<?php

namespace Architect;

class Access {

	const MASTER = '1392efdc4a7dd3808f33940250f624fd';

	public function validateRequest()
	{
		if ($secret == self::MASTER) {
			return true;
		} else {
			$app_id;
		}
	}
}