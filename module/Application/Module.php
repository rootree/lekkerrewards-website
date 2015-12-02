<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {

        $config = $e->getApplication()->getConfig();

        setlocale(LC_ALL, $config['locale']['default']);
        date_default_timezone_set($config['company']['timezone']);

        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);


        $viewModel = $e->getApplication()->getMvcEvent()->getViewModel();
        $viewModel->company = $config['company'];

        // Zend\Session\Container
        $session = New Container('language');
        $language = $session->language ? $session->language : $config['locale']['default'];
        $viewModel->language = substr($language, 0, 2);

        $application = $e->getApplication();
        $serviceManager = $application->getServiceManager();
        // Just a call to the translator, nothing special!
        $serviceManager->get('translator');
        $this->initTranslator($e);
    }

    protected function initTranslator(MvcEvent $event)
    {
        $serviceManager = $event->getApplication()->getServiceManager();

        // Zend\Session\Container
        $session = New Container('language');

        $config = $event->getApplication()->getConfig();

        $translator = $serviceManager->get('translator');
        $translator
            ->setLocale($session->language)
            ->setFallbackLocale($config['locale']['default']);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
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

    public function getServiceConfig()
    {
        return include __DIR__ . '/config/service.config.php';
    }
}
