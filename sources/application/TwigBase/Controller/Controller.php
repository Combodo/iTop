<?php
/**
 * @copyright   Copyright (C) 2010-2019 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\TwigBase\Controller;

use ajax_page;
use ApplicationMenu;
use Combodo\iTop\Application\TwigBase\Twig\TwigHelper;
use Dict;
use Exception;
use IssueLog;
use iTopWebPage;
use LoginWebPage;
use MetaModel;
use ReflectionClass;
use SetupPage;
use SetupUtils;
use Twig_Error;
use utils;
use ZipArchive;

abstract class Controller
{
	/** @var \Twig\Environment */
	private $m_oTwig;
	/** @var string */
	private $m_sOperation;
	/** @var string */
	private $m_sModule;
	/** @var iTopWebPage|\ajax_page */
	private $m_oPage;
	/** @var bool */
	private $m_bCheckDemoMode = false;
	/** @var bool */
	private $m_bMustBeAdmin = false;
	/** @var string */
	private $m_sMenuId = null;
	/** @var string */
	private $m_sDefaultOperation = 'Default';
	private $m_aDefaultParams;
	private $m_aLinkedScripts;
	private $m_aLinkedStylesheets;
	private $m_aAjaxTabs;


	public function __construct()
	{
		$this->m_aLinkedScripts = array();
		$this->m_aLinkedStylesheets = array();
		$this->m_aAjaxTabs = array();
		$this->m_aDefaultParams = array();
	}

	/**
	 * Initialize the Controller from a module
	 */
	public function InitFromModule()
	{
		$sModulePath = dirname(dirname($this->getDir()));
		$this->SetModuleName(basename($sModulePath));
		$this->SetViewPath($sModulePath.'/view');
		try
		{
			$this->m_aDefaultParams = array('sIndexURL' => utils::GetAbsoluteUrlModulePage($this->m_sModule, 'index.php'));
		}
		catch (Exception $e)
		{
			IssueLog::Error($e->getMessage());
		}
	}

	/**
	 * Indicates the path of the view directory (containing the twig templates)
	 *
	 * @param string $sViewPath
	 */
	public function SetViewPath($sViewPath)
	{
		$oTwig = TwigHelper::GetTwigEnvironment($sViewPath);
		$this->m_oTwig = $oTwig;
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

	private function getDir()
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
		try
		{
			$this->CheckAccess();
			$this->m_sOperation = utils::ReadParam('operation', $this->m_sDefaultOperation);

			$sMethodName = 'Operation'.$this->m_sOperation;
			if (method_exists($this, $sMethodName))
			{
				$this->$sMethodName();
			}
			else
			{
				$this->DisplayPageNotFound();
			}
		}
		catch (Exception $e)
		{
			require_once(APPROOT."/setup/setuppage.class.inc.php");

			http_response_code(500);
			$oP = new SetupPage(Dict::S('UI:PageTitle:FatalError'));
			$oP->add("<h1>".Dict::S('UI:FatalErrorMessage')."</h1>\n");
			$oP->add(get_class($e).' : '.htmlentities($e->GetMessage(), ENT_QUOTES, 'utf-8'));
			$oP->output();

			IssueLog::Error($e->getMessage());
		}
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
	 * @throws \Exception
	 */
	private function CheckAccess()
	{
		if ($this->m_bCheckDemoMode && MetaModel::GetConfig()->Get('demo_mode'))
		{
			throw new Exception("Sorry, iTop is in <b>demonstration mode</b>: this feature is disabled.");
		}

		LoginWebPage::DoLogin($this->m_bMustBeAdmin);
		if (!empty($this->m_sMenuId))
		{
			ApplicationMenu::CheckMenuIdEnabled($this->m_sMenuId);
		}
	}

	/**
	 * @return array
	 * @throws \Exception
	 */
	private function GetDefaultParameters()
	{
		return $this->m_aDefaultParams;
	}

	/**
	 * Disable this feature if in demo mode
	 *
	 * @api
	 */
	public function DisableInDemoMode()
	{
		$this->m_bCheckDemoMode = true;
	}

	/**
	 * Allow only admin users for this feature
	 *
	 * @api
	 */
	public function AllowOnlyAdmin()
	{
		$this->m_bMustBeAdmin = true;
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
		$this->m_sMenuId = $sMenuId;
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
		$this->m_sDefaultOperation = $sDefaultOperation;
	}

	/**
	 * Display an AJAX page (ajax_page)
	 *
	 * @api
	 *
	 * @param array $aParams Params used by the twig template
	 * @param null $sTemplateName Name of the twig template, ie MyTemplate for MyTemplate.html.twig
	 *
	 * @throws \Exception
	 */
	public function DisplayAjaxPage($aParams = array(), $sTemplateName = null)
	{
		$this->DisplayPage($aParams, $sTemplateName, 'ajax');
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
	public function DisplayPage($aParams = array(), $sTemplateName = null, $sPageType = 'html')
	{
		if (empty($sTemplateName))
		{
			$sTemplateName = $this->m_sOperation;
		}
		$aParams = array_merge($this->GetDefaultParameters(), $aParams);
		$this->CreatePage($sPageType);
		$this->AddToPage($this->RenderTemplate($aParams, $sTemplateName, 'html'));
		$this->AddScriptToPage($this->RenderTemplate($aParams, $sTemplateName, 'js'));
		$this->AddReadyScriptToPage($this->RenderTemplate($aParams, $sTemplateName, 'ready.js'));
		if (!empty($this->m_aAjaxTabs))
		{
			$this->m_oPage->AddTabContainer('');
			$this->m_oPage->SetCurrentTabContainer('');
		}
		foreach ($this->m_aAjaxTabs as $aTab)
		{
			$this->AddAjaxTabToPage($aTab['label'], $aTab['url'], $aTab['cache']);
		}
		foreach ($this->m_aLinkedScripts as $sLinkedScript)
		{
			$this->AddLinkedScriptToPage($sLinkedScript);
		}
		foreach ($this->m_aLinkedStylesheets as $sLinkedStylesheet)
		{
			$this->AddLinkedStylesheetToPage($sLinkedStylesheet);
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
	public function DisplayJSONPage($aParams = array(), $iResponseCode = 200, $aHeaders = array())
	{
		http_response_code($iResponseCode);
		header('Content-Type: application/json');
		foreach ($aHeaders as $sHeader)
		{
			header($sHeader);
		}
		echo json_encode($aParams);
	}

	/**
	 * Generate a page, zip it and propose the zipped file for download
	 *
	 * @api
	 *
	 * @param array $aParams Params used by the twig template
	 * @param null $sTemplateName Name of the twig template, ie MyTemplate for MyTemplate.html.twig
	 */
	public function DownloadZippedPage($aParams = array(), $sTemplateName = null)
	{
		if (empty($sTemplateName))
		{
			$sTemplateName = $this->m_sOperation;
		}
		$sReportFolder = str_replace("\\", '/', APPROOT.'log/');
		$sReportFile = 'itop-system-information-report-'.date('Y-m-d-H-i-s');
		$sHTMLReport = $sReportFolder.$sReportFile.'.html';
		$sZIPReportFile = $sReportFile;

		file_put_contents($sHTMLReport, $this->RenderTemplate($aParams, $sTemplateName, 'html'));

		$this->ZipDownloadRemoveFile(array($sHTMLReport), $sZIPReportFile, true);
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
		foreach ($aFiles as $sFile)
		{
			$oArchive->addFile($sFile, basename($sFile));
		}
		$oArchive->close();

		if ($bUnlinkFiles)
		{
			foreach ($aFiles as $sFile)
			{
				unlink($sFile);
			}
		}

		$this->SendFileContent($sArchiveFileFullPath, $sDownloadArchiveName.'.zip', true, true);
	}

	final protected function SendFileContent($sFilePath, $sDownloadArchiveName = null, $bFileTransfer = true, $bRemoveFile = false, $aHeaders = array())
	{
		$sFileMimeType = utils::GetFileMimeType($sFilePath);
		header('Content-Type: '.$sFileMimeType);

		if ($bFileTransfer)
		{
			header('Content-Description: File Transfer');
			header('Content-Disposition: inline; filename="'.$sDownloadArchiveName);
		}

		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');

		foreach ($aHeaders as $sKey => $sValue)
		{
			header($sKey.': '.$sValue);
		}

		header('Content-Length: '.filesize($sFilePath));

		readfile($sFilePath);

		if ($bRemoveFile)
		{
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
	 */
	public function AddLinkedScript($sScript)
	{
		$this->m_aLinkedScripts[] = $sScript;
	}

	/**
	 * Add an linked stylesheet to the current Page
	 *
	 * @api
	 *
	 * @param string $sStylesheet Stylesheet path to link
	 */
	public function AddLinkedStylesheet($sStylesheet)
	{
		$this->m_aLinkedStylesheets[] = $sStylesheet;
	}

	/**
	 * Add an AJAX tab to the current page
	 *
	 * @api
	 *
	 * @param string $sLabel Label of the tab
	 * @param string $sURL URL to call when the tab is activated
	 * @param bool $bCache If true, cache the result for the current web page
	 */
	public function AddAjaxTab($sLabel, $sURL, $bCache = true)
	{
		$this->m_aAjaxTabs[] = array('label' => $sLabel, 'url' => $sURL, 'cache' => $bCache);
	}

	private function RenderTemplate($aParams, $sName, $sTemplateFileExtension)
	{
		if (empty($this->m_oTwig))
		{
			return 'Not initialized. Call Controller::InitFromModule() or Controller::SetViewPath() before any display';
		}
		try
		{
			return $this->m_oTwig->render($sName.'.'.$sTemplateFileExtension.'.twig', $aParams);
		}
		catch (Twig_Error $e)
		{
			// Ignore errors
			if (!utils::StartsWith($e->getMessage(), 'Unable to find template'))
			{
				IssueLog::Error($e->getMessage());
			}
		}

		return '';
	}

	/**
	 * @param $sPageType
	 *
	 * @throws \Exception
	 */
	private function CreatePage($sPageType)
	{
		switch ($sPageType)
		{
			case 'html':
				$this->m_oPage = new iTopWebPage($this->GetOperationTitle());
				break;

			case 'ajax':
				$this->m_oPage = new ajax_page($this->GetOperationTitle());
				break;
		}
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
	 * @param $sContent
	 *
	 * @throws \Exception
	 */
	private function AddToPage($sContent)
	{
		$this->m_oPage->add($sContent);
	}

	private function AddReadyScriptToPage($sScript)
	{
		$this->m_oPage->add_ready_script($sScript);
	}

	private function AddScriptToPage($sScript)
	{
		$this->m_oPage->add_script($sScript);
	}

	private function AddLinkedScriptToPage($sLinkedScript)
	{
		$this->m_oPage->add_linked_script($sLinkedScript);
	}

	private function AddLinkedStylesheetToPage($sLinkedStylesheet)
	{
		$this->m_oPage->add_linked_stylesheet($sLinkedStylesheet);
	}

	private function AddAjaxTabToPage($sLabel, $sURL, $bCache)
	{
		$this->m_oPage->AddAjaxTab($sLabel, $sURL, $bCache);
	}

	/**
	 * @throws \Exception
	 */
	private function OutputPage()
	{
		$this->m_oPage->output();
	}
}
