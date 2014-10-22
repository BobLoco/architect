<?php
namespace Architect;

use \Architect\ORM\EntityManager;
use \Architect\ResponseCode;

/**
 * Architect\Request
 *
 * Security and access layer
 *
 * @category Request
 * @package Architect
 * @author Rob Lowcock <rob.lowcock@gmail.com>
 */
class Request {

	/**
	 * Master key
	 */
	const MASTER = '1392efdc4a7dd3808f33940250f624fd';

	/**
	 * The request data
	 * @var array
	 */
	private $request_data = array();

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->request_data = \Architect\Core::$app->request()->getBody();

		// Total hack to clean-up request data
		// For some reason Slim's middleware doesn't work :-(
		if (!is_array($this->request_data)) {
			$this->request_data = json_decode($this->request_data, true);
			
			if (json_last_error() !== JSON_ERROR_NONE) {
				parse_str($this->request_data, $this->request_data);
			}
		}
	}

	/**
	 * Validate the request
	 * @return boolean Whether the request is valid
	 */
	public function validate()
	{
		// Grab the parameters from the request
		$params = \Architect\Core::$app->request()->params();

		if (empty($params['secret'])) {
			throw new \RuntimeException('No application secret set', ResponseCode::ERROR_BADREQUEST);
		}

		$secret = $params['secret'];

		if ($secret == self::MASTER) {
			\Architect\Core::$app->response()->header('Access-Control-Allow-Origin', '*');
			return true;
		} else {
			if (empty($params['app_id'])) {
				throw new \RuntimeException('No application ID set', ResponseCode::ERROR_BADREQUEST);
			}

			$entity_manager = new EntityManager();
			$orm = $entity_manager->createManager();

			$app_id = (int) $params['app_id'];

			$app = $orm->find('\Architect\ORM\src\App', $app_id);

			if (empty($app)) {
				throw new \RuntimeException('Invalid credentials', ResponseCode::ERROR_AUTH);
			}

			$stored_secret = $app->getAppSecret();

			if ($params['secret'] === $stored_secret) {
				\Architect\Core::$app->response()->header('Access-Control-Allow-Origin', $app->getAppUrl());
				return true;
			} else {
				throw new \RuntimeException('Invalid credentials', ResponseCode::ERROR_AUTH);
			}
		}
	}

	/**
	 * Retrieve an individual parameter
	 * @param  string $param The parameter to retrieve
	 * @return mixed The parameter value
	 */
	public function get($param)
	{
		if (in_array($param, array_keys($this->request_data))) {
			return $this->request_data[$param];
		} else {
			return false;
		}
	}
}