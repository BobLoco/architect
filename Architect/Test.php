<?php

namespace Architect;

class Test extends ArchitectAbstract
{
	public function test2()
	{
		$m = new \MongoClient();
		$db = $m->{'architect-dev'};

		$collection = $db->projects;
		$cursor = $collection->find();

		$output = array();

		foreach ($cursor as $doc) {
			$output[] = $doc;
		}

		return $output;
	}
}