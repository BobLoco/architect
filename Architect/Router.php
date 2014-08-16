<?php
namespace Architect;

/**
 * Architect\Router
 *
 * Defines and runs the routes for Architect
 *
 * @category Core
 * @package Architect
 * @author Rob Lowcock <rob.lowcock@gmail.com>
 */
class Router extends ArchitectAbstract
{
	/**
	 * Define the routes and return output for each REST method
	 * @param  object $app
	 * @return void
	 */
	public function routes($app)
	{
		$app->get('/:class/:identifier', function($class, $identifier) use ($app) {
			$loaded = $this->_loadClass($class, $app);

			echo json_encode($loaded->read($identifier));
		});

		$app->get('/:class', function ($class) use ($app) {
			$loaded = $this->_loadClass($class, $app);

			echo json_encode($loaded->read());
		});

		$app->put('/:class/:identifier', function($class, $identifier) use ($app) {
			$loaded = $this->_loadClass($class, $app);

			echo json_encode($loaded->update($identifier));
		});

		$app->post('/:class', function($class) use ($app) {
			$loaded = $this->_loadClass($class, $app);

			echo json_encode($loaded->create());
		});

		$app->delete('/:class/:identifier', function($class, $identifier) use ($app) {
			$loaded = $this->_loadClass($class, $app);

			echo json_encode($loaded->delete($identifier));
		});
	}

	/**
	 * Load a class based on the route recieved
	 * @param  string $class
	 * @param  object $app
	 * @return object
	 */
	protected function _loadClass($class, $app) {
		$fullclass = '\\Architect\\Controllers\\' . ucfirst($class);

		if (!class_exists($fullclass)) {
			$app->halt(404);
		}

		$app->contentType('application/json');

		return new $fullclass($app);
	}
}