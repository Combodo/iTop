<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Form\Field;

use Closure;
use Combodo\iTop\Form\Validator\AbstractValidator;
use Combodo\iTop\Form\Validator\MandatoryValidator;

/**
 * Description of Field
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since iTop 2.3.0
 */
abstract class Field
{
    /** @var string */
	const ENUM_DISPLAY_MODE_COSY = 'cosy';          // Label above value
    /** @var string */
	const ENUM_DISPLAY_MODE_COMPACT = 'compact';    // Label and value side by side
    /** @var string */
	const ENUM_DISPLAY_MODE_DENSE = 'dense';        // Label and value side by side, closely

	/** @var string */
	const DEFAULT_LABEL = '';
	/** @var string */
	const DEFAULT_DESCRIPTION = '';
	/** @var array */
	const DEFAULT_METADATA = array();
	/** @var bool */
	const DEFAULT_HIDDEN = false;
	/** @var bool */
	const DEFAULT_READ_ONLY = false;
	/** @var bool */
	const DEFAULT_MANDATORY = false;
    /** @var string */
	const DEFAULT_DISPLAY_MODE = self::ENUM_DISPLAY_MODE_COSY;
	/** @var bool */
	const DEFAULT_VALID = true;

	/** @var string */
	protected $sId;
	/** @var string */
	protected $sGlobalId;
	/** @var string */
	protected $sFormPath;
	/** @var string */
	protected $sLabel;
	/**
	 * Description text of the field, typically to bring more information to the user on the field purpose
	 *
	 * @var string
	 * @since 3.0.0
	 */
	protected $sDescription;
	/** @var array */
	protected $aMetadata;
	/** @var bool */
	protected $bHidden;
	/** @var bool */
	protected $bReadOnly;
	/** @var bool */
	protected $bMandatory;
	/** @var string */
	protected $sDisplayMode;
	/** @var AbstractValidator[] */
	protected $aValidators;
	/**
	 * @var bool
	 * @since 3.1.0 N°6414
	 */
	protected $bValidationDisabled;

	/** @var bool */
	protected $bValid;
	/** @var array */
	protected $aErrorMessages;
	protected $currentValue;
	protected $onFinalizeCallback;

	/**
	 * Default constructor
	 *
	 * @param string        $sId
	 * @param \Closure|null $onFinalizeCallback (Used in the $oForm->AddField($sId, ..., function() use ($oManager, $oForm, '...') { ... } ); )
	 */
	public function __construct(string $sId, Closure $onFinalizeCallback = null)
	{
		$this->sId = $sId;
		// No space in such an id, that could be used as a DOM node id
		$this->sGlobalId = 'field_' . str_replace(' ', '_', $sId) . '_' . uniqid();
		$this->sLabel = static::DEFAULT_LABEL;
		$this->sDescription = static::DEFAULT_DESCRIPTION;
		$this->aMetadata = static::DEFAULT_METADATA;
		$this->bHidden = static::DEFAULT_HIDDEN;
		$this->bReadOnly = static::DEFAULT_READ_ONLY;
		$this->bMandatory = static::DEFAULT_MANDATORY;
		$this->sDisplayMode = static::DEFAULT_DISPLAY_MODE;
		$this->aValidators = array();
		$this->bValidationDisabled = false;
		$this->bValid = static::DEFAULT_VALID;
		$this->aErrorMessages = array();
		$this->onFinalizeCallback = $onFinalizeCallback;
	}

	/**
	 * Returns the field id within its container form
	 *
	 * @return string
	 */
	public function GetId()
	{
		return $this->sId;
	}

	/**
	 * Returns a unique field id within the top level form
	 *
	 * @return string
	 */
	public function GetGlobalId()
	{
		return $this->sGlobalId;
	}

	/**
	 * Returns the id of the container form
	 *
	 * @return string
	 */
	public function GetFormPath()
	{
		return $this->sFormPath;
	}

	/**
	 *
	 * @return string
	 */
	public function GetLabel()
	{
		return $this->sLabel;
	}

	/**
	 * Return true if the field has a description. Note that an empty string is equivalent to no description.
	 *
	 * @see static::$sDescription
	 * @return bool
	 * @since 3.0.0
	 */
	public function HasDescription(): bool
	{
		return empty($this->sDescription) === false;
	}

	/**
	 * @see static::$sDescription
	 * @return string
	 * @since 3.0.0
	 */
	public function GetDescription(): string
	{
		return $this->sDescription;
	}

	/**
	 * Return an array of $sName => $sValue metadata.
	 *
	 * @return array
	 * @since 2.7.0
	 */
	public function GetMetadata()
	{
		return $this->aMetadata;
	}

	/**
	 *
	 * @return boolean
	 */
	public function GetHidden()
	{
		return $this->bHidden;
	}

	/**
	 *
	 * @return boolean
	 */
	public function GetReadOnly()
	{
		return $this->bReadOnly;
	}

	/**
	 * Note: This not implemented yet! Just a pre-conception for CaseLogField
	 *
	 * @todo Implement
	 * @return boolean
	 */
	public function GetMustChange()
	{
		// TODO
		return false;
	}

	/**
	 *
	 * @return boolean
	 */
	public function GetMandatory()
	{
		return $this->bMandatory;
	}

    /**
     *
     * @return string
     */
	public function GetDisplayMode()
    {
        return $this->sDisplayMode;
    }

	public function GetValidators()
	{
		return $this->aValidators;
	}

	/**
	 * Returns the current validation state of the field (true|false).
	 * It DOESN'T make the validation, see Validate() instead.
	 *
	 * @return boolean
	 */
	public function GetValid()
	{
		return $this->bValid;
	}

	/**
	 *
	 * @return array
	 */
	public function GetErrorMessages()
	{
		return $this->aErrorMessages;
	}

	/**
	 *
	 * @return mixed
	 */
	public function GetCurrentValue()
	{
		return $this->currentValue;
	}

	/**
	 * @return mixed
	 */
	public function GetDisplayValue()
	{
		return $this->currentValue;
	}
	
	/**
	 * Sets the field formpath
	 * Usually Called by the form when adding the field
	 *
	 * @param string $sFormPath
	 * @return $this
	 */
	public function SetFormPath(string $sFormPath)
	{
		$this->sFormPath = $sFormPath;
		return $this;
	}

	/**
	 *
	 * @param string $sLabel
	 * @return $this
	 */
	public function SetLabel(string $sLabel)
	{
		$this->sLabel = $sLabel;
		return $this;
	}

	/**
	 * @param string $sDescription
	 * @see static::$sDescription
	 *
	 * @return $this
	 * @since 3.0.0
	 */
	public function SetDescription(string $sDescription)
	{
		$this->sDescription = $sDescription;
		return $this;
	}

	/**
	 * Must be an array of $sName => $sValue metadata.
	 *
	 * @param array $aMetadata
	 *
	 * @return $this
	 * @since 2.7.0
	 */
	public function SetMetadata(array $aMetadata)
	{
		$this->aMetadata = $aMetadata;
		return $this;
	}

	/**
	 *
	 * @param boolean $bHidden
	 * @return $this
	 */
	public function SetHidden(bool $bHidden)
	{
		$this->bHidden = $bHidden;
		return $this;
	}

	/**
	 *
	 * @param boolean $bReadOnly
	 * @return $this
	 */
	public function SetReadOnly(bool $bReadOnly)
	{
		$this->bReadOnly = $bReadOnly;
		return $this;
	}

	/**
	 * Sets if the field is mandatory or not.
	 * Setting the value will automatically add/remove a MandatoryValidator to the Field
	 *
	 * @param boolean $bMandatory
	 *
	 * @return $this
	 */
	public function SetMandatory(bool $bMandatory)
	{
		// Before changing the property, we check if it was already mandatory. If not, we had the mandatory validator
		if ($bMandatory && !$this->bMandatory) {
			$this->AddValidator($this->GetMandatoryValidatorInstance());
		}

		if (false === $bMandatory) {
			foreach ($this->aValidators as $iKey => $oValue) {
				if ($oValue instanceof MandatoryValidator) {
                    unset($this->aValidators[$iKey]);
                }
            }
        }

		$this->bMandatory = $bMandatory;

		return $this;
	}

    /**
     * @return AbstractValidator
     * @since 3.1.0 N°6414
     */
	protected function GetMandatoryValidatorInstance(): AbstractValidator
	{
		return new MandatoryValidator();
	}

	/**
	 * Sets if the field is must change or not.
	 * Note: This not implemented yet! Just a pre-conception for CaseLogField
	 *
	 * @param boolean $bMustChange
	 *
	 * @return $this
	 * @todo Implement
	 */
	public function SetMustChange(bool $bMustChange)
	{
		// TODO.
		return $this;
	}

    /**
     *
     * @param string $sDisplayMode
     * @return $this
     */
	public function SetDisplayMode(string $sDisplayMode)
    {
        $this->sDisplayMode = $sDisplayMode;
        return $this;
    }

	/**
	 *
	 * @param array $aValidators
	 * @return $this
	 */
	public function SetValidators(array $aValidators)
	{
		$this->aValidators = $aValidators;
		return $this;
	}

	/**
	 * Note : Function is protected as bValid should not be set from outside
	 *
	 * @param boolean $bValid
	 * @return $this
	 */
	protected function SetValid(bool $bValid)
	{
		$this->bValid = $bValid;
		return $this;
	}

	/**
	 * Note : Function is protected as aErrorMessages should not be set from outside
	 *
	 * @param array $aErrorMessages
	 * @return $this
	 */
	protected function SetErrorMessages(array $aErrorMessages)
	{
		$this->aErrorMessages = $aErrorMessages;
		return $this;
	}

	/**
	 *
	 * @param mixed $currentValue
	 * @return $this
	 */
	public function SetCurrentValue($currentValue)
	{
		$this->currentValue = $currentValue;
		return $this;
	}

	/**
	 *
	 * @param Closure $onFinalizeCallback
	 * @return $this
	 */
	public function SetOnFinalizeCallback(Closure $onFinalizeCallback)
	{
		$this->onFinalizeCallback = $onFinalizeCallback;
		return $this;
	}

	/**
	 * Add a metadata to the field. If the metadata $sName already exists, it will be overwritten.
	 * Note: $sValue should NOT be HTML (or something else) encoded, only the renderer should take care of it.
	 *
	 * @param string $sName
	 * @param string $sValue
	 *
	 * @return $this;
	 * @since 2.7.0
	 */
	public function AddMetadata(string $sName, ?string $sValue = null)
	{
		$this->aMetadata[$sName] = $sValue;

		return $this;
	}

    /**
     * @param AbstractValidator $oValidator
     * @return $this
     */
	public function AddValidator(AbstractValidator $oValidator)
	{
		$this->aValidators[] = $oValidator;

		return $this;
	}

	/**
	 * @return $this
	 */
	public function RemoveValidator(AbstractValidator $oValidator)
	{
		foreach ($this->aValidators as $iKey => $oValue) {
			if ($oValue === $oValidator) {
				unset($this->aValidators[$iKey]);
			}
		}

		return $this;
	}

	/**
     * @param string $sValidatorClassName validator class name, should be one of {@see AbstractValidator} children
     * @return $this
     * @since 3.1.0 N°6414
     */
    final public function RemoveValidatorsOfClass(string $sValidatorClassName)
    {
        foreach ($this->aValidators as $iKey => $oValue) {
            if ($oValue instanceof $sValidatorClassName) {
                unset($this->aValidators[$iKey]);
            }
        }

        return $this;
    }

	/**
	 * Note : Function is protected as aErrorMessages should not be add from outside
	 *
	 * @param string $sErrorMessage
	 *
	 * @return $this
	 */
	protected function AddErrorMessage(string $sErrorMessage)
	{
		$this->aErrorMessages[] = $sErrorMessage;

		return $this;
	}

	/**
	 * Note : Function is protected as aErrorMessages should not be set from outside
	 *
	 * @return $this
	 */
	protected function EmptyErrorMessages()
	{
		$this->aErrorMessages = array();
		return $this;
	}

	/**
	 * Returns if the field is editable. Meaning that it is not editable nor hidden.
	 *
	 * @return boolean
	 */
	public function IsEditable()
	{
		return (!$this->bReadOnly && !$this->bHidden);
	}

	public function OnCancel()
	{
		// Overload when needed
	}

	public function OnFinalize()
	{
		if ($this->onFinalizeCallback !== null) {
			// Note : We MUST have a temp variable to call the Closure. otherwise it won't work when the Closure is a class member
			$callback = $this->onFinalizeCallback;
			$callback($this);
		}
	}

	/**
	 * @param bool $bValidationDisabled
	 *
	 * @return $this
	 *
	 * @since 3.1.0 N°6414
	 */
	public function SetValidationDisabled(bool $bValidationDisabled = true): Field
	{
		$this->bValidationDisabled = $bValidationDisabled;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function IsValidationDisabled(): bool
	{
		return $this->bValidationDisabled;
	}

	/**
	 * Validates the field using the validators set.
	 *
	 * Before overriding this method in children classes, try to add a custom validator !
	 *
	 * @uses GetValidators()
	 * @uses SetValid()
	 * @uses AddErrorMessage()
	 */
	abstract public function Validate();
}
