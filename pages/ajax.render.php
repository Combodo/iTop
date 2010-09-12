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
 * Handles various ajax requests
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

require_once('../application/application.inc.php');
require_once('../application/webpage.class.inc.php');
require_once('../application/ajaxwebpage.class.inc.php');
require_once('../application/wizardhelper.class.inc.php');
require_once('../application/ui.linkswidget.class.inc.php');

require_once('../application/startup.inc.php');
require_once('../application/user.preferences.class.inc.php');

require_once('../application/loginwebpage.class.inc.php');
LoginWebPage::DoLogin(false /* bMustBeAdmin */, true /* IsAllowedToPortalUsers */); // Check user rights and prompt if needed

$oPage = new ajax_page("");
$oPage->no_cache();

$operation = utils::ReadParam('operation', '');
$sFilter = stripslashes(utils::ReadParam('filter', ''));
$sEncoding = utils::ReadParam('encoding', 'serialize');
$sClass = utils::ReadParam('class', 'MissingAjaxParam');
$sStyle = utils::ReadParam('style', 'list');

switch($operation)
{
	case 'addObjects':
	require_once('../application/uilinkswizard.class.inc.php');
	$sClass = utils::ReadParam('class', '', 'get');
	$sLinkedClass = utils::ReadParam('linkedClass', '');
	$sLinkageAttr = utils::ReadParam('linkageAttr', '');
	$iObjectId = utils::ReadParam('objectId', '');
	$oLinksWizard = new UILinksWizard($sClass,  $sLinkageAttr, $iObjectId, $sLinkedClass);
	$oLinksWizard->DisplayAddForm($oPage);
	break;
	
	// ui.linkswidget
	case 'searchObjectsToAdd':
	$sRemoteClass = utils::ReadParam('sRemoteClass', '');
	$sAttCode = utils::ReadParam('sAttCode', '');
	$iInputId = utils::ReadParam('iInputId', '');
	$sSuffix = utils::ReadParam('sSuffix', '');
	$aAlreadyLinked = utils::ReadParam('aAlreadyLinked', array());
	$oWidget = new UILinksWidget($sClass, $sAttCode, $iInputId, $sSuffix);
	$oWidget->SearchObjectsToAdd($oPage, $sRemoteClass, $aAlreadyLinked);	
	break;
	
	// ui.linkswidget
	case 'doAddObjects':
	$sAttCode = utils::ReadParam('sAttCode', '');
	$iInputId = utils::ReadParam('iInputId', '');
	$sSuffix = utils::ReadParam('sSuffix', '');
	$aLinkedObjectIds = utils::ReadParam('selectObject', array());
	$oWidget = new UILinksWidget($sClass, $sAttCode, $iInputId, $sSuffix);
	$oWidget->DoAddObjects($oPage, $aLinkedObjectIds);	
	break;
	
	case 'wizard_helper_preview':
	$sJson = utils::ReadParam('json_obj', '');
	$oWizardHelper = WizardHelper::FromJSON($sJson);
	$oObj = $oWizardHelper->GetTargetObject();
	$oObj->DisplayBareProperties($oPage); 
	break;
	
	case 'wizard_helper':
	$sJson = utils::ReadParam('json_obj', '');
	$oWizardHelper = WizardHelper::FromJSON($sJson);
	$oObj = $oWizardHelper->GetTargetObject(); 
	$sClass = $oWizardHelper->GetTargetClass();
	foreach($oWizardHelper->GetFieldsForDefaultValue() as $sAttCode)
	{
		$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
		$defaultValue = $oAttDef->GetDefaultValue();
		$oWizardHelper->SetDefaultValue($sAttCode, $defaultValue);
		$oObj->Set($sAttCode, $defaultValue);
	}
	foreach($oWizardHelper->GetFieldsForAllowedValues() as $sAttCode)
	{
		$sId = $oWizardHelper->GetIdForField($sAttCode);
		if ($sId != '')
		{
			// It may happen that the field we'd like to update does not
			// exist in the form. For example, if the field should be hidden/read-only
			// in the current state of the object
			$value = $oObj->Get($sAttCode);
			$displayValue = $oObj->GetEditValue($sAttCode);
			$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
			$sHTMLValue = cmdbAbstractObject::GetFormElementForField($oPage, $sClass, $sAttCode, $oAttDef, $value, $displayValue, $sId, '', 0, array('this' => $oObj));
			// Make sure that we immediatly validate the field when we reload it
			$oPage->add_ready_script("$('#$sId').trigger('validate');");
			$oWizardHelper->SetAllowedValuesHtml($sAttCode, $sHTMLValue);
		}
	}
	$oPage->add("<script type=\"text/javascript\">\noWizardHelper.m_oData=".$oWizardHelper->ToJSON().";\noWizardHelper.UpdateFields();\n</script>\n");
	break;
		
	case 'ajax':
	if ($sFilter != "")
	{
		$sExtraParams = stripslashes(utils::ReadParam('extra_params', ''));
		$aExtraParams = array();
		if (!empty($sExtraParams))
		{
			$aExtraParams = json_decode(str_replace("'", '"', $sExtraParams), true /* associative array */);
		}
		if ($sEncoding == 'oql')
		{
			$oFilter = CMDBSearchFilter::FromOQL($sFilter);
		}
		else
		{
			$oFilter = CMDBSearchFilter::unserialize($sFilter);
		}
		$oDisplayBlock = new DisplayBlock($oFilter, $sStyle, false);
		$oDisplayBlock->RenderContent($oPage, $aExtraParams);
	}
	else
	{
		$oPage->p("Invalid query (empty filter).");
	}
	break;
	
	case 'details':
	$key = utils::ReadParam('id', 0);
	$oFilter = new DBObjectSearch($sClass);
	$oFilter->AddCondition('id', $key, '=');
	$oDisplayBlock = new DisplayBlock($oFilter, 'details', false);
	$oDisplayBlock->RenderContent($oPage);
	break;
	
	case 'preview':
	$key = utils::ReadParam('id', 0);
	$oFilter = new DBObjectSearch($sClass);
	$oFilter->AddCondition('id', $key, '=');
	$oDisplayBlock = new DisplayBlock($oFilter, 'preview', false);
	$oDisplayBlock->RenderContent($oPage);
	break;
	
	case 'pie_chart':
	$sGroupBy = utils::ReadParam('group_by', '');
	if ($sFilter != '')
	{
		if ($sEncoding == 'oql')
		{
			$oFilter = CMDBSearchFilter::FromOQL($sFilter);
		}
		else
		{
			$oFilter = CMDBSearchFilter::unserialize($sFilter);
		}
		$oDisplayBlock = new DisplayBlock($oFilter, 'pie_chart_ajax', false);
		$oDisplayBlock->RenderContent($oPage, array('group_by' => $sGroupBy));
	}
	else
	{
	
		$oPage->add("<chart>\n<chart_type>3d pie</chart_type><!-- empty filter '$sFilter' --></chart>\n.");
	}
	break;
	
	case 'open_flash_chart':
	$aParams = utils::ReadParam('params', array());
	if ($sFilter != '')
	{
		$oFilter = CMDBSearchFilter::unserialize($sFilter);
		$oDisplayBlock = new DisplayBlock($oFilter, 'open_flash_chart_ajax', false);
		$oDisplayBlock->RenderContent($oPage, $aParams);
	}
	else
	{
	
		$oPage->add("<chart>\n<chart_type>3d pie</chart_type><!-- empty filter '$sFilter' --></chart>\n.");
	}
	break;

	case 'modal_details':
	$key = utils::ReadParam('id', 0);
	$oFilter = new DBObjectSearch($sClass);
	$oFilter->AddCondition('id', $key, '=');
	$oPage->Add("<p style=\"width:100%; margin-top:-5px;padding:3px; background-color:#33f; color:#fff;\">Object Details</p>\n");
	$oDisplayBlock = new DisplayBlock($oFilter, 'details', false);
	$oDisplayBlock->RenderContent($oPage);
	$oPage->Add("<input type=\"button\" class=\"jqmClose\" value=\" Close \" />\n");
	break;
		
	case 'ui.linkswidget':
	/*
	$sClass = utils::ReadParam('sclass', 'bizContact');
	$sAttCode = utils::ReadParam('attCode', 'name');
	$sOrg = utils::ReadParam('org_id', '');
	$sName = utils::ReadParam('q', '');
	$iMaxCount = utils::ReadParam('max', 30);
	UILinksWidget::Autocomplete($oPage, $sClass, $sAttCode, $sName, $iMaxCount);
	*/
	break;
	
	case 'ui.linkswidget.linkedset':
	/*
	$sClass = utils::ReadParam('sclass', 'bizContact');
	$sJSONSet = stripslashes(utils::ReadParam('sset', ''));
	$sExtKeyToMe = utils::ReadParam('sextkeytome', '');
	$sExtKeyToRemote = utils::ReadParam('sextkeytoremote', '');
	$iObjectId = utils::ReadParam('id', -1);
	UILinksWidget::RenderSet($oPage, $sClass, $sJSONSet, $sExtKeyToMe, $sExtKeyToRemote, $iObjectId);
	$iFieldId = utils::ReadParam('myid', '-1');
	$oPage->add_ready_script("$('#{$iFieldId}').trigger('validate');");
	*/
	break;
	
	case 'autocomplete':
	$key = utils::ReadParam('id', 0);
	$sClass = utils::ReadParam('sclass', 'bizContact');
	$sAttCode = utils::ReadParam('attCode', 'name');
	$sOrg = utils::ReadParam('org_id', '');
	$sName = utils::ReadParam('q', '');
	$iMaxCount = utils::ReadParam('max', 30);
	$aArgs = array();
	if (!empty($key))
	{
		if ($oThis = MetaModel::GetObject($sClass, $key))
		{
			$aArgs['*this*'] = $oThis;
			$aArgs['this'] = $oThis;
		}
	} 
	$aAllowedValues = MetaModel::GetAllowedValues_att($sClass, $sAttCode, $aArgs, $sName);
	$iCount = 0;
	foreach($aAllowedValues as $key => $value)
	{
		$oPage->add($value."|".$key."\n");
	}
	break;
	
	case 'link':
	$sClass = utils::ReadParam('sclass', 'logInfra');
	$sAttCode = utils::ReadParam('attCode', 'name');
	//$sOrg = utils::ReadParam('org_id', '');
	$sName = utils::ReadParam('q', '');
	$iMaxCount = utils::ReadParam('max', 30);
	$iCount = 0;
	$oFilter = new DBObjectSearch($sClass);
	$oFilter->AddCondition($sAttCode, $sName, 'Begins with');
	//$oFilter->AddCondition('org_id', $sOrg, '=');
	$oSet = new CMDBObjectSet($oFilter, array($sAttCode => true));
	while( ($iCount < $iMaxCount) && ($oObj = $oSet->fetch()) )
	{
		$oPage->add($oObj->GetAsHTML($sAttCode)."|".$oObj->GetKey()."\n");
		$iCount++;
	}
	break;
	
	case 'create':
		case 'create_menu':
		$sClass = utils::ReadParam('class', '');
		$sFilter = utils::ReadParam('filter', '');
		menuNode::DisplayCreationForm($oPage, $sClass, $sFilter);
	break;

	case 'combo_options':
	$oFilter = CMDBSearchFilter::FromOQL($sFilter);
	$oSet = new CMDBObjectSet($oFilter);
	while( $oObj = $oSet->fetch())
	{
		$oPage->add('<option title="Here is more information..." value="'.$oObj->GetKey().'">'.$oObj->GetName().'</option>');
	}
	break;
	
	case 'display_document':
	$id = utils::ReadParam('id', '');
	$sField = utils::ReadParam('field', '');
	if (!empty($sClass) && !empty($id) && !empty($sField))
	{
		DownloadDocument($oPage, $sClass, $id, $sField, 'inline');
	}
	break;
	
	case 'download_document':
	$id = utils::ReadParam('id', '');
	$sField = utils::ReadParam('field', '');
	if (!empty($sClass) && !empty($id) && !empty($sField))
	{
		DownloadDocument($oPage, $sClass, $id, $sField, 'attachement');
	}
	break;
	
	case 'search_form':
	$sClass = utils::ReadParam('className', '');
	$sRootClass = utils::ReadParam('baseClass', '');
	$currentId = utils::ReadParam('currentId', '');
	$oFilter = new DBObjectSearch($sClass);
	$oSet = new CMDBObjectSet($oFilter); 
	$sHtml = cmdbAbstractObject::GetSearchForm($oPage, $oSet, array('currentId' => $currentId, 'baseClass' => $sRootClass));
	$oPage->add($sHtml);
	break;
	
	case 'set_pref':
	$sCode = utils::ReadPostedParam('code', '');
	$sValue = utils::ReadPostedParam('value', '');
	appUserPreferences::SetPref($sCode, $sValue);
	break;

	case 'erase_all_pref':
	// Can be useful in case a user got some corrupted prefs...
	appUserPreferences::ClearPreferences();
	break;

	default:
	$oPage->p("Invalid query.");
}
$oPage->output();

/**
 * Downloads a document to the browser, either as 'inline' or 'attachment'
 *  
 * @param WebPage $oPage The web page for the output
 * @param string $sClass Class name of the object
 * @param mixed $id Identifier of the object
 * @param string $sAttCode Name of the attribute containing the document to download
 * @param string $sContentDisposition Either 'inline' or 'attachment'
 * @return none
 */   
function DownloadDocument(WebPage $oPage, $sClass, $id, $sAttCode, $sContentDisposition = 'attachement')
{
	try
	{
		$oObj = MetaModel::GetObject($sClass, $id);
		if (is_object($oObj))
		{
			$oDocument = $oObj->Get($sAttCode);
			if (is_object($oDocument))
			{
				$oPage->add_header('Content-type: '.$oDocument->GetMimeType());
				$oPage->add_header('Content-Disposition: '.$sContentDisposition.'; filename="'.$oDocument->GetFileName().'"');
				$oPage->add($oDocument->GetData());
			}
		}
	}
	catch(Exception $e)
	{
		$oPage->p($e->getMessage());
	}
}
?>
