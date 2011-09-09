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

/**
 * Get the list of parameters (i.e. attribute codes) to be handled while creating a new UserRequest object
 * @return Array The list of attribute codes
 */
function GetParamsList()
{
	return array('org_id', 'caller_id', 'service_id', 'servicesubcategory_id', 'request_type', 'title', 'description', 'impact', 'urgency', 'workgroup_id');
}

/**
 * Outputs a list of parameters as hidden field into the current page
 * (must be called when inside a form)
 * @param WebPage $oP The current web page
 * @param Array $aInteractive The list of parameters that are handled intractively and thus should not be output as hidden fields
 * @param Hash $aParameters Array name => value for the parameters
 * @return void
 */
function DumpHiddenParams($oP, $aInteractive, $aParameters)
{
	foreach($aParameters as $sAttCode => $value)
	{
		if (!in_array($sAttCode, $aInteractive))
		{
			$oP->Add("<input type=\"hidden\" name=\"attr_$sAttCode\" value=\"".htmlentities($value, ENT_QUOTES, 'UTF-8')."\">");
		}
	}
}

/**
 * Read all the parameters of the page for building a UserRequest
 * Parameters that were absent from the page's parameters are not set in the resulting hash array
 * @input string $sMethod Either get or post
 * @return Hash Array of name => value corresponding to the parameters that were passed to the page
 */
function ReadAllParams()
{
	$aParams = GetParamsList();
	$aValues = array();
	foreach($aParams as $sName)
	{
		$value = utils::ReadParam('attr_'.$sName, null, false, 'raw_data');
		if (!is_null($value))
		{
			$aValues[$sName] = $value;
		}
	}
	return $aValues;
}

/**
 * Displays the portal main menu
 * @param WebPage $oP The current web page
 * @return void
 */
function DisplayMainMenu(WebPage $oP)
{
	$oP->AddMenuButton('refresh', 'Portal:Refresh', './index.php?operation=welcome');
	$oP->AddMenuButton('create', 'Portal:CreateNewRequest', './index.php?operation=create_request');
	$oP->AddMenuButton('change_pwd', 'Portal:ChangeMyPassword', './index.php?loginop=change_pwd');

	$oP->add("<div id=\"#div_resolved_requests\">\n");
	$oP->add("<h1 id=\"#open_requests\">".Dict::S('Portal:OpenRequests')."</h1>\n");
	ListOpenRequests($oP);
	$oP->add("</div>\n");
	$oP->add("<div id=\"#div_resolved_requests\">\n");
	$oP->add("<h1 id=\"#resolved_requests\">".Dict::S('Portal:ResolvedRequests')."</h1>\n");
	ListResolvedRequests($oP);
	$oP->add("</div>\n");
}

/**
 * Displays the form to select a Service Id (among the valid ones for the specified user Organization)
 * @param WebPage $oP Web page for the form output
 * @param Organization $oUserOrg The organization of the current user
 * @return void
 */
function SelectService($oP, $oUserOrg)
{
	// Init forms parameters
	$aParameters = ReadAllParams();
	
	$oSearch = DBObjectSearch::FromOQL('SELECT Service AS s JOIN SLA AS sla ON sla.service_id=s.id JOIN lnkContractToSLA AS ln ON ln.sla_id=sla.id JOIN CustomerContract AS cc ON ln.contract_id=cc.id WHERE cc.org_id = :org_id');
	$oSet = new CMDBObjectSet($oSearch, array(), array('org_id' => $oUserOrg->GetKey()));
	$oP->add("<div class=\"wizContainer\" id=\"form_select_service\">\n");
	$oP->add("<h1 id=\"select_subcategory\">".Dict::S('Portal:SelectService')."</h1>\n");
	$oP->add("<form action=\"../portal/index.php\" id=\"request_form\" method=\"get\">\n");
	$oP->add("<table>\n");
	while($oService = $oSet->Fetch())
	{
		$id = $oService->GetKey();
		$sChecked = "";
		if (isset($aParameters['service_id']) && ($id == $aParameters['service_id']))
		{
			$sChecked = "checked";
		}
		$oP->p("<tr><td style=\"vertical-align:top\"><p><input name=\"attr_service_id\" $sChecked type=\"radio\" id=\"svc_$id\" value=\"$id\"></p></td><td style=\"vertical-align:top\"><p><b><label for=\"svc_$id\">".htmlentities($oService->GetName(), ENT_QUOTES, 'UTF-8')."</label></b></p>");
		$oP->p("<p>".htmlentities($oService->Get('description'), ENT_QUOTES, 'UTF-8')."</p></td></tr>");		
	}
	$oP->add("</table>\n");	
	DumpHiddenParams($oP, array('service_id'), $aParameters);
	$oP->add("<input type=\"hidden\" name=\"step\" value=\"1\">");
	$oP->add("<input type=\"hidden\" name=\"operation\" value=\"create_request\">");
	$oP->p("<input type=\"submit\" value=\"".Dict::S('UI:Button:Next')."\">");
	$oP->add("</form>");
	$oP->add("</div class=\"wizContainer\">\n");
	$sMessage = Dict::S('Portal:PleaseSelectOneService');
	$oP->add_ready_script(
<<<EOF
	$('#request_form').submit(function() {
		return CheckSelection('$sMessage');
	});
EOF
);
}

/**
 * Displays the form to select a Service Subcategory Id (among the valid ones for the specified user Organization)
 * and based on the page's parameter 'service_id'
 * @param WebPage $oP Web page for the form output
 * @param Organization $oUserOrg The organization of the current user
 * @return void
 */

function SelectSubService($oP, $oUserOrg)
{
	// Init forms parameters
	$aParameters = ReadAllParams();
	$iSvcId = $aParameters['service_id'];
	$iDefaultSubSvcId = isset($aParameters['servicesubcategory_id']) ? $aParameters['servicesubcategory_id'] : 0;

	$oSearch = DBObjectSearch::FromOQL('SELECT ServiceSubcategory AS ss WHERE ss.service_id = :svc_id');
	$oSet = new CMDBObjectSet($oSearch, array(), array('svc_id' => $iSvcId));
	$oService = MetaModel::GetObject('Service', $iSvcId, false);
	if (is_object($oService))
	{
		$oP->add("<div class=\"wizContainer\" id=\"form_select_servicesubcategory\">\n");
		$oP->add("<h1 id=\"select_subcategory\">".Dict::Format('Portal:SelectSubcategoryFrom_Service', htmlentities($oService->GetName(), ENT_QUOTES, 'UTF-8'))."</h1>\n");
		$oP->add("<form id=\"request_form\" method=\"get\">\n");
		$oP->add("<table>\n");
		while($oSubService = $oSet->Fetch())
		{
			$id = $oSubService->GetKey();
			$sChecked = "";
			if ($id == $iDefaultSubSvcId)
			{
				$sChecked = "checked";
			}
			$oP->p("<tr><td style=\"vertical-align:top\"><p><input name=\"attr_servicesubcategory_id\" $sChecked type=\"radio\" id=\"subsvc_$id\" value=\"$id\"></p></td><td style=\"vertical-align:top\"><p><b><label for=\"subsvc_$id\">".htmlentities($oSubService->GetName(), ENT_QUOTES, 'UTF-8')."</label></b></p>");
			$oP->p("<p>".htmlentities($oSubService->Get('description'), ENT_QUOTES, 'UTF-8')."</p></td></tr>");
		}
		$sMessage = Dict::S('Portal:PleaseSelectAServiceSubCategory');
		$oP->add_ready_script(
<<<EOF
	$('#request_form').submit(function() {
		return CheckSelection('$sMessage');
	});
EOF
);
		$oP->add("</table>\n");	
		DumpHiddenParams($oP, array('servicesubcategory_id'), $aParameters);
		$oP->add("<input type=\"hidden\" name=\"step\" value=\"2\">");
		$oP->add("<input type=\"hidden\" name=\"operation\" value=\"create_request\">");
		$oP->p("<input type=\"submit\" value=\"".Dict::S('UI:Button:Back')."\"  onClick=\"$('#request_form input[name=step]').val(0); $('#request_form').unbind('submit');\"\">&nbsp;<input type=\"submit\" value=\"".Dict::S('UI:Button:Next')."\">");
		$oP->add("</form>");
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
	$aList = array('request_type', 'title', 'description', 'impact', 'urgency', 'workgroup_id');
	$aParameters = ReadAllParams();

	$oService = MetaModel::GetObject('Service', $aParameters['service_id'], false);
	$oSubService = MetaModel::GetObject('ServiceSubcategory', $aParameters['servicesubcategory_id'], false);
	if (is_object($oService) && is_object($oSubService))
	{
		$oRequest = new UserRequest();
		$oRequest->Set('org_id', $oUserOrg->GetKey());
		$oRequest->Set('caller_id', UserRights::GetContactId());
		$oRequest->Set('service_id', $aParameters['service_id']);
		$oRequest->Set('servicesubcategory_id', $aParameters['servicesubcategory_id']);
		
		$oAttDef = MetaModel::GetAttributeDef('UserRequest', 'service_id');
		$aDetails[] = array('label' => '<span>'.$oAttDef->GetLabel().'</span>', 'value' => htmlentities($oService->GetName(), ENT_QUOTES, 'UTF-8'));
		$oAttDef = MetaModel::GetAttributeDef('UserRequest', 'servicesubcategory_id');
		$aDetails[] = array('label' => '<span>'.$oAttDef->GetLabel().'</span>', 'value' => htmlentities($oSubService->GetName(), ENT_QUOTES, 'UTF-8'));
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
			$aDetails[] = array('label' => '<span>'.$oAttDef->GetLabel().'</span>', 'value' => $sValue);
		}
		if (!class_exists('AttachmentPlugIn'))
		{
			// the Attachement plug-ins is not installed, do it the old way
			$aDetails[] = array('label' => '<span>'.Dict::S('Portal:Attachments').'</span>', 'value' => '&nbsp;');
			$aDetails[] = array('label' => '&nbsp;', 'value' => '<div id="attachments"></div><p><button type="button" onClick="AddAttachment();">'.Dict::S('Portal:AddAttachment').'</button/></p>');
		}
		$oP->add_linked_script("../js/json.js");
		$oP->add_linked_script("../js/forms-json-utils.js");
		$oP->add_linked_script("../js/wizardhelper.js");
		$oP->add_linked_script("../js/wizard.utils.js");
		$oP->add_linked_script("../js/linkswidget.js");
		$oP->add_linked_script("../js/extkeywidget.js");
		$oP->add_linked_script("../js/jquery.blockUI.js");
		$oP->add("<div class=\"wizContainer\" id=\"form_request_description\">\n");
		$oP->add("<h1 id=\"title_request_form\">".Dict::S('Portal:DescriptionOfTheRequest')."</h1>\n");
		$oP->add("<form action=\"../portal/index.php\" enctype=\"multipart/form-data\" id=\"request_form\" method=\"post\">\n");
		$oP->add("<table><tr><td>\n");
		$oP->details($aDetails);

		$sTransactionId = utils::GetNewTransactionId();
		$oP->SetTransactionId($sTransactionId); // Must be set before calling the plug-in
		if (class_exists('AttachmentPlugIn'))
		{
			$oAttPlugin = new AttachmentPlugIn();
			// depending on the plug-in's configuration, the attachments are displayed either in the 'properties' or in the 'relations'
			// in the portal, both are handled the same way... it does not matter
			$oAttPlugin->OnDisplayProperties($oRequest, $oP, true /* edit */);
			$oAttPlugin->OnDisplayRelations($oRequest, $oP, true /* edit */);
		}

		$oP->add("</td></tr></table>\n");
		DumpHiddenParams($oP, $aList, $aParameters);
		$oP->add("<input type=\"hidden\" name=\"step\" value=\"3\">");
		$oP->add("<input type=\"hidden\" name=\"operation\" value=\"create_request\">");
		$oP->add("<input type=\"hidden\" name=\"transaction_id\" value=\"$sTransactionId\">\n");
		$oP->p("<input type=\"submit\" value=\"".Dict::S('UI:Button:Back')."\" onClick=\"$('#request_form input[name=step]').val(1); $('#request_form').unbind('submit');\">&nbsp;<input type=\"submit\" value=\"".Dict::S('UI:Button:Finish')."\">");
		$oP->add("</form>");
		$oP->add("</div>\n");
		$iFieldsCount = count($aFieldsMap);
		$sJsonFieldsMap = json_encode($aFieldsMap);
		$oP->add_ready_script(
<<<EOF
		// Create the object once at the beginning of the page... no state specified => new
		var oWizardHelper = new WizardHelper('UserRequest', '');
		oWizardHelper.SetFieldsMap($sJsonFieldsMap);
		oWizardHelper.SetFieldsCount($iFieldsCount);

		// Starts the validation when the page is ready
		CheckFields('request_form', false);
		$('#request_form').submit( function() {
			return OnSubmit('request_form');
		});
		$(window).unload(function() { OnUnload('$sTransactionId') } );
EOF
);
		$sBtnLabel = Dict::S('Portal:RemoveAttachment');
		$oP->add_script(
<<<EOF
		var index = 0;
		function AddAttachment()
		{
			$('#attachments').append('<p id="attachment_'+index+'"><input type="file" name="attachement_'+index+'"/>&nbsp;<button type="button" onClick="RemoveAttachment('+index+')">{$sBtnLabel}</button/></p>');
			index++;
		}
		function RemoveAttachment(id_attachment)
		{
			$('#attachment_'+id_attachment).remove();
		}		
EOF
);
	}
	else
	{
		// User not authorized to use this service ?
		DisplayMainMenu($oP);
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
	$aParameters = ReadAllParams();
	$sTransactionId = utils::ReadPostedParam('transaction_id', '');
	if (!utils::IsTransactionValid($sTransactionId))
	{
		$oP->add("<h1>".Dict::S('UI:Error:ObjectAlreadyCreated')."</h1>\n");
		DisplayMainMenu($oP);
		return;
	}
		
	// Validate the parameters
	// 1) Service
	$oSearch = DBObjectSearch::FromOQL('SELECT Service AS s JOIN SLA AS sla ON sla.service_id=s.id JOIN lnkContractToSLA AS ln ON ln.sla_id=sla.id JOIN CustomerContract AS cc ON ln.contract_id=cc.id WHERE cc.org_id = :org_id AND s.id = :svc_id');
	$oSet = new CMDBObjectSet($oSearch, array(), array('org_id' => $oUserOrg->GetKey(), 'svc_id' => $aParameters['service_id']));
	if ($oSet->Count() != 1)
	{
		// Invalid service for the current user !
		throw new Exception("Invalid Service: id={$aParameters['servicesubcategory_id']} for the current user (org_id=".$oUserOrg->GetKey().").");
	}
	$oService = $oSet->Fetch();
	// 2) Service Subcategory
	$oSearch = DBObjectSearch::FromOQL('SELECT ServiceSubcategory AS sc WHERE sc.id = :subcategory_id AND sc.service_id = :svc_id');
	$oSet = new CMDBObjectSet($oSearch, array(), array('svc_id' => $aParameters['service_id'], 'subcategory_id' =>$aParameters['servicesubcategory_id'] ));
	if ($oSet->Count() != 1)
	{
		// Invalid subcategory
		throw new Exception("Invalid ServiceSubcategory: id={$aParameters['servicesubcategory_id']} for service ".$oService->GetName()."({$aParameters['service_id']})");
	}
		
	$oRequest = new UserRequest();
	$oRequest->Set('org_id', $oUserOrg->GetKey());
	$oRequest->Set('caller_id', UserRights::GetContactId());
	$aList = array('service_id', 'servicesubcategory_id', 'request_type', 'title', 'description', 'impact', 'urgency', 'workgroup_id');
	foreach($aList as $sAttCode)
	{
		$oRequest->Set($sAttCode, $aParameters[$sAttCode]);	
	}
	
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
		
		if (class_exists('AttachmentPlugIn'))
		{
			// New way: use the plug-in
			$oAttPlugin = new AttachmentPlugIn();
			$oAttPlugin->OnFormSubmit($oRequest);
		}
		else
		{
			// Old way: create linked documents
			$index = 0;
			foreach($_FILES as $sName => $void)
			{
				$oAttachment = utils::ReadPostedDocument($sName);
				if (!$oAttachment->IsEmpty())
				{
					$index++;
					// Create a document and attach it to the created ticket
					$oDoc = new FileDoc();
					$oDoc->Set('name', Dict::Format('Portal:Attachment_No_To_Ticket_Name', $index, $oRequest->GetName(), $oAttachment->GetFileName()));
					$oDoc->Set('org_id', $oUserOrg->GetKey());
					$oDoc->Set('description', $oAttachment->GetFileName());
					$oDoc->Set('contents', $oAttachment);
					$oDoc->DBInsertTracked($oMyChange);
					// Link the document to the ticket
					$oLink = new lnkTicketToDoc();
					$oLink->Set('ticket_id', $oRequest->GetKey());
					$oLink->Set('document_id', $oDoc->GetKey());
					$oLink->DBInsertTracked($oMyChange);
				}
			}		
		}
		DisplayMainMenu($oP);
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
	$iStep = utils::ReadParam('step', 0);
	
	switch($iStep)
	{
		case 0:
		default:
		$oP->AddMenuButton('cancel', 'UI:Button:Cancel', './index.php?operation=welcome');
		SelectService($oP, $oUserOrg);
		break;
		
		case 1:
		$oP->AddMenuButton('cancel', 'UI:Button:Cancel', './index.php?operation=welcome');
		SelectSubService($oP, $oUserOrg);
		break;
		
		case 2:
		$oP->AddMenuButton('cancel', 'UI:Button:Cancel', './index.php?operation=welcome');
			RequestCreationForm($oP, $oUserOrg);
		break;

		case 3:
		DoCreateRequest($oP, $oUserOrg);
		break;
	}
}

/**
 * Displays the value of the given field, in HTML, without any hyperlink to other objects
 * @param DBObject $oObj The object to use
 * @param string $sAttCode Code of the attribute to display
 * @return string HTML text representing the value of this field
 */
function GetFieldAsHtml($oObj, $sAttCode)
{
	$sValue = '';
	$oAttDef = MetaModel::GetAttributeDef(get_class($oObj), $sAttCode);
	if ($oAttDef->IsExternalKey())
	{
		// Special processing for external keys: don't display any hyperlink
		$oTargetObj = MetaModel::GetObject($oAttDef->GetTargetClass(), $oObj->Get($sAttCode), false);
		if (is_object($oTargetObj))
		{
			$sValue = $oTargetObj->GetName();						
		}
		else
		{
			$sValue = Dict::S('UI:UndefinedObject');
		}
	}
	else
	{
		$sValue = $oObj->GetAsHTML($sAttCode);
	}
	return $sValue;	
}

/**
 * Displays a list of objects, without any hyperlink (except for the object's details)
 * @param WebPage $oP The web page for the output
 * @param DBObjectSet $oSet The set of objects to display
 * @param Array $aZList The ZList (list of field codes) to use for the tabular display
 * @return string The HTML text representing the list
 */
 function DisplaySet($oP, $oSet, $aZList)
 {
	if ($oSet->Count() > 0)
	{
		$aAttribs = array();
		$aValues = array();
		$oAttDef = MetaModel::GetAttributeDef('UserRequest', 'ref');
		$aAttribs['key'] = array('label' => $oAttDef->GetLabel(), 'description' => $oAttDef->GetDescription());
		foreach($aZList as $sAttCode)
		{
			$oAttDef = MetaModel::GetAttributeDef('UserRequest', $sAttCode);
			$aAttribs[$sAttCode] = array('label' => $oAttDef->GetLabel(), 'description' => $oAttDef->GetDescription());
		}
		while($oRequest = $oSet->Fetch())
		{
			$aRow = array();
			
			$aRow['key'] = '<a href="'.utils::GetAbsoluteUrlAppRoot().'portal/index.php?operation=details&id='.$oRequest->GetKey().'">'.$oRequest->Get('ref').'</a>';
			$sHilightClass = $oRequest->GetHilightClass();
			if ($sHilightClass != '')
			{
				$aRow['@class'] = $sHilightClass;	
			}
			foreach($aZList as $sAttCode)
			{
				$aRow[$sAttCode] = GetFieldAsHtml($oRequest, $sAttCode);
			}
			$aValues[$oRequest->GetKey()] = $aRow;
		}
		$oP->Table($aAttribs, $aValues);
		// Temprory until we merge re-use the paginated tables:
		$oP->add_ready_script(
<<<EOF
		$('table.listResults').tableHover().tablesorter( { widgets: ['myZebra', 'truncatedList']} );
EOF
		);
	}
	else
	{
		$oP->add(Dict::S('Portal:NoOpenRequest'));
	}
}

/**
 * Lists all the currently opened User Requests for the current user
 * @param WebPage $oP The current web page
 * @return void
 */
function ListOpenRequests(WebPage $oP)
{
	$iContactId = UserRights::GetContactId();
	$oContact = MetaModel::GetObject('Contact', $iContactId, false); // false => Can fail
	if (is_object($oContact))
	{
		$sOQL = 'SELECT UserRequest WHERE caller_id = :contact_id AND status NOT IN ("resolved", "closed")';
		$oSearch = DBObjectSearch::FromOQL($sOQL);
		$oSet = new CMDBObjectSet($oSearch, array(), array('contact_id' => $iContactId));
		$aZList =  array('title', 'start_date', 'status', 'service_id', 'priority', 'workgroup_id', 'agent_id');
		DisplaySet($oP, $oSet, $aZList);
	}	
}

/**
 * Lists all the currently Resolved (not "Closed")User Requests for the current user
 * @param WebPage $oP The current web page
 * @return void
 */
function ListResolvedRequests(WebPage $oP)
{
	$iContactId = UserRights::GetContactId();
	$oContact = MetaModel::GetObject('Contact', $iContactId, false); // false => Can fail
	if (is_object($oContact))
	{
		$sOQL = 'SELECT UserRequest WHERE caller_id = :contact_id AND status ="resolved"';
		$oSearch = DBObjectSearch::FromOQL($sOQL);
		$oSet = new CMDBObjectSet($oSearch, array(), array('contact_id' => $iContactId));
		$aZList =  array('title', 'start_date', 'status', 'service_id', 'priority', 'workgroup_id', 'agent_id');
		DisplaySet($oP, $oSet, $aZList);
	}	
}
/**
 * Displays the details of the specified UserRequest object
 * @param WebPage $oP The current web page for the output
 * @param UserRequest $oRequest The object to display
 * @return void
 */
function DisplayRequestDetails($oP, UserRequest $oRequest, $bEditMode = true)
{
	// Identical to the standard 'details' ZList of UserRequest, except that the field 'org_id' has been removed
	$aList = array(
		'col:col1' => array(
			'fieldset:Ticket:baseinfo' => array('ref','title','request_type','status','priority','service_id','servicesubcategory_id','product' ),
			'fieldset:Ticket:moreinfo' => array('impact','urgency','description','resolution_code', 'solution', 'user_satisfaction', 'user_commment','freeze_reason'),
			),
		'col:col2' => array(
			'fieldset:Ticket:date' => array('start_date','last_update','assignment_date','tto_escalation_deadline', 'ttr_escalation_deadline', 'close_date',  'closure_deadline',),
			'fieldset:Ticket:contact' => array('caller_id','workgroup_id','agent_id',),
			'fieldset:Ticket:relation' => array('related_problem_id', 'related_change_id'),
			)

	);

	// Similar to CMDBAbstractObject::GetBareProperties except that: multiple tabs are not supported and GetFieldAsHtml is customized
	// in order to NOT display any hyperlink
	$aDetails = array();
	$oP->add('<div id="request_details" class="ui-widget-content">');

	$aDetailsStruct = CMDBAbstractObject::ProcessZlist($aList, array('UI:PropertiesTab' => array()), 'UI:PropertiesTab', 'col1', '');
	// Compute the list of properties to display, first the attributes in the 'details' list, then 
	// all the remaining attributes that are not external fields
	$aDetails = array();
	$iInputId = 0;
	foreach($aDetailsStruct as $sTab => $aCols )
	{
		$aDetails[$sTab] = array();
		ksort($aCols);
		$oP->add('<table style="vertical-align:top"><tr>');
		foreach($aCols as $sColIndex => $aFieldsets)
		{
			$oP->add('<td style="vertical-align:top">');
			//$aDetails[$sTab][$sColIndex] = array();
			$sLabel = '';
			$sPreviousLabel = '';
			$aDetails[$sTab][$sColIndex] = array();
			foreach($aFieldsets as $sFieldsetName => $aFields)
			{
				if (!empty($sFieldsetName) && ($sFieldsetName[0] != '_'))
				{
					$sLabel = $sFieldsetName;
				}
				else
				{
					$sLabel = '';
				}
				if ($sLabel != $sPreviousLabel)
				{
					if (!empty($sPreviousLabel))
					{
						$oP->add('<fieldset>');
						$oP->add('<legend>'.Dict::S($sPreviousLabel).'</legend>');
					}
					$oP->Details($aDetails[$sTab][$sColIndex]);
					if (!empty($sPreviousLabel))
					{
						$oP->add('</fieldset>');
					}
					$aDetails[$sTab][$sColIndex] = array();
					$sPreviousLabel = $sLabel;
				}
				foreach($aFields as $sAttCode)
				{
					if (MetaModel::IsValidAttCode(get_class($oRequest), $sAttCode))
					{
						$iFlags = $oRequest->GetAttributeFlags($sAttCode);
						if ( ($iFlags & OPT_ATT_HIDDEN) == 0)
						{
							// The field is visible, add it to the current column
							$val = GetFieldAsHtml($oRequest, $sAttCode);
							$aDetails[$sTab][$sColIndex][] = array( 'label' => '<span title="'.MetaModel::GetDescription('UserRequest', $sAttCode).'">'.MetaModel::GetLabel('UserRequest', $sAttCode).'</span>', 'value' => $val);
							$iInputId++;
						}
					}				
				}
			}
			if (!empty($sPreviousLabel))
			{
				$oP->add('<fieldset>');
				$oP->add('<legend>'.Dict::S($sFieldsetName).'</legend>');
			}
			$oP->Details($aDetails[$sTab][$sColIndex]);
			if (!empty($sPreviousLabel))
			{
				$oP->add('</fieldset>');
			}
			$oP->add('</td>');
		}
		$oP->add('</tr></table>');
	}

	if (!class_exists('AttachmentPlugIn'))
	{
		// Attachments, the old way
		$sOQL = 'SELECT FileDoc AS Doc JOIN lnkTicketToDoc AS L ON L.document_id = Doc.id WHERE L.ticket_id = :request_id';
		$oSearch = DBObjectSearch::FromOQL($sOQL);
		$oSet = new CMDBObjectSet($oSearch, array(), array('request_id' => $oRequest->GetKey()));
		$aDetails = array();
		if ($oSet->Count() > 0)
		{
			$sAttachements = '<table>';
			while($oDoc = $oSet->Fetch())
			{
				$sAttachements .= '<tr><td>'.$oDoc->GetAsHtml('contents').'</td></tr>';
			}
			$sAttachements .= '</table>';
			$aDetails[] = array('label' => Dict::S('Portal:Attachments'), 'value' => $sAttachements);
		}
		$oP->Details($aDetails);
	}
	
	// Case log... editable so that users can post comments
	if ($bEditMode)
	{
		$oP->add("<form action=\"../portal/index.php\" id=\"request_form\" method=\"post\">\n");
		$oP->add("<input type=\"hidden\" name=\"id\" value=\"".$oRequest->GetKey()."\">");
		$oP->add("<input type=\"hidden\" name=\"step\" value=\"3\">");
		$sTransactionId = utils::GetNewTransactionId();
		$oP->SetTransactionId($sTransactionId);
		$oP->add("<input type=\"hidden\" name=\"transaction_id\" value=\"$sTransactionId\">\n");
		$oP->add("<input type=\"hidden\" name=\"operation\" value=\"details\">");
		$oP->add('<fieldset id="request_details_log"><legend>'.MetaModel::GetLabel('UserRequest', 'ticket_log').'</legend>');
		$oAttDef = MetaModel::GetAttributeDef(get_class($oRequest), 'ticket_log');
		$oValue = $oRequest->Get('ticket_log');
		$oP->add($oRequest->GetFormElementForField($oP, get_class($oRequest), 'ticket_log', $oAttDef, $oValue, $sDisplayValue = '', $iId = 'att_ticket_log'));
		//$oP->add(GetFieldAsHtml($oRequest, 'ticket_log'));
		$oP->add('</fieldset>');
		if (class_exists('AttachmentPlugIn'))
		{
			$oAttPlugin = new AttachmentPlugIn();
			// depending on the plug-in's configuration, the attachments are displayed either in the 'properties' or in the 'relations'
			// in the portal, both are handled the same way... it does not matter
			$oAttPlugin->OnDisplayProperties($oRequest, $oP, true /* edit */);
			$oAttPlugin->OnDisplayRelations($oRequest, $oP, true /* edit */);
		}
		$oP->p('<input type="submit" value="'.Dict::S('UI:Button:Ok').'">');
		$oP->add('</form>');
		$oP->add_ready_script(
<<<EOF
		$('#request_form').submit( function() { return OnSubmit('request_form'); });
		$(window).unload(function() { OnUnload('$sTransactionId') } );
EOF
		);
	}
	else
	{
		$oP->add('<fieldset id="request_details_log"><legend>'.MetaModel::GetLabel('UserRequest', 'ticket_log').'</legend>');
		$oP->add(GetFieldAsHtml($oRequest, 'ticket_log'));
		$oP->add('</fieldset>');
		if (class_exists('AttachmentPlugIn'))
		{
			$oAttPlugin = new AttachmentPlugIn();
			// depending on the plug-in's configuration, the attachments are displayed either in the 'properties' or in the 'relations'
			// in the portal, both are handled the same way... it does not matter
			$oAttPlugin->OnDisplayProperties($oRequest, $oP, false /* edit */);
			$oAttPlugin->OnDisplayRelations($oRequest, $oP, false /* edit */);
		}
	}
	$oP->add('</div>');
}

/**
 * Displays a form for the user to provide feedback about a 'resolved' UserRequest and then close the request
 * @param WebPage $oP The current web page
 * @param UserRequest $oRequest The object to display
 * @return void
 */
function DisplayResolvedRequestForm($oP, UserRequest $oRequest)
{
	$oP->add("<div class=\"wizContainer\" id=\"form_close_request\">\n");
	$oP->add("<form action=\"../portal/index.php\" id=\"request_form\" method=\"post\">\n");
	$aArgs = array('this' => $oRequest);
	$sClass = get_class($oRequest);

	$aDetails = array();
	$aTargetStates = MetaModel::EnumStates($sClass);
	$aTargetState = $aTargetStates['closed'];
	$aExpectedAttributes = $aTargetState['attribute_list'];
	$iFieldIndex = 0;
	
	foreach($aExpectedAttributes as $sAttCode => $iExpectCode)
	{
		// Prompt for an attribute if
		// - the attribute must be changed or must be displayed to the user for confirmation
		// - or the field is mandatory and currently empty
		if ( ($iExpectCode & (OPT_ATT_MUSTCHANGE | OPT_ATT_MUSTPROMPT)) ||
			 (($iExpectCode & OPT_ATT_MANDATORY) && ($oRequest->Get($sAttCode) == '')) ) 
		{
			$aAttributesDef = MetaModel::ListAttributeDefs($sClass);
			$oAttDef = $aAttributesDef[$sAttCode];
			$aArgs = array('this' => $oRequest);
			$sHTMLValue = cmdbAbstractObject::GetFormElementForField($oP, $sClass, $sAttCode, $oAttDef, $oRequest->Get($sAttCode), $oRequest->GetEditValue($sAttCode), 'att_'.$iFieldIndex, '', $iExpectCode, $aArgs);
			$aDetails[] = array('label' => $oAttDef->GetLabel(), 'value' => "<span id=\"field_att_$iFieldIndex\">$sHTMLValue</span>");
			$aFieldsMap[$sAttCode] = 'att_'.$iFieldIndex;
			$iFieldIndex++;
		}
	}
	$aStimuli = MetaModel::EnumStimuli($sClass);
	$oP->add("<h1>".Dict::S('Portal:EnterYourCommentsOnTicket')."</h1>");
	$oP->details($aDetails);
	$oP->add("<input type=\"hidden\" name=\"id\" value=\"".$oRequest->GetKey()."\">");
	$oP->add("<input type=\"hidden\" name=\"step\" value=\"2\">");
	$oP->add("<input type=\"hidden\" name=\"transaction_id\" value=\"".utils::GetNewTransactionId()."\">\n");
	$oP->add("<input type=\"hidden\" name=\"operation\" value=\"details\">");
	$oP->p("<input type=\"submit\" value=\"".Dict::S('Portal:Button:CloseTicket')."\">");
	$oP->add("</form>");
	$oP->add("</div>\n");
	$oP->add("<h1 id=\"title_request_details\">".Dict::Format('Portal:TitleRequestDetailsFor_Request', $oRequest->GetName())."</h1>\n");
	DisplayRequestDetails($oP, $oRequest, false /* bEditMode */);

	$oP->add_ready_script(
<<<EOF
		// Starts the validation when the page is ready
		CheckFields('request_form', false);
		$('#request_form').submit( function() {
			return OnSubmit('request_form');
		});
EOF
);
}

/**
 * Actually close the request and saves the user's feedback
 * @param WebPage $oP The current web page
 * @param UserRequest $oRequest The object to close
 * @return void
 */
function DoCloseRequest($oP, UserRequest $oRequest)
{
	$sTransactionId = utils::ReadPostedParam('transaction_id', '');
	if (!utils::IsTransactionValid($sTransactionId))
	{
		$oP->add("<h1>".Dict::S('UI:Error:ObjectAlreadyCreated')."</h1>\n");
		DisplayMainMenu($oP);
		return;
	}
	
	$sClass = get_class($oRequest);
	$aDetails = array();
	$aTargetStates = MetaModel::EnumStates($sClass);
	$aTargetState = $aTargetStates['closed'];
	$aExpectedAttributes = $aTargetState['attribute_list'];
	$iFieldIndex = 0;
	
	foreach($aExpectedAttributes as $sAttCode => $iExpectCode)
	{
		// Prompt for an attribute if
		// - the attribute must be changed or must be displayed to the user for confirmation
		// - or the field is mandatory and currently empty
		if ( ($iExpectCode & (OPT_ATT_MUSTCHANGE | OPT_ATT_MUSTPROMPT)) ||
			 (($iExpectCode & OPT_ATT_MANDATORY) && ($oRequest->Get($sAttCode) == '')) ) 
		{
			$value = utils::ReadPostedParam('attr_'.$sAttCode, null, 'raw_data');
			if (!is_null($value))
			{
				$oRequest->Set($sAttCode, $value);
			}
		}
	}
	if ($oRequest->ApplyStimulus('ev_close'))
	{
		$oMyChange = MetaModel::NewObject("CMDBChange");
		$oMyChange->Set("date", time());
		$sUserString = CMDBChange::GetCurrentUserName();
		$oMyChange->Set("userinfo", $sUserString);
		$iChangeId = $oMyChange->DBInsert();
		$oRequest->DBUpdateTracked($oMyChange);
		$oP->p("<h1>".Dict::Format('UI:Class_Object_Updated', MetaModel::GetName(get_class($oRequest)), $oRequest->GetName())."</h1>\n");
		DisplayMainMenu($oP);
	}
	else
	{
		$oP->AddMenuButton('back', 'Portal:Back', './index.php?operation=welcome');
		$oP->add('Error: cannot close the request - '.$oRequest->GetName());
	}
}

/**
 * Find the UserRequest object of the specified ID. Make sure that it the caller is the current user
 * @param integer $id The ID of the request to find
 * @return UserRequert The found object, or null in case of failure (object does not exist, user has no rights to see it...)
 */
function FindRequest($id)
{
	$oRequest = null;
	$iContactId = UserRights::GetContactId();
	$oContact = MetaModel::GetObject('Contact', $iContactId, false); // false => Can fail
	if (is_object($oContact))
	{
		$sOQL = "SELECT UserRequest WHERE caller_id = :contact_id AND id = :request_id";
		$oSearch = DBObjectSearch::FromOQL($sOQL);
		$oSet = new CMDBObjectSet($oSearch, array(), array('contact_id' => $iContactId, 'request_id' => $id));
		if ($oSet->Count() > 0)
		{
			$oRequest = $oSet->Fetch();
		}
	}
	else
	{
		$oP->AddMenuButton('back', 'Portal:Back', './index.php?operation=welcome');
		$oP->add("<p class=\"error\">".Dict::S('Portal:ErrorNoContactForThisUser')."</p>");
	}
	return $oRequest;	
}
/**
 * Displays the details of a request
 * @param WebPage $oP The current web page
 * @return void
 */
function RequestDetails(WebPage $oP, $id)
{
	$oRequest = FindRequest($id);
	if (!is_object($oRequest))
	{
		DisplayMainMenu($oP);
		return;
	}
	$iDefaultStep = 0;
	if ($oRequest->GetState() == 'resolved')
	{
		// The current ticket is in 'resolved' state, prompt to close it
		$iDefaultStep = 1;
	}

	$iStep = utils::ReadParam('step', $iDefaultStep);

	switch($iStep)
	{
		case 0:
		$oP->AddMenuButton('back', 'Portal:Back', './index.php?operation=welcome');
		$oP->add("<h1 id=\"title_request_details\">".$oRequest->GetIcon()."&nbsp;".Dict::Format('Portal:TitleRequestDetailsFor_Request', $oRequest->GetName())."</h1>\n");
		DisplayRequestDetails($oP, $oRequest);
		break;
		
		case 1:
		$oP->AddMenuButton('cancel', 'UI:Button:Cancel', './index.php?operation=welcome');
		DisplayResolvedRequestForm($oP, $oRequest);
		break;
		
		case 2:
		DoCloseRequest($oP, $oRequest);
		break;

		case 3:
		AddComment($oP, $oRequest);
		break;
		
		default:
		//  Should never happen
		DisplayMainMenu($oP);
	}
}

/**
 * Adds a comment to the specified UserRequest and displays the main menu
 * @param WebPage $oP The current web page for the output
 * @param $id ID of the object to update
 * @return void
 */
function AddComment($oP, $id)
{
	$oRequest = FindRequest($id);
	if (!is_object($oRequest))
	{
		DisplayMainMenu($oP);
		return;
	}
	$sTransactionId = utils::ReadPostedParam('transaction_id', '');
	if (!utils::IsTransactionValid($sTransactionId))
	{
		$oP->add("<h1>".Dict::S('UI:Error:ObjectAlreadyUpdated')."</h1>\n");
		DisplayMainMenu($oP);
		return;
	}
	$sComment = trim(utils::ReadPostedParam('attr_ticket_log', '', 'raw_data'));
	if (!empty($sComment))
	{
		$oRequest->Set('ticket_log', $sComment);
	}
	if (class_exists('AttachmentPlugIn'))
	{
		$oAttPlugin = new AttachmentPlugIn();
		$oAttPlugin->OnFormSubmit($oRequest, '');
	}
	if ($oRequest->IsModified())
	{
		$oMyChange = MetaModel::NewObject("CMDBChange");
		$oMyChange->Set("date", time());
		$sUserString = CMDBChange::GetCurrentUserName();
		$oMyChange->Set("userinfo", $sUserString);
		$iChangeId = $oMyChange->DBInsert();
		$oRequest->DBUpdateTracked($oMyChange);
		$oP->p("<h1>".Dict::Format('UI:Class_Object_Updated', MetaModel::GetName(get_class($oRequest)), $oRequest->GetName())."</h1>\n");
		
		// If there is any trigger for the Portal Update, then activate them
		$aClasses = MetaModel::EnumParentClasses(get_class($oRequest), ENUM_PARENT_CLASSES_ALL);
		$aClasses = CMDBSource::Quote($aClasses);
		$sOQL = "SELECT TriggerOnPortalUpdate WHERE target_class IN (".implode(',', $aClasses).")";
		$oSet = new DBObjectSet(DBObjectSearch::FromOQL($sOQL));
		while($oTrigger = $oSet->Fetch())
		{
			$oTrigger->DoActivate($oRequest->ToArgs('this'));
		}
	}
	else
	{
		$oP->p("<h1>".Dict::Format('UI:Class_Object_NotUpdated', MetaModel::GetName(get_class($oRequest)), $oRequest->GetName())."</h1>\n");
	}
	DisplayMainMenu($oP);
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

try
{
	require_once(APPROOT.'/application/startup.inc.php');
	require_once(APPROOT.'/application/portalwebpage.class.inc.php');
	$oAppContext = new ApplicationContext();
	$sOperation = utils::ReadParam('operation', '');
	
	require_once(APPROOT.'/application/loginwebpage.class.inc.php');
	LoginWebPage::DoLogin(false /* bMustBeAdmin */, true /* IsAllowedToPortalUsers */); // Check user rights and prompt if needed

	$oUserOrg = GetUserOrg();

	$sCode = $oUserOrg->Get('code');
	$sAlternateStylesheet = '';
	if (@file_exists("./$sCode/portal.css"))
	{
		$sAlternateStylesheet = "$sCode";
	}

	$oP = new PortalWebPage(Dict::S('Portal:Title'), $sAlternateStylesheet);
	$oP->add($sAlternateStylesheet);

	if (is_object($oUserOrg))
	{
		switch($sOperation)
		{
			case 'create_request':
			CreateRequest($oP, $oUserOrg);
			break;
					
			case 'details':
			$iRequestId = utils::ReadParam('id', 0);
			RequestDetails($oP, $iRequestId);
			break;
			
			case 'welcome':
			default:
			DisplayMainMenu($oP);
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
