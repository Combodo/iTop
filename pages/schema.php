<?php
// Copyright (C) 2010-2012 Combodo SARL
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
 * Presentation of the data model
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

require_once('../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/itopwebpage.class.inc.php');

require_once(APPROOT.'/application/startup.inc.php');

require_once(APPROOT.'/application/loginwebpage.class.inc.php');
LoginWebPage::DoLogin(true); // Check user rights and prompt if needed (must be admin)


/**
 * Helper for this page -> link to a class
 */
function MakeClassHLink($sClass, $sContext)
{
	return "<a href=\"schema.php?operation=details_class&class=$sClass{$sContext}\" title=\"".MetaModel::GetClassDescription($sClass)."\">".MetaModel::GetName($sClass)." ($sClass)</a>";
}

/**
 * Helper for this page -> link to a class
 */
function MakeRelationHLink($sRelCode, $sContext)
{
	$sDesc = MetaModel::GetRelationDescription($sRelCode);
	return "<a href=\"schema.php?operation=details_relation&relcode=$sRelCode{$sContext}\" title=\"$sDesc\">".$sRelCode."</a>";
}

/**
 * Helper for the global list and the details of a given class
 */
function DisplaySubclasses($oPage, $sClass, $sContext)
{
	$aChildClasses = MetaModel::EnumChildClasses($sClass);
	if (count($aChildClasses) != 0)
	{
		$oPage->add("<ul>\n");
		$aOrderedClasses = array();
		foreach($aChildClasses as $sClassName)
		{
			// Skip indirect childs, they will be handled somewhere else
			if (MetaModel::GetParentPersistentClass($sClassName) == $sClass)
			{
					$aOrderedClasses[$sClassName] = MetaModel::GetName($sClassName);
			}
		}
		// Sort on the display name
		asort($aOrderedClasses);
		foreach($aOrderedClasses as $sClassName => $sDisplayName)
		{
			// Skip indirect childs, they will be handled somewhere else
			if (MetaModel::GetParentPersistentClass($sClassName) == $sClass)
			{
					$oPage->add("<li class=\"open\">".MakeClassHLink($sClassName, $sContext)."\n");
					DisplaySubclasses($oPage, $sClassName, $sContext);
					$oPage->add("</li>\n");
			}
		}
		$oPage->add("</ul>\n");
	}
}

/**
 * Helper for the global list and the details of a given class
 */
function DisplayReferencingClasses($oPage, $sClass, $sContext)
{
	$bSkipLinkingClasses = false;
	$aRefs = MetaModel::EnumReferencingClasses($sClass, $bSkipLinkingClasses);
	if (count($aRefs) != 0)
	{
		$oPage->add("<ul>\n");
		foreach ($aRefs as $sRemoteClass => $aRemoteKeys)
		{
			foreach ($aRemoteKeys as $sExtKeyAttCode => $oExtKeyAttDef)
			{
				$oPage->add("<li>".Dict::Format('UI:Schema:Class_ReferencingClasses_From_By', $sClass, MakeClassHLink($sRemoteClass, $sContext), $sExtKeyAttCode)."</li>\n");
			}
		}
		$oPage->add("</ul>\n");
	}
}

/**
 * Helper for the global list and the details of a given class
 */
function DisplayLinkingClasses($oPage, $sClass, $sContext)
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
				$oPage->add("<li>".Dict::Format('UI:Schema:Class_IsLinkedTo_Class_Via_ClassAndAttribute', $sClass, MakeClassHLink($sRemoteClass, $sContext), MakeClassHLink($sLinkClass, $sContext), $sExtKeyAttCode));
			}
		}
		$oPage->add("</ul>\n");
	}
}

/**
 * Helper for the global list and the details of a given class
 */
function DisplayRelatedClassesBestInClass($oPage, $sClass, $iLevels = 20, &$aVisitedClasses = array(), $bSubtree = true, $sContext)
{
	if ($iLevels <= 0) return;
	$iLevels--;

	if (array_key_exists($sClass, $aVisitedClasses)) return;
	$aVisitedClasses[$sClass] = true;

	if ($bSubtree) $oPage->add("<ul class=\"treeview\">\n");
	foreach (MetaModel::EnumParentClasses($sClass) as $sParentClass)
	{
		DisplayRelatedClassesBestInClass($oPage, $sParentClass, $iLevels, $aVisitedClasses, false, $sContext);
	}
	////$oPage->add("<div style=\"background-color:#ccc; border: 1px dashed #333;\">");
	foreach (MetaModel::EnumReferencedClasses($sClass) as $sExtKeyAttCode => $sRemoteClass)
	{
		$sVisited = (array_key_exists($sRemoteClass, $aVisitedClasses)) ? " ..." : "";
		if (MetaModel::GetAttributeOrigin($sClass, $sExtKeyAttCode) == $sClass)
		{
			$oPage->add("<li>$sClass| <em>$sExtKeyAttCode</em> =&gt;".MakeClassHLink($sRemoteClass, $sContext)."$sVisited</li>\n");
			DisplayRelatedClassesBestInClass($oPage, $sRemoteClass, $iLevels, $aVisitedClasses, true, $sContext);
		}
	}
	foreach (MetaModel::EnumReferencingClasses($sClass) as $sRemoteClass => $aRemoteKeys)
	{
		foreach ($aRemoteKeys as $sExtKeyAttCode => $oExtKeyAttDef)
		{
			$sVisited = (array_key_exists($sRemoteClass, $aVisitedClasses)) ? " ..." : "";
			$oPage->add("<li>$sClass| &lt;=".MakeClassHLink($sRemoteClass, $sContext)."::<em>$sExtKeyAttCode</em>$sVisited</li>\n");
			DisplayRelatedClassesBestInClass($oPage, $sRemoteClass, $iLevels, $aVisitedClasses, true, $sContext);
		}
	}
	////$oPage->add("</div>");
	if ($bSubtree) $oPage->add("</ul>\n");
}

/**
 * Helper for the list of classes related to the given class
 */
function DisplayRelatedClasses($oPage, $sClass, $sContext)
{
	$oPage->add("<h3>".Dict::Format('UI:Schema:Links:1-n', $sClass)."</h3>\n");
	DisplayReferencingClasses($oPage, $sClass, $sContext);

	$oPage->add("<h3>".Dict::Format('UI:Schema:Links:n-n', $sClass)."</h3>\n");
	DisplayLinkingClasses($oPage, $sClass, $sContext);

	$oPage->add("<h3>".Dict::S('UI:Schema:Links:All')."</h3>\n");
	$aEmpty = array();
	DisplayRelatedClassesBestInClass($oPage, $sClass, 4, $aEmpty, true, $sContext);
}

/**
 * Helper for the lifecycle details of a given class
 */
function DisplayLifecycle($oPage, $sClass)
{
	$sStateAttCode = MetaModel::GetStateAttributeCode($sClass);
	if (empty($sStateAttCode))
	{
		$oPage->p(Dict::S('UI:Schema:NoLifeCyle'));
	}
	else
	{
		$aStates = MetaModel::EnumStates($sClass);
		$aStimuli = MetaModel::EnumStimuli($sClass);
		$oPage->add("<img src=\"".utils::GetAbsoluteUrlAppRoot()."pages/graphviz.php?class=$sClass\">\n");
		$oPage->add("<h3>".Dict::S('UI:Schema:LifeCycleTransitions')."</h3>\n");
		$oPage->add("<ul>\n");
		foreach ($aStates as $sStateCode => $aStateDef)
		{
			$sStateLabel = MetaModel::GetStateLabel($sClass, $sStateCode);
			$sStateDescription = MetaModel::GetStateDescription($sClass, $sStateCode);
			$oPage->add("<li title=\"code: $sStateCode\">$sStateLabel <span style=\"color:grey;\">($sStateCode) $sStateDescription</span></li>\n");
			$oPage->add("<ul>\n");
			foreach(MetaModel::EnumTransitions($sClass, $sStateCode) as $sStimulusCode => $aTransitionDef)
			{
				$sStimulusLabel = $aStimuli[$sStimulusCode]->GetLabel();
				$sTargetStateLabel = MetaModel::GetStateLabel($sClass, $aTransitionDef['target_state']);
				if (count($aTransitionDef['actions']) > 0)
				{
					$aActionsDesc = array();
					foreach ($aTransitionDef['actions'] as $actionHandler)
					{
						if (is_string($actionHandler))
						{
							$aActionsDesc[] = $actionHandler;
						}
						else
						{
							$aParamsDesc = array();
							foreach ($actionHandler['params'] as $aParamData)
							{
								$aParamsDesc[] = $aParamData['type'].':'.$aParamData['value'];
							}
							$aActionsDesc[] = $actionHandler['verb'].'('.implode(', ', $aParamsDesc).')';
						}
					}
					$sActions = " <em>(".implode(', ', $aActionsDesc).")</em>";
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

		$oPage->add("<h3>".Dict::S('UI:Schema:LifeCyleAttributeOptions')."</h3>\n");
		$oPage->add("<ul>\n");
		foreach ($aStates as $sStateCode => $aStateDef)
		{
			$sStateLabel = MetaModel::GetStateLabel($sClass, $sStateCode);
			$sStateDescription = MetaModel::GetStateDescription($sClass, $sStateCode);
			$oPage->add("<li title=\"code: $sStateCode\">$sStateLabel <span style=\"color:grey;\">($sStateCode) $sStateDescription</span></li>\n");
			if (count($aStates[$sStateCode]['attribute_list']) > 0)
			{
				$oPage->add("<ul>\n");
				foreach($aStates[$sStateCode]['attribute_list'] as $sAttCode => $iOptions)
				{
					$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
					$sAttLabel = $oAttDef->GetLabel();
	
					$aOptions = array();
					if ($iOptions & OPT_ATT_HIDDEN) $aOptions[] = Dict::S('UI:Schema:LifeCycleHiddenAttribute');
					if ($iOptions & OPT_ATT_READONLY) $aOptions[] = Dict::S('UI:Schema:LifeCycleReadOnlyAttribute');
					if ($iOptions & OPT_ATT_MANDATORY) $aOptions[] = Dict::S('UI:Schema:LifeCycleMandatoryAttribute');
					if ($iOptions & OPT_ATT_MUSTCHANGE) $aOptions[] = Dict::S('UI:Schema:LifeCycleAttributeMustChange');
					if ($iOptions & OPT_ATT_MUSTPROMPT) $aOptions[] = Dict::S('UI:Schema:LifeCycleAttributeMustPrompt');
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
				$oPage->p("<em>".Dict::S('UI:Schema:LifeCycleEmptyList')."</em>");
			}
		}
		$oPage->add("</ul>\n");
	}
}


/**
 * Helper for the trigger
 */
function DisplayTriggers($oPage, $sClass)
{
	$sClassList = implode("', '", MetaModel::EnumParentClasses($sClass, ENUM_PARENT_CLASSES_ALL));
	$oSet = new CMDBObjectSet(DBObjectSearch::FromOQL("SELECT TriggerOnObject WHERE target_class IN ('$sClassList')"));
	cmdbAbstractObject::DisplaySet($oPage, $oSet, array('block_id' => 'triggers'));
}


/**
 * Display the list of classes from the business model
 */
function DisplayClassesList($oPage, $sContext)
{
	$oPage->add("<h1>".Dict::S('UI:Schema:Title')."</h1>\n");

	$oPage->add("<ul id=\"ClassesList\" class=\"treeview fileview\">\n");
	// Get all the "root" classes for display
	$aRootClasses = array();
	foreach(MetaModel::GetClasses() as $sClassName)
	{
		if (MetaModel::IsRootClass($sClassName))
		{
			$aRootClasses[$sClassName] = MetaModel::GetName($sClassName);
		}
		elseif (MetaModel::IsStandaloneClass($sClassName))
		{
			$aRootClasses[$sClassName] = MetaModel::GetName($sClassName);
		}
	}
	// Sort them alphabetically on their display name
	asort($aRootClasses);
	foreach($aRootClasses as $sClassName => $sDisplayName)
	{
		if (MetaModel::IsRootClass($sClassName))
		{
			$oPage->add("<li class=\"open\">".MakeClassHLink($sClassName, $sContext)."\n");
			DisplaySubclasses($oPage, $sClassName, $sContext);
			$oPage->add("</li>\n");
		}
		elseif (MetaModel::IsStandaloneClass($sClassName))
		{
			$oPage->add("<li>".MakeClassHLink($sClassName, $sContext)."</li>\n");
		}
	}
	$oPage->add("</ul>\n");

	$oPage->add("<h1>".Dict::S('UI:Schema:Relationships')."</h1>\n");

	$oPage->add("<ul id=\"ClassesRelationships\" class=\"treeview\">\n");
	foreach (MetaModel::EnumRelations() as $sRelCode)
	{
		$oPage->add("<li>".MakeRelationHLink($sRelCode, $sContext)."\n");
		$oPage->add("<ul>\n");
		$oPage->add("<li>Description: ".htmlentities(MetaModel::GetRelationDescription($sRelCode), ENT_QUOTES, 'UTF-8')."</li>\n");
		$oPage->add("<li>Verb up: ".htmlentities(MetaModel::GetRelationVerbUp($sRelCode), ENT_QUOTES, 'UTF-8')."</li>\n");
		$oPage->add("<li>Verb down: ".htmlentities(MetaModel::GetRelationVerbDown($sRelCode), ENT_QUOTES, 'UTF-8')."</li>\n");
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
function DisplayClassDetails($oPage, $sClass, $sContext)
{
	$oPage->add("<h2>".MetaModel::GetName($sClass)." ($sClass) - ".MetaModel::GetClassDescription($sClass)."</h2>\n");
	if (MetaModel::IsAbstract($sClass))
	{
		$oPage->p(Dict::S('UI:Schema:AbstractClass'));
	}
	else
	{
		$oPage->p(Dict::S('UI:Schema:NonAbstractClass'));
	}

//	$oPage->p("<h3>".Dict::S('UI:Schema:ClassHierarchyTitle')."</h3>");

	$aParentClasses = array();
	foreach(MetaModel::EnumParentClasses($sClass) as $sParentClass)
	{
		$aParentClasses[] = MakeClassHLink($sParentClass, $sContext);
	}
	if (count($aParentClasses) > 0)
	{
		$sParents = implode(' &gt;&gt; ', $aParentClasses)." &gt;&gt; <b>$sClass</b>";
	}
	else
	{
		$sParents = '';
	}
	$oPage->p("[<a href=\"schema.php?operation=list{$sContext}\">".Dict::S('UI:Schema:AllClasses')."</a>] $sParents");

	if (MetaModel::HasChildrenClasses($sClass))
	{
		$oPage->add("<ul id=\"ClassHierarchy\">");
		$oPage->add("<li class=\"closed\">".$sClass."\n");
		DisplaySubclasses($oPage, $sClass,$sContext);
		$oPage->add("</li>\n");
		$oPage->add("</ul>\n");
		$oPage->add_ready_script('$("#ClassHierarchy").treeview();');	
	}
	$oPage->p('');
	$oPage->AddTabContainer('details');
	$oPage->SetCurrentTabContainer('details');
	// List the attributes of the object
	$aForwardChangeTracking = MetaModel::GetTrackForwardExternalKeys($sClass);
	$aDetails = array();
	foreach(MetaModel::ListAttributeDefs($sClass) as $sAttCode=>$oAttDef)
	{
		if ($oAttDef->IsExternalKey())
		{
		   $sValue = Dict::Format('UI:Schema:ExternalKey_To',MakeClassHLink($oAttDef->GetTargetClass(), $sContext));
			if (array_key_exists($sAttCode, $aForwardChangeTracking))
			{
				$oLinkSet = $aForwardChangeTracking[$sAttCode];
				$sRemoteClass = $oLinkSet->GetHostClass();
				$sValue = $sValue."<span title=\"Forward changes to $sRemoteClass\">*</span>";
			}
		}
		elseif ($oAttDef->IsLinkSet())
		{
			$sValue = MakeClassHLink($oAttDef->GetLinkedClass(), $sContext);
		}
		else
		{
		   $sValue = $oAttDef->GetDescription();
		}
		$sType = $oAttDef->GetType().' ('.$oAttDef->GetTypeDesc().')';
		$sOrigin = MetaModel::GetAttributeOrigin($sClass, $sAttCode);
		$sAllowedValues = "";
		$sMoreInfo = "";

		$aCols = array();
		foreach($oAttDef->GetSQLColumns() as $sCol => $sFieldDesc)
		{
			$aCols[] = "$sCol: $sFieldDesc";
		}
		if (count($aCols) > 0)
		{
			$sCols = implode(', ', $aCols);
	
			$aMoreInfo = array();
			$aMoreInfo[] = Dict::Format('UI:Schema:Columns_Description', $sCols);
			$aMoreInfo[] =  Dict::Format('UI:Schema:Default_Description', $oAttDef->GetDefaultValue());
			$aMoreInfo[] = $oAttDef->IsNullAllowed() ? Dict::S('UI:Schema:NullAllowed') : Dict::S('UI:Schema:NullNotAllowed');
			$sMoreInfo .= implode(', ', $aMoreInfo);
		}

		if ($oAttDef instanceof AttributeEnum)
		{
			// Display localized values for the enum (which depend on the localization provided by the class)
			$aLocalizedValues = MetaModel::GetAllowedValues_att($sClass, $sAttCode, array());
			$aDescription = array();
			foreach($aLocalizedValues as $val => $sDisplay)
			{
				$aDescription[] = htmlentities("$val => ", ENT_QUOTES, 'UTF-8').$sDisplay;
			}
			$sAllowedValues = implode(', ', $aDescription);
		}
		elseif (is_object($oAllowedValuesDef = $oAttDef->GetValuesDef()))
		{
			$sAllowedValues = $oAllowedValuesDef->GetValuesDescription();
		}
		else
		{
			$sAllowedValues = '';
		}

		$aDetails[] = array('code' => $oAttDef->GetCode(), 'type' => $sType, 'origin' => $sOrigin, 'label' => $oAttDef->GetLabel(), 'description' => $sValue, 'values' => $sAllowedValues, 'moreinfo' => $sMoreInfo);
	}
	$oPage->SetCurrentTab(Dict::S('UI:Schema:Attributes'));
	$aConfig = array( 'code' => array('label' => Dict::S('UI:Schema:AttributeCode'), 'description' => Dict::S('UI:Schema:AttributeCode+')),
					  'label' => array('label' => Dict::S('UI:Schema:Label'), 'description' => Dict::S('UI:Schema:Label+')),
					  'type' => array('label' => Dict::S('UI:Schema:Type'), 'description' => Dict::S('UI:Schema:Type+')),
					  'origin' => array('label' => Dict::S('UI:Schema:Origin'), 'description' => Dict::S('UI:Schema:Origin+')),
					  'description' => array('label' => Dict::S('UI:Schema:Description'), 'description' => Dict::S('UI:Schema:Description+')),
					  'values' => array('label' => Dict::S('UI:Schema:AllowedValues'), 'description' => Dict::S('UI:Schema:AllowedValues+')),
					  'moreinfo' => array('label' => Dict::S('UI:Schema:MoreInfo'), 'description' => Dict::S('UI:Schema:MoreInfo+')),
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
	$oPage->SetCurrentTab(Dict::S('UI:Schema:SearchCriteria'));
	$aConfig = array( 'code' => array('label' => Dict::S('UI:Schema:FilterCode'), 'description' => Dict::S('UI:Schema:FilterCode+')),
					  'description' => array('label' => Dict::S('UI:Schema:FilterDescription'), 'description' => Dict::S('UI:Schema:FilterDescription+')),
					  'operators' => array('label' => Dict::S('UI:Schema:AvailOperators'), 'description' => Dict::S('UI:Schema:AvailOperators+'))
	);
	$oPage->table($aConfig, $aDetails);

	$oPage->SetCurrentTab(Dict::S('UI:Schema:ChildClasses'));
	DisplaySubclasses($oPage, $sClass, $sContext);

	$oPage->SetCurrentTab(Dict::S('UI:Schema:ReferencingClasses'));
	DisplayReferencingClasses($oPage, $sClass, $sContext);

	$oPage->SetCurrentTab(Dict::S('UI:Schema:RelatedClasses'));
	DisplayRelatedClasses($oPage, $sClass, $sContext);

	$oPage->SetCurrentTab(Dict::S('UI:Schema:LifeCycle'));
	DisplayLifecycle($oPage, $sClass, $sContext);

	$oPage->SetCurrentTab(Dict::S('UI:Schema:Triggers'));
	DisplayTriggers($oPage, $sClass, $sContext);

	$oPage->SetCurrentTab();
	$oPage->SetCurrentTabContainer();
}


/**
 * Display the details of a given relation (e.g. "impacts")
 */
function DisplayRelationDetails($oPage, $sRelCode, $sContext)
{
	$sDesc = MetaModel::GetRelationDescription($sRelCode);
	$sVerbDown = MetaModel::GetRelationVerbDown($sRelCode);
	$sVerbUp = MetaModel::GetRelationVerbUp($sRelCode);
	$oPage->add("<h1>".Dict::Format('UI:Schema:Relation_Code_Description', $sRelCode, $sDesc)."</h1>");
	$oPage->p(Dict::Format('UI:Schema:RelationDown_Description', $sVerbDown));
	$oPage->p(Dict::Format('UI:Schema:RelationUp_Description', $sVerbUp));

	$oPage->add("<ul id=\"RelationshipDetails\" class=\"treeview\">\n");
	foreach(MetaModel::GetClasses() as $sClass)
	{
		$aRelQueries = MetaModel::EnumRelationQueries($sClass, $sRelCode);
		if (count($aRelQueries) > 0)
		{
			$oPage->add("<li>class ".MakeClassHLink($sClass, $sContext)."\n");
			$oPage->add("<ul>\n");
			foreach ($aRelQueries as $sRelKey => $aQuery)
			{
				$sQuery = $aQuery['sQuery'];
				$iDistance = $aQuery['iDistance'];
				if ($aQuery['bPropagate'])
				{
					$oPage->add("<li>".Dict::Format('UI:Schema:RelationPropagates', $sRelKey, $iDistance, $sQuery)."</li>\n");
				}
				else
				{
					$oPage->add("<li>".Dict::Format('UI:Schema:RelationDoesNotPropagate', $sRelKey, $iDistance, $sQuery)."</li>\n");
				}
			}
			$oPage->add("</ul>\n");
			$oPage->add("</li>\n");
		}
	}
	$oPage->add_ready_script('$("#RelationshipDetails").treeview();');
}


// Display the menu on the left
$oAppContext = new ApplicationContext();
$sContext = $oAppContext->GetForLink();
if (!empty($sContext))
{
	$sContext = '&'.$sContext;
}
$operation = utils::ReadParam('operation', '');

$oPage = new iTopWebPage(Dict::S('UI:Schema:Title'));
$oPage->no_cache();

$operation = utils::ReadParam('operation', '');

switch($operation)
{
	case 'details_class':
	$sClass = utils::ReadParam('class', 'logRealObject', false, 'class');
	DisplayClassDetails($oPage, $sClass, $sContext);
	break;
	
	case 'details_relation':
	$sRelCode = utils::ReadParam('relcode', '');
	DisplayRelationDetails($oPage, $sRelCode, $sContext);
	break;
	
	case 'list':
	default:
	DisplayClassesList($oPage, $sContext);
}

$oPage->output();
?>
