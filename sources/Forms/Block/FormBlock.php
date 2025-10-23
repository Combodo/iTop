<?php

namespace Combodo\iTop\Forms\Block;

use Symfony\Component\Form\Extension\Core\Type\FormType;

class FormBlock extends AbstractFormBlock
{
	public const OUTPUT_VALUE = 'value';

	public function __construct(string $sName, array $aOptions = [])
	{
		$aOptions['form_block'] = $this;
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
		$this->AddOutput(new FormOutput(self::OUTPUT_VALUE, 'string'));
	}
}