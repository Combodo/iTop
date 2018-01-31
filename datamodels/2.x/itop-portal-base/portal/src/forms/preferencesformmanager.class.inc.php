<?php

// Copyright (C) 2010-2018 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>

namespace Combodo\iTop\Portal\Form;

use Exception;
use IssueLog;
use CMDBSource;
use Dict;
use UserRights;
use Combodo\iTop\Form\FormManager;
use Combodo\iTop\Form\Form;
use Combodo\iTop\Form\Field\HiddenField;
use Combodo\iTop\Form\Field\SelectField;

/**
 * Description of preferencesformmanager
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
class PreferencesFormManager extends FormManager
{
	const FORM_TYPE = 'preferences';

	public function Build()
	{
		// Building the form
		$oForm = new Form('preferences');

		// Adding hidden field with form type
		$oField = new HiddenField('form_type');
		$oField->SetCurrentValue('preferences');
		$oForm->AddField($oField);

		// Adding language field
		$oField = new SelectField('language');
		$oField->SetMandatory(true)
			->SetLabel(Dict::S('UI:Favorites:SelectYourLanguage'))
			->SetCurrentValue(Dict::GetUserLanguage())
			->SetStartsWithNullChoice(false);
		// - Preparing choices
		$aChoices = array();
		foreach (Dict::GetLanguages() as $sCode => $aLanguage)
		{
			$aChoices[$sCode] = $aLanguage['description'] . ' (' . $aLanguage['localized_description'] . ')';
		}
		asort($aChoices);
		$oField->SetChoices($aChoices);
		// - Adding to form
		$oForm->AddField($oField);

		$oForm->Finalize();
		$this->oForm = $oForm;
		$this->oRenderer->SetForm($this->oForm);
	}

	/**
	 * Validates the form and returns an array with the validation status and the messages.
	 * If the form is valid, creates/updates the object.
	 *
	 * eg :
	 *  array(
	 * 	  'status' => true|false
	 * 	  'messages' => array(
	 * 		  'errors' => array()
	 * 	)
	 *
	 * @param array $aArgs
	 * @return array
	 */
	public function OnSubmit($aArgs = null)
	{
		$aData = array(
			'valid' => true,
			'messages' => array(
				'success' => array(),
				'warnings' => array(), // Not used as of today, just to show that the structure is ready for change like this.
				'error' => array()
			)
		);

		// Update object and form
		$this->OnUpdate($aArgs);

		// Check if form valid
		if ($this->oForm->Validate())
		{
			// The try catch is essentially to start a MySQL transaction
			try
			{
				// Starting transaction
				CMDBSource::Query('START TRANSACTION');
				$iFieldChanged = 0;

				// Updating user
				$oCurUser = UserRights::GetUserObject();
				// - Language
				$sLanguage = $this->oForm->GetField('language')->GetCurrentValue();
				if (($sLanguage !== null) && ($oCurUser->Get('language') !== $sLanguage))
				{
					$oCurUser->Set('language', $sLanguage);
					$iFieldChanged++;
				}
				
				// Updating only if preferences changed
				if ($iFieldChanged > 0)
				{
					$oCurUser->AllowWrite(true);
					$oCurUser->DBUpdate();
					$aData['messages']['success'] += array('_main' => array(Dict::S('Brick:Portal:Object:Form:Message:Saved')));
				}

				// Ending transaction with a commit as everything was fine
				CMDBSource::Query('COMMIT');
			}
			catch (Exception $e)
			{
				// End transaction with a rollback as something failed
				CMDBSource::Query('ROLLBACK');
				$aData['valid'] = false;
				$aData['messages']['error'] += array('_main' => array($e->getMessage()));
				IssueLog::Error(__METHOD__ . ' at line ' . __LINE__ . ' : Rollback during submit (' . $e->getMessage() . ')');
			}
		}
		else
		{
			// Handle errors
			$aData['valid'] = false;
			$aData['messages']['error'] += $this->oForm->GetErrorMessages();
		}
		
		return $aData;
	}

	public function OnUpdate($aArgs = null)
	{

		// We build the form
		$this->Build();

		// Then we update it with new values
		if (is_array($aArgs))
		{
			if (isset($aArgs['currentValues']))
			{
				foreach ($aArgs['currentValues'] as $sPreferenceName => $value)
				{
					$this->oForm->GetField($sPreferenceName)->SetCurrentValue($value);
				}
			}
		}
	}

	public function OnCancel($aArgs = null)
	{
		
	}

}
