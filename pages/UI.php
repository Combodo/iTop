<?php
require_once('../application/application.inc.php');
require_once('../application/itopwebpage.class.inc.php');
require_once('../application/wizardhelper.class.inc.php');

require_once('../application/startup.inc.php');

$oContext = new UserContext();
$oAppContext = new ApplicationContext();
$iActiveNodeId = utils::ReadParam('menu', '');
if (empty($iActiveNodeId) && !is_numeric($iActiveNodeId))
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
		$sClassLabel = MetaModel::GetName($sClass);
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
				$oP->set_title("iTop - ".$oObj->GetDisplayName()." - $sClassLabel details");
				$oObj->DisplayDetails($oP);
			}
			else
			{
				$oP->set_title("iTop - Error");
				$oP->add("<p>Sorry this object does not exist (or you are not allowed to view it).</p>\n");
			}
		}
	break;
	
	case 'search_oql':
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
					$oBlock->Display($oP, 'csv');
					$oPage->add_ready_script(" $('#csv').css('height', '95%');"); // adjust the size of the block
				}
				else
				{
					$oBlock = new DisplayBlock($oFilter, 'list', false);
					$oBlock->Display($oP, 1);
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
	case 'search_form':
		$sClass = utils::ReadParam('class', '');
		$sFormat = utils::ReadParam('format', 'html');
		$bSearchForm = utils::ReadParam('search_form', true);
		if (empty($sClass))
		{
			$oP->set_title("iTop - Error");
			$oP->add("<p>'class' must be specifed for this operation.</p>\n");
		}
		else
		{
			$oP->set_title("iTop - Search results");
			$oFilter =  $oContext->NewFilter($sClass);
			$oSet = new DBObjectSet($oFilter);
			if ($bSearchForm)
			{
				$oBlock = new DisplayBlock($oFilter, 'search', false /* Asynchronous */, array('open' => true));
				$oBlock->Display($oP, 0);
			}
			if (strtolower($sFormat) == 'csv')
			{
				$oBlock = new DisplayBlock($oFilter, 'csv', false);
				$oBlock->Display($oP, 1);
				$oP->add_ready_script(" $('#csv').css('height', '95%');"); // adjust the size of the block
			}
			else
			{
				$oBlock = new DisplayBlock($oFilter, 'list', false);
				$oBlock->Display($oP, 1);
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
				$oBlock->Display($oP, 'csv');
				$oP->add_ready_script(" $('#csv').css('height', '95%');"); // adjust the size of the block
			}
			else
			{
				$oBlock = new DisplayBlock($oFilter, 'list', false);
				$oBlock->Display($oP, 1);
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
			$iBlock = 0;
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
						$oBlock->Display($oP, $iBlock++);
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
		$sClassLabel = MetaModel::GetName($sClass);
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
				$oP->set_title("iTop - ".$oObj->GetName()." - $sClassLabel modification");
				$oP->add("<div class=\"page_header\">\n");
				$oP->add("<h1>Modification of $sClassLabel: <span class=\"hilite\">".$oObj->GetName()."</span></h1>\n");
				$oP->add("</div>\n");

				$oP->add("<div class=\"wizContainer\">\n");
				$oObj->DisplayModifyForm($oP);
				$oP->add("</div>\n");
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
	$sClassLabel = MetaModel::GetName($sClass);
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
			$oP->add_linked_script("../js/json.js");
			$oP->add_linked_script("../js/forms-json-utils.js");
			$oP->add_linked_script("../js/wizardhelper.js");
			$oP->add_linked_script("../js/wizard.utils.js");
			$oP->add_linked_script("../js/linkswidget.js");
			$oP->add_linked_script("../js/jquery.blockUI.js");
			$oP->set_title("iTop - ".$oObjToClone->GetName()." - $sClassLabel clone");
			$oP->add("<div class=\"page_header\">\n");
			$oP->add("<h1>Clone of $sClassLabel: <span class=\"hilite\">".$oObjToClone->GetName()."</span></h1>\n");
			$oP->add("</div>\n");

			$oP->add("<div class=\"wizContainer\">\n");
			cmdbAbstractObject::DisplayCreationForm($oP, $sClass, $oObjToClone);
			$oP->add("</div>\n");
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
			$oP->add("<h2>Creation of a new $sClassLabel</h2>");
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
				// new object or that can't be retrieved (corrupted id or object not allowed to this user)
				$id = '';
				$oObj = MetaModel::NewObject($sClass);
			}
			$oP->add("<script type=\"text/javascript\">
			// Fill the map between the fields of the form and the attributes of the object\n");
			
			$aNewFieldsMap = array();
			foreach($aFieldsMap as $id => $sFieldCode)
			{
				$aNewFieldsMap[$sFieldCode] = $id;
			}
			$iFieldsCount = count($aFieldsMap);
			$sJsonFieldsMap = json_encode($aNewFieldsMap);
		
			$oP->add("
			// Initializes the object once at the beginning of the page...
			var oWizardHelper = new WizardHelper('$sClass');
			oWizardHelper.SetFieldsMap($sJsonFieldsMap);
			oWizardHelper.SetFieldsCount($iFieldsCount);
		
			ActivateStep(1);
			</script>\n");
		}
	break;
	
	case 'apply_modify':
		$sClass = utils::ReadPostedParam('class', '');
		$sClassLabel = MetaModel::GetName($sClass);
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
				$oP->set_title("iTop - ".$oObj->GetName()." - $sClassLabel modification");
				$oP->add("<h1>".$oObj->GetName()." - $sClassLabel modification</h1>\n");
				$bObjectModified = false;
				foreach(MetaModel::ListAttributeDefs(get_class($oObj)) as $sAttCode=>$oAttDef)
				{
					$iFlags = $oObj->GetAttributeFlags($sAttCode);
					if ($iFlags & (OPT_ATT_HIDDEN | OPT_ATT_READONLY))
					{
						// Non-visible, or read-only attribute, do nothing
					}
					else if ($sAttCode == 'finalclass')
					{
						// This very specific field is read-only
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
					else if ($oAttDef->GetEditClass() == 'Document')
					{
						// There should be an uploaded file with the named attr_<attCode>
						$oDocument = utils::ReadPostedDocument('file_'.$sAttCode);
						if (!$oDocument->IsEmpty())
						{
							// A new file has been uploaded
							$oObj->Set($sAttCode, $oDocument);
							$bObjectModified = true;
						}
					}
					else if (!$oAttDef->IsExternalField())
					{
						$rawValue = utils::ReadPostedParam("attr_$sAttCode", null);
						if (!is_null($rawValue))
						{
							$aAttributes[$sAttCode] = trim($rawValue);
							$previousValue = $oObj->Get($sAttCode);
							if ($previousValue != $aAttributes[$sAttCode])
							{
								$oObj->Set($sAttCode, $aAttributes[$sAttCode]);
								$bObjectModified = true;
							}
						}
					}
				}
				if (!$bObjectModified)
				{
					$oP->p("No modification detected. ".MetaModel::GetName(get_class($oObj))." has <strong>not</strong> been updated.\n");
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
			
					$oP->p(MetaModel::GetName(get_class($oObj))." updated.\n");
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
	case 'delete_confirmed':
	$sClass = utils::ReadParam('class', '');
	$sClassLabel = MetaModel::GetName($sClass);
	$id = utils::ReadParam('id', '');
	$oObj = $oContext->GetObject($sClass, $id);
	$sName = $oObj->GetName();

	if (!UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY, DBObjectSet::FromObject($oObj)))
	{
		throw new SecurityException('You are not allowed to do delete objects of class '.$sClass);
	}

	// Evaluate the impact on the DB integrity
	//
	list ($aDeletedObjs, $aResetedObjs) = $oObj->GetDeletionScheme();

	// Evaluate feasibility (user access control)
	//
	$bFoundManual = false;
	$bFoundStopper = false;
	$iTotalDelete = 0; // count of object that must be deleted
	$iTotalReset = 0; // count of object for which an ext key will be reset (to 0)
	foreach ($aDeletedObjs as $sRemoteClass => $aDeletes)
	{
		$iTotalDelete += count($aDeletes);
		foreach ($aDeletes as $iId => $aData)
		{
			$oToDelete = $aData['to_delete'];
			$bDeleteAllowed = UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY, DBObjectSet::FromObject($oToDelete));
			if (!$bDeleteAllowed)
			{
				$aDeletedObjs[$sRemoteClass][$iId]['issue'] = 'not allowed to delete this object';
				$bFoundStopper = true;
			}

			$bAutoDel = $aData['auto_delete'];
			if (!$bAutoDel)
			{
				$bFoundManual = true;
			}
		}
	}
	foreach ($aResetedObjs as $sRemoteClass => $aToReset)
	{
		$iTotalReset += count($aToReset);
		foreach ($aToReset as $iId => $aData)
		{
			$oToReset = $aData['to_reset'];
			$aExtKeyLabels = array();
			$aForbiddenKeys = array(); // keys on which the current user is not allowed to write
			foreach ($aData['attributes'] as $sRemoteExtKey => $aRemoteAttDef)
			{
				$bUpdateAllowed = UserRights::IsActionAllowedOnAttribute($sClass, $sRemoteExtKey, UR_ACTION_MODIFY, DBObjectSet::FromObject($oToReset));
				if (!$bUpdateAllowed)
				{
					$bFoundStopper = true;
					$aForbiddenKeys[] = $aRemoteAttDef->GetLabel();
				}
				$aExtKeyLabels[] = $aRemoteAttDef->GetLabel();
			}
			$aResetedObjs[$sRemoteClass][$iId]['attributes_list'] = implode(', ', $aExtKeyLabels); 
			if (count($aForbiddenKeys) > 0)
			{
				$aResetedObjs[$sRemoteClass][$iId]['issue'] = 'you are not allowed to update some fields: '.implode(', ', $aForbiddenKeys);
			}
		}
	}
	// Count of dependent objects (+ the current one)
	$iTotalTargets = $iTotalDelete + $iTotalReset;

	if ($operation == 'delete_confirmed')
	{
		$oP->add("<h1>Deletion of ".$oObj->GetName()."</h1>\n");
		// Security - do not allow the user to force a forbidden delete by the mean of page arguments...
		if ($bFoundStopper)
		{
			throw new SecurityException('This object could not be deleted because the current user do not have sufficient rights');
		}
		if ($bFoundManual)
		{
			throw new SecurityException('This object could not be deleted because some manual operations must be performed prior to that');
		}

		// Prepare the change reporting
		//
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

		// Delete dependencies
		//
		$aDisplayData = array();
		foreach ($aDeletedObjs as $sRemoteClass => $aDeletes)
		{
			foreach ($aDeletes as $iId => $aData)
			{
				$oToDelete = $aData['to_delete'];

				$aDisplayData[] = array(
					'class' => MetaModel::GetName(get_class($oToDelete)),
					'object' => $oToDelete->GetHyperLink(),
					'consequence' => 'automatically deleted',
				);

				$oToDelete->DBDeleteTracked($oMyChange);
			}
		}
		
		// Update dependencies
		//
		foreach ($aResetedObjs as $sRemoteClass => $aToReset)
		{
			foreach ($aToReset as $iId => $aData)
			{
				$oToReset = $aData['to_reset'];
				$aDisplayData[] = array(
					'class' => MetaModel::GetName(get_class($oToReset)),
					'object' => $oToReset->GetHyperLink(),
					'consequence' => 'automatic reset of: '.$aData['attributes_list'],
				);

				foreach ($aData['attributes'] as $sRemoteExtKey => $aRemoteAttDef)
				{
					$oToReset->Set($sRemoteExtKey, 0);
					$oToReset->DBUpdateTracked($oMyChange);
				}
			}
		}

		// Report automatic jobs
		//
		if ($iTotalTargets > 0)
		{
			$oP->p('Cleaning up any reference to '.$oObj->GetName().'...');
			$aDisplayConfig = array();
			$aDisplayConfig['class'] = array('label' => 'Class', 'description' => '');
			$aDisplayConfig['object'] = array('label' => 'Object', 'description' => '');
			$aDisplayConfig['consequence'] = array('label' => 'Done', 'description' => 'What has been done');
			$oP->table($aDisplayConfig, $aDisplayData);
		}

		$oObj->DBDeleteTracked($oMyChange);
		$oP->add("<h1>".$sName." - $sClassLabel deleted</h1>\n");
	}
	else
	{
		$oP->add("<h1>Deletion of ".$oObj->GetHyperLink()."</h1>\n");
		// Explain what should be done
		//
		$aDisplayData = array();
		foreach ($aDeletedObjs as $sRemoteClass => $aDeletes)
		{
			foreach ($aDeletes as $iId => $aData)
			{
				$oToDelete = $aData['to_delete'];
				$bAutoDel = $aData['auto_delete'];
				if (array_key_exists('issue', $aData))
				{
					if ($bAutoDel)
					{
						$sConsequence = 'Should be automaticaly deleted, but you are not allowed to do so';
					}
					else
					{
						$sConsequence = 'Must be deleted manually - you are not allowed to delete this object, please contact your application admin';
					}
				}
				else
				{
					if ($bAutoDel)
					{
						$sConsequence = 'Will be automaticaly deleted';
					}
					else
					{
						$sConsequence = 'Must be deleted manually';
					}
				}
				$aDisplayData[] = array(
					'class' => MetaModel::GetName(get_class($oToDelete)),
					'object' => $oToDelete->GetHyperLink(),
					'consequence' => $sConsequence,
				);
			}
		}
		foreach ($aResetedObjs as $sRemoteClass => $aToReset)
		{
			foreach ($aToReset as $iId => $aData)
			{
				$oToReset = $aData['to_reset'];
				if (array_key_exists('issue', $aData))
				{
					$sConsequence = "Should be automatically updated, but: ".$aData['issue'];
				}
				else
				{
					$sConsequence = "will be automaticaly updated (reset: ".$aData['attributes_list'].")";
				}
				$aDisplayData[] = array(
					'class' => MetaModel::GetName(get_class($oToReset)),
					'object' => $oToReset->GetHyperLink(),
					'consequence' => $sConsequence,
				);
			}
		}

		if ($iTotalTargets > 0)
		{
			$oP->p("$iTotalTargets objects/links are referencing ".$oObj->GetName());
			$oP->p('To ensure Database integrity, any reference should be further eliminated');

			$aDisplayConfig = array();
			$aDisplayConfig['class'] = array('label' => 'Class', 'description' => '');
			$aDisplayConfig['object'] = array('label' => 'Object', 'description' => '');
			$aDisplayConfig['consequence'] = array('label' => 'Consequence', 'description' => 'What will happen to this object');
			$oP->table($aDisplayConfig, $aDisplayData);
		}

		if ($iTotalTargets > 0 && ($bFoundManual || $bFoundStopper))
		{
			if ($bFoundStopper)
			{
				$oP->p("Sorry, you are not allowed to delete this object, please see detailed explanations above");
			}
			elseif ($bFoundManual)
			{
				$oP->p("Please do the manual operations requested above prior to requesting the deletion of this object");
			}		
			$oP->add("<form method=\"post\">\n");
			$oP->add("<input DISABLED type=\"submit\" name=\"\" value=\" Delete! \">\n");
			$oP->add("<input type=\"button\" onclick=\"window.history.back();\" value=\" Cancel \">\n");
			$oP->add("</form>\n");
		}
		else
		{
			$oP->p("Please confirm that you want to delete ".$oObj->GetHyperLink());
			$oP->add("<form method=\"post\">\n");
			$oP->add("<input type=\"hidden\" name=\"menu\" value=\"$iActiveNodeId\">\n");
			$oP->add("<input type=\"hidden\" name=\"operation\" value=\"delete_confirmed\">\n");
			$oP->add("<input type=\"hidden\" name=\"class\" value=\"$sClass\">\n");
			$oP->add("<input type=\"hidden\" name=\"id\" value=\"$id\">\n");
			$oP->add("<input type=\"submit\" name=\"\" value=\" Delete! \">\n");
			$oP->add("<input type=\"button\" onclick=\"window.history.back();\" value=\" Cancel \">\n");
			$oP->add("</form>\n");
		}
	}
	break;
	
	case 'apply_new':
	$oP->p('Creation of the object');
	$oP->p('Obsolete, should now go through the wizard...');
	break;
	
	case 'apply_clone':
	$sClass = utils::ReadPostedParam('class', '');
	$sClassLabel = MetaModel::GetName($sClass);
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
			$oP->add("<h1>".$oObj->GetName()." - $sClassLabel created</h1>\n");
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
		$oObj = $oWizardHelper->GetTargetObject(true /* read uploaded files */);
		if (is_object($oObj))
		{
			$sClass = get_class($oObj);
			$sClassLabel = MetaModel::GetName($sClass);
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
			$oP->set_title("iTop - ".$oObj->GetName()." - $sClassLabel created");
			$oP->add("<h1>".$oObj->GetName()." - $sClassLabel created</h1>\n");
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
						$aArgs = array('this' => $oObj);
						$sHTMLValue = cmdbAbstractObject::GetFormElementForField($oP, $sClass, $sAttCode, $oAttDef, $oObj->Get($sAttCode), $oObj->GetDisplayValue($sAttCode), '', '', $iExpectCode, $aArgs);
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
				$oP->p("<strong>Error: object has already been updated!</strong>\n");
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
			
					$oP->p(MetaModel::GetName(get_class($oObj))." updated.\n");
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

	case 'modify_links':
	$sClass = utils::ReadParam('class', '');
	$sLinkAttr = utils::ReadParam('link_attr', '');
	$sTargetClass = utils::ReadParam('target_class', '');
	$id = utils::ReadParam('id', '');
	$bAddObjects = utils::ReadParam('addObjects', false);
	if ( empty($sClass) || empty($id) || empty($sLinkAttr) || empty($sTargetClass)) // TO DO: check that the class name is valid !
	{
		$oP->set_title("iTop - Error");
		$oP->add("<p>4 parameters are mandatory for this operation: class, id, target_class and link_attr.</p>\n");
	}
	else
	{
		require_once('../application/uilinkswizard.class.inc.php');
		$oWizard = new UILinksWizard($sClass, $sLinkAttr, $id, $sTargetClass);
		$oWizard->Display($oP, $oContext, array('StartWithAdd' => $bAddObjects));		
	}
	break;
	
	case 'do_modify_links':
	$aLinks = utils::ReadParam('linkId', array(), 'post');
	$sLinksToRemove = trim(utils::ReadParam('linksToRemove', '', 'post'));
	$aLinksToRemove = array();
	if (!empty($sLinksToRemove))
	{
		$aLinksToRemove = explode(' ', trim($sLinksToRemove));
	}
	$sClass = utils::ReadParam('class', '', 'post');
	$sLinkageAtt = utils::ReadParam('linkage', '', 'post');
	$iObjectId = utils::ReadParam('object_id', '', 'post');
	$sLinkingAttCode = utils::ReadParam('linking_attcode', '', 'post');
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
	
	// Delete links that are to be deleted
	foreach($aLinksToRemove as $iLinkId)
	{
		if ($iLinkId > 0) // Negative IDs are objects that were not even created
		{
			$oLink = $oContext->GetObject($sClass, $iLinkId);
			$oLink->DBDeleteTracked($oMyChange);
		}
	}
	
	$aEditableFields = array();
	$aData = array();
	foreach(MetaModel::GetAttributesList($sClass) as $sAttCode)
	{
		$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
		if ( (!$oAttDef->IsExternalKey()) && (!$oAttDef->IsExternalField()))
		{
			$aEditableFields[] = $sAttCode;
			$aData[$sAttCode] = utils::ReadParam('attr_'.$sAttCode, array(), 'post');
		}
	}
	
	// Update existing links or create new links
	foreach($aLinks as $iLinkId)
	{
		if ($iLinkId > 0)
		{
			// This is an existing link to be modified
			$oLink = $oContext->GetObject($sClass, $iLinkId);
			
			// Update all the attributes of the link
			foreach($aEditableFields as $sAttCode)
			{
				$value = $aData[$sAttCode][$iLinkId];
				$oLink->Set($sAttCode, $value);
			}
			if ($oLink->IsModified())
			{
				$oLink->DBUpdateTracked($oMyChange);
			}
			//echo "Updated link:<br/>\n";
			//var_dump($oLink);
		}
		else
		{
			// A new link must be created
			$oLink = MetaModel::NewObject($sClass);
			$oLinkedObjectId = -$iLinkId;
			// Set all the attributes of the link
			foreach($aEditableFields as $sAttCode)
			{
				$value = $aData[$sAttCode][$iLinkId];
				$oLink->Set($sAttCode, $value);
			}
			// And the two external keys
			$oLink->Set($sLinkageAtt, $iObjectId);
			$oLink->Set($sLinkingAttCode, $oLinkedObjectId);
			// then save it
			//echo "Created link:<br/>\n";
			//var_dump($oLink);
			$oLink->DBInsertTracked($oMyChange);
		}
	}
	// Display again the details of the linked object
	$oAttDef = MetaModel::GetAttributeDef($sClass, $sLinkageAtt);
	$sTargetClass = $oAttDef->GetTargetClass();
	$oObj = $oContext->GetObject($sTargetClass, $iObjectId);
	
	$oSearch = $oContext->NewFilter(get_class($oObj));
	$oBlock = new DisplayBlock($oSearch, 'search', false);
	$oBlock->Display($oP, 0);
	$oObj->DisplayDetails($oP);
	break;
	
	default:
	if (is_object($oActiveNode))
	{
		$oActiveNode->RenderContent($oP, $oAppContext->GetAsHash());
	}
}
////MetaModel::ShowQueryTrace();
$oP->output();
?>
