<?php

class OQLException extends CoreException
{
	public function __construct($sIssue, $sInput, $iLine, $iCol, $sUnexpected, $aExpecting = null)
	{
		$this->m_MyIssue = $sIssue;
		$this->m_sInput = $sInput;
		$this->m_iLine = $iLine;
		$this->m_iCol = $iCol;
		$this->m_sUnexpected = $sUnexpected;
		$this->m_aExpecting = $aExpecting;

		if (is_null($this->m_aExpecting))
		{
			$sMessage = "$sIssue - found '$sUnexpected' at $iCol in '$sInput'";
		}
		else
		{
			$sExpectations = '{'.implode(', ', $aExpecting).'}';
			$sMessage = "$sIssue - found '$sUnexpected' at $iCol in '$sInput', expecting $sExpectations";
		}
		
		// make sure everything is assigned properly
		parent::__construct($sMessage, 0);
	}
	
	public function getHtmlDesc($sHighlightHtmlBegin = '<b>', $sHighlightHtmlEnd = '</b>')
	{
		$sRet = htmlentities($this->m_MyIssue.", found '".$this->m_sUnexpected."' in: ");
		$sRet .= htmlentities(substr($this->m_sInput, 0, $this->m_iCol));
		$sRet .= $sHighlightHtmlBegin.htmlentities(substr($this->m_sInput, $this->m_iCol, strlen($this->m_sUnexpected))).$sHighlightHtmlEnd;
		$sRet .= htmlentities(substr($this->m_sInput, $this->m_iCol + strlen($this->m_sUnexpected)));
		return $sRet;
	}
}

?>
