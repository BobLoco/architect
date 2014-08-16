<?php
namespace Architect;

use \Slim\Slim;

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

	/**
	 * Constructor
	 */
	public function __construct()
	{
		self::$app = new Slim();
	}

	/**
	 * Define the routes and return output for each REST method
	 * @param  object $app
	 * @return void
	 */
	public function routes()
	{
		self::$app->get('/:class/:identifier', function($class, $identifier) {
			$loaded = $this->_loadClass($class);

			$this->_displayOutput($loaded->read($identifier));
		});

		self::$app->get('/:class', function ($class) {
			$loaded = $this->_loadClass($class);

			$this->_displayOutput($loaded->read());
		});

		self::$app->put('/:class/:identifier', function($class, $identifier) {
			$loaded = $this->_loadClass($class);

			$this->_displayOutput($loaded->update($identifier));
		});

		self::$app->post('/:class', function($class) {
			$loaded = $this->_loadClass($class);

			$this->_displayOutput($loaded->create());
		});

		self::$app->delete('/:class/:identifier', function($class, $identifier) {
			$loaded = $this->_loadClass($class);

			$this->_displayOutput($loaded->delete($identifier));
		});
	}

	/**
	 * Load a class based on the route recieved
	 * @param  string $class
	 * @param  object $app
	 * @return object
	 */
	private function _loadClass($class)
	{
		$fullclass = '\\Architect\\Controllers\\' . ucfirst($class);

		if (!class_exists($fullclass)) {
			self::$app->halt(404);
		}

		self::$app->contentType('application/json');

		return new $fullclass();
	}

	/**
	 * Display the result output
	 * @param  Result $result
	 * @return void
	 */
	private function _displayOutput(Result $result)
	{
		if ($result->getCode() === ResponseCode::RESOURCE_NOT_FOUND) {
			self::$app->halt(404);
		}

		$data = $result->getData();

		if (!empty($data)) {
			echo json_encode($data);
		}
	}
}