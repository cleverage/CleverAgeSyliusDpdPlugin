<?php

declare(strict_types=1);

namespace CleverAge\SyliusDpdPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * @psalm-suppress UnusedVariable
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('clever_age_sylius_dpd_plugin');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
            ->scalarNode('securityKey')->isRequired()->cannotBeEmpty()->end()
            ->end();

        return $treeBuilder;
    }
}
