<?php

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Symfony\Component\Cache\Adapter\ArrayAdapter;


require_once __DIR__ . '/vendor/autoload.php';

function getEntityManager(): EntityManager
{
    $config = ORMSetup::createAttributeMetadataConfiguration(
        paths: [__DIR__ . '/entity'],
        isDevMode: true,
        proxyDir: dirname(__DIR__) . '/var/cache',
        cache: new ArrayAdapter()
    );
    $config->setProxyNamespace('Cache\Proxies');

    $connection = DriverManager::getConnection([
        'user' => 'root',
        'dbname' => 'myproject_schedule_oop',
        'password' => '',
        'host' => 'localhost',
        'driver' => 'pdo_mysql',
        'charset' => 'UTF8',
        'port' => 3306,
    ], $config);

    return new EntityManager($connection, $config);
}

