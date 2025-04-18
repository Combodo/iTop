<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms;

use Combodo\iTop\Forms\FormType\Base\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\ResolvedFormType as SymfonyResolvedFormType;
use Symfony\Component\Form\ResolvedFormTypeInterface;


/**
 * Plumbing for iTop custom form builder.
 */
class ResolvedFormType extends SymfonyResolvedFormType implements ResolvedFormTypeInterface
{
	/**
	 * Creates a new builder instance.
	 *
	 * Override this method if you want to customize the builder class.
	 */
	protected function newBuilder(string $name, ?string $dataClass, FormFactoryInterface $factory, array $options): FormBuilderInterface
	{
		$builder = parent::newBuilder($name, $dataClass, $factory, $options);

		// Wrap the builder in a DynamicFormBuilder
		return new FormBuilder($builder);
	}
}