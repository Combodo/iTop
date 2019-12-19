<?php
/**
 * @copyright   Copyright (C) 2010-2019 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\TwigBase\Twig;

use Twig_Environment;
use Twig_Loader_Filesystem;


class TwigHelper
{
	public static function GetTwigEnvironment($sViewPath)
	{
		$oLoader = new Twig_Loader_Filesystem($sViewPath);
		$oTwig = new Twig_Environment($oLoader);
		Extension::RegisterTwigExtensions($oTwig);

		return $oTwig;
	}
}
