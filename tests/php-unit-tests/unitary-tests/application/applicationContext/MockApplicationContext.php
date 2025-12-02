<?php

namespace applicationContext;

class MockApplicationContext extends \ApplicationContext
{
	public function __construct(array $applicationContextConfig)
	{
		parent::__construct();
		$this->aValues = $applicationContextConfig;
	}

}
