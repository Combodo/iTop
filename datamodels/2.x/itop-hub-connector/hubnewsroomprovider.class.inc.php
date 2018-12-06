<?php
require_once(APPROOT.'application/newsroomprovider.class.inc.php');

class HubNewsroomProvider extends NewsroomProviderBase
{
	/**
	 * {@inheritDoc}
	 * @see NewsroomProviderBase::GetTTL()
	 */
	public function GetTTL()
	{
		// TODO Auto-generated method stub
		return 15*60; // Update every 15 minutes
	}
	
	/**
	 * {@inheritDoc}
	 * @see NewsroomProviderBase::IsApplicable()
	 */
	public function IsApplicable(User $oUser = null)
	{
		if ($oUser !== null)
		{
			return UserRights::IsAdministrator($oUser);
		}
		else
		{
			return false;
		}
		
	}
	/**
	 * {@inheritDoc}
	 * @see NewsroomProviderBase::GetLabel()
	 */
	public function GetLabel()
	{
		return 'iTop Hub'; // No need to translate...
	}
	
	public function GetMarkAllAsReadURL()
	{
		return $this->MakeURL('route_mark_all_messages_as_read');
	}
	
	public function GetFetchURL()
	{
		return $this->MakeURL('route_fetch_unread_messages');
	}
	
	public function GetViewAllURL()
	{
		return $sBaseUrl = $this->oConfig->GetModuleSetting('itop-hub-connector', 'url').MetaModel::GetModuleSetting('itop-hub-connector', 'route_view_all_messages');
	}
	
	/**
	 * {@inheritDoc}
	 * @see iNewsroomProvider::GetPlaceholders()
	 */
	public function GetPlaceholders()
	{
		return array(
			'%connect_to_itop_hub%' => utils::GetAbsoluteUrlModulePage('itop-hub-connector', 'launch.php', array('target' => 'view_dashboard')),
		);
	}
	
	/**
	 * {@inheritDoc}
	 * @see NewsroomProviderBase::GetPreferencesUrl()
	 */
	public function GetPreferencesUrl()
	{
		return null;
	}
	
	private function MakeURL($sRouteCode)
	{
		$sBaseUrl = $this->oConfig->GetModuleSetting('itop-hub-connector', 'url').MetaModel::GetModuleSetting('itop-hub-connector', $sRouteCode);
		
		$sParameters = 'uuid[bdd]='.urlencode((string) trim(DBProperty::GetProperty('database_uuid', ''), '{}'));
		$sParameters .= '&uuid[file]='.urlencode((string) trim(@file_get_contents(APPROOT."data/instance.txt"), "{} \n"));
		$sParameters .= '&uuid[user]='.urlencode(UserRights::GetUserId());
		
		return $sBaseUrl.'?'.$sParameters;
	}
}
