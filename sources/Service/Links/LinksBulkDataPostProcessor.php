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

namespace Combodo\iTop\Service\Links;

use CMDBSource;
use Combodo\iTop\Service\Base\iDataPostProcessor;
use DBObjectSet;
use DBSearch;
use Exception;
use ExceptionLog;
use FieldExpression;

/**
 * Class LinksBulkDataPostProcessor
 *
 * @api
 *
 * @since 3.1.0
 * @package Combodo\iTop\Service\Links
 */
class LinksBulkDataPostProcessor implements iDataPostProcessor
{
	/** @inheritDoc */
	public static function Execute(array $aData, array $aSettings): array
	{
		return self::ComputeScopeData($aData, $aSettings['bulk_oql'], $aSettings['link_class'], $aSettings['origin_field'], $aSettings['target_field']);
	}

	/**
	 * ComputeScopeData.
	 *
	 * @param array $aResult
	 * @param string $sScope
	 * @param string $sLinkClass
	 * @param string $sOriginField
	 * @param string $sTargetField
	 *
	 * @return array
	 */
	public static function ComputeScopeData(array $aResult, string $sScope, string $sLinkClass, string $sOriginField, string $sTargetField): array
	{
		if (!empty($sScope)) {

			try {
				// OQL to select bulk object selection
				$oDbObjectSearchBulkObjects = DBSearch::FromOQL($sScope);
				$oDbObjectSetBulkObjects = new DBObjectSet($oDbObjectSearchBulkObjects);
				$aBulksObjects = $oDbObjectSetBulkObjects->GetColumnAsArray('id', false);
				$sBulkList = implode(',', $aBulksObjects);

				// Get all links attached to object selection
				$sOqlGroupBy = "SELECT $sLinkClass AS lnk WHERE lnk.$sOriginField IN ($sBulkList)";
				$oDbObjectSearch = DBSearch::FromOQL($sOqlGroupBy);

				// Group by links attached to object selection
				$oFieldExp = new FieldExpression($sTargetField, 'lnk');
				$sQuery = $oDbObjectSearch->MakeGroupByQuery([$sTargetField], array('grouped_by_1' => $oFieldExp), true);
				$aGroupResult = CMDBSource::QueryToArray($sQuery, MYSQLI_ASSOC);

				// Iterate throw result...
				foreach ($aResult as &$aItem) {

					// Find group by object to extract link count
					$aFound = null;
					foreach ($aGroupResult as $aItemGroup) {
						if ($aItem['key'] === $aItemGroup['grouped_by_1']) {
							$aFound = $aItemGroup;
						}
					}

					// If found, get information
					if ($aFound !== null) {
						$aItem['group'] = 'Objects already linked';
						$aItem['occurrence'] = $aFound['_itop_count_'];
						$aItem['occurrence_label'] = "Link on {$aFound['_itop_count_']} Objects(s)";
						$aItem['occurrence_info'] = "({$aFound['_itop_count_']})";
						$aItem['full'] = ($aFound['_itop_count_'] == $oDbObjectSetBulkObjects->Count());

						// Retrieve linked objects keys
						$sOqlLinkKeys = "SELECT $sLinkClass AS lnk WHERE lnk.$sOriginField IN ($sBulkList) AND lnk.$sTargetField = {$aItem['key']}";
						$oDbSearchLinkKeys = DBSearch::FromOQL($sOqlLinkKeys);
						$aLinkedObjects = new DBObjectSet($oDbSearchLinkKeys);
						$aItem['link_keys'] = $aLinkedObjects->GetColumnAsArray('id', false);

					} else {
						$aItem['group'] = 'Others';
						$aItem['occurrence'] = '';
						$aItem['empty'] = true;
					}

				}

				// Order items
				usort($aResult, [self::class, "CompareItems"]);
			}
			catch (Exception $e) {

				ExceptionLog::LogException($e);
			}

		}

		return $aResult;
	}

	/**
	 * CompareItems.
	 *
	 * @param $aItemA
	 * @param $aItemB
	 *
	 * @return array|int
	 */
	static private function CompareItems($aItemA, $aItemB): int
	{
		if ($aItemA['occurrence'] === $aItemB['occurrence']) {
			return 0;
		}

		return ($aItemA['occurrence'] > $aItemB['occurrence']) ? -1 : 1;
	}
}