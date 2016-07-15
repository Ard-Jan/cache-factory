<?php

namespace Cache\Factory\Adapter;

abstract class AbstractAdapter implements AdapterInterface
{
    const ADAPTER_NAMESPACE_TEMPLATE = '\\Cache\\Adapter\\%s\\%sCachePool';

    /**
     * Returns configured instance of driver required by the cache pool
     *
     * @param array $config The configuration array
     *
     * @return mixed
     */
    abstract protected function getConfiguredDriver(array $config);

    /**
     * @inheritdoc
     */
    public function make(array $config)
    {
        $cachePoolClassName = $this->getAdapterClassName(static::class);
        $cacheDriver        = $this->getConfiguredDriver($config);

        return new $cachePoolClassName($cacheDriver);
    }

    /**
     * Returns the full cache pool class name (with namespace).
     *
     * @param string $adapterClassName The adapter class name
     *
     * @return string The fully qualified class name
     */
    protected function getAdapterClassName($adapterClassName)
    {
        $adapterClassName = str_replace(__NAMESPACE__, '', $adapterClassName);
        $adapterClassName = str_replace('\\', '', $adapterClassName);
        $adapterClassName = str_replace('Mock_', '', $adapterClassName);
        $adapterClassName = preg_replace('/_[0-9a-z]+$/', '', $adapterClassName);
        $adapterClassName = sprintf(self::ADAPTER_NAMESPACE_TEMPLATE, $adapterClassName, $adapterClassName);

        return $adapterClassName;
    }
}
