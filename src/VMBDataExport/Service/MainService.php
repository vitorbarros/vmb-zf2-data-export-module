<?php
namespace VMBDataExport\Service;

use Doctrine\ORM\EntityManager;
use VMBDataExport\Export;

class MainService
{

    /**
     * @var EntityManager
     */
    protected $em;
    /**
     * @var string
     */
    protected $responsableClass;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function strategy($exportType, array $data)
    {
        if ($exportType != null) {

            $type = strtoupper($exportType);
            $class = 'VMBDataExport\\Export\\' . $type . 'Export';

            if (class_exists($class)) {

                if (null === $this->responsableClass) {
                    $this->responsableClass = new $class($this->em);
                }
                if ($this->responsableClass instanceof Export\ExportDataServiceInterface) {
                    if (isset($data['custom']) && $data['custom'] != null) {
                        $this->responsableClass->writeCustomData($data);
                        return $this->responsableClass->exportCustomData();
                    }
                    $this->responsableClass->writeData($data);
                    return $this->responsableClass->export();
                }
                throw new \Exception("Class {$class} must implements 'VMBDataExport\\Export\\ExportDataServiceInterface' ");
            }
            throw new \Exception("Class {$class} does not exist");
        }
        throw new \Exception("Type can not be null");
    }
}