<?php

namespace DavidFricker\RestAPI\Model;

class ExampleModel extends AbstractModel 
{
	public function getDomain()
	{
		return ['domain' => 'foo.bar'];
	}
}