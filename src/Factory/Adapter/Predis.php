<?php

namespace Cache\Factory\Adapter;

use Cache\Factory\Config\Adapter\Predis as Config;
use Predis\Client;

class Predis extends AbstractAdapter
{
    protected function getConfiguredDriver(array $config)
    {
        $options = (!empty($config[Config::INDEX_OPTIONS])) ? $config[Config::INDEX_OPTIONS] : null;

        return $this->getPredisInstance($config, $options);
    }

    protected function getPredisInstance($config, $options)
    {
        return new Client($config[Config::INDEX_SERVERS], $options);
    }
}
