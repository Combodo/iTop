<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\FormType\Orm;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType as SymfonyTextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QueryType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		parent::buildForm($builder, $options);
		$builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
			\IssueLog::Info($event->getForm()->getName().' PRE_SET_DATA');
		});
		$builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
			\IssueLog::Info($event->getForm()->getName().' POST_SET_DATA');
		});
		$builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
			\IssueLog::Info($event->getForm()->getName().' PRE_SUBMIT');
		});
		$builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
			\IssueLog::Info($event->getForm()->getName().' POST_SUBMIT');
		});
	}

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