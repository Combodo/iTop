<?php
/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 17/09/2018
 * Time: 12:31
 */

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use DBSearch;

/**
 * Tests of the DBSearch class.
 * <ul>
 * <li>MakeGroupByQuery</li>
 * </ul>
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class DBSearchCommitTest extends ItopDataTestCase
{
	// Need database COMMIT in order to create the FULLTEXT INDEX of MySQL
	const USE_TRANSACTION = false;

	/**
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 * @throws \Exception
	 */
	public function testAttributeTagSet()
	{
		// Create a tag
		$this->CreateTagData(TAG_CLASS, TAG_ATTCODE, 'tag1', 'UNIT First');
		$this->CreateTagData(TAG_CLASS, TAG_ATTCODE, 'tag2', 'UNIT Second');
		//Use it
		$oObjWithTagSet = $this->CreateObjectWithTagSet();
		$oObjWithTagSet->Set(TAG_ATTCODE, 'tag1');
		$oObjWithTagSet->DBWrite();

		$oSearch = DBSearch::FromOQL("SELECT ".TAG_CLASS);
		$oSearch->AddCondition(TAG_ATTCODE, 'tag1', 'MATCHES');
		$oSet = new \DBObjectSet($oSearch);
		static::assertEquals(1, $oSet->Count());


		$oObjWithTagSet->Set(TAG_ATTCODE, 'tag1 tag2');
		$oObjWithTagSet->DBWrite();

		$oSet = new \DBObjectSet($oSearch);
		static::assertEquals(1, $oSet->Count());
	}

	/**
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 * @throws \Exception
	 */
	public function testAttributeTagSet2()
	{
		// Create a tag
		$this->CreateTagData(TAG_CLASS, TAG_ATTCODE, 'tag1', 'UNIT First');
		$this->CreateTagData(TAG_CLASS, TAG_ATTCODE, 'tag2', 'UNIT Second');
		//Use it
		$oObjWithTagSet = $this->CreateObjectWithTagSet();
		$oObjWithTagSet->Set(TAG_ATTCODE, 'tag1');
		$oObjWithTagSet->DBWrite();

		$oSearch = DBSearch::FromOQL("SELECT ".TAG_CLASS);
		$oSearch->AddCondition(TAG_ATTCODE, 'tag1');
		$oSet = new \DBObjectSet($oSearch);
		static::assertEquals(1, $oSet->Count());


		$oObjWithTagSet->Set(TAG_ATTCODE, 'tag1 tag2');
		$oObjWithTagSet->DBWrite();

		$oSet = new \DBObjectSet($oSearch);
		static::assertEquals(0, $oSet->Count());
	}

}
