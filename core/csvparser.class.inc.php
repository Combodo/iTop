<?php

/**
 * CSVParser
 * CSV interpreter helper, optionaly tries to guess column mapping and the separator, check the consistency 
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @author      Denis Flaven <denisflave@free.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */

class CSVParserException extends CoreException
{
}




/**
 * CSVParser
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     $itopversion$
 */
class CSVParser
{
	private $m_sCSVData;
	private $m_sSep;
	private $m_iSkip;

	public function __construct($sTxt)
	{
		$this->m_sCSVData = $sTxt;
	}

	public function SetSeparator($sSep)
	{
		$this->m_sSep = $sSep;
	}
	public function GetSeparator()
	{
		return $this->m_sSep;
	}

	public function SetSkipLines($iSkip)
	{
		$this->m_iSkip = $iSkip;
	}
	public function GetSkipLines()
	{
		return $this->m_iSkip;
	}

	public function GuessSeparator()
	{
		// Note: skip the first line anyway
	
		$aKnownSeps = array(';', ',', "\t"); // Use double quote for special chars!!!
		$aStatsBySeparator = array();
		foreach ($aKnownSeps as $sSep)
		{
			$aStatsBySeparator[$sSep] = array();
		}
	
		foreach(explode("\n", $this->m_sCSVData) as $sLine)
		{
			$sLine = trim($sLine);
			if (substr($sLine, 0, 1) == '#') continue;
			if (empty($sLine)) continue;
	
			$aLineCharsCount = count_chars($sLine, 0);
			foreach ($aKnownSeps as $sSep)
			{
				$aStatsBySeparator[$sSep][] = $aLineCharsCount[ord($sSep)];
			}
		}
	
		// Default to ','
		$this->SetSeparator(",");

		foreach ($aKnownSeps as $sSep)
		{
			// Note: this function is NOT available :-( 
			// stats_variance($aStatsBySeparator[$sSep]);
			$iMin = min($aStatsBySeparator[$sSep]);
			$iMax = max($aStatsBySeparator[$sSep]);
			if (($iMin == $iMax) && ($iMax > 0))
			{
				$this->SetSeparator($sSep);
				break;
			}
		}
		return $this->GetSeparator();
	}

	public function GuessSkipLines()
	{
		// Take the FIRST -valuable- LINE ONLY
		// If there is a number, then for sure this is not a header line
		// Otherwise, we may consider that there is one line to skip
		foreach(explode("\n", $this->m_sCSVData) as $sLine)
		{
			$sLine = trim($sLine);
			if (substr($sLine, 0, 1) == '#') continue;
			if (empty($sLine)) continue;
	
			foreach (explode($this->m_sSep, $sLine) as $value)
			{
				if (is_numeric($value))
				{
					$this->SetSkipLines(0);
					return 0;
				}
			}
			$this->SetSkipLines(1);
			return 1;
		}
	}

	function ToArray($aFieldMap = null, $iMax = 0)
	{
		// $aFieldMap is an array of col_index=>col_name
		// $iMax is to limit the count of rows computed
		$aRes = array();
	
		$iCount = 0;
		$iSkipped = 0;
		foreach(explode("\n", $this->m_sCSVData) as $sLine)
		{
			$sLine = trim($sLine);
			if (substr($sLine, 0, 1) == '#') continue;
			if (empty($sLine)) continue;
	
			if ($iSkipped < $this->m_iSkip)
			{
				$iSkipped++;
				continue;
			}
	
			foreach (explode($this->m_sSep, $sLine) as $iCol=>$sValue)
			{
				if (is_array($aFieldMap)) $sColRef = $aFieldMap[$iCol];
				else                      $sColRef = $iCol;
				$aRes[$iCount][$sColRef] = $sValue;
			}
	
			$iCount++;
			if (($iMax > 0) && ($iCount >= $iMax)) break;
		}
		return $aRes;
	}

	public function ListFields()
	{
		// Take the first valuable line
		foreach(explode("\n", $this->m_sCSVData) as $sLine)
		{
			$sLine = trim($sLine);
			if (substr($sLine, 0, 1) == '#') continue;
			if (empty($sLine)) continue;
			// We've got the first valuable line, that's it!
			break;
		}

		$aRet = array();
		foreach (explode($this->m_sSep, $sLine) as $iCol=>$value)
		{
			if ($this->m_iSkip == 0)
			{
				// No header to help us
				$sLabel = "field $iCol";
			}
			else
			{
				$sLabel = "$value";
			}
			$aRet[] = $sLabel;
		}
		return $aRet;
	}
}


?>
