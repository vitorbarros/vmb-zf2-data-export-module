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
    protected $responsableClass;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    /**
     * Método que chama a clase responsavel pela exportação dos dados de acordo com o tipo informado
     * @author Vitor Barros
     * @param $exportType
     * @param $dados
     * @throws \Exception
     */
    public function strategy($exportType, array $dados) {

        if($exportType != null) {

            $type = strtoupper($exportType);
            $class = 'DataExport\\Export\\'.$type.'Export';

            if(class_exists($class)) {

                if(null === $this->responsableClass) {
                    $this->responsableClass = new $class($this->em);
                }

                if($this->responsableClass instanceof Export\ExportDataServiceInterface) {

                    $this->responsableClass->writeData($dados);
                    return $this->responsableClass->export();

                }else
                    throw new \Exception("Class {$class} must implements 'DataExport\\Export\\ExportDataServiceInterface' ");

            }else
                throw new \Exception("Class {$class} does not exist");

        }else
            throw new \Exception("Type can not be null");

    }

}