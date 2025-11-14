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

/**
 * Collection form type.
 *
 */
class CollectionBlock extends AbstractTypeFormBlock
{
	// Inputs
	public const INPUT_CLASS_NAME = 'class_name';

	/** @var FormBlock block */
	protected AbstractTypeFormBlock $oPrototypeBlock;

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

		$oOptionsRegister->SetOption('prototype', true);
		$oOptionsRegister->SetOption('allow_add', true);
		$oOptionsRegister->SetOption('prototype_options', [
			'label' => false
		]);

		// not type options
		$oOptionsRegister->SetOption('block_entry_type', FormBlock::class, false);
		$oOptionsRegister->SetOption('block_entry_options', [], false);
	}

	/** @inheritdoc */
	protected function AfterOptionsRegistered(OptionsRegister $oOptionsRegister): void
	{
		parent::AfterOptionsRegistered($oOptionsRegister);

		$sBlockEntryType = $this->GetOption('block_entry_type');
		$sBlockEntryOptions = $this->GetOption('block_entry_options');
		$this->oPrototypeBlock = new ($sBlockEntryType)('prototype', $sBlockEntryOptions);

		$oOptionsRegister->SetOption('entry_type', $this->oPrototypeBlock->GetFormType());
		$oOptionsRegister->SetOption('entry_options', $this->oPrototypeBlock->GetOptions());
	}

}