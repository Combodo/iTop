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

namespace Combodo\iTop\Application\TwigBase\Controller;

use ApplicationMenu;
use Combodo\iTop\Application\TwigBase\Twig\TwigHelper;
use Combodo\iTop\Application\WebPage\AjaxPage;
use Combodo\iTop\Application\WebPage\ErrorPage;
use Combodo\iTop\Application\WebPage\iTopWebPage;
use Combodo\iTop\Application\WebPage\WebPage;
use Combodo\iTop\Controller\AbstractController;
use Combodo\iTop\Forms\Block\AbstractFormBlock;
use Combodo\iTop\Forms\Block\Base\FormBlock;
use Combodo\iTop\Forms\Forms;
use Combodo\iTop\Forms\FormType\FormTypeHelper;
use Combodo\iTop\Service\InterfaceDiscovery\InterfaceDiscovery;
use Dict;
use Exception;
use ExecutionKPI;
use IssueLog;
use LoginWebPage;
use MetaModel;
use ReflectionClass;
use SetupPage;
use SetupUtils;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Component\Form\Extension\Csrf\CsrfExtension;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormRenderer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Twig\Error\SyntaxError;
use Twig\RuntimeLoader\FactoryRuntimeLoader;
use utils;
use ZipArchive;

abstract class Controller extends AbstractController
{
	public const ENUM_PAGE_TYPE_HTML            = 'html';
	public const ENUM_PAGE_TYPE_BASIC_HTML      = 'basic_html';
	public const ENUM_PAGE_TYPE_AJAX            = 'ajax';
	public const ENUM_PAGE_TYPE_TURBO_FORM_AJAX = 'turbo_ajax';
	public const ENUM_PAGE_TYPE_SETUP           = 'setup';

	public const TWIG_ERROR   = 'error';
	public const TWIG_WARNING = 'warning';

	/** @var \Twig\Environment */
	private $oTwig;
	/** @var string */
	protected $m_sOperation;
	/** @var string */
	private $m_sModule;
	/** @var iTopWebPage|AjaxPage */
	private $oPage;
	/** @var bool */
	private $bCheckDemoMode = false;
	/** @var bool */
	private $bMustBeAdmin = false;
	/** @var string */
	private $sMenuId = null;
	/** @var string */
	private $sDefaultOperation = 'Default';
	private $aDefaultParams;
	private $aLinkedScripts;
	private $aLinkedStylesheets;
	private $aSaas;
	private $aAjaxTabs;
	/** parameters for page's blocks
	 *
	 * @var array
	 * @since 3.0.0
	 */
	private $aBlockParams;
	/** @var string */
	private $sAccessTokenConfigParamId = null;
	/** @var boolean false to disable breadcrumb */
	private $bIsBreadCrumbEnabled = true;
	/** @var array contains same parameters as {@see iTopWebPage::SetBreadCrumbEntry()} */
	private $aBreadCrumbEntry = [];

	/** @var Request Request (from Symfony http_foundation component @link https://symfony.com/doc/current/components/http_foundation.html) */
	private Request $oRequest;

	/** @var FormFactoryBuilderInterface Factory form builder (from Symfony form component @link https://symfony.com/doc/current/components/form.html) */
	private FormFactoryBuilderInterface $oFormFactoryBuilder;

	/** @var CsrfTokenManager Csrf manager (from Symfony form component @link https://symfony.com/doc/current/security/csrf.html) */
	private CsrfTokenManager $oCsrfTokenManager;
	private ?string $sContentType = null;
	private ?string $sPageType = null;
	private bool $bDebugAllowed = true;
	protected bool $bDebugForced;

	/**
	 * Controller constructor.
	 *
	 * @param string $sViewPath Path of the twig files
	 * @param string $sModuleName name of the module (or 'core' if not a module)
	 * @param array $aAdditionalPaths for twig templates
	 * @param array $aThemes for default form templates
	 *
	 * @throws \ReflectionException
	 * @throws \Twig\Error\LoaderError
	 */
	public function __construct($sViewPath = '', $sModuleName = 'core', $aAdditionalPaths = [], array $aThemes = ['application/forms/itop_console_layout.html.twig', 'application/forms/wip_form_demonstrator.html.twig'])
	{
		$this->aLinkedScripts = [];
		$this->aLinkedStylesheets = [];
		$this->aSaas = [];
		$this->aAjaxTabs = [];
		$this->aDefaultParams = [];
		$this->aBlockParams = [];
		$this->SetModuleName($sModuleName);

		// Initialize Symfony components
		$this->InitSymfonyComponents($sViewPath, $sModuleName, $aAdditionalPaths, $aThemes);
		$this->InitDebugExtensions();
	}

	/**
	 * Init Symfony components.
	 *
	 * @param string $sViewPath
	 * @param string $sModuleName
	 * @param array $aAdditionalPaths
	 * @param array $aThemes
	 *
	 * @return void
	 * @throws \ReflectionException
	 * @throws \Twig\Error\LoaderError
	 */
	private function InitSymfonyComponents(string $sViewPath, string $sModuleName, array $aAdditionalPaths, array $aThemes): void
	{
		// Twig environment
		$aAdditionalPaths[] = APPROOT.'lib/symfony/twig-bridge/Resources/views/Form';
		$aAdditionalPaths[] = APPROOT.'templates';
		foreach (InterfaceDiscovery::GetInstance()->FindItopClasses(iProfilerExtension::class) as $sExtension) {
			/** @var \Combodo\iTop\Application\TwigBase\Controller\iProfilerExtension $oExtensionInstance */
			$oExtensionInstance = $sExtension::GetInstance();
			$path = $oExtensionInstance->GetTemplatesPath();
			if (is_string($path)) {
				if (!in_array($path, $aAdditionalPaths)) {
					$aAdditionalPaths[] = $path;
				}
			} elseif (is_array($path)) {
				foreach ($path as $sPath) {
					if (!in_array($sPath, $aAdditionalPaths)) {
						$aAdditionalPaths[] = $sPath;
					}
				}
			}
		}
		if (strlen($sViewPath) > 0) {
			$this->SetViewPath($sViewPath, $aAdditionalPaths, $aThemes);
			if ($sModuleName != 'core') {
				try {
					$this->aDefaultParams = ['sIndexURL'   => utils::GetAbsoluteUrlModulePage($this->m_sModule, 'index.php')];
				} catch (Exception $e) {
					IssueLog::Error($e->getMessage());
				}
			}
		}

		// PHP Request object representation from PHP request globals
		$this->oRequest = Request::createFromGlobals();
		$this->bDebugForced = $this->oRequest->query->has('debug');

		// Initialize the CSRF token manager
		$this->oCsrfTokenManager = new CsrfTokenManager();

		// Initialize the form factory builder to handle Request objects
		$this->oFormFactoryBuilder = Forms::createFormFactoryBuilder()
			->addExtension(new HttpFoundationExtension())
			->addExtension(new CsrfExtension($this->oCsrfTokenManager));
	}

	/**
	 * Initialize the Controller from a module
	 */
	public function InitFromModule()
	{
		$sModulePath = dirname(dirname($this->GetDir()));
		$this->SetModuleName(basename($sModulePath));
		$this->SetViewPath($sModulePath.'/view');
		try {
			$this->aDefaultParams = ['sIndexURL'   => utils::GetAbsoluteUrlModulePage($this->m_sModule, 'index.php')];
		} catch (Exception $e) {
			IssueLog::Error($e->getMessage());
		}
	}

	/**
	 * Indicates the path of the view directory (containing the twig templates)
	 *
	 * @param string $sViewPath
	 * @param array $aAdditionalPaths
	 *
	 * @throws \Twig\Error\LoaderError
	 */
	public function SetViewPath($sViewPath, $aAdditionalPaths = [], array $aThemes = ['application/forms/itop_console_layout.html.twig', 'application/forms/wip_form_demonstrator.html.twig']): void
	{
		$oTwig = TwigHelper::GetTwigEnvironment($sViewPath, $aAdditionalPaths);
		/** @link https://github.com/symfony/twig-bridge/blob/6.4/CHANGELOG.md#320 */
		$formEngine = new TwigRendererEngine($aThemes, $oTwig);
		$oTwig->addRuntimeLoader(new FactoryRuntimeLoader([
			FormRenderer::class => function () use ($formEngine): FormRenderer {
				return new FormRenderer($formEngine, $this->oCsrfTokenManager);
			},
		]));
		$oTwig->addExtension(new FormExtension());
		$this->oTwig = $oTwig;
	}

	/**
	 * Set the name of the current module
	 * Used to name operations see Controller::GetOperationTitle()
	 *
	 * @param string $sModule Name of the module
	 */
	public function SetModuleName($sModule)
	{
		$this->m_sModule = $sModule;
	}

	/**
	 * @return string
	 */
	private function GetDir(): string
	{
		return dirname((new ReflectionClass(static::class))->getFileName());
	}

	/**
	 * Entry point to handle requests
	 *
	 * @api
	 */
	public function HandleOperation(): void
	{
		try {
			$this->CheckAccess();
			$this->m_sOperation = utils::ReadParam('operation', $this->sDefaultOperation);

			if ($this->CallOperation(utils::ToCamelCase($this->m_sOperation))) {
				return;
			}

			// Fallback to unchanged names for compatibility
			if ($this->CallOperation($this->m_sOperation)) {
				return;
			}

			$this->DisplayBadRequest();
		} catch (Exception $e) {
			http_response_code(500);
			$oP = new ErrorPage(Dict::S('UI:PageTitle:FatalError'));
			$oP->add("<h1>".Dict::S('UI:FatalErrorMessage')."</h1>\n");
			$oP->add(get_class($e).' : '.utils::EscapeHtml($e->GetMessage()));
			$oP->output();

			IssueLog::Exception('HandleOperation failed for '.json_encode($this->m_sOperation), $e);
		}
	}

	/**
	 * Entry point to handle requests for ajax pages
	 *
	 * @api
	 */
	public function HandleAjaxOperation(): void
	{
		try {
			$this->CheckAccess();
			$this->m_sOperation = utils::ReadParam('operation', $this->sDefaultOperation);

			if ($this->CallOperation(utils::ToCamelCase($this->m_sOperation))) {
				return;
			}

			// Fallback to unchanged names for compatibility
			if ($this->CallOperation($this->m_sOperation)) {
				return;
			}

			$this->DisplayPageNotFound();
		} catch (Exception $e) {
			http_response_code(500);
			$aResponse = ['sError' => $e->getMessage()];
			echo json_encode($aResponse);
		}
	}

	private function CallOperation($sOperation): bool
	{
		$sMethodName = 'Operation'.$sOperation;
		if (!method_exists($this, $sMethodName)) {
			return false;
		}

		$this->$sMethodName();

		return true;
	}

	/**
	 * Overridable "page not found" which is more an "operation not found"
	 */
	public function DisplayBadRequest(): void
	{
		http_response_code(400);
		die('Operation not found');
	}

	/**
	 * Overridable "page not found" which is more an "operation not found"
	 */
	public function DisplayPageNotFound(): void
	{
		http_response_code(404);
		die("Page not found");
	}

	/**
	 * @throws \Exception
	 * @since 3.0.0 N°3606 - Adapt TwigBase Controller for combodo-monitoring extension
	 */
	protected function CheckAccess(): void
	{
		if ($this->bCheckDemoMode && MetaModel::GetConfig()->Get('demo_mode')) {
			throw new Exception("Sorry, iTop is in <b>demonstration mode</b>: this feature is disabled.");
		}

		$sExecModule = utils::ReadParam('exec_module', "");

		$sConfiguredAccessTokenValue = empty($this->sAccessTokenConfigParamId) ? "" : trim(MetaModel::GetConfig()->GetModuleSetting($sExecModule, $this->sAccessTokenConfigParamId));

		if (empty($sExecModule) || empty($sConfiguredAccessTokenValue)) {
			LoginWebPage::DoLogin($this->bMustBeAdmin);
		} else {
			//token mode without login required
			//N°7147 - Error HTTP 500 due to access_token not URL decoded
			$sPassedToken = utils::ReadPostedParam($this->sAccessTokenConfigParamId, null, false, 'raw_data');
			if (is_null($sPassedToken)) {
				$sPassedToken = utils::ReadParam($this->sAccessTokenConfigParamId, null, false, 'raw_data');
			}

			$sDecodedPassedToken = urldecode($sPassedToken);
			if ($sDecodedPassedToken !== $sConfiguredAccessTokenValue) {
				$sMsg = "Invalid token passed under '$this->sAccessTokenConfigParamId' http param to reach '$sExecModule' page.";
				IssueLog::Error(
					$sMsg,
					null,
					[
						'sHtmlDecodedToken' => $sDecodedPassedToken,
						'conf param ID'     => $this->sAccessTokenConfigParamId,
					]
				);
				throw new Exception("Invalid token");
			}
		}

		if (!empty($this->sMenuId)) {
			ApplicationMenu::CheckMenuIdEnabled($this->sMenuId);
		}
	}

	/**
	 * @return array
	 * @throws \Exception
	 */
	private function GetDefaultParameters(): array
	{
		return $this->aDefaultParams;
	}

	/**
	 * Disable this feature if in demo mode
	 *
	 * @api
	 */
	public function DisableInDemoMode(): void
	{
		$this->bCheckDemoMode = true;
	}

	/**
	 * Allow only admin users for this feature
	 *
	 * @api
	 */
	public function AllowOnlyAdmin(): void
	{
		$this->bMustBeAdmin = true;
	}

	/**
	 * Used to ensure iTop security without logging-in by passing a token.
	 * This security mechanism is applied to current extension main page when :
	 *  - '$m_sAccessTokenConfigParamId' is configured under $MyModuleSettings section.
	 *
	 * Main page will be allowed as long as
	 *  - there is an HTTP  parameter with the name '$m_sAccessTokenConfigParamId' parameter
	 *  - '$m_sAccessTokenConfigParamId' HTTP parameter value matches the value stored in iTop configuration.
	 *
	 * Example:
	 * Let's assume $m_sAccessTokenConfigParamId='access_token' with iTop $MyModuleSettings below configuration:
	 *      'combodo-shadok' => array ( 'access_token' => 'gabuzomeu')
	 * 'combodo-shadok' extension main page is rendered only with HTTP requests containing '&access_token=gabuzomeu'
	 * Otherwise an HTTP error code 500 will be returned.
	 *
	 * @param string $m_sAccessTokenConfigParamId
	 */
	public function SetAccessTokenConfigParamId(string $m_sAccessTokenConfigParamId): void
	{
		$this->sAccessTokenConfigParamId = trim($m_sAccessTokenConfigParamId) ?? "";
	}

	/**
	 * Set the Id of the menu to check for user access rights
	 *
	 * @api
	 *
	 * @param string $sMenuId
	 */
	public function SetMenuId($sMenuId): void
	{
		$this->sMenuId = $sMenuId;
	}

	/**
	 * Set the default operation when no 'operation' parameter is given on URL
	 *
	 * @api
	 *
	 * @param string $sDefaultOperation
	 */
	public function SetDefaultOperation($sDefaultOperation): void
	{
		$this->sDefaultOperation = $sDefaultOperation;
	}

	/**
	 * Display an AJAX (html) page (AjaxPage)
	 *
	 * @api
	 *
	 * @param array $aParams Params used by the twig template
	 * @param null $sTemplateName Name of the twig template, ie MyTemplate for MyTemplate.html.twig
	 *
	 * @throws \Exception
	 */
	public function DisplayAjaxPage($aParams = [], $sTemplateName = null): void
	{
		$this->DisplayPage($aParams, $sTemplateName, 'ajax');
	}

	/**
	 * Display an Setup page (SetupPage)
	 *
	 * @api
	 *
	 * @param array $aParams Params used by the twig template
	 * @param string|null $sTemplateName Name of the twig template, ie MyTemplate for MyTemplate.html.twig
	 *
	 * @throws \Exception
	 */
	public function DisplaySetupPage(array $aParams = [], ?string $sTemplateName = null): void
	{
		$this->DisplayPage($aParams, $sTemplateName, 'setup');
	}

	/**
	 * Generate a page to update only the impacted fields of a form
	 *
	 * @param array $aParams
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function DisplayTurboAjaxPage(array $aParams = []): void
	{
		$this->DisplayPage($aParams, 'application/forms/turbo-ajax-update', self::ENUM_PAGE_TYPE_TURBO_FORM_AJAX);
	}

	/**
	 * Display the twig page based on the name or the operation
	 *
	 * @api
	 *
	 * @param array $aParams Params used by the twig template
	 * @param string|null $sTemplateName Name of the twig template, ie MyTemplate for MyTemplate.html.twig
	 * @param string $sPageType ('html', 'basic_html', 'ajax', 'turbo_ajax', 'setup')
	 *
	 * @throws \Exception
	 */
	public function DisplayPage(array $aParams = [], ?string $sTemplateName = null, string $sPageType = self::ENUM_PAGE_TYPE_HTML): void
	{
		if (empty($sTemplateName)) {
			$sTemplateName = $this->m_sOperation;
		}

		$this->sPageType = $sPageType;

		$aParams = array_merge($this->GetDefaultParameters(), $aParams);
		$this->CreatePage($sPageType);
		$sHTMLContent = $this->RenderTemplate($aParams, $sTemplateName, 'html', $aErrors);
		if ($sHTMLContent !== false) {
			$this->AddToPage($sHTMLContent);
		}
		$sJSScript = $this->RenderTemplate($aParams, $sTemplateName, 'js', $aErrors);
		if ($sJSScript !== false) {
			$this->AddScriptToPage($sJSScript);
		}
		$sReadyScript = $this->RenderTemplate($aParams, $sTemplateName, 'ready.js', $aErrors);
		if ($sReadyScript !== false) {
			$this->AddReadyScriptToPage($sReadyScript);
		}
		$sStyle = $this->RenderTemplate($aParams, $sTemplateName, 'css', $aErrors);
		if ($sStyle !== false) {
			$this->AddStyleToPage($sStyle);
		}
		if ($sHTMLContent === false && $sJSScript === false && $sReadyScript === false && $sStyle === false) {
			if (is_null($aErrors) || count($aErrors) === 0) {
				$aErrors[self::TWIG_ERROR] = "Missing TWIG template for $sTemplateName";
			}
			IssueLog::Error(implode("\n", $aErrors[self::TWIG_ERROR] ?? [])."\n".implode("\n", $aErrors[self::TWIG_WARNING] ?? []));
		} else {
			// Ignore warnings
			$aErrors[self::TWIG_WARNING] = [];
		}
		$this->RenderErrors($aErrors);

		$this->ManageDebugExtensions($aParams, $sPageType);

		if (!empty($this->aAjaxTabs)) {
			$this->oPage->AddTabContainer('TwigBaseTabContainer');
			$this->oPage->SetCurrentTabContainer('TwigBaseTabContainer');
		}
		foreach ($this->aAjaxTabs as $sTabCode => $aTabData) {
			$this->AddAjaxTabToPage($sTabCode, $aTabData['label'], $aTabData['url'], $aTabData['cache']);
		}
		foreach ($this->aLinkedScripts as $sLinkedScript) {
			$this->AddLinkedScriptToPage($sLinkedScript);
		}
		foreach ($this->aLinkedStylesheets as $sLinkedStylesheet) {
			$this->AddLinkedStylesheetToPage($sLinkedStylesheet);
		}
		foreach ($this->aSaas as $sSaasRelPath) {
			$this->AddSaasToPage($sSaasRelPath);
		}
		foreach ($this->aBlockParams as $sKey => $value) {
			$this->SetBlockParamToPage($sKey, $value);
		}
		$this->SetContentTypeToPage();
		$this->OutputPage();
		$this->sPageType = null;
	}

	/**
	 * Return a JSON response
	 *
	 * @api
	 *
	 * @param array $aParams Content of the response, will be converted to JSON
	 * @param int $iResponseCode HTTP response code
	 * @param array $aHeaders additional HTTP headers
	 */
	public function DisplayJSONPage(array $aParams = [], int $iResponseCode = 200, array $aHeaders = []): void
	{
		$oKpi = new ExecutionKPI();
		http_response_code($iResponseCode);
		header('Content-Type: application/json');
		foreach ($aHeaders as $sHeader) {
			header($sHeader);
		}
		$sJSON = json_encode($aParams);
		echo $sJSON;
		$oKpi->ComputeAndReport('Echoing ('.round(strlen($sJSON) / 1024).' Kb)');

		ExecutionKPI::ReportStats();
	}

	/**
	 * Generate a page, zip it and propose the zipped file for download
	 *
	 * @api
	 *
	 * @param array $aParams Params used by the twig template
	 * @param string|null $sTemplateName Name of the twig template, ie MyTemplate for MyTemplate.html.twig
	 * @param string $sReportFileName Root name of the report file
	 *
	 * @throws \Exception
	 *
	 * @since 3.0.1 3.1.0 Add $sReportFileName parameter
	 */
	public function DownloadZippedPage(array $aParams = [], ?string $sTemplateName = null, string $sReportFileName = 'itop-system-information-report'): void
	{
		if (empty($sTemplateName)) {
			$sTemplateName = $this->m_sOperation;
		}
		$sReportFolder = str_replace("\\", '/', APPROOT.'log/');
		$sReportFile = $sReportFileName.'-'.date('Y-m-d-H-i-s');
		$sHTMLReport = $sReportFolder.$sReportFile.'.html';
		$sZIPReportFile = $sReportFile;

		ob_start();
		$this->DisplayPage($aParams, $sTemplateName, self::ENUM_PAGE_TYPE_BASIC_HTML);
		file_put_contents($sHTMLReport, ob_get_contents());
		ob_end_clean();

		$this->ZipDownloadRemoveFile([$sHTMLReport], $sZIPReportFile, true);
	}

	/**
	 * Create an archive and launch download, remove original file and archive when done
	 *
	 * @param string[] $aFiles
	 * @param string $sDownloadArchiveName file name to download, without the extension (.zip is automatically added)
	 * @param bool $bUnlinkFiles if true then will unlink each source file
	 */
	final protected function ZipDownloadRemoveFile(array $aFiles, string $sDownloadArchiveName, bool $bUnlinkFiles = false): void
	{
		$sArchiveFileFullPath = tempnam(SetupUtils::GetTmpDir(), 'itop_download-').'.zip';
		$oArchive = new ZipArchive();
		$oArchive->open($sArchiveFileFullPath, ZipArchive::CREATE);
		foreach ($aFiles as $sFile) {
			$oArchive->addFile($sFile, basename($sFile));
		}
		$oArchive->close();

		if ($bUnlinkFiles) {
			foreach ($aFiles as $sFile) {
				unlink($sFile);
			}
		}

		$this->SendFileContent($sArchiveFileFullPath, $sDownloadArchiveName.'.zip', true, true);
	}

	final protected function SendFileContent($sFilePath, $sDownloadArchiveName = null, $bFileTransfer = true, $bRemoveFile = false, $aHeaders = []): void
	{
		$sFileMimeType = utils::GetFileMimeType($sFilePath);
		header('Content-Type: '.$sFileMimeType);

		if ($bFileTransfer) {
			header('Content-Disposition: attachment; filename="'.$sDownloadArchiveName.'"');
		}

		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Expires: 0');

		foreach ($aHeaders as $sKey => $sValue) {
			header($sKey.': '.$sValue);
		}

		header('Content-Length: '.filesize($sFilePath));

		readfile($sFilePath);

		if ($bRemoveFile) {
			unlink($sFilePath);
		}
		exit(0);
	}

	/**
	 * Add a linked script to the current Page
	 *
	 * @api
	 *
	 * @param string $sScript Script path to link
	 *
	 * @since 3.2.0 $sScript must be absolute URI
	 */
	public function AddLinkedScript($sScript): void
	{
		$this->aLinkedScripts[] = $sScript;
	}

	/**
	 * Add an linked stylesheet to the current Page
	 *
	 * @api
	 *
	 * @param string $sStylesheet Stylesheet path to link
	 *
	 * @since 3.2.0 $sScript must be absolute URI
	 */
	public function AddLinkedStylesheet($sStylesheet): void
	{
		$this->aLinkedStylesheets[] = $sStylesheet;
	}

	/**
	 * Add a linked stylesheet to the current Page
	 *
	 * @api
	 *
	 * @param string $sSaasRelPath SCSS Stylesheet relative path to link
	 */
	public function AddSaas(string $sSaasRelPath): void
	{
		$this->aSaas[] = $sSaasRelPath;
	}

	/**
	 * Add an AJAX tab to the current page
	 *
	 * @api
	 *
	 * @param string $sURL URL to call when the tab is activated
	 * @param bool $bCache If true, cache the result for the current web page
	 * @param string|null $sLabel Label of the tab (if null the code is translated)
	 *
	 * @param string $sCode Code of the tab
	 */
	public function AddAjaxTab(string $sCode, string $sURL, bool $bCache = true, string $sLabel = null): void
	{
		if (is_null($sLabel)) {
			$sLabel = Dict::S($sCode);
		}
		$this->aAjaxTabs[$sCode] = ['label' => $sLabel, 'url' => $sURL, 'cache' => $bCache];
	}

	/**
	 * @param array $aBlockParams
	 *
	 * @since 3.0.0
	 */
	public function SetBlockParams(array $aBlockParams): void
	{
		$this->aBlockParams = $aBlockParams;
	}

	/**
	 * Allow to set manually the content type of the page
	 *
	 * @api
	 *
	 * @param string $sContentType
	 *
	 * @return void
	 * @since 3.3.0
	 */
	public function SetContentType(string $sContentType): void
	{
		$this->sContentType = $sContentType;
	}

	/**
	 * @see Controller::SetBreadCrumbEntry() to set breadcrumb content (by default will be title)
	 * @since 2.7.7 3.0.1 3.1.0 N°4760 method creation
	 */
	public function DisableBreadCrumb(): void
	{
		$this->bIsBreadCrumbEnabled = false;
	}

	/**
	 * @see iTopWebPage::SetBreadCrumbEntry()
	 *
	 * @param string $sId
	 * @param string $sLabel
	 * @param string $sDescription
	 * @param string $sUrl
	 * @param string $sIcon
	 *
	 * @since 2.7.7 3.0.1 3.1.0 N°4760 method creation
	 */
	public function SetBreadCrumbEntry(string $sId, string $sLabel, string $sDescription, string $sUrl = '', string $sIcon = ''): void
	{
		$this->aBreadCrumbEntry = [$sId, $sLabel, $sDescription, $sUrl, $sIcon];
	}

	/**
	 * Get the current incoming request
	 *
	 * @api
	 *
	 * @return \Symfony\Component\HttpFoundation\Request
	 * @since 3.3.0
	 */
	public function GetRequest(): Request
	{
		return $this->oRequest;
	}

	/**
	 * Get a form builder.
	 * This form builder can be used to create a form or to add fields to an existing form.
	 *
	 * @api
	 *
	 * @param \Combodo\iTop\Forms\Block\AbstractFormBlock $oFormBlock
	 * @param mixed|null $data
	 *
	 * @return \Symfony\Component\Form\FormBuilderInterface
	 * @since 3.3.0
	 */
	public function GetFormBuilder(AbstractFormBlock $oFormBlock, mixed $data = null): FormBuilderInterface
	{
		return $this->oFormFactoryBuilder->getFormFactory()->createNamedBuilder($oFormBlock->GetName(), $oFormBlock->GetFormType(), $data, $oFormBlock->GetOptions());
	}

	/**
	 * @param array $aParams
	 * @param string $sName
	 * @param string $sTemplateFileExtension
	 * @param array|null $aErrors
	 *
	 * @return string|false
	 * @throws \Exception
	 */
	private function RenderTemplate(array $aParams, string $sName, string $sTemplateFileExtension, ?array &$aErrors): string|false
	{
		if (is_null($aErrors)) {
			$aErrors = [];
		}
		$sTemplateFile = $sName.'.'.$sTemplateFileExtension.'.twig';
		if (empty($this->oTwig)) {
			throw new Exception('Not initialized. Call Controller::InitFromModule() or Controller::SetViewPath() before any display');
		}
		try {
			return $this->oTwig->render($sTemplateFile, $aParams);
		} catch (SyntaxError $e) {
			IssueLog::Error($e->getMessage().' - file: '.$e->getFile().'('.$e->getLine().')');
			$aErrors[self::TWIG_ERROR][] = $e->getMessage();

			return '';
		} catch (Exception $e) {
			$sExceptionMessage = $e->getMessage();
			if (str_contains($sExceptionMessage, 'at line')) {
				IssueLog::Error($sExceptionMessage);
				$aErrors[self::TWIG_ERROR][] = $sExceptionMessage;

				return '';
			}
			if (!str_contains($sExceptionMessage, 'Unable to find template')) {
				IssueLog::Error($sExceptionMessage);
			}
			$aErrors[self::TWIG_WARNING][] = $sExceptionMessage;
		}

		return false;
	}

	/**
	 * @param string $sPageType
	 *
	 * @throws \Exception
	 */
	private function CreatePage(string $sPageType): void
	{
		switch ($sPageType) {
			case self::ENUM_PAGE_TYPE_HTML:
				$this->oPage = new iTopWebPage($this->GetOperationTitle(), false);
				$this->oPage->add_http_headers();

				if ($this->bIsBreadCrumbEnabled) {
					if (count($this->aBreadCrumbEntry) > 0) {
						list($sId, $sTitle, $sDescription, $sUrl, $sIcon) = $this->aBreadCrumbEntry;
						$this->oPage->SetBreadCrumbEntry($sId, $sTitle, $sDescription, $sUrl, $sIcon);
					}
				} else {
					$this->oPage->DisableBreadCrumb();
				}

				break;

			case self::ENUM_PAGE_TYPE_BASIC_HTML:
				$this->oPage = new WebPage($this->GetOperationTitle());
				break;

			case self::ENUM_PAGE_TYPE_AJAX:
				$this->oPage = new AjaxPage($this->GetOperationTitle());
				break;

			case self::ENUM_PAGE_TYPE_TURBO_FORM_AJAX:
				$this->oPage = new AjaxPage($this->GetOperationTitle());
				$this->SetContentType('text/vnd.turbo-stream.html');
				break;

			case self::ENUM_PAGE_TYPE_SETUP:
				$this->oPage = new SetupPage($this->GetOperationTitle());
				break;
		}
		$this->oTwig->addGlobal('UIBlockParent', [$this->oPage]);
		$this->oTwig->addGlobal('oPage', $this->oPage);
	}

	/**
	 * Get the title of the operation
	 *
	 * @return string
	 */
	public function GetOperationTitle(): string
	{
		return Dict::S($this->m_sModule.'/Operation:'.$this->m_sOperation.'/Title');
	}

	/**
	 * @return string
	 * @since 3.0.0
	 */
	public function GetOperation(): string
	{
		return $this->m_sOperation;
	}

	/**
	 * @param $sContent
	 *
	 * @throws \Exception
	 */
	private function AddToPage($sContent): void
	{
		$this->oPage->add($sContent);
	}

	private function AddReadyScriptToPage($sScript): void
	{
		$this->oPage->add_ready_script($sScript);
	}

	private function AddScriptToPage($sScript): void
	{
		$this->oPage->add_script($sScript);
	}

	private function AddLinkedScriptToPage($sLinkedScript): void
	{
		$this->oPage->LinkScriptFromURI($sLinkedScript);
	}

	private function AddLinkedStylesheetToPage($sLinkedStylesheet): void
	{
		$this->oPage->LinkStylesheetFromURI($sLinkedStylesheet);
	}

	private function AddStyleToPage($sStyle): void
	{
		$this->oPage->add_style($sStyle);
	}

	private function AddSaasToPage($sSaasRelPath): void
	{
		$this->oPage->add_saas($sSaasRelPath);
	}

	private function AddAjaxTabToPage($sCode, $sTitle, $sURL, $bCache): void
	{
		$this->oPage->AddAjaxTab($sCode, $sURL, $bCache, $sTitle);
	}

	private function SetContentTypeToPage(): void
	{
		if (!is_null($this->sContentType)) {
			$this->oPage->SetContentType($this->sContentType);
		}
	}

	/**
	 * @param string $sKey
	 * @param $value
	 *
	 * @since 3.0.0
	 */
	private function SetBlockParamToPage(string $sKey, $value): void
	{
		$this->oPage->SetBlockParam($sKey, $value);
	}

	/**
	 * @throws \Exception
	 */
	private function OutputPage(): void
	{
		$this->oPage->output();
	}

	private function InitDebugExtensions(): void
	{
		foreach (InterfaceDiscovery::GetInstance()->FindItopClasses(iProfilerExtension::class) as $sExtension) {
			/** @var \Combodo\iTop\Application\TwigBase\Controller\iProfilerExtension $oExtensionInstance */
			$oExtensionInstance = $sExtension::GetInstance();
			$oExtensionInstance->Init();
		}
	}

	/**
	 * @param array $aParams
	 * @param string $sPageType
	 *
	 * @return void
	 * @throws \ReflectionException
	 * @throws \Twig\Error\LoaderError
	 * @throws \Twig\Error\RuntimeError
	 * @throws \Twig\Error\SyntaxError
	 * @throws \Exception
	 */
	private function ManageDebugExtensions(array $aParams, string $sPageType): void
	{
		if (!in_array($sPageType, [self::ENUM_PAGE_TYPE_HTML, self::ENUM_PAGE_TYPE_AJAX, self::ENUM_PAGE_TYPE_TURBO_FORM_AJAX])) {
			return;
		}
		if (!$this->bDebugAllowed && !$this->bDebugForced) {
			return;
		}
		$aProfilesInfo = [];
		foreach (InterfaceDiscovery::GetInstance()->FindItopClasses(iProfilerExtension::class) as $sExtension) {
			/** @var \Combodo\iTop\Application\TwigBase\Controller\iProfilerExtension $oExtensionInstance */
			$oExtensionInstance = $sExtension::GetInstance();
			$oExtensionInstance->SetDebugForced($this->bDebugForced);
			if ($oExtensionInstance->IsEnabled()) {
				$sDebugTemplate = $oExtensionInstance->GetDebugTemplate();
				$aDebugParams = $oExtensionInstance->GetDebugParams($aParams);
				$aLinkedScripts = $oExtensionInstance->GetLinkedScripts();
				if (is_array($aLinkedScripts)) {
					$this->aLinkedScripts = array_merge($this->aLinkedScripts, $aLinkedScripts);
				}
				$aLinkedStylesheets = $oExtensionInstance->GetLinkedStylesheets();
				if (is_array($aLinkedStylesheets)) {
					$this->aLinkedStylesheets = array_merge($this->aLinkedStylesheets, $aLinkedStylesheets);
				}
				$aSaas = $oExtensionInstance->GetSaas();
				if (is_array($aSaas)) {
					$this->aSaas = array_merge($this->aSaas, $aSaas);
				}
				$aProfilesInfo[] = ['sTemplate' => $sDebugTemplate, 'aProfileData' => $aDebugParams];
			}
		}
		if (count($aProfilesInfo) === 0) {
			return;
		}

		if ($sPageType === self::ENUM_PAGE_TYPE_HTML || $sPageType === self::ENUM_PAGE_TYPE_AJAX) {
			$this->AddToPage($this->oTwig->render('application/forms/itop_debug.html.twig', ['aProfilesInfo' => $aProfilesInfo]));
		} elseif ($sPageType === self::ENUM_PAGE_TYPE_TURBO_FORM_AJAX) {
			$this->AddToPage($this->oTwig->render('application/forms/itop_debug_update.html.twig', ['aProfilesInfo' => $aProfilesInfo]));
		}
	}

	/**
	 * render error message
	 *
	 * @param array $aErrors
	 *
	 * @return void
	 * @throws \Twig\Error\LoaderError
	 * @throws \Twig\Error\RuntimeError
	 * @throws \Twig\Error\SyntaxError
	 * @throws \Exception
	 * @since 3.3.0
	 */
	public function RenderErrors(array $aErrors): void
	{
		if (is_null($this->sPageType)) {
			return;
		}
		$sErrorMsg = '';
		if (count($aErrors[self::TWIG_ERROR] ?? []) > 0) {
			$sErrorMsg .= implode("\n", $aErrors[self::TWIG_ERROR]);
			$sErrorMsg .= "\n";
		}
		if (count($aErrors[self::TWIG_WARNING] ?? []) > 0) {
			$sErrorMsg .= implode("\n", $aErrors[self::TWIG_WARNING]);
		}

		if ($this->sPageType === self::ENUM_PAGE_TYPE_TURBO_FORM_AJAX) {
			if (utils::IsNotNullOrEmptyString($sErrorMsg)) {
				$this->AddToPage($this->oTwig->render('application/forms/itop_error_update.html.twig', ['sControllerError' => $sErrorMsg]));
			}

			return;
		}

		$this->AddToPage($this->oTwig->render('application/forms/itop_error.html.twig', ['sControllerError' => $sErrorMsg]));
	}

	public function SetDebugAllowed(bool $bDebugAllowed): void
	{
		$this->bDebugAllowed = $bDebugAllowed;
	}

	protected function HandleFormSubmitted(FormBlock $oFormBlock, FormInterface $oForm): bool
	{
		$sTrigger = $this->GetRequest()->get($oFormBlock->GetName())['_turbo_trigger'];

		if (!empty($sTrigger)) {

			// Compute blocks to redraw
			$aBlocksToRedraw = FormTypeHelper::ComputeBlocksToRedraw($oFormBlock, $oForm, $sTrigger);

			// Display turbo response
			$this->DisplayTurboAjaxPage($aBlocksToRedraw);

		} else {

			// Display turbo response
			$this->DisplayTurboAjaxPage(['current_form' => $oForm->createView()]);

		}

		return true;
	}
}
