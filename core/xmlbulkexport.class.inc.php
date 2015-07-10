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
		$oP->p(" *\tThere are no options for the XML format.");
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
				$sChecked = (utils::ReadParam('no_localize', 0) == 1) ? ' checked ' : '';
				$oP->add('<fieldset><legend>'.Dict::S('Core:BulkExport:XMLOptions').'</legend>');
				$oP->add('<table>');
				$oP->add('<tr>');
				$oP->add('<td><input type="checkbox" id="xml_no_localize" name="no_localize" value="1"'.$sChecked.'><label for="xml_no_localize"> '.Dict::S('Core:BulkExport:OptionNoLocalize').'</label></td>');
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
	
		$this->aStatusInfo['localize'] = (utils::ReadParam('no_localize', 0) != 1);
	}
	
	protected function GetSampleData(DBObject $oObj, $sAttCode)
	{
		return $oObj->GetAsXML($sAttCode);
	}

	public function GetHeader()
	{
		$oSet = new DBObjectSet($this->oSearch);
		$this->aStatusInfo['position'] = 0;
		$this->aStatusInfo['total'] = $oSet->Count();
		$sData = "<"."?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<Set>\n";
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
		
		$bLocalize = $this->aStatusInfo['localize'];
		
		$aClasses = $this->oSearch->GetSelectedClasses();
		$aAuthorizedClasses = array();
		foreach($aClasses as $sAlias => $sClassName)
		{
			if (UserRights::IsActionAllowed($sClassName, UR_ACTION_BULK_READ, $oSet) && (UR_ALLOWED_YES || UR_ALLOWED_DEPENDS))
			{
				$aAuthorizedClasses[$sAlias] = $sClassName;
			}
		}
		$aAttribs = array();
		$aList = array();
		$aList[$sAlias] = MetaModel::GetZListItems($sClassName, 'details');
		
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
				foreach(MetaModel::ListAttributeDefs($sClassName) as $sAttCode=>$oAttDef)
				{
					if (is_null($oObj))
					{
						$sData .= "<$sAttCode>null</$sAttCode>\n";
					}
					else
					{
						if ($oAttDef->IsWritable() )
						{
							$sValue = $oObj->GetAsXML($sAttCode, $bLocalize);
							$sData .= "<$sAttCode>$sValue</$sAttCode>\n";
						}
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
