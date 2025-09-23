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

namespace Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\CMDBChangeOp;


use AttributeDateTime;
use Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\ActivityEntry;
use Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\EditsEntry;
use DateTime;
use iCMDBChangeOp;
use MetaModel;

/**
 * Class CMDBChangeOpFactory
 *
 * Default factory for CMDBChangeOp change ops
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\CMDBChangeOp
 * @since 3.0.0
 * @internal
 */
class CMDBChangeOpFactory
{
	/** @var string DEFAULT_TYPE Used to overload the type from the ActivityEntry */
	public const DEFAULT_TYPE = EditsEntry::DEFAULT_TYPE;
	/** @var string DEFAULT_DECORATION_CLASSES Used to overload the decoration classes from the ActivityEntry */
	public const DEFAULT_DECORATION_CLASSES = ActivityEntry::DEFAULT_DECORATION_CLASSES;

	/**
	 * Make an ActivityEntry from the iCMDBChangeOp $oChangeOp
	 *
	 * @param \iCMDBChangeOp $oChangeOp
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\ActivityEntry
	 * @throws \OQLException
	 */
	public static function MakeFromCmdbChangeOp(iCMDBChangeOp $oChangeOp)
	{
		$oDateTime = DateTime::createFromFormat(AttributeDateTime::GetInternalFormat(), $oChangeOp->Get('date'));
		$sContent = $oChangeOp->GetDescription();

		// Retrieve author login
		$sAuthorLogin = static::GetUserLoginFromChangeOp($oChangeOp);

		$oEntry = new ActivityEntry($oDateTime, $sAuthorLogin, $sContent);
		$oEntry->SetType(static::DEFAULT_TYPE)
			->SetDecorationClasses(static::DEFAULT_DECORATION_CLASSES);

		return $oEntry;
	}

	/**
	 * Return the login of the $oChangeOp author or its friendlyname if the user cannot be retrieved.
	 *
	 * @param \iCMDBChangeOp $oChangeOp
	 *
	 * @return string|null
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 */
	public static function GetUserLoginFromChangeOp(iCMDBChangeOp $oChangeOp)
	{
		$iAuthorId = $oChangeOp->Get('user_id');
		// - Set login in the friendlyname as a fallback
		$sAuthorLogin = $oChangeOp->Get('userinfo');
		// - Try to find user login from its ID if present (since iTop 3.0.0)
		if(empty($iAuthorId) === false)
		{
			$oAuthor = MetaModel::GetObject('User', $iAuthorId, false, true);
			if(empty($oAuthor) === false)
			{
				$sAuthorLogin = $oAuthor->Get('login');
			}
		}

		return $sAuthorLogin;
	}
}