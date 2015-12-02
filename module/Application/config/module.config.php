<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Application;

return array(
    'doctrine' => array(
        'driver' => array(
            'annotation_driver' => array(
                'paths' => array(
                    __DIR__ . '/../src/' . __NAMESPACE__ . '/Model/Entity',
                    __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity',
                ),
            ),
            'orm_default' => array(
                'drivers' => array(
                    // our entities
                    __NAMESPACE__ . '\Model\Entity' => 'annotation_driver',
                    // auto-generated doctrine entities
                    __NAMESPACE__ . '\Entity' => 'annotation_driver',
                ),
            ),
        )
    ),

    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'locations' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/locations/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'locations',
                    ),
                ),
            ),
            'about' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/about/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'about',
                    ),
                ),
            ),
            'privacy-policy' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/privacy-policy/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'privacyPolicy',
                    ),
                ),
            ),
            'terms-of-use' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/terms-of-use/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'termsOfUse',
                    ),
                ),
            ),
            'change-language' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/change-language/:language/',
                    'constraints' => array(

                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'changeLanguage',
                    ),
                ),
            ),
            'support' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/support/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'support',
                    ),
                ),
            ),

            'customer' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/customer/[:action/][:merchant/]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'merchant'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Customer',
                        'action'     => 'index',
                    ),
                ),
            ),
            'customer-activity' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/customer/activity/:merchant-name/',
                    'constraints' => array(
                        'merchant-name'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Customer',
                        'action'     => 'activity',
                    ),
                ),
            ),



            'merchant-logo' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/merchant/logo/:id/',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Merchant',
                        'action'     => 'logo',
                    ),
                ),
            ),

            'merchant' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/merchant/:merchant-name/',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Merchant',
                        'action'     => 'index',
                    ),
                ),
            ),

            'ajax' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/ajax/:action/',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Ajax',
                        'action'     => 'index',
                    ),
                ),
            ),

            'business' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/business/[:action/][:id/]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Business',
                        'action'     => 'index',
                    ),
                ),
            ),

            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            /*'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),*/
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory'
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Ajax' => 'Application\Controller\AjaxController',
            'Application\Controller\Index' => 'Application\Controller\IndexController',
            'Application\Controller\Business' => 'Application\Controller\BusinessController',
            'Application\Controller\Merchant' => 'Application\Controller\MerchantController',
            'Application\Controller\Customer' => 'Application\Controller\CustomerController',
            'Application\Controller\Cli' => 'Application\Controller\CliController'
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),

    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
                'generate-qrs' => array(
                    'options' => array(
                        'route'    => 'generate-qrs <qty>',
                        'defaults' => array(
                            'controller' => __NAMESPACE__ . '\Controller\Cli',
                            'action'     => 'generateQrs',
                            'qty'     => '[0-9]+',
                        ),
                    ),
                ),
            ),
        ),
    ),
);
