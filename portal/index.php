<?php
// Copyright (C) 2010-2014 Combodo SARL
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
 * iTop User Portal main page
 *
 * @copyright   Copyright (C) 2010-2013 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
require_once('../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/nicewebpage.class.inc.php');
require_once(APPROOT.'/application/wizardhelper.class.inc.php');


/**
 * Helper to determine the supported types of tickets
 */
function GetTicketClasses()
{
	$aClasses = array();
	foreach (explode(',', MetaModel::GetConfig()->Get('portal_tickets')) as $sRawClass)
	{
		$sRawClass = trim($sRawClass);
		if (!MetaModel::IsValidClass($sRawClass))
		{
			throw new Exception("Class '$sRawClass' is not a valid class, please review your configuration (portal_tickets)");
		}
		if (!MetaModel::IsParentClass('Ticket', $sRawClass))
		{
			throw new Exception("Class '$sRawClass' does not inherit from Ticket, please review your configuration (portal_tickets)");
		}
		$aClasses[] = $sRawClass;
	}
	return $aClasses;
} 


/**
 * Helper to protect the portal against malicious usages
 * Throws an exception if the current user is not allowed to view the object details  
 */
function ValidateObject($oObject)
{
	if (IsPowerUser())
	{
		$sValidationDefine = 'PORTAL_'.strtoupper(get_class($oObject)).'_DISPLAY_POWERUSER_QUERY';
	}
	else
	{
		$sValidationDefine = 'PORTAL_'.strtoupper(get_class($oObject)).'_DISPLAY_QUERY';
	}
	if (defined($sValidationDefine))
	{
		$sValidationOql = constant($sValidationDefine);
		$oSearch = DBObjectSearch::FromOQL($sValidationOql);
		$oSearch->AddCondition('id', $oObject->GetKey());

		if ($iUser = UserRights::GetContactId())
		{
			$oContact = MetaModel::GetObject('Contact', $iUser);
			$aArgs = $oContact->ToArgs('contact');
		}
		else
		{
			$aArgs = array();
		}
	
		$oSet = new DBObjectSet($oSearch, array(), $aArgs);
		if ($oSet->Count() == 0)
		{
			throw new SecurityException('You are not allowed to access the object '.get_class($oObject).'::'.$oObject->GetKey());
		}
	}
}


/**
 * Helper to get the relevant constant 
 */
function GetConstant($sClass, $sName)
{
	$sConstName = 'PORTAL_'.strtoupper($sClass).'_'.$sName;
	if (defined($sConstName))
	{
		return constant($sConstName);
	}
	else
	{
		throw new Exception("Missing portal constant '$sConstName'");
	}
}

/**
 * Helper to determine the ticket class given the service subcategory
 */
function ComputeClass($iSubSvcId)
{
	$aClasses = GetTicketClasses();
	if ((PORTAL_SET_TYPE_FROM == '') || (PORTAL_TYPE_TO_CLASS == ''))
	{
		// return the first enabled class
		$sClass = reset($aClasses);
	}
	else
	{
		$oServiceSubcat = MetaModel::GetObject('ServiceSubcategory', $iSubSvcId, true, true /* allow all data*/);
		$sTicketType = $oServiceSubcat->Get(PORTAL_SET_TYPE_FROM);
		$aMapping = json_decode(PORTAL_TYPE_TO_CLASS, true);
		if (!array_key_exists($sTicketType, $aMapping))
		{
			throw new Exception("Ticket type '$sTicketType' not found in the mapping (".implode(', ', array_keys($aMapping))."). Please contact your administrator.");
		}
		$sClass = $aMapping[$sTicketType];
		if (!in_array($sClass, $aClasses))
		{
			throw new Exception("Service subcategory #$iSubSvcId has a ticket type ($sClass) that is not known by the portal, please contact your administrator.");
		}
	}
	return $sClass;
}

/**
 * Helper to limit the service categories depending on the current settings
 */
function RestrictSubcategories(&$oSearch)
{
	$aMapping = (PORTAL_TYPE_TO_CLASS == '') ? array() : json_decode(PORTAL_TYPE_TO_CLASS, true);
	foreach($aMapping as $sTicketType => $sClass)
	{
		if (!in_array($sClass, GetTicketClasses()))
		{
			// Exclude this value for the result set
			$oSearch->AddCondition(PORTAL_SET_TYPE_FROM, $sTicketType, '!=');
		}
	}
}
 

/**
 * Displays the portal main menu
 * @param WebPage $oP The current web page
 * @return void
 */
function DisplayMainMenu(WebPage $oP)
{
	$oP->AddMenuButton('showongoing', 'Portal:ShowOngoing', '../portal/index.php?operation=show_ongoing');
	$oP->AddMenuButton('newrequest', 'Portal:CreateNewRequest', '../portal/index.php?operation=create_request');
	$oP->AddMenuButton('showclosed', 'Portal:ShowClosed', '../portal/index.php?operation=show_closed');
	if (UserRights::CanChangePassword())
	{
		$oP->AddMenuButton('change_pwd', 'Portal:ChangeMyPassword', '../portal/index.php?loginop=change_pwd');
	}
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
	$aParameters = $oP->ReadAllParams(PORTAL_ALL_PARAMS.',template_id');
	
	$oSearch = DBObjectSearch::FromOQL(PORTAL_SERVICECATEGORY_QUERY);
	$oSearch->AllowAllData(); // In case the user has the rights on his org only
	$oSet = new CMDBObjectSet($oSearch, array(), array('org_id' => $oUserOrg->GetKey()));
	if ($oSet->Count() == 1)
	{
		$oService = $oSet->Fetch();
		$iSvcCategory = $oService->GetKey();
		// Only one Category, skip this step in the wizard
		SelectServiceSubCategory($oP, $oUserOrg, $iSvcCategory);
	}
	else
	{
		$oP->add("<div class=\"wizContainer\" id=\"form_select_service\">\n");
		$oP->WizardFormStart('request_wizard', 1);

		$oP->add("<h1 id=\"select_category\">".Dict::S('Portal:SelectService')."</h1>\n");
		$oP->add("<table>\n");
		while($oService = $oSet->Fetch())
		{
			$id = $oService->GetKey();
			$sChecked = "";
			if (isset($aParameters['service_id']) && ($id == $aParameters['service_id']))
			{
				$sChecked = "checked";
			}
			$oP->p("<tr><td style=\"vertical-align:top\"><p><input name=\"attr_service_id\" $sChecked type=\"radio\" id=\"service_$id\" value=\"$id\"></p></td><td style=\"vertical-align:top\"><p><b><label for=\"service_$id\">".$oService->GetName()."</label></b></p>");
			$oP->p("<p>".$oService->GetAsHTML('description')."</p></td></tr>");		
		}
		$oP->add("</table>\n");	
	
		$oP->DumpHiddenParams($aParameters, array('service_id'));
		$oP->add("<input type=\"hidden\" name=\"operation\" value=\"create_request\">");
		$oP->WizardFormButtons(BUTTON_NEXT | BUTTON_CANCEL); // NO back button since it's the first step of the Wizard
		$oP->WizardFormEnd();
		$oP->WizardCheckSelectionOnSubmit(Dict::S('Portal:PleaseSelectOneService'));
		$oP->add("</div>\n");
	}
}

/**
 * Displays the form to select a Service Subcategory Id (among the valid ones for the specified user Organization)
 * and based on the page's parameter 'service_id'
 * @param WebPage $oP Web page for the form output
 * @param Organization $oUserOrg The organization of the current user
 * @param $iSvcId Id of the selected service in case of pass-through (when there is only one service)
 * @return void
 */
function SelectServiceSubCategory($oP, $oUserOrg, $iSvcId = null)
{
	$aParameters = $oP->ReadAllParams(PORTAL_ALL_PARAMS.',template_id');
	if ($iSvcId == null)
	{
		$iSvcId = $aParameters['service_id'];
	}
	else
	{
		$aParameters['service_id'] = $iSvcId;
	}
	$iDefaultSubSvcId = isset($aParameters['servicesubcategory_id']) ? $aParameters['servicesubcategory_id'] : 0;

	$iDefaultWizNext = 2;

	$oSearch = DBObjectSearch::FromOQL(PORTAL_SERVICE_SUBCATEGORY_QUERY);
	RestrictSubcategories($oSearch);
	$oSearch->AllowAllData(); // In case the user has the rights on his org only
	$oSet = new CMDBObjectSet($oSearch, array(), array('svc_id' => $iSvcId, 'org_id' => $oUserOrg->GetKey()));
	if ($oSet->Count() == 1)
	{
		// Only one sub service, skip this step of the wizard
		$oSubService = $oSet->Fetch();
		$iSubSvdId = $oSubService->GetKey();
		SelectRequestTemplate($oP, $oUserOrg, $iSvcId, $iSubSvdId);
	}
	else
	{
		$oServiceCategory = MetaModel::GetObject('Service', $iSvcId, false, true /* allow all data*/);
		if (is_object($oServiceCategory))
		{
			$oP->add("<div class=\"wizContainer\" id=\"form_select_servicesubcategory\">\n");
			$oP->add("<h1 id=\"select_subcategory\">".Dict::Format('Portal:SelectSubcategoryFrom_Service', $oServiceCategory->GetName())."</h1>\n");
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
				$oP->add("<p><b><label for=\"servicesubcategory_$id\">".$oSubService->GetName()."</label></b></p>");
				$oP->add("<p>".$oSubService->GetAsHTML('description')."</p>");
				$oP->add("</td>");
				$oP->add("</tr>");
			}
			$oP->add("</table>\n");	
			$oP->DumpHiddenParams($aParameters, array('servicesubcategory_id'));
			$oP->add("<input type=\"hidden\" name=\"operation\" value=\"create_request\">");
			$oP->WizardFormButtons(BUTTON_BACK | BUTTON_NEXT | BUTTON_CANCEL); //Back button automatically discarded if on the first page
			$oP->WizardFormEnd();
			$oP->WizardCheckSelectionOnSubmit(Dict::S('Portal:PleaseSelectAServiceSubCategory'));
			$oP->add("</div>\n");
		}
		else
		{
			$oP->p("Error: Invalid Service: id = $iSvcId");
		}
	}
}

/**
 * Displays the form to select a Template
 * @param WebPage $oP Web page for the form output
 * @param Organization $oUserOrg The organization of the current user
 * @param $iSvcId Id of the selected service in case of pass-through (when there is only one service)
 * @param integer $iSubSvcId The identifier of the sub-service (fall through when there is only one sub-service)
 * @return void
 */
function SelectRequestTemplate($oP, $oUserOrg, $iSvcId = null, $iSubSvcId = null)
{
	$aParameters = $oP->ReadAllParams(PORTAL_ALL_PARAMS.',template_id');

	if (!is_null($iSvcId))
	{
		$aParameters['service_id'] = $iSvcId;
	}
	if (!is_null($iSubSvcId))
	{
		$aParameters['servicesubcategory_id'] = $iSubSvcId;
	}

	$iDefaultTemplate = isset($aParameters['template_id']) ? $aParameters['template_id'] : 0;
	if (MetaModel::IsValidClass('Template'))
	{
		$sClass = ComputeClass($aParameters['servicesubcategory_id']);
		try
		{
			$sOql = GetConstant($sClass, 'TEMPLATE_QUERY');
		}
		catch(Exception $e)
		{
			// Backward compatibility
			$sOql = REQUEST_TEMPLATE_QUERY;
		}
		$oSearch = DBObjectSearch::FromOQL($sOql);
		$oSearch->AllowAllData();
		$oSet = new CMDBObjectSet($oSearch, array(), array(
			'service_id' => $aParameters['service_id'],
			'servicesubcategory_id' => $aParameters['servicesubcategory_id']
		));
		if ($oSet->Count() == 0)
		{
			RequestCreationForm($oP, $oUserOrg, $aParameters['service_id'], $aParameters['servicesubcategory_id'], 0);
			return;
		}
		elseif ($oSet->Count() == 1)
		{
			$oTemplate = $oSet->Fetch();
			$iTemplateId = $oTemplate->GetKey();
			RequestCreationForm($oP, $oUserOrg, $aParameters['service_id'], $aParameters['servicesubcategory_id'], $iTemplateId);
			return;
		}

		$oServiceSubCategory = MetaModel::GetObject('ServiceSubcategory', $aParameters['servicesubcategory_id'], false);
		if (is_object($oServiceSubCategory))
		{
			$oP->add("<div class=\"wizContainer\" id=\"form_select_servicesubcategory\">\n");
			$oP->add("<h1 id=\"select_template\">".Dict::Format('Portal:SelectRequestTemplate', $oServiceSubCategory->GetName())."</h1>\n");
			$oP->WizardFormStart('request_wizard', 3);
			$oP->add("<table>\n");
			while($oTemplate = $oSet->Fetch())
			{
				$id = $oTemplate->GetKey();
				$sChecked = "";
				if ($id == $iDefaultTemplate)
				{
					$sChecked = "checked";
				}
				$oP->add("<tr>");

				$oP->add("<td style=\"vertical-align:top\">");
				$oP->p("<input name=\"attr_template_id\" $sChecked type=\"radio\" id=\"template_$id\" value=\"$id\">");
				$oP->add("</td>");

				$oP->add("<td style=\"vertical-align:top\">");
				$oP->p("<b><label for=\"template_$id\">".$oTemplate->GetAsHTML('label')."</label></b>");
				$oP->p($oTemplate->GetAsHTML('description'));
				$oP->add("</td>");

				$oP->add("</tr>");
			}
			$oP->add("</table>\n");	
			$oP->DumpHiddenParams($aParameters, array('template_id'));
			$oP->add("<input type=\"hidden\" name=\"operation\" value=\"create_request\">");
			$oP->WizardFormButtons(BUTTON_BACK | BUTTON_NEXT | BUTTON_CANCEL); //Back button automatically discarded if on the first page
			$oP->WizardCheckSelectionOnSubmit(Dict::S('Portal:PleaseSelectATemplate'));
			$oP->WizardFormEnd();
			$oP->add("</div>\n");
		}
		else
		{
			$oP->p("Error: Invalid servicesubcategory_id = ".$aParameters['servicesubcategory_id']);
		}
	}
	else
	{
		RequestCreationForm($oP, $oUserOrg, $aParameters['service_id'], $aParameters['servicesubcategory_id']);
		return;
	}
}

/**
 * Displays the form for the final step of the ticket creation
 * @param WebPage $oP The current web page for the form output
 * @param Organization $oUserOrg The organization of the current user
 * @param integer $iSvcId The identifier of the service (fall through when there is only one service)
 * @param integer $iSubSvcId The identifier of the sub-service (fall through when there is only one sub-service)
 * @param integer $iTemplateId The identifier of the template (fall through when there is only one template)
 * @return void
 */
function RequestCreationForm($oP, $oUserOrg, $iSvcId = null, $iSubSvcId = null, $iTemplateId = null)
{
	$aParameters = $oP->ReadAllParams(PORTAL_ALL_PARAMS.',template_id');
	if (!is_null($iSvcId))
	{
		$aParameters['service_id'] = $iSvcId;
	}
	if (!is_null($iSubSvcId))
	{
		$aParameters['servicesubcategory_id'] = $iSubSvcId;
	}
	if (!is_null($iTemplateId))
	{
		$aParameters['template_id'] = $iTemplateId;
	}
	
	$sDescription = '';
	if (isset($aParameters['template_id']) && ($aParameters['template_id'] != 0))
	{
		$aTemplateFields = array();
		$oTemplate = MetaModel::GetObject('Template', $aParameters['template_id'], false);
		if (is_object($oTemplate))
		{
			$oFieldSearch = DBObjectSearch::FromOQL('SELECT TemplateField WHERE template_id = :template_id');
			$oFieldSearch->AllowAllData();
			$oFieldSet = new DBObjectSet($oFieldSearch, array('order' => true), array('template_id' => $oTemplate->GetKey()));
			while($oField = $oFieldSet->Fetch())
			{
				$sAttCode = $oField->Get('code');
				if (isset($aParameters[$sAttCode]))
				{
					$oField->Set('initial_value', $aParameters[$sAttCode]);
				}
				$aTemplateFields[$sAttCode] = $oField;
			}
		}
	}

	$oServiceCategory = MetaModel::GetObject('Service', $aParameters['service_id'], false, true /* allow all data*/);
	$oServiceSubCategory = MetaModel::GetObject('ServiceSubcategory', $aParameters['servicesubcategory_id'], false, true /* allow all data*/);
	if (is_object($oServiceCategory) && is_object($oServiceSubCategory))
	{
		$sClass = ComputeClass($oServiceSubCategory->GetKey());
		$oRequest = MetaModel::NewObject($sClass);
		$oRequest->Set('org_id', $oUserOrg->GetKey());
		$oRequest->Set('caller_id', UserRights::GetContactId());
		$oRequest->Set('service_id', $aParameters['service_id']);
		$oRequest->Set('servicesubcategory_id', $aParameters['servicesubcategory_id']);

		$oAttDef = MetaModel::GetAttributeDef($sClass, 'service_id');
		$aDetails[] = array('label' => $oAttDef->GetLabel(), 'value' => $oServiceCategory->GetName());

		$oAttDef = MetaModel::GetAttributeDef($sClass, 'servicesubcategory_id');
		$aDetails[] = array('label' => $oAttDef->GetLabel(), 'value' => $oServiceSubCategory->GetName());

		$aList = explode(',', GetConstant($sClass, 'FORM_ATTRIBUTES'));

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
			$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
			$iFlags = $oRequest->GetAttributeFlags($sAttCode);
			if (isset($aParameters[$sAttCode]))
			{
				$value = $aParameters[$sAttCode];
			}
			$aArgs = array('this' => $oRequest);
				
			$sInputId = 'attr_'.$sAttCode;
			$aFieldsMap[$sAttCode] = $sInputId;
			$sValue = "<span id=\"field_{$sInputId}\">".$oRequest->GetFormElementForField($oP, $sClass, $sAttCode, $oAttDef, $value, '', 'attr_'.$sAttCode, '', $iFlags, $aArgs).'</span>';
			$aDetails[] = array('label' => $oAttDef->GetLabel(), 'value' => $sValue);
		}
		$aHidden = array();
		if (!empty($aTemplateFields))
		{
			foreach ($aTemplateFields as $sAttCode =>  $oField)
			{
				$sValue = $oField->GetFormElement($oP, $sClass);
				if ($oField->Get('input_type') == 'hidden')
				{
					$aHidden[] = $sValue;
				}
				else
				{
					$aDetails[] = array('label' => $oField->GetAsHTML('label'), 'value' => $sValue);
				}
			}
		}

		$oP->add_script(
<<<EOF
// Create the object once at the beginning of the page...
	var oWizardHelper = new WizardHelper('$sClass', '');
EOF
);

		$oP->add_linked_script("../js/json.js");
		$oP->add_linked_script("../js/forms-json-utils.js");
		$oP->add_linked_script("../js/wizardhelper.js");
		$oP->add_linked_script("../js/wizard.utils.js");
		$oP->add_linked_script("../js/linkswidget.js");
		$oP->add_linked_script("../js/extkeywidget.js");
		$oP->add_linked_script("../js/jquery.blockUI.js");
		$oP->add("<div class=\"wizContainer\" id=\"form_request_description\">\n");
		$oP->add("<h1 id=\"title_request_form\">".Dict::S('Portal:DescriptionOfTheRequest')."</h1>\n");
		$oP->WizardFormStart('request_form', 4);

		$oP->details($aDetails);

		// Add hidden fields for known values, enabling dependant attributes to be computed correctly
		//
		foreach($oRequest->ListChanges() as $sAttCode => $value)
		{
			if (!in_array($sAttCode, $aList))
			{
				$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
				if ($oAttDef->IsScalar() && $oAttDef->IsWritable())
				{
					$sValue = htmlentities($oRequest->Get($sAttCode), ENT_QUOTES, 'UTF-8');
					$oP->add("<input type=\"hidden\" id=\"attr_$sAttCode\" name=\"attr_$sAttCode\" value=\"$sValue\">");
					$aFieldsMap[$sAttCode] = 'attr_'.$sAttCode;
				}
			}
		}
		if (isset($aParameters['template_id']) && ($aParameters['template_id'] != 0))
		{
			$oP->add("<input type=\"hidden\" name=\"attr_template_id\" value=\"{$aParameters['template_id']}\">");
		}
		$oP->add(implode("\n", $aHidden));
		$oAttPlugin = new AttachmentPlugIn();
		$oAttPlugin->OnDisplayRelations($oRequest, $oP, true /* edit */);

		$oP->add("<input type=\"hidden\" name=\"operation\" value=\"create_request\">");
		$oP->WizardFormButtons(BUTTON_BACK | BUTTON_FINISH | BUTTON_CANCEL); //Back button automatically discarded if on the first page
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
 * Validate the parameters and create the ticket object (based on the page's POSTed parameters)
 * @param WebPage $oP The current web page for the  output
 * @param Organization $oUserOrg The organization of the current user
 * @return void
 */
function DoCreateRequest($oP, $oUserOrg)
{
	$aParameters = $oP->ReadAllParams(PORTAL_ALL_PARAMS.',template_id');
	$sTransactionId = utils::ReadPostedParam('transaction_id', '');
	if (!utils::IsTransactionValid($sTransactionId))
	{
		$oP->add("<h1>".Dict::S('UI:Error:ObjectAlreadyCreated')."</h1>\n");
		//ShowOngoingTickets($oP);
		return;
	}
		
	// Validate the parameters
	// 1) ServiceCategory
	$oSearch = DBObjectSearch::FromOQL(PORTAL_VALIDATE_SERVICECATEGORY_QUERY);
	$oSearch->AllowAllData(); // In case the user has the rights on his org only
	$oSet = new CMDBObjectSet($oSearch, array(), array('id' => $aParameters['service_id'], 'org_id' => $oUserOrg->GetKey()));
	if ($oSet->Count() != 1)
	{
		// Invalid service for the current user !
		throw new Exception("Invalid Service Category: id={$aParameters['service_id']} - count: ".$oSet->Count());
	}
	$oServiceCategory = $oSet->Fetch();
	
	// 2) Service Subcategory
	$oSearch = DBObjectSearch::FromOQL(PORTAL_VALIDATE_SERVICESUBCATEGORY_QUERY);
	RestrictSubcategories($oSearch);
	$oSearch->AllowAllData(); // In case the user has the rights on his org only
	$oSet = new CMDBObjectSet($oSearch, array(), array('service_id' => $aParameters['service_id'], 'id' =>$aParameters['servicesubcategory_id'],'org_id' => $oUserOrg->GetKey() ));
	if ($oSet->Count() != 1)
	{
		// Invalid subcategory
		throw new Exception("Invalid ServiceSubcategory: id={$aParameters['servicesubcategory_id']} for service category ".$oServiceCategory->GetName()."({$aParameters['service_id']}) - count: ".$oSet->Count());
	}
	$oServiceSubCategory = $oSet->Fetch();

	$sClass = ComputeClass($oServiceSubCategory->GetKey());
	$oRequest = MetaModel::NewObject($sClass);
	$aAttList = array_merge(explode(',', GetConstant($sClass, 'FORM_ATTRIBUTES')), array('service_id', 'servicesubcategory_id'));
	$oRequest->UpdateObjectFromPostedForm('' /* form prefix */, $aAttList);
	$oRequest->Set('org_id', $oUserOrg->GetKey());
	$oRequest->Set('caller_id', UserRights::GetContactId());

	if (isset($aParameters['moreinfo']))
	{
		// There is a template, insert it into the description
		$sLogAttCode = GetConstant($sClass, 'PUBLIC_LOG');
		$oRequest->Set($sLogAttCode, $aParameters['moreinfo']);
	}

	$sTypeAttCode = GetConstant($sClass, 'TYPE');
	if (($sTypeAttCode != '') && (PORTAL_SET_TYPE_FROM != ''))
	{
		$oRequest->Set($sTypeAttCode, $oServiceSubCategory->Get(PORTAL_SET_TYPE_FROM));
	}
	if (MetaModel::IsValidAttCode($sClass, 'origin'))
	{
		$oRequest->Set('origin', 'portal');
	}

	$oAttPlugin = new AttachmentPlugIn();
	$oAttPlugin->OnFormSubmit($oRequest);

	list($bRes, $aIssues) = $oRequest->CheckToWrite();
	if ($bRes)
	{
		if (isset($aParameters['template_id']))
		{
			$oTemplate = MetaModel::GetObject('Template', $aParameters['template_id']);
			$sLogAttCode = GetConstant($sClass, 'PUBLIC_LOG');
			$oRequest->Set($sLogAttCode, $oTemplate->GetPostedValuesAsText($oRequest)."\n");
			$oRequest->DBInsertNoReload();
			$oTemplate->RecordExtraDataFromPostedForm($oRequest);
		}
		else
		{
			$oRequest->DBInsertNoReload();
		}
		$oP->add("<h1>".Dict::Format('UI:Title:Object_Of_Class_Created', $oRequest->GetName(), MetaModel::GetName($sClass))."</h1>\n");

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
		SelectRequestTemplate($oP, $oUserOrg);
		break;
		
		case 3:
		RequestCreationForm($oP, $oUserOrg);
		break;

		case 4:
		DoCreateRequest($oP, $oUserOrg);
		break;
	}
}

/**
 * Helper to display lists (UserRequest, Incident, etc.)
 * Adjust the presentation depending on the following cases:
 * - no item at all
 * - items of one class only
 * - items of several classes    
 */ 
function DisplayRequestLists(WebPage $oP, $aClassToSet)
{
	$iNotEmpty = 0; // Count of types for which there are some items to display
	foreach ($aClassToSet as $sClass => $oSet)
	{
		if ($oSet->Count() > 0)
		{
			$iNotEmpty++;
		}
	}
	if ($iNotEmpty == 0)
	{
		$oP->p(Dict::S('Portal:NoOpenRequest'));
	}
	else
	{
		foreach ($aClassToSet as $sClass => $oSet)
		{
			if ($iNotEmpty > 1)
			{
				// Differentiate the sublists
				$oP->add("<h2>".MetaModel::GetName($sClass)."</h2>\n");
			}
			if ($oSet->Count() > 0)
			{
				$sZList = GetConstant($sClass, 'LIST_ZLIST');
				$aZList =  explode(',', $sZList);
				$oP->DisplaySet($oSet, $aZList, Dict::S('Portal:NoOpenRequest'));
			}
		}
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

	$aClassToSet = array();
	foreach (GetTicketClasses() as $sClass)
	{
		$sOQL = "SELECT $sClass WHERE org_id = :org_id AND status NOT IN ('closed', 'resolved')";
		$oSearch = DBObjectSearch::FromOQL($sOQL);
		$iUser = UserRights::GetContactId();
		if ($iUser > 0 && !IsPowerUser())
		{
			$oSearch->AddCondition('caller_id', $iUser);
		}
		$aClassToSet[$sClass] = new CMDBObjectSet($oSearch, array(), array('org_id' => $oUserOrg->GetKey()));
	}
	DisplayRequestLists($oP, $aClassToSet);
}

/**
 * Lists all the currently resolved (not yet closed) User Requests for the current user
 * @param WebPage $oP The current web page
 * @return void
 */
function ListResolvedRequests(WebPage $oP)
{
	$oUserOrg = GetUserOrg();

	$aClassToSet = array();
	foreach (GetTicketClasses() as $sClass)
	{
		$sOQL = "SELECT $sClass WHERE org_id = :org_id AND status = 'resolved'";
		$oSearch = DBObjectSearch::FromOQL($sOQL);
		$iUser = UserRights::GetContactId();
		if ($iUser > 0 && !IsPowerUser())
		{
			$oSearch->AddCondition('caller_id', $iUser);
		}
		$aClassToSet[$sClass] = new CMDBObjectSet($oSearch, array(), array('org_id' => $oUserOrg->GetKey()));
	}
	DisplayRequestLists($oP, $aClassToSet);
}

/**
 * Lists all the currently closed tickets
 * @param WebPage $oP The current web page
 * @return void
 */
function ListClosedTickets(WebPage $oP)
{
	$aAttSpecs = explode(',', PORTAL_TICKETS_SEARCH_CRITERIA);
	// Remove the caller_id form the search criteria if the user is not a Portal Power User
	// since the user is only allowed to see her/his own tickets
	foreach($aAttSpecs as $idx => $sAttCode)
	{
		if (($sAttCode == 'caller_id') && !IsPowerUser())
		{
			unset($aAttSpecs[$idx]);
		}
	}
	$aClasses = GetTicketClasses();
	$sMainClass = reset($aClasses);
	$oP->DisplaySearchForm($sMainClass, $aAttSpecs, array('operation' => 'show_closed'), 'search_', false /* => not closed */);

	$oUserOrg = GetUserOrg();

	$oP->add("<h1>".Dict::S('Portal:ClosedRequests')."</h1>\n");

	$aClassToSet = array();
	foreach (GetTicketClasses() as $sClass)
	{
		$oSearch = $oP->PostedParamsToFilter($sClass, $aAttSpecs, 'search_');
		if(is_null($oSearch))
		{
			$oSearch = new DBObjectSearch($sClass);
		}
		$oSearch->AddCondition('org_id', $oUserOrg->GetKey());
		$oSearch->AddCondition('status', 'closed');
		$iUser = UserRights::GetContactId();
		if ($iUser > 0 && !IsPowerUser())
		{
			$oSearch->AddCondition('caller_id', $iUser);
		}
		$aClassToSet[$sClass] = new CMDBObjectSet($oSearch);
	}
	DisplayRequestLists($oP, $aClassToSet);
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
	if (in_array(get_class($oObj), GetTicketClasses()))
	{
		ShowDetailsRequest($oP, $oObj);
	}
	else
	{
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
	$sLogAttCode = GetConstant($sClass, 'PUBLIC_LOG');
	$sUserCommentAttCode = GetConstant($sClass, 'USER_COMMENT');

	$bIsReopenButton = false;
	$bIsCloseButton = false;
	$bIsEscalateButton = false;
	$bEditAttachments = false;
	$aEditAtt = array(); // List of attributes editable in the main form
	if (!MetaModel::DBIsReadOnly())
	{
		switch($oObj->GetState())
		{						
			case 'resolved':
			$aEditAtt = array();
			$aTransitions = $oObj->EnumTransitions();
			$oSet = DBObjectSet::FromObject($oObj);
			// Add the "Reopen" button if this is valid action
			if (array_key_exists('ev_reopen', $aTransitions) && UserRights::IsStimulusAllowed($sClass, 'ev_reopen', $oSet))
			{
				$bIsReopenButton = true;
				MakeStimulusForm($oP, $oObj, 'ev_reopen', array($sLogAttCode));
			}
			// Add the "Close" button if this is valid action
			if (array_key_exists('ev_close', $aTransitions) && UserRights::IsStimulusAllowed($sClass, 'ev_close', $oSet))
			{
				$bIsCloseButton = true;
				MakeStimulusForm($oP, $oObj, 'ev_close', array('user_satisfaction', $sUserCommentAttCode));
			}
			break;
	
			case 'closed':
			// By convention 'closed' is the final state of a ticket and nothing can be done in such a state
			break;

			default:
			// In all other states, the only possible action is to update the ticket (both the case log and the attachments)
			// This update is possible only if the case log field is not read-only or hidden in the current state
			$iFlags = $oObj->GetAttributeFlags($sLogAttCode);
			$bReadOnly = (($iFlags & (OPT_ATT_READONLY | OPT_ATT_HIDDEN)) != 0);
			if ($bReadOnly)
			{
				$aEditAtt = array();
				$bEditAttachments = false;				
			}
			else
			{
				$aEditAtt = array(
					$sLogAttCode => '????'
				);
				$bEditAttachments = true;
			}
			break;
		}
	}

// REFACTORISER LA MISE EN FORME
	$oP->add("<h1 id=\"title_request_details\">".$oObj->GetIcon()."&nbsp;".Dict::Format('Portal:TitleRequestDetailsFor_Request', $oObj->GetName())."</h1>\n");

	$aAttList = json_decode(GetConstant($sClass, 'DETAILS_ZLIST'), true);

	switch($oObj->GetState())
	{
		case 'closed':
		$aAttList['centered'][] = 'user_satisfaction';
		$aAttList['centered'][] = $sUserCommentAttCode;
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
	$oP->DisplayObjectDetails($oObj, $aAttList['col:left']);
	$oP->add('</td>');
	$oP->add('<td style="vertical-align:top;">');
	$oP->DisplayObjectDetails($oObj, $aAttList['col:right']);
	$oP->add('</td>');
	$oP->add('</tr>');
	if (array_key_exists('centered', $aAttList))
	{
		$oP->add('<tr>');
		$oP->add('<td style="vertical-align:top;" colspan="2">');
		$oP->DisplayObjectDetails($oObj, $aAttList['centered']);
		$oP->add('</td>');
		$oP->add('</tr>');
	}

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
		if ($sAttCode == $sLogAttCode)
		{
			// Skip, the public log will be displayed below the buttons
			continue;
		}
		$oP->add("<div class=\"edit_item\">");
		$oP->add('<h1>'.$aFieldSpec['label'].'</h1>');
		$oP->add($aFieldSpec['value']);
		$oP->add('</div>');
	}
	if($bIsReopenButton)
	{
		$sStimulusCode = 'ev_reopen';
		$sTitle = addslashes(Dict::S('Portal:Button:ReopenTicket'));
		$sOk = addslashes(Dict::S('UI:Button:Ok'));
		$oP->p('<input type="button" onClick="RunStimulusDialog(\''.$sStimulusCode.'\', \''.$sTitle.'\', \''.$sOk.'\');" value="'.$sTitle.'...">');
	}
	if($bIsCloseButton)
	{
		$sStimulusCode = 'ev_close';
		$sTitle = addslashes(Dict::S('Portal:Button:CloseTicket'));
		$sOk = addslashes(Dict::S('UI:Button:Ok'));
		$oP->p('<input type="button" onClick="RunStimulusDialog(\''.$sStimulusCode.'\', \''.$sTitle.'\', \''.$sOk.'\');" value="'.$sTitle.'...">');
	}
	elseif (count($aEditAtt) > 0)
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

	$oP->add('<tr>');
	$oP->add('<td colspan="2" style="vertical-align:top;">');
	if (isset($aEditFields[$sLogAttCode]))
	{
		$oP->add("<div class=\"edit_item\">");
		$oP->add('<h1>'.$aEditFields[$sLogAttCode]['label'].'</h1>');
		$oP->add($aEditFields[$sLogAttCode]['value']);
		$oP->add('</div>');
	}
	else
	{
		$oP->add('<h1>'.MetaModel::GetLabel($sClass, $sLogAttCode).'</h1>');
		$oP->add($oObj->GetAsHTML($sLogAttCode));
	}
	$oP->add('</td>');
	$oP->add('</tr>');

	$oP->add('</table>');
	$oP->add('</div>');

	$oP->WizardFormEnd();
	$oP->add('</div>');
}

/**
 * Create form to apply a stimulus
 * @param WebPage $oP The current web page
 * @param Object $oObj The target object
 * @param String $sStimulusCode Stimulus that will be applied
 * @param Array $aEditAtt List of attributes to edit
 * @return void
 */
function MakeStimulusForm(WebPage $oP, $oObj, $sStimulusCode, $aEditAtt)
{
	static $bHasStimulusForm = false;

	$sDialogId = $sStimulusCode."_dialog";
	$sFormId = $sStimulusCode."_form";
	$sCancelButtonLabel = Dict::S('UI:Button:Cancel');

	$oP->add('<div id="'.$sDialogId.'" style="display: none;">');
	$sClass = get_class($oObj);

	$oP->add('<form id="'.$sFormId.'" method="post">');
	$sTransactionId = utils::GetNewTransactionId();
	$oP->add("<input type=\"hidden\" id=\"transaction_id\" name=\"transaction_id\" value=\"$sTransactionId\">\n");
	$oP->add("<input type=\"hidden\" name=\"class\" value=\"$sClass\">");
	$oP->add("<input type=\"hidden\" name=\"id\" value=\"".$oObj->GetKey()."\">");
	$oP->add("<input type=\"hidden\" name=\"operation\" value=\"update_request\">");
	$oP->add("<input type=\"hidden\" id=\"stimulus_to_apply\" name=\"apply_stimulus\" value=\"$sStimulusCode\">\n");

	$aTransitions = $oObj->EnumTransitions();
	$aStimuli = MetaModel::EnumStimuli($sClass);
	if (!isset($aTransitions[$sStimulusCode]))
	{
		// Invalid stimulus
		throw new ApplicationException(Dict::Format('UI:Error:Invalid_Stimulus_On_Object_In_State', $sStimulusCode, $oObj->GetName(), $oObj->GetStateLabel()));
	}

	// Compute the attribute flags in the target state
	$aTransition = $aTransitions[$sStimulusCode];
	$sTargetState = $aTransition['target_state'];
	$aTargetStates = MetaModel::EnumStates($sClass);
	$aTargetState = $aTargetStates[$sTargetState];
	$aExpectedAttributes = $aTargetState['attribute_list'];

	foreach($aEditAtt as $sAttCode)
	{
		$sValue = $oObj->Get($sAttCode);
		$sDisplayValue = $oObj->GetEditValue($sAttCode);
		$aArgs = array('this' => $oObj, 'formPrefix' => '');
		$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
		$sInputId = 'input_'.$sAttCode;
		$iFlags = array_key_exists($sAttCode, $aExpectedAttributes) ? $aExpectedAttributes[$sAttCode] : 0;
		$sHTMLValue = "<span id=\"field_{$sStimulusCode}_{$sInputId}\">".cmdbAbstractObject::GetFormElementForField($oP, $sClass, $sAttCode, $oAttDef, $sValue, $sDisplayValue, $sInputId, '', $iFlags, $aArgs).'</span>';

		$oP->add('<h1>'.MetaModel::GetLabel($sClass, $sAttCode).'</h1>');
		$oP->add($sHTMLValue);
	}
	$oP->add('</form>');
	$oP->add('</div>');

	if (!$bHasStimulusForm)
	{
		$bHasStimulusForm = true;
		$oP->add_script(
<<<EOF

function RunStimulusDialog(sStimulusCode, sTitle, sOkButtonLabel)
{
	var sWidth = 'auto';
	if (sStimulusCode == 'ev_reopen')
	{
		// Avoid having a dialog spanning the complete width of the window
		// just because it contains a CaseLog entry
		sWidth = '80%';
	}
				
	$('#'+sStimulusCode+'_dialog').dialog({
		height: 'auto',
		width: sWidth,
		modal: true,
		title: sTitle,
		buttons: [
		{ text: sOkButtonLabel, click: function() {
			$(this).find('#'+sStimulusCode+'_form').submit();
		} },
		{ text: "$sCancelButtonLabel", click: function() {
			$(this).dialog( "close" );
		} }
		]
	});
	// Start the validation
	CheckFields(sStimulusCode+'_form', false);
	$('#'+sStimulusCode+'_form').submit( function() {
		return OnSubmit(sStimulusCode+'_form');
	});
}
EOF
		);
	}
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

/**
 * Determine if the current user can be considered as being a portal power user
 */ 
function IsPowerUSer()
{
	$iUserID = UserRights::GetUserId();
	$sOQLprofile = "SELECT URP_Profiles AS p JOIN URP_UserProfile AS up ON up.profileid=p.id WHERE up.userid = :user AND p.name = :profile";
	$oProfileSet = new DBObjectSet(
		DBObjectSearch::FromOQL($sOQLprofile),
		array(),
		array(
			'user' => $iUserID,
			'profile' => PORTAL_POWER_USER_PROFILE,
		)
	);	
	$bRes = ($oProfileSet->count() > 0);
	return $bRes;
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

	$aClasses = explode(',', MetaModel::GetConfig()->Get('portal_tickets'));
	$sMainClass = trim(reset($aClasses));
	if (!class_exists($sMainClass))
	{
		$oP = new WebPage(Dict::S('Portal:Title'));
		$oP->p(dict::Format('Portal:NoRequestMgmt', UserRights::GetUserFriendlyName()));
	}
	else
	{
		$oUserOrg = GetUserOrg();
	
		$sCode = $oUserOrg->Get('code');
		$sAlternateStylesheet = '';
		if (@file_exists("./$sCode/portal.css"))
		{
			$sAlternateStylesheet = "$sCode";
		}
	
		$oP = new PortalWebPage(Dict::S('Portal:Title'), $sAlternateStylesheet);
	
	   $oP->EnableDisconnectButton(utils::CanLogOff());
	   $oP->SetWelcomeMessage(Dict::Format('Portal:WelcomeUserOrg', UserRights::GetUserFriendlyName(), $oUserOrg->GetName()));
	
		if (is_object($oUserOrg))
		{
			switch($sOperation)
			{
				case 'show_closed':
				$oP->set_title(Dict::S('Portal:ShowClosed'));
				DisplayMainMenu($oP);
				ShowClosedTickets($oP);
				break;
						
				case 'create_request':
				$oP->set_title(Dict::S('Portal:CreateNewRequest'));
				DisplayMainMenu($oP);
				if (!MetaModel::DBIsReadOnly())
				{
					CreateRequest($oP, $oUserOrg);
				}
				break;
						
				case 'details':
				$oP->set_title(Dict::S('Portal:TitleDetailsFor_Request'));
				DisplayMainMenu($oP);
				$oObj = $oP->FindObjectFromArgs(GetTicketClasses());
				ValidateObject($oObj);
				DisplayObject($oP, $oObj, $oUserOrg);
				break;
				
				case 'update_request':
				$oP->set_title(Dict::S('Portal:TitleDetailsFor_Request'));
				DisplayMainMenu($oP);
				if (!MetaModel::DBIsReadOnly())
				{
					$oObj = $oP->FindObjectFromArgs(GetTicketClasses());
					ValidateObject($oObj);
					$aAttList = array(
						GetConstant(get_class($oObj), 'PUBLIC_LOG'),
						'user_satisfaction',
						GetConstant(get_class($oObj), 'USER_COMMENT')
					);
					try
					{
						$oP->DoUpdateObjectFromPostedForm($oObj, $aAttList);
						$oObj->Reload(); // Make sure the object is in good shape to be displayed
					}
					catch(TransactionException $e)
					{
						$oP->add("<h1>".Dict::S('UI:Error:ObjectAlreadyUpdated')."</h1>\n");
					}
					DisplayObject($oP, $oObj, $oUserOrg);
				}
				break;
	
				case 'show_ongoing':
				default:
				$oP->set_title(Dict::S('Portal:ShowOngoing'));
				DisplayMainMenu($oP);
				ShowOngoingTickets($oP);
			} 
		}
	}
	$oP->output();
}
catch(CoreException $e)
{
	require_once(APPROOT.'/setup/setuppage.class.inc.php');
	$oP = new SetupPage(Dict::S('UI:PageTitle:FatalError'));
	$oP->add("<h1>".Dict::S('UI:FatalErrorMessage')."</h1>\n");	
	$oP->error(Dict::Format('UI:Error_Details', $e->getHtmlDesc()));	
	//$oP->p($e->getTraceAsString());	
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
	//$oP->p($e->getTraceAsString());	
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
