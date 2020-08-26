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


/**
 * Interface iUIBlock
 *
 * @package Combodo\iTop\Application\UI
 * @internal
 * @since 2.8.0
 */
interface iUIBlock
{
	/**
	 * Return the relative path (from <ITOP>/templates/) of the global template (HTML, JS, CSS) to use or null if it's not provided. Should not be used to often as JS/CSS files would be duplicated making the browser parsing time way longer.
	 *
	 * @return string|null
	 */
	public static function GetGlobalTemplateRelPath();

	/**
	 * Return the relative path (from <ITOP>/templates/) of the HTML template to use or null if no HTML to render
	 *
	 * @return string|null
	 */
	public static function GetHtmlTemplateRelPath();

	/**
	 * Return the relative path (from <ITOP>/templates/) of the JS template to use or null if there is no inline JS to render
	 *
	 * @return string|null
	 */
	public static function GetJsTemplateRelPath();

	/**
	 * Return an array of the relative paths (from <ITOP>/) of the JS files to use for the block itself
	 *
	 * @return array
	 */
	public static function GetJsFilesRelPaths();

	/**
	 * Return the relative path (from <ITOP>/templates/) of the CSS template to use or null if there is no inline CSS to render
	 *
	 * @return string|null
	 */
	public static function GetCssTemplateRelPath();

	/**
	 * Return an array of the relative paths (from <ITOP>/) of the CSS files to use for the block itself
	 *
	 * @return array
	 */
	public static function GetCssFilesRelPaths();

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
	 * @return \Combodo\iTop\Application\UI\iUIBlock[]
	 */
	public function GetSubBlocks();

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
}