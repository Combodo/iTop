<?php

/**
 * A notification
 *
 * @package     iTopORM
 */
abstract class ActionNotification extends Action
{
    /**
     * @inheritDoc
     * @throws \CoreException
     */
    public static function Init()
    {
        $aParams = array
        (
            "category" => "grant_by_profile,core/cmdb",
            "key_type" => "autoincrement",
            "name_attcode" => "name",
            "complementary_name_attcode" => ['finalclass', 'description'],
            "state_attcode" => "",
            "reconc_keys" => ['name'],
            "db_table" => "priv_action_notification",
            "db_key_field" => "id",
            "db_finalclass_field" => "",
        );
        MetaModel::Init_Params($aParams);
        MetaModel::Init_InheritAttributes();

        // Display lists
        // - Attributes to be displayed for the complete details
        MetaModel::Init_SetZListItems('details', array('name', 'description', 'status', 'trigger_list'));
        // - Attributes to be displayed for a list
        MetaModel::Init_SetZListItems('list', array('finalclass', 'description', 'status'));
        MetaModel::Init_AddAttribute(new AttributeApplicationLanguage("language", array("sql" => "language", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));

        // Search criteria
        // - Criteria of the std search form
//		MetaModel::Init_SetZListItems('standard_search', array('name'));
        // - Default criteria of the search form
//		MetaModel::Init_SetZListItems('default_search', array('name'));
    }

    /**
     * @param $sLanguage
     * @param $sLanguageCode
     *
     * @return array [$sPreviousLanguage, $aPreviousPluginProperties]
     * @throws \ArchivedObjectException
     * @throws \CoreException
     * @throws \DictExceptionUnknownLanguage
     * @since 3.2.0
     */
    public function SetNotificationLanguage($sLanguage = null, $sLanguageCode = null)
    {
        $sPreviousLanguage = Dict::GetUserLanguage();
        $aPreviousPluginProperties = ApplicationContext::GetPluginProperties('QueryLocalizerPlugin');
        $sLanguage = $sLanguage ?? $this->Get('language');
        $sLanguageCode = $sLanguageCode ?? $sLanguage;
        if (!utils::IsNullOrEmptyString($sLanguage)) {
            // If a language is specified for this action, force this language
            // when rendering all placeholders inside this message
            Dict::SetUserLanguage($sLanguage);
            AttributeDateTime::LoadFormatFromConfig();
            ApplicationContext::SetPluginProperty('QueryLocalizerPlugin', 'language_code', $sLanguageCode);
        }
        return [$sPreviousLanguage, $aPreviousPluginProperties];
    }
}