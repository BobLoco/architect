<?php
namespace Architect;

use \Slim\Slim;
use \Pimple\Container;

/**
 * Architect\Core
 *
 * Defines and runs the core rendering Architect
 *
 * @category Architect
 * @package Core
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
		// Create the Slim object
		self::$app = new Slim(array('debug' => false));

		// Optimistically add the ContentTypes middleware
		// This doesn't seem to work yet
		self::$app->add(new \Slim\Middleware\ContentTypes());

		// Set the container to be available to the core
		$this->container = $container;
	}

	/**
	 * Define the routes and return output for each REST method
	 * @throws \RuntimeException
	 */
	public function routes()
	{
		// Handle any exceptions
		self::$app->error(function (\Exception $exception) {
			$data = array(
				'Message' => $exception->getMessage(),
				'Code' => $exception->getCode(),
			);
			$code = $exception->getCode();

			$this->displayResponse($data, $code);
		});

		// GET requests
		self::$app->get('/:class/:identifier', function ($class, $identifier) {
			$this->respond($class, 'read', $identifier);
		});

		self::$app->get('/:class', function ($class) {
			$this->respond($class, 'read');
		});

		// PUT requests
		self::$app->put('/:class/:identifier', function ($class, $identifier) {
			$this->respond($class, 'update', $identifier);
		});

		self::$app->put('/:class', function ($class) {
			throw new \RuntimeException('A resource identifier must be specified when using PUT', ResponseCode::ERROR_NOMETHOD);
		});

		// POST requests
		self::$app->post('/:class/:identifier', function ($class, $identifier) {
			throw new \RuntimeException('A resource identifier cannot be specified when using POST', ResponseCode::ERROR_NOMETHOD);
		});

		self::$app->post('/:class', function ($class) {
			$this->respond($class, 'create');
		});

		// DELETE requests
		self::$app->delete('/:class/:identifier', function ($class, $identifier) {
			$this->respond($class, 'delete', $identifier);
		});

		self::$app->delete('/:class', function ($class) {
			throw new \RuntimeException('A resource identifier must be specified when using DELETE', ResponseCode::ERROR_NOMETHOD);
		});

		// OPTIONS requests
		self::$app->options('/(:name+)', function () {
			$this->container['request']->validate();
			self::$app->response()->header('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, OPTIONS');
			self::$app->response()->header('Access-Control-Allow-Headers', 'Content-Type');
		});
	}

	/**
	 * Respond to a successful route
	 * @param string $class The class name
	 * @param string $method The method to be called
	 * @param int $identifier The identifier of the resource
	 * @throws \LogicException
	 */
	private function respond($class, $method, $identifier = null)
	{
		// Validate the request
		$this->container['request']->validate();

		// Make it a controller namespace
		$fullclass = '\\Architect\\Controllers\\' . ucfirst($class);

		// 404 if the class doesn't exist
		if (!class_exists($fullclass)) {
			self::$app->halt(404);
		}

		// Otherwise, instantiate it and pass it the container
		$loaded_class = new $fullclass($this->container);

		// Throw an exception when the method hasn't been created yet
		if (!method_exists($loaded_class, $method)) {
			throw new \LogicException('Method does not exist in resource', ResponseCode::ERROR_NOTIMPLEMENTED);
		}

		// Call the method with or without the identifier accordingly
		if (!is_null($identifier)) {
			$result = $loaded_class->$method($identifier);
		} else {
			$result = $loaded_class->$method();
		}

		// Check the result is an actual result
		if (!($result instanceof Result)) {
			throw new \LogicException('No result sent from API', ResponseCode::ERROR_INTERNAL);
		}

		// Get the data and code
		$data = $result->getData();
		$code = $result->getCode();

		// Display it!
		$this->displayResponse($data, $code);
	}

	/**
	 * Output a response
	 * @param array $data The data
	 * @param int $code The response code
	 */
	private function displayResponse($data, $code) {
		// Default to 200 if all else fails
		if (empty($code)) {
			$code = ResponseCode::OK;
		}

		// Get the response from Slim
		$response = self::$app->response();

		// Set the headers
		$response->header('Access-Control-Allow-Origin', '*');
		$response->header("Content-Type", "application/json");

		// If our status code is an internal one, just give a generic 500
		if ($code > 599) {
			$response->setStatus(ResponseCode::ERROR_INTERNAL);
		} else {
			$response->setStatus($code);
		}

		// Output the response!
		$response->write(json_encode($data));
	}
}