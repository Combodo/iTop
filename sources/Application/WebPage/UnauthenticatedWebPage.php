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

namespace Combodo\iTop\Application\WebPage;

use Combodo\iTop\Application\Branding;
use Combodo\iTop\Application\TwigBase\Twig\TwigHelper;
use Combodo\iTop\Renderer\BlockRenderer;
use Combodo\iTop\Renderer\Console\ConsoleBlockRenderer;
use Dict;
use Exception;
use ExecutionKPI;
use utils;

/**
 * Class UnauthenticatedWebPage
 *
 * @author Stephen Abello <stephen.abello@combodo.com>
 *
 * @since 3.0.0
 */
class UnauthenticatedWebPage extends NiceWebPage
{
	const DEFAULT_PAGE_TEMPLATE_REL_PATH = 'pages/backoffice/unauthenticatedwebpage/layout';
	private $sContent;
	private $sPanelTitle;
	private $sPanelIcon;

	// TODO 3.0 Find a clever way to allow theme customization for unauthenticated webpages
	private $sCustomThemeUrl;

	/** @since 3.2.0 */
	protected string $sPortalBaseFolderRelPath;
	/** @since 3.2.0 */
	protected string $sPortalSourcesFolderRelPath;
	/** @since 3.2.0 */
	protected string $sPortalPublicFolderRelPath;
	/** @since 3.2.0 */
	protected string $sPortalBaseFolderAbsPath;
	/** @since 3.2.0 */
	protected string $sPortalSourcesFolderAbsPath;
	/** @since 3.2.0 */
	protected string $sPortalPublicFolderAbsPath;
	/** @since 3.2.0 */
	protected string $sPortalPublicFolderAbsUrl;

	/**
	 * @inheritDoc
	 * @throws \Exception
	 */
	public function __construct($s_title, $bPrintable = false)
	{
		$this->Init();

		$oKpi = new ExecutionKPI();
		parent::__construct($s_title, $bPrintable);

		$this->sContent = '';
		$this->sPanelTitle = '';
		$this->sPanelIcon = Branding::GetLoginLogoAbsoluteUrl();
		$this->SetContentType('text/html');

		// - bootstrap
		$this->LinkScriptFromURI(UAWP_PORTAL_PUBLIC_FOLDER_ABSOLUTE_URL . 'lib/bootstrap/js/bootstrap.min.js');

		// Note: Since 2.6.0 moment was moved from portal to iTop core
		$this->LinkScriptFromAppRoot('node_modules/moment/min/moment-with-locales.min.js');

		$this->LinkScriptFromURI(UAWP_PORTAL_PUBLIC_FOLDER_ABSOLUTE_URL . 'lib/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js');

		// CSS files
		$this->LinkStylesheetFromURI(UAWP_PORTAL_PUBLIC_FOLDER_ABSOLUTE_URL . 'lib/bootstrap/css/bootstrap.min.css');
		$this->add_saas(UAWP_PORTAL_PUBLIC_FOLDER_RELATIVE_PATH . 'css/bootstrap-theme-combodo.scss');
		$this->LinkStylesheetFromURI(UAWP_PORTAL_PUBLIC_FOLDER_ABSOLUTE_URL . 'lib/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css');

		// Default theme
		$this->add_saas('css/unauthenticated.scss');
		$oKpi->ComputeStats(get_class($this).' creation', $s_title);
	}

	/**
	 * Init.
	 *
	 * @return void
	 * @throws \Exception
	 */
	private function Init(): void
	{
		$this->sPortalBaseFolderRelPath = 'env-' . utils::GetCurrentEnvironment() . '/itop-portal-base/portal/';
		$this->sPortalSourcesFolderRelPath = $this->sPortalBaseFolderRelPath . 'src/';
		$this->sPortalPublicFolderRelPath = $this->sPortalBaseFolderRelPath . 'public/';

		$this->sPortalBaseFolderAbsPath = APPROOT . $this->sPortalBaseFolderRelPath;
		$this->sPortalSourcesFolderAbsPath = APPROOT . $this->sPortalSourcesFolderRelPath;
		$this->sPortalPublicFolderAbsPath = APPROOT . $this->sPortalPublicFolderRelPath;

		/** @noinspection PhpUnhandledExceptionInspection */
		$this->sPortalPublicFolderAbsUrl = utils::GetAbsoluteUrlModulesRoot().'/itop-portal-base/portal/public/';

		// Constants to be used in the UnauthenticatedWebPage
		if(!defined('UAWP_PORTAL_PUBLIC_FOLDER_ABSOLUTE_URL'))
		{
			define('UAWP_PORTAL_PUBLIC_FOLDER_ABSOLUTE_URL', $this->sPortalPublicFolderAbsUrl);
		}
		if(!defined('UAWP_PORTAL_PUBLIC_FOLDER_RELATIVE_PATH'))
		{
			define('UAWP_PORTAL_PUBLIC_FOLDER_RELATIVE_PATH', $this->sPortalPublicFolderRelPath);
		}
	}

	/**
	 * @inheritdoc
	 */
	public function add($sHtml)
	{
		$this->sContent .= $sHtml;
	}
	
	/**
	 * @inheritdoc
	 */
	public function output()
	{
		$oKpi = new ExecutionKPI();
		// Send headers
		foreach ($this->a_headers as $sHeader) {
			header($sHeader);
		}

		$s_captured_output = $this->ob_get_clean_safe();

		$aData = [];

		// Prepare internal parts (js files, css files, js snippets, css snippets, ...)
		// - Generate necessary dict. files
		if ($this->bAddJSDict) {
			$this->output_dict_entries();
		}

		$aData['oLayout'] = $this->oContentLayout;
		$aData['aDeferredBlocks'] = $this->GetDeferredBlocks($this->oContentLayout);

		ConsoleBlockRenderer::AddCssJsToPage($this, $this->oContentLayout);

		// Base structure of data to pass to the TWIG template
		$aData['aPage'] = [
			'sAbsoluteUrlAppRoot' => addslashes(utils::GetAbsoluteUrlAppRoot()),
			'sTitle'              => $this->s_title,
			'aMetadata'           => [
				'sCharset' => static::PAGES_CHARSET,
				'sLang'    => $this->GetLanguageForMetadata(),
			],
			'aCssFiles'           => $this->a_linked_stylesheets,
			'aCssInline'          => $this->a_styles,
			'aJsInlineEarly'      => $this->a_early_scripts,
			'aJsFiles'            => $this->a_linked_scripts,
			'aJsInlineLive'       => $this->a_scripts,
			'aJsInlineOnDomReady' => $this->GetReadyScripts(),
			'aJsInlineOnInit'     => $this->a_init_scripts,

			// TODO 3.0.0: TEMP, used while developing, remove it.
			'sCapturedOutput'     => utils::FilterXSS($s_captured_output),
			'sDeferredContent'    => utils::FilterXSS($this->s_deferred_content),
			'sContent' => $this->sContent,
			'sPanelIcon' => $this->sPanelIcon,
			'sPanelTitle' => $this->sPanelTitle
		];

		$aData['aBlockParams'] = $this->GetBlockParams();

		if ($this->a_base['href'] != '') {
			$aData['aPage']['aMetadata']['sBaseUrl'] = $this->a_base['href'];
		}

		if ($this->a_base['target'] != '') {
			$aData['aPage']['aMetadata']['sBaseTarget'] = $this->a_base['target'];
		}

		// Favicon
		$aData['aPage']['sFaviconUrl'] = $this->GetFaviconAbsoluteUrl();

		$oTwigEnv = TwigHelper::GetTwigEnvironment(BlockRenderer::TWIG_BASE_PATH, BlockRenderer::TWIG_ADDITIONAL_PATHS);
		
		// Render final TWIG into global HTML
		$sHtml = TwigHelper::RenderTemplate($oTwigEnv, $aData, $this->GetTemplateRelPath());
		$oKpi->ComputeAndReport(get_class($this).' output');
		echo $sHtml;
		$oKpi->ComputeAndReport('Echoing ('.round(strlen($sHtml) / 1024).' Kb)');
		ExecutionKPI::ReportStats();
	}
	
	/**
	 * Displays a success message.
	 *
	 * @param string $sMessage
	 *
	 * @throws Exception
	 */
	public function DisplaySuccessMessage($sMessage)
	{
		$this->Display('<div class="alert alert-success">'.$sMessage.'</div>');
	}

	/**
	 * Displays an error message.
	 *
	 * @param string $sMessage
	 *
	 * @throws Exception
	 */
	public function DisplayErrorMessage($sMessage)
	{
		$sFormTitle = Dict::S('UnauthenticatedForms:Form:DefaultLabel:Form:Title');
		$sHtml = '<div class="alert alert-danger">'.$sMessage.'</div>';

		$this->set_title($sMessage);
		$this->Display($sHtml, $sFormTitle);
	}

	/**
	 * @return string
	 */
	public function GetPanelTitle(): string
	{
		return $this->sPanelTitle;
	}

	/**
	 * @param string $sPanelTitle
	 *
	 * @return UnauthenticatedWebPage
	 */
	public function SetPanelTitle(string $sPanelTitle): UnauthenticatedWebPage
	{
		$this->sPanelTitle = $sPanelTitle;
		return $this;
	}

	/**
	 * @return string
	 */
	public function GetPanelIcon(): string
	{
		return $this->sPanelIcon;
	}

	/**
	 * @param string $sPanelIcon
	 *
	 * @return UnauthenticatedWebPage
	 */
	public function SetPanelIcon(string $sPanelIcon): UnauthenticatedWebPage
	{
		$this->sPanelIcon = $sPanelIcon;
		return $this;
	}


	/**
	 * @inheritDoc
	 * @throws \Exception
	 */
	protected function LoadTheme()
	{
		$this->LinkStylesheetFromAppRoot('css/font-awesome/css/all.min.css');
		// Default theme
		$this->add_saas('css/unauthenticated.scss');
		// Custom theme to allow admin to override the default one.
		if(!empty($this->sCustomThemeUrl))
		{
			$this->LinkStylesheetFromURI($this->sCustomThemeUrl);
		}
	}

	/**
	 * @inheritDoc
	 * @since 3.2.0
	 */
	protected function GetFaviconAbsoluteUrl()
	{
		return Branding::GetLoginFavIconAbsoluteUrl();
	}
}