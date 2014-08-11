<?php

namespace Architect;

class Test extends ArchitectAbstract
{
	public function get($identifier)
	{
		$output = array('id' => $identifier);

		return $output;
	}
}