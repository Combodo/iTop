<?php
/*
* @copyright   Copyright (C) 2010-2025 Combodo SAS
* @license     http://opensource.org/licenses/AGPL-3.0
* @since 3.3.0
 */
use Combodo\iTop\Application\UI\Base\Component\Alert\AlertUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Html\Html;
use Combodo\iTop\Application\UI\Base\Component\Html\HtmlFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\Select\SelectOptionUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\Select\SelectUIBlockFactory;
use Combodo\iTop\Application\UI\Base\UIBlock;
use Combodo\iTop\Core\MetaModel\FriendlyNameType;


/**
 * This class allow to define placeholder to be used in the audit 'rule' and audit 'category' OQL queries.
 *
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class AuditFilterField extends cmdbAbstractObject
{
    public static function Init()
    {
        $aParams = array
        (
            'category'            => 'application,grant_by_profile',
            'key_type'            => 'autoincrement',
            'name_attcode'        => 'placeholder',
            'state_attcode'       => '',
            'reconc_keys'         => array('placeholder'),
            'db_table'            => 'priv_auditfilterfield',
            'db_key_field'        => 'id',
            'db_finalclass_field' => '',
            'style'               => new ormStyle(null, null, null, null, null, '../images/icons/icons8-audit-filtre.svg'),
        );
        MetaModel::Init_Params($aParams);
        MetaModel::Init_AddAttribute(new AttributeString("placeholder", array("allowed_values" => null, "sql" => "placeholder", "default_value" => "", "is_null_allowed" => false, "depends_on" => array(), "validation_pattern"=>'^\w+$')));
        MetaModel::Init_AddAttribute(new AttributeString("label", array("allowed_values" => null, "sql" => "label", "default_value" => "", "is_null_allowed" => false, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeEnum("type", array("allowed_values"=>new ValueSetEnum("select_oql,select_values,number,date" , true), "display_style"=>'list', "sql"=>'type', "default_value"=>'', "is_null_allowed"=>true, "depends_on"=>array(), "always_load_in_tables"=>false)));
        MetaModel::Init_AddAttribute(new AttributeOQL("oql", array("allowed_values" => null, "sql" => "oql", "default_value" => "", "is_null_allowed" => true, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeString("values", array("allowed_values" => null, "sql" => "values", "default_value" => "", "is_null_allowed" => true, "depends_on" => array())));


	    MetaModel::Init_AddAttribute(new AttributeExternalKey("auditdomain_id", array("targetclass" => "AuditDomain", "jointype" => null, "allowed_values" => null, "sql" => "auditdomain_id", "is_null_allowed" => false, "on_target_delete" => DEL_SILENT, "depends_on" => array(),	    )));
	    MetaModel::Init_AddAttribute(new AttributeExternalField("auditdomain_name", array("allowed_values" => null, "extkey_attcode" => 'auditdomain_id', "target_attcode" => 'name', "always_load_in_tables" => false)));

        // Display lists
        MetaModel::Init_SetZListItems('details', array('label', 'placeholder', 'type', 'oql', 'values')); // Attributes to be displayed for the complete details
        MetaModel::Init_SetZListItems('list', array('label', 'placeholder', 'type', 'oql','values')); // Attributes to be displayed for a list
        // Search criteria
        MetaModel::Init_SetZListItems('standard_search', array('label', 'placeholder','type')); // Criteria of the std search form
        MetaModel::Init_SetZListItems('default_search', array('label', 'placeholder')); // Criteria of the advanced search form
    }
    /**
     * @param WebPage $oPage
     * @return void
     * @throws ConfigException
     * @throws CoreException
     */
    public static function DisplayListOfFields(WebPage $oPage): void
    {
        $sOQL = 'SELECT AuditFilterField';
        $oSearch = DBObjectSearch::FromOQL($sOQL);
        $oAuditFilterSet = new DBObjectSet($oSearch, array(), array());
        if ($oAuditFilterSet->Count()>0) {
            $sHtml = '<ul style="list-style: unset; padding-left: 20px;">';
            while ($oAuditFilter = $oAuditFilterSet->Fetch()) {
                $sHtml .= '<li><i>:' . $oAuditFilter->Get('placeholder') . '</i> for ' .  $oAuditFilter->Get('label') . '</li>';
            }
            $sHtml .= '</ul>';
            $oInfoBlock = AlertUIBlockFactory::MakeForInformation('In OQL query, you can use this placeholders:', '')
                    ->AddSubBlock(new Html($sHtml))
            ->SetOpenedByDefault(false);
            $oPage->AddUiBlock($oInfoBlock);
        }
    }

    protected function RegisterEventListeners()
    {
        parent::RegisterEventListeners();

        // listenerId = CheckUsersUpdate
        $this->RegisterCRUDListener('EVENT_DB_CHECK_TO_WRITE', 'CheckPlaceholderName', 1, 'itop-structure');
        $this->RegisterCRUDListener('EVENT_DB_CHECK_TO_WRITE', 'CheckMandatoryFields', 2, 'itop-structure');
    }


    public function CheckPlaceholderName(Combodo\iTop\Service\Events\EventData $oEventData)
    {
        $aChanges = $this->ListChanges();
        if (array_key_exists('placeholder', $aChanges)) {
            $sPlaceholder = $this->Get('placeholder');
            if (str_starts_with('this->', $sPlaceholder)) {
                $this->AddCheckIssue(Dict::S('Class:AuditFilterField/Attribute:placeholder:Error:StartWith','this'));
            }
            if (str_starts_with('current_user', $sPlaceholder)) {
                $this->AddCheckIssue(Dict::S('Class:AuditFilterField/Attribute:placeholder:Error:StartWith','current_user'));
            }
            if (str_starts_with('current_contact', $sPlaceholder)) {
                $this->AddCheckIssue(Dict::S('Class:AuditFilterField/Attribute:placeholder:Error:StartWith','current_contact'));
            }
            if (in_array($sPlaceholder, ['current_user', 'current_contact']) ) {
                $this->AddCheckIssue(Dict::S('Class:AuditFilterField/Attribute:placeholder:Error:ReservedWord', $sPlaceholder));
            }
        }
    }
    public function CheckMandatoryFields(Combodo\iTop\Service\Events\EventData $oEventData)
    {
        switch ($this->Get('type')) {
            case 'select_oql':
                if (utils::IsNullOrEmptyString($this->Get('oql'))) {
                    $this->AddCheckIssue(Dict::S('Class:AuditFilterField/Attribute:oql:Error:Empty'));
                }
                break;
            case 'select_values':
                if (utils::IsNullOrEmptyString($this->Get('values'))) {
                    $this->AddCheckIssue(Dict::S('Class:AuditFilterField/Attribute:values:Error:Empty'));
                }
                break;
         //   case 'number':
         //   case 'date':
         //       break;
        }
    }

    public function GetFieldBlock(WebPage $oPage, string $sCurrentValue = ''): UIBlock
    {
        switch ($this->Get('type')) {
            case 'select_oql':
                $sFieldName = $this->Get('placeholder');
                $sOql = $this->Get('oql');
                $sLabel = $this->Get('label');

                $oSearch = DBObjectSearch::FromOQL($sOql);
                $oAllowedValues = new DBObjectSet($oSearch);
                $oAllowedValues->SetShowObsoleteData(utils::ShowObsoleteData());
                $iMaxComboLength = MetaModel::GetConfig()->Get('max_combo_length');

                $bIsAutocomplete = $oAllowedValues->CountExceeds($iMaxComboLength);
                $sWrapperCssClass = $bIsAutocomplete ? 'field_input_extkey ibo-input-wrapper ibo-input-select-wrapper--with-buttons ibo-input-select-autocomplete-wrapper' : 'ibo-input-select-wrapper';
                $sHTMLValue = "<div class=\"field_input_zone  $sWrapperCssClass\">";

                // We just need to compare the number of entries with MaxComboLength, so no need to get the real count.
                if (!$bIsAutocomplete) {
                    // Discrete list of values, use a SELECT or RADIO buttons depending on the config
                    $sHelpText = '';
                    $aOptions = [];
                    $aOptions['value'] = "";
                    $aOptions['label'] = Dict::S('UI:SelectOne');

                    $oAllowedValues->Rewind();
                    $sClassAllowed = $oAllowedValues->GetClass();
                    $bAddingValue = false;

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

                    $oSelect = SelectUIBlockFactory::MakeForSelect($sFieldName, $sFieldName);
                    $oSelect->AddCSSClass('ibo-input-field-wrapper');

                    while ($oChoiceItem = $oAllowedValues->Fetch()) {

                        $sOptionName = utils::HtmlEntityDecode($oChoiceItem->GetName());

                        if ($bAddingValue) {
                            $aArguments = [];
                            foreach ($aAdditionalField as $sAdditionalField) {
                                array_push($aArguments, $oAllowedValues->Get($sAdditionalField));
                            }
                            $sOptionName .= '<br><i>' . utils::HtmlEntities(vsprintf($sFormatAdditionalField, $aArguments)) . '</i>';;
                        }
                        if (!empty($sObjectImageAttCode)) {
                            // Try to retrieve image for contact
                            /** @var \ormDocument $oImage */
                            $oImage = $oAllowedValues->Get($sObjectImageAttCode);
                            if (!$oImage->IsEmpty()) {
                                $sPicturepictureUrl = $oImage->GetDisplayURL($sClassAllowed, $oChoiceItem->GetKey(), $sObjectImageAttCode);
                                $sOptionName .= ' <span class="ibo-input-select--autocomplete-item-image" style="background-image: url(' . $sPicturepictureUrl . ');"></span>';
                            } else {
                                $sInitials = utils::FormatInitialsForMedallion(utils::ToAcronym($oChoiceItem->Get('friendlyname')));
                                $sOptionName .= ' <span class="ibo-input-select--autocomplete-item-image" ">' . $sInitials . '</span>';
                            }
                        }
                        $oOption = SelectOptionUIBlockFactory::MakeForSelectOption($oChoiceItem->GetKey(), $sOptionName, ($sCurrentValue == $oChoiceItem->GetKey()));
                        $oSelect->AddOption($oOption);
                    }
                    $sInputType = CmdbAbstractObject::ENUM_INPUT_TYPE_DROPDOWN_DECORATED;

                    $sJsonOptions = str_replace("'", "\'", str_replace('\\', '\\\\', json_encode($aOptions)));
                    $oPage->add_ready_script(
                        <<<JS
                let select$sFieldName = $('#$sFieldName').selectize({
                            plugins:['custom_itop', 'selectize-plugin-a11y'],                  
                        });
        JS
                    );
                    return $oSelect;
                } else {
                    // Too many choices, use an autocomplete
                    // Check that the given value is allowed
                    $oSearch = $oAllowedValues->GetFilter();
                    $oSearch->AddCondition('id', $sCurrentValue);
                    $oSet = new DBObjectSet($oSearch);
                    $sClass = $oSet->GetClass();
                    if ($oSet->Count() == 0) {
                        $sCurrentValue = null;
                    }

                    if (is_null($sCurrentValue) || ($sCurrentValue == 0)) // Null values are displayed as ''
                    {
                        $sDisplayValue = '';
                    } else {
                        $sDisplayValue = MetaModel::GetObject($sClass, $sCurrentValue)->GetName();
                    }
                    $iMinChars = MetaModel::GetConfig()->Get('min_autocomplete_chars'); //@@@ $this->oAttDef->GetMinAutoCompleteChars();

                    // the input for the auto-complete
                    $sInputType = CmdbAbstractObject::ENUM_INPUT_TYPE_AUTOCOMPLETE;
                    $sHTMLValue .= "<input class=\"field_autocomplete ibo-input ibo-input-select ibo-input-select-autocomplete\" type=\"text\"  id=\"label_$sFieldName\" value=\"$sDisplayValue\" placeholder='...'/>";

                    // another hidden input to store & pass the object's Id
                    $sHTMLValue .= "<input type=\"hidden\" id=\"$sFieldName\" name=\"{$sFieldName}\" value=\"" . utils::HtmlEntities($sCurrentValue) . "\" />\n";

                    $sMessage = Dict::S('UI:Message:EmptyList:UseSearchForm');
                    $oPage->add_ready_script(
                        <<<EOF
                oACWidget_{$sFieldName} = new ExtKeyWidget('$sFieldName', '$sClass', '$sOql', '$sLabel', false, null, '{$sFieldName}', true, false);
                oACWidget_{$sFieldName}.emptyHtml = "<div style=\"background: #fff; border:0; text-align:center; vertical-align:middle;\"><p>$sMessage</p></div>";
                oACWidget_{$sFieldName}.AddAutocomplete($iMinChars, '');
                if ($('#ac_dlg_{$sFieldName}').length == 0)
                {
                    $('body').append('<div id="ac_dlg_{$sFieldName}"></div>');
                }
        EOF
                    );
                    $sHTMLValue .= "<div class=\"ibo-input-select--action-buttons\">";
                    $sHTMLValue .= "<a href=\"#\" class=\"ibo-input-select--action-button ibo-input-select--action-button--clear ibo-is-hidden\"  id=\"mini_clear_{$sFieldName}\" onClick=\"$('#$sFieldName').val('');$('#label_$sFieldName').val('');		$('#label_$sFieldName').data('selected_value', '');\" data-tooltip-content='" . Dict::S('UI:Button:Clear') . "'><i class=\"fas fa-times\"></i></a>";
                    $sHTMLValue .= "<a href=\"#\" class=\"ibo-input-select--action-button ibo-input-select--action-button--search\"  id=\"mini_search_{$sFieldName}\" onClick=\"oACWidget_{$sFieldName}.Search();\" data-tooltip-content='" . Dict::S('UI:Button:Search') . "'><i class=\"fas fa-search\"></i></a>";
                    if (MetaModel::IsHierarchicalClass($sClass) !== false) {
                        $sHTMLValue .= "<a href=\"#\" class=\"ibo-input-select--action-button ibo-input-select--action-button--hierarchy\" id=\"mini_tree_{$sFieldName}\" onClick=\"oACWidget_{$sFieldName}.HKDisplay();\" data-tooltip-content='" . Dict::S('UI:Button:SearchInHierarchy') . "'><i class=\"fas fa-sitemap\"></i></a>";
                        $oPage->add_ready_script(
                            <<<JS
                       if ($('#ac_tree_{$sFieldName}').length == 0)
                       {
                           $('body').append('<div id="ac_tree_{$sFieldName}"></div>');
                       }
           JS
                        );
                    }
                }

                $sHTMLValue .= "</div>";
                $sHTMLValue .= "</div>";

                return new Html($sHTMLValue);
                break;

           case 'select_values':
               $aListValues = explode(',',$this->Get('values'));
               $oSelect = SelectUIBlockFactory::MakeForSelect($this->Get('placeholder'), $this->Get('placeholder'));
               $oSelect->AddCSSClass('ibo-input-field-wrapper');

              foreach($aListValues as $sValue) {
                   $oSelect->AddOption(SelectOptionUIBlockFactory::MakeForSelectOption($sValue, $sValue, ($sCurrentValue == $sValue)));
               }

               return $oSelect;
               break;

            case 'number':
                $oInput = HtmlFactory::MakeRaw('<input type="number" id="'.$this->Get('placeholder').'" name="'.$this->Get('placeholder').'" value="'.$sCurrentValue.'"/>');
                return $oInput;
                break;

            case 'date':

                $sFieldName = $this->Get('placeholder');
                $sDateFormatDatePicker = AttributeDate::GetFormat()->ToDatePicker();

                $oInput = HtmlFactory::MakeRaw('<input class="date date-pick ibo-input ibo-input-date" type="text" id="'.$sFieldName.'" name="'.$sFieldName.'" value="'.$sCurrentValue.'"/>');

                $oPage->add_ready_script(<<<EOF
                    $('#$sFieldName').datepicker({
                            showOn: 'button',
                            buttonImage: '../images/calendar.png',
                            buttonImageOnly: true,
                            dateFormat: '$sDateFormatDatePicker',
                            constrainInput: false,
                            changeMonth: true,
                            changeYear: true,
                    });
EOF);

                return $oInput;
                break;

           default:
               return new Html('Not implemented yet');
        }
    }

}