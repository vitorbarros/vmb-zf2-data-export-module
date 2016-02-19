<?php
namespace VMBDataExport;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use VMBDataExport\Service\MainService;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '../../../config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    public function getServiceConfig() {
    
        return array(
    
            'factories' => array(
                'VMBDataExport\Service\MainService' => function($sm) {
                    return new MainService($sm->get('Doctrine\ORM\EntityManager'));
                }
            ),
    
        );
    
    }
    
}
