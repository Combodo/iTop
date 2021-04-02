<?php
/**
 * Copyright (C) 2013-2021 Combodo SARL
 * This file is part of iTop.
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * You should have received a copy of the GNU Affero General Public License
 */

namespace Combodo\iTop\Test\UnitTest\Synchro;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use DBObjectSearch;
use DBObjectSet;
use DBSearch;
use Exception;
use MetaModel;
use SynchroDataSource;
use UserLocal;
use utils;


class DataSynchroTest extends ItopDataTestCase
{
	protected const AUTH_USER = 'DataSynchroTest';
	protected const AUTH_PWD = 'sdf234(-fgh;,dfgDFG';
	const USE_TRANSACTION = false;
	private $oOrg1;
	private $oOrg2;

	protected function setUp()
	{
		parent::setUp();

		// Create the login account if it does not exist yet
		$oSearch = DBSearch::FromOQL('SELECT User WHERE login = "'.static::AUTH_USER.'"');
		$oSet = new DBObjectSet($oSearch);
		if ($oSet->Count() == 0)
		{
			$oProfileSearch = DBSearch::FromOQL('SELECT URP_Profiles WHERE name LIKE "administrator"');
			$oProfileSet = new DBObjectSet($oProfileSearch);
			$oAdminProfile = $oProfileSet->fetch();

			$oUser = MetaModel::NewObject('UserLocal',  array(
				'login' => static::AUTH_USER,
				'password' => static::AUTH_PWD,
				'expiration' => UserLocal::EXPIRE_NEVER,
			));
			$oProfiles = $oUser->Get('profile_list');
			$oProfiles->AddItem(MetaModel::NewObject('URP_UserProfile', array(
				'profileid' => $oAdminProfile->GetKey()
			)));
			$oUser->Set('profile_list', $oProfiles);
			$oUser->DBInsertNoReload();
		}

		/** @var \Organization $oOrganisation */
		$sUniqueId1 = microtime();
		$oOrganisation = $this->createObject('Organization', array(
			'name' => 'datasynchro_org1_' . $sUniqueId1,
			'code' => $sUniqueId1
		));
		$this->oOrg1 = $oOrganisation;
		$sUniqueId2 = microtime();
		$oOrganisation2 = $this->createObject('Organization', array(
			'name' => 'datasynchro_org2_' . $sUniqueId2,
			'code' => $sUniqueId2
		));
		$this->oOrg2 = $oOrganisation2;
	}

	protected function ExecSynchroImport($aParams, $bSynchroByHttp)
	{
		if (!$bSynchroByHttp) {
			return utils::ExecITopScript('synchro/synchro_import.php', $aParams, static::AUTH_USER, static::AUTH_PWD);
		}

		$aParams['auth_user'] = static::AUTH_USER;
		$aParams['auth_pwd'] = static::AUTH_PWD;

		//$aParams['output'] = 'details';
		$aParams['csvdata'] = file_get_contents($aParams['csvfile']);


		$sUrl = \MetaModel::GetConfig()->Get('app_root_url').'/synchro/synchro_import.php?login_mode=form';
		$sResult = utils::DoPostRequest($sUrl, $aParams, null, $aResponseHeaders, []);
		// Read the status code from the last line
		$aLines = explode("\n", trim(strip_tags($sResult)));
		//$sLastLine = array_pop($aLines);

		return array(0, $aLines);
	}

	/**
	 * Run a series of data synchronization through the REST API
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \CoreWarning
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	public function RunDataSynchroTest($aUserLoginUsecase)
	{
		$sDescription = $aUserLoginUsecase['desc'];
		$sTargetClass = $aUserLoginUsecase['target_class'];
		$aSourceProperties = $aUserLoginUsecase['source_properties'];
		$aSourceData = $aUserLoginUsecase['source_data'];
		$aTargetData = $aUserLoginUsecase['target_data'];
		$aAttributes =$aUserLoginUsecase['attributes'];
		$bSynchroByHttp = $aUserLoginUsecase['bSynchroByHttp'];

		$sClass = $sTargetClass;

		$aTargetAttributes = array_shift($aTargetData);
		$aSourceAttributes = array_shift($aSourceData);

		if (count($aSourceData) + 1 != count($aTargetData))
		{
			throw new Exception("Target data must contain exactly ".(count($aSourceData) + 1)." items, found ".count($aTargetData));
		}

		// Create the data source
		//
		$oDataSource = new SynchroDataSource();
		$oDataSource->Set('name', 'Test data sync '.time());
		$oDataSource->Set('description', 'unit test - created automatically');
		$oDataSource->Set('status', 'production');
		$oDataSource->Set('user_id', 0);
		$oDataSource->Set('scope_class', $sClass);
		foreach ($aSourceProperties as $sProperty => $value)
		{
			$oDataSource->Set($sProperty, $value);
		}
		$iDataSourceId = $oDataSource->DBInsert();

		$oAttributeSet = $oDataSource->Get('attribute_list');
		while ($oAttribute = $oAttributeSet->Fetch())
		{
			if (array_key_exists($oAttribute->Get('attcode'), $aAttributes))
			{
				$aAttribInfo = $aAttributes[$oAttribute->Get('attcode')];
				if (array_key_exists('reconciliation_attcode', $aAttribInfo))
				{
					$oAttribute->Set('reconciliation_attcode', $aAttribInfo['reconciliation_attcode']);
				}
				$oAttribute->Set('update', $aAttribInfo['do_update']);
				$oAttribute->Set('reconcile', $aAttribInfo['do_reconcile']);
			}
			else
			{
				$oAttribute->Set('update', false);
				$oAttribute->Set('reconcile', false);
			}
			$oAttribute->DBUpdate();
		}

		// Prepare list of prefixes -> make sure objects are unique with regard to the reconciliation scheme
		$aPrefixes = array(); // attcode => prefix
		foreach($aSourceAttributes as $iDummy => $sAttCode)
		{
			$aPrefixes[$sAttCode] = ''; // init with something
		}
		foreach($aAttributes as $sAttCode => $aAttribInfo)
		{
			if (isset($aAttribInfo['automatic_prefix']) && $aAttribInfo['automatic_prefix'])
			{
				$aPrefixes[$sAttCode] = 'TEST_'.$iDataSourceId.'_';
			}
		}

		// List existing objects (to be ignored in the analysis)
		//
		$oAllObjects = new DBObjectSet(new DBObjectSearch($sClass));
		$aExisting = $oAllObjects->ToArray(true);
		$sExistingIds = implode(', ', array_keys($aExisting));

		// Create the initial object list
		//
		$aInitialTarget = $aTargetData[0];
		foreach($aInitialTarget as $aObjFields)
		{
			$oNewTarget = MetaModel::NewObject($sClass);
			foreach($aTargetAttributes as $iAtt => $sAttCode)
			{
				$oNewTarget->Set($sAttCode, $aPrefixes[$sAttCode].$aObjFields[$iAtt]);
			}
			$oNewTarget->DBInsertNoReload();
		}

		//add sleep to make sure expected objects will be found
		usleep(10000);
		foreach($aTargetData as $iRow => $aExpectedObjects)
		{
			// Check the status (while ignoring existing objects)
			//
			if (empty($sExistingIds))
			{
				$oObjects = new DBObjectSet(DBObjectSearch::FromOQL("SELECT $sClass"));
			}
			else
			{
				$oObjects = new DBObjectSet(DBObjectSearch::FromOQL("SELECT $sClass WHERE id NOT IN($sExistingIds)"));
			}
			$aFound = $oObjects->ToArray();
			$aErrors_Unexpected = array();
			foreach($aFound as $iObj => $oObj)
			{
				// Is this object in the expected objects list
				$bFoundMatch = false;
				foreach($aExpectedObjects as $iExp => $aValues)
				{
					$bDoesMatch = true;
					foreach($aTargetAttributes as $iCol => $sAttCode)
					{
						if ($oObj->Get($sAttCode) != $aPrefixes[$sAttCode].$aValues[$iCol])
						{
							$bDoesMatch = false;
							break;
						}
					}
					if ($bDoesMatch)
					{
						$bFoundMatch = true;
						unset($aExpectedObjects[$iExp]);
						break;
					}
				}
				if (!$bFoundMatch)
				{
					$aObjDesc = array();
					foreach($aTargetAttributes as $iCol => $sAttCode)
					{
						$aObjDesc[$sAttCode] = $oObj->Get($sAttCode);
					}
					$aErrors_Unexpected[get_class($oObj).'::'.$oObj->GetKey()] = $aObjDesc;
				}
			}

			// Display the current status
			//
			$aErrors = array();
			if (count($aErrors_Unexpected) > 0) {
				$aErrors[] = "Unexpected objects found in iTop DB after step $iRow (starting at 0):\n".print_r($aErrors_Unexpected, true);
			}
			if (count($aExpectedObjects) > 0) {
				$aErrors[] = "Expected objects NOT found in iTop DB after step $iRow (starting at 0)\n".print_r($aExpectedObjects, true);
			}
			if (count($aErrors) > 0) {
				$sAdditionalInfo = (isset($sResultsViewable)) ? $sResultsViewable : "";
				static::fail(implode("\n", $aErrors) . "\n $sAdditionalInfo");
			} else {
				static::assertTrue(true);
			}

			// If not on the final row, run a data exchange sequence
			//
			if (array_key_exists($iRow, $aSourceData))
			{
				$aToBeLoaded = $aSourceData[$iRow];

				// First line
				$sCsvData = implode(';', $aSourceAttributes)."\n";

				$sTextQualifier = '"';

				foreach($aToBeLoaded as $aDataRow)
				{
					$aFinalData = array();
					foreach($aDataRow as $iCol => $value)
					{
						$sAttCode = $aSourceAttributes[$iCol];
						$sRawValue = $aPrefixes[$sAttCode].$value;

						$sFrom = array("\r\n", $sTextQualifier);
						$sTo = array("\n", $sTextQualifier.$sTextQualifier);
						$sCSVValue = $sTextQualifier.str_replace($sFrom, $sTo, (string)$sRawValue).$sTextQualifier;

						$aFinalData[] = $sCSVValue;
					}
					$sCsvData .= implode(';', $aFinalData)."\n";
				}
				$sCSVTmpFile = tempnam(sys_get_temp_dir(), "CSV");
				file_put_contents($sCSVTmpFile, $sCsvData);

				$aParams = array(
					'csvfile' => $sCSVTmpFile,
					'data_source_id' => $iDataSourceId,
					'separator' => ';',
					'simulate' => 0,
					'output' => 'details',
				);
				list($iRetCode, $aOutputLines) = static::ExecSynchroImport($aParams, $bSynchroByHttp);

				unlink($sCSVTmpFile);

				// Report the load results
				//
				if (strlen($sCsvData) > 5000)
				{
					$sCsvDataViewable = 'INPUT TOO LONG TO BE DISPLAYED ('.strlen($sCsvData).")\n".substr($sCsvData, 0, 500)."\n... TO BE CONTINUED";
				}
				else
				{
					$sCsvDataViewable = $sCsvData;
				}
				echo "Input Data:\n";
				echo $sCsvDataViewable;
				echo "\n";

				$sResultsViewable = '|   '.implode("\n|   ", $aOutputLines);

				echo "Results:\n";
				echo $sResultsViewable;
				echo "\n";

				if ($iRetCode != 0)
				{
					static::fail("Execution of synchro_import failing with code '$iRetCode', see error.log for more details");
				}

				if (stripos($sResultsViewable, 'exception') !== false)
				{
					self::fail('Encountered an Exception during the last import/synchro');
				}

				$aKeys = ["creation", "update", "deletion"];
				foreach ($aKeys as $sKey){
					$this->assertContains("$sKey errors: 0", $sResultsViewable, "step $iRow : below res should contain '$sKey errors: 0': " . $sResultsViewable);
				}

				//N°3805 : potential javascript returned like
				/*
				        Please wait...
	var aListJsFiles = [];
                $(document).ready(function () {
                            setTimeout(function () {
                                    }, 50);
                    });
				 */
				$sLastExpectedLine = "#Replica disappeared, no action taken: 0";
				$aSplittedRes = explode($sLastExpectedLine, $sResultsViewable);
				$this->assertNotFalse($aSplittedRes);
				if (count($aSplittedRes)>1){
					$sPotentialIssuesWithWebApplication = $aSplittedRes[1];
					$this->assertEquals("", $sPotentialIssuesWithWebApplication, 'when failed it means data synchro result is polluted with some web application stuff like html or js');
				}
			}
		}
	}

	private function GetNominalUsecaseData(){
		return array(
			'desc' => 'Load user logins',
			'target_class' => 'UserLocal',
			'source_properties' => array(
				'full_load_periodicity' => 3600, // should be ignored in this case
				'reconciliation_policy' => 'use_attributes',
				'action_on_zero' => 'create',
				'action_on_one' => 'update',
				'action_on_multiple' => 'error',
				'delete_policy' => 'delete',
				'delete_policy_update' => '',
				'delete_policy_retention' => 0,
			),
			'source_data' => array(
				array('primary_key', 'login', 'password', 'profile_list'),
				array(
					array('user_A', 'login_A', 'password_A', 'profileid:10;reason:he/she is managing services'),
				),
			),
			'target_data' => array(
				array('login'), //columns
				array(
					// Initial state
				),
				array(
					array('login_A'), //expected values
				),
			),
			'attributes' => array(
				'login' => array(
					'do_reconcile' => true,
					'do_update' => true,
					'automatic_prefix' => true, // unique id (for unit testing)
				),
				'password' => array(
					'do_reconcile' => false,
					'do_update' => true,
				),
				'profile_list' => array(
					'do_reconcile' => false,
					'do_update' => true,
				),
			),
			'bSynchroByHttp' => false
		);
	}

	public function testDataSynchroByCli(){
		$this->RunDataSynchroTest($this->GetNominalUsecaseData());
	}

	public function testDataSynchroByHttp(){
		$aUserLoginUsecase = $this->GetNominalUsecaseData();
		$aUserLoginUsecase['bSynchroByHttp'] = true;
		$this->RunDataSynchroTest($aUserLoginUsecase);
	}

	/*public function testWithDeleteOption(){
		$aUserLoginUsecase = array(
			'desc' => 'Simple scenario with delete option (and extkey given as org/name)',
			'target_class' => 'ApplicationSolution',
			'source_properties' => array(
				'full_load_periodicity' => 1,
				'reconciliation_policy' => 'use_attributes',
				'action_on_zero' => 'create',
				'action_on_one' => 'update',
				'action_on_multiple' => 'error',
				'delete_policy' => 'delete',
				'delete_policy_update' => '',
				'delete_policy_retention' => 0,
			),
			'source_data' => array(
				array('primary_key', 'org_id', 'name', 'status'),
				array(
					array('obj_A', '<NULL>', 'obj_A', 'active'), // org_id unchanged
					array('obj_B', '_DUMMY_', 'obj_B', 'active'), // error, '_DUMMY_' unknown
					array('obj_C', $this->oOrg1->Get('code'), 'obj_C', 'active'),
					array('obj_D', $this->oOrg1->Get('code'), 'obj_D', 'active'),
					array('obj_E', $this->oOrg1->Get('code'), 'obj_E', 'active'),
				),
				array(
					array('obj_D', $this->oOrg1->Get('code'), 'obj_D', 'inactive'),
					array('obj_E', $this->oOrg1->Get('code'), 'obj_E', '<NULL>'),
				),
			),
			'target_data' => array(
				array('org_id', 'name', 'status'),
				array(
					// Initial state
					array($this->oOrg2->GetKey(), 'obj_A', 'active'),
					array($this->oOrg2->GetKey(), 'obj_B', 'active'),
				),
				array(
					array($this->oOrg2->GetKey(), 'obj_A', 'active'),
					array($this->oOrg2->GetKey(), 'obj_B', 'active'),
					array($this->oOrg1->GetKey(), 'obj_C', 'active'),
					array($this->oOrg1->GetKey(), 'obj_D', 'active'),
					array($this->oOrg1->GetKey(), 'obj_E', 'active'),
				),
				array(
					array($this->oOrg2->GetKey(), 'obj_A', 'active'),
					array($this->oOrg2->GetKey(), 'obj_B', 'active'),
					array($this->oOrg1->GetKey(), 'obj_D', 'inactive'),
					array($this->oOrg1->GetKey(), 'obj_E', 'active'),
				),
			),
			'attributes' => array(
				'org_id' => array(
					'do_reconcile' => false,
					'do_update' => true,
					'reconciliation_attcode' => 'code',
				),
				'name' => array(
					'do_reconcile' => true,
					'do_update' => true,
					'automatic_prefix' => true, // unique id
				),
				'status' => array(
					'do_reconcile' => false,
					'do_update' => true,
				),
			),
			'bSynchroByHttp' => false
		);
		$this->RunDataSynchroTest($aUserLoginUsecase);
	}*/

	/*public function testUpdateThenDeleteWithRetention(){
		$aUserLoginUsecase = array(
			'desc' => 'Update then delete with retention (to complete with manual testing) and reconciliation on org/name',
			'target_class' => 'ApplicationSolution',
			'source_properties' => array(
				'full_load_periodicity' => 0,
				'reconciliation_policy' => 'use_attributes',
				'action_on_zero' => 'create',
				'action_on_one' => 'update',
				'action_on_multiple' => 'error',
				'delete_policy' => 'update_then_delete',
				'delete_policy_update' => 'status:inactive',
				'delete_policy_retention' => 15,
			),
			'source_data' => array(
				array('primary_key', 'org_id', 'name', 'status'),
				array(
					array('obj_A', $this->oOrg1->Get('name'), 'obj_A', 'active'),
				),
				array(
				),
			),
			'target_data' => array(
				array('org_id', 'name', 'status'),
				array(
					// Initial state
				),
				array(
					array($this->oOrg1->GetKey(), 'obj_A', 'active'),
				),
				array(
					array($this->oOrg1->GetKey(), 'obj_A', 'inactive'),
					// deleted !
				),
			),
			'attributes' => array(
				'org_id' => array(
					'do_reconcile' => true,
					'do_update' => true,
					'reconciliation_attcode' => 'name',
				),
				'name' => array(
					'do_reconcile' => true,
					'do_update' => true,
					'automatic_prefix' => true, // unique id
				),
				'status' => array(
					'do_reconcile' => false,
					'do_update' => true,
				),
			),
			'bSynchroByHttp' => false
		);
		$this->RunDataSynchroTest($aUserLoginUsecase);
	}*/


	/*public function testLoadingApplicationSolution(){
		$aUserLoginUsecase= array(
			'desc' => 'Simple scenario loading a few ApplicationSolution',
			'target_class' => 'ApplicationSolution',
			'source_properties' => array(
				'full_load_periodicity' => 0,
				'reconciliation_policy' => 'use_attributes',
				'action_on_zero' => 'create',
				'action_on_one' => 'update',
				'action_on_multiple' => 'error',
				'delete_policy' => 'update',
				'delete_policy_update' => 'status:inactive',
				'delete_policy_retention' => 0,

			),
			'source_data' => array(
				array('primary_key', 'org_id', 'name', 'status'),
				array(
					array('obj_A', 2, 'obj_A', 'active'),
					array('obj_B', 2, 'obj_B', 'inactive'),
					array('obj_C', 2, 'obj_C', 'inactive'),
				),
				array(
					array('obj_A', 2, 'obj_A', 'active'),
					array('obj_B', 2, 'obj_B', 'inactive'),
					array('obj_C', 2, 'obj_C', 'inactive'),
				),
				array(
					array('obj_A', 2, 'obj_A', 'active'),
					array('obj_C', 2, 'obj_C', 'inactive'),
					array('obj_D', 2, 'obj_D', 'inactive'),
				),
				array(
					array('obj_C', 2, 'obj_C', 'active'),
				),
				array(
					array('obj_C', 2, 'obj_C', 'active'),
				),
			),
			'target_data' => array(
				array('org_id', 'name', 'status'),
				array(
					// Initial state
					array(2, 'obj_A', 'inactive'),
					array(2, 'obj_B', 'active'),
					array(2, 'obj_B', 'inactive'),
				),
				array(
					array(2, 'obj_A', 'active'),
					array(2, 'obj_B', 'active'),
					array(2, 'obj_B', 'inactive'),
					array(2, 'obj_C', 'inactive'),
				),
				array(
					array(2, 'obj_A', 'active'),
					array(2, 'obj_B', 'active'),
					array(2, 'obj_B', 'inactive'),
					array(2, 'obj_C', 'inactive'),
				),
				array(
					array(2, 'obj_A', 'active'),
					array(2, 'obj_B', 'active'),
					array(2, 'obj_B', 'inactive'),
					array(2, 'obj_C', 'inactive'),
					array(2, 'obj_D', 'inactive'),
				),
				array(
					array(2, 'obj_A', 'inactive'),
					array(2, 'obj_B', 'active'),
					array(2, 'obj_B', 'inactive'),
					array(2, 'obj_C', 'active'),
					array(2, 'obj_D', 'inactive'),
				),
				array(
					array(2, 'obj_A', 'inactive'),
					array(2, 'obj_B', 'active'),
					array(2, 'obj_B', 'inactive'),
					array(2, 'obj_C', 'active'),
					array(2, 'obj_D', 'inactive'),
				),
			),
			'attributes' => array(
				'org_id' => array(
					'do_reconcile' => false,
					'do_update' => true,
				),
				'name' => array(
					'do_reconcile' => true,
					'do_update' => true,
					'automatic_prefix' => true, // unique id
				),
				'status' => array(
					'do_reconcile' => false,
					'do_update' => true,
				),
			),
			'bSynchroByHttp' => false
		);
		$this->RunDataSynchroTest($aUserLoginUsecase);
	}*/
}
