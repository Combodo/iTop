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

/**
 * Database Connection parameters screen
 */
use Combodo\iTop\Application\WebPage\WebPage;

class WizStepDBParams extends WizardStep
{
	public function GetTitle()
	{
		return 'Database Configuration';
	}

	public function GetPossibleSteps()
	{
		return [WizStepAdminAccount::class];
	}

	public function UpdateWizardStateAndGetNextStep($bMoveForward = true)
	{
		$this->oWizard->SaveParameter('db_server', '');
		$this->oWizard->SaveParameter('db_user', '');
		$this->oWizard->SaveParameter('db_pwd', '');
		$this->oWizard->SaveParameter('db_name', '');
		$this->oWizard->SaveParameter('db_prefix', '');
		$this->oWizard->SaveParameter('new_db_name', '');
		$this->oWizard->SaveParameter('create_db', '');
		$this->oWizard->SaveParameter('db_new_name', '');
		$this->oWizard->SaveParameter('db_tls_enabled', false);
		$this->oWizard->SaveParameter('db_tls_ca', '');

		return ['class' => WizStepAdminAccount::class, 'state' => ''];
	}

	public function Display(WebPage $oPage)
	{
		$oPage->add('<h2>Configuration of the database connection:</h2>');
		$sDBServer = $this->oWizard->GetParameter('db_server', '');
		$sDBUser = $this->oWizard->GetParameter('db_user', '');
		$sDBPwd = $this->oWizard->GetParameter('db_pwd', '');
		$sDBName = $this->oWizard->GetParameter('db_name', '');
		$sDBPrefix = $this->oWizard->GetParameter('db_prefix', '');
		$sTlsEnabled = $this->oWizard->GetParameter('db_tls_enabled', '');
		$sTlsCA = $this->oWizard->GetParameter('db_tls_ca', '');
		$sNewDBName = $this->oWizard->GetParameter('db_new_name', false);

		$oPage->add('<table>');
		SetupUtils::DisplayDBParameters(
			$oPage,
			true,
			$sDBServer,
			$sDBUser,
			$sDBPwd,
			$sDBName,
			$sDBPrefix,
			$sTlsEnabled,
			$sTlsCA,
			$sNewDBName
		);
		$sAuthentToken = $this->oWizard->GetParameter('authent', '');
		$oPage->add('<input type="hidden" id="authent_token" value="'.$sAuthentToken.'"/>');
		$oPage->add('</table>');
		$sCreateDB = $this->oWizard->GetParameter('create_db', 'yes');
		if ($sCreateDB == 'no') {
			$oPage->add_ready_script('$("#existing_db").prop("checked", true);');
		} else {
			$oPage->add_ready_script('$("#create_db").prop("checked", true);');
		}
	}

	public function AsyncAction(WebPage $oPage, $sCode, $aParameters)
	{
		switch ($sCode) {
			case 'check_db':
				SetupUtils::AsyncCheckDB($oPage, $aParameters);
				break;
		}
	}

	/**
	 * Tells whether the "Next" button should be enabled interactively
	 * @return string A piece of javascript code returning either true or false
	 */
	public function JSCanMoveForward()
	{
		return
			<<<EOF
	if ($("#wiz_form").data("db_connection") === "error") return false;

	var bRet = true;
	bRet = ValidateField("db_name", true) && bRet;
	bRet = ValidateField("db_new_name", true) && bRet;
	bRet = ValidateField("db_prefix", true) && bRet;

	return bRet;
EOF
		;
	}
}
