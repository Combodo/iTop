<?php
/**
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Application;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

class TestAutoload extends ItopDataTestCase
{

	/**
	 */
	public function testAutoloader()
	{
		if (class_exists('Composer\InstalledVersions')) {
			$this->assertTrue(true);
			return;
		}
		$this->assertTrue(false, 'You should run composer install on the faulty module');
	}
}
