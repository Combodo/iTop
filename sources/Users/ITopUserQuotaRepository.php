<?php

namespace Combodo\iTop\Users;

use CoreException;
use CoreUnexpectedValue;
use DBObjectSearch;
use DBObjectSet;
use DBSearch;
use DBUnionSearch;
use Dict;
use DictExceptionMissingString;
use Exception;
use IssueLog;
use MetaModel;
use MySQLException;
use User;
use UserRights;

/**
 *
 */
class ITopUserQuotaRepository
{
	/**
	 * @param string $sExcludedUsers
	 * @param string $sExcludedProfiles
	 * @param bool $bAllData
	 * @param string $sExcludedFinalClasses
	 *
	 * @return DBObjectSearch|DBUnionSearch|null
	 * @throws Exception
	 */
	public function GetConsoleUsers(string $sExcludedUsers = '', string $sExcludedProfiles = '', bool $bAllData = true, string $sExcludedFinalClasses = 'UserToken, UserRemoteSaaS'): null|DBObjectSearch|DBUnionSearch
	{
		$sOQLInQuotaUser = "
        SELECT User AS u
        WHERE u.status != 'disabled'
        AND u.login NOT IN ('$sExcludedUsers')
        AND u.finalclass != ' $sExcludedFinalClasses '
        AND id NOT IN (
            SELECT User AS uex
            JOIN URP_UserProfile AS uup ON uup.userid = uex.id
            JOIN URP_Profiles AS up ON uup.profileid = up.id
            WHERE up.name IN ('$sExcludedProfiles'))
        ";
		try {
			$oFilter = $bAllData ? DBObjectSearch::FromOQL_AllData($sOQLInQuotaUser) : DBObjectSearch::FromOQL($sOQLInQuotaUser);
		}
		catch (Exception $e) {
			IssueLog::Error('Core:GetConsoleUsersQuota:Error : '.$e->getMessage());
			throw new Exception(Dict::Format('Core:GetQuota:Error', Dict::S('Core:ConsoleUsers')));
		}

		// TODO remove read only users
		return $oFilter;
	}

	/**
	 * @throws Exception
	 */
	public function GetApplicationUsers(bool $bAllData = true): null|DBObjectSearch|DBUnionSearch
	{
		$sOQLApplicationUser = 'SELECT UserToken';
		try {
			$oFilter = $bAllData ? DBObjectSearch::FromOQL_AllData($sOQLApplicationUser) : DBObjectSearch::FromOQL($sOQLApplicationUser);
		}
		catch (Exception $e) {
			IssueLog::Error('Core:GetConsoleUsersQuota:Error : '.$e->getMessage());
			throw new Exception(Dict::Format('Core:GetQuota:Error', Dict::S('Core:ApplicationUsers')));
		}

		return $oFilter;

	}

	/**
	 * @throws Exception
	 */
	public function GetDisabledUsers(bool $bAllData = true): null|DBObjectSearch|DBUnionSearch
	{
		$sOQLDisabledUser = "
		SELECT User AS u
		WHERE u.status = 'disabled'
		";
		try {
			$oFilter = $bAllData ? DBObjectSearch::FromOQL_AllData($sOQLDisabledUser) : DBObjectSearch::FromOQL($sOQLDisabledUser);
		}
		catch (Exception $e) {
			IssueLog::Error('Core:GetDisabledUsersQuota:Error : '.$e->getMessage());
			throw new Exception(Dict::Format('Core:GetQuota:Error', Dict::S('Core:DisabledUsers')));
		}

		return $oFilter;
	}

private function IsUserReadOnly(User $oUser, string $sClassCategory)
{
	UserRights::Login($oUser->GetName());

	foreach (MetaModel::GetClasses($sClassCategory) as $sClass) {
		$aClassStimuli = MetaModel::EnumStimuli($sClass);
		if (count($aClassStimuli) > 0) {
			$aStimuli = [];
			foreach ($aClassStimuli as $sStimulusCode => $oStimulus) {
				if (UserRights::IsStimulusAllowed($sClass, $sStimulusCode, null, $oUser)) {
					$aStimuli[] =
						$oStimulus->GetLabel();
				}
			}
			$sStimuli = implode(', ', $aStimuli);
		} else {
			$sStimuli = '';
		}

		if (
			UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY, null, $oUser) ||
			UserRights::IsActionAllowed($sClass, UR_ACTION_BULK_MODIFY, null, $oUser) ||
			UserRights::IsActionAllowed($sClass, UR_ACTION_DELETE, null, $oUser) ||
			UserRights::IsActionAllowed($sClass, UR_ACTION_BULK_DELETE, null, $oUser) ||
			$sStimuli != ''
		 ) {
			UserRights::Logoff();
			return false;
		}
	}
	UserRights::Logoff();
	return true;
}

	/**
	 * @throws DictExceptionMissingString
	 * @throws CoreException
	 * @throws Exception
	 */
	public function GetReadOnlyUsers(): array
	{
		$aReadOnlyUsers = [];
		$oAllUsersFilter = $this->GetAllUsers();
		$aAllUsers = $this->GetUsersFromFilter($oAllUsersFilter);
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

		// TODO remove disabled users
		return $aReadOnlyUsers;
	}

	/**
	 * @throws Exception
	 */
	public function getPortalUsers(bool $bAllData = true): null|DBObjectSearch|DBUnionSearch
	{
		$sOQLPortalUser = '
        SELECT User AS u
        JOIN URP_UserProfile AS uup ON uup.userid = u.id
        JOIN URP_Profiles AS up ON uup.profileid = up.id
		WHERE up.name = \' '.PORTAL_PROFILE_NAME.'\'';

		try {
			$oFilter = $bAllData ? DBObjectSearch::FromOQL_AllData($sOQLPortalUser) : DBObjectSearch::FromOQL($sOQLPortalUser);
		}
		catch (Exception $e) {
			IssueLog::Error('combodo-users-quota-slave/GetUsersInQuota : '.$e->getMessage(), 'combodo-users-quota');
			throw new Exception(Dict::Format('Core:GetQuota:Error', Dict::S('Core:PortalUsers')));
		}

		// TODO remove read only users
		return $oFilter;
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
		while ($oUser = $oSet->fetch()) {
			$aUsers[] = $oUser;
		}

		return $aUsers;
	}

	/**
	 * @throws Exception
	 */
	public function GetAllUsers(bool $bAllData = true): DBUnionSearch|DBObjectSearch|DBSearch|null
	{
		$sOqlUser = 'SELECT User';

		try {
			$oFilter = $bAllData ? DBObjectSearch::FromOQL_AllData($sOqlUser) : DBObjectSearch::FromOQL($sOqlUser);
		}
		catch (Exception $e) {
			IssueLog::Error('combodo-users-quota-slave/GetUsersNotInQuota : '.$e->getMessage(), 'combodo-users-quota');
			throw new Exception(Dict::S('CombodoUserQuota:Error'));
		}

		return $oFilter;

	}

}