<?php
/*
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
namespace Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\CaseLogEntryForm;

use cmdbAbstractObject;
use Combodo\iTop\Application\UI\Base\Component\Input\RichText\RichText;
use Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenu;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;
use Combodo\iTop\Application\UI\Base\UIBlock;

/**
 * Class CaseLogEntryForm
 *
 * @package Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\CaseLogEntryForm
 */
class CaseLogEntryForm extends UIContentBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-caselog-entry-form';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/layouts/activity-panel/caselog-entry-form/layout';
	public const DEFAULT_JS_TEMPLATE_REL_PATH = 'base/layouts/activity-panel/caselog-entry-form/layout';
	public const DEFAULT_JS_FILES_REL_PATH = [
		'js/layouts/activity-panel/caselog-entry-form.js',
	];

	/** @var string Form is autonomous and can send data on its own */
	public const ENUM_SUBMIT_MODE_AUTONOMOUS = 'autonomous';
	/** @var string Form is bridged to its host object form */
	public const ENUM_SUBMIT_MODE_BRIDGED = 'bridged';
	/** @var string Container of the form is a specific caselog tab */
	public const ENUM_CONTAINER_TAB_TYPE_CASELOG = 'caselog';
	/** @var string Container of the form is the activity tab */
	public const ENUM_CONTAINER_TAB_TYPE_ACTIVITY = 'activity';

	/** @var string */
	public const DEFAULT_SUBMIT_MODE = self::ENUM_SUBMIT_MODE_AUTONOMOUS;
	/** @var string */
	public const DEFAULT_CONTAINER_TAB_TYPE = self::ENUM_CONTAINER_TAB_TYPE_ACTIVITY;

	/**
	 * @var string Whether the form can send data on its own or if it's bridged with its host object form
	 * @see static::ENUM_SUBMIT_MODE_XXX
	 */
	protected $sSubmitMode;
	/**
	 * @var string Whether the form container is a caselog tab or an activity tab
	 * @see static::ENUM_CONTAINER_TAB_TYPE_XXX
	 */
	protected $sContainerTabType;
	/** @var \Combodo\iTop\Application\UI\Base\Component\Input\RichText\RichText $oTextInput The main input to write a case log entry */
	protected $oTextInput;
	/** @var \Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenu Menu for possible options on the send button */
	protected $oSendButtonPopoverMenu;
	/** @var array $aMainActionButtons The form main actions (send, cancel, ...) */
	protected $aMainActionButtons;
	/** @var array $aExtraActionButtons The form extra actions, can be populated through a public API */
	protected $aExtraActionButtons;

	/**
	 * CaseLogEntryForm constructor.
	 *
	 * @param null $sName
	 */
	public function __construct($sName = null)
	{
		parent::__construct($sName);
		$this->sSubmitMode = static::DEFAULT_SUBMIT_MODE;
		$this->sContainerTabType = static::DEFAULT_CONTAINER_TAB_TYPE;
		$this->SetTextInput(new RichText());
		$this->aMainActionButtons = [];
		$this->aExtraActionButtons = [];
	}

	/**
	 * @see $sSubmitMode
	 *
	 * @return string
	 */
	public function GetSubmitMode(): string
	{
		return $this->sSubmitMode;
	}

	/**
	 * @param string $sSubmitMode
	 * @see $sSubmitMode
	 *
	 * @return $this
	 */
	public function SetSubmitMode(string $sSubmitMode)
	{
		$this->sSubmitMode = $sSubmitMode;
		return $this;
	}

	/**
	 * Set the submit mode (autonomous, bridged) from the host object mode (create, edit, view, ...)
	 * eg. create => bridged, view => autonomous.
	 *
	 * @param string $sObjectMode
	 * @see $sSubmitMode
	 * @see cmdbAbstractObject::ENUM_OBJECT_MODE_XXX
	 *
	 * @return $this
	 */
	public function SetSubmitModeFromHostObjectMode($sObjectMode)
	{
		switch ($sObjectMode){
			case cmdbAbstractObject::ENUM_OBJECT_MODE_CREATE:
			case cmdbAbstractObject::ENUM_OBJECT_MODE_EDIT:
				$sSubmitMode = static::ENUM_SUBMIT_MODE_BRIDGED;
				break;

			case cmdbAbstractObject::ENUM_OBJECT_MODE_VIEW:
			case cmdbAbstractObject::ENUM_OBJECT_MODE_STIMULUS:
			default:
				$sSubmitMode = static::ENUM_SUBMIT_MODE_AUTONOMOUS;
				break;
		}

		$this->SetSubmitMode($sSubmitMode);
		return $this;
	}

	/**
	 * Return true if the submit mode is autonomous
	 *
	 * @see $sSubmitMode
	 *
	 * @return bool
	 */
	public function IsSubmitAutonomous(): bool
	{
		return $this->GetSubmitMode() === static::ENUM_SUBMIT_MODE_AUTONOMOUS;
	}

	/**
	 * @see $sContainerTabType
	 *
	 * @return string
	 */
	public function GetContainerTabType(): string
	{
		return $this->sContainerTabType;
	}

	/**
	 * @param string $sContainerTabType
	 * @see $sContainerTabType
	 *
	 * @return $this
	 */
	public function SetContainerTabType(string $sContainerTabType)
	{
		$this->sContainerTabType = $sContainerTabType;
		return $this;
	}

	/**
	 * @return \Combodo\iTop\Application\UI\Base\Component\Input\RichText\RichText
	 */
	public function GetTextInput(): RichText
	{
		return $this->oTextInput;
	}

	/**
	 * @param \Combodo\iTop\Application\UI\Base\Component\Input\RichText\RichText $oTextInput
	 *
	 * @return $this
	 */
	public function SetTextInput(RichText $oTextInput)
	{
		$this->oTextInput = $oTextInput;
		return $this;
	}

	/**
	 * @return \Combodo\iTop\Application\UI\Base\UIBlock[]
	 */
	public function GetMainActionButtons()
	{
		return $this->aMainActionButtons;
	}

	/**
	 * Set all main action buttons at once, replacing all existing ones
	 *
	 * @param array $aFormActionButtons
	 * @return $this
	 */
	public function SetMainActionButtons(array $aFormActionButtons)
	{
		$this->aMainActionButtons = $aFormActionButtons;
		return $this;
	}

	/**
	 * @param \Combodo\iTop\Application\UI\Base\UIBlock $oMainActionButton
	 * @return $this;
	 */
	public function AddMainActionButtons(UIBlock $oMainActionButton)
	{
		$this->aMainActionButtons[] = $oMainActionButton;
		return $this;
	}

	/**
	 * @return \Combodo\iTop\Application\UI\Base\UIBlock[]
	 */
	public function GetExtraActionButtons()
	{
		return $this->aExtraActionButtons;
	}

	/**
	 * Set all extra action buttons at once, replacing all existing ones
	 *
	 * @param array $aExtraActionButtons
	 * @see $aExtraActionButtons
	 *
	 * @return $this
	 */
	public function SetExtraActionButtons(array $aExtraActionButtons)
	{
		$this->aExtraActionButtons = $aExtraActionButtons;
		return $this;
	}

	/**
	 * @param \Combodo\iTop\Application\UI\Base\UIBlock $oExtraActionButton
	 *
	 * @return $this;
	 * @see $aExtraActionButtons
	 *
	 */
	public function AddExtraActionButtons(UIBlock $oExtraActionButton)
	{
		$this->aExtraActionButtons[] = $oExtraActionButton;
		return $this;
	}

	/**
	 * @return \Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenu
	 */
	public function GetSendButtonPopoverMenu(): PopoverMenu
	{
		return $this->oSendButtonPopoverMenu;
	}

	/**
	 * @param \Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenu $oCaseLogSelectionPopOverMenu
	 * @return $this
	 */
	public function SetSendButtonPopoverMenu(PopoverMenu $oCaseLogSelectionPopOverMenu)
	{
		$this->oSendButtonPopoverMenu = $oCaseLogSelectionPopOverMenu;
		return $this;
	}

	/**
	 * Return true is there is a PopoverMenu for the send button
	 *
	 * @return bool
	 */
	public function HasSendButtonPopoverMenu(): bool
	{
		return $this->oSendButtonPopoverMenu !== null;
	}

	/**
	 * @inheritdoc
	 */
	public function GetSubBlocks(): array
	{
		$aSubBlocks = [];
		$aSubBlocks[$this->GetTextInput()->GetId()] = $this->GetTextInput();

		foreach ($this->GetExtraActionButtons() as $oExtraActionButton)
		{
			$aSubBlocks[$oExtraActionButton->GetId()] = $oExtraActionButton;
		}

		foreach ($this->GetMainActionButtons() as $oMainActionButton)
		{
			$aSubBlocks[$oMainActionButton->GetId()] = $oMainActionButton;
		}

		$aSubBlocks[$this->GetSendButtonPopoverMenu()->GetId()] = $this->GetSendButtonPopoverMenu();
		
		return $aSubBlocks;
	}
	
}