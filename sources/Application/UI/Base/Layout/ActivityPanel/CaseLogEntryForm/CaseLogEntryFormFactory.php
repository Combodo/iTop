<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\CaseLogEntryForm;


use cmdbAbstractObject;
use Combodo\iTop\Application\UI\Base\Component\Button\Button;
use Combodo\iTop\Application\UI\Base\Component\Button\ButtonUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\ButtonGroup\ButtonGroupUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenu;
use Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenuItem\PopoverMenuItemFactory;
use Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\CaseLogEntryForm\CaseLogEntryForm;
use DBObject;
use DBObjectSet;
use Dict;
use JSPopupMenuItem;
use MetaModel;
use UserRights;

/**
 * Class CaseLogEntryFormFactory
 *
 * @internal
 * @author Stephen Abello <stephen.abello@combodo.com>
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\CaseLogEntryFormFactory
 * @since 3.0.0
 */
class CaseLogEntryFormFactory
{
	public static function MakeForCaselogTab(DBObject $oObject, string $sCaseLogAttCode, string $sObjectMode = cmdbAbstractObject::DEFAULT_DISPLAY_MODE)
	{
		$oCaseLogEntryForm = new CaseLogEntryForm($oObject, $sCaseLogAttCode);
		$oCaseLogEntryForm->SetSubmitModeFromHostObjectMode($sObjectMode)
			->AddMainActionButtons(static::PrepareCancelButton());

		$oSaveButton = static::PrepareSaveButton();
		$oTransitionsMenu = static::PrepareTransitionsSelectionPopoverMenu($oObject, $sCaseLogAttCode);
		if (true === $oTransitionsMenu->HasItems()) {
			$oButtonGroup = ButtonGroupUIBlockFactory::MakeButtonWithOptionsMenu($oSaveButton, $oTransitionsMenu);
			$oCaseLogEntryForm->AddMainActionButtons($oButtonGroup);
		} else {
			$oCaseLogEntryForm->AddMainActionButtons($oSaveButton);
		}

		return $oCaseLogEntryForm;
	}

	/**
	 * @return \Combodo\iTop\Application\UI\Base\Component\Button\Button
	 */
	protected static function PrepareCancelButton(): Button
	{
		return ButtonUIBlockFactory::MakeForCancel(Dict::S('UI:Button:Cancel'), 'cancel', 'cancel');
	}

	/**
	 * @return \Combodo\iTop\Application\UI\Base\Component\Button\Button
	 */
	protected static function PrepareSaveButton(): Button
	{
		$oButton = ButtonUIBlockFactory::MakeForPrimaryAction(Dict::S('UI:Button:Send'), 'save', 'save');
		$oButton->SetIconClass('fas fa-paper-plane');

		return $oButton;
	}

	protected static function PrepareTransitionsSelectionPopoverMenu(DBObject $oObject, string $sCaseLogAttCode): PopoverMenu
	{
		$sObjClass = get_class($oObject);

		$oMenu = new PopoverMenu();
		$sSectionId = 'send-actions';
		$oMenu->AddSection($sSectionId);

		$sCaseLogEntryFormDataRole = CaseLogEntryForm::BLOCK_CODE;

		// Note: This code is inspired from cmdbAbstract::DisplayModifyForm(), it might be better to factorize it
		$aTransitions = $oObject->EnumTransitions();
		if (!isset($aExtraParams['custom_operation']) && count($aTransitions)) {
			$oSetToCheckRights = DBObjectSet::FromObject($oObject);
			$aStimuli = Metamodel::EnumStimuli($sObjClass);
			foreach ($aTransitions as $sStimulusCode => $aTransitionDef) {
				$iActionAllowed = (get_class($aStimuli[$sStimulusCode]) == 'StimulusUserAction') ? UserRights::IsStimulusAllowed($sObjClass,
					$sStimulusCode, $oSetToCheckRights) : UR_ALLOWED_NO;
				switch ($iActionAllowed) {
					case UR_ALLOWED_YES:
						$oMenuItem = PopoverMenuItemFactory::MakeFromApplicationPopupMenuItem(
							new JSPopupMenuItem(
								CaseLogEntryForm::BLOCK_CODE.'--add-action--'.$sCaseLogAttCode.'--stimulus--'.$sStimulusCode,
								Dict::Format('UI:Button:SendAnd', $aStimuli[$sStimulusCode]->GetLabel()),
								<<<JS
$(this).closest('[data-role="{$sCaseLogEntryFormDataRole}"]').trigger('save_entry.caselog_entry_form.itop', {stimulus_code: '{$sStimulusCode}'});
JS
							)
						);
						$oMenu->AddItem($sSectionId, $oMenuItem);
						break;
				}
			}
		}

		return $oMenu;
	}
}