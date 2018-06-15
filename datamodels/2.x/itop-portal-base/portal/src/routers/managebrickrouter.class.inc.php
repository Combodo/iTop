<?php

// Copyright (C) 2010-2018 Combodo SARL
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

namespace Combodo\iTop\Portal\Router;

class ManageBrickRouter extends AbstractRouter
{
	static $aRoutes = array(
        array(
            'pattern' => '/manage/{sBrickId}/{sGroupingTab}',
            'callback' => 'Combodo\\iTop\\Portal\\Controller\\ManageBrickController::DisplayAction',
            'bind' => 'p_manage_brick',
            'asserts' => array(),
            'values' => array(
                'sGroupingTab' => null,
            )
        ),
        array(
            'pattern' => '/manage/{sBrickId}/display-as/{sDisplayMode}/{sGroupingTab}',
            'callback' => 'Combodo\\iTop\\Portal\\Controller\\ManageBrickController::DisplayAction',
            'bind' => 'p_manage_brick_display_as',
            'asserts' => array(
                'sDisplayMode' => 'list|pie-chart|bar-chart'
            ),
            'values' => array(
                'sGroupingTab' => null,
            )
        ),
        array(
			'pattern' => '/manage/{sBrickId}/{sGroupingTab}/{sGroupingArea}/page/{iPageNumber}/show/{iListLength}',
			'callback' => 'Combodo\\iTop\\Portal\\Controller\\ManageBrickController::DisplayAction',
			'bind' => 'p_manage_brick_lazy',
			'asserts' => array(
				'iPageNumber' => '\d+',
				'iListLength' => '\d+',
			),
			'values' => array(
				'sDataLoading' => 'lazy',
				'iPageNumber' => '1',
				'iListLength' => '20',
			)
		),
		array(
			'pattern' => '/manage/export/excel/start/{sBrickId}/{sGroupingTab}/{sGroupingArea}',
			'callback' => 'Combodo\\iTop\\Portal\\Controller\\ManageBrickController::ExcelExportStartAction',
			'bind' => 'p_manage_brick_excel_export_start',
			'asserts' => array(),
			'values' => array(),
		),
	);

}
