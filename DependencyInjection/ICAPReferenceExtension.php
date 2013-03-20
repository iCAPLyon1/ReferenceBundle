<?php

namespace ICAP\ReferenceBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ICAPReferenceExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    // public function load(array $configs, ContainerBuilder $container)
    // {
    //     $configuration = new Configuration();
    //     $config = $this->processConfiguration($configuration, $configs);

    //     $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
    //     $loader->load('services.yml');

    //     $container->setParameter('referencesConfiguration', $config);
    // }

    public function load(array $configs, ContainerBuilder $container)
    {
        // $configuration = new Configuration();
        // $config = $this->processConfiguration($configuration, $configs);

        $locator = new FileLocator(__DIR__ . '/../Resources/config/services');
        $loader = new YamlFileLoader($container, $locator);
        $loader->load('listeners.yml');

        $serviceLoader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $serviceLoader->load('services.yml');

        // $configLoader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/referenceConfig'));
        // $serviceLoader->load('config.yml');

        // $configuration = new Configuration();
        // $config = $this->processConfiguration($configuration, $configs);
        // $container->setParameter('referencesConfiguration', $config);
    }
}
