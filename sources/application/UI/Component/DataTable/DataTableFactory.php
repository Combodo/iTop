<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Component\DataTable;

use CMDBObjectSet;
use cmdbAbstractObject;
use Combodo\iTop\Application\UI\Component\Html\Html;
use Combodo\iTop\Application\UI\Component\Panel\PanelFactory;
use MetaModel;
use appUserPreferences;
use UserRights;
use MenuBlock;
use Combodo\iTop\Application\UI\Layout\UIContentBlock;
use WebPage;
use Dict;

/**
 * Class DataTableFactory
 *
 * @internal
 * @package Combodo\iTop\Application\UI\Component\DataTable
 * @since 3.0.0
 */
class DataTableFactory
{
	public static function MakeForResult(WebPage $oPage, string $sListId, CMDBObjectSet $oSet, $aExtraParams = array())
	{
		$oPanel = PanelFactory::MakeForClass( $oSet->GetClass(), "Result");
		$oDataTable = DataTableFactory::MakeForRendering( $sListId,  $oSet, $aExtraParams );
		$oPanel->AddMainBlock($oDataTable);

		$oMenuBlock = new MenuBlock($oSet->GetFilter(), 'list');
		$oBlock = $oMenuBlock->GetRenderContent($oPage, $aExtraParams, $sListId);
		$oBlockMenu = new UIContentBlock();
		$oBlockMenu->AddSubBlock($oBlock);
		$oPanel->AddToolbarBlock($oBlockMenu);

		return $oPanel;
	}
	public static function MakeForObject(WebPage $oPage, string $sListId, CMDBObjectSet $oSet, $aExtraParams = array())
	{
		$oPanel = PanelFactory::MakeForClass( $oSet->GetClass(), "Result");
		$oDataTable = DataTableFactory::MakeForRenderingObject( $sListId,  $oSet, $aExtraParams );
		$oPanel->AddMainBlock($oDataTable);

		$oMenuBlock = new MenuBlock($oSet->GetFilter(), 'list');
		$oBlock = $oMenuBlock->GetRenderContent($oPage, $aExtraParams, $sListId);
		$oBlockMenu = new UIContentBlock();
		$oBlockMenu->AddSubBlock($oBlock);
		$oPanel->AddToolbarBlock($oBlockMenu);

		return $oPanel;
	}
	/**
	 * Make a basis Panel component
	 *
	 * @param string $sTitle
	 *
	 * @return DataTableBlock
	 */
	public static function MakeForRendering(string $sListId, CMDBObjectSet $oSet, $aExtraParams = array())
	{
		$oDataTable = new DataTableBlock('datatable_'.$sListId);
		///////////////////////////////////////////////////
		/*TODO 3.0.0 PrintableVersion
		if ($oPage->IsPrintableVersion() || $oPage->is_pdf())
		{
			return self::GetDisplaySetForPrinting($oPage, $oSet, $aExtraParams);
		}
		*/

		// Initialize and check the parameters
		$bViewLink = isset($aExtraParams['view_link']) ? $aExtraParams['view_link'] : true;
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
		$bSelectMode = isset($aExtraParams['selection_mode']) ? $aExtraParams['selection_mode'] == true : false;
		$bSingleSelectMode = isset($aExtraParams['selection_type']) ? ($aExtraParams['selection_type'] == 'single') : false;

		$aExtraFieldsRaw = isset($aExtraParams['extra_fields']) ? explode(',',
			trim($aExtraParams['extra_fields'])) : array();
		$aExtraFields = array();
		foreach ($aExtraFieldsRaw as $sFieldName) {
			// Ignore attributes not of the main queried class
			if (preg_match('/^(.*)\.(.*)$/', $sFieldName, $aMatches)) {
				$sClassAlias = $aMatches[1];
				$sAttCode = $aMatches[2];
				if ($sClassAlias == $oSet->GetFilter()->GetClassAlias()) {//$oSet->GetFilter()->GetSelectedClasses()
					$aExtraFields[] = $sAttCode;
				}
			} else {
				$aExtraFields[] = $sFieldName;
			}
		}
		$sClassName = $oSet->GetFilter()->GetClass();
		$sZListName = isset($aExtraParams['zlist']) ? ($aExtraParams['zlist']) : 'list';
		if ($sZListName !== false) {
			$aList = cmdbAbstractObject::FlattenZList(MetaModel::GetZListItems($sClassName, $sZListName));
			$aList = array_merge($aList, $aExtraFields);
		} else {
			$aList = $aExtraFields;
		}

		// Filter the list to removed linked set since we are not able to display them here
		foreach ($aList as $index => $sAttCode) {
			$oAttDef = MetaModel::GetAttributeDef($sClassName, $sAttCode);
			if ($oAttDef instanceof AttributeLinkedSet) {
				// Removed from the display list
				unset($aList[$index]);
			}
		}

		if (!empty($sLinkageAttribute)) {
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
			foreach ($aList as $sLinkAttCode) {
				$oLinkAttDef = $aAttDefs[$sLinkAttCode];
				if ((!$oLinkAttDef->IsExternalKey()) && (!$oLinkAttDef->IsExternalField())) {
					$aDisplayList[] = $sLinkAttCode;
				}
			}
			// Then display all the attributes neither specific to the link record nor to the 'linkage' object (because the latter are constant)
			foreach ($aList as $sLinkAttCode) {
				$oLinkAttDef = $aAttDefs[$sLinkAttCode];
				if (($oLinkAttDef->IsExternalKey() && ($sLinkAttCode != $sLinkageAttribute))
					|| ($oLinkAttDef->IsExternalField() && ($oLinkAttDef->GetKeyAttCode() != $sLinkageAttribute))) {
					$aDisplayList[] = $sLinkAttCode;
				}
			}
			// First display all the attributes specific to the link
			// Then display all the attributes linked to the other end of the relationship
			$aList = $aDisplayList;
		}

		$sSelectMode = 'none';
		if ($bSelectMode) {
			$sSelectMode = $bSingleSelectMode ? 'single' : 'multiple';
		}

		$sClassAlias = $oSet->GetClassAlias();
		$bDisplayLimit = isset($aExtraParams['display_limit']) ? $aExtraParams['display_limit'] : true;

		$sTableId = isset($aExtraParams['table_id']) ? $aExtraParams['table_id'] : null;
		$aClassAliases = array($sClassAlias => $sClassName);
		$oDefaultSettings = DataTableSettings::GetDataModelSettings($aClassAliases, $bViewLink, array($sClassAlias => $aList));

		if ($bDisplayLimit) {
			$iDefaultPageSize = appUserPreferences::GetPref('default_page_size', MetaModel::GetConfig()->GetMinDisplayLimit());
			$oDefaultSettings->iDefaultPageSize = $iDefaultPageSize;
		} else {
			$oDefaultSettings->iDefaultPageSize = 0;
		}
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
		$oSet->SetOrderBy($oCustomSettings->GetSortOrder());

		// Load only the requested columns
		$aColumnsToLoad = array();
		foreach ($oCustomSettings->aColumns as $sAlias => $aColumnsInfo) {
			foreach ($aColumnsInfo as $sAttCode => $aData) {
				if ($sAttCode != '_key_') {
					if ($aData['checked']) {
						$aColumnsToLoad[$sAlias][] = $sAttCode;
					} else {
						// See if this column is a must to load
						$sClass = $aClassAliases[$sAlias];
						$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
						if ($oAttDef->alwaysLoadInTables()) {
							$aColumnsToLoad[$sAlias][] = $sAttCode;
						}
					}
				}
			}
		}
		$oSet->OptimizeColumnLoad($aColumnsToLoad);

		$aColumnDefinition = [];
		foreach ($aClassAliases as $sClassAlias => $sClassName) {
			foreach ($oCustomSettings->aColumns[$sClassAlias] as $sAttCode => $aData) {
				if ($aData['checked']) {
					if ($sAttCode == '_key_') {
						$aColumnDefinition[] = [
							'description' => $aData['label'],
							'object_class' => $sClassName,
							'class_alias' => $sClassAlias,
							'attribute_code' => $sAttCode,
							'attribute_type' => '_key_',
							'attribute_label' => $aData['alias'],
							"render" => "return '<a class=\'object-ref-link\' href=  \'UI.php?operation=details&class=".$sClassName."&id='+data+'\'>'+row['".$sClassAlias."/friendlyname']+'</a>' ;",
						];
					} else {
						$oAttDef = MetaModel::GetAttributeDef($sClassName, $sAttCode);
						$sAttDefClass = get_class($oAttDef);
						$sAttLabel = MetaModel::GetLabel($sClassName, $sAttCode);
						$aColumnDefinition[] = [
							'description' => $oAttDef->GetOrderByHint(),
							'object_class' => $sClassName,
							'class_alias' => $sClassAlias,
							'attribute_code' => $sAttCode,
							'attribute_type' => $sAttDefClass,
							'attribute_label' => $sAttLabel,
							"render" => $oAttDef->GetRenderForDataTable($sClassAlias),
						];
					}
				}
			}
		}

		$aOptions = [];
		if($oDefaultSettings != null)
		{
			$aOptions['oDefaultSettings'] = json_encode(array('iDefaultPageSize' => $oDefaultSettings->iDefaultPageSize, 'oColumns' => $oDefaultSettings->aColumns));
		}

		if ($sSelectMode == 'multiple') {
			$aOptions['select'] = "multi";
		} else if ($sSelectMode == 'single') {
			$aOptions['select'] = "single";
		}

		$aOptions['iPageSize'] = 10;
		if ($oCustomSettings->iDefaultPageSize > 0) {
			$aOptions['iPageSize'] = $oCustomSettings->iDefaultPageSize;
		}

		$aOptions['sTableId'] =$sTableId;
		$aOptions['bUseCustomSettings'] =$bUseCustomSettings;
		$aOptions['bViewLink'] =$bViewLink;

		$oDataTable->SetOptions($aOptions);
		$oDataTable->SetAjaxUrl("ajax.render.php");
		$oDataTable->SetAjaxData(json_encode([
			"operation" => 'search',
			"filter" => $oSet->GetFilter()->serialize(),
			"columns" => $oCustomSettings->aColumns,
			"extra_params" => $aExtraParams,
			"class_aliases" => $aClassAliases,
		]));
		$oDataTable->SetDisplayColumns($aColumnDefinition);
		$oDataTable->SetResultColumns($oCustomSettings->aColumns);

		return $oDataTable;
	}
	public static function MakeForRenderingObject(string $sListId, CMDBObjectSet $oSet, $aExtraParams = array())
	{
		$oDataTable = new DataTableBlock('datatable_'.$sListId);
		$aList = array();

		// Initialize and check the parameters
		$bViewLink = isset($aExtraParams['view_link']) ? $aExtraParams['view_link'] : true;
		// Check if there is a list of aliases to limit the display to...
		$aDisplayAliases = isset($aExtraParams['display_aliases']) ? explode(',',
			$aExtraParams['display_aliases']) : array();
		$sZListName = isset($aExtraParams['zlist']) ? ($aExtraParams['zlist']) : 'list';

		$aExtraFieldsRaw = isset($aExtraParams['extra_fields']) ? explode(',',
			trim($aExtraParams['extra_fields'])) : array();
		$aExtraFields = array();
		$sAttCode = '';
		foreach($aExtraFieldsRaw as $sFieldName)
		{
			// Ignore attributes not of the main queried class
			if (preg_match('/^(.*)\.(.*)$/', $sFieldName, $aMatches))
			{
				$sClassAlias = $aMatches[1];
				$sAttCode = $aMatches[2];
				if (array_key_exists($sClassAlias, $oSet->GetSelectedClasses()))
				{
					$aExtraFields[$sClassAlias][] = $sAttCode;
				}
			}
			else
			{
				$aExtraFields['*'] = $sAttCode;
			}
		}

		$aClassAliases = $oSet->GetFilter()->GetSelectedClasses();
		$aAuthorizedClasses = array();
		foreach($aClassAliases as $sAlias => $sClassName)
		{
			if ((UserRights::IsActionAllowed($sClassName, UR_ACTION_READ, $oSet) != UR_ALLOWED_NO) &&
				((count($aDisplayAliases) == 0) || (in_array($sAlias, $aDisplayAliases))))
			{
				$aAuthorizedClasses[$sAlias] = $sClassName;
			}
		}
		foreach($aAuthorizedClasses as $sAlias => $sClassName)
		{
			if (array_key_exists($sAlias, $aExtraFields))
			{
				$aList[$sAlias] = $aExtraFields[$sAlias];
			}
			else
			{
				$aList[$sAlias] = array();
			}
			if ($sZListName !== false)
			{
				$aDefaultList = self::FlattenZList(MetaModel::GetZListItems($sClassName, $sZListName));

				$aList[$sAlias] = array_merge($aDefaultList, $aList[$sAlias]);
			}

			// Filter the list to removed linked set since we are not able to display them here
			foreach ($aList[$sAlias] as $index => $sAttCode)
			{
				$oAttDef = MetaModel::GetAttributeDef($sClassName, $sAttCode);
				if ($oAttDef instanceof AttributeLinkedSet)
				{
					// Removed from the display list
					unset($aList[$sAlias][$index]);
				}
			}

			if (empty($aList[$sAlias]))
			{
				unset($aList[$sAlias], $aAuthorizedClasses[$sAlias]);
			}
		}

		$sSelectMode = 'none';

		$oDefaultSettings = DataTableSettings::GetDataModelSettings($aAuthorizedClasses, $bViewLink, $aList);

		$bDisplayLimit = isset($aExtraParams['display_limit']) ? $aExtraParams['display_limit'] : true;
		if ($bDisplayLimit)
		{
			$iDefaultPageSize = appUserPreferences::GetPref('default_page_size',
				MetaModel::GetConfig()->GetMinDisplayLimit());
			$oDefaultSettings->iDefaultPageSize = $iDefaultPageSize;
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
		$oSet->SetOrderBy($oCustomSettings->GetSortOrder());

		// Load only the requested columns
		$aColumnsToLoad = array();
		foreach ($oCustomSettings->aColumns as $sAlias => $aColumnsInfo) {
			foreach ($aColumnsInfo as $sAttCode => $aData) {
				if ($sAttCode != '_key_') {
					if ($aData['checked']) {
						$aColumnsToLoad[$sAlias][] = $sAttCode;
					} else {
						// See if this column is a must to load
						$sClass = $aClassAliases[$sAlias];
						$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
						if ($oAttDef->alwaysLoadInTables()) {
							$aColumnsToLoad[$sAlias][] = $sAttCode;
						}
					}
				}
			}
		}
		$oSet->OptimizeColumnLoad($aColumnsToLoad);

		$aColumnDefinition = [];
		foreach ($aClassAliases as $sClassAlias => $sClassName) {
			foreach ($oCustomSettings->aColumns[$sClassAlias] as $sAttCode => $aData) {
				if ($aData['checked']) {
					if ($sAttCode == '_key_') {
						$aColumnDefinition[] = [
							'description' => $aData['label'],
							'object_class' => $sClassName,
							'class_alias' => $sClassAlias,
							'attribute_code' => $sAttCode,
							'attribute_type' => '_key_',
							'attribute_label' => $aData['alias'],
							"render" => "return '<a class=\'object-ref-link\' href=  \'UI.php?operation=details&class=".$sClassName."&id='+data+'\'>'+row['".$sClassAlias."/friendlyname']+'</a>' ;",
						];
					} else {
						$oAttDef = MetaModel::GetAttributeDef($sClassName, $sAttCode);
						$sAttDefClass = get_class($oAttDef);
						$sAttLabel = MetaModel::GetLabel($sClassName, $sAttCode);
						$aColumnDefinition[] = [
							'description' => $oAttDef->GetOrderByHint(),
							'object_class' => $sClassName,
							'class_alias' => $sClassAlias,
							'attribute_code' => $sAttCode,
							'attribute_type' => $sAttDefClass,
							'attribute_label' => $sAttLabel,
							"render" => $oAttDef->GetRenderForDataTable($sClassAlias),
						];
					}
				}
			}
		}

		$aOptions = [];
		if($oDefaultSettings != null)
		{
			$aOptions['oDefaultSettings'] = json_encode(array('iDefaultPageSize' => $oDefaultSettings->iDefaultPageSize, 'oColumns' => $oDefaultSettings->aColumns));
		}

		if ($sSelectMode == 'multiple') {
			$aOptions['select'] = "multi";
		} else if ($sSelectMode == 'single') {
			$aOptions['select'] = "single";
		}

		$aOptions['iPageSize'] = 10;
		if ($oCustomSettings->iDefaultPageSize > 0) {
			$aOptions['iPageSize'] = $oCustomSettings->iDefaultPageSize;
		}

		$aOptions['sTableId'] =$sTableId;
		$aOptions['bUseCustomSettings'] =$bUseCustomSettings;
		$aOptions['bViewLink'] =$bViewLink;

		$oDataTable->SetOptions($aOptions);
		$oDataTable->SetAjaxUrl("ajax.render.php");
		$oDataTable->SetAjaxData(json_encode([
			"operation" => 'search',
			"filter" => $oSet->GetFilter()->serialize(),
			"columns" => $oCustomSettings->aColumns,
			"extra_params" => $aExtraParams,
			"class_aliases" => $aClassAliases,
		]));
		$oDataTable->SetDisplayColumns($aColumnDefinition);
		$oDataTable->SetResultColumns($oCustomSettings->aColumns);

		return $oDataTable;
	}
	public static function GetOptionsForRendering(array $aColumns,string $sSelectMode, string $sFilter, int $iLength, array $aExtraParams)
	{
		$aOptions = [];

		$aColumnsDefinitions = [];
		$aColumnDefinition = [];
		$aClassAliases = [];

		foreach ($aColumns as $sClassName => $aClassColumns) {
			$aClassAliases[$sClassName]=$sClassName;
			foreach ($aClassColumns as $sAttCode => $aData) {
				if ($aData['checked'] == "true") {
					$aColumnDefinition["width"] = "auto";
					$aColumnDefinition["searchable"]= false;
					$aColumnDefinition["sortable"]= true;
					$aColumnDefinition["defaultContent"]= "";
					$aColumnDefinition["type"]= "html";

					if ($sAttCode == '_key_') {
						$aColumnDefinition["title"] =$aData['alias'];
						$aColumnDefinition['metadata'] =[
								'object_class'=> $sClassName,
								'attribute_code'=> $sAttCode,
								'attribute_type'=> '_key_',
								'attribute_label'=> $aData['alias'],
							];
						$aColumnDefinition["data"] = $sClassName."/".$sAttCode;
						$aColumnDefinition["render"] =[
							"display"=> "return '<a class=\'object-ref-link\' href=\'UI.php?operation=details&class=".$sClassName."&id='+data+'\'>'+row['".$sClassName."/friendlyname']+'</a>' ;",
							"_"=>$sClassName."/".$sAttCode,
						];
					} else {
						$oAttDef = MetaModel::GetAttributeDef($sClassName, $sAttCode);
						$sAttDefClass = get_class($oAttDef);
						$sAttLabel = MetaModel::GetLabel($sClassName, $sAttCode);

						$aColumnDefinition["title"] =$sAttLabel;
						$aColumnDefinition['metadata'] =[
								'object_class'=> $sClassName,
								'attribute_code'=> $sAttCode,
								'attribute_type'=> $sAttDefClass,
								'attribute_label'=> $sAttLabel,
							];
						$aColumnDefinition["data"] = $sClassName."/".$sAttCode;
						$aColumnDefinition["render"] =[
							"display"=> $oAttDef->GetRenderForDataTable($sClassName),
							"_"=>$sClassName."/".$sAttCode,
						];
					}
					array_push($aColumnsDefinitions,$aColumnDefinition);
				}
			}
		}

		$aOptions['select'] =$sSelectMode;

		$aOptions['pageLength'] = $iLength;

		$sAjaxData=json_encode([
			"operation" => 'search',
			"filter" => $sFilter,
			"columns" => $aColumns,
			"extra_params" => $aExtraParams,
			"class_aliases" => $aClassAliases,
		]);


		$aOptions[] = [
				"language" =>
					["processing"=> 	 Dict::Format('UI:Datatables:Language:Processing'),
						"search"=> 		  Dict::Format('UI:Datatables:Language:Search'),
						"lengthMenu"=> 	Dict::Format('UI:Datatables:Language:LengthMenu'),
						"zeroRecords"=> 	 Dict::Format('UI:Datatables:Language:ZeroRecords'),
						"info"=>           Dict::Format('UI:Datatables:Language:Info'),
						"infoEmpty"=> 	   Dict::Format('UI:Datatables:Language:InfoEmpty'),
						"infoFiltered"=> 	Dict::Format('UI:Datatables:Language:InfoFiltered'),
						"emptyTable"=> 	  Dict::Format('UI:Datatables:Language:EmptyTable'),
						"paginate"=>  [
							"first"=> 	  "<<",
							"previous"=>    "<",
							"next"=> 	   ">",
							"last"=> 	   ">>"
						],
						"aria"=>  [
							"sortAscending"=>  Dict::Format( 'UI:Datatables:Language:Sort:Ascending'),
							"sortDescending"=> Dict::Format('UI:Datatables:Language:Sort:Descending')
						],
					],
				"lengthMenu" => Dict::Format( 'Portal:Datatables:Language:DisplayLength:All'),
				"dom"=>  "<'ibo-datatable-toolbar'pil>t<'ibo-datatable-toolbar'pil>",
				"order"=>  [],
				"filter"=> false,
				"processing"=>  true,
				"serverSide"=>  true,
				"columns"=> $aColumnsDefinitions,
				"allColumns"=> $aColumns,
				'ajax' => '$.fn.dataTable.pipeline( {
					"url": "ajax.render.php",
					"data": '.$sAjaxData.',
					"method":	"post",
					"pages": 5 // number of pages to cache
				} )'
			];


		return $aOptions;
	}
}