<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 */
class CollectionFormType extends AbstractType
{
	/** @inheritdoc */
	public function getParent(): string
	{
		return CollectionType::class;
	}


	public function configureOptions(OptionsResolver $resolver)
	{
		parent::configureOptions($resolver);

		$resolver->setDefined([
			'block_entry_type',
			'block_entry_options',
		]);
	}


}