<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service\Events\Description;

/**
 * Description of an event when registering
 *
 * @api
 * @package EventsAPI
 * @since 3.1.0
 */
class EventDescription
{
	private string $sEventName;
	/** @var string[]|string|null  */
	private $mEventSources;
	private string $sDescription;
	private string $sReplaces;
	/** @var \Combodo\iTop\Service\Events\Description\EventDataDescription[]  */
	private array $aEventDataDescription;
	private string $sModule;

	/**
	 * Create a description for an event
	 *
	 * @api
	 * @param string $sEventName Name of the described event
	 * @param string|string[]|null $mEventSources Source(s) for this event (can be the root class for CRUD events)
	 * @param string $sDescription Description of the event
	 * @param string $sReplaces In case this event obsoletes previous extensibility functions
	 * @param \Combodo\iTop\Service\Events\Description\EventDataDescription[] $aEventDataDescription Description of the data associated with this event
	 * @param string $sModule iTop Module name where the event is defined
	 */
	public function __construct(string $sEventName, $mEventSources, string $sDescription, string $sReplaces, array $aEventDataDescription, string $sModule)
	{
		$this->sEventName = $sEventName;
		$this->mEventSources = $mEventSources;
		$this->sDescription = $sDescription;
		$this->sReplaces = $sReplaces;
		$this->aEventDataDescription = $aEventDataDescription;
		$this->sModule = $sModule;
	}

	/**
	 * @return string
	 */
	public function GetEventName(): string
	{
		return $this->sEventName;
	}

	/**
	 * @param string $sEventName
	 */
	public function SetEventName(string $sEventName): void
	{
		$this->sEventName = $sEventName;
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
	public function GetReplaces(): string
	{
		return $this->sReplaces;
	}

	/**
	 * @param string $sReplaces
	 */
	public function SetReplaces(string $sReplaces): void
	{
		$this->sReplaces = $sReplaces;
	}

	/**
	 * @return array
	 */
	public function GetEventDataDescription(): array
	{
		return $this->aEventDataDescription;
	}

	/**
	 * @param \Combodo\iTop\Service\Events\Description\EventDataDescription[] $aEventDataDescription
	 */
	public function SetEventDataDescription(array $aEventDataDescription): void
	{
		$this->aEventDataDescription = $aEventDataDescription;
	}

	/**
	 * @return string
	 */
	public function GetModule(): string
	{
		return $this->sModule;
	}

	/**
	 * @param string $sModule
	 */
	public function SetModule(string $sModule): void
	{
		$this->sModule = $sModule;
	}

	/**
	 * @return string|string[]|null
	 */
	public function GetEventSources()
	{
		return $this->mEventSources;
	}

	/**
	 * @param string|string[]|null $mEventSources
	 */
	public function SetEventSources($mEventSources): void
	{
		$this->mEventSources = $mEventSources;
	}
}