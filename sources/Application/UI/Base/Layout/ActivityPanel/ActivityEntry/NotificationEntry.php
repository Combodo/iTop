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

/**
 * Class NotificationEntry
 *
 * @internal
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry
 * @since 3.0.0
 */
class NotificationEntry extends ActivityEntry
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-notification-entry';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/layouts/activity-panel/activity-entry/notification-entry';

	public const DEFAULT_TYPE = 'edits';
	public const DEFAULT_DECORATION_CLASSES = 'fas fa-fw fa-bell';

	/** @var string Title of the entry, usually the linked action title */
	protected $sTitle;
	/** @var string Message of the entry, usually it's status */
	protected $sMessage;

	/**
	 * NotificationEntry constructor.
	 *
	 * @param \DateTime $oDateTime
	 * @param string $sAuthorLogin
	 * @param string $sTitle
	 * @param string $sMessage
	 * @param string|null $sId
	 *
	 * @throws \OQLException
	 */
	public function __construct(DateTime $oDateTime, string $sAuthorLogin, string $sTitle, string $sMessage, ?string $sId = null)
	{
		parent::__construct($oDateTime, $sAuthorLogin, null, $sId);

		$this->SetTitle($sTitle);
		$this->SetMessage($sMessage);
	}

	/**
	 * @see static::$sTitle
	 *
	 * @param string $sTitle
	 *
	 * @return $this
	 */
	public function SetTitle(string $sTitle)
	{
		$this->sTitle = $sTitle;

		return $this;
	}

	/**
	 * @see static::$sTitle
	 * @return string
	 */
	public function GetTitle(): string
	{
		return $this->sTitle;
	}

	/**
	 * @see static::$sMessage
	 *
	 * @param string $sMessage
	 *
	 * @return $this
	 */
	public function SetMessage(string $sMessage)
	{
		$this->sMessage = $sMessage;

		return $this;
	}

	/**
	 * @see static::$sMessage
	 * @return string
	 */
	public function GetMessage(): string
	{
		return $this->sMessage;
	}
}