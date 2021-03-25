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


use AttributeDateTime;
use Combodo\iTop\Application\UI\Base\UIBlock;
use DateTime;
use UserRights;
use utils;

/**
 * Class ActivityEntry
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry
 * @internal
 * @since 3.0.0
 */
class ActivityEntry extends UIBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-activity-entry';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/layouts/activity-panel/activity-entry/layout';

	// Specific constants
	/** @var string DEFAULT_ORIGIN */
	public const DEFAULT_ORIGIN = 'unknown';
	/** @var string DEFAULT_TYPE */
	public const DEFAULT_TYPE = 'generic';
	/** @var string DEFAULT_DECORATION_CLASSES */
	public const DEFAULT_DECORATION_CLASSES = 'fas fa-fw fa-mortar-pestle';
	/** @var string Relative URL (from the app. root) to the default author picture URL */
	public const DEFAULT_AUTHOR_PICTURE_REL_URL = 'images/icons/icons8-music-robot.svg';

	/** @var string $sType Type of entry, used for filtering (eg. case log, edits, transition, ...) */
	protected $sType;
	/** @var string $sDecorationClasses CSS classes to use to decorate the entry */
	protected $sDecorationClasses;
	/** @var string|null $sContent Raw content of the entry itself (should not have been processed / escaped) */
	protected $sContent;
	/** @var \DateTime $oDateTime Date / time the entry occurred */
	protected $oDateTime;
	/** @var string $sAuthorLogin Login of the author (user, cron, extension, ...) who made the activity of the entry */
	protected $sAuthorLogin;
	/** @var string $sAuthorFriendlyname */
	protected $sAuthorFriendlyname;
	/** @var string $sAuthorInitials */
	protected $sAuthorInitials;
	/** @var string $sAuthorPictureAbsUrl */
	protected $sAuthorPictureAbsUrl;
	/** @var bool $bIsFromCurrentUser Flag to know if the user who made the activity was the current user */
	protected $bIsFromCurrentUser;
	/** @var string $sOrigin Origin of the entry (case log, cron, lifecycle, user edit, ...) */
	protected $sOrigin;

	/**
	 * ActivityEntry constructor.
	 *
	 * @param \DateTime $oDateTime
	 * @param string $sAuthorLogin
	 * @param string|null $sContent
	 * @param string|null $sId
	 *
	 * @throws \OQLException
	 */
	public function __construct(DateTime $oDateTime, string $sAuthorLogin, ?string $sContent = null, ?string $sId = null)
	{
		parent::__construct($sId);

		$this->SetType(static::DEFAULT_TYPE);
		$this->SetDecorationClasses(static::DEFAULT_DECORATION_CLASSES);
		$this->SetContent($sContent);
		$this->SetDateTime($oDateTime);
		$this->SetAuthor($sAuthorLogin);
		$this->SetOrigin(static::DEFAULT_ORIGIN);
	}

	/**
	 * Set the type of the entry (eg. case log, edits, transition, ...)
	 *
	 * @param string $sType
	 *
	 * @return $this
	 */
	public function SetType(string $sType)
	{
		$this->sType = $sType;

		return $this;
	}

	/**
	 * Return the type of the entry (eg. case log, edits, transition, ...)
	 *
	 * @return string
	 */
	public function GetType()
	{
		return $this->sType;
	}

	/**
	 * Set the CSS decoration classes
	 *
	 * @param string $sDecorationClasses Must be a space-separated list of CSS classes
	 *
	 * @return $this
	 */
	public function SetDecorationClasses(string $sDecorationClasses)
	{
		$this->sDecorationClasses = $sDecorationClasses;

		return $this;
	}

	/**
	 * Return a string of the space separated CSS decoration classes
	 *
	 * @return string
	 */
	public function GetDecorationClasses()
	{
		return $this->sDecorationClasses;
	}

	/**
	 * Set the content without any filtering / escaping
	 *
	 * @param string|null $sContent
	 *
	 * @return $this
	 */
	public function SetContent(?string $sContent)
	{
		$this->sContent = $sContent;

		return $this;
	}

	/**
	 * Return the raw content without any filtering / escaping
	 *
	 * @return string
	 */
	public function GetContent()
	{
		return $this->sContent;
	}

	/**
	 * @param \DateTime $oDateTime
	 *
	 * @return $this
	 */
	public function SetDateTime(DateTime $oDateTime)
	{
		$this->oDateTime = $oDateTime;
		return $this;
	}

	/**
	 * Return the date time without formatting, as per the mysql format
	 * @return string
	 */
	public function GetRawDateTime()
	{
		return $this->oDateTime->format(AttributeDateTime::GetInternalFormat());
	}

	/**
	 * Return the date time formatted as per the iTop config.
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function GetFormattedDateTime()
	{
		$oDateTimeFormat = AttributeDateTime::GetFormat();
		return $oDateTimeFormat->Format($this->oDateTime);
	}

	/**
	 * Set the author and its information based on the $sAuthorLogin
	 *
	 * @param string $sAuthorLogin
	 *
	 * @return $this
	 * @throws \OQLException
	 * @throws \Exception
	 */
	public function SetAuthor(string $sAuthorLogin)
	{
		$this->sAuthorLogin = $sAuthorLogin;

		// Set friendlyname to whatever we have in case $sAuthorLogin is not a valid login (deleted user, cron, ...)
		$iAuthorId = UserRights::GetUserId($this->sAuthorLogin);
		// - Friendlyname
		if (true === empty($iAuthorId)) {
			$this->sAuthorFriendlyname = $this->sAuthorLogin;
		} else {
			$this->sAuthorFriendlyname = UserRights::GetUserFriendlyName($this->sAuthorLogin);
		}
		// - Initials
		$this->sAuthorInitials = UserRights::GetUserInitials($this->sAuthorLogin);
		// - Picture
		$this->sAuthorPictureAbsUrl = UserRights::GetUserPictureAbsUrl($this->sAuthorLogin, false);
		if ((null === $this->sAuthorPictureAbsUrl) && (ITOP_APPLICATION_SHORT === $this->sAuthorLogin)) {
			$this->sAuthorPictureAbsUrl = utils::GetAbsoluteUrlAppRoot().static::DEFAULT_AUTHOR_PICTURE_REL_URL;
		}

		$this->bIsFromCurrentUser = UserRights::GetUserId($this->sAuthorLogin) === UserRights::GetUserId();

		return $this;
	}

	/**
	 * @return string
	 */
	public function GetAuthorLogin()
	{
		return $this->sAuthorLogin;
	}

	/**
	 * @return string
	 */
	public function GetAuthorFriendlyname()
	{
		return $this->sAuthorFriendlyname;
	}

	/**
	 * @return string
	 */
	public function GetAuthorInitials()
	{
		return $this->sAuthorInitials;
	}

	/**
	 * @return string
	 */
	public function GetAuthorPictureAbsUrl()
	{
		return $this->sAuthorPictureAbsUrl;
	}

	/**
	 * Return true if the current user is the author of the activity entry
	 *
	 * @return bool
	 */
	public function IsFromCurrentUser()
	{
		return $this->bIsFromCurrentUser;
	}

	/**
	 * Set the origin of the activity entry
	 *
	 * @param string $sOrigin
	 *
	 * @return $this
	 */
	protected function SetOrigin(string $sOrigin)
	{
		$this->sOrigin = $sOrigin;

		return $this;
	}

	/**
	 * Return the origin of the activity entry
	 *
	 * @return string
	 */
	public function GetOrigin()
	{
		return $this->sOrigin;
	}
}