<?php

/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Layout\ActivityPanel\ActivityNewEntryFormFactory;


use Combodo\iTop\Application\UI\Component\Button\ButtonFactory;
use Combodo\iTop\Application\UI\Component\Input\RichText\RichText;
use Combodo\iTop\Application\UI\Component\PopoverMenu\PopoverMenuFactory;
use Combodo\iTop\Application\UI\Layout\ActivityPanel\ActivityNewEntryForm\ActivityNewEntryForm;

/**
 * Class ActivityNewEntryFormFactory
 *
 * @internal
 * @author Stephen Abello <stephen.abello@combodo.com>
 * @package Combodo\iTop\Application\UI\Layout\ActivityPanel\ActivityNewEntryFormFactory
 * @since 3.0.0
 */
class ActivityNewEntryFormFactory
{
	public static function MakeForObjectDetailsActivityPanel($aCaseLogs): ActivityNewEntryForm
	{
		$oActivityNewEntryForm = new ActivityNewEntryForm();
		$oActivityNewEntryForm->SetFormTextInput(new RichText());
		$oActivityNewEntryForm->AddFormActionButtons(ButtonFactory::MakeForSecondaryAction('Cancel')
			->SetOnClickJsCode("$(this).parents('[data-role=\"ibo-activity-new-entry-form--action-buttons--right-actions\"]').trigger('cancel');"));
		$oActivityNewEntryForm->AddFormActionButtons(ButtonFactory::MakeForPrimaryAction('Send')
			->SetColor('cyan')
			->SetIconClass('fas fa-paper-plane')
			->SetOnClickJsCode("$(this).parents('[data-role=\"ibo-activity-new-entry-form--action-buttons--right-actions\"]').trigger('submit');"));
		//$oActivityNewEntryForm->AddTextInputActionButtons(ButtonFactory::MakeForSecondaryAction('Templates')->SetColor('blue'));
		
		$oActivityNewEntryForm->SetCaseLogSelectionPopOverMenu(PopoverMenuFactory::MakeMenuForActivityNewEntryFormSubmit($aCaseLogs));
		
		return $oActivityNewEntryForm;
	}
}