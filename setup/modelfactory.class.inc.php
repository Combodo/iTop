<?php
/**
 * Copyright (C) 2013-2020 Combodo SARL
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

/**
 * ModelFactory: in-memory manipulation of the XML MetaModel
 */

require_once(APPROOT.'setup/moduleinstaller.class.inc.php');
require_once(APPROOT.'setup/itopdesignformat.class.inc.php');
require_once(APPROOT.'setup/compat/domcompat.php');
require_once(APPROOT.'core/designdocument.class.inc.php');

/**
 * Special exception type thrown when the XML stacking fails
 *
 */
class MFException extends Exception
{
	/**
	 * @var integer
	 */
	protected $iSourceLineNumber;
	/**
	 * @var string
	 */
	protected $sXPath;
	/**
	 * @var string
	 */
	protected $sExtraInfo;

	const COULD_NOT_BE_ADDED = 1;
	const COULD_NOT_BE_DELETED = 2;
	const COULD_NOT_BE_MODIFIED_NOT_FOUND = 3;
	const COULD_NOT_BE_MODIFIED_ALREADY_DELETED = 4;
	const INVALID_DELTA = 5;
	const ALREADY_DELETED = 6;
	const NOT_FOUND = 7;
	const PARENT_NOT_FOUND = 8;


	/**
	 * MFException constructor.
	 *
	 * @inheritDoc
	 */
	public function __construct($message = null, $code = null, $iSourceLineNumber = 0, $sXPath = '', $sExtraInfo = '', $previous = null)
	{
		parent::__construct($message, $code, $previous);
		$this->iSourceLineNumber = $iSourceLineNumber;
		$this->sXPath = $sXPath;
		$this->sExtraInfo = $sExtraInfo;
	}

	/**
	 * Get the source line number where the problem happened
	 *
	 * @return number
	 */
	public function GetSourceLineNumber()
	{
		return $this->iSourceLineNumber;
	}

	/**
	 * Get the XPath in the whole document where the problem happened
	 *
	 * @return string
	 */
	public function GetXPath()
	{
		return $this->sXPath;
	}

	/**
	 * Get some extra info (depending on the exception's code), like the invalid value for the _delta attribute
	 *
	 * @return string
	 */
	public function GetExtraInfo()
	{
		return $this->sExtraInfo;
	}
}

/**
 * ModelFactoryModule: the representation of a Module (i.e. element that can be selected during the setup)
 *
 * @package ModelFactory
 */
class MFModule
{
	/**
	 * @var string
	 */
	protected $sId;
	/**
	 * @var string
	 */
	protected $sName;
	/**
	 * @var string
	 */
	protected $sVersion;
	/**
	 * @var string
	 */
	protected $sRootDir;
	/**
	 * @var string
	 */
	protected $sLabel;
	/**
	 * @var array
	 */
	protected $aDataModels;
	/**
	 * @var bool
	 */
	protected $bAutoSelect;
	/**
	 * @var string
	 */
	protected $sAutoSelect;
	/**
	 * @var array
	 */
	protected $aFilesToInclude;

	/**
	 * MFModule constructor.
	 *
	 * @param string $sId
	 * @param string $sRootDir
	 * @param string $sLabel
	 * @param bool $bAutoSelect
	 */
	public function __construct($sId, $sRootDir, $sLabel, $bAutoSelect = false)
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
		$this->bAutoSelect = $bAutoSelect;
		$this->sAutoSelect = 'false';
		$this->aFilesToInclude = array('addons' => array(), 'business' => array(), 'webservices' => array(),);

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


	/**
	 * @return string
	 */
	public function GetId()
	{
		return $this->sId;
	}

	/**
	 * @return string
	 */
	public function GetName()
	{
		return $this->sName;
	}

	/**
	 * @return string
	 */
	public function GetVersion()
	{
		return $this->sVersion;
	}

	/**
	 * @return string
	 */
	public function GetLabel()
	{
		return $this->sLabel;
	}

	/**
	 * @return string
	 */
	public function GetRootDir()
	{
		return $this->sRootDir;
	}

	/**
	 * @return string
	 */
	public function GetModuleDir()
	{
		return basename($this->sRootDir);
	}

	/**
	 * @return array
	 */
	public function GetDataModelFiles()
	{
		return $this->aDataModels;
	}

	/**
	 * List all classes in this module
	 *
	 * @return array
	 */
	public function ListClasses()
	{
		return array();
	}

	/**
	 * @return array
	 */
	public function GetDictionaryFiles()
	{
		$aDictionaries = array();
		if ($hDir = opendir($this->sRootDir))
		{
			while (($sFile = readdir($hDir)) !== false)
			{
				$aMatches = array();
				if (preg_match("/^[^\\.]+.dict.".$this->sName.'.php$/i', $sFile,
					$aMatches)) // Dictionary files are named like <Lang>.dict.<ModuleName>.php
				{
					$aDictionaries[] = $this->sRootDir.'/'.$sFile;
				}
			}
			closedir($hDir);
		}

		return $aDictionaries;
	}

	/**
	 * @return bool
	 */
	public function IsAutoSelect()
	{
		return $this->bAutoSelect;
	}

	/**
	 * @param string $sAutoSelect
	 */
	public function SetAutoSelect($sAutoSelect)
	{
		$this->sAutoSelect = $sAutoSelect;
	}

	/**
	 * @return string
	 */
	public function GetAutoSelect()
	{
		return $this->sAutoSelect;
	}

	/**
	 * @param array $aFiles
	 * @param string $sCategory
	 */
	public function SetFilesToInclude($aFiles, $sCategory)
	{
		// Now ModuleDiscovery provides us directly with relative paths... nothing to do
		$this->aFilesToInclude[$sCategory] = $aFiles;
	}

	/**
	 * @param string $sCategory
	 *
	 * @return mixed
	 */
	public function GetFilesToInclude($sCategory)
	{
		return $this->aFilesToInclude[$sCategory];
	}

}

/**
 * MFDeltaModule: an optional module, made of a single file
 *
 * @package ModelFactory
 */
class MFDeltaModule extends MFModule
{
	/**
	 * MFDeltaModule constructor.
	 *
	 * @param $sDeltaFile
	 */
	public function __construct($sDeltaFile)
	{
		parent::__construct('datamodel-delta', '', 'Additional Delta');
		$this->sName = 'delta';
		$this->sVersion = '1.0';
		$this->aDataModels = array($sDeltaFile);
		$this->aFilesToInclude = array('addons' => array(), 'business' => array(), 'webservices' => array(),);
	}

	/**
	 * @inheritDoc
	 */
	public function GetName()
	{
		return ''; // Objects created inside this pseudo module retain their original module's name
	}

	/**
	 * @inheritDoc
	 */
	public function GetRootDir()
	{
		return '';
	}

	/**
	 * @inheritDoc
	 */
	public function GetModuleDir()
	{
		return '';
	}

	/**
	 * @inheritDoc
	 */
	public function GetDictionaryFiles()
	{
		return array();
	}
}

/**
 * MFDeltaModule: an optional module, made of a single file
 *
 * @package ModelFactory
 */
class MFCoreModule extends MFModule
{
	/**
	 * MFCoreModule constructor.
	 *
	 * @param $sName
	 * @param $sLabel
	 * @param $sDeltaFile
	 */
	public function __construct($sName, $sLabel, $sDeltaFile)
	{
		parent::__construct($sName, '', $sLabel);
		$this->sName = $sName;
		$this->sVersion = '1.0';
		$this->aDataModels = array($sDeltaFile);
		$this->aFilesToInclude = array('addons' => array(), 'business' => array(), 'webservices' => array(),);
	}

	/**
	 * @inheritDoc
	 */
	public function GetRootDir()
	{
		return '';
	}

	/**
	 * @inheritDoc
	 */
	public function GetModuleDir()
	{
		return '';
	}

	/**
	 * @inheritDoc
	 */
	public function GetDictionaryFiles()
	{
		return array();
	}
}

/**
 * MFDictModule: an optional module, consisting only of dictionaries
 *
 * @package ModelFactory
 */
class MFDictModule extends MFModule
{
	/**
	 * MFDictModule constructor.
	 *
	 * @param $sName
	 * @param $sLabel
	 * @param $sRootDir
	 */
	public function __construct($sName, $sLabel, $sRootDir)
	{
		parent::__construct($sName, $sRootDir, $sLabel);
		$this->sName = $sName;
		$this->sVersion = '1.0';
		$this->aDataModels = array();
		$this->aFilesToInclude = array('addons' => array(), 'business' => array(), 'webservices' => array(),);
	}

	/**
	 * @inheritDoc
	 */
	public function GetRootDir()
	{
		return '';
	}

	/**
	 * @inheritDoc
	 */
	public function GetModuleDir()
	{
		return '';
	}

	/**
	 * @inheritDoc
	 */
	public function GetDictionaryFiles()
	{
		$aDictionaries = array();
		if ($hDir = opendir($this->sRootDir))
		{
			while (($sFile = readdir($hDir)) !== false)
			{
				$aMatches = array();
				if (preg_match("/^.*dictionary\\.itop.*.php$/i", $sFile,
					$aMatches)) // Dictionary files are named like <Lang>.dict.<ModuleName>.php
				{
					$aDictionaries[] = $this->sRootDir.'/'.$sFile;
				}
			}
			closedir($hDir);
		}

		return $aDictionaries;
	}
}


/**
 * ModelFactory: the class that manages the in-memory representation of the XML MetaModel
 *
 * @package ModelFactory
 */
class ModelFactory
{
	protected $aRootDirs;
	protected $oDOMDocument;
	protected $oRoot;
	protected $oModules;
	protected $oClasses;
	protected $oMenus;
	protected $oMeta;
	protected $oDictionaries;
	static protected $aLoadedClasses;
	static protected $aWellKnownParents = array('DBObject', 'CMDBObject', 'cmdbAbstractObject');
	static protected $aLoadedModules;
	static protected $aLoadErrors;
	protected $aDict;
	protected $aDictKeys;


	/**
	 * ModelFactory constructor.
	 *
	 * @param $aRootDirs
	 * @param array $aRootNodeExtensions
	 *
	 * @throws \Exception
	 */
	public function __construct($aRootDirs, $aRootNodeExtensions = array())
	{
		$this->aDict = array();
		$this->aDictKeys = array();
		$this->aRootDirs = $aRootDirs;
		$this->oDOMDocument = new MFDocument();
		$this->oRoot = $this->oDOMDocument->CreateElement('itop_design');
		$this->oRoot->setAttribute('xmlns:xsi', "http://www.w3.org/2001/XMLSchema-instance");
		$this->oRoot->setAttribute('version', ITOP_DESIGN_LATEST_VERSION);
		$this->oDOMDocument->AppendChild($this->oRoot);
		$this->oModules = $this->oDOMDocument->CreateElement('loaded_modules');
		$this->oRoot->AppendChild($this->oModules);
		$this->oClasses = $this->oDOMDocument->CreateElement('classes');
		$this->oRoot->AppendChild($this->oClasses);
		$this->oDictionaries = $this->oDOMDocument->CreateElement('dictionaries');
		$this->oRoot->AppendChild($this->oDictionaries);

		foreach (self::$aWellKnownParents as $sWellKnownParent)
		{
			$this->AddWellKnownParent($sWellKnownParent);
		}
		$this->oMenus = $this->oDOMDocument->CreateElement('menus');
		$this->oRoot->AppendChild($this->oMenus);

		$this->oMeta = $this->oDOMDocument->CreateElement('meta');
		$this->oRoot->AppendChild($this->oMeta);

		foreach ($aRootNodeExtensions as $sElementName)
		{
			$oElement = $this->oDOMDocument->CreateElement($sElementName);
			$this->oRoot->AppendChild($oElement);
		}
		self::$aLoadedModules = array();
		self::$aLoadErrors = array();

		libxml_use_internal_errors(true);
	}

	/**
	 * @param null $oNode
	 * @param bool $bReturnRes
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function Dump($oNode = null, $bReturnRes = false)
	{
		if (is_null($oNode))
		{
			$oNode = $this->oRoot;
		}

		return $oNode->Dump($bReturnRes);
	}

	/**
	 * @param $sCacheFile
	 */
	public function LoadFromFile($sCacheFile)
	{
		$this->oDOMDocument->load($sCacheFile);
		$this->oRoot = $this->oDOMDocument->firstChild;

		$this->oModules = $this->oRoot->getElementsByTagName('loaded_modules')->item(0);
		self::$aLoadedModules = array();
		foreach ($this->oModules->getElementsByTagName('module') as $oModuleNode)
		{
			$sId = $oModuleNode->getAttribute('id');
			$sRootDir = $oModuleNode->GetChildText('root_dir');
			$sLabel = $oModuleNode->GetChildText('label');
			self::$aLoadedModules[] = new MFModule($sId, $sRootDir, $sLabel);
		}
	}

	/**
	 * @param $sCacheFile
	 */
	public function SaveToFile($sCacheFile)
	{
		$this->oDOMDocument->save($sCacheFile);
	}

	/**
	 * To progressively replace LoadModule
	 *
	 * @param \MFElement $oSourceNode
	 * @param \MFDocument|\MFElement $oTargetParentNode
	 *
	 * @throws \MFException
	 * @throws \DOMFormatException
	 * @throws \Exception
	 */
	public function LoadDelta($oSourceNode, $oTargetParentNode)
	{
		if (!$oSourceNode instanceof DOMElement)
		{
			return;
		}
		//echo "Load $oSourceNode->tagName::".$oSourceNode->getAttribute('id')." (".$oSourceNode->getAttribute('_delta').")<br/>\n";
		$oTarget = $this->oDOMDocument;

		$sDeltaSpec = $oSourceNode->getAttribute('_delta');
		if (($oSourceNode->tagName == 'class') && ($oSourceNode->parentNode->tagName == 'classes') && ($oSourceNode->parentNode->parentNode->tagName == 'itop_design'))
		{
			$sParentId = $oSourceNode->GetChildText('parent');
			if (($sDeltaSpec == 'define') || ($sDeltaSpec == 'force'))
			{
				// This tag is organized in hierarchy: determine the real parent node (as a subnode of the current node)
				$oTargetParentNode = $oTarget->GetNodeById('/itop_design/classes//class', $sParentId)->item(0);

				if (!$oTargetParentNode)
				{
					// echo "Dumping target doc - looking for '$sParentId'<br/>\n";
					// $this->oDOMDocument->firstChild->Dump();
					$sPath = MFDocument::GetItopNodePath($oSourceNode);
					$iLine = $oSourceNode->getLineNo();
					throw new MFException($sPath.' at line '.$iLine.": parent class '$sParentId' could not be found",
						MFException::PARENT_NOT_FOUND, $iLine, $sPath, $sParentId);
				}
			}
			else
			{
				$oTargetNode = $oTarget->GetNodeById('/itop_design/classes//class', $oSourceNode->getAttribute('id'))->item(0);
				if (!$oTargetNode)
				{
					if ($sDeltaSpec === 'if_exists')
					{
						// Just ignore it
					}
					else
					{
						// echo "Dumping target doc - looking for '".$oSourceNode->getAttribute('id')."'<br/>\n";
						// $this->oDOMDocument->firstChild->Dump();
						$sPath = MFDocument::GetItopNodePath($oSourceNode);
						$iLine = $oSourceNode->getLineNo();
						throw new MFException($sPath.' at line '.$iLine.": could not be found", MFException::NOT_FOUND, $iLine, $sPath);

					}
				}
				else
				{
					$oTargetParentNode = $oTargetNode->parentNode;
					if (($sDeltaSpec == 'redefine') && ($oTargetParentNode->getAttribute('id') != $sParentId))
					{
						// A class that has moved <=> deletion and creation elsewhere
						$oTargetParentNode = $oTarget->GetNodeById('/itop_design/classes//class', $sParentId)->item(0);
						$oTargetNode->Delete();
						$oSourceNode->setAttribute('_delta', 'define');
						$sDeltaSpec = 'define';
					}
				}

			}
		}

		switch ($sDeltaSpec)
		{
			case 'if_exists':
			case 'must_exist':
			case 'merge':
			case '':
				$bMustExist = ($sDeltaSpec == 'must_exist');
				$bIfExists = ($sDeltaSpec == 'if_exists');
				$sSearchId = $oSourceNode->hasAttribute('_rename_from') ? $oSourceNode->getAttribute('_rename_from') : $oSourceNode->getAttribute('id');
				$oTargetNode = $oSourceNode->MergeInto($oTargetParentNode, $sSearchId, $bMustExist, $bIfExists);
				if ($oTargetNode)
				{
					foreach ($oSourceNode->childNodes as $oSourceChild)
					{
						// Continue deeper
						$this->LoadDelta($oSourceChild, $oTargetNode);
					}
				}
				break;

			case 'define_if_not_exists':
				$oExistingNode = $oTargetParentNode->_FindChildNode($oSourceNode);
				if (($oExistingNode == null) || ($oExistingNode->getAttribute('_alteration') == 'removed'))
				{
					// Same as 'define' below
					$oTargetNode = $oTarget->ImportNode($oSourceNode, true);
					$oTargetParentNode->AddChildNode($oTargetNode);
				}
				else
				{
					$oTargetNode = $oExistingNode;
				}
				$oTargetNode->setAttribute('_alteration', 'needed');
				break;

			case 'define':
				// New node - copy child nodes as well
				$oTargetNode = $oTarget->ImportNode($oSourceNode, true);
				$oTargetParentNode->AddChildNode($oTargetNode);
				break;

			case 'force':
				// Force node - copy child nodes as well
				$oTargetNode = $oTarget->ImportNode($oSourceNode, true);
				$oTargetParentNode->SetChildNode($oTargetNode, null, true);
				break;

			case 'redefine':
				// Replace the existing node by the given node - copy child nodes as well
				$oTargetNode = $oTarget->ImportNode($oSourceNode, true);
				$sSearchId = $oSourceNode->hasAttribute('_rename_from') ? $oSourceNode->getAttribute('_rename_from') : $oSourceNode->getAttribute('id');
				$oTargetParentNode->RedefineChildNode($oTargetNode, $sSearchId);
				break;

			case 'delete_if_exists':
				$oTargetNode = $oTargetParentNode->_FindChildNode($oSourceNode);
				if (($oTargetNode !== null) && ($oTargetNode->getAttribute('_alteration') !== 'removed'))
				{
					// Delete the node if it actually exists and is not already marked as deleted
					$oTargetNode->Delete();
				}
				// otherwise fail silently
				break;

			case 'delete':
				$oTargetNode = $oTargetParentNode->_FindChildNode($oSourceNode);
				$sPath = MFDocument::GetItopNodePath($oSourceNode);
				$iLine = $oSourceNode->getLineNo();
				if ($oTargetNode == null)
				{
					throw new MFException($sPath.' at line '.$iLine.": could not be deleted (not found)", MFException::COULD_NOT_BE_DELETED,
						$iLine, $sPath);
				}
				if ($oTargetNode->getAttribute('_alteration') == 'removed')
				{
					throw new MFException($sPath.' at line '.$iLine.": could not be deleted (already marked as deleted)",
						MFException::ALREADY_DELETED, $iLine, $sPath);
				}
				$oTargetNode->Delete();
				break;

			default:
				$sPath = MFDocument::GetItopNodePath($oSourceNode);
				$iLine = $oSourceNode->getLineNo();
				throw new MFException($sPath.' at line '.$iLine.": unexpected value for attribute _delta: '".$sDeltaSpec."'",
					MFException::INVALID_DELTA, $iLine, $sPath, $sDeltaSpec);
		}

		if ($oTargetNode)
		{
			if ($oSourceNode->hasAttribute('_rename_from'))
			{
				$oTargetNode->Rename($oSourceNode->getAttribute('id'));
			}
			if ($oTargetNode->hasAttribute('_delta'))
			{
				$oTargetNode->removeAttribute('_delta');
			}
		}
	}

	/**
	 * Loads the definitions corresponding to the given Module
	 *
	 * @param MFModule $oModule
	 * @param array $aLanguages The list of languages to process (for the dictionaries). If empty all languages are kept
	 *
	 * @throws \Exception
	 */
	public function LoadModule(MFModule $oModule, $aLanguages = array())
	{
		try
		{
			$aDataModels = $oModule->GetDataModelFiles();
			$sModuleName = $oModule->GetName();
			self::$aLoadedModules[] = $oModule;

			// For persistence in the cache
			$oModuleNode = $this->oDOMDocument->CreateElement('module');
			$oModuleNode->setAttribute('id', $oModule->GetId());
			$oModuleNode->AppendChild($this->oDOMDocument->CreateElement('root_dir', $oModule->GetRootDir()));
			$oModuleNode->AppendChild($this->oDOMDocument->CreateElement('label', $oModule->GetLabel()));

			$this->oModules->AppendChild($oModuleNode);

			foreach ($aDataModels as $sXmlFile)
			{
				$oDocument = new MFDocument();
				libxml_clear_errors();
				$oDocument->load($sXmlFile);
				$aErrors = libxml_get_errors();
				if (count($aErrors) > 0)
				{
					throw new Exception($this->GetXMLErrorMessage($aErrors));
				}

				$oXPath = new DOMXPath($oDocument);
				$oNodeList = $oXPath->query('/itop_design/classes//class');
				foreach ($oNodeList as $oNode)
				{
					if ($oNode->getAttribute('_created_in') == '')
					{
						$oNode->SetAttribute('_created_in', $sModuleName);
					}
				}
				$oNodeList = $oXPath->query('/itop_design/constants/constant');
				foreach ($oNodeList as $oNode)
				{
					if ($oNode->getAttribute('_created_in') == '')
					{
						$oNode->SetAttribute('_created_in', $sModuleName);
					}
				}
				$oNodeList = $oXPath->query('/itop_design/menus/menu');
				foreach ($oNodeList as $oNode)
				{
					if ($oNode->getAttribute('_created_in') == '')
					{
						$oNode->SetAttribute('_created_in', $sModuleName);
					}
				}
				$oUserRightsNode = $oXPath->query('/itop_design/user_rights')->item(0);
				if ($oUserRightsNode)
				{
					if ($oUserRightsNode->getAttribute('_created_in') == '')
					{
						$oUserRightsNode->SetAttribute('_created_in', $sModuleName);
					}
				}

				$oAlteredNodes = $oXPath->query('/itop_design//*[@_delta]');
				if ($oAlteredNodes->length > 0)
				{
					foreach ($oAlteredNodes as $oAlteredNode)
					{
						$oAlteredNode->SetAttribute('_altered_in', $sModuleName);
					}
				}

				$oFormat = new iTopDesignFormat($oDocument);
				if (!$oFormat->Convert())
				{
					$sError = implode(', ', $oFormat->GetErrors());
					throw new Exception("Cannot load module $sModuleName, failed to upgrade to datamodel format of: $sXmlFile. Reason(s): $sError");
				}

				$oDeltaRoot = $oDocument->childNodes->item(0);
				$this->LoadDelta($oDeltaRoot, $this->oDOMDocument);
			}

			$aDictionaries = $oModule->GetDictionaryFiles();

			$sPHPFile = 'undefined';
			try
			{
				$this->ResetTempDictionary();
				foreach ($aDictionaries as $sPHPFile)
				{
					$sDictFileContents = file_get_contents($sPHPFile);
					$sDictFileContents = str_replace(array('<'.'?'.'php', '?'.'>'), '', $sDictFileContents);
					$sDictFileContents = str_replace('Dict::Add', '$this->AddToTempDictionary', $sDictFileContents);
					eval($sDictFileContents);
				}

				foreach ($this->aDict as $sLanguageCode => $aDictDefinition)
				{
					if ((count($aLanguages) > 0) && !in_array($sLanguageCode, $aLanguages))
					{
						// skip some languages if the parameter says so
						continue;
					}

					$oNodes = $this->GetNodeById('dictionary', $sLanguageCode, $this->oDictionaries);
					if ($oNodes->length == 0)
					{
						$oXmlDict = $this->oDOMDocument->CreateElement('dictionary');
						$oXmlDict->setAttribute('id', $sLanguageCode);
						$this->oDictionaries->AddChildNode($oXmlDict);
						$oXmlEntries = $this->oDOMDocument->CreateElement('english_description', $aDictDefinition['english_description']);
						$oXmlDict->AppendChild($oXmlEntries);
						$oXmlEntries = $this->oDOMDocument->CreateElement('localized_description',
							$aDictDefinition['localized_description']);
						$oXmlDict->AppendChild($oXmlEntries);
						$oXmlEntries = $this->oDOMDocument->CreateElement('entries');
						$oXmlDict->AppendChild($oXmlEntries);
					}
					else
					{
						$oXmlDict = $oNodes->item(0);
						$oXmlEntries = $oXmlDict->GetUniqueElement('entries');
					}

					foreach ($aDictDefinition['entries'] as $sCode => $sLabel)
					{

						$oXmlEntry = $this->oDOMDocument->CreateElement('entry');
						$oXmlEntry->setAttribute('id', $sCode);
						$oXmlValue = $this->oDOMDocument->CreateCDATASection($sLabel);
						$oXmlEntry->appendChild($oXmlValue);
						if (array_key_exists($sLanguageCode, $this->aDictKeys) && array_key_exists($sCode,
								$this->aDictKeys[$sLanguageCode]))
						{
							$oMe = $this->aDictKeys[$sLanguageCode][$sCode];
							$sFlag = $oMe->getAttribute('_alteration');
							$oMe->parentNode->replaceChild($oXmlEntry, $oMe);
							$sNewFlag = $sFlag;
							if ($sFlag == '')
							{
								$sNewFlag = 'replaced';
							}
							$oXmlEntry->setAttribute('_alteration', $sNewFlag);

						}
						else
						{
							$oXmlEntry->setAttribute('_alteration', 'added');
							$oXmlEntries->appendChild($oXmlEntry);
						}
						$this->aDictKeys[$sLanguageCode][$sCode] = $oXmlEntry;
					}
				}
			}
			catch (Exception $e)
			{
				throw new Exception('Failed to load dictionary file "'.$sPHPFile.'", reason: '.$e->getMessage());
			}

		}
		catch (Exception $e)
		{
			$aLoadedModuleNames = array();
			foreach (self::$aLoadedModules as $oModule)
			{
				$aLoadedModuleNames[] = $oModule->GetName();
			}
			throw new Exception('Error loading module "'.$oModule->GetName().'": '.$e->getMessage().' - Loaded modules: '.implode(',',
					$aLoadedModuleNames));
		}
	}

	/**
	 * Collects the PHP Dict entries into the ModelFactory for transforming the dictionary into an XML structure
	 *
	 * @param string $sLanguageCode The language code
	 * @param string $sEnglishLanguageDesc English description of the language (unused but kept for API compatibility)
	 * @param string $sLocalizedLanguageDesc Localized description of the language (unused but kept for API compatibility)
	 * @param array $aEntries The entries to load: string_code => translation
	 */
	protected function AddToTempDictionary($sLanguageCode, $sEnglishLanguageDesc, $sLocalizedLanguageDesc, $aEntries)
	{
		$this->aDict[$sLanguageCode]['english_description'] = $sEnglishLanguageDesc;
		$this->aDict[$sLanguageCode]['localized_description'] = $sLocalizedLanguageDesc;
		if (!array_key_exists('entries', $this->aDict[$sLanguageCode]))
		{
			$this->aDict[$sLanguageCode]['entries'] = array();
		}

		foreach ($aEntries as $sKey => $sValue)
		{
			$this->aDict[$sLanguageCode]['entries'][$sKey] = $sValue;
		}
	}

	protected function ResetTempDictionary()
	{
		$this->aDict = array();
	}

	/**
	 *    XML load errors (XML format and validation)
	 *
	 * @Deprecated Errors are now sent by Exception
	 */
	function HasLoadErrors()
	{
		return (count(self::$aLoadErrors) > 0);
	}

	/**
	 * @Deprecated Errors are now sent by Exception
	 * @return array
	 */
	function GetLoadErrors()
	{
		return self::$aLoadErrors;
	}

	/**
	 * @param array $aErrors
	 *
	 * @return string
	 */
	protected function GetXMLErrorMessage($aErrors)
	{
		$sMessage = "Data model source file ({$aErrors[0]->file}) could not be loaded : \n";
		foreach ($aErrors as $oXmlError)
		{
			// XML messages already ends with \n
			$sMessage .= $oXmlError->message;
		}

		return $sMessage;
	}

	/**
	 * @param bool $bExcludeWorkspace
	 *
	 * @return MFModule[]
	 */
	function GetLoadedModules($bExcludeWorkspace = true)
	{
		if ($bExcludeWorkspace)
		{
			$aModules = array();
			foreach (self::$aLoadedModules as $oModule)
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


	/**
	 * @param $sModuleName
	 *
	 * @return mixed|null
	 */
	function GetModule($sModuleName)
	{
		foreach (self::$aLoadedModules as $oModule)
		{
			if ($oModule->GetName() == $sModuleName)
			{
				return $oModule;
			}
		}

		return null;
	}

	/**
	 * @param $sTagName
	 * @param string $sValue
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function CreateElement($sTagName, $sValue = '')
	{
		return $this->oDOMDocument->createElement($sTagName, $sValue);
	}

	/**
	 * @param $sXPath
	 * @param $sId
	 * @param null $oContextNode
	 *
	 * @return \DOMNodeList
	 */
	public function GetNodeById($sXPath, $sId, $oContextNode = null)
	{
		return $this->oDOMDocument->GetNodeById($sXPath, $sId, $oContextNode);
	}

	/**
	 * Check if the class specified by the given node already exists in the loaded DOM
	 *
	 * @param DOMNode $oClassNode The node corresponding to the class to load
	 *
	 * @return bool True if the class exists, false otherwise
	 * @throws Exception
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
	 *
	 * @param string $sClassName The node corresponding to the class to load
	 * @param bool $bIncludeMetas Look for $sClassName also in meta declaration (PHP classes) if not found in XML classes
	 *
	 * @return bool True if the class exists, false otherwise
	 * @throws \Exception
	 *
	 */
	protected function ClassNameExists($sClassName, $bIncludeMetas = false)
	{
		return !is_null($this->GetClass($sClassName, $bIncludeMetas));
	}

	/**
	 * Add the given class to the DOM
	 *
	 * @param DOMNode $oClassNode
	 * @param string $sModuleName The name of the module in which this class is declared
	 *
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

			if (substr($sParentClass, 0, 1) == '/') // Convention for well known parent classes
			{
				// Remove the leading slash character
				$oParentNameNode = $oClassNode->GetOptionalElement('parent')->firstChild; // Get the DOMCharacterData node
				$oParentNameNode->data = substr($sParentClass, 1);
			}
		}
	}

	/**
	 * @param $sName
	 * @param $sIcon
	 *
	 * @return string
	 */
	public function GetClassXMLTemplate($sName, $sIcon)
	{
		$sHeader = '<?'.'xml version="1.0" encoding="utf-8"?'.'>';

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
	 * List all constants from the DOM, for a given module
	 *
	 * @param string $sModuleName
	 *
	 * @return \DOMNodeList
	 * @throws Exception
	 */
	public function ListConstants($sModuleName)
	{
		return $this->GetNodes("/itop_design/constants/constant[@_created_in='$sModuleName']");
	}

	/**
	 * List all classes from the DOM, for a given module
	 *
	 * @param string $sModuleName
	 *
	 * @return \DOMNodeList
	 * @throws Exception
	 */
	public function ListClasses($sModuleName)
	{
		return $this->GetNodes("/itop_design/classes//class[@id][@_created_in='$sModuleName']");
	}

	/**
	 * List all classes from the DOM
	 *
	 * @param bool $bIncludeMetas Also look for meta declaration (PHP classes) in addition to XML classes
	 *
	 * @return \DOMNodeList
	 */
	public function ListAllClasses($bIncludeMetas = false)
	{
		$sXPath = "/itop_design/classes//class[@id]";
		if ($bIncludeMetas === true)
		{
			$sXPath .= "|/itop_design/meta/classes/class[@id]";
		}

		return $this->GetNodes($sXPath);
	}

	/**
	 * List top level (non abstract) classes having child classes
	 *
	 * @throws Exception
	 */
	public function ListRootClasses()
	{
		return $this->GetNodes("/itop_design/classes/class/class[@id][class]");
	}

	/**
	 * @param string $sClassName
	 * @param bool $bIncludeMetas Look for $sClassName also in meta declaration (PHP classes) if not found in XML classes
	 *
	 * @return \MFElement|null
	 */
	public function GetClass($sClassName, $bIncludeMetas = false)
	{
		// Check if class among XML classes
		/** @var \MFElemen|null $oClassNode */
		$oClassNode = $this->GetNodes("/itop_design/classes//class[@id='$sClassName']")->item(0);

		// If not, check if class among exposed meta classes (PHP classes)
		if (is_null($oClassNode) && ($bIncludeMetas === true))
		{
			/** @var \MFElement|null $oClassNode */
			$oClassNode = $this->GetNodes("/itop_design/meta/classes/class[@id='$sClassName']")->item(0);
		}

		return $oClassNode;
	}

	/**
	 * @param string $sWellKnownParent
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function AddWellKnownParent($sWellKnownParent)
	{
		$oWKClass = $this->oDOMDocument->CreateElement('class');
		$oWKClass->setAttribute('id', $sWellKnownParent);
		$this->oClasses->AppendChild($oWKClass);

		return $oWKClass;
	}

	/**
	 * @param $oClassNode
	 *
	 * @return \DOMNodeList
	 */
	public function GetChildClasses($oClassNode)
	{
		return $this->GetNodes("class", $oClassNode);
	}


	/**
	 * @param string $sClassName
	 * @param string $sAttCode
	 *
	 * @return \MFElement|null
	 * @throws \Exception
	 */
	public function GetField($sClassName, $sAttCode)
	{
		if (!$this->ClassNameExists($sClassName))
		{
			return null;
		}
		$oClassNode = self::$aLoadedClasses[$sClassName];
		/** @var \MFElement|null $oFieldNode */
		$oFieldNode = $this->GetNodes("fields/field[@id='$sAttCode']", $oClassNode)->item(0);
		if (($oFieldNode == null) && ($sParentClass = $oClassNode->GetChildText('parent')))
		{
			return $this->GetField($sParentClass, $sAttCode);
		}

		return $oFieldNode;
	}

	/**
	 * List all classes from the DOM
	 *
	 * @param \DOMNode $oClassNode
	 *
	 * @return \DOMNodeList
	 */
	public function ListFields(DOMNode $oClassNode)
	{
		return $this->GetNodes("fields/field", $oClassNode);
	}

	/**
	 * List all transitions from a given state
	 *
	 * @param DOMNode $oStateNode The state
	 *
	 * @return \DOMNodeList
	 * @throws Exception
	 */
	public function ListTransitions(DOMNode $oStateNode)
	{
		return $this->GetNodes("transitions/transition", $oStateNode);
	}

	/**
	 * List all states of a given class
	 *
	 * @param DOMNode $oClassNode The class
	 *
	 * @return \DOMNodeList
	 * @throws Exception
	 */
	public function ListStates(DOMNode $oClassNode)
	{
		return $this->GetNodes("lifecycle/states/state", $oClassNode);
	}

	/**
	 * @return mixed
	 */
	public function ApplyChanges()
	{
		return $this->oRoot->ApplyChanges();
	}

	/**
	 * @return mixed
	 */
	public function ListChanges()
	{
		return $this->oRoot->ListChanges();
	}


	/**
	 * Import the node into the delta
	 *
	 * @param $oNodeClone
	 *
	 * @return mixed
	 */
	protected function SetDeltaFlags($oNodeClone)
	{
		$sAlteration = $oNodeClone->getAttribute('_alteration');
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
			case 'needed':
				$oNodeClone->setAttribute('_delta', 'define_if_not_exists');
				break;
			case 'forced':
				$oNodeClone->setAttribute('_delta', 'force');
				break;
		}

		return $oNodeClone;
	}

	/**
	 * Create path for the delta
	 *
	 * @param array       aMovedClasses The classes that have been moved in the hierarchy (deleted + created elsewhere)
	 * @param DOMDocument oTargetDoc  Where to attach the top of the hierarchy
	 * @param MFElement   oNode       The node to import with its path
	 *
	 * @return \DOMElement|null
	 */
	protected function ImportNodeAndPathDelta($aMovedClasses, $oTargetDoc, $oNode)
	{
		// Preliminary: skip the parent if this node is organized hierarchically into the DOM
		// Only class nodes are organized this way
		$oParent = $oNode->parentNode;
		if ($oNode->IsClassNode())
		{
			while (($oParent instanceof DOMElement) && ($oParent->IsClassNode()))
			{
				$oParent = $oParent->parentNode;
			}
		}
		// Recursively create the path for the parent
		if ($oParent instanceof DOMElement)
		{
			$oParentClone = $this->ImportNodeAndPathDelta($aMovedClasses, $oTargetDoc, $oParent);
		}
		else
		{
			// We've reached the top let's add the node into the root recipient
			$oParentClone = $oTargetDoc;
		}

		$sAlteration = $oNode->getAttribute('_alteration');
		if ($oNode->IsClassNode() && ($sAlteration != ''))
		{
			// Handle the moved classes
			//
			// Import the whole root node
			$oNodeClone = $oTargetDoc->importNode($oNode->cloneNode(true), true);
			$oParentClone->appendChild($oNodeClone);
			$this->SetDeltaFlags($oNodeClone);

			// Handle the moved classes found under the root node (or the root node itself)
			foreach ($oNodeClone->GetNodes("descendant-or-self::class[@id]", false) as $oClassNode)
			{
				if (array_key_exists($oClassNode->getAttribute('id'), $aMovedClasses))
				{
					if ($sAlteration == 'removed')
					{
						// Remove that node: this specification will be overridden by the 'replaced' spec (see below)
						$oClassNode->parentNode->removeChild($oClassNode);
					}
					else
					{
						// Move the class at the root, with the flag 'modified'
						$oParentClone->appendChild($oClassNode);
						$oClassNode->setAttribute('_alteration', 'replaced');
						$this->SetDeltaFlags($oClassNode);
					}
				}
			}
		}
		else
		{
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
				$bCopyContents = ($sAlteration == 'replaced') || ($sAlteration == 'added') || ($sAlteration == 'needed') || ($sAlteration == 'forced');
				$oNodeClone = $oTargetDoc->importNode($oNode->cloneNode($bCopyContents), $bCopyContents);
				$this->SetDeltaFlags($oNodeClone);
				$oParentClone->appendChild($oNodeClone);
			}
		}

		return $oNodeClone;
	}

	/**
	 * Set the value for a given trace attribute
	 * See MFElement::SetTrace to enable/disable change traces
	 *
	 * @param $sAttribute
	 * @param $sPreviousValue
	 * @param $sNewValue
	 */
	public function SetTraceValue($sAttribute, $sPreviousValue, $sNewValue)
	{
		// Search into the deleted node as well!
		$oNodeSet = $this->oDOMDocument->GetNodes("//*[@$sAttribute='$sPreviousValue']", null, false);
		foreach ($oNodeSet as $oTouchedNode)
		{
			$oTouchedNode->setAttribute($sAttribute, $sNewValue);
		}
	}

	/**
	 * Get the document version of the delta
	 *
	 * @param array $aNodesToIgnore
	 * @param null $aAttributes
	 *
	 * @return \MFDocument
	 * @throws \Exception
	 */
	public function GetDeltaDocument($aNodesToIgnore = array(), $aAttributes = null)
	{
		$oDelta = new MFDocument();

		// Handle classes moved from one parent to another
		// This will be done in two steps:
		// 1) Identify the moved classes (marked as deleted under the original parent, and created under the new parent)
		// 2) When importing those "moved" classes into the delta (see ImportNodeAndPathDelta), extract them from the hierarchy (the alteration can be done at an upper level in the hierarchy) and mark them as "modified" 
		$aMovedClasses = array();
		foreach ($this->GetNodes("/itop_design/classes//class/class[@_alteration='removed']", null, false) as $oNode)
		{
			$sId = $oNode->getAttribute('id');
			if ($this->GetNodes("/itop_design/classes//class/class[@id='$sId']/properties", null, false)->length > 0)
			{
				$aMovedClasses[$sId] = true;
			}
		}

		foreach ($this->ListChanges() as $oAlteredNode)
		{
			$this->ImportNodeAndPathDelta($aMovedClasses, $oDelta, $oAlteredNode);
		}
		foreach ($aNodesToIgnore as $sXPath)
		{
			$oNodesToRemove = $oDelta->GetNodes($sXPath);
			foreach ($oNodesToRemove as $oNode)
			{
				if ($oNode instanceof DOMAttr)
				{
					$oNode->ownerElement->removeAttributeNode($oNode);
				}
				else
				{
					$oNode->parentNode->removeChild($oNode);
				}
			}
		}
		$oNodesToClean = $oDelta->GetNodes('/itop_design//*[@_altered_in]');
		foreach ($oNodesToClean as $oNode)
		{
			$oNode->removeAttribute('_altered_in');
		}

		if ($aAttributes != null)
		{
			foreach ($aAttributes as $sAttribute => $value)
			{
				if ($oDelta->documentElement) // yes, this may happen when still no change has been performed (and a module has been selected for installation)
				{
					$oDelta->documentElement->setAttribute($sAttribute, $value);
				}
			}
		}

		return $oDelta;
	}

	/**
	 * Get the text/XML version of the delta
	 *
	 * @param array $aNodesToIgnore
	 * @param null $aAttributes
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function GetDelta($aNodesToIgnore = array(), $aAttributes = null)
	{
		$oDelta = $this->GetDeltaDocument($aNodesToIgnore, $aAttributes);

		return $oDelta->saveXML();
	}

	/**
	 * Searches on disk in the root directories for module description files
	 * and returns an array of MFModules
	 *
	 * @return array Array of MFModules
	 * @throws \Exception
	 */
	public function FindModules()
	{
		$aAvailableModules = ModuleDiscovery::GetAvailableModules($this->aRootDirs);
		$aResult = array();
		foreach ($aAvailableModules as $sId => $aModule)
		{
			$oModule = new MFModule($sId, $aModule['root_dir'], $aModule['label'], isset($aModule['auto_select']));
			if (isset($aModule['auto_select']))
			{
				$oModule->SetAutoSelect($aModule['auto_select']);
			}
			if (isset($aModule['datamodel']) && is_array($aModule['datamodel']))
			{
				$oModule->SetFilesToInclude($aModule['datamodel'], 'business');
			}
			if (isset($aModule['webservice']) && is_array($aModule['webservice']))
			{
				$oModule->SetFilesToInclude($aModule['webservice'], 'webservices');
			}
			if (isset($aModule['addons']) && is_array($aModule['addons']))
			{
				$oModule->SetFilesToInclude($aModule['addons'], 'addons');
			}
			$aResult[] = $oModule;
		}

		return $aResult;
	}

	public function TestAlteration()
	{
		$sDOMOriginal = 'undefined';
		$sDOMModified = 'undefined';
		$sDOMRebuilt = 'undefined';
		$sDeltaXML = 'undefined';
		try
		{
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

			// DOM Get the original values, then modify its contents by the mean of the API
			$oRoot = $this->GetNodes('//itop_design')->item(0);
			//$oRoot->Dump();
			$sDOMOriginal = $oRoot->Dump(true);

			$oNode = $oRoot->GetNodes('a/b')->item(0);
			$oNew = $this->oDOMDocument->CreateElement('b', 'New text');
			$oNode->parentNode->RedefineChildNode($oNew);

			$oNode = $oRoot->GetNodes('a/c')->item(0);
			$oNewC = $this->oDOMDocument->CreateElement('c');
			$oNewC->setAttribute('id', '1');
			$oNode->parentNode->RedefineChildNode($oNewC);

			$oNewC->appendChild($this->oDOMDocument->CreateElement('d', 'x'));
			$oNewC->appendChild($this->oDOMDocument->CreateElement('d', 'y'));
			$oNewC->appendChild($this->oDOMDocument->CreateElement('d', 'z'));
			$oNamedNode = $this->oDOMDocument->CreateElement('z');
			$oNamedNode->setAttribute('id', 'abc');
			$oNewC->AddChildNode($oNamedNode);
			$oNewC->AddChildNode($this->oDOMDocument->CreateElement('r', 'to be replaced'));

			// Alter this "modified node", no flag should be set in its subnodes
			$oNewC->Rename('blah');
			$oNewC->Rename('foo');
			$oNewC->AddChildNode($this->oDOMDocument->CreateElement('y', '(no flag)'));
			$oNewC->AddChildNode($this->oDOMDocument->CreateElement('x', 'To delete programmatically'));
			$oSubNode = $oNewC->GetUniqueElement('z');
			$oSubNode->Rename('abcdef');
			$oSubNode = $oNewC->GetUniqueElement('x');
			$oSubNode->Delete();
			$oNewC->RedefineChildNode($this->oDOMDocument->CreateElement('r', 'replacement'));

			$oNode = $oRoot->GetNodes("//a[@id='second a']")->item(0);
			$oNode->Rename('el 2o A');
			$oNode->Rename('el secundo A');
			$oNew = $this->oDOMDocument->CreateElement('e', 'Something new here');
			$oNode->AddChildNode($oNew);
			$oNewA = $this->oDOMDocument->CreateElement('a');
			$oNewA->setAttribute('id', 'new a');
			$oNode->AddChildNode($oNewA);
			$oSubnode = $this->oDOMDocument->CreateElement('parent', 'el secundo A');
			$oSubnode->setAttribute('id', 'to be changed');
			$oNewA->AddChildNode($oSubnode);
			$oNewA->AddChildNode($this->oDOMDocument->CreateElement('f', 'Welcome to the newcomer'));
			$oNewA->AddChildNode($this->oDOMDocument->CreateElement('x', 'To delete programmatically'));

			// Alter this "new a", as it is new, no flag should be set
			$oNewA->Rename('new_a');
			$oSubNode = $oNewA->GetUniqueElement('parent');
			$oSubNode->Rename('alter ego');
			$oNewA->RedefineChildNode($this->oDOMDocument->CreateElement('f', 'dummy data'));
			$oSubNode = $oNewA->GetUniqueElement('x');
			$oSubNode->Delete();

			$oNode = $oRoot->GetNodes("//a[@id='third a']")->item(0);
			$oNode->Delete();

			//$oRoot->Dump();
			$sDOMModified = $oRoot->Dump(true);

			// Compute the delta
			//
			$sDeltaXML = $this->GetDelta();
			//echo "<pre>\n";
			//echo htmlentities($sDeltaXML);
			//echo "</pre>\n";

			// Reiterating - try to remake the DOM by applying the computed delta
			//
			$this->oDOMDocument = new MFDocument();
			$this->oDOMDocument->loadXML($sOriginalXML);
			$oRoot = $this->GetNodes('//itop_design')->item(0);
			//$oRoot->Dump();
			echo "<h4>Rebuild the DOM - Delta applied...</h4>\n";
			$oDeltaDoc = new MFDocument();
			$oDeltaDoc->loadXML($sDeltaXML);

			//$oDeltaDoc->Dump();
			//$this->oDOMDocument->Dump();
			$oDeltaRoot = $oDeltaDoc->childNodes->item(0);
			$this->LoadDelta($oDeltaRoot, $this->oDOMDocument);
			//$oRoot->Dump();
			$sDOMRebuilt = $oRoot->Dump(true);
		}
		catch (Exception $e)
		{
			echo "<h1>Exception: ".$e->getMessage()."</h1>\n";
			echo "<pre>\n";
			debug_print_backtrace();
			echo "</pre>\n";
		}
		$sArrStyle = "font-size: 40;";
		echo "<table>\n";
		echo " <tr>\n";
		echo "  <td width=\"50%\">\n";
		echo "   <h4>DOM - Original values</h4>\n";
		echo "   <pre>".htmlentities($sDOMOriginal)."</pre>\n";
		echo "  </td>\n";
		echo "  <td width=\"50%\" align=\"left\" valign=\"center\"><span style=\"$sArrStyle\">&rArr; &rArr; &rArr;</span></td>\n";
		echo " </tr>\n";
		echo " <tr><td align=\"center\"><span style=\"$sArrStyle\">&dArr;</div></td><td align=\"center\"><span style=\"$sArrStyle\"><span style=\"$sArrStyle\">&dArr;</div></div></td></tr>\n";
		echo " <tr>\n";
		echo "  <td width=\"50%\">\n";
		echo "   <h4>DOM - Altered with various changes</h4>\n";
		echo "   <pre>".htmlentities($sDOMModified)."</pre>\n";
		echo "  </td>\n";
		echo "  <td width=\"50%\">\n";
		echo "   <h4>DOM - Rebuilt from the Delta</h4>\n";
		echo "   <pre>".htmlentities($sDOMRebuilt)."</pre>\n";
		echo "  </td>\n";
		echo " </tr>\n";
		echo " <tr><td align=\"center\"><span style=\"$sArrStyle\">&dArr;</div></td><td align=\"center\"><span style=\"$sArrStyle\">&uArr;</div></td></tr>\n";
		echo "  <td width=\"50%\">\n";
		echo "   <h4>Delta (Computed by ModelFactory)</h4>\n";
		echo "   <pre>".htmlentities($sDeltaXML)."</pre>\n";
		echo "  </td>\n";
		echo "  <td width=\"50%\" align=\"left\" valign=\"center\"><span style=\"$sArrStyle\">&rArr; &rArr; &rArr;</span></td>\n";
		echo " </tr>\n";
		echo "</table>\n";
	} // TEST !


	/**
	 * Extracts some nodes from the DOM
	 *
	 * @param string $sXPath A XPath expression
	 * @param null $oContextNode
	 * @param bool $bSafe
	 *
	 * @return DOMNodeList
	 */
	public function GetNodes($sXPath, $oContextNode = null, $bSafe = true)
	{
		return $this->oDOMDocument->GetNodes($sXPath, $oContextNode, $bSafe);
	}
}

/**
 * MFElement: helper to read/change the DOM
 *
 * @package ModelFactory
 * @property \MFDocument $ownerDocument This is only here for type hinting as iTop replaces \DOMDocument with \MFDocument
 * @property \MFElement $parentNode This is only here for type hinting as iTop replaces \DOMElement with \MFElement
 */
class MFElement extends Combodo\iTop\DesignElement
{
	/**
	 * Extracts some nodes from the DOM
	 *
	 * @param string $sXPath A XPath expression
	 * @param bool $bSafe
	 *
	 * @return DOMNodeList
	 */
	public function GetNodes($sXPath, $bSafe = true)
	{
		return $this->ownerDocument->GetNodes($sXPath, $this, $bSafe);
	}

	/**
	 * Extracts some nodes from the DOM (active nodes only !!!)
	 *
	 * @param string $sXPath A XPath expression
	 * @param string $sId
	 *
	 * @return DOMNodeList
	 */
	public function GetNodeById($sXPath, $sId)
	{
		return $this->ownerDocument->GetNodeById($sXPath, $sId, $this);
	}

	/**
	 * Returns the node directly under the given node
	 *
	 * @param string $sTagName
	 * @param bool $bMustExist
	 *
	 * @return MFElement
	 * @throws \DOMFormatException
	 */
	public function GetUniqueElement($sTagName, $bMustExist = true)
	{
		$oNode = null;
		foreach ($this->childNodes as $oChildNode)
		{
			if (($oChildNode->nodeName == $sTagName) && (($oChildNode->getAttribute('_alteration') != 'removed')))
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
	 * Assumes the current node to be either a text or
	 * <items>
	 *   <item [key]="..."]>value<item>
	 *   <item [key]="..."]>value<item>
	 * </items>
	 * where value can be the either a text or an array of items... recursively
	 * Returns a PHP array
	 *
	 * @param string $sElementName
	 *
	 * @return array|string if no subnode is found, return current node text, else return results as array
	 * @throws \DOMFormatException
	 */
	public function GetNodeAsArrayOfItems($sElementName = 'items')
	{
		$oItems = $this->GetOptionalElement($sElementName);
		if ($oItems)
		{
			$res = array();
			$aRanks = array();
			foreach ($oItems->childNodes as $oItem)
			{
				if ($oItem instanceof DOMElement)
				{
					// When an attribute is missing
					if ($oItem->hasAttribute('id'))
					{
						$key = $oItem->getAttribute('id');
						if (array_key_exists($key, $res))
						{
							// Houston!
							throw new DOMFormatException("id '$key' already used", null, null, $oItem);
						}
						$res[$key] = $oItem->GetNodeAsArrayOfItems();
					}
					else
					{
						$res[] = $oItem->GetNodeAsArrayOfItems();
					}
					$sRank = $oItem->GetChildText('rank');
					if ($sRank != '')
					{
						$aRanks[] = (float)$sRank;
					}
					else
					{
						$aRanks[] = count($aRanks) > 0 ? max($aRanks) + 1 : 0;
					}
					array_multisort($aRanks, $res);
				}
			}
		}
		else
		{
			$res = $this->GetText();
		}

		return $res;
	}

	/**
	 * @param $oXmlDoc
	 * @param $oXMLNode
	 * @param $itemValue
	 */
	protected static function AddItemToNode($oXmlDoc, $oXMLNode, $itemValue)
	{
		if (is_array($itemValue))
		{
			$oXmlItems = $oXmlDoc->CreateElement('items');
			$oXMLNode->AppendChild($oXmlItems);

			foreach ($itemValue as $key => $item)
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
			$oXmlText = $oXmlDoc->CreateTextNode((string)$itemValue);
			$oXMLNode->AppendChild($oXmlText);
		}
	}

	/**
	 * Helper to remove child nodes
	 */
	protected function DeleteChildren()
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
	 * Find the child node matching the given node.
	 * UNSAFE: may return nodes marked as _alteration="removed"
	 * A method with the same signature MUST exist in MFDocument for the recursion to work fine
	 *
	 * @param \MFElement $oRefNode The node to search for
	 * @param string $sSearchId substitutes to the value of the 'id' attribute
	 *
	 * @return \MFElement|null
	 * @throws \Exception
	 */
	public function _FindChildNode(MFElement $oRefNode, $sSearchId = null)
	{
		return self::_FindNode($this, $oRefNode, $sSearchId);
	}

	/**
	 * Find the child node matching the given node under the specified parent.
	 * UNSAFE: may return nodes marked as _alteration="removed"
	 *
	 * @param \DOMNode $oParent
	 * @param \MFElement $oRefNode
	 * @param string $sSearchId
	 *
	 * @return \MFElement|null
	 * @throws Exception
	 */
	public static function _FindNode(DOMNode $oParent, MFElement $oRefNode, $sSearchId = null)
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

			/** @var \MFElement|null $oRes */
			$oRes = $oXPath->query($sXPath, $oRoot)->item(0);
		}
		else
		{
			// Get the first one having the same tag name (ignore others)
			$sXPath = './'.$oRefNode->tagName;

			/** @var \MFElement|null $oRes */
			$oRes = $oXPath->query($sXPath, $oRoot)->item(0);
		}

		return $oRes;
	}

	/**
	 * Check if the current node is under a node 'added' or 'altered'
	 * Usage: In such a case, the change must not be tracked
	 *
	 * @return boolean true if `_alteration` flag is set on any parent of the current node
	 */
	public function IsInDefinition()
	{
		// Iterate through the parents: reset the flag if any of them has a flag set 
		for ($oParent = $this; $oParent instanceof MFElement; $oParent = $oParent->parentNode)
		{
			if ($oParent->getAttribute('_alteration') != '')
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * Check if the given node is (a child of a node) altered by one of the supplied modules
	 *
	 * @param array $aModules The list of module codes to consider
	 *
	 * @return boolean
	 */
	public function IsAlteredByModule($aModules)
	{
		// Iterate through the parents: reset the flag if any of them has a flag set
		for ($oParent = $this; $oParent instanceof MFElement; $oParent = $oParent->parentNode)
		{
			if (in_array($oParent->getAttribute('_altered_in'), $aModules))
			{
				return true;
			}
		}

		return false;
	}

	protected static $aTraceAttributes = null;

	/**
	 * Enable/disable the trace on changed nodes
	 *
	 * @param array aAttributes Array of attributes (key => value) to be added onto any changed node
	 */
	public static function SetTrace($aAttributes = null)
	{
		self::$aTraceAttributes = $aAttributes;
	}

	/**
	 * Mark the node as touched (if tracing is active)
	 */
	public function AddTrace()
	{
		if (!is_null(self::$aTraceAttributes))
		{
			foreach (self::$aTraceAttributes as $sAttribute => $value)
			{
				$this->setAttribute($sAttribute, $value);
			}
		}
	}

	/**
	 * Add a node and set the flags that will be used to compute the delta
	 *
	 * @param MFElement $oNode The node (including all subnodes) to add
	 *
	 * @throws \MFException
	 * @throws \Exception
	 */
	public function AddChildNode(MFElement $oNode)
	{
		// First: cleanup any flag behind the new node, and eventually add trace data
		$oNode->ApplyChanges();
		$oNode->AddTrace();

		$oExisting = $this->_FindChildNode($oNode);
		if ($oExisting)
		{
			if ($oExisting->getAttribute('_alteration') != 'removed')
			{
				$sPath = MFDocument::GetItopNodePath($oNode);
				$iLine = $oNode->getLineNo();
				throw new MFException($sPath.' at line '.$iLine.": could not be added (already exists)", MFException::COULD_NOT_BE_ADDED,
					$iLine, $sPath);
			}
			$oExisting->ReplaceWith($oNode);
			$sFlag = 'replaced';
		}
		else
		{
			$this->appendChild($oNode);
			$sFlag = 'added';
		}
		if (!$this->IsInDefinition())
		{
			$oNode->setAttribute('_alteration', $sFlag);
		}
	}

	/**
	 * Modify a node and set the flags that will be used to compute the delta
	 *
	 * @param MFElement $oNode The node (including all subnodes) to set
	 * @param string|null $sSearchId
	 *
	 * @return void
	 *
	 * @throws \MFException
	 * @throws \Exception
	 */
	public function RedefineChildNode(MFElement $oNode, $sSearchId = null)
	{
		// First: cleanup any flag behind the new node, and eventually add trace data
		$oNode->ApplyChanges();
		$oNode->AddTrace();

		$oExisting = $this->_FindChildNode($oNode, $sSearchId);
		if (!$oExisting)
		{
			$sPath = MFDocument::GetItopNodePath($this)."/".$oNode->tagName.(empty($sSearchId) ? '' : "[$sSearchId]");
			$iLine = $oNode->getLineNo();
			throw new MFException($sPath." at line $iLine: could not be modified (not found)", MFException::COULD_NOT_BE_MODIFIED_NOT_FOUND,
				$sPath, $iLine);
		}
		$sPrevFlag = $oExisting->getAttribute('_alteration');
		if ($sPrevFlag == 'removed')
		{
			$sPath = MFDocument::GetItopNodePath($this)."/".$oNode->tagName.(empty($sSearchId) ? '' : "[$sSearchId]");
			$iLine = $oNode->getLineNo();
			throw new MFException($sPath." at line $iLine: could not be modified (marked as deleted)",
				MFException::COULD_NOT_BE_MODIFIED_ALREADY_DELETED, $sPath, $iLine);
		}
		$oExisting->ReplaceWith($oNode);
		if (!$this->IsInDefinition())
		{
			if ($sPrevFlag == '')
			{
				$sPrevFlag = 'replaced';
			}
			$oNode->setAttribute('_alteration', $sPrevFlag);
		}
	}

	/**
	 * Combination of AddChildNode or RedefineChildNode... it depends
	 * This should become the preferred way of doing things (instead of implementing a test + the call to one of the APIs!
	 *
	 * @param MFElement $oNode The node (including all subnodes) to set
	 * @param string $sSearchId Optional Id of the node to SearchMenuNode
	 * @param bool $bForce Force mode to dynamically add or replace nodes
	 *
	 * @throws \Exception
	 */
	public function SetChildNode(MFElement $oNode, $sSearchId = null, $bForce = false)
	{
		// First: cleanup any flag behind the new node, and eventually add trace data
		$oNode->ApplyChanges();
		$oNode->AddTrace();

		$oExisting = $this->_FindChildNode($oNode, $sSearchId);
		if ($oExisting)
		{
			$sOldId = $oExisting->getAttribute('_old_id');
			if (!empty($sOldId))
			{
				$oNode->setAttribute('_old_id', $sOldId);
			}

			$sPrevFlag = $oExisting->getAttribute('_alteration');
			if ($sPrevFlag == 'removed')
			{
				$sFlag = $bForce ? 'forced' : 'replaced';
			}
			else
			{
				$sFlag = $sPrevFlag; // added, replaced or ''
			}
			$oExisting->ReplaceWith($oNode);
		}
		else
		{
			$this->appendChild($oNode);
			$sFlag = $bForce ? 'forced' : 'added';
		}
		if (!$this->IsInDefinition())
		{
			if ($sFlag == '')
			{
				$sFlag = $bForce ? 'forced' : 'replaced';
			}
			$oNode->setAttribute('_alteration', $sFlag);
		}
	}

	/**
	 * Check that the current node is actually a class node, under classes
	 */
	public function IsClassNode()
	{
		if ($this->tagName == 'class')
		{
			if (($this->parentNode->tagName == 'classes') && ($this->parentNode->parentNode->tagName == 'itop_design')) // Beware: classes/class also exists in the group definition
			{
				return true;
			}

			return $this->parentNode->IsClassNode();
		}
		else
		{
			return false;
		}
	}

	/**
	 * Replaces a node by another one, making sure that recursive nodes are preserved
	 *
	 * @param MFElement $oNewNode The replacement
	 */
	protected function ReplaceWith($oNewNode)
	{
		// Move the classes from the old node into the new one
		if ($this->IsClassNode())
		{
			foreach ($this->GetNodes('class') as $oChild)
			{
				$oNewNode->appendChild($oChild);
			}
		}

		$oParentNode = $this->parentNode;
		$oParentNode->replaceChild($oNewNode, $this);
	}

	/**
	 * Remove a node and set the flags that will be used to compute the delta
	 *
	 * @throws \Exception
	 */
	public function Delete()
	{
		switch ($this->getAttribute('_alteration'))
		{
			case 'replaced':
				$sFlag = 'removed';
				break;
			case 'added':
			case 'needed':
				$sFlag = null;
				break;
			case 'removed':
				throw new Exception("Attempting to remove a deleted node: $this->tagName (id: ".$this->getAttribute('id')."");

			default:
				$sFlag = 'removed';
				if ($this->IsInDefinition())
				{
					$sFlag = null;
					break;
				}
		}
		if ($sFlag)
		{
			$this->setAttribute('_alteration', $sFlag);
			$this->DeleteChildren();

			// Add trace data
			$this->AddTrace();
		}
		else
		{
			// Remove the node entirely
			$this->parentNode->removeChild($this);
		}
	}

	/**
	 * Merge the current node into the given container
	 *
	 * @param \MFElement $oContainer An element or a document
	 * @param string $sSearchId The id to consider (could be blank)
	 * @param bool $bMustExist Throw an exception if the node must already be found (and not marked as deleted!)
	 * @param bool $bIfExists Return null if the node does not exists (or is marked as deleted)
	 *
	 * @return \MFElement|null
	 * @throws \Exception
	 */
	public function MergeInto($oContainer, $sSearchId, $bMustExist, $bIfExists = false)
	{
		$oTargetNode = $oContainer->_FindChildNode($this, $sSearchId);
		if ($oTargetNode)
		{
			if ($oTargetNode->getAttribute('_alteration') == 'removed')
			{
				if ($bMustExist)
				{
					throw new Exception(MFDocument::GetItopNodePath($this).' at line '.$this->getLineNo().": could not be found (marked as deleted)");
				}
				// Beware: ImportNode(xxx, false) DOES NOT copy the node's attribute on *some* PHP versions (<5.2.17)
				// So use this workaround to import a node and its attributes on *any* PHP version
				$oTargetNode = $oContainer->ownerDocument->ImportNode($this->cloneNode(false), true);
				$oContainer->appendChild($oTargetNode);
			}
		}
		else
		{
			if ($bMustExist)
			{
				//echo "Dumping parent node<br/>\n";
				//$oContainer->Dump();
				throw new Exception(MFDocument::GetItopNodePath($this).' at line '.$this->getLineNo().": could not be found");
			}
			if (!$bIfExists)
			{
				// Beware: ImportNode(xxx, false) DOES NOT copy the node's attribute on *some* PHP versions (<5.2.17)
				// So use this workaround to import a node and its attributes on *any* PHP version
				$oTargetNode = $oContainer->ownerDocument->ImportNode($this->cloneNode(false), true);
				$oContainer->appendChild($oTargetNode);
			}
		}

		return $oTargetNode;
	}

	/**
	 * Renames a node and set the flags that will be used to compute the delta
	 *
	 * @param string $sId The new id
	 */
	public function Rename($sId)
	{
		if (($this->getAttribute('_alteration') == 'replaced') || !$this->IsInDefinition())
		{
			$sOriginalId = $this->getAttribute('_old_id');
			if ($sOriginalId == '')
			{
				$sRenameOrig = $this->getAttribute('_rename_from');
				if (empty($sRenameOrig))
				{
					$this->setAttribute('_old_id', $this->getAttribute('id'));
				}
				else
				{
					$this->setAttribute('_old_id', $sRenameOrig);
					$this->removeAttribute('_rename_from');
				}
			}
			else
			{
				if ($sOriginalId == $sId)
				{
					$this->removeAttribute('_old_id');
				}
			}
		}
		$this->setAttribute('id', $sId);

		// Leave a trace of this change
		$this->AddTrace();
	}


	/**
	 * List changes below a given node (see also MFDocument::ListChanges)
	 */
	public function ListChanges()
	{
		// Note: omitting the dot will make the query be global to the whole document!!!
		return $this->ownerDocument->GetNodes('.//*[@_alteration or @_old_id]', $this, false);
	}

	/**
	 * List changes below a given node (see also MFDocument::ApplyChanges)
	 */
	public function ApplyChanges()
	{
		$oNodes = $this->ListChanges();
		foreach ($oNodes as $oNode)
		{
			$sOperation = $oNode->GetAttribute('_alteration');
			switch ($sOperation)
			{
				case 'added':
				case 'replaced':
				case 'needed':
				case 'forced':
					// marked as added or modified, just reset the flag
					$oNode->removeAttribute('_alteration');
					break;

				case 'removed':
					// marked as deleted, let's remove the node from the tree
					$oNode->parentNode->removeChild($oNode);
					break;
			}
			if ($oNode->hasAttribute('_old_id'))
			{
				$oNode->removeAttribute('_old_id');
			}
		}
	}
}

/**
 * MFDocument - formatting rules for XML input/output
 *
 * @package ModelFactory
 */
class MFDocument extends \Combodo\iTop\DesignDocument
{
	/**
	 * Over loadable. Called prior to data loading.
	 */
	protected function Init()
	{
		parent::Init();
		$this->registerNodeClass('DOMElement', 'MFElement');
	}

	/**
	 * Overload the standard API
	 *
	 * @param \DOMNode|null $node
	 * @param int $options
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function saveXML(DOMNode $node = null, $options = 0)
	{
		$oRootNode = $this->firstChild;
		if (!$oRootNode)
		{
			$oRootNode = $this->createElement('itop_design'); // make sure that the document is not empty
			$oRootNode->setAttribute('xmlns:xsi', "http://www.w3.org/2001/XMLSchema-instance");
			$oRootNode->setAttribute('version', ITOP_DESIGN_LATEST_VERSION);
			$this->appendChild($oRootNode);
		}

		return parent::saveXML($node, $options);
	}

	/**
	 * Overload createElement to make sure (via new DOMText) that the XML entities are
	 * always properly escaped
	 * (non-PHPdoc)
	 *
	 * @see DOMDocument::createElement()
	 *
	 * @param string $sName
	 * @param null $value
	 * @param null $namespaceURI
	 *
	 * @return \MFElement
	 * @throws \Exception
	 */
	function createElement($sName, $value = null, $namespaceURI = null)
	{
		/** @var \MFElement $oElement */
		$oElement = $this->importNode(new MFElement($sName, null, $namespaceURI));
		if (($value !== '') && ($value !== null))
		{
			$oElement->appendChild(new DOMText($value));
		}

		return $oElement;
	}

	/**
	 * Find the child node matching the given node
	 * A method with the same signature MUST exist in MFElement for the recursion to work fine
	 *
	 * @param MFElement $oRefNode The node to search for
	 * @param string $sSearchId substitutes to the value of the 'id' attribute
	 *
	 * @return \DOMElement|null
	 * @throws \Exception
	 */
	public function _FindChildNode(MFElement $oRefNode, $sSearchId = null)
	{
		return MFElement::_FindNode($this, $oRefNode, $sSearchId);
	}

	/**
	 * Extracts some nodes from the DOM
	 *
	 * @param string $sXPath A XPath expression
	 * @param null $oContextNode
	 * @param bool $bSafe
	 *
	 * @return DOMNodeList
	 */
	public function GetNodes($sXPath, $oContextNode = null, $bSafe = true)
	{
		$oXPath = new DOMXPath($this);
		if ($bSafe)
		{
			$sXPath .= "[not(@_alteration) or @_alteration!='removed']";
		}

		if (is_null($oContextNode))
		{
			$oResult = $oXPath->query($sXPath);
		}
		else
		{
			$oResult = $oXPath->query($sXPath, $oContextNode);
		}

		return $oResult;
	}

	/**
	 * @param string $sXPath
	 * @param string $sId
	 * @param \DOMNode $oContextNode
	 *
	 * @return \DOMNodeList
	 */
	public function GetNodeById($sXPath, $sId, $oContextNode = null)
	{
		$oXPath = new DOMXPath($this);
		$sQuotedId = self::XPathQuote($sId);
		$sXPath .= "[@id=$sQuotedId and(not(@_alteration) or @_alteration!='removed')]";

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

/**
 * Helper class manage parameters stored as XML nodes
 * to be converted to a PHP structure during compilation
 * Values can be either a hash, an array, a string, a boolean, an int or a float
 */
class MFParameters
{
	protected $aData = null;

	/**
	 * MFParameters constructor.
	 *
	 * @param \DOMNode $oNode
	 *
	 * @throws \Exception
	 */
	public function __construct(DOMNode $oNode)
	{
		$this->aData = array();
		$this->LoadFromDOM($oNode);
	}

	/**
	 * @param $sCode
	 * @param string $default
	 *
	 * @return mixed|string
	 */
	public function Get($sCode, $default = '')
	{
		if (array_key_exists($sCode, $this->aData))
		{
			return $this->aData[$sCode];
		}

		return $default;
	}

	/**
	 * @return array|null
	 */
	public function GetAll()
	{
		return $this->aData;
	}

	/**
	 * @param \DOMNode $oNode
	 *
	 * @throws \Exception
	 */
	public function LoadFromDOM(DOMNode $oNode)
	{
		$this->aData = array();
		foreach ($oNode->childNodes as $oChildNode)
		{
			if ($oChildNode instanceof DOMElement)
			{
				$this->aData[$oChildNode->nodeName] = $this->ReadElement($oChildNode);
			}
		}
	}

	/**
	 * @param \DOMNode $oNode
	 *
	 * @return array|bool|int
	 * @throws \Exception
	 */
	protected function ReadElement(DOMNode $oNode)
	{
		$value = null;
		if ($oNode instanceof DOMElement)
		{
			$sDefaultNodeType = ($this->HasChildNodes($oNode)) ? 'hash' : 'string';
			$sNodeType = $oNode->getAttribute('type');
			if ($sNodeType == '')
			{
				$sNodeType = $sDefaultNodeType;
			}

			switch ($sNodeType)
			{
				case 'array':
					$value = array();
					// Treat the current element as zero based array, child tag names are NOT meaningful
					$sFirstTagName = null;
					foreach ($oNode->childNodes as $oChildElement)
					{
						if ($oChildElement instanceof DOMElement)
						{
							if ($sFirstTagName == null)
							{
								$sFirstTagName = $oChildElement->nodeName;
							}
							else
							{
								if ($sFirstTagName != $oChildElement->nodeName)
								{
									throw new Exception("Invalid Parameters: mixed tags ('$sFirstTagName' and '".$oChildElement->nodeName."') inside array '".$oNode->nodeName."'");
								}
							}
							$val = $this->ReadElement($oChildElement);
							// No specific Id, just push the value at the end of the array
							$value[] = $val;
						}
					}
					ksort($value, SORT_NUMERIC);
					break;

				case 'hash':
					$value = array();
					// Treat the current element as a hash, child tag names are keys
					foreach ($oNode->childNodes as $oChildElement)
					{
						if ($oChildElement instanceof DOMElement)
						{
							if (array_key_exists($oChildElement->nodeName, $value))
							{
								throw new Exception("Invalid Parameters file: duplicate tags '".$oChildElement->nodeName."' inside hash '".$oNode->nodeName."'");
							}
							$val = $this->ReadElement($oChildElement);
							$value[$oChildElement->nodeName] = $val;
						}
					}
					break;

				case 'int':
				case 'integer':
					$value = (int)$this->GetText($oNode);
					break;

				case 'bool':
				case 'boolean':
					if (($this->GetText($oNode) == 'true') || ($this->GetText($oNode) == 1))
					{
						$value = true;
					}
					else
					{
						$value = false;
					}
					break;

				case 'string':
				default:
					$value = str_replace('\n', "\n", (string)$this->GetText($oNode));
			}
		}
		else
		{
			if ($oNode instanceof DOMText)
			{
				$value = $oNode->wholeText;
			}
		}

		return $value;
	}

	/**
	 * @param $sAttName
	 * @param $oNode
	 * @param $sDefaultValue
	 *
	 * @return mixed
	 */
	protected function GetAttribute($sAttName, $oNode, $sDefaultValue)
	{
		$sRet = $sDefaultValue;

		foreach ($oNode->attributes as $oAttribute)
		{
			if ($oAttribute->nodeName == $sAttName)
			{
				$sRet = $oAttribute->nodeValue;
				break;
			}
		}

		return $sRet;
	}

	/**
	 * Returns the TEXT of the current node (possibly from several sub nodes)
	 *
	 * @param $oNode
	 * @param null $sDefault
	 *
	 * @return null|string
	 */
	public function GetText($oNode, $sDefault = null)
	{
		$sText = null;
		foreach ($oNode->childNodes as $oChildNode)
		{
			if ($oChildNode instanceof DOMText)
			{
				if (is_null($sText))
				{
					$sText = '';
				}
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
	 * Check if a node has child nodes (apart from text nodes)
	 *
	 * @param $oNode
	 *
	 * @return bool
	 */
	public function HasChildNodes($oNode)
	{
		if ($oNode instanceof DOMElement)
		{
			foreach ($oNode->childNodes as $oChildNode)
			{
				if ($oChildNode instanceof DOMElement)
				{
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * @param \XMLParameters $oTask
	 */
	function Merge(XMLParameters $oTask)
	{
		//todo: clarify the usage of this function that CANNOT work
		$this->aData = $this->array_merge_recursive_distinct($this->aData, $oTask->aData);
	}

	/**
	 * array_merge_recursive does indeed merge arrays, but it converts values with duplicate
	 * keys to arrays rather than overwriting the value in the first array with the duplicate
	 * value in the second array, as array_merge does. I.e., with array_merge_recursive,
	 * this happens (documented behavior):
	 *
	 * array_merge_recursive(array('key' => 'org value'), array('key' => 'new value'));
	 *     => array('key' => array('org value', 'new value'));
	 *
	 * array_merge_recursive_distinct does not change the data types of the values in the arrays.
	 * Matching keys' values in the second array overwrite those in the first array, as is the
	 * case with array_merge, i.e.:
	 *
	 * array_merge_recursive_distinct(array('key' => 'org value'), array('key' => 'new value'));
	 *     => array('key' => array('new value'));
	 *
	 * Parameters are passed by reference, though only for performance reasons. They're not
	 * altered by this function.
	 *
	 * @param array $array1
	 * @param array $array2
	 *
	 * @return array
	 * @author Daniel <daniel (at) danielsmedegaardbuus (dot) dk>
	 * @author Gabriel Sobrinho <gabriel (dot) sobrinho (at) gmail (dot) com>
	 */
	protected function array_merge_recursive_distinct(array &$array1, array &$array2)
	{
		$merged = $array1;

		foreach ($array2 as $key => &$value)
		{
			if (is_array($value) && isset ($merged [$key]) && is_array($merged [$key]))
			{
				$merged [$key] = $this->array_merge_recursive_distinct($merged [$key], $value);
			}
			else
			{
				$merged [$key] = $value;
			}
		}

		return $merged;
	}
}