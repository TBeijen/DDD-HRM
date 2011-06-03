<?php
// bootstrap Doctrine
require_once '../bootstrap-doctrine.php';

// setup in-memory database
$unitTestConnectionOptions = array(
    'driver' => 'pdo_sqlite',
    'path' => ':memory:'
);

// provide configuration to BaseTestCase
Test\BaseTestCase::setConfiguration($unitTestConnectionOptions, $config);
