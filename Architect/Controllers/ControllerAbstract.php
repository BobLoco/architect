<?php
namespace Architect\Controllers;

use \Architect\ORM\EntityManager;

/**
 * Architect\Controllers\ControllerAbstract
 *
 * Abstracted functionality for controllers
 *
 * @category Controllers
 * @package Architect
 * @subpackage Controllers
 * @author Rob Lowcock <rob.lowcock@gmail.com>
 */
abstract class ControllerAbstract
{
	protected $_app;
	protected $_orm;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$entity_manager = new EntityManager();
		$this->_orm = $entity_manager->createManager();
	}
}