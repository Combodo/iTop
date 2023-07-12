<?php

namespace Combodo\iTop\Test;

class OrmCaseLogExtensionForTest extends \AbstractOrmCaseLogExtension
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

class FakeOrmCaseLogExtension1 extends \AbstractOrmCaseLogExtension
{
}

class FakeOrmCaseLogExtension2 extends \AbstractOrmCaseLogExtension
{
}

class FakeOrmCaseLogExtension3 extends \AbstractOrmCaseLogExtension
{
}
