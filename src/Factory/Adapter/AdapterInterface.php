<?php

namespace Cache\Factory\Adapter;

use Psr\Cache\CacheItemPoolInterface;

interface AdapterInterface
{
    /**
     * Creates and returns a new instance of desired cache adapter wrapped into the right Cache Item Pool implementation
     *
     * @param array $config The configuration array
     *
     * @return CacheItemPoolInterface
     */
    public function make(array $config);
}
