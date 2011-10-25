<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * Core test list
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */


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
		$oQuery = new SQLQuery(
			$sTable = 'myTable',
			$sTableAlias = 'myTableAlias',
			$aFields = array('column1'=>new FieldExpression('column1', 'myTableAlias'), 'column2'=>new FieldExpression('column2', 'myTableAlias')),
//			$aFullTextNeedles = array('column1'),
			$bToDelete = false,
			$aValues = array()
		);
		$oQuery->AddCondition(Expression::FromOQL('DATE(NOW() - 1200 * 2) > \'2008-07-31\''));

		$oSubQuery1 = new SQLQuery(
			$sTable = 'myTable1',
			$sTableAlias = 'myTable1Alias',
			$aFields = array('column1_1'=>new FieldExpression('column1', 'myTableAlias'), 'column1_2'=>new FieldExpression('column1', 'myTableAlias')),
//			$aFullTextNeedles = array(),
			$bToDelete = false,
			$aValues = array()
		);

		$oSubQuery2 = new SQLQuery(
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

class TestOQLParser extends TestFunction
{
	static public function GetName() {return 'Check OQL parsing';}
	static public function GetDescription() {return 'Attempts a series of queries, and in particular those with a bad syntax';}

	protected function CheckQuery($sQuery, $bIsCorrectQuery)
	{
		$oOql = new OqlInterpreter($sQuery);
		try
		{
			$oTrash = $oOql->Parse(); // Not expecting a given format, otherwise use ParseExpression/ParseObjectQuery/ParseValueSetQuery
			self::DumpVariable($oTrash);
		}
		catch (OQLException $OqlException)
		{
			if ($bIsCorrectQuery)
			{
				echo "<p>More info on this unexpected failure:<br/>".$OqlException->getHtmlDesc()."</p>\n";
				throw $OqlException;
				return false;
			}
			else
			{
				// Everything is fine :-)
				echo "<p>More info on this expected failure:<br/>".$OqlException->getHtmlDesc()."</p>\n";
				return true;
			}
		}
		// The query was correctly parsed, was it expected to be correct ?
		if ($bIsCorrectQuery)
		{
			return true;
		}
		else
		{
			throw new UnitTestException("The query '$sQuery' was parsed with success, while it shouldn't (?)");
			return false;
		}
	}

	protected function TestQuery($sQuery, $bIsCorrectQuery)
	{
		if (!$this->CheckQuery($sQuery, $bIsCorrectQuery))
		{
			return false;
		}
		return true;
	}

	public function DoExecute()
	{
		$aQueries = array(
			'SELECT toto' => true,
			'SELECT toto WHERE toto.a = 1' => true,
			'SELECT toto WHERE toto.a = :myparameter' => true,
			'SELECT toto WHERE toto.a IN (:param1)' => true,
			'SELECT toto WHERE toto.a IN (:param1, :param2)' => true,
			'SELECT toto WHERE toto.a=1' => true,
			'SELECT toto WHERE toto.a = "1"' => true,
			'SELECT toto WHHHERE toto.a = "1"' => false,
			'SELECT toto WHERE toto.a == "1"' => false,
			'SELECT toto WHERE toto.a % 1' => false,
			//'SELECT toto WHERE toto.a LIKE 1' => false,
			'SELECT toto WHERE toto.a like \'arg\'' => false,
			'SELECT toto WHERE toto.a NOT LIKE "That\'s it"' => true,
			'SELECT toto WHERE toto.a NOT LIKE "That\'s "it""' => false,
			'SELECT toto WHERE toto.a NOT LIKE "That\'s \\"it\\""' => true,
			'SELECT toto WHERE toto.a NOT LIKE \'That"s it\'' => true,
			'SELECT toto WHERE toto.a NOT LIKE \'That\'s it\'' => false,
			'SELECT toto WHERE toto.a NOT LIKE \'That\\\'s it\'' => true,
			'SELECT toto WHERE toto.a NOT LIKE "blah \\ truc"' => false,
			'SELECT toto WHERE toto.a NOT LIKE "blah \\\\ truc"' => true,
			'SELECT toto WHERE toto.a NOT LIKE \'blah \\ truc\'' => false,
			'SELECT toto WHERE toto.a NOT LIKE \'blah \\\\ truc\'' => true,

			'SELECT toto WHERE toto.a NOT LIKE "\\\\"' => true,
			'SELECT toto WHERE toto.a NOT LIKE "\\""' => true,
			'SELECT toto WHERE toto.a NOT LIKE "\\"\\\\"' => true,
			'SELECT toto WHERE toto.a NOT LIKE "\\\\\\""' => true,
			'SELECT toto WHERE toto.a NOT LIKE ""' => true,
			'SELECT toto WHERE toto.a NOT LIKE "\\\\"' => true,
			"SELECT UserRightsMatrixClassGrant WHERE UserRightsMatrixClassGrant.class = 'lnkContactRealObject' AND UserRightsMatrixClassGrant.action = 'modify' AND UserRightsMatrixClassGrant.login = 'Denis'" => true,
			"SELECT A WHERE A.col1 = 'lit1' AND A.col2 = 'lit2' AND A.col3 = 'lit3'" => true,

			'SELECT toto WHERE toto.a NOT LIKE "blah" AND toto.b LIKE "foo"' => true,

			//'SELECT toto WHERE toto.a > \'asd\'' => false,
			'SELECT toto WHERE toto.a = 1 AND toto.b LIKE "x" AND toto.f >= 12345' => true,
			'SELECT Device JOIN Site ON Device.site = Site.id' => true,
			'SELECT Device JOIN Site ON Device.site = Site.id JOIN Country ON Site.location = Country.id' => true,

			"SELECT A JOIN B ON A.myB = B.id WHERE (A.col1 = 123 AND B.col1 = 'aa') OR (A.col3 = 'zzz' AND B.col4 > 100)" => true,
			"SELECT A JOIN B ON A.myB = B.id WHERE (A.col1 = B.col2 AND B.col1 = A.col2) OR (A.col3 = '' AND B.col4 > 100)" => true,
			"SELECT A JOIN B ON A.myB = B.id WHERE A.col1 + B.col2 * B.col1 = A.col2" => true,
			"SELECT A JOIN B ON A.myB = B.id WHERE A.col1 + (B.col2 * B.col1) = A.col2" => true,
			"SELECT A JOIN B ON A.myB = B.id WHERE (A.col1 + B.col2) * B.col1 = A.col2" => true,

			'SELECT Device AS D_ JOIN Site AS S_ ON D_.site = S_.id WHERE S_.country = "Francia"' => true,

			// Several objects in a row...
			//
			'SELECT A FROM A' => true,
			'SELECT A JOIN B ON A.myB = B.id WHERE A.col1 = 2' => true,
			'SELECT A FROM A JOIN B ON A.myB = B.id WHERE A.col1 = 2' => true,
			'SELECT B FROM A JOIN B ON A.myB = B.id WHERE A.col1 = 2' => true,
			'SELECT A,B FROM A JOIN B ON A.myB = B.id WHERE A.col1 = 2' => true,
			'SELECT A, B FROM A JOIN B ON A.myB = B.id WHERE A.col1 = 2' => true,
			'SELECT B,A FROM A JOIN B ON A.myB = B.id WHERE A.col1 = 2' => true,
			'SELECT  A, B,C FROM A JOIN B ON A.myB = B.id' => false,
			'SELECT C FROM A JOIN B ON A.myB = B.id WHERE A.col1 = 2' => false,
			'SELECT A JOIN B ON A.myB BELOW B.id WHERE A.col1 = 2' => true,
			'SELECT A JOIN B ON A.myB = B.id JOIN C ON C.parent_id BELOW B.id WHERE A.col1 = 2 AND B.id = 3' => true,
			'SELECT A JOIN B ON A.myB = B.id JOIN C ON C.parent_id BELOW STRICT B.id WHERE A.col1 = 2 AND B.id = 3' => true,
			'SELECT A JOIN B ON A.myB = B.id JOIN C ON C.parent_id NOT BELOW B.id WHERE A.col1 = 2 AND B.id = 3' => true,
			'SELECT A JOIN B ON A.myB = B.id JOIN C ON C.parent_id NOT BELOW STRICT B.id WHERE A.col1 = 2 AND B.id = 3' => true,
			'SELECT A JOIN B ON A.myB = B.id JOIN C ON C.parent_id = B.id WHERE A.col1 BELOW 2 AND B.id = 3' => false,
		);

		$iErrors = 0;

		foreach($aQueries as $sQuery => $bIsCorrectQuery)
		{
			$sIsOk = $bIsCorrectQuery ? 'good' : 'bad';
			echo "<h4>Testing query: $sQuery ($sIsOk)</h4>\n";
			try
			{
				$bRet = $this->TestQuery($sQuery, $bIsCorrectQuery);
			}
			catch(Exception $e)
			{
				$this->m_aErrors[] = "Exception: ".$e->getMessage();
				$bRet = false;
			}
			if (!$bRet) $iErrors++;
		}
		
		return ($iErrors == 0);
	}
}


class TestCSVParser extends TestFunction
{
	static public function GetName() {return 'Check CSV parsing';}
	static public function GetDescription() {return 'Loads a set of CSV data';}

	public function DoExecute()
	{
		$sDataFile = '?field1?;?field2?;?field3?
?a?;?b?;?c?
a;b;c
 ? a ? ; ? b ? ; ? c ? 
 a ; b ; c 
??;??;??
;;
?a"?;?b?;?c?
?a1
a2?;?b?;?c?
?a1,a2?;?b?;?c?
?a?;?b?;?c1,",c2
,c3?
?a?;?b?;?ouf !?
    Espace sur la fin ; 1234; e@taloc.com ';

		self::DumpVariable($sDataFile);

		$aExpectedResult = array(
			//array('field1', 'field2', 'field3'),
			array('a', 'b', 'c'),
			array('a', 'b', 'c'),
			array(' a ', ' b ', ' c '),
			array('a', 'b', 'c'),
			array('', '', ''),
			array('', '', ''),
			array('a"', 'b', 'c'),
			array("a1\na2", 'b', 'c'),
			array('a1,a2', 'b', 'c'),
			array('a', 'b', "c1,\",c2\n,c3"),
			array('a', 'b', 'ouf !'),
			array('Espace sur la fin', '1234', 'e@taloc.com'),
		);
	
		$oCSVParser = new CSVParser($sDataFile, ';', '?');
		$aData = $oCSVParser->ToArray(1, null, 0);

		$iIssues = 0;

		echo "<table border=\"1\">\n";
		foreach ($aData as $iRow => $aRow)
		{
			echo "<tr>\n";
			foreach ($aRow as $iCol => $sCell)
			{
				if (empty($sCell))
				{
					$sCellValue = '&nbsp;';
				}
				else
				{
					$sCellValue = htmlentities($sCell, ENT_QUOTES, 'UTF-8');
				}

				if (!isset($aExpectedResult[$iRow][$iCol]))
				{
					$iIssues++;
					$sCellValue = "<span style =\"color: red; background-color: grey;\">$sCellValue</span>";
				}
				elseif ($aExpectedResult[$iRow][$iCol] != $sCell)
				{
					$iIssues++;
					$sCellValue = "<span style =\"color: red; background-color: lightgrey;\">$sCellValue</span>, expecting '<span style =\"color: green; background-color: lightgrey;\">".$aExpectedResult[$iRow][$iCol]."</span>'";
				}

				echo "<td><pre>$sCellValue</pre></td>";
			}
			echo "</tr>\n";
		}
		echo "</table>\n";
		return ($iIssues > 0);
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
	
		self::DumpVariable(MetaModel::EnumLinkingClasses());
		self::DumpVariable(MetaModel::EnumLinkingClasses("cmdbContact"));
		self::DumpVariable(MetaModel::EnumLinkingClasses("cmdWorkshop"));
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
		$team = MetaModel::GetObject("cmdbTeam", "2");
		$team->Set("headcount", rand(1,1000));
		$team->Set("email", "Luis ".rand(9,250));
		self::DumpVariable($team->ListChanges());
		echo "New headcount = {$team->Get("headcount")}</br>\n";
		echo "Computed name = {$team->Get("name")}</br>\n";
	
		$oMyChange = MetaModel::NewObject("CMDBChange");
		$oMyChange->Set("date", time());
		$oMyChange->Set("userinfo", "test_setattribute / Made by robot #".rand(1,100));
		$iChangeId = $oMyChange->DBInsert();
	
		//MetaModel::StartDebugQuery();
		$team->DBUpdateTracked($oMyChange);
		//MetaModel::StopDebugQuery();
	
		echo "<h4>Check the modified team</h4>";
		$oTeam = MetaModel::GetObject("cmdbTeam", "2");
		self::DumpVariable($oTeam);
	}
	function test_newobject()
	{
		$oMyChange = MetaModel::NewObject("CMDBChange");
		$oMyChange->Set("date", time());
		$oMyChange->Set("userinfo", "test_newobject / Made by robot #".rand(1,100));
		$iChangeId = $oMyChange->DBInsert();
	
		echo "<h4>Create a new object (team)</h4>";
		$oNewTeam = MetaModel::NewObject("cmdbTeam");
		$oNewTeam->Set("name", "ekip2choc #".rand(1000, 2000));
		$oNewTeam->Set("email", "machin".rand(1,100)."@tnut.com");
		$oNewTeam->Set("email", null);
		$oNewTeam->Set("owner", "ITOP");
		$oNewTeam->Set("headcount", "0".rand(38000, 38999)); // should be reset to an int value
		$iId = $oNewTeam->DBInsertTracked($oMyChange);
		echo "Created new team: $iId</br>";
		echo "<h4>Delete team #$iId</h4>";
		$oTeam = MetaModel::GetObject("cmdbTeam", $iId);
		$oTeam->DBDeleteTracked($oMyChange);
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
		echo "<h4>Create a change</h4>";
		$oMyChange = MetaModel::NewObject("CMDBChange");
		$oMyChange->Set("date", time());
		$oMyChange->Set("userinfo", "Made by robot #".rand(1,100));
		$iChangeId = $oMyChange->DBInsert();
		echo "Created new change: $iChangeId</br>";
		self::DumpVariable($oMyChange);
	
		echo "<h4>Create a new object (team)</h4>";
		$oNewTeam = MetaModel::NewObject("cmdbTeam");
		$oNewTeam->Set("name", "ekip2choc #".rand(1000, 2000));
		$oNewTeam->Set("email", "machin".rand(1,100)."@tnut.com");
		$oNewTeam->Set("email", null);
		$oNewTeam->Set("owner", "ITOP");
		$oNewTeam->Set("headcount", "0".rand(38000, 38999)); // should be reset to an int value
		$iId = $oNewTeam->DBInsertTracked($oMyChange);
		echo "Created new team: $iId</br>";
		echo "<h4>Delete team #$iId</h4>";
		$oTeam = MetaModel::GetObject("cmdbTeam", $iId);
		$oTeam->DBDeleteTracked($oMyChange);
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
		$oMerge->Merge($oSet2);
		$oMerge->Merge($oSet2);
	
		echo "Set1 - Found ".$oSet1->Count()." items.</br>\n";
		echo "Set2 - Found ".$oSet2->Count()." items.</br>\n";
		echo "Intersect - Found ".$oIntersect->Count()." items.</br>\n";
		echo "Delta - Found ".$oDelta->Count()." items.</br>\n";
		echo "Merge - Found ".$oMerge->Count()." items.</br>\n";
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
		$aRels = $oObj->GetRelatedObjects("Potes", $iMaxDepth);
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
	
		$oMyChange = MetaModel::NewObject("CMDBChange");
		$oMyChange->Set("date", time());
		$oMyChange->Set("userinfo", "test_linkedset / Made by robot #".rand(1,100));
		$iChangeId = $oMyChange->DBInsert();
		$oObj->DBUpdateTracked($oMyChange);
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
// Test a complex biz model on the fly
///////////////////////////////////////////////////////////////////////////

abstract class MyFarm extends TestBizModel
{
	static public function GetConfigFile() {return '/config-test-farm.php';}

	protected function DoPrepare()
	{
		parent::DoPrepare();
		$this->ResetDB();
		MetaModel::DBCheckIntegrity();
	}

	protected function InsertMammal($sSpecies, $sSex, $iSpeed, $iMotherid, $iFatherId, $sName, $iHeight, $sBirth)
	{
		$oNew = MetaModel::NewObject('Mammal');
		$oNew->Set('species', $sSpecies);
		$oNew->Set('sex', $sSex);
		$oNew->Set('speed', $iSpeed);
		$oNew->Set('mother', $iMotherid);
		$oNew->Set('father', $iFatherId);
		$oNew->Set('name', $sName);
		$oNew->Set('height', $iHeight);
		$oNew->Set('birth', $sBirth);
		return $this->ObjectToDB($oNew);
	}

	protected function InsertBird($sSpecies, $sSex, $iSpeed, $iMotherid, $iFatherId)
	{
		$oNew = MetaModel::NewObject('Bird');
		$oNew->Set('species', $sSpecies);
		$oNew->Set('sex', $sSex);
		$oNew->Set('speed', $iSpeed);
		$oNew->Set('mother', $iMotherid);
		$oNew->Set('father', $iFatherId);
		return $this->ObjectToDB($oNew);
	}

	protected function InsertFlyingBird($sSpecies, $sSex, $iSpeed, $iMotherid, $iFatherId, $iFlyingSpeed)
	{
		$oNew = MetaModel::NewObject('FlyingBird');
		$oNew->Set('species', $sSpecies);
		$oNew->Set('sex', $sSex);
		$oNew->Set('speed', $iSpeed);
		$oNew->Set('mother', $iMotherid);
		$oNew->Set('father', $iFatherId);
		$oNew->Set('flyingspeed', $iFlyingSpeed);
		return $this->ObjectToDB($oNew);
	}

	private function InsertGroup($sName, $iLeaderId)
	{
		$oNew = MetaModel::NewObject('Group');
		$oNew->Set('name', $sName);
		$oNew->Set('leader', $iLeaderId);
		$iId = $oNew->DBInsertNoReload();
		return $iId;
	}
}


class TestQueriesOnFarm extends MyFarm
{
	static public function GetName()
	{
		return 'Farm test';
	}

	static public function GetDescription()
	{
		return 'A series of tests on the farm business model (SQL generation)';
	}

	protected function CheckQuery($sQuery, $bIsCorrectQuery)
	{
		if ($bIsCorrectQuery)
		{
			echo "<h4 style=\"color:green;\">$sQuery</h4>\n";
		}
		else
		{
			echo "<h4 style=\"color:red;\">$sQuery</h3>\n";
		}
		try
		{
			//$oOql = new OqlInterpreter($sQuery);
			//$oTrash = $oOql->ParseObjectQuery();
			//self::DumpVariable($oTrash, true);
			$oMyFilter = DBObjectSearch::FromOQL($sQuery);
		}
		catch (OQLException $oOqlException)
		{
			if ($bIsCorrectQuery)
			{
				echo "<p>More info on this unexpected failure:<br/>".$oOqlException->getHtmlDesc()."</p>\n";
				throw $oOqlException;
				return false;
			}
			else
			{
				// Everything is fine :-)
				echo "<p>More info on this expected failure:\n";
				echo "<ul>\n";
				echo "<li>".get_class($oOqlException)."</li>\n";
				echo "<li>".$oOqlException->getMessage()."</li>\n";
				echo "<li>".$oOqlException->getHtmlDesc()."</li>\n";
				echo "</ul>\n";
				echo "</p>\n";
				return true;
			}
		}
		// The query was correctly parsed, was it expected to be correct ?
		if (!$bIsCorrectQuery)
		{
			throw new UnitTestException("The query '$sQuery' was parsed with success, while it shouldn't (?)");
			return false;
		}
		echo "<p>To OQL: ".$oMyFilter->ToOQL()."</p>";

		$this->search_and_show_list($oMyFilter);
		
		//echo "<p>first pass<p>\n";
		//self::DumpVariable($oMyFilter, true);
		$sQuery1 = MetaModel::MakeSelectQuery($oMyFilter);
		//echo "<p>second pass<p>\n";
		//self::DumpVariable($oMyFilter, true);
		//$sQuery1 = MetaModel::MakeSelectQuery($oMyFilter);
		
		$sSerialize = $oMyFilter->serialize();
		echo "<p>Serialized:$sSerialize</p>\n";
		$oFilter2 = DBObjectSearch::unserialize($sSerialize);
		try
		{
			$sQuery2 = MetaModel::MakeSelectQuery($oFilter2);
		}
		catch (Exception $e)
		{
			echo "<p>Could not compute the query after unserialize</p>\n";
			echo "<p>Query 1: $sQuery1</p>\n";
			MyHelpers::var_cmp_html($oMyFilter, $oFilter2);
			throw $e;
		}
		//if ($oFilter2 != $oMyFilter) no, they may differ while the resulting query is the same!
		if ($sQuery1 != $sQuery2)
		{
			echo "<p>serialize/unserialize mismatch :-(</p>\n";
			MyHelpers::var_cmp_html($sQuery1, $sQuery2);
			MyHelpers::var_cmp_html($oMyFilter, $oFilter2);
			return false;
		}
		return true;
	}

	protected function DoExecute()
	{
//			$this->ReportError("Found two different OQL expression out of the (same?) filter: <em>$sExpr1</em> != <em>$sExpr2</em>");
//			$this->ReportSuccess('Found '.$oSet->Count()." objects of class $sClassName");
		echo "<h3>Create protagonists...</h3>";

		$iId1 = $this->InsertMammal('human', 'male', 10, 0, 0, 'romanoff', 192, '1971-07-19');
		$iId2 = $this->InsertMammal('human', 'female', 9, 0, 0, 'rouanita', 165, '1983-01-23');
		$this->InsertMammal('human', 'female', 3, $iId2, $iId1, 'pomme', 169, '2008-02-23');
		$this->InsertMammal('pig', 'female', 3, 0, 0, 'grouinkette', 85, '2006-06-01');
		$this->InsertMammal('donkey', 'female', 3, 0, 0, 'muleta', 124, '2003-11-11');

		$this->InsertBird('rooster', 'male', 12, 0, 0);
		$this->InsertFlyingBird('pie', 'female', 11, 0, 0, 35);

		// Benchmarking
		//
		if (false)
		{
			define ('COUNT_BENCHMARK', 10);
			echo "<h3>Parsing a long query, ".COUNT_BENCHMARK." times</h3>";
			$sQuery = "SELECT Animal AS Child JOIN Mammal AS Dad ON Child.father = Dad.id JOIN Animal AS Mum ON Child.mother = Mum.id WHERE Dad.birth < DATE_SUB(CURRENT_DATE(), INTERVAL 10 YEAR) AND Dad.height * 2 <= ROUND(TO_DAYS(Dad.birth) / (3 + 1) * 5 - 3)";
	
			$fStart = MyHelpers::getmicrotime();
			for($i=0 ; $i < COUNT_BENCHMARK ; $i++)
			{
				$oMyFilter = DBObjectSearch::FromOQL($sQuery);
			}
			$fDuration = MyHelpers::getmicrotime() - $fStart;
			$fParsingDuration = $fDuration / COUNT_BENCHMARK;
			echo "<p>Mean time by op: $fParsingDuration</p>";
		}

		echo "<h3>Test queries...</h3>";

		$aQueries = array(
			'SELECT Animal' => true,
			'SELECT Animal WHERE Animal.pkey = 1' => false,
			'SELECT Animal WHERE Animal.id = 1' => true,
			'SELECT Aniiimal' => false,
			'SELECTe Animal' => false,
			'SELECT * FROM Animal' => false,
			'SELECT Animal AS zoo WHERE zoo.species = \'human\'' => true,
			'SELECT Animal AS zoo WHERE species = \'human\'' => true,
			'SELECT Animal AS zoo WHERE espece = \'human\'' => false,
			'SELECT Animal AS zoo WHERE zoo.species IN (\'human\', "pig")' => true,
			'SELECT Animal AS zoo WHERE CONCATENATION(zoo.species, zoo.sex) LIKE "hum%male"' => false,
			'SELECT Animal AS zoo WHERE CONCAT(zoo.species, zoo.sex) LIKE "hum%male"' => true,
			'SELECT Animal AS zoo WHERE zoo.species NOT IN (\'human\', "pig")' => true,
			'SELECT Animal AS zoo WHERE zoo.kind = \'human\'' => false,
			'SELECT Animal WHERE Animal.species = \'human\' AND Animal.sex = \'female\'' => true,
			'SELECT Mammal AS x WHERE (x.species = \'human\' AND x.name LIKE \'ro%\') OR (x.species = \'donkey\' AND x.name LIKE \'po%\')' => true,
			'SELECT Mammal AS x WHERE x.species = \'human\' AND x.name LIKE \'ro%\' OR x.species = \'donkey\' AND x.name LIKE \'po%\'' => true,
			'SELECT Mammal AS m WHERE MONTH(m.birth) = 7' => true,
			'SELECT Mammal AS m WHERE DAY(m.birth) = 19' => true,
			'SELECT Mammal AS m WHERE YEAR(m.birth) = 1971' => true,
			'SELECT Mammal AS m WHERE m.birth < DATE_SUB(CURRENT_DATE(), INTERVAL 10 YEAR)' => true,
			'SELECT Mammal AS m WHERE m.birth > DATE_SUB(NOW(), INTERVAL 2000 DAY)' => true,
			'SELECT Mammal AS m WHERE (TO_DAYS(NOW()) - TO_DAYS(m.birth)) > 2000' => true,
			'SELECT Mammal AS m WHERE m.name = IF(FLOOR(ROUND(m.height)) > 2, "pomme", "romain")' => true,
			'SELECT Mammal AS m WHERE (1 + 2' => false,
			'SELECT Mammal AS m WHERE (1 + 2 * 4 / 23) > 0' => true,
			'SELECT Mammal AS m WHERE (4 / 23 * 2 + 1) > 0' => true,
			'SELECT Mammal AS m WHERE 1/0' => true,
			'SELECT Mammal AS m WHERE MONTH(m.birth) = 7' => true,
			'SELECT Animal JOIN Group ON Group.leader = Animal.id' => true,
			'SELECT Group JOIN Animal ON Group.leader = Animal.id' => true,
			'SELECT Animal AS A JOIN Group AS G1 ON G1.leader = A.id' => true,
			'SELECT Animal AS A JOIN Group AS G ON FooClass.leader = A.id' => false,
			'SELECT Animal AS A JOIN Group AS G ON G.leader = FooClass.id' => false,
			'SELECT Animal AS A JOIN Group AS G ON G.masterchief = A.id' => false,
			'SELECT Animal AS A JOIN Group AS G ON A.id = G.leader' => false,
			'SELECT Animal AS A JOIN Group AS G ON G.leader = A.id WHERE A.sex=\'male\' OR G.qwerty = 123' => false,
			'SELECT Animal AS A JOIN Group AS G ON G.leader = A.id WHERE A.sex=\'male\' OR G.name LIKE "a%"' => true,
			'SELECT Animal AS A JOIN Group AS G ON G.leader = A.id WHERE A.id = 1' => true,
			'SELECT Animal AS A JOIN Group AS G ON G.leader = A.id WHERE id = 1' => false,
			'SELECT Animal AS A JOIN Group AS G ON A.member = G.id' => false,
			'SELECT Mammal AS M JOIN Group AS G ON M.member = G.id' => true,
			'SELECT Mammal AS M JOIN Group AS G ON A.member = G.id' => false,
			'SELECT Mammal AS myAlias JOIN Group AS myAlias ON myAlias.member = myAlias.id' => false,
			'SELECT Mammal AS Mammal JOIN Group AS Mammal ON Mammal.member = Mammal.id' => false,
			'SELECT Group AS G WHERE G.leader_name LIKE "%"' => true,
			'SELECT Group AS G WHERE G.leader_speed < 100000' => true,
			'SELECT Mammal AS M JOIN Group AS G ON M.member = G.id WHERE G.leader_name LIKE "%"' => true,
			'SELECT Mammal AS M JOIN Group AS G ON M.member = G.id WHERE G.leader_speed < 100000' => true,
			'SELECT Mammal AS Child JOIN Mammal AS Dad ON Child.father = Dad.id' => true,
			'SELECT Mammal AS Child JOIN Animal AS Dad ON Child.father = Dad.id' => true,
			'SELECT Animal AS Child JOIN Mammal AS Dad ON Child.father = Dad.id' => true,
			'SELECT Animal AS Child JOIN Animal AS Dad ON Child.father = Dad.id' => true,
			'SELECT Animal AS Dad JOIN Animal AS Child ON Child.father = Dad.id' => true,
			'SELECT Animal AS Child JOIN Animal AS Dad ON Child.father = Dad.id JOIN Animal AS Mum ON Child.mother = Mum.id' => true,
			'SELECT Animal AS Child JOIN Animal AS Dad ON Child.father = Dad.id JOIN Animal AS Mum ON Child.mother = Mum.id WHERE Dad.id = 1' => true,
			'SELECT Animal AS Child JOIN Animal AS Dad ON Child.father = Dad.id JOIN Animal AS Mum ON Child.mother = Mum.id WHERE Dad.name = \'romanoff\'' => false,
			'SELECT Animal AS Child JOIN Mammal AS Dad ON Child.father = Dad.id' => true,
			'SELECT Animal AS Child JOIN Mammal AS Dad ON Child.father = Dad.id JOIN Animal AS Mum ON Child.mother = Mum.id WHERE Dad.name = \'romanoff\' OR Mum.speed = 0' => true,
			'SELECT Animal AS Dad JOIN Animal AS Child ON Child.father = Dad.id JOIN Animal AS Mum ON Child.mother = Mum.id' => true,
			'SELECT Mammal AS Dad JOIN Mammal AS Child ON Child.father = Dad.id' => true,
			'SELECT Mammal AS Dad JOIN Mammal AS Child ON Child.father = Dad.id JOIN Mammal AS Mum ON Child.mother = Mum.id WHERE Dad.name = \'romanoff\' OR Mum.name=\'chloe\' OR Child.name=\'bizounours\'' => true,
			// Specifying multiple objects
			'SELECT Animal FROM Animal' => true,
			'SELECT yelele FROM Animal' => false,
			'SELECT Animal FROM Animal AS A' => false,
			'SELECT A FROM Animal AS A' => true,
		);
		//$aQueries = array(
		//	'SELECT Mammal AS M JOIN Group AS G ON M.member = G.id WHERE G.leader_name LIKE "%"' => true,
		//);
		foreach($aQueries as $sQuery => $bIsCorrect)
		{
			$this->CheckQuery($sQuery, $bIsCorrect);
		}
		return true;
	}
}


///////////////////////////////////////////////////////////////////////////
// Test data load
///////////////////////////////////////////////////////////////////////////

class TestBulkChangeOnFarm extends TestBizModel
{
	static public function GetName()
	{
		return 'Farm test - data load';
	}

	static public function GetDescription()
	{
		return 'Bulk load';
	}
	
	static public function GetConfigFile() {return '/config-test-farm.php';}

	protected function DoPrepare()
	{
		parent::DoPrepare();
		$this->ResetDB();
		MetaModel::DBCheckIntegrity();
	}

	protected function DoExecute()
	{
//			$this->ReportError("Found two different OQL expression out of the (same?) filter: <em>$sExpr1</em> != <em>$sExpr2</em>");
//			$this->ReportSuccess('Found '.$oSet->Count()." objects of class $sClassName");

		$oParser = new CSVParser("denomination,hauteur,age
		suzy,123,2009-01-01
		chita,456,
		", ',', '"');
		$aData = $oParser->ToArray(1, array('_name', '_height', '_birth'));
		self::DumpVariable($aData);

		$oBulk = new BulkChange(
			'Mammal',
			$aData,
			// attributes
			array('name' => '_name', 'height' => '_height', 'birth' => '_birth'),
			// ext keys
			array(),
			// reconciliation
			array('name')
		);

		$oMyChange = MetaModel::NewObject("CMDBChange");
		$oMyChange->Set("date", time());
		$oMyChange->Set("userinfo", "Testor");
		$iChangeId = $oMyChange->DBInsert();
//		echo "Created new change: $iChangeId</br>";

		echo "<h3>Planned for loading...</h3>";
		$aRes = $oBulk->Process();
		self::DumpVariable($aRes);
		echo "<h3>Go for loading...</h3>";
		$aRes = $oBulk->Process($oMyChange);
		self::DumpVariable($aRes);

		return;

		$oRawData = array(
			'Mammal',
			array('species', 'sex', 'speed', 'mother', 'father', 'name', 'height', 'birth'),
			"human,male,23,0,0,romulus,192,1971
			human,male,23,0,0,remus,154,-50
			human,male,23,0,0,julius,160,-49
			human,female,23,0,0,cleopatra,142,-50
			pig,female,23,0,0,confucius,50,2003"
		);
	}
}


///////////////////////////////////////////////////////////////////////////
// Test data load
///////////////////////////////////////////////////////////////////////////

class TestFullTextSearchOnFarm extends MyFarm
{
	static public function GetName()
	{
		return 'Farm test - full text search';
	}

	static public function GetDescription()
	{
		return 'Focus on the full text search feature';
	}
	
	protected function DoExecute()
	{
		echo "<h3>Create protagonists...</h3>";

		$iId1 = $this->InsertMammal('human', 'male', 10, 0, 0, 'romanoff', 192, '1971-07-19');
		$iId2 = $this->InsertMammal('human', 'female', 9, 0, 0, 'rouanita', 165, '1983-01-23');
		$this->InsertMammal('human', 'female', 3, $iId2, $iId1, 'pomme', 169, '2008-02-23');
		$this->InsertMammal('pig', 'female', 3, 0, 0, 'grouinkette', 85, '2006-06-01');
		$this->InsertMammal('donkey', 'female', 3, 0, 0, 'muleta', 124, '2003-11-11');

		$this->InsertBird('rooster', 'male', 12, 0, 0);
		$this->InsertFlyingBird('pie', 'female', 11, 0, 0, 35);

		echo "<h3>Search...</h3>";
		$oSearch = new DBObjectSearch('Mammal');
		$oSearch->AddCondition_FullText('manof');
		//$oResultSet = new DBObjectSet($oSearch);
		$this->search_and_show_list($oSearch);
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

	static public function GetConfigFile() {return '/config-itop.php';}

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
			$sSQL = MetaModel::MakeSelectQuery($oFilter);
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

		return array(
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
			'SELECT Incident JOIN Person ON Incident.agent_id = Person.id WHERE Person.id = 5',
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

	static public function GetConfigFile() {return '/config-itop.php';}

	protected function DoBenchmark($sOqlQuery)
	{
		echo "<h5>Testing query: $sOqlQuery</h5>";

		$fStart = MyHelpers::getmicrotime();
		$oFilter = DBObjectSearch::FromOQL($sOqlQuery);
		$fParsingDuration = MyHelpers::getmicrotime() - $fStart;

		$fStart = MyHelpers::getmicrotime();
		$sSQL = MetaModel::MakeSelectQuery($oFilter);
		$fBuildDuration = MyHelpers::getmicrotime() - $fStart;

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

      if (false)
      {
		echo "<ul style=\"font-size:smaller;\">\n";
		echo "<li>Parsing: $fParsingDuration</li>\n";
		echo "<li>Build: $fBuildDuration</li>\n";
		echo "<li>Query: $fQueryDuration</li>\n";
		echo "<li>Fetch: $fFetchDuration</li>\n";
		echo "<li>ToOql: $fToOqlDuration</li>\n";
		echo "</ul>\n";
		}

		// Everything but the ToOQL (wich is interesting, anyhow)
		$fTotal = $fParsingDuration + $fBuildDuration + $fQueryDuration + $fFetchDuration; 

		return array(
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
	
	protected function DoExecute()
	{
		$aQueries = array(
			'SELECT Person AS PP WHERE PP.friendlyname LIKE "%dali"',
			'SELECT Person AS PP WHERE PP.location_id_friendlyname LIKE "%ce ch%"',
			'SELECT Organization AS OO JOIN Person AS PP ON PP.org_id = OO.id',
			'SELECT lnkTeamToContact AS lnk JOIN Team AS T ON lnk.team_id = T.id',
			'SELECT lnkTeamToContact AS lnk JOIN Team AS T ON lnk.team_id = T.id JOIN Contact AS C ON lnk.contact_id = C.id',
			'SELECT Incident JOIN Person ON Incident.agent_id = Person.id WHERE Person.id = 5',
			// this one is failing...
			//'SELECT L, P FROM Person AS P JOIN Location AS L ON P.location_id = L.id',
		);
		foreach (MetaModel::GetClasses() as $sClass)
		{
			$aQueries[] = 'SELECT '.$sClass;
			$aQueries[] = 'SELECT '.$sClass.' AS zz';
			$aQueries[] = 'SELECT '.$sClass.' AS zz WHERE id = 1';
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

	static public function GetConfigFile() {return '/config-itop.php';}

	
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
// Test data exchange
///////////////////////////////////////////////////////////////////////////

class TestDataExchange extends TestBizModel
{
	static public function GetName()
	{
		return 'Data exchange';
	}

	static public function GetDescription()
	{
		return 'Test REST services: synchro_import and synchro_exec';
	}

	static public function GetConfigFile() {return '/config-itop.php';}

	protected function DoExecScenario($aSingleScenario)
	{
		echo "<div style=\"padding: 10;\">\n";
		echo "<h3 style=\"background-color: #ddddff; padding: 10;\">{$aSingleScenario['desc']}</h3>\n";

		$sClass = $aSingleScenario['target_class'];

		$aTargetData = $aSingleScenario['target_data'];
		$aSourceData = $aSingleScenario['source_data'];

		$aTargetAttributes = array_shift($aTargetData);
		$aSourceAttributes = array_shift($aSourceData);

		if (count($aSourceData) + 1 != count($aTargetData))
		{
			throw new Exception("Target data must contain exactly ".(count($aSourceData) + 1)." items, found ".count($aTargetData));
		}

		// Create the data source
		//
		$oDataSource = new SynchroDataSource();
		$oDataSource->Set('name', 'Test data sync '.time());
		$oDataSource->Set('description', 'unit test - created automatically');
		$oDataSource->Set('status', 'production');
		$oDataSource->Set('user_id', 0);
		$oDataSource->Set('scope_class', $sClass);
		$oDataSource->Set('scope_restriction', '');
		$oDataSource->Set('full_load_periodicity', $aSingleScenario['full_load_periodicity']);
		$oDataSource->Set('reconciliation_policy', $aSingleScenario['reconciliation_policy']);
		$oDataSource->Set('action_on_zero', $aSingleScenario['action_on_zero']);
		$oDataSource->Set('action_on_one', $aSingleScenario['action_on_one']);
		$oDataSource->Set('action_on_multiple', $aSingleScenario['action_on_multiple']);
		$oDataSource->Set('delete_policy', $aSingleScenario['delete_policy']);
		$oDataSource->Set('delete_policy_update', $aSingleScenario['delete_policy_update']);
		$oDataSource->Set('delete_policy_retention', $aSingleScenario['delete_policy_retention']);
		$iDataSourceId = $this->ObjectToDB($oDataSource, true /* reload */);

      $oAttributeSet = $oDataSource->Get('attribute_list');
      while ($oAttribute = $oAttributeSet->Fetch())
      {
      	if (array_key_exists($oAttribute->Get('attcode'), $aSingleScenario['attributes']))
      	{
      		$aAttribInfo = $aSingleScenario['attributes'][$oAttribute->Get('attcode')];
      		if (array_key_exists('reconciliation_attcode', $aAttribInfo))
      		{
					$oAttribute->Set('reconciliation_attcode', $aAttribInfo['reconciliation_attcode']);
				}
				$oAttribute->Set('update', $aAttribInfo['do_update']);
				$oAttribute->Set('reconcile', $aAttribInfo['do_reconcile']);
			}
      	else
      	{
				$oAttribute->Set('update', false);
				$oAttribute->Set('reconcile', false);
			}
			$this->UpdateObjectInDB($oAttribute);
		}

		// Prepare list of prefixes -> make sure objects are unique with regard to the reconciliation scheme
		$aPrefixes = array(); // attcode => prefix
		foreach($aSourceAttributes as $iDummy => $sAttCode)
		{
			$aPrefixes[$sAttCode] = ''; // init with something
		}
		foreach($aSingleScenario['attributes'] as $sAttCode => $aAttribInfo)
		{
			if (isset($aAttribInfo['automatic_prefix']) && $aAttribInfo['automatic_prefix'])
			{
				$aPrefixes[$sAttCode] = 'TEST_'.$iDataSourceId.'_';
			}
		}

		// List existing objects (to be ignored in the analysis
		//
		$oAllObjects = new DBObjectSet(new DBObjectSearch($sClass));
		$aExisting = $oAllObjects->ToArray(true);
		$sExistingIds = implode(', ', array_keys($aExisting));

		// Create the initial object list
		//
		$aInitialTarget = $aTargetData[0];
		foreach($aInitialTarget as $aObjFields)
		{
			$oNewTarget = MetaModel::NewObject($sClass);
			foreach($aTargetAttributes as $iAtt => $sAttCode)
			{
				$oNewTarget->Set($sAttCode, $aPrefixes[$sAttCode].$aObjFields[$iAtt]);
			}
			$this->ObjectToDB($oNewTarget);
		}

		foreach($aTargetData as $iRow => $aExpectedObjects)
		{
			// Check the status (while ignoring existing objects)
			//
			if (empty($sExistingIds))
			{
				$oObjects = new DBObjectSet(DBObjectSearch::FromOQL("SELECT $sClass"));
			}
			else
			{
				$oObjects = new DBObjectSet(DBObjectSearch::FromOQL("SELECT $sClass WHERE id NOT IN($sExistingIds)"));
			}
			$aFound = $oObjects->ToArray();
			$aErrors_Unexpected = array();
			foreach($aFound as $iObj => $oObj)
			{
				// Is this object in the expected objects list
				$bFoundMatch = false;
				foreach($aExpectedObjects as $iExp => $aValues)
				{
					$bDoesMatch = true;
					foreach($aTargetAttributes as $iCol => $sAttCode)
					{
						if ($oObj->Get($sAttCode) != $aPrefixes[$sAttCode].$aValues[$iCol])
						{
							$bDoesMatch = false;
							break;
						}
					}
					if ($bDoesMatch)
					{
						$bFoundMatch = true;
						unset($aExpectedObjects[$iExp]);
						break;
					}
				}
				if (!$bFoundMatch)
				{
					$aErrors_Unexpected[] = $oObj->GetKey();
				}
			}

  			// Display the current status
			//
			echo "<p>Status at step $iRow</p>\n";
			$aCurrentDataSet = array();
			foreach($aFound as $iObj => $oObj)
			{
				$aObjDesc = array(
					'Status' => (in_array($iObj, $aErrors_Unexpected) ? 'unexpected' : 'ok'),
					'Object' => $oObj->GetHyperLink()
				);
				foreach($aTargetAttributes as $iCol => $sAttCode)
				{
					$aObjDesc[$sAttCode] = $oObj->Get($sAttCode);
				}
				$aCurrentDataSet[] = $aObjDesc;
			}
			if (count($aExpectedObjects) > 0)
			{
				foreach($aExpectedObjects as $iExp => $aValues)
				{
					$aObjDesc = array(
						'Status' => 'missing',
						'Object' => 'n/a'
					);
					foreach($aTargetAttributes as $iCol => $sAttCode)
					{
						$aObjDesc[$sAttCode] = $aPrefixes[$sAttCode].$aValues[$iCol];
					}
					$aCurrentDataSet[] = $aObjDesc;
				}
			}
			echo MyHelpers::make_table_from_assoc_array($aCurrentDataSet);

			if ((count($aErrors_Unexpected) > 0) || (count($aExpectedObjects) > 0))
			{
				throw new UnitTestException("The current status in iTop does not match the expectations");
			}

			// If not on the final row, run a data exchange sequence
			//
			if (array_key_exists($iRow, $aSourceData))
			{
				$aToBeLoaded = $aSourceData[$iRow];

				$sCsvData = implode(';', $aSourceAttributes)."\n";
				foreach($aToBeLoaded as $aDataRow)
				{
					$aFinalData = array();
					foreach($aDataRow as $iCol => $value)
					{
						if (is_null($value))
						{
							$aFinalData[] = '<NULL>';
						}
						else
						{
							$sAttCode = $aSourceAttributes[$iCol];
							$aFinalData[] = $aPrefixes[$sAttCode].$value;
						}
					}
					$sCsvData .= implode(';', $aFinalData)."\n";
				}
				$aPostData = array('csvdata' => $sCsvData);
		
				$aImportArgs = array(
					'data_source_id' => $iDataSourceId,
					'separator' => ';',
					'simulate' => 0,
					'output' => 'details',
				);
		
				$aGetParams = array();
				$aGetParamReport = array();
				foreach($aImportArgs as $sArg => $sValue)
				{
					$aGetParams[] = $sArg.'='.urlencode($sValue);
					$aGetParamReport[] = $sArg.'='.$sValue;
				}
				$sGetParams = implode('&', $aGetParams);
				$sLogin = isset($aSingleScenario['login']) ? $aSingleScenario['login'] : 'admin';
				$sPassword = isset($aSingleScenario['password']) ? $aSingleScenario['password'] : 'admin';
		
				$sRes = self::DoPostRequestAuth('../synchro/synchro_import.php?'.$sGetParams, $aPostData, $sLogin, $sPassword);

				// Report the load results
				//
				if (strlen($sCsvData) > 5000)
				{
					$sCsvDataViewable = 'INPUT TOO LONG TO BE DISPLAYED ('.strlen($sCsvData).")\n".substr($sCsvData, 0, 500)."\n... TO BE CONTINUED";
				}
				else
				{
					$sCsvDataViewable = $sCsvData;
				}
				$sCsvDataViewable = htmlentities($sCsvDataViewable, ENT_QUOTES, 'UTF-8');
		
				echo "<div style=\"\">\n";
				echo "      <pre class=\"vardump\">$sCsvDataViewable</pre>\n";
				echo "</div>\n";
		
				echo "<pre class=\"vardump\" style=\"clear: both; padding: 15; background-color: black; color: green;\">$sRes</pre>\n";
				if (stripos($sRes, 'exception') !== false)
				{
					throw new UnitTestException('Encountered an Exception during the last import/synchro');
				}
			}
		}
		return;

		echo "</div>\n";
	}
	
	protected function DoExecute()
	{
/*
		$aScenarios = array(
			array(
				'desc' => 'Load user logins',
				'login' => 'admin',
				'password' => 'admin',
				'target_class' => 'UserLocal',
				'full_load_periodicity' => 3600, // should be ignored in this case
				'reconciliation_policy' => 'use_attributes',
				'action_on_zero' => 'create',
				'action_on_one' => 'update',
				'action_on_multiple' => 'error',
				'delete_policy' => 'delete',
				'delete_policy_update' => '',
				'delete_policy_retention' => 0,
				'source_data' => array(
					array('primary_key', 'login', 'password', 'profile_list'),
					array(
						array('user_A', 'login_A', 'password_A', 'profileid:10;reason:he/she is managing services'),
					),
				),
				'target_data' => array(
					array('login'),
					array(
						// Initial state
					),
					array(
						array('login_A'),
					),
				),
				'attributes' => array(
					'login' => array(
						'do_reconcile' => true,
						'do_update' => true,
						'automatic_prefix' => true, // unique id (for unit testing)
					),
					'password' => array(
						'do_reconcile' => false,
						'do_update' => true,
					),
					'profile_list' => array(
						'do_reconcile' => false,
						'do_update' => true,
					),
				),
			),
		);
*/
		$aScenarios = array(
			array(
				'desc' => 'Simple scenario with delete option (and extkey given as org/name)',
				'login' => 'admin',
				'password' => 'admin',
				'target_class' => 'ApplicationSolution',
				'full_load_periodicity' => 3600, // should be ignored in this case
				'reconciliation_policy' => 'use_attributes',
				'action_on_zero' => 'create',
				'action_on_one' => 'update',
				'action_on_multiple' => 'error',
				'delete_policy' => 'delete',
				'delete_policy_update' => '',
				'delete_policy_retention' => 0,
				'source_data' => array(
					array('primary_key', 'org_id', 'name', 'status'),
					array(
						array('obj_A', null, 'obj_A', 'production'), // org_id unchanged
						array('obj_B', '_DUMMY_', 'obj_B', 'production'), // error, '_DUMMY_' unknown
						array('obj_C', 'SOMECODE', 'obj_C', 'production'),
						array('obj_D', null, 'obj_D', 'production'),
						array('obj_E', '_DUMMY_', 'obj_E', 'production'),
					),
					array(
					),
					array(
					),
				),
				'target_data' => array(
					array('org_id', 'name', 'status'),
					array(
						// Initial state
						array(2, 'obj_A', 'production'),
						array(2, 'obj_B', 'production'),
					),
					array(
						array(2, 'obj_A', 'production'),
						array(2, 'obj_B', 'production'),
						array(1, 'obj_C', 'production'),
					),
					array(
						array(2, 'obj_A', 'production'),
						array(2, 'obj_B', 'production'),
						// deleted !
					),
					// The only diff here is into the log
					array(
						array(2, 'obj_A', 'production'),
						array(2, 'obj_B', 'production'),
						// deleted !
					),
				),
				'attributes' => array(
					'org_id' => array(
						'do_reconcile' => false,
						'do_update' => true,
						'reconciliation_attcode' => 'code',
					),
					'name' => array(
						'do_reconcile' => true,
						'do_update' => true,
						'automatic_prefix' => true, // unique id
					),
					'status' => array(
						'do_reconcile' => false,
						'do_update' => true,
					),
				),
			),
		//);
		//$aXXXXScenarios = array(
			array(
				'desc' => 'Update then delete with retention (to complete with manual testing) and reconciliation on org/name',
				'login' => 'admin',
				'password' => 'admin',
				'target_class' => 'ApplicationSolution',
				'full_load_periodicity' => 3600,
				'reconciliation_policy' => 'use_attributes',
				'action_on_zero' => 'create',
				'action_on_one' => 'update',
				'action_on_multiple' => 'error',
				'delete_policy' => 'update_then_delete',
				'delete_policy_update' => 'status:obsolete',
				'delete_policy_retention' => 15,
				'source_data' => array(
					array('primary_key', 'org_id', 'name', 'status'),
					array(
						array('obj_A', 'Demo', 'obj_A', 'production'),
					),
					array(
					),
				),
				'target_data' => array(
					array('org_id', 'name', 'status'),
					array(
						// Initial state
					),
					array(
						array(2, 'obj_A', 'production'),
					),
					array(
						array(2, 'obj_A', 'obsolete'),
						// deleted !
					),
				),
				'attributes' => array(
					'org_id' => array(
						'do_reconcile' => true,
						'do_update' => true,
						'reconciliation_attcode' => 'name',
					),
					'name' => array(
						'do_reconcile' => true,
						'do_update' => true,
						'automatic_prefix' => true, // unique id
					),
					'status' => array(
						'do_reconcile' => false,
						'do_update' => true,
					),
				),
			),
		//);
		//$aXXScenarios = array(
			array(
				'desc' => 'Simple scenario loading a few ApplicationSolution',
				'login' => 'admin',
				'password' => 'admin',
				'target_class' => 'ApplicationSolution',
				'full_load_periodicity' => 3600,
				'reconciliation_policy' => 'use_attributes',
				'action_on_zero' => 'create',
				'action_on_one' => 'update',
				'action_on_multiple' => 'error',
				'delete_policy' => 'update',
				'delete_policy_update' => 'status:obsolete',
				'delete_policy_retention' => 0,
				'source_data' => array(
					array('primary_key', 'org_id', 'name', 'status'),
					array(
						array('obj_A', 2, 'obj_A', 'production'),
						array('obj_B', 2, 'obj_B', 'implementation'),
						array('obj_C', 2, 'obj_C', 'implementation'),
					),
					array(
						array('obj_A', 2, 'obj_A', 'production'),
						array('obj_B', 2, 'obj_B', 'implementation'),
						array('obj_C', 2, 'obj_C', 'implementation'),
					),
					array(
						array('obj_A', 2, 'obj_A', 'production'),
						array('obj_C', 2, 'obj_C', 'implementation'),
						array('obj_D', 2, 'obj_D', 'implementation'),
					),
					array(
						array('obj_C', 2, 'obj_C', 'production'),
					),
					array(
						array('obj_C', 2, 'obj_C', 'production'),
					),
				),
				'target_data' => array(
					array('org_id', 'name', 'status'),
					array(
						// Initial state
						array(2, 'obj_A', 'implementation'),
						array(2, 'obj_B', 'production'),
						array(2, 'obj_B', 'implementation'),
					),
					array(
						array(2, 'obj_A', 'production'),
						array(2, 'obj_B', 'production'),
						array(2, 'obj_B', 'implementation'),
						array(2, 'obj_C', 'implementation'),
					),
					array(
						array(2, 'obj_A', 'production'),
						array(2, 'obj_B', 'production'),
						array(2, 'obj_B', 'implementation'),
						array(2, 'obj_C', 'implementation'),
					),
					array(
						array(2, 'obj_A', 'production'),
						array(2, 'obj_B', 'production'),
						array(2, 'obj_B', 'implementation'),
						array(2, 'obj_C', 'implementation'),
						array(2, 'obj_D', 'implementation'),
					),
					array(
						array(2, 'obj_A', 'obsolete'),
						array(2, 'obj_B', 'production'),
						array(2, 'obj_B', 'implementation'),
						array(2, 'obj_C', 'production'),
						array(2, 'obj_D', 'obsolete'),
					),
					array(
						array(2, 'obj_A', 'obsolete'),
						array(2, 'obj_B', 'production'),
						array(2, 'obj_B', 'implementation'),
						array(2, 'obj_C', 'production'),
						array(2, 'obj_D', 'obsolete'),
					),
				),
				'attributes' => array(
					'org_id' => array(
						'do_reconcile' => false,
						'do_update' => true,
					),
					'name' => array(
						'do_reconcile' => true,
						'do_update' => true,
						'automatic_prefix' => true, // unique id
					),
					'status' => array(
						'do_reconcile' => false,
						'do_update' => true,
					),
				),
			),
		); 

		foreach ($aScenarios as $aSingleScenario)
		{
			$this->DoExecScenario($aSingleScenario);
		}
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
		$sWsdlUri = 'http'.(isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS']!='off') ? 's' : '').'://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].dirname($_SERVER['SCRIPT_NAME']).'/../webservices/itop.wsdl.php';

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

	static public function GetConfigFile() {return '/config-itop.php';}

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

	static public function GetConfigFile() {return '/config-itop.php';}

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

	static public function GetConfigFile() {return '/config-itop.php';}

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

	static public function GetConfigFile() {return '/config-itop.php';}

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

	static public function GetConfigFile() {return '/config-itop.php';}

	protected function DoExecute()
	{
		$oUser = new UserLocal();
		$oUser->Set('login', 'patator'.time());
		$oUser->Set('password', 'patator');
		//$oUser->Set('contactid', 0);
		//$oUser->Set('language', $sLanguage);

      $sLinkSetSpec = "profileid:10;reason:service manager|profileid->name:Problem Manager;'reason:problem manager;glandeur";

		$oAttDef = MetaModel::GetAttributeDef('UserLocal', 'profile_list');
		$oSet = $oAttDef->MakeValueFromString($sLinkSetSpec);
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

	static public function GetConfigFile() {return '/config-itop.php';}

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

?>
