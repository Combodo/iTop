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
//   You should havze received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>

namespace Combodo\iTop\Portal\Router;

use Silex\Application;

class BrowseBrickRouter extends AbstractRouter
{
	static $aRoutes = array(
		// We don't set asserts for sBrowseMode on that route, as it the generic one, it can be extended by another brick.
		array('pattern' => '/browse/{sBrickId}',
			'callback' => 'Combodo\\iTop\\Portal\\Controller\\BrowseBrickController::DisplayAction',
			'bind' => 'p_browse_brick'
		),
		array('pattern' => '/browse/{sBrickId}/{sBrowseMode}',
			'callback' => 'Combodo\\iTop\\Portal\\Controller\\BrowseBrickController::DisplayAction',
			'bind' => 'p_browse_brick_mode'
		),
		array('pattern' => '/browse/{sBrickId}/list/page/{iPageNumber}/show/{iCountPerPage}',
			'callback' => 'Combodo\\iTop\\Portal\\Controller\\BrowseBrickController::DisplayAction',
			'bind' => 'p_browse_brick_mode_list',
			'asserts' => array(
				'sBrowseMode' => 'list',
				'iPageNumber' => '\d+',
				'iCountPerPage' => '\d+'
			),
			'values' => array(
				'sBrowseMode' => 'list',
				'sDataLoading' => 'lazy',
				'iPageNumber' => '1',
				'iCountPerPage' => '20'
			)
		),
		array('pattern' => '/browse/{sBrickId}/tree/expand/{sLevelAlias}/{sNodeId}',
			'callback' => 'Combodo\\iTop\\Portal\\Controller\\BrowseBrickController::DisplayAction',
			'bind' => 'p_browse_brick_mode_tree',
			'asserts' => array(
				'sBrowseMode' => 'tree'
			),
			'values' => array(
				'sBrowseMode' => 'tree',
				'sDataLoading' => 'lazy',
				'sNodeId' => null
			)
		),
	);

}

?>