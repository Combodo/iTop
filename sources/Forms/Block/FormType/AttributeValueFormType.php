<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\FormType;

use MetaModel;
use Symfony\Component\Form\AbstractType;

class AttributeValueFormType extends AbstractType
{
	public function getParent(): string
	{
		return ChoiceFormType::class;
	}

	public static function GetOptionsFromInputs(array $inputs): array
	{
		$aValues = [];

		if (!empty($inputs['attribute'])) {
			$oAttDef = MetaModel::GetAttributeDef($inputs['object_class'], $inputs['attribute']);
			$aValues = $oAttDef->GetAllowedValues();
			$aValues = $aValues !== null ? array_combine($aValues, $aValues) : [];
		}

		return [
			'choices' => $aValues,
		];
	}
}