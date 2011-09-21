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
 * iTop User Portal main page
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */
require_once('../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/nicewebpage.class.inc.php');
require_once(APPROOT.'/application/wizardhelper.class.inc.php');



define('SERVICECATEGORY_QUERY', 'SELECT Service AS s JOIN SLA AS sla ON sla.service_id=s.id JOIN lnkContractToSLA AS ln ON ln.sla_id=sla.id JOIN CustomerContract AS cc ON ln.contract_id=cc.id WHERE cc.org_id = :org_id');
define('SERVICE_SUBCATEGORY_QUERY', 'SELECT ServiceSubcategory WHERE service_id = :svc_id');

define('VALIDATE_SERVICECATEGORY_QUERY', 'SELECT Service AS s JOIN SLA AS sla ON sla.service_id=s.id JOIN lnkContractToSLA AS ln ON ln.sla_id=sla.id JOIN CustomerContract AS cc ON ln.contract_id=cc.id WHERE cc.org_id = :org_id AND s.id = :id');
define('VALIDATE_SERVICESUBCATEGORY_QUERY', 'SELECT ServiceSubcategory AS Sub JOIN Service AS Svc ON Sub.service_id = Svc.id WHERE Svc.org_id=:org_id AND Sub.id=:id');

define('ALL_PARAMS', 'from_service_id,org_id,caller_id,service_id,servicesubcategory_id,title,description,impact,urgency,workgroup_id,moreinfo,caller_id,start_date,end_date,duration,impact_duration');



/**
 * Displays the portal main menu
 * @param WebPage $oP The current web page
 * @return void
 */
function DisplayMainMenu(WebPage $oP)
{
	$oP->AddMenuButton('showongoing', 'Portal:ShowOngoing', './index.php?operation=show_ongoing');
	$oP->AddMenuButton('newrequest', 'Portal:CreateNewRequest', './index.php?operation=create_request');
	$oP->AddMenuButton('showclosed', 'Portal:ShowClosed', './index.php?operation=show_closed');
	$oP->AddMenuButton('change_pwd', 'Portal:ChangeMyPassword', './index.php?loginop=change_pwd');
}

/**
 * Displays the current tickets
 * @param WebPage $oP The current web page
 * @return void
 */
function ShowOngoingTickets(WebPage $oP)
{
	$oP->add("<div id=\"open_requests\">\n");
	$oP->add("<h1 id=\"title_open_requests\">".Dict::S('Portal:OpenRequests')."</h1>\n");
	ListOpenRequests($oP);
	$oP->add("</div>\n");

	$oP->add("<div id=\"#div_resolved_requests\">\n");
	$oP->add("<h1 id=\"#resolved_requests\">".Dict::S('Portal:ResolvedRequests')."</h1>\n");
	ListResolvedRequests($oP);
	$oP->add("</div>\n");
}

/**
 * Displays the closed tickets
 * @param WebPage $oP The current web page
 * @return void
 */
function ShowClosedTickets(WebPage $oP)
{
	$oP->add("<div id=\"#closed_tickets\">\n");
	//$oP->add("<h1 id=\"#closed_tickets\">".Dict::S('Portal:ListClosedTickets')."</h1>\n");
	ListClosedTickets($oP);
	$oP->add("</div>\n");
}

/**
 * Displays the form to select a Service Category Id (among the valid ones for the specified user Organization)
 * @param WebPage $oP Web page for the form output
 * @param Organization $oUserOrg The organization of the current user
 * @return void
 */
function SelectServiceCategory($oP, $oUserOrg)
{
	$aParameters = $oP->ReadAllParams(ALL_PARAMS);
	
	$oP->add("<div class=\"wizContainer\" id=\"form_select_service\">\n");
	$oP->WizardFormStart('request_wizard', 1);

	$oP->add("<h1 id=\"select_category\">".Dict::S('Portal:SelectService')."</h1>\n");
	$oP->add("<table>\n");
	$oSearch = DBObjectSearch::FromOQL(SERVICECATEGORY_QUERY);
	$oSet = new CMDBObjectSet($oSearch, array(), array('org_id' => $oUserOrg->GetKey()));
	while($oService = $oSet->Fetch())
	{
		$id = $oService->GetKey();
		$sChecked = "";
		if (isset($aParameters['service_id']) && ($id == $aParameters['service_id']))
		{
			$sChecked = "checked";
		}
		$oP->p("<tr><td style=\"vertical-align:top\"><p><input name=\"attr_service_id\" $sChecked type=\"radio\" id=\"service_$id\" value=\"$id\"></p></td><td style=\"vertical-align:top\"><p><b><label for=\"service_$id\">".htmlentities($oService->GetName(), ENT_QUOTES, 'UTF-8')."</label></b></p>");
		$oP->p("<p>".$oService->GetAsHTML('description')."</p></td></tr>");		
	}
	$oP->add("</table>\n");	

	$oP->DumpHiddenParams($aParameters, array('service_id'));
	$oP->add("<input type=\"hidden\" name=\"operation\" value=\"create_request\">");
	$oP->WizardFormButtons(BUTTON_BACK | BUTTON_NEXT | BUTTON_CANCEL);
	$oP->WizardFormEnd();
	$oP->WizardCheckSelectionOnSubmit(Dict::S('Portal:PleaseSelectOneService'));
	$oP->add("</div>\n");
}

/**
 * Displays the form to select a Service Subcategory Id (among the valid ones for the specified user Organization)
 * and based on the page's parameter 'service_id'
 * @param WebPage $oP Web page for the form output
 * @param Organization $oUserOrg The organization of the current user
 * @return void
 */

function SelectServiceSubCategory($oP, $oUserOrg)
{
	$aParameters = $oP->ReadAllParams(ALL_PARAMS);

	$iSvcId = $aParameters['service_id'];
	$iDefaultSubSvcId = isset($aParameters['servicesubcategory_id']) ? $aParameters['servicesubcategory_id'] : 0;

	$iDefaultWizNext = 2;

	$oSearch = DBObjectSearch::FromOQL(SERVICE_SUBCATEGORY_QUERY);
	$oSet = new CMDBObjectSet($oSearch, array(), array('svc_id' => $iSvcId, 'org_id' => $oUserOrg->GetKey()));
	$oServiceCategory = MetaModel::GetObject('Service', $iSvcId, false);
	if (is_object($oServiceCategory))
	{
		$oP->add("<div class=\"wizContainer\" id=\"form_select_servicesubcategory\">\n");
		$oP->add("<h1 id=\"select_subcategory\">".Dict::Format('Portal:SelectSubcategoryFrom_Service', htmlentities($oServiceCategory->GetName(), ENT_QUOTES, 'UTF-8'))."</h1>\n");
		$oP->WizardFormStart('request_wizard', $iDefaultWizNext);
		$oP->add("<table>\n");
		while($oSubService = $oSet->Fetch())
		{
			$id = $oSubService->GetKey();
			$sChecked = "";
			if ($id == $iDefaultSubSvcId)
			{
				$sChecked = "checked";
			}

			$oP->add("<tr>");

			$oP->add("<td style=\"vertical-align:top\">");
			$oP->add("<p><input name=\"attr_servicesubcategory_id\" $sChecked type=\"radio\" id=\"servicesubcategory_$id\" value=\"$id\"></p>");
			$oP->add("</td>");

			$oP->add("<td style=\"vertical-align:top\">");
			$oP->add("<p><b><label for=\"servicesubcategory_$id\">".htmlentities($oSubService->GetName(), ENT_QUOTES, 'UTF-8')."</label></b></p>");
			$oP->add("<p>".$oSubService->GetAsHTML('description')."</p>");
			$oP->add("</td>");
			$oP->add("</tr>");
		}
		$oP->add("</table>\n");	
		$oP->DumpHiddenParams($aParameters, array('servicesubcategory_id'));
		$oP->add("<input type=\"hidden\" name=\"operation\" value=\"create_request\">");
		$oP->WizardFormButtons(BUTTON_BACK | BUTTON_NEXT | BUTTON_CANCEL);
		$oP->WizardFormEnd();
		$oP->WizardCheckSelectionOnSubmit(Dict::S('Portal:PleaseSelectAServiceSubCategory'));
		$oP->add("</div>\n");
	}
	else
	{
		$oP->p("Error: Invalid Service: id = $iSvcId");
	}
}

/**
 * Displays the form for the final step of the UserRequest creation
 * @param WebPage $oP The current web page for the form output
 * @param Organization $oUserOrg The organization of the current user
 * @return void
 */
function RequestCreationForm($oP, $oUserOrg)
{
		$oP->add_ready_script(
<<<EOF
		// Create the object once at the beginning of the page...
		var oWizardHelper = new WizardHelper('UserRequest', '');
EOF
);
	$aParameters = $oP->ReadAllParams(ALL_PARAMS);

	$aList = array('title', 'description', 'impact', 'urgency', 'workgroup_id');

	$sDescription = '';
	if (isset($aParameters['template_id']))
	{
		$oTemplate = MetaModel::GetObject('Template', $aParameters['template_id'], false);
		if (is_object($oTemplate))
		{
			$sDescription = htmlentities($oTemplate->Get('template'), ENT_QUOTES, 'UTF-8');
		}
	}

	$oServiceCategory = MetaModel::GetObject('Service', $aParameters['service_id'], false);
	$oServiceSubCategory = MetaModel::GetObject('ServiceSubcategory', $aParameters['servicesubcategory_id'], false);
	if (is_object($oServiceCategory) && is_object($oServiceSubCategory))
	{
		$oRequest = new UserRequest();
		$oRequest->Set('org_id', $oUserOrg->GetKey());
		$oRequest->Set('caller_id', UserRights::GetContactId());
		$oRequest->Set('service_id', $aParameters['service_id']);
		$oRequest->Set('servicesubcategory_id', $aParameters['servicesubcategory_id']);
		
		$oAttDef = MetaModel::GetAttributeDef('UserRequest', 'service_id');
		$aDetails[] = array('label' => $oAttDef->GetLabel(), 'value' => htmlentities($oServiceCategory->GetName(), ENT_QUOTES, 'UTF-8'));
		$oAttDef = MetaModel::GetAttributeDef('UserRequest', 'servicesubcategory_id');
		$aDetails[] = array('label' => $oAttDef->GetLabel(), 'value' => htmlentities($oServiceSubCategory->GetName(), ENT_QUOTES, 'UTF-8'));

		$iFlags = 0;
		foreach($aList as $sAttCode)
		{
			$value = '';
			if (isset($aParameters[$sAttCode]))
			{
				$value = $aParameters[$sAttCode];
				$oRequest->Set($sAttCode, $value);
			}
		}
		$aFieldsMap = array();
		foreach($aList as $sAttCode)
		{
			$value = '';
			$oAttDef = MetaModel::GetAttributeDef(get_class($oRequest), $sAttCode);
			$iFlags = $oRequest->GetAttributeFlags($sAttCode);
			if (isset($aParameters[$sAttCode]))
			{
				$value = $aParameters[$sAttCode];
			}
			$aArgs = array('this' => $oRequest);
				
			$aFieldsMap[$sAttCode] = 'attr_'.$sAttCode;
			$sValue = $oRequest->GetFormElementForField($oP, get_class($oRequest), $sAttCode, $oAttDef, $value, '', 'attr_'.$sAttCode, '', $iFlags, $aArgs);
			$aDetails[] = array('label' => $oAttDef->GetLabel(), 'value' => $sValue);
		}
		$aDetails[] = array('label' => Dict::S('Class:Ticket/Attribute:ticket_log'), 'value' => '<textarea id="attr_moreinfo" class="resizable ui-resizable" cols="40" rows="8" name="attr_moreinfo" title="" style="margin: 0px; resize: none; position: static; display: block; height: 145px; width: 339px;">'.$sDescription.'</textarea>');

		$oP->add_linked_script("../js/json.js");
		$oP->add_linked_script("../js/forms-json-utils.js");
		$oP->add_linked_script("../js/wizardhelper.js");
		$oP->add_linked_script("../js/wizard.utils.js");
		$oP->add_linked_script("../js/linkswidget.js");
		$oP->add_linked_script("../js/extkeywidget.js");
		$oP->add_linked_script("../js/jquery.blockUI.js");
		$oP->add("<div class=\"wizContainer\" id=\"form_request_description\">\n");
		$oP->add("<h1 id=\"title_request_form\">".Dict::S('Portal:DescriptionOfTheRequest')."</h1>\n");
		$oP->WizardFormStart('request_form', 3);
		//$oP->add("<table>\n");
		$oP->details($aDetails);

		$oAttPlugin = new AttachmentPlugIn();
		$oAttPlugin->OnDisplayRelations($oRequest, $oP, true /* edit */);

		$oP->DumpHiddenParams($aParameters, $aList);
		$oP->add("<input type=\"hidden\" name=\"operation\" value=\"create_request\">");
		$oP->WizardFormButtons(BUTTON_BACK | BUTTON_FINISH | BUTTON_CANCEL);
		$oP->WizardFormEnd();
		$oP->add("</div>\n");
		$iFieldsCount = count($aFieldsMap);
		$sJsonFieldsMap = json_encode($aFieldsMap);

		$oP->add_ready_script(
<<<EOF
		oWizardHelper.SetFieldsMap($sJsonFieldsMap);
		oWizardHelper.SetFieldsCount($iFieldsCount);

		// Starts the validation when the page is ready
		CheckFields('request_form', false);
		$('#request_form').submit( function() {
			return OnSubmit('request_form');
		});
EOF
);
	}
	else
	{
		// User not authorized to use this service ?
		//ShowOngoingTickets($oP);
	}
}

/**
 * Validate the parameters and create the UserRequest object (based on the page's POSTed parameters)
 * @param WebPage $oP The current web page for the  output
 * @param Organization $oUserOrg The organization of the current user
 * @return void
 */
function DoCreateRequest($oP, $oUserOrg)
{
	$aParameters = $oP->ReadAllParams(ALL_PARAMS);
	$sTransactionId = utils::ReadPostedParam('transaction_id', '');
	if (!utils::IsTransactionValid($sTransactionId))
	{
		$oP->add("<h1>".Dict::S('UI:Error:ObjectAlreadyCreated')."</h1>\n");
		//ShowOngoingTickets($oP);
		return;
	}
		
	// Validate the parameters
	// 1) ServiceCategory
	$oSearch = DBObjectSearch::FromOQL(VALIDATE_SERVICECATEGORY_QUERY);
	$oSet = new CMDBObjectSet($oSearch, array(), array('id' => $aParameters['service_id'], 'org_id' => $oUserOrg->GetKey()));
	if ($oSet->Count() != 1)
	{
		// Invalid service for the current user !
		throw new Exception("Invalid Service Category: id={$aParameters['service_id']} - count: ".$oSet->Count());
	}
	$oServiceCategory = $oSet->Fetch();
	
	// 2) Service Subcategory
	$oSearch = DBObjectSearch::FromOQL(VALIDATE_SERVICESUBCATEGORY_QUERY);
	$oSet = new CMDBObjectSet($oSearch, array(), array('service_id' => $aParameters['service_id'], 'id' =>$aParameters['servicesubcategory_id'],'org_id' => $oUserOrg->GetKey() ));
	if ($oSet->Count() != 1)
	{
		// Invalid subcategory
		throw new Exception("Invalid ServiceSubcategory: id={$aParameters['servicesubcategory_id']} for service category ".$oServiceCategory->GetName()."({$aParameters['service_id']}) - count: ".$oSet->Count());
	}
	$oServiceSubCategory = $oSet->Fetch();
	
	$oRequest = new UserRequest();
	$oRequest->Set('org_id', $oUserOrg->GetKey());
	$oRequest->Set('caller_id', UserRights::GetContactId());
	$aList = array('service_id', 'servicesubcategory_id', 'title', 'description', 'impact');
	$oRequest->UpdateObjectFromPostedForm();
	if (isset($aParameters['moreinfo']))
	{
		// There is a template, insert it into the description
		$oRequest->Set('ticket_log', $aParameters['moreinfo']);
	}

	/////$oP->DoUpdateObjectFromPostedForm($oObj);
	$oAttPlugin = new AttachmentPlugIn();
	$oAttPlugin->OnFormSubmit($oRequest);

	list($bRes, $aIssues) = $oRequest->CheckToWrite();
	if ($bRes)
	{
		$oMyChange = MetaModel::NewObject("CMDBChange");
		$oMyChange->Set("date", time());
		$sUserString = CMDBChange::GetCurrentUserName();
		$oMyChange->Set("userinfo", $sUserString);
		$iChangeId = $oMyChange->DBInsert();
		$oRequest->DBInsertTracked($oMyChange);
		$oP->add("<h1>".Dict::Format('UI:Title:Object_Of_Class_Created', $oRequest->GetName(), MetaModel::GetName(get_class($oRequest)))."</h1>\n");

		//DisplayObject($oP, $oRequest, $oUserOrg);
		ShowOngoingTickets($oP);
	}
	else
	{
		RequestCreationForm($oP, $oUserOrg);
		$sIssueDesc = Dict::Format('UI:ObjectCouldNotBeWritten', implode(', ', $aIssues));
		$oP->add_ready_script("alert('".addslashes($sIssueDesc)."');");
	}	
}

/**
 * Prompts the user for creating a new request
 * @param WebPage $oP The current web page
 * @return void
 */
function CreateRequest(WebPage $oP, Organization $oUserOrg)
{
	switch($oP->GetWizardStep())
	{
		case 0:
		default:
		SelectServiceCategory($oP, $oUserOrg);
		break;
		
		case 1:
		SelectServiceSubCategory($oP, $oUserOrg);
		break;
		
		case 2:
		RequestCreationForm($oP, $oUserOrg);
		break;

		case 3:
		DoCreateRequest($oP, $oUserOrg);
		break;
	}
}

/**
 * Lists all the currently opened User Requests for the current user
 * @param WebPage $oP The current web page
 * @return void
 */
function ListOpenRequests(WebPage $oP)
{
	$oUserOrg = GetUserOrg();

	$sOQL = 'SELECT UserRequest WHERE org_id = :org_id AND status NOT IN ("closed")';
	$oSearch = DBObjectSearch::FromOQL($sOQL);
	$iUser = UserRights::GetContactId();
	if ($iUser > 0)
	{
		$oSearch->AddCondition('caller_id', $iUser);
	}
	$oSet = new CMDBObjectSet($oSearch, array(), array('org_id' => $oUserOrg->GetKey()));
	$aZList =  array('finalclass', 'title', 'start_date', 'status', 'servicesubcategory_id', 'priority', 'caller_id');
	$oP->DisplaySet($oSet, $aZList, Dict::S('Portal:NoOpenRequest'));
}

/**
 * Lists all the currently resolved (not yet closed) User Requests for the current user
 * @param WebPage $oP The current web page
 * @return void
 */
function ListResolvedRequests(WebPage $oP)
{
	$oUserOrg = GetUserOrg();

	$sOQL = 'SELECT UserRequest WHERE org_id = :org_id AND status = "resolved"';
	$oSearch = DBObjectSearch::FromOQL($sOQL);
	$iUser = UserRights::GetContactId();
	if ($iUser > 0)
	{
		$oSearch->AddCondition('caller_id', $iUser);
	}
	$oSet = new CMDBObjectSet($oSearch, array(), array('org_id' => $oUserOrg->GetKey()));
	$aZList =  array('finalclass', 'title', 'start_date', 'status', 'servicesubcategory_id', 'priority', 'caller_id');
	$oP->DisplaySet($oSet, $aZList, Dict::S('Portal:NoOpenRequest'));
}

/**
 * Lists all the currently closed tickets
 * @param WebPage $oP The current web page
 * @return void
 */
function ListClosedTickets(WebPage $oP)
{
	$aAttSpecs = array('ref', 'start_date', 'close_date', 'service_id', 'caller_id');
	$aZList =  array('title', 'start_date', 'close_date', 'servicesubcategory_id');

	$oP->DisplaySearchForm('UserRequest', $aAttSpecs, array('operation' => 'show_closed'), 'search_', false /* => not closed */);

	$oUserOrg = GetUserOrg();

	// UserRequest
	$oSearch = $oP->PostedParamsToFilter('UserRequest', $aAttSpecs, 'search_');
	if(is_null($oSearch))
	{
		$oSearch = new DBObjectSearch('UserRequest');
	}
	$oSearch->AddCondition('org_id', $oUserOrg->GetKey());
	$oSearch->AddCondition('status', 'closed');
	$iUser = UserRights::GetContactId();
	if ($iUser > 0)
	{
		$oSearch->AddCondition('caller_id', $iUser);
	}
	$oSet1 = new CMDBObjectSet($oSearch);
	$oP->add("<p>\n");
	$oP->add("<h1>".Dict::S('Portal:ClosedRequests')."</h1>\n");
	$oP->DisplaySet($oSet1, $aZList, Dict::S('Portal:NoClosedRequest'));
	$oP->add("</p>\n");
}


/**
 * Display an object - to be customized
 * @param WebPage $oP The current web page
 * @param Object $oObj Any kind of object
 * @param Object $oUserOrg The organization of the logged in user 
 * @return void
 */
function DisplayObject($oP, $oObj, $oUserOrg)
{
	switch(get_class($oObj))
	{
		case 'UserRequest':
		ShowDetailsRequest($oP, $oObj);
		break;

		default:
		throw new Exception("The class ".get_class($oObj)." is not handled through the portal");
	}
}

/**
 * Displays the details of a request
 * @param WebPage $oP The current web page
 * @param Object $oObj The target object
 * @return void
 */
function ShowDetailsRequest(WebPage $oP, $oObj)
{
	$sClass = get_class($oObj);

	$bIsEscalateButton = false;
	$bIsCloseButton = false;
	$bEditAttachments = false;
	switch($oObj->GetState())
	{
		case 'new':
		case 'assigned':
		case 'frozen':
		$aEditAtt = array(
			'ticket_log' => '????'
		);
		$bEditAttachments = true;
		// disabled - $bIsEscalateButton = true;
		break;

		case 'escalated_tto':
		case 'escalated_ttr':
		$aEditAtt = array(
			'ticket_log' => '????'
		);
		$bEditAttachments = true;
		break;

		case 'resolved':
		$aEditAtt = array(
			// non, read-only dans cet etat - 'ticket_log' => '????',
			'user_satisfaction' => '????',
			'user_commment' => '????',
		);
		$bIsCloseButton = true;
		break;

		case 'closed':
		case 'closure_requested':
		default:
		$aEditAtt = array();
		break;
	}

// REFACTORISER LA MISE EN FORME
	$oP->add("<h1 id=\"title_request_details\">".$oObj->GetIcon()."&nbsp;".Dict::Format('Portal:TitleRequestDetailsFor_Request', $oObj->GetName())."</h1>\n");

	switch($sClass)
	{
		case 'UserIssue':
		//$aAttList = array('ref', 'status', 'title', 'description', 'start_date', 'caller_id', 'servicesubcategory_id', 'impact', 'priority', 'agent_id', 'close_date', 'last_update', 'assignment_date', 'resolution_code', 'solution', 'origin', 'time_spent', 'respected_gtr', 'gtr_overdue', 'user_satisfaction', 'user_commment', 'freeze_reason', 'ticket_log');
		$aAttList = array('col:0'=> array('ref','caller_id','impact','perimeter','servicesubcategory_id','title'),'col:1'=> array('status','priority','start_date','resolution_date','last_update','agent_id'));
		break;

		case 'UserRequest':
		//$aAttList = array('ref', 'status', 'title', 'description', 'requesttype', 'start_date', 'caller_id', 'servicesubcategory_id', 'priority', 'agent_id', 'close_date', 'last_update', 'assignment_date', 'user_satisfaction', 'user_commment', 'freeze_reason', 'ticket_log');
		$aAttList = array('col:0'=> array('ref','caller_id','servicesubcategory_id','title'),'col:1'=> array('status','priority','start_date','resolution_date','last_update','agent_id'));
		break;

		default:
		//$aAttList = array('ref');
		array('col:0'=> array('ref','service_id','servicesubcategory_id','title'),'col:1'=> array('status','start_date'));
		break;
	}

	// Remove the edited attribute from the shown attributes
	//
	foreach($aEditAtt as $sAttCode => $foo)
	{
		foreach($aAttList as $col => $aColumn)
		{
			if (in_array($sAttCode, $aColumn))
			{
				if(($index = array_search($sAttCode, $aColumn)) !== false)
				{
					unset($aAttList[$col][$index]);
				}
			}
		}
	}

	$oP->add("<div class=\"wizContainer\" id=\"form_commment_request\">\n");
	$oP->WizardFormStart('request_form', null);

	$oP->add('<div id="request_details">');
	$oP->add('<table id="request_details_table">');

	$oP->add('<tr>');
	$oP->add('<td style="vertical-align:top;">');
	$oP->DisplayObjectDetails($oObj, $aAttList['col:0']);
	$oP->add('</td>');
	$oP->add('<td style="vertical-align:top;">');
	$oP->DisplayObjectDetails($oObj, $aAttList['col:1']);
	$oP->add('</td>');
	$oP->add('</tr>');

// REFACTORISER
	$oP->add('<tr>');
	$oP->add('<td colspan="2" style="vertical-align:top;">');
	$oAttPlugin = new AttachmentPlugIn();
	if ($bEditAttachments)
	{
		$oAttPlugin->EnableDelete(false);
		$oAttPlugin->OnDisplayRelations($oObj, $oP, true /* edit */);
	}
	else
	{
		$oAttPlugin->OnDisplayRelations($oObj, $oP, false /* read */);
	}
	$oP->add('</td>');
	$oP->add('</tr>');

	if (count($aEditAtt) > 0)
	{
		$oP->add('<tr>');
		$oP->add('<td colspan="2" style="vertical-align:top;">');

		//$oP->add("<form action=\"../portal/index.php\" id=\"request_form\" method=\"post\">\n");
		//$oP->add('<table id=""><tr><td style="vertical-align:top;">');
		//$oP->add("<h1 id=\"title_request_details\">".Dict::Format('Portal:CommentsFor_Request', $oObj->GetName())."</h1>\n");
		$oP->add("<input type=\"hidden\" name=\"class\" value=\"$sClass\">");
		$oP->add("<input type=\"hidden\" name=\"id\" value=\"".$oObj->GetKey()."\">");
		$oP->add("<input type=\"hidden\" name=\"operation\" value=\"update_request\">");
		$oP->add("<input type=\"hidden\" id=\"stimulus_to_apply\" name=\"apply_stimulus\" value=\"\">\n");
		$oP->add_script(
<<<EOF
		function SetStimulusToApply(sStimulusCode)
		{
			$('#stimulus_to_apply').val(sStimulusCode);
		}
EOF
);
		$aEditFields = array(); // Intermediate array to avoid code duplication while splitting btw ticket_log and the rest
		foreach($aEditAtt as $sAttCode => $foo)
		{
			$sValue = $oObj->Get($sAttCode);
			$sDisplayValue = $oObj->GetEditValue($sAttCode);
			$aArgs = array('this' => $oObj, 'formPrefix' => '');
			$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
			$sInputId = 'input_'.$sAttCode;
			$sHTMLValue = "<span id=\"field_{$sInputId}\">".cmdbAbstractObject::GetFormElementForField($oP, $sClass, $sAttCode, $oAttDef, $sValue, $sDisplayValue, $sInputId, '', 0 /*$iFlags*/, $aArgs).'</span>';

			$aEditFields[$sAttCode] = array(
				'label' => MetaModel::GetLabel($sClass, $sAttCode),
				'value' => $sHTMLValue
			);
		}
		foreach($aEditFields as $sAttCode => $aFieldSpec)
		{
			if ($sAttCode == 'ticket_log')
			{
				// Skip, the public log will be displayed below the buttons
				continue;
			}
			$oP->add("<div class=\"edit_item\">");
			$oP->add('<h1>'.$aFieldSpec['label'].'</h1>');
			$oP->add($aFieldSpec['value']);
			$oP->add('</div>');
		}
	//	$oP->p('<textarea id="user_request_commment" name="commment"></textarea>');
		if($bIsCloseButton)
		{
			$sStimulusCode = 'ev_close';
			$oP->p('<input type="submit" onClick="SetStimulusToApply(\''.$sStimulusCode.'\');" value="'.Dict::S('Portal:Button:CloseTicket').'">');
		}
		else
		{
			$oP->p('<input type="submit" value="'.Dict::S('Portal:Button:UpdateRequest').'">');
		}
		
		if ($bIsEscalateButton)
		{
			$sStimulusCode = 'ev_timeout';
			$oP->p('<input type="submit" onClick="SetStimulusToApply(\''.$sStimulusCode.'\');" value="'.Dict::S('Portal:ButtonEscalate').'">');
		}

		$oP->add('</td>');
		$oP->add('</tr>');
	}

	$oP->add('<tr>');
	$oP->add('<td colspan="2" style="vertical-align:top;">');
	if (isset($aEditFields['ticket_log']))
	{
		$oP->add("<div class=\"edit_item\">");
		$oP->add('<h1>'.$aEditFields['ticket_log']['label'].'</h1>');
		$oP->add($aEditFields['ticket_log']['value']);
		$oP->add('</div>');
	}
	else
	{
		$oP->add('<h1>'.MetaModel::GetLabel($sClass, 'ticket_log').'</h1>');
		$oP->add($oObj->GetAsHTML('ticket_log'));
	}
	$oP->add('</td>');
	$oP->add('</tr>');

	$oP->add('</table>');
	$oP->add('</div>');

	$oP->WizardFormEnd();
	$oP->add('</div>');
}

/**
 * Get The organization of the current user (i.e. the organization of its contact)
 * @param WebPage $oP The current page, for errors output
 * @return Organization The user's org or null in case of problem...
 */
function GetUserOrg()
{
	$oOrg = null;
	$iContactId = UserRights::GetContactId();
	$oContact = MetaModel::GetObject('Contact', $iContactId, false); // false => Can fail
	if (is_object($oContact))
	{
		$oOrg = MetaModel::GetObject('Organization', $oContact->Get('org_id'), false); // false => can fail
	}
	else
	{
		throw new Exception(Dict::S('Portal:ErrorNoContactForThisUser'));
	}
	return $oOrg;
}

///////////////////////////////////////////////////////////////////////////////
//
// Main program
//
///////////////////////////////////////////////////////////////////////////////

try
{
	require_once(APPROOT.'/application/startup.inc.php');
	require_once(APPROOT.'/application/portalwebpage.class.inc.php');
	$oAppContext = new ApplicationContext();
	$sOperation = utils::ReadParam('operation', '');
	
	require_once(APPROOT.'/application/loginwebpage.class.inc.php');
	LoginWebPage::DoLogin(false /* bMustBeAdmin */, true /* IsAllowedToPortalUsers */); // Check user rights and prompt if needed

   ApplicationContext::SetUrlMakerClass('MyPortalURLMaker');

	$oUserOrg = GetUserOrg();

	$sCode = $oUserOrg->Get('code');
	$sAlternateStylesheet = '';
	if (@file_exists("./$sCode/portal.css"))
	{
		$sAlternateStylesheet = "$sCode";
	}

	$oP = new PortalWebPage(Dict::S('Portal:Title'), $sAlternateStylesheet);

   $oP->EnableDisconnectButton(true);
   $oP->SetWelcomeMessage(Dict::Format('Portal:WelcomeUserOrg', UserRights::GetUserFriendlyName(), $oUserOrg->GetName()));

	if (is_object($oUserOrg))
	{
		switch($sOperation)
		{
			case 'show_closed':
			DisplayMainMenu($oP);
			ShowClosedTickets($oP);
			break;
					
			case 'create_request':
			DisplayMainMenu($oP);
			CreateRequest($oP, $oUserOrg);
			break;
					
			case 'details':
			DisplayMainMenu($oP);
			$oObj = $oP->FindObjectFromArgs(array('UserRequest'));
			DisplayObject($oP, $oObj, $oUserOrg);
			break;
			
			case 'update_request':
			DisplayMainMenu($oP);
			$oObj = $oP->FindObjectFromArgs(array('UserRequest'));
			switch(get_class($oObj))
			{
			case 'UserRequest':
				$aAttList = array('ticket_log', 'user_satisfaction', 'user_commment');
				break;

			default:
				throw new Exception("Implementation issue: unexpected class '".get_class($oObj)."'");
			}
			try
			{
				$oP->DoUpdateObjectFromPostedForm($oObj, $aAttList);
			}
			catch(TransactionException $e)
			{
				$oP->add("<h1>".Dict::S('UI:Error:ObjectAlreadyUpdated')."</h1>\n");
			}
			DisplayObject($oP, $oObj, $oUserOrg);
			break;

			case 'show_ongoing':
			default:
			DisplayMainMenu($oP);
			ShowOngoingTickets($oP);
		} 
	}
	$oP->output();
}
catch(CoreException $e)
{
	require_once(APPROOT.'/setup/setuppage.class.inc.php');
	$oP = new SetupWebPage(Dict::S('UI:PageTitle:FatalError'));
	$oP->add("<h1>".Dict::S('UI:FatalErrorMessage')."</h1>\n");	
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
