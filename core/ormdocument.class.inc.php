<?php

/**
 * ormDocument
 * encapsulate the behavior of a binary data set that will be stored an attribute of class AttributeBlob 
 *
 * @package     tbd
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @author      Denis Flaven <denisflave@free.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 */

class ormDocument
{
	protected $m_data;
	protected $m_sMimeType;
	protected $m_sFileName;
	
	/**
	 * Constructor
	 */
	public function __construct($data = null, $sMimeType = 'text/plain', $sFileName = '')
	{
		$this->m_data = $data;
		$this->m_sMimeType = $sMimeType;
		$this->m_sFileName = $sFileName;
	}

	public function __toString()
	{
		return MyHelpers::beautifulstr($this->m_data, 100, true);
	}

	public function IsEmpty()
	{
		return ($this->m_data == null);
	}
	
	public function GetMimeType()
	{
		return $this->m_sMimeType;
	}
	public function GetMainMimeType()
	{
		$iSeparatorPos = strpos($this->m_sMimeType, '/');
		if ($iSeparatorPos > 0)
		{
			return substr($this->m_sMimeType, 0, $iSeparatorPos);
		}
		return $this->m_sMimeType;
	}

	public function GetData()
	{
		return $this->m_data;
	}

	public function GetFileName()
	{
		return $this->m_sFileName;
	}

	public function GetAsHTML()
	{
		$sResult = '';
		if ($this->IsEmpty())
		{
			// If the filename is not empty, display it, this is used
			// by the creation wizard while the file has not yet been uploaded
			$sResult = $this->GetFileName();
		}
		else
		{
			$data = $this->GetData();
			$sResult = $this->GetFileName().' [ '.$this->GetMimeType().', size: '.strlen($data).' byte(s) ]';
		}
		return $sResult;
	}
	
	/**
	 * Returns an HTML fragment that will display the document *inline* (if possible)
	 * @return string
	 */	 	 	
	public function GetDisplayInline($sClass, $Id, $sAttCode)
	{
		switch ($this->GetMainMimeType())
		{
			case 'text':
			case 'html':
			$data = $this->GetData();
			switch($this->GetMimeType())
			{
				case 'text/html':
				case 'text/xml':
				return "<iframe src=\"../pages/ajax.render.php?operation=display_document&class=$sClass&id=$Id&field=$sAttCode\" width=\"100%\" height=\"400\">Loading...</iframe>\n";
				
				default:
				return "<pre>".htmlentities(MyHelpers::beautifulstr($data, 1000, true))."</pre>\n";			
			}
			break; // Not really needed, but...

			case 'application':
			switch($this->GetMimeType())
			{
				case 'application/pdf':
				return "<iframe src=\"../pages/ajax.render.php?operation=display_document&class=$sClass&id=$Id&field=$sAttCode\" width=\"100%\" height=\"400\">Loading...</iframe>\n";
			}
			break;
			
			case 'image':
			return "<img src=\"../pages/ajax.render.php?operation=display_document&class=$sClass&id=$Id&field=$sAttCode\" />\n";
		}
	}
	
	/**
	 * Returns an hyperlink to display the document *inline*
	 * @return string
	 */	 	 	
	public function GetDisplayLink($sClass, $Id, $sAttCode)
	{
		return "<a href=\"../pages/ajax.render.php?operation=display_document&class=$sClass&id=$Id&field=$sAttCode\" target=\"_blank\" >".$this->GetFileName()."</a>\n";
	}
	
	/**
	 * Returns an hyperlink to download the document (content-disposition: attachment)
	 * @return string
	 */	 	 	
	public function GetDownloadLink($sClass, $Id, $sAttCode)
	{
		return "<a href=\"../pages/ajax.render.php?operation=download_document&class=$sClass&id=$Id&field=$sAttCode\">".$this->GetFileName()."</a>\n";
	}
}
?>
