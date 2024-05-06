<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

/**
 * Class DBSearchHelper
 *
 * @since 3.0.0
 */
class DBSearchHelper
{
	/**
	 * Add context filter to DBUnionSearch
	 *
	 * @param \DBSearch|null $oSearch
	 *
	 * @throws \Exception
	 * @since 3.0.0
	 */
	public static function AddContextFilter(?DBSearch $oSearch): void
	{
		$oAppContext = new ApplicationContext();
		$sClass = $oSearch->GetClass();
		foreach ($oAppContext->GetNames() as $key) {
			// Find the value of the object corresponding to each 'context' parameter
			$aCallSpec = [$sClass, 'MapContextParam'];
			$sAttCode = '';
			if (is_callable($aCallSpec)) {
				$sAttCode = call_user_func($aCallSpec, $key); // Returns null when there is no mapping for this parameter
			}

			if (MetaModel::IsValidAttCode($sClass, $sAttCode)) {
				// Add Hierarchical condition if hierarchical key
				$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
				if (isset($oAttDef) && ($oAttDef->IsExternalKey())) {
					$iDefaultValue = intval($oAppContext->GetCurrentValue($key));
					if ($iDefaultValue != 0) {
						try {
							/** @var AttributeExternalKey $oAttDef */
							$sTargetClass = $oAttDef->GetTargetClass();
							$sHierarchicalKeyCode = MetaModel::IsHierarchicalClass($sTargetClass);
							if ($sHierarchicalKeyCode !== false) {
								$oFilter = new DBObjectSearch($sTargetClass);
								$oFilter->AddCondition('id', $iDefaultValue);
								$oHKFilter = new DBObjectSearch($sTargetClass);
								$oHKFilter->AddCondition_PointingTo($oFilter, $sHierarchicalKeyCode, TREE_OPERATOR_BELOW);
								$oSearch->AddCondition_PointingTo($oHKFilter, $sAttCode);
							}
						}
						catch (Exception $e) {
							// If filtering fails just ignore it
						}
					}
				}
			}
		}
	}
}