<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\Base;

use Combodo\iTop\Forms\Block\AbstractFormBlock;
use Combodo\iTop\Forms\Block\AbstractTypeFormBlock;
use Combodo\iTop\Forms\FormType\Base\CollectionFormType;
use Combodo\iTop\Forms\IO\Converter\CollectionToCountConverter;
use Combodo\iTop\Forms\IO\Format\IntegerIOFormat;
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
	/** @var AbstractTypeFormBlock Prototype block */
	private AbstractTypeFormBlock $oPrototypeBlock;
	public const OUTPUT_COUNT = 'count';

	/** @inheritdoc */
	public function GetFormType(): string
	{
		return CollectionFormType::class;
	}

	/**
	 * @return AbstractFormBlock
	 */
	public function GetPrototypeBlock(): AbstractFormBlock
	{
		return $this->oPrototypeBlock;
	}

	public function EntryDependsOnParent(string $sInputName, string $sParentInputName): AbstractFormBlock
	{
		$this->oPrototypeBlock->InputDependsOnParent($sInputName, $sParentInputName);

		return $this;
	}

	/** @inheritdoc */
	protected function RegisterIO(IORegister $oIORegister): void
	{
		parent::RegisterIO($oIORegister);
		$oIORegister->AddOutput(self::OUTPUT_COUNT, IntegerIOFormat::class, false, new CollectionToCountConverter());
	}

	/** @inheritdoc */
	protected function RegisterOptions(OptionsRegister $oOptionsRegister): void
	{
		parent::RegisterOptions($oOptionsRegister);

		$oOptionsRegister->SetOption('block_entry_type', FormBlock::class, false);
		$oOptionsRegister->SetOption('block_entry_options', [], false);
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

		$oBlockEntryType = $this->GetOption('block_entry_type');
		$oBlockEntryOptions = $this->GetOption('block_entry_options');
		$this->oPrototypeBlock = new $oBlockEntryType('prototype', array_merge($this->GetOption('prototype_options'), $oBlockEntryOptions));
		$this->oPrototypeBlock->SetParent($this);

		try {
			$oOptionsRegister->SetOption('entry_type', $this->oPrototypeBlock->GetFormType());
			$oOptionsRegister->SetOption('entry_options', $this->oPrototypeBlock->GetOptions());
		} catch (RegisterException $e) {

		}

	}

}
