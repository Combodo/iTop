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


use AttributeDateTime;
use CMDBChange;
use CMDBChangeOp;
use DateTime;
use DBObject;
use EventNotification;
use Exception;
use MetaModel;
use ReflectionClass;

/**
 * Class ActivityEntryFactory
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry
 * @since 3.0.0
 * @internal
 */
class ActivityEntryFactory
{
	/**
	 * Make an ActivityEntry entry (for ActivityPanel) based on the $oChangeOp.
	 *
	 * @param \CMDBChangeOp $oChangeOp
	 * @param \CMDBChange $oChange
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\ActivityEntry
	 * @throws \ReflectionException
	 * @throws \Exception
	 */
	public static function MakeFromCmdbChangeOp(CMDBChangeOp $oChangeOp, CMDBChange $oChange)
	{
		$sFactoryFqcn = static::GetFactoryClass($oChangeOp, 'CMDBChangeOp');

		// If no factory found, throw an exception as the developer most likely forgot to create it
		if (empty($sFactoryFqcn)) {
			throw new Exception('No factory found for '.get_class($oChangeOp).', did you forgot to create one?');
		}

		/** @var \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\ActivityEntry $oEntry */
		/** @noinspection PhpUndefinedMethodInspection Call static method from the $sFactoryFqcn class */
		$oEntry = $sFactoryFqcn::MakeFromCmdbChangeOp($oChangeOp);
		$oEntry->SetOrigin($oChange->Get('origin'));

		return $oEntry;
	}

	/**
	 * Make a CaseLogEntry entry (for ActivityPanel) from an ormCaseLog array entry.
	 *
	 * @param string $sAttCode Code of the case log attribute
	 * @param array $aOrmEntry
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\CaseLogEntry
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \OQLException
	 */
	public static function MakeFromCaseLogEntryArray(string $sAttCode, array $aOrmEntry)
	{
		$oUser = MetaModel::GetObject('User', $aOrmEntry['user_id'], false, true);
		$sUserLogin = ($oUser === null) ? '' : $oUser->Get('login');

		// We sanitize OrmEntry even if it's already sanitized: if the entry is somehow truncated or metadata are wrong we may break whole page DOM 
		$oEntry = new CaseLogEntry(
			DateTime::createFromFormat(AttributeDateTime::GetInternalFormat(), $aOrmEntry['date']),
			$sUserLogin,
			$sAttCode,
			\HTMLSanitizer::Sanitize($aOrmEntry['message_html']),
			$aOrmEntry['user_login']
		);

		return $oEntry;
	}

	/**
	 * Make an ActivityEntry entry (for ActivityPanel) based on the $oEvent
	 *
	 * @param \EventNotification $oEvent
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\ActivityEntry
	 * @throws \ReflectionException
	 */
	public static function MakeFromEventNotification(EventNotification $oEvent)
	{
		$sFactoryFqcn = static::GetFactoryClass($oEvent, 'EventNotification');

		// If no factory found, throw an exception as the developer most likely forgot to create it
		if (empty($sFactoryFqcn)) {
			throw new Exception('No factory found for '.get_class($oEvent).', did you forgot to create one?');
		}

		/** @var \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\NotificationEntry $oEntry */
		/** @noinspection PhpUndefinedMethodInspection Call static method from the $sFactoryFqcn class */
		$oEntry = $sFactoryFqcn::MakeFromEventNotification($oEvent);

		return $oEntry;
	}

	/**
	 * Return the FQCN of the best fitted factory for the $oObject / $sObjectType tuple. If none found, null will be returned.
	 *
	 * @param \DBObject $oObject
	 *
	 * @return string|null
	 * @throws \ReflectionException
	 */
	protected static function GetFactoryClass(DBObject $oObject, string $sObjectType)
	{
		// Classes to search a factory for
		$aClassesTree = [get_class($oObject)];

		// Add parent classes to tree if not a root class
		$aParentClasses = class_parents($oObject);
		if (is_array($aParentClasses)) {
			$aClassesTree = array_merge($aClassesTree, array_values($aParentClasses));
		}

		$sFactoryFqcn = null;
		foreach ($aClassesTree as $sClass) {
			// Warning: This will replace all occurrences of $sObjectType (eg. 'CMDBChangeOp', 'EventNotification', ...) which can be an issue on classes using this
			// We used the case sensitive search to limit this issue.
			$sSimplifiedClass = (new ReflectionClass($sClass))->getShortName();
			$sFactoryFqcnToTry = __NAMESPACE__.'\\'.$sObjectType.'\\'.$sSimplifiedClass.'Factory';

			// Stop at the first factory found
			if (class_exists($sFactoryFqcnToTry)) {
				$sFactoryFqcn = $sFactoryFqcnToTry;
				break;
			}
		}

		return $sFactoryFqcn;
	}
}