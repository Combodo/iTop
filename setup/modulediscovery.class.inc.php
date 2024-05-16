<?php
/**
 * Copyright (c) 2010-2024 Combodo SAS
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
 * along with iTop. If not, see <http://www.gnu.org/licenses/>
 *
 */

class MissingDependencyException extends CoreException
{
	/**
	 * @see \ModuleDiscovery::OrderModulesByDependencies property init
	 * @var array<string, array<string>>
	 *     module id as key
	 *     another array as value, containing : 'module' with module info, 'dependencies' with missing dependencies
	 */
	public $aModulesInfo;

	/**
	 * @return string HTML to print to the user the modules impacted
	 * @since 2.7.7 3.0.2 3.1.0 N°5090 PR #280
	 */
	public function getHtmlDesc($sHighlightHtmlBegin = null, $sHighlightHtmlEnd = null)
	{
		$sErrorMessage = <<<HTML
<p>The following modules have unmet dependencies:</p>
<ul>
HTML;
		foreach ($this->aModulesInfo as $sModuleId => $aModuleErrors) {
			$sModuleLabel = $aModuleErrors['module']['label'];
			$aModuleMissingDependencies = $aModuleErrors['dependencies'];
			$sErrorMessage .= <<<HTML
	<li><strong>{$sModuleLabel}</strong> ({$sModuleId}):
		<ul>
HTML;

			foreach ($aModuleMissingDependencies as $sMissingModule) {
				$sErrorMessage .= "<li>{$sMissingModule}</li>";
			}
			$sErrorMessage .= <<<HTML
		</ul>
	</li>
HTML;

		}
		$sErrorMessage .= '</ul>';

		return $sErrorMessage;
	}
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
	protected static $m_aModuleVersionByName = array();

	// All the entries below are list of file paths relative to the module directory
	protected static $m_aFilesList = array('datamodel', 'webservice', 'dictionary', 'data.struct', 'data.sample');


	// ModulePath is used by AddModule to get the path of the module being included (in ListModuleFiles)
	protected static $m_sModulePath = null;
	protected static function SetModulePath($sModulePath)
	{
		self::$m_sModulePath = $sModulePath;
	}

	/**
	 * @param string $sFilePath
	 * @param string $sId
	 * @param array $aArgs
	 *
	 * @throws \Exception for missing parameter
	 */
	public static function AddModule($sFilePath, $sId, $aArgs)
	{
		if (!array_key_exists('itop_version', $aArgs))
		{
			// Assume 1.0.2
			$aArgs['itop_version'] = '1.0.2';
		}
		foreach (array_keys(self::$m_aModuleArgs) as $sArgName)
		{
			if (!array_key_exists($sArgName, $aArgs))
			{
				throw new Exception("Module '$sId': missing argument '$sArgName'");
		   }
		}

		$aArgs['root_dir'] = dirname($sFilePath);
		$aArgs['module_file'] = $sFilePath;

		list($sModuleName, $sModuleVersion) = static::GetModuleName($sId);
		if ($sModuleVersion == '')
		{
			$sModuleVersion = '1.0.0';
		}

		if (array_key_exists($sModuleName, self::$m_aModuleVersionByName))
		{
			if (version_compare($sModuleVersion, self::$m_aModuleVersionByName[$sModuleName]['version'], '>'))
			{
				// Newer version, let's upgrade
				$sIdToRemove = self::$m_aModuleVersionByName[$sModuleName]['id'];
				unset(self::$m_aModules[$sIdToRemove]);

				self::$m_aModuleVersionByName[$sModuleName]['version'] = $sModuleVersion;
				self::$m_aModuleVersionByName[$sModuleName]['id'] = $sId;
			}
			else
			{
				// Older (or equal) version, let's ignore it
				return;
			}
		}
		else
		{
			// First version to be loaded for this module, remember it
			self::$m_aModuleVersionByName[$sModuleName]['version'] = $sModuleVersion;
			self::$m_aModuleVersionByName[$sModuleName]['id'] = $sId;
		}

		self::$m_aModules[$sId] = $aArgs;

		// Now keep the relative paths, as provided
		/*
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
		*/
		// Populate automatically the list of dictionary files
		$aMatches = array();
		if(preg_match('|^([^/]+)|', $sId, $aMatches)) // ModuleName = everything before the first forward slash
		{
			$sModuleName = $aMatches[1];
			$sDir = dirname($sFilePath);
			$aDirs = [
				$sDir => self::$m_sModulePath,
				$sDir.'/dictionaries' => self::$m_sModulePath.'/dictionaries'
			];
			foreach ($aDirs as $sRootDir => $sPath)
			{
				if ($hDir = @opendir($sRootDir))
				{
					while (($sFile = readdir($hDir)) !== false)
					{
						$aMatches = array();
						if (preg_match("/^[^\\.]+.dict.$sModuleName.php$/i", $sFile, $aMatches)) // Dictionary files named like <Lang>.dict.<ModuleName>.php are loaded automatically
						{
							self::$m_aModules[$sId]['dictionary'][] = $sPath.'/'.$sFile;
						}
					}
					closedir($hDir);
				}
			}
		}
	}

	/**
	 * Get the list of "discovered" modules, ordered based on their (inter) dependencies
	 *
	 * @param bool $bAbortOnMissingDependency ...
	 * @param array $aModulesToLoad List of modules to search for, defaults to all if omitted
	 *
	 * @return array
	 * @throws \MissingDependencyException
	 */
	protected static function GetModules($bAbortOnMissingDependency = false, $aModulesToLoad = null)
	{
		// Order the modules to take into account their inter-dependencies
		return self::OrderModulesByDependencies(self::$m_aModules, $bAbortOnMissingDependency, $aModulesToLoad);
	}

	/**
	 * Arrange an list of modules, based on their (inter) dependencies
	 * @param array $aModules The list of modules to process: 'id' => $aModuleInfo
	 * @param bool $bAbortOnMissingDependency ...
	 * @param array $aModulesToLoad List of modules to search for, defaults to all if omitted
	 * @return array
	 * @throws \MissingDependencyException
*/
	public static function OrderModulesByDependencies($aModules, $bAbortOnMissingDependency = false, $aModulesToLoad = null)
	{
		// Order the modules to take into account their inter-dependencies
		$aDependencies = [];
		$aSelectedModules = [];
		foreach($aModules as $sId => $aModule)
		{
			list($sModuleName, ) = self::GetModuleName($sId);
			if (is_null($aModulesToLoad) || in_array($sModuleName, $aModulesToLoad))
			{
				$aDependencies[$sId] = $aModule['dependencies'];
				$aSelectedModules[$sModuleName] = true;
			}
		}
		ksort($aDependencies);
		$aOrderedModules = [];
		$iLoopCount = 1;
		while(($iLoopCount < count($aModules)) && (count($aDependencies) > 0) )
		{
			foreach($aDependencies as $sId => $aRemainingDeps)
			{
				$bDependenciesSolved = true;
				foreach($aRemainingDeps as $sDepId)
				{
					if (!self::DependencyIsResolved($sDepId, $aOrderedModules, $aSelectedModules))
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
			$aModulesInfo = [];
			$aModuleDeps = [];
			foreach($aDependencies as $sId => $aDeps)
			{
				$aModule = $aModules[$sId];
				$aDepsWithIcons = [];
				foreach($aDeps as $sIndex => $sDepId)
				{
					if (self::DependencyIsResolved($sDepId, $aOrderedModules, $aSelectedModules))
					{
						$aDepsWithIcons[$sIndex] = '✅ ' . $sDepId;
					} else
					{
						$aDepsWithIcons[$sIndex] = '❌ ' .  $sDepId;
					}
				}
				$aModuleDeps[] = "{$aModule['label']} (id: $sId) depends on: ".implode(' + ', $aDepsWithIcons);
				$aModulesInfo[$sId] = array('module' => $aModule, 'dependencies' => $aDepsWithIcons);
			}
			$sMessage = "The following modules have unmet dependencies:\n".implode(",\n", $aModuleDeps);
			$oException = new MissingDependencyException($sMessage);
			$oException->aModulesInfo = $aModulesInfo;
			throw $oException;
		}
		// Return the ordered list, so that the dependencies are met...
		$aResult = array();
		foreach($aOrderedModules as $sId)
		{
			$aResult[$sId] = $aModules[$sId];
		}
		return $aResult;
	}

	/**
	 * Remove the duplicate modules (i.e. modules with the same name but with a different version) from the supplied list of modules
	 * @param array $aModules
	 * @return array The ordered modules as a duplicate-free list of modules
	 */
	public static function RemoveDuplicateModules($aModules)
	{
		// No longer needed, kept only for compatibility
		// The de-duplication is now done directly by the AddModule method
		return $aModules;
	}

	protected static function DependencyIsResolved($sDepString, $aOrderedModules, $aSelectedModules)
	{
		$bResult = false;
		$aModuleVersions = array();
		// Separate the module names from their version for an easier comparison later
		foreach($aOrderedModules as $sModuleId)
		{
			$aMatches = array();
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
			$aPotentialPrerequisites = array();
			foreach($aMatches as $aMatch)
			{
				foreach($aMatch as $sModuleId)
				{
					// $sModuleId in the dependency string is made of a <name>/<optional_operator><version>
					// where the operator is < <= = > >= (by default >=)
					$aModuleMatches = array();
					if(preg_match('|^([^/]+)/(<?>?=?)([^><=]+)$|', $sModuleId, $aModuleMatches))
					{
						$sModuleName = $aModuleMatches[1];
						$aPotentialPrerequisites[$sModuleName] = true;
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
			$bMissingPrerequisite = false;
			foreach (array_keys($aPotentialPrerequisites) as $sModuleName)
			{
				if (array_key_exists($sModuleName, $aSelectedModules))
				{
					// This module is actually a prerequisite
					if (!array_key_exists($sModuleName, $aModuleVersions))
					{
						$bMissingPrerequisite = true;
					}
				}
			}
			if ($bMissingPrerequisite)
			{
				$bResult = false;
			}
			else
			{
				$sBooleanExpr = str_replace(array_keys($aReplacements), array_values($aReplacements), $sDepString);
				$bOk = @eval('$bResult = '.$sBooleanExpr.'; return true;');
				if ($bOk == false)
				{
					SetupLog::Warning("Eval of '$sBooleanExpr' returned false");
					echo "Failed to parse the boolean Expression = '$sBooleanExpr'<br/>";
				}
			}
		}
		return $bResult;
	}

	/**
	 * Search (on the disk) for all defined iTop modules, load them and returns the list (as an array)
	 * of the possible iTop modules to install
	 *
	 * @param $aSearchDirs array of directories to search (absolute paths)
	 * @param bool $bAbortOnMissingDependency ...
	 * @param array $aModulesToLoad List of modules to search for, defaults to all if omitted
	 *
	 * @return array A big array moduleID => ModuleData
	 * @throws \Exception
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
		self::$m_aModuleVersionByName = array();
	}

	/**
	 * Helper function to interpret the name of a module
	 * @param $sModuleId string Identifier of the module, in the form 'name/version'
	 * @return array(name, version)
	 */
	public static function GetModuleName($sModuleId)
	{
		$aMatches = array();
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
	 *
	 * @param $sRelDir string Directory to start from
	 * @param $sRootDir string The root directory path
	 *
	 * @throws \Exception
	 */
	protected static function ListModuleFiles($sRelDir, $sRootDir)
	{
		static $iDummyClassIndex = 0;
		$sDirectory = $sRootDir.'/'.$sRelDir;

		if ($hDir = opendir($sDirectory))
		{
			// This is the correct way to loop over the directory. (according to the documentation)
			while (($sFile = readdir($hDir)) !== false)
			{
				$aMatches = array();
				if (is_dir($sDirectory.'/'.$sFile))
				{
					if (($sFile != '.') && ($sFile != '..') && ($sFile != '.svn') && ($sFile != 'vendor'))
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
							SetupLog::Warning("Eval of $sRelDir/$sFile returned false");
						}

						//echo "<p>Done.</p>\n";
					}
					catch(ParseError $e)
					{
					    // PHP 7
						SetupLog::Warning("Eval of $sRelDir/$sFile caused an exception: ".$e->getMessage()." at line ".$e->getLine());
					}
					catch(Exception $e)
					{
						// Continue...
						SetupLog::Warning("Eval of $sRelDir/$sFile caused an exception: ".$e->getMessage());
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
		SetupLog::Error($sText);
	}

	public static function log_warning($sText)
	{
		SetupLog::Warning($sText);
	}

	public static function log_info($sText)
	{
		SetupLog::Info($sText);
	}

	public static function log_ok($sText)
	{
		SetupLog::Ok($sText);
	}

	public static function log($sText)
	{
		SetupLog::Ok($sText);
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

