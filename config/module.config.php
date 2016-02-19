<?php
namespace VMBDataExport;

return array(
    'router' => array(
        'routes' => array(
            'export' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/export',
                    'defaults' => array(
                        'controller' => 'data-export',
                        'action'     => 'export',
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'data-export' => 'VMBDataExport\Controller\DataExportController',
        ),
    ),
    'doctrine'  =>  array(
        'driver'    =>  array(
            __NAMESPACE__ . '_driver'  =>  array(
                'class' =>  'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' =>  'array',
                'paths' =>  array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity'),
            ),
            'orm_default'   =>  array(
                'drivers'   =>  array(
                    __NAMESPACE__ . '\Entity'   =>  __NAMESPACE__ . '_driver',
                ),
            ),
        ),
    ),
);
