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
 * Class XMLPage
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

require_once(APPROOT."/application/webpage.class.inc.php");
/**
 * Simple web page with no includes or fancy formatting, useful to generateXML documents
 * The page adds the content-type text/XML and the encoding into the headers
 */
class XMLPage extends WebPage
{
	/**
	 * For big XML files, it's better NOT to store everything in memory and output the XML piece by piece
	 */
	var $m_bPassThrough;
	var $m_bHeaderSent;
	
    function __construct($s_title, $bPassThrough = false)
    {
        parent::__construct($s_title);
        $this->m_bPassThrough = $bPassThrough;
        $this->m_bHeaderSent = false;
        $this->add_header("Content-type: text/xml; charset=utf-8");
		$this->add_header("Cache-control: no-cache");
		$this->add_header("Content-location: export.xml");
    }	

    public function output()
    {
    	if (!$this->m_bPassThrough)
    	{
			$this->add("<?xml version=\"1.0\" encoding=\"UTF-8\"?".">\n");
			$this->add_header("Content-Length: ".strlen(trim($this->s_content)));
	        foreach($this->a_headers as $s_header)
	        {
	            header($s_header);
	        }
	        echo trim($this->s_content);
    	}
    }
    
    public function add($sText)
    {
    	if (!$this->m_bPassThrough)
    	{
    		parent::add($sText);
    	}
    	else
    	{
    		if ($this->m_bHeaderSent)
    		{
		   		echo $sText;
    		}
    		else
    		{
		        $s_captured_output = ob_get_contents();
		        ob_end_clean();
		        foreach($this->a_headers as $s_header)
		        {
		            header($s_header);
		        }
				echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?".">\n";
		        echo trim($s_captured_output);
		        echo trim($this->s_content);
		        echo $sText;
		        $this->m_bHeaderSent = true;
    		}
     	}
    }

    public function small_p($sText)
    {
	}
	
	public function table($aConfig, $aData, $aParams = array())
	{
	}
}
?>
