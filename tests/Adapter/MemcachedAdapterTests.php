<?php

use Cache\Factory\Adapter\Memcached;
use Cache\Factory\Config\Adapter\AdapterInterface as Config;
use Cache\Factory\Config\Loader;

class MemcachedAdapterTests extends PHPUnit_Framework_TestCase
{
    /**
     * @var string Name of the adapter in the configuration
     */
    protected $adapterName = 'memcached';

    /**
     * @var string Type of the cache pool adapter
     */
    protected $adapterType = 'Memcached';

    /**
     * @var string Path to the config file
     */
    protected $configFile;

    /**
     * @var array
     */
    protected $config;

    /**
     * Sets the config file
     */
    protected function setUp()
    {
        $this->configFile = __DIR__ . '/../cache.yml';

        $configLoader = new Loader();
        $config       = $configLoader->load($this->configFile);

        $configLoader->setAdapterName($this->adapterType);

        $processedConfiguration = $configLoader->process($this->adapterName, $config);
        $this->config           = $processedConfiguration[Config::INDEX_ADAPTER][$this->adapterName];
    }

    /**
     * Tests creation of the memcached cache item pool
     */
    public function testMake()
    {
        $memcachedAdapterFactory = new Memcached();
        $memcachedCacheItemPool  = $memcachedAdapterFactory->make($this->config);

        $this->assertInstanceOf('Psr\\Cache\\CacheItemPoolInterface', $memcachedCacheItemPool);
        $this->assertInstanceOf('\\Cache\\Adapter\\Memcached\\MemcachedCachePool', $memcachedCacheItemPool);
    }
}
