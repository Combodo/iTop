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

	public function __construct($proposedValue)
	{
		$this->m_proposedValue = $proposedValue;
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

	public function GetValue($bHtml = false)
	{
		if ($bHtml)
		{
			return self::ValueAsHtml($this->m_proposedValue);
		}
		else
		{
			return $this->m_proposedValue;
		}
	}

	abstract public function GetDescription($bHtml = false);
}


class CellChangeSpec_Void extends CellChangeSpec
{
	public function GetDescription($bHtml = false)
	{
		return $this->GetValue($bHtml);
	}
}

class CellChangeSpec_Unchanged extends CellChangeSpec
{
	public function GetDescription($bHtml = false)
	{
		return $this->GetValue($bHtml)." (unchanged)";
	}
}

class CellChangeSpec_Init extends CellChangeSpec
{
	public function GetDescription($bHtml = false)
	{
		return $this->GetValue($bHtml);
	}
}

class CellChangeSpec_Modify extends CellChangeSpec
{
	protected $m_previousValue;

	public function __construct($proposedValue, $previousValue)
	{
		$this->m_previousValue = $previousValue;
		parent::__construct($proposedValue);
	}

	public function GetDescription($bHtml = false)
	{
		return $this->GetValue($bHtml)." (previous: ".self::ValueAsHtml($this->m_previousValue).")";
	}
}

class CellChangeSpec_Issue extends CellChangeSpec_Modify
{
	protected $m_sReason;

	public function __construct($proposedValue, $previousValue, $sReason)
	{
		$this->m_sReason = $sReason;
		parent::__construct($proposedValue, $previousValue);
	}

	public function GetDescription($bHtml = false)
	{
		if (is_null($this->m_proposedValue))
		{
			return 'Could not be changed - reason: '.$this->m_sReason;
		}
		return 'Could not be changed to "'.$this->GetValue($bHtml).'" - reason: '.$this->m_sReason.' (previous: '.$this->m_previousValue.')';
	}
}

class CellChangeSpec_Ambiguous extends CellChangeSpec_Modify
{
	protected $m_iCount;
	protected $m_sOql;

	public function __construct($previousValue, $iCount, $sOql)
	{
		$this->m_iCount = $iCount;
		$this->m_sQuery = $sOql;
		parent::__construct(null, $previousValue);
	}

	public function GetDescription($bHtml = false)
	{
		if ($bHtml)
		{
			$sCount = '<a href="'.$this->m_sQuery.'">'.$this->m_iCount.'</a>';
		}
		else
		{
			$sCount = $this->m_iCount;
		}
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

	abstract public function GetDescription($bHtml = false);
}

class RowStatus_NoChange extends RowStatus
{
	public function GetDescription($bHtml = false)
	{
		return "unchanged";
	}
}

class RowStatus_NewObj extends RowStatus
{
	protected $m_iObjKey;

	public function __construct($sClass = '', $iObjKey = null)
	{
		$this->m_iObjKey = $iObjKey;
		$this->m_sClass = $sClass;
	}

	public function GetDescription($bHtml = false)
	{
		if (is_null($this->m_iObjKey))
		{
			return "Create";
		}
		else
		{
			if (!empty($this->m_sClass))
			{
				$oObj = MetaModel::GetObject($this->m_sClass, $this->m_iObjKey);
				return 'Created '.$oObj->GetHyperLink();
			}
			else
			{
				return 'Created (id: '.$this->m_iObjKey.')';
			}
		}	
	}
}

class RowStatus_Modify extends RowStatus
{
	protected $m_iChanged;

	public function __construct($iChanged)
	{
		$this->m_iChanged = $iChanged;
	}

	public function GetDescription($bHtml = false)
	{
		return "update ".$this->m_iChanged." cols";
	}
}

class RowStatus_Issue extends RowStatus
{
	protected $m_sReason;

	public function __construct($sReason)
	{
		$this->m_sReason = $sReason;
	}

	public function GetDescription($bHtml = false)
	{
		return 'Skipped - reason:'.$this->m_sReason;
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
			$aResults["col$iCol"] = new CellChangeSpec_Void($aRowData[$iCol]);
		}

		$oExtObjects = new CMDBObjectSet($oReconFilter);
		$aKeys = $oExtObjects->ToArray();
		return $aKeys;
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
				$aResults["col$iCol"] = new CellChangeSpec_Void($aRowData[$iCol]);
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
					$aResults[$sAttCode]= new CellChangeSpec_Issue(null, $oTargetObj->Get($sAttCode), 'Object not found - check the spelling (no space before/after)');
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
				$aResults[$sAttCode]= new CellChangeSpec_Ambiguous($previousValue, $oExtObjects->Count(), $oExtObjects->ToOql());
			}

			// Report
			if (!array_key_exists($sAttCode, $aResults))
			{
				$oForeignObj = $oTargetObj->Get($sAttCode);
				if (array_key_exists($sAttCode, $oTargetObj->ListChanges()))
				{
					if ($oTargetObj->IsNew())
					{
						$aResults[$sAttCode]= new CellChangeSpec_Init($oForeignObj);
					}
					else
					{
						$previousValue = self::MakeSpecObject($oExtKey->GetTargetClass(), $oTargetObj->GetOriginal($sAttCode));
						$aResults[$sAttCode]= new CellChangeSpec_Modify($oForeignObj, $previousValue);
					}
				}
				else
				{
					$aResults[$sAttCode]= new CellChangeSpec_Unchanged($oForeignObj);
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
					$aResults["col$iCol"]= new CellChangeSpec_Void($aRowData[$iCol]);
				}
				else
				{
					$aResults["col$iCol"]= new CellChangeSpec_Init($aRowData[$iCol]);
				}
				
			}
			if (isset($aErrors[$sAttCode]))
			{
				$aResults["col$iCol"]= new CellChangeSpec_Issue($oTargetObj->Get($sAttCode), $oTargetObj->GetOriginal($sAttCode), $aErrors[$sAttCode]);
			}
			elseif (array_key_exists($sAttCode, $aChangedFields))
			{
				if ($oTargetObj->IsNew())
				{
					$aResults["col$iCol"]= new CellChangeSpec_Init($oTargetObj->Get($sAttCode));
				}
				else
				{
					$aResults["col$iCol"]= new CellChangeSpec_Modify($oTargetObj->Get($sAttCode), $oTargetObj->GetOriginal($sAttCode));
				}
			}
			else
			{
				// By default... nothing happens
				$aResults["col$iCol"]= new CellChangeSpec_Void($aRowData[$iCol]);
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
		}
		else
		{
			$aResult[$iRow]["__STATUS__"] = new RowStatus_NewObj();
		}
	
	}
	
	protected function UpdateObject(&$aResult, $iRow, $oTargetObj, $aRowData, CMDBChange $oChange = null)
	{
		$aResult[$iRow] = $this->PrepareObject($oTargetObj, $aRowData, $aErrors);

		// Reporting
		//
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
					$aMatches = $this->ResolveExternalKey($aRowData, $sAttCode, $aResult[$iRow]);

					if (count($aMatches) == 1)
					{
						$oRemoteObj = reset($aMatches); // first item
						$valuecondition = $oRemoteObj->GetKey();
						$aResult[$iRow][$sAttCode] = new CellChangeSpec_Void($oRemoteObj->GetKey());
					} 					
					elseif (count($aMatches) == 0)
					{
						$aResult[$iRow]["__RECONCILIATION__"] = "Could not find a match for external key '$sAttCode'";
						$aResult[$iRow][$sAttCode] = new CellChangeSpec_Issue(null, null, 'object not found');
					} 					
					else
					{
						$aResult[$iRow]["__RECONCILIATION__"] = "Ambiguous external key '$sAttCode'";
						$aResult[$iRow][$sAttCode] = new CellChangeSpec_Issue(null, null, 'found '.count($aMatches).' matches');
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
					$aResult[$iRow]["__RECONCILIATION__"] = "Object not found";
					break;
				case 1:
					$oTargetObj = $oReconciliationSet->Fetch();
					$this->UpdateObject($aResult, $iRow, $oTargetObj, $aRowData, $oChange);
					$aResult[$iRow]["__RECONCILIATION__"] = "Found a match ".$oTargetObj->GetHyperLink();
					// $aResult[$iRow]["__STATUS__"]=> set in UpdateObject
					break;
				default:
					// Found several matches, ambiguous
					// Render "void" results on any column
					foreach($this->m_aExtKeys as $sAttCode => $aKeyConfig)
					{
						foreach ($aKeyConfig as $sForeignAttCode => $iCol)
						{
							$aResult[$iRow]["col$iCol"] = new CellChangeSpec_Void($aRowData[$iCol]);
						}
						$aResult[$iRow][$sAttCode] = new CellChangeSpec_Void('n/a');
					}
					foreach ($this->m_aAttList as $sAttCode => $iCol)
					{
						$aResult[$iRow]["col$iCol"]= new CellChangeSpec_Void($aRowData[$iCol]);
					}
					$aResult[$iRow]["__RECONCILIATION__"] = "Found ".$oReconciliationSet->Count()." matches";
					$aResult[$iRow]["__STATUS__"]= new RowStatus_Issue("ambiguous reconciliation");
				}
			}
	
			// Whatever happened, do report the reconciliation values
			foreach($this->m_aAttList as $iCol)
			{
				if (!array_key_exists($iCol, $aResult[$iRow]))
				{
					$aResult[$iRow]["col$iCol"] = new CellChangeSpec_Void($aRowData[$iCol]);
				}
			}
			foreach($this->m_aExtKeys as $sAttCode => $aForeignAtts)
			{
				if (!array_key_exists($sAttCode, $aResult[$iRow]))
				{
					$aResult[$iRow][$sAttCode] = new CellChangeSpec_Void('n/a');
					foreach ($aForeignAtts as $sForeignAttCode => $iCol)
					{
						// The foreign attribute is one of our reconciliation key
						$aResult[$iRow]["col$iCol"] = new CellChangeSpec_Void($aRowData[$iCol]);
					}
				}
			}
		}
		return $aResult;
	}
}


?>
