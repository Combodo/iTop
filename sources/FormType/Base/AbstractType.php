<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\FormType\Base;

use Symfony\Component\Form\AbstractType as SymfonyAbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractType extends SymfonyAbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		parent::buildForm($builder, $options);
		if (isset($options['callback']) && isset($options['hook_type'])) {
			$builder->addEventListener($options['hook_type'], function (FormEvent $event) use ($options): void {
				\IssueLog::Info($event->getForm()->getName().' '.$options['hook_type']);
				call_user_func($options['callback'], $event);
			});
		}
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		parent::configureOptions($resolver);
		$resolver->setDefined('callback')
			->setAllowedTypes('callback', 'callable');
		$resolver->setDefined('hook_type')
			->setAllowedTypes('hook_type', 'string');
	}

	/**
	 * Called only when GetPrerequisites() is not null
	 * @param array $aUserOptions
	 * @param array $aModelData
	 *
	 * @return array|null null if field is not present
	 */
	public function BuildOptions(array $aUserOptions, array $aModelData): ?array
	{
		return null;
	}

	/**
	 * @param array $aUserOptions
	 *
	 * @return array|null null if not dynamic
	 */
	public function GetPrerequisites(array $aUserOptions): ?array
	{
		return null;
	}
}