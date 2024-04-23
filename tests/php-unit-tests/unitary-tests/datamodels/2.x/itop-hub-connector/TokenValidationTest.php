<?php
declare(strict_types=1);

namespace Combodo\iTop\Test\UnitTest\Module\LaunchTest;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use TokenValidation;

class TokenValidationTest extends ItopDataTestCase
{
	/**
	 * @param string $sSetupToken
	 *
	 * @return string
	 */
	public function createSetupTokenFile(string $sSetupToken): string
	{
		$sSetupTokenFile = APPROOT.'data/.setup';
		file_put_contents($sSetupTokenFile, $sSetupToken);

		return $sSetupTokenFile;
	}protected function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('datamodels/2.x/itop-hub-connector/TokenValidation.php');
	}

	public function testLaunch()
	{
		$oTokenValidation = new TokenValidation();

		$sSetupToken = bin2hex(random_bytes(12));
		$this->assertFalse($oTokenValidation->isSetupTokenValid('lol'));
		$this->assertFalse($oTokenValidation->isSetupTokenValid(''));
		$this->assertFalse($oTokenValidation->isSetupTokenValid($sSetupToken));
		$this->createSetupTokenFile($sSetupToken);
		$this->assertFalse($oTokenValidation->isSetupTokenValid('lol'));
		$this->createSetupTokenFile($sSetupToken);
		$this->assertFalse($oTokenValidation->isSetupTokenValid(''));
		$this->createSetupTokenFile($sSetupToken);
		$this->assertTrue($oTokenValidation->isSetupTokenValid($sSetupToken));
	}
}
