<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\FormType\Orm;

use Combodo\iTop\Forms\FormType\Base\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType as SymfonyChoiceType;
use utils;

class ValuesFromAttcodeType extends AbstractType
{
	public function getParent()
	{
		return SymfonyChoiceType::class;
	}

	public function BuildOptions(array $aUserOptions, array $aModelData): ?array
	{
		$sAttCode = $aModelData[$aUserOptions['source_attcode']] ?? null;
		if (utils::IsNullOrEmptyString($sAttCode)) {
			return null;
		} else {
			$oModelReflection = new \ModelReflectionRuntime();
			$sClass = $aModelData[$aUserOptions['source_class']];
			if (! $oModelReflection->IsValidClass($sClass)) {
				try {
					$oQuery = $oModelReflection->GetQuery($sClass);
				} catch (\Exception $e) {
					return null;
				}
				$sClass = $oQuery->GetClass();
			}
			if (\MetaModel::IsValidAttCode($sClass, $sAttCode)) {
				$aAllowed = $oModelReflection->GetAllowedValues_att($sClass, $sAttCode);
				if (is_array($aAllowed))
				{
					$aValues = array_flip($aAllowed);
				} else {
					$aValues = [];
				}
				$aFormOptions['choices'] = $aValues;
			} else {
				$aFormOptions['choices'] = [];
			}
		}
		$aFormOptions['multiple'] = true;
		$aFormOptions['required'] = false;

		return $aFormOptions;
	}

	public function GetPrerequisites(array $aUserOptions): ?array
	{
		return [
			$aUserOptions['source_attcode'],
			$aUserOptions['source_class'],
		];
	}
}