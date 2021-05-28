<?php
/**
 * Copyright (C) 2013-2021 Combodo SARL
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

	/** @var string|null */
	public const DEFAULT_GLOBAL_TEMPLATE_REL_PATH = null;
	/** @var string|null */
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = null;
	/**
	 * @var array list of external JS file paths to include in the page. Paths are relative to APPROOT
	 *    **Warning** : if you need to call a JS var defined in one of this file, then this calling code MUST be in {@see DEFAULT_JS_ON_READY_TEMPLATE_REL_PATH}
	 *         and not in {@see DEFAULT_JS_TEMPLATE_REL_PATH} ! Indeed the later is output before external files loading.
	 */
	public const DEFAULT_JS_FILES_REL_PATH = [];
	/** @var string|null */
	public const DEFAULT_JS_TEMPLATE_REL_PATH = null;
	/** @var string|null Relative path (from <ITOP>/templates/) to the JS template not deferred */
	public const DEFAULT_JS_LIVE_TEMPLATE_REL_PATH = null;
	/** @var string|null Relative path (from <ITOP>/templates/) to the JS template after DEFAULT_JS_TEMPLATE_REL_PATH */
	public const DEFAULT_JS_ON_READY_TEMPLATE_REL_PATH = null;
	/** @var array */
	public const DEFAULT_CSS_FILES_REL_PATH = [];
	/** @var string|null */
	public const DEFAULT_CSS_TEMPLATE_REL_PATH = null;
	/** @var bool */
	public const DEFAULT_IS_HIDDEN = false;
	/** @var array */
	public const DEFAULT_ADDITIONAL_CSS_CLASSES = [];

	/** @var string ENUM_BLOCK_FILES_TYPE_JS */
	public const ENUM_BLOCK_FILES_TYPE_JS = 'js';
	/** @var string ENUM_BLOCK_FILES_TYPE_CSS */
	public const ENUM_BLOCK_FILES_TYPE_CSS = 'css';
	/** @var string ENUM_BLOCK_FILES_TYPE_FILE */
	public const ENUM_BLOCK_FILES_TYPE_FILES = 'files';
	/** @var string ENUM_BLOCK_FILES_TYPE_TEMPLATE */
	public const ENUM_BLOCK_FILES_TYPE_TEMPLATE = 'template';

	/** @var array Cache for the CSS classes of a block inheritance. Key is the block class, value is an array of CSS classes */
	private static $aBlocksInheritanceCSSClassesCache = [];

	/** @var string $sId */
	protected $sId;

	/** @var string Relative path (from <ITOP>/templates/) to the "global" TWIG template which contains HTML, JS inline, JS files, CSS inline, CSS files. Should not be used too often as JS/CSS files would be duplicated making browser parsing time way longer. */
	protected $sGlobalTemplateRelPath;
	/** @var string Relative path (from <ITOP>/templates/) to the HTML template */
	protected $sHtmlTemplateRelPath;
	/** @var array Relative paths (from <ITOP>/templates/) to the JS templates (Live, on init., on ready) */
	protected $aJsTemplatesRelPath;
	/** @var string Relative path (from <ITOP>/templates/) to the CSS template */
	protected $sCssTemplateRelPath;
	/** @var array Relative paths (from <ITOP>/) to the JS files */
	protected $aJsFilesRelPath;
	/** @var array Relative paths (from <ITOP>/) to the CSS files */
	protected $aCssFilesRelPath;
	/** @var array Array <KEY> => <VALUE> which will be output as HTML data-xxx attributes (eg. data-<KEY>="<VALUE>") */
	protected $aDataAttributes = [];
	/** @var bool Whether the current block is shown or hidden */
	protected $bIsHidden;
	/** @var array Additional CSS classes to put on the block */
	protected $aAdditionalCSSClasses;

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
		$this->aJsTemplatesRelPath[self::ENUM_JS_TYPE_LIVE] = static::DEFAULT_JS_LIVE_TEMPLATE_REL_PATH;
		$this->aJsTemplatesRelPath[self::ENUM_JS_TYPE_ON_INIT] = static::DEFAULT_JS_TEMPLATE_REL_PATH;
		$this->aJsTemplatesRelPath[self::ENUM_JS_TYPE_ON_READY] = static::DEFAULT_JS_ON_READY_TEMPLATE_REL_PATH;
		$this->sCssTemplateRelPath = static::DEFAULT_CSS_TEMPLATE_REL_PATH;
		$this->sGlobalTemplateRelPath = static::DEFAULT_GLOBAL_TEMPLATE_REL_PATH;
		$this->bIsHidden = static::DEFAULT_IS_HIDDEN;
		$this->aAdditionalCSSClasses = static::DEFAULT_ADDITIONAL_CSS_CLASSES;
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
	public function GetJsTemplatesRelPath(string $sType) {
		if (!in_array($sType, [self::ENUM_JS_TYPE_LIVE, self::ENUM_JS_TYPE_ON_INIT, self::ENUM_JS_TYPE_ON_READY])) {
			throw new UIException($this, "Type of javascript $sType not supported");
		}

		return $this->aJsTemplatesRelPath[$sType];
	}

	/**
	 * @inheritDoc
	 * @used-by \Combodo\iTop\Application\UI\Base\UIBlock::GetFilesUrlRecursively
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
	 * @used-by \Combodo\iTop\Application\UI\Base\UIBlock::GetFilesUrlRecursively
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
	 * Note: If $sCSSClass is already present, proceeds silently
	 *
	 * @param string $sCSSClass CSS class to add to the generated html block
	 *
	 * @return $this
	 *
	 * @uses $aAdditionalCSSClasses
	 */
	public function AddCSSClass(string $sCSSClass)
	{
		$sCSSClass = trim($sCSSClass);

		if (!array_key_exists($sCSSClass, $this->aAdditionalCSSClasses)) {
			$this->aAdditionalCSSClasses[] = $sCSSClass;
		}

		return $this;
	}

	/**
	 * Note: If $sCSSClass is not present, proceeds silently
	 *
	 * @param string $sCSSClass
	 *
	 * @return $this
	 *
	 * @uses $aAdditionalCSSClasses
	 */
	public function RemoveCSSClass(string $sCSSClass)
	{
		if (array_key_exists($sCSSClass, $this->aAdditionalCSSClasses)) {
			unset($this->aAdditionalCSSClasses[$sCSSClass]);
		}

		return $this;
	}

	/**
	 * @param array $aCSSClasses like <code>['ibo-is-hidden', 'ibo-alert--body']</code>
	 *
	 * @return $this
	 *
	 * @uses $aAdditionalCSSClasses
	 */
	public function AddCSSClasses(array $aCSSClasses)
	{
		foreach ($aCSSClasses as $sCSSClass) {
			if (!empty($sCSSClass)) {
				$this->AddCSSClass($sCSSClass);
			}
		}

		return $this;
	}

	/**
	 * Overrides additional classes with the specified value
	 *
	 * @param array $aCSSClasses like <code>['ibo-is-hidden', 'ibo-alert--body']</code>
	 *
	 * @return $this
	 *
	 * @uses $aAdditionalCSSClasses
	 */
	public function SetCSSClasses(array $aCSSClasses)
	{
		$this->aAdditionalCSSClasses = [];
		$this->AddCSSClasses($aCSSClasses);

		return $this;
	}

	/**
	 * @return array
	 *
	 * @uses $aAdditionalCSSClasses
	 * @see static::GetAdditionalCSSClassesAsString() for a simpler usage in the views
	 */
	public function GetAdditionalCSSClasses(): array
	{
		return $this->aAdditionalCSSClasses;
	}

	/**
	 * @return string All additional CSS classes as a spec-separated string
	 *
	 * @uses static::GetAdditionalCSSClasses()
	 */
	public function GetAdditionalCSSClassesAsString(): string
	{
		return implode(' ', $this->GetAdditionalCSSClasses());
	}

	/**
	 * @return string[] The identification CSS classes of the all the parent blocks of the current one
	 */
	public function GetBlocksInheritanceCSSClasses(): array
	{
		$sCurrentClass = static::class;

		// Add to cache if not already there
		if (false === array_key_exists($sCurrentClass, self::$aBlocksInheritanceCSSClassesCache)) {
			// Start with self
			$aCSSClasses = [static::BLOCK_CODE];

			// Get class ONLY from parents that implement iUIBlock
			foreach (class_parents(static::class) as $sParentClass) {
				if (false === in_array(iUIBlock::class, class_implements($sParentClass))) {
					continue;
				}

				$aCSSClasses[] = $sParentClass::BLOCK_CODE;
			}

			self::$aBlocksInheritanceCSSClassesCache[$sCurrentClass] = $aCSSClasses;
		}

		return self::$aBlocksInheritanceCSSClassesCache[$sCurrentClass];
	}

	/**
	 * @see static::GetBlocksInheritanceCSSClasses()
	 * @return string Same as the regular method but as a space separated string
	 */
	public function GetBlocksInheritanceCSSClassesAsString(): string
	{
		return implode(' ', $this->GetBlocksInheritanceCSSClasses());
	}

	/**
	 * @return array
	 */
	public function GetDataAttributes(): array
	{
		return $this->aDataAttributes;
	}

	/**
	 * @param array $aDataAttributes Array of data attributes in the format ['name' => 'value']
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
	 * @uses static::$aDataAttributes
	 */
	public function HasDataAttributes(): bool
	{
		return !empty($this->aDataAttributes);
	}

	/**
	 * @return bool
	 */
	public function IsHidden(): bool
	{
		return $this->bIsHidden;
	}

	/**
	 * @param bool $bIsHidden Indicates if the block is hidden by default
	 *
	 * @return $this
	 */
	public function SetIsHidden(bool $bIsHidden)
	{
		$this->bIsHidden = $bIsHidden;

		return $this;
	}
}
