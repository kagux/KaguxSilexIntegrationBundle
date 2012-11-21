<?php
namespace Kagux\SilexIntegrationBundle\Silex;
class SilexAware implements SilexAwareInterface
{
    /**
     * @var \Silex\Application
     */
    protected $silex;

    public function setSilex(\Silex\Application $silex)
    {
        $this->silex=$silex;
    }
}
