<?php
namespace Kagux\SilexIntegrationBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SilexIntegrationPass  implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {
        if(!$container->getParameter('silex.app.enabled')) return;
        $container->setDefinition('original.controller_resolver', $container->findDefinition('controller_resolver'));
        $container->setAlias('controller_resolver',new Alias('silex.controller_resolver'),false);
        $container->setAlias('silex.app', new Alias($container->getParameter('silex.app.service')));
        $twig_form_engine_def = $container->findDefinition('twig.form.engine');
        $twig_form_engine_def->replaceArgument(0, new Reference('silex.integration.twig.form.resources'));
    }
}
