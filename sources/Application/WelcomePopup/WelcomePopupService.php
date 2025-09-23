<?php

namespace Combodo\iTop\Application\WelcomePopup;

use AttributeDateTime;
use Combodo\iTop\Service\InterfaceDiscovery\InterfaceDiscovery;
use DBObjectSearch;
use DBObjectSet;
use Exception;
use IssueLog;
use LogChannels;
use MetaModel;
use UserRights;
use WelcomePopupAcknowledge;
use iWelcomePopupExtension;

/**
 * Handling of the messages displayed in the "Welcome Popup"
 * @since 3.1.0
 *
 */
class WelcomePopupService
{
	private const PROVIDER_KEY_LENGTH = 128;

	/** @var \Combodo\iTop\Application\WelcomePopup\WelcomePopupService|null Singleton instance */
	protected static ?WelcomePopupService $oSingleton = null;

	/** @var \Combodo\iTop\Application\WelcomePopup\Message[]|null Acknowledged messages for the current user */
	protected static $aAcknowledgedMessages = null;

	/** @var \iWelcomePopupExtension[]|null "Providers" of welcome popup messages */
	protected $aMessagesProviders = null;

	/**
	 * @internal
	 * @return $this The singleton instance of the service
	 */
	public static function GetInstance(): WelcomePopupService
	{
		if (null === static::$oSingleton) {
			static::$oSingleton = new static();
		}

		return static::$oSingleton;
	}

	/**
	 * Helper function for usort to compare two items based on their 'importance' field
	 *
	 * @param array $aProviderMessageData1
	 * @param array $aProviderMessageData2
	 *
	 * @return int
	 */
	public static function SortOnImportance(array $aProviderMessageData1, array $aProviderMessageData2): int
	{
		if ($aProviderMessageData1['message']->GetImportance() === $aProviderMessageData2['message']->GetImportance()) {
			return strcmp($aProviderMessageData1['message']->GetID(), $aProviderMessageData2['message']->GetID());
		}
		return ($aProviderMessageData1['message']->GetImportance() < $aProviderMessageData2['message']->GetImportance())  ? -1 : 1;
	}

	/**********************/
	/* Non-static methods */
	/**********************/

	/**
	 * Singleton pattern, can't use the constructor. Use {@see \Combodo\iTop\Application\WelcomePopup\WelcomePopupService::GetInstance()} instead.
	 *
	 * @return void
	 */
	protected function __construct()
	{
		// Don't do anything, we don't want to be initialized
	}

	/**
	 * Get the list of {@see \Combodo\iTop\Application\WelcomePopup\Message} to display in the Welcome popup dialog
	 * @return \Combodo\iTop\Application\WelcomePopup\Message[]
	 */
	public function GetMessages(): array
	{
		$this->LoadProviders();
		return $this->ProcessMessages();
	}
	
	/**
	 * Get the {@see \Combodo\iTop\Application\WelcomePopup\Message} to display from a list of {@see \iWelcomePopupExtension} instances
	 * The messages are ordered by importance ({@see \iWelcomePopupExtension::ENUM_IMPORTANCE_CRITICAL} first) then by ID
	 * Invalid messages or acknowledged messages are removed from the list
	 *
	 * @return array
	 */
	protected function ProcessMessages(): array
	{
		$this->LoadProviders();
		/** @var array $aAllProvidersMessagesData */
		$aAllProvidersMessagesData = [];
		foreach($this->aMessagesProviders as $oProvider) {
			$aProviderMessages = $oProvider->GetMessages();
			if (count($aProviderMessages) === 0) {
				IssueLog::Debug('Empty list of messages for '.$oProvider::class, LogChannels::CONSOLE);
				continue;
			}

			$sProviderIconRelPath = $oProvider->GetIconRelPath();
			foreach($aProviderMessages as $oMessage) {
				if (false === ($oMessage instanceof Message)) {
					IssueLog::Error('Invalid message returned by iWelcomePopupExtension::GetMessages(), must be of class ' . Message::class, LogChannels::CONSOLE, [
						'provider_class' => $oProvider::class,
						'message' => $oMessage,
					]);
					continue; // Fail silently
				}

				$aAllProvidersMessagesData[] = [
					'uuid' => $this->MakeStringFitIn($oProvider::class, static::PROVIDER_KEY_LENGTH).'::'.$oMessage->GetID(),
					'message' => $oMessage,
					'provider_icon_rel_path' => $sProviderIconRelPath,
				];
			}
		}
		// Filter the acknowledged messages AFTER getting all messages
		// This allows for "replacing" a message (from another provider for example)
		// by automatically acknowledging it when called in GetMessages()
		foreach($aAllProvidersMessagesData as $key => $aProviderMessageData) {
			if ($this->IsMessageAcknowledged($aProviderMessageData['uuid'])) {
				IssueLog::Debug('Ignoring already acknowledged message '.$aProviderMessageData['uuid'], LogChannels::CONSOLE);
				unset($aAllProvidersMessagesData[$key]);
			}
		}
		usort($aAllProvidersMessagesData,  [static::class, 'SortOnImportance']);
		return $aAllProvidersMessagesData;
	}

	/**
	 * Acknowledge a message (from a specific provider) for the current user, then notifies the provider (in case it wants to do some extra processing)
	 *
	 * @param string $sMessageUUID Format <PROVIDER_FQCN>::<MESSAGE_ID>
	 *
	 * @return void
	 * @throws \CoreException
	 */
	public function AcknowledgeMessage(string $sMessageUUID): void
	{
		$this->LoadProviders();
		$oAcknowledge = MetaModel::NewObject(WelcomePopupAcknowledge::class, [
			'message_uuid' => $sMessageUUID,
			'acknowledge_date' => date(AttributeDateTime::GetSQLFormat()),
			'user_id' => UserRights::GetConnectedUserId(),
		]);
		try {
			$oAcknowledge->DBInsert();
			$oProvider = $this->GetProviderByUUID($sMessageUUID);
			if (static::$aAcknowledgedMessages !== null) {
				static::$aAcknowledgedMessages[] = $sMessageUUID; // Update the cache
			}

			// Notify the provider of the message
			$sMessageId = substr($sMessageUUID, strpos($sMessageUUID, '::') + 2);
			if ($oProvider !== null) {
				$oProvider->AcknowledgeMessage($sMessageId);
			}
		} catch(Exception $e) {
			IssueLog::Error("Failed to acknowledge the message $sMessageUUID for user ".UserRights::GetConnectedUserId().". Reason: ".$e->getMessage(), LogChannels::CONSOLE);
		}
	}
	
	/**
	 * Load the provider of messages, decoupled from the constructor for testability
	 */
	protected function LoadProviders(): void
	{
		if ($this->aMessagesProviders !== null) return;

		$aProviders = [];
		$aProviderClasses = InterfaceDiscovery::GetInstance()->FindItopClasses(iWelcomePopupExtension::class);
		foreach($aProviderClasses as $sProviderClass) {
			$aProviders[] = new $sProviderClass();
		}
		$this->SetMessagesProviders($aProviders);
	}

	/**
	 * Check if a given message was acknowledged by the current user
	 * @param string $sMessageId
	 * @return bool
	 */
	protected function IsMessageAcknowledged(string $sMessageUUID): bool
	{
		$iUserId = UserRights::GetConnectedUserId();	
		if (static::$aAcknowledgedMessages === null) {
			
			$oSearch = new DBObjectSearch(WelcomePopupAcknowledge::class);
			$oSearch->AddCondition('user_id', $iUserId);
			$oSet = new DBObjectSet($oSearch);
			$aAcknowledgedMessages = $oSet->GetColumnAsArray('message_uuid');
			$this->SetAcknowledgedMessagesCache($aAcknowledgedMessages);
		}
		return in_array($sMessageUUID, static::$aAcknowledgedMessages);
	}
	
	/**
	 * Set the cache of acknowledged messages (useful for testing)
	 * @param array $aAcknowledgedMessages
	 */
	protected function SetAcknowledgedMessagesCache(array $aAcknowledgedMessages): void
	{
		static::$aAcknowledgedMessages = $aAcknowledgedMessages;
	}

	/**
	 * Set the cache of welcome popup message providers (useful for testing)
	 * @param iWelcomePopupExtension[] $aMessagesProviders
	 */
	protected function SetMessagesProviders(array $aMessagesProviders): void
	{
		$this->aMessagesProviders = $aMessagesProviders;
	}
	
	/**
	 * Retrieve the provider associated with a message
	 * @param string $sMessageUUID
	 * @return iWelcomePopupExtension|NULL
	 */
	protected function GetProviderByUUID(string $sMessageUUID): ?iWelcomePopupExtension
	{
		$this->LoadProviders();
		$sProviderKey = substr($sMessageUUID, 0, strpos($sMessageUUID, '::'));
		foreach($this->aMessagesProviders as $oProvider) {
			if ($this->MakeStringFitIn($oProvider::class, static::PROVIDER_KEY_LENGTH) === $sProviderKey) {
				return $oProvider;
			}
		}
		return null;
	}
	
	/**
	 * Shorten the given string (if needed) but preserving its uniqueness
	 * @param string $sProviderClass
	 * @param int $iLengthLimit
	 * @return string
	 */
	protected function MakeStringFitIn(string $sProviderClass, int $iLengthLimit): string
	{
		if(mb_strlen($sProviderClass) <= $iLengthLimit) {
			return $sProviderClass;
		}
		// Truncate the string to $iLimitLength and replace the first carahcters with the MD5 of the complete string 
		$sMD5 = md5($sProviderClass, false);
		return $sMD5.'-'.mb_substr($sProviderClass, -($iLengthLimit - strlen($sMD5) - 1)); // strlen is OK on the MD5 string, and '-' is not allowed in a class name
	}
}

