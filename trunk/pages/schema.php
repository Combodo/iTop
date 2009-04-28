<?php
require_once('../application/application.inc.php');
require_once('../application/itopwebpage.class.inc.php');

require_once('../application/startup.inc.php');


/**
 * Helper for this page -> link to a class
 */
function MakeClassHLink($sClass)
{
	return "<a href=\"?operation=details_class&class=$sClass\" title=\"".MetaModel::GetClassDescription($sClass)."\">".MetaModel::GetName($sClass)."</a>";
}

/**
 * Helper for this page -> link to a class
 */
function MakeRelationHLink($sRelCode)
{
	$sDec = MetaModel::GetRelationProperty($sRelCode, 'description');
	//$sVerbDown = MetaModel::GetRelationProperty($sRelCode, 'verb_down');
	//$sVerbUp = MetaModel::GetRelationProperty($sRelCode, 'verb_up');
	return "<a href=\"?operation=details_relation&relcode=$sRelCode\" title=\"$sDec\">".$sRelCode."</a>";
}

/**
 * Helper for the global list and the details of a given class
 */
function DisplaySubclasses($oPage, $sClass)
{
	$aChildClasses = MetaModel::EnumChildClasses($sClass);
	if (count($aChildClasses) != 0)
	{
		$oPage->add("<ul>\n");
		foreach($aChildClasses as $sClassName)
		{
			// Skip indirect childs, they will be handled somewhere else
			if (MetaModel::GetParentPersistentClass($sClassName) == $sClass)
			{
					$oPage->add("<li>".MakeClassHLink($sClassName)."\n");
					DisplaySubclasses($oPage, $sClassName);
					$oPage->add("</li>\n");
			}
		}
		$oPage->add("</ul>\n");
	}
}

/**
 * Helper for the global list and the details of a given class
 */
function DisplayReferencingClasses($oPage, $sClass)
{
	$bSkipLinkingClasses = false;
	$aRefs = MetaModel::EnumReferencingClasses($sClass, $bSkipLinkingClasses);
	if (count($aRefs) != 0)
	{
		$oPage->add("<ul>\n");
		foreach ($aRefs as $sRemoteClass => $aRemoteKeys)
		{
			foreach ($aRemoteKeys as $sExtKeyAttCode)
			{
				$oPage->add("<li>".MakeClassHLink($sRemoteClass)." by <em>$sExtKeyAttCode</em></li>\n");
			}
		}
		$oPage->add("</ul>\n");
	}
}

/**
 * Helper for the global list and the details of a given class
 */
function DisplayLinkingClasses($oPage, $sClass)
{
	$bSkipLinkingClasses = false;
	$aRefs = MetaModel::EnumLinkingClasses($sClass);
	if (count($aRefs) != 0)
	{
		$oPage->add("<ul>\n");
		foreach ($aRefs as $sLinkClass => $aRemoteClasses)
		{
			foreach($aRemoteClasses as $sExtKeyAttCode => $sRemoteClass)
			{
				$oPage->add("<li>".MakeClassHLink($sRemoteClass)." by <em>".MakeClassHLink($sLinkClass)."::$sExtKeyAttCode</em></li>\n");
			}
		}
		$oPage->add("</ul>\n");
	}
}

/**
 * Helper for the global list and the details of a given class
 */
function DisplayRelatedClassesBestInClass($oPage, $sClass, $iLevels = 20, &$aVisitedClasses = array(), $bSubtree = true)
{
	if ($iLevels <= 0) return;
	$iLevels--;

	if (array_key_exists($sClass, $aVisitedClasses)) return;
	$aVisitedClasses[$sClass] = true;

	if ($bSubtree) $oPage->add("<ul class=\"treeview\">\n");
	foreach (MetaModel::EnumParentClasses($sClass) as $sParentClass)
	{
		DisplayRelatedClassesBestInClass($oPage, $sParentClass, $iLevels, $aVisitedClasses, false);
	}
	////$oPage->add("<div style=\"background-color:#ccc; border: 1px dashed #333;\">");
	foreach (MetaModel::EnumReferencedClasses($sClass) as $sExtKeyAttCode => $sRemoteClass)
	{
		$sVisited = (array_key_exists($sRemoteClass, $aVisitedClasses)) ? " ..." : "";
		if (MetaModel::GetAttributeOrigin($sClass, $sExtKeyAttCode) == $sClass)
		{
			$oPage->add("<li>$sClass| <em>$sExtKeyAttCode</em> =&gt;".MakeClassHLink($sRemoteClass)."$sVisited</li>\n");
			DisplayRelatedClassesBestInClass($oPage, $sRemoteClass, $iLevels, $aVisitedClasses);
		}
	}
	foreach (MetaModel::EnumReferencingClasses($sClass) as $sRemoteClass => $aRemoteKeys)
	{
		foreach ($aRemoteKeys as $sExtKeyAttCode)
		{
			$sVisited = (array_key_exists($sRemoteClass, $aVisitedClasses)) ? " ..." : "";
			$oPage->add("<li>$sClass| &lt;=".MakeClassHLink($sRemoteClass)."::<em>$sExtKeyAttCode</em>$sVisited</li>\n");
			DisplayRelatedClassesBestInClass($oPage, $sRemoteClass, $iLevels, $aVisitedClasses);
		}
	}
	////$oPage->add("</div>");
	if ($bSubtree) $oPage->add("</ul>\n");
}

/**
 * Helper for the list of classes related to the given class
 */
function DisplayRelatedClasses($oPage, $sClass)
{
	$oPage->add("<h3>Childs</h3>\n");
	DisplaySubclasses($oPage, $sClass);

	$oPage->add("<h3>Pointed to by...</h3>\n");
	DisplayReferencingClasses($oPage, $sClass);

	$oPage->add("<h3>Linked to ...</h3>\n");
	DisplayLinkingClasses($oPage, $sClass);

	$oPage->add("<h3>ZE Graph ...</h3>\n");
	DisplayRelatedClassesBestInClass($oPage, $sClass, 4);
}

/**
 * Helper for the lifecycle details of a given class
 */
function DisplayLifecycle($oPage, $sClass)
{
	$sStateAttCode = MetaModel::GetStateAttributeCode($sClass);
	if (empty($sStateAttCode))
	{
		$oPage->p("no lifecycle for this class");
	}
	else
	{
		$aStates = MetaModel::EnumStates($sClass);
		$aStimuli = MetaModel::EnumStimuli($sClass);
		$oPage->add("<img src=\"/pages/graphviz.php?class=$sClass\">\n");
		$oPage->add("<h3>Transitions</h3>\n");
		$oPage->add("<ul>\n");
		foreach ($aStates as $sStateCode => $aStateDef)
		{
			$sStateLabel = $aStates[$sStateCode]['label'];
			$sStateDescription = $aStates[$sStateCode]['description'];
			$oPage->add("<li title=\"code: $sStateCode\">$sStateLabel <span style=\"color:grey;\">($sStateDescription)</span></li>\n");
			$oPage->add("<ul>\n");
			foreach(MetaModel::EnumTransitions($sClass, $sStateCode) as $sStimulusCode => $aTransitionDef)
			{
				$sStimulusLabel = $aStimuli[$sStimulusCode]->Get('label');
				$sTargetStateLabel = $aStates[$aTransitionDef['target_state']]['label'];
				if (count($aTransitionDef['actions']) > 0)
				{
					$sActions = " <em>(".implode(', ', $aTransitionDef['actions']).")</em>";
				}
				else
				{
					$sActions = "";
				}
				$oPage->add("<li><span style=\"color:red;font-weight=bold;\">$sStimulusLabel</span> =&gt; $sTargetStateLabel $sActions</li>\n");
			}
			$oPage->add("</ul>\n");
		}
		$oPage->add("</ul>\n");

		$oPage->add("<h3>Attribute options</h3>\n");
		$oPage->add("<ul>\n");
		foreach ($aStates as $sStateCode => $aStateDef)
		{
			$sStateLabel = $aStates[$sStateCode]['label'];
			$sStateDescription = $aStates[$sStateCode]['description'];
			$oPage->add("<li title=\"code: $sStateCode\">$sStateLabel <span style=\"color:grey;\">($sStateDescription)</span></li>\n");
			if (count($aStates[$sStateCode]['attribute_list']) > 0)
			{
				$oPage->add("<ul>\n");
				foreach($aStates[$sStateCode]['attribute_list'] as $sAttCode => $iOptions)
				{
					$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
					$sAttLabel = $oAttDef->GetLabel();
	
					$aOptions = array();
					if ($iOptions & OPT_ATT_HIDDEN) $aOptions[] = 'Hidden';
					if ($iOptions & OPT_ATT_READONLY) $aOptions[] = 'Read-only';
					if ($iOptions & OPT_ATT_MANDATORY) $aOptions[] = 'Mandatory';
					if ($iOptions & OPT_ATT_MUSTCHANGE) $aOptions[] = 'Must change';
					if ($iOptions & OPT_ATT_MUSTPROMPT) $aOptions[] = 'Must be proposed for changing';
					if (count($aOptions))
					{
						$sOptions = implode(', ', $aOptions);
					}
					else
					{
						$sOptions = "";
					}
	
					$oPage->add("<li><span style=\"color:purple;font-weight=bold;\">$sAttLabel</span> $sOptions</li>\n");
				}
				$oPage->add("</ul>\n");
			}
			else
			{
				$oPage->p("<em>empty list</em>");
			}
		}
		$oPage->add("</ul>\n");
	}
}


/**
 * Display the list of classes from the business model
 */
function DisplayClassesList($oPage)
{
	$oPage->add("<h1>iTop objects schema</h1>\n");

	$oPage->add("<ul id=\"ClassesList\" class=\"treeview fileview\">\n");
	foreach(MetaModel::EnumCategories() as $sCategory)
	{
		if (empty($sCategory)) continue; // means 'all' -> skip

		$sClosed = ($sCategory == 'bizmodel') ? '' : ' class="closed"';
		$oPage->add("<li$sClosed>Category <b>$sCategory</b>\n");

		$oPage->add("<ul>\n");
		foreach(MetaModel::GetClasses($sCategory) as $sClassName)
		{
			if (MetaModel::IsStandaloneClass($sClassName))
			{
				$oPage->add("<li>".MakeClassHLink($sClassName)."</li>\n");
			}
			else if (MetaModel::IsRootClass($sClassName))
			{
				$oPage->add("<li class=\"closed\">".MakeClassHLink($sClassName)."\n");
				DisplaySubclasses($oPage, $sClassName);
				$oPage->add("</li>\n");
			}
		}
		$oPage->add("</ul>\n");

		$oPage->add("</li>\n");
	}
	$oPage->add("</ul>\n");


	$oPage->add("<h1>Relationships</h1>\n");

	$oPage->add("<ul id=\"ClassesRelationships\" class=\"treeview\">\n");
	foreach (MetaModel::EnumRelations() as $sRelCode)
	{
		$oPage->add("<li>".MakeRelationHLink($sRelCode)."\n");
		$oPage->add("<ul>\n");
		foreach (MetaModel::EnumRelationProperties($sRelCode) as $sProp => $sValue)
		{
			$oPage->add("<li>$sProp: ".htmlentities($sValue)."</li>\n");
		}
		$oPage->add("</ul>\n");
		$oPage->add("</li>\n");
	}
	$oPage->add("</ul>\n");
	$oPage->add_ready_script('$("#ClassesList").treeview();');
	$oPage->add_ready_script('$("#ClassesRelationships").treeview();');
}

/**
 * Display the details of a given class of objects
 */
function DisplayClassDetails($oPage, $sClass)
{
	$oPage->p("<h2>$sClass</h2><br/>\n".MetaModel::GetClassDescription($sClass)."<br/>\n");
	$oPage->p("<h3>Class Hierarchy</h3>");
	$oPage->p("[<a href=\"?operation='list'\">All classes</a>]");
	// List the parent classes
	$sParent = MetaModel::GetParentPersistentClass($sClass);
	$aParents = array();
	$aParents[] = $sClass;
	while($sParent != "" && $sParent != 'cmdbAbstractObject')
	{
		$aParents[] = $sParent;
		$sParent = MetaModel::GetParentPersistentClass($sParent);
	}
	$iIndex = count($aParents);
	$sSpace ="";
	$oPage->add("<ul id=\"ClassHierarchy\">");
	while ($iIndex > 0)
	{
		$iIndex--;
		$oPage->add("<li>".MakeClassHLink($aParents[$iIndex])."\n");
		$oPage->add("<ul>\n");
	}
	for($iIndex = 0; $iIndex < count($aParents); $iIndex++)
	{
		$oPage->add("</ul>\n</li>\n");
	}
	$oPage->add("</ul>\n");
	$oPage->add_ready_script('$("#ClassHierarchy").treeview();');	
	$oPage->p('');
	$oPage->AddTabContainer('details');
	$oPage->SetCurrentTabContainer('details');
	// List the attributes of the object
	$aDetails = array();
	foreach(MetaModel::ListAttributeDefs($sClass) as $sAttCode=>$oAttDef)
	{
		if ($oAttDef->IsExternalKey())
		{
		   $sValue = "External key to ".MakeClassHLink($oAttDef->GetTargetClass());
		}
		else
		{
		   $sValue = $oAttDef->GetDescription();
		}
        $sType = $oAttDef->GetType().' ('.$oAttDef->GetTypeDesc().')';
        $sOrigin = MetaModel::GetAttributeOrigin($sClass, $sAttCode);
		$sAllowedValues = "";
		$oAllowedValuesDef = $oAttDef->GetValuesDef();
		$sMoreInfo = "";
		if (is_subclass_of($oAttDef, 'AttributeDBFieldVoid'))
		{
			$aMoreInfo = array();
			$aMoreInfo[] = "Column: <em>".$oAttDef->GetSQLExpr()."</em>";
			$aMoreInfo[] = "Default: '".$oAttDef->GetDefaultValue()."'";
			$aMoreInfo[] = $oAttDef->IsNullAllowed() ? "Null allowed" : "Null NOT allowed";
			//$aMoreInfo[] = $oAttDef->DBGetUsedFields();
			$sMoreInfo .= implode(', ', $aMoreInfo);
		}

		if (is_object($oAllowedValuesDef)) $sAllowedValues = $oAllowedValuesDef->GetValuesDescription();
		else $sAllowedValues = '';

		$aDetails[] = array('code' => $oAttDef->GetCode(), 'type' => $sType, 'origin' => $sOrigin, 'label' => $oAttDef->GetLabel(), 'description' => $sValue, 'values' => $sAllowedValues, 'moreinfo' => $sMoreInfo);
	}
	$oPage->SetCurrentTab('Attributes');
	$aConfig = array( 'code' => array('label' => 'Attribute code', 'description' => 'Code of this attribute'),
					  'label' => array('label' => 'Label', 'description' => 'Label of this attribute'),
					  'type' => array('label' => 'Type', 'description' => 'Data type of this attribute'),
					  'origin' => array('label' => 'Origin', 'description' => 'The base class for this attribute'),
					  'description' => array('label' => 'Description', 'description' => 'Description of this attribute'),
					  'values' => array('label' => 'Allowed Values', 'description' => 'Restrictions on the possible values for this attribute'),
					  'moreinfo' => array('label' => 'More info', 'description' => 'More info for the fields related to a Database field'),
	);
	$oPage->table($aConfig, $aDetails);

	// List the search criteria for this object
	$aDetails = array();
	foreach (MetaModel::GetClassFilterDefs($sClass) as $sFilterCode => $oFilterDef)
	{
		$aOpDescs = array();
		foreach ($oFilterDef->GetOperators() as $sOpCode => $sOpDescription)
		{
			$sIsTheLooser = ($sOpCode == $oFilterDef->GetLooseOperator()) ? " (loose search)" : "";
			$aOpDescs[] = "$sOpCode ($sOpDescription)$sIsTheLooser";
		}
		$aDetails[] = array( 'code' => $sFilterCode, 'description' => $oFilterDef->GetLabel(),'operators' => implode(" / ", $aOpDescs));	
	}
	$oPage->SetCurrentTab('Search criteria');
	$aConfig = array( 'code' => array('label' => 'Filter code', 'description' => 'Code of this search criteria'),
					  'description' => array('label' => 'Description', 'description' => 'Description of this search criteria'),
					  'operators' => array('label' => 'Available operators', 'description' => 'Possible operators for this search criteria')
	);
	$oPage->table($aConfig, $aDetails);

	$oPage->SetCurrentTab('Child classes');
	DisplaySubclasses($oPage, $sClass);

	$oPage->SetCurrentTab('Referencing classes');
	DisplayReferencingClasses($oPage, $sClass);

	$oPage->SetCurrentTab('Related classes');
	DisplayRelatedClasses($oPage, $sClass);

	$oPage->SetCurrentTab('Lifecycle');
	DisplayLifecycle($oPage, $sClass);

	$oPage->SetCurrentTab();
	$oPage->SetCurrentTabContainer();
}


/**
 * Display the details of a given relation (e.g. "impacts")
 */
function DisplayRelationDetails($oPage, $sRelCode)
{
	$sDesc = MetaModel::GetRelationProperty($sRelCode, 'description');
	$sVerbDown = MetaModel::GetRelationProperty($sRelCode, 'verb_down');
	$sVerbUp = MetaModel::GetRelationProperty($sRelCode, 'verb_up');
	$oPage->add("<h1>Relation <em>$sRelCode</em> ($sDesc)</h1>");
	$oPage->p("Down: $sVerbDown");
	$oPage->p("Up: $sVerbUp");

	$oPage->add("<ul id=\"RelationshipDetails\" class=\"treeview\">\n");
	foreach(MetaModel::GetClasses() as $sClass)
	{
		$aRelQueries = MetaModel::EnumRelationQueries($sClass, $sRelCode);
		if (count($aRelQueries) > 0)
		{
			$oPage->add("<li>class ".MakeClassHLink($sClass)."\n");
			$oPage->add("<ul>\n");
			foreach ($aRelQueries as $sRelKey => $aQuery)
			{
				$sQuery = $aQuery['sQuery'];
				$bPropagate = $aQuery['bPropagate'] ? "Propagate" : "Do not propagate";
				$iDistance = $aQuery['iDistance'];

				$oPage->add("<li>$sRelKey: $bPropagate ($iDistance) ".DBObjectSearch::SibuSQLAsHtml($sQuery)."</li>\n");
			}
			$oPage->add("</ul>\n");
			$oPage->add("</li>\n");
		}
	}
	$oPage->add_ready_script('$("#RelationshipDetails").treeview();');
}


require_once('../application/loginwebpage.class.inc.php');
login_web_page::DoLogin(); // Check user rights and prompt if needed

// Display the menu on the left
$oContext = new UserContext();
$oAppContext = new ApplicationContext();
$iActiveNodeId = utils::ReadParam('menu', -1);
$currentOrganization = utils::ReadParam('org_id', 1);
$operation = utils::ReadParam('operation', '');

$oPage = new iTopWebPage("iTop objects schema", $currentOrganization);
$oPage->no_cache();

$operation = utils::ReadParam('operation', '');

switch($operation)
{
	case 'details_class':
	$sClass = utils::ReadParam('class', 'logRealObject');
	DisplayClassDetails($oPage, $sClass);
	break;
	
	case 'details_relation':
	$sRelCode = utils::ReadParam('relcode', '');
	DisplayRelationDetails($oPage, $sRelCode);
	break;
	
	case 'details':
	$oPage->p('operation=details has been deprecated, please use details_class');
	break;
	case 'list':
	default:
	DisplayClassesList($oPage);
}

$oPage->output();
?>
