<?php
namespace Application;

use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Cache\ZendStorageCache;
use Application\Service\ErrorHandling as ErrorHandlingService;
use Zend\Log\Logger as ZendLogLogger;
use Zend\Log\Writer\Stream as LogWriterStream;
use Application\Service as Service;
use Application\Service\AuthenticationService as AuthenticationService;
use Application\Adapter\ObjectRepository;
use Zend\Session\Container; // We need this when using sessions

return array(
    'factories' => array(
        'Application\Service\Locations' => function (ServiceLocatorInterface $sl) {
            $em = $sl->get('Doctrine\ORM\EntityManager');
            return new Service\Locations($em);
        },
        'Application\Service\Qr' => function (ServiceLocatorInterface $sl) {
            $em = $sl->get('Doctrine\ORM\EntityManager');
            return new Service\Qr($em);
        },
        'Application\Service\Merchant' => function (ServiceLocatorInterface $sl) {
            $em = $sl->get('Doctrine\ORM\EntityManager');
            return new Service\Merchant($em);
        },
        'Application\Service\Customer' => function (ServiceLocatorInterface $sl) {
            $em = $sl->get('Doctrine\ORM\EntityManager');
            $emailService = $sl->get('Application\Service\Email');
            $qrService = $sl->get('Application\Service\Qr');
            return new Service\Customer($em, $emailService, $qrService);
        },
        'Application\Service\Owner' => function (ServiceLocatorInterface $sl) {
            $em = $sl->get('Doctrine\ORM\EntityManager');
            $emailService = $sl->get('Application\Service\Email');
            return new Service\Owner($em, $emailService);
        },
        'Application\Service\Dictionary' => function (ServiceLocatorInterface $sl) {
            $em = $sl->get('Doctrine\ORM\EntityManager');
            return new Service\Dictionary($em);
        },
        'Application\Service\API' => function (ServiceLocatorInterface $sl) {
            $em = $sl->get('Doctrine\ORM\EntityManager');
            return new Service\API($em);
        },
        'Application\Service\Statistics' => function (ServiceLocatorInterface $sl) {
            $em = $sl->get('Doctrine\ORM\EntityManager');
            return new Service\Statistics($em);
        },
        'Application\Service\Visit' => function (ServiceLocatorInterface $sl) {
            $em = $sl->get('Doctrine\ORM\EntityManager');
            return new Service\Visit($em);
        },
        'Application\Service\Email' => function (ServiceLocatorInterface $sl) {
            $viewRenderer = $sl->get('ViewRenderer');
            $config = $sl->get('config');
            $translator = $sl->get('translator');
            return new Service\Email($translator, $config, $viewRenderer);
        },
        'doctrine.cache.filesystem' => function (ServiceLocatorInterface $sm) {
            return new ZendStorageCache($sm->get('cache.filesystem'));
        },
        'Zend\Authentication\CustomerService' => function ($serviceManager) {
/*
            $entityManager = $serviceManager->get('doctrine.entitymanager.orm_default');
            $doctrineAuthAdapter = new ObjectRepository(array(
                'objectManager' => $entityManager,
                'identityClass' => 'Application\Model\Entity\Customer',
                'identityProperty' => 'eMail',
                'credentialProperty' => 'password',
                'credentialCallable' => function ($userObj, $password) {
                    return ($userObj->getPassword() === md5($password));
                }, // this function makes the password hash salted
                // you could also just use return md5($credential);
            ));

            // my AuthenticationService uses the entity manager
            // and the ObjectRepository
            $authService = new AuthenticationService();
            $authService->setEntityManager($entityManager);
            $authService->setAdapter($doctrineAuthAdapter);

            return $authService;
*/


            // If you are using DoctrineORMModule:
            return $serviceManager->get('doctrine.authenticationservice.orm_default');
        },
        'Zend\Authentication\BusinessService' => function ($serviceManager) {
            // If you are using DoctrineORMModule
            return $serviceManager->get('doctrine.authenticationservice.business');
        },
        'ApplicationServiceErrorHandling' => function ($sm) {
            $logger = $sm->get('ZendLog');
            $service = new ErrorHandlingService($logger);
            return $service;
        },
        'doctrine.authenticationservice.business' => function ($sm) {

            $entityManager = clone $sm->get('doctrine.entitymanager.orm_default');
            $doctrineAuthAdapter = new ObjectRepository(array(
                'objectManager' => $entityManager,
                'identityClass' => 'Application\Entity\Owner',
                'identityProperty' => 'eMail',
                'credentialProperty' => 'password',
                'credentialCallable' => function ($userObj, $password) {
                    return ($userObj->getPassword() === md5($password));
                }, // this function makes the password hash salted
                // you could also just use return md5($credential);
            ));

            // my AuthenticationService uses the entity manager
            // and the ObjectRepository
            $authService = new AuthenticationService();
            $authService->setEntityManager($entityManager);
            $authService->setAdapter($doctrineAuthAdapter);

            return $authService;
        },
        'Application\Service\Store' => function (ServiceLocatorInterface $sl) {
            $storeConfig = $sl->get('config');
            return new \Application\Service\Store($storeConfig['store']);
        },
        'ZendLog' => function ($sm) {
            $config = $sm->get('config');
            $filename = $config['logs']['filename'];
            $log = new ZendLogLogger();
            $writer = new LogWriterStream($config['logs']['path'] . $filename);
            $log->addWriter($writer);
            return $log;
        },
    )
);