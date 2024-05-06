<?php

/**
 * Copyright (C) 2013-2024 Combodo SAS
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

namespace Combodo\iTop\Portal\Form;

use Combodo\iTop\Application\Helper\Session;
use Combodo\iTop\Form\Field\HiddenField;
use Combodo\iTop\Form\Field\PasswordField;
use Combodo\iTop\Form\Form;
use Combodo\iTop\Form\FormManager;
use Dict;
use Exception;
use IssueLog;
use UserRights;

/**
 * Description of PasswordFormManager
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since  2.3.0
 */
class PasswordFormManager extends FormManager
{
	/** @var string FORM_TYPE */
	const FORM_TYPE = 'change_password';

	/**
	 * @throws \Exception
	 */
	public function Build()
	{
		// Building the form
		$oForm = new Form('change_password');

		$oForm->SetTransactionId(\utils::GetNewTransactionId());

		// Adding hidden field with form type
		$oField = new HiddenField('form_type');
		$oField->SetCurrentValue('change_password');
		$oForm->AddField($oField);

		// Adding old password field
		$oField = new PasswordField('old_password');
		$oField->SetMandatory(true)
			->SetLabel(Dict::S('UI:Login:OldPasswordPrompt'));
		$oForm->AddField($oField);
		// Adding new password field
		$oField = new PasswordField('new_password');
		$oField->SetMandatory(true)
			->SetLabel(Dict::S('Brick:Portal:UserProfile:Password:ChoosePassword'));
		$oForm->AddField($oField);
		// Adding confirm password field
		$oField = new PasswordField('confirm_password');
		$oField->SetMandatory(true)
			->SetLabel(Dict::S('Brick:Portal:UserProfile:Password:ConfirmPassword'));
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
	 *      'status' => true|false
	 *      'messages' => array(
	 *          'errors' => array()
	 *    )
	 *
	 * @param array $aArgs
	 *
	 * @return array
	 *
	 * @throws \Exception
	 */
	public function OnSubmit($aArgs = null)
	{
		$aData = parent::OnSubmit($aArgs);

		if (! $aData['valid']) {
			return $aData;
		}

		// Update object and form
		$this->OnUpdate($aArgs);

		// Check if form valid
		if ($this->oForm->Validate())
		{
			// The try catch is essentially to start a MySQL transaction
			try
			{
				// Updating password
				$sAuthUser = Session::Get('auth_user');
				$sOldPassword = $this->oForm->GetField('old_password')->GetCurrentValue();
				$sNewPassword = $this->oForm->GetField('new_password')->GetCurrentValue();
				$sConfirmPassword = $this->oForm->GetField('confirm_password')->GetCurrentValue();

				if ($sOldPassword !== '' && $sNewPassword !== '' && $sConfirmPassword !== '')
				{
					if (!UserRights::CanChangePassword())
					{
						$aData['valid'] = false;
						$aData['messages']['error'] += array(
							'_main' => array(
								Dict::Format('Brick:Portal:UserProfile:Password:CantChangeContactAdministrator', ITOP_APPLICATION_SHORT),
							),
						);
					}
					else
					{
						if (!UserRights::CheckCredentials($sAuthUser, $sOldPassword))
						{
							$aData['valid'] = false;
							$aData['messages']['error'] += array('old_password' => array(Dict::S('UI:Login:IncorrectOldPassword')));
						}
						else
						{
							if ($sNewPassword !== $sConfirmPassword)
							{
								$aData['valid'] = false;
								$aData['messages']['error'] += array('confirm_password' => array(Dict::S('UI:Login:RetypePwdDoesNotMatch')));
							}
							elseif ($sNewPassword === $sOldPassword)
							{
								$aData['valid'] = false;
								$aData['messages']['error'] += array('new_password' => array(Dict::S('UI:Login:PasswordNotChanged')));
							}
							else
							{
								try {
									if (!UserRights::ChangePassword($sOldPassword, $sNewPassword))
									{
										$aData['valid'] = false;
										$aData['messages']['error'] += array(
											'confirm_password' => array(
												Dict::Format('Brick:Portal:UserProfile:Password:CantChangeForUnknownReason',
													ITOP_APPLICATION_SHORT),
											),
										);
									}
									else
									{
										$aData['messages']['success'] += array('_main' => array(Dict::S('Brick:Portal:Object:Form:Message:Saved')));
									}
								}
								catch (\CoreCannotSaveObjectException $e)
								{
									$aData['valid'] = false;
									$aData['messages']['error'] += array(
										'new_password' => $e->getIssues(),
										'confirm_password' => array(),
									);
								}
							}
						}
					}
				}
			}
			catch (Exception $e)
			{
				$aData['valid'] = false;
				$aData['messages']['error'] += array('_main' => array($e->getMessage()));
				IssueLog::Error(__METHOD__.' at line '.__LINE__.' : Exception during submit ('.$e->getMessage().')');
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

	/**
	 * @param array $aArgs
	 *
	 * @throws \Exception
	 */
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

	/**
	 * @param array $aArgs
	 */
	public function OnCancel($aArgs = null)
	{

	}

}
