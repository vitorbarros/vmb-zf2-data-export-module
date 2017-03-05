<?php
namespace VMBDataExport;

use VMBDataExport\Controller\DataExportController;

return array(
    'router' => array(
        'routes' => array(
            'export' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/export',
                    'defaults' => array(
                        'controller' => 'data-export',
                        'action' => 'export',
                    ),
                ),
            ),
        ),
    ),
);
