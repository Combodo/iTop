<?php
/**
 *
 * Copyright (C) 2010-2018 Combodo SARL
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


require_once(APPROOT.'/application/webpage.class.inc.php');
require_once(APPROOT.'/application/displayblock.class.inc.php');

class UISearchFormForeignKeys
{
	public function __construct($sTargetClass, $iInputId, $sAttCode, $sSuffix)
	{
		$this->m_sRemoteClass = $sTargetClass;
		$this->m_iInputId = $iInputId;
		$this->sAttCode = $sAttCode;
		$this->m_sNameSuffix = $sSuffix;
	}

	/**
	 * @param WebPage $oPage
	 *
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 * @throws \Exception
	 */
	public function ShowModalSearchForeignKeys($oPage)
	{
		$bOpen = MetaModel::GetConfig()->Get('legacy_search_drawer_open');
		$sHtml = "<div class=\"wizContainer\" style=\"vertical-align:top;\">\n";

		$oFilter = new DBObjectSearch($this->m_sRemoteClass);

		$oBlock = new DisplayBlock($oFilter, 'search', false);
		$sHtml .= $oBlock->GetDisplay($oPage, "SearchFormToAdd_{$this->m_sAttCode}{$this->m_sNameSuffix}",
			array(
				'open' => $bOpen,
				'menu' => false,
				'table_id' => "SearchResultsToAdd_{$this->m_sAttCode}{$this->m_sNameSuffix}",
				'table_id2' => 'add_'.$this->m_sAttCode,
				'table_inner_id' => "ResultsToAdd_{$this->m_sAttCode}{$this->m_sNameSuffix}",
				'selection_mode' => true,
				'cssCount' => '#count_'.$this->m_sAttCode.$this->m_sNameSuffix,
				'query_params' => $oFilter->GetInternalParams(),
			));
		$sHtml .= "<form id=\"ObjectsAddForm_{$this->m_sAttCode}{$this->m_sNameSuffix}\">\n";
		$sHtml .= "<div id=\"SearchResultsToAdd_{$this->m_sAttCode}{$this->m_sNameSuffix}\" style=\"vertical-align:top;background: #fff;height:100%;overflow:auto;padding:0;border:0;\">\n";
		$sHtml .= "<div style=\"background: #fff; border:0; text-align:center; vertical-align:middle;\"><p>".Dict::S('UI:Message:EmptyList:UseSearchForm')."</p></div>\n";
		$sHtml .= "</div>\n";
		$sHtml .= "<input type=\"hidden\" id=\"count_{$this->m_sAttCode}{$this->m_sNameSuffix}\" value=\"0\"/>";
		$sHtml .= "<input type=\"button\" value=\"".Dict::S('UI:Button:Cancel')."\" onClick=\"$('#dlg_{$this->m_sAttCode}{$this->m_sNameSuffix}').dialog('close');\">&nbsp;&nbsp;<input id=\"btn_ok_{$this->m_sAttCode}{$this->m_sNameSuffix}\" disabled=\"disabled\" type=\"button\" onclick=\"return oWidget{$this->m_iInputId}.DoAddObjects(this.id);\" value=\"".Dict::S('UI:Button:Add')."\">";
		$sHtml .= "</div>\n";
		$sHtml .= "</form>\n";
		$oPage->add($sHtml);
		$oPage->add_ready_script("$('#dlg_{$this->m_sAttCode}{$this->m_sNameSuffix}').dialog({ width: $(window).width()*0.8, height: $(window).height()*0.8, autoOpen: false, modal: true, resizeStop: oWidget{$this->m_iInputId}.UpdateSizes });");
		$oPage->add_ready_script("$('#dlg_{$this->m_sAttCode}{$this->m_sNameSuffix}').dialog('option', {title:'".addslashes(Dict::Format('UI:AddObjectsOf_Class_LinkedWith_Class', MetaModel::GetName($this->m_sLinkedClass),
				MetaModel::GetName($this->m_sClass)))."'});");
		$oPage->add_ready_script("$('#SearchFormToAdd_{$this->m_sAttCode}{$this->m_sNameSuffix} form').bind('submit.uilinksWizard', oWidget{$this->m_iInputId}.SearchObjectsToAdd);");
		$oPage->add_ready_script("$('#SearchFormToAdd_{$this->m_sAttCode}{$this->m_sNameSuffix}').resize(oWidget{$this->m_iInputId}.UpdateSizes);");
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
		$oBlock->Display($oP, "ResultsToAdd_{$this->m_sAttCode}",
			array('menu' => false, 'cssCount' => '#count_'.$this->m_sAttCode.$this->m_sNameSuffix, 'selection_mode' => true, 'table_id' => 'add_'.$this->m_sAttCode));
	}

}