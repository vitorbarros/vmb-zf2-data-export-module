<?php


namespace VMBDataExport\Export;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\Entity;
use VMBDataExport\Entity\ExportableInterface;
use Zend\Json\Json;
use Zend\Math\Rand;

/**
 * Class Export
 * @package VMBDataExport\Export
 * @author Matias Iglesias <matiasiglesias@meridiem.com.ar>
 */
abstract class Export implements ExportDataServiceInterface
{
    /**
     * Doctrine Entity Manager
     * @var EntityManager $em
     */
    protected $em;

    /**
     * Exportable Entity
     * @var string $entity
     */
    protected $entity;

    /**
     * Entity Repository
     * @var EntityRepository $entityRepository
     */
    protected $entityRepository;

    /**
     * Criteria
     * @var array $criteria
     */
    protected $criteria;

    /**
     * Column Headers
     * @var array $headers
     */
    protected $headers;

    /**
     * Custom Repository method
     * If not set findAll or findBy will be called depending if criteria is set or not
     * @var string $method
     */
    protected $method;

    /**
     * Formated data to export
     * @var  array $resultFormatedData
     */
    protected $resultFormatedData;

    /**
     * Result file prefix
     * If not set it will be a 6 letter Random filename
     * @var string $filePrefix
     */
    protected $filePrefix;

    /**
     * Export constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Sets Configuration based on config array
     * @param array $config
     * @throws \BadMethodCallException
     */
    private  function setConfig($config) {
        if (!array_key_exists('entity', $config)) {
            throw new \BadMethodCallException('You mush especify a Doctrine Entity to Export');
        }
        $this->entity = $config['entity'];
        if (!class_exists($this->entity)) {
            throw new \BadMethodCallException("Entity {$this->entity} does not exists");
        }
        if ($this->entity instanceof ExportableInterface) {
            throw new \BadMethodCallException(
                "Exportable Entity {$this->entity} must implements 'VMBDataExport\\Entity\\ExportableInterface'"
            );
        }
        $this->criteria = Json::decode($config['criteria'], Json::TYPE_ARRAY);
        $this->headers = Json::decode($config['headers'], Json::TYPE_ARRAY);
        $this->method = isset($config['method'])?$config['method']:null;
        $this->entityRepository = $this->em->getRepository($this->entity);
        if (!empty($this->method)) {
            if (!method_exists($this->entityRepository, $this->method)) {
                throw new \BadMethodCallException(
                    "Method {$this->method} does not exists on {$this->entity} Repository"
                );
            }
        }
        if (array_key_exists('filePrefix', $config)) {
            $this->filePrefix = $config['filePrefix'];
        }
    }

    /**
     * Return Entity data based on $config
     * @param array $config
     * @return array
     */
    protected function getEntityData($config) {
        $this->setConfig($config);

        /** @var array $entityData */
        $entityData = [];

        if (!empty($this->method)) {
            $entityData = call_user_func([
                $this->entityRepository,
                $this->method
            ],
                $this->criteria
            );
        } else {
            if (empty($this->criteria)) {
                $entityData = $this->entityRepository->findAll();
            } else {
                $entityData = $this->entityRepository->findBy($this->criteria);
            }
        }
        return $entityData;
    }

    protected function getFilePrefix() {
        if (!empty($this->filePrefix)) {
            return $this->filePrefix;
        } else {
            return base64_encode(Rand::getBytes(6, true));
        }
    }
}