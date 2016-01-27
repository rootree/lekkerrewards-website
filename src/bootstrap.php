<?php
// bootstrap.php
require_once "vendor/autoload.php";

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$paths = array("module/Application/src/Application/Entity");
$isDevMode = false;

// the connection configuration
$dbParams = array(
    'driver'   => 'pdo_mysql',
    'host' => '127.0.0.1',
    'port' => '8889',
    'user' => 'root',
    'password' => '111111',
    'dbname' => 'loyalty',
);

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, null, null, false);
$entityManager = EntityManager::create($dbParams, $config);