<?php
/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 27/04/2018
 * Time: 09:52
 */

namespace Combodo\iTop\Test\UnitTest\Core\Oql;

use OqlInterpreter;
use Combodo\iTop\Test\UnitTest\ItopTestCase;

class OqlInterpreterTest extends ItopTestCase
{
	/**
	 * @throws \Exception
	 */
	protected function setUp()
	{
		parent::setUp();

		require_once(APPROOT.'/core/cmdbobject.class.inc.php');
		require_once(APPROOT."core/oql/oqlinterpreter.class.inc.php");
	}

	/**
	 * @dataProvider ParseProvider
	 * @param $sQuery
	 */
	public function testParse($sQuery)
	{
		$oOql = new OqlInterpreter($sQuery);
		$oTrash = $oOql->Parse(); // Not expecting a given format, otherwise use ParseExpression/ParseObjectQuery/ParseValueSetQuery

		$this->debug($oTrash);
	}

	public function ParseProvider()
	{
		return array(
			array("SELECT Contact WHERE org_id->deliverymodel_id->name = 'toto'"),
			array("SELECT Contact WHERE cis_list->name = 'toto'"),
		);
	}
}
