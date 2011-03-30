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

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-profiles-itil/1.0.0',
	array(
		// Identification
		//
		'label' => 'Create standard ITIL profiles',
		'category' => 'create_profiles',

		// Setup
		//
		'dependencies' => array(
		),
		'mandatory' => true,
		'visible' => false,
		'installer' => 'CreateITILProfilesInstaller',

		// Components
		//
		'datamodel' => array(
			//'model.itop-profiles-itil.php',
		),
		'webservice' => array(
			//'webservices.itop-profiles-itil.php',
		),
		'dictionary' => array(
			//'en.dict.itop-profiles-itil.php',
			//'fr.dict.itop-profiles-itil.php',
			//'de.dict.itop-profiles-itil.php',
		),
		'data.struct' => array(
			//'data.struct.itop-profiles-itil.xml',
		),
		'data.sample' => array(
			//'data.sample.itop-profiles-itil.xml',
		),
		
		// Documentation
		//
		'doc.manual_setup' => '',
		'doc.more_information' => '',

		// Default settings
		//
		'settings' => array(
			//'some_setting' => 'some value',
		),
	)
);


// Module installation handler
//
class CreateITILProfilesInstaller extends ModuleInstallerAPI
{
	public static function BeforeWritingConfig(Config $oConfiguration)
	{
		//$oConfiguration->SetModuleSetting('user-rigths-profile', 'myoption', 'myvalue');
		return $oConfiguration;
	}

	public static function AfterDatabaseCreation(Config $oConfiguration)
	{
		self::ComputeITILProfiles();
		//self::ComputeBasicProfiles();
		self::DoCreateProfiles();
		UserRights::FlushPrivileges(true /* reset admin cache */);
	}
	
	protected static $m_aActions = array(
		UR_ACTION_READ => 'Read',
		UR_ACTION_MODIFY => 'Modify',
		UR_ACTION_DELETE => 'Delete',
		UR_ACTION_BULK_READ => 'Bulk Read',
		UR_ACTION_BULK_MODIFY => 'Bulk Modify',
		UR_ACTION_BULK_DELETE => 'Bulk Delete',
	);

	// Note: It is possible to specify the same class in several modules
	//
	protected static $m_aModules = array();
	protected static $m_aProfiles = array();


	protected static $m_aCacheActionGrants = null;
	protected static $m_aCacheStimulusGrants = null;
	protected static $m_aCacheProfiles = null;
	
	protected static function DoCreateActionGrant($iProfile, $iAction, $sClass, $bPermission = true)
	{
		$sAction = self::$m_aActions[$iAction];
	
		if (is_null(self::$m_aCacheActionGrants))
		{
			self::$m_aCacheActionGrants = array();
			$oFilterAll = new DBObjectSearch('URP_ActionGrant');
			$oSet = new DBObjectSet($oFilterAll);
			while ($oGrant = $oSet->Fetch())
			{
				self::$m_aCacheActionGrants[$oGrant->Get('profileid').'-'.$oGrant->Get('action').'-'.$oGrant->Get('class')] = $oGrant->GetKey();
			}
		}	

		$sCacheKey = "$iProfile-$sAction-$sClass";
		if (isset(self::$m_aCacheActionGrants[$sCacheKey]))
		{
			return self::$m_aCacheActionGrants[$sCacheKey];
		}

		$oNewObj = MetaModel::NewObject("URP_ActionGrant");
		$oNewObj->Set('profileid', $iProfile);
		$oNewObj->Set('permission', $bPermission ? 'yes' : 'no');
		$oNewObj->Set('class', $sClass);
		$oNewObj->Set('action', $sAction);
		$iId = $oNewObj->DBInsertNoReload();
		self::$m_aCacheActionGrants[$sCacheKey] = $iId;	
		return $iId;
	}
	
	protected static function DoCreateStimulusGrant($iProfile, $sStimulusCode, $sClass)
	{
		if (is_null(self::$m_aCacheStimulusGrants))
		{
			self::$m_aCacheStimulusGrants = array();
			$oFilterAll = new DBObjectSearch('URP_StimulusGrant');
			$oSet = new DBObjectSet($oFilterAll);
			while ($oGrant = $oSet->Fetch())
			{
				self::$m_aCacheStimulusGrants[$oGrant->Get('profileid').'-'.$oGrant->Get('stimulus').'-'.$oGrant->Get('class')] = $oGrant->GetKey();
			}
		}	

		$sCacheKey = "$iProfile-$sStimulusCode-$sClass";
		if (isset(self::$m_aCacheStimulusGrants[$sCacheKey]))
		{
			return self::$m_aCacheStimulusGrants[$sCacheKey];
		}
		$oNewObj = MetaModel::NewObject("URP_StimulusGrant");
		$oNewObj->Set('profileid', $iProfile);
		$oNewObj->Set('permission', 'yes');
		$oNewObj->Set('class', $sClass);
		$oNewObj->Set('stimulus', $sStimulusCode);
		$iId = $oNewObj->DBInsertNoReload();
		self::$m_aCacheStimulusGrants[$sCacheKey] = $iId;	
		return $iId;
	}
	
	protected static function DoCreateProfile($sName, $sDescription)
	{
		if (is_null(self::$m_aCacheProfiles))
		{
			self::$m_aCacheProfiles = array();
			$oFilterAll = new DBObjectSearch('URP_Profiles');
			$oSet = new DBObjectSet($oFilterAll);
			while ($oProfile = $oSet->Fetch())
			{
				self::$m_aCacheProfiles[$oProfile->Get('name')] = $oProfile->GetKey();
			}
		}	

		$sCacheKey = $sName;
		if (isset(self::$m_aCacheProfiles[$sCacheKey]))
		{
			return self::$m_aCacheProfiles[$sCacheKey];
		}
		$oNewObj = MetaModel::NewObject("URP_Profiles");
		$oNewObj->Set('name', $sName);
		$oNewObj->Set('description', $sDescription);
		$iId = $oNewObj->DBInsertNoReload();
		self::$m_aCacheProfiles[$sCacheKey] = $iId;	
		return $iId;
	}
	
	protected static function DoSetupProfile($sName, $aProfileData)
	{
		$sDescription = $aProfileData['description'];
		if (strlen(trim($aProfileData['write_modules'])) == 0)
		{
			$aWriteModules = array(); 
		}
		else
		{
			$aWriteModules = explode(',', trim($aProfileData['write_modules']));
		}
		if (strlen(trim($aProfileData['delete_modules'])) == 0)
		{
			$aDeleteModules = array(); 
		}
		else
		{
			$aDeleteModules = explode(',', trim($aProfileData['delete_modules']));
		}
		$aStimuli = $aProfileData['stimuli'];
		
		$iProfile = self::DoCreateProfile($sName, $sDescription);
	
		// Warning: BulkInsert is working because we will load one single class
		//          having one single table !
		//          the benefit is: 10 queries (1 per profile) instead of 1500
		//          which divides the overall user rights setup process by 5
		DBObject::BulkInsertStart();

		// Grant read rights for everything
		//
		foreach (MetaModel::GetClasses('bizmodel') as $sClass)
		{
			self::DoCreateActionGrant($iProfile, UR_ACTION_READ, $sClass);
			self::DoCreateActionGrant($iProfile, UR_ACTION_BULK_READ, $sClass);
		}
	
		// Grant write for given modules
		// Start by compiling the information, because some modules may overlap
		$aWriteableClasses = array();
		foreach ($aWriteModules as $sModule)
		{
			//$oPage->p('Granting write access for the module"'.$sModule.'" - '.count(self::$m_aModules[$sModule]).' classes');
			foreach (self::$m_aModules[$sModule] as $sClass)
			{
				$aWriteableClasses[$sClass] = true;
			}
		}
		foreach ($aWriteableClasses as $sClass => $foo)
		{
			if (!MetaModel::IsValidClass($sClass))
			{
				throw new CoreException("Invalid class name '$sClass'");
			}
			self::DoCreateActionGrant($iProfile, UR_ACTION_MODIFY, $sClass);
			self::DoCreateActionGrant($iProfile, UR_ACTION_BULK_MODIFY, $sClass);
		}
		
		// Grant delete for given modules
		// Start by compiling the information, because some modules may overlap
		$aDeletableClasses = array();
		foreach ($aDeleteModules as $sModule)
		{
			//$oPage->p('Granting delete access for the module"'.$sModule.'" - '.count(self::$m_aModules[$sModule]).' classes');
			foreach (self::$m_aModules[$sModule] as $sClass)
			{
				$aDeletableClasses[$sClass] = true;
			}
		}
		foreach ($aDeletableClasses as $sClass => $foo)
		{
			if (!MetaModel::IsValidClass($sClass))
			{
				throw new CoreException("Invalid class name '$sClass'");
			}
			self::DoCreateActionGrant($iProfile, UR_ACTION_DELETE, $sClass);
			// By default, do not allow bulk deletion operations for standard users
			// self::DoCreateActionGrant($iProfile, UR_ACTION_BULK_DELETE, $sClass);
		}
		
		// Grant stimuli for given classes
		foreach ($aStimuli as $sClass => $sAllowedStimuli)
		{
			if (!MetaModel::IsValidClass($sClass))
			{
				// Could be a class defined in a module that wasn't installed
				continue;
				//throw new CoreException("Invalid class name '$sClass'");
			}

			if ($sAllowedStimuli == 'any')
			{
				$aAllowedStimuli = array_keys(MetaModel::EnumStimuli($sClass));
			}
			elseif ($sAllowedStimuli == 'none')
			{
				$aAllowedStimuli = array();
			}
			else
			{
				$aAllowedStimuli = explode(',', $sAllowedStimuli);
			}
			foreach ($aAllowedStimuli as $sStimulusCode)
			{
				self::DoCreateStimulusGrant($iProfile, $sStimulusCode, $sClass);
			}
		}
		// Again: this is working only because action/stimulus grant are classes made of a single table!
		DBObject::BulkInsertFlush();
	}
	
	public static function DoCreateProfiles()
	{
		URP_Profiles::DoCreateAdminProfile();
		URP_Profiles::DoCreateUserPortalProfile();

		foreach(self::$m_aProfiles as $sName => $aProfileData)
		{
			self::DoSetupProfile($sName, $aProfileData);
		}
	}

	public static function ComputeBasicProfiles()
	{
		// In this profiling scheme, one single module represents all the classes
		//
		self::$m_aModules = array(
			'UserData' => MetaModel::GetClasses('bizmodel'),
		);

		self::$m_aProfiles = array(
			'Reader' => array(
				'description' => 'Person having a ready-only access to the data',
				'write_modules' => '',
				'delete_modules' => '',
				'stimuli' => array(
				),
			),
			'Writer' => array(
				'description' => 'Contributor to the contents (read + write access)',
				'write_modules' => 'UserData',
				'delete_modules' => 'UserData',
				'stimuli' => array(
					// any class => 'any'
				),
			),
		);
	}

	public static function ComputeITILProfiles()
	{
		// In this profiling scheme, modules are based on ITIL recommendations
		//
		self::$m_aModules = array(
			'General' => MetaModel::GetClasses('structure'),
			'Documentation' => MetaModel::GetClasses('documentation'),
			'Configuration' => MetaModel::GetClasses('configmgmt'),
			'Incident' => MetaModel::GetClasses('incidentmgmt'),
			'Problem' => MetaModel::GetClasses('problemmgmt'),
			'Change' => MetaModel::GetClasses('changemgmt'),
			'Service' => MetaModel::GetClasses('servicemgmt'),
			'Call' => MetaModel::GetClasses('requestmgmt'),
			'KnownError' => MetaModel::GetClasses('knownerrormgmt'),
		);
		
		self::$m_aProfiles = array(
			'Configuration Manager' => array(
				'description' => 'Person in charge of the documentation of the managed CIs',
				'write_modules' => 'General,Documentation,Configuration',
				'delete_modules' => 'General,Documentation,Configuration',
				'stimuli' => array(
					//'Server' => 'none',
					//'Contract' => 'none',
					//'IncidentTicket' => 'none',
					//'ChangeTicket' => 'any',
				),
			),
			'Service Desk Agent' => array(
				'description' => 'Person in charge of creating incident reports',
				'write_modules' => 'Incident,Call',
				'delete_modules' => 'Incident,Call',
				'stimuli' => array(
					'Incident' => 'ev_assign',
					'UserRequest' => 'ev_assign',
				),
			),
			'Support Agent' => array(
				'description' => 'Person analyzing and solving the current incidents',
				'write_modules' => 'Incident',
				'delete_modules' => 'Incident',
				'stimuli' => array(
					'Incident' => 'ev_assign,ev_reassign,ev_resolve,ev_close',
					'UserRequest' => 'ev_assign,ev_reassign,ev_resolve,ev_close,ev_freeze',
				),
			),
			'Problem Manager' => array(
				'description' => 'Person analyzing and solving the current problems',
				'write_modules' => 'Problem,KnownError',
				'delete_modules' => 'Problem,KnownError',
				'stimuli' => array(
					'Problem' => 'ev_assign,ev_reassign,ev_resolve,ev_close',
				),
			),

			'Change Implementor' => array(
				'description' => 'Person executing the changes',
				'write_modules' => 'Change',
				'delete_modules' => 'Change',
				'stimuli' => array(
					'NormalChange' => 'ev_plan,ev_replan,ev_implement,ev_monitor',
					'EmergencyChange' => 'ev_plan,ev_replan,ev_implement,ev_monitor',
					'RoutineChange' => 'ev_plan,ev_replan,ev_implement,ev_monitor',
				),
			),
			'Change Supervisor' => array(
				'description' => 'Person responsible for the overall change execution',
				'write_modules' => 'Change',
				'delete_modules' => 'Change',
				'stimuli' => array(
					'NormalChange' => 'ev_validate,ev_reject,ev_assign,ev_reopen,ev_finish',
					'EmergencyChange' => 'ev_assign,ev_reopen,ev_finish',
					'RoutineChange' => 'ev_assign,ev_reopen,ev_finish',
				),
			),
			'Change Approver' => array(
				'description' => 'Person who could be impacted by some changes',
				'write_modules' => 'Change',
				'delete_modules' => 'Change',
				'stimuli' => array(
					'NormalChange' => 'ev_approve,ev_notapprove',
					'EmergencyChange' => 'ev_approve,ev_notapprove',
					'RoutineChange' => 'none',
				),
			),
			'Service Manager' => array(
				'description' => 'Person responsible for the service delivered to the [internal] customer',
				'write_modules' => 'Service',
				'delete_modules' => 'Service',
				'stimuli' => array(
				),
			),
			'Document author' => array(
				'description' => 'Any person who could contribute to documentation',
				'write_modules' => 'Documentation',
				'delete_modules' => 'Documentation',
				'stimuli' => array(
				),
			),
		);
	}
}

?>
