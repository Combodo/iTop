<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\DataTable;

use ApplicationContext;
use ApplicationException;
use appUserPreferences;
use AttributeCaseLog;
use AttributeLinkedSet;
use cmdbAbstractObject;
use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Button\ButtonUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\CollapsibleSection\CollapsibleSection;
use Combodo\iTop\Application\UI\Base\Component\DataTable\StaticTable\FormTable\FormTable;
use Combodo\iTop\Application\UI\Base\Component\DataTable\StaticTable\FormTableRow\FormTableRow;
use Combodo\iTop\Application\UI\Base\Component\DataTable\StaticTable\StaticTable;
use Combodo\iTop\Application\UI\Base\Component\Html\Html;
use Combodo\iTop\Application\UI\Base\Component\Html\HtmlFactory;
use Combodo\iTop\Application\UI\Base\Component\Panel\PanelUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Template\TemplateUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Title\TitleUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Toolbar\ToolbarUIBlockFactory;
use Combodo\iTop\Application\UI\Base\iUIBlock;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;
use Combodo\iTop\Controller\AjaxRenderController;
use DBObjectSet;
use DeprecatedCallsLog;
use Dict;
use DisplayBlock;
use IssueLog;
use LogChannels;
use MenuBlock;
use MetaModel;
use UserRights;
use utils;
use Combodo\iTop\Application\WebPage\WebPage;

/**
 * Class DataTableUIBlockFactory
 *
 * @author Anne-Catherine Cognet <anne-catherine.cognet@combodo.com>
 * @package UIBlockAPI
 * @api
 * @since 3.0.0
 */
class DataTableUIBlockFactory extends AbstractUIBlockFactory
{
	/** @inheritDoc */
	public const TWIG_TAG_NAME = 'UIDataTable';
	/** @inheritDoc */
	public const UI_BLOCK_CLASS_NAME = DataTable::class;

	/**
	 * If inside an iTop object, you can use {@see cmdbAbstractObject::DisplaySet()}
	 *
	 * @api
	 * @param WebPage $oPage
	 * @param string $sListId
	 * @param \DBObjectSet $oSet
	 * @param array $aExtraParams See possible values in {@see self::RenderDataTable()}
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Layout\UIContentBlock
	 * @throws \ApplicationException
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 * @throws \ReflectionException
	 */
	public static function MakeForResult(WebPage $oPage, string $sListId, DBObjectSet $oSet, $aExtraParams = array())
	{
		$oDataTable = DataTableUIBlockFactory::MakeForRendering($sListId, $oSet, $aExtraParams);
		if ($oPage->IsPrintableVersion()) {
			$oDataTable->AddOption('printVersion', true);
		}

		return self::RenderDataTable($oDataTable, DisplayBlock::ENUM_STYLE_LIST, $oPage, $sListId, $oSet, $aExtraParams);
	}

	/**
	 * If inside an iTop object, you can use {@see cmdbAbstractObject::DisplaySet()}
	 *
	 * @api
	 * @param WebPage $oPage
	 * @param string $sListId
	 * @param DBObjectSet $oSet
	 * @param array $aExtraParams See possible values in {@see self::RenderDataTable()}
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Layout\UIContentBlock
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 * @throws \ReflectionException
	 */
	public static function MakeForObject(WebPage $oPage, string $sListId, DBObjectSet $oSet, $aExtraParams = array())
	{
		$oDataTable = DataTableUIBlockFactory::MakeForRendering($sListId, $oSet, $aExtraParams);
		if ($oPage->IsPrintableVersion()) {
			$oDataTable->AddOption('printVersion', true);
		}

		return self::RenderDataTable($oDataTable, DisplayBlock::ENUM_STYLE_LIST_IN_OBJECT, $oPage, $sListId, $oSet, $aExtraParams);
	}

	/**
	 * @param \Combodo\iTop\Application\UI\Base\Component\DataTable\DataTable $oDataTable
	 * @param string $sStyle
	 * @param WebPage $oPage
	 * @param string $sListId
	 * @param \DBObjectSet $oSet
	 * @param array $aExtraParams example keys used in this method :
	 *          - toolkit_menu = boolean
	 *          - surround_with_panel = boolean : if true adds the standard class panel (icon, title, ...)
	 *          - panel_title = string
	 *          - panel_title_is_html = boolean
	 *          - panel_icon = string : class icon (for example from {@see MetaModel::GetClassIcon()})
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Layout\UIContentBlock
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 * @throws \ReflectionException
	 */
	protected static function RenderDataTable(DataTable $oDataTable, string $sStyle, WebPage $oPage, string $sListId, DBObjectSet $oSet, array $aExtraParams)
	{
		// Filter this list action
		$sFilterListUrl = utils::GetDataTableSearchUrl($oSet->GetFilter(), $aExtraParams);
		if (utils::IsNotNullOrEmptyString($sFilterListUrl)) {
			$aExtraParams['filter_this_list_url'] = $sFilterListUrl;
		}

		if (!isset($aExtraParams['menu']) || $aExtraParams['menu'] === "1" || $aExtraParams['menu'] === true) {
			$oMenuBlock = new MenuBlock($oSet->GetFilter(), $sStyle);
			$aExtraParams['refresh_action'] = $oDataTable->GetJSRefresh();
			$oBlockMenu = $oMenuBlock->GetRenderContent($oPage, $aExtraParams, $sListId);
		} else {
			$bToolkitMenu = true;
			if (isset($aExtraParams['toolkit_menu'])) {
				$bToolkitMenu = (bool)$aExtraParams['toolkit_menu'];
			}
			if (UserRights::IsPortalUser() || $oPage->IsPrintableVersion()) {
				// Portal users have a limited access to data, for now they can only see what's configured for them
				$bToolkitMenu = false;
			}
			if ($bToolkitMenu) {
				$oMenuBlock = new MenuBlock($oSet->GetFilter(), $sStyle);
				$oBlockMenu = $oMenuBlock->GetRenderContent($oPage, $aExtraParams, $sListId);
			} else {
				$oBlockMenu = new UIContentBlock();
			}
		}

		// Default behavor, surrond table with a panel for better display
		if (!isset($aExtraParams['surround_with_panel']) || $aExtraParams['surround_with_panel']) {
			$oContainer = PanelUIBlockFactory::MakeForClass($oSet->GetClass(), '')
				->AddCSSClass('ibo-datatable-panel');

			// Panel title
			if (isset($aExtraParams['panel_title'])) {
				if (isset($aExtraParams['panel_title_is_html']) && $aExtraParams['panel_title_is_html'] === true) {
					$oContainer->AddTitleBlock(HtmlFactory::MakeRaw($aExtraParams['panel_title']));
				} else {
					$oContainer->SetTitle($aExtraParams['panel_title']);
				}
			}
			// - Description
			if (isset($aExtraParams['panel_title_tooltip'])) {
				$oContainerTitleBlock = $oContainer->GetTitleBlock()
					->AddDataAttribute('tooltip-content', $aExtraParams['panel_title_tooltip'])
					->AddDataAttribute('tooltip-max-width', 'min(600px, 90vw)') // Allow big description to be wide enough while shrinking on small screens
					->AddCSSClass('ibo-has-description');
			}

			// Panel subtitle
			if(!empty($oDataTable->GetInitDisplayData()) && isset($oDataTable->GetInitDisplayData()['recordsTotal'])){
				$iCount = $oDataTable->GetInitDisplayData()['recordsTotal'];
			} else {
				$iCount = $oSet->Count();
			}
			$sCountHtml = '<span class="ibo-datatable--result-count">'.$iCount.'</span>';
			if ($oDataTable->GetOption('select_mode') === 'multiple') {
				$sSubTitle = Dict::Format('UI:Pagination:HeaderSelection', $sCountHtml, '<span class="ibo-datatable--selected-count">0</span>');
			} else {
				$sSubTitle = Dict::Format('UI:Pagination:HeaderNoSelection', $sCountHtml);
				}

			if (utils::IsNotNullOrEmptyString($sFilterListUrl)) {
				$sSubTitle = '<a href="'.$sFilterListUrl.'" title="'.Dict::S('UI:Menu:FilterList').'">'.$sSubTitle.'</a>';
			}
			$oContainer->AddSubTitleBlock(new Html($sSubTitle));

			// Panel icon
			if (isset($aExtraParams['panel_icon']) && strlen($aExtraParams['panel_icon']) > 0) {
				$oContainer->SetIcon($aExtraParams['panel_icon']);
			}
			$oContainer->AddToolbarBlock($oBlockMenu);
			$oContainer->AddMainBlock($oDataTable);
		} else {
			$oContainer = new UIContentBlock();
			$oToolbar = ToolbarUIBlockFactory::MakeStandard();
			$oToolbar->AddSubBlock($oBlockMenu);
			$oContainer->AddSubBlock($oToolbar);
			$oContainer->AddSubBlock($oDataTable);
		}

		return $oContainer;
	}

	/**
	 * Make a row actions toolbar template.
	 *
	 * @param iUIBlock $oTable datatable object that needs to use tTableRowActions trait
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Template\Template
	 * @throws \Exception
	 * @since 3.1.0
	 */
	public static function MakeActionRowToolbarTemplate(iUIBlock $oTable)
	{
		// test trait
		$sTableClass = get_class($oTable);
		if (!utils::IsTraitUsedByClass(tTableRowActions::class, $sTableClass)) {
			throw new \Exception("DataTableUIBlockFactory::MakeActionRowToolbarTemplate: {$sTableClass} iUIBlock needs tTableRowActions trait");
		}

		// row actions template
		$oTemplate = TemplateUIBlockFactory::MakeStandard($oTable->GetId().'_actions_buttons_template');

		// row actions toolbar container
		$oToolbar = ToolbarUIBlockFactory::MakeStandard();
		$oToolbar->AddCSSClass('ibo-datatable--row-actions-toolbar');

		// for each action...create an icon button
		foreach ($oTable->GetRowActions() as $iKey => $aAction) {
			$oButton = ButtonUIBlockFactory::MakeIconAction(
				array_key_exists('icon_classes', $aAction) ? $aAction['icon_classes'] : 'fas fa-question',
				array_key_exists('tooltip', $aAction) ? Dict::S($aAction['tooltip']) : '',
				array_key_exists('name', $aAction) ? $aAction['name'] : 'undefined'
			);
			if (array_key_exists('color', $aAction)) {
				$oButton->SetColor($aAction['color']);
			}
			$oButton->SetDataAttributes(['label' => Dict::S($aAction['label']), 'action-id' => $iKey, 'table-id' => $oTable->GetId()]);
			if (array_key_exists('metadata', $aAction)) {
				$aMetadata = $aAction['metadata'];
				if (is_array($aMetadata)) {
					foreach ($aMetadata as $key => $value) {
						$oButton->AddDataAttribute($key, $value);
					}
				}
			}

			$oToolbar->AddSubBlock($oButton);
		}

		$oTemplate->AddSubBlock($oToolbar);

		return $oTemplate;
	}

	/**
	 * Make a basis Panel component
	 *
	 * @api
	 * @param string $sListId
	 * @param \DBObjectSet $oSet
	 * @param array $aExtraParams
	 *
	 * @return DataTable
	 * @throws \ApplicationException
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MySQLException
	 */
	public static function MakeForRendering(string $sListId, DBObjectSet $oSet, $aExtraParams = array())
	{
		$oDataTable = new DataTable('datatable_'.$sListId);
		$aLists = array();

		// Initialize and check the parameters
		$bViewLink = isset($aExtraParams['view_link']) ? $aExtraParams['view_link'] : true;
		// Check if there is a list of aliases to limit the display to...
		$aDisplayAliases = isset($aExtraParams['display_aliases']) ? explode(',', $aExtraParams['display_aliases']) : array();
		$sZListName = isset($aExtraParams['zlist']) ? ($aExtraParams['zlist']) : 'list';

		$sLinkageAttribute = isset($aExtraParams['link_attr']) ? $aExtraParams['link_attr'] : '';
		$iLinkedObjectId = isset($aExtraParams['object_id']) ? $aExtraParams['object_id'] : 0;
		$sTargetAttr = isset($aExtraParams['target_attr']) ? $aExtraParams['target_attr'] : '';
		if (!empty($sLinkageAttribute)) {
			if ($iLinkedObjectId == 0) {
				// if 'links' mode is requested the id of the object to link to must be specified
				throw new ApplicationException(Dict::S('UI:Error:MandatoryTemplateParameter_object_id'));
			}
			if ($sTargetAttr == '') {
				// if 'links' mode is requested the d of the object to link to must be specified
				throw new ApplicationException(Dict::S('UI:Error:MandatoryTemplateParameter_target_attr'));
			}
		}

		$aExtraFieldsRaw = isset($aExtraParams['extra_fields']) ? explode(',',
			trim($aExtraParams['extra_fields'])) : array();
		$aExtraFields = array();
		$sAttCode = '';
		foreach ($aExtraFieldsRaw as $sFieldName) {
			// Ignore attributes not of the main queried class
			if (preg_match('/^(.*)\.(.*)$/', $sFieldName, $aMatches)) {
				$sClassAlias = $aMatches[1];
				$sAttCode = $aMatches[2];
				if (array_key_exists($sClassAlias, $oSet->GetSelectedClasses())) {
					$aExtraFields[$sClassAlias][] = $sAttCode;
				}
			} else {
				$aExtraFields['*'][] = $sFieldName;
			}
			}

		$aClassAliases = $oSet->GetFilter()->GetSelectedClasses();
		$aAuthorizedClasses = array();
		foreach ($aClassAliases as $sAlias => $sClassName) {
			if ((UserRights::IsActionAllowed($sClassName, UR_ACTION_READ, $oSet) != UR_ALLOWED_NO) &&
				((count($aDisplayAliases) == 0) || (in_array($sAlias, $aDisplayAliases)))) {
				$aAuthorizedClasses[$sAlias] = $sClassName;
			}
		}
		foreach ($aAuthorizedClasses as $sAlias => $sClassName) {
			// In case there is only 1 "alias" for the extra fields and it is the fallback ("*"), then consider that all fields are for the current alias.
			// This is for the particular use case when the zlist is set to false and extra fields are specified.
			if ( (count($aExtraFields) === 1) && (array_keys($aExtraFields)[0] === '*') ) {
				$aLists[$sAlias] = $aExtraFields['*'];
			}
			// Regular use case, dispatch fields to their corresponding aliases
			else if (array_key_exists($sAlias, $aExtraFields)) {
				$aLists[$sAlias] = $aExtraFields[$sAlias];
		}
			// Finally, if unknown alias, ignore fields
			else {
				$aLists[$sAlias] = array();
			}

			// If zlist specified, merge its fields with the currently present
		if ($sZListName !== false) {
				$aDefaultList = MetaModel::FlattenZList(MetaModel::GetZListItems($sClassName, $sZListName));
				$aLists[$sAlias] = array_merge($aDefaultList, $aLists[$sAlias]);
		}

		// Filter the list to removed linked set since we are not able to display them here
			foreach ($aLists[$sAlias] as $index => $sAttCode) {
			$oAttDef = MetaModel::GetAttributeDef($sClassName, $sAttCode);
			if ($oAttDef instanceof AttributeLinkedSet) {
				// Removed from the display list
					unset($aLists[$sAlias][$index]);
				}
			}

			if (empty($aLists[$sAlias])) {
				unset($aLists[$sAlias], $aAuthorizedClasses[$sAlias]);
		}

			// Only for main class
			if (!empty($sLinkageAttribute) && $sClassName === $oSet->GetFilter()->GetClass()) {
			// The set to display is in fact a set of links between the object specified in the $sLinkageAttribute
			// and other objects...
			// The display will then group all the attributes related to the link itself:
			// | Link_attr1 | link_attr2 | ... || Object_attr1 | Object_attr2 | Object_attr3 | .. | Object_attr_n |
			$aDisplayList = array();
			$aAttDefs = MetaModel::ListAttributeDefs($sClassName);
			assert(isset($aAttDefs[$sLinkageAttribute]));
			$oAttDef = $aAttDefs[$sLinkageAttribute];
			assert($oAttDef->IsExternalKey());
			// First display all the attributes specific to the link record
				foreach ($aLists[$sAlias] as $sLinkAttCode) {
				$oLinkAttDef = $aAttDefs[$sLinkAttCode];
				if ((!$oLinkAttDef->IsExternalKey()) && (!$oLinkAttDef->IsExternalField())) {
					$aDisplayList[] = $sLinkAttCode;
				}
			}
			// Then display all the attributes neither specific to the link record nor to the 'linkage' object (because the latter are constant)
				foreach ($aLists[$sAlias] as $sLinkAttCode) {
				$oLinkAttDef = $aAttDefs[$sLinkAttCode];
				if (($oLinkAttDef->IsExternalKey() && ($sLinkAttCode != $sLinkageAttribute))
					|| ($oLinkAttDef->IsExternalField() && ($oLinkAttDef->GetKeyAttCode() != $sLinkageAttribute))) {
					$aDisplayList[] = $sLinkAttCode;
				}
			}
			// First display all the attributes specific to the link
			// Then display all the attributes linked to the other end of the relationship
				$aLists[$sAlias] = $aDisplayList;
			}
		}

		// N°6356 Check if there is at least 1 class remaining to display
		if (count($aLists) === 0) {
			IssueLog::Debug('Could not find any class / attribute to display in the list. Did you ensure the selected classes have the requested zlist? As a fallback, we will just display the friendlyname for the first selected class.', LogChannels::DATATABLE, [
				'selected_classes' => $aClassAliases,
				'zlist' => $sZListName,
			]);

			$sFirstClassAlias = array_keys($aClassAliases)[0];
			$aAuthorizedClasses[$sFirstClassAlias] = $aClassAliases[$sFirstClassAlias];
			$aLists[$sFirstClassAlias] = [];
		}

		$oDefaultSettings = DataTableSettings::GetDataModelSettings($aAuthorizedClasses, $bViewLink, $aLists);

		$bDisplayLimit = isset($aExtraParams['display_limit']) ? $aExtraParams['display_limit'] : true;
		if ($bDisplayLimit) {
			$iDefaultPageSize = appUserPreferences::GetPref('default_page_size', MetaModel::GetConfig()->GetMinDisplayLimit());
			$oDefaultSettings->iDefaultPageSize = $iDefaultPageSize;
		} else {
			$oDefaultSettings->iDefaultPageSize = 0;
		}

		$sTableId = isset($aExtraParams['table_id']) ? $aExtraParams['table_id'] : null;
		$oDefaultSettings->aSortOrder = MetaModel::GetOrderByDefault($sClassName);

		$bUseCustomSettings = false;
		// Identified tables can have their own specific settings
		$oCustomSettings = DataTableSettings::GetTableSettings($aClassAliases, $sTableId);

		if ($oCustomSettings != null) {
			// Custom settings overload the default ones
			$bUseCustomSettings = true;
			if ($oDefaultSettings->iDefaultPageSize == 0) {
				$oCustomSettings->iDefaultPageSize = 0;
			}
		} else {
			$oCustomSettings = $oDefaultSettings;
		}

		if ($oCustomSettings->iDefaultPageSize > 0) {
			$oSet->SetLimit($oCustomSettings->iDefaultPageSize);
		}

		if (count($oCustomSettings->aColumns) == 0) {
			$oCustomSettings->aColumns = $oDefaultSettings->aColumns;
		}
		if (count($oCustomSettings->GetSortOrder()) == 0) {
			$oCustomSettings->aSortOrder = $oDefaultSettings->aSortOrder;
		}

		$sIdName = isset($aExtraParams["id_for_select"]) ? $aExtraParams["id_for_select"] : "";
		// Load only the requested columns
		$aColumnsToLoad = array();
		foreach ($oCustomSettings->aColumns as $sAlias => $aColumnsInfo) {
			foreach ($aColumnsInfo as $sAttCode => $aData) {
				$bForceLoad = false;
				if ($aData['sort'] != 'none' || isset($oCustomSettings->aSortOrder[$sAttCode])) {
					$bForceLoad = true;
				}
				if ($sAttCode != '_key_') {
					if ($aData['checked'] || $bForceLoad) {
						$aColumnsToLoad[$sAlias][] = $sAttCode;
					} else {
						// See if this column is a must to load
						$sClass = $aClassAliases[$sAlias];
						$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
						if ($oAttDef->AlwaysLoadInTables()) {
							$aColumnsToLoad[$sAlias][] = $sAttCode;
						}
					}
				} else {
					if ($sIdName == "") {
						$sIdName = $sAlias."/_key_";
					}
				}
			}
		}
		$oSet->OptimizeColumnLoad($aColumnsToLoad);

		$aColumnDefinition = [];
		$iIndexColumn=0;

		$bSelectMode = isset($aExtraParams['selection_mode']) ? $aExtraParams['selection_mode'] == true : false;
		$bSingleSelectMode = isset($aExtraParams['selection_type']) ? ($aExtraParams['selection_type'] == 'single') : false;
		$sSelectMode = '';
		if ($bSelectMode) {
			$sSelectMode = $bSingleSelectMode ? 'single' : 'multiple';
			$iIndexColumn++;
		}

		$aSortOrder = [];
		$aSortDatable = [];
		foreach ($aAuthorizedClasses as $sClassAlias => $sClassName) {
			if (false === isset($oCustomSettings->aColumns[$sClassAlias])) {
				continue;
			}

			foreach ($oCustomSettings->aColumns[$sClassAlias] as $sAttCode => $aData) {
				$sCode = ($aData['code'] == '_key_') ? 'friendlyname' : $aData['code'];
				if ($aData['sort'] != 'none') {
					$aSortOrder[$sClassAlias.'.'.$sCode] = ($aData['sort'] == 'asc'); // true for ascending, false for descending
					$aSortDatable=[$iIndexColumn,$aData['sort']];
				}
				elseif (isset($oCustomSettings->aSortOrder[$sAttCode])){
					$aSortOrder[$sClassAlias.'.'.$sCode] = $oCustomSettings->aSortOrder[$sAttCode]; // true for ascending, false for descending
				}
				
				if ($aData['checked']) {
					if ($sAttCode == '_key_') {
						if ($bViewLink) {
							$aColumnDefinition[] = [
								'description' => $aData['label'],
								'object_class' => $sClassName,
								'class_alias' => $sClassAlias,
								'attribute_code' => $sAttCode,
								'attribute_type' => '_key_',
								'attribute_label' => MetaModel::GetName($sClassName),
								'render' => "return row['".$sClassAlias."/hyperlink'];",
							];

						}
					} else {
						$oAttDef = MetaModel::GetAttributeDef($sClassName, $sAttCode);
						if ($oAttDef instanceof AttributeCaseLog) {
							// Add JS files for display caselog
							// Dummy collapsible section created in order to get JS files
							$oCollapsibleSection = new CollapsibleSection('');
							$oDataTable->AddMultipleJsFilesRelPaths($oCollapsibleSection->GetJsFilesUrlRecursively());
						}
						$sAttDefClass = get_class($oAttDef);
						$sAttLabel = $oAttDef->GetLabel();
						$aColumnDefinition[] = [
							'description' => $oAttDef->GetOrderByHint(),
							'object_class' => $sClassName,
							'class_alias' => $sClassAlias,
							'attribute_code' => $sAttCode,
							'attribute_type' => $sAttDefClass,
							'attribute_label' => $sAttLabel,
							'render' => $oAttDef->GetRenderForDataTable($sClassAlias),
						];
					}
					$iIndexColumn++;
				}
			}
		}
		$oSet->SetOrderBy($aSortOrder);

		$aOptions = [];
		if ($oDefaultSettings != null) {
			$aOptions['oDefaultSettings'] = json_encode(array('iDefaultPageSize' => $oDefaultSettings->iDefaultPageSize, 'oColumns' => $oDefaultSettings->aColumns));
		}

		// Selection mode
		if ($sSelectMode == 'multiple') {
			$aOptions['select_mode'] = "multiple";
		} else {
			if ($sSelectMode == 'single') {
				$aOptions['select_mode'] = "single";
			}
		}
		$aOptions['selectionMode'] = $aExtraParams['selectionMode']?? 'positive';

		// Sort
		$aOptions['sort'] = $aSortDatable;

		// Items count selector
		if (isset($aExtraParams['cssCount'])) {
			$aOptions['sCountSelector'] = $aExtraParams['cssCount'];
		}

		// Pages length
		$aOptions['iPageSize'] = 10;
		if ($oCustomSettings->iDefaultPageSize > 0) {
			$aOptions['iPageSize'] = (int)$oCustomSettings->iDefaultPageSize;
		}

		// Max height is only set if necessary, otherwise we want the list to occupy all the height it can depending on its pagination
		if (isset($aExtraParams['max_height'])) {
			$aOptions['sMaxHeight'] = $aExtraParams['max_height'];
		}

		$aOptions['processing'] = true;
		$aOptions['sTableId'] = $sTableId;
		$aOptions['sListId'] = $sListId;
		$aOptions['bUseCustomSettings'] = $bUseCustomSettings;
		$aOptions['bViewLink'] = $bViewLink;
		$aOptions['oClassAliases'] = json_encode($aClassAliases);
		if (isset($aExtraParams['selected_rows']) && !empty($aExtraParams['selected_rows'])) {
			$aOptions['sSelectedRows'] = json_encode($aExtraParams['selected_rows']);
		} else {
			$aOptions['sSelectedRows'] = '[]';
		}
		$aExtraParams['table_id']=$sTableId;
		$aExtraParams['list_id']=$sListId;


		$oDataTable->SetOptions($aOptions);
		$oDataTable->SetAjaxUrl(utils::GetAbsoluteUrlAppRoot()."pages/ajax.render.php");
		$oDataTable->SetAjaxData([
			"operation"     => 'search',
			"filter"        => $oSet->GetFilter()->serialize(),
			"columns"       => $oCustomSettings->aColumns,
			"extra_params"  => $aExtraParams,
			"class_aliases" => $aClassAliases,
			"select_mode"   => $sSelectMode,
		]);
		$oDataTable->SetDisplayColumns($aColumnDefinition);
		$oDataTable->SetResultColumns($oCustomSettings->aColumns);
		$oDataTable->SetInitDisplayData(AjaxRenderController::GetDataForTable($oSet, $aClassAliases, $aColumnsToLoad, $sIdName, $aExtraParams));

		// row actions
		if (isset($aExtraParams['row_actions'])) {
			$oDataTable->SetRowActions($aExtraParams['row_actions']);
		}

		if (isset($aExtraParams['creation_in_modal_js_handler'])){
			$oDataTable->SetModalCreationHandler($aExtraParams['creation_in_modal_js_handler']);
		}

		return $oDataTable;
	}

	/**
	 * @deprecated 3.1.0 N°6261 Use \DataTableUIBlockFactory::MakeForRendering instead
	 * @param string $sListId
	 * @param DBObjectSet $oSet
	 * @param array $aExtraParams
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\DataTable\DataTable
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MySQLException
	 */
	public static function MakeForRenderingObject(string $sListId, DBObjectSet $oSet, $aExtraParams = array())
	{
		DeprecatedCallsLog::NotifyDeprecatedPhpMethod('use DataTableUIBlockFactory::MakeForRendering instead');

		return static::MakeForRendering($sListId, $oSet, $aExtraParams);
	}

	/**
	 * @param array $aColumns
	 * @param string $sSelectMode
	 * @param string $sFilter
	 * @param int $iLength
	 * @param array $aClassAliases
	 * @param array $aExtraParams
	 * @param string $sTableId
	 *
	 * @return array
	 * @throws \Exception
	 */
	public static function GetOptionsForRendering(array $aColumns, string $sSelectMode, string $sFilter, int $iLength, array $aClassAliases, array $aExtraParams, string $sTableId)
	{
		$oAppRoot = utils::GetAbsoluteUrlAppRoot();

		$aOptions = [];
		$sListId = $aExtraParams["list_id"];
		$aColumnsDefinitions = [];
		$aColumnDefinition = [];

		$sSortCol = utils::ReadParam('sort_col', '', false, 'raw_data');
		$sSortOrder = utils::ReadParam('sort_order', '', false, 'raw_data');
		$sOrder = [];
		$aJsFiles = [];
		if ($sSortCol != "") {
			$sOrder[] = [$sSortCol, $sSortOrder];
		}
		if ($sSelectMode != "") {
			$aColumnDefinition["width"] = "auto";
			$aColumnDefinition["searchable"] = false;
			$aColumnDefinition["sortable"] = false;
			if ($sSelectMode != "single") {
				$aColumnDefinition["title"] = "<span class=\"row_input\"><input type=\"checkbox\" onclick=\"checkAllDataTable('".$sTableId."',this.checked,'".$sListId."');\" class=\"checkAll\" id=\"field_".$sTableId."_check_all\" name=\"field_".$sTableId."_check_all\" title=\"".Dict::S('UI:SearchValue:CheckAll')." / ".Dict::S('UI:SearchValue:UncheckAll')."\" /></span>";
			} else {
				$aColumnDefinition["title"] = "";
			}
			$aColumnDefinition["type"] = "html";
			$aColumnDefinition["data"] = "";
			$aColumnDefinition["render"]["display"] = "";
			if ($sSelectMode != "single") {
				$aColumnDefinition["render"]["display"] = $aColumnDefinition["render"]["display"] . " var oCheckboxElem = $('<span class=\"row_input\"><input type=\"checkbox\" class=\"selectList".$sTableId."\" name=\"selectObject[]\" value='+row.id+' /></span>');";
			}
			else {
				$aColumnDefinition["render"]["display"] = $aColumnDefinition["render"]["display"] . " var oCheckboxElem = $('<span class=\"row_input\"><input type=\"radio\" class=\"selectList".$sTableId."\" name=\"selectObject[]\" value='+ row.id +' /></span>');";
			}
			$aColumnDefinition["render"]["display"] = $aColumnDefinition["render"]["display"] . "	if (row.limited_access) { oCheckboxElem.html('-'); } else {	oCheckboxElem.find(':input').attr('data-object-id', row.id).attr('data-target-object-id', row.target_id); }";
			$aColumnDefinition["render"]["display"] = $aColumnDefinition["render"]["display"]. "	return oCheckboxElem.prop('outerHTML');	";
			array_push($aColumnsDefinitions, $aColumnDefinition);
		}

		foreach ($aColumns as $sClassAlias => $aClassColumns) {
			$sClassName=$aClassAliases[$sClassAlias];
			foreach ($aClassColumns as $sAttCode => $aData) {
				if ($aData['checked'] == "true") {
					$aColumnDefinition["width"] = "auto";
					$aColumnDefinition["searchable"] = false;
					$aColumnDefinition["sortable"] = true;
					$aColumnDefinition["defaultContent"] = "";
					$aColumnDefinition["type"] = "html";

					if ($sAttCode == '_key_') {
						$sAttLabel = $aData['alias'];
						$aColumnDefinition["title"] = $aData['alias'];
						$aColumnDefinition['metadata'] = [
							'object_class'    => $sClassName,
							'class_alias'     => $sClassAlias,
							'attribute_code'  => $sAttCode,
							'attribute_type'  => '_key_',
							'attribute_label' => $sAttLabel,
						];
						$aColumnDefinition["data"] = $sClassAlias."/".$sAttCode;
						$aColumnDefinition["render"] = [
							"display" => "return row['".$sClassAlias."/hyperlink'];",
							"_"       => $sClassAlias."/".$sAttCode,
						];
						$aColumnDefinition["createdCell"] = <<<JS
						$(td).attr('data-object-class', `$sClassName`);
						$(td).attr('data-attribute-label', `$sAttLabel`);
						if (rowData["$sClassAlias/$sAttCode/raw"]) {
							$(td).attr('data-value-raw', rowData["$sClassAlias/$sAttCode/raw"]);
		                }
JS;
					} else {
						$oAttDef = MetaModel::GetAttributeDef($sClassName, $sAttCode);
						if ($oAttDef instanceof AttributeCaseLog) {
							// Get JS files
							// Dummy collapsible section created in order to get JS files
							$oCollapsibleSection = new CollapsibleSection('');
							$aJsFiles = array_merge($aJsFiles, $oCollapsibleSection->GetJsFilesUrlRecursively());
						}
						$sAttDefClass = get_class($oAttDef);
						$sAttLabel = MetaModel::GetLabel($sClassName, $sAttCode);
						$aColumnDefinition["title"] = $sAttLabel;
						$aColumnDefinition['metadata'] = [
							'object_class'    => $sClassName,
							'class_alias'     => $sClassAlias,
							'attribute_code'  => $sAttCode,
							'attribute_type'  => $sAttDefClass,
							'attribute_label' => $sAttLabel,
						];
						$aColumnDefinition["data"] = $sClassAlias."/".$sAttCode;
						$aColumnDefinition["render"] = [
							"display" => $oAttDef->GetRenderForDataTable($sClassAlias),
							"_"       => $sClassAlias."/".$sAttCode,
						];
						$aColumnDefinition["createdCell"] = <<<JS
						$(td).attr('data-object-class', `$sClassName`);
						$(td).attr('data-attribute-label', `$sAttLabel`);
						$(td).attr('data-attribute-code', `$sAttCode`);
						$(td).attr('data-attribute-type', `$sAttDefClass`);
						if (rowData["$sClassAlias/$sAttCode/raw"]) {
							$(td).attr('data-value-raw', rowData["$sClassAlias/$sAttCode/raw"]);
		                }
JS;
					}
					array_push($aColumnsDefinitions, $aColumnDefinition);
				}
			}
		}

		$aOptions['select'] = ["style" => $sSelectMode, "info" => false];

		$aOptions['pageLength'] = $iLength;

		$sAjaxData = json_encode([
			"operation" => 'search',
			"filter" => $sFilter,
			"columns" => $aColumns,
			"extra_params" => $aExtraParams,
			"class_aliases" => $aClassAliases,
			"select_mode" => $sSelectMode,
		]);

		$oAppContext = new ApplicationContext();
		$aOptions = array_merge($aOptions, [
			"language" =>
				[
					"processing" => Dict::Format('UI:Datatables:Language:Processing'),
					"search" => Dict::Format('UI:Datatables:Language:Search'),
					"lengthMenu" => Dict::Format('UI:Datatables:Language:LengthMenu'),
					"zeroRecords" => Dict::Format('UI:Datatables:Language:ZeroRecords'),
					"info" => Dict::Format('UI:Datatables:Language:Info'),
					"infoEmpty" => Dict::Format('UI:Datatables:Language:InfoEmpty'),
					"infoFiltered" => Dict::Format('UI:Datatables:Language:InfoFiltered'),
					"emptyTable" => Dict::Format('UI:Datatables:Language:EmptyTable'),
					"paginate" => [
						"first" => "<<",
						"previous" => "<",
						"next" => ">",
						"last" => ">>",
					],
					"aria" => [
						"sortAscending" => Dict::Format('UI:Datatables:Language:Sort:Ascending'),
						"sortDescending" => Dict::Format('UI:Datatables:Language:Sort:Descending'),
					],
				],
			"lengthMenu" => Dict::Format('Portal:Datatables:Language:DisplayLength:All'),
			"dom" => "<'ibo-datatable--toolbar'<'ibo-datatable--toolbar-left' pl><'ibo-datatable--toolbar-right' i>>t<'ibo-datatable--toolbar'<'ibo-datatable--toolbar-left' pl><'ibo-datatable--toolbar-right' i>>",
			"scrollX" => true,
			"scrollCollapse" => true,
			"ordering" => true,
			"order" => $sOrder,
			"filter" => false,
			"processing" => true,
			"serverSide" => true,
			"columns" => $aColumnsDefinitions,
			"allColumns" => $aColumns,
			'ajax' => '$.fn.dataTable.pipeline( {
					"url": "ajax.render.php?'.$oAppContext->GetForLink().'",
					"data": '.$sAjaxData.',
					"method":	"post",
					"pages": 5 // number of pages to cache
				} )'
		]);
		if (count($aJsFiles) > 0) {
			foreach ($aJsFiles as $sJsFile) {
				$aUrlFiles[] = utils::GetAbsoluteUrlAppRoot().$sJsFile;
			}
			$aOptions['js_files'] = $aUrlFiles;
			$aOptions['js_files_param'] = 'itopversion';
			$aOptions['js_files_value'] = ITOP_VERSION;
		}
		return $aOptions;
	}

	/**
	 * @api
	 * @param string $sTitle
	 * @param array $aColumns
	 * @param array $aData
	 * @param string|null $sId
	 * @param array $aExtraParams
	 * @param string $sFilter
	 * @param array $aOptions
	 * @param array $aRowActions @since 3.1.0
	 * *
	 * $aColumns =[
	 *           'nameField1' => ['label' => labelFIeld1, 'description' => descriptionField1],
	 *           'nameField2' => ['label' => labelFIeld2, 'description' => descriptionField2],
	 *           'nameField3' => ['label' => labelFIeld3, 'description' => descriptionField3]];
	 * $aData = [['nameField1' => valueField1, 'nameField2' => valueField2, 'nameField3' => valueField3],...]
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Layout\UIContentBlock
	 */
	public static function MakeForStaticData(string $sTitle, array $aColumns, array $aData, ?string $sId = null, array $aExtraParams = [], string $sFilter = "", array $aOptions = [], array $aRowActions = null)
	{
		$oBlock = new UIContentBlock();
		if ($sTitle != "") {
			$oTitle = TitleUIBlockFactory::MakeNeutral($sTitle, 3);
			$oBlock->AddSubBlock($oTitle);
		}
		$oTable = new StaticTable($sId, [], $aExtraParams);
		if ($aRowActions != null) {
			$oTable->SetRowActions($aRowActions);
			$aColumns['actions'] = [
				'label'       => Dict::S('UI:Datatables:Column:RowActions:Label'),
				'description' => Dict::S('UI:Datatables:Column:RowActions:Description'),
			];
		}
		$oTable->SetColumns($aColumns);
		$oTable->SetData($aData);
		$oTable->SetFilter($sFilter);
		$oTable->SetOptions($aOptions);

		$oBlock->AddSubBlock($oTable);

		return $oBlock;
	}

	/**
	 * @api
	 * @param string $sRef
	 * @param array $aColumns
	 * @param array $aData
	 * @param string $sFilter
	 * @param array $aRowActions @since 3.1.0
	 *
	 * $aColumns =[
	 *           'nameField1' => ['label' => labelFIeld1, 'description' => descriptionField1],
	 *           'nameField2' => ['label' => labelFIeld2, 'description' => descriptionField2],
	 *           'nameField3' => ['label' => labelFIeld3, 'description' => descriptionField3]];
	 * $aData = [['nameField1' => valueField1, 'nameField2' => valueField2, 'nameField3' => valueField3],...]
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\DataTable\StaticTable\FormTable\FormTable
	 */
	public static function MakeForForm(string $sRef, array $aColumns, array $aData = [], string $sFilter = '', array $aRowActions = null)
	{
		$oTable = new FormTable("datatable_".$sRef);
		$oTable->SetRef($sRef);
		if ($aRowActions != null) {
			$oTable->SetRowActions($aRowActions);
			$aColumns['actions'] = [
				'label'       => Dict::S('UI:Datatables:Column:RowActions:Label'),
				'description' => Dict::S('UI:Datatables:Column:RowActions:Description'),
			];
		}
		$oTable->SetColumns($aColumns);
		$oTable->SetFilter($sFilter);

		foreach ($aData as $iRowId => $aRow) {
			$oRow = new FormTableRow($sRef, $aColumns, $aRow, $iRowId);
			$oTable->AddRow($oRow);
		}

		return $oTable;
	}

	/**
	 * @return array
	 */
	public static function GetAllowedParams(): array
	{
		return [
			'surround_with_panel',
			/** bool embed table into a Panel */
			'menu',
			/** bool display table menu */
			'view_link',
			/** bool display the friendlyname column with links to the objects details */
			'link_attr',
			/** string link att code */
			'object_id',
			/** int Id of the object linked */
			'target_attr',
			/** string target att code of the link */
			'selection_mode',
			/** bool activate selection */
			'selection_type',
			/** string 'multiple' or 'single' */
			'extra_fields',
			/** string comma separated list of link att code to display ('alias.attcode')*/
			'zlist',
			/** string name of the zlist to display when 'extra_fields' is not set */
			'display_limit',
			/** bool if true pagination is used (default = true)  */
			'table_id',
			/** string datatable id */
			'cssCount',
			/** string external counter (input hidden) js selector */
			'selected_rows',
			/** array list of Ids already selected when displaying the datatable */
			'display_aliases',
			/** string comma separated list of class aliases to display */
			'list_id',
			/** string list outer id */
			'selection_enabled',
			/** list of id in witch select is allowed, if not exists all lines are selectable */
			'id_for_select',
			/**give definition of id for select checkbox*/
			'row_actions',
			/** array of blocks displayed on every row */
			'creation_in_modal',
			/** bool to perform a creation of a new object of this type in a modal */
			'creation_in_modal_tooltip',
			/** creation in modal button tooltip */
			'creation_in_modal_form_title',
			/** creation in modal form title */
			'creation_in_modal_js_handler',
			/** Handler to call when trying to create a new object in modal */
			'creation_disallowed',
			/** Don't provide the standard object creation feature */
		];
	}
}