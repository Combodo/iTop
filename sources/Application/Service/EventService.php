<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service;

use Closure;
use ContextTag;
use Exception;
use ExecutionKPI;
use IssueLog;

define('LOG_EVENT_SERVICE_CHANNEL', 'EventService');

class EventService
{
	public static $aEventListeners = [];
	private static $iEventIdCounter = 0;
	private static $aEventDescription = [];

	/**
	 * Register a callback for a specific event
	 *
	 * @param string $sEvent corresponding event
	 * @param callable $callback The callback to call
	 * @param array|string|null $sEventSource event filtering depending on the source of the event
	 * @param mixed $aCallbackData optional data given by the registrar to the callback
	 * @param array|string|null $context context filter
	 * @param float $fPriority optional priority for callback order
	 *
	 * @return string Id of the registration (used for unregistering)
	 *
	 * @throws \Exception
	 */
	public static function RegisterListener(string $sEvent, callable $callback, $sEventSource = null, $aCallbackData = [], $context = null, float $fPriority = 0.0): string
	{
		is_callable($callback, false, $sName);

		$aEventCallbacks = self::$aEventListeners[$sEvent] ?? [];
		$sId = 'event_'.self::$iEventIdCounter++;
		$aEventCallbacks[] = array(
			'id'       => $sId,
			'callback' => $callback,
			'source'   => $sEventSource,
			'name'     => $sName,
			'data'     => $aCallbackData,
			'context'  => $context,
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
		self::$aEventListeners[$sEvent] = $aEventCallbacks;

		$iTotalRegistrations = 0;
		foreach (self::$aEventListeners as $aEvent) {
			$iTotalRegistrations += count($aEvent);
		}
		$sEventName = "$sEvent:".self::GetSourcesAsString($sEventSource);
		IssueLog::Trace("Registering event '$sEventName' for '$sName' with id '$sId' (total $iTotalRegistrations)", LOG_EVENT_SERVICE_CHANNEL);

		return $sId;
	}

	public static function GetListenersAsJSON()
	{
		return json_encode(self::$aEventListeners, JSON_PRETTY_PRINT);
	}

	/**
	 * Fire an event. Call all the callbacks registered for this event.
	 *
	 * @param \Combodo\iTop\Service\EventData $oEventData
	 *
	 * @throws \Exception from the callback
	 */
	public static function FireEvent(EventData $oEventData)
	{
		$sEvent = $oEventData->GetEvent();
		$eventSource = $oEventData->GetEventSource();
		$oKPI = new ExecutionKPI();
		$sSource = isset($aEventData['debug_info']) ? " {$aEventData['debug_info']}" : '';
		$sEventName = "$sEvent:".self::GetSourcesAsString($eventSource);
		IssueLog::Trace("Fire event '$sEventName'$sSource", LOG_EVENT_SERVICE_CHANNEL);
		if (!isset(self::$aEventListeners[$sEvent])) {
			IssueLog::Trace("No registration found for event '$sEvent'", LOG_EVENT_SERVICE_CHANNEL);
			$oKPI->ComputeStats('FireEvent', $sEvent);

			return;
		}

		foreach (self::$aEventListeners[$sEvent] as $aEventCallback) {
			if (!self::MatchEventSource($aEventCallback['source'], $eventSource)) {
				continue;
			}
			if (!self::MatchContext($aEventCallback['context'])) {
				continue;
			}
			$sName = $aEventCallback['name'];
			IssueLog::Debug("Fire event '$sEventName'$sSource calling '$sName'", LOG_EVENT_SERVICE_CHANNEL);
			try {
				if (is_callable($aEventCallback['callback'])) {
					$oEventData->SetCallbackData($aEventCallback['data']);
					call_user_func($aEventCallback['callback'], $oEventData);
				} else {
					IssueLog::Debug("Callback '$sName' not a callable anymore, unregister", LOG_EVENT_SERVICE_CHANNEL);
					self::UnRegisterCallback($aEventCallback['id']);
				}
			}
			catch (Exception $e) {
				IssueLog::Error("Event '$sEventName' for '$sName' id {$aEventCallback['id']} failed with error: ".$e->getMessage());
				throw $e;
			}
		}
		$oKPI->ComputeStats('FireEvent', $sEvent);
	}

	private static function MatchEventSource($srcRegistered, $srcEvent): bool
	{
		if (empty($srcRegistered)) {
			// no filtering
			return true;
		}
		if (empty($srcEvent)) {
			// no match (the registered source is not empty)
			return false;
		}
		if (is_string($srcRegistered)) {
			$aSrcRegistered = array($srcRegistered);
		} elseif (is_array($srcRegistered)) {
			$aSrcRegistered = $srcRegistered;
		} else {
			$aSrcRegistered = array();
		}

		if (is_string($srcEvent)) {
			$aSrcEvent = array($srcEvent);
		} elseif (is_array($srcEvent)) {
			$aSrcEvent = $srcEvent;
		} else {
			$aSrcEvent = array();
		}

		foreach ($aSrcEvent as $sSrcEvent) {
			if (in_array($sSrcEvent, $aSrcRegistered)) {
				// sources matches
				return true;
			}
		}

		// no match
		return false;
	}

	private static function MatchContext($registeredContext): bool
	{
		if (empty($registeredContext)) {
			return true;
		}
		if (is_string($registeredContext)) {
			$aContexts = array($registeredContext);
		} elseif (is_array($registeredContext)) {
			$aContexts = $registeredContext;
		} else {
			return false;
		}
		foreach ($aContexts as $sContext) {
			if (ContextTag::Check($sContext)) {
				return true;
			}
		}

		return false;
	}

	private static function GetSourcesAsString($srcRegistered): string
	{
		if (empty($srcRegistered)) {
			return '';
		}
		if (is_string($srcRegistered)) {
			return $srcRegistered;
		}
		if (is_array($srcRegistered)) {
			return implode(',', $srcRegistered);
		}

		return '';
	}

	/**
	 * Unregister a previously registered callback
	 *
	 * @param string $sId the callback registration id
	 */
	public static function UnRegisterCallback(string $sId)
	{
		$bRemoved = self::Browse(function ($sEvent, $idx, $aEventCallback) use ($sId) {
			if ($aEventCallback['id'] == $sId) {
				$sName = self::$aEventListeners[$sEvent][$idx]['name'];
				unset (self::$aEventListeners[$sEvent][$idx]);
				IssueLog::Trace("Unregistered callback '$sName' id $sId' on event '$sEvent'", LOG_EVENT_SERVICE_CHANNEL);

				return false;
			}

			return true;
		});

		if (!$bRemoved) {
			IssueLog::Trace("No registration found for callback '$sId'", LOG_EVENT_SERVICE_CHANNEL);
		}
	}

	/**
	 * Unregister an event
	 *
	 * @param string $sEvent event to unregister
	 */
	public static function UnRegisterEvent(string $sEvent)
	{
		if (!isset(self::$aEventListeners[$sEvent])) {
			IssueLog::Trace("No registration found for event '$sEvent'", LOG_EVENT_SERVICE_CHANNEL);

			return;
		}

		unset(self::$aEventListeners[$sEvent]);
		IssueLog::Trace("Unregistered all the callbacks on event '$sEvent'", LOG_EVENT_SERVICE_CHANNEL);
	}

	/**
	 * Unregister all the events
	 */
	public static function UnRegisterAll()
	{
		self::$aEventListeners = array();
		IssueLog::Trace("Unregistered all events", LOG_EVENT_SERVICE_CHANNEL);
	}

	/**
	 * Browse all the registrations
	 *
	 * @param \Closure $callback function($sEvent, $idx, $aEventCallback) to call (return false to interrupt the browsing)
	 *
	 * @return bool true if interrupted else false
	 */
	private static function Browse(closure $callback): bool
	{
		foreach (self::$aEventListeners as $sEvent => $aCallbackList) {
			foreach ($aCallbackList as $idx => $aEventCallback) {
				if (call_user_func($callback, $sEvent, $idx, $aEventCallback) === false) {
					return true;
				}
			}
		}

		return false;
	}

	// For information only
	public static function RegisterEvent(string $sEvent, string $sDescription, string $sModule)
	{
		if (isset(self::$aEventDescription[$sEvent])) {
			$sPrevious = self::$aEventDescription[$sEvent]['module'];
			IssueLog::Error("The Event $sEvent defined by $sModule has already been defined in $sPrevious, check your delta", LOG_EVENT_SERVICE_CHANNEL);
		}

		self::$aEventDescription[$sEvent] = [
			'constant'=> 'EVENT_SERVICE_'.strtoupper(self::FromCamelCase($sEvent)),
			'name'=> $sEvent,
			'description' => $sDescription,
			'module' => $sModule,
		];
	}

	// Intentionally duplicated from SetupUtils, not yet loaded when RegisterEvent is called
	private static function FromCamelCase($sInput) {
		$sPattern = '!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!';
		preg_match_all($sPattern, $sInput, $aMatches);
		$aRet = $aMatches[0];
		foreach ($aRet as &$sMatch) {
			$sMatch = $sMatch == strtoupper($sMatch) ?
				strtolower($sMatch) :
				lcfirst($sMatch);
		}
		return implode('_', $aRet);
	}


	public static function GetDefinedEventsAsJSON()
	{
		return json_encode(self::$aEventDescription, JSON_PRETTY_PRINT);
	}
}
