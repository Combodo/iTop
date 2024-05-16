<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service\TemporaryObjects;

use DateTime;
use DBObject;
use Exception;
use ExceptionLog;
use IssueLog;
use LogChannels;
use MetaModel;
use TemporaryObjectDescriptor;
use utils;

/**
 * TemporaryObjectManager.
 *
 * Manager class to perform global temporary objects tasks.
 *
 * @experimental do not use, this feature will be part of a future version
 *
 * @since 3.1
 */
class TemporaryObjectManager
{
	/** @var TemporaryObjectManager|null Singleton */
	static private ?TemporaryObjectManager $oSingletonInstance = null;

	/** @var TemporaryObjectRepository $oTemporaryObjectRepository */
	private TemporaryObjectRepository $oTemporaryObjectRepository;

	/**
	 * GetInstance.
	 *
	 * @return TemporaryObjectManager
	 */
	public static function GetInstance(): TemporaryObjectManager
	{
		if (is_null(self::$oSingletonInstance)) {
			self::$oSingletonInstance = new TemporaryObjectManager();
		}

		return self::$oSingletonInstance;
	}

	/**
	 * Constructor.
	 *
	 */
	private function __construct()
	{
		// Retrieve service dependencies
		$this->oTemporaryObjectRepository = TemporaryObjectRepository::GetInstance();
	}

	/**
	 * CreateTemporaryObject.
	 *
	 * @param string $sTempId Temporary id context for the temporary object
	 * @param string $sObjectClass Temporary object class
	 * @param string $sObjectKey Temporary object key
	 * @param string $sOperation temporary operation on file TemporaryObjectHelper::OPERATION_CREATE or TemporaryObjectHelper::OPERATION_DELETE
	 *
	 * @return TemporaryObjectDescriptor|null
	 */
	public function CreateTemporaryObject(string $sTempId, string $sObjectClass, string $sObjectKey, string $sOperation): ?TemporaryObjectDescriptor
	{
		$result = $this->oTemporaryObjectRepository->Create($sTempId, $sObjectClass, $sObjectKey, $sOperation);

		// Log
		IssueLog::Debug("TemporaryObjectsManager: Create a temporary object attached to temporary id $sTempId", LogChannels::TEMPORARY_OBJECTS, [
			'temp_id'    => $sTempId,
			'item_class' => $sObjectClass,
			'item_id'    => $sObjectKey,
			'succeeded'  => $result != null,
		]);

		return $result;
	}

	/**
	 * Cancel the ongoing operation (create or delete) on all the temporary objects impacted by this transaction id
	 *
	 * @param string $sTransactionId form transaction id
	 *
	 * @return bool true if success
	 */
	public function CancelAllTemporaryObjects(string $sTransactionId): bool
	{
		try {
			// Get temporary object descriptors
			$oDbObjectSet = $this->oTemporaryObjectRepository->SearchByTempId($sTransactionId, true);

			// Cancel temporary objects...
			$bResult = $this->CancelTemporaryObjects($oDbObjectSet->ToArray());

			// Log
			IssueLog::Debug("TemporaryObjectsManager: Cancel all temporary objects attached to temporary id $sTransactionId", LogChannels::TEMPORARY_OBJECTS, [
				'temp_id'   => $sTransactionId,
				'succeeded' => $bResult,
			]);

			// return operation success
			return true;
		}
		catch (Exception $e) {
			ExceptionLog::LogException($e);

			return false;
		}

	}

	/**
	 * Cancel the ongoing operation (create or delete) on the given temporary objects
	 *
	 * @param array{\TemporaryObjectDescriptor} $aTemporaryObjectDescriptor
	 *
	 * @return bool true if success
	 */
	private function CancelTemporaryObjects(array $aTemporaryObjectDescriptor): bool
	{
		try {
			// All operations succeeded
			$bResult = true;

			/** @var TemporaryObjectDescriptor $oTemporaryObjectDescriptor */
			foreach ($aTemporaryObjectDescriptor as $oTemporaryObjectDescriptor) {

				// Cancel temporary objects
				if (!$this->CancelTemporaryObject($oTemporaryObjectDescriptor)) {
					$bResult = false;
				}
			}

			return $bResult;
		}
		catch (Exception $e) {
			ExceptionLog::LogException($e);

			return false;
		}

	}


	/**
	 * Extends the temporary object descriptor lifetime
	 *
	 * @param string $sTransactionId
	 *
	 * @return bool
	 */
	public function ExtendTemporaryObjectsLifetime(string $sTransactionId): bool
	{
		try {
			// Create db set from db search
			$oDbObjectSet = $this->oTemporaryObjectRepository->SearchByTempId($sTransactionId);

			// Expiration date
			$iExpirationDate = time() + TemporaryObjectConfig::GetInstance()->GetConfigTemporaryLifetime();

			// Delay objects expiration
			while ($oObject = $oDbObjectSet->Fetch()) {
				$oObject->Set('expiration_date', $iExpirationDate);
				$oObject->DBUpdate();
			}

			// Log
			$date = new DateTime();
			$date->setTimestamp($iExpirationDate);
			IssueLog::Debug("TemporaryObjectsManager: Delay all temporary objects descriptors expiration date attached to temporary id $sTransactionId", LogChannels::TEMPORARY_OBJECTS, [
				'temp_id'                 => $sTransactionId,
				'expiration_date'         => date_format($date, 'Y-m-d H:i:s'),
				'total_temporary_objects' => $this->oTemporaryObjectRepository->CountTemporaryObjectsByTempId($sTransactionId),
			]);

			// return operation success
			return true;
		}
		catch (Exception $e) {
			ExceptionLog::LogException($e);

			return false;
		}
	}

	/**
	 * Accept all the temporary objects operations
	 *
	 * @param string $sTransactionId
	 *
	 * @return void
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	private function FinalizeTemporaryObjects(string $sTransactionId)
	{
		// All operations succeeded
		$bResult = true;

		// Get temporary object descriptors
		$oDBObjectSet = $this->oTemporaryObjectRepository->SearchByTempId($sTransactionId, true);

		// Iterate throw descriptors...
		/** @var TemporaryObjectDescriptor $oTemporaryObjectDescriptor */
		while ($oTemporaryObjectDescriptor = $oDBObjectSet->Fetch()) {
			// Retrieve attributes values
			$sHostClass = $oTemporaryObjectDescriptor->Get('host_class');
			$sHostId = $oTemporaryObjectDescriptor->Get('host_id');

			// No host object
			if ($sHostId === 0) {
				$bResult = $bResult && $this->CancelTemporaryObject($oTemporaryObjectDescriptor);
				continue;
			}

			// Host object pointed by descriptor doesn't exist anymore
			$oHostObject = MetaModel::GetObject($sHostClass, $sHostId, false);
			if (is_null($oHostObject)) {
				$bResult = $bResult && $this->CancelTemporaryObject($oTemporaryObjectDescriptor);
				continue;
			}

			// Otherwise confirm
			$bResult = $bResult && $this->ConfirmTemporaryObject($oTemporaryObjectDescriptor);
		}

		// Log
		IssueLog::Debug("TemporaryObjectsManager: Finalize all temporary objects attached to temporary id $sTransactionId", LogChannels::TEMPORARY_OBJECTS, [
			'temp_id'   => $sTransactionId,
			'succeeded' => $bResult,
		]);

	}

	/**
	 * Accept operation on the given temporary object
	 *
	 * @param TemporaryObjectDescriptor $oTemporaryObjectDescriptor
	 *
	 * @return bool
	 */
	private function ConfirmTemporaryObject(TemporaryObjectDescriptor $oTemporaryObjectDescriptor): bool
	{
		try {
			// Retrieve attributes values
			$sOperation = $oTemporaryObjectDescriptor->Get('operation');
			$sItemClass = $oTemporaryObjectDescriptor->Get('item_class');
			$sItemId = $oTemporaryObjectDescriptor->Get('item_id');

			// Get temporary object
			$oTemporaryObject = MetaModel::GetObject($sItemClass, $sItemId);

			if ($sOperation === TemporaryObjectHelper::OPERATION_DELETE) {
				// Delete temporary object
				$oTemporaryObject->DBDelete();
				IssueLog::Info("Temporary Object [$sItemClass:$sItemId] removed (operation: $sOperation)", LogChannels::TEMPORARY_OBJECTS, utils::GetStackTraceAsArray());
			} elseif ($sOperation === TemporaryObjectHelper::OPERATION_CREATE) {
				// Send an event in case of creation confirmation
				$oTemporaryObject->FireEvent(TemporaryObjectsEvents::TEMPORARY_OBJECT_EVENT_CONFIRM_CREATE);
			}

			// Remove temporary object descriptor entry
			$oTemporaryObjectDescriptor->DBDelete();

			// Log
			IssueLog::Debug("Temporary Object [$sItemClass:$sItemId] $sOperation confirmed", LogChannels::TEMPORARY_OBJECTS, utils::GetStackTraceAsArray());

			return true;
		}
		catch (Exception $e) {
			ExceptionLog::LogException($e);

			return false;
		}
	}

	/**
	 * CancelTemporaryObject.
	 *
	 * @param TemporaryObjectDescriptor $oTemporaryObjectDescriptor
	 *
	 * @return bool
	 */
	private function CancelTemporaryObject(TemporaryObjectDescriptor $oTemporaryObjectDescriptor): bool
	{
		try {
			// Retrieve attributes values
			$sOperation = $oTemporaryObjectDescriptor->Get('operation');
			$sItemClass = $oTemporaryObjectDescriptor->Get('item_class');
			$sItemId = $oTemporaryObjectDescriptor->Get('item_id');

			if ($sOperation === TemporaryObjectHelper::OPERATION_CREATE) {

				// Get temporary object
				$oTemporaryObject = MetaModel::GetObject($sItemClass, $sItemId, false);

				// Delete temporary object
				if (!is_null($oTemporaryObject)) {
					$oTemporaryObject->DBDelete();
				}

				IssueLog::Info("Temporary Object [$sItemClass:$sItemId] removed (operation: $sOperation)", LogChannels::TEMPORARY_OBJECTS, utils::GetStackTraceAsArray());
			}

			// Remove temporary object descriptor entry
			$oTemporaryObjectDescriptor->DBDelete();

			return true;
		}
		catch (Exception $e) {
			ExceptionLog::LogException($e);

			return false;
		}
	}

	/**
	 * Handle temporary objects.
	 *
	 * @param \DBObject $oDBObject
	 * @param array $aContext
	 *
	 * @return void
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	public function HandleTemporaryObjects(DBObject $oDBObject, array $aContext)
	{
		if (array_key_exists('create', $aContext)) {
			// Retrieve context information
			$aContextCreation = $aContext['create'];
			$sTransactionId = $aContextCreation['transaction_id'] ?? null;
			$sHostClass = $aContextCreation['host_class'] ?? null;
			$sHostAttCode = $aContextCreation['host_att_code'] ?? null;

			// Security
			if (is_null($sTransactionId) || is_null($sHostClass) || is_null($sHostAttCode)) {
				return;
			}

			// Get host class attribute definition
			try {
				$oAttDef = MetaModel::GetAttributeDef($sHostClass, $sHostAttCode);
			}
			catch (Exception $e) {
				ExceptionLog::LogException($e);

				return;
			}

			// If creation as temporary object requested or force for all objects
			if (($oAttDef->IsParam('create_temporary_object') && $oAttDef->Get('create_temporary_object'))
				|| TemporaryObjectConfig::GetInstance()->GetConfigTemporaryForce()) {

				$this->CreateTemporaryObject($sTransactionId, get_class($oDBObject), $oDBObject->GetKey(), TemporaryObjectHelper::OPERATION_CREATE);
			}
		}
		if (array_key_exists('finalize', $aContext)) {
			// Retrieve context information
			$aContextFinalize = $aContext['finalize'];
			$sTransactionId = $aContextFinalize['transaction_id'] ?? null;

			// validate temporary objects
			$this->FinalizeTemporaryObjects($sTransactionId);
		}
	}

	/**
	 * GarbageExpiredTemporaryObjects.
	 *
	 * @return bool
	 */
	public function GarbageExpiredTemporaryObjects(): bool
	{
		try {
			// Search for expired temporary objects
			$oDBObjectSet = $this->oTemporaryObjectRepository->SearchByExpired();

			// Cancel temporary objects
			$this->CancelTemporaryObjects($oDBObjectSet->ToArray());

			return true;
		}
		catch (Exception $e) {
			ExceptionLog::LogException($e);

			return false;
		}
	}
}
