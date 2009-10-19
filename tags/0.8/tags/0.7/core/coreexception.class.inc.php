<?php

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
							$aPairs[$key] = '('.implode(', ', $val).')';
						}
						else
						{
							$aPairs[$key] = $val;
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
}

?>
