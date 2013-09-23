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
 * ModuleDiscovery: list available modules
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class MissingDependencyException extends Exception
{
}

class ModuleDiscovery
{
	static $m_aModuleArgs = array(
		'label' => 'One line description shown during the interactive setup',
		'dependencies' => 'array of module ids',
		'mandatory' => 'boolean',
		'visible' => 'boolean',
		'datamodel' =>  'array of data model files',
		//'dictionary' => 'array of dictionary files', // No longer mandatory, now automated
		'data.struct' => 'array of structural data files',
		'data.sample' => 'array of sample data files',
		'doc.manual_setup' => 'url',
		'doc.more_information' => 'url',
	);
	

	// Cache the results and the source directories
	protected static $m_aSearchDirs = null;
	protected static $m_aModules = array();

	// All the entries below are list of file paths relative to the module directory
	protected static $m_aFilesList = array('datamodel', 'webservice', 'dictionary', 'data.struct', 'data.sample');


	// ModulePath is used by AddModule to get the path of the module being included (in ListModuleFiles)
	protected static $m_sModulePath = null;
	protected static function SetModulePath($sModulePath)
	{
		self::$m_sModulePath = $sModulePath;
	}

	public static function AddModule($sFilePath, $sId, $aArgs)
	{
		if (!array_key_exists('itop_version', $aArgs))
		{
			// Assume 1.0.2
			$aArgs['itop_version'] = '1.0.2';
		}
		foreach (self::$m_aModuleArgs as $sArgName => $sArgDesc)
		{
			if (!array_key_exists($sArgName, $aArgs))
			{
				throw new Exception("Module '$sId': missing argument '$sArgName'");
		   }
		}

		$aArgs['root_dir'] = dirname($sFilePath);
		$aArgs['module_file'] = $sFilePath;

		self::$m_aModules[$sId] = $aArgs;

		foreach(self::$m_aFilesList as $sAttribute)
		{
			if (isset(self::$m_aModules[$sId][$sAttribute]))
			{
				// All the items below are list of files, that are relative to the current file
				// being loaded, let's update their path to store path relative to the application directory
				foreach(self::$m_aModules[$sId][$sAttribute] as $idx => $sRelativePath)
				{
					self::$m_aModules[$sId][$sAttribute][$idx] = self::$m_sModulePath.'/'.$sRelativePath;
				}
			}
		}
		// Populate automatically the list of dictionary files
		if(preg_match('|^([^/]+)|', $sId, $aMatches)) // ModuleName = everything before the first forward slash
		{
			$sModuleName = $aMatches[1];
			$sDir = dirname($sFilePath);
			if ($hDir = opendir($sDir))
			{
				while (($sFile = readdir($hDir)) !== false)
				{
					$aMatches = array();
					if (preg_match("/^[^\\.]+.dict.$sModuleName.php$/i", $sFile, $aMatches)) // Dictionary files named like <Lang>.dict.<ModuleName>.php are loaded automatically
					{
						self::$m_aModules[$sId]['dictionary'][] = self::$m_sModulePath.'/'.$sFile;
					}
				}
				closedir($hDir);
			}
		}
	}

	/**
	 *	
	 * @param bool  $bAbortOnMissingDependency ...
	 * @param hash $aModulesToLoad List of modules to search for, defaults to all if ommitted
	 */	 
	protected static function GetModules($bAbortOnMissingDependency = false, $aModulesToLoad = null)
	{
		// Order the modules to take into account their inter-dependencies
		$aDependencies = array();
		foreach(self::$m_aModules as $sId => $aModule)
		{
			list($sModuleName, $sModuleVersion) = self::GetModuleName($sId);
			if (is_null($aModulesToLoad) || in_array($sModuleName, $aModulesToLoad))
			{
				$aDependencies[$sId] = $aModule['dependencies'];
			}
		}
		ksort($aDependencies);
		$aOrderedModules = array();
		$iLoopCount = 1;
		while(($iLoopCount < count(self::$m_aModules)) && (count($aDependencies) > 0) )
		{
			foreach($aDependencies as $sId => $aRemainingDeps)
			{
				$bDependenciesSolved = true;
				foreach($aRemainingDeps as $sDepId)
				{
					if (!self::DependencyIsResolved($sDepId, $aOrderedModules))
					{
						$bDependenciesSolved = false;
					}
				}
				if ($bDependenciesSolved)
				{
					$aOrderedModules[] = $sId;
					unset($aDependencies[$sId]);
				}
			}
			$iLoopCount++;
		}
		if ($bAbortOnMissingDependency && count($aDependencies) > 0)
		{
			$aModuleDeps = array();			
			foreach($aDependencies as $sId => $aDeps)
			{
				$aModule = self::$m_aModules[$sId];
				$aModuleDeps[] = "{$aModule['label']} (id: $sId) depends on ".implode(' + ', $aDeps);
			}
			$sMessage = "The following modules have unmet dependencies: ".implode(', ', $aModuleDeps);
			throw new MissingDependencyException($sMessage);
		}
		// Return the ordered list, so that the dependencies are met...
		$aResult = array();
		foreach($aOrderedModules as $sId)
		{
			$aResult[$sId] = self::$m_aModules[$sId];
		}
		return $aResult;
	}
	
	protected static function DependencyIsResolved($sDepString, $aOrderedModules)
	{
		$bResult = false;
		$aModuleVersions = array();
		// Separate the module names from their version for an easier comparison later
		foreach($aOrderedModules as $sModuleId)
		{
			if (preg_match('|^([^/]+)/(.*)$|', $sModuleId, $aMatches))
			{
				$aModuleVersions[$aMatches[1]] = $aMatches[2];
			}
			else
			{
				// No version number found, assume 1.0.0
				$aModuleVersions[$sModuleId] = '1.0.0';
			}
		}
		if (preg_match_all('/([^\(\)&| ]+)/', $sDepString, $aMatches))
		{
			$aReplacements = array();
			foreach($aMatches as $aMatch)
			{
				foreach($aMatch as $sModuleId)
				{
					// $sModuleId in the dependency string is made of a <name>/<optional_operator><version>
					// where the operator is < <= = > >= (by default >=)
					if(preg_match('|^([^/]+)/(<?>?=?)([^><=]+)$|', $sModuleId, $aModuleMatches))
					{
						$sModuleName = $aModuleMatches[1];
						$sOperator = $aModuleMatches[2];
						if ($sOperator == '')
						{
							$sOperator = '>=';
						}
						$sExpectedVersion = $aModuleMatches[3];
						if (array_key_exists($sModuleName, $aModuleVersions))
						{
							// module is present, check the version
							$sCurrentVersion = $aModuleVersions[$sModuleName];
							if (version_compare($sCurrentVersion, $sExpectedVersion, $sOperator))
							{
								$aReplacements[$sModuleId] = '(true)'; // Add parentheses to protect against invalid condition causing
																	   // a function call that results in a runtime fatal error								
							}
							else
							{
								$aReplacements[$sModuleId] = '(false)'; // Add parentheses to protect against invalid condition causing
																	   // a function call that results in a runtime fatal error								
							}
						}
						else
						{
							// module is not present
							$aReplacements[$sModuleId] = '(false)'; // Add parentheses to protect against invalid condition causing
																    // a function call that results in a runtime fatal error
						}
					}
				}
			}
			$sBooleanExpr = str_replace(array_keys($aReplacements), array_values($aReplacements), $sDepString);
			$bOk = @eval('$bResult = '.$sBooleanExpr.'; return true;');
			if($bOk == false)
			{
				SetupPage::log_warning("Eval of $sRelDir/$sFile returned false");
				echo "Failed to parse the boolean Expression = '$sBooleanExpr'<br/>";			
			}
		}
		return $bResult;
	}

	/**
	 * Search (on the disk) for all defined iTop modules, load them and returns the list (as an array)
	 * of the possible iTop modules to install
	 * @param aSearchDirs Array of directories to search (absolute paths)
	 * @param bool  $bAbortOnMissingDependency ...
	 * @param hash $aModulesToLoad List of modules to search for, defaults to all if ommitted
	 * @return Hash A big array moduleID => ModuleData
	 */
	public static function GetAvailableModules($aSearchDirs, $bAbortOnMissingDependency = false, $aModulesToLoad = null)
	{
		if (self::$m_aSearchDirs != $aSearchDirs)
		{
			self::ResetCache();
		}
		
		if (is_null(self::$m_aSearchDirs))
		{
			self::$m_aSearchDirs = $aSearchDirs;
			
			// Not in cache, let's scan the disk
			foreach($aSearchDirs as $sSearchDir)
			{
				$sLookupDir = realpath($sSearchDir);			
				if ($sLookupDir == '')
				{
					throw new Exception("Invalid directory '$sSearchDir'");
				}
	
				clearstatcache();
				self::ListModuleFiles(basename($sSearchDir), dirname($sSearchDir));
			}
			return self::GetModules($bAbortOnMissingDependency, $aModulesToLoad);
		}
		else
		{
			// Reuse the previous results
			return self::GetModules($bAbortOnMissingDependency, $aModulesToLoad);
		}
	}
	
	public static function ResetCache()
	{
		self::$m_aSearchDirs = null;
		self::$m_aModules = array();
	}

	/**
	 * Helper function to interpret the name of a module
	 * @param $sModuleId string Identifier of the module, in the form 'name/version'
	 * @return array(name, version)
	 */    
	public static function GetModuleName($sModuleId)
	{
		if (preg_match('!^(.*)/(.*)$!', $sModuleId, $aMatches))
		{
			$sName = $aMatches[1];
			$sVersion = $aMatches[2];
		}
		else
		{
			$sName = $sModuleId;
			$sVersion = "";
		}
		return array($sName, $sVersion);
	}
	
	/**
	 * Helper function to browse a directory and get the modules
	 * @param $sRelDir string Directory to start from
	 * @return array(name, version)
	 */    
	protected static function ListModuleFiles($sRelDir, $sRootDir)
	{
		static $iDummyClassIndex = 0;
		static $aDefinedClasses = array();
		$sDirectory = $sRootDir.'/'.$sRelDir;
		
		if ($hDir = opendir($sDirectory))
		{
			// This is the correct way to loop over the directory. (according to the documentation)
			while (($sFile = readdir($hDir)) !== false)
			{
				$aMatches = array();
				if (is_dir($sDirectory.'/'.$sFile))
				{
					if (($sFile != '.') && ($sFile != '..') && ($sFile != '.svn'))
					{
						self::ListModuleFiles($sRelDir.'/'.$sFile, $sRootDir);
					}
				}
				else if (preg_match('/^module\.(.*).php$/i', $sFile, $aMatches))
				{
					self::SetModulePath($sRelDir);
					try
					{
						$sModuleFileContents = file_get_contents($sDirectory.'/'.$sFile);
						$sModuleFileContents = str_replace(array('<?php', '?>'), '', $sModuleFileContents);
						$sModuleFileContents = str_replace('__FILE__', "'".addslashes($sDirectory.'/'.$sFile)."'", $sModuleFileContents);
						preg_match_all('/class ([A-Za-z0-9_]+) extends ([A-Za-z0-9_]+)/', $sModuleFileContents, $aMatches);
						//print_r($aMatches);
						$idx = 0;
						foreach($aMatches[1] as $sClassName)
						{
							if (class_exists($sClassName))
							{
								// rename the class inside the code to prevent a "duplicate class" declaration
								// and change its parent class as well so that nobody will find it and try to execute it
								$sModuleFileContents = str_replace($sClassName.' extends '.$aMatches[2][$idx], $sClassName.'_'.($iDummyClassIndex++).' extends DummyHandler', $sModuleFileContents);
							}
							$idx++;
						}
						$bRet = eval($sModuleFileContents);
						
						if ($bRet === false)
						{
							SetupPage::log_warning("Eval of $sRelDir/$sFile returned false");
						}
						
						//echo "<p>Done.</p>\n";
					}
					catch(Exception $e)
					{
						// Continue...
						SetupPage::log_warning("Eval of $sRelDir/$sFile caused an exception: ".$e->getMessage());
					}
				}
			}
			closedir($hDir);
		}
		else
		{
			throw new Exception("Data directory (".$sDirectory.") not found or not readable.");
		}
	}
} // End of class


/** Alias for backward compatibility with old module files in which
 *  the declaration of a module invokes SetupWebPage::AddModule()
 *  whereas the new form is ModuleDiscovery::AddModule()
 */  
class SetupWebPage extends ModuleDiscovery
{
	// For backward compatibility with old modules...
	public static function log_error($sText)
	{
		SetupPage::log_error($sText);
	}

	public static function log_warning($sText)
	{
		SetupPage::log_warning($sText);
	}

	public static function log_info($sText)
	{
		SetupPage::log_info($sText);
	}

	public static function log_ok($sText)
	{
		SetupPage::log_ok($sText);
	}

	public static function log($sText)
	{
		SetupPage::log($sText);
	}	
}
		
/** Ugly patch !!!
 * In order to be able to analyse / load several times
 * the same module file, we rename the class (to avoid duplicate class definitions)
 * and we make the class extends the dummy class below in order to "deactivate" completely
 * the class (in case some piece of code enumerate the classes derived from a well known class)
 * Note that this will not work if someone enumerates the classes that implement a given interface
 */
class DummyHandler {
}
