<?php

/**
 * Class ItopHubMenusHandler
 *
 * @author Denis Flaven <denis.flaven@combodo.com>
 * @since 2.4.1
 */
class ItopHubMenusHandler extends ModuleHandlerAPI
{
	/**
	 * iTop Hub menus are defined in PHP instead of XML to avoid people overloading them through a delta.
	 * Do NOT refactor them to the XML.
	 *
	 * @throws \Exception
	 */
	public static function OnMenuCreation()
	{
		// Add the admin menus
		if (UserRights::IsAdministrator())
		{
			$sRootUrl = utils::GetAbsoluteUrlAppRoot().'pages/exec.php?exec_module=itop-hub-connector&exec_page=launch.php';
			$sMyExtensionsUrl = utils::GetAbsoluteUrlAppRoot().'pages/exec.php?exec_module=itop-hub-connector&exec_page=myextensions.php';
			
			$oHubMenu = new MenuGroup('iTopHub', 999 /* fRank */, 'fc fc-itophub-icon fc-1-5x');
			$fRank = 1;
			new WebPageMenuNode('iTopHub:Register', $sRootUrl.'&target=view_dashboard', $oHubMenu->GetIndex(), $fRank++);
			new WebPageMenuNode('iTopHub:MyExtensions', $sMyExtensionsUrl, $oHubMenu->GetIndex(), $fRank++);
			new WebPageMenuNode('iTopHub:BrowseExtensions', $sRootUrl.'&target=browse_extensions', $oHubMenu->GetIndex(), $fRank++);
 		}
	}
}