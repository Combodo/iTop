<?php
/**
 * Copyright (C) 2013-2023 Combodo SARL
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

namespace Combodo\iTop\Portal\Twig;

use Combodo\iTop\Application\Helper\CKEditorHelper;
use Twig\Extension\AbstractExtension;

use Twig\TwigFunction;

/**
 * Class CKEditorExtension
 *
 * Twig functions for CKEditor.
 *
 * @package Combodo\iTop\Portal\Twig
 * @since 3.2.0
 */
class CKEditorExtension extends AbstractExtension
{
	/** @inheritdoc  */
	public function getFunctions() : array
	{
		return [
			new TwigFunction('inject_ckeditor_resources', [$this, 'injectCKEditorResources']),
		];
	}

	/**
	 * Inject CKEditor resources.
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function injectCKEditorResources() : string
	{
		$sScriptTemplate = '';
		$aJSFilesRelPaths = CKEditorHelper::GetJSFilesRelPathsForCKEditor();

		foreach ($aJSFilesRelPaths as $sJSFileRelPath){
			$sUrl = \utils::GetAbsoluteUrlAppRoot() . $sJSFileRelPath;
			$sUrl = \utils::AddParameterToUrl($sUrl, 't', \utils::GetCacheBusterTimestamp());
			$sScriptTemplate .= '<script type="text/javascript" src="' . $sUrl . '"></script>';
		}

		return $sScriptTemplate;
	}
}