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
 * Class ApplicationContext
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

require_once("../application/utils.inc.php");
/**
 * Helper class to store and manipulate the parameters that make the application's context
 *
 * Usage:
 * 1) Build the application's context by constructing the object
 *   (the object will read some of the page's parameters)
 *
 * 2) Add these parameters to hyperlinks or to forms using the helper, functions
 *    GetForLink(), GetForForm() or GetAsHash()
 */
class ApplicationContext
{
	protected $aNames;
	protected $aValues;
	protected static $aDefaultValues; // Cache shared among all instances
	
	public function __construct()
	{
		$this->aNames = array(
			'org_id', 'menu'
		);
		$this->ReadContext();
	}
	
	/**
	 * Read the context directly in the PHP parameters (either POST or GET)
	 * return nothing
	 */
	protected function ReadContext()
	{
		if (empty(self::$aDefaultValues))
		{
			self::$aDefaultValues = array();
			foreach($this->aNames as $sName)
			{
				$sValue = utils::ReadParam($sName, '');
				// TO DO: check if some of the context parameters are mandatory (or have default values)
				if (!empty($sValue))
				{
					self::$aDefaultValues[$sName] = $sValue;
				}
			}
		}
		$this->aValues = self::$aDefaultValues;
	}
	
	/**
	 * Returns the context as string with the format name1=value1&name2=value2....
	 * return string The context as a string to be appended to an href property
	 */
	public function GetForLink()
	{
		$aParams = array();
		foreach($this->aValues as $sName => $sValue)
		{
			$aParams[] = $sName.'='.urlencode($sValue);
		}
		return implode("&", $aParams);
	}
	
	/**
	 * Returns the context as sequence of input tags to be inserted inside a <form> tag
	 * return string The context as a sequence of <input type="hidden" /> tags
	 */
	public function GetForForm()
	{
		$sContext = "";
		foreach($this->aValues as $sName => $sValue)
		{
			$sContext .= "<input type=\"hidden\" name=\"$sName\" value=\"$sValue\" />\n";
		}
		return $sContext;
	}

	/**
	 * Returns the context as a hash array 'parameter_name' => value
	 * return array The context information
	 */
	public function GetAsHash()
	{
		return $this->aValues;
	}
	/**
	 * Removes the specified parameter from the context, for example when the same parameter
	 * is already a search parameter
	 * @param string $sParamName Name of the parameter to remove	 	 
	 * @return none
	 */	
	public function Reset($sParamName)
	{
		if (isset($this->aValues[$sParamName]))
		{
			unset($this->aValues[$sParamName]);
		}
	}
}
?>
