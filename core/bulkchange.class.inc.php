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

	public function GetValue()
	{
		return $this->m_proposedValue; 
	}

	abstract public function GetDescription();
}


class CellChangeSpec_Void extends CellChangeSpec
{
	public function GetDescription()
	{
		return $this->GetValue();
	}
}

class CellChangeSpec_Unchanged extends CellChangeSpec
{
	public function GetDescription()
	{
		return $this->GetValue()." (unchanged)";
	}
}

class CellChangeSpec_Init extends CellChangeSpec
{
	public function GetDescription()
	{
		return $this->GetValue();
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

	public function GetDescription()
	{
		return $this->GetValue()." (previous: ".$this->m_previousValue.")";
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

	public function GetDescription()
	{
		if (is_null($this->m_proposedValue))
		{
			return 'Could not be changed - reason: '.$this->m_sReason;
		}
		return 'Could not be changed to "'.$this->GetValue().'" - reason: '.$this->m_sReason.' (previous: '.$this->m_previousValue.')';
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
	protected $m_iObjKey;

	public function __construct($iObjKey = null)
	{
		$this->m_iObjKey = $iObjKey;
	}

	public function GetDescription()
	{
		if (is_null($this->m_iObjKey))
		{
			return "Create";
		}
		else
		{
			return 'Created ('.$this->m_iObjKey.')';
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

	public function GetDescription()
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

	public function GetDescription()
	{
		return 'Skipped - reason:'.$this->m_sReason;
	}
}


/**
 * BulkChange
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     $itopversion$
 */
class BulkChange
{
	protected $m_sClass;
	protected $m_aData;
	// Note: hereafter, iCol maybe actually be any acceptable key (string)
	// #@# todo: rename the variables to sColIndex
	protected $m_aAttList; // attcode => iCol
	protected $m_aReconcilKeys;// iCol => attcode
	protected $m_aExtKeys;	// aExtKeys[sExtKeyAttCode][sExtReconcKeyAttCode] = iCol;

	public function __construct($sClass, $aData, $aAttList, $aReconcilKeys, $aExtKeys)
	{
		$this->m_sClass = $sClass;
		$this->m_aData = $aData;
		$this->m_aAttList = $aAttList;
		$this->m_aReconcilKeys = $aReconcilKeys;
		$this->m_aExtKeys = $aExtKeys;
	}

	protected function PrepareObject(&$oTargetObj, $aRowData, &$aErrors)
	{
		$aResults = array();
		$aErrors = array();
	
		// External keys reconciliation
		//
		foreach($this->m_aExtKeys as $sAttCode => $aKeyConfig)
		{
			$oExtKey = MetaModel::GetAttributeDef(get_class($oTargetObj), $sAttCode);
			$oReconFilter = new CMDBSearchFilter($oExtKey->GetTargetClass());
			foreach ($aKeyConfig as $sForeignAttCode => $iCol)
			{
				// The foreign attribute is one of our reconciliation key
				$sFieldId = MakeExtFieldSelectValue($sAttCode, $sForeignAttCode);
				$oReconFilter->AddCondition($sForeignAttCode, $aRowData[$iCol], '=');
				$aResults["col$iCol"] = new CellChangeSpec_Void($aRowData[$iCol]);
			}
			$oExtObjects = new CMDBObjectSet($oReconFilter);
			switch($oExtObjects->Count())
			{
			case 0:
				$aErrors[$sAttCode] = "Object not found";
				$aResults[$sAttCode]= new CellChangeSpec_Issue(null, $oTargetObj->Get($sAttCode), 'Object not found');
				break;
			case 1:
				// Do change the external key attribute
				$oForeignObj = $oExtObjects->Fetch();
				$oTargetObj->Set($sAttCode, $oForeignObj->GetKey());
	
				// Report it
				if (array_key_exists($sAttCode, $oTargetObj->ListChanges()))
				{
					if ($oTargetObj->IsNew())
					{
						$aResults[$sAttCode]= new CellChangeSpec_Init($oForeignObj->GetKey(), $oTargetObj->Get($sAttCode), $oTargetObj->GetOriginal($sAttCode));
					}
					else
					{
						$aResults[$sAttCode]= new CellChangeSpec_Modify($oForeignObj->GetKey(), $oTargetObj->Get($sAttCode), $oTargetObj->GetOriginal($sAttCode));
					}
				}
				else
				{
					$aResults[$sAttCode]= new CellChangeSpec_Unchanged($oTargetObj->Get($sAttCode));
				}
				break;
			default:
				$aErrors[$sAttCode] = "Found ".$oExtObjects->Count()." matches";
				$aResults[$sAttCode]= new CellChangeSpec_Issue(null, $oTargetObj->Get($sAttCode), "Found ".$oExtObjects->Count()." matches");
			}
		}	
	
		// Set the object attributes
		//
		foreach ($this->m_aAttList as $sAttCode => $iCol)
		{
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
			if (isset($aErrors[$sAttCode]))
			{
				$aResults["col$iCol"]= new CellChangeSpec_Issue($aRowData[$iCol], $oTargetObj->Get($sAttCode), $aErrors[$sAttCode]);
			}
			elseif (array_key_exists($sAttCode, $aChangedFields))
			{
				$originalValue = $oTargetObj->GetOriginal($sAttCode);
				if ($oTargetObj->IsNew())
				{
					$aResults["col$iCol"]= new CellChangeSpec_Init($aRowData[$iCol], $oTargetObj->Get($sAttCode), $originalValue);
				}
				else
				{
					$aResults["col$iCol"]= new CellChangeSpec_Modify($aRowData[$iCol], $oTargetObj->Get($sAttCode), $originalValue);
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
		// Could be said once for all rows !!!
		foreach(MetaModel::ListAttributeDefs($this->m_sClass) as $sAttCode=>$oAtt)
		{
			if (!$oAtt->IsExternalKey()) continue;
		}
	
		// Optionaly record the results
		//
		if ($oChange)
		{
			$newID = $oTargetObj->DBInsertTrackedNoReload($oChange);
			$aResult[$iRow]["__STATUS__"] = new RowStatus_NewObj($newID);
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
			foreach($this->m_aReconcilKeys as $sAttCode)
			{
				$iCol = $this->m_aAttList[$sAttCode];
				$oReconciliationFilter->AddCondition($sAttCode, $aRowData[$iCol], '=');
			}
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
				$aResult[$iRow]["__RECONCILIATION__"] = "Found a match ".$oTargetObj->GetKey();
				// $aResult[$iRow]["__STATUS__"]=> set in UpdateObject
				break;
			default:
				foreach ($this->m_aAttList as $sAttCode => $iCol)
				{
					$aResult[$iRow]["col$iCol"]= $aRowData[$iCol];
				}
				$aResult[$iRow]["__RECONCILIATION__"] = "Found ".$oReconciliationSet->Count()." matches";
				$aResult[$iRow]["__STATUS__"]= new RowStatus_Issue("ambiguous reconciliation");
			}
	
			// Whatever happened, do report the reconciliation values
			foreach($this->m_aReconcilKeys as $sAttCode)
			{
				$iCol = $this->m_aAttList[$sAttCode];
				$aResult[$iRow]["col$iCol"] = new CellChangeSpec_Void($aRowData[$iCol]);
			}
		}
		return $aResult;
	}
}


?>
