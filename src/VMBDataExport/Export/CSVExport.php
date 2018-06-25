<?php
namespace VMBDataExport\Export;

use Zend\Math\Rand;

class CSVExport extends Export
{
    public function writeData(array $dados)
    {
        $entityData = $this->getEntityData($dados);

        foreach ($entityData as $data) {
            $arrayData = $data->toArray();
            foreach ($this->headers as $head) {
                $resultFormated['result'][$head] = $arrayData[$head];
            }

            $result[] = $resultFormated['result'];
        }

        $this->resultFormatedData = $result;

    }

    /**
     * @return mixed|string
     * @throws \Exception
     */
    public function export()
    {
        $path = __DIR__ . '/../../../../../../public/csv/';
        if (!is_dir($path)) {
            throw new \Exception("Please make sure that 'public/csv' directory exists");
        }
        $fileName = str_replace("/", ".", $this->getFilePrefix() . '_' . date("Y-m-d-H-i-s") . '.csv');
        $file = fopen($path . $fileName, "w");

        fputcsv($file, $this->headers);
        foreach ($this->resultFormatedData as $data) {
            fputcsv($file, $data);
        }

        fclose($file);
        return '/csv/' . $fileName;
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