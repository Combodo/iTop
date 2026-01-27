<?php

// Copyright (C) 2012-2024 Combodo SAS
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

namespace Combodo\iTop\Application\Dashlet\Base;

use Combodo\iTop\Application\Helper\WebResourcesHelper;
use Combodo\iTop\Application\UI\Base\Component\Dashlet\DashletContainer;
use Dict;
use utils;

class DashletGroupByPie extends DashletGroupBy
{
	/**
	 * @inheritdoc
	 */
	public function __construct($oModelReflection, $sId)
	{
		parent::__construct($oModelReflection, $sId);
		$this->aProperties['style'] = 'pie';
	}

	/**
	 * @inheritDoc
	 */
	public function GetJSFilesRelPaths(): array
	{
		return array_merge(
			parent::GetJSFilesRelPaths(),
			WebResourcesHelper::GetJSFilesRelPathsForC3JS()
		);
	}

	/**
	 * @inheritDoc
	 */
	public function GetCSSFilesRelPaths(): array
	{
		return array_merge(
			parent::GetCSSFilesRelPaths(),
			WebResourcesHelper::GetCSSFilesRelPathsForC3JS()
		);
	}
}
