<?php

class EventIssue extends Event
{
    public static function Init()
    {
        $aParams = array
        (
            "category" => "core/cmdb,view_in_gui",
            "key_type" => "autoincrement",
            "name_attcode" => "",
            "state_attcode" => "",
            "reconc_keys" => array(),
            "db_table" => "priv_event_issue",
            "db_key_field" => "id",
            "db_finalclass_field" => "",
            "order_by_default" => array('date' => false)
        );
        MetaModel::Init_Params($aParams);
        MetaModel::Init_InheritAttributes();
        MetaModel::Init_AddAttribute(new AttributeString("issue", array("allowed_values" => null, "sql" => "issue", "default_value" => null, "is_null_allowed" => false, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeString("impact", array("allowed_values" => null, "sql" => "impact", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeString("page", array("allowed_values" => null, "sql" => "page", "default_value" => null, "is_null_allowed" => false, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributePropertySet("arguments_post", array("allowed_values" => null, "sql" => "arguments_post", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributePropertySet("arguments_get", array("allowed_values" => null, "sql" => "arguments_get", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeTable("callstack", array("allowed_values" => null, "sql" => "callstack", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributePropertySet("data", array("allowed_values" => null, "sql" => "data", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));

        // Display lists
        MetaModel::Init_SetZListItems('details', array('date', 'message', 'userinfo', 'issue', 'impact', 'page', 'arguments_post', 'arguments_get', 'callstack', 'data')); // Attributes to be displayed for the complete details
        MetaModel::Init_SetZListItems('list', array('date', 'userinfo', 'issue', 'impact')); // Attributes to be displayed for a list
        // Search criteria
//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
    }

    protected function OnInsert()
    {
        // Init page information: name, arguments
        //
        $this->Set('page', @$GLOBALS['_SERVER']['SCRIPT_NAME']);

        if (strlen($this->Get('userinfo')) == 0) {
            $this->Set('userinfo', UserRights::GetUserId());
        }

        if (array_key_exists('_GET', $GLOBALS) && is_array($GLOBALS['_GET'])) {
            $this->Set('arguments_get', $this->SanitizeRequestParams($GLOBALS['_GET']));
        } else {
            $this->Set('arguments_get', array());
        }

        if (array_key_exists('_POST', $GLOBALS) && is_array($GLOBALS['_POST'])) {
            $this->Set('arguments_post', $this->SanitizeRequestParams($GLOBALS['_POST']));
        } else {
            $this->Set('arguments_post', array());
        }
        $sLength = mb_strlen($this->Get('issue'));
        if ($sLength > 255) {
            $this->Set('issue', mb_substr($this->Get('issue'), 0, 210) . " -truncated ($sLength chars)");
        }

        $sLength = mb_strlen($this->Get('impact'));
        if ($sLength > 255) {
            $this->Set('impact', mb_substr($this->Get('impact'), 0, 210) . " -truncated ($sLength chars)");
        }

        $sLength = mb_strlen($this->Get('page'));
        if ($sLength > 255) {
            $this->Set('page', mb_substr($this->Get('page'), 0, 210) . " -truncated ($sLength chars)");
        }
    }

    protected function SanitizeRequestParams(array $aParams): array
    {
        $aSanitizedParams = [];

        foreach ($aParams as $sKey => $sValue) {
            if (is_string($sValue)) {
                if (stristr($sKey, 'pwd') !== false || stristr($sKey, 'passwd') !== false || stristr($sKey, 'password') !== false) {
                    $aSanitizedParams[$sKey] = '****';
                } elseif (mb_strlen($sValue) < 256) {
                    $aSanitizedParams[$sKey] = $sValue;
                } else {
                    $aSanitizedParams[$sKey] = '!long string: ' . mb_strlen($sValue) . ' chars';
                }
            } else {
                // Not a string (avoid warnings in case the value cannot be easily cast into a string)
                $aSanitizedParams[$sKey] = @(string)$sValue;
            }
        }


        return $aSanitizedParams;
    }
}