<?php
// Copyright (C) 2015 Combodo SARL
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
 * Bulk export: Tabular export: abstract base class for all "tabular" exports.
 * Provides the user interface for selecting the column to be exported
 *
 * @copyright   Copyright (C) 2015 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

abstract class TabularBulkExport extends BulkExport
{
	public function EnumFormParts()
	{
		return array_merge(parent::EnumFormParts(), array('tabular_fields' => array('fields')));
	}

	public function DisplayFormPart(WebPage $oP, $sPartId)
	{
		switch($sPartId)
		{
			case 'tabular_fields':
				$sFields = utils::ReadParam('fields', '', true, 'raw_data');
				$sSuggestedFields = utils::ReadParam('suggested_fields', null, true, 'raw_data');
				if (($sSuggestedFields !== null) && ($sSuggestedFields !== ''))
				{
					$aSuggestedFields = explode(',', $sSuggestedFields);
					$sFields = implode(',', $this->SuggestFields($aSuggestedFields));
				}
				$oP->add('<input id="tabular_fields" type="hidden" size="50" name="fields" value="'.htmlentities($sFields, ENT_QUOTES, 'UTF-8').'"></input>');
				break;
					
			default:
				return parent:: DisplayFormPart($oP, $sPartId);
		}
	}

	protected function SuggestFields($aSuggestedFields)
	{
		// By defaults all fields are Ok, nothing gets translated but
		// you can overload this method if some fields are better exported
		// (in a given format) by using an alternate field, for example id => friendlyname
		$aAliases = $this->oSearch->GetSelectedClasses();
		foreach($aSuggestedFields as $idx => $sField)
		{
			if (preg_match('/^([^\\.]+)\\.(.+)$/', $sField, $aMatches))
			{
				$sAlias = $aMatches[1];
				$sAttCode = $aMatches[2];
				$sClass = $aAliases[$sAlias];
			}
			else
			{
				$sAlias = '';
				$sAttCode = $sField;
				$sClass = reset($aAliases);
			}
			$aSuggestedFields[$idx] = $this->SuggestField($aAliases, $sClass, $sAlias, $sAttCode);
		}
		return $aSuggestedFields;
	}

	protected function SuggestField($aAliases, $sClass, $sAlias, $sAttCode)
	{
		// Remove the aliases (if any) from the field names to make them compatible
		// with the 'short' notation used in this case by the widget
		if (count($aAliases) == 1)
		{
			return $sAttCode;
		}
		return $sAlias.'.'.$sAttCode;
	}

	protected function IsSubAttribute($sClass, $sAttCode, $oAttDef)
	{
		return (($oAttDef instanceof AttributeFriendlyName) || ($oAttDef instanceof AttributeExternalField) || ($oAttDef instanceof AttributeSubItem));
	}

	protected function GetSubAttributes($sClass, $sAttCode, $oAttDef)
	{
		$aResult = array();
		switch(get_class($oAttDef))
		{
			case 'AttributeExternalKey':
			case 'AttributeHierarchicalKey':
				$aResult[] = array('code' => $sAttCode, 'unique_label' => $oAttDef->GetLabel(), 'label' => Dict::S('Core:BulkExport:Identifier'), 'attdef' => $oAttDef);
				$aResult[] = array('code' =>  $sAttCode.'_friendlyname', 'unique_label' => $oAttDef->GetLabel().'->'.Dict::S('Core:BulkExport:Friendlyname'), 'label' => Dict::S('Core:BulkExport:Friendlyname'), 'attdef' => MetaModel::GetAttributeDef($sClass, $sAttCode.'_friendlyname'));

				foreach(MetaModel::ListAttributeDefs($sClass) as $sSubAttCode => $oSubAttDef)
				{
					if ($oSubAttDef instanceof AttributeExternalField)
					{
						if ($oSubAttDef->GetKeyAttCode() == $sAttCode)
						{
							$aResult[] = array('code' => $sSubAttCode, 'unique_label' => $oAttDef->GetLabel().'->'.$oSubAttDef->GetExtAttDef()->GetLabel(), 'label' => $oSubAttDef->GetExtAttDef()->GetLabel(), 'attdef' => $oSubAttDef);
						}
					}
				}
				break;
					
			case 'AttributeStopWatch':
				foreach(MetaModel::ListAttributeDefs($sClass) as $sSubAttCode => $oSubAttDef)
				{
					if ($oSubAttDef instanceof AttributeSubItem)
					{
						if ($oSubAttDef->GetParentAttCode() == $sAttCode)
						{
							$aResult[] = array('code' => $sSubAttCode, 'unique_label' => $oSubAttDef->GetLabel(), 'label' => $oSubAttDef->GetLabel(), 'attdef' => $oSubAttDef);
						}
					}
				}
				break;
					
		}
		return $aResult;
	}

	protected function GetInteractiveFieldsWidget(WebPage $oP, $sWidgetId)
	{
		$oSet = new DBObjectSet($this->oSearch);
		$aSelectedClasses = $this->oSearch->GetSelectedClasses();
		$aAuthorizedClasses = array();
		foreach($aSelectedClasses as $sAlias => $sClassName)
		{
			if (UserRights::IsActionAllowed($sClassName, UR_ACTION_BULK_READ, $oSet) && (UR_ALLOWED_YES || UR_ALLOWED_DEPENDS))
			{
				$aAuthorizedClasses[$sAlias] = $sClassName;
			}
		}
		$aAllFieldsByAlias = array();
		foreach($aAuthorizedClasses as $sAlias => $sClass)
		{
			$aAllFields = array();
			if (count($aAuthorizedClasses) > 1 )
			{
				$sShortAlias = $sAlias.'.';
			}
			else
			{
				$sShortAlias = '';
			}
			if ($this->IsValidField($sClass, 'id'))
			{
				$aAllFields[] = array('code' =>  $sShortAlias.'id', 'unique_label' => $sShortAlias.Dict::S('Core:BulkExport:Identifier'), 'label' => $sShortAlias.'id', 'subattr' => array(
					array('code' =>  $sShortAlias.'id', 'unique_label' => $sShortAlias.Dict::S('Core:BulkExport:Identifier'), 'label' => $sShortAlias.'id'),
					array('code' =>  $sShortAlias.'friendlyname', 'unique_label' => $sShortAlias.Dict::S('Core:BulkExport:Friendlyname'), 'label' => $sShortAlias.Dict::S('Core:BulkExport:Friendlyname')),
				));
			}
			foreach(MetaModel::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
			{
				if($this->IsSubAttribute($sClass, $sAttCode, $oAttDef)) continue;

				if ($this->IsValidField($sClass, $sAttCode, $oAttDef))
				{
					$sShortLabel = $oAttDef->GetLabel();
					$sLabel = $sShortAlias.$oAttDef->GetLabel();
					$aSubAttr = $this->GetSubAttributes($sClass, $sAttCode, $oAttDef);
					$aValidSubAttr = array();
					foreach($aSubAttr as $aSubAttDef)
					{
						if ($this->IsValidField($sClass, $aSubAttDef['code'], $aSubAttDef['attdef']))
						{
							$aValidSubAttr[] = array('code' => $sShortAlias.$aSubAttDef['code'], 'label' => $aSubAttDef['label'], 'unique_label' => $aSubAttDef['unique_label']);
						}
					}
					$aAllFields[] = array('code' => $sShortAlias.$sAttCode, 'label' => $sShortLabel, 'unique_label' => $sLabel, 'subattr' => $aValidSubAttr);
				}
			}
			usort($aAllFields,  array(get_class($this), 'SortOnLabel'));
			if (count($aAuthorizedClasses) > 1)
			{
				$sKey = MetaModel::GetName($sClass).' ('.$sAlias.')';
			}
			else
			{
				$sKey = MetaModel::GetName($sClass);
			}
			$aAllFieldsByAlias[$sKey] = $aAllFields;
		}

		$oP->add('<div id="'.$sWidgetId.'"></div>');
		$JSAllFields = json_encode($aAllFieldsByAlias);
		$oSet = new DBObjectSet($this->oSearch);
		$iCount = $oSet->Count();
		$iPreviewLimit = 3;
		$oSet->SetLimit($iPreviewLimit);
		$aSampleData = array();
		while($aRow = $oSet->FetchAssoc())
		{
			$aSampleRow = array();
			foreach($aAuthorizedClasses as $sAlias => $sClass)
			{
				if (count($aAuthorizedClasses) > 1 )
				{
					$sShortAlias = $sAlias.'.';
				}
				else
				{
					$sShortAlias = '';
				}

				if ($this->IsValidField($sClass, 'id'))
				{
					$aSampleRow[$sShortAlias.'id'] = $this->GetSampleKey($aRow[$sAlias]);
				}
				foreach(MetaModel::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
				{
					if ($this->IsValidField($sClass, $sAttCode, $oAttDef))
					{
						$aSampleRow[$sShortAlias.$sAttCode] = $this->GetSampleData($aRow[$sAlias], $sAttCode);
					}
				}
			}
			$aSampleData[] = $aSampleRow;
		}
		$sJSSampleData = json_encode($aSampleData);
		$aLabels = array(
			'preview_header' => Dict::S('Core:BulkExport:DragAndDropHelp'),
			'empty_preview' => Dict::S('Core:BulkExport:EmptyPreview'),
			'columns_order' => Dict::S('Core:BulkExport:ColumnsOrder'),
			'columns_selection' => Dict::S('Core:BulkExport:AvailableColumnsFrom_Class'),
			'check_all' => Dict::S('Core:BulkExport:CheckAll'),
			'uncheck_all' => Dict::S('Core:BulkExport:UncheckAll'),
			'no_field_selected' => Dict::S('Core:BulkExport:NoFieldSelected'),
		);
		$sJSLabels = json_encode($aLabels);
		$oP->add_ready_script(
<<<EOF
$('#$sWidgetId').tabularfieldsselector({fields: $JSAllFields, value_holder: '#tabular_fields', advanced_holder: '#tabular_advanced', sample_data: $sJSSampleData, total_count: $iCount, preview_limit: $iPreviewLimit, labels: $sJSLabels });
EOF
		);
	}

	static public function SortOnLabel($aItem1, $aItem2)
	{
		return strcmp($aItem1['label'], $aItem2['label']);
	}

	/**
	 * Tells if the specified field can be exported
	 * @param unknown $sClass
	 * @param unknown $sAttCode
	 * @param AttributeDefinition $oAttDef Can be null when $sAttCode == 'id'
	 * @return boolean
	 */
	protected function IsValidField($sClass, $sAttCode, $oAttDef = null)
	{
		if ($sAttCode == 'id') return true;
		if ($oAttDef instanceof AttributeLinkedSet) return false;
		return true; //$oAttDef->IsScalar();
	}

	/**
	 * Tells if the specified field is part of the "advanced" fields
	 * @param unknown $sClass
	 * @param unknown $sAttCode
	 * @param AttributeDefinition $oAttDef Can be null when $sAttCode == 'id'
	 * @return boolean
	 */
	protected function IsAdvancedValidField($sClass, $sAttCode, $oAttDef = null)
	{
		return (($sAttCode == 'id') || ($oAttDef instanceof AttributeExternalKey));
	}

	protected function GetSampleData(DBObject $oObj, $sAttCode)
	{
		if ($oObj == null) return '';
		return $oObj->GetEditValue($sAttCode);
	}

	protected function GetSampleKey(DBObject $oObj)
	{
		if ($oObj == null) return '';
		return $oObj->GetKey();
	}

	public function ReadParameters()
	{
		parent::ReadParameters();
		$sQueryId = utils::ReadParam('query', null, true);
		$sFields = utils::ReadParam('fields', null, true, 'raw_data');
		if ((($sFields === null) || ($sFields === '')) && ($sQueryId === null))
		{
			throw new BulkExportMissingParameterException('fields');
		}
		else if(($sQueryId !== null) && ($sQueryId !== null))
		{
			$oSearch = DBObjectSearch::FromOQL('SELECT QueryOQL WHERE id = :query_id', array('query_id' => $sQueryId));
			$oQueries = new DBObjectSet($oSearch);
			if ($oQueries->Count() > 0)
			{
				$oQuery = $oQueries->Fetch();
				$sFields = trim($oQuery->Get('fields'));
				if ($sFields === '')
				{
					throw new BulkExportMissingParameterException('fields');
				}
			}
			else
			{
				throw BulkExportException('Invalid value for the parameter: query. There is no Query Phrasebook with id = '.$sQueryId, Dict::Format('Core:BulkExport:InvalidParameter_Query', $sQueryId));
			}
		}

		$this->aStatusInfo['fields'] = explode(',', $sFields);
	}
}
