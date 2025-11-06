<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\Base;

use Combodo\iTop\Forms\Block\AbstractFormBlock;
use Combodo\iTop\Forms\Block\AbstractTypeFormBlock;
use Combodo\iTop\Forms\Block\FormBlockException;
use Combodo\iTop\Forms\Block\FormType\FormType;
use Combodo\iTop\Forms\FormBuilder\DependencyMap;
use Combodo\iTop\Forms\FormsException;
use Exception;
use ReflectionClass;

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
		catch (Exception $ex) {
			throw new FormsException('Unable to construct demonstrator form.', 0, $ex);
		}
	}

	/** @inheritdoc */
	public function GetFormType(): string
	{
		return FormType::class;
	}

	/** @inheritdoc */
	public function InitBlockOptions(array &$aUserOptions): void
	{
		parent::InitBlockOptions($aUserOptions);

		$aUserOptions['compound'] = true;
		$aUserOptions['attr'] = [
			'class' => 'form',
		];

	}

	/**
	 * Add a child form.
	 *
	 * @param string $sName block name
	 * @param string $sType block class name
	 * @param array $aOptions options
	 *
	 * @return $this
	 * @throws \ReflectionException
	 */
	public function Add(string $sName, string $sType, array $aOptions): AbstractFormBlock
	{
		$oRef = new ReflectionClass($sType);
		if($oRef->isSubclassOf(AbstractFormBlock::class) === false){
			throw new FormBlockException("The block type '$sType' is not a subclass of AbstractFormBlock.");
		}

		$aOptions['priority'] = -count($this->aChildrenBlocks);
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