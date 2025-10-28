<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\Base;

use Combodo\iTop\Forms\Block\AbstractFormBlock;
use Combodo\iTop\Forms\FormsException;
use Symfony\Component\Form\Extension\Core\Type\FormType;

/**
 * Complex form type.
 *
 */
class FormBlock extends AbstractFormBlock
{
	/** @var array children blocks */
	private array $aChildrenBlocks = [];

	/**
	 * Constructor.
	 *
	 * @param string $sName block name
	 * @param array $aOptions options
	 *
	 * @throws FormsException
	 */
	public function __construct(string $sName, array $aOptions = [])
	{
		parent::__construct($sName, $aOptions);

		try {
			// Build the form
			$this->BuildForm();
		}
		catch (Exception $ex) {
			throw new FormsException('Unable to construct demonstrator form.', 0, $ex);
		}
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
	 * Add a child form.
	 *
	 * @param string $sType block class name
	 * @param string $sName block name
	 * @param array $aOptions options
	 *
	 * @return $this
	 */
	public function Add(string $sType, string $sName, array $aOptions): AbstractFormBlock
	{
		$oSubFormBlock = new ($sType)($sName, $aOptions);
		$this->aChildrenBlocks[$sName] = $oSubFormBlock;
		$oSubFormBlock->SetParent($this);
		return $oSubFormBlock;
	}

	/**
	 * Get the children forms.
	 *
	 * @return array
	 */
	public function GetChildren(): array
	{
		return $this->aChildrenBlocks;
	}

	/**
	 * Return a child block.
	 *
	 * @param string $sName name of the block
	 *
	 * @return AbstractFormBlock
	 */
	public function Get(string $sName): AbstractFormBlock
	{
		return $this->aChildrenBlocks[$sName];
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