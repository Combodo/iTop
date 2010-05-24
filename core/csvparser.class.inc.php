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
 * CSV parser
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */


class CSVParserException extends CoreException
{
}

define('stSTARTING', 1); //grey zone: the type is undetermined
define('stRAW', 2); //building a non-qualified string
define('stQUALIFIED', 3); //building qualified string
define('stESCAPED', 4); //just encountered an escape char

define('evSEPARATOR', 1);
define('evNEWLINE', 2);
define('evTEXTQUAL', 3); // used for escaping as well
define('evOTHERCHAR', 4);


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

	public function __construct($sTxt, $sSep = ',', $sTextQualifier = '"')
	{
		$this->m_sCSVData = str_replace("\r\n", "\n", $sTxt);
		$this->m_sSep = $sSep;
		$this->m_sTextQualifier = $sTextQualifier;
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
	protected function __AddCell($c = null, $aFieldMap = null)
	{
		if (!is_null($aFieldMap))
		{
			$iNextCol = count($this->m_aCurrRow);
			$iNextName = $aFieldMap[$iNextCol];
			$this->m_aCurrRow[$iNextName] = $this->m_sCurrCell;
		}
		else
		{
			$this->m_aCurrRow[] = $this->m_sCurrCell;
		}
		$this->m_sCurrCell = '';
	}
	protected function __AddRow($c = null, $aFieldMap = null)
	{
		$this->__AddCell($c, $aFieldMap);

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
	}

	function ToArray($iToSkip = 1, $aFieldMap = null, $iMax = 0)
	{
		$aTransitions = array();

		$aTransitions[stSTARTING][evSEPARATOR] = array('__AddCell', stSTARTING);
		$aTransitions[stSTARTING][evNEWLINE] = array('__AddRow', stSTARTING);
		$aTransitions[stSTARTING][evTEXTQUAL] = array('', stQUALIFIED);
		$aTransitions[stSTARTING][evOTHERCHAR] = array('__AddChar', stRAW);

		$aTransitions[stRAW][evSEPARATOR] = array('__AddCell', stSTARTING);
		$aTransitions[stRAW][evNEWLINE] = array('__AddRow', stSTARTING);
		$aTransitions[stRAW][evTEXTQUAL] = array('__AddChar', stRAW);
		$aTransitions[stRAW][evOTHERCHAR] = array('__AddChar', stRAW);

		$aTransitions[stQUALIFIED][evSEPARATOR] = array('__AddChar', stQUALIFIED);
		$aTransitions[stQUALIFIED][evNEWLINE] = array('__AddChar', stQUALIFIED);
		$aTransitions[stQUALIFIED][evTEXTQUAL] = array('', stESCAPED);
		$aTransitions[stQUALIFIED][evOTHERCHAR] = array('__AddChar', stQUALIFIED);

		$aTransitions[stESCAPED][evSEPARATOR] = array('__AddCell', stSTARTING);
		$aTransitions[stESCAPED][evNEWLINE] = array('__AddRow', stSTARTING);
		$aTransitions[stESCAPED][evTEXTQUAL] = array('__AddChar', stQUALIFIED);
		$aTransitions[stESCAPED][evOTHERCHAR] = array('__AddChar', stSTARTING);

		// Reset parser variables
		$this->m_sCurrCell = '';
		$this->m_aCurrRow = array();
		$this->m_iToSkip = $iToSkip;
		$this->m_aDataSet = array();

		$iState = stSTARTING;
		for($i = 0; $i < strlen($this->m_sCSVData) ; $i++)
		{
			$c = $this->m_sCSVData[$i];

//			// Note: I did that because the unit test was not working fine (file edited with notepad: \n chars padded :-(
//			if (ord($c) == 0) continue;

			if ($c == $this->m_sSep)
			{
				$iEvent = evSEPARATOR;
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
		// Close the final line
		$this->__AddRow(null, $aFieldMap);
		return $this->m_aDataSet;
	}

	public function ListFields()
	{
		$aHeader = $this->ToArray(0, null, 1);
		return $aHeader[0];
	}
}


?>
