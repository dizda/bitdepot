<?php

namespace Dizda\Bundle\BlockchainBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('dizda_blockchain');

        $rootNode
            ->children()
                ->scalarNode('provider')
                    ->defaultValue('chain')
                ->end()
                ->arrayNode('endpoints')
                    ->treatNullLike(array())
                    ->prototype('scalar')->end()
                    ->defaultValue(['https://insight.bitpay.com/api/'])
                ->end()
            ->end();

        return $treeBuilder;
    }
}
