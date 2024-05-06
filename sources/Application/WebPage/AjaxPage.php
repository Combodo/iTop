<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\WebPage;

use Combodo\iTop\Application\TwigBase\Twig\TwigHelper;
use Combodo\iTop\Application\UI\Base\Component\Panel\PanelUIBlockFactory;
use Combodo\iTop\Application\UI\Base\iUIBlock;
use Combodo\iTop\Application\UI\Base\Layout\iUIContentBlock;
use Combodo\iTop\Renderer\BlockRenderer;
use Combodo\iTop\Renderer\Console\ConsoleBlockRenderer;
use DeprecatedCallsLog;
use ExecutionKPI;
use utils;

class AjaxPage extends WebPage implements iTabbedPage
{
	/**
	 * Jquery style ready script
	 *
	 * @var array
	 */
	protected $m_oTabs;
	private $m_sMenu; // If set, then the menu will be updated

	const DEFAULT_PAGE_TEMPLATE_REL_PATH = 'pages/backoffice/ajaxpage/layout';
	/** @var string  */
	private $sPromiseId;

	/**
	 * @var bool if false will also output extra JS & CSS
	 * @since 3.0.1 3.1.0 N°4836 Introduce this new option to AjaxPage, as sometimes we only need to return a simple string
	 */
	protected $bOutputDataOnly = false;

	/**
	 * constructor for the web page
	 *
	 * @param string $s_title Not used
	 * @param bool $bOutputExtraResources if true will output also JS & CSS resources
	 */
	function __construct($s_title)
	{
		$oKpi = new ExecutionKPI();
		$sPrintable = utils::ReadParam('printable', '0');
		$bPrintable = ($sPrintable == '1');

		parent::__construct($s_title, $bPrintable);
		//$this->add_header("Content-type: text/html; charset=utf-8");
		$this->no_cache();
		$this->add_http_headers();
		$this->m_oTabs = new TabManager();
		$this->sContentType = 'text/html';
		$this->sContentDisposition = 'inline';
		$this->m_sMenu = "";
		$this->sPromiseId = utils::ReadParam('ajax_promise_id', uniqid('ajax_', true));

		utils::InitArchiveMode();
		$oKpi->ComputeStats(get_class($this).' creation', 'AjaxPage');
	}

	/**
	 * Disabling sending the header so that resource won't be blocked by CORB. See parent method documentation.
	 * @return void
	 * @since 2.7.10 3.0.4 3.1.2 3.2.0 N°4368 method creation
	 */
	public function add_xcontent_type_options()
	{
		// Nothing to do !
	}

	/**
	 * @see static::$bOutputDataOnly
	 * @param bool $bFlag
	 *
	 * @return $this
	 *
	 * @since 3.0.1 3.1.0 N°4836 Method creation : sometimes we only want to output a simple string
	 */
	public function SetOutputDataOnly(bool $bFlag)
	{
		$this->bOutputDataOnly = $bFlag;

		return $this;
	}

	/**
	 * @inheritDoc
	 * @throws \Exception
	 */
	public function AddTabContainer($sTabContainer, $sPrefix = '', iUIContentBlock $oParentBlock = null)
	{
		if (is_null($oParentBlock)) {
			$oParentBlock = PanelUIBlockFactory::MakeNeutral('');
			$this->AddUiBlock($oParentBlock);
		}

		$oParentBlock->AddSubBlock($this->m_oTabs->AddTabContainer($sTabContainer, $sPrefix));
	}

	/**
	 * @inheritDoc
	 * @throws \Exception
	 */
	public function AddToTab($sTabContainer, $sTabCode, $sHtml)
	{
		$this->add($this->m_oTabs->AddToTab($sTabContainer, $sTabCode, $sHtml));
	}

	/**
	 * @inheritDoc
	 */
	public function SetCurrentTabContainer($sTabContainer = '')
	{
		return $this->m_oTabs->SetCurrentTabContainer($sTabContainer);
	}

	/**
	 * @inheritDoc
	 *
	 * @param string|null $sTabDescription {@see \Combodo\iTop\Application\UI\Base\Layout\TabContainer\Tab\Tab::$sDescription}
	 * @since 3.1.0 N°5920 Add $sTabDescription argument
	 */
	public function SetCurrentTab($sTabCode = '', $sTabTitle = null, ?string $sTabDescription = null)
	{
		return $this->m_oTabs->SetCurrentTab($sTabCode, $sTabTitle, $sTabDescription);
	}

	/**
	 * @inheritDoc
	 * @throws \Exception
	 */
	public function AddAjaxTab($sTabCode, $sUrl, $bCache = true, $sTabTitle = null, $sPlaceholder = null)
	{
		$this->add($this->m_oTabs->AddAjaxTab($sTabCode, $sUrl, $bCache, $sTabTitle, $sPlaceholder));
	}

	/**
	 * @inheritDoc
	 */
	public function GetCurrentTab()
	{
		return $this->m_oTabs->GetCurrentTab();
	}

	/**
	 * @inheritDoc
	 */
	public function RemoveTab($sTabCode, $sTabContainer = null)
	{
		$this->m_oTabs->RemoveTab($sTabCode, $sTabContainer);
	}

	/**
	 * @inheritDoc
	 */
	public function FindTab($sPattern, $sTabContainer = null)
	{
		return $this->m_oTabs->FindTab($sPattern, $sTabContainer);
	}

	/**
	 * Make the given tab the active one, as if it were clicked
	 * DOES NOT WORK: apparently in the *old* version of jquery
	 * that we are using this is not supported... TO DO upgrade
	 * the whole jquery bundle...
	 */
	public function SelectTab($sTabContainer, $sTabCode)
	{
		$this->add_ready_script($this->m_oTabs->SelectTab($sTabContainer, $sTabCode));
	}

	/**
	 * @param string $sHtml
	 *
	 * @deprecated Will be removed in 3.0.0
	 */
	public function AddToMenu($sHtml)
	{
		DeprecatedCallsLog::NotifyDeprecatedPhpMethod();
		$this->m_sMenu .= $sHtml;
	}

	/**
	 * @inheritDoc
	 */
	public function output()
	{
		$oKpi = new ExecutionKPI();
		$s_captured_output = $this->ob_get_clean_safe();

		if (!empty($this->sContentType)) {
			$this->add_header('Content-type: '.$this->sContentType);
		}
		if (!empty($this->sContentDisposition)) {
			$this->add_header('Content-Disposition: '.$this->sContentDisposition.'; filename="'.$this->sContentFileName.'"');
		}
		foreach ($this->a_headers as $s_header) {
			header($s_header);
		}

		if (false === $this->bOutputDataOnly) {
			// Prepare internal parts (js files, css files, js snippets, css snippets, ...)
			// - Generate necessary dict. files
			if ($this->bAddJSDict) {
				$this->output_dict_entries();
			}

			ConsoleBlockRenderer::AddCssJsToPage($this, $this->oContentLayout);

			$this->outputCollapsibleSectionInit();
		}

		// Render the blocks
		$aData = [];
		$aData['oLayout'] = $this->oContentLayout;
		$aData['aDeferredBlocks'] = $this->GetDeferredBlocks($this->oContentLayout);

		$aData['aPage'] = [
			'sAbsoluteUrlAppRoot' => addslashes(utils::GetAbsoluteUrlAppRoot()),
			'sTitle'              => $this->s_title,
			'aMetadata'           => [
				'sCharset' => static::PAGES_CHARSET,
				'sLang' => $this->GetLanguageForMetadata(),
			],
			'aCssFiles'           => $this->a_linked_stylesheets,
			'aCssInline'          => $this->a_styles,
			'aJsFiles'            => $this->a_linked_scripts,
			'aJsInlineLive'       => $this->a_scripts,
			'aJsInlineOnDomReady' => $this->GetReadyScripts(),
			'aJsInlineOnInit'     => $this->a_init_scripts,
			'bEscapeContent'      => ($this->sContentType == 'text/html') && ($this->sContentDisposition == 'inline'),
			// TODO 3.0.0: TEMP, used while developping, remove it.
			'sSanitizedContent'   => utils::FilterXSS($this->s_content),
			'sDeferredContent'    => utils::FilterXSS(addslashes(str_replace("\n", '', $this->s_deferred_content))),
			'sCapturedOutput'     => utils::FilterXSS($s_captured_output),
			'sPromiseId'          => $this->sPromiseId,
		];

		$aData['aBlockParams'] = $this->GetBlockParams();

		$oTwigEnv = TwigHelper::GetTwigEnvironment(BlockRenderer::TWIG_BASE_PATH, BlockRenderer::TWIG_ADDITIONAL_PATHS);
		// Render final TWIG into global HTML
		$sHtml = TwigHelper::RenderTemplate($oTwigEnv, $aData, $this->GetTemplateRelPath());
		$oKpi->ComputeAndReport(get_class($this).' output');

		// Echo global HTML
		echo $sHtml;
		$oKpi->ComputeAndReport('Echoing ('.round(strlen($sHtml) / 1024).' Kb)');
		ExecutionKPI::ReportStats();
	}

	/**
	 * Adds a paragraph with a smaller font into the page
	 * NOT implemented (i.e does nothing)
	 *
	 * @param string $sText Content of the (small) paragraph
	 *
	 * @return void
	 */
	public function small_p($sText)
	{
	}

	/**
	 * @inheritDoc
	 * @throws \Exception
	 */
	public function add($sHtml)
	{
		if (($this->m_oTabs->GetCurrentTabContainer() != '') && ($this->m_oTabs->GetCurrentTab() != '')) {
			$this->m_oTabs->AddToTab($this->m_oTabs->GetCurrentTabContainer(), $this->m_oTabs->GetCurrentTab(), $sHtml);
		} else {
			parent::add($sHtml);
		}
	}

	/**
	 * @inheritDoc
	 */
	public function AddUiBlock(?iUIBlock $oBlock): ?iUIBlock
	{
		if (is_null($oBlock)) {
			return null;
		}
		if (($this->m_oTabs->GetCurrentTabContainer() != '') && ($this->m_oTabs->GetCurrentTab() != '')) {
			return $this->m_oTabs->AddUIBlockToCurrentTab($oBlock);
		}

		return parent::AddUiBlock($oBlock);
	}

	/**
	 * @inheritDoc
	 */
	public function start_capture()
	{
		$sCurrentTabContainer = $this->m_oTabs->GetCurrentTabContainer();
		$sCurrentTab = $this->m_oTabs->GetCurrentTab();

		if (!empty($sCurrentTabContainer) && !empty($sCurrentTab)) {
			$iOffset = $this->m_oTabs->GetCurrentTabLength();
			return array('tc' => $sCurrentTabContainer, 'tab' => $sCurrentTab, 'offset' => $iOffset);
		} else {
			return parent::start_capture();
		}
	}

	/**
	 * @inheritDoc
	 */
	public function end_capture($offset)
	{
		if (is_array($offset)) {
			if ($this->m_oTabs->TabExists($offset['tc'], $offset['tab'])) {
				$sCaptured = $this->m_oTabs->TruncateTab($offset['tc'], $offset['tab'], $offset['offset']);
			} else {
				$sCaptured = '';
			}
		} else {
			$sCaptured = parent::end_capture($offset);
		}
		return $sCaptured;
	}

	/**
	 * @inheritDoc
	 */
	public function add_at_the_end($s_html, $sId = null)
	{
		if ($sId != '') {
			$this->add_script("$('#{$sId}').remove();"); // Remove any previous instance of the same Id
		}
		$this->s_deferred_content .= $s_html;
	}

	/**
	 * @inheritDoc
	 */
	public function GetUniqueId()
	{
		assert(false);
	}
	
	/**
	 * @inheritDoc
	 */
	public static function FilterXSS($sHTML)
	{
		return str_ireplace(array('<script', '</script>'), array('<!-- <removed-script', '</removed-script> -->'), $sHTML);
	}
}
