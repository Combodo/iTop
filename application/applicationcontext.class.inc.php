<?php
// Copyright (C) 2010-2012 Combodo SARL
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
 * Class ApplicationContext
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

require_once(APPROOT."/application/utils.inc.php");

/**
 * Interface for directing end-users to the relevant application
 */ 
interface iDBObjectURLMaker
{
	public static function MakeObjectURL($sClass, $iId);
}

/**
 * Direct end-users to the standard iTop application: UI.php
 */ 
class iTopStandardURLMaker implements iDBObjectURLMaker
{
	public static function MakeObjectURL($sClass, $iId)
	{
		$sPage = DBObject::ComputeStandardUIPage($sClass);
		$sAbsoluteUrl = utils::GetAbsoluteUrlAppRoot();
		$sUrl = "{$sAbsoluteUrl}pages/$sPage?operation=details&class=$sClass&id=$iId";
		return $sUrl;
	}
}

/**
 * Direct end-users to the standard Portal application
 */ 
class PortalURLMaker implements iDBObjectURLMaker
{
	public static function MakeObjectURL($sClass, $iId)
	{
		$sAbsoluteUrl = utils::GetAbsoluteUrlAppRoot();
		$sUrl = "{$sAbsoluteUrl}portal/index.php?operation=details&class=$sClass&id=$iId";
		return $sUrl;
	}
}


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
	
	public function __construct($bReadContext = true)
	{
		$this->aNames = array(
			'org_id', 'menu'
		);
		if ($bReadContext)
		{
			$this->ReadContext();			
		}

	}
	
	/**
	 * Read the context directly in the PHP parameters (either POST or GET)
	 * return nothing
	 */
	protected function ReadContext()
	{
		if (!isset(self::$aDefaultValues))
		{
			self::$aDefaultValues = array();
			$aContext = utils::ReadParam('c', array(), false, 'context_param');
			foreach($this->aNames as $sName)
			{
				$sValue = isset($aContext[$sName]) ? $aContext[$sName] : '';
				// TO DO: check if some of the context parameters are mandatory (or have default values)
				if (!empty($sValue))
				{
					self::$aDefaultValues[$sName] = $sValue;
				}
				// Hmm, there must be a better (more generic) way to handle the case below:
				// When there is only one possible (allowed) organization, the context must be
				// fixed to this org
				if ($sName == 'org_id')
				{
					if (MetaModel::IsValidClass('Organization'))
					{
						$oSearchFilter = new DBObjectSearch('Organization');
						$oSearchFilter->SetModifierProperty('UserRightsGetSelectFilter', 'bSearchMode', true);
						$oSet = new CMDBObjectSet($oSearchFilter);
						$iCount = $oSet->Count();
						if ($iCount == 1)
						{
							// Only one possible value for org_id, set it in the context
							$oOrg = $oSet->Fetch();
							self::$aDefaultValues[$sName] = $oOrg->GetKey();
						}
					}					
				}
			}
		}
		$this->aValues = self::$aDefaultValues;
	}
	
	/**
	 * Returns the current value for the given parameter
	 * @param string $sParamName Name of the parameter to read
	 * @return mixed The value for this parameter
	 */
	public function GetCurrentValue($sParamName, $defaultValue = '')
	{
		if (isset($this->aValues[$sParamName]))
		{
			return $this->aValues[$sParamName];
		}
		return $defaultValue;
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
			$aParams[] = "c[$sName]".'='.urlencode($sValue);
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
			$sContext .= "<input type=\"hidden\" name=\"c[$sName]\" value=\"".htmlentities($sValue, ENT_QUOTES, 'UTF-8')."\" />\n";
		}
		return $sContext;
	}

	/**
	 * Returns the context as a hash array 'parameter_name' => value
	 * return array The context information
	 */
	public function GetAsHash()
	{
		$aReturn = array();
		foreach($this->aValues as $sName => $sValue)
		{
			$aReturn["c[$sName]"] = $sValue;
		}
		return $aReturn;
	}
	
	/**
	 * Returns an array of the context parameters NAMEs
	 * @return array The list of context parameters
	 */
	public function GetNames()
	{
		return $this->aNames;
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

	/**
	 * Initializes the given object with the default values provided by the context
	 */
	public function InitObjectFromContext(DBObject &$oObj)
	{
		$sClass = get_class($oObj);
		foreach($this->GetNames() as $key)
		{
			$aCallSpec = array($sClass, 'MapContextParam');
			if (is_callable($aCallSpec))
			{
				$sAttCode = call_user_func($aCallSpec, $key); // Returns null when there is no mapping for this parameter					

				if (MetaModel::IsValidAttCode($sClass, $sAttCode))
				{
					$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
					if ($oAttDef->IsWritable())
					{
						$value = $this->GetCurrentValue($key, null);
						if (!is_null($value))
						{
							$oObj->Set($sAttCode, $value);
						}
					}
				}
			}
		}		
	}
	
	static $m_sUrlMakerClass = null;

	/**
	 * Set the current application url provider
	 * @param sClass string Class implementing iDBObjectURLMaker	 
	 * @return void
	 */
	public static function SetUrlMakerClass($sClass = 'iTopStandardURLMaker')
	{
		$sPrevious = self::GetUrlMakerClass();

		self::$m_sUrlMakerClass = $sClass;
		$_SESSION['UrlMakerClass'] = $sClass;

		return $sPrevious;
	}

	/**
	 * Get the current application url provider
	 * @return string the name of the class
	 */
	public static function GetUrlMakerClass()
	{
		if (is_null(self::$m_sUrlMakerClass))
		{
			if (isset($_SESSION['UrlMakerClass']))
			{
				self::$m_sUrlMakerClass = $_SESSION['UrlMakerClass'];
			}
			else
			{
				self::$m_sUrlMakerClass = 'iTopStandardURLMaker';
			}
		}
		return self::$m_sUrlMakerClass;
	}

	/**
	 * Get the current application url provider
	 * @return string the name of the class
	 */
   public static function MakeObjectUrl($sObjClass, $sObjKey, $sUrlMakerClass = null, $bWithNavigationContext = true)
   {
   	$oAppContext = new ApplicationContext();

      if (is_null($sUrlMakerClass))
      {
			$sUrlMakerClass = self::GetUrlMakerClass();
		}
		$sUrl = call_user_func(array($sUrlMakerClass, 'MakeObjectUrl'), $sObjClass, $sObjKey);
		if (strlen($sUrl) > 0)
		{
			if ($bWithNavigationContext)
			{
				return $sUrl."&".$oAppContext->GetForLink();
			}
			else
			{
				return $sUrl;
			}
		}
		else
		{
			return '';
		}	
	}

	protected static $m_aPluginProperties = null;

	/**
	 * Load plugin properties for the current session
	 * @return void
	 */
	protected static function LoadPluginProperties()
	{
		if (isset($_SESSION['PluginProperties']))
		{
			self::$m_aPluginProperties = $_SESSION['PluginProperties'];
		}
		else
		{
			self::$m_aPluginProperties = array();
		}
	}

	/**
	 * Set plugin properties
	 * @param sPluginClass string Class implementing any plugin interface
	 * @param sProperty string Name of the property
	 * @param value scalar Value (numeric or string)
	 * @return void
	 */
	public static function SetPluginProperty($sPluginClass, $sProperty, $value)
	{
		if (is_null(self::$m_aPluginProperties)) self::LoadPluginProperties();

		self::$m_aPluginProperties[$sPluginClass][$sProperty] = $value;
		$_SESSION['PluginProperties'][$sPluginClass][$sProperty] = $value;
	}

	/**
	 * Get plugin properties
	 * @param sPluginClass string Class implementing any plugin interface
	 * @return array of sProperty=>value pairs
	 */
	public static function GetPluginProperties($sPluginClass)
	{
		if (is_null(self::$m_aPluginProperties)) self::LoadPluginProperties();

		if (array_key_exists($sPluginClass, self::$m_aPluginProperties))
		{
			return self::$m_aPluginProperties[$sPluginClass];
		}
		else
		{
			return array();
		}
	}

}
?>
