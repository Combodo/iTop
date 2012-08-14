<?php
// Copyright (C) 2010 Combodo SARL
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
	

	// Cache the results and the source directory
	// Note that, as class can be declared within the module files, they cannot be loaded twice.
	// Then the following assumption is made: within the same execution page, the module
	// discovery CANNOT be executed on several different paths
	protected static $m_sModulesRoot = null;
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

	protected static function GetModules($oP = null)
	{
		// Order the modules to take into account their inter-dependencies
		$aDependencies = array();
		foreach(self::$m_aModules as $sId => $aModule)
		{
			$aDependencies[$sId] = $aModule['dependencies'];
		}
		$aOrderedModules = array();
		$iLoopCount = 1;
		while(($iLoopCount < count(self::$m_aModules)) && (count($aDependencies) > 0) )
		{
			foreach($aDependencies as $sId => $aRemainingDeps)
			{
				$bDependenciesSolved = true;
				foreach($aRemainingDeps as $sDepId)
				{
					if (!in_array($sDepId, $aOrderedModules))
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
		if (count($aDependencies) >0)
		{
			$sHtml = "<ul><b>Warning: the following modules have unmet dependencies, and have been ignored:</b>\n";			
			foreach($aDependencies as $sId => $aDeps)
			{
				$aModule = self::$m_aModules[$sId];
				$sHtml.= "<li>{$aModule['label']} (id: $sId), depends on: ".implode(', ', $aDeps)."</li>";
			}
			$sHtml .= "</ul>\n";
			if ($oP instanceof SetupPage)
			{
				$oP->warning($sHtml); // used in the context of the installation
			}
			elseif (class_exists('SetupPage'))
			{
				SetupPage::log_warning($sHtml); // used in the context of ?
			}
			else
			{
				echo $sHtml; // used in the context of the compiler
			}
		}
		// Return the ordered list, so that the dependencies are met...
		$aResult = array();
		foreach($aOrderedModules as $sId)
		{
			$aResult[$sId] = self::$m_aModules[$sId];
		}
		return $aResult;
	}

	/**
	 * Search (on the disk) for all defined iTop modules, load them and returns the list (as an array)
	 * of the possible iTop modules to install
	 * @param sRootDir Application root directory
	 * @param sSearchDir Directory to search (relative to root dir)
	 * @return Hash A big array moduleID => ModuleData
	 */
	public static function GetAvailableModules($sRootDir, $sSearchDir, $oP = null)
	{
		$sLookupDir = realpath($sRootDir.'/'.$sSearchDir);

		if (is_null(self::$m_sModulesRoot))
		{
			// First call
			//
			if ($sLookupDir == '')
			{
				throw new Exception("Invalid directory '$sRootDir/$sSearchDir'");
			}
			self::$m_sModulesRoot = $sLookupDir;

			clearstatcache();
			self::ListModuleFiles($sSearchDir, $sRootDir);
			return self::GetModules($oP);
		}
		elseif (self::$m_sModulesRoot != $sLookupDir)
		{
			throw new Exception("Design issue: the discovery of modules cannot be made on two different paths (previous: ".self::$m_sModulesRoot.", new: $sLookupDir)");
		}
		else
		{
			// Reuse the previous results
			//
			return self::GetModules($oP);
		}
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
		$sDirectory = $sRootDir.'/'.$sRelDir;
		//echo "<p>$sDirectory</p>\n";
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
						//echo "<p>Loading: $sDirectory/$sFile...</p>\n";
						//SetupPage::log_info("Discovered module $sFile");
						require_once($sDirectory.'/'.$sFile);
						//echo "<p>Done.</p>\n";
					}
					catch(Exception $e)
					{
						// Continue...
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
class SetupWebPage extends ModuleDiscovery{}

?>
