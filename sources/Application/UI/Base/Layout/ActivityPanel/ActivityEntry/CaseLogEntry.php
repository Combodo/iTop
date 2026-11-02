<?php

/**
 * Copyright (C) 2013-2024 Combodo SAS
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

namespace Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry;

use DateTime;
use utils;

/**
 * Class CaseLogEntry
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry
 * @internal
 * @since 3.0.0
 */
class CaseLogEntry extends ActivityEntry
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-caselog-entry';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/layouts/activity-panel/activity-entry/caselog-entry';

	public const DEFAULT_TYPE = 'caselog';
	public const DEFAULT_DECORATION_CLASSES = 'fas fa-fw fa-quote-left';

	// Specific constants
	public const DEFAULT_CASELOG_RANK = 0;

	/** @var string $sAttCode Code of the corresponding case log attribute */
	protected $sAttCode;
	/** @var int $iCaseLogRank Rank of its case log in the host panel, can be used for highlight purposes for example */
	protected $iCaseLogRank;
	/** @var string $sAuthorName Fallback name used if $sAuthorLogin is empty */
	protected $sAuthorName;
	/**
	 * @var int|null Internal index of the CaseLog entry
	 * @since 3.3.0 N°1365 - Carry the internal index into the Activity panel
	 */
	protected $iEntryIndex;
	/**
	 * @var bool Whether the current user can edit this entry
	 * @since 3.3.0 N°1365 - Track whether the edit action is authorized
	 */
	protected $bCanEditEntry;
	/**
	 * @var array<int, CaseLogEditEntry> Edit history, newest first
	 * @since 3.3.0 N°1365 - Expose the CaseLog edit history
	 */
	protected $aModificationHistory;

	/**
	 * CaseLogEntry constructor.
	 *
	 * @param \DateTime $oDateTime
	 * @param string $sAuthorLogin
	 * @param string $sAttCode
	 * @param string $sContent
	 * @param string $sAuthorName
	 * @param string|null $sId
	 *
	 * @throws \OQLException
	 * @since 3.3.0 N°1365 - Initialize CaseLog edit metadata
	 */
	public function __construct(DateTime $oDateTime, string $sAuthorLogin, string $sAttCode, string $sContent, string $sAuthorName, ?string $sId = null)
	{
		$this->sAuthorName = $sAuthorName;
		parent::__construct($oDateTime, $sAuthorLogin, $sContent, $sId);

		$this->sAttCode = $sAttCode;
		$this->SetCaseLogRank(static::DEFAULT_CASELOG_RANK);
		$this->iEntryIndex = null;
		$this->bCanEditEntry = false;
		$this->aModificationHistory = [];
	}

	/*
	* Set the author and its information based on the $sAuthorLogin using parent call
	* If no parent call found no matching User, fallback on caselog author name and display it as foreign message
	*
	* @param string $sAuthorLogin
	*
	* @return $this
	* @throws \OQLException
	* @throws \Exception
	*/
	public function SetAuthor(string $sAuthorLogin)
	{
		parent::SetAuthor($sAuthorLogin);

		// If no User was found in parent call
		if ($this->sAuthorLogin === '') {
			// Use caselog user_login info as friendlyname and compute its initials
			$this->sAuthorFriendlyname = $this->sAuthorName;
			$this->sAuthorInitials = utils::ToAcronym($this->sAuthorFriendlyname);

			// Reset Picture as we probably have default image
			$this->sAuthorPictureAbsUrl = null;
			// Reset bIsFromCurrentUser as UserRights often consider '' login as current user login
			$this->bIsFromCurrentUser = null;
		}
		return $this;
	}

	/**
	 * Return the code of the corresponding case log attribute
	 *
	 * @return string
	 */
	public function GetAttCode(): string
	{
		return $this->sAttCode;
	}

	/**
	 * Set the internal CaseLog entry index.
	 *
	 * @param int $iEntryIndex
	 *
	 * @return $this
	 * @since 3.3.0 N°1365 - Expose the indexed CaseLog entry to the activity panel
	 */
	public function SetEntryIndex(int $iEntryIndex)
	{
		$this->iEntryIndex = $iEntryIndex;

		return $this;
	}

	/**
	 * Return the internal CaseLog entry index.
	 *
	 * @return int|null
	 * @since 3.3.0 N°1365 - Expose the indexed CaseLog entry to the activity panel
	 */
	public function GetEntryIndex(): ?int
	{
		return $this->iEntryIndex;
	}

	/**
	 * Set whether the current user can edit this entry.
	 *
	 * @param bool $bCanEditEntry
	 *
	 * @return $this
	 * @since 3.3.0 N°1365 - Display the CaseLog edition action only when authorized
	 */
	public function SetCanEditEntry(bool $bCanEditEntry)
	{
		$this->bCanEditEntry = $bCanEditEntry;

		return $this;
	}

	/**
	 * Return whether the current user can edit this entry.
	 *
	 * @return bool
	 * @since 3.3.0 N°1365 - Display the CaseLog edition action only when authorized
	 */
	public function CanEditEntry(): bool
	{
		return $this->bCanEditEntry;
	}

	/**
	 * Set the edit history for this entry.
	 *
	 * @param array<int, CaseLogEditEntry> $aModificationHistory
	 *
	 * @return $this
	 * @since 3.3.0 N°1365 - Expose the CaseLog edit history
	 */
	public function SetModificationHistory(array $aModificationHistory)
	{
		$this->aModificationHistory = $aModificationHistory;

		return $this;
	}

	/**
	 * Return whether this entry has any edit history.
	 *
	 * @return bool
	 * @since 3.3.0 N°1365 - Display the CaseLog edit history
	 */
	public function HasModificationHistory(): bool
	{
		return $this->aModificationHistory !== [];
	}

	/**
	 * Return the edit history, newest first.
	 *
	 * @return array<int, CaseLogEditEntry>
	 * @since 3.3.0 N°1365 - Display the CaseLog edit history
	 */
	public function GetModificationHistory(): array
	{
		return $this->aModificationHistory;
	}

	/**
	 * Return the formatted date of the latest edit.
	 *
	 * @return string
	 * @throws \Exception
	 * @since 3.3.0 N°1365 - Display the latest CaseLog edit date
	 */
	public function GetFormattedLastModificationDateTime(): string
	{
		if (false === $this->HasModificationHistory()) {
			return '';
		}

		return $this->aModificationHistory[0]->GetFormattedDateTime();
	}

	/**
	 * Set the rank of the case log in the host panel
	 *
	 * @param int $iCaseLogRank
	 *
	 * @return $this
	 */
	public function SetCaseLogRank(int $iCaseLogRank)
	{
		$this->iCaseLogRank = $iCaseLogRank;

		return $this;
	}

	/**
	 * Return the rank of the case log in the host panel
	 *
	 * @return int
	 */
	public function GetCaseLogRank(): int
	{
		return $this->iCaseLogRank;
	}
}
