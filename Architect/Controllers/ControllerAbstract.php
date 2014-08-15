<?php
namespace Architect\Controllers;

use \Architect\ORM\EntityManager;

abstract class ControllerAbstract
{
	protected $_app;
	protected $_orm;

	public function __construct($app)
	{
		$this->_app = $app;

		$entity_manager = new EntityManager();
		$this->_orm = $entity_manager->createManager();
	}
}