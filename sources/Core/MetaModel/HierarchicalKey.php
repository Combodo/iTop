<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\MetaModel;

use CMDBSource;
use CoreException;
use MetaModel;

/**
 * Helper class to check and rebuild data integrity of hierarchical keys
 *
 * @author Eric Espie <eric.espie@combodo.com>
 * @package Combodo\iTop\Core\MetaModel
 * @since 3.0.0 N°2527
 */
class HierarchicalKey {

	/**
	 * Verify that an HK control information is correct
	 * @param $sClass
	 * @param $sAttCode
	 * @param $oAttDef
	 *
	 * @throws \CoreException thrown if not correct
	 * @throws \MySQLException
	 */
	public static function VerifyIntegrity($sClass, $sAttCode, $oAttDef)
	{
		$sTable = MetaModel::DBGetTable($sClass, $sAttCode);
		$sLeft = $oAttDef->GetSQLLeft();
		$sRight = $oAttDef->GetSQLRight();

		list($aControlByParent, $aControlById) = self::GetHierarchyControlData($sTable, $sAttCode, $sLeft, $sRight, $sLeft);

		// Get global boundaries
		$sSQL = "SELECT MAX(`$sRight`) AS MaxRight, MIN(`$sLeft`) as MinLeft FROM `$sTable`";
		$aRes = CMDBSource::QueryToArray($sSQL, MYSQLI_ASSOC);
		$aValues = $aRes[0];
		// fake super-root
		$aControlById[0] = [
			'ctrl_left'  => $aValues['MinLeft'] - 1, // to mimic the controls of a fake super-root
			'ctrl_right' => $aValues['MaxRight'] + 1,
		];
		foreach ($aControlByParent as $iParentId => $aTreeIds) {
			$iParentLeft = $aControlById[$iParentId]['ctrl_left'];
			$iParentRight = $aControlById[$iParentId]['ctrl_right'];
			$iMinLeft = $iParentLeft;

			foreach ($aTreeIds as $aValues) {
				$iChildLeft = $aValues['ctrl_left'];
				$iChildRight = $aValues['ctrl_right'];
				if ($iChildLeft <= $iMinLeft || $iParentRight <= $iChildRight || $iChildRight <= $iChildLeft) {
					throw new CoreException("Wrong HK control values");
				}
			}
		}
	}

	public static function Rebuild($sClass, $sAttCode, $oAttDef, $bForce = false)
	{
		$sTable = MetaModel::DBGetTable($sClass, $sAttCode);
		$sLeft = $oAttDef->GetSQLLeft();
		$sRight = $oAttDef->GetSQLRight();
		list($aControlByParent) = self::GetHierarchyControlData($sTable, $sAttCode, $sLeft, $sRight, 'id');

		$aEntriesToUpdate = [];

		$iCurrentControl = 1;
		// roots
		$aEntries = $aControlByParent[0];
		while (!empty($aEntries)) {
			// consider the first entry
			$aEntry = &$aEntries[0];
			if (!array_key_exists('new_left', $aEntry)) {
				// The node has never been seen, set its left control
				$aEntry['new_left'] = $iCurrentControl++;
				if (array_key_exists($aEntry['id'], $aControlByParent)) {
					// node has children, add them to the list
					$aChildren = $aControlByParent[$aEntry['id']];
					$aEntries = array_merge($aChildren, $aEntries);
					continue;
				}
			}
			// All the children have been computed, close the node
			$aEntry['new_right'] = $iCurrentControl++;
			if ($bForce || $aEntry['ctrl_left'] != $aEntry['new_left'] || $aEntry['ctrl_right'] != $aEntry['new_right']) {
				$aEntriesToUpdate[] = $aEntry;
			}
			// remove the entry
			array_shift($aEntries);
		}

		foreach ($aEntriesToUpdate as $aEntry) {
			$iLeft = $aEntry['new_left'];
			$iRight = $aEntry['new_right'];
			$iId = $aEntry['id'];
			$sSQL = "UPDATE `$sTable` SET `$sLeft` = $iLeft, `$sRight` = $iRight WHERE id= $iId";
			CMDBSource::Query($sSQL);
		}
	}

	/**
	 * @param string $sTable       database table
	 * @param string $sAttCode     parent field (hierarchy)
	 * @param string $sLeft        left field
	 * @param string $sRight       right field
	 *
	 * @return array[] $aControlById all the left and right by id and $aControlByParent same but organized by parent
	 * @throws \MySQLException
	 */
	private static function GetHierarchyControlData($sTable, $sAttCode, $sLeft, $sRight, $sOrderBy)
	{
		$sSQL = "SELECT id, `$sLeft` AS ctrl_left, `$sRight` AS ctrl_right, `$sAttCode` AS parent_id FROM `$sTable` WHERE 1 ORDER BY `$sOrderBy`";
		$aTreeIds = CMDBSource::QueryToArray($sSQL, MYSQLI_ASSOC);
		$aControlById = [];
		$aControlByParent = [];
		while (!empty($aTreeIds)) {
			$aTreeId = array_shift($aTreeIds);
			$aControlById[$aTreeId['id']] = $aTreeId;
			$aControlByParent[$aTreeId['parent_id']][] = $aTreeId;
		}
		return [$aControlByParent, $aControlById];
	}

}