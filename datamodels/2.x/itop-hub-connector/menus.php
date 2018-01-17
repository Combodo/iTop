<?php
class ItopHubMenusHandler extends ModuleHandlerAPI
{
	public static function OnMenuCreation()
	{
		// Add the admin menus
		if (UserRights::IsAdministrator())
		{
			$sRootUrl = utils::GetAbsoluteUrlAppRoot().'pages/exec.php?exec_module=itop-hub-connector&exec_page=launch.php';
			$sMyExtensionsUrl = utils::GetAbsoluteUrlAppRoot().'pages/exec.php?exec_module=itop-hub-connector&exec_page=myextensions.php';
			
			$oHubMenu = new MenuGroup('iTopHub', 999 /* fRank */);
			$fRank = 1;
			new WebPageMenuNode('iTopHub:Register', $sRootUrl.'&target=view_dashboard', $oHubMenu->GetIndex(), $fRank++);
			new WebPageMenuNode('iTopHub:MyExtensions', $sMyExtensionsUrl, $oHubMenu->GetIndex(), $fRank++);
			new WebPageMenuNode('iTopHub:BrowseExtensions', $sRootUrl.'&target=browse_extensions', $oHubMenu->GetIndex(), $fRank++);
 		}
	}
}