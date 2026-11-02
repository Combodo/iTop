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

use Combodo\iTop\Application\Helper\HtmlDiffHelper;
use DateTime;
use Dict;

/**
 * History record describing an edit of an existing CaseLog entry.
 *
 * @since 3.3.0 N°1365 - Display inline CaseLog edit history
 */
class CaseLogEditEntry extends ActivityEntry
{
	/** @since 3.3.0 N°1365 - Classify CaseLog edit history entries */
	public const DEFAULT_TYPE = 'edits';
	/** @since 3.3.0 N°1365 - Decorate CaseLog edit history entries */
	public const DEFAULT_DECORATION_CLASSES = 'fas fa-fw fa-pen';

	/**
	 * @var string
	 * @since 3.3.0 N°1365 - Identify the edited CaseLog attribute
	 */
	protected $sAttCode;
	/**
	 * @var int Internal index of the edited CaseLog entry
	 * @since 3.3.0 N°1365 - Identify the edited CaseLog entry
	 */
	protected $iEntryIndex;
	/**
	 * @var string|null Previous CaseLog content; null when the historical snapshot predates snapshot storage
	 * @since 3.3.0 N°1365 - Display CaseLog edit versions
	 */
	protected $sPreviousContent;
	/**
	 * @var string|null New CaseLog content; null when the historical snapshot predates snapshot storage
	 * @since 3.3.0 N°1365 - Display CaseLog edit versions
	 */
	protected $sNewContent;
	/**
	 * @var array{previous: string, new: string}|null Cached rendered comparison
	 * @since 3.3.0 N°1365 - Reuse the comparison for both versions
	 */
	protected $aContentComparison;

	/**
	 * @param \DateTime $oDateTime
	 * @param string $sAuthorLogin
	 * @param string $sAttCode
	 * @param int $iEntryIndex
	 * @param int $iChangeOpId
	 * @param string|null $sPreviousContent
	 * @param string|null $sNewContent
	 *
	 * @throws \OQLException
	 * @since 3.3.0 N°1365 - Display CaseLog edit history
	 */
	public function __construct(DateTime $oDateTime, string $sAuthorLogin, string $sAttCode, int $iEntryIndex, int $iChangeOpId, ?string $sPreviousContent = null, ?string $sNewContent = null)
	{
		parent::__construct($oDateTime, $sAuthorLogin, Dict::S('UI:CaseLog:Edition:ActivityEntry'), 'caselog-edit-history-'.$iChangeOpId);
		$this->sAttCode = $sAttCode;
		$this->iEntryIndex = $iEntryIndex;
		$this->sPreviousContent = $sPreviousContent;
		$this->sNewContent = $sNewContent;
		$this->aContentComparison = null;
		$this->SetType(static::DEFAULT_TYPE);
		$this->SetDecorationClasses(static::DEFAULT_DECORATION_CLASSES);
	}

	/**
	 * Return the edited CaseLog attribute code.
	 *
	 * @return string
	 * @since 3.3.0 N°1365 - Identify the edited CaseLog entry
	 */
	public function GetAttCode(): string
	{
		return $this->sAttCode;
	}

	/**
	 * Return the internal index of the edited CaseLog entry.
	 *
	 * @return int
	 * @since 3.3.0 N°1365 - Identify the edited CaseLog entry
	 */
	public function GetEntryIndex(): int
	{
		return $this->iEntryIndex;
	}

	/**
	 * Return whether both versions of this edit are available.
	 *
	 * @return bool
	 * @since 3.3.0 N°1365 - Display CaseLog edit versions
	 */
	public function HasContentSnapshot(): bool
	{
		return ($this->sPreviousContent !== null) && ($this->sNewContent !== null);
	}

	/**
	 * Return the rendered content before the edit, with removed text highlighted.
	 *
	 * @return string
	 * @since 3.3.0 N°1365 - Display CaseLog edit versions
	 */
	public function GetPreviousContent(): string
	{
		return $this->GetContentComparison()['previous'];
	}

	/**
	 * Return the rendered content after the edit, with added text highlighted.
	 *
	 * @return string
	 * @since 3.3.0 N°1365 - Display CaseLog edit versions
	 */
	public function GetNewContent(): string
	{
		return $this->GetContentComparison()['new'];
	}

	/**
	 * Return the cached rendered comparison for this edit.
	 *
	 * @return array{previous: string, new: string}
	 * @throws \CoreException
	 * @since 3.3.0 N°1365 - Compare rendered CaseLog versions
	 */
	private function GetContentComparison(): array
	{
		if (!$this->HasContentSnapshot()) {
			return ['previous' => '', 'new' => ''];
		}

		if ($this->aContentComparison === null) {
			$this->aContentComparison = HtmlDiffHelper::Compare($this->sPreviousContent ?? '', $this->sNewContent ?? '');
		}

		return $this->aContentComparison;
	}
}
