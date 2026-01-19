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

use Combodo\iTop\Application\UI\Base\Component\Dashlet\DashletContainer;
use Dict;
use utils;

class DashletProxy extends DashletUnknown
{
	/**
	 * @inheritdoc
	 */
	public function __construct($oModelReflection, $sId)
	{
		parent::__construct($oModelReflection, $sId);

		// Remove DashletUnknown class
		if (($key = array_search('dashlet-unknown', $this->aCSSClasses)) !== false) {
			unset($this->aCSSClasses[$key]);
		}

		$this->aCSSClasses[] = 'dashlet-proxy';
	}

	/**
	 * @inheritdoc
	 */
	public function Render($oPage, $bEditMode = false, $aExtraParams = [])
	{
		// This should never be called.
		$oDashletContainer = new DashletContainer(null, ['dashlet-content']);
		$oDashletContainer->AddHtml('<div>This dashlet is not supposed to be rendered as it is just a proxy for third-party widgets.</div>');

		return $oDashletContainer;
	}

	/**
	 * @inheritdoc
	 *
	 * @throws \Exception
	 */
	public function RenderNoData($oPage, $bEditMode = false, $aExtraParams = [])
	{
		$sIconUrl = utils::HtmlEntities(utils::GetAbsoluteUrlAppRoot().'images/dashlet-proxy.png');
		$sExplainText = Dict::Format('UI:DashletProxy:RenderNoDataText:Edit', $this->GetDashletType());

		$oDashletContainer = new DashletContainer(null, ['dashlet-content']);

		$sHtml = '';
		$sHtml .= '<div class="dashlet-pxy-image"><img src="'.$sIconUrl.'" /></div>';
		$sHtml .= '<div class="dashlet-pxy-text">'.$sExplainText.'</div>';

		$oDashletContainer->AddHtml($sHtml);

		return $oDashletContainer;
	}
}
