<?php
namespace Architect;

/**
 * Architect\ResponseCode
 *
 * Defines response codes
 * These match HTTP response codes, so can be used when the exception is caught
 * and returned as a header
 *
 * @category Core
 * @package Architect
 * @author Rob Lowcock <rob.lowcock@gmail.com>
 */
class ResponseCode extends ArchitectAbstract
{
	// Success
	const OK = 200;
	const OK_CREATED = 201;
	const OK_ACCEPTED = 202; // Accepted for processing, not completed
	const OK_NOCONTENT = 204; // Processed, but no content returned

	// Client Error
	const ERROR_BADREQUEST = 400;
	const ERROR_AUTH = 403;	// Ignore 401, as it isn't true client-side auth
	const ERROR_NOTFOUND = 404;
	const ERROR_NOMETHOD = 405; // Method not supported by resource
	const ERROR_TEAPOT = 418; // Because why not

	// Internal error
	const ERROR_INTERNAL = 500;
	const ERROR_NOTIMPLEMENTED = 501; // Future method
	const ERROR_SERVICE = 503; // Down for maintenance

	// We use codes of 600 and above for internal debugging, and then just return 500
	const ERROR_DB = 600;
}