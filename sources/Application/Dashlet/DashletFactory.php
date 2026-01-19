<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\Dashlet;

use Combodo\iTop\Application\Dashlet\Service\DashletService;
use ModelReflectionRuntime;

class DashletFactory
{
	private static DashletFactory $oInstance;
	private $oModelReflectionRuntime;

	protected function __construct()
	{
		$this->oModelReflectionRuntime = new ModelReflectionRuntime();
	}

	final public static function GetInstance(): DashletFactory
	{
		if (!isset(static::$oInstance)) {
			static::$oInstance = new DashletFactory();
		}

		return static::$oInstance;
	}

	public function SetModelReflectionRuntime(ModelReflectionRuntime $oModelReflectionRuntime): void
	{
		$this->oModelReflectionRuntime = $oModelReflectionRuntime;
	}

	public function CreateDashlet(string $sClass, string $sId): Dashlet
	{
		if (!DashletService::GetInstance()->IsDashletAvailable($sClass)) {
			$sClass = 'DashletUnknown';
			//throw new DashletException("Dashlet ".json_encode($sClass)." is not available");
		}

		/** @var Dashlet $oDashlet */
		$oDashlet = new $sClass($this->oModelReflectionRuntime, $sId);
		$oDashlet->SetDashletType($sClass);

		return $oDashlet;
	}
}
