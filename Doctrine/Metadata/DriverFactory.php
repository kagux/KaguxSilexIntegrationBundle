<?php
namespace Kagux\SilexIntegrationBundle\Doctrine\Metadata;

use Silex\Application;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;



class DriverFactory
{
    private $app;
    private $driver;

    public function __construct(Application $app, MappingDriverChain $driver)
    {
        $this->app=$app;
        $this->driver=$driver;
    }

    public function create()
    {
        if(!isset($this->app['db.orm.em'])) return $this->driver;
        $silex_configuration=$this->app['db.orm.em']->getConfiguration();
        $silex_meta_driver = $silex_configuration->getMetadataDriverImpl();
        if ($silex_meta_driver instanceof MappingDriverChain) {
            foreach ($silex_meta_driver->getDrivers() as $namespace => $driver) {
                $this->driver->addDriver($driver, $namespace);
            }
        }
        else{
            throw new \Exception('Only chains are integrated for now');
        }
        return $this->driver;
    }

}
