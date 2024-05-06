<?php
// Copyright (C) 2024 Combodo SAS
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

namespace Combodo\iTop\Application\WebPage;

use Combodo\iTop\Application\UI\Base\iUIBlock;
use Combodo\iTop\Renderer\BlockRenderer;
use Exception;
use ExecutionKPI;

/**
 * Adapter class: when an API requires WebPage and you want to produce something else
 *
 * @copyright   Copyright (C) 2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


class CaptureWebPage extends WebPage
{
	function __construct()
	{
		$oKpi = new ExecutionKPI();
		parent::__construct('capture web page');
		$oKpi->ComputeStats(get_class($this).' creation', 'CaptureWebPage');
	}

	public function GetHtml()
	{
		$trash = $this->ob_get_clean_safe();

		$oBlockRenderer = new BlockRenderer($this->oContentLayout);
		return $oBlockRenderer->RenderHtml();
	}

	public function GetJS()
	{
		$sRet = implode("\n", $this->a_scripts);
		if (!empty($this->s_deferred_content))
		{
			$sRet .= "\n\$('body').append('".addslashes(str_replace("\n", '', $this->s_deferred_content))."');";
		}

		$oBlockRenderer = new BlockRenderer($this->oContentLayout);
		$sRet .= $oBlockRenderer->RenderJsInline(iUIBlock::ENUM_JS_TYPE_LIVE);
		$sRet .= $oBlockRenderer->RenderJsInline(iUIBlock::ENUM_JS_TYPE_ON_INIT);

		return $sRet;
	}

	public function GetReadyJS()
	{
		$sRet =  "\$(document).ready(function() {\n".implode("\n", $this->a_init_scripts).implode("\n", $this->a_ready_scripts)."\n});";

		$oBlockRenderer = new BlockRenderer($this->oContentLayout);
		$sRet .= $oBlockRenderer->RenderJsInline(iUIBlock::ENUM_JS_TYPE_ON_READY);

		return $sRet;
	}

	public function GetCSS()
	{
		return $this->a_styles;
	}

	public function GetJSFiles()
	{
		return $this->a_linked_scripts;
	}

	public function GetCSSFiles()
	{
		return $this->a_linked_stylesheets;
	}

	public function output()
	{
		throw new Exception(__method__.' should not be called');
	}
}

