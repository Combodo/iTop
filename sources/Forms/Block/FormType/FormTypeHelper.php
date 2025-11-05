<?php

namespace Combodo\iTop\Forms\Block\FormType;

use Symfony\Component\Form\FormInterface;

class FormTypeHelper
{
	public static function buildFormTypeFullPath(FormInterface $form): string
	{
		$names = [];
		$current = $form;
		while ($current !== null) {
			$names[] = $current->getName();
			$current = $current->getParent();
		}
		return implode('_', array_reverse($names));
	}
}