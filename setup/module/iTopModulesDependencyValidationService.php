<?php
/**
 * Copyright (C) 2013-2024 Combodo SAS
 * This file is part of iTop.
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * You should have received a copy of the GNU Affero General Public License
 */

require_once __DIR__ . '/XmlModuleMetaInfo.php';
require_once __DIR__ . '/XmlModule.php';

/**
 * @group modulesDependencyValidation
 */
class iTopModulesDependencyValidationService {
	private static ?iTopModulesDependencyValidationService $oInstance;
	public static array $aModulesDataByModuleName=[];

	public array $aModules=[];
	private string $sCurrentModule;
	private array $aDefineNodes;
	private array $aDependencyNodes;
	private array $aAllDmFiles=[];
	private array $aSoftDependencyNodes=[];
	private array $aSubfoldersByModulename=[];

	public static function GetInstance(): iTopModulesDependencyValidationService
	{
		if (!isset(self::$oInstance)) {
			self::$oInstance = new iTopModulesDependencyValidationService();
		}

		return self::$oInstance;
	}

	public static function SetInstance(?iTopModulesDependencyValidationService $instance): void {
		self::$oInstance = $instance;
	}

	private static function GetModulesDataByModuleName() : array {
		if (count(self::$aModulesDataByModuleName)>0){
			return self::$aModulesDataByModuleName;
		}

		$aDirsToScan = [
			APPROOT.'datamodels/2.x',
			APPROOT.'extensions',
			APPROOT.'extensions',
			APPROOT.'data/production-modules',
			APPROOT.'data/production-modules',
		];
		self::$aModulesDataByModuleName = ModuleDiscovery::GetAvailableModules($aDirsToScan, true);

		return self::$aModulesDataByModuleName;
	}

	public function ListDatamodelFiles() : array
	{
		if (count($this->aAllDmFiles)==0){
			$aGlobPAtterns = [
				APPROOT.'datamodels/2.x/*',
				APPROOT.'application',
				APPROOT.'core',
				APPROOT.'extensions',
				APPROOT.'extensions/*',
				APPROOT.'data/production-modules',
				APPROOT.'data/production-modules/*',
			];

			foreach ($aGlobPAtterns as $sPattern) {
				$this->aAllDmFiles = array_merge($this->aAllDmFiles, glob("$sPattern/datamodel.*.xml"));
			}
		}
		return $this->aAllDmFiles;
	}

	private function CreateIfNeededAndGetXmlModule(string $sModuleName) : XmlModule {
		/** @var XmlModule $oCurrentXmlModule */
		$oCurrentXmlModule = $this->aModules[$sModuleName] ?? null;
		if (is_null($oCurrentXmlModule)){
			$oCurrentXmlModule = new XmlModule($sModuleName);
			$this->aModules[$sModuleName] = $oCurrentXmlModule;
		}

		return $oCurrentXmlModule;
	}

	public function FetchAllDependenciesViaDM()
	{
		foreach ($this->ListDatamodelFiles() as $sFile) {
			$this->FetchXmlMetaInfo($sFile);
		}

		foreach ($this->aDefineNodes as $sKey => $aModules){
			foreach ($aModules as $sModuleName){
				$this->CreateIfNeededAndGetXmlModule($sModuleName);
			}

			$aSoftlyDependentModules = $this->aSoftDependencyNodes[$sKey] ?? null;
			if (is_null($aSoftlyDependentModules)){
				continue;
			}

			foreach ($aSoftlyDependentModules as $sModuleName){
				$oCurrentXmlModule = $this->CreateIfNeededAndGetXmlModule($sModuleName);
				$oCurrentXmlModule->AddDependency($sKey, $aModules, $this->aModules);
			}
		}

		foreach ($this->aDependencyNodes as $sKey => $aModules){
			foreach ($aModules as $sModuleName){
				$aDefiningModules = $this->aDefineNodes[$sKey] ?? null;
				if (is_null($aDefiningModules)){
					continue;
				}

				$oCurrentXmlModule = $this->CreateIfNeededAndGetXmlModule($sModuleName);
				$oCurrentXmlModule->AddDependency($sKey, $aDefiningModules, $this->aModules);
			}
		}

		$this->OrderModules();
		$this->CompleteModuleDependencies();
	}

	public function FetchAllDependenciesViaModulesFiles()
	{
		$aFullnameClassesByModuleName=[];
		foreach (self::GetModulesDataByModuleName() as $sModuleName => $aModuleData){
			//echo "$sModuleName\n";
			$aFiles = $aModuleData[2]['datamodel'] ?? [];
			$sDir = dirname($aModuleData['module_file_path']);

			$aDeps=[];
			foreach ($aFiles as $sFile){
				if (preg_match("|.*model\.$sModuleName\.php|", $sFile)){
					continue;
				}
				//echo "$sDir/$sFile\n";
				$aDeps=array_merge($aDeps, $this->ListDeclaredFullnameClassesFromPhpFile("$sDir/$sFile"));
			}

			$aFullnameClassesByModuleName[$sModuleName]=$aDeps;
		}

		foreach ($aFullnameClassesByModuleName as $sModuleName => $aFullnameClasses){
			foreach (self::GetModulesDataByModuleName() as $sModuleName2 => $aModuleData){
				if ($sModuleName2 === $sModuleName){
					continue;
				}

				$sDir = dirname($aModuleData['module_file_path']);

				if (count($aFullnameClassesByModuleName)==0){
					continue;
				}

				$sStr = "";
				foreach ($aFullnameClasses as $sClass){
					$sStr .= <<<TXT
 -e "$sClass"
TXT;
				}

				if (strlen($sStr)==0){
					continue;
				}

				$bFound=false;
				foreach ($this->GetFolders($sModuleName2, $sDir) as $sFolderDir => $sGrepRecursiveCliOption) {
					$sCliCmd = str_replace('\\', '\\\\\\\\', sprintf("grep -l%s %s $sFolderDir", $sGrepRecursiveCliOption, $sStr));
					$sOutput = exec($sCliCmd);

					if (strlen($sOutput) != 0) {
						$bFound=true;
						$sCliCmd = str_replace('\\', '\\\\\\\\', sprintf("grep -o%s %s $sFolderDir", $sGrepRecursiveCliOption, $sStr));
						$sOutput = exec($sCliCmd);
						//echo "|$sOutput|\n";
						break;
					}
				}

				if ($bFound){
					$oCurrentXmlModule = $this->CreateIfNeededAndGetXmlModule($sModuleName);
					$oCurrentXmlModule2 = $this->CreateIfNeededAndGetXmlModule($sModuleName2);
					try{
						$sKey = $this->GetFirstFoundDepsUID($sOutput);
					} catch(\Exception $e){
						var_dump($aFullnameClassesByModuleName);
						var_dump(
							[ $sStr, $sCliCmd, $sModuleName, $sModuleName2, $sOutput]
						);
						throw $e;
					}
					$oCurrentXmlModule2->AddDependency($sKey, [$sModuleName], $this->aModules);
					//echo "$sModuleName2 => $sModuleName\n";
				}
			}
		}
	}

	public function GetFirstFoundDepsUID(string $sOutput) : string  {
		if (preg_match_all('|.*:(.*)|m', $sOutput, $aMatches)){
			//var_dump($aMatches);
			return $aMatches[1][0];
		}

		throw new \Exception('no match: ' . $sOutput);
	}

	private function GetFolders($sModuleName2, $sDir) : array
	{
		if (array_key_exists($sModuleName2, $this->aSubfoldersByModulename)){
			return $this->aSubfoldersByModulename[$sModuleName2];
		}

		$aRes=[];
		$aFiles=[];
		foreach (glob("$sDir/*") as $sPath){
			if (! is_dir($sPath)){
				$aFiles[]=$sPath;
				continue;
			}

			if (strpos($sPath, '\.git') !== false){
				continue;
			}

			if (strpos($sPath, 'vendor') !== false){
				continue;
			}

			if (strpos($sPath, 'test') !== false){
				continue;
			}

			$aRes[$sPath]="r";
		}

		$aRes=[ implode(' ', $aFiles) => "" ];
		$this->aSubfoldersByModulename[$sModuleName2]=$aRes;
		return $aRes;
	}

	private function GetModuleSuffix($sFile) : string
	{
		if (! preg_match('|.*datamodel\.([^\.]+)\.xml|', $sFile, $aMatches)){
			throw new \Exception("Regexp issue: $sFile");
		}
		return $aMatches[1];
	}

	public function FetchXmlMetaInfo($sFile) : void {
		$oDomDoc = new \DOMDocument('1.0', 'UTF-8');
		libxml_clear_errors();
		$oDomDoc->loadXml(file_get_contents($sFile));
		$aErrors = libxml_get_errors();
		if (count($aErrors) > 0)
		{
			throw new \Exception("Malformed XML");
		}

		$this->sCurrentModule = $this->GetModuleSuffix($sFile);

		if (! isset($this->aDefineNodes)){
			$this->aDefineNodes=[];
		}

		if (! isset($this->aDependencyNodes)){
			$this->aDependencyNodes=[];
		}

		foreach ($oDomDoc->childNodes as $oDomNode){
			$this->FetchMetaInfo($oDomDoc->childNodes);
		}
	}

	private function FetchMetaInfo(\DOMNodeList $oDomNodeList, ?string $sPath=null)
	{
		/** @var \DOMNode $oDomNode */
		foreach ($oDomNodeList as $oDomNode) {
			/** @var \DOMAttr $oDelta */
			$oDelta = $oDomNode->attributes['_delta'] ?? null;
			/** @var \DOMAttr $oId */
			$oId = $oDomNode->attributes['id'] ?? null;

			if (! is_null($oId)) {
				$sId = $oId->nodeValue;
				$sCurrentPath = $sPath ? $sPath."->".$sId : $sId;

				if (!is_null($oDelta)) {
					$oXmlModuleMetaInfo = new XmlModuleMetaInfo($sId, $oDomNode->nodeName, $sCurrentPath, $oDelta->nodeValue);
					$sKey = $oXmlModuleMetaInfo->GetUID();
					if ($oXmlModuleMetaInfo->IsDefine()) {
						if (array_key_exists($sKey, $this->aDefineNodes)) {
							$this->aDefineNodes[$sKey][] = $this->sCurrentModule;
						} else {
							$this->aDefineNodes[$sKey] = [$this->sCurrentModule];
						}
					} else {
						if (array_key_exists($sKey, $this->aDependencyNodes)) {
							$this->aDependencyNodes[$sKey][] = $this->sCurrentModule;
						} else {
							$this->aDependencyNodes[$sKey] = [$this->sCurrentModule];
						}
					}
				} else {
					$oXmlModuleMetaInfo = new XmlModuleMetaInfo($sId, $oDomNode->nodeName, $sCurrentPath, "nodelta");
					$sKey = $oXmlModuleMetaInfo->GetUID();
					if (array_key_exists($sKey, $this->aSoftDependencyNodes)) {
						$this->aSoftDependencyNodes[$sKey][] = $this->sCurrentModule;
					} else {
						$this->aSoftDependencyNodes[$sKey] = [$this->sCurrentModule];
					}
				}
			} else if ($oDomNode instanceof \DOMElement){
				$sCurrentPath = $sPath ? $sPath . '->' . $oDomNode->nodeName : $oDomNode->nodeName;
			} else{
				$sCurrentPath = $sPath;
			}

			$this->FetchMetaInfo($oDomNode->childNodes, $sCurrentPath);
		}
	}

	private function OrderModules()
	{
		$aModuleDepsCount = [];
		/** @var XmlModule $oXmlModule */
		foreach ($this->aModules as $oXmlModule) {
			$aModuleDepsCount[$oXmlModule->sModuleName] = count($oXmlModule->aDependencyModulesNames);
		}

		$aOrderModules=[];
		while (count($aModuleDepsCount)>0) {
			asort($aModuleDepsCount);

			foreach ($aModuleDepsCount as $sModuleName => $iCount){
				if ($iCount>0){
					throw new \Exception("still deps with $sModuleName");
				}

				unset($aModuleDepsCount[$sModuleName]);
				$aOrderModules[$sModuleName] = $this->aModules[$sModuleName];
				break;
			}

			//echo "$sModuleName\n";
			foreach ($aModuleDepsCount as $sStillToProcessModuleName => $c){
				/** @var XmlModule $oXmlStillToProcessModule */
				$oXmlStillToProcessModule = $this->aModules[$sStillToProcessModuleName];
				if ($oXmlStillToProcessModule->Depends($sModuleName)){
					$aModuleDepsCount[$sStillToProcessModuleName] = $c - 1 ;
				}
			}
		}

		$this->aModules = $aOrderModules;
	}

	private function CompleteModuleDependencies()
	{
		/** @var XmlModule $oXmlModule */
		foreach ($this->aModules as $oXmlModule) {
			$oXmlModule->CompleteModuleDependencies($this->aModules);
		}
	}

	/**
	 * Read declared classes/interfaces in modules.php file (either directly listed files or inside autoload)
	 * @param string : module file path
	 *
	 * @return array: list of fullname classes
	 */
	public function ListDeclaredFullnameClassesFromPhpFile(string $sPath) : array
	{
		if (false !== strpos($sPath, 'autoload.php')){
			return $this->ListDeclaredFullnameClassesFromAutoloadFile($sPath);
	    }

		$aRes=[];

		$sContent = file_get_contents($sPath);

		$sNamespace='';
		if (preg_match('|namespace (.*)[ ]*;|', $sContent, $aMatches)){
			$sNamespace=trim($aMatches[1]) . '\\';
		}


		if (preg_match_all('|^class ([a-zA-Z]*) |m', $sContent, $aMatches)){
			foreach($aMatches[1] as $sClass){
				$aRes[]=$sNamespace.$sClass;
			}
		}

		if (preg_match_all('|^interface ([a-zA-Z]*)|m', $sContent, $aMatches)){
			foreach($aMatches[1] as $sInterface){
				$aRes[]=$sNamespace.$sInterface;
			}
		}

		return $aRes;
	}

	/**
	 * Read declared classes/interfaces autoload file
	 *
	 * @param string : module file path
	 *
	 * @return array: list of fullname classes
	 */
	private function ListDeclaredFullnameClassesFromAutoloadFile(string $sPath) : array
	{
		$sAutoloadClassMap = dirname($sPath) . "/composer/autoload_classmap.php";
		//echo $sAutoloadClassMap . '\n';
		if (!is_file($sAutoloadClassMap)) {
			return [];
		}

		$sTempfile = tempnam(sys_get_temp_dir(), 'autoload_');
		$sContent = file_get_contents($sAutoloadClassMap);
		$sReplace=<<<TXT
\$aModuleFiles=
TXT;
		$sContent = preg_replace('|return|', $sReplace, $sContent);
		//var_dump($sContent);
		file_put_contents($sTempfile, $sContent);
		require_once $sTempfile;
		@unlink($sTempfile);

		$aRes=[];
		foreach (array_keys($aModuleFiles) as $sClass){
			if (strpos($sClass, 'InstalledVersions')){
				continue;
			}
			$aRes[]=$sClass;
		}
		return $aRes;
	}
}


