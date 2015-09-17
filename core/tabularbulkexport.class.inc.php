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
				return parent::DisplayFormPart($oP, $sPartId);
		}
	}

	protected function SuggestFields($aSuggestedFields)
	{
		$aRet = array();
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
			$sMostRelevantField = $this->SuggestField($sClass, $sAttCode);
			$sAttCodeEx = MetaModel::NormalizeFieldSpec($sClass, $sMostRelevantField);
			// Remove the aliases (if any) from the field names to make them compatible
			// with the 'short' notation used in this case by the widget
			if (count($aAliases) > 1)
			{
				$sAttCodeEx = $sAlias.'.'.$sAttCodeEx;
			}
			$aRet[] = $sAttCodeEx;
		}
		return $aRet;
	}

	protected function SuggestField($sClass, $sAttCode)
	{
		return $sAttCode;
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

				$bAddFriendlyName = true;
				$oKeyAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
				$sRemoteClass = $oKeyAttDef->GetTargetClass();
				$sFriendlyNameAttCode = MetaModel::GetFriendlyNameAttributeCode($sRemoteClass);
				if (!is_null($sFriendlyNameAttCode))
				{
					// The friendly name is made of a single attribute, check if that attribute is present as an external field
					foreach(MetaModel::ListAttributeDefs($sClass) as $sSubAttCode => $oSubAttDef)
					{
						if ($oSubAttDef instanceof AttributeExternalField)
						{
							if (($oSubAttDef->GetKeyAttCode() == $sAttCode) && ($oSubAttDef->GetExtAttCode() == $sFriendlyNameAttCode))
							{
								$bAddFriendlyName = false;
							}
						}
					}
				}

				$aResult[$sAttCode] = array('code' => $sAttCode, 'unique_label' => $oAttDef->GetLabel(), 'label' => Dict::S('UI:CSVImport:idField'), 'attdef' => $oAttDef);

				if ($bAddFriendlyName)
				{
					if ($this->IsExportableField($sClass, $sAttCode.'->friendlyname'))
					{
						$aResult[$sAttCode.'->friendlyname'] = array('code' =>  $sAttCode.'->friendlyname', 'unique_label' => $oAttDef->GetLabel().'->'.Dict::S('Core:FriendlyName-Label'), 'label' => Dict::S('Core:FriendlyName-Label'), 'attdef' => MetaModel::GetAttributeDef($sClass, $sAttCode.'_friendlyname'));
					}
				}

				foreach(MetaModel::ListAttributeDefs($sClass) as $sSubAttCode => $oSubAttDef)
				{
					if ($oSubAttDef instanceof AttributeExternalField)
					{
						if ($this->IsExportableField($sClass, $sSubAttCode, $oSubAttDef))
						{
							if ($oSubAttDef->GetKeyAttCode() == $sAttCode)
							{
								$sAttCodeEx = $sAttCode.'->'.$oSubAttDef->GetExtAttCode();
								$aResult[$sAttCodeEx] = array('code' => $sAttCodeEx, 'unique_label' => $oAttDef->GetLabel().'->'.$oSubAttDef->GetExtAttDef()->GetLabel(), 'label' => MetaModel::GetLabel($sRemoteClass, $oSubAttDef->GetExtAttCode()), 'attdef' => $oSubAttDef);
							}
						}
					}
				}

				// Add the reconciliation keys
				foreach(MetaModel::GetReconcKeys($sRemoteClass) as $sRemoteAttCode)
			  	{
					$sAttCodeEx = $sAttCode.'->'.$sRemoteAttCode;
					if (!array_key_exists($sAttCodeEx, $aResult))
					{
						$oRemoteAttDef = MetaModel::GetAttributeDef($sRemoteClass, $sRemoteAttCode);
						if ($this->IsExportableField($sRemoteClass, $sRemoteAttCode, $oRemoteAttDef))
						{
							$aResult[$sAttCodeEx] = array('code' =>  $sAttCodeEx, 'unique_label' => $oAttDef->GetLabel().'->'.$oRemoteAttDef->GetLabel(), 'label' => MetaModel::GetLabel($sRemoteClass, $sRemoteAttCode), 'attdef' => $oRemoteAttDef);
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
							if ($this->IsExportableField($sClass, $sSubAttCode, $oSubAttDef))
							{
								$aResult[$sSubAttCode] = array('code' => $sSubAttCode, 'unique_label' => $oSubAttDef->GetLabel(), 'label' => $oSubAttDef->GetLabel(), 'attdef' => $oSubAttDef);
							}
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
		$aAllAttCodes = array();
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
			if ($this->IsExportableField($sClass, 'id'))
			{
				$sFriendlyNameAttCode = MetaModel::GetFriendlyNameAttributeCode($sClass);
				if (is_null($sFriendlyNameAttCode))
				{
					// The friendly name is made of several attribute
					$aSubAttr = array(
						array('attcodeex' => 'id', 'code' => $sShortAlias.'id', 'unique_label' => $sShortAlias.Dict::S('UI:CSVImport:idField'), 'label' => $sShortAlias.'id'),
						array('attcodeex' => 'friendlyname', 'code' => $sShortAlias.'friendlyname', 'unique_label' => $sShortAlias.Dict::S('Core:FriendlyName-Label'), 'label' => $sShortAlias.Dict::S('Core:FriendlyName-Label')),
					);
				}
				else
				{
					// The friendly name has no added value
					$aSubAttr = array();
				}
				$aAllFields[] = array('attcodeex' => 'id', 'code' => $sShortAlias.'id', 'unique_label' => $sShortAlias.Dict::S('UI:CSVImport:idField'), 'label' => Dict::S('UI:CSVImport:idField'), 'subattr' => $aSubAttr);
			}
			foreach(MetaModel::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
			{
				if($this->IsSubAttribute($sClass, $sAttCode, $oAttDef)) continue;

				if ($this->IsExportableField($sClass, $sAttCode, $oAttDef))
				{
					$sShortLabel = $oAttDef->GetLabel();
					$sLabel = $sShortAlias.$oAttDef->GetLabel();
					$aSubAttr = $this->GetSubAttributes($sClass, $sAttCode, $oAttDef);
					$aValidSubAttr = array();
					foreach($aSubAttr as $aSubAttDef)
					{
						$aValidSubAttr[] = array('attcodeex' => $aSubAttDef['code'], 'code' => $sShortAlias.$aSubAttDef['code'], 'label' => $aSubAttDef['label'], 'unique_label' => $sShortAlias.$aSubAttDef['unique_label']);
					}
					$aAllFields[] = array('attcodeex' => $sAttCode, 'code' => $sShortAlias.$sAttCode, 'label' => $sShortLabel, 'unique_label' => $sLabel, 'subattr' => $aValidSubAttr);
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

			foreach ($aAllFields as $aFieldSpec)
			{
				$sAttCode = $aFieldSpec['attcodeex'];
				if (count($aFieldSpec['subattr']) > 0)
				{
					foreach ($aFieldSpec['subattr'] as $aSubFieldSpec)
					{
						$aAllAttCodes[$sAlias][] = $aSubFieldSpec['attcodeex'];
					}
				}
				else
				{
					$aAllAttCodes[$sAlias][] = $sAttCode;
				}
			}
		}

		$oP->add('<div id="'.$sWidgetId.'"></div>');
		$JSAllFields = json_encode($aAllFieldsByAlias);

		// First, fetch only the ids - the rest will be fetched by an object reload
		$oSet = new DBObjectSet($this->oSearch);
		$iCount = $oSet->Count();

		foreach ($this->oSearch->GetSelectedClasses() as $sAlias => $sClass)
		{
			$aColumns[$sAlias] = array();
		}
		$oSet->OptimizeColumnLoad($aColumns);
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

				foreach ($aAllAttCodes[$sAlias] as $sAttCodeEx)
				{
					$oObj = $aRow[$sAlias];
					$aSampleRow[$sShortAlias.$sAttCodeEx] = $oObj ? $this->GetSampleData($oObj, $sAttCodeEx) : '';
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
	 * @param AttributeDefinition $oAttDef Can be null in case the attribute definition has not been fetched by the caller
	 * @return boolean
	 */
	protected function IsExportableField($sClass, $sAttCode, $oAttDef = null)
	{
		if ($sAttCode == 'id') return true;
		if (is_null($oAttDef))
		{
			$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
		}
		if ($oAttDef instanceof AttributeLinkedSet) return false;
		return true; //$oAttDef->IsScalar();
	}

	protected function GetSampleData($oObj, $sAttCode)
	{
		if ($sAttCode == 'id') return $oObj->GetKey();
		return $oObj->GetEditValue($sAttCode);
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
				if (($sFields === null) || ($sFields === ''))
				{
					// No 'fields' parameter supplied, take the fields from the query phrasebook definition
					$sFields = trim($oQuery->Get('fields'));
					if ($sFields === '')
					{
						throw new BulkExportMissingParameterException('fields');
					}
				}
			}
			else
			{
				throw BulkExportException('Invalid value for the parameter: query. There is no Query Phrasebook with id = '.$sQueryId, Dict::Format('Core:BulkExport:InvalidParameter_Query', $sQueryId));
			}
		}

		// Interpret (and check) the list of fields
		//
		$aSelectedClasses = $this->oSearch->GetSelectedClasses();
		$aAliases = array_keys($aSelectedClasses);
		$aAuthorizedClasses = array();
		foreach($aSelectedClasses as $sAlias => $sClassName)
		{
			if (UserRights::IsActionAllowed($sClassName, UR_ACTION_BULK_READ) == UR_ALLOWED_YES)
			{
				$aAuthorizedClasses[$sAlias] = $sClassName;
			}
		}
		$aFields = explode(',', $sFields);
		$this->aStatusInfo['fields'] = array();
		foreach($aFields as $sFieldSpec)
		{
			// Trim the values since it's natural to write: fields=name, first_name, org_name instead of fields=name,first_name,org_name
			$sExtendedAttCode = trim($sFieldSpec);

			if (preg_match('/^([^\.]+)\.(.+)$/', $sExtendedAttCode, $aMatches))
			{
				$sAlias = $aMatches[1];
				$sAttCode = $aMatches[2];
			}
			else
			{
				$sAlias = reset($aAliases);
				$sAttCode = $sExtendedAttCode;
			}
			if (!array_key_exists($sAlias, $aSelectedClasses))
			{
				throw new Exception("Invalid alias '$sAlias' for the column '$sExtendedAttCode'. Availables aliases: '".implode("', '", $aAliases)."'");
			}
			$sClass = $aSelectedClasses[$sAlias];
			if (!array_key_exists($sAlias, $aAuthorizedClasses))
			{
				throw new Exception("You do not have enough permissions to bulk read data of class '$sClass' (alias: $sAlias)");
			}
				
			if ($this->bLocalizeOutput)
			{
				try
				{
					$sLabel = MetaModel::GetLabel($sClass, $sAttCode);
				}
				catch (Exception $e)
				{
					throw new Exception("Wrong field specification '$sFieldSpec': ".$e->getMessage());
			}
			}
			else
			{
				$sLabel = $sAttCode;
			}
			if (count($aAuthorizedClasses) > 1)
			{
				$sColLabel = $sAlias.'.'.$sLabel;
			}
			else
			{
				$sColLabel = $sLabel;
			}
			$this->aStatusInfo['fields'][] = array(
				'sFieldSpec' => $sExtendedAttCode,
				'sAlias' => $sAlias,
				'sClass' => $sClass,
				'sAttCode' => $sAttCode,
				'sLabel' => $sLabel,
				'sColLabel' => $sColLabel
			);
		}
	}

	/**
	 * Prepare the given object set with the list of fields as read into $this->aStatusInfo['fields']
	 */
	protected function OptimizeColumnLoad(DBObjectSet $oSet)
	{
		$aColumnsToLoad = array();

		foreach($this->aStatusInfo['fields'] as $iCol => $aFieldSpec)
		{
			$sClass = $aFieldSpec['sClass'];
			$sAlias = $aFieldSpec['sAlias'];
			$sAttCode = $aFieldSpec['sAttCode'];
				
			if (!array_key_exists($sAlias, $aColumnsToLoad))
			{
				$aColumnsToLoad[$sAlias] = array();
			}
			// id is not a real attribute code and, moreover, is always loaded
			if ($sAttCode != 'id')
			{
				// Extended attributes are not recognized by DBObjectSet::OptimizeColumnLoad
				if (($iPos = strpos($sAttCode, '->')) === false)
				{
					$aColumnsToLoad[$sAlias][] = $sAttCode;
					$sClass = '???';
				}
				else
				{
					$sExtKeyAttCode = substr($sAttCode, 0, $iPos);
					$sRemoteAttCode = substr($sAttCode, $iPos + 2);

					// Load the external key to avoid an object reload!
					$aColumnsToLoad[$sAlias][] = $sExtKeyAttCode;

					// Load the external field (if any) to avoid getting the remote object (see DBObject::Get that does the same)
					$oExtFieldAtt = MetaModel::FindExternalField($sClass, $sExtKeyAttCode, $sRemoteAttCode);
					if (!is_null($oExtFieldAtt))
					{
						$aColumnsToLoad[$sAlias][] = $oExtFieldAtt->GetCode();
					}
				}
			}
		}

		// Add "always loaded attributes"
		//
		$aSelectedClasses = $this->oSearch->GetSelectedClasses();
		foreach ($aSelectedClasses as $sAlias => $sClass)
		{
			foreach (MetaModel::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
			{
				if ($oAttDef->AlwaysLoadInTables())
				{
					$aColumnsToLoad[$sAlias][] = $sAttCode;
				}
			}
		}

		$oSet->OptimizeColumnLoad($aColumnsToLoad);
	}	 	
}
