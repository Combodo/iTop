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

require_once(APPROOT."/application/webpage.class.inc.php");
 
class ajax_page extends WebPage implements iTabbedPage
{
    /**
     * Jquery style ready script
     * @var array
     */
	protected $m_sReadyScript;
	protected $m_oTabs;
	private $m_sMenu; // If set, then the menu will be updated

	/**
	 * constructor for the web page
	 *
	 * @param string $s_title Not used
	 */
	function __construct($s_title) {
		$sPrintable = utils::ReadParam('printable', '0');
		$bPrintable = ($sPrintable == '1');

		parent::__construct($s_title, $bPrintable);
		$this->m_sReadyScript = "";
		//$this->add_header("Content-type: text/html; charset=utf-8");
		$this->no_cache();
		$this->add_xframe_options();
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
	public function AddTabContainer($sTabContainer, $sPrefix = '')
	{
		$this->add($this->m_oTabs->AddTabContainer($sTabContainer, $sPrefix));
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
    	if (!empty($this->sContentType))
    	{
			$this->add_header('Content-type: '.$this->sContentType);
    	}
    	if (!empty($this->sContentDisposition))
    	{
			$this->add_header('Content-Disposition: '.$this->sContentDisposition.'; filename="'.$this->sContentFileName.'"');
    	}
        foreach($this->a_headers as $s_header)
        {
            header($s_header);
        }
		if ($this->m_oTabs->TabsContainerCount() > 0)
		{
			$this->add_ready_script(
<<<EOF
			// The "tab widgets" to handle.
			var tabs = $('div[id^=tabbedContent]');
			  
			// Ugly patch for a change in the behavior of jQuery UI:
			// Before jQuery UI 1.9, tabs were always considered as "local" (opposed to Ajax)
			// when their href was beginning by #. Starting with 1.9, a <base> tag in the page
			// is taken into account and causes "local" tabs to be considered as Ajax
			// unless their URL is equal to the URL of the page...
			if ($('base').length > 0)
			{
				$('div[id^=tabbedContent] > ul > li > a').each(function() {
					var sHash = location.hash;
					var sCleanLocation = location.href.toString().replace(sHash, '').replace(/#$/, '');
		    		$(this).attr("href", sCleanLocation+$(this).attr("href"));
				});
			}
			if ($.bbq)
			{
				// This selector will be reused when selecting actual tab widget A elements.
				var tab_a_selector = 'ul.ui-tabs-nav a';
				  
				// Enable tabs on all tab widgets. The `event` property must be overridden so
				// that the tabs aren't changed on click, and any custom event name can be
				// specified. Note that if you define a callback for the 'select' event, it
				// will be executed for the selected tab whenever the hash changes.
				tabs.tabs({ event: 'change' });
				  
				// Define our own click handler for the tabs, overriding the default.
				tabs.find( tab_a_selector ).click(function()
				{
					var state = {};
							  
					// Get the id of this tab widget.
					var id = $(this).closest( 'div[id^=tabbedContent]' ).attr( 'id' );
					  
					// Get the index of this tab.
					var idx = $(this).parent().prevAll().length;
					
					// Set the state!
					state[ id ] = idx;
					$.bbq.pushState( state );
				});
			}
			else
			{
				tabs.tabs();
			}
EOF
);
		}
		// Render the tabs in the page (if any)
		$this->s_content = $this->m_oTabs->RenderIntoContent($this->s_content, $this);
		
		// Additional UI widgets to be activated inside the ajax fragment
		// Important: Testing the content type is not enough because some ajax handlers have not correctly positionned the flag (e.g json response corrupted by the script)
		if (($this->sContentType == 'text/html') && (preg_match('/class="date-pick"/', $this->s_content) || preg_match('/class="datetime-pick"/', $this->s_content)) )
		{
			$this->add_ready_script(
<<<EOF
PrepareWidgets();
EOF
			);
		}
	    $this->outputCollapsibleSectionInit();

	    $oKPI = new ExecutionKPI();
	    $s_captured_output = $this->ob_get_clean_safe();
	    if (($this->sContentType == 'text/html') &&  ($this->sContentDisposition == 'inline'))
        {
        	// inline content != attachment && html => filter all scripts for malicious XSS scripts
        	echo self::FilterXSS($this->s_content);
        }
        else
        {
        	echo $this->s_content;
        }
        if (!empty($this->m_sMenu))
        {
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
        if (count($this->a_scripts) > 0)
        {
        	echo "<script type=\"text/javascript\">\n";
        	echo implode("\n", $this->a_scripts);
        	echo "\n</script>\n";
        }
        if (count($this->a_linked_scripts) > 0)
        {
        	echo "<script type=\"text/javascript\">\n";
        	foreach($this->a_linked_scripts as $sScriptUrl)
        	{
        		echo '$.getScript('.json_encode($sScriptUrl).");\n";
        	}
        	echo "\n</script>\n";
        }
        if (!empty($this->s_deferred_content))
        {
            echo "<script type=\"text/javascript\">\n";
            echo "\$('body').append('".addslashes(str_replace("\n", '', $this->s_deferred_content))."');\n";
            echo "\n</script>\n";
        }
        if (!empty($this->m_sReadyScript))
        {
	        echo "<script type=\"text/javascript\">\n";
	        echo $this->m_sReadyScript; // Ready Scripts are output as simple scripts
	        echo "\n</script>\n";
        }
        if(count($this->a_linked_stylesheets) > 0)
        {
        	echo "<script type=\"text/javascript\">";
        	foreach($this->a_linked_stylesheets as $aStylesheet)
	        {
		        $sStylesheetUrl = $aStylesheet['link'];
	        	echo "if (!$('link[href=\"{$sStylesheetUrl}\"]').length) $('<link href=\"{$sStylesheetUrl}\" rel=\"stylesheet\">').appendTo('head');\n";
	        }
        	echo "\n</script>\n";
        }
        
		if (trim($s_captured_output) != "")
        {
        	echo self::FilterXSS($s_captured_output);
        }

	    $oKPI->ComputeAndReport('Echoing');

        if (class_exists('DBSearch'))
        {
            DBSearch::RecordQueryTrace();
        }
	    if (class_exists('ExecutionKPI'))
	    {
		    ExecutionKPI::ReportStats();
	    }
    }

    /**
     * Adds a paragraph with a smaller font into the page
     * NOT implemented (i.e does nothing)
     * @param string $sText Content of the (small) paragraph     
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
        if (($this->m_oTabs->GetCurrentTabContainer() != '') && ($this->m_oTabs->GetCurrentTab() != ''))
        {
            $this->m_oTabs->AddToTab($this->m_oTabs->GetCurrentTabContainer(), $this->m_oTabs->GetCurrentTab(), $sHtml);
        }
        else
        {
            parent::add($sHtml);
        }
    }

	/**
	 * @inheritDoc
	 */
	public function start_capture()
    {
    	$sCurrentTabContainer = $this->m_oTabs->GetCurrentTabContainer();
		$sCurrentTab = $this->m_oTabs->GetCurrentTab();
		
		if (!empty($sCurrentTabContainer) && !empty($sCurrentTab))
		{
			$iOffset = $this->m_oTabs->GetCurrentTabLength();
			return array('tc' => $sCurrentTabContainer, 'tab' => $sCurrentTab, 'offset' => $iOffset);
		}
		else
		{
			return parent::start_capture();
		}
    }

	/**
	 * @inheritDoc
	 */
	public function end_capture($offset)
    {
		if (is_array($offset))
		{
			if ($this->m_oTabs->TabExists($offset['tc'], $offset['tab']))
			{
				$sCaptured = $this->m_oTabs->TruncateTab($offset['tc'], $offset['tab'], $offset['offset']);
			}
			else
			{
				$sCaptured = '';
			}
		}
		else
		{
			$sCaptured = parent::end_capture($offset);
		}
		return $sCaptured;
    }

	/**
	 * @inheritDoc
	 */
	public function add_at_the_end($s_html, $sId = '')
    {
    	if ($sId != '')
    	{
	    	$this->add_script("$('#{$sId}').remove();"); // Remove any previous instance of the same Id
    	}
        $this->s_deferred_content .= $s_html;
    }

	/**
	 * @inheritDoc
	 */
	public function add_ready_script($sScript)
	{
		$this->m_sReadyScript .= $sScript."\n";
	}

	/**
	 * @inheritDoc
	 */
	public function GetUniqueId()
	{
		assert(false);
		return 0;
	}

	/**
	 * @inheritDoc
	 */
	public static function FilterXSS($sHTML)
	{
		return str_ireplace(array('<script', '</script>'), array('<!-- <removed-script', '</removed-script> -->'), $sHTML);
	}
}

