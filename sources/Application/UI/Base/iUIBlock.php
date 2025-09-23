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


/**
 * Interface iUIBlock
 *
 * UIBlocks are the foundation of the new UI system. They aim at providing reusable components with better maintenability and segreagtion.
 * Used only in the backoffice for now.
 *
 * @package Combodo\iTop\Application\UI
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @internal
 * @since   3.0.0
 */
interface iUIBlock {
	/** @var string "Live" or "inline" JS is to be executed immediately when the code is parsed */
	public const ENUM_JS_TYPE_LIVE = "live.js";
	/** @var string "On init" JS is to be executed immediately when the DOM is ready */
	public const ENUM_JS_TYPE_ON_INIT = "js";
	/** @var string "On ready" JS is to be executed slightly after the "on init" JSs when the DOM is ready */
	public const ENUM_JS_TYPE_ON_READY = "ready.js";

	/** @var string */
	public const ENUM_BLOCK_FILES_TYPE_JS = 'js';
	/** @var string */
	public const ENUM_BLOCK_FILES_TYPE_CSS = 'css';
	/** @var string */
	public const ENUM_BLOCK_FILES_TYPE_FILES = 'files';
	/** @var string */
	public const ENUM_BLOCK_FILES_TYPE_TEMPLATE = 'template';

	/**
	 * Should not be used too often as JS/CSS files would be duplicated making the browser parsing time way longer.
	 *
	 * @return string|null The relative path (from <ITOP>/templates/) of the "global" template (which contains HTML, JS inline, JS files, CSS inline, CSS files) to use or null if it's not provided.
	 */
	public function GetGlobalTemplateRelPath(): ?string;

	/**
	 * @return string|null The relative path (from <ITOP>/templates/) of the HTML template to use or null if no HTML to render
	 */
	public function GetHtmlTemplateRelPath(): ?string;

	/**
	 * @param string $sType Javascript type {@see static::ENUM_JS_TYPE_LIVE and others}
	 *
	 * @return string|null The relative path (from <ITOP>/templates/) of the JS template (for $sType) to use or null if there is no inline JS (for $sType) to render
	 */
	public function GetJsTemplatesRelPath(string $sType): ?string;

	/**
	 * @return array Array of the relative paths (from <ITOP>/) of the JS files to use for the block itself
	 */
	public function GetJsFilesRelPaths(): array;

	/**
	 * @return string|null The relative path (from <ITOP>/templates/) of the CSS template to use or null if there is no inline CSS to render
	 */
	public function GetCssTemplateRelPath(): ?string;

	/**
	 * @return array Array of the relative paths (from <ITOP>/) of the CSS files to use for the block itself
	 */
	public function GetCssFilesRelPaths(): array;

	/**
	 * @return string ID of the block
	 */
	public function GetId(): string;

	/**
	 * @return \Combodo\iTop\Application\UI\Base\iUIBlock[] Array iUIBlock embedded in this iUIBlock. Must be an associative array (<BLOCK_ID> => <BLOCK_INSTANCE>)
	 */
	public function GetSubBlocks(): array;

	/**
	 * @return \Combodo\iTop\Application\UI\Base\iUIBlock[] Array of iUIBlock to add at the end of the page. Must be an associative array (<BLOCK_ID> => <BLOCK_INSTANCE>)
	 */
	public function GetDeferredBlocks(): array;

	/**
	 * @param bool $bAbsoluteUrl If set to true, URLs will be absolute, otherwise they will be relative to the app. root
	 *
	 * @return string[] Array of the JS files URL necessary for the block and all its sub blocks.
	 */
	public function GetJsFilesUrlRecursively(bool $bAbsoluteUrl = false): array;

	/**
	 * @param bool $bAbsoluteUrl If set to true, URLs will be absolute, otherwise they will be relative to the app. root
	 *
	 * @return string[] Array of the CSS files URL necessary for the block and all its sub blocks.
	 */
	public function GetCssFilesUrlRecursively(bool $bAbsoluteUrl = false): array;

	/**
	 * Add HTML code to the current block
	 *
	 * @param string $sHTML
	 *
	 * @return $this
	 */
	public function AddHtml(string $sHTML);

	/**
	 * @return array Block's specific parameters
	 */
	public function GetParameters(): array;

	/**
	 * Add a JS file to a block (if not already present)
	 *
	 * @param string $sPath relative path of a JS file to add
	 *
	 * @return $this
	 */
	public function AddJsFileRelPath(string $sPath);

	/**
	 * Add several JS files to a block.
	 * Duplicates will not be added.
	 *
	 * @param string[] $aPaths
	 *
	 * @return mixed
	 */
	public function AddMultipleJsFilesRelPaths(array $aPaths);

	/**
	 * Add a CSS file to a block (if not already present)
	 *
	 * @param string $sPath relative path of a CSS file to add
	 *
	 * @return $this
	 */
	public function AddCssFileRelPath(string $sPath);

	/**
	 * Add several CSS files to a block.
	 * Duplicates will not be added.
	 *
	 * @param string[] $aPaths
	 *
	 * @return mixed
	 */
	public function AddMultipleCssFilesRelPaths(array $aPaths);

}
