<?php

/**
 * Copyright (C) 2010-2024 Combodo SAS
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License
 * as published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

namespace Combodo\iTop\Service\CaseLog;

use Combodo\iTop\Application\EventRegister\ApplicationEvents;
use Combodo\iTop\Core\AttributeDefinition\AttributeCaseLog;
use DBObject;
use DBObjectSet;
use Dict;
use InlineImage;
use MetaModel;
use ormCaseLog;
use UserRights;

/**
 * Authorizes and applies edits to existing CaseLog entries.
 *
 * @since 3.3.0 N°1365 - Move CaseLog edition into iTop core
 */
class CaseLogEditionService
{
	/**
	 * @var self|null
	 * @since 3.3.0 N°1365 - Provide the core CaseLog editing service
	 */
	private static $oInstance;

	/**
	 * @since 3.3.0 N°1365 - Provide a singleton CaseLog editing service
	 */
	private function __construct()
	{
	}

	/**
	 * @return self
	 * @since 3.3.0 N°1365 - Provide a singleton CaseLog editing service
	 */
	public static function GetInstance(): self
	{
		if (!isset(static::$oInstance)) {
			static::$oInstance = new static();
		}

		return static::$oInstance;
	}

	/**
	 * Check whether the current user can edit the specified CaseLog entry.
	 *
	 * @param \DBObject $oObject
	 * @param string $sAttCode
	 * @param int $iEntryIndex Zero-based internal CaseLog index, oldest entry first
	 *
	 * @return bool
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @since 3.3.0 N°1365 - Check whether a CaseLog entry can be edited
	 */
	public function CanEditEntry(DBObject $oObject, string $sAttCode, int $iEntryIndex): bool
	{
		$oConfig = MetaModel::GetConfig();
		if (false === (bool)$oConfig->Get('caselog_edition.enabled')) {
			return false;
		}

		$iUserId = (int)UserRights::GetUserId();
		if (($iUserId <= 0) || $oObject->IsNew()) {
			return false;
		}

		$sObjectClass = get_class($oObject);
		if (false === MetaModel::IsValidAttCode($sObjectClass, $sAttCode)) {
			return false;
		}

		$oAttDef = MetaModel::GetAttributeDef($sObjectClass, $sAttCode);
		if (false === ($oAttDef instanceof AttributeCaseLog)) {
			return false;
		}

		if (false === $this->IsTargetAllowed($sObjectClass, $sAttCode, $oConfig->Get('caselog_edition.allowed_classes'), $oConfig->Get('caselog_edition.allowed_case_logs'))) {
			return false;
		}

		if (false === $this->HasAllowedProfile($oConfig->Get('caselog_edition.allowed_profiles'))) {
			return false;
		}

		if (($oObject->GetAttributeFlags($sAttCode) & (OPT_ATT_READONLY | OPT_ATT_HIDDEN)) !== 0) {
			return false;
		}

		$oObjectSet = DBObjectSet::FromObject($oObject);
		if (UR_ALLOWED_YES !== UserRights::IsActionAllowed($sObjectClass, UR_ACTION_MODIFY, $oObjectSet)) {
			return false;
		}
		if (UR_ALLOWED_YES !== UserRights::IsActionAllowedOnAttribute($sObjectClass, $sAttCode, UR_ACTION_MODIFY, $oObjectSet)) {
			return false;
		}

		$oCaseLog = $oObject->Get($sAttCode);
		if (false === ($oCaseLog instanceof ormCaseLog) || ($iEntryIndex < 0) || ($oCaseLog->GetEntryCount() === 0)) {
			return false;
		}

		$iArrayIndex = $oCaseLog->GetLatestEntryIndex() - $iEntryIndex;
		$aEntries = $oCaseLog->GetAsArray();
		if (!isset($aEntries[$iArrayIndex])) {
			return false;
		}

		$aEntry = $aEntries[$iArrayIndex];
		if ((int)($aEntry['user_id'] ?? 0) !== $iUserId) {
			return false;
		}

		$iModificationDelayHours = (int)$oConfig->Get('caselog_edition.modification_delay');
		if ($iModificationDelayHours < 0) {
			return false;
		}
		if ($iModificationDelayHours > 0) {
			$iEntryTimestamp = strtotime($aEntry['date']);
			if (($iEntryTimestamp === false) || ((time() - $iEntryTimestamp) > ($iModificationDelayHours * 3600))) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Sanitize and save the edited CaseLog entry, keeping its original author and date.
	 *
	 * @param \DBObject $oObject
	 * @param string $sAttCode
	 * @param int $iEntryIndex Zero-based internal CaseLog index, oldest entry first
	 * @param string $sNewHtml
	 *
	 * @return array{message_html: string, changed: bool}
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \ArchivedObjectException
	 * @throws \MySQLException
	 * @throws \OQLException
	 * @since 3.3.0 N°1365 - Edit a CaseLog entry and record the native change history
	 */
	public function EditEntry(DBObject $oObject, string $sAttCode, int $iEntryIndex, string $sNewHtml): array
	{
		if (false === $this->CanEditEntry($oObject, $sAttCode, $iEntryIndex)) {
			throw new \CoreException(Dict::S('UI:CaseLog:Edition:ErrorNotAllowed'));
		}
		if (trim($sNewHtml) === 'undefined') {
			throw new \CoreException(Dict::S('UI:CaseLog:Edition:ErrorRequest'));
		}

		$oCaseLog = $oObject->Get($sAttCode);
		$iArrayIndex = $oCaseLog->GetLatestEntryIndex() - $iEntryIndex;
		$aEntries = $oCaseLog->GetAsArray();
		$sPreviousHtml = $aEntries[$iArrayIndex]['message_html'];
		$oEditedCaseLog = clone $oCaseLog;
		$sSanitizedHtml = $oEditedCaseLog->UpdateEntryAt($iEntryIndex, $sNewHtml);
		if ($sPreviousHtml === $sSanitizedHtml) {
			return [
				'message_html' => $sSanitizedHtml,
				'changed' => false,
			];
		}

		$oObject->Set($sAttCode, $oEditedCaseLog);
		InlineImage::FinalizeInlineImages($oObject);
		$oObject->DBUpdate();
		$oObject->FireEvent(ApplicationEvents::APPLICATION_EVENT_CASELOG_ENTRY_EDITED, [
			'attribute_code' => $sAttCode,
			'entry_index' => $iEntryIndex,
		]);

		return [
			'message_html' => $sSanitizedHtml,
			'changed' => true,
		];
	}

	/**
	 * Check the configured class and CaseLog attribute allow-lists.
	 *
	 * @param string $sObjectClass
	 * @param string $sAttCode
	 * @param mixed $aAllowedClasses
	 * @param mixed $aAllowedCaseLogs
	 *
	 * @return bool
	 * @since 3.3.0 N°1365 - Apply CaseLog editing class and attribute allow-lists
	 */
	private function IsTargetAllowed(string $sObjectClass, string $sAttCode, $aAllowedClasses, $aAllowedCaseLogs): bool
	{
		if ((false === is_array($aAllowedClasses) && false === is_string($aAllowedClasses))
			|| (false === is_array($aAllowedCaseLogs) && false === is_string($aAllowedCaseLogs) && $aAllowedCaseLogs !== null)) {
			return false;
		}

		$aAllowedClasses = $this->NormalizeList($aAllowedClasses);
		if (($aAllowedClasses !== []) && (false === in_array($sObjectClass, $aAllowedClasses, true))) {
			return false;
		}
		if (($aAllowedCaseLogs === []) || ($aAllowedCaseLogs === null) || ($aAllowedCaseLogs === '')) {
			return true;
		}
		if (is_string($aAllowedCaseLogs)) {
			return in_array($sObjectClass.'::'.$sAttCode, $this->NormalizeList($aAllowedCaseLogs), true);
		}

		if (array_key_exists($sObjectClass, $aAllowedCaseLogs)) {
			$aAllowedAttributes = $this->NormalizeList($aAllowedCaseLogs[$sObjectClass]);
			return ($aAllowedAttributes === []) || in_array($sAttCode, $aAllowedAttributes, true);
		}

		foreach ($aAllowedCaseLogs as $sAllowedCaseLog) {
			if (is_string($sAllowedCaseLog) && ($sAllowedCaseLog === $sObjectClass.'::'.$sAttCode)) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Check whether the current user has at least one profile allowed by configuration.
	 *
	 * @param mixed $aAllowedProfiles
	 *
	 * @return bool
	 * @since 3.3.0 N°1365 - Apply the CaseLog editing profile allow-list
	 */
	private function HasAllowedProfile($aAllowedProfiles): bool
	{
		if ((false === is_array($aAllowedProfiles)) && (false === is_string($aAllowedProfiles))) {
			return false;
		}

		$aAllowedProfiles = $this->NormalizeList($aAllowedProfiles);
		$aCurrentProfiles = UserRights::ListProfiles();
		if ($aCurrentProfiles === []) {
			return false;
		}
		if ($aAllowedProfiles === []) {
			return true;
		}

		foreach ($aAllowedProfiles as $sAllowedProfile) {
			if (is_string($sAllowedProfile) && UserRights::HasProfile($sAllowedProfile)) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Normalize a configuration list represented as an array or a delimited string.
	 *
	 * @param mixed $mValues
	 *
	 * @return string[]
	 * @since 3.3.0 N°1365 - Normalize CaseLog editing allow-lists
	 */
	private function NormalizeList($mValues): array
	{
		if (is_string($mValues)) {
			$mValues = preg_split('/[\s,;]+/', $mValues, -1, PREG_SPLIT_NO_EMPTY);
		}
		if (false === is_array($mValues)) {
			return [];
		}

		$aValues = [];
		foreach ($mValues as $mValue) {
			if (is_scalar($mValue)) {
				$sValue = (string)$mValue;
				if (($sValue !== '') && (false === in_array($sValue, $aValues, true))) {
					$aValues[] = $sValue;
				}
			}
		}

		return $aValues;
	}
}
