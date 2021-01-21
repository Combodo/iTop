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

namespace Combodo\iTop\Application\UI\Base;


use utils;

/**
 * Class UIBlock
 *
 * @package Combodo\iTop\Application\UI
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @internal
 * @since   3.0.0
 */
abstract class UIBlock implements iUIBlock
{
	/** @var string BLOCK_CODE The block code to use to generate the identifier, the CSS/JS prefixes, ...
	 *
	 * Should start "ibo-" for the iTop backoffice blocks, followed by the name of the block in lower case (eg. for a MyCustomBlock class,
	 * should be "ibo-my-custom-clock")
	 */
	public const BLOCK_CODE = 'ibo-block';

	/** @var string|null GLOBAL_TEMPLATE_REL_PATH Relative path (from <ITOP>/templates/) to the "global" TWIG template which contains HTML, JS inline, JS files, CSS inline, CSS files. Should not be used too often as JS/CSS files would be duplicated making browser parsing time way longer. */
	public const DEFAULT_GLOBAL_TEMPLATE_REL_PATH = null;
	/** @var string|null HTML_TEMPLATE_REL_PATH Relative path (from <ITOP>/templates/) to the HTML template */
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = null;
	/** @var array JS_FILES_REL_PATH Relative paths (from <ITOP>/) to the JS files */
	public const DEFAULT_JS_FILES_REL_PATH = [];
	/** @var string|null JS_TEMPLATE_REL_PATH Relative path (from <ITOP>/templates/) to the JS template on dom ready */
	public const DEFAULT_JS_TEMPLATE_REL_PATH = null;
	/** @var string|null Relative path (from <ITOP>/templates/) to the JS template not deferred */
	public const DEFAULT_JS_LIVE_TEMPLATE_REL_PATH = null;
	/** @var string|null Relative path (from <ITOP>/templates/) to the JS template after DEFAULT_JS_TEMPLATE_REL_PATH */
	public const DEFAULT_JS_ON_READY_TEMPLATE_REL_PATH = null;
	/** @var array CSS_FILES_REL_PATH Relative paths (from <ITOP>/) to the CSS files */
	public const DEFAULT_CSS_FILES_REL_PATH = [];
	/** @var string|null CSS_TEMPLATE_REL_PATH Relative path (from <ITOP>/templates/) to the CSS template */
	public const DEFAULT_CSS_TEMPLATE_REL_PATH = null;
	/** @var bool Default value for $bIsHidden */
	public const DEFAULT_IS_HIDDEN = false;

	/** @var string ENUM_BLOCK_FILES_TYPE_JS */
	public const ENUM_BLOCK_FILES_TYPE_JS = 'js';
	/** @var string ENUM_BLOCK_FILES_TYPE_CSS */
	public const ENUM_BLOCK_FILES_TYPE_CSS = 'css';
	/** @var string ENUM_BLOCK_FILES_TYPE_FILE */
	public const ENUM_BLOCK_FILES_TYPE_FILES = 'files';
	/** @var string ENUM_BLOCK_FILES_TYPE_TEMPLATE */
	public const ENUM_BLOCK_FILES_TYPE_TEMPLATE = 'template';

	/** @var string $sId */
	protected $sId;

	/** @var string */
	protected $sGlobalTemplateRelPath;
	/** @var string */
	protected $sHtmlTemplateRelPath;
	/** @var array */
	protected $aJsTemplateRelPath;
	/** @var string */
	protected $sCssTemplateRelPath;
	/** @var array */
	protected $aJsFilesRelPath;
	/** @var array */
	protected $aCssFilesRelPath;
	/** @var array Array <KEY> => <VALUE> which will be output as HTML data-xxx attributes (eg. data-<KEY>="<VALUE>") */
	protected $aDataAttributes = [];
	/** @var bool show or hide the current block */
	protected $bIsHidden;
	/** @var array */
	protected $aAdditionalCSSClasses = [];

	/**
	 * UIBlock constructor.
	 *
	 * @param string|null $sId
	 */
	public function __construct(?string $sId = null)
	{
		$this->sId = $sId ?? $this->GenerateId();
		$this->aJsFilesRelPath = static::DEFAULT_JS_FILES_REL_PATH;
		$this->aCssFilesRelPath = static::DEFAULT_CSS_FILES_REL_PATH;
		$this->sHtmlTemplateRelPath = static::DEFAULT_HTML_TEMPLATE_REL_PATH;
		$this->aJsTemplateRelPath[self::ENUM_JS_TYPE_LIVE] = static::DEFAULT_JS_LIVE_TEMPLATE_REL_PATH;
		$this->aJsTemplateRelPath[self::ENUM_JS_TYPE_ON_INIT] = static::DEFAULT_JS_TEMPLATE_REL_PATH;
		$this->aJsTemplateRelPath[self::ENUM_JS_TYPE_ON_READY] = static::DEFAULT_JS_ON_READY_TEMPLATE_REL_PATH;
		$this->sCssTemplateRelPath = static::DEFAULT_CSS_TEMPLATE_REL_PATH;
		$this->sGlobalTemplateRelPath = static::DEFAULT_GLOBAL_TEMPLATE_REL_PATH;
		$this->bIsHidden = static::DEFAULT_IS_HIDDEN;
	}

	/**
	 * @inheritDoc
	 */
	public function GetGlobalTemplateRelPath()
	{
		return $this->sGlobalTemplateRelPath;
	}

	/**
	 * @inheritDoc
	 */
	public function GetHtmlTemplateRelPath() {
		return $this->sHtmlTemplateRelPath;
	}

	/**
	 * @inheritDoc
	 */
	public function GetJsTemplateRelPath(string $sType) {
		if (!in_array($sType, [self::ENUM_JS_TYPE_LIVE, self::ENUM_JS_TYPE_ON_INIT, self::ENUM_JS_TYPE_ON_READY])) {
			throw new UIException($this, "Type of javascript $sType not supported");
		}

		return $this->aJsTemplateRelPath[$sType];
	}

	/**
	 * @inheritDoc
	 */
	public function GetJsFilesRelPaths() {
		return $this->aJsFilesRelPath;
	}

	/**
	 * @inheritDoc
	 */
	public function GetCssTemplateRelPath()
	{
		return $this->sCssTemplateRelPath;
	}

	/**
	 * @inheritDoc
	 */
	public function GetCssFilesRelPaths()
	{
		return $this->aCssFilesRelPath;
	}

	/**
	 * Return the block code of the object instance
	 *
	 * @return string
	 * @see static::BLOCK_CODE
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
	 * @return \Combodo\iTop\Application\UI\Base\iUIBlock[]
	 */
	public function GetSubBlocks()
	{
		return [];
	}

	/**
	 * @inheritDoc
	 * @return \Combodo\iTop\Application\UI\Base\iUIBlock[]
	 */
	public function GetDeferredBlocks(): array
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

	public function AddHtml(string $sHTML) {
		// By default this does nothing
		return $this;
	}

	public function GetParameters(): array {
		return [];
	}

	/**
	 * @inheritDoc
	 */
	public function AddJsFileRelPath(string $sPath)
	{
		$this->aJsFilesRelPath[] = $sPath;

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function AddCssFileRelPath(string $sPath)
	{
		$this->aCssFilesRelPath[] = $sPath;

		return $this;
	}

	/**
	 * **Warning**, this shouldn't generate any dot as this will be used in CSS and JQuery selectors !
	 *
	 * @return string a unique ID for the block
	 */
	protected function GenerateId()
	{
		$sUniqId = uniqid(static::BLOCK_CODE.'-', true);
		$sUniqId = str_replace('.', '-', $sUniqId);

		return $sUniqId;
	}

	/**
	 * Return an array of the URL of the block $sFilesType and its sub blocks.
	 * URL is relative unless the $bAbsoluteUrl is set to true.
	 *
	 * @param string $sFileType (see static::ENUM_BLOCK_FILES_TYPE_JS, static::ENUM_BLOCK_FILES_TYPE_CSS)
	 * @param bool   $bAbsoluteUrl
	 *
	 * @return array
	 * @throws \Exception
	 */
	protected function GetFilesUrlRecursively(string $sFileType, bool $bAbsoluteUrl = false) {
		$aFiles = [];
		$sFilesRelPathMethodName = 'Get'.ucfirst($sFileType).'FilesRelPaths';

		// Files from the block itself
		foreach ($this->$sFilesRelPathMethodName() as $sFilePath) {
			$aFiles[] = (($bAbsoluteUrl === true) ? utils::GetAbsoluteUrlAppRoot() : '').$sFilePath;
		}

		// Files from its sub blocks
		foreach ($this->GetSubBlocks() as $sSubBlockName => $oSubBlock) {
			/** @noinspection SlowArrayOperationsInLoopInspection */
			$aFiles = array_merge(
				$aFiles,
				$oSubBlock->GetFilesUrlRecursively($sFileType, $bAbsoluteUrl)
			);
		}

		return $aFiles;
	}

	/**
	 * @param string $sCSSClasses with space as separator, like <code>ibo-is-hidden ibo-alert--body</code>
	 *
	 * @return $this
	 *
	 * @use aAdditionalCSSClasses
	 */
	public function AddCSSClasses(string $sCSSClasses)
	{
		foreach (explode(' ', $sCSSClasses) as $sCSSClass) {
			if (!empty($sCSSClass)) {
				$this->aAdditionalCSSClasses[$sCSSClass] = $sCSSClass;
			}
		}

		return $this;
	}

	/**
	 * Overrides additional classes with the specified value
	 *
	 * @param string $sCSSClasses with space as separator, like <code>ibo-is-hidden ibo-alert--body</code>
	 *
	 * @return $this
	 *
	 * @use aAdditionalCSSClasses
	 */
	public function SetCSSClasses(string $sCSSClasses)
	{
		$this->aAdditionalCSSClasses = [];
		$this->AddCSSClasses($sCSSClasses);

		return $this;
	}

	/**
	 * @return string
	 */
	public function GetAdditionalCSSClasses(): string
	{
		return implode(' ', $this->aAdditionalCSSClasses);
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
	protected function GetTemplateRelPathRecursively(string $sExtensionFileType) {
		$aFiles = [];

		$sFilesRelPathMethodName = 'Get'.ucfirst($sExtensionFileType).'TemplateRelPath';
		$aFiles[] = $this::$sFilesRelPathMethodName();

		// Files from its sub blocks
		foreach ($this->GetSubBlocks() as $sSubBlockName => $oSubBlock) {
			/** @noinspection SlowArrayOperationsInLoopInspection */
			$aFiles = array_merge(
				$aFiles,
				$oSubBlock->GetTemplateRelPathRecursively($sExtensionFileType)
			);
		}

		return $aFiles;
	}


	/**
	 * @return array
	 */
	public function GetDataAttributes(): array
	{
		return $this->aDataAttributes;
	}

	/**
	 * @param array $aDataAttributes
	 *
	 * @return $this
	 */
	public function SetDataAttributes(array $aDataAttributes)
	{
		$this->aDataAttributes = $aDataAttributes;

		return $this;
	}


	/**
	 * @param string $sName
	 * @param string $sValue
	 *
	 * @return $this
	 */
	public function AddDataAttribute(string $sName, string $sValue)
	{
		$this->aDataAttributes[$sName] = $sValue;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function IsHidden(): bool
	{
		return $this->bIsHidden;
	}

	/**
	 * @param bool $bIsHidden
	 *
	 * @return $this
	 */
	public function SetIsHidden(bool $bIsHidden)
	{
		$this->bIsHidden = $bIsHidden;

		return $this;
	}
}
