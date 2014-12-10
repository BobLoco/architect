<?php
namespace Architect\ORM;

// use the Doctrine classes
use \Doctrine\ORM\Tools\Setup;

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
        $config = Setup::createAnnotationMetadataConfiguration(array(__DIR__.'/src'), $devMode);

        // connect to the DB
        $conn = array(
            'driver' => Config::DB_DRIVER,
            'user' => Config::DB_USER,
            'password' => Config::DB_PASS,
            'dbname' => Config::DB_DATABASE,
        );

        // get the entity manager
        return \Doctrine\ORM\EntityManager::create($conn, $config);
    }
}
