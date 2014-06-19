<?php

namespace Architect;

abstract class ArchitectAbstract
{
	protected $_params = array();

	public function setParams($params)
	{
		// @TODO: Add clean-up here
		$this->_params = $params;
	}

	public function getParams()
	{
		return $this->_params;
	}
}