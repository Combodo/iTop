<?php
// Copyright (C) 2010-2024 Combodo SAS
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>

/**
 * Special handling for OQL syntax errors
 *
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


class OQLException extends CoreException
{
	/**
	 * @var string
	 * @since 3.1.0
	 */
	protected $m_MyIssue;
	/**
	 * @var string
	 * @since 3.1.0
	 */
	protected $m_sInput;
	/**
	 * @var int
	 * @since 3.1.0
	 */
	protected $m_iLine;
	/**
	 * @var int
	 * @since 3.1.0
	 */
	protected $m_iCol;
	/**
	 * @var string
	 * @since 3.1.0
	 */
	protected $m_sUnexpected;
	/**
	 * @var array|null string
	 * @since 3.1.0
	 */
	protected $m_aExpecting;

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

	public function GetUserFriendlyDescription()
	{
		// Todo - translate all errors!
		return $this->getMessage();
	}

	public function getHtmlDesc($sHighlightHtmlBegin = '<span style="font-weight: bolder">', $sHighlightHtmlEnd = '</span>')
	{
		$sRet = utils::EscapeHtml($this->m_MyIssue.", found '".$this->m_sUnexpected."' in: ");
		$sRet .= utils::EscapeHtml(substr($this->m_sInput, 0, $this->m_iCol));
		$sRet .= $sHighlightHtmlBegin.utils::EscapeHtml(substr($this->m_sInput, $this->m_iCol, strlen($this->m_sUnexpected))).$sHighlightHtmlEnd;
		$sRet .= utils::EscapeHtml(substr($this->m_sInput, $this->m_iCol + strlen($this->m_sUnexpected)));

		if (!is_null($this->m_aExpecting) && (count($this->m_aExpecting) > 0)) {
			if (count($this->m_aExpecting) < 30) {
				$sExpectations = '{'.implode(', ', $this->m_aExpecting).'}';
				$sRet .= ", expecting ".utils::EscapeHtml($sExpectations);
			}
			$sSuggest = self::FindClosestString($this->m_sUnexpected, $this->m_aExpecting);
			if (strlen($sSuggest) > 0) {
				$sRet .= ", I would suggest to use '$sHighlightHtmlBegin".utils::EscapeHtml($sSuggest)."$sHighlightHtmlEnd'";
			}
		}

		return $sRet;
	}

	public function GetIssue()
	{
		return $this->m_MyIssue;
	}

	public function GetSuggestions()
	{
		return $this->m_aExpecting;
	}

	public function GetWrongWord()
	{
		return $this->m_sUnexpected;
	}

	public function GetColumn()
	{
		return $this->m_iCol;
	}

	static public function FindClosestString($sInput, $aDictionary)
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
			
			if (($fDist <= 3) && ($fShortest < 0 || $fDist <= $fShortest))
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
