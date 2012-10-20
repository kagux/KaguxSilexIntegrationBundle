<?php
namespace Kagux\SilexIntegrationBundle\HttpKernel;

use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpFoundation\Request;

class ControllerResolver implements ControllerResolverInterface
{
    private $original_controller_resolver;

    public function __construct(ControllerResolverInterface $original_controller_resolver)
    {
        $this->original_controller_resolver=$original_controller_resolver;
    }

    public function getController(Request $request)
    {
        return $this->original_controller_resolver->getController($request);
    }


    public function getArguments(Request $request, $controller)
    {
        return $this->original_controller_resolver->getArguments($request,$controller);
    }
}
