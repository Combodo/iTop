<?php
require_once("../application/webpage.class.inc.php");
/**
 * Simple web page with no includes, header or fancy formatting, useful to
 * generate HTML fragments when called by an AJAX method
 *
 * @package     iTopApplication
 * @access		public 
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @author      Denis Flaven <dflaven@free.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 */
 
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
     * Adds a tabular content to the web page
     * @param Hash $aConfig Configuration of the table: hash array of 'column_id' => 'Column Label'
     * @param Hash $aData Hash array. Data to display in the table: each row is made of 'column_id' => Data. A column 'pkey' is expected for each row
     * @param Hash $aParams Hash array. Extra parameters for the table. Entry 'class' holds the class of the objects listed in the table
     * @return void
     */	  
	public function table($aConfig, $aData, $aParams = array())
	{
		// WARNING WARNING WARNING
		// This whole function is actually a copy paste from iTopWebPage::table
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
			if (false) //(isset($aParams['preview']) && $aParams['preview'])
			{
				$sHtml .= "<tr id=\"Row_".$iNbTables."_".$aRow['key']."\" onClick=\"DisplayPreview(".$iNbTables.",".$aRow['key'].",'".$aParams['class']."')\">\n";
			}
			else if (isset($aRow['key']))
			{
				$sHtml .= "<tr onDblClick=\"DisplayDetails(".$aRow['key'].",'".$aParams['class']."')\">\n";
			}
			else
			{
				$sHtml .= "<tr>\n";
			}
			foreach($aConfig as $sName=>$aVoid)
			{
				if ($sName != 'key')
				{
					$sValue = empty($aRow[$sName]) ? '&nbsp;' : $aRow[$sName];
					$sHtml .= "<td>$sValue</td>\n";
				}
				else
				{
					$sUIPage = cmdbAbstractObject::ComputeUIPage($aParams['class']);
					$sHtml .= "<td><a class=\"no-arrow\" href=\"$sUIPage?operation=details&id=".$aRow['key']."&class=".$aParams['class']."&".$oAppContext->GetForLink()."\"><img src=\"../images/zoom.gif\" title=\"".Dict::S('UI:Details+')."\" border=\"0\"></a></td>\n";
				}
			}
			$sHtml .= "</tr>\n";
		}
		$sHtml .= "</tbody>\n";
		$sHtml .= "</table>\n";
		if (isset($aParams['preview']) && $aParams['preview'])
		{
			$sHtml .= "<div class=\"PreviewPane\" id=\"PreviewPane_".$iNbTables."\" style=\"height:100px;border:1px solid black;margin-top:2px;padding:3px;text-align:left;display:none;\">Preview Pane</div>";
		}
		$this->add($sHtml);	
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
}

?>
