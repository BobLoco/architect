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
	protected $_orm;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$entity_manager = new EntityManager();
		$this->_orm = $entity_manager->createManager();
	}

	/**
	 * Convert camelcase to underscore
	 * @param  string $string The string to convert
	 * @return string A converted string
	 */
	protected function _underscore($string)
	{
		return strtolower(preg_replace('/([a-z])([A-Z])/', '${1}_${2}', $string));
	}

	/**
	 * Convert underscore to camelcase
	 * @param  string $string The string to convert
	 * @return string A converted string
	 */
	protected function _camelcase($string)
	{
		return preg_replace_callback('/_([a-z])/', function($matches){
			return ucfirst($matches[0]);
		}, $string);
	}
}