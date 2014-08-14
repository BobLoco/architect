<?php

namespace Architect;

class Router extends ArchitectAbstract
{
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

	protected function _loadClass($class, $app) {
		$fullclass = '\\Architect\\Controllers\\' . ucfirst($class);

		$app->contentType('application/json');

		return new $fullclass($app);
	}
}