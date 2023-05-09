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
	protected $aOrmCaseLogExtension = null;

	public function __construct(array $aOrmCaseLogExtensions=null)
	{
		$this->aOrmCaseLogExtension = $aOrmCaseLogExtensions;
	}

	protected function LoadCaseLogExtensions()
	{
		if ($this->aOrmCaseLogExtension !== null) return;

		$aOrmCaseLogExtensions = [];
		$aOrmCaseLogExtensionClasses = \utils::GetClassesForInterface(iOrmCaseLogExtension::class, '', array('[\\\\/]lib[\\\\/]', '[\\\\/]node_modules[\\\\/]', '[\\\\/]test[\\\\/]', '[\\\\/]tests[\\\\/]'));
		foreach($aOrmCaseLogExtensionClasses as $sOrmCaseLogExtensionClass) {
			$aOrmCaseLogExtensions[] = new $sOrmCaseLogExtensionClass();
		}
		$this->aOrmCaseLogExtension = $aOrmCaseLogExtensions;
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
		foreach ($this->aOrmCaseLogExtension as $oOrmCaseLogExtension){
			/** var iOrmCaseLogExtension $oOrmCaseLogExtension */
			$bTouched = $bTouched || $oOrmCaseLogExtension->Rebuild($sLog, $aIndex);
		}

		if ($bTouched){
			return new \ormCaseLog($sLog, $aIndex);
		}

		return null;
	}
}
