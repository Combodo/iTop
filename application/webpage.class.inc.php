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
 * Class WebPage
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

/**
 * Simple helper class to ease the production of HTML pages
 *
 * This class provide methods to add content, scripts, includes... to a web page 
 * and renders the full web page by putting the elements in the proper place & order
 * when the output() method is called.
 * Usage:
 * 	$oPage = new WebPage("Title of my page");
 *	$oPage->p("Hello World !");
 *	$oPage->output();
 */
class WebPage
{
    protected $s_title;
    protected $s_content;
    protected $s_deferred_content;
    protected $a_scripts;
    protected $a_styles;
    protected $a_include_scripts;
    protected $a_include_stylesheets;
    protected $a_headers;
    protected $a_base;
    
    public function __construct($s_title)
    {
        $this->s_title = $s_title;
        $this->s_content = "";
        $this->s_deferred_content = '';
        $this->a_scripts = array();
        $this->a_styles = array();
        $this->a_linked_scripts = array();
        $this->a_linked_stylesheets = array();
        $this->a_headers = array();
        $this->a_base = array( 'href' => '', 'target' => '');
        ob_start(); // Start capturing the output
    }
	
	/**
	 * Change the title of the page after its creation
	 */
    public function set_title($s_title)
    {
        $this->s_title = $s_title;
    }
    
	/**
	 * Specify a default URL and a default target for all links on a page
	 */
    public function set_base($s_href = '', $s_target = '')
    {
        $this->a_base['href'] = $s_href;
        $this->a_base['target'] = $s_target;
    }
    
	/**
	 * Add any text or HTML fragment to the body of the page
	 */
    public function add($s_html)
    {
        $this->s_content .= $s_html;
    }
    
	/**
	 * Add any text or HTML fragment at the end of the body of the page
	 * This is useful to add hidden content, DIVs or FORMs that should not
	 * be embedded into each other.	 	 
	 */
    public function add_at_the_end($s_html)
    {
        $this->s_deferred_content .= $s_html;
    }
    
	/**
	 * Add a paragraph to the body of the page
	 */
    public function p($s_html)
    {
        $this->add($this->GetP($s_html));
    }
    
	/**
	 * Add a paragraph to the body of the page
	 */
    public function GetP($s_html)
    {
        return "<p>$s_html</p>\n";
    }
    
	/**
	* Adds a tabular content to the web page
	* @param Hash $aConfig Configuration of the table: hash array of 'column_id' => 'Column Label'
	* @param Hash $aData Hash array. Data to display in the table: each row is made of 'column_id' => Data. A column 'pkey' is expected for each row
	* @param Hash $aParams Hash array. Extra parameters for the table.
	* @return void
	*/	  
	public function table($aConfig, $aData, $aParams = array())
	{
		$this->add($this->GetTable($aConfig, $aData, $aParams));
	}
	
	public function GetTable($aConfig, $aData, $aParams = array())
	{
		$oAppContext = new ApplicationContext();
		
		static $iNbTables = 0;
		$iNbTables++;
		$sHtml = "";
		$sHtml .= "<table class=\"listResults\">\n";
		$sHtml .= "<thead>\n";
		$sHtml .= "<tr>\n";
		foreach($aConfig as $sName=>$aDef)
		{
			$sHtml .= "<th title=\"".$aDef['description']."\">".$aDef['label']."</th>\n";
		}
		$sHtml .= "</tr>\n";
		$sHtml .= "</thead>\n";
		$sHtml .= "<tbody>\n";
		foreach($aData as $aRow)
		{
			$sHtml .= "<tr>\n";
			foreach($aConfig as $sName=>$aAttribs)
			{
				$aMatches = array();
				$sClass = isset($aAttribs['class']) ? 'class="'.$aAttribs['class'].'"' : '';
				$sValue = ($aRow[$sName] === '') ? '&nbsp;' : $aRow[$sName];
				$sHtml .= "<td $sClass>$sValue</td>\n";
			}
			$sHtml .= "</tr>\n";
		}
		$sHtml .= "</tbody>\n";
		$sHtml .= "</table>\n";
		return $sHtml;
	}
    
	/**
	 * Add some Javascript to the header of the page
	 */
    public function add_script($s_script)
    {
        $this->a_scripts[] = $s_script;
    }
    
	/**
	 * Add some Javascript to the header of the page
	 */
    public function add_ready_script($s_script)
    {
        // Do nothing silently... this is not supported by this type of page...
    }
	/**
	 * Add some CSS definitions to the header of the page
	 */
    public function add_style($s_style)
    {
        $this->a_styles[] = $s_style;
    }

	/**
	 * Add a script (as an include, i.e. link) to the header of the page
	 */
    public function add_linked_script($s_linked_script)
    {
        $this->a_linked_scripts[] = $s_linked_script;
    }

	/**
	 * Add a CSS stylesheet (as an include, i.e. link) to the header of the page
	 */
    public function add_linked_stylesheet($s_linked_stylesheet, $s_condition = "")
    {
        $this->a_linked_stylesheets[] = array( 'link' => $s_linked_stylesheet, 'condition' => $s_condition);
    }

	/**
	 * Add some custom header to the page
	 */
    public function add_header($s_header)
    {
        $this->a_headers[] = $s_header;
    }

	/**
	 * Add needed headers to the page so that it will no be cached
	 */
    public function no_cache()
    {
        $this->add_header("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
        $this->add_header("Expires: Fri, 17 Jul 1970 05:00:00 GMT");    // Date in the past
    }

	/**
	 * Build a special kind of TABLE useful for displaying the details of an object from a hash array of data
	 */
	public function details($aFields)
	{

		$this->add($this->GetDetails($aFields));
    }
	
	/**
	 * Build a special kind of TABLE useful for displaying the details of an object from a hash array of data
	 */
	public function GetDetails($aFields)
	{
		$sHtml = "<table>\n";
		$sHtml .= "<tbody>\n";
		foreach($aFields as $aAttrib)
		{
			$sHtml .= "<tr>\n";
			// By Rom, for csv import, proposed to show several values for column selection
			if (is_array($aAttrib['value']))
			{
				$sHtml .= "<td class=\"label\">".$aAttrib['label']."</td><td>".implode("</td><td>", $aAttrib['value'])."</td>\n";
			}
			else
			{
				$sHtml .= "<td class=\"label\">".$aAttrib['label']."</td><td>".$aAttrib['value']."</td>\n";
			}
    		$sHtml .= "</tr>\n";
		}
		$sHtml .= "</tbody>\n";
		$sHtml .= "</table>\n";
		return $sHtml;
    }
	
	/**
	 * Outputs (via some echo) the complete HTML page by assembling all its elements
	 */
    public function output()
    {
        foreach($this->a_headers as $s_header)
        {
            header($s_header);
        }
        $s_captured_output = ob_get_contents();
        ob_end_clean();
        echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n";
        echo "<html>\n";
        echo "<head>\n";
        echo "<title>{$this->s_title}</title>\n";
        echo $this->get_base_tag();
        foreach($this->a_linked_scripts as $s_script)
        {
            echo "<script type=\"text/javascript\" src=\"$s_script\"></script>\n";
        }
        if (count($this->a_scripts)>0)
        {
            echo "<script type=\"text/javascript\">\n";
            foreach($this->a_scripts as $s_script)
            {
                echo "$s_script\n";
            }
            echo "</script>\n";
        }
        foreach($this->a_linked_stylesheets as $a_stylesheet)
        {
			if ($a_stylesheet['condition'] != "")
			{
				echo "<!--[if {$a_stylesheet['condition']}]>\n";
			}
            echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"{$a_stylesheet['link']}\" />\n";
			if ($a_stylesheet['condition'] != "")
			{
				echo "<![endif]-->\n";
			}
        }
        
        if (count($this->a_styles)>0)
        {
            echo "<style>\n";
            foreach($this->a_styles as $s_style)
            {
                echo "$s_style\n";
            }
            echo "</style>\n";
        }
        echo "</head>\n";
        echo "<body>\n";
        echo $this->s_content;
        if (trim($s_captured_output) != "")
        {
            echo "<div class=\"raw_output\">$s_captured_output</div>\n";
        }
        echo $this->s_deferred_content;
        echo "</body>\n";
        echo "</html>\n";
    }

	/**
	 * Build a series of hidden field[s] from an array
	 */
	 // By Rom - je verrais bien une serie d'outils pour gerer des parametres que l'on retransmet entre pages d'un wizard...
	 //          ptet deriver webpage en webwizard
	public function add_input_hidden($sLabel, $aData)
	{
		foreach($aData as $sKey=>$sValue)
		{
			$this->add("<input type=\"hidden\" name=\"".$sLabel."[$sKey]\" value=\"$sValue\">");
		}
	}

	protected function get_base_tag()
	{
		$sTag = '';
        if (($this->a_base['href'] != '') || ($this->a_base['target'] != ''))
        {
        	$sTag = '<base ';
        	if (($this->a_base['href'] != ''))
        	{
        		$sTag .= "href =\"{$this->a_base['href']}\" ";
			}
        	if (($this->a_base['target'] != ''))
        	{
        		$sTag .= "target =\"{$this->a_base['target']}\" ";
			}
			$sTag .= " />\n";
		}
		return $sTag;
	}
}
?>
