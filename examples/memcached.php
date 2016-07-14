<?php

require_once __DIR__ . '/common/bootstrap.php';

$cachePool = $cachePoolFactory->make('memcached');

require_once __DIR__ . '/common/execute.php';
