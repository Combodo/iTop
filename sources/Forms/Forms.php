<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms;

use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\Form\FormFactoryBuilder;
use Symfony\Component\Form\FormFactoryBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * Plumbing for iTop custom form builder.
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
		return (new FormFactoryBuilder())
			->addExtension(new HttpFoundationExtension())
			->setResolvedTypeFactory(new ResolvedFormTypeFactory());
	}

	/**
	 * This class cannot be instantiated.
	 */
	private function __construct()
	{
	}
}