<?php
require_once("../application/webpage.class.inc.php");
/**
 * Simple web page with no includes or fancy formatting, useful to generateXML documents
 * The page adds the content-type text/XML and the encoding into the headers
 */
class CSVPage extends web_page
{
    function __construct($s_title)
    {
        parent::__construct($s_title);
		$this->add_header("Content-type: text/html; charset=iso-8859-1");
		$this->add_header("Cache-control: no-cache");
    }	

    public function output()
    {
		$this->add_header("Content-Length: ".strlen(trim($this->s_content)));
        foreach($this->a_headers as $s_header)
        {
            header($s_header);
        }
        echo trim($this->s_content);
    }

    public function small_p($sText)
    {
	}
	
	public function table($aConfig, $aData, $aParams = array())
	{
	}
}

?>