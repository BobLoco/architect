<?php
namespace Architect\ORM;

// use the Doctrine classes
use \Doctrine\ORM\Tools\Setup;
use \Pimple\Container;

/**
 * Architect\ORM\EntityManager
 *
 * ORM Entity Manager
 *
 * @category ORM
 * @package Architect
 * @subpackage ORM
 * @author Rob Lowcock <rob.lowcock@gmail.com>
 */
class EntityManager extends \Architect\ArchitectAbstract implements EntityManagerInterface
{
    public function createManager()
    {
        // setup Doctrine
        $devMode = true;
        $settings = Setup::createAnnotationMetadataConfiguration(array(__DIR__.'/src'), $devMode);

        $config = $this->container['config']->getDbConfig();

        // connect to the DB
        $conn = array(
            'driver' => $config['driver'],
            'user' => $config['username'],
            'password' => $config['password'],
            'dbname' => $config['database'],
        );

        // get the entity manager
        return \Doctrine\ORM\EntityManager::create($conn, $settings);
    }
}
