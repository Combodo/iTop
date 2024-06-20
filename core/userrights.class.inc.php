<?php

use Combodo\iTop\Application\Helper\Session;
use Combodo\iTop\Application\WebPage\WebPage;
use Combodo\iTop\Service\Events\EventData;

define('UR_ALLOWED_NO', 0);
define('UR_ALLOWED_YES', 1);
define('UR_ALLOWED_DEPENDS', 2);

define('UR_ACTION_READ', 1); // View an object
define('UR_ACTION_MODIFY', 2); // Create/modify an object/attribute
define('UR_ACTION_DELETE', 3); // Delete an object

define('UR_ACTION_BULK_READ', 4); // Export multiple objects
define('UR_ACTION_BULK_MODIFY', 5); // Create/modify multiple objects
define('UR_ACTION_BULK_DELETE', 6); // Delete multiple objects

define('UR_ACTION_CREATE', 7); // Instantiate an object

define('UR_ACTION_APPLICATION_DEFINED', 10000); // Application specific actions (CSV import, View schema...)

/**
 * User management module API
 *
 * @package     iTopORM
 */
abstract class UserRightsAddOnAPI
{
	/**
	 * @param string $sAdminUser
	 * @param string $sAdminPwd
	 * @param string $sLanguage
	 *
	 * @return mixed
	 */
	abstract public function CreateAdministrator($sAdminUser, $sAdminPwd, $sLanguage = 'EN US'); // could be used during initial installation

	/**
	 * @return void
	 */
	abstract public function Init(); // loads data (possible optimizations)

	/**
	 * Used to build select queries showing only objects visible for the given user
	 *
	 * @param string $sLogin
	 * @param string $sClass
	 * @param array $aSettings
	 *
	 * @return mixed
	 */
	abstract public function GetSelectFilter($sLogin, $sClass, $aSettings = array()); // returns a filter object

	/**
	 * @param \User $oUser
	 * @param string $sClass
	 * @param int $iActionCode
	 * @param null $oInstanceSet
	 *
	 * @return bool
	 */
	abstract public function IsActionAllowed($oUser, $sClass, $iActionCode, $oInstanceSet = null);

	/**
	 * @param \User $oUser
	 * @param string $sClass
	 * @param string $sStimulusCode
	 * @param \DBObjectSet|null $oInstanceSet
	 *
	 * @return bool
	 */
	abstract public function IsStimulusAllowed($oUser, $sClass, $sStimulusCode, $oInstanceSet = null);

	/**
	 * @param \User $oUser
	 * @param string $sClass
	 * @param string $sAttCode
	 * @param int $iActionCode
	 * @param \DBObjectSet|null $oInstanceSet
	 *
	 * @return bool
	 */
	abstract public function IsActionAllowedOnAttribute($oUser, $sClass, $sAttCode, $iActionCode, $oInstanceSet = null);

	/**
	 * @param \User $oUser
	 *
	 * @return bool
	 */
	abstract public function IsAdministrator($oUser);

	/**
	 * @param \User $oUser
	 *
	 * @return bool
	 */
	abstract public function IsPortalUser($oUser);

	/**
	 * @return void
	 */
	abstract public function FlushPrivileges();

	/**
	 * Default behavior for addons that do not support profiles
	 *
	 * @param \User $oUser
	 * @return array
	 */
	public function ListProfiles($oUser)
	{
		return array();
	}

	/**
	 * ...
	 *
	 * @param string$sClass
	 * @param array $aAllowedOrgs
	 * @param array $aSettings
	 * @param string|null $sAttCode
	 *
	 * @return \DBObjectSearch
	 * @throws \CoreException
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 */
	public function MakeSelectFilter($sClass, $aAllowedOrgs, $aSettings = array(), $sAttCode = null)
	{
		if ($sAttCode == null)
		{
			$sAttCode = $this->GetOwnerOrganizationAttCode($sClass);
		}
		if (empty($sAttCode))
		{
			return $oFilter  = new DBObjectSearch($sClass);
		}

		$oExpression = new FieldExpression($sAttCode, $sClass);
		$oFilter  = new DBObjectSearch($sClass);
		$oListExpr = ListExpression::FromScalars($aAllowedOrgs);

		$oCondition = new BinaryExpression($oExpression, 'IN', $oListExpr);
		$oFilter->AddConditionExpression($oCondition);

		if ($this->HasSharing())
		{
			if (($sAttCode == 'id') && isset($aSettings['bSearchMode']) && $aSettings['bSearchMode'])
			{
				// Querying organizations (or derived)
				// and the expected list of organizations will be used as a search criteria
				// Therefore the query can also return organization having objects shared with the allowed organizations
				//
				// 1) build the list of organizations sharing something with the allowed organizations
				// Organization <== sharing_org_id == SharedObject having org_id IN {user orgs}
				$oShareSearch = new DBObjectSearch('SharedObject');
				$oOrgField = new FieldExpression('org_id', 'SharedObject');
				$oShareSearch->AddConditionExpression(new BinaryExpression($oOrgField, 'IN', $oListExpr));

				$oSearchSharers = new DBObjectSearch('Organization');
				$oSearchSharers->AllowAllData();
				$oSearchSharers->AddCondition_ReferencedBy($oShareSearch, 'sharing_org_id');
				$aSharers = array();
				foreach($oSearchSharers->SelectAttributeToArray('id') as $aRow)
				{
					$aSharers[] = $aRow['id'];
				}
				// 2) Enlarge the overall results: ... OR id IN(id1, id2, id3)
				if (count($aSharers) > 0)
				{
					$oSharersList = ListExpression::FromScalars($aSharers);
					$oFilter->MergeConditionExpression(new BinaryExpression($oExpression, 'IN', $oSharersList));
				}
			}

			$aShareProperties = SharedObject::GetSharedClassProperties($sClass);
			if ($aShareProperties)
			{
				$sShareClass = $aShareProperties['share_class'];
				$sShareAttCode = $aShareProperties['attcode'];

				$oSearchShares = new DBObjectSearch($sShareClass);
				$oSearchShares->AllowAllData();

				$sHierarchicalKeyCode = MetaModel::IsHierarchicalClass('Organization');
				$oOrgField = new FieldExpression('org_id', $sShareClass);
				$oSearchShares->AddConditionExpression(new BinaryExpression($oOrgField, 'IN', $oListExpr));
				$aShared = array();
				foreach($oSearchShares->SelectAttributeToArray($sShareAttCode) as $aRow)
				{
					$aShared[] = $aRow[$sShareAttCode];
				}
				if (count($aShared) > 0)
				{
					$oObjId = new FieldExpression('id', $sClass);
					$oSharedIdList = ListExpression::FromScalars($aShared);
					$oFilter->MergeConditionExpression(new BinaryExpression($oObjId, 'IN', $oSharedIdList));
				}
			}
		} // if HasSharing

		return $oFilter;
	}
}


require_once(APPROOT.'/application/cmdbabstract.class.inc.php');
abstract class User extends cmdbAbstractObject
{
	/**
	 * @throws \CoreException
	 */
	public static function Init()
	{
		$aParams = array
		(
			"category"            => "core,grant_by_profile,silo",
			"key_type"            => "autoincrement",
			"name_attcode"        => "login",
			"state_attcode"       => "status",
			"reconc_keys"         => array(),
			"db_table"            => "priv_user",
			"db_key_field"        => "id",
			"db_finalclass_field" => "",
			"style"               => new ormStyle("ibo-dm-class--User", "ibo-dm-class-alt--User", "var(--ibo-dm-class--User--main-color)", "var(--ibo-dm-class--User--complementary-color)", null, "itop-structure/../../images/icons/icons8-security-pass.svg"),
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeExternalKey("contactid", array("targetclass" => "Person", "allowed_values" => null, "sql" => "contactid", "is_null_allowed" => true, "on_target_delete" => DEL_MANUAL, "depends_on" => array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("last_name", array("allowed_values" => null, "extkey_attcode" => 'contactid', "target_attcode" => "name")));
		MetaModel::Init_AddAttribute(new AttributeExternalField("first_name", array("allowed_values" => null, "extkey_attcode" => 'contactid', "target_attcode" => "first_name")));
		MetaModel::Init_AddAttribute(new AttributeExternalField("email", array("allowed_values" => null, "extkey_attcode" => 'contactid', "target_attcode" => "email")));
		MetaModel::Init_AddAttribute(new AttributeExternalField("org_id", array("allowed_values" => null, "extkey_attcode" => 'contactid', "target_attcode" => "org_id")));

		MetaModel::Init_AddAttribute(new AttributeString("login", array("allowed_values" => null, "sql" => "login", "default_value" => null, "is_null_allowed" => false, "depends_on" => array())));

		MetaModel::Init_AddAttribute(new AttributeApplicationLanguage("language", array("sql" => "language", "default_value" => "EN US", "is_null_allowed" => false, "depends_on" => array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("status", array(
			"allowed_values"  => new ValueSetEnum('enabled,disabled'),
			"styled_values"   => [
				'enabled'  => new ormStyle('ibo-dm-enum--User-status-enabled', 'ibo-dm-enum-alt--User-status-enabled', 'var(--ibo-dm-enum--User-status-enabled--main-color)', 'var(--ibo-dm-enum--User-status-enabled--complementary-color)', null, null),
				'disabled' => new ormStyle('ibo-dm-enum--User-status-disabled', 'ibo-dm-enum-alt--User-status-disabled', 'var(--ibo-dm-enum--User-status-disabled--main-color)', 'var(--ibo-dm-enum--User-status-disabled--complementary-color)', null, null),
			],
			"sql"             => "status",
			"default_value"   => "enabled",
			"is_null_allowed" => false,
			"depends_on"      => array(),
		)));

		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("profile_list",array("linked_class" => "URP_UserProfile", "ext_key_to_me" => "userid", "ext_key_to_remote" => "profileid", "allowed_values" => null, "count_min" => 1, "count_max" => 0, "depends_on" => array(), "display_style" => 'property', "with_php_constraint" => true, "with_php_computation" => true)));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("allowed_org_list", array("linked_class" => "URP_UserOrg", "ext_key_to_me" => "userid", "ext_key_to_remote" => "allowed_org_id", "allowed_values" => null, "count_min" => 1, "count_max" => 0, "depends_on" => array(), 'with_php_constraint' => true)));
		MetaModel::Init_AddAttribute(new AttributeCaseLog("log", array("sql" => 'log', "is_null_allowed" => true, "default_value" => '', "allowed_values" => null, "depends_on" => array(), "always_load_in_tables" => false)));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('contactid', 'org_id', 'email', 'login', 'language', 'status', 'profile_list', 'allowed_org_list', 'log')); // Unused as it's an abstract class !
		MetaModel::Init_SetZListItems('list', array('finalclass', 'first_name', 'last_name', 'status', 'org_id')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('login', 'contactid', 'email', 'language', 'status', 'org_id')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('default_search', array('login', 'contactid', 'status', 'org_id')); // Default criteria of the search banner
	}

	protected function RegisterEventListeners()
	{
		if ($this->IsCurrentUser() && !UserRights::IsAdministrator()) {
			$this->RegisterCRUDListener(EVENT_DB_SET_ATTRIBUTES_FLAGS, 'SetAllowedOrgListReadOnly');
		}
	}

	abstract public function CheckCredentials($sPassword);
	abstract public function TrustWebServerContext();
	abstract public function CanChangePassword();
	abstract public function ChangePassword($sOldPassword, $sNewPassword);

	protected function SetAllowedOrgListReadOnly(EventData $oEventData)
	{
		$this->AddAttributeFlags('allowed_org_list', OPT_ATT_READONLY);
	}

	/*
	* Compute a name in best effort mode
	 *
	 * @return string
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	*/
	public function GetFriendlyName()
	{
		if (!MetaModel::IsValidAttCode(get_class($this), 'contactid'))
		{
			return $this->Get('login');
		}
		if ($this->Get('contactid') != 0)
		{
			$sFirstName = $this->Get('first_name');
			$sLastName = $this->Get('last_name');
			$sEmail = $this->Get('email');
			if (strlen($sFirstName) > 0)
			{
				return "$sFirstName $sLastName";
			}
			elseif (strlen($sEmail) > 0)
			{
				return "$sLastName <$sEmail>";
			}
			else
			{
				return $sLastName;
			}
		}
		return $this->Get('login');
	}

	/*
	* Compute the initials in best effort mode
	 *
	 * @return string
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @since 3.0.0
	*/
	public function GetInitials(): string
	{
		$sInitials = '';

		if (MetaModel::IsValidAttCode(get_class($this), 'contactid') && ($this->Get('contactid') != 0)) {
			$sInitials = utils::ToAcronym($this->Get('contactid_friendlyname'));
		}

		if (empty($sInitials)) {
			$sInitials = utils::ToAcronym($this->Get('login'));
		}

		return $sInitials;
	}

	protected $oContactObject;

	/**
	 * Fetch and memorize the associated contact (if any)
	 */
	public function GetContactObject()
	{
		if (is_null($this->oContactObject))
		{
			if (MetaModel::IsValidAttCode(get_class($this), 'contactid') && ($this->Get('contactid') != 0))
			{
				$this->oContactObject = null;
				// The User Contact is generally a Person, so try it first
				if (MetaModel::IsValidClass('Person')) {
					$this->oContactObject = MetaModel::GetObject('Person', $this->Get('contactid'), false);
				}
				if (is_null($this->oContactObject)) {
					$this->oContactObject = MetaModel::GetObject('Contact', $this->Get('contactid'));
				}
			}
		}
		return $this->oContactObject;
	}

	/**
	 * Overload the standard behavior.
	 *
	 * @throws \CoreException
	 */
	public function DoCheckToWrite()
	{
		parent::DoCheckToWrite();

		$oAddon = UserRights::GetModuleInstance();
		$aChanges = $this->ListChanges();
		if (array_key_exists('login', $aChanges)) {
			// Check login uniqueness
			if ( $this->GetOriginal('login') === null || strcasecmp($this->Get('login'), $this->GetOriginal('login')) !== 0) {
				$sNewLogin = $aChanges['login'];
				$oSearch = DBObjectSearch::FromOQL_AllData("SELECT User WHERE login = :newlogin");
				if (!$this->IsNew()) {
					$oSearch->AddCondition('id', $this->GetKey(), '!=');
				}
				$oSet = new DBObjectSet($oSearch, array(), array('newlogin' => $sNewLogin));
				if ($oSet->Count() > 0) {
					$this->m_aCheckIssues[] = Dict::Format('Class:User/Error:LoginMustBeUnique', $sNewLogin);
				}
			}
		}

		// A User cannot disable himself
		if ($this->IsCurrentUser()) {
			if (isset($aChanges['status']) && ($this->Get('status') == 'disabled')) {
				$this->m_aCheckIssues[] = Dict::S('Class:User/Error:StatusChangeIsNotAllowed');
			}
		}

		// Check that this user has at least one profile assigned when profiles have changed
		if (array_key_exists('profile_list', $aChanges)) {
			/** @var \DBObjectSet $oSet */
			$oSet = $this->Get('profile_list');
			if ($oSet->Count() == 0) {
				if (ContextTag::Check(ContextTag::TAG_SETUP)) {
					// During setup, if a profile is no more part of iTop, it will be deleted
					// But if it is the only profile assigned to a user, we don't want this to stop the setup
					SetupLog::Warning("The user with id: ".$this->GetKey()." is no more usable as its last profile was removed during setup");
					return;
				}
				$this->m_aCheckIssues[] = Dict::S('Class:User/Error:AtLeastOneProfileIsNeeded');
			}

			// A user cannot add to themself a profile denying the access to the backoffice
			$aForbiddenProfiles = PortalDispatcherData::GetData('backoffice')['deny'];
			if ($this->IsCurrentUser()) {
				$oSet->Rewind();
				$aProfiles = [];
				while ($oUserProfile = $oSet->Fetch()) {
					$sProfile = $oUserProfile->Get('profile');
					if (in_array($sProfile, $aForbiddenProfiles)) {
						$this->m_aCheckIssues[] = Dict::Format('Class:User/Error:ProfileNotAllowed', $sProfile);
					}
					$aProfiles[$oUserProfile->Get('profileid')] = $sProfile;
				}

				if (!in_array(ADMIN_PROFILE_NAME, $aProfiles)) {
					// Check if the user is yet allowed to modify Users
					if (method_exists($oAddon, 'ResetCache')) {
						$aCurrentProfiles = Session::Get('profile_list');
						// Set the current profiles into a session variable (not yet in the database)
						Session::Set('profile_list', $aProfiles);

						$oAddon->ResetCache();
						if (!$oAddon->IsActionAllowed($this, 'User', UR_ACTION_MODIFY, null)) {
							$this->m_aCheckIssues[] = Dict::S('Class:User/Error:CurrentProfilesHaveInsufficientRights');
						}
						$oAddon->ResetCache();

						if (is_null($aCurrentProfiles)) {
							Session::IsSet('profile_list');
						} else {
							Session::Set('profile_list', $aCurrentProfiles);
						}
					}
				}
			}
		}

		// Only administrators can manage administrators
		if (UserRights::IsAdministrator($this) && !UserRights::IsAdministrator()) {
			$this->m_aCheckIssues[] = Dict::S('UI:Login:Error:AccessRestricted');
		}

		// A contact is mandatory (an administrator can bypass it but not for himself)
		if ((!UserRights::IsAdministrator() || $this->IsCurrentUser())
			&& !$this->IsNew()
			&& isset($aChanges['contactid'])
			&& empty($this->Get('contactid'))) {
			$this->m_aCheckIssues[] = Dict::S('Class:User/Error:PersonIsMandatory');
		}
		// Warning if the user has no associated contact
		elseif (empty($this->Get('contactid'))) {
		    $this->AddCheckWarning(Dict::S('Class:User/Warning:NoContactHasImpact'));
		}

		// Allowed orgs must contain the user org (if any)
		if (!empty($this->Get('org_id')) && !UserRights::IsAdministrator($this)) {
			// Get the user org and all its parent orgs
			$aUserOrgs = [$this->Get('org_id')];
			$sHierarchicalKeyCode = MetaModel::IsHierarchicalClass('Organization');
			if ($sHierarchicalKeyCode !== false) {
				$sOrgQuery = 'SELECT Org FROM Organization AS Org JOIN Organization AS Root ON Org.'.$sHierarchicalKeyCode.' ABOVE Root.id WHERE Root.id = :id';
				$oOrgSet = new DBObjectSet(DBObjectSearch::FromOQL_AllData($sOrgQuery), [], ['id' => $this->Get('org_id')]);
				while ($aRow = $oOrgSet->FetchAssoc()) {
					$oOrg = $aRow['Org'];
					$aUserOrgs[] = $oOrg->GetKey();
				}
			}
			// Check the allowed orgs list
			$oSet = $this->get('allowed_org_list');
			if ($oSet->Count() > 0) {
				$bFound = false;
				while ($oOrg = $oSet->Fetch()) {
					if (in_array($oOrg->Get('allowed_org_id'), $aUserOrgs)) {
						$bFound = true;
						break;
					}
				}
				if (!$bFound) {
					$this->m_aCheckIssues[] = Dict::S('Class:User/Error:AllowedOrgsMustContainUserOrg');
				}
			}
		}

		// Modified User is not administrator and has no allowed orgs, warn about the consequences
		if (!UserRights::IsAdministrator($this) && ($this->get('allowed_org_list')->Count() == 0)) {
			$this->AddCheckWarning(Dict::S('Class:User/Warning:NoOrganizationMeansFullAccess'));
		}

		if (!UserRights::IsAdministrator()) {
			$oUser = UserRights::GetUserObject();
			if (!is_null($oUser) && method_exists($oAddon, 'GetUserOrgs')) {
				$aOrgs = $oAddon->GetUserOrgs($oUser, ''); // Modifier allowed orgs
				if (count($aOrgs) > 0) {
					// Check that the modified User belongs to one of our organization
					if (!in_array($this->GetOriginal('org_id'), $aOrgs) && !in_array($this->Get('org_id'), $aOrgs)) {
						$this->m_aCheckIssues[] = Dict::S('Class:User/Error:UserOrganizationNotAllowed');
					}
					// Check users with restricted organizations when allowed organizations have changed
					if ($this->IsNew() || array_key_exists('allowed_org_list', $aChanges)) {
						$oSet = $this->get('allowed_org_list');
						if ($oSet->Count() == 0) {
							$this->m_aCheckIssues[] = Dict::S('Class:User/Error:AtLeastOneOrganizationIsNeeded');
						} else {
							$aModifiedLinks = $oSet->ListModifiedLinks();
							foreach ($aModifiedLinks as $oLink) {
								if (!in_array($oLink->Get('allowed_org_id'), $aOrgs)) {
									$this->m_aCheckIssues[] = Dict::S('Class:User/Error:OrganizationNotAllowed');
								}
							}
						}
					}
				}
			}
		}
	}

	/**
	 * @inheritDoc
	 * @since 3.0.0
	 */
	public function DoCheckToDelete(&$oDeletionPlan)
	{
		parent::DoCheckToDelete($oDeletionPlan);

		// A user cannot suppress himself
		if ($this->IsCurrentUser()) {
			$this->m_bSecurityIssue = true;
			$this->m_aDeleteIssues[] = Dict::S('UI:Delete:NotAllowedToDelete');
		}
	}

	function GetGrantAsHtml($sClass, $iAction)
	{
		if (UserRights::IsActionAllowed($sClass, $iAction, null, $this)) {
			return '<span style="background-color: #ddffdd;">'.Dict::S('UI:UserManagement:ActionAllowed:Yes').'</span>';
		} else {
			return '<span style="background-color: #ffdddd;">'.Dict::S('UI:UserManagement:ActionAllowed:No').'</span>';
		}
	}

	function DoShowGrantSumary($oPage, $sClassCategory)
	{
		if (UserRights::IsAdministrator($this))
		{
			// Looks dirty, but ok that's THE ONE
			$oPage->p(Dict::S('UI:UserManagement:AdminProfile+'));
			return;
		}

		$oKPI = new ExecutionKPI();

		$aDisplayData = array();
		foreach (MetaModel::GetClasses($sClassCategory) as $sClass)
		{
			$aClassStimuli = MetaModel::EnumStimuli($sClass);
			if (count($aClassStimuli) > 0)
			{
				$aStimuli = array();
				foreach ($aClassStimuli as $sStimulusCode => $oStimulus)
				{
					if (UserRights::IsStimulusAllowed($sClass, $sStimulusCode, null, $this))
					{
						$aStimuli[] = '<span title="'.$sStimulusCode.': '.utils::EscapeHtml($oStimulus->GetDescription()).'">'.utils::EscapeHtml($oStimulus->GetLabel()).'</span>';
					}
				}
				$sStimuli = implode(', ', $aStimuli);
			}
			else
			{
				$sStimuli = '<em title="'.Dict::S('UI:UserManagement:NoLifeCycleApplicable+').'">'.Dict::S('UI:UserManagement:NoLifeCycleApplicable').'</em>';
			}

			$aDisplayData[] = array(
				'class' => MetaModel::GetName($sClass),
				'read' => $this->GetGrantAsHtml($sClass, UR_ACTION_READ),
				'bulkread' => $this->GetGrantAsHtml($sClass, UR_ACTION_BULK_READ),
				'write' => $this->GetGrantAsHtml($sClass, UR_ACTION_MODIFY),
				'bulkwrite' => $this->GetGrantAsHtml($sClass, UR_ACTION_BULK_MODIFY),
				'delete' => $this->GetGrantAsHtml($sClass, UR_ACTION_DELETE),
				'bulkdelete' => $this->GetGrantAsHtml($sClass, UR_ACTION_BULK_DELETE),
				'stimuli' => $sStimuli,
			);
		}

		$oKPI->ComputeAndReport('Computation of user rights');

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
		$this->DoShowGrantSumary($oPage, 'bizmodel,grant_by_profile');

		// debug
		if (false)
		{
			$oPage->SetCurrentTab('More on user rigths (dev only)');
			$oPage->add("<h3>User rights</h3>\n");
			$this->DoShowGrantSumary($oPage, 'addon/userrights');
			$oPage->add("<h3>Change log</h3>\n");
			$this->DoShowGrantSumary($oPage, 'core/cmdb');
			$oPage->add("<h3>Application</h3>\n");
			$this->DoShowGrantSumary($oPage, 'application');
			$oPage->add("<h3>GUI</h3>\n");
			$this->DoShowGrantSumary($oPage, 'gui');

		}
	}

  	public function CheckToDelete(&$oDeletionPlan)
  	{
  		if (MetaModel::GetConfig()->Get('demo_mode'))
		{
			// Users deletion is NOT allowed in demo mode
			$oDeletionPlan->AddToDelete($this, null);
			$oDeletionPlan->SetDeletionIssues($this, array('deletion not allowed in demo mode.'), true);
			$oDeletionPlan->ComputeResults();
			return false;
		}
		return parent::CheckToDelete($oDeletionPlan);
  	}

	protected function DBDeleteSingleObject()
	{
		if (MetaModel::GetConfig()->Get('demo_mode'))
		{
			// Users deletion is NOT allowed in demo mode
			return;
		}
		parent::DBDeleteSingleObject();
	}

	/**
	 * @return bool
	 * @throws \OQLException
	 * @since 3.0.0
	 */
	protected function IsCurrentUser(): bool
	{
		if (is_null(UserRights::GetUserId())) {
			return false;
		}
		return UserRights::GetUserId() == $this->GetKey();
	}
}

/**
 * Abstract class for all types of "internal" authentication i.e. users
 * for which the application is supplied a login and a password opposed
 * to "external" users for whom the authentication is performed outside
 * of the application (by the web server for example).
 * Note that "internal" users do not necessary correspond to a local authentication
 * they may be authenticated by a remote system, like in authent-ldap.
 */
abstract class UserInternal extends User
{
	// Nothing special, just a base class to categorize this type of authenticated users
	public static function Init()
	{
		$aParams = array
		(
			"category"            => "core,grant_by_profile,silo",
			"key_type"            => "autoincrement",
			"name_attcode"        => "login",
			"state_attcode"       => "",
			"reconc_keys"         => array('login'),
			"db_table"            => "priv_internaluser",
			"db_key_field"        => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		// When set, this token allows for password reset
		MetaModel::Init_AddAttribute(new AttributeOneWayPassword("reset_pwd_token", array("allowed_values" => null, "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('contactid', 'org_id', 'email', 'login', 'status', 'language', 'profile_list', 'allowed_org_list', 'log')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('finalclass', 'first_name', 'last_name', 'status', 'org_id')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('login', 'contactid', 'status', 'org_id')); // Criteria of the std search form
	}

	/**
	 * Use with care!
	 */
	public function SetPassword($sNewPassword)
	{
	}

	/**
	 * The email recipient is the person who is allowed to regain control when the password gets lost
	 * Throws an exception if the feature cannot be available
	 */
	public function GetResetPasswordEmail()
	{
		if (!MetaModel::IsValidAttCode(get_class($this), 'contactid'))
		{
			throw new Exception(Dict::S('UI:ResetPwd-Error-NoContact'));
		}
		$iContactId = $this->Get('contactid');
		if ($iContactId == 0)
		{
			throw new Exception(Dict::S('UI:ResetPwd-Error-NoContact'));
		}
		$oContact = MetaModel::GetObject('Contact', $iContactId);
		// Determine the email attribute (the first one will be our choice)
		foreach (MetaModel::ListAttributeDefs(get_class($oContact)) as $sAttCode => $oAttDef)
		{
			if ($oAttDef instanceof AttributeEmailAddress)
			{
				$sEmailAttCode = $sAttCode;
				// we've got one, exit the loop
				break;
			}
		}
		if (!isset($sEmailAttCode))
		{
			throw new Exception(Dict::S('UI:ResetPwd-Error-NoEmailAtt'));
		}
		$sRes = trim($oContact->Get($sEmailAttCode));
		return $sRes;
	}
}

/**
 * Self register extension
 *
 * @package     iTopORM
 */
interface iSelfRegister
{
	/**
	 * Called when no user is found in iTop for the corresponding 'name'. This method
	 * can create/synchronize the User in iTop with an external source (such as AD/LDAP) on the fly
	 * @param string $sName The typed-in user name
	 * @param string $sPassword The typed-in password
	 * @param string $sLoginMode The login method used (cas|form|basic|url)
	 * @param string $sAuthentication The authentication method used (any|internal|external)
	 * @return bool true if the user is a valid one, false otherwise
	 */
	public static function CheckCredentialsAndCreateUser($sName, $sPassword, $sLoginMode, $sAuthentication);

	/**
	 * Called after the user has been authenticated and found in iTop. This method can
	 * Update the user's definition on the fly (profiles...) to keep it in sync with an external source
	 * @param User $oUser The user to update/synchronize
	 * @param string $sLoginMode The login mode used (cas|form|basic|url)
	 * @param string $sAuthentication The authentication method used
	 * @return void
	 */
	public static function UpdateUser(User $oUser, $sLoginMode, $sAuthentication);
}

/**
 * User management core API
 *
 * @package     iTopORM
 */
class UserRights
{
	const DEFAULT_USER_CONTACT_ID_ATTCODE = 'contactid';
	const DEFAULT_CONTACT_ORG_ID_ATTCODE = 'org_id';
	const DEFAULT_CONTACT_ORG_ID_FRIENDLYNAME_ATTCODE = self::DEFAULT_CONTACT_ORG_ID_ATTCODE.'_friendlyname';
	const DEFAULT_CONTACT_FIRSTNAME_ATTCODE = 'first_name';
	const DEFAULT_CONTACT_PICTURE_ATTCODE = 'picture';

	public static $m_aCacheUsers;
	/** @var array Associative array of user's ID => user's picture URL */
	protected static $m_aCacheContactPictureAbsUrl = [];
	/** @var UserRightsAddOnAPI $m_oAddOn */
	protected static $m_oAddOn;
	protected static $m_oUser = null;
	protected static $m_oRealUser = null;
	protected static $m_sSelfRegisterAddOn = null;
	protected static $m_aAdmins = array();
	protected static $m_aPortalUsers = array();
	/** @var array array('sName' => $sName, 'bSuccess' => $bSuccess); */
	private static $m_sLastLoginStatus = null;

	/**
	 * @return void
	 * @since 3.0.4 3.1.1 3.2.0
	 */
	protected static function ResetCurrentUserData()
	{
		self::$m_oUser = null;
		self::$m_oRealUser = null;
		self::$m_sLastLoginStatus = null;
	}

	/**
	 * @param string $sModuleName
	 *
	 * @return void
	 * @throws \CoreException
	 */
	public static function SelectModule($sModuleName)
	{
		if (!class_exists($sModuleName))
		{
			throw new CoreException("Could not select this module, '$sModuleName' in not a valid class name");
		}
		if (!is_subclass_of($sModuleName, 'UserRightsAddOnAPI'))
		{
			throw new CoreException("Could not select this module, the class '$sModuleName' is not derived from UserRightsAddOnAPI");
		}
		self::$m_oAddOn = new $sModuleName;
		self::$m_oAddOn->Init();
		self::ResetCurrentUserData();
	}

	/**
	 * @param string $sModuleName
	 *
	 * @return void
	 * @throws \CoreException
	 */
	public static function SelectSelfRegister($sModuleName)
	{
		if (!class_exists($sModuleName))
		{
			throw new CoreException("Could not select the class, '$sModuleName' for self register, is not a valid class name");
		}
		self::$m_sSelfRegisterAddOn = $sModuleName;
	}

	/**
	 * @return \UserRightsAddOnAPI
	 */
	public static function GetModuleInstance()
	{
		return self::$m_oAddOn;
	}

	/**
	 * Installation: create the very first user
	 *
	 * @param string $sAdminUser
	 * @param string $sAdminPwd
	 * @param string $sLanguage
	 *
	 * @return bool
	 */
	public static function CreateAdministrator($sAdminUser, $sAdminPwd, $sLanguage = 'EN US')
	{
		$bRes = self::$m_oAddOn->CreateAdministrator($sAdminUser, $sAdminPwd, $sLanguage);
		self::FlushPrivileges(true /* reset admin cache */);
		return $bRes;
	}

	/**
	 * @return bool
	 */
	public static function IsLoggedIn()
	{
		if (self::$m_oUser == null)
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	/**
	 * Set the current user (as part of the login process)
	 *
	 * @param string $sLogin Login of the concerned user
	 * @param string $sAuthentication
	 *
	 * @return bool
	 * @throws \DictExceptionUnknownLanguage
	 * @throws \OQLException
	 */
	public static function Login($sLogin, $sAuthentication = 'any')
	{
		self::ResetCurrentUserData();

		$oUser = self::FindUser($sLogin, $sAuthentication);
		if (is_null($oUser))
		{
			return false;
		}
		self::$m_oUser = $oUser;

		if (Session::IsSet('impersonate_user'))
		{
			self::$m_oRealUser = self::$m_oUser;
			self::$m_oUser = self::FindUser(Session::Get('impersonate_user'));
		}

		Dict::SetUserLanguage(self::GetUserLanguage());
		return true;
	}

	/**
	 * Reset current user and cleanup associated SESSION data
	 *
	 * @return void
	 * @since 3.0.4 3.1.1 3.2.0
	 */
	public static function Logoff()
	{
		self::ResetCurrentUserData();
		Dict::SetUserLanguage(null);
		self::_ResetSessionCache();
	}

	/**
	 * @param string $sLogin Login of the user to check the credentials for
	 * @param string $sPassword
	 * @param string $sLoginMode
	 * @param string $sAuthentication
	 *
	 * @return bool
	 * @throws \OQLException
	 */
	public static function CheckCredentials($sLogin, $sPassword, $sLoginMode = 'form', $sAuthentication = 'any')
	{
		$oUser = self::FindUser($sLogin, $sAuthentication);
		if (is_null($oUser))
		{
			// Check if the user does not exist at all or if it is just disabled
			if (self::FindUser($sLogin, $sAuthentication, true) == null)
			{
				// User does not exist at all
				$bCheckCredentialsAndCreateUser = self::CheckCredentialsAndCreateUser($sLogin, $sPassword, $sLoginMode, $sAuthentication);
				self::$m_sLastLoginStatus = array('sName' => $sLogin, 'bSuccess' => $bCheckCredentialsAndCreateUser);
				return $bCheckCredentialsAndCreateUser;
			}
			else
			{
				// User is actually disabled
				self::$m_sLastLoginStatus = array('sName' => $sLogin, 'bSuccess' => false);
				return  false;
			}
		}

		if (!$oUser->CheckCredentials($sPassword))
		{
			self::$m_sLastLoginStatus = array('sName' => $sLogin, 'bSuccess' => false);
			return false;
		}
		self::UpdateUser($oUser, $sLoginMode, $sAuthentication);
		self::$m_sLastLoginStatus = array('sName' => $sLogin, 'bSuccess' => true);

		return true;
	}

	/**
	 * @param string $sName
	 * @param string $sPassword
	 * @param string $sLoginMode
	 * @param string $sAuthentication
	 *
	 * @return mixed
	 */
	public static function CheckCredentialsAndCreateUser($sName, $sPassword, $sLoginMode, $sAuthentication)
	{
		if (self::$m_sSelfRegisterAddOn != null)
		{
			return call_user_func(array(self::$m_sSelfRegisterAddOn, 'CheckCredentialsAndCreateUser'), $sName, $sPassword, $sLoginMode, $sAuthentication);
		}
	}

	/**
	 * @param \User $oUser
	 * @param string $sLoginMode
	 * @param string $sAuthentication
	 */
	public static function UpdateUser($oUser, $sLoginMode, $sAuthentication)
	{
		if (self::$m_sSelfRegisterAddOn != null)
		{
			call_user_func(array(self::$m_sSelfRegisterAddOn, 'UpdateUser'), $oUser, $sLoginMode, $sAuthentication);
		}
	}

	/**
	 * @return bool
	 */
	public static function TrustWebServerContext()
	{
		if (!is_null(self::$m_oUser))
		{
 			return self::$m_oUser->TrustWebServerContext();
		}
		else
		{
			return false;
		}
	}

	/**
	 * Tells whether or not the archive mode is allowed to the current user
	 *
	 * @return bool
	 * @throws \CoreException
	 */
	public static function CanBrowseArchive()
	{
		if (is_null(self::$m_oUser))
		{
			$bRet = false;
		}
		elseif (Session::IsSet('archive_allowed'))
		{
			$bRet = Session::Get('archive_allowed');
		}
		else
		{
			// As of now, anybody can switch to the archive mode as soon as there is an archivable class
			$bRet = (count(MetaModel::EnumArchivableClasses()) > 0);
			Session::Set('archive_allowed', $bRet);
		}
		return $bRet;
	}

	/**
	 * @return bool
	 */
	public static function CanChangePassword()
	{
		if (MetaModel::DBIsReadOnly())
		{
			return false;
		}

		if (!is_null(self::$m_oUser))
		{
 			return self::$m_oUser->CanChangePassword();
		}
		else
		{
			return false;
		}
	}

	/**
	 * @param string $sOldPassword
	 * @param string $sNewPassword
	 * @param string $sLogin Login of the concerned user
	 *
	 * @return bool
	 * @throws \OQLException
	 */
	public static function ChangePassword($sOldPassword, $sNewPassword, $sLogin = '')
	{
		if (empty($sLogin))
		{
			$oUser = self::$m_oUser;
		}
		else
		{
			// find the id out of the login string
			$oUser = self::FindUser($sLogin);
		}
		if (is_null($oUser))
		{
			return false;
		}
		else
		{
			$oUser->AllowWrite(true);
			return $oUser->ChangePassword($sOldPassword, $sNewPassword);
		}
	}

	/**
	 * @param string $sLogin Login identifier of the user to impersonate
	 *
	 * @return bool True if an impersonation occurred
	 * @throws \DictExceptionUnknownLanguage
	 * @throws \OQLException
	 */
	public static function Impersonate($sLogin)
	{
		if (!self::CheckLogin()) return false;

		$bRet = false;
		$oUser = self::FindUser($sLogin);
		if ($oUser)
		{
			$bRet = true;
			if (is_null(self::$m_oRealUser))
			{
				// First impersonation
				self::$m_oRealUser = self::$m_oUser;
			}
			if (self::$m_oRealUser && (self::$m_oRealUser->GetKey() == $oUser->GetKey()))
			{
				// Equivalent to "Deimpersonate"
				self::Deimpersonate();
			}
			else
			{
				// Do impersonate!
				self::$m_oUser = $oUser;
				Dict::SetUserLanguage(self::GetUserLanguage());
				Session::Set('impersonate_user', $sLogin);
				self::_ResetSessionCache();

				//N°5135 - Impersonate: history of changes versus log entries
				//track impersonation inside changelogs
				CMDBObject::SetTrackUserId(null);
				CMDBObject::CreateChange();
			}
		}
		return $bRet;
	}

	/**
	 * @throws \DictExceptionUnknownLanguage
	 */
	public static function Deimpersonate()
	{
		if (!is_null(self::$m_oRealUser))
		{
			self::$m_oUser = self::$m_oRealUser;
			//N°5135 - fix IsImpersonated() after calling Deimpersonate()
			self::$m_oRealUser = null;
			Dict::SetUserLanguage(self::GetUserLanguage());
			Session::Unset('impersonate_user');
			self::_ResetSessionCache();

			//N°5135 - Impersonate: history of changes versus log entries
			//stop tracking impersonation inside changelogs
			CMDBObject::CreateChange();
		}
	}

	/**
	 * @return string connected {@see User} login field value, otherwise empty string
	 */
	public static function GetUser()
	{
		if (is_null(self::$m_oUser))
		{
			return '';
		}
		else
		{
			return self::$m_oUser->Get('login');
		}
	}

	/**
	 * @return \User|null
	 */
	public static function GetUserObject()
	{
		if (is_null(self::$m_oUser))
		{
			return null;
		}
		else
		{
			return self::$m_oUser;
		}
	}

	/**
	 * @param Person $oPerson Person we try to match against Users contact (also Person objects)
	 * @param bool $bMustBeUnique If true, return null when 2+ Users matching this Person were found. Otherwise return the first one
	 *
	 * @return \DBObject|null
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @since 3.0.0
	 */
	public static function GetUserFromPerson(Person $oPerson, bool $bMustBeUnique = true): ?DBObject
	{
		$sUserSearch = 'SELECT User WHERE contactid = :id';
		$oUserSearch = DBObjectSearch::FromOQL($sUserSearch);
		$oUserSearch->AllowAllData();
		$oUserSet = new DBObjectSet($oUserSearch, array(), array('id' => $oPerson->GetKey()));
		if($oUserSet->Count() > 0 && !($oUserSet->Count() > 1 && $bMustBeUnique)){
			return $oUserSet->Fetch();
		}
		return null;
	}

	/**
	 * @return string
	 */
	public static function GetUserLanguage()
	{
		if (is_null(self::$m_oUser))
		{
			return 'EN US';

		}
		else
		{
			return self::$m_oUser->Get('language');
		}
	}

	/**
	 * @param string $sLogin
	 *
	 * @return string|null
	 * @throws \OQLException
	 */
	public static function GetUserId($sLogin = '')
	{
		if (empty($sLogin))
		{
			// return current user id
			if (is_null(self::$m_oUser))
			{
				return null;
			}
			return self::$m_oUser->GetKey();
		}
		else
		{
			// find the id out of the login string
			$oUser = self::FindUser($sLogin);
			if (is_null($oUser))
			{
				return null;
			}
			return $oUser->GetKey();
		}
	}

	/**
	 * @param string $sLogin Login of the user from which we return the picture URL
	 * @param bool $bAllowDefaultPicture Set to false if you want it to return null instead of the default picture URL when the contact has no picture defined. This can be useful when we want to display something else than the default picture (eg. initials)
	 *
	 * @return null|string Absolute URL of the user picture (from their contact if they have one, or from the preferences)
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \Exception
	 * @since 3.0.0
	 */
	public static function GetUserPictureAbsUrl($sLogin = '', $bAllowDefaultPicture = true)
	{
		$sUserPicturesFolder = 'images/user-pictures/';
		$sUserPicturePlaceholderPrefKey = 'user_picture_placeholder';
		$sPictureUrl = null;

		// First, check cache
		if (array_key_exists($sLogin, static::$m_aCacheContactPictureAbsUrl)) {
			return static::$m_aCacheContactPictureAbsUrl[$sLogin];
		}

		// Then check if the user has a contact attached and if it has an picture defined
		$sContactId = UserRights::GetContactId($sLogin);
		if (!empty($sContactId)) {
			$oContact = null;
			// Picture if generally for Person, so try it first
			if (MetaModel::IsValidClass('Person')) {
				$oContact = MetaModel::GetObject('Person', $sContactId, false, true);
			}
			if (is_null($oContact)) {
				$oContact = MetaModel::GetObject('Contact', $sContactId, false, true);
			}
			$sContactClass = get_class($oContact);

			// Check that Contact object still exists and that Contact class has a picture attribute
			// - Try to get the semantic image attribute, or try to fallback on the default one if none defined
			$sContactPictureAttCode = MetaModel::HasImageAttributeCode($sContactClass) ? MetaModel::GetImageAttributeCode($sContactClass) : static::DEFAULT_CONTACT_PICTURE_ATTCODE;
			if (!is_null($oContact) && MetaModel::IsValidAttCode($sContactClass, $sContactPictureAttCode)) {
				/** @var \ormDocument $oPicture */
				$oPicture = $oContact->Get($sContactPictureAttCode);
				if ($oPicture->IsEmpty()) {
					if ($bAllowDefaultPicture === true) {
						/** @var \AttributeImage $oAttDef */
						$oAttDef = MetaModel::GetAttributeDef($sContactClass, $sContactPictureAttCode);
						$sPictureUrl = $oAttDef->Get('default_image');
					} else {
						$sPictureUrl = null;
					}
				} else {
					if (ContextTag::Check(ContextTag::TAG_PORTAL)) {
						$sSignature = $oPicture->GetSignature();
						$sPictureUrl = utils::GetAbsoluteUrlAppRoot().'pages/exec.php/object/document/display/'.$sContactClass.'/'.$oContact->GetKey().'/'.$sContactPictureAttCode.'?cache=86400&s='.$sSignature.'&exec_module=itop-portal-base&exec_page=index.php&portal_id='.PORTAL_ID;
					} else {
						$sPictureUrl = $oPicture->GetDisplayURL($sContactClass, $oContact->GetKey(), $sContactPictureAttCode);
					}
				}
			}
		}
		// If no contact & empty login, check if current user has a placeholder in they preferences
		elseif ('' === $sLogin) {
			$sPlaceholderPictureFilename = appUserPreferences::GetPref($sUserPicturePlaceholderPrefKey, null, static::GetUserId($sLogin));
			if (!empty($sPlaceholderPictureFilename)) {
				$sPictureUrl = utils::GetAbsoluteUrlAppRoot().$sUserPicturesFolder.$sPlaceholderPictureFilename;
			}
		}
		// Else, no contact and no login, then it's for an unknown origin (system, extension, ...)

		// Then, the default picture
		if (utils::IsNullOrEmptyString($sPictureUrl) && $bAllowDefaultPicture === true) {
			$sPictureUrl = utils::GetAbsoluteUrlAppRoot().$sUserPicturesFolder.'user-profile-default-256px.png';
		}

		// Update cache
		static::$m_aCacheContactPictureAbsUrl[$sLogin] = $sPictureUrl;

		return $sPictureUrl;
	}

	/**
	 * @param string $sLogin Login of the user from which we return the contact ID
	 *
	 * @return string
	 * @throws \Exception
	 */
	public static function GetContactId($sLogin = '')
	{
		if (empty($sLogin))
		{
			$oUser = self::$m_oUser;
		}
		else
		{
			$oUser = self::FindUser($sLogin);
		}
		if (is_null($oUser))
		{
			return '';
		}
		if (!MetaModel::IsValidAttCode(get_class($oUser), static::DEFAULT_USER_CONTACT_ID_ATTCODE))
		{
			return '';
		}
		return $oUser->Get(static::DEFAULT_USER_CONTACT_ID_ATTCODE);
	}

	/**
	 * Return the organization name of the current user's contact.
	 * If the user has no contact linked, null is returned.
	 *
	 * @return string|null
	 * @throws \Exception
	 * @since 3.0.0
	 */
	public static function GetContactOrganizationFriendlyname()
	{
		$sOrgFriendlyname = null;

		$oContact = static::GetContactObject();
		if(!is_null($oContact) && MetaModel::IsValidAttCode(get_class($oContact), static::DEFAULT_CONTACT_ORG_ID_FRIENDLYNAME_ATTCODE))
		{
			$sOrgFriendlyname = $oContact->Get(static::DEFAULT_CONTACT_ORG_ID_FRIENDLYNAME_ATTCODE);
		}

		return $sOrgFriendlyname;
	}

	/**
	 * Return the first name of the current user's contact.
	 * If the user has no contact, null is returned.
	 *
	 * @return string|null
	 * @throws \Exception
	 * @since 3.0.0
	 */
	public static function GetContactFirstname()
	{
		$sFirstname = null;

		$oContact = static::GetContactObject();
		if(!is_null($oContact) && MetaModel::IsValidAttCode(get_class($oContact), static::DEFAULT_CONTACT_FIRSTNAME_ATTCODE))
		{
			$sFirstname = $oContact->Get(static::DEFAULT_CONTACT_FIRSTNAME_ATTCODE);
		}

		return $sFirstname;
	}

	/**
	 * Return the friendlyname of the current user's contact.
	 * If the user has no contact, null is returned.
	 *
	 * @return string|null
	 * @throws \Exception
	 * @since 3.0.0
	 */
	public static function GetContactFriendlyname()
	{
		$sFriendlyname = null;

		$oContact = static::GetContactObject();
		if(!is_null($oContact))
		{
			$sFriendlyname = $oContact->GetRawName();
		}

		return $sFriendlyname;
	}

	/**
	 * @return \DBObject|null
	 */
	public static function GetContactObject()
	{
		if (is_null(self::$m_oUser))
		{
			return null;
		}
		else
		{
			return self::$m_oUser->GetContactObject();
		}
	}

	/**
	 * Render the user name in best effort mode
	 *
	 * @param string $sLogin Login of the user we want to retrieve the friendlyname
	 *
	 * @return string
	 * @throws \OQLException
	 */
	public static function GetUserFriendlyName($sLogin = '')
	{
		if (empty($sLogin))
		{
			$oUser = self::$m_oUser;
		}
		else
		{
			$oUser = self::FindUser($sLogin);
		}
		if (is_null($oUser))
		{
			return '';
		}
		return $oUser->GetFriendlyName();
	}

	/**
	 * Render the user initials in best effort mode (first letter of first word + first letter of any other word if capitalized)
	 *
	 * @param string $sLogin Login of the user from which we want to retrieve the initials
	 *
	 * @return string
	 * @throws \OQLException
	 * @since 3.0.0
	 */
	public static function GetUserInitials($sLogin = '')
	{
		if (empty($sLogin)) {
			$oUser = self::$m_oUser;
		} else {
			$oUser = self::FindUser($sLogin);
		}
		if (is_null($oUser)) {
			return utils::ToAcronym($sLogin);
		}

		return $oUser->GetInitials();
	}

	/**
	 * @return bool
	 */
	public static function IsImpersonated()
	{
		if (is_null(self::$m_oRealUser))
		{
			return false;
		}
		return true;
	}

	/**
	 * @return string
	 */
	public static function GetRealUser()
	{
		if (is_null(self::$m_oRealUser))
		{
			return '';
		}
		return self::$m_oRealUser->Get('login');
	}

	/**
	 * @return \User|null
	 */
	public static function GetRealUserObject()
	{
		return self::$m_oRealUser;
	}

	/**
	 * @return int|string ID of the connected user : if impersonate then use {@see m_oRealUser}, else {@see m_oUser}. If no user set then return ''
	 * @since 2.6.5 2.7.6 3.0.0 N°4289 method creation
	 */
	public static function GetConnectedUserId() {
		if (false === is_null(static::$m_oRealUser)) {
			return static::$m_oRealUser->GetKey();
		}
		if (false === is_null(static::$m_oUser)) {
			return static::$m_oUser->GetKey();
		}

		return '';
	}

	/**
	 * @return string
	 */
	public static function GetRealUserId()
	{
		if (is_null(self::$m_oRealUser))
		{
			return '';
		}
		return self::$m_oRealUser->GetKey();
	}

	/**
	 * @return string
	 */
	public static function GetRealUserFriendlyName()
	{
		if (is_null(self::$m_oRealUser))
		{
			return '';
		}
		return self::$m_oRealUser->GetFriendlyName();
	}

	/**
	 * @return bool
	 */
	protected static function CheckLogin()
	{
		if (!self::IsLoggedIn())
		{
			//throw new UserRightException('No user logged in', array());
			return false;
		}
		return true;
	}

	/**
	 * Add additional filter for organization silos to all the requests.
	 *
	 * @param string $sClass
	 * @param array $aSettings
	 *
	 * @return bool|\Expression
	 */
	public static function GetSelectFilter($sClass, $aSettings = array())
	{
		// When initializing, we need to let everything pass trough
		if (!self::CheckLogin()) {return true;}

		if (self::IsAdministrator()) {return true;}

		try
		{
			// Check Bug 1436 for details
			if (MetaModel::HasCategory($sClass, 'bizmodel') || MetaModel::HasCategory($sClass, 'silo') || MetaModel::HasCategory($sClass, 'filter'))
			{
				return self::$m_oAddOn->GetSelectFilter(self::$m_oUser, $sClass, $aSettings);
			}
			else
			{
				return true;
			}
		} catch (Exception $e)
		{
			return false;
		}
	}

	/**
	 * @param string $sClass
	 * @param int $iActionCode see UR_ACTION_* constants
	 * @param DBObjectSet $oInstanceSet
	 * @param User $oUser
	 *
	 * @return int (UR_ALLOWED_YES|UR_ALLOWED_NO|UR_ALLOWED_DEPENDS)
	 * @throws \CoreException
	 */
	public static function IsActionAllowed($sClass, $iActionCode, $oInstanceSet = null, $oUser = null)
	{
		// When initializing, we need to let everything pass trough
		if (!self::CheckLogin()) return UR_ALLOWED_YES;

		if (MetaModel::DBIsReadOnly())
		{
			if ($iActionCode == UR_ACTION_CREATE) return UR_ALLOWED_NO;
			if ($iActionCode == UR_ACTION_MODIFY) return UR_ALLOWED_NO;
			if ($iActionCode == UR_ACTION_BULK_MODIFY) return UR_ALLOWED_NO;
			if ($iActionCode == UR_ACTION_DELETE) return UR_ALLOWED_NO;
			if ($iActionCode == UR_ACTION_BULK_DELETE) return UR_ALLOWED_NO;
		}

		$aPredefinedObjects = call_user_func(array($sClass, 'GetPredefinedObjects'));
		if ($aPredefinedObjects != null)
		{
			// As opposed to the read-only DB, modifying an object is allowed
			// (the constant columns will be marked as read-only)
			//
			if ($iActionCode == UR_ACTION_CREATE) return UR_ALLOWED_NO;
			if ($iActionCode == UR_ACTION_DELETE) return UR_ALLOWED_NO;
			if ($iActionCode == UR_ACTION_BULK_DELETE) return UR_ALLOWED_NO;
		}

		if (self::IsAdministrator($oUser)) return UR_ALLOWED_YES;

		if (MetaModel::HasCategory($sClass, 'bizmodel') || MetaModel::HasCategory($sClass, 'grant_by_profile'))
		{
			if (is_null($oUser))
			{
				$oUser = self::$m_oUser;
			}
			if ($iActionCode == UR_ACTION_CREATE)
			{
				// The addons currently DO NOT handle the case "CREATE"
				// Therefore it is considered to be equivalent to "MODIFY"
				$iActionCode = UR_ACTION_MODIFY;
			}
			return self::$m_oAddOn->IsActionAllowed($oUser, $sClass, $iActionCode, $oInstanceSet);
		}
		elseif(($iActionCode == UR_ACTION_READ) && MetaModel::HasCategory($sClass, 'view_in_gui'))
		{
			return UR_ALLOWED_YES;
		}
		else
		{
			// Other classes could be edited/listed by the administrators
			return UR_ALLOWED_NO;
		}
	}

	/**
	 * @param string $sClass
	 * @param string $sStimulusCode
	 * @param \DBObjectSet|null $oInstanceSet
	 * @param \User|null $oUser
	 *
	 * @return bool
	 * @throws \CoreException
	 */
	public static function IsStimulusAllowed($sClass, $sStimulusCode, /*dbObjectSet*/ $oInstanceSet = null, $oUser = null)
	{
		// When initializing, we need to let everything pass trough
		if (!self::CheckLogin()) return true;

		if (MetaModel::DBIsReadOnly())
		{
			return false;
		}

		if (self::IsAdministrator($oUser)) return true;

		if (MetaModel::HasCategory($sClass, 'bizmodel'))
		{
			if (is_null($oUser))
			{
				$oUser = self::$m_oUser;
			}
			return self::$m_oAddOn->IsStimulusAllowed($oUser, $sClass, $sStimulusCode, $oInstanceSet);
		}
		else
		{
			// Other classes could be edited/listed by the administrators
			return false;
		}
	}

	/**
	 * @param string $sClass
	 * @param string $sAttCode
	 * @param int $iActionCode
	 * @param \DBObjectSet $oInstanceSet
	 * @param \User $oUser
	 *
	 * @return int (UR_ALLOWED_YES|UR_ALLOWED_NO)
	 * @throws \CoreException
	 */
	public static function IsActionAllowedOnAttribute($sClass, $sAttCode, $iActionCode, /*dbObjectSet*/$oInstanceSet = null, $oUser = null)
	{
		// When initializing, we need to let everything pass trough
		if (!self::CheckLogin()) return UR_ALLOWED_YES;

		if (MetaModel::DBIsReadOnly())
		{
			if ($iActionCode == UR_ACTION_MODIFY) return UR_ALLOWED_NO;
			if ($iActionCode == UR_ACTION_DELETE) return UR_ALLOWED_NO;
			if ($iActionCode == UR_ACTION_BULK_MODIFY) return UR_ALLOWED_NO;
			if ($iActionCode == UR_ACTION_BULK_DELETE) return UR_ALLOWED_NO;
		}

		if (self::IsAdministrator($oUser)) return UR_ALLOWED_YES;

		if (MetaModel::HasCategory($sClass, 'bizmodel') || MetaModel::HasCategory($sClass, 'grant_by_profile'))
		{
			if (is_null($oUser))
			{
				$oUser = self::$m_oUser;
			}
			return self::$m_oAddOn->IsActionAllowedOnAttribute($oUser, $sClass, $sAttCode, $iActionCode, $oInstanceSet);
		}

		// this module is forbidden for non admins
		if (MetaModel::HasCategory($sClass, 'addon/userrights')) return UR_ALLOWED_NO;

		// the rest is allowed
		return UR_ALLOWED_YES;


	}

	/**
	 * @param \User|null $oUser
	 *
	 * @return bool
	 */
	public static function IsAdministrator($oUser = null)
	{
		if (!self::CheckLogin()) return false;

		if (is_null($oUser))
		{
			$oUser = self::$m_oUser;
		}
		$iUser = $oUser->GetKey();
		if (!isset(self::$m_aAdmins[$iUser]))
		{
			self::$m_aAdmins[$iUser] = self::$m_oAddOn->IsAdministrator($oUser);
		}
		return self::$m_aAdmins[$iUser];
	}

	/**
	 * @param \User|null $oUser
	 *
	 * @return bool
	 */
	public static function IsPortalUser($oUser = null)
	{
		if (!self::CheckLogin()) return false;

		if (is_null($oUser))
		{
			$oUser = self::$m_oUser;
		}
		$iUser = $oUser->GetKey();
		if (!isset(self::$m_aPortalUsers[$iUser]))
		{
			self::$m_aPortalUsers[$iUser] = self::$m_oAddOn->IsPortalUser($oUser);
		}
		return self::$m_aPortalUsers[$iUser];
	}

	/**
	 * @return array
	 */
	public static function GetAllowedPortals()
    {
        $aAllowedPortals = array();
        $aPortalsConf = PortalDispatcherData::GetData();
        $aDispatchers = array();
        foreach ($aPortalsConf as $sPortalId => $aConf)
        {
            $sHandlerClass = $aConf['handler'];
            $aDispatchers[$sPortalId] = new $sHandlerClass($sPortalId);
        }

        foreach ($aDispatchers as $sPortalId => $oDispatcher)
        {
            if ($oDispatcher->IsUserAllowed())
            {
                $aAllowedPortals[] = array(
                    'id' => $sPortalId,
                    'label' => $oDispatcher->GetLabel(),
                    'url' => $oDispatcher->GetUrl(),
                );
            }
        }
        return $aAllowedPortals;
    }

	/**
	 * @see UR_ACTION_READ, UR_ACTION_MODIFY, ...
	 *
	 * @param int $iActionCode
	 * @param array $aCategories
	 * @param bool $bWithLabels
	 * @param \User|null $oUser
	 *
	 * @return array
	 * @throws \DictExceptionMissingString
	 * @throws \CoreException
	 */
    public static function GetAllowedClasses($iActionCode, $aCategories = array('bizmodel'), $bWithLabels = false, $oUser = null)
    {
    	$aAllowedClasses = [];
    	foreach(MetaModel::GetClasses(implode(',', $aCategories)) as $sClass)
	    {
	    	if(static::IsActionAllowed($sClass, $iActionCode, null, $oUser) === UR_ALLOWED_YES)
		    {
		    	if($bWithLabels)
			    {
			    	$aAllowedClasses[$sClass] = MetaModel::GetName($sClass);
			    }
		    	else
			    {
		    	    $aAllowedClasses[] = $sClass;
			    }
		    }
	    }

    	// Sort by label
    	if($bWithLabels)
	    {
	    	asort($aAllowedClasses);
	    }

    	return $aAllowedClasses;
    }

	/**
	 * @param \User|null $oUser
	 *
	 * @return array|mixed
	 */
    public static function ListProfiles($oUser = null)
	{
		if (is_null($oUser))
		{
			$oUser = self::$m_oUser;
		}
		if ($oUser === null)
		{
			// Not logged in: no profile at all
			$aProfiles = array();
		}
		elseif ((self::$m_oUser !== null) && ($oUser->GetKey() == self::$m_oUser->GetKey()))
		{
			// Data about the current user can be found into the session data
			if (Session::IsSet('profile_list'))
			{
				$aProfiles = Session::Get('profile_list');
			}
		}

		if (!isset($aProfiles))
		{
			$aProfiles = self::$m_oAddOn->ListProfiles($oUser);
		}
		return $aProfiles;
	}

	/**
	 * @param string $sProfileName Profile name to search for
	 * @param User|null $oUser
	 *
	 * @return bool
	 */
	public static function HasProfile($sProfileName, $oUser = null)
	{
		$bRet = in_array($sProfileName, self::ListProfiles($oUser));
		return $bRet;
	}

	/**
	 * Reset cached data
	 * @param Bool Reset admin cache as well
	 * @return void
	 */
	public static function FlushPrivileges($bResetAdminCache = false)
	{
		if ($bResetAdminCache)
		{
			self::$m_aAdmins = array();
			self::$m_aPortalUsers = array();
		}
		self::_ResetSessionCache();
		if (self::$m_oAddOn)
		{
			self::$m_oAddOn->FlushPrivileges();
		}
	}

	/**
	 * Find a user based on its login and its type of authentication
	 *
	 * @param string $sLogin Login/identifier of the user
	 * @param string $sAuthentication Type of authentication used: internal|external|any
	 * @param bool $bAllowDisabledUsers Whether or not to retrieve disabled users (status != enabled)
	 *
	 * @return User The found user or null
	 * @throws \OQLException
	 */
	protected static function FindUser($sLogin, $sAuthentication = 'any', $bAllowDisabledUsers = false)
	{
		if ($sAuthentication == 'any')
		{
			$oUser = self::FindUser($sLogin, 'internal');
			if ($oUser == null)
			{
				$oUser = self::FindUser($sLogin, 'external');
			}
		}
		else
		{
			if (!isset(self::$m_aCacheUsers))
			{
				self::$m_aCacheUsers = array('internal' => array(), 'external' => array());
			}

			if (!isset(self::$m_aCacheUsers[$sAuthentication][$sLogin]))
			{
				switch($sAuthentication)
				{
					case 'external':
					$sBaseClass = 'UserExternal';
					break;

					case 'internal':
					$sBaseClass = 'UserInternal';
					break;

					default:
					echo "<p>sAuthentication = $sAuthentication</p>\n";
					assert(false); // should never happen
				}
				$oSearch = DBObjectSearch::FromOQL("SELECT $sBaseClass WHERE login = :login");
				$oSearch->AllowAllData();
				if (!$bAllowDisabledUsers)
				{
					$oSearch->AddCondition('status', 'enabled');
				}
				$oSet = new DBObjectSet($oSearch, array(), array('login' => $sLogin));
				$oUser = $oSet->fetch();
				self::$m_aCacheUsers[$sAuthentication][$sLogin] = $oUser;
			}
			$oUser = self::$m_aCacheUsers[$sAuthentication][$sLogin];
		}
		return $oUser;
	}

	/**
	 * @param string$sClass
	 * @param array $aAllowedOrgs
	 * @param array $aSettings
	 * @param string|null $sAttCode
	 *
	 * @return \DBObjectSearch
	 * @throws \CoreException
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 */
	public static function MakeSelectFilter($sClass, $aAllowedOrgs, $aSettings = array(), $sAttCode = null)
	{
		return self::$m_oAddOn->MakeSelectFilter($sClass, $aAllowedOrgs, $aSettings, $sAttCode);
	}

	public static function _InitSessionCache()
	{
		// Cache data about the current user into the session
		Session::Set('profile_list', self::ListProfiles());

		$oConfig = MetaModel::GetConfig();
		$bSessionIdRegeneration = $oConfig->Get('regenerate_session_id_enabled');
		if ($bSessionIdRegeneration)
		{
			// Protection against session fixation/injection: generate a new session id.

			// Alas a PHP bug (technically a bug in the memcache session handler, https://bugs.php.net/bug.php?id=71187)
			// causes session_regenerate_id to fail with a catchable fatal error in PHP 7.0 if the session handler is memcache(d).
			// The bug has been fixed in PHP 7.2, but in case session_regenerate_id()
			// fails we just silently ignore the error and keep the same session id...
			$old_error_handler = set_error_handler(array(__CLASS__, 'VoidErrorHandler'));
			Session::RegenerateId(true);
			if ($old_error_handler !== null) {
				set_error_handler($old_error_handler);
			}
		}
	}

	public static function _ResetSessionCache()
	{
		Session::Unset('profile_list');
		Session::Unset('archive_allowed');
	}

	/**
	 * Fake error handler to silently discard fatal errors
	 * @param int $iErrNo
	 * @param string $sErrStr
	 * @param string $sErrFile
	 * @param int $iErrLine
	 * @return boolean
	 */
	public static function VoidErrorHandler($iErrno, $sErrStr, $sErrFile, $iErrLine)
	{
		return true; // Ignore the error
	}

	/**
	 * @return null|array The last login/result (null if none has failed) the array has this structure : array('sName' => $sName, 'bSuccess' => $bSuccess);
	 */
	public static function GetLastLoginStatus()
	{
		return self::$m_sLastLoginStatus;
	}
}

/**
 * Helper class to get the number/list of items for which a given action is allowed/possible
 */
class ActionChecker
{
	var $oFilter;
	var $iActionCode;
	var $iAllowedCount = null;
	var $aAllowedIDs = null;

	public function __construct(DBSearch $oFilter, $iActionCode)
	{
		$this->oFilter = $oFilter;
		$this->iActionCode = $iActionCode;
		$this->iAllowedCount = null;
		$this->aAllowedIDs = null;
	}

	/**
	 * returns the number of objects for which the action is allowed
	 * @return integer The number of "allowed" objects 0..N
	 */
	public function GetAllowedCount()
	{
		if ($this->iAllowedCount == null) $this->CheckObjects();
		return $this->iAllowedCount;
	}

	/**
	 * If IsAllowed returned UR_ALLOWED_DEPENDS, this methods returns
	 * an array of ObjKey => Status (true|false)
	 * @return array
	 */
	public function GetAllowedIDs()
	{
		if ($this->aAllowedIDs == null) $this->IsAllowed();
		return $this->aAllowedIDs;
	}

	/**
	 * Check if the speficied stimulus is allowed for the set of objects
	 * @return UR_ALLOWED_YES, UR_ALLOWED_NO or UR_ALLOWED_DEPENDS
	 */
	public function IsAllowed()
	{
		$sClass = $this->oFilter->GetClass();
		$oSet = new DBObjectSet($this->oFilter);
		$iActionAllowed = UserRights::IsActionAllowed($sClass, $this->iActionCode, $oSet);
		if ($iActionAllowed == UR_ALLOWED_DEPENDS)
		{
			// Check for each object if the action is allowed or not
			$this->aAllowedIDs = array();
			$oSet->Rewind();
			$this->iAllowedCount = 0;
			while($oObj = $oSet->Fetch())
			{
				$oObjSet = DBObjectSet::FromArray($sClass, array($oObj));
				if (UserRights::IsActionAllowed($sClass, $this->iActionCode, $oObjSet) == UR_ALLOWED_NO)
				{
					$this->aAllowedIDs[$oObj->GetKey()] = false;
				}
				else
				{
					// Assume UR_ALLOWED_YES, since there is just one object !
					$this->aAllowedIDs[$oObj->GetKey()] = true;
					$this->iAllowedCount++;
				}
			}
		}
		else if ($iActionAllowed == UR_ALLOWED_YES)
		{
			$this->iAllowedCount = $oSet->Count();
			$this->aAllowedIDs = array(); // Optimization: not filled when Ok for all objects
		}
		else // UR_ALLOWED_NO
		{
			$this->iAllowedCount = 0;
			$this->aAllowedIDs = array();
		}
		return $iActionAllowed;
	}
}

/**
 * Helper class to get the number/list of items for which a given stimulus can be applied (allowed & possible)
 */
class StimulusChecker extends ActionChecker
{
	var $sState = null;

	public function __construct(DBSearch $oFilter, $sState, $iStimulusCode)
	{
		parent::__construct($oFilter, $iStimulusCode);
		$this->sState = $sState;
	}

	/**
	 * Check if the speficied stimulus is allowed for the set of objects
	 * @return UR_ALLOWED_YES, UR_ALLOWED_NO or UR_ALLOWED_DEPENDS
	 */
	public function IsAllowed()
	{
		$sClass = $this->oFilter->GetClass();
		if (MetaModel::IsAbstract($sClass)) return UR_ALLOWED_NO; // Safeguard, not implemented if the base class of the set is abstract !

		$oSet = new DBObjectSet($this->oFilter);
		$iActionAllowed = UserRights::IsStimulusAllowed($sClass,  $this->iActionCode, $oSet);
		if ($iActionAllowed == UR_ALLOWED_NO)
		{
			$this->iAllowedCount = 0;
			$this->aAllowedIDs = array();
		}
		else // Even if UR_ALLOWED_YES, we need to check if each object is in the appropriate state
		{
			// Hmmm, may not be needed right now because we limit the "multiple" action to object in
			// the same state... may be useful later on if we want to extend this behavior...

			// Check for each object if the action is allowed or not
			$this->aAllowedIDs = array();
			$oSet->Rewind();
			$iAllowedCount = 0;
			$iActionAllowed = UR_ALLOWED_DEPENDS;
			while($oObj = $oSet->Fetch())
			{
				$aTransitions = $oObj->EnumTransitions();
				if (array_key_exists($this->iActionCode, $aTransitions))
				{
					// Temporary optimization possible: since the current implementation
					// of IsActionAllowed does not perform a 'per instance' check, we could
					// skip this second validation phase and assume it would return UR_ALLOWED_YES
					$oObjSet = DBObjectSet::FromArray($sClass, array($oObj));
					if (!UserRights::IsStimulusAllowed($sClass, $this->iActionCode, $oObjSet))
					{
						$this->aAllowedIDs[$oObj->GetKey()] = false;
					}
					else
					{
						// Assume UR_ALLOWED_YES, since there is just one object !
						$this->aAllowedIDs[$oObj->GetKey()] = true;
						$this->iState = $oObj->GetState();
						$this->iAllowedCount++;
					}
				}
				else
				{
					$this->aAllowedIDs[$oObj->GetKey()] = false;
				}
			}
		}

		if ($this->iAllowedCount == $oSet->Count())
		{
			$iActionAllowed = UR_ALLOWED_YES;
		}
		if ($this->iAllowedCount == 0)
		{
			$iActionAllowed = UR_ALLOWED_NO;
		}

		return $iActionAllowed;
	}

	public function GetState()
	{
		return $this->iState;
	}
}
