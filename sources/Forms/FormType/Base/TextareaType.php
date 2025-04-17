<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\FormType\Base;

use Symfony\Component\Form\Extension\Core\Type\TextareaType as SymfonyTextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextareaType extends AbstractType
{
	public function getParent(): ?string
	{
		return SymfonyTextareaType::class;
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		parent::configureOptions($resolver);
	}

	public function getBlockPrefix(): string
	{
		return 'itop_textarea';
	}
}