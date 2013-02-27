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
 * Class iPlugin
 * Management of application plugin 
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

interface iApplicationUIExtension
{
	public function OnDisplayProperties($oObject, WebPage $oPage, $bEditMode = false);
	public function OnDisplayRelations($oObject, WebPage $oPage, $bEditMode = false);
	public function OnFormSubmit($oObject, $sFormPrefix = '');
	public function OnFormCancel($sTempId); // temp id is made of session_id and transaction_id, it identifies the object in a unique way

	public function EnumUsedAttributes($oObject); // Not yet implemented
	public function GetIcon($oObject); // Not yet implemented
	public function GetHilightClass($oObject);

	public function EnumAllowedActions(DBObjectSet $oSet);
}

interface iApplicationObjectExtension
{
	public function OnIsModified($oObject);
	public function OnCheckToWrite($oObject);
	public function OnCheckToDelete($oObject);
	public function OnDBUpdate($oObject, $oChange = null);
	public function OnDBInsert($oObject, $oChange = null);
	public function OnDBDelete($oObject, $oChange = null);
}

/**
 * New extension to add menu items in the "popup" menus inside iTop. Provides a greater flexibility than
 * iApplicationUIExtension::EnumAllowedActions.
 * 
 * To add some menus into iTop, declare a class that implements this interface, it will be called automatically
 * by the application, as long as the class definition is included somewhere in the code
 */
interface iPopupMenuExtension
{
	// Possible types of menu into which new items can be added
	const MENU_OBJLIST_ACTIONS = 1; 	// $param is a DBObjectSet containing the list of objects
	const MENU_OBJLIST_TOOLKIT = 2;		// $param is a DBObjectSet containing the list of objects
	const MENU_OBJDETAILS_ACTIONS = 3;	// $param is a DBObject instance: the object currently displayed
	const MENU_DASHBOARD_ACTIONS = 4;	// $param is a Dashboard instance: the dashboard currently displayed
	const MENU_USER_ACTIONS = 5;		// $param is a null ??

	/**
	 * Get the list of items to be added to a menu. The items will be inserted in the menu in the order of the returned array
	 * @param int $iMenuId The identifier of the type of menu, as listed by the constants MENU_xxx above
	 * @param mixed $param Depends on $iMenuId, see the constants defined above
	 * @return Array An array of ApplicationPopupMenuItem or an empty array if no action is to be added to the menu
	 */
	public static function EnumItems($iMenuId, $param);
}

/**
 * Each menu items is defined by an instance of an object derived from the class
 * ApplicationPopupMenu below
 *
 */
abstract class ApplicationPopupMenuItem
{
	protected $sUID;
	protected $sLabel;
	
	public function __construct($sUID, $sLabel)
	{
		$this->sUID = $sUID;
		$this->sLabel = $sLabel;
	}
	
	public function GetUID()
	{
		return $this->sUID;
	}
	
	public function GetLabel()
	{
		return $this->sLabel;
	}
	
	/**
	 * Returns the components to create a popup menu item in HTML
	 * @return Hash A hash array: array('label' => , 'url' => , 'target' => , 'onclick' => )
	 */
	abstract public function GetMenuItem();

	public function GetLinkedScripts()
	{
		return array();
	}
}

/**
 * Class for adding an item into a popup menu that browses to the given URL
 */
class URLPopupMenuItem extends ApplicationPopupMenuItem
{
	protected $sURL;
	protected $sTarget;
	
	/**
	 * Class for adding an item that browses to the given URL
	 * @param string $sUID The unique identifier of this menu in iTop... make sure you pass something unique enough
	 * @param string $sLabel The display label of the menu (must be localized)
	 * @param string $sURL If the menu is an hyperlink, provide the absolute hyperlink here
	 * @param string $sTarget In case the menu is an hyperlink and a specific target is needed (_blank for example), pass it here
	 */
	public function __construct($sUID, $sLabel, $sURL, $sTarget = '_top')
	{
		parent::__construct($sUID, $sLabel);
		$this->sURL = $sURL;
		$this->sTarget = $sTarget;
	}
	
	public function GetMenuItem()
	{
		return array ('label' => $this->GetLabel(), 'url' => $this->sURL, 'target' => $this->sTarget);	
	}
}

/**
 * Class for adding an item into a popup menu that triggers some Javascript code
 */
class JSPopupMenuItem extends ApplicationPopupMenuItem
{
	protected $sJSCode;
	protected $aIncludeJSFiles;
	
	/**
	 * Class for adding an item that triggers some Javascript code
	 * @param string $sUID The unique identifier of this menu in iTop... make sure you pass something unique enough
	 * @param string $sLabel The display label of the menu (must be localized)
	 * @param string $sJSCode In case the menu consists in executing some havascript code inside the page, pass it here. If supplied $sURL ans $sTarget will be ignored
	 * @param array $aIncludeJSFiles An array of file URLs to be included (once) to provide some JS libraries for the page.
	 */
	public function __construct($sUID, $sLabel, $sJSCode, $aIncludeJSFiles = array())
	{
		parent::__construct($sUID, $sLabel);
		$this->sJSCode = $sJSCode;
		$this->aIncludeJSFiles = $aIncludeJSFiles;
	}
	
	public function GetMenuItem()
	{
		return array ('label' => $this->GetLabel(), 'onclick' => $this->sJSCode, 'url' => '#');
	}
	
	public function GetLinkedScripts()
	{
		return $this->aIncludeJSFiles;
	}
}

/**
 * Class for adding a separator (horizontal line, not selectable) the output
 * will automatically reduce several consecutive separators to just one
 */
class SeparatorPopupMenuItem extends ApplicationPopupMenuItem
{
	/**
	 * Class for inserting a separator into a popup menu
	 */
	public function __construct()
	{
		parent::__construct('', '');
	}
	
	public function GetMenuItem()
	{
		return array ('label' => '<hr class="menu-separator">', 'url' => '');
	}
}

/**
 * Implement this interface to add content to any iTopWebPage
 * There are 3 places where content can be added:
 * - The north pane: (normaly empty/hidden) at the top of the page, spanning the whole
 *   width of the page
 * - The south pane: (normaly empty/hidden) at the bottom of the page, spanning the whole
 *   width of the page
 * - The admin banner (two tones gray background) at the left of the global search.
 *   Limited space, use it for short messages
 * Each of the methods of this interface is supposed to return the HTML to be inserted at
 * the specified place and can use the passed iTopWebPage object to add javascript or CSS definitions
 *
 */
interface iPageUIExtension
{
	/**
	 * Add content to the North pane
	 * @param WebPage $oPage The page to insert stuff into.
	 * @return string The HTML content to add into the page
	 */
	public function GetNorthPaneHtml(iTopWebPage $oPage);
	/**
	 * Add content to the South pane
	 * @param WebPage $oPage The page to insert stuff into.
	 * @return string The HTML content to add into the page
	 */
	public function GetSouthPaneHtml(iTopWebPage $oPage);
	/**
	 * Add content to the "admin banner"
	 * @param WebPage $oPage The page to insert stuff into.
	 * @return string The HTML content to add into the page
	 */
	public function GetBannerHtml(iTopWebPage $oPage);
}

/**
 * Implement this interface to add new operations to the REST/JSON web service
 *  
 * @package     Extensibility
 * @api
 * @since 2.0.1  
 */
interface iRestServiceProvider
{
	/**
	 * Enumerate services delivered by this class
	 * @param string $sVersion The version (e.g. 1.0) supported by the services
	 * @return array An array of hash 'verb' => verb, 'description' => description
	 */
	public function ListOperations($sVersion);
	/**
	 * Enumerate services delivered by this class
	 * @param string $sVersion The version (e.g. 1.0) supported by the services
	 * @return RestResult The standardized result structure (at least a message)
	 * @throws Exception in case of internal failure.	 
	 */
	public function ExecOperation($sVersion, $sVerb, $aParams);
}

/**
 * Minimal REST response structure. Derive this structure to add response data and error codes.
 *
 * @package     Extensibility
 * @api
 * @since 2.0.1  
 */
class RestResult
{
	/**
	 * Result: no issue has been encountered
	 */
	const OK = 0;
	/**
	 * Result: missing/wrong credentials or the user does not have enough rights to perform the requested operation 
	 */
	const UNAUTHORIZED = 1;
	/**
	 * Result: the parameter 'version' is missing
	 */
	const MISSING_VERSION = 2;
	/**
	 * Result: the parameter 'json_data' is missing
	 */
	const MISSING_JSON = 3;
	/**
	 * Result: the input structure is not a valid JSON string
	 */
	const INVALID_JSON = 4;
	/**
	 * Result: no operation is available for the specified version
	 */
	const UNSUPPORTED_VERSION = 10;
	/**
	 * Result: the requested operation is not valid for the specified version
	 */
	const UNKNOWN_OPERATION = 11;
	/**
	 * Result: the operation could not be performed, see the message for troubleshooting
	 */
	const INTERNAL_ERROR = 100;

	/**
	 * Default constructor - ok!
	 * 	 
	 * @param DBObject $oObject The object being reported
	 * @param string $sAttCode The attribute code (must be valid)
	 * @return string A scalar representation of the value
	 */
	public function __construct()
	{
		$this->code = RestResult::OK;
	}

	public $code;
	public $message;
}

/**
 * Helpers for implementing REST services
 *
 * @package     Extensibility
 * @api
 */
class RestUtils
{
	/**
	 * Registering tracking information. Any further object modification be associated with the given comment, when the modification gets recorded into the DB
	 * 	 
	 * @param StdClass $oData Structured input data. Must contain 'comment'.
	 * @return void
	 * @throws Exception
	 * @api
	 */
	public static function InitTrackingComment($oData)
	{
		$sComment = self::GetMandatoryParam($oData, 'comment');
		CMDBObject::SetTrackInfo($sComment);
	}

	/**
	 * Read a mandatory parameter from  from a Rest/Json structure.
	 * 	 
	 * @param StdClass $oData Structured input data. Must contain the entry defined by sParamName.
	 * @param string $sParamName Name of the parameter to fetch from the input data
	 * @return void
	 * @throws Exception If the parameter is missing
	 * @api
	 */
	public static function GetMandatoryParam($oData, $sParamName)
	{
		if (isset($oData->$sParamName))
		{
			return $oData->$sParamName;
		}
		else
		{
			throw new Exception("Missing parameter '$sParamName'");
		}
	}


	/**
	 * Read an optional parameter from  from a Rest/Json structure.
	 * 	 
	 * @param StdClass $oData Structured input data.
	 * @param string $sParamName Name of the parameter to fetch from the input data
	 * @param mixed $default Default value if the parameter is not found in the input data
	 * @return void
	 * @throws Exception
	 * @api
	 */
	public static function GetOptionalParam($oData, $sParamName, $default)
	{
		if (isset($oData->$sParamName))
		{
			return $oData->$sParamName;
		}
		else
		{
			return $default;
		}
	}


	/**
	 * Read a class  from a Rest/Json structure.
	 *
	 * @param StdClass $oData Structured input data. Must contain the entry defined by sParamName.
	 * @param string $sParamName Name of the parameter to fetch from the input data
	 * @return void
	 * @throws Exception If the parameter is missing or the class is unknown
	 * @api
	 */
	public static function GetClass($oData, $sParamName)
	{
		$sClass = self::GetMandatoryParam($oData, $sParamName);
		if (!MetaModel::IsValidClass($sClass))
		{
			throw new Exception("$sParamName: '$sClass' is not a valid class'");
		}
		return $sClass;
	}


	/**
	 * Read a list of attribute codes from a Rest/Json structure.
	 * 	 
	 * @param string $sClass Name of the class
	 * @param StdClass $oData Structured input data.
	 * @param string $sParamName Name of the parameter to fetch from the input data
	 * @return void
	 * @throws Exception
	 * @api
	 */
	public static function GetFieldList($sClass, $oData, $sParamName)
	{
		$sFields = self::GetOptionalParam($oData, $sParamName, '*');
		$aShowFields = array();
		if ($sFields == '*')
		{
			foreach (MetaModel::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
			{
				$aShowFields[] = $sAttCode;
			}
		}
		else
		{
			foreach(explode(',', $sFields) as $sAttCode)
			{
				$sAttCode = trim($sAttCode);
				if (($sAttCode != 'id') && (!MetaModel::IsValidAttCode($sClass, $sAttCode)))
				{
					throw new Exception("$sParamName: invalid attribute code '$sAttCode'");
				}
				$aShowFields[] = $sAttCode;
			}
		}
		return $aShowFields;
	}

	/**
	 * Read and interpret object search criteria from a Rest/Json structure
	 * 	  	 
	 * @param string $sClass Name of the class
	 * @param StdClass $oCriteria Hash of attribute code => value (can be a substructure or a scalar, depending on the nature of the attriute)
	 * @return object The object found
	 * @throws Exception If the input structure is not valid or it could not find exactly one object
	 */
	protected static function FindObjectFromCriteria($sClass, $oCriteria)
	{
		$aCriteriaReport = array();
		if (isset($oCriteria->finalclass))
		{
			$sClass = $oCriteria->finalclass;
			if (!MetaModel::IsValidClass($sClass))
			{
				throw new Exception("finalclass: Unknown class '$sClass'");
			}
		}
		$oSearch = new DBObjectSearch($sClass);
		foreach ($oCriteria as $sAttCode => $value)
		{
			$realValue = self::MakeValue($sClass, $sAttCode, $value);
			$oSearch->AddCondition($sAttCode, $realValue);
			$aCriteriaReport[] = "$sAttCode: $value ($realValue)";
		}
		$oSet = new DBObjectSet($oSearch);
		$iCount = $oSet->Count();
		if ($iCount == 0)
		{
			throw new Exception("No item found with criteria: ".implode(', ', $aCriteriaReport));
		}
		elseif ($iCount > 1)
		{
			throw new Exception("Several items found ($iCount) with criteria: ".implode(', ', $aCriteriaReport));
		}
		$res = $oSet->Fetch();
		return $res;
	}


	/**
	 * Find an object from a polymorph search specification (Rest/Json)
	 * 	 
	 * @param string $sClass Name of the class
	 * @param mixed $key Either search criteria (substructure), or an object or an OQL string.
	 * @return DBObject The object found
	 * @throws Exception If the input structure is not valid or it could not find exactly one object
	 * @api
	 */
	public static function FindObjectFromKey($sClass, $key)
	{
		if (is_object($key))
		{
			$res = self::FindObjectFromCriteria($sClass, $key);
		}
		elseif (is_numeric($key))
		{
			$res = MetaModel::GetObject($sClass, $key);
		}
		elseif (is_string($key))
		{
			// OQL
			$oSearch = DBObjectSearch::FromOQL($key);
			$oSet = new DBObjectSet($oSearch);
			$iCount = $oSet->Count();
			if ($iCount == 0)
			{
				throw new Exception("No item found for query: $key");
			}
			elseif ($iCount > 1)
			{
				throw new Exception("Several items found ($iCount) for query: $key");
			}
			$res = $oSet->Fetch();
		}
		else
		{
			throw new Exception("Wrong format for key");
		}
		return $res;
	}

	/**
	 * Search objects from a polymorph search specification (Rest/Json)
	 * 	 
	 * @param string $sClass Name of the class
	 * @param mixed $key Either search criteria (substructure), or an object or an OQL string.
	 * @return DBObjectSet The search result set
	 * @throws Exception If the input structure is not valid
	 */
	public static function GetObjectSetFromKey($sClass, $key)
	{
		if (is_object($key))
		{
			if (isset($oCriteria->finalclass))
			{
				$sClass = $oCriteria->finalclass;
				if (!MetaModel::IsValidClass($sClass))
				{
					throw new Exception("finalclass: Unknown class '$sClass'");
				}
			}
		
			$oSearch = new DBObjectSearch($sClass);
			foreach ($key as $sAttCode => $value)
			{
				$realValue = self::MakeValue($sClass, $sAttCode, $value);
				$oSearch->AddCondition($sAttCode, $realValue);
			}
		}
		elseif (is_numeric($key))
		{
			$oSearch = new DBObjectSearch($sClass);
			$oSearch->AddCondition('id', $key);
		}
		elseif (is_string($key))
		{
			// OQL
			$oSearch = DBObjectSearch::FromOQL($key);
			$oObjectSet = new DBObjectSet($oSearch);
		}
		else
		{
			throw new Exception("Wrong format for key");
		}
		$oObjectSet = new DBObjectSet($oSearch);
		return $oObjectSet;
	}

	/**
	 * Interpret the Rest/Json value and get a valid attribute value
	 * 	 
	 * @param string $sClass Name of the class
	 * @param string $sAttCode Attribute code
	 * @param mixed $value Depending on the type of attribute (a scalar, or search criteria, or list of related objects...)
	 * @return mixed The value that can be used with DBObject::Set()
	 * @throws Exception If the specification of the value is not valid.
	 * @api
	 */
	public static function MakeValue($sClass, $sAttCode, $value)
	{
		try
		{
			if (!MetaModel::IsValidAttCode($sClass, $sAttCode))
			{
				throw new Exception("Unknown attribute");
			}
			$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
			if ($oAttDef instanceof AttributeExternalKey)
			{
				$oExtKeyObject = self::FindObjectFromKey($oAttDef->GetTargetClass(), $value);
				$value = $oExtKeyObject->GetKey();
			}
			elseif ($oAttDef instanceof AttributeLinkedSet)
			{
				if (!is_array($value))
				{
					throw new Exception("A link set must be defined by an array of objects");
				}
				$sLnkClass = $oAttDef->GetLinkedClass();
				$aLinks = array();
				foreach($value as $oValues)
				{
					$oLnk = self::MakeObjectFromFields($sLnkClass, $oValues);
					$aLinks[] = $oLnk;
				}
				$value = DBObjectSet::FromArray($sLnkClass, $aLinks);
			}
		}
		catch (Exception $e)
		{
			throw new Exception("$sAttCode: ".$e->getMessage(), $e->getCode());
		}
		return $value;
	}

	/**
	 * Interpret a Rest/Json structure that defines attribute values, and build an object
	 * 	 
	 * @param string $sClass Name of the class
	 * @param array $aFields A hash of attribute code => value specification.
	 * @return DBObject The newly created object
	 * @throws Exception If the specification of the values is not valid
	 * @api
	 */
	public static function MakeObjectFromFields($sClass, $aFields)
	{
		$oObject = MetaModel::NewObject($sClass);
		foreach ($aFields as $sAttCode => $value)
		{
			$realValue = self::MakeValue($sClass, $sAttCode, $value);
			$oObject->Set($sAttCode, $realValue);
		}
		return $oObject;
	}

	/**
	 * Interpret a Rest/Json structure that defines attribute values, and update the given object
	 * 	 
	 * @param DBObject $oObject The object being modified
	 * @param array $aFields A hash of attribute code => value specification.
	 * @return DBObject The object modified
	 * @throws Exception If the specification of the values is not valid
	 * @api
	 */
	public static function UpdateObjectFromFields($oObject, $aFields)
	{
		$sClass = get_class($oObject);
		foreach ($aFields as $sAttCode => $value)
		{
			$realValue = self::MakeValue($sClass, $sAttCode, $value);
			$oObject->Set($sAttCode, $realValue);
		}
		return $oObject;
	}
}
