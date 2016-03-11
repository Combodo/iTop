<?php

// Copyright (C) 2010-2016 Combodo SARL
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

namespace Combodo\iTop\Renderer;

/**
 * Description of RenderingOutput
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
class RenderingOutput
{
	protected $sHtml;
	protected $sJsInline;
	protected $aJsFiles;
	protected $sCssInline;
	protected $aCssFiles;

	public function __construct()
	{
		$this->sHtml = '';
		$this->sJsInline = '';
		$this->aJsFiles = array();
		$this->sCssInline = '';
		$this->aCssFiles = array();
	}

	/**
	 *
	 * @return string
	 */
	public function GetHtml()
	{
		return $this->sHtml;
	}

	/**
	 *
	 * @return string
	 */
	public function GetJs()
	{
		return $this->sJsInline;
	}

	/**
	 *
	 * @return array
	 */
	public function GetJsFiles()
	{
		return $this->aJsFiles;
	}

	/**
	 *
	 * @return string
	 */
	public function GetCss()
	{
		return $this->sCssInline;
	}

	/**
	 *
	 * @return array
	 */
	public function GetCssFiles()
	{
		return $this->aCssFiles;
	}

	/**
	 *
	 * @param string $sHtml
	 * @return \Combodo\iTop\Renderer\RenderingOutput
	 */
	public function AddHtml($sHtml, $bEncodeHtmlEntities = false)
	{
		$this->sHtml .= ($bEncodeHtmlEntities) ? htmlentities($sHtml, ENT_QUOTES, 'UTF-8') : $sHtml;
		return $this;
	}

	/**
	 *
	 * @param string $sJs
	 * @return \Combodo\iTop\Renderer\RenderingOutput
	 */
	public function AddJs($sJs)
	{
		$this->sJsInline .= $sJs . "\n";
		return $this;
	}

	/**
	 *
	 * @param string $sFile
	 * @return \Combodo\iTop\Renderer\RenderingOutput
	 */
	public function AddJsFile($sFile)
	{
		if (!in_array($sFile, $this->aJsFiles))
		{
			$this->aJsFiles[] = $sFile;
		}
		return $this;
	}

	/**
	 *
	 * @param string $sFile
	 * @return \Combodo\iTop\Renderer\RenderingOutput
	 */
	public function RemoveJsFile($sFile)
	{
		if (in_array($sFile, $this->aJsFiles))
		{
			unset($this->aJsFiles[$sFile]);
		}
		return $this;
	}

	/**
	 *
	 * @param string $sCss
	 * @return \Combodo\iTop\Renderer\RenderingOutput
	 */
	public function AddCss($sCss)
	{
		$this->sCssInline .= $sCss . "\n";
		return $this;
	}

	/**
	 *
	 * @param string $sFile
	 * @return \Combodo\iTop\Renderer\RenderingOutput
	 */
	public function AddCssFile($sFile)
	{
		if (!in_array($sFile, $this->aCssFiles))
		{
			$this->aCssFiles[] = $sFile;
		}
		return $this;
	}

	/**
	 *
	 * @param string $sFile
	 * @return \Combodo\iTop\Renderer\RenderingOutput
	 */
	public function RemoveCssFile($sFile)
	{
		if (in_array($sFile, $this->aCssFiles))
		{
			unset($this->aCssFiles[$sFile]);
		}
		return $this;
	}

}
