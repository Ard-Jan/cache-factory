<?php

use Cache\Factory\Config\Adapter\AdapterInterface as Config;
use Cache\Factory\Config\Adapter\Filesystem;
use Cache\Factory\Factory;
use Cache\TagInterop\TaggableCacheItemPoolInterface;
use Psr\Cache\CacheItemPoolInterface;

class FactoryTests extends PHPUnit_Framework_TestCase
{
    protected $adapterName = 'local';

    protected $adapterType = 'Filesystem';

    /**
     * Returns valid configuration(s).
     *
     * @return array
     */
    public function validConfigurationDataProvider()
    {
        $config1 = array(
            Config::INDEX_CACHE => array(
                Config::INDEX_ADAPTER => array(
                    $this->adapterName => array(
                        Config::INDEX_TYPE     => $this->adapterType,
                        Filesystem::INDEX_PATH => __DIR__,
                    )
                )
            )
        );

        return array(array($config1));
    }

    /**
     * Returns invalid configuration(s).
     *
     * @return array
     */
    public function invalidConfigurationDataProvider()
    {
        $config1 = array(
            Config::INDEX_CACHE => array(
                Config::INDEX_ADAPTER => array(
                    $this->adapterName => array(
                        'typo' => $this->adapterType,
                    )
                )
            )
        );
        $config2 = array(
            Config::INDEX_CACHE => array(
                'adaptor' => array(
                    $this->adapterName => array(
                        Config::INDEX_TYPE => $this->adapterType,
                    )
                )
            )
        );
        $config3 = array(
            'Cahce' => array(
                Config::INDEX_ADAPTER => array(
                    $this->adapterName => array(
                        Config::INDEX_TYPE => $this->adapterType,
                    )
                )
            )
        );

        return array(
            array($config1),
            array($config2),
            array($config3),
        );
    }

    /**
     * Tests that the factory can create a proper PSR-6 compatible Cache Pool based on valid configuration.
     *
     * @param array $config Adapter configuration
     *
     * @dataProvider validConfigurationDataProvider
     */
    public function testThatFactoryWorksWithValidConfig(array $config)
    {
        $cachePoolFactory = new Factory();

        $cachePoolFactory->setConfig($config);

        $cachePool         = $cachePoolFactory->make($this->adapterName);
        $taggableCachePool = $cachePoolFactory->makeTaggable($this->adapterName);

        $this->assertInstanceOf(CacheItemPoolInterface::class, $cachePool);
        $this->assertInstanceOf(TaggableCacheItemPoolInterface::class, $taggableCachePool);
    }

    /**
     * Tests that the factory exception when no config array and no config file are set in the factory.
     *
     * @expectedException RuntimeException
     */
    public function testThatFactoryThrowsExceptionWhenNoConfigAndNoConfigFileAreSet()
    {
        $cachePoolFactory = new Factory();

        $cachePoolFactory->make($this->adapterName);
    }

    /**
     * Tests that the factory throws exception when the configuration is invalid.
     *
     * @param array $config Adapter configuration
     *
     * @dataProvider invalidConfigurationDataProvider
     *
     * @expectedException RuntimeException
     * @expectedExceptionMessage Invalid configuration
     */
    public function testThatFactoryThrowsExceptionWithInvalidConfig(array $config)
    {
        $cachePoolFactory = new Factory();

        $cachePoolFactory->setConfig($config);
        $cachePoolFactory->make($this->adapterName);
    }

    /**
     * Tests that the factory throws exception when the adapter specified by the 'type' index is not implemented.
     *
     * @param array $config Adapter configuration
     *
     * @dataProvider validConfigurationDataProvider
     *
     * @expectedException RuntimeException
     * @expectedExceptionMessage There is no cache pool factory implementation for the adapter "local"
     */
    public function testThatFactoryThrowsExceptionWhenAdapterDoesNotExist(array $config)
    {
        $config[Config::INDEX_CACHE][Config::INDEX_ADAPTER][$this->adapterName][Config::INDEX_TYPE] = 'local';

        $cachePoolFactory = new Factory();

        $cachePoolFactory->setConfig($config);
        $cachePoolFactory->make($this->adapterName);
    }
}
