<?php

namespace Combodo\iTop\Portal\Twig;

use Combodo\iTop\Portal\Routing\UrlGenerator;
use MetaModel;

class AppIconUrlAccessor
{
	private $oUrlGenerator;

	public function __construct(UrlGenerator $oUrlGenerator)
	{
		$this->oUrlGenerator = $oUrlGenerator;
	}

	/**
	 *
	 * @param $bIgnoreOverrideCheck bool If true, use the URL set in the configuration file even if it's not a custom value
	 *
	 * @return string
	 */
	public function GetAppIconUrl($bIgnoreOverrideCheck = false): string
	{
		// Try if a custom URL was set in the configuration file
		if ($bIgnoreOverrideCheck === true || MetaModel::GetConfig()->IsCustomValue('app_icon_url')) {
			return $_ENV['COMBODO_CONF_APP_ICON_URL'] ;
		}
		// Otherwise use the home page
		return  $this->oUrlGenerator->generate('p_home');
	}
}
