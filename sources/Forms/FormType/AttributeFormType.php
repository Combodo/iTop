<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\FormType;

use Symfony\Component\Form\AbstractType;

class AttributeFormType extends AbstractType
{
	public function getParent(): string
	{
		return ChoiceFormType::class;
	}

}