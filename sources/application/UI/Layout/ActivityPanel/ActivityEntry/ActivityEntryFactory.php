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

namespace Combodo\iTop\Application\UI\Layout\ActivityPanel\ActivityEntry;


use AttributeDateTime;
use DateTime;
use MetaModel;

/**
 * Class ActivityEntryFactory
 *
 * @internal
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Layout\ActivityPanel\ActivityEntry
 * @since 2.8.0
 */
class ActivityEntryFactory
{
	/**
	 * Make a CaseLogEntry entry (for ActivityPanel) from an ormCaseLog array entry.
	 *
	 * @param string $sAttCode Code of the case log attribute
	 * @param array $aOrmEntry
	 *
	 * @return \Combodo\iTop\Application\UI\Layout\ActivityPanel\ActivityEntry\CaseLogEntry
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \OQLException
	 */
	public static function MakeFromCaseLogEntryArray($sAttCode, $aOrmEntry)
	{
		$oUser = MetaModel::GetObject('User', $aOrmEntry['user_id'], false, true);
		$sUserLogin = ($oUser === null) ? '' : $oUser->Get('login');

		$oEntry = new CaseLogEntry(
			$aOrmEntry['message_html'],
			DateTime::createFromFormat(AttributeDateTime::GetInternalFormat(), $aOrmEntry['date']),
			$sUserLogin,
			$sAttCode
		);

		return $oEntry;
	}
}