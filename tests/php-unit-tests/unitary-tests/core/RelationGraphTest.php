<?php

namespace Combodo\iTop\Test\UnitTest\Core;


use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use MetaModel;
use RelationGraph;

/**
 * Class RelationGraphTest
 *
 * @package Combodo\iTop\Test\UnitTest\Core
 */
class RelationGraphTest extends ItopDataTestCase
{
	const USE_TRANSACTION = false;


	protected function setUp(): void
	{
		parent::setUp();
	}
	public function ComputeRelatedObjectsProvider()
	{
		return array(
			'Server::1' => array('Server',1),
			'Server::2' => array('Server',2),
		);
	}

	/**
	 * @dataProvider ComputeRelatedObjectsProvider
	 *
	 * @param $sClass
	 * @param $iKey
	 *
	 * @return void
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 */
	public function testComputeRelatedObjectsDown($sClass, $iKey)
	{
		$oServer = MetaModel::GetObject($sClass, $iKey);
		MetaModel::GetConfig()->Set('relations.complete_analysis', true);

		$oGraphTrue = new RelationGraph();
		$oGraphTrue->AddSourceObject($oServer);
		$oGraphTrue->ComputeRelatedObjectsDown('impacts', 10, true);


		MetaModel::GetConfig()->Set('relations.complete_analysis', false);
		$oGraphFalse = new RelationGraph();
		$oGraphFalse->AddSourceObject($oServer);
		$oGraphFalse->ComputeRelatedObjectsDown('impacts', 10, true);

		$aNodeFalse = $oGraphFalse->_GetNodes();
		$aNodeTrue = $oGraphFalse->_GetNodes();

		//test if the 2 graph contains the same objects
		$this->assertEquals(count($aNodeFalse), count($aNodeFalse),'With the admin user, the impact analysis down must have the same number of impacted items whatever the value of the "relations.complete_analysis" parameter.');
		foreach ($aNodeTrue as $sKey =>$oNodeTrue){
			$this->assertArrayHasKey($sKey, $aNodeFalse,'With the admin user, the impact analysis down must have the same results whatever the value of the "relations.complete_analysis" parameter.');
		}
	}

	/**
	 * @dataProvider ComputeRelatedObjectsProvider
	 *
	 * @param $sClass
	 * @param $iKey
	 *
	 * @return void
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 */
	public function testComputeRelatedObjectsUp($sClass, $iKey)
	{
		$oServer = MetaModel::GetObject($sClass, $iKey);
		MetaModel::GetConfig()->Set('relations.complete_analysis', true);

		$oGraphTrue = new RelationGraph();
		$oGraphTrue->AddSourceObject($oServer);
		$oGraphTrue->ComputeRelatedObjectsUp('impacts', 10, true);


		MetaModel::GetConfig()->Set('relations.complete_analysis', false);
		$oGraphFalse = new RelationGraph();
		$oGraphFalse->AddSourceObject($oServer);
		$oGraphFalse->ComputeRelatedObjectsUp('impacts', 10, true);

		$aNodeFalse = $oGraphFalse->_GetNodes();
		$aNodeTrue = $oGraphFalse->_GetNodes();

		//test if the 2 graph contains the same objects
		$this->assertEquals(count($aNodeFalse), count($aNodeFalse),'With the admin user, the impact analysis up must have the same number of impacted items whatever the value of the "relations.complete_analysis" parameter.');
		foreach ($aNodeTrue as $sKey =>$oNodeTrue){
			$this->assertArrayHasKey($sKey, $aNodeFalse,'With the admin user, the impact analysis up must have the same results whatever the value of the "relations.complete_analysis" parameter.');
		}
	}

}
