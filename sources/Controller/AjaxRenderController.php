<?php
/*
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Controller;

use AjaxPage;
use ApplicationContext;
use ApplicationMenu;
use AttributeLinkedSet;
use AttributeOneWayPassword;
use BinaryExpression;
use BulkExport;
use BulkExportException;
use CMDBObjectSet;
use CMDBSource;
use Combodo\iTop\Application\UI\Base\Component\DataTable\DataTableSettings;
use Combodo\iTop\Application\UI\Base\Component\DataTable\DataTableUIBlockFactory;
use DBObjectSearch;
use DBObjectSet;
use DBSearch;
use Dict;
use Exception;
use ExecutionKPI;
use Expression;
use FieldExpression;
use FunctionExpression;
use JsonPage;
use MetaModel;
use ScalarExpression;
use UILinksWidget;
use utils;
use WizardHelper;

class AjaxRenderController
{
	/**
	 * @param \AjaxPage $oPage
	 *
	 * @param bool $bTokenOnly
	 *
	 * @throws \Exception
	 */
	public function ExportBuild(AjaxPage $oPage, bool $bTokenOnly)
	{
		register_shutdown_function(function () {
			$aErr = error_get_last();
			if (($aErr !== null) && ($aErr['type'] & (E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR))) {
				ob_end_clean();
				echo json_encode(array('code' => 'error', 'percentage' => 100, 'message' => Dict::Format('UI:Error_Details', $aErr['message'])));
			}
		});

		try {
			$token = utils::ReadParam('token', null);
			$sTokenForDisplay = utils::HtmlEntities($token);
			$aResult = array( // Fallback error, just in case
				'code' => 'error',
				'percentage' => 100,
				'message' => "Export not found for token: '$sTokenForDisplay'",
			);
			$data = '';
			if ($token === null) {
				if ($bTokenOnly) {
					throw new Exception('Access not allowed');
				}
				$sFormat = utils::ReadParam('format', '');
				$sExpression = utils::ReadParam('expression', null, false, 'raw_data');
				$iQueryId = utils::ReadParam('query', null);
				if ($sExpression === null) {
					$oQuerySearch = DBObjectSearch::FromOQL('SELECT QueryOQL WHERE id = :query_id', array('query_id' => $iQueryId));
					$oQuerySearch->UpdateContextFromUser();
					$oQueries = new DBObjectSet($oQuerySearch);
					if ($oQueries->Count() > 0) {
						$oQuery = $oQueries->Fetch();
						$sExpression = $oQuery->Get('oql');
					} else {
						$aResult = array('code' => 'error', 'percentage' => 100, 'message' => "Invalid query phrasebook identifier: '$iQueryId'");
					}
				}
				if ($sExpression !== null) {
					$oSearch = DBObjectSearch::FromOQL($sExpression);
					$oSearch->UpdateContextFromUser();
					$oExporter = BulkExport::FindExporter($sFormat, $oSearch);
					$oExporter->SetObjectList($oSearch);
					$oExporter->SetFormat($sFormat);
					$oExporter->SetChunkSize(EXPORTER_DEFAULT_CHUNK_SIZE);
					$oExporter->ReadParameters();
				}

				// First pass, generate the headers
				$data .= $oExporter->GetHeader();
			} else {
				$oExporter = BulkExport::FindExporterFromToken($token);
				if (utils::ReadParam('start', 0, false, 'integer') == 1) {
					// From portal, the first call is using a token
					$data .= $oExporter->GetHeader();
				}
			}

			if ($oExporter) {
				$data .= $oExporter->GetNextChunk($aResult);
				if ($aResult['code'] != 'done') {
					$oExporter->AppendToTmpFile($data);
					$aResult['token'] = $oExporter->SaveState();
				} else {
					// Last pass
					$data .= $oExporter->GetFooter();
					$oExporter->AppendToTmpFile($data);
					$aResult['token'] = $oExporter->SaveState();
					if (substr($oExporter->GetMimeType(), 0, 5) == 'text/') {
						// Result must be encoded in UTF-8 to be passed as part of a JSON structure
						$sCharset = $oExporter->GetCharacterSet();
						if (strtoupper($sCharset) != 'UTF-8') {
							$aResult['text_result'] = iconv($sCharset, 'UTF-8', file_get_contents($oExporter->GetTmpFilePath()));
						} else {
							$aResult['text_result'] = file_get_contents($oExporter->GetTmpFilePath());
						}
						$aResult['mime_type'] = $oExporter->GetMimeType();
					}
					$aResult['message'] = Dict::Format('Core:BulkExport:ClickHereToDownload_FileName', $oExporter->GetDownloadFileName());
				}
			}
			$oPage->add(json_encode($aResult));
		} catch (BulkExportException $e) {
			$aResult = array('code' => 'error', 'percentage' => 100, 'message' => utils::HtmlEntities($e->GetLocalizedMessage()));
			$oPage->add(json_encode($aResult));
		} catch (Exception $e) {
			$aResult = array('code' => 'error', 'percentage' => 100, 'message' => utils::HtmlEntities($e->getMessage()));
			$oPage->add(json_encode($aResult));
		}
	}

	/**
	 * Get all the menus count
	 *
	 * The resulting JSON is added to the page with the format:
	 * {"code": "done or error", "counts": {"menu_id_1": count1, "menu_id_2": count2...}}
	 *
	 * @param \AjaxPage $oPage
	 */
	public function GetMenusCount(AjaxPage $oPage)
	{
		$aCounts = ApplicationMenu::GetMenusCount();
		$aResult = ['code' => 'done', 'counts' => $aCounts];
		$oPage->add(json_encode($aResult));
	}

	/**
	 * @param string $sFilter
	 *
	 * @return array
	 * @throws \Exception
	 */
	public static function SearchAndRefresh(string $sFilter): array
	{
		$extraParams = utils::ReadParam('extra_params', '', false, 'raw_data');
		$aExtraParams = array();
		if (is_array($extraParams)) {
			$aExtraParams = $extraParams;
		} else {
			$sExtraParams = stripslashes($extraParams);
			if (!empty($sExtraParams)) {
				$val = json_decode(str_replace("'", '"', $sExtraParams), true /* associative array */);
				if ($val !== null) {
					$aExtraParams = $val;
				}
			}
		}
		if (!isset($aExtraParams['list_id'])) {
			$sListId = utils::ReadParam('list_id', null);
			if (!is_null($sListId)) {
				$aExtraParams['list_id'] = $sListId;
			}
		}

		$sTableId = utils::ReadParam('list_id', '');
		$iLength = utils::ReadParam('end', 10);
		$aColumns = utils::ReadParam('columns', array(), false, 'raw_data');
		$sSelectMode = utils::ReadParam('select_mode', '');
		$aClassAliases = utils::ReadParam('class_aliases', array());
		$aResult = DataTableUIBlockFactory::GetOptionsForRendering($aColumns, $sSelectMode, $sFilter, $iLength, $aClassAliases, $aExtraParams, $sTableId);

		return $aResult;
	}

	/**
	 * @param string $sEncoding
	 * @param string $sFilter
	 *
	 * @return array
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 */
	public static function Search(string $sEncoding, string $sFilter):array
	{
		$extraParams = utils::ReadParam('extra_params', '', false, 'raw_data');
		$aExtraParams = array();
		if (is_array($extraParams)) {
			$aExtraParams = $extraParams;
		} else {
			$sExtraParams = stripslashes($extraParams);
			if (!empty($sExtraParams)) {
				$val = json_decode(str_replace("'", '"', $sExtraParams), true /* associative array */);
				if ($val !== null) {
					$aExtraParams = $val;
				}
			}
		}
		if ($sEncoding == 'oql') {
			$oFilter = DBSearch::FromOQL($sFilter);
		} else {
			$oFilter = DBSearch::unserialize($sFilter);
		}
		$iStart = utils::ReadParam('start', 0);
		$iEnd = utils::ReadParam('end', 1);
		$iDrawNumber = utils::ReadParam('draw', 1);

		$aSort = utils::ReadParam('order', [], false, 'array');
		$bForceSort = false;
		if (count($aSort) > 0) {
			$iSortCol = $aSort[0]["column"];
			$sSortOrder = $aSort[0]["dir"];
		} else{
			$bForceSort = true;
			$iSortCol = 0;
			$sSortOrder = "asc";
		}
		$sSelectMode = utils::ReadParam('select_mode', '');
		if (!empty($sSelectMode) && ($sSelectMode != 'none')) {
			// The first column is used for the selection (radio / checkbox) and is not sortable
			$iSortCol--;
		}
		$aColumns = utils::ReadParam('columns', array(), false, 'raw_data');
		$aClassAliases = utils::ReadParam('class_aliases', array());

		foreach ($aColumns as $sClass => $aAttCodes) {
			foreach ($aAttCodes as $sAttCode => $aAttProperties) {
				if (!array_key_exists('checked', $aAttProperties)) {
					/**
					 * For data passed in XHR queries with some volume, on some servers data can be cut off because of a php.ini's `max_input_vars` set too low
					 *
					 * Normal format is :
					 * ```
					 * array (
					 *   'UserRequest' =>
					 *   array (
					 *     '_key_' =>
					 *     array (
					 *       'label' => 'User Request (Link)',
					 *       'checked' => 'true',
					 *       'disabled' => 'true',
					 *       'alias' => 'UserRequest',
					 *       'code' => '_key_',
					 *       'sort' => 'none',
					 *     ),
					 *    // ...
					 *     'parent_request_id_friendlyname' =>
					 *     array (
					 *       'label' => 'parent_request_id_friendlyname (Friendly Name)',
					 *       'checked' => 'false',
					 *       'disabled' => 'false',
					 *       'alias' => 'UserRequest',
					 *       'code' => 'parent_request_id_friendlyname',
					 *       'sort' => 'none',
					 *     ),
					 * )
					 * ```
					 *
					 * While with a low max_input_vars we can get :
					 * ```
					 * array (
					 *   'UserRequest' =>
					 *   array (
					 *     '_key_' =>
					 *     array (
					 *       'label' => 'User Request (Link)',
					 *       'checked' => 'true',
					 *       'disabled' => 'true',
					 *       'alias' => 'UserRequest',
					 *       'code' => '_key_',
					 *       'sort' => 'none',
					 *     ),
					 *    // ...
					 *     'parent_request_id_friendlyname' =>
					 *     array (
					 *       'label' => 'parent_request_id_friendlyname (Friendly Name)',
					 *     ),
					 * )
					 * ```
					 *
					 * @link https://www.php.net/manual/fr/info.configuration.php#ini.max-input-vars PHP doc on `max_input_vars`
					 * @link https://www.itophub.io/wiki/page?id=latest%3Ainstall%3Aphp_and_mysql_configuration#php_mysql_mariadb_settings Combodo's recommended options
					 */
					$iMaxInputVarsValue = ini_get('max_input_vars');
					IssueLog::Warning(
						"ajax.render.php received an invalid array for columns : check max_input_vars value in php.ini !",
						null,
						array(
							'controller' => '\Combodo\iTop\Controller\AjaxRenderController::Search',
							'max_input_vars' => $iMaxInputVarsValue,
							'class.attcode with invalid format' => "$sClass.$sAttCode",
						)
					);
					$aColumns[$sClass][$sAttCode]['checked'] = 'false';
				}
			}
		}

		// Filter the list to removed linked set since we are not able to display them here
		$sIdName = isset($extraParams["id_for_select"])?$extraParams["id_for_select"]:"";
		$aOrderBy = array();
		$iSortIndex = 0;

		$aColumnsLoad = array();
		foreach ($aClassAliases as $sAlias => $sClassName) {
			$aColumnsLoad[$sAlias] = array();
			if (!isset($aColumns[$sAlias])) {
				continue;
			}
			// It's better to use default class order than asc first column when none specified by the request 
			if($bForceSort && count(MetaModel::GetOrderByDefault($sClassName)) > 0){
				$iSortCol = -1;
				
				$aDefaultOrder = MetaModel::GetOrderByDefault($sClassName);
				foreach ($aDefaultOrder as $sAttCode => $bOrder) {
					$aOrderBy[$sAlias.'.'.$sAttCode] = $bOrder;
				}
			}
			foreach ($aColumns[$sAlias] as $sAttCode => $aData) {
				if ($aData['checked'] == 'true') {
					$aColumns[$sAlias][$sAttCode]['checked'] = true;
					if ($sAttCode == '_key_') {
						if ($sIdName == "") {
							$sIdName = $sAlias."/_key_";
						}
						if ($iSortCol == $iSortIndex) {
							if (!MetaModel::HasChildrenClasses($oFilter->GetClass())) {
								$aNameSpec = MetaModel::GetNameSpec($oFilter->GetClass());
								if ($aNameSpec[0] == '%1$s') {
									// The name is made of a single column, let's sort according to the sort algorithm for this column
									$aOrderBy[$sAlias.'.'.$aNameSpec[1][0]] = ($sSortOrder == 'asc');
								} else {
									$aOrderBy[$sAlias.'.'.'friendlyname'] = ($sSortOrder == 'asc');
								}
							} else {
								$aOrderBy[$sAlias.'.'.'friendlyname'] = ($sSortOrder == 'asc');
							}
						}
					} else {
						$oAttDef = MetaModel::GetAttributeDef($sClassName, $sAttCode);
						if ($oAttDef instanceof AttributeLinkedSet) {
							// Removed from the display list
							unset($aColumns[$sAlias][$sAttCode]);
						} else {
							$aColumnsLoad[$sAlias][] = $sAttCode;
						}
						if ($iSortCol == $iSortIndex) {
							if ($oAttDef->IsExternalKey()) {
								$sSortCol = $sAttCode.'_friendlyname';
							} else {
								$sSortCol = $sAttCode;
							}
							$aOrderBy[$sAlias.'.'.$sSortCol] = ($sSortOrder == 'asc');
						}
					}
					$iSortIndex++;
				} else {
					$aColumns[$sAlias][$sAttCode]['checked'] = false;
				}
			}
		}
		$aQueryParams = isset($aExtraParams['query_params']) ? $aExtraParams['query_params'] : [];

		// Load only the requested columns
		$oSet = new DBObjectSet($oFilter, $aOrderBy, $aQueryParams, null, $iEnd - $iStart, $iStart);
		$oSet->OptimizeColumnLoad($aColumnsLoad);

		if (isset($aExtraParams['show_obsolete_data'])) {
			$bShowObsoleteData = $aExtraParams['show_obsolete_data'];
		} else {
			$bShowObsoleteData = utils::ShowObsoleteData();
		}
		$oSet->SetShowObsoleteData($bShowObsoleteData);
		$oKPI = new ExecutionKPI();
		$aResult["draw"] = $iDrawNumber;
		$aResult["recordsTotal"] = $oSet->Count();
		$aResult["recordsFiltered"] = $oSet->Count();
		$aResult["data"] = [];
		while ($aObject = $oSet->FetchAssoc()) {
			$aObj = [];
			foreach ($aClassAliases as $sAlias => $sClass) {
				if (isset($aObject[$sAlias])) {
					$aObj[$sAlias."/_key_"] = $aObject[$sAlias]->GetKey();
					$aObj[$sAlias."/hyperlink"] = $aObject[$sAlias]->GetHyperlink();
					foreach ($aObject[$sAlias]->GetLoadedAttributes() as $sAttCode) {
						$aObj[$sAlias."/".$sAttCode] = $aObject[$sAlias]->GetAsHTML($sAttCode);
					}
					$sObjHighlightClass = $aObject[$sAlias]->GetHilightClass();
					if (!empty($sObjHighlightClass)){
						$aObj['@class'] = 'ibo-is-'.$sObjHighlightClass;
					}
				}
			}
			if ($sIdName != "") {
				$aObj["id"] = $aObj[$sIdName];
			}
			if (isset($aObj)) {
				array_push($aResult["data"], $aObj);
			}
		}
		$oKPI->ComputeAndReport('Data fetch and format');

		return $aResult;
	}

	/**
	 * @return bool
	 * @throws \Exception
	 */
	public static function DatatableSaveSettings(): bool
	{
		$iPageSize = utils::ReadParam('page_size', 10);
		$sTableId = utils::ReadParam('table_id', null, false, 'raw_data');
		$bSaveAsDefaults = (utils::ReadParam('defaults', 'true') == 'true');
		$aClassAliases = utils::ReadParam('class_aliases', array(), false, 'raw_data');
		$aColumns = utils::ReadParam('columns', array(), false, 'raw_data');

		foreach ($aColumns as $sAlias => $aList) {
			foreach ($aList as $sAttCode => $aData) {
				$aColumns[$sAlias][$sAttCode]['checked'] = ($aData['checked'] == 'true');
				$aColumns[$sAlias][$sAttCode]['disabled'] = ($aData['disabled'] == 'true');
				$aColumns[$sAlias][$sAttCode]['sort'] = ($aData['sort']);
			}
		}

		$oSettings = new DataTableSettings($aClassAliases, $sTableId);
		$oSettings->iDefaultPageSize = $iPageSize;
		$oSettings->aColumns = $aColumns;

		if ($bSaveAsDefaults) {
			if ($sTableId != null) {
				$oCurrSettings = DataTableSettings::GetTableSettings($aClassAliases, $sTableId, true /* bOnlyTable */);
				if ($oCurrSettings) {
					$oCurrSettings->ResetToDefault(false); // Reset this table to the defaults
				}
			}
			$bRet = $oSettings->SaveAsDefault();
		} else {
			$bRet = $oSettings->Save();
		}

		return $bRet;
	}

	/**
	 * @return bool
	 */
	public static function DatatableResetSettings(): bool
	{
		$sTableId = utils::ReadParam('table_id', null, false, 'raw_data');
		$aClassAliases = utils::ReadParam('class_aliases', array(), false, 'raw_data');
		$bResetAll = (utils::ReadParam('defaults', 'true') == 'true');

		$oSettings = new DataTableSettings($aClassAliases, $sTableId);
		$bRet = $oSettings->ResetToDefault($bResetAll);

		return $bRet;
	}

	/**
	 * @param string $sStyle
	 * @param string $sFilter
	 *
	 * @return array
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 */
	public static function RefreshDashletList(string $sStyle, string $sFilter): array
	{
		$aExtraParams = utils::ReadParam('extra_params', '', false, 'raw_data');
		$oFilter = DBObjectSearch::FromOQL($sFilter);
		$oFilter->SetShowObsoleteData(utils::ShowObsoleteData());

		if (isset($aExtraParams['group_by'])) {

			$sAlias = $oFilter->GetClassAlias();
			if (isset($aExtraParams['group_by_label'])) {
				$oGroupByExp = Expression::FromOQL($aExtraParams['group_by']);
			} else {
				// Backward compatibility: group_by is simply a field id
				$oGroupByExp = new FieldExpression($aExtraParams['group_by'], $sAlias);
			}

			// Security filtering
			$aFields = $oGroupByExp->ListRequiredFields();
			foreach ($aFields as $sFieldAlias) {
				$aMatches = array();
				if (preg_match('/^([^.]+)\\.([^.]+)$/', $sFieldAlias, $aMatches)) {
					$sFieldClass = $oFilter->GetClassName($aMatches[1]);
					$oAttDef = MetaModel::GetAttributeDef($sFieldClass, $aMatches[2]);
					if ($oAttDef instanceof AttributeOneWayPassword) {
						throw new Exception('Grouping on password fields is not supported.');
					}
				}
			}

			$aGroupBy = [];
			$aGroupBy['grouped_by_1'] = $oGroupByExp;

			$aFunctions = [];
			$sFctVar = '_itop_count_';
			if (isset($aExtraParams['aggregation_function']) && !empty($aExtraParams['aggregation_attribute'])) {
				$sAggregationFunction = $aExtraParams['aggregation_function'];
				$sAggregationAttr = $aExtraParams['aggregation_attribute'];
				$oAttrExpr = Expression::FromOQL('`'.$sAlias.'`.`'.$sAggregationAttr.'`');
				$oFctExpr = new FunctionExpression(strtoupper($sAggregationFunction), [$oAttrExpr]);
				$sFctVar = '_itop_'.$sAggregationFunction.'_';
				$aFunctions = [$sFctVar => $oFctExpr];
			}

			$iLimit = 0;
			if (isset($aExtraParams['limit'])) {
				$iLimit = intval($aExtraParams['limit']);
			}
			$aOrderBy = [];
			if (isset($aExtraParams['order_direction']) && isset($aExtraParams['order_by'])) {
				switch ($aExtraParams['order_by']) {
					case 'attribute':
						$aOrderBy = array('grouped_by_1' => ($aExtraParams['order_direction'] === 'asc'));
						break;
					case 'function':
						$aOrderBy = array($sFctVar => ($aExtraParams['order_direction'] === 'asc'));
						break;
				}
			}
			$aQueryParams = [];
			if (isset($aExtraParams['query_params'])) {
				$aQueryParams = $aExtraParams['query_params'];
			}
			$sSql = $oFilter->MakeGroupByQuery($aQueryParams, $aGroupBy, true, $aFunctions, $aOrderBy, $iLimit);

			$aRes = CMDBSource::QueryToArray($sSql);

			$aGroupBy = array();
			$aLabels = array();
			$aValues = array();
			$iTotalCount = 0;
			foreach ($aRes as $iRow => $aRow) {
				$sValue = $aRow['grouped_by_1'];
				$aValues[$iRow] = $sValue;
				$sHtmlValue = $oGroupByExp->MakeValueLabel($oFilter, $sValue, $sValue);
				$aLabels[$iRow] = $sHtmlValue;
				$aGroupBy[$iRow] = (int)$aRow[$sFctVar];
				$iTotalCount += $aRow['_itop_count_'];
			}

			$aResult = array();
			$oAppContext = new ApplicationContext();
			$sParams = $oAppContext->GetForLink();
			foreach ($aGroupBy as $iRow => $iCount) {
				// Build the search for this subset
				$oSubsetSearch = $oFilter->DeepClone();
				$oCondition = new BinaryExpression($oGroupByExp, '=', new ScalarExpression($aValues[$iRow]));
				$oSubsetSearch->AddConditionExpression($oCondition);
				if (isset($aExtraParams['query_params'])) {
					$aQueryParams = $aExtraParams['query_params'];
				} else {
					$aQueryParams = array();
				}
				$sFilter = rawurlencode($oSubsetSearch->serialize(false, $aQueryParams));

				$aResult[] = array(
					'group' => $aLabels[$iRow],
					'value' => "<a href=\"".utils::GetAbsoluteUrlAppRoot()."pages/UI.php?operation=search&dosearch=1&$sParams&filter=$sFilter\">$iCount</a>",
				); // TO DO: add the context information
			}

		} else {
			// Simply count the number of elements in the set
			$aOrderBy = [];
			if (isset($aExtraParams['order_direction']) && isset($aExtraParams['order_by'])) {
				$aOrderBy = ['order_by' => $aExtraParams['order_by'], 'order_direction' => $aExtraParams['order_direction']];
			}
			$aQueryParams = [];
			if (isset($aExtraParams['query_params'])) {
				$aQueryParams = $aExtraParams['query_params'];
			}
			$oSet = new CMDBObjectSet($oFilter, $aOrderBy, $aQueryParams);
			$iCount = $oSet->Count();
			$sFormat = 'UI:CountOfObjects';
			if (isset($aExtraParams['format'])) {
				$sFormat = $aExtraParams['format'];
			}
			$aResult = ['result' => Dict::Format($sFormat, $iCount)];
		}

		return $aResult;
	}

	/**
	 * @param string $sFilter
	 *
	 * @return array
	 * @throws \CoreException
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 */
	public static function RefreshDashletCount(string $sFilter): array
	{
		$aExtraParams = utils::ReadParam('extra_params', '', false, 'raw_data');
		$oFilter = DBObjectSearch::FromOQL($sFilter);
		$oFilter->SetShowObsoleteData(utils::ShowObsoleteData());
		$aQueryParams = array();
		if (isset($aExtraParams['query_params'])) {
			$aQueryParams = $aExtraParams['query_params'];
		}
		$oSet = new CMDBObjectSet($oFilter, [], $aQueryParams);
		$iCount = $oSet->Count();
		$aResult = ['count' => $iCount];

		return $aResult;
	}

	/**
	 * @param string $sFilter
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \OQLException
	 */
	public static function DoAddObjects(AjaxPage $oPage, string $sClass, string $sFilter)
	{
		$sAttCode = utils::ReadParam('sAttCode', '');
		$iInputId = utils::ReadParam('iInputId', '');
		$sSuffix = utils::ReadParam('sSuffix', '');
		$sRemoteClass = utils::ReadParam('sRemoteClass', $sClass, false, 'class');
		$bDuplicates = (utils::ReadParam('bDuplicates', 'false') == 'false') ? false : true;
		$sJson = utils::ReadParam('json', '', false, 'raw_data');
		$iMaxAddedId = utils::ReadParam('max_added_id');
		$oWizardHelper = WizardHelper::FromJSON($sJson);
		/** @var \DBObject $oObj */
		$oObj = $oWizardHelper->GetTargetObject();
		$oKPI = new ExecutionKPI();
		$oWidget = new UILinksWidget($sClass, $sAttCode, $iInputId, $sSuffix, $bDuplicates);
		if ($sFilter != '') {
			$oFullSetFilter = DBObjectSearch::unserialize($sFilter);
		} else {
			$oFullSetFilter = new DBObjectSearch($sRemoteClass);
		}
		$oWidget->DoAddObjects($oPage, $iMaxAddedId, $oFullSetFilter, $oObj);
		$oKPI->ComputeAndReport('Data write');
	}

	/**
	 * @param string $sFilter
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \OQLException
	 */
	public static function DoAddIndirectLinks(JsonPage $oPage, string $sClass, string $sFilter)
	{
		$sAttCode = utils::ReadParam('sAttCode', '');
		$iInputId = utils::ReadParam('iInputId', '');
		$sSuffix = utils::ReadParam('sSuffix', '');
		$sRemoteClass = utils::ReadParam('sRemoteClass', $sClass, false, 'class');
		$bDuplicates = (utils::ReadParam('bDuplicates', 'false') == 'false') ? false : true;
		$sJson = utils::ReadParam('json', '', false, 'raw_data');
		$iMaxAddedId = utils::ReadParam('max_added_id');
		$oWizardHelper = WizardHelper::FromJSON($sJson);
		/** @var \DBObject $oObj */
		$oObj = $oWizardHelper->GetTargetObject();
		$oKPI = new ExecutionKPI();
		$oWidget = new UILinksWidget($sClass, $sAttCode, $iInputId, $sSuffix, $bDuplicates);
		if ($sFilter != '') {
			$oFullSetFilter = DBObjectSearch::unserialize($sFilter);
		} else {
			$oFullSetFilter = new DBObjectSearch($sRemoteClass);
		}
		$oWidget->DoAddIndirectLinks($oPage, $iMaxAddedId, $oFullSetFilter, $oObj);
		$oKPI->ComputeAndReport('Data write');
	}
}
