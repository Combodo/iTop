<?php
// Copyright (c) 2023 Combodo SARL
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
//

/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 02/10/2017
 * Time: 13:58
 */

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use DBObject;
use MetaModel;


/**
 * @group specificOrgInSampleData
 */
class XMLDataLoaderTest extends ItopDataTestCase
{
	const CREATE_TEST_ORG = false;

	public function testDataLoader()
	{
		$sXML = 
<<<XML
<?xml version="1.0" encoding="UTF-8"?>
<Set>
	<Organization alias="Organization" id="71">
		<name>ZuperTest</name>
		<code></code>
		<status>active</status>
		<parent_id>0</parent_id>
		<parent_id_friendlyname></parent_id_friendlyname>
		<parent_name></parent_name>
		<parent_id_obsolescence_flag>no</parent_id_obsolescence_flag>
		<overview></overview>
		<deliverymodel_id>0</deliverymodel_id>
		<deliverymodel_id_friendlyname></deliverymodel_id_friendlyname>
		<deliverymodel_name></deliverymodel_name>
		<friendlyname>ZuperTest</friendlyname>
		<obsolescence_flag>no</obsolescence_flag>
		<obsolescence_date></obsolescence_date>
	</Organization>
	<Location alias="Location" id="4">
		<name>Zanzibar</name>
		<status>active</status>
		<org_id>71</org_id>
		<org_id_friendlyname>ZuperTest</org_id_friendlyname>
		<org_name>ZuperTest</org_name>
		<org_id_obsolescence_flag>no</org_id_obsolescence_flag>
		<address></address>
		<postal_code></postal_code>
		<city></city>
		<country></country>
		<friendlyname>Zanzibar</friendlyname>
		<obsolescence_flag>no</obsolescence_flag>
		<obsolescence_date></obsolescence_date>
	</Location>
	<Person alias="Person" id="60">
		<name>Zmillpatt</name>
		<status>active</status>
		<org_id>71</org_id>
		<org_id_friendlyname>ZuperTest</org_id_friendlyname>
		<org_name>ZuperTest</org_name>
		<org_id_obsolescence_flag>no</org_id_obsolescence_flag>
		<email></email>
		<phone></phone>
		<notify>yes</notify>
		<function></function>
		<picture></picture>
		<first_name>Zacharie</first_name>
		<employee_number></employee_number>
		<mobile_phone></mobile_phone>
		<location_id>4</location_id>
		<location_id_friendlyname>Zanzibar</location_id_friendlyname>
		<location_name>Zanzibar</location_name>
		<location_id_obsolescence_flag>no</location_id_obsolescence_flag>
		<manager_id>0</manager_id>
		<manager_id_friendlyname></manager_id_friendlyname>
		<manager_name></manager_name>
		<manager_id_obsolescence_flag>no</manager_id_obsolescence_flag>
		<finalclass>Person</finalclass>
		<friendlyname>Zacharie Zmillpatt</friendlyname>
		<obsolescence_flag>no</obsolescence_flag>
		<obsolescence_date></obsolescence_date>
	</Person>
</Set>
XML;
		$this->CreateFromXMLString($sXML);

		$oPerson = MetaModel::GetObjectByName('Person', 'Zacharie Zmillpatt');

		$this->assertEquals('Zanzibar', $oPerson->Get('location_id_friendlyname'));
		$this->assertEquals('ZuperTest', $oPerson->Get('org_id_friendlyname'));
	}
}