<?php
/*
 * Copyright (C) 2013-2021 Combodo SARL
 * This file is part of iTop.
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * You should have received a copy of the GNU Affero General Public License
 */

namespace Combodo\iTop\Test\TestUtils;

use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestListenerDefaultImplementation;
use PHPUnit\Framework\TestSuite;
use Symfony\Component\Filesystem\Filesystem;

class PurgeTestEnvDir implements TestListener
{
	use TestListenerDefaultImplementation;

	private $bIsFirstStartCall = true;
	private $bIsFirstEndCall = true;

	public function startTestSuite(TestSuite $suite)
	{
		if (!$this->bIsFirstStartCall) {
			return;
		}
		$this->bIsFirstStartCall = false;

		$filesystem = new Filesystem();

		$prefix = \Combodo\iTop\Test\UnitTest\ItopTestCase::TEST_ITOP_ENV_PREFIX;
		assert(strlen($prefix) > 4, 'the env test prefix is long enough');

		$sBaseDir =  realpath(__DIR__."/../../../");
		$envPathPattern = "{$sBaseDir}/env-{$prefix}*";
		$aListEnv = glob($envPathPattern, GLOB_ONLYDIR);

		foreach ($aListEnv as $sPath) {
			assert(strpos($sPath, 'env-test-') !== false, 'the deleted dir contains env-test-');
			$filesystem->remove($sPath);

			fwrite(STDOUT, sprintf('PurgeTestEnvDir: test env "%s" purged before the Tests', basename($sPath)));
			fwrite(STDOUT, "\n");

		}
	}
}