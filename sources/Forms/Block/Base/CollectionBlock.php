<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\Base;

use Combodo\iTop\Forms\Block\AbstractTypeFormBlock;
use Combodo\iTop\Forms\Block\FormType\CollectionFormType;
use Combodo\iTop\Forms\Block\IO\Format\ClassIOFormat;

/**
 * Collection form type.
 *
 */
class CollectionBlock extends AbstractTypeFormBlock
{
	// Inputs
	public const INPUT_CLASS_NAME = 'class_name';

	/**
	 * Constructor.
	 *
	 * @param string $sName
	 * @param array $aOptions
	 */
	public function __construct(string $sName, array $aOptions = [])
	{
		parent::__construct($sName, $aOptions);
	}

	/** @inheritdoc */
	public function GetFormType(): string
	{
		return CollectionFormType::class;
	}

	/** @inheritdoc */
	public function InitInputs(): void
	{
		parent::InitInputs();
		$this->AddInput(self::INPUT_CLASS_NAME, ClassIOFormat::class);
	}

	protected $oBlock;

	public function GetOptions(): array
	{
		$aOptions = parent::GetOptions();

		// Convert block information in type information
		if(isset($aOptions['block_entry_type'])) {
			$aOptions['prototype'] = true;
			$aOptions['allow_add'] = true;
			$aOptions['prototype_options']  = [
				'label' => false
			];
		}

		$sBlockEntryType = $aOptions['block_entry_type'];
		$sBlockEntryOptions = $aOptions['block_entry_options'];
		$this->oBlock = new ($sBlockEntryType)('prototype', $sBlockEntryOptions);

//		$this->HandleBlockDependencies();
//		$this->oBlock->SetParent($this->GetParent());
//		$oBlock->DependsOn('company', 'company', AbstractFormBlock::OUTPUT_VALUE);

		unset($aOptions['block_entry_type']);
		unset($aOptions['block_entry_options']);
		$aOptions['entry_type'] = $this->oBlock->GetFormType();
		$aOptions['entry_options'] = $this->oBlock->GetOptions();

		return $aOptions;
	}

	public function HandleBlockDependencies(): void
	{

	}

}