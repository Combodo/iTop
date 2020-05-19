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
	private $mUserData;

	/**
	 * EventServiceData constructor.
	 *
	 * @param $sEvent
	 * @param $sEventSource
	 * @param $mEventData
	 * @param $mUserData
	 */
	public function __construct($sEvent, $sEventSource, $mEventData, $mUserData)
	{
		$this->sEvent = $sEvent;
		$this->mEventData = $mEventData;
		$this->mUserData = $mUserData;
		$this->sEventSource = $sEventSource;
	}

	/**
	 * @return string
	 */
	public function GetEvent()
	{
		return $this->sEvent;
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
	public function GetUserData()
	{
		return $this->mUserData;
	}
}
