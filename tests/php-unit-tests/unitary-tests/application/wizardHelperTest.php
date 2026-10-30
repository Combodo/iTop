<?php

/**
 * Copyright (C) 2018 Dennis Lassiter
 *
 * This file is part of iTop.
 *
 *  iTop is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with iTop. If not, see <http://www.gnu.org/licenses/>
 *
 */

namespace Combodo\iTop\Test\UnitTest\Application;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use WizardHelper;
use CoreException;

/**
 * @covers WizardHelper
 */
class wizardHelperTest extends ItopDataTestCase
{

	public function testGetTargetObjectWithInvalidLinkSetDataValue()
	{
		// test case from N°9392 - [SECU] Stored XSS in subnets_list Parameter
		$sWizardHelperJSON = '{"m_sClass":"VLAN","m_oFieldsMap":{"vlan_tag":"2_vlan_tag","org_id":"2_org_id","description":"2_description","subnets_list":"2_subnets_list","physicalinterfaces_list":"2_physicalinterfaces_list"},"m_oCurrentValues":{"vlan_tag":null,"org_id":null,"description":null,"subnets_list":"[]oszzt<img src=a onerror=alert(1)>l053a","physicalinterfaces_list":"[]"},"m_aDefaultValueRequested":[],"m_aAllowedValuesRequested":[],"m_oDefaultValue":{},"m_oAllowedValues":{},"m_aStaticValues":[],"m_iFieldsCount":5,"m_sFormPrefix":"","m_sState":"","m_bReturnNotEditableFields":false,"m_sWizHelperJsVarName":null}';
		$this->expectException(CoreException::class);
		$this->expectExceptionMessageMatches('/^WizardHelper GetTargetObject: invalid data for linked set attribute/');
		$oWizardHelper = WizardHelper::FromJSON($sWizardHelperJSON);
		$oWizardHelper->GetTargetObject();
	}

}
