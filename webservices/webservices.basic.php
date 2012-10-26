<?php
// Copyright (C) 2010-2012 Combodo SARL
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
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

require_once(APPROOT.'/webservices/webservices.class.inc.php');


class BasicServices extends WebServicesBase
{
	static protected function GetWSDLFilePath()
	{
		return APPROOT.'/webservices/itop.wsdl.tpl';
	}

	/**
	 * Get the server version (TODO: get it dynamically, where ?)
	 *	 
	 * @return WebServiceResult
	 */
	static public function GetVersion()
	{
		if (ITOP_REVISION == '$WCREV$')
		{
			$sVersionString = ITOP_VERSION.' [dev]';
		}
		else
		{
			// This is a build made from SVN, let display the full information
			$sVersionString = ITOP_VERSION."-".ITOP_REVISION." ".ITOP_BUILD_DATE;
		}

		return $sVersionString;
	}

	public function CreateRequestTicket($sLogin, $sPassword, $sTitle, $sDescription, $oCallerDesc, $oCustomerDesc, $oServiceDesc, $oServiceSubcategoryDesc, $sProduct, $oWorkgroupDesc, $aSOAPImpactedCIs, $sImpact, $sUrgency)
	{
		if (!UserRights::CheckCredentials($sLogin, $sPassword))
		{
			$oRes = new WebServiceResultFailedLogin($sLogin);
			$this->LogUsage(__FUNCTION__, $oRes);

			return $oRes->ToSoapStructure();
		}
		UserRights::Login($sLogin);

		$aCallerDesc = self::SoapStructToExternalKeySearch($oCallerDesc);
		$aCustomerDesc = self::SoapStructToExternalKeySearch($oCustomerDesc);
		$aServiceDesc = self::SoapStructToExternalKeySearch($oServiceDesc);
		$aServiceSubcategoryDesc = self::SoapStructToExternalKeySearch($oServiceSubcategoryDesc);
		$aWorkgroupDesc = self::SoapStructToExternalKeySearch($oWorkgroupDesc);

		$aImpactedCIs = array();
		if (is_null($aSOAPImpactedCIs)) $aSOAPImpactedCIs = array();
		foreach($aSOAPImpactedCIs as $oImpactedCIs)
		{
			$aImpactedCIs[] = self::SoapStructToLinkCreationSpec($oImpactedCIs);
		}

		$oRes = $this->_CreateResponseTicket
		(
			'UserRequest',
			$sTitle,
			$sDescription,
			$aCallerDesc,
			$aCustomerDesc,
			$aServiceDesc,
			$aServiceSubcategoryDesc,
			$sProduct,
			$aWorkgroupDesc,
			$aImpactedCIs,
			$sImpact,
			$sUrgency
		);
		return $oRes->ToSoapStructure();
	}

	public function CreateIncidentTicket($sLogin, $sPassword, $sTitle, $sDescription, $oCallerDesc, $oCustomerDesc, $oServiceDesc, $oServiceSubcategoryDesc, $sProduct, $oWorkgroupDesc, $aSOAPImpactedCIs, $sImpact, $sUrgency)
	{
		if (!UserRights::CheckCredentials($sLogin, $sPassword))
		{
			$oRes = new WebServiceResultFailedLogin($sLogin);
			$this->LogUsage(__FUNCTION__, $oRes);

			return $oRes->ToSoapStructure();
		}
		UserRights::Login($sLogin);

		
		if (!class_exists('Incident'))
		{
			$oRes = new WebServiceResult();
			$oRes->LogError("The class Incident does not exist. Did you install the Incident Management (ITIL) module ?");
			return $oRes->ToSoapStructure();
		}
		
		$aCallerDesc = self::SoapStructToExternalKeySearch($oCallerDesc);
		$aCustomerDesc = self::SoapStructToExternalKeySearch($oCustomerDesc);
		$aServiceDesc = self::SoapStructToExternalKeySearch($oServiceDesc);
		$aServiceSubcategoryDesc = self::SoapStructToExternalKeySearch($oServiceSubcategoryDesc);
		$aWorkgroupDesc = self::SoapStructToExternalKeySearch($oWorkgroupDesc);

		$aImpactedCIs = array();
		if (is_null($aSOAPImpactedCIs)) $aSOAPImpactedCIs = array();
		foreach($aSOAPImpactedCIs as $oImpactedCIs)
		{
			$aImpactedCIs[] = self::SoapStructToLinkCreationSpec($oImpactedCIs);
		}

		$oRes = $this->_CreateResponseTicket
		(
			'Incident',
			$sTitle,
			$sDescription,
			$aCallerDesc,
			$aCustomerDesc,
			$aServiceDesc,
			$aServiceSubcategoryDesc,
			$sProduct,
			$aWorkgroupDesc,
			$aImpactedCIs,
			$sImpact,
			$sUrgency
		);
		return $oRes->ToSoapStructure();
	}
	
	/**
	 * Create an ResponseTicket (Incident or UserRequest) from an external system
	 * Some CIs might be specified (by their name/IP)
	 *	 
	 * @param string sClass The class of the ticket: Incident or UserRequest
	 * @param string sTitle
	 * @param string sDescription
	 * @param array aCallerDesc
	 * @param array aCustomerDesc
	 * @param array aServiceDesc
	 * @param array aServiceSubcategoryDesc
	 * @param string sProduct
	 * @param array aWorkgroupDesc
	 * @param array aImpactedCIs
	 * @param string sImpact
	 * @param string sUrgency
	 *
	 * @return WebServiceResult
	 */
	protected function _CreateResponseTicket($sClass, $sTitle, $sDescription, $aCallerDesc, $aCustomerDesc, $aServiceDesc, $aServiceSubcategoryDesc, $sProduct, $aWorkgroupDesc, $aImpactedCIs, $sImpact, $sUrgency)
	{

		$oRes = new WebServiceResult();

		try
		{
			$oMyChange = MetaModel::NewObject("CMDBChange");
			$oMyChange->Set("date", time());
			$oMyChange->Set("userinfo", "Administrator");
			$iChangeId = $oMyChange->DBInsertNoReload();
	
			$oNewTicket = MetaModel::NewObject($sClass);
			$this->MyObjectSetScalar('title', 'title', $sTitle, $oNewTicket, $oRes);
			$this->MyObjectSetScalar('description', 'description', $sDescription, $oNewTicket, $oRes);

			$this->MyObjectSetExternalKey('org_id', 'customer', $aCustomerDesc, $oNewTicket, $oRes);
			$this->MyObjectSetExternalKey('caller_id', 'caller', $aCallerDesc, $oNewTicket, $oRes);
	
			$this->MyObjectSetExternalKey('service_id', 'service', $aServiceDesc, $oNewTicket, $oRes);
			if (!array_key_exists('service_id', $aServiceSubcategoryDesc))
			{
				$aServiceSubcategoryDesc['service_id'] = $oNewTicket->Get('service_id');
			}
			$this->MyObjectSetExternalKey('servicesubcategory_id', 'servicesubcategory', $aServiceSubcategoryDesc, $oNewTicket, $oRes);
			if (MetaModel::IsValidAttCode($sClass, 'product'))
			{
				// 1.x data models
				$this->MyObjectSetScalar('product', 'product', $sProduct, $oNewTicket, $oRes);
			}

			if (MetaModel::IsValidAttCode($sClass, 'workgroup_id'))
			{
				// 1.x data models
				$this->MyObjectSetExternalKey('workgroup_id', 'workgroup', $aWorkgroupDesc, $oNewTicket, $oRes);
			}
			else if (MetaModel::IsValidAttCode($sClass, 'team_id'))
			{
				// 2.x data models
				$this->MyObjectSetExternalKey('team_id', 'workgroup', $aWorkgroupDesc, $oNewTicket, $oRes);
			}


			if (MetaModel::IsValidAttCode($sClass, 'ci_list'))
			{
				// 1.x data models
				$aDevicesNotFound = $this->AddLinkedObjects('ci_list', 'impacted_cis', 'FunctionalCI', $aImpactedCIs, $oNewTicket, $oRes);
			}
			else if (MetaModel::IsValidAttCode($sClass, 'functionalcis_list'))
			{
				// 2.x data models
				$aDevicesNotFound = $this->AddLinkedObjects('functionalcis_list', 'impacted_cis', 'FunctionalCI', $aImpactedCIs, $oNewTicket, $oRes);
			}
			
			if (count($aDevicesNotFound) > 0)
			{
				$this->MyObjectSetScalar('description', 'n/a', $sDescription.' - Related CIs: '.implode(', ', $aDevicesNotFound), $oNewTicket, $oRes);
			}
			else
			{
				$this->MyObjectSetScalar('description', 'n/a', $sDescription, $oNewTicket, $oRes);
			}

			$this->MyObjectSetScalar('impact', 'impact', $sImpact, $oNewTicket, $oRes);
			$this->MyObjectSetScalar('urgency', 'urgency', $sUrgency, $oNewTicket, $oRes);

			$this->MyObjectInsert($oNewTicket, 'created', $oMyChange, $oRes);
		}
		catch (CoreException $e)
		{
			$oRes->LogError($e->getMessage());
		}
		catch (Exception $e)
		{
			$oRes->LogError($e->getMessage());
		}

		$this->LogUsage(__FUNCTION__, $oRes);
		return $oRes;
	}

	/**
	 * Given an OQL, returns a set of objects (several objects could be on the same row)
	 *	 
	 * @param string sOQL
	 */	 
	public function SearchObjects($sLogin, $sPassword, $sOQL)
	{
		if (!UserRights::CheckCredentials($sLogin, $sPassword))
		{
			$oRes = new WebServiceResultFailedLogin($sLogin);
			$this->LogUsage(__FUNCTION__, $oRes);

			return $oRes->ToSoapStructure();
		}
		UserRights::Login($sLogin);

		$oRes = $this->_SearchObjects($sOQL);
		return $oRes->ToSoapStructure();
	}

	protected function _SearchObjects($sOQL)
	{
		$oRes = new WebServiceResult();
		try
		{
			$oSearch = DBObjectSearch::FromOQL($sOQL);
			$oSet = new DBObjectSet($oSearch);
			$aData = $oSet->ToArrayOfValues();
			foreach($aData as $iRow => $aRow)
			{
				$oRes->AddResultRow("row_$iRow", $aRow);
			}
		}
		catch (CoreException $e)
		{
			$oRes->LogError($e->getMessage());
		}
		catch (Exception $e)
		{
			$oRes->LogError($e->getMessage());
		}

		$this->LogUsage(__FUNCTION__, $oRes);
		return $oRes;
	}
}
?>
