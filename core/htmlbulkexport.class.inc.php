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
 * Bulk export: HTML export
 *
 * @copyright   Copyright (C) 2015 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class HTMLBulkExport extends TabularBulkExport
{
	public function DisplayUsage(Page $oP)
	{
		$oP->p(" * html format options:");
		$oP->p(" *\tfields: (mandatory) the comma separated list of field codes to export (e.g: name,org_id,service_name...).");
	}

	public function EnumFormParts()
	{
		return array_merge(parent::EnumFormParts(), array('interactive_fields_html' => array('interactive_fields_html')));
	}

	public function DisplayFormPart(WebPage $oP, $sPartId)
	{
		switch($sPartId)
		{
			case 'interactive_fields_html':
				$this->GetInteractiveFieldsWidget($oP, 'interactive_fields_html');
				break;
					
			default:
				return parent:: DisplayFormPart($oP, $sPartId);
		}
	}

	protected function GetSampleData(DBObject $oObj, $sAttCode)
	{
		return $oObj->GetAsHTML($sAttCode);
	}

	public function GetHeader()
	{
        $sData = '';
		
		$oSet = new DBObjectSet($this->oSearch);
		$this->aStatusInfo['status'] = 'running';
		$this->aStatusInfo['position'] = 0;
		$this->aStatusInfo['total'] = $oSet->Count();

		$aSelectedClasses = $this->oSearch->GetSelectedClasses();
		foreach($aSelectedClasses as $sAlias => $sClassName)
		{
			if (UserRights::IsActionAllowed($sClassName, UR_ACTION_BULK_READ, $oSet) && (UR_ALLOWED_YES || UR_ALLOWED_DEPENDS))
			{
				$aAuthorizedClasses[$sAlias] = $sClassName;
			}
		}
		$aAliases = array_keys($aAuthorizedClasses);
		$aData = array();
		foreach($this->aStatusInfo['fields'] as $sExtendedAttCode)
		{
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
			if (!in_array($sAlias, $aAliases))
			{
				throw new Exception("Invalid alias '$sAlias' for the column '$sExtendedAttCode'. Availables aliases: '".implode("', '", $aAliases)."'");
			}
			$sClass = $aSelectedClasses[$sAlias];
				
			switch($sAttCode)
			{
				case 'id':
					if (count($aSelectedClasses) > 1)
					{
						$aData[] = $sAlias.'.id'; //@@@
					}
					else
					{
						$aData[] = 'id'; //@@@
					}
					break;

				default:
					$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
					if (count($aSelectedClasses) > 1)
					{
						$aData[] = $sAlias.'.'.$oAttDef->GetLabel();
					}
					else
					{
						$aData[] = $oAttDef->GetLabel();
					}
			}
		}
		$sData .= "<table class=\"listResults\">\n";
		$sData .= "<thead>\n";
		$sData .= "<tr>\n";
		foreach($aData as $sLabel)
		{
			$sData .= "<th>".$sLabel."</th>\n";
		}
		$sData .= "</tr>\n";
		$sData .= "</thead>\n";
		$sData .= "<tbody>\n";
		return $sData;
	}

	public function GetNextChunk(&$aStatus)
	{
		$sRetCode = 'run';
		$iPercentage = 0;

		$oSet = new DBObjectSet($this->oSearch);
		$aSelectedClasses = $this->oSearch->GetSelectedClasses();
		$aAliases = array_keys($aSelectedClasses);
		$oSet->SetLimit($this->iChunkSize, $this->aStatusInfo['position']);

		$aAliasByField = array();
		$aColumnsToLoad = array();

		// Prepare the list of aliases / columns to load
		foreach($this->aStatusInfo['fields'] as $sExtendedAttCode)
		{
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
				
			if (!in_array($sAlias, $aAliases))
			{
				throw new Exception("Invalid alias '$sAlias' for the column '$sExtendedAttCode'. Availables aliases: '".implode("', '", $aAliases)."'");
			}
				
			if (!array_key_exists($sAlias, $aColumnsToLoad))
			{
				$aColumnsToLoad[$sAlias] = array();
			}
			if ($sAttCode != 'id')
			{
				// id is not a real attribute code and, moreover, is always loaded
				$aColumnsToLoad[$sAlias][] = $sAttCode;
			}
			$aAliasByField[$sExtendedAttCode] = array('alias' => $sAlias, 'attcode' => $sAttCode);
		}

		$iCount = 0;
		$sData = '';
		$oSet->OptimizeColumnLoad($aColumnsToLoad);
		$iPreviousTimeLimit = ini_get('max_execution_time');
		$iLoopTimeLimit = MetaModel::GetConfig()->Get('max_execution_time_per_loop');
		while($aRow = $oSet->FetchAssoc())
		{
			set_time_limit($iLoopTimeLimit);
			$sFirstAlias = reset($aAliases);
			$sHilightClass = $aRow[$sFirstAlias]->GetHilightClass();
			if ($sHilightClass != '')
			{
				$sData .= "<tr class=\"$sHilightClass\">";
			}
			else
			{
				$sData .= "<tr>";
			}
			foreach($aAliasByField as $aAttCode)
			{
				$sField = '';
				switch($aAttCode['attcode'])
				{
					case 'id':
						$sField = $aRow[$aAttCode['alias']]->GetHyperlink();
						break;
							
					default:
						$sField = $aRow[$aAttCode['alias']]->GetAsHtml($aAttCode['attcode']);
				}
				$sValue = ($sField === '') ? '&nbsp;' : $sField;
				$sData .= "<td>$sValue</td>";
			}
			$sData .= "</tr>";
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
		$sData = "</tbody>\n";
		$sData .= "</table>\n";
		return $sData;
	}

	public function GetSupportedFormats()
	{
		return array('html' => Dict::S('Core:BulkExport:HTMLFormat'));
	}

	public function GetMimeType()
	{
		return 'text/html';
	}

	public function GetFileExtension()
	{
		return 'html';
	}
}
