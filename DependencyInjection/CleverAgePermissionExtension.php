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

use CleverAge\PermissionBundle\Voter\ClassVoter;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Autoload services from Resources/config/services
 */
class CleverAgePermissionExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $classVoterDefinition = $container->getDefinition(ClassVoter::class);
        foreach ($config['classes'] as $class => $configuration) {
            $classVoterDefinition->addMethodCall('addPermissions', [$class, $configuration['permissions']]);
        }
    }
}
