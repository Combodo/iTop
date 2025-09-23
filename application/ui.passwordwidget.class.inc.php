<?php
// Copyright (C) 2010-2024 Combodo SAS
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

/**
 * Class UIPasswordWidget
 * UI wdiget for displaying and editing one-way encrypted passwords
 *
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Application\WebPage\WebPage;

require_once(APPROOT.'/application/displayblock.class.inc.php');

class UIPasswordWidget 
{
	protected static $iWidgetIndex = 0;
	protected $sAttCode;
	protected $sNameSuffix;
	protected $iId;
	
	public function __construct($sAttCode, $iInputId, $sNameSuffix = '')
	{
		self::$iWidgetIndex++;
		$this->sAttCode = $sAttCode;
		$this->sNameSuffix = $sNameSuffix;
		$this->iId = $iInputId;
	}
	
	/**
	 * Get the HTML fragment corresponding to the linkset editing widget
	 * @param WebPage $oP The web page used for all the output
	 * @param Hash $aArgs Extra context arguments
	 * @return string The HTML fragment to be inserted into the page
	 */
	public function Display(WebPage $oPage, $aArgs = array())
	{
		$oPage->add_dict_entry('UI:Component:Input:Password:DoesNotMatch');

		$sCode = $this->sAttCode.$this->sNameSuffix;
		$iWidgetIndex = self::$iWidgetIndex;

		$aPasswordValues = utils::ReadPostedParam("attr_{$sCode}", null, 'raw_data');
		$sPasswordValue = $aPasswordValues ? $aPasswordValues['value'] : '*****';
		$sConfirmPasswordValue = $aPasswordValues ? $aPasswordValues['confirm'] : '*****';
		$sChangedValue = (($sPasswordValue != '*****') || ($sConfirmPasswordValue != '*****')) ? 1 : 0;
		$sHtmlValue = '';
		$sHtmlValue .= '<div class="field_input_zone field_input_onewaypassword ibo-input-wrapper ibo-input-one-way-password-wrapper">';
		$sHtmlValue .= '<input class="ibo-input" type="password" maxlength="255" name="attr_'.$sCode.'[value]" id="'.$this->iId.'" value="'.utils::EscapeHtml($sPasswordValue).'"/>';
		$sHtmlValue .= '<div class="ibo-input-wrapper ibo-input-wrapper--with-buttons"><input class="ibo-input" type="password" maxlength="255" id="'.$this->iId.'_confirm" value="'.utils::EscapeHtml($sConfirmPasswordValue).'" name="attr_'.$sCode.'[confirm]"/>';
		$sHtmlValue .= '<div class="ibo-input-select--action-buttons"><div class="ibo-input-select--action-button ibo-input-select--action-button--create" data-tooltip-content="'.Dict::S('UI:PasswordConfirm').'"><i class="fas fa-question-circle"></i></div></div></div>';
		$sHtmlValue .= '<button id="'.$this->iId.'_reset" class="ibo-button ibo-is-regular ibo-is-neutral" onClick="ResetPwd(\''.$this->iId.'\');">';
		$sHtmlValue .= '<span class="ibo-button--icon fas fa-undo"></span><span class="ibo-button--label">'.Dict::S('UI:Button:ResetPassword').'</span></button>';
		$sHtmlValue .= '<input type="hidden" id="'.$this->iId.'_changed" name="attr_'.$sCode.'[changed]" value="'.$sChangedValue.'"/>';
		$sHtmlValue .= '</div>';

		$sHtmlValue .= '<span class="form_validation ibo-field-validation" id="v_'.$this->iId.'"></span><span class="field_status" id="fstatus_'.$this->iId.'"></span>';

		$oPage->add_ready_script("$('#$this->iId').on('keyup change', function(evt) { return PasswordFieldChanged('$this->iId') } );"); // Bind to a custom event: validate
		$oPage->add_ready_script("$('#$this->iId').on('keyup change validate', function(evt, sFormId) { return ValidatePasswordField('$this->iId', sFormId) } );"); // Bind to a custom event: validate
		$oPage->add_ready_script("$('#{$this->iId}_confirm').on('keyup change', function(evt, sFormId) { return ValidatePasswordField('$this->iId', sFormId) } );"); // Bind to a custom event: validate
		$oPage->add_ready_script("$('#{$this->iId}').on('update', function(evt, sFormId)
			{
				if ($(this).prop('disabled'))
				{
					$('#{$this->iId}_confirm').prop('disabled', true);
					$('#{$this->iId}_changed').prop('disabled', true);
					$('#{$this->iId}_reset').prop('disabled', true);
				}
				else
				{
					$('#{$this->iId}_confirm').prop('disabled', false);
					$('#{$this->iId}_changed').prop('disabled', false);
					$('#{$this->iId}_reset').prop('disabled', false);
				}
			}
		);"); // Bind to a custom event: update to handle enabling/disabling
		return $sHtmlValue;
	}
}
?>
