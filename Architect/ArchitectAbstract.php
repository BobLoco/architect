<?php
namespace Architect;

use \Pimple\Container;

/**
 * Architect\ArchitectAbstract
 *
 * Abstract for Architect core
 *
 * @category Architect
 * @package Architect
 * @subpackage Architect
 * @author Rob Lowcock <rob.lowcock@gmail.com>
 */
abstract class ArchitectAbstract
{
	protected $container;

	/**
	 * Constructor
	 * @param Container $container The DI container
	 */
	public function __construct(Container $container)
	{
		$this->container = $container;
	}
}
