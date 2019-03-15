<?php

// Copyright (C) 2010-2017 Combodo SARL
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
 * Main page of iTop
 *
 * @copyright   Copyright (C) 2010-2017 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


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
 * Apply the 'next-action' to the given object or redirect to the page that prompts for additional information if needed
 *
 * @param $oP WebPage The page for the output
 * @param $oObj CMDBObject The object to process
 * @param $sNextAction string The code of the stimulus for the 'action' (i.e. Transition) to apply
 *
 * @throws \ApplicationException
 * @throws \CoreException
 * @throws \CoreUnexpectedValue
 */
function ApplyNextAction(Webpage $oP, CMDBObject $oObj, $sNextAction)
{
	// Here handle the apply stimulus
	$aTransitions = $oObj->EnumTransitions();
	if (!isset($aTransitions[$sNextAction]))
	{
		// Invalid stimulus
		throw new ApplicationException(Dict::Format('UI:Error:Invalid_Stimulus_On_Object_In_State', $sNextAction, $oObj->GetName(), $oObj->GetStateLabel()));
	}
	// Get the list of missing mandatory fields for the target state, considering only the changes from the previous form (i.e don't prompt twice)
	$aExpectedAttributes = $oObj->GetTransitionAttributes($sNextAction);
	
	if (count($aExpectedAttributes) == 0)
	{
		// If all the mandatory fields are already present, just apply the transition silently...
		if ($oObj->ApplyStimulus($sNextAction))
		{
			$oObj->DBUpdate();
		}
		ReloadAndDisplay($oP, $oObj);
	}
	else
	{
		// redirect to the 'stimulus' action
		$oAppContext = new ApplicationContext();
//echo "<p>Missing Attributes <pre>".print_r($aExpectedAttributes, true)."</pre></p>\n";
		
		$oP->add_header('Location: '.utils::GetAbsoluteUrlAppRoot().'pages/UI.php?operation=stimulus&class='.get_class($oObj).'&stimulus='.$sNextAction.'&id='.$oObj->getKey().'&'.$oAppContext->GetForLink());
	}
}

function ReloadAndDisplay($oPage, $oObj, $sMessageId = '', $sMessage = '', $sSeverity = null)
{
	$oAppContext = new ApplicationContext();
	if ($sMessageId != '')
	{
		cmdbAbstractObject::SetSessionMessage(get_class($oObj), $oObj->GetKey(), $sMessageId, $sMessage, $sSeverity, 0, true /* must not exist */);
	}
	$oPage->add_header('Location: '.utils::GetAbsoluteUrlAppRoot().'pages/UI.php?operation=details&class='.get_class($oObj).'&id='.$oObj->getKey().'&'.$oAppContext->GetForLink());
}

/**
 * Displays the details of an object
 * @param $oP WebPage Page for the output
 * @param $sClass string The name of the class of the object
 * @param $oObj DBObject The object to display
 * @param $id mixed Identifier of the object (name or ID)
 * @throws \CoreException
 * @throws \DictExceptionMissingString
 * @throws \SecurityException
*/
function DisplayDetails($oP, $sClass, $oObj, $id)
{
	$sClassLabel = MetaModel::GetName($sClass);

// 2018-04-11 : removal of the search block
//	$oSearch = new DBObjectSearch($sClass);
//	$oBlock = new DisplayBlock($oSearch, 'search', false);
//	$oBlock->Display($oP, 0, array(
//		'table_id'  => 'search-widget-results-outer',
//		'open'      => false,
//		'update_history' => false,
//	));

	// The object could be listed, check if it is actually allowed to view it
	$oSet = CMDBObjectSet::FromObject($oObj);
	if (UserRights::IsActionAllowed($sClass, UR_ACTION_READ, $oSet) == UR_ALLOWED_NO)
	{
		throw new SecurityException('User not allowed to view this object', array('class' => $sClass, 'id' => $id));
	}
	$oP->set_title(Dict::Format('UI:DetailsPageTitle', $oObj->GetRawName(), $sClassLabel)); // Set title will take care of the encoding
	$oObj->DisplayDetails($oP);
}

/**
 * Display the session messages relative to the object identified by its "message key" (class::id)
 * @param string $sMessageKey
 * @param WebPage $oPage
 */
function DisplayMessages($sMessageKey, WebPage $oPage)
{
	if (array_key_exists('obj_messages', $_SESSION) && array_key_exists($sMessageKey, $_SESSION['obj_messages']))
	{
		$aMessages = array();
		$aRanks = array();
		foreach ($_SESSION['obj_messages'][$sMessageKey] as $sMessageId => $aMessageData)
		{
			$sMsgClass = 'message_'.$aMessageData['severity'];
			$aMessages[] = "<div class=\"header_message $sMsgClass\">".$aMessageData['message']."</div>";
			$aRanks[] = $aMessageData['rank'];
		}
		unset($_SESSION['obj_messages'][$sMessageKey]);
		array_multisort($aRanks, $aMessages);
		foreach ($aMessages as $sMessage)
		{
			$oPage->add($sMessage);
		}
	}
}

/**
 * Helper to update the breadrumb for the current object
 * @param DBObject $oObj
 * @param WebPage $oPage
 * @throws \CoreException
 * @throws \DictExceptionMissingString
*/
function SetObjectBreadCrumbEntry(DBObject $oObj, WebPage $oPage)
{
	$sClass = get_class($oObj); // get the leaf class
	$sIcon = MetaModel::GetClassIcon($sClass, false);
	if ($sIcon == '')
	{
		$sIcon = utils::GetAbsoluteUrlAppRoot().'images/breadcrumb_object.png';
	}
	$oPage->SetBreadCrumbEntry("ui-details-$sClass-".$oObj->GetKey(), $oObj->Get('friendlyname'), MetaModel::GetName($sClass).': '.$oObj->Get('friendlyname'), '', $sIcon);
}

/**
 * Displays the result of a search request
 * @param $oP WebPage Web page for the output
 * @param $oFilter DBSearch The search of objects to display
 * @param $bSearchForm boolean Whether or not to display the search form at the top the page
 * @param $sBaseClass string The base class for the search (can be different from the actual class of the results)
 * @param $sFormat string The format to use for the output: csv or html
 * @param $bDoSearch bool True to display the search results below the search form
 * @param $bSearchFormOpen bool True to display the search form fully expanded (only if $bSearchForm of course)
 * @throws \CoreException
 * @throws \DictExceptionMissingString
 */
function DisplaySearchSet($oP, $oFilter, $bSearchForm = true, $sBaseClass = '', $sFormat = '', $bDoSearch = true, $bSearchFormOpen = true)
{
	if ($bSearchForm)
	{
		$aParams = array('open' => $bSearchFormOpen, 'table_id' => '1');
		if (!empty($sBaseClass))
		{
			$aParams['baseClass'] = $sBaseClass;
		}
		$oBlock = new DisplayBlock($oFilter, 'search', false /* Asynchronous */, $aParams);
		$oBlock->Display($oP, 0);
	}
	if ($bDoSearch)
	{
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

			// Breadcrumb
			//$iCount = $oBlock->GetDisplayedCount();
			$sPageId = "ui-search-".$oFilter->GetClass();
			$sLabel = MetaModel::GetName($oFilter->GetClass());
			$oP->SetBreadCrumbEntry($sPageId, $sLabel, '', '', '../images/breadcrumb-search.png');
		}
	}
}

/**
 * Displays a form (checkboxes) to select the objects for which to apply a given action
 * Only the objects for which the action is valid can be checked. By default all valid objects are checked
 *
 * @param \WebPage $oP WebPage The page for output
 * @param \DBSearch $oFilter DBSearch The filter that defines the list of objects
 * @param string $sNextOperation string The next operation (code) to be executed when the form is submitted
 * @param ActionChecker $oChecker ActionChecker The helper class/instance used to check for which object the action is valid
 * @param array $aExtraFormParams
 *
 * @throws \ApplicationException
 */
function DisplayMultipleSelectionForm($oP, $oFilter, $sNextOperation, $oChecker, $aExtraFormParams = array())
{
		$oAppContext = new ApplicationContext();
		$iBulkActionAllowed = $oChecker->IsAllowed();
		$aExtraParams = array('selection_type' => 'multiple', 'selection_mode' => true, 'display_limit' => false, 'menu' => false);
		if ($iBulkActionAllowed == UR_ALLOWED_DEPENDS)
		{
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
	$oP->add("<input type=\"hidden\" name=\"filter\" value=\"".htmlentities($oFilter->Serialize(), ENT_QUOTES, 'UTF-8')."\">\n");
		$oP->add("<input type=\"hidden\" name=\"transaction_id\" value=\"".utils::GetNewTransactionId()."\">\n");
		foreach($aExtraFormParams as $sName => $sValue)
		{
			$oP->add("<input type=\"hidden\" name=\"$sName\" value=\"$sValue\">\n");
		}
		$oP->add($oAppContext->GetForForm());
		$oBlock->Display($oP, 1, $aExtraParams);
		$oP->add("<input type=\"button\" value=\"".Dict::S('UI:Button:Cancel')."\" onClick=\"window.history.back()\">&nbsp;&nbsp;<input type=\"submit\" value=\"".Dict::S('UI:Button:Next')."\">\n");
		$oP->add("</form>\n");
		$oP->add_ready_script("$('#1 table.listResults').trigger('check_all');");
}

function DisplayNavigatorListTab($oP, $aResults, $sRelation, $sDirection, $oObj)
{
	$oP->SetCurrentTab(Dict::S('UI:RelationshipList'));
	$oP->add("<div id=\"impacted_objects\" style=\"width:100%;background-color:#fff;padding:10px;\">");
	$sOldRelation = $sRelation;
	if (($sRelation == 'impacts') && ($sDirection == 'up'))
	{
		$sOldRelation = 'depends on';
	}
	$oP->add("<h1>".MetaModel::GetRelationDescription($sOldRelation).' '.$oObj->GetName()."</h1>\n");
	$oP->add("<div id=\"impacted_objects_lists\">");
	$oP->add('<img src="../images/indicator.gif">');
	/*
	 * Content is rendered asynchronously via pages/ajax.render.php?operation=relation_lists
	 */
	/*
	$iBlock = 1; // Zero is not a valid blockid
	foreach($aResults as $sListClass => $aObjects)
	{
		$oSet = CMDBObjectSet::FromArray($sListClass, $aObjects);
		$oP->add("<div class=\"page_header\">\n");
		$oP->add("<h2>".MetaModel::GetClassIcon($sListClass)."&nbsp;<span class=\"hilite\">".Dict::Format('UI:Search:Count_ObjectsOf_Class_Found', count($aObjects), Metamodel::GetName($sListClass))."</h2>\n");
		$oP->add("</div>\n");
		$oBlock = DisplayBlock::FromObjectSet($oSet, 'list');
		$oBlock->Display($oP, $iBlock++, array('table_id' => get_class($oObj).'_'.$sRelation.'_'.$sDirection.'_'.$sListClass));
		$oP->P('&nbsp;'); // Some space ?				
	}
	*/
	$oP->add("</div>");
	$oP->add("</div>");
}

function DisplayNavigatorGroupTab($oP)
{
	$oP->SetCurrentTab(Dict::S('UI:RelationGroups'));
	$oP->add("<div id=\"impacted_groups\" style=\"width:100%;background-color:#fff;padding:10px;\">");
	$oP->add('<img src="../images/indicator.gif">');
	/*
	 * Content is rendered asynchronously via pages/ajax.render.php?operation=relation_groups
	*/
	$oP->add("</div>");
}

/***********************************************************************************
 * 
 * Main user interface page starts here
 *
 ***********************************************************************************/
require_once('../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/itopwebpage.class.inc.php');
require_once(APPROOT.'/application/wizardhelper.class.inc.php');

require_once(APPROOT.'/application/startup.inc.php');

try
{
	$operation = utils::ReadParam('operation', '');
	$bPrintable = (utils::ReadParam('printable', 0) == '1');

	$oKPI = new ExecutionKPI();
	$oKPI->ComputeAndReport('Data model loaded');

	$oKPI = new ExecutionKPI();

	require_once(APPROOT.'/application/loginwebpage.class.inc.php');
	$sLoginMessage = LoginWebPage::DoLogin(); // Check user rights and prompt if needed
	$oAppContext = new ApplicationContext();

	$oKPI->ComputeAndReport('User login');

	$oP = new iTopWebPage(Dict::S('UI:WelcomeToITop'), $bPrintable);
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
		case 'apply_stimulus': // Form displayed when applying a stimulus (state change)
		$oP->add_linked_script("../js/json.js");
		$oP->add_linked_script("../js/forms-json-utils.js");
		$oP->add_linked_script("../js/wizardhelper.js");
		$oP->add_linked_script("../js/wizard.utils.js");
		$oP->add_linked_script("../js/linkswidget.js");
		$oP->add_linked_script("../js/linksdirectwidget.js");
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
				// Check anyhow if there is a message for this object (like you've just created it)
				$sMessageKey = $sClass.'::'.$id;
				DisplayMessages($sMessageKey, $oP);
				$oP->set_title(Dict::S('UI:ErrorPageTitle'));

				// Attempt to load the object in archive mode
				utils::PushArchiveMode(true);
				if (is_numeric($id))
				{
					$oObj = MetaModel::GetObject($sClass, $id, false /* MustBeFound */);
				}
				else
				{
					$oObj = MetaModel::GetObjectByName($sClass, $id, false /* MustBeFound */);
				}
				utils::PopArchiveMode();
				if (is_null($oObj))
				{
					$oP->P(Dict::S('UI:ObjectDoesNotExist'));
				}
				else
				{
					SetObjectBreadCrumbEntry($oObj, $oP);
					$oP->P(Dict::S('UI:ObjectArchived'));
				}
			}
			else
			{
				try
				{
					$oObj->Reload();
				}
				catch(Exception $e)
				{
					// Probably not allowed to see this instance of a derived class
					
					// Check anyhow if there is a message for this object (like you've just created it)
					$sMessageKey = $sClass.'::'.$id;
					DisplayMessages($sMessageKey, $oP);
						
					$oObj = null; 
					$oP->set_title(Dict::S('UI:ErrorPageTitle'));
					$oP->P(Dict::S('UI:ObjectDoesNotExist'));
				}
				if (!is_null($oObj))
				{
					SetObjectBreadCrumbEntry($oObj, $oP);
					DisplayDetails($oP, $sClass, $oObj, $id);
				}				
			}
		break;

		case 'release_lock_and_details':
        $oP->DisableBreadCrumb();
		$sClass = utils::ReadParam('class', '');
		$id = utils::ReadParam('id', '');
		$oObj = MetaModel::GetObject($sClass, $id);
		$sToken = utils::ReadParam('token', '');
		if ($sToken != '')
		{
			iTopOwnershipLock::ReleaseLock($sClass, $id, $sToken);
		}
		cmdbAbstractObject::ReloadAndDisplay($oP, $oObj, array('operation' => 'details'));
		break;
	
		///////////////////////////////////////////////////////////////////////////////////////////

		case 'search_oql': // OQL query
			$sOQLClass = utils::ReadParam('oql_class', '', false, 'class');
			$sBaseClass = utils::ReadParam('base_class', $sOQLClass, false, 'class');
			$sOQLClause = utils::ReadParam('oql_clause', '', false, 'raw_data');
			$sFormat = utils::ReadParam('format', '');
			$bSearchForm = utils::ReadParam('search_form', true);
			$sTitle = utils::ReadParam('title', 'UI:SearchResultsPageTitle');
			if (empty($sOQLClass))
			{
				throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'oql_class'));
			}
			$oP->set_title(Dict::S($sTitle));
			$oP->add('<h1>'.Dict::S($sTitle).'</h1>');
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
					$oBlock->Display($oP, 0, array('table_id' => 'search-widget-result-outer'));
				}
				$oP->add('<div id="search-widget-result-outer"><p><b>'.Dict::Format('UI:Error:IncorrectOQLQuery_Message', $e->getHtmlDesc()).'</b></p></div>');
			}
			catch(Exception $e)
			{
				$oP->P('<b>'.Dict::Format('UI:Error:AnErrorOccuredWhileRunningTheQuery_Message', $e->getMessage()).'</b>');
			}
		break;
		
		///////////////////////////////////////////////////////////////////////////////////////////

		case 'search_form': // Search form
			$sClass = utils::ReadParam('class', '', false, 'class');
			$sFormat = utils::ReadParam('format', 'html');
			$bSearchForm = utils::ReadParam('search_form', true);
			$bDoSearch = utils::ReadParam('do_search', true);
			if (empty($sClass))
			{
				throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'class'));
			}
			$oP->set_title(Dict::S('UI:SearchResultsPageTitle'));
			$oFilter =  new DBObjectSearch($sClass);
			DisplaySearchSet($oP, $oFilter, $bSearchForm, '' /* sBaseClass */, $sFormat, $bDoSearch, true /* Search Form Expanded */);
			break;

		///////////////////////////////////////////////////////////////////////////////////////////

		case 'search': // Serialized DBSearch
			$sFilter = utils::ReadParam('filter', '', false, 'raw_data');
			$sFormat = utils::ReadParam('format', '');
			$bSearchForm = utils::ReadParam('search_form', true);
			if (empty($sFilter))
			{
				throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'filter'));
			}
			$oP->set_title(Dict::S('UI:SearchResultsPageTitle'));
			$oFilter = DBSearch::unserialize($sFilter); // TO DO : check that the filter is valid
			$oFilter->UpdateContextFromUser();
			DisplaySearchSet($oP, $oFilter, $bSearchForm, '' /* sBaseClass */, $sFormat);
		break;
	
		///////////////////////////////////////////////////////////////////////////////////////////

		case 'full_text': // Global "google-like" search
			$oP->DisableBreadCrumb();
			$sFullText = trim(utils::ReadParam('text', '', false, 'raw_data'));
			$iTune = utils::ReadParam('tune', 0);
			if (empty($sFullText))
			{
				$oP->p(Dict::S('UI:Search:NoSearch'));
			}
			else
			{
				$iErrors = 0;

				// Check if a class name/label is supplied to limit the search
				$sClassName = '';
				if (preg_match('/^([^\"]+):(.+)$/', $sFullText, $aMatches))
				{
					$sClassName = $aMatches[1];
					if (MetaModel::IsValidClass($sClassName))
					{
						$sFullText = trim($aMatches[2]);
					}
					elseif ($sClassName = MetaModel::GetClassFromLabel($sClassName, false /* => not case sensitive */))
					{
						$sFullText = trim($aMatches[2]);
					}
				}
				
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

				// Check the needle length
				$iMinLenth = MetaModel::GetConfig()->Get('full_text_needle_min');
				foreach ($aFullTextNeedles as $sNeedle)
				{
					if (strlen($sNeedle) < $iMinLenth)
					{
						$oP->p(Dict::Format('UI:Search:NeedleTooShort', $sNeedle, $iMinLenth));
						$key = array_search($sNeedle, $aFullTextNeedles);
						if($key!== false)
						{
							unset($aFullTextNeedles[$key]);
						}
					}
				}
				if(empty($aFullTextNeedles))
				{
					$oP->p(Dict::S('UI:Search:NoSearch'));
					break;
				}
				$sFullText = implode(' ', $aFullTextNeedles);

				// Sanity check of the accelerators
				/** @var array $aAccelerators */
				$aAccelerators = MetaModel::GetConfig()->Get('full_text_accelerators');
				foreach ($aAccelerators as $sClass => $aAccelerator)
				{
					try
					{
						$bSkip = array_key_exists('skip', $aAccelerator) ? $aAccelerator['skip'] : false;
						if (!$bSkip)
						{
							$oSearch = DBObjectSearch::FromOQL($aAccelerator['query']);
							if ($sClass != $oSearch->GetClass())
							{
								$oP->p("Full text accelerator for class '$sClass': searched class mismatch (".$oSearch->GetClass().")");
								$iErrors++;
							}
						}
					}
					catch (OqlException $e)
					{
						$oP->p("Full text accelerator for class '$sClass': ".$e->getHtmlDesc());
						$iErrors++;
					}
				}

				if ($iErrors == 0)
				{
					$oP->set_title(Dict::S('UI:SearchResultsPageTitle'));
					$sPageId = "ui-global-search";
					$sLabel = Dict::S('UI:SearchResultsTitle');
					$sDescription = Dict::S('UI:SearchResultsTitle+');
					$oP->SetBreadCrumbEntry($sPageId, $sLabel, $sDescription, '', utils::GetAbsoluteUrlAppRoot().'images/search.png');
					$oP->add("<div style=\"padding: 10px;\">\n");
					$oP->add("<div class=\"header_message\" id=\"full_text_progress\" style=\"position: fixed; background-color: #cccccc; opacity: 0.7; padding: 1.5em;\">\n");
					$oP->add('<img id="full_text_indicator" src="../images/indicator.gif">&nbsp;<span style="padding: 1.5em;">'.Dict::Format('UI:Search:Ongoing', htmlentities($sFullText, ENT_QUOTES, 'UTF-8')).'</span>');
					$oP->add("</div>\n");
					$oP->add("<div id=\"full_text_results\">\n");
					$oP->add("<div id=\"full_text_progress_placeholder\" style=\"padding: 1.5em;\">&nbsp;</div>\n");
					$oP->add("<h2>".Dict::Format('UI:FullTextSearchTitle_Text', htmlentities($sFullText, ENT_QUOTES, 'UTF-8'))."</h2>");
					$oP->add("</div>\n");
					$oP->add("</div>\n");
					$sJSClass = addslashes($sClassName);
					$sJSNeedles = json_encode($aFullTextNeedles);
					$oP->add_ready_script(
<<<EOF
						var oParams = {operation: 'full_text_search', position: 0, 'class': '$sJSClass', needles: $sJSNeedles, tune: $iTune};
						$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php', oParams, function(data) {
							$('#full_text_results').append(data);
						});
EOF
					);
					if ($iTune > 0)
					{
						$oP->add_script("var oTimeStatistics = {};");
					}
				}
			}	
		break;
	
		///////////////////////////////////////////////////////////////////////////////////////////

		case 'modify': // Form to modify an object
			$oP->DisableBreadCrumb();
			$sClass = utils::ReadParam('class', '', false, 'class');
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
				$oObj->DisplayModifyForm($oP, array('wizard_container' => 1)); // wizard_container: Display the blue borders and the title above the form
			}
		break;
	
		///////////////////////////////////////////////////////////////////////////////////////////

		case 'select_for_modify_all': // Select the list of objects to be modified (bulk modify)
		$oP->DisableBreadCrumb();
		$oP->set_title(Dict::S('UI:ModifyAllPageTitle'));
		$sFilter = utils::ReadParam('filter', '', false, 'raw_data');
		if (empty($sFilter))
		{
			throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'filter'));
		}
		$oFilter = DBObjectSearch::unserialize($sFilter); //TODO : check that the filter is valid
		// Add user filter
		$oFilter->UpdateContextFromUser();
		$sClass = $oFilter->GetClass();
		$oChecker = new ActionChecker($oFilter, UR_ACTION_BULK_MODIFY);
		$oP->add("<h1>".Dict::S('UI:ModifyAllPageTitle')."</h1>\n");			
		
		DisplayMultipleSelectionForm($oP, $oFilter, 'form_for_modify_all', $oChecker);
		break;	
		///////////////////////////////////////////////////////////////////////////////////////////

		case 'form_for_modify_all': // Form to modify multiple objects (bulk modify)
		$oP->DisableBreadCrumb();
		$sFilter = utils::ReadParam('filter', '', false, 'raw_data');
		$sClass = utils::ReadParam('class', '', false, 'class');
		$oFullSetFilter = DBObjectSearch::unserialize($sFilter);
		// Add user filter
		$oFullSetFilter->UpdateContextFromUser();
		$aSelectedObj = utils::ReadMultipleSelection($oFullSetFilter);
		$sCancelUrl = "./UI.php?operation=search&filter=".urlencode($sFilter)."&".$oAppContext->GetForLink();
		$aContext = array('filter' => htmlentities($sFilter, ENT_QUOTES, 'UTF-8'));
		cmdbAbstractObject::DisplayBulkModifyForm($oP, $sClass, $aSelectedObj, 'preview_or_modify_all', $sCancelUrl, array(), $aContext);
		break;
		
		///////////////////////////////////////////////////////////////////////////////////////////

		case 'preview_or_modify_all': // Preview or apply bulk modify
		$oP->DisableBreadCrumb();
		$sFilter = utils::ReadParam('filter', '', false, 'raw_data');
		$oFilter = DBObjectSearch::unserialize($sFilter); // TO DO : check that the filter is valid
		// Add user filter
		$oFilter->UpdateContextFromUser();
		$oChecker = new ActionChecker($oFilter, UR_ACTION_BULK_MODIFY);

		$sClass = utils::ReadParam('class', '', false, 'class');
		$bPreview = utils::ReadParam('preview_mode', '');
		$sSelectedObj = utils::ReadParam('selectObj', '', false, 'raw_data');
		if ( empty($sClass) || empty($sSelectedObj)) // TO DO: check that the class name is valid !
		{
			throw new ApplicationException(Dict::Format('UI:Error:2ParametersMissing', 'class', 'selectObj'));
		}
		$aSelectedObj = explode(',', $sSelectedObj);
		$sCancelUrl = "./UI.php?operation=search&filter=".urlencode($sFilter)."&".$oAppContext->GetForLink();
		$aContext = array(
			'filter' => htmlentities($sFilter, ENT_QUOTES, 'UTF-8'),
			'selectObj' => $sSelectedObj,
		);
		cmdbAbstractObject::DoBulkModify($oP, $sClass, $aSelectedObj, 'preview_or_modify_all', $bPreview, $sCancelUrl, $aContext);
		break;

		///////////////////////////////////////////////////////////////////////////////////////////

		case 'new': // Form to create a new object
			$oP->DisableBreadCrumb();
			$sClass = utils::ReadParam('class', '', false, 'class');
			$sStateCode = utils::ReadParam('state', '');
			$bCheckSubClass = utils::ReadParam('checkSubclass', true);
			if ( empty($sClass) )
			{
				throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'class'));
			}

/*
			$aArgs = utils::ReadParam('default', array(), false, 'raw_data');
			$aContext = $oAppContext->GetAsHash();
			foreach( $oAppContext->GetNames() as $key)
			{
				$aArgs[$key] = $oAppContext->GetCurrentValue($key);
			}
*/
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

				// Set all the default values in an object and clone this "default" object
				$oObjToClone = MetaModel::NewObject($sRealClass);

				// 1st - set context values
				$oAppContext->InitObjectFromContext($oObjToClone);
				// 2nd - set values from the page argument 'default'
				$oObjToClone->UpdateObjectFromArg('default');
				$aPrefillFormParam = array( 'user' => $_SESSION["auth_user"],
					'context' => $oAppContext->GetAsHash(),
					'default' => utils::ReadParam('default', array(), '', 'raw_data'),
					'origin' => 'console'
				);
				$oObjToClone->PrefillForm('creation_from_0',$aPrefillFormParam);

				cmdbAbstractObject::DisplayCreationForm($oP, $sRealClass, $oObjToClone, array());
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
				$aDefaults = utils::ReadParam('default', array(), false, 'raw_data');
				$oP->add($oAppContext->GetForForm());
				$oP->add("<input type=\"hidden\" name=\"checkSubclass\" value=\"0\">\n");
				$oP->add("<input type=\"hidden\" name=\"state\" value=\"$sStateCode\">\n");
				$oP->add("<input type=\"hidden\" name=\"operation\" value=\"new\">\n");
				foreach($aDefaults as $key => $value)
				{
					if (is_array($value))
					{
						foreach($value as $key2 => $value2)
						{
							if (is_array($value2))
							{
								foreach($value2 as $key3 => $value3)
								{
									$sValue = htmlentities($value3, ENT_QUOTES, 'UTF-8');
									$oP->add("<input type=\"hidden\" name=\"default[$key][$key2][$key3]\" value=\"$sValue\">\n");
								}
							}
							else
							{
								$sValue = htmlentities($value2, ENT_QUOTES, 'UTF-8');
								$oP->add("<input type=\"hidden\" name=\"default[$key][$key2]\" value=\"$sValue\">\n");
							}
						}
					}
					else
					{
						$sValue = htmlentities($value, ENT_QUOTES, 'UTF-8');
						$oP->add("<input type=\"hidden\" name=\"default[$key]\" value=\"$sValue\">\n");
					}
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
			$oP->DisableBreadCrumb();
			$sClass = utils::ReadPostedParam('class', '');
			$sClassLabel = MetaModel::GetName($sClass);
			$id = utils::ReadPostedParam('id', '');
			$sTransactionId = utils::ReadPostedParam('transaction_id', '', 'transaction_id');
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
				$sUser = UserRights::GetUser();
				IssueLog::Error("UI.php '$operation' : invalid transaction_id ! data: user='$sUser', class='$sClass'");
				$oP->set_title(Dict::Format('UI:ModificationPageTitle_Object_Class', $oObj->GetRawName(), $sClassLabel)); // Set title will take care of the encoding
				$oP->p("<strong>".Dict::S('UI:Error:ObjectAlreadyUpdated')."</strong>\n");
			}
			else
			{
				$aErrors = $oObj->UpdateObjectFromPostedForm();
				$sMessage = '';
				$sSeverity = 'ok';

				if (!$oObj->IsModified() && empty($aErrors))
				{
					$oP->set_title(Dict::Format('UI:ModificationPageTitle_Object_Class', $oObj->GetRawName(), $sClassLabel)); // Set title will take care of the encoding
					$sMessage = Dict::Format('UI:Class_Object_NotUpdated', MetaModel::GetName(get_class($oObj)), $oObj->GetName());
					$sSeverity = 'info';
				}
				else
				{
					try
					{
						CMDBSource::Query('START TRANSACTION');
						if (!empty($aErrors))
						{
							throw new CoreCannotSaveObjectException(array('id' => $oObj->GetKey(), 'class' => $sClass, 'issues' => $aErrors));
						}
						$oObj->DBUpdate();
						CMDBSource::Query('COMMIT');
						$sMessage = Dict::Format('UI:Class_Object_Updated', MetaModel::GetName(get_class($oObj)), $oObj->GetName());
						$sSeverity = 'ok';
					}
					catch (CoreCannotSaveObjectException $e)
					{
						// Found issues, explain and give the user a second chance
						//
						CMDBSource::Query('ROLLBACK');
						$bDisplayDetails = false;
						$aIssues = $e->getIssues();
						$oP->AddHeaderMessage($e->getHtmlMessage(), 'message_error');
						$oObj->DisplayModifyForm($oP,
							array('wizard_container' => true)); // wizard_container: display the wizard border and the title
					}
					catch (DeleteException $e)
					{
						CMDBSource::Query('ROLLBACK');
						// Say two things:
						// - 1) Don't be afraid nothing was modified
						$sMessage = Dict::Format('UI:Class_Object_NotUpdated', MetaModel::GetName(get_class($oObj)), $oObj->GetName());
						$sSeverity = 'info';
						cmdbAbstractObject::SetSessionMessage(get_class($oObj), $oObj->GetKey(), 'UI:Class_Object_NotUpdated', $sMessage,
							$sSeverity, 0, true /* must not exist */);
						// - 2) Ok, there was some trouble indeed
						$sMessage = $e->getMessage();
						$sSeverity = 'error';
						$bDisplayDetails = true;
					}
					utils::RemoveTransaction($sTransactionId);
				}
			}
			if ($bDisplayDetails)
			{	
				$oObj = MetaModel::GetObject(get_class($oObj), $oObj->GetKey()); //Workaround: reload the object so that the linkedset are displayed properly
				$sNextAction = utils::ReadPostedParam('next_action', '');
				if (!empty($sNextAction))
				{
					ApplyNextAction($oP, $oObj, $sNextAction);
				}
				else
				{
					// Nothing more to do
					ReloadAndDisplay($oP, $oObj, 'update', $sMessage, $sSeverity);
				}
				
				$bLockEnabled = MetaModel::GetConfig()->Get('concurrent_lock_enabled');
				if ($bLockEnabled)
				{
					// Release the concurrent lock, if any
					$sOwnershipToken = utils::ReadPostedParam('ownership_token', null, 'raw_data');
					if ($sOwnershipToken !== null)
					{
						// We're done, let's release the lock
						iTopOwnershipLock::ReleaseLock(get_class($oObj), $oObj->GetKey(), $sOwnershipToken);
					}
				}
			}
		break;

		///////////////////////////////////////////////////////////////////////////////////////////

		case 'select_for_deletion': // Select multiple objects for deletion
			$oP->DisableBreadCrumb();
			$sFilter = utils::ReadParam('filter', '', false, 'raw_data');
			if (empty($sFilter))
			{
				throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'filter'));
			}
			$oP->set_title(Dict::S('UI:BulkDeletePageTitle'));
			$oP->add("<h1>".Dict::S('UI:BulkDeleteTitle')."</h1>\n");
			$oFilter = DBSearch::unserialize($sFilter); // TO DO : check that the filter is valid
			$oFilter->UpdateContextFromUser();
			$oChecker = new ActionChecker($oFilter, UR_ACTION_BULK_DELETE);
			DisplayMultipleSelectionForm($oP, $oFilter, 'bulk_delete', $oChecker);
		break;

		///////////////////////////////////////////////////////////////////////////////////////////

		case 'bulk_delete_confirmed': // Confirm bulk deletion of objects
			$oP->DisableBreadCrumb();
			$sTransactionId = utils::ReadPostedParam('transaction_id', '', 'transaction_id');
			if (!utils::IsTransactionValid($sTransactionId))
			{
				$sUser = UserRights::GetUser();
				$sClass = utils::ReadParam('class', '', false, 'class');
				IssueLog::Error("UI.php '$operation' : invalid transaction_id ! data: user='$sUser', class='$sClass'");
				throw new ApplicationException(Dict::S('UI:Error:ObjectsAlreadyDeleted'));
			}
		// Fall through
			
		///////////////////////////////////////////////////////////////////////////////////////////

		case 'delete':
		case 'bulk_delete': // Actual bulk deletion (if confirmed)
			$oP->DisableBreadCrumb();
			$sClass = utils::ReadParam('class', '', false, 'class');
			$sClassLabel = MetaModel::GetName($sClass);
			$aObjects = array();
			if ($operation == 'delete')
			{
				// Single object
				$id = utils::ReadParam('id', '');
				$oObj = MetaModel::GetObject($sClass, $id);
				$aObjects[] = $oObj;
				if (!UserRights::IsActionAllowed($sClass, UR_ACTION_DELETE, DBObjectSet::FromObject($oObj)))
				{
					throw new SecurityException(Dict::Format('UI:Error:DeleteNotAllowedOn_Class', $sClassLabel));
				}
			}
			else
			{
				// Several objects
				$sFilter = utils::ReadPostedParam('filter', '', 'raw_data');
				$oFullSetFilter = DBObjectSearch::unserialize($sFilter);
				// Add user filter
				$oFullSetFilter->UpdateContextFromUser();
				$aSelectObject = utils::ReadMultipleSelection($oFullSetFilter);
				if ( empty($sClass) || empty($aSelectObject)) // TO DO: check that the class name is valid !
				{
					throw new ApplicationException(Dict::Format('UI:Error:2ParametersMissing', 'class', 'selectObject[]'));
				}
				foreach($aSelectObject as $iId)
				{
					$aObjects[] = MetaModel::GetObject($sClass, $iId);
				}
				if (count($aObjects) == 1)
				{
					if (!UserRights::IsActionAllowed($sClass, UR_ACTION_DELETE, DBObjectSet::FromArray($sClass, $aObjects)))
					{
						throw new SecurityException(Dict::Format('UI:Error:BulkDeleteNotAllowedOn_Class', $sClassLabel));
					}
				}
				else
				{
					if (!UserRights::IsActionAllowed($sClass, UR_ACTION_BULK_DELETE, DBObjectSet::FromArray($sClass, $aObjects)))
					{
						throw new SecurityException(Dict::Format('UI:Error:BulkDeleteNotAllowedOn_Class', $sClassLabel));
					}
					$oP->set_title(Dict::S('UI:BulkDeletePageTitle'));
				}
			}
			// Go for the common part... (delete single, delete bulk, delete confirmed)
			cmdbAbstractObject::DeleteObjects($oP, $sClass, $aObjects, ($operation != 'bulk_delete_confirmed'), 'bulk_delete_confirmed');
			break;
			
		///////////////////////////////////////////////////////////////////////////////////////////

		case 'apply_new': // Creation of a new object
		$oP->DisableBreadCrumb();
		$sClass = utils::ReadPostedParam('class', '', 'class');
		$sClassLabel = MetaModel::GetName($sClass);
			$sTransactionId = utils::ReadPostedParam('transaction_id', '', 'transaction_id');
		$aErrors = array();
		if ( empty($sClass) ) // TO DO: check that the class name is valid !
		{
			throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'class'));
		}
		if (!utils::IsTransactionValid($sTransactionId, false))
		{
			$sUser = UserRights::GetUser();
			IssueLog::Error("UI.php '$operation' : invalid transaction_id ! data: user='$sUser', class='$sClass'");
			$oP->p("<strong>".Dict::S('UI:Error:ObjectAlreadyCreated')."</strong>\n");
		}
		else
		{
			/** @var \cmdbAbstractObject $oObj */
			$oObj = MetaModel::NewObject($sClass);
			$sStateAttCode = MetaModel::GetStateAttributeCode($sClass);
			if (!empty($sStateAttCode))
			{
				$sTargetState = utils::ReadPostedParam('obj_state', '');
				if ($sTargetState != '')
				{
					$oObj->Set($sStateAttCode, $sTargetState);
				}
			}
			$aErrors = $oObj->UpdateObjectFromPostedForm();
		}
		if (isset($oObj) && is_object($oObj))
		{
			$sClass = get_class($oObj);
			$sClassLabel = MetaModel::GetName($sClass);

			try
			{
				if (!empty($aErrors))
				{
					throw new CoreCannotSaveObjectException(array('id' => $oObj->GetKey(), 'class' => $sClass, 'issues' => $aErrors));
				}

				$oObj->DBInsertNoReload();// No need to reload

				utils::RemoveTransaction($sTransactionId);
				$oP->set_title(Dict::S('UI:PageTitle:ObjectCreated'));

				// Compute the name, by reloading the object, even if it disappeared from the silo
				$oObj = MetaModel::GetObject($sClass, $oObj->GetKey(), true /* Must be found */, true /* Allow All Data*/);
				$sName = $oObj->GetName();
				$sMessage = Dict::Format('UI:Title:Object_Of_Class_Created', $sName, $sClassLabel);

				$sNextAction = utils::ReadPostedParam('next_action', '');
				if (!empty($sNextAction))
				{
					$oP->add("<h1>$sMessage</h1>");
					ApplyNextAction($oP, $oObj, $sNextAction);
				}
				else
				{
					// Nothing more to do
					ReloadAndDisplay($oP, $oObj, 'create', $sMessage, 'ok');
				}
			}
			catch (CoreCannotSaveObjectException $e)
			{
				// Found issues, explain and give the user a second chance
				//
				$aIssues = $e->getIssues();

				$oP->set_title(Dict::Format('UI:CreationPageTitle_Class', $sClassLabel));
				$oP->add("<h1>".MetaModel::GetClassIcon($sClass)."&nbsp;".Dict::Format('UI:CreationTitle_Class', $sClassLabel)."</h1>\n");
				$oP->add("<div class=\"wizContainer\">\n");
				$oP->AddHeaderMessage($e->getHtmlMessage(), 'message_error');
				cmdbAbstractObject::DisplayCreationForm($oP, $sClass, $oObj);
				$oP->add("</div>\n");
			}
		}
		break;
			
		///////////////////////////////////////////////////////////////////////////////////////////

		case 'select_bulk_stimulus': // Form displayed when applying a stimulus to many objects
		$oP->DisableBreadCrumb();
		$sFilter = utils::ReadParam('filter', '', false, 'raw_data');
		$sStimulus = utils::ReadParam('stimulus', '');
		$sState = utils::ReadParam('state', '');
		if (empty($sFilter) || empty($sStimulus) || empty($sState))
		{
			throw new ApplicationException(Dict::Format('UI:Error:3ParametersMissing', 'filter', 'stimulus', 'state'));
		}
		$oFilter = DBObjectSearch::unserialize($sFilter);
		$oFilter->UpdateContextFromUser();
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
		$oP->DisableBreadCrumb();
		$sFilter = utils::ReadParam('filter', '', false, 'raw_data');
		$sStimulus = utils::ReadParam('stimulus', '');
		$sState = utils::ReadParam('state', '');
		if (empty($sFilter) || empty($sStimulus) || empty($sState))
		{
			throw new ApplicationException(Dict::Format('UI:Error:3ParametersMissing', 'filter', 'stimulus', 'state'));
		}
		$oFilter = DBObjectSearch::unserialize($sFilter);
		// Add user filter
		$oFilter->UpdateContextFromUser();
		$sClass = $oFilter->GetClass();
		$aSelectObject = utils::ReadMultipleSelection($oFilter);
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
			$sTargetState = $aTransitions[$sStimulus]['target_state'];
			$aStates = MetaModel::EnumStates($sClass);
			$aTargetStateDef = $aStates[$sTargetState];

			$oP->set_title(Dict::Format('UI:StimulusModify_N_ObjectsOf_Class', $sActionLabel, count($aSelectObject), $sClass));
			$oP->add('<div class="page_header">');
			$oP->add('<h1>'.MetaModel::GetClassIcon($sClass).'&nbsp;'.Dict::Format('UI:StimulusModify_N_ObjectsOf_Class', $sActionLabel, count($aSelectObject), $sClass).'</h1>');
			$oP->add('</div>');

			$aExpectedAttributes = MetaModel::GetTransitionAttributes($sClass, $sStimulus, $sState);
			$aDetails = array();
			$sFormId = 'apply_stimulus';
			$sFormPrefix = $sFormId.'_';
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
				$sFieldInputId = $sFormPrefix.$sAttCode;
				// Prompt for an attribute if
				// - the attribute must be changed or must be displayed to the user for confirmation
				// - or the field is mandatory and currently empty
				if ( ($iExpectCode & (OPT_ATT_MUSTCHANGE | OPT_ATT_MUSTPROMPT)) ||
					 (($iExpectCode & OPT_ATT_MANDATORY) && ($oObj->Get($sAttCode) == '')) ) 
				{
					$aAttributesDef = MetaModel::ListAttributeDefs($sClass);
					$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
					$aPrerequisites = MetaModel::GetPrerequisiteAttributes($sClass, $sAttCode); // List of attributes that are needed for the current one
					if (count($aPrerequisites) > 0)
					{
						// When 'enabling' a field, all its prerequisites must be enabled too
						$sFieldList = "['{$sFormPrefix}".implode("','{$sFormPrefix}", $aPrerequisites)."']";
						$oP->add_ready_script("$('#enable_{$sFieldInputId}').bind('change', function(evt, sFormId) { return PropagateCheckBox( this.checked, $sFieldList, true); } );\n");
					}
					$aDependents = MetaModel::GetDependentAttributes($sClass, $sAttCode); // List of attributes that are needed for the current one
					if (count($aDependents) > 0)
					{
						// When 'disabling' a field, all its dependent fields must be disabled too
						$sFieldList = "['{$sFormPrefix}".implode("','{$sFormPrefix}", $aDependents)."']";
						$oP->add_ready_script("$('#enable_{$sFieldInputId}').bind('change', function(evt, sFormId) { return PropagateCheckBox( this.checked, $sFieldList, false); } );\n");
					}
					$aArgs = array('this' => $oObj);
					$sHTMLValue = cmdbAbstractObject::GetFormElementForField($oP, $sClass, $sAttCode, $oAttDef, $oObj->Get($sAttCode), $oObj->GetEditValue($sAttCode), $sFieldInputId, '', $iExpectCode, $aArgs);
					$sComments = '<input type="checkbox" checked id="enable_'.$sFieldInputId.'"  onClick="ToggleField(this.checked, \''.$sFieldInputId.'\')"/>';
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
								$sTip .= "<li>".Dict::Format('UI:BulkModify:N_MoreValues', count($aValues[$sAttCode]) - $iMaxCount)."</li>";
								break;
							}					
						}
						$sTip .= "</ul></p>";
						$sTip = addslashes($sTip);
						$sReadyScript .= "$('#multi_values_$sFieldInputId').qtip( { content: '$sTip', show: 'mouseover', hide: 'mouseout', style: { name: 'dark', tip: 'leftTop' }, position: { corner: { target: 'rightMiddle', tooltip: 'leftTop' }} } );\n";
						$sComments .= '<div class="multi_values" id="multi_values_'.$sFieldInputId.'">'.count($aValues[$sAttCode]).'</div>';
					}
					$aDetails[] = array('label' => '<span>'.$oAttDef->GetLabel().'</span>', 'value' => "<span id=\"field_$sFieldInputId\">$sHTMLValue</span>", 'comments' => $sComments);
					$aFieldsMap[$sAttCode] = $sFieldInputId;
					$iFieldIndex++;
				}
			}
			$sButtonsPosition = MetaModel::GetConfig()->Get('buttons_position');
			if ($sButtonsPosition == 'bottom')
			{
				// bottom: Displays the ticket details BEFORE the actions
				$oP->add('<div class="ui-widget-content">');
				$oObj->DisplayBareProperties($oP);
				$oP->add('</div>');
			}
			$oP->add("<div class=\"wizContainer\">\n");
			$oP->add("<form id=\"{$sFormId}\" method=\"post\" onSubmit=\"return OnSubmit('{$sFormId}');\">\n");
			$oP->add("<table><tr><td>\n");
			$oP->details($aDetails);
			$oP->add("</td></tr></table>\n");
			$oP->add("<input type=\"hidden\" name=\"class\" value=\"$sClass\">\n");
			$oP->add("<input type=\"hidden\" name=\"operation\" value=\"bulk_apply_stimulus\">\n");
			$oP->add("<input type=\"hidden\" name=\"preview_mode\" value=\"1\">\n");
			$oP->add("<input type=\"hidden\" name=\"filter\" value=\"".utils::HtmlEntities($sFilter)."\">\n");
			$oP->add("<input type=\"hidden\" name=\"stimulus\" value=\"$sStimulus\">\n");
			$oP->add("<input type=\"hidden\" name=\"state\" value=\"$sState\">\n");
			$oP->add("<input type=\"hidden\" name=\"transaction_id\" value=\"".utils::GetNewTransactionId()."\">\n");
			$oP->add($oAppContext->GetForForm());
			$oP->add("<input type=\"hidden\" name=\"selectObject\" value=\"".implode(',',$aSelectObject)."\">\n");
			$sURL = "./UI.php?operation=search&filter=".urlencode($sFilter)."&".$oAppContext->GetForLink();
			$oP->add("<input type=\"button\" value=\"".Dict::S('UI:Button:Cancel')."\" onClick=\"window.location.href='$sURL'\">&nbsp;&nbsp;&nbsp;&nbsp;\n");
			$oP->add("<button type=\"submit\" class=\"action\"><span>$sActionLabel</span></button>\n");
			$oP->add("</form>\n");
			$oP->add("</div>\n");
			if ($sButtonsPosition != 'bottom')
			{
				// top or both: Displays the ticket details AFTER the actions
				$oP->add('<div class="ui-widget-content">');
				$oObj->DisplayBareProperties($oP);
				$oP->add('</div>');
			}
			$iFieldsCount = count($aFieldsMap);
			$sJsonFieldsMap = json_encode($aFieldsMap);
	
			$oP->add_script(
<<<EOF
			// Initializes the object once at the beginning of the page...
			var oWizardHelper = new WizardHelper('$sClass', '', '$sTargetState', '$sState', '$sStimulus');
			oWizardHelper.SetFieldsMap($sJsonFieldsMap);
			oWizardHelper.SetFieldsCount($iFieldsCount);
EOF
);
			$oP->add_ready_script(
<<<EOF
			// Starts the validation when the page is ready
			CheckFields('{$sFormId}', false);
			$sReadyScript
EOF
);
		}
		break;
		
		case 'bulk_apply_stimulus':
		$oP->DisableBreadCrumb();
		$bPreviewMode = utils::ReadPostedParam('preview_mode', false);
		$sFilter = utils::ReadPostedParam('filter', '', 'raw_data');
		$sStimulus = utils::ReadPostedParam('stimulus', '');
		$sState = utils::ReadPostedParam('state', '');
		$sSelectObject = utils::ReadPostedParam('selectObject', '', 'raw_data');
		$aSelectObject = explode(',', $sSelectObject);

		if (empty($sFilter) || empty($sStimulus) || empty($sState))
		{
			throw new ApplicationException(Dict::Format('UI:Error:3ParametersMissing', 'filter', 'stimulus', 'state'));
		}
			$sTransactionId = utils::ReadPostedParam('transaction_id', '', 'transaction_id');
		if (!utils::IsTransactionValid($sTransactionId))
		{
			$sUser = UserRights::GetUser();
			IssueLog::Error("UI.php '$operation' : invalid transaction_id ! data: user='$sUser'");
			$oP->p(Dict::S('UI:Error:ObjectAlreadyUpdated'));
		}
		else
		{
			// For archiving the modification
			$oFilter = DBObjectSearch::unserialize($sFilter);
			// Add user filter
			$oFilter->UpdateContextFromUser();
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
						$sTargetState = $aTransitions[$sStimulus]['target_state'];
						$aExpectedAttributes = $oObj->GetTransitionAttributes($sStimulus /* cureent state */);
						$aDetails = array();
						$aErrors = array();
						foreach($aExpectedAttributes as $sAttCode => $iExpectCode)
						{
							$iFlags = $oObj->GetTransitionFlags($sAttCode, $sStimulus);
							if (($iExpectCode & (OPT_ATT_MUSTCHANGE|OPT_ATT_MUSTPROMPT)) || ($oObj->Get($sAttCode) == '') ) 
							{
								$paramValue = utils::ReadPostedParam("attr_$sAttCode", '', 'raw_data');
								if ( ($iFlags & OPT_ATT_SLAVE) && ($paramValue != $oObj->Get($sAttCode)) )
								{
									$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
									$aErrors[] = Dict::Format('UI:AttemptingToSetASlaveAttribute_Name', $oAttDef->GetLabel());
									unset($aExpectedAttributes[$sAttCode]);
								}
							}
						}
						
						$oObj->UpdateObjectFromPostedForm('', array_keys($aExpectedAttributes), $aExpectedAttributes);
						
						if (count($aErrors) == 0)
						{
							if ($oObj->ApplyStimulus($sStimulus))
							{
								list($bResult, $aErrors) = $oObj->CheckToWrite();
								$sStatus = $bResult ? Dict::S('UI:BulkModifyStatusModified') : Dict::S('UI:BulkModifyStatusSkipped');							
								if ($bResult)
								{
									$oObj->DBUpdate();
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
			$sURL = "./UI.php?operation=search&filter=".urlencode($sFilter)."&".$oAppContext->GetForLink();
			$oP->add('<input type="button" onClick="window.location.href=\''.$sURL.'\'" value="'.Dict::S('UI:Button:Done').'">');
		}
		break;

		case 'stimulus': // Form displayed when applying a stimulus (state change)
		$oP->DisableBreadCrumb();
		$sClass = utils::ReadParam('class', '', false, 'class');
		$id = utils::ReadParam('id', '');
		$sStimulus = utils::ReadParam('stimulus', '');
		if ( empty($sClass) || empty($id) ||  empty($sStimulus) ) // TO DO: check that the class name is valid !
		{
			throw new ApplicationException(Dict::Format('UI:Error:3ParametersMissing', 'class', 'id', 'stimulus'));
		}
		$aStimuli = MetaModel::EnumStimuli($sClass);
		if ((get_class($aStimuli[$sStimulus]) !== 'StimulusUserAction') || (UserRights::IsStimulusAllowed($sClass, $sStimulus) === UR_ALLOWED_NO))
		{
			$sUser = UserRights::GetUser();
			IssueLog::Error("UI.php '$operation' : Stimulus '$sStimulus' not allowed ! data: user='$sUser', class='$sClass'");
			throw new ApplicationException(Dict::S('UI:Error:ActionNotAllowed'));
		}

			$oObj = MetaModel::GetObject($sClass, $id, false);
		if ($oObj != null)
		{
			$aPrefillFormParam = array( 'user' => $_SESSION["auth_user"],
				'context' => $oAppContext->GetAsHash(),
				'stimulus' => $sStimulus,
				'origin' => 'console'
			);
			$oObj->DisplayStimulusForm($oP, $sStimulus, $aPrefillFormParam);
		}
		else
		{
			$oP->set_title(Dict::S('UI:ErrorPageTitle'));
			$oP->P(Dict::S('UI:ObjectDoesNotExist'));
		}		
		break;

		///////////////////////////////////////////////////////////////////////////////////////////

		case 'apply_stimulus': // Actual state change
		$oP->DisableBreadCrumb();
		$sClass = utils::ReadPostedParam('class', '');
		$id = utils::ReadPostedParam('id', '');
			$sTransactionId = utils::ReadPostedParam('transaction_id', '', 'transaction_id');
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
			$sMessage = '';
			$sSeverity = 'ok';
			$bDisplayDetails = true;
			if (!isset($aTransitions[$sStimulus]))
			{
				throw new ApplicationException(Dict::Format('UI:Error:Invalid_Stimulus_On_Object_In_State', $sStimulus, $oObj->GetName(), $oObj->GetStateLabel()));
			}
			if (!utils::IsTransactionValid($sTransactionId))
			{
				$sUser = UserRights::GetUser();
				IssueLog::Error("UI.php '$operation' : invalid transaction_id ! data: user='$sUser', class='$sClass'");
				$sMessage = Dict::S('UI:Error:ObjectAlreadyUpdated');
				$sSeverity = 'info';
			}
			elseif ((get_class($aStimuli[$sStimulus]) !== 'StimulusUserAction') || (UserRights::IsStimulusAllowed($sClass, $sStimulus) === UR_ALLOWED_NO))
			{
				$sUser = UserRights::GetUser();
				IssueLog::Error("UI.php '$operation' : Stimulus '$sStimulus' not allowed ! data: user='$sUser', class='$sClass'");
				$sMessage = Dict::S('UI:Error:ActionNotAllowed');
				$sSeverity = 'error';
			}
			else
			{
				$sActionLabel = $aStimuli[$sStimulus]->GetLabel();
				$sActionDetails = $aStimuli[$sStimulus]->GetDescription();
				$sTargetState = $aTransitions[$sStimulus]['target_state'];
				$aExpectedAttributes = $oObj->GetTransitionAttributes($sStimulus /*, current state*/);
				$aDetails = array();
				$aErrors = array();
				foreach($aExpectedAttributes as $sAttCode => $iExpectCode)
				{
					$iFlags = $oObj->GetTransitionFlags($sAttCode, $sStimulus);
					if (($iExpectCode & (OPT_ATT_MUSTCHANGE|OPT_ATT_MUSTPROMPT)) || ($oObj->Get($sAttCode) == '') ) 
					{
						$paramValue = utils::ReadPostedParam("attr_$sAttCode", '', 'raw_data');
						if ( ($iFlags & OPT_ATT_SLAVE) && ($paramValue != $oObj->Get($sAttCode)))
						{
							$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
							$aErrors[] = Dict::Format('UI:AttemptingToChangeASlaveAttribute_Name', $oAttDef->GetLabel());
							unset($aExpectedAttributes[$sAttCode]);
						}
					}
				}
				
				$oObj->UpdateObjectFromPostedForm('', array_keys($aExpectedAttributes), $aExpectedAttributes);
				
				if (count($aErrors) == 0)
				{
					$sIssues = '';
					$bApplyStimulus = true;
					list($bRes, $aIssues) = $oObj->CheckToWrite(); // Check before trying to write the object
					if ($bRes)
					{
						try
						{
							$bApplyStimulus = $oObj->ApplyStimulus($sStimulus); // will write the object in the DB
						}
						catch(CoreException $e)
						{
							// Rollback to the previous state... by reloading the object from the database and applying the modifications again
							$oObj = MetaModel::GetObject(get_class($oObj), $oObj->GetKey());
							$oObj->UpdateObjectFromPostedForm('', array_keys($aExpectedAttributes), $aExpectedAttributes);
							$sIssues = $e->getMessage();
						}
					}
					else
					{
						$sIssues = implode(' ', $aIssues);
					}
					
					if (!$bApplyStimulus)
					{
						$sMessage = Dict::S('UI:FailedToApplyStimuli');
						$sSeverity = 'error';

						$sOwnershipToken = utils::ReadPostedParam('ownership_token', null, 'raw_data');
						if ($sOwnershipToken !== null)
						{
							// Release the concurrent lock, if any
							iTopOwnershipLock::ReleaseLock(get_class($oObj), $oObj->GetKey(), $sOwnershipToken);
						}
					}
					else if ($sIssues != '')
					{
						$sOwnershipToken = utils::ReadPostedParam('ownership_token', null, 'raw_data');
						if ($sOwnershipToken !== null)
						{
							// Release the concurrent lock, if any, a new lock will be re-acquired by DisplayStimulusForm below
							iTopOwnershipLock::ReleaseLock(get_class($oObj), $oObj->GetKey(), $sOwnershipToken);
						}
							
						$bDisplayDetails = false;
						// Found issues, explain and give the user a second chance
						//
						$oObj->DisplayStimulusForm($oP, $sStimulus);
						$sIssueDesc = Dict::Format('UI:ObjectCouldNotBeWritten',$sIssues);
						$oP->add_ready_script("alert('".addslashes($sIssueDesc)."');");
					}
					else
					{
						$sMessage = Dict::Format('UI:Class_Object_Updated', MetaModel::GetName(get_class($oObj)), $oObj->GetName());
						$sSeverity = 'ok';
						utils::RemoveTransaction($sTransactionId);
						$bLockEnabled = MetaModel::GetConfig()->Get('concurrent_lock_enabled');
						if ($bLockEnabled)
						{
							// Release the concurrent lock, if any
							$sOwnershipToken = utils::ReadPostedParam('ownership_token', null, 'raw_data');
							if ($sOwnershipToken !== null)
							{
								// We're done, let's release the lock
								iTopOwnershipLock::ReleaseLock(get_class($oObj), $oObj->GetKey(), $sOwnershipToken);
							}
						}
					}
				}
				else
				{
					$sMessage = implode('</p><p>', $aErrors);
					$sSeverity = 'error';
				}
			}
			if ($bDisplayDetails)
			{
				ReloadAndDisplay($oP, $oObj, 'apply_stimulus', $sMessage, $sSeverity);
			}
		}
		else
		{
			$oP->set_title(Dict::S('UI:ErrorPageTitle'));
			$oP->P(Dict::S('UI:ObjectDoesNotExist'));
		}		
		break;

		///////////////////////////////////////////////////////////////////////////////////////////
		
		case 'swf_navigator': // Graphical display of the relations "impact" / "depends on"
		require_once(APPROOT.'core/simplegraph.class.inc.php');
		require_once(APPROOT.'core/relationgraph.class.inc.php');
		require_once(APPROOT.'core/displayablegraph.class.inc.php');
		$sClass = utils::ReadParam('class', '', false, 'class');
		$id = utils::ReadParam('id', 0);
		$sRelation = utils::ReadParam('relation', 'impact');
		$sDirection = utils::ReadParam('direction', 'down');
		$iGroupingThreshold = utils::ReadParam('g', 5);

		$bDirDown = ($sDirection === 'down');
		$oObj = MetaModel::GetObject($sClass, $id);
		$iMaxRecursionDepth = MetaModel::GetConfig()->Get('relations_max_depth');
		$aSourceObjects = array($oObj);

		$oP->set_title(MetaModel::GetRelationDescription($sRelation, $bDirDown).' '.$oObj->GetName());

		$sPageId = "ui-relation-graph-".$sClass.'::'.$id;
		$sLabel = $oObj->GetName().' '.MetaModel::GetRelationLabel($sRelation, $bDirDown);
		$sDescription = MetaModel::GetRelationDescription($sRelation, $bDirDown).' '.$oObj->GetName();
		$oP->SetBreadCrumbEntry($sPageId, $sLabel, $sDescription);

			if ($sRelation == 'depends on')
		{
			$sRelation = 'impacts';
			$sDirection = 'up';
		}
		if ($sDirection == 'up')
		{
			$oRelGraph = MetaModel::GetRelatedObjectsUp($sRelation, $aSourceObjects, $iMaxRecursionDepth);
		}
		else
		{
			$oRelGraph = MetaModel::GetRelatedObjectsDown($sRelation, $aSourceObjects, $iMaxRecursionDepth);
		}
		

		$aResults = $oRelGraph->GetObjectsByClass();
		$oDisplayGraph = DisplayableGraph::FromRelationGraph($oRelGraph, $iGroupingThreshold, ($sDirection == 'down'));		
		
		$oP->AddTabContainer('Navigator');
		$oP->SetCurrentTabContainer('Navigator');
		
		$sFirstTab = MetaModel::GetConfig()->Get('impact_analysis_first_tab');
		$sContextKey = "itop-config-mgmt/relation_context/$sClass/$sRelation/$sDirection";
		
		// Check if the current object supports Attachments, similar to AttachmentPlugin::IsTargetObject
		$sClassForAttachment = null;
		$iIdForAttachment = null;
		if (class_exists('Attachment'))
		{
			$aAllowedClasses = MetaModel::GetModuleSetting('itop-attachments', 'allowed_classes', array('Ticket'));
			foreach($aAllowedClasses as $sAllowedClass)
			{
				if ($oObj instanceof $sAllowedClass)
				{
					$iIdForAttachment = $id;
					$sClassForAttachment = $sClass;
				}
			}
		}
		
		// Display the tabs
		if ($sFirstTab == 'list')
		{
			DisplayNavigatorListTab($oP, $aResults, $sRelation, $sDirection, $oObj);
			$oP->SetCurrentTab(Dict::S('UI:RelationshipGraph'));
			$oDisplayGraph->Display($oP, $aResults, $sRelation, $oAppContext, array(), $sClassForAttachment, $iIdForAttachment, $sContextKey, array('this' => $oObj));
			DisplayNavigatorGroupTab($oP);
		}
		else
		{
			$oP->SetCurrentTab(Dict::S('UI:RelationshipGraph'));
			$oDisplayGraph->Display($oP, $aResults, $sRelation, $oAppContext, array(), $sClassForAttachment, $iIdForAttachment, $sContextKey, array('this' => $oObj));
			DisplayNavigatorListTab($oP, $aResults, $sRelation, $sDirection, $oObj);
			DisplayNavigatorGroupTab($oP);
		}

		$oP->SetCurrentTab('');
		break;
		
		///////////////////////////////////////////////////////////////////////////////////////////
		
		case 'kill_lock':
		$oP->DisableBreadCrumb();
		$sClass = utils::ReadParam('class', '');
		$id = utils::ReadParam('id', '');
		iTopOwnershipLock::KillLock($sClass, $id);
		$oObj = MetaModel::GetObject($sClass, $id);
		ReloadAndDisplay($oP, $oObj, 'concurrent_lock_killed', Dict::S('UI:ConcurrentLockKilled'), 'info');
		break;
		
		///////////////////////////////////////////////////////////////////////////////////////////

		case 'cancel': // An action was cancelled
		$oP->DisableBreadCrumb();
		$oP->set_title(Dict::S('UI:OperationCancelled'));
		$oP->add('<h1>'.Dict::S('UI:OperationCancelled').'</h1>');
		break;
	
		///////////////////////////////////////////////////////////////////////////////////////////

		default: // Menu node rendering (templates)
		ApplicationMenu::LoadAdditionalMenus();
		$oMenuNode = ApplicationMenu::GetMenuNode(ApplicationMenu::GetMenuIndexById(ApplicationMenu::GetActiveNodeId()));
		if (is_object($oMenuNode))
		{
			$oMenuNode->RenderContent($oP, $oAppContext->GetAsHash());
			$oP->set_title($oMenuNode->GetLabel());
		}
		
		///////////////////////////////////////////////////////////////////////////////////////////

	}
	DisplayWelcomePopup($oP);
	$oP->output();	
}
catch(CoreException $e)
{
	require_once(APPROOT.'/setup/setuppage.class.inc.php');
	$oP = new SetupPage(Dict::S('UI:PageTitle:FatalError'));
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
	$oP = new SetupPage(Dict::S('UI:PageTitle:FatalError'));
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