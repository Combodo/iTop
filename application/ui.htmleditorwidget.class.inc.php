<?php
// Copyright (C) 2010-2015 Combodo SARL
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
 * Class UIHTMLEditorWidget
 * UI wdiget for displaying and editing one-way encrypted passwords
 *
 * @author      Phil Eddies
 * @author      Romain Quetiez
 * @copyright   Copyright (C) 2010-2015 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class UIHTMLEditorWidget 
{
	protected $m_iId;
	protected $m_oAttDef;
	protected $m_sAttCode;
	protected $m_sNameSuffix;
	protected $m_sFieldPrefix;
	protected $m_sHelpText;
	protected $m_sValidationField;
	protected $m_sValue;
	protected $m_sMandatory;
	
	public function __construct($iInputId, $oAttDef, $sNameSuffix, $sFieldPrefix, $sHelpText, $sValidationField, $sValue, $sMandatory)
	{
		$this->m_iId = $iInputId;
		$this->m_oAttDef = $oAttDef;
		$this->m_sAttCode = $oAttDef->GetCode();
		$this->m_sNameSuffix = $sNameSuffix;
		$this->m_sHelpText = $sHelpText;
		$this->m_sValidationField = $sValidationField;
		$this->m_sValue = $sValue;
		$this->m_sMandatory = $sMandatory;
		$this->m_sFieldPrefix = $sFieldPrefix;
	}
	
	/**
	 * Get the HTML fragment corresponding to the HTML editor widget
	 * @param WebPage $oP The web page used for all the output
	 * @param Hash $aArgs Extra context arguments
	 * @return string The HTML fragment to be inserted into the page
	 */
	public function Display(WebPage $oPage, $aArgs = array())
	{
		$iId = $this->m_iId;
		$sCode = $this->m_sAttCode.$this->m_sNameSuffix;
		$sValue = $this->m_sValue;
		$sHelpText = $this->m_sHelpText;
		$sValidationField = $this->m_sValidationField;

		$sHtmlValue = "<table><tr><td><textarea class=\"htmlEditor\" title=\"$sHelpText\" name=\"attr_{$this->m_sFieldPrefix}{$sCode}\" rows=\"10\" cols=\"10\" id=\"$iId\">$sValue</textarea></td><td>$sValidationField</td></tr></table>";

		// Replace the text area with CKEditor
		// To change the default settings of the editor,
		// a) edit the file /js/ckeditor/config.js
		// b) or override some of the configuration settings, using the second parameter of ckeditor()
		$aConfig = array();
		$sLanguage = strtolower(trim(UserRights::GetUserLanguage()));
		$aConfig['language'] = $sLanguage;
		$aConfig['contentsLanguage'] = $sLanguage;
		$aConfig['extraPlugins'] = 'disabler';
		$sWidthSpec = addslashes(trim($this->m_oAttDef->GetWidth()));
		if ($sWidthSpec != '')
		{
			$aConfig['width'] = $sWidthSpec;
		}
		$sHeightSpec = addslashes(trim($this->m_oAttDef->GetHeight()));
		if ($sHeightSpec != '')
		{
			$aConfig['height'] = $sHeightSpec;
		}
		$sConfigJS = json_encode($aConfig);

		$oPage->add_ready_script("$('#$iId').ckeditor(function() { /* callback code */ }, $sConfigJS);"); // Transform $iId into a CKEdit

		// Please read...
		// ValidateCKEditField triggers a timer... calling itself indefinitely
		// This design was the quickest way to achieve the field validation (only checking if the field is blank)
		// because the ckeditor does not fire events like "change" or "keyup", etc.
		// See http://dev.ckeditor.com/ticket/900 => won't fix
		// The most relevant solution would be to implement a plugin to CKEdit, and handle the internal events like: setData, insertHtml, insertElement, loadSnapshot, key, afterUndo, afterRedo

		// Could also be bound to 'instanceReady.ckeditor'
		$oPage->add_ready_script("$('#$iId').bind('validate', function(evt, sFormId) { return ValidateCKEditField('$iId', '', {$this->m_sMandatory}, sFormId, '') } );\n");
		$oPage->add_ready_script("$('#$iId').bind('update', function() { BlockField('cke_$iId', $('#$iId').attr('disabled')); $(this).data('ckeditorInstance').setReadOnly($(this).prop('disabled')); } );\n");

		return $sHtmlValue;
	}
}
?>
