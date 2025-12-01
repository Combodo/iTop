<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\Base;

use Combodo\iTop\Forms\Block\AbstractFormBlock;
use Combodo\iTop\Forms\Block\AbstractTypeFormBlock;
use Combodo\iTop\Forms\Block\FormBlockException;
use Combodo\iTop\Forms\FormBuilder\DependencyMap;
use Combodo\iTop\Forms\FormsException;
use Combodo\iTop\Forms\FormType\Base\FormType;
use Combodo\iTop\Forms\Register\OptionsRegister;
use Exception;
use ReflectionClass;
use ReflectionException;

/**
 * A block to manage a form with children.
 *
 * @package Combodo\iTop\Forms\Block\Base
 * @since 3.3.0
 */
class FormBlock extends AbstractTypeFormBlock
{
	/** @var AbstractFormBlock[] children blocks */
	private array $aChildrenBlocks = [];
	public ?DependencyMap $oDependencyMap = null;

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
		} catch (FormsException $e) {
			throw $e;
		} catch (Exception $e) {
			throw new FormBlockException('Unable to construct form', 0, $e);
		}
	}

	/** @inheritdoc */
	public function GetFormType(): string
	{
		return FormType::class;
	}

	/** @inheritdoc */
	protected function RegisterOptions(OptionsRegister $oOptionsRegister): void
	{
		parent::RegisterOptions($oOptionsRegister);
		$oOptionsRegister->SetOption('compound', true);
		$oOptionsRegister->SetOptionArrayValue('attr', 'class', 'form');
	}

	/**
	 * Add a child form.
	 *
	 * @param string $sName block name
	 * @param string $sBlockClass block class name
	 * @param array $aOptions options
	 *
	 * @return $this
	 * @throws ReflectionException
	 * @throws FormBlockException
	 */
	public function Add(string $sName, string $sBlockClass, array $aOptions = []): AbstractFormBlock
	{
		$this->VerifyBlockName($sName);
		$this->VerifyBlockClassName($sBlockClass);

		$aOptions['priority'] = -count($this->aChildrenBlocks);
		$oSubFormBlock = new ($sBlockClass)($sName, $aOptions);
		$this->aChildrenBlocks[$sName] = $oSubFormBlock;
		$oSubFormBlock->SetParent($this);

		return $oSubFormBlock;
	}

	/**
	 * @param string $sBlockName
	 *
	 * @return void
	 * @throws FormBlockException
	 */
	private function VerifyBlockName(string $sBlockName): void
	{
		if (!ctype_alnum(str_replace(['-', '_'], '', $sBlockName))) {
			throw new FormBlockException("Block name '$sBlockName' is not valid. Only alphanumeric characters, hyphens and underscores are allowed.");
		}
	}

	/**
	 * @param string $sBlockClass
	 *
	 * @return void
	 * @throws FormBlockException
	 * @throws ReflectionException
	 */
	private function VerifyBlockClassName(string $sBlockClass): void
	{
		if (!is_a($sBlockClass, AbstractFormBlock::class, true)) {
			throw new FormBlockException('The block type '.json_encode($sBlockClass).' is not a subclass of '.json_encode(AbstractFormBlock::class));
		}
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
	 * @throws \Combodo\iTop\Forms\Block\FormBlockException
	 */
	public function Get(string $sName): AbstractFormBlock
	{
		if (!array_key_exists($sName, $this->aChildrenBlocks)) {
			throw new FormBlockException('Block does not exist '.json_encode($sName));
		}
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

	public function GetSubFormBlock(string $sBlockTurboTriggerName): ?AbstractFormBlock
	{
		$oBlock = $this;
		if (preg_match_all('/\[(?<level>[^\[]+)\]/', $sBlockTurboTriggerName, $aMatches)) {
			foreach ($aMatches['level'] as $level) {
				$oBlock = $oBlock->Get($level);
			}
		}

		return $oBlock;
	}

	public function GetDependenciesMap(): ?DependencyMap
	{
		return $this->oDependencyMap;
	}
}
