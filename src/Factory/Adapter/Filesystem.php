<?php

namespace Cache\Factory\Adapter;

use Cache\Factory\Config\Adapter\Filesystem as Config;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem as Flysystem;

class Filesystem extends AbstractAdapter
{
    protected function getConfiguredDriver(array $config)
    {
        $localFsAdapter = new Local($config[Config::INDEX_PATH]);

        return new Flysystem($localFsAdapter);
    }
}
