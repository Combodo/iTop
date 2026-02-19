<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use InlineImage;

class InlineImageTest extends ItopDataTestCase
{
	/**
	 * @dataProvider OnFormCancelInvalidTempIdProvider
	 *
	 * @param $sTempId
	 * @param bool $bExpectedReturn
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DeleteException
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 * @covers       InlineImage::OnFormCancel()
	 */
	public function testOnFormCancelInvalidTempId($sTempId, bool $bExpectedReturn)
	{
		$bTestReturn = InlineImage::OnFormCancel($sTempId);
		$this->assertEquals($bExpectedReturn, $bTestReturn);
	}

	public function OnFormCancelInvalidTempIdProvider()
	{
		return [
			'Null temp_id' => [
				null,
				false,
			],
			'Empty temp_id' => [
				'',
				false,
			],
			'0 as integer temp_id' => [
				0,
				true,
			],
			'0 as string temp_id' => [
				'0',
				true,
			],
			'String temp_id' => [
				'fake_temp_id',
				true,
			],
		];
	}

	/**
	 * @covers InlineImage::FixUrls
	 */
	public function testFixUrls_shouldReturnAnEmptyStringIfNullOrEmptyStringPassed()
	{
		$sResult = InlineImage::FixUrls(null);
		$this->assertEquals('', $sResult);

		$sResult = InlineImage::FixUrls('');
		$this->assertEquals('', $sResult);
	}

	/**
	 * @covers InlineImage::FixUrls
	 */
	public function testFixUrls_shouldReturnUnchangedValueIfValueContainsNoImage()
	{
		$sHtml = '<div><p>Texte sans image</p></div>';
		$sResult = InlineImage::FixUrls($sHtml);
		$this->assertEquals($sHtml, $sResult);
	}

	/**
	 * @covers InlineImage::FixUrls
	 */
	public function testFixUrls_shouldReplaceImagesSrcWithCurrentAppRootUrlAndSecret()
	{
		$sHtml = <<<HTML
<div>
	<img src="/images/test1.png" data-img-id="123" data-img-secret="abc" />
	<img src="/images/test2.png" data-img-id="456" data-img-secret="def" />
</div>
HTML;
		$sResult = InlineImage::FixUrls($sHtml);
		$this->assertStringContainsString('<img', $sResult);
		$this->assertStringContainsString(\utils::EscapeHtml(\utils::GetAbsoluteUrlAppRoot().INLINEIMAGE_DOWNLOAD_URL.'123&s=abc'), $sResult);
		$this->assertStringContainsString(\utils::EscapeHtml(\utils::GetAbsoluteUrlAppRoot().INLINEIMAGE_DOWNLOAD_URL.'456&s=def'), $sResult);
	}
}
