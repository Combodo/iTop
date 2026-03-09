<?php

/**
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace DBObject\Utils;

use Combodo\iTop\Service\Events\EventData;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use MetaModel;

/**
 * Test support class used to count events received
 * And allow callbacks on events
 */
class CRUDEventReceiver
{
	public bool $bDBUpdateCalledSuccessfullyDuringEvent = false;

	private static $oTestCase;
	private array $aCallbacks = [];

	public function __construct(ItopDataTestCase $oTestCase)
	{
		self::$oTestCase = $oTestCase;
	}

	/**
	 * Add a specific callback for an event
	 *
	 * @param string $sEvent event name
	 * @param string $sClass event source class name
	 * @param string $sFct   function to call on CRUDEventReceiver object
	 * @param int $iCount    limit the number of calls to the callback
	 *
	 * @return void
	 */
	public function AddCallback(string $sEvent, string $sClass, string $sFct, int $iCount = 1): void
	{
		$this->aCallbacks[$sEvent][$sClass] = [
			'callback' => [$this, $sFct],
			'count' => $iCount,
		];
	}

	public function CleanCallbacks()
	{
		$this->aCallbacks = [];
		$this->bDBUpdateCalledSuccessfullyDuringEvent = false;
	}

	/**
	 * Event callbacks => this function counts the received events by event name and source class
	 * If AddCallback() method has been called a specific callback is called, else only the count is done
	 *
	 * @param \Combodo\iTop\Service\Events\EventData $oData
	 *
	 * @return void
	 */
	public function OnEvent(EventData $oData)
	{
		$sEvent = $oData->GetEvent();
		$oObject = $oData->Get('object');
		$sClass = get_class($oObject);
		$iKey = $oObject->GetKey();
		$this->Debug(__METHOD__.": received event '$sEvent' for $sClass::$iKey");
		self::$oTestCase::IncrementCallCount($sEvent);

		if (isset($this->aCallbacks[$sEvent][$sClass])) {
			$aCallBack = $this->aCallbacks[$sEvent][$sClass];
			if ($aCallBack['count'] > 0) {
				$this->aCallbacks[$sEvent][$sClass]['count']--;
				call_user_func($this->aCallbacks[$sEvent][$sClass]['callback'], $oData);
			}
		}
	}

	public function RegisterCRUDEventListeners(?string $sEvent = null, $mEventSource = null)
	{
		$this->Debug('Registering Test event listeners');
		if (is_null($sEvent)) {
			self::$oTestCase->EventService_RegisterListener(EVENT_DB_COMPUTE_VALUES, [$this, 'OnEvent'], $mEventSource);
			self::$oTestCase->EventService_RegisterListener(EVENT_DB_CHECK_TO_WRITE, [$this, 'OnEvent'], $mEventSource);
			self::$oTestCase->EventService_RegisterListener(EVENT_DB_CHECK_TO_DELETE, [$this, 'OnEvent'], $mEventSource);
			self::$oTestCase->EventService_RegisterListener(EVENT_DB_BEFORE_WRITE, [$this, 'OnEvent'], $mEventSource);
			self::$oTestCase->EventService_RegisterListener(EVENT_DB_AFTER_WRITE, [$this, 'OnEvent'], $mEventSource);
			self::$oTestCase->EventService_RegisterListener(EVENT_DB_ABOUT_TO_DELETE, [$this, 'OnEvent'], $mEventSource);
			self::$oTestCase->EventService_RegisterListener(EVENT_DB_AFTER_DELETE, [$this, 'OnEvent'], $mEventSource);
			self::$oTestCase->EventService_RegisterListener(EVENT_DB_LINKS_CHANGED, [$this, 'OnEvent'], $mEventSource);
			self::$oTestCase->EventService_RegisterListener(EVENT_ENUM_TRANSITIONS, [$this, 'OnEvent'], $mEventSource);

			return;
		}
		self::$oTestCase->EventService_RegisterListener($sEvent, [$this, 'OnEvent'], $mEventSource);
	}

	/**
	 * @noinspection PhpUnusedPrivateMethodInspection Used as a callback
	 */
	private function AddRoleToLink(EventData $oData): void
	{
		$this->Debug(__METHOD__);
		$oObject = $oData->Get('object');
		$oContactType = MetaModel::NewObject('ContactType', ['name' => 'test_'.$oObject->GetKey()]);
		$oContactType->DBInsert();
		$oObject->Set('role_id', $oContactType->GetKey());
	}

	/**
	 * @noinspection PhpUnusedPrivateMethodInspection Used as a callback
	 */
	private function SetRandomPersonFunction(EventData $oData): void
	{
		$this->Debug(__METHOD__);
		$oObject = $oData->Get('object');
		$oObject->Set('function', 'CRUD_function_'.rand());
	}

	/**
	 * @noinspection PhpUnusedPrivateMethodInspection Used as a callback
	 */
	private function SetRandomPersonFirstNameStartingWithCRUD(EventData $oData): void
	{
		$this->Debug(__METHOD__);
		$oObject = $oData->Get('object');
		$oObject->Set('first_name', 'CRUD_first_name_'.rand());
	}

	/**
	 * @noinspection PhpUnusedPrivateMethodInspection Used as a callback
	 */
	private function GetObjectAttributesValues(EventData $oData): void
	{
		$this->Debug(__METHOD__);
		$oObject = $oData->Get('object');
		foreach (MetaModel::ListAttributeDefs(get_class($oObject)) as $sAttCode => $oAttDef) {
			if (!$oAttDef->IsLinkSet()) {
				$oObject->Get($sAttCode);
			}
		}
	}

	/**
	 * @noinspection PhpUnusedPrivateMethodInspection Used as a callback
	 */
	private function SetRandomPersonFunctionAndVerifyThatUpdateIsIgnored(EventData $oData): void
	{
		$this->Debug(__METHOD__);
		$oObject = $oData->Get('object');
		$oObject->Set('function', 'CRUD_function_'.rand());
		$oObject->DBUpdate(); // Should be ignored
		if (empty($oObject->ListChanges())) {
			$this->bDBUpdateCalledSuccessfullyDuringEvent = true;
		}
	}

	/**
	 * @noinspection PhpUnusedPrivateMethodInspection Used as a callback
	 */
	private function DenyAllTransitions(EventData $oData): void
	{
		$this->Debug(__METHOD__);
		/** @var \DBObject $oObject */
		$oObject = $oData->Get('object');
		$aAllowedStimuli = $oData->Get('allowed_stimuli');
		// Deny all transitions
		foreach ($aAllowedStimuli as $sStimulus) {
			$this->debug(" * Deny $sStimulus");
			$oObject->DenyTransition($sStimulus);
		}
	}

	/**
	 * @noinspection PhpUnusedPrivateMethodInspection Used as a callback
	 */
	private function DenyAssignTransition(EventData $oData): void
	{
		$this->Debug(__METHOD__);
		/** @var \DBObject $oObject */
		$oObject = $oData->Get('object');
		$oObject->DenyTransition('ev_assign');
	}

	/**
	 * static version of the debug to be accessible from other objects
	 *
	 * @param $sMsg
	 */
	public static function DebugStatic($sMsg)
	{
		get_class(self::$oTestCase)::DebugStatic($sMsg);
	}

	/**
	 * @param $sMsg
	 */
	public function Debug($sMsg)
	{
		get_class(self::$oTestCase)::DebugStatic($sMsg);
	}
}
