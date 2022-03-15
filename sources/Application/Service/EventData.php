<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service;


/**
 * Data given to the Event Service callbacks
 * Class EventServiceData
 *
 * @package Combodo\iTop\Service
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
	 * @param string $sEvent
	 * @param mixed $mEventSource
	 * @param array $aEventData
	 * @param array $aCallbackData
	 */
	public function __construct(string $sEvent, $mEventSource, array $aEventData, array $aCallbackData)
	{
		$this->sEvent = $sEvent;
		$this->aEventData = $aEventData;
		$this->mEventSource = $mEventSource;
		$this->aCallbackData = $aCallbackData;
	}

	/**
	 * @return string
	 */
	public function GetEvent()
	{
		return $this->sEvent;
	}

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
	 * @return mixed
	 */
	public function GetEventSource()
	{
		return $this->mEventSource;
	}

	/**
	 * @return array
	 */
	public function GetEventData(): array
	{
		return $this->aEventData;
	}

	/**
	 * @return array
	 */
	public function GetCallbackData(): array
	{
		return $this->aCallbackData;
	}
}
