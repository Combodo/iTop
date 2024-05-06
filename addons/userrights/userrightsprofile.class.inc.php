<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Application\WebPage\WebPage;

define('ADMIN_PROFILE_NAME', 'Administrator');
define('PORTAL_PROFILE_NAME', 'Portal user');

class UserRightsBaseClassGUI extends cmdbAbstractObject
{
	// Whenever something changes, reload the privileges

	protected function AfterInsert()
	{
		UserRights::FlushPrivileges();
	}

	protected function AfterUpdate()
	{
		UserRights::FlushPrivileges();
	}

	protected function AfterDelete()
	{
		UserRights::FlushPrivileges();
	}
}


class URP_Profiles extends UserRightsBaseClassGUI
{
	public static function Init()
	{
		$aParams = array
		(
			"category"                   => "addon/userrights,grant_by_profile,filter",
			"key_type"                   => "autoincrement",
			"name_attcode"               => "name",
			"complementary_name_attcode" => array('description'),
			"state_attcode"              => "",
			"reconc_keys"                => array(),
			"db_table"                   => "priv_urp_profiles",
			"db_key_field"               => "id",
			"db_finalclass_field"        => "",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("user_list", array("linked_class"=>"URP_UserProfile", "ext_key_to_me"=>"profileid", "ext_key_to_remote"=>"userid", "allowed_values"=>null, "count_min"=>1, "count_max"=>0, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('name', 'description', 'user_list')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('description')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('name','description')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('default_search', array ('name','description'));
	}

	protected static $m_aCacheProfiles = null;

	public static function DoCreateProfile($sName, $sDescription)
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

	function GetGrantAsHtml($oUserRights, $sClass, $sAction)
	{
		$bGrant = $oUserRights->GetProfileActionGrant($this->GetKey(), $sClass, $sAction);
		if (is_null($bGrant))
		{
			return '<span style="background-color: #ffdddd;">'.Dict::S('UI:UserManagement:ActionAllowed:No').'</span>';
		}
		elseif ($bGrant)
		{
			return '<span style="background-color: #ddffdd;">'.Dict::S('UI:UserManagement:ActionAllowed:Yes').'</span>';
		}
		else
		{
			return '<span style="background-color: #ffdddd;">'.Dict::S('UI:UserManagement:ActionAllowed:No').'</span>';
		}
	}

	function DoShowGrantSumary($oPage)
	{
		if ($this->GetRawName() == "Administrator")
		{
			// Looks dirty, but ok that's THE ONE
			$oPage->p(Dict::S('UI:UserManagement:AdminProfile+'));
			return;
		}

		// Note: for sure, we assume that the instance is derived from UserRightsProfile
		$oUserRights = UserRights::GetModuleInstance();

		$aDisplayData = array();
		foreach (MetaModel::GetClasses('bizmodel,grant_by_profile') as $sClass)
		{
			$aStimuli = array();
			foreach (MetaModel::EnumStimuli($sClass) as $sStimulusCode => $oStimulus)
			{
				$bGrant = $oUserRights->GetClassStimulusGrant($this->GetKey(), $sClass, $sStimulusCode);
				if ($bGrant === true)
				{
					$aStimuli[] = '<span title="'.$sStimulusCode.': '.utils::EscapeHtml($oStimulus->GetDescription()).'">'.utils::EscapeHtml($oStimulus->GetLabel()).'</span>';
				}
			}
			$sStimuli = implode(', ', $aStimuli);

			$aDisplayData[] = array(
				'class' => MetaModel::GetName($sClass),
				'read' => $this->GetGrantAsHtml($oUserRights, $sClass, 'r'),
				'bulkread' => $this->GetGrantAsHtml($oUserRights, $sClass, 'br'),
				'write' => $this->GetGrantAsHtml($oUserRights, $sClass, 'w'),
				'bulkwrite' => $this->GetGrantAsHtml($oUserRights, $sClass, 'bw'),
				'delete' => $this->GetGrantAsHtml($oUserRights, $sClass, 'd'),
				'bulkdelete' => $this->GetGrantAsHtml($oUserRights, $sClass, 'bd'),
				'stimuli' => $sStimuli,
			);
		}

		$aDisplayConfig = array();
		$aDisplayConfig['class'] = array('label' => Dict::S('UI:UserManagement:Class'), 'description' => Dict::S('UI:UserManagement:Class+'));
		$aDisplayConfig['read'] = array('label' => Dict::S('UI:UserManagement:Action:Read'), 'description' => Dict::S('UI:UserManagement:Action:Read+'));
		$aDisplayConfig['bulkread'] = array('label' => Dict::S('UI:UserManagement:Action:BulkRead'), 'description' => Dict::S('UI:UserManagement:Action:BulkRead+'));
		$aDisplayConfig['write'] = array('label' => Dict::S('UI:UserManagement:Action:Modify'), 'description' => Dict::S('UI:UserManagement:Action:Modify+'));
		$aDisplayConfig['bulkwrite'] = array('label' => Dict::S('UI:UserManagement:Action:BulkModify'), 'description' => Dict::S('UI:UserManagement:Action:BulkModify+'));
		$aDisplayConfig['delete'] = array('label' => Dict::S('UI:UserManagement:Action:Delete'), 'description' => Dict::S('UI:UserManagement:Action:Delete+'));
		$aDisplayConfig['bulkdelete'] = array('label' => Dict::S('UI:UserManagement:Action:BulkDelete'), 'description' => Dict::S('UI:UserManagement:Action:BulkDelete+'));
		$aDisplayConfig['stimuli'] = array('label' => Dict::S('UI:UserManagement:Action:Stimuli'), 'description' => Dict::S('UI:UserManagement:Action:Stimuli+'));
		$oPage->table($aDisplayConfig, $aDisplayData);
	}

	function DisplayBareRelations(WebPage $oPage, $bEditMode = false)
	{
		parent::DisplayBareRelations($oPage, $bEditMode);

		$oPage->SetCurrentTab('UI:UserManagement:GrantMatrix');
		$this->DoShowGrantSumary($oPage);
	}

	public static function GetReadOnlyAttributes()
	{
		return array('name', 'description');
	}


	// returns an array of id => array of column => php value(so-called "real value")
	public static function GetPredefinedObjects()
	{
		return ProfilesConfig::GetProfilesValues();
	}

	// Before deleting a profile,
	// preserve DB integrity by deleting links to users
	protected function OnDelete()
	{
		// Don't remove admin profile
		if ($this->Get('name') === ADMIN_PROFILE_NAME)
		{
			throw new SecurityException(Dict::Format('UI:Login:Error:AccessAdmin'));
		}

		// Note: this may break the rule that says: "a user must have at least ONE profile" !
		$oLnkSet = $this->Get('user_list');
		while($oLnk = $oLnkSet->Fetch())
		{
			$oLnk->DBDelete();
		}
	}

	/**
	 * Returns the set of flags (OPT_ATT_HIDDEN, OPT_ATT_READONLY, OPT_ATT_MANDATORY...)
	 * for the given attribute in the current state of the object
	 * @param $sAttCode string $sAttCode The code of the attribute
	 * @param $aReasons array To store the reasons why the attribute is read-only (info about the synchro replicas)
	 * @param $sTargetState string The target state in which to evalutate the flags, if empty the current state will be used
	 * @return integer Flags: the binary combination of the flags applicable to this attribute
	 */
	public function GetAttributeFlags($sAttCode, &$aReasons = array(), $sTargetState = '')
	{
		$iFlags = parent::GetAttributeFlags($sAttCode, $aReasons, $sTargetState);
		if (MetaModel::GetConfig()->Get('demo_mode'))
		{
			$aReasons[] = 'Sorry, profiles are read-only in the demonstration mode!';
			$iFlags |= OPT_ATT_READONLY;
		}
		return $iFlags;
	}
}



class URP_UserProfile extends UserRightsBaseClassGUI
{
	public static function Init()
	{
		$aParams = array
		(
			"category"            => "addon/userrights,grant_by_profile,filter",
			"key_type"            => "autoincrement",
			"name_attcode"        => array("userlogin", "profile"),
			"state_attcode"       => "",
			"reconc_keys"         => array(),
			"db_table"            => "priv_urp_userprofile",
			"db_key_field"        => "id",
			"db_finalclass_field" => "",
			"is_link" 			  => true, /** @since 3.1.0 N°6482 */
			'uniqueness_rules'    => array(
				'no_duplicate' => array(
					'attributes'  => array(
						0 => 'userid',
						1 => 'profileid',
					),
					'filter'      => '',
					'disabled'    => false,
					'is_blocking' => true,
				),
			),
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeExternalKey("userid", array("targetclass" => "User", "jointype" => "", "allowed_values" => null, "sql" => "userid", "is_null_allowed" => false, "on_target_delete" => DEL_AUTO, "depends_on" => array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("userlogin", array("allowed_values" => null, "extkey_attcode" => 'userid', "target_attcode" => "login")));

		MetaModel::Init_AddAttribute(new AttributeExternalKey("profileid",
			array("targetclass" => "URP_Profiles", "jointype" => "", "allowed_values" => null, "sql" => "profileid", "is_null_allowed" => false, "on_target_delete" => DEL_AUTO, "depends_on" => array(), "allow_target_creation" => false)));
		MetaModel::Init_AddAttribute(new AttributeExternalField("profile", array("allowed_values" => null, "extkey_attcode" => 'profileid', "target_attcode" => "name")));

		MetaModel::Init_AddAttribute(new AttributeString("reason", array("allowed_values" => null, "sql" => "description", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('userid', 'profileid', 'reason')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('userid', 'profileid', 'reason')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('userid', 'profileid')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('userid', 'profileid')); // Criteria of the advanced search form
	}

	public function CheckToDelete(&$oDeletionPlan)
	{
		if (MetaModel::GetConfig()->Get('demo_mode')) {
			// Users deletion is NOT allowed in demo mode
			$oDeletionPlan->AddToDelete($this, null);
			$oDeletionPlan->SetDeletionIssues($this, array('deletion not allowed in demo mode.'), true);
			$oDeletionPlan->ComputeResults();

			return false;
		}
		try {
			$this->CheckIfProfileIsAllowed(UR_ACTION_DELETE);
		}
		catch (SecurityException $e) {
			// Users deletion is NOT allowed
			$oDeletionPlan->AddToDelete($this, null);
			$oDeletionPlan->SetDeletionIssues($this, [$e->getMessage()], true);
			$oDeletionPlan->ComputeResults();

			return false;
		}

		return parent::CheckToDelete($oDeletionPlan);
	}

	public function DoCheckToDelete(&$oDeletionPlan)
	{
		if (MetaModel::GetConfig()->Get('demo_mode')) {
			// Users deletion is NOT allowed in demo mode
			$oDeletionPlan->AddToDelete($this, null);
			$oDeletionPlan->SetDeletionIssues($this, array('deletion not allowed in demo mode.'), true);
			$oDeletionPlan->ComputeResults();

			return false;
		}
		try {
			$this->CheckIfProfileIsAllowed(UR_ACTION_DELETE);
		}
		catch (SecurityException $e) {
			// Users deletion is NOT allowed
			$oDeletionPlan->AddToDelete($this, null);
			$oDeletionPlan->SetDeletionIssues($this, [$e->getMessage()], true);
			$oDeletionPlan->ComputeResults();

			return false;
		}

		return parent::DoCheckToDelete($oDeletionPlan);
	}

	protected function OnInsert()
	{
		$this->CheckIfProfileIsAllowed(UR_ACTION_CREATE);
	}

	protected function OnUpdate()
	{
		$this->CheckIfProfileIsAllowed(UR_ACTION_MODIFY);
	}

	protected function OnDelete()
	{
	}

	/**
	 * @param $iActionCode
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \SecurityException
	 */
	protected function CheckIfProfileIsAllowed($iActionCode)
	{
		// When initializing or admin, we need to let everything pass trough
		if (!UserRights::IsLoggedIn() || UserRights::IsAdministrator()) { return; }

		// Only administrators can manage administrators
		$iOrigUserId = $this->GetOriginal('userid');
		if (!empty($iOrigUserId))
		{
			$oUser = MetaModel::GetObject('User', $iOrigUserId, true, true);
			if (UserRights::IsAdministrator($oUser) && !UserRights::IsAdministrator())
			{
				throw new SecurityException(Dict::Format('UI:Login:Error:AccessRestricted'));
			}
		}
		$oUser = MetaModel::GetObject('User', $this->Get('userid'), true, true);
		if (UserRights::IsAdministrator($oUser) && !UserRights::IsAdministrator())
		{
			throw new SecurityException(Dict::Format('UI:Login:Error:AccessRestricted'));
		}
		if (!UserRights::IsActionAllowed(get_class($this), $iActionCode, DBObjectSet::FromObject($this)))
		{
			throw new SecurityException(Dict::Format('UI:Error:ObjectCannotBeUpdated'));
		}
		if (!UserRights::IsAdministrator() && ($this->Get('profile') === ADMIN_PROFILE_NAME))
		{
			throw new SecurityException(Dict::Format('UI:Login:Error:AccessAdmin'));
		}
	}

}

class URP_UserOrg extends UserRightsBaseClassGUI
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "addon/userrights,grant_by_profile",
			"key_type" => "autoincrement",
			"name_attcode" => array("userlogin", "allowed_org_name"),
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_urp_userorg",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeExternalKey("userid", array("targetclass"=>"User", "jointype"=> "", "allowed_values"=>null, "sql"=>"userid", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("userlogin", array("allowed_values"=>null, "extkey_attcode"=> 'userid', "target_attcode"=>"login")));

		MetaModel::Init_AddAttribute(new AttributeExternalKey("allowed_org_id", array("targetclass"=>"Organization", "jointype"=> "", "allowed_values"=>null, "sql"=>"allowed_org_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("allowed_org_name", array("allowed_values"=>null, "extkey_attcode"=> 'allowed_org_id', "target_attcode"=>"name")));

		MetaModel::Init_AddAttribute(new AttributeString("reason", array("allowed_values"=>null, "sql"=>"reason", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('userid', 'allowed_org_id', 'reason')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('allowed_org_id', 'reason')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('userid', 'allowed_org_id')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('userid', 'allowed_org_id')); // Criteria of the advanced search form
	}

	protected function OnInsert()
	{
		$this->CheckIfOrgIsAllowed();
	}

	protected function OnUpdate()
	{
		$this->CheckIfOrgIsAllowed();
	}

	protected function OnDelete()
	{
		$this->CheckIfOrgIsAllowed();
	}

	/**
	 * @throws \CoreException
	 */
	protected function CheckIfOrgIsAllowed()
	{
		if (!UserRights::IsLoggedIn() || UserRights::IsAdministrator()) { return; }

		$oUser = UserRights::GetUserObject();
		$oAddon = UserRights::GetModuleInstance();
		$aOrgs = $oAddon->GetUserOrgs($oUser, '');
		if (count($aOrgs) > 0)
		{
			$iOrigOrgId = $this->GetOriginal('allowed_org_id');
			if ((!empty($iOrigOrgId) && !in_array($iOrigOrgId, $aOrgs)) || !in_array($this->Get('allowed_org_id'), $aOrgs))
			{
				throw new SecurityException(Dict::Format('Class:User/Error:OrganizationNotAllowed'));
			}
		}
	}
}




class UserRightsProfile extends UserRightsAddOnAPI
{
	static public $m_aActionCodes = array(
		UR_ACTION_READ => 'r',
		UR_ACTION_MODIFY => 'w',
		UR_ACTION_DELETE => 'd',
		UR_ACTION_BULK_READ => 'br',
		UR_ACTION_BULK_MODIFY => 'bw',
		UR_ACTION_BULK_DELETE => 'bd',
	);

    /**
     * @var array $aUsersProfilesList Cache of users' profiles. Hash array of user ID => [profile ID => profile friendlyname, profile ID => profile friendlyname, ...]
     * @since 2.7.10 3.0.4 3.1.1 3.2.0 N°6887
     */
	private $aUsersProfilesList = [];

	// Installation: create the very first user
	public function CreateAdministrator($sAdminUser, $sAdminPwd, $sLanguage = 'EN US')
	{
		CMDBObject::SetCurrentChangeFromParams('Initialization create administrator');

		$iContactId = 0;
		// Support drastic data model changes: no organization class (or not writable)!
		if (MetaModel::IsValidClass('Organization') && !MetaModel::IsAbstract('Organization')) {
			$oOrg = MetaModel::NewObject('Organization');
			$oOrg->Set('name', 'My Company/Department');
			$oOrg->Set('code', 'SOMECODE');
			$iOrgId = $oOrg->DBInsertNoReload();

			// Support drastic data model changes: no Person class  (or not writable)!
			if (MetaModel::IsValidClass('Person') && !MetaModel::IsAbstract('Person')) {
				$oContact = MetaModel::NewObject('Person');
				$oContact->Set('name', 'My last name');
				$oContact->Set('first_name', 'My first name');
				if (MetaModel::IsValidAttCode('Person', 'org_id'))
				{
					$oContact->Set('org_id', $iOrgId);
				}
				$oContact->Set('email', 'my.email@foo.org');
				$iContactId = $oContact->DBInsertNoReload();
			}
		}


		$oUser = new UserLocal();
		$oUser->Set('login', $sAdminUser);
		$oUser->Set('password', $sAdminPwd);
		if (MetaModel::IsValidAttCode('UserLocal', 'contactid') && ($iContactId != 0))
		{
			$oUser->Set('contactid', $iContactId);
		}
		$oUser->Set('language', $sLanguage); // Language was chosen during the installation

		// Add this user to the very specific 'admin' profile
		$oAdminProfile = MetaModel::GetObjectFromOQL("SELECT URP_Profiles WHERE name = :name", array('name' => ADMIN_PROFILE_NAME), true /*all data*/);
		if (is_object($oAdminProfile))
		{
			$oUserProfile = new URP_UserProfile();
			$oUserProfile->Set('profileid', $oAdminProfile->GetKey());
			$oUserProfile->Set('reason', 'By definition, the administrator must have the administrator profile');
			$oSet = DBObjectSet::FromObject($oUserProfile);
			$oUser->Set('profile_list', $oSet);
		}
		$iUserId = $oUser->DBInsertNoReload();
		return true;
	}

	public function Init()
	{
	}

	protected $m_aUserOrgs = array(); // userid -> array of orgid
	protected $m_aAdministrators = null; // [user id]

	// Built on demand, could be optimized if necessary (doing a query for each attribute that needs to be read)
	protected $m_aObjectActionGrants = array();

	/**
	 * Read and cache organizations allowed to the given user
	 *
	 * @param $oUser
	 * @param $sClass (not used here but can be used in overloads)
	 *
	 * @return array
	 * @throws \CoreException
	 * @throws \Exception
	 */
	public function GetUserOrgs($oUser, $sClass)
	{
		$iUser = $oUser->GetKey();
		if (!array_key_exists($iUser, $this->m_aUserOrgs))
		{
			$this->m_aUserOrgs[$iUser] = array();

			$sHierarchicalKeyCode = MetaModel::IsHierarchicalClass('Organization');
			if ($sHierarchicalKeyCode !== false)
			{
				$sUserOrgQuery = 'SELECT UserOrg, Org FROM Organization AS Org JOIN Organization AS Root ON Org.'.$sHierarchicalKeyCode.' BELOW Root.id JOIN URP_UserOrg AS UserOrg ON UserOrg.allowed_org_id = Root.id WHERE UserOrg.userid = :userid';
				$oUserOrgSet = new DBObjectSet(DBObjectSearch::FromOQL_AllData($sUserOrgQuery), array(), array('userid' => $iUser));
				while ($aRow = $oUserOrgSet->FetchAssoc())
				{
					$oOrg = $aRow['Org'];
					$this->m_aUserOrgs[$iUser][] = $oOrg->GetKey();
				}
			}
			else
			{
				$oSearch = new DBObjectSearch('URP_UserOrg');
				$oSearch->AllowAllData();
				$oCondition = new BinaryExpression(new FieldExpression('userid'), '=', new VariableExpression('userid'));
				$oSearch->AddConditionExpression($oCondition);

				$oUserOrgSet = new DBObjectSet($oSearch, array(), array('userid' => $iUser));
				while ($oUserOrg = $oUserOrgSet->Fetch())
				{
					$this->m_aUserOrgs[$iUser][] = $oUserOrg->Get('allowed_org_id');
				}
			}
		}
		return $this->m_aUserOrgs[$iUser];
	}

	public function ResetCache()
	{
		// Loaded by Load cache
		$this->m_aUserOrgs = array();

		// Cache
		$this->m_aObjectActionGrants = array();
		$this->m_aAdministrators = null;
	}

	public function LoadCache()
	{
		static $bSharedObjectInitialized = false;
		if (!$bSharedObjectInitialized)
		{
			$bSharedObjectInitialized = true;
			if (self::HasSharing())
			{
				SharedObject::InitSharedClassProperties();
			}
		}
		return true;
	}

	/**
	 * @param $oUser User
	 * @return bool
	 */
	public function IsAdministrator($oUser)
	{
		// UserRights caches the list for us
		return UserRights::HasProfile(ADMIN_PROFILE_NAME, $oUser);
	}

	/**
	 * @param $oUser User
	 * @return bool
	 */
	public function IsPortalUser($oUser)
	{
		// UserRights caches the list for us
		return UserRights::HasProfile(PORTAL_PROFILE_NAME, $oUser);
	}

	/**
	 * @param $oUser User
	 *
	 * @return array
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 */
	public function ListProfiles($oUser)
	{
		$aRet = array();
		$oSearch = new DBObjectSearch('URP_UserProfile');
		$oSearch->AllowAllData();
		$oSearch->NoContextParameters();
		$oSearch->Addcondition('userid', $oUser->GetKey(), '=');
		$oProfiles = new DBObjectSet($oSearch);
		while ($oUserProfile = $oProfiles->Fetch())
		{
			$aRet[$oUserProfile->Get('profileid')] = $oUserProfile->Get('profileid_friendlyname');
		}
		return $aRet;
	}

	public function GetSelectFilter($oUser, $sClass, $aSettings = array())
	{
		$this->LoadCache();

		// Let us pass an administrator for bypassing the grant matrix check in order to test this method without the need to set up a complex profile
		// In the nominal case Administrators never end up here (since they completely bypass GetSelectFilter)
		if (!static::IsAdministrator($oUser) && (MetaModel::HasCategory($sClass, 'silo') || MetaModel::HasCategory($sClass, 'bizmodel')))
		{
			// N°4354 - Categories 'silo' and 'bizmodel' do check the grant matrix. Whereas 'filter' always allows to read (but the result can be filtered)
			$aObjectPermissions = $this->GetUserActionGrant($oUser, $sClass, UR_ACTION_READ);
			if ($aObjectPermissions['permission'] == UR_ALLOWED_NO)
			{
				return false;
			}
		}

		$oFilter = true;
		$aConditions =  array();

		// Determine if this class is part of a silo and build the filter for it
		$sAttCode = self::GetOwnerOrganizationAttCode($sClass);
		if (!is_null($sAttCode))
		{
			$aUserOrgs = $this->GetUserOrgs($oUser, $sClass);
			if (count($aUserOrgs) > 0)
			{
				$oFilter = $this->MakeSelectFilter($sClass, $aUserOrgs, $aSettings, $sAttCode);
			}
			// else: No org means 'any org'
		}
		// else: No silo for this class

		// Specific conditions to hide, for non-administrators, the Administrator Users, the Administrator Profile and related links
		// Note: when logged as an administrator, GetSelectFilter is completely bypassed.
		if ($this->AdministratorsAreHidden())
		{
			if ($sClass == 'URP_Profiles')
			{
				$oExpression = new FieldExpression('id', $sClass);
				$oScalarExpr = new ScalarExpression(1);

				$aConditions[] = new BinaryExpression($oExpression, '!=', $oScalarExpr);
			}
			else if (($sClass == 'URP_UserProfile') || ($sClass == 'User') || (is_subclass_of($sClass, 'User')))
			{
				$aAdministrators = $this->GetAdministrators();
				if (count($aAdministrators) > 0)
				{
					$sAttCode = ($sClass == 'URP_UserProfile') ? 'userid' : 'id';
					$oExpression = new FieldExpression($sAttCode, $sClass);
					$oListExpr = ListExpression::FromScalars($aAdministrators);
					$aConditions[] = new BinaryExpression($oExpression, 'NOT IN', $oListExpr);
				}
			}
		}

		// Handling of the added conditions
		if (count($aConditions) > 0)
		{
			if($oFilter === true)
			{
				// No 'silo' filter, let's build a clean one
				$oFilter = new DBObjectSearch($sClass);
			}

			// Add the conditions to the filter
			foreach($aConditions as $oCondition)
			{
				$oFilter->AddConditionExpression($oCondition);
			}
		}

		return $oFilter;
	}

	/**
	 * Retrieve (and memoize) the list of administrator accounts.
	 * Note that there should always be at least one administrator account
	 * @return number[]
	 */
	private function GetAdministrators()
	{
		if ($this->m_aAdministrators === null)
		{
			// Find all administrators
			$this->m_aAdministrators = array();
			$oAdministratorsFilter = new DBObjectSearch('User');
			$oLnkFilter = new DBObjectSearch('URP_UserProfile');
			$oExpression = new FieldExpression('profileid', 'URP_UserProfile');
			$oScalarExpr = new ScalarExpression(1);
			$oCondition = new BinaryExpression($oExpression, '=', $oScalarExpr);
			$oLnkFilter->AddConditionExpression($oCondition);
			$oAdministratorsFilter->AddCondition_ReferencedBy($oLnkFilter, 'userid');
			$oAdministratorsFilter->AllowAllData(true); // Mandatory to prevent infinite recursion !!
			$oSet = new DBObjectSet($oAdministratorsFilter);
			$oSet->OptimizeColumnLoad(array('User' => array('login')));
			while($oUser = $oSet->Fetch())
			{
				$this->m_aAdministrators[] = $oUser->GetKey();
			}
		}
		return $this->m_aAdministrators;
	}

	/**
	 * Whether or not to hide the 'Administrator' profile and the administrator accounts
	 * @return boolean
	 */
	private function AdministratorsAreHidden()
	{
		return ((bool)MetaModel::GetConfig()->Get('security.hide_administrators'));
	}


	// This verb has been made public to allow the development of an accurate feedback for the current configuration
	public function GetProfileActionGrant($iProfile, $sClass, $sAction)
	{
		// Note: action is forced lowercase to be more flexible (historical bug)
		$sAction = strtolower($sAction);

		return ProfilesConfig::GetProfileActionGrant($iProfile, $sClass, $sAction);
	}

	protected function GetUserActionGrant($oUser, $sClass, $iActionCode)
	{
		$this->LoadCache();

		// load and cache permissions for the current user on the given class
		//
		$iUser = $oUser->GetKey();
		if (isset($this->m_aObjectActionGrants[$iUser][$sClass][$iActionCode])){
			$aTest = $this->m_aObjectActionGrants[$iUser][$sClass][$iActionCode];
			if (is_array($aTest)) return $aTest;
		}

		$sAction = self::$m_aActionCodes[$iActionCode];

		$bStatus = null;
        // Cache user's profiles
		if(false === array_key_exists($iUser, $this->aUsersProfilesList)){
		     $this->aUsersProfilesList[$iUser] = UserRights::ListProfiles($oUser);
		}
		// Call the API of UserRights because it caches the list for us
		foreach($this->aUsersProfilesList[$iUser] as $iProfile => $oProfile)
		{
			$bGrant = $this->GetProfileActionGrant($iProfile, $sClass, $sAction);
			if (!is_null($bGrant))
			{
				if ($bGrant)
				{
					if (is_null($bStatus))
					{
						$bStatus = true;
					}
				}
				else
				{
					$bStatus = false;
				}
			}
		}

		$iPermission = $bStatus ? UR_ALLOWED_YES : UR_ALLOWED_NO;

		$aRes = array(
			'permission' => $iPermission,
		);
		$this->m_aObjectActionGrants[$iUser][$sClass][$iActionCode] = $aRes;
		return $aRes;
	}

	public function IsActionAllowed($oUser, $sClass, $iActionCode, $oInstanceSet = null)
	{
		$this->LoadCache();

		$aObjectPermissions = $this->GetUserActionGrant($oUser, $sClass, $iActionCode);
		$iPermission = $aObjectPermissions['permission'];

		// Note: In most cases the object set is ignored because it was interesting to optimize for huge data sets
		//       and acceptable to consider only the root class of the object set

		if ($iPermission != UR_ALLOWED_YES)
		{
			// It is already NO for everyone... that's the final word!
		}
		elseif ($iActionCode == UR_ACTION_READ)
		{
			// We are protected by GetSelectFilter: the object set contains objects allowed or shared for reading
		}
		elseif ($iActionCode == UR_ACTION_BULK_READ)
		{
			// We are protected by GetSelectFilter: the object set contains objects allowed or shared for reading
		}
		elseif ($oInstanceSet)
		{
			// We are protected by GetSelectFilter: the object set contains objects allowed or shared for reading
			// We have to answer NO for objects shared for reading purposes
			if (self::HasSharing())
			{
				$aClassProps = SharedObject::GetSharedClassProperties($sClass);
				if ($aClassProps)
				{
					// This class is shared, GetSelectFilter may allow some objects for read only
					// But currently we are checking wether the objects might be written...
					// Let's exclude the objects based on the relevant criteria

					$sOrgAttCode = self::GetOwnerOrganizationAttCode($sClass);
					if (!is_null($sOrgAttCode))
					{
						$aUserOrgs = $this->GetUserOrgs($oUser, $sClass);
						if (!is_null($aUserOrgs) && count($aUserOrgs) > 0)
						{
							$iCountNO = 0;
							$iCountYES = 0;
							$oInstanceSet->Rewind();
							while($oObject = $oInstanceSet->Fetch())
							{
								$iOrg = $oObject->Get($sOrgAttCode);
								if (in_array($iOrg, $aUserOrgs))
								{
									$iCountYES++;
								}
								else
								{
									$iCountNO++;
								}
							}
							if ($iCountNO == 0)
							{
								$iPermission = UR_ALLOWED_YES;
							}
							elseif ($iCountYES == 0)
							{
								$iPermission = UR_ALLOWED_NO;
							}
							else
							{
								$iPermission = UR_ALLOWED_DEPENDS;
							}
						}
					}
				}
			}
		}
		return $iPermission;
	}

	public function IsActionAllowedOnAttribute($oUser, $sClass, $sAttCode, $iActionCode, $oInstanceSet = null)
	{
		$this->LoadCache();

		// Note: The object set is ignored because it was interesting to optimize for huge data sets
		//       and acceptable to consider only the root class of the object set
		$aObjectPermissions = $this->GetUserActionGrant($oUser, $sClass, $iActionCode);
		return $aObjectPermissions['permission'];
	}

	// This verb has been made public to allow the development of an accurate feedback for the current configuration
	public function GetClassStimulusGrant($iProfile, $sClass, $sStimulusCode)
	{
		return ProfilesConfig::GetProfileStimulusGrant($iProfile, $sClass, $sStimulusCode);
	}

	public function IsStimulusAllowed($oUser, $sClass, $sStimulusCode, $oInstanceSet = null)
	{
		$this->LoadCache();
		// Note: this code is VERY close to the code of IsActionAllowed()
		$iUser = $oUser->GetKey();

        // Cache user's profiles
		if(false === array_key_exists($iUser, $this->aUsersProfilesList)){
			$this->aUsersProfilesList[$iUser] = UserRights::ListProfiles($oUser);
		}

		// Note: The object set is ignored because it was interesting to optimize for huge data sets
		//       and acceptable to consider only the root class of the object set
		$bStatus = null;
		// Call the API of UserRights because it caches the list for us
		foreach($this->aUsersProfilesList[$iUser] as $iProfile => $oProfile)
		{
			$bGrant = $this->GetClassStimulusGrant($iProfile, $sClass, $sStimulusCode);
			if (!is_null($bGrant))
			{
				if ($bGrant)
				{
					if (is_null($bStatus))
					{
						$bStatus = true;
					}
				}
				else
				{
					$bStatus = false;
				}
			}
		}

		$iPermission = $bStatus ? UR_ALLOWED_YES : UR_ALLOWED_NO;
		return $iPermission;
	}

	public function FlushPrivileges()
	{
		$this->ResetCache();
	}

	/**
	 * @param string $sClass
	 * @return string|null Find out which attribute is corresponding the dimension 'owner org'
	 *                   returns null if no such attribute has been found (no filtering should occur)
	 */
	public static function GetOwnerOrganizationAttCode($sClass)
	{
		$sAttCode = null;

		$aCallSpec = array($sClass, 'MapContextParam');
		if (($sClass == 'Organization') || is_subclass_of($sClass, 'Organization'))
		{
			$sAttCode = 'id';
		}
		elseif (is_callable($aCallSpec))
		{
			$sAttCode = call_user_func($aCallSpec, 'org_id'); // Returns null when there is no mapping for this parameter
			if (!MetaModel::IsValidAttCode($sClass, $sAttCode))
			{
				// Skip silently. The data model checker will tell you something about this...
				$sAttCode = null;
			}
		}
		elseif(MetaModel::IsValidAttCode($sClass, 'org_id'))
		{
			$sAttCode = 'org_id';
		}

		return $sAttCode;
	}

	/**
	 * Determine wether the objects can be shared by the mean of a class SharedObject
	 **/
	protected static function HasSharing()
	{
		static $bHasSharing;
		if (!isset($bHasSharing))
		{
			$bHasSharing = class_exists('SharedObject');
		}
		return $bHasSharing;
	}
}


UserRights::SelectModule('UserRightsProfile');

?>
