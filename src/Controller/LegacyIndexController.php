<?php
/**
 * Copyright (C) 2010-2020 Combodo SARL
 *
 *   This file is part of iTop.
 *
 *   iTop is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU Affero General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   iTop is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU Affero General Public License for more details.
 *
 *   You should have received a copy of the GNU Affero General Public License
 *   along with iTop. If not, see <http: *www.gnu.org/licenses/>
 *
 */

namespace Combodo\iTop\Controller;


use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class LegacyIndexController extends \Symfony\Bundle\FrameworkBundle\Controller\Controller
{
	public function indexAction()
	{
		$sResponse = '';

		$sConfigFile = 'conf/production/config-itop.php';
		$sStartPage = './pages/UI.php';
		$sSetupPage = './setup/index.php';

		/**
		 * Check that the configuration file exists and has the appropriate access rights
		 * If the file does not exist, launch the configuration wizard to create it
		 */
		if (file_exists(dirname(__FILE__).'/'.$sConfigFile))
		{
			if (!is_readable($sConfigFile))
			{
				$sResponse .= "<p><b>Error</b>: Unable to read the configuration file: '$sConfigFile'. Please check the access rights on this file.</p>";
			}
			else if (is_writable($sConfigFile))
			{
				require_once (APPROOT.'setup/setuputils.class.inc.php');
				if (\SetupUtils::IsInReadOnlyMode())
				{
					$sResponse .= "<p><b>Warning</b>: the application is currently in maintenance, please wait.</p>";
					$sResponse .= "<p>Click <a href=\"$sStartPage\">here</a> to ignore this warning and continue to run iTop in read-only mode.</p>";
				}
				else
				{
					$sResponse .= "<p><b>Security Warning</b>: the configuration file '$sConfigFile' should be read-only.</p>";
					$sResponse .= "<p>Please modify the access rights to this file.</p>";
					$sResponse .= "<p>Click <a href=\"$sStartPage\">here</a> to ignore this warning and continue to run iTop.</p>";
				}
			}
			else
			{
				return new RedirectResponse($sStartPage);

			}
		}
		else
		{
			// Config file does not exist, need to run the setup wizard to create it
			return new RedirectResponse($sSetupPage);
		}

		return new Response($sResponse);
	}
}