<?php

/**
 * BulkChange
 * Interpret a given data set and update the DB accordingly (fake mode avail.) 
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @author      Denis Flaven <denisflave@free.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */

class BulkChangeException extends CoreException
{
}

/**
 * CellChangeSpec
 * A series of classes, keeping the information about a given cell: could it be changed or not (and why)?  
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     $itopversion$
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
			return htmlentities($value);
		}
	}

	public function GetValue()
	{
		return $this->m_proposedValue;
	}

	public function GetOql()
	{
		return $this->m_proposedValue;
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
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     $itopversion$
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
 ** BulkChange *
 ** @package iTopORM
 ** @author Romain Quetiez <romainquetiez@yahoo.fr>
 ** @license http://www.opensource.org/licenses/lgpl-license.php LGPL
 ** @link www.itop.com
 ** @since 1.0
 ** @version $itopversion$ */
class BulkChange
{
	protected $m_sClass; 
	protected $m_aData; // Note: hereafter, iCol maybe actually be any acceptable key (string)
	// #@# todo: rename the variables to sColIndex
	protected $m_aAttList; // attcode => iCol
	protected $m_aExtKeys; // aExtKeys[sExtKeyAttCode][sExtReconcKeyAttCode] = iCol;
	protected $m_aReconcilKeys;// attcode (attcode = 'id' for the pkey) 

	public function __construct($sClass, $aData, $aAttList, $aExtKeys, $aReconcilKeys)
	{
		$this->m_sClass = $sClass;
		$this->m_aData = $aData;
		$this->m_aAttList = $aAttList;
		$this->m_aReconcilKeys = $aReconcilKeys;
		$this->m_aExtKeys = $aExtKeys;
	}

	static protected function MakeSpecObject($sClass, $iId)
	{
		try
		{
			$oObj = MetaModel::GetObject($sClass, $iId);
		}
		catch(CoreException $e)
		{
			// in case an ext key is 0 (which is currently acceptable)
			return $iId;
		}
		return $oObj;
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

	protected function PrepareObject(&$oTargetObj, $aRowData, &$aErrors)
	{
		$aResults = array();
		$aErrors = array();
	
		// External keys reconciliation
		//
		foreach($this->m_aExtKeys as $sAttCode => $aKeyConfig)
		{
			// Skip external keys used for the reconciliation process
			if (!array_key_exists($sAttCode, $this->m_aAttList)) continue;

			$oExtKey = MetaModel::GetAttributeDef(get_class($oTargetObj), $sAttCode);
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
				if ($oExtKey->IsNullAllowed())
				{
					$oTargetObj->Set($sAttCode, $oExtKey->GetNullValue());
				}
				else
				{
					$aErrors[$sAttCode] = "Object not found";
					$aResults[$sAttCode]= new CellStatus_Issue(null, $oTargetObj->Get($sAttCode), 'Object not found - check the spelling (no space before/after)');
				}
				break;
			case 1:
				// Do change the external key attribute
				$oForeignObj = $oExtObjects->Fetch();
				$oTargetObj->Set($sAttCode, $oForeignObj->GetKey());
				break;
			default:
				$aErrors[$sAttCode] = "Found ".$oExtObjects->Count()." matches";
				$previousValue = self::MakeSpecObject($oExtKey->GetTargetClass(), $oTargetObj->Get($sAttCode));
				$aResults[$sAttCode]= new CellStatus_Ambiguous($previousValue, $oExtObjects->Count(), $oExtObjects->ToOql());
			}

			// Report
			if (!array_key_exists($sAttCode, $aResults))
			{
				$oForeignObj = $oTargetObj->Get($sAttCode);
				if (array_key_exists($sAttCode, $oTargetObj->ListChanges()))
				{
					if ($oTargetObj->IsNew())
					{
						$aResults[$sAttCode]= new CellStatus_Void($oForeignObj);
					}
					else
					{
						$previousValue = self::MakeSpecObject($oExtKey->GetTargetClass(), $oTargetObj->GetOriginal($sAttCode));
						$aResults[$sAttCode]= new CellStatus_Modify($oForeignObj, $previousValue);
					}
				}
				else
				{
					$aResults[$sAttCode]= new CellStatus_Void($oForeignObj);
				}
			}
		}	
	
		// Set the object attributes
		//
		foreach ($this->m_aAttList as $sAttCode => $iCol)
		{
			// skip the private key, if any
			if ($sAttCode == 'id') continue;

			if (!$oTargetObj->CheckValue($sAttCode, $aRowData[$iCol]))
			{
				$aErrors[$sAttCode] = "Unexpected value";
			}
			else
			{
				$oTargetObj->Set($sAttCode, $aRowData[$iCol]);
			}
		}
	
		// Reporting on fields
		//
		$aChangedFields = $oTargetObj->ListChanges();
		foreach ($this->m_aAttList as $sAttCode => $iCol)
		{
			if ($sAttCode == 'id')
			{
				if ($aRowData[$iCol] == $oTargetObj->GetKey())
				{
					$aResults[$iCol]= new CellStatus_Void($aRowData[$iCol]);
				}
				else
				{
					$aResults[$iCol]= new CellStatus_Void($aRowData[$iCol]);
				}
				
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
		if (!$oTargetObj->CheckConsistency())
		{
			$aErrors["GLOBAL"] = "Attributes not consistent with each others";
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
			return;
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
			return;
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
	
	public function Process(CMDBChange $oChange = null)
	{
		// Note: $oChange can be null, in which case the aim is to check what would be done
	
		// Compute the results
		//
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
						$aResult[$iRow][$sAttCode] = new CellStatus_Issue(null, null, 'object not found');
					} 					
					else
					{
						$aResult[$iRow][$sAttCode] = new CellStatus_Ambiguous(null, count($aMatches), $sQuery);
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
					$this->CreateObject($aResult, $iRow, $aRowData, $oChange);
					// $aResult[$iRow]["__STATUS__"]=> set in CreateObject
					break;
				case 1:
					$oTargetObj = $oReconciliationSet->Fetch();
					$this->UpdateObject($aResult, $iRow, $oTargetObj, $aRowData, $oChange);
					// $aResult[$iRow]["__STATUS__"]=> set in UpdateObject
					break;
				default:
					// Found several matches, ambiguous
					// Render "void" results on any column
					foreach($this->m_aExtKeys as $sAttCode => $aKeyConfig)
					{
						foreach ($aKeyConfig as $sForeignAttCode => $iCol)
						{
							$aResult[$iRow][$iCol] = new CellStatus_Void($aRowData[$iCol]);
						}
						$aResult[$iRow][$sAttCode] = new CellStatus_Void('n/a');
					}
					foreach ($this->m_aAttList as $sAttCode => $iCol)
					{
						$aResult[$iRow][$iCol]= new CellStatus_Void($aRowData[$iCol]);
					}
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
					foreach ($aForeignAtts as $sForeignAttCode => $iCol)
					{
						// The foreign attribute is one of our reconciliation key
						$aResult[$iRow][$iCol] = new CellStatus_Void($aRowData[$iCol]);
					}
				}
			}
		}
		return $aResult;
	}
}


?>
