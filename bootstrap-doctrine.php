<?php
require_once __DIR__ . '/lib/doctrine-orm/Doctrine/Common/ClassLoader.php';

// root path of Domain classes
$domainRoot = __DIR__ . '/domain';

// configure class loader
$classLoader = new \Doctrine\Common\ClassLoader('Doctrine', __DIR__ . '/lib/doctrine-orm');
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Symfony', __DIR__ . '/lib/doctrine-orm/Doctrine');
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Domain', __DIR__);
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Test', __DIR__);
$classLoader->register();

// configure doctrine
$cacheImpl = new \Doctrine\Common\Cache\ArrayCache;
$config = new Doctrine\ORM\Configuration;
$driverImpl = $config->newDefaultAnnotationDriver($domainRoot . '/Entity');
$config->setProxyDir($domainRoot  . '/Proxy');
$config->setProxyNamespace('Domain\Proxy');
$config->setMetadataDriverImpl($driverImpl);
$config->setMetadataCacheImpl($cacheImpl);
$config->setQueryCacheImpl($cacheImpl);
$config->setAutoGenerateProxyClasses(true);

$connectionOptions = array(
    'dbname' => 'ddd_hrm',
    'user' => 'ddd_hrm',
    'password' => 'ddd_hrm',
    'host' => 'localhost',
    'driver' => 'pdo_mysql',
);