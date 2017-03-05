<?php
namespace VMBDataExport;

use VMBDataExport\Service\CustomExportService;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ControllerProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use VMBDataExport\Service\MainService;

/**
 * Class Module
 * @package VMBDataExport
 * @author Matias Iglesias <matiasiglesias@meridiem.com.ar>
 */
class Module implements
    AutoloaderProviderInterface,
    ConfigProviderInterface,
    ControllerProviderInterface
{
    /**
     * @param MvcEvent $e
     */
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return include __DIR__ . '../../../config/module.config.php';
    }

    /**
     * @return array|\Zend\ServiceManager\Config
     */
    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ],
            ],
        ];
    }

    /**
     * @return array|\Zend\ServiceManager\Config
     */
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'VMBDataExport\Service\MainService' => function ($sm) {
                    return new MainService($sm->get('Doctrine\ORM\EntityManager'));
                },
                'VMBDataExport\Service\CustomExportService' => function ($sm) {
                    return new CustomExportService($sm->get('Doctrine\ORM\EntityManager'));
                }
            ),
        );
    }

    /**
     * @return array|\Zend\ServiceManager\Config
     */
    public function getControllerConfig()
    {
        return array(
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
    }


}
