<?php
// Copyright (C) 2011 Combodo SARL
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

class DOMFormatException extends Exception
{
}

/**
 * Compiler class
 */ 
class MFCompiler
{
	protected $oFactory;
	protected $sSourceDir;

	protected $aRootClasses;
	protected $aLog;

	public function __construct($oModelFactory, $sSourceDir)
	{
		$this->oFactory = $oModelFactory;
		$this->sSourceDir = $sSourceDir;

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

	public function Compile($sTargetDir, $oP = null)
	{
		$aMenuNodes = array();
		$aMenusByModule = array();
		foreach ($this->oFactory->ListActiveChildNodes('menus', 'menu') as $oMenuNode)
		{
			$sMenuId = $oMenuNode->getAttribute('id');
			$aMenuNodes[$sMenuId] = $oMenuNode;

			$sModuleMenu = $oMenuNode->getAttribute('_created_in');
			$aMenusByModule[$sModuleMenu][] = $sMenuId;
		}

		$this->aRootClasses = array();
		foreach ($this->oFactory->ListRootClasses() as $oClass)
		{
			$this->Log("Root class: ".$oClass->getAttribute('id'));
			$this->aRootClasses[$oClass->getAttribute('id')] = $oClass;
		}

		$aModules = $this->oFactory->GetLoadedModules();
		foreach($aModules as $foo => $oModule)
		{
			$sModuleName = $oModule->GetName();
			$sModuleVersion = $oModule->GetVersion();
		
			$sModuleRootDir = realpath($oModule->GetRootDir());
			$sRelativeDir = substr($sModuleRootDir, strlen($this->sSourceDir) + 1);
		
			// Push the other module files
			$this->CopyDirectory($sModuleRootDir, $sTargetDir.'/'.$sRelativeDir);

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
					try
					{
						$sCompiledCode .= $this->CompileClass($oClass, $sRelativeDir, $oP);
					}
					catch (ssDOMFormatException $e)
					{
						$sClass = $oClass->getAttribute("id");
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
						$sCompiledCode .= $this->CompileMenu($oMenuNode, $sRelativeDir, $oP);
					}
					catch (ssDOMFormatException $e)
					{
						throw new Exception("Failed to process menu '$sMenuId', from '$sModuleRootDir': ".$e->getMessage());
					}
				}
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
				$sAuthor = 'Combodo compiler';
				$sLicence = 'http://www.opensource.org/licenses/gpl-3.0.html LGPL';
				$sFileHeader =
<<<EOF
<?php
//
// File generated by ... on the $sCurrDate
// Please do not edit manually
//
//
// Copyright (C) 2010 Combodo SARL
//
// ben on met quoi ici ?
// Signé: Romain
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
 * Classes and menus for $sModuleName (version $sModuleVersion)
 *
 * @author      $sAuthor
 * @license     $sLicence
 */

EOF;
				file_put_contents($sResultFile, $sFileHeader.$sCompiledCode);
			}
			
		}
	}

	/**
	 * Helper to copy the module files to the exploitation environment
	 * Returns true if successfull 
	 */ 
	protected function CopyDirectory($sSource, $sDest)
	{
		if (is_dir($sSource))
		{
			if (!is_dir($sDest))
			{
				mkdir($sDest);
			}
			$aFiles = scandir($sSource);
			if(sizeof($aFiles) > 0 )
			{
				foreach($aFiles as $sFile)
				{
					if ($sFile == '.' || $sFile == '..' || $sFile == '.svn')
					{
						// Skip
						continue;
					}
	
					if (is_dir($sSource.'/'.$sFile))
					{
						$this->CopyDirectory($sSource.'/'.$sFile, $sDest.'/'.$sFile);
					}
					else
					{
						copy($sSource.'/'.$sFile, $sDest.'/'.$sFile);
					}
				}
			}
			return true;
		}
		elseif (is_file($sSource))
		{
			return copy($sSource, $sDest);
		}
		else
		{
			return false;
		}
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
	protected function QuoteForPHP($sStr)
	{
		$sEscaped = str_replace(array('\\', '"', "\n"), array('\\\\', '\\"', '\\n'), $sStr);
		$sRet = '"'.$sEscaped.'"';
		return $sRet;
	}

	protected function CompileClass($oClass, $sModuleRelativeDir, $oP)
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
	// todo - utile ?
				$aParameters['allowed_values'] = 'null';
				$aParameters['count_min'] = $this->GetPropNumber($oField, 'count_min', 0);
				$aParameters['count_max'] = $this->GetPropNumber($oField, 'count_max', 0);
				$aParameters['duplicates'] = $this->GetPropBoolean($oField, 'duplicates', false);
				$aParameters['depends_on'] = $sDependencies;
			}
			elseif ($sAttType == 'AttributeLinkedSet')
			{
				$aParameters['linked_class'] = $this->GetPropString($oField, 'linked_class', '');
				$aParameters['ext_key_to_me'] = $this->GetPropString($oField, 'ext_key_to_me', '');
	// todo - utile ?
				$aParameters['allowed_values'] = 'null';
				$aParameters['count_min'] = $this->GetPropNumber($oField, 'count_min', 0);
				$aParameters['count_max'] = $this->GetPropNumber($oField, 'count_max', 0);
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
				$aParameters['min_auto_complete_chars'] = $this->GetPropNumber($oField, 'min_auto_complete_chars');
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
				$aParameters['min_auto_complete_chars'] = $this->GetPropNumber($oField, 'min_auto_complete_chars');
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
		if ($oProperties->GetChildText('abstract') == 'true')
		{
			$sPHP .= 'abstract class '.$oClass->getAttribute('id');
		}
		else
		{
			$sPHP .= 'class '.$oClass->getAttribute('id');
		}
		$sPHP .= " extends ".$oClass->GetChildText('parent', 'DBObject')."\n";
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


	protected function CompileMenu($oMenu, $sModuleRelativeDir, $oP)
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
			$sTemplateFile = $oMenu->GetChildText('definition_file');
			$sTemplateSpec = $this->PathToPHP($sTemplateFile, $sModuleRelativeDir);
			$sNewMenu = "new DashboardMenuNode('$sMenuId', $sTemplateSpec, $sParentSpec, $fRank);";
			break;

		case 'TemplateMenuNode':
			$sTemplateFile = $oMenu->GetChildText('template_file');
			$sTemplateSpec = $this->PathToPHP($sTemplateFile, $sModuleRelativeDir);
			$sNewMenu = "new TemplateMenuNode('$sMenuId', $sTemplateSpec, $sParentSpec, $fRank);";
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
					$sNewMenu = "new MenuGroup('$sMenuId', $fRank, '$sEnableClass', $sEnableAction, $sEnablePermission, '$sEnableStimulus');";
				}
				else
				{
					$sNewMenu = "new MenuGroup('$sMenuId', $fRank, '$sEnableClass', $sEnableAction, $sEnablePermission);";
				}
				//$sNewMenu = "new MenuGroup('$sMenuId', $fRank, '$sEnableClass', UR_ACTION_MODIFY, UR_ALLOWED_YES|UR_ALLOWED_DEPENDS);";
			}
			else
			{
				$sNewMenu = "new MenuGroup('$sMenuId', $fRank);";
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
	}
}



?>
