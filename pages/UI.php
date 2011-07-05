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
	$oDeletionPlan = new DeletionPlan();

	foreach($aObjects as $oObj)
	{
		if ($bDeleteConfirmed)
		{
			// Prepare the change reporting
			//
			$oMyChange = MetaModel::NewObject("CMDBChange");
			$oMyChange->Set("date", time());
			$sUserString = CMDBChange::GetCurrentUserName();
			$oMyChange->Set("userinfo", $sUserString);
			$oMyChange->DBInsert();

			$oObj->DBDeleteTracked($oMyChange, null, $oDeletionPlan);
		}
		else
		{
			$oObj->CheckToDelete($oDeletionPlan);
		}
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
		if ($oDeletionPlan->FoundSecurityIssue())
		{
			throw new CoreException(Dict::S('UI:Error:NotEnoughRightsToDelete'));
		}
		if ($oDeletionPlan->FoundManualOperation())
		{
			throw new CoreException(Dict::S('UI:Error:CannotDeleteBecauseManualOpNeeded'));
		}
		if ($oDeletionPlan->FoundManualDelete())
		{
			throw new CoreException(Dict::S('UI:Error:CannotDeleteBecauseOfDepencies'));
		}

		// Report deletions
		//
		$aDisplayData = array();
		foreach ($oDeletionPlan->ListDeletes() as $sTargetClass => $aDeletes)
		{
			foreach ($aDeletes as $iId => $aData)
			{
				$oToDelete = $aData['to_delete'];

				if (isset($aData['requested_explicitely']))
				{
					$sMessage = Dict::S('UI:Delete:Deleted');
				}
				else
				{
					$sMessage = Dict::S('UI:Delete:AutomaticallyDeleted');
				}
				$aDisplayData[] = array(
					'class' => MetaModel::GetName(get_class($oToDelete)),
					'object' => $oToDelete->GetHyperLink(),
					'consequence' => $sMessage,
				);
			}
		}
	
		// Report updates
		//
		foreach ($oDeletionPlan->ListUpdates() as $sTargetClass => $aToUpdate)
		{
			foreach ($aToUpdate as $iId => $aData)
			{
				$oToUpdate = $aData['to_reset'];
				$aDisplayData[] = array(
					'class' => MetaModel::GetName(get_class($oToUpdate)),
					'object' => $oToUpdate->GetHyperLink(),
					'consequence' => Dict::Format('UI:Delete:AutomaticResetOf_Fields', $aData['attributes_list']),
				);
			}
		}

		// Report automatic jobs
		//
		if ($oDeletionPlan->GetTargetCount() > 0)
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
		foreach ($oDeletionPlan->ListDeletes() as $sTargetClass => $aDeletes)
		{
			foreach ($aDeletes as $iId => $aData)
			{
				$oToDelete = $aData['to_delete'];
				$bAutoDel = (($aData['mode'] == DEL_SILENT) || ($aData['mode'] == DEL_AUTO));
				if (array_key_exists('issue', $aData))
				{
					if ($bAutoDel)
					{
						if (isset($aData['requested_explicitely']))
						{
							$sConsequence = Dict::Format('UI:Delete:CannotDeleteBecause', $aData['issue']);
						}
						else
						{
							$sConsequence = Dict::Format('UI:Delete:ShouldBeDeletedAtomaticallyButNotPossible', $aData['issue']);
						}
					}
					else
					{
						$sConsequence = Dict::Format('UI:Delete:MustBeDeletedManuallyButNotPossible', $aData['issue']);
					}
				}
				else
				{
					if ($bAutoDel)
					{
						if (isset($aData['requested_explicitely']))
						{
	                  $sConsequence = ''; // not applicable
						}
						else
						{
							$sConsequence = Dict::S('UI:Delete:WillBeDeletedAutomatically');
						}
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
		foreach ($oDeletionPlan->ListUpdates() as $sRemoteClass => $aToUpdate)
		{
			foreach ($aToUpdate as $iId => $aData)
			{
				$oToUpdate = $aData['to_reset'];
				if (array_key_exists('issue', $aData))
				{
					$sConsequence = Dict::Format('UI:Delete:CannotUpdateBecause_Issue', $aData['issue']);
				}
				else
				{
					$sConsequence = Dict::Format('UI:Delete:WillAutomaticallyUpdate_Fields', $aData['attributes_list']);
				}
				$aDisplayData[] = array(
					'class' => MetaModel::GetName(get_class($oToUpdate)),
					'object' => $oToUpdate->GetHyperLink(),
					'consequence' => $sConsequence,
				);
			}
		}

      $iImpactedIndirectly = $oDeletionPlan->GetTargetCount() - count($aObjects);
		if ($iImpactedIndirectly > 0)
		{
			if (count($aObjects) == 1)
			{
				$oObj = $aObjects[0];
				$oP->p(Dict::Format('UI:Delete:Count_Objects/LinksReferencing_Object', $iImpactedIndirectly, $oObj->GetName()));
			}
			else
			{
				$oP->p(Dict::Format('UI:Delete:Count_Objects/LinksReferencingTheObjects', $iImpactedIndirectly));
			}
			$oP->p(Dict::S('UI:Delete:ReferencesMustBeDeletedToEnsureIntegrity'));
		}

		if (($iImpactedIndirectly > 0) || $oDeletionPlan->FoundStopper())
		{
			$aDisplayConfig = array();
			$aDisplayConfig['class'] = array('label' => 'Class', 'description' => '');
			$aDisplayConfig['object'] = array('label' => 'Object', 'description' => '');
			$aDisplayConfig['consequence'] = array('label' => 'Consequence', 'description' => Dict::S('UI:Delete:Consequence+'));
			$oP->table($aDisplayConfig, $aDisplayData);
		}

		if ($oDeletionPlan->FoundStopper())
		{
			if ($oDeletionPlan->FoundSecurityIssue())
			{
				$oP->p(Dict::S('UI:Delete:SorryDeletionNotAllowed'));
			}
			elseif ($oDeletionPlan->FoundManualOperation())
			{
				$oP->p(Dict::S('UI:Delete:PleaseDoTheManualOperations'));
			}
			else // $bFoundManualOp
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
				foreach($aObjects as $oObj)
				{
					$aKeys[] = $oObj->GetKey();
				}
				$oFilter = new DBObjectSearch($sClass);
				$oFilter->AddCondition('id', $aKeys, 'IN');
				$oSet = new CMDBobjectSet($oFilter);
				$oP->add('<div id="0">');
				CMDBAbstractObject::DisplaySet($oP, $oSet, array('display_limit' => false, 'menu' => false));
				$oP->add("</div>\n");
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
	$('#welcome_popup').dialog( { width:'80%', height: 'auto', title: '$sTitle', autoOpen: true, modal:true,
								  close: function() {
								  	var bDisplay = $('#display_welcome_popup:checked').length;
								  	SetUserPreference('welcome_popup', bDisplay, true); 
								  }
								  });
	if ($('#welcome_popup').height() > ($(window).height()-70))
	{
		$('#welcome_popup').height($(window).height()-70);
	}
EOF
);
				$_SESSION['welcome'] = 'ok';
			}
		}
	}	
}

/**
 * Displays the details of an object
 * @param $oP WebPage Page for the output
 * @param $sClass string The name of the class of the object
 * @param $oObj DBObject The object to display
 * @param $id mixed Identifier of the object (name or ID)
 */
function DisplayDetails($oP, $sClass, $oObj, $id)
{
	$sClassLabel = MetaModel::GetName($sClass);
	$oSearch = new DBObjectSearch($sClass);
	$oBlock = new DisplayBlock($oSearch, 'search', false);
	$oBlock->Display($oP, 0);

	// The object could be listed, check if it is actually allowed to view it
	$oSet = CMDBObjectSet::FromObject($oObj);
	if (UserRights::IsActionAllowed($sClass, UR_ACTION_READ, $oSet) == UR_ALLOWED_NO)
	{
		throw new SecurityException('User not allowed to view this object', array('class' => $sClass, 'id' => $id));
	}
	$oP->set_title(Dict::Format('UI:DetailsPageTitle', $oObj->GetName(), $sClassLabel));
	$oObj->DisplayDetails($oP);
}

/**
 * Displays the result of a search request
 * @param $oP WebPage Web page for the output
 * @param $oFilter DBObjectSearch The search of objects to display
 * @param $bSearchForm boolean Whether or not to display the search form at the top the page
 * @param $sBaseClass string The base class for the search (can be different from the actual class of the results)
 * @param $sFormat string The format to use for the output: csv or html
 */
function DisplaySearchSet($oP, $oFilter, $bSearchForm = true, $sBaseClass = '', $sFormat = '')
{
	$oSet = new DBObjectSet($oFilter);
	if ($bSearchForm)
	{
		$aParams = array('open' => true);
		if (!empty($sBaseClass))
		{
			$aParams['baseClass'] = $sBaseClass;
		}
		$oBlock = new DisplayBlock($oFilter, 'search', false /* Asynchronous */, $aParams);
		$oBlock->Display($oP, 0);
	}
	if (strtolower($sFormat) == 'csv')
	{
		$oBlock = new DisplayBlock($oFilter, 'csv', false);
		$oBlock->Display($oP, 1);
		// Adjust the size of the Textarea containing the CSV to fit almost all the remaining space
		$oP->add_ready_script(" $('#1>textarea').height($('#1').parent().height() - $('#0').outerHeight() - 30).width( $('#1').parent().width() - 20);"); // adjust the size of the block
	}
	else
	{
		$oBlock = new DisplayBlock($oFilter, 'list', false);
		$oBlock->Display($oP, 1);
	}
}

/**
 * Displays a form (checkboxes) to select the objects for which to apply a given action
 * Only the objects for which the action is valid can be checked. By default all valid objects are checked
 * @param $oP WebPage The page for output
 * @param $oFilter DBObjectSearch The filter that defines the list of objects
  * @param $sNextOperation string The next operation (code) to be executed when the form is submitted
 * @param $oChecker ActionChecker The helper class/instance used to check for which object the action is valid
 * @return none
 */
function DisplayMultipleSelectionForm($oP, $oFilter, $sNextOperation, $oChecker, $aExtraFormParams = array())
{
		$oAppContext = new ApplicationContext();
		$iBulkActionAllowed = $oChecker->IsAllowed();
		$sClass = $oFilter->GetClass();
		$aExtraParams = array('selection_type' => 'multiple', 'selection_mode' => true, 'display_limit' => false, 'menu' => false);
		if ($iBulkActionAllowed == UR_ALLOWED_DEPENDS)
		{
			$aAllowed = array();
			$aExtraParams['selection_enabled'] = $oChecker->GetAllowedIDs();
		}
		else if(UR_ALLOWED_NO)
		{
			throw new ApplicationException(Dict::Format('UI:ActionNotAllowed'));
		}
		
		$oBlock = new DisplayBlock($oFilter, 'list', false);
		$oP->add("<form method=\"post\" action=\"./UI.php\">\n");
		$oP->add("<input type=\"hidden\" name=\"operation\" value=\"$sNextOperation\">\n");
		$oP->add("<input type=\"hidden\" name=\"class\" value=\"".$oFilter->GetClass()."\">\n");
		$oP->add("<input type=\"hidden\" name=\"filter\" value=\"".$oFilter->Serialize()."\">\n");
		foreach($aExtraFormParams as $sName => $sValue)
		{
			$oP->add("<input type=\"hidden\" name=\"$sName\" value=\"$sValue\">\n");
		}
			$oP->add($oAppContext->GetForForm());
		$oBlock->Display($oP, 1, $aExtraParams);
		$oP->add("<input type=\"button\" value=\"".Dict::S('UI:Button:Cancel')."\" onClick=\"window.history.back()\">&nbsp;&nbsp;<input type=\"submit\" value=\"".Dict::S('UI:Button:Next')."\">\n");
		$oP->add("</form>\n");
		$oP->add_ready_script("CheckAll('.selectList1:not(:disabled)', true);\n");
}

/***********************************************************************************
 * 
 * Main user interface page, starts here
 *
 * ***********************************************************************************/
try
{
	require_once('../approot.inc.php');
	require_once(APPROOT.'/application/application.inc.php');
	require_once(APPROOT.'/application/itopwebpage.class.inc.php');
	require_once(APPROOT.'/application/wizardhelper.class.inc.php');

	require_once(APPROOT.'/application/startup.inc.php');
	$operation = utils::ReadParam('operation', '');

	$oKPI = new ApplicationStartupKPI();
	$oKPI->ComputeAndReport('Load of data model and all includes');

	$oKPI = new ExecutionKPI();

	require_once(APPROOT.'/application/loginwebpage.class.inc.php');
	$sLoginMessage = LoginWebPage::DoLogin(); // Check user rights and prompt if needed
	$oAppContext = new ApplicationContext();

	$oKPI->ComputeAndReport('User login');

	$oP = new iTopWebPage(Dict::S('UI:WelcomeToITop'));
	$oP->SetMessage($sLoginMessage);

	// All the following actions use advanced forms that require more javascript to be loaded
	switch($operation)
	{
		case 'new': // Form to create a new object
		case 'modify': // Form to modify an object
		case 'apply_new': // Creation of a new object
		case 'apply_modify': // Applying the modifications to an existing object
		case 'form_for_modify_all': // Form to modify multiple objects (bulk modify)
		case 'bulk_stimulus': // For to apply a stimulus to multiple objects
		case 'stimulus': // Form displayed when applying a stimulus (state change)
		$oP->add_linked_script("../js/json.js");
		$oP->add_linked_script("../js/forms-json-utils.js");
		$oP->add_linked_script("../js/wizardhelper.js");
		$oP->add_linked_script("../js/wizard.utils.js");
		$oP->add_linked_script("../js/linkswidget.js");
		$oP->add_linked_script("../js/extkeywidget.js");
		$oP->add_linked_script("../js/jquery.blockUI.js");
		break;		
	}
		
	switch($operation)
	{
		///////////////////////////////////////////////////////////////////////////////////////////
		
		case 'details': // Details of an object
			$sClass = utils::ReadParam('class', '');
			$id = utils::ReadParam('id', '');
		if ( empty($sClass) || empty($id))
			{
				throw new ApplicationException(Dict::Format('UI:Error:2ParametersMissing', 'class', 'id'));
			}

			if (is_numeric($id))
			{
				$oObj = MetaModel::GetObject($sClass, $id, false /* MustBeFound */);
			}
			else
			{
				$oObj = MetaModel::GetObjectByName($sClass, $id, false /* MustBeFound */);
			}
			if (is_null($oObj))
			{
				$oP->set_title(Dict::S('UI:ErrorPageTitle'));
				$oP->P(Dict::S('UI:ObjectDoesNotExist'));
			}
			else
			{
			DisplayDetails($oP, $sClass, $oObj, $id);
			}
		break;
	
		///////////////////////////////////////////////////////////////////////////////////////////

		case 'search_oql': // OQL query
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
			$oFilter = DBObjectSearch::FromOQL($sOQL);
			DisplaySearchSet($oP, $oFilter, $bSearchForm, $sBaseClass, $sFormat);
			}
			catch(CoreException $e)
			{
			$oFilter = new DBObjectSearch($sOQLClass);
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
		
		///////////////////////////////////////////////////////////////////////////////////////////

		case 'search_form': // Search form
			$sClass = utils::ReadParam('class', '');
			$sFormat = utils::ReadParam('format', 'html');
			$bSearchForm = utils::ReadParam('search_form', true);
			if (empty($sClass))
			{
				throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'class'));
			}
			$oP->set_title(Dict::S('UI:SearchResultsPageTitle'));
			$oFilter =  new DBObjectSearch($sClass);
		DisplaySearchSet($oP, $oFilter, $bSearchForm, '' /* sBaseClass */, $sFormat);
		break;
		
		///////////////////////////////////////////////////////////////////////////////////////////

		case 'search': // Serialized CMDBSearchFilter
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
			DisplaySearchSet($oP, $oFilter, $bSearchForm, '' /* sBaseClass */, $sFormat);
		break;
	
		///////////////////////////////////////////////////////////////////////////////////////////

		case 'full_text': // Global "google-like" search
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
	
		///////////////////////////////////////////////////////////////////////////////////////////

		case 'modify': // Form to modify an object
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
	
		///////////////////////////////////////////////////////////////////////////////////////////

		case 'select_for_modify_all': // Select the list of objects to be modified (bulk modify)
		$oP->set_title(Dict::S('UI:ModifyAllPageTitle'));
		$sFilter = utils::ReadParam('filter', '');
		if (empty($sFilter))
		{
			throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'filter'));
		}
		// TO DO: limit the search filter by the user context
		$oFilter = DBObjectSearch::unserialize($sFilter); // TO DO : check that the filter is valid
		$sClass = $oFilter->GetClass();	
		$oChecker = new ActionChecker($oFilter, UR_ACTION_BULK_MODIFY);
		$oP->add("<h1>Modify All...</h1>\n");			
		
		DisplayMultipleSelectionForm($oP, $oFilter, 'form_for_modify_all', $oChecker);
		break;	
		///////////////////////////////////////////////////////////////////////////////////////////

		case 'form_for_modify_all': // Form to modify multiple objects (bulk modify)
		$sFilter = utils::ReadParam('filter', '');
		$sClass = utils::ReadParam('class', '');
		$oFullSetFilter = DBObjectSearch::unserialize($sFilter);
		$aSelectedObj = utils::ReadMultipleSelection($oFullSetFilter);
		if (count($aSelectedObj) > 0)
		{
			$iAllowedCount = count($aSelectedObj);
			$sSelectedObj = implode(',', $aSelectedObj);

			$sOQL = "SELECT $sClass WHERE id IN (".$sSelectedObj.")";
			$oSet = new CMDBObjectSet(DBObjectSearch::FromOQL($sOQL));
			
			// Compute the distribution of the values for each field to determine which of the "scalar" fields are homogenous
			$aList = MetaModel::ListAttributeDefs($sClass);
			$aValues = array();
			foreach($aList as $sAttCode => $oAttDef)
			{
				if ($oAttDef->IsScalar())
				{
					$aValues[$sAttCode] = array();
				}
			}
			while($oObj = $oSet->Fetch())
			{
				foreach($aList as $sAttCode => $oAttDef)
				{
					if ($oAttDef->IsScalar() && $oAttDef->IsWritable())
					{
						$currValue = $oObj->Get($sAttCode);
						if (is_object($currValue)) continue; // Skip non scalar values...
						if(!array_key_exists($currValue, $aValues[$sAttCode]))
						{
							$aValues[$sAttCode][$currValue] = array('count' => 1, 'display' => $oObj->GetAsHTML($sAttCode)); 
						}
						else
						{
							$aValues[$sAttCode][$currValue]['count']++; 
						}
					}
				}
			}
			// Now create an object that has values for the homogenous values only				
			$oDummyObj = new $sClass(); // @@ What if the class is abstract ?
			$aComments = array();
			function MyComparison($a, $b) // Sort descending
			{
			    if ($a['count'] == $b['count'])
			    {
			        return 0;
			    }
			    return ($a['count'] > $b['count']) ? -1 : 1;
			}

			$iFormId = cmdbAbstractObject::GetNextFormId(); // Identifier that prefixes all the form fields
			$sReadyScript = '';
			$aDependsOn = array();
			$sFormPrefix = '2_';
			foreach($aList as $sAttCode => $oAttDef)
			{
				$aPrerequisites = MetaModel::GetPrequisiteAttributes($sClass, $sAttCode); // List of attributes that are needed for the current one
				if (count($aPrerequisites) > 0)
				{
					// When 'enabling' a field, all its prerequisites must be enabled too
					$sFieldList = "['{$sFormPrefix}".implode("','{$sFormPrefix}", $aPrerequisites)."']";
					$oP->add_ready_script("$('#enable_{$sFormPrefix}{$sAttCode}').bind('change', function(evt, sFormId) { return PropagateCheckBox( this.checked, $sFieldList, true); } );\n");
				}
				$aDependents = MetaModel::GetDependentAttributes($sClass, $sAttCode); // List of attributes that are needed for the current one
				if (count($aDependents) > 0)
				{
					// When 'disabling' a field, all its dependent fields must be disabled too
					$sFieldList = "['{$sFormPrefix}".implode("','{$sFormPrefix}", $aDependents)."']";
					$oP->add_ready_script("$('#enable_{$sFormPrefix}{$sAttCode}').bind('change', function(evt, sFormId) { return PropagateCheckBox( this.checked, $sFieldList, false); } );\n");
				}
				if ($oAttDef->IsScalar() && $oAttDef->IsWritable())
				{
					if ($oAttDef->GetEditClass() == 'One Way Password')
					{
						
						$sTip = "Unknown values";
						$sReadyScript .= "$('#multi_values_$sAttCode').qtip( { content: '$sTip', show: 'mouseover', hide: 'mouseout', style: { name: 'dark', tip: 'leftTop' }, position: { corner: { target: 'rightMiddle', tooltip: 'leftTop' }} } );";

						$oDummyObj->Set($sAttCode, null);
						$aComments[$sAttCode] = '<input type="checkbox" id="enable_'.$iFormId.'_'.$sAttCode.'" onClick="ToogleField(this.checked, \''.$iFormId.'_'.$sAttCode.'\')"/>';
						$aComments[$sAttCode] .= '<div class="multi_values" id="multi_values_'.$sAttCode.'"> ? </div>';
						$sReadyScript .=  'ToogleField(false, \''.$iFormId.'_'.$sAttCode.'\');'."\n";
					}
					else
					{
						$iCount = count($aValues[$sAttCode]);
						if ($iCount == 1)
						{
							// Homogenous value
							reset($aValues[$sAttCode]);
							$aKeys = array_keys($aValues[$sAttCode]);
							$currValue = $aKeys[0]; // The only value is the first key
							//echo "<p>current value for $sAttCode : $currValue</p>";
							$oDummyObj->Set($sAttCode, $currValue);
							$aComments[$sAttCode] = '<input type="checkbox" checked id="enable_'.$iFormId.'_'.$sAttCode.'"  onClick="ToogleField(this.checked, \''.$iFormId.'_'.$sAttCode.'\')"/>';
							$aComments[$sAttCode] .= '<div class="mono_value">1</div>';
						}
						else
						{
							// Non-homogenous value
							$aMultiValues = $aValues[$sAttCode];
							uasort($aMultiValues, 'MyComparison');
							$iMaxCount = 5;
							$sTip = "<p><b>".Dict::Format('UI:BulkModify_Count_DistinctValues', $iCount)."</b><ul>";
							$index = 0;
							foreach($aMultiValues as $sCurrValue => $aVal)
							{
								$sDisplayValue = empty($aVal['display']) ? '<i>'.Dict::S('Enum:Undefined').'</i>' : str_replace(array("\n", "\r"), " ", $aVal['display']);
								$sTip .= "<li>".Dict::Format('UI:BulkModify:Value_Exists_N_Times', $sDisplayValue, $aVal['count'])."</li>";
								$index++;
								if ($iMaxCount == $index)
								{
									$sTip .= "<li>".Dict::Format('UI:BulkModify:N_MoreValues', count($aMultiValues) - $iMaxCount)."</li>";
									break;
								}					
							}
							$sTip .= "</ul></p>";
							$sReadyScript .= "$('#multi_values_$sAttCode').qtip( { content: '$sTip', show: 'mouseover', hide: 'mouseout', style: { name: 'dark', tip: 'leftTop' }, position: { corner: { target: 'rightMiddle', tooltip: 'leftTop' }} } );";
	
							$oDummyObj->Set($sAttCode, null);
							$aComments[$sAttCode] = '<input type="checkbox" id="enable_'.$iFormId.'_'.$sAttCode.'" onClick="ToogleField(this.checked, \''.$iFormId.'_'.$sAttCode.'\')"/>';
							$aComments[$sAttCode] .= '<div class="multi_values" id="multi_values_'.$sAttCode.'">'.$iCount.'</div>';
						}
						$sReadyScript .=  'ToogleField('.(($iCount == 1) ? 'true': 'false').', \''.$iFormId.'_'.$sAttCode.'\');'."\n";
					}
				}
			}				
			
			$sStateAttCode = MetaModel::GetStateAttributeCode($sClass);
			if (($sStateAttCode != '') && ($oDummyObj->GetState() == ''))
			{
				// Hmmm, it's not gonna work like this ! Set a default value for the "state"
				// Maybe we should use the "state" that is the most common among the objects...
				$aMultiValues = $aValues[$sStateAttCode];
				uasort($aMultiValues, 'MyComparison');
				foreach($aMultiValues as $sCurrValue => $aVal)
				{
					$oDummyObj->Set($sStateAttCode, $sCurrValue);
					break;
				}				
				//$oStateAtt = MetaModel::GetAttributeDef($sClass, $sStateAttCode);
				//$oDummyObj->Set($sStateAttCode, $oStateAtt->GetDefaultValue());
			}
			$oP->add("<div class=\"page_header\">\n");
			$oP->add("<h1>".$oDummyObj->GetIcon()."&nbsp;".Dict::Format('UI:Modify_M_ObjectsOf_Class_OutOf_N', $iAllowedCount, $sClass, $iAllowedCount)."</h1>\n");
			$oP->add("</div>\n");

			$oP->add("<div class=\"wizContainer\">\n");
			$oDummyObj->DisplayModifyForm($oP, array('fieldsComments' => $aComments, 'noRelations' => true, 'custom_operation' => 'preview_or_modify_all', 'custom_button' => Dict::S('UI:Button:PreviewModifications'), 'selectObj' => $sSelectedObj, 'filter' => $sFilter, 'preview_mode' => true, 'disabled_fields' => '{}'));
			$oP->add("</div>\n");
			$oP->add_ready_script($sReadyScript);
			$sURL = "./UI.php?operation=search&filter=$sFilter&".$oAppContext->GetForLink();
			$oP->add_ready_script(
<<<EOF
$('.wizContainer button.cancel').unbind('click');
$('.wizContainer button.cancel').click( function() { window.location.href = '$sURL'; } );
EOF
);

		} // Else no object selected ???
		else
		{
			$oP->p("No object selected !, nothing to do");
		}
		break;
		
		///////////////////////////////////////////////////////////////////////////////////////////

		case 'preview_or_modify_all': // Preview or apply bulk modify
		$sFilter = utils::ReadParam('filter', '');
		$sClass = utils::ReadParam('class', '');
		$bPreview = utils::ReadParam('preview_mode', '');
		$sSelectedObj = utils::ReadParam('selectObj', '');
		if ( empty($sClass) || empty($sSelectedObj)) // TO DO: check that the class name is valid !
		{
			throw new ApplicationException(Dict::Format('UI:Error:2ParametersMissing', 'class', 'selectObj'));
		}
		$aSelectedObj = explode(',', $sSelectedObj);
		$aHeaders = array(
			'form::select' => array('label' => "<input type=\"checkbox\" onClick=\"CheckAll('.selectList:not(:disabled)', this.checked);\"></input>", 'description' => Dict::S('UI:SelectAllToggle+')),
			'object' => array('label' => MetaModel::GetName($sClass), 'description' => Dict::S('UI:ModifiedObject')),
			'status' => array('label' => Dict::S('UI:BulkModifyStatus'), 'description' => Dict::S('UI:BulkModifyStatus+')),
			'errors' => array('label' => Dict::S('UI:BulkModifyErrors'), 'description' => Dict::S('UI:BulkModifyErrors+')),
		);
		$aRows = array();

		$oP->add("<div class=\"page_header\">\n");
		$oP->add("<h1>".MetaModel::GetClassIcon($sClass)."&nbsp;".Dict::Format('UI:Modify_N_ObjectsOf_Class', count($aSelectedObj), $sClass)."</h1>\n");
		$oP->add("</div>\n");
		$oP->set_title(Dict::Format('UI:Modify_N_ObjectsOf_Class', count($aSelectedObj), $sClass));
		if (!$bPreview)
		{
			// Not in preview mode, do the update for real
			$sTransactionId = utils::ReadPostedParam('transaction_id', '');
			if (!utils::IsTransactionValid($sTransactionId, false))
			{
				throw new Exception(Dict::S('UI:Error:ObjectAlreadyUpdated'));
			}
			$oMyChange = MetaModel::NewObject("CMDBChange");
			$oMyChange->Set("date", time());
			$sUserString = CMDBChange::GetCurrentUserName();
			$oMyChange->Set("userinfo", $sUserString);
			$iChangeId = $oMyChange->DBInsert();
			utils::RemoveTransaction($sTransactionId);
		}
		foreach($aSelectedObj as $iId)
		{
			$oObj = MetaModel::GetObject($sClass, $iId);
			$aErrors = $oObj->UpdateObject('');
			$bResult = (count($aErrors) == 0);
			if ($bResult)
			{
				list($bResult, $aErrors) = $oObj->CheckToWrite(true /* Enforce Read-only fields */);
			}
			if ($bPreview)
			{
				$sStatus = $bResult ? Dict::S('UI:BulkModifyStatusOk') : Dict::S('UI:BulkModifyStatusError');
			}
			else
			{
				$sStatus = $bResult ? Dict::S('UI:BulkModifyStatusModified') : Dict::S('UI:BulkModifyStatusSkipped');
			}
			$sCSSClass = $bResult ? HILIGHT_CLASS_NONE : HILIGHT_CLASS_CRITICAL;
			$sChecked = $bResult ? 'checked' : '';
			$sDisabled = $bResult ? '' : 'disabled';
			$aRows[] = array(
				'form::select' => "<input type=\"checkbox\" class=\"selectList\" $sChecked $sDisabled\"></input>",
				'object' => $oObj->GetHyperlink(),
				'status' => $sStatus,
				'errors' => '<p>'.($bResult ? '': implode('</p><p>', $aErrors)).'</p>',
				'@class' => $sCSSClass,
			);
			if ($bResult && (!$bPreview))
			{
				$oObj->DBUpdateTracked($oMyChange);
			}
		}
		$oP->Table($aHeaders, $aRows);
		$sURL = "./UI.php?operation=search&filter=$sFilter&".$oAppContext->GetForLink();
		if ($bPreview)
		{
			// Form to submit:
			$oP->add("<form method=\"post\" action=\"UI.php\" enctype=\"multipart/form-data\">\n");
			$aDefaults = utils::ReadParam('default', array());
			$oP->add($oAppContext->GetForForm());
			$oP->add("<input type=\"hidden\" name=\"class\" value=\"$sClass\">\n");
			$oP->add("<input type=\"hidden\" name=\"filter\" value=\"$sFilter\">\n");
			$oP->add("<input type=\"hidden\" name=\"selectObj\" value=\"$sSelectedObj\">\n");
			$oP->add("<input type=\"hidden\" name=\"operation\" value=\"preview_or_modify_all\">\n");
			$oP->add("<input type=\"hidden\" name=\"preview_mode\" value=\"0\">\n");
			$oP->add("<input type=\"hidden\" name=\"transaction_id\" value=\"".utils::GetNewTransactionId()."\">\n");
			$oP->add("<button type=\"button\" class=\"action cancel\" onClick=\"window.location.href='$sURL'\">".Dict::S('UI:Button:Cancel')."</button>&nbsp;&nbsp;&nbsp;&nbsp;\n");
			$oP->add("<button type=\"submit\" class=\"action\"><span>".Dict::S('UI:Button:ModifyAll')."</span></button>\n");
			foreach($_POST as $sKey => $value)
			{
				if (preg_match('/attr_(.+)/', $sKey, $aMatches))
				{
					$oP->add("<input type=\"hidden\" name=\"$sKey\" value=\"$value\">\n");
				}
			}
			$oP->add("</form>\n");
		}
		else
		{
			$sURL = "./UI.php?operation=search&filter=$sFilter&".$oAppContext->GetForLink();
			$oP->add("<button type=\"button\" onClick=\"window.location.href='$sURL'\" class=\"action\"><span>".Dict::S('UI:Button:Done')."</span></button>\n");
		}
		break;

		///////////////////////////////////////////////////////////////////////////////////////////

		case 'new': // Form to create a new object
			$sClass = utils::ReadParam('class', '');
			$sStateCode = utils::ReadParam('state', '');
			$bCheckSubClass = utils::ReadParam('checkSubclass', true);
			if ( empty($sClass) )
			{
				throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'class'));
			}

			$aArgs = utils::ReadParam('default', array());
			$aContext = $oAppContext->GetAsHash();
			foreach( $oAppContext->GetNames() as $key)
			{
				$aArgs[$key] = $oAppContext->GetCurrentValue($key);	
			}

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
				foreach( $oAppContext->GetNames() as $key)
				{
					$aDefaults[$key] = $oAppContext->GetCurrentValue($key);	
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
	
		///////////////////////////////////////////////////////////////////////////////////////////

		case 'apply_modify': // Applying the modifications to an existing object
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
			elseif (!utils::IsTransactionValid($sTransactionId, false))
			{
				$oP->set_title(Dict::Format('UI:ModificationPageTitle_Object_Class', $oObj->GetName(), $sClassLabel));
				$oP->p("<strong>".Dict::S('UI:Error:ObjectAlreadyUpdated')."</strong>\n");
			}
			else
			{
				$oObj->UpdateObject();

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
						$sUserString = CMDBChange::GetCurrentUserName();
						$oMyChange->Set("userinfo", $sUserString);
						$iChangeId = $oMyChange->DBInsert();
						$oObj->DBUpdateTracked($oMyChange);
						utils::RemoveTransaction($sTransactionId);
			
						$oP->p(Dict::Format('UI:Class_Object_Updated', MetaModel::GetName(get_class($oObj)), $oObj->GetName()));
					}
					else
					{
						$bDisplayDetails = false;
						// Found issues, explain and give the user a second chance
						//
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

		///////////////////////////////////////////////////////////////////////////////////////////

		case 'select_for_deletion': // Select multiple objects for deletion
			$sFilter = utils::ReadParam('filter', '');
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
			$oP->add("<input type=\"hidden\" name=\"filter\" value=\"".$oFilter->Serialize()."\">\n");
			$oP->add("<input type=\"hidden\" name=\"transaction_id\" value=\"".utils::GetNewTransactionId()."\">\n");
			$oBlock->Display($oP, 1, array('selection_type' => 'multiple', 'selection_mode' => true, 'display_limit' => false, 'menu' => false));
			$oP->add("<input type=\"button\" value=\"".Dict::S('UI:Button:Cancel')."\" onClick=\"window.history.back()\">&nbsp;&nbsp;<input type=\"submit\" value=\"".Dict::S('UI:Button:Next')."\">\n");
			$oP->add("</form>\n");
		break;
		
		///////////////////////////////////////////////////////////////////////////////////////////

		case 'bulk_delete_confirmed': // Confirm bulk deletion of objects
			$sTransactionId = utils::ReadPostedParam('transaction_id', '');
			if (!utils::IsTransactionValid($sTransactionId))
			{
				throw new ApplicationException(Dict::S('UI:Error:ObjectsAlreadyDeleted'));
			}
		// Fall through
			
		///////////////////////////////////////////////////////////////////////////////////////////

		case 'bulk_delete': // Actual bulk deletion (if confirmed)
			$sClass = utils::ReadPostedParam('class', '');
			$sClassLabel = MetaModel::GetName($sClass);
			$sFilter = utils::ReadPostedParam('filter', '');
			$oFullSetFilter = DBObjectSearch::unserialize($sFilter);
			$aSelectObject = utils::ReadMultipleSelection($oFullSetFilter);
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
				throw new SecurityException(Dict::Format('UI:Error:BulkDeleteNotAllowedOn_Class', $sClass));
			}
			$oP->set_title(Dict::S('UI:BulkDeletePageTitle'));
			DeleteObjects($oP, $sClass, $aObjects, ($operation == 'bulk_delete_confirmed'));
		break;
			
		///////////////////////////////////////////////////////////////////////////////////////////

		case 'delete':				// Deletion (preview)
		case 'delete_confirmed':	// Deletion (confirmed)
		$sClass = utils::ReadParam('class', '');
		$sClassLabel = MetaModel::GetName($sClass);
		$id = utils::ReadParam('id', '');
		$oObj = MetaModel::GetObject($sClass, $id);
	
		if (!UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY, DBObjectSet::FromObject($oObj)))
		{
			throw new SecurityException(Dict::Format('UI:Error:DeleteNotAllowedOn_Class', $sClass));
		}
		DeleteObjects($oP, $sClass, array($oObj), ($operation == 'delete_confirmed'));
		break;
	
		///////////////////////////////////////////////////////////////////////////////////////////

		case 'apply_new': // Creation of a new object
		$sClass = utils::ReadPostedParam('class', '');
		$sClassLabel = MetaModel::GetName($sClass);
		$sTransactionId = utils::ReadPostedParam('transaction_id', '');
		if ( empty($sClass) ) // TO DO: check that the class name is valid !
		{
			throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'class'));
		}
		if (!utils::IsTransactionValid($sTransactionId, false))
		{
			$oP->p("<strong>".Dict::S('UI:Error:ObjectAlreadyCreated')."</strong>\n");
		}
		else
		{
			$oObj = MetaModel::NewObject($sClass);
			$oObj->UpdateObject();
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
				$sUserString = CMDBChange::GetCurrentUserName();
				$oMyChange->Set("userinfo", $sUserString);
				$iChangeId = $oMyChange->DBInsert();
				$oObj->DBInsertTracked($oMyChange);
				utils::RemoveTransaction($sTransactionId);
				$oP->set_title(Dict::S('UI:PageTitle:ObjectCreated'));
				$oP->add("<h1>".Dict::Format('UI:Title:Object_Of_Class_Created', $oObj->GetName(), $sClassLabel)."</h1>\n");
				$oObj->DisplayDetails($oP);
			}
			else
			{
				// Found issues, explain and give the user a second chance
				//
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
		
		///////////////////////////////////////////////////////////////////////////////////////////

		case 'wizard_apply_new': // no more used ???
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
				$sUserString = CMDBChange::GetCurrentUserName();
				$oMyChange->Set("userinfo", $sUserString);
				$iChangeId = $oMyChange->DBInsert();
				$oObj->DBInsertTracked($oMyChange);
				$oP->set_title(Dict::S('UI:PageTitle:ObjectCreated'));
				$oP->add("<h1>".Dict::Format('UI:Title:Object_Of_Class_Created', $oObj->GetName(), $sClassLabel)."</h1>\n");
				$oObj->DisplayDetails($oP);
			}
		}
		break;
	
		///////////////////////////////////////////////////////////////////////////////////////////

		case 'select_bulk_stimulus': // Form displayed when applying a stimulus to many objects
		$sFilter = utils::ReadParam('filter', '');
		$sStimulus = utils::ReadParam('stimulus', '');
		$sState = utils::ReadParam('state', '');
		if (empty($sFilter) || empty($sStimulus) || empty($sState))
		{
			throw new ApplicationException(Dict::Format('UI:Error:3ParametersMissing', 'filter', 'stimulus', 'state'));
		}
		$oFilter = DBObjectSearch::unserialize($sFilter);
		$sClass = $oFilter->GetClass();	
		$aStimuli = MetaModel::EnumStimuli($sClass);
		$sActionLabel = $aStimuli[$sStimulus]->GetLabel();
		$sActionDetails = $aStimuli[$sStimulus]->GetDescription();
		$oP->set_title($sActionLabel);
		$oP->add('<div class="page_header">');
		$oP->add('<h1>'.MetaModel::GetClassIcon($sClass).'&nbsp;'.$sActionLabel.'</h1>');
		$oP->add('</div>');

		$oChecker = new StimulusChecker($oFilter, $sState, $sStimulus);
		$aExtraFormParams = array('stimulus' => $sStimulus, 'state' => $sState);
		DisplayMultipleSelectionForm($oP, $oFilter, 'bulk_stimulus', $oChecker, $aExtraFormParams);
		break;
		
		case 'bulk_stimulus':
		$sFilter = utils::ReadParam('filter', '');
		$sStimulus = utils::ReadParam('stimulus', '');
		$sState = utils::ReadParam('state', '');
		if (empty($sFilter) || empty($sStimulus) || empty($sState))
		{
			throw new ApplicationException(Dict::Format('UI:Error:3ParametersMissing', 'filter', 'stimulus', 'state'));
		}
		$oFilter = DBObjectSearch::unserialize($sFilter);
		$sClass = $oFilter->GetClass();	
		$aSelectObj = utils::ReadMultipleSelection($oFilter);
		if (count($aSelectObject) == 0)
		{
			// Nothing to do, no object was selected !
			throw new ApplicationException(Dict::S('UI:BulkAction:NoObjectSelected'));
		}
		else
		{
			$aTransitions = MetaModel::EnumTransitions($sClass, $sState);
			$aStimuli = MetaModel::EnumStimuli($sClass);
			
			$sActionLabel = $aStimuli[$sStimulus]->GetLabel();
			$sActionDetails = $aStimuli[$sStimulus]->GetDescription();
			$aTransition = $aTransitions[$sStimulus];
			$sTargetState = $aTransition['target_state'];
			$aStates = MetaModel::EnumStates($sClass);
			$aTargetStateDef = $aStates[$sTargetState];
			
			$oP->set_title(Dict::Format('UI:StimulusModify_N_ObjectsOf_Class', $sActionLabel, count($aSelectObject), $sClass));
			$oP->add('<div class="page_header">');
			$oP->add('<h1>'.MetaModel::GetClassIcon($sClass).'&nbsp;'.Dict::Format('UI:StimulusModify_N_ObjectsOf_Class', $sActionLabel, count($aSelectObject), $sClass).'</h1>');
			$oP->add('</div>');

			$aExpectedAttributes = $aTargetStateDef['attribute_list'];
			$aDetails = array();
			$iFieldIndex = 0;
			$aFieldsMap = array();
			$aValues = array();
			$aObjects = array();
			foreach($aSelectObject as $iId)
			{
				$aObjects[] = MetaModel::GetObject($sClass, $iId);
			}
			$oSet = DBObjectSet::FromArray($sClass, $aObjects);
			$oObj = $oSet->ComputeCommonObject($aValues);
			$sStateAttCode = MetaModel::GetStateAttributeCode($sClass);
			$oObj->Set($sStateAttCode,$sTargetState);
			$sReadyScript = '';
			foreach($aExpectedAttributes as $sAttCode => $iExpectCode)
			{
				// Prompt for an attribute if
				// - the attribute must be changed or must be displayed to the user for confirmation
				// - or the field is mandatory and currently empty
				if ( ($iExpectCode & (OPT_ATT_MUSTCHANGE | OPT_ATT_MUSTPROMPT)) ||
					 (($iExpectCode & OPT_ATT_MANDATORY) && ($oObj->Get($sAttCode) == '')) ) 
				{
					$aAttributesDef = MetaModel::ListAttributeDefs($sClass);
					$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
					$aPrerequisites = MetaModel::GetPrequisiteAttributes($sClass, $sAttCode); // List of attributes that are needed for the current one
					if (count($aPrerequisites) > 0)
					{
						// When 'enabling' a field, all its prerequisites must be enabled too
						$sFieldList = "['".implode("','", $aPrerequisites)."']";
						$oP->add_ready_script("$('#enable_{$sAttCode}').bind('change', function(evt, sFormId) { return PropagateCheckBox( this.checked, $sFieldList, true); } );\n");
					}
					$aDependents = MetaModel::GetDependentAttributes($sClass, $sAttCode); // List of attributes that are needed for the current one
					if (count($aDependents) > 0)
					{
						// When 'disabling' a field, all its dependent fields must be disabled too
						$sFieldList = "['".implode("','", $aDependents)."']";
						$oP->add_ready_script("$('#enable_{$sAttCode}').bind('change', function(evt, sFormId) { return PropagateCheckBox( this.checked, $sFieldList, false); } );\n");
					}
					$aArgs = array('this' => $oObj);
					$sHTMLValue = cmdbAbstractObject::GetFormElementForField($oP, $sClass, $sAttCode, $oAttDef, $oObj->Get($sAttCode), $oObj->GetEditValue($sAttCode), $sAttCode, '', $iExpectCode, $aArgs);
					$sComments = '<input type="checkbox" checked id="enable_'.$sAttCode.'"  onClick="ToogleField(this.checked, \''.$sAttCode.'\')"/>';
					if (!isset($aValues[$sAttCode]))
					{
						$aValues[$sAttCode] = array();
					}
					if (count($aValues[$sAttCode]) == 1)
					{
						$sComments .= '<div class="mono_value">1</div>';
					}
					else
					{
						// Non-homogenous value
						$iMaxCount = 5;
						$sTip = "<p><b>".Dict::Format('UI:BulkModify_Count_DistinctValues', count($aValues[$sAttCode]))."</b><ul>";
						$index = 0;
						foreach($aValues[$sAttCode] as $sCurrValue => $aVal)
						{
							$sDisplayValue = empty($aVal['display']) ? '<i>'.Dict::S('Enum:Undefined').'</i>' : str_replace(array("\n", "\r"), " ", $aVal['display']);
							$sTip .= "<li>".Dict::Format('UI:BulkModify:Value_Exists_N_Times', $sDisplayValue, $aVal['count'])."</li>";
							$index++;
							if ($iMaxCount == $index)
							{
								$sTip .= "<li>".(count($aMultiValues) - $iMaxCount)." more different values...</li>";
								break;
							}					
							if ($iMaxCount == $index)
							{
								$sTip .= "<li>".Dict::Format('UI:BulkModify:N_MoreValues', count($aValues[$sAttCode]) - $iMaxCount)."</li>";
								break;
							}					
						}
						$sTip .= "</ul></p>";
						$sReadyScript .= "$('#multi_values_$sAttCode').qtip( { content: '$sTip', show: 'mouseover', hide: 'mouseout', style: { name: 'dark', tip: 'leftTop' }, position: { corner: { target: 'rightMiddle', tooltip: 'leftTop' }} } );\n";
						$sComments .= '<div class="multi_values" id="multi_values_'.$sAttCode.'">'.count($aValues[$sAttCode]).'</div>';
					}
					$aDetails[] = array('label' => '<span>'.$oAttDef->GetLabel().'</span>', 'value' => "<span id=\"field_$sAttCode\">$sHTMLValue</span>", 'comments' => $sComments);
					$aFieldsMap[$sAttCode] = $sAttCode;
					$iFieldIndex++;
				}
			}
			$oP->add('<div class="ui-widget-content">');
			$oObj->DisplayBareProperties($oP);
			$oP->add('</div>');
			$oP->add("<div class=\"wizContainer\">\n");
			$oP->add("<form id=\"apply_stimulus\" method=\"post\" onSubmit=\"return OnSubmit('apply_stimulus');\">\n");
			$oP->add("<table><tr><td>\n");
			$oP->details($aDetails);
			$oP->add("</td></tr></table>\n");
			$oP->add("<input type=\"hidden\" name=\"class\" value=\"$sClass\">\n");
			$oP->add("<input type=\"hidden\" name=\"operation\" value=\"bulk_apply_stimulus\">\n");
			$oP->add("<input type=\"hidden\" name=\"preview_mode\" value=\"1\">\n");
			$oP->add("<input type=\"hidden\" name=\"filter\" value=\"$sFilter\">\n");
			$oP->add("<input type=\"hidden\" name=\"stimulus\" value=\"$sStimulus\">\n");
			$oP->add("<input type=\"hidden\" name=\"state\" value=\"$sState\">\n");
			$oP->add("<input type=\"hidden\" name=\"transaction_id\" value=\"".utils::GetNewTransactionId()."\">\n");
			$oP->add($oAppContext->GetForForm());
			$oP->add("<input type=\"hidden\" name=\"selectObject\" value=\"".implode(',',$aSelectObject)."\">\n");
			$sURL = "./UI.php?operation=search&filter=$sFilter&".$oAppContext->GetForLink();
			$oP->add("<input type=\"button\" value=\"".Dict::S('UI:Button:Cancel')."\" onClick=\"window.location.href='$sURL'\">&nbsp;&nbsp;&nbsp;&nbsp;\n");
			$oP->add("<button type=\"submit\" class=\"action\"><span>$sActionLabel</span></button>\n");
			$oP->add("</form>\n");
			$oP->add("</div>\n");
			$iFieldsCount = count($aFieldsMap);
			$sJsonFieldsMap = json_encode($aFieldsMap);
	
			$oP->add_script(
<<<EOF
			// Initializes the object once at the beginning of the page...
			var oWizardHelper = new WizardHelper('$sClass', '', '$sTargetState');
			oWizardHelper.SetFieldsMap($sJsonFieldsMap);
			oWizardHelper.SetFieldsCount($iFieldsCount);
EOF
);
			$oP->add_ready_script(
<<<EOF
			// Starts the validation when the page is ready
			CheckFields('apply_stimulus', false);
			$sReadyScript
EOF
);
		}
		break;
		
		case 'bulk_apply_stimulus':
		$bPreviewMode = utils::ReadPostedParam('preview_mode', false);
		$sFilter = utils::ReadPostedParam('filter', '');
		$sStimulus = utils::ReadPostedParam('stimulus', '');
		$sState = utils::ReadPostedParam('state', '');
		$sSelectObject = utils::ReadPostedParam('selectObject', '');
		$aSelectObject = explode(',', $sSelectObject);

		if (empty($sFilter) || empty($sStimulus) || empty($sState))
		{
			throw new ApplicationException(Dict::Format('UI:Error:3ParametersMissing', 'filter', 'stimulus', 'state'));
		}
		$sTransactionId = utils::ReadPostedParam('transaction_id', '');
		if (!utils::IsTransactionValid($sTransactionId))
		{
			$oP->p(Dict::S('UI:Error:ObjectAlreadyUpdated'));
		}
		else
		{
			// For archiving the modification
			$oFilter = DBObjectSearch::unserialize($sFilter);
			$sClass = $oFilter->GetClass();
			$aObjects = array();
			foreach($aSelectObject as $iId)
			{
				$aObjects[] = MetaModel::GetObject($sClass, $iId);
			}

			$aTransitions = MetaModel::EnumTransitions($sClass, $sState);
			$aStimuli = MetaModel::EnumStimuli($sClass);
			
			$sActionLabel = $aStimuli[$sStimulus]->GetLabel();
			$sActionDetails = $aStimuli[$sStimulus]->GetDescription();
			
			$oP->set_title(Dict::Format('UI:StimulusModify_N_ObjectsOf_Class', $sActionLabel, count($aObjects), $sClass));
			$oP->add('<div class="page_header">');
			$oP->add('<h1>'.MetaModel::GetClassIcon($sClass).'&nbsp;'.Dict::Format('UI:StimulusModify_N_ObjectsOf_Class', $sActionLabel, count($aObjects), $sClass).'</h1>');
			$oP->add('</div>');
			
			$oSet = DBObjectSet::FromArray($sClass, $aObjects);
			$oMyChange = MetaModel::NewObject("CMDBChange");
			$oMyChange->Set("date", time());
			$sUserString = CMDBChange::GetCurrentUserName();
			$oMyChange->Set("userinfo", $sUserString);
			$iChangeId = $oMyChange->DBInsert();
			
			// For reporting
			$aHeaders = array(
				'object' => array('label' => MetaModel::GetName($sClass), 'description' => Dict::S('UI:ModifiedObject')),
				'status' => array('label' => Dict::S('UI:BulkModifyStatus'), 'description' => Dict::S('UI:BulkModifyStatus+')),
				'errors' => array('label' => Dict::S('UI:BulkModifyErrors'), 'description' => Dict::S('UI:BulkModifyErrors+')),
			);
			$aRows = array();
			while ($oObj = $oSet->Fetch())
			{
				$sError = Dict::S('UI:BulkModifyStatusOk');
				try
				{
					$aTransitions = $oObj->EnumTransitions();
					$aStimuli = MetaModel::EnumStimuli($sClass);
					if (!isset($aTransitions[$sStimulus]))
					{
						throw new ApplicationException(Dict::Format('UI:Error:Invalid_Stimulus_On_Object_In_State', $sStimulus, $oObj->GetName(), $oObj->GetStateLabel()));
					}
					else
					{
						$sActionLabel = $aStimuli[$sStimulus]->GetLabel();
						$sActionDetails = $aStimuli[$sStimulus]->GetDescription();
						$aTransition = $aTransitions[$sStimulus];
						$sTargetState = $aTransition['target_state'];
						$aTargetStates = MetaModel::EnumStates($sClass);
						$aTargetState = $aTargetStates[$sTargetState];
						$aExpectedAttributes = $aTargetState['attribute_list'];
						$aDetails = array();
						$aErrors = array();
						foreach($aExpectedAttributes as $sAttCode => $iExpectCode)
						{
							$iFlags = $oObj->GetAttributeFlags($sAttCode);
							if (($iExpectCode & (OPT_ATT_MUSTCHANGE|OPT_ATT_MUSTPROMPT)) || ($oObj->Get($sAttCode) == '') ) 
							{
								$paramValue = utils::ReadPostedParam("attr_$sAttCode", '');
								if ( ($iFlags & OPT_ATT_SLAVE) && ($paramValue != $oObj->Get($sAttCode)) )
								{
									$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
									$aErrors[] = Dict::Format('UI:AttemptingToSetASlaveAttribute_Name', $oAttDef->GetLabel());
								}
								else
								{
									$oObj->Set($sAttCode, $paramValue);
								}
							}
						}
						if (count($aErrors) == 0)
						{
							if ($oObj->ApplyStimulus($sStimulus))
							{
								list($bResult, $aErrors) = $oObj->CheckToWrite();
								$sStatus = $bResult ? Dict::S('UI:BulkModifyStatusModified') : Dict::S('UI:BulkModifyStatusSkipped');							
								if ($bResult)
								{
									$oObj->DBUpdateTracked($oMyChange);
								}
								else
								{
									$sError = '<p>'.implode('</p></p>',$aErrors)."</p>\n";
								}
							}
							else
							{
								$sStatus = Dict::S('UI:BulkModifyStatusSkipped');							
								$sError = '<p>'.Dict::S('UI:FailedToApplyStimuli')."<p>\n";
							}
						}
						else
						{
							$sStatus = Dict::S('UI:BulkModifyStatusSkipped');							
							$sError = '<p>'.implode('</p></p>',$aErrors)."</p>\n";
						}
					}
				}
				catch(Exception $e)
				{
					$sError = $e->getMessage();
					$sStatus = Dict::S('UI:BulkModifyStatusSkipped');
				}
				$aRows[] = array(
					'object' => $oObj->GetHyperlink(),
					'status' => $sStatus,
					'errors' => $sError,
				);
			}
			$oP->Table($aHeaders, $aRows);
			// Back to the list
			$sURL = "./UI.php?operation=search&filter=$sFilter&".$oAppContext->GetForLink();
			$oP->add('<input type="button" onClick="window.location.href=\''.$sURL.'\'" value="'.Dict::S('UI:Button:Done').'">');
		}
		break;

		case 'stimulus': // Form displayed when applying a stimulus (state change)
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
			$oP->add("<div class=\"page_header\">\n");
			$oP->add("<h1>$sActionLabel - <span class=\"hilite\">{$oObj->GetName()}</span></h1>\n");
			$oP->set_title($sActionLabel);
			$oP->add("</div>\n");
			$oP->add('<div class="ui-widget-content">');
			$oObj->DisplayBareProperties($oP);
			$oP->add('</div>');
			$aTargetState = $aTargetStates[$sTargetState];
			$aExpectedAttributes = $aTargetState['attribute_list'];
			$oP->add("<h1>$sActionDetails</h1>\n");
			$oP->add("<div class=\"wizContainer\">\n");
			$oP->add("<form id=\"apply_stimulus\" method=\"post\" onSubmit=\"return OnSubmit('apply_stimulus');\">\n");
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
					$aDetails[] = array('label' => '<span>'.$oAttDef->GetLabel().'</span>', 'value' => "<span id=\"field_att_$iFieldIndex\">$sHTMLValue</span>");
					$aFieldsMap[$sAttCode] = 'att_'.$iFieldIndex;
					$iFieldIndex++;
				}
			}
			$oP->add('<table><tr><td>');
			$oP->details($aDetails);
			$oP->add('</td></tr></table>');
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
			var oWizardHelper = new WizardHelper('$sClass', '', '$sTargetState');
			oWizardHelper.SetFieldsMap($sJsonFieldsMap);
			oWizardHelper.SetFieldsCount($iFieldsCount);
EOF
);
			$oP->add_ready_script(
<<<EOF
			// Starts the validation when the page is ready
			CheckFields('apply_stimulus', false);
EOF
);
		}
		else
		{
			$oP->set_title(Dict::S('UI:ErrorPageTitle'));
			$oP->P(Dict::S('UI:ObjectDoesNotExist'));
		}		
		break;

		///////////////////////////////////////////////////////////////////////////////////////////

		case 'apply_stimulus': // Actual state change
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
				$aTargetState = $aTargetStates[$sTargetState];
				$aExpectedAttributes = $aTargetState['attribute_list'];
				$aDetails = array();
				$aErrors = array();
				foreach($aExpectedAttributes as $sAttCode => $iExpectCode)
				{
					$iFlags = $oObj->GetAttributeFlags($sAttCode);
					if (($iExpectCode & (OPT_ATT_MUSTCHANGE|OPT_ATT_MUSTPROMPT)) || ($oObj->Get($sAttCode) == '') ) 
					{
						$paramValue = utils::ReadPostedParam("attr_$sAttCode", '');
						if ( ($iFlags & OPT_ATT_SLAVE) && ($paramValue != $oObj->Get($sAttCode)))
						{
							$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
							$aErrors[] = Dict::Format('UI:AttemptingToChangeASlaveAttribute_Name', $oAttDef->GetLabel());
						}
						else
						{
						$oObj->Set($sAttCode, $paramValue);
					}
				}
				}
				if (count($aErrors) == 0)
				{
				if ($oObj->ApplyStimulus($sStimulus))
				{
					$oMyChange = MetaModel::NewObject("CMDBChange");
					$oMyChange->Set("date", time());
					$sUserString = CMDBChange::GetCurrentUserName();
					$oMyChange->Set("userinfo", $sUserString);
					$iChangeId = $oMyChange->DBInsert();
					$oObj->DBUpdateTracked($oMyChange);
					$oP->p(Dict::Format('UI:Class_Object_Updated', MetaModel::GetName(get_class($oObj)), $oObj->GetName()));
				}
					else
					{
						$oP->p(Dict::S('UI:FailedToApplyStimuli'));
					}
				}
				else
				{
					$oP->p(implode('</p><p>', $aErrors));
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

		///////////////////////////////////////////////////////////////////////////////////////////

		case 'modify_links': // ?? still used  ??
		$sClass = utils::ReadParam('class', '');
		$sLinkAttr = utils::ReadParam('link_attr', '');
		$sTargetClass = utils::ReadParam('target_class', '');
		$id = utils::ReadParam('id', '');
		$bAddObjects = utils::ReadParam('addObjects', false);
		if ( empty($sClass) || empty($id) || empty($sLinkAttr) || empty($sTargetClass)) // TO DO: check that the class name is valid !
		{
			throw new ApplicationException(Dict::Format('UI:Error:4ParametersMissing', 'class', 'id', 'target_class', 'link_attr'));
		}
		require_once(APPROOT.'/application/uilinkswizard.class.inc.php');
		$oWizard = new UILinksWizard($sClass, $sLinkAttr, $id, $sTargetClass);
		$oWizard->Display($oP, array('StartWithAdd' => $bAddObjects));		
		break;
	
		///////////////////////////////////////////////////////////////////////////////////////////

		case 'do_modify_links': // ?? still used ??
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
		$sUserString = CMDBChange::GetCurrentUserName();
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
		
		///////////////////////////////////////////////////////////////////////////////////////////

		case 'swf_navigator': // Graphical display of the relations "impact" / "depends on"
		$sClass = utils::ReadParam('class', '');
		$id = utils::ReadParam('id', 0);
		$sRelation = utils::ReadParam('relation', 'impact');
		
		$oP->AddTabContainer('Navigator');
		$oP->SetCurrentTabContainer('Navigator');
		$oP->SetCurrentTab(Dict::S('UI:RelationshipGraph'));
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
		$oP->SetCurrentTab(Dict::S('UI:RelationshipList'));
		$oP->add("<div id=\"impacted_objects\" style=\"width:100%;background-color:#fff;padding:10px;\"><p style=\"height:150px;\">&nbsp;</p></div>");
		$oP->add_ready_script(
<<<EOF
	var ajax_request = null;
	
	function UpdateImpactedObjects(sClass, iId, sRelation)
	{
		var class_name = sClass; //$('select[name=class_name]').val();
		if (class_name != '')
		{
			$('#impacted_objects').block();
	
			// Make sure that we cancel any pending request before issuing another
			// since responses may arrive in arbitrary order
			if (ajax_request != null)
			{
				ajax_request.abort();
				ajax_request = null;
			}
	
			ajax_request = $.get('xml.navigator.php', { 'class': sClass, id: iId, relation: sRelation, format: 'html' },
					function(data)
					{
						$('#impacted_objects').empty();
						$('#impacted_objects').append(data);
						$('#impacted_objects').unblock();
						$('#impacted_objects .listResults').tablesorter( { widgets: ['myZebra', 'truncatedList']} ); // sortable and zebra tables
						$('#impacted_objects table.listResults').tableHover(); // hover tables
					}
			);
		}
	}
	
	UpdateImpactedObjects('$sClass', $id, '$sRelation');
EOF
		);
		$oP->SetCurrentTab('');
		break;
		
		///////////////////////////////////////////////////////////////////////////////////////////

		case 'cancel': // An action was cancelled
		$oP->set_title(Dict::S('UI:OperationCancelled'));
		$oP->add('<h1>'.Dict::S('UI:OperationCancelled').'</h1>');
		break;
	
		///////////////////////////////////////////////////////////////////////////////////////////

		default: // Menu node rendering (templates)
		$oMenuNode = ApplicationMenu::GetMenuNode(ApplicationMenu::GetActiveNodeId());
		if (is_object($oMenuNode))
		{
		
			$oMenuNode->RenderContent($oP, $oAppContext->GetAsHash());
			$oP->set_title($oMenuNode->GetLabel());
		}
		
		///////////////////////////////////////////////////////////////////////////////////////////

	}
	$oKPI->ComputeAndReport('GUI creation before output');

	ExecutionKPI::ReportStats();
	MetaModel::ShowQueryTrace();

	DisplayWelcomePopup($oP);
	$oP->output();	
}
catch(CoreException $e)
{
	require_once(APPROOT.'/setup/setuppage.class.inc.php');
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
	require_once(APPROOT.'/setup/setuppage.class.inc.php');
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
