<?php
namespace VMBDataExport\Export;

use Doctrine\ORM\EntityManager;
use Zend\Json\Json;
use Zend\Math\Rand;

class XLSExport implements ExportDataServiceInterface
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

        $path = __DIR__ . '/../../../../../../public/xls/';
        if (!is_dir($path)) {
            throw new \Exception("Please make sure that 'public/xls' directory exists");
        }

        $fileName = str_replace("/", ".", base64_encode(Rand::getBytes(6, true)) . '_' . date("Y-m-d-H:i:s") . '.xls');

        $file = fopen($path . $fileName, "w");
        $count = 0;

        $html = null;
        $html .= "<table>";
        $html .= "<tr>";
        foreach ($this->headers as $headers) {
            $html .= "<th>{$headers}</th>";
        }
        $html .= "</tr>";
        foreach ($this->resultFormatedData as $data) {
            foreach ($data as $colunas) {
                $count++;
                if ($count == 0) {
                    $html .= "<tr>";
                }
                $html .= "<td>{$colunas}</td>";
                if ($count == count($this->headers)) {
                    $html .= "</tr>";
                    $count = 0;
                }
            }
        }

        fputs($file, utf8_decode($html));
        fclose($file);
        return '/xls/' . $fileName;
    }

    /**
     * @param array $data
     * @param array $headers
     * @return mixed
     */
    public function writeCustomData(array $data, array $headers)
    {
        $this->resultFormatedData = $data;
        $this->headers = $headers;
        return $this;
    }
}