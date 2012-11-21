<?php

namespace Kagux\SilexIntegrationBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class KaguxSilexIntegrationExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        if(!isset( $config['app_service'])) {
            $container->setParameter('silex.app.enabled', false);
            return;
        }
        $container->setParameter('silex.app.enabled', true);
        $container->setParameter('silex.app.service', $config['app_service']);
        $this->addClassesToCompile(array(
            'Kagux\\SilexIntegrationBundle\\HttpKernel\\ControllerResolver',
            'Kagux\\SilexIntegrationBundle\\Silex\\ApplicationIntegrator',
            'Kagux\\SilexIntegrationBundle\\Silex\\Route\\Converter',
            'Kagux\\SilexIntegrationBundle\\Router\\Loader\\SilexLoader',
            'Kagux\\SilexIntegrationBundle\\Doctrine\\Metadata\\DriverFactory',
            'Kagux\\SilexIntegrationBundle\\Twig\\Form\\Engine\\Resources\\Resolver'
        ));
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('integrators.yml');
    }
}
