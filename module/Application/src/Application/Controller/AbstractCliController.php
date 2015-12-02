<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController as ZendAbstractActionController;
use Zend\Mvc\MvcEvent;

abstract class AbstractCliController extends ZendAbstractActionController
{
    /**
     * @inheritDoc
     */
    public function onDispatch(MvcEvent $event)
    {

        $config = $event->getApplication()->getConfig();

        setlocale(LC_ALL, $config['locale']['default']);
        date_default_timezone_set($config['company']['timezone']);

        try {
            $actionResponse = parent::onDispatch($event);
        } catch (\Exception $e) {
            $sm = $event->getApplication()->getServiceManager();
            $service = $sm->get('ApplicationServiceErrorHandling');
            $service->logException($e);
            $actionResponse = $e->getMessage();

            echo $e . PHP_EOL;
        }
        return $actionResponse;
    }
}
