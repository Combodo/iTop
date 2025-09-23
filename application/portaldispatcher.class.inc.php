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

	/**
	 * @param \User|null $oUser
	 *
	 * @return bool
	 * @since 3.2.0 N°2039 Add $oUser parameter
	 */
	public function IsUserAllowed(?User $oUser = null)
	{
		$bRet = true;
		$aProfiles = UserRights::ListProfiles($oUser);
		
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
		$aOverloads = MetaModel::GetConfig()->Get('portal_dispatch_urls');
		if (array_key_exists($this->sPortalid, $aOverloads))
		{
			$sRet = $aOverloads[$this->sPortalid];
		}
		else
		{
			$sRet = utils::GetAbsoluteUrlAppRoot().$this->aData['url'];
		}
		return $sRet;
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