<?php
// Copyright (C) 2011 Combodo SARL
//
/**
 * ModelFactory: in-memory manipulation of the XML MetaModel
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     Combodo Private
 */


require_once(APPROOT.'setup/moduleinstaller.class.inc.php');

 /**
 * ModelFactoryModule: the representation of a Module (i.e. element that can be selected during the setup)
 * @package ModelFactory
 */
class MFModule
{
	protected $sId;
	protected $sName;
	protected $sVersion;
	protected $sRootDir;
	protected $sLabel;
	protected $aDataModels;
	
	public function __construct($sId, $sRootDir, $sLabel)
	{
		$this->sId = $sId;	
		
		list($this->sName, $this->sVersion) = ModuleDiscovery::GetModuleName($sId);
		if (strlen($this->sVersion) == 0)
		{
			$this->sVersion = '1.0.0';
		}

		$this->sRootDir = $sRootDir;
		$this->sLabel = $sLabel;
		$this->aDataModels = array();
	
		// Scan the module's root directory to find the datamodel(*).xml files
		if ($hDir = opendir($sRootDir))
		{
			// This is the correct way to loop over the directory. (according to the documentation)
			while (($sFile = readdir($hDir)) !== false)
			{
				if (preg_match('/^datamodel(.*)\.xml$/i', $sFile, $aMatches))
				{
					$this->aDataModels[] = $this->sRootDir.'/'.$aMatches[0];
				}
			}
			closedir($hDir);
		}
	}
	
	
	public function GetId()
	{
		return $this->sId;
	}
	
	public function GetName()
	{
		return $this->sName;
	}

	public function GetVersion()
	{
		return $this->sVersion;
	}

	public function GetLabel()
	{
		return $this->sLabel;
	}
	
	public function GetRootDir()
	{
		return $this->sRootDir;
	}

	public function GetModuleDir()
	{
		return basename($this->sRootDir);
	}

	public function GetDataModelFiles()
	{
		return $this->aDataModels;
	}
			
	/**
	 * List all classes in this module
	 */
	public function ListClasses()
	{
		return array();
	}
}

/**
 * ModelFactory: the class that manages the in-memory representation of the XML MetaModel
 * @package ModelFactory
 */
class ModelFactory
{
	protected $sRootDir;
	protected $oDOMDocument;
	protected $oRoot;
	protected $oModules;
	protected $oClasses;
	protected $oMenus;
	static protected $aLoadedClasses;
	static protected $aWellKnownParents = array('DBObject', 'CMDBObject','cmdbAbstractObject');
//	static protected $aWellKnownMenus = array('DataAdministration', 'Catalogs', 'ConfigManagement', 'Contact', 'ConfigManagementCI', 'ConfigManagement:Shortcuts', 'ServiceManagement');
	static protected $aLoadedModules;
	static protected $aLoadErrors;

	
	public function __construct($sRootDir, $aRootNodeExtensions = array())
	{
		$this->sRootDir = $sRootDir;
		$this->oDOMDocument = new MFDocument();
		$this->oRoot = $this->oDOMDocument->CreateElement('itop_design');
		$this->oDOMDocument->AppendChild($this->oRoot);
		$this->oModules = $this->oDOMDocument->CreateElement('loaded_modules');
		$this->oRoot->AppendChild($this->oModules);
		$this->oClasses = $this->oDOMDocument->CreateElement('classes');
		$this->oRoot->AppendChild($this->oClasses);
		foreach (self::$aWellKnownParents as $sWellKnownParent)
		{
			$oWKClass = $this->oDOMDocument->CreateElement('class');
			$oWKClass->setAttribute('id', $sWellKnownParent);
			$this->oClasses->AppendChild($oWKClass);
		}
		$this->oMenus = $this->oDOMDocument->CreateElement('menus');
		$this->oRoot->AppendChild($this->oMenus);
		
		foreach($aRootNodeExtensions as $sElementName)
		{
			$oElement = $this->oDOMDocument->CreateElement($sElementName);
			$this->oRoot->AppendChild($oElement);
		}
		self::$aLoadedModules = array();
		self::$aLoadErrors = array();

		libxml_use_internal_errors(true);
	}
	
	public function Dump($oNode = null)
	{
		if (is_null($oNode))
		{
			$oNode = $this->oRoot;
		}
		$oNode->Dump();
	}

	public function LoadFromFile($sCacheFile)
	{
		$this->oDOMDocument->load($sCacheFile);
		$this->oRoot = $this->oDOMDocument->firstChild;
		
		$this->oModules = $this->oRoot->getElementsByTagName('loaded_modules')->item(0);
		self::$aLoadedModules = array();
		foreach($this->oModules->getElementsByTagName('module') as $oModuleNode)
		{
			$sId = $oModuleNode->getAttribute('id');
			$sRootDir = $oModuleNode->GetChildText('root_dir');
			$sLabel = $oModuleNode->GetChildText('label');
			self::$aLoadedModules[] = new MFModule($sId, $sRootDir, $sLabel);
		}
	}

	public function SaveToFile($sCacheFile)
	{
		$this->oDOMDocument->save($sCacheFile);
	}
	/**
	 * To progressively replace LoadModule
	 * @param xxx xxx
	 */
	public function LoadDelta(DOMDocument $oDeltaDoc, $oSourceNode, $oTargetParentNode)
	{
		if (!$oSourceNode instanceof DOMElement) return;
		//echo "Load $oSourceNode->tagName::".$oSourceNode->getAttribute('id')." (".$oSourceNode->getAttribute('_delta').")<br/>\n";
		$oTarget = $this->oDOMDocument;

		if (($oSourceNode->tagName == 'class') && ($oSourceNode->parentNode->tagName == 'classes'))
		{
			if ($oSourceNode->getAttribute('_delta') == 'define')
			{
				// This tag is organized in hierarchy: determine the real parent node (as a subnode of the current node)
				$sParentId = $oSourceNode->GetChildText('parent');
				
				$oTargetParentNode = $oTarget->GetNodeById('/itop_design/classes//class', $sParentId)->item(0);
	
				if (!$oTargetParentNode)
				{
					echo "Dumping target doc - looking for '$sPath'<br/>\n";
					$this->oDOMDocument->firstChild->Dump();
					throw new Exception("XML datamodel loader: could not find parent node for $oSourceNode->tagName/".$oSourceNode->getAttribute('id')." with parent id $sParentId");
				}
			}
			else 
			{
				$oTargetNode = $oTarget->GetNodeById('/itop_design/classes//class', $oSourceNode->getAttribute('id'))->item(0);
				if (!$oTargetNode)
				{
					echo "Dumping target doc - looking for '$sPath'<br/>\n";
					$this->oDOMDocument->firstChild->Dump();
					throw new Exception("XML datamodel loader: could not find node for $oSourceNode->tagName/".$oSourceNode->getAttribute('id'));
				}
				else
				{
					$oTargetParentNode = $oTargetNode->parentNode;
				}				
								
			}
		}

		switch ($oSourceNode->getAttribute('_delta'))
		{
		case 'must_exist':
		case 'merge':
		case '':
			$bMustExist = ($oSourceNode->getAttribute('_delta') == 'must_exist');
			$sSearchId = $oSourceNode->hasAttribute('_rename_from') ? $oSourceNode->getAttribute('_rename_from') : $oSourceNode->getAttribute('id');
			$oTargetNode = $oSourceNode->MergeInto($oTargetParentNode, $sSearchId, $bMustExist);
			foreach($oSourceNode->childNodes as $oSourceChild)
			{
				// Continue deeper
				$this->LoadDelta($oDeltaDoc, $oSourceChild, $oTargetNode);
			}			
			break;

		case 'define':
			// New node - copy child nodes as well
			$oTargetNode = $oTarget->ImportNode($oSourceNode, true);
			$oTargetParentNode->AddChildNode($oTargetNode);
			break;

		case 'redefine':
			// Replace the existing node by the given node - copy child nodes as well
			$oTargetNode = $oTarget->ImportNode($oSourceNode, true);
			$oTargetParentNode->RedefineChildNode($oTargetNode);
			break;

		case 'delete':
			$oTargetNode = $oTargetParentNode->FindExistingChildNode($oSourceNode);
			$oTargetNode->Delete();
			break;
		}

		if ($oSourceNode->hasAttribute('_rename_from'))
		{
			$oTargetNode->Rename($oSourceNode->getAttribute('id'));
		}
		if ($oTargetNode->hasAttribute('_delta'))
		{
			$oTargetNode->removeAttribute('_delta');
		}
	}

	/**
	 * Loads the definitions corresponding to the given Module
	 * @param MFModule $oModule
	 */
	public function LoadModule(MFModule $oModule)
	{
		$aDataModels = $oModule->GetDataModelFiles();
		$sModuleName = $oModule->GetName();
		$aClasses = array();
		self::$aLoadedModules[] = $oModule;
		
		// For persistence in the cache
		$oModuleNode = $this->oDOMDocument->CreateElement('module');
		$oModuleNode->setAttribute('id', $oModule->GetId());
		$oModuleNode->AppendChild($this->oDOMDocument->CreateElement('root_dir', $oModule->GetRootDir()));
		$oModuleNode->AppendChild($this->oDOMDocument->CreateElement('label', $oModule->GetLabel()));
		$this->oModules->AppendChild($oModuleNode);
		
		foreach($aDataModels as $sXmlFile)
		{
			$oDocument = new MFDocument();
			libxml_clear_errors();
			$oDocument->load($sXmlFile);
			//$bValidated = $oDocument->schemaValidate(APPROOT.'setup/itop_design.xsd');
			$aErrors = libxml_get_errors();
			if (count($aErrors) > 0)
			{
				self::$aLoadErrors[$sModuleName] = $aErrors;
				return;
			}

			$oXPath = new DOMXPath($oDocument);
			$oNodeList = $oXPath->query('/itop_design/classes//class');
			foreach($oNodeList as $oNode)
			{
				$oNode->SetAttribute('_created_in', $sModuleName);
			}
			$oNodeList = $oXPath->query('/itop_design/menus/menu');
			foreach($oNodeList as $oNode)
			{
				$oNode->SetAttribute('_created_in', $sModuleName);
			}

			$oDeltaRoot = $oDocument->childNodes->item(0);
			$this->LoadDelta($oDocument, $oDeltaRoot, $this->oDOMDocument);
		}
	}

	/**
	 *	XML load errors (XML format and validation)
	 */	
	function HasLoadErrors()
	{
		return (count(self::$aLoadErrors) > 0);
	}
	function GetLoadErrors()
	{
		return self::$aLoadErrors;
	}

	function GetLoadedModules($bExcludeWorkspace = true)
	{
		if ($bExcludeWorkspace)
		{
			$aModules = array();
			foreach(self::$aLoadedModules as $oModule)
			{
				if (!$oModule instanceof MFWorkspace)
				{
					$aModules[] = $oModule;
				}
			}
		}
		else
		{
			$aModules = self::$aLoadedModules;
		}
		return $aModules;
	}
	
	
	function GetModule($sModuleName)
	{
		foreach(self::$aLoadedModules as $oModule)
		{
			if ($oModule->GetName() == $sModuleName) return $oModule;
		}
		return null;
	}
	
	public function CreateElement($sTagName, $sValue = '')
	{
		return $this->oDOMDocument->createElement($sTagName, $sValue);
	}
	
	public function GetNodeById($sXPath, $sId, $oContextNode = null)
	{
		return $this->oDOMDocument->GetNodeById($sXPath, $sId, $oContextNode);
	}
	
	/**
	 * Check if the class specified by the given node already exists in the loaded DOM
	 * @param DOMNode $oClassNode The node corresponding to the class to load
	 * @throws Exception
	 * @return bool True if the class exists, false otherwise
	 */
	protected function ClassExists(DOMNode $oClassNode)
	{
	assert(false);
		if ($oClassNode->hasAttribute('id'))
		{
			$sClassName = $oClassNode->GetAttribute('id');
		}
		else
		{
			throw new Exception('ModelFactory::AddClass: Cannot add a class with no name');
		}
	
		return (array_key_exists($sClassName, self::$aLoadedClasses));
	}
	
	/**
	 * Check if the class specified by the given name already exists in the loaded DOM
	 * @param string $sClassName The node corresponding to the class to load
	 * @throws Exception
	 * @return bool True if the class exists, false otherwise
	 */
	protected function ClassNameExists($sClassName, $bFlattenLayers = true)
	{
		return !is_null($this->GetClass($sClassName, $bFlattenLayers));
	}

	/**
	 * Add the given class to the DOM
	 * @param DOMNode $oClassNode
	 * @param string $sModuleName The name of the module in which this class is declared
	 * @throws Exception
	 */
	public function AddClass(DOMNode $oClassNode, $sModuleName)
	{
		if ($oClassNode->hasAttribute('id'))
		{
			$sClassName = $oClassNode->GetAttribute('id');
		}
		else
		{
			throw new Exception('ModelFactory::AddClass: Cannot add a class with no name');
		}
		if ($this->ClassNameExists($oClassNode->getAttribute('id')))
		{
			throw new Exception("ModelFactory::AddClass: Cannot add the already existing class $sClassName");
		}
		
		$sParentClass = $oClassNode->GetChildText('parent', '');
		$oParentNode = $this->GetClass($sParentClass);
		if ($oParentNode == null)
		{
			throw new Exception("ModelFactory::AddClass: Cannot find the parent class of '$sClassName': '$sParentClass'");
		}
		else
		{
			if ($sModuleName != '')
			{
				$oClassNode->SetAttribute('_created_in', $sModuleName);
			}
			$oParentNode->AddChildNode($this->oDOMDocument->importNode($oClassNode, true));
		}
	}
	
	/**
	 * Remove a class from the DOM
	 * @param string $sClass
	 * @throws Exception
	 */
	public function RemoveClass($sClass)
	{
		$oClassNode = $this->GetClass($sClass);
		if ($oClassNode == null)
		{
			throw new Exception("ModelFactory::RemoveClass: Cannot remove the non existing class $sClass");
		}

		// Note: the child classes are removed entirely
		$oClassNode->Delete();
	}

	/**
	 * Modify a class within the DOM
	 * @param string $sMenuId
	 * @param DOMNode $oMenuNode
	 * @throws Exception
	 */
	public function AlterClass($sClassName, DOMNode $oClassNode)
	{
		$sOriginalName = $sClassName;
		if ($this->ClassNameExists($sClassName))
		{
			$oDestNode = self::$aLoadedClasses[$sClassName];
		}
		else
		{
			$sOriginalName = $oClassNode->getAttribute('_original_name');
			if ($this->ClassNameExists($sOriginalName))
			{
				// Class was renamed !
				$oDestNode = self::$aLoadedClasses[$sOriginalName];
			}
			else
			{
				throw new Exception("ModelFactory::AddClass: Cannot alter the non-existing class $sClassName / $sOriginalName");
			}
		}
		$this->_priv_AlterNode($oDestNode, $oClassNode);
		$sClassName = $oDestNode->getAttribute('id');
		if ($sOriginalName != $sClassName)
		{
			unset(self::$aLoadedClasses[$sOriginalName]);
			self::$aLoadedClasses[$sClassName] = $oDestNode;
		}
		$this->_priv_SetFlag($oDestNode, 'modified');
	}

	
	public function GetClassXMLTemplate($sName, $sIcon)
	{
		$sHeader = '<?xml version="1.0" encoding="utf-8"?'.'>';
		return
<<<EOF
$sHeader
<class id="$sName">
	<comment/>
	<properties>
	</properties>
	<naming format=""><attributes/></naming>
	<reconciliation><attributes/></reconciliation>
	<display_template/>
	<icon>$sIcon</icon>
	</properties>
	<fields/>
	<lifecycle/>
	<methods/>
	<presentation>
		<details><items/></details>
		<search><items/></search>
		<list><items/></list>
	</presentation>
</class>
EOF
		;
	}
	/**
	 * List all classes from the DOM, for a given module
	 * @param string $sModuleNale
	 * @param bool $bFlattenLayers
	 * @throws Exception
	 */
	public function ListClasses($sModuleName, $bFlattenLayers = true)
	{
		$sXPath = "/itop_design/classes//class[@_created_in='$sModuleName']";
		if ($bFlattenLayers)
		{
			$sXPath = "/itop_design/classes//class[@_created_in='$sModuleName' and (not(@_alteration) or @_alteration!='removed')]";
		}
		return $this->GetNodes($sXPath);
	}
		
	/**
	 * List all classes from the DOM, for a given module
	 * @param string $sModuleNale
	 * @param bool $bFlattenLayers
	 * @throws Exception
	 */
	public function ListAllClasses($bFlattenLayers = true)
	{
		$sXPath = "/itop_design/classes//class";
		if ($bFlattenLayers)
		{
			$sXPath = "/itop_design/classes//class[not(@_alteration) or @_alteration!='removed']";
		}
		return $this->GetNodes($sXPath);
	}
	
	public function GetClass($sClassName, $bFlattenLayers = true)
	{
		$oClassNode = $this->GetNodes("/itop_design/classes//class[@id='$sClassName']")->item(0);
		if ($oClassNode == null)
		{
			return null;
		}
		elseif ($bFlattenLayers)
		{
			$sOperation = $oClassNode->getAttribute('_alteration');
			if ($sOperation == 'removed')
			{
				$oClassNode = null;
			}
		}
		return $oClassNode;
	}
	
	public function GetChildClasses($oClassNode, $bFlattenLayers = true)
	{
		$sXPath = "class";
		if ($bFlattenLayers)
		{
			$sXPath = "class[(@_operation!='removed')]";
		}
		return $this->GetNodes($sXPath, $oClassNode);
	}
		
	
	public function GetField($sClassName, $sAttCode, $bFlattenLayers = true)
	{
		if (!$this->ClassNameExists($sClassName))
		{
			return null;
		}
		$oClassNode = self::$aLoadedClasses[$sClassName];
		if ($bFlattenLayers)
		{
			$sOperation = $oClassNode->getAttribute('_operation');
			if ($sOperation == 'removed')
			{
				$oClassNode = null;
			}
		}
		$sXPath = "fields/field[@id='$sAttCode']";
		if ($bFlattenLayers)
		{
			$sXPath = "fields/field[(@id='$sAttCode' and (not(@_operation) or @_operation!='removed'))]";
		}
		$oFieldNode = $this->GetNodes($sXPath, $oClassNode)->item(0);
		if (($oFieldNode == null) && ($sParentClass = $oClassNode->GetChildText('parent')))
		{
			return $this->GetField($sParentClass, $sAttCode, $bFlattenLayers);
		}
		return $oFieldNode;
	}
		
	/**
	 * List all classes from the DOM
	 * @param bool $bFlattenLayers
	 * @throws Exception
	 */
	public function ListFields(DOMNode $oClassNode, $bFlattenLayers = true)
	{
		$sXPath = "fields/field";
		if ($bFlattenLayers)
		{
			$sXPath = "fields/field[not(@_alteration) or @_alteration!='removed']";
		}
		return $this->GetNodes($sXPath, $oClassNode);
	}
	
	public function AddField(DOMNode $oClassNode, $sFieldCode, $sFieldType, $sSQL, $defaultValue, $bIsNullAllowed, $aExtraParams)
	{
		$oNewField = $this->oDOMDocument->createElement('field');
		$oNewField->setAttribute('id', $sFieldCode);
		$this->_priv_AlterField($oNewField, $sFieldType, $sSQL, $defaultValue, $bIsNullAllowed, $aExtraParams);
		$oFields = $oClassNode->getElementsByTagName('fields')->item(0);
		$oFields->AppendChild($oNewField);
		$this->_priv_SetFlag($oNewField, 'added');
	}
	
	public function RemoveField(DOMNode $oClassNode, $sFieldCode)
	{
		$sXPath = "fields/field[@id='$sFieldCode']";
		$oFieldNodes = $this->GetNodes($sXPath, $oClassNode);
		if (is_object($oFieldNodes) && (is_object($oFieldNodes->item(0))))
		{
			$oFieldNode = $oFieldNodes->item(0);
			$sOpCode = $oFieldNode->getAttribute('_operation');
			if ($oFieldNode->getAttribute('_operation') == 'added')
			{
				$oFieldNode->parentNode->removeChild($oFieldNode);
			}
			else
			{
				$this->_priv_SetFlag($oFieldNode, 'removed');
			}
		}
	}
	
	public function AlterField(DOMNode $oClassNode, $sFieldCode, $sFieldType, $sSQL, $defaultValue, $bIsNullAllowed, $aExtraParams)
	{
		$sXPath = "fields/field[@id='$sFieldCode']";
		$oFieldNodes = $this->GetNodes($sXPath, $oClassNode);
		if (is_object($oFieldNodes) && (is_object($oFieldNodes->item(0))))
		{
			$oFieldNode = $oFieldNodes->item(0);
			//@@TODO: if the field was 'added' => then let it as 'added'
			$sOpCode = $oFieldNode->getAttribute('_operation');
			switch($sOpCode)
			{
				case 'added':
				case 'modified':
				// added or modified, let it as it is
				break;
				
				default:
				$this->_priv_SetFlag($oFieldNodes->item(0), 'modified');
			}
			$this->_priv_AlterField($oFieldNodes->item(0), $sFieldType, $sSQL, $defaultValue, $bIsNullAllowed, $aExtraParams);
		}
	}

	protected function _priv_AlterField(DOMNode $oFieldNode, $sFieldType, $sSQL, $defaultValue, $bIsNullAllowed, $aExtraParams)
	{
		switch($sFieldType)
		{			
			case 'Blob':
			case 'Boolean':
			case 'CaseLog':
			case 'Deadline':
			case 'Duration':
			case 'EmailAddress':
			case 'EncryptedString':
			case 'HTML':
			case 'IPAddress':
			case 'LongText':
			case 'OQL':
			case 'OneWayPassword':
			case 'Password':
			case 'Percentage':
			case 'String':
			case 'Text':
			case 'Text':
			case 'TemplateHTML':
			case 'TemplateString':
			case 'TemplateText':
			case 'URL':
			case 'Date':
			case 'DateTime':
			case 'Decimal':
			case 'Integer':
			break;	
			
			case 'ExternalKey':
			$this->_priv_AddFieldAttribute($oFieldNode, 'target_class', $aExtraParams);
			// Fall through
			case 'HierarchicalKey':
			$this->_priv_AddFieldAttribute($oFieldNode, 'on_target_delete', $aExtraParams);
			$this->_priv_AddFieldAttribute($oFieldNode, 'filter', $aExtraParams);
			break;

			case 'ExternalField':
			$this->_priv_AddFieldAttribute($oFieldNode, 'extkey_attcode', $aExtraParams);
			$this->_priv_AddFieldAttribute($oFieldNode, 'target_attcode', $aExtraParams);
			break;
				
			case 'Enum':
			$this->_priv_SetFieldValues($oFieldNode, $aExtraParams);
			break;
			
			case 'LinkedSetIndirect':
			$this->_priv_AddFieldAttribute($oFieldNode, 'ext_key_to_remote', $aExtraParams);
			// Fall through
			case 'LinkedSet':
			$this->_priv_AddFieldAttribute($oFieldNode, 'linked_class', $aExtraParams);
			$this->_priv_AddFieldAttribute($oFieldNode, 'ext_key_to_me', $aExtraParams);
			$this->_priv_AddFieldAttribute($oFieldNode, 'count_min', $aExtraParams);
			$this->_priv_AddFieldAttribute($oFieldNode, 'count_max', $aExtraParams);
			break;
			
			default:
			throw(new Exception('Unsupported type of field: '.$sFieldType));
		}
		$this->_priv_SetFieldDependencies($oFieldNode, $aExtraParams);
		$oFieldNode->setAttribute('type', $sFieldType);
		$oFieldNode->setAttribute('sql', $sSQL);
		$oFieldNode->setAttribute('default_value', $defaultValue);
		$oFieldNode->setAttribute('is_null_alllowed', $bIsNullAllowed ? 'true' : 'false');
	}
	
	protected function _priv_AddFieldAttribute(DOMNode $oFieldNode, $sAttributeCode, $aExtraParams, $bMandatory = false)
	{
		$value = array_key_exists($sAttributeCode, $aExtraParams) ? $aExtraParams[$sAttributeCode] : '';
		if (($value == '') && (!$bMandatory)) return;
		$oFieldNode->setAttribute($sAttributeCode, $value);
	}
	
	protected function _priv_SetFieldDependencies($oFieldNode, $aExtraParams)
	{
		$aDeps = array_key_exists('dependencies', $aExtraParams) ? $aExtraParams['dependencies'] : '';
		$oDependencies = $oFieldNode->getElementsByTagName('dependencies')->item(0);

		// No dependencies before, and no dependencies to add, exit
		if (($oDependencies == null) && ($aDeps == '')) return;
		
		// Remove the previous dependencies
		$oFieldNode->removeChild($oDependencies);
		// If no dependencies, exit
		if ($aDeps == '') return;

		// Build the new list of dependencies
		$oDependencies = $this->oDOMDocument->createElement('dependencies');

		foreach($aDeps as $sAttCode)
		{
			$oDep = $this->oDOMDocument->createElement('attribute');
			$oDep->setAttribute('id', $sAttCode);
			$oDependencies->addChild($oDep);
		}
		$oFieldNode->addChild($oDependencies);
	}
	
	protected function _priv_SetFieldValues($oFieldNode, $aExtraParams)
	{
		$aVals = array_key_exists('values', $aExtraParams) ? $aExtraParams['values'] : '';
		$oValues = $oFieldNode->getElementsByTagName('values')->item(0);

		// No dependencies before, and no dependencies to add, exit
		if (($oValues == null) && ($aVals == '')) return;
		
		// Remove the previous dependencies
		$oFieldNode->removeChild($oValues);
		// If no dependencies, exit
		if ($aVals == '') return;

		// Build the new list of dependencies
		$oValues = $this->oDOMDocument->createElement('values');

		foreach($aVals as $sValue)
		{
			$oVal = $this->oDOMDocument->createElement('value', $sValue);
			$oValues->appendChild($oVal);
		}
		$oFieldNode->appendChild($oValues);
	}

	public function SetPresentation(DOMNode $oClassNode, $sPresentationCode, $aPresentation)
	{
		$oPresentation = $oClassNode->getElementsByTagName('presentation')->item(0);
		if (!is_object($oPresentation))
		{
			$oPresentation = $this->oDOMDocument->createElement('presentation');
			$oClassNode->appendChild($oPresentation);
		}
		$oZlist = $oPresentation->getElementsByTagName($sPresentationCode)->item(0);
		if (is_object($oZlist))
		{
			// Remove the previous Zlist
			$oPresentation->removeChild($oZlist);
		}
		// Create the ZList anew
		$oZlist = $this->oDOMDocument->createElement($sPresentationCode);
		$oPresentation->appendChild($oZlist);
		$this->AddZListItem($oZlist, $aPresentation);
		$this->_priv_SetFlag($oZlist, 'replaced');
	}

	protected function AddZListItem($oXMLNode, $value)
	{
		if (is_array($value))
		{
			$oXmlItems = $this->oDOMDocument->CreateElement('items');
			$oXMLNode->appendChild($oXmlItems);
			
			foreach($value as $key => $item)
			{
				$oXmlItem = $this->oDOMDocument->CreateElement('item');
				$oXmlItems->appendChild($oXmlItem);
	
				if (is_string($key))
				{
					$oXmlItem->SetAttribute('key', $key);
				}
				$this->AddZListItem($oXmlItem, $item);
			}
		}
		else
		{
			$oXmlText = $this->oDOMDocument->CreateTextNode((string) $value);
			$oXMLNode->appendChild($oXmlText);
		}
	}
	
	/**
	 * List all transitions from a given state
	 * @param DOMNode $oStateNode The state
	 * @param bool $bFlattenLayers
	 * @throws Exception
	 */
	public function ListTransitions(DOMNode $oStateNode, $bFlattenLayers = true)
	{
		$sXPath = "transitions/transition";
		if ($bFlattenLayers)
		{
			//$sXPath = "transitions/transition[@_operation!='removed']";
		}
		return $this->GetNodes($sXPath, $oStateNode);
	}
		
	/**
	 * List all states of a given class
	 * @param DOMNode $oClassNode The class
	 * @param bool $bFlattenLayers
	 * @throws Exception
	 */
	public function ListStates(DOMNode $oClassNode, $bFlattenLayers = true)
	{
		$sXPath = "lifecycle/states/state";
		if ($bFlattenLayers)
		{
			//$sXPath = "lifecycle/states/state[@_operation!='removed']";
		}
		return $this->GetNodes($sXPath, $oClassNode);
	}
		
	/**
	 * List Zlists from the DOM for a given class
	 * @param bool $bFlattenLayers
	 * @throws Exception
	 */
	public function ListZLists(DOMNode $oClassNode, $bFlattenLayers = true)
	{
		// Not yet implemented !!!
		return array();
	}
		
	public function ApplyChanges()
	{
		$oNodes = $this->ListChanges();
		foreach($oNodes as $oNode)
		{
			$sOperation = $oNode->GetAttribute('_alteration');
			switch($sOperation)
			{
				case 'added':
				case 'replaced':
				// marked as added or modified, just reset the flag
				$oNode->removeAttribute('_alteration');
				break;
				
				case 'removed':
				// marked as deleted, let's remove the node from the tree
				$oNode->parentNode->removeChild($oNode);
				// TODO!!!!!!!
				//unset(self::$aLoadedClasses[$sClass]);
				break;
			}
			if ($oNode->hasAttribute('_old_id'))
			{
				$oNode->removeAttribute('_old_id');
			}
		}
	}
	
	public function ListChanges()
	{
		return $this->GetNodes('//*[@_alteration or @_old_id]');
	}


	/**
	 * Create path for the delta
	 * @param DOMDocument oTargetDoc  Where to attach the top of the hierarchy
	 * @param MFElement   oNode       The node to import with its path	 	 
	 */
	protected function ImportNodeAndPathDelta($oTargetDoc, $oNode)
	{
		// Preliminary: skip the parent if this node is organized hierarchically into the DOM
		// The criteria to detect a hierarchy is: same tag + have an id
		$oParent = $oNode->parentNode;
		while (($oParent instanceof DOMElement) && ($oParent->tagName == $oNode->tagName) && $oParent->hasAttribute('id'))
		{
			$oParent = $oParent->parentNode;
		}
		// Recursively create the path for the parent
		if ($oParent instanceof DOMElement)
		{
			$oParentClone = $this->ImportNodeAndPathDelta($oTargetDoc, $oParent);
		}
		else
		{
			// We've reached the top let's add the node into the root recipient
			$oParentClone = $oTargetDoc;
		}
		// Look for the node into the parent node
		// Note: this is an identified weakness of the algorithm,
		//       because for each node modified, and each node of its path
		//       we will have to lookup for the existing entry
		//       Anyhow, this loop is quite quick to execute because in the delta
		//       the number of nodes is limited
		$oNodeClone = null;
		foreach ($oParentClone->childNodes as $oChild)
		{
			if (($oChild instanceof DOMElement) && ($oChild->tagName == $oNode->tagName))
			{
				if (!$oNode->hasAttribute('id') || ($oNode->getAttribute('id') == $oChild->getAttribute('id')))
				{
					$oNodeClone = $oChild;
					break;
				}
			}
		} 
		if (!$oNodeClone)
		{
			$sAlteration = $oNode->getAttribute('_alteration');
			$bCopyContents = ($sAlteration == 'replaced') || ($sAlteration == 'added');
			$oNodeClone = $oTargetDoc->importNode($oNode->cloneNode($bCopyContents), $bCopyContents);
			$oNodeClone->removeAttribute('_alteration');
			if ($oNodeClone->hasAttribute('_old_id'))
			{
				$oNodeClone->setAttribute('_rename_from', $oNodeClone->getAttribute('_old_id'));
				$oNodeClone->removeAttribute('_old_id');
			}
			switch ($sAlteration)
			{
			case '':
				if ($oNodeClone->hasAttribute('id'))
				{
					$oNodeClone->setAttribute('_delta', 'must_exist');
				}
				break;
			case 'added':
				$oNodeClone->setAttribute('_delta', 'define');
				break;
			case 'replaced':
				$oNodeClone->setAttribute('_delta', 'redefine');
				break;
			case 'removed':
				$oNodeClone->setAttribute('_delta', 'delete');
				break;
			}
			$oParentClone->appendChild($oNodeClone);
		}
		return $oNodeClone;
	}

	/**
	 * Get the text/XML version of the delta
	 */	
	public function GetDelta()
	{
		$oDelta = new MFDocument();
		foreach($this->ListChanges() as $oAlteredNode)
		{
			$this->ImportNodeAndPathDelta($oDelta, $oAlteredNode);
		}
		return $oDelta->saveXML();
	}
	
	/**
	 * Searches on disk in the root directory for module description files
	 * and returns an array of MFModules
	 * @return array Array of MFModules
	 */
	public function FindModules($sSubDirectory = '')
	{
		$aAvailableModules = ModuleDiscovery::GetAvailableModules($this->sRootDir, $sSubDirectory);
		$aResult = array();
		foreach($aAvailableModules as $sId => $aModule)
		{
			$aResult[] = new MFModule($sId, $aModule['root_dir'], $aModule['label']);
		}
		return $aResult;
	}
	
	public function TestAlteration()
	{
		if (false)
		{
			echo "<h4>Extrait des données chargées</h4>\n";
			$oRoot = $this->GetNodes("//class[@id='Contact']")->item(0);
			$oRoot->Dump();
			return;
		}

		$sHeader = '<?xml version="1.0" encoding="utf-8"?'.'>';
		$sOriginalXML =
<<<EOF
$sHeader
<itop_design>
	<a id="first a">
		<b>Text</b>
		<c id="1">
			<d>D1</d>
			<d>D2</d>
		</c>
	</a>
	<a id="second a">
		<parent>first a</parent>
	</a>
	<a id="third a">
		<parent>first a</parent>
		<x>blah</x>
	</a>
</itop_design>
EOF;

		$this->oDOMDocument = new MFDocument();
		$this->oDOMDocument->loadXML($sOriginalXML);

		echo "<h4>Données d'origine</h4>\n";
		$oRoot = $this->GetNodes('//itop_design')->item(0);
		$oRoot->Dump();

		$oNode = $oRoot->GetNodes('a/b')->item(0);
		$oNew = $this->oDOMDocument->CreateElement('b', 'New text');
		$oNode->parentNode->RedefineChildNode($oNew);		

		$oNode = $oRoot->GetNodes('a/c')->item(0);
		$oNew = $this->oDOMDocument->CreateElement('c');
		$oNew->setAttribute('id', '1');
		$oNew->appendChild($this->oDOMDocument->CreateElement('d', 'x'));
		$oNew->appendChild($this->oDOMDocument->CreateElement('d', 'y'));
		$oNew->appendChild($this->oDOMDocument->CreateElement('d', 'z'));
		$oNode->parentNode->RedefineChildNode($oNew);		

		$oNode = $oRoot->GetNodes("//a[@id='second a']")->item(0);
		$oNode->Rename('el secundo A');		
		$oNew = $this->oDOMDocument->CreateElement('e', 'Something new here');
		$oNode->AddChildNode($oNew);		
		$oNew = $this->oDOMDocument->CreateElement('a');
		$oNew->setAttribute('id', 'new a');
		$oNew->appendChild($this->oDOMDocument->CreateElement('parent', 'el secundo A'));
		$oNew->appendChild($this->oDOMDocument->CreateElement('f', 'Welcome to the newcomer'));
		$oNode->AddChildNode($oNew);		

		$oNode = $oRoot->GetNodes("//a[@id='third a']")->item(0);
		$oNode->Delete();		

		echo "<h4>Après modifications (avec les APIs de ModelFactory)</h4>\n";
		$oRoot->Dump();
		
		echo "<h4>Delta calculé</h4>\n";
		$sDeltaXML = $this->GetDelta();
		echo "<pre>\n";
		echo htmlentities($sDeltaXML);
		echo "</pre>\n";

		echo "<h4>Réitération: on recharge le modèle épuré</h4>\n";
		$this->oDOMDocument = new MFDocument();
		$this->oDOMDocument->loadXML($sOriginalXML);
		$oRoot = $this->GetNodes('//itop_design')->item(0);
		$oRoot->Dump();

		echo "<h4>On lui applique le delta calculé vu ci-dessus, et on obtient...</h4>\n";
		$oDeltaDoc = new MFDocument();
		$oDeltaDoc->loadXML($sDeltaXML);
		
		$oDeltaDoc->Dump();
		$this->oDOMDocument->Dump();
		$oDeltaRoot = $oDeltaDoc->childNodes->item(0);
		$this->LoadDelta($oDeltaDoc, $oDeltaRoot, $this->oDOMDocument);
		$oRoot->Dump();
	} // TEST !


	/**
	 * Extracts some nodes from the DOM
	 * @param string $sXPath A XPath expression
	 * @return DOMNodeList
	 */
	public function GetNodes($sXPath, $oContextNode = null)
	{
		return $this->oDOMDocument->GetNodes($sXPath, $oContextNode);
	}

	public function ListActiveChildNodes($sContextXPath, $sTagName)
	{
		$oContextPath = $this->oRoot->GetNodes($sContextXPath)->item(0);
		return $oContextPath->ListActiveChildNodes($sTagName);
	}
}


/**
 * MFElement: helper to read/change the DOM
 * @package ModelFactory
 */
class MFElement extends DOMElement
{
	/**
	 * Extracts some nodes from the DOM
	 * @param string $sXPath A XPath expression
	 * @return DOMNodeList
	 */
	public function GetNodes($sXPath)
	{
		return $this->ownerDocument->GetNodes($sXPath, $this);
	}

	/**
	 * For debugging purposes
	 */
	public function Dump()
	{
		echo "<pre>\n";	 	
		echo htmlentities($this->ownerDocument->saveXML($this));
		echo "</pre>\n";	 	
	}

	/**
	 * Returns the node directly under the given node 
	 */ 
	public function GetUniqueElement($sTagName, $bMustExist = true)
	{
		$oNode = null;
		foreach($this->childNodes as $oChildNode)
		{
			if ($oChildNode->nodeName == $sTagName)
			{
				$oNode = $oChildNode;
				break;
			}
		}
		if ($bMustExist && is_null($oNode))
		{
			throw new DOMFormatException('Missing unique tag: '.$sTagName);
		}
		return $oNode;
	}
	
	/**
	 * Returns the node directly under the current node, or null if missing 
	 */ 
	public function GetOptionalElement($sTagName)
	{
		return $this->GetUniqueElement($sTagName, false);
	}
	
	
	/**
	 * Returns the TEXT of the current node (possibly from several subnodes) 
	 */ 
	public function GetText($sDefault = null)
	{
		$sText = null;
		foreach($this->childNodes as $oChildNode)
		{
			if ($oChildNode instanceof DOMCharacterData) // Base class of DOMText and DOMCdataSection
			{
				if (is_null($sText)) $sText = '';
				$sText .= $oChildNode->wholeText;
			}
		}
		if (is_null($sText))
		{
			return $sDefault;
		}
		else
		{
			return $sText;
		}
	}
	
	/**
	 * Get the TEXT value from the child node 
	 */ 
	public function GetChildText($sTagName, $sDefault = null)
	{
		$sRet = $sDefault;
		if ($oChild = $this->GetOptionalElement($sTagName))
		{
			$sRet = $oChild->GetText($sDefault);
		}
		return $sRet;
	}

	/**
	 * Assumes the current node to be either a text or
	 * <items>
	 *   <item [key]="..."]>value<item>
	 *   <item [key]="..."]>value<item>
	 * </items>
	 * where value can be the either a text or an array of items... recursively 
	 * Returns a PHP array 
	 */ 
	public function GetNodeAsArrayOfItems()
	{
		$oItems = $this->GetOptionalElement('items');
		if ($oItems)
		{
			$res = array();
			foreach($oItems->childNodes as $oItem)
			{
				// When an attribute is missing
				if ($oItem->hasAttribute('id'))
				{
					$key = $oItem->getAttribute('id');
					$res[$key] = $oItem->GetNodeAsArrayOfItems();
				}
				else
				{
					$res[] = $oItem->GetNodeAsArrayOfItems();
				}
			}
		}
		else
		{
			$res = $this->GetText();
		}
		return $res;
	}

	public function SetNodeAsArrayOfItems($aList)
	{
		$oNewNode = $this->ownerDocument->CreateElement($this->tagName);
		if ($this->getAttribute('id') != '')
		{
			$oNewNode->setAttribute('id', $this->getAttribute('id'));
		}
		self::AddItemToNode($this->ownerDocument, $oNewNode, $aList);
		$this->parentNode->RedefineChildNode($oNewNode);
	}
	
	protected static function AddItemToNode($oXmlDoc, $oXMLNode, $itemValue)
	{
		if (is_array($itemValue))
		{
			$oXmlItems = $oXmlDoc->CreateElement('items');
			$oXMLNode->AppendChild($oXmlItems);
			
			foreach($itemValue as $key => $item)
			{
				$oXmlItem = $oXmlDoc->CreateElement('item');
				$oXmlItems->AppendChild($oXmlItem);
	
				if (is_string($key))
				{
					$oXmlItem->SetAttribute('key', $key);
				}
				self::AddItemToNode($oXmlDoc, $oXmlItem, $item);
			}
		}
		else
		{
			$oXmlText = $oXmlDoc->CreateTextNode((string) $itemValue);
			$oXMLNode->AppendChild($oXmlText);
		}
	}

	/**
	 * Helper to remove child nodes	
	 */	
	public function DeleteChildren()
	{
		while (isset($this->firstChild))
		{
			if ($this->firstChild instanceof MFElement)
			{
				$this->firstChild->DeleteChildren();
			}
			$this->removeChild($this->firstChild);
		}
	} 

	/**
	 * Find the child node matching the given node
	 * @param MFElement $oRefNode The node to search for
	 * @param bool      $sSearchId substitutes to the value of the 'id' attribute 
	 */	
	public function FindExistingChildNode(MFElement $oRefNode, $sSearchId = null)
	{
		return self::FindNode($this, $oRefNode, $sSearchId);
	}
	/**
	 * Find the child node matching the given node
	 * @param DOMNode   $oParent The node to look into (could be DOMDocument, DOMElement...)
	 * @param MFElement $oRefNode The node to search for
	 * @param bool      $sSearchId substitutes to the value of the 'id' attribute 
	 */	
	public static function FindNodeSlow(DOMNode $oParent, MFElement $oRefNode, $sSearchId = null)
	{
		$oRes = null;
		if ($oRefNode->hasAttribute('id'))
		{
			// Find the first element having the same tag name and id
			if (!$sSearchId)
			{
				$sSearchId = $oRefNode->getAttribute('id');
			}
			foreach($oParent->childNodes as $oChildNode)
			{
				if (($oChildNode instanceof DOMElement) && ($oChildNode->tagName == $oRefNode->tagName))
				{
					if ($oChildNode->hasAttribute('id') && ($oChildNode->getAttribute('id') == $sSearchId))
					{
						$oRes = $oChildNode;
						break;
					}
				}
			}
		}
		else
		{
			// Get the first one having the same tag name (ignore others)
			foreach($oParent->childNodes as $oChildNode)
			{
				if (($oChildNode instanceof DOMElement) && ($oChildNode->tagName == $oRefNode->tagName))
				{
					$oRes = $oChildNode;
					break;
				}
			}
		}
		return $oRes;
	}
	
	/**
	 * Seems to work fine (and is about 10 times faster than above) EXCEPT for menus !!!!
	 * @param unknown_type $oParent
	 * @param unknown_type $oRefNode
	 * @param unknown_type $sSearchId
	 * @throws Exception
	 */
	public static function FindNode(DOMNode $oParent, MFElement $oRefNode, $sSearchId = null)
	{
		$oRes = null;
		if ($oParent instanceof DOMDocument)
		{
			$oDoc = $oParent->firstChild->ownerDocument;
			$oRoot = $oParent;
		}
		else
		{
			$oDoc = $oParent->ownerDocument;
			$oRoot = $oParent;
		}

		$oXPath = new DOMXPath($oDoc);
		if ($oRefNode->hasAttribute('id'))
		{
			// Find the first element having the same tag name and id
			if (!$sSearchId)
			{
				$sSearchId = $oRefNode->getAttribute('id');
			}
			$sXPath = './'.$oRefNode->tagName."[@id='$sSearchId']";
		
			$oRes = $oXPath->query($sXPath, $oRoot)->item(0);
		}
		else
		{
			// Get the first one having the same tag name (ignore others)
			$sXPath = './'.$oRefNode->tagName;
		
			$oRes = $oXPath->query($sXPath, $oRoot)->item(0);
		}
		return $oRes;
	}
	
	public function ListActiveChildNodes($sTagName)
	{
		$sXPath = $sTagName."[not(@_alteration) or @_alteration!='removed']";
		return $this->GetNodes($sXPath);
	}


	/**
	 * Add a node and set the flags that will be used to compute the delta
	 * @param MFElement $oNode The node (including all subnodes) to add
	 */	
	public function AddChildNode(MFElement $oNode)
	{
		$sFlag = null;

		$oExisting = $this->FindExistingChildNode($oNode);
		if ($oExisting)
		{
			if ($oExisting->getAttribute('_alteration') != 'removed')
			{
				throw new Exception("Attempting to add a node that already exists: $oNode->tagName (id: ".$oNode->getAttribute('id')."");
			}
			$sFlag = 'replaced';
			$oExisting->ReplaceWith($oNode);
		}
		else
		{
			$this->appendChild($oNode);

			$sFlag = 'added';
			// Iterate through the parents: reset the flag if any of them has a flag set 
			for($oParent = $oNode ; $oParent instanceof MFElement ; $oParent = $oParent->parentNode)
			{
				if ($oParent->getAttribute('_alteration') != '')
				{
					$sFlag = null;
					break;
				}
			}
		}
		if ($sFlag)
		{
			$oNode->setAttribute('_alteration', $sFlag);
		}
	}

	/**
	 * Modify a node and set the flags that will be used to compute the delta
	 * @param MFElement $oNode       The node (including all subnodes) to set
	 */	
	public function RedefineChildNode(MFElement $oNode)
	{
		$oExisting = $this->FindExistingChildNode($oNode);
		if (!$oExisting)
		{
			throw new Exception("Attempting to modify a non existing node: $oNode->tagName (id: ".$oNode->getAttribute('id').")");
		}
		if ($oExisting->getAttribute('_alteration') == 'removed')
		{
			throw new Exception("Attempting to modify a deleted node: $oNode->tagName (id: ".$oNode->getAttribute('id')."");
		}
		$oExisting->ReplaceWith($oNode);
			
		if ($oNode->getAttribute('_alteration') != '')
		{
			// added or modified: leave the flag unchanged
			$sFlag = null;
		}
		else
		{
			$sFlag = 'replaced';
			// Iterate through the parents: reset the flag if any of them has a flag set 
			for($oParent = $oNode ; $oParent instanceof MFElement ; $oParent = $oParent->parentNode)
			{
				if ($oParent->getAttribute('_alteration') != '')
				{
					$sFlag = null;
					break;
				}
			}
		}
		if ($sFlag)
		{
			$oNode->setAttribute('_alteration', $sFlag);
		}
	}

	/**
	 * Replaces a node by another one, making sure that recursive nodes are preserved
	 * @param MFElement $oNewNode The replacement
	 */	
	protected function ReplaceWith($oNewNode)
	{
		// Move the classes from the old node into the new one
		foreach($this->GetNodes('class') as $oChild)
		{
			$oNewNode->appendChild($oChild);
		}
		

		$oParentNode = $this->parentNode;
		$oParentNode->replaceChild($oNewNode, $this);
	}

	/**
	 * Remove a node and set the flags that will be used to compute the delta
	 */	
	public function Delete()
	{
		$oParent = $this->parentNode;
		switch ($this->getAttribute('_alteration'))
		{
		case 'replaced':
			$sFlag = 'removed';
			break;
		case 'added':
			$sFlag = null;
			break;
		case 'removed':
			throw new Exception("Attempting to remove a deleted node: $this->tagName (id: ".$this->getAttribute('id')."");

		default:
			$sFlag = 'removed';
			// Iterate through the parents: reset the flag if any of them has a flag set 
			for($oParent = $this ; $oParent instanceof MFElement ; $oParent = $oParent->parentNode)
			{
				if ($oParent->getAttribute('_alteration') != '')
				{
					$sFlag = null;
					break;
				}
			}
		}
		if ($sFlag)
		{
			$this->setAttribute('_alteration', $sFlag);
			$this->DeleteChildren();
		}
		else
		{
			// Remove the node entirely
			$oParent->removeChild($this);
		}
	}

	/**
	 * Merge the current node into the given container
	 * 	 
	 * @param DOMNode $oContainer An element or a document	 
	 * @param string  $sSearchId  The id to consider (could be blank)
	 * @param bool    $bMustExist Throw an exception if the node must already be found (and not marked as deleted!)
	 */
	public function MergeInto($oContainer, $sSearchId, $bMustExist)
	{
		$oTargetNode = $oContainer->FindExistingChildNode($this, $sSearchId);
		if ($oTargetNode)
		{
			if ($oTargetNode->getAttribute('_alteration') == 'removed')
			{
				if ($bMustExist)
				{
					throw new Exception("XML datamodel loader: found mandatory node $this->tagName/$sSearchId marked as deleted in $oContainer->tagName");
				}
				$oTargetNode = $oContainer->ownerDocument->ImportNode($this, false);
				$oContainer->AddChildNode($oTargetNode);
			}
		}
		else
		{
			if ($bMustExist)
			{
				echo "Dumping parent node<br/>\n";
				$oContainer->Dump();
				throw new Exception("XML datamodel loader: could not find $this->tagName/$sSearchId in $oContainer->tagName");
			}
			$oTargetNode = $oContainer->ownerDocument->ImportNode($this, false);
			$oContainer->AddChildNode($oTargetNode);
		}
		return $oTargetNode;
	}

	/**
	 * Renames a node and set the flags that will be used to compute the delta
	 * @param String    $sNewId The new id	 
	 */	
	public function Rename($sId)
	{
		$this->setAttribute('_old_id', $this->getAttribute('id'));
		$this->setAttribute('id', $sId);
	}
}

/**
 * MFDocument - formating rules for XML input/output
 * @package ModelFactory
 */
class MFDocument extends DOMDocument
{
	public function __construct()
	{
		parent::__construct('1.0', 'UTF-8');
		$this->registerNodeClass('DOMElement', 'MFElement');

		$this->formatOutput = true; // indent (must by loaded with option LIBXML_NOBLANKS)
		$this->preserveWhiteSpace = true; // otherwise the formatOutput option would have no effect
	}

	/**
	 * Overload of the standard API	
	 */
	public function load($filename, $options = 0)
	{
		parent::load($filename, LIBXML_NOBLANKS);
	}

	/**
	 * Overload of the standard API	
	 */
	public function loadXML($source, $options = 0)
	{
		parent::loadXML($source, LIBXML_NOBLANKS);
	}

	/**
	 * Overload the standard API
	 */
	public function saveXML(DOMNode $node = null, $options = 0)
	{
		$oRootNode = $this->firstChild;
		if (!$oRootNode)
		{
			$oRootNode = $this->createElement('itop_design'); // make sure that the document is not empty
			$this->appendChild($oRootNode);
		}
		$oRootNode->setAttribute('xmlns:xsi', "http://www.w3.org/2001/XMLSchema-instance");
		return parent::saveXML();
	}
	/**
	 * For debugging purposes
	 */
	public function Dump()
	{
		echo "<pre>\n";	 	
		echo htmlentities($this->saveXML());
		echo "</pre>\n";	 	
	}

	/**
	 * Find the child node matching the given node
	 * @param MFElement $oRefNode The node to search for
	 * @param bool      $sSearchId substitutes to the value of the 'id' attribute 
	 */	
	public function FindExistingChildNode(MFElement $oRefNode, $sSearchId = null)
	{
		return MFElement::FindNode($this, $oRefNode, $sSearchId);
	}

	/**
	 * Extracts some nodes from the DOM
	 * @param string $sXPath A XPath expression
	 * @return DOMNodeList
	 */
	public function GetNodes($sXPath, $oContextNode = null)
	{
		$oXPath = new DOMXPath($this);
		
		if (is_null($oContextNode))
		{
			return $oXPath->query($sXPath);
		}
		else
		{
			return $oXPath->query($sXPath, $oContextNode);
		}
	}
	
	public function GetNodeById($sXPath, $sId, $oContextNode = null)
	{
		$oXPath = new DOMXPath($this);
		$sXPath .= "[@id='$sId' and(not(@_alteration) or @_alteration!='removed')]";
		
		if (is_null($oContextNode))
		{
			return $oXPath->query($sXPath);
		}
		else
		{
			return $oXPath->query($sXPath, $oContextNode);
		}
	}
}