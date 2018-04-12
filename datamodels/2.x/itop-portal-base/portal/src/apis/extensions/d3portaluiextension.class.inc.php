<?php
// Copyright (c) 2010-2017 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>
//
namespace Combodo\iTop\Portal\API\Extension;

use AbstractPortalUIExtension;
use Silex\Application;
use utils;

class D3PortalUIExtension extends AbstractPortalUIExtension
{
	public function GetCSSFiles(Application $oApp)
	{
		$aCSSFiles = array(
			utils::GetAbsoluteUrlAppRoot().'css/c3.min.css?v='.ITOP_VERSION,
		);
		return $aCSSFiles;
	}

	public function GetJSFiles(Application $oApp)
	{
		$aJSFiles = array(
			utils::GetAbsoluteUrlAppRoot().'js/d3.min.js?v='.ITOP_VERSION,
			utils::GetAbsoluteUrlAppRoot().'js/c3.min.js?v='.ITOP_VERSION,
			utils::GetCurrentModuleUrl().'/portal/web/js/export.js?v='.ITOP_VERSION,
		);
		return $aJSFiles;
	}
}