<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service\Events\Description;

/**
 * Description of the data given with an event when registering
 *
 * @api
 * @package EventsAPI
 * @since 3.1.0
 */
class EventDataDescription
{
	private string $sName;
	private string $sDescription;
	private string $sType;

	/**
	 * Create a data description
	 *
	 * @api
	 * @param string $sName Name of the parameter
	 * @param string $sDescription  Description of the parameter
	 * @param string $sType Type of the parameter
	 */
	public function __construct(string $sName, string $sDescription, string $sType)
	{
		$this->sName = $sName;
		$this->sDescription = $sDescription;
		$this->sType = $sType;
	}

	/**
	 * @return string
	 */
	public function GetName(): string
	{
		return $this->sName;
	}

	/**
	 * @param string $sName
	 */
	public function SetName(string $sName): void
	{
		$this->sName = $sName;
	}

	/**
	 * @return string
	 */
	public function GetDescription(): string
	{
		return $this->sDescription;
	}

	/**
	 * @param string $sDescription
	 */
	public function SetDescription(string $sDescription): void
	{
		$this->sDescription = $sDescription;
	}

	/**
	 * @return string
	 */
	public function GetType(): string
	{
		return $this->sType;
	}

	/**
	 * @param string $sType
	 */
	public function SetType(string $sType): void
	{
		$this->sType = $sType;
	}
}