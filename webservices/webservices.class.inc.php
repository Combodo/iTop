<?php
// Copyright (C) 2010-2015 Combodo SARL
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
 * Implementation of iTop SOAP services
 *
 * @copyright   Copyright (C) 2010-2015 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


require_once(APPROOT.'/webservices/itopsoaptypes.class.inc.php');

/**
 * Generic response of iTop SOAP services
 *
 * @package     iTopORM
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
		$this->m_aResult = array();
		$this->m_aErrors = array();
		$this->m_aWarnings = array();
		$this->m_aInfos = array();
	}

	public function ToSoapStructure()
	{
		$aResults = array();
		foreach($this->m_aResult as $sLabel => $aData)
		{
			$aValues = array();
			foreach($aData as $sKey => $value)
			{
				$aValues[] = new SOAPKeyValue($sKey, $value);
			}
			$aResults[] = new SoapResultMessage($sLabel, $aValues);
		}
		$aInfos = array();
		foreach($this->m_aInfos as $sMessage)
		{
			$aInfos[] = new SoapLogMessage($sMessage);
		}
		$aWarnings = array();
		foreach($this->m_aWarnings as $sMessage)
		{
			$aWarnings[] = new SoapLogMessage($sMessage);
		}
		$aErrors = array();
		foreach($this->m_aErrors as $sMessage)
		{
			$aErrors[] = new SoapLogMessage($sMessage);
		}

		$oRet = new SOAPResult(
			$this->m_bStatus,
			$aResults,
			new SOAPResultLog($aErrors),
			new SOAPResultLog($aWarnings),
			new SOAPResultLog($aInfos)
		);

		return $oRet;
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
	 * Add result details - object reference
	 *
	 * @param string sLabel
	 * @param object oObject
	 */
	public function AddResultObject($sLabel, $oObject)
	{
		$oAppContext = new ApplicationContext();
		$this->m_aResult[$sLabel] = array(
			'id' => $oObject->GetKey(),
			'name' => $oObject->GetRawName(),
			'url' => $oAppContext->MakeObjectUrl(get_class($oObject), $oObject->GetKey(), null, false), // Raw URL without HTML tags
		);
	}

	/**
	 * Add result details - a table row
	 *
	 * @param string sLabel
	 * @param object oObject
	 */
	public function AddResultRow($sLabel, $aRow)
	{
		$this->m_aResult[$sLabel] = $aRow;
	}

	/**
	 * Log an error
	 *
	 * @param string sDescription
	 */
	public function LogError($sDescription)
	{
		$this->m_aErrors[] = $sDescription;
		// Note: SOAP do transform false into null
		$this->m_bStatus = 0;
	}

	/**
	 * Log a warning
	 *
	 * @param string sDescription
	 */
	public function LogWarning($sDescription)
	{
		$this->m_aWarnings[] = $sDescription;
	}

	/**
	 * Log an error or a warning
	 *
	 * @param string sDescription
	 * @param boolean bIsStopper
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

	protected static function LogToText($aLog)
	{
		return implode("\n", $aLog);
	}

	public function GetInfoAsText()
	{
		return self::LogToText($this->m_aInfos);
	}

	public function GetWarningsAsText()
	{
		return self::LogToText($this->m_aWarnings);
	}

	public function GetErrorsAsText()
	{
		return self::LogToText($this->m_aErrors);
	}

	public function GetReturnedDataAsText()
	{
		$sRet = '';
		foreach ($this->m_aResult as $sKey => $value)
		{
			$sRet .= "===== $sKey =====\n";
			$sRet .= print_r($value, true);
		}
		return $sRet;
	}
}


/**
 * Generic response of iTop SOAP services - failed login
 *
 * @package     iTopORM
 */
class WebServiceResultFailedLogin extends WebServiceResult
{
	public function __construct($sLogin)
	{
		parent::__construct();
		$this->LogError("Wrong credentials: '$sLogin'");
	}
}

/**
 * Implementation of the Services
 *
 * @package     iTopORM
 */
abstract class WebServicesBase
{
	static public function GetWSDLContents($sServiceCategory = '')
	{
		if ($sServiceCategory == '')
		{
			$sServiceCategory = 'BasicServices';
		}
		$sWsdlFilePath = call_user_func(array($sServiceCategory, 'GetWSDLFilePath'));
		return file_get_contents($sWsdlFilePath);
	}

	/**
	 * Helper to log a service delivery
	 *
	 * @param string sVerb
	 * @param array aArgs
	 * @param WebServiceResult oRes
	 *
	 */
	protected function LogUsage($sVerb, $oRes)
	{
		if (!MetaModel::IsLogEnabledWebService()) return;

		$oLog = new EventWebService();
		if ($oRes->IsOk())
		{
			$oLog->Set('message', $sVerb.' was successfully invoked');
		}
		else
		{
			$oLog->Set('message', $sVerb.' returned errors');
		}
		$oLog->Set('userinfo', UserRights::GetUser());
		$oLog->Set('verb', $sVerb);
		$oLog->Set('result', $oRes->IsOk());
		$this->TrimAndSetValue($oLog, 'log_info', (string)$oRes->GetInfoAsText());
		$this->TrimAndSetValue($oLog, 'log_warning', (string)$oRes->GetWarningsAsText());
		$this->TrimAndSetValue($oLog, 'log_error', (string)$oRes->GetErrorsAsText());
		$this->TrimAndSetValue($oLog, 'data', (string)$oRes->GetReturnedDataAsText());
		$oLog->DBInsertNoReload();
	}
	
	protected function TrimAndSetValue($oLog, $sAttCode, $sValue)
	{
		$oAttDef = MetaModel::GetAttributeDef(get_class($oLog), $sAttCode);
		if (is_object($oAttDef))
		{
			$iMaxSize = $oAttDef->GetMaxSize();
			if ($iMaxSize && (strlen($sValue) > $iMaxSize))
			{
				$sValue = substr($sValue, 0, $iMaxSize);
			}
			$oLog->Set($sAttCode, $sValue);
		}
	}

	/**
	 * Helper to set a scalar attribute
	 *
	 * @param string sAttCode
	 * @param scalar value
	 * @param DBObject oTargetObj
	 * @param WebServiceResult oRes
	 *
	 */
	protected function MyObjectSetScalar($sAttCode, $sParamName, $value, &$oTargetObj, &$oRes)
	{
		$res = $oTargetObj->CheckValue($sAttCode, $value);
		if ($res === true)
		{
			$oTargetObj->Set($sAttCode, $value);
		}
		else
		{
			// $res contains the error description
			$oRes->LogError("Unexpected value for parameter $sParamName: $res");
		}
	}

	/**
	 * Helper to set an external key
	 *
	 * @param string sAttCode
	 * @param array aExtKeyDesc
	 * @param DBObject oTargetObj
	 * @param WebServiceResult oRes
	 *
	 */
	protected function MyObjectSetExternalKey($sAttCode, $sParamName, $aExtKeyDesc, &$oTargetObj, &$oRes)
	{
		$oExtKey = MetaModel::GetAttributeDef(get_class($oTargetObj), $sAttCode);

		$bIsMandatory = !$oExtKey->IsNullAllowed();

		if (is_null($aExtKeyDesc))
		{
			if ($bIsMandatory)
			{
				$oRes->LogError("Parameter $sParamName: found null for a mandatory key");
			}
			else
			{
				// skip silently
				return;
			}
		}

		if (count($aExtKeyDesc) == 0)
		{
			$oRes->LogIssue("Parameter $sParamName: no search condition has been specified", $bIsMandatory);
			return;
		}

		$sKeyClass = $oExtKey->GetTargetClass();
		$oReconFilter = new DBObjectSearch($sKeyClass);
		foreach ($aExtKeyDesc as $sForeignAttCode => $value)
		{
			if (!MetaModel::IsValidFilterCode($sKeyClass, $sForeignAttCode))
			{
				$aCodes = array_keys(MetaModel::GetClassFilterDefs($sKeyClass));
				$sMsg = "Parameter $sParamName: '$sForeignAttCode' is not a valid filter code for class '$sKeyClass', expecting a value in {".implode(', ', $aCodes)."}";
				$oRes->LogIssue($sMsg, $bIsMandatory);
			}
			// The foreign attribute is one of our reconciliation key
			$oReconFilter->AddCondition($sForeignAttCode, $value, '=');
		}
		$oExtObjects = new CMDBObjectSet($oReconFilter);
		switch($oExtObjects->Count())
		{
		case 0:
			$sMsg = "Parameter $sParamName: no match (searched: '".$oReconFilter->ToOQL(true)."')";
			$oRes->LogIssue($sMsg, $bIsMandatory);
			break;
		case 1:
			// Do change the external key attribute
			$oForeignObj = $oExtObjects->Fetch();
			$oTargetObj->Set($sAttCode, $oForeignObj->GetKey());

			// Report it (no need to report if the object already had this value
			if (array_key_exists($sAttCode, $oTargetObj->ListChanges()))
			{
				$oRes->LogInfo("Parameter $sParamName: found match ".get_class($oForeignObj)."::".$oForeignObj->GetKey()." '".$oForeignObj->GetName()."'");
			}
			break;
		default:
			$sMsg = "Parameter $sParamName: Found ".$oExtObjects->Count()." matches (searched: '".$oReconFilter->ToOQL(true)."')";
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
	protected function AddLinkedObjects($sLinkAttCode, $sParamName, $sLinkedClass, $aLinkList, &$oTargetObj, &$oRes)
	{
		$oLinkAtt = MetaModel::GetAttributeDef(get_class($oTargetObj), $sLinkAttCode);
		$sLinkClass = $oLinkAtt->GetLinkedClass();
		$sExtKeyToItem = $oLinkAtt->GetExtKeyToRemote();

		$aItemsFound = array();
		$aItemsNotFound = array();
		
		if (is_null($aLinkList))
		{
			return $aItemsNotFound;
		}

		foreach ($aLinkList as $aItemData)
		{
			if (!array_key_exists('class', $aItemData))
			{
				$oRes->LogWarning("Parameter $sParamName: missing 'class' specification");
				continue; // skip
			}
			$sTargetClass = $aItemData['class'];
			if (!MetaModel::IsValidClass($sTargetClass))
			{
				$oRes->LogError("Parameter $sParamName: invalid class '$sTargetClass'");
				continue; // skip
			}
			if (!MetaModel::IsParentClass($sLinkedClass, $sTargetClass))
			{
				$oRes->LogError("Parameter $sParamName: '$sTargetClass' is not a child class of '$sLinkedClass'");
				continue; // skip
			}
			$oReconFilter = new DBObjectSearch($sTargetClass);
			$aCIStringDesc = array();
			foreach ($aItemData['search'] as $sAttCode => $value)
			{
				if (!MetaModel::IsValidFilterCode($sTargetClass, $sAttCode))
				{
					$aCodes = array_keys(MetaModel::GetClassFilterDefs($sTargetClass));
					$oRes->LogError("Parameter $sParamName: '$sAttCode' is not a valid filter code for class '$sTargetClass', expecting a value in {".implode(', ', $aCodes)."}");
					continue 2; // skip the entire item
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
				$oRes->LogWarning("Parameter $sParamName: object to link $sLinkedClass / $sItemDesc could not be found (searched: '".$oReconFilter->ToOQL(true)."')");
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
				$oRes->LogWarning("Parameter $sParamName: Found ".$oExtObjects->Count()." matches for item '$sItemDesc' (searched: '".$oReconFilter->ToOQL(true)."')");
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
						$oRes->LogWarning("Parameter $sParamName: Attaching item '".$aItemData['desc']."', the attribute code '$sKey' is not valid ; check the class '$sLinkClass'");
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

	protected function MyObjectInsert($oTargetObj, $sResultLabel, $oChange, &$oRes)
	{
		if ($oRes->IsOk())
		{
			list($bRes, $aIssues) = $oTargetObj->CheckToWrite();
			if ($bRes)
			{
				$iId = $oTargetObj->DBInsertTrackedNoReload($oChange);
				$oRes->LogInfo("Created object ".get_class($oTargetObj)."::$iId");
				$oRes->AddResultObject($sResultLabel, $oTargetObj);
			}
			else
			{
				$oRes->LogError("The ticket could not be created due to forbidden values (or inconsistent values)");
				foreach($aIssues as $iIssue => $sIssue)
				{
					$oRes->LogError("Issue #$iIssue: $sIssue");
				}
			}
		}
	}


	static protected function SoapStructToExternalKeySearch($oExternalKeySearch)
	{
		if (is_null($oExternalKeySearch)) return null;
		if ($oExternalKeySearch->IsVoid()) return null;

		$aRes = array();
		foreach($oExternalKeySearch->conditions as $oSearchCondition)
		{
			$aRes[$oSearchCondition->attcode] = $oSearchCondition->value;
		}
		return $aRes;
	}

	static protected function SoapStructToLinkCreationSpec(SoapLinkCreationSpec $oLinkCreationSpec)
	{
		$aRes = array
		(
			'class' => $oLinkCreationSpec->class,
			'search' => array(),
			'link_values' => array(),
		);

		foreach($oLinkCreationSpec->conditions as $oSearchCondition)
		{
			$aRes['search'][$oSearchCondition->attcode] = $oSearchCondition->value;
		}

		foreach($oLinkCreationSpec->attributes as $oAttributeValue)
		{
			$aRes['link_values'][$oAttributeValue->attcode] = $oAttributeValue->value;
		}

		return $aRes;
	}

	static protected function SoapStructToAssociativeArray($aArrayOfAssocArray)
	{
		if (is_null($aArrayOfAssocArray)) return array();

		$aRes = array();
		foreach($aArrayOfAssocArray as $aAssocArray)
		{
			$aRow = array();
			foreach ($aAssocArray as $oKeyValuePair)
			{
				$aRow[$oKeyValuePair->key] = $oKeyValuePair->value;
			}
			$aRes[] = $aRow;		
		}
		return $aRes;
	}
}
?>
