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
 * Special handling for OQL syntax errors
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */


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
		$sRet = htmlentities($this->m_MyIssue.", found '".$this->m_sUnexpected."' in: ", ENT_QUOTES, 'UTF-8');
		$sRet .= htmlentities(substr($this->m_sInput, 0, $this->m_iCol), ENT_QUOTES, 'UTF-8');
		$sRet .= $sHighlightHtmlBegin.htmlentities(substr($this->m_sInput, $this->m_iCol, strlen($this->m_sUnexpected)), ENT_QUOTES, 'UTF-8').$sHighlightHtmlEnd;
		$sRet .= htmlentities(substr($this->m_sInput, $this->m_iCol + strlen($this->m_sUnexpected)), ENT_QUOTES, 'UTF-8');

		if (!is_null($this->m_aExpecting) && (count($this->m_aExpecting) > 0))
		{
			$sExpectations = '{'.implode(', ', $this->m_aExpecting).'}';
			$sRet .= ", expecting ".htmlentities($sExpectations, ENT_QUOTES, 'UTF-8'); 
			$sSuggest = self::FindClosestString($this->m_sUnexpected, $this->m_aExpecting);
			if (strlen($sSuggest) > 0)
			{
				$sRet .= ", I would suggest to use '$sHighlightHtmlBegin".htmlentities($sSuggest, ENT_QUOTES, 'UTF-8')."$sHighlightHtmlEnd'";
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
