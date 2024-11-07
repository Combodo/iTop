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
	/**
	 * @var string The block code to use to generate the identifier, the CSS/JS prefixes, ...
	 * Should start "ibo-" for the iTop backoffice blocks, followed by the name of the block in lower case (eg. for a MyCustomBlock class, should be "ibo-my-custom-block")
	 */
	public const BLOCK_CODE = 'ibo-block';
	/**
	 * @var bool Set to true so the block automatically requires/includes its ancestors' external JS files. If set to false, only the files from the block itself will be included
	 * @see static::DEFAULT_JS_FILES_REL_PATH
	 */
	public const REQUIRES_ANCESTORS_DEFAULT_JS_FILES = false;
	/**
	 * @var bool Set to true so the block automatically requires/includes its ancestors' external CSS files. If set to false, only the files from the block itself will be included
	 * @see static::DEFAULT_CSS_FILES_REL_PATH
	 */
	public const REQUIRES_ANCESTORS_DEFAULT_CSS_FILES = false;

	/**
	 * @var string|null
	 * @see static::$GetGlobalTemplateRelPath
	 */
	public const DEFAULT_GLOBAL_TEMPLATE_REL_PATH = null;
	/**
	 * @var string|null
	 * @see static::$sHtmlTemplateRelPath
	 */
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = null;
	/**
	 * @var array
	 * @see static::$aJsFilesRelPath
	 */
	public const DEFAULT_JS_FILES_REL_PATH = [
		'js/ui-block.js',
	];
	/**
	 * @var string|null Relative path (from <ITOP>/templates/) to the "on init" JS template
	 * @see static::$aJsTemplatesRelPath
	 * @see iUIBlock::ENUM_JS_TYPE_ON_INIT
	 */
	public const DEFAULT_JS_TEMPLATE_REL_PATH = null;
	/**
	 * @var string|null Relative path (from <ITOP>/templates/) to the JS template not deferred
	 * @see static::$aJsTemplatesRelPath
	 * @see iUIBlock::ENUM_JS_TYPE_LIVE
	 */
	public const DEFAULT_JS_LIVE_TEMPLATE_REL_PATH = null;
	/**
	 * @var string|null Relative path (from <ITOP>/templates/) to the JS template after DEFAULT_JS_TEMPLATE_REL_PATH
	 * @see static::$aJsTemplatesRelPath
	 * @see iUIBlock::ENUM_JS_TYPE_ON_READY
	 */
	public const DEFAULT_JS_ON_READY_TEMPLATE_REL_PATH = null;
	/**
	 * @var array
	 * @see static::$aCssFilesRelPath
	 */
	public const DEFAULT_CSS_FILES_REL_PATH = [];
	/**
	 * @var string|null
	 * @see static::$sCssTemplateRelPath
	 */
	public const DEFAULT_CSS_TEMPLATE_REL_PATH = null;
	/**
	 * @var bool
	 * @see static::$bIsHidden
	 */
	public const DEFAULT_IS_HIDDEN = false;
	/**
	 * @var array
	 * @see static::$aAdditionalCSSClasses
	 */
	public const DEFAULT_ADDITIONAL_CSS_CLASSES = [];

	/** @var array Cache for the CSS classes of a block inheritance. Key is the block class, value is an array of CSS classes */
	private static $aBlocksInheritanceCSSClassesCache = [];

	/** @var string ID of the block */
	protected $sId;

	/**
	 * @var string|null
	 * @see iUIBlock::GetGlobalTemplateRelPath()
	 */
	protected $sGlobalTemplateRelPath;
	/**
	 * @var string|null
	 * @see iUIBlock::GetHtmlTemplateRelPath()
	 */
	protected $sHtmlTemplateRelPath;
	/**
	 * @var array Relative paths (from <ITOP>/templates/) to the JS templates (Live, on init., on ready)
	 * Key is the JS type ({@see iUIBlock::ENUM_JS_TYPE_LIVE}, ...), value is the relative path to the template
	 * @see iUIBlock::GetJsTemplatesRelPath()
	 */
	protected $aJsTemplatesRelPath;
	/**
	 * @var string|null
	 * @see iUIBlock::GetCssTemplateRelPath()
	 */
	protected $sCssTemplateRelPath;
	/**
	 * @var array Relative paths (from <ITOP>/) to the external JS files to include in the page.
	 *
	 * **Warning**: If you need to call a JS var defined in one of this file, then this calling code MUST be in {@see static::DEFAULT_JS_ON_READY_TEMPLATE_REL_PATH}
	 * and not in {@see static::DEFAULT_JS_TEMPLATE_REL_PATH} ! Indeed the later is output before external files loading.
	 */
	protected $aJsFilesRelPath = [];
	/**
	 * @var array
	 * @see iUIBlock::GetCssFilesRelPaths()
	 */
	protected $aCssFilesRelPath = [];
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

		// Add external JS files
		// 1) From ancestors if they are required
		if (static::REQUIRES_ANCESTORS_DEFAULT_JS_FILES) {
			// Include ancestors files
			foreach (array_reverse(class_parents(static::class)) as $sParentClass) {
				$this->AddMultipleJsFilesRelPaths($sParentClass::DEFAULT_JS_FILES_REL_PATH);
			}
		}

		// 2) For current class if they are explicitely defined/overloaded, otherwise it will require the files from the closest ancestor with the constant definition which we don't want; which means:
		//  - If this is the root class
		//  - If this class requires ancestors files in which case it requires itselves
		//  - If this class overloads files from its parent
		//      IMPORTANT: We don't have a way -yet- to determine if the instantiated class has overloaded the constant directly
		//      So we simply check if the instantiated class constant is different from its parent
		$mParentClass = get_parent_class(static::class);
		if ((false === $mParentClass)
			|| (true === static::REQUIRES_ANCESTORS_DEFAULT_JS_FILES)
			|| ($mParentClass::DEFAULT_JS_FILES_REL_PATH !== static::DEFAULT_JS_FILES_REL_PATH)
		) {
			$this->AddMultipleJsFilesRelPaths(static::DEFAULT_JS_FILES_REL_PATH);
		}

		// Add external CSS files
		// 1) From ancestors if they are required
		if (static::REQUIRES_ANCESTORS_DEFAULT_CSS_FILES) {
			// Include ancestors files
			foreach (array_reverse(class_parents(static::class)) as $sParentClass) {
				$this->AddMultipleCssFilesRelPaths($sParentClass::DEFAULT_CSS_FILES_REL_PATH);
			}
		}

		// 2) For current class if they are explicitely defined/overloaded, otherwise it will require the files from the closest ancestor with the constant definition which we don't want; which means:
		//  - If this is the root class
		//  - If this class requires ancestors files in which case it requires itselves
		//  - If this class overloads files from its parent
		//      IMPORTANT: We don't have a way -yet- to determine if the instantiated class has overloaded the constant directly
		//      So we simply check if the instantiated class constant is different from its parent
		$mParentClass = get_parent_class(static::class);

		if ((false === $mParentClass)
			|| (true === static::REQUIRES_ANCESTORS_DEFAULT_CSS_FILES)
			|| ($mParentClass::DEFAULT_CSS_FILES_REL_PATH !== static::DEFAULT_CSS_FILES_REL_PATH)
		) {
			$this->AddMultipleCssFilesRelPaths(static::DEFAULT_CSS_FILES_REL_PATH);
		}

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
	public function GetGlobalTemplateRelPath(): ?string
	{
		return $this->sGlobalTemplateRelPath;
	}

	/**
	 * @inheritDoc
	 */
	public function GetHtmlTemplateRelPath(): ?string
	{
		return $this->sHtmlTemplateRelPath;
	}

	/**
	 * @inheritDoc
	 */
	public function GetJsTemplatesRelPath(string $sType): ?string
	{
		if (!in_array($sType, [self::ENUM_JS_TYPE_LIVE, self::ENUM_JS_TYPE_ON_INIT, self::ENUM_JS_TYPE_ON_READY])) {
			throw new UIException($this, "Type of javascript $sType not supported");
		}

		return $this->aJsTemplatesRelPath[$sType];
	}

	/**
	 * @inheritDoc
	 * @used-by \Combodo\iTop\Application\UI\Base\UIBlock::GetFilesUrlRecursively
	 */
	public function GetJsFilesRelPaths(): array
	{
		return $this->aJsFilesRelPath;
	}

	/**
	 * @inheritDoc
	 */
	public function GetCssTemplateRelPath(): ?string
	{
		return $this->sCssTemplateRelPath;
	}

	/**
	 * @inheritDoc
	 * @used-by \Combodo\iTop\Application\UI\Base\UIBlock::GetFilesUrlRecursively
	 */
	public function GetCssFilesRelPaths(): array
	{
		return $this->aCssFilesRelPath;
	}

	/**
	 * Return the block code of the object instance
	 *
	 * @return string
	 * @see static::BLOCK_CODE
	 */
	public function GetBlockCode(): string
	{
		return static::BLOCK_CODE;
	}

	/**
	 * @inheritDoc
	 */
	public function GetId(): string
	{
		return $this->sId;
	}

	/**
	 * @inheritDoc
	 * @return \Combodo\iTop\Application\UI\Base\iUIBlock[]
	 */
	public function GetSubBlocks(): array
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
	public function GetJsFilesUrlRecursively(bool $bAbsoluteUrl = false): array
	{
		return $this->GetFilesUrlRecursively(static::ENUM_BLOCK_FILES_TYPE_JS, $bAbsoluteUrl);
	}

	/**
	 * @inheritDoc
	 * @throws \Exception
	 */
	public function GetCssFilesUrlRecursively(bool $bAbsoluteUrl = false): array
	{
		return $this->GetFilesUrlRecursively(static::ENUM_BLOCK_FILES_TYPE_CSS, $bAbsoluteUrl);
	}

	/**
	 * @inheritDoc
	 */
	public function AddHtml(string $sHTML) {
		// By default this does nothing
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function GetParameters(): array {
		return [];
	}

	/**
	 * @inheritDoc
	 */
	public function AddJsFileRelPath(string $sPath)
	{
		if(!in_array($sPath, $this->aJsFilesRelPath)) {
			$this->aJsFilesRelPath[] = $sPath;
		}

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function AddMultipleJsFilesRelPaths(array $aPaths)
	{
		foreach($aPaths as $sPath){
			$this->AddJsFileRelPath($sPath);
		}

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function AddCssFileRelPath(string $sPath)
	{
		if(!in_array($sPath, $this->aCssFilesRelPath)) {
			$this->aCssFilesRelPath[] = $sPath;
		}

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function AddMultipleCssFilesRelPaths(array $aPaths)
	{
		foreach($aPaths as $sPath){
			$this->AddCssFileRelPath($sPath);
		}

		return $this;
	}

	/**
	 * **Warning**, this shouldn't generate any dot as this will be used in CSS and JQuery selectors !
	 *
	 * @return string a unique ID for the block
	 */
	protected function GenerateId(): string
	{
		$sUniqId = uniqid(static::BLOCK_CODE.'-', true);
		$sUniqId = utils::Sanitize($sUniqId, '', utils::ENUM_SANITIZATION_FILTER_ELEMENT_IDENTIFIER);

		return $sUniqId;
	}

	/**
	 * Return an array of the URL of the block's $sFilesType and its sub blocks.
	 * URL is relative unless the $bAbsoluteUrl is set to true.
	 *
	 * @param string $sFileType (see static::ENUM_BLOCK_FILES_TYPE_JS, static::ENUM_BLOCK_FILES_TYPE_CSS)
	 * @param bool   $bAbsoluteUrl
	 *
	 * @return array
	 * @throws \Exception
	 */
	protected function GetFilesUrlRecursively(string $sFileType, bool $bAbsoluteUrl = false): array
	{
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
	 * @see static::$aAdditionalCSSClasses
	 */
	public function AddCSSClass(string $sCSSClass)
	{
		$sCSSClass = trim($sCSSClass);

		if (!array_key_exists($sCSSClass, $this->aAdditionalCSSClasses)) {
			$this->aAdditionalCSSClasses[$sCSSClass] = $sCSSClass;
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
	 * @see static::$aAdditionalCSSClasses
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
	 * @see static::$aAdditionalCSSClasses
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
	 * @see static::$aAdditionalCSSClasses
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
	 * @see static::$aAdditionalCSSClasses
	 * @see static::GetAdditionalCSSClassesAsString() for a simpler usage in the views
	 */
	public function GetAdditionalCSSClasses(): array
	{
		return $this->aAdditionalCSSClasses;
	}

	/**
	 * @return string All additional CSS classes as a spec-separated string
	 *
	 * @see static::GetAdditionalCSSClasses()
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
	 * @see static::$aDataAttributes
	 */
	public function GetDataAttributes(): array
	{
		return $this->aDataAttributes;
	}

	/**
	 * @param array $aDataAttributes Array of data attributes in the format ['name' => 'value']
	 *
	 * @return $this
	 * @see static::$aDataAttributes
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
	 * @see static::$aDataAttributes
	 */
	public function AddDataAttribute(string $sName, string $sValue)
	{
		$this->aDataAttributes[$sName] = $sValue;

		return $this;
	}

	/**
	 * @param string $sName Name of the data attribute
	 *
	 * @return bool True if $sName is already defined (even as a null value) in the UIBLock data attributes, false otherwise
	 * @see static::$aDataAttributes
	 * @since 3.0.4 3.1.0 N°6140
	 */
	public function HasDataAttribute(string $sName): bool
	{
		return array_key_exists($sName, $this->aDataAttributes);
	}

	/**
	 * @return bool
	 * @see static::$aDataAttributes
	 */
	public function HasDataAttributes(): bool
	{
		return !empty($this->aDataAttributes);
	}

	/**
	 * @return bool
	 * @see static::$bIsHidden
	 */
	public function IsHidden(): bool
	{
		return $this->bIsHidden;
	}

	/**
	 * @param bool $bIsHidden
	 *
	 * @return $this
	 * @see static::$bIsHidden
	 */
	public function SetIsHidden(bool $bIsHidden)
	{
		$this->bIsHidden = $bIsHidden;

		return $this;
	}
}
