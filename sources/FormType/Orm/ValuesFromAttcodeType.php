<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\FormType\Orm;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType as SymfonyChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use utils;

class ValuesFromAttcodeType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
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

	public function getParent()
	{
		return SymfonyChoiceType::class;
	}

	public function configureOptions(OptionsResolver $resolver)
    {
	    parent::configureOptions($resolver);
    }

	public static function BuildSubField(FormInterface $oForm, string $sName, array $aData, array $aFormOptions = []): void
	{
		\IssueLog::Info("ValuesFromAttcodeType BuildSubField data: ".var_export($aData, true));


		if (utils::IsNullOrEmptyString($aData['group_by'] ?? null)) {
			return;
		} else {
			$oModelReflection = new \ModelReflectionRuntime();
			$oQuery = $oModelReflection->GetQuery($aData['query']);
			$sClass = $oQuery->GetClass();
			$sAttCode = $aData['group_by'];
			if (\MetaModel::IsValidAttCode($sClass, $sAttCode)) {
				$oAttDef = \MetaModel::GetAttributeDef($sClass, $sAttCode);

				$aFormOptions['choices'] = array_flip($oAttDef->GetAllowedValues() ?? []);
			} else {
				$aFormOptions['choices'] = [];
			}
		}
		$aFormOptions['multiple'] = true;
		$aFormOptions['required'] = false;

		$oForm->add($sName, ValuesFromAttcodeType::class, $aFormOptions);
	}

}