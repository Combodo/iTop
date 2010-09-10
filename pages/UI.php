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
 * Main page of iTop
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

/**
 * Perform all the needed checks to delete one (or more) objects
 */
function DeleteObjects(WebPage $oP, $sClass, $aObjects, $bDeleteConfirmed)
{
	$bFoundManual = false;
	$bFoundStopper = false;
	$iTotalDelete = 0; // count of object that must be deleted
	$iTotalReset = 0; // count of object for which an ext key will be reset (to 0)
	$aTotalDeletedObjs = array();
	$aTotalResetedObjs = array();
	foreach($aObjects as $oObj)
	{
		// Evaluate the impact on the DB integrity
		//
		list ($aDeletedObjs, $aResetedObjs) = $oObj->GetDeletionScheme();
	
		// Evaluate feasibility (user access control)
		//
		foreach ($aDeletedObjs as $sRemoteClass => $aDeletes)
		{
			$iTotalDelete += count($aDeletes);
			foreach ($aDeletes as $iId => $aData)
			{
				$oToDelete = $aData['to_delete'];
				$bDeleteAllowed = UserRights::IsActionAllowed($sClass, UR_ACTION_DELETE, DBObjectSet::FromObject($oToDelete));
				$aTotalDeletedObjs[$sRemoteClass][$iId]['auto_delete'] = $aData['auto_delete'];
				if (!$bDeleteAllowed)
				{
					$aTotalDeletedObjs[$sRemoteClass][$iId]['issue'] = Dict::S('UI:Delete:NotAllowedToDelete');
					$bFoundStopper = true;
				}
				else
				{
					$aTotalDeletedObjs[$sRemoteClass][$iId]['to_delete'] = $oToDelete;
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
				$aTotalResetedObjs[$sRemoteClass][$iId]['attributes_list'] = $aResetedObjs[$sRemoteClass][$iId]['attributes_list'];
				$aTotalResetedObjs[$sRemoteClass][$iId]['attributes'] = $aResetedObjs[$sRemoteClass][$iId]['attributes'];
				if (count($aForbiddenKeys) > 0)
				{
					$aTotalResetedObjs[$sRemoteClass][$iId]['issue'] = Dict::Format('UI:Delete:NotAllowedToUpdate_Fields',implode(', ', $aForbiddenKeys));
				}
				else
				{
					$aTotalResetedObjs[$sRemoteClass][$iId]['to_reset'] = $oToReset;
				}
			}
		}
		// Count of dependent objects (+ the current one)
		$iTotalTargets = $iTotalDelete + $iTotalReset;
	}
	
	if ($bDeleteConfirmed)
	{
		if (count($aObjects) == 1)
		{
			$oObj = $aObjects[0];
			$oP->add("<h1>".Dict::Format('UI:Title:DeletionOf_Object', $oObj->GetName())."</h1>\n");				
		}
		else
		{
			$oP->add("<h1>".Dict::Format('UI:Title:BulkDeletionOf_Count_ObjectsOf_Class', count($aObjects), MetaModel::GetName($sClass))."</h1>\n");		
		}
		// Security - do not allow the user to force a forbidden delete by the mean of page arguments...
		if ($bFoundStopper)
		{
			throw new CoreException(Dict::S('UI:Error:NotEnoughRightsToDelete'));
		}
		if ($bFoundManual)
		{
			throw new CoreException(Dict::S('UI:Error:CannotDeleteBecauseOfDepencies'));
		}

		// Prepare the change reporting
		//
		$oMyChange = MetaModel::NewObject("CMDBChange");
		$oMyChange->Set("date", time());
		if (UserRights::IsImpersonated())
		{
			$sUserString = Dict::Format('UI:Archive_User_OnBehalfOf_User', UserRights::GetRealUser(), UserRights::GetUser());
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
		foreach ($aTotalDeletedObjs as $sRemoteClass => $aDeletes)
		{
			foreach ($aDeletes as $iId => $aData)
			{
				$oToDelete = $aData['to_delete'];

				$aDisplayData[] = array(
					'class' => MetaModel::GetName(get_class($oToDelete)),
					'object' => $oToDelete->GetHyperLink(),
					'consequence' => Dict::S('UI:Delete:AutomaticallyDeleted'),
				);

				$oToDelete->DBDeleteTracked($oMyChange);
			}
		}
	
		// Update dependencies
		//
		foreach ($aTotalResetedObjs as $sRemoteClass => $aToReset)
		{
			foreach ($aToReset as $iId => $aData)
			{
				$oToReset = $aData['to_reset'];
				$aDisplayData[] = array(
					'class' => MetaModel::GetName(get_class($oToReset)),
					'object' => $oToReset->GetHyperLink(),
					'consequence' => Dict::Format('UI:Delete:AutomaticResetOf_Fields', $aData['attributes_list']),
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
			if (count($aObjects) == 1)
			{
				$oObj = $aObjects[0];
				$oP->p(Dict::Format('UI:Delete:CleaningUpRefencesTo_Object', $oObj->GetName()));
			}
			else
			{
				$oP->p(Dict::Format('UI:Delete:CleaningUpRefencesTo_Several_ObjectsOf_Class', count($aObjects), MetaModel::GetName($sClass)));
			}
			$aDisplayConfig = array();
			$aDisplayConfig['class'] = array('label' => 'Class', 'description' => '');
			$aDisplayConfig['object'] = array('label' => 'Object', 'description' => '');
			$aDisplayConfig['consequence'] = array('label' => 'Done', 'description' => Dict::S('UI:Delete:Done+'));
			$oP->table($aDisplayConfig, $aDisplayData);
		}

		foreach($aObjects as $oObj)
		{
			$sName = $oObj->GetName();
			$sClassLabel = MetaModel::GetName(get_class($oObj));
			$oObj->DBDeleteTracked($oMyChange);
			$oP->add("<h1>".Dict::Format('UI:Delete:_Name_Class_Deleted', $sName, $sClassLabel)."</h1>\n");
		}
	}
	else
	{
		if (count($aObjects) == 1)
		{
			$oObj = $aObjects[0];
			$oP->add("<h1>".Dict::Format('UI:Delete:ConfirmDeletionOf_Name', $oObj->GetName())."</h1>\n");
		}
		else
		{
			$oP->add("<h1>".Dict::Format('UI:Delete:ConfirmDeletionOf_Count_ObjectsOf_Class', count($aObjects), MetaModel::GetName($sClass))."</h1>\n");
		}
		// Explain what should be done
		//
		$aDisplayData = array();
		foreach ($aTotalDeletedObjs as $sRemoteClass => $aDeletes)
		{
			foreach ($aDeletes as $iId => $aData)
			{
				$oToDelete = $aData['to_delete'];
				$bAutoDel = $aData['auto_delete'];
				if (array_key_exists('issue', $aData))
				{
					if ($bAutoDel)
					{
						$sConsequence = Dict::S('UI:Delete:ShouldBeDeletedAtomaticallyButNotAllowed');
					}
					else
					{
						$sConsequence = Dict::S('UI:Delete:MustBeDeletedManuallyButNotAllowed');
					}
				}
				else
				{
					if ($bAutoDel)
					{
						$sConsequence = Dict::S('UI:Delete:WillBeDeletedAutomatically');
					}
					else
					{
						$sConsequence = Dict::S('UI:Delete:MustBeDeletedManually');
					}
				}
				$aDisplayData[] = array(
					'class' => MetaModel::GetName(get_class($oToDelete)),
					'object' => $oToDelete->GetHyperLink(),
					'consequence' => $sConsequence,
				);
			}
		}
		foreach ($aTotalResetedObjs as $sRemoteClass => $aToReset)
		{
			foreach ($aToReset as $iId => $aData)
			{
				$oToReset = $aData['to_reset'];
				if (array_key_exists('issue', $aData))
				{
					$sConsequence = Dict::Format('UI:Delete:CannotUpdateBecause_Issue', $aData['issue']);
				}
				else
				{
					$sConsequence = Dict::Format('UI:Delete:WillAutomaticallyUpdate_Fields', $aData['attributes_list']);
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
			if (count($aObjects) == 1)
			{
				$oObj = $aObjects[0];
				$oP->p(Dict::Format('UI:Delete:Count_Objects/LinksReferencing_Object', $iTotalTargets, $oObj->GetName()));
			}
			else
			{
				$oP->p(Dict::Format('UI:Delete:Count_Objects/LinksReferencingTheObjects', $iTotalTargets));
			}
			$oP->p(Dict::S('UI:Delete:ReferencesMustBeDeletedToEnsureIntegrity'));

			$aDisplayConfig = array();
			$aDisplayConfig['class'] = array('label' => 'Class', 'description' => '');
			$aDisplayConfig['object'] = array('label' => 'Object', 'description' => '');
			$aDisplayConfig['consequence'] = array('label' => 'Consequence', 'description' => Dict::S('UI:Delete:Consequence+'));
			$oP->table($aDisplayConfig, $aDisplayData);
		}

		if ($iTotalTargets > 0 && ($bFoundManual || $bFoundStopper))
		{
			if ($bFoundStopper)
			{
				$oP->p(Dict::S('UI:Delete:SorryDeletionNotAllowed'));
			}
			elseif ($bFoundManual)
			{
				$oP->p(Dict::S('UI:Delete:PleaseDoTheManualOperations'));
			}		
			$oP->add("<form method=\"post\">\n");
			$oP->add("<input type=\"hidden\" name=\"transaction_id\" value=\"".utils::ReadParam('transaction_id')."\">\n");
			$oP->add("<input type=\"button\" onclick=\"window.history.back();\" value=\"".Dict::S('UI:Button:Back')."\">\n");
			$oP->add("<input DISABLED type=\"submit\" name=\"\" value=\"".Dict::S('UI:Button:Delete')."\">\n");
			$oP->add("</form>\n");
		}
		else
		{
			if (count($aObjects) == 1)
			{
				$oObj = $aObjects[0];
				$id = $oObj->GetKey();
				$oP->p('<h1>'.Dict::Format('UI:Delect:Confirm_Object', $oObj->GetHyperLink()).'</h1>');
				$oP->add("<form method=\"post\">\n");
				$oP->add("<input type=\"hidden\" name=\"transaction_id\" value=\"".utils::ReadParam('transaction_id')."\">\n");
				$oP->add("<input type=\"hidden\" name=\"operation\" value=\"delete_confirmed\">\n");
				$oP->add("<input type=\"hidden\" name=\"class\" value=\"$sClass\">\n");
				$oP->add("<input type=\"hidden\" name=\"id\" value=\"$id\">\n");
				$oP->add("<input type=\"button\" onclick=\"window.history.back();\" value=\"".Dict::S('UI:Button:Back')."\">\n");
				$oP->add("<input type=\"submit\" name=\"\" value=\"".Dict::S('UI:Button:Delete')."\">\n");
				$oP->add("</form>\n");
			}
			else
			{
				$oP->p('<h1>'.Dict::Format('UI:Delect:Confirm_Count_ObjectsOf_Class', count($aObjects), MetaModel::GetName($sClass)).'</h1>');
				$oSet = CMDBobjectSet::FromArray($sClass, $aObjects);
				CMDBAbstractObject::DisplaySet($oP, $oSet, array('display_limit' => false, 'menu' => false));
				$oP->add("<form method=\"post\">\n");
				$oP->add("<input type=\"hidden\" name=\"transaction_id\" value=\"".utils::ReadParam('transaction_id')."\">\n");
				$oP->add("<input type=\"hidden\" name=\"operation\" value=\"bulk_delete_confirmed\">\n");
				$oP->add("<input type=\"hidden\" name=\"class\" value=\"$sClass\">\n");
				foreach($aObjects as $oObj)
				{
					$oP->add("<input type=\"hidden\" name=\"selectObject[]\" value=\"".$oObj->GetKey()."\">\n");
				}
				$oP->add("<input type=\"button\" onclick=\"window.history.back();\" value=\"".Dict::S('UI:Button:Back')."\">\n");
				$oP->add("<input type=\"submit\" name=\"\" value=\"".Dict::S('UI:Button:Delete')."\">\n");
				$oP->add("</form>\n");
			}
		}
	}

}

/**
 * Updates an object from the POSTed parameters
 */
function UpdateObject(&$oObj)
{
	foreach(MetaModel::ListAttributeDefs(get_class($oObj)) as $sAttCode=>$oAttDef)
	{
		if ($oAttDef->IsLinkSet() && $oAttDef->IsIndirect())
		{
			$aLinks = utils::ReadPostedParam("attr_$sAttCode", '');
			$sLinkedClass = $oAttDef->GetLinkedClass();
			$sExtKeyToRemote = $oAttDef->GetExtKeyToRemote();
			$sExtKeyToMe = $oAttDef->GetExtKeyToMe();
			$oLinkedSet = DBObjectSet::FromScratch($sLinkedClass);
			if (is_array($aLinks))
			{
				foreach($aLinks as $id => $aData)
				{
					if (is_numeric($id))
					{
						if ($id < 0)
						{
							// New link to be created, the opposite of the id (-$id) is the ID of the remote object
							$oLink = MetaModel::NewObject($sLinkedClass);
							$oLink->Set($sExtKeyToRemote, -$id);
							$oLink->Set($sExtKeyToMe, $oObj->GetKey());
						}
						else
						{
							// Existing link, potentially to be updated...
							$oLink = MetaModel::GetObject($sLinkedClass, $id);
						}
						// Now populate the attributes
						foreach($aData as $sName => $value)
						{
							if (MetaModel::IsValidAttCode($sLinkedClass, $sName))
							{
								$oLinkAttDef = MetaModel::GetAttributeDef($sLinkedClass, $sName);
								if ($oLinkAttDef->IsWritable())
								{
									$oLink->Set($sName, $value);
								}
							}
						}
						$oLinkedSet->AddObject($oLink);
					}
				}
			}
			$oObj->Set($sAttCode, $oLinkedSet);
		}
		else if ($oAttDef->IsWritable())
		{
			$iFlags = $oObj->GetAttributeFlags($sAttCode);
			if ($iFlags & (OPT_ATT_HIDDEN | OPT_ATT_READONLY))
			{
				// Non-visible, or read-only attribute, do nothing
			}
			elseif ($oAttDef->GetEditClass() == 'Document')
			{
				// There should be an uploaded file with the named attr_<attCode>
				$oDocument = utils::ReadPostedDocument('file_'.$sAttCode);
				if (!$oDocument->IsEmpty())
				{
					// A new file has been uploaded
					$oObj->Set($sAttCode, $oDocument);
				}
			}
			elseif ($oAttDef->GetEditClass() == 'One Way Password')
			{
				// Check if the password was typed/changed
				$bChanged = utils::ReadPostedParam("attr_{$sAttCode}_changed", false);
				if ($bChanged)
				{
					// The password has been changed or set
					$rawValue = utils::ReadPostedParam("attr_$sAttCode", null);
					$oObj->Set($sAttCode, $rawValue);
				}
			}
			else
			{
				$rawValue = utils::ReadPostedParam("attr_$sAttCode", null);
				if (!is_null($rawValue))
				{
					$aAttributes[$sAttCode] = trim($rawValue);
					$previousValue = $oObj->Get($sAttCode);
					if ($previousValue !== $aAttributes[$sAttCode])
					{
						$oObj->Set($sAttCode, $aAttributes[$sAttCode]);
					}
				}
			}
		}
	}
}

/**
 * Displays a popup welcome message, once per session at maximum
 * until the user unchecks the "Display welcome at startup"
 * @param WebPage $oP The current web page for the display
 * @return void
 */
function DisplayWelcomePopup(WebPage $oP)
{
	if (!isset($_SESSION['welcome']))
	{
		// Check, only once per session, if the popup should be displayed...
		// If the user did not already ask for hiding it forever
		$bPopup = appUserPreferences::GetPref('welcome_popup', true);
		if ($bPopup)
		{
			$sTemplate = @file_get_contents('../application/templates/welcome_popup.html');
			if ($sTemplate !== false)
			{
				$oTemplate = new DisplayTemplate($sTemplate);
				$oP->add("<div id=\"welcome_popup\">");
				$oTemplate->Render($oP, array());
				$oP->add("<p style=\"float:left\"><input type=\"checkbox\" checked id=\"display_welcome_popup\"/><label for=\"display_welcome_popup\">&nbsp;".Dict::S('UI:DisplayThisMessageAtStartup')."</label></p>\n");
				$oP->add("<p style=\"float:right\"><input type=\"button\" value=\"".Dict::S('UI:Button:Ok')."\" onClick=\"$('#welcome_popup').dialog('close');\"/>\n");
				$oP->add("</div>\n");
				$sTitle = addslashes(Dict::S('UI:WelcomeMenu:Title'));
				$oP->add_ready_script(
<<<EOF
	$('#welcome_popup').dialog( { width:'80%', title: '$sTitle', autoOpen: true, modal:true,
								  close: function() {
								  	var bDisplay = $('#display_welcome_popup:checked').length;
								  	SetUserPreference('welcome_popup', bDisplay, true); 
								  }
								  });
EOF
);
				$_SESSION['welcome'] = 'ok';
			}
		}
	}	
}
/***********************************************************************************
 * 
 * Main user interface page, starts here
 *
 * ***********************************************************************************/
try
{
	require_once('../application/application.inc.php');
	require_once('../application/itopwebpage.class.inc.php');
	require_once('../application/wizardhelper.class.inc.php');

	require_once('../application/startup.inc.php');
	$oAppContext = new ApplicationContext();
	$currentOrganization = utils::ReadParam('org_id', '');
	$operation = utils::ReadParam('operation', '');

	$oKPI = new ExecutionKPI();

	require_once('../application/loginwebpage.class.inc.php');
	LoginWebPage::DoLogin(); // Check user rights and prompt if needed

	$oKPI->ComputeAndReport('User login');

	$oP = new iTopWebPage(Dict::S('UI:WelcomeToITop'), $currentOrganization);

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
				throw new ApplicationException(Dict::Format('UI:Error:2ParametersMissing', 'class', 'id'));
			}

			$oObj = MetaModel::GetObject($sClass, $id, false /* MustBeFound */);
			if (is_null($oObj))
			{
				$oP->set_title(Dict::S('UI:ErrorPageTitle'));
				$oP->P(Dict::S('UI:ObjectDoesNotExist'));
			}
			else
			{
				// The object could be listed, check if it is actually allowed to view it
				$oSet = CMDBObjectSet::FromObject($oObj);
				if (UserRights::IsActionAllowed($sClass, UR_ACTION_READ, $oSet) == UR_ALLOWED_NO)
				{
					throw new SecurityException('User not allowed to view this object', array('class' => $sClass, 'id' => $id));
				}
				$oP->set_title(Dict::Format('UI:DetailsPageTitle', $oObj->GetName(), $sClassLabel));
				$oObj->DisplayDetails($oP);
			}
		break;
	
		case 'search_oql':
			$sOQLClass = utils::ReadParam('oql_class', '');
			$sOQLClause = utils::ReadParam('oql_clause', '');
			$sFormat = utils::ReadParam('format', '');
			$bSearchForm = utils::ReadParam('search_form', true);
			if (empty($sOQLClass))
			{
				throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'oql_class'));
			}
			$oP->set_title(Dict::S('UI:SearchResultsPageTitle'));
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
				$oP->P('<b>'.Dict::Format('UI:Error:IncorrectOQLQuery_Message', $e->getHtmlDesc()).'</b>');
			}
			catch(Exception $e)
			{
				$oP->P('<b>'.Dict::Format('UI:Error:AnErrorOccuredWhileRunningTheQuery_Message', $e->getMessage()).'</b>');
			}
		break;
		
		case 'search_form':
			$sClass = utils::ReadParam('class', '');
			$sFormat = utils::ReadParam('format', 'html');
			$bSearchForm = utils::ReadParam('search_form', true);
			if (empty($sClass))
			{
				throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'class'));
			}
			$oP->set_title(Dict::S('UI:SearchResultsPageTitle'));
			$oFilter =  new DBObjectSearch($sClass);
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
		break;
		
		case 'search':
			$sFilter = utils::ReadParam('filter', '');
			$sFormat = utils::ReadParam('format', '');
			$bSearchForm = utils::ReadParam('search_form', true);
			if (empty($sFilter))
			{
				throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'filter'));
			}
			$oP->set_title(Dict::S('UI:SearchResultsPageTitle'));
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
		break;
	
		case 'full_text':
			$sFullText = trim(utils::ReadParam('text', ''));
			if (empty($sFullText))
			{
				$oP->p(Dict::S('UI:Search:NoSearch'));
			}
			else
			{
				$oP->set_title(Dict::S('UI:SearchResultsPageTitle'));
				$oP->p("<h1>".Dict::Format('UI:FullTextSearchTitle_Text', $sFullText)."</h1>");
				$iCount = 0;
				$iBlock = 0;
				// Search in full text mode in all the classes
				$aMatches = array();
				if (preg_match('/^"(.*)"$/', $sFullText, $aMatches))
				{
					// The text is surrounded by double-quotes, remove the quotes and treat it as one single expression
					$aFullTextNeedles = array($aMatches[1]);
				}
				else
				{
					// Split the text on the blanks and treat this as a search for <word1> AND <word2> AND <word3>
					$aFullTextNeedles = explode(' ', $sFullText);
				}
				foreach(MetaModel::GetClasses('searchable') as $sClassName)
				{
					$oFilter = new DBObjectSearch($sClassName);
					foreach($aFullTextNeedles as $sSearchText)
					{
						$oFilter->AddCondition_FullText($sSearchText);
					}
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
							$oP->add("<h2>".MetaModel::GetClassIcon($sClassName)."&nbsp;<span class=\"hilite\">".Dict::Format('UI:Search:Count_ObjectsOf_Class_Found', count($aLeafs), Metamodel::GetName($sClassName))."</h2>\n");
							$oP->add("</div>\n");
							$oLeafsFilter->AddCondition('id', $aLeafs, 'IN');
							$oBlock = new DisplayBlock($oLeafsFilter, 'list', false);
							$oBlock->Display($oP, $iBlock++);
							$oP->P('&nbsp;'); // Some space ?
						}
					}
				}
				if ($iCount == 0)
				{
					$oP->p(Dict::S('UI:Search:NoObjectFound'));
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
				throw new ApplicationException(Dict::Format('UI:Error:2ParametersMissing', 'class', 'id'));
			}
			// Check if the user can modify this object
			$oObj = MetaModel::GetObject($sClass, $id, false /* MustBeFound */);
			if (is_null($oObj))
			{
				$oP->set_title(Dict::S('UI:ErrorPageTitle'));
				$oP->P(Dict::S('UI:ObjectDoesNotExist'));
			}
			else
			{
				// The object could be read - check if it is allowed to modify it
				$oSet = CMDBObjectSet::FromObject($oObj);
				if (UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY, $oSet) == UR_ALLOWED_NO)
				{
					throw new SecurityException('User not allowed to modify this object', array('class' => $sClass, 'id' => $id));
				}
				// Note: code duplicated to the case 'apply_modify' when a data integrity issue has been found
				$oP->set_title(Dict::Format('UI:ModificationPageTitle_Object_Class', $oObj->GetName(), $sClassLabel));
				$oP->add("<div class=\"page_header\">\n");
				$oP->add("<h1>".$oObj->GetIcon()."&nbsp;".Dict::Format('UI:ModificationTitle_Class_Object', $sClassLabel, $oObj->GetName())."</h1>\n");
				$oP->add("</div>\n");

				$oP->add("<div class=\"wizContainer\">\n");
				$oObj->DisplayModifyForm($oP);
				$oP->add("</div>\n");
			}
		break;
	
		case 'new':
			$sClass = utils::ReadParam('class', '');
			$sStateCode = utils::ReadParam('state', '');
			$bCheckSubClass = utils::ReadParam('checkSubclass', true);
			if ( empty($sClass) )
			{
				throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'class'));
			}
			// Note: code duplicated to the case 'apply_modify' when a data integrity issue has been found
			$oP->add_linked_script("../js/json.js");
			$oP->add_linked_script("../js/forms-json-utils.js");
			$oP->add_linked_script("../js/wizardhelper.js");
			$oP->add_linked_script("../js/wizard.utils.js");
			$oP->add_linked_script("../js/linkswidget.js");
			$oP->add_linked_script("../js/jquery.blockUI.js");

			$aArgs = array_merge($oAppContext->GetAsHash(), utils::ReadParam('default', array()));

			// If the specified class has subclasses, ask the user an instance of which class to create
			$aSubClasses = MetaModel::EnumChildClasses($sClass, ENUM_CHILD_CLASSES_ALL); // Including the specified class itself
			$aPossibleClasses = array();
			$sRealClass = '';
			if ($bCheckSubClass)
			{
				foreach($aSubClasses as $sCandidateClass)
				{
					if (!MetaModel::IsAbstract($sCandidateClass) && (UserRights::IsActionAllowed($sCandidateClass, UR_ACTION_MODIFY) == UR_ALLOWED_YES))
					{
						$aPossibleClasses[$sCandidateClass] = MetaModel::GetName($sCandidateClass);
					}
				}
				// Only one of the subclasses can be instantiated...
				if (count($aPossibleClasses) == 1)
				{
					$aKeys = array_keys($aPossibleClasses);
					$sRealClass = $aKeys[0];
				}
			}
			else
			{
				$sRealClass = $sClass;
			}
			
			if (!empty($sRealClass))
			{
				// Display the creation form
				$sClassLabel = MetaModel::GetName($sRealClass);
				// Note: some code has been duplicated to the case 'apply_new' when a data integrity issue has been found
				$oP->set_title(Dict::Format('UI:CreationPageTitle_Class', $sClassLabel));
				$oP->add("<h1>".MetaModel::GetClassIcon($sRealClass)."&nbsp;".Dict::Format('UI:CreationTitle_Class', $sClassLabel)."</h1>\n");
				$oP->add("<div class=\"wizContainer\">\n");
				$aDefaults = utils::ReadParam('default', array());
				$aContext = $oAppContext->GetAsHash();
				foreach($aContext as $key => $value)
				{
					$aDefaults[$key] = $value;	
				}
				// Set all the default values in an object and clone this "default" object
				$oObjToClone = MetaModel::NewObject($sRealClass);
				foreach($aDefaults as $sName => $value)
				{
					if (MetaModel::IsValidAttCode($sRealClass, $sName))
					{
						$oAttDef = MetaModel::GetAttributeDef($sRealClass, $sName);
						if ($oAttDef->IsWritable())
						{
							$oObjToClone->Set($sName, $value);
						}
					}
				}
				cmdbAbstractObject::DisplayCreationForm($oP, $sRealClass, $oObjToClone, array('default' => $aDefaults));
				$oP->add("</div>\n");
			}
			else
			{
				// Select the derived class to create
				$sClassLabel = MetaModel::GetName($sClass);
				$oP->add("<h1>".MetaModel::GetClassIcon($sClass)."&nbsp;".Dict::Format('UI:CreationTitle_Class', $sClassLabel)."</h1>\n");
				$oP->add("<div class=\"wizContainer\">\n");
				$oP->add('<form>');
				$oP->add('<p>'.Dict::Format('UI:SelectTheTypeOf_Class_ToCreate', $sClassLabel));
				$aDefaults = utils::ReadParam('default', array());
				$oP->add($oAppContext->GetForForm());
				$oP->add("<input type=\"hidden\" name=\"checkSubclass\" value=\"0\">\n");
				$oP->add("<input type=\"hidden\" name=\"state\" value=\"$sStateCode\">\n");
				$oP->add("<input type=\"hidden\" name=\"operation\" value=\"new\">\n");
				foreach($aDefaults as $key => $value)
				{
					$oP->add("<input type=\"hidden\" name=\"default[$key]\" value=\"$value\">\n");
					$aDefaults[$key] = $value;	
				}
				$oP->add('<select name="class">');
				asort($aPossibleClasses);
				foreach($aPossibleClasses as $sClassName => $sClassLabel)
				{
					$sSelected = ($sClassName == $sClass) ? 'selected' : '';
					$oP->add("<option $sSelected value=\"$sClassName\">$sClassLabel</option>");
				}
				$oP->add('</select>');
				$oP->add("&nbsp; <input type=\"submit\" value=\"".Dict::S('UI:Button:Apply')."\"></p>");
				$oP->add('</form>');
				$oP->add("</div>\n");
			}
		break;
	
		case 'apply_modify':
			$sClass = utils::ReadPostedParam('class', '');
			$sClassLabel = MetaModel::GetName($sClass);
			$id = utils::ReadPostedParam('id', '');
			$sTransactionId = utils::ReadPostedParam('transaction_id', '');
			if ( empty($sClass) || empty($id)) // TO DO: check that the class name is valid !
			{
				throw new ApplicationException(Dict::Format('UI:Error:2ParametersMissing', 'class', 'id'));
			}
			$bDisplayDetails = true;
			$oObj = MetaModel::GetObject($sClass, $id, false);
			if ($oObj == null)
			{
				$bDisplayDetails = false;
				$oP->set_title(Dict::S('UI:ErrorPageTitle'));
				$oP->P(Dict::S('UI:ObjectDoesNotExist'));
			}
			elseif (!utils::IsTransactionValid($sTransactionId))
			{
				$oP->set_title(Dict::Format('UI:ModificationPageTitle_Object_Class', $oObj->GetName(), $sClassLabel));
				$oP->p("<strong>".Dict::S('UI:Error:ObjectAlreadyUpdated')."</strong>\n");
			}
			else
			{
				UpdateObject($oObj);

				if (!$oObj->IsModified())
				{
					$oP->set_title(Dict::Format('UI:ModificationPageTitle_Object_Class', $oObj->GetName(), $sClassLabel));
					$oP->p(Dict::Format('UI:Class_Object_NotUpdated', MetaModel::GetName(get_class($oObj)), $oObj->GetName()));
				}
				else
				{
					list($bRes, $aIssues) = $oObj->CheckToWrite();
					if ($bRes)
					{
						$oP->set_title(Dict::Format('UI:ModificationPageTitle_Object_Class', $oObj->GetName(), $sClassLabel));
						$oP->add("<h1>".Dict::Format('UI:ModificationTitle_Class_Object', $sClassLabel, $oObj->GetName())."</h1>\n");

						$oMyChange = MetaModel::NewObject("CMDBChange");
						$oMyChange->Set("date", time());
						if (UserRights::IsImpersonated())
						{
							$sUserString = Dict::Format('UI:Archive_User_OnBehalfOf_User', UserRights::GetRealUser(), UserRights::GetUser());
						}
						else
						{
							$sUserString = UserRights::GetUser();
						}
						$oMyChange->Set("userinfo", $sUserString);
						$iChangeId = $oMyChange->DBInsert();
						$oObj->DBUpdateTracked($oMyChange);
			
						$oP->p(Dict::Format('UI:Class_Object_Updated', MetaModel::GetName(get_class($oObj)), $oObj->GetName()));
					}
					else
					{
						$bDisplayDetails = false;
						// Found issues, explain and give the user a second chance
						//
						// Note: code duplicated from the case 'modify'
						$oP->add_linked_script("../js/json.js");
						$oP->add_linked_script("../js/forms-json-utils.js");
						$oP->add_linked_script("../js/wizardhelper.js");
						$oP->add_linked_script("../js/wizard.utils.js");
						$oP->add_linked_script("../js/linkswidget.js");
						$oP->add_linked_script("../js/jquery.blockUI.js");
						$oP->set_title(Dict::Format('UI:ModificationPageTitle_Object_Class', $oObj->GetName(), $sClassLabel));
						$oP->add("<div class=\"page_header\">\n");
						$oP->add("<h1>".$oObj->GetIcon()."&nbsp;".Dict::Format('UI:ModificationTitle_Class_Object', $sClassLabel, $oObj->GetName())."</h1>\n");
						$oP->add("</div>\n");
						$oP->add("<div class=\"wizContainer\">\n");
						$oObj->DisplayModifyForm($oP);
						$oP->add("</div>\n");
						$sIssueDesc = Dict::Format('UI:ObjectCouldNotBeWritten', implode(', ', $aIssues));
						$oP->add_ready_script("alert('".addslashes($sIssueDesc)."');");
					}
				}
			}
			if ($bDisplayDetails)
			{
				$oObj = MetaModel::GetObject(get_class($oObj), $oObj->GetKey()); //Workaround: reload the object so that the linkedset are displayed properly
				$oObj->DisplayDetails($oP);
			}
		break;

		case 'select_for_deletion':
			$sFilter = utils::ReadParam('filter', '');
			$sFormat = utils::ReadParam('format', '');
			if (empty($sFilter))
			{
				throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'filter'));
			}
			$oP->set_title(Dict::S('UI:BulkDeletePageTitle'));
			$oP->add("<h1>".Dict::S('UI:BulkDeleteTitle')."</h1>\n");
			// TO DO: limit the search filter by the user context
			$oFilter = CMDBSearchFilter::unserialize($sFilter); // TO DO : check that the filter is valid
			$oSet = new DBObjectSet($oFilter);
			$oBlock = new DisplayBlock($oFilter, 'list', false);
			$oP->add("<form method=\"post\">\n");
			$oP->add("<input type=\"hidden\" name=\"operation\" value=\"bulk_delete\">\n");
			$oP->add("<input type=\"hidden\" name=\"class\" value=\"".$oFilter->GetClass()."\">\n");
			$oP->add("<input type=\"hidden\" name=\"transaction_id\" value=\"".utils::GetNewTransactionId()."\">\n");
			$oBlock->Display($oP, 1, array('selection_type' => 'multiple', 'selection_mode' => true, 'display_limit' => false, 'menu' => false));
			$oP->add("<input type=\"button\" value=\"".Dict::S('UI:Button:Cancel')."\" onClick=\"window.history.back()\">&nbsp;&nbsp;<input type=\"submit\" value=\"".Dict::S('UI:Button:Next')."\">\n");
			$oP->add("</form>\n");
		break;
		
		case 'bulk_delete_confirmed':
			$sTransactionId = utils::ReadPostedParam('transaction_id', '');
			if (!utils::IsTransactionValid($sTransactionId))
			{
				throw new ApplicationException(Dict::S('UI:Error:ObjectsAlreadyDeleted'));
			}
		case 'bulk_delete':
			$sClass = utils::ReadPostedParam('class', '');
			$sClassLabel = MetaModel::GetName($sClass);
			$aSelectObject = utils::ReadPostedParam('selectObject', '');
			$aObjects = array();
			if ( empty($sClass) || empty($aSelectObject)) // TO DO: check that the class name is valid !
			{
				throw new ApplicationException(Dict::Format('UI:Error:2ParametersMissing', 'class', 'selectObject[]'));
			}
			foreach($aSelectObject as $iId)
			{
				$aObjects[] = MetaModel::GetObject($sClass, $iId);
			}
			if (!UserRights::IsActionAllowed($sClass, UR_ACTION_BULK_DELETE, DBObjectSet::FromArray($sClass, $aObjects)))
			{
				throw new SecurityException(Dict::S('UI:Error:BulkDeleteNotAllowedOn_Class'), $sClass);
			}
			$oP->set_title(Dict::S('UI:BulkDeletePageTitle'));
			DeleteObjects($oP, $sClass, $aObjects, ($operation == 'bulk_delete_confirmed'));
		break;
			
		case 'delete':
		case 'delete_confirmed':
		$sClass = utils::ReadParam('class', '');
		$sClassLabel = MetaModel::GetName($sClass);
		$id = utils::ReadParam('id', '');
		$oObj = MetaModel::GetObject($sClass, $id);
	
		if (!UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY, DBObjectSet::FromObject($oObj)))
		{
			throw new SecurityException(Dict::S('UI:Error:DeleteNotAllowedOn_Class'), $sClass);
		}
		DeleteObjects($oP, $sClass, array($oObj), ($operation == 'delete_confirmed'));
		break;
	
		case 'apply_new':
		$sClass = utils::ReadPostedParam('class', '');
		$sClassLabel = MetaModel::GetName($sClass);
		$sTransactionId = utils::ReadPostedParam('transaction_id', '');
		if ( empty($sClass) ) // TO DO: check that the class name is valid !
		{
			throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'class'));
		}
		if (!utils::IsTransactionValid($sTransactionId))
		{
			$oP->p("<strong>".Dict::S('UI:Error:ObjectAlreadyCreated')."</strong>\n");
		}
		else
		{
			$oObj = MetaModel::NewObject($sClass);
			UpdateObject($oObj);
		}
		if (isset($oObj) && is_object($oObj))
		{
			$sClass = get_class($oObj);
			$sClassLabel = MetaModel::GetName($sClass);

			list($bRes, $aIssues) = $oObj->CheckToWrite();
			if ($bRes)
			{
				$oMyChange = MetaModel::NewObject("CMDBChange");
				$oMyChange->Set("date", time());
				if (UserRights::IsImpersonated())
				{
					$sUserString = Dict::Format('UI:Archive_User_OnBehalfOf_User', UserRights::GetRealUser(), UserRights::GetUser());
				}
				else
				{
					$sUserString = UserRights::GetUser();
				}
				$oMyChange->Set("userinfo", $sUserString);
				$iChangeId = $oMyChange->DBInsert();
				$oObj->DBInsertTracked($oMyChange);
				$oP->set_title(Dict::S('UI:PageTitle:ObjectCreated'));
				$oP->add("<h1>".Dict::Format('UI:Title:Object_Of_Class_Created', $oObj->GetName(), $sClassLabel)."</h1>\n");
				$oObj->DisplayDetails($oP);
			}
			else
			{
				// Found issues, explain and give the user a second chance
				//
				// Note: code similar to the case 'modify'
				$oP->add_linked_script("../js/json.js");
				$oP->add_linked_script("../js/forms-json-utils.js");
				$oP->add_linked_script("../js/wizardhelper.js");
				$oP->add_linked_script("../js/wizard.utils.js");
				$oP->add_linked_script("../js/linkswidget.js");
				$oP->add_linked_script("../js/jquery.blockUI.js");
				$oP->set_title(Dict::Format('UI:CreationPageTitle_Class', $sClassLabel));
				$oP->add("<h1>".MetaModel::GetClassIcon($sClass)."&nbsp;".Dict::Format('UI:CreationTitle_Class', $sClassLabel)."</h1>\n");
				$oP->add("<div class=\"wizContainer\">\n");
				cmdbAbstractObject::DisplayCreationForm($oP, $sClass, $oObj);
				$oP->add("</div>\n");
				$sIssueDesc = Dict::Format('UI:ObjectCouldNotBeWritten', implode(', ', $aIssues));
				$oP->add_ready_script("alert('".addslashes($sIssueDesc)."');");
			}
		}
		break;
		
		case 'wizard_apply_new':
		$sJson = utils::ReadPostedParam('json_obj', '');
		$oWizardHelper = WizardHelper::FromJSON($sJson);
		$sTransactionId = utils::ReadPostedParam('transaction_id', '');
		if (!utils::IsTransactionValid($sTransactionId))
		{
			$oP->p(Dict::S('UI:Error:ObjectAlreadyCreated'));
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
				if (UserRights::IsImpersonated())
				{
					$sUserString = Dict::Format('UI:Archive_User_OnBehalfOf_User', UserRights::GetRealUser(), UserRights::GetUser());
				}
				else
				{
					$sUserString = UserRights::GetUser();
				}
				$oMyChange->Set("userinfo", $sUserString);
				$iChangeId = $oMyChange->DBInsert();
				$oObj->DBInsertTracked($oMyChange);
				$oP->set_title(Dict::S('UI:PageTitle:ObjectCreated'));
				$oP->add("<h1>".Dict::Format('UI:Title:Object_Of_Class_Created', $oObj->GetName(), $sClassLabel)."</h1>\n");
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
			throw new ApplicationException(Dict::Format('UI:Error:3ParametersMissing', 'class', 'id', 'stimulus'));
		}
		$oObj = MetaModel::GetObject($sClass, $id, false);
		if ($oObj != null)
		{
			$aTransitions = $oObj->EnumTransitions();
			$aStimuli = MetaModel::EnumStimuli($sClass);
			if (!isset($aTransitions[$sStimulus]))
			{
				// Invalid stimulus
				throw new ApplicationException(Dict::Format('UI:Error:Invalid_Stimulus_On_Object_In_State', $sStimulus, $oObj->GetName(), $oObj->GetStateLabel()));
			}
			$sActionLabel = $aStimuli[$sStimulus]->GetLabel();
			$sActionDetails = $aStimuli[$sStimulus]->GetDescription();
			$aTransition = $aTransitions[$sStimulus];
			$sTargetState = $aTransition['target_state'];
			$aTargetStates = MetaModel::EnumStates($sClass);
			$oP->add_linked_script("../js/json.js");
			$oP->add_linked_script("../js/forms-json-utils.js");
			$oP->add_linked_script("../js/wizardhelper.js");
			$oP->add_linked_script("../js/wizard.utils.js");
			$oP->add_linked_script("../js/linkswidget.js");
			$oP->add_linked_script("../js/jquery.blockUI.js");
			$oP->add("<div class=\"page_header\">\n");
			$oP->add("<h1>$sActionLabel - <span class=\"hilite\">{$oObj->GetName()}</span></h1>\n");
			$oP->set_title($sActionLabel);
			$oP->add("</div>\n");
			$oObj->DisplayBareProperties($oP);
			$aTargetState = $aTargetStates[$sTargetState];
			$aExpectedAttributes = $aTargetState['attribute_list'];
			$oP->add("<h1>$sActionDetails</h1>\n");
			$oP->add("<div class=\"wizContainer\">\n");
			$oP->add("<form id=\"apply_stimulus\" method=\"post\" onSubmit=\"return CheckFields('apply_stimulus', true);\">\n");
			$aDetails = array();
			$iFieldIndex = 0;
			$aFieldsMap = array();
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
					$sHTMLValue = cmdbAbstractObject::GetFormElementForField($oP, $sClass, $sAttCode, $oAttDef, $oObj->Get($sAttCode), $oObj->GetEditValue($sAttCode), 'att_'.$iFieldIndex, '', $iExpectCode, $aArgs);
					$aDetails[] = array('label' => $oAttDef->GetLabel(), 'value' => "<span id=\"field_att_$iFieldIndex\">$sHTMLValue</span>");
					$aFieldsMap[$sAttCode] = 'att_'.$iFieldIndex;
					$iFieldIndex++;
				}
			}
			$oP->details($aDetails);
			$oP->add("<input type=\"hidden\" name=\"id\" value=\"$id\" id=\"id\">\n");
			$aFieldsMap['id'] = 'id';
			$oP->add("<input type=\"hidden\" name=\"class\" value=\"$sClass\">\n");
			$oP->add("<input type=\"hidden\" name=\"operation\" value=\"apply_stimulus\">\n");
			$oP->add("<input type=\"hidden\" name=\"stimulus\" value=\"$sStimulus\">\n");
			$oP->add("<input type=\"hidden\" name=\"transaction_id\" value=\"".utils::GetNewTransactionId()."\">\n");
			$oP->add($oAppContext->GetForForm());
			$oP->add("<button type=\"button\" class=\"action\" onClick=\"BackToDetails('$sClass', $id)\"><span>".Dict::S('UI:Button:Cancel')."</span></button>&nbsp;&nbsp;&nbsp;&nbsp;\n");
			$oP->add("<button type=\"submit\" class=\"action\"><span>$sActionLabel</span></button>\n");
			$oP->add("</form>\n");
			$oP->add("</div>\n");

			$iFieldsCount = count($aFieldsMap);
			$sJsonFieldsMap = json_encode($aFieldsMap);
	
			$oP->add_script(
<<<EOF
			// Initializes the object once at the beginning of the page...
			var oWizardHelper = new WizardHelper('$sClass');
			oWizardHelper.SetFieldsMap($sJsonFieldsMap);
			oWizardHelper.SetFieldsCount($iFieldsCount);
EOF
);
		}
		else
		{
			$oP->set_title(Dict::S('UI:ErrorPageTitle'));
			$oP->P(Dict::S('UI:ObjectDoesNotExist'));
		}		
		break;

		case 'apply_stimulus':
		$sClass = utils::ReadPostedParam('class', '');
		$id = utils::ReadPostedParam('id', '');
		$sTransactionId = utils::ReadPostedParam('transaction_id', '');
		$sStimulus = utils::ReadPostedParam('stimulus', '');
		if ( empty($sClass) || empty($id) ||  empty($sStimulus) ) // TO DO: check that the class name is valid !
		{
			throw new ApplicationException(Dict::Format('UI:Error:3ParametersMissing', 'class', 'id', 'stimulus'));
		}
		$oObj = MetaModel::GetObject($sClass, $id, false);
		if ($oObj != null)
		{
			$aTransitions = $oObj->EnumTransitions();
			$aStimuli = MetaModel::EnumStimuli($sClass);
			if (!isset($aTransitions[$sStimulus]))
			{
				throw new ApplicationException(Dict::Format('UI:Error:Invalid_Stimulus_On_Object_In_State', $sStimulus, $oObj->GetName(), $oObj->GetStateLabel()));
			}
			if (!utils::IsTransactionValid($sTransactionId))
			{
				$oP->p(Dict::S('UI:Error:ObjectAlreadyUpdated'));
			}
			else
			{
				$sActionLabel = $aStimuli[$sStimulus]->GetLabel();
				$sActionDetails = $aStimuli[$sStimulus]->GetDescription();
				$aTransition = $aTransitions[$sStimulus];
				$sTargetState = $aTransition['target_state'];
				$aTargetStates = MetaModel::EnumStates($sClass);
				//$oP->add("<div class=\"page_header\">\n");
				//$oP->add("<h1>$sActionLabel - <span class=\"hilite\">{$oObj->GetName()}</span></h1>\n");
				//$oP->add("<p>$sActionDetails</p>\n");
				//$oP->p(Dict::Format('UI:Apply_Stimulus_On_Object_In_State_ToTarget_State', $sActionLabel, $oObj->GetName(), $oObj->GetStateLabel(), $sTargetState));
				//$oP->add("</div>\n");
				$aTargetState = $aTargetStates[$sTargetState];
				$aExpectedAttributes = $aTargetState['attribute_list'];
				$aDetails = array();
				foreach($aExpectedAttributes as $sAttCode => $iExpectCode)
				{
					if (($iExpectCode & (OPT_ATT_MUSTCHANGE|OPT_ATT_MUSTPROMPT)) || ($oObj->Get($sAttCode) == '') ) 
					{
						$paramValue = utils::ReadPostedParam("attr_$sAttCode", '');
						$oObj->Set($sAttCode, $paramValue);
					}
				}
				if ($oObj->ApplyStimulus($sStimulus))
				{
					$oMyChange = MetaModel::NewObject("CMDBChange");
					$oMyChange->Set("date", time());
					if (UserRights::IsImpersonated())
					{
						$sUserString = Dict::Format('UI:Archive_User_OnBehalfOf_User', UserRights::GetRealUser(), UserRights::GetUser());
					}
					else
					{
						$sUserString = UserRights::GetUser();
					}
					$oMyChange->Set("userinfo", $sUserString);
					$iChangeId = $oMyChange->DBInsert();
					$oObj->DBUpdateTracked($oMyChange);
					$oP->p(Dict::Format('UI:Class_Object_Updated', MetaModel::GetName(get_class($oObj)), $oObj->GetName()));
				}
			}
			$oObj->DisplayDetails($oP);
		}
		else
		{
			$oP->set_title(Dict::S('UI:ErrorPageTitle'));
			$oP->P(Dict::S('UI:ObjectDoesNotExist'));
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
			throw new ApplicationException(Dict::Format('UI:Error:4ParametersMissing', 'class', 'id', 'target_class', 'link_attr'));
		}
		require_once('../application/uilinkswizard.class.inc.php');
		$oWizard = new UILinksWizard($sClass, $sLinkAttr, $id, $sTargetClass);
		$oWizard->Display($oP, array('StartWithAdd' => $bAddObjects));		
		break;
	
		case 'do_modify_links':
		$aLinks = utils::ReadPostedParam('linkId', array());
		$sLinksToRemove = trim(utils::ReadPostedParam('linksToRemove', ''));
		$aLinksToRemove = array();
		if (!empty($sLinksToRemove))
		{
			$aLinksToRemove = explode(' ', trim($sLinksToRemove));
		}
		$sClass = utils::ReadPostedParam('class', '');
		$sLinkageAtt = utils::ReadPostedParam('linkage', '');
		$iObjectId = utils::ReadPostedParam('object_id', '');
		$sLinkingAttCode = utils::ReadPostedParam('linking_attcode', '');
		$oMyChange = MetaModel::NewObject("CMDBChange");
		$oMyChange->Set("date", time());
		if (UserRights::IsImpersonated())
		{
			$sUserString = Dict::Format('UI:Archive_User_OnBehalfOf_User', UserRights::GetRealUser(), UserRights::GetUser());
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
				$oLink = MetaModel::GetObject($sClass, $iLinkId);
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
				$oLink = MetaModel::GetObject($sClass, $iLinkId);
			
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
		$oObj = MetaModel::GetObject($sTargetClass, $iObjectId);
	
		$oSearch = new DBObjectSearch(get_class($oObj));
		$oBlock = new DisplayBlock($oSearch, 'search', false);
		$oBlock->Display($oP, 0);
		$oObj->DisplayDetails($oP);
		break;
		
		case 'swf_navigator':
		$sClass = utils::ReadParam('class', '');
		$id = utils::ReadParam('id', 0);
		$sRelation = utils::ReadParam('relation', 'impact');
		$width = 1000;
		$height = 700;
		$sParams = "pWidth=$width&pHeight=$height&drillUrl=".urlencode('../pages/UI.php?operation=details')."&displayController=false&xmlUrl=".urlencode("./xml.navigator.php")."&obj_class=$sClass&obj_id=$id&relation=$sRelation";
		
		$oP->add("<object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0\" width=\"$width\" height=\"$height\" id=\"navigator\" align=\"middle\">
		<param name=\"allowScriptAccess\" value=\"sameDomain\" />
		<param name=\"allowFullScreen\" value=\"false\" />
		<param name=\"FlashVars\" value=\"$sParams\" />
		<param name=\"movie\" value=\"../navigator/navigator.swf\" /><param name=\"quality\" value=\"high\" /><param name=\"bgcolor\" value=\"#ffffff\" />
		<embed src=\"../navigator/navigator.swf\" flashVars=\"$sParams\" quality=\"high\" bgcolor=\"#ffffff\" width=\"$width\" height=\"$height\" name=\"navigator\" align=\"middle\" allowScriptAccess=\"sameDomain\" allowFullScreen=\"false\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.adobe.com/go/getflashplayer\" />
		</object>\n");
		break;
	
		default:
		$oMenuNode = ApplicationMenu::GetMenuNode(ApplicationMenu::GetActiveNodeId());
		if (is_object($oMenuNode))
		{
		
			$oMenuNode->RenderContent($oP, $oAppContext->GetAsHash());
			$oP->set_title($oMenuNode->GetLabel());
		}
	}
	$oKPI->ComputeAndReport('GUI creation before output');

	ExecutionKPI::ReportStats();
	MetaModel::ShowQueryTrace();

	DisplayWelcomePopup($oP);
	$oP->output();	
}
catch(CoreException $e)
{
	require_once('../setup/setuppage.class.inc.php');
	$oP = new SetupWebPage(Dict::S('UI:PageTitle:FatalError'));
	if ($e instanceof SecurityException)
	{
		$oP->add("<h1>".Dict::S('UI:SystemIntrusion')."</h1>\n");
	}
	else
	{
		$oP->add("<h1>".Dict::S('UI:FatalErrorMessage')."</h1>\n");
	}	
	$oP->error(Dict::Format('UI:Error_Details', $e->getHtmlDesc()));	
	$oP->output();

	if (MetaModel::IsLogEnabledIssue())
	{
		if (MetaModel::IsValidClass('EventIssue'))
		{
			try
			{
				$oLog = new EventIssue();
	
				$oLog->Set('message', $e->getMessage());
				$oLog->Set('userinfo', '');
				$oLog->Set('issue', $e->GetIssue());
				$oLog->Set('impact', 'Page could not be displayed');
				$oLog->Set('callstack', $e->getTrace());
				$oLog->Set('data', $e->getContextData());
				$oLog->DBInsertNoReload();
			}
			catch(Exception $e)
			{
				IssueLog::Error("Failed to log issue into the DB");
			}
		}

		IssueLog::Error($e->getMessage());
	}

	// For debugging only
	//throw $e;
}
catch(Exception $e)
{
	require_once('../setup/setuppage.class.inc.php');
	$oP = new SetupWebPage(Dict::S('UI:PageTitle:FatalError'));
	$oP->add("<h1>".Dict::S('UI:FatalErrorMessage')."</h1>\n");	
	$oP->error(Dict::Format('UI:Error_Details', $e->getMessage()));	
	$oP->output();

	if (MetaModel::IsLogEnabledIssue())
	{
		if (MetaModel::IsValidClass('EventIssue'))
		{
			try
			{
				$oLog = new EventIssue();
	
				$oLog->Set('message', $e->getMessage());
				$oLog->Set('userinfo', '');
				$oLog->Set('issue', 'PHP Exception');
				$oLog->Set('impact', 'Page could not be displayed');
				$oLog->Set('callstack', $e->getTrace());
				$oLog->Set('data', array());
				$oLog->DBInsertNoReload();
			}
			catch(Exception $e)
			{
				IssueLog::Error("Failed to log issue into the DB");
			}
		}

		IssueLog::Error($e->getMessage());
	}
}
?>
