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

namespace Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry;


use AttributeDateTime;
use CMDBChangeOp;
use DateTime;
use Exception;
use MetaModel;
use ReflectionClass;

/**
 * Class ActivityEntryFactory
 *
 * @internal
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry
 * @since 3.0.0
 */
class ActivityEntryFactory
{
	/**
	 * Make an ActivityEntry entry (for ActivityPanel) based on the $oChangeOp.
	 *
	 * @param \CMDBChangeOp $oChangeOp
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\ActivityEntry
	 * @throws \Exception
	 */
	public static function MakeFromCmdbChangeOp(CMDBChangeOp $oChangeOp)
	{
		$sFactoryFqcn = static::GetCmdbChangeOpFactoryClass($oChangeOp);

		// If no factory found, throw an exception as the developer most likely forgot to create it
		if(empty($sFactoryFqcn))
		{
			throw new Exception('No factory found for '.get_class($oChangeOp).', did you forgot to create one?');
		}

		/** @var \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\ActivityEntry $oEntry */
		/** @noinspection PhpUndefinedMethodInspection Call static method from the $sFactoryFqcn class */
		$oEntry = $sFactoryFqcn::MakeFromCmdbChangeOp($oChangeOp);

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

		$oEntry = new CaseLogEntry(
			DateTime::createFromFormat(AttributeDateTime::GetInternalFormat(), $aOrmEntry['date']),
			$sUserLogin,
			$sAttCode,
			$aOrmEntry['message_html']
		);

		return $oEntry;
	}

	/**
	 * Return the FQCN of the best fitted factory for the $oChangeOp. If none found, null will be returned.
	 *
	 * @param \CMDBChangeOp $oChangeOp
	 *
	 * @return string|null
	 * @throws \ReflectionException
	 */
	protected static function GetCmdbChangeOpFactoryClass(CMDBChangeOp $oChangeOp)
	{
		// Classes to search a factory for
		$aClassesTree = [get_class($oChangeOp)];

		// Add parent classes to tree if not a root class
		$aParentClasses = class_parents($oChangeOp);
		if(is_array($aParentClasses))
		{
			$aClassesTree = array_merge($aClassesTree, array_values($aParentClasses));
		}

		$sFactoryFqcn = null;
		foreach($aClassesTree as $sClass)
		{
			// Warning: This will replace all occurrences of 'CMDBChangeOp' which can be an issue on classes using this
			// We used the case sensitive search to limit this issue.
			$sSimplifiedClass = (new ReflectionClass($sClass))->getShortName();
			$sFactoryFqcnToTry = __NAMESPACE__ . '\\CMDBChangeOp\\' . $sSimplifiedClass . 'Factory';

			// Stop at the first factory found
			if(class_exists($sFactoryFqcnToTry))
			{
				$sFactoryFqcn = $sFactoryFqcnToTry;
				break;
			}
		}

		return $sFactoryFqcn;
	}
}