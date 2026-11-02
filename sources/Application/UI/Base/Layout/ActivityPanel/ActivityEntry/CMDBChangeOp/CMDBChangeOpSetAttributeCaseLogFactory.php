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
use CMDBChangeOpSetAttributeCaseLog;
use Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\CaseLogEditEntry;
use DateTime;
use iCMDBChangeOp;

/**
 * Factory for CaseLog change operations.
 *
 * @since 3.3.0 N°1365 - Render CaseLog edit history
 * @internal
 */
class CMDBChangeOpSetAttributeCaseLogFactory extends CMDBChangeOpSetAttributeFactory
{
	/**
	 * Make a history entry from a CaseLog change operation.
	 *
	 * @param \iCMDBChangeOp $oChangeOp
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\ActivityEntry
	 * @throws \OQLException
	 * @since 3.3.0 N°1365 - Render CaseLog edit history
	 */
	public static function MakeFromCmdbChangeOp(iCMDBChangeOp $oChangeOp)
	{
		if (($oChangeOp instanceof CMDBChangeOpSetAttributeCaseLog) && ((bool)$oChangeOp->Get('is_edit'))) {
			$oDateTime = DateTime::createFromFormat(AttributeDateTime::GetInternalFormat(), $oChangeOp->Get('date'));
			if ($oDateTime === false) {
				throw new \CoreException('Invalid date on CaseLog edit history entry');
			}
			$sAuthorLogin = static::GetUserLoginFromChangeOp($oChangeOp);
			$sPreviousContent = $oChangeOp->Get('oldvalue');
			$sNewContent = $oChangeOp->Get('newvalue');

			return new CaseLogEditEntry(
				$oDateTime,
				$sAuthorLogin,
				$oChangeOp->Get('attcode'),
				(int)$oChangeOp->Get('lastentry'),
				(int)$oChangeOp->GetKey(),
				is_string($sPreviousContent) ? $sPreviousContent : null,
				is_string($sNewContent) ? $sNewContent : null
			);
		}

		return parent::MakeFromCmdbChangeOp($oChangeOp);
	}
}
