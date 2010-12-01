<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * Bulk change facility (common to interactive and batch usages)
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */


/**
 * BulkChange
 * Interpret a given data set and update the DB accordingly (fake mode avail.) 
 *
 * @package     iTopORM
 */

class BulkChangeException extends CoreException
{
}

/**
 * CellChangeSpec
 * A series of classes, keeping the information about a given cell: could it be changed or not (and why)?  
 *
 * @package     iTopORM
 */
abstract class CellChangeSpec
{
	protected $m_proposedValue;
	protected $m_sOql; // in case of ambiguity

	public function __construct($proposedValue, $sOql = '')
	{
		$this->m_proposedValue = $proposedValue;
		$this->m_sOql = $sOql;
	}

	static protected function ValueAsHtml($value)
	{
		if (MetaModel::IsValidObject($value))
		{
			return $value->GetHyperLink();
		}
		else
		{
			return htmlentities($value, ENT_QUOTES, 'UTF-8');
		}
	}

	public function GetValue()
	{
		return $this->m_proposedValue;
	}

	public function GetOql()
	{
		return $this->m_sOql;
	}

	abstract public function GetDescription();
}


class CellStatus_Void extends CellChangeSpec
{
	public function GetDescription()
	{
		return '';
	}
}

class CellStatus_Modify extends CellChangeSpec
{
	protected $m_previousValue;

	public function __construct($proposedValue, $previousValue)
	{
		$this->m_previousValue = $previousValue;
		parent::__construct($proposedValue);
	}

	public function GetDescription()
	{
		return 'Modified';
	}

	public function GetPreviousValue()
	{
		return $this->m_previousValue;
	}
}

class CellStatus_Issue extends CellStatus_Modify
{
	protected $m_sReason;

	public function __construct($proposedValue, $previousValue, $sReason)
	{
		$this->m_sReason = $sReason;
		parent::__construct($proposedValue, $previousValue);
	}

	public function GetDescription()
	{
		if (is_null($this->m_proposedValue))
		{
			return 'Could not be changed - reason: '.$this->m_sReason;
		}
		return 'Could not be changed to '.$this->m_proposedValue.' - reason: '.$this->m_sReason;
	}
}

class CellStatus_SearchIssue extends CellStatus_Issue
{
	public function __construct()
	{
		parent::__construct(null, null, null);
	}

	public function GetDescription()
	{
		return 'No match';
	}
}

class CellStatus_NullIssue extends CellStatus_Issue
{
	public function __construct()
	{
		parent::__construct(null, null, null);
	}

	public function GetDescription()
	{
		return 'Missing mandatory value';
	}
}


class CellStatus_Ambiguous extends CellStatus_Issue
{
	protected $m_iCount;

	public function __construct($previousValue, $iCount, $sOql)
	{
		$this->m_iCount = $iCount;
		$this->m_sQuery = $sOql;
		parent::__construct(null, $previousValue, '');
	}

	public function GetDescription()
	{
		$sCount = $this->m_iCount;
		return "Ambiguous: found $sCount objects";
	}
}


/**
 * RowStatus
 * A series of classes, keeping the information about a given row: could it be changed or not (and why)?  
 *
 * @package     iTopORM
 */
abstract class RowStatus
{
	public function __construct()
	{
	}

	abstract public function GetDescription();
}

class RowStatus_NoChange extends RowStatus
{
	public function GetDescription()
	{
		return "unchanged";
	}
}

class RowStatus_NewObj extends RowStatus
{
	public function GetDescription()
	{
		return "created";
	}
}

class RowStatus_Modify extends RowStatus
{
	protected $m_iChanged;

	public function __construct($iChanged)
	{
		$this->m_iChanged = $iChanged;
	}

	public function GetDescription()
	{
		return "updated ".$this->m_iChanged." cols";
	}
}

class RowStatus_Disappeared extends RowStatus_Modify
{
	public function GetDescription()
	{
		return "disappeared, changed ".$this->m_iChanged." cols";
	}
}

class RowStatus_Issue extends RowStatus
{
	protected $m_sReason;

	public function __construct($sReason)
	{
		$this->m_sReason = $sReason;
	}

	public function GetDescription()
	{
		return 'Issue: '.$this->m_sReason;
	}
}


/**
 * BulkChange
 *
 * @package iTopORM
 */
class BulkChange
{
	protected $m_sClass; 
	protected $m_aData; // Note: hereafter, iCol maybe actually be any acceptable key (string)
	// #@# todo: rename the variables to sColIndex
	protected $m_aAttList; // attcode => iCol
	protected $m_aExtKeys; // aExtKeys[sExtKeyAttCode][sExtReconcKeyAttCode] = iCol;
	protected $m_aReconcilKeys; // attcode (attcode = 'id' for the pkey)
	protected $m_sSynchroScope; // OQL - if specified, then the missing items will be reported
	protected $m_aOnDisappear; // array of attcode => value, values to be set when an object gets out of scope (ignored if no scope has been defined)

	public function __construct($sClass, $aData, $aAttList, $aExtKeys, $aReconcilKeys, $sSynchroScope = null, $aOnDisappear = null)
	{
		$this->m_sClass = $sClass;
		$this->m_aData = $aData;
		$this->m_aAttList = $aAttList;
		$this->m_aReconcilKeys = $aReconcilKeys;
		$this->m_aExtKeys = $aExtKeys;
		$this->m_sSynchroScope = $sSynchroScope;
		$this->m_aOnDisappear = $aOnDisappear;
	}

	protected function ResolveExternalKey($aRowData, $sAttCode, &$aResults)
	{
		$oExtKey = MetaModel::GetAttributeDef($this->m_sClass, $sAttCode);
		$oReconFilter = new CMDBSearchFilter($oExtKey->GetTargetClass());
		foreach ($this->m_aExtKeys[$sAttCode] as $sForeignAttCode => $iCol)
		{
			// The foreign attribute is one of our reconciliation key
			$oReconFilter->AddCondition($sForeignAttCode, $aRowData[$iCol], '=');
			$aResults[$iCol] = new CellStatus_Void($aRowData[$iCol]);
		}

		$oExtObjects = new CMDBObjectSet($oReconFilter);
		$aKeys = $oExtObjects->ToArray();
		return array($oReconFilter->ToOql(), $aKeys);
	}

	// Returns true if the CSV data specifies that the external key must be left undefined
	protected function IsNullExternalKeySpec($aRowData, $sAttCode)
	{
		$oExtKey = MetaModel::GetAttributeDef($this->m_sClass, $sAttCode);
		foreach ($this->m_aExtKeys[$sAttCode] as $sForeignAttCode => $iCol)
		{
			// The foreign attribute is one of our reconciliation key
			if (strlen($aRowData[$iCol]) > 0)
			{
				return false;
			}
		}
		return true;
	}

	protected function PrepareObject(&$oTargetObj, $aRowData, &$aErrors)
	{
		$aResults = array();
		$aErrors = array();
	
		// External keys reconciliation
		//
		foreach($this->m_aExtKeys as $sAttCode => $aKeyConfig)
		{
			// Skip external keys used for the reconciliation process
			// if (!array_key_exists($sAttCode, $this->m_aAttList)) continue;

			$oExtKey = MetaModel::GetAttributeDef(get_class($oTargetObj), $sAttCode);

			if ($this->IsNullExternalKeySpec($aRowData, $sAttCode))
			{
				foreach ($aKeyConfig as $sForeignAttCode => $iCol)
				{
					$aResults[$iCol] = new CellStatus_Void($aRowData[$iCol]);
				}
				if ($oExtKey->IsNullAllowed())
				{
					$oTargetObj->Set($sAttCode, $oExtKey->GetNullValue());
					$aResults[$sAttCode]= new CellStatus_Void($oExtKey->GetNullValue());
				}
				else
				{
					$aErrors[$sAttCode] = "Null not allowed";
					$aResults[$sAttCode]= new CellStatus_Issue(null, $oTargetObj->Get($sAttCode), 'Null not allowed');
				}
			}
			else
			{
				$oReconFilter = new CMDBSearchFilter($oExtKey->GetTargetClass());
				foreach ($aKeyConfig as $sForeignAttCode => $iCol)
				{
					// The foreign attribute is one of our reconciliation key
					$oReconFilter->AddCondition($sForeignAttCode, $aRowData[$iCol], '=');
					$aResults[$iCol] = new CellStatus_Void($aRowData[$iCol]);
				}
				$oExtObjects = new CMDBObjectSet($oReconFilter);
				switch($oExtObjects->Count())
				{
				case 0:
					$aErrors[$sAttCode] = "Object not found";
					$aResults[$sAttCode]= new CellStatus_SearchIssue();
					break;
				case 1:
					// Do change the external key attribute
					$oForeignObj = $oExtObjects->Fetch();
					$oTargetObj->Set($sAttCode, $oForeignObj->GetKey());
					break;
				default:
					$aErrors[$sAttCode] = "Found ".$oExtObjects->Count()." matches";
					$aResults[$sAttCode]= new CellStatus_Ambiguous($oTargetObj->Get($sAttCode), $oExtObjects->Count(), $oReconFilter->ToOql());
				}
			}

			// Report
			if (!array_key_exists($sAttCode, $aResults))
			{
				$iForeignObj = $oTargetObj->Get($sAttCode);
				if (array_key_exists($sAttCode, $oTargetObj->ListChanges()))
				{
					if ($oTargetObj->IsNew())
					{
						$aResults[$sAttCode]= new CellStatus_Void($iForeignObj);
					}
					else
					{
						$aResults[$sAttCode]= new CellStatus_Modify($iForeignObj, $oTargetObj->GetOriginal($sAttCode));
					}
				}
				else
				{
					$aResults[$sAttCode]= new CellStatus_Void($iForeignObj);
				}
			}
		}
	
		// Set the object attributes
		//
		foreach ($this->m_aAttList as $sAttCode => $iCol)
		{
			// skip the private key, if any
			if ($sAttCode == 'id') continue;

			$res = $oTargetObj->CheckValue($sAttCode, $aRowData[$iCol]);
			if ($res === true)
			{
				$oTargetObj->Set($sAttCode, $aRowData[$iCol]);
			}
			else
			{
				// $res is a string with the error description
				$aErrors[$sAttCode] = "Unexpected value for attribute '$sAttCode': $res";
			}
		}
	
		// Reporting on fields
		//
		$aChangedFields = $oTargetObj->ListChanges();
		foreach ($this->m_aAttList as $sAttCode => $iCol)
		{
			if ($sAttCode == 'id')
			{
				$aResults[$iCol]= new CellStatus_Void($aRowData[$iCol]);
			}
			if (isset($aErrors[$sAttCode]))
			{
				$aResults[$iCol]= new CellStatus_Issue($oTargetObj->Get($sAttCode), $oTargetObj->GetOriginal($sAttCode), $aErrors[$sAttCode]);
			}
			elseif (array_key_exists($sAttCode, $aChangedFields))
			{
				if ($oTargetObj->IsNew())
				{
					$aResults[$iCol]= new CellStatus_Void($oTargetObj->Get($sAttCode));
				}
				else
				{
					$aResults[$iCol]= new CellStatus_Modify($oTargetObj->Get($sAttCode), $oTargetObj->GetOriginal($sAttCode));
				}
			}
			else
			{
				// By default... nothing happens
				$aResults[$iCol]= new CellStatus_Void($aRowData[$iCol]);
			}
		}
	
		// Checks
		//
		$res = $oTargetObj->CheckConsistency();
		if ($res !== true)
		{
			// $res contains the error description
			$aErrors["GLOBAL"] = "Attributes not consistent with each others: $res";
		}
		return $aResults;
	}
	
	protected function PrepareMissingObject(&$oTargetObj, &$aErrors)
	{
		$aResults = array();
		$aErrors = array();
	
		// External keys
		//
		foreach($this->m_aExtKeys as $sAttCode => $aKeyConfig)
		{
			//$oExtKey = MetaModel::GetAttributeDef(get_class($oTargetObj), $sAttCode);
			$aResults[$sAttCode]= new CellStatus_Void($oTargetObj->Get($sAttCode));

			foreach ($aKeyConfig as $sForeignAttCode => $iCol)
			{
				$aResults[$iCol] = new CellStatus_Void('?');
			}
		}
	
		// Update attributes
		//
		foreach($this->m_aOnDisappear as $sAttCode => $value)
		{
			if (!MetaModel::IsValidAttCode(get_class($oTargetObj), $sAttCode))
			{
				throw new BulkChangeException('Invalid attribute code', array('class' => get_class($oTargetObj), 'attcode' => $sAttCode));
			}
			$oTargetObj->Set($sAttCode, $value);
			if (!array_key_exists($sAttCode, $this->m_aAttList))
			{
				// #@# will be out of the reporting... (counted anyway)
			}
		}
	
		// Reporting on fields
		//
		$aChangedFields = $oTargetObj->ListChanges();
		foreach ($this->m_aAttList as $sAttCode => $iCol)
		{
			if ($sAttCode == 'id')
			{
				$aResults[$iCol]= new CellStatus_Void($oTargetObj->GetKey());
			}
			if (array_key_exists($sAttCode, $aChangedFields))
			{
				$aResults[$iCol]= new CellStatus_Modify($oTargetObj->Get($sAttCode), $oTargetObj->GetOriginal($sAttCode));
			}
			else
			{
				// By default... nothing happens
				$aResults[$iCol]= new CellStatus_Void($oTargetObj->Get($sAttCode));
			}
		}
	
		// Checks
		//
		$res = $oTargetObj->CheckConsistency();
		if ($res !== true)
		{
			// $res contains the error description
			$aErrors["GLOBAL"] = "Attributes not consistent with each others: $res";
		}
		return $aResults;
	}

	
	protected function CreateObject(&$aResult, $iRow, $aRowData, CMDBChange $oChange = null)
	{
		$oTargetObj = MetaModel::NewObject($this->m_sClass);
		$aResult[$iRow] = $this->PrepareObject($oTargetObj, $aRowData, $aErrors);
	
		if (count($aErrors) > 0)
		{
			$sErrors = implode(', ', $aErrors);
			$aResult[$iRow]["__STATUS__"] = new RowStatus_Issue("Unexpected attribute value(s)");
			return $oTargetObj;
		}
	
		// Check that any external key will have a value proposed
		$aMissingKeys = array();
		foreach (MetaModel::GetExternalKeys($this->m_sClass) as $sExtKeyAttCode => $oExtKey)
		{
			if (!$oExtKey->IsNullAllowed())
			{
				if (!array_key_exists($sExtKeyAttCode, $this->m_aExtKeys) && !array_key_exists($sExtKeyAttCode, $this->m_aAttList))
				{ 
					$aMissingKeys[] = $oExtKey->GetLabel();
				}
			}
		}
		if (count($aMissingKeys) > 0)
		{
			$sMissingKeys = implode(', ', $aMissingKeys);
			$aResult[$iRow]["__STATUS__"] = new RowStatus_Issue("Could not be created, due to missing external key(s): $sMissingKeys");
			return $oTargetObj;
		}
	
		// Optionaly record the results
		//
		if ($oChange)
		{
			$newID = $oTargetObj->DBInsertTrackedNoReload($oChange);
			$aResult[$iRow]["__STATUS__"] = new RowStatus_NewObj($this->m_sClass, $newID);
			$aResult[$iRow]["finalclass"] = get_class($oTargetObj);
			$aResult[$iRow]["id"] = new CellStatus_Void($newID);
		}
		else
		{
			$aResult[$iRow]["__STATUS__"] = new RowStatus_NewObj();
			$aResult[$iRow]["finalclass"] = get_class($oTargetObj);
			$aResult[$iRow]["id"] = new CellStatus_Void(0);
		}
		return $oTargetObj;
	}
	
	protected function UpdateObject(&$aResult, $iRow, $oTargetObj, $aRowData, CMDBChange $oChange = null)
	{
		$aResult[$iRow] = $this->PrepareObject($oTargetObj, $aRowData, $aErrors);

		// Reporting
		//
		$aResult[$iRow]["finalclass"] = get_class($oTargetObj);
		$aResult[$iRow]["id"] = new CellStatus_Void($oTargetObj->GetKey());

		if (count($aErrors) > 0)
		{
			$sErrors = implode(', ', $aErrors);
			$aResult[$iRow]["__STATUS__"] = new RowStatus_Issue("Unexpected attribute value(s)");
			return;
		}
	
		$aChangedFields = $oTargetObj->ListChanges();
		if (count($aChangedFields) > 0)
		{
			$aResult[$iRow]["__STATUS__"] = new RowStatus_Modify(count($aChangedFields));
	
			// Optionaly record the results
			//
			if ($oChange)
			{
				$oTargetObj->DBUpdateTracked($oChange);
			}
		}
		else
		{
			$aResult[$iRow]["__STATUS__"] = new RowStatus_NoChange();
		}
	}

	protected function UpdateMissingObject(&$aResult, $iRow, $oTargetObj, CMDBChange $oChange = null)
	{
		$aResult[$iRow] = $this->PrepareMissingObject($oTargetObj, $aErrors);

		// Reporting
		//
		$aResult[$iRow]["finalclass"] = get_class($oTargetObj);
		$aResult[$iRow]["id"] = new CellStatus_Void($oTargetObj->GetKey());

		if (count($aErrors) > 0)
		{
			$sErrors = implode(', ', $aErrors);
			$aResult[$iRow]["__STATUS__"] = new RowStatus_Issue("Unexpected attribute value(s)");
			return;
		}
	
		$aChangedFields = $oTargetObj->ListChanges();
		if (count($aChangedFields) > 0)
		{
			$aResult[$iRow]["__STATUS__"] = new RowStatus_Disappeared(count($aChangedFields));
	
			// Optionaly record the results
			//
			if ($oChange)
			{
				$oTargetObj->DBUpdateTracked($oChange);
			}
		}
		else
		{
			$aResult[$iRow]["__STATUS__"] = new RowStatus_Disappeared(0);
		}
	}
	
	public function Process(CMDBChange $oChange = null)
	{
		// Note: $oChange can be null, in which case the aim is to check what would be done

		// Debug...
		//
		if (false)
		{
			echo "<pre>\n";
			echo "Attributes:\n";
			print_r($this->m_aAttList);
			echo "ExtKeys:\n";
			print_r($this->m_aExtKeys);
			echo "Reconciliation:\n";
			print_r($this->m_aReconcilKeys);
			echo "Synchro scope:\n";
			print_r($this->m_sSynchroScope);
			echo "Synchro changes:\n";
			print_r($this->m_aOnDisappear);
			//echo "Data:\n";
			//print_r($this->m_aData);
			echo "</pre>\n";
			exit;
		}


		// Compute the results
		//
		if (!is_null($this->m_sSynchroScope))
		{
			$aVisited = array();
		}
		$aResult = array();
		foreach($this->m_aData as $iRow => $aRowData)
		{
			$oReconciliationFilter = new CMDBSearchFilter($this->m_sClass);
			$bSkipQuery = false;
			foreach($this->m_aReconcilKeys as $sAttCode)
			{
				$valuecondition = null;
				if (array_key_exists($sAttCode, $this->m_aExtKeys))
				{
					if ($this->IsNullExternalKeySpec($aRowData, $sAttCode))
					{
						$oExtKey = MetaModel::GetAttributeDef($this->m_sClass, $sAttCode);
						if ($oExtKey->IsNullAllowed())
						{
							$valuecondition = $oExtKey->GetNullValue();
							$aResult[$iRow][$sAttCode] = new CellStatus_Void($oExtKey->GetNullValue());
						}
						else
						{
							$aResult[$iRow][$sAttCode] = new CellStatus_NullIssue();
						}
					}
					else
					{
						// The value has to be found or verified
						list($sQuery, $aMatches) = $this->ResolveExternalKey($aRowData, $sAttCode, $aResult[$iRow]);
	
						if (count($aMatches) == 1)
						{
							$oRemoteObj = reset($aMatches); // first item
							$valuecondition = $oRemoteObj->GetKey();
							$aResult[$iRow][$sAttCode] = new CellStatus_Void($oRemoteObj->GetKey());
						} 					
						elseif (count($aMatches) == 0)
						{
							$aResult[$iRow][$sAttCode] = new CellStatus_SearchIssue();
						} 					
						else
						{
							$aResult[$iRow][$sAttCode] = new CellStatus_Ambiguous(null, count($aMatches), $sQuery);
						}
					} 					
				}
				else
				{
					// The value is given in the data row
					$iCol = $this->m_aAttList[$sAttCode];
					$valuecondition = $aRowData[$iCol];
				}
				if (is_null($valuecondition))
				{
					$bSkipQuery = true;
				}
				else
				{
					$oReconciliationFilter->AddCondition($sAttCode, $valuecondition, '=');
				}
			}
			if ($bSkipQuery)
			{
				$aResult[$iRow]["__STATUS__"]= new RowStatus_Issue("failed to reconcile");
			}
			else
			{
				$oReconciliationSet = new CMDBObjectSet($oReconciliationFilter);
				switch($oReconciliationSet->Count())
				{
				case 0:
					$oTargetObj = $this->CreateObject($aResult, $iRow, $aRowData, $oChange);
					// $aResult[$iRow]["__STATUS__"]=> set in CreateObject
					$aVisited[] = $oTargetObj->GetKey();
					break;
				case 1:
					$oTargetObj = $oReconciliationSet->Fetch();
					$this->UpdateObject($aResult, $iRow, $oTargetObj, $aRowData, $oChange);
					// $aResult[$iRow]["__STATUS__"]=> set in UpdateObject
					if (!is_null($this->m_sSynchroScope))
					{
						$aVisited[] = $oTargetObj->GetKey();
					}
					break;
				default:
					// Found several matches, ambiguous
					$aResult[$iRow]["__STATUS__"]= new RowStatus_Issue("ambiguous reconciliation");
					$aResult[$iRow]["id"]= new CellStatus_Ambiguous(0, $oReconciliationSet->Count(), $oReconciliationFilter->ToOql());
					$aResult[$iRow]["finalclass"]= 'n/a';
				}
			}
	
			// Whatever happened, do report the reconciliation values
			foreach($this->m_aAttList as $iCol)
			{
				if (!array_key_exists($iCol, $aResult[$iRow]))
				{
					$aResult[$iRow][$iCol] = new CellStatus_Void($aRowData[$iCol]);
				}
			}
			foreach($this->m_aExtKeys as $sAttCode => $aForeignAtts)
			{
				if (!array_key_exists($sAttCode, $aResult[$iRow]))
				{
					$aResult[$iRow][$sAttCode] = new CellStatus_Void('n/a');
				}
				foreach ($aForeignAtts as $sForeignAttCode => $iCol)
				{
					if (!array_key_exists($iCol, $aResult[$iRow]))
					{
						// The foreign attribute is one of our reconciliation key
						$aResult[$iRow][$iCol] = new CellStatus_Void($aRowData[$iCol]);
					}
				}
			}
		}

		if (!is_null($this->m_sSynchroScope))
		{
			// Compute the delta between the scope and visited objects
			$oScopeSearch = DBObjectSearch::FromOQL($this->m_sSynchroScope);
			$oScopeSet = new DBObjectSet($oScopeSearch);
			while ($oObj = $oScopeSet->Fetch())
			{
				$iObj = $oObj->GetKey();
				if (!in_array($iObj, $aVisited))
				{
					$iRow++;
					$this->UpdateMissingObject($aResult, $iRow, $oObj, $oChange);
				}
			}
		}

		return $aResult;
	}
}


?>
