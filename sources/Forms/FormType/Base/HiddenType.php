<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\FormType\Base;

use Symfony\Component\Form\Extension\Core\Type\HiddenType as SymfonyHiddenType;

class HiddenType extends AbstractType
{
	public function getParent(): ?string
	{
		return SymfonyHiddenType::class;
	}

	public function getBlockPrefix(): string
	{
		return 'itop_hidden';
	}
}