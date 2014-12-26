<?php
/**
 * Config interface file
 *
 * @author Rob Lowcock <rob.lowcock@gmail.com>
 */
namespace Architect\ORM;

/**
 * Architect\ORM\ConfigInterface
 *
 * Config Interface
 *
 * @category ORM
 * @package Architect
 * @subpackage ORM
 * @author Rob Lowcock <rob.lowcock@gmail.com>
 */
interface ConfigInterface
{
    /**
     * Retrieve a database configuration
     */
    public function getDbConfig();
}
