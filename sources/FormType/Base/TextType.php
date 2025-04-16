<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\FormType\Base;

use Symfony\Component\Form\Extension\Core\Type\TextType as SymfonyTextType;

class TextType extends AbstractType
{
	public function getParent(): ?string
	{
		return SymfonyTextType::class;
	}

	public function getBlockPrefix(): string
	{
		return 'itop_text';
	}
}