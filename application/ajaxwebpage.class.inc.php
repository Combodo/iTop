<?php
// Copyright (C) 2010-2012 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>

/**
 * Simple web page with no includes, header or fancy formatting, useful to
 * generate HTML fragments when called by an AJAX method
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

require_once(APPROOT."/application/webpage.class.inc.php");
 
class ajax_page extends WebPage
{
    /**
     * Jquery style ready script
     * @var Hash     
     */	  
	protected $m_sReadyScript;
	protected $m_sCurrentTab;
	protected $m_sCurrentTabContainer;
	protected $m_aTabs;
	private $m_sMenu; // If set, then the menu will be updated
	
    /**
     * constructor for the web page
     * @param string $s_title Not used
     */	  
	function __construct($s_title)
    {
        parent::__construct($s_title);
        $this->m_sReadyScript = "";
		//$this->add_header("Content-type: text/html; charset=utf-8");
		$this->add_header("Cache-control: no-cache");
		$this->m_sCurrentTabContainer = '';
        $this->m_sCurrentTab = '';
		$this->m_aTabs = array();
		$this->sContentType = 'text/html';
		$this->sContentDisposition = 'inline';
		$this->m_sMenu = "";
    }	

	public function AddTabContainer($sTabContainer, $sPrefix = '')
	{
		$this->m_aTabs[$sTabContainer] = array('content' =>'', 'prefix' => $sPrefix);
		$this->add("\$Tabs:$sTabContainer\$");
	}
	
	public function AddToTab($sTabContainer, $sTabLabel, $sHtml)
	{
		if (!isset($this->m_aTabs[$sTabContainer]['content'][$sTabLabel]))
		{
			// Set the content of the tab
			$this->m_aTabs[$sTabContainer]['content'][$sTabLabel] = $sHtml;
		}
		else
		{
			// Append to the content of the tab
			$this->m_aTabs[$sTabContainer]['content'][$sTabLabel] .= $sHtml;
		}
	}

	public function SetCurrentTabContainer($sTabContainer = '')
	{
		$sPreviousTabContainer = $this->m_sCurrentTabContainer;
		$this->m_sCurrentTabContainer = $sTabContainer;
		return $sPreviousTabContainer;
	}

	public function SetCurrentTab($sTabLabel = '')
	{
		$sPreviousTab = $this->m_sCurrentTab;
		$this->m_sCurrentTab = $sTabLabel;
		return $sPreviousTab;
	}
	
	public function GetCurrentTab()
	{
		return $this->m_sCurrentTab;
	}
	
	public function AddToMenu($sHtml)
	{
		$this->m_sMenu .= $sHtml;
	}

    /**
     * Echoes the content of the whole page
     * @return void
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
		if (count($this->m_aTabs) > 0)
		{
			$this->add_ready_script(
<<<EOF
			// The "tab widgets" to handle.
			var tabs = $('div[id^=tabbedContent]');

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
		foreach($this->m_aTabs as $sTabContainerName => $aTabContainer)
		{
			$sTabs = '';
			$m_aTabs = $aTabContainer['content'];
			$sPrefix = $aTabContainer['prefix'];
			$container_index = 0;
			if (count($m_aTabs) > 0)
			{
			  $sTabs = "<!-- tabs -->\n<div id=\"tabbedContent_{$sPrefix}{$sTabContainerName}\" class=\"light\">\n";
			  $sTabs .= "<ul>\n";
			  // Display the unordered list that will be rendered as the tabs
	          $i = 0;
			  foreach($m_aTabs as $sTabName => $sTabContent)
			  {
			      $sTabs .= "<li><a href=\"#tab_{$sPrefix}$i\" class=\"tab\"><span>".htmlentities($sTabName, ENT_QUOTES, 'UTF-8')."</span></a></li>\n";
			      $i++;
	          }
			  $sTabs .= "</ul>\n";
			  // Now add the content of the tabs themselves
			  $i = 0;
			  foreach($m_aTabs as $sTabName => $sTabContent)
			  {
			      $sTabs .= "<div id=\"tab_{$sPrefix}$i\">".$sTabContent."</div>\n";
			      $i++;
	          }
			  $sTabs .= "</div>\n<!-- end of tabs-->\n";
	        }
			$this->s_content = str_replace("\$Tabs:$sTabContainerName\$", $sTabs, $this->s_content);
			$container_index++;
		}
		
		// Additional UI widgets to be activated inside the ajax fragment ??
    	if (($this->sContentType == 'text/html') && (preg_match('/class="date-pick"/', $this->s_content) || preg_match('/class="datetime-pick"/', $this->s_content)) )
		{
			$this->add_ready_script(
<<<EOF
	$(".date-pick").datepicker({
			showOn: 'button',
			buttonImage: '../images/calendar.png',
			buttonImageOnly: true,
			dateFormat: 'yy-mm-dd',
			constrainInput: false,
			changeMonth: true,
			changeYear: true
		});
	$(".datetime-pick").datepicker({
			showOn: 'button',
			buttonImage: '../images/calendar.png',
			buttonImageOnly: true,
			dateFormat: 'yy-mm-dd 00:00:00',
			constrainInput: false,
			changeMonth: true,
			changeYear: true
		});
EOF
			);
		}	
        $s_captured_output = ob_get_contents();
        ob_end_clean();
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
	        echo "$('#accordion').accordion({ header: 'h3', navigation: true, autoHeight: false, collapsible: false, icons: false });\n";
	        echo "\n</script>\n";
        }

        //echo $this->s_deferred_content;
        if (count($this->a_scripts) > 0)
        {
            echo "<script type=\"text/javascript\">\n";
            echo implode("\n", $this->a_scripts);
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
        
		if (trim($s_captured_output) != "")
        {
        	echo self::FilterXSS($s_captured_output);
        }

        if (class_exists('MetaModel'))
        {
            MetaModel::RecordQueryTrace();
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

    public function add($sHtml)
    {
        if (!empty($this->m_sCurrentTabContainer) && !empty($this->m_sCurrentTab))
        {
            $this->AddToTab($this->m_sCurrentTabContainer, $this->m_sCurrentTab, $sHtml);
        }
        else
        {
            parent::add($sHtml);
        }
    }

	/**
	 * Records the current state of the 'html' part of the page output
	 * @return mixed The current state of the 'html' output
	 */    
    public function start_capture()
    {
        if (!empty($this->m_sCurrentTabContainer) && !empty($this->m_sCurrentTab))
        {
        	$iOffset = isset($this->m_aTabs[$this->m_sCurrentTabContainer]['content'][$this->m_sCurrentTab]) ? strlen($this->m_aTabs[$this->m_sCurrentTabContainer]['content'][$this->m_sCurrentTab]): 0;
            return array('tc' => $this->m_sCurrentTabContainer, 'tab' => $this->m_sCurrentTab, 'offset' => $iOffset);
        }
        else
        {
            return parent::start_capture();
        }
    }

    /**
     * Returns the part of the html output that occurred since the call to start_capture
     * and removes this part from the current html output
     * @param $offset mixed The value returned by start_capture
     * @return string The part of the html output that was added since the call to start_capture
     */    
    public function end_capture($offset)
    {
    	if (is_array($offset))
    	{
    		if (isset($this->m_aTabs[$offset['tc']]['content'][$offset['tab']]))
    		{
		    	$sCaptured = substr($this->m_aTabs[$offset['tc']]['content'][$offset['tab']], $offset['offset']);
		    	$this->m_aTabs[$offset['tc']]['content'][$offset['tab']] = substr($this->m_aTabs[$offset['tc']]['content'][$offset['tab']], 0, $offset['offset']);
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
	 * Add any text or HTML fragment (identified by an ID) at the end of the body of the page
	 * This is useful to add hidden content, DIVs or FORMs that should not
	 * be embedded into each other.	 	 
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
	 * Adds a script to be executed when the DOM is ready (typical JQuery use)
	 * NOT implemented in this version of the class.
	 * @return void	 
	 */	 	 	
	public function add_ready_script($sScript)
	{
		$this->m_sReadyScript .= $sScript."\n";
	}
	
	/**
	 * Cannot be called in this context, since Ajax pages do not share
	 * any context with the calling page !!
	 */
	public function GetUniqueId()
	{
		assert(false);
		return 0;
	}
	
	public static function FilterXSS($sHTML)
	{
		return str_ireplace(array('<script', '</script>'), array('<!-- <removed-script', '</removed-script> -->'), $sHTML);
	}
}

?>
