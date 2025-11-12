<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block;

use Combodo\iTop\Forms\Block\IO\Format\BooleanIOFormat;

abstract class AbstractTypeFormBlock extends AbstractFormBlock
{
	// Inputs
	public const INPUT_VISIBLE = 'visible';

	/**
	 * Return the form type.
	 *
	 * @return string
	 */
	abstract public function GetFormType(): string;

	/**
	 * Initialize inputs.
	 *
	 * @return void
	 */
	public function InitInputs(): void
	{
		parent::InitInputs();
		$this->AddInput(self::INPUT_VISIBLE, BooleanIOFormat::class);
	}

	/**
	 * @param string|null $sEventType
	 *
	 * @return bool
	 * @throws FormBlockException
	 */
	public function IsVisible(string $sEventType = null): bool
	{
		$oInput = $this->GetInput(self::INPUT_VISIBLE);
		if(!$oInput->IsBound()){
			return true;
		}

		$bVisible = $oInput->GetValue($sEventType);

		return $bVisible !== null && $bVisible->IsTrue();
	}

	/**
	 * @return true
	 */
	public function AllowAdd(string $sEventType = null): bool
	{
		return true;
	}
}