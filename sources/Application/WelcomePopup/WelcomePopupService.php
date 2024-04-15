<?php

namespace Combodo\iTop\Application\WelcomePopup;

use AttributeDateTime;
use DBObjectSearch;
use DBObjectSet;
use Exception;
use IssueLog;
use LogChannels;
use MetaModel;
use UserRights;
use WelcomePopupAcknowledge;
use iWelcomePopupExtension;
use utils;

/**
 * Handling of the messages displayed in the "Welcome Popup"
 * @since 3.1.0
 *
 */
class WelcomePopupService
{
	/** @var int TODO: What is it for? */
	private const PROVIDER_KEY_LENGTH = 128;

	/** @var \Combodo\iTop\Application\WelcomePopup\WelcomePopupService|null Singleton instance */
	protected static ?WelcomePopupService $oSingleton = null;

	/** @var string[]|null Acknowledged messages for the current user */
	protected static $aAcknowledgedMessages = null;

	/** @var iWelcomePopup[]|null "Providers" of welcome popup messages */
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
	 * @param string[] $aItem1
	 * @param string[] $aItem2
	 * @return int
	 */
	public static function SortOnImportance($aItem1, $aItem2): int
	{
		if ($aItem1['importance'] === $aItem2['importance']) {
			return strcmp($aItem1['id'], $aItem2['id']);
		}
		return ($aItem1['importance'] < $aItem2['importance'])  ? -1 : 1;
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
	 * Get the list of messages to display in the Welcome popup dialog
	 * @return string[][]
	 */
	public function GetMessages(): array
	{
		$this->LoadProviders();
		return $this->ProcessMessages();
	}
	
	/**
	 * Get the messages to display from a list of {@see \iWelcomePopupExtension} instances
	 * The messages are ordered by importance (CRITICAL first) then by ID
	 * Invalid messages or acknowledged messages are removed from the list
	 *
	 * @return array
	 */
	protected function ProcessMessages(): array
	{
		$this->LoadProviders();
		$aMessages = [];
		foreach($this->aMessagesProviders as $oProvider) {
			$aProviderMessages = $oProvider->GetMessages();
			if (count($aProviderMessages) === 0) {
				IssueLog::Debug('Empty list of messages for '.get_class($oProvider), LogChannels::CONSOLE);
				continue;
			}

			foreach($aProviderMessages as $aMessage) {
				$aReasons = [];
				if (!$this->IsMessageValid($aMessage, $aReasons)) {
					IssueLog::Error('Invalid structure returned by '.get_class($oProvider).'::GetMessages()', LogChannels::CONSOLE, $aReasons);
					continue; // Fail silently
				}
				$sUUID = $this->MakeStringFitIn(get_class($oProvider), static::PROVIDER_KEY_LENGTH).'::'.$aMessage['id'];
				$aMessage['uuid'] = $sUUID;
				$aMessages[] = $aMessage;
			}
		}
		// Filter the acknowledged messages AFTER getting all messages
		// This allows for "replacing" a message (from another provider for example)
		// by automatically acknowledging it when called in GetMessages()
		foreach($aMessages as $key => $aMessage) {
			if ($this->IsMessageAcknowledged($aMessage['uuid'])) {
				IssueLog::Debug('Ignoring already acknowledged message '.$aMessage['uuid'], LogChannels::CONSOLE);
				unset($aMessages[$key]);
			}
		}
		usort($aMessages,  array(get_class($this), 'SortOnImportance'));
		return $aMessages;
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
		$aProviderClasses = utils::GetClassesForInterface(iWelcomePopupExtension::class, '', array('[\\\\/]lib[\\\\/]', '[\\\\/]node_modules[\\\\/]', '[\\\\/]test[\\\\/]', '[\\\\/]tests[\\\\/]'));
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
			if ($this->MakeStringFitIn(get_class($oProvider), static::PROVIDER_KEY_LENGTH) === $sProviderKey) {
				return $oProvider;
			}
		}
		return null;
	}
	
	/**
	 * Check if the structure of a given message is valid by checking
	 * all its mandatory elements
	 * @param string[] $aMessage
	 * @param string[] $aReasons
	 * @return bool
	 */
	protected function IsMessageValid($aMessage, array &$aReasons): bool
	{
		if (!is_array($aMessage)) {
			$aReasons[] = 'GetMessage() must return an array of arrays.';
			return false; // Stop checking immediately
		}
		$bRet = true;
		foreach(['id', 'importance', 'title'] as $sKey) {
			if (!array_key_exists($sKey, $aMessage)) {
				$aReasons[] = "Field '$sKey' missing from the message structure.";
				$bRet = false;
			}
		}
		if (!array_key_exists('html', $aMessage) && !array_key_exists('twig', $aMessage)) {
			$aReasons[] = "Message structure must contain either a field 'html' or a field 'twig'.";
			$bRet = false;
		}
		return $bRet;
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

