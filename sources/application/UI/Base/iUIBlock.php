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


/**
 * Interface iUIBlock
 *
 * @package Combodo\iTop\Application\UI
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @internal
 * @since   3.0.0
 */
interface iUIBlock {
	public const ENUM_JS_TYPE_ON_INIT = "js";
	public const ENUM_JS_TYPE_LIVE = "live.js";
	public const ENUM_JS_TYPE_ON_READY = "ready.js";
	/**
	 * Return the relative path (from <ITOP>/templates/) of the global template (HTML, JS, CSS) to use or null if it's not provided. Should not be used to often as JS/CSS files would be duplicated making the browser parsing time way longer.
	 *
	 * @return string|null
	 */
	public function GetGlobalTemplateRelPath();

	/**
	 * Return the relative path (from <ITOP>/templates/) of the HTML template to use or null if no HTML to render
	 *
	 * @return string|null
	 */
	public function GetHtmlTemplateRelPath();

	/**
	 * Return the relative path (from <ITOP>/templates/) of the JS template to use or null if there is no inline JS to render
	 *
	 * @param string $sType javascript type only ENUM_JS_TYPE_ON_INIT / ENUM_JS_TYPE_ON_READY / ENUM_JS_TYPE_LIVE
	 *
	 * @return string|null
	 */
	public function GetJsTemplatesRelPath(string $sType) ;

	/**
	 * Return an array of the relative paths (from <ITOP>/) of the JS files to use for the block itself
	 *
	 * @return array
	 */
	public function GetJsFilesRelPaths();

	/**
	 * Return the relative path (from <ITOP>/templates/) of the CSS template to use or null if there is no inline CSS to render
	 *
	 * @return string|null
	 */
	public function GetCssTemplateRelPath();

	/**
	 * Return an array of the relative paths (from <ITOP>/) of the CSS files to use for the block itself
	 *
	 * @return array
	 */
	public function GetCssFilesRelPaths();

	/**
	 * Return the ID of the block
	 *
	 * @return string
	 */
	public function GetId();

	/**
	 * Return an array iUIBlock embedded in this iUIBlock
	 * Must be an associative array (<BLOCK_ID> => <BLOCK_INSTANCE>)
	 *
	 * @return \Combodo\iTop\Application\UI\Base\iUIBlock[]
	 */
	public function GetSubBlocks();

	/**
	 * Return an array of iUIBlock to add at the end of the page
	 * Must be an associative array (<BLOCK_ID> => <BLOCK_INSTANCE>)
	 *
	 * @return \Combodo\iTop\Application\UI\Base\iUIBlock[]
	 */
	public function GetDeferredBlocks(): array;

	/**
	 * Return an array of the JS files URL necessary for the block and all its sub blocks.
	 * URLs are relative unless the $bAbsolutePath is set to true.
	 *
	 * @param bool $bAbsoluteUrl
	 *
	 * @return string[]
	 */
	public function GetJsFilesUrlRecursively(bool $bAbsoluteUrl = false);

	/**
	 * Return an array of the CSS files URL necessary for the block and all its sub blocks.
	 * URLs are relative unless the $bAbsolutePath is set to true.
	 *
	 * @param bool $bAbsoluteUrl
	 *
	 * @return string[]
	 * @throws \Exception
	 */
	public function GetCssFilesUrlRecursively(bool $bAbsoluteUrl = false);

	/**
	 * Add HTML code to the current block
	 *
	 * @param string $sHTML
	 *
	 * @return $this
	 */
	public function AddHtml(string $sHTML);

	/**
	 * Return block specific parameters
	 *
	 * @return array
	 */
	public function GetParameters(): array;

	/**
	 * Add a JS file to a block
	 *
	 * @param string $sPath
	 *
	 * @return $this
	 */
	public function AddJsFileRelPath(string $sPath);

	/**
	 * Add a CSS file to a block
	 *
	 * @param string $sPath
	 *
	 * @return $this
	 */
	public function AddCssFileRelPath(string $sPath);

}
