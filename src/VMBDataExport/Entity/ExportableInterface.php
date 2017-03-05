<?php
namespace VMBDataExport\Entity;

interface ExportableInterface
{
    /**
     * Array representation of Doctrine Entity
     * @return array
     */
    function toArray();
}