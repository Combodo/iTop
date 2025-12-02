<?php

/**
 * KPI logging extensibility point
 *
 * KPI Logger extension
 */
interface iKPILoggerExtension
{
	/**
	 * Init the statistics collected
	 *
	 * @return void
	 */
	public function InitStats();

	/**
	 * Add a new KPI to the stats
	 *
	 * @param \Combodo\iTop\Core\Kpi\KpiLogData $oKpiLogData
	 *
	 * @return mixed
	 */
	public function LogOperation($oKpiLogData);
}
