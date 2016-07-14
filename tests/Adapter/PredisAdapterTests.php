<?php

use Cache\Factory\Adapter\Predis;
use Cache\Factory\Config\Adapter\AdapterInterface as Config;
use Cache\Factory\Config\Loader;

class PredisAdapterTests extends PHPUnit_Framework_TestCase
{
    /**
     * @var string Name of the adapter in the configuration
     */
    protected $adapterName = 'redis';

    /**
     * @var string Type of the cache pool adapter
     */
    protected $adapterType = 'Predis';

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
     * Tests creation of the redis cache item pool
     */
    public function testMake()
    {
        $redisAdapterFactory = new Predis();
        $redisCacheItemPool  = $redisAdapterFactory->make($this->config);

        $this->assertInstanceOf('Psr\\Cache\\CacheItemPoolInterface', $redisCacheItemPool);
        $this->assertInstanceOf('\\Cache\\Adapter\\Predis\\PredisCachePool', $redisCacheItemPool);
    }
}
