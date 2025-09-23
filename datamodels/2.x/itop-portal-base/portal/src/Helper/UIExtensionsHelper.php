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


namespace Combodo\iTop\Portal\Helper;


use InvalidParameterException;
use iPortalUIExtension;
use MetaModel;
use Symfony\Component\DependencyInjection\ContainerInterface;
use utils;

/**
 * Class UIExtensionsHelper
 *
 * @property array css_files
 * @property string|null css_inline
 * @property array js_files
 * @property string|null js_inline
 * @property array html
 *
 * @package Combodo\iTop\Portal\Helper
 * @since 2.7.0
 */
class UIExtensionsHelper
{
	/** @var null|array $aUIExtensions Lazy loaded (hence the null by default) */
	protected $aUIExtensions;
	/** @var \Symfony\Component\DependencyInjection\Container $oContainer */
	private $oContainer;

	/**
	 * UIExtensionsHelper constructor.
	 *
	 * @param \Symfony\Component\DependencyInjection\ContainerInterface $oContainer
	 */
	public function __construct(ContainerInterface $oContainer)
	{
		$this->oContainer = $oContainer;
	}

	/**
	 * @param string $sPropName
	 *
	 * @return mixed
	 * @throws \InvalidParameterException
	 * @throws \Exception
	 */
	public function __get($sPropName)
	{
		if ($this->aUIExtensions === null)
		{
			$this->InitUIExtensions();
		}

		if (array_key_exists($sPropName, $this->aUIExtensions))
		{
			return $this->aUIExtensions[$sPropName];
		}

		throw new InvalidParameterException("Invalid property name $sPropName");
	}

	/**
	 * @param string $sPropName
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function __isset($sPropName)
	{
		if ($this->aUIExtensions === null)
		{
			$this->InitUIExtensions();
		}

		return array_key_exists($sPropName, $this->aUIExtensions);
	}

	/**
	 * @return array
	 */
	public function GetUIExtensions()
	{
		return $this->aUIExtensions;
	}

	/**
	 * Init the UI extensions implementing iPortalUIExtension
	 *
	 * @throws \Exception
	 */
	protected function InitUIExtensions()
	{
		$aUIExtensions = array(
			'css_files' => array(),
			'css_inline' => null,
			'js_files' => array(),
			'js_inline' => null,
			'html' => array(),
		);

		$aUIExtensionHooks = array(
			iPortalUIExtension::ENUM_PORTAL_EXT_UI_BODY,
			iPortalUIExtension::ENUM_PORTAL_EXT_UI_NAVIGATION_MENU,
			iPortalUIExtension::ENUM_PORTAL_EXT_UI_MAIN_CONTENT,
		);

		/** @var iPortalUIExtension $oExtensionInstance */
		foreach (MetaModel::EnumPlugins('iPortalUIExtension') as $oExtensionInstance)
		{
			// Adding CSS files
			$aImportPaths = array($_ENV['COMBODO_PORTAL_BASE_ABSOLUTE_PATH'].'css/');
			foreach ($oExtensionInstance->GetCSSFiles($this->oContainer) as $sCSSFile)
			{
				// Removing app root url as we need to pass a path on the file system (relative to app root)
				$sCSSFilePath = str_replace(utils::GetAbsoluteUrlAppRoot(), '', $sCSSFile);
				// Compiling SCSS file
				$sCSSFileCompiled = utils::GetAbsoluteUrlAppRoot().utils::GetCSSFromSASS($sCSSFilePath,
						$aImportPaths);

				if (!in_array($sCSSFileCompiled, $aUIExtensions['css_files']))
				{
					$aUIExtensions['css_files'][] = $sCSSFileCompiled;
				}
			}

			// Adding CSS inline
			$sCSSInline = $oExtensionInstance->GetCSSInline($this->oContainer);
			if ($sCSSInline !== null)
			{
				$aUIExtensions['css_inline'] .= "\n\n".$sCSSInline;
			}

			// Adding JS files
			$aUIExtensions['js_files'] = array_merge($aUIExtensions['js_files'],
				$oExtensionInstance->GetJSFiles($this->oContainer));

			// Adding JS inline
			$sJSInline = $oExtensionInstance->GetJSInline($this->oContainer);
			if ($sJSInline !== null)
			{
				// Note: Semi-colon is to prevent previous script that would have omitted it.
				$aUIExtensions['js_inline'] .= "\n\n;\n".$sJSInline;
			}

			// Adding HTML for each hook
			foreach ($aUIExtensionHooks as $sUIExtensionHook)
			{
				$sFunctionName = 'Get'.$sUIExtensionHook.'HTML';
				$sHTML = $oExtensionInstance->$sFunctionName($this->oContainer);
				if ($sHTML !== null)
				{
					if (!array_key_exists($sUIExtensionHook, $aUIExtensions['html']))
					{
						$aUIExtensions['html'][$sUIExtensionHook] = '';
					}
					$aUIExtensions['html'][$sUIExtensionHook] .= "\n\n".$sHTML;
				}
			}
		}

		$this->aUIExtensions = $aUIExtensions;
	}
}