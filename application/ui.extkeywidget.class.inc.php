<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Application\Helper\FormHelper;
use Combodo\iTop\Application\UI\Base\Component\Form\FormUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlockUIBlockFactory;
use Combodo\iTop\Application\WebPage\WebPage;
use Combodo\iTop\Core\MetaModel\FriendlyNameType;

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
	protected $sFilter;
	protected $sAttCode;
	protected $bSearchMode;

	//public function __construct($sAttCode, $sClass, $sTitle, $oAllowedValues, $value, $iInputId, $bMandatory, $sNameSuffix = '', $sFieldPrefix = '', $sFormPrefix = '')

	/**
	 * @param WebPage $oPage
	 * @param string $sAttCode
	 * @param string $sClass
	 * @param string $sTitle
	 * @param object $oAllowedValues
	 * @param mixed $value
	 * @param int $iInputId
	 * @param boolean $bMandatory
	 * @param string $sFieldName
	 * @param string $sFormPrefix
	 * @param array $aArgs
	 * @param boolean $bSearchMode
	 * @param string $sInputType type of field rendering, contains one of the \cmdbAbstractObject::ENUM_INPUT_TYPE_* constants
	 *
	 * @return string
	 * @throws \Exception
	 *
	 * @since 3.0.0 N°3750 new $sInputType parameter
	 * @since 2.7.7 3.0.1 3.1.0 N°3129 Add default value for $aArgs for PHP 8.0 compat
	 */
	public static function DisplayFromAttCode(
		$oPage, $sAttCode, $sClass, $sTitle, $oAllowedValues, $value, $iInputId, $bMandatory, $sFieldName = '', $sFormPrefix = '',
		$aArgs = [], $bSearchMode = false, &$sInputType = ''
	)
	{
		$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
		$sTargetClass = $oAttDef->GetTargetClass();
		$iMaxComboLength = $oAttDef->GetMaximumComboLength();
		$bAllowTargetCreation = $oAttDef->AllowTargetCreation();
		if (!$bSearchMode) {
			$sDisplayStyle = $oAttDef->GetDisplayStyle();
		} else {
			$sDisplayStyle = 'select'; // In search mode, always use a drop-down list
		}
		$oWidget = new UIExtKeyWidget($sTargetClass, $iInputId, $sAttCode, $bSearchMode);
		if (!$bSearchMode) {
			switch ($sDisplayStyle)
			{
				case 'radio':
				case 'radio_horizontal':
				case 'radio_vertical':
					$sInputType = CmdbAbstractObject::ENUM_INPUT_TYPE_RADIO;

					return $oWidget->DisplayRadio($oPage, $iMaxComboLength, $bAllowTargetCreation, $oAllowedValues, $value, $sFieldName, $sDisplayStyle);

				case 'select':
				case 'list':
				default:
					return $oWidget->DisplaySelect($oPage, $iMaxComboLength, $bAllowTargetCreation, $sTitle, $oAllowedValues, $value,
						$bMandatory, $sFieldName, $sFormPrefix, $aArgs, $sInputType);
			}
		} else {
			return $oWidget->Display($oPage, $iMaxComboLength, $bAllowTargetCreation, $sTitle, $oAllowedValues, $value, $iInputId,
				$bMandatory, $sFieldName, $sFormPrefix, $aArgs, null, $sDisplayStyle, true, $sInputType);
		}
	}

	public function __construct($sTargetClass, $iInputId, $sAttCode = '', $bSearchMode = false, $sFilter = null)
	{
		$this->sTargetClass = $sTargetClass;
		$this->sFilter = $sFilter;
		$this->iId = $iInputId;
		$this->sAttCode = $sAttCode;
		$this->bSearchMode = $bSearchMode;
	}

	/**
	 * @param WebPage $oPage
	 * @param int $iMaxComboLength
	 * @param bool $bAllowTargetCreation
	 * @param string $sTitle
	 * @param \DBObjectset $oAllowedValues
	 * @param mixed $value
	 * @param bool $bMandatory
	 * @param string $sFieldName
	 * @param string $sFormPrefix
	 * @param array $aArgs Extra context arguments
	 * @param string $sInputType type of field rendering, contains one of the \cmdbAbstractObject::ENUM_INPUT_TYPE_* constants
	 *
	 * @return string the HTML fragment corresponding to the ext key editing widget
	 * @throws \ArchivedObjectException
	 * @throws \ConfigException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 *
	 * @since 3.0.0 N°2508 - Include Obsolescence icon within list and autocomplete
	 * @since 3.0.0 N°3750 new $sInputType parameter
	 */
	public function DisplaySelect(WebPage $oPage, $iMaxComboLength, $bAllowTargetCreation, $sTitle, DBObjectset $oAllowedValues, $value, $bMandatory, $sFieldName, $sFormPrefix = '', $aArgs = array(), &$sInputType = '')
	{
		$sTitle = addslashes($sTitle);
		$oPage->LinkScriptFromAppRoot('js/extkeywidget.js');
		$oPage->LinkScriptFromAppRoot('js/forms-json-utils.js');

		$bCreate = (!$this->bSearchMode) && (UserRights::IsActionAllowed($this->sTargetClass, UR_ACTION_MODIFY) && $bAllowTargetCreation);
		$bExtensions = true;
		$sMessage = Dict::S('UI:Message:EmptyList:UseSearchForm');
		$sAttrFieldPrefix = ($this->bSearchMode) ? '' : 'attr_';

		$sFilter = addslashes($oAllowedValues->GetFilter()->ToOQL());
		if ($this->bSearchMode) {
			$sWizHelper = 'null';
			$sWizHelperJSON = "''";
			$sJSSearchMode = 'true';
		} else {
			if (isset($aArgs['wizHelper'])) {
				$sWizHelper = $aArgs['wizHelper'];
			} else {
				$sWizHelper = 'oWizardHelper'.$sFormPrefix;
			}
			$sWizHelperJSON = $sWizHelper.'.UpdateWizardToJSON()';
			$sJSSearchMode = 'false';
		}
		if (is_null($oAllowedValues)) {
			throw new Exception('Implementation: null value for allowed values definition');
		}
		$oAllowedValues->SetShowObsoleteData(utils::ShowObsoleteData());
		// Don't automatically launch the search if the table is huge
		$bDoSearch = !utils::IsHighCardinality($this->sTargetClass);
		$sJSDoSearch = $bDoSearch ? 'true' : 'false';

		$bIsAutocomplete = $oAllowedValues->CountExceeds($iMaxComboLength);
		$sWrapperCssClass = $bIsAutocomplete ? 'ibo-input-select-autocomplete-wrapper' : 'ibo-input-select-wrapper';
		$sHTMLValue = "<div class=\"field_input_zone field_input_extkey ibo-input-wrapper ibo-input-select-wrapper--with-buttons $sWrapperCssClass\" data-attcode=\"".$this->sAttCode."\"  data-validation=\"untouched\"  data-accessibility-selectize-label=\"$sTitle\">";
		
		// We just need to compare the number of entries with MaxComboLength, so no need to get the real count.
		if (!$bIsAutocomplete) {
			// Discrete list of values, use a SELECT or RADIO buttons depending on the config
			$sHelpText = ''; //$this->oAttDef->GetHelpOnEdition();
			//$sHTMLValue .= "<div class=\"field_select_wrapper\">\n";
			$aOptions = [];

			$aOption = [];
			$aOption['value'] = "";
			$aOption['label'] = Dict::S('UI:SelectOne');
			array_push($aOptions, $aOption);

			$oAllowedValues->Rewind();
			$sClassAllowed = $oAllowedValues->GetClass();
			$bAddingValue = false;

			// N°4792 - load only the required fields
			$aFieldsToLoad = [];

			$aComplementAttributeSpec = MetaModel::GetNameSpec($oAllowedValues->GetClass(), FriendlyNameType::COMPLEMENTARY);
			$sFormatAdditionalField = $aComplementAttributeSpec[0];
			$aAdditionalField = $aComplementAttributeSpec[1];

			if (count($aAdditionalField) > 0) {
				$bAddingValue = true;
				$aFieldsToLoad[$sClassAllowed] = $aAdditionalField;
			}
			$sObjectImageAttCode = MetaModel::GetImageAttributeCode($sClassAllowed);
			if (!empty($sObjectImageAttCode)) {
				$aFieldsToLoad[$sClassAllowed][] = $sObjectImageAttCode;
			}
			$aFieldsToLoad[$sClassAllowed][] = 'friendlyname';
			$oAllowedValues->OptimizeColumnLoad($aFieldsToLoad);
			$bInitValue = false;
			while ($oObj = $oAllowedValues->Fetch()) {
				$aOption = [];
				$aOption['value'] = $oObj->GetKey();
				$aOption['label'] = $oObj->GetName();
				$aOption['search_label'] = utils::HtmlEntityDecode($oObj->GetName());

				if (($oAllowedValues->Count() == 1) && ($bMandatory == 'true')) {
					// When there is only once choice, select it by default
					if ($value != $oObj->GetKey()) {
						$value = $oObj->GetKey();
						$bInitValue = true;
					}
				}
				if ($oObj->IsObsolete()) {
					$aOption['obsolescence_flag'] = "1";
				}
				if ($bAddingValue) {
					$aArguments = [];
					foreach ($aAdditionalField as $sAdditionalField) {
						array_push($aArguments, $oObj->Get($sAdditionalField));
					}
					$aOption['additional_field'] = utils::HtmlEntities(vsprintf($sFormatAdditionalField, $aArguments));
				}
				if (!empty($sObjectImageAttCode)) {
					// Try to retrieve image for contact
					/** @var \ormDocument $oImage */
					$oImage = $oObj->Get($sObjectImageAttCode);
					if (!$oImage->IsEmpty()) {
						$aOption['picture_url'] = $oImage->GetDisplayURL($sClassAllowed, $oObj->GetKey(), $sObjectImageAttCode);
						$aOption['initials'] = '';
					} else {
						$aOption['initials'] = utils::FormatInitialsForMedallion(utils::ToAcronym($oObj->Get('friendlyname')));
					}
				}
				array_push($aOptions, $aOption);
			}
			$sInputType = CmdbAbstractObject::ENUM_INPUT_TYPE_DROPDOWN_DECORATED;
			$sHTMLValue .= "<select class=\"ibo-input-select-placeholder\" title=\"$sHelpText\" name=\"{$sAttrFieldPrefix}{$sFieldName}\" id=\"$this->iId\"  tabindex=\"0\"></select>";
			$sJsonOptions = str_replace("'", "\'", str_replace('\\', '\\\\', json_encode($aOptions)));
			$oPage->add_ready_script(
				<<<EOF
		oACWidget_{$this->iId} = new ExtKeyWidget('{$this->iId}', '{$this->sTargetClass}', '$sFilter', '$sTitle', true, $sWizHelper, '{$this->sAttCode}', $sJSSearchMode, $sJSDoSearch);
		oACWidget_{$this->iId}.emptyHtml = "<div style=\"background: #fff; border:0; text-align:center; vertical-align:middle;\"><p>$sMessage</p></div>";
		oACWidget_{$this->iId}.AddSelectize('$sJsonOptions','$value');
		$('#$this->iId').on('update', function() { oACWidget_{$this->iId}.Update(); } );
		$('#$this->iId').on('change', function() { $(this).trigger('extkeychange'); } );
EOF
			);
			if ($bInitValue) {
				$oPage->add_ready_script("$('#$this->iId').one('validate', function() { $(this).trigger('change'); } );");
			}
			$sHTMLValue .= "<div class=\"ibo-input-select--action-buttons\">";
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
			} else {
				$sDisplayValue = $this->GetObjectName($value);
			}
			$iMinChars = isset($aArgs['iMinChars']) ? $aArgs['iMinChars'] : 2; //@@@ $this->oAttDef->GetMinAutoCompleteChars();

			// the input for the auto-complete
			$sInputType = CmdbAbstractObject::ENUM_INPUT_TYPE_AUTOCOMPLETE;
			$sHTMLValue .= "<input class=\"field_autocomplete ibo-input ibo-input-select ibo-input-select-autocomplete\" type=\"text\"  id=\"label_$this->iId\" value=\"$sDisplayValue\" placeholder='...'/>";

			// another hidden input to store & pass the object's Id
			$sHTMLValue .= "<input type=\"hidden\" id=\"$this->iId\" name=\"{$sAttrFieldPrefix}{$sFieldName}\" value=\"".utils::HtmlEntities($value)."\" />\n";

			$JSSearchMode = $this->bSearchMode ? 'true' : 'false';
			// Scripts to start the autocomplete and bind some events to it
			$oPage->add_ready_script(
				<<<EOF
		oACWidget_{$this->iId} = new ExtKeyWidget('{$this->iId}', '{$this->sTargetClass}', '$sFilter', '$sTitle', false, $sWizHelper, '{$this->sAttCode}', $sJSSearchMode, $sJSDoSearch);
		oACWidget_{$this->iId}.emptyHtml = "<div style=\"background: #fff; border:0; text-align:center; vertical-align:middle;\"><p>$sMessage</p></div>";
		oACWidget_{$this->iId}.AddAutocomplete($iMinChars, $sWizHelperJSON);
		if ($('#ac_dlg_{$this->iId}').length == 0)
		{
			$('body').append('<div id="ac_dlg_{$this->iId}"></div>');
		}
EOF
			);
			$sHTMLValue .= "<div class=\"ibo-input-select--action-buttons\">";
			$sHTMLValue .= "	<a href=\"#\" class=\"ibo-input-select--action-button ibo-input-select--action-button--clear ibo-is-hidden\"  id=\"mini_clear_{$this->iId}\" onClick=\"oACWidget_{$this->iId}.Clear();\" data-tooltip-content='".Dict::S('UI:Button:Clear')."'><i class=\"fas fa-times\"></i></a>";
		}
		if ($bCreate && $bExtensions) {
			$sCallbackName = (MetaModel::IsAbstract($this->sTargetClass)) ? 'SelectObjectClass' : 'CreateObject';

			$sHTMLValue .= "<a href=\"#\" class=\"ibo-input-select--action-button ibo-input-select--action-button--create\" id=\"mini_add_{$this->iId}\" onClick=\"oACWidget_{$this->iId}.{$sCallbackName}();\" data-tooltip-content='".Dict::S('UI:Button:Create')."'><i class=\"fas fa-plus\"></i></a>";
			$oPage->add_ready_script(
				<<<JS
		if ($('#ajax_{$this->iId}').length == 0)
		{
			$('body').append('<div id="ajax_{$this->iId}"></div>');
		}
JS
			);
		}
		if ($bExtensions && MetaModel::IsHierarchicalClass($this->sTargetClass) !== false) {
			$sHTMLValue .= "<a href=\"#\" class=\"ibo-input-select--action-button ibo-input-select--action-button--hierarchy\" id=\"mini_tree_{$this->iId}\" onClick=\"oACWidget_{$this->iId}.HKDisplay();\" data-tooltip-content='".Dict::S('UI:Button:SearchInHierarchy')."'><i class=\"fas fa-sitemap\"></i></a>";
			$oPage->add_ready_script(
				<<<JS
			if ($('#ac_tree_{$this->iId}').length == 0)
			{
				$('body').append('<div id="ac_tree_{$this->iId}"></div>');
			}		
JS
			);
		}
		if ($oAllowedValues->CountExceeds($iMaxComboLength)) {
			$sHTMLValue .= "	<a href=\"#\" class=\"ibo-input-select--action-button ibo-input-select--action-button--search\"  id=\"mini_search_{$this->iId}\" onClick=\"oACWidget_{$this->iId}.Search();\" data-tooltip-content='".Dict::S('UI:Button:Search')."'><i class=\"fas fa-search\"></i></a>";
		}
		$sHTMLValue .= "</div>";
		$sHTMLValue .= "</div>";
		$sHTMLValue .= "<span class=\"form_validation ibo-field-validation\" id=\"v_{$this->iId}\"></span><span class=\"field_status\" id=\"fstatus_{$this->iId}\"></span>";

		return $sHTMLValue;
	}

	/**
	 * @since 3.0.0 N°2508 - Include Obsolescence icon within list and autocomplete
	 * Get the HTML fragment corresponding to the ext key editing widget
	 * @param WebPage $oP The web page used for all the output
	 * @param array $aArgs Extra context arguments
	 * @return string The HTML fragment to be inserted into the page
	 */
	public function DisplayRadio(WebPage $oPage, $iMaxComboLength, $bAllowTargetCreation, DBObjectset $oAllowedValues, $value, $sFieldName, $sDisplayStyle)
	{
		$oPage->LinkScriptFromAppRoot('js/forms-json-utils.js');

		$bCreate = (!$this->bSearchMode) && (UserRights::IsActionAllowed($this->sTargetClass, UR_ACTION_BULK_MODIFY) && $bAllowTargetCreation);
		$bExtensions = true;
		$sAttrFieldPrefix = ($this->bSearchMode) ? '' : 'attr_';

		$sHTMLValue = "<div class=\"field_input_zone field_input_extkey\">";

		if (is_null($oAllowedValues))
		{
			throw new Exception('Implementation: null value for allowed values definition');
		}
		$oAllowedValues->SetShowObsoleteData(utils::ShowObsoleteData());

		// We just need to compare the number of entries with MaxComboLength, so no need to get the real count.
		if (!$oAllowedValues->CountExceeds($iMaxComboLength))
		{
			// Discrete list of values, use a SELECT or RADIO buttons depending on the config
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
		}
		else
		{
			$sHTMLValue .= "unable to display. Too much values";
		}
		$sHTMLValue .= '<div class="ibo-input-select--action-buttons">';
		if ($bExtensions && MetaModel::IsHierarchicalClass($this->sTargetClass) !== false)
		{
			$sHTMLValue .= "<span class=\"field_input_btn\"><div class=\"mini_button\" id=\"mini_tree_{$this->iId}\" onClick=\"oACWidget_{$this->iId}.HKDisplay();\"><i class=\"fas fa-sitemap\"></i></div></span>";
			$oPage->add_ready_script(
				<<<JS
			if ($('#ac_tree_{$this->iId}').length == 0)
			{
				$('body').append('<div id="ac_tree_{$this->iId}"></div>');
			}		
JS
			);
		}
		if ($bCreate && $bExtensions)
		{
			$sCallbackName = (MetaModel::IsAbstract($this->sTargetClass)) ? 'SelectObjectClass' : 'CreateObject';

			$sHTMLValue .= "<span class=\"field_input_btn\"><div class=\"mini_button\" id=\"mini_add_{$this->iId}\" onClick=\"oACWidget_{$this->iId}.{$sCallbackName}();\"><i class=\"fas fa-plus\"></i></div></span>";
			$oPage->add_ready_script(
				<<<JS
		if ($('#ajax_{$this->iId}').length == 0)
		{
			$('body').append('<div id="ajax_{$this->iId}"></div>');
		}
JS
			);
		}
		$sHTMLValue .= "</div>";
		$sHTMLValue .= "</div>";

		// Note: This test is no longer necessary as we changed the markup to extract validation decoration in the standard .field_input_xxx container
		//if (($sDisplayStyle == 'select') || ($sDisplayStyle == 'list'))
		//{
		$sHTMLValue .= "<span class=\"form_validation ibo-field-validation\" id=\"v_{$this->iId}\"></span><span class=\"field_status\" id=\"fstatus_{$this->iId}\"></span>";
		//}

		return $sHTMLValue;
	}

	/**
	 * Get the HTML fragment corresponding to the ext key editing widget
	 *
	 * @param WebPage $oPage
	 * @param int $iMaxComboLength
	 * @param boolean $bAllowTargetCreation
	 * @param string $sTitle
	 * @param \DBObjectset $oAllowedValues
	 * @param mixed $value
	 * @param int $iInputId
	 * @param boolean $bMandatory
	 * @param strin $sFieldName
	 * @param string $sFormPrefix
	 * @param array $aArgs Extra context arguments
	 * @param null $bSearchMode
	 * @param string $sDisplayStyle
	 * @param boolean $bSearchMultiple
	 * @param string $sInputType type of field rendering, contains one of the \cmdbAbstractObject::ENUM_INPUT_TYPE_* constants
	 *
	 * @return string The HTML fragment to be inserted into the page
	 * @throws \ConfigException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 *
	 * @since 3.0.0 N°3750 new $sInputType parameter
	 */
	public function Display(WebPage $oPage, $iMaxComboLength, $bAllowTargetCreation, $sTitle, DBObjectset $oAllowedValues, $value, $iInputId, $bMandatory, $sFieldName, $sFormPrefix = '', $aArgs = array(), $bSearchMode = null, $sDisplayStyle = 'select', $bSearchMultiple = true, &$sInputType = '')
	{
		if (!is_null($bSearchMode)) {
			$this->bSearchMode = $bSearchMode;
		}
		$sTitle = addslashes($sTitle);
		$oPage->LinkScriptFromAppRoot('js/extkeywidget.js');
		$oPage->LinkScriptFromAppRoot('js/forms-json-utils.js');

		$bCreate = (!$this->bSearchMode) && (UserRights::IsActionAllowed($this->sTargetClass, UR_ACTION_BULK_MODIFY) && $bAllowTargetCreation);
		$bExtensions = true;
		$sMessage = Dict::S('UI:Message:EmptyList:UseSearchForm');
		$sAttrFieldPrefix = ($this->bSearchMode) ? '' : 'attr_';

		$sHTMLValue = "<div class=\"field_input_zone field_input_extkey\">";
		$sFilter = addslashes($oAllowedValues->GetFilter()->ToOQL());
		if ($this->bSearchMode) {
			$sWizHelper = 'null';
			$sWizHelperJSON = "''";
			$sJSSearchMode = 'true';
		} else {
			if (isset($aArgs['wizHelper'])) {
				$sWizHelper = $aArgs['wizHelper'];
			} else {
				$sWizHelper = 'oWizardHelper'.$sFormPrefix;
			}
			$sWizHelperJSON = $sWizHelper.'.UpdateWizardToJSON()';
			$sJSSearchMode = 'false';
		}
		if (is_null($oAllowedValues)) {
			throw new Exception('Implementation: null value for allowed values definition');
		}
		$oAllowedValues->SetShowObsoleteData(utils::ShowObsoleteData());
		// Don't automatically launch the search if the table is huge
		$bDoSearch = !utils::IsHighCardinality($this->sTargetClass);
		$sJSDoSearch = $bDoSearch ? 'true' : 'false';

		// We just need to compare the number of entries with MaxComboLength, so no need to get the real count.
		if (!$oAllowedValues->CountExceeds($iMaxComboLength)) {
			// Discrete list of values, use a SELECT or RADIO buttons depending on the config
			switch ($sDisplayStyle) {
				case 'radio':
				case 'radio_horizontal':
				case 'radio_vertical':
					$sInputType = CmdbAbstractObject::ENUM_INPUT_TYPE_RADIO;
					$sValidationField = null;

					$bVertical = ($sDisplayStyle != 'radio_horizontal');
					$bExtensions = false;
					$oAllowedValues->Rewind();
					$aAllowedValues = array();
					while ($oObj = $oAllowedValues->Fetch()) {
						$aAllowedValues[$oObj->GetKey()] = $oObj->GetName();
					}
					$sHTMLValue .= $oPage->GetRadioButtons($aAllowedValues, $value, $this->iId, "{$sAttrFieldPrefix}{$sFieldName}", false /*  $bMandatory will be placed manually */, $bVertical, $sValidationField);
					$aEventsList[] = 'change';
					break;

				case 'select':
				case 'list':
				default:
					$sHelpText = '';
					$sHTMLValue .= "<div class=\"field_select_wrapper\">\n";

					if ($this->bSearchMode) {
						if ($bSearchMultiple) {
							$sHTMLValue .= "<select class=\"multiselect\" multiple title=\"$sHelpText\" name=\"{$sAttrFieldPrefix}{$sFieldName}[]\" id=\"$this->iId\">\n";
						} else {
							$sHTMLValue .= "<select title=\"$sHelpText\" name=\"{$sAttrFieldPrefix}{$sFieldName}\" id=\"$this->iId\">\n";
							$sDisplayValue = isset($aArgs['sDefaultValue']) ? $aArgs['sDefaultValue'] : Dict::S('UI:SearchValue:Any');
							$sHTMLValue .= "<option value=\"\">$sDisplayValue</option>\n";
						}
					} else {
						$sHTMLValue .= "<select class=\"ibo-input-select-placeholder\" title=\"$sHelpText\" name=\"{$sAttrFieldPrefix}{$sFieldName}\" id=\"$this->iId\">\n";
						$sHTMLValue .= "<option value=\"\">".Dict::S('UI:SelectOne')."</option>\n";
					}

					$oAllowedValues->Rewind();
					while ($oObj = $oAllowedValues->Fetch()) {
						$key = $oObj->GetKey();
						$display_value = $oObj->GetName();

						if (($oAllowedValues->Count() == 1) && ($bMandatory == 'true')) {
							// When there is only once choice, select it by default
							$sSelected = 'selected';
							if ($value != $key) {
								$oPage->add_ready_script(
									<<<EOF
$('#$this->iId').attr('data-validate','dependencies');
EOF
								);
							}
						} else {
							$sSelected = (is_array($value) && in_array($key, $value)) || ($value == $key) ? 'selected' : '';
						}
						$sHTMLValue .= "<option value=\"$key\" $sSelected>$display_value</option>\n";
					}
				$sHTMLValue .= "</select>\n";
				$sHTMLValue .= "</div>\n";

				$sInputType = CmdbAbstractObject::ENUM_INPUT_TYPE_DROPDOWN_RAW;
				if (($this->bSearchMode) && $bSearchMultiple) {
					$sInputType = CmdbAbstractObject::ENUM_INPUT_TYPE_DROPDOWN_MULTIPLE_CHOICES;
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
		$('#$this->iId').on('update', function() { oACWidget_{$this->iId}.Update(); } );
		$('#$this->iId').on('change', function() { $(this).trigger('extkeychange') } );

EOF
					);
			}
		} else {
			// Too many choices, use an autocomplete
			$sInputType = CmdbAbstractObject::ENUM_INPUT_TYPE_AUTOCOMPLETE;
			// Check that the given value is allowed
			$oSearch = $oAllowedValues->GetFilter();
			$oSearch->AddCondition('id', $value);
			$oSet = new DBObjectSet($oSearch);
			if ($oSet->Count() == 0) {
				$value = null;
			}

			if (is_null($value) || ($value == 0)) // Null values are displayed as ''
			{
				$sDisplayValue = isset($aArgs['sDefaultValue']) ? $aArgs['sDefaultValue'] : '';
			} else {
				$sDisplayValue = $this->GetObjectName($value);
			}
			$iMinChars = isset($aArgs['iMinChars']) ? $aArgs['iMinChars'] : 2; //@@@ $this->oAttDef->GetMinAutoCompleteChars();

			// the input for the auto-complete
			$sHTMLValue .= "<input class=\"field_autocomplete ibo-input-select\" type=\"text\"  id=\"label_$this->iId\" value=\"$sDisplayValue\"/>";
			$sHTMLValue .= "<div class=\"ibo-input-select--action-buttons\"><span class=\"field_input_btn\"><div class=\"mini_button ibo-input-select--action-button\"  id=\"mini_search_{$this->iId}\" onClick=\"oACWidget_{$this->iId}.Search();\"><i class=\"fas fa-search\"></i></div></span></div>";

			// another hidden input to store & pass the object's Id
			$sHTMLValue .= "<input type=\"hidden\" id=\"$this->iId\" name=\"{$sAttrFieldPrefix}{$sFieldName}\" value=\"".utils::EscapeHtml($value)."\" />\n";

			$JSSearchMode = $this->bSearchMode ? 'true' : 'false';
			// Scripts to start the autocomplete and bind some events to it
			$oPage->add_ready_script(
				<<<EOF
		oACWidget_{$this->iId} = new ExtKeyWidget('{$this->iId}', '{$this->sTargetClass}', '$sFilter', '$sTitle', false, $sWizHelper, '{$this->sAttCode}', $sJSSearchMode, $sJSDoSearch);
		oACWidget_{$this->iId}.emptyHtml = "<div style=\"background: #fff; border:0; text-align:center; vertical-align:middle;\"><p>$sMessage</p></div>";
		oACWidget_{$this->iId}.AddAutocomplete($iMinChars, $sWizHelperJSON);
		if ($('#ac_dlg_{$this->iId}').length == 0)
		{
			$('body').append('<div id="ac_dlg_{$this->iId}"></div>');
		}
EOF
			);
		}
		if ($bExtensions && MetaModel::IsHierarchicalClass($this->sTargetClass) !== false) {
			$sHTMLValue .= "<span class=\"field_input_btn\"><div class=\"ibo-input-select--action-button\" id=\"mini_tree_{$this->iId}\" onClick=\"oACWidget_{$this->iId}.HKDisplay();\"><i class=\"fas fa-sitemap\"></i></div></span>";
			$oPage->add_ready_script(
				<<<JS
			if ($('#ac_tree_{$this->iId}').length == 0)
			{
				$('body').append('<div id="ac_tree_{$this->iId}"></div>');
			}		
JS
			);
		}
		if ($bCreate && $bExtensions) {
			$sCallbackName = (MetaModel::IsAbstract($this->sTargetClass)) ? 'SelectObjectClass' : 'CreateObject';

			$sHTMLValue .= "<span class=\"field_input_btn\"><div class=\"ibo-input-select--action-button\" id=\"mini_add_{$this->iId}\" onClick=\"oACWidget_{$this->iId}.{$sCallbackName}();\"><i class=\"fas fa-plus\"></i></div></span>";
			$oPage->add_ready_script(
				<<<JS
		if ($('#ajax_{$this->iId}').length == 0)
		{
			$('body').append('<div id="ajax_{$this->iId}"></div>');
		}
JS
			);
		}
		$sHTMLValue .= "</div>";
		$sHTMLValue .= "<span class=\"form_validation ibo-field-validation\" id=\"v_{$this->iId}\"></span><span class=\"field_status\" id=\"fstatus_{$this->iId}\"></span>";

		return $sHTMLValue;
	}

	public function GetSearchDialog(WebPage $oPage, $sTitle, $oCurrObject = null)
	{
		$oPage->add('<div class="wizContainer" style="vertical-align:top;"><div id="dc_'.$this->iId.'">');

		if (($oCurrObject != null) && ($this->sAttCode != '')) {
			$oAttDef = MetaModel::GetAttributeDef(get_class($oCurrObject), $this->sAttCode);
			/** @var \DBObject $oCurrObject */
			$aArgs = $oCurrObject->ToArgsForQuery();
			$aParams = array('query_params' => $aArgs);
			$oSet = $oAttDef->GetAllowedValuesAsObjectSet($aArgs);
			$oFilter = $oSet->GetFilter();
		} else if (!empty($this->sFilter)) {
			$aParams = array();
			$oFilter = DBObjectSearch::FromOQL($this->sFilter);
		} else {
			$aParams = array();
			$oFilter = new DBObjectSearch($this->sTargetClass);
		}
		$oFilter->SetModifierProperty('UserRightsGetSelectFilter', 'bSearchMode', $this->bSearchMode);
		$oBlock = new DisplayBlock($oFilter, 'search', false, $aParams);
		$oPage->AddUiBlock($oBlock->GetDisplay($oPage, 'dtc_'.$this->iId,
			array(
				'menu'           => false,
				'currentId'      => $this->iId,
				'table_id'       => "dr_{$this->iId}",
				'table_inner_id' => "{$this->iId}_results",
				'selection_mode' => true,
				'selection_type' => 'single',
				'cssCount'       => '#count_'.$this->iId.'_results',
			)
		));
		$sCancel = Dict::S('UI:Button:Cancel');
		$sOK = Dict::S('UI:Button:Ok');
		$sEmptyList = Dict::S('UI:Message:EmptyList:UseSearchForm');
		$oPage->add(<<<HTML
<form id="fr_{$this->iId}" OnSubmit="return oACWidget_{$this->iId}.DoOk();">
		<div id="dr_{$this->iId}">
		<div><p>{$sEmptyList}</p></div>
		</div>
		<input type="hidden" id="count_{$this->iId}_results" value="0">
		</form>
		</div></div>
HTML
		);

		$sDialogTitleSanitized = addslashes(utils::HtmlToText($sTitle));
		$oPage->add_ready_script(<<<JS
		$('#ac_dlg_{$this->iId}').dialog({ 
				width: $(window).width()*0.8, 
				height: $(window).height()*0.8, 
				autoOpen: false, 
				modal: true, 
				title: '$sDialogTitleSanitized', 
				resizeStop: oACWidget_{$this->iId}.UpdateSizes, 
				close: oACWidget_{$this->iId}.OnClose,
				buttons: [
							{ text: "$sCancel",
							 class: "ibo-is-alternative ibo-is-neutral",
							 click: function() {
								$(this).dialog('close');
							} },
							{ text: "$sOK",
							 class: "ibo-is-regular ibo-is-primary",
							 click: function() {
								oACWidget_{$this->iId}.DoOk();
							} },
				],
		});
		$('#fs_{$this->iId}').on('submit.uiAutocomplete', oACWidget_{$this->iId}.DoSearchObjects);
		$('#dc_{$this->iId}').on('resize', oACWidget_{$this->iId}.UpdateSizes);
JS
		);
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
		$oBlock->Display($oP, $this->iId.'_results', array('this' => $oObj, 'cssCount'=> '#count_'.$this->iId.'_results', 'menu' => false, 'selection_mode' => true, 'selection_type' => 'single', 'table_id' => 'select_'.$this->sAttCode)); // Don't display the 'Actions' menu on the results
	}

    /**
     * Search for objects to be selected
     *
     * @param WebPage $oP The page used for the output (usually an AjaxWebPage)
     * @param string $sFilter The OQL expression used to define/limit limit the scope of possible values
     * @param DBObject $oObj The current object for the OQL context
     * @param string $sContains The text of the autocomplete to filter the results
     * @param string $sOutputFormat
     * @param null $sOperation for the values @see ValueSetObjects->LoadValues() not used since 3.0.0
     *
     * @throws CoreException
     * @throws OQLException
     *
     * @since 2.7.7 3.0.1 3.1.0 N°3129 Remove default value for $oObj for PHP 8.0 compatibility
     */
	public function AutoComplete(WebPage $oP, $sFilter, $oObj, $sContains, $sOutputFormat = self::ENUM_OUTPUT_FORMAT_CSV, $sOperation = null	)
	{
		if (is_null($sFilter)) {
			throw new Exception('Implementation: null value for allowed values definition');
		}

		// Current extkey value, so we can display event if it is not available anymore (eg. archived).
		$iCurrentExtKeyId = (is_null($oObj) || $this->sAttCode === '') ? 0 : $oObj->Get($this->sAttCode);
		$oValuesSet = new ValueSetObjects($sFilter, 'friendlyname'); // Bypass GetName() to avoid the encoding by htmlentities
		$iMax = MetaModel::GetConfig()->Get('max_autocomplete_results');
		$oValuesSet->SetLimit($iMax);
		$oValuesSet->SetSort(false);
		$oValuesSet->SetModifierProperty('UserRightsGetSelectFilter', 'bSearchMode', $this->bSearchMode);
		$oValuesSet->SetLimit($iMax);
		$aValuesStartWith = $oValuesSet->GetValuesForAutocomplete(array('this' => $oObj, 'current_extkey_id' => $iCurrentExtKeyId), $sContains, 'start_with');
		asort($aValuesStartWith);
		$aValues = $aValuesStartWith;
		if (sizeof($aValues) < $iMax) {
			$aValuesContains = $oValuesSet->GetValuesForAutocomplete(array('this' => $oObj, 'current_extkey_id' => $iCurrentExtKeyId), $sContains, 'contains');
			asort($aValuesContains);
			$iSize = sizeof($aValues);
			foreach ($aValuesContains as $sKey => $sFriendlyName)
			{
				if (!isset($aValues[$sKey]))
				{
					$aValues[$sKey] = $sFriendlyName;
					if (++$iSize >= $iMax)
					{
						break;
					}
				}
			}
		}
		elseif (!in_array($sContains, $aValues))
		{
			$aValuesEquals = $oValuesSet->GetValuesForAutocomplete(array('this' => $oObj, 'current_extkey_id' => $iCurrentExtKeyId), $sContains,	'equals');
			// Note: Here we cannot use array_merge as it would reindex the numeric keys starting from 0 when keys are actually the objects ID.
			// As a workaround we use array_replace as it does preserve numeric keys. It's ok if some values from $aValuesEquals are replaced with values from $aValues as they contain the same data.
			$aValues = array_replace($aValuesEquals, $aValues);
		}

		switch($sOutputFormat)
		{
			case static::ENUM_OUTPUT_FORMAT_JSON:

				$aJsonMap = array();
				foreach ($aValues as $sKey => $aValue) {
					$aElt = ['value' => $sKey, 'label' => utils::EscapeHtml($aValue['label']), 'obsolescence_flag' => $aValue['obsolescence_flag']];
					if ($aValue['additional_field'] != '') {
						$aElt['additional_field'] = utils::EscapeHtml($aValue['additional_field']);
					}

					if (array_key_exists('initials', $aValue)) {
						$aElt['initials'] = utils::FormatInitialsForMedallion($aValue['initials']);
						if (array_key_exists('picture_url', $aValue)) {
							$aElt['picture_url'] = $aValue['picture_url'];
						}
					}
					$aJsonMap[] = $aElt;
				}

				$oP->SetContentType('application/json');
				$oP->add(json_encode($aJsonMap));
				break;

			case static::ENUM_OUTPUT_FORMAT_CSV:
				foreach($aValues as $sKey => $aValue)
				{
					$oP->add(trim($aValue['label'])."\t".$sKey."\n");
				}
				break;
			default:
				throw new Exception('Invalid output format, "'.$sOutputFormat.'" given.');
				break;
		}
	}

	/**
	 * @param int $iObjId object ID
	 * @param string $sFormAttCode attcode if we need a value that isn't the display name
	 *
	 * @return string Either the display name of the selected object or the specified attribute value
	 *
	 * @uses \DBObject::GetName() to get display name
	 *
	 * @since 3.0.0 N°3227 add the $sAttCode parameter and method PHPDoc
	 */
	public function GetObjectName($iObjId, $sFormAttCode = null)
	{
		$aModifierProps = array();
		$aModifierProps['UserRightsGetSelectFilter']['bSearchMode'] = $this->bSearchMode;

		$oObj = MetaModel::GetObject($this->sTargetClass, $iObjId, false, false, $aModifierProps);
		if ($oObj) {
			if (is_null($sFormAttCode)) {
				return $oObj->GetName();
			} else {
				return $oObj->Get($sFormAttCode);
			}
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
        $aSubClasses = MetaModel::EnumChildClasses($this->sTargetClass, ENUM_CHILD_CLASSES_ALL);
        $aPossibleClasses = array();
        foreach($aSubClasses as $sCandidateClass)
        {
            if (!MetaModel::IsAbstract($sCandidateClass) && (UserRights::IsActionAllowed($sCandidateClass, UR_ACTION_MODIFY) == UR_ALLOWED_YES))
            {
                $aPossibleClasses[$sCandidateClass] = MetaModel::GetName($sCandidateClass);
            }
        }

		$sClassLabel = MetaModel::GetName($this->sTargetClass);
        $sDialogTitle = Dict::Format('UI:CreationTitle_Class', $sClassLabel);;
        $oBlock = UIContentBlockUIBlockFactory::MakeStandard('ac_create_'.$this->iId,['ibo-is-visible']);
		$oPage->AddSubBlock($oBlock);
		$oClassForm = FormUIBlockFactory::MakeStandard();
		$oBlock->AddSubBlock($oClassForm);
		$oClassForm->AddSubBlock(cmdbAbstractObject::DisplayBlockSelectClassToCreate( $sClassLabel, $this->sTargetClass,   $aPossibleClasses));
		$sDialogTitleEscaped = addslashes($sDialogTitle);
        $oPage->add_ready_script("$('#ac_create_$this->iId').dialog({ width: 'auto', height: 'auto', maxHeight: $(window).height() - 50, autoOpen: false, modal: true, title: '$sDialogTitleEscaped'});\n");
        $oPage->add_ready_script("$('#ac_create_{$this->iId} form').removeAttr('onsubmit');");
        $oPage->add_ready_script("$('#ac_create_{$this->iId} form').find('select').attr('id', 'ac_create_{$this->iId}_select');");
        $oPage->add_ready_script("$('#ac_create_{$this->iId} form').on('submit.uilinksWizard', oACWidget_{$this->iId}.DoSelectObjectClass);");
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
				foreach($aConsts[$sClassAlias] as $sAttCode => $value) {
					$oNewObj->Set($sAttCode, $value);
				}
			}
		}

		// 3rd - set values from the page argument 'default'
		$oNewObj->UpdateObjectFromArg('default');

		$sClassLabel = MetaModel::GetName($this->sTargetClass);
		$sHeaderTitleEscaped = utils::EscapeHtml(Dict::Format('UI:CreationTitle_Class', $sClassLabel));

		$oPage->add(<<<HTML
<div id="ac_create_{$this->iId}" title="{$sHeaderTitleEscaped}">
	<div id="dcr_{$this->iId}">
HTML
		);

		$aFormExtraParams = array(
			'formPrefix'  => $this->iId,
			'noRelations' => true,
		);

		// Remove blob edition from creation form @see N°5863 to allow blob edition in modal context
		FormHelper::DisableAttributeBlobInputs($this->sTargetClass, $aFormExtraParams);

		if(FormHelper::HasMandatoryAttributeBlobInputs($oNewObj)){
			$oPage->AddUiBlock(FormHelper::GetAlertForMandatoryAttributeBlobInputsInModal(FormHelper::ENUM_MANDATORY_BLOB_MODE_CREATE));
		}
		
		cmdbAbstractObject::DisplayCreationForm($oPage, $this->sTargetClass, $oNewObj, array(), $aFormExtraParams);
		$oPage->add(<<<HTML
	</div>
</div>
HTML
		);

		$oPage->add_ready_script(<<<JS
$('#ac_create_{$this->iId}').dialog({ width: $(window).width() * 0.6, height: 'auto', maxHeight: $(window).height() - 50, autoOpen: false, modal: true});
$('#dcr_{$this->iId} form').removeAttr('onsubmit');
$('#dcr_{$this->iId} form').find('button[type="submit"]').on('click', oACWidget_{$this->iId}.DoCreateObject);
JS
		);
	}

	/**
	 * Display the hierarchy of the 'target' class
	 */
	public function DisplayHierarchy(WebPage $oPage, $sFilter, $currValue, $oObj)
	{
		$sDialogTitle = addslashes(Dict::Format('UI:HierarchyOf_Class', MetaModel::GetName($this->sTargetClass)));
		$oPage->add('<div id="dlg_tree_'.$this->iId.'"><div class="wizContainer" style="vertical-align:top;"><div style="margin-bottom:5px;" id="tree_'.$this->iId.'">');
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
			$oPage->add('<span class="treecontrol ibo-button-group" id="treecontrolid"><a class="ibo-button ibo-is-regular ibo-is-neutral" href="?#">'.Dict::S("UI:Treeview:CollapseAll").'</a><a class="ibo-button ibo-is-regular ibo-is-neutral" href="?#">'.Dict::S("UI:Treeview:ExpandAll").'</a></span>');
		}
		
		$oPage->add('</div></div>');
		
		$sOkButtonLabel = Dict::S('UI:Button:Ok');
		$sCancelButtonLabel = Dict::S('UI:Button:Cancel');
		$oPage->add_ready_script("\$('#tree_$this->iId ul').treeview({ control: '#treecontrolid',	persist: 'false'});\n");
		$oPage->add_ready_script(<<<JS
$('#dlg_tree_$this->iId').dialog({
	width: 'auto',
	height: 'auto',
	autoOpen: true,
	modal: true,
	title: '$sDialogTitle',
    open: function() {
        $(this).css("max-height", parseInt($(window).height()*0.7)+'px');        
    },
	buttons: [
		{ 
			text: "$sCancelButtonLabel", 
			click: function() { $(this).dialog( "close" ); } 
		},
		{ 
			text: "$sOkButtonLabel",
		   	class: "ibo-is-primary",
			click: function() {
				oACWidget_{$this->iId}.DoHKOk();
			},
		},
		],

	resizeStop: oACWidget_{$this->iId}.OnHKResize,
	close: oACWidget_{$this->iId}.OnHKClose 
});

$('#dlg_tree_$this->iId + .ui-dialog-buttonpane .ui-dialog-buttonset').prepend($('#treecontrolid'));

JS
		);
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
			if (count($aErrors) == 0) {

				// Retrieve JSON data
				$sJSON = utils::ReadParam('json', '{}', false, utils::ENUM_SANITIZATION_FILTER_RAW_DATA);
				$oJSON = json_decode($sJSON);

				$oObj->SetContextSection('temporary_objects', [
					'create' => [
						'transaction_id' => utils::ReadParam('root_transaction_id', '', false, utils::ENUM_SANITIZATION_FILTER_TRANSACTION_ID),
						'host_class'     => $oJSON->m_sClass,
						'host_att_code'  => $this->sAttCode,
					],
				]);
				$oObj->DBInsertNoReload();

				return array('name' => $oObj->GetName(), 'id' => $oObj->GetKey());
			} else {
				return array('error' => implode(' ', $aErrors), 'id' => 0);
			}
		}
		catch (Exception $e) {
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
