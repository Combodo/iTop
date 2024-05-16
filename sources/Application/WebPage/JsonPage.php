<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\WebPage;

use ExecutionKPI;

/**
 * Class JsonPage
 *
 * @author Anne-Catherine Cognet <anne-catherine.cognet@combodo.com>
 * @author Eric Espie <eric.espie@combodo.com>
 * @since 3.0.0
 */
class JsonPage extends WebPage
{
	/** @var array Bags of data to include in the response */
	protected $aData = [];
	/**
	 * @var bool If true, only static::$aData will be output; otherwise data and scripts will be output in a structured object.
	 * This can be useful when feeding response to a third party lib that doesn't understand the structured format.
	 */
	protected $bOutputDataOnly = false;

	/**
	 * JsonPage constructor.
	 */
	public function __construct()
	{
		$oKpi = new ExecutionKPI();
		parent::__construct('');
		$this->sContentType = 'application/json';
		$oKpi->ComputeStats(get_class($this).' creation', 'JsonPage');
	}

	/**
	 * @return array
	 */
	public function GetData(): array
	{
		return $this->aData;
	}

	/**
	 * @param array $aData
	 *
	 * @return $this
	 */
	public function SetData(array $aData)
	{
		$this->aData = $aData;

		return $this;
	}

	/**
	 * @param array $aDataRow
	 *
	 * @return $this
	 */
	public function AddData(array $aDataRow)
	{
		$this->aData[] = $aDataRow;

		return $this;
	}

	/**
	 * @see static::$bOutputDataOnly
	 * @param bool $bFlag
	 *
	 * @return $this
	 */
	public function SetOutputDataOnly(bool $bFlag)
	{
		$this->bOutputDataOnly = $bFlag;

		return $this;
	}

	/**
	 * Output the headers
	 *
	 * @return void
	 * @since 3.1.0
	 */
	protected function OutputHeaders(): void
	{
		$this->add_header('Content-type: ' . $this->sContentType);

		foreach ($this->a_headers as $s_header) {
			header($s_header);
		}
	}

	/**
	 * @return string Content to output
	 * @since 3.1.0
	 */
	protected function ComputeContent(): string
	{
		$aScripts = array_merge($this->a_init_scripts, $this->a_scripts, $this->a_ready_scripts);

		$aJson = $this->bOutputDataOnly ? $this->aData : [
			'data'    => $this->aData,
			'scripts' => $aScripts,
		];

		return json_encode($aJson);
	}

	/**
	 * @inheritDoc
	 */
	public function output()
	{
		$oKpi = new ExecutionKPI();
		$this->OutputHeaders();
		$sContent = $this->ComputeContent();
		$oKpi->ComputeAndReport(get_class($this).' output');

		echo $sContent;
		$oKpi->ComputeAndReport('Echoing ('.round(strlen($sContent) / 1024).' Kb)');
		ExecutionKPI::ReportStats();
	}
}