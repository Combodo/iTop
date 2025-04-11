<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\FormType\Orm;

use Combodo\iTop\FormType\Base\HiddenType;
use Dict;
use Exception;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType as SymfonyChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttCodeGroupByType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('hidden', HiddenType::class, ['mapped' => false]);
		$sRelatedNode = $options['query_source'];
		$builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($sRelatedNode): void {
			$oForm = $event->getForm();
			$sCurrentValue = $oForm->getParent()->get($sRelatedNode)->getData();
			$this->BuildSubField($oForm, $sCurrentValue);
		});

		$builder->get('hidden')->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($sRelatedNode): void {
			$oForm = $event->getForm()->getParent();
			$sCurrentValue = $oForm->getParent()->get($sRelatedNode)->getData();
			$this->BuildSubField($oForm, $sCurrentValue);
		});
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		parent::configureOptions($resolver);
		$resolver->setRequired('query_source');
		$resolver->setAllowedTypes('query_source', 'string');
	}

	public function BuildSubField(FormInterface $oForm, string $sQuery): void
	{
		$aData = $oForm->getParent()->getData();
		\IssueLog::Info('Form Data: '.var_export($aData, true));

		//$aFormOptions['inherit_data'] = true;
		$aFormOptions['choices'] = $this->GetGroupByOptions($sQuery);
		$aFormOptions['multiple'] = false;

		// create the field, this is similar the $builder->add()
		// field name, field type, field options
		$oForm->add('selected', SymfonyChoiceType::class, $aFormOptions);
	}

	protected $oModelReflection;

	protected function GetGroupByOptions($sOql)
	{
		$this->oModelReflection = new \ModelReflectionRuntime();

		$aGroupBy = array();
		try
		{
			$oQuery = $this->oModelReflection->GetQuery($sOql);
			$sClass = $oQuery->GetClass();
			foreach($this->oModelReflection->ListAttributes($sClass) as $sAttCode => $sAttType)
			{
				// For external fields, find the real type of the target
				$sExtFieldAttCode = $sAttCode;
				$sTargetClass = $sClass;
				while (is_a($sAttType, 'AttributeExternalField', true))
				{
					$sExtKeyAttCode = $this->oModelReflection->GetAttributeProperty($sTargetClass, $sExtFieldAttCode, 'extkey_attcode');
					$sTargetAttCode = $this->oModelReflection->GetAttributeProperty($sTargetClass, $sExtFieldAttCode, 'target_attcode');
					$sTargetClass = $this->oModelReflection->GetAttributeProperty($sTargetClass, $sExtKeyAttCode, 'targetclass');
					$aTargetAttCodes = $this->oModelReflection->ListAttributes($sTargetClass);
					$sAttType = $aTargetAttCodes[$sTargetAttCode];
					$sExtFieldAttCode = $sTargetAttCode;
				}

				$aForbidenAttType = [
					'AttributeLinkedSet',
					'AttributeFriendlyName',

					'iAttributeNoGroupBy', //we cannot only use iAttributeNoGroupBy since this method is also used by the designer who do not have access to the classes' PHP reflection API. So the known classes has to be listed altogether
					'AttributeOneWayPassword',
					'AttributeEncryptedString',
					'AttributePassword',
				];
				foreach ($aForbidenAttType as $sForbidenAttType) {
					if (is_a($sAttType, $sForbidenAttType, true))
					{
						continue 2;
					}
				}

				$sLabel = $this->oModelReflection->GetLabel($sClass, $sAttCode);
				if (!in_array($sLabel, $aGroupBy))
				{
					$aGroupBy[$sAttCode] = $sLabel;

					if (is_a($sAttType, 'AttributeDateTime', true))
					{
						$aGroupBy[$sAttCode.':hour'] = Dict::Format('UI:DashletGroupBy:Prop-GroupBy:Select-Hour', $sLabel);
						$aGroupBy[$sAttCode.':month'] = Dict::Format('UI:DashletGroupBy:Prop-GroupBy:Select-Month', $sLabel);
						$aGroupBy[$sAttCode.':day_of_week'] = Dict::Format('UI:DashletGroupBy:Prop-GroupBy:Select-DayOfWeek', $sLabel);
						$aGroupBy[$sAttCode.':day_of_month'] = Dict::Format('UI:DashletGroupBy:Prop-GroupBy:Select-DayOfMonth', $sLabel);
					}
				}
			}
			asort($aGroupBy);
		}
		catch (Exception $e)
		{
			// Fallback in case of OQL problem
		}
		return array_flip($aGroupBy);
	}
}