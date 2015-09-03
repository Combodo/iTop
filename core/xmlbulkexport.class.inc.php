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
 * Bulk export: XML export
 *
 * @copyright   Copyright (C) 2015 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class XMLBulkExport extends BulkExport
{
	public function DisplayUsage(Page $oP)
	{
		$oP->p(" * xml format options:");
		$oP->p(" *\tno_localize: set to 1 to retrieve non-localized values (for instance for ENUM values). Default is 0 (= localized values)");
		$oP->p(" *\tlinksets: set to 1 to retrieve links to related objects (1-N or N-N relations). Default is 0 (= only scalar fields)");
	}

	public function EnumFormParts()
	{
		return array_merge(parent::EnumFormParts(), array('xml_options' => array('xml_no_options')));
	}
	
	public function DisplayFormPart(WebPage $oP, $sPartId)
	{
		switch($sPartId)
		{
			case 'xml_options':
				$sNoLocalizeChecked = (utils::ReadParam('no_localize', 0) == 1) ? ' checked ' : '';
				$sLinksetChecked = (utils::ReadParam('linksets', 0) == 1) ? ' checked ' : '';
				$oP->add('<fieldset><legend>'.Dict::S('Core:BulkExport:XMLOptions').'</legend>');
				$oP->add('<table>');
				$oP->add('<tr>');
				$oP->add('<td><input type="checkbox" id="xml_no_localize" name="no_localize" value="1"'.$sNoLocalizeChecked.'><label for="xml_no_localize"> '.Dict::S('Core:BulkExport:OptionNoLocalize').'</label></td>');
				$oP->add('</tr>');
				$oP->add('<tr>');
				$oP->add('<td><input type="checkbox" id="xml_linksets" name="linksets" value="1"'.$sLinksetChecked.'><label for="xml_linksets"> '.Dict::S('Core:BulkExport:OptionLinkSets').'</label></td>');
				$oP->add('</tr>');
				$oP->add('</table>');
				$oP->add('</fieldset>');
				break;
					
			default:
				return parent:: DisplayFormPart($oP, $sPartId);
		}
	}
	
	public function ReadParameters()
	{
		parent::ReadParameters();
	
		$this->aStatusInfo['linksets'] = (utils::ReadParam('linksets', 0) == 1);
	}
	
	protected function GetSampleData($oObj, $sAttCode)
	{
		$sRet = ($sAttCode == 'id') ? $oObj->GetKey() : $oObj->GetAsXML($sAttCode);
		return $sRet;
	}

	public function GetHeader()
	{
		// Check permissions
		foreach($this->oSearch->GetSelectedClasses() as $sAlias => $sClass)
		{
			if (UserRights::IsActionAllowed($sClass, UR_ACTION_BULK_READ) != UR_ALLOWED_YES)
			{
				throw new Exception("You do not have enough permissions to bulk read data of class '$sClass' (alias: $sAlias)");
			}
		}

		$oSet = new DBObjectSet($this->oSearch);
		$this->aStatusInfo['position'] = 0;
		$this->aStatusInfo['total'] = $oSet->Count();
		$sData = "<"."?xml version=\"1.0\" encoding=\"UTF-8\"?".">\n<Set>\n";
		return $sData;
	}

	public function GetNextChunk(&$aStatus)
	{
		$sRetCode = 'run';
		$iPercentage = 0;

		$iCount = 0;
		$sData = '';
		
		$oSet = new DBObjectSet($this->oSearch);
		$oSet->SetLimit($this->iChunkSize, $this->aStatusInfo['position']);
		
		$aClasses = $this->oSearch->GetSelectedClasses();
		$aAuthorizedClasses = array();
		$aClass2Attributes = array();
		foreach($aClasses as $sAlias => $sClassName)
		{
			if (UserRights::IsActionAllowed($sClassName, UR_ACTION_BULK_READ, $oSet) && (UR_ALLOWED_YES || UR_ALLOWED_DEPENDS))
			{
				$aAuthorizedClasses[$sAlias] = $sClassName;
				$aAttributes = array();
				foreach(MetaModel::ListAttributeDefs($sClassName) as $sAttCode=>$oAttDef)
				{
					if ($oAttDef->IsLinkSet() && !$this->aStatusInfo['linksets'])
					{
						continue;
					}
					if (!$oAttDef->IsWritable())
					{
						continue;
					}
					$aAttributes[$sAttCode] = $oAttDef;
					if ($oAttDef->IsExternalKey())
					{
						foreach(MetaModel::ListAttributeDefs($sClassName) as $sSubAttCode=>$oSubAttDef)
						{
							if ($oSubAttDef->IsExternalField() && ($oSubAttDef->GetKeyAttCode() == $sAttCode))
							{
								$aAttributes[$sAttCode.'_friendlyname'] = MetaModel::GetAttributeDef($sClassName, $sAttCode.'_friendlyname');
								$aAttributes[$sSubAttCode] = $oSubAttDef;
							}
						}
					}
				}
				$aClass2Attributes[$sAlias] = $aAttributes;
			}
		}
		
		$iPreviousTimeLimit = ini_get('max_execution_time');
		$iLoopTimeLimit = MetaModel::GetConfig()->Get('max_execution_time_per_loop');
		
		while ($aObjects = $oSet->FetchAssoc())
		{
			set_time_limit($iLoopTimeLimit);
			if (count($aAuthorizedClasses) > 1)
			{
				$sData .= "<Row>\n";
			}
			foreach($aAuthorizedClasses as $sAlias => $sClassName)
			{
				$oObj = $aObjects[$sAlias];
				if (is_null($oObj))
				{
					$sData .= "<$sClassName alias=\"$sAlias\" id=\"null\">\n";
				}
				else
				{
					$sClassName = get_class($oObj);
					$sData .= "<$sClassName alias=\"$sAlias\" id=\"".$oObj->GetKey()."\">\n";
				}
				foreach($aClass2Attributes[$sAlias] as $sAttCode=>$oAttDef)
				{
					if (is_null($oObj))
					{
						$sData .= "<$sAttCode>null</$sAttCode>\n";
					}
					else
					{
						$sValue = $oObj->GetAsXML($sAttCode, $this->bLocalizeOutput);
						$sData .= "<$sAttCode>$sValue</$sAttCode>\n";
					}
				}
				$sData .= "</$sClassName>\n";
			}
			if (count($aAuthorizedClasses) > 1)
			{
				$sData .= "</Row>\n";
			}
			$iCount++;
		}
		
		set_time_limit($iPreviousTimeLimit);
		$this->aStatusInfo['position'] += $this->iChunkSize;
		if ($this->aStatusInfo['total'] == 0)
		{
			$iPercentage = 100;
		}
		else
		{
			$iPercentage = floor(min(100.0, 100.0*$this->aStatusInfo['position']/$this->aStatusInfo['total']));
		}

		if ($iCount < $this->iChunkSize)
		{
			$sRetCode = 'done';
		}

		$aStatus = array('code' => $sRetCode, 'message' => Dict::S('Core:BulkExport:RetrievingData'), 'percentage' => $iPercentage);
		return $sData;
	}

	public function GetFooter()
	{
		$sData = "</Set>\n";

		return $sData;
	}

	public function GetSupportedFormats()
	{
		return array('xml' => Dict::S('Core:BulkExport:XMLFormat'));
	}

	public function GetMimeType()
	{
		return 'text/xml';
	}

	public function GetFileExtension()
	{
		return 'xml';
	}
}
