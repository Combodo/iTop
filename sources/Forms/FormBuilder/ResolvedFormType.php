<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\FormBuilder;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\ResolvedFormType as SymfonyResolvedFormType;
use Symfony\Component\Form\ResolvedFormTypeInterface;

/**
 * Symfony ResolvedFormType override to use our FormBuilder.
 *
 * @package Combodo\iTop\Forms\FormBuilder
 * @since 3.3.0
 */
class ResolvedFormType extends SymfonyResolvedFormType implements ResolvedFormTypeInterface
{
	protected function newBuilder(string $name, ?string $dataClass, FormFactoryInterface $factory, array $options): FormBuilderInterface
	{
		$builder = parent::newBuilder($name, $dataClass, $factory, $options);

		return new FormBuilder($builder);
	}
}
