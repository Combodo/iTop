<?php

namespace MyCompany\iTop\MyModule\Controller;

use Combodo\iTop\Application\TwigBase\Controller\Controller;
use UserRights;
use utils;

class MyModuleController extends Controller
{
	public function OperationHelloWorld()
	{
		$aParams['sName'] = UserRights::GetUser();
		$aParams['sDate'] = date("r");
		$aParams['aQuarter'] = ['January', 'February', 'March'];
		$this->DisplayPage($aParams);
	}

	// ...
	public function OperationSelectMonth()
	{
		$aMonths = ['January', 'February', 'March'];
		$iMonth = utils::ReadParam('month', 0);
		$aParams['sSelectedMonth'] = $aMonths[$iMonth];
		$this->DisplayPage($aParams);
	}
}