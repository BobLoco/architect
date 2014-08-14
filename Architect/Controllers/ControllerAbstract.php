<?php
namespace Architect\Controllers;

abstract class ControllerAbstract
{
	protected $_app;

	public function __construct($app)
	{
		$this->_app = $app;
	}
}