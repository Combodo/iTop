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
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Csrf\CsrfExtension;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormRenderer;
use Symfony\Component\Form\Forms;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Twig\Error\SyntaxError;
use Twig\RuntimeLoader\FactoryRuntimeLoader;
use utils;
use ZipArchive;

abstract class Controller extends AbstractController
{
	public const ENUM_PAGE_TYPE_HTML = 'html';
	public const ENUM_PAGE_TYPE_BASIC_HTML = 'basic_html';
	public const ENUM_PAGE_TYPE_AJAX = 'ajax';
	public const ENUM_PAGE_TYPE_SETUP = 'setup';

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

	/**
	 * Controller constructor.
	 *
	 * @param string $sViewPath Path of the twig files
	 * @param string $sModuleName name of the module (or 'core' if not a module)
	 */
	public function __construct($sViewPath = '', $sModuleName = 'core', $aAdditionalPaths = [])
	{
		$this->aLinkedScripts = [];
		$this->aLinkedStylesheets = [];
		$this->aSaas = [];
		$this->aAjaxTabs = [];
		$this->aDefaultParams = [];
		$this->aBlockParams = [];
		$this->SetModuleName($sModuleName);

		// Initialize Symfony components
		$this->InitSymfonyComponents($sViewPath, $sModuleName, $aAdditionalPaths);
		$this->InitDebugExtensions();
	}

	/**
	 * Init Symfony components.
	 *
	 * @param string $sViewPath
	 * @param string $sModuleName
	 * @param array $aAdditionalPaths
	 *
	 * @return void
	 * @throws \ReflectionException
	 */
	private function InitSymfonyComponents(string $sViewPath, string $sModuleName, array $aAdditionalPaths): void
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
			$this->SetViewPath($sViewPath, $aAdditionalPaths);
			if ($sModuleName != 'core') {
				try {
					$this->aDefaultParams = ['sIndexURL' => utils::GetAbsoluteUrlModulePage($this->m_sModule, 'index.php')];
				} catch (Exception $e) {
					IssueLog::Error($e->getMessage());
				}
			}
		}

		// PHP Request object representation from PHP request globals
		$this->oRequest = Request::createFromGlobals();

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
			$this->aDefaultParams = ['sIndexURL' => utils::GetAbsoluteUrlModulePage($this->m_sModule, 'index.php')];
		} catch (Exception $e) {
			IssueLog::Error($e->getMessage());
		}
	}

	/**
	 * Indicates the path of the view directory (containing the twig templates)
	 *
	 * @param string $sViewPath
	 */
	public function SetViewPath($sViewPath, $aAdditionalPaths = [])
	{
		$oTwig = TwigHelper::GetTwigEnvironment($sViewPath, $aAdditionalPaths);
		/** @link https://github.com/symfony/twig-bridge/blob/6.4/CHANGELOG.md#320 */
		$formEngine = new TwigRendererEngine(['application/forms/itop_console_layout.html.twig'], $oTwig);
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
	public function HandleOperation()
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

			IssueLog::Error($e->getMessage());
		}
	}

	/**
	 * Entry point to handle requests
	 *
	 * @api
	 */
	public function HandleAjaxOperation()
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
	public function DisplayBadRequest()
	{
		http_response_code(400);
		die('Operation not found');
	}

	/**
	 * Overridable "page not found" which is more an "operation not found"
	 */
	public function DisplayPageNotFound()
	{
		http_response_code(404);
		die("Page not found");
	}

	/**
	 * @since 3.0.0 N°3606 - Adapt TwigBase Controller for combodo-monitoring extension
	 * @throws \Exception
	 */
	protected function CheckAccess()
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
						'conf param ID' => $this->sAccessTokenConfigParamId,
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
	private function GetDefaultParameters()
	{
		return $this->aDefaultParams;
	}

	/**
	 * Disable this feature if in demo mode
	 *
	 * @api
	 */
	public function DisableInDemoMode()
	{
		$this->bCheckDemoMode = true;
	}

	/**
	 * Allow only admin users for this feature
	 *
	 * @api
	 */
	public function AllowOnlyAdmin()
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
	public function SetMenuId($sMenuId)
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
	public function SetDefaultOperation($sDefaultOperation)
	{
		$this->sDefaultOperation = $sDefaultOperation;
	}

	/**
	 * Display an AJAX page (AjaxPage)
	 *
	 * @api
	 *
	 * @param array $aParams Params used by the twig template
	 * @param null $sTemplateName Name of the twig template, ie MyTemplate for MyTemplate.html.twig
	 *
	 * @throws \Exception
	 */
	public function DisplayAjaxPage($aParams = [], $sTemplateName = null)
	{
		$this->DisplayPage($aParams, $sTemplateName, 'ajax');
	}

	/**
	 * Display an Setup page (SetupPage)
	 *
	 * @api
	 *
	 * @param array $aParams Params used by the twig template
	 * @param null $sTemplateName Name of the twig template, ie MyTemplate for MyTemplate.html.twig
	 *
	 * @throws \Exception
	 */
	public function DisplaySetupPage($aParams = [], $sTemplateName = null)
	{
		$this->DisplayPage($aParams, $sTemplateName, 'setup');
	}

	/**
	 * Display the twig page based on the name or the operation
	 *
	 * @api
	 *
	 * @param array $aParams Params used by the twig template
	 * @param string $sTemplateName Name of the twig template, ie MyTemplate for MyTemplate.html.twig
	 * @param string $sPageType ('html' or 'ajax')
	 *
	 * @throws \Exception
	 */
	public function DisplayPage($aParams = [], $sTemplateName = null, $sPageType = 'html')
	{
		if (empty($sTemplateName)) {
			$sTemplateName = $this->m_sOperation;
		}
		$aParams = array_merge($this->GetDefaultParameters(), $aParams);
		$this->CreatePage($sPageType);
		$sHTMLContent = $this->RenderTemplate($aParams, $sTemplateName, 'html', $sErrorMsg);
		if ($sHTMLContent !== false) {
			$this->AddToPage($sHTMLContent);
		}
		$sJSScript = $this->RenderTemplate($aParams, $sTemplateName, 'js', $sErrorMsg);
		if ($sJSScript !== false) {
			$this->AddScriptToPage($sJSScript);
		}
		$sReadyScript = $this->RenderTemplate($aParams, $sTemplateName, 'ready.js', $sErrorMsg);
		if ($sReadyScript !== false) {
			$this->AddReadyScriptToPage($sReadyScript);
		}
		$sStyle = $this->RenderTemplate($aParams, $sTemplateName, 'css', $sErrorMsg);
		if ($sStyle !== false) {
			$this->AddStyleToPage($sStyle);
		}
		if ($sHTMLContent === false && $sJSScript === false && $sReadyScript === false && $sStyle === false) {
			if (utils::IsNullOrEmptyString($sErrorMsg)) {
				$sErrorMsg = "Missing TWIG template for $sTemplateName";
			}
			IssueLog::Error($sErrorMsg);
			$this->AddToPage($this->oTwig->render('application/forms/itop_error.html.twig', ['sControllerError' => $sErrorMsg]));
		}

		$this->ManageDebugExtensions($aParams);

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
		$this->OutputPage();
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
	public function DisplayJSONPage($aParams = [], $iResponseCode = 200, $aHeaders = [])
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
	public function DownloadZippedPage($aParams = [], $sTemplateName = null, $sReportFileName = 'itop-system-information-report')
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
	final protected function ZipDownloadRemoveFile($aFiles, $sDownloadArchiveName, $bUnlinkFiles = false)
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

	final protected function SendFileContent($sFilePath, $sDownloadArchiveName = null, $bFileTransfer = true, $bRemoveFile = false, $aHeaders = [])
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
	 * @since 3.2.0 $sScript must be absolute URI
	 */
	public function AddLinkedScript($sScript)
	{
		$this->aLinkedScripts[] = $sScript;
	}

	/**
	 * Add an linked stylesheet to the current Page
	 *
	 * @api
	 *
	 * @param string $sStylesheet Stylesheet path to link
	 * @since 3.2.0 $sScript must be absolute URI
	 */
	public function AddLinkedStylesheet($sStylesheet)
	{
		$this->aLinkedStylesheets[] = $sStylesheet;
	}

	/**
	 * Add an linked stylesheet to the current Page
	 *
	 * @api
	 *
	 * @param string $sSaasRelPath SCSS Stylesheet relative path to link
	 */
	public function AddSaas($sSaasRelPath)
	{
		$this->aSaas[] = $sSaasRelPath;
	}

	/**
	 * Add an AJAX tab to the current page
	 *
	 * @param string $sCode Code of the tab
	 * @param string $sURL URL to call when the tab is activated
	 * @param bool $bCache If true, cache the result for the current web page
	 * @param string $sLabel Label of the tab (if null the code is translated)
	 *
	 * @api
	 *
	 */
	public function AddAjaxTab($sCode, $sURL, $bCache = true, $sLabel = null)
	{
		if (is_null($sLabel)) {
			$sLabel = Dict::S($sCode);
		}
		$this->aAjaxTabs[$sCode] = ['label' => $sLabel, 'url' => $sURL, 'cache' => $bCache];
	}

	/**
	 * @param array $aBlockParams
	 * @since 3.0.0
	 */
	public function SetBlockParams(array $aBlockParams)
	{
		$this->aBlockParams = $aBlockParams;
	}

	/**
	 * @since 2.7.7 3.0.1 3.1.0 N°4760 method creation
	 * @see Controller::SetBreadCrumbEntry() to set breadcrumb content (by default will be title)
	 */
	public function DisableBreadCrumb()
	{
		$this->bIsBreadCrumbEnabled = false;
	}

	/**
	 * @since 2.7.7 3.0.1 3.1.0 N°4760 method creation
	 * @see iTopWebPage::SetBreadCrumbEntry()
	 */
	public function SetBreadCrumbEntry($sId, $sLabel, $sDescription, $sUrl = '', $sIcon = '')
	{
		$this->aBreadCrumbEntry = [$sId, $sLabel, $sDescription, $sUrl, $sIcon];
	}

	public function GetRequest(): Request
	{
		return $this->oRequest;
	}

	/**
	 * Get a form builder.
	 * This form builder can be used to create a form or to add fields to an existing form.
	 *
	 * @param string $type
	 * @param mixed|null $data
	 * @param array $options
	 *
	 * @return FormBuilderInterface
	 */
	public function GetFormBuilder(string $type = FormType::class, mixed $data = null, array $options = []): FormBuilderInterface
	{
		return $this->oFormFactoryBuilder->getFormFactory()->createBuilder($type, $data, $options);
	}

	/**
	 * Get a form.
	 * This form can be directly used in a twig template.
	 *
	 * @param string $type
	 * @param mixed|null $data
	 * @param array $options
	 *
	 * @return FormInterface
	 */
	public function GetForm(string $type = FormType::class, mixed $data = null, array $options = []): FormInterface
	{
		if (is_null($data)) {
			$data = $type::GetDefaultData();
		}
		return $this->GetFormBuilder($type, $data, $options)->getForm();
	}

	/**
	 * @param $aParams
	 * @param $sName
	 * @param $sTemplateFileExtension
	 *
	 * @return string|false
	 * @throws \Exception
	 */
	private function RenderTemplate(array $aParams, string $sName, string $sTemplateFileExtension, string &$sErrorMsg = null): string|false
	{
		$sTemplateFile = $sName.'.'.$sTemplateFileExtension.'.twig';
		if (empty($this->oTwig)) {
			throw new Exception('Not initialized. Call Controller::InitFromModule() or Controller::SetViewPath() before any display');
		}
		try {
			return $this->oTwig->render($sTemplateFile, $aParams);
		} catch (SyntaxError $e) {
			IssueLog::Error($e->getMessage().' - file: '.$e->getFile().'('.$e->getLine().')');
			return $this->oTwig->render('application/forms/itop_error.html.twig', ['sControllerError' => $e->getMessage()]);
		} catch (Exception $e) {
			$sExceptionMessage = $e->getMessage();
			if (str_contains($sExceptionMessage, 'at line')) {
				IssueLog::Error($sExceptionMessage);
				return $this->oTwig->render('application/forms/itop_error.html.twig', ['sControllerError' => $sExceptionMessage]);
			}
			if (!str_contains($sExceptionMessage, 'Unable to find template')) {
				IssueLog::Error($sExceptionMessage);
			}
			if (is_null($sErrorMsg)) {
				$sErrorMsg = '';
			}
			$sErrorMsg .= $sExceptionMessage."\n";
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

			case self::ENUM_PAGE_TYPE_SETUP:
				$this->oPage = new SetupPage($this->GetOperationTitle());
				break;
		}
		$this->oTwig->addGlobal('UIBlockParent', [$this->oPage]);
		$this->oTwig->addGlobal('oPage', $this->oPage);
		$this->oTwig->addGlobal('debug', utils::IsDevelopmentEnvironment());
	}

	/**
	 * Get the title of the operation
	 *
	 * @return string
	 */
	public function GetOperationTitle()
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
	private function AddToPage($sContent)
	{
		$this->oPage->add($sContent);
	}

	private function AddReadyScriptToPage($sScript)
	{
		$this->oPage->add_ready_script($sScript);
	}

	private function AddScriptToPage($sScript)
	{
		$this->oPage->add_script($sScript);
	}

	private function AddLinkedScriptToPage($sLinkedScript)
	{
		$this->oPage->LinkScriptFromURI($sLinkedScript);
	}

	private function AddLinkedStylesheetToPage($sLinkedStylesheet)
	{
		$this->oPage->LinkStylesheetFromURI($sLinkedStylesheet);
	}

	private function AddStyleToPage($sStyle)
	{
		$this->oPage->add_style($sStyle);
	}

	private function AddSaasToPage($sSaasRelPath)
	{
		$this->oPage->add_saas($sSaasRelPath);
	}

	private function AddAjaxTabToPage($sCode, $sTitle, $sURL, $bCache)
	{
		$this->oPage->AddAjaxTab($sCode, $sURL, $bCache, $sTitle);
	}

	/**
	 * @param string $sKey
	 * @param $value
	 * @since 3.0.0
	 */
	private function SetBlockParamToPage(string $sKey, $value)
	{
		$this->oPage->SetBlockParam($sKey, $value);
	}

	/**
	 * @throws \Exception
	 */
	private function OutputPage()
	{
		$this->oPage->output();
	}

	private function InitDebugExtensions()
	{
		foreach (InterfaceDiscovery::GetInstance()->FindItopClasses(iProfilerExtension::class) as $sExtension) {
			/** @var \Combodo\iTop\Application\TwigBase\Controller\iProfilerExtension $oExtensionInstance */
			$oExtensionInstance = $sExtension::GetInstance();
			$oExtensionInstance->Init();
		}
	}

	/**
	 * @param array $aParams
	 *
	 * @return void
	 * @throws \ReflectionException
	 * @throws \Twig\Error\LoaderError
	 * @throws \Twig\Error\RuntimeError
	 * @throws \Twig\Error\SyntaxError
	 */
	private function ManageDebugExtensions(array $aParams): void
	{
		foreach (InterfaceDiscovery::GetInstance()->FindItopClasses(iProfilerExtension::class) as $sExtension) {
			/** @var \Combodo\iTop\Application\TwigBase\Controller\iProfilerExtension $oExtensionInstance */
			$oExtensionInstance = $sExtension::GetInstance();
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
				$this->AddToPage($this->oTwig->render($sDebugTemplate, $aDebugParams));
			}
		}
	}
}
