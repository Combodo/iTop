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
 * ModelFactoryItem: an item managed by the ModuleFactory
 * @package ModelFactory
 */
abstract class MFItem
{
	public function __construct($sName, $sValue) 
	{
		parent::__construct($sName, $sValue);
	}
	
	/**
	 * List the source files for this item
	 */
	public function ListSources()
	{
		
	}
	/**
	 * List the rights/restrictions for this item
	 */
	public function ListRights()
	{
		
	}
}
 /**
 * ModelFactoryModule: the representation of a Module (i.e. element that can be selected during the setup)
 * @package ModelFactory
 */
class MFModule extends MFItem
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

class MFWorkspace extends MFModule
{
	public function __construct($sRootDir)
	{
		$this->sId = 'itop-workspace';	
		
		$this->sName = 'workspace';
		$this->sVersion = '1.0';

		$this->sRootDir = $sRootDir;
		$this->sLabel = 'Workspace';
		$this->aDataModels = array();

		$this->aDataModels[] = $this->GetWorkspacePath();
	}

	public function GetWorkspacePath()
	{
		return $this->sRootDir.'/workspace.xml';
	}
	
	public function GetName()
	{
		return ''; // The workspace itself has no name so that objects created inside it retain their original module's name
	}
}

 /**
 * ModelFactoryClass: the representation of a Class (i.e. a PHP class)
 * @package ModelFactory
 */
class MFClass extends MFItem
{
	/**
	 * List all fields of this class
	 */
	public function ListFields()
	{
		return array();
	}
	
	/**
	 * List all methods of this class
	 */
	public function ListMethods()
	{
		return array();
	}
	
	/**
	 * Whether or not the class has a lifecycle
	 * @return bool
	 */
	public function HasLifeCycle()
	{
		return true; //TODO implement
	}
	
	/**
	 * Returns the code of the attribute used to store the lifecycle state
	 * @return string
	 */
	public function GetLifeCycleAttCode()
	{
		if ($this->HasLifeCycle())
		{
			
		}
		return '';
	}
	
	/**
	 * List all states of this class
	 */
	public function ListStates()
	{
		return array();
	}
	/**
	 * List all relations of this class
	 */
	public function ListRelations()
	{
		return array();
	}
	/**
	 * List all transitions of this class
	 */
	public function ListTransitions()
	{
		return array();
	}
}

 /**
 * ModelFactoryField: the representation of a Field (i.e. a property of a class)
 * @package ModelFactory
 */
class MFField extends MFItem
{
}

 /**
 * ModelFactoryMethod: the representation of a Method (i.e. a method of a class)
 * @package ModelFactory
 */
class MFMethod extends MFItem
{
}

 /**
 * ModelFactoryState: the representation of a state in the life cycle of the class
 * @package ModelFactory
 */
class MFState extends MFItem
{
}

 /**
 * ModelFactoryRelation: the representation of a n:n relationship between two classes
 * @package ModelFactory
 */
class MFRelation extends MFItem
{
}

 /**
 * ModelFactoryTransition: the representation of a transition between two states in the life cycle of the class
 * @package ModelFactory
 */
class MFTransition extends MFItem
{
}


/**
 * ModelFactory: the class that manages the in-memory representation of the XML MetaModel
 * @package ModelFactory
 */
class ModelFactory
{
	protected $sRootDir;
	protected $oDOMDocument;
	protected $oClasses;
	static protected $aLoadedClasses;
	static protected $aWellKnownParents = array('DBObject', 'CMDBObject','cmdbAbstractObject');
	static protected $aLoadedModules;
	
	
	public function __construct($sRootDir)
	{
		$this->sRootDir = $sRootDir;
		$this->oDOMDocument = new DOMDocument('1.0', 'UTF-8');
		$this->oClasses = $this->oDOMDocument->CreateElement('classes');
		$this->oDOMDocument->AppendChild($this->oClasses);
		self::$aLoadedClasses = array();
		self::$aLoadedModules = array();
	}
	
	public function Dump($oNode = null)
	{
		if (is_null($oNode))
		{
			$oNode = $this->oClasses;
		}
		echo htmlentities($this->oDOMDocument->saveXML($oNode));
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
		foreach($aDataModels as $sXmlFile)
		{
			$oDocument = new DOMDocument('1.0', 'UTF-8');
			$oDocument->load($sXmlFile);
			$oXPath = new DOMXPath($oDocument);
			$oNodeList = $oXPath->query('//*');
			foreach($oNodeList as $oNode)
			{
					$oNode->SetAttribute('_source', $sXmlFile);
			}
			$oXPath = new DOMXPath($oDocument);
			$oNodeList = $oXPath->query('//classes/class');
			foreach($oNodeList as $oNode)
			{
				if ($oNode->hasAttribute('parent'))
				{
					$sParentClass = $oNode->GetAttribute('parent');
				}
				else
				{
					$sParentClass = '';
				}
				$sClassName = $oNode->GetAttribute('name');
				$aClasses[$sClassName] = array('name' => $sClassName, 'parent' => $sParentClass, 'node' => $oNode);
			}
		}
		
		$index = 1;
		do
		{
			$bNothingLoaded = true;
			foreach($aClasses as $sClassName => $aClassData)
			{
				$sOperation = $aClassData['node']->getAttribute('_operation');
				switch($sOperation)
				{
					case 'added':
					case '':
					if (in_array($aClassData['parent'], self::$aWellKnownParents))
					{
						$this->AddClass($aClassData['node'], $sModuleName);
						unset($aClasses[$sClassName]);
						$bNothingLoaded = false;
					}
					else if ($this->ClassNameExists($aClassData['parent']))
					{
						$this->AddClass($aClassData['node'], $sModuleName);
						unset($aClasses[$sClassName]);
						$bNothingLoaded = false;					
					}
					break;
					
					case 'removed':
					unset($aClasses[$sClassName]);
					$this->RemoveClass($sClassName);
					break;
					
					case 'modified':
					unset($aClasses[$sClassName]);
					$this->AlterClass($sClassName, $aClassData['node']);
					break;
				}
			}
			$index++;
		}
		while((count($aClasses)>0) && !$bNothingLoaded);

		// The remaining classes have no known parent, let's add them at the root
		foreach($aClasses as $sClassName => $aClassData)
		{
			$sOperation = $aClassData['node']->getAttribute('_operation');
			switch($sOperation)
			{
				case 'added':
				case '':
				// Add the class as a new root class
				$this->AddClass($aClassData['node'], $sModuleName);
				$oNewNode = $this->oDOMDocument->ImportNode($aClassData['node']);
				break;
				
				case 'removed':
				$this->RemoveClass($sClassName);
				break;
				
				case 'modified':
				//@@TODO Handle the modification of a class here
				$this->AlterClass($sClassName, $aClassData['node']);
				break;
			}
		}			
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
	
	
	/**
	 * Check if the class specified by the given node already exists in the loaded DOM
	 * @param DOMNode $oClassNode The node corresponding to the class to load
	 * @throws Exception
	 * @return bool True if the class exists, false otherwise
	 */
	protected function ClassExists(DOMNode $oClassNode)
	{
		if ($oClassNode->hasAttribute('name'))
		{
			$sClassName = $oClassNode->GetAttribute('name');
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
	protected function ClassNameExists($sClassName)
	{
		return (array_key_exists($sClassName, self::$aLoadedClasses));
	}
	/**
	 * Add the given class to the DOM
	 * @param DOMNode $oClassNode
	 * @param string $sModuleName The name of the module in which this class is declared
	 * @throws Exception
	 */
	public function AddClass(DOMNode $oClassNode, $sModuleName)
	{
		if ($oClassNode->hasAttribute('name'))
		{
			$sClassName = $oClassNode->GetAttribute('name');
		}
		else
		{
			throw new Exception('ModelFactory::AddClass: Cannot add a class with no name');
		}
		if ($this->ClassExists($oClassNode))
		{
			throw new Exception("ModelFactory::AddClass: Cannot add the already existing class $sClassName");
		}
		
		$sParentClass = '';
		if ($oClassNode->hasAttribute('parent'))
		{
			$sParentClass = $oClassNode->GetAttribute('parent');
		}
		
		//echo "Adding class: $sClassName, parent: $sParentClass<br/>";
		if (!in_array($sParentClass, self::$aWellKnownParents) && $this->ClassNameExists($sParentClass))
		{
			// The class is a subclass of a class already loaded, add it under
			self::$aLoadedClasses[$sClassName] = $this->oDOMDocument->ImportNode($oClassNode, true /* bDeep */);
			self::$aLoadedClasses[$sClassName]->SetAttribute('_operation', 'added');
			if ($sModuleName != '')
			{
				self::$aLoadedClasses[$sClassName]->SetAttribute('_created_in', $sModuleName);
			}
			self::$aLoadedClasses[$sParentClass]->AppendChild(self::$aLoadedClasses[$sClassName]);
			$bNothingLoaded = false;
		}
		else if (in_array($sParentClass, self::$aWellKnownParents))
		{
			// Add the class as a new root class
			self::$aLoadedClasses[$sClassName] = $this->oDOMDocument->ImportNode($oClassNode, true /* bDeep */);
			self::$aLoadedClasses[$sClassName]->SetAttribute('_operation', 'added');
			if ($sModuleName != '')
			{
				self::$aLoadedClasses[$sClassName]->SetAttribute('_created_in', $sModuleName);
			}
			$this->oClasses->AppendChild(self::$aLoadedClasses[$sClassName]);
		}
		else
		{
			throw new Exception("ModelFactory::AddClass: Cannot add the class $sClassName, unknown parent class: $sParentClass");
		}
	}
	
	/**
	 * Remove a class from the DOM
	 * @param string $sClass
	 * @throws Exception
	 */
	public function RemoveClass($sClass)
	{
		if (!$this->ClassNameExists($sClass))
		{
			throw new Exception("ModelFactory::RemoveClass: Cannot remove the non existing class $sClass");
		}
		$oClassNode = self::$aLoadedClasses[$sClass];
		if ($oClassNode->getAttribute('_operation') == 'added')
		{
			$oClassNode->parentNode->RemoveChild($oClassNode);
			unset(self::$aLoadedClasses[$sClass]);	
		}
		else
		{
			self::$aLoadedClasses[$sClass]->SetAttribute('_operation', 'removed');
			//TODO: also mark as removed the child classes
		}
		
	}

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
		$sClassName = $oDestNode->getAttribute('name');
		if ($sOriginalName != $sClassName)
		{
			unset(self::$aLoadedClasses[$sOriginalName]);
			self::$aLoadedClasses[$sClassName] = $oDestNode;
		}
		$this->_priv_SetFlag($oDestNode, 'modified');
	}
	
	protected function _priv_AlterNode(DOMNode $oNode, DOMNode $oDeltaNode)
	{
		foreach ($oDeltaNode->attributes as $sName => $oAttrNode)
		{
			$sCurrentValue = $oNode->getAttribute($sName);
			$sNewValue = $oAttrNode->value;
			$oNode->setAttribute($sName, $oAttrNode->value);
		}
		
		$aSrcChildNodes = $oNode->childNodes;
		foreach($oDeltaNode->childNodes as $index => $oChildNode)
		{
			if (!$oChildNode instanceof DOMElement)
			{
				// Text or CData nodes are treated by position
				$sOperation = $oChildNode->parentNode->getAttribute('_operation');
				switch($sOperation)
				{
					case 'removed':
					// ???
					break;
					
					case 'modified':
					case 'replaced':
					case 'added':
					$oNewNode = $this->oDOMDocument->importNode($oChildNode);
					$oSrcChildNode = $aSrcChildNodes->item($index);
					if ($oSrcChildNode)
					{
						$oNode->replaceChild($oNewNode, $oSrcChildNode);
					}
					else
					{
						$oNode->appendChild($oNewNode);
					}
					
					break;
					
					case '':
					// Do nothing
				}
			}
			else
			{
				$sOperation = $oChildNode->getAttribute('_operation');
				$sPath = $oChildNode->tagName;
				$sName = $oChildNode->getAttribute('name');
				if ($sName != '')
				{
					$sPath .= "[@name='$sName']";
				}
				switch($sOperation)
				{
					case 'removed':
					$oToRemove = $this->_priv_GetNodes($sPath, $oNode)->item(0);
					if ($oToRemove != null)
					{
						$this->_priv_SetFlag($oToRemove, 'removed');
					}
					break;
					
					case 'modified':
					$oToModify = $this->_priv_GetNodes($sPath, $oNode)->item(0);
					if ($oToModify != null)
					{
						$this->_priv_AlterNode($oToModify, $oChildNode);
					}
					else
					{
						throw new Exception("Cannot modify the non-existing node '$sPath' in '".$oNode->getNodePath()."'");
					}
					break;
					
					case 'replaced':
					$oNewNode = $this->oDOMDocument->importNode($oChildNode, true); // Import the node and its child nodes
					$oToModify = $this->_priv_GetNodes($sPath, $oNode)->item(0);
					$oNode->replaceChild($oNewNode, $oToModify);	
					break;
					
					case 'added':
					$oNewNode = $this->oDOMDocument->importNode($oChildNode);
					$oNode->appendChild($oNewNode);
					$this->_priv_SetFlag($oNewNode, 'added');
					break;
					
					case '':
					// Do nothing
				}
			}
		}	
	}
	
	public function GetClassXMLTemplate($sName, $sIcon)
	{
		return
<<<EOF
<?xml version="1.0" encoding="utf-8"?>
<class name="$sName" parent="" db_table="" category="" abstract="" key_type="autoincrement" db_key_field="id" db_final_class_field="finalclass">
	<properties>
	<comment/>
	<naming format=""><attributes/></naming>
	<reconciliation><attributes/></reconciliation>
	<display_template/>
	<icon>$sIcon</icon>
	</properties>
	<fields/>
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
		$sXPath = "//class[@_created_in='$sModuleName']";
		if ($bFlattenLayers)
		{
			$sXPath = "//class[@_created_in='$sModuleName' and @_operation!='removed']";
		}
		return $this->_priv_GetNodes($sXPath);
	}
		
	/**
	 * List all classes from the DOM, for a given module
	 * @param string $sModuleNale
	 * @param bool $bFlattenLayers
	 * @throws Exception
	 */
	public function ListAllClasses($bFlattenLayers = true)
	{
		$sXPath = "//class";
		if ($bFlattenLayers)
		{
			$sXPath = "//class[@_operation!='removed']";
		}
		return $this->_priv_GetNodes($sXPath);
	}
	
	public function GetClass($sClassName, $bFlattenLayers = true)
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
		return $oClassNode;
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
		$sXPath = "fields/field[@name='$sAttCode']";
		if ($bFlattenLayers)
		{
			$sXPath = "fields/field[(@name='$sAttCode' and (not(@_operation) or @_operation!='removed'))]";
		}
		$oFieldNode = $this->_priv_GetNodes($sXPath, $oClassNode)->item(0);
		if (($oFieldNode == null) && ($oClassNode->getAttribute('parent') != ''))
		{
			return $this->GetField($oClassNode->getAttribute('parent'), $sAttCode, $bFlattenLayers);
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
			$sXPath = "fields/field[not(@_operation) or @_operation!='removed']";
		}
		return $this->_priv_GetNodes($sXPath, $oClassNode);
	}
	
	public function AddField(DOMNode $oClassNode, $sFieldCode, $sFieldType, $sSQL, $defaultValue, $bIsNullAllowed, $aExtraParams)
	{
		$oNewField = $this->oDOMDocument->createElement('field');
		$oNewField->setAttribute('name', $sFieldCode);
		$this->_priv_AlterField($oNewField, $sFieldType, $sSQL, $defaultValue, $bIsNullAllowed, $aExtraParams);
		$oFields = $oClassNode->getElementsByTagName('fields')->item(0);
		$oFields->AppendChild($oNewField);
		$this->_priv_SetFlag($oNewField, 'added');
	}
	
	public function RemoveField(DOMNode $oClassNode, $sFieldCode)
	{
		$sXPath = "fields/field[@name='$sFieldCode']";
		$oFieldNodes = $this->_priv_GetNodes($sXPath, $oClassNode);
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
		$sXPath = "fields/field[@name='$sFieldCode']";
		$oFieldNodes = $this->_priv_GetNodes($sXPath, $oClassNode);
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
			$oDep->setAttribute('name', $sAttCode);
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
	
	public function _priv_SetFlag($oNode, $sFlagValue)
	{
		$sPreviousFlag = $oNode->getAttribute('_operation');
		if ($sPreviousFlag == 'added')
		{
			// Do nothing ??
		}
		else
		{
			$oNode->setAttribute('_operation', $sFlagValue);
		}
		if ($oNode->tagName != 'class')
		{
			$this->_priv_SetFlag($oNode->parentNode, 'modified');
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
		return $this->_priv_GetNodes($sXPath, $oStateNode);
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
		return $this->_priv_GetNodes($sXPath, $oClassNode);
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
		foreach($oNodes as $oClassNode)
		{
			$sOperation = $oClassNode->GetAttribute('_operation');
			switch($sOperation)
			{
				case 'added':
				case 'modified':
				// marked as added or modified, just reset the flag
				$oClassNode->setAttribute('_operation', '');
				break;
				
				case 'removed':
				// marked as deleted, let's remove the node from the tree
				$oParent = $oClassNode->parentNode;
				$sClass = $oClassNode->GetAttribute('name');
				echo "Calling removeChild...<br/>";
				$oParent->removeChild($oClassNode);
				unset(self::$aLoadedClasses[$sClass]);
				break;
			}
		}
	}
	
	public function ListChanges()
	{
		$sXPath = "//*[@_operation!='']";
		return $this->_priv_GetNodes($sXPath);		
	}
	
	/**
	 * Get the text/XML version of the delta
	 */	
	public function GetDelta()
	{
		$oDelta = new DOMDocument('1.0', 'UTF-8');
		$oRootNode = $oDelta->createElement('classes');
		$oDelta->appendChild($oRootNode);
		
		$this->_priv_ImportModifiedChildren($oDelta, $oRootNode, $this->oDOMDocument->firstChild);
		//file_put_contents($sXMLDestPath, $oDelta->saveXML());
		return $oDelta->saveXML();
	}
	
	
	protected function _priv_ImportModifiedChildren(DOMDocument $oDoc, DOMNode $oDestNode, DOMNode $oNode)
	{
		static $iDepth = 0;
		$iDepth++;
		foreach($oNode->childNodes as $oChildNode)
		{
			$sNodeName = $oChildNode->nodeName;
			$sNodeValue = $oChildNode->nodeValue;
			if (!$oChildNode instanceof DOMElement)
			{
				$sName = '$$';
				$sOperation = $oChildNode->parentNode->getAttribute('_operation');
			}
			else
			{
				$sName = $oChildNode->getAttribute('name');;
				$sOperation = $oChildNode->getAttribute('_operation');
			}
			
//echo str_repeat('+', $iDepth)." $sNodeName [$sName], operation: $sOperation\n";
			
			switch($sOperation)
			{
				case 'removed':
				$oDeletedNode = $oDoc->importNode($oChildNode->cloneNode(false)); // Copies all the node's attributes, but NOT the child nodes
				$oDeletedNode->removeAttribute('_source');
				if ($oDeletedNode->tagName == 'class')
				{
					// classes are always located under the root node
					$oDoc->firstChild->appendChild($oDeletedNode);
				}
				else
				{
					$oDestNode->appendChild($oDeletedNode);
				}
//echo "<p>".str_repeat('+', $iDepth).$oChildNode->getAttribute('name')." was removed...</p>";
				break;

				case 'added':
//echo "<p>".str_repeat('+', $iDepth).$oChildNode->tagName.':'.$oChildNode->getAttribute('name')." was created...</p>";
				$oModifiedNode = $oDoc->importNode($oChildNode, true); // Copies all the node's attributes, and the child nodes as well
				if ($oChildNode instanceof DOMElement)
				{
					$oModifiedNode->removeAttribute('_source');
					if ($oModifiedNode->tagName == 'class')
					{
						// classes are always located under the root node
						$oDoc->firstChild->appendChild($oModifiedNode);
					}
					else
					{
						$oDestNode->appendChild($oModifiedNode);
					}
				}
				else
				{
					$oDestNode->appendChild($oModifiedNode);
				}
				break;
				
				case 'replaced':
//echo "<p>".str_repeat('+', $iDepth).$oChildNode->tagName.':'.$oChildNode->getAttribute('name')." was replaced...</p>";
				$oModifiedNode = $oDoc->importNode($oChildNode, true); // Copies all the node's attributes, and the child nodes as well
				if ($oChildNode instanceof DOMElement)
				{
					$oModifiedNode->removeAttribute('_source');
				}
				$oDestNode->appendChild($oModifiedNode);
				break;
				
				case 'modified':
//echo "<p>".str_repeat('+', $iDepth).$oChildNode->tagName.':'.$oChildNode->getAttribute('name')." was modified...</p>";
				if ($oChildNode instanceof DOMElement)
				{
//echo str_repeat('+', $iDepth)." Copying (NON recursively) the modified node\n";
					$oModifiedNode = $oDoc->importNode($oChildNode, false); // Copies all the node's attributes, but NOT the child nodes
					$oModifiedNode->removeAttribute('_source');
					$this->_priv_ImportModifiedChildren($oDoc, $oModifiedNode, $oChildNode);
					if ($oModifiedNode->tagName == 'class')
					{
						// classes are always located under the root node
						$oDoc->firstChild->appendChild($oModifiedNode);
					}
					else
					{
						$oDestNode->appendChild($oModifiedNode);
					}
				}
				else
				{
//echo str_repeat('+', $iDepth)." Copying (recursively) the modified node\n";
					$oModifiedNode = $oDoc->importNode($oChildNode, true); // Copies all the node's attributes, and the child nodes
					$oDestNode->appendChild($oModifiedNode);
				}
				break;
				
				default:
				// No change: search if there is not a modified child class
				$oModifiedNode = $oDoc->importNode($oChildNode->cloneNode(false)); // Copies all the node's attributes, but NOT the child nodes
//echo str_repeat('+', $iDepth)." Importing (NON recursively) the modified node\n";
				if ($oChildNode instanceof DOMElement)
				{
					$oModifiedNode->removeAttribute('_source');
				}
			}
			if ($oChildNode->tagName == 'class')
			{
//echo "<p>".str_repeat('+', $iDepth)."Checking if a subclass of ".$oChildNode->getAttribute('name')." was modified...</p>";
				// classes are always located under the root node
				$this->_priv_ImportModifiedChildren($oDoc, $oModifiedNode, $oChildNode);
			}
		}
		$iDepth--;
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
	
	/**
	 * Produce the PHP files corresponding to the data model
	 * @param $bTestOnly bool
	 * @return array Array of errors, empty if no error
	 */
	public function Compile($bTestOnly)
	{
		return array();
	}
	
	/**
	 * Extracts some nodes from the DOM
	 * @param string $sXPath A XPath expression
	 * @return DOMNodeList
	 */
	public function _priv_GetNodes($sXPath, $oContextNode = null)
	{
		$oXPath = new DOMXPath($this->oDOMDocument);
		
		if (is_null($oContextNode))
		{
			return $oXPath->query($sXPath);
		}
		else
		{
			return $oXPath->query($sXPath, $oContextNode);
		}
	}
	
	/**
	 * Insert a new node in the DOM
	 * @param DOMNode $oNode
	 * @param DOMNode $oParentNode
	 */
	public function _priv_AddNode(DOMNode $oNode, DOMNode $oParentNode)
	{
	}
	
	/**
	 * Remove a node from the DOM
	 * @param DOMNode $oNode
	 * @param DOMNode $oParentNode
	 */
	public function _priv_RemoveNode(DOMNode $oNode)
	{
	}
	
	/**
	 * Add or modify an attribute of a node
	 * @param DOMNode $oNode
	 * @param string $sAttributeName
	 * @param mixed $atttribueValue
	 */
	public function _priv_SetNodeAttribute(DOMNode $oNode, $sAttributeName, $atttribueValue)
	{
	}
	
	/**
	 * Helper to browse the DOM -could be factorized in ModelFactory
	 * Returns the node directly under the given node, and that is supposed to be always present and unique 
	 */ 
	protected function GetUniqueElement($oDOMNode, $sTagName, $bMustExist = true)
	{
		$oNode = null;
		foreach($oDOMNode->childNodes as $oChildNode)
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
	 * Helper to browse the DOM -could be factorized in ModelFactory
	 * Returns the node directly under the given node, or null is missing 
	 */ 
	protected function GetOptionalElement($oDOMNode, $sTagName)
	{
		return $this->GetUniqueElement($oDOMNode, $sTagName, false);
	}
	
	
	/**
	 * Helper to browse the DOM -could be factorized in ModelFactory
	 * Returns the TEXT of the given node (possibly from several subnodes) 
	 */ 
	protected function GetNodeText($oNode)
	{
		$sText = '';
		foreach($oNode->childNodes as $oChildNode)
		{
			if ($oChildNode instanceof DOMCharacterData) // Base class of DOMText and DOMCdataSection
			{
				$sText .= $oChildNode->wholeText;
			}
		}
		return $sText;
	}
	
	/**
	 * Helper to browse the DOM -could be factorized in ModelFactory
	 * Assumes the given node to be either a text or
	 * <items>
	 *   <item [key]="..."]>value<item>
	 *   <item [key]="..."]>value<item>
	 * </items>
	 * where value can be the either a text or an array of items... recursively 
	 * Returns a PHP array 
	 */ 
	public function GetNodeAsArrayOfItems($oNode)
	{
		$oItems = $this->GetOptionalElement($oNode, 'items');
		if ($oItems)
		{
			$res = array();
			foreach($oItems->childNodes as $oItem)
			{
				// When an attribute is missing
				if ($oItem->hasAttribute('key'))
				{
					$key = $oItem->getAttribute('key');
					$res[$key] = $this->GetNodeAsArrayOfItems($oItem);
				}
				else
				{
					$res[] = $this->GetNodeAsArrayOfItems($oItem);
				}
			}
		}
		else
		{
			$res = $this->GetNodeText($oNode);
		}
		return $res;
	}
	
}