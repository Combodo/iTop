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
	private $sEventSource;
	private $mEventData;
	private $mCallbackData;

	/**
	 * EventServiceData constructor.
	 *
	 * @param string $sEvent
	 * @param string $sEventSource
	 * @param array $aEventData
	 * @param array $aCallbackData
	 */
	public function __construct($sEvent, $sEventSource, $aEventData, $aCallbackData)
	{
		$this->sEvent = $sEvent;
		$this->mEventData = $aEventData;
		$this->sEventSource = $sEventSource;
		$this->mCallbackData = $aCallbackData;
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
		if (is_array($this->mEventData) && isset($this->mEventData[$sParam]))
		{
			return $this->mEventData[$sParam];
		}

		if (is_array($this->mCallbackData) && isset($this->mCallbackData[$sParam]))
		{
			return $this->mCallbackData[$sParam];
		}
		return null;
	}

	/**
	 * @return string
	 */
	public function GetEventSource()
	{
		return $this->sEventSource;
	}

	/**
	 * @return mixed
	 */
	public function GetEventData()
	{
		return $this->mEventData;
	}

	/**
	 * @return mixed
	 */
	public function GetCallbackData()
	{
		return $this->mCallbackData;
	}
}
