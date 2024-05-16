<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service\Module;

use MetaModel;
use ReflectionClass;
use ReflectionMethod;
use utils;

class ModuleService
{
	/** @var ModuleService */
	private static $oInstance;

	private function __construct()
	{
	}

	public static function GetInstance(): ModuleService
	{
		if (!isset(static::$oInstance)) {
			static::$oInstance = new ModuleService();
		}

		return static::$oInstance;
	}

    /**
     * Get a "signature" of the method of an extension in the form of: "[module-name] class::method()"
     *
     * @param object|string $object Object or class
     * @param string $sMethod
     *
     * @return string
     * @throws \ReflectionException
     */
    public function GetModuleMethodSignature($object, string $sMethod): string
    {
        $sSignature = '';
        $oReflectionMethod = new ReflectionMethod($object, $sMethod);
        $oReflectionClass = $oReflectionMethod->getDeclaringClass();
        $sExtension = $this->GetModuleNameFromObject($oReflectionClass->getName());
        if (strlen($sExtension) !== 0) {
            $sSignature .= '['.$sExtension.'] ';
        }
        $sSignature .= $oReflectionClass->getShortName().'::'.$sMethod.'()';

        return $sSignature;
    }

    /**
     * Get the module name from an object or class
     *
     * @param object|string $object
     *
     * @return string
     * @throws \ReflectionException
     */
    public function GetModuleNameFromObject($object): string
    {
        $oReflectionClass = new ReflectionClass($object);
        $sPath = str_replace('\\', '/', $oReflectionClass->getFileName());
        $sPattern = str_replace('\\', '/', '@'.APPROOT.'env-'.utils::GetCurrentEnvironment()).'/(?<ext>.+)/@U';
        if (preg_match($sPattern, $sPath, $aMatches) !== false) {
            if (isset($aMatches['ext'])) {
                return $aMatches['ext'];
            }
        }

        return '';
    }

    /**
     * **Warning** : returned result can be invalid as we're using backtrace to find the module dir name
     *
     * @param int $iCallDepth The depth of the module in the callstack. Zero when called directly from within the module
     *
     * @return string the relative (to MODULESROOT) path of the root directory of the module containing the file where the call to
     *     this function is made
     *     or an empty string if no such module is found (or not called within a module file)
     *
     * @uses \debug_backtrace()
     */
    public function GetCurrentModuleDir(int $iCallDepth): string
    {
        $sCurrentModuleDir = '';
        $aCallStack = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $sCallerFile = realpath($aCallStack[$iCallDepth]['file']);

        foreach(GetModulesInfo() as $sModuleName => $aInfo)
        {
            if ($aInfo['root_dir'] !== '')
            {
                $sRootDir = realpath(APPROOT.$aInfo['root_dir']);

                if(substr($sCallerFile, 0, strlen($sRootDir)) === $sRootDir)
                {
                    $sCurrentModuleDir = basename($sRootDir);
                    break;
                }
            }
        }
        return $sCurrentModuleDir;
    }

    /**
     * **Warning** : as this method uses {@see GetCurrentModuleDir} it produces hazardous results.
     * You should better uses directly {@see GetAbsoluteUrlModulesRoot} and add the module dir name yourself ! See N°4573
     *
     * @return string the base URL for all files in the current module from which this method is called
     * or an empty string if no such module is found (or not called within a module file)
     * @throws \Exception
     *
     * @uses GetCurrentModuleDir
     */
    public function GetCurrentModuleUrl(int $iCallDepth = 0): string
    {
        $sDir = $this->GetCurrentModuleDir(1 + $iCallDepth);
        if ( $sDir !== '')
        {
            return utils::GetAbsoluteUrlModulesRoot().'/'.$sDir;
        }
        return '';
    }

    /**
     * @param string $sProperty The name of the property to retrieve
     * @param mixed $defaultValue
     *
     * @return mixed the value of a given setting for the current module
     */
    public function GetCurrentModuleSetting(string $sProperty, $defaultValue = null)
    {
        $sModuleName = $this->GetCurrentModuleName(1);
        return MetaModel::GetModuleSetting($sModuleName, $sProperty, $defaultValue);
    }

    /**
     * @param string $sModuleName
     *
     * @return string|NULL compiled version of a given module, as it was seen by the compiler
     */
    public function GetCompiledModuleVersion(string $sModuleName): ?string
    {
        $aModulesInfo = GetModulesInfo();
        if (array_key_exists($sModuleName, $aModulesInfo))
        {
            return $aModulesInfo[$sModuleName]['version'];
        }
        return null;
    }

    /**
     * Returns the name of the module containing the file where the call to this function is made
     * or an empty string if no such module is found (or not called within a module file)
     *
     * @param int $iCallDepth The depth of the module in the callstack. Zero when called directly from within the module
     *
     * @return string
     */
    public function GetCurrentModuleName(int $iCallDepth = 0): string
    {
        $sCurrentModuleName = '';
        $aCallStack = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $sCallerFile = realpath($aCallStack[$iCallDepth]['file']);

        return $this->GetModuleNameFromPath($sCallerFile);
    }

    private function GetModuleNameFromPath($sPath)
    {
        foreach (GetModulesInfo() as $sModuleName => $aInfo) {
            if ($aInfo['root_dir'] !== '') {
                $sRootDir = realpath(APPROOT.$aInfo['root_dir']);
                if (substr($sPath, 0, strlen($sRootDir)) === $sRootDir) {

                    return $sModuleName;
                }
            }
        }

        return '';
    }

    /**
     * Get the extension code from the call stack.
     * Scan the call stack until a module is found.
     *
     * @param int $iLevelsToIgnore
     *
     * @return string module name
     */
    public function GetModuleNameFromCallStack(int $iLevelsToIgnore = 0): string
    {
        $aCallStack = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $aCallStack = array_slice($aCallStack, $iLevelsToIgnore);

        foreach ($aCallStack as $aCallInfo) {
            $sFile = realpath(empty($aCallInfo['file']) ? '' : $aCallInfo['file']);

            $sModuleName = $this->GetModuleNameFromPath($sFile);
            if (strlen($sModuleName) > 0) {
                return $sModuleName;
            }
        }

        return '';
    }

}