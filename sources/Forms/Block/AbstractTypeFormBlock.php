<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block;

use Combodo\iTop\Forms\IO\Format\BooleanIOFormat;
use Combodo\iTop\Forms\Register\IORegister;

abstract class AbstractTypeFormBlock extends AbstractFormBlock
{
	// Inputs
	public const INPUT_VISIBLE = 'visible';
	public const INPUT_ENABLE = 'enable';

	/** @var bool flag indicating the form insertion */
	private bool $bIsAddedToForm = false;

	/**
	 * Return the form type.
	 *
	 * @return string
	 */
	abstract public function GetFormType(): string;

	/** @inheritdoc */
	protected function RegisterIO(IORegister $oIORegister): void
	{
		parent::RegisterIO($oIORegister);
		$oIORegister->AddInput(self::INPUT_VISIBLE, BooleanIOFormat::class);
		$oIORegister->AddInput(self::INPUT_ENABLE, BooleanIOFormat::class);
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

	/**
	 * The block has been added to its parent.
	 *
	 * @return bool
	 */
	public function IsAdded(): bool
	{
		return $this->bIsAddedToForm;
	}

	/**
	 * Indicate that the block has been added to its parent.
	 *
	 * @param bool $bIsAdded
	 *
	 * @return void
	 */
	public function SetAdded(bool $bIsAdded): void
	{
		$this->bIsAddedToForm = $bIsAdded;
	}
}