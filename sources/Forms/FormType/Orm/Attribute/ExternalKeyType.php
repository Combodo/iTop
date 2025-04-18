<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\FormType\Orm\Attribute;

use Combodo\iTop\Forms\FormType\Base\AbstractType;
use Combodo\iTop\Forms\FormType\FormTypeException;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType as SymfonyChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use ValueSetObjects;

class ExternalKeyType extends AbstractType
{
	public function getParent()
	{
		return SymfonyChoiceType::class;
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		parent::configureOptions($resolver);
	}

	public function BuildOptions(array $aUserOptions, array $aModelData = []): ?array
	{
		$oModelReflection = new \ModelReflectionRuntime();
		$sClass = $aUserOptions['class'];
		if (!$oModelReflection->IsValidClass($sClass)) {
			throw new FormTypeException("Unknown class $sClass");
		}
		$oValueSet = new ValueSetObjects("SELECT `$sClass`");
		$aOptions['choices'] = array_flip($oValueSet->GetValues([]));
		$aOptions['multiple'] = false;

		return $aOptions;
	}

}