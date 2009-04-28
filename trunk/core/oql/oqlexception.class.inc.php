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

		if (is_null($this->m_aExpecting) || (count($this->m_aExpecting) == 0))
		{
			$sMessage = "$sIssue - found '{$this->m_sUnexpected}' at $iCol in '$sInput'";
		}
		else
		{
			$sExpectations = '{'.implode(', ', $this->m_aExpecting).'}';
			$sSuggest = self::FindClosestString($this->m_sUnexpected, $this->m_aExpecting);
			$sMessage = "$sIssue - found '{$this->m_sUnexpected}' at $iCol in '$sInput', expecting $sExpectations, I would suggest to use '$sSuggest'";
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

		if (!is_null($this->m_aExpecting) && (count($this->m_aExpecting) > 0))
		{
			$sExpectations = '{'.implode(', ', $this->m_aExpecting).'}';
			$sRet .= ", expecting ".htmlentities($sExpectations); 
			$sSuggest = self::FindClosestString($this->m_sUnexpected, $this->m_aExpecting);
			if (strlen($sSuggest) > 0)
			{
				$sRet .= ", I would suggest to use '$sHighlightHtmlBegin".htmlentities($sSuggest)."$sHighlightHtmlEnd'";
			}
		}

		return $sRet;
	}

	static protected function FindClosestString($sInput, $aDictionary)
	{
		// no shortest distance found, yet
		$fShortest = -1;
		$sRet = '';
		
		// loop through words to find the closest
		foreach ($aDictionary as $sSuggestion)
		{
			// calculate the distance between the input string and the suggested one
			$fDist = levenshtein($sInput, $sSuggestion);
			if ($fDist == 0)
			{
				// Exact match
				return $sSuggestion;
			}
			
			if ($fShortest < 0 || ($fDist < 4 && $fDist <= $fShortest))
			{
				// set the closest match, and shortest distance
				$sRet = $sSuggestion;
				$fShortest = $fDist;
			}
		}
		return $sRet;
	}
}

?>
