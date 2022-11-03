<?php
/*
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
namespace Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\CaseLogEntryForm;

use cmdbAbstractObject;
use Combodo\iTop\Application\UI\Base\Component\Input\RichText\RichText;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;
use Combodo\iTop\Application\UI\Base\UIBlock;
use DBObject;
use MetaModel;
use utils;

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

	/** @var string */
	public const DEFAULT_SUBMIT_MODE = self::ENUM_SUBMIT_MODE_AUTONOMOUS;

	/** @var DBObject Object hosting the case log attribute */
	protected $oObject;
	/** @var string Attribute code of the case log in $oObject */
	protected $sAttCode;
	/**
	 * @var string Whether the form can send data on its own or if it's bridged with its host object form
	 * @see static::ENUM_SUBMIT_MODE_XXX
	 */
	protected $sSubmitMode;
	/** @var \Combodo\iTop\Application\UI\Base\Component\Input\RichText\RichText $oTextInput The main input to write a case log entry */
	protected $oTextInput;
	/** @var array $aMainActionButtons The form main actions (send, cancel, ...) */
	protected $aMainActionButtons;
	/** @var array $aExtraActionButtons The form extra actions, can be populated through a public API */
	protected $aExtraActionButtons;

	/**
	 * CaseLogEntryForm constructor.
	 *
	 * @param \DBObject $oObject
	 * @param string|null $sId
	 */
	public function __construct(DBObject $oObject, string $sAttCode, string $sId = null)
	{
		parent::__construct($sId);
		$this->oObject = $oObject;
		$this->sAttCode = $sAttCode;
		$this->sSubmitMode = static::DEFAULT_SUBMIT_MODE;
		$this->aMainActionButtons = [];
		$this->aExtraActionButtons = [];
		$this->InitTextInput();
	}

	/**
	 * @uses static::$oObject
	 * @return \DBObject
	 */
	public function GetObject(): DBObject
	{
		return $this->oObject;
	}

	/**
	 * @uses static::$oObject
	 * @return string The class of $oObject
	 */
	public function GetObjectClass(): string
	{
		return get_class($this->oObject);
	}

	/**
	 * @uses static::$oObject
	 * @return string The ID of $oObject
	 */
	public function GetObjectId(): string
	{
		return $this->oObject->GetKey();
	}

	/**
	 * @uses static::$sAttCode
	 * @return string
	 */
	public function GetAttCode(): string
	{
		return $this->sAttCode;
	}

	/**
	 * @see static::$sAttCode
	 * @return string
	 * @throws \Exception
	 */
	public function GetAttLabel(): string
	{
		return MetaModel::GetLabel($this->GetObjectClass(), $this->GetAttCode());
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
	 * @see cmdbAbstractObject::ENUM_DISPLAY_MODE_XXX
	 *
	 * @return $this
	 */
	public function SetSubmitModeFromHostObjectMode($sObjectMode)
	{
		switch ($sObjectMode){
			case cmdbAbstractObject::ENUM_DISPLAY_MODE_CREATE:
			case cmdbAbstractObject::ENUM_DISPLAY_MODE_EDIT:
				$sSubmitMode = static::ENUM_SUBMIT_MODE_BRIDGED;
				break;

			case cmdbAbstractObject::ENUM_DISPLAY_MODE_VIEW:
			case cmdbAbstractObject::ENUM_DISPLAY_MODE_STIMULUS:
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
	 * @uses $oTextInput
	 * @return $this
	 */
	protected function InitTextInput()
	{
		$this->oTextInput = new RichText();

		// Add the "host_class" to the mention endpoints so it can filter objects regarding the triggers
		$aConfig = $this->oTextInput->GetConfig();
		if (isset($aConfig['mentions'])) {
			foreach ($aConfig['mentions'] as $iIdx => $aData) {
				$sFeed = $aConfig['mentions'][$iIdx]['feed'];
				$sFeed = utils::AddParameterToUrl($sFeed, 'host_class', $this->GetObjectClass());
				$aConfig['mentions'][$iIdx]['feed'] = utils::AddParameterToUrl($sFeed, 'host_id', $this->GetObjectId());
			}
		}
		$this->oTextInput->SetConfig($aConfig);

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
	public function GetExtraActionButtons(): array
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
	 * @inheritdoc
	 */
	public function GetSubBlocks(): array
	{
		$aSubBlocks = [];
		$aSubBlocks[$this->GetTextInput()->GetId()] = $this->GetTextInput();

		foreach ($this->GetExtraActionButtons() as $oExtraActionButton) {
			$aSubBlocks[$oExtraActionButton->GetId()] = $oExtraActionButton;
		}

		foreach ($this->GetMainActionButtons() as $oMainActionButton) {
			$aSubBlocks[$oMainActionButton->GetId()] = $oMainActionButton;
		}

		return $aSubBlocks;
	}
	
}