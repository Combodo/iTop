<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Layout\Object;


use cmdbAbstractObject;
use Combodo\iTop\Application\UI\Base\Component\Panel\Panel;
use Combodo\iTop\Application\UI\Helper\UIHelper;
use DBObject;
use Dict;
use Combodo\iTop\Application\UI\Hook\iKeyboardShortcut;
use MetaModel;

/**
 * Class ObjectDetails
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Layout\Object
 * @since 3.0.0
 */
class ObjectDetails extends Panel implements iKeyboardShortcut
{
	// Overloaded constants
	/**
	 * @inheritDoc
	 */
	public const BLOCK_CODE = 'ibo-object-details';
	/**
	 * @inheritDoc
	 */
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/layouts/object/object-details/layout';
	/**
	 * @inheritDoc
	 */
	public const DEFAULT_JS_TEMPLATE_REL_PATH = 'base/layouts/object/object-details/layout';
	/**
	 * @inheritDoc
	 */
	public const DEFAULT_JS_FILES_REL_PATH = [
		'js/layouts/object/object-details.js',
	];

	/** @var string Class name of the object (eg. "UserRequest") */
	protected $sClassName;
	/** @var string Class label of the object (eg. "User request") */
	protected $sClassLabel;
	/** @var string ID of the object */
	protected $sObjectId;
	/** @var string */
	protected $sObjectName;
	/**
	 * @var string The mode in which the object should be displayed (read, edit, create, ...)
	 * @see \cmdbAbstractObject::ENUM_DISPLAY_MODE_XXX
	 */
	protected $sObjectMode;
	/** @var string */
	protected $sIconUrl;
	/** @var string|null Code of the current value of the attribute carrying the state for $sClassName */
	protected $sStatusCode;
	/** @var string|null Label of the current value of the attribute carrying the state for $sClassName  */
	protected $sStatusLabel;
	/** @var string|null Color value (eg. #ABCDEF, var(--foo-color), ...) */
	protected $sStatusColor;

	/**
	 * ObjectDetails constructor.
	 *
	 * @param \DBObject $oObject The object for which we display the details
	 * @param string $sMode See \cmdbAbstractObject::ENUM_DISPLAY_MODE_XXX
	 * @param string|null $sId ID of the block itself, not the $oObject ID
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 */
	public function __construct(DBObject $oObject, string $sMode = cmdbAbstractObject::DEFAULT_DISPLAY_MODE, ?string $sId = null)
	{
		$this->sClassName = get_class($oObject);
		$this->sClassLabel = MetaModel::GetName($this->GetClassName());
		$this->sObjectId = $oObject->GetKey();
		// Note: We get the raw name as only the front-end consumer knows when and how to encode it.
		$this->sObjectMode = $sMode;

		$this->ComputeObjectName($oObject);

		parent::__construct($this->sObjectName, [], static::DEFAULT_COLOR_SCHEME, $sId);

		$this->SetColorFromClass($this->sClassName);
		$this->ComputeIconUrl($oObject);
		$this->ComputeState($oObject);
	}

	/**
	 * @see self::$sClassName
	 * @return string
	 */
	public function GetClassName(): string
	{
		return $this->sClassName;
	}

	/**
	 * @see self::$sClassLabel
	 * @return $this
	 */
	public function SetClassLabel($sClassLabel)
	{
		$this->sClassLabel = $sClassLabel;

		return $this;
	}
	
	/**
	 * @see self::$sClassLabel
	 * @return string
	 */
	public function GetClassLabel(): string
	{
		return $this->sClassLabel;
	}

	/**
	 * @see self::$sObjectName
	 * @return string
	 */
	public function GetObjectName(): string
	{
		return $this->sObjectName;
	}

	/**
	 * @see self::$sObjectId
	 * @return string
	 */
	public function GetObjectId(): string
	{
		return $this->sObjectId;
	}

	/**
	 * @see self::$sObjectMode
	 * @return string
	 */
	public function GetObjectMode(): string
	{
		return $this->sObjectMode;
	}

	/**
	 * Set the status to display for the object
	 *
	 * @param string $sCode
	 * @param string $sLabel
	 * @param string $sColor
	 *
	 * @return $this
	 */
	public function SetStatus(string $sCode, string $sLabel, string $sColor)
	{
		$this->sStatusCode = $sCode;
		$this->sStatusLabel = $sLabel;
		$this->sStatusColor = $sColor;

		return $this;
	}

	/**
	 * @see self::$sStatusCode
	 * @return string|null
	 */
	public function GetStatusCode(): ?string
	{
		return $this->sStatusCode;
	}

	/**
	 * @see static::$sStatusCode
	 * @return bool
	 */
	public function HasStatus(): bool
	{
		return $this->sStatusCode !== null;
	}

	/**
	 * @see self::$sStatusLabel
	 * @return string
	 */
	public function GetStatusLabel(): ?string
	{
		return $this->sStatusLabel;
	}

	/**
	 * @see self::$sStatusColor
	 * @return string
	 */
	public function GetStatusColor(): string
	{
		return $this->sStatusColor;
	}

	/**
	 * @inheritDoc
	 */
	public function HasSubTitle(): bool
	{
		return ($this->sObjectMode != "create");
	}

	/**
	 * @param \DBObject $oObject
	 * @see static::$oObject
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 */
	protected function ComputeIconUrl(DBObject $oObject): void
	{
		// Default icon is the class icon
		$sIconUrl = $oObject->GetIcon(false);
		// Note: Class icons are a square image with no margin around, so they need to be zoomed out in the medallion
		$sIconCoverMethod = $oObject->HasInstanceIcon() && !$oObject->HasHighlightIcon() ?  static::ENUM_ICON_COVER_METHOD_COVER : static::ENUM_ICON_COVER_METHOD_ZOOMOUT;

		$this->SetIcon($sIconUrl, $sIconCoverMethod, true);
	}

	/**
	 * @see static::$oObject
	 *
	 * @param \DBObject $oObject
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreException*@throws \Exception
	 */
	protected function ComputeState(DBObject $oObject): void
	{
		if (MetaModel::HasStateAttributeCode($this->sClassName)) {
			$this->sStatusCode = $oObject->GetState();
			$this->sStatusLabel = $oObject->GetStateLabel();

			$oStyle = MetaModel::GetEnumStyle($this->sClassName, MetaModel::GetStateAttributeCode($this->sClassName), $this->sStatusCode);
			if ($oStyle !== null) {
				$this->sStatusColor = $oStyle->GetMainColor();
			}
			// If no style defined, fallback on a default color
			else {
				$sColorName = UIHelper::GetColorNameFromStatusCode($this->sStatusCode);
				$this->sStatusColor = "var(--ibo-lifecycle-$sColorName-state-primary-color)";
			}
		}
	}

	/**
	 * @param \DBObject $oObject
	 * @see static::$oObject
	 *
	 * @throws \CoreException
	 */
	protected function ComputeObjectName(DBObject $oObject): void
	{
		if ($this->sObjectMode === cmdbAbstractObject::ENUM_DISPLAY_MODE_CREATE) {
			$this->sObjectName = Dict::Format('UI:CreationTitle_Class', $this->sClassLabel);
		} else {
			$this->sObjectName = $oObject->GetRawName();
		}
	}

	/**
	 * @inheritDoc
	 */
	public static function GetShortcutKeys(): array
	{
		return [['id' => 'ibo-edit-object', 'label' => 'UI:Layout:ObjectDetails:KeyboardShortcut:EditObject', 'key' => 'e', 'event' => 'edit_object'],
			['id' => 'ibo-delete-object', 'label' => 'UI:Layout:ObjectDetails:KeyboardShortcut:DeleteObject', 'key' => 'd', 'event' => 'delete_object'],
			['id' => 'ibo-new-object', 'label' => 'UI:Layout:ObjectDetails:KeyboardShortcut:NewObject', 'key' => 'n', 'event' => 'new_object'],
			['id' => 'ibo-save-object', 'label' => 'UI:Layout:ObjectDetails:KeyboardShortcut:SaveObject', 'key' => 's', 'event' => 'save_object']];
	}

	/**
	 * @inheritDoc
	 */
	public static function GetShortcutTriggeredElementSelector(): string
	{
		return "[data-role='".static::BLOCK_CODE."']";
	}
}