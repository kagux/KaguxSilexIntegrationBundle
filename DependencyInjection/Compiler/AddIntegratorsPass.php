<?php
namespace Kagux\SilexIntegrationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;
use Kagux\SilexIntegrationBundle\Silex\Integration\Service\ServiceIntegrationMode;
use Kagux\SilexIntegrationBundle\Silex\Integration\SilexIntegrationException;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AddIntegratorsPass  implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if(!$container->getParameter('silex.app.enabled')) return;
        $pool = $container->getDefinition('silex.integration.service.pool');
        foreach ($container->findTaggedServiceIds('silex.integrator') as $id => $tags) {
            $interface = 'Kagux\SilexIntegrationBundle\Silex\SilexAwareInterface';
            $class = $this->getClass($container, $id);
            if (!$this->implementsInterface($class, $interface)) {
                throw new \InvalidArgumentException(sprintf('Service "%s" must implement interface "%s".', $id, $interface));
            }
            $integrator = $container->getDefinition($id);
            if ($this->implementsInterface($class, 'Symfony\Component\DependencyInjection\ContainerAwareInterface')){
                $integrator->addMethodCall('setContainer',array(new Reference('service_container')));
            }
            if ($this->implementsInterface($class, 'Kagux\SilexIntegrationBundle\Silex\SilexAwareInterface')){
                $integrator->addMethodCall('setSilex',array(new Reference('silex.app')));
            }
            foreach ($tags as $attributes) {
                if (!isset($attributes['id'])){
                    throw new SilexIntegrationException(sprintf('Integrator service %s has to set service id it integrates', $id));
                }
                $mode = isset($attributes['mode'])?$this->getMode($attributes['mode']):ServiceIntegrationMode::SKIP_IF_NO_SERVICE;
                $priority = isset($attributes['priority'])?$attributes['priority']:0;
                $pool->addMethodCall('addServiceIntegrator', array($integrator, $attributes['id'], $mode, $priority));
            }
        }
    }

    private function getClass(ContainerBuilder $container, $id)
    {
        $class = $container->getDefinition($id)->getClass();
        return  (strpos($class, '%') === 0)?$container->getParameter(str_replace('%','',$class)) : $class ;
    }

    private function implementsInterface($class, $interface)
    {
        $refClass = new \ReflectionClass($class);
        return $refClass->implementsInterface($interface);
    }

    private function getMode($mode)
    {
        switch($mode){
            case 'skip':
                return ServiceIntegrationMode::SKIP_IF_NO_SERVICE;
            case 'create':
                return ServiceIntegrationMode::CREATE_IF_NO_SERVICE;
            default:
                throw new SilexIntegrationException(sprintf('Service has invalid integrator mode "%s"',  $mode));
        }
    }
}
