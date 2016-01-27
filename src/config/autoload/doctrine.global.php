<?php
namespace Application;

return array(
    'doctrine' => array(
        'driver' => array(
            'annotation_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
            ),
        ),
        'authentication' => array(
            'orm_default' => array(
                'object_manager' => 'Doctrine\ORM\EntityManager',
                'identity_class' => 'Application\Model\Entity\Customer',
                'identity_property' => 'eMail',
                'credential_property' => 'password',
                'credentialCallable' => function ($userObj, $password) {

                    return ($userObj->getPassword() === md5($password));
                }
            ),
        ),
        'configuration' => array(
            'orm_default' => array(
                'metadata_cache' => 'array',
                'query_cache' => 'array',
                'result_cache' => 'array'
            )
        ),
    ),
    'doctrine_factories' => array(
        'authenticationadapter' => 'Application\Factory\Authentication\AdapterFactory',
    ),
);