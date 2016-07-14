<?php
namespace VMBDataExport\Export;

interface ExportDataServiceInterface
{
    /**
     * @return mixed
     */
    public function export();

    /**
     * @param array $data
     * @return mixed
     */
    public function writeData(array $data);

    /**
     * @param array $data
     * @return mixed
     */
    public function writeCustomData(array $data);

    /**
     * @return mixed
     */
    public function exportCustomData();
}