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

	/** @inheritdoc */
	public function buildView(FormView $view, FormInterface $form, array $options)
	{
		parent::buildView($view, $form, $options);

		/** @var FormBlock $oBlock */
		$oBlock = $options['form_block'];

		$aData = [];
		foreach ($oBlock->GetChildren() as $oChild) {
			if (!$oChild instanceof AbstractTypeFormBlock) {
				continue;
			}

			if ($oChild->IsAdded()) {
				$aData[] = [
					'name' => $oChild->GetName(),
					'added' => $oChild->IsAdded(),
					'id' => FormTypeHelper::GetFormId($form).'_'.$oChild->GetName(),
				];
			} else {
				$aData[] = [
					'name' => $oChild->GetName(),
					'added' => $oChild->IsAdded(),
					'id' => FormTypeHelper::GetFormId($form).'_'.$oChild->GetName(),
				];
			}

		}
		$view->vars['blocks'] = $aData;
	}

}
