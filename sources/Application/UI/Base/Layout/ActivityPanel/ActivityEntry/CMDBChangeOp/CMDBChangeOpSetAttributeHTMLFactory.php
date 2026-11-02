<?php

/**
 * Copyright (C) 2013-2024 Combodo SAS
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License
 * as published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

namespace Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\CMDBChangeOp;

use CMDBChangeOpSetAttributeHTML;
use Combodo\iTop\Application\Helper\HtmlDiffHelper;
use Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\EditsEntry;
use Dict;
use iCMDBChangeOp;

/**
 * Build rich-text comparisons for HTML attribute history entries.
 *
 * @since 3.3.0 N°1365 - Compare ticket and other HTML attribute versions
 * @internal
 */
class CMDBChangeOpSetAttributeHTMLFactory extends CMDBChangeOpSetAttributeFactory
{
	/**
	 * Make an activity entry from an HTML attribute change operation.
	 *
	 * @param \iCMDBChangeOp $oChangeOp
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\ActivityEntry
	 * @throws \CoreException
	 * @throws \OQLException
	 * @throws \Exception
	 * @since 3.3.0 N°1365 - Compare rendered HTML attribute versions
	 */
	public static function MakeFromCmdbChangeOp(iCMDBChangeOp $oChangeOp)
	{
		$oEntry = parent::MakeFromCmdbChangeOp($oChangeOp);
		if (!($oChangeOp instanceof CMDBChangeOpSetAttributeHTML) || !($oEntry instanceof EditsEntry)) {
			return $oEntry;
		}

		$sPreviousHtml = $oChangeOp->Get('prevdata');
		$sNewHtml = $oChangeOp->Get('newvalue');
		if (!is_string($sNewHtml) || (!is_string($sPreviousHtml) && ($sPreviousHtml !== null))) {
			return $oEntry;
		}

		$sAttCode = $oChangeOp->Get('attcode');
		$aAttributes = $oEntry->GetAttributes();
		if (!isset($aAttributes[$sAttCode])) {
			return $oEntry;
		}

		$aAttributes[$sAttCode]['descriptions'] = [
			Dict::Format('Change:AttName_Changed', $aAttributes[$sAttCode]['label']),
		];
		$oEntry->SetAttributes($aAttributes);
		$oEntry->SetHtmlComparison($sAttCode, HtmlDiffHelper::Compare($sPreviousHtml ?? '', $sNewHtml));

		return $oEntry;
	}
}
