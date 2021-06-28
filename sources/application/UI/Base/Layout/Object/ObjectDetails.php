<?php
/*
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Layout\Object;


use cmdbAbstractObject;
use Combodo\iTop\Application\UI\Base\Component\Panel\Panel;
use Combodo\iTop\Application\UI\Helper\UIHelper;
use DBObject;
use Dict;
use iKeyboardShortcut;
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
	 * @see \cmdbAbstractObject::ENUM_OBJECT_MODE_XXX
	 */
	protected $sObjectMode;
	/** @var string */
	protected $sIconUrl;
	/** @var string */
	protected $sStatusCode;
	/** @var string */
	protected $sStatusLabel;
	/** @var string */
	protected $sStatusColor;

	/**
	 * ObjectDetails constructor.
	 *
	 * @param \DBObject $oObject The object for which we display the details
	 * @param string $sMode See \cmdbAbstractObject::ENUM_OBJECT_MODE_XXX
	 * @param string|null $sId ID of the block itself, not the $oObject ID
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 */
	public function __construct(DBObject $oObject, string $sMode = cmdbAbstractObject::DEFAULT_OBJECT_MODE, ?string $sId = null)
	{
		$this->sClassName = get_class($oObject);
		$this->sClassLabel = MetaModel::GetName($this->GetClassName());
		$this->sObjectId = $oObject->GetKey();
		// Note: We get the raw name as only the front-end consumer knows when and how to encode it.
		$this->sObjectMode = $sMode;

		$this->ComputeObjectName($oObject);

		parent::__construct($this->sObjectName, [], static::DEFAULT_COLOR, $sId);

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
		$this->sStatusCode = $sColor;
		$this->sStatusLabel = $sLabel;
		$this->sStatusColor = $sColor;

		return $this;
	}

	/**
	 * @see self::$sStatusCode
	 * @return string
	 */
	public function GetStatusCode(): string
	{
		return $this->sStatusCode;
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
		$sIconCoverMethod = static::ENUM_ICON_COVER_METHOD_ZOOMOUT;
		// Use object image from semantic attribute only if it's not the default image
		if (!$oObject->IsNew() && MetaModel::HasImageAttributeCode($this->sClassName)) {
			$sImageAttCode = MetaModel::GetImageAttributeCode($this->sClassName);
			if (!empty($sImageAttCode)) {
				/** @var \ormDocument $oImage */
				$oImage = $oObject->Get($sImageAttCode);
				if (!$oImage->IsEmpty()) {
					$sIconUrl = $oImage->GetDisplayURL($this->sClassName, $this->sObjectId, $sImageAttCode);
					$sIconCoverMethod = static::ENUM_ICON_COVER_METHOD_COVER;
				}
			}

		}

		$this->SetIcon($sIconUrl, $sIconCoverMethod, true);
	}

	/**
	 * @param \DBObject $oObject
	 * @see static::$oObject
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 */
	protected function ComputeState(DBObject $oObject): void
	{
		if (MetaModel::HasStateAttributeCode($this->sClassName)) {
			$this->sStatusCode = $oObject->GetState();
			$this->sStatusLabel = $oObject->GetStateLabel();
			$this->sStatusColor = UIHelper::GetColorFromStatus($this->sClassName, $this->sStatusCode);
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
		if ($this->sObjectMode === cmdbAbstractObject::ENUM_OBJECT_MODE_CREATE) {
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