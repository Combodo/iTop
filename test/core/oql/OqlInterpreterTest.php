<?php
/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 27/04/2018
 * Time: 09:52
 */

namespace Combodo\iTop\Test\UnitTest\Core\Oql;

use DBObjectSearch;
use DBObjectSet;
use ExpressionCache;
use OqlInterpreter;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use OqlNormalizeException;
use OQLParserException;

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
        require_once(APPROOT.'core/dbobjectset.class.php');
        require_once(APPROOT."core/modelreflection.class.inc.php");

        require_once(APPROOT."core/expressioncache.class.inc.php");
        $sCacheFileName = ExpressionCache::GetCacheFileName();
        unlink($sCacheFileName);
        ExpressionCache::Warmup();
	}




    public function testShorthandExpansionCloningOption()
    {
        $sQuery = "SELECT Contact  WHERE org_id->deliverymodel_id->name = 'Standard support' AND 1 AND 1 AND 1";

        $oDbObjectSearch = DBObjectSearch::FromOQL($sQuery);
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
    public function testShorthandExpansion($sQuery, $sExpectedException, $sExpectedOqlEquals, $aExpectedSqlContains)
    {

        if (!empty($sExpectedException)) {
            $this->expectException($sExpectedException);
        }

        $oDbObjectSearch = DBObjectSearch::FromOQL($sQuery);

        $sSql = $oDbObjectSearch->MakeSelectQuery();
        $sOql = $oDbObjectSearch->ToOQL();
        $oSet = new DBObjectSet($oDbObjectSearch);
        $iCount = $oSet->Count();

        $this->assertInternalType('numeric', $iCount);
        $this->assertEquals($sExpectedOqlEquals, $sOql);
        foreach ($aExpectedSqlContains as $sExpectedSqlContain)
        {
            $this->assertContains($sExpectedSqlContain, $sSql);
        }

        $this->debug($sSql);
        $this->debug($sOql);
    }

    public function ShorthandExpansionProvider()
    {
        return array(
            array("SELECT Contact WHERE org_id->deliverymodel_id->name = 'Standard support'", false, 'SELECT `Contact` FROM Contact AS `Contact` WHERE (Contact.org_id->deliverymodel_id->name = \'Standard support\')', array(
                'JOIN (`organization`',
                'JOIN `deliverymodel`',
            )),
            array('SELECT Contact WHERE org_id->deliverymodel_id->contacts_list->role_id->name != ""', OqlNormalizeException::class, '', array()),
            array('SELECT Contact WHERE cis_list->name = "Cluster1"', OqlNormalizeException::class, '', array()),
            array('SELECT Contact WHERE cis_list->name LIKE "%m%"', OqlNormalizeException::class, '', array()),
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

        $this->assertInstanceOf(\OqlObjectQuery::class, $oTrash);


        $this->debug($sQuery);
    }

    public function ParseProvider()
    {
        return array(
            array("SELECT Contact WHERE org_id->deliverymodel_id->name = 'Standard support'"),
            array("SELECT Contact WHERE org_id->foo->bar LIKE 'Standard support'"),
            array("SELECT Contact WHERE cis_list->name = 3"),
            array("SELECT Contact WHERE cis_list->name < 3"),
            array("SELECT Contact WHERE cis_list->name >- 3"),
        );
    }

}
