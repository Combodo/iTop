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
 * Class ApplicationContext
 *
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Application\Helper\Session;
use Combodo\iTop\Application\UI\Base\Component\Input\InputUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;
use Combodo\iTop\Application\UI\Base\UIBlock;

require_once(APPROOT."/application/utils.inc.php");

/**
 * Interface for directing end-users to the relevant application
 */
interface iDBObjectURLMaker
{
	/**
	 * @param string $sClass
	 * @param string $iId
	 *
	 * @return string
	 */
	public static function MakeObjectURL($sClass, $iId);
}

/**
 * Direct end-users to the standard iTop application: UI.php
 */ 
class iTopStandardURLMaker implements iDBObjectURLMaker
{
    /**
     * @param string $sClass
     * @param string $iId
     *
     * @return string
     * @throws \Exception
     */
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
    /**
     * @param string $sClass
     * @param string $iId
     *
     * @return string
     * @throws \Exception
     */
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
    public static $m_sUrlMakerClass = null;
    protected static $m_aPluginProperties = null;
    protected static $aDefaultValues; // Cache shared among all instances

	protected $aNames;
	protected $aValues;

    /**
     * ApplicationContext constructor.
     *
     * @param bool $bReadContext
     *
     * @throws \Exception
     */
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
     *
     * @throws \Exception
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
				// fixed to this org unless there is only one organization in the system then
				// no filter is applied
				if ($sName == 'org_id')
				{
					if (MetaModel::IsValidClass('Organization'))
					{
						$oSearchFilter = new DBObjectSearch('Organization');
						$oSet = new CMDBObjectSet($oSearchFilter);
						$iCount = $oSet->CountWithLimit(2);
						if ($iCount > 1)
						{
							$oSearchFilter->SetModifierProperty('UserRightsGetSelectFilter', 'bSearchMode', true);
							$oSet = new CMDBObjectSet($oSearchFilter);
							$iCount = $oSet->CountWithLimit(2);
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
		}
		$this->aValues = self::$aDefaultValues;
	}

    /**
     * Returns the current value for the given parameter
     *
     * @param string $sParamName Name of the parameter to read
     * @param string $defaultValue
     *
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
	 * @return string The context as a string to be appended to an href property
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
	 * @since 3.0.0 N°2534 - dashboard: bug with autorefresh that deactivates filtering on organisation
	 * Returns the params as c[menu]:..., c[org_id]:....
	 * @return string The params
	 */
	public function GetForPostParams()
	{
		return json_encode($this->aValues);
	}

	/**
	 * Returns the context as sequence of input tags to be inserted inside a <form> tag
	 *
	 * @return string The context as a sequence of <input type="hidden" /> tags
	 */
	public function GetForForm()
	{
		$sContext = "";
		foreach ($this->aValues as $sName => $sValue) {
			$sContext .= "<input type=\"hidden\" name=\"c[$sName]\" value=\"".utils::EscapeHtml($sValue)."\" />\n";
		}
		return $sContext;
	}
	/**
	 * Returns the context an array of input blocks
	 *
	 * @return array The context as a sequence of <input type="hidden" /> tags
	 * @since 3.0.0
	 */
	public function GetForUIForm()
	{
		$aContextInputBlocks = [];
		foreach ($this->aValues as $sName => $sValue) {
			$aContextInputBlocks[] = InputUIBlockFactory::MakeForHidden("c[$sName]", $sValue);
		}
		return $aContextInputBlocks;
	}

	/**
	 * Returns the context as sequence of input tags to be inserted inside a <form> tag
	 *
	 */
	public function GetForFormBlock(): UIBlock
	{
		$oContext = new UIContentBlock();
		foreach ($this->aValues as $sName => $sValue) {
			$oContext->AddSubBlock(InputUIBlockFactory::MakeForHidden('c[$sName]', utils::HtmlEntities($sValue)));
		}
		return $oContext;
	}

	/**
	 * Returns the context as a hash array 'parameter_name' => value
	 *
	 * @return array The context information
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
	 *
	 * @param \DBObject $oObj
	 *
	 * @throws \Exception
	 * @throws \CoreUnexpectedValue
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

	/**
	 * Set the current application url provider
	 * @param string $sClass Class implementing iDBObjectURLMaker
	 * @return string
	 */
	public static function SetUrlMakerClass($sClass = 'iTopStandardURLMaker')
	{
		$sPrevious = self::GetUrlMakerClass();

		self::$m_sUrlMakerClass = $sClass;
		Session::Set('UrlMakerClass', $sClass);

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
			if (Session::IsSet('UrlMakerClass'))
			{
				self::$m_sUrlMakerClass = Session::Get('UrlMakerClass');
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
	 *
	 * @param string $sObjClass
	 * @param string $sObjKey
	 * @param null $sUrlMakerClass
	 * @param bool $bWithNavigationContext
	 *
	 * @return string the name of the class
	 * @throws \Exception
	 */
   public static function MakeObjectUrl($sObjClass, $sObjKey, $sUrlMakerClass = null, $bWithNavigationContext = true)
   {
   	$oAppContext = new ApplicationContext();

        if (is_null($sUrlMakerClass)) {
	        $sUrlMakerClass = self::GetUrlMakerClass();
        }
		$sUrl = call_user_func(array($sUrlMakerClass, 'MakeObjectUrl'), $sObjClass, $sObjKey);
	   if (utils::StrLen($sUrl) > 0) {
		   if ($bWithNavigationContext) {
			   return $sUrl."&".$oAppContext->GetForLink();
		   } else {
			   return $sUrl;
		   }
	   } else {
		   return '';
	   }
	}

	/**
	 * Load plugin properties for the current session
	 * @return void
	 */
	protected static function LoadPluginProperties()
	{
		if (Session::IsSet('PluginProperties'))
		{
			self::$m_aPluginProperties = Session::Get('PluginProperties');
		}
		else
		{
			self::$m_aPluginProperties = array();
		}
	}

	/**
	 * Set plugin properties
	 * @param string $sPluginClass Class implementing any plugin interface
	 * @param string $sProperty Name of the property
	 * @param mixed $value Value (numeric or string)
	 * @return void
	 */
	public static function SetPluginProperty($sPluginClass, $sProperty, $value)
	{
		if (is_null(self::$m_aPluginProperties)) self::LoadPluginProperties();

		self::$m_aPluginProperties[$sPluginClass][$sProperty] = $value;
		Session::Set(['PluginProperties', $sPluginClass, $sProperty], $value);
	}

	/**
	 * Get plugin properties
	 * @param string $sPluginClass Class implementing any plugin interface
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
