<?php
namespace Kagux\SilexIntegrationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\ControllerNameParser;

class ControllerParser extends ControllerNameParser
{
    public function parse($controller)
    {
        return $controller instanceof \Closure?  $controller: parent::parse($controller);
    }

}
