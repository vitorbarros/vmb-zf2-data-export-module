<?php
namespace VMBDataExport\Export;

use Doctrine\ORM\EntityManager;
use Zend\Json\Json;
use Zend\Math\Rand;

class CSVExport implements ExportDataServiceInterface
{

    /**
     * @var EntityManager
     */
    private $em;

    private $resultFormatedData;
    private $headers;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function writeData(array $dados)
    {

        $entity = null;
        $criteria = Json::decode($dados['criteria'], Json::TYPE_ARRAY);
        $this->headers = Json::decode($dados['headers'], Json::TYPE_ARRAY);
        $entity = $dados['entity'];
        $sql = null;
        $result = array();
        $resultFormated = array();
        $entityData = array();

        if (empty($criteria)) {
            $entityData = $this->em->getRepository($entity)->findAll();
        } else {
            $entityData = $this->em->getRepository($entity)->findBy($criteria);
        }

        foreach ($entityData as $data) {
            $arrayData = $data->toArray();
            foreach ($this->headers as $head) {
                $resultFormated['result'][$head] = $arrayData[$head];
            }

            $result[] = $resultFormated['result'];
        }

        $this->resultFormatedData = $result;

    }

    public function export()
    {

        $path = __DIR__ . '/../../../../../../public/csv/';
        if (!is_dir($path)) {
            throw new \Exception("Please make sure that 'public/csv' directory exists");
        }
        $fileName = str_replace("/", ".", base64_encode(Rand::getBytes(6, true)) . '_' . date("Y-m-d-H:i:s") . '.csv');
        $file = fopen($path . $fileName, "w");

        fputcsv($file, $this->headers);
        foreach ($this->resultFormatedData as $data) {
            fputcsv($file, $data);
        }

        fclose($file);
        return '/csv/' . $fileName;

    }

}