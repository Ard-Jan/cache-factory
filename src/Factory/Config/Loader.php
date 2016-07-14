<?php

namespace Cache\Factory\Config;

use Cache\Factory\Config\Adapter\AdapterInterface;
use Cache\Factory\Config\Loader\Yaml;
use RuntimeException;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Exception\FileLoaderLoadException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;

class Loader
{
    const EXCEPTION_CLASS_DOES_NOT_EXIST = 'Config class for the adapter %s doesn\'t exist.';

    const EXCEPTION_CLASS_MUST_IMPLEMENT = 'Config class for the adapter %s must implement %s';

    /**
     * The name of target cache adapter.
     *
     * @var string
     */
    protected $adapterName;

    /**
     * Returns the adapter name.
     *
     * @return string
     */
    public function getAdapterName()
    {
        return $this->adapterName;
    }

    /**
     * Sets the adapter name.
     *
     * @param string $adapterName The name of target cache adapter
     */
    public function setAdapterName($adapterName)
    {
        $this->adapterName = $adapterName;
    }

    /**
     * Loads the configuration file
     *
     * @param string $configFile
     *
     * @throws FileLoaderLoadException
     *
     * @return array The loaded configuration
     */
    public function load($configFile)
    {
        $configDirectories = array(dirname($configFile));

        $locator        = new FileLocator($configDirectories);
        $loaderResolver = new LoaderResolver(
            array(
                new Yaml($locator),
            )
        );

        $delegatingLoader = new DelegatingLoader($loaderResolver);
        $config           = $delegatingLoader->load($configFile);

        return $config;
    }

    /**
     * Processes and validates the configuration
     *
     * @param string $adapterName The name of the adapter
     * @param array  $config      The configuration to validate
     *
     * @throws RuntimeException
     *
     * @return array The processed configuration
     */
    public function process($adapterName, array $config)
    {
        $className = $this->getAdapterConfigClassName();
        if (!class_exists($className)) {
            throw new RuntimeException(sprintf(self::EXCEPTION_CLASS_DOES_NOT_EXIST, $this->getAdapterName()));
        }

        /** @var AdapterInterface $configurationClass */
        $configurationClass = new $className();
        if (!$configurationClass instanceof AdapterInterface) {
            throw new RuntimeException(
                sprintf(self::EXCEPTION_CLASS_MUST_IMPLEMENT, $this->getAdapterName(), AdapterInterface::class)
            );
        }

        $configurationClass->setAdapterName($adapterName);

        $configToValidate = array(
            AdapterInterface::INDEX_CACHE => array(
                AdapterInterface::INDEX_ADAPTER => array(
                    $adapterName => $config[AdapterInterface::INDEX_CACHE][AdapterInterface::INDEX_ADAPTER][$adapterName]
                )
            )
        );

        $processor       = new Processor();
        $configProcessed = $processor->processConfiguration($configurationClass, $configToValidate);

        return $configProcessed;
    }

    /**
     * Returns the correct namespace of the Adapter config classes
     *
     * @return string The adapter config class name
     */
    protected function getAdapterConfigClassName()
    {
        $adapterConfigNamespace = sprintf('%s\\Adapter\\%s', __NAMESPACE__, $this->getAdapterName());

        return $adapterConfigNamespace;
    }
}
