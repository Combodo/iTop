<?php
/*
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

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
		parent::__construct('');
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
	 * @inheritDoc
	 */
	public function output()
	{
		$this->add_header('Content-type: application/json');

		foreach ($this->a_headers as $s_header) {
			header($s_header);
		}

		$aScripts = array_merge($this->a_init_scripts, $this->a_scripts, $this->a_ready_scripts);

		$aJson = $this->bOutputDataOnly ? $this->aData : [
			'data' => $this->aData,
			'scripts' => $aScripts,
		];

		$oKpi = new ExecutionKPI();
		$sJSON = json_encode($aJson);
		echo $sJSON;
		$oKpi->ComputeAndReport('Echoing ('.round(strlen($sJSON) / 1024).' Kb)');
	}

}