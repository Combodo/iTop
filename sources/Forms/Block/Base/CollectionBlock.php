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

	/** @inheritdoc  */
	public function InitBlockOptions(array &$aUserOptions): void
	{
		parent::InitBlockOptions($aUserOptions);

		// Convert block information in type information
		if(isset($aUserOptions['block_entry_type'])) {
			$sBlockEntryType = $aUserOptions['block_entry_type'];
			$sBlockEntryOptions = $this->aUserOptions['block_entry_options'];
			$oBlock = new ($sBlockEntryType)('prototype', $sBlockEntryOptions);
			unset($aUserOptions['block_entry_type']);
			unset($aUserOptions['block_entry_options']);
			$aUserOptions['entry_type'] = $oBlock->GetFormType();
			$aUserOptions['entry_options'] = $oBlock->GetOptions();

			$aUserOptions['prototype'] = true;
			$aUserOptions['allow_add'] = true;
			$aUserOptions['prototype_options']  = [
				'label' => false
			];
		}
	}

}