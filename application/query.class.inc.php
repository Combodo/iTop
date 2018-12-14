<?php
// Copyright (C) 2010-2015 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>


/**
 * Persistent class Event and derived
 * Application internal events
 * There is also a file log 
 *
 * @copyright   Copyright (C) 2010-2015 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

abstract class Query extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb,view_in_gui,application,grant_by_profile",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_query",
			"db_key_field" => "id",
			"db_finalclass_field" => "realclass",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));


		// Display lists
		MetaModel::Init_SetZListItems('details', array('name', 'description')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('description')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('name', 'description')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('default_search', array('name', 'description')); // Criteria of the default search form
		// MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}
}

class QueryOQL extends Query
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb,view_in_gui,application,grant_by_profile",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_query_oql",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeOQL("oql", array("allowed_values"=>null, "sql"=>"oql", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("fields", array("allowed_values"=>null, "sql"=>"fields", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		// Rolled back to AttributeText until AttributeQueryAttCodeSet can manage fields order correctly
		//MetaModel::Init_AddAttribute(new AttributeQueryAttCodeSet("fields", array("allowed_values"=>null,"max_items" => 1000, "query_field" => "oql", "sql"=>"fields", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array('oql'))));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('name', 'description', 'oql', 'fields')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('description')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('name', 'description', 'fields', 'oql')); // Criteria of the std search form
	}

	function DisplayBareProperties(WebPage $oPage, $bEditMode = false, $sPrefix = '', $aExtraParams = array())
	{
		$aFieldsMap = parent::DisplayBareProperties($oPage, $bEditMode, $sPrefix, $aExtraParams);
		
		if (!$bEditMode)
		{
			$sFields = trim($this->Get('fields'));
			$bExportV1Recommended = ($sFields == '');
			if ($bExportV1Recommended)
			{
				$oFieldAttDef = MetaModel::GetAttributeDef('QueryOQL', 'fields');
				$oPage->add('<div class="message message_error" style="padding-left: 30px;"><div style="padding: 10px;">'.Dict::Format('UI:Query:UrlV1', $oFieldAttDef->GetLabel()).'</div></div>');
				$sUrl = utils::GetAbsoluteUrlAppRoot().'webservices/export.php?format=spreadsheet&login_mode=basic&query='.$this->GetKey();
			}
			else 
			{
				$sUrl = utils::GetAbsoluteUrlAppRoot().'webservices/export-v2.php?format=spreadsheet&login_mode=basic&date_format='.urlencode((string)AttributeDateTime::GetFormat()).'&query='.$this->GetKey();
			}
			$sOql = $this->Get('oql');
			$sMessage = null;
			try
			{
				$oSearch = DBObjectSearch::FromOQL($sOql);
				$aParameters = $oSearch->GetQueryParams();
				foreach($aParameters as $sParam => $val)
				{
					$sUrl .= '&arg_'.$sParam.'=["'.$sParam.'"]';
				}

				$oPage->p(Dict::S('UI:Query:UrlForExcel').':<br/><textarea cols="80" rows="3" READONLY>'.$sUrl.'</textarea>');

				if (count($aParameters) == 0)
				{
					$oBlock = new DisplayBlock($oSearch, 'list');
					$aExtraParams = array(
						//'menu' => $sShowMenu,
						'table_id' => 'query_preview_'.$this->getKey(),
					);
					$sBlockId = 'block_query_preview_'.$this->GetKey(); // make a unique id (edition occuring in the same DOM)
					$oBlock->Display($oPage, $sBlockId, $aExtraParams);
				}
			}
			catch (OQLException $e)
			{
				$sMessage = '<div class="message message_error" style="padding-left: 30px;"><div style="padding: 10px;">'.Dict::Format('UI:RunQuery:Error', $e->getHtmlDesc()).'</div></div>';
				$oPage->p($sMessage);
			}
		}
		return $aFieldsMap;
	}

// Rolled back until 'fields' can be properly managed by AttributeQueryAttCodeSet
//
//	public function ComputeValues()
//	{
//		parent::ComputeValues();
//
//		// Remove unwanted attribute codes
//		$aChanges = $this->ListChanges();
//		if (isset($aChanges['fields']))
//		{
//			$oAttDef = MetaModel::GetAttributeDef(get_class($this), 'fields');
//			$aArgs = array('this' => $this);
//			$aAllowedValues = $oAttDef->GetAllowedValues($aArgs);
//
//			/** @var \ormSet $oValue */
//			$oValue = $this->Get('fields');
//			$aValues = $oValue->GetValues();
//			$bChanged = false;
//			foreach($aValues as $key => $sValue)
//			{
//				if (!isset($aAllowedValues[$sValue]))
//				{
//					unset($aValues[$key]);
//					$bChanged = true;
//				}
//			}
//			if ($bChanged)
//			{
//				$oValue->SetValues($aValues);
//				$this->Set('fields', $oValue);
//			}
//		}
//	}

}

?>
