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

namespace Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\EventNotification;


use AttributeDateTime;
use Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\NotificationEntry;
use DateTime;
use EventNotification;

/**
 * Class EventNotificationFactory
 *
 * Default factory for EventNotification events
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\EventNotification
 * @since 3.0.0
 * @internal
 */
class EventNotificationFactory
{
	/** @var string Used to overload the type from the NotificationEntry */
	public const DEFAULT_TYPE = NotificationEntry::DEFAULT_TYPE;
	/** @var string Used to overload the decoration classes from the NotificationEntry */
	public const DEFAULT_DECORATION_CLASSES = NotificationEntry::DEFAULT_DECORATION_CLASSES;

	/**
	 * Make an ActivityEntry from the iEventNotification $oEvent
	 *
	 * @param \EventNotification $oEvent
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\NotificationEntry
	 * @throws \OQLException
	 */
	public static function MakeFromEventNotification(EventNotification $oEvent)
	{
		$oDateTime = DateTime::createFromFormat(AttributeDateTime::GetInternalFormat(), $oEvent->Get('date'));

		// Author login is hardcoded:
		// - Adding a user_id column to the event tables like for the CMDBChangeOp could be erro prone during migration as those tables are huge.
		// - Marking events as created by the app. is good enough as the user triggering it as no power over it, it cannot avoid it.
		$sAuthorLogin = ITOP_APPLICATION_SHORT;

		$oEntry = new NotificationEntry($oDateTime, $sAuthorLogin, $oEvent->Get('action_id_friendlyname'), $oEvent->Get('message'));
		$oEntry->SetType(static::DEFAULT_TYPE)
			->SetDecorationClasses(static::DEFAULT_DECORATION_CLASSES);

		return $oEntry;
	}
}