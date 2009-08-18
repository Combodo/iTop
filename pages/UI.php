<?php
require_once('../application/application.inc.php');
require_once('../application/itopwebpage.class.inc.php');
require_once('../application/wizardhelper.class.inc.php');

require_once('../application/startup.inc.php');

$oContext = new UserContext();
$oAppContext = new ApplicationContext();
$iActiveNodeId = utils::ReadParam('menu', '');
if (empty($iActiveNodeId))
{
	// No menu specified, let's get the default one:
	// 1) It's a root menu item (parent_id == 0)
	// 2) with the lowest rank
	$oFilter = DBObjectSearch::FromOQL('SELECT menuNode AS M WHERE M.parent_id = 0');
	if ($oFilter)
	{
		$oMenuSet = new CMDBObjectSet($oFilter);
		while($oMenu = $oMenuSet->Fetch())
		{
			$aRanks[$oMenu->GetKey()] = $oMenu->Get('rank');
		}
		asort($aRanks); // sort by ascending rank: menuId => rank
		$aKeys = array_keys($aRanks);
		$iActiveNodeId = array_shift($aKeys); // Takes the first key, i.e. the menuId with the lowest rank
	}
}
$currentOrganization = utils::ReadParam('org_id', '');
$operation = utils::ReadParam('operation', '');

require_once('../application/loginwebpage.class.inc.php');
login_web_page::DoLogin(); // Check user rights and prompt if needed


$oP = new iTopWebPage("Welcome to ITop", $currentOrganization);

// From now on the context is limited to the the selected organization ??
if ($iActiveNodeId != -1)
{
    $oActiveNode = $oContext->GetObject('menuNode', $iActiveNodeId);
}
else
{
    $oActiveNode = null;
}

switch($operation)
{
	case 'details':
		$sClass = utils::ReadParam('class', '');
		$id = utils::ReadParam('id', '');
		$oSearch = new DBObjectSearch($sClass);
		$oBlock = new DisplayBlock($oSearch, 'search', false);
		$oBlock->Display($oP, 0);
		if ( empty($sClass) || empty($id)) // TO DO: check that the class name is valid !
		{
			$oP->add("<p>'class' and 'id' parameters must be specifed for this operation.</p>\n");
		}
		else
		{
			$oObj = $oContext->GetObject($sClass, $id);
			if ($oObj != null)
			{
				$oP->set_title("iTop - ".$oObj->GetDisplayName()." - $sClass details");
				$oObj->DisplayDetails($oP);
			}
			else
			{
				$oP->set_title("iTop - Error");
				$oP->add("<p>Sorry this object does not exist (or you are not allowed to view it).</p>\n");
			}
		}
	break;
	
	case 'search_form':
		$sOQLClass = utils::ReadParam('oql_class', '');
		$sOQLClause = utils::ReadParam('oql_clause', '');
		$sFormat = utils::ReadParam('format', '');
		$bSearchForm = utils::ReadParam('search_form', true);
		if (empty($sOQLClass))
		{
			$oP->set_title("iTop - Error");
			$oP->add("<p>'oql_class' must be specifed for this operation.</p>\n");
		}
		else
		{
			$oP->set_title("iTop - Search results");
			$sOQL = "SELECT $sOQLClass $sOQLClause";
			try
			{
				$oFilter = DBObjectSearch::FromOQL($sOQL); // To Do: Make sure we don't bypass security
				$oSet = new DBObjectSet($oFilter);
				if ($bSearchForm)
				{
					$oBlock = new DisplayBlock($oFilter, 'search', false);
					$oBlock->Display($oP, 0);
				}
				if (strtolower($sFormat) == 'csv')
				{
					$oBlock = new DisplayBlock($oFilter, 'csv', false);
					$oBlock->Display($oP, 0);
				}
				else
				{
					$oBlock = new DisplayBlock($oFilter, 'list', false);
					$oBlock->Display($oP, 0);
				}
			}
			catch(CoreException $e)
			{
				$oFilter = new DBObjectSearch($sOQLClass); // To Do: Make sure we don't bypass security
				$oSet = new DBObjectSet($oFilter);
				if ($bSearchForm)
				{
					$oBlock = new DisplayBlock($oFilter, 'search', false);
					$oBlock->Display($oP, 0);
				}
				$oP->P("<b>Error incorrect OQL query:</b>");
				$oP->P($e->getHtmlDesc());
			}
			catch(Exception $e)
			{
				$oP->p('<b>An error occured while running the query:</b>');
				$oP->p($e->getMessage());
			}
		}
	break;
	
	case 'search':
		$sFilter = utils::ReadParam('filter', '');
		$sFormat = utils::ReadParam('format', '');
		$bSearchForm = utils::ReadParam('search_form', true);
		if (empty($sFilter))
		{
			$oP->set_title("iTop - Error");
			$oP->add("<p>'filter' must be specifed for this operation.</p>\n");
		}
		else
		{
			$oP->set_title("iTop - Search results");
			// TO DO: limit the search filter by the user context
			$oFilter = CMDBSearchFilter::unserialize($sFilter); // TO DO : check that the filter is valid
			$oSet = new DBObjectSet($oFilter);
			if ($bSearchForm)
			{
				$oBlock = new DisplayBlock($oFilter, 'search', false);
				$oBlock->Display($oP, 0);
			}
			if (strtolower($sFormat) == 'csv')
			{
				$oBlock = new DisplayBlock($oFilter, 'csv', false);
				$oBlock->Display($oP, 0);
			}
			else
			{
				$oBlock = new DisplayBlock($oFilter, 'list', false);
				$oBlock->Display($oP, 0);
			}
		}
	break;
	
	case 'full_text':
		$sFullText = trim(utils::ReadParam('text', ''));
		if (empty($sFullText))
		{
			$oP->p('Nothing to search.');
		}
		else
		{
			$oP->p("<h2>Results for '$sFullText':</h2>\n");
			$iCount = 0;
			// Search in full text mode in all the classes
			foreach(MetaModel::GetClasses('bizmodel') as $sClassName)
			{
				$oFilter = new DBObjectSearch($sClassName);
				$oFilter->AddCondition_FullText($sFullText);
				$oSet = new DBObjectSet($oFilter);
				if ($oSet->Count() > 0)
				{
					$aLeafs = array();
					while($oObj = $oSet->Fetch())
					{
						if (get_class($oObj) == $sClassName)
						{
							$aLeafs[] = $oObj->GetKey();
						}
					}
					$oLeafsFilter = new DBObjectSearch($sClassName);
					if (count($aLeafs) > 0)
					{
						$iCount += count($aLeafs);
						$oP->add("<div class=\"page_header\">\n");
						$oP->add("<h1><span class=\"hilite\">".Metamodel::GetName($sClassName).":</span> ".count($aLeafs)." object(s) found.</h1>\n");
						$oP->add("</div>\n");
						$oLeafsFilter->AddCondition('pkey', $aLeafs, 'IN');
						$oBlock = new DisplayBlock($oLeafsFilter, 'list', false);
						$oBlock->Display($oP, 0);
					}
				}
			}
			if ($iCount == 0)
			{
				$oP->p('No object found.');
			}
		}	
	break;
	
	case 'modify':
		$oP->add_linked_script("../js/json.js");
		$oP->add_linked_script("../js/forms-json-utils.js");
		$oP->add_linked_script("../js/wizardhelper.js");
		$oP->add_linked_script("../js/wizard.utils.js");
		$oP->add_linked_script("../js/linkswidget.js");
		$oP->add_linked_script("../js/jquery.blockUI.js");
		$sClass = utils::ReadParam('class', '');
		$id = utils::ReadParam('id', '');
		if ( empty($sClass) || empty($id)) // TO DO: check that the class name is valid !
		{
			$oP->add("<p>'class' and 'id' parameters must be specifed for this operation.</p>\n");
		}
		else
		{
			// Check if the user can modify this object
			$oSearch = new DBObjectSearch($sClass);
			$oSearch->AddCondition('pkey', $id, '=');
			$oSet = new CMDBObjectSet($oSearch);
			if ($oSet->Count() > 0)
			{
				$oObj = $oSet->Fetch();
			}
			
			$bIsModifiedAllowed = (UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY, $oSet) == UR_ALLOWED_YES);
			$bIsReadAllowed = (UserRights::IsActionAllowed($sClass, UR_ACTION_READ, $oSet) == UR_ALLOWED_YES);
			if( ($oObj != null) && ($bIsModifiedAllowed) && ($bIsReadAllowed))
			{
				$oP->set_title("iTop - ".$oObj->GetName()." - $sClass modification");
				$oP->add("<h1>".$oObj->GetName()." - $sClass modification</h1>\n");
				$oObj->DisplayModifyForm($oP);
			}
			else
			{
				$oP->set_title("iTop - Error");
				$oP->add("<p>Sorry this object does not exist (or you are not allowed to view it).</p>\n");
			}
		}
	break;
	
	case 'clone':
	$sClass = utils::ReadParam('class', '');
	$id = utils::ReadParam('id', '');
	if ( empty($sClass) || empty($id)) // TO DO: check that the class name is valid !
	{
		$oP->add("<p>'class' and 'id' parameters must be specifed for this operation.</p>\n");
	}
	else
	{
		// Check if the user can modify this object
		$oSearch = new DBObjectSearch($sClass);
		$oSearch->AddCondition('pkey', $id, '=');
		$oSet = new CMDBObjectSet($oSearch);
		if ($oSet->Count() > 0)
		{
			$oObjToClone = $oSet->Fetch();
		}
		
		$bIsModifiedAllowed = (UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY, $oSet) == UR_ALLOWED_YES);
		$bIsReadAllowed = (UserRights::IsActionAllowed($sClass, UR_ACTION_READ, $oSet) == UR_ALLOWED_YES);
		if( ($oObjToClone != null) && ($bIsModifiedAllowed) && ($bIsReadAllowed))
		{
			$oP->set_title("iTop - ".$oObjToClone->GetName()." - $sClass clone");
			$oP->add("<h1>".$oObjToClone->GetName()." - $sClass clone</h1>\n");
			cmdbAbstractObject::DisplayCreationForm($oP, $sClass, $oObjToClone);
		}
		else
		{
			$oP->set_title("iTop - Error");
			$oP->add("<p>Sorry this object does not exist (or you are not allowed to view it).</p>\n");
		}
	}
	break;
	
	case 'new':
		$sClass = utils::ReadParam('class', '');
		$sStateCode = utils::ReadParam('state', '');
		if ( empty($sClass) )
		{
			$oP->p("The class must be specified for this operation!");
		}
		else
		{
			$oP->add_linked_script("../js/json.js");
			$oP->add_linked_script("../js/forms-json-utils.js");
			$oP->add_linked_script("../js/wizardhelper.js");
			$oP->add_linked_script("../js/wizard.utils.js");
			$oP->add_linked_script("../js/linkswidget.js");
			$oP->add_linked_script("../js/jquery.blockUI.js");
			$oWizard = new UIWizard($oP, $sClass, $sStateCode);
			$sStateCode = $oWizard->GetTargetState(); // Will computes the default state if none was supplied
			$sClassLabel = MetaModel::GetName($sClass);
			$oP->p("<h2>Creation of a new $sClassLabel</h2>");
			if (!empty($sStateCode))
			{
				$aStates = MetaModel::EnumStates($sClass);
				$sStateLabel = $aStates[$sStateCode]['label'];
			}
			$aWizardSteps = $oWizard->GetWizardStructure();
			
			// Display the structure of the wizard
			$iStepIndex = 1;
			$iMaxInputId = 0;
			$aFieldsMap = array();
			foreach($aWizardSteps['mandatory'] as $aSteps)
			{
				$oP->SetCurrentTab("Step $iStepIndex *");
				$oWizard->DisplayWizardStep($aSteps, $iStepIndex, $iMaxInputId, $aFieldsMap);
				//$oP->add("</div>\n");
				$iStepIndex++;
			}	
			foreach($aWizardSteps['optional'] as $aSteps)
			{
				$oP->SetCurrentTab("Step $iStepIndex *");
				$oWizard->DisplayWizardStep($aSteps, $iStepIndex, $iMaxInputId, $aFieldsMap, true); // true means enable the finish button
				//$oP->add("</div>\n");
				$iStepIndex++;
			}
			$oWizard->DisplayFinalStep($iStepIndex, $aFieldsMap);	
			
			$oAppContext = new ApplicationContext();
			$oContext = new UserContext();
			$oObj = null;
			if (!empty($id))
			{
				$oObj = $oContext->GetObject($sClass, $id);
			}
			if (!is_object($oObj))
			{
				// new object or or that can't be retrieved (corrupted id or object not allowed to this user)
				$id = '';
				$oObj = MetaModel::NewObject($sClass);
			}
			$oP->add("<script>
			// Fill the map between the fields of the form and the attributes of the object\n");
			
			$aNewFieldsMap = array();
			foreach($aFieldsMap as $id => $sFieldCode)
			{
				$aNewFieldsMap[$sFieldCode] = $id;
			}
			$sJsonFieldsMap = json_encode($aNewFieldsMap);
		
			$oP->add("
			// Initializes the object once at the beginning of the page...
			var oWizardHelper = new WizardHelper('$sClass');
			oWizardHelper.SetFieldsMap($sJsonFieldsMap);
		
			ActivateStep(1);
			</script>\n");
		}
	break;
	
	case 'apply_modify':
		$sClass = utils::ReadPostedParam('class', '');
		$id = utils::ReadPostedParam('id', '');
		$sTransactionId = utils::ReadPostedParam('transaction_id', '');
		if ( empty($sClass) || empty($id)) // TO DO: check that the class name is valid !
		{
			$oP->add("<p>'class' and 'id' parameters must be specifed for this operation.</p>\n");
		}
		else if (!utils::IsTransactionValid($sTransactionId))
		{
			$oP->p("<strong>Error: object has already be updated!</strong>\n");
		}
		else
		{
			$oObj = $oContext->GetObject($sClass, $id);
			if ($oObj != null)
			{
				$oP->set_title("iTop - ".$oObj->GetName()." - $sClass modification");
				$oP->add("<h1>".$oObj->GetName()." - $sClass modification</h1>\n");
				$bObjectModified = false;
				foreach(MetaModel::ListAttributeDefs(get_class($oObj)) as $sAttCode=>$oAttDef)
				{
					$iFlags = $oObj->GetAttributeFlags($sAttCode);
					if ($iFlags & (OPT_ATT_HIDDEN | OPT_ATT_READONLY))
					{
						// Non-visible, or read-only attribute, do nothing
					}
					else if ($oAttDef->IsLinkSet())
					{
						// Link set, the data is a set of link objects, encoded in JSON
						$aAttributes[$sAttCode] = trim(utils::ReadPostedParam("attr_$sAttCode", ''));
						if (!empty($aAttributes[$sAttCode]))
						{
							$oLinkSet = WizardHelper::ParseJsonSet($oObj, $oAttDef->GetLinkedClass(), $oAttDef->GetExtKeyToMe(), $aAttributes[$sAttCode]);
							$oObj->Set($sAttCode, $oLinkSet);
							// TO DO: detect a real modification, for now always update !!
							$bObjectModified = true;
						}
					}
					else if (!$oAttDef->IsExternalField())
					{
						$aAttributes[$sAttCode] = trim(utils::ReadPostedParam("attr_$sAttCode", ''));
						$previousValue = $oObj->Get($sAttCode);
						if (!empty($aAttributes[$sAttCode]) && ($previousValue != $aAttributes[$sAttCode]))
						{
							$oObj->Set($sAttCode, $aAttributes[$sAttCode]);
							$bObjectModified = true;
						}
					}
				}
				if (!$bObjectModified)
				{
					$oP->p("No modification detected. ".get_class($oObj)." has <strong>not</strong> been updated.\n");
				}
				else if ($oObj->CheckToUpdate())
				{
					$oMyChange = MetaModel::NewObject("CMDBChange");
					$oMyChange->Set("date", time());
					if (UserRights::GetUser() != UserRights::GetRealUser())
					{
						$sUserString = UserRights::GetRealUser()." on behalf of ".UserRights::GetUser();
					}
					else
					{
						$sUserString = UserRights::GetUser();
					}
					$oMyChange->Set("userinfo", $sUserString);
					$iChangeId = $oMyChange->DBInsert();
					$oObj->DBUpdateTracked($oMyChange);
			
					$oP->p(get_class($oObj)." updated.\n");
				}
				else
				{
					$oP->p("<strong>Error: object can not be updated!</strong>\n");
					//$oObj->Reload(); // restore default values!
				}
			}
			else
			{
				$oP->set_title("iTop - Error");
				$oP->add("<p>Sorry this object does not exist (or you are not allowed to edit it).</p>\n");
			}
		}
		$oObj->DisplayDetails($oP);
	break;
	
	case 'delete':
	$sClass = utils::ReadParam('class', '');
	$id = utils::ReadParam('id', '');
	$oObj = $oContext->GetObject($sClass, $id);
	$sName = $oObj->GetName();
	$oMyChange = MetaModel::NewObject("CMDBChange");
	$oMyChange->Set("date", time());
	if (UserRights::GetUser() != UserRights::GetRealUser())
	{
		$sUserString = UserRights::GetRealUser()." on behalf of ".UserRights::GetUser();
	}
	else
	{
		$sUserString = UserRights::GetUser();
	}
	$oMyChange->Set("userinfo", $sUserString);
	$oMyChange->DBInsert();
	$oObj->DBDeleteTracked($oMyChange);
	$oP->add("<h1>".$sName." - $sClass deleted</h1>\n");
	break;
	
	case 'apply_new':
	$oP->p('Creation of the object');
	$oP->p('Obsolete, should now go through the wizard...');
	break;
	
	case 'apply_clone':
	$sClass = utils::ReadPostedParam('class', '');
	$iCloneId = utils::ReadPostedParam('clone_id', '');
	$sTransactionId = utils::ReadPostedParam('transaction_id', '');
	if (!utils::IsTransactionValid($sTransactionId))
	{
		$oP->p("<strong>Error: object has already be cloned!</strong>\n");
	}
	else
	{
			$oObj = $oContext->GetObject($sClass, $iCloneId);
			$oMyChange = MetaModel::NewObject("CMDBChange");
			$oMyChange->Set("date", time());
			if (UserRights::GetUser() != UserRights::GetRealUser())
			{
				$sUserString = UserRights::GetRealUser()." on behalf of ".UserRights::GetUser();
			}
			else
			{
				$sUserString = UserRights::GetUser();
			}
			$oMyChange->Set("userinfo", $sUserString);
			$iChangeId = $oMyChange->DBInsert();
			$sStateAttCode = MetaModel::GetStateAttributeCode(get_class($oObj));
			foreach(MetaModel::ListAttributeDefs($sClass) as $sAttCode=>$oAttDef)
			{
				if ( ('finalclass' != $sAttCode) && // finalclass is a reserved word, hardcoded !
				     ($sStateAttCode != $sAttCode) &&
					  (!$oAttDef->IsExternalField()) )
				{
					$value = utils::ReadPostedParam('attr_'.$sAttCode, '');
					$oObj->Set($sAttCode, $value);
				}
			}
			$oObj->DBCloneTracked($oMyChange);
			$oP->add("<h1>".$oObj->GetName()." - $sClass created</h1>\n");
			$oObj->DisplayDetails($oP);
	}

	break;
	
	case 'wizard_apply_new':
	$sJson = utils::ReadPostedParam('json_obj', '');
	$oWizardHelper = WizardHelper::FromJSON($sJson);
	$sTransactionId = utils::ReadPostedParam('transaction_id', '');
	if (!utils::IsTransactionValid($sTransactionId))
	{
		$oP->p("<strong>Error: object has already be created!</strong>\n");
	}
	else
	{
		$oObj = $oWizardHelper->GetTargetObject();
		if (is_object($oObj))
		{
			$sClass = get_class($oObj);
			$oMyChange = MetaModel::NewObject("CMDBChange");
			$oMyChange->Set("date", time());
			if (UserRights::GetUser() != UserRights::GetRealUser())
			{
				$sUserString = UserRights::GetRealUser()." on behalf of ".UserRights::GetUser();
			}
			else
			{
				$sUserString = UserRights::GetUser();
			}
			$oMyChange->Set("userinfo", $sUserString);
			$iChangeId = $oMyChange->DBInsert();
			$oObj->DBInsertTracked($oMyChange);
			$oP->set_title("iTop - ".$oObj->GetName()." - $sClass created");
			$oP->add("<h1>".$oObj->GetName()." - $sClass created</h1>\n");
			$oObj->DisplayDetails($oP);
		}
	}
	break;
	
	case 'stimulus':
	$sClass = utils::ReadParam('class', '');
	$id = utils::ReadParam('id', '');
	$sStimulus = utils::ReadParam('stimulus', '');
	if ( empty($sClass) || empty($id) ||  empty($sStimulus) ) // TO DO: check that the class name is valid !
	{
		$oP->add("<p>'class', 'id' and 'stimulus' parameters must be specifed for this operation.</p>\n");
	}
	else
	{
		$oObj = $oContext->GetObject($sClass, $id);
		if ($oObj != null)
		{
			$aTransitions = $oObj->EnumTransitions();
			$aStimuli = MetaModel::EnumStimuli($sClass);
			if (!isset($aTransitions[$sStimulus]))
			{
				$oP->add("<p><strong>Error:</strong> Invalid stimulus: '$sStimulus' on object: {$oObj->GetName()} in state {$oObj->GetState()}.</p>\n");
			}
			else
			{
				$sActionLabel = $aStimuli[$sStimulus]->Get('label');
				$sActionDetails = $aStimuli[$sStimulus]->Get('description');
				$aTransition = $aTransitions[$sStimulus];
				$sTargetState = $aTransition['target_state'];
				$aTargetStates = MetaModel::EnumStates($sClass);
				$oP->add("<div class=\"page_header\">\n");
				$oP->add("<h1>$sActionLabel - <span class=\"hilite\">{$oObj->GetName()}</span></h1>\n");
				//$oP->add("<p>Applying '$sActionLabel' on object: {$oObj->GetName()} in state {$oObj->GetState()} to target state: $sTargetState.</p>\n");
				$oP->add("</div>\n");
				$oObj->DisplayBareDetails($oP);
				$aTargetState = $aTargetStates[$sTargetState];
				//print_r($aTransitions[$sStimulus]);
				//print_r($aTargetState);
				$aExpectedAttributes = $aTargetState['attribute_list'];
				$oP->add("<div class=\"wizHeader\">\n");
				$oP->add("<h1>$sActionDetails</h1>\n");
				$oP->add("<div class=\"wizContainer\">\n");
				$oP->add("<form method=\"post\">\n");
				$aDetails = array();
				foreach($aExpectedAttributes as $sAttCode => $iExpectCode)
				{
					// Prompt for an attribute if
					// - the attribute must be changed or must be displayed to the user for confirmation
					// - or the field is mandatory and currently empty
					if ( ($iExpectCode & (OPT_ATT_MUSTCHANGE | OPT_ATT_MUSTPROMPT)) ||
						 (($iExpectCode & OPT_ATT_MANDATORY) && ($oObj->Get($sAttCode) == '')) ) 
					{
						$aAttributesDef = MetaModel::ListAttributeDefs($sClass);
						$oAttDef = $aAttributesDef[$sAttCode];
						$sHTMLValue = cmdbAbstractObject::GetFormElementForField($oP, $sClass, $sAttCode, $oAttDef, $oObj->Get($sAttCode), $oObj->GetDisplayValue($sAttCode));
						$aDetails[] = array('label' => $oAttDef->GetLabel(), 'value' => $sHTMLValue);
					}
				}
				$oP->details($aDetails);
				$oP->add("<input type=\"hidden\" name=\"id\" value=\"$id\">\n");
				$oP->add("<input type=\"hidden\" name=\"class\" value=\"$sClass\">\n");
				$oP->add("<input type=\"hidden\" name=\"operation\" value=\"apply_stimulus\">\n");
				$oP->add("<input type=\"hidden\" name=\"stimulus\" value=\"$sStimulus\">\n");
				$oP->add("<input type=\"hidden\" name=\"transaction_id\" value=\"".utils::GetNewTransactionId()."\">\n");
				$oP->add($oAppContext->GetForForm());
				$oP->add("<button type=\"button\" class=\"action\" onClick=\"goBack()\"><span>Cancel</span></button>&nbsp;&nbsp;&nbsp;&nbsp;\n");
				$oP->add("<button type=\"submit\" class=\"action\"><span>$sActionLabel</span></button>\n");
				$oP->add("</form>\n");
				$oP->add("</div>\n");
				$oP->add("</div>\n");
			}
		}
		else
		{
			$oP->set_title("iTop - Error");
			$oP->add("<p>Sorry this object does not exist (or you are not allowed to edit it).</p>\n");
		}		
	}
	break;

	case 'apply_stimulus':
	$sClass = utils::ReadPostedParam('class', '');
	$id = utils::ReadPostedParam('id', '');
	$sTransactionId = utils::ReadPostedParam('transaction_id', '');
	$sStimulus = utils::ReadPostedParam('stimulus', '');
	if ( empty($sClass) || empty($id) ||  empty($sStimulus) ) // TO DO: check that the class name is valid !
	{
		$oP->add("<p>'class', 'id' and 'stimulus' parameters must be specifed for this operation.</p>\n");
	}
	else
	{
		$oObj = $oContext->GetObject($sClass, $id);
		if ($oObj != null)
		{
			$aTransitions = $oObj->EnumTransitions();
			$aStimuli = MetaModel::EnumStimuli($sClass);
			if (!isset($aTransitions[$sStimulus]))
			{
				$oP->add("<p><strong>Error:</strong> Invalid stimulus: '$sStimulus' on object: {$oObj->GetName()} in state {$oObj->GetState()}.</p>\n");
			}
			else if (!utils::IsTransactionValid($sTransactionId))
			{
				$oP->p("<strong>Error: object has already be updated!</strong>\n");
			}
			else
			{
				$sActionLabel = $aStimuli[$sStimulus]->Get('label');
				$sActionDetails = $aStimuli[$sStimulus]->Get('description');
				$aTransition = $aTransitions[$sStimulus];
				$sTargetState = $aTransition['target_state'];
				$aTargetStates = MetaModel::EnumStates($sClass);
				$oP->add("<div class=\"page_header\">\n");
				$oP->add("<h1>$sActionLabel - <span class=\"hilite\">{$oObj->GetName()}</span></h1>\n");
				$oP->add("<p>$sActionDetails</p>\n");
				$oP->add("<p>Applying '$sActionLabel' on object: {$oObj->GetName()} in state {$oObj->GetState()} to target state: $sTargetState.</p>\n");
				$oP->add("</div>\n");
				$aTargetState = $aTargetStates[$sTargetState];
				//print_r($aTransitions[$sStimulus]);
				//print_r($aTargetState);
				$aExpectedAttributes = $aTargetState['attribute_list'];
				$aDetails = array();
				foreach($aExpectedAttributes as $sAttCode => $iExpectCode)
				{
					if (($iExpectCode & OPT_ATT_MUSTCHANGE) || ($oObj->Get($sAttCode) == '') ) 
					{
						$paramValue = utils::ReadPostedParam("attr_$sAttCode", '');
						$oObj->Set($sAttCode, $paramValue);
					}
				}
				if ($oObj->ApplyStimulus($sStimulus) && $oObj->CheckToUpdate())
				{
					$oMyChange = MetaModel::NewObject("CMDBChange");
					$oMyChange->Set("date", time());
					if (UserRights::GetUser() != UserRights::GetRealUser())
					{
						$sUserString = UserRights::GetRealUser()." on behalf of ".UserRights::GetUser();
					}
					else
					{
						$sUserString = UserRights::GetUser();
					}
					$oMyChange->Set("userinfo", $sUserString);
					$iChangeId = $oMyChange->DBInsert();
					$oObj->DBUpdateTracked($oMyChange);
			
					$oP->p(get_class($oObj)." updated.\n");
				}
				$oObj->DisplayDetails($oP);
			}
		}
		else
		{
			$oP->set_title("iTop - Error");
			$oP->add("<p>Sorry this object does not exist (or you are not allowed to edit it).</p>\n");
		}		
	}
	break;

	
	default:
	$oActiveNode->RenderContent($oP, $oAppContext->GetAsHash());
}
$oP->output();
?>
