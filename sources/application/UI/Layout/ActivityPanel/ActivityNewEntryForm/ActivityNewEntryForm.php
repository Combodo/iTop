<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
namespace Combodo\iTop\Application\UI\Layout\ActivityPanel\ActivityNewEntryForm;
use Combodo\iTop\Application\UI\Component\PopoverMenu\PopoverMenu;
use Combodo\iTop\Application\UI\Layout\UIContentBlock;
use Combodo\iTop\Application\UI\UIBlock;
/**
 * Class ActivityNewEntryForm
 *
 * @package Combodo\iTop\Application\UI\Layout\ActivityPanel\ActivityNewEntryForm
 */
class ActivityNewEntryForm extends UIContentBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-activitynewentryform';
	public const HTML_TEMPLATE_REL_PATH = 'layouts/activity-panel/activitynewentryform/layout';
	public const JS_TEMPLATE_REL_PATH = 'layouts/activity-panel/activitynewentryform/layout';
	public const JS_FILES_REL_PATH = [
		'js/layouts/activity-panel/activity-new-entry-form.js',
	];

	/** @var \Combodo\iTop\Application\UI\Component\Input\RichText\RichText $oFormTextInput */
	protected $oFormTextInput;
	/** @var \Combodo\iTop\Application\UI\Component\PopoverMenu\PopoverMenu */
	protected $oCaseLogSelectionPopOverMenu;
	/** @var array $aTextInputActionButtons */
	protected $aTextInputActionButtons;
	/** @var array $aFormActionButtons */
	protected $aFormActionButtons;

	/**
	 * ActivityNewEntryForm constructor.
	 *
	 * @param null $sName
	 */
	public function __construct($sName = null)
	{
		parent::__construct($sName);
		$this->aFormActionButtons = [];
		$this->aTextInputActionButtons = [];
	}


	/**
	 * @return \Combodo\iTop\Application\UI\Component\Input\RichText\RichText
	 */
	public function GetFormTextInput(): \Combodo\iTop\Application\UI\Component\Input\RichText\RichText
	{
		return $this->oFormTextInput;
	}

	/**
	 * @param \Combodo\iTop\Application\UI\Component\Input\RichText\RichText $oFormTextInput
	 * @return $this
	 */
	public function SetFormTextInput(\Combodo\iTop\Application\UI\Component\Input\RichText\RichText $oFormTextInput): ActivityNewEntryForm
	{
		$this->oFormTextInput = $oFormTextInput;
		return $this;
	}

	/**
	 * @return array
	 */
	public function GetTextInputActionButtons(): array
	{
		return $this->aTextInputActionButtons;
	}

	/**
	 * @param array $aTextInputActionButtons
	 * @return $this
	 */
	public function SetTextInputActionButtons(array $aTextInputActionButtons): ActivityNewEntryForm
	{
		$this->aTextInputActionButtons = $aTextInputActionButtons;
		return $this;
	}

	/**
	 * @param \Combodo\iTop\Application\UI\UIBlock $oTextInputActionButtons
	 */
	public function AddTextInputActionButtons(UIBlock $oTextInputActionButtons): void
	{
		$this->aTextInputActionButtons[] = $oTextInputActionButtons;
	}

	/**
	 * @return mixed
	 */
	public function GetFormActionButtons()
	{
		return $this->aFormActionButtons;
	}

	/**
	 * @param array $aFormActionButtons
	 * @return $this
	 */
	public function SetFormActionButtons(array $aFormActionButtons): ActivityNewEntryForm
	{
		$this->aFormActionButtons = $aFormActionButtons;
		return $this;
	}
	
	/**
	 * @param UIBlock $oFormActionButtons
	 */
	public function AddFormActionButtons(UIBlock $oFormActionButtons): void
	{
		$this->aFormActionButtons[] = $oFormActionButtons;
	}

	/**
	 * @return PopoverMenu
	 */
	public function GetCaseLogSelectionPopOverMenu(): PopoverMenu
	{
		return $this->oCaseLogSelectionPopOverMenu;
	}

	/**
	 * @param PopoverMenu $oCaseLogSelectionPopOverMenu
	 * @return $this
	 */
	public function SetCaseLogSelectionPopOverMenu(PopoverMenu $oCaseLogSelectionPopOverMenu): ActivityNewEntryForm
	{
		$this->oCaseLogSelectionPopOverMenu = $oCaseLogSelectionPopOverMenu;
		return $this;
	}


	public function GetSubBlocks() : array
	{
		$aSubBlocks = [];
		$aSubBlocks[$this->GetFormTextInput()->GetId()] = $this->GetFormTextInput();
		foreach ($this->GetTextInputActionButtons() as $oTextInputActionButton)
		{
			$aSubBlocks[$oTextInputActionButton->GetId()] = $oTextInputActionButton;
		}		
		foreach ($this->GetFormActionButtons() as $oFormActionButton)
		{
			$aSubBlocks[$oFormActionButton->GetId()] = $oFormActionButton;
		}
		$oCaseLogSelectionPopOverMenu = $this->GetCaseLogSelectionPopOverMenu();
		$aSubBlocks[$oCaseLogSelectionPopOverMenu->GetId()] = $oCaseLogSelectionPopOverMenu;
		
		return $aSubBlocks;
	}
	
}