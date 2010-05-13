<?php


class SecurityException extends CoreException
{
}

class CoreException extends Exception
{
	public function __construct($sIssue, $aContextData = null, $sImpact = '')
	{
		$this->m_sIssue = $sIssue;
		$this->m_sImpact = $sImpact;
		$this->m_aContextData = $aContextData ? $aContextData : array();
		
		$sMessage = $sIssue;
		if (!empty($sImpact)) $sMessage .= "($sImpact)";
		if (count($this->m_aContextData) > 0)
		{
			$sMessage .= ": ";
			$aContextItems = array();
			foreach($this->m_aContextData as $sKey => $value)
			{
				if (is_array($value))
				{
					$aPairs = array();
					foreach($value as $key => $val)
					{
						if (is_array($val))
						{
							$aPairs[] = $key.'=>('.implode(', ', $val).')';
						}
						else
						{
							$aPairs[] = $key.'=>'.$val;
						}
					}
					$sValue = '{'.implode('; ', $aPairs).'}';
				}
				else
				{
					$sValue = $value;
				}
				$aContextItems[] = "$sKey = $sValue";
			}
			$sMessage .= implode(', ', $aContextItems);
		}
		parent::__construct($sMessage, 0);
	}

	public function getHtmlDesc($sHighlightHtmlBegin = '<b>', $sHighlightHtmlEnd = '</b>')
	{
		return $this->getMessage();
	}

	public function getTraceAsHtml()
	{
		$aBackTrace = $this->getTrace();
		return MyHelpers::get_callstack_html(0, $this->getTrace());
		// return "<pre>\n".$this->getTraceAsString()."</pre>\n";
	}

	public function addInfo($sKey, $value)
	{
		$this->m_aContextData[$sKey] = $value;
	}

	public function getIssue()
	{
		return $this->m_sIssue;
	}
	public function getImpact()
	{
		return $this->m_sImpact;
	}
	public function getContextData()
	{
		return $this->m_aContextData;
	}
}

class CoreWarning extends CoreException
{
}

?>
