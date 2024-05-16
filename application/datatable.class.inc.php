<?php

use Combodo\iTop\Application\UI\Base\Component\DataTable\DataTableSettings;
use Combodo\iTop\Application\WebPage\WebPage;
use Combodo\iTop\Renderer\Console\ConsoleBlockRenderer;

/**
 * Copyright (C) 2013-2024 Combodo SAS
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 *
 * @deprecated 3.0.0 use Combodo\iTop\Application\UI\Base\Component\DataTable\Datatable
 */

class DataTable
{
	protected $iListId;		// Unique ID inside the web page
	/** @var string */
	private $sDatatableContainerId;
	protected $sTableId;	// identifier for saving the settings (combined with the class aliases)
	protected $oSet;		// The set of objects to display
	protected $aClassAliases;	// The aliases (alias => class) inside the set
	protected $iNbObjects;		// Total number of objects in the set
	protected $bUseCustomSettings;	// Whether or not the current display uses custom settings
	protected $oDefaultSettings;	// the default settings for displaying such a list
	protected $bShowObsoleteData;

	/**
	 * @param string $iListId  Unique ID for this div/table in the page
	 * @param DBObjectSet $oSet The set of data to display
	 * @param array$aClassAliases The list of classes/aliases to be displayed in this set $sAlias => $sClassName
	 * @param string $sTableId A string (or null) identifying this table in order to persist its settings
	 *
	 * @throws \CoreException
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 */
	public function __construct($iListId, $oSet, $aClassAliases, $sTableId = null)
	{
		DeprecatedCallsLog::NotifyDeprecatedPhpMethod('use Combodo\iTop\Application\UI\Base\Component\DataTable\Datatable');
		$this->iListId = utils::GetSafeId($iListId); // Make a "safe" ID for jQuery
		$this->sDatatableContainerId = 'datatable_'.utils::GetSafeId($iListId);
		$this->oSet = $oSet;
		$this->aClassAliases = $aClassAliases;
		$this->sTableId = $sTableId;
		$this->iNbObjects = $oSet->Count();
		$this->bUseCustomSettings = false;
		$this->oDefaultSettings = null;
		$this->bShowObsoleteData = $oSet->GetShowObsoleteData();
	}

	/**
	 * @param WebPage $oPage
	 * @param DataTableSettings $oSettings
	 * @param $bActionsMenu
	 * @param $sSelectMode
	 * @param $bViewLink
	 * @param $aExtraParams
	 *
	 * @return string
	 * @throws \CoreException
	 * @throws \MySQLException
	 */
	public function Display(WebPage $oPage, DataTableSettings $oSettings, $bActionsMenu, $sSelectMode, $bViewLink, $aExtraParams)
	{
		$this->oDefaultSettings = $oSettings;

		// Identified tables can have their own specific settings
		$oCustomSettings = DataTableSettings::GetTableSettings($this->aClassAliases, $this->sTableId);
		
		if ($oCustomSettings != null)
		{
			// Custom settings overload the default ones
			$this->bUseCustomSettings = true;
			if ($this->oDefaultSettings->iDefaultPageSize == 0)
			{
				$oCustomSettings->iDefaultPageSize = 0;
			}
		}
		else
		{
			$oCustomSettings = $oSettings;
		}

		if ($oCustomSettings->iDefaultPageSize > 0)
		{
			$this->oSet->SetLimit($oCustomSettings->iDefaultPageSize);
		}
		$this->oSet->SetOrderBy($oCustomSettings->GetSortOrder());

		// Load only the requested columns
		$aColumnsToLoad = array();
		foreach($oCustomSettings->aColumns as $sAlias => $aColumnsInfo)
		{
			foreach($aColumnsInfo as $sAttCode => $aData)
			{
				if ($sAttCode != '_key_')
				{
					if ($aData['checked'])
					{
						$aColumnsToLoad[$sAlias][] = $sAttCode;
					}
					else
					{
						// See if this column is a must to load			
						$sClass = $this->aClassAliases[$sAlias];
						$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
						if ($oAttDef->AlwaysLoadInTables()) {
							$aColumnsToLoad[$sAlias][] = $sAttCode;
						}
					}
				}
			}
		}
		$this->oSet->OptimizeColumnLoad($aColumnsToLoad);


		$bToolkitMenu = true;
		if (isset($aExtraParams['toolkit_menu']))
		{
			$bToolkitMenu = (bool) $aExtraParams['toolkit_menu'];
		}
		if (UserRights::IsPortalUser())
		{
			// Portal users have a limited access to data, for now they can only see what's configured for them
			$bToolkitMenu = false;
		}
		
		return $this->GetAsHTML($oPage, $oCustomSettings->iDefaultPageSize, $oCustomSettings->iDefaultPageSize, 0, $oCustomSettings->aColumns, $bActionsMenu, $bToolkitMenu, $sSelectMode, $bViewLink, $aExtraParams);
	}

	/**
	 * @param WebPage $oPage
	 * @param $iPageSize
	 * @param $iDefaultPageSize
	 * @param $iPageIndex
	 * @param $aColumns
	 * @param $bActionsMenu
	 * @param $bToolkitMenu
	 * @param $sSelectMode
	 * @param $bViewLink
	 * @param $aExtraParams
	 *
	 * @return string
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 */
	public function GetAsHTML(WebPage $oPage, $iPageSize, $iDefaultPageSize, $iPageIndex, $aColumns, $bActionsMenu, $bToolkitMenu, $sSelectMode, $bViewLink, $aExtraParams)
	{
		$sObjectsCount = $this->GetObjectCount($oPage, $sSelectMode);
		$sPager = $this->GetPager($oPage, $iPageSize, $iDefaultPageSize, $iPageIndex);
		$sActionsMenu = '';
		$sToolkitMenu = '';
		if ($bActionsMenu) {
			$sActionsMenu = $this->GetActionsMenu($oPage, $aExtraParams);
		}
//		if ($bToolkitMenu)
//		{
//			$sToolkitMenu = $this->GetToolkitMenu($oPage, $aExtraParams);
//		}

		$sDataTable = $this->GetHTMLTable($oPage, $aColumns, $sSelectMode, $iPageSize, $bViewLink, $aExtraParams);
		$sConfigDlg = $this->GetTableConfigDlg($oPage, $aColumns, $bViewLink, $iDefaultPageSize);

		$sHtml = "<table id=\"{$this->sDatatableContainerId}\" class=\"datatable\">";
		$sHtml .= "<tr><td>";
		$sHtml .= "<table style=\"width:100%;\">";
		$sHtml .= "<tr><td class=\"pagination_container\">$sObjectsCount</td><td class=\"menucontainer\">$sToolkitMenu $sActionsMenu</td></tr>";
		$sHtml .= "<tr>$sPager</tr>";
		$sHtml .= "</table>";
		$sHtml .= "</td></tr>";
		$sHtml .= "<tr><td class=\"datacontents\">$sDataTable</td></tr>";
		$sHtml .= "</table>\n";
		$oPage->add_at_the_end($sConfigDlg);

		$aExtraParams['show_obsolete_data'] = $this->bShowObsoleteData;

		$aOptions = array(
			'sPersistentId' => '',
			'sFilter' => $this->oSet->GetFilter()->serialize(),
			'oColumns' => $aColumns,
			'sSelectMode' => $sSelectMode,
			'sViewLink' => ($bViewLink ? 'true' : 'false'),
			'iNbObjects' => $this->iNbObjects,
			'iDefaultPageSize' => $iDefaultPageSize,
			'iPageSize' =>  $iPageSize,
			'iPageIndex' =>  $iPageIndex,
			'oClassAliases' => $this->aClassAliases,
			'sTableId' => $this->sTableId,
			'oExtraParams' => $aExtraParams,
			'sRenderUrl' => utils::GetAbsoluteUrlAppRoot().'pages/ajax.render.php',
			'oRenderParameters' => array('str' => ''), // Forces JSON to encode this as a object...
			'oDefaultSettings' => array('str' => ''), // Forces JSON to encode this as a object...
			'oLabels' => array('moveup' => Dict::S('UI:Button:MoveUp'), 'movedown' => Dict::S('UI:Button:MoveDown')),
		);
		if($this->oDefaultSettings != null)
		{
			$aOptions['oDefaultSettings'] = $this->GetAsHash($this->oDefaultSettings);
		}
		$sJSOptions = json_encode($aOptions);
		$oPage->add_ready_script("$('#{$this->sDatatableContainerId}').datatable($sJSOptions);");

		return $sHtml;
	}

	/**
	 * When refreshing the body of a paginated table, get the rows of the table (inside the TBODY)
	 * return string The HTML rows to insert inside the <tbody> node
	 */
	public function GetAsHTMLTableRows(WebPage $oPage, $iPageSize, $aColumns, $sSelectMode, $bViewLink, $aExtraParams)
	{
		if ($iPageSize < 1)
		{
			$iPageSize = -1; // convention: no pagination
		}
		$aAttribs = $this->GetHTMLTableConfig($aColumns, $sSelectMode, $bViewLink);
		$aValues = $this->GetHTMLTableValues($aColumns, $sSelectMode, $iPageSize, $bViewLink, $aExtraParams);
		
		$sHtml = '';
		foreach($aValues as $aRow)
		{
			$sHtml .= $oPage->GetTableRow($aRow, $aAttribs);
		}
		return $sHtml;
	}

	/**
	 * @param WebPage $oPage
	 * @param $sSelectMode
	 *
	 * @return string
	 */
	protected function GetObjectCount(WebPage $oPage, $sSelectMode)
	{
		if (($sSelectMode == 'single') || ($sSelectMode == 'multiple'))
		{
			$sHtml = '<div class="pagination_objcount">'.Dict::Format('UI:Pagination:HeaderSelection', '<span id="total">'.$this->iNbObjects.'</span>', '<span class="selectedCount">0</span>').'</div>';
		}
		else
		{
			$sHtml = '<div class="pagination_objcount">'.Dict::Format('UI:Pagination:HeaderNoSelection', '<span id="total">'.$this->iNbObjects.'</span>').'</div>';
		}
		return $sHtml;		
	}

	/**
	 * @param WebPage $oPage
	 * @param $iPageSize
	 * @param $iDefaultPageSize
	 * @param $iPageIndex
	 *
	 * @return string
	 */
	protected function GetPager(WebPage $oPage, $iPageSize, $iDefaultPageSize, $iPageIndex)
	{
		$sHtml = '';
		if ($iPageSize < 1) // Display all
		{
			$sPagerStyle = 'style="display:none"'; // no limit: display the full table, so hide the "pager" UI
												   // WARNING: mPDF does not take the "display" style into account
												   // when applied to a <td> or a <table> tag, so make sure you apply this to a div
		}
		else
		{
			$sPagerStyle = '';
		}
		
		$sCombo = '<select class="pagesize">';
		if($iPageSize < 1)
		{
			$sCombo .= "<option selected=\"selected\" value=\"-1\">".Dict::S('UI:Pagination:All')."</option>";
		}
		else
		{
			for($iPage = 1; $iPage < 5; $iPage++)
			{
				$iNbItems = $iPage * $iDefaultPageSize;
				$sSelected = ($iNbItems == $iPageSize) ? 'selected="selected"' : '';
				$sCombo .= "<option  $sSelected value=\"$iNbItems\">$iNbItems</option>";
			}
			$sCombo .= "<option value=\"-1\">".Dict::S('UI:Pagination:All')."</option>";
		}

		$sCombo .= '</select>';
		
		$sPages = Dict::S('UI:Pagination:PagesLabel');
		$sPageSizeCombo = Dict::Format('UI:Pagination:PageSize', $sCombo);
		
		$iNbPages = ($iPageSize < 1) ? 1 : ceil($this->iNbObjects / $iPageSize);
		if ($iNbPages == 1)
		{
			// No need to display the pager
			$sPagerStyle = 'style="display:none"';
		}
		$aPagesToDisplay = array();
		for($idx = 0; $idx <= min(4, $iNbPages-1); $idx++)
		{
			if ($idx == 0)
			{
				$aPagesToDisplay[$idx] = '<span page="0" class="curr_page">1</span>';
			}
			else
			{
				$aPagesToDisplay[$idx] = "<span id=\"gotopage_$idx\" class=\"gotopage\" page=\"$idx\">".(1+$idx)."</span>";
			}
		}
		$iLastPageIdx = $iNbPages - 1;
		if (!isset($aPagesToDisplay[$iLastPageIdx]))
		{
			unset($aPagesToDisplay[$idx - 1]); // remove the last page added to make room for the very last page
			$aPagesToDisplay[$iLastPageIdx] = "<span id=\"gotopage_$iLastPageIdx\" class=\"gotopage\" page=\"$iLastPageIdx\">... $iNbPages</span>";
		}
		$sPagesLinks = implode('', $aPagesToDisplay);
		$sPagesList = '['.implode(',', array_keys($aPagesToDisplay)).']';

		$sAppRootUrl = utils::GetAbsoluteUrlAppRoot();
		$sSelectionMode = ($iNbPages == 1) ? '' : 'positive';
		$sHtml =
<<<EOF
		<td colspan="2">
		<div $sPagerStyle>
		<table id="pager{$this->iListId}" class="pager"><tr>
		<td>$sPages</td>
		<td><img src="{$sAppRootUrl}images/first.png" class="first"/>AAAA</td>
		<td><img src="{$sAppRootUrl}images/prev.png" class="prev"/></td>
		<td><span id="index">$sPagesLinks</span></td>
		<td><img src="{$sAppRootUrl}images/next.png" class="next"/></td>
		<td><img src="{$sAppRootUrl}images/last.png" class="last"/></td>
		<td>$sPageSizeCombo</td>
		<td><span id="loading">&nbsp;</span><input type="hidden" name="selectionMode" value="$sSelectionMode"></input>
		</td>
		</tr>
		</table>
		</div>
		</td>
EOF;
		return $sHtml;
	}

	/**
	 * @param WebPage $oPage
	 * @param $aExtraParams
	 *
	 * @return string
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
	 * @throws \Twig\Error\LoaderError
	 * @throws \Twig\Error\RuntimeError
	 * @throws \Twig\Error\SyntaxError
	 */
	protected function GetActionsMenu(WebPage $oPage, $aExtraParams)
	{
		$oMenuBlock = new MenuBlock($this->oSet->GetFilter(), 'list');
		$oBlock = $oMenuBlock->GetRenderContent($oPage, $aExtraParams, $this->iListId);

		return ConsoleBlockRenderer::RenderBlockTemplateInPage($oPage, $oBlock);
	}

	/**
	 * @param WebPage $oPage
	 * @param $aExtraParams
	 *
	 * @return string
	 * @throws \Exception
	 */
	protected function GetToolkitMenu(WebPage $oPage, $aExtraParams)
	{
		if (!$oPage->IsPrintableVersion())
		{
			$sMenuTitle = Dict::S('UI:ConfigureThisList');
			$sHtml = '<div class="itop_popup toolkit_menu" id="tk_'.$this->iListId.'"><ul><li aria-label="'.Dict::S('UI:Menu:Toolkit').'"><i class="fas fa-tools"></i><i class="fas fa-caret-down"></i><ul>';
	
			$oMenuItem1 = new JSPopupMenuItem('iTop::ConfigureList', $sMenuTitle, "$('#datatable_dlg_".$this->iListId."').dialog('open');");
			$aActions = array(
				$oMenuItem1->GetUID() => $oMenuItem1->GetMenuItem(),
			);
			$this->oSet->Rewind();
			utils::GetPopupMenuItems($oPage, iPopupMenuExtension::MENU_OBJLIST_TOOLKIT, $this->oSet, $aActions, $this->sTableId, $this->iListId);
			$this->oSet->Rewind();
			$sHtml .= $oPage->RenderPopupMenuItems($aActions);
		}
		else
		{
			$sHtml = '';
		}
		return $sHtml;
	}

	/**
	 * @param WebPage $oPage
	 * @param $aColumns
	 * @param $bViewLink
	 * @param $iDefaultPageSize
	 *
	 * @return string
	 */
	protected function GetTableConfigDlg(WebPage $oPage, $aColumns, $bViewLink, $iDefaultPageSize)
	{
		$sHtml = "<div id=\"datatable_dlg_{$this->iListId}\" style=\"display: none;\">";
		$sHtml .= "<form onsubmit=\"return false\">";
		$sChecked = ($this->bUseCustomSettings) ? '' : 'checked';
		$sHtml .= "<p><input id=\"dtbl_dlg_settings_{$this->iListId}\" type=\"radio\" name=\"settings\" $sChecked value=\"defaults\"><label for=\"dtbl_dlg_settings_{$this->iListId}\">&nbsp;".Dict::S('UI:UseDefaultSettings').'</label></p>';
		$sHtml .= "<fieldset>";
		$sChecked = ($this->bUseCustomSettings) ? 'checked':  '';
		$sHtml .= "<legend class=\"transparent\"><input id=\"dtbl_dlg_specific_{$this->iListId}\" type=\"radio\" class=\"specific_settings\" name=\"settings\" $sChecked value=\"specific\"><label for=\"dtbl_dlg_specific_{$this->iListId}\">&nbsp;".Dict::S('UI:UseSpecificSettings')."</label></legend>";
		$sHtml .= Dict::S('UI:ColumnsAndSortOrder').'<br/><ul class="sortable_field_list" id="sfl_'.$this->iListId.'"></ul>';
		
		$sHtml .= '<p>'.Dict::Format('UI:Display_X_ItemsPerPage', '<input type="text" size="4" name="page_size" value="'.$iDefaultPageSize.'">').'</p>';
		$sHtml .= "</fieldset>";
		$sHtml .= "<fieldset>";
		$sSaveChecked = ($this->sTableId != null) ? 'checked' : '';
		$sCustomDisabled = ($this->sTableId == null) ? 'disabled="disabled" stay-disabled="true" ' : '';
		$sCustomChecked = ($this->sTableId != null) ? 'checked' : '';
		$sGenericChecked = ($this->sTableId == null) ? 'checked' : '';
		$sHtml .= "<legend class=\"transparent\"><input id=\"dtbl_dlg_save_{$this->iListId}\" type=\"checkbox\" $sSaveChecked name=\"save_settings\"><label for=\"dtbl_dlg_save_{$this->iListId}\">&nbsp;".Dict::S('UI:UseSavetheSettings')."</label></legend>";
		$sHtml .= "<p><input id=\"dtbl_dlg_this_list_{$this->iListId}\" type=\"radio\" name=\"scope\" $sCustomChecked $sCustomDisabled value=\"this_list\"><label for=\"dtbl_dlg_this_list_{$this->iListId}\">&nbsp;".Dict::S('UI:OnlyForThisList').'</label>&nbsp;&nbsp;&nbsp;&nbsp;';
		$sHtml .= "<input id=\"dtbl_dlg_all_{$this->iListId}\" type=\"radio\" name=\"scope\" $sGenericChecked value=\"defaults\"><label for=\"dtbl_dlg_all_{$this->iListId}\">&nbsp;".Dict::S('UI:ForAllLists').'</label></p>';
		$sHtml .= "</fieldset>";
		$sHtml .= '<table style="width:100%"><tr><td style="text-align:center;">';
		$sHtml .= '<button type="button" onclick="$(\'#'.$this->sDatatableContainerId.'\').datatable(\'onDlgCancel\'); $(\'#datatable_dlg_'.$this->iListId.'\').dialog(\'close\')">'.Dict::S('UI:Button:Cancel').'</button>';
		$sHtml .= '</td><td style="text-align:center;">';
		$sHtml .= '<button type="submit" onclick="$(\'#'.$this->sDatatableContainerId.'\').datatable(\'onDlgOk\');$(\'#datatable_dlg_'.$this->iListId.'\').dialog(\'close\');">'.Dict::S('UI:Button:Ok').'</button>';
		$sHtml .= '</td></tr></table>';
		$sHtml .= "</form>";
		$sHtml .= "</div>";
		
		$sDlgTitle = addslashes(Dict::S('UI:ListConfigurationTitle'));
		$oPage->add_ready_script("$('#datatable_dlg_{$this->iListId}').dialog({autoOpen: false, title: '$sDlgTitle', width: 500, close: function() { $('#{$this->sDatatableContainerId}').datatable('onDlgCancel'); } });");

		return $sHtml;
	}

	/**
	 * @param $oSetting
	 *
	 * @return array
	 */
	public function GetAsHash($oSetting)
	{
		$aSettings = array('iDefaultPageSize' => $oSetting->iDefaultPageSize, 'oColumns' => $oSetting->aColumns);
		return $aSettings;
	}

	/**
	 * @param array $aColumns
	 * @param string $sSelectMode
	 * @param bool $bViewLink
	 *
	 * @return array
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 * @throws \Exception
	 */
	protected function GetHTMLTableConfig($aColumns, $sSelectMode, $bViewLink)
	{
		$aAttribs = array();
		if ($sSelectMode == 'multiple')
		{
			$aAttribs['form::select'] = array(
				'label' => "<input type=\"checkbox\" onClick=\"CheckAll('.selectList{$this->iListId}:not(:disabled)', this.checked);\" class=\"checkAll\"></input>",
				'description' => Dict::S('UI:SelectAllToggle+'),
				'metadata' => array(),
			);
		}
		else if ($sSelectMode == 'single')
		{
			$aAttribs['form::select'] = array('label' => '', 'description' => '', 'metadata' => array());
		}

		foreach($this->aClassAliases as $sAlias => $sClassName)
		{
			foreach($aColumns[$sAlias] as $sAttCode => $aData)
			{
				if ($aData['checked'])
				{
					if ($sAttCode == '_key_')
					{
						$sAttLabel = MetaModel::GetName($sClassName);

						$aAttribs['key_'.$sAlias] = array(
							'label' => $sAttLabel,
							'description' => '',
							'metadata' => array(
								'object_class' => $sClassName,
								'attribute_label' => $sAttLabel,
							),
						);
					}
					else
					{
						$oAttDef = MetaModel::GetAttributeDef($sClassName, $sAttCode);
						$sAttDefClass = get_class($oAttDef);
						$sAttLabel = MetaModel::GetLabel($sClassName, $sAttCode);

						$aAttribs[$sAttCode.'_'.$sAlias] = array(
							'label' => $sAttLabel,
							'description' => $oAttDef->GetOrderByHint(),
							'metadata' => array(
								'object_class' => $sClassName,
								'attribute_code' => $sAttCode,
								'attribute_type' => $sAttDefClass,
								'attribute_label' => $sAttLabel,
							),
						);
					}
				}
			}
		}
		return $aAttribs;
	}


	/**
	 * @param $aColumns
	 * @param $sSelectMode
	 * @param $iPageSize
	 * @param $bViewLink
	 * @param $aExtraParams
	 *
	 * @return array
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \Exception
	 */
	protected function GetHTMLTableValues($aColumns, $sSelectMode, $iPageSize, $bViewLink, $aExtraParams)
	{
		$bLocalize = true;
		if (isset($aExtraParams['localize_values']))
		{
			$bLocalize = (bool) $aExtraParams['localize_values'];
		}

		$aValues = array();
		$aAttDefsCache = array();
		$this->oSet->Seek(0);
		$iMaxObjects = $iPageSize;
		while (($aObjects = $this->oSet->FetchAssoc()) && ($iMaxObjects != 0))
		{
			$bFirstObject = true;
			$aRow = array();
			foreach($this->aClassAliases as $sAlias => $sClassName)
			{
				if (is_object($aObjects[$sAlias]))
				{
					$sHilightClass = MetaModel::GetHilightClass($sClassName, $aObjects[$sAlias]);
					if ($sHilightClass != '')
					{
						$aRow['@class'] = $sHilightClass;	
					}
					if ((($sSelectMode == 'single') || ($sSelectMode == 'multiple')) && $bFirstObject)
					{
						if (array_key_exists('selection_enabled', $aExtraParams) && isset($aExtraParams['selection_enabled'][$aObjects[$sAlias]->GetKey()]))
						{
							$sDisabled = ($aExtraParams['selection_enabled'][$aObjects[$sAlias]->GetKey()]) ? '' : ' disabled="disabled"';
						}
						else
						{
							$sDisabled = '';
						}
						if ($sSelectMode == 'single')
						{
							$aRow['form::select'] = "<input type=\"radio\" $sDisabled class=\"selectList{$this->iListId}\" name=\"selectObject\" value=\"".$aObjects[$sAlias]->GetKey()."\"></input>";
						}
						else
						{
							$aRow['form::select'] = "<input type=\"checkbox\" $sDisabled class=\"selectList{$this->iListId}\" name=\"selectObject[]\" value=\"".$aObjects[$sAlias]->GetKey()."\"></input>";
						}
					}
					foreach($aColumns[$sAlias] as $sAttCode => $aData)
					{
						if ($aData['checked'])
						{
							if ($sAttCode == '_key_')
							{
								$aRow['key_'.$sAlias] = array(
									'value_raw' => $aObjects[$sAlias]->GetKey(),
									'value_html' => $aObjects[$sAlias]->GetHyperLink(),
								);
							}
							else
							{
								// Prepare att. def. classes cache to avoid retrieving AttDef for each row
								if(!isset($aAttDefsCache[$sClassName][$sAttCode]))
								{
									$aAttDefClassesCache[$sClassName][$sAttCode] = get_class(MetaModel::GetAttributeDef($sClassName, $sAttCode));
								}

								// Only retrieve raw (stored) value for simple fields
								$bExcludeRawValue = false;
								foreach (cmdbAbstractObject::GetAttDefClassesToExcludeFromMarkupMetadataRawValue() as $sAttDefClassToExclude)
								{
									if (is_a($aAttDefClassesCache[$sClassName][$sAttCode], $sAttDefClassToExclude, true))
									{
										$bExcludeRawValue = true;
										break;
									}
								}

								if($bExcludeRawValue)
								{
									$aRow[$sAttCode.'_'.$sAlias] = $aObjects[$sAlias]->GetAsHTML($sAttCode, $bLocalize);
								}
								else
								{
									$aRow[$sAttCode.'_'.$sAlias] = array(
										'value_raw' => $aObjects[$sAlias]->Get($sAttCode),
										'value_html' => $aObjects[$sAlias]->GetAsHTML($sAttCode, $bLocalize),
									);
								}
							}
						}
					}
				}
				else
				{
					foreach($aColumns[$sAlias] as $sAttCode => $aData)
					{
						if ($aData['checked'])
						{
							if ($sAttCode == '_key_')
							{
								$aRow['key_'.$sAlias] = '';
							}
							else
							{
								$aRow[$sAttCode.'_'.$sAlias] = '';
							}
						}
					}
				}
				$bFirstObject = false;
			}
			$aValues[] = $aRow;
			$iMaxObjects--;
		}
		return $aValues;
	}

	/**
	 * @param WebPage $oPage
	 * @param $aColumns
	 * @param $sSelectMode
	 * @param $iPageSize
	 * @param $bViewLink
	 * @param $aExtraParams
	 *
	 * @return string
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \Exception
	 */
	public function GetHTMLTable(WebPage $oPage, $aColumns, $sSelectMode, $iPageSize, $bViewLink, $aExtraParams)
	{
		$iNbPages = ($iPageSize < 1) ? 1 : ceil($this->iNbObjects / $iPageSize);
		if ($iPageSize < 1)
		{
			$iPageSize = -1; // convention: no pagination
		}
		$aAttribs = $this->GetHTMLTableConfig($aColumns, $sSelectMode, $bViewLink);

		$aValues = $this->GetHTMLTableValues($aColumns, $sSelectMode, $iPageSize, $bViewLink, $aExtraParams);

		$sHtml = '<table class="listContainer object-list">';

		foreach($this->oSet->GetFilter()->GetInternalParams() as $sName => $sValue)
		{
			$aExtraParams['query_params'][$sName] = $sValue;
		}
		$aExtraParams['show_obsolete_data'] = $this->bShowObsoleteData;

		$sHtml .= "<tr><td>";
		$sHtml .= $oPage->GetTable($aAttribs, $aValues);
		$sHtml .= '</td></tr>';
		$sHtml .= '</table>';
		$iCount = $this->iNbObjects;

		$aArgs = $this->oSet->GetArgs();
		$sExtraParams = addslashes(str_replace('"', "'", json_encode(array_merge($aExtraParams, $aArgs)))); // JSON encode, change the style of the quotes and escape them
		$sSelectModeJS = '';
		$sHeaders = '';
		if (($sSelectMode == 'single') || ($sSelectMode == 'multiple'))
		{
			$sSelectModeJS = $sSelectMode;
			$sHeaders = 'headers: { 0: {sorter: false}},';
		}
		$sDisplayKey = ($bViewLink) ? 'true' : 'false';
		// Protect against duplicate elements in the Zlist
		$aUniqueOrderedList = array();
		foreach($this->aClassAliases as $sAlias => $sClassName)
		{
			foreach($aColumns[$sAlias] as $sAttCode => $aData)
			{
				if ($aData['checked'])
				{
					$aUniqueOrderedList[$sAttCode] = true;
				}
			}
		}
		$aUniqueOrderedList = array_keys($aUniqueOrderedList);
		$sJSColumns = json_encode($aColumns);
		$sJSClassAliases = json_encode($this->aClassAliases);
		$sCssCount = isset($aExtraParams['cssCount']) ? ", cssCount: '{$aExtraParams['cssCount']}'" : '';
		$this->oSet->ApplyParameters();
		// Display the actual sort order of the table
		$aRealSortOrder = $this->oSet->GetRealSortOrder();
		$aDefaultSort = array();
		$iColOffset = 0;
		if (($sSelectMode == 'single') || ($sSelectMode == 'multiple'))
		{
			$iColOffset += 1;
		}
		if ($bViewLink)
		{
//			$iColOffset += 1;
		}
		foreach($aRealSortOrder as $sColCode => $bAscending)
		{
			$iPos = array_search($sColCode, $aUniqueOrderedList);
			if ($iPos !== false)
			{
				$aDefaultSort[] = "[".($iColOffset+$iPos).",".($bAscending ? '0' : '1')."]";
			}
			else if (($iPos = array_search(preg_replace('/_friendlyname$/', '', $sColCode), $aUniqueOrderedList)) !== false)
			{
				// if sorted on the friendly name of an external key, then consider it sorted on the column that shows the links
				$aDefaultSort[] = "[".($iColOffset+$iPos).",".($bAscending ? '0' : '1')."]";
			}
			else if($sColCode == 'friendlyname' && $bViewLink)
			{
				$aDefaultSort[] = "[".($iColOffset).",".($bAscending ? '0' : '1')."]";
			}
		}
		$sFakeSortList = '';
		if (count($aDefaultSort) > 0)
		{
			$sFakeSortList = '['.implode(',', $aDefaultSort).']';
		}
		$sOQL = addslashes($this->oSet->GetFilter()->serialize());
		$oPage->add_ready_script(
<<<JS
var oTable = $('#{$this->sDatatableContainerId} table.listResults');
oTable.tableHover();
oTable
	.tablesorter({ $sHeaders widgets: ['myZebra', 'truncatedList']})
	.tablesorterPager({
		container: $('#pager{$this->iListId}'),
		totalRows:$iCount,
		size: $iPageSize,
		filter: '$sOQL',
		extra_params: '$sExtraParams',
		select_mode: '$sSelectModeJS',
		displayKey: $sDisplayKey,
		table_id: '{$this->sDatatableContainerId}',
		columns: $sJSColumns,
		class_aliases: $sJSClassAliases $sCssCount
	});
JS
	);
		if ($sFakeSortList != '')
		{
			$oPage->add_ready_script("oTable.trigger(\"fakesorton\", [$sFakeSortList]);");
		}
		return $sHtml;
	}

	/**
	 * @param WebPage $oPage
	 * @param $iDefaultPageSize
	 * @param $iStart
	 */
	public function UpdatePager(WebPage $oPage, $iDefaultPageSize, $iStart)
	{
		$iPageSize = $iDefaultPageSize;
		$iPageIndex = 0;
		$sHtml = $this->GetPager($oPage, $iPageSize, $iDefaultPageSize, $iPageIndex);
		$oPage->add_ready_script("$('#pager{$this->iListId}').html('".json_encode($sHtml)."');");
		if ($iDefaultPageSize < 1)
		{
			$oPage->add_ready_script("$('#pager{$this->iListId}').parent().hide()");
		}
		else
		{
			$oPage->add_ready_script("$('#pager{$this->iListId}').parent().show()");
		}
	}
}

/**
 * Simplified version of the data table with less "decoration" (and no paging)
 * which is optimized for printing
 */
class PrintableDataTable extends DataTable
{
	/**
	 * @param WebPage $oPage
	 * @param $iPageSize
	 * @param $iDefaultPageSize
	 * @param $iPageIndex
	 * @param $aColumns
	 * @param $bActionsMenu
	 * @param $bToolkitMenu
	 * @param $sSelectMode
	 * @param $bViewLink
	 * @param $aExtraParams
	 *
	 * @return string
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 */
	public function GetAsHTML(WebPage $oPage, $iPageSize, $iDefaultPageSize, $iPageIndex, $aColumns, $bActionsMenu, $bToolkitMenu, $sSelectMode, $bViewLink, $aExtraParams)
	{
		return $this->GetHTMLTable($oPage, $aColumns, $sSelectMode, -1, $bViewLink, $aExtraParams);
	}

	/**
	 * @param WebPage $oPage
	 * @param $aColumns
	 * @param $sSelectMode
	 * @param $iPageSize
	 * @param $bViewLink
	 * @param $aExtraParams
	 *
	 * @return string
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 */
	public function GetHTMLTable(WebPage $oPage, $aColumns, $sSelectMode, $iPageSize, $bViewLink, $aExtraParams)
	{
		$iNbPages = ($iPageSize < 1) ? 1 : ceil($this->iNbObjects / $iPageSize);
		if ($iPageSize < 1)
		{
			$iPageSize = -1; // convention: no pagination
		}
		$aAttribs = $this->GetHTMLTableConfig($aColumns, $sSelectMode, $bViewLink);
	
		$aValues = $this->GetHTMLTableValues($aColumns, $sSelectMode, $iPageSize, $bViewLink, $aExtraParams);
	
		$sHtml = $oPage->GetTable($aAttribs, $aValues);
		
		return $sHtml;
	}
}
