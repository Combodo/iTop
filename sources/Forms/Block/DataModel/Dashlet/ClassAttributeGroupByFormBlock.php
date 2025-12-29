<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\DataModel\Dashlet;

use Combodo\iTop\Forms\Block\DataModel\AttributeChoiceFormBlock;
use Combodo\iTop\Forms\Block\FormBlockException;
use Combodo\iTop\Forms\Register\OptionsRegister;
use Combodo\iTop\Service\DependencyInjection\DIService;
use Dict;
use Exception;

/**
 * A block to manage an attribute of a data model class for grouping purpose
 *
 * @package Combodo\iTop\Forms\Block\DataModel\Dashlet
 * @since 3.3.0
 */
class ClassAttributeGroupByFormBlock extends AttributeChoiceFormBlock
{
	/**
	 * @param \Combodo\iTop\Forms\Register\OptionsRegister $oOptionsRegister
	 *
	 * @throws \Combodo\iTop\Forms\Block\FormBlockException
	 * @throws \Combodo\iTop\Forms\Register\RegisterException
	 * @throws \Combodo\iTop\Service\DependencyInjection\DIException
	 */
	public function UpdateOptions(OptionsRegister $oOptionsRegister): void
	{
		parent::UpdateOptions($oOptionsRegister);
		$oModelReflection = DIService::GetInstance()->GetService('ModelReflection');

		$aGroupBy = [];
		try {
			$sClass = strval($this->GetInputValue(self::INPUT_CLASS_NAME));
			if ($oModelReflection->IsValidClass($sClass)) {
				foreach ($oModelReflection->ListAttributes($sClass) as $sAttCode => $sAttType) {
					// For external fields, find the real type of the target
					$sExtFieldAttCode = $sAttCode;
					$sTargetClass = $sClass;
					while (is_a($sAttType, 'AttributeExternalField', true)) {
						$sExtKeyAttCode = $oModelReflection->GetAttributeProperty($sTargetClass, $sExtFieldAttCode, 'extkey_attcode');
						$sTargetAttCode = $oModelReflection->GetAttributeProperty($sTargetClass, $sExtFieldAttCode, 'target_attcode');
						$sTargetClass = $oModelReflection->GetAttributeProperty($sTargetClass, $sExtKeyAttCode, 'targetclass');
						//					$aTargetAttCodes = AttributeChoiceFormBlock::ListAttributeCodesByCategory($sTargetClass, 'group_by');
						$sAttType = $sTargetAttCode;
						$sExtFieldAttCode = $sTargetAttCode;
					}

					$sLabel = $oModelReflection->GetLabel($sClass, $sAttCode);
					if (!in_array($sLabel, $aGroupBy)) {
						$aGroupBy[$sLabel] = $sAttCode;

						if (is_a($sAttType, 'AttributeDateTime', true)) {
							$aGroupBy[Dict::Format('UI:DashletGroupBy:Prop-GroupBy:Select-Hour', $sLabel)] = $sAttCode.':hour';
							$aGroupBy[Dict::Format('UI:DashletGroupBy:Prop-GroupBy:Select-Month', $sLabel)] = $sAttCode.':month';
							$aGroupBy[Dict::Format('UI:DashletGroupBy:Prop-GroupBy:Select-Year', $sLabel)] = $sAttCode.':year';
							$aGroupBy[Dict::Format('UI:DashletGroupBy:Prop-GroupBy:Select-DayOfWeek', $sLabel)] = $sAttCode.':day_of_week';
							$aGroupBy[Dict::Format('UI:DashletGroupBy:Prop-GroupBy:Select-DayOfMonth', $sLabel)] = $sAttCode.':day_of_month';
						}
					}
				}
				ksort($aGroupBy);
			}
		} catch (Exception $e) {
			throw new FormBlockException(__METHOD__.': block issue', 0, $e);
		}

		$oOptionsRegister->SetOption('choices', $aGroupBy);
	}
}
