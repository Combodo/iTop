<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\FormType\Base;

use Combodo\iTop\Forms\Block\AbstractTypeFormBlock;
use Combodo\iTop\Forms\Block\Base\FormBlock;
use Combodo\iTop\Forms\FormType\FormTypeHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form type.
 *
 * @package Combodo\iTop\Forms\FormType\Base
 * @since 3.3.0
 */
class FormType extends AbstractType
{
	/** @inheritdoc */
	public function getParent(): string
	{
		return \Symfony\Component\Form\Extension\Core\Type\FormType::class;
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		parent::configureOptions($resolver);

		$resolver->setDefault('display', 'cosy');
	}

	/** @inheritdoc */
	public function buildView(FormView $view, FormInterface $form, array $options): void
	{
		parent::buildView($view, $form, $options);

		$view->vars['blocks'] = $this->GetChildrenBlocks($options['form_block'], $form);
		$view->vars['display'] = $options['display'];
	}

	/**
	 * @param FormBlock $oBlock
	 * @param FormInterface $form
	 *
	 * @return array
	 */
	private function GetChildrenBlocks(FormBlock $oBlock, FormInterface $form): array
	{
		$aData = [];
		foreach ($oBlock->GetChildren() as $oChild) {
			if (!$oChild instanceof AbstractTypeFormBlock) {
				continue;
			}

			if ($oChild->IsAdded()) {
				$aData[] = [
					'name'  => $oChild->GetName(),
					'added' => $oChild->IsAdded(),
					'id'    => FormTypeHelper::GetFormId($form).'_'.$oChild->GetName(),
				];
			} else {
				$aData[] = [
					'name'  => $oChild->GetName(),
					'added' => $oChild->IsAdded(),
					'id'    => FormTypeHelper::GetFormId($form).'_'.$oChild->GetName(),
				];
			}

		}

		return $aData;
	}

}
