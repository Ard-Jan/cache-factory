<?php

namespace Cache\Factory\Config\Adapter;

use Symfony\Component\Config\Definition\Builder\ScalarNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

abstract class AbstractConfig implements AdapterInterface
{
    /**
     * @var string The name of the adapter
     */
    protected $adapterName;

    /**
     * @inheritdoc
     */
    abstract public function getAdapterConfigTreeBuilder();

    /**
     * @inheritdoc
     */
    public function setAdapterName($adapterName)
    {
        $this->adapterName = $adapterName;
    }

    /**
     * @inheritdoc
     */
    public function getAdapterName()
    {
        return $this->adapterName;
    }

    /**
     * Merges together the tree node definition with the Adapter node definition
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $typeNode = new ScalarNodeDefinition(self::INDEX_TYPE);
        $typeNode->isRequired()->cannotBeEmpty();

        $adapterNode = $this->getAdapterConfigTreeBuilder();
        $adapterNode->append($typeNode);

        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root(self::INDEX_CACHE);

        $rootNode
            ->children()
                ->arrayNode(self::INDEX_ADAPTER)
                    ->children()
                        ->append($adapterNode)
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
