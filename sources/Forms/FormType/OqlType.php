<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OqlType extends AbstractType
{
	public function getParent(): string
	{
		return TextType::class;
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefault('outputs', array(
			'selected_class' => function($oData) {
				if($oData === null)
					return null;
				// extract selected class
				preg_match('/SELECT\s+(\w+)/', $oData, $aMatches);
				return $aMatches[1] ?? null;
			}
		));
	}

}