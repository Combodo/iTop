<?php
/**
 * Copyright (C) 2013-2021 Combodo SARL
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

	/**
	 * CaseLogEntry constructor.
	 *
	 * @param \DateTime $oDateTime
	 * @param \User $sAuthorLogin
	 * @param string $sAttCode
	 * @param string $sContent
	 * @param string|null $sId
	 *
	 * @throws \OQLException
	 */
	public function __construct(DateTime $oDateTime, string $sAuthorLogin, string $sAttCode, string $sContent, ?string $sId = null)
	{
		parent::__construct($oDateTime, $sAuthorLogin, $sContent, $sId);

		$this->sAttCode = $sAttCode;
		$this->SetCaseLogRank(static::DEFAULT_CASELOG_RANK);
		$this->SetOrigin('caselog:'.$this->sAttCode);
	}

	/**
	 * Return the code of the corresponding case log attribute
	 *
	 * @return string
	 */
	public function GetAttCode()
	{
		return $this->sAttCode;
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
	public function GetCaseLogRank()
	{
		return $this->iCaseLogRank;
	}
}