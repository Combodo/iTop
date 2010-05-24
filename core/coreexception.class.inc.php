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
 * Exception management
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */



class SecurityException extends CoreException
{
}

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
							$aPairs[] = $key.'=>('.implode(', ', $val).')';
						}
						else
						{
							$aPairs[] = $key.'=>'.$val;
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

	public function getTraceAsHtml()
	{
		$aBackTrace = $this->getTrace();
		return MyHelpers::get_callstack_html(0, $this->getTrace());
		// return "<pre>\n".$this->getTraceAsString()."</pre>\n";
	}

	public function addInfo($sKey, $value)
	{
		$this->m_aContextData[$sKey] = $value;
	}

	public function getIssue()
	{
		return $this->m_sIssue;
	}
	public function getImpact()
	{
		return $this->m_sImpact;
	}
	public function getContextData()
	{
		return $this->m_aContextData;
	}
}

class CoreWarning extends CoreException
{
}

?>
