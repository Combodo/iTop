<?php

namespace Combodo\iTop\Controller\OAuth;

use Combodo\iTop\Application\TwigBase\Controller\Controller;
use utils;

class OAuthLandingController extends Controller
{
	public function OperationLanding()
	{
		$this->AddLinkedScript(utils::GetAbsoluteUrlAppRoot().'node_modules/jquery/dist/jquery.min.js');
		$this->AddLinkedScript(utils::GetAbsoluteUrlAppRoot().'js/ajax_hook.js');
		$this->DisplayPage([], null, static::ENUM_PAGE_TYPE_BASIC_HTML);
	}
}