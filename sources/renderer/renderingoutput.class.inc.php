<?php

/**
 * Copyright (C) 2013-2019 Combodo SARL
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
     * @param string $sHtml
     * @param bool $bEncodeHtmlEntities
     *
     * @return \Combodo\iTop\Renderer\RenderingOutput
     */
	public function AddHtml($sHtml, $bEncodeHtmlEntities = false)
	{
		$this->sHtml .= ($bEncodeHtmlEntities) ? htmlentities($sHtml, ENT_QUOTES, 'UTF-8') : $sHtml;
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
	public function AddMetadata($sName, $sValue)
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
	public function RemoveMetadata($sName)
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

    /**
     *
     * @param string $sClass
     * @return \Combodo\iTop\Renderer\RenderingOutput
     */
    public function AddCssClass($sClass)
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
    public function RemoveCssClass($sClass)
    {
        if (in_array($sClass, $this->aCssClasses))
        {
            unset($this->aCssClasses[$sClass]);
        }
        return $this;
    }

}
