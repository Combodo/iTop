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

	protected function GetSampleData($oObj, $sAttCode)
	{
		return $this->GetValue($oObj, $sAttCode);
	}

	protected function GetValue($oObj, $sAttCode)
	{
		switch($sAttCode)
		{
			case 'id':
				$sRet = $oObj->GetHyperlink();
				break;
					
			default:
				$value = $oObj->Get($sAttCode);
				if ($value instanceof ormCaseLog)
				{
					$sRet = $value->GetAsSimpleHtml();
				}
				elseif ($value instanceof ormStopWatch)
				{
					$sRet = $value->GetTimeSpent();
				}
				else
				{
					$sRet = $oObj->GetAsHtml($sAttCode);
				}
		}
		return $sRet;
	}

	public function GetHeader()
	{
		$sData = '';
		
		$oSet = new DBObjectSet($this->oSearch);
		$this->aStatusInfo['status'] = 'running';
		$this->aStatusInfo['position'] = 0;
		$this->aStatusInfo['total'] = $oSet->Count();

		$sData .= "<table class=\"listResults\">\n";
		$sData .= "<thead>\n";
		$sData .= "<tr>\n";
		foreach($this->aStatusInfo['fields'] as $iCol => $aFieldSpec)
		{
			$sData .= "<th>".$aFieldSpec['sColLabel']."</th>\n";
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
		$oSet->SetLimit($this->iChunkSize, $this->aStatusInfo['position']);
		$this->OptimizeColumnLoad($oSet);

		$sFirstAlias = $this->oSearch->GetClassAlias();

		$iCount = 0;
		$sData = '';
		$iPreviousTimeLimit = ini_get('max_execution_time');
		$iLoopTimeLimit = MetaModel::GetConfig()->Get('max_execution_time_per_loop');
		while($aRow = $oSet->FetchAssoc())
		{
			set_time_limit($iLoopTimeLimit);
			$oMainObj = $aRow[$sFirstAlias];
			$sHilightClass = '';
			if ($oMainObj)
			{
				$sHilightClass = $aRow[$sFirstAlias]->GetHilightClass();
			}
			if ($sHilightClass != '')
			{
				$sData .= "<tr class=\"$sHilightClass\">";
			}
			else
			{
				$sData .= "<tr>";
			}
			foreach($this->aStatusInfo['fields'] as $iCol => $aFieldSpec)
			{
				$sAlias = $aFieldSpec['sAlias'];
				$sAttCode = $aFieldSpec['sAttCode'];

				$oObj = $aRow[$sAlias];
				$sField = '';
				if ($oObj)
				{
					$sField = $this->GetValue($oObj, $sAttCode);
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
