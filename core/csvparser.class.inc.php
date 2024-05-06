<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


define('stSTARTING', 1); //grey zone: the type is undetermined
define('stRAW', 2); //building a non-qualified string
define('stQUALIFIED', 3); //building qualified string
define('stESCAPED', 4); //just encountered an escape char

define('evBLANK', 0);
define('evSEPARATOR', 1);
define('evNEWLINE', 2);
define('evTEXTQUAL', 3); // used for escaping as well
define('evOTHERCHAR', 4);
define('evEND', 5);

define('NULL_VALUE', '<NULL>');


/**
 * CSVParser
 *
 * @package     iTopORM
 */
class CSVParser
{
	private $m_sCSVData;
	private $m_sSep;
	private $m_sTextQualifier;
	private $m_iTimeLimitPerRow;

	public function __construct($sTxt, $sSep = ',', $sTextQualifier = '"', $iTimeLimitPerRow = null)
	{
		$this->m_sCSVData = str_replace("\r\n", "\n", $sTxt);
		$this->m_sSep = $sSep;
		$this->m_sTextQualifier = $sTextQualifier;
		$this->m_iTimeLimitPerRow = $iTimeLimitPerRow;
	}

	protected $m_sCurrCell = '';
	protected $m_aCurrRow = array();
	protected $m_iToSkip = 0;
	protected $m_aDataSet = array();

	protected function __AddChar($c)
	{
		$this->m_sCurrCell .= $c;
	}
	protected function __ClearCell()
	{
		$this->m_sCurrCell = '';
	}
	protected function __AddCell($c = null, $aFieldMap = null, $bTrimSpaces = false)
	{
		if ($bTrimSpaces)
		{
			$sCell = trim($this->m_sCurrCell);
		}
		else
		{
			$sCell = $this->m_sCurrCell;
		}
		if ($sCell == NULL_VALUE)
		{
			$sCell = null;
		}

		if (!is_null($aFieldMap))
		{
			$iNextCol = count($this->m_aCurrRow);
			$iNextName = $aFieldMap[$iNextCol];
			$this->m_aCurrRow[$iNextName] = $sCell;
		}
		else
		{
			$this->m_aCurrRow[] = $sCell;
		}
		$this->m_sCurrCell = '';
	}
	protected function __AddRow($c = null, $aFieldMap = null, $bTrimSpaces = false)
	{
		$this->__AddCell($c, $aFieldMap, $bTrimSpaces);

		if ($this->m_iToSkip > 0)
		{
			$this->m_iToSkip--;
		}
		elseif (count($this->m_aCurrRow) > 1)
		{
			$this->m_aDataSet[] = $this->m_aCurrRow;
		}
		elseif (count($this->m_aCurrRow) == 1)
		{
			// Get the unique value
			$aValues = array_values($this->m_aCurrRow);
			$sValue = $aValues[0]; 
			if (strlen($sValue) > 0)
			{
				$this->m_aDataSet[] = $this->m_aCurrRow;
			}
		}
		else
		{
			// blank line, skip silently
		}
		$this->m_aCurrRow = array();
		
		// More time for the next row
		if ($this->m_iTimeLimitPerRow !== null)
		{
			set_time_limit(intval($this->m_iTimeLimitPerRow));
		}
	}
	protected function __AddCellTrimmed($c = null, $aFieldMap = null)
	{
		$this->__AddCell($c, $aFieldMap, true);
	}

	protected function __AddRowTrimmed($c = null, $aFieldMap = null)
	{
		$this->__AddRow($c, $aFieldMap, true);
	}

	function ToArray($iToSkip = 1, $aFieldMap = null, $iMax = 0)
	{
		$aTransitions = array();

		$aTransitions[stSTARTING][evBLANK] = array('', stSTARTING);
		$aTransitions[stSTARTING][evSEPARATOR] = array('__AddCell', stSTARTING);
		$aTransitions[stSTARTING][evNEWLINE] = array('__AddRow', stSTARTING);
		$aTransitions[stSTARTING][evTEXTQUAL] = array('', stQUALIFIED);
		$aTransitions[stSTARTING][evOTHERCHAR] = array('__AddChar', stRAW);
		$aTransitions[stSTARTING][evEND] = array('__AddRow', stSTARTING);

		$aTransitions[stRAW][evBLANK] = array('__AddChar', stRAW);
		$aTransitions[stRAW][evSEPARATOR] = array('__AddCellTrimmed', stSTARTING);
		$aTransitions[stRAW][evNEWLINE] = array('__AddRowTrimmed', stSTARTING);
		$aTransitions[stRAW][evTEXTQUAL] = array('__AddChar', stRAW);
		$aTransitions[stRAW][evOTHERCHAR] = array('__AddChar', stRAW);
		$aTransitions[stRAW][evEND] = array('__AddRowTrimmed', stSTARTING);

		$aTransitions[stQUALIFIED][evBLANK] = array('__AddChar', stQUALIFIED);
		$aTransitions[stQUALIFIED][evSEPARATOR] = array('__AddChar', stQUALIFIED);
		$aTransitions[stQUALIFIED][evNEWLINE] = array('__AddChar', stQUALIFIED);
		$aTransitions[stQUALIFIED][evTEXTQUAL] = array('', stESCAPED);
		$aTransitions[stQUALIFIED][evOTHERCHAR] = array('__AddChar', stQUALIFIED);
		$aTransitions[stQUALIFIED][evEND] = array('__AddRow', stSTARTING);

		$aTransitions[stESCAPED][evBLANK] = array('', stESCAPED);
		$aTransitions[stESCAPED][evSEPARATOR] = array('__AddCell', stSTARTING);
		$aTransitions[stESCAPED][evNEWLINE] = array('__AddRow', stSTARTING);
		$aTransitions[stESCAPED][evTEXTQUAL] = array('__AddChar', stQUALIFIED);
		$aTransitions[stESCAPED][evOTHERCHAR] = array('__AddChar', stSTARTING);
		$aTransitions[stESCAPED][evEND] = array('__AddRow', stSTARTING);

		// Reset parser variables
		$this->m_sCurrCell = '';
		$this->m_aCurrRow = array();
		$this->m_iToSkip = $iToSkip;
		$this->m_aDataSet = array();

		$iDataLength = strlen($this->m_sCSVData);

		$iState = stSTARTING;
		$iTimeLimit = null;
		if ($this->m_iTimeLimitPerRow !== null)
		{
			// Give some time for the first row
			$iTimeLimit = ini_get('max_execution_time');
			set_time_limit(intval($this->m_iTimeLimitPerRow));
		}
		for($i = 0; $i <= $iDataLength ; $i++)
		{
			if ($i == $iDataLength)
			{
				$c = null;
				$iEvent = evEND;
			}
			else
			{
				$c = $this->m_sCSVData[$i];

				if ($c == $this->m_sSep)
				{
					$iEvent = evSEPARATOR;
				}
				elseif ($c == ' ')
				{
					$iEvent = evBLANK;
				}
				elseif ($c == "\t")
				{
					$iEvent = evBLANK;
				}
				elseif ($c == "\n")
				{
					$iEvent = evNEWLINE;
				}
				elseif ($c == $this->m_sTextQualifier)
				{
					$iEvent = evTEXTQUAL;
				}
				else
				{
					$iEvent = evOTHERCHAR;
				}
			}

			$sAction = $aTransitions[$iState][$iEvent][0];
			$iState = $aTransitions[$iState][$iEvent][1];

			if (!empty($sAction))
			{
				$aCallSpec = array($this, $sAction);
				if (is_callable($aCallSpec))
				{
					call_user_func($aCallSpec, $c, $aFieldMap);
				}
				else
				{
					throw new CSVParserException("CSVParser: unknown verb '$sAction'");
				}
			}

			$iLineCount = count($this->m_aDataSet);
			if (($iMax > 0) && ($iLineCount >= $iMax)) break;
		}
		if ($iTimeLimit !== null)
		{
			// Restore the previous time limit
			set_time_limit(intval($iTimeLimit));
		}
		return $this->m_aDataSet;
	}

	public function ListFields()
	{
		$aHeader = $this->ToArray(0, null, 1);
		return $aHeader[0];
	}
}


?>
