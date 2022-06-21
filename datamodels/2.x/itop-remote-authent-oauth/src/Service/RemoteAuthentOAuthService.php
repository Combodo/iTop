<?php
/**
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\RemoteAuthentOAuth\Service;

use RemoteAuthentOAuth;
use WebPage;

class RemoteAuthentOAuthService
{
	/**
	 * @var \RemoteAuthentOAuth
	 */
	protected $oRemoteAuthentOAuth;

	/**
	 * @param \RemoteAuthentOAuth $oRemoteAuthentOAuth
	 */
	public function __construct(RemoteAuthentOAuth $oRemoteAuthentOAuth)
	{
		$this->oRemoteAuthentOAuth = $oRemoteAuthentOAuth;
	}

	public function Authenticate(WebPage $oPage)
	{

	}


	public function DisplayAuthentForm(WebPage $oPage)
	{
		$sForm = "<form>\n";
		$sForm .= "<input type='hidden' name='additional' value=''/>\n";

		foreach (['provider', 'client_id', 'client_secret', 'scope'] as $sAttCode) {
			$sValue = $this->oRemoteAuthentOAuth->Get($sAttCode);
			$sForm .= "<input type='hidden' name='$sAttCode' value='$sValue'/>\n";
		}
		$sForm .= "<button class=\"ibo-oauth-wizard--form--submit\" type=\"submit\">Authentication</button>\n";

		$sForm .= "</form>\n";
		$oPage->add($sForm);
	}
}