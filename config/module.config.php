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
    'controllers' => array(
        'factories' => array(
            'data-export' => function ($sm) {
                return new DataExportController(
                    $sm->getServiceLocator()->get('VMBDataExport\Service\MainService')
                );
            }
        ),
    ),
);
