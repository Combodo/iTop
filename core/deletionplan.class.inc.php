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
 * Class dbObject: the root of persistent classes
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
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

		foreach($this->m_aToUpdate as $sClass => $aToUpdate)
		{
			foreach($aToUpdate as $iId => $aData)
			{
				$this->m_iToUpdate++;

            $oObject = $aData['to_reset'];
				$aExtKeyLabels = array();
				foreach ($aData['attributes'] as $sRemoteExtKey => $aRemoteAttDef)
				{
					$oObject->Set($sRemoteExtKey, 0);
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

	public function AddToUpdate($oObject, $oAttDef)
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
		}
	}
}
?>
