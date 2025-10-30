<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OqlFormType extends AbstractType
{
	/** @inheritdoc  */
	public function getParent(): string
	{
		return TextareaType::class;
	}

	/** @inheritdoc  */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefault('help', 'An OQL query expression');

		$resolver->setDefault('attr', [
			'placeholder' => 'SELECT Contact',
		]);

		$resolver->setDefault('outputs', array(
			'selected_class' => function ($oData) {
				if ($oData === null) {
					return null;
				}
				// extract selected class
				preg_match('/SELECT\s+(\w+)/', $oData, $aMatches);

				return $aMatches[1] ?? null;
			},
		));

		$resolver->setDefined('with_ai_button');
	}

	/** @inheritdoc  */
	public function buildView(FormView $view, FormInterface $form, array $options): void
	{
		parent::buildView($view, $form, $options);

		$view->vars['with_ai_button'] = $options['with_ai_button'];
	}
}