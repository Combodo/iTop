<?php

/**
 * Copyright (C) 2013-2024 Combodo SAS
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
class Event extends DBObject implements iDisplay
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
            "db_table" => "priv_event",
            "db_key_field" => "id",
            "db_finalclass_field" => "realclass",
            "order_by_default" => array('date' => false)
        );
        MetaModel::Init_Params($aParams);
        //MetaModel::Init_InheritAttributes();
        MetaModel::Init_AddAttribute(new AttributeText("message", array("allowed_values" => null, "sql" => "message", "default_value" => null, "is_null_allowed" => false, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeDateTime("date", array("allowed_values" => null, "sql" => "date", "default_value" => "NOW()", "is_null_allowed" => false, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeString("userinfo", array("allowed_values" => null, "sql" => "userinfo", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));
//		MetaModel::Init_AddAttribute(new AttributeString("userinfo", array("allowed_values"=>null, "sql"=>"userinfo", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));

        // Display lists
        MetaModel::Init_SetZListItems('details', array('date', 'message', 'userinfo')); // Attributes to be displayed for the complete details
        MetaModel::Init_SetZListItems('list', array('date', 'finalclass', 'message')); // Attributes to be displayed for a list
        // Search criteria
//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
    }

    /**
     * Maps the given context parameter name to the appropriate filter/search code for this class
     * @param string $sContextParam Name of the context parameter, i.e. 'org_id'
     * @return string Filter code, i.e. 'customer_id'
     */
    public static function MapContextParam($sContextParam)
    {
        if ($sContextParam == 'menu') {
            return null;
        } else {
            return $sContextParam;
        }
    }

    /**
     * This function returns a 'hilight' CSS class, used to hilight a given row in a table
     * There are currently (i.e defined in the CSS) 4 possible values HILIGHT_CLASS_CRITICAL,
     * HILIGHT_CLASS_WARNING, HILIGHT_CLASS_OK, HILIGHT_CLASS_NONE
     * To Be overridden by derived classes
     * @param void
     * @return String The desired higlight class for the object/row
     */
    public function GetHilightClass()
    {
        // Possible return values are:
        // HILIGHT_CLASS_CRITICAL, HILIGHT_CLASS_WARNING, HILIGHT_CLASS_OK, HILIGHT_CLASS_NONE	
        return HILIGHT_CLASS_NONE; // Not hilighted by default
    }

    public static function GetUIPage()
    {
        return 'UI.php';
    }

    function DisplayDetails(\Combodo\iTop\Application\WebPage\WebPage $oPage, $bEditMode = false)
    {
        // Object's details
        //$this->DisplayBareHeader($oPage, $bEditMode);
        $oPage->AddTabContainer(OBJECT_PROPERTIES_TAB);
        $oPage->SetCurrentTabContainer(OBJECT_PROPERTIES_TAB);
        $oPage->SetCurrentTab('UI:PropertiesTab');
        $this->DisplayBareProperties($oPage, $bEditMode);
    }

    function DisplayBareProperties(\Combodo\iTop\Application\WebPage\WebPage $oPage, $bEditMode = false, $sPrefix = '', $aExtraParams = array())
    {
        if ($bEditMode) return array(); // Not editable

        $aDetails = array();
        $sClass = get_class($this);
        $aZList = MetaModel::FlattenZlist(MetaModel::GetZListItems($sClass, 'details'));
        foreach ($aZList as $sAttCode) {
            $sDisplayValue = $this->GetAsHTML($sAttCode);
            $aDetails[] = array('label' => '<span title="' . MetaModel::GetDescription($sClass, $sAttCode) . '">' . MetaModel::GetLabel($sClass, $sAttCode) . '</span>', 'value' => $sDisplayValue);
        }
        $oPage->Details($aDetails);

        return array();
    }
}