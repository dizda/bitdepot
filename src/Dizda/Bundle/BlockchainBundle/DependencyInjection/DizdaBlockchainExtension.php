<?php

namespace Dizda\Bundle\BlockchainBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class DizdaBlockchainExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        foreach ($config as $k => $v) {
            $container->setParameter('dizda_blockchain.' . $k, $v);
        }

        // Let the user to choose the blockchain provider
        $blockchainProvider = $container->getDefinition('dizda_blockchain.blockchain.provider');
        $blockchainProvider->replaceArgument(0, new Reference(
            sprintf('dizda_blockchain.blockchain.%s', $container->getParameter('dizda_blockchain.provider'))
        ));
    }
}
