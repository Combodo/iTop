<?php

/*!
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Application\WebPage\CaptureWebPage;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use ormDocument;
use UserRights;

/**
 * Tests of the ormDocument class
 */
class ormDocumentTest extends ItopDataTestCase
{
	private const RESTRICTED_PROFILE = 'Configuration Manager';
	private int $iUserOrg;
	private int $iOrgDifferentFromUser;

	protected function setUp(): void
	{
		parent::setUp();

		$this->iUserOrg = $this->GivenObjectInDB('Organization', [
			'name' => 'UserOrg',
		]);

		$this->iOrgDifferentFromUser = $this->GivenObjectInDB('Organization', [
			'name' => 'OrgDifferentFromUser',
		]);

		$this->LoginRestrictedUser($this->iUserOrg, self::RESTRICTED_PROFILE);
		$this->ResetMetaModelQueyCacheGetObject();
	}

	/**
	 * @inheritDoc
	 */
	protected function LoadRequiredItopFiles(): void
	{
		parent::LoadRequiredItopFiles();

		$this->RequireOnceItopFile('core/ormdocument.class.inc.php');
	}

	/**
	 * @param array $aDocAData
	 * @param array $aDocBData
	 * @param bool $bExpectedResult
	 *
	 * @dataProvider EqualsExceptDownloadsCountProvider
	 */
	public function testEqualsExceptDownloadsCount(array $aDocAData, array $aDocBData, bool $bExpectedResult)
	{
		$oDocA = new ormDocument(base64_decode($aDocAData[0]), $aDocAData[1], $aDocAData[2], $aDocAData[3]);
		$oDocB = new ormDocument(base64_decode($aDocBData[0]), $aDocBData[1], $aDocBData[2], $aDocBData[3]);

		$bTestedResult = $oDocA->EqualsExceptDownloadsCount($oDocB);
		$this->assertSame($bExpectedResult, $bTestedResult);
	}

	public function EqualsExceptDownloadsCountProvider(): array
	{
		$sFirstDummyTextFileContentBase64 = "Rmlyc3Q=";
		$sSecondDummyTextFileContentBase64 = "U2Vjb25k";

		return [
			'Total different files' => [
				[
					$sFirstDummyTextFileContentBase64,
					"text/plain",
					"a.txt",
					0,
				],
				[
					$sSecondDummyTextFileContentBase64,
					"image/png",
					"b.png",
					1,
				],
				false,
			],
			'Different data only' => [
				[
					$sFirstDummyTextFileContentBase64,
					"text/plain",
					"a.txt",
					0,
				],
				[
					$sSecondDummyTextFileContentBase64,
					"text/plain",
					"a.txt",
					0,
				],
				false,
			],
			'Different mime types only' => [
				[
					$sFirstDummyTextFileContentBase64,
					"text/plain",
					"a.txt",
					0,
				],
				[
					$sFirstDummyTextFileContentBase64,
					"image/png",
					"a.txt",
					0,
				],
				false,
			],
			'Different file names only' => [
				[
					$sFirstDummyTextFileContentBase64,
					"text/plain",
					"a.txt",
					0,
				],
				[
					$sFirstDummyTextFileContentBase64,
					"text/plain",
					"b.txt",
					0,
				],
				false,
			],
			'Different download counts only' => [
				[
					$sFirstDummyTextFileContentBase64,
					"text/plain",
					"a.txt",
					0,
				],
				[
					$sFirstDummyTextFileContentBase64,
					"text/plain",
					"a.txt",
					1,
				],
				true,
			],
			'Identical files, different object instances' => [
				[
					$sFirstDummyTextFileContentBase64,
					"text/plain",
					"a.txt",
					0,
				],
				[
					$sFirstDummyTextFileContentBase64,
					"text/plain",
					"a.txt",
					0,
				],
				false,
			],
		];
	}

	public function testResizeImageToFitShouldResizeImageWhenImageIsTooBig()
	{
		$sImageData = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAoAAAAICAIAAABPmPnhAAAAe0lEQVQI132OMQoCMRRE3/9Z3M126V0kB9BCvICnziXs7QIWlttqpWMRFQT1VcMbGMb4xPoQ18uWL4eTxxglSaq1Au8OwM1TSi3nnLGnzxKA4fM8N1VKQVyPZ6Br6s4Xhj7st9OwcNy61yUsGEK3Nmu+mUawcbfiN85fHsBoHdXt5HATAAAAAElFTkSuQmCC');
		$sMimeType = 'image/png';
		$sFileName = 'MyImage.png';
		$oDoc = new ormDocument($sImageData, $sMimeType, $sFileName);
		$iMawWidth = 6;
		$iMaxHeight = 5;

		$oResult = $oDoc->ResizeImageToFit($iMawWidth, $iMaxHeight, $aDimensions);

		$aRealDimensions = \utils::GetImageSize($oResult->GetData());
		$aActualDimensions = [
			'width' => $aRealDimensions[0],
			'height' => $aRealDimensions[1],
		];

		$this->assertNotSame($oDoc, $oResult, 'ResizeImageToFit should return a new object when there have been some modifications');
		$this->assertIsArray($aDimensions, 'ResizeImageToFit should fill aDimension with the dimensions of the new image when there are no issues');
		$this->assertEquals($aDimensions, $aActualDimensions, 'The returned dimensions should match the real dimensions of the image');
		$this->assertLessThanOrEqual($iMawWidth, $aActualDimensions['width'], 'The new width should be less than or equal to max width');
		$this->assertLessThanOrEqual($iMaxHeight, $aActualDimensions['height'], 'The new height should be less than or equal to max height');
	}

	public function testResizeImageToFitShouldDoNothingWhenImageIsAlreadySmallEnough()
	{
		$sImageData = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAoAAAAICAIAAABPmPnhAAAAe0lEQVQI132OMQoCMRRE3/9Z3M126V0kB9BCvICnziXs7QIWlttqpWMRFQT1VcMbGMb4xPoQ18uWL4eTxxglSaq1Au8OwM1TSi3nnLGnzxKA4fM8N1VKQVyPZ6Br6s4Xhj7st9OwcNy61yUsGEK3Nmu+mUawcbfiN85fHsBoHdXt5HATAAAAAElFTkSuQmCC');
		$sMimeType = 'image/png';
		$sFileName = 'MyImage.png';
		$oDoc = new ormDocument($sImageData, $sMimeType, $sFileName);
		$iMawWidth = 10;
		$iMaxHeight = 8;

		$oResult = $oDoc->ResizeImageToFit($iMawWidth, $iMaxHeight, $aDimensions);

		$this->assertSame($oDoc, $oResult, 'ResizeImageToFit should return the same object when there have been no modifications');
		$this->assertIsArray($aDimensions, 'ResizeImageToFit should fill aDimension with the dimensions of the image when there are no issues');
	}

	public function testResizeImageToFitShouldDoNothingWhenItCannotReadTheImage()
	{
		$sImageData = 'garbagedata';
		$sMimeType = 'image/png';
		$sFileName = 'MyImage.png';
		$oDoc = new ormDocument($sImageData, $sMimeType, $sFileName);
		$iMawWidth = 10;
		$iMaxHeight = 8;

		$oResult = $oDoc->ResizeImageToFit($iMawWidth, $iMaxHeight, $aDimensions);

		$this->assertSame($oDoc, $oResult, 'ResizeImageToFit should return the same object when there have been no modifications');
		$this->assertNull($aDimensions, 'ResizeImageToFit should fill aDimension with null when there are issues');
	}

	public function testResizeImageToFitShouldDoNothingWhenItDoesNotHandleTheMimeType()
	{
		$sImageData = base64_decode('Qk3mAAAAAAAAAEYAAAA4AAAACgAAAAgAAAABABAAAwAAAKAAAAAjLgAAIy4AAAAAAAAAAAAAAHwAAOADAAAfAAAAAAAAAMQExATEBMQExATEBMQExATEBMQExATEBMQExATEBMQExATEBMQExAQAAAAAAAAAAAAAAAAgBMQgxATEBAAAAAAAAAAAAAAAACAExCAgBAAAIQT/f/9/1loAACAAxATEGMQEAABjDP9//3//fwAAxATEBMQUxAQAACEE/3//f3tvAADEBMQExATEBAAAAAAAAAAAAAAAACAAxATEBMQEAAA=');
		$sMimeType = 'image/bmp';
		$sFileName = 'MyImage.bmp';
		$oDoc = new ormDocument($sImageData, $sMimeType, $sFileName);
		$iMawWidth = 5;
		$iMaxHeight = 5;

		$oResult = $oDoc->ResizeImageToFit($iMawWidth, $iMaxHeight, $aDimensions);

		$this->assertSame($oDoc, $oResult, 'ResizeImageToFit should return the same object when there have been no modifications');
		$this->assertNull($aDimensions, 'ResizeImageToFit should fill aDimension with null when there are issues');
	}

	public function testResizeImageToFitShouldNotResizeWhenMaximumIs0()
	{
		$sImageData = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAoAAAAICAIAAABPmPnhAAAAe0lEQVQI132OMQoCMRRE3/9Z3M126V0kB9BCvICnziXs7QIWlttqpWMRFQT1VcMbGMb4xPoQ18uWL4eTxxglSaq1Au8OwM1TSi3nnLGnzxKA4fM8N1VKQVyPZ6Br6s4Xhj7st9OwcNy61yUsGEK3Nmu+mUawcbfiN85fHsBoHdXt5HATAAAAAElFTkSuQmCC');
		$sMimeType = 'image/png';
		$sFileName = 'MyImage.png';
		$oDoc = new ormDocument($sImageData, $sMimeType, $sFileName);
		$iMawWidth = 0;
		$iMaxHeight = 0;

		$oResult = $oDoc->ResizeImageToFit($iMawWidth, $iMaxHeight, $aDimensions);

		$this->assertSame($oDoc, $oResult, 'ResizeImageToFit should return the same object when there have been no modifications');
		$this->assertIsArray($aDimensions, 'ResizeImageToFit should fill aDimension with the dimensions of the image when there are no issues');
	}

	public function testResizeImageToFitShouldIgnoreMaximum0Axis()
	{
		$sImageData = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAoAAAAICAIAAABPmPnhAAAAe0lEQVQI132OMQoCMRRE3/9Z3M126V0kB9BCvICnziXs7QIWlttqpWMRFQT1VcMbGMb4xPoQ18uWL4eTxxglSaq1Au8OwM1TSi3nnLGnzxKA4fM8N1VKQVyPZ6Br6s4Xhj7st9OwcNy61yUsGEK3Nmu+mUawcbfiN85fHsBoHdXt5HATAAAAAElFTkSuQmCC');
		$sMimeType = 'image/png';
		$sFileName = 'MyImage.png';
		$oDoc = new ormDocument($sImageData, $sMimeType, $sFileName);
		$iMawWidth = 5;
		$iMaxHeight = 0;

		$oResult = $oDoc->ResizeImageToFit($iMawWidth, $iMaxHeight, $aDimensions);

		$aRealDimensions = \utils::GetImageSize($oResult->GetData());
		$aActualDimensions = [
			'width' => $aRealDimensions[0],
			'height' => $aRealDimensions[1],
		];

		$this->assertNotSame($oDoc, $oResult, 'ResizeImageToFit should return a new object when there have been some modifications');
		$this->assertIsArray($aDimensions, 'ResizeImageToFit should fill aDimension with the dimensions of the new image when there are no issues');
		$this->assertEquals($aDimensions, $aActualDimensions, 'The returned dimensions should match the real dimensions of the image');
		$this->assertEquals($iMawWidth, $aActualDimensions['width'], 'The new width should be exactly the max width');
		$this->assertGreaterThanOrEqual($iMaxHeight, $aActualDimensions['height'], 'The new height should not be 0');
	}

	/**
	 * Test that DownloadDocument enforces rights for documents
	 *
	 * @dataProvider DownloadDocumentRightsProvider
	 */
	public function testDownloadDocumentDifferentOrg(string $sTargetClass, string $sAttCode, string $sData, string $sFileName, ?string $sHostClass)
	{
		$iDeniedDocumentId = $this->CreateDownloadTargetInOrg($sTargetClass, $sAttCode, $this->iOrgDifferentFromUser, $sData, $sFileName, $sHostClass);

		$oPageDenied = new CaptureWebPage();
		ormDocument::DownloadDocument($oPageDenied, $sTargetClass, $iDeniedDocumentId, $sAttCode);
		$sDeniedHtml = (string) $oPageDenied->GetHtml();
		$this->assertStringContainsString(
			'the object does not exist or you are not allowed to view it',
			$sDeniedHtml,
			'Expected error message when rights are missing.'
		);
		$this->assertStringNotContainsString($sData, $sDeniedHtml, 'Unexpected file data present when rights are missing.');
	}

	/**
	 * Test that DownloadDocument allows to retrieve document with the same org (or host object org)
	 *
	 * @dataProvider DownloadDocumentRightsProvider
	 */
	public function testDownloadDocumentSameOrg(string $sTargetClass, string $sAttCode, string $sData, string $sFileName, ?string $sHostClass)
	{
		$iAllowedDocumentId = $this->CreateDownloadTargetInOrg($sTargetClass, $sAttCode, $this->iUserOrg, $sData, $sFileName, $sHostClass);

		$oPageAllowed = new CaptureWebPage();
		ormDocument::DownloadDocument($oPageAllowed, $sTargetClass, $iAllowedDocumentId, $sAttCode);
		$sAllowedHtml = (string) $oPageAllowed->GetHtml();
		$this->assertStringContainsString($sData, $sAllowedHtml, 'Expected file data present when rights are sufficient.');
		$this->assertStringNotContainsString('the object does not exist or you are not allowed to view it', $sAllowedHtml, 'Unexpected error message when rights are sufficient.');
	}

	/**
	 * @dataProvider DownloadDocumentRightsProvider
	 */
	public function testAllowsDownloadingDocumentWhenBypassingRightsChecksWithAllowAllData(string $sTargetClass, string $sAttCode, string $sData, string $sFileName, ?string $sHostClass)
	{
		$iDeniedDocumentId = $this->CreateDownloadTargetInOrg($sTargetClass, $sAttCode, $this->iOrgDifferentFromUser, $sData, $sFileName, $sHostClass);

		$oPageAllowed = new CaptureWebPage();
		ormDocument::DownloadDocument($oPageAllowed, $sTargetClass, $iDeniedDocumentId, $sAttCode, ormDocument::ENUM_CONTENT_DISPOSITION_INLINE, bAllowAllData: true);
		$sAllowedHtml = $oPageAllowed->GetHtml();

		$this->assertStringContainsString($sData, $sAllowedHtml, 'Expected file data present when bypassing rights checks.');
		$this->assertStringNotContainsString("Invalid id ($iDeniedDocumentId) for class '$sTargetClass' - the object does not exist or you are not allowed to view it", $sAllowedHtml, 'Unexpected invalid id error message when bypassing rights checks.');
	}

	public function DownloadDocumentRightsProvider(): array
	{
		return [
			'DocumentFile' => [
				'class' => 'DocumentFile',
				'data_attribute_id' => 'file',
				'data' => 'document_data',
				'file_name' => 'document.txt',
				'host_class' => null],
			'Attachment' => [
				'class' => 'Attachment',
				'data_attribute_id' => 'contents',
				'data' => 'attachment_data',
				'file_name' => 'attachment.txt',
				'host_class' => 'UserRequest'],
		];
	}

	/**
	 * Helper to avoid duplicating object creation in tests
	 * Created objects and host objects depending on the Document class
	 * @param string $sTargetClass
	 * @param string $sAttCode
	 * @param int $iOrgId
	 * @param string $sData
	 * @param string $sFileName
	 * @param string|null $sHostClass
	 * @return int
	 * @throws \Exception
	 */
	private function CreateDownloadTargetInOrg(string $sTargetClass, string $sAttCode, int $iOrgId, string $sData, string $sFileName, ?string $sHostClass): int
	{

		if ($sTargetClass === 'DocumentFile') {
			return $this->GivenObjectInDB($sTargetClass, [
					'name' => 'UnitTestDocFile_'.uniqid(),
				'org_id' => $iOrgId,
				$sAttCode => new ormDocument($sData, 'text/plain', $sFileName),
			]);
		}

		if ($sTargetClass === 'Attachment') {
			$iHostId = $this->GivenObjectInDB($sHostClass, [
				'title' => 'UnitTestUserRequest_'.uniqid(),
				'org_id' => $iOrgId,
				'description' => 'A user request for testing attachment download rights',
			]);

			return $this->GivenObjectInDB('Attachment', [
				'item_class' => $sHostClass,
				'item_id' => $iHostId,
				'item_org_id' => $iOrgId,
				$sAttCode => new ormDocument($sData, 'text/plain', $sFileName),
			]);
		}

		throw new \Exception("Unsupported target class: $sTargetClass");
	}

	private function LoginRestrictedUser(int $iAllowedOrgId, string $sProfileName): void
	{
		if (UserRights::IsLoggedIn()) {
			UserRights::Logoff();
		}
		$sLogin = $this->GivenUserRestrictedToAnOrganizationInDB($iAllowedOrgId, self::$aURP_Profiles[$sProfileName]);
		UserRights::Login($sLogin);
	}
}
