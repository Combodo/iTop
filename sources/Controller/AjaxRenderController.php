<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Controller;

use AjaxPage;
use ApplicationMenu;
use AttributeLinkedSet;
use BulkExport;
use BulkExportException;
use Combodo\iTop\Application\UI\Base\Component\DataTable\DataTableFactory;
use Combodo\iTop\Application\UI\Base\Component\DataTable\DataTableSettings;
use DBObjectSearch;
use DBObjectSet;
use DBSearch;
use Dict;
use Exception;
use ExecutionKPI;
use MetaModel;
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
	 * @param $sFilter
	 *
	 * @return array
	 * @throws \Exception
	 */
	public static function SearchAndRefresh($sFilter): array
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
		$aResult = DataTableFactory::GetOptionsForRendering($aColumns, $sSelectMode, $sFilter, $iLength, $aClassAliases, $aExtraParams);

		return $aResult;
	}

	/**
	 * @param $sEncoding
	 * @param $sFilter
	 *
	 * @return mixed
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 */
	public static function Search($sEncoding, $sFilter)
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
}
