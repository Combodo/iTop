<?php

class AttributeArchiveFlag extends AttributeBoolean
{
    public function __construct($sCode)
    {
        parent::__construct($sCode, array(
            "allowed_values" => null,
            "sql" => $sCode,
            "default_value" => false,
            "is_null_allowed" => false,
            "depends_on" => array()
        ));
    }

    public function RequiresIndex()
    {
        return true;
    }

    public function CopyOnAllTables()
    {
        return true;
    }

    public function IsWritable()
    {
        return false;
    }

    public function IsMagic()
    {
        return true;
    }

    public function GetLabel($sDefault = null)
    {
        $sDefault = Dict::S('Core:AttributeArchiveFlag/Label', $sDefault);

        return parent::GetLabel($sDefault);
    }

    public function GetDescription($sDefault = null)
    {
        $sDefault = Dict::S('Core:AttributeArchiveFlag/Label+', $sDefault);

        return parent::GetDescription($sDefault);
    }
}