<?php

use Combodo\iTop\Service\Router\Router;

class iTopNewsroomProvider extends NewsroomProviderBase {

	public function IsApplicable(User $oUser = null){
		return true;
	}
	public function GetLabel()
	{
		return Dict::S('UI:Newsroom:iTopNotification:Label');
	}

	public function GetFetchURL()
	{
		return self::MakeURL('fetch_unread_messages');
	}

	public function GetMarkAllAsReadURL()
	{
		return self::MakeURL('mark_all_as_read_messages');
	}

	public function GetViewAllURL()
	{
		return self::MakeURL('view_all');
	}

	private static function MakeURL($sRouteCode)
	{
		return Router::GetInstance()->GenerateUrl(iTopNewsroomController::ROUTE_NAMESPACE . '.' . $sRouteCode);
	}

	public function GetTTL()
	{
		return MetaModel::GetConfig()->Get('notifications.itop.newsroom_cache_time') * 60;
	}
}