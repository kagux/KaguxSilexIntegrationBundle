<?php
namespace Kagux\SilexIntegrationBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DoctrineMetadataIntegrationPass  implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {
        if(!$container->getParameter('silex.app.enabled')) return;
        $container->setDefinition('original.doctrine.orm.default_metadata_driver', $container->findDefinition('doctrine.orm.default_metadata_driver'));
        $container->setAlias('doctrine.orm.default_metadata_driver',new Alias('silex.doctrine.orm.default_metadata_driver'),false);
    }
}
