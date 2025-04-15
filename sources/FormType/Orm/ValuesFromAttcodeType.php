<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\FormType\Orm;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType as SymfonyChoiceType;
use Symfonycasts\DynamicForms\DependentField;

class ValuesFromAttcodeType extends AbstractType
{
	public function getParent()
	{
		return SymfonyChoiceType::class;
	}

	public static function BuildSubField(DependentField $oDependentField, string $sQuery, ?string $sGroupByAttCode, array $aFormOptions = []): void
	{
		if (is_null($sGroupByAttCode)) {
			$aFormOptions['choices'] = [];
		} else {
			$oModelReflection = new \ModelReflectionRuntime();
			$oQuery = $oModelReflection->GetQuery($sQuery);
			$sClass = $oQuery->GetClass();
			$oAttDef = \MetaModel::GetAttributeDef($sClass, $sGroupByAttCode);

			//$aFormOptions['inherit_data'] = true;
			$aFormOptions['choices'] = array_flip($oAttDef->GetAllowedValues() ?? []);
		}
		$aFormOptions['multiple'] = true;

		$oDependentField->add(ValuesFromAttcodeType::class, $aFormOptions);
	}

}