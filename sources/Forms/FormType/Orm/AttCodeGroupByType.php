<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\FormType\Orm;

use Combodo\iTop\Forms\FormType\Base\AbstractType;
use Dict;
use Exception;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType as SymfonyChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttCodeGroupByType extends AbstractType
{
	public function getParent()
	{
		return SymfonyChoiceType::class;
	}

	public function BuildOptions(array $aUserOptions, array $aModelData = []): ?array
	{
		$oModelReflection = new \ModelReflectionRuntime();
		$sClassOrOql = $aModelData[$aUserOptions['source_class']];
		if ($oModelReflection->IsValidClass($sClassOrOql)) {
			$sClass = $sClassOrOql;
		} else {
			try {
				$oQuery = $oModelReflection->GetQuery($sClassOrOql);
			} catch (Exception $e) {
				return null;
			}
			$sClass = $oQuery->GetClass();
		}
		$aFormOptions['choices'] = $this->GetGroupByOptions($sClass);
		$aFormOptions['multiple'] = false;

		return $aFormOptions;
	}

	public function ConfigureDynamicOptions(OptionsResolver $oResolver)
	{
		$oResolver->setRequired('source_class');
		$oResolver->setAllowedTypes('source_class', 'string');
	}

	public function GetPrerequisites(array $aUserOptions): ?array
	{
		return [$aUserOptions['source_class']];
	}

	protected function GetGroupByOptions($sClassOrOql)
	{
		$oModelReflection = new \ModelReflectionRuntime();

		$aGroupBy = array();
		try
		{
			if ($oModelReflection->IsValidClass($sClassOrOql)) {
				$sClass = $sClassOrOql;
			} else {
				$oQuery = $oModelReflection->GetQuery($sClassOrOql);
				$sClass = $oQuery->GetClass();
			}
			foreach($oModelReflection->ListAttributes($sClass) as $sAttCode => $sAttType)
			{
				// For external fields, find the real type of the target
				$sExtFieldAttCode = $sAttCode;
				$sTargetClass = $sClass;
				while (is_a($sAttType, 'AttributeExternalField', true))
				{
					$sExtKeyAttCode = $oModelReflection->GetAttributeProperty($sTargetClass, $sExtFieldAttCode, 'extkey_attcode');
					$sTargetAttCode = $oModelReflection->GetAttributeProperty($sTargetClass, $sExtFieldAttCode, 'target_attcode');
					$sTargetClass = $oModelReflection->GetAttributeProperty($sTargetClass, $sExtKeyAttCode, 'targetclass');
					$aTargetAttCodes = $oModelReflection->ListAttributes($sTargetClass);
					$sAttType = $aTargetAttCodes[$sTargetAttCode];
					$sExtFieldAttCode = $sTargetAttCode;
				}

				$aForbiddenAttType = [
					'AttributeLinkedSet',
					'AttributeFriendlyName',

					'iAttributeNoGroupBy', //we cannot only use iAttributeNoGroupBy since this method is also used by the designer who do not have access to the classes' PHP reflection API. So the known classes has to be listed altogether
					'AttributeOneWayPassword',
					'AttributeEncryptedString',
					'AttributePassword',
				];
				foreach ($aForbiddenAttType as $sForbiddenAttType) {
					if (is_a($sAttType, $sForbiddenAttType, true))
					{
						continue 2;
					}
				}

				$sLabel = $oModelReflection->GetLabel($sClass, $sAttCode);
				if (!in_array($sLabel, $aGroupBy))
				{
					$aGroupBy[$sAttCode] = $sLabel;

					if (is_a($sAttType, 'AttributeDateTime', true))
					{
						$aGroupBy[$sAttCode.':hour'] = Dict::Format('UI:DashletGroupBy:Prop-GroupBy:Select-Hour', $sLabel);
						$aGroupBy[$sAttCode.':month'] = Dict::Format('UI:DashletGroupBy:Prop-GroupBy:Select-Month', $sLabel);
						$aGroupBy[$sAttCode.':day_of_week'] = Dict::Format('UI:DashletGroupBy:Prop-GroupBy:Select-DayOfWeek', $sLabel);
						$aGroupBy[$sAttCode.':day_of_month'] = Dict::Format('UI:DashletGroupBy:Prop-GroupBy:Select-DayOfMonth', $sLabel);
					}
				}
			}
			asort($aGroupBy);
		}
		catch (Exception $e)
		{
			// Fallback in case of OQL problem
		}
		return array_flip($aGroupBy);
	}
}