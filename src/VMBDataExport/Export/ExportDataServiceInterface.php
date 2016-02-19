<?php
namespace VMBDataExport\Export;

interface ExportDataServiceInterface
{

    /**
     * Método que deve ser implementado para os serviços que farão exportação de dados
     * @author Vitor Barros
     * @return mixed
     */
    public function export();

    /**
     * Método que deve ser implementado para gravar os dados no tipo de arquivo selecionado
     * @author Vitor Barros
     * @return mixed
     */
    public function writeData(array $dados);

}