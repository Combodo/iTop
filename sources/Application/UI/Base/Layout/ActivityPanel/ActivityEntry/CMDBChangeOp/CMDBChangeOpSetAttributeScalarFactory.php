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
use Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\TransitionEntry;
use DateTime;
use iCMDBChangeOp;
use MetaModel;

/**
 * Class CMDBChangeOpSetAttributeScalarFactory
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\CMDBChangeOp\Factory
 * @since 3.0.0
 * @internal
 */
class CMDBChangeOpSetAttributeScalarFactory extends CMDBChangeOpSetAttributeFactory
{
	/**
	 * @inheritDoc
	 * @throws \CoreException
	 */
	public static function MakeFromCmdbChangeOp(iCMDBChangeOp $oChangeOp)
	{
		$sHostObjectClass = $oChangeOp->Get('objclass');
		$sAttCode = $oChangeOp->Get('attcode');

		// Specific ActivityEntry for state changes, otherwise just a regular EditsEntry
		if (MetaModel::HasStateAttributeCode($sHostObjectClass) && ($sAttCode === MetaModel::GetStateAttributeCode($sHostObjectClass))) {
			$oDateTime = DateTime::createFromFormat(AttributeDateTime::GetInternalFormat(), $oChangeOp->Get('date'));

			// Retrieve author login
			$sAuthorLogin = static::GetUserLoginFromChangeOp($oChangeOp);

			$sOriginStateLabel = MetaModel::GetStateLabel($sHostObjectClass, $oChangeOp->Get('oldvalue'));
			$sTargetStateLabel = MetaModel::GetStateLabel($sHostObjectClass, $oChangeOp->Get('newvalue'));

			$oEntry = new TransitionEntry($oDateTime, $sAuthorLogin, $sHostObjectClass, $sOriginStateLabel, $sTargetStateLabel);
		}
		else
		{
			$oEntry = parent::MakeFromCmdbChangeOp($oChangeOp);
		}

		return $oEntry;
	}
}