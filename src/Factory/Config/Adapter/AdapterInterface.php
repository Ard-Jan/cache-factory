<?php

namespace Cache\Factory\Config\Adapter;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

interface AdapterInterface extends ConfigurationInterface
{
    const INDEX_CACHE           = 'Cache';

    const INDEX_ADAPTER         = 'adapter';

    const INDEX_TYPE            = 'type';

    const INDEX_DEFAULT_ADAPTER = 'default';

    /**
     * Returns the adapter specific config builder
     *
     * @return TreeBuilder
     */
    public function getAdapterConfigTreeBuilder();

    /**
     * Sets the adapter name
     *
     * @param string $className The name of the adapter
     */
    public function setAdapterName($className);

    /**
     * Returns the adapter name
     *
     * @return string $adapterType The name of the adapter
     */
    public function getAdapterName();
}
