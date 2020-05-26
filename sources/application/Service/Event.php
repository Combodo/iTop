<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service;

use Closure;
use Exception;
use ExecutionKPI;
use IssueLog;

define('LOG_EVENT_SERVICE_CHANNEL', 'EventService');

class Event
{
	private static $aEvents = array();
	private static $iEventIdCounter = 0;

	/**
	 * Register a callback for a specific event
	 *
	 * @param string $sEvent corresponding event
	 * @param callable $callback The callback to call
	 * @param string|array $sEventSource event filtering depending on the source of the event
	 * @param mixed|null $mUserData Optional user data
	 * @param float $fPriority optional priority for callback order
	 *
	 * @return string Id of the registration (used for unregistering)
	 *
	 * @throws \Exception
	 */
	public static function Register($sEvent, callable $callback, $sEventSource = null, $mUserData = null, $fPriority = 0.0)
	{
		is_callable($callback, false, $sName);

		$aEventCallbacks = isset(self::$aEvents[$sEvent]) ? self::$aEvents[$sEvent] : array();
		$sId = 'event_'.self::$iEventIdCounter++;
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
			$sEventName = "$sEvent:".self::GetSourcesAsString($sEventSource);
			IssueLog::Trace("Registering event '$sEventName' for '$sName' with id '$sId' (total $iTotalRegistrations)", LOG_EVENT_SERVICE_CHANNEL);
		}
		return $sId;
	}

	/**
	 * Fire an event. Call all the callbacks registered for this event.
	 *
	 * @param string $sEvent event to trigger
	 * @param string|array $sEventSource source of the event
	 * @param mixed|null $mEventData event related data
	 *
	 * @throws \Exception from the callback
	 */
	public static function FireEvent($sEvent, $sEventSource = null, $mEventData = null)
	{
		$oKPI = new ExecutionKPI();
		$sSource = isset($mEventData['debug_info']) ? " {$mEventData['debug_info']}" : '';
		$sEventName = "$sEvent:".self::GetSourcesAsString($sEventSource);
		IssueLog::Trace("Fire event '$sEventName'$sSource", LOG_EVENT_SERVICE_CHANNEL);
		if (!isset(self::$aEvents[$sEvent]))
		{
			IssueLog::Trace("No registration found for event '$sEvent'", LOG_EVENT_SERVICE_CHANNEL);
			$oKPI->ComputeStats('FireEvent', $sEvent);
			return;
		}

		foreach (self::$aEvents[$sEvent] as $aEventCallback)
		{
			if (!self::MatchEventSource($aEventCallback['source'], $sEventSource))
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
		$oKPI->ComputeStats('FireEvent', $sEvent);
	}

	private static function MatchEventSource($srcRegistered, $srcEvent)
	{
		if (empty($srcRegistered))
		{
			// no filtering
			return true;
		}
		if (empty($srcEvent))
		{
			// no match (the registered source is not empty)
			return false;
		}
		if (is_string($srcRegistered))
		{
			$aSrcRegistered = array($srcRegistered);
		}
		elseif (is_array($srcRegistered))
		{
			$aSrcRegistered = $srcRegistered;
		}
		else
		{
			$aSrcRegistered = array();
		}

		if (is_string($srcEvent))
		{
			$aSrcEvent = array($srcEvent);
		}
		elseif (is_array($srcEvent))
		{
			$aSrcEvent = $srcEvent;
		}
		else
		{
			$aSrcEvent = array();
		}

		foreach ($aSrcRegistered as $sSrcRegistered)
		{
			foreach ($aSrcEvent as $sSrcEvent)
			{
				if ($sSrcRegistered == $sSrcEvent)
				{
					// sources matches
					return true;
				}
			}
		}
		// no match
		return false;
	}

	private static function GetSourcesAsString($srcRegistered)
	{
		if (empty($srcRegistered))
		{
			return '';
		}
		if (is_string($srcRegistered))
		{
			return $srcRegistered;
		}
		if (is_array($srcRegistered))
		{
			$sStr = implode(',', $srcRegistered);
		}
		return '';
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

	public static function GetEventNameList()
	{

		$aEventNameInstances = \MetaModel::EnumPlugins('iModuleExtension', iEventName::class);
		$aEventNameList = self::MergeEventNameLists($aEventNameInstances);

		return $aEventNameList;
	}

	/**
	 * @param \Combodo\iTop\Service\iEventName[] $aEventNameInstances
	 *
	 * @return array
	 */
	private static function MergeEventNameLists(array $aEventNameInstances)
	{
		$aEventNameList = array();

		foreach ($aEventNameInstances as $oEventName)
		{
			$aList = $oEventName->GetEventNameList();
			$sModule = $aList['module'];
			$aEvents = $aList['events'];

			if (empty($aEventNameList[$sModule]))
			{
				$aEventNameList[$sModule] = $aEvents;
			}
			else
			{
				$aEventNameList[$sModule] = array_merge($aEventNameList[$sModule], $aEvents);
			}
		}

		return $aEventNameList;
	}
}
