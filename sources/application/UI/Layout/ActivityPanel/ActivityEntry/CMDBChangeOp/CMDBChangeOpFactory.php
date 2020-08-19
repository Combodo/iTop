<?php
/**
 * Copyright (C) 2013-2020 Combodo SARL
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

namespace Combodo\iTop\Application\UI\Layout\ActivityPanel\ActivityEntry\CMDBChangeOp;


use AttributeDateTime;
use Combodo\iTop\Application\UI\Layout\ActivityPanel\ActivityEntry\ActivityEntry;
use Combodo\iTop\Application\UI\Layout\ActivityPanel\ActivityEntry\EditsEntry;
use DateTime;
use iCMDBChangeOp;

/**
 * Class CMDBChangeOpFactory
 *
 * Default factory for CMDBChangeOp change ops
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Layout\ActivityPanel\ActivityEntry\CMDBChangeOp
 */
class CMDBChangeOpFactory
{
	/** @var string DEFAULT_TYPE Used to overload the type from the ActivityEntry */
	const DEFAULT_TYPE = EditsEntry::DEFAULT_TYPE;
	/** @var string DEFAULT_DECORATION_CLASSES Used to overload the decoration classes from the ActivityEntry */
	const DEFAULT_DECORATION_CLASSES = ActivityEntry::DEFAULT_DECORATION_CLASSES;

	/**
	 * Make an ActivityEntry from the iCMDBChangeOp $oChangeOp
	 *
	 * @param \iCMDBChangeOp $oChangeOp
	 *
	 * @return \Combodo\iTop\Application\UI\Layout\ActivityPanel\ActivityEntry\ActivityEntry
	 * @throws \OQLException
	 */
	public static function MakeFromCmdbChangeOp(iCMDBChangeOp $oChangeOp)
	{
		$oDateTime = DateTime::createFromFormat(AttributeDateTime::GetInternalFormat(), $oChangeOp->Get('date'));
		$sAuthorFriendlyname = $oChangeOp->Get('userinfo');
		$sContent = $oChangeOp->GetDescription();

		$oEntry = new ActivityEntry($oDateTime, $sAuthorFriendlyname, $sContent);
		$oEntry->SetType(static::DEFAULT_TYPE)
			->SetDecorationClasses(static::DEFAULT_DECORATION_CLASSES);

		return $oEntry;
	}
}