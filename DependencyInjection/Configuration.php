<?php declare(strict_types=1);
/*
 * This file is part of the CleverAge/PermissionBundle package.
 *
 * Copyright (c) 2015-2021 Clever-Age
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CleverAge\PermissionBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link
 * http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 *
 * @author Vincent Chalnot <vincent@sidus.fr>
 */
class Configuration implements ConfigurationInterface
{
    protected $root;

    /**
     * @param string $root
     */
    public function __construct($root = 'clever_age_permission')
    {
        $this->root = $root;
    }

    /**
     * {@inheritdoc}
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder($this->root);
        $rootNode = $treeBuilder->getRootNode();

        $classDefinition = $rootNode
            ->children()
                ->arrayNode('classes')
                    ->prototype('array')
                        ->children();

        $this->appendClassDefinition($classDefinition);

        $classDefinition->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }

    /**
     * @param NodeBuilder $classDefinition
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    protected function appendClassDefinition(NodeBuilder $classDefinition): void
    {
        $classDefinition->variableNode('permissions')->isRequired()->end();
    }
}
