<?php

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use Config;
use MetaModel;
use Person;
use utils;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class AttributeImageTest extends ItopDataTestCase
{
	public function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('core/attributedef.class.inc.php');
	}

	public function testGet(): void
	{
		$oConfig = utils::GetConfig();
		$oPersonPictureAttDef = MetaModel::GetAttributeDef(Person::class, 'picture');

		$sAppRootUrl1 = 'http://localhost/iTop/';
		$this->SetNewAppRootUrl($oConfig, $sAppRootUrl1);
		$sPersonPictureDefaultImageUrl = $oPersonPictureAttDef->Get('default_image');
		$this->assertStringStartsWith($sAppRootUrl1, $sPersonPictureDefaultImageUrl);

		$sAppRootUrl2 = 'https://demo.combodo.com/simple/';
		$this->SetNewAppRootUrl($oConfig, $sAppRootUrl2);
		$oConfig->Set('app_root_url', $sAppRootUrl2);
		$sPersonPictureDefaultImageUrl = $oPersonPictureAttDef->Get('default_image');
		$this->assertStringStartsWith($sAppRootUrl2, $sPersonPictureDefaultImageUrl);
	}

	/**
	 * Note that we need to reset manually the cache in \utils::GetAbsoluteUrlAppRoot, which is called from \AttributeImage::Get
	 */
	private function SetNewAppRootUrl(Config $oConfig, string $sAppRootUrl):void {
		$oConfig->Set('app_root_url', $sAppRootUrl);
		$this->SetNonPublicStaticProperty(utils::class, 'sAbsoluteUrlAppRootCache', null);
	}
}
