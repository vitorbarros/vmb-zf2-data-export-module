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
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity'),
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver',
                ),
            ),
        ),
    ),
);
