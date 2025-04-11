<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\FormType\Orm;

use Combodo\iTop\FormType\Base\HiddenType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType as SymfonyChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ValuesFromAttcodeType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('hidden', HiddenType::class, ['mapped' => false]);
		$sAttCodeType = $options['attcode_source'];
		$builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($sAttCodeType): void {
			$oForm = $event->getForm();
			$sAttCode = $oForm->getParent()->get($sAttCodeType)->get('selected')->getData();
			$this->BuildSubField($oForm, $sAttCode);
		});

		$builder->get('hidden')->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($sAttCodeType): void {
			$oForm = $event->getForm()->getParent();
			$sAttCode = $oForm->getParent()->get($sAttCodeType)->get('selected')->getData();
			$this->BuildSubField($oForm, $sAttCode);
		});
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		parent::configureOptions($resolver);
		$resolver->setRequired('attcode_source');
		$resolver->setAllowedTypes('attcode_source', 'string');
	}

	public function BuildSubField(FormInterface $oForm, string $sAttCode): void
	{
		$aData = $oForm->getParent()->getData();
		\IssueLog::Info('Form Data: '.var_export($aData, true));

		$sQuery = $aData['query'];
		$sClass = \DBSearch::FromOQL($sQuery)->GetClass();
		$oAttDef = \MetaModel::GetAttributeDef($sClass, $sAttCode);

		//$aFormOptions['inherit_data'] = true;
		$aFormOptions['choices'] = array_flip($oAttDef->GetAllowedValues());
		$aFormOptions['multiple'] = true;

		// create the field, this is similar the $builder->add()
		// field name, field type, field options
		$oForm->add('selected', SymfonyChoiceType::class, $aFormOptions);
	}

}