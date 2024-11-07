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

use Exception;
use ExceptionLog;
use iDBObjectSetIterator;
use MetaModel;
use ormLinkSet;
use utils;

/**
 * Class LinksSetDataTransformer
 *
 * @api
 *
 * @since 3.1.0
 * @package Combodo\iTop\Service\Links
 */
class LinkSetDataTransformer
{
	/**
	 * Decode.
	 *
	 * Convert db object set to array.
	 * This array will be provided to set component view.
	 *
	 * @param \iDBObjectSetIterator $oDbObjectSet Db object set
	 * @param string $sTargetClass Target class name
	 * @param string|null $sTargetField Target field
	 *
	 * @return array
	 */
	static public function Decode(iDBObjectSetIterator $oDbObjectSet, string $sTargetClass, string $sTargetField = null): array
	{
		try {
			// Prepare result
			$aResult = [];

			// Ensure start at set beginning
			$oDbObjectSet->Rewind();

			// Iterate throw objects...
			while ($oObject = $oDbObjectSet->Fetch()) {

				// In case ot indirect link
				if ($sTargetField !== null) {
					$oObject = MetaModel::GetObject($sTargetClass, $oObject->Get($sTargetField));
				}

				if (!utils::ShowObsoleteData() && $oObject->IsObsolete()) {
					continue;
				}

				// Append object key
				$aResult[] = $oObject->GetKey();
			}

			return $aResult;
		}
		catch (Exception $e) {

			ExceptionLog::LogException($e);

			return [];
		}
	}

	/**
	 * Encode.
	 *
	 * Convert array from view to arrays used by UI.php to apply link set modifications.
	 *
	 * @see cmdbAbstractObject::PrepareValueFromPostedForm
	 *
	 * @param array $aElements Link set elements
	 * @param string $sLinkClass Link class name
	 * @param string|null $sExtKeyToRemote External key to remote
	 *
	 * @return array{to_be_created: array, to_be_deleted: array, to_be_added: array, to_be_removed: array}
	 */
	static public function Encode(array $aElements, string $sLinkClass, string $sExtKeyToRemote = null): array
	{
		// Result arrays
		$aToBeCreate = [];
		$aToBeDelete = [];
		$aToBeAdd = [];
		$aToBeRemove = [];

		// Iterate throw data...
		foreach ($aElements as $aData) {

			switch ($aData['operation']) {

				// OPERATION ADD
				case 'add':
					if ($sExtKeyToRemote === null) {
						// Direct link attach
						$aToBeAdd[] = $aData['data']['key'];
					} else {
						// Indirect link creation
						$aToBeCreate[] = [
							'class' => $sLinkClass,
							'data'  => [
								$sExtKeyToRemote => $aData['data']['key'],
							],
						];
					}
					break;

				// OPERATION REMOVE
				case 'remove':
					if ($sExtKeyToRemote === null) {
						// Direct link detach
						$aToBeRemove[] = $aData['data']['key'];
					} else {
						// Indirect link deletion
						foreach ($aData['data']['link_keys'] as $sKey) {
							$aToBeDelete[] = $sKey;
						}
					}
					break;
			}

		}

		return [
			'to_be_created' => $aToBeCreate,
			'to_be_deleted' => $aToBeDelete,
			'to_be_added'   => $aToBeAdd,
			'to_be_removed' => $aToBeRemove,
		];
	}

	/**
	 * Convert string representation of an orm linked set to object ormLinkSet.
	 *
	 * @param string $sValue
	 * @param \ormLinkSet $oOrmLinkSet
	 *
	 */
	static public function StringToOrmLinkSet(string $sValue, ormLinkSet $oOrmLinkSet)
	{
		try {
			$aItems = explode(" ", $sValue);
			foreach ($aItems as $sItem) {
				if (!empty($sItem)) {
					$oItem = MetaModel::GetObject($oOrmLinkSet->GetClass(), intval($sItem));
					if (!utils::ShowObsoleteData() && $oItem->IsObsolete()) {
						continue;
					}
					$oOrmLinkSet->AddItem($oItem);
				}
			}
		}
		catch (Exception $e) {
			ExceptionLog::LogException($e);
		}
	}
}