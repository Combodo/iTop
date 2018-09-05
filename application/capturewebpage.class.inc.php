<?php
// Copyright (C) 2016 Combodo SARL
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

/**
 * Adapter class: when an API requires WebPage and you want to produce something else
 *
 * @copyright   Copyright (C) 2016 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

require_once(APPROOT."/application/webpage.class.inc.php");

class CaptureWebPage extends WebPage
{
	protected $aReadyScripts;

	function __construct()
	{
		parent::__construct('capture web page');
		$this->aReadyScripts = array();
	}

	public function GetHtml()
	{
		$trash = $this->ob_get_clean_safe();
		return $this->s_content;
	}

	public function GetJS()
	{
		$sRet = implode("\n", $this->a_scripts);
		if (!empty($this->s_deferred_content))
		{
			$sRet .= "\n\$('body').append('".addslashes(str_replace("\n", '', $this->s_deferred_content))."');";
		}
		return $sRet;
	}

	public function GetReadyJS()
	{
		return "\$(document).ready(function() {\n".implode("\n", $this->aReadyScripts)."\n});";
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

	public function add_ready_script($sScript)
	{
		$this->aReadyScripts[] = $sScript;
	}
}

