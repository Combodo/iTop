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
 * Complex form type.
 *
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
		}
		catch (Exception $e) {
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
	 * @param string $sType block class name
	 * @param array $aSymfonyOptions options
	 * @param array $aOptions options
	 *
	 * @return $this
	 * @throws ReflectionException
	 * @throws \Combodo\iTop\Forms\Block\FormBlockException
	 */
	public function Add(string $sName, string $sType, array $aSymfonyOptions, array $aOptions = []): AbstractFormBlock
	{
		$oRef = new ReflectionClass($sType);
		if($oRef->isSubclassOf(AbstractFormBlock::class) === false){
			throw new FormBlockException("The block type '$sType' is not a subclass of AbstractFormBlock.");
		}

		$aOptions['priority'] = -count($this->aChildrenBlocks);
		$oSubFormBlock = new ($sType)($sName, $aSymfonyOptions, $aOptions);
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