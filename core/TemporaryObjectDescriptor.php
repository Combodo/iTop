<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

/**
 * Class TemporaryObjectDescriptor
 *
 * Descriptor to track a temporary object.
 *
 * @experimental do not use, this feature will be part of a future version
 *
 * @since 3.1
 */
class TemporaryObjectDescriptor extends DBObject
{
	public static function Init()
	{
		$aParams = array(
			'category'            => 'core',
			'key_type'            => 'autoincrement',
			'name_attcode'        => array('item_class', 'temp_id'),
			'image_attcode'       => '',
			'state_attcode'       => '',
			'reconc_keys'         => array(''),
			'db_table'            => 'priv_temporary_object_descriptor',
			'db_key_field'        => 'id',
			'db_finalclass_field' => '',
			'style'               => new ormStyle(null, null, null, null, null, null),
			'indexes'             => array(
				1 =>
					array(
						0 => 'temp_id',
					),
				2 =>
					array(
						0 => 'item_class',
						1 => 'item_id',
					),
			),
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeDateTime('expiration_date', array('sql' => 'expiration_date', 'is_null_allowed' => false, 'default_value' => '', 'allowed_values' => null, 'depends_on' => array(), 'always_load_in_tables' => false)));
		MetaModel::Init_AddAttribute(new AttributeString('temp_id', array('sql' => 'temp_id', 'is_null_allowed' => true, 'default_value' => '', 'allowed_values' => null, 'depends_on' => array(), 'always_load_in_tables' => false)));
		MetaModel::Init_AddAttribute(new AttributeString('item_class', array('sql' => 'item_class', 'is_null_allowed' => false, 'default_value' => '', 'allowed_values' => null, 'depends_on' => array(), 'always_load_in_tables' => false)));
		MetaModel::Init_AddAttribute(new AttributeObjectKey('item_id', array('class_attcode' => 'item_class', 'sql' => 'item_id', 'is_null_allowed' => true, 'allowed_values' => null, 'depends_on' => array(), 'always_load_in_tables' => false)));
		MetaModel::Init_AddAttribute(new AttributeDateTime('creation_date', array('sql' => 'creation_date', 'is_null_allowed' => true, 'default_value' => '', 'allowed_values' => null, 'depends_on' => array(), 'always_load_in_tables' => false)));
		MetaModel::Init_AddAttribute(new AttributeString('host_class', array('sql' => 'host_class', 'is_null_allowed' => true, 'default_value' => '', 'allowed_values' => null, 'depends_on' => array(), 'always_load_in_tables' => false)));
		MetaModel::Init_AddAttribute(new AttributeObjectKey('host_id', array('class_attcode' => 'host_class', 'sql' => 'host_id', 'is_null_allowed' => true, 'allowed_values' => null, 'depends_on' => array(), 'always_load_in_tables' => false)));
		MetaModel::Init_AddAttribute(new AttributeString('host_att_code', array('sql' => 'host_att_code', 'is_null_allowed' => true, 'default_value' => '', 'allowed_values' => null, 'depends_on' => array(), 'always_load_in_tables' => false)));
		MetaModel::Init_AddAttribute(new AttributeEnum("operation", array("allowed_values" => new ValueSetEnum('create,delete'), "sql" => "operation", "default_value" => "create", "is_null_allowed" => true, "depends_on" => array())));

		MetaModel::Init_SetZListItems('details', array(
			0 => 'temp_id',
			1 => 'item_class',
			2 => 'item_id',
			3 => 'creation_date',
			4 => 'expiration_date',
			5 => 'meta',
		));
		MetaModel::Init_SetZListItems('standard_search', array(
			0 => 'temp_id',
			1 => 'item_class',
			2 => 'item_id',
		));
		MetaModel::Init_SetZListItems('list', array(
			0 => 'temp_id',
			1 => 'item_class',
			2 => 'item_id',
			3 => 'creation_date',
			4 => 'expiration_date',
		));;
	}


	public function DBInsertNoReload()
	{
		$this->SetCurrentDateIfNull('creation_date');

		return parent::DBInsertNoReload();
	}


	/**
	 * Set/Update all of the '_item' fields
	 *
	 * @param object $oItem Container item
	 *
	 * @return void
	 */
	public function SetItem($oItem, $bUpdateOnChange = false)
	{
		$sClass = get_class($oItem);
		$iItemId = $oItem->GetKey();

		$this->Set('item_class', $sClass);
		$this->Set('item_id', $iItemId);
	}
}
