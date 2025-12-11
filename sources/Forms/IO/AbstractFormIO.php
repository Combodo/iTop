<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\IO;

use Combodo\iTop\Forms\Block\AbstractFormBlock;
use Combodo\iTop\Forms\IO\Format\AbstractIOFormat;
use Symfony\Component\Form\FormEvents;

/**
 * Abstract form IO.
 *
 * @package Combodo\iTop\Forms\IO
 * @since 3.3.0
 */
class AbstractFormIO
{
	public const EVENT_POST_SET_DATA = FormEvents::POST_SET_DATA;
	public const EVENT_POST_SUBMIT   = FormEvents::POST_SUBMIT;
	public const EVENT_FORM_STATIC   = 'form.static';

	/** @var AbstractFormBlock The owner block */
	private AbstractFormBlock $oOwnerBlock;

	/** @var string Name of the IO */
	private string $sName;

	/** @var string Type of the IO data */
	private string $sType;

	/** @var bool array */
	private bool $bIsArray;

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
	 * @param bool $bIsArray indicates if the IO is an array
	 *
	 * @throws FormBlockIOException
	 */
	public function __construct(string $sName, string $sType, bool $bIsArray = false)
	{
		if (!is_a($sType, AbstractIOFormat::class, true)) {
			throw new FormBlockIOException('invalid form format type '.json_encode($sType).' given');
		}
		$this->sType = $sType;
		$this->bIsArray = $bIsArray;
		$this->SetName($sName);
	}

	public function SetOwnerBlock(AbstractFormBlock $oOwnerBlock): void
	{
		$this->oOwnerBlock = $oOwnerBlock;
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
	 * @throws FormBlockIOException
	 */
	public function SetName(string $sName): self
	{
		// Check name validity
		if (preg_match('/^(?<name>((\w+\.\w+)|\w+))$/', $sName, $aMatches)) {
			$sParsedName = $aMatches['name'];
			if ($sParsedName !== $sName) {
				$sName = json_encode($sName);
				$sParsedName = json_encode($sParsedName);
				//				$sBlockName = json_encode($this->GetOwnerBlock()->GetName());
				throw new FormBlockIOException("Input $sName does not match $sParsedName for block.");
			}
		} else {
			$sName = json_encode($sName);
			//			$sBlockName = json_encode($this->GetOwnerBlock()->GetName());
			throw new FormBlockIOException("Input $sName is not valid for block.");
		}

		// Name is valid
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
	 * Return true if is array.
	 *
	 * @return bool
	 */
	public function IsArray(): bool
	{
		return $this->bIsArray;
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
	 * @param string|null $sEventType
	 *
	 * @return mixed
	 */
	public function GetValue(string $sEventType = null): mixed
	{
		if ($sEventType === null) {
			return $this->Value();
		}

		return $this->aValues[$sEventType] ?? null;
	}

	/**
	 * Return true if value exist.
	 *
	 * @return bool
	 */
	public function HasValue(): bool
	{
		return $this->HasEventValue(FormEvents::POST_SET_DATA) || $this->HasEventValue(FormEvents::POST_SUBMIT);
	}

	/**
	 * Return true if value exist.
	 *
	 * @param string|null $sEventType
	 *
	 * @return bool
	 */
	public function HasEventValue(string $sEventType = null): bool
	{
		if ($sEventType === null) {
			return $this->HasValue();
		}

		return array_key_exists($sEventType, $this->aValues) && $this->aValues[$sEventType] !== null;
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
	private function Value(): mixed
	{
		if (array_key_exists('form.static', $this->aValues)) {
			return $this->aValues['form.static'];
		}
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
	 * @throws FormBlockIOException
	 */
	public function BindToInput(FormInput $oDestinationIO): FormBinding
	{
		$oBinding = new FormBinding($this, $oDestinationIO);
		$this->aBindingsToInputs[] = $oBinding;

		return $oBinding;
	}

	/**
	 * Attach a binding.
	 *
	 * @param FormBinding $oFormBinding
	 *
	 * @return void
	 * @throws FormBlockIOException when already bound
	 */
	public function Attach(FormBinding $oFormBinding): void
	{
		if ($this->IsBound()) {
			throw new FormBlockIOException("Can't attach ".json_encode($oFormBinding->oSourceIO->GetName())." to ".json_encode($this->GetName()).", already bound to ".json_encode($this->oBinding->oSourceIO->GetName()));
		}
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

	public function HasBindingOut(): bool
	{
		return count($this->aBindingsToInputs) > 0;
	}

	public function GetBindingsToInputs(): array
	{
		return $this->aBindingsToInputs;
	}

}
