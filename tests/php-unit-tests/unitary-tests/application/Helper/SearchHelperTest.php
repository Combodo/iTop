<?php
/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Application\Helper;

use Combodo\iTop\Application\Helper\SearchHelper;
use Combodo\iTop\Application\WebPage\iTopWebPage;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use DBSearch;
use MetaModel;

class SearchHelperTest extends ItopDataTestCase
{
	protected static array $aHighCardinalityClasses = [];
	protected static bool $bSearchManualSubmit = false;

	protected function setUp(): void
	{
		parent::setUp();
		self::$aHighCardinalityClasses = MetaModel::GetConfig()->Get('high_cardinality_classes');
		self::$bSearchManualSubmit = MetaModel::GetConfig()->Get('search_manual_submit');
	}

	protected function tearDown(): void
	{
		parent::tearDown();
		MetaModel::GetConfig()->Set('high_cardinality_classes', static::$aHighCardinalityClasses);
		MetaModel::GetConfig()->Set('search_manual_submit', static::$bSearchManualSubmit);
	}

	public function testDisplaySearchSetWithNoHighCardinalityClassesAddsResultSubBlock(): void
	{
		MetaModel::GetConfig()->Set('high_cardinality_classes', []);
		MetaModel::GetConfig()->Set('search_manual_submit', false);

		$oP = new iTopWebPage('SearchHelperTest');
		$oFilter = DBSearch::FromOQL('SELECT UserRequest');
		SearchHelper::DisplaySearchSet($oP, $oFilter);
		$oContentLayout = $oP->GetContentLayout();
		$this->assertTrue($oContentLayout->HasSubBlock('search_1'));
		$oSearchBlock = $oContentLayout->getSubBlock('search_1');
		$this->assertTrue($oSearchBlock->HasSubBlock('result_1'));

		if (ob_get_level() > 0) {
			ob_end_clean();
		}
	}

	public function testDisplaySearchSetWithHighCardinalityClassesDoesNotAddResultSubBlock(): void
	{
		MetaModel::GetConfig()->Set('high_cardinality_classes', ['UserRequest']);
		MetaModel::GetConfig()->Set('search_manual_submit', false);

		$oP = new iTopWebPage('SearchHelperTest');
		$oFilter = DBSearch::FromOQL('SELECT UserRequest');
		SearchHelper::DisplaySearchSet($oP, $oFilter);
		$oContentLayout = $oP->GetContentLayout();
		$this->assertTrue($oContentLayout->HasSubBlock('search_1'));
		$oSearchBlock = $oContentLayout->getSubBlock('search_1');
		$this->assertFalse($oSearchBlock->HasSubBlock('result_1'));

		if (ob_get_level() > 0) {
			ob_end_clean();
		}
	}

	public function testDisplaySearchSetWithSearchManualSubmitAndWithoutHighCardinalityClassesDoesNotAddResultSubBlock(): void
	{
		MetaModel::GetConfig()->Set('high_cardinality_classes', []);
		MetaModel::GetConfig()->Set('search_manual_submit', true);

		$oP = new iTopWebPage('SearchHelperTest');
		$oFilter = DBSearch::FromOQL('SELECT UserRequest');
		SearchHelper::DisplaySearchSet($oP, $oFilter);
		$oContentLayout = $oP->GetContentLayout();
		$this->assertTrue($oContentLayout->HasSubBlock('search_1'));
		$oSearchBlock = $oContentLayout->getSubBlock('search_1');
		$this->assertFalse($oSearchBlock->HasSubBlock('result_1'));

		if (ob_get_level() > 0) {
			ob_end_clean();
		}
	}
}
