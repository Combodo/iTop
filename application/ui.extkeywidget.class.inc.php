<?php
/**
 * Copyright (C) 2013-2020 Combodo SARL
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

require_once(APPROOT.'/application/webpage.class.inc.php');
require_once(APPROOT.'/application/displayblock.class.inc.php');

/**
 * Class UIExtKeyWidget
 * UI widget for displaying and editing external keys when
 * A simple drop-down list is not enough...
 *
 * The layout is the following
 *
 * +-- #label_<id> (input)-------+  +-----------+
 * |                             |  | Browse... |
 * +-----------------------------+  +-----------+
 *
 * And the popup dialog has the following layout:
 *
 * +------------------- ac_dlg_<id> (div)-----------+
 * + +--- ds_<id> (div)---------------------------+ |
 * | | +------------- fs_<id> (form)------------+ | |
 * | | | +--------+---+                         | | |
 * | | | | Class  | V |                         | | |
 * | | | +--------+---+                         | | |
 * | | |                                        | | |
 * | | |    S e a r c h   F o r m               | | |
 * | | |                           +--------+   | | |
 * | | |                           | Search |   | | |
 * | | |                           +--------+   | | |
 * | | +----------------------------------------+ | |
 * | +--------------+-dh_<id>-+--------------------+ |
 * |                \ Search /                      |
 * |                 +------+                       |
 * | +--- fr_<id> (form)--------------------------+ |
 * | | +------------ dr_<id> (div)--------------+ | |
 * | | |                                        | | |
 * | | |      S e a r c h  R e s u l t s        | | |
 * | | |                                        | | |
 * | | +----------------------------------------+ | |
 * | |   +--------+    +-----+                    | |
 * | |   | Cancel |    | Add |                    | |
 * | |   +--------+    +-----+                    | |
 * | +--------------------------------------------+ |
 * +------------------------------------------------+
 */
class UIExtKeyWidget
{
	const ENUM_OUTPUT_FORMAT_CSV = 'csv';
	const ENUM_OUTPUT_FORMAT_JSON = 'json';

	protected $iId;
	protected $sTargetClass;
	protected $sAttCode;
	protected $bSearchMode;

	//public function __construct($sAttCode, $sClass, $sTitle, $oAllowedValues, $value, $iInputId, $bMandatory, $sNameSuffix = '', $sFieldPrefix = '', $sFormPrefix = '')
	static public function DisplayFromAttCode($oPage, $sAttCode, $sClass, $sTitle, $oAllowedValues, $value, $iInputId, $bMandatory, $sFieldName = '', $sFormPrefix = '', $aArgs, $bSearchMode = false)
	{
		$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
		$sTargetClass = $oAttDef->GetTargetClass();
		$iMaxComboLength = $oAttDef->GetMaximumComboLength();
		$bAllowTargetCreation = $oAttDef->AllowTargetCreation();
		if (!$bSearchMode)
		{
			$sDisplayStyle = $oAttDef->GetDisplayStyle();
		}
		else
		{
			$sDisplayStyle = 'select'; // In search mode, always use a drop-down list
		}
		$oWidget = new UIExtKeyWidget($sTargetClass, $iInputId, $sAttCode, $bSearchMode);
		return $oWidget->Display($oPage, $iMaxComboLength, $bAllowTargetCreation, $sTitle, $oAllowedValues, $value, $iInputId, $bMandatory, $sFieldName, $sFormPrefix, $aArgs, null, $sDisplayStyle);
	}

	public function __construct($sTargetClass, $iInputId, $sAttCode = '', $bSearchMode = false)
	{
		$this->sTargetClass = $sTargetClass;
		$this->iId = $iInputId;
		$this->sAttCode = $sAttCode;
		$this->bSearchMode = $bSearchMode;
	}

	/**
	 * Get the HTML fragment corresponding to the ext key editing widget
	 * @param WebPage $oP The web page used for all the output
	 * @param array $aArgs Extra context arguments
	 * @return string The HTML fragment to be inserted into the page
	 */
	public function Display(WebPage $oPage, $iMaxComboLength, $bAllowTargetCreation, $sTitle, DBObjectset $oAllowedValues, $value, $iInputId, $bMandatory, $sFieldName, $sFormPrefix = '', $aArgs = array(), $bSearchMode = null, $sDisplayStyle = 'select', $bSearchMultiple = true)
	{
		if (!is_null($bSearchMode))
		{
			$this->bSearchMode = $bSearchMode;
		}
		$sTitle = addslashes($sTitle);
		$oPage->add_linked_script('../js/extkeywidget.js');
		$oPage->add_linked_script('../js/forms-json-utils.js');

		$bCreate = (!$this->bSearchMode) && (UserRights::IsActionAllowed($this->sTargetClass, UR_ACTION_BULK_MODIFY) && $bAllowTargetCreation);
		$bExtensions = true;
		$sMessage = Dict::S('UI:Message:EmptyList:UseSearchForm');
		$sAttrFieldPrefix = ($this->bSearchMode) ? '' : 'attr_';

		$sHTMLValue = "<div class=\"field_input_zone field_input_extkey\">";
		$sFilter = addslashes($oAllowedValues->GetFilter()->ToOQL());
		if($this->bSearchMode)
		{
			$sWizHelper = 'null';
			$sWizHelperJSON = "''";
			$sJSSearchMode = 'true';
		}
		else
		{
			if (isset($aArgs['wizHelper']))
			{
				$sWizHelper = $aArgs['wizHelper'];
			}
			else
			{
				$sWizHelper = 'oWizardHelper'.$sFormPrefix;
			}
			$sWizHelperJSON = $sWizHelper.'.UpdateWizardToJSON()';
			$sJSSearchMode = 'false';
		}
		if (is_null($oAllowedValues))
		{
			throw new Exception('Implementation: null value for allowed values definition');
		}
		$oAllowedValues->SetShowObsoleteData(utils::ShowObsoleteData());
		// Don't automatically launch the search if the table is huge
		$bDoSearch = !utils::IsHighCardinality($this->sTargetClass);
		$sJSDoSearch = $bDoSearch ? 'true' : 'false';

		// We just need to compare the number of entries with MaxComboLength, so no need to get the real count.
		if (!$oAllowedValues->CountExceeds($iMaxComboLength))
		{
            // Discrete list of values, use a SELECT or RADIO buttons depending on the config
			switch($sDisplayStyle)
			{
				case 'radio':
				case 'radio_horizontal':
				case 'radio_vertical':
				$sValidationField = null;

				$bVertical = ($sDisplayStyle != 'radio_horizontal');
				$bExtensions = false;
				$oAllowedValues->Rewind();
				$aAllowedValues = array();
				while($oObj = $oAllowedValues->Fetch())
				{
					$aAllowedValues[$oObj->GetKey()] = $oObj->GetName();
				}
				$sHTMLValue .= $oPage->GetRadioButtons($aAllowedValues, $value, $this->iId, "{$sAttrFieldPrefix}{$sFieldName}", false /*  $bMandatory will be placed manually */, $bVertical, $sValidationField);
				$aEventsList[] ='change';
				break;

				case 'select':
				case 'list':
				default:

				$sHelpText = ''; //$this->oAttDef->GetHelpOnEdition();
				$sHTMLValue .= "<div class=\"field_select_wrapper\">\n";

				if ($this->bSearchMode)
				{
					if ($bSearchMultiple)
					{
						$sHTMLValue .= "<select class=\"multiselect\" multiple title=\"$sHelpText\" name=\"{$sAttrFieldPrefix}{$sFieldName}[]\" id=\"$this->iId\">\n";
					}
					else
					{
						$sHTMLValue .= "<select title=\"$sHelpText\" name=\"{$sAttrFieldPrefix}{$sFieldName}\" id=\"$this->iId\">\n";
						$sDisplayValue = isset($aArgs['sDefaultValue']) ? $aArgs['sDefaultValue'] : Dict::S('UI:SearchValue:Any');
						$sHTMLValue .= "<option value=\"\">$sDisplayValue</option>\n";
					}
				}
				else
				{
					$sHTMLValue .= "<select title=\"$sHelpText\" name=\"{$sAttrFieldPrefix}{$sFieldName}\" id=\"$this->iId\">\n";
					$sHTMLValue .= "<option value=\"\">".Dict::S('UI:SelectOne')."</option>\n";
				}

				$oAllowedValues->Rewind();
				while($oObj = $oAllowedValues->Fetch())
				{
					$key = $oObj->GetKey();
					$display_value = $oObj->GetName();

					if (($oAllowedValues->Count() == 1) && ($bMandatory == 'true') )
					{
						// When there is only once choice, select it by default
						$sSelected = 'selected';
						if($value != $key)
						{
							$oPage->add_ready_script(
								<<<EOF
$('#$this->iId').attr('data-validate','dependencies');
EOF
							);
						}
					}
					else
					{
						$sSelected = (is_array($value) && in_array($key, $value)) || ($value == $key) ? 'selected' : '';
					}
					$sHTMLValue .= "<option value=\"$key\" $sSelected>$display_value</option>\n";
				}
				$sHTMLValue .= "</select>\n";
				$sHTMLValue .= "</div>\n";

				if (($this->bSearchMode) && $bSearchMultiple)
				{
					$aOptions = array(
						'header' => true,
						'checkAllText' => Dict::S('UI:SearchValue:CheckAll'),
						'uncheckAllText' => Dict::S('UI:SearchValue:UncheckAll'),
						'noneSelectedText' => Dict::S('UI:SearchValue:Any'),
						'selectedText' => Dict::S('UI:SearchValue:NbSelected'),
						'selectedList' => 1,
					);
					$sJSOptions = json_encode($aOptions);
					$oPage->add_ready_script("$('.multiselect').multiselect($sJSOptions);");
				}
				$oPage->add_ready_script(
<<<EOF
		oACWidget_{$this->iId} = new ExtKeyWidget('{$this->iId}', '{$this->sTargetClass}', '$sFilter', '$sTitle', true, $sWizHelper, '{$this->sAttCode}', $sJSSearchMode, $sJSDoSearch);
		oACWidget_{$this->iId}.emptyHtml = "<div style=\"background: #fff; border:0; text-align:center; vertical-align:middle;\"><p>$sMessage</p></div>";
		$('#$this->iId').bind('update', function() { oACWidget_{$this->iId}.Update(); } );
		$('#$this->iId').bind('change', function() { $(this).trigger('extkeychange') } );

EOF
				);
			} // Switch
		}
		else
		{
			// Too many choices, use an autocomplete
			// Check that the given value is allowed
			$oSearch = $oAllowedValues->GetFilter();
			$oSearch->AddCondition('id', $value);
			$oSet = new DBObjectSet($oSearch);
			if ($oSet->Count() == 0)
			{
				$value = null;
			}

			if (is_null($value) || ($value == 0)) // Null values are displayed as ''
			{
				$sDisplayValue = isset($aArgs['sDefaultValue']) ? $aArgs['sDefaultValue'] : '';
			}
			else
			{
				$sDisplayValue = $this->GetObjectName($value);
			}
			$iMinChars = isset($aArgs['iMinChars']) ? $aArgs['iMinChars'] : 3; //@@@ $this->oAttDef->GetMinAutoCompleteChars();

			// the input for the auto-complete
			$sHTMLValue .= "<input class=\"field_autocomplete\" type=\"text\" id=\"label_$this->iId\" value=\"$sDisplayValue\"/>";
			$sHTMLValue .= "<span class=\"field_input_btn\"><div class=\"mini_button\"  id=\"mini_search_{$this->iId}\" onClick=\"oACWidget_{$this->iId}.Search();\"><i class=\"fas fa-search\"></i></div></span>";

			// another hidden input to store & pass the object's Id
			$sHTMLValue .= "<input type=\"hidden\" id=\"$this->iId\" name=\"{$sAttrFieldPrefix}{$sFieldName}\" value=\"".htmlentities($value, ENT_QUOTES, 'UTF-8')."\" />\n";

			$JSSearchMode = $this->bSearchMode ? 'true' : 'false';
			// Scripts to start the autocomplete and bind some events to it
			$oPage->add_ready_script(
<<<EOF
		oACWidget_{$this->iId} = new ExtKeyWidget('{$this->iId}', '{$this->sTargetClass}', '$sFilter', '$sTitle', false, $sWizHelper, '{$this->sAttCode}', $sJSSearchMode, $sJSDoSearch);
		oACWidget_{$this->iId}.emptyHtml = "<div style=\"background: #fff; border:0; text-align:center; vertical-align:middle;\"><p>$sMessage</p></div>";
		$('#label_$this->iId').autocomplete(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php', { scroll:true, minChars:{$iMinChars}, autoFill:false, matchContains:true, mustMatch: true, keyHolder:'#{$this->iId}', extraParams:{operation:'ac_extkey', sTargetClass:'{$this->sTargetClass}',sFilter:'$sFilter',bSearchMode:$JSSearchMode, json: function() { return $sWizHelperJSON; } }});
		$('#label_$this->iId').keyup(function() { if ($(this).val() == '') { $('#$this->iId').val(''); } } ); // Useful for search forms: empty value in the "label", means no value, immediatly !
		$('#label_$this->iId').result( function(event, data, formatted) { OnAutoComplete('{$this->iId}', event, data, formatted); } );
		$('#$this->iId').bind('update', function() { oACWidget_{$this->iId}.Update(); } );
		if ($('#ac_dlg_{$this->iId}').length == 0)
		{
			$('body').append('<div id="ac_dlg_{$this->iId}"></div>');
		}
EOF
);
		}
		if ($bExtensions && MetaModel::IsHierarchicalClass($this->sTargetClass) !== false)
		{
			$sHTMLValue .= "<span class=\"field_input_btn\"><div class=\"mini_button\" id=\"mini_tree_{$this->iId}\" onClick=\"oACWidget_{$this->iId}.HKDisplay();\"><i class=\"fas fa-sitemap\"></i></div></span>";
			$oPage->add_ready_script(
<<<EOF
			if ($('#ac_tree_{$this->iId}').length == 0)
			{
				$('body').append('<div id="ac_tree_{$this->iId}"></div>');
			}		
EOF
);
		}
		if ($bCreate && $bExtensions)
		{
			$sCallbackName = (MetaModel::IsAbstract($this->sTargetClass)) ? 'SelectObjectClass' : 'CreateObject';

			$sHTMLValue .= "<span class=\"field_input_btn\"><div class=\"mini_button\" id=\"mini_add_{$this->iId}\" onClick=\"oACWidget_{$this->iId}.{$sCallbackName}();\"><i class=\"fas fa-plus\"></i></div></span>";
			$oPage->add_ready_script(
<<<EOF
		if ($('#ajax_{$this->iId}').length == 0)
		{
			$('body').append('<div id="ajax_{$this->iId}"></div>');
		}
EOF
);
		}
        $sHTMLValue .= "</div>";

		// Note: This test is no longer necessary as we changed the markup to extract validation decoration in the standard .field_input_xxx container
		//if (($sDisplayStyle == 'select') || ($sDisplayStyle == 'list'))
		//{
			$sHTMLValue .= "<span class=\"form_validation\" id=\"v_{$this->iId}\"></span><span class=\"field_status\" id=\"fstatus_{$this->iId}\"></span>";
		//}

		return $sHTMLValue;
	}

	public function GetSearchDialog(WebPage $oPage, $sTitle, $oCurrObject = null)
	{
		$sHTML = '<div class="wizContainer" style="vertical-align:top;"><div id="dc_'.$this->iId.'">';

		if ( ($oCurrObject != null) && ($this->sAttCode != ''))
		{
			$oAttDef = MetaModel::GetAttributeDef(get_class($oCurrObject), $this->sAttCode);
			/** @var \DBObject $oCurrObject */
			$aArgs = $oCurrObject->ToArgsForQuery();
			$aParams = array('query_params' => $aArgs);
			$oSet = $oAttDef->GetAllowedValuesAsObjectSet($aArgs);
			$oFilter = $oSet->GetFilter();
		}
		else
		{
			$aParams = array();
			$oFilter = new DBObjectSearch($this->sTargetClass);
		}
		$oFilter->SetModifierProperty('UserRightsGetSelectFilter', 'bSearchMode', $this->bSearchMode);
		$oBlock = new DisplayBlock($oFilter, 'search', false, $aParams);
		$sHTML .= $oBlock->GetDisplay($oPage, $this->iId,
            array(
                'menu' => false,
                'currentId' => $this->iId,
                'table_id' => "dr_{$this->iId}",
                'table_inner_id' => "{$this->iId}_results",
                'selection_mode' => true,
                'selection_type' => 'single',
                'cssCount' => '#count_'.$this->iId)
        );
		$sHTML .= "<form id=\"fr_{$this->iId}\" OnSubmit=\"return oACWidget_{$this->iId}.DoOk();\">\n";
		$sHTML .= "<div id=\"dr_{$this->iId}\" style=\"vertical-align:top;background: #fff;height:100%;overflow:auto;padding:0;border:0;\">\n";
		$sHTML .= "<div style=\"background: #fff; border:0; text-align:center; vertical-align:middle;\"><p>".Dict::S('UI:Message:EmptyList:UseSearchForm')."</p></div>\n";
		$sHTML .= "</div>\n";
		$sHTML .= "<input type=\"button\" id=\"btn_cancel_{$this->iId}\" value=\"".Dict::S('UI:Button:Cancel')."\" onClick=\"$('#ac_dlg_{$this->iId}').dialog('close');\">&nbsp;&nbsp;";
		$sHTML .= "<input type=\"button\" id=\"btn_ok_{$this->iId}\" value=\"".Dict::S('UI:Button:Ok')."\"  onClick=\"oACWidget_{$this->iId}.DoOk();\">";
		$sHTML .= "<input type=\"hidden\" id=\"count_{$this->iId}\" value=\"0\">";
		$sHTML .= "</form>\n";
		$sHTML .= '</div></div>';

		$sDialogTitle = addslashes($sTitle);
		$oPage->add_ready_script(
<<<EOF
		$('#ac_dlg_{$this->iId}').dialog({ width: $(window).width()*0.8, height: $(window).height()*0.8, autoOpen: false, modal: true, title: '$sDialogTitle', resizeStop: oACWidget_{$this->iId}.UpdateSizes, close: oACWidget_{$this->iId}.OnClose });
		$('#fs_{$this->iId}').bind('submit.uiAutocomplete', oACWidget_{$this->iId}.DoSearchObjects);
		$('#dc_{$this->iId}').resize(oACWidget_{$this->iId}.UpdateSizes);
EOF
);
		$oPage->add($sHTML);
	}

	/**
	 * Search for objects to be selected
	 *
	 * @param WebPage $oP The page used for the output (usually an AjaxWebPage)
	 * @param $sFilter
	 * @param string $sRemoteClass Name of the "remote" class to perform the search on, must be a derived class of m_sRemoteClass
	 * @param null $oObj
	 *
	 * @throws \OQLException
	 */
	public function SearchObjectsToSelect(WebPage $oP, $sFilter, $sRemoteClass = '', $oObj = null)
	{
		if (is_null($sFilter))
		{
			throw new Exception('Implementation: null value for allowed values definition');
		}

		$oFilter = DBObjectSearch::FromOQL($sFilter);
		if (strlen($sRemoteClass) > 0)
		{
			$oFilter->ChangeClass($sRemoteClass);
		}
		$oFilter->SetModifierProperty('UserRightsGetSelectFilter', 'bSearchMode', $this->bSearchMode);

		// Current extkey value, so we can display event if it is not available anymore (eg. archived).
		$iCurrentExtKeyId = (is_null($oObj)) ? 0 : $oObj->Get($this->sAttCode);

		$oBlock = new DisplayBlock($oFilter, 'list_search', false, array('query_params' => array('this' => $oObj, 'current_extkey_id' => $iCurrentExtKeyId)));
		$oBlock->Display($oP, $this->iId.'_results', array('this' => $oObj, 'cssCount'=> '#count_'.$this->iId, 'menu' => false, 'selection_mode' => true, 'selection_type' => 'single', 'table_id' => 'select_'.$this->sAttCode)); // Don't display the 'Actions' menu on the results
	}

    /**
     * Search for objects to be selected
     *
     * @param WebPage  $oP        The page used for the output (usually an AjaxWebPage)
     * @param string   $sFilter   The OQL expression used to define/limit limit the scope of possible values
     * @param DBObject $oObj      The current object for the OQL context
     * @param string   $sContains The text of the autocomplete to filter the results
     * @param string   $sOutputFormat
     * @param null     $sOperation for the values @see ValueSetObjects->LoadValues()
     *
     * @throws CoreException
     * @throws OQLException
     */
	public function AutoComplete(WebPage $oP, $sFilter, $oObj = null, $sContains, $sOutputFormat = self::ENUM_OUTPUT_FORMAT_CSV, $sOperation = null)
	{
		if (is_null($sFilter))
		{
			throw new Exception('Implementation: null value for allowed values definition');
		}

        // Current extkey value, so we can display event if it is not available anymore (eg. archived).
        $iCurrentExtKeyId = (is_null($oObj) || $this->sAttCode === '') ? 0 : $oObj->Get($this->sAttCode);

		$oValuesSet = new ValueSetObjects($sFilter, 'friendlyname'); // Bypass GetName() to avoid the encoding by htmlentities
		$iMax = 150;
		$oValuesSet->SetLimit($iMax);
		$oValuesSet->SetSort(false);
		$oValuesSet->SetModifierProperty('UserRightsGetSelectFilter', 'bSearchMode', $this->bSearchMode);
		$oValuesSet->SetLimit($iMax);
		$aValuesContains = $oValuesSet->GetValues(array('this' => $oObj, 'current_extkey_id' => $iCurrentExtKeyId), $sContains, 'contains');
		asort($aValuesContains);
		$aValues = array();
		foreach($aValuesContains as $sKey => $sFriendlyName)
		{
			if (!isset($aValues[$sKey]))
			{
				$aValues[$sKey] = $sFriendlyName;
			}
		}

		switch($sOutputFormat)
		{
			case static::ENUM_OUTPUT_FORMAT_JSON:

			    $aJsonMap = array();
			    foreach ($aValues as $sKey => $sLabel)
                {
                    $aJsonMap[] = array('value' => $sKey, 'label' => $sLabel);
                }

			    $oP->SetContentType('application/json');
                $oP->add(json_encode($aJsonMap));
				break;

			case static::ENUM_OUTPUT_FORMAT_CSV:
				foreach($aValues as $sKey => $sFriendlyName)
				{
					$oP->add(trim($sFriendlyName)."\t".$sKey."\n");
				}
				break;
			default:
				throw new Exception('Invalid output format, "'.$sOutputFormat.'" given.');
				break;
		}
	}

	/**
	 * Get the display name of the selected object, to fill back the autocomplete
	 */
	public function GetObjectName($iObjId)
	{
		$aModifierProps = array();
		$aModifierProps['UserRightsGetSelectFilter']['bSearchMode'] = $this->bSearchMode;

		$oObj = MetaModel::GetObject($this->sTargetClass, $iObjId, false, false, $aModifierProps);
		if ($oObj)
		{
			return $oObj->GetName();
		}
		else
		{
			return '';
		}
	}

	/**
	 * Get the form to select a leaf class from the $this->sTargetClass (that should be abstract)
	 * Note: Inspired from UILinksWidgetDirect::GetObjectCreationDialog()
	 *
	 * @param WebPage $oPage
	 *
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 */
	public function GetClassSelectionForm(WebPage $oPage)
	{
        // For security reasons: check that the "proposed" class is actually a subclass of the linked class
        // and that the current user is allowed to create objects of this class
        $aSubClasses = MetaModel::EnumChildClasses($this->sTargetClass);
        $aPossibleClasses = array();
        foreach($aSubClasses as $sCandidateClass)
        {
            if (!MetaModel::IsAbstract($sCandidateClass) && (UserRights::IsActionAllowed($sCandidateClass, UR_ACTION_MODIFY) == UR_ALLOWED_YES))
            {
                $aPossibleClasses[$sCandidateClass] = MetaModel::GetName($sCandidateClass);
            }
        }

        $sDialogTitle = '';
        $oPage->add('<div id="ac_create_'.$this->iId.'"><div class="wizContainer" style="vertical-align:top;"><div id="dcr_'.$this->iId.'">');
        $oPage->add('<form>');

		$sClassLabel = MetaModel::GetName($this->sTargetClass);
		$oPage->add('<p>'.Dict::Format('UI:SelectTheTypeOf_Class_ToCreate', $sClassLabel));
		$oPage->add('<nobr><select name="class">');
		asort($aPossibleClasses);
		foreach($aPossibleClasses as $sClassName => $sClassLabel)
		{
			$oPage->add("<option value=\"$sClassName\">$sClassLabel</option>");
		}
		$oPage->add('</select>');
		$oPage->add('&nbsp; <button type="submit" class="action" style="margin-top:15px;"><span>' . Dict::S('UI:Button:Ok') . '</span></button></nobr></p>');

        $oPage->add('</form>');
        $oPage->add('</div></div></div>');
        $oPage->add_ready_script("\$('#ac_create_$this->iId').dialog({ width: 'auto', height: 'auto', maxHeight: $(window).height() - 50, autoOpen: false, modal: true, title: '$sDialogTitle'});\n");
        $oPage->add_ready_script("$('#dcr_{$this->iId} form').removeAttr('onsubmit');");
        $oPage->add_ready_script("$('#dcr_{$this->iId} form').bind('submit.uilinksWizard', oACWidget_{$this->iId}.DoSelectObjectClass);");
	}

	/**
	 * Get the form to create a new object of the 'target' class
	 */
	public function GetObjectCreationForm(WebPage $oPage, $oCurrObject, $aPrefillFormParam)
	{
		// Set all the default values in an object and clone this "default" object
		$oNewObj = MetaModel::NewObject($this->sTargetClass);

		// 1st - set context values
		$oAppContext = new ApplicationContext();
		$oAppContext->InitObjectFromContext($oNewObj);
		$oNewObj->PrefillForm('creation_from_extkey', $aPrefillFormParam);
		// 2nd set the default values from the constraint on the external key... if any
		if ( ($oCurrObject != null) && ($this->sAttCode != ''))
		{
			$oAttDef = MetaModel::GetAttributeDef(get_class($oCurrObject), $this->sAttCode);
			$aParams = array('this' => $oCurrObject);
			$oSet = $oAttDef->GetAllowedValuesAsObjectSet($aParams);
			$aConsts = $oSet->ListConstantFields();
			$sClassAlias = $oSet->GetFilter()->GetClassAlias();
			if (isset($aConsts[$sClassAlias]))
			{
				foreach($aConsts[$sClassAlias] as $sAttCode => $value)
				{
					$oNewObj->Set($sAttCode, $value);
				}
			}
		}

		// 3rd - set values from the page argument 'default'
		$oNewObj->UpdateObjectFromArg('default');

		$sDialogTitle = '';
		$sClassLabel = MetaModel::GetName($this->sTargetClass);
		$sClassIcon = MetaModel::GetClassIcon($this->sTargetClass);
		$sObjClass = get_class($oNewObj);
		$sObjKey = $oNewObj->GetKey();
		$sHeaderTitle = Dict::Format('UI:CreationTitle_Class', $sClassLabel);
		$oPage->add(<<<HTML
<div id="ac_create_{$this->iId}">
	<!-- Beginning of object-details -->
	<div class="object-details" data-object-class="$sObjClass" data-object-id="$sObjKey" data-object-mode="create">
		<!-- Beginning of wizContainer -->
		<div class="wizContainer" style="vertical-align:top;">
			<div id="dcr_{$this->iId}">
				<h1>$sClassIcon&nbsp;$sHeaderTitle</h1>
HTML
		);
		$aFieldsFlags = array();
		$aFieldsComments = array();
		foreach(MetaModel::ListAttributeDefs($this->sTargetClass) as $sAttCode => $oAttDef)
		{
			if (($oAttDef instanceof AttributeBlob) || (false))
			{
				$aFieldsFlags[$sAttCode] = OPT_ATT_READONLY;
				$aFieldsComments[$sAttCode] = '&nbsp;<img src="../images/transp-lock.png" style="vertical-align:middle" title="'.htmlentities(Dict::S('UI:UploadNotSupportedInThisMode')).'"/>';
			}
		}
	 	cmdbAbstractObject::DisplayCreationForm($oPage, $this->sTargetClass, $oNewObj, array(), array('formPrefix' => $this->iId, 'noRelations' => true, 'fieldsFlags' => $aFieldsFlags, 'fieldsComments' => $aFieldsComments));
		$oPage->add(<<<HTML
			</div>
		</div><!-- End of wizContainer -->
	</div><!-- End of object-details -->
</div>
HTML
		);
//		$oPage->add_ready_script("\$('#ac_create_$this->iId').dialog({ width: $(window).width()*0.8, height: 'auto', autoOpen: false, modal: true, title: '$sDialogTitle'});\n");
		$oPage->add_ready_script("\$('#ac_create_$this->iId').dialog({ width: 'auto', height: 'auto', maxHeight: $(window).height() - 50, autoOpen: false, modal: true, title: '$sDialogTitle'});\n");
		$oPage->add_ready_script("$('#dcr_{$this->iId} form').removeAttr('onsubmit');");
		$oPage->add_ready_script("$('#dcr_{$this->iId} form').bind('submit.uilinksWizard', oACWidget_{$this->iId}.DoCreateObject);");
	}

	/**
	 * Display the hierarchy of the 'target' class
	 */
	public function DisplayHierarchy(WebPage $oPage, $sFilter, $currValue, $oObj)
	{
		$sDialogTitle = addslashes(Dict::Format('UI:HierarchyOf_Class', MetaModel::GetName($this->sTargetClass)));
		$oPage->add('<div id="dlg_tree_'.$this->iId.'"><div class="wizContainer" style="vertical-align:top;"><div style="overflow:auto;background:#fff;margin-bottom:5px;" id="tree_'.$this->iId.'">');
		$oPage->add('<table style="width:100%"><tr><td>');
		if (is_null($sFilter))
		{
			throw new Exception('Implementation: null value for allowed values definition');
		}

	    $oFilter = DBObjectSearch::FromOQL($sFilter);
		$oFilter->SetModifierProperty('UserRightsGetSelectFilter', 'bSearchMode', $this->bSearchMode);
		$oSet = new DBObjectSet($oFilter, array(), array('this' => $oObj, 'current_extkey_id' => $currValue));

		$oSet->SetShowObsoleteData(utils::ShowObsoleteData());

		$sHKAttCode = MetaModel::IsHierarchicalClass($this->sTargetClass);
		$bHasChildLeafs = $this->DumpTree($oPage, $oSet, $sHKAttCode, $currValue);

		$oPage->add('</td></tr></table>');
		$oPage->add('</div>');

		if ($bHasChildLeafs)
		{
			$oPage->add('<div class="treecontrol" id="treecontrolid"><a href="?#">'.Dict::S("UI:Treeview:CollapseAll").'</a> | <a href="?#">'.Dict::S("UI:Treeview:ExpandAll").'</a></div>');
		}

		$oPage->add("<input type=\"button\" id=\"btn_cancel_{$this->iId}\" value=\"".Dict::S('UI:Button:Cancel')."\" onClick=\"$('#dlg_tree_{$this->iId}').dialog('close');\">&nbsp;&nbsp;");
		$oPage->add("<input type=\"button\" id=\"btn_ok_{$this->iId}\" value=\"".Dict::S('UI:Button:Ok')."\"  onClick=\"oACWidget_{$this->iId}.DoHKOk();\">");

		$oPage->add('</div></div>');

		$oPage->add_ready_script("\$('#tree_$this->iId ul').treeview({ control: '#treecontrolid',	persist: 'false'});\n");
		$oPage->add_ready_script("\$('#dlg_tree_$this->iId').dialog({ width: 'auto', height: 'auto', autoOpen: true, modal: true, title: '$sDialogTitle', resizeStop: oACWidget_{$this->iId}.OnHKResize, close: oACWidget_{$this->iId}.OnHKClose });\n");
	}

	/**
	 * Get the form to create a new object of the 'target' class
	 */
	public function DoCreateObject($oPage)
	{
		try
		{
			$oObj = MetaModel::NewObject($this->sTargetClass);
			$aErrors = $oObj->UpdateObjectFromPostedForm($this->iId);
			if (count($aErrors) == 0)
			{
				$oObj->DBInsert();
				return array('name' => $oObj->GetName(), 'id' => $oObj->GetKey());
			}
			else
			{
				return array('error' => implode(' ', $aErrors), 'id' => 0);
			}
		}
		catch(Exception $e)
		{
			return array('error' => $e->getMessage(), 'id' => 0);
		}
	}

	/**
	 * @param WebPage $oP
	 * @param \DBObjectSet $oSet
	 * @param string $sParentAttCode
	 * @param string $currValue
	 *
	 * @return bool true if there are at least one child leaf, false if only roots nodes are present
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 */
	function DumpTree($oP, $oSet, $sParentAttCode, $currValue)
	{
		$aTree = array();
		$aNodes = array();
		while($oObj = $oSet->Fetch())
		{
			$iParentId = $oObj->Get($sParentAttCode);
			if (!isset($aTree[$iParentId]))
			{
				$aTree[$iParentId] = array();
			}
			$aTree[$iParentId][$oObj->GetKey()] = $oObj->GetName();
			$aNodes[$oObj->GetKey()] = $oObj;
		}

		$aParents = array_keys($aTree);
		$aRoots = array();
		foreach($aParents as $id)
		{
			if (!array_key_exists($id, $aNodes))
			{
				$aRoots[] = $id;
			}
		}
		foreach($aRoots as $iRootId)
		{
			$this->DumpNodes($oP, $iRootId, $aTree, $aNodes, $currValue);
		}

		$bHasOnlyRootNodes = (count($aTree) === 1);
		return !$bHasOnlyRootNodes;
	}

	function DumpNodes($oP, $iRootId, $aTree, $aNodes, $currValue)
	{
		$bSelect = true;
		$bMultiple = false;
		$sSelect = '';
		if (array_key_exists($iRootId, $aTree))
		{
			$aSortedRoots = $aTree[$iRootId];
			asort($aSortedRoots);
			$oP->add("<ul>\n");
			$fUniqueId = microtime(true);
			foreach($aSortedRoots as $id => $sName)
			{
				if ($bSelect)
				{
					$sChecked = ($aNodes[$id]->GetKey() == $currValue) ? 'checked' : '';
					if ($bMultiple)
					{
						$sSelect = '<input id="input_'.$fUniqueId.'_'.$aNodes[$id]->GetKey().'" type="checkbox" value="'.$aNodes[$id]->GetKey().'" name="selectObject[]" '.$sChecked.'>&nbsp;';
					}
					else
					{
						$sSelect = '<input id="input_'.$fUniqueId.'_'.$aNodes[$id]->GetKey().'" type="radio" value="'.$aNodes[$id]->GetKey().'" name="selectObject" '.$sChecked.'>&nbsp;';
					}
				}
				$oP->add('<li class="closed">'.$sSelect.'<label for="input_'.$fUniqueId.'_'.$aNodes[$id]->GetKey().'">'.$aNodes[$id]->GetName().'</label>');
				$this->DumpNodes($oP, $id, $aTree, $aNodes, $currValue);
				$oP->add("</li>\n");
			}
			$oP->add("</ul>\n");
		}
	}

}
