<?php
namespace VMBDataExport\Export;

class XLSExport extends Export
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

        $path = __DIR__ . '/../../../../../../public/xls/';
        if (!is_dir($path)) {
            throw new \Exception("Please make sure that 'public/xls' directory exists");
        }

        $fileName = str_replace("/", ".", $this->getFilePrefix() . '_' . date("Y-m-d-H-i-s") . '.xls');

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
        $html .= "</table>";

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