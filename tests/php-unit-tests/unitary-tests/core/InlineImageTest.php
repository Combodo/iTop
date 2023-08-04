<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
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
}
