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

require_once("../application/webpage.class.inc.php");
 
class ajax_page extends WebPage
{
    /**
     * Jquery style ready script
     * @var Hash     
     */	  
	protected $m_sReadyScript;
	
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
        $s_captured_output = ob_get_contents();
        ob_end_clean();
        echo $this->s_content;
        echo $this->s_deferred_content;
        if (!empty($this->m_sReadyScript))
        {
	        echo "<script>\n";
	        echo $this->m_sReadyScript; // Ready Scripts are output as simple scripts
	        echo "</script>\n";
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
