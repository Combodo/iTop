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
use ReflectionClass;
use ReflectionException;

define('LOG_EVENT_SERVICE_CHANNEL', 'EventService');

class EventService
{
	private static $aEvents = array();

	/**
	 * Register a callback for specific event
	 *
	 * @param string $sEvent
	 * @param mixed $mClass The class to call (must implement iEventServiceCallable or iEventServiceCallableStatic for static classes)
	 * @param mixed|null $mUserData Optional user data
	 * @param string $sGroup optional group (for controlled mass un-registration)
	 * @param int $fPriority optional priority for callback order
	 *
	 * @return string Id of the registration (used for unregister)
	 * @throws \CoreException
	 */
	public static function Register($sEvent, $mClass, $mUserData = null, $sGroup = '', $fPriority = 0)
	{
		// Check the callable
		if (is_string($mClass))
		{
			try
			{
				$oReflectionClass = new ReflectionClass($mClass);
			}
			catch (ReflectionException $e)
			{
				throw new CoreException("Class '$mClass' not found");
			}
			if (!$oReflectionClass->implementsInterface('Combodo\iTop\Service\iEventServiceCallableStatic'))
			{
				throw new CoreException("Class '$mClass' does not implement iEventServiceCallableStatic interface");
			}
			$sClass = $mClass;
		}
		else
		{
			$sClass = get_class($mClass);
			if (!$mClass instanceof iEventServiceCallable)
			{
				throw new CoreException("Class '$sClass' does not implement iEventServiceCallable interface");
			}
		}

		if (isset(self::$aEvents[$sEvent]))
		{
			$aEventCallbacks = self::$aEvents[$sEvent];
		}
		else
		{
			$aEventCallbacks = array();
		}
		$sId = uniqid('event_');
		$aEventCallbacks[] = array(
			'id' => $sId,
			'callback' => array($mClass, 'OnEvent'),
			'class' => $sClass,
			'user_data' => $mUserData,
			'group' => $sGroup,
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
		IssueLog::Debug("Registering event '$sEvent' for class '$sClass' with id '$sId'", LOG_EVENT_SERVICE_CHANNEL);

		return $sId;
	}

	/**
	 * Unregister a previously registered callback
	 *
	 * @param string $sId the registration id
	 */
	public static function UnRegisterCallback($sId)
	{
		$bRemoved = self::Browse(function ($sEvent, $idx, $aEventCallback) use ($sId) {
			if ($aEventCallback['id'] == $sId)
			{
				unset (self::$aEvents[$sEvent][$idx]);
				IssueLog::Debug("Unregistered callback '{$sId}' on event '{$sEvent}'", LOG_EVENT_SERVICE_CHANNEL);
				return false;
			}
			return true;
		});

		if (!$bRemoved)
		{
			IssueLog::Debug("No registration found for callback '{$sId}'", LOG_EVENT_SERVICE_CHANNEL);
		}
	}

	/**
	 * Unregister a group
	 *
	 * @param string $sGroup the group to unregister
	 */
	public static function UnRegisterGroup($sGroup)
	{
		$iRemovedCount = 0;

		self::Browse(function ($sEvent, $idx, $aEventCallback) use ($sGroup, &$iRemovedCount) {
			if ($aEventCallback['group'] == $sGroup)
			{
				$sId = self::$aEvents[$sEvent][$idx]['id'];
				unset (self::$aEvents[$sEvent][$idx]);
				IssueLog::Debug("Unregistered callback '{$sId}' for the group '{$sGroup}' on event '{$sEvent}'", LOG_EVENT_SERVICE_CHANNEL);
				$iRemovedCount++;
			}
			return true;
		});

		if ($iRemovedCount == 0)
		{
			IssueLog::Debug("No registration found for group '{$sGroup}'", LOG_EVENT_SERVICE_CHANNEL);
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
			IssueLog::Debug("No registration found for event '$sEvent'", LOG_EVENT_SERVICE_CHANNEL);
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
	 * Trigger an event. Call all the callbacks registered for this event.
	 *
	 * @param string $sEvent event to trigger
	 * @param mixed|null $mEventData event related data
	 */
	public static function Trigger($sEvent, $mEventData = null)
	{
		IssueLog::Debug("Trigger event '$sEvent'", LOG_EVENT_SERVICE_CHANNEL);
		if (!isset(self::$aEvents[$sEvent]))
		{
			IssueLog::Debug("No registration found for event '$sEvent'", LOG_EVENT_SERVICE_CHANNEL);
			return;
		}

		foreach (self::$aEvents[$sEvent] as $aEventCallback)
		{
			IssueLog::Debug("Trigger event '$sEvent' calling '{$aEventCallback['class']}::OnEvent()' for id {$aEventCallback['id']}", LOG_EVENT_SERVICE_CHANNEL);
			try
			{
				call_user_func($aEventCallback['callback'], $sEvent, $mEventData, $aEventCallback['user_data']);
			}
			catch (Exception $e)
			{
				IssueLog::Error("Event '$sEvent' on '{$aEventCallback['class']}::OnEvent()' for id {$aEventCallback['id']} failed with error: ".$e->getMessage());
			}
		}
	}
}

interface iEventServiceCallable
{
	/** Called when a registered event is triggered
	 *
	 * @param string $sEvent Event triggered
	 * @param mixed|null $mEventData optional data relative to the event
	 * @param mixed|null $mUserData optional data relative to the Callback
	 *
	 */
	public function OnEvent($sEvent, $mEventData = null, $mUserData = null);
}

interface iEventServiceCallableStatic
{
	/** Called when a registered event is triggered
	 *
	 * @param string $sEvent Event triggered
	 * @param mixed|null $mEventData optional data relative to the event
	 * @param mixed|null $mUserData optional data relative to the Callback
	 *
	 */
	public static function OnEvent($sEvent, $mEventData = null, $mUserData = null);
}
