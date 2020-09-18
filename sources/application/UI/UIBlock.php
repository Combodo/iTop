<?php
/**
 * Copyright (C) 2013-2020 Combodo SARL
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

namespace Combodo\iTop\Application\UI;


use utils;

/**
 * Class UIBlock
 *
 * @package Combodo\iTop\Application\UI
 * @internal
 * @since 2.8.0
 */
abstract class UIBlock implements iUIBlock
{
	/** @var string BLOCK_CODE The block code to use to generate the identifier, the CSS/JS prefixes, ...
	 *
	 * Should start "ibo-" for the iTop backoffice blocks, followed by the name of the block in lower case (eg. for a MyCustomBlock class,
	 * should be "ibo-my-custom-clock")
	 */
	public const BLOCK_CODE = 'ibo-block';

	/** @var string|null GLOBAL_TEMPLATE_REL_PATH Relative path (from <ITOP>/templates/) to the "global" TWIG template which contains HTML, JS inline, JS files, CSS inline, CSS files. Should not be used to often as JS/CSS files would be duplicated making browser parsing time way longer. */
	public const GLOBAL_TEMPLATE_REL_PATH = null;
	/** @var string|null HTML_TEMPLATE_REL_PATH Relative path (from <ITOP>/templates/) to the HTML template */
	public const HTML_TEMPLATE_REL_PATH = null;
	/** @var array JS_FILES_REL_PATH Relative paths (from <ITOP>/) to the JS files */
	public const JS_FILES_REL_PATH = [];
	/** @var string|null JS_TEMPLATE_REL_PATH Relative path (from <ITOP>/templates/) to the JS template */
	public const JS_TEMPLATE_REL_PATH = null;
	/** @var array CSS_FILES_REL_PATH Relative paths (from <ITOP>/) to the CSS files */
	public const CSS_FILES_REL_PATH = [];
	/** @var string|null CSS_TEMPLATE_REL_PATH Relative path (from <ITOP>/templates/) to the CSS template */
	public const CSS_TEMPLATE_REL_PATH = null;

	/** @var string ENUM_BLOCK_FILES_TYPE_JS */
	public const ENUM_BLOCK_FILES_TYPE_JS = 'js';
	/** @var string ENUM_BLOCK_FILES_TYPE_CSS */
	public const ENUM_BLOCK_FILES_TYPE_CSS = 'css';
	/** @var string ENUM_BLOCK_FILES_TYPE_FILE */
	public const ENUM_BLOCK_FILES_TYPE_FILES = 'files';
	/** @var string ENUM_BLOCK_FILES_TYPE_TEMPLATE */
	public const ENUM_BLOCK_FILES_TYPE_TEMPLATE = 'template';


	/**
	 * @inheritDoc
	 */
	public static function GetGlobalTemplateRelPath()
	{
		return static::GLOBAL_TEMPLATE_REL_PATH;
	}

	/**
	 * @inheritDoc
	 */
	public static function GetHtmlTemplateRelPath()
	{
		return static::HTML_TEMPLATE_REL_PATH;
	}

	/**
	 * @inheritDoc
	 */
	public static function GetJsTemplateRelPath()
	{
		return static::JS_TEMPLATE_REL_PATH;
	}

	/**
	 * @inheritDoc
	 */
	public static function GetJsFilesRelPaths()
	{
		return static::JS_FILES_REL_PATH;
	}

	/**
	 * @inheritDoc
	 */
	public static function GetCssTemplateRelPath()
	{
		return static::CSS_TEMPLATE_REL_PATH;
	}

	/**
	 * @inheritDoc
	 */
	public static function GetCssFilesRelPaths()
	{
		return static::CSS_FILES_REL_PATH;
	}


	/** @var string $sId */
	protected $sId;

	/**
	 * UIBlock constructor.
	 *
	 * @param string|null $sId
	 */
	public function __construct(?string $sId = null)
	{
		$this->sId = ($sId !== null) ? $sId : $this->GenerateId();
	}

	/**
	 * Return a unique ID for the block
	 *
	 * @return string
	 */
	protected function GenerateId()
	{
		return uniqid(static::BLOCK_CODE.'-');
	}

	/**
	 * Return the block code of the object instance
	 *
	 * @see static::BLOCK_CODE
	 * @return string
	 */
	public function GetBlockCode()
	{
		return static::BLOCK_CODE;
	}

	/**
	 * @inheritDoc
	 */
	public function GetId()
	{
		return $this->sId;
	}

	/**
	 * @inheritDoc
	 * @return \Combodo\iTop\Application\UI\iUIBlock[]
	 */
	public function GetSubBlocks()
	{
		return [];
	}

	/**
	 * @inheritDoc
	 * @throws \Exception
	 */
	public function GetJsFilesUrlRecursively(bool $bAbsoluteUrl = false)
	{
		return $this->GetFilesUrlRecursively(static::ENUM_BLOCK_FILES_TYPE_JS, $bAbsoluteUrl);
	}

	/**
	 * @inheritDoc
	 * @throws \Exception
	 */
	public function GetCssFilesUrlRecursively(bool $bAbsoluteUrl = false)
	{
		return $this->GetFilesUrlRecursively(static::ENUM_BLOCK_FILES_TYPE_CSS, $bAbsoluteUrl);
	}

	/**
	 * @return array
	 * @throws \Exception
	 */
	public function GetJsTemplateRelPathRecursively(): array
	{
		return $this->GetUrlRecursively(static::ENUM_BLOCK_FILES_TYPE_JS, static::ENUM_BLOCK_FILES_TYPE_TEMPLATE, false);
	}

	/**
	 * @return array
	 * @throws \Exception
	 */
	public function GetCssTemplateRelPathRecursively(): array
	{
		return $this->GetUrlRecursively(static::ENUM_BLOCK_FILES_TYPE_CSS, static::ENUM_BLOCK_FILES_TYPE_TEMPLATE, false);
	}

	/**
	 * Return an array of the URL of the block $sFilesType and its sub blocks.
	 * URL is relative unless the $bAbsoluteUrl is set to true.
	 *
	 * @param string $sFileType (see static::ENUM_BLOCK_FILES_TYPE_JS, static::ENUM_BLOCK_FILES_TYPE_CSS)
	 * @param bool $bAbsoluteUrl
	 *
	 * @return array
	 * @throws \Exception
	 */
	protected function GetFilesUrlRecursively(string $sFileType, bool $bAbsoluteUrl = false)
	{
		$aFiles = [];
		$sFilesRelPathMethodName = 'Get'.ucfirst($sFileType).'FilesRelPaths';

		// Files from the block itself
		foreach ($this::$sFilesRelPathMethodName() as $sFilePath) {
			$aFiles[] = (($bAbsoluteUrl === true) ? utils::GetAbsoluteUrlAppRoot() : '').$sFilePath;
		}

		// Files from its sub blocks
		foreach ($this->GetSubBlocks() as $sSubBlockName => $oSubBlock) {
			$aFiles = array_merge(
				$aFiles,
				$oSubBlock->GetFilesUrlRecursively($sFileType, $bAbsoluteUrl)
			);
		}

		return $aFiles;
	}


	/**
	 * Return an array of the URL of the block $sFilesType and its sub blocks.
	 * URL is relative unless the $bAbsoluteUrl is set to true.
	 *
	 * @param string $sExtensionFileType (see static::ENUM_BLOCK_FILES_TYPE_JS, static::ENUM_BLOCK_FILES_TYPE_CSS)
	 *
	 * @return array
	 * @throws \Exception
	 */
	protected function GetTemplateRelPathRecursively(string $sExtensionFileType)
	{
		$aFiles = [];

		$sFilesRelPathMethodName = 'Get'.ucfirst($sExtensionFileType).'TemplateRelPath';
		$aFiles[] = $this::$sFilesRelPathMethodName();

		// Files from its sub blocks
		foreach ($this->GetSubBlocks() as $sSubBlockName => $oSubBlock) {
			$aFiles = array_merge(
				$aFiles,
				$oSubBlock->GetTemplateRelPathRecursively($sExtensionFileType)
			);
		}

		return $aFiles;
	}


	public function AddHtml(string $sHTML): iUIBlock
	{
		// By default this does nothing
		return $this;
	}

	public function GetParameters(): array
	{
		return [];
	}
}
