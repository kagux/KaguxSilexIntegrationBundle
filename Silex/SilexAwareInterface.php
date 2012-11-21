<?php
namespace Kagux\SilexIntegrationBundle\Silex;

interface SilexAwareInterface
{
    /**
     * @param \Silex\Application $silex
     */
    public function setSilex(\Silex\Application $silex);
}
