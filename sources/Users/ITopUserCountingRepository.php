<?php

namespace Combodo\iTop\Users;

use ArchivedObjectException;
use CoreException;
use CoreUnexpectedValue;
use DBObjectSearch;
use DBObjectSet;
use DBUnionSearch;
use Dict;
use DictExceptionMissingString;
use DictExceptionUnknownLanguage;
use Exception;
use IssueLog;
use MetaModel;
use MySQLException;
use OQLException;
use User;
use UserRights;

/**
 *
 */
class ITopUserCountingRepository
{
	/**
	 * @param array $aExcludedUsers
	 * @param array $aExcludedProfiles
	 * @param bool $bAllData
	 * @param array $aExcludedFinalClasses
	 *
	 * @return array
	 * @throws CoreException
	 * @throws CoreUnexpectedValue
	 * @throws DictExceptionMissingString
	 * @throws MySQLException
	 * @throws Exception
	 */
	public function GetConsoleUsers(array $aExcludedUsers = [], array $aExcludedProfiles = [], bool $bAllData = true, array $aExcludedFinalClasses = ['UserToken', 'UserRemoteSaaS']): array
	{
		$sExcludedUsers = $this->ArrayToOQLStringParameter($aExcludedUsers);
		$sExcludedProfiles = $this->ArrayToOQLStringParameter($aExcludedProfiles);
		$sExcludedFinalClasses = $this->ArrayToOQLStringParameter($aExcludedFinalClasses);

		$sOQLUserConsole = "
		SELECT User AS u
		WHERE u.status != 'disabled'
		AND u.login NOT IN ($sExcludedUsers)
		AND u.finalclass NOT IN ($sExcludedFinalClasses)
		AND id NOT IN (
			SELECT User AS uex
			JOIN URP_UserProfile AS uup ON uup.userid = uex.id
			JOIN URP_Profiles AS up ON uup.profileid = up.id
			WHERE up.name IN ($sExcludedProfiles))
	";
		try {
			$oFilter = $bAllData ? DBObjectSearch::FromOQL_AllData($sOQLUserConsole) : DBObjectSearch::FromOQL($sOQLUserConsole);
		} catch (Exception $e) {
			IssueLog::Error('Core:GetConsoleUsersQuota:Error : '.$e->getMessage());
			throw new Exception(Dict::Format('Core:GetQuota:Error', Dict::S('Core:ConsoleUsers')));
		}

		$aConsoleUsers = $this->GetUsersFromFilter($oFilter);
		$aPortalUsers = $this->GetPortalUsers();
		$aReadOnlyUsers = $this->GetReadOnlyUsers();

		return array_diff($aConsoleUsers, $aPortalUsers, $aReadOnlyUsers);
	}

	private function ArrayToOQLStringParameter(array $aValues): string
	{
		$aQuotedValues = [];
		foreach ($aValues as $value) {
			$value = trim((string) $value);
			if ($value === '') {
				continue;
			}
			$aQuotedValues[] = "'".addslashes($value)."'";
		}

		return empty($aQuotedValues) ? "''" : implode(', ', $aQuotedValues);
	}

	/**
	 * @throws CoreUnexpectedValue
	 * @throws CoreException
	 * @throws MySQLException
	 */
	public function GetUsersFromFilter(DBObjectSearch|DBUnionSearch|null $oFilter, array $aOrderBy = [], array $aArgs = []): array
	{
		$aUsers = [];
		if (is_null($oFilter)) {
			return $aUsers;
		}
		$oSet = new DBObjectSet($oFilter, $aOrderBy, $aArgs);
		while ($oUser = $oSet->Fetch()) {
			$aUsers[] = $oUser;
		}

		return $aUsers;
	}

	/**
	 * @throws Exception
	 */
	public function GetPortalUsers(bool $bAllData = true): array
	{
		$sOQLPortalUser = "
        SELECT User AS u
        JOIN URP_UserProfile AS uup ON uup.userid = u.id
        JOIN URP_Profiles AS up ON uup.profileid = up.id
		WHERE up.id = '2' AND u.status != 'disabled' ";

		try {
			$oFilter = $bAllData ? DBObjectSearch::FromOQL_AllData($sOQLPortalUser) : DBObjectSearch::FromOQL($sOQLPortalUser);
		} catch (Exception $e) {
			IssueLog::Error('Core:GetConsoleUsersQuota:Error : '.$e->getMessage());
			throw new Exception(Dict::Format('Core:GetQuota:Error', Dict::S('Core:ApplicationUsers')));
		}

		return $this->GetUsersFromFilter($oFilter);
	}

	/**
	 * @throws DictExceptionMissingString
	 * @throws CoreException
	 * @throws Exception
	 */
	public function GetReadOnlyUsers(): array
	{
		$aReadOnlyUsers = [];
		$aAllUsers = $this->GetAllUsers();
		/** @var User $oUser */
		foreach ($aAllUsers as $oUser) {
			$bIsReadOnlyUser = true;
			if (!$this->IsUserReadOnly($oUser, 'bizmodel') ||
				!$this->IsUserReadOnly($oUser, 'grant_by_profile')) {
				$bIsReadOnlyUser = false;
			}
			if ($bIsReadOnlyUser) {
				$aReadOnlyUsers[] = $oUser;
			}
		}

		// remove portal users
		$aPortalUsers = $this->GetPortalUsers();
		$aReadOnlyUsers = array_diff($aReadOnlyUsers, $aPortalUsers);
		// remove disabled users
		$aDisabledUsers = $this->GetDisabledUsers();

		return array_diff($aReadOnlyUsers, $aDisabledUsers);
	}

	/**
	 * @throws Exception
	 */
	public function GetAllUsers(bool $bAllData = true): array
	{
		$sOqlUser = 'SELECT User';

		try {
			$oFilter = $bAllData ? DBObjectSearch::FromOQL_AllData($sOqlUser) : DBObjectSearch::FromOQL($sOqlUser);
		} catch (Exception $e) {
			IssueLog::Error('combodo-users-quota-slave/GetUsersNotInQuota : '.$e->getMessage(), 'combodo-users-quota');
			throw new Exception(Dict::S('CombodoUserQuota:Error'));
		}

		return $this->GetUsersFromFilter($oFilter);
	}

	/**
	 * @throws CoreException
	 * @throws MySQLException
	 * @throws CoreUnexpectedValue
	 * @throws OQLException
	 * @throws ArchivedObjectException
	 * @throws DictExceptionUnknownLanguage
	 */
	private function IsUserReadOnly(User $oUser, string $sClassCategory): bool
	{
		if ($oUser->Get('status') == 'disabled') {
			return false;
		}

		// check if user is a portal user
		$oProfileLinks = $oUser->Get('profile_list');
		while ($oLink = $oProfileLinks->Fetch()) {
			$iProfileId = $oLink->Get('profileid');
			if (!$iProfileId) {
				continue;
			}
			$oProfile = MetaModel::GetObject('URP_Profiles', $iProfileId, false);
			if ($oProfile && $oProfile->Get('name') === PORTAL_PROFILE_NAME) {
				return false;
			}
		}

		// login (mandatory to compute rights)
		UserRights::Login($oUser->GetName());

		foreach (MetaModel::GetClasses($sClassCategory) as $sClass) {
			// no need to check stimuli for now since users can't execute stimulus without UR_ACTION_MODIFY
			if (
				UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY, null, $oUser) ||
				UserRights::IsActionAllowed($sClass, UR_ACTION_BULK_MODIFY, null, $oUser) ||
				UserRights::IsActionAllowed($sClass, UR_ACTION_DELETE, null, $oUser) ||
				UserRights::IsActionAllowed($sClass, UR_ACTION_BULK_DELETE, null, $oUser)
			) {
				UserRights::Logoff();
				return false;
			}
		}
		UserRights::Logoff();
		return true;
	}

	/**
	 * @throws Exception
	 */
	public function GetDisabledUsers(bool $bAllData = true): array
	{
		$sOQLDisabledUser = "
		SELECT User AS u
		WHERE u.status = 'disabled'
		";
		try {
			$oFilter = $bAllData ? DBObjectSearch::FromOQL_AllData($sOQLDisabledUser) : DBObjectSearch::FromOQL($sOQLDisabledUser);
		} catch (Exception $e) {
			IssueLog::Error('Core:GetDisabledUsersQuota:Error : '.$e->getMessage());
			throw new Exception(Dict::Format('Core:GetQuota:Error', Dict::S('Core:DisabledUsers')));
		}

		return $this->GetUsersFromFilter($oFilter);
	}

	/**
	 * @throws Exception
	 */
	public function GetApplicationUsers(bool $bAllData = true): array
	{
		$sOQLApplicationUser = 'SELECT UserToken';
		try {
			$oFilter = $bAllData ? DBObjectSearch::FromOQL_AllData($sOQLApplicationUser) : DBObjectSearch::FromOQL($sOQLApplicationUser);
		} catch (Exception $e) {
			IssueLog::Error('Core:GetConsoleUsersQuota:Error : '.$e->getMessage());
			throw new Exception(Dict::Format('Core:GetQuota:Error', Dict::S('Core:ApplicationUsers')));
		}

		return $this->GetUsersFromFilter($oFilter);
	}
}
