<?php
namespace Kagux\SilexIntegrationBundle\Doctrine\Common\Persistence\Mapping\Driver;

use Doctrine\Common\Persistence\Mapping\Driver\MappingDriver;
use Doctrine\Common\Persistence\Mapping\MappingException;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;

class MappingDriverChain implements MappingDriver
{

    private $drivers;

    public function __construct()
    {
        $this->drivers=array();
    }

    public function addDriver(MappingDriver $driver)
    {
        $this->drivers[]=$driver;
    }

    /**
     * Loads the metadata for the specified class into the provided container.
     *
     * @param string $className
     * @param ClassMetadata $metadata
     */
    function loadMetadataForClass($className, ClassMetadata $metadata)
    {

        foreach ($this->drivers as $driver) {
            try{
                /** @var $driver MappingDriver */
                $driver->loadMetadataForClass($className,$metadata);
                break;
            }
            catch(MappingException $e){}
        }

    }

    /**
     * Gets the names of all mapped classes known to this driver.
     *
     * @return array The names of all mapped classes known to this driver.
     */
    function getAllClassNames()
    {
        $classNames = array();
        $driverClasses = array();

        /* @var $driver MappingDriver */
        foreach ($this->drivers AS $driver) {
            $oid = spl_object_hash($driver);

            if (!isset($driverClasses[$oid])) {
                $driverClasses[$oid] = $driver->getAllClassNames();
            }

            foreach ($driverClasses[$oid] AS $className) {
                $classNames[$className] = true;
            }
        }

        return array_keys($classNames);
    }

    /**
     * Whether the class with the specified name should have its metadata loaded.
     * This is only the case if it is either mapped as an Entity or a
     * MappedSuperclass.
     *
     * @param string $className
     * @return boolean
     */
    function isTransient($className)
    {
        /* @var $driver MappingDriver */
        foreach ($this->drivers AS $driver) {
            try{
                return $driver->isTransient($className);
            }
            catch(\Exception $e){}
        }

        return true;
    }
}
