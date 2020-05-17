<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service;

use Closure;
use CoreException;
use Exception;
use IssueLog;
use ReflectionMethod;

define('LOG_EVENT_SERVICE_CHANNEL', 'EventService');

class Event
{
	private static $aEvents = array();

	/**
	 * Register a callback for specific event
	 *
	 * @param string $sEvent
	 * @param callable $callback The class to call (must implement iEventServiceCallable or iEventServiceCallableStatic for static classes)
	 * @param string $sEventSource event filtering depending on the source of the event
	 * @param mixed|null $mUserData Optional user data
	 * @param float $fPriority optional priority for callback order
	 *
	 * @return string Id of the registration (used for unregister)
	 * @throws \CoreException
	 */
	public static function Register($sEvent, callable $callback, $sEventSource = '', $mUserData = null, $fPriority = 0.0)
	{
		try
		{
			$sName = self::GetCallableDesc($callback);
		}
		catch (Exception $e)
		{
			throw new CoreException("EventService registering '{$sEvent}' Invalid callback: ".$e->getMessage());
		}

		if (isset(self::$aEvents[$sEvent]))
		{
			$aEventCallbacks = self::$aEvents[$sEvent];
		}
		else
		{
			$aEventCallbacks = array();
		}
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
			IssueLog::Debug("Registering event '$sEventName' for '$sName' with id '$sId' (total $iTotalRegistrations)", LOG_EVENT_SERVICE_CHANNEL);
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
		if (is_array($mEventData) && isset($mEventData['this']))
		{
			$sSource = ' from: '.get_class($mEventData['this']).':'.$mEventData['this']->GetKey();
		}
		else
		{
			$sSource = '';
		}
		$sEventName = ($sEventSource != '') ? "$sEvent:$sEventSource" : $sEvent;
		IssueLog::Debug("Fire event '$sEventName'$sSource", LOG_EVENT_SERVICE_CHANNEL);
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
			$sId = $aEventCallback['id'];

			IssueLog::Debug("Fire event '$sEventName' calling '{$sName}' id '{$sId}'", LOG_EVENT_SERVICE_CHANNEL);
			try
			{
				call_user_func($aEventCallback['callback'], new EventData($sEvent, $sEventSource, $mEventData, $aEventCallback['user_data']));
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
				IssueLog::Debug("Unregistered callback '{$sName}' id {$sId}' on event '{$sEvent}'", LOG_EVENT_SERVICE_CHANNEL);
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
		IssueLog::Debug("Unregistered all the callbacks on event '{$sEvent}'", LOG_EVENT_SERVICE_CHANNEL);
	}

	/**
	 * Unregister all the events
	 */
	public static function UnRegisterAll()
	{
		self::$aEvents = array();
		IssueLog::Debug("Unregistered all events", LOG_EVENT_SERVICE_CHANNEL);
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

	/**
	 * @param callable $callable
	 *
	 * @return bool|string
	 * @throws \ReflectionException
	 * @throws \CoreException
	 */
	private static function GetCallableDesc(callable $callable)
	{
		if (is_callable($callable))
		{
			switch (true)
			{
				case is_object($callable):
					return 'Closure' === get_class($callable) ? 'closure' : 'invocable';
				case is_string($callable):
					return $callable;
				case is_array($callable):
					$m = null;
					if (preg_match('~^(:?(?<reference>self|parent)::)?(?<method>[a-z_][a-z0-9_]*)$~i', $callable[1], $m))
					{
						if (is_string($callable[0]))
						{
							if ('parent' === strtolower($m['reference']))
							{
								list($left, $right) = [get_parent_class($callable[0]), $m['method']];
							}
							else
							{
								list($left, $right) = [$callable[0], $m['method']];
							}
							return self::GetName($left, $right);
						}
						else
						{
							if ('self' === strtolower($m['reference']))
							{
								list($left, $right) = [$callable[0], $m['method']];
							}
							else
							{
								list($left, $right) = $callable;
							}
							return self::GetName($left, $right);
						}
					}
					break;
			}
			return 'unknown';
		}
		throw new CoreException('Not a callable');
	}

	/**
	 * @param $left
	 * @param $right
	 *
	 * @return string
	 * @throws \ReflectionException
	 */
	private static function GetName($left, $right)
	{
		if (is_object($left))
		{
			return get_class($left).'::'.$right;
		}
		return (new ReflectionMethod($left, $right))->GetName();
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
