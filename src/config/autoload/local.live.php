<?php

return array(
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'params' => array(
                    'host'     => 'localhost',
                    'port'     => '3306',
                    'user'     => 'root',
                    'password' => '111111',
                    'dbname'   => 'loyalty',
                ))))
    ,
    'company' => array(
        'baseHost' => 'http://lekkerrewards.nl/',
    ),
    'store' => [
        'path' => '/var/websites/lekkerrewards.nl/public/static/',
        'url-static' => 'http://lekkerrewards.nl/static/',
        'type' => [
            'merchant-logo' => 'merchant-logo',
        ],
    ],
);
