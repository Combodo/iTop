<?php
// Copyright (C) 2010-2013 Combodo SARL
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
 * Algorithm to delete object(s) and maintain data integrity
 *
 * @copyright   Copyright (C) 2010-2013 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class DeleteException extends CoreException
{
}

/**
 * Deletion plan (other objects to be deleted/modified, eventual issues, etc.) 
 *
 * @package     iTopORM
 */
class DeletionPlan
{
	//protected $m_aIssues;

	protected $m_bFoundStopper;
	protected $m_bFoundSecurityIssue;
	protected $m_bFoundManualDelete;
	protected $m_bFoundManualOperation;

	protected $m_iToDelete;
	protected $m_iToUpdate;

  	protected $m_aToDelete;
  	protected $m_aToUpdate;

	protected static $m_aModeUpdate = array(
		DEL_SILENT => array(
			DEL_SILENT => DEL_SILENT,
			DEL_AUTO => DEL_AUTO,
			DEL_MANUAL => DEL_MANUAL
		),
		DEL_MANUAL => array(
			DEL_SILENT => DEL_MANUAL,
			DEL_AUTO => DEL_AUTO,
			DEL_MANUAL => DEL_MANUAL
		),
		DEL_AUTO => array(
			DEL_SILENT => DEL_AUTO,
			DEL_AUTO => DEL_AUTO,
			DEL_MANUAL => DEL_AUTO
		)
	);

	public function __construct()
	{
		$this->m_iToDelete = 0;
		$this->m_iToUpdate = 0;

		$this->m_aToDelete = array();
		$this->m_aToUpdate = array();

		$this->m_bFoundStopper = false;
		$this->m_bFoundSecurityIssue = false;
		$this->m_bFoundManualDelete = false;
		$this->m_bFoundManualOperation = false;
	}

	public function ComputeResults()
	{
		$this->m_iToDelete = 0;
		$this->m_iToUpdate = 0;

		foreach($this->m_aToDelete as $sClass => $aToDelete)
		{
			foreach($aToDelete as $iId => $aData)
			{
				$this->m_iToDelete++;
				if (isset($aData['issue']))
				{
					$this->m_bFoundStopper = true;
					$this->m_bFoundManualOperation = true;
					if (isset($aData['issue_security']))
					{
						$this->m_bFoundSecurityIssue = true;
					}
				}
				if ($aData['mode'] == DEL_MANUAL)
				{
					$this->m_bFoundStopper = true;
					$this->m_bFoundManualDelete = true;
				}
			}
		}

		// Getting and setting time limit are not symetric:
		// www.php.net/manual/fr/function.set-time-limit.php#72305
		$iPreviousTimeLimit = ini_get('max_execution_time');
		$iLoopTimeLimit = MetaModel::GetConfig()->Get('max_execution_time_per_loop');
		foreach($this->m_aToUpdate as $sClass => $aToUpdate)
		{
			foreach($aToUpdate as $iId => $aData)
			{
				set_time_limit($iLoopTimeLimit);
				$this->m_iToUpdate++;

				$oObject = $aData['to_reset'];
				$aExtKeyLabels = array();
				foreach ($aData['attributes'] as $sRemoteExtKey => $aRemoteAttDef)
				{
					$oObject->Set($sRemoteExtKey, $aData['values'][$sRemoteExtKey]);
					$aExtKeyLabels[] = $aRemoteAttDef->GetLabel();
				}
				$this->m_aToUpdate[$sClass][$iId]['attributes_list'] = implode(', ', $aExtKeyLabels); 

				list($bRes, $aIssues, $bSecurityIssues) = $oObject->CheckToWrite();
				if (!$bRes)
				{
					$this->m_aToUpdate[$sClass][$iId]['issue'] = implode(', ', $aIssues);
					$this->m_bFoundStopper = true;

					if ($bSecurityIssues)
					{
						$this->m_aToUpdate[$sClass][$iId]['issue_security'] = true;
						$this->m_bFoundSecurityIssue = true;
					}
				}
			}
		}
		set_time_limit($iPreviousTimeLimit);
	}

	public function GetIssues()
	{
		$aIssues = array();
		foreach ($this->m_aToDelete as $sClass => $aToDelete)
		{
			foreach ($aToDelete as $iId => $aData)
			{
				if (isset($aData['issue']))
				{
					$aIssues[] = $aData['issue'];
				}
			}
		}
		foreach ($this->m_aToUpdate as $sClass => $aToUpdate)
		{
			foreach ($aToUpdate as $iId => $aData)
			{
				if (isset($aData['issue']))
				{
					$aIssues[] = $aData['issue'];
				}
			}
		}
		return $aIssues;
	}

	public function ListDeletes()
	{
		return $this->m_aToDelete;
	}

	public function ListUpdates()
	{
		return $this->m_aToUpdate;
	}

	public function GetTargetCount()
	{
		return $this->m_iToDelete + $this->m_iToUpdate;
	}

	public function FoundStopper()
	{
		return $this->m_bFoundStopper;
	}

	public function FoundSecurityIssue()
	{
		return $this->m_bFoundSecurityIssue;
	}

	public function FoundManualOperation()
	{
		return $this->m_bFoundManualOperation;
	}

	public function FoundManualDelete()
	{
		return $this->m_bFoundManualDelete;
	}

	public function FoundManualUpdate()
	{
	}

	public function AddToDelete($oObject, $iDeletionMode = null)
	{
		if (is_null($iDeletionMode))
		{
			$bRequestedExplicitely = true;
			$iDeletionMode = DEL_AUTO;
		}
		else
		{
			$bRequestedExplicitely = false;
		}

		$sClass = get_class($oObject);
		$iId = $oObject->GetKey();

		if (isset($this->m_aToUpdate[$sClass][$iId]))
		{
			unset($this->m_aToUpdate[$sClass][$iId]);
		}

		if (isset($this->m_aToDelete[$sClass][$iId]))
		{
			if ($this->m_aToDelete[$sClass][$iId]['requested_explicitely'])
			{
				// No change: let it in mode DEL_AUTO
			}
			else
			{
				$iPrevDeletionMode = $this->m_aToDelete[$sClass][$iId]['mode'];
				$iNewDeletionMode = self::$m_aModeUpdate[$iPrevDeletionMode][$iDeletionMode];
				$this->m_aToDelete[$sClass][$iId]['mode'] = $iNewDeletionMode;
	
				if ($bRequestedExplicitely)
				{
					// This object was in the root list
					$this->m_aToDelete[$sClass][$iId]['requested_explicitely'] = true;
					$this->m_aToDelete[$sClass][$iId]['mode'] = DEL_AUTO;
				}
			}
		}
		else
		{
			$this->m_aToDelete[$sClass][$iId] = array(
				'to_delete' => $oObject,
				'mode' => $iDeletionMode,
				'requested_explicitely' => $bRequestedExplicitely,
			);
		}
	}

	public function SetDeletionIssues($oObject, $aIssues, $bSecurityIssue)
	{
		if (count($aIssues) > 0)
		{
			$sClass = get_class($oObject);
			$iId = $oObject->GetKey();
			$this->m_aToDelete[$sClass][$iId]['issue'] = implode(', ', $aIssues);
			if ($bSecurityIssue)
			{
				$this->m_aToDelete[$sClass][$iId]['issue_security'] = true;
			}
		}
	}

	public function AddToUpdate($oObject, $oAttDef, $value = 0)
	{
		$sClass = get_class($oObject);
		$iId = $oObject->GetKey();
		if (isset($this->m_aToDelete[$sClass][$iId]))
		{
			// skip... it should be deleted anyhow !
		}
		else
		{
			if (!isset($this->m_aToUpdate[$sClass][$iId]))
			{
				$this->m_aToUpdate[$sClass][$iId] = array(
					'to_reset' => $oObject,
				);
			}
			$this->m_aToUpdate[$sClass][$iId]['attributes'][$oAttDef->GetCode()] = $oAttDef;
			$this->m_aToUpdate[$sClass][$iId]['values'][$oAttDef->GetCode()] = $value;
		}
	}
}
?>
