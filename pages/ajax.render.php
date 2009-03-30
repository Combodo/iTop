<?php
require_once('../application/application.inc.php');
require_once('../application/webpage.class.inc.php');
require_once('../application/ajaxwebpage.class.inc.php');
require_once('../application/wizardhelper.class.inc.php');
require_once('../application/ui.linkswidget.class.inc.php');

require_once('../application/startup.inc.php');
if (isset($_SERVER['PHP_AUTH_USER']))
{
	// Attempt to login, fails silently
	UserRights::Login($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
}

$oPage = new ajax_page("");
$oPage->no_cache();

$oContext = new UserContext();
$operation = utils::ReadParam('operation', '');
$sFilter = stripslashes(utils::ReadParam('filter', ''));
$sEncoding = utils::ReadParam('encoding', 'serialize');
$sClass = utils::ReadParam('class', 'bizContact');
$sStyle = utils::ReadParam('style', 'list');

switch($operation)
{
	case 'wizard_helper_preview':
	$sJson = utils::ReadParam('json_obj', '', 'post');
	$oWizardHelper = WizardHelper::FromJSON($sJson);
	$oObj = $oWizardHelper->GetTargetObject();
	$oObj->DisplayBareDetails($oPage); 
	break;
	
	case 'wizard_helper':
	$sJson = utils::ReadParam('json_obj', '');
	$oWizardHelper = WizardHelper::FromJSON($sJson);
	$oObj = $oWizardHelper->GetTargetObject(); 
	foreach($oWizardHelper->GetFieldsForDefaultValue() as $sAttCode)
	{
		$oAttDef = MetaModel::GetAttributeDef(get_class($oObj), $sAttCode);
		$oWizardHelper->SetDefaultValue($sAttCode, $oAttDef->GetDefaultValue());
	}
	foreach($oWizardHelper->GetFieldsForAllowedValues() as $sAttCode)
	{
		$oWizardHelper->SetAllowedValuesHtml($sAttCode, "Possible values ($sAttCode)");
	}
	$oPage->add($oWizardHelper->ToJSON());
	break;
		
	case 'ajax':
	if ($sFilter != "")
	{
		if ($sEncoding == 'sibusql')
		{
			$oFilter = CMDBSearchFilter::FromSibusQL($sFilter);
		}
		else
		{
			$oFilter = CMDBSearchFilter::unserialize($sFilter);
		}
		$oDisplayBlock = new DisplayBlock($oFilter, $sStyle, false);
		$oDisplayBlock->RenderContent($oPage);
	}
	else
	{
		$oPage->p("Invalid query (empty filter).");
	}
	break;
	
	case 'details':
	$key = utils::ReadParam('id', 0);
	$oFilter = $oContext->NewFilter($sClass);
	$oFilter->AddCondition('pkey', $key, '=');
	$oDisplayBlock = new DisplayBlock($oFilter, 'details', false);
	$oDisplayBlock->RenderContent($oPage);
	break;
	
	case 'preview':
	$key = utils::ReadParam('id', 0);
	$oFilter = $oContext->NewFilter($sClass);
	$oFilter->AddCondition('pkey', $key, '=');
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
		if ($sEncoding == 'oql')
		{
			$oFilter = CMDBSearchFilter::FromOQL($sFilter);
		}
		else
		{
			$oFilter = CMDBSearchFilter::unserialize($sFilter);
		}
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
	$oFilter = $oContext->NewFilter($sClass);
	$oFilter->AddCondition('pkey', $key, '=');
	$oPage->Add("<p style=\"width:100%; margin-top:-5px;padding:3px; background-color:#33f; color:#fff;\">Object Details</p>\n");
	$oDisplayBlock = new DisplayBlock($oFilter, 'details', false);
	$oDisplayBlock->RenderContent($oPage);
	$oPage->Add("<input type=\"button\" class=\"jqmClose\" value=\" Close \" />\n");
	break;
		
	case 'ui.linkswidget':
	$sClass = utils::ReadParam('sclass', 'bizContact');
	$sAttCode = utils::ReadParam('attCode', 'name');
	$sOrg = utils::ReadParam('org_id', '');
	$sName = utils::ReadParam('q', '');
	$iMaxCount = utils::ReadParam('max', 30);
	UILinksWidget::Autocomplete($oPage, $oContext, $sClass, $sAttCode, $sName, $iMaxCount);
	break;
	
	case 'ui.linkswidget.linkedset':
	$sClass = utils::ReadParam('sclass', 'bizContact');
	$sJSONSet = stripslashes(utils::ReadParam('sset', ''));
	$sExtKeyToMe = utils::ReadParam('sextkeytome', '');
	UILinksWidget::RenderSet($oPage, $sClass, $sJSONSet, $sExtKeyToMe);
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
		}
	} 
	$aAllowedValues = MetaModel::GetAllowedValues_att($sClass, $sAttCode, $aArgs, $sName);
	$iCount = 0;
	foreach($aAllowedValues as $key => $value)
	{
		$oPage->add($value."|".$key."\n");
		if ($iCount++) break;
	}
	break;
	
	case 'link':
	$sClass = utils::ReadParam('sclass', 'logInfra');
	$sAttCode = utils::ReadParam('attCode', 'name');
	//$sOrg = utils::ReadParam('org_id', '');
	$sName = utils::ReadParam('q', '');
	$iMaxCount = utils::ReadParam('max', 30);
	$iCount = 0;
	$oFilter = $oContext->NewFilter($sClass);
	$oFilter->AddCondition($sAttCode, $sName, 'Begins with');
	//$oFilter->AddCondition('org_id', $sOrg);
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
	$oFilter = CMDBSearchFilter::FromSibusQL($sFilter);
	$oSet = new CMDBObjectSet($oFilter);
	while( $oObj = $oSet->fetch())
	{
		$oPage->add('<option title="Here is more information..." value="'.$oObj->GetKey().'">'.$oObj->GetDisplayName().'</option>');
	}
	break;

	default:
	$oPage->p("Invalid query.");
}
$oPage->output();
?>
