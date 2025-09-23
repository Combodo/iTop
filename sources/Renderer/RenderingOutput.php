<?php

/**
 * Copyright (C) 2013-2024 Combodo SAS
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

namespace Combodo\iTop\Renderer;

use utils;

/**
 * Description of RenderingOutput
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
class RenderingOutput
{
	protected $sHtml;
	protected $aMetadata;
	protected $sJsInline;
	protected $aJsFiles;
	protected $sCssInline;
	protected $aCssFiles;
	protected $aCssClasses;

	public function __construct()
	{
		$this->sHtml = '';
		$this->aMetadata = array();
		$this->sJsInline = '';
		$this->aJsFiles = array();
		$this->sCssInline = '';
		$this->aCssFiles = array();
		$this->aCssClasses = array();
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
	 * @return array
	 * @since 2.7.0
	 */
	public function GetMetadata()
	{
		return $this->aMetadata;
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
     * @return array
     */
	public function GetCssClasses()
    {
	    return $this->aCssClasses;
    }

	/**
	 *
	 * @param ?string $sHtml
	 * @param bool $bEscapeHtmlEntities
	 *
	 * @return \Combodo\iTop\Renderer\RenderingOutput
	 */
	public function AddHtml(?string $sHtml, bool $bEscapeHtmlEntities = false)
	{
		if (!is_null($sHtml)) {
			$this->sHtml .= ($bEscapeHtmlEntities) ? utils::Escapehtml($sHtml) : $sHtml;
		}
		
		return $this;
	}

	/**
	 * Add a metadata identified by $sName.
	 *
	 * @param string $sName
	 * @param string $sValue
	 *
	 * @return $this
	 * @since 2.7.0
	 */
	public function AddMetadata(string $sName, string $sValue)
	{
		$this->aMetadata[$sName] = $sValue;

		return $this;
	}

	/**
	 * Remove the metadata identified by $sName
	 *
	 * @param string $sName
	 *
	 * @return $this
	 * @since 2.7.0
	 */
	public function RemoveMetadata(string $sName)
	{
		if (in_array($sName, $this->aMetadata))
		{
			unset($this->aJsFiles[$sName]);
		}

		return $this;
	}

	/**
	 *
	 * @param string $sJs
	 *
	 * @return \Combodo\iTop\Renderer\RenderingOutput
	 */
	public function AddJs(string $sJs)
	{
		$this->sJsInline .= $sJs."\n";

		return $this;
	}

	/**
	 * Set the JS files (absolute URLs) and replace any existing ones.
	 *
	 * @param array $aFiles Array of absolute URLs
	 *
	 * @return $this
	 * @since 3.0.0
	 */
	public function SetJsFiles(array $aFiles)
	{
		$this->aJsFiles = $aFiles;

		return $this;
	}

	/**
	 *
	 * @param string $sFile
	 *
	 * @return \Combodo\iTop\Renderer\RenderingOutput
	 */
	public function AddJsFile(string $sFile)
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
	 *
	 * @return \Combodo\iTop\Renderer\RenderingOutput
	 */
	public function RemoveJsFile(string $sFile)
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
	 *
	 * @return \Combodo\iTop\Renderer\RenderingOutput
	 */
	public function AddCss(string $sCss)
	{
		$this->sCssInline .= $sCss."\n";

		return $this;
	}

	/**
	 * Set the CSS files (absolute URLs) and replace any existing ones.
	 *
	 * @param array $aFiles Array of absolute URLs
	 *
	 * @return $this
	 * @since 3.0.0
	 */
	public function SetCssFiles(array $aFiles)
	{
		$this->aCssFiles = $aFiles;

		return $this;
	}

	/**
	 *
	 * @param string $sFile
	 *
	 * @return \Combodo\iTop\Renderer\RenderingOutput
	 */
	public function AddCssFile(string $sFile)
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
	public function RemoveCssFile(string $sFile)
	{
		if (in_array($sFile, $this->aCssFiles))
		{
			unset($this->aCssFiles[$sFile]);
		}

		return $this;
    }

    /**
     *
     * @param string $sClass
     * @return \Combodo\iTop\Renderer\RenderingOutput
     */
	public function AddCssClass(string $sClass)
	{
		if (!in_array($sClass, $this->aCssClasses))
		{
			$this->aCssClasses[] = $sClass;
		}

		return $this;
    }

    /**
     *
     * @param string $sClass
     * @return \Combodo\iTop\Renderer\RenderingOutput
     */
	public function RemoveCssClass(string $sClass)
	{
		if (in_array($sClass, $this->aCssClasses))
		{
			unset($this->aCssClasses[$sClass]);
		}

		return $this;
    }

}
