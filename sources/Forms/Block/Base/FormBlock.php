<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\Base;

use Combodo\iTop\Forms\Block\AbstractFormBlock;
use Symfony\Component\Form\Extension\Core\Type\FormType;

/**
 * Complex form type.
 *
 */
class FormBlock extends AbstractFormBlock
{
	/** @var array form sub blocks */
	private array $aSubFormBlocks = [];

	/**
	 * Constructor.
	 *
	 * @param string $sName
	 * @param array $aOptions
	 */
	public function __construct(string $sName, array $aOptions = [])
	{
		parent::__construct($sName, $aOptions);

		// Build the form
		$this->BuildForm();
	}

	/** @inheritdoc  */
	public function GetFormType(): string
	{
		return FormType::class;
	}

	/** @inheritdoc  */
	public function InitOptions(): array
	{
		return [
			'compound' => true
		];
	}

	/**
	 * Add a sub form.
	 *
	 * @param AbstractFormBlock $oSubFormBlock
	 *
	 * @return $this
	 */
	public function AddSubFormBlock(AbstractFormBlock $oSubFormBlock): AbstractFormBlock
	{
		$this->aSubFormBlocks[] = $oSubFormBlock;
		return $this;
	}

	/**
	 * Get the sub forms.
	 *
	 * @return array
	 */
	public function GetSubFormBlocks(): array
	{
		return $this->aSubFormBlocks;
	}

	/**
	 * Build the form.
	 *
	 * @return void
	 */
	protected function BuildForm(): void
	{

	}

}