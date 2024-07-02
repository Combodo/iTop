<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\WebPage;

use Combodo\iTop\Application\Branding;
use Combodo\iTop\Application\Helper\Session;
use Combodo\iTop\Application\Helper\WebResourcesHelper;
use Combodo\iTop\Application\TwigBase\Twig\TwigHelper;
use Combodo\iTop\Application\UI\Base\Component\Alert\AlertUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\DataTable\DataTableUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Html\Html;
use Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenu;
use Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenuFactory;
use Combodo\iTop\Application\UI\Base\iUIBlock;
use Combodo\iTop\Application\UI\Base\Layout\iUIContentBlock;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;
use Combodo\iTop\Renderer\BlockRenderer;
use Combodo\iTop\Renderer\Console\ConsoleBlockRenderer;
use DBSearch;
use DeprecatedCallsLog;
use Dict;
use ExecutionKPI;
use IssueLog;
use LogChannels;
use MetaModel;
use Symfony\Component\HttpFoundation\Response;
use UserRights;
use utils;
use const APPROOT;
use const MODULESROOT;


/**
 * <p>Simple helper class to ease the production of HTML pages
 *
 * <p>This class provide methods to add content, scripts, includes... to a web page
 * and renders the full web page by putting the elements in the proper place & order
 * when the output() method is called.
 *
 * <p>Usage:
 * ```php
 *    $oPage = new WebPage("Title of my page");
 *    $oPage->p("Hello World !");
 *    $oPage->output();
 * ```
 * </p>
 * <p>
 * JS scripts can be added. They will be executed in sequence depending on the API used to inject them:
 * <ol>
 *    <li>add_early_script()</li>
 *    <li>LinkScriptFromAppRoot(), LinkScriptFromURI(), LinkScriptFromModule()</li>
 *    <li>add_script()</li>
 *    <li>add_init_script() - DOMContentLoaded</li>
 *    <li>add_ready_script() - DOMContentLoaded + 50 ms</li>
 * </ol>
 * </p>
 */
class WebPage implements Page
{
	/**
	 * @since 2.7.0 N°2529
	 */
	const PAGES_CHARSET = 'utf-8';

	/**
	 * @var string
	 * @since 3.0.0
	 */
	public const ENUM_SESSION_MESSAGE_SEVERITY_INFO = 'INFO';
	/**
	 * @var string
	 * @since 3.0.0
	 */
	public const ENUM_SESSION_MESSAGE_SEVERITY_OK = 'ok';
	/**
	 * @var string
	 * @since 3.0.0
	 */
	public const ENUM_SESSION_MESSAGE_SEVERITY_WARNING = 'warning';
	/**
	 * @var string
	 * @since 3.0.0
	 */
	public const ENUM_SESSION_MESSAGE_SEVERITY_ERROR = 'error';

	/**
	 * @var string
	 * @since 3.2.0
	 */
	protected const ENUM_RESOURCE_TYPE_JS = "js";
	/**
	 * @var string
	 * @since 3.2.0
	 */
	protected const ENUM_RESOURCE_TYPE_CSS = "css";

	/**
	 * @var string
	 * @since 3.0.0
	 */
	protected const ENUM_COMPATIBILITY_FILE_TYPE_JS = 'js';
	/**
	 * @var string
	 * @since 3.0.0
	 */
	protected const ENUM_COMPATIBILITY_FILE_TYPE_CSS = 'css';
	/**
	 * @var string
	 * @since 3.0.0
	 */
	protected const ENUM_COMPATIBILITY_MODE_MOVED_FILES = 'moved';
	/**
	 * @var string
	 * @since 3.0.0
	 */
	protected const ENUM_COMPATIBILITY_MODE_DEPRECATED_FILES = 'deprecated';

	/**
	 * @var array Script linked to the page through URIs, which are now included only were necessary instead of in all pages, but can be added back if necessary {@see "compatibility.include_moved_js_files" conf. param.}
	 * @since 3.0.0
	 */
	protected const COMPATIBILITY_MOVED_LINKED_SCRIPTS_REL_PATH = [];
	/**
	 * @var array Script linked to the page through URIs, which were deprecated (in iTop previous version) but can be added back if necessary {@see "compatibility.include_deprecated_js_files" conf. param.}
	 * @since 3.0.0
	 */
	protected const COMPATIBILITY_DEPRECATED_LINKED_SCRIPTS_REL_PATH = [];
	/**
	 * @var array Stylesheets linked to the page through URIs, which are now included only were necessary instead of in all pages, but can be added back if necessary {@see "compatibility.include_moved_css_files" conf. param.}
	 * @since 3.0.0
	 */
	protected const COMPATIBILITY_MOVED_LINKED_STYLESHEETS_REL_PATH = [];
	/**
	 * @var array Stylesheets linked to the page through URIs, which were deprecated (in iTop previous version) but can be added back if necessary {@see "compatibility.include_deprecated_css_files" conf. param.}
	 * @since 3.0.0
	 */
	protected const COMPATIBILITY_DEPRECATED_LINKED_STYLESHEETS_REL_PATH = [];

	/**
	 * @var string
	 * @since 3.0.0
	 */
	public const DEFAULT_SESSION_MESSAGE_SEVERITY = self::ENUM_SESSION_MESSAGE_SEVERITY_INFO;
	/**
	 * @var string Rel. path to the template to use for the rendering. File name must be without the extension.
	 * @since 3.0.0
	 */
	const DEFAULT_PAGE_TEMPLATE_REL_PATH = 'pages/backoffice/webpage/layout';

	protected $s_title;
	protected $s_content;
	protected $s_deferred_content;
	/**
	 * @var array Scripts to be put in the page's header, therefore executed first, BEFORE the DOM interpretation.
	 *            /!\ Keep in mind that no external JS files (eg. jQuery) will be loaded yet.
	 * @since 3.0.0
	 */
	protected $a_early_scripts;
	/** @var array Scripts to be executed immediately without waiting for the DOM to be ready */
	protected $a_scripts;
	/** @var array Scripts to be executed when the DOM is ready (typical JQuery use), right before "ready scripts" */
	protected $a_init_scripts;
	/**
	 * @see GetReadyScripts getter that adds custom script at the end
	 * @var array Scripts to be executed when the DOM is ready, with a slight delay, after the "init scripts"
	 */
	protected $a_ready_scripts;
	/** @var array Scripts linked (externals) to the page through URIs */
	protected $a_linked_scripts;
	/** @var array Specific dictionary entries to be used client side */
	protected $a_dict_entries;
	/** @var array Sub-sets of dictionary entries (based on the given prefix) for the client side */
	protected $a_dict_entries_prefixes;
	/** @var array Inline style to put in the page's header */
	protected $a_styles;
	/** @var array Stylesheets linked (external) to the page through URIs */
	protected $a_linked_stylesheets;
	/**
	 * @var array These parameters are used by the page UIBlocks and avoid accessing them directly from external code
	 * @since 3.0.0
	 */
	protected $aBlockParams;
	/** @var array Contain fonts that needs to be preloaded
	 * @since 3.0.0
	 */
	protected $aPreloadedFonts;
	protected $a_headers;
	protected $a_base;
	protected $iNextId;
	protected $iTransactionId;
	protected $sContentType;
	protected $sContentDisposition;
	protected $sContentFileName;
	protected $bTrashUnexpectedOutput;
	protected $s_sOutputFormat;
	protected $a_OutputOptions;
	protected $bPrintable;
	protected $bHasCollapsibleSection;
	/**
	 * @var bool Whether the JS dictionary entries should be added to the page or not during the final output
	 * @see static::add_dict_entry
	 * @see static::add_dict_entries
	 */
	protected $bAddJSDict;
	/** @var iUIContentBlock $oContentLayout */
	protected $oContentLayout;
	protected $sTemplateRelPath;


	/**
	 * @var bool|string|string[]
	 */
	private $s_OutputFormat;

	/**
	 * WebPage constructor.
	 *
	 * @param string $s_title
	 * @param bool $bPrintable
	 */
	public function __construct(string $s_title, bool $bPrintable = false)
	{
		$oKpi = new ExecutionKPI();
		$this->s_title = $s_title;
		$this->s_content = "";
		$this->s_deferred_content = '';
		$this->InitializeEarlyScripts();
		$this->InitializeScripts();
		$this->InitializeInitScripts();
		$this->InitializeReadyScripts();
		$this->InitializeLinkedScripts();
		$this->InitializeCompatibilityLinkedScripts();
		$this->InitializeDictEntries();
		$this->InitializeStyles();
		$this->InitializeLinkedStylesheets();
		$this->InitializeCompatibilityLinkedStylesheets();
		$this->aPreloadedFonts = WebResourcesHelper::GetPreloadedFonts();
		$this->a_headers = [];
		$this->a_base = ['href' => '', 'target' => ''];
		$this->iNextId = 0;
		$this->iTransactionId = 0;
		$this->sContentType = '';
		$this->sContentDisposition = '';
		$this->sContentFileName = '';
		$this->bTrashUnexpectedOutput = false;
		$this->s_OutputFormat = utils::ReadParam('output_format', 'html');
		$this->a_OutputOptions = [];
		$this->aBlockParams = [];
		$this->bHasCollapsibleSection = false;
		$this->bPrintable = $bPrintable;
		// Note: JS dict. entries cannot be added to a page if current environment and config file aren't available yet.
		$this->bAddJSDict = class_exists('\Dict') && is_dir(utils::GetCompiledEnvironmentPath().'dictionaries/') && file_exists(utils::GetConfigFilePath());
		$this->oContentLayout = new UIContentBlock();
		$this->SetTemplateRelPath(static::DEFAULT_PAGE_TEMPLATE_REL_PATH);

		ob_start(); // Start capturing the output
		$oKpi->ComputeStats(get_class($this).' creation', 'WebPage');
	}

	/**
	 * @param string $sMessageKey
	 * @param array $aRanks
	 * @param array $aMessages
	 */
	public function AddSessionMessages(string $sMessageKey, array $aRanks = [], array $aMessages = []): void
	{
		if (is_array(Session::Get(['obj_messages', $sMessageKey]))) {
			$aReadMessages = [];
			foreach (Session::Get(['obj_messages', $sMessageKey]) as $aMessageData) {
				if (!in_array($aMessageData['message'], $aReadMessages)) {
					$aReadMessages[] = $aMessageData['message'];
					$aRanks[] = $aMessageData['rank'];
					switch ($aMessageData['severity']) {
						case static::ENUM_SESSION_MESSAGE_SEVERITY_OK:
							$aMessages[] = AlertUIBlockFactory::MakeForSuccess('', $aMessageData['message']);
							break;
						case static::ENUM_SESSION_MESSAGE_SEVERITY_WARNING:
							$aMessages[] = AlertUIBlockFactory::MakeForWarning('', $aMessageData['message']);
							break;
						case static::ENUM_SESSION_MESSAGE_SEVERITY_ERROR:
							$aMessages[] = AlertUIBlockFactory::MakeForDanger('', $aMessageData['message']);
							break;
						case static::ENUM_SESSION_MESSAGE_SEVERITY_INFO:
						default:
							$aMessages[] = AlertUIBlockFactory::MakeForInformation('', $aMessageData['message']);
							break;
					}
				}
			}
			Session::Unset(['obj_messages', $sMessageKey]);
		}
		array_multisort($aRanks, $aMessages);
		foreach ($aMessages as $oMessage) {
			$this->AddUiBlock($oMessage);
		}
	}

	/**
	 * Change the title of the page after its creation
	 *
	 * @param string $s_title
	 *
	 * @return void
	 */
	public function set_title($s_title)
	{
		$this->s_title = $s_title;
	}

	/**
	 * Specify a default URL and a default target for all links on a page
	 *
	 * @param string $s_href
	 * @param string $s_target
	 * @return void
	 */
	public function set_base($s_href = '', $s_target = '')
	{
		$this->a_base['href'] = $s_href;
		$this->a_base['target'] = $s_target;
	}

	/**
	 * @inheritDoc
	 */
	public function add($s_html)
	{
		$this->oContentLayout->AddHtml($s_html);
	}

	/**
	 * Add any rendered text or HTML fragment to the body of the page using a twig template
	 *
	 * @param string $sViewPath Absolute path of the templates folder
	 * @param string $sTemplateName Name of the twig template, ie MyTemplate for MyTemplate.html.twig
	 * @param array $aParams Params used by the twig template
	 * @param string $sDefaultType default type of the template ('html', 'xml', ...)
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function add_twig_template($sViewPath, $sTemplateName, $aParams = array(), $sDefaultType = 'html')
	{
		TwigHelper::RenderIntoPage($this, $sViewPath, $sTemplateName, $aParams, $sDefaultType);
	}

	/**
	 * Add any text or HTML fragment (identified by an ID) at the end of the body of the page
	 * This is useful to add hidden content, DIVs or FORMs that should not
	 * be embedded into each other.
	 *
	 * @param string $s_html
	 * @param string $sId
	 */
	public function add_at_the_end($s_html, $sId = null)
	{
		$this->AddDeferredBlock(new Html($s_html, $sId));
	}

	/**
	 * @inheritDoc
	 */
	public function p($s_html)
	{
		$this->add($this->GetP($s_html));
	}

	/**
	 * @inheritDoc
	 */
	public function pre($s_html)
	{
		$this->add('<pre>'.$s_html.'</pre>');
	}

	/**
	 * @inheritDoc
	 */
	public function add_comment($sText)
	{
		$this->add('<!--'.$sText.'-->');
	}

	/**
	 * Add a paragraph to the body of the page
	 *
	 * @param string $s_html
	 *
	 * @return string
	 */
	public function GetP($s_html)
	{
		return "<p>$s_html</p>\n";
	}

	/**
	 * @inheritDoc
	 * @throws \Exception
	 */
	public function table($aConfig, $aData, $aParams = array())
	{
		$oDataTable = $this->GetTableBlock($aConfig, $aData);
		$oDataTable->AddOption("bFullscreen", true);
		$this->AddUiBlock($oDataTable);
	}

	public function GetTableBlock($aColumns, $aData)
	{
		$sId = Utils::Sanitize(uniqid('form_', true), '', Utils::ENUM_SANITIZATION_FILTER_VARIABLE_NAME);

		return DataTableUIBlockFactory::MakeForForm($sId, $aColumns, $aData);
	}

	/**
	 * @param array $aConfig
	 * @param array $aData
	 * @param array $aParams
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function GetTable($aConfig, $aData, $aParams = array())
	{
		static $iNbTables = 0;
		$iNbTables++;
		$sHtml = "";
		$sHtml .= "<table class=\"listResults\">\n";
		$sHtml .= "<thead>\n";
		$sHtml .= "<tr>\n";
		foreach ($aConfig as $sName => $aDef)
		{
			$sHtml .= "<th title=\"".$aDef['description']."\">".$aDef['label']."</th>\n";
		}
		$sHtml .= "</tr>\n";
		$sHtml .= "</thead>\n";
		$sHtml .= "<tbody>\n";
		foreach ($aData as $aRow)
		{
			$sHtml .= $this->GetTableRow($aRow, $aConfig);
		}
		$sHtml .= "</tbody>\n";
		$sHtml .= "</table>\n";

		return $sHtml;
	}

	/**
	 * @param array $aRow
	 * @param array $aConfig
	 *
	 * @return string
	 */
	public function GetTableRow($aRow, $aConfig)
	{
		$sHtml = '';
		if (isset($aRow['@class'])) // Row specific class, for hilighting certain rows
		{
			$sHtml .= "<tr class=\"{$aRow['@class']}\">";
		}
		else
		{
			$sHtml .= "<tr>";
		}
		foreach ($aConfig as $sName => $aAttribs)
		{
			$sClass = isset($aAttribs['class']) ? 'class="'.$aAttribs['class'].'"' : '';

			// Prepare metadata
			// - From table config.
			$sMetadata = '';
			if(isset($aAttribs['metadata']))
			{
				foreach($aAttribs['metadata'] as $sMetadataProp => $sMetadataValue)
				{
					$sMetadataPropSanitized = str_replace('_', '-', $sMetadataProp);
					$sMetadataValueSanitized = utils::HtmlEntities($sMetadataValue);
					$sMetadata .= 'data-'.$sMetadataPropSanitized.'="'.$sMetadataValueSanitized.'" ';
				}
			}

			// Prepare value
			if(is_array($aRow[$sName]))
			{
				$sValueHtml = ($aRow[$sName]['value_html'] === '') ? '&nbsp;' : $aRow[$sName]['value_html'];
				$sMetadata .= 'data-value-raw="'.utils::HtmlEntities($aRow[$sName]['value_raw']).'" ';
			}
			else
			{
				$sValueHtml = ($aRow[$sName] === '') ? '&nbsp;' : $aRow[$sName];
			}

			$sHtml .= "<td $sClass $sMetadata>$sValueHtml</td>";
		}
		$sHtml .= "</tr>";

		return $sHtml;
	}

	/**
	 * Add a UIBlock in the page by dispatching its parts in the right places (CSS, JS, HTML)
	 *
	 * @param \Combodo\iTop\Application\UI\Base\iUIBlock $oBlock
	 *
	 * @return \Combodo\iTop\Application\UI\Base\iUIBlock block added
	 * @since 3.0.0
	 */
	public function AddUiBlock(?iUIBlock $oBlock): ?iUIBlock
	{
		// TODO 3.0.0: Why make this parameter nullable?!
		if (is_null($oBlock)) {
			return null;
		}
		$this->oContentLayout->AddSubBlock($oBlock);
		return $oBlock;
	}

	public function AddSubBlock(?iUIBlock $oBlock): ?iUIBlock
	{
		return $this->AddUiBlock($oBlock);
	}

	/**
	 * Add a UIBlock in the page at the end by dispatching its parts in the right places (CSS, JS, HTML)
	 *
	 * @param \Combodo\iTop\Application\UI\Base\iUIBlock $oBlock
	 *
	 * @return \Combodo\iTop\Application\UI\Base\iUIBlock block added
	 * @since 3.0.0
	 */
	public function AddDeferredBlock(iUIBlock $oBlock): ?iUIBlock
	{
		$this->oContentLayout->AddDeferredBlock($oBlock);
		return $oBlock;
	}

	/**
	 * Internal helper to link a resource from the iTop package (e.g. `<ITOP/`)
	 *
	 * @param string $sFileRelPath Rel. path from iTop app. root of the resource to link (e.g. `css/login.css`)
	 * @param string $sResourceType {@see static::ENUM_RESOURCE_TYPE_JS}, {@see static::ENUM_RESOURCE_TYPE_CSS}
	 *
	 * @return void
	 * @throws \Exception
	 * @internal
	 * @since 3.2.0 N°7315
	 */
	private function LinkResourceFromAppRoot(string $sFileRelPath, string $sResourceType): void
	{
		// Ensure there is actually a URI
		if (utils::IsNullOrEmptyString(trim($sFileRelPath))) {
			return;
		}

		// Ensure file is within the app folder
		$sFileRelPathWithoutQueryParams = explode("?", $sFileRelPath)[0];
		if (false === utils::RealPath(APPROOT . $sFileRelPathWithoutQueryParams, APPROOT)) {
			IssueLog::Warning("Linked resource added to page with a path from outside app directory, it will be ignored.", LogChannels::CONSOLE, [
				"linked_resource_uri" => $sFileRelPath,
				"request_uri" => $_SERVER['REQUEST_URI'] ?? '' /* CLI */,
			]);
			return;
		}

		$sAppRootUrl = utils::GetAbsoluteUrlAppRoot();

		// Ensure app root url ends with a slash as it is not guaranteed by the API
		if (strcmp(substr($sAppRootUrl, -1), '/') !== 0) {
			$sAppRootUrl .= '/';
		}

		$this->LinkResourceFromURI($sAppRootUrl . $sFileRelPath, $sResourceType);
	}

	/**
	 * Internal helper to link a resource from any module
	 *
	 * @param string $sFileRelPath Rel. path from current environment (e.g. `<ITOP>/env-production/`) of the resource to link (e.g. `some-module/asset/css/some-file.css`)
	 * @param string $sResourceType {@see static::ENUM_RESOURCE_TYPE_JS}, {@see static::ENUM_RESOURCE_TYPE_CSS}
	 *
	 * @return void
	 * @throws \Exception
	 * @internal
	 * @since 3.2.0 N°7315
	 */
	private function LinkResourceFromModule(string $sFileRelPath, string $sResourceType): void
	{
		// Ensure there is actually a URI
		if (utils::IsNullOrEmptyString(trim($sFileRelPath))) {
			return;
		}

		// Ensure file is within the app folder
		$sFileRelPathWithoutQueryParams = explode("?", $sFileRelPath)[0];
		$sFileAbsPath = MODULESROOT . $sFileRelPathWithoutQueryParams;
		// For modules only, we don't check real path if symlink as the file would not be in under MODULESROOT
		if (false === is_link($sFileAbsPath) && false === utils::RealPath($sFileAbsPath, MODULESROOT)) {
			IssueLog::Warning("Linked resource added to page with a path from outside current env. directory, it will be ignored.", LogChannels::CONSOLE, [
				"linked_resource_url" => $sFileRelPath,
				"request_uri" => $_SERVER['REQUEST_URI'] ?? '' /* CLI */,
			]);
			return;
		}

		$this->LinkResourceFromURI(utils::GetAbsoluteUrlModulesRoot() . $sFileRelPath, $sResourceType);
	}

	/**
	 * Internal helper to link a resource from any URI, may it be on iTop server or an external one
	 *
	 * @param string $sFileAbsURI Abs. URI of the resource to link (e.g. `https://external.server.com/some-file.css`)
	 * @param string $sResourceType {@see static::ENUM_RESOURCE_TYPE_JS}, {@see static::ENUM_RESOURCE_TYPE_CSS}
	 *
	 * @return void
	 * @throws \Exception
	 * @internal
	 * @since 3.2.0 N°7315
	 */
	private function LinkResourceFromURI(string $sFileAbsURI, string $sResourceType): void
	{
		// Ensure there is actually a URI
		if (utils::IsNullOrEmptyString(trim($sFileAbsURI))) {
			return;
		}

		// Check if URI is absolute ("://" do allow any protocol), otherwise ignore the file
		if (false === stripos($sFileAbsURI, "://")) {
			IssueLog::Warning("Linked resource added to page with a non absolute URI, it will be ignored.", LogChannels::CONSOLE, [
				"linked_resource_url" => $sFileAbsURI,
				"request_uri" => $_SERVER['REQUEST_URI'] ?? '' /* CLI */,
			]);
			return;
		}

		switch ($sResourceType) {
			case static::ENUM_RESOURCE_TYPE_JS:
				$this->a_linked_scripts[$sFileAbsURI] = $sFileAbsURI;
				break;

			case static::ENUM_RESOURCE_TYPE_CSS:
				$this->a_linked_stylesheets[$sFileAbsURI] = ['link' => $sFileAbsURI, 'condition' => ''];
				break;
		}
	}

	/**
	 * Empty all base JS in the page's header
	 *
	 * @uses WebPage::$a_a_early_scripts
	 * @return void
	 * @since 3.0.0
	 */
	protected function EmptyEarlyScripts(): void
	{
		$this->a_early_scripts = [];
	}

	/**
	 * Initialize base JS in the page's header
	 *
	 * @uses WebPage::$a_scripts
	 * @return void
	 * @since 3.0.0
	 */
	protected function InitializeEarlyScripts(): void
	{
		$this->EmptyEarlyScripts();
	}

	/**
	 * Add some Javascript to the header of the page
	 *
	 * The provided JS code will be executed at step 1 of the JS execution chain:
	 * [early script] ==> linked script ==> script ==> init script ==> ready script
	 *
	 * /!\ Keep in mind that no external JS files (eg. jQuery) will be loaded yet.
	 *
	 * @api-advanced
	 * @see static::add_script, static::add_init_script, static::add_ready_script
	 * @see static::LinkScriptFromAppRoot, static::LinkScriptFromModule, static::LinkScriptFromURI
	 *
	 * @param string $s_script
	 *
	 * @since 3.0.0
	 * @uses WebPage::$a_early_scripts
	 */
	public function add_early_script($s_script)
	{
		if (!empty(trim($s_script))) {
			$this->a_early_scripts[] = $s_script;
		}
	}

	/**
	 * Empty all base JS
	 *
	 * @uses WebPage::$a_scripts
	 * @return void
	 * @since 3.0.0
	 */
	protected function EmptyScripts(): void
	{
		$this->a_scripts = [];
	}

	/**
	 * Initialize base JS
	 *
	 * @uses WebPage::$a_scripts
	 * @return void
	 * @since 3.0.0
	 */
	protected function InitializeScripts(): void
	{
		$this->EmptyScripts();
	}

	/**
	 * Add some Javascript to be executed immediately without waiting for the DOM to be ready
	 *
	 * The provided JS code will be executed at step 3 of the JS execution chain:
	 * early script ==> linked script ==> [script] ==> init script ==> ready script
	 *
	 * @api-advanced
	 * @see static::add_early_script, static::add_init_script, static::add_ready_script
	 * @see static::LinkScriptFromAppRoot, static::LinkScriptFromURI, static::LinkScriptFromModule
	 *
	 * @param string $s_script
	 *
	 * @uses WebPage::$a_scripts
	 * @since 3.0.0 These scripts are put at the end of the <body> tag instead of the end of the <head> tag, {@see static::add_early_script} to add script there
	 */
	public function add_script($s_script)
	{
		if (!empty(trim($s_script))) {
			$this->a_scripts[] = $s_script;
		}
	}

	/**
	 * Empty all base init. scripts for the page
	 *
	 * @uses WebPage::$a_init_scripts
	 * @return void
	 * @since 3.0.0
	 */
	protected function EmptyInitScripts(): void
	{
		$this->a_init_scripts = [];
	}

	/**
	 * Initialize base init. scripts for the page
	 *
	 * @uses WebPage::$a_init_scripts
	 * @return void
	 * @since 3.0.0
	 */
	protected function InitializeInitScripts(): void
	{
		$this->EmptyInitScripts();
	}

	/**
	 * Add a script to be executed when the DOM is ready (typical JQuery use)
	 *
	 * The provided JS code will be executed at step 4 of the JS execution chain:
	 * early script ==> linked script ==> script ==> [init script] ==> ready script
	 *
	 * @api-advanced
	 * @see static::add_early_script, static::add_script, static::add_ready_script
	 * @see static::LinkScriptFromAppRoot, static::LinkScriptFromURI, static::LinkScriptFromModule
	 *
	 * @param string $sScript
	 *
	 * @return void
	 *@uses WebPage::$a_init_scripts
	 */
	public function add_init_script($sScript)
	{
		if (!empty(trim($sScript))) {
			$this->a_init_scripts[] = $sScript;
		}
	}

	/**
	 * Empty all base ready scripts for the page
	 *
	 * @uses WebPage::$a_ready_scripts
	 * @return void
	 * @since 3.0.0
	 */
	protected function EmptyReadyScripts(): void
	{
		$this->a_ready_scripts = [];
	}

	/**
	 * Initialize base ready scripts for the page
	 *
	 * @uses WebPage::$a_reset_init_scripts
	 * @return void
	 * @since 3.0.0
	 */
	protected function InitializeReadyScripts(): void
	{
		$this->EmptyReadyScripts();
	}

	/**
	 * Add some Javascript to be executed once the DOM is ready, slightly (50 ms) after the "init scripts"
	 *
	 * The provided JS code will be executed at step 5 of the JS execution chain:
	 * early script ==> linked script ==> script ==> init script ==> [ready script]
	 *
	 * @api-advanced
	 * @see static::add_early_script, static::add_script, static::add_init_script
	 * @see static::LinkScriptFromAppRoot, static::LinkScriptFromURI, static::LinkScriptFromModule
	 *
	 * @param $sScript
	 *
	 * @uses WebPage::$a_ready_scripts
	 */
	public function add_ready_script($sScript)
	{
		if (!empty(trim($sScript))) {
			$this->a_ready_scripts[] = $sScript;
		}
	}

	/**
	 * @return array all the script added, plus a last one to know when ready scripts are done processing
	 * @since 3.0.0 N°3750 method creation
	 * @uses self::a_ready_scripts
	 * @uses self::GetReadyScriptsStartedTrigger
	 * @uses self::GetReadyScriptsFinishedTrigger
	 */
	protected function GetReadyScripts(): array
	{
		$aReadyScripts = $this->a_ready_scripts;

		$sReadyStartedTriggerScript = $this->GetReadyScriptsStartedTrigger();
		if (!empty($sReadyStartedTriggerScript)) {
			array_unshift($aReadyScripts, $sReadyStartedTriggerScript);
		}

		$sReadyFinishedTriggerScript = $this->GetReadyScriptsFinishedTrigger();
		if (!empty($sReadyFinishedTriggerScript)) {
			$aReadyScripts[] = $sReadyFinishedTriggerScript;
		}

		return $aReadyScripts;
	}

	/**
	 * @return ?string script to execute before all ready scripts
	 * @since 3.0.0 N°3750 method creation
	 */
	protected function GetReadyScriptsStartedTrigger(): ?string
	{
		return null;
	}

	/**
	 * @return ?string script to execute after all ready scripts are done processing
	 * @since 3.0.0 N°3750 method creation
	 */
	protected function GetReadyScriptsFinishedTrigger(): ?string
	{
		return null;
	}

	/**
	 * Empty all base linked scripts for the page
	 *
	 * @return void
	 * @uses WebPage::$a_linked_scripts
	 * @since 3.0.0
	 */
	protected function EmptyLinkedScripts(): void
	{
		$this->a_linked_scripts = [];
	}

	/**
	 * Initialize base linked scripts for the page
	 *
	 * @uses WebPage::$a_linked_scripts
	 * @return void
	 * @since 3.0.0
	 */
	protected function InitializeLinkedScripts(): void
	{
		$this->EmptyLinkedScripts();
	}

	/**
	 * Add a script (as an include, i.e. link) to the header of the page.<br>
	 * Handles duplicates : calling twice with the same script will add the script only once
	 *
	 * @param string $sLinkedScriptAbsUrl
	 *
	 * @return void
	 * @uses WebPage::$a_linked_scripts
	 * @since 3.2.0 N°6935 $sLinkedScriptAbsUrl MUST be an absolute URL
	 * @deprecated 3.2.0 N°7315 Use {@see static::LinkScriptFromXXX()} instead
	 */
	public function add_linked_script($sLinkedScriptAbsUrl)
	{
		DeprecatedCallsLog::NotifyDeprecatedPhpMethod();

		// Ensure there is actually a URI
		if (utils::IsNullOrEmptyString(trim($sLinkedScriptAbsUrl))) {
			return;
		}

		// Check if URI is absolute ("://" do allow any protocol), otherwise warn that it's a deprecated behavior
		if (false === stripos($sLinkedScriptAbsUrl, "://")) {
			IssueLog::Warning("Linked script added to page via deprecated API with a non absolute URL, it may lead to it not being loaded and causing javascript errors.", null, [
				"linked_script_url" => $sLinkedScriptAbsUrl,
				"request_uri" => $_SERVER['REQUEST_URI'] ?? '' /* CLI */,
			]);
		}

		$this->a_linked_scripts[$sLinkedScriptAbsUrl] = $sLinkedScriptAbsUrl;
	}

	/**
	 * Use to include JS files from the iTop package (e.g. `<ITOP>/js/*`)
	 *
	 * The provided JS code will be executed at step 2 of the JS execution chain:
	 * early script ==> [linked script] ==> script ==> init script ==> ready script
	 *
	 * @api-advanced
	 * @see static::add_early_script, static::add_script, static::add_init_script, static::add_ready_script
	 * @see static::LinkScriptFromURI, static::LinkScriptFromModule
	 *
	 * @param string $sFileRelPath Rel. path from iTop app. root of the JS file to link (e.g. `js/utils.js`)
	 *
	 * @return void
	 * @throws \Exception
	 * @since 3.2.0 N°7315
	 */
	public function LinkScriptFromAppRoot(string $sFileRelPath): void
	{
		$this->LinkResourceFromAppRoot($sFileRelPath, static::ENUM_RESOURCE_TYPE_JS);
	}

	/**
	 * Use to include JS files from any module
	 *
	 * The provided JS code will be executed at step 2 of the JS execution chain:
	 * early script ==> [linked script] ==> script ==> init script ==> ready script
	 *
	 * @api-advanced
	 * @see static::add_early_script, static::add_script, static::add_init_script, static::add_ready_script
	 * @see static::LinkScriptFromAppRoot, static::LinkScriptFromURI
	 *
	 * @param string $sFileRelPath Rel. path from current environment (e.g. `<ITOP>/env-production/`) of the JS file to link (e.g. `some-module/asset/js/some-file.js`)
	 *
	 * @return void
	 * @throws \Exception
	 * @since 3.2.0 N°7315
	 */
	public function LinkScriptFromModule(string $sFileRelPath): void
	{
		$this->LinkResourceFromModule($sFileRelPath, static::ENUM_RESOURCE_TYPE_JS);
	}

	/**
	 * Use to include JS files from any URI, typically an external server
	 *
	 * The provided JS code will be executed at step 2 of the JS execution chain:
	 * early script ==> [linked script] ==> script ==> init script ==> ready script
	 *
	 * @api-advanced
	 * @see static::add_early_script, static::add_script, static::add_init_script, static::add_ready_script
	 * @see static::LinkScriptFromAppRoot, static::LinkScriptFromModule
	 *
	 * @param string $sFileAbsURI Abs. URI of the JS file to link (e.g. `https://external.server.com/some-file.js`)
	 *                            Note: Any non-absolute URI will be ignored.
	 *
	 * @return void
	 * @throws \Exception
	 * @since 3.2.0 N°7315
	 */
	public function LinkScriptFromURI(string $sFileAbsURI): void
	{
		$this->LinkResourceFromURI($sFileAbsURI, static::ENUM_RESOURCE_TYPE_JS);
	}

	/**
	 * Initialize compatibility linked scripts for the page
	 *
	 * @see static::COMPATIBILITY_DEPRECATED_LINKED_SCRIPTS_REL_PATH
	 * @throws \ConfigException
	 * @throws \CoreException
	 * @since 3.0.0
	 */
	protected function InitializeCompatibilityLinkedScripts(): void
	{
		$bIncludeDeprecatedFiles = utils::GetConfig()->Get('compatibility.include_deprecated_js_files');
		if ($bIncludeDeprecatedFiles) {
			$this->AddCompatibilityFiles(static::ENUM_COMPATIBILITY_FILE_TYPE_JS, static::ENUM_COMPATIBILITY_MODE_DEPRECATED_FILES);
		}

		$bIncludeMovedFiles = utils::GetConfig()->Get('compatibility.include_moved_js_files');
		if ($bIncludeMovedFiles) {
			$this->AddCompatibilityFiles(static::ENUM_COMPATIBILITY_FILE_TYPE_JS, static::ENUM_COMPATIBILITY_MODE_MOVED_FILES);
		}
	}

	/**
	 * Empty both dict. entries and dict. entries prefixes for the page
	 *
	 * @uses WebPage::$a_dict_entries
	 * @uses WebPage::$dict_a_dict_entries_prefixes
	 * @return void
	 * @since 3.0.0
	 */
	protected function EmptyDictEntries(): void
	{
		$this->a_dict_entries = [];
		$this->a_dict_entries_prefixes = [];
	}

	/**
	 * Initialize both dict. entries and dict. entries prefixes for the page
	 *
	 * @uses WebPage::$a_dict_entries
	 * @uses WebPage::$dict_a_dict_entries_prefixes
	 * @return void
	 * @since 3.0.0
	 */
	protected function InitializeDictEntries(): void
	{
		$this->EmptyDictEntries();
	}

	/**
	 * Allow a dictionnary entry to be used client side with Dict.S()
	 *
	 * @param string $s_entryId a translation label key
	 *
	 * @uses WebPage::$a_dict_entries
	 * @see WebPage::add_dict_entries()
	 * @see utils.js
	 */
	public function add_dict_entry($s_entryId)
	{
		$this->a_dict_entries[] = $s_entryId;
	}

	/**
	 * Add a set of dictionary entries (based on the given prefix) for the Javascript side
	 *
	 * @param string $s_entriesPrefix translation label prefix (eg 'UI:Button:' to add all keys beginning with this)
	 *
	 * @see WebPage::::$dict_a_dict_entries_prefixes
	 * @see WebPage::add_dict_entry()
	 * @see utils.js
	 */
	public function add_dict_entries($s_entriesPrefix)
	{
		$this->a_dict_entries_prefixes[] = $s_entriesPrefix;
	}

	/**
	 * @return string
	 */
	protected function get_dict_signature()
	{
		return str_replace('_', '', Dict::GetUserLanguage()).'-'.md5(implode(',',
					$this->a_dict_entries).'|'.implode(',', $this->a_dict_entries_prefixes));
	}

	/**
	 * @return string
	 */
	protected function get_dict_file_content()
	{
		$aEntries = array();
		foreach ($this->a_dict_entries as $sCode)
		{
			$aEntries[$sCode] = Dict::S($sCode);
		}
		foreach ($this->a_dict_entries_prefixes as $sPrefix)
		{
			$aEntries = array_merge($aEntries, Dict::ExportEntries($sPrefix));
		}

		$sEntriesAsJson = json_encode($aEntries);
		$sJSFile = <<<JS
// Create variable, so it can be used by the Dict class on initialization
var aDictEntries = {$sEntriesAsJson};

// Check if Dict._entries already exists in order to complete, this is for async calls only.
// Note: We should not overload the WebPage::get_dict_file_content() in AjaxPage to put the part below as the same dict file can be consumed either by a regular page or an async page.
if ((typeof Dict != "undefined") && (typeof Dict._entries != "undefined")) {
	Object.assign(Dict._entries, aDictEntries);
}
JS;

		return $sJSFile;
	}

	/**
	 * Empty all inline styles for the page
	 *
	 * @uses WebPage::$a_styles
	 * @return void
	 * @since 3.0.0
	 */
	protected function EmptyStyles(): void
	{
		$this->a_styles = [];
	}

	/**
	 * Initialize inline styles for the page
	 *
	 * @uses WebPage::$a_styles
	 * @return void
	 * @since 3.0.0
	 */
	protected function InitializeStyles(): void
	{
		$this->EmptyStyles();
	}

	/**
	 * Add some CSS definitions to the header of the page
	 *
	 * @param string $s_style
	 */
	public function add_style($s_style)
	{
		if (!empty(trim($s_style))) {
			$this->a_styles[] = $s_style;
		}
	}

	/**
	 * Empty all linked stylesheets for the page
	 *
	 * @uses WebPage::$a_linked_stylesheets
	 * @return void
	 * @since 3.0.0
	 */
	protected function EmptyLinkedStylesheets(): void
	{
		$this->a_linked_stylesheets = [];
	}

	/**
	 * Initialize linked stylesheets for the page
	 *
	 * @uses WebPage::$a_linked_stylesheets
	 * @return void
	 * @since 3.0.0
	 */
	protected function InitializeLinkedStylesheets(): void
	{
		$this->EmptyLinkedStylesheets();
	}

	/**
	 * Add a CSS stylesheet (as an include, i.e. link) to the header of the page
	 * Handles duplicates since 3.0.0 : calling twig with the same stylesheet will add the stylesheet only once
	 *
	 * @param string $sLinkedStylesheet
	 * @param string $sCondition
	 * @return void
	 * @since 3.2.0 N°6935 $sLinkedStylesheet MUST be an absolute URL
	 * @deprecated 3.2.0 N°7315 Use {@see static::LinkStylesheetFromXXX()} instead
	 */
	public function add_linked_stylesheet($sLinkedStylesheet, $sCondition = "")
	{
		DeprecatedCallsLog::NotifyDeprecatedPhpMethod();

		// Ensure there is actually a URI
		if (utils::IsNullOrEmptyString(trim($sLinkedStylesheet))) {
			return;
		}

		// Check if URI is absolute ("://" do allow any protocol), otherwise warn that it's a deprecated behavior
		if (false === stripos($sLinkedStylesheet, "://")) {
			IssueLog::Warning("Linked stylesheet added to page via deprecated API with a non absolute URL, it may lead to it not being loaded and causing visual glitches.", null, [
				"linked_stylesheet_url" => $sLinkedStylesheet,
				"request_uri" => $_SERVER['REQUEST_URI'] ?? '' /* CLI */,
			]);
		}

		$this->a_linked_stylesheets[$sLinkedStylesheet] = array('link' => $sLinkedStylesheet, 'condition' => $sCondition);
	}

	/**
	 * Use to link CSS files from the iTop package (e.g. `<ITOP>/css/*`)
	 *
	 * @param string $sFileRelPath Rel. path from iTop app. root of the CSS file to link (e.g. `css/login.css`)
	 *
	 * @return void
	 * @throws \Exception
	 * @since 3.2.0 N°7315
	 * @api
	 */
	public function LinkStylesheetFromAppRoot(string $sFileRelPath): void
	{
		$this->LinkResourceFromAppRoot($sFileRelPath, static::ENUM_RESOURCE_TYPE_CSS);
	}

	/**
	 * Use to link CSS files from any module
	 *
	 * @param string $sFileRelPath Rel. path from current environment (e.g. `<ITOP>/env-production/`) of the CSS file to link (e.g. `some-module/asset/css/some-file.css`)
	 *
	 * @return void
	 * @throws \Exception
	 * @since 3.2.0 N°7315
	 * @api
	 */
	public function LinkStylesheetFromModule(string $sFileRelPath): void
	{
		$this->LinkResourceFromModule($sFileRelPath, static::ENUM_RESOURCE_TYPE_CSS);
	}

	/**
	 * Use to link CSS files from any URI, typically an external server
	 *
	 * @param string $sFileAbsURI Abs. URI of the CSS file to link (e.g. `https://external.server.com/some-file.css`)
	 *                            Note: Any non-absolute URI will be ignored.
	 *
	 * @return void
	 * @throws \Exception
	 * @since 3.2.0 N°7315
	 * @api
	 */
	public function LinkStylesheetFromURI(string $sFileAbsURI): void
	{
		$this->LinkResourceFromURI($sFileAbsURI, static::ENUM_RESOURCE_TYPE_CSS);
	}

	/**
	 * Initialize compatibility linked stylesheets for the page
	 *
	 * @see static::COMPATIBILITY_MOVED_LINKED_STYLESHEETS_REL_PATH
	 * @see static::COMPATIBILITY_DEPRECATED_LINKED_STYLESHEETS_REL_PATH
	 * @throws \ConfigException
	 * @throws \CoreException
	 * @since 3.0.0
	 */
	protected function InitializeCompatibilityLinkedStylesheets(): void
	{
		$bIncludeDeprecatedFiles = utils::GetConfig()->Get('compatibility.include_deprecated_css_files');
		if ($bIncludeDeprecatedFiles) {
			$this->AddCompatibilityFiles(static::ENUM_COMPATIBILITY_FILE_TYPE_CSS, static::ENUM_COMPATIBILITY_MODE_DEPRECATED_FILES);
		}

		$bIncludeMovedFiles = utils::GetConfig()->Get('compatibility.include_moved_css_files');
		if ($bIncludeMovedFiles) {
			$this->AddCompatibilityFiles(static::ENUM_COMPATIBILITY_FILE_TYPE_CSS, static::ENUM_COMPATIBILITY_MODE_MOVED_FILES);
		}
	}

	/**
	 * Add compatibility files of $sFileType from $sMode (which are declared in {@see static::COMPATIBILITY_<MODE>_LINKED_<TYPE>_REL_PATH}) back to the page
	 *
	 * @param string $sFileType {@uses static::ENUM_COMPATIBILITY_FILE_TYPE_XXX}
	 * @param string $sMode {@uses static::ENUM_COMPATIBILITY_MODE_XXX}
	 *
	 * @uses static::add_linked_script Depending on $sFileType
	 * @uses static::add_linked_stylesheet Depending on $sFileType
	 *
	 * @throws \Exception
	 * @since 3.0.0
	 */
	protected function AddCompatibilityFiles(string $sFileType, string $sMode): void
	{
		$sConstantName = 'COMPATIBILITY_'.strtoupper($sMode).'_LINKED_'. ($sFileType === static::ENUM_COMPATIBILITY_FILE_TYPE_CSS ? 'STYLESHEETS' : 'SCRIPTS') .'_REL_PATH';
		$sMethodName = 'add_linked_'.($sFileType === static::ENUM_COMPATIBILITY_FILE_TYPE_CSS ? 'stylesheet' : 'script');

		// Add ancestors files
		foreach (array_reverse(class_parents(static::class)) as $sParentClass) {
			foreach (constant($sParentClass.'::'.$sConstantName) as $sFile) {
				$this->$sMethodName(utils::GetAbsoluteUrlAppRoot().$sFile);
			}
		}

		// Add current class files
		foreach (constant('static::'.$sConstantName) as $sFile) {
			$this->$sMethodName(utils::GetAbsoluteUrlAppRoot().$sFile);
		}
	}

	/**
	 * @param string $sSaasRelPath
	 *
	 * @throws \Exception
	 */
	public function add_saas($sSaasRelPath)
	{
		$sCssRelPath = utils::GetCSSFromSASS($sSaasRelPath);
		$sRootUrl = utils::GetAbsoluteUrlAppRoot();
		if ($sRootUrl === '')
		{
			// We're running the setup of the first install...
			$sRootUrl = '../';
		}
		$sCSSUrl = $sRootUrl.$sCssRelPath;
		$this->LinkStylesheetFromURI($sCSSUrl);
	}

	/**
	 * Add some custom header to the page
	 *
	 * @param string $s_header
	 */
	public function add_header($s_header)
	{
		$this->a_headers[] = $s_header;
	}

	/**
	 * @param string|null $sXFrameOptionsHeaderValue passed to {@see add_xframe_options}
	 *
	 * @return void
	 * @since 2.7.10 3.0.4 3.1.2 3.2.0 N°4368 method creation, replace {@see add_xframe_options} consumers call
	 */
	public function add_http_headers($sXFrameOptionsHeaderValue = null)
	{
		$this->add_xframe_options($sXFrameOptionsHeaderValue);
		$this->add_xcontent_type_options();
	}

	/**
	 * @param string|null $sHeaderValue for example `SAMESITE`. If null will set the header using the `security_header_xframe` config parameter value.
	 *
	 * @since 2.7.3 3.0.0 N°3416
	 * @uses \utils::GetConfig()
	 *
	 * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Frame-Options HTTP header MDN documentation
	 */
	public function add_xframe_options($sHeaderValue = null)
	{
		if (is_null($sHeaderValue)) {
			$sHeaderValue = utils::GetConfig()->Get('security_header_xframe');
		}

		$this->add_header('X-Frame-Options: '.$sHeaderValue);
	}

	/**
	 * Warning : this header will trigger the Cross-Origin Read Blocking (CORB) protection for some mime types (HTML, XML except SVG, JSON, text/plain)
	 * In consequence some children pages will override this method.
	 *
	 * Sending header can be disabled globally using the `security.enable_header_xcontent_type_options` optional config parameter.
	 *
	 * @return void
	 * @since 2.7.10 3.0.4 3.1.2 3.2.0 N°4368 method creation
	 *
	 * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Content-Type-Options HTTP header MDN documentation
	 * @link https://chromium.googlesource.com/chromium/src/+/master/services/network/cross_origin_read_blocking_explainer.md#determining-whether-a-response-is-corb_protected "Determining whether a response is CORB-protected"
	 */
	public function add_xcontent_type_options()
	{
		try {
			$oConfig = utils::GetConfig();
		} catch (ConfigException|CoreException $e) {
			$oConfig = null;
		}
		if (is_null($oConfig)) {
			$bSendXContentTypeOptionsHttpHeader = true;
		} else {
			$bSendXContentTypeOptionsHttpHeader = $oConfig->Get('security.enable_header_xcontent_type_options');
		}

		if ($bSendXContentTypeOptionsHttpHeader === false) {
			return;
		}

		$this->add_header('X-Content-Type-Options: nosniff');
	}

	/**
	 * Add needed headers to the page so that it will no be cached
	 */
	public function no_cache()
	{
		$this->add_header('Cache-control: no-cache, no-store, must-revalidate');
		$this->add_header('Pragma: no-cache');
		$this->add_header('Expires: 0');
	}

	public function set_cache($iCacheSec)
	{
		$this->add_header("Cache-Control: max-age=$iCacheSec");
		$this->add_header("Pragma: cache");
		$this->add_header("Expires: ");
	}

	/**
	 * Build a special kind of TABLE useful for displaying the details of an object from a hash array of data
	 *
	 * @param array $aFields
	 */
	public function details($aFields)
	{
		$this->add($this->GetDetails($aFields));
	}

	/**
	 * Whether or not the page is a PDF page
	 *
	 * @return boolean
	 */
	public function is_pdf()
	{
		return false;
	}

	/**
	 * Records the current state of the 'html' part of the page output
	 *
	 * @return mixed The current state of the 'html' output
	 */
	public function start_capture()
	{
		return strlen($this->s_content);
	}

	/**
	 * Returns the part of the html output that occurred since the call to start_capture
	 * and removes this part from the current html output
	 *
	 * @param $offset mixed The value returned by start_capture
	 *
	 * @return string The part of the html output that was added since the call to start_capture
	 */
	public function end_capture($offset)
	{
		$sCaptured = substr($this->s_content, $offset);
		$this->s_content = substr($this->s_content, 0, $offset);

		return $sCaptured;
	}

	/**
	 * Build a special kind of TABLE useful for displaying the details of an object from a hash array of data
	 *
	 * @param array $aFields
	 *
	 * @return string
	 */
	public function GetDetails($aFields)
	{
		$aPossibleAttFlags = MetaModel::EnumPossibleAttributeFlags();

		$sHtml = "<div class=\"ibo-details\">\n";
		foreach ($aFields as $aAttrib)
		{
			$sLayout = isset($aAttrib['layout']) ? $aAttrib['layout'] : 'small';

			// Prepare metadata attributes
			$sDataAttributeCode = isset($aAttrib['attcode']) ? 'data-attribute-code="'.$aAttrib['attcode'].'"' : '';
			$sDataAttributeType = isset($aAttrib['atttype']) ? 'data-attribute-type="'.$aAttrib['atttype'].'"' : '';
			$sDataAttributeLabel = isset($aAttrib['attlabel']) ? 'data-attribute-label="'.utils::HtmlEntities($aAttrib['attlabel']).'"' : '';
			$sDataInputId = isset($aAttrib['inputid']) ? 'data-input-id="'.utils::HtmlEntities($aAttrib['inputid']).'"' : '';
			$sDataInputType = isset($aAttrib['inputtype']) ? 'data-input-type="'.utils::HtmlEntities($aAttrib['inputtype']).'"' : '';
			// - Attribute flags
			$sDataAttributeFlags = '';
			if(isset($aAttrib['attflags']))
			{
				foreach($aPossibleAttFlags as $sFlagCode => $iFlagValue)
				{
					// Note: Skip normal flag as we don't need it.
					if($sFlagCode === 'normal')
					{
						continue;
					}
					$sFormattedFlagCode = str_ireplace('_', '-', $sFlagCode);
					$sFormattedFlagValue = (($aAttrib['attflags'] & $iFlagValue) === $iFlagValue) ? 'true' : 'false';
					$sDataAttributeFlags .= 'data-attribute-flag-'.$sFormattedFlagCode.'="'.$sFormattedFlagValue.'" ';
				}
			}
			// - Value raw
			$sDataValueRaw = isset($aAttrib['value_raw']) ? 'data-value-raw="'.utils::HtmlEntities($aAttrib['value_raw']).'"' : '';

			$sHtml .= "<div class=\"ibo-field ibo-field-{$sLayout}\" data-role=\"ibo-field\" $sDataAttributeCode $sDataAttributeType $sDataAttributeLabel $sDataAttributeFlags $sDataValueRaw $sDataInputId $sDataInputType>\n";
			$sHtml .= "<div class=\"ibo-field--label\">{$aAttrib['label']}</div>\n";

			// By Rom, for csv import, proposed to show several values for column selection
			if (is_array($aAttrib['value']))
			{
				$sHtml .= "<div class=\"ibo-field--value\">".implode("</div><div>", $aAttrib['value'])."</div>\n";
			}
			else
			{
				$sHtml .= "<div class=\"ibo-field--value\">".$aAttrib['value']."</div>\n";
			}
			// Checking if we should add comments & infos
			$sComment = (isset($aAttrib['comments'])) ? $aAttrib['comments'] : '';
			$sInfo = (isset($aAttrib['infos'])) ? $aAttrib['infos'] : '';
			if ($sComment !== '')
			{
				$sHtml .= "<div class=\"ibo-field--comments\">$sComment</div>\n";
			}
			if ($sInfo !== '')
			{
				$sHtml .= "<div class=\"ibo-field--infos\">$sInfo</div>\n";
			}

			$sHtml .= "</div>\n";
		}
		$sHtml .= "</div>\n";

		return $sHtml;
	}

	/**
	 * Build a set of radio buttons suitable for editing a field/attribute of an object (including its validation)
	 *
	 * @param $aAllowedValues array Array of value => display_value
	 * @param $value mixed Current value for the field/attribute
	 * @param $iId mixed Unique Id for the input control in the page
	 * @param $sFieldName string The name of the field, attr_<$sFieldName> will hold the value for the field
	 * @param $bMandatory bool Whether or not the field is mandatory
	 * @param $bVertical bool Disposition of the radio buttons vertical or horizontal
	 * @param $sValidationField string HTML fragment holding the validation field (exclamation icon...)
	 *
	 * @return string The HTML fragment corresponding to the radio buttons
	 */
	public function GetRadioButtons(
		$aAllowedValues, $value, $iId, $sFieldName, $bMandatory, $bVertical, $sValidationField
	) {
		$idx = 0;
		$sHTMLValue = '';
		foreach ($aAllowedValues as $key => $display_value)
		{
			if ((count($aAllowedValues) == 1) && ($bMandatory == 'true'))
			{
				// When there is only once choice, select it by default
				$sSelected = 'checked';
			}
			else
			{
				$sSelected = ($value == $key) ? 'checked' : '';
			}
			$sHTMLValue .= "<input type=\"radio\" id=\"{$iId}_{$key}\" name=\"radio_$sFieldName\" onChange=\"$('#{$iId}').val(this.value).trigger('change');\" value=\"$key\" $sSelected><label class=\"radio\" for=\"{$iId}_{$key}\">&nbsp;$display_value</label>&nbsp;";
			if ($bVertical)
			{
				if ($idx == 0)
				{
					// Validation icon at the end of the first line
					$sHTMLValue .= "&nbsp;{$sValidationField}\n";
				}
				$sHTMLValue .= "<br>\n";
			}
			$idx++;
		}
		$sHTMLValue .= "<input type=\"hidden\" id=\"$iId\" name=\"$sFieldName\" value=\"$value\"/>";
		if (!$bVertical)
		{
			// Validation icon at the end of the line
			$sHTMLValue .= "&nbsp;{$sValidationField}\n";
		}

		return $sHTMLValue;
	}

	/**
	 * Discard unexpected output data (such as PHP warnings)
	 * This is a MUST when the Page output is DATA (download of a document, download CSV export, download ...)
	 */
	public function TrashUnexpectedOutput()
	{
		$this->bTrashUnexpectedOutput = true;
	}

	/**
	 * Read the output buffer and deal with its contents:
	 * - trash unexpected output if the flag has been set
	 * - report unexpected behaviors such as the output buffering being stopped
	 *
	 * Possible improvement: I've noticed that several output buffers are stacked,
	 * if they are not empty, the output will be corrupted. The solution would
	 * consist in unstacking all of them (and concatenate the contents).
	 *
	 * @throws \Exception
	 */
	protected function ob_get_clean_safe()
	{
		$sOutput = ob_get_contents();
		if ($sOutput === false)
		{
			$sMsg = "Design/integration issue: No output buffer. Some piece of code has called ob_get_clean() or ob_end_clean() without calling ob_start()";
			if ($this->bTrashUnexpectedOutput)
			{
				IssueLog::Error($sMsg);
				$sOutput = '';
			}
			else
			{
				$sOutput = $sMsg;
			}
		}
		else
		{
			ob_end_clean(); // on some versions of PHP doing so when the output buffering is stopped can cause a notice
			if ($this->bTrashUnexpectedOutput)
			{
				if (trim($sOutput) != '')
				{
					if (Utils::GetConfig() && Utils::GetConfig()->Get('debug_report_spurious_chars')) {
						IssueLog::Error("Trashing unexpected output:'$sOutput'\n");
					}
				}
				$sOutput = '';
			}
		}

		return $sOutput;
	}

	public function GetDeferredBlocks(iUIBlock $oBlock): array
	{
		$aDeferredBlocks = $oBlock->GetDeferredBlocks();

		foreach ($oBlock->GetSubBlocks() as $oSubBlock) {
			$aDeferredBlocks = array_merge($aDeferredBlocks, $this->GetDeferredBlocks($oSubBlock));
		}

		return $aDeferredBlocks;
	}

	/**
	 * @inheritDoc
	 * @throws \Exception
	 * @since 3.2.0 Prefer using {@see static::GenerateResponse()} in with a Symfony style controller and route
	 */
	public function output()
	{
		// Send headers
		foreach ($this->a_headers as $sHeader) {
			header($sHeader);
		}

		// Render HTML content
		$sHtml = $this->RenderContent();

		// Echo global HTML
		$oKpi = new ExecutionKPI();
		echo $sHtml;
		$oKpi->ComputeAndReport('Echoing ('.round(strlen($sHtml) / 1024).' KB)');

		if (class_exists('DBSearch')) {
			DBSearch::RecordQueryTrace();
		}
		ExecutionKPI::ReportStats();
	}

	/**
	 * @return \Symfony\Component\HttpFoundation\Response Generate the Symfony Response object (content + code + headers) for the current page, this is equivalent (and new way) of \WebPage::output() for Symfony controllers
	 * @since 3.2.0 N°6935
	 */
	public function GenerateResponse(): Response
	{
		// Render HTML content
		$sHtml = $this->RenderContent();

		// Prepare Symfony Response
		$oKpi = new ExecutionKPI();

		// Format headers as a key => value array to match Symfony Response format
		$aHeadersAsAssocArray = [];
		foreach ($this->a_headers as $sHeader) {
			// Limit explode to only split at the first occurrence
			list($sHeaderName, $sHeaderValue) = explode(': ', $sHeader, 2);
			$aHeadersAsAssocArray[$sHeaderName] = $sHeaderValue;
		}

		$oResponse = new Response($sHtml, Response::HTTP_OK, $aHeadersAsAssocArray);
		$oKpi->ComputeAndReport('Preparing response ('.round(strlen($sHtml) / 1024).' KB)');

		if (class_exists('DBSearch')) {
			DBSearch::RecordQueryTrace();
		}
		ExecutionKPI::ReportStats();

		return $oResponse;
	}

	/**
	 * @return string Complete HTML content of the \WebPage
	 * @throws \CoreTemplateException
	 * @throws \Twig\Error\LoaderError
	 * @since 3.2.0 N°6935
	 */
	protected function RenderContent(): string
	{
		$oKpi = new ExecutionKPI();
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
			'aPreloadedFonts'     => $this->aPreloadedFonts,
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

		$oKpi->ComputeAndReport("Rendering content (".static::class.")");

		return $sHtml;
	}

	/**
	 * Build a series of hidden field[s] from an array
	 *
	 * @param string $sLabel
	 * @param array $aData
	 */
	public function add_input_hidden($sLabel, $aData)
	{
		foreach ($aData as $sKey => $sValue) {
			// Note: protection added to protect against the Notice 'array to string conversion' that appeared with PHP 5.4
			// (this function seems unused though!)
			if (is_scalar($sValue)) {
				$this->add("<input type=\"hidden\" name=\"".$sLabel."[$sKey]\" value=\"$sValue\">");
			}
		}
	}

	/**
	 * Get an ID (for any kind of HTML tag) that is guaranteed unique in this page
	 *
	 * @return int The unique ID (in this page)
	 * @deprecated 3.0.0 use utils::GetUniqueId() instead
	 */
	public function GetUniqueId()
	{
		DeprecatedCallsLog::NotifyDeprecatedPhpMethod('use utils::GetUniqueId() instead');

		return utils::GetUniqueId();
	}

	/**
	 * Set the content-type (mime type) for the page's content
	 *
	 * @param $sContentType string
	 *
	 * @return void
	 */
	public function SetContentType(string $sContentType)
	{
		$this->sContentType = $sContentType;
	}

	/**
	 * Set the content-disposition (mime type) for the page's content
	 *
	 * @param $sDisposition string The disposition: 'inline' or 'attachment'
	 * @param $sFileName string The original name of the file
	 *
	 * @return void
	 */
	public function SetContentDisposition($sDisposition, $sFileName)
	{
		$this->sContentDisposition = $sDisposition;
		$this->sContentFileName = $sFileName;
	}

	/**
	 * Set the transactionId of the current form
	 *
	 * @param $iTransactionId integer
	 *
	 * @return void
	 */
	public function SetTransactionId($iTransactionId)
	{
		$this->iTransactionId = $iTransactionId;
	}

	/**
	 * Returns the transactionId of the current form
	 *
	 * @return integer The current transactionID
	 */
	public function GetTransactionId()
	{
		return $this->iTransactionId;
	}

	/**
	 * What is the currently selected output format
	 *
	 * @return string The selected output format: html, pdf...
	 */
	public function GetOutputFormat()
	{
		return $this->s_OutputFormat;
	}

	/**
	 * Check whether the desired output format is possible or not
	 *
	 * @param string $sOutputFormat The desired output format: html, pdf...
	 *
	 * @return bool True if the format is Ok, false otherwise
	 */
	function IsOutputFormatAvailable($sOutputFormat)
	{
		$bResult = false;
		switch ($sOutputFormat)
		{
			case 'html':
				$bResult = true; // Always supported
				break;

			case 'pdf':
				$bResult = @is_readable(APPROOT.'lib/MPDF/mpdf.php');
				break;
		}

		return $bResult;
	}

	/**
	 * Check whether the output must be printable (using print.css, for sure!)
	 *
	 * @return bool ...
	 */
	public function IsPrintableVersion()
	{
		return $this->bPrintable;
	}

	/**
	 * Retrieves the value of a named output option for the given format
	 *
	 * @param string $sFormat The format: html or pdf
	 * @param string $sOptionName The name of the option
	 *
	 * @return mixed false if the option was never set or the options's value
	 */
	public function GetOutputOption($sFormat, $sOptionName)
	{
		if (isset($this->a_OutputOptions[$sFormat][$sOptionName]))
		{
			return $this->a_OutputOptions[$sFormat][$sOptionName];
		}

		return false;
	}

	/**
	 * Sets a named output option for the given format
	 *
	 * @param string $sFormat The format for which to set the option: html or pdf
	 * @param string $sOptionName the name of the option
	 * @param mixed $sValue The value of the option
	 */
	public function SetOutputOption($sFormat, $sOptionName, $sValue)
	{
		if (!isset($this->a_OutputOptions[$sFormat]))
		{
			$this->a_OutputOptions[$sFormat] = array($sOptionName => $sValue);
		}
		else
		{
			$this->a_OutputOptions[$sFormat][$sOptionName] = $sValue;
		}
	}

	/**
	 * @param array $aActions
	 * @param array $aFavoriteActions
	 *
	 * @return string
	 */
	public function RenderPopupMenuItems($aActions, $aFavoriteActions = array())
	{
		$sPrevUrl = '';
		$sHtml = '';
		if (!$this->IsPrintableVersion()) {
			foreach ($aActions as $sActionId => $aAction) {
				$sDataActionId = 'data-action-id="'.$sActionId.'"';
				$sClass = isset($aAction['css_classes']) ? 'class="'.implode(' ', $aAction['css_classes']).'"' : '';
				$sOnClick = isset($aAction['onclick']) ? 'onclick="'.htmlspecialchars($aAction['onclick'], ENT_QUOTES,
						"UTF-8").'"' : '';
				$sTarget = isset($aAction['target']) ? "target=\"{$aAction['target']}\"" : "";
				if (empty($aAction['url'])) {
					if ($sPrevUrl != '') // Don't output consecutively two separators...
					{
						$sHtml .= "<li $sDataActionId>{$aAction['label']}</li>";
					}
					$sPrevUrl = '';
				} else {
					$sHtml .= "<li $sDataActionId><a $sTarget href=\"{$aAction['url']}\" $sClass $sOnClick>{$aAction['label']}</a></li>";
					$sPrevUrl = $aAction['url'];
				}
			}
			$sHtml .= "</ul></li></ul></div>";
			foreach (array_reverse($aFavoriteActions) as $sActionId => $aAction) {
				$sTarget = isset($aAction['target']) ? " target=\"{$aAction['target']}\"" : "";
				$sHtml .= "<div class=\"actions_button\" data-action-id=\"$sActionId\"><a $sTarget href='{$aAction['url']}'>{$aAction['label']}</a></div>";
			}
		}

		return $sHtml;
	}

	/**
	 * @param string $sId
	 * @param array $aActions
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenu|null
	 */
	public function GetPopoverMenu(string $sId, array $aActions): ?PopoverMenu
	{
		if ($this->IsPrintableVersion()) {
			return null;
		}

		return PopoverMenuFactory::MakeMenuForActions($sId, $aActions);
	}

	/**
	 * @param bool $bReturnOutput
	 *
	 * @throws \Exception
	 */
	protected function output_dict_entries($bReturnOutput = false)
	{
		if ($this->sContentType != 'text/plain' && $this->sContentType != 'application/json' && $this->sContentType != 'application/javascript') {
			/** @var \iBackofficeDictEntriesExtension $oExtensionInstance */
			foreach (MetaModel::EnumPlugins('iBackofficeDictEntriesExtension') as $oExtensionInstance) {
				foreach ($oExtensionInstance->GetDictEntries() as $sDictEntry) {
					$this->add_dict_entry($sDictEntry);
				}
			}
			/** @var \iBackofficeDictEntriesPrefixesExtension $oExtensionInstance */
			foreach (MetaModel::EnumPlugins('iBackofficeDictEntriesPrefixesExtension') as $oExtensionInstance) {
				foreach ($oExtensionInstance->GetDictEntriesPrefixes() as $sDictEntryPrefix) {
					$this->add_dict_entries($sDictEntryPrefix);
				}
			}
			if ((count($this->a_dict_entries) > 0) || (count($this->a_dict_entries_prefixes) > 0)) {
				if (class_exists('Dict')) {
					// The dictionary may not be available for example during the setup...
					// Create a specific dictionary file and load it as a JS script
					$sSignature = $this->get_dict_signature();
					$sJSFileName = utils::GetCachePath().$sSignature.'.js';
					if (!file_exists($sJSFileName) && is_writable(utils::GetCachePath())) {
						file_put_contents($sJSFileName, $this->get_dict_file_content());
					}
					// Load the dictionary as the first javascript file, so that other JS file benefit from the translations
					array_unshift($this->a_linked_scripts, utils::GetAbsoluteUrlAppRoot().'pages/ajax.document.php?operation=dict&s='.$sSignature);
				}
			}
		}
	}


	/**
	 * @deprecated 3.0.0 use {@link \Combodo\iTop\Application\UI\Base\Component\CollapsibleSection\CollapsibleSection}
	 *
	 * Adds init scripts for the collapsible sections
	 */
	protected function outputCollapsibleSectionInit()
	{
		if (!$this->bHasCollapsibleSection) {
			return;
		}

		$this->add_script(<<<'EOD'
function initCollapsibleSection(iSectionId, bOpenedByDefault, sSectionStateStorageKey)
{
var bStoredSectionState = JSON.parse(localStorage.getItem(sSectionStateStorageKey));
var bIsSectionOpenedInitially = (bStoredSectionState == null) ? bOpenedByDefault : bStoredSectionState;

if (bIsSectionOpenedInitially) {
	$("#LnkCollapse_"+iSectionId).toggleClass("open");
	$("#Collapse_"+iSectionId).toggle();
}

$("#LnkCollapse_"+iSectionId).on('click', function(e) {
	localStorage.setItem(sSectionStateStorageKey, !($("#Collapse_"+iSectionId).is(":visible")));
	$("#LnkCollapse_"+iSectionId).toggleClass("open");
	$("#Collapse_"+iSectionId).slideToggle("normal");
	e.preventDefault(); // we don't want to do anything more (see #1030 : a non wanted tab switching was triggered)
});
}
EOD
		);
	}

	/**
	 * @deprecated 3.0.0 use {@link \Combodo\iTop\Application\UI\Base\Component\CollapsibleSection\CollapsibleSection}
	 *
	 * @param bool $bOpenedByDefault
	 * @param string $sSectionStateStorageBusinessKey
	 *
	 * @param string $sSectionLabel
	 *
	 * @throws \Exception
	 */
	public function StartCollapsibleSection($sSectionLabel, $bOpenedByDefault = false, $sSectionStateStorageBusinessKey = '')
	{
		DeprecatedCallsLog::NotifyDeprecatedPhpMethod('use \\Combodo\\iTop\\Application\\UI\\Base\\Component\\CollapsibleSection\\CollapsibleSection');
		$this->add($this->GetStartCollapsibleSection($sSectionLabel, $bOpenedByDefault, $sSectionStateStorageBusinessKey));
	}

	/**
	 * @deprecated 3.0.0 use {@link \Combodo\iTop\Application\UI\Base\Component\CollapsibleSection\CollapsibleSection}
	 *
	 * @param string $sSectionLabel
	 * @param bool $bOpenedByDefault
	 * @param string $sSectionStateStorageBusinessKey
	 *
	 * @return string
	 *
	 * @throws \Exception
	 */
	public function GetStartCollapsibleSection($sSectionLabel, $bOpenedByDefault = false, $sSectionStateStorageBusinessKey = '')
	{
		$this->bHasCollapsibleSection = true;
		$sHtml = '';
		static $iSectionId = 0;
		$sHtml .= '<a id="LnkCollapse_'.$iSectionId.'" class="CollapsibleLabel" href="#">'.$sSectionLabel.'</a></br>'."\n";
		$sHtml .= '<div id="Collapse_'.$iSectionId.'" style="display:none">'."\n";

		$oConfig = MetaModel::GetConfig();
		$sSectionStateStorageKey = $oConfig->GetItopInstanceid().'/'.$sSectionStateStorageBusinessKey.'/collapsible-'.$iSectionId;
		$sSectionStateStorageKey = json_encode($sSectionStateStorageKey);
		$sOpenedByDefault = ($bOpenedByDefault) ? 'true' : 'false';
		$this->add_ready_script("initCollapsibleSection($iSectionId, $sOpenedByDefault, '$sSectionStateStorageKey');");

		$iSectionId++;

		return $sHtml;
	}

	/**
	 * @deprecated 3.0.0 use {@link \Combodo\iTop\Application\UI\Base\Component\CollapsibleSection\CollapsibleSection}
	 */
	public function EndCollapsibleSection()
	{
		$this->add($this->GetEndCollapsibleSection());
	}

	/**
	 * @deprecated 3.0.0 use {@link \Combodo\iTop\Application\UI\Base\Component\CollapsibleSection\CollapsibleSection}
	 *
	 * @return string
	 */
	public function GetEndCollapsibleSection()
	{
		return "</div>";
	}

	/**
	 * Return the language for the page metadata based on the current user
	 *
	 * @return string
	 * @since 3.0.0
	 */
	protected function GetLanguageForMetadata()
	{
		$sUserLang = UserRights::GetUserLanguage();

		return strtolower(substr($sUserLang, 0, 2));
	}

	/**
	 * @return string Absolute URL for the favicon
	 * @throws \Exception
	 * @since 3.0.0
	 */
	protected function GetFaviconAbsoluteUrl()
	{
		return Branding::GetMainFavIconAbsoluteUrl();
	}

	/**
	 * Set the template path to use for the page
	 *
	 * @param string $sTemplateRelPath Relative path (from <ITOP>/templates/) to the template path
	 *
	 * @return $this
	 * @since 3.0.0
	 */
	public function SetTemplateRelPath($sTemplateRelPath)
	{
		$this->sTemplateRelPath = $sTemplateRelPath;
		return $this;
	}

	/**
	 * Return the relative path (from <ITOP>/templates/) to the page template
	 *
	 * @return string
	 * @since 3.0.0
	 */
	public function GetTemplateRelPath()
	{
		return $this->sTemplateRelPath;
	}

	/**
	 *
	 * @see static::$aBlockParams
	 *
	 * @param string $sKey
	 * @param $value
	 *
	 * @return $this
	 * @since 3.0.0
	 */
	public function SetBlockParam(string $sKey, $value)
	{
		$this->aBlockParams[$sKey] = $value;

		return $this;
	}

	/**
	 * @see static::$aBlockParams
	 * @return array
	 * @since 3.0.0
	 */
	public function GetBlockParams(): array
	{
		return $this->aBlockParams;
	}

	/**
	 * @param array $aFonts
	 *
	 * @since 3.0.0
	 */
	public function AddPreloadedFonts(array $aFonts)
	{
		$this->aPreloadedFonts = array_merge($this->aPreloadedFonts, $aFonts);
	}

	/**
	 * @return bool
	 *
	 * @since 3.2.0
	 */
	public function GetAddJSDict(): bool
	{
		return $this->bAddJSDict;
	}

	/**
	 * @param bool $bAddJSDict
	 *
	 * @return $this
	 *
	 * @since 3.2.0
	 */
	public function SetAddJSDict(bool $bAddJSDict)
	{
		$this->bAddJSDict = $bAddJSDict;
		return $this;
	}
}
