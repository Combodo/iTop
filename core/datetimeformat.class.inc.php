<?php
// Copyright (C) 2024 Combodo SAS
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
 * Helper class to generate Date & Time formatting strings in the various conventions
 * from the PHP DateTime::createFromFormat convention.
 * 
 * Example:
 * 
 * $oFormat = new DateTimeFormat('m/d/Y H:i');
 * $oFormat->ToExcel();
 * >> 'MM/dd/YYYY HH:mm'
 * 
 * @author Denis Flaven <denis.flaven@combodo.com>
 *
 */
class DateTimeFormat
{
	protected $sPHPFormat;
	
	/**
	 * Constructs the DateTimeFormat object
	 * @param string $sPHPFormat A format string using the PHP 'DateTime::createFromFormat' convention
	 */
	public function __construct($sPHPFormat)
	{
		$this->sPHPFormat = (string)$sPHPFormat;
	}
	
	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->sPHPFormat;
	}
	
	/**
	 * Return the mapping table for converting between various conventions for date/time formats
	 */
	protected static function GetFormatMapping()
	{
		return array(
				// Days
				'd' => array('regexpr' => '(0[1-9]|[1-2][0-9]|3[0-1])', 'datepicker' => 'dd', 'excel' => 'dd', 'moment' => 'DD'), // Day of the month: 2 digits (with leading zero)
				'j' => array('regexpr' => '([1-9]|[1-2][0-9]|3[0-1])', 'datepicker' => 'd', 'excel' => 'd', 'moment' => 'D'), // Day of the month: 1 or 2 digits (without leading zero)
				// Months
				'm' => array('regexpr' => '(0[1-9]|1[0-2])', 'datepicker' => 'mm', 'excel' => 'MM', 'moment' => 'MM' ), // Month on 2 digits i.e. 01-12
				'n' => array('regexpr' => '([1-9]|1[0-2])', 'datepicker' => 'm', 'excel' => 'm', 'moment' => 'M'), // Month on 1 or 2 digits 1-12
				// Years
				'Y' => array('regexpr' => '([0-9]{4})', 'datepicker' => 'yy', 'excel' => 'YYYY', 'moment' => 'YYYY'), // Year on 4 digits
				'y' => array('regexpr' => '([0-9]{2})', 'datepicker' => 'y', 'excel' => 'YY', 'moment' => 'YY'), // Year on 2 digits
				// Hours
				'H' => array('regexpr' => '([0-1][0-9]|2[0-3])', 'datepicker' => 'HH', 'excel' => 'HH', 'moment' => 'HH'), // Hour 00..23
				'h' => array('regexpr' => '(0[1-9]|1[0-2])', 'datepicker' => 'hh', 'excel' => 'hh', 'moment' => 'hh'), // Hour 01..12
				'G' => array('regexpr' => '([0-9]|1[0-9]|2[0-3])', 'datepicker' => 'H', 'excel' => 'H', 'moment' => 'H'), // Hour 0..23
				'g' => array('regexpr' => '([1-9]|1[0-2])', 'datepicker' => 'h', 'excel' => 'h', 'moment' => 'h'), // Hour 1..12
				'a' => array('regexpr' => '(am|pm)', 'datepicker' => 'tt', 'excel' => 'am/pm', 'moment' => 'a'),
				'A' => array('regexpr' => '(AM|PM)', 'datepicker' => 'TT', 'excel' => 'AM/PM', 'moment' => 'A'),
				// Minutes
				'i' => array('regexpr' => '([0-5][0-9])', 'datepicker' => 'mm', 'excel' => 'mm', 'moment' => 'mm'),
				// Seconds
				's' => array('regexpr' => '([0-5][0-9])', 'datepicker' => 'ss', 'excel' => 'ss', 'moment' => 'ss'),	
		);
	}

	/**
	 * Transform the PHP format into the specified format, taking care of escaping the litteral characters
	 * using the supplied escaping expression
	 * @param string $sOutputFormatCode THe target format code: regexpr|datepicker|excel|moment
	 * @param string $sEscapePattern The replacement string for escaping characters in the output string. %s is the source char.
	 * @param string $bEscapeAll True to systematically escape all litteral characters
	 * @param array $sSpecialChars A string containing the only characters to escape in the output
	 * @return string The string in the requested format 
	 */
	protected function Transform($sOutputFormatCode, $sEscapePattern, $bEscapeAll = false, $sSpecialChars = '')
	{
		$aMappings = static::GetFormatMapping();
		$sResult = '';
		
		$bEscaping = false;
		for($i=0; $i < strlen($this->sPHPFormat); $i++)
		{
			if (($this->sPHPFormat[$i] == '\\'))
			{
				$bEscaping = true;
				continue;
			}
			
			if ($bEscaping)
			{
				if (($sSpecialChars === '') || (strpos($sSpecialChars, $this->sPHPFormat[$i]) !== false))
				{
					$sResult .= sprintf($sEscapePattern, $this->sPHPFormat[$i]);
				}
				else
				{
					$sResult .= $this->sPHPFormat[$i];
				}
				
				$bEscaping = false;
			}
			else if(array_key_exists($this->sPHPFormat[$i], $aMappings))
			{
				// Not a litteral value, must be replaced by its regular expression pattern
				$sResult .= $aMappings[$this->sPHPFormat[$i]][$sOutputFormatCode];
			}
			else
			{
				if ($bEscapeAll || (strpos($sSpecialChars, $this->sPHPFormat[$i]) !== false))
				{
					$sResult .= sprintf($sEscapePattern, $this->sPHPFormat[$i]);
				}
				else
				{
					// Normal char with no special meaning, no need to escape it
					$sResult .= $this->sPHPFormat[$i];
				}
			}
		}
		
		return $sResult;		
	}	
	
	/**
	 * Format a date into the supplied format string
	 * @param mixed $date An int, string, DateTime object or null !!
	 * @throws Exception
	 * @return string The formatted date
	 */
	public function Format($date)
	{
		if ($date == null)
		{
			$sDate = '';
		}
		else if (($date === '0000-00-00') || ($date === '0000-00-00 00:00:00'))
		{
			$sDate = '';
		}
		else if ($date instanceof DateTime)
		{
			// Parameter is a DateTime
			$sDate = $date->format($this->sPHPFormat);
		}
		else if (is_int($date))
		{
			// Parameter is a Unix timestamp
			$oDate = new DateTime();
			$oDate->setTimestamp($date);
			$sDate = $oDate->format($this->sPHPFormat);
		}
		else if (is_string($date))
		{
			$oDate = new DateTime($date);
			$sDate = $oDate->format($this->sPHPFormat);
		}
		else
		{
			throw new Exception(__CLASS__."::Format: Unexpected date value: ".print_r($date, true));
		}
		return $sDate;
	}
	
	/**
	 * Parse a date in the supplied format and return the date as a string in the internal format
	 * @param string $sDate The string to parse
	 * @param string $sFormat The format, in PHP createFromFormat convention
	 * @throws Exception
	 * @return DateTime|null
	 */
	public function Parse($sDate)
	{
		if (($sDate == null) || ($sDate == '0000-00-00 00:00:00') || ($sDate == '0000-00-00'))
		{
			return null;	
		}
		else
		{
			$sFormat = preg_replace('/\\?/', '', $this->sPHPFormat); // replace escaped characters by a wildcard for parsing
			$oDate = DateTime::createFromFormat($this->sPHPFormat, $sDate);
			if ($oDate === false)
			{
				throw new Exception(__CLASS__."::Parse: Unable to parse the date: '$sDate' using the format: '{$this->sPHPFormat}'");
			}
			return $oDate;
		}
	}
	
	/**
	 * Get the date or datetime format string in the jQuery UI date picker format
	 * @return string The format string using the date picker convention
	 */
	public function ToDatePicker()
	{
		return $this->Transform('datepicker', "'%s'");
	}
	
	/**
	 * Get a date or datetime format string in the Excel format
	 * @return string The format string using the Excel convention
	 */
	public function ToExcel()
	{
		return $this->Transform('excel', "%s");
	}
	
	/**
	 * Get a date or datetime format string in the moment.js format
	 * @return string The format string using the moment.js convention
	 */
	public function ToMomentJS()
	{
		return $this->Transform('moment', "[%s]", true /* escape all */);
	}
	
	public static function GetJSSQLToCustomFormat()
	{
		$aPHPToMoment = array();
		foreach(self::GetFormatMapping() as $sPHPCode => $aMapping)
		{
			$aPHPToMoment[$sPHPCode] = $aMapping['moment'];
		}
		$sJSMapping = json_encode($aPHPToMoment);
		
		$sFunction =
<<<EOF
function PHPDateTimeFormatToSubFormat(sPHPFormat, sPlaceholders)
{
	var iMax = 0;
	var iMin = 999;
	var bEscaping = false;
	for(var i=0; i<sPHPFormat.length; i++)
	{
		var c = sPHPFormat[i];
		if (c == '\\\\')
		{
			bEscaping = true;
			continue;
		}
		
		if (bEscaping)
		{
			bEscaping = false;
			continue;
		}
		else
		{
			if (sPlaceholders.search(c) != -1)
			{
				iMax = Math.max(iMax, i);
				iMin = Math.min(iMin, i);
			}
		}
	}
	return sPHPFormat.substr(iMin, iMax - iMin + 1);
}

function PHPDateTimeFormatToMomentFormat(sPHPFormat)
{
	var aFormatMapping = $sJSMapping;
	var sMomentFormat = '';
	
	var bEscaping = false;
	for(var i=0; i<sPHPFormat.length; i++)
	{
		var c = sPHPFormat[i];
		if (c == '\\\\')
		{
			bEscaping = true;
			continue;
		}
		
		if (bEscaping)
		{
			sMomentFormat += '['+c+']';
			bEscaping = false;
		}
		else
		{
			if (aFormatMapping[c] !== undefined)
			{
				sMomentFormat += aFormatMapping[c];
			}
			else
			{
				sMomentFormat += '['+c+']';
			}
		}
	}
	return sMomentFormat;
}

function DateFormatFromPHP(sSQLDate, sPHPFormat)
{
	if (sSQLDate === '') return '';
	var sPHPDateFormat = PHPDateTimeFormatToSubFormat(sPHPFormat, 'Yydjmn');
	var sMomentFormat = PHPDateTimeFormatToMomentFormat(sPHPDateFormat);	
	return moment(sSQLDate).format(sMomentFormat);
}		

function DateTimeFormatFromPHP(sSQLDate, sPHPFormat)
{
	if (sSQLDate === '') return '';
	var sMomentFormat = PHPDateTimeFormatToMomentFormat(sPHPFormat);	
	return moment(sSQLDate).format(sMomentFormat);
}		
EOF
		;
		return $sFunction;
	}
	
	/**
	 * Get a placeholder text for a date or datetime format string
	 * @return string The placeholder text (localized)
	 */
	public function ToPlaceholder()
	{
		$aMappings = static::GetFormatMapping();
		$sResult = '';
		
		$bEscaping = false;
		for($i=0; $i < strlen($this->sPHPFormat); $i++)
		{
			if (($this->sPHPFormat[$i] == '\\'))
			{
				$bEscaping = true;
				continue;
			}
			
			if ($bEscaping)
			{
				$sResult .= $this->sPHPFormat[$i]; // No need to escape characters in the placeholder
				$bEscaping = false;
			}
			else if(array_key_exists($this->sPHPFormat[$i], $aMappings))
			{
				// Not a litteral value, must be replaced by Dict equivalent
				$sResult .= Dict::S('Core:DateTime:Placeholder_'.$this->sPHPFormat[$i]);
			}
			else
			{

				// Normal char with no special meaning
				$sResult .= $this->sPHPFormat[$i];
			}
		}
		
		return $sResult;
	}

	/**
	 * Produces a subformat (Date or Time) by extracting the part of the whole DateTime format containing only the given placeholders
	 * @return string
	 */
	protected function ToSubFormat($aPlaceholders)
	{
		$iStart = 999;
		$iEnd = 0;
		
		foreach($aPlaceholders as $sChar)
		{
			$iPos = strpos($this->sPHPFormat, $sChar);
			if ($iPos !== false)
			{
				if (($iPos > 0) && ($this->sPHPFormat[$iPos-1] == '\\'))
				{
					// The placeholder is actually escaped, it's a litteral character, ignore it
					continue;
				}
				$iStart = min($iStart, $iPos);
				$iEnd = max($iEnd, $iPos);
			}
		}
		$sFormat = substr($this->sPHPFormat, $iStart, $iEnd - $iStart + 1);
		return $sFormat;
	}
	
	/**
	 * Produces the Date format string by extracting only the date part of the date and time format string
	 * @return string
	 */
	public function ToDateFormat()
	{
		return $this->ToSubFormat(array('Y', 'y', 'd', 'j', 'm', 'n'));
	}
	
	/**
	 * Produces the Time format string by extracting only the time part of the date and time format string
	 * @return string
	 */
	public function ToTimeFormat()
	{
		return $this->ToSubFormat(array('H', 'h', 'G', 'g', 'i', 's', 'a', 'A'));
	}
	
	/**
	 * Get the regular expression to (approximately) validate a date/time for the current format
	 * The validation does not take into account the number of days in a month (i.e. June 31st will pass, as well as Feb 30th!)
	 * @param string $sDelimiter Surround the regexp (and escape) if needed
	 * @return string The regular expression in PCRE syntax
	 */
	public function ToRegExpr($sDelimiter = null)
	{
		$sRet = '^'.$this->Transform('regexpr', "\\%s", false /* escape all */, '.?*$^()[]:').'$';
		if ($sDelimiter !== null)
		{
			$sRet = $sDelimiter.str_replace($sDelimiter, '\\'.$sDelimiter, $sRet).$sDelimiter;
		}
		return $sRet;
	}
}
