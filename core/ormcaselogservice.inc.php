<?php
/**
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

/**
 * Service dedicated to ormCaseLog rebuild
 *
 * @since 3.1.0 N°6275
 */
class ormCaseLogService
{
	/**
	 * Array of "providers" of welcome popup messages
	 * @var iOrmCaseLogExtension[]
	 */
	protected $aOrmCaseLogExtensions = null;

	public function __construct(array $aOrmCaseLogExtensions=null)
	{
		$this->aOrmCaseLogExtensions = $aOrmCaseLogExtensions;
	}

	protected function LoadCaseLogExtensions($aClassesForInterfaceOrmCaseLog=null) : array
	{
		if ($this->aOrmCaseLogExtensions !== null) return $this->aOrmCaseLogExtensions;

		if ($aClassesForInterfaceOrmCaseLog === null) {
			$aClassesForInterfaceOrmCaseLog = \utils::GetClassesForInterface(iOrmCaseLogExtension::class, '',
				array('[\\\\/]lib[\\\\/]', '[\\\\/]node_modules[\\\\/]', '[\\\\/]test[\\\\/]', '[\\\\/]tests[\\\\/]'));
		}

		$aConfiguredOrmCaseLogExtensionClasses = MetaModel::GetConfig()->Get('ormcaselog_extension_classes');
		$this->aOrmCaseLogExtensions = [];
		foreach ($aConfiguredOrmCaseLogExtensionClasses as $sConfiguredOrmCaseLogExtensionClass) {
			if (in_array($sConfiguredOrmCaseLogExtensionClass, $aClassesForInterfaceOrmCaseLog)){
				$this->aOrmCaseLogExtensions[] = new $sConfiguredOrmCaseLogExtensionClass();
			}
		}

		return $this->aOrmCaseLogExtensions;
	}

	/**
	 * @param string|null $sLog
	 * @param array|null $aIndex
	 *
	 * @return \ormCaseLog|null: returns rebuilt ormCaseLog. null if not touched
	 */
	public function Rebuild($sLog, $aIndex) : ?\ormCaseLog
	{
		$this->LoadCaseLogExtensions();

		$bTouched = false;
		foreach ($this->aOrmCaseLogExtensions as $oOrmCaseLogExtension){
			/** var iOrmCaseLogExtension $oOrmCaseLogExtension */
			$bTouched = $bTouched || $oOrmCaseLogExtension->Rebuild($sLog, $aIndex);
		}

		if ($bTouched){
			return new \ormCaseLog($sLog, $aIndex);
		}

		return null;
	}
}
