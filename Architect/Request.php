<?php
namespace Architect;

use \Architect\ORM\EntityManager;

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
	 * [$request_data description]
	 * @var array
	 */
	public $request_data = array();

	public function __construct()
	{
		// $this->request_data = ;
	}

	/**
	 * Validate the request
	 * @return boolean Whether the request is valid
	 */
	public function validate()
	{
		$params = \Architect\Core::$app->request()->params();

		if (empty($params['secret'])) {
			throw new \Exception('No application secret set');
		}

		$secret = $params['secret'];

		if ($secret == self::MASTER) {
			return true;
		} else {
			if (empty($params['app_id'])) {
				throw new \Exception('No application ID set');
			}

			$entity_manager = new EntityManager();
			$orm = $entity_manager->createManager();

			$app_id = (int) $params['app_id'];

			$app = $orm->find('\Architect\ORM\src\App', $app_id);

			if (empty($app)) {
				throw new \Exception('Invalid credentials');
			}

			$stored_secret = $app->getAppSecret();

			if ($params['secret'] === $stored_secret) {
				\Architect\Core::$app->response()->header('Access-Control-Allow-Origin', $app->getAppUrl());
				return true;
			} else {
				throw new \Exception('Invalid credentials');
			}
		}
	}
}