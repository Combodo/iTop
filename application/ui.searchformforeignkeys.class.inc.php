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
	public function ShowModalSearchForeignKeys($oPage, $sTitle)
	{

		$oFilter = new DBObjectSearch($this->m_sRemoteClass);

		$oBlock = new DisplayBlock($oFilter, 'search', false);
		$oPage->AddUiBlock($oBlock->GetDisplay($oPage, "SearchFormToAdd_{$this->m_iInputId}",
			array(
				'menu' => false,
				'result_list_outer_selector' => "SearchResultsToAdd_{$this->m_iInputId}",
				'table_id' => "add_{$this->m_iInputId}",
				'table_inner_id' => "ResultsToAdd_{$this->m_iInputId}",
				'selection_mode' => true,
				'cssCount' => "#count_{$this->m_iInputId}",
				'query_params' => $oFilter->GetInternalParams(),
			)));
		$sEmptyList = Dict::S('UI:Message:EmptyList:UseSearchForm');
		$sCancel = Dict::S('UI:Button:Cancel');
		$sAdd = Dict::S('UI:Button:Add');

		$oPage->add(<<<HTML
<form id="ObjectsAddForm_{$this->m_iInputId}">
    <div id="SearchResultsToAdd_{$this->m_iInputId}" style="vertical-align:top;height:100%;overflow:auto;padding:0;border:0;">
        <div style="background: #fff; border:0; text-align:center; vertical-align:middle;"><p>{$sEmptyList}</p></div>
    </div>
    <input type="hidden" id="count_{$this->m_iInputId}" value="0"/>
</form>
HTML
		);

		$oPage->add_ready_script(
			<<<JS
 $('#dlg_{$this->m_iInputId}').dialog({ 
 			width: $(window).width()*0.8, 
 			height: $(window).height()*0.8, 
 			autoOpen: false, 
 			modal: true, 
 			resizeStop: oForeignKeysWidget{$this->m_iInputId}.UpdateSizes,
            buttons: [
				{
					text: Dict.S('UI:Button:Cancel'),
					class: "cancel ibo-is-alternative ibo-is-neutral",
					click: function() {
						$('#dlg_{$this->m_iInputId}').dialog('close');
					}
				},
				{
					text:  Dict.S('UI:Button:Add'),
					id: 'btn_ok_{$this->m_iInputId}',
					class: "ok ibo-is-regular ibo-is-primary",
					click: function() {
						oForeignKeysWidget{$this->m_iInputId}.DoAddObjects(this.id);							
					}
				},
			],

 });
$('#dlg_{$this->m_iInputId}').dialog('option', {title:'$sTitle'});
$('#SearchFormToAdd_{$this->m_iInputId} form').on('submit.uilinksWizard', oForeignKeysWidget{$this->m_iInputId}.SearchObjectsToAdd);
$('#SearchFormToAdd_{$this->m_iInputId}').on('resize', oForeignKeysWidget{$this->m_iInputId}.UpdateSizes);
JS
);
	}

	public function GetFullListForeignKeysFromSelection($oPage, $oFullSetFilter)
	{
		try
		{
			$aLinkedObjects = utils::ReadMultipleSelectionWithFriendlyname($oFullSetFilter);
			$oPage->add(json_encode($aLinkedObjects));
		}
		catch (CoreException $e)
		{
			http_response_code(500);
			$oPage->add(json_encode(array('error' => $e->GetMessage())));
			IssueLog::Error($e->getMessage()."\nDebug trace:\n".$e->getTraceAsString());
		}
	}

	/**
	 * Search for objects to be linked to the current object (i.e "remote" objects)
	 *
	 * @param WebPage $oP The page used for the output (usually an AjaxWebPage)
	 * @param string $sRemoteClass Name of the "remote" class to perform the search on, must be a derived class of m_sRemoteClass
	 *
	 * @throws \Exception
	 */
	public function ListResultsSearchForeignKeys(WebPage $oP, $sRemoteClass = '')
	{
		if ($sRemoteClass != '')
		{
			// assert(MetaModel::IsParentClass($this->m_sRemoteClass, $sRemoteClass));
			$oFilter = new DBObjectSearch($sRemoteClass);
		}
		else
		{
			// No remote class specified use the one defined in the linkedset
			$oFilter = new DBObjectSearch($this->m_sRemoteClass);
		}

		$oBlock = new DisplayBlock($oFilter, 'list', false);
		$oBlock->Display($oP, "ResultsToAdd_{$this->m_iInputId}",
			array('menu' => false, 'cssCount' => "#count_{$this->m_iInputId}", 'selection_mode' => true, 'table_id' => "add_{$this->m_iInputId}"));
	}

}
