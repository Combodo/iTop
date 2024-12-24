<?php

namespace Combodo\iTop\Service\InterfaceDiscovery;

use Combodo\iTop\Service\Cache\DataModelDependantCache;
use Exception;
use IssueLog;
use LogChannels;
use MetaModel;
use ReflectionClass;
use utils;

/**
 * Enumerate classes implementing given interfaces
 *
 * @api
 *
 * @since 3.2.0
 */
class InterfaceDiscovery
{
	private static InterfaceDiscovery $oInstance;
	private DataModelDependantCache $oCacheService;
	private ?array $aForcedClassMap = null; // For testing purposes

	const CACHE_NONE = 'CACHE_NONE';
	const CACHE_DYNAMIC = 'CACHE_DYNAMIC';  // rebuild cache when files changes
	const CACHE_STATIC = 'CACHE_STATIC';    // Built once at setup

	private function __construct()
	{
		$this->oCacheService = DataModelDependantCache::GetInstance();
	}

	public static function GetInstance(): InterfaceDiscovery
	{
		if (!isset(self::$oInstance)) {
			self::$oInstance = new InterfaceDiscovery();
		}

		return self::$oInstance;
	}

	/**
	 * Find the ITOP classes implementing a given interface. The returned classes have the following properties:
	 * - They can be instantiated
	 * - They are not aliases
	 * - Their path relative to iTop does not contain /lib/, /node_modules/, /test/ or /tests/
	 *
	 * @param string $sInterface Fully qualified interface name
	 *
	 * @return array of fully qualified class names
	 * @throws \ReflectionException when $sInterface is not an interface
	 *
	 * @api
	 *
	 * @since 3.2.0
	 */
	public function FindItopClasses(string $sInterface): array
	{
		$aExcludedPaths = ['/lib/', '/node_modules/', '/test/', '/tests/', '/vendor/'];

		return $this->FindClasses($sInterface, $aExcludedPaths);
	}

	/**
	 * @param string $sInterface
	 * @param array $aExcludedPaths
	 *
	 * @return array
	 * @throws \ReflectionException
	 */
	private function FindClasses(string $sInterface, array $aExcludedPaths = []): array
	{
		if ($this->GetCacheMode() !== self::CACHE_NONE) {
			$sCacheUniqueKey = $this->MakeCacheKey($sInterface, $aExcludedPaths);
			if ($this->IsCacheValid($sCacheUniqueKey)) {
				return $this->ReadClassesFromCache($sCacheUniqueKey);
			}
		}

		$sExcludedPathsRegExp = $this->GetExcludedPathsRegExp($aExcludedPaths);
		$aMatchingClasses = [];
		foreach ($this->GetCandidateClasses() as $sPHPClass => $sOptionalPHPFile) {
			if (!$this->IsValidPHPFile($sOptionalPHPFile, $sExcludedPathsRegExp)) {
				continue;
			}

			if ($this->IsInterfaceImplementation($sPHPClass, $sInterface)) {
				$aMatchingClasses[] = $sPHPClass;
			}
		}

		if ($this->GetCacheMode() !== self::CACHE_NONE) {
			$this->SaveClassesToCache($sCacheUniqueKey, $aMatchingClasses, ['Interface' => $sInterface, 'Excluded paths' => implode(',', $aExcludedPaths)]);
		}

		return $aMatchingClasses;
	}

	private function GetAutoloadClassMaps(): array
	{
		if ($this->GetCacheMode() === self::CACHE_DYNAMIC) {
			$aAutoloadClassMaps = $this->oCacheService->Fetch('InterfaceDiscovery', 'autoload_classmaps');
			if ($aAutoloadClassMaps !== null) {
				return $aAutoloadClassMaps;
			}
		}

		// guess all the autoload class maps from the extensions
		$aAutoloadClassMaps = glob(APPROOT.'env-'.utils::GetCurrentEnvironment().'/*/vendor/composer/autoload_classmap.php');
		if($aAutoloadClassMaps === false) {
			$aAutoloadClassMaps = [];
		}
		$aAutoloadClassMaps[] = APPROOT.'lib/composer/autoload_classmap.php';
		$aAutoloadClassMaps[] = APPROOT.'env-'.utils::GetCurrentEnvironment().'/itop-portal-base/portal/vendor/composer/autoload_classmap.php';

		if ($this->GetCacheMode() === self::CACHE_DYNAMIC) {
			$this->oCacheService->Store('InterfaceDiscovery', 'autoload_classmaps', $aAutoloadClassMaps);
		}

		return $aAutoloadClassMaps;
	}

	/**
	 * @param string $sPHPClass
	 * @param string $sInterface
	 *
	 * @return bool
	 *
	 * @throws \ReflectionException
	 */
	private function IsInterfaceImplementation(string $sPHPClass, string $sInterface): bool
	{
		try {
			$oRefClass = new ReflectionClass($sPHPClass);
		}
		catch (Exception $e) {
			return false;
		}

		if (!$oRefClass->implementsInterface($sInterface)) {
			return false;
		}
		if ($oRefClass->isInterface()) {
			return false;
		}
		if ($oRefClass->isAbstract()) {
			return false;
		}
		if ($oRefClass->isTrait()) {
			return false;
		}
		if ($oRefClass->getName() !== $sPHPClass) {
			return false;
		} // Skip aliases

		return true;
	}

	protected function GetCandidateClasses(): array
	{
		$aClassMap = [];
		$aAutoloaderErrors = [];
		if ($this->aForcedClassMap !== null) {
			$aClassMap = $this->aForcedClassMap;
		} else {
			foreach ($this->GetAutoloadClassMaps() as $sAutoloadFile) {
				if (false === utils::RealPath($sAutoloadFile, APPROOT)) {
					// can happen when we still have the autoloader symlink in env-*, but it points to a file that no longer exists
					$aAutoloaderErrors[] = $sAutoloadFile;
					continue;
				}
				$aTmpClassMap = include $sAutoloadFile;
				/** @noinspection SlowArrayOperationsInLoopInspection we are getting an associative array so the documented workarounds cannot be used */
				$aClassMap = array_merge($aClassMap, $aTmpClassMap);
			}
			if (count($aAutoloaderErrors) > 0) {
				IssueLog::Debug(
					__METHOD__." cannot load some of the autoloader files",
					LogChannels::CORE,
					['autoloader_errors' => $aAutoloaderErrors]
				);
			}

			// Add already loaded classes
			$aCurrentClasses = array_fill_keys(get_declared_classes(), '');
			$aClassMap = array_merge($aCurrentClasses, $aClassMap);
		}

		return $aClassMap;
	}

	private function IsValidPHPFile(string $sOptionalPHPFile, ?string $sExcludedPathsRegExp): bool
	{
		if ($sOptionalPHPFile === '') {
			return true;
		}

		$sOptionalPHPFile = utils::LocalPath($sOptionalPHPFile);
		if ($sOptionalPHPFile === false) {
			return false;
		}

		if (is_null($sExcludedPathsRegExp)) {
			return true;
		}

		if (preg_match($sExcludedPathsRegExp, '/'.$sOptionalPHPFile) === 1) {
			return false;
		}

		return true;
	}

	private function IsCacheValid(string $sKey): bool
	{
		if ($this->aForcedClassMap !== null) {
			return false;
		}

		if (!$this->oCacheService->HasEntry('InterfaceDiscovery', $sKey)) {
			return false;
		}

		if ($this->GetCacheMode() === self::CACHE_STATIC) {
			return true;
		}

		// On development environment, we check the cache validity by comparing the cache file with the autoload_classmap files
		$iCacheTime = $this->oCacheService->GetEntryModificationTime('InterfaceDiscovery', $sKey);
		foreach ($this->GetAutoloadClassMaps() as $sSourceFile) {
			$iSourceTime = filemtime($sSourceFile);
			if ($iSourceTime > $iCacheTime) {
				return false;
			}
		}

		return true;
	}

	public function ReadClassesFromCache(string $sKey): array
	{
		return $this->oCacheService->Fetch('InterfaceDiscovery', $sKey);
	}

	protected function SaveClassesToCache(string $sKey, array $aMatchingClasses, array $aMoreInfo): void
	{
		if ($this->aForcedClassMap !== null) {
			return;
		}

		$this->oCacheService->Store('InterfaceDiscovery', $sKey, $aMatchingClasses, $aMoreInfo);
	}

	private function GetExcludedPathsRegExp(array $aExcludedPaths): ?string
	{
		if (count($aExcludedPaths) == 0) {
			return null;
		}

		$aExcludedPathRegExps = array_map(function ($sPath) {
			return preg_quote($sPath, '#');
		}, $aExcludedPaths);

		return '#'.implode('|', $aExcludedPathRegExps).'#';
	}

	protected function MakeCacheKey(string $sInterface, array $aExcludedPaths): string
	{
		if (count($aExcludedPaths) == 0) {
			$sKey = $sInterface;
		} else {
			$sKey = $sInterface.':'.implode(',', $aExcludedPaths);
		}

		$iPos = strrpos($sInterface, '\\');
		$sInterfaceDisplayName = $iPos === false ? $sInterface : substr($sInterface, $iPos + 1);

		return md5($sKey)."-$sInterfaceDisplayName";
	}

	/**
	 * @param \Combodo\iTop\Service\Cache\DataModelDependantCache $this->oCacheService
	 */
	public function SetCacheService(DataModelDependantCache $oCacheService): void
	{
		$this->oCacheService = $oCacheService;
	}

	protected function GetCacheMode(): string
	{
		if (!utils::IsDevelopmentEnvironment()) {
			return self::CACHE_STATIC;
		}

		if (!is_null(MetaModel::GetConfig()) && MetaModel::GetConfig()->Get('developer_mode.interface_cache.enabled')) {
			return self::CACHE_DYNAMIC;
		}

		return self::CACHE_NONE;
	}
}