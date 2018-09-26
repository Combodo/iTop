<?php
// Copyright (C) 2010-2017 Combodo SARL
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

define('CASELOG_VISIBLE_ITEMS', 2);
define('CASELOG_SEPARATOR', "\n".'========== %1$s : %2$s (%3$d) ============'."\n\n");


/**
 * Class to store a "case log" in a structured way, keeping track of its successive entries
 *  
 * @copyright   Copyright (C) 2010-2017 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
class ormCaseLog {
	protected $m_sLog;
	protected $m_aIndex;
	protected $m_bModified;
	
	/**
	 * Initializes the log with the first (initial) entry
	 * @param $sLog string The text of the whole case log
	 * @param $aIndex array The case log index
	 */
	public function __construct($sLog = '', $aIndex = array())
	{
		$this->m_sLog = $sLog;
		$this->m_aIndex = $aIndex;
		$this->m_bModified = false;
	}
	
	public function GetText($bConvertToPlainText = false)
	{
		if ($bConvertToPlainText)
		{
			// Rebuild the log, but filtering any HTML markup for the all 'html' entries in the log
			return $this->GetAsPlainText();
		}
		else
		{
			return $this->m_sLog;
		}
	}
	
	public static function FromJSON($oJson)
	{
		if (!isset($oJson->items))
		{
			throw new Exception("Missing 'items' elements");
		}
		$oCaseLog = new ormCaseLog();
		foreach($oJson->items as $oItem)
		{
			$oCaseLog->AddLogEntryFromJSON($oItem);
		}
		return $oCaseLog;
	}

	/**
	 * Return a value that will be further JSON encoded	
	 */	
	public function GetForJSON()
	{
		// Order by ascending date
		$aRet = array('entries' => array_reverse($this->GetAsArray()));
		return $aRet;
	}

	/**
	 * Return all the data, in a format that is suitable for programmatic usages:
	 * -> dates not formatted
	 * -> to preserve backward compatibility, to the returned structure must grow (new array entries)
	 *
	 * Format:
	 * array (
	 *    array (
	 *       'date' => <yyyy-mm-dd hh:mm:ss>,
	 *       'user_login' => <user friendly name>
	 *       'user_id' => OPTIONAL <id of the user account (caution: the object might have been deleted since)>
	 *       'message' => <message as plain text (CR/LF), empty if message_html is given>
	 *       'message_html' => <message with HTML markup, empty if message is given>
	 *    )
	 *
	 * @return array
	 * @throws DictExceptionMissingString
	 */
	public function GetAsArray()
	{
		$aEntries = array();
		$iPos = 0;
		for($index=count($this->m_aIndex)-1 ; $index >= 0 ; $index--)
		{
			$iPos += $this->m_aIndex[$index]['separator_length'];
			$sTextEntry = substr($this->m_sLog, $iPos, $this->m_aIndex[$index]['text_length']);
			$iPos += $this->m_aIndex[$index]['text_length'];

			// Workaround: PHP < 5.3 cannot unserialize correctly DateTime objects,
			// therefore we have changed the format. To preserve the compatibility with existing
			// installations of iTop, both format are allowed:
			//     the 'date' item is either a DateTime object, or a unix timestamp
			if (is_int($this->m_aIndex[$index]['date']))
			{
				// Unix timestamp
				$sDate = date(AttributeDateTime::GetInternalFormat(),$this->m_aIndex[$index]['date']);
			}
			elseif (is_object($this->m_aIndex[$index]['date']))
			{
				if (version_compare(phpversion(), '5.3.0', '>='))
				{
					// DateTime
					$sDate = $this->m_aIndex[$index]['date']->format(AttributeDateTime::GetInternalFormat());
				}
				else
				{
					// No Warning... but the date is unknown
					$sDate = '';
				}
			}
			$sFormat = array_key_exists('format',  $this->m_aIndex[$index]) ?  $this->m_aIndex[$index]['format'] : 'text';
			switch($sFormat)
			{
				case 'text':
					$sHtmlEntry = utils::TextToHtml($sTextEntry);
					break;

				case 'html':
					$sHtmlEntry = $sTextEntry;
					$sTextEntry = utils::HtmlToText($sHtmlEntry);
					break;
			}
			$aEntries[] = array(
				'date' => $sDate,
				'user_login' => $this->m_aIndex[$index]['user_name'],
				'user_id' => $this->m_aIndex[$index]['user_id'],
				'message' => $sTextEntry,
				'message_html' => $sHtmlEntry,
			);
		}

		// Process the case of an eventual remainder (quick migration of AttributeText fields)
		if ($iPos < (strlen($this->m_sLog) - 1))
		{
			$sTextEntry = substr($this->m_sLog, $iPos);

			$aEntries[] = array(
				'date' => '',
				'user_login' => '',
				'user_id' => 0,
				'message' => $sTextEntry,
				'message_html' => utils::TextToHtml($sTextEntry),
			);
		}

		return $aEntries;
	}


	/**
	 * Returns a "plain text" version of the log (equivalent to $this->m_sLog) where all the HTML markup from the 'html' entries have been removed
	 * @return string
	 */
	public function GetAsPlainText()
	{
		$sPlainText = '';
		$aJSON = $this->GetForJSON();
		foreach($aJSON['entries'] as $aData)
		{
			$sSeparator = sprintf(CASELOG_SEPARATOR, $aData['date'], $aData['user_login'], $aData['user_id']);
			$sPlainText .= $sSeparator.$aData['message'];
		}
		return $sPlainText;	
	}
	
	public function GetIndex()
	{
		return $this->m_aIndex;
	}

	public function __toString()
	{
        if($this->IsEmpty()) return '';

        return $this->m_sLog;
	}

	public function IsEmpty()
    {
        return ($this->m_sLog === null);
    }
	
	public function ClearModifiedFlag()
	{
		$this->m_bModified = false;
	}

	/**
	 * Produces an HTML representation, aimed at being used within an email
	 */	 	
	public function GetAsEmailHtml()
	{
		$sStyleCaseLogHeader = '';
		$sStyleCaseLogEntry = '';

		$sHtml = '<table style="width:100%;table-layout:fixed"><tr><td>'; // Use table-layout:fixed to force the with to be independent from the actual content
		$iPos = 0;
		$aIndex = $this->m_aIndex;
		for($index=count($aIndex)-1 ; $index >= 0 ; $index--)
		{
			$iPos += $aIndex[$index]['separator_length'];
			$sTextEntry = substr($this->m_sLog, $iPos, $aIndex[$index]['text_length']);
			$sCSSClass = 'caselog_entry_html';
			if (!array_key_exists('format', $aIndex[$index]) || ($aIndex[$index]['format'] == 'text'))
			{
				$sCSSClass = 'caselog_entry';
				$sTextEntry = str_replace(array("\r\n", "\n", "\r"), "<br/>", htmlentities($sTextEntry, ENT_QUOTES, 'UTF-8'));
			}
			else
			{
				$sTextEntry = InlineImage::FixUrls($sTextEntry);
			}
			$iPos += $aIndex[$index]['text_length'];

			$sEntry = '<div class="caselog_header" style="'.$sStyleCaseLogHeader.'">';
			// Workaround: PHP < 5.3 cannot unserialize correctly DateTime objects,
			// therefore we have changed the format. To preserve the compatibility with existing
			// installations of iTop, both format are allowed:
			//     the 'date' item is either a DateTime object, or a unix timestamp
			if (is_int($aIndex[$index]['date']))
			{
				// Unix timestamp
				$sDate = date((string)AttributeDateTime::GetFormat(), $aIndex[$index]['date']);
			}
			elseif (is_object($aIndex[$index]['date']))
			{
				if (version_compare(phpversion(), '5.3.0', '>='))
				{
					// DateTime
					$sDate = $aIndex[$index]['date']->format((string)AttributeDateTime::GetFormat());
				}
				else
				{
					// No Warning... but the date is unknown
					$sDate = '';
				}
			}
			$sEntry .= sprintf(Dict::S('UI:CaseLog:Header_Date_UserName'), '<span class="caselog_header_date">'.$sDate.'</span>', '<span class="caselog_header_user">'.$aIndex[$index]['user_name'].'</span>');
			$sEntry .= '</div>';
			$sEntry .= '<div class="'.$sCSSClass.'" style="'.$sStyleCaseLogEntry.'">';
			$sEntry .= $sTextEntry;
			$sEntry .= '</div>';
			$sHtml = $sHtml.$sEntry;
		}

		// Process the case of an eventual remainder (quick migration of AttributeText fields)
		if ($iPos < (strlen($this->m_sLog) - 1))
		{
			$sTextEntry = substr($this->m_sLog, $iPos);
			$sTextEntry = str_replace(array("\r\n", "\n", "\r"), "<br/>", htmlentities($sTextEntry, ENT_QUOTES, 'UTF-8'));

			if (count($this->m_aIndex) == 0)
			{
				$sHtml .= '<div class="caselog_entry" style="'.$sStyleCaseLogEntry.'"">';
				$sHtml .= $sTextEntry;
				$sHtml .= '</div>';
			}
			else
			{
				$sHtml .= '<div class="caselog_header" style="'.$sStyleCaseLogHeader.'">';
				$sHtml .= Dict::S('UI:CaseLog:InitialValue');
				$sHtml .= '</div>';
				$sHtml .= '<div class="caselog_entry" style="'.$sStyleCaseLogEntry.'">';
				$sHtml .= $sTextEntry;
				$sHtml .= '</div>';
			}
		}
		$sHtml .= '</td></tr></table>';
		return $sHtml;
	}
	
	/**
	 * Produces an HTML representation, aimed at being used to produce a PDF with TCPDF (no table)
	 */	 	
	public function GetAsSimpleHtml($aTransfoHandler = null)
	{
		$sStyleCaseLogEntry = '';

		$sHtml = '<ul class="case_log_simple_html">';
		$iPos = 0;
		$aIndex = $this->m_aIndex;
		for($index=count($aIndex)-1 ; $index >= 0 ; $index--)
		{
			$iPos += $aIndex[$index]['separator_length'];
			$sTextEntry = substr($this->m_sLog, $iPos, $aIndex[$index]['text_length']);
			$sCSSClass = 'case_log_simple_html_entry_html';
			if (!array_key_exists('format', $aIndex[$index]) || ($aIndex[$index]['format'] == 'text'))
			{
				$sCSSClass = 'case_log_simple_html_entry';
				$sTextEntry = str_replace(array("\r\n", "\n", "\r"), "<br/>", htmlentities($sTextEntry, ENT_QUOTES, 'UTF-8'));
				if (!is_null($aTransfoHandler))
				{
					$sTextEntry = call_user_func($aTransfoHandler, $sTextEntry);
				}
			}
			else
			{
				if (!is_null($aTransfoHandler))
				{
					$sTextEntry = call_user_func($aTransfoHandler, $sTextEntry, true /* wiki "links" only */);
				}
				$sTextEntry = InlineImage::FixUrls($sTextEntry);
			}			
			$iPos += $aIndex[$index]['text_length'];

			$sEntry = '<li>';
			// Workaround: PHP < 5.3 cannot unserialize correctly DateTime objects,
			// therefore we have changed the format. To preserve the compatibility with existing
			// installations of iTop, both format are allowed:
			//     the 'date' item is either a DateTime object, or a unix timestamp
			if (is_int($aIndex[$index]['date']))
			{
				// Unix timestamp
				$sDate = date((string)AttributeDateTime::GetFormat(),$aIndex[$index]['date']);
			}
			elseif (is_object($aIndex[$index]['date']))
			{
				if (version_compare(phpversion(), '5.3.0', '>='))
				{
					// DateTime
					$sDate = $aIndex[$index]['date']->format((string)AttributeDateTime::GetFormat());
				}
				else
				{
					// No Warning... but the date is unknown
					$sDate = '';
				}
			}
			$sEntry .= sprintf(Dict::S('UI:CaseLog:Header_Date_UserName'), '<span class="caselog_header_date">'.$sDate.'</span>', '<span class="caselog_header_user">'.$aIndex[$index]['user_name'].'</span>');
			$sEntry .= '<div class="'.$sCSSClass.'" style="'.$sStyleCaseLogEntry.'">';
			$sEntry .= $sTextEntry;
			$sEntry .= '</div>';
			$sEntry .= '</li>';
			$sHtml = $sHtml.$sEntry;
		}

		// Process the case of an eventual remainder (quick migration of AttributeText fields)
		if ($iPos < (strlen($this->m_sLog) - 1))
		{
			$sTextEntry = substr($this->m_sLog, $iPos);
			$sTextEntry = str_replace(array("\r\n", "\n", "\r"), "<br/>", htmlentities($sTextEntry, ENT_QUOTES, 'UTF-8'));

			if (count($this->m_aIndex) == 0)
			{
				$sHtml .= '<li>';
				$sHtml .= $sTextEntry;
				$sHtml .= '</li>';
			}
			else
			{
				$sHtml .= '<li>';
				$sHtml .= Dict::S('UI:CaseLog:InitialValue');
				$sHtml .= '<div class="case_log_simple_html_entry" style="'.$sStyleCaseLogEntry.'">';
				$sHtml .= $sTextEntry;
				$sHtml .= '</div>';
				$sHtml .= '</li>';
			}
		}
		$sHtml .= '</ul>';
		return $sHtml;
	}

	/**
	 * Produces an HTML representation, aimed at being used within the iTop framework
	 */	 	
	public function GetAsHTML(WebPage $oP = null, $bEditMode = false, $aTransfoHandler = null)
	{
		$bPrintableVersion = (utils::ReadParam('printable', '0') == '1');

		$sHtml = '<table style="width:100%;table-layout:fixed"><tr><td>'; // Use table-layout:fixed to force the with to be independent from the actual content
		$iPos = 0;
		$aIndex = $this->m_aIndex;
		if (($bEditMode) && (count($aIndex) > 0) && $this->m_bModified)
		{
			// Don't display the first element, that is still considered as editable
			$aLastEntry = end($aIndex);
			$iPos = $aLastEntry['separator_length'] + $aLastEntry['text_length'];
			array_pop($aIndex);
		}
		for($index=count($aIndex)-1 ; $index >= 0 ; $index--)
		{
			if (!$bPrintableVersion && ($index < count($aIndex) - CASELOG_VISIBLE_ITEMS))
			{
				$sOpen = '';
				$sDisplay = 'style="display:none;"';
			}
			else
			{
				$sOpen = ' open';
				$sDisplay = '';
			}
			$iPos += $aIndex[$index]['separator_length'];
			$sTextEntry = substr($this->m_sLog, $iPos, $aIndex[$index]['text_length']);
			$sCSSClass= 'caselog_entry_html';
			if (!array_key_exists('format', $aIndex[$index]) || ($aIndex[$index]['format'] == 'text'))
			{
				$sCSSClass= 'caselog_entry';
				$sTextEntry = str_replace(array("\r\n", "\n", "\r"), "<br/>", htmlentities($sTextEntry, ENT_QUOTES, 'UTF-8'));
				if (!is_null($aTransfoHandler))
				{
					$sTextEntry = call_user_func($aTransfoHandler, $sTextEntry);
				}
			}
			else
			{
				if (!is_null($aTransfoHandler))
				{
					$sTextEntry = call_user_func($aTransfoHandler, $sTextEntry, true /* wiki "links" only */);
				}
				$sTextEntry = InlineImage::FixUrls($sTextEntry);
			}
			$iPos += $aIndex[$index]['text_length'];

			$sEntry = '<div class="caselog_header'.$sOpen.'">';
			// Workaround: PHP < 5.3 cannot unserialize correctly DateTime objects,
			// therefore we have changed the format. To preserve the compatibility with existing
			// installations of iTop, both format are allowed:
			//     the 'date' item is either a DateTime object, or a unix timestamp
			if (is_int($aIndex[$index]['date']))
			{
				// Unix timestamp
				$sDate = date((string)AttributeDateTime::GetFormat(),$aIndex[$index]['date']);
			}
			elseif (is_object($aIndex[$index]['date']))
			{
				if (version_compare(phpversion(), '5.3.0', '>='))
				{
					// DateTime
					$sDate = $aIndex[$index]['date']->format((string)AttributeDateTime::GetFormat());
				}
				else
				{
					// No Warning... but the date is unknown
					$sDate = '';
				}
			}
			$sEntry .= sprintf(Dict::S('UI:CaseLog:Header_Date_UserName'), $sDate, $aIndex[$index]['user_name']);
			$sEntry .= '</div>';
			$sEntry .= '<div class="'.$sCSSClass.'"'.$sDisplay.'>';
			$sEntry .= $sTextEntry;
			$sEntry .= '</div>';
			$sHtml = $sHtml.$sEntry;
		}

		// Process the case of an eventual remainder (quick migration of AttributeText fields)
		if ($iPos < (strlen($this->m_sLog) - 1))
		{
			// In this case the format is always "text"
			$sTextEntry = substr($this->m_sLog, $iPos);
			$sTextEntry = str_replace(array("\r\n", "\n", "\r"), "<br/>", htmlentities($sTextEntry, ENT_QUOTES, 'UTF-8'));
			if (!is_null($aTransfoHandler))
			{
				$sTextEntry = call_user_func($aTransfoHandler, $sTextEntry);
			}

			if (count($this->m_aIndex) == 0)
			{
				$sHtml .= '<div class="caselog_entry open">';
				$sHtml .= $sTextEntry;
				$sHtml .= '</div>';
			}
			else
			{
				if (!$bPrintableVersion && (count($this->m_aIndex) - CASELOG_VISIBLE_ITEMS > 0))
				{
					$sOpen = '';
					$sDisplay = 'style="display:none;"';
				}
				else
				{
					$sOpen = ' open';
					$sDisplay = '';
				}
				$sHtml .= '<div class="caselog_header'.$sOpen.'">';
				$sHtml .= Dict::S('UI:CaseLog:InitialValue');
				$sHtml .= '</div>';
				$sHtml .= '<div class="caselog_entry"'.$sDisplay.'>';
				$sHtml .= $sTextEntry;
				$sHtml .= '</div>';
			}
		}
		$sHtml .= '</td></tr></table>';
		return $sHtml;
	}
	
	/**
	 * Add a new entry to the log or merge the given text into the currently modified entry 
	 * and updates the internal index
	 * @param $sText string The text of the new entry 
	 */
	public function AddLogEntry($sText, $sOnBehalfOf = '')
	{
		$sText = HTMLSanitizer::Sanitize($sText);
		$sDate = date(AttributeDateTime::GetInternalFormat());
		if ($sOnBehalfOf == '')
		{
			$sOnBehalfOf = UserRights::GetUserFriendlyName();
			$iUserId = UserRights::GetUserId();
		}
		else
		{
			$iUserId = null;
		}
		if ($this->m_bModified)
		{
			$aLatestEntry = end($this->m_aIndex);
			if ($aLatestEntry['user_name'] == $sOnBehalfOf)
			{
				// Append the new text to the previous one
				$sPreviousText = substr($this->m_sLog, $aLatestEntry['separator_length'], $aLatestEntry['text_length']);
				$sText = $sPreviousText."\n".$sText;

				// Cleanup the previous entry
				array_pop($this->m_aIndex);
				$this->m_sLog = substr($this->m_sLog, $aLatestEntry['separator_length'] + $aLatestEntry['text_length']);
			}
		}

		$sSeparator = sprintf(CASELOG_SEPARATOR, $sDate, $sOnBehalfOf, $iUserId);
		$iSepLength = strlen($sSeparator);
		$iTextlength = strlen($sText);
		$this->m_sLog = $sSeparator.$sText.$this->m_sLog; // Latest entry printed first
		$this->m_aIndex[] = array(
			'user_name' => $sOnBehalfOf,
			'user_id' => $iUserId,
			'date' => time(),
			'text_length' => $iTextlength,
			'separator_length' => $iSepLength,
			'format' => 'html',
		);
		$this->m_bModified = true;
	}


	public function AddLogEntryFromJSON($oJson, $bCheckUserId = true)
	{
		if (isset($oJson->user_id))
		{
			if (!UserRights::IsAdministrator())
			{
				throw new Exception("Only administrators can set the user id", RestResult::UNAUTHORIZED);
			}
			if ($bCheckUserId && ($oJson->user_id != 0))
			{
				try
				{
					$oUser = RestUtils::FindObjectFromKey('User', $oJson->user_id);
				}
				catch(Exception $e)
				{
					throw new Exception('user_id: '.$e->getMessage(), $e->getCode());
				}
				$iUserId = $oUser->GetKey();
				$sOnBehalfOf = $oUser->GetFriendlyName();
			}
			else
			{
				$iUserId = $oJson->user_id;
				$sOnBehalfOf = $oJson->user_login;
			}
		}
		else
		{
			$iUserId = UserRights::GetUserId();
			$sOnBehalfOf = UserRights::GetUserFriendlyName();
		}
		
		if (isset($oJson->date))
		{
			$oDate = new DateTime($oJson->date);
			$iDate = (int) $oDate->format('U');
		}
		else
		{
			$iDate = time();
		}
		if (isset($oJson->format))
		{
			$sFormat = $oJson->format;
		}
		else
		{
			// The default is HTML
			$sFormat = 'html';
		}

		$sText = isset($oJson->message) ? $oJson->message : '';
		if ($sFormat == 'html')
		{
			$sText = HTMLSanitizer::Sanitize($sText);
		}

		$sDate = date(AttributeDateTime::GetInternalFormat(), $iDate);

		$sSeparator = sprintf(CASELOG_SEPARATOR, $sDate, $sOnBehalfOf, $iUserId);
		$iSepLength = strlen($sSeparator);
		$iTextlength = strlen($sText);
		$this->m_sLog = $sSeparator.$sText.$this->m_sLog; // Latest entry printed first
		$this->m_aIndex[] = array(
			'user_name' => $sOnBehalfOf,	
			'user_id' => $iUserId,	
			'date' => $iDate,	
			'text_length' => $iTextlength,	
			'separator_length' => $iSepLength,
			'format' => $sFormat,
		);

		$this->m_bModified = true;
	}


	public function GetModifiedEntry($sFormat = 'text')
	{
		$sModifiedEntry = '';
		if ($this->m_bModified)
		{
			$sModifiedEntry = $this->GetLatestEntry($sFormat);
		}
		return $sModifiedEntry;
	}

	/**
	 * Get the latest entry from the log
	 * @param string The expected output format text|html
	 * @return string
	 */
	public function GetLatestEntry($sFormat = 'text')
	{
		$sRes = '';
		$aLastEntry = end($this->m_aIndex);
		$sRaw = substr($this->m_sLog, $aLastEntry['separator_length'], $aLastEntry['text_length']);
		switch($sFormat)
		{
			case 'text':
			if ($aLastEntry['format'] == 'text')
			{
				$sRes = $sRaw;
			}
			else
			{
				$sRes = utils::HtmlToText($sRaw);
			}
			break;
			
			case 'html':
			if ($aLastEntry['format'] == 'text')
			{
				$sRes = utils::TextToHtml($sRaw);
			}
			else
			{
				$sRes = $sRaw;
			}
			break;
		}
		return $sRes;
	}

	/**
	 * Get the index of the latest entry from the log
	 * @return integer
	 */
	public function GetLatestEntryIndex()
	{
		$aKeys = array_keys($this->m_aIndex);
		$iLast = end($aKeys); // Strict standards: the parameter passed to 'end' must be a variable since it is passed by reference
		return $iLast;
	}
	
	/**
	 * Get the text string corresponding to the given entry in the log (zero based index, older entries first)
	 * @param integer $iIndex
	 * @return string The text of the entry
	 */
	public function GetEntryAt($iIndex)
	{
		$iPos = 0;
		$index = count($this->m_aIndex) - 1;
		while($index > $iIndex)
		{
			$iPos += $this->m_aIndex[$index]['separator_length'];
			$iPos += $this->m_aIndex[$index]['text_length'];
			$index--;
		}
		$iPos += $this->m_aIndex[$index]['separator_length'];
		$sText = substr($this->m_sLog, $iPos, $this->m_aIndex[$index]['text_length']);
		return $sText;
	}
}
?>