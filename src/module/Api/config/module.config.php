<?php
return array(
    'router' => array(
        'routes' => array(
            'api' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/api/:action/:api-key/',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'api-key' => '[a-zA-Z][a-zA-Z0-9]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Api\Controller\Api',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(),
    'controllers' => array(
        'invokables' => array(
            'Api\Controller\Api' => 'Api\Controller\ApiController',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => [
            'ViewJsonStrategy'
        ]
    ),
);