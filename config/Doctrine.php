<?php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once "vendor/autoload.php";

// Create a simple "default" Doctrine ORM configuration for Annotations
$isDevMode = true;
$proxyDir = null;
$cache = null;
$useSimpleAnnotationReader = true;
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/App/Db"), $isDevMode, $proxyDir, $cache, $useSimpleAnnotationReader);
// or if you prefer yaml or XML
//$config = Setup::createXMLMetadataConfiguration(array(__DIR__."/config/xml"), $isDevMode);
//$config = Setup::createYAMLMetadataConfiguration(array(__DIR__."/config/yaml"), $isDevMode);

// database configuration parameters
$conn = array(
    'dbname'   => 'memory_lane',
    'host'     => 'localhost',
    'password' => 'mpmegmrx',
    'user'     => 'postgres',
    'driver'   => 'pdo_pgsql'
);

// obtaining the entity manager
$entityManager = EntityManager::create($conn, $config);