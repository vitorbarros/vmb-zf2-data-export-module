<?php
namespace VMBDataExport\Export;

use Doctrine\ORM\EntityManager;

class PDFExport implements ExportDataServiceInterface
{

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function writeData(array $dados)
    {
        //TODO implement this action
    }

    public function export()
    {
        //TODO implement this action
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function writeCustomData(array $data)
    {
        // TODO: Implement writeCustomData() method.
    }

    /**
     * @return mixed
     */
    public function exportCustomData()
    {
        // TODO: Implement exportCustomData() method.
    }
}