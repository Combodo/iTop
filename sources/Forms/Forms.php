<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms;

use Combodo\iTop\Forms\FormBuilder\FormTypeExtension;
use Combodo\iTop\Forms\FormBuilder\ResolvedFormTypeFactory;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\FormFactoryBuilder;
use Symfony\Component\Form\FormFactoryBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Validator\Validation;

/**
 * Form.
 *
 * @package Combodo\iTop\Forms
 * @since 3.3.0
 */
final class Forms
{
	/**
	 * Creates a form factory with the iTop configuration.
	 */
	public static function createFormFactory(): FormFactoryInterface
	{
		return self::createFormFactoryBuilder()->getFormFactory();
	}

	/**
	 * Creates a form factory builder with the iTop configuration.
	 */
	public static function createFormFactoryBuilder(): FormFactoryBuilderInterface
	{
		// Set up the Validator component
		$validator = Validation::createValidatorBuilder()
			->enableAttributeMapping()->getValidator();

		return (new FormFactoryBuilder())
			->addExtension(new HttpFoundationExtension())
			->addExtension(new ValidatorExtension($validator))
			->addTypeExtension(new FormTypeExtension())
			->setResolvedTypeFactory(new ResolvedFormTypeFactory());
	}

	/**
	 * This class cannot be instantiated.
	 */
	private function __construct()
	{
	}
}
