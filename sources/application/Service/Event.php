<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service;

use Closure;
use Exception;
use IssueLog;

define('LOG_EVENT_SERVICE_CHANNEL', 'EventService');

class Event
{
	private static $aEvents = array();

	/**
	 * Register a callback for a specific event
	 *
	 * @param string $sEvent corresponding event
	 * @param callable $callback The callback to call
	 * @param string $sEventSource event filtering depending on the source of the event
	 * @param mixed|null $mUserData Optional user data
	 * @param float $fPriority optional priority for callback order
	 *
	 * @return string Id of the registration (used for unregistering)
	 *
	 * @throws \Exception
	 */
	public static function Register($sEvent, callable $callback, $sEventSource = '', $mUserData = null, $fPriority = 0.0)
	{
		is_callable($callback, false, $sName);

		$aEventCallbacks = isset(self::$aEvents[$sEvent]) ? self::$aEvents[$sEvent] : array();
		$sId = uniqid('event_', true);
		$aEventCallbacks[] = array(
			'id' => $sId,
			'callback' => $callback,
			'source' => $sEventSource,
			'name' => $sName,
			'user_data' => $mUserData,
			'priority' => $fPriority,
		);
		usort($aEventCallbacks, function ($a, $b) {
			$fPriorityA = $a['priority'];
			$fPriorityB = $b['priority'];
			if ($fPriorityA == $fPriorityB) {
				return 0;
			}
			return ($fPriorityA < $fPriorityB) ? -1 : 1;
		});
		self::$aEvents[$sEvent] = $aEventCallbacks;

		if (IssueLog::CanLog(IssueLog::LEVEL_DEBUG, LOG_EVENT_SERVICE_CHANNEL))
		{
			$iTotalRegistrations = 0;
			foreach (self::$aEvents as $aEvent)
			{
				$iTotalRegistrations += count($aEvent);
			}
			$sEventName = ($sEventSource != '') ? "$sEvent:$sEventSource" : $sEvent;
			IssueLog::Trace("Registering event '$sEventName' for '$sName' with id '$sId' (total $iTotalRegistrations)", LOG_EVENT_SERVICE_CHANNEL);
		}
		return $sId;
	}

	/**
	 * Fire an event. Call all the callbacks registered for this event.
	 *
	 * @param string $sEvent event to trigger
	 * @param string $sEventSource source of the event
	 * @param mixed|null $mEventData event related data
	 *
	 * @throws \Exception from the callback
	 */
	public static function FireEvent($sEvent, $sEventSource = '', $mEventData = null)
	{
		$sSource = isset($mEventData['debug_info']) ? " {$mEventData['debug_info']}" : '';
		$sEventName = ($sEventSource != '') ? "$sEvent:$sEventSource" : $sEvent;
		IssueLog::Trace("Fire event '$sEventName'$sSource", LOG_EVENT_SERVICE_CHANNEL);
		if (!isset(self::$aEvents[$sEvent]))
		{
			IssueLog::Trace("No registration found for event '$sEvent'", LOG_EVENT_SERVICE_CHANNEL);
			return;
		}

		foreach (self::$aEvents[$sEvent] as $aEventCallback)
		{
			if ($aEventCallback['source'] != '' && $sEventSource != $aEventCallback['source'])
			{
				continue;
			}
			$sName = $aEventCallback['name'];
			IssueLog::Debug("Fire event '$sEventName'$sSource calling '{$sName}'", LOG_EVENT_SERVICE_CHANNEL);
			try
			{
				if (is_callable($aEventCallback['callback']))
				{
					call_user_func($aEventCallback['callback'], new EventData($sEvent, $sEventSource, $mEventData, $aEventCallback['user_data']));
				}
				else
				{
					IssueLog::Debug("Callback '{$sName}' not a callable anymore, unregister", LOG_EVENT_SERVICE_CHANNEL);
					self::UnRegisterCallback($aEventCallback['id']);
				}
			}
			catch (Exception $e)
			{
				IssueLog::Error("Event '$sEventName' for '{$sName}' id {$aEventCallback['id']} failed with error: ".$e->getMessage());
				throw $e;
			}
		}
	}

	/**
	 * Unregister a previously registered callback
	 *
	 * @param string $sId the callback registration id
	 */
	public static function UnRegisterCallback($sId)
	{
		$bRemoved = self::Browse(function ($sEvent, $idx, $aEventCallback) use ($sId) {
			if ($aEventCallback['id'] == $sId)
			{
				$sName = self::$aEvents[$sEvent][$idx]['name'];
				unset (self::$aEvents[$sEvent][$idx]);
				IssueLog::Trace("Unregistered callback '{$sName}' id {$sId}' on event '{$sEvent}'", LOG_EVENT_SERVICE_CHANNEL);
				return false;
			}
			return true;
		});

		if (!$bRemoved)
		{
			IssueLog::Trace("No registration found for callback '{$sId}'", LOG_EVENT_SERVICE_CHANNEL);
		}
	}

	/**
	 * Unregister an event
	 *
	 * @param string $sEvent event to unregister
	 */
	public static function UnRegisterEvent($sEvent)
	{
		if (!isset(self::$aEvents[$sEvent]))
		{
			IssueLog::Trace("No registration found for event '$sEvent'", LOG_EVENT_SERVICE_CHANNEL);
			return;
		}

		unset(self::$aEvents[$sEvent]);
		IssueLog::Trace("Unregistered all the callbacks on event '{$sEvent}'", LOG_EVENT_SERVICE_CHANNEL);
	}

	/**
	 * Unregister all the events
	 */
	public static function UnRegisterAll()
	{
		self::$aEvents = array();
		IssueLog::Trace("Unregistered all events", LOG_EVENT_SERVICE_CHANNEL);
	}

	/**
	 * Browse all the registrations
	 *
	 * @param \Closure $callback function($sEvent, $idx, $aEventCallback) to call (return false to interrupt the browsing)
	 *
	 * @return bool true if interrupted else false
	 */
	private static function Browse(closure $callback)
	{
		foreach (self::$aEvents as $sEvent => $aCallbackList)
		{
			foreach ($aCallbackList as $idx => $aEventCallback)
			{
				if (call_user_func($callback, $sEvent, $idx, $aEventCallback) === false)
				{
					return true;
				}
			}
		}
		return false;
	}
}

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
