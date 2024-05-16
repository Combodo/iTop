<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service\Links;

use Combodo\iTop\Core\MetaModel\FriendlyNameType;
use Combodo\iTop\Service\Base\ObjectRepository;
use Exception;
use ExceptionLog;
use iDBObjectSetIterator;
use MetaModel;
use utils;

/**
 * Class LinkSetRepository
 *
 *
 * @internal
 * @since 3.1.0
 * @package Combodo\iTop\Service\Links
 */
class LinkSetRepository
{

	/**
	 * Get list of remote objects information based on a linkSet
	 *
	 * @param iDBObjectSetIterator $oDbObjectSet Db object set
	 * @param bool $bForce options with force flag will be kept event if they don't be part of the current value of set
	 * @param array $aInitialOptions
	 * @param string $sTargetClass Target class name
	 * @param string|null $sTargetField Target field
	 *
	 * @return array|null
	 */
	static public function LinksDbSetToTargetObjectArray(iDBObjectSetIterator $oDbObjectSet, bool $bForce, array &$aInitialOptions, string $sTargetClass, string $sTargetField = null): ?array
	{
		try {

			// Retrieve friendly name complementary specification
			$aComplementAttributeSpec = MetaModel::GetNameSpec($sTargetClass, FriendlyNameType::COMPLEMENTARY);

			// Retrieve image attribute code
			$sObjectImageAttCode = MetaModel::GetImageAttributeCode($sTargetClass);

			// Prepare fields to load
			$aFieldsToLoad = ObjectRepository::GetDefaultFieldsToLoad($aComplementAttributeSpec, $sObjectImageAttCode);

			// Optimize columns load
			$oDbObjectSet->OptimizeColumnLoad([
				$sTargetClass => $aFieldsToLoad,
			]);

			// Iterate throw objects...
			$oDbObjectSet->Rewind();
			while ($oObject = $oDbObjectSet->Fetch()) {

				// Ignore obsolete data
				if (!utils::ShowObsoleteData() && $oObject->IsObsolete()) {
					continue;
				}

				// Prepare objet data
				$aObjectData = [];

				// Link keys
				$aObjectData['link_keys'] = [$oObject->GetKey()];

				// In case ot indirect link
				if ($sTargetField != null) {
					$oObject = MetaModel::GetObject($sTargetClass, $oObject->Get($sTargetField));
				}

				// Remote key
				$aObjectData['key'] = $oObject->GetKey();

				// force option
				$aObjectData['force'] = $bForce;

				// Fill loaded columns...
				foreach ($aFieldsToLoad as $sField) {
					$aObjectData[$sField] = $oObject->Get($sField);
				}

				// Compute others data
				$aInitialOptions[$oObject->GetKey()] = ObjectRepository::ComputeOthersData($oObject, $sTargetClass, $aObjectData, $aComplementAttributeSpec, $sObjectImageAttCode);
			}

			return $aInitialOptions;
		}
		catch (Exception $e) {

			ExceptionLog::LogException($e);

			return null;
		}
	}

}