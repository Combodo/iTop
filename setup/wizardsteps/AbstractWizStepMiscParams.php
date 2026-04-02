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
 * @since 3.0.0 N°4092
 */
abstract class AbstractWizStepMiscParams extends WizardStep
{
	/**
	 * @since 3.0.0 N°4092
	 */
	final protected function AddUseSymlinksFlagOption(WebPage $oPage): void
	{
		if (MFCompiler::CanUseSymbolicLinks()) {
			$sChecked = $this->oWizard->GetParameter('use-symbolic-links', MFCompiler::UseSymbolicLinks()) ? ' checked ' : '';

			$oPage->add('<fieldset>');
			$oPage->add('<legend>Dev parameters</legend>');
			$oPage->p('<input id="use-symbolic-links" name="use-symbolic-links" type="checkbox"'.$sChecked.'><label for="use-symbolic-links">&nbsp;Create symbolic links instead of creating a copy in env-production (useful for debugging extensions)');
			$oPage->add('</fieldset>');
		}
	}

	final protected function AddForceUninstallFlagOption(WebPage $oPage): void
	{
		$sChecked = $this->oWizard->GetParameter('force-uninstall', false) ? ' checked ' : '';
		$oPage->add('<fieldset>');
		$oPage->add('<legend>Advanced parameters</legend>');
		$oPage->p('<input id="force-uninstall" type="checkbox"'.$sChecked.' name="force-uninstall"><label for="force-uninstall">&nbsp;Disable uninstallation checks for extensions');
		$oPage->add('</fieldset>');

		$oPage->add_ready_script(
			<<<'JS'
$("#force-uninstall").on("click", function() {
	let $this = $(this);
	let bForceUninstall = $this.prop("checked");
	if( bForceUninstall && !confirm('Beware, uninstalling extensions flagged as non uninstallable may result in data corruption and application crashes. Are you sure you want to continue ?')){
		$this.prop("checked",false);
	}
});
JS
		);
	}
}
