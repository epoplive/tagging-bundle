<?php

namespace Evilpope\TaggingBundle\DependencyInjection;

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
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('evilpope_tag');
        if (\method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // BC layer for symfony/config 4.1 and older
            $rootNode = $treeBuilder->root('evilpope_tag');
        }

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        $rootNode
            ->children()
            ->arrayNode('model')
            ->isRequired()
//                      ->cannotBeEmpty()
            ->children()
            ->scalarNode('tag_class')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('tagging_class')->isRequired()->cannotBeEmpty()->end()
            ->end()
            ->end()
            ->arrayNode('service')
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('slugifier')->defaultValue('evilpope_tag.slugifier.default')->cannotBeEmpty()->end()
            ->end()
            ->end()
            ->end();

        return $treeBuilder;
    }
}
