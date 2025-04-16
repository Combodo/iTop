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
	    $resolver->setDefault('attr', ['class' => 'ibo-input ibo-input-select']);
    }

	public function BuildOptions(array $aUserOptions, array $aModelData): ?array
	{
		$sAttCode = $aModelData[$aUserOptions['source_attcode']] ?? null;
		if (utils::IsNullOrEmptyString($sAttCode)) {
			return null;
		} else {
			$oModelReflection = new \ModelReflectionRuntime();
			$sClass = $aModelData[$aUserOptions['source_class']];
			if (! $oModelReflection->IsValidClass($sClass)) {
				try {
					$oQuery = $oModelReflection->GetQuery($sClass);
				} catch (\Exception $e) {
					return null;
				}
				$sClass = $oQuery->GetClass();
			}
			if (\MetaModel::IsValidAttCode($sClass, $sAttCode)) {
				$aAllowed = $oModelReflection->GetAllowedValues_att($sClass, $sAttCode);
				if (is_array($aAllowed))
				{
					$aValues = array_flip($aAllowed);
				} else {
					$aValues = [];
				}
				$aFormOptions['choices'] = $aValues;
			} else {
				$aFormOptions['choices'] = [];
			}
		}
		$aFormOptions['multiple'] = true;
		$aFormOptions['required'] = false;

		return $aFormOptions;
	}

	public function GetPrerequisites(array $aUserOptions): ?array
	{
		return [
			$aUserOptions['source_class'],
			$aUserOptions['source_attcode'],
		];
	}
}