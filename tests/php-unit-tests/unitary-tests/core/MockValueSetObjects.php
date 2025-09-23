<?php

class MockValueSetObjects extends ValueSetObjects
{
	public function __construct($sFilterExp, $sValueAttCode = '', $aOrderBy = array(), $bAllowAllData = false, $aModifierProperties = array())
	{
		parent::__construct($sFilterExp, $sValueAttCode , $aOrderBy, $bAllowAllData, $aModifierProperties );
	}
	public function GetFilterOQL(
		$sOperation, $sContains
	)
	{

		return $this->GetFilter($sOperation, $sContains)->ToOQL();

	}
}
