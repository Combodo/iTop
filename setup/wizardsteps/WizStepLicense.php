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
 * License acceptation screen
 */
class WizStepLicense extends WizardStep
{
	public function GetTitle()
	{
		return 'License Agreement';
	}

	public function GetPossibleSteps()
	{
		return ['WizStepDBParams'];
	}

	public function UpdateWizardStateAndGetNextStep($bMoveForward = true)
	{
		$this->oWizard->SaveParameter('accept_license', 'no');
		return ['class' => 'WizStepDBParams', 'state' => ''];
	}

	/**
	 * @return bool true if we need to display a GDPR confirmation
	 * @throws \Exception
	 * @since 2.7.7 3.0.2 3.1.0 N°5037 method creation
	 * @since 2.7.8 3.0.3 3.1.0 N°5758 rename from NeedsRgpdConsent to NeedsGdprConsent
	 */
	private function NeedsGdprConsent()
	{
		$sMode = $this->oWizard->GetParameter('install_mode');

		if ($sMode !== 'install') {
			return false;
		}

		$aModules = SetupUtils::AnalyzeInstallation($this->oWizard);
		return SetupUtils::IsConnectableToITopHub($aModules);
	}

	/**
	 * @param WebPage $oPage
	 */
	public function Display(WebPage $oPage)
	{
		$aLicenses = SetupUtils::GetLicenses();
		$oPage->add_style(
			<<<CSS
fieldset ul {
	max-height: min(30em, 40vh); /* Allow usage of the UI up to 150% zoom */
	overflow: auto;
}
CSS
		);

		$oPage->add('<h2>Licenses agreements for the components of '.ITOP_APPLICATION.'</h2>');
		$oPage->add_style('div a.no-arrow { background:transparent; padding-left:0;}');
		$oPage->add_style('.toggle { cursor:pointer; text-decoration:underline; color:#1C94C4; }');
		$oPage->add('<fieldset>');
		$oPage->add('<legend>Components of '.ITOP_APPLICATION.'</legend>');
		$oPage->add('<ul id="ibo-setup-licenses--components-list">');
		$index = 0;
		foreach ($aLicenses as $oLicense) {
			$oPage->add('<li><b>'.$oLicense->product.'</b>, &copy; '.$oLicense->author.' is licensed under the <b>'.$oLicense->license_type.' license</b>. (<span class="toggle" id="toggle_'.$index.'">Details</span>)');
			$oPage->add('<div id="license_'.$index.'" class="license_text ibo-is-html-content" style="display:none;overflow:auto;max-height:10em;font-size:12px;border:1px #696969 solid;margin-bottom:1em; margin-top:0.5em;padding:0.5em;"><pre>'.$oLicense->text.'</pre></div>');
			$oPage->add_ready_script('$(".license_text a").attr("target", "_blank").addClass("no-arrow");');
			$oPage->add_ready_script('$("#toggle_'.$index.'").on("click", function() { $("#license_'.$index.'").toggle(); } );');
			$index++;
		}
		$oPage->add('</ul>');
		$oPage->add('</fieldset>');
		$sChecked = ($this->oWizard->GetParameter('accept_license', 'no') == 'yes') ? ' checked ' : '';
		$oPage->add('<div class="setup-accept-licenses"><input class="check_select" type="checkbox" name="accept_license" id="accept" value="yes" '.$sChecked.'><label for="accept">I accept the terms of the licenses of the '.count($aLicenses).' components mentioned above.</label></div>');
		if ($this->NeedsGdprConsent()) {
			$oPage->add('<br>');
			$oPage->add('<fieldset>');
			$oPage->add('<legend>European General Data Protection Regulation</legend>');
			$oPage->add('<div class="ibo-setup-licenses--components-list">'.ITOP_APPLICATION.' software is compliant with the processing of personal data according to the European General Data Protection Regulation (GDPR).<p></p>
By installing '.ITOP_APPLICATION.' you agree that some information will be collected by Combodo to help you manage your instances and for statistical purposes.
This data remains anonymous until it is associated to a user account on iTop Hub.</p>
<p>List of collected data available in our <a target="_blank" href="https://www.itophub.io/page/data-privacy">Data privacy section.</a></p><br></div>');
			$oPage->add('<input type="checkbox" class="check_select" id="rgpd_consent">');
			$oPage->add('<label for="rgpd_consent">&nbsp;I accept the processing of my personal data</label>');
			$oPage->add('</fieldset>');
		}
		$oPage->add_ready_script('$(".check_select").on("click change", function() { WizardUpdateButtons(); });');

		$oPage->add_script(
			<<<JS
				function isRgpdConsentOk(){
		            let eRgpdConsent = $("#rgpd_consent");
		            if(eRgpdConsent.length){
		                if(!eRgpdConsent[0].checked){
		                    return false;
		                }
		            }
                    return true;
				}
JS
		);
	}

	/**
	 * Tells whether the "Next" button should be enabled interactively
	 * @return string A piece of javascript code returning either true or false
	 */
	public function JSCanMoveForward()
	{
		return 'return ($("#accept").prop("checked") && isRgpdConsentOk());';
	}

}
