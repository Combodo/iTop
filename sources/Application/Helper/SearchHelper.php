<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\Helper;

use Combodo\iTop\Application\WebPage\iTopWebPage;
use Combodo\iTop\Application\WebPage\WebPage;
use DBSearch;
use DisplayBlock;
use MetaModel;
use utils;

class SearchHelper
{
	/**
	 * Displays the result of a search request
	 * @param $oP WebPage Web page for the output
	 * @param $oFilter DBSearch The search of objects to display
	 * @param $bSearchForm boolean Whether or not to display the search form at the top the page
	 * @param $sBaseClass string The base class for the search (can be different from the actual class of the results)
	 * @param $sFormat string The format to use for the output: csv or html
	 * @param $bDoSearch bool True to display the search results below the search form
	 * @param $bSearchFormOpen bool True to display the search form fully expanded (only if $bSearchForm of course)
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 */
	public static function DisplaySearchSet($oP, $oFilter, $bSearchForm = true, $sBaseClass = '', $sFormat = '', $bDoSearch = true, $bSearchFormOpen = true, $aParams = []): void
	{
		//search block
		$oBlockForm = null;
		if ($bSearchForm) {
			$aParams['open'] = $bSearchFormOpen;
			if (false === isset($aParams['table_id'])) {
				$aParams['table_id'] = 'result_1';
			}
			if (!empty($sBaseClass)) {
				$aParams['baseClass'] = $sBaseClass;
			}
			$oBlockForm = new DisplayBlock($oFilter, 'search', false /* Asynchronous */, $aParams);

			if (!$bDoSearch) {
				$oBlockForm->Display($oP, 0);
			}
		}
		if ($bDoSearch) {
			if (strtolower($sFormat) == 'csv') {
				$oBlock = new DisplayBlock($oFilter, 'csv', false);
				// Adjust the size of the Textarea containing the CSV to fit almost all the remaining space
				$oP->add_ready_script(" $('#1>textarea').height($('#1').parent().height() - $('#0').outerHeight() - 30).width( $('#1').parent().width() - 20);"); // adjust the size of the block
			} else {
				$oBlock = new DisplayBlock($oFilter, 'list', false);

				// Breadcrumb
				//$iCount = $oBlock->GetDisplayedCount();
				$sPageId = "ui-search-".$oFilter->GetClass();
				$sLabel = MetaModel::GetName($oFilter->GetClass());
				$oP->SetBreadCrumbEntry($sPageId, $sLabel, '', '', 'fas fa-search', iTopWebPage::ENUM_BREADCRUMB_ENTRY_ICON_TYPE_CSS_CLASSES);
			}
			if ($bSearchForm) {
				//add search block
				$sTableId = utils::ReadParam('_table_id_', null, false, 'raw_data');
				if ($sTableId == '') {
					$sTableId = 'result_1';
				}
				$aExtraParams['table_id'] = $sTableId;
				$aExtraParams['submit_on_load'] = false;
				$oUIBlockForm = $oBlockForm->GetDisplay($oP, 'search_1', $aExtraParams);

				// If the class is not high cardinality, we can display the results directly in the same page
				if (!utils::IsHighCardinality($oFilter->GetClass())) {
					//add result block
					$oUIBlock = $oBlock->GetDisplay($oP, $sTableId);
					$oUIBlock->AddCSSClasses(['display_block', 'sf_results_area']);
					$oUIBlock->AddDataAttribute('target', 'search_results');
					$oUIBlockForm->AddSubBlock($oUIBlock);
				}

				$oP->AddUiBlock($oUIBlockForm);
			} else {
				$oBlock->Display($oP, 1);
			}
		}
	}
}
