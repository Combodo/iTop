<?php

class ModuleInstallationRepository
{
	private static ModuleInstallationRepository $oInstance;

	protected function __construct()
	{
	}

	final public static function GetInstance(): ModuleInstallationRepository
	{
		if (!isset(self::$oInstance)) {
			self::$oInstance = new ModuleInstallationRepository();
		}

		return self::$oInstance;
	}

	final public static function SetInstance(?ModuleInstallationRepository $oInstance): void
	{
		self::$oInstance = $oInstance;
	}

	private ?array $aSelectInstall = null;

	/**
* @param \Config|null $oConfig
* @return array
	 */
	public function ReadComputeInstalledModules(?Config $oConfig): array
	{
		$aSelectInstall = [];
		try {
			$aSelectInstall = $this->ReadFromDB($oConfig);
		} catch (MySQLException $e) {
			// No database or erroneous information
		}

		return $this->ComputeInstalledModules($aSelectInstall);
	}

	/**
* @param \Config|null $oConfig
* @return array
* @throws \MySQLException
* @throws \MySQLQueryHasNoResultException
	 */
	public function ReadFromDB(?Config $oConfig): array
	{
		if (is_null($oConfig)) {
			return [];
		}

		if (! is_null($this->aSelectInstall)) {
			//test only
			return $this->aSelectInstall;
		}

		CMDBSource::InitFromConfig($oConfig);
		//read db module installations
		$tableWithPrefix = $this->GetTableWithPrefix($oConfig);
		$iRootId = CMDBSource::QueryToScalar("SELECT max(parent_id) FROM $tableWithPrefix");
		// Get the latest installed modules, without the "root" ones (iTop version and datamodel version)
		$sSQL = <<<SQL
SELECT * FROM $tableWithPrefix
WHERE 
parent_id='$iRootId'
OR id='$iRootId'
SQL;
		try {
			return CMDBSource::QueryToArray($sSQL);
		} catch (MySQLException $e) {
			SetupLog::Exception(__METHOD__, $e);
			throw $e;
		}
	}

	private function GetTableWithPrefix(Config $oConfig)
	{
		$sPrefix = $oConfig->Get('db_subname');
		if (utils::IsNullOrEmptyString($sPrefix)) {
			return "priv_module_install";
		}

		return "{$sPrefix}priv_module_install";
	}

	/**
	 * @param \Config $oConfig
	 *
	 * @return array|false
	 */
	public function GetApplicationVersion(Config $oConfig)
	{
		try {
			CMDBSource::InitFromConfig($oConfig);
			$tableWithPrefix = $this->GetTableWithPrefix($oConfig);
			$sSQLQuery = "SELECT * FROM $tableWithPrefix";
			$aSelectInstall = CMDBSource::QueryToArray($sSQLQuery);
		} catch (MySQLException $e) {
			// No database or erroneous information
			SetupLog::Error(
				'Can not connect to the database',
				null,
				[
					'host'    => $oConfig->Get('db_host'),
					'user'    => $oConfig->Get('db_user'),
					'pwd:'    => $oConfig->Get('db_pwd'),
					'db name' => $oConfig->Get('db_name'),
					'msg'     => $e->getMessage(),
				]
			);
			return false;
		}

		$aResult = [];
		// Scan the list of installed modules to get the version of the 'ROOT' module which holds the main application version
		foreach ($aSelectInstall as $aInstall) {
			$sModuleVersion = $aInstall['version'];
			if ($sModuleVersion == '') {
				// Though the version cannot be empty in iTop 2.0, it used to be possible
				// therefore we have to put something here or the module will not be considered
				// as being installed
				$sModuleVersion = '0.0.0';
			}

			if ($aInstall['parent_id'] == 0) {
				if ($aInstall['name'] == DATAMODEL_MODULE) {
					$aResult['datamodel_version'] = $sModuleVersion;
					$aComments = json_decode($aInstall['comment'], true);
					if (is_array($aComments)) {
						$aResult = array_merge($aResult, $aComments);
					}
				} else {
					$aResult['product_name'] = $aInstall['name'];
					$aResult['product_version'] = $sModuleVersion;
				}
			}
		}
		if (!array_key_exists('datamodel_version', $aResult)) {
			// Versions prior to 2.0 did not record the version of the datamodel
			// so assume that the datamodel version is equal to the application version
			$aResult['datamodel_version'] = $aResult['product_version'];
		}

		SetupLog::Info(__METHOD__, null, ["product_name" => $aResult['product_name'], "product_version" => $aResult['product_version']]);

		return count($aResult) == 0 ? false : $aResult;
	}

	private function ComputeInstalledModules(array $aSelectInstall): array
	{
		$aInstallByModule = []; // array of <module> => array ('installed' => timestamp, 'version' => <version>)

		//module installation datetime is mostly the same for all modules
		//unless there was issue recording things in DB
		$sFirstDatetime = null;
		$iFirstTime = -1;
		foreach ($aSelectInstall as $aInstall) {
			//$aInstall['comment']; // unsused
			$sDatetime = $aInstall['installed'];

			if (is_null($sFirstDatetime)) {
				$sFirstDatetime = $sDatetime;
				$iFirstTime = strtotime($sDatetime);
				$iInstalled = $iFirstTime;
			} elseif ($sDatetime === $sFirstDatetime) {
				$iInstalled = $iFirstTime;
			} else {
				$sDatetime = $aInstall['installed'];
				$iInstalled = strtotime($sDatetime);
			}

			$sModuleName = $aInstall['name'];
			$sModuleVersion = $aInstall['version'];
			if ($sModuleVersion == '') {
				// Though the version cannot be empty in iTop 2.0, it used to be possible
				// therefore we have to put something here or the module will not be considered
				// as being installed
				$sModuleVersion = '0.0.0';
			}

			if ($aInstall['parent_id'] == 0) {
				$aInstallByModule[ROOT_MODULE] = [
					'installed_version' => $sModuleVersion,
					'installed' => $iInstalled,
					'version' => $sModuleVersion,
				];
			} else {
				$aInstallByModule[$sModuleName] = [
					'installed' => $iInstalled,
					'version' => $sModuleVersion,
				];
			}
		}

		return $aInstallByModule;
	}

	/**
	 * Return previous module installation. offset is applied on parent_id.
	 * @param $iOffset: by default (offset=0) returns current installation
	 * @return array
	 */
	public static function GetPreviousModuleInstallationsByOffset(int $iOffset = 0): array
	{
		$oFilter = DBObjectSearch::FromOQL('SELECT ModuleInstallation AS mi WHERE mi.parent_id=0 AND mi.name!="datamodel"');
		$oSet = new DBObjectSet($oFilter, ['installed' => false]); // Most recent first
		$oSet->SetLimit($iOffset + 1);

		$iParentId = 0;
		while (!is_null($oModuleInstallation = $oSet->Fetch())) {
			/** @var \DBObject $oModuleInstallation */
			if ($iOffset == 0) {
				$iParentId = $oModuleInstallation->Get('id');
				break;
			}
			$iOffset--;
		}

		if ($iParentId === 0) {
			IssueLog::Error("no ITOP_APPLICATION ModuleInstallation found", null, ['offset' => $iOffset]);
			throw new \Exception("no ITOP_APPLICATION ModuleInstallation found");
		}

		$oFilter = DBObjectSearch::FromOQL("SELECT ModuleInstallation AS mi WHERE mi.id=$iParentId OR mi.parent_id=$iParentId");
		$oSet = new DBObjectSet($oFilter); // Most recent first
		$aRawValues = $oSet->ToArrayOfValues();
		$aValues = [];
		foreach ($aRawValues as $aRawValue) {
			$aValue = [];
			foreach ($aRawValue as $sAliasAttCode => $sValue) {
				// remove 'mi.' from AttCode
				$sAttCode = substr($sAliasAttCode, 3);
				$aValue[$sAttCode] = $sValue;
			}

			$aValues[] = $aValue;
		}
		return $aValues;
	}
}
