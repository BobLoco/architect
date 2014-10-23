<?php
namespace Architect;

use \Slim\Slim;
use \Pimple\Container;

/**
 * Architect\Core
 *
 * Defines and runs the core rendering Architect
 *
 * @category Core
 * @package Architect
 * @author Rob Lowcock <rob.lowcock@gmail.com>
 */
class Core extends ArchitectAbstract
{
	public static $app;

	protected $container;

	/**
	 * Constructor
	 */
	public function __construct(Container $container)
	{
		self::$app = new Slim(array('debug' => false));
		self::$app->add(new \Slim\Middleware\ContentTypes());

		$this->container = $container;
	}

	/**
	 * Define the routes and return output for each REST method
	 * @param  object $app
	 * @return void
	 */
	public function routes()
	{
		self::$app->error(function (\Exception $exception) {
			$data = array(
				'Message' => $exception->getMessage(),
				'Code' => $exception->getCode(),
			);
			$status = $exception->getCode();

			$this->_displayResponse($data, $status);
		});

		self::$app->get('/:class/:identifier', function($class, $identifier) {
			$this->_respond($class, 'read', $identifier);
		});

		self::$app->get('/:class', function ($class) {
			$this->_respond($class, 'read');
		});

		self::$app->put('/:class/:identifier', function($class, $identifier) {
			$this->_respond($class, 'update', $identifier);
		});

		self::$app->post('/:class', function($class) {
			$this->_respond($class, 'create');
		});

		self::$app->delete('/:class/:identifier', function($class, $identifier) {
			$this->_respond($class, 'delete', $identifier);
		});

		self::$app->options('/(:name+)', function(){
			self::$app->response()->header('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, OPTIONS');
			self::$app->response()->header('Access-Control-Allow-Headers', 'Content-Type');
		});

		
	}

	private function _respond($class, $method, $identifier = null)
	{
		// Validate the request
		$this->container['request']->validate();

		$fullclass = '\\Architect\\Controllers\\' . ucfirst($class);

		// 404 if the class doesn't exist
		if (!class_exists($fullclass)) {
			self::$app->halt(404);
		}

		// Otherwise, instantiate it and pass it the container
		$loaded_class = new $fullclass($this->container);

		if (!is_null($identifier)) {
			$result = $loaded_class->$method($identifier);
		} else {
			$result = $loaded_class->$method();
		}

		if (!($result instanceof Result)) {
			throw new \LogicException('No result sent from API', ResponseCode::ERROR_INTERNAL);
		}

		$data = $result->getData();
		$status = $result->getCode();

		$this->_displayResponse($data, $code);
	}

	private function _displayResponse($data, $code) {

		// Default to 200 if all else fails
		if (empty($status)) {
			$status = ResponseCode::OK;
		}

		$response = self::$app->response();
		$response->header('Access-Control-Allow-Origin', '*');

		if ($status > 599) {
			$response->status(ResponseCode::ERROR_INTERNAL);
		} else {
			$response->status($status);
		}

		$response->write(json_encode($data));
	}
}