<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Application\TwigBase\Twig\TwigHelper;
use Combodo\iTop\Application\UI\Base\iUIBlock;
use Combodo\iTop\Application\UI\Base\Layout\iUIContentBlock;
use Combodo\iTop\Renderer\BlockRenderer;

class AjaxPage extends WebPage implements iTabbedPage
{
	/**
	 * Jquery style ready script
	 *
	 * @var array
	 */
	protected $m_aReadyScripts;
	protected $m_oTabs;
	private $m_sMenu; // If set, then the menu will be updated
	const DEFAULT_PAGE_TEMPLATE_REL_PATH = 'pages/backoffice/ajaxpage/layout';

	/**
	 * constructor for the web page
	 *
	 * @param string $s_title Not used
	 */
	function __construct($s_title)
	{
		$sPrintable = utils::ReadParam('printable', '0');
		$bPrintable = ($sPrintable == '1');

		parent::__construct($s_title, $bPrintable);
		$this->m_aReadyScripts = [];
		//$this->add_header("Content-type: text/html; charset=utf-8");
		$this->add_header('Cache-control: no-cache, no-store, must-revalidate');
		$this->add_header('Pragma: no-cache');
		$this->add_header('Expires: 0');
		$this->add_header('X-Frame-Options: deny');
		$this->m_oTabs = new TabManager();
		$this->sContentType = 'text/html';
		$this->sContentDisposition = 'inline';
		$this->m_sMenu = "";

		utils::InitArchiveMode();
	}

	/**
	 * @inheritDoc
	 * @throws \Exception
	 */
	public function AddTabContainer($sTabContainer, $sPrefix = '', iUIContentBlock $oParentBlock = null)
	{
		$this->AddUiBlock($this->m_oTabs->AddTabContainer($sTabContainer, $sPrefix));
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
	 */
	public function SetCurrentTab($sTabCode = '', $sTabTitle = null)
	{
		return $this->m_oTabs->SetCurrentTab($sTabCode, $sTabTitle);
	}

	/**
	 * @inheritDoc
	 * @throws \Exception
	 */
	public function AddAjaxTab($sTabCode, $sUrl, $bCache = true, $sTabTitle = null)
	{
		$this->add($this->m_oTabs->AddAjaxTab($sTabCode, $sUrl, $bCache, $sTabTitle));
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
		$this->m_sMenu .= $sHtml;
	}

	/**
	 * @inheritDoc
	 */
	public function output()
	{
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

		// Render the blocks
		// Additional UI widgets to be activated inside the ajax fragment
		// Important: Testing the content type is not enough because some ajax handlers have not correctly positionned the flag (e.g json response corrupted by the script)

		// TODO 3.0.0 à revoir
		if (($this->sContentType == 'text/html') && (preg_match('/class="date-pick"/', $this->s_content) || preg_match('/class="datetime-pick"/', $this->s_content))) {
			$this->add_ready_script(
				<<<EOF
PrepareWidgets();
EOF
			);
		}
		$this->outputCollapsibleSectionInit();

		$this->RenderInlineScriptsAndCSSRecursively($this->oContentLayout);


		$aData = [];
		$aData['oLayout'] = $this->oContentLayout;
		$aData['aDeferredBlocks'] = $this->GetDeferredBlocks($this->oContentLayout);

		$aData['aPage'] = [
			'sAbsoluteUrlAppRoot' => addslashes(utils::GetAbsoluteUrlAppRoot()),
			'sTitle' => $this->s_title,
			'aMetadata' => [
				'sCharset' => static::PAGES_CHARSET,
				'sLang' => $this->GetLanguageForMetadata(),
			],
			'aCssFiles' => $this->a_linked_stylesheets,
			'aCssInline' => $this->a_styles,
			'aJsFiles' => $this->a_linked_scripts,
			'aJsInlineLive' => $this->a_scripts,
			'aJsInlineOnDomReady' => $this->m_aReadyScripts,
			'bEscapeContent' => ($this->sContentType == 'text/html') && ($this->sContentDisposition == 'inline'),
			// TODO 3.0.0: TEMP, used while developping, remove it.
			'sSanitizedContent' => utils::FilterXSS($this->s_content),
			'sDeferredContent' => utils::FilterXSS(addslashes(str_replace("\n", '', $this->s_deferred_content))),
			'sCapturedOutput' => utils::FilterXSS($s_captured_output),
		];

		$oTwigEnv = TwigHelper::GetTwigEnvironment(BlockRenderer::TWIG_BASE_PATH, BlockRenderer::TWIG_ADDITIONAL_PATHS);
		// Render final TWIG into global HTML
		$oKpi = new ExecutionKPI();
		$sHtml = TwigHelper::RenderTemplate($oTwigEnv, $aData, $this->GetTemplateRelPath());
		$oKpi->ComputeAndReport('TWIG rendering');

		// Echo global HTML
		$oKpi = new ExecutionKPI();
		echo $sHtml;
		$oKpi->ComputeAndReport('Echoing ('.round(strlen($sHtml) / 1024).' Kb)');

		return;

		/////////////////////////////////////////////////////////
		////////////////// ☢ DANGER ZONE ☢ /////////////////////
		/////////////////////////////////////////////////////////

		$oKPI = new ExecutionKPI();
		$s_captured_output = $this->ob_get_clean_safe();
		if (($this->sContentType == 'text/html') && ($this->sContentDisposition == 'inline')) {
			// inline content != attachment && html => filter all scripts for malicious XSS scripts
			echo self::FilterXSS($this->s_content);
		} else {
			echo $this->s_content;
		}

		// TODO 3.0.0 Only for designer ?
		if (!empty($this->m_sMenu)) {
			$uid = time();
			echo "<div id=\"accordion_temp_$uid\">\n";
			echo "<div id=\"accordion\">\n";
			echo "<!-- Beginning of the accordion menu -->\n";
			echo self::FilterXSS($this->m_sMenu);
			echo "<!-- End of the accordion menu-->\n";
			echo "</div>\n";
			echo "</div>\n";

			echo "<script type=\"text/javascript\">\n";
			echo "$('#inner_menu').html($('#accordion_temp_$uid').html());\n";
			echo "$('#accordion_temp_$uid').remove();\n";
			echo "\n</script>\n";
		}

		//echo $this->s_deferred_content;
		if (count($this->a_scripts) > 0) {
			echo "<script type=\"text/javascript\">\n";
			echo implode("\n", $this->a_scripts);
			echo "\n</script>\n";
		}
		if (count($this->a_linked_scripts) > 0) {
			echo "<script type=\"text/javascript\">\n";
			foreach ($this->a_linked_scripts as $sScriptUrl) {
				echo '$.getScript('.json_encode($sScriptUrl).");\n";
			}
			echo "\n</script>\n";
		}
		if (!empty($this->s_deferred_content)) {
			echo "<script type=\"text/javascript\">\n";
			echo "\$('body').append('".addslashes(str_replace("\n", '', $this->s_deferred_content))."');\n";
			echo "\n</script>\n";
		}
		if (!empty($this->m_aReadyScripts)) {
			echo "<script type=\"text/javascript\">\n";
			echo $this->m_aReadyScripts; // Ready Scripts are output as simple scripts
			echo "\n</script>\n";
		}
		if (count($this->a_linked_stylesheets) > 0) {
			echo "<script type=\"text/javascript\">";
			foreach ($this->a_linked_stylesheets as $aStylesheet) {
				$sStylesheetUrl = $aStylesheet['link'];
				echo "if (!$('link[href=\"{$sStylesheetUrl}\"]').length) $('<link href=\"{$sStylesheetUrl}\" rel=\"stylesheet\">').appendTo('head');\n";
			}
			echo "\n</script>\n";
		}

		if (trim($s_captured_output) != "") {
			echo self::FilterXSS($s_captured_output);
		}

		$oKPI->ComputeAndReport('Echoing');

		if (class_exists('DBSearch')) {
			DBSearch::RecordQueryTrace();
		}
		if (class_exists('ExecutionKPI')) {
			ExecutionKPI::ReportStats();
		}
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
	public function add($sHtml): ?iUIBlock
	{
		if (($this->m_oTabs->GetCurrentTabContainer() != '') && ($this->m_oTabs->GetCurrentTab() != '')) {
			$this->m_oTabs->AddToTab($this->m_oTabs->GetCurrentTabContainer(), $this->m_oTabs->GetCurrentTab(), $sHtml);
		} else {
			return parent::add($sHtml);
		}
		return null;
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
	public function add_ready_script($sScript)
	{
		if (!empty(trim($sScript))) {
			$this->m_aReadyScripts[] = $sScript;
		}
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
