<?php

namespace Cache\Factory;

use Cache\Factory\Adapter\AdapterInterface;
use Cache\Factory\Config\Adapter\AdapterInterface as Config;
use Cache\Factory\Config\Loader;
use Cache\Taggable\TaggablePSR6PoolAdapter;
use Psr\Cache\CacheItemPoolInterface;
use RuntimeException;

class Factory
{
    const EXCEPTION_TEMPLATE_NO_ADAPTER_IMPLEMENTATION = 'There is no cache pool factory implementation for the adapter "%s"';

    const EXCEPTION_TEMPLATE_ADAPTER_MUST_IMPLEMENT    = 'The cache pool factory implementation for the adapter %s must implement %s';

    const EXCEPTION_CONFIG_AND_CONFIG_FILE_NOT_SET     = 'Either the config or the config file must be set';

    const EXCEPTION_BAD_CONFIG                         = 'Invalid configuration';

    /**
     * Type of the cache adapter (Redis, Memcached etc).
     *
     * @var string
     */
    protected $adapterType;

    /**
     * Configuration array
     *
     * @var array
     */
    protected $config;

    /**
     * Configuration file (path and name)
     *
     * @var string
     */
    protected $configFile;

    /**
     * @var Loader
     */
    protected $configLoader;

    /**
     * Returns the cache adapter name.
     *
     * @return string
     */
    public function getAdapterType()
    {
        return $this->adapterType;
    }

    /**
     * Sets the cache adapter name.
     *
     * @param string $adapterType
     *
     * @return $this
     */
    public function setAdapterType($adapterType)
    {
        $this->adapterType = $adapterType;

        $this->getConfigLoader()->setAdapterName($adapterType);

        return $this;
    }

    /**
     * Returns the config loader.
     *
     * @return Loader
     */
    public function getConfigLoader()
    {
        return $this->configLoader;
    }

    /**
     * Sets the configuration for the adapter.
     *
     * @param array $config The configuration array
     *
     * @return $this
     */
    public function setConfig(array $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Sets the configuration file for the adapter.
     *
     * @param string $configFile Configuration filename
     *
     * @return $this
     */
    public function setConfigFile($configFile)
    {
        $this->configFile = $configFile;

        return $this;
    }

    public function __construct()
    {
        $this->configLoader = new Loader();
    }

    /**
     * Creates and returns an instance of Cache Pool type specified by adapter name.
     *
     * If both config array and the configFile are set,
     * the config variable will take precedence over the configFile and the later will be ignored.
     *
     * @param string $adapterName The name of the adapter to use
     *
     * @throws RuntimeException
     *
     * @return CacheItemPoolInterface
     */
    public function make($adapterName)
    {
        if (empty($this->config) && empty($this->configFile)) {
            throw new RuntimeException(self::EXCEPTION_CONFIG_AND_CONFIG_FILE_NOT_SET);
        }

        if (empty($this->config)) {
            $this->config = $this->getConfigLoader()->load($this->configFile);
        }

        if (empty($this->config[Config::INDEX_CACHE][Config::INDEX_ADAPTER][$adapterName][Config::INDEX_TYPE])) {
            throw new RuntimeException(self::EXCEPTION_BAD_CONFIG);
        }
        $adapterType = $this->config[Config::INDEX_CACHE][Config::INDEX_ADAPTER][$adapterName][Config::INDEX_TYPE];
        $this->setAdapterType($adapterType);

        $cachePoolFactory = $this->getCachePoolFactory($adapterType);

        $processedConfiguration = $this->getConfigLoader()->process($adapterName, $this->config);
        $adapterConfiguration   = $processedConfiguration[Config::INDEX_ADAPTER][$adapterName];
        $cachePool              = $cachePoolFactory->make($adapterConfiguration);

        return $cachePool;
    }

    /**
     * Creates and returns an instance of Taggable Cache Pool type specified by adapter name.
     *
     * @param string $adapterName The name of the adapter to use
     *
     * @return \Cache\Taggable\TaggablePoolInterface
     */
    public function makeTaggable($adapterName)
    {
        return TaggablePSR6PoolAdapter::makeTaggable($this->make($adapterName));
    }

    /**
     * Returns the specialised cache pool factory for given adapter type.
     *
     * @param string $adapterType The adapter type
     *
     * @throws RuntimeException
     *
     * @return AdapterInterface
     */
    protected function getCachePoolFactory($adapterType)
    {
        $cachePoolFactoryClassName = __NAMESPACE__ . '\\Adapter\\' . $adapterType;

        if (!class_exists($cachePoolFactoryClassName)) {
            throw new RuntimeException(sprintf(self::EXCEPTION_TEMPLATE_NO_ADAPTER_IMPLEMENTATION, $adapterType));
        }

        /** @var AdapterInterface $adapterFactory */
        $adapterFactory = new $cachePoolFactoryClassName();
        if (!$adapterFactory instanceof AdapterInterface) {
            throw new RuntimeException(sprintf(self::EXCEPTION_TEMPLATE_ADAPTER_MUST_IMPLEMENT, $adapterType, 'AdapterInterface'));
        }

        return $adapterFactory;
    }
}
