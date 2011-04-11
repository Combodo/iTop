<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * Simple web page with no includes, header or fancy formatting, useful to
 * generate HTML fragments when called by an AJAX method
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
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
	
    /**
     * constructor for the web page
     * @param string $s_title Not used
     */	  
	function __construct($s_title)
    {
        parent::__construct($s_title);
        $this->m_sReadyScript = "";
		$this->add_header("Content-type: text/html; charset=utf-8");
		$this->add_header("Cache-control: no-cache");
		$this->m_sCurrentTabContainer = '';
        $this->m_sCurrentTab = '';
		$this->m_aTabs = array();
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
	
    /**
     * Echoes the content of the whole page
     * @return void
     */	  
    public function output()
    {
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
			  $sTabs = "<!-- tabs -->\n<div id=\"tabbedContent_{$sPrefix}{$container_index}\" class=\"light\">\n";
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
	
        $s_captured_output = ob_get_contents();
        ob_end_clean();
        echo $this->s_content;
        echo $this->s_deferred_content;
        if (count($this->a_scripts) > 0)
        {
            echo "<script type=\"text/javascript\">\n";
            echo implode("\n", $this->a_scripts);
            echo "\n</script>\n";
        }
        if (!empty($this->s_deferred_content))
        {
            echo "<script type=\"text/javascript\">\n";
            echo "\$('body').append('".$this->s_deferred_content."');\n";
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
            echo $s_captured_output;
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
	 * Adds a script to be executed when the DOM is ready (typical JQuery use)
	 * NOT implemented in this version of the class.
	 * @return void	 
	 */	 	 	
	public function add_ready_script($sScript)
	{
		// Does nothing in ajax rendered content.. for now...
		// Maybe we should add this as a simple <script> tag at the end of the output
		// considering that at this time everything in the page is "ready"...
		$this->m_sReadyScript .= $sScript;
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
	
}

?>
