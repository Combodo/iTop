<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\Base;

use Combodo\iTop\Forms\Block\AbstractFormBlock;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

/**
 * Collection form type.
 *
 */
class CollectionBlock extends AbstractFormBlock
{

	public function __construct(string $sName, array $aOptions = [])
	{
		parent::__construct($sName, $aOptions);

	}

	/** @inheritdoc  */
	public function GetFormType(): string
	{
		return CollectionType::class;
	}

	/** @inheritdoc  */
	public function InitOptions(): array
	{
		$sBlockEntryType = $this->GetOptions()['block_entry_type'];
		$sBlockEntryOptions = $this->GetOptions()['block_entry_options'];

		$this->aOptions = [];

		$oBlock = new ($sBlockEntryType)('prototype', $sBlockEntryOptions);

		return [
			'entry_type' => $oBlock->GetFormType()
		];

	}

}