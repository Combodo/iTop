<?php

/**
 *
 * Copyright (C) 2010-2024 Combodo SAS
 *
 * This file is part of iTop.
 *
 *  iTop is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  iTop is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with iTop. If not, see <http://www.gnu.org/licenses/>
 *
 */

use Combodo\iTop\Application\WebPage\WebPage;

require_once(APPROOT.'/application/displayblock.class.inc.php');

class UISearchFormForeignKeys
{
	private $m_sRemoteClass;
	private $m_iInputId;

	public function __construct($sTargetClass, $iInputId = null)
	{
		$this->m_sRemoteClass = $sTargetClass;
		$this->m_iInputId = $iInputId;
	}

	/**
	 * @param WebPage $oPage
	 *
	 * @param $sTitle
	 *
	 * @throws \Exception
	 */
	public function ShowModalSearchForeignKeys($oPage)
	{

		$oFilter = new DBObjectSearch($this->m_sRemoteClass);

		$oBlock = new DisplayBlock($oFilter, 'search', false);
		$oPage->AddUiBlock($oBlock->GetDisplay(
			$oPage,
			"SearchFormToAdd_{$this->m_iInputId}",
			[
				'menu' => false,
				'result_list_outer_selector' => "SearchResultsToAdd_{$this->m_iInputId}",
				'table_id' => "add_{$this->m_iInputId}",
				'table_inner_id' => "ResultsToAdd_{$this->m_iInputId}",
				'selection_mode' => true,
				'cssCount' => "#count_{$this->m_iInputId}",
				'query_params' => $oFilter->GetInternalParams(),
			]
		));
		$sEmptyList = Dict::S('UI:Message:EmptyList:UseSearchForm');

		$oPage->add(
			<<<HTML
<form id="ObjectsAddForm_{$this->m_iInputId}">
    <div id="SearchResultsToAdd_{$this->m_iInputId}" style="vertical-align:top;height:100%;overflow:auto;padding:0;border:0;">
        <div style="background: #fff; border:0; text-align:center; vertical-align:middle;"><p>{$sEmptyList}</p></div>
    </div>
    <input type="hidden" id="count_{$this->m_iInputId}" value="0"/>
</form>
HTML
		);
	}

	public function GetFullListForeignKeysFromSelection($oPage, $oFullSetFilter)
	{
		try {
			$aLinkedObjects = utils::ReadMultipleSelectionWithFriendlyname($oFullSetFilter);
			$oPage->add(json_encode($aLinkedObjects));
		} catch (CoreException $e) {
			http_response_code(500);
			$oPage->add(json_encode(['error' => $e->GetMessage()]));
			IssueLog::Error($e->getMessage()."\nDebug trace:\n".$e->getTraceAsString());
		}
	}
}
