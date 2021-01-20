<?php

/*
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\CaseLogEntryFormFactory;


use cmdbAbstractObject;
use Combodo\iTop\Application\UI\Base\Component\Button\Button;
use Combodo\iTop\Application\UI\Base\Component\Button\ButtonUIBlockFactory;
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
	public static function MakeForCaselogTab(DBObject $oObject, string $sCaseLogAttCode, string $sObjectMode = cmdbAbstractObject::DEFAULT_OBJECT_MODE)
	{
		$oCaseLogEntryForm = new CaseLogEntryForm();
		$oCaseLogEntryForm->SetSubmitModeFromHostObjectMode($sObjectMode)
			->AddMainActionButtons(static::PrepareCancelButton())
			->AddMainActionButtons(static::PrepareSendButton()->SetLabel(Dict::S('UI:Button:AddEntryAndWithChoice')))
			->SetSendButtonPopoverMenu(static::PrepareSendActionSelectionPopoverMenu($oObject, $sCaseLogAttCode));

		return $oCaseLogEntryForm;
	}

	/**
	 * @return \Combodo\iTop\Application\UI\Base\Component\Button\Button
	 */
	protected static function PrepareCancelButton(): Button
	{
		return ButtonUIBlockFactory::MakeForSecondaryAction(Dict::S('UI:Button:Cancel'), 'cancel', 'cancel');
	}

	/**
	 * @return \Combodo\iTop\Application\UI\Base\Component\Button\Button
	 */
	protected static function PrepareSendButton(): Button
	{
		$oButton = ButtonUIBlockFactory::MakeForPrimaryAction(Dict::S('UI:Button:Send'), 'send', 'send');
		$oButton->SetIconClass('fas fa-paper-plane');

		return $oButton;
	}

	protected static function PrepareSendActionSelectionPopoverMenu(DBObject $oObject, string $sCaseLogAttCode): PopoverMenu
	{
		$sObjClass = get_class($oObject);

		$oMenu = new PopoverMenu();
		$sSectionId = 'send-actions';
		$oMenu->AddSection($sSectionId);

		$sCaseLogEntryFormDataRole = CaseLogEntryForm::BLOCK_CODE;

		// Standard, just save
		$oMenuItem = PopoverMenuItemFactory::MakeFromApplicationPopupMenuItem(
			new JSPopupMenuItem(
				CaseLogEntryForm::BLOCK_CODE.'--add-action--'.$sCaseLogAttCode.'--save',
				Dict::S('UI:Button:Save'),
				<<<JS
$(this).closest('[data-role="{$sCaseLogEntryFormDataRole}"]').trigger('add_to_caselog.caselog_entry_form.itop', {caselog_att_code: '{$sCaseLogAttCode}'});
JS
			)
		);
		$oMenu->AddItem($sSectionId, $oMenuItem);

		// Transitions
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
								$aStimuli[$sStimulusCode]->GetLabel(),
								<<<JS
$(this).closest('[data-role="{$sCaseLogEntryFormDataRole}"]').trigger('add_to_caselog.caselog_entry_form.itop', {caselog_att_code: '{$sCaseLogAttCode}', stimulus_code: '{$sStimulusCode}'});
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