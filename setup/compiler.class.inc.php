<?php
// Copyright (C) 2011-2013 Combodo SARL
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
		$sFinalTargetDir = $sTargetDir;
		if ($bUseSymbolicLinks)
		{
			// Skip the creation of a temporary dictionary, not compatible with symbolic links
			$sTempTargetDir = $sFinalTargetDir;
		}
		else
		{
			// Create a temporary directory
			// Once the compilation is 100% successful, then move the results into the target directory
			$sTempTargetDir = tempnam(SetupUtils::GetTmpDir(), 'itop-');
			unlink($sTempTargetDir); // I need a directory, not a file...
			SetupUtils::builddir($sTempTargetDir); // Here is the directory
		}

		try
		{
			$this->DoCompile($sTempTargetDir, $sFinalTargetDir, $oP = null, $bUseSymbolicLinks);
		}
		catch (Exception $e)
		{
			if ($sTempTargetDir != $sFinalTargetDir)
			{
				// Cleanup the temporary directory
				SetupUtils::rrmdir($sTempTargetDir);
			}
			throw $e;
		}

		if ($sTempTargetDir != $sFinalTargetDir)
		{
			// Move the results to the target directory
			SetupUtils::movedir($sTempTargetDir, $sFinalTargetDir);
		}
	}
	

	protected function DoCompile($sTempTargetDir, $sFinalTargetDir, $oP = null, $bUseSymbolicLinks = false)
	{
		$aAllClasses = array(); // flat list of classes

		// Determine the target modules for the MENUS
		//
		$aMenuNodes = array();
		$aMenusByModule = array();
		foreach ($this->oFactory->GetNodes('menus/menu') as $oMenuNode)
		{
			$sMenuId = $oMenuNode->getAttribute('id');
			$aMenuNodes[$sMenuId] = $oMenuNode;

			$sModuleMenu = $oMenuNode->getAttribute('_created_in');
			$aMenusByModule[$sModuleMenu][] = $sMenuId;
		}

		// Determine the target module (exactly one!) for USER RIGHTS
		// This used to be based solely on the module which created the user_rights node first
		// Unfortunately, our sample extension was delivered with the xml structure, resulting in the new module to be the recipient of the compilation
		// Then model.itop-profiles-itil would not exist... resulting in an error after the compilation (and the actual product of the compiler would never be included
		// The bullet proof implementation would be to compile in a separate directory as it has been done with the dictionaries... that's another story
		$aModules = $this->oFactory->GetLoadedModules();
		$sUserRightsModule = '';
		foreach($aModules as $foo => $oModule)
		{
			if ($oModule->GetName() == 'itop-profiles-itil')
			{
				$sUserRightsModule = 'itop-profiles-itil';
				break;
			}
		}
		$oUserRightsNode = $this->oFactory->GetNodes('user_rights')->item(0);
		if ($oUserRightsNode && ($sUserRightsModule == ''))
		{
			// Legacy algorithm (itop <= 2.0.3)
			$sUserRightsModule = $oUserRightsNode->getAttribute('_created_in');
		}
		$this->Log("User Rights module found: '$sUserRightsModule'");

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
		
			$sModuleRootDir = $oModule->GetRootDir();
			if ($sModuleRootDir != '')
			{
				$sModuleRootDir = realpath($sModuleRootDir);
				$sRelativeDir = basename($sModuleRootDir);
				// Push the other module files
				SetupUtils::copydir($sModuleRootDir, $sTempTargetDir.'/'.$sRelativeDir, $bUseSymbolicLinks);
			}

			$sCompiledCode = '';

			$oConstants = $this->oFactory->ListConstants($sModuleName);
			if ($oConstants->length > 0)
			{
				foreach($oConstants as $oConstant)
				{
					$sCompiledCode .= $this->CompileConstant($oConstant)."\n";
				}
			}

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
						$sCompiledCode .= $this->CompileClass($oClass, $sTempTargetDir, $sFinalTargetDir, $sRelativeDir, $oP);
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
				$sMenuCreationClass = 'MenuCreation_'.preg_replace('/[^A-Za-z0-9_]/', '_', $sModuleName);
				$sCompiledCode .=
<<<EOF

//
// Menus
//
class $sMenuCreationClass extends ModuleHandlerAPI
{
	public static function OnMenuCreation()
	{
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
				$aMenusForAll = array();
				$aMenusForAdmins = array();
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
						$aMenuLines = $this->CompileMenu($oMenuNode, $sTempTargetDir, $sFinalTargetDir, $sRelativeDir, $oP);
					}
					catch (DOMFormatException $e)
					{
						throw new Exception("Failed to process menu '$sMenuId', from '$sModuleRootDir': ".$e->getMessage());
					}
					if ($oMenuNode->GetChildText('enable_admin_only') == '1')
					{
						$aMenusForAdmins = array_merge($aMenusForAdmins, $aMenuLines);
					}
					else
					{
						$aMenusForAll = array_merge($aMenusForAll, $aMenuLines);
					}
				}
				$sIndent = "\t\t";
				foreach ($aMenusForAll as $sPHPLine)
				{
					$sCompiledCode .= $sIndent.$sPHPLine."\n";
				}
				if (count($aMenusForAdmins) > 0)
				{
					$sCompiledCode .= $sIndent."if (UserRights::IsAdministrator())\n";
					$sCompiledCode .= $sIndent."{\n";
					foreach ($aMenusForAdmins as $sPHPLine)
					{
						$sCompiledCode .= $sIndent."\t".$sPHPLine."\n";
					}
					$sCompiledCode .= $sIndent."}\n";
				}
				$sCompiledCode .=
<<<EOF
	}
} // class $sMenuCreationClass
EOF;
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
				$sResultFile = $sTempTargetDir.'/'.$sRelativeDir.'/model.'.$sModuleName.'.php';
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
		$sDictDir = $sTempTargetDir.'/dictionaries';
		if (!is_dir($sDictDir))
		{
			$this->Log("Creating directory $sDictDir");
			mkdir($sDictDir, 0777, true);
		}

		$oDictionaries = $this->oFactory->GetNodes('dictionaries/dictionary');
		foreach($oDictionaries as $oDictionaryNode)
		{
			$this->CompileDictionary($oDictionaryNode, $sTempTargetDir, $sFinalTargetDir);
		}

		// Compile the branding
		//
		$oBrandingNode = $this->oFactory->GetNodes('branding')->item(0);
		$this->CompileBranding($oBrandingNode, $sTempTargetDir, $sFinalTargetDir);

	} // DoCompile()

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
		if (empty($aFlags))
		{
			$aFlags[] = 'OPT_ATT_NORMAL'; // When no flag is defined, reset the state to "normal"	
		}
		$sRes = implode(' | ', $aFlags);
		return $sRes;
	}

	/**
	 * Helper to format the tracking level for linkset (direct or indirect attributes)
	 * @param string $sTrackingLevel Value set from within the XML
	 * Returns string PHP flag
	 */ 
	protected function TrackingLevelToPHP($sAttType, $sTrackingLevel)
	{
		static $aXmlToPHP_Links = array(
			'none' => 'LINKSET_TRACKING_NONE',
			'list' => 'LINKSET_TRACKING_LIST',
			'details' => 'LINKSET_TRACKING_DETAILS',
			'all' => 'LINKSET_TRACKING_ALL',
		);
	
		static $aXmlToPHP_Others = array(
			'none' => 'ATTRIBUTE_TRACKING_NONE',
			'all' => 'ATTRIBUTE_TRACKING_ALL',
		);

		switch ($sAttType)
		{
		case 'AttributeLinkedSetIndirect':
		case 'AttributeLinkedSet':
			$aXmlToPHP = $aXmlToPHP_Links;
			break;

		default:
			$aXmlToPHP = $aXmlToPHP_Others;
		}

		if (!array_key_exists($sTrackingLevel, $aXmlToPHP))
		{
			throw new DOMFormatException("Tracking level: unknown value '$sTrackingLevel', expecting a value in {".implode(', ', array_keys($aXmlToPHP))."}");
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
			'add_remove' => 'LINKSET_EDITMODE_ADDREMOVE',
		);
	
		if (!array_key_exists($sEditMode, $aXmlToPHP))
		{
			throw new DOMFormatException("Edit mode: unknown value '$sEditMode'");
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
	
	protected function GetMandatoryPropString($oNode, $sTag)
	{
		$val = $oNode->GetChildText($sTag);
		if (!is_null($val) && ($val !== ''))
		{
			return "'".$val."'";
		}
		else
		{
			throw new DOMFormatException("missing (or empty) mandatory tag '$sTag' under the tag '".$oNode->nodeName."'");
		}	
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

	protected function CompileConstant($oConstant)
	{
		$sName = $oConstant->getAttribute('id');
		$sType = $oConstant->getAttribute('xsi:type');
		$sText = $oConstant->GetText(null);

		switch ($sType)
		{
		case 'integer':
			if (is_null($sText))
			{
				// No data given => null
				$sScalar = 'null';
			}
			else
			{
				$sScalar = (string)(int)$sText;
			}
			break;
		
		case 'float':
			if (is_null($sText))
			{
				// No data given => null
				$sScalar = 'null';
			}
			else
			{
				$sScalar = (string)(float)$sText;
			}
			break;
		
		case 'bool':
			if (is_null($sText))
			{
				// No data given => null
				$sScalar = 'null';
			}
			else
			{
				$sScalar = ($sText == 'true') ? 'true' : 'false';
			}
			break;

		case 'string':
		default:
			$sScalar = $this->QuoteForPHP($sText, true);
		}
		$sPHPDefine = "define('$sName', $sScalar);";
		return $sPHPDefine;
	}

	protected function CompileClass($oClass, $sTempTargetDir, $sFinalTargetDir, $sModuleRelativeDir, $oP)
	{
		$sClass = $oClass->getAttribute('id');
		$oProperties = $oClass->GetUniqueElement('properties');
	
		// Class caracteristics
		//
		$aClassParams = array();
		$aClassParams['category'] = $this->GetPropString($oProperties, 'category', '');
		$aClassParams['key_type'] = "'autoincrement'";
		if ((bool) $this->GetPropNumber($oProperties, 'is_link', 0))
		{
			$aClassParams['is_link'] = 'true';
		}

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
	
		$this->CompileFiles($oProperties, $sTempTargetDir.'/'.$sModuleRelativeDir, $sFinalTargetDir.'/'.$sModuleRelativeDir, '');
		if (($sIcon = $oProperties->GetChildText('icon')) && (strlen($sIcon) > 0))
		{
			$sIcon = $sModuleRelativeDir.'/'.$sIcon;
			$aClassParams['icon'] = "utils::GetAbsoluteUrlModulesRoot().'$sIcon'";
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

		if ($oIndexes = $oProperties->GetOptionalElement('indexes'))
		{
			$aIndexes = array();
			foreach($oIndexes->getElementsByTagName('index') as $oIndex)
			{
				$sIndexId = $oIndex->getAttribute('id');
				$oAttributes = $oIndex->GetUniqueElement('attributes');
				foreach($oAttributes->getElementsByTagName('attribute') as $oAttribute)
				{
					$aIndexes[$sIndexId][] = $oAttribute->getAttribute('id');
				}
			}
			$aClassParams['indexes'] = var_export($aIndexes, true);
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
			try
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
					$aParameters['linked_class'] = $this->GetMandatoryPropString($oField, 'linked_class', '');
					$aParameters['ext_key_to_me'] = $this->GetMandatoryPropString($oField, 'ext_key_to_me', '');
					$aParameters['ext_key_to_remote'] = $this->GetMandatoryPropString($oField, 'ext_key_to_remote', '');
					$aParameters['allowed_values'] = 'null';
					$aParameters['count_min'] = $this->GetPropNumber($oField, 'count_min', 0);
					$aParameters['count_max'] = $this->GetPropNumber($oField, 'count_max', 0);
					$aParameters['duplicates'] = $this->GetPropBoolean($oField, 'duplicates', false);
					$aParameters['depends_on'] = $sDependencies;
				}
				elseif ($sAttType == 'AttributeLinkedSet')
				{
					$aParameters['linked_class'] = $this->GetMandatoryPropString($oField, 'linked_class', '');
					$aParameters['ext_key_to_me'] = $this->GetMandatoryPropString($oField, 'ext_key_to_me', '');
					$aParameters['count_min'] = $this->GetPropNumber($oField, 'count_min', 0);
					$aParameters['count_max'] = $this->GetPropNumber($oField, 'count_max', 0);
					$sEditMode = $oField->GetChildText('edit_mode');
					if (!is_null($sEditMode))
					{
						$aParameters['edit_mode'] = $this->EditModeToPHP($sEditMode);
					}
					if ($sOql = $oField->GetChildText('filter'))
					{
						$sEscapedOql = self::QuoteForPHP($sOql);
						$aParameters['allowed_values'] = "new ValueSetObjects($sEscapedOql)";
					}
					else
					{
						$aParameters['allowed_values'] = 'null';
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
					$aParameters['sql'] = $this->GetMandatoryPropString($oField, 'sql', '');
					$aParameters['is_null_allowed'] = $this->GetPropBoolean($oField, 'is_null_allowed', false);
					$aParameters['on_target_delete'] = $oField->GetChildText('on_target_delete');
					$aParameters['depends_on'] = $sDependencies;
					$aParameters['max_combo_length'] = $this->GetPropNumber($oField, 'max_combo_length');
					$aParameters['min_autocomplete_chars'] = $this->GetPropNumber($oField, 'min_autocomplete_chars');
					$aParameters['allow_target_creation'] = $this->GetPropBoolean($oField, 'allow_target_creation');
					$aParameters['display_style'] = $this->GetPropString($oField, 'display_style', 'select');
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
					$aParameters['sql'] = $this->GetMandatoryPropString($oField, 'sql', '');
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
					$aParameters['extkey_attcode'] = $this->GetMandatoryPropString($oField, 'extkey_attcode', '');
					$aParameters['target_attcode'] = $this->GetMandatoryPropString($oField, 'target_attcode', '');
				}
				elseif ($sAttType == 'AttributeURL')
				{
					$aParameters['target'] = $this->GetPropString($oField, 'target', '');
					$aParameters['allowed_values'] = 'null';
					$aParameters['sql'] = $this->GetMandatoryPropString($oField, 'sql', '');
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
						//	new style... $aValues[] = self::QuoteForPHP($oValue->textContent);
						$aValues[] = $oValue->textContent;
					}
					//	new style... $sValues = 'array('.implode(', ', $aValues).')';
					$sValues = '"'.implode(',', $aValues).'"';
					$aParameters['allowed_values'] = "new ValueSetEnum($sValues)";
					$aParameters['display_style'] = $this->GetPropString($oField, 'display_style', 'list');
					$aParameters['sql'] = $this->GetMandatoryPropString($oField, 'sql', '');
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
					$aParameters['working_time_computing'] = $this->GetPropString($oField, 'working_time', ''); // Blank (different than DefaultWorkingTimeComputer)
	
					$oThresholds = $oField->GetUniqueElement('thresholds');
					$oThresholdNodes = $oThresholds->getElementsByTagName('threshold');
					$aThresholds = array();
					foreach($oThresholdNodes as $oThreshold)
					{
						$iPercent = (int)$oThreshold->getAttribute('id');
	
						$oHighlight = $oThreshold->GetUniqueElement('highlight', false);
						$sHighlight = '';
						if($oHighlight)
						{
							$sCode  = $oHighlight->GetChildText('code');
							$sPersistent =  $this->GetPropBoolean($oHighlight, 'persistent', false);
							$sHighlight = "'highlight' => array('code' => '$sCode', 'persistent' => $sPersistent), ";
						}
						
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
									$sParamType = $oParam->getAttribute('xsi:type');
									if ($sParamType == '')
									{
										$sParamType = 'string';
									}
									$aActionParams[] = "array('type' => '$sParamType', 'value' => ".self::QuoteForPHP($oParam->textContent).")";
								}
							}
							$sActionParams = 'array('.implode(', ', $aActionParams).')';
							$sVerb = $this->GetPropString($oAction, 'verb');
							$aActions[] = "array('verb' => $sVerb, 'params' => $sActionParams)";
						}
						$sActions = 'array('.implode(', ', $aActions).')';
						$aThresholds[] = $iPercent." => array('percent' => $iPercent, $sHighlight 'actions' => $sActions)";
					}
					$aParameters['thresholds'] = 'array('.implode(', ', $aThresholds).')';
				}
				elseif ($sAttType == 'AttributeSubItem')
				{
					$aParameters['target_attcode'] = $this->GetMandatoryPropString($oField, 'target_attcode');
					$aParameters['item_code'] = $this->GetMandatoryPropString($oField, 'item_code');
				}
				else
				{
					$aParameters['allowed_values'] = 'null'; // or "new ValueSetEnum('SELECT xxxx')"
					$aParameters['sql'] = $this->GetMandatoryPropString($oField, 'sql', '');
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
				$aParameters['always_load_in_tables'] = $this->GetPropBoolean($oField, 'always_load_in_tables', false);
				$sTrackingLevel = $oField->GetChildText('tracking_level');
				if (!is_null($sTrackingLevel))
				{
					$aParameters['tracking_level'] = $this->TrackingLevelToPHP($sAttType, $sTrackingLevel);
				}
		
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
			catch(Exception $e)
			{
				throw new DOMFormatException("Field: '$sAttCode', (type: $sAttType), ".$e->getMessage());	
			}
		}
	
		// Lifecycle
		//
		$sLifecycle = '';
		$sHighlightScale = '';
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
			$oHighlightScale = $oLifecycle->GetUniqueElement('highlight_scale', false);
			if ($oHighlightScale)
			{
				$sHighlightScale = "\t\t// Higlight Scale\n";
				$sHighlightScale .= "		MetaModel::Init_DefineHighlightScale( array(\n";
				
				$this->CompileFiles($oHighlightScale, $sTempTargetDir.'/'.$sModuleRelativeDir, $sFinalTargetDir.'/'.$sModuleRelativeDir, '');
				
				foreach ($oHighlightScale->getElementsByTagName('item') as $oItem)
				{
					$sItemCode = $oItem->getAttribute('id');
					$fRank = (float)$oItem->GetChildText('rank');
					$sColor = $oItem->GetChildText('color');
					if (($sIcon = $oItem->GetChildText('icon')) && (strlen($sIcon) > 0))
					{
						$sIcon = $sModuleRelativeDir.'/'.$sIcon;
						$sIcon = "utils::GetAbsoluteUrlModulesRoot().'$sIcon'";
					}
					else
					{
						$sIcon = "''";
					}
					switch($sColor)
					{
						// Known PHP constants: keep the literal value as-is
						case 'HILIGHT_CLASS_CRITICAL':
						case 'HIGHLIGHT_CLASS_CRITICAL':
						$sColor = 'HILIGHT_CLASS_CRITICAL';
						break;
						
						case 'HILIGHT_CLASS_OK':
						case 'HIGHLIGHT_CLASS_OK':
						$sColor = 'HILIGHT_CLASS_OK';
						break;
						
						case 'HIGHLIGHT_CLASS_WARNING':
						case 'HILIGHT_CLASS_WARNING':
						$sColor = 'HILIGHT_CLASS_WARNING';
						break;
						
						case 'HIGHLIGHT_CLASS_NONE':
						case 'HILIGHT_CLASS_NONE':
						$sColor = 'HILIGHT_CLASS_NONE';
						break;
						
						default:
						// Future extension, specify your own color??
						$sColor = "'".addslashes($sColor)."'";
					}
					$sHighlightScale .= "		    '$sItemCode' => array('rank' => $fRank, 'color' => $sColor, 'icon' => $sIcon),\n";
					
				}
				$sHighlightScale .= "		));\n";
			}
					
			$oStates = $oLifecycle->GetUniqueElement('states');
			$aStatesDependencies = array();
			$aStates = array();
			foreach ($oStates->getElementsByTagName('state') as $oState)
			{
				$aStatesDependencies[$oState->getAttribute('id')] = $oState->GetChildText('inherit_flags_from', '');
				$aStates[$oState->getAttribute('id')] = $oState;
			}
			$aStatesOrder = array();
			while (count($aStatesOrder) < count($aStatesDependencies))
			{
				$iResolved = 0;
				foreach($aStatesDependencies as $sState => $sInheritFrom)
				{
					if (is_null($sInheritFrom))
					{
						// Already recorded as resolved
						continue;
					}
					elseif ($sInheritFrom == '')
					{
						// Resolved
						$aStatesOrder[$sState] = $sInheritFrom;
						$aStatesDependencies[$sState] = null;
						$iResolved++;
					}
					elseif (isset($aStatesOrder[$sInheritFrom]))
					{
						// Resolved
						$aStatesOrder[$sState] = $sInheritFrom;
						$aStatesDependencies[$sState] = null;
						$iResolved++;
					}
				}
				if ($iResolved == 0)
				{
					// No change on this loop -> there are unmet dependencies
					$aRemainingDeps = array();
					foreach($aStatesDependencies as $sState => $sParentState)
					{
						if (strlen($sParentState) > 0)
						{
							$aRemainingDeps[] = $sState.' ('.$sParentState.')';
						}
					}
					throw new DOMFormatException("Could not solve inheritance for states: ".implode(', ', $aRemainingDeps));
				}
			}
			foreach ($aStatesOrder as $sState => $foo)
			{
				$oState = $aStates[$sState];
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
				$sAttributeInherit = $oState->GetChildText('inherit_flags_from', '');
				$sLifecycle .= "				\"attribute_inherit\" => '$sAttributeInherit',\n";
				$oHighlight = $oState->GetUniqueElement('highlight', false);
				if ($oHighlight)
				{
					$sCode = $oHighlight->GetChildText('code', '');
					if ($sCode != '')
					{
						$sLifecycle .= "				'highlight' => array('code' => '$sCode'),\n";
					}
					
				}
				
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
					$sStimulus = $oTransition->getAttribute('id');
					$sTargetState = $oTransition->GetChildText('target');
	
					$oActions = $oTransition->GetUniqueElement('actions');
					$aVerbs = array();
					foreach ($oActions->getElementsByTagName('action') as $oAction)
					{
						$sVerb = $oAction->GetChildText('verb');
						$oParams = $oAction->GetOptionalElement('params');
						$aActionParams = array();
						if ($oParams)
						{
							$oParamNodes = $oParams->getElementsByTagName('param');
							foreach($oParamNodes as $oParam)
							{
								$sParamType = $oParam->getAttribute('xsi:type');
								if ($sParamType == '')
								{
									$sParamType = 'string';
								}
								$aActionParams[] = "array('type' => '$sParamType', 'value' => ".self::QuoteForPHP($oParam->textContent).")";
							}
						}
						else
						{
							// Old (pre 2.1.0) format, when no parameter is specified, assume 1 parameter: reference sStimulusCode
							$aActionParams[] = "array('type' => 'reference', 'value' => 'sStimulusCode')";
						}
						$sActionParams = 'array('.implode(', ', $aActionParams).')';
						$aVerbs[] = "array('verb' => '$sVerb', 'params' => $sActionParams)";
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
				throw new Exception("Failed to process class '".$oClass->getAttribute('id')."', missing required tag 'name' under 'php_parent'.");
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
$sHighlightScale
$sZlists
	}

$sMethods
}
EOF;
		return $sPHP;
	}// function CompileClass()


	protected function CompileMenu($oMenu, $sTempTargetDir, $sFinalTargetDir, $sModuleRelativeDir, $oP)
	{
		$this->CompileFiles($oMenu, $sTempTargetDir.'/'.$sModuleRelativeDir, $sFinalTargetDir.'/'.$sModuleRelativeDir, $sModuleRelativeDir);

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

		$fRank = (float) $oMenu->GetChildText('rank');
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
				$oXMLDoc->save($sTempTargetDir.'/'.$sModuleRelativeDir.'/'.$sFileName);
			}
			$sNewMenu = "new DashboardMenuNode('$sMenuId', $sTemplateSpec, $sParentSpec, $fRank);";
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

		case 'TemplateMenuNode':
			$sTemplateFile = $oMenu->GetChildText('template_file');
			$sTemplateSpec = $this->PathToPHP($sTemplateFile, $sModuleRelativeDir);

			if ($sEnableClass = $oMenu->GetChildText('enable_class'))
			{
				$sEnableAction = $oMenu->GetChildText('enable_action', 'null');
				$sEnablePermission = $oMenu->GetChildText('enable_permission', 'UR_ALLOWED_YES');
				$sEnableStimulus = $oMenu->GetChildText('enable_stimulus');
				if ($sEnableStimulus != null)
				{
					$sNewMenu = "new TemplateMenuNode('$sMenuId', $sTemplateSpec, $sParentSpec, $fRank, '$sEnableClass', $sEnableAction, $sEnablePermission, '$sEnableStimulus');";
				}
				else
				{
					$sNewMenu = "new TemplateMenuNode('$sMenuId', $sTemplateSpec, $sParentSpec, $fRank, '$sEnableClass', $sEnableAction, $sEnablePermission);";
				}
			}
			else
			{
				$sNewMenu = "new TemplateMenuNode('$sMenuId', $sTemplateSpec, $sParentSpec, $fRank);";
			}
			break;

		case 'MenuGroup':
		default:
			if ($sEnableClass = $oMenu->GetChildText('enable_class'))
			{
				$sEnableAction = $oMenu->GetChildText('enable_action', 'null');
				$sEnablePermission = $oMenu->GetChildText('enable_permission', 'UR_ALLOWED_YES');
				$sEnableStimulus = $oMenu->GetChildText('enable_stimulus');
				if ($sEnableStimulus != null)
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

		$aPHPMenu = array("\$__comp_menus__['$sMenuId'] = $sNewMenu");
		if ($sAutoReload = $oMenu->GetChildText('auto_reload'))
		{
			$sAutoReload = self::QuoteForPHP($sAutoReload);
			$aPHPMenu[] = "\$__comp_menus__['$sMenuId']->SetParameters(array('auto_reload' => $sAutoReload));";
		}
		return $aPHPMenu;
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
						if (($sOnTargetDel == 'DEL_AUTO') || ($sOnTargetDel == 'DEL_SILENT'))
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
					if (strpos($sAction, 'action:') === 0)
					{
						$sType = 'action';
						$sActionCode = substr($sAction, strlen('action:'));
						$sActionCode = $aActionsInShort[$sActionCode];
					}
					else
					{
						$sType = 'stimulus';
						$sActionCode = substr($sAction, strlen('stimulus:'));
					}
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
							$this->CumulateGrant($aGrants, $iProfile.'_'.$sClass.'_s_'.$sActionCode, $bGrant);
							$this->CumulateGrant($aGrants, $iProfile.'_'.$sClass.'+_s_'.$sActionCode, $bGrant); // subclasses inherit this grant
						}
						else
						{
							$this->CumulateGrant($aGrants, $iProfile.'_'.$sClass.'_'.$sActionCode, $bGrant);
							$this->CumulateGrant($aGrants, $iProfile.'_'.$sClass.'+_'.$sActionCode, $bGrant); // subclasses inherit this grant
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

	// Now replaced by MetaModel::GetLinkClasses (working with 1.x)
	// This function could be deprecated
	public static function GetLinkClasses()
	{
		return self::\$aLINKTOCLASSES;
	}

	public static function GetProfileActionGrant(\$iProfileId, \$sClass, \$sAction)
	{
		\$bLegacyBehavior = MetaModel::GetConfig()->Get('user_rights_legacy');

		// Search for a grant, stoping if any deny is encountered (allowance implies the verification of all paths)
		\$bAllow = null;

		// 1 - The class itself
		// 
		\$sGrantKey = \$iProfileId.'_'.\$sClass.'_'.\$sAction;
		if (isset(self::\$aGRANTS[\$sGrantKey]))
		{
			\$bAllow = self::\$aGRANTS[\$sGrantKey];
			if (\$bLegacyBehavior) return \$bAllow;
			if (!\$bAllow) return false;
		}

		// 2 - The parent classes, up to the root class
		// 
		foreach (MetaModel::EnumParentClasses(\$sClass, ENUM_PARENT_CLASSES_EXCLUDELEAF, false /*bRootFirst*/) as \$sParent)
		{
			\$sGrantKey = \$iProfileId.'_'.\$sParent.'+_'.\$sAction;
			if (isset(self::\$aGRANTS[\$sGrantKey]))
			{
				\$bAllow = self::\$aGRANTS[\$sGrantKey];
				if (\$bLegacyBehavior) return \$bAllow;
				if (!\$bAllow) return false;
			}
		}

		// 3 - The related classes (if the current is an N-N link with DEL_AUTO/DEL_SILENT)
		//
		\$bGrant = self::GetLinkActionGrant(\$iProfileId, \$sClass, \$sAction);
		if (!is_null(\$bGrant))
		{
			\$bAllow = \$bGrant;
			if (\$bLegacyBehavior) return \$bAllow;
			if (!\$bAllow) return false;
		}

		// 4 - All
		// 
		\$sGrantKey = \$iProfileId.'_*_'.\$sAction;
		if (isset(self::\$aGRANTS[\$sGrantKey]))
		{
			\$bAllow = self::\$aGRANTS[\$sGrantKey];
			if (\$bLegacyBehavior) return \$bAllow;
			if (!\$bAllow) return false;
		}

		// null or true
		return \$bAllow;
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

	// Propagate the rights on classes onto the links themselves (the external keys must have DEL_AUTO or DEL_SILENT
	//
	protected static function GetLinkActionGrant(\$iProfileId, \$sClass, \$sAction)
	{
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
		return null;
	}
}

EOF;
	return $sPHP;
	} // function CompileUserRights

	protected function CompileDictionary($oDictionaryNode, $sTempTargetDir, $sFinalTargetDir)
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
		$sDictFile = $sTempTargetDir.'/dictionaries/'.$sSafeLang.'.dict.php';
		file_put_contents($sDictFile, $sPHPDict);
	}

	// Transform the file references into the corresponding filename (and create the file in the relevant directory)
	//
	protected function CompileFiles($oNode, $sTempTargetDir, $sFinalTargetDir, $sRelativePath)
	{
		$oFileRefs = $oNode->GetNodes(".//fileref");
		foreach ($oFileRefs as $oFileRef)
		{
			$sFileId = $oFileRef->getAttribute('ref');
			if ($sFileId !== '')
			{
				$oNodes = $this->oFactory->GetNodes("/itop_design/files/file[@id='$sFileId']");
				if ($oNodes->length == 0)
				{
					throw new DOMFormatException('Could not find the file with ref '.$sFileId);
				}
	
				$sName = $oNodes->item(0)->GetChildText('name');
				$sData = base64_decode($oNodes->item(0)->GetChildText('data'));
				$aPathInfo = pathinfo($sName);
				$sFile = $sFileId.'.'.$aPathInfo['extension'];
				$sFilePath = $sTempTargetDir.'/images/'.$sFile;
				@mkdir($sTempTargetDir.'/images');
				file_put_contents($sFilePath, $sData);
				if (!file_exists($sFilePath))
				{
					throw new Exception('Could not write icon file '.$sFilePath);
				}
				$oParentNode = $oFileRef->parentNode;
				$oParentNode->removeChild($oFileRef);
				
				$oTextNode = $oParentNode->ownerDocument->createTextNode($sRelativePath.'/images/'.$sFile);
				$oParentNode->appendChild($oTextNode);
			}
		}
	}


	protected function CompileLogo($oBrandingNode, $sTempTargetDir, $sFinalTargetDir, $sNodeName, $sTargetFile)
	{
		if (($sIcon = $oBrandingNode->GetChildText($sNodeName)) && (strlen($sIcon) > 0))
		{
			if (substr($sIcon, 0, 8) == 'branding')
			{
				$sSourceFile = $sTempTargetDir.'/'.$sIcon;
			}
			else
			{
				$sSourceFile = APPROOT.$sIcon;
			}
			$sTargetFile = $sTempTargetDir.'/branding/'.$sTargetFile.'.png';

			if (!file_exists($sSourceFile))
			{
				throw new Exception("Branding $sNodeName: could not find the file $sIcon ($sSourceFile)");
			}

			// Note: rename makes sense only when the file given as a file ref, otherwise it may be an item of the application (thus it must be kept there)
			copy($sSourceFile, $sTargetFile);
		}
	}

	protected function CompileBranding($oBrandingNode, $sTempTargetDir, $sFinalTargetDir)
	{
		if ($oBrandingNode)
		{
			// Transform file refs into files in the images folder
			SetupUtils::builddir($sTempTargetDir.'/branding');
			$this->CompileFiles($oBrandingNode, $sTempTargetDir.'/branding', $sFinalTargetDir.'/branding', 'branding');

			$this->CompileLogo($oBrandingNode, $sTempTargetDir, $sFinalTargetDir, 'main_logo', 'main-logo');
			$this->CompileLogo($oBrandingNode, $sTempTargetDir, $sFinalTargetDir, 'login_logo', 'login-logo');
			$this->CompileLogo($oBrandingNode, $sTempTargetDir, $sFinalTargetDir, 'portal_logo', 'portal-logo');

			// Cleanup the images directory (eventually made by CompileFiles)
			if (file_exists($sTempTargetDir.'/branding/images'))
			{
				SetupUtils::rrmdir($sTempTargetDir.'/branding/images');
			}
		}
	}
}

?>
