<?php
/*
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class JsonPage extends WebPage
{
	protected $aData = [];

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

	public function output()
	{
		$this->add_header('Content-type: application/json');

		foreach ($this->a_headers as $s_header) {
			header($s_header);
		}

		$aScripts = array_merge($this->a_init_scripts, $this->a_scripts, $this->a_ready_scripts);

		$aJson = [
			'data' => $this->aData,
			'scripts' => $aScripts,
		];

		echo json_encode($aJson);
	}

}