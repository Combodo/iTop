<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\Base;

use Combodo\iTop\Forms\Block\AbstractTypeFormBlock;
use Combodo\iTop\Forms\FormType\Base\CollectionFormType;
use Combodo\iTop\Forms\IO\Format\ClassIOFormat;
use Combodo\iTop\Forms\Register\IORegister;
use Combodo\iTop\Forms\Register\OptionsRegister;
use Combodo\iTop\Forms\Register\RegisterException;

/**
 * A block to manage collections of form blocks.
 *
 * @package Combodo\iTop\Forms\Block\Base
 * @since 3.3.0
 */
class CollectionBlock extends AbstractTypeFormBlock
{
	// Inputs
	public const INPUT_CLASS_NAME = 'input_class_name';

	/** @inheritdoc */
	public function GetFormType(): string
	{
		return CollectionFormType::class;
	}

	/**
	 * Get the prototype block.
	 *
	 * @return AbstractTypeFormBlock
	 */
	public function GetPrototypeBlock(): AbstractTypeFormBlock
	{
		return $this->oPrototypeBlock;
	}

	/** @inheritdoc */
	protected function RegisterIO(IORegister $oIORegister): void
	{
		parent::RegisterIO($oIORegister);
		$oIORegister->AddInput(self::INPUT_CLASS_NAME, ClassIOFormat::class);
	}

	/** @inheritdoc */
	protected function RegisterOptions(OptionsRegister $oOptionsRegister): void
	{
		parent::RegisterOptions($oOptionsRegister);

		$oOptionsRegister->SetOption('entry_block', null, false);
		$oOptionsRegister->SetOption('prototype', true);
		$oOptionsRegister->SetOption('allow_add', true);
		$oOptionsRegister->SetOption('prototype_options', [
			'label' => false,
		]);
	}

	/** @inheritdoc */
	protected function AfterOptionsRegistered(OptionsRegister $oOptionsRegister): void
	{
		parent::AfterOptionsRegistered($oOptionsRegister);

		$oBlockEntryType = $this->GetOption('entry_block');

		try {
			$oOptionsRegister->SetOption('entry_type', $oBlockEntryType->GetFormType());
			$oOptionsRegister->SetOption('entry_options', $oBlockEntryType->GetOptions());
		} catch (RegisterException $e) {

		}

	}

}
