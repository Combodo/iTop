<?php

/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Layout\ActivityPanel\ActivityNewEntryFormFactory;


use Combodo\iTop\Application\UI\Component\Button\ButtonFactory;
use Combodo\iTop\Application\UI\Component\Input\RichText\RichText;
use Combodo\iTop\Application\UI\Layout\ActivityPanel\ActivityNewEntryForm\ActivityNewEntryForm;

/**
 * Class ActivityNewEntryFormFactory
 *
 * @internal
 * @author Stephen Abello <stephen.abello@combodo.com>
 * @package Combodo\iTop\Application\UI\Layout\ActivityPanel\ActivityNewEntryFormFactory
 * @since 2.8.0
 */
class ActivityNewEntryFormFactory
{
	public static function MakeForObjectDetailsActivityPanel(): ActivityNewEntryForm
	{
		$oActivityNewEntryForm = new ActivityNewEntryForm();
		$oActivityNewEntryForm->SetFormTextInput(new RichText());
		$oActivityNewEntryForm->AddFormActionButtons(ButtonFactory::MakeForSecondaryAction('Cancel'));
		$oActivityNewEntryForm->AddFormActionButtons(ButtonFactory::MakeForValidationAction('Send'));
		$oActivityNewEntryForm->AddFormActionButtons(ButtonFactory::MakeForValidationAction('Send....'));
		$oActivityNewEntryForm->AddTextInputActionButtons(ButtonFactory::MakeForSecondaryAction('Templates')->SetColor('blue'));
		return $oActivityNewEntryForm;
	}
}