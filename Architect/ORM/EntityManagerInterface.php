<?php
/**
 * Interface for entity manager
 *
 * @author Rob Lowcock <rob.lowcock@gmail.com>
 */
namespace Architect\ORM;

/**
 * Architect\ORM\EntityManagerInterface
 *
 * Entity Manager Interface
 *
 * @category ORM
 * @package Architect
 * @subpackage ORM
 * @author Rob Lowcock <rob.lowcock@gmail.com>
 */
interface EntityManagerInterface
{
    /**
     * Create the entity manager
     * @return \Architect\ORM\EntityManager
     */
    public function createManager();
}
