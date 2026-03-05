<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use InlineImage;
use ormDocument;

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

	/**
	 * @covers InlineImage::ReplaceInlineImagesWithBase64Representation
	 */
	public function testReplaceInlineImagesWithBase64Representation()
	{
		// create an inline image in the database
		$oInlineImage = $this->createObject(InlineImage::class, [
			'expire' => (new \DateTime('+1 day'))->format('Y-m-d H:i:s'),
			'item_class' => 'UserRequest',
			'item_id' => 999,
			'item_org_id' => 1,
			'contents' => new ormDocument('0x89504E470D0A1A0A0000000D494844520000000E0000000E08060000001F482DD1000000017352474200AECE1CE90000000467414D410000B18F0BFC6105000000097048597300000EC300000EC301C76FA8640000001E49444154384F63782BA3F29F1CCC802E402C1ED588078F6AC483E9AF11008B8BA9C08A7A3F290000000049454E44AE426082', 'image/png', 'square_red.png'),
			'secret' => 'a94bff3ea6a872bdbc359a1704cdddb3',
		]);
		$sInlineImageId = $oInlineImage->GetKey();
		$sInlineImageSecret = $oInlineImage->Get('secret');

		// HTML with inline image
		$sHtml = <<<HTML
<img src="http://host/iTop/pages/ajax.document.php?operation=download_inlineimage&amp;id=$sInlineImageId&amp;s=$sInlineImageSecret" data-img-id="$sInlineImageId" data-img-secret="$sInlineImageSecret" />
HTML;

		// expected HTML with base64 representation of the image
		$sExpected = <<<HTML
<img src="data:image/png;base64,MHg4OTUwNEU0NzBEMEExQTBBMDAwMDAwMEQ0OTQ4NDQ1MjAwMDAwMDBFMDAwMDAwMEUwODA2MDAwMDAwMUY0ODJERDEwMDAwMDAwMTczNTI0NzQyMDBBRUNFMUNFOTAwMDAwMDA0Njc0MTRENDEwMDAwQjE4RjBCRkM2MTA1MDAwMDAwMDk3MDQ4NTk3MzAwMDAwRUMzMDAwMDBFQzMwMUM3NkZBODY0MDAwMDAwMUU0OTQ0NDE1NDM4NEY2Mzc4MkJBM0YyOUYxQ0NDODAyRTQwMkMxRUQ1ODgwNzhGNkFDNDgzRTlBRjExMDA4QjhCQTlDMDhBN0EzRjI5MDAwMDAwMDA0OTQ1NEU0NEFFNDI2MDgy"  />
HTML;

		// test the method
		$sResult = InlineImage::ReplaceInlineImagesWithBase64Representation($sHtml);
		$this->assertEquals($sExpected, $sResult);
	}
}
