<?php

class OrmCaseLogExtensionForTest implements \iOrmCaseLogExtension
{
	private $sReturnedLog;
	private $aReturnedIndex;
	private $bTouched;

	public function __construct(){
	}

	public function Init($bTouched, $sReturnedLog, $aReturnedIndex){
		$this->bTouched = $bTouched;
		$this->sReturnedLog = $sReturnedLog;
		$this->aReturnedIndex = $aReturnedIndex;
	}

	public function Rebuild(&$sLog, &$aIndex) : bool{
		$sLog = $this->sReturnedLog;
		$aIndex = $this->aReturnedIndex;
		return $this->bTouched;
	}
}

