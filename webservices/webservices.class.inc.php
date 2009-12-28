<?php

/**
 * Create Ticket web service
 * Web Service API wrapper
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @author      Denis Flaven <denisflave@free.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */

class WebServiceResult
{
	
	/**
	 * Overall status
	 *
	 * @var m_bStatus
	 */
	public $m_bStatus;

	/**
	 * Error log
	 *
	 * @var m_aErrors
	 */
	public $m_aErrors;

	/**
	 * Warning log
	 *
	 * @var m_aWarnings
	 */
	public $m_aWarnings;

	/**
	 * Information log
	 *
	 * @var m_aInfos
	 */
	public $m_aInfos;

	/**
	 * Constructor
	 *
	 * @param status $bStatus
	 */
	public function __construct()
	{
		$this->m_bStatus = true;
		$this->m_aErrors = array();
		$this->m_aWarnings = array();
		$this->m_aInfos = array();
	}

	/**
	 * Did the current processing encounter a stopper issue ?
	 *
	 * @return bool
	 */
	public function IsOk()
	{
		return $this->m_bStatus;
	}

	/**
	 * Log an error
	 *
	 * @param description $sDescription
	 */
	public function LogError($sDescription)
	{
		$this->m_aErrors[] = $sDescription;
		$this->m_bStatus = false;
	}

	/**
	 * Log a warning
	 *
	 * @param description $sDescription
	 */
	public function LogWarning($sDescription)
	{
		$this->m_aWarnings[] = $sDescription;
	}

	/**
	 * Log an error or a warning
	 *
	 * @param string $sDescription
	 * @param boolean $bIsStopper
	 */
	public function LogIssue($sDescription, $bIsStopper = true)
	{
		if ($bIsStopper) $this->LogError($sDescription);
		else             $this->LogWarning($sDescription);
	}

	/**
	 * Log operation details
	 *
	 * @param description $sDescription
	 */
	public function LogInfo($sDescription)
	{
		$this->m_aInfos[] = $sDescription;
	}
}

class WebServices
{
	/**
	 * Helper to set an external key
	 *
	 * @param string sAttCode
	 * @param array aCallerDesc
	 * @param DBObject oTargetObj
	 * @param WebServiceResult oRes
	 *
	 */
	protected function SetExternalKey($sAttCode, $aExtKeyDesc, &$oTargetObj, &$oRes)
	{
		$oExtKey = MetaModel::GetAttributeDef(get_class($oTargetObj), $sAttCode);

		$bIsMandatory = !$oExtKey->IsNullAllowed();
		if (count($aExtKeyDesc) == 0)
		{
			$oRes->LogIssue("Ext key $sAttCode: no data was given to give a value to the key", $bIsMandatory);
			return;
		}

		$sKeyClass = $oExtKey->GetTargetClass();
		$oReconFilter = new CMDBSearchFilter($sKeyClass);
		foreach ($aExtKeyDesc as $sForeignAttCode => $value)
		{
			if (!MetaModel::IsValidFilterCode($sKeyClass, $sForeignAttCode))
			{
				$sMsg = "Ext key $sAttCode: '$sForeignAttCode' is not a valid filter code for class '$sKeyClass'";
				$oRes->LogIssue($sMsg, $bIsMandatory);
			}
			// The foreign attribute is one of our reconciliation key
			$oReconFilter->AddCondition($sForeignAttCode, $value, '=');
		}
		$oExtObjects = new CMDBObjectSet($oReconFilter);
		switch($oExtObjects->Count())
		{
		case 0:
			$sMsg = "External key $sAttCode could not be found (searched: '".$oReconFilter->ToOQL()."')";
			$oRes->LogIssue($sMsg, $bIsMandatory);
			break;
		case 1:
			// Do change the external key attribute
			$oForeignObj = $oExtObjects->Fetch();
			$oTargetObj->Set($sAttCode, $oForeignObj->GetKey());

			// Report it (no need to report if the object already had this value
			if (array_key_exists($sAttCode, $oTargetObj->ListChanges()))
			{
				$oRes->LogInfo("$sAttCode has been set to ".$oForeignObj->GetKey());
			}
			break;
		default:
			$sMsg = "Found ".$oExtObjects->Count()." matches for external key $sAttCode (searched: '".$oReconFilter->ToOQL()."')";
			$oRes->LogIssue($sMsg, $bIsMandatory);
		}
	}

	/**
	 * Helper to link objects
	 *
	 * @param string sLinkAttCode
	 * @param string sLinkedClass
	 * @param array $aLinkList
	 * @param DBObject oTargetObj
	 * @param WebServiceResult oRes
	 *
	 * @return array List of objects that could not be found
	 */
	protected function AddLinkedObjects($sLinkAttCode, $sLinkedClass, $aLinkList, &$oTargetObj, &$oRes)
	{
		$oLinkAtt = MetaModel::GetAttributeDef(get_class($oTargetObj), $sLinkAttCode);
		$sLinkClass = $oLinkAtt->GetLinkedClass();
		$sExtKeyToItem = $oLinkAtt->GetExtKeyToRemote();

		$aItemsFound = array();
		$aItemsNotFound = array();
		foreach ($aLinkList as $aItemData)
		{
			$sTargetClass = $aItemData['class'];
			if (!MetaModel::IsValidClass($sTargetClass))
			{
				$oRes->LogError("Invalid class $sTargetClass for impacted item");
				continue; // skip
			}
			if (!MetaModel::IsParentClass($sLinkedClass, $sTargetClass))
			{
				$oRes->LogError("$sTargetClass is not a child class of $sLinkedClass");
				continue; // skip
			}
			$oReconFilter = new CMDBSearchFilter($sTargetClass);
			$aCIStringDesc = array();
			foreach ($aItemData['search'] as $sAttCode => $value)
			{
				if (!MetaModel::IsValidFilterCode($sTargetClass, $sAttCode))
				{
					$oRes->LogError("Invalid filter code $sAttCode for class $sTargetClass");
					continue; // skip
				}
				$aCIStringDesc[] = "$sAttCode: $value";

				// The attribute is one of our reconciliation key
				$oReconFilter->AddCondition($sAttCode, $value, '=');
			}
			if (count($aCIStringDesc) == 1)
			{
				// take the last and unique value to describe the object
				$sItemDesc = $value;
			}
			else
			{
				// describe the object by the given keys
				$sItemDesc = $sTargetClass.'('.implode('/', $aCIStringDesc).')';
			}

			$oExtObjects = new CMDBObjectSet($oReconFilter);
			switch($oExtObjects->Count())
			{
			case 0:
				$oRes->LogWarning("Object to link $sLinkedClass / $sItemDesc could not be found (searched: '".$oReconFilter->ToOQL()."')");
				$aItemsNotFound[] = $sItemDesc;
				break;
			case 1:
				$aItemsFound[] = array (
					'object' => $oExtObjects->Fetch(),
					'link_values' => @$aItemData['link_values'],
					'desc' => $sItemDesc,
				);
				break;
			default:
				$oRes->LogWarning("Found ".$oExtObjects->Count()." matches for external key $sAttCode (searched: '".$oReconFilter->ToOQL()."')");
				$aItemsNotFound[] = $sItemDesc;
			}
		}

		if (count($aItemsFound) > 0)
		{
			$aLinks = array();
			foreach($aItemsFound as $aItemData)
			{
				$oLink = MetaModel::NewObject($sLinkClass);
				$oLink->Set($sExtKeyToItem, $aItemData['object']->GetKey());
				foreach($aItemData['link_values'] as $sKey => $value)
				{
					if(!MetaModel::IsValidAttCode($sLinkClass, $sKey))
					{
						$oRes->LogWarning("Attaching item '".$aItemData['desc']."', the attribute code '$sKey' is not valid ; check the class '$sLinkClass'");
					}
					else
					{
						$oLink->Set($sKey, $value);
					}
				}
				$aLinks[] = $oLink;
			}
			$oImpactedInfraSet = DBObjectSet::FromArray($sLinkClass, $aLinks);
			$oTargetObj->Set($sLinkAttCode, $oImpactedInfraSet);
		}

		return $aItemsNotFound;
	}

	/**
	 * Create an incident ticket from a monitoring system
	 * Some CIs might be specified (by their name/IP)
	 *	 
	 * @param string sDecription
	 * @param string sInitialSituation
	 * @param array aCallerDesc
	 * @param array aCustomerDesc
	 * @param array aWorkgroupDesc
	 * @param array aImpactedCIs
	 * @param string sSeverity
	 *
	 * @return WebServiceResult
	 */
	function CreateIncidentTicket($sDescription, $sInitialSituation, $aCallerDesc, $aCustomerDesc, $aWorkgroupDesc, $aImpactedCIs, $sSeverity)
	{
		$oRes = new WebServiceResult();

		new CMDBChange();
		$oMyChange = MetaModel::NewObject("CMDBChange");
		$oMyChange->Set("date", time());
		$oMyChange->Set("userinfo", "Administrator");
		$iChangeId = $oMyChange->DBInsertNoReload();

		$oNewTicket = MetaModel::NewObject('bizIncidentTicket');
		$oNewTicket->Set('title', $sDescription);
		$oNewTicket->Set('initial_situation', $sInitialSituation);
		$oNewTicket->Set('severity', $sSeverity);

		$this->SetExternalKey('org_id', $aCustomerDesc, $oNewTicket, $oRes);
		$this->SetExternalKey('caller_id', $aCallerDesc, $oNewTicket, $oRes);
		$this->SetExternalKey('workgroup_id', $aWorkgroupDesc, $oNewTicket, $oRes);

		$aDevicesNotFound = $this->AddLinkedObjects('impacted_infra_manual', 'logInfra', $aImpactedCIs, $oNewTicket, $oRes);
		if (count($aDevicesNotFound) > 0)
		{
			$oTargetObj->Set('impact', implode(', ', $aDevicesNotFound));
		}

		if ($oRes->IsOk())
		{
			$iId = $oNewTicket->DBInsertTrackedNoReload($oMyChange);
			$oRes->LogInfo("Created ticket #$iId");
		}
		return $oRes;
	}
}
?>
