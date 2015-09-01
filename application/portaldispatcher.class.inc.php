<?php
class PortalDispatcher
{
	protected $sPortalid;
	protected $aData;
	
	public function __construct($sPortalId)
	{
		$this->sPortalid = $sPortalId;
		$this->aData = PortalDispatcherData::GetData($sPortalId);
	}
	
	public function IsUserAllowed()
	{
		$bRet = true;
		if (array_key_exists('profile_list', $_SESSION))
		{
			$aProfiles = $_SESSION['profile_list'];
		}
		else
		{
			$oUser = UserRights::GetUserObject();
			$oSet = $oUser->Get('profile_list');
			while(($oLnkUserProfile = $oSet->Fetch()) !== null)
			{
				$aProfiles[] = $oLnkUserProfile->Get('profileid_friendlyname');
			}
			$_SESSION['profile_list'] = $aProfiles;
		}		
		
		foreach($this->aData['deny'] as $sDeniedProfile)
		{
			// If one denied profile is present, it's enough => return false
			if (in_array($sDeniedProfile, $aProfiles))
			{
				return false;
			}
		}
		// If there are some "allow" profiles, then by default the result is false
		// since the user must have at least one of the profiles to be allowed
		if (count($this->aData['allow']) > 0)
		{
			$bRet = false;
		}
		foreach($this->aData['allow'] as $sAllowProfile)
		{
			// If one "allow" profile is present, it's enough => return true
			if (in_array($sAllowProfile, $aProfiles))
			{
				return true;
			}
		}
		return $bRet;
	}
	
	public function GetURL()
	{
		return utils::GetAbsoluteUrlAppRoot().$this->aData['url'];
	}
	
	public function GetLabel()
	{
		return Dict::S('portal:'.$this->sPortalid);
	}
	
	public function GetRank()
	{
		return $this->aData['rank'];
	}
}