<?php

class TestSQLQuery extends TestScenarioOnDB
{
	static public function GetName() {return 'SQLQuery';}
	static public function GetDescription() {return 'SQLQuery does not depend on the rest of the framework, therefore it makes sense to have a separate test framework for it';}

	static public function GetDBHost() {return 'localhost';}
	static public function GetDBUser() {return 'RomainDBLogin';}
	static public function GetDBPwd() {return '';}
	static public function GetDBName() {return 'TestSQLQuery';}
	static public function GetDBSubName() {return 'taratata';}


	protected function DoPrepare()
	{
		parent::DoPrepare();
		cmdbSource::CreateTable('CREATE TABLE `myTable` (myKey INT(11) NOT NULL auto_increment, column1 VARCHAR(255), column2 VARCHAR(255), PRIMARY KEY (`myKey`)) ENGINE = innodb');
		cmdbSource::CreateTable('CREATE TABLE `myTable1` (myKey1 INT(11) NOT NULL auto_increment, column1_1 VARCHAR(255), column1_2 VARCHAR(255), PRIMARY KEY (`myKey1`)) ENGINE = innodb');
		cmdbSource::CreateTable('CREATE TABLE `myTable2` (myKey2 INT(11) NOT NULL auto_increment, column2_1 VARCHAR(255), column2_2 VARCHAR(255), PRIMARY KEY (`myKey2`)) ENGINE = innodb');
	}

	protected function DoExecute()
	{
		$oQuery = new SQLQuery(
			$sTable = 'myTable',
			$sTableAlias = 'myTableAlias',
			$aFields = array('column1'=>new FieldExpression('column1', 'myTableAlias'), 'column2'=>new FieldExpression('column2', 'myTableAlias')),
			$oCondition = new BinaryExpression(new FieldExpression('column1', 'myTableAlias'), 'LIKE', new ScalarExpression('trash')),
			$aFullTextNeedles = array('column1'),
			$bToDelete = false,
			$aValues = array()
		);
		$oQuery->AddCondition(Expression::FromOQL('DATE(NOW() - 1200 * 2) > \'2008-07-31\''));

		$oSubQuery1 = new SQLQuery(
			$sTable = 'myTable1',
			$sTableAlias = 'myTable1Alias',
			$aFields = array('column1_1'=>new FieldExpression('column1', 'myTableAlias'), 'column1_2'=>new FieldExpression('column1', 'myTableAlias')),
			$oCondition = new TrueSQLExpression,
			$aFullTextNeedles = array(),
			$bToDelete = false,
			$aValues = array()
		);

		$oSubQuery2 = new SQLQuery(
			$sTable = 'myTable2',
			$sTableAlias = 'myTable2Alias',
			$aFields = array('column2_1'=>new FieldExpression('column2', 'myTableAlias'), 'column2_2'=>new FieldExpression('column2', 'myTableAlias')),
			$oCondition = new TrueSQLExpression,
			$aFullTextNeedles = array(),
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
			MyHelpers::var_dump_html($oTrash, true);
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
		);

		$iErrors = 0;

		foreach($aQueries as $sQuery => $bIsCorrectQuery)
		{
			$sIsOk = $bIsCorrectQuery ? 'good' : 'bad';
			echo "<h4>Testing query: $sQuery ($sIsOk)</h4>\n";
			$bRet = $this->TestQuery($sQuery, $bIsCorrectQuery);
			if (!$bRet) $iErrors++;
		}
		
		return ($iErrors == 0);
	}
}


class TestGenericItoMyModel extends TestBizModelGeneric
{
	static public function GetName()
	{
		return 'Generic RO test on '.self::GetConfigFile();
	}

	static public function GetConfigFile() {return '../config-test-mymodel.php';}
}

class TestGenericItopBigModel extends TestBizModelGeneric
{
	static public function GetName()
	{
		return 'Generic RO test on '.self::GetConfigFile();
	}

	static public function GetConfigFile() {return '../config-test-itopv06.php';}
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
		echo "<p>Totor: ".(UserRights::Login('Totor', 'toto') ? 'ok' : 'NO')."</p>\n";
		echo "<p>Romain: ".(UserRights::Login('Romain', 'toto') ? 'ok' : 'NO')."</p>\n";
		echo "<p>User: ".UserRights::GetUser()."</p>\n";
		echo "<p>On behalf of...".UserRights::GetRealUser()."</p>\n";

		echo "<p>Denis (impersonate) : ".(UserRights::Impersonate('Denis', 'tutu') ? 'ok' : 'NO')."</p>\n";
		echo "<p>User: ".UserRights::GetUser()."</p>\n";
		echo "<p>On behalf of...".UserRights::GetRealUser()."</p>\n";

		UserRights::GetFilter('bizOrganization'); // returns a filter object

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
	
	static public function GetConfigFile() {return '../config-test-mymodel.php';}

	function test_linksinfo()
	{
		echo "<h4>Enum links</h4>";
		MyHelpers::var_dump_html(MetaModel::EnumReferencedClasses("cmdbTeam"));
		MyHelpers::var_dump_html(MetaModel::EnumReferencingClasses("Organization"));
	
		MyHelpers::var_dump_html(MetaModel::EnumLinkingClasses());
		MyHelpers::var_dump_html(MetaModel::EnumLinkingClasses("cmdbContact"));
		MyHelpers::var_dump_html(MetaModel::EnumLinkingClasses("cmdWorkshop"));
		MyHelpers::var_dump_html(MetaModel::GetLinkLabel("Liens_entre_contacts_et_workshop", "toworkshop"));
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
		MyHelpers::var_dump_html($team);
	}
	
	function test_setattribute()
	{
		echo "<h4>Set attribute and update</h4>";
		$team = MetaModel::GetObject("cmdbTeam", "2");
		$team->Set("headcount", rand(1,1000));
		$team->Set("email", "Luis ".rand(9,250));
		MyHelpers::var_dump_html($team->ListChanges());
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
		MyHelpers::var_dump_html($oTeam);
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
		MyHelpers::var_dump_html($oTeam);
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
		MyHelpers::var_dump_html($oMyChange);
	
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
		MyHelpers::var_dump_html($oTeam);
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
				$aItems = MetaModel::GetZListItems($sKlass, $sListCode);
				if (count($aItems) == 0) continue;
	
				echo "$sKlass - $sListCode : {".implode(", ", $aItems)."}</br>\n";
			}
		}
	
		echo "<h4>IsAttributeInZList()... </h4>";
		echo "Liens_entre_contacts_et_workshop::ws_info in list1 ? ".(MetaModel::IsAttributeInZList("Liens_entre_contacts_et_workshop", "list1", "ws_info") ? "yes" : "no")."</br>\n";
		echo "Liens_entre_contacts_et_workshop::toworkshop in list1 ? ".(MetaModel::IsAttributeInZList("Liens_entre_contacts_et_workshop", "list1", "toworkshop") ? "yes" : "no")."</br>\n";
	
	}
	
	function test_SibuSQL()
	{
		echo "<h4>Simple But Structured Query Language</h4>";
	
		$oMyFilter = new DBObjectSearch("cmdbContact");
		echo "Tous les contacts: ".$oMyFilter->ToSibuSQL()."<br/>\n";
		$oNewFilter = DBObjectSearch::FromSibuSQL($oMyFilter->ToSibuSQL());
		echo "En passant par un filtre, ca revient en : ".$oNewFilter->ToSibuSQL()."</br>\n";
		$this->search_and_show_list($oNewFilter);
	
		$sFilterDesc = "cmdbContact: name Begins with '$[debutnom:as:debut du nom]' AND ownername NotLike $[ddd::]"; 
		echo "Construction d'un filtre a partir de sa description en SibuSQL: $sFilterDesc<br/>\n";
	
		MyHelpers::var_dump_html(DBObjectSearch::ListSibusQLParams($sFilterDesc));
		$oNewFilter = DBObjectSearch::FromSibuSQL($sFilterDesc, array('ddd'=>123));
		echo "Ca revient en: ".$oNewFilter->ToSibuSQL();
	}
	
	function test_pkey()
	{
		echo "<h4>Test search on pkey</h4>";
		$sExpr1 = "cmdbContact: pkey IN {40, 42}";
		$sExpr2 = "cmdbContact: pkey NOTIN {40, 42}";
		$this->search_and_show_list_from_sibusql($sExpr1);
		$this->search_and_show_list_from_sibusql($sExpr2);
	
		echo "Et maintenant, on fusionne....</br>\n";
		$oSet1 = new CMDBObjectSet(DBObjectSearch::FromSibuSQL($sExpr1));
		$oSet2 = new CMDBObjectSet(DBObjectSearch::FromSibuSQL($sExpr2));
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
		
		//MyHelpers::var_dump_html(MetaModel::EnumRelationQueries("cmdbObjectHomeMade", "Potes"));
		MyHelpers::var_dump_html(MetaModel::EnumRelationQueries("cmdbContact", "Potes"));
	
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
	
		echo "<h4>Test relations - same results, by the mean of a SibuSQL</h4>";
		$this->search_and_show_list_from_sibusql("cmdbContact: RELATED (Potes, $iMaxDepth) TO (cmdbContact: pkey = 18)");
		
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
	
	
		MyHelpers::var_dump_html(MetaModel::GetStateAttributeCode("cmdbContact"));
		MyHelpers::var_dump_html(MetaModel::EnumStates("cmdbContact"));
		MyHelpers::var_dump_html(MetaModel::EnumStimuli("cmdbContact"));
		foreach(MetaModel::EnumStates("cmdbContact") as $sStateCode => $aStateDef)
		{
			echo "<p>Transition from <strong>$sStateCode</strong></p>\n";
			MyHelpers::var_dump_html(MetaModel::EnumTransitions("cmdbContact", $sStateCode));
		}
	
		$oObj = MetaModel::GetObject("cmdbContact", 18);
		echo "Current state: ".$oObj->GetState()."... let's go to school...";
		MyHelpers::var_dump_html($oObj->EnumTransitions());
		$oObj->ApplyStimulus("toschool");
		echo "New state: ".$oObj->GetState()."... let's get older...";
		MyHelpers::var_dump_html($oObj->EnumTransitions());
		$oObj->ApplyStimulus("raise");
		echo "New state: ".$oObj->GetState()."... let's try to go further... (should give an error)";
		MyHelpers::var_dump_html($oObj->EnumTransitions());
		$oObj->ApplyStimulus("raise"); // should give an error
	}


	protected function DoExecute()
	{
//				$this->ReportError("Found two different SibuSQL expression out of the (same?) filter: <em>$sExpr1</em> != <em>$sExpr2</em>");
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
		$this->test_SibuSQL();
		//$this->test_pkey();
		$this->test_relations();
		$this->test_linkedset();
		$this->test_object_lifecycle();
		return true;
	}
}


///////////////////////////////////////////////////////////////////////////
// Test a complex biz model on the fly
///////////////////////////////////////////////////////////////////////////

class TestQueriesOnFarm extends TestBizModel
{
	static public function GetName()
	{
		return 'Farm test';
	}

	static public function GetDescription()
	{
		return 'A series of tests on the farm business model (SQL generation)';
	}
	
	static public function GetConfigFile() {return '../config-test-farm.php';}

	protected function DoPrepare()
	{
		parent::DoPrepare();
		$this->ResetDB();
		MetaModel::DBCheckIntegrity();
	}

	protected $m_oChange;
	protected function ObjectToDB(CMDBObject $oNew)
	{
		if (!isset($this->m_oChange))
		{
			 new CMDBChange();
			$oMyChange = MetaModel::NewObject("CMDBChange");
			$oMyChange->Set("date", time());
			$oMyChange->Set("userinfo", "Administrator");
			$iChangeId = $oMyChange->DBInsertNoReload();
			$this->m_oChange = $oMyChange; 
		}
		$iId = $oNew->DBInsertTrackedNoReload($this->m_oChange);
		return $iId;
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
			//MyHelpers::var_dump_html($oTrash, true);
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
		//MyHelpers::var_dump_html($oMyFilter, true);
		$sQuery1 = MetaModel::MakeSelectQuery($oMyFilter);
		//echo "<p>second pass<p>\n";
		//MyHelpers::var_dump_html($oMyFilter, true);
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
//			$this->ReportError("Found two different SibuSQL expression out of the (same?) filter: <em>$sExpr1</em> != <em>$sExpr2</em>");
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
			'SELECT Animal AS A JOIN Group AS G ON G.leader = A.pkey' => false,
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
	
	static public function GetConfigFile() {return '../config-test-farm.php';}

	protected function DoPrepare()
	{
		parent::DoPrepare();
		$this->ResetDB();
		MetaModel::DBCheckIntegrity();
	}

	protected function DoExecute()
	{
//			$this->ReportError("Found two different SibuSQL expression out of the (same?) filter: <em>$sExpr1</em> != <em>$sExpr2</em>");
//			$this->ReportSuccess('Found '.$oSet->Count()." objects of class $sClassName");

		$oParser = new CSVParser("#denomination,hauteur,age
		suzy,123,2009-01-01
		chita,456,
		");
		$oParser->SetSeparator(',');
		$aData = $oParser->ToArray(array('_name', '_height', '_birth'));
		MyHelpers::var_dump_html($aData);

		$oBulk = new BulkChange(
			'Mammal',
			$aData,
			array('name' => '_name', 'height' => '_height', 'birth' => '_birth'),
			array('name'),
			array()
		);

		$oMyChange = MetaModel::NewObject("CMDBChange");
		$oMyChange->Set("date", time());
		$oMyChange->Set("userinfo", "Testor");
		$iChangeId = $oMyChange->DBInsert();
//		echo "Created new change: $iChangeId</br>";

		echo "<h3>Planned for loading...</h3>";
		$aRes = $oBulk->Process();
		print_r($aRes);
		echo "<h3>Go for loading...</h3>";
		$aRes = $oBulk->Process($oMyChange);
		print_r($aRes);

		return true;

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
// Benchmark queries
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

	static public function GetConfigFile() {return '../config-itop.php';}

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
			'SELECT bizIncidentTicket',
			'SELECT bizIncidentTicket WHERE id=1',
			'SELECT bizPerson',
			'SELECT bizPerson WHERE id=1',
			'SELECT bizIncidentTicket JOIN bizPerson ON bizIncidentTicket.agent_id = bizPerson.id WHERE bizPerson.id = 5',
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
			$aValues['OQL'] = htmlentities($sOQL);

			foreach($aResults as $sDesc => $sInfo)
			{
				$aValues[$sDesc] = htmlentities($sInfo);
			}
			$aData[] = $aValues;
		}
		echo MyHelpers::make_table_from_assoc_array($aData);
		return true;
	}
}

///////////////////////////////////////////////////////////////////////////
// Test data load
///////////////////////////////////////////////////////////////////////////

class TestItopWebServices extends TestWebServices
{
	static public function GetName()
	{
		return 'Itop - web services';
	}

	static public function GetDescription()
	{
		return 'Bulk load and ???';
	}

	protected function DoExecSingleLoad($aLoadSpec)
	{
		$sTitle = 'Load: '.$aLoadSpec['class'];
		$sClass = $aLoadSpec['class'];
		$sCsvData = $aLoadSpec['csvdata'];

		$aPostData = array('class' => $sClass, 'csvdata' => $sCsvData);
		$sRes = self::DoPostRequestAuth('webservices/import.php', $aPostData);

		echo "<div><h3>$sTitle</h3><pre>$sCsvData</pre><div>$sRes</div></div>";
	}
	
	protected function DoExecute()
	{

		$aLoads = array(
			array(
				'class' => 'bizOrganization',
				'csvdata' => "name;code\nWorldCompany;WCY"
			),
			array(
				'class' => 'bizLocation',
				'csvdata' => "name;org_id;address\nParis;1;Centre de la Franca"
			),
			array(
				'class' => 'bizPerson',
				'csvdata' => "email;name;first_name;org_id;phone\njohn.foo@starac.com;Foo;John;1;+33(1)23456789"
			),
			array(
				'class' => 'bizTeam',
				'csvdata' => "name;org_id;location_id\nSquadra Azzura;1;1"
			),
			array(
				'class' => 'bizWorkgroup',
				'csvdata' => "name;org_id;team_id\ntravailleurs alpins;1;6"
			),
			array(
				'class' => 'bizIncidentTicket',
				'csvdata' => "name;title;type;customer_id;initial_situation;start_date;next_update;caller_id;workgroup_id;agent_id\nOVSD-12345;server down;Network;1;server was found down;2009-04-10 12:00;2009-04-10 15:00;3;317;5"
			),
		);  

		foreach ($aLoads as $aLoadSpec)
		{
			$this->DoExecSingleLoad($aLoadSpec);
		}

		return true;
	}
}
?>
