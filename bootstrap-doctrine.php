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

// configure doctrine
$cacheImpl = new \Doctrine\Common\Cache\ArrayCache;
$config = new Doctrine\ORM\Configuration;
$driverImpl = $config->newDefaultAnnotationDriver($domainRoot . '/Entity');
$config->setProxyDir($domainRoot  . '/Entity/Proxy');
$config->setProxyNamespace('Domain\Entity\Proxy');
$config->setMetadataDriverImpl($driverImpl);
$config->setMetadataCacheImpl($cacheImpl);
$config->setQueryCacheImpl($cacheImpl);
$config->setAutoGenerateProxyClasses(true);

$connectionOptions = array(
    'driver' => 'pdo_sqlite',
    'path' => ':memory:'
);

$em = \Doctrine\ORM\EntityManager::create($connectionOptions, $config);
