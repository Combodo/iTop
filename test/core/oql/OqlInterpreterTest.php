<?php
/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 27/04/2018
 * Time: 09:52
 */

namespace Combodo\iTop\Test\UnitTest\Core\Oql;

use OqlInterpreter;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

class OqlInterpreterTest extends ItopDataTestCase
{
	/**
	 * @throws \Exception
	 */
	protected function setUp()
	{
		parent::setUp();

		require_once(APPROOT.'/core/cmdbobject.class.inc.php');
        require_once(APPROOT."core/oql/oqlinterpreter.class.inc.php");
        require_once(APPROOT.'core/dbobject.class.php');
        require_once(APPROOT."core/dbobjectsearch.class.php");
        require_once(APPROOT."core/modelreflection.class.inc.php");


	}




    public function testShorthandExpansionCloningOption()
    {
        $sQuery = "SELECT Contact  WHERE org_id->deliverymodel_id->name = 'Standard support' AND 1 AND 1 AND 1";

        $oDbObjectSearch = \DBObjectSearch::FromOQL($sQuery);
        $oDbObjectSearchExpandedClone1 = $oDbObjectSearch->ShorthandExpansion(true);
        $oDbObjectSearchExpandedClone2 = $oDbObjectSearch->ShorthandExpansion(true);
        $oDbObjectSearchExpandedBase1 = $oDbObjectSearch->ShorthandExpansion();
        $oDbObjectSearchExpandedBase2 = $oDbObjectSearch->ShorthandExpansion();

        $this->assertSame($oDbObjectSearchExpandedBase1, $oDbObjectSearch);
        $this->assertSame($oDbObjectSearchExpandedBase2, $oDbObjectSearch);

        $this->assertNotSame($oDbObjectSearchExpandedClone1, $oDbObjectSearch);
        $this->assertNotSame($oDbObjectSearchExpandedClone2, $oDbObjectSearch);

        $this->assertNotSame($oDbObjectSearchExpandedClone1, $oDbObjectSearchExpandedClone2);

        $this->debug($oDbObjectSearch->ToOQL());
    }

    /**
     * @dataProvider ShorthandExpansionProvider
     * @param $sQuery
     */
    public function testShorthandExpansion($sQuery)
    {
        $oDbObjectSearch = \DBObjectSearch::FromOQL($sQuery);
        $oDbObjectSearchExpanded = $oDbObjectSearch->ShorthandExpansion();


        $this->assertSame($oDbObjectSearch, $oDbObjectSearchExpanded);

        $this->debug($oDbObjectSearch->ToOQL());
    }

    public function ShorthandExpansionProvider()
    {
        return array(
            array("SELECT Contact WHERE org_id->deliverymodel_id->name = 'tato'"),
            array('SELECT Contact WHERE cis_list->name = "Cluster1"'),
            array('SELECT Contact WHERE cis_list->name like "%m%"'),
        );
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
            array("SELECT Contact WHERE org_id->deliverymodel_id->name = 'Standard support'"),
            array("SELECT Contact WHERE cis_list->name = 'toto'"),
        );
    }

}
