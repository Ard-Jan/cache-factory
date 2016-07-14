<?php

date_default_timezone_set('Europe/Berlin');

const CACHE_KEY   = 'test_key';
const CACHE_VALUE = 'test_value';
const CACHE_TTL   = 2;

require_once __DIR__ . '/../../vendor/autoload.php';

use Cache\Factory\Factory;

$cachePoolFactory = new Factory();
$cachePoolFactory->setConfigFile(__DIR__ . '/../config/cache.yml');
