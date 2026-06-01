<?php

/**
 * Copyright (C) 2013-2026 Combodo SAS
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
use Combodo\iTop\Application\WebPage\WebPage;

/**
 * Administrator Account definition screen
 */
class WizStepAdminAccount extends WizardStep
{
	public function GetTitle()
	{
		return 'Administrator Account';
	}

	public function GetPossibleSteps()
	{
		return [WizStepInstallMiscParams::class];
	}

	public function UpdateWizardStateAndGetNextStep($bMoveForward = true): WizardState
	{
		$this->oWizard->SaveParameter('admin_user', '');
		$this->oWizard->SaveParameter('admin_pwd', '');
		$this->oWizard->SaveParameter('confirm_pwd', '');
		$this->oWizard->SaveParameter('admin_language', 'EN US');

		return new WizardState(WizStepInstallMiscParams::class);
	}

	public function Display(SetupPage $oPage): void
	{
		$sAdminUser = $this->oWizard->GetParameter('admin_user', 'admin');
		$sAdminPwd = $this->oWizard->GetParameter('admin_pwd', '');
		$sConfirmPwd = $this->oWizard->GetParameter('confirm_pwd', '');
		$sAdminLanguage = $this->oWizard->GetParameter('admin_language', 'EN US');
		$oPage->add('<h2>Definition of the Administrator Account</h2>');
		$oPage->add('<fieldset>');
		$oPage->add('<legend>Administrator Account</legend>');
		$oPage->add('<table>');
		$oPage->add('<tr><td>Login: </td><td><input id="admin_user" class="ibo-input" name="admin_user" type="text" size="25" maxlength="64" value="'.utils::EscapeHtml($sAdminUser).'"><span id="v_admin_user"/></td></tr>');
		$oPage->add('<tr><td>Password: </td><td><input id="admin_pwd" class="ibo-input"  autocomplete="off" name="admin_pwd" type="password" size="25" maxlength="64" value="'.utils::EscapeHtml($sAdminPwd).'"><span id="v_admin_pwd"/></td></tr>');
		$oPage->add('<tr><td>Confirm password: </td><td><input id="confirm_pwd" class="ibo-input"  autocomplete="off" name="confirm_pwd" type="password" size="25" maxlength="64" value="'.utils::EscapeHtml($sConfirmPwd).'"></td></tr>');
		$sSourceDir = APPROOT.'dictionaries/';
		$aLanguages = SetupUtils::GetAvailableLanguages($sSourceDir);
		$oPage->add('<tr><td>Language: </td><td>');
		$oPage->add(SetupUtils::GetLanguageSelect($sSourceDir, 'admin_language', $sAdminLanguage));
		$oPage->add('</td></tr>');
		$oPage->add('</table>');
		$oPage->add('</fieldset>');
		$oPage->add_ready_script(
			<<<EOF
		$('#admin_user').on('change keyup', function() { WizardUpdateButtons(); } );
		$('#admin_pwd').on('change keyup', function() { WizardUpdateButtons(); } );
		$('#confirm_pwd').on('change keyup', function() { WizardUpdateButtons(); } );
EOF
		);
	}

	/**
	 * Tells whether the "Next" button should be enabled interactively
	 * @return string A piece of javascript code returning either true or false
	 */
	public function JSCanMoveForward()
	{
		return
			<<<EOF
	bRet = ($('#admin_user').val() != '');
	if (!bRet)
	{
		$("#v_admin_user").html('<i class="fas fa-exclamation-triangle setup-invalid-field--icon" title="This field cannot be empty"></i>');
	}
	else
	{
		$("#v_admin_user").html('');
	}
	
	bPasswordsMatch = ($('#admin_pwd').val() == $('#confirm_pwd').val());
	if (!bPasswordsMatch)
	{
		$('#v_admin_pwd').html('<i class="fas fa-exclamation-triangle setup-invalid-field--icon" title="Retyped password does not match"></i>');
	}
	else
	{
		$('#v_admin_pwd').html('');
	}
	bRet = bPasswordsMatch && bRet;
	
	return bRet;
EOF
		;
	}
}
