<?php

namespace Combodo\iTop\Forms\Block;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;

class FormBlock extends AbstractFormBlock
{
	public function __construct(string $sName, array $aOptions = [])
	{
		parent::__construct($sName, $aOptions);
	}


	public function GetFormType(): string
	{
		return FormType::class;
	}

	public function InitInputs(): void
	{
	}

	public function InitOutputs(): void
	{
	}

	public function Build(FormBuilderInterface $oBuilder): FormInterface
	{
		foreach ($this->GetSubFormBlocks() as $oSubForm) {
			$oBuilder->add($oSubForm->GetName(), $oSubForm->GetFormType(), $oSubForm->GetOptions());
		}

		return $oBuilder->getForm();
	}
}