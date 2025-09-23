<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service\Events;

use Closure;
use Combodo\iTop\Service\Events\Description\EventDescription;
use Combodo\iTop\Service\InterfaceDiscovery\InterfaceDiscovery;
use Combodo\iTop\Service\Module\ModuleService;
use ContextTag;
use CoreException;
use DBObject;
use Exception;
use ExecutionKPI;
use ReflectionClass;
use utils;

/**
 * Event driven extensibility.
 * Inspired by [PSR-14: Event Dispatcher](https://www.php-fig.org/psr/psr-14).
 * Adapted to iTop needs in terms of event filtering (using event source or context).
 *
 * @package EventsAPI
 * @api
 * @since 3.1.0
 */
final class EventService
{
	/** @var array */
	private static array $aEventListeners = [];
	/** @var int */
	private static int $iEventIdCounter = 0;
	/** @var array */
	private static array $aEventDescriptions = [];

	/**
	 * Initialize the Event Service. This is called by iTop.
	 *
	 * @internal
	 * @return void
	 */
	public static function InitService()
	{
		$aEventServiceSetup = InterfaceDiscovery::GetInstance()->FindItopClasses(iEventServiceSetup::class);
		foreach ($aEventServiceSetup as $sEventServiceSetupClass) {
			/** @var iEventServiceSetup $oEventServiceSetup */
			$oEventServiceSetup = new $sEventServiceSetupClass();
			$oEventServiceSetup->RegisterEventsAndListeners();
		}

	}

	/**
	 * Register a callback for a specific event
	 *
	 * **Warning** : be ultra careful on memory footprint ! each callback will be saved in {@see aEventListeners}, and a callback is
	 * made of the whole object instance and the method name ({@link https://www.php.net/manual/en/language.types.callable.php}).
	 * For example to register on DBObject instances, you should better use {@see DBObject::RegisterCRUDListener()}
	 *
	 * @uses aEventListeners
	 *
	 * @api
	 * @param string $sEvent corresponding event
	 * @param callable $callback The callback to call
	 * @param array|string|null $sEventSource event filtering depending on the source of the event
	 * @param mixed $aCallbackData optional data given by the registrar to the callback
	 * @param array|string|null $context context filter
	 * @param float $fPriority optional priority for callback order
	 *
	 * @return string registration identifier
	 *
	 * @see DBObject::RegisterCRUDListener() to register in DBObject instances instead, to reduce memory footprint (callback saving)
	 *
	 * @since 3.1.0 method creation
	 * @since 3.1.0-3 3.1.1 3.2.0 N°6716 PHPDoc change to warn on memory footprint, and {@see DBObject::RegisterCRUDListener()} alternative
	 */
	public static function RegisterListener(string $sEvent, callable $callback, $sEventSource = null, array $aCallbackData = [], $context = null, float $fPriority = 0.0, $sModuleId = ''): string
	{
		if (!is_callable($callback, false, $sName)) {
			return false;
		}

		if (utils::IsNullOrEmptyString($sModuleId)) {
			$sModuleId = ModuleService::GetInstance()->GetModuleNameFromCallStack();
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

		$sSource = self::GetSourcesAsString($sEventSource);
		EventServiceLog::Trace("Registering Listener '$sName' for event '$sEvent' source '$sSource' from '$sModuleId'");

		return $sId;
	}

	/**
	 * @return false|string
	 */
	public static function GetListenersAsJSON()
	{
		return json_encode(self::$aEventListeners, JSON_PRETTY_PRINT);
	}

	/**
	 * Fire an event. Call all the callbacks registered for this event.
	 *
	 * @api
	 * @param \Combodo\iTop\Service\Events\EventData $oEventData
	 *
	 * @throws \Exception from the callback
	 */
	public static function FireEvent(EventData $oEventData)
	{
		$sEvent = $oEventData->GetEvent();
		if (!self::IsEventRegistered($sEvent)) {
			$sError = "Fire event error: Event $sEvent is not registered";
			EventServiceLog::Error($sError);
			throw new CoreException($sError);
		}
		$eventSource = $oEventData->GetEventSource();
		$sLogEventName = "$sEvent - ".self::GetSourcesAsString($eventSource).' '.json_encode($oEventData->GetEventData());
		EventServiceLog::Trace("Fire event '$sLogEventName'");
		if (!isset(self::$aEventListeners[$sEvent])) {

			return;
		}

		$oLastException = null;
		$sLastExceptionMessage = null;
		$bEventFired = false;
		foreach (self::GetListeners($sEvent, $eventSource) as $aEventCallback) {
			if (!self::MatchContext($aEventCallback['context'])) {
				continue;
			}
			$sName = $aEventCallback['name'];
			EventServiceLog::Debug("Fire event '$sLogEventName' calling '$sName'");
			$bEventFired = true;
			try {
				$oEventData->SetCallbackData($aEventCallback['data']);
				$oKPI = new ExecutionKPI();

				call_user_func($aEventCallback['callback'], $oEventData);

				if (is_array($aEventCallback['callback']) && !$oKPI->ComputeStatsForExtension($aEventCallback['callback'][0], $aEventCallback['callback'][1], "Event: $sEvent")) {
					$sSignature = ModuleService::GetInstance()->GetModuleMethodSignature($aEventCallback['callback'][0], $aEventCallback['callback'][1]);
					$oKPI->ComputeStats('FireEvent', "$sEvent callback: $sSignature");
				}
			}
			catch (EventException $e) {
				EventServiceLog::Error("Event '$sLogEventName' for '$sName' id {$aEventCallback['id']} failed with blocking error: ".$e->getMessage());
				throw $e;
			}
			catch (Exception $e) {
				$sLastExceptionMessage = "Event '$sLogEventName' for '$sName' id {$aEventCallback['id']} failed with non-blocking error: ".$e->getMessage();
				EventServiceLog::Error($sLastExceptionMessage);
				$oLastException = $e;
			}
		}
		if ($bEventFired) {
			EventServiceLog::Debug("End of event '$sLogEventName'");
		}

		if (!is_null($oLastException)) {
			EventServiceLog::Error("Throwing the last exception caught: $sLastExceptionMessage");
			throw $oLastException;
		}
	}

	/**
	 * @param string $sEvent
	 * @param $eventSource
	 *
	 * @return array
	 */
	public static function GetListeners(string $sEvent, $eventSource = null): array
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
				EventServiceLog::Trace("Unregistered callback '$sName' id $sId' on event '$sEvent'");

				return false;
			}

			return true;
		});

		if (!$bRemoved) {
			EventServiceLog::Trace("No registration found for callback '$sId'");
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
			EventServiceLog::Trace("No registration found for event '$sEvent'");

			return;
		}

		unset(self::$aEventListeners[$sEvent]);
		EventServiceLog::Trace("Unregistered all the callbacks on event '$sEvent'");
	}

	/**
	 * Unregister all the events
	 */
	public static function UnRegisterAll()
	{
		self::$aEventListeners = [];
		self::$aEventDescriptions = [];
		self::$iEventIdCounter = 0;
		EventServiceLog::Trace("Unregistered all events");
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
	 * @param \Combodo\iTop\Service\Events\Description\EventDescription $oEventDescription
	 *
	 * @return void
	 */
	public static function RegisterEvent(EventDescription $oEventDescription)
	{
		$sEvent = $oEventDescription->GetEventName();
		$sModule = $oEventDescription->GetModule();
		if (self::IsEventRegistered($sEvent)) {
			$sPrevious = self::$aEventDescriptions[$sEvent]['module'];
			EventServiceLog::Warning("The Event $sEvent defined by $sModule has already been defined in $sPrevious, check your delta");

			return;
		}

		self::$aEventDescriptions[$sEvent] = [
			'name'        => $sEvent,
			'description' => $oEventDescription,
			'module'      => $sModule,
		];
	}

	/**
	 * @param string $sClass
	 *
	 * @return array
	 * @throws \ReflectionException
	 * @throws \ReflectionException
	 * @throws \ReflectionException
	 */
	public static function GetEventsByClass(string $sClass): array
	{
		$aRes = [];
		$oClass = new ReflectionClass($sClass);
		foreach (self::$aEventDescriptions as $sEvent => $aEventInfo) {
			if (is_array($aEventInfo['description']->GetEventSources())) {
				foreach ($aEventInfo['description']->GetEventSources() as $sSource) {
					if (class_exists($sSource) && ($sClass == $sSource || $oClass->isSubclassOf($sSource))) {
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
		return array_key_exists($sEvent, self::$aEventDescriptions);
	}

	/**
	 * @return false|string
	 */
	public static function GetDefinedEventsAsJSON()
	{
		return json_encode(self::$aEventDescriptions, JSON_PRETTY_PRINT);
	}
}
