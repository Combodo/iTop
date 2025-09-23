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
use Combodo\iTop\Application\Helper\CKEditorHelper;
use Combodo\iTop\Application\Helper\WebResourcesHelper;
use Combodo\iTop\Application\TwigBase\Twig\TwigHelper;
use Combodo\iTop\Application\WebPage\WebPage;
use Combodo\iTop\Renderer\BlockRenderer;

/**
 * Class UIHTMLEditorWidget
 * UI wdiget for displaying and editing one-way encrypted passwords
 *
 * @author      Romain Quetiez
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
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
	 *
	 * @param WebPage $oPage The web page used for all the output
	 * @param array $aArgs Extra context arguments
	 *
	 * @return string The HTML fragment to be inserted into the page
	 */
	public function Display(WebPage $oPage, array $aArgs = array()) : string
	{
		$iId = $this->m_iId;
		$sCode = $this->m_sAttCode.$this->m_sNameSuffix;
		$sValue = $this->m_sValue;
		$sHelpText = $this->m_sHelpText;
		$sValidationField = $this->m_sValidationField;
		$aConfig = [];

		$sEncodedValue = CKEditorHelper::PrepareCKEditorValueTextEncodingForTextarea($sValue);
		$sHtmlValue = "<div class=\"field_input_zone field_input_html ibo-input-wrapper\"><textarea class=\"htmlEditor ibo-input-richtext-placeholder\" title=\"$sHelpText\" name=\"attr_{$this->m_sFieldPrefix}{$sCode}\" id=\"$iId\">$sEncodedValue</textarea></div>$sValidationField";

		// Prepare CKEditor size
		$sWidthSpec = addslashes(trim($this->m_oAttDef->GetWidth()));
		if ($sWidthSpec != '') {
			/* N°6543 - the function min allow to keep text inside the column when width is defined*/
			$aConfig['width'] = "min($sWidthSpec,100%)";
		}
		$sHeightSpec = addslashes(trim($this->m_oAttDef->GetHeight()));
		if ($sHeightSpec != '') {
			$aConfig['height'] = $sHeightSpec;
		}

		// Enable CKEditor
		CKEditorHelper::ConfigureCKEditorElementForWebPage($oPage, $iId, $sValue, true, $aConfig);

		// Could also be bound to 'instanceReady.ckeditor'
		$oPage->add_ready_script("$('#$iId').on('validate', function(evt, sFormId) { return ValidateCKEditField('$iId', '', {$this->m_sMandatory}, sFormId, '') } );\n");

		return $sHtmlValue;
	}
}
