<?php

class MockValueSetObjects extends ValueSetObjects
{
	public function __construct($sFilterExp, $sValueAttCode = '', $aOrderBy = [], $bAllowAllData = false, $aModifierProperties = [])
	{
		parent::__construct($sFilterExp, $sValueAttCode, $aOrderBy, $bAllowAllData, $aModifierProperties);
	}
	public function GetFilterOQL(
		$sOperation,
		$sContains
	) {

		return $this->GetFilter($sOperation, $sContains)->ToOQL();

	}
}
