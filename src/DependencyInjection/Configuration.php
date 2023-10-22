<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\DependencyInjection;

use Dontdrinkandroot\Common\Asserted;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\ScalarNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('ddr_activity_pub_core');
        $rootNode = Asserted::instanceOf($treeBuilder->getRootNode(), ArrayNodeDefinition::class);

        $rootNode
            ->children()
            ->append(
                (new ScalarNodeDefinition('actor_path_prefix'))
                    ->defaultValue('/@')
                    ->cannotBeEmpty()
            )
            ->end();

        return $treeBuilder;
    }
}
