<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\FormType\Orm;

use Combodo\iTop\Forms\FormType\Base\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType as SymfonyTextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QueryType extends AbstractType
{
	public function getParent(): ?string
	{
		return SymfonyTextareaType::class;
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		parent::configureOptions($resolver);
		$resolver->setDefault('attr', ['class' => 'ibo-is-code ibo-query-oql']);
	}

	public function getBlockPrefix(): string
	{
		return 'itop_query';
	}
}