<?php

namespace Cache\Factory\Adapter;

use Cache\Factory\Config\Adapter\Memcached as Config;

class Memcached extends AbstractAdapter
{
    protected function getConfiguredDriver(array $config)
    {
        $memcached = $this->getMemcachedInstance();

        foreach ($config[Config::INDEX_SERVERS] as $server) {
            $memcached->addServer(
                $server[Config::INDEX_HOST],
                $server[Config::INDEX_PORT]
            );
        }

        if (!empty($config[Config::INDEX_OPTIONS])) {
            $memcached->setOptions($config[Config::INDEX_OPTIONS]);
        }

        return $memcached;
    }

    protected function getMemcachedInstance()
    {
        return new \Memcached();
    }
}
