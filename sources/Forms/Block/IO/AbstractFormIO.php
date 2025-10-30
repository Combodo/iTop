<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\IO;

use Combodo\iTop\Forms\Block\AbstractFormBlock;
use Symfony\Component\Form\FormEvents;

/**
 *
 */
class AbstractFormIO
{
	/** @var AbstractFormBlock The owner block */
	private AbstractFormBlock $oOwnerBlock;

	/** @var string Name of the IO */
	private string $sName;

	/** @var string Type of the IO data */
	private string $sType;

	/** @var array Stored values */
	private array $aValues = [];

	/** @var FormBinding|null */
	private FormBinding|null $oBinding = null;

	/** @var array bindings pointing to other inputs */
	protected array $aBindingsToInputs = [];

	/**
	 * Constructor.
	 *
	 * @param string $sName name of the IO
	 * @param string $sType type of the IO
	 */
	public function __construct(string $sName, string $sType)
	{
		$this->sName = $sName;
		$this->sType = $sType;
	}

	/**
	 * Get the owner block.
	 *
	 * @return AbstractFormBlock
	 */
	public function GetOwnerBlock(): AbstractFormBlock
	{
		return $this->oOwnerBlock;
	}

	/**
	 * Set the owner block.
	 *
	 * @param AbstractFormBlock $oOwnerBlock
	 *
	 * @return $this
	 */
	public function SetOwnerBlock(AbstractFormBlock $oOwnerBlock): self
	{
		$this->oOwnerBlock = $oOwnerBlock;

		return $this;
	}

	/**
	 * Get the IO name.
	 *
	 * @return string
	 */
	public function GetName(): string
	{
		return $this->sName;
	}

	/**
	 * Set the IO name.
	 *
	 * @param string $sName
	 *
	 * @return self
	 */
	public function SetName(string $sName): self
	{
		$this->sName = $sName;

		return $this;
	}

	/**
	 * Get the IO data type.
	 *
	 * @return string
	 */
	public function GetDataType(): string
	{
		return $this->sType;
	}

	/**
	 * Set the IO value.
	 *
	 * @param string $sEventType
	 * @param mixed $oValue
	 *
	 * @return self
	 */
	public function SetValue(string $sEventType, mixed $oValue): self
	{
		$this->aValues[$sEventType] = $oValue;

		return $this;
	}

	/**
	 * Get the IO value.
	 *
	 * @param string $sEventType
	 *
	 * @return mixed
	 */
	public function GetValue(string $sEventType): mixed
	{
		return $this->aValues[$sEventType] ?? null;
	}

	/**
	 * Return true if value exist.
	 *
	 * @return bool
	 */
	public function HasValue(): bool
	{
		$PostSetDataExist = array_key_exists(FormEvents::POST_SET_DATA, $this->aValues) && $this->aValues[FormEvents::POST_SET_DATA] !== null;
		$PostSubmitExist = array_key_exists(FormEvents::POST_SUBMIT, $this->aValues) && $this->aValues[FormEvents::POST_SUBMIT] !== null;

		return $PostSetDataExist || $PostSubmitExist;
	}

	/**
	 * Return all values.
	 *
	 * @return array
	 */
	public function GetValues(): array
	{
		return $this->aValues;
	}

	/**
	 * Set the IO values.
	 *
	 * @param array $aValues
	 *
	 * @return $this
	 */
	public function SetValues(array $aValues): self
	{
		$this->aValues = $aValues;

		return $this;
	}

	/**
	 * Get the most relevant value.
	 *
	 * @return mixed
	 */
	public function Value(): mixed
	{
		if (array_key_exists(FormEvents::POST_SUBMIT, $this->aValues)) {
			return $this->aValues[FormEvents::POST_SUBMIT];
		}
		if (array_key_exists(FormEvents::POST_SET_DATA, $this->aValues)) {
			return $this->aValues[FormEvents::POST_SET_DATA];
		}

		return null;
	}

	/**
	 * Bind to input.
	 *
	 * @param FormInput $oDestinationIO
	 *
	 * @return FormBinding
	 */
	public function BindToInput(FormInput $oDestinationIO): FormBinding
	{
		$oBinding = new FormBinding($this, $oDestinationIO);

		$this->aBindingsToInputs[] = $oBinding;

		$oDestinationIO->Attach($oBinding);

		return $oBinding;
	}

	/**
	 * Attach a binding.
	 *
	 * @param FormBinding $oFormBinding
	 *
	 * @return void
	 */
	public function Attach(FormBinding $oFormBinding)
	{
		$this->oBinding = $oFormBinding;
	}

	/**
	 * Indicate IO is bound.
	 *
	 * @return bool
	 */
	public function IsBound(): bool
	{
		return $this->oBinding !== null;
	}

	/**
	 * Return the binding.
	 *
	 * @return FormBinding|null
	 */
	public function GetBinding(): ?FormBinding
	{
		return $this->oBinding;
	}

	/**
	 * Indicated inputs data is ready.
	 *
	 * @return bool
	 */
	public function IsDataReady(): bool
	{
		return $this->HasValue();
	}


}