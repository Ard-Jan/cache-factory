<?php

namespace Cache\Factory\Config\Adapter;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Predis extends AbstractConfig
{
    const INDEX_SERVERS = 'servers';

    const INDEX_OPTIONS = 'options';

    const INDEX_HOST    = 'host';

    const INDEX_PORT    = 'port';

    const INDEX_SCHEME  = 'scheme';

    /**
     * Returns the adapter specific config builder
     *
     * @return ArrayNodeDefinition|NodeDefinition
     */
    public function getAdapterConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $node        = $treeBuilder->root($this->getAdapterName());

        $node
            ->children()
                ->arrayNode(self::INDEX_SERVERS)
                    ->prototype('array')
                        ->children()
                            ->scalarNode(self::INDEX_HOST)
                                ->defaultValue('127.0.0.1')
                            ->end()
                            ->scalarNode(self::INDEX_PORT)
                                ->defaultValue(6379)
                            ->end()
                            ->scalarNode(self::INDEX_SCHEME)
                                ->defaultValue('tcp')
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode(self::INDEX_OPTIONS)
                ->end()
            ->end();

        return $node;
    }
}
