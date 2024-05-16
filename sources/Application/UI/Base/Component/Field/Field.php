<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\Field;


use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;
use Combodo\iTop\Application\UI\Base\UIBlock;
use utils;

/**
 * @since 3.0.0
 */
class Field extends UIContentBlock
{
	/** @inheritdoc */
	public const BLOCK_CODE = 'ibo-field';
	/** @inheritdoc */
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/field/layout';

	public const ENUM_FIELD_LAYOUT_SMALL = 'small';
	public const ENUM_FIELD_LAYOUT_LARGE = 'large';

	/** @var string */
	protected $sLayout;
	/** @var string */
	protected $sAttCode;
	/** @var string */
	protected $sAttType;
	/** @var string */
	protected $sAttLabel;
	/** @var bool */
	protected $bIsReadOnly = false;
	/** @var bool */
	protected $bIsMandatory = false;
	/** @var bool */
	protected $bMustChange = false;
	/** @var bool */
	protected $bMustPrompt = false;
	/** @var bool */
	protected $bIsSlave = false;
	/** @var string */
	protected $sValueRaw;
	/** @var string */
	protected $sLabel;
	/**
	 * @var string
	 * @since 3.1.0
	 */
	protected $sDescription = '';
	/** @var string */
	protected $sValueId;

	/** @var string */
	protected $sComments;

	public function __construct(string $sLabel, UIBlock $oValue = null, ?string $sId = null)
	{
		parent::__construct($sId);
		$this->sLabel = $sLabel;
		$this->sValueId = null;
		if (!is_null($oValue)) {
			$this->AddSubBlock($oValue);
		}
	}

	/**
	 * @return string
	 */
	public function GetLayout(): ?string
	{
		return $this->sLayout;
	}

	/**
	 * @param string $sLayout
	 *
	 * @return $this
	 */
	public function SetLayout(string $sLayout)
	{
		$this->sLayout = $sLayout;

		return $this;
	}

	/**
	 * @return string
	 */
	public function GetAttCode(): ?string
	{
		return $this->sAttCode;
	}

	/**
	 * @param string $sAttCode
	 *
	 * @return $this
	 */
	public function SetAttCode(string $sAttCode)
	{
		$this->sAttCode = $sAttCode;

		return $this;
	}

	/**
	 * @return string
	 */
	public function GetAttType(): ?string
	{
		return $this->sAttType;
	}

	/**
	 * @param string $sAttType
	 *
	 * @return $this
	 */
	public function SetAttType(string $sAttType)
	{
		$this->sAttType = $sAttType;

		return $this;
	}

	/**
	 * @return string
	 */
	public function GetAttLabel(): ?string
	{
		return $this->sAttLabel;
	}

	/**
	 * @param string $sAttLabel
	 *
	 * @return $this
	 */
	public function SetAttLabel(string $sAttLabel)
	{
		$this->sAttLabel = $sAttLabel;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function IsReadOnly(): bool
	{
		return $this->bIsReadOnly;
	}

	/**
	 * @param bool $bIsReadOnly
	 *
	 * @return $this
	 */
	public function SetIsReadOnly(bool $bIsReadOnly)
	{
		$this->bIsReadOnly = $bIsReadOnly;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function IsMandatory(): bool
	{
		return $this->bIsMandatory;
	}

	/**
	 * @param bool $bIsMandatory
	 *
	 * @return $this
	 */
	public function SetIsMandatory(bool $bIsMandatory)
	{
		$this->bIsMandatory = $bIsMandatory;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function IsMustChange(): bool
	{
		return $this->bMustChange;
	}

	/**
	 * @param bool $bIsMustChange
	 *
	 * @return $this
	 */
	public function SetMustChange(bool $bIsMustChange)
	{
		$this->bMustChange = $bIsMustChange;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function IsMustPrompt(): bool
	{
		return $this->bMustPrompt;
	}

	/**
	 * @param bool $bIsMustPrompt
	 *
	 * @return $this
	 */
	public function SetMustPrompt(bool $bIsMustPrompt)
	{
		$this->bMustPrompt = $bIsMustPrompt;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function IsSlave(): bool
	{
		return $this->bIsSlave;
	}

	/**
	 * @param bool $bIsSlave
	 *
	 * @return $this
	 */
	public function SetIsSlave(bool $bIsSlave)
	{
		$this->bIsSlave = $bIsSlave;

		return $this;
	}

	/**
	 * @return string
	 */
	public function GetValueRaw(): ?string
	{
		return $this->sValueRaw;
	}

	/**
	 * @param string $sValueRaw
	 *
	 * @return $this
	 */
	public function SetValueRaw(string $sValueRaw)
	{
		$this->sValueRaw = $sValueRaw;

		return $this;
	}

	/**
	 * @return string
	 */
	public function GetLabel(): string
	{
		return $this->sLabel;
	}

	/**
	 * @param string $sLabel
	 *
	 * @return $this
	 */
	public function SetLabel(string $sLabel)
	{
		$this->sLabel = $sLabel;

		return $this;
	}

	/**
	 * @return \Combodo\iTop\Application\UI\Base\UIBlock
	 */
	public function GetValue()
	{
		return $this->oValue;
	}

	/**
	 * @param \Combodo\iTop\Application\UI\Base\UIBlock $oValue
	 *
	 * @return $this
	 */
	public function SetValue(UIBlock $oValue)
	{
		$this->oValue = $oValue;

		return $this;
	}

	/**
	 * @return string
	 */
	public function GetComments(): ?string
	{
		return $this->sComments;
	}

	/**
	 * @param string $sComments
	 *
	 * @return $this
	 */
	public function SetComments(string $sComments)
	{
		$this->sComments = $sComments;

		return $this;
	}

	/**
	 * @return string
	 */
	public function GetValueId(): ?string
	{
		return $this->sValueId;
	}

	/**
	 * @param string|null $sValueId
	 *
	 * @return $this
	 */
	public function SetValueId(?string $sValueId)
	{
		$this->sValueId = $sValueId;

		return $this;
	}

	public function SetInputId(string $sInputId)
	{
		$this->AddDataAttribute('input-id', $sInputId);

		return $this;
	}

	public function SetInputType(string $sInputType)
	{
		$this->AddDataAttribute('input-type', $sInputType);

		return $this;
	}

	/**
	 * @return string
	 * @since 3.1.0
	 */
	public function GetDescription(): string
	{
		return $this->sDescription;
	}

	/**
	 * @param string $sDescription
	 *
	 * @return $this
	 * @since 3.1.0
	 */
	public function SetDescription(string $sDescription)
	{
		$this->sDescription = $sDescription;
		return $this;
	}

	/*
	 * @return bool
	 * @since 3.1.0
	 */
	public function HasDescription(): bool
	{
		return utils::IsNotNullOrEmptyString($this->GetDescription());
	}
}