<?php

namespace Architect;

class Router extends ArchitectAbstract {
	
	public function routes($app) {
		$app->get('/:class/:function', function($class, $function) use ($app) {
			$fullclass = '\\Architect\\' . ucfirst($class);
			$loaded = new $fullclass();

			$loaded->setParams($_GET);

			$app->contentType('application/json');

			echo json_encode($loaded->$function());
		});
	}
}