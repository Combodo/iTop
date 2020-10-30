<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Controller;

use ajax_page;
use BulkExport;
use BulkExportException;
use DBObjectSearch;
use DBObjectSet;
use Dict;
use Exception;
use utils;

class AjaxRenderController
{
	/**
	 * @param \ajax_page $oPage
	 *
	 * @param bool $bTokenOnly
	 *
	 * @throws \Exception
	 */
	public static function ExportBuild(ajax_page $oPage, bool $bTokenOnly)
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
}
