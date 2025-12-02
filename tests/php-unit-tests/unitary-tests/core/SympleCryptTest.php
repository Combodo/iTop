<?php

/*!
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use SodiumException;

/**
 * Tests of the ormPassword class
 */
class SympleCryptTest extends ItopDataTestCase
{
	public function DecryptClassProvider()
	{
		$aClassProvider =  ['SimpleCrypt' => ['SimpleCrypt'],
			'SimpleCryptSimpleEngine' => ['SimpleCryptSimpleEngine']];
		if (function_exists('sodium_crypto_secretbox_open')) {
			$aClassProvider['SimpleCryptSodiumEngine'] = ['SimpleCryptSodiumEngine'] ;
		}
		if (function_exists('openssl_decrypt')) {
			$aClassProvider['SimpleCryptOpenSSLEngine'] = ['SimpleCryptOpenSSLEngine'];
			$aClassProvider['SimpleCryptOpenSSLMcryptCompatibilityEngine'] = ['SimpleCryptOpenSSLMcryptCompatibilityEngine'];
		}
		return$aClassProvider;
	}
	/**
	 * @param $sClass
	 * @dataProvider DecryptClassProvider
	 **/
	public function testDecryptWithNullValue($sClass)
	{
		$oSimpleCrypt = new $sClass();
		$this->assertEquals(null, $oSimpleCrypt->Decrypt("dd", null));
	}

	/**
	 * @param $sClass
	 * @dataProvider DecryptClassProvider
	 **/
	public function testDecryptWithEmptyValue($sClass)
	{
		$oSimpleCrypt = new $sClass();
		$this->assertEquals('', $oSimpleCrypt->Decrypt("dd", ""));
	}

	public function DecryptClassWithNonDecryptableValueProvider()
	{
		$aClassProvider =  ['SimpleCrypt' => ['SimpleCrypt', '** decryption error **'],
		//    'SimpleCryptSimpleEngine'=>['SimpleCryptSimpleEngine', '   ']
		];
		if (function_exists('sodium_crypto_secretbox_open')) {
			$aClassProvider['SimpleCryptSodiumEngine'] = ['SimpleCryptSodiumEngine', '', 'SodiumException'] ;
		}
		if (function_exists('openssl_decrypt')) {
			$aClassProvider['SimpleCryptOpenSSLEngine'] = ['SimpleCryptOpenSSLEngine', '** decryption error **'];
			$aClassProvider['SimpleCryptOpenSSLMcryptCompatibilityEngine'] = ['SimpleCryptOpenSSLMcryptCompatibilityEngine', '** decryption error **'];
		}
		return$aClassProvider;
	}
	/**
	 * @param $sClass
	 * @param $sExpectedValue
	 * @dataProvider DecryptClassWithNonDecryptableValueProvider
	 **/
	public function testDecrypWithNonDecryptableValue($sClass, $sExpectedValue = '', $sExpectedException = null)
	{
		if ($sExpectedException !== null) {
			$this->expectException($sExpectedException);
		}
		$oSimpleCrypt = new $sClass();
		$result = $oSimpleCrypt->Decrypt("dd", "gabuzomeuuofteod");
		$this->assertEquals($sExpectedValue, $result, '');
	}

}
