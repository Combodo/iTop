<?php
/**
 * Copyright (C) 2013-2022 Combodo SARL
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

namespace Combodo\iTop\Application\UI\Links\Set;

use Combodo\iTop\Application\UI\Base\Component\Input\Set\DataProvider\iDataBinder;
use DBObjectSet;
use DBSearch;
use MetaModel;

/**
 * Class LinksBulkDataBinder
 *
 * @api
 *
 * @since 3.1.0
 * @package Combodo\iTop\Application\UI\Links\Set
 */
class LinksBulkDataBinder implements iDataBinder
{
	/** @inheritDoc */
	public static function Bind(string $sObjectClassName, array $aData, array $aBinderSettings)
	{
		return self::ComputeScopeData($aData, $aBinderSettings['bulk_oql'], $aBinderSettings['attribute_linked_set_code'], $aBinderSettings['target_field'], $sObjectClassName);
	}

	/** @todo BDA create oql with count */
	public static function ComputeScopeData(array $aResult, string $sScope, string $sScopeField, ?string $sTargetField, string $sObjectClass): array
	{
		if (!empty($sScope)) {
			$oDbObjectSearch = DBSearch::FromOQL($sScope);
			$oDbObjectSet = new DBObjectSet($oDbObjectSearch);
			foreach ($aResult as &$aObjectLink) {
				$iCount = 0;
				$aLinkKeys = [];
				$oDbObjectSet->Rewind();
				while ($oObject = $oDbObjectSet->Fetch()) {
					$test = $oObject->Get($sScopeField);
					if ($sTargetField != null) {
						$aIds = $test->GetColumnAsArray($sTargetField);
						foreach ($aIds as $iLinkId => $sObjectId) {
							$o = MetaModel::GetObject($sObjectClass, $sObjectId);
							if ($o->Get('id') == $aObjectLink['key']) {
								$iCount++;
								$aLinkKeys[] = $iLinkId;
							}
						}
					} else {
						$aIds = $test->GetColumnAsArray('id');
						if (in_array($aObjectLink['key'], $aIds)) {
							$iCount++;
						}
					}
				}
				if ($iCount) {
					$aObjectLink['group'] = 'Objects already linked';
					$aObjectLink['occurrence'] = 'Link on '.$iCount.' Objects(s)';
					$aObjectLink['full'] = ($iCount === $oDbObjectSet->Count());
					$aObjectLink['link_keys'] = $aLinkKeys;
				} else {
					$aObjectLink['group'] = 'Others';
					$aObjectLink['occurrence'] = '';
					$aObjectLink['empty'] = true;
				}
			}

			usort($aResult, [self::class, "cmp"]);
		}

		return $aResult;
	}

	static function cmp($a, $b)
	{
		if ($a['group'] == $b['group']) {
			return 0;
		}

		return ($a['group'] < $b['group']) ? -1 : 1;
	}
}