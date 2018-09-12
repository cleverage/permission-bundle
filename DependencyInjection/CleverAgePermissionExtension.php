<?php
/*
 * This file is part of the CleverAge/PermissionBundle package.
 *
 * Copyright (c) 2015-2018 Clever-Age
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CleverAge\PermissionBundle\DependencyInjection;

use CleverAge\PermissionBundle\Voter\ClassVoter;
use Sidus\BaseBundle\DependencyInjection\SidusBaseExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Autoload services from Resources/config/services
 */
class CleverAgePermissionExtension extends SidusBaseExtension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        parent::load($configs, $container);

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $classVoterDefinition = $container->getDefinition(ClassVoter::class);
        foreach ($config['classes'] as $class => $configuration) {
            $classVoterDefinition->addMethodCall('addPermissions', [$class, $configuration['permissions']]);
        }
    }
}
