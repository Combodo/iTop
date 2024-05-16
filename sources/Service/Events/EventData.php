<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service\Events;


/**
 * Data given to the Event Service callbacks
 * Class EventServiceData
 *
 * @api
 * @package EventsAPI
 * @since 3.1.0
 */
class EventData
{
	private string $sEvent;
	private $mEventSource;
	private array $aEventData;
	/** @var array Additional data collected by the listener : they can be set in {@see \Combodo\iTop\Service\Events\EventService::RegisterListener} */
	private array $aCallbackData;

	/**
	 * EventServiceData constructor.
	 *
	 * @api
	 * @param string $sEvent    Event fired
	 * @param string|array|null $mEventSource Event source
	 * @param array $aEventData Event data for the listeners
	 */
	public function __construct(string $sEvent, $mEventSource = null, array $aEventData = [])
	{
		$this->sEvent = $sEvent;
		$this->aEventData = $aEventData;
		$this->mEventSource = $mEventSource;
		$this->aCallbackData = [];
	}

	/**
	 * Get the event fired.
	 *
	 * @api
	 * @return string Event fired.
	 */
	public function GetEvent(): string
	{
		return $this->sEvent;
	}

	/**
	 * Get any parameter from the data sent when firing the event.
	 *
	 * @api
	 * @param $sParam
	 *
	 * @return mixed|null Parameter given when firing the event.
	 */
	public function Get($sParam)
	{
		if (isset($this->aEventData[$sParam])) {
			return $this->aEventData[$sParam];
		}

		if (isset($this->aCallbackData[$sParam])) {
			return $this->aCallbackData[$sParam];
		}

		return null;
	}

	/**
	 * Get event source of fired event.
	 *
	 * @api
	 * @return mixed Source given when firing the event.
	 */
	public function GetEventSource()
	{
		return $this->mEventSource;
	}

	/**
	 * Get all the data sent when firing the event.
	 *
	 * @api
	 * @return array All the data given when firing the event.
	 */
	public function GetEventData(): array
	{
		return $this->aEventData;
	}

	/**
	 * @param mixed $aCallbackData
	 */
	public function SetCallbackData($aCallbackData)
	{
		$this->aCallbackData = $aCallbackData ?? [];
	}

	/**
	 * @api
	 * @uses static::$aCallbackData
	 */
	public function GetCallbackData(): array
	{
		return $this->aCallbackData;
	}
}
