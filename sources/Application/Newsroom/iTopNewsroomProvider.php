<?php

class iTopNewsroomProvider extends NewsroomProviderBase {

	public function IsApplicable(User $oUser = null){
		return true;
	}
	public function GetLabel()
	{
		return ITOP_APPLICATION_SHORT;
	}

	public function GetFetchURL()
	{
		return self::MakeURL('fetch_unread_messages');
	}

	public function GetMarkAllAsReadURL()
	{
		return self::MakeURL('mark_all_as_read');
	}

	public function GetViewAllURL()
	{
		return self::MakeURL('view_all');
	}

	private static function MakeURL($sRouteCode)
	{
		return utils::GetAbsoluteUrlAppRoot().'pages/ajax.render.php?route=itopnewsroom.' . $sRouteCode;
	}

	public function GetTTL()
	{
		return MetaModel::GetConfig()->Get('notifications.itop.newsroom_cache_time') * 60;
	}
}