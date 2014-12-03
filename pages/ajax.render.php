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
 * Handles various ajax requests
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

require_once('../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/webpage.class.inc.php');
require_once(APPROOT.'/application/ajaxwebpage.class.inc.php');
require_once(APPROOT.'/application/wizardhelper.class.inc.php');
require_once(APPROOT.'/application/ui.linkswidget.class.inc.php');
require_once(APPROOT.'/application/ui.extkeywidget.class.inc.php');
require_once(APPROOT.'/application/datatable.class.inc.php');
require_once(APPROOT.'/application/excelexporter.class.inc.php');

try
{
	require_once(APPROOT.'/application/startup.inc.php');
	require_once(APPROOT.'/application/user.preferences.class.inc.php');
	
	require_once(APPROOT.'/application/loginwebpage.class.inc.php');
	LoginWebPage::DoLogin(false /* bMustBeAdmin */, true /* IsAllowedToPortalUsers */); // Check user rights and prompt if needed
	
	$oPage = new ajax_page("");
	$oPage->no_cache();

	
	$operation = utils::ReadParam('operation', '');
	$sFilter = stripslashes(utils::ReadParam('filter', '', false, 'raw_data'));
	$sEncoding = utils::ReadParam('encoding', 'serialize');
	$sClass = utils::ReadParam('class', 'MissingAjaxParam', false, 'class');
	$sStyle = utils::ReadParam('style', 'list');

	switch($operation)
	{
		case 'datatable':
		case 'pagination':
		$oPage->SetContentType('text/html');
		$extraParams = utils::ReadParam('extra_param', '', false, 'raw_data');
		$aExtraParams = array();
		if (is_array($extraParams))
		{
			$aExtraParams = $extraParams;
		}
		else
		{
			$sExtraParams = stripslashes($extraParams);
			if (!empty($sExtraParams))
			{
				$val = json_decode(str_replace("'", '"', $sExtraParams), true /* associative array */);
				if ($val !== null)
				{
					$aExtraParams = $val;
				}
			}
		}
		if ($sEncoding == 'oql')
		{
			$oFilter = CMDBSearchFilter::FromOQL($sFilter);
		}
		else
		{
			$oFilter = CMDBSearchFilter::unserialize($sFilter);
		}
		$iStart = utils::ReadParam('start',0);
		$iEnd = utils::ReadParam('end',1);
		$iSortCol = utils::ReadParam('sort_col','null');
		$sSelectMode = utils::ReadParam('select_mode', '');
		if (!empty($sSelectMode) && ($sSelectMode != 'none'))
		{
			// The first column is used for the selection (radio / checkbox) and is not sortable
			$iSortCol--;
		}
		$bDisplayKey = utils::ReadParam('display_key', 'true') == 'true';
		$aColumns = utils::ReadParam('columns', array(), false, 'raw_data');
		$aClassAliases = utils::ReadParam('class_aliases', array());
		$iListId = utils::ReadParam('list_id', 0);
		//$aList = cmdbAbstractObject::FlattenZList(MetaModel::GetZListItems($sClassName, 'list'));

		// Filter the list to removed linked set since we are not able to display them here
		$aOrderBy = array();
		$iSortIndex = 0;
		
		$aColumnsLoad = array();
		foreach($aClassAliases as $sAlias => $sClassName)
		{
			$aColumnsLoad[$sAlias] = array();
			foreach($aColumns[$sAlias] as $sAttCode => $aData)
			{
				if ($aData['checked'] == 'true')
				{
					$aColumns[$sAlias][$sAttCode]['checked'] = true;
					if ($sAttCode == '_key_')
					{
						if ($iSortCol == $iSortIndex)
						{
							if (!MetaModel::HasChildrenClasses($oFilter->GetClass()))
							{
								$aNameSpec = MetaModel::GetNameSpec($oFilter->GetClass());
								if ($aNameSpec[0] == '%1$s')
								{
									// The name is made of a single column, let's sort according to the sort algorithm for this column
									$aOrderBy[$sAlias.'.'.$aNameSpec[1][0]] = (utils::ReadParam('sort_order', 'asc') == 'asc');
								}
								else
								{
									$aOrderBy[$sAlias.'.'.'friendlyname'] = (utils::ReadParam('sort_order', 'asc') == 'asc');
								}
							}
							else
							{
								$aOrderBy[$sAlias.'.'.'friendlyname'] = (utils::ReadParam('sort_order', 'asc') == 'asc');
							}
						}
					}
					else
					{
						$oAttDef = MetaModel::GetAttributeDef($sClassName, $sAttCode);
						if ($oAttDef instanceof AttributeLinkedSet)
						{
							// Removed from the display list
							unset($aColumns[$sAlias][$sAttCode]);
						}
						else
						{
							$aColumnsLoad[$sAlias][] = $sAttCode;
						}
						if ($iSortCol == $iSortIndex)
						{
							if ($oAttDef->IsExternalKey())
							{
								$sSortCol = $sAttCode.'_friendlyname';
							}
							else
							{
								$sSortCol = $sAttCode;
							}
							$aOrderBy[$sAlias.'.'.$sSortCol] = (utils::ReadParam('sort_order', 'asc') == 'asc');
						}
					}
					$iSortIndex++;
				}
				else
				{
					$aColumns[$sAlias][$sAttCode]['checked'] = false;
				}
			}

		}
		
		// Load only the requested columns
		$oSet = new DBObjectSet($oFilter, $aOrderBy, $aExtraParams, null, $iEnd-$iStart, $iStart);
		$oSet->OptimizeColumnLoad($aColumnsLoad);

		$oDataTable = new DataTable($iListId, $oSet, $oSet->GetSelectedClasses());
		if ($operation == 'datatable')
		{
			// Redraw the whole table
			$sHtml = $oDataTable->UpdatePager($oPage, $iEnd-$iStart, $iStart); // Set the default page size
			$sHtml .= $oDataTable->GetHTMLTable($oPage, $aColumns, $sSelectMode, $iEnd-$iStart, $bDisplayKey, $aExtraParams);
		}
		else
		{
			// redraw just the needed rows
			$sHtml = $oDataTable->GetAsHTMLTableRows($oPage, $iEnd-$iStart, $aColumns, $sSelectMode, $bDisplayKey, $aExtraParams);
		}
		$oPage->add($sHtml);
		break;
		
		case 'datatable_save_settings':
		$oPage->SetContentType('text/plain');
		$iPageSize = utils::ReadParam('page_size', 10);
		$sTableId = utils::ReadParam('table_id', null, false, 'raw_data');
		$bSaveAsDefaults = (utils::ReadParam('defaults', 'true') == 'true');
		$aClassAliases = utils::ReadParam('class_aliases', array(), false, 'raw_data');
		$aColumns = utils::ReadParam('columns', array(), false, 'raw_data');
		
		foreach($aColumns as $sAlias => $aList)
		{
			foreach($aList as $sAttCode => $aData)
			{
				$aColumns[$sAlias][$sAttCode]['checked'] = ($aData['checked'] == 'true');
				$aColumns[$sAlias][$sAttCode]['disabled'] = ($aData['disabled'] == 'true');
				$aColumns[$sAlias][$sAttCode]['sort'] = ($aData['sort']);
			}
		}
		
		$oSettings = new DataTableSettings($aClassAliases, $sTableId);
		$oSettings->iDefaultPageSize = $iPageSize;
		$oSettings->aColumns = $aColumns;

		if ($bSaveAsDefaults)
		{
			if ($sTableId != null)
			{
				$oCurrSettings = DataTableSettings::GetTableSettings($aClassAliases, $sTableId, true /* bOnlyTable */ );
				if ($oCurrSettings)
				{
					$oCurrSettings->ResetToDefault(false); // Reset this table to the defaults
				}
			}
			$bRet = $oSettings->SaveAsDefault();
		}
		else
		{
			$bRet = $oSettings->Save();
		}
		$oPage->add($bRet ? 'Ok' : 'KO');
		break;
		
		case 'datatable_reset_settings':
		$oPage->SetContentType('text/plain');
		$sTableId = utils::ReadParam('table_id', null, false, 'raw_data');
		$aClassAliases = utils::ReadParam('class_aliases', array(), false, 'raw_data');
		$bResetAll = (utils::ReadParam('defaults', 'true') == 'true');
		
		$oSettings = new DataTableSettings($aClassAliases, $sTableId);
		$bRet = $oSettings->ResetToDefault($bResetAll);
		$oPage->add($bRet ? 'Ok' : 'KO');
		break;
		
		// ui.linkswidget
		case 'addObjects':
		$oPage->SetContentType('text/html');
		$sAttCode = utils::ReadParam('sAttCode', '');
		$iInputId = utils::ReadParam('iInputId', '');
		$sSuffix = utils::ReadParam('sSuffix', '');
		$bDuplicates = (utils::ReadParam('bDuplicates', 'false') == 'false') ? false : true;
		$sJson = utils::ReadParam('json', '', false, 'raw_data');
		if (!empty($sJson))
		{
			$oWizardHelper = WizardHelper::FromJSON($sJson);
			$oObj = $oWizardHelper->GetTargetObject();
		}
		else
		{
			// Search form: no current object
			$oObj = null;
		}
		$oWidget = new UILinksWidget($sClass, $sAttCode, $iInputId, $sSuffix, $bDuplicates);
		$oWidget->GetObjectPickerDialog($oPage, $oObj);	
		break;
		
		// ui.linkswidget
		case 'searchObjectsToAdd':
		$oPage->SetContentType('text/html');
		$sRemoteClass = utils::ReadParam('sRemoteClass', '', false, 'class');
		$sAttCode = utils::ReadParam('sAttCode', '');
		$iInputId = utils::ReadParam('iInputId', '');
		$sSuffix = utils::ReadParam('sSuffix', '');
		$bDuplicates = (utils::ReadParam('bDuplicates', 'false') == 'false') ? false : true;
		$aAlreadyLinked = utils::ReadParam('aAlreadyLinked', array());
		$oWidget = new UILinksWidget($sClass, $sAttCode, $iInputId, $sSuffix, $bDuplicates);
		$oWidget->SearchObjectsToAdd($oPage, $sRemoteClass, $aAlreadyLinked);	
		break;
		
		//ui.linksdirectwidget
		case 'createObject':
		$oPage->SetContentType('text/html');
		$sClass = utils::ReadParam('class', '', false, 'class');
		$sRealClass = utils::ReadParam('real_class', '', false, 'class');
		$sAttCode = utils::ReadParam('att_code', '');
		$iInputId = utils::ReadParam('iInputId', '');
		$oPage->SetContentType('text/html');
		$oWidget = new UILinksWidgetDirect($sClass, $sAttCode, $iInputId);
		$oWidget->GetObjectCreationDlg($oPage, $sRealClass);
		break;
		
		// ui.linksdirectwidget
		case 'getLinksetRow':
		$oPage->SetContentType('text/html');
		$sClass = utils::ReadParam('class', '', false, 'class');
		$sRealClass = utils::ReadParam('real_class', '', false, 'class');
		$sAttCode = utils::ReadParam('att_code', '');
		$iInputId = utils::ReadParam('iInputId', '');
		$iTempId = utils::ReadParam('tempId', '');
		$aValues = utils::ReadParam('values', array(), false, 'raw_data');
		$oPage->SetContentType('text/html');
		$oWidget = new UILinksWidgetDirect($sClass, $sAttCode, $iInputId);
		$oPage->add($oWidget->GetRow($oPage, $sRealClass, $aValues, -$iTempId));
		break;
		
		// ui.linksdirectwidget
		case 'selectObjectsToAdd':
		$oPage->SetContentType('text/html');
		$sClass = utils::ReadParam('class', '', false, 'class');
		$sJson = utils::ReadParam('json', '', false, 'raw_data');
		$oObj = null;
		if ($sJson != '')
		{
			$oWizardHelper = WizardHelper::FromJSON($sJson);
			$oObj = $oWizardHelper->GetTargetObject();
		}
		$sRealClass = utils::ReadParam('real_class', '', false, 'class');
		$sAttCode = utils::ReadParam('att_code', '');
		$iInputId = utils::ReadParam('iInputId', '');
		$iCurrObjectId =  utils::ReadParam('iObjId', 0);
		$oPage->SetContentType('text/html');
		$oWidget = new UILinksWidgetDirect($sClass, $sAttCode, $iInputId);
		$oWidget->GetObjectsSelectionDlg($oPage, $oObj);
		break;
			
		// ui.linksdirectwidget
		case 'searchObjectsToAdd2':
		$oPage->SetContentType('text/html');
		$sClass = utils::ReadParam('class', '', false, 'class');
		$sRealClass = utils::ReadParam('real_class', '', false, 'class');
		$sAttCode = utils::ReadParam('att_code', '');
		$iInputId = utils::ReadParam('iInputId', '');
		$aAlreadyLinked =  utils::ReadParam('aAlreadyLinked', array());
		$sJson = utils::ReadParam('json', '', false, 'raw_data');
		$oObj = null;
		if ($sJson != '')
		{
			$oWizardHelper = WizardHelper::FromJSON($sJson);
			$oObj = $oWizardHelper->GetTargetObject();
		}
		$oWidget = new UILinksWidgetDirect($sClass, $sAttCode, $iInputId);
		$oWidget->SearchObjectsToAdd($oPage, $sRealClass, $aAlreadyLinked, $oObj);
		break;
		
		// ui.linksdirectwidget
		case 'doAddObjects2':
		$oPage->SetContentType('text/html');
		$oPage->SetContentType('text/html');
		$sClass = utils::ReadParam('class', '', false, 'class');
		$sRealClass = utils::ReadParam('real_class', '', false, 'class');
		$sAttCode = utils::ReadParam('att_code', '');
		$iInputId = utils::ReadParam('iInputId', '');
		$iCurrObjectId =  utils::ReadParam('iObjId', 0);
		$sFilter = utils::ReadParam('filter', '', false, 'raw_data');
		if ($sFilter != '')
		{
			$oFullSetFilter = DBObjectSearch::unserialize($sFilter);
		}
		else
		{
			$oLinksetDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
			$valuesDef = $oLinksetDef->GetValuesDef();				
			if ($valuesDef === null)
			{
				$oFullSetFilter = new DBObjectSearch($oLinksetDef->GetLinkedClass());
			}
			else
			{
				if (!$valuesDef instanceof ValueSetObjects)
				{
					throw new Exception('Error: only ValueSetObjects are supported for "allowed_values" in AttributeLinkedSet ('.$this->sClass.'/'.$this->sAttCode.').');
				}
				$oFullSetFilter = DBObjectSearch::FromOQL($valuesDef->GetFilterExpression());
			}		
		}
		$oWidget = new UILinksWidgetDirect($sClass, $sAttCode, $iInputId);
		$oWidget->DoAddObjects($oPage, $oFullSetFilter);	
		break;
		
		////////////////////////////////////////////////////////////
		
		// ui.extkeywidget
		case 'searchObjectsToSelect':
		$oPage->SetContentType('text/html');
		$sTargetClass = utils::ReadParam('sTargetClass', '', false, 'class');
		$iInputId = utils::ReadParam('iInputId', '');
		$sRemoteClass = utils::ReadParam('sRemoteClass', '', false, 'class');
		$sFilter = utils::ReadParam('sFilter', '', false, 'raw_data');
		$sJson = utils::ReadParam('json', '', false, 'raw_data');
		$sAttCode = utils::ReadParam('sAttCode', '');
		$bSearchMode = (utils::ReadParam('bSearchMode', 'false') == 'true');
		if (!empty($sJson))
		{
			$oWizardHelper = WizardHelper::FromJSON($sJson);
			$oObj = $oWizardHelper->GetTargetObject();
		}
		else
		{
			// Search form: no current object
			$oObj = null;
		}
		$oWidget = new UIExtKeyWidget($sTargetClass, $iInputId, $sAttCode, $bSearchMode);
		$oWidget->SearchObjectsToSelect($oPage, $sFilter, $sRemoteClass, $oObj);	
		break;
	
		// ui.extkeywidget: autocomplete
		case 'ac_extkey':
		$oPage->SetContentType('text/plain');
		$sTargetClass = utils::ReadParam('sTargetClass', '', false, 'class');
		$iInputId = utils::ReadParam('iInputId', '');
		$sFilter = utils::ReadParam('sFilter', '', false, 'raw_data');
		$sJson = utils::ReadParam('json', '', false, 'raw_data');
		$sContains = utils::ReadParam('q', '', false, 'raw_data');
		$bSearchMode = (utils::ReadParam('bSearchMode', 'false') == 'true');
		if ($sContains !='')
		{
			if (!empty($sJson))
			{
				$oWizardHelper = WizardHelper::FromJSON($sJson);
				$oObj = $oWizardHelper->GetTargetObject();
			}
			else
			{
				// Search form: no current object
				$oObj = null;
			}
			$oWidget = new UIExtKeyWidget($sTargetClass, $iInputId, '', $bSearchMode);
			$oWidget->AutoComplete($oPage, $sFilter, $oObj, $sContains);
		}
		break;
	
		// ui.extkeywidget
		case 'objectSearchForm':
		$oPage->SetContentType('text/html');
		$sTargetClass = utils::ReadParam('sTargetClass', '', false, 'class');
		$iInputId = utils::ReadParam('iInputId', '');
		$sTitle = utils::ReadParam('sTitle', '', false, 'raw_data');
		$sAttCode = utils::ReadParam('sAttCode', '');
		$bSearchMode = (utils::ReadParam('bSearchMode', 'false') == 'true');
		$oWidget = new UIExtKeyWidget($sTargetClass, $iInputId, $sAttCode, $bSearchMode);
		$sJson = utils::ReadParam('json', '', false, 'raw_data');
		if (!empty($sJson))
		{
			$oWizardHelper = WizardHelper::FromJSON($sJson);
			$oObj = $oWizardHelper->GetTargetObject();
		}
		else
		{
			// Search form: no current object
			$oObj = null;
		}
		$oWidget->GetSearchDialog($oPage, $sTitle, $oObj);
		break;

		// ui.extkeywidget
		case 'objectCreationForm':
		$oPage->SetContentType('text/html');
		$sTargetClass = utils::ReadParam('sTargetClass', '', false, 'class');
		$iInputId = utils::ReadParam('iInputId', '');
		$sAttCode = utils::ReadParam('sAttCode', '');
		$oWidget = new UIExtKeyWidget($sTargetClass, $iInputId, $sAttCode, false);
		$sJson = utils::ReadParam('json', '', false, 'raw_data');
		if (!empty($sJson))
		{
			$oWizardHelper = WizardHelper::FromJSON($sJson);
			$oObj = $oWizardHelper->GetTargetObject();
		}
		else
		{
			// Search form: no current object
			$oObj = null;
		}
		$oWidget->GetObjectCreationForm($oPage, $oObj);
		break;
		
		// ui.extkeywidget
		case 'doCreateObject':
		$oPage->SetContentType('application/json');
		$sTargetClass = utils::ReadParam('sTargetClass', '', false, 'class');
		$iInputId = utils::ReadParam('iInputId', '');
		$sFormPrefix = utils::ReadParam('sFormPrefix', '');
		$sAttCode = utils::ReadParam('sAttCode', '');
		$oWidget = new UIExtKeyWidget($sTargetClass, $iInputId, $sAttCode, false);
		$aResult = $oWidget->DoCreateObject($oPage);
		echo json_encode($aResult);
		break;
		
		// ui.extkeywidget
		case 'getObjectName':
		$oPage->SetContentType('application/json');
		$sTargetClass = utils::ReadParam('sTargetClass', '', false, 'class');
		$iInputId = utils::ReadParam('iInputId', '');
		$iObjectId = utils::ReadParam('iObjectId', '');
		$bSearchMode = (utils::ReadParam('bSearchMode', 'false') == 'true');
		$oWidget = new UIExtKeyWidget($sTargetClass, $iInputId, '', $bSearchMode);
		$sName = $oWidget->GetObjectName($iObjectId);
		echo json_encode(array('name' => $sName));	
		break;
		
		// ui.extkeywidget
		case 'displayHierarchy':
		$oPage->SetContentType('text/html');
		$sTargetClass = utils::ReadParam('sTargetClass', '', false, 'class');
		$sInputId = utils::ReadParam('sInputId', '');
		$sFilter = utils::ReadParam('sFilter', '', false, 'raw_data');
		$sJson = utils::ReadParam('json', '', false, 'raw_data');
		$currValue = utils::ReadParam('value', '');
		$bSearchMode = (utils::ReadParam('bSearchMode', 'false') == 'true');
		if (!empty($sJson))
		{
			$oWizardHelper = WizardHelper::FromJSON($sJson);
			$oObj = $oWizardHelper->GetTargetObject();
		}
		else
		{
			// Search form: no current object
			$oObj = null;
		}
		$oWidget = new UIExtKeyWidget($sTargetClass, $sInputId, '', $bSearchMode);
		$oWidget->DisplayHierarchy($oPage, $sFilter, $currValue, $oObj);
		break;
		
		////////////////////////////////////////////////////
		
		// ui.linkswidget
		case 'doAddObjects':
		$oPage->SetContentType('text/html');
		$sAttCode = utils::ReadParam('sAttCode', '');
		$iInputId = utils::ReadParam('iInputId', '');
		$sSuffix = utils::ReadParam('sSuffix', '');
		$sRemoteClass = utils::ReadParam('sRemoteClass', $sClass, false, 'class');
		$bDuplicates = (utils::ReadParam('bDuplicates', 'false') == 'false') ? false : true;
		$sJson = utils::ReadParam('json', '', false, 'raw_data');
		$oWizardHelper = WizardHelper::FromJSON($sJson);
		$oObj = $oWizardHelper->GetTargetObject();
		$oWidget = new UILinksWidget($sClass, $sAttCode, $iInputId, $sSuffix, $bDuplicates);
		if ($sFilter != '')
		{
			$oFullSetFilter = DBObjectSearch::unserialize($sFilter);
		}
		else
		{
			$oFullSetFilter = new DBObjectSearch($sRemoteClass);		
		}
		$oWidget->DoAddObjects($oPage, $oFullSetFilter, $oObj);	
		break;
			
		////////////////////////////////////////////////////////////
		
		case 'wizard_helper_preview':
		$oPage->SetContentType('text/html');
		$sJson = utils::ReadParam('json_obj', '', false, 'raw_data');
		$oWizardHelper = WizardHelper::FromJSON($sJson);
		$oObj = $oWizardHelper->GetTargetObject();
		$oObj->DisplayBareProperties($oPage); 
		break;
		
		case 'wizard_helper':
		$oPage->SetContentType('text/html');
		$sJson = utils::ReadParam('json_obj', '', false, 'raw_data');
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
		$sFormPrefix = $oWizardHelper->GetFormPrefix();
		foreach($oWizardHelper->GetFieldsForAllowedValues() as $sAttCode)
		{
			$sId = $oWizardHelper->GetIdForField($sAttCode);
			if ($sId != '')
			{
				if ($oObj->IsNew())
				{
					$iFlags = $oObj->GetInitialStateAttributeFlags($sAttCode);
				}
				else
				{
					$iFlags = $oObj->GetAttributeFlags($sAttCode);
				}
				if ($iFlags & OPT_ATT_READONLY)
				{
					$sHTMLValue = "<span id=\"field_{$sId}\">".$oObj->GetAsHTML($sAttCode);
					$sHTMLValue .= '<input type="hidden" id="'.$sId.'" name="attr_'.$sFormPrefix.$sAttCode.'" value="'.htmlentities($oObj->Get($sAttCode), ENT_QUOTES, 'UTF-8').'"/></span>';
					$oWizardHelper->SetAllowedValuesHtml($sAttCode, $sHTMLValue);
				}
				else
				{
					// It may happen that the field we'd like to update does not
					// exist in the form. For example, if the field should be hidden/read-only
					// in the current state of the object
					$value = $oObj->Get($sAttCode);
					$displayValue = $oObj->GetEditValue($sAttCode);
					$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
					if (!$oAttDef->IsWritable())
					{
						// Even non-writable fields (like AttributeExternal) can be refreshed 
						$sHTMLValue = $oObj->GetAsHTML($sAttCode);
					}
					else
					{
						$iFlags = MetaModel::GetAttributeFlags($sClass, $oObj->GetState(), $sAttCode);
						$sHTMLValue = cmdbAbstractObject::GetFormElementForField($oPage, $sClass, $sAttCode, $oAttDef, $value, $displayValue, $sId, '', $iFlags, array('this' => $oObj, 'formPrefix' => $sFormPrefix));
						// Make sure that we immediately validate the field when we reload it
						$oPage->add_ready_script("$('#$sId').trigger('validate');");
					}
					$oWizardHelper->SetAllowedValuesHtml($sAttCode, $sHTMLValue);
				}
			}
		}
		$oPage->add_script("oWizardHelper{$sFormPrefix}.m_oData=".$oWizardHelper->ToJSON().";\noWizardHelper{$sFormPrefix}.UpdateFields();\n");
		break;
		
		case 'obj_creation_form':
		$oPage->SetContentType('text/html');
		$sJson = utils::ReadParam('json_obj', '', false, 'raw_data');
		$oWizardHelper = WizardHelper::FromJSON($sJson);
		$oObj = $oWizardHelper->GetTargetObject(); 
		$sClass = $oWizardHelper->GetTargetClass();
		$sTargetState = utils::ReadParam('target_state', '');
		$iTransactionId = utils::ReadParam('transaction_id', '');
		$oObj->Set(MetaModel::GetStateAttributeCode($sClass), $sTargetState);
		cmdbAbstractObject::DisplayCreationForm($oPage, $sClass, $oObj, array(), array('action' => utils::GetAbsoluteUrlAppRoot().'pages/UI.php', 'transaction_id' => $iTransactionId)); 
		break;
		
		// DisplayBlock
		case 'ajax':
		$oPage->SetContentType('text/html');
		if ($sFilter != "")
		{
			$sExtraParams = stripslashes(utils::ReadParam('extra_params', '', false, 'raw_data'));
			$aExtraParams = array();
			if (!empty($sExtraParams))
			{
				$aExtraParams = json_decode(str_replace("'", '"', $sExtraParams), true /* associative array */);
			}
			// Restore the app context from the ExtraParams
			$oAppContext = new ApplicationContext(false); // false => don't read the context yet !
			$aContext = array();
			foreach($oAppContext->GetNames() as $sName)
			{
				$sParamName = 'c['.$sName.']';
				if (isset($aExtraParams[$sParamName]))
				{
					$aContext[$sName] = $aExtraParams[$sParamName];
				}
			}
			$_REQUEST['c'] = $aContext;
			if ($sEncoding == 'oql')
			{
				$oFilter = CMDBSearchFilter::FromOQL($sFilter);
			}
			else
			{
				$oFilter = CMDBSearchFilter::unserialize($sFilter);
			}
			$oDisplayBlock = new DisplayBlock($oFilter, $sStyle, false);
			$aExtraParams['display_limit'] = true;
			$aExtraParams['truncated'] = true;
			$oDisplayBlock->RenderContent($oPage, $aExtraParams);
		}
		else
		{
			$oPage->p("Invalid query (empty filter).");
		}
		break;
		
		case 'displayCSVHistory':
		$oPage->SetContentType('text/html');
		$bShowAll = (utils::ReadParam('showall', 'false') == 'true');
		BulkChange::DisplayImportHistory($oPage, true, $bShowAll);
		break;
		
		case 'details':
		$oPage->SetContentType('text/html');
		$key = utils::ReadParam('id', 0);
		$oFilter = new DBObjectSearch($sClass);
		$oFilter->AddCondition('id', $key, '=');
		$oDisplayBlock = new DisplayBlock($oFilter, 'details', false);
		$oDisplayBlock->RenderContent($oPage);
		break;
		
		case 'pie_chart':
		$oPage->SetContentType('application/json');
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
		// Workaround for IE8 + IIS + HTTPS
		// See TRAC #363, fix described here: http://forums.codecharge.com/posts.php?post_id=97771
		$oPage->add_header("Expires: Fri, 17 Jul 1970 05:00:00 GMT");
		$oPage->add_header("Cache-Control: cache, must-revalidate");
		$oPage->add_header("Pragma: public");

		$oPage->SetContentType('application/json');
		$aParams = utils::ReadParam('params', array(), false, 'raw_data');
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
		$oPage->SetContentType('text/html');
		$key = utils::ReadParam('id', 0);
		$oFilter = new DBObjectSearch($sClass);
		$oFilter->AddCondition('id', $key, '=');
		$oPage->Add("<p style=\"width:100%; margin-top:-5px;padding:3px; background-color:#33f; color:#fff;\">Object Details</p>\n");
		$oDisplayBlock = new DisplayBlock($oFilter, 'details', false);
		$oDisplayBlock->RenderContent($oPage);
		$oPage->Add("<input type=\"button\" class=\"jqmClose\" value=\" Close \" />\n");
		break;

		case 'link':
		$oPage->SetContentType('text/html');
		$sClass = utils::ReadParam('sclass', 'logInfra', false, 'class');
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
	
		case 'combo_options':
		$oPage->SetContentType('text/html');
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
		$iCacheSec = (int) utils::ReadParam('cache', 0);
		if (!empty($sClass) && !empty($id) && !empty($sField))
		{
			DownloadDocument($oPage, $sClass, $id, $sField, 'attachment');
			if ($iCacheSec > 0)
			{
				$oPage->add_header("Expires: "); // Reset the value set in ajax_page
				$oPage->add_header("Cache-Control: no-transform,public,max-age=$iCacheSec,s-maxage=$iCacheSec");
			}
		}
		break;
		
		case 'search_form':
		$oPage->SetContentType('text/html');
		$sClass = utils::ReadParam('className', '', false, 'class');
		$sRootClass = utils::ReadParam('baseClass', '', false, 'class');
		$currentId = utils::ReadParam('currentId', '');
		$sTableId = utils::ReadParam('_table_id_', null, false, 'raw_data');
		$sAction = utils::ReadParam('action', '');
		$oFilter = new DBObjectSearch($sClass);
		$oSet = new CMDBObjectSet($oFilter); 
		$sHtml = cmdbAbstractObject::GetSearchForm($oPage, $oSet, array('currentId' => $currentId, 'baseClass' => $sRootClass, 'action' => $sAction, 'table_id' => $sTableId));
		$oPage->add($sHtml);
		break;
		
		case 'set_pref':
		$sCode = utils::ReadPostedParam('code', '');
		$sValue = utils::ReadPostedParam('value', '', 'raw_data');
		appUserPreferences::SetPref($sCode, $sValue);
		break;
	
		case 'erase_all_pref':
		// Can be useful in case a user got some corrupted prefs...
		appUserPreferences::ClearPreferences();
		break;

		case 'on_form_cancel':
		// Called when a creation/modification form is cancelled by the end-user
		// Let's take this opportunity to inform the plug-ins so that they can perform some cleanup
		$iTransactionId = utils::ReadParam('transaction_id', 0);
		$sTempId = session_id().'_'.$iTransactionId;
		foreach (MetaModel::EnumPlugins('iApplicationUIExtension') as $oExtensionInstance)
		{
			$oExtensionInstance->OnFormCancel($sTempId);
		}
		break;

		case 'reload_dashboard':
		$oPage->SetContentType('text/html');
		$sDashboardId = utils::ReadParam('dashboard_id', '', false, 'raw_data');
		$aExtraParams = utils::ReadParam('extra_params', '', false, 'raw_data');
		ApplicationMenu::LoadAdditionalMenus();
		$idx = ApplicationMenu::GetMenuIndexById($sDashboardId);
		$oMenu = ApplicationMenu::GetMenuNode($idx);
		$oDashboard = $oMenu->GetDashboard();
		$oDashboard->Render($oPage, false, $aExtraParams);
		$oPage->add_ready_script("$('.dashboard_contents table.listResults').tableHover(); $('.dashboard_contents table.listResults').tablesorter( { widgets: ['myZebra', 'truncatedList']} );");
		break;
		
		case 'dashboard_editor':
		$sId = utils::ReadParam('id', '', false, 'raw_data');
		ApplicationMenu::LoadAdditionalMenus();
		$idx = ApplicationMenu::GetMenuIndexById($sId);
		$oMenu = ApplicationMenu::GetMenuNode($idx);
		$oMenu->RenderEditor($oPage);
		break;
		
		case 'new_dashlet':
		require_once(APPROOT.'application/forms.class.inc.php');
		require_once(APPROOT.'application/dashlet.class.inc.php');
		$sDashletClass = utils::ReadParam('dashlet_class', '');
		$sDashletId =  utils::ReadParam('dashlet_id', '', false, 'raw_data');
		if (is_subclass_of($sDashletClass, 'Dashlet'))
		{
			$oDashlet = new $sDashletClass(new ModelReflectionRuntime(), $sDashletId);
			$offset = $oPage->start_capture();
			$oDashlet->DoRender($oPage, true /* bEditMode */, false /* bEnclosingDiv */);
			$sHtml = addslashes($oPage->end_capture($offset));
			$sHtml = str_replace("\n", '', $sHtml);
			$sHtml = str_replace("\r", '', $sHtml);
			$oPage->add_script("$('#dashlet_$sDashletId').html('$sHtml');"); // in ajax web page add_script has the same effect as add_ready_script
																			// but is executed BEFORE all 'ready_scripts'
			$oForm = $oDashlet->GetForm(); // Rebuild the form since the values/content changed
			$oForm->SetSubmitParams(utils::GetAbsoluteUrlAppRoot().'pages/ajax.render.php', array('operation' => 'update_dashlet_property'));
			$sHtml = addslashes($oForm->RenderAsPropertySheet($oPage, true /* bReturnHtml */, '.itop-dashboard'));
			$sHtml = str_replace("\n", '', $sHtml);
			$sHtml = str_replace("\r", '', $sHtml);
			$oPage->add_script("$('#dashlet_properties_$sDashletId').html('$sHtml')"); // in ajax web page add_script has the same effect as add_ready_script																	   // but is executed BEFORE all 'ready_scripts'
		}
		break;
			
		case 'update_dashlet_property':
		require_once(APPROOT.'application/forms.class.inc.php');
		require_once(APPROOT.'application/dashlet.class.inc.php');
		$aParams = utils::ReadParam('params', '', false, 'raw_data');
		$sDashletClass = $aParams['attr_dashlet_class'];
		$sDashletId = $aParams['attr_dashlet_id'];
		$aUpdatedProperties = $aParams['updated']; // Code of the changed properties as an array: 'attr_xxx', 'attr_xxy', etc...
		$aPreviousValues = $aParams['previous_values']; // hash array: 'attr_xxx' => 'old_value'
		if (is_subclass_of($sDashletClass, 'Dashlet'))
		{
			$oDashlet = new $sDashletClass(new ModelReflectionRuntime(), $sDashletId);
			$oForm = $oDashlet->GetForm();
			$aValues = $oForm->ReadParams(); // hash array: 'xxx' => 'new_value'
			
			$aCurrentValues = $aValues;
			$aUpdatedDecoded = array();
			foreach($aUpdatedProperties as $sProp)
			{
				$sDecodedProp = str_replace('attr_', '', $sProp); // Remove the attr_ prefix
				$aCurrentValues[$sDecodedProp] = $aPreviousValues[$sProp]; // Set the previous value
				$aUpdatedDecoded[] = $sDecodedProp;
			}
			
			$oDashlet->FromParams($aCurrentValues);
			$sPrevClass = get_class($oDashlet);
			$oDashlet = $oDashlet->Update($aValues, $aUpdatedDecoded);
			$sNewClass = get_class($oDashlet);
			if ($sNewClass != $sPrevClass)
			{
				$oPage->add_ready_script("$('#dashlet_$sDashletId').dashlet('option', {dashlet_class: '$sNewClass'});");
			}
			if ($oDashlet->IsRedrawNeeded())
			{
				$offset = $oPage->start_capture();
				$oDashlet->DoRender($oPage, true /* bEditMode */, false /* bEnclosingDiv */);
				$sHtml = addslashes($oPage->end_capture($offset));
				$sHtml = str_replace("\n", '', $sHtml);
				$sHtml = str_replace("\r", '', $sHtml);
				
				$oPage->add_script("$('#dashlet_$sDashletId').html('$sHtml');"); // in ajax web page add_script has the same effect as add_ready_script
																				// but is executed BEFORE all 'ready_scripts'
			}
			if ($oDashlet->IsFormRedrawNeeded())
			{
				$oForm = $oDashlet->GetForm(); // Rebuild the form since the values/content changed
				$oForm->SetSubmitParams(utils::GetAbsoluteUrlAppRoot().'pages/ajax.render.php', array('operation' => 'update_dashlet_property'));
				$sHtml = addslashes($oForm->RenderAsPropertySheet($oPage, true /* bReturnHtml */, '.itop-dashboard'));
				$sHtml = str_replace("\n", '', $sHtml);
				$sHtml = str_replace("\r", '', $sHtml);
				$oPage->add_script("$('#dashlet_properties_$sDashletId').html('$sHtml')"); // in ajax web page add_script has the same effect as add_ready_script																	   // but is executed BEFORE all 'ready_scripts'
																						   // but is executed BEFORE all 'ready_scripts'
			}
		}
		break;
		
		case 'save_dashboard':
		$sDashboardId = utils::ReadParam('dashboard_id', '', false, 'raw_data');
		$aParams = array();
		$aParams['layout_class'] = utils::ReadParam('layout_class', '');
		$aParams['title'] = utils::ReadParam('title', '', false, 'raw_data');
		$aParams['auto_reload'] = utils::ReadParam('auto_reload', false);
		$aParams['auto_reload_sec'] = utils::ReadParam('auto_reload_sec', 300);
		$aParams['cells'] = utils::ReadParam('cells', array(), false, 'raw_data');
		$oDashboard = new RuntimeDashboard($sDashboardId);
		$oDashboard->FromParams($aParams);
		$oDashboard->Save();
		// trigger a reload of the current page since the dashboard just changed
		$oPage->add_ready_script(
<<<EOF
	var sLocation = new String(window.location.href);
	var sNewLocation = sLocation.replace('&edit=1', '');
	sNewLocation = sLocation.replace(/#(.?)$/, ''); // Strips everything after the hash, since IF the URL does not change AND contains a hash, then Chrome does not reload the page
	window.location.href = sNewLocation;
EOF
		);
		$oPage->add_ready_script("sLocation = new String(window.location.href); window.location.href=sLocation.replace('&edit=1', '');"); // reloads the page, doing a GET even if we arrived via a POST
		break;

		case 'revert_dashboard':
		$sDashboardId = utils::ReadParam('dashboard_id', '', false, 'raw_data');
		$oDashboard = new RuntimeDashboard($sDashboardId);
		$oDashboard->Revert();
		
		// trigger a reload of the current page since the dashboard just changed
		$oPage->add_ready_script("window.location.href=window.location.href;"); // reloads the page, doing a GET even if we arrived via a POST
		break;
		
		case 'render_dashboard':
		$sDashboardId = utils::ReadParam('dashboard_id', '', false, 'raw_data');
		$aParams = array();
		$aParams['layout_class'] = utils::ReadParam('layout_class', '');
		$aParams['title'] = utils::ReadParam('title', '', false, 'raw_data');
		$aParams['cells'] = utils::ReadParam('cells', array(), false, 'raw_data');
		$aParams['auto_reload'] = utils::ReadParam('auto_reload', false);
		$aParams['auto_reload_sec'] = utils::ReadParam('auto_reload_sec', 300);
		$oDashboard = new RuntimeDashboard($sDashboardId);
		$oDashboard->FromParams($aParams);
		$oDashboard->Render($oPage, true /* bEditMode */);
		break;
		
		case 'dashlet_creation_dlg':
		$sOQL = utils::ReadParam('oql', '', false, 'raw_data');
		RuntimeDashboard::GetDashletCreationDlgFromOQL($oPage, $sOQL);
		break;

		case 'add_dashlet':
		$oForm = RuntimeDashboard::GetDashletCreationForm();
		$aValues = $oForm->ReadParams();
		
		$sDashletClass = $aValues['dashlet_class'];
		$sMenuId = $aValues['menu_id'];
		
		if (is_subclass_of($sDashletClass, 'Dashlet'))
		{
			$oDashlet = new $sDashletClass(new ModelReflectionRuntime(), 0);
			$oDashlet->FromParams($aValues);

			ApplicationMenu::LoadAdditionalMenus();
			$index = ApplicationMenu::GetMenuIndexById($sMenuId);
			$oMenu = ApplicationMenu::GetMenuNode($index);
			$oMenu->AddDashlet($oDashlet);
			// navigate to the dashboard page
			if ($aValues['open_editor'])
			{
				$oPage->add_ready_script("window.location.href='".addslashes(utils::GetAbsoluteUrlAppRoot().'pages/UI.php?c[menu]='.urlencode($sMenuId))."&edit=1';"); // reloads the page, doing a GET even if we arrived via a POST
			}
		}
		break;
		
		case 'shortcut_list_dlg':
		$sOQL = utils::ReadParam('oql', '', false, 'raw_data');
		$sTableSettings = utils::ReadParam('table_settings', '', false, 'raw_data');
		ShortcutOQL::GetCreationDlgFromOQL($oPage, $sOQL, $sTableSettings);
		break;
		
		case 'shortcut_list_create':
		$oForm = ShortcutOQL::GetCreationForm();
		$aValues = $oForm->ReadParams();

		$oAppContext = new ApplicationContext();
		$aContext = $oAppContext->GetAsHash();
		$sContext = serialize($aContext);
		
		$oShortcut = MetaModel::NewObject("ShortcutOQL");
		$oShortcut->Set('user_id', UserRights::GetUserId());
		$oShortcut->Set("context", $sContext);
		$oShortcut->Set("name", $aValues['name']);
		$oShortcut->Set("oql", $aValues['oql']);
		$iAutoReload = (int)$aValues['auto_reload_sec'];
		if (($aValues['auto_reload']) && ($iAutoReload > 0))
		{
			$oShortcut->Set("auto_reload_sec", max(MetaModel::GetConfig()->Get('min_reload_interval'), $iAutoReload));
			$oShortcut->Set("auto_reload", 'custom');
		}
		$iId = $oShortcut->DBInsertNoReload();

		$oShortcut->CloneTableSettings($aValues['table_settings']);

		// Add the menu node in the right place
		//
		// Mmmm... already done because the newly created menu is read from the DB
		//         as soon as we invoke DisplayMenu 

		// Refresh the menu pane
		$aExtraParams = array();
		ApplicationMenu::DisplayMenu($oPage, $aExtraParams);
		break;

		case 'shortcut_rename_dlg':
		$oSearch = new DBObjectSearch('Shortcut');
		$aShortcuts = utils::ReadMultipleSelection($oSearch);
		$iShortcut = $aShortcuts[0];
		$oShortcut = MetaModel::GetObject('Shortcut', $iShortcut);
		$oShortcut->StartRenameDialog($oPage);
		break;

		case 'shortcut_rename_go':
		$iShortcut = utils::ReadParam('id', 0);
		$oShortcut = MetaModel::GetObject('Shortcut', $iShortcut);

		$sName = utils::ReadParam('attr_name', '', false, 'raw_data');
		if (strlen($sName) > 0)
		{
			$oShortcut->Set('name', $sName);
			$oShortcut->DBUpdate();
			$oPage->add_ready_script('window.location.reload();');
		}
		
		break;

		case 'shortcut_delete_go':
		$oSearch = new DBObjectSearch('Shortcut');
		$oSearch->AddCondition('user_id', UserRights::GetUserId(), '=');
		$aShortcuts = utils::ReadMultipleSelection($oSearch);
		foreach ($aShortcuts as $iShortcut)
		{
			$oShortcut = MetaModel::GetObject('Shortcut', $iShortcut);
			$oShortcut->DBDelete();
			$oPage->add_ready_script('window.location.reload();');
		}
		break;

		case 'export_dashboard':
		$sMenuId = utils::ReadParam('id', '', false, 'raw_data');
		ApplicationMenu::LoadAdditionalMenus();
		$index = ApplicationMenu::GetMenuIndexById($sMenuId);
		$oMenu = ApplicationMenu::GetMenuNode($index);
		if ($oMenu instanceof DashboardMenuNode)
		{
			$oDashboard = $oMenu->GetDashboard();

			$oPage->TrashUnexpectedOutput();
			$oPage->SetContentType('text/xml');
			$oPage->SetContentDisposition('attachment', $oMenu->GetLabel().'.xml');
			$oPage->add($oDashboard->ToXml());
		}
		break;
		
		case 'import_dashboard':
		$sMenuId = utils::ReadParam('id', '', false, 'raw_data');
		ApplicationMenu::LoadAdditionalMenus();
		$index = ApplicationMenu::GetMenuIndexById($sMenuId);
		$oMenu = ApplicationMenu::GetMenuNode($index);
		$aResult = array('error' => '');
		try
		{
			if ($oMenu instanceof DashboardMenuNode)
			{
				$oDoc = utils::ReadPostedDocument('dashboard_upload_file');
				$oDashboard = $oMenu->GetDashboard();
				$oDashboard->FromXml($oDoc->GetData());
				$oDashboard->Save();
			}
			else
			{
				$aResult['error'] = 'Dashboard id="'.$sMenuId.'" not found.';
			}
		}
		catch(DOMException $e)
		{
			$aResult = array('error' => Dict::S('UI:Error:InvalidDashboardFile'));
		}
		catch(Exception $e)
		{
			$aResult = array('error' => $e->getMessage());
		}
		$oPage->add(json_encode($aResult));
		break;
		
		case 'about_box':
		$oPage->SetContentType('text/html');

		$sDialogTitle = addslashes(Dict::S('UI:About:Title'));
		$oPage->add_ready_script(
<<<EOF
$('#about_box').dialog({
	width: 700,
	modal: true,
	title: '$sDialogTitle',
	close: function() { $(this).remove(); }
});
$("#collapse_support_details").click(function() {
	$("#support_details").slideToggle('normal');
	$("#collapse_support_details").toggleClass('open');
});
$('#support_details').toggle();
EOF
		);
		$sVersionString = Dict::Format('UI:iTopVersion:Long', ITOP_VERSION, ITOP_REVISION, ITOP_BUILD_DATE);
		$sMySQLVersion = CMDBSource::GetDBVersion();
		$sPHPVersion = phpversion();
		$sOSVersion = PHP_OS;
		$sWebServerVersion = $_SERVER["SERVER_SOFTWARE"];
		$sModules = implode(', ', get_loaded_extensions());

		// Get the datamodel directory
		$oFilter = DBObjectSearch::FromOQL('SELECT ModuleInstallation WHERE name="datamodel"');
		$oSet = new DBObjectSet($oFilter, array('installed' => false)); // Most recent first
		$oLastInstall = $oSet->Fetch();
		$sLastInstallDate = $oLastInstall->Get('installed');
		$sDataModelVersion = $oLastInstall->Get('version');
		$aDataModelInfo = json_decode($oLastInstall->Get('comment'), true);
		$sDataModelSourceDir = $aDataModelInfo['source_dir'];

		require_once(APPROOT.'setup/runtimeenv.class.inc.php');
		$sCurrEnv = utils::GetCurrentEnvironment();
		$oRuntimeEnv = new RunTimeEnvironment($sCurrEnv);
		$aSearchDirs = array(APPROOT.$sDataModelSourceDir);
		if (file_exists(APPROOT.'extensions'))
		{
			$aSearchDirs[] = APPROOT.'extensions';
		}
		$aAvailableModules = $oRuntimeEnv->AnalyzeInstallation(MetaModel::GetConfig(), $aSearchDirs);

		require_once(APPROOT.'setup/setuputils.class.inc.php');
		$aLicenses = SetupUtils::GetLicenses();

		$aItopSettings = array('cron_max_execution_time', 'timezone');
		$aPHPSettings = array('memory_limit', 'max_execution_time', 'upload_max_filesize', 'post_max_size');
		$aMySQLSettings = array('max_allowed_packet', 'key_buffer_size', 'query_cache_size');
		$aMySQLStatuses = array('Key_read_requests', 'Key_reads');

		if (extension_loaded('suhosin'))
		{
			$aPHPSettings[] = 'suhosin.post.max_vars';
			$aPHPSettings[] = 'suhosin.get.max_value_length';
		}

		$aMySQLVars = array();
		foreach (CMDBSource::QueryToArray('SHOW VARIABLES') as $aRow)
		{
			$aMySQLVars[$aRow['Variable_name']] = $aRow['Value'];
		}

		$aMySQLStats = array();
		foreach (CMDBSource::QueryToArray('SHOW GLOBAL STATUS') as $aRow)
		{
			$aMySQLStats[$aRow['Variable_name']] = $aRow['Value'];
		}

		// Display
		//
		$oPage->add("<div id=\"about_box\">");
		$oPage->add('<div style="margin-left: 120px;">');
		$oPage->add('<table>');
		$oPage->add('<tr>');
		$oPage->add('<td><a href="http://www.combodo.com" title="www.combodo.com" target="_blank" style="background: none;"><img src="../images/logo-combodo.png" style="float: right;"/></a></td>');
		$oPage->add('<td style="padding-left: 20px;">');
		$oPage->add($sVersionString.'<br/>');
		$oPage->add(Dict::S('UI:About:DataModel').': '.$sDataModelVersion.'<br/>');
		$oPage->add('MySQL: '.$sMySQLVersion.'<br/>');
		$oPage->add('PHP: '.$sPHPVersion.'<br/>');
		$oPage->add('</td>');
		$oPage->add('</tr>');
		$oPage->add('</table>');
		$oPage->add("</div>");

		$oPage->add("<div>");
		$oPage->add('<fieldset>');
		$oPage->add('<legend>'.Dict::S('UI:About:Licenses').'</legend>');
		$oPage->add('<ul style="margin: 0; font-size: smaller;">');
		foreach($aLicenses as $index => $oLicense)
		{
			$oPage->add('<li><b>'.$oLicense->product.'</b>, &copy; '.$oLicense->author.' is licensed under the <b>'.$oLicense->license_type.' license</b>. (<a id="toggle_'.$index.'" class="CollapsibleLabel" style="cursor:pointer;">Details</a>)');
			$oPage->add('<div id="license_'.$index.'" class="license_text" style="display:none;overflow:auto;max-height:10em;font-size:small;border:1px #696969 solid;margin-bottom:1em; margin-top:0.5em;padding:0.5em;">'.$oLicense->text.'</div>');
			$oPage->add_ready_script('$("#toggle_'.$index.'").click( function() { $("#license_'.$index.'").slideToggle("normal"); } );');
		}
		$oPage->add('</ul>');
		$oPage->add('</fieldset>');
		$oPage->add("</div>");

		$oPage->add('<fieldset>');
		$oPage->add('<legend>'.Dict::S('UI:About:Modules').'</legend>');
		//$oPage->add(print_r($aAvailableModules, true));
		$oPage->add("<div style=\"height: 150px; overflow: auto; font-size: smaller;\">");
		$oPage->add('<ul style="margin: 0;">');
		foreach ($aAvailableModules as $sModuleId => $aModuleData)
		{
			if ($sModuleId == '_Root_') continue;
			if (!$aModuleData['visible']) continue;
			if ($aModuleData['version_db'] == '') continue;
			$oPage->add('<li>'.$aModuleData['label'].' ('.$aModuleData['version_db'].')</li>');
		}
		$oPage->add('</ul>');
		$oPage->add("</div>");
		$oPage->add('</fieldset>');


		// MUST NOT be localized, as the information given here will be sent to the support
		$oPage->add("<a id=\"collapse_support_details\" class=\"CollapsibleLabel\" href=\"#\">".Dict::S('UI:About:Support')."</a></br>\n");
		$oPage->add("<div id=\"support_details\">");
		$oPage->add('<textarea readonly style="width: 660px; height: 150px; font-size: smaller;">');
		$oPage->add("===== begin =====\n");
		$oPage->add('iTopVersion: '.ITOP_VERSION."\n");
		$oPage->add('iTopBuild: '.ITOP_REVISION."\n");
		$oPage->add('iTopBuildDate: '.ITOP_BUILD_DATE."\n");
		$oPage->add('DataModelVersion: '.$sDataModelVersion."\n");
		$oPage->add('MySQLVersion: '.$sMySQLVersion."\n");
		$oPage->add('PHPVersion: '. $sPHPVersion."\n");
		$oPage->add('OSVersion: '.$sOSVersion."\n");
		$oPage->add('WebServerVersion: '.$sWebServerVersion."\n");
		$oPage->add('PHPModules: '.$sModules."\n");
		foreach ($aItopSettings as $siTopVar)
		{
			$oPage->add('ItopSetting/'.$siTopVar.': '.MetaModel::GetConfig()->Get($siTopVar)."\n");
		}
		foreach ($aPHPSettings as $sPHPVar)
		{
			$oPage->add('PHPSetting/'.$sPHPVar.': '.ini_get($sPHPVar)."\n");
		}
		foreach ($aMySQLSettings as $sMySQLVar)
		{
			$oPage->add('MySQLSetting/'.$sMySQLVar.': '.$aMySQLVars[$sMySQLVar]."\n");
		}
		foreach ($aMySQLStatuses as $sMySQLStatus)
		{
			$oPage->add('MySQLStatus/'.$sMySQLStatus.': '.$aMySQLStats[$sMySQLStatus]."\n");
		}

		$oPage->add('InstallDate: '.$sLastInstallDate."\n");
		$oPage->add('InstallPath: '.APPROOT."\n");
		foreach ($aAvailableModules as $sModuleId => $aModuleData)
		{
			if ($sModuleId == '_Root_') continue;
			if ($aModuleData['version_db'] == '') continue;
			$oPage->add('InstalledModule/'.$sModuleId.': '.$aModuleData['version_db']."\n");
		}

		$oPage->add('===== end =====');
		$oPage->add('</textarea>');
		$oPage->add("</div>");

		$oPage->add("</div>");
		break;
		
		case 'history':
		$oPage->SetContentType('text/html');
		$id = (int)utils::ReadParam('id', 0);
		$iStart = (int)utils::ReadParam('start', 0);
		$iCount = (int)utils::ReadParam('count', MetaModel::GetConfig()->Get('max_history_length', '50'));
		$oObj = MetaModel::GetObject($sClass, $id);
		$oObj->DisplayBareHistory($oPage, false, $iCount, $iStart);
		$oPage->add_ready_script("$('#history table.listResults').tableHover(); $('#history table.listResults').tablesorter( { widgets: ['myZebra', 'truncatedList']} );");
		break;

		case 'history_from_filter':
		$oPage->SetContentType('text/html');
		$oHistoryFilter = CMDBSearchFilter::unserialize($sFilter);
		$iStart = (int)utils::ReadParam('start', 0);
		$iCount = (int)utils::ReadParam('count', MetaModel::GetConfig()->Get('max_history_length', '50'));
		$oBlock = new HistoryBlock($oHistoryFilter, 'table', false);
		$oBlock->SetLimit($iCount, $iStart);
		$oBlock->Display($oPage, 'history');
		$oPage->add_ready_script("$('#history table.listResults').tableHover(); $('#history table.listResults').tablesorter( { widgets: ['myZebra', 'truncatedList']} );");
		break;

		case 'full_text_search':
		$aFullTextNeedles = utils::ReadParam('needles', array(), false, 'raw_data');
		$sFullText = trim(implode(' ', $aFullTextNeedles));
		$sClassName = utils::ReadParam('class', '');
		$iCount = utils::ReadParam('count', 0);
		$iCurrentPos = utils::ReadParam('position', 0);
		$iTune = utils::ReadParam('tune', 0);
		if (empty($sFullText))
		{
			$oPage->p(Dict::S('UI:Search:NoSearch'));
			break;
		}

		// Search in full text mode in all the classes
		$aMatches = array();

		// Build the ordered list of classes to search into
		//
		if (empty($sClassName))
		{
			$aSearchClasses = MetaModel::GetClasses('searchable');					
		}
		else
		{
			// Search is limited to a given class and its subclasses
			$aSearchClasses = MetaModel::EnumChildClasses($sClassName, ENUM_CHILD_CLASSES_ALL);
		}
		// Skip abstract classes, since we search in all the child classes anyway
		foreach($aSearchClasses as $idx => $sClass)
		{
			if (MetaModel::IsAbstract($sClass))
			{
				unset($aSearchClasses[$idx]);
			}
		}

		$sMaxChunkDuration = MetaModel::GetConfig()->Get('full_text_chunk_duration');
		$aAccelerators = MetaModel::GetConfig()->Get('full_text_accelerators');

		foreach (array_reverse($aAccelerators) as $sClass => $aRestriction)
		{
			$bSkip = false;
			$iPos = array_search($sClass, $aSearchClasses);
			if ($iPos !== false)
			{
				unset($aSearchClasses[$iPos]);
			}
			else
			{
				$bSkip = true;
			}
			$bSkip |= array_key_exists('skip', $aRestriction) ? $aRestriction['skip'] : false ;
			if (!in_array($sClass, $aSearchClasses))
			if ($sClass == $sClassName)
			{
				// Class explicitely requested, do NOT skip it
				// beware: there may not be a 'query' defined for a skipped class !
				$bSkip = false;
			}
			if (!$bSkip)
			{
				// NOT skipped, add the class to the list of classes to search into
				if (array_key_exists('query', $aRestriction))
				{
					array_unshift($aSearchClasses, $aRestriction['query']);
				}
				else
				{
					// No accelerator query
					array_unshift($aSearchClasses, $sClassName);
				}
			}
		}

		$aSearchClasses = array_values($aSearchClasses); // renumbers the array starting from zero, removing the missing indexes
		$fStarted = microtime(true);
		$iFoundInThisRound = 0;
		for($iPos = $iCurrentPos; $iPos < count($aSearchClasses) ; $iPos++)
		{
			if ($iFoundInThisRound && (microtime(true) - $fStarted >= $sMaxChunkDuration))
			{
				break;
			}

			$sClassSpec = $aSearchClasses[$iPos];
			if (substr($sClassSpec, 0, 7) == 'SELECT ')
			{
				$oFilter = DBObjectSearch::FromOQL($sClassSpec);
				$sClassName = $oFilter->GetClass();
				$sNeedleFormat = isset($aAccelerators[$sClassName]['needle']) ? $aAccelerators[$sClassName]['needle'] : '%$needle$%';
				$sNeedle = str_replace('$needle$', $sFullText, $sNeedleFormat);
				$aParams = array('needle' => $sNeedle);
			}
			else
			{
				$sClassName = $sClassSpec;
				$oFilter = new DBObjectSearch($sClassName);
				$aParams = array();

				foreach($aFullTextNeedles as $sSearchText)
				{
					$oFilter->AddCondition_FullText($sSearchText);
				}
			}
			// Skip abstract classes
			if (MetaModel::IsAbstract($sClassName)) continue;

			if ($iTune > 0)
			{
				$fStartedClass = microtime(true);
			}
			$oSet = new DBObjectSet($oFilter, array(), $aParams);
			if (array_key_exists($sClassName, $aAccelerators) && array_key_exists('attributes', $aAccelerators[$sClassName]))
			{
				$oSet->OptimizeColumnLoad(array($oFilter->GetClassAlias() => $aAccelerators[$sClassName]['attributes']));
			}

			$sFullTextJS = addslashes($sFullText);
			$bEnableEnlarge =  array_key_exists($sClassName, $aAccelerators) && array_key_exists('query', $aAccelerators[$sClassName]);
			if (array_key_exists($sClassName, $aAccelerators) && array_key_exists('enable_enlarge', $aAccelerators[$sClassName]))
			{
				$bEnableEnlarge &= $aAccelerators[$sClassName]['enable_enlarge'];
			}
			$sEnlargeTheSearch =
<<<EOF
			$('.search-class-$sClassName button').attr('disabled', 'disabled');

			$('.search-class-$sClassName h2').append('&nbsp;<img id="indicator" src="../images/indicator.gif">');
			var oParams = {operation: 'full_text_search_enlarge', class: '$sClassName', text: '$sFullTextJS'};
			$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php', oParams, function(data) {
				$('.search-class-$sClassName').html(data);
			});
EOF
			;

			
			$sEnlargeButton = '';
			if ($bEnableEnlarge)
			{
				$sEnlargeButton = "&nbsp;<button onclick=\"".htmlentities($sEnlargeTheSearch, ENT_QUOTES, 'UTF-8')."\">".Dict::S('UI:Search:Enlarge')."</button>";
			}
			if ($oSet->Count() > 0)
			{
				$aLeafs = array();
				while($oObj = $oSet->Fetch())
				{
					if (get_class($oObj) == $sClassName)
					{
						$aLeafs[] = $oObj->GetKey();
						$iFoundInThisRound ++; 
					}
				}
				$oLeafsFilter = new DBObjectSearch($sClassName);
				if (count($aLeafs) > 0)
				{
					$iCount += count($aLeafs);
					$oPage->add("<div class=\"search-class-result search-class-$sClassName\">\n");
					$oPage->add("<div class=\"page_header\">\n");
					if (array_key_exists($sClassName, $aAccelerators))
					{
						$oPage->add("<h2>".MetaModel::GetClassIcon($sClassName)."&nbsp;<span class=\"hilite\">".Dict::Format('UI:Search:Count_ObjectsOf_Class_Found', count($aLeafs), Metamodel::GetName($sClassName)).$sEnlargeButton."</h2>\n");
					}
					else
					{
						$oPage->add("<h2>".MetaModel::GetClassIcon($sClassName)."&nbsp;<span class=\"hilite\">".Dict::Format('UI:Search:Count_ObjectsOf_Class_Found', count($aLeafs), Metamodel::GetName($sClassName))."</h2>\n");
					}
					$oPage->add("</div>\n");
					$oLeafsFilter->AddCondition('id', $aLeafs, 'IN');
					$oBlock = new DisplayBlock($oLeafsFilter, 'list', false);
					$sBlockId = 'global_search_'.$sClassName;
					$oPage->add('<div id="'.$sBlockId.'">');
					$oBlock->RenderContent($oPage, array('table_id' => $sBlockId, 'currentId' => $sBlockId));
					$oPage->add("</div>\n");
					$oPage->add("</div>\n");
					$oPage->p('&nbsp;'); // Some space ?
				}
			}
			else if (array_key_exists($sClassName, $aAccelerators))
			{
				$oPage->add("<div class=\"search-class-result search-class-$sClassName\">\n");
				$oPage->add("<div class=\"page_header\">\n");
				$oPage->add("<h2>".MetaModel::GetClassIcon($sClassName)."&nbsp;<span class=\"hilite\">".Dict::Format('UI:Search:Count_ObjectsOf_Class_Found', 0, Metamodel::GetName($sClassName)).$sEnlargeButton."</h2>\n");
				$oPage->add("</div>\n");
				$oPage->add("</div>\n");
				$oPage->p('&nbsp;'); // Some space ?
			}
			if ($iTune > 0)
			{
				$fDurationClass = microtime(true) - $fStartedClass;
				$oPage->add_script("oTimeStatistics.$sClassName = $fDurationClass;");
			}
		}
		if ($iPos < count($aSearchClasses))
		{
			$sJSNeedle = json_encode($aFullTextNeedles);
			$oPage->add_ready_script(
<<<EOF
				var oParams = {operation: 'full_text_search', position: $iPos, needles: $sJSNeedle, count: $iCount, tune: $iTune};
				$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php', oParams, function(data) {
					$('#full_text_results').append(data);
				});
EOF
			);
		}
		else
		{
			// We're done
			$oPage->add_ready_script(
<<<EOF
$('#full_text_indicator').hide();
$('#full_text_progress,#full_text_progress_placeholder').hide(500);
EOF
			);

			if ($iTune > 0)
			{
				$oPage->add_ready_script(
<<<EOF
				var sRes = '<h4>Search statistics (tune = 1)</h4><table>';
				sRes += '<thead><tr><th>Class</th><th>Time</th></tr></thead>';
				sRes += '<tbody>';
				var fTotal = 0;
				for (var sClass in oTimeStatistics)
				{
					fTotal = fTotal + oTimeStatistics[sClass];
					fRounded = Math.round(oTimeStatistics[sClass] * 1000) / 1000;
					sRes += '<tr><td>' + sClass + '</td><td>' + fRounded + '</td></tr>';
				}
				
				fRoundedTotal = Math.round(fTotal * 1000) / 1000;
				sRes += '<tr><td><b>Total</b></td><td><b>' + fRoundedTotal + '</b></td></tr>';
				sRes += '</tbody>';
				sRes += '</table>';
				$('#full_text_results').append(sRes);
EOF
				);
			}

			if ($iCount == 0)
			{
				$sFullTextSummary = addslashes(Dict::S('UI:Search:NoObjectFound'));
				$oPage->add_ready_script("$('#full_text_results').append('<div id=\"no_object_found\">$sFullTextSummary</div>');");
			}
		}
		break;

		case 'full_text_search_enlarge':
		$sFullText = trim(utils::ReadParam('text', '', false, 'raw_data'));
		$sClass = trim(utils::ReadParam('class', ''));
		$iTune = utils::ReadParam('tune', 0);

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

		$oFilter = new DBObjectSearch($sClass);
		foreach($aFullTextNeedles as $sSearchText)
		{
			$oFilter->AddCondition_FullText($sSearchText);
		}
		$oSet = new DBObjectSet($oFilter);
		$oPage->add("<div class=\"page_header\">\n");
		$oPage->add("<h2>".MetaModel::GetClassIcon($sClass)."&nbsp;<span class=\"hilite\">".Dict::Format('UI:Search:Count_ObjectsOf_Class_Found', $oSet->Count(), Metamodel::GetName($sClass))."</h2>\n");
		$oPage->add("</div>\n");
		if ($oSet->Count() > 0)
		{
			$aLeafs = array();
			while($oObj = $oSet->Fetch())
			{
				if (get_class($oObj) == $sClass)
				{
					$aLeafs[] = $oObj->GetKey();
				}
			}
			$oLeafsFilter = new DBObjectSearch($sClass);
			if (count($aLeafs) > 0)
			{
				$oLeafsFilter->AddCondition('id', $aLeafs, 'IN');
				$oBlock = new DisplayBlock($oLeafsFilter, 'list', false);
				$sBlockId = 'global_search_'.$sClass;
				$oPage->add('<div id="'.$sBlockId.'">');
				$oBlock->RenderContent($oPage, array('table_id' => $sBlockId, 'currentId' => $sBlockId));
				$oPage->add('</div>');
				$oPage->P('&nbsp;'); // Some space ?
				// Hide "no object found"
				$oPage->add_ready_script('$("#no_object_found").hide();');
			}
		}
		$oPage->add_ready_script(
<<<EOF
$('#full_text_indicator').hide();
$('#full_text_progress,#full_text_progress_placeholder').hide(500);
EOF
		);
		break;

		case 'xlsx_export_dialog':
		$sFilter = utils::ReadParam('filter', '', false, 'raw_data');
		$oPage->SetContentType('text/html');
		$oPage->add(
<<<EOF
<style>
 .ui-progressbar {
	position: relative;
}
.progress-label {
	position: absolute;
	left: 50%;
	top: 1px;
	font-size: 11pt;
}
.download-form button {
	display:block;
	margin-left: auto;
	margin-right: auto;
	margin-top: 2em;
}
.ui-progressbar-value {
	background: url(../setup/orange-progress.gif);
}
.progress-bar {
	height: 20px;
}
.statistics > div {
	padding-left: 16px;
	cursor: pointer;
	font-size: 10pt;
	background: url(../images/minus.gif) 0 2px no-repeat;
}				
.statistics > div.closed {
	padding-left: 16px;
	background: url(../images/plus.gif) 0 2px no-repeat;
}
				
.statistics .closed .stats-data {
	display: none;
}
.stats-data td {
	padding-right: 5px;
}
</style>				
EOF
		);
		$oPage->add('<div id="XlsxExportDlg">');
		$oPage->add('<div class="export-options">');
		$oPage->add('<p><input type="checkbox" id="export-advanced-mode"/>&nbsp;<label for="export-advanced-mode">'.Dict::S('UI:CSVImport:AdvancedMode').'</label></p>');
		$oPage->add('<p style="font-size:10pt;margin-left:2em;margin-top:-0.5em;padding-bottom:1em;">'.Dict::S('UI:CSVImport:AdvancedMode+').'</p>');
		$oPage->add('<p><input type="checkbox" id="export-auto-download" checked="checked"/>&nbsp;<label for="export-auto-download">'.Dict::S('ExcelExport:AutoDownload').'</label></p>');
		$oPage->add('</div>');
		$oPage->add('<div class="progress"><p class="status-message">'.Dict::S('ExcelExport:PreparingExport').'</p><div class="progress-bar"><div class="progress-label"></div></div></div>');
		$oPage->add('<div class="statistics"><div class="stats-toggle closed">'.Dict::S('ExcelExport:Statistics').'<div class="stats-data"></div></div></div>');
		$oPage->add('</div>');
		$aLabels = array(
			'dialog_title' => Dict::S('ExcelExporter:ExportDialogTitle'),
			'cancel_button' => Dict::S('UI:Button:Cancel'),
			'export_button' => Dict::S('ExcelExporter:ExportButton'),
			'download_button' => Dict::Format('ExcelExporter:DownloadButton', 'export.xlsx'), //TODO: better name for the file (based on the class of the filter??)
 		);
		$sJSLabels = json_encode($aLabels);
		$sFilter = addslashes($sFilter);
		$sJSPageUrl = addslashes(utils::GetAbsoluteUrlAppRoot().'pages/ajax.render.php');
		$oPage->add_ready_script("$('#XlsxExportDlg').xlsxexporter({filter: '$sFilter', labels: $sJSLabels, ajax_page_url: '$sJSPageUrl'});");
		break;
		
		case 'xlsx_start':
		$sFilter = utils::ReadParam('filter', '', false, 'raw_data');
		$bAdvanced = (utils::ReadParam('advanced', 'false') == 'true');
		$oSearch = DBObjectSearch::unserialize($sFilter);
		
		$oExcelExporter = new ExcelExporter();
		$oExcelExporter->SetObjectList($oSearch);
		//$oExcelExporter->SetChunkSize(10); //Only for testing
		$oExcelExporter->SetAdvancedMode($bAdvanced);
		$sToken = $oExcelExporter->SaveState();
		$oPage->add(json_encode(array('status' => 'ok', 'token' => $sToken)));
		break;
		
		case 'xlsx_run':
		$sMemoryLimit = MetaModel::GetConfig()->Get('xlsx_exporter_memory_limit');
		ini_set('memory_limit', $sMemoryLimit);
		ini_set('max_execution_time', max(300, ini_get('max_execution_time'))); // At least 5 minutes
					
		$sToken = utils::ReadParam('token', '', false, 'raw_data');
		$oExcelExporter = new ExcelExporter($sToken);
		$aStatus = $oExcelExporter->Run();
		$aResults = array('status' => $aStatus['code'], 'percentage' =>  $aStatus['percentage'], 'message' =>  $aStatus['message']);
		if ($aStatus['code'] == 'done')
		{
			$aResults['statistics'] = $oExcelExporter->GetStatistics('html');
		}
		$oPage->add(json_encode($aResults));
		break;
		
		case 'xlsx_download':
		$sToken = utils::ReadParam('token', '', false, 'raw_data');
		$oPage->SetContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		$oPage->SetContentDisposition('attachment', 'export.xlsx');
		$sFileContent = ExcelExporter::GetExcelFileFromToken($sToken);
		$oPage->add($sFileContent);
		ExcelExporter::CleanupFromToken($sToken);
		break;
		
		case 'xlsx_abort':
		// Stop & cleanup an export...
		$sToken = utils::ReadParam('token', '', false, 'raw_data');
		ExcelExporter::CleanupFromToken($sToken);
		break;		
		

		default:
		$oPage->p("Invalid query.");
	}

	$oPage->output();
}
catch (Exception $e)
{
	// note: transform to cope with XSS attacks
	echo htmlentities($e->GetMessage(), ENT_QUOTES, 'utf-8');
	echo "<p>Debug trace: <pre>".$e->getTraceAsString()."</pre></p>\n";
	IssueLog::Error($e->getMessage());
}



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
function DownloadDocument(WebPage $oPage, $sClass, $id, $sAttCode, $sContentDisposition = 'attachment')
{
	try
	{
		$oObj = MetaModel::GetObject($sClass, $id, false, false);
		if (!is_object($oObj))
		{
			throw new Exception("Invalid id ($id) for class '$sClass' - the object does not exist or you are not allowed to view it");
		}
		$oDocument = $oObj->Get($sAttCode);
		if (is_object($oDocument))
		{
			$oPage->TrashUnexpectedOutput();
			$oPage->SetContentType($oDocument->GetMimeType());
			$oPage->SetContentDisposition($sContentDisposition,$oDocument->GetFileName());
			$oPage->add($oDocument->GetData());
		}
	}
	catch(Exception $e)
	{
		$oPage->p($e->getMessage());
	}
}
?>
