<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\FormType\Attribute;

use Combodo\iTop\Forms\FormType\Base\AbstractType;
use Combodo\iTop\Forms\FormType\FormTypeException;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType as SymfonyChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use ValueSetObjects;

class ExternalKeyType extends AbstractType
{
	public function configureOptions(OptionsResolver $resolver)
	{
		parent::configureOptions($resolver);
		$resolver->setRequired('class');
		$resolver->setAllowedTypes('class', 'string');
	}

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$oModelReflection = new \ModelReflectionRuntime();
		$sClass = $options['class'];
		if (!$oModelReflection->IsValidClass($sClass)) {
			throw new FormTypeException("Unknown class $sClass");
		}
		$oValueSet = new ValueSetObjects("SELECT `$sClass`");
		$aUserOptions['choices'] = array_flip($oValueSet->GetValues([]));
		$aUserOptions['multiple'] = false;
		$aUserOptions['inherit_data'] = true;
		$builder->add('selected', SymfonyChoiceType::class, $aUserOptions);
		parent::buildForm($builder, $options);
	}

}