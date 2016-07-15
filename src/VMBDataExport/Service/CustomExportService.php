<?php
namespace VMBDataExport\Service;

use Doctrine\ORM\EntityManager;
use VMBDataExport\Export\ExportDataServiceInterface;

class CustomExportService
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var ExportDataServiceInterface
     */
    private $class;

    /**
     * CustomExportService constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param $query
     * @param array $headerFields
     * @param $exportClass
     * @return bool|mixed
     * @throws \Exception
     */
    public function export($query, array $headerFields, $exportClass)
    {
        $result = $this->em->createQuery($query)->getArrayResult();
        
        $type = strtoupper($exportClass);
        $class = 'VMBDataExport\\Export\\' . $type . 'Export';

        if (class_exists($class)) {
            if (null === $this->class) {
                $this->class = new $class($this->em);
            }
            if ($this->class instanceof ExportDataServiceInterface) {
                $this->class->writeCustomData($result, $headerFields);
                return $this->class->export();
            }
            throw new \Exception("Class {$class} must implements 'VMBDataExport\\Export\\ExportDataServiceInterface' ");
        }
        throw new \Exception("Class {$class} does not exist");
    }
}