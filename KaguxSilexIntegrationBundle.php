<?php

namespace Kagux\SilexIntegrationBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Kagux\SilexIntegrationBundle\DependencyInjection\Compiler\AddAutoIntegratorsPass;
use Kagux\SilexIntegrationBundle\DependencyInjection\Compiler\SilexContainerIntegrationPass;
use Kagux\SilexIntegrationBundle\Silex\Integration\SilexIntegrationException;
use Kagux\SilexIntegrationBundle\DependencyInjection\Compiler\AddIntegratorsPass;
use Kagux\SilexIntegrationBundle\DependencyInjection\Compiler\TwigFormIntegrationPass;
use Kagux\SilexIntegrationBundle\DependencyInjection\Compiler\SilexAppServiceIntegrationPass;
use Kagux\SilexIntegrationBundle\DependencyInjection\Compiler\DoctrineMetadataIntegrationPass;
use Kagux\SilexIntegrationBundle\DependencyInjection\Compiler\ControllerResolveIntegrationPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class KaguxSilexIntegrationBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ControllerResolveIntegrationPass);
        $container->addCompilerPass(new SilexContainerIntegrationPass());
        $container->addCompilerPass(new DoctrineMetadataIntegrationPass());
        $container->addCompilerPass(new SilexAppServiceIntegrationPass());
        $container->addCompilerPass(new TwigFormIntegrationPass());
        $container->addCompilerPass(new AddIntegratorsPass());
        $container->addCompilerPass(new AddAutoIntegratorsPass());
    }

    public function boot()
    {
        try{
        $pool = $this->container->get('silex.integration.service.pool');
        }
        catch( \InvalidArgumentException $e){
            throw new SilexIntegrationException($e->getMessage(). ' '.
             'Please, ensure that you don\'t use Symfony2 services integrated in "create" '.
             'mode as middleware functions. Because Silex tries to load them before we had '.
             'a chance to integrate requested service. You can wrap it in a closure though!');
        }
        $pool->integrate();
    }


}
