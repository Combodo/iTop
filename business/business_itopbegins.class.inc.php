<?php

require_once('../core/MyHelpers.class.inc.php');
require_once('../core/cmdbobject.class.inc.php');

/**
 * business_itopbegins.class.inc.php
 * User defined objects, for unit testing 
 *
 * @package     iTopUnitTests
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @author      Denis Flaven <denisflave@free.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */


///////////////////////////////////////////////////////////////////////////////
// Business implementation demo
///////////////////////////////////////////////////////////////////////////////


/**
 * blah blah 
 *
 * @package     iTopUnitTests
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */
class cmdbContact extends CMDBObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "blah",
			"name" => "klassContact",
			"description" => "klass contact description",
			"key_type" => "autoincrement",
			"key_label" => "",
			"name_attcode" => "att_contact_name",
			"state_attcode" => "",
			"reconc_keys" => array("att_contact_name"),
			"db_table" => "contact",
			"db_key_field" => "contactid",
			"db_finalclass_field" => "actualclass",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("att_contact_name", array("label"=>"name of the contact", "description"=>"blah", "allowed_values"=>null, "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array(), "sql"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeInteger("att_contact_availability", array("label"=>"degree of availability in percent", "description"=>"blah", "allowed_values"=>null, "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array(), "sql"=>"availability")));
		MetaModel::Init_AddAttribute(new AttributeDate("start_date", array("label"=>"Starting date", "description"=>"Incident starting date", "allowed_values"=>null, "sql"=>"start_date", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("att_contact_name");
		MetaModel::Init_AddFilterFromAttribute("att_contact_availability");
	}
}

/**
 * blah blah 
 *
 * @package     iTopUnitTests
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */
class cmdbPerson extends cmdbContact
{
	public static function Init()
	{
		$oValsDunsNumber = new ValueSetObjects("cmdbCompany: att_company_dunsnumber Begins with '$[duns_prm::]'", "att_company_dunsnumber", array("att_company_dunsnumber"=>true));

		$aParams = array
		(
			"category" => "blah",
			"name" => "klassPerson",
			"description" => "klass person description",
			"key_type" => "autoincrement",
			"key_label" => "",
			"name_attcode" => "att_contact_name",
			"state_attcode" => "",
			"reconc_keys" => array("att_contact_name"),
			"db_table" => "person",
			"db_key_field" => "personid",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("att_person_email", array("label"=>"iMaile", "description"=>"imelle", "allowed_values"=>$oValsDunsNumber, "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array(), "sql"=>"email")));
		MetaModel::Init_AddAttribute(new AttributeString("att_person_name", array("label"=>"secName", "description"=>"secondary name", "allowed_values"=>new ValueSetEnum(array("nom1", "nom2", "nom10", "no", "noms", "")), "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array(), "sql"=>"name")));

		MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("att_person_email");
	}
}

/**
 * blah blah 
 *
 * @package     iTopUnitTests
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */
class cmdbSubcontractor extends cmdbPerson
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "blah",
			"name" => "klassSubcontractor",
			"description" => "klass subcontractor description",
			"key_type" => "autoincrement",
			"key_label" => "",
			"name_attcode" => "att_contact_name",
			"state_attcode" => "",
			"reconc_keys" => array("att_contact_name"),
			"db_table" => "subcontractor",
			"db_key_field" => "subcontractorid",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("att_contractinfo", array("label"=>"contract info", "description"=>"blah", "allowed_values"=>null, "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array(), "sql"=>"contractinfo")));

		MetaModel::Init_AddAttribute(new AttributeExternalKey("ext_subcontractor_provider", array("label"=>"ssii", "description"=>"blah", "allowed_values"=>null, "sql"=>"provider", "targetclass"=>"cmdbProvider", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("extatt_subcontractor_provider_ref", array("label"=>"ref", "description"=>"blah", "allowed_values"=>null, "extkey_attcode"=>"ext_subcontractor_provider", "target_attcode"=>"att_provider_ref")));

		MetaModel::Init_AddAttribute(new AttributeExternalKey("ext_subcontractor_tutor", array("label"=>"tutor", "description"=>"blah", "allowed_values"=>null, "sql"=>"tutor", "targetclass"=>"cmdbPerson", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("extatt_subcontractor_tutor_email", array("label"=>"tutor email", "description"=>"blah", "allowed_values"=>null, "extkey_attcode"=>"ext_subcontractor_tutor", "target_attcode"=>"att_person_email")));
		MetaModel::Init_AddAttribute(new AttributeExternalField("extatt_subcontractor_tutor_secondname", array("label"=>"2ndname (ext field)", "description"=>"blah", "allowed_values"=>null, "extkey_attcode"=>"ext_subcontractor_tutor", "target_attcode"=>"att_person_name")));

		MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("extatt_subcontractor_tutor_secondname");
	}
}

/**
 * blah blah 
 *
 * @package     iTopUnitTests
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */
class cmdbCrowd extends cmdbObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "blah",
			"name" => "klassCrowd",
			"description" => "klass crowd description",
			"key_type" => "autoincrement",
			"key_label" => "",
			"name_attcode" => "att_crowd_peoplecount",
			"state_attcode" => "",
			"reconc_keys" => array("att_crowd_peoplecount"),
			"db_table" => "crowd",
			"db_key_field" => "crowdid",
			"db_finalclass_field" => "crowdclass",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeInteger("att_crowd_peoplecount", array("label"=>"people count", "description"=>"blah", "allowed_values"=>null, "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array(), "sql"=>"peoplecount")));

		MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("att_crowd_peoplecount");
	}
}

/**
 * blah blah 
 *
 * @package     iTopUnitTests
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */
class cmdbCompany extends cmdbCrowd
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "blah",
			"name" => "klassCompany",
			"description" => "klass company description",
			"key_type" => "autoincrement",
			"key_label" => "",
			"name_attcode" => "att_company_dunsnumber",
			"state_attcode" => "",
			"reconc_keys" => array("att_company_dunsnumber"),
			"db_table" => "company",
			"db_key_field" => "companyid",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("att_company_dunsnumber", array("label"=>"duns number", "description"=>"blah", "allowed_values"=>null, "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array(), "sql"=>"dunsnumber")));

		MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("att_company_dunsnumber");
	}
}

/**
 * blah blah 
 *
 * @package     iTopUnitTests
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */
class cmdbProvider extends cmdbCompany
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "blah",
			"name" => "klassProvider",
			"description" => "klass provider description",
			"key_type" => "autoincrement",
			"key_label" => "",
			"name_attcode" => "att_provider_ref",
			"state_attcode" => "",
			"reconc_keys" => array("att_provider_ref"),
			"db_table" => "provider",
			"db_key_field" => "providerid",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeInteger("att_provider_ref", array("label"=>"provider ref", "description"=>"blah", "allowed_values"=>null, "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array(), "sql"=>"providerref")));

		MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("att_provider_ref");
	}
}


?>
