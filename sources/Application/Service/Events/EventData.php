<?php
/*
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
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
	private $sEvent;
	private $mEventSource;
	private $aEventData;
	private $aCallbackData;

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
	public function GetEvent()
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
		if (is_array($this->aEventData) && isset($this->aEventData[$sParam])) {
			return $this->aEventData[$sParam];
		}

		if (is_array($this->aCallbackData) && isset($this->aCallbackData[$sParam])) {
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
		$this->aCallbackData = $aCallbackData;
	}

	/**
	 * Get the data associated with the listener.
	 * The data were passed when registering the listener.
	 *
	 * @api
	 * @return mixed The data registered with the listener.
	 */
	public function GetCallbackData()
	{
		return $this->aCallbackData;
	}
}
