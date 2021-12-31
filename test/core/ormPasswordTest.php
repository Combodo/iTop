<?php
/*!
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use ormPassword;
use Utils;

/**
 * Tests of the ormPassword class
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class ormPasswordTest extends ItopDataTestCase
{

	/**
	 * @param $sToHashValues
	 * @param $sToHashSalt
	 * @param $sHashAlgo
	 * @param $sExpectedHash
	 *
	 * @throws \ConfigException
	 * @throws \CoreException
	 * @dataProvider HashProvider
	 */
	public function testCheckHash($sToHashValues, $sToHashSalt, $sHashAlgo, $sExpectedHash)
	{
		utils::GetConfig()->SetPasswordHashAlgo($sHashAlgo);
		$oPassword1 = new ormPassword($sExpectedHash, $sToHashSalt);
		static::assertTrue($oPassword1->CheckPassword($sToHashValues));
	}

	public function HashProvider()
	{
		return array(
			'Bcrypt' => array(
				'admin',
				'',
				PASSWORD_BCRYPT,
				'$2y$10$P6yqXv/0pT4e9kfN6d95jOKX4KR5Il.N0vRLc2DoZoycwnU9mcnia'
			),
			'sha256 (legacy)' => array(
				'admin',
				'salt',
				'sha256',
				'2bb7998496899acdd8137fad3a44faf96a84a03d7f230ce42e97cd17c7ae429e'
			),
		);
	}
}
