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

	// Errors codes
	const ERROR_PARAMS = 100;
	const ERROR_DB = 101;

	// Responses
	const RESOURCE_NOT_FOUND = 200;
}