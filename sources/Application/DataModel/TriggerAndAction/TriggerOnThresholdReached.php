<?php

/**
 * Class TriggerOnThresholdReached
 */
class TriggerOnThresholdReached extends TriggerOnObject
{
	/**
	 * @throws \CoreException
	 * @throws \Exception
	 */
	public static function Init()
	{
		$aParams = array
		(
			"category"                   => "grant_by_profile,core/cmdb,application",
			"key_type"                   => "autoincrement",
			"name_attcode"               => "description",
			"complementary_name_attcode" => ['finalclass', 'complement'],
			"state_attcode"              => "",
			"reconc_keys"                => ['description'],
			"db_table"                   => "priv_trigger_threshold",
			"db_key_field"               => "id",
			"db_finalclass_field"        => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeClassAttCodeSet('stop_watch_code', array(
			"allowed_values"                      => null,
			"class_field"                         => "target_class",
			"sql"                                 => "stop_watch_code",
			"default_value"                       => null,
			"is_null_allowed"                     => false,
			"max_items"                           => 1,
			"min_items"                           => 1,
			"attribute_definition_exclusion_list" => null,
			"attribute_definition_list"           => "AttributeStopWatch",
			"include_child_classes_attributes"    => true,
			"depends_on"                          => array('target_class'),
		)));
		MetaModel::Init_AddAttribute(new AttributeString("threshold_index", array("allowed_values" => null, "sql" => "threshold_index", "default_value" => null, "is_null_allowed" => false, "depends_on" => array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('description', 'context', 'target_class', 'stop_watch_code', 'threshold_index', 'filter', 'subscription_policy', 'action_list')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('target_class', 'threshold_index', 'threshold_index')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('description', 'target_class')); // Criteria of the std search form
		//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}
}