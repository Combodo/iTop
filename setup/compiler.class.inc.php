<?php
// Copyright (C) 2011-2012 Combodo SARL
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


require_once(APPROOT.'setup/setuputils.class.inc.php');

class DOMFormatException extends Exception
{
}

/**
 * Compiler class
 */ 
class MFCompiler
{
	protected $oFactory;

	protected $aRootClasses;
	protected $aLog;

	public function __construct($oModelFactory)
	{
		$this->oFactory = $oModelFactory;

		$this->aLog = array();
	}

	protected function Log($sText)
	{
		$this->aLog[] = $sText;
	}

	protected function DumpLog($oPage)
	{
		foreach ($this->aLog as $sText)
		{
			$oPage->p($sText);
		}
	}
	
	public function GetLog()
	{
		return $this->aLog;
	}
	
	public function Compile($sTargetDir, $oP = null, $bUseSymbolicLinks = false)
	{
		$aAllClasses = array(); // flat list of classes

		// Determine the target modules for the MENUS
		//
		$aMenuNodes = array();
		$aMenusByModule = array();
		foreach ($this->oFactory->ListActiveChildNodes('menus', 'menu') as $oMenuNode)
		{
			$sMenuId = $oMenuNode->getAttribute('id');
			$aMenuNodes[$sMenuId] = $oMenuNode;

			$sModuleMenu = $oMenuNode->getAttribute('_created_in');
			$aMenusByModule[$sModuleMenu][] = $sMenuId;
		}

		// Determine the target module (exactly one!) for USER RIGHTS
		//
		$sUserRightsModule = '';
		$oUserRightsNode = $this->oFactory->GetNodes('user_rights')->item(0);
		if ($oUserRightsNode)
		{
			$sUserRightsModule = $oUserRightsNode->getAttribute('_created_in');
			$this->Log("User Rights module found: $sUserRightsModule");
		}

		// List root classes
		//
		$this->aRootClasses = array();
		foreach ($this->oFactory->ListRootClasses() as $oClass)
		{
			$this->Log("Root class (with child classes): ".$oClass->getAttribute('id'));
			$this->aRootClasses[$oClass->getAttribute('id')] = $oClass;
		}

		// Compile, module by module
		//
		$aModules = $this->oFactory->GetLoadedModules();
		foreach($aModules as $foo => $oModule)
		{
			$sModuleName = $oModule->GetName();
			$sModuleVersion = $oModule->GetVersion();
		
			$sModuleRootDir = realpath($oModule->GetRootDir());
			$sRelativeDir = basename($sModuleRootDir);
		
			// Push the other module files
			SetupUtils::copydir($sModuleRootDir, $sTargetDir.'/'.$sRelativeDir, $bUseSymbolicLinks);

			$sCompiledCode = '';

			$oClasses = $this->oFactory->ListClasses($sModuleName);
			$iClassCount = $oClasses->length;
			if ($iClassCount == 0)
			{
				$this->Log("Found module without classes declared: $sModuleName");
			}
			else
			{
				foreach($oClasses as $oClass)
				{
					$sClass = $oClass->getAttribute("id");
					$aAllClasses[] = $sClass;
					try
					{
						$sCompiledCode .= $this->CompileClass($oClass, $sTargetDir, $sRelativeDir, $oP);
					}
					catch (DOMFormatException $e)
					{
						throw new Exception("Failed to process class '$sClass', from '$sModuleRootDir': ".$e->getMessage());
					}
				}
			}

			if (!array_key_exists($sModuleName, $aMenusByModule))
			{
				$this->Log("Found module without menus declared: $sModuleName");
			}
			else
			{
				$sCompiledCode .=
<<<EOF

//
// Menus
//

global \$__comp_menus__; // ensure that the global variable is indeed global !

EOF;
				// Preliminary: determine parent menus not defined within the current module
				$aMenusToLoad = array();
				$aParentMenus = array();
				foreach($aMenusByModule[$sModuleName] as $sMenuId)
				{
					$oMenuNode = $aMenuNodes[$sMenuId];
					if ($sParent = $oMenuNode->GetChildText('parent', null))
					{
						$aMenusToLoad[] = $sParent;
						$aParentMenus[] = $sParent;
					}
					// Note: the order matters: the parents must be defined BEFORE
					$aMenusToLoad[] = $sMenuId;
				}
				$aMenusToLoad = array_unique($aMenusToLoad);
				foreach($aMenusToLoad as $sMenuId)
				{
					$oMenuNode = $aMenuNodes[$sMenuId];
					if ($oMenuNode->getAttribute("xsi:type") == 'MenuGroup')
					{
						// Note: this algorithm is wrong
						// 1 - the module may appear empty in the current module, while children are defined in other modules
						// 2 - check recursively that child nodes are not empty themselves
						// Future algorithm:
						// a- browse the modules and build the menu tree
						// b- browse the tree and blacklist empty menus
						// c- before compiling, discard if blacklisted
						if (!in_array($oMenuNode->getAttribute("id"), $aParentMenus))
						{
							// Discard empty menu groups
							continue;
						}
					}
					try
					{
						$sCompiledCode .= $this->CompileMenu($oMenuNode, $sTargetDir, $sRelativeDir, $oP);
					}
					catch (DOMFormatException $e)
					{
						throw new Exception("Failed to process menu '$sMenuId', from '$sModuleRootDir': ".$e->getMessage());
					}
				}
			}

			// User rights
			//
			if ($sModuleName == $sUserRightsModule)
			{
				$sCompiledCode .= $this->CompileUserRights($oUserRightsNode);
			}

			// Create (overwrite if existing) the compiled file
			//
			if (strlen($sCompiledCode) > 0)
			{
				// We have compiled something: write the result file
				//
				$sResultFile = $sTargetDir.'/'.$sRelativeDir.'/model.'.$sModuleName.'.php';
				if (is_file($sResultFile))
				{
					$this->Log("Updating $sResultFile for module $sModuleName in version $sModuleVersion ($iClassCount classes)");
				}
				else
				{
					$sResultDir = dirname($sResultFile);
					if (!is_dir($sResultDir))
					{
						$this->Log("Creating directory $sResultDir");
						mkdir($sResultDir, 0777, true);
					}
					$this->Log("Creating $sResultFile for module $sModuleName in version $sModuleVersion ($iClassCount classes)");
				}

				// Compile the module into a single file
				//
				$sId = $sModuleName;
				$sCurrDate = date(DATE_ISO8601);
				$sAuthor = 'iTop compiler';
				$sLicence = 'http://opensource.org/licenses/AGPL-3.0';
				$sFileHeader =
<<<EOF
<?php
//
// File generated by ... on the $sCurrDate
// Please do not edit manually
//

/**
 * Classes and menus for $sModuleName (version $sModuleVersion)
 *
 * @author      $sAuthor
 * @license     $sLicence
 */

EOF;
				$ret = file_put_contents($sResultFile, $sFileHeader.$sCompiledCode);
				if ($ret === false)
				{
					$iLen = strlen($sFileHeader.$sCompiledCode);
					$fFree = @disk_free_space(dirname($sResultFile));
					$aErr = error_get_last();
					throw new Exception("Failed to write '$sResultFile'. Last error: '{$aErr['message']}', content to write: $iLen bytes, available free space on disk: $fFree.");
				}
			}
			else
			{
					$this->Log("Compilation of module $sModuleName in version $sModuleVersion produced not code at all. No file written.");
			}
		} // foreach module

		// Compile the dictionaries -out of the modules
		//
		$sDictDir = $sTargetDir.'/dictionaries';
		if (!is_dir($sDictDir))
		{
			$this->Log("Creating directory $sDictDir");
			mkdir($sDictDir, 0777, true);
		}

		$oDictionaries = $this->oFactory->ListActiveChildNodes('dictionaries', 'dictionary');
		foreach($oDictionaries as $oDictionaryNode)
		{
			$this->CompileDictionary($oDictionaryNode, $sTargetDir);
		}
	}

	/**
	 * Helper to form a valid ZList from the array built by GetNodeAsArrayOfItems()
	 */	 	
	protected function ArrayOfItemsToZList(&$aItems)
	{
		$aTransformed = array();
		foreach ($aItems as $key => $value)
		{
			if (is_null($value))
			{
				$aTransformed[] = $key;
			}
			else
			{
				if (is_array($value))
				{
					$this->ArrayOfItemsToZList($value);
				}
				$aTransformed[$key] = $value;
			}
		}
		$aItems = $aTransformed;
	}

	/**
	 * Helper to format the flags for an attribute, in a given state
	 * @param object $oAttNode DOM node containing the information to build the flags
	 * Returns string PHP flags, based on the OPT_ATT_ constants, or empty (meaning 0, can be omitted)
	 */ 
	protected function FlagsToPHP($oAttNode)
	{
		static $aNodeAttributeToFlag = array(
			'mandatory' => 'OPT_ATT_MANDATORY',
			'read_only' => 'OPT_ATT_READONLY',
			'must_prompt' => 'OPT_ATT_MUSTPROMPT',
			'must_change' => 'OPT_ATT_MUSTCHANGE',
			'hidden' => 'OPT_ATT_HIDDEN',
		);
	
		$aFlags = array();
		foreach ($aNodeAttributeToFlag as $sNodeAttribute => $sFlag)
		{
			$bFlag = ($oAttNode->GetOptionalElement($sNodeAttribute) != null);
			if ($bFlag)
			{
				$aFlags[] = $sFlag;
			}
		}
		$sRes = implode(' | ', $aFlags);
		return $sRes;
	}

	/**
	 * Helper to format the tracking level for linkset (direct or indirect attributes)
	 * @param string $sTrackingLevel Value set from within the XML
	 * Returns string PHP flag
	 */ 
	protected function TrackingLevelToPHP($sTrackingLevel)
	{
		static $aXmlToPHP = array(
			'none' => 'LINKSET_TRACKING_NONE',
			'list' => 'LINKSET_TRACKING_LIST',
			'details' => 'LINKSET_TRACKING_DETAILS',
			'all' => 'LINKSET_TRACKING_ALL',
		);
	
		if (!array_key_exists($sTrackingLevel, $aXmlToPHP))
		{
			throw new DOMFormatException("Tracking level: unknown value '$sTrackingLevel'");
		}
		return $aXmlToPHP[$sTrackingLevel];
	}

	/**
	 * Helper to format the edit-mode for direct linkset
	 * @param string $sEditMode Value set from within the XML
	 * Returns string PHP flag
	 */ 
	protected function EditModeToPHP($sEditMode)
	{
		static $aXmlToPHP = array(
			'none' => 'LINKSET_EDITMODE_NONE',
			'add_only' => 'LINKSET_EDITMODE_ADDONLY',
			'actions' => 'LINKSET_EDITMODE_ACTIONS',
			'in_place' => 'LINKSET_EDITMODE_INPLACE',
		);
	
		if (!array_key_exists($sEditMode, $aXmlToPHP))
		{
			throw new DOMFormatException("Edit mode: unknown value '$sTrackingLevel'");
		}
		return $aXmlToPHP[$sEditMode];
	}

	
	/**
	 * Format a path (file or url) as an absolute path or relative to the module or the app
	 */ 
	protected function PathToPHP($sPath, $sModuleRelativeDir, $bIsUrl = false)
	{
		if ($sPath == '')
		{
			$sPHP = "''";
		}
		elseif (substr($sPath, 0, 2) == '$$')
		{
			// Absolute
			$sPHP = self::QuoteForPHP(substr($sPath, 2));
		}
		elseif (substr($sPath, 0, 1) == '$')
		{
			// Relative to the application
			if ($bIsUrl)
			{
				$sPHP = "utils::GetAbsoluteUrlAppRoot().".self::QuoteForPHP(substr($sPath, 1));
			}
			else
			{
				$sPHP = "APPROOT.".self::QuoteForPHP(substr($sPath, 1));
			}
		}
		else
		{
			// Relative to the module
			if ($bIsUrl)
			{
				$sPHP = "utils::GetAbsoluteUrlAppRoot().".self::QuoteForPHP($sModuleRelativeDir.''.$sPath);
			}
			else
			{
				$sPHP = "dirname(__FILE__).'/$sPath'";
			}
		}
		return $sPHP;
	}

	protected function GetPropString($oNode, $sTag, $sDefault = null)
	{
		$val = $oNode->GetChildText($sTag);
		if (is_null($val))
		{
			if (is_null($sDefault))
			{
				return null;
			}
			else
			{
				$val = $sDefault;
			}
		}
		return "'".$val."'";
	}

	protected function GetPropBoolean($oNode, $sTag, $bDefault = null)
	{
		$val = $oNode->GetChildText($sTag);
		if (is_null($val))
		{
			if (is_null($bDefault))
			{
				return null;
			}
			else
			{
				return $bDefault ? 'true' : 'false';
			}
		}
		return $val == 'true' ? 'true' : 'false';
	}

	protected function GetPropNumber($oNode, $sTag, $nDefault = null)
	{
		$val = $oNode->GetChildText($sTag);
		if (is_null($val))
		{
			if (is_null($nDefault))
			{
				return null;
			}
			else
			{
				$val = $nDefault;
			}
		}
		return (string)$val;
	}

	/**
	 * Adds quotes and escape characters
	 */	 	
	protected function QuoteForPHP($sStr, $bSimpleQuotes = false)
	{
		if ($bSimpleQuotes)
		{
			$sEscaped = str_replace(array('\\', "'"), array('\\\\', "\\'"), $sStr);
			$sRet = "'$sEscaped'";
		}
		else
		{
			$sEscaped = str_replace(array('\\', '"', "\n"), array('\\\\', '\\"', '\\n'), $sStr);
			$sRet = '"'.$sEscaped.'"';
		}
		return $sRet;
	}

	protected function CompileClass($oClass, $sTargetDir, $sModuleRelativeDir, $oP)
	{
		$sClass = $oClass->getAttribute('id');
		$oProperties = $oClass->GetUniqueElement('properties');
	
		// Class caracteristics
		//
		$aClassParams = array();
		$aClassParams['category'] = $this->GetPropString($oProperties, 'category', '');
		$aClassParams['key_type'] = "'autoincrement'";
	
		if ($oNaming = $oProperties->GetOptionalElement('naming'))
		{
			$oNameAttributes = $oNaming->GetUniqueElement('attributes');
			$oAttributes = $oNameAttributes->getElementsByTagName('attribute');
			$aNameAttCodes = array();
			foreach($oAttributes as $oAttribute)
			{
				$aNameAttCodes[] = $oAttribute->getAttribute('id');
			}
			if (count($aNameAttCodes) > 1)
			{
				// New style...
				$sNameAttCode = "array('".implode("', '", $aNameAttCodes)."')";
			}
			elseif (count($aNameAttCodes) == 1)
			{
				// New style...
				$sNameAttCode = "'$aNameAttCodes[0]'";
			}
			else
			{
				$sNameAttCode = "''";
			}
		}
		else
		{
			$sNameAttCode = "''";
		}
		$aClassParams['name_attcode'] = $sNameAttCode;
	
		$oLifecycle = $oClass->GetOptionalElement('lifecycle');
		if ($oLifecycle)
		{
			$sStateAttCode = $oLifecycle->GetChildText('attribute');
		}
		else
		{
			$sStateAttCode = "";
		}
		$aClassParams['state_attcode'] = "'$sStateAttCode'";
	
		if ($oReconciliation = $oProperties->GetOptionalElement('reconciliation'))
		{
			$oReconcAttributes = $oReconciliation->getElementsByTagName('attribute');
			$aReconcAttCodes = array();
			foreach($oReconcAttributes as $oAttribute)
			{
				$aReconcAttCodes[] = $oAttribute->getAttribute('id');
			}
			$sReconcKeys = "array('".implode("', '", $aReconcAttCodes)."')";
		}
		else
		{
			$sReconcKeys = "array()";
		}
		$aClassParams['reconc_keys'] = $sReconcKeys;
	
		$aClassParams['db_table'] = $this->GetPropString($oProperties, 'db_table', '');
		$aClassParams['db_key_field'] = $this->GetPropString($oProperties, 'db_key_field', 'id');

		if (array_key_exists($sClass, $this->aRootClasses))
		{
			$sDefaultFinalClass = 'finalclass';
		}
		else
		{
			$sDefaultFinalClass = '';
		}
		$aClassParams['db_finalclass_field'] = $this->GetPropString($oProperties, 'db_final_class_field', $sDefaultFinalClass);
	
		if (($sDisplayTemplate = $oProperties->GetChildText('display_template')) && (strlen($sDisplayTemplate) > 0))
		{
			$sDisplayTemplate = $sModuleRelativeDir.'/'.$sDisplayTemplate;
			$aClassParams['display_template'] = "utils::GetAbsoluteUrlModulesRoot().'$sDisplayTemplate'";
		}
	
		if (($sIcon = $oProperties->GetChildText('icon')) && (strlen($sIcon) > 0))
		{
			$sIcon = $sModuleRelativeDir.'/'.$sIcon;
			$aClassParams['icon'] = "utils::GetAbsoluteUrlModulesRoot().'$sIcon'";
		}
		else // si <fileref ref="nnn">
		{
			$oIcon = $oProperties->GetOptionalElement('icon');
			if ($oIcon)
			{
				$oFileRef = $oIcon->GetOptionalElement('fileref');
				if ($oFileRef)
				{
					$iFileId = $oFileRef->getAttribute('ref');
					$sXPath = "/itop_design/files/file[@id='$iFileId']";
					$oNodes = $this->oFactory->GetNodes($sXPath);
					if ($oNodes->length == 0)
					{
						throw new DOMFormatException('Could not find the file with ref '.$iFileId);
					}

					$sName = $oNodes->item(0)->GetChildText('name');
					$sData = base64_decode($oNodes->item(0)->GetChildText('data'));
					$aPathInfo = pathinfo($sName);
					$sFile = 'icon-file'.$iFileId.'.'.$aPathInfo['extension'];
					$sFilePath = $sTargetDir.'/'.$sModuleRelativeDir.'/'.$sFile;
					file_put_contents($sFilePath, $sData);
					if (!file_exists($sFilePath))
					{
						throw new Exception('Could not write icon file '.$sFilePath);
					}
					$aClassParams['icon'] = "utils::GetAbsoluteUrlModulesRoot().'$sModuleRelativeDir/$sFile'";
				}
			}
		}
	
		$oOrder = $oProperties->GetOptionalElement('order');
		if ($oOrder)
		{
			$oColumnsNode = $oOrder->GetUniqueElement('columns');
			$oColumns = $oColumnsNode->getElementsByTagName('column');
			$aSortColumns = array();
			foreach($oColumns as $oColumn)
			{
				$aSortColumns[] = "'".$oColumn->getAttribute('id')."' => ".(($oColumn->getAttribute('ascending') == 'true') ? 'true' : 'false');
			}
			if (count($aSortColumns) > 0)
			{
				$aClassParams['order_by_default'] = "array(".implode(", ", $aSortColumns).")";
			}
		}
	

		// Finalize class params declaration
		//
		$aClassParamsPHP = array();
		foreach($aClassParams as $sKey => $sPHPValue)
		{
			$aClassParamsPHP[] = "			'$sKey' => $sPHPValue,";
		}
		$sClassParams = implode("\n", $aClassParamsPHP);
	
		// Comment on top of the class declaration
		//
		$sCodeComment = $oProperties->GetChildText('comment');
	
		// Fields
		//
		$sAttributes = '';
		foreach($this->oFactory->ListFields($oClass) as $oField)
		{
			// $oField
			$sAttCode = $oField->getAttribute('id');
			$sAttType = $oField->getAttribute('xsi:type');
	
			$aDependencies = array();
			$oDependencies = $oField->GetOptionalElement('dependencies');
			if (!is_null($oDependencies))
			{
				$oDepNodes = $oDependencies->getElementsByTagName('attribute');
				foreach($oDepNodes as $oDepAttribute)
				{
					$aDependencies[] = "'".$oDepAttribute->getAttribute('id')."'";
				}
			}
			$sDependencies = 'array('.implode(', ', $aDependencies).')';
	
			$aParameters = array();
	
			if ($sAttType == 'AttributeLinkedSetIndirect')
			{
				$aParameters['linked_class'] = $this->GetPropString($oField, 'linked_class', '');
				$aParameters['ext_key_to_me'] = $this->GetPropString($oField, 'ext_key_to_me', '');
				$aParameters['ext_key_to_remote'] = $this->GetPropString($oField, 'ext_key_to_remote', '');
				$aParameters['allowed_values'] = 'null';
				$aParameters['count_min'] = $this->GetPropNumber($oField, 'count_min', 0);
				$aParameters['count_max'] = $this->GetPropNumber($oField, 'count_max', 0);
				$aParameters['duplicates'] = $this->GetPropBoolean($oField, 'duplicates', false);
				$sTrackingLevel = $oField->GetChildText('tracking_level');
				if (!is_null($sTrackingLevel))
				{
					$aParameters['tracking_level'] = $this->TrackingLevelToPHP($sTrackingLevel);
				}
				$aParameters['depends_on'] = $sDependencies;
			}
			elseif ($sAttType == 'AttributeLinkedSet')
			{
				$aParameters['linked_class'] = $this->GetPropString($oField, 'linked_class', '');
				$aParameters['ext_key_to_me'] = $this->GetPropString($oField, 'ext_key_to_me', '');
				$aParameters['allowed_values'] = 'null';
				$aParameters['count_min'] = $this->GetPropNumber($oField, 'count_min', 0);
				$aParameters['count_max'] = $this->GetPropNumber($oField, 'count_max', 0);
				$sTrackingLevel = $oField->GetChildText('tracking_level');
				if (!is_null($sTrackingLevel))
				{
					$aParameters['tracking_level'] = $this->TrackingLevelToPHP($sTrackingLevel);
				}
				$sEditMode = $oField->GetChildText('edit_mode');
				if (!is_null($sEditMode))
				{
					$aParameters['edit_mode'] = $this->EditModeToPHP($sEditMode);
				}
				$aParameters['depends_on'] = $sDependencies;
			}
			elseif ($sAttType == 'AttributeExternalKey')
			{
				$aParameters['targetclass'] = $this->GetPropString($oField, 'target_class', '');
				// deprecated: $aParameters['jointype'] = 'null';
				if ($sOql = $oField->GetChildText('filter'))
				{
					$sEscapedOql = self::QuoteForPHP($sOql);
					$aParameters['allowed_values'] = "new ValueSetObjects($sEscapedOql)"; // or "new ValueSetObjects('SELECT xxxx')"
				}
				else
				{
					$aParameters['allowed_values'] = 'null'; // or "new ValueSetObjects('SELECT xxxx')"
				}
				$aParameters['sql'] = $this->GetPropString($oField, 'sql', '');
				$aParameters['is_null_allowed'] = $this->GetPropBoolean($oField, 'is_null_allowed', false);
				$aParameters['on_target_delete'] = $oField->GetChildText('on_target_delete');
				$aParameters['depends_on'] = $sDependencies;
				$aParameters['max_combo_length'] = $this->GetPropNumber($oField, 'max_combo_length');
				$aParameters['min_autocomplete_chars'] = $this->GetPropNumber($oField, 'min_autocomplete_chars');
				$aParameters['allow_target_creation'] = $this->GetPropBoolean($oField, 'allow_target_creation');
			}
			elseif ($sAttType == 'AttributeHierarchicalKey')
			{
				if ($sOql = $oField->GetChildText('filter'))
				{
					$sEscapedOql = self::QuoteForPHP($sOql);
					$aParameters['allowed_values'] = "new ValueSetObjects($sEscapedOql)"; // or "new ValueSetObjects('SELECT xxxx')"
				}
				else
				{
					$aParameters['allowed_values'] = 'null'; // or "new ValueSetObjects('SELECT xxxx')"
				}
				$aParameters['sql'] = $this->GetPropString($oField, 'sql', '');
				$aParameters['is_null_allowed'] = $this->GetPropBoolean($oField, 'is_null_allowed', false);
				$aParameters['on_target_delete'] = $oField->GetChildText('on_target_delete');
				$aParameters['depends_on'] = $sDependencies;
				$aParameters['max_combo_length'] = $this->GetPropNumber($oField, 'max_combo_length');
				$aParameters['min_autocomplete_chars'] = $this->GetPropNumber($oField, 'min_autocomplete_chars');
				$aParameters['allow_target_creation'] = $this->GetPropBoolean($oField, 'allow_target_creation');
			}
			elseif ($sAttType == 'AttributeExternalField')
			{
				$aParameters['allowed_values'] = 'null';
				$aParameters['extkey_attcode'] = $this->GetPropString($oField, 'extkey_attcode', '');
				$aParameters['target_attcode'] = $this->GetPropString($oField, 'target_attcode', '');
			}
			elseif ($sAttType == 'AttributeURL')
			{
				$aParameters['target'] = $this->GetPropString($oField, 'target', '');
				$aParameters['allowed_values'] = 'null';
				$aParameters['sql'] = $this->GetPropString($oField, 'sql', '');
				$aParameters['default_value'] = $this->GetPropString($oField, 'default_value', '');
				$aParameters['is_null_allowed'] = $this->GetPropBoolean($oField, 'is_null_allowed', false);
				$aParameters['depends_on'] = $sDependencies;
			}
			elseif ($sAttType == 'AttributeEnum')
			{
				$oValues = $oField->GetUniqueElement('values');
				$oValueNodes = $oValues->getElementsByTagName('value');
				$aValues = array();
				foreach($oValueNodes as $oValue)
				{
	//	new style...			$aValues[] = self::QuoteForPHP($oValue->textContent);
					$aValues[] = $oValue->textContent;
				}
	//	new style... $sValues = 'array('.implode(', ', $aValues).')';
				$sValues = '"'.implode(',', $aValues).'"';
				$aParameters['allowed_values'] = "new ValueSetEnum($sValues)";
				$aParameters['display_style'] = $this->GetPropString($oField, 'display_style', 'list');
				$aParameters['sql'] = $this->GetPropString($oField, 'sql', '');
				$aParameters['default_value'] = $this->GetPropString($oField, 'default_value', '');
				$aParameters['is_null_allowed'] = $this->GetPropBoolean($oField, 'is_null_allowed', false);
				$aParameters['depends_on'] = $sDependencies;
			}
			elseif ($sAttType == 'AttributeBlob')
			{
				$aParameters['is_null_allowed'] = $this->GetPropBoolean($oField, 'is_null_allowed', false);
				$aParameters['depends_on'] = $sDependencies;
			}
			elseif ($sAttType == 'AttributeStopWatch')
			{
				$oStates = $oField->GetUniqueElement('states');
				$oStateNodes = $oStates->getElementsByTagName('state');
				$aStates = array();
				foreach($oStateNodes as $oState)
				{
					$aStates[] = '"'.$oState->GetAttribute('id').'"';
				}
				$aParameters['states'] = 'array('.implode(', ', $aStates).')';

				$aParameters['goal_computing'] = $this->GetPropString($oField, 'goal', 'DefaultMetricComputer'); // Optional, no deadline by default
				$aParameters['working_time_computing'] = $this->GetPropString($oField, 'working_time', 'DefaultWorkingTimeComputer'); // Optional, defaults to 24x7

				$oThresholds = $oField->GetUniqueElement('thresholds');
				$oThresholdNodes = $oThresholds->getElementsByTagName('threshold');
				$aThresholds = array();
				foreach($oThresholdNodes as $oThreshold)
				{
					$iPercent = $this->GetPropNumber($oThreshold, 'percent');

					$oActions = $oThreshold->GetUniqueElement('actions');
					$oActionNodes = $oActions->getElementsByTagName('action');
					$aActions = array();
					foreach($oActionNodes as $oAction)
					{
						$oParams = $oAction->GetOptionalElement('params');
						$aActionParams = array();
						if ($oParams)
						{
							$oParamNodes = $oParams->getElementsByTagName('param');
							foreach($oParamNodes as $oParam)
							{
								$aActionParams[] = self::QuoteForPHP($oParam->textContent);
							}
						}
						$sActionParams = 'array('.implode(', ', $aActionParams).')';
						$sVerb = $this->GetPropString($oAction, 'verb');
						$aActions[] = "array('verb' => $sVerb, 'params' => $sActionParams)";
					}
					$sActions = 'array('.implode(', ', $aActions).')';
					$aThresholds[] = $iPercent." => array('percent' => $iPercent, 'actions' => $sActions)";
				}
				$aParameters['thresholds'] = 'array('.implode(', ', $aThresholds).')';
			}
			elseif ($sAttType == 'AttributeSubItem')
			{
				$aParameters['target_attcode'] = $this->GetPropString($oField, 'target_attcode');
				$aParameters['item_code'] = $this->GetPropString($oField, 'item_code');
			}
			else
			{
				$aParameters['allowed_values'] = 'null'; // or "new ValueSetEnum('SELECT xxxx')"
				$aParameters['sql'] = $this->GetPropString($oField, 'sql', '');
				$aParameters['default_value'] = $this->GetPropString($oField, 'default_value', '');
				$aParameters['is_null_allowed'] = $this->GetPropBoolean($oField, 'is_null_allowed', false);
				$aParameters['depends_on'] = $sDependencies;
			}

			// Optional parameters (more for historical reasons)
			// Added if present...
			//
			$aParameters['validation_pattern'] = $this->GetPropString($oField, 'validation_pattern');
			$aParameters['width'] = $this->GetPropNumber($oField, 'width');
			$aParameters['height'] = $this->GetPropNumber($oField, 'height');
			$aParameters['digits'] = $this->GetPropNumber($oField, 'digits');
			$aParameters['decimals'] = $this->GetPropNumber($oField, 'decimals');
	
			$aParams = array();
			foreach($aParameters as $sKey => $sValue)
			{
				if (!is_null($sValue))
				{
					$aParams[] = '"'.$sKey.'"=>'.$sValue;
				}
			}
			$sParams = implode(', ', $aParams);
			$sAttributes .= "		MetaModel::Init_AddAttribute(new $sAttType(\"$sAttCode\", array($sParams)));\n";
		}
	
		// Lifecycle
		//
		$sLifecycle = '';
		if ($oLifecycle)
		{
			$sLifecycle .= "\t\t// Lifecycle (status attribute: $sStateAttCode)\n";
			$sLifecycle .= "\t\t//\n";
	
			$oStimuli = $oLifecycle->GetUniqueElement('stimuli');
			foreach ($oStimuli->getElementsByTagName('stimulus') as $oStimulus)
			{
				$sStimulus = $oStimulus->getAttribute('id');
				$sStimulusClass = $oStimulus->getAttribute('xsi:type');
	
				$sLifecycle .= "		MetaModel::Init_DefineStimulus(new ".$sStimulusClass."(\"".$sStimulus."\", array()));\n";
			}
	
			$oStates = $oLifecycle->GetUniqueElement('states');
			foreach ($oStates->getElementsByTagName('state') as $oState)
			{
				$sState = $oState->getAttribute('id');
	
				$oInitialStatePath = $oState->GetOptionalElement('initial_state_path');
				if ($oInitialStatePath)
				{
					$aInitialStatePath = array();
					foreach ($oInitialStatePath->getElementsByTagName('state_ref') as $oIntermediateState)
					{
						$aInitialStatePath[] = "'".$oIntermediateState->GetText()."'";
					}
					$sInitialStatePath = 'Array('.implode(', ', $aInitialStatePath).')';
				}

				$sLifecycle .= "		MetaModel::Init_DefineState(\n";
				$sLifecycle .= "			\"".$sState."\",\n";
				$sLifecycle .= "			array(\n";
				$sLifecycle .= "				\"attribute_inherit\" => '',\n";
				$sLifecycle .= "				\"attribute_list\" => array(\n";

				$oFlags = $oState->GetUniqueElement('flags');
				foreach ($oFlags->getElementsByTagName('attribute') as $oAttributeNode)
				{
					$sFlags = $this->FlagsToPHP($oAttributeNode);
					if (strlen($sFlags) > 0)
					{
						$sAttCode = $oAttributeNode->GetAttribute('id');
						$sLifecycle .= "					'$sAttCode' => $sFlags,\n";
					}
				}

				$sLifecycle .= "				),\n";
				if (!is_null($oInitialStatePath))
				{
					$sLifecycle .= "				\"initial_state_path\" => $sInitialStatePath,\n";
				}
				$sLifecycle .= "			)\n";
				$sLifecycle .= "		);\n";
	
				$oTransitions = $oState->GetUniqueElement('transitions');
				foreach ($oTransitions->getElementsByTagName('transition') as $oTransition)
				{
					$sStimulus = $oTransition->GetChildText('stimulus');
					$sTargetState = $oTransition->GetChildText('target');
	
					$oActions = $oTransition->GetUniqueElement('actions');
					$aVerbs = array();
					foreach ($oActions->getElementsByTagName('action') as $oAction)
					{
						$sVerb = $oAction->GetChildText('verb');
						$aVerbs[] = "'$sVerb'";
					}
					$sActions = implode(', ', $aVerbs);
					$sLifecycle .= "		MetaModel::Init_DefineTransition(\"$sState\", \"$sStimulus\", array(\"target_state\"=>\"$sTargetState\", \"actions\"=>array($sActions), \"user_restriction\"=>null));\n";
				}
			}
		}
		
		// ZLists
		//
		$aListRef = array(
			'details' => 'details',
			'standard_search' => 'search',
			'list' => 'list'
		);
	
		$oPresentation = $oClass->GetUniqueElement('presentation');
		$sZlists = '';
		foreach ($aListRef as $sListCode => $sListTag)
		{
			$oListNode = $oPresentation->GetOptionalElement($sListTag);
			if ($oListNode)
			{
				$aAttributes = $oListNode->GetNodeAsArrayOfItems();
				$this->ArrayOfItemsToZList($aAttributes);
		
				$sZAttributes = var_export($aAttributes, true);
				$sZlists .= "		MetaModel::Init_SetZListItems('$sListCode', $sZAttributes);\n";
			}
		}
	
		// Methods
		$sMethods = "";
		$oMethods = $oClass->GetUniqueElement('methods');
		foreach($oMethods->getElementsByTagName('method') as $oMethod)
		{
			$sMethodCode = $oMethod->GetChildText('code');
			if ($sMethodComment = $oMethod->GetChildText('comment', null))
			{
				$sMethods .= "\n\t$sMethodComment\n".$sMethodCode."\n";
			}
			else
			{
				$sMethods .= "\n\n".$sMethodCode."\n";
			}		
		}
	
		// Let's make the whole class declaration
		//
		$sPHP = "\n\n$sCodeComment\n";
		$sParentClass = $oClass->GetChildText('php_parent');
		$oPhpParent = $oClass->GetUniqueElement('php_parent', false);
		if ($oPhpParent)
		{
			$sParentClass = $oPhpParent->GetChildText('name', '');
			if ($sParentClass == '')
			{
				throw new Exception("Failed to process class '".$oClass->getAttribute('id')."', from '$sRelativeDir':  missing required tag 'name' under 'php_parent'.");
			}
			$sIncludeFile = $oPhpParent->GetChildText('file', '');
			if ($sIncludeFile != '')
			{
				$sPHP .= "\nrequire_once('$sIncludeFile'); // Implementation of the class $sParentClass\n";
			}
//TODO fix this !!!
//			$sFullPath =  $this->sSourceDir.'/'.$sModuleRelativeDir.'/'.$sIncludeFile;
//			if (!file_exists($sFullPath))
//			{
//				throw new Exception("Failed to process class '".$oClass->getAttribute('id')."', from '$sModuleRelativeDir'. The required include file: '$sFullPath' does not exist.");
//			}
		}
		else
		{
			$sParentClass = $oClass->GetChildText('parent', 'DBObject');
		}
		if ($oProperties->GetChildText('abstract') == 'true')
		{
			$sPHP .= 'abstract class '.$oClass->getAttribute('id');
		}
		else
		{
			$sPHP .= 'class '.$oClass->getAttribute('id');
		}
		$sPHP .= " extends $sParentClass\n";
		$sPHP .=
<<<EOF
{
	public static function Init()
	{
		\$aParams = array
		(
$sClassParams
		);
		MetaModel::Init_Params(\$aParams);
		MetaModel::Init_InheritAttributes();
$sAttributes
$sLifecycle
$sZlists
	}

$sMethods
}
EOF;
		return $sPHP;
	}// function CompileClass()


	protected function CompileMenu($oMenu, $sTargetDir, $sModuleRelativeDir, $oP)
	{
		$sMenuId = $oMenu->getAttribute("id");
		$sMenuClass = $oMenu->getAttribute("xsi:type");

		$sParent = $oMenu->GetChildText('parent', null);
		if ($sParent)
		{
			$sParentSpec = "\$__comp_menus__['$sParent']->GetIndex()";
		}
		else
		{
			$sParentSpec = '-1';
		}

		$fRank = $oMenu->GetChildText('rank');
		switch($sMenuClass)
		{
		case 'WebPageMenuNode':
			$sUrl = $oMenu->GetChildText('url');
			$sUrlSpec = $this->PathToPHP($sUrl, $sModuleRelativeDir, true /* Url */);
			$sNewMenu = "new WebPageMenuNode('$sMenuId', $sUrlSpec, $sParentSpec, $fRank);";
			break;

		case 'DashboardMenuNode':
			$sTemplateFile = $oMenu->GetChildText('definition_file', '');
			if ($sTemplateFile != '')
			{
				$sTemplateSpec = $this->PathToPHP($sTemplateFile, $sModuleRelativeDir);
			}
			else
			{
				$oDashboardDefinition = $oMenu->GetOptionalElement('definition');
				if ($oDashboardDefinition == null)
				{
					throw(new DOMFormatException('Missing definition for Dashboard menu "'.$sMenuId.'" expecting either a tag "definition_file" or "definition".'));
				}
				$sFileName = strtolower(str_replace(array(':', '/', '\\', '*'), '_', $sMenuId)).'_dashboard_menu.xml';
				$sTemplateSpec = $this->PathToPHP($sFileName, $sModuleRelativeDir);
				
				$oXMLDoc = new DOMDocument('1.0', 'UTF-8');
				$oXMLDoc->formatOutput = true; // indent (must be loaded with option LIBXML_NOBLANKS)
				$oXMLDoc->preserveWhiteSpace = true; // otherwise the formatOutput option would have no effect
				
				$oRootNode = $oXMLDoc->createElement('dashboard'); // make sure that the document is not empty
				$oRootNode->setAttribute('xmlns:xsi', "http://www.w3.org/2001/XMLSchema-instance");
				$oXMLDoc->appendChild($oRootNode);
				foreach($oDashboardDefinition->childNodes as $oNode)
				{
					$oDefNode = $oXMLDoc->importNode($oNode, true); // layout, cells, etc Nodes and below
					$oRootNode->appendChild($oDefNode);
				}
				$oXMLDoc->save($sTargetDir.'/'.$sModuleRelativeDir.'/'.$sFileName);
			}
			$sNewMenu = "new DashboardMenuNode('$sMenuId', $sTemplateSpec, $sParentSpec, $fRank);";
			break;

		case 'TemplateMenuNode':
			$sTemplateFile = $oMenu->GetChildText('template_file');
			$sTemplateSpec = $this->PathToPHP($sTemplateFile, $sModuleRelativeDir);
			$sNewMenu = "new TemplateMenuNode('$sMenuId', $sTemplateSpec, $sParentSpec, $fRank);";
			break;

		case 'ShortcutContainerMenuNode':
			$sNewMenu = "new ShortcutContainerMenuNode('$sMenuId', $sParentSpec, $fRank);";
			break;

		case 'OQLMenuNode':
			$sOQL = self::QuoteForPHP($oMenu->GetChildText('oql'));
			$bSearch = ($oMenu->GetChildText('do_search') == '1') ? 'true' : 'false';
			$sNewMenu = "new OQLMenuNode('$sMenuId', $sOQL, $sParentSpec, $fRank, $bSearch);";
			break;

		case 'NewObjectMenuNode':
			$sClass = $oMenu->GetChildText('class');
			$sNewMenu = "new NewObjectMenuNode('$sMenuId', '$sClass', $sParentSpec, $fRank);";
			break;

		case 'SearchMenuNode':
			$sClass = $oMenu->GetChildText('class');
			$sNewMenu = "new SearchMenuNode('$sMenuId', '$sClass', $sParentSpec, $fRank);";
			break;

		case 'MenuGroup':
		default:
			if ($sEnableClass = $oMenu->GetChildText('enable_class'))
			{
				$sEnableAction = $oMenu->GetChildText('enable_action');
				$sEnablePermission = $oMenu->GetChildText('enable_permission');
				$sEnableStimulus = $oMenu->GetChildText('enable_stimulus');
				if (strlen($sEnableStimulus) > 0)
				{
					$sNewMenu = "new $sMenuClass('$sMenuId', $fRank, '$sEnableClass', $sEnableAction, $sEnablePermission, '$sEnableStimulus');";
				}
				else
				{
					$sNewMenu = "new $sMenuClass('$sMenuId', $fRank, '$sEnableClass', $sEnableAction, $sEnablePermission);";
				}
			}
			else
			{
				$sNewMenu = "new $sMenuClass('$sMenuId', $fRank);";
			}
		}

		$sIndent = '';
		$aPHPMenu = array("\$__comp_menus__['$sMenuId'] = $sNewMenu");
		if ($sAutoReload = $oMenu->GetChildText('auto_reload'))
		{
			$sAutoReload = self::QuoteForPHP($sAutoReload);
			$aPHPMenu[] = "\$__comp_menus__['$sMenuId']->SetParameters(array('auto_reload' => $sAutoReload));";
		}

		$sAdminOnly = $oMenu->GetChildText('enable_admin_only');
		if ($sAdminOnly && ($sAdminOnly == '1'))
		{
			$sPHP = $sIndent."if (UserRights::IsAdministrator())\n";
			$sPHP .= $sIndent."{\n";
			foreach($aPHPMenu as $sPHPLine)
			{
				$sPHP .= $sIndent."   $sPHPLine\n";
			}
			$sPHP .= $sIndent."}\n";
		}
		else
		{
			$sPHP = '';
			foreach($aPHPMenu as $sPHPLine)
			{
				$sPHP .= $sIndent.$sPHPLine."\n";
			}
		}

		return $sPHP;
	} // function CompileMenu

	/**
	 * Helper to compute the grant, taking any existing grant into account
	*/
	protected function CumulateGrant(&$aGrants, $sKey, $bGrant)
	{
		if (isset($aGrants[$sKey]))
		{
			if (!$bGrant)
			{
				$aGrants[$sKey] = false;
			}
		}
		else
		{
			$aGrants[$sKey] = $bGrant;
		}
	}

	protected function CompileUserRights($oUserRightsNode)
	{
		static $aActionsInShort = array(
			'read' => 'r',
			'bulk read' => 'br',
			'write' => 'w',
			'bulk write' => 'bw',
			'delete' => 'd',
			'bulk delete' => 'bd',
		);

		// Preliminary : create an index so that links will be taken into account implicitely
		$aLinkToClasses = array();
		$oClasses = $this->oFactory->ListAllClasses();
		foreach($oClasses as $oClass)
		{
			$bIsLink = false;
			$oProperties = $oClass->GetOptionalElement('properties');
			if ($oProperties)
			{
				$bIsLink = (bool) $this->GetPropNumber($oProperties, 'is_link', 0);
			}
			if ($bIsLink)
			{
				foreach($this->oFactory->ListFields($oClass) as $oField)
				{
					$sAttType = $oField->getAttribute('xsi:type');
		
					if (($sAttType == 'AttributeExternalKey') || ($sAttType == 'AttributeHierarchicalKey'))
					{
						$sOnTargetDel = $oField->GetChildText('on_target_delete');
						if ($sOnTargetDel == 'DEL_AUTO')
						{
							$sTargetClass = $oField->GetChildText('target_class');
							$aLinkToClasses[$oClass->getAttribute('id')][] = $sTargetClass;
						}
					}
				}
			}
		}

		// Groups
		//
		$aGroupClasses = array();
		$oGroups = $oUserRightsNode->GetUniqueElement('groups');
		foreach($oGroups->getElementsByTagName('group') as $oGroup)
		{
			$sGroupId = $oGroup->getAttribute("id");

			$aClasses = array();
			$oClasses = $oGroup->GetUniqueElement('classes');
			foreach($oClasses->getElementsByTagName('class') as $oClass)
			{
				
				$sClass = $oClass->getAttribute("id");
				$aClasses[] = $sClass;

				//$bSubclasses = $this->GetPropBoolean($oClass, 'subclasses', true);
				//if ($bSubclasses)...
			}

			$aGroupClasses[$sGroupId] = $aClasses;
		}

		// Profiles and grants
		//
		$aProfiles = array();
		// Hardcode the administrator profile
		$aProfiles[1] = array(
			'name' => 'Administrator',
			'description' => 'Has the rights on everything (bypassing any control)'
		); 

		$aGrants = array();
		$oProfiles = $oUserRightsNode->GetUniqueElement('profiles');
		foreach($oProfiles->getElementsByTagName('profile') as $oProfile)
		{
			$iProfile = $oProfile->getAttribute("id");
			$sName = $oProfile->GetChildText('name');
			$sDescription = $oProfile->GetChildText('description');

			$oGroups = $oProfile->GetUniqueElement('groups');
			foreach($oGroups->getElementsByTagName('group') as $oGroup)
			{
				$sGroupId = $oGroup->getAttribute("id");

				$aActions = array();
				$oActions = $oGroup->GetUniqueElement('actions');
				foreach($oActions->getElementsByTagName('action') as $oAction)
				{
					$sAction = $oAction->getAttribute("id");
					$sType = $oAction->getAttribute("xsi:type");
					$sGrant = $oAction->GetText();
					$bGrant = ($sGrant == 'allow');
					
					if ($sGroupId == '*')
					{
						$aGrantClasses = array('*');
					}
					else
					{
						$aGrantClasses = $aGroupClasses[$sGroupId];
					}
					foreach ($aGrantClasses as $sClass)
					{
						if ($sType == 'stimulus')
						{
							$this->CumulateGrant($aGrants, $iProfile.'_'.$sClass.'_s_'.$sAction, $bGrant);
							$this->CumulateGrant($aGrants, $iProfile.'_'.$sClass.'+_s_'.$sAction, $bGrant); // subclasses inherit this grant
						}
						else
						{
							$sAction = $aActionsInShort[$sType];
							$this->CumulateGrant($aGrants, $iProfile.'_'.$sClass.'_'.$sAction, $bGrant);
							$this->CumulateGrant($aGrants, $iProfile.'_'.$sClass.'+_'.$sAction, $bGrant); // subclasses inherit this grant
						}
					}
				}
			}

			$aProfiles[$iProfile] = array(
				'name' => $sName,
				'description' => $sDescription
			);
		}

		$sProfiles = var_export($aProfiles, true);
		$sGrants = var_export($aGrants, true);
		$sLinkToClasses = var_export($aLinkToClasses, true);

		$sPHP =
<<<EOF
//
// List of constant profiles
// - used by the class URP_Profiles at setup (create/update/delete records)
// - used by the addon UserRightsProfile to determine user rights
//
class ProfilesConfig
{
	protected static \$aPROFILES = $sProfiles;

	protected static \$aGRANTS = $sGrants;

	protected static \$aLINKTOCLASSES = $sLinkToClasses;

	public static function GetProfileActionGrant(\$iProfileId, \$sClass, \$sAction)
	{
		// Search for a grant, starting from the most explicit declaration,
		// then searching for less and less explicit declaration

		// 1 - The class itself
		// 
		\$sGrantKey = \$iProfileId.'_'.\$sClass.'_'.\$sAction;
		if (isset(self::\$aGRANTS[\$sGrantKey]))
		{
			return self::\$aGRANTS[\$sGrantKey];
		}

		// 2 - The parent classes, up to the root class
		// 
		foreach (MetaModel::EnumParentClasses(\$sClass, ENUM_PARENT_CLASSES_EXCLUDELEAF, false /*bRootFirst*/) as \$sParent)
		{
			\$sGrantKey = \$iProfileId.'_'.\$sParent.'+_'.\$sAction;
			if (isset(self::\$aGRANTS[\$sGrantKey]))
			{
				return self::\$aGRANTS[\$sGrantKey];
			}
		}

		// 3 - The related classes (if the current is an N-N link with AUTO_DEL)
		//
		if (array_key_exists(\$sClass, self::\$aLINKTOCLASSES))
		{
			// Get the grant for the remote classes. The resulting grant is:
			// - One YES => YES
			// - 100% undefined => undefined
			// - otherwise => NO
			//

			// Having write allowed on the remote class implies write + delete on the N-N link class
			if (\$sAction == 'd')
			{
				\$sRemoteAction = 'w';
			}
			elseif (\$sAction == 'bd')
			{
				\$sRemoteAction = 'bw';
			}
			else
			{
				\$sRemoteAction = \$sAction;
			}

			foreach (self::\$aLINKTOCLASSES[\$sClass] as \$sRemoteClass)
			{
				\$bUndefined = true;
				\$bGrant = self::GetProfileActionGrant(\$iProfileId, \$sRemoteClass, \$sAction);
				if (\$bGrant === true)
				{
					return true;
				}
				if (\$bGrant === false)
				{
					\$bUndefined = false;
				}
			}
			if (!\$bUndefined)
			{
				return false;
			}
		}

		// 4 - All
		// 
		\$sGrantKey = \$iProfileId.'_*_'.\$sAction;
		if (isset(self::\$aGRANTS[\$sGrantKey]))
		{
			return self::\$aGRANTS[\$sGrantKey];
		}

		// Still undefined for this class
		return null;
	}	

	public static function GetProfileStimulusGrant(\$iProfileId, \$sClass, \$sStimulus)
	{
		\$sGrantKey = \$iProfileId.'_'.\$sClass.'_s_'.\$sStimulus;
		if (isset(self::\$aGRANTS[\$sGrantKey]))
		{
			return self::\$aGRANTS[\$sGrantKey];
		}
		\$sGrantKey = \$iProfileId.'_*_s_'.\$sStimulus;
		if (isset(self::\$aGRANTS[\$sGrantKey]))
		{
			return self::\$aGRANTS[\$sGrantKey];
		}
		return null;
	}

	// returns an array of id => array of column => php value(so-called "real value")
	public static function GetProfilesValues()
	{
		return self::\$aPROFILES;
	}
}

EOF;
	return $sPHP;
	} // function CompileUserRights

	protected function CompileDictionary($oDictionaryNode, $sTargetDir)
	{
		$sLang = $oDictionaryNode->getAttribute('id');
		$sEnglishLanguageDesc = $oDictionaryNode->GetChildText('english_description');
		$sLocalizedLanguageDesc = $oDictionaryNode->GetChildText('localized_description');

		$aEntriesPHP = array();
		$oEntries = $oDictionaryNode->GetUniqueElement('entries');
		foreach($oEntries->getElementsByTagName('entry') as $oEntry)
		{
			$sStringCode = $oEntry->getAttribute('id');
			$sValue = $oEntry->GetText();
			$aEntriesPHP[] = "\t'$sStringCode' => ".self::QuoteForPHP($sValue, true).",";
		}
		$sEntriesPHP = implode("\n", $aEntriesPHP);

		$sEscEnglishLanguageDesc = self::QuoteForPHP($sEnglishLanguageDesc);
		$sEscLocalizedLanguageDesc = self::QuoteForPHP($sLocalizedLanguageDesc);
		$sPHPDict =
<<<EOF
<?php
//
// Dictionary built by the compiler for the language "$sLang"
//
Dict::Add('$sLang', $sEscEnglishLanguageDesc, $sEscLocalizedLanguageDesc, array(
$sEntriesPHP
));
EOF;
		$sSafeLang = str_replace(' ', '-', strtolower(trim($sLang)));
		$sDictFile = $sTargetDir.'/dictionaries/'.$sSafeLang.'.dict.php';
		file_put_contents($sDictFile, $sPHPDict);
	}
}

?>