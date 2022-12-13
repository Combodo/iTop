<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service;

use Closure;
use Combodo\iTop\Service\Description\EventDescription;
use ContextTag;
use CoreException;
use Exception;
use ExecutionKPI;
use ReflectionClass;
use utils;

/**
 * Event driven extensibility.
 * Inspired by [PSR-14: Event Dispatcher](https://www.php-fig.org/psr/psr-14).
 * Adapted to iTop needs in terms of event filtering (using event source or context).
 *
 * @package Combodo\iTop\Service
 * @api
 * @since 3.1.0
 */
class EventService
{
	private static $aEventListeners = [];
	private static $iEventIdCounter = 0;
	private static $aEventDescription = [];

	/**
	 * Initialize the Event Service. This is called by iTop.
	 *
	 * @internal
	 * @return void
	 */
	public static function InitService()
	{
		self::$aEventListeners = [];
		self::$iEventIdCounter = 0;
		self::$aEventDescription = [];

		$aEventServiceSetup = utils::GetClassesForInterface(iEventServiceSetup::class);
		foreach ($aEventServiceSetup as $sEventServiceSetupClass) {
			/** @var \Combodo\iTop\Service\iEventServiceSetup $oEventServiceSetup */
			$oEventServiceSetup = new $sEventServiceSetupClass();
			$oEventServiceSetup->RegisterEventsAndListeners();
		}

	}

	/**
	 * Register a callback for a specific event
	 *
	 * @api
	 * @param string $sEvent corresponding event
	 * @param callable $callback The callback to call
	 * @param array|string|null $sEventSource event filtering depending on the source of the event
	 * @param mixed $aCallbackData optional data given by the registrar to the callback
	 * @param array|string|null $context context filter
	 * @param float $fPriority optional priority for callback order
	 *
	 * @return string Id of the registration
	 *
	 */
	public static function RegisterListener(string $sEvent, callable $callback, $sEventSource = null, $aCallbackData = [], $context = null, float $fPriority = 0.0, $sModuleId = ''): string
	{
		if (!is_callable($callback, false, $sName)) {
			return false;
		}

		$aEventCallbacks = self::$aEventListeners[$sEvent] ?? [];
		$sId = 'event_'.self::$iEventIdCounter++;
		$aEventCallbacks[] = array(
			'id'       => $sId,
			'event'    => $sEvent,
			'callback' => $callback,
			'source'   => $sEventSource,
			'name'     => $sName,
			'data'     => $aCallbackData,
			'context'  => $context,
			'priority' => $fPriority,
			'module'   => $sModuleId,
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
		$sLogEventName = "$sEvent:".self::GetSourcesAsString($sEventSource);
		EventHelper::Trace("Registering event '$sLogEventName' for '$sName' with id '$sId' (total $iTotalRegistrations)");

		return $sId;
	}

	public static function GetListenersAsJSON()
	{
		return json_encode(self::$aEventListeners, JSON_PRETTY_PRINT);
	}

	/**
	 * Fire an event. Call all the callbacks registered for this event.
	 *
	 * @api
	 * @param \Combodo\iTop\Service\EventData $oEventData
	 *
	 * @throws \Exception from the callback
	 */
	public static function FireEvent(EventData $oEventData)
	{
		$sEvent = $oEventData->GetEvent();
		if (!self::IsEventRegistered($sEvent)) {
			$sError = "Fire event error: Event $sEvent is not registered";
			EventHelper::Error($sError);
			throw new CoreException($sError);
		}
		$eventSource = $oEventData->GetEventSource();
		$oKPI = new ExecutionKPI();
		$sLogEventName = "$sEvent - ".self::GetSourcesAsString($eventSource).' '.json_encode($oEventData->GetEventData());
		EventHelper::Trace("Fire event '$sLogEventName'");
		if (!isset(self::$aEventListeners[$sEvent])) {
			EventHelper::Debug("No listener for '$sLogEventName'", $sEvent, $eventSource);
			$oKPI->ComputeStats('FireEvent', $sEvent);

			return;
		}

		foreach (self::GetListeners($sEvent, $eventSource) as $aEventCallback) {
			if (!self::MatchContext($aEventCallback['context'])) {
				continue;
			}
			$sName = $aEventCallback['name'];
			EventHelper::Debug("Fire event '$sLogEventName' calling '$sName'", $sEvent, $eventSource);
			try {
				$oEventData->SetCallbackData($aEventCallback['data']);
				call_user_func($aEventCallback['callback'], $oEventData);
			}
			catch (Exception $e) {
				EventHelper::Error("Event '$sLogEventName' for '$sName' id {$aEventCallback['id']} failed with error: ".$e->getMessage());
				throw $e;
			}
		}
		EventHelper::Debug("End of event '$sLogEventName'", $sEvent, $eventSource);
		$oKPI->ComputeStats('FireEvent', $sEvent);
	}

	public static function GetListeners($sEvent, $eventSource)
	{
		$aListeners = [];
		if (isset(self::$aEventListeners[$sEvent])) {
			foreach (self::$aEventListeners[$sEvent] as $aEventCallback) {
				if (EventHelper::MatchEventSource($aEventCallback['source'], $eventSource)) {
					$aListeners[] = $aEventCallback;
				}
			}
		}

		return $aListeners;
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
			return substr($srcRegistered, 0, 30);
		}
		if (is_array($srcRegistered)) {
			return substr(implode(',', $srcRegistered), 0, 30);
		}

		return '';
	}

	/**
	 * Unregister a previously registered callback
	 *
	 * @param string $sId the callback registration id
	 */
	public static function UnRegisterListener(string $sId)
	{
		$bRemoved = self::Browse(function ($sEvent, $idx, $aEventCallback) use ($sId) {
			if ($aEventCallback['id'] == $sId) {
				$sName = self::$aEventListeners[$sEvent][$idx]['name'];
				unset (self::$aEventListeners[$sEvent][$idx]);
				EventHelper::Trace("Unregistered callback '$sName' id $sId' on event '$sEvent'");

				return false;
			}

			return true;
		});

		if (!$bRemoved) {
			EventHelper::Trace("No registration found for callback '$sId'");
		}
	}

	/**
	 * Unregister all the listeners for an event
	 *
	 * @param string $sEvent event to unregister
	 */
	public static function UnRegisterEventListeners(string $sEvent)
	{
		if (!isset(self::$aEventListeners[$sEvent])) {
			EventHelper::Trace("No registration found for event '$sEvent'");

			return;
		}

		unset(self::$aEventListeners[$sEvent]);
		EventHelper::Trace("Unregistered all the callbacks on event '$sEvent'");
	}

	/**
	 * Unregister all the events
	 */
	public static function UnRegisterAll()
	{
		self::$aEventListeners = array();
		EventHelper::Trace("Unregistered all events");
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

	/**
	 * Register an event.
	 * This allows to describe all the events available.
	 * This step is mandatory before firing an event.
	 *
	 * @api
	 * @param \Combodo\iTop\Service\Description\EventDescription $oEventDescription
	 *
	 * @return void
	 */
	public static function RegisterEvent(EventDescription $oEventDescription)
	{
		$sEvent = $oEventDescription->GetEventName();
		$sModule = $oEventDescription->GetModule();
		if (self::IsEventRegistered($sEvent)) {
			$sPrevious = self::$aEventDescription[$sEvent]['module'];
			EventHelper::Warning("The Event $sEvent defined by $sModule has already been defined in $sPrevious, check your delta");

			return;
		}

		self::$aEventDescription[$sEvent] = [
			'name'        => $sEvent,
			'description' => $oEventDescription,
			'module'      => $sModule,
		];
	}

	public static function GetEventsByClass($sClass)
	{
		$aRes = [];
		$oClass = new ReflectionClass($sClass);
		foreach (self::$aEventDescription as $sEvent => $aEventInfo) {
			if (is_array($aEventInfo['description']->GetSources())) {
				foreach ($aEventInfo['description']->GetSources() as $sSource) {
					if ($sClass == $sSource || $oClass->isSubclassOf($sSource)) {
						$aRes[$sEvent] = $aEventInfo;
					}
				}
			}
		}

		return $aRes;
	}

	/**
	 * Check is an event is already registered.
	 * Can be used to avoid exception when firing an unregistered event.
	 *
	 * @api
	 * @param string $sEvent
	 *
	 * @return bool
	 */
	public static function IsEventRegistered(string $sEvent): bool
	{
		return array_key_exists($sEvent, self::$aEventDescription);
	}

	public static function GetDefinedEventsAsJSON()
	{
		return json_encode(self::$aEventDescription, JSON_PRETTY_PRINT);
	}
}
