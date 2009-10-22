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
		$data = $this->GetData();

		switch ($this->GetMainMimeType())
		{
		case 'text':
			return "<pre>".htmlentities(MyHelpers::beautifulstr($data, 1000, true))."</pre>\n";

		case 'application':
			return "binary data for ".$this->GetMimeType().', size: '.strlen($data).' byte(s).';

		case 'html':
		default:
			return "<div>".htmlentities(MyHelpers::beautifulstr($data, 1000, true))."</div>\n";
		}
	}
}
?>
