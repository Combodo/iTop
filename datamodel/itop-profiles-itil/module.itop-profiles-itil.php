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

	public static function AfterDatabaseCreation(Config $oConfiguration, $sPreviousVersion, $sCurrentVersion)
	{
		self::ComputeITILProfiles();
		//self::ComputeBasicProfiles();
		$bFirstInstall = empty($sPreviousVersion);
		self::DoCreateProfiles($bFirstInstall);
		UserRights::FlushPrivileges(true /* reset admin cache */);
	}

	// Note: It is possible to specify the same class in several modules
	//
	protected static $m_aModules = array();
	protected static $m_aProfiles = array();
	
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
		
		$iProfile = URP_Profiles::DoCreateProfile($sName, $sDescription);
	
		// Warning: BulkInsert is working because we will load one single class
		//          having one single table !
		//          the benefit is: 10 queries (1 per profile) instead of 1500
		//          which divides the overall user rights setup process by 5
		DBObject::BulkInsertStart();

		// Grant read rights for everything
		//
		foreach (MetaModel::GetClasses('bizmodel') as $sClass)
		{
			URP_Profiles::DoCreateActionGrant($iProfile, UR_ACTION_READ, $sClass);
			URP_Profiles::DoCreateActionGrant($iProfile, UR_ACTION_BULK_READ, $sClass);
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
			URP_Profiles::DoCreateActionGrant($iProfile, UR_ACTION_MODIFY, $sClass);
			URP_Profiles::DoCreateActionGrant($iProfile, UR_ACTION_BULK_MODIFY, $sClass);
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
			URP_Profiles::DoCreateActionGrant($iProfile, UR_ACTION_DELETE, $sClass);
			// By default, do not allow bulk deletion operations for standard users
			// URP_Profiles::DoCreateActionGrant($iProfile, UR_ACTION_BULK_DELETE, $sClass);
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
				URP_Profiles::DoCreateStimulusGrant($iProfile, $sStimulusCode, $sClass);
			}
		}
		// Again: this is working only because action/stimulus grant are classes made of a single table!
		DBObject::BulkInsertFlush();
	}
	
	/*
	* Create the built-in User Portal profile with its reserved name
	*/	
	public static function DoCreateUserPortalProfile()
	{
		// Do not attempt to create this profile if the module 'User Request Management' is not installed
		// Note: ideally, the creation of this profile should be moved to the 'User Request Management' module
		if (!MetaModel::IsValidClass('UserRequest')) return;

		$iNewId =  URP_Profiles::DoCreateProfile(PORTAL_PROFILE_NAME, 'Has the rights to access to the user portal. People having this profile will not be allowed to access the standard application, they will be automatically redirected to the user portal.', true /* reserved name */);
		
		// Grant read rights for everything
		//
		foreach (MetaModel::GetClasses('bizmodel') as $sClass)
		{
			URP_Profiles::DoCreateActionGrant($iNewId, UR_ACTION_READ, $sClass);
			URP_Profiles::DoCreateActionGrant($iNewId, UR_ACTION_BULK_READ, $sClass);
		}
		// Can create UserRequests and attach Documents to it
		self::SafeCreateActionGrant($iNewId, UR_ACTION_MODIFY, 'UserRequest');
		self::SafeCreateActionGrant($iNewId, UR_ACTION_MODIFY, 'lnkTicketToDoc');
		self::SafeCreateActionGrant($iNewId, UR_ACTION_DELETE, 'lnkTicketToDoc');
		self::SafeCreateActionGrant($iNewId, UR_ACTION_MODIFY, 'FileDoc');
		// Can close user requests
		self::SafeCreateStimulusGrant($iNewId, 'ev_close', 'UserRequest');
	}
	protected static function SafeCreateActionGrant($iProfile, $iAction, $sClass, $bPermission = true)
	{
		if (MetaModel::IsValidClass($sClass)) URP_Profiles::DoCreateActionGrant($iProfile, $iAction, $sClass, $bPermission);
	}

	protected static function SafeCreateStimulusGrant($iProfile, $sStimulusCode, $sClass)
	{
		if (MetaModel::IsValidClass($sClass)) URP_Profiles::DoCreateStimulusGrant($iProfile, $sStimulusCode, $sClass);
	}

	public static function DoCreateProfiles($bFirstInstall = true)
	{
		URP_Profiles::DoCreateAdminProfile(); // Will be created only if it does not exist
		self::DoCreateUserPortalProfile(); // Will be created only if it does not exist and updated otherwise

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
			'LnkTickets' => MetaModel::GetClasses('lnkticket'),
			'LnkIncidents' => MetaModel::GetClasses('lnkincident'),
			'LnkServices' => MetaModel::GetClasses('lnkservice'),
			'LnkKnownErrors' => MetaModel::GetClasses('lnkknownerror'),
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
				'delete_modules' => 'LnkTickets,LnkIncidents',
				'stimuli' => array(
					'Incident' => 'ev_assign',
					'UserRequest' => 'ev_assign',
				),
			),
			'Support Agent' => array(
				'description' => 'Person analyzing and solving the current incidents',
				'write_modules' => 'Incident,Call',
				'delete_modules' => 'LnkTickets,LnkIncidents',
				'stimuli' => array(
					'Incident' => 'ev_assign,ev_reassign,ev_resolve,ev_close',
					'UserRequest' => 'ev_assign,ev_reassign,ev_resolve,ev_close,ev_freeze',
				),
			),
			'Problem Manager' => array(
				'description' => 'Person analyzing and solving the current problems',
				'write_modules' => 'Problem,KnownError',
				'delete_modules' => 'LnkTickets,LnkKnownErrors',
				'stimuli' => array(
					'Problem' => 'ev_assign,ev_reassign,ev_resolve,ev_close',
				),
			),

			'Change Implementor' => array(
				'description' => 'Person executing the changes',
				'write_modules' => 'Change',
				'delete_modules' => 'LnkTickets',
				'stimuli' => array(
					'NormalChange' => 'ev_plan,ev_replan,ev_implement,ev_monitor',
					'EmergencyChange' => 'ev_plan,ev_replan,ev_implement,ev_monitor',
					'RoutineChange' => 'ev_plan,ev_replan,ev_implement,ev_monitor',
				),
			),
			'Change Supervisor' => array(
				'description' => 'Person responsible for the overall change execution',
				'write_modules' => 'Change',
				'delete_modules' => 'LnkTickets',
				'stimuli' => array(
					'NormalChange' => 'ev_validate,ev_reject,ev_assign,ev_reopen,ev_finish',
					'EmergencyChange' => 'ev_assign,ev_reopen,ev_finish',
					'RoutineChange' => 'ev_assign,ev_reopen,ev_finish',
				),
			),
			'Change Approver' => array(
				'description' => 'Person who could be impacted by some changes',
				'write_modules' => 'Change',
				'delete_modules' => 'LnkTickets',
				'stimuli' => array(
					'NormalChange' => 'ev_approve,ev_notapprove',
					'EmergencyChange' => 'ev_approve,ev_notapprove',
					'RoutineChange' => 'none',
				),
			),
			'Service Manager' => array(
				'description' => 'Person responsible for the service delivered to the [internal] customer',
				'write_modules' => 'Service',
				'delete_modules' => 'LnkServices',
				'stimuli' => array(
				),
			),
			'Document author' => array(
				'description' => 'Any person who could contribute to documentation',
				'write_modules' => 'Documentation',
				'delete_modules' => 'Documentation,LnkTickets',
				'stimuli' => array(
				),
			),
		);
	}
}

?>
