<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Controller;

use AjaxPage;
use ApplicationContext;
use ApplicationMenu;
use AttributeLinkedSet;
use BinaryExpression;
use BulkExport;
use BulkExportException;
use CMDBObjectSet;
use CMDBSource;
use Combodo\iTop\Application\UI\Base\Component\DataTable\DataTableSettings;
use Combodo\iTop\Application\UI\Base\Component\DataTable\DataTableUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\ActivityEntryFactory;
use Combodo\iTop\Renderer\BlockRenderer;
use DBObjectSearch;
use DBObjectSet;
use DBSearch;
use Dict;
use Exception;
use ExecutionKPI;
use Expression;
use InlineImage;
use MetaModel;
use ScalarExpression;
use utils;

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
		$iLength = utils::ReadParam('end', 10);
		$aColumns = utils::ReadParam('columns', array(), false, 'raw_data');
		$sSelectMode = utils::ReadParam('select_mode', '');
		$aClassAliases = utils::ReadParam('class_aliases', array());
		$aResult = DataTableUIBlockFactory::GetOptionsForRendering($aColumns, $sSelectMode, $sFilter, $iLength, $aClassAliases, $aExtraParams);

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
		if (count($aSort) > 0) {
			$iSortCol = $aSort[0]["column"];
			$sSortOrder = $aSort[0]["dir"];
		} else {
			$iSortCol = 0;
			$sSortOrder = "asc";
		}
		$sSelectMode = utils::ReadParam('select_mode', '');
		if (!empty($sSelectMode) && ($sSelectMode != 'none')) {
			// The first column is used for the selection (radio / checkbox) and is not sortable
			$iSortCol--;
		}
		$bDisplayKey = utils::ReadParam('display_key', 'true') == 'true';
		$aColumns = utils::ReadParam('columns', array(), false, 'raw_data');
		$aClassAliases = utils::ReadParam('class_aliases', array());
		$iListId = utils::ReadParam('list_id', 0);

		// Filter the list to removed linked set since we are not able to display them here
		$sIdName = "";
		$aOrderBy = array();
		$iSortIndex = 0;

		$aColumnsLoad = array();
		foreach ($aClassAliases as $sAlias => $sClassName) {
			$aColumnsLoad[$sAlias] = array();
			if (!isset($aColumns[$sAlias])) {
				continue;
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
			foreach ($aClassAliases as $sAlias => $sClass) {
				if (isset($aColumns[$sAlias])) {
					foreach ($aColumns[$sAlias] as $sAttCode => $oAttDef) {
						if ($sAttCode == "_key_") {
							$aObj[$sAlias."/".$sAttCode] = $aObject[$sAlias]->GetKey();
						} else {
							$aObj[$sAlias."/".$sAttCode] = $aObject[$sAlias]->GetAsHTML($sAttCode);
						}
					}
				}
			}
			if ($sIdName != "") {
				$aObj["id"] = $aObj[$sIdName];
			}
			array_push($aResult["data"], $aObj);
		}

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
				$oCurrSettings = unserialize(DataTableSettings::GetTableSettings($aClassAliases, $sTableId, true /* bOnlyTable */));
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
	 * Add new entries to some of the object's (identified by posted parameters) case logs
	 *
	 * @return array The status of the update, a renewed transaction ID and the entries as HTML so they can be append to the front.
	 * [
	 *  'success' => true,
	 *  'entries' => [
	 *      '<ATT_CODE_1>' => [
	 *          html_rendering => '<HTML_RENDERING_TO_BE_APPEND_IN_FRONT_END>',
	 *      ],
	 *      '<ATT_CODE_2>' => [
	 *          html_rendering => '<HTML_RENDERING_TO_BE_APPEND_IN_FRONT_END>',
	 *      ],
	 *      ...
	 *  ],
	 *  'renewed_transaction_id' => '<RENEWED_TRANSACTION_ID>',
	 * ]
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \OQLException
	 * @throws \ReflectionException
	 * @throws \Twig\Error\LoaderError
	 * @throws \Twig\Error\RuntimeError
	 * @throws \Twig\Error\SyntaxError
	 */
	public static function AddCaseLogsEntries(): array
	{
		$sObjectClass = utils::ReadPostedParam('object_class', null, utils::ENUM_SANITIZATION_FILTER_CLASS);
		$sObjectId = utils::ReadPostedParam('object_id', 0);
		$sTransactionId = utils::ReadPostedParam('transaction_id', null, utils::ENUM_SANITIZATION_FILTER_TRANSACTION_ID);
		$aEntries = utils::ReadPostedParam('entries', [], utils::ENUM_SANITIZATION_FILTER_RAW_DATA);

		// Consistency checks
		// - Mandatory parameters
		if (empty($sObjectClass) || empty($sObjectId) || empty($sTransactionId) || empty($aEntries)) {
			throw new Exception('Missing mandatory parameters object_class / object_id / transaction_id / entries');
		}
		// - Transaction ID
		// Note: We keep the transaction ID for several reasons:
		// - We might send several messages, so renewing it would not make such a difference except making the follwoing line harder
		// - We need the transaction ID to passed in the JS snippet that allows images to be uploaded (see InlineImage::EnableCKEditorImageUpload()), renewing it would only make things more complicated
		// => For all those reasons, we let the GC clean the transactions IDs, just like when a transaction ID is not deleted when cancelling a regular object edition.
		if (!utils::IsTransactionValid($sTransactionId, false)) {
			throw new Exception(Dict::S('iTopUpdate:Error:InvalidToken'));
		}

		$aResults = [
			'success' => true,
			'entries' => [],
		];

		// Note: Will trigger an exception if object does not exists or not accessible to the user
		$oObject = MetaModel::GetObject($sObjectClass, $sObjectId);
		foreach ($aEntries as $sAttCode => $aData) {
			// Add entry to object
			$oObject->Set($sAttCode, $aData['value']);

			// Make entry rendering to send back to the front
			$aEntryAsArray = $oObject->Get($sAttCode)->GetAsArray()[0];
			$oEntryBlock = ActivityEntryFactory::MakeFromCaseLogEntryArray($sAttCode, $aEntryAsArray);
			$oEntryBlock->SetCaseLogRank((int)$aData['rank']);
			$sEntryAsHtml = BlockRenderer::RenderBlockTemplates($oEntryBlock);

			$aResults['entries'][$sAttCode] = [
				'html_rendering' => $sEntryAsHtml,
			];
		}
		$oObject->DBWrite();

		// Finalize inline images
		InlineImage::FinalizeInlineImages($oObject);

		return $aResults;
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
	public static function RefreshDashletList(string $sStyle, string $sFilter): array
	{
		$aExtraParams = utils::ReadParam('extra_params', '', false, 'raw_data');
		$oFilter = DBObjectSearch::FromOQL($sFilter);

		if (isset($aExtraParams['group_by'])) {

			$sAlias = $oFilter->GetClassAlias();
			if (isset($aExtraParams['group_by_label'])) {
				$oGroupByExp = Expression::FromOQL($aExtraParams['group_by']);
				$sGroupByLabel = $aExtraParams['group_by_label'];
			} else {
				// Backward compatibility: group_by is simply a field id
				$oGroupByExp = new FieldExpression($aExtraParams['group_by'], $sAlias);
				$sGroupByLabel = MetaModel::GetLabel($oFilter->GetClass(), $aExtraParams['group_by']);
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

			$aGroupBy = array();
			$aGroupBy['grouped_by_1'] = $oGroupByExp;
			$aQueryParams = array();
			if (isset($aExtraParams['query_params'])) {
				$aQueryParams = $aExtraParams['query_params'];
			}
			$aFunctions = array();
			$sAggregationFunction = 'count';
			$sFctVar = '_itop_count_';
			$sAggregationAttr = '';
			if (isset($aExtraParams['aggregation_function']) && !empty($aExtraParams['aggregation_attribute'])) {
				$sAggregationFunction = $aExtraParams['aggregation_function'];
				$sAggregationAttr = $aExtraParams['aggregation_attribute'];
				$oAttrExpr = Expression::FromOQL('`'.$sAlias.'`.`'.$sAggregationAttr.'`');
				$oFctExpr = new FunctionExpression(strtoupper($sAggregationFunction), array($oAttrExpr));
				$sFctVar = '_itop_'.$sAggregationFunction.'_';
				$aFunctions = array($sFctVar => $oFctExpr);
			}

			if (!empty($sAggregationAttr)) {
				$sClass = $oFilter->GetClass();
				$sAggregationAttr = MetaModel::GetLabel($sClass, $sAggregationAttr);
			}
			$iLimit = 0;
			if (isset($aExtraParams['limit'])) {
				$iLimit = intval($aExtraParams['limit']);
			}
			$aOrderBy = array();
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

			$oSet = new CMDBObjectSet($oFilter, $aOrderBy, $aExtraParams);
			$iCount = $oSet->Count();
			$sFormat = 'UI:CountOfObjects';
			if (isset($aExtraParams['format'])) {
				$sFormat = $aExtraParams['format'];
			}
			$aResult = ['result' => Dict::Format($sFormat, $iCount)];
		}

		return $aResult;
	}

	public static function RefreshCount(string $sFilter): array
	{
		$aExtraParams = utils::ReadParam('extra_params', '', false, 'raw_data');
		$oFilter = DBObjectSearch::FromOQL($sFilter);

		$oSet = new CMDBObjectSet($oFilter, [], $aExtraParams);
		$iCount = $oSet->Count();
		$aResult = ['count' => $iCount];

		return $aResult;
	}


}
