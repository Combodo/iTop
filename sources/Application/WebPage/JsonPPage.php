<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\WebPage;

use CoreException;
use ExecutionKPI;
use utils;

/**
 * Class JsonPPage
 * Handles JSON-P calls {@link https://en.wikipedia.org/wiki/JSONP}
 *
 * @author Stephen Abello <stephen.abello@combodo.com>
 * @since 3.1.0
 */
class JsonPPage extends JsonPage
{
	/** @var string Name of the callback to call on response */
	protected $sCallbackName;

	/**
	 * JsonPPage constructor.
	 *
	 * @param string $sCallbackName
	 *
	 * @throws \CoreException
	 */
	public function __construct(string $sCallbackName)
	{
		$oKpi = new ExecutionKPI();
		parent::__construct();
		$this->sContentType = 'application/javascript';
		$this->SetCallbackName($sCallbackName);
		$oKpi->ComputeStats(get_class($this).' creation', 'JsonPPage');
	}

	/**
	 * @param string $sCallbackName
	 *
	 * @return $this
	 * @throws \CoreException
	 *@see JsonPPage::$sCallbackName
	 *
	 */
	public function SetCallbackName(string $sCallbackName)
	{
		if (utils::IsNullOrEmptyString($sCallbackName)) {
			throw new CoreException('JsonPPage callback cannot be empty');
		}

		$this->sCallbackName = $sCallbackName;

		return $this;
	}

	/**
	 * @return string
	 *@see JsonPPage::$sCallbackName
	 */
	public function GetCallbackName(): string
	{
		return $this->sCallbackName;
	}

	/**
	 * @inheritDoc
	 */
	protected function ComputeContent(): string
	{
		$sContent = parent::ComputeContent();

		return $this->sCallbackName . '(' . $sContent . ');';
	}
}