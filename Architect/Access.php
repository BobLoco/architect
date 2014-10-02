<?php

namespace Architect;

class Access {
	const MASTER = '1392efdc4a7dd3808f33940250f624fd';

	public function validateRequest()
	{
		if (empty($_REQUEST['secret'])) {
			throw new Exception('No application secret set');
		}

		$secret = $_REQUEST['secret'];

		if ($secret == self::MASTER) {
			return true;
		} else {
			$entity_manager = new EntityManager();
			$orm = $entity_manager->createManager();

			$app_id;
		}
	}
}