<?php
// Copyright (C) 2010-2015 Combodo SARL
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

namespace Combodo\iTop\Portal\Brick;

use \Combodo\iTop\Portal\Brick\PortalBrick;

/**
 * Description of UserProfileBrick
 * 
 * @author Guillaume Lajarige
 */
class UserProfileBrick extends PortalBrick
{
    const DEFAULT_PAGE_TEMPLATE_PATH = 'itop-portal-base/portal/src/views/bricks/user-profile/layout.html.twig';
	const DEFAULT_TILE_TEMPLATE_PATH = 'itop-portal-base/portal/src/views/bricks/user-profile/tile.html.twig';
	const DEFAULT_VISIBLE_NAVIGATION_MENU = false;
	const DEFAULT_VISIBLE_HOME = false;
	const DEFAUT_TITLE = 'Brick:Portal:UserProfile:Title';

	static $sRouteName = 'p_user_profile_brick';
}

?>