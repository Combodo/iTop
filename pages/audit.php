<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Application\UI\Base\Component\Alert\AlertUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Button\ButtonUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Dashlet\DashletContainer;
use Combodo\iTop\Application\UI\Base\Component\Dashlet\DashletFactory;
use Combodo\iTop\Application\UI\Base\Component\DataTable\DataTableUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Panel\Panel;
use Combodo\iTop\Application\UI\Base\Component\Text\Text;
use Combodo\iTop\Application\UI\Base\Component\Title\TitleUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\Dashboard\DashboardColumn;
use Combodo\iTop\Application\UI\Base\Layout\Dashboard\DashboardRow;
use Combodo\iTop\Application\WebPage\CSVPage;
use Combodo\iTop\Application\WebPage\ErrorPage;
use Combodo\iTop\Application\WebPage\iTopWebPage;

/**
 * Adds the context parameters to the audit rule query
 *
 * @param DBSearch $oFilter
 * @param ApplicationContext $oAppContext
 *
 * @throws \CoreException
 * @throws \CoreWarning
 * @throws \Exception
 */
function FilterByContext(DBSearch &$oFilter, ApplicationContext $oAppContext)
{
	$sObjClass = $oFilter->GetClass();		
	$aContextParams = $oAppContext->GetNames();
	$aCallSpec = array($sObjClass, 'MapContextParam');
	if (is_callable($aCallSpec))
	{
		foreach($aContextParams as $sParamName)
		{
			$sValue = $oAppContext->GetCurrentValue($sParamName, null);
			if ($sValue != null)
			{
				$sAttCode = call_user_func($aCallSpec, $sParamName); // Returns null when there is no mapping for this parameter
				if ( ($sAttCode != null) && MetaModel::IsValidAttCode($sObjClass, $sAttCode))
				{
					// Check if the condition points to a hierarchical key
                    $bConditionAdded = false;
					if ($sAttCode == 'id')
					{
						// Filtering on the objects themselves
						$sHierarchicalKeyCode = MetaModel::IsHierarchicalClass($sObjClass);
						
						if ($sHierarchicalKeyCode !== false)
						{
							$oRootFilter = new DBObjectSearch($sObjClass);
							$oRootFilter->AddCondition($sAttCode, $sValue);
							$oFilter->AddCondition_PointingTo($oRootFilter, $sHierarchicalKeyCode, TREE_OPERATOR_BELOW); // Use the 'below' operator by default
							$bConditionAdded = true;
						}
					}
					else
					{
						$oAttDef = MetaModel::GetAttributeDef($sObjClass, $sAttCode);
						$bConditionAdded = false;
						if ($oAttDef->IsExternalKey())
						{
							$sHierarchicalKeyCode = MetaModel::IsHierarchicalClass($oAttDef->GetTargetClass());
							
							if ($sHierarchicalKeyCode !== false)
							{
								$oRootFilter = new DBObjectSearch($oAttDef->GetTargetClass());
								$oRootFilter->AddCondition('id', $sValue);
								$oHKFilter = new DBObjectSearch($oAttDef->GetTargetClass());
								$oHKFilter->AddCondition_PointingTo($oRootFilter, $sHierarchicalKeyCode, TREE_OPERATOR_BELOW); // Use the 'below' operator by default
								$oFilter->AddCondition_PointingTo($oHKFilter, $sAttCode);
								$bConditionAdded = true;
							}
						}
					}
					if (!$bConditionAdded)
					{
						$oFilter->AddCondition($sAttCode, $sValue);
					}
				}
			}
		}
	}
}

/**
 * @param int $iRuleId Audit rule ID
 * @param DBObjectSearch $oDefinitionFilter Created from the audit category's OQL
 * @param ApplicationContext $oAppContext
 *
 * @return mixed
 * @throws \ArchivedObjectException
 * @throws \CoreException
 * @throws \OQLException
 */
function GetRuleResultFilter($iRuleId, $oDefinitionFilter, $oAppContext)
{
	$oRule = MetaModel::GetObject('AuditRule', $iRuleId);
	$sOql = $oRule->Get('query');
	$oRuleFilter = DBObjectSearch::FromOQL($sOql);
	$oRuleFilter->UpdateContextFromUser();
	FilterByContext($oRuleFilter, $oAppContext); // Not needed since this filter is a subset of the definition filter, but may speedup things

	if ($oRule->Get('valid_flag') == 'false')
	{
		// The query returns directly the invalid elements
		$oFilter = $oRuleFilter->Intersect($oDefinitionFilter);
	}
	else
	{
		// The query returns only the valid elements, all the others are invalid
		// Warning : we're generating a `WHERE ID IN`... query, and this could be very slow if there are lots of id !
      	$aValidRows = $oRuleFilter->ToDataArray(array('id'));
		$aValidIds = array();
		foreach($aValidRows as $aRow)
		{
			$aValidIds[] = $aRow['id'];
		}
		/** @var \DBObjectSearch $oFilter */
		$oFilter = $oDefinitionFilter->DeepClone();
		if (count($aValidIds) > 0)
		{
			$aInDefSet = array();
			foreach($oDefinitionFilter->ToDataArray(array('id')) as $aRow)
			{
				$aInDefSet[] = $aRow['id'];
			}
			$aInvalids = array_diff($aInDefSet, $aValidIds);
			if (count($aInvalids) > 0)
			{
				$oFilter->AddConditionForInOperatorUsingParam('id', $aInvalids, true);
			}
			else
			{
				$oFilter->AddCondition('id', 0, '=');
			}
		}
	}
	return $oFilter;
}

try
{
	require_once('../approot.inc.php');
	require_once(APPROOT.'/application/application.inc.php');
	require_once(APPROOT.'/application/startup.inc.php');
	IssueLog::Trace('----- Request: '.utils::GetRequestUri(), LogChannels::WEB_REQUEST);

	$bSelectionAuditRulesByDefault = utils::GetConfig()->Get('audit.enable_selection_landing_page');
	$operation = utils::ReadParam('operation', $bSelectionAuditRulesByDefault ? 'selection' : 'audit');
	$oAppContext = new ApplicationContext();
	
	require_once(APPROOT.'/application/loginwebpage.class.inc.php');
	LoginWebPage::DoLogin(); // Check user rights and prompt if needed
	
	$oP = new iTopWebPage(Dict::S('UI:Audit:Title'));

	switch($operation)
	{
		case 'csv':
		$oP->DisableBreadCrumb();
		// Big result sets cause long OQL that cannot be passed (serialized) as a GET parameter
		// Therefore we don't use the standard "search_oql" operation of UI.php to display the CSV
		$iCategory = utils::ReadParam('category', '');
		$iRuleIndex = utils::ReadParam('rule', 0);
	
		$oAuditCategory = MetaModel::GetObject('AuditCategory', $iCategory);
		$oDefinitionFilter = DBObjectSearch::FromOQL($oAuditCategory->Get('definition_set'));
		$oDefinitionFilter->UpdateContextFromUser();
		FilterByContext($oDefinitionFilter, $oAppContext);
		$oDefinitionSet = new CMDBObjectSet($oDefinitionFilter);
		$oFilter = GetRuleResultFilter($iRuleIndex, $oDefinitionFilter, $oAppContext);
		$oErrorObjectSet = new CMDBObjectSet($oFilter);
		$oAuditRule = MetaModel::GetObject('AuditRule', $iRuleIndex);
		$sFileName = utils::ReadParam('filename', null, true, 'string');
		$bAdvanced = utils::ReadParam('advanced', false);
		$sAdvanced = $bAdvanced ? '&advanced=1' : '';
		
		if ($sFileName != null)
		{
			$oP = new CSVPage("iTop - Export");
			$sCharset = MetaModel::GetConfig()->Get('csv_file_default_charset');
			$sCSVData = cmdbAbstractObject::GetSetAsCSV($oErrorObjectSet, array('localize_values' => true, 'fields_advanced' => $bAdvanced), $sCharset);
			if ($sCharset == 'UTF-8')
			{
				$sOutputData = UTF8_BOM.$sCSVData;
			}
			else
			{
				$sOutputData = $sCSVData;
			}
			if ($sFileName == '')
			{
				// Plain text => Firefox will NOT propose to download the file
				$oP->add_header("Content-type: text/plain; charset=$sCharset");
			}
			else
			{
				$sCSVName = basename($sFileName); // pseudo sanitization, just in case
				// Force the name of the downloaded file, since windows gives precedence to the extension over of the mime type
				$oP->add_header("Content-disposition: attachment; filename=\"$sCSVName\"");
				$oP->add_header("Content-type: text/csv; charset=$sCharset");
			}
			$oP->add($sOutputData);
			$oP->TrashUnexpectedOutput();
			$oP->output();
			exit;
		} else {
			$sTitle = Dict::S('UI:Audit:AuditErrors');
			$oP->SetBreadCrumbEntry('ui-tool-auditerrors', $sTitle, '', '', 'fas fa-stethoscope', iTopWebPage::ENUM_BREADCRUMB_ENTRY_ICON_TYPE_CSS_CLASSES);

			$oBackButton = ButtonUIBlockFactory::MakeIconLink('fas fa-chevron-left', Dict::S('UI:Audit:InteractiveAudit:Back'), "./audit.php?".$oAppContext->GetForLink());
			$oP->AddUiBlock($oBackButton);
			$oP->AddUiBlock(TitleUIBlockFactory::MakeForPage($sTitle.$oAuditRule->Get('description')));

			$sBlockId = 'audit_errors';
			$oP->p("<div id=\"$sBlockId\">");
			$oBlock = DisplayBlock::FromObjectSet($oErrorObjectSet, 'csv', array('show_obsolete_data' => true));
			$oBlock->Display($oP, 1);
			$oP->p("</div>");
			// Adjust the size of the Textarea containing the CSV to fit almost all the remaining space
			$oP->add_ready_script(" $('#1>textarea').height(400);"); // adjust the size of the block			
			$sExportUrl = utils::GetAbsoluteUrlAppRoot()."pages/audit.php?operation=csv&category=".$oAuditCategory->GetKey()."&rule=".$oAuditRule->GetKey();
			$oDownloadButton = ButtonUIBlockFactory::MakeForAlternativePrimaryAction('fas fa-chevron-left', Dict::S('UI:Audit:InteractiveAudit:Back'), "./audit.php?".$oAppContext->GetForLink());

			$oP->add_ready_script("$('a[href*=\"webservices/export.php?expression=\"]').attr('href', '".$sExportUrl."&filename=audit.csv".$sAdvanced."');");
			$oP->add_ready_script("$('#1 :checkbox').removeAttr('onclick').on('click', function() { var sAdvanced = ''; if (this.checked) sAdvanced = '&advanced=1'; window.location.href='$sExportUrl'+sAdvanced; } );");
		}
		break;
						
		case 'errors':
			$sTitle = Dict::S('UI:Audit:AuditErrors');
			$iCategory = utils::ReadParam('category', '');
			$iRuleIndex = utils::ReadParam('rule', 0);

			$oAuditCategory = MetaModel::GetObject('AuditCategory', $iCategory);
			$oDefinitionFilter = DBObjectSearch::FromOQL($oAuditCategory->Get('definition_set'));
			$oDefinitionFilter->UpdateContextFromUser();
			FilterByContext($oDefinitionFilter, $oAppContext);
			$oDefinitionSet = new CMDBObjectSet($oDefinitionFilter);
			$oFilter = GetRuleResultFilter($iRuleIndex, $oDefinitionFilter, $oAppContext);
			$oErrorObjectSet = new CMDBObjectSet($oFilter);
			$oAuditRule = MetaModel::GetObject('AuditRule', $iRuleIndex);
			$sDescription = get_class($oAuditRule).": ".$oAuditRule->GetName();
			$oP->SetBreadCrumbEntry('ui-tool-auditerrors', $sTitle, $sDescription, '', 'fas fa-stethoscope', iTopWebPage::ENUM_BREADCRUMB_ENTRY_ICON_TYPE_CSS_CLASSES);

			$oBackButton = ButtonUIBlockFactory::MakeIconLink('fas fa-chevron-left', Dict::S('UI:Audit:Interactive:Button:Back'), "./audit.php?".$oAppContext->GetForLink());
			$oP->AddUiBlock($oBackButton);
			$oP->AddUiBlock(TitleUIBlockFactory::MakeForPage($sTitle.$oAuditRule->Get('description')));
			$sBlockId = 'audit_errors';
			$oP->p("<div id=\"$sBlockId\">");
			$oBlock = DisplayBlock::FromObjectSet($oErrorObjectSet, 'list', array('show_obsolete_data' => true));
			$oBlock->Display($oP, 1);
			$oP->p("</div>");
			$sExportUrl = utils::GetAbsoluteUrlAppRoot()."pages/audit.php?operation=csv&category=".$oAuditCategory->GetKey()."&rule=".$oAuditRule->GetKey();
			$oP->add_ready_script("$('a[href*=\"pages/UI.php?operation=search\"]').attr('href', '".$sExportUrl."')");
			break;

		case 'selection':
			$oP->SetBreadCrumbEntry('ui-tool-auditselection', Dict::S('UI:Audit:Interactive:Selection:BreadCrumb'), Dict::S('UI:Audit:Interactive:Selection:BreadCrumb+'), '', 'fas fa-stethoscope', iTopWebPage::ENUM_BREADCRUMB_ENTRY_ICON_TYPE_CSS_CLASSES);
			if (UserRights::IsActionAllowed('AuditCategory', UR_ACTION_MODIFY)) {
				$oButton = ButtonUIBlockFactory::MakeLinkNeutral(utils::GetAbsoluteUrlAppRoot()."pages/UI.php?c[menu]=AuditCategories", Dict::S('UI:Audit:Interactive:Button:Configuration'), 'fas fa-wrench');
				$oP->AddUiBlock($oButton);
			}
			$oP->AddUiBlock(TitleUIBlockFactory::MakeForPage(Dict::S('UI:Audit:Interactive:Selection:Title')));
			$oP->AddUiBlock(new Text(Dict::S('UI:Audit:Interactive:Selection:SubTitle')));

			// Header block to select all audit categories
			$oCategoriesSet = new DBObjectSet(new DBObjectSearch('AuditCategory'));
			$iCategoryCount = $oCategoriesSet->Count();

			$oDashboardRow = new DashboardRow();
			$oDashboardRow->AddCSSClass('ibo-audit--dashboard');
			$oDashboardColumn = new DashboardColumn(false, true);
			$oDashboardRow->AddDashboardColumn($oDashboardColumn);
			$oAllCategoriesDashlet = new DashletContainer();
			$oAllCategoriesDashlet
				->AddCSSClasses(['ibo-dashlet--is-inline', 'ibo-dashlet-badge'])
				->AddSubBlock(DashletFactory::MakeForDashletBadge(
					utils::GetAbsoluteUrlAppRoot().'images/icons/icons8-audit.svg',
					utils::GetAbsoluteUrlAppRoot()."pages/audit.php?operation=audit",
					$iCategoryCount,
					Dict::S('UI:Audit:Interactive:Selection:BadgeAll')
				));
			$oDashboardColumn->AddUIBlock($oAllCategoriesDashlet);
			$oP->AddUiBlock($oDashboardRow);

			// Three column layout to display all available audit domains
			$oDashboardRow = new DashboardRow();
			$oDashboardRow
				->AddCSSClass('ibo-audit--dashboard')
				->AddDashboardColumn(new DashboardColumn(false, true))
				->AddDashboardColumn(new DashboardColumn(false, true))
				->AddDashboardColumn(new DashboardColumn(false, true));

			// Fetch all audit domains with at least on linked category
			$oDomainSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT AuditDomain AS domain JOIN lnkAuditCategoryToAuditDomain AS lnk ON lnk.domain_id = domain.id"));
			$oDomainSet->SetOrderBy(array('name' => true));
			$iDomainCnt = 0;
			/** @var AuditDomain $oAuditDomain */
			while($oAuditDomain = $oDomainSet->Fetch()) {
				$sDomainUrl = utils::GetAbsoluteUrlAppRoot()."pages/audit.php?operation=audit&domain=".$oAuditDomain->GetKey();
				$sIconUrl = utils::GetAbsoluteUrlAppRoot().'images/icons/icons8-puzzle.svg';
					/** @var \ormDocument $oImage */
				$oImage = $oAuditDomain->Get('icon');
				if (!$oImage->IsEmpty()) {
					$sIconUrl = $oImage->GetDisplayURL(get_class($oAuditDomain), $oAuditDomain->GetKey(), 'icon');
				}
				$iCategoryCount = $oAuditDomain->Get('categories_list')->Count();
				$oDomainBlock = DashletFactory::MakeForDashletBadge($sIconUrl, $sDomainUrl, $iCategoryCount, $oAuditDomain->Get('name'));
				$oDomainDashlet = new DashletContainer();
				$oDomainDashlet->AddSubBlock($oDomainBlock)->AddCSSClasses(['ibo-dashlet--is-inline', 'ibo-dashlet-badge']);
				$oDashboardRow->GetSubBlocks()[$iDomainCnt % 3]->AddUIBlock($oDomainDashlet); // ;
				$iDomainCnt++;
			}

			$oP->AddUiBlock($oDashboardRow);
			break;

		case 'audit':
		default:
			$sDomainKey = utils::ReadParam('domain', '');
			$sCategories = utils::ReadParam('categories', '', false, utils::ENUM_SANITIZATION_FILTER_STRING);  // May contain commas
			// Default case, full audit
			$oCategoriesSet = new DBObjectSet(new DBObjectSearch('AuditCategory'));
			$sTitle = Dict::S('UI:Audit:Interactive:All:Title');
			$sSubTitle = Dict::S('UI:Audit:Interactive:All:SubTitle');
			$sBreadCrumbLabel = Dict::S('UI:Audit:Interactive:All:BreadCrumb');
			$sBreadCrumbTooltip = Dict::S('UI:Audit:Interactive:All:BreadCrumb+');

			if (!empty($sCategories)) {  // Case with a set of categories
				$oCategoriesSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT AuditCategory WHERE id IN (:categories)", array('categories' => explode(',', $sCategories))));
				$sCategories = implode(", ", $oCategoriesSet->GetColumnAsArray('name'));
				$oCategoriesSet->Rewind();
				$sTitle = Dict::Format('UI:Audit:Interactive:Categories:Title', $sCategories);
				$sSubTitle = Dict::Format('UI:Audit:Interactive:Categories:SubTitle', $oCategoriesSet->Count());
				$sBreadCrumbLabel = Dict::Format('UI:Audit:Interactive:Categories:BreadCrumb', $oCategoriesSet->Count());
				$sBreadCrumbTooltip = Dict::Format('UI:Audit:Interactive:Categories:BreadCrumb+', $sCategories);

			} elseif (!empty($sDomainKey)) {  // Case with a single Domain
				$oAuditDomain = MetaModel::GetObject('AuditDomain', $sDomainKey);
				$oCategoriesSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT AuditCategory AS c JOIN lnkAuditCategoryToAuditDomain AS lnk ON lnk.category_id = c.id WHERE lnk.domain_id = :domain", array('domain' => $oAuditDomain->GetKey())));
				$sDomainName = $oAuditDomain->GetName();
				$sTitle = Dict::Format('UI:Audit:Interactive:Domain:Title', $sDomainName);
				$sSubTitle = Dict::Format('UI:Audit:Interactive:Domain:SubTitle', $sDomainName);
				$sBreadCrumbLabel = Dict::Format('UI:Audit:Interactive:Domain:BreadCrumb', $sDomainName);
				$sBreadCrumbTooltip = Dict::Format('UI:Audit:Interactive:Domain:BreadCrumb+', $sDomainName);
			}

			$oP->SetBreadCrumbEntry('ui-tool-audit', $sBreadCrumbLabel, $sBreadCrumbTooltip, '', 'fas fa-stethoscope', iTopWebPage::ENUM_BREADCRUMB_ENTRY_ICON_TYPE_CSS_CLASSES);
			$oBackButton = ButtonUIBlockFactory::MakeLinkNeutral("./audit.php?".$oAppContext->GetForLink(), Dict::S('UI:Audit:Interactive:Button:Back'), 'fas fa-chevron-left');
			$oP->AddUiBlock($oBackButton);
			$oP->AddUiBlock(TitleUIBlockFactory::MakeForPage($sTitle));
			$oP->AddUiBlock(new Text($sSubTitle));

			$oTotalBlock = DashletFactory::MakeForDashletBadge(utils::GetAbsoluteUrlAppRoot().'images/icons/icons8-audit.svg', '#', 0, Dict::S('UI:Audit:Dashboard:ObjectsAudited'));
			$oErrorBlock = DashletFactory::MakeForDashletBadge(utils::GetAbsoluteUrlAppRoot().'images/icons/icons8-delete.svg', '#', 0, Dict::S('UI:Audit:Dashboard:ObjectsInError'));
			$oWorkingBlock = DashletFactory::MakeForDashletBadge(utils::GetAbsoluteUrlAppRoot().'images/icons/icons8-checkmark.svg', '#', 0, Dict::S('UI:Audit:Dashboard:ObjectsValidated'));

			$aCSSClasses = ['ibo-dashlet--is-inline', 'ibo-dashlet-badge'];

			$oDashletContainerTotal = new DashletContainer();
			$oDashletContainerError = new DashletContainer();
			$oDashletContainerWorking = new DashletContainer();

			$oDashletContainerTotal->AddSubBlock($oTotalBlock)->AddCSSClasses($aCSSClasses);
			$oDashletContainerError->AddSubBlock($oErrorBlock)->AddCSSClasses($aCSSClasses);
			$oDashletContainerWorking->AddSubBlock($oWorkingBlock)->AddCSSClasses($aCSSClasses);

			$oDashboardRow = new DashboardRow();

			$oDashboardColumnTotal = new DashboardColumn(false, true);
			$oDashboardColumnError = new DashboardColumn(false, true);
			$oDashboardColumnWorking = new DashboardColumn(false, true);

			$oDashboardColumnTotal->AddUIBlock($oDashletContainerTotal);
			$oDashboardColumnError->AddUIBlock($oDashletContainerError);
			$oDashboardColumnWorking->AddUIBlock($oDashletContainerWorking);

			$oDashboardRow->AddDashboardColumn($oDashboardColumnTotal);
			$oDashboardRow->AddDashboardColumn($oDashboardColumnError);
			$oDashboardRow->AddDashboardColumn($oDashboardColumnWorking);

			$oDashboardRow->AddCSSClass('ibo-audit--dashboard');

			$oP->AddUiBlock($oDashboardRow);

			$aAuditCategoryPanels = [];
			/** @var AuditCategory $oAuditCategory */
			while ($oAuditCategory = $oCategoriesSet->fetch()) {
				$oAuditCategoryPanelBlock = new Panel($oAuditCategory->GetName());
				$oAuditCategoryPanelBlock->SetIsCollapsible(true);
				// Create toolbar and add it to panel
				$oToolbar = \Combodo\iTop\Application\UI\Base\Component\Toolbar\ToolbarUIBlockFactory::MakeForButton();
				$oAuditCategoryPanelBlock->AddToolbarBlock($oToolbar);
				// Add a button in the above toolbar
				$sAuditCategoryClass = get_class($oAuditCategory);
				if (UserRights::IsActionAllowed($sAuditCategoryClass, UR_ACTION_READ)) {
					$oToolbar->AddSubBlock(ButtonUIBlockFactory::MakeIconLink('fas fa-wrench fa-lg', Dict::S('UI:Audit:ViewRules'), ApplicationContext::MakeObjectUrl($sAuditCategoryClass, $oAuditCategory->GetKey()).'&#ObjectProperties=tab_ClassAuditCategoryAttributerules_list'),);
				}
				$aResults = array();
				try {
					$iCount = 0;
					$oDefinitionFilter = DBObjectSearch::FromOQL($oAuditCategory->Get('definition_set'));
					$oDefinitionFilter->UpdateContextFromUser();
					FilterByContext($oDefinitionFilter, $oAppContext);

					$aObjectsWithErrors = array();
					if (!empty($currentOrganization)) {
						if (MetaModel::IsValidFilterCode($oDefinitionFilter->GetClass(), 'org_id')) {
							$oDefinitionFilter->AddCondition('org_id', $currentOrganization, '=');
						}
					}
					$oDefinitionSet = new CMDBObjectSet($oDefinitionFilter);
					$iCount = $oDefinitionSet->Count();
					$oRulesFilter = new DBObjectSearch('AuditRule');
					$oRulesFilter->AddCondition('category_id', $oAuditCategory->GetKey(), '=');
					$oRulesSet = new DBObjectSet($oRulesFilter);
					while ($oAuditRule = $oRulesSet->fetch()) {
						$aRow = array();
						$aRow['description'] = $oAuditRule->GetName();
						if ($iCount == 0) {
							// nothing to check, really !
							$aRow['nb_errors'] = "<a href=\"audit.php?operation=errors&category=".$oAuditCategory->GetKey()."&rule=".$oAuditRule->GetKey()."\">0</a>";
							$aRow['percent_ok'] = '100.00';
							$aRow['class'] = $oAuditCategory->GetReportColor($iCount, 0);
						} else {
							try {
								$oFilter = GetRuleResultFilter($oAuditRule->GetKey(), $oDefinitionFilter, $oAppContext);
							$aErrors = $oFilter->SelectAttributeToArray('id');
								$iErrorsCount = count($aErrors);
								foreach ($aErrors as $aErrorRow) {
									$aObjectsWithErrors[$aErrorRow['id']] = true;
								}
								$aRow['nb_errors'] = ($iErrorsCount == 0) ? '0' : "<a href=\"?operation=errors&category=".$oAuditCategory->GetKey()."&rule=".$oAuditRule->GetKey()."&".$oAppContext->GetForLink()."\">$iErrorsCount</a> <a href=\"?operation=csv&category=".$oAuditCategory->GetKey()."&rule=".$oAuditRule->GetKey()."&".$oAppContext->GetForLink()."\"><img src=\"" . utils::GetAbsoluteUrlAppRoot() . "images/icons/icons8-export-csv.svg\" class=\"ibo-audit--audit-line--csv-download\"></a>";
								$aRow['percent_ok'] = sprintf('%.2f', 100.0 * (($iCount - $iErrorsCount) / $iCount));
								$aRow['class'] = $oAuditCategory->GetReportColor($iCount, $iErrorsCount);
							}
							catch (Exception $e) {
								$aRow['nb_errors'] = Dict::S('UI:Audit:OqlError');
								$aRow['percent_ok'] = Dict::S('UI:Audit:Error:ValueNA');
								$aRow['class'] = 'red';
								$sMessage = Dict::Format('UI:Audit:ErrorIn_Rule_Reason', $oAuditRule->GetHyperlink(), $e->getMessage());

								$oErrorAlert = AlertUIBlockFactory::MakeForFailure(Dict::S('UI:Audit:ErrorIn_Rule'), $sMessage);
								$oErrorAlert->AddCSSClass('ibo-audit--error-alert');
								$oP->AddUiBlock($oErrorAlert);
							}
						}
						$aResults[] = $aRow;
					}
					$iTotalErrors = count($aObjectsWithErrors);
					$sOverallPercentOk = ($iCount == 0) ? '100.00' : sprintf('%.2f', 100.0 * (($iCount - $iTotalErrors) / $iCount));
					$sClass = $oAuditCategory->GetReportColor($iCount, $iTotalErrors);

					$oTotalBlock->SetCount((int)$oTotalBlock->GetCount() + ($iCount));
					$oErrorBlock->SetCount((int)$oErrorBlock->GetCount() + $iTotalErrors);
					$oWorkingBlock->SetCount((int)$oWorkingBlock->GetCount() + ($iCount - $iTotalErrors));
					$oAuditCategoryPanelBlock->SetSubTitle(Dict::Format('UI:Audit:AuditCategory:Subtitle', $iTotalErrors, $iCount, $sOverallPercentOk));

				}
			catch(Exception $e)
			{
				$sMessage = Dict::Format('UI:Audit:ErrorIn_Category_Reason', $oAuditCategory->GetHyperlink(), utils::HtmlEntities($e->getMessage()));
				$oErrorAlert = AlertUIBlockFactory::MakeForFailure(Dict::S('UI:Audit:ErrorIn_Category'), $sMessage);
				$oErrorAlert->AddCSSClass('ibo-audit--error-alert');
				$oP->AddUiBlock($oErrorAlert);
				continue;
			}

			$oAuditCategoryPanelBlock->SetColorFromColorSemantic($sClass);
			$oAuditCategoryPanelBlock->AddCSSClass('ibo-audit--audit-category--panel');
			$aData = [];
			foreach($aResults as $aRow)
			{
				$aData[] = array(
					'audit_rule' => $aRow['description'],
					'nb_err' => $aRow['nb_errors'],
					'percentage_ok' => $aRow['percent_ok'],
					'@class' => 'ibo-is-'.$aRow['class'].'',
				);
			}

			$aAttribs = array(
				'audit_rule' => array('label' => Dict::S('UI:Audit:HeaderAuditRule'), 'description' => Dict::S('UI:Audit:HeaderAuditRule')),
				'nb_err' => array('label' => Dict::S('UI:Audit:HeaderNbErrors'), 'description' => Dict::S('UI:Audit:HeaderNbErrors')),
				'percentage_ok' => array('label' => Dict::S('UI:Audit:PercentageOk'), 'description' => Dict::S('UI:Audit:PercentageOk')),
			);

			$oAttachmentTableBlock = DataTableUIBlockFactory::MakeForStaticData('', $aAttribs, $aData, null, [], "", array('pageLength' => -1));
			$oAuditCategoryPanelBlock->AddSubBlock($oAttachmentTableBlock);
			$aAuditCategoryPanels[] = $oAuditCategoryPanelBlock;
		}
		foreach ($aAuditCategoryPanels as $oAuditCategoryPanel) {
			$oP->AddUiBlock($oAuditCategoryPanel);
		}
	}
	$oP->output();
}
catch(CoreException $e)
{
	require_once(APPROOT.'/setup/setuppage.class.inc.php');
	$oP = new ErrorPage(Dict::S('UI:PageTitle:FatalError'));
	$oP->add("<h1>".Dict::S('UI:FatalErrorMessage')."</h1>\n");	
	$oP->error(Dict::Format('UI:Error_Details', $e->getHtmlDesc()));	
	$oP->output();

	if (MetaModel::IsLogEnabledIssue())
	{
		if (MetaModel::IsValidClass('EventIssue'))
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

		IssueLog::Error($e->getMessage());
	}

	// For debugging only
	//throw $e;
}
catch(Exception $e)
{
	require_once(APPROOT.'/setup/setuppage.class.inc.php');
	$oP = new ErrorPage(Dict::S('UI:PageTitle:FatalError'));
	$oP->add("<h1>".Dict::S('UI:FatalErrorMessage')."</h1>\n");	
	$oP->error(Dict::Format('UI:Error_Details', $e->getMessage()));	
	$oP->output();

	if (MetaModel::IsLogEnabledIssue())
	{
		if (MetaModel::IsValidClass('EventIssue'))
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

		IssueLog::Error($e->getMessage());
	}
}
