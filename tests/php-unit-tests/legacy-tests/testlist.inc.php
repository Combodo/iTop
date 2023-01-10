<?php
// Copyright (C) 2010-2021 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>

/**
 * Core test list
 *
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


class TestBeHappy extends TestHandler // TestFunctionInOut, TestBizModel...
{
	static public function GetName()
	{
		return 'Be happy!';
	}

	static public function GetDescription()
	{
		return 'Sample test with success';
	}

	protected function DoExecute()
	{
		echo "<p>Am I happy?</p>";
		echo "<p>Yes, I am!</p>";
	}
}
class TestBeSad extends TestHandler
{
	static public function GetName()
	{
		return 'Be sad...';
	}

	static public function GetDescription()
	{
		return 'Sample test with failure';
	}

	protected function DoExecute()
	{
		echo "Am I happy?";
		throw new Exception('jamais content');
	}
}

class TestSQLQuery extends TestScenarioOnDB
{
	static public function GetName() {return 'SQLQuery';}
	static public function GetDescription() {return 'SQLQuery does not depend on the rest of the framework, therefore it makes sense to have a separate test framework for it';}

	static public function GetDBHost() {return 'localhost';}
	static public function GetDBUser() {return 'root';}
	static public function GetDBPwd() {return '';}
	static public function GetDBName() {return 'TestSQLQuery';}
	static public function GetDBSubName() {return 'taratata';}


	protected function DoPrepare()
	{
		parent::DoPrepare();
		cmdbSource::CreateTable('CREATE TABLE `myTable` (myKey INT(11) NOT NULL auto_increment, column1 VARCHAR(255), column2 VARCHAR(255), PRIMARY KEY (`myKey`)) ENGINE = '.MYSQL_ENGINE);
		cmdbSource::CreateTable('CREATE TABLE `myTable1` (myKey1 INT(11) NOT NULL auto_increment, column1_1 VARCHAR(255), column1_2 VARCHAR(255), PRIMARY KEY (`myKey1`)) ENGINE = '.MYSQL_ENGINE);
		cmdbSource::CreateTable('CREATE TABLE `myTable2` (myKey2 INT(11) NOT NULL auto_increment, column2_1 VARCHAR(255), column2_2 VARCHAR(255), PRIMARY KEY (`myKey2`)) ENGINE = '.MYSQL_ENGINE);
	}

	protected function DoExecute()
	{
		$oQuery = new SQLObjectQuery(
			$sTable = 'myTable',
			$sTableAlias = 'myTableAlias',
			$aFields = array('column1'=>new FieldExpression('column1', 'myTableAlias'), 'column2'=>new FieldExpression('column2', 'myTableAlias')),
//			$aFullTextNeedles = array('column1'),
			$bToDelete = false,
			$aValues = array()
		);
		$oQuery->AddCondition(Expression::FromOQL('DATE(NOW() - 1200 * 2) > \'2008-07-31\''));

		$oSubQuery1 = new SQLObjectQuery(
			$sTable = 'myTable1',
			$sTableAlias = 'myTable1Alias',
			$aFields = array('column1_1'=>new FieldExpression('column1', 'myTableAlias'), 'column1_2'=>new FieldExpression('column1', 'myTableAlias')),
//			$aFullTextNeedles = array(),
			$bToDelete = false,
			$aValues = array()
		);

		$oSubQuery2 = new SQLObjectQuery(
			$sTable = 'myTable2',
			$sTableAlias = 'myTable2Alias',
			$aFields = array('column2_1'=>new FieldExpression('column2', 'myTableAlias'), 'column2_2'=>new FieldExpression('column2', 'myTableAlias')),
//			$aFullTextNeedles = array(),
			$bToDelete = false,
			$aValues = array()
		);

		$oQuery->AddInnerJoin($oSubQuery1, 'column1', 'column1_1');
		$oQuery->AddLeftJoin($oSubQuery2, 'column2', 'column2_2');
		
		$oQuery->DisplayHtml();
		$oQuery->RenderDelete();
		$oQuery->RenderUpdate();
		echo '<p>'.$oQuery->RenderSelect().'</p>';
		$oQuery->RenderSelect(array('column1'));
		$oQuery->RenderSelect(array('column1', 'column2'));
	}
}

class TestGenericItoMyModel extends TestBizModelGeneric
{
	static public function GetName()
	{
		return 'Generic RO test on '.self::GetConfigFile();
	}

	static public function GetConfigFile() {return '/config-test-mymodel.php';}
}

class TestGenericItopBigModel extends TestBizModelGeneric
{
	static public function GetName()
	{
		return 'Generic RO test on '.self::GetConfigFile();
	}

	static public function GetConfigFile() {return '/config-test-itopv06.php';}
}

class TestUserRightsMatrixItop extends TestUserRights
{
	static public function GetName()
	{
		return 'User rights test on user rights matrix';
	}

	static public function GetDescription()
	{
		return 'blah blah blah';
	}

	public function DoPrepare()
	{
		parent::DoPrepare();
		MetaModel::Startup('../config-test-itopv06.php');
	}

	protected function DoExecute()
	{
		$sUser = 'Romain';
		echo "<p>Totor: ".(UserRights::CheckCredentials('Totor', 'toto') ? 'ok' : 'NO')."</p>\n";
		echo "<p>Romain: ".(UserRights::CheckCredentials('Romain', 'toto') ? 'ok' : 'NO')."</p>\n";
		echo "<p>User: ".UserRights::GetUser()."</p>\n";
		echo "<p>On behalf of...".UserRights::GetRealUser()."</p>\n";

		echo "<p>Denis (impersonate) : ".(UserRights::Impersonate('Denis', 'tutu') ? 'ok' : 'NO')."</p>\n";
		echo "<p>User: ".UserRights::GetUser()."</p>\n";
		echo "<p>On behalf of...".UserRights::GetRealUser()."</p>\n";

		$oSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT bizOrganization"));
		echo "<p>IsActionAllowed...".(UserRights::IsActionAllowed('bizOrganization', UR_ACTION_MODIFY, $oSet) == UR_ALLOWED_YES ? 'ok' : 'NO')."</p>\n";
		echo "<p>IsStimulusAllowed...".(UserRights::IsStimulusAllowed('bizOrganization', 'myStimulus', $oSet) == UR_ALLOWED_YES ? 'ok' : 'NO')."</p>\n";
		echo "<p>IsActionAllowedOnAttribute...".(UserRights::IsActionAllowedOnAttribute('bizOrganization', 'myattribute', UR_ACTION_MODIFY, $oSet) == UR_ALLOWED_YES ? 'ok' : 'NO')."</p>\n";
		return true;
	}
}

///////////////////////////////////////////////////////////////////////////
// Test a complex biz model on the fly
///////////////////////////////////////////////////////////////////////////

class TestMyBizModel extends TestBizModel
{
	static public function GetName()
	{
		return 'A series of tests on a weird business model';
	}

	static public function GetDescription()
	{
		return 'Attempts various operations and build complex queries';
	}
	
	static public function GetConfigFile() {return '/config-test-mymodel.php';}

	function test_linksinfo()
	{
		echo "<h4>Enum links</h4>";
		self::DumpVariable(MetaModel::EnumReferencedClasses("cmdbTeam"));
		self::DumpVariable(MetaModel::EnumReferencingClasses("Organization"));

		self::DumpVariable(MetaModel::GetLinkClasses());
		self::DumpVariable(MetaModel::GetLinkLabel("Liens_entre_contacts_et_workshop", "toworkshop"));
	}
	
	function test_list_attributes()
	{
		echo "<h4>List attributes</h4>";
		foreach(MetaModel::ListAttributeDefs("cmdbTeam") as $sAttCode=>$oAttDef)
		{
			echo $oAttDef->GetLabel()." / ".$oAttDef->GetDescription()." / ".$oAttDef->GetType()."</br>\n";
		}
	}
	
	function test_search()
	{
		echo "<h4>Two searches</h4>";
		$oFilterAllDevs = new DBObjectSearch("cmdbTeam");
		$oAllDevs = new DBObjectSet($oFilterAllDevs);
		
		echo "Found ".$oAllDevs->Count()." items.</br>\n";
		while ($oDev = $oAllDevs->Fetch())
		{
			$aValues = array();
			foreach(MetaModel::GetAttributesList($oAllDevs->GetClass()) as $sAttCode)
			{
				$aValues[] = MetaModel::GetLabel(get_class($oDev), $sAttCode)." (".MetaModel::GetDescription(get_class($oDev), $sAttCode).") = ".$oDev->GetAsHTML($sAttCode);
			}
			echo $oDev->GetKey()." => ".implode(", ", $aValues)."</br>\n";
		}
	
		// a second one
		$oMyFilter = new DBObjectSearch("cmdbContact");
		//$oMyFilter->AddCondition("name", "aii", "Finishes with");
		$oMyFilter->AddCondition("name", "aii");
		$this->search_and_show_list($oMyFilter);
		
	}
	
	function test_reload()
	{
		echo "<h4>Reload</h4>";
		$team = MetaModel::GetObject("cmdbContact", "2");
		echo "Chargement de l'attribut headcount: {$team->Get("headcount")}</br>\n";
		self::DumpVariable($team);
	}
	
	function test_setattribute()
	{
		echo "<h4>Set attribute and update</h4>";
		/** @var cmdbTeam $team */
		$team = MetaModel::GetObject("cmdbTeam", "2");
		$team->Set("headcount", rand(1,1000));
		$team->Set("email", "Luis ".rand(9,250));
		self::DumpVariable($team->ListChanges());
		echo "New headcount = {$team->Get("headcount")}</br>\n";
		echo "Computed name = {$team->Get("name")}</br>\n";

		CMDBObject::SetTrackInfo('test_setattribute / Made by robot #'.rand(1, 100));
		//DBSearch::StartDebugQuery();
		$team->DBUpdate();
		//DBSearch::StopDebugQuery();
	
		echo "<h4>Check the modified team</h4>";
		$oTeam = MetaModel::GetObject("cmdbTeam", "2");
		self::DumpVariable($oTeam);
	}
	function test_newobject()
	{
		echo "<h4>Create a new object (team)</h4>";
		$oNewTeam = MetaModel::NewObject("cmdbTeam");
		$oNewTeam->Set("name", "ekip2choc #".rand(1000, 2000));
		$oNewTeam->Set("email", "machin".rand(1,100)."@tnut.com");
		$oNewTeam->Set("email", null);
		$oNewTeam->Set("owner", "ITOP");
		$oNewTeam->Set("headcount", "0".rand(38000, 38999)); // should be reset to an int value
		$iId = $oNewTeam->DBInsert();
		echo "Created new team: $iId</br>";
		echo "<h4>Delete team #$iId</h4>";
		$oTeam = MetaModel::GetObject("cmdbTeam", $iId);
		$oTeam->DBDelete();
		echo "Deleted team: $iId</br>";
		self::DumpVariable($oTeam);
	}
	
	
	function test_updatecolumn()
	{
		$oMyChange = MetaModel::NewObject("CMDBChange");
		$oMyChange->Set("date", time());
		$oMyChange->Set("userinfo", "test_updatecolumn / Made by robot #".rand(1,100));
		$iChangeId = $oMyChange->DBInsert();
	
		$sNewEmail = "updatecol".rand(9,250)."@quedlaballe.com";
		echo "<h4>Update a the email: set to '$sNewEmail'</h4>";
		$oMyFilter = new DBObjectSearch("cmdbContact");
		$oMyFilter->AddCondition("name", "o", "Contains");
	
		echo "Candidates before:</br>";
		$this->search_and_show_list($oMyFilter);
	
		MetaModel::BulkUpdateTracked($oMyChange, $oMyFilter, array("email" => $sNewEmail));
	
		echo "Candidates after:</br>";
		$this->search_and_show_list($oMyFilter);
	}
	
	function test_error()
	{
		trigger_error("Stop requested", E_USER_ERROR);
	}
	
	function test_changetracking()
	{
		echo '<h4>Create a change</h4>';
		/** @var CMDBChange $oMyChange * */
		$oMyChange = MetaModel::NewObject('CMDBChange');
		$oMyChange->Set('date', time());
		$oMyChange->Set('userinfo', 'Made by robot #'.rand(1, 100));
		self::DumpVariable($oMyChange);

		echo '<h4>Create a new object (team)</h4>';
		$oNewTeam = MetaModel::NewObject('cmdbTeam');
		$oNewTeam->Set('name', 'ekip2choc #'.rand(1000, 2000));
		$oNewTeam->Set('email', 'machin'.rand(1, 100).'@tnut.com');
		$oNewTeam->Set('email', null);
		$oNewTeam->Set('owner', 'ITOP');
		$oNewTeam->Set('headcount', '0'.rand(38000, 38999)); // should be reset to an int value
		$oNewTeam::SetCurrentChange($oMyChange);
		$iId = $oNewTeam->DBInsert();
		echo "Created new team: $iId</br>";
		echo "<h4>Delete team #$iId</h4>";
		$oTeam = MetaModel::GetObject('cmdbTeam', $iId);
		$oTeam::SetCurrentChange($oMyChange);
		$oTeam->DBDelete();
		echo "Deleted team: $iId</br>";
		self::DumpVariable($oTeam);
	}
	
	function test_zlist()
	{
		echo "<h4>Test ZLists</h4>";
		$aZLists = MetaModel::EnumZLists();
		foreach ($aZLists as $sListCode)
		{
			$aListInfos = MetaModel::GetZListInfo($sListCode);
			echo "<h4>List '".$sListCode."' (".$aListInfos["description"].") of type '".$aListInfos["type"]."'</h5>\n";
	
			foreach (MetaModel::GetSubclasses("cmdbObjectHomeMade") as $sKlass)
			{
				$aItems = MetaModel::FlattenZlist(MetaModel::GetZListItems($sKlass, $sListCode));
				if (count($aItems) == 0) continue;
	
				echo "$sKlass - $sListCode : {".implode(", ", $aItems)."}</br>\n";
			}
		}
	
		echo "<h4>IsAttributeInZList()... </h4>";
		echo "Liens_entre_contacts_et_workshop::ws_info in list1 ? ".(MetaModel::IsAttributeInZList("Liens_entre_contacts_et_workshop", "list1", "ws_info") ? "yes" : "no")."</br>\n";
		echo "Liens_entre_contacts_et_workshop::toworkshop in list1 ? ".(MetaModel::IsAttributeInZList("Liens_entre_contacts_et_workshop", "list1", "toworkshop") ? "yes" : "no")."</br>\n";
	
	}
	
	function test_pkey()
	{
		echo "<h4>Test search on pkey</h4>";
		$sExpr1 = "SELECT cmdbContact WHERE id IN (40, 42)";
		$sExpr2 = "SELECT cmdbContact WHERE IN NOT IN (40, 42)";
		$this->search_and_show_list_from_oql($sExpr1);
		$this->search_and_show_list_from_oql($sExpr2);
	
		echo "Et maintenant, on fusionne....</br>\n";
		$oSet1 = new CMDBObjectSet(DBObjectSearch::FromOQL($sExpr1));
		$oSet2 = new CMDBObjectSet(DBObjectSearch::FromOQL($sExpr2));
		$oIntersect = $oSet1->CreateIntersect($oSet2);
		$oDelta = $oSet1->CreateDelta($oSet2);
	
		$oMerge = clone $oSet1;
		$oAppend->Append($oSet2);
		$oAppend->Append($oSet2);
	
		echo "Set1 - Found ".$oSet1->Count()." items.</br>\n";
		echo "Set2 - Found ".$oSet2->Count()." items.</br>\n";
		echo "Intersect - Found ".$oIntersect->Count()." items.</br>\n";
		echo "Delta - Found ".$oDelta->Count()." items.</br>\n";
		echo "Append - Found ".$oAppend->Count()." items.</br>\n";
		//$this->show_list($oObjSet);
	}
	
	function test_relations()
	{
		echo "<h4>Test relations</h4>";
		
		//self::DumpVariable(MetaModel::EnumRelationQueries("cmdbObjectHomeMade", "Potes"));
		self::DumpVariable(MetaModel::EnumRelationQueries("cmdbContact", "Potes"));
	
		$iMaxDepth = 9;
		echo "Max depth = $iMaxDepth</br>\n";
	
		$oObj = MetaModel::GetObject("cmdbContact", 18);
		$aRels = $oObj->GetRelatedObjectsDown("Potes", $iMaxDepth);
		echo $oObj->Get('name')." has some 'Potes'...</br>\n";
		foreach ($aRels as $sClass => $aObjs)
		{
			echo "$sClass, count = ".count($aObjs)." =&gt; ".implode(', ', array_keys($aObjs))."</br>\n";
			$oObjectSet = CMDBObjectSet::FromArray($sClass, $aObjs);
			$this->show_list($oObjectSet);
		}
	
		echo "<h4>Test relations - same results, by the mean of a OQL</h4>";
		$this->search_and_show_list_from_oql("cmdbContact: RELATED (Potes, $iMaxDepth) TO (cmdbContact: pkey = 18)");
		
	}
	
	function test_linkedset()
	{
		echo "<h4>Linked set attributes</h4>\n";
		$oObj = MetaModel::GetObject("cmdbContact", 18);
		
		echo "<h5>Current workshops</h5>\n";
		$oSetWorkshopsCurr = $oObj->Get("myworkshops");
		$this->show_list($oSetWorkshopsCurr);
	
		echo "<h5>Setting workshops</h5>\n";
		$oNewLink = new cmdbLiens();
		$oNewLink->Set('toworkshop', 2);
		$oNewLink->Set('function', 'mafonctioooon');
		$oNewLink->Set('a1', 'tralala1');
		$oNewLink->Set('a2', 'F7M');
		$oSetWorkshops = CMDBObjectSet::FromArray("cmdbLiens", array($oNewLink));
		$oObj->Set("myworkshops", $oSetWorkshops); 
		$this->show_list($oSetWorkshops);
	
		echo "<h5>New workshops</h5>\n";
		$oSetWorkshopsCurr = $oObj->Get("myworkshops");
		$this->show_list($oSetWorkshopsCurr);

		CMDBObject::SetTrackInfo('test_linkedset / Made by robot #'.rand(1, 100));
		$oObj->DBUpdate();
		$oObj = MetaModel::GetObject("cmdbContact", 18);
	
		echo "<h5>After the write</h5>\n";
		$oSetWorkshopsCurr = $oObj->Get("myworkshops");
		$this->show_list($oSetWorkshopsCurr);
	}
	
	function test_object_lifecycle()
	{
		echo "<h4>Test object lifecycle</h4>";
	
	
		self::DumpVariable(MetaModel::GetStateAttributeCode("cmdbContact"));
		self::DumpVariable(MetaModel::EnumStates("cmdbContact"));
		self::DumpVariable(MetaModel::EnumStimuli("cmdbContact"));
		foreach(MetaModel::EnumStates("cmdbContact") as $sStateCode => $aStateDef)
		{
			echo "<p>Transition from <strong>$sStateCode</strong></p>\n";
			self::DumpVariable(MetaModel::EnumTransitions("cmdbContact", $sStateCode));
		}
	
		$oObj = MetaModel::GetObject("cmdbContact", 18);
		echo "Current state: ".$oObj->GetState()."... let's go to school...";
		self::DumpVariable($oObj->EnumTransitions());
		$oObj->ApplyStimulus("toschool");
		echo "New state: ".$oObj->GetState()."... let's get older...";
		self::DumpVariable($oObj->EnumTransitions());
		$oObj->ApplyStimulus("raise");
		echo "New state: ".$oObj->GetState()."... let's try to go further... (should give an error)";
		self::DumpVariable($oObj->EnumTransitions());
		$oObj->ApplyStimulus("raise"); // should give an error
	}


	protected function DoExecute()
	{
//				$this->ReportError("Found two different OQL expression out of the (same?) filter: <em>$sExpr1</em> != <em>$sExpr2</em>");
//			$this->ReportSuccess('Found '.$oSet->Count()." objects of class $sClassName");
		//$this->test_linksinfo();
		//$this->test_list_attributes();
		//$this->test_search();
		//$this->test_reload();
		//$this->test_newobject();
		$this->test_setattribute();
		//$this->test_updatecolumn();
		//$this->test_error();
		//$this->test_changetracking();
		$this->test_zlist();
		$this->test_OQL();
		//$this->test_pkey();
		$this->test_relations();
		$this->test_linkedset();
		$this->test_object_lifecycle();
	}
}


///////////////////////////////////////////////////////////////////////////
// Test queries
///////////////////////////////////////////////////////////////////////////

class TestItopEfficiency extends TestBizModel
{
	static public function GetName()
	{
		return 'Itop - benchmark';
	}

	static public function GetDescription()
	{
		return 'Measure time to perform the queries';
	}

	protected function DoBenchmark($sOqlQuery)
	{
		echo "<h3>Testing query: $sOqlQuery</h3>";

		$fStart = MyHelpers::getmicrotime();
		for($i=0 ; $i < COUNT_BENCHMARK ; $i++)
		{
			$oFilter = DBObjectSearch::FromOQL($sOqlQuery);
		}
		$fDuration = MyHelpers::getmicrotime() - $fStart;
		$fParsingDuration = $fDuration / COUNT_BENCHMARK;

		$fStart = MyHelpers::getmicrotime();
		for($i=0 ; $i < COUNT_BENCHMARK ; $i++)
		{
			$sSQL = $oFilter->MakeSelectQuery();
		}
		$fDuration = MyHelpers::getmicrotime() - $fStart;
		$fBuildDuration = $fDuration / COUNT_BENCHMARK;

		$fStart = MyHelpers::getmicrotime();
		for($i=0 ; $i < COUNT_BENCHMARK ; $i++)
		{
			$res = CMDBSource::Query($sSQL);
		}
		$fDuration = MyHelpers::getmicrotime() - $fStart;
		$fQueryDuration = $fDuration / COUNT_BENCHMARK;

		// The fetch could not be repeated with the same results
		// But we've seen so far that is was very very quick to exec
		// So it makes sense to benchmark it a single time
		$fStart = MyHelpers::getmicrotime();
		$aRow = CMDBSource::FetchArray($res);
		$fDuration = MyHelpers::getmicrotime() - $fStart;
		$fFetchDuration = $fDuration;

		$fStart = MyHelpers::getmicrotime();
		for($i=0 ; $i < COUNT_BENCHMARK ; $i++)
		{
			$sOql = $oFilter->ToOQL();
		}
		$fDuration = MyHelpers::getmicrotime() - $fStart;
		$fToOqlDuration = $fDuration / COUNT_BENCHMARK;

		echo "<ul>\n";
		echo "<li>Parsing: $fParsingDuration</li>\n";
		echo "<li>Build: $fBuildDuration</li>\n";
		echo "<li>Query: $fQueryDuration</li>\n";
		echo "<li>Fetch: $fFetchDuration</li>\n";
		echo "<li>ToOql: $fToOqlDuration</li>\n";
		echo "</ul>\n";

		// Everything but the ToOQL (wich is interesting, anyhow)
		$fTotal = $fParsingDuration + $fBuildDuration + $fQueryDuration + $fFetchDuration; 

		if ($fTotal == 0)
		{
			$aRet = array(
				'rows' => CMDBSource::NbRows($res),
				'duration (s)' => '0 (negligeable)',
				'parsing (%)' => '?',
				'build SQL (%)' => '?',
				'query exec (%)' => '?',
				'fetch (%)' => '?',
				'to OQL (%)' => '?',
				'parsing+build (%)' => '?',
			);
		}
		else
		{
			$aRet = array(
				'rows' => CMDBSource::NbRows($res),
				'duration (s)' => round($fTotal, 4),
				'parsing (%)' => round(100 * $fParsingDuration / $fTotal, 1),
				'build SQL (%)' => round(100 * $fBuildDuration / $fTotal, 1),
				'query exec (%)' => round(100 * $fQueryDuration / $fTotal, 1),
				'fetch (%)' => round(100 * $fFetchDuration / $fTotal, 1),
				'to OQL (%)' => round(100 * $fToOqlDuration / $fTotal, 1),
				'parsing+build (%)' => round(100 * ($fParsingDuration + $fBuildDuration) / $fTotal, 1),
			);
		}
		return $aRet;
	}
	
	protected function DoExecute()
	{
		define ('COUNT_BENCHMARK', 3);
		echo "<p>The test will be repeated ".COUNT_BENCHMARK." times</p>";

		$aQueries = array(
			'SELECT CMDBChangeOpSetAttribute',
			'SELECT CMDBChangeOpSetAttribute WHERE id=10',
			'SELECT CMDBChangeOpSetAttribute WHERE id=123456789',
			'SELECT CMDBChangeOpSetAttribute WHERE CMDBChangeOpSetAttribute.id=10',
			'SELECT Ticket',
			'SELECT Ticket WHERE id=1',
			'SELECT Person',
			'SELECT Person WHERE id=1',
			'SELECT Server',
			'SELECT Server WHERE id=1',
			'SELECT UserRequest JOIN Person ON UserRequest.agent_id = Person.id WHERE Person.id = 5',
		);
		$aStats  = array();
		foreach ($aQueries as $sOQL)
		{
			$aStats[$sOQL] = $this->DoBenchmark($sOQL);
		}

		$aData = array();
		foreach ($aStats as $sOQL => $aResults)
		{
			$aValues = array();
			$aValues['OQL'] = htmlentities($sOQL, ENT_QUOTES, 'UTF-8');

			foreach($aResults as $sDesc => $sInfo)
			{
				$aValues[$sDesc] = htmlentities($sInfo, ENT_QUOTES, 'UTF-8');
			}
			$aData[] = $aValues;
		}
		echo MyHelpers::make_table_from_assoc_array($aData);
	}
}

///////////////////////////////////////////////////////////////////////////
// Benchmark queries
///////////////////////////////////////////////////////////////////////////

class TestQueries extends TestBizModel
{
	static public function GetName()
	{
		return 'Itop - queries';
	}

	static public function GetDescription()
	{
		return 'Try as many queries as possible';
	}

	protected function DoBenchmark($sOqlQuery)
	{
		echo "<h5>Testing query: $sOqlQuery</h5>";

		$fStart = MyHelpers::getmicrotime();
		$oFilter = DBObjectSearch::FromOQL($sOqlQuery);
		$fParsingDuration = MyHelpers::getmicrotime() - $fStart;

		$fStart = MyHelpers::getmicrotime();
		$sSQL = $oFilter->MakeSelectQuery();
		$fBuildDuration = MyHelpers::getmicrotime() - $fStart;

		$iJoins = preg_match_all('/JOIN/', $sSQL) + 1;

		$fStart = MyHelpers::getmicrotime();
		$res = CMDBSource::Query($sSQL);
		$fQueryDuration = MyHelpers::getmicrotime() - $fStart;

		// The fetch could not be repeated with the same results
		// But we've seen so far that is was very very quick to exec
		// So it makes sense to benchmark it a single time
		$fStart = MyHelpers::getmicrotime();
		$aRow = CMDBSource::FetchArray($res);
		$fDuration = MyHelpers::getmicrotime() - $fStart;
		$fFetchDuration = $fDuration;

		$fStart = MyHelpers::getmicrotime();
		$sOql = $oFilter->ToOQL();
		$fToOqlDuration = MyHelpers::getmicrotime() - $fStart;

		// Everything but the ToOQL (which is interesting, anyhow)
		$fTotal = $fParsingDuration + $fBuildDuration + $fQueryDuration + $fFetchDuration;

		if ($fTotal == 0)
		{
			$aRet = array(
				'rows' => CMDBSource::NbRows($res),
				'duration (s)' => '0 (negligeable)',
				'parsing (%)' => '?',
				'build SQL (%)' => '?',
				'query exec (%)' => '?',
				'fetch (%)' => '?',
				'to OQL (%)' => '?',
				'parsing+build (%)' => '?',
				'joins' => $iJoins,
			);
		}
		else
		{
			$aRet = array(
				'rows' => CMDBSource::NbRows($res),
				'duration (s)' => round($fTotal, 4),
				'parsing (%)' => round(100 * $fParsingDuration / $fTotal, 1),
				'build SQL (%)' => round(100 * $fBuildDuration / $fTotal, 1),
				'query exec (%)' => round(100 * $fQueryDuration / $fTotal, 1),
				'fetch (%)' => round(100 * $fFetchDuration / $fTotal, 1),
				'to OQL (%)' => round(100 * $fToOqlDuration / $fTotal, 1),
				'parsing+build (%)' => round(100 * ($fParsingDuration + $fBuildDuration) / $fTotal, 1),
				'joins' => $iJoins,
			);
		}
		return $aRet;
	}
	
	protected function DoExecute()
	{
		$aQueries = array();
		foreach (MetaModel::GetClasses() as $sClass)
		{
			$aQueries[] = 'SELECT '.$sClass;
			$aQueries[] = 'SELECT '.$sClass.' WHERE id = 1';
		}	
		$aStats  = array();
		foreach ($aQueries as $sOQL)
		{
			$aStats[$sOQL] = $this->DoBenchmark($sOQL);
		}

		$aData = array();
		foreach ($aStats as $sOQL => $aResults)
		{
			$aValues = array();
			$aValues['OQL'] = htmlentities($sOQL, ENT_QUOTES, 'UTF-8');

			foreach($aResults as $sDesc => $sInfo)
			{
				$aValues[$sDesc] = htmlentities($sInfo, ENT_QUOTES, 'UTF-8');
			}
			$aData[] = $aValues;
		}
		echo MyHelpers::make_table_from_assoc_array($aData);
	}
}

///////////////////////////////////////////////////////////////////////////
// Check programmaticaly built queries
///////////////////////////////////////////////////////////////////////////

class TestQueriesByAPI extends TestBizModel
{
	static public function GetName()
	{
		return 'Itop - queries build programmaticaly';
	}

	static public function GetDescription()
	{
		return 'Validate the DBObjectSearch API, through a set of complex (though realistic cases)';
	}

	protected function DoExecute()
	{
		// Note: relying on eval() - after upgrading to PHP 5.3 we can move to closure (aka anonymous functions)
		$aQueries = array(
			'Basic (validate the test)' => array(
				'search' => '
$oSearch = DBObjectSearch::FromOQL("SELECT P FROM Organization AS O JOIN Person AS P ON P.org_id = O.id WHERE org_id = 2");
				',
				'oql' => 'SELECT P FROM Organization AS O JOIN Person AS P ON P.org_id = O.id WHERE P.org_id = 2'
			),
			'Double constraint' => array(
				'search' => '
$oSearch = DBObjectSearch::FromOQL("SELECT Contact AS c");
$sClass = $oSearch->GetClass();
$sFilterCode = "org_id";

$oAttDef = MetaModel::GetAttributeDef($sClass, $sFilterCode);

if ($oAttDef->IsExternalKey())
{
	$sHierarchicalKeyCode = MetaModel::IsHierarchicalClass($oAttDef->GetTargetClass());
	
	if ($sHierarchicalKeyCode !== false)
	{
		$oFilter = new DBObjectSearch($oAttDef->GetTargetClass(), "ORGA");
		$oFilter->AddCondition("id", 2);
		$oHKFilter = new DBObjectSearch($oAttDef->GetTargetClass(), "ORGA");
		$oHKFilter->AddCondition_PointingTo(clone $oFilter, $sHierarchicalKeyCode, TREE_OPERATOR_BELOW);

		$oSearch->AddCondition_PointingTo(clone $oHKFilter, $sFilterCode);

		$oFilter = new DBObjectSearch($oAttDef->GetTargetClass(), "ORGA");
		$oFilter->AddCondition("id", 2);
		$oHKFilter = new DBObjectSearch($oAttDef->GetTargetClass(), "ORGA");
		$oHKFilter->AddCondition_PointingTo(clone $oFilter, $sHierarchicalKeyCode, TREE_OPERATOR_BELOW);

		$oSearch->AddCondition_PointingTo(clone $oHKFilter, $sFilterCode);
	}
}
				',
				'oql' => 'SELECT Contact AS C JOIN Organization ???'
			),
			'Simplified issue' => array(
				'search' => '
$oSearch = DBObjectSearch::FromOQL("SELECT P FROM Organization AS O JOIN Person AS P ON P.org_id = O.id WHERE O.id = 2");
$oOrgSearch = new DBObjectSearch("Organization", "O2");
$oOrgSearch->AddCondition("id", 2);
$oSearch->AddCondition_PointingTo($oOrgSearch, "org_id");
				',
				'oql' => 'SELECT P FROM Organization AS O JOIN Person AS P ON P.org_id = O.id JOIN Organization AS O2 ON P.org_id = O2.id WHERE O.id = 2 AND O2.id = 2'
			),
		);
		foreach ($aQueries as $sQueryDesc => $aQuerySpec)
		{
			echo "<h2>Query $sQueryDesc</h2>\n";
			echo "<p>Using code: ".highlight_string("<?php\n".trim($aQuerySpec['search'])."\n?".'>', true)."</p>\n";
			echo "<p>Expected OQL: ".$aQuerySpec['oql']."</p>\n";

			if (isset($oSearch))
			{
				unset($oSearch);
			}
			eval($aQuerySpec['search']);
			$sResOQL = $oSearch->ToOQL();
			echo "<p>Resulting OQL: ".$sResOQL."</p>\n";

			echo "<pre>";
			print_r($oSearch);
			echo "</pre>";

			$sSQL = $oSearch->MakeSelectQuery();
			$res = CMDBSource::Query($sSQL);
			foreach (CMDBSource::ExplainQuery($sSQL) as $aRow)
			{
			}
		}

//		throw new UnitTestException("Expecting result '{$aWebService['expected result']}', but got '$res'");

	}
}

///////////////////////////////////////////////////////////////////////////
// Test bulk load API
///////////////////////////////////////////////////////////////////////////

class TestItopBulkLoad extends TestBizModel
{
	static public function GetName()
	{
		return 'Itop - test BulkChange class';
	}

	static public function GetDescription()
	{
		return 'Execute a bulk change at the Core API level';
	}

	protected function DoExecute()
	{
		$sLogin = 'testbulkload_'.time();

		$oParser = new CSVParser("login,contactid->name,password,profile_list
		_1_$sLogin,Picasso,secret1,profileid:10;reason:service manager|profileid->name:Problem Manager;'reason:toto;problem manager'
		_2_$sLogin,Picasso,secret2,
		", ',', '"');
		$aData = $oParser->ToArray(1, array('_login', '_contact_name', '_password', '_profiles'));
		self::DumpVariable($aData);

		$oUser = new UserLocal();
		$oUser->Set('login', 'patator');
		$oUser->Set('password', 'patator');
		//$oUser->Set('contactid', 0);
		//$oUser->Set('language', $sLanguage);

		$aProfiles = array(
			array(
				'profileid' => 10, // Service Manager
				'reason' => 'service manager',
			),
			array(
				'profileid->name' => 'Problem Manager',
				'reason' => 'problem manager',
			),
		);

		$oBulk = new BulkChange(
			'UserLocal',
			$aData,
			// attributes
			array('login' => '_login', 'password' => '_password', 'profile_list' => '_profiles'),
			// ext keys
			array('contactid' => array('name' => '_contact_name')),
			// reconciliation
			array('login'),
			// Synchro - scope
			"SELECT UserLocal",
			// Synchro - set attribute on missing objects
			array ('password' => 'terminated', 'login' => 'terminated'.time())
		);

		if (false)
		{
		$oMyChange = MetaModel::NewObject("CMDBChange");
		$oMyChange->Set("date", time());
		$oMyChange->Set("userinfo", "Testor");
		$iChangeId = $oMyChange->DBInsert();
//		echo "Created new change: $iChangeId</br>";
		}

		echo "<h3>Planned for loading...</h3>";
		$aRes = $oBulk->Process();
		self::DumpVariable($aRes);
		if (false)
		{
		echo "<h3>Go for loading...</h3>";
		$aRes = $oBulk->Process($oMyChange);
		self::DumpVariable($aRes);
		}

		return;
	}
}


///////////////////////////////////////////////////////////////////////////
// Test data load
///////////////////////////////////////////////////////////////////////////

class TestImportREST extends TestWebServices
{
	static public function GetName()
	{
		return 'CSV import (REST)';
	}

	static public function GetDescription()
	{
		return 'Test various options and fonctionality of import.php';
	}

	protected function DoExecSingleLoad($aLoadSpec, $iTestId = null)
	{
		$sCsvData = $aLoadSpec['csvdata'];

		echo "<div style=\"padding: 10;\">\n";
		if (is_null($iTestId))
		{
			echo "<h3 style=\"background-color: #ddddff; padding: 10;\">{$aLoadSpec['desc']}</h3>\n";
		}
		else
		{
			echo "<h3 style=\"background-color: #ddddff; padding: 10;\"><a href=\"?todo=exec&testid=TestImportREST&subtests=$iTestId\">$iTestId</a> - {$aLoadSpec['desc']}</h3>\n";
		}

		$aPostData = array('csvdata' => $sCsvData);

		$aGetParams = array();
		$aGetParamReport = array();
		foreach($aLoadSpec['args'] as $sArg => $sValue)
		{
			$aGetParams[] = $sArg.'='.urlencode($sValue);
			$aGetParamReport[] = $sArg.'='.$sValue;
		}
		$sGetParams = implode('&', $aGetParams);
		$sLogin = isset($aLoadSpec['login']) ? $aLoadSpec['login'] : 'admin';
		$sPassword = isset($aLoadSpec['password']) ? $aLoadSpec['password'] : 'admin';

		$sRes = self::DoPostRequestAuth('../webservices/import.php?'.$sGetParams, $aPostData, $sLogin, $sPassword);

		$sArguments = implode('<br/>', $aGetParamReport);

		if (strlen($sCsvData) > 5000)
		{
			$sCsvDataViewable = 'INPUT TOO LONG TO BE DISPLAYED ('.strlen($sCsvData).")\n".substr($sCsvData, 0, 500)."\n... TO BE CONTINUED";
		}
		else
		{
			$sCsvDataViewable = $sCsvData;
		}

		echo "<div style=\"\">\n";
		echo "   <div style=\"float:left; width:20%; padding:5; background-color:#eeeeff;\">\n";
		echo "      $sArguments\n";
		echo "   </div>\n";
		echo "   <div style=\"float:right; width:75%; padding:5; background-color:#eeeeff\">\n";
		echo "      <pre class=\"vardump\">$sCsvDataViewable</pre>\n";
		echo "   </div>\n";
		echo "</div>\n";

		echo "<pre class=\"vardump\" style=\"clear: both; padding: 15; background-color: black; color: green;\">$sRes</pre>\n";

		echo "</div>\n";
	}
	
	protected function DoExecute()
	{

		$aLoads = array(
			array(
				'desc' => 'Missing class',
				'login' => 'admin',
				'password' => 'admin',
				'args' => array(
				),
				'csvdata' => "xxx",
			),
			array(
				'desc' => 'Wrong class',
				'login' => 'admin',
				'password' => 'admin',
				'args' => array(
					'class' => 'toto',
				),
				'csvdata' => "xxx",
			),
			array(
				'desc' => 'Wrong output type',
				'login' => 'admin',
				'password' => 'admin',
				'args' => array(
					'class' => 'NetworkDevice',
					'output' => 'onthefly',
				),
				'csvdata' => "xxx",
			),
			array(
				'desc' => 'Weird format, working anyhow...',
				'login' => 'admin',
				'password' => 'admin',
				'args' => array(
					'class' => 'Server',
					'output' => 'details',
					'separator' => '*',
					'qualifier' => '@',
					'reconciliationkeys' => 'org_id,name',
				),
				'csvdata' => 'name*org_id
									server01*2
									  @server02@@combodo@*   2
									server45*99',
			),
			array(
				'desc' => 'Load an organization',
				'login' => 'admin',
				'password' => 'admin',
				'args' => array(
					'class' => 'Organization',
					'output' => 'details',
					'separator' => ';',
					'reconciliationkeys' => '',
				),
				'csvdata' => "name;code\nWorldCompany;WCY",
			),
			array(
				'desc' => 'Load a location',
				'login' => 'admin',
				'password' => 'admin',
				'args' => array(
					'class' => 'Location',
					'output' => 'details',
					'separator' => ';',
					'reconciliationkeys' => '',
				),
				'csvdata' => "name;org_id;address\nParis;1;Centre de la Franca",
			),
			array(
				'desc' => 'Load a person',
				'login' => 'admin',
				'password' => 'admin',
				'args' => array(
					'class' => 'Person',
					'output' => 'details',
					'separator' => ';',
					'reconciliationkeys' => '',
				),
				'csvdata' => "email;name;first_name;org_id;phone\njohn.foo@starac.com;Foo;John;1;+33(1)23456789",
			),
			array(
				'desc' => 'Load a person - wrong email format',
				'login' => 'admin',
				'password' => 'admin',
				'args' => array(
					'class' => 'Person',
					'output' => 'details',
					'separator' => ';',
					'reconciliationkeys' => '',
				),
				'csvdata' => "email;name;first_name;org_id\nemailPASbon;Foo;John;1",
			),
			array(
				'desc' => 'Load a team',
				'login' => 'admin',
				'password' => 'admin',
				'args' => array(
					'class' => 'Team',
					'output' => 'details',
					'separator' => ';',
					'reconciliationkeys' => '',
				),
				'csvdata' => "name;org_id;location_name\nSquadra Azzura2;1;Paris",
			),
			array(
				'desc' => 'Load server',
				'login' => 'admin',
				'password' => 'admin',
				'args' => array(
					'class' => 'Server',
					'output' => 'details',
					'separator' => ';',
					'reconciliationkeys' => '',
				),
				'csvdata' => "name;status;owner_name;location_name;location_id->org_name;os_family;os_version;management_ip;cpu;ram;brand;model;serial_number\nlocalhost.;production;Demo;Grenoble;Demo;Ubuntu 9.10;2.6.31-19-generic-#56-Ubuntu SMP Thu Jan 28 01:26:53 UTC 2010;16.16.230.232;Intel(R) Core(TM)2 Duo CPU     T7100  @ 1.80GHz;2005;Hewlett-Packard;HP Compaq 6510b (GM108UC#ABF);CNU7370BNP",
			),
			array(
				'desc' => 'Load server (column header localized in english)',
				'login' => 'admin',
				'password' => 'admin',
				'args' => array(
					'class' => 'Server',
					'output' => 'details',
					'separator' => ';',
					'reconciliationkeys' => '',
				),
				'csvdata' => "Name;Status;Owner Organization;Location;location_id->org_name;OS Family;OS Version;Management IP;CPU;RAM;Brand;Model;Serial  Number\nlocalhost.;production;Demo;Grenoble;Demo;Ubuntu 9.10;2.6.31-19-generic-#56-Ubuntu SMP Thu Jan 28 01:26:53 UTC 2010;16.16.230.232;Intel(R) Core(TM)2 Duo CPU     T7100  @ 1.80GHz;2005;Hewlett-Packard;HP Compaq 6510b (GM108UC#ABF);CNU7370BNP",
			),
			array(
				'desc' => 'Load server (directly from Export results)',
				'login' => 'admin',
				'password' => 'admin',
				'args' => array(
					'class' => 'Server',
					'output' => 'details',
					'reconciliationkeys' => '',
				),
				'csvdata' => 'id,Name,Status,Owner organization,Owner organization->Name,Business criticity,Brand,Model,Serial  Number,Asset Reference,Description,Location,Location->Name,Location details,Management IP,Default Gateway,CPU,RAM,Hard Disk,OS Family,OS Version
1,"dbserver1.demo.com","production",2,"Demo","medium","HP","DL380","","","ouille
[[Server:webserver.demo.com]]",1,"Grenoble","","10.1.1.10","255.255.255.0","2","16Gb","120Gb","Linux","Debian (Lenny)"',
			),
			array(
				'desc' => 'Load server - wrong value for status',
				'login' => 'admin',
				'password' => 'admin',
				'args' => array(
					'class' => 'Server',
					'output' => 'details',
					'separator' => ';',
					'reconciliationkeys' => '',
				),
				'csvdata' => "name;status;owner_name;location_name;location_id->org_name;os_family;os_version;management_ip;cpu;ram;brand;model;serial_number\nlocalhost.;Production;Demo;Grenoble;Demo;Ubuntu 9.10;2.6.31-19-generic-#56-Ubuntu SMP Thu Jan 28 01:26:53 UTC 2010;16.16.230.232;Intel(R) Core(TM)2 Duo CPU     T7100  @ 1.80GHz;2005;Hewlett-Packard;HP Compaq 6510b (GM108UC#ABF);CNU7370BNP",
			),
			array(
				'desc' => 'Load NW if',
				'login' => 'admin',
				'password' => 'admin',
				'args' => array(
					'class' => 'NetworkInterface',
					'output' => 'details',
					'separator' => ';',
					'reconciliationkeys' => '',
				),
				'csvdata' => "name;status;org_id;device_name;physical_type;ip_address;ip_mask;mac_address;speed\neth0;implementation;2;localhost.;ethernet;16.16.230.232;255.255.240.0;00:1a:4b:68:e3:97;\nlo;implementation;2;localhost.;ethernet;127.0.0.1;255.0.0.0;;",
			),
			// Data Bruno
			array(
				'desc' => 'Load NW devices from real life',
				'login' => 'admin',
				'password' => 'admin',
				'args' => array(
					'class' => 'NetworkDevice',
					'output' => 'details',
					'separator' => ';',
					'reconciliationkeys' => 'org_id,Name',
					),
				'csvdata' => 'name;management_ip;importance;Owner organization->Name;type
									truc-machin-bidule;172.15.255.150;high;My Company/Department;switch
									10.15.255.222;10.15.255.222;high;My Company/Department;switch',
			),
			array(
				'desc' => 'Load NW ifs',
				'login' => 'admin',
				'password' => 'admin',
				'args' => array(
					'class' => 'NetworkInterface',
					'output' => 'details',
					'separator' => ';',
					'reconciliationkeys' => 'device_id->name,name',
				),
				'csvdata' => 'device_id->name;org_id->name;name;ip_address;ip_mask;speed;link_type;mac_address;physical_type
					truc-machin-bidule;My Company/Department;"GigabitEthernet44";;;0;downlink;00 12 F2 CB C4 EB ;ethernet
					truc-machin-bidule;My Company/Department;"GigabitEthernet38";;;0;downlink;00 12 F2 CB C4 E5 ;ethernet
					un-autre;My Company/Department;"GigabitEthernet2/3";;;1000000000;uplink;00 12 F2 20 0F 1A ;ethernet',
			),
			array(
				'desc' => 'The simplest data load',
				'login' => 'admin',
				'password' => 'admin',
				'args' => array(
					'class' => 'Location',
					'output' => 'details',
				),
				'csvdata' => "name\nParis",
			),
			array(
				'desc' => 'The simplest data load + org',
				'login' => 'admin',
				'password' => 'admin',
				'args' => array(
					'class' => 'Location',
					'output' => 'details',
					'separator' => ';',
				),
				'csvdata' => "name;org_id\nParis;2",
			),
			array(
				'desc' => 'The simplest data load + org (name)',
				'login' => 'admin',
				'password' => 'admin',
				'args' => array(
					'class' => 'Location',
					'output' => 'details',
					'separator' => ';',
				),
				'csvdata' => "name;org_name\nParis;Demo",
			),
			array(
				'desc' => 'The simplest data load + org (code)',
				'login' => 'admin',
				'password' => 'admin',
				'args' => array(
					'class' => 'Location',
					'output' => 'details',
					'separator' => ';',
				),
				'csvdata' => "name;org_id->code\nParis;DEMO",
			),
			array(
				'desc' => 'Ouput: summary',
				'login' => 'admin',
				'password' => 'admin',
				'args' => array(
					'class' => 'Location',
					'output' => 'summary',
					'separator' => ';',
				),
				'csvdata' => "name;org_id->code\nParis;DEMO",
			),
			array(
				'desc' => 'Ouput: retcode',
				'login' => 'admin',
				'password' => 'admin',
				'args' => array(
					'class' => 'Location',
					'output' => 'retcode',
					'separator' => ';',
				),
				'csvdata' => "name;org_id->code\nParis;DEMO",
			),
			array(
				'desc' => 'Error in reconciliation list',
				'login' => 'admin',
				'password' => 'admin',
				'args' => array(
					'class' => 'Location',
					'output' => 'details',
					'separator' => ';',
					'reconciliationkeys' => 'org_id',
				),
				'csvdata' => "org_name;name\nDemo;Paris",
			),
			array(
				'desc' => 'Error in attribute list that does not allow to compute reconciliation scheme',
				'login' => 'admin',
				'password' => 'admin',
				'args' => array(
					'class' => 'Location',
					'output' => 'details',
					'separator' => ';',
				),
				'csvdata' => "org_name;country\nDemo;France",
			),
			array(
				'desc' => 'Error in attribute list - case A',
				'login' => 'admin',
				'password' => 'admin',
				'args' => array(
					'class' => 'Location',
					'output' => 'details',
					'separator' => ';',
				),
				'csvdata' => "name;org\nParis;2",
			),
			array(
				'desc' => 'Error in attribute list - case B1 (key->attcode)',
				'login' => 'admin',
				'password' => 'admin',
				'args' => array(
					'class' => 'Location',
					'output' => 'details',
					'separator' => ';',
				),
				'csvdata' => "name;org->code\nParis;DEMO",
			),
			array(
				'desc' => 'Error in attribute list - case B2 (key->attcode)',
				'login' => 'admin',
				'password' => 'admin',
				'args' => array(
					'class' => 'Location',
					'output' => 'details',
					'separator' => ';',
				),
				'csvdata' => "name;org_id->duns\nParis;DEMO",
			),
			array(
				'desc' => 'Always changing... special comment in change tracking',
				'login' => 'admin',
				'password' => 'admin',
				'args' => array(
					'class' => 'Location',
					'output' => 'details',
					'separator' => ';',
					'comment' => 'automated testing'
				),
				'csvdata' => "org_name;name;address\nDemo;Le pantheon;Addresse bidon:".((string)microtime(true)),
			),
			array(
				'desc' => 'Always changing... but "simulate"',
				'login' => 'admin',
				'password' => 'admin',
				'args' => array(
					'class' => 'Location',
					'output' => 'details',
					'separator' => ';',
					'simulate' => '1',
					'comment' => 'SHOULD NEVER APPEAR IN THE HISTORY'
				),
				'csvdata' => "org_name;name;address\nDemo;Le pantheon;restore address?",
			),
			array(
				'desc' => 'Load a user account',
				'login' => 'admin',
				'password' => 'admin',
				'args' => array(
					'class' => 'UserLocal',
					'output' => 'details',
					'separator' => ',',
					'simulate' => '0',
					'comment' => 'automated testing'
				),
				'csvdata' => "login,password,profile_list\nby_import_csv,fakepwd,profileid->name:Configuration Manager|profileid:10;reason:direct id",
			),
		); 

     	$sSubTests = utils::ReadParam('subtests', null, true, 'raw_data');
     	if (is_null($sSubTests))
     	{
			foreach ($aLoads as $iTestId => $aLoadSpec)
			{
				$this->DoExecSingleLoad($aLoadSpec, $iTestId);
			}
		}
		else
		{
			$aSubTests = explode(',', $sSubTests);
			foreach ($aSubTests as $iTestId)
			{
				$this->DoExecSingleLoad($aLoads[$iTestId], $iTestId);
			}
		}
	}
}

///////////////////////////////////////////////////////////////////////////
// Test massive data load
///////////////////////////////////////////////////////////////////////////
define('IMPORT_COUNT', 4000);

class TestImportRESTMassive extends TestImportREST
{
	static public function GetName()
	{
		return 'CSV import (REST) - HUGE data set ('.IMPORT_COUNT.' PCs)';
	}

	static public function GetDescription()
	{
		return 'Stress import.php';
	}

	protected function DoExecute()
	{
		$aLoadSpec = array(
			'desc' => 'Loading PCs: '.IMPORT_COUNT,
			'args' => array(
				'class' => 'PC',
				'output' => 'summary',
			),
			'csvdata' => "name;org_id;brand\n",
		);
		for($i = 0 ; $i <= IMPORT_COUNT ; $i++)
		{
			$aLoadSpec['csvdata'] .= "pc.import.$i;2;Combodo\n";
		}
		$this->DoExecSingleLoad($aLoadSpec);
	}
}

///////////////////////////////////////////////////////////////////////////
// Test SOAP services
///////////////////////////////////////////////////////////////////////////

$aCreateTicketSpecs = array(
	array(
		'service_category' => 'BasicServices',
		'verb' => 'GetVersion',
//		'expected result' => '1.0.1',
		'expected result' => '$ITOP_VERSION$ [dev]',
		'explain result' => 'no comment!',
		'args' => array(),
	),
	array(
		'service_category' => '',
		'verb' => 'CreateIncidentTicket',
		'expected result' => true,
		'explain result' => 'link attribute unknown + a CI not found',
		'args' => array(
			'admin', /* sLogin */
			'admin', /* sPassword */
			'desc of ticket', /* sDescription */
			'initial situation blah blah blah', /* sInitialSituation */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('id', 1))), /* aCallerDesc */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('name', 'Demo'))), /* aCustomerDesc */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('name', 'Telecom and connectivity'))), /* aServiceDesc */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('name', 'Network Troubleshooting'))), /* aServiceSubcategoryDesc */
			'sub product of the service', /* sProduct */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('name', 'Hardware support'))), /* aWorkgroupDesc */
			array(
				new SOAPLinkCreationSpec(
					'InfrastructureCI',
					array(new SOAPSearchCondition('name', 'dbserver1.demo.com')),
					array(new SOAPAttributeValue('impacting', 'very critical'))
				),
				new SOAPLinkCreationSpec(
					'NetworkDevice',
					array(new SOAPSearchCondition('name', 'switch01')),
					array(new SOAPAttributeValue('impact', 'who cares'))
				),
				new SOAPLinkCreationSpec(
					'Server',
					array(new SOAPSearchCondition('name', 'thisone')),
					array(new SOAPAttributeValue('impact', 'our lives'))
				),
			), /* aImpact */
			'1', /* sImpact */
			'1', /* sUrgency */
		),
	),
	array(
		'service_category' => '',
		'verb' => 'CreateIncidentTicket',
		'expected result' => true,
		'explain result' => 'caller not specified',
		'args' => array(
			'admin', /* sLogin */
			'admin', /* sPassword */
			'PC burning', /* sDescription */
			'The power supply suddenly started to warm up', /* sInitialSituation */
			new SOAPExternalKeySearch(), /* aCallerDesc */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('name', 'Demo'))), /* aCustomerDesc */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('name', 'Computers and peripherals'))), /* aServiceDesc */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('id', 1))), /* aServiceSubcategoryDesc */
			'sub product of the service', /* sProduct */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('name', 'Hardware support'))), /* aWorkgroupDesc */
			array(
				new SOAPLinkCreationSpec(
					'InfrastructureCI',
					array(new SOAPSearchCondition('name', 'dbserver1.demo.com')),
					array()
				), /* aImpact */
			),
			'1', /* sImpact */
			'1', /* sUrgency */
		),
	),
	array(
		'service_category' => '',
		'verb' => 'CreateIncidentTicket',
		'expected result' => false,
		'explain result' => 'wrong class on CI to attach',
		'args' => array(
			'admin', /* sLogin */
			'admin', /* sPassword */
			'PC burning', /* sDescription */
			'The power supply suddenly started to warm up', /* sInitialSituation */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('id', 1))), /* aCallerDesc */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('name', 'Demo'))), /* aCustomerDesc */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('name', 'Computers and peripherals'))), /* aServiceDesc */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('id', 1))), /* aServiceSubcategoryDesc */
			'sub product of the service', /* sProduct */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('name', 'Hardware support'))), /* aWorkgroupDesc */
			array(
				new SOAPLinkCreationSpec(
					'logInfra',
					array(new SOAPSearchCondition('dummyfiltercode', 2)),
					array(new SOAPAttributeValue('impact', 'very critical'))
				),
			), /* aImpact */
			'1', /* sImpact */
			'1', /* sUrgency */
		),
	),
	array(
		'service_category' => '',
		'verb' => 'CreateIncidentTicket',
		'expected result' => false,
		'explain result' => 'wrong search condition on CI to attach',
		'args' => array(
			'admin', /* sLogin */
			'admin', /* sPassword */
			'PC burning', /* sDescription */
			'The power supply suddenly started to warm up', /* sInitialSituation */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('id', 1))), /* aCallerDesc */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('name', 'Demo'))), /* aCustomerDesc */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('name', 'Computers and peripherals'))), /* aServiceDesc */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('id', 1))), /* aServiceSubcategoryDesc */
			'sub product of the service', /* sProduct */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('name', 'Hardware support'))), /* aWorkgroupDesc */
			array(
				new SOAPLinkCreationSpec(
					'InfrastructureCI',
					array(new SOAPSearchCondition('dummyfiltercode', 2)),
					array(new SOAPAttributeValue('impact', 'very critical'))
				),
			), /* aImpact */
			'1', /* sImpact */
			'1', /* sUrgency */
		),
	),
	array(
		'service_category' => '',
		'verb' => 'CreateIncidentTicket',
		'expected result' => true,
		'explain result' => 'no CI to attach (empty array)',
		'args' => array(
			'admin', /* sLogin */
			'admin', /* sPassword */
			'Houston not reachable', /* sDescription */
			'Tried to join the shuttle', /* sInitialSituation */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('id', 1))), /* aCallerDesc */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('name', 'Demo'))), /* aCustomerDesc */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('name', 'Computers and peripherals'))), /* aServiceDesc */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('id', 1))), /* aServiceSubcategoryDesc */
			'sub product of the service', /* sProduct */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('name', 'Hardware support'))), /* aWorkgroupDesc */
			array(
			), /* aImpact */
			'1', /* sImpact */
			'1', /* sUrgency */
		),
	),
	array(
		'service_category' => '',
		'verb' => 'CreateIncidentTicket',
		'expected result' => true,
		'explain result' => 'no CI to attach (null)',
		'args' => array(
			'admin', /* sLogin */
			'admin', /* sPassword */
			'Houston not reachable', /* sDescription */
			'Tried to join the shuttle', /* sInitialSituation */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('id', 1))), /* aCallerDesc */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('name', 'Demo'))), /* aCustomerDesc */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('name', 'Computers and peripherals'))), /* aServiceDesc */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('id', 1))), /* aServiceSubcategoryDesc */
			'sub product of the service', /* sProduct */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('name', 'Hardware support'))), /* aWorkgroupDesc */
			null,  /* aImpact */
			'1', /* sImpact */
			'1', /* sUrgency */
		),
	),
	array(
		'service_category' => '',
		'verb' => 'CreateIncidentTicket',
		'expected result' => true,
		'explain result' => 'caller unknown',
		'args' => array(
			'admin', /* sLogin */
			'admin', /* sPassword */
			'Houston not reachable', /* sDescription */
			'Tried to join the shuttle', /* sInitialSituation */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('id', 1000))), /* aCallerDesc */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('name', 'Demo'))), /* aCustomerDesc */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('name', 'Computers and peripherals'))), /* aServiceDesc */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('id', 1))), /* aServiceSubcategoryDesc */
			'sub product of the service', /* sProduct */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('name', 'Hardware support'))), /* aWorkgroupDesc */
			array(
			), /* aImpact */
			'1', /* sImpact */
			'1', /* sUrgency */
		),
	),
	array(
		'service_category' => '',
		'verb' => 'CreateIncidentTicket',
		'expected result' => false,
		'explain result' => 'wrong values for impact and urgency',
		'args' => array(
			'admin', /* sLogin */
			'admin', /* sPassword */
			'Houston not reachable', /* sDescription */
			'Tried to join the shuttle', /* sInitialSituation */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('id', 1))), /* aCallerDesc */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('name', 'Demo'))), /* aCustomerDesc */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('name', 'Computers and peripherals'))), /* aServiceDesc */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('id', 1))), /* aServiceSubcategoryDesc */
			'sub product of the service', /* sProduct */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('name', 'Hardware support'))), /* aWorkgroupDesc */
			array(
			), /* aImpact */
			'6', /* sImpact */
			'7', /* sUrgency */
		),
	),
	array(
		'service_category' => '',
		'verb' => 'CreateIncidentTicket',
		'expected result' => false,
		'explain result' => 'wrong password',
		'args' => array(
			'admin', /* sLogin */
			'xxxxx', /* sPassword */
			'Houston not reachable', /* sDescription */
			'Tried to join the shuttle', /* sInitialSituation */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('id', 1))), /* aCallerDesc */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('name', 'Demo'))), /* aCustomerDesc */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('name', 'Computers and peripherals'))), /* aServiceDesc */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('id', 1))), /* aServiceSubcategoryDesc */
			'sub product of the service', /* sProduct */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('name', 'Hardware support'))), /* aWorkgroupDesc */
			array(
			), /* aImpact */
			'1', /* sImpact */
			'1', /* sUrgency */
		),
	),
	array(
		'service_category' => '',
		'verb' => 'CreateIncidentTicket',
		'expected result' => false,
		'explain result' => 'wrong login',
		'args' => array(
			'xxxxx', /* sLogin */
			'yyyyy', /* sPassword */
			'Houston not reachable', /* sDescription */
			'Tried to join the shuttle', /* sInitialSituation */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('id', 1))), /* aCallerDesc */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('name', 'Demo'))), /* aCustomerDesc */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('name', 'Computers and peripherals'))), /* aServiceDesc */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('id', 1))), /* aServiceSubcategoryDesc */
			'sub product of the service', /* sProduct */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('name', 'Hardware support'))), /* aWorkgroupDesc */
			array(
			), /* aImpact */
			'1', /* sImpact */
			'1', /* sUrgency */
		),
	),
	array(
		'service_category' => '',
		'verb' => 'SearchObjects',
		'expected result' => true,
		'explain result' => '',
		'args' => array(
			'admin', /* sLogin */
			'admin', /* sPassword */
			'SELECT Incident WHERE id > 20', /* sOQL */
		),
	),
	array(
		'service_category' => '',
		'verb' => 'SearchObjects',
		'expected result' => false,
		'explain result' => 'wrong OQL',
		'args' => array(
			'admin', /* sLogin */
			'admin', /* sPassword */
			'SELECT ThisClassDoesNotExist', /* sOQL */
		),
	),
);


$aManageCloudUsersSpecs = array(
	array(
		'service_category' => '',
		'verb' => 'SearchObjects',
		'expected result' => false,
		'explain result' => 'wrong OQL',
		'args' => array(
			'admin', /* sLogin */
			'admin', /* sPassword */
			'SELECT ThisClassDoesNotExist', /* sOQL */
		),
	),
	array(
		'service_category' => '',
		'verb' => 'SearchObjects',
		'expected result' => true,
		'explain result' => 'ok',
		'args' => array(
			'admin', /* sLogin */
			'admin', /* sPassword */
			'SELECT Organization', /* sOQL */
		),
	),
	array(
		'service_category' => 'CloudUsersManagementService',
		'verb' => 'CreateAccount',
		'expected result' => true,
		'explain result' => 'ok',
		'args' => array(
			'admin', /* sAdminLogin */
			'admin', /* sAdminPassword */
			'http://myserver.mydomain.fr:8080', /* sCloudMgrUrl */
			'andros@combodo.com', /* sLogin */
			'André', /* sFirstName */
			'Dupont', /* sLastName */
			1, /* iOrgId */
			'FR FR', /* sLanguage */
			array(
				array(
					new SOAPKeyValue('profile_id', '2'),
					new SOAPKeyValue('reason', 'whynot'),
				),
				array(
					new SOAPKeyValue('profile_id', '3'),
					new SOAPKeyValue('reason', 'because'),
				),
			), /* aProfiles (array of key/value pairs) */
			array(
			), /* aAllowedOrgs (array of key/value pairs) */
			'comment on the creation operation', /* sComment */
		),
	),
	array(
		'service_category' => 'CloudUsersManagementService',
		'verb' => 'ModifyAccount',
		'expected result' => true,
		'explain result' => 'ok',
		'args' => array(
			'admin', /* sAdminLogin */
			'admin', /* sAdminPassword */
			'andros@combodo.com', /* sLogin */
			'nono', /* sFirstName */
			'robot', /* sLastName */
			2, /* iOrgId */
			'EN US', /* sLanguage */
			array(
				array(
					new SOAPKeyValue('profile_id', '3'),
					new SOAPKeyValue('reason', 'because'),
				),
			), /* aProfiles (array of key/value pairs) */
			array(
			), /* aAllowedOrgs (array of key/value pairs) */
			'comment on the modify operation', /* sComment */
		),
	),
	array(
		'service_category' => 'CloudUsersManagementService',
		'verb' => 'DeleteAccount',
		'expected result' => true,
		'explain result' => '',
		'args' => array(
			'admin', /* sAdminLogin */
			'admin', /* sAdminPassword */
			'andros@combodo.com', /* sLogin */
			'comment on the deletion operation', /* sComment */
		),
	),
	array(
		'service_category' => 'CloudUsersManagementService',
		'verb' => 'DeleteAccount',
		'expected result' => false,
		'explain result' => 'wrong login',
		'args' => array(
			'admin', /* sAdminLogin */
			'admin', /* sAdminPassword */
			'taratatata@sdf.com', /* sLogin */
			'comment on the deletion operation', /* sComment */
		),
	),
);

abstract class TestSoap extends TestSoapWebService
{
	static public function GetName() {return 'Test SOAP';}
	static public function GetDescription() {return 'Do basic stuff to test the SOAP capability';}

	protected $m_aTestSpecs;

	protected function DoExecute()
	{
		echo "<p>Note: You may also want to try the sample SOAP client <a href=\"../webservices/itopsoap.examples.php\">itopsoap.examples.php</a></p>\n";

		$aSOAPMapping = SOAPMapping::GetMapping();

		// this file is generated dynamically with location = here
		$sWsdlUri = 'http'.(utils::IsConnectionSecure() ? 's' : '').'://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].dirname($_SERVER['SCRIPT_NAME']).'/../webservices/itop.wsdl.php';

		ini_set("soap.wsdl_cache_enabled","0");

		foreach ($this->m_aTestSpecs as $iPos => $aWebService)
		{
			echo "<h2>SOAP call #$iPos - {$aWebService['verb']}</h2>\n";
			echo "<p>Using WSDL: $sWsdlUriForService</p>\n";
			echo "<p>{$aWebService['explain result']}</p>\n";

			$sWsdlUriForService = $sWsdlUri.'?service_category='.$aWebService['service_category'];
			$this->m_SoapClient = new SoapClient
			(
				$sWsdlUriForService,
				array(
					'classmap' => $aSOAPMapping,
					'trace' => 1,
				)
			);
	
			if (false)
			{
				self::DumpVariable($this->m_SoapClient->__getTypes());
			} 

			try
			{
				$oRes = call_user_func_array(array($this->m_SoapClient, $aWebService['verb']), $aWebService['args']);
			}
			catch(SoapFault $e)
			{
				print "<pre>\n"; 
				print "Request: \n".htmlspecialchars($this->m_SoapClient->__getLastRequest()) ."\n"; 
				print "Response: \n".htmlspecialchars($this->m_SoapClient->__getLastResponse())."\n"; 
				print "</pre>";
				print "Response in HTML: <p>".$this->m_SoapClient->__getLastResponse()."</p>"; 
				throw $e;
			}

			self::DumpVariable($oRes);
	
			print "<pre>\n"; 
			print "Request: \n".htmlspecialchars($this->m_SoapClient->__getLastRequest()) ."\n"; 
			print "Response: \n".htmlspecialchars($this->m_SoapClient->__getLastResponse())."\n"; 
			print "</pre>";

			if ($oRes instanceof SOAPResult)
			{
				$res = $oRes->status;
			}
			elseif ($oRes instanceof SOAPSimpleResult)
			{
				$res = $oRes->status;
			}
			else
			{
				$res = $oRes;
			}
			if ($res != $aWebService['expected result'])
			{
				echo "Expecting:<br/>\n";
				var_dump($aWebService['expected result']);
				echo "Obtained:<br/>\n";
				var_dump($res);
				throw new UnitTestException("Expecting result '{$aWebService['expected result']}', but got '$res'");
			}
		} 
	}
}

abstract class TestSoapDirect extends TestBizModel
{
	static public function GetName() {return 'Test web services locally';}
	static public function GetDescription() {return 'Invoke the service directly (troubleshooting)';}

	protected $m_aTestSpecs;

	protected function DoExecute()
	{
		foreach ($this->m_aTestSpecs as $iPos => $aWebService)
		{
			$sServiceClass = $aWebService['service_category'];
			if (empty($sServiceClass)) $sServiceClass = 'BasicServices';
			$oWebServices = new $sServiceClass();

			echo "<h2>SOAP call #$iPos - {$aWebService['verb']}</h2>\n";
			echo "<p>{$aWebService['explain result']}</p>\n";
			$oRes = call_user_func_array(array($oWebServices, $aWebService['verb']), $aWebService['args']);
			self::DumpVariable($oRes);

			if ($oRes instanceof SOAPResult)
			{
				$res = $oRes->status;
			}
			elseif ($oRes instanceof SOAPSimpleResult)
			{
				$res = $oRes->status;
			}
			else
			{
				$res = $oRes;
			}
			if ($res != $aWebService['expected result'])
			{
				echo "Expecting:<br/>\n";
				var_dump($aWebService['expected result']);
				echo "Obtained:<br/>\n";
				var_dump($res);
				throw new UnitTestException("Expecting result '{$aWebService['expected result']}', but got '$res'");
			}
		}
		return true;
	}
}

class TestSoap_Tickets extends TestSoap
{
	static public function GetName() {return 'Test SOAP - create ticket';}

	protected function DoExecute()
	{
		global $aCreateTicketSpecs;
		$this->m_aTestSpecs = $aCreateTicketSpecs;
		return parent::DoExecute();
	}
}

class TestSoapDirect_Tickets extends TestSoapDirect
{
	static public function GetName() {return 'Test SOAP without SOAP - create ticket';}

	protected function DoExecute()
	{
		global $aCreateTicketSpecs;
		$this->m_aTestSpecs = $aCreateTicketSpecs;
		return parent::DoExecute();
	}
}


class TestSoap_ManageCloudUsers extends TestSoap
{
	static public function GetName() {return 'Test SOAP - manage Cloud Users';}

	protected function DoExecute()
	{
		global $aManageCloudUsersSpecs;
		$this->m_aTestSpecs = $aManageCloudUsersSpecs;
		return parent::DoExecute();
	}
}

class TestSoapDirect_ManageCloudUsers extends TestSoapDirect
{
	static public function GetName() {return 'Test SOAP without SOAP - manage Cloud Users';}

	protected function DoExecute()
	{
		global $aManageCloudUsersSpecs;
		$this->m_aTestSpecs = $aManageCloudUsersSpecs;
		return parent::DoExecute();
	}
}


////////////////////// End of SOAP TESTS


class TestTriggerAndEmail extends TestBizModel
{
	static public function GetName() {return 'Test trigger and email';}
	static public function GetDescription() {return 'Create a trigger and an email, then activates the trigger';}

	protected function CreateEmailSpec($oTrigger, $sStatus, $sTo, $sCC, $sTesterEmail)
	{
		$oAction = MetaModel::NewObject("ActionEmail");
		$oAction->Set("status", $sStatus);
		$oAction->Set("name", "New server");
		$oAction->Set("test_recipient", $sTesterEmail);
		$oAction->Set("from", $sTesterEmail);
		$oAction->Set("reply_to", $sTesterEmail);
		$oAction->Set("to", $sTo);
		$oAction->Set("cc", $sCC);
		$oAction->Set("bcc", "");
		$oAction->Set("subject", "New server: '\$this->name()$'");
		$oAction->Set("body", "<html><body><p>Dear customer,</p><p>We have created the server \$this->hyperlink()$ in the IT infrastructure database.</p><p>You will be further notified when it is in <strong>Production</strong>.</p><p>The IT infrastructure management team.</p><p>Here are some accentuated characters for french people: 'ééà'</p></body></html>");
		$oAction->Set("importance", "low");
		$iActionId = $this->ObjectToDB($oAction, true);

		$oLink = MetaModel::NewObject("lnkTriggerAction");
		$oLink->Set("trigger_id", $oTrigger->GetKey());
		$oLink->Set("action_id", $iActionId);
		$oLink->Set("order", "1");
		$iLink = $this->ObjectToDB($oLink, true);
	}

	protected function DoExecute()
	{
		$oMyPerson = MetaModel::NewObject("Person");
		$oMyPerson->Set("name", "testemail1");
		$oMyPerson->Set("first_name", "theodore");
		$oMyPerson->Set("org_id", "1");
		$oMyPerson->Set("email", "romain.quetiez@combodo.com");
		$iPersonId = $this->ObjectToDB($oMyPerson, true);

		$oMyPerson = MetaModel::NewObject("Person");
		$oMyPerson->Set("name", "testemail2");
		$oMyPerson->Set("first_name", "theodore");
		$oMyPerson->Set("org_id", "1");
		$oMyPerson->Set("email", "denis.flaven@combodo.com");
		$iPersonId = $this->ObjectToDB($oMyPerson, true);

		$oMyPerson = MetaModel::NewObject("Person");
		$oMyPerson->Set("name", "testemail3");
		$oMyPerson->Set("first_name", "theodore");
		$oMyPerson->Set("org_id", "1");
		$oMyPerson->Set("email", "erwan.taloc@combodo.com");
		$iPersonId = $this->ObjectToDB($oMyPerson, true);

		$oMyServer = MetaModel::NewObject("Server");
		$oMyServer->Set("name", "wfr.terminator.com");
		$oMyServer->Set("status", "production");
		$oMyServer->Set("org_id", 2);
		$iServerId = $this->ObjectToDB($oMyServer, true);

		$oMyTrigger = MetaModel::NewObject("TriggerOnStateEnter");
		$oMyTrigger->Set("description", "Testor");
		$oMyTrigger->Set("target_class", "Server");
		$oMyTrigger->Set("state", "Shipped");
		$iTriggerId = $this->ObjectToDB($oMyTrigger, true);

		// Error in OQL field(s)
		//
		$this->CreateEmailSpec
		(
			$oMyTrigger,
			'test',
			"SELECT Person WHERE naime = 'Dali'",
			"SELECT Server",
			'romain.quetiez@combodo.com'
		);

		// Error: no recipient
		//
		$this->CreateEmailSpec
		(
			$oMyTrigger,
			'test',
			"",
			"",
			'romain.quetiez@combodo.com'
		);

		// Test
		//
		$this->CreateEmailSpec
		(
			$oMyTrigger,
			'test',
			"SELECT Person WHERE name LIKE 'testemail%'",
			"SELECT Person",
			'romain.quetiez@combodo.com'
		);

		// Test failing because of a wrong test recipient address
		//
		$this->CreateEmailSpec
		(
			$oMyTrigger,
			'test',
			"SELECT Person WHERE name LIKE 'testemail%'",
			"",
			'toto@walibi.bg'
		);

		// Normal behavior
		//
		$this->CreateEmailSpec
		(
			$oMyTrigger,
			'enabled',
			"SELECT Person WHERE name LIKE 'testemail%'",
			"",
			'romain.quetiez@combodo.com'
		);

		// Does nothing, because it is disabled
		//
		$this->CreateEmailSpec
		(
			$oMyTrigger,
			'disabled',
			"SELECT Person WHERE name = 'testemail%'",
			"",
			'romain.quetiez@combodo.com'
		);

		$oMyTrigger->DoActivate($oMyServer->ToArgs('this'));

		return true;
	}
}

class TestDBProperties extends TestBizModel
{
	static public function GetName()
	{
		return 'Itop - DB Properties';
	}

	static public function GetDescription()
	{
		return 'Write and read a dummy property';
	}

	protected function DoExecute()
	{
		$sName = 'test';
		DBProperty::SetProperty($sName, 'unix time:'.time(), 'means nothing', 'done with the automated test utility');
		$sValue = DBProperty::GetProperty($sName, 'defaults to this because the table has not been created (1.0.1 install?)');
		echo "<p>Write... then read property <b>$sName</b>, found: '$sValue'</p>\n";
	}
}

class TestCreateObjects extends TestBizModel
{
	static public function GetName()
	{
		return 'Itop - create objects';
	}

	static public function GetDescription()
	{
		return 'Create weird objects (reproduce a bug?)';
	}

	protected function DoExecute()
	{
		$oMyObj = MetaModel::NewObject("Server");
		$oMyObj->Set("name", "test".rand(1,1000));
		$oMyObj->Set("org_id", 2);
		$oMyObj->Set("status", 'production');
		$this->ObjectToDB($oMyObj, $bReload = true);
		echo "<p>Created: {$oMyObj->GetHyperLink()}</p>";

		$sTicketRef = "I-abcdef";
		echo "<p>Creating: $sTicketRef</p>";
		$oMyObj = MetaModel::NewObject("Incident");
		$oMyObj->Set("ref", $sTicketRef);
		$oMyObj->Set("title", "my title");
		$oMyObj->Set("description", "my description");
		$oMyObj->Set("ticket_log", "my ticket log");
		$oMyObj->Set("start_date", "2010-03-08 17:37:00");
		$oMyObj->Set("status", "resolved");
		$oMyObj->Set("caller_id", 1);
		$oMyObj->Set("org_id", 1);
		$oMyObj->Set("urgency", 3);
		$oMyObj->Set("agent_id", 1);
		$oMyObj->Set("close_date", "0000-00-00 00:00:00");
		$oMyObj->Set("last_update", "2010-04-08 16:47:29");
		$oMyObj->Set("solution", "branche ton pc!");
		// External key given as a string -> should be casted to an integer
		$oMyObj->Set("service_id", "1");
		$oMyObj->Set("servicesubcategory_id", "1");
		$oMyObj->Set("product", "");
		$oMyObj->Set("impact", 2);
		$oMyObj->Set("priority", 3);
		$oMyObj->Set("related_problem_id", 0);
		$oMyObj->Set("related_change_id", 0);
		$oMyObj->Set("assignment_date", "");
		$oMyObj->Set("resolution_date", "");
		$oMyObj->Set("tto_escalation_deadline", "");
		$oMyObj->Set("ttr_escalation_deadline", "");
		$oMyObj->Set("closure_deadline", "");
		$oMyObj->Set("resolution_code", "fixed");
		$oMyObj->Set("user_satisfaction", "");
		$oMyObj->Set("user_commment", "");
		$oMyObj->Set("workgroup_id", 4);
		$this->ObjectToDB($oMyObj, $bReload = true);
		echo "<p>Created: {$oMyObj->GetHyperLink()}</p>";
	}
}

class TestSetLinkset extends TestBizModel
{
	static public function GetName()
	{
		return 'Itop - Link set from a string';
	}

	static public function GetDescription()
	{
		return 'Create a user account, setting its profile by the mean of a string (prerequisite to CSV import of linksets)';
	}

	protected function DoExecute()
	{
		$oUser = new UserLocal();
		$oUser->Set('login', 'patator'.time());
		$oUser->Set('password', 'patator');
		//$oUser->Set('contactid', 0);
		//$oUser->Set('language', $sLanguage);

      $sLinkSetSpec = "profileid:10;reason:service manager|profileid->name:Problem Manager;'reason:problem manager;glandeur";

		$oAttDef = MetaModel::GetAttributeDef('UserLocal', 'profile_list');
		$oSet = $oAttDef->MakeValueFromString($sLinkSetSpec, $bLocalizedValue = false);
		$oUser->Set('profile_list', $oSet);

		// Create a change to record the history of the User object
		$this->ObjectToDB($oUser, $bReload = true);
		echo "<p>Created: {$oUser->GetHyperLink()}</p>";
	}
}

class TestEmailAsynchronous extends TestBizModel
{
	static public function GetName()
	{
		return 'Itop - Asynchronous email';
	}

	static public function GetDescription()
	{
		return 'Queues a request to send an email';
	}

	protected function DoExecute()
	{
		for ($i = 0 ; $i < 2 ; $i++)
		{
			$oMail = new Email();
			$oMail->SetRecipientTO('romain.quetiez@combodo.com');
			$oMail->SetRecipientFrom('romain.quetiez@combodo.com');
			$oMail->SetRecipientCC('romainquetiez@yahoo.fr');
			$oMail->SetSubject('automated test - '.$i);
			$oMail->SetBody('this is one is entirely working fine '.time());
			$iRes = $oMail->Send($aIssues, false);
			switch ($iRes)
			{
				case EMAIL_SEND_OK:
					echo "EMAIL_SEND_OK<br/>\n";
					break;

				case EMAIL_SEND_PENDING:
					echo "EMAIL_SEND_PENDING<br/>\n";
					break;

				case EMAIL_SEND_ERROR:
					echo "EMAIL_SEND_ERROR: <br/>\n";
					foreach($aIssues as $sIssue)
					{
						echo "Issue: $sIssue<br/>\n";
					}
					break;
			}
		}
		
	}
}

abstract class TestLinkSet extends TestBizModel
{
	protected function StandardizedDump($oSet, $sAttPrefixToIgnore)
	{
		if (!$oSet->m_bLoaded) $oSet->Load();
		$oSet->Rewind();

		$aRet = array();
		while($oObject = $oSet->Fetch())
		{
			$aValues = array();
			foreach(MetaModel::ListAttributeDefs(get_class($oObject)) as $sAttCode => $oAttDef)
			{
				//if (!$oAttDef->IsPartOfFingerprint()) continue;
				//if ($oAttDef->IsMagic()) continue;
				if ($sAttCode == 'friendlyname') continue;
				if (substr($sAttCode, -strlen('_archive_flag')) == '_archive_flag') continue;
				if (substr($sAttCode, -strlen('_obsolescence_flag')) == '_obsolescence_flag') continue;
				if (substr($sAttCode, 0, strlen($sAttPrefixToIgnore)) == $sAttPrefixToIgnore) continue;
				if ($oAttDef->IsScalar())
				{
					$aValues[] = $oObject->Get($sAttCode);
				}
			}
			$aRet[] = implode(', ', $aValues);
		}
		sort($aRet);
		return $aRet;
	}

}

class TestLinkSetRecording_NN_WithDuplicates extends TestLinkSet
{
	static public function GetName()
	{
		return 'Linkset N-N having duplicated allowed (Connectable CI to Network Device)';
	}

	static public function GetDescription()
	{
		return 'Simulate CSV/data synchro type of recording. Check the values and the history. Lots of issues there: #1145, #1146 and #1147';
	}

	protected function DoExecute()
	{
		CMDBSource::Query('START TRANSACTION');
		//CMDBSource::Query('ROLLBACK'); automatique !
		
		////////////////////////////////////////////////////////////////////////////////
		// Set the stage
		//
		$oServer = MetaModel::NewObject('Server');
		$oServer->Set('name', 'unit test linkset');
		$oServer->Set('org_id', 3);
		$oServer->DBInsert();
		$iServer = $oServer->GetKey();
		
		$oTypes = new DBObjectSet(DBObjectSearch::FromOQL('SELECT NetworkDeviceType WHERE name = "Router"'));
		$oType = $oTypes->fetch();
		
		$oDevice = MetaModel::NewObject('NetworkDevice');
		$oDevice->Set('name', 'test device A');
		$oDevice->Set('org_id', 3);
		$oDevice->Set('networkdevicetype_id', $oType->GetKey());
		$oDevice->DBInsert();
		$iDev1 = $oDevice->GetKey();
		
		$oDevice = MetaModel::NewObject('NetworkDevice');
		$oDevice->Set('name', 'test device B');
		$oDevice->Set('org_id', 3);
		$oDevice->Set('networkdevicetype_id', $oType->GetKey());
		$oDevice->DBInsert();
		$iDev2 = $oDevice->GetKey();
		
		
		////////////////////////////////////////////////////////////////////////////////
		// Scenarii
		//
		$aScenarii = array(
			array(
				'description' => 'Add the first item',
				'links' => array(
					array(
						'networkdevice_id' => $iDev1,
						'connectableci_id' => $iServer,
						'network_port' => '',
						'device_port' => '',
					),
				),
				'expected-res' => array (
			 		"$iDev1, test device A, unit test linkset, , , downlink, test device A",
				),
				'history_added' => 1,
				'history_removed' => 0,
				'history_modified' => 0,
			),
			array(
				'description' => 'Modify the unique item',
				'links' => array(
					array(
						'networkdevice_id' => $iDev1,
						'connectableci_id' => $iServer,
						'network_port' => 'devTagada',
						'device_port' => '',
					),
				),
				'expected-res' => array (
			 		"$iDev1, test device A, unit test linkset, devTagada, , downlink, test device A",
				),
				'history_added' => 1,
				'history_removed' => 1,
				'history_modified' => 0,
			),
			array(
				'description' => 'Modify again the original item and add a second item',
				'links' => array(
					array(
						'networkdevice_id' => $iDev1,
						'connectableci_id' => $iServer,
						'network_port' => '',
						'device_port' => '',
					),
					array(
						'networkdevice_id' => $iDev2,
						'connectableci_id' => $iServer,
						'network_port' => '',
						'device_port' => '',
					),
				),
				'expected-res' => array (
			 		"$iDev1, test device A, unit test linkset, , , downlink, test device A",
			 		"$iDev2, test device B, unit test linkset, , , downlink, test device B",
				),
				'history_added' => 2,
				'history_removed' => 1,
				'history_modified' => 0,
			),
			array(
				'description' => 'No change, the links are added in the reverse order',
				'links' => array(
					array(
						'networkdevice_id' => $iDev2,
						'connectableci_id' => $iServer,
						'network_port' => '',
						'device_port' => '',
					),
					array(
						'networkdevice_id' => $iDev1,
						'connectableci_id' => $iServer,
						'network_port' => '',
						'device_port' => '',
					),
				),
				'expected-res' => array (
			 		"$iDev1, test device A, unit test linkset, , , downlink, test device A",
			 		"$iDev2, test device B, unit test linkset, , , downlink, test device B",
				),
				'history_added' => 0,
				'history_removed' => 0,
				'history_modified' => 0,
			),
			array(
				'description' => 'Change on attribute on both links at the same time',
				'links' => array(
					array(
						'networkdevice_id' => $iDev2,
						'connectableci_id' => $iServer,
						'network_port' => 'PortDev B',
						'device_port' => '',
					),
					array(
						'networkdevice_id' => $iDev1,
						'connectableci_id' => $iServer,
						'network_port' => 'PortDev A',
						'device_port' => '',
					),
				),
				'expected-res' => array (
			 		"$iDev1, test device A, unit test linkset, PortDev A, , downlink, test device A",
			 		"$iDev2, test device B, unit test linkset, PortDev B, , downlink, test device B",
				),
				'history_added' => 2,
				'history_removed' => 2,
				'history_modified' => 0,
			),
			array(
				'description' => 'Removing A',
				'links' => array(
					array(
						'networkdevice_id' => $iDev2,
						'connectableci_id' => $iServer,
						'network_port' => 'PortDev B',
						'device_port' => '',
					),
				),
				'expected-res' => array (
			 		"$iDev2, test device B, unit test linkset, PortDev B, , downlink, test device B",
				),
				'history_added' => 0,
				'history_removed' => 1,
				'history_modified' => 0,
			),
			array(
				'description' => 'Adding B again - with a different port (duplicate!)',
				'links' => array(
					array(
						'networkdevice_id' => $iDev2,
						'connectableci_id' => $iServer,
						'network_port' => 'port_123',
						'device_port' => '',
					),
					array(
						'networkdevice_id' => $iDev2,
						'connectableci_id' => $iServer,
						'network_port' => 'port_456',
						'device_port' => '',
					),
				),
				'expected-res' => array (
			 		"$iDev2, test device B, unit test linkset, port_123, , downlink, test device B",
			 		"$iDev2, test device B, unit test linkset, port_456, , downlink, test device B",
				),
				'history_added' => 2,
				'history_removed' => 1,
				'history_modified' => 0,
			),
			array(
				'description' => 'No change (creating a set with the reloaded links, like in the UI)',
				'links' => array(
					array(
						'id' => "SELECT lnkConnectableCIToNetworkDevice WHERE networkdevice_id = $iDev2 AND connectableci_id = $iServer AND network_port = 'port_123'",
						'networkdevice_id' => $iDev2,
						'connectableci_id' => $iServer,
						'network_port' => 'port_123',
						'device_port' => '',
					),
					array(
						'id' => "SELECT lnkConnectableCIToNetworkDevice WHERE networkdevice_id = $iDev2 AND connectableci_id = $iServer AND network_port = 'port_456'",
						'networkdevice_id' => $iDev2,
						'connectableci_id' => $iServer,
						'network_port' => 'port_456',
						'device_port' => '',
					),
				),
				'expected-res' => array (
					"$iDev2, test device B, unit test linkset, port_123, , downlink, test device B",
					"$iDev2, test device B, unit test linkset, port_456, , downlink, test device B",
				),
				'history_added' => 0,
				'history_removed' => 0,
				'history_modified' => 0,
			),
			array(
				'description' => 'Change an attribute on one link (based on reloaded links, like in the UI)',
				'links' => array(
					array(
						'id' => "SELECT lnkConnectableCIToNetworkDevice WHERE networkdevice_id = $iDev2 AND connectableci_id = $iServer AND network_port = 'port_123'",
						'networkdevice_id' => $iDev2,
						'connectableci_id' => $iServer,
						'network_port' => 'port_123_modified',
						'device_port' => '',
					),
					array(
						'id' => "SELECT lnkConnectableCIToNetworkDevice WHERE networkdevice_id = $iDev2 AND connectableci_id = $iServer AND network_port = 'port_456'",
						'networkdevice_id' => $iDev2,
						'connectableci_id' => $iServer,
						'network_port' => 'port_456',
						'device_port' => '',
					),
				),
				'expected-res' => array (
					"$iDev2, test device B, unit test linkset, port_123_modified, , downlink, test device B",
					"$iDev2, test device B, unit test linkset, port_456, , downlink, test device B",
				),
				'history_added' => 0,
				'history_removed' => 0,
				'history_modified' => 1,
			),
			array(
				'description' => 'Remove the second link (set based on reloaded links, like in the UI)',
				'links' => array(
					array(
						'id' => "SELECT lnkConnectableCIToNetworkDevice WHERE networkdevice_id = $iDev2 AND connectableci_id = $iServer AND network_port = 'port_123_modified'",
						'networkdevice_id' => $iDev2,
						'connectableci_id' => $iServer,
						'network_port' => 'port_123_modified',
						'device_port' => '',
					),
				),
				'expected-res' => array (
					"$iDev2, test device B, unit test linkset, port_123_modified, , downlink, test device B",
				),
				'history_added' => 0,
				'history_removed' => 1,
				'history_modified' => 0,
			),
			array(
				'description' => 'Remove all',
				'links' => array(
				),
				'expected-res' => array (
				),
				'history_added' => 0,
				'history_removed' => 1,
				'history_modified' => 0,
			),
			array(
				'description' => 'Create one link from scratch, no port, to prepare for the next test case',
				'links' => array(
					array(
						'networkdevice_id' => $iDev2,
						'connectableci_id' => $iServer,
						'network_port' => 'portX',
						'device_port' => '',
					),
				),
				'expected-res' => array (
					"$iDev2, test device B, unit test linkset, portX, , downlink, test device B",
				),
				'history_added' => 1,
				'history_removed' => 0,
				'history_modified' => 0,
			),
			array(
				'description' => 'Device B twice (same characteristics) - known issue #1145 (test failing until we fix it)',
				'links' => array(
					array(
						'networkdevice_id' => $iDev2,
						'connectableci_id' => $iServer,
						'network_port' => 'portX',
						'device_port' => '',
					),
					array(
						'networkdevice_id' => $iDev2,
						'connectableci_id' => $iServer,
						'network_port' => 'portX',
						'device_port' => '',
					),
				),
				'expected-res' => array (
			 		"$iDev2, test device B, unit test linkset, portX, , downlink, test device B",
			 		"$iDev2, test device B, unit test linkset, portX, , downlink, test device B",
				),
				'history_added' => 1,
				'history_removed' => 0,
				'history_modified' => 0,
			),
		);
		
		foreach ($aScenarii as $aScenario)
		{
			echo "<h4>".$aScenario['description']."</h4>\n";
		
			$oChange = MetaModel::NewObject("CMDBChange");
			$oChange->Set("date", time());
			$oChange->Set("userinfo", CMDBChange::GetCurrentUserName());
			$oChange->Set("origin", 'custom-extension');
			$oChange->DBInsert();
			CMDBObject::SetCurrentChange($oChange);
			$iChange = $oChange->GetKey();
			
			// Prepare set
			$oLinkset = DBObjectSet::FromScratch('lnkConnectableCIToNetworkDevice');
			foreach ($aScenario['links'] as $aLinkData)
			{
				if (array_key_exists('id', $aLinkData))
				{
					$sOQL = $aLinkData['id'];
					$oSet = new DBObjectSet(DBObjectSearch::FromOQL($sOQL));
					$oLink1 = $oSet->Fetch();
					if (!is_object($oLink1)) throw new Exception('Failed to find the lnkConnectableCIToNetworkDevice: '.$sOQL);
				}
				else
				{
					$oLink1 = MetaModel::NewObject('lnkConnectableCIToNetworkDevice');
				}
				foreach ($aLinkData as $sAttCode => $value)
				{
					if ($sAttCode == 'id') continue;
					$oLink1->Set($sAttCode, $value);
				}
				$oLinkset->AddObject($oLink1);
			}
						
			// Write
			$oServer = MetaModel::GetObject('Server', $iServer);
			$oServer->Set('networkdevice_list', $oLinkset);
			$oServer->DBWrite();
			
			// Check Results
			$bFoundIssue = false;
			$oServer = MetaModel::GetObject('Server', $iServer);
			$oLinkset = $oServer->Get('networkdevice_list');
			
			$aRes = $this->StandardizedDump($oLinkset, 'connectableci_id');
			$sRes = var_export($aRes, true);
			echo "Found: <pre>".$sRes."</pre>\n";
		
			$sExpectedRes = var_export($aScenario['expected-res'], true);
			if ($sRes != $sExpectedRes)
			{
				$bFoundIssue = true;
				echo "NOT COMPLIANT!!! Expecting: <pre>".$sExpectedRes."</pre>\n";
			}
			
			// Check History
			$aQueryParams = array('change' => $iChange, 'objclass' => get_class($oServer), 'objkey' => $oServer->GetKey());
			
			$oAdded = new DBObjectSet(DBSearch::FromOQL("SELECT CMDBChangeOpSetAttributeLinksAddRemove WHERE objclass = :objclass AND objkey = :objkey AND change = :change AND type = 'added'"), array(), $aQueryParams);
			echo "added: ".$oAdded->Count()."<br/>\n";
			if ($aScenario['history_added'] != $oAdded->Count())
			{
				$bFoundIssue = true;
				echo "NOT COMPLIANT!!! Expecting: ".$aScenario['history_added']."<br/>\n";
			}
		
			$oRemoved = new DBObjectSet(DBSearch::FromOQL("SELECT CMDBChangeOpSetAttributeLinksAddRemove WHERE objclass = :objclass AND objkey = :objkey AND change = :change AND type = 'removed'"), array(), $aQueryParams);
			echo "removed: ".$oRemoved->Count()."<br/>\n";
			if ($aScenario['history_removed'] != $oRemoved->Count())
			{
				$bFoundIssue = true;
				echo "NOT COMPLIANT!!! Expecting: ".$aScenario['history_removed']."<br/>\n";
			}
		
			$oModified = new DBObjectSet(DBSearch::FromOQL("SELECT CMDBChangeOpSetAttributeLinksTune WHERE objclass = :objclass AND objkey = :objkey AND change = :change"), array(), $aQueryParams);
			echo "modified: ".$oModified->Count()."<br/>\n";
			if ($aScenario['history_modified'] != $oModified->Count())
			{
				$bFoundIssue = true;
				echo "NOT COMPLIANT!!! Expecting: ".$aScenario['history_modified']."<br/>\n";
			}
		
			if ($bFoundIssue)
			{
				throw new Exception('Stopping on failed scenario');
			}
		}
	}
}

class TestLinkSetRecording_NN_NoDuplicates extends TestLinkSet
{
	static public function GetName()
	{
		return 'Linksets N-N in general (99% of them)';
	}

	static public function GetDescription()
	{
		return 'Simulate CSV/data synchro type of recording. Check the values and the history.';
	}

	protected function DoExecute()
	{
		CMDBSource::Query('START TRANSACTION');
		//CMDBSource::Query('ROLLBACK'); automatique !
		
		////////////////////////////////////////////////////////////////////////////////
		// Set the stage
		//
		$oTeam = MetaModel::NewObject('Team');
		$oTeam->Set('name', 'unit test linkset');
		$oTeam->Set('org_id', 3);
		$oTeam->DBInsert();
		$iTeam = $oTeam->GetKey();
		
		$oPerson = MetaModel::NewObject('Person');
		$oPerson->Set('name', 'test person A');
		$oPerson->Set('first_name', 'totoche');
		$oPerson->Set('org_id', 3);
		$oPerson->DBInsert();
		$iPerson1 = $oPerson->GetKey();
		
		$oPerson = MetaModel::NewObject('Person');
		$oPerson->Set('name', 'test person B');
		$oPerson->Set('first_name', 'totoche');
		$oPerson->Set('org_id', 3);
		$oPerson->DBInsert();
		$iPerson2 = $oPerson->GetKey();
		
		$oTypes = new DBObjectSet(DBSearch::FromOQL('SELECT ContactType WHERE name="Manager"'));
		$iRole = $oTypes->Fetch()->GetKey();

		////////////////////////////////////////////////////////////////////////////////
		// Scenarii
		//
		$aScenarii = array(
			array(
				'description' => 'Add the first item',
				'links' => array(
					array(
						'person_id' => $iPerson1,
						'team_id' => $iTeam,
						'role_id' => 0,
					),
				),
				'expected-res' => array (
			 		"unit test linkset, $iPerson1, test person A, 0, , totoche test person A, ",
				),
				'history_added' => 1,
				'history_removed' => 0,
				'history_modified' => 0,
			),
			array(
				'description' => 'Modify the unique item',
				'links' => array(
					array(
						'person_id' => $iPerson1,
						'team_id' => $iTeam,
						'role_id' => $iRole,
					),
				),
				'expected-res' => array (
			 		"unit test linkset, $iPerson1, test person A, $iRole, Manager, totoche test person A, Manager",
				),
				'history_added' => 0,
				'history_removed' => 0,
				'history_modified' => 1,
			),
			array(
				'description' => 'Modify again the original item and add a second item',
				'links' => array(
					array(
						'person_id' => $iPerson1,
						'team_id' => $iTeam,
						'role_id' => 0,
					),
					array(
						'person_id' => $iPerson2,
						'team_id' => $iTeam,
						'role_id' => 0,
					),
				),
				'expected-res' => array (
			 		"unit test linkset, $iPerson1, test person A, 0, , totoche test person A, ",
			 		"unit test linkset, $iPerson2, test person B, 0, , totoche test person B, ",
				),
				'history_added' => 1,
				'history_removed' => 0,
				'history_modified' => 1,
			),
			array(
				'description' => 'No change, the links are added in the reverse order',
				'links' => array(
					array(
						'person_id' => $iPerson2,
						'team_id' => $iTeam,
						'role_id' => 0,
					),
					array(
						'person_id' => $iPerson1,
						'team_id' => $iTeam,
						'role_id' => 0,
					),
				),
				'expected-res' => array (
			 		"unit test linkset, $iPerson1, test person A, 0, , totoche test person A, ",
			 		"unit test linkset, $iPerson2, test person B, 0, , totoche test person B, ",
				),
				'history_added' => 0,
				'history_removed' => 0,
				'history_modified' => 0,
			),
			array(
				'description' => 'Removing A',
				'links' => array(
					array(
						'person_id' => $iPerson2,
						'team_id' => $iTeam,
						'role_id' => 0,
					),
				),
				'expected-res' => array (
			 		"unit test linkset, $iPerson2, test person B, 0, , totoche test person B, ",
				),
				'history_added' => 0,
				'history_removed' => 1,
				'history_modified' => 0,
			),
			array(
				'description' => 'Adding B again (duplicate!)',
				'links' => array(
					array(
						'person_id' => $iPerson2,
						'team_id' => $iTeam,
						'role_id' => 0,
					),
					array(
						'person_id' => $iPerson2,
						'team_id' => $iTeam,
						'role_id' => 0,
					),
				),
				'expected-res' => array (
			 		"unit test linkset, $iPerson2, test person B, 0, , totoche test person B, ",
				),
				'history_added' => 0,
				'history_removed' => 0,
				'history_modified' => 0,
			),
			array(
				'description' => 'Remove all',
				'links' => array(
				),
				'expected-res' => array (
				),
				'history_added' => 0,
				'history_removed' => 1,
				'history_modified' => 0,
			),
			array(
				'description' => 'Add the first item (again)',
				'links' => array(
					array(
						'person_id' => $iPerson1,
						'team_id' => $iTeam,
						'role_id' => 0,
					),
				),
				'expected-res' => array (
			 		"unit test linkset, $iPerson1, test person A, 0, , totoche test person A, ",
				),
				'history_added' => 1,
				'history_removed' => 0,
				'history_modified' => 0,
			),
			array(
				'description' => 'Set the role (based on reloaded links, like in the UI)',
				'links' => array(
					array(
						'id' => "SELECT lnkPersonToTeam WHERE person_id=$iPerson1 AND team_id=$iTeam",
						'person_id' => $iPerson1,
						'team_id' => $iTeam,
						'role_id' => $iRole,
					),
				),
				'expected-res' => array (
			 		"unit test linkset, $iPerson1, test person A, 14, Manager, totoche test person A, Manager",
				),
				'history_added' => 0,
				'history_removed' => 0,
				'history_modified' => 1,
			),
			array(
				'description' => 'Clear the role and add another person with a role (based on reloaded links, like in the UI)',
				'links' => array(
					array(
						'id' => "SELECT lnkPersonToTeam WHERE person_id=$iPerson1 AND team_id=$iTeam",
						'person_id' => $iPerson1,
						'team_id' => $iTeam,
						'role_id' => 0,
					),
					array(
						'person_id' => $iPerson2,
						'team_id' => $iTeam,
						'role_id' => $iRole,
					),
				),
				'expected-res' => array (
			 		"unit test linkset, $iPerson1, test person A, 0, , totoche test person A, ",
			 		"unit test linkset, $iPerson2, test person B, 14, Manager, totoche test person B, Manager",
				),
				'history_added' => 1,
				'history_removed' => 0,
				'history_modified' => 1,
			),
		);
		
		foreach ($aScenarii as $aScenario)
		{
			echo "<h4>".$aScenario['description']."</h4>\n";
		
			$oChange = MetaModel::NewObject("CMDBChange");
			$oChange->Set("date", time());
			$oChange->Set("userinfo", CMDBChange::GetCurrentUserName());
			$oChange->Set("origin", 'custom-extension');
			$oChange->DBInsert();
			CMDBObject::SetCurrentChange($oChange);
			$iChange = $oChange->GetKey();
			
			// Prepare set
			$oLinkset = DBObjectSet::FromScratch('lnkPersonToTeam');
			foreach ($aScenario['links'] as $aLinkData)
			{
				if (array_key_exists('id', $aLinkData))
				{
					$sOQL = $aLinkData['id'];
					$oSet = new DBObjectSet(DBObjectSearch::FromOQL($sOQL));
					$oLink1 = $oSet->Fetch();
					if (!is_object($oLink1)) throw new Exception('Failed to find the lnkPersonToTeam: '.$sOQL);
				}
				else
				{
					$oLink1 = MetaModel::NewObject('lnkPersonToTeam');
				}
				foreach ($aLinkData as $sAttCode => $value)
				{
					if ($sAttCode == 'id') continue;
					$oLink1->Set($sAttCode, $value);
				}
				$oLinkset->AddObject($oLink1);
			}
			
			// Write
			$oTeam = MetaModel::GetObject('Team', $iTeam);
			$oTeam->Set('persons_list', $oLinkset);
			$oTeam->DBWrite();
			
			// Check Results
			$bFoundIssue = false;
			$oTeam = MetaModel::GetObject('Team', $iTeam);
			$oLinkset = $oTeam->Get('persons_list');
			
			$aRes = $this->StandardizedDump($oLinkset, 'team_id');
			$sRes = var_export($aRes, true);
			echo "Found: <pre>".$sRes."</pre>\n";
		
			$sExpectedRes = var_export($aScenario['expected-res'], true);
			if ($sRes != $sExpectedRes)
			{
				$bFoundIssue = true;
				echo "NOT COMPLIANT!!! Expecting: <pre>".$sExpectedRes."</pre>\n";
			}
			
			// Check History
			$aQueryParams = array('change' => $iChange, 'objclass' => get_class($oTeam), 'objkey' => $oTeam->GetKey());
			
			$oAdded = new DBObjectSet(DBSearch::FromOQL("SELECT CMDBChangeOpSetAttributeLinksAddRemove WHERE objclass = :objclass AND objkey = :objkey AND change = :change AND type = 'added'"), array(), $aQueryParams);
			echo "added: ".$oAdded->Count()."<br/>\n";
			if ($aScenario['history_added'] != $oAdded->Count())
			{
				$bFoundIssue = true;
				echo "NOT COMPLIANT!!! Expecting: ".$aScenario['history_added']."<br/>\n";
			}
		
			$oRemoved = new DBObjectSet(DBSearch::FromOQL("SELECT CMDBChangeOpSetAttributeLinksAddRemove WHERE objclass = :objclass AND objkey = :objkey AND change = :change AND type = 'removed'"), array(), $aQueryParams);
			echo "removed: ".$oRemoved->Count()."<br/>\n";
			if ($aScenario['history_removed'] != $oRemoved->Count())
			{
				$bFoundIssue = true;
				echo "NOT COMPLIANT!!! Expecting: ".$aScenario['history_removed']."<br/>\n";
			}
		
			$oModified = new DBObjectSet(DBSearch::FromOQL("SELECT CMDBChangeOpSetAttributeLinksTune WHERE objclass = :objclass AND objkey = :objkey AND change = :change"), array(), $aQueryParams);
			echo "modified: ".$oModified->Count()."<br/>\n";
			if ($aScenario['history_modified'] != $oModified->Count())
			{
				$bFoundIssue = true;
				echo "NOT COMPLIANT!!! Expecting: ".$aScenario['history_modified']."<br/>\n";
			}
		
			if ($bFoundIssue)
			{
				throw new Exception('Stopping on failed scenario');
			}
		}
	}
}

class TestLinkSetRecording_1N extends TestLinkSet
{
	static public function GetName()
	{
		return 'Linkset 1-N (Network Interface vs Server: Edit in-place)';
	}

	static public function GetDescription()
	{
		return 'Simulate CSV/data synchro type of recording. Check the values and the history.';
	}

	protected function DoExecute()
	{
		CMDBSource::Query('START TRANSACTION');
		//CMDBSource::Query('ROLLBACK'); automatique !

		////////////////////////////////////////////////////////////////////////////////
		// Set the stage
		//
		$oServer = MetaModel::NewObject('Server');
		$oServer->Set('name', 'unit test linkset');
		$oServer->Set('org_id', 3);
		$oServer->DBInsert();
		$iServer = $oServer->GetKey();

		////////////////////////////////////////////////////////////////////////////////
		// Scenarii
		//
		$aScenarii = array(
			array(
				'description' => 'Add the first interface',
				'interfaces' => array(
					array(
					'connectableci_id' => $iServer,
					'name' => 'eth0',
					'speed' => '1000.00',
					),
				),
				'expected-res' => array (
					"eth0, , , , , , 1000.00, $iServer, unit test linkset, PhysicalInterface, unit test linkset, Server",
				),
				'history_added' => 1,
				'history_removed' => 0,
				'history_modified' => 0,
			),
			array(
				'description' => 'Add a second interface',
				'interfaces' => array(
					array(
					'connectableci_id' => $iServer,
					'name' => 'eth0',
					'speed' => '1000.00',
					),
					array(
					'connectableci_id' => $iServer,
					'name' => 'eth1',
					'speed' => '1000.00',
					),
				),
				'expected-res' => array (
					"eth0, , , , , , 1000.00, $iServer, unit test linkset, PhysicalInterface, unit test linkset, Server",
					"eth1, , , , , , 1000.00, $iServer, unit test linkset, PhysicalInterface, unit test linkset, Server",
				),
				'history_added' => 1,
				'history_removed' => 0,
				'history_modified' => 0,
			),
			array(
				'description' => 'Change the speed of an interface',
				'interfaces' => array(
					array(
					'connectableci_id' => $iServer,
					'name' => 'eth0',
					'speed' => '100.00',
					),
					array(
					'connectableci_id' => $iServer,
					'name' => 'eth1',
					'speed' => '1000.00',
					),
				),
				'expected-res' => array (
					"eth0, , , , , , 100.00, $iServer, unit test linkset, PhysicalInterface, unit test linkset, Server",
					"eth1, , , , , , 1000.00, $iServer, unit test linkset, PhysicalInterface, unit test linkset, Server",
				),
				'history_added' => 1,
				'history_removed' => 1,
				'history_modified' => 0,
			),
			array(
				'description' => 'Change the name of an interface',
				'interfaces' => array(
					array(
					'connectableci_id' => $iServer,
					'name' => 'eth0-renamed',
					'speed' => '1000.00',
					),
					array(
					'connectableci_id' => $iServer,
					'name' => 'eth1',
					'speed' => '1000.00',
					),
				),
				'expected-res' => array (
					"eth0-renamed, , , , , , 1000.00, $iServer, unit test linkset, PhysicalInterface, unit test linkset, Server",
					"eth1, , , , , , 1000.00, $iServer, unit test linkset, PhysicalInterface, unit test linkset, Server",
				),
				'history_added' => 1,
				'history_removed' => 1,
				'history_modified' => 0,
			),
			array(
				'description' => 'Remove all interfaces',
				'interfaces' => array(
				),
				'expected-res' => array (
				),
				'history_added' => 0,
				'history_removed' => 2,
				'history_modified' => 0,
			),
		);

		foreach ($aScenarii as $aScenario)
		{
			echo "<h4>".$aScenario['description']."</h4>\n";

			$oChange = MetaModel::NewObject("CMDBChange");
			$oChange->Set("date", time());
			$oChange->Set("userinfo", CMDBChange::GetCurrentUserName());
			$oChange->Set("origin", 'custom-extension');
			$oChange->DBInsert();
			CMDBObject::SetCurrentChange($oChange);
			$iChange = $oChange->GetKey();
				
			// Prepare set
			$oLinkset = DBObjectSet::FromScratch('PhysicalInterface');
			foreach ($aScenario['interfaces'] as $aIntfData)
			{
				$oInterface = MetaModel::NewObject('PhysicalInterface');
				foreach ($aIntfData as $sAttCode => $value)
				{
					$oInterface->Set($sAttCode, $value);
				}
				$oLinkset->AddObject($oInterface);
			}
				
			// Write
			$oServer = MetaModel::GetObject('Server', $iServer);
			$oServer->Set('physicalinterface_list', $oLinkset);
			$oServer->DBWrite();
				
			// Check Results
			$bFoundIssue = false;
			$oServer = MetaModel::GetObject('Server', $iServer);
			$oLinkset = $oServer->Get('physicalinterface_list');
				
			$aRes = $this->StandardizedDump($oLinkset, 'zzz');
			$sRes = var_export($aRes, true);
			echo "Found: <pre>".$sRes."</pre>\n";

			$sExpectedRes = var_export($aScenario['expected-res'], true);
			if ($sRes != $sExpectedRes)
			{
				$bFoundIssue = true;
				echo "NOT COMPLIANT!!! Expecting: <pre>".$sExpectedRes."</pre>\n";
			}
				
			// Check History
			$aQueryParams = array('change' => $iChange, 'objclass' => get_class($oServer), 'objkey' => $oServer->GetKey());
				
			$oAdded = new DBObjectSet(DBSearch::FromOQL("SELECT CMDBChangeOpSetAttributeLinksAddRemove WHERE objclass = :objclass AND objkey = :objkey AND change = :change AND type = 'added'"), array(), $aQueryParams);
			echo "added: ".$oAdded->Count()."<br/>\n";
			if ($aScenario['history_added'] != $oAdded->Count())
			{
				$bFoundIssue = true;
				echo "NOT COMPLIANT!!! Expecting: ".$aScenario['history_added']."<br/>\n";
			}

			$oRemoved = new DBObjectSet(DBSearch::FromOQL("SELECT CMDBChangeOpSetAttributeLinksAddRemove WHERE objclass = :objclass AND objkey = :objkey AND change = :change AND type = 'removed'"), array(), $aQueryParams);
			echo "removed: ".$oRemoved->Count()."<br/>\n";
			if ($aScenario['history_removed'] != $oRemoved->Count())
			{
				$bFoundIssue = true;
				echo "NOT COMPLIANT!!! Expecting: ".$aScenario['history_removed']."<br/>\n";
			}

			$oModified = new DBObjectSet(DBSearch::FromOQL("SELECT CMDBChangeOpSetAttributeLinksTune WHERE objclass = :objclass AND objkey = :objkey AND change = :change"), array(), $aQueryParams);
			echo "modified: ".$oModified->Count()."<br/>\n";
			if ($aScenario['history_modified'] != $oModified->Count())
			{
				$bFoundIssue = true;
				echo "NOT COMPLIANT!!! Expecting: ".$aScenario['history_modified']."<br/>\n";
			}

			if ($bFoundIssue)
			{
				throw new Exception('Stopping on failed scenario');
			}
		}
	}
}


class TestLinkSetRecording_1NAdd_Remove extends TestLinkSet
{
	static public function GetName()
	{
		return 'Linkset 1-N (Delivery Model vs Organization: Edit Add/Remove)';
	}

	static public function GetDescription()
	{
		return 'Simulate CSV/data synchro type of recording. Check the values and the history.';
	}

	protected function DoExecute()
	{
		CMDBSource::Query('START TRANSACTION');
		//CMDBSource::Query('ROLLBACK'); automatique !

		////////////////////////////////////////////////////////////////////////////////
		// Set the stage
		//
		$oProvider = new Organization();
		$oProvider->Set('name', 'Test-Provider1');
		$oProvider->DBInsert();
		$iProvider = $oProvider->GetKey();
		
		$oDM1 = new DeliveryModel();
		$oDM1->Set('name', 'Test-DM-1');
		$oDM1->Set('org_id', $iProvider);
		$oDM1->DBInsert();
		$iDM1 = $oDM1->GetKey();

		$oDM2 = new DeliveryModel();
		$oDM2->Set('name', 'Test-DM-2');
		$oDM2->Set('org_id', $iProvider);
		$oDM2->DBInsert();
		$iDM2 = $oDM2->GetKey();

		////////////////////////////////////////////////////////////////////////////////
		// Scenarii
		//
		$aScenarii = array(
			array(
				'description' => 'Add the first customer',
				'organizations' => array(
					array(
						'deliverymodel_id' => $iDM1,
						'name' => 'Test-Customer-1',
						),
					),
				'expected-res' => array (
					"Test-Customer-1, , active, 0, , $iDM1, Test-DM-1, , Test-DM-1",
				),
				'history_added' => 0,
				'history_removed' => 0,
				'history_modified' => 0,
			),	
			array(
				'description' => 'Remove the customer by loading an empty set',
				'organizations' => array(
					),
				'expected-res' => array (
				),
				'history_added' => 0,
				'history_removed' => 0,
				'history_modified' => 0,
			),	
			array(
				'description' => 'Create two customers at once',
				'organizations' => array(
					array(
						'deliverymodel_id' => $iDM1,
						'name' => 'Test-Customer-1',
					),
					array(
						'deliverymodel_id' => $iDM1,
						'name' => 'Test-Customer-2',
					),
				),
				'expected-res' => array (
					"Test-Customer-1, , active, 0, , $iDM1, Test-DM-1, , Test-DM-1",
					"Test-Customer-2, , active, 0, , $iDM1, Test-DM-1, , Test-DM-1",
				),
				'history_added' => 0,
				'history_removed' => 0,
				'history_modified' => 0,
			),	
			array(
				'description' => 'Move Customer-1 to the second Delivery Model',
				'organizations' => array(
					array(
						'id' => "SELECT Organization WHERE name='Test-Customer-1'",
						'deliverymodel_id' => $iDM2,
						'name' => 'Test-Customer-1',
					),
					array(
						'deliverymodel_id' => $iDM1,
						'name' => 'Test-Customer-2',
					),
				),
				'expected-res' => array (
					"Test-Customer-2, , active, 0, , $iDM1, Test-DM-1, , Test-DM-1",
				),
				'history_added' => 0,
				'history_removed' => 0,
				'history_modified' => 0,
			),	
			array(
				'description' => 'Move Customer-1 back to the first Delivery Model and reset Customer-2 (no Delivery Model)',
				'organizations' => array(
					array(
						'id' => "SELECT Organization WHERE name='Test-Customer-1'",
						'deliverymodel_id' => $iDM1,
						'name' => 'Test-Customer-1',
					),
					array(
						'id' => "SELECT Organization WHERE name='Test-Customer-2'",
						'deliverymodel_id' => 0,
						'name' => 'Test-Customer-2',
					),
				),
				'expected-res' => array (
					"Test-Customer-1, , active, 0, , $iDM1, Test-DM-1, , Test-DM-1",
				),
				'history_added' => 0,
				'history_removed' => 0,
				'history_modified' => 0,
			),	
		);

		foreach ($aScenarii as $aScenario)
		{
			echo "<h4>".$aScenario['description']."</h4>\n";

			$oChange = MetaModel::NewObject("CMDBChange");
			$oChange->Set("date", time());
			$oChange->Set("userinfo", CMDBChange::GetCurrentUserName());
			$oChange->Set("origin", 'custom-extension');
			$oChange->DBInsert();
			CMDBObject::SetCurrentChange($oChange);
			$iChange = $oChange->GetKey();

			// Prepare set
			$oLinkset = DBObjectSet::FromScratch('Organization');
			foreach ($aScenario['organizations'] as $aOrgData)
			{
				if (array_key_exists('id', $aOrgData))
				{
					$sOQL = $aOrgData['id'];
					$oSet = new DBObjectSet(DBObjectSearch::FromOQL($sOQL));
					$oOrg = $oSet->Fetch();
					if (!is_object($oOrg)) throw new Exception('Failed to find the Organization: '.$sOQL);
				}
				else
				{
					$oOrg = MetaModel::NewObject('Organization');
				}
				foreach ($aOrgData as $sAttCode => $value)
				{
					if ($sAttCode == 'id') continue;
					$oOrg->Set($sAttCode, $value);
				}
				$oLinkset->AddObject($oOrg);
			}

			// Write
			$oDM = MetaModel::GetObject('DeliveryModel', $iDM1);
			$oDM->Set('customers_list', $oLinkset);
			$oDM->DBWrite();

			// Check Results
			$bFoundIssue = false;
			$oDM = MetaModel::GetObject('DeliveryModel', $iDM1);
			$oLinkset = $oDM->Get('customers_list');

			$aRes = $this->StandardizedDump($oLinkset, 'zzz');
			$sRes = var_export($aRes, true);
			echo "Found: <pre>".$sRes."</pre>\n";

			$sExpectedRes = var_export($aScenario['expected-res'], true);
			if ($sRes != $sExpectedRes)
			{
				$bFoundIssue = true;
				echo "NOT COMPLIANT!!! Expecting: <pre>".$sExpectedRes."</pre>\n";
			}

			// Check History
			$aQueryParams = array('change' => $iChange, 'objclass' => get_class($oDM), 'objkey' => $oDM->GetKey());

			$oAdded = new DBObjectSet(DBSearch::FromOQL("SELECT CMDBChangeOpSetAttributeLinksAddRemove WHERE objclass = :objclass AND objkey = :objkey AND change = :change AND type = 'added'"), array(), $aQueryParams);
			echo "added: ".$oAdded->Count()."<br/>\n";
			if ($aScenario['history_added'] != $oAdded->Count())
			{
				$bFoundIssue = true;
				echo "NOT COMPLIANT!!! Expecting: ".$aScenario['history_added']."<br/>\n";
			}

			$oRemoved = new DBObjectSet(DBSearch::FromOQL("SELECT CMDBChangeOpSetAttributeLinksAddRemove WHERE objclass = :objclass AND objkey = :objkey AND change = :change AND type = 'removed'"), array(), $aQueryParams);
			echo "removed: ".$oRemoved->Count()."<br/>\n";
			if ($aScenario['history_removed'] != $oRemoved->Count())
			{
				$bFoundIssue = true;
				echo "NOT COMPLIANT!!! Expecting: ".$aScenario['history_removed']."<br/>\n";
			}

			$oModified = new DBObjectSet(DBSearch::FromOQL("SELECT CMDBChangeOpSetAttributeLinksTune WHERE objclass = :objclass AND objkey = :objkey AND change = :change"), array(), $aQueryParams);
			echo "modified: ".$oModified->Count()."<br/>\n";
			if ($aScenario['history_modified'] != $oModified->Count())
			{
				$bFoundIssue = true;
				echo "NOT COMPLIANT!!! Expecting: ".$aScenario['history_modified']."<br/>\n";
			}

			if ($bFoundIssue)
			{
				throw new Exception('Stopping on failed scenario');
			}
		}
	}
}

class TestDateTimeFormats extends TestBizModel
{
	static public function GetName() {return 'Check Date & Time formating and parsing';}
	static public function GetDescription() {return 'Check the formating and parsing of dates for various formats';}
	public function DoExecute()
	{
		require_once(APPROOT.'core/datetimeformat.class.inc.php');
		$bRet = true;
		$aTestFormats = array(
				'French (short)' => 'd/m/Y H:i:s',
				'French (short - no seconds)' => 'd/m/Y H:i',
				'French (long)' => 'd/m/Y H\\h i\\m\\i\\n s\\s',
				'English US' => 'm/d/Y H:i:s',
				'English US (12 hours)' => 'm/d/Y h:i:s a',
				'English US (12 hours, short)' => 'n/j/Y g:i:s a',
				'English UK' => 'd/m/Y H:i:s',
				'German' => 'd.m.Y H:i:s',
				'SQL' => 'Y-m-d H:i:s',
		);
		// Valid date and times, all tests should pass
		$aTestDates = array('2015-01-01 00:00:00', '2015-12-31 23:59:00', '2016-01-01 08:21:00', '2016-02-28 12:30:00', '2016-02-29 16:47:00', /*'2016-02-29 14:30:17'*/);
		foreach($aTestFormats as $sDesc => $sFormat)
		{
			$this->ReportSuccess("Test of the '$sDesc' format: '$sFormat':");
			$oFormat = new DateTimeFormat($sFormat);
			foreach($aTestDates as $sTestDate)
			{
				$oDate = new DateTime($sTestDate);
				$sFormattedDate = $oFormat->Format($oDate);
				$oParsedDate = $oFormat->Parse($sFormattedDate);
				$sPattern = $oFormat->ToRegExpr('/');
				$bParseOk = ($oParsedDate->format('Y-m-d H:i:s') == $sTestDate);
				if (!$bParseOk)
				{
					$this->ReportError('Parsed ('.$sFormattedDate.') date different from initial date (difference of '.((int)$oParsedDate->format('U')- (int)$oDate->format('U')).'s)');
					$bRet = false;
				}
				$bValidateOk = preg_match($sPattern, $sFormattedDate);
				if (!$bValidateOk)
				{
					$this->ReportError('Formatted date ('.$sFormattedDate.') does not match the validation pattern ('.$sPattern.')');
					$bRet = false;
				}
				
				$this->ReportSuccess("Formatted date: $sFormattedDate - Parsing: ".($bParseOk ? 'Ok' : '<b>KO</b>')." - Validation: ".($bValidateOk ? 'Ok' : '<b>KO</b>'));
			}
			echo "</p>\n";
		}

		// Invalid date & time strings, all regexpr validation should fail
		$aInvalidTestDates = array(
			'SQL' => array('2015-13-01 00:00:00', '2015-12-51 23:59:00', '2016-01-01 +08:21:00', '2016-02-28 24:30:00', '2016-02-29 16:67:88'),
			'French (short)' => array('01/01/20150 00:00:00', '01/01/20150 00:00:00', '01/13/2015 00:00:00', '01/01/2015 40:00:00', '01/01/2015 00:99:00'),
			'English US (12 hours)' => array('13/01/2015 12:00:00 am', '12/33/2015 12:00:00 am', '12/23/215 12:00:00 am', '05/04/2016 16:00:00 am', '05/04/2016 10:00:00 ap'),
		);
		
		foreach($aInvalidTestDates as $sFormatName => $aDatesToParse)
		{
			$sFormat = $aTestFormats[$sFormatName];
			$oFormat = new DateTimeFormat($sFormat);
			$this->ReportSuccess("Test of the '$sFormatName' format: '$sFormat':");
			foreach($aDatesToParse as $sDate)
			{
				$sPattern = $oFormat->ToRegExpr('/');
				$bValidateOk = preg_match($sPattern, $sDate);
				if ($bValidateOk)
				{
					$this->ReportError('Formatted date ('.$sFormattedDate.') matches the validation pattern ('.$sPattern.') whereas it should not!');
					$bRet = false;
				}
				$this->ReportSuccess("Formatted date: $sDate - Validation: ".($bValidateOk ? '<b>KO</n>' : 'rejected, Ok.'));
			}	
		}
		return $bRet;
	}
}

class TestExecActions extends TestBizModel
{
	static public function GetName()
	{
		return 'Scripted actions API DBObject::ExecAction - syntax errors';
	}

	static public function GetDescription()
	{
		return 'Check that wrong arguments are correclty reported';
	}

	protected function DoExecute()
	{
		$oSource = new UserRequest();
		$oSource->Set('title', 'Houston!');
		$oSource->Set('description', 'Looks like we have a problem');

		$oTarget = new Server();

		////////////////////////////////////////////////////////////////////////////////
		// Scenarii
		//
		$aScenarii = array(
			array(
				'action' => 'set',
				'error' => 'Action: set - Invalid syntax'
			),
			array(
				'action' => 'smurf()',
				'error' => 'Action: smurf() - Invalid verb'
			),
			array(
				'action' => ' smurf () ',
				'error' => 'Action:  smurf ()  - Invalid syntax'
			),
			array(
				'action' => 'clone(some_att_code, another_one)',
				'error' => 'Action: clone(some_att_code, another_one) - Unknown attribute Server::some_att_code'
			),
			array(
				'action' => 'copy(toto, titi)',
				'error' => 'Action: copy(toto, titi) - Unknown attribute Server::titi'
			),
			array(
				'action' => 'copy(toto, name)',
				'error' => 'Action: copy(toto, name) - Unknown attribute UserRequest::toto'
			),
			array(
				'action' => 'copy()',
				'error' => 'Action: copy() - Missing argument #1: source attribute'
			),
			array(
				'action' => 'copy(title)',
				'error' => 'Action: copy(title) - Missing argument #2: target attribute'
			),
			array(
				'action' => 'set(toto)',
				'error' => 'Action: set(toto) - Unknown attribute Server::toto'
			),
			array(
				'action' => 'set(toto, something)',
				'error' => 'Action: set(toto, something) - Unknown attribute Server::toto'
			),
			array(
				'action' => 'set()',
				'error' => 'Action: set() - Missing argument #1: target attribute'
			),
			array(
				'action' => 'reset(toto)',
				'error' => 'Action: reset(toto) - Unknown attribute Server::toto'
			),
			array(
				'action' => 'reset()',
				'error' => 'Action: reset() - Missing argument #1: target attribute'
			),
			array(
				'action' => 'nullify(toto)',
				'error' => 'Action: nullify(toto) - Unknown attribute Server::toto'
			),
			array(
				'action' => 'nullify()',
				'error' => 'Action: nullify() - Missing argument #1: target attribute'
			),
			array(
				'action' => 'append(toto, something)',
				'error' => 'Action: append(toto, something) - Unknown attribute Server::toto'
			),
			array(
				'action' => 'append(name)',
				'error' => 'Action: append(name) - Missing argument #2: value to append'
			),
			array(
				'action' => 'append()',
				'error' => 'Action: append() - Missing argument #1: target attribute'
			),
			array(
				'action' => 'add_to_list(toto, titi)',
				'error' => 'Action: add_to_list(toto, titi) - Unknown attribute UserRequest::toto'
			),
			array(
				'action' => 'add_to_list(caller_id, titi)',
				'error' => 'Action: add_to_list(caller_id, titi) - Unknown attribute Server::titi'
			),
			array(
				'action' => 'add_to_list(caller_id)',
				'error' => 'Action: add_to_list(caller_id) - Missing argument #2: target attribute (link set)'
			),
			array(
				'action' => 'add_to_list()',
				'error' => 'Action: add_to_list() - Missing argument #1: source attribute'
			),
			array(
				'action' => 'apply_stimulus(toto)',
				'error' => 'Action: apply_stimulus(toto) - Unknown stimulus Server::toto'
			),
			array(
				'action' => 'apply_stimulus()',
				'error' => 'Action: apply_stimulus() - Missing argument #1: stimulus'
			),
			array(
				'action' => 'call_method(toto)',
				'error' => 'Action: call_method(toto) - Unknown method Server::toto()'
			),
			array(
				'action' => 'call_method()',
				'error' => 'Action: call_method() - Missing argument #1: method name'
			),
		);

		foreach ($aScenarii as $aScenario)
		{
			echo "<h4>".htmlentities($aScenario['action'], ENT_QUOTES, 'UTF-8')."</h4>\n";
			$sMessage = '';
			try
			{
				$oTarget->ExecActions(array($aScenario['action']), array('source' => $oSource));
				$sMessage = 'Expecting an exception... none has been thrown!';
			}
			catch (Exception $e)
			{
				if ($e->getMessage() != $aScenario['error'])
				{
					$sMessage = 'Wrong message: expecting "'.$aScenario['error'].'" and got "'.$e->getMessage().'"';
				}
			}
			if ($sMessage !='')
			{
				throw new Exception($sMessage);
			}
		}
	}
}

class TestParsingOptimization extends TestBizModel
{
	static public function GetName()
	{
		return 'Query optimizations (Merging joins on OQL parsing)';
	}

	static public function GetDescription()
	{
		return 'Checking a few queries that do involve query optimizations (implemented for the sake of optimizing the portal)';
	}

	protected function DoExecute()
	{
		$aQueries = array(
			"SELECT UserRequest AS u
				JOIN Person AS p1 ON u.caller_id=p1.id
				JOIN Organization AS o1 ON p1.org_id=o1.id
				JOIN Person AS p2 ON u.caller_id=p2.id WHERE p2.status='active' AND p1.status='inactive'",
			"SELECT UserRequest AS u
				JOIN Person AS p1 ON u.caller_id=p1.id
				JOIN Person AS p2 ON u.caller_id=p2.id WHERE p2.status='active' AND p1.status='inactive'",
			"SELECT UserRequest AS u
				JOIN Person AS p1 ON u.caller_id=p1.id
				JOIN Organization AS o1 ON p1.org_id=o1.id
				JOIN Person ON u.caller_id=Person.id
				JOIN Location AS l ON Person.location_id = l.id WHERE Person.status='active' AND p1.status='inactive' AND l.country='France'",
		);
		foreach ($aQueries as $sQuery)
		{
			echo "<h5>To Parse: ".htmlentities($sQuery, ENT_QUOTES, 'UTF-8')."</h5>\n";
			$oSearch = DBSearch::FromOQL($sQuery);
			$sQueryOpt = $oSearch->ToOQL();
			echo "<h5>Optimized: ".htmlentities($sQueryOpt, ENT_QUOTES, 'UTF-8')."</h5>\n";
			CMDBSource::TestQuery($oSearch->MakeSelectQuery());
			echo "<p>Successfully tested the SQL query.</p>\n";
		}
	}
}

class TestUnions extends TestBizModel
{
	static public function GetName()
	{
		return 'Unions';
	}

	static public function GetDescription()
	{
		return 'Checking a few UNION queries';
	}

	protected function DoExecute()
	{
		// The two first items did reveal an issue with the query cache,
		//because SELECT Person on the second line must not give the same query as SELECT Person on the first line
		$aQueries = array(
			"SELECT Person UNION SELECT Person" => true,
			"SELECT Person UNION SELECT Team" => true,
			"SELECT Person UNION SELECT Contact" => true,
			"SELECT Contact UNION SELECT Person" => true,
			"SELECT Person UNION SELECT Organization" => false,
		);
		foreach ($aQueries as $sQuery => $bSuccess)
		{
			echo "<h5>To Parse: ".htmlentities($sQuery, ENT_QUOTES, 'UTF-8')."</h5>\n";
			try
			{
				$oSearch = DBSearch::FromOQL($sQuery);
				if (!$bSuccess) throw new Exception('This query should not be parsable!');

				CMDBSource::TestQuery($oSearch->MakeSelectQuery());
				echo "<p>Successfully tested the SQL query.</p>\n";
			}
			catch (OQLException $e)
			{
				if ($bSuccess) throw $e;
				echo "<p>Failed as expected.</p>\n";
			}
		}
	}
}

class TestImplicitAlias extends TestBizModel
{
	static public function GetName()
	{
		return 'OQLImplicitAliases';
	}

	static public function GetDescription()
	{
		return 'Checking implicit aliases resolution';
	}

	protected function DoExecute()
	{
		// The two first items did reveal an issue with the query cache,
		//because SELECT Person on the second line must not give the same query as SELECT Person on the first line
		$aQueries = array(
			"SELECT Person WHERE org_id = 1" => true,
			"SELECT Person WHERE s.org_id = 1" => false,
			"SELECT Person AS p WHERE p.org_id = 1" => true,
			"SELECT Person AS p WHERE Person.org_id = 1" => false,
			"SELECT P FROM Organization AS O JOIN Person AS P ON P.org_id = O.id WHERE org_id = 2" => true, // Bug N.539
			"SELECT Server JOIN Location ON Server.location_id = Location.id" => true,
			"SELECT Server JOIN Location ON Server.location_id = id" => false,
			"SELECT Server JOIN Location ON Server = Location.id" => false,
			"SELECT Server JOIN Location ON Server.location_id = Location.id WHERE Server.org_id = 1" => true,
			"SELECT Server JOIN Location ON Server.location_id = Location.id WHERE org_id = 1" => false,
		);
		foreach ($aQueries as $sQuery => $bSuccess)
		{
			echo "<h5>To Parse: ".htmlentities($sQuery, ENT_QUOTES, 'UTF-8')."</h5>\n";
			try
			{
				$oSearch = DBSearch::FromOQL($sQuery);
				if (!$bSuccess) throw new Exception('This query should not be parsable!');

				CMDBSource::TestQuery($oSearch->MakeSelectQuery());
				echo "<p>Successfully tested the SQL query.</p>\n";
			}
			catch (OQLException $e)
			{
				if ($bSuccess) throw $e;
				echo "<p>Failed as expected.</p>\n";
			}
		}
	}
}

class TestBug609 extends TestBizModel
{
	static public function GetName()
	{
		return 'UNION with JOINS ordered differently';
	}

	static public function GetDescription()
	{
		return '(N.609) Inconsistent SQL query (various symptoms, must mostly in the form of "Class \'IT Department\' not found"';
	}

	protected function DoExecute()
	{
		$sQueryA = 'SELECT t,o FROM Team AS t JOIN Organization AS o ON t.org_id = o.id';
		$sQueryB = 'SELECT t,o FROM Organization AS o JOIN Team AS t ON t.org_id = o.id';

		$oSearch = DBSearch::FromOQL("$sQueryB UNION $sQueryA");

		$oSet = new DBObjectSet($oSearch);
		while($oObject = $oSet->Fetch())
		{
			echo "Successfull load for <b>".$oObject->GetName()."</b><br>\n";
		}
	}
}

class TestBug788 extends TestBizModel
{
	static public function GetName()
	{
		return 'Graph - delete nodes';
	}

	static public function GetDescription()
	{
		return '(N.788) Graph not refreshed when unchecking some classes';
	}

	protected function DoExecute()
	{
		$oGraph = new SimpleGraph();
		$a = new GraphNode($oGraph, 'A');
		$b = new GraphNode($oGraph, 'B');
		$c = new GraphNode($oGraph, 'C');
		new GraphEdge($oGraph, 'A--B', $a, $b);
		new GraphEdge($oGraph, 'B--C', $b, $c);
		new GraphEdge($oGraph, 'C--B', $c, $b);

		echo "<h5>Graphe initial</h5>";
		echo $oGraph->DumpAsHtmlImage();
		echo $oGraph->DumpAsHTMLText();

		echo "<h5>Removing C</h5>";
		$oGraph->FilterNode($c);
		unset($c);
		echo $oGraph->DumpAsHtmlImage();
		echo $oGraph->DumpAsHTMLText();

		if ((count($oGraph->_GetNodes()) != 2) || (count($oGraph->_GetEdges()) != 1))
		{
			throw new Exception('The graph should be made of 2 nodes and 1 edge');
		}

		echo "<h5>Removing B</h5>";
		$oGraph->FilterNode($b);
		unset($b);
		echo $oGraph->DumpAsHtmlImage();
		echo $oGraph->DumpAsHTMLText();

		if ((count($oGraph->_GetNodes()) != 1) || (count($oGraph->_GetEdges()) != 0))
		{
			throw new Exception('The graph should contain only the node A');
		}
	}
}

class WhereIsThe61TablesThreat extends TestBizModel
{
	static public function GetName()
	{
		return '61 tables';
	}

	static public function GetDescription()
	{
		return 'Evaluate where is the 61 tables limit threat';
	}

	protected function DoExecute()
	{
		$aClassToCount_Full = array();
		$aDistribution = array();
		$iTotalClasses = 0;
		foreach (MetaModel::GetClasses() as $sClass)
		{
			if (MetaModel::IsAbstract($sClass)) continue;

			$iTotalClasses++;
			$oSearch = DBSearch::FromOQL("SELECT $sClass WHERE id = 1");
			$oSql = $oSearch->GetSQLQueryStructure(array(), false, null);
			$iCount = $oSql->CountTables();
			$aClassToCount_Full[$sClass] = $iCount;
			if (array_key_exists($iCount, $aDistribution))
			{
				$aDistribution[$iCount]++;
			}
			else
			{
				$aDistribution[$iCount] = 1;
			}
		}
		arsort($aClassToCount_Full);

		$iHighestCount = max($aClassToCount_Full);
		for($i = 1; $i < $iHighestCount ; $i++)
		{
			if (!array_key_exists($i, $aDistribution))
			{
				$aDistribution[$i] = 0;
			}
		}
		ksort($aDistribution);


		$i = 0;
		$iLimit = 15;
		$iCountThreshold = 10;
		echo "<h5>TOP $iLimit offenders (+ those exceeding $iCountThreshold tables)</h5>";
		foreach ($aClassToCount_Full as $sClass => $iCountFull)
		{
			$i++;
			if (($iCountFull <= $iCountThreshold) && ($i >= $iLimit)) break;

			echo "$sClass: $iCountFull tables<br/>";
		}

		echo "<h5>Distribution of table counts</h5>";
		echo "<p>Over a total of $iTotalClasses instantiable classes.</p>";
		echo "<table>";
		echo "<tr><td>Table count</td><td>Classes</td></tr>";
		foreach ($aDistribution as $iTableCount => $iClassCount)
		{
			echo "<tr><td>$iTableCount</td><td>$iClassCount</td></tr>";
		}
		echo "</table>";
	}
}

class TestBug689 extends TestBizModel
{
	static public function GetName()
	{
		return 'An OQL failing to export in XML';
	}

	static public function GetDescription()
	{
		return '(N.689) Reaching the limit of 61 tables';
	}

	protected function DoExecute()
	{
		$sOql = 'SELECT child, parent, s1, p, o FROM UserRequest AS child JOIN UserRequest AS parent ON child.parent_request_id = parent.id JOIN lnkFunctionalCIToTicket AS l1 ON l1.ticket_id = child.id JOIN Server AS s1 ON l1.functionalci_id = s1.id JOIN Person AS p ON child.caller_id = p.id JOIN Organization AS o ON p.org_id = o.id';
		$oSearch = DBSearch::FromOQL($sOql);
		$oSql = $oSearch->GetSQLQueryStructure(array(), false, null);
		//$sSql = $oSql->RenderSelect();

		echo '<p>'.$sOql.'</p>';
		echo '<p>This query rendered with all columns give a MySQL query having <b>'.$oSql->CountTables().'</b> tables... let\'s try it with the DBObjectSet API:</p>';
		$oSet = new DBObjectSet($oSearch);
		$oObj = $oSet->Fetch();
		echo '<p>Well done, this is working fine! Some magic happened in the background!</p>';
	}
}

class TestDBObjectLinkedObjects extends TestBizModel
{
	static public function GetName()
	{
		return 'DBObject Linked objects API';
	}

	static public function GetDescription()
	{
		return 'Add/Remove/Modify linked objects (recorded as a delta within DBObject, later recorded in DB)';
	}

	protected function DoExecute()
	{
		CMDBSource::Query('START TRANSACTION');
		//CMDBSource::Query('ROLLBACK'); automatique !

		////////////////////////////////////////////////////////////////////////////////
		// Set the stage
		//

		$oTypes = new DBObjectSet(DBObjectSearch::FromOQL('SELECT NetworkDeviceType WHERE name = "Router"'));
		$oType = $oTypes->fetch();

		$oDevice1 = MetaModel::NewObject('NetworkDevice');
		$oDevice1->Set('name', 'test device 1');
		$oDevice1->Set('org_id', 3);
		$oDevice1->Set('networkdevicetype_id', $oType->GetKey());
		$oDevice1->DBInsert();
		$iDev1 = $oDevice1->GetKey();

		$oDevice2 = MetaModel::NewObject('NetworkDevice');
		$oDevice2->Set('name', 'test device 2');
		$oDevice2->Set('org_id', 3);
		$oDevice2->Set('networkdevicetype_id', $oType->GetKey());
		$oDevice2->DBInsert();
		$iDev2 = $oDevice2->GetKey();

		$oServer = MetaModel::NewObject('Server');
		$oServer->Set('name', 'unit test linkset');
		$oServer->Set('org_id', 3);
		$oLinkSet = $oServer->Get('networkdevice_list');
		$oLinkSet->AddItem(MetaModel::NewObject('lnkConnectableCIToNetworkDevice', array('networkdevice_id' => $iDev1)));
		$oServer->Set('networkdevice_list', $oLinkSet);
		assert($oServer->IsModified(), 'Server is modified');
		$oServer->DBInsert();
		$iServer = $oServer->GetKey();

		$oServer = MetaModel::GetObject('Server', $iServer);
		$oLinkSet = $oServer->Get('networkdevice_list');
		assert($oLinkSet->Count() == 1, 'One NW Dev attached');
		$oLink = $oLinkSet->Fetch();
		assert($oLink->Get('networkdevice_id') == $iDev1, 'New device correctly attached');

		$oLinkSet = $oServer->Get('networkdevice_list');
		$oLinkSet->AddItem(MetaModel::NewObject('lnkConnectableCIToNetworkDevice', array('networkdevice_id' => $iDev2)));
		$oServer->Set('networkdevice_list', $oLinkSet);
		assert($oServer->IsModified(), 'Server is modified');
		$oServer->DBUpdate();

		$oServer = MetaModel::GetObject('Server', $iServer);
		$oLinkSet = $oServer->Get('networkdevice_list');
		assert($oLinkSet->Count() == 2, 'Two NW Dev attached');
		$oNewLinkSet = clone $oLinkSet;
		while ($oLink = $oLinkSet->Fetch())
		{
			$iLinkId = $oLink->Get('networkdevice_id');
			if ($iLinkId == $iDev1)
			{
				$oNewLinkSet->RemoveItem($oLink->GetKey());
			}
			elseif ($iLinkId == $iDev2)
			{
				$oLink->Set('network_port', 'lePortSalut');
				$oNewLinkSet->ModifyItem($oLink);
			}
		}
		$oServer->Set('networkdevice_list', $oNewLinkSet);
		assert($oServer->IsModified(), 'Server is modified');
		$oServer->DBUpdate();

		$oServer = MetaModel::GetObject('Server', $iServer);
		$oLinkSet = $oServer->Get('networkdevice_list');
		assert($oLinkSet->Count() == 1, 'One NW Dev attached');
		$oLink = $oLinkSet->Fetch();
		assert($oLink->Get('networkdevice_id') == $iDev2, 'Dev2 remained attached');
		assert($oLink->Get('network_port') == 'lePortSalut', 'Port has been changed');
	}
}

class TestDBObjectLinkedObjectsLegacy extends TestBizModel
{
	static public function GetName()
	{
		return 'DBObject Linked objects API (legacy usage)';
	}

	static public function GetDescription()
	{
		return 'Alter a link set by redefining the whole list of links (not recommended!)';
	}

	protected function DoExecute()
	{
		CMDBSource::Query('START TRANSACTION');
		//CMDBSource::Query('ROLLBACK'); automatique !

		////////////////////////////////////////////////////////////////////////////////
		// Set the stage
		//

		$oTypes = new DBObjectSet(DBObjectSearch::FromOQL('SELECT NetworkDeviceType WHERE name = "Router"'));
		$oType = $oTypes->fetch();

		$oDevice1 = MetaModel::NewObject('NetworkDevice');
		$oDevice1->Set('name', 'test device 1');
		$oDevice1->Set('org_id', 3);
		$oDevice1->Set('networkdevicetype_id', $oType->GetKey());
		$oDevice1->DBInsert();
		$iDev1 = $oDevice1->GetKey();

		$oDevice2 = MetaModel::NewObject('NetworkDevice');
		$oDevice2->Set('name', 'test device 2');
		$oDevice2->Set('org_id', 3);
		$oDevice2->Set('networkdevicetype_id', $oType->GetKey());
		$oDevice2->DBInsert();
		$iDev2 = $oDevice2->GetKey();

		$oServer = MetaModel::NewObject('Server');
		$oServer->Set('name', 'unit test linkset');
		$oServer->Set('org_id', 3);
		$oLinkSet = $oServer->Get('networkdevice_list');
		$oNewLinkSet = DBObjectSet::FromScratch('lnkConnectableCIToNetworkDevice');
		while ($oLink = $oLinkSet->Fetch())
		{
			$oNewLinkSet->AddObject($oLink);
		}
		$oNewLinkSet->AddObject(MetaModel::NewObject('lnkConnectableCIToNetworkDevice', array('networkdevice_id' => $iDev1)));
		$oServer->Set('networkdevice_list', $oNewLinkSet);
		assert($oServer->IsModified(), 'Server is modified');
		$oServer->DBInsert();
		$iServer = $oServer->GetKey();

		$oServer = MetaModel::GetObject('Server', $iServer);
		$oLinkSet = $oServer->Get('networkdevice_list');
		assert($oLinkSet->Count() == 1, 'One NW Dev attached');
		$oLink = $oLinkSet->Fetch();
		assert($oLink->Get('networkdevice_id') == $iDev1, 'New device correctly attached');

		$oNewLinkSet = DBObjectSet::FromScratch('lnkConnectableCIToNetworkDevice');
		$oLinkSet->Rewind();
		while ($oLink = $oLinkSet->Fetch())
		{
			$oNewLinkSet->AddObject($oLink);
		}
		$oNewLinkSet->AddObject(MetaModel::NewObject('lnkConnectableCIToNetworkDevice', array('networkdevice_id' => $iDev2)));
		$oServer->Set('networkdevice_list', $oNewLinkSet);
		assert($oServer->IsModified(), 'Server is modified');
		$oServer->DBUpdate();

		$oServer = MetaModel::GetObject('Server', $iServer);
		$oLinkSet = $oServer->Get('networkdevice_list');
		assert($oLinkSet->Count() == 2, 'Two NW Dev attached');
		$oNewLinkSet = DBObjectSet::FromScratch('lnkConnectableCIToNetworkDevice');
		$oServer->Set('networkdevice_list', $oNewLinkSet);
		while ($oLink = $oLinkSet->Fetch())
		{
			$iLinkId = $oLink->Get('networkdevice_id');
			if ($iLinkId == $iDev1)
			{
				// Remove...ie do not add it!
			}
			elseif ($iLinkId == $iDev2)
			{
				$oLink->Set('network_port', 'lePortSalut');
				$oNewLinkSet->AddObject($oLink);
			}
			else
			{
				$oNewLinkSet->AddObject($oLink);
			}
		}
		$oServer->Set('networkdevice_list', $oNewLinkSet);
		assert($oServer->IsModified(), 'Server is modified');
		$oServer->DBUpdate();

		$oServer = MetaModel::GetObject('Server', $iServer);
		$oLinkSet = $oServer->Get('networkdevice_list');
		assert($oLinkSet->Count() == 1, 'One NW Dev attached');
		$oLink = $oLinkSet->Fetch();
		assert($oLink->Get('networkdevice_id') == $iDev2, 'Dev2 remained attached');
		assert($oLink->Get('network_port') == 'lePortSalut', 'Port has been changed');
	}
}
