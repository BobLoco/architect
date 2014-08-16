<?php
namespace Architect;

/**
 * Architect\ResponseCode
 *
 * Defines response codes
 *
 * @category Core
 * @package Architect
 * @author Rob Lowcock <rob.lowcock@gmail.com>
 */
class ResponseCode extends ArchitectAbstract
{
	const OK = 0;

	const ERROR_DB = 100;

	const RESOURCE_NOT_FOUND = 200;
}