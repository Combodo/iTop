<?php
require_once('../application/cmdbabstract.class.inc.php');
require_once('../application/template.class.inc.php');


/**
 * itop.business.class.inc.php
 * User defined objects, implements the business need 
 *
 * @package     iTopBizModelSamples
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @author      Denis Flaven <denisflave@free.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */

/**
 * Possible values for the statuses of objects
 */
$oAllowedStatuses = new ValueSetEnum('production,implementation,obsolete');

/**
 * Relation graphs
 */
MetaModel::RegisterRelation("impacts", array("description"=>"objects being functionaly impacted", "verb_down"=>"impacts", "verb_up"=>"is impacted by"));

////////////////////////////////////////////////////////////////////////////////////
/**
* An organization that owns some objects
*
* An organization "owns" some persons (its employees) but also some other objects
* (its assets) like buildings, computers, furniture...
* the services that they provides, the contracts/OLA they have signed as customer
* 
* Organization ownership might be used to manage the R/W access to the object
*/
////////////////////////////////////////////////////////////////////////////////////
/**
 * itop.business.class.inc.php
 * User defined objects, implements the business need 
 *
 * @package     iTopBizModelSamples
 * @author      Erwan Taloc <taloche@yahoo.fr>
 * @author      Denis Flaven <denisflave@free.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */
class bizOrganization extends cmdbAbstractObject
{
	public static function Init()
	{
		global $oAllowedStatuses;
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"name" => "Organization",
			"description" => "Organizational structure: can be Company and/or Department",
			"key_type" => "autoincrement",
			"key_label" => "id",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("name"),
			"db_table" => "organizations",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/default.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_AddAttribute(new AttributeString("name", array("label"=>"Name", "description"=>"Common name", "allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array() )));
		MetaModel::Init_AddAttribute(new AttributeString("code", array("label"=>"Code", "description"=>"Organization code (Siret, DUNS,...)", "allowed_values"=>null, "sql"=>"code", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array() )));
		MetaModel::Init_AddAttribute(new AttributeEnum("status", array("label"=>"Status", "description"=>"Lifecycle status", "allowed_values"=>$oAllowedStatuses, "sql"=>"status", "default_value"=>"implementation", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("parent_id", array("targetclass"=>"bizOrganization", "label"=>"Parent Id", "description"=>"Parent organization", "allowed_values"=>null, "sql"=>"parent_id", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("parent_name", array("label"=>"Parent Name", "description"=>"Name of the parent organization", "allowed_values"=>null, "extkey_attcode"=> 'parent_id', "target_attcode"=>"name")));

		MetaModel::Init_AddFilterFromAttribute("name");
		MetaModel::Init_AddFilterFromAttribute("code");
		MetaModel::Init_AddFilterFromAttribute("status");
		
		// Display lists
		MetaModel::Init_SetZListItems('details', array('name', 'code', 'status', 'parent_id')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('name', 'status', 'parent_id')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('name', 'code', 'status')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'code', 'status')); // Criteria of the advanced search form
	}
	
	public function Generate(cmdbDataGenerator $oGenerator)
	{
		//$this->SetKey($oGenerator->GetOrganizationCode());
		$this->Set('name', $oGenerator->GetOrganizationName());
		$this->Set('code', $oGenerator->GetOrganizationCode());
		$this->Set('status', 'implementation');
		$this->Set('parent_id', 1);

	}
}

////////////////////////////////////////////////////////////////////////////////////
/**
* Class of objects owned by some organization
*
* This is the root class of all the objects that can be "owned" by an organization
* 
* A Real Object
*   can be supported by Contacts, having a specific role (same contact with multiple roles?)
*   can be documented by Documents
*/
////////////////////////////////////////////////////////////////////////////////////
/**
 * itop.business.class.inc.php
 * User defined objects, implements the business need 
 *
 * @package     iTopBizModelSamples
 * @author      Erwan Taloc <taloche@yahoo.fr>
 * @author      Denis Flaven <denisflave@free.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */
class logRealObject extends cmdbAbstractObject
{
	public static function Init()
	{
		global $oAllowedStatuses;
		
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"name" => "Object",
			"description" => "Any CMDB object",
			"key_type" => "autoincrement",
			"key_label" => "id",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("name"),
			"db_table" => "objects",
			"db_key_field" => "id",
			"db_finalclass_field" => "obj_class",
			"display_template" => "../business/templates/default.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_AddAttribute(new AttributeString("name", array("label"=>"Name", "description"=>"Common name", "allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("status", array("label"=>"Status", "description"=>"Lifecycle status", "allowed_values"=>$oAllowedStatuses, "sql"=>"status", "default_value"=>"implementation", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("org_id", array("targetclass"=>"bizOrganization", "label"=>"Organization Id", "description"=>"ID of the object owner organization", "allowed_values"=>null, "sql"=>"org_id", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("org_name", array("label"=>"Organization", "description"=>"Company / Department owning this object", "allowed_values"=>null, "extkey_attcode"=> 'org_id', "target_attcode"=>"name")));

		MetaModel::Init_AddFilterFromAttribute("name");
		MetaModel::Init_AddFilterFromAttribute("status");
		MetaModel::Init_AddFilterFromAttribute("org_id");
		MetaModel::Init_AddFilterFromAttribute("org_name");
		
		// Display lists
		MetaModel::Init_SetZListItems('details', array('name', 'status', 'org_id')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('finalclass', 'name', 'status', 'org_id')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status', 'org_id')); // Criteria of the advanced search form
	}
	
	public function Generate(cmdbDataGenerator $oGenerator)
	{
		$this->Set('org_id', $oGenerator->GetOrganizationId());
		$this->Set('name', "<overload in derived class>");
		$this->Set('status', $oGenerator->GenerateString("enum(implementation,production)"));
	}
}

////////////////////////////////////////////////////////////////////////////////////
/**
* Any kind of thing that can be contacted (person, team, hotline...)
* A contact can:
*   be linked to any Real Object with a role
*   be part of a GroupContact
*/
////////////////////////////////////////////////////////////////////////////////////
class bizContact extends logRealObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"name" => "Contact",
			"description" => "Contact",
			"key_type" => "",
			"key_label" => "id",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("org_name", "name"), // inherited attributes
			"db_table" => "contacts",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/default.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeExternalField("org_name", array("label"=>"Organization", "description"=>"Company / Department of the contact", "allowed_values"=>null, "extkey_attcode"=> 'org_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeString("email", array("label"=>"eMail", "description"=>"Email address", "allowed_values"=>null, "sql"=>"email", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("phone", array("label"=>"Phone", "description"=>"Telephone", "allowed_values"=>null, "sql"=>"telephone", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("location_id", array("targetclass"=>"bizLocation", "label"=>"Location Id", "description"=>"Id of the location where the contact is located", "allowed_values"=>null, "sql"=>"location_id", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("location_name", array("label"=>"Location Name", "description"=>"Name of the location where the contact is located", "allowed_values"=>null, "extkey_attcode"=> 'location_id', "target_attcode"=>"name")));

		MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("org_name");
		MetaModel::Init_AddFilterFromAttribute("email");
		MetaModel::Init_AddFilterFromAttribute("phone");
		MetaModel::Init_AddFilterFromAttribute("location_id");
		MetaModel::Init_AddFilterFromAttribute("location_name");
		
		// Display lists
		MetaModel::Init_SetZListItems('details', array('name', 'status', 'org_id', 'email', 'location_id', 'phone')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('finalclass', 'name', 'status', 'org_id', 'email', 'location_id', 'phone')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'email', 'location_id', 'phone')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status', 'org_id')); // Criteria of the advanced search form
	}
	
	public function Generate(cmdbDataGenerator $oGenerator)
	{
		$this->Set('org_id', $oGenerator->GetOrganizationId());
		$this->Set('name', "<overload in derived classes>");
		$this->Set('email', "<overload in derived classes>");
		$this->Set('phone', $oGenerator->GenerateString("enum(+1,+33,+44,+49,+421)| |number(100-999)| |number(000-999)"));
		$this->Set('location_id', $oGenerator->GenerateKey("bizLocation", array('org_id' =>$oGenerator->GetOrganizationId() )));
	}
}

////////////////////////////////////////////////////////////////////////////////////
/**
* Physical person only  
*/
////////////////////////////////////////////////////////////////////////////////////
class bizPerson extends bizContact
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"name" => "Person",
			"description" => "Person",
			"key_type" => "",
			"key_label" => "id",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("org_name", "first_name", "name"),  // comment en définir plusieurs
			// "reconc_keys" => array("org_name", "employe_number"), 
			"db_table" => "persons",   // Can it use the same physical DB table as any contact ?
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/person.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("first_name", array("label"=>"first Name", "description"=>"First name", "allowed_values"=>null, "sql"=>"first_name", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("employe_number", array("label"=>"Employe Number", "description"=>"employe number", "allowed_values"=>null, "sql"=>"employe_number", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("first_name");
		MetaModel::Init_AddFilterFromAttribute("employe_number");
		
		// Display lists
		MetaModel::Init_SetZListItems('details', array('first_name', 'name', 'status', 'org_id', 'email', 'location_id', 'phone', 'employe_number')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('first_name', 'name', 'status', 'org_id', 'email', 'location_id', 'phone')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('first_name', 'name', 'status', 'email', 'location_id', 'phone', 'employe_number')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('first_name', 'name', 'status', 'email', 'location_id', 'phone', 'employe_number')); // Criteria of the advanced search form
	}

	public function Generate(cmdbDataGenerator $oGenerator)
	{
		parent::Generate($oGenerator);
		$this->Set('name', $oGenerator->GenerateLastName());
		$this->Set('first_name', $oGenerator->GenerateFirstName());
		$this->Set('email', $oGenerator->GenerateEmail($this->Get('first_name'), $this->Get('name')));
		$this->Set('phone', $oGenerator->GenerateString("enum(+1,+33,+44,+49,+421)| |number(100-999)| |number(000-999)"));
	}
}

////////////////////////////////////////////////////////////////////////////////////
/**
* A team is basically a contact which is also a group of contacts
* (and thus a team can contain other teams)
*/
////////////////////////////////////////////////////////////////////////////////////
class bizTeam extends bizContact
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"name" => "Team",
			"description" => "A group of contacts",
			"key_type" => "",
			"key_label" => "id",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("org_name", "name"), // inherited attributes
			"db_table" => "teams",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/team.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_InheritFilters();
		
		// Display lists
		MetaModel::Init_SetZListItems('details', array('name', 'status', 'org_id', 'email', 'location_id', 'phone')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('name', 'status', 'org_id', 'email', 'location_id', 'phone')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'email', 'location_id', 'phone')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status', 'org_id')); // Criteria of the advanced search form
	}
}


////////////////////////////////////////////////////////////////////////////////////
/**
* An electronic document, with version tracking
*/
////////////////////////////////////////////////////////////////////////////////////
class bizDocument extends logRealObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"name" => "Document",
			"description" => "Document",
			"key_type" => "",
			"key_label" => "id",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("org_name", "name"), // inherited attributes
			"db_table" => "documents",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/document.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeExternalField("org_name", array("label"=>"Organization", "description"=>"Company / Department owning the document", "allowed_values"=>null, "extkey_attcode"=> 'org_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeEnum("scope", array("label"=>"scope", "description"=>"Scope of this document", "allowed_values"=>new ValueSetEnum("organization,hardware support"), "sql"=>"scope", "default_value"=>"organization", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("description", array("label"=>"Description", "description"=>"Service Description", "allowed_values"=>null, "sql"=>"description", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("scope");
		MetaModel::Init_AddFilterFromAttribute("description");

		MetaModel::Init_SetZListItems('details', array('name', 'status', 'org_id', 'scope','description')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('name', 'status', 'org_id', 'scope')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'scope')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status', 'scope')); // Criteria of the advanced search form

	}

}

////////////////////////////////////////////////////////////////////////////////////
/**
* A version of an electronic document
*/
////////////////////////////////////////////////////////////////////////////////////
class bizDocVersion extends cmdbAbstractObject
{
	public static function Init()
	{
		global $oAllowedStatuses;
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"name" => "DocumentVersion",
			"description" => "A version of a document",
			"key_type" => "autoincrement",
			"key_label" => "id",
			"name_attcode" => "version_number",
			"state_attcode" => "",
			"reconc_keys" => array("docname", "version_number"),
			"db_table" => "document_versions",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/document.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_AddAttribute(new AttributeExternalKey("document", array("targetclass"=>"bizDocument", "label"=>"document", "description"=>"The main document", "allowed_values"=>null, "sql"=>"document_id", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("docname", array("label"=>"document name", "description"=>"name of the document", "allowed_values"=>null, "extkey_attcode"=> 'document', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeString("version_number", array("label"=>"version number", "description"=>"Service name", "allowed_values"=>null, "sql"=>"version_number", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("status", array("label"=>"status", "description"=>"Status", "allowed_values"=>$oAllowedStatuses, "sql"=>"status", "default_value"=>"implementation", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("type", array("label"=>"type", "description"=>"Type", "allowed_values"=>new ValueSetEnum("local,draft"), "sql"=>"type", "default_value"=>"local", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeURL("url", array("label"=>"URL", "description"=>"Hyperlink to the version", "allowed_values"=>null, "target"=>"_blank", "sql"=>"url", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("description", array("label"=>"Description", "description"=>"Service Description", "allowed_values"=>null, "sql"=>"description", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_AddFilterFromAttribute("document");
		MetaModel::Init_AddFilterFromAttribute("docname");
		MetaModel::Init_AddFilterFromAttribute("version_number");
		MetaModel::Init_AddFilterFromAttribute("status");
		MetaModel::Init_AddFilterFromAttribute("type");
		MetaModel::Init_AddFilterFromAttribute("description");

		MetaModel::Init_SetZListItems('details', array('docname', 'status', 'version_number', 'type','url','description')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('version_number', 'status', 'type', 'url')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('docname', 'type')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('docname', 'type')); // Criteria o
	}
}

////////////////////////////////////////////////////////////////////////////////////
/**
* n-n link between any Object and a Document
*/
////////////////////////////////////////////////////////////////////////////////////
class lnkDocumentRealObject extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"name" => "DocumentsLinks",
			"description" => "A link between a document and another object",
			"key_type" => "autoincrement",
			"key_label" => "link_id",
			"name_attcode" => "link_type",
			"state_attcode" => "",
			"reconc_keys" => array("doc_name", "object_name"),
			"db_table" => "documents_links",
			"db_key_field" => "link_id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/default.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_AddAttribute(new AttributeExternalKey("doc_id", array("targetclass"=>"bizDocument", "label"=>"Document Name", "description"=>"id of the Document", "allowed_values"=>null, "sql"=>"doc_id", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("doc_name", array("label"=>"Document", "description"=>"name of the document", "allowed_values"=>null, "extkey_attcode"=> 'doc_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("object_id", array("targetclass"=>"logRealObject", "label"=>"object", "description"=>"Object linked", "allowed_values"=>null, "sql"=>"object_id", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("object_name", array("label"=>"object name", "description"=>"name of the linked object", "allowed_values"=>null, "extkey_attcode"=> 'object_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeString("link_type", array("label"=>"link_type", "description"=>"Type of the link", "allowed_values"=>null, "sql"=>"link_type", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_AddFilterFromAttribute("doc_id");
		MetaModel::Init_AddFilterFromAttribute("doc_name");
		MetaModel::Init_AddFilterFromAttribute("object_id");
		MetaModel::Init_AddFilterFromAttribute("object_name");
		MetaModel::Init_AddFilterFromAttribute("link_type");
		
		// Display lists
		MetaModel::Init_SetZListItems('details', array('doc_id', 'object_name', 'link_type')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('doc_id', 'object_name', 'link_type')); // Attributes to be displayed for a list
	}
}




////////////////////////////////////////////////////////////////////////////////////
/**
* n-n link between any Object and a contact
*/
////////////////////////////////////////////////////////////////////////////////////
class lnkContactRealObject extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"name" => "ContactsLinks",
			"description" => "A link between a contact and another object",
			"key_type" => "autoincrement",
			"key_label" => "link_id",
			"name_attcode" => "role",
			"state_attcode" => "",
			"reconc_keys" => array("contact_name", "object_name"),
			"db_table" => "contacts_links",
			"db_key_field" => "link_id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/default.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_AddAttribute(new AttributeExternalKey("contact_id", array("targetclass"=>"bizContact", "label"=>"Contact", "description"=>"The contact", "allowed_values"=>null, "sql"=>"contact_id", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("contact_name", array("label"=>"Contact name", "description"=>"name of the contact", "allowed_values"=>null, "extkey_attcode"=> 'contact_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeExternalField("contact_phone", array("label"=>"Phone", "description"=>"Phone number of the contact", "allowed_values"=>null, "extkey_attcode"=> 'contact_id', "target_attcode"=>"phone")));
		MetaModel::Init_AddAttribute(new AttributeExternalField("contact_email", array("label"=>"eMail", "description"=>"eMail address of the contact", "allowed_values"=>null, "extkey_attcode"=> 'contact_id', "target_attcode"=>"email")));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("object_id", array("targetclass"=>"logRealObject", "label"=>"object", "description"=>"Object linked", "allowed_values"=>null, "sql"=>"object_id", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("object_name", array("label"=>"Object name", "description"=>"name of the linked object", "allowed_values"=>null, "extkey_attcode"=> 'object_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeString("role", array("label"=>"Role", "description"=>"Role of the contact", "allowed_values"=>null, "sql"=>"role", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_AddFilterFromAttribute("contact_id");
		MetaModel::Init_AddFilterFromAttribute("contact_name");
		MetaModel::Init_AddFilterFromAttribute("object_id");
		MetaModel::Init_AddFilterFromAttribute("object_name");
		MetaModel::Init_AddFilterFromAttribute("role");
		
		// Display lists
		MetaModel::Init_SetZListItems('details', array('contact_id', 'contact_phone', 'contact_email', 'object_id', 'role')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('contact_id', 'contact_phone', 'contact_email', 'object_id', 'role')); // Attributes to be displayed for a list
	}
}

////////////////////////////////////////////////////////////////////////////////////
/**
* Any Infrastructure object (bizLocation, bizDevice, bizApplication, bizCircuit, bizInterface)
* An infrastructure object:
*   can be covered by an OLA
*   can support the delivery of a Service
*   can be part of an GroupInfra
*/
////////////////////////////////////////////////////////////////////////////////////
class logInfra extends logRealObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"name" => "Infra",
			"description" => "Infrastructure real object",
			"key_type" => "",
			"key_label" => "id",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("org_name", "name"), // inherited attributes
			"db_table" => "infra",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/default.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
    MetaModel::Init_AddAttribute(new AttributeEnum("severity", array("label"=>"Severity", "description"=>"Severity for this infrastructure", "allowed_values"=>new ValueSetEnum("high,medium,low"), "sql"=>"severity", "default_value"=>"low", "is_null_allowed"=>false, "depends_on"=>array())));
  
		MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("severity");
	}
}

////////////////////////////////////////////////////////////////////////////////////
/**
* bizLocation (Region, Country, City, Site, Building, Floor, Room, Rack,...)
* pourrait être mis en plusieurs sous objects, puisqu'une adresse sur region n'a pas trop de sens
* 
*/
////////////////////////////////////////////////////////////////////////////////////
class bizLocation extends logInfra
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"name" => "Location",
			"description" => "Any type of location: Region, Country, City, Site, Building, Floor, Room, Rack,...",
			"key_type" => "",
			"key_label" => "id",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("org_name", "name"), // inherited attributes
			"db_table" => "location",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/location.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeText("address", array("label"=>"Address", "description"=>"The postal address of the location", "allowed_values"=>null, "sql"=>"address", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("country", array("label"=>"Country", "description"=>"Country of the location", "allowed_values"=>null, "sql"=>"country", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("parent_location_id", array("targetclass"=>"bizLocation", "label"=>"Parent Location", "description"=>"where is the real object physically located", "allowed_values"=>null, "sql"=>"parent_location_id", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("parent_location_name", array("label"=>"Parent location (Name)", "description"=>"name of the parent location", "allowed_values"=>null, "extkey_attcode"=> 'parent_location_id', "target_attcode"=>"name")));

    //  on veut pouvoir rechercher une location qui soit un descendant (pas obligatoirement direct) d'une Location, on fait comment ?

		MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("country");
		MetaModel::Init_AddFilterFromAttribute("address");
		MetaModel::Init_AddFilterFromAttribute("parent_location_id");
		MetaModel::Init_AddFilterFromAttribute("parent_location_name");
		
		// Display lists
		MetaModel::Init_SetZListItems('details', array('name', 'status', 'org_id', 'address', 'country', 'parent_location_id')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('name', 'status', 'org_id', 'country')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'country', 'parent_location_name')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status', 'address', 'country', 'parent_location_id', 'org_id')); // Criteria of the advanced search form
	}
	
	public function ComputeValues()
	{ 
  /*
		$this->Set("location_id", $this->GetKey());
		// Houston, I've got an issue, as this field is calculated, I should reload the object... ?
		$this->Set("location_name", "abc (to be finalized)");
  */
	}

	function DisplayDetails(web_page $oPage)
	{
		parent::DisplayDetails($oPage);
/*
		parent::DisplayDetails($oPage);



		$oSearchFilter = new CMDBSearchFilter('bizServer');
		$oSearchFilter->AddCondition('location_id', $this->GetKey(), '=');
		$oSet = new CMDBObjectSet($oSearchFilter);
		$count = $oSet->Count();
		if ($count > 0)
		{
			$oPage->SetCurrentTab("Servers");
			$oPage->p("$count server(s) at this location:");
			$this->DisplaySet($oPage, $oSet);
		}
		$oSearchFilter = new CMDBSearchFilter('bizNetworkDevice');
		$oSearchFilter->AddCondition('location_id', $this->GetKey(), '=');
		$oSet = new CMDBObjectSet($oSearchFilter);
		$count = $oSet->Count();
		if ($count > 0)
		{
			$oPage->SetCurrentTab("Network Devices");
			$oPage->p("$count Network Device(s) at this location:");
			$this->DisplaySet($oPage, $oSet);
		}
		$oSearchFilter = new CMDBSearchFilter('bizPC');
		$oSearchFilter->AddCondition('location_id', $this->GetKey(), '=');
		$oSet = new CMDBObjectSet($oSearchFilter);
		$count = $oSet->Count();
		if ($count > 0)
		{
			$oPage->SetCurrentTab("PCs");
			$oPage->p("$count PC(s) at this location:");
			$this->DisplaySet($oPage, $oSet);
		}
		$oSearchFilter = new CMDBSearchFilter('bizPerson');
		$oSearchFilter->AddCondition('location_id', $this->GetKey(), '=');
		$oSet = new CMDBObjectSet($oSearchFilter);
		$count = $oSet->Count();
		if ($count > 0)
		{
			$oPage->SetCurrentTab("Contacts");
			$oPage->p("$count person(s) located to this location:");
			$this->DisplaySet($oPage, $oSet);
		}

		$oSearchFilter = new CMDBSearchFilter('lnkDocumentRealObject');
		$oSearchFilter->AddCondition('object_id', $this->GetKey(), '=');
		$oSet = new CMDBObjectSet($oSearchFilter);
		$count = $oSet->Count();
		if ($count > 0)
		{
			$oPage->SetCurrentTab("Details");
			$oPage->p("$count Document(s) linked to this location:");
			$this->DisplaySet($oPage, $oSet);
		}
*/
	
	}


	public function Generate(cmdbDataGenerator $oGenerator)
	{
		parent::Generate($oGenerator);
		$sLastName = $oGenerator->GenerateLastName();
		$sCityName = $oGenerator->GenerateCityName();
		$this->Set('name', $sCityName);
		$this->Set('country', $oGenerator->GenerateCountryName());
		$this->Set('address', $oGenerator->GenerateString("number(1-999)| |enum(rue,rue,rue,place,avenue,av.,route de)| |$sLastName| |number(0000-9999)|0 |$sCityName"));
		$this->Set('parent_location_id', 1);
	}
}

////////////////////////////////////////////////////////////////////////////////////
/**
* Circuit (one end only)
*/
////////////////////////////////////////////////////////////////////////////////////
class bizCircuit extends logInfra
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"name" => "Circuit",
			"description" => "Any type of circuit",
			"key_type" => "",
			"key_label" => "id",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("org_name", "carrier_name", "carrier_ref", "name"), // inherited attributes
			"db_table" => "circuits",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/Circuits.html",
		);

		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("speed", array("label"=>"speed", "description"=>"speed of the circuit", "allowed_values"=>null, "sql"=>"speed", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("location_id", array("targetclass"=>"bizLocation", "label"=>"Location ID", "description"=>"Id of the location", "allowed_values"=>null, "sql"=>"location_id", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("location_name", array("label"=>"Location", "description"=>"Name of the location", "allowed_values"=>null, "extkey_attcode"=> 'location_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("interface_id", array("targetclass"=>"bizInterface", "label"=>"Interface Id", "description"=>"id of the interface", "allowed_values"=>null, "sql"=>"interface_id", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("interface_name", array("label"=>"Interface", "description"=>"Name of the interface", "allowed_values"=>null, "extkey_attcode"=> 'interface_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("provider_id", array("targetclass"=>"bizOrganization", "label"=>"Carrier ID", "description"=>"Organization ID of the provider of the Circuit", "allowed_values"=>null, "sql"=>"provider_id", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("carrier_name", array("label"=>"Carrier", "description"=>"Name of the carrier", "allowed_values"=>null, "extkey_attcode"=> 'provider_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeString("carrier_ref", array("label"=>"Carrier reference", "description"=>"reference of the circuit used by the carrier", "allowed_values"=>null, "sql"=>"carrier_ref", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("speed");
		MetaModel::Init_AddFilterFromAttribute("location_id");
		MetaModel::Init_AddFilterFromAttribute("location_name");
		MetaModel::Init_AddFilterFromAttribute("interface_id");
		MetaModel::Init_AddFilterFromAttribute("provider_id");
		MetaModel::Init_AddFilterFromAttribute("carrier_ref");
		
		// Display lists
		MetaModel::Init_SetZListItems('details', array('name', 'status', 'org_id', 'speed', 'provider_id', 'carrier_ref', 'location_id')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('name', 'status', 'org_id', 'provider_id', 'carrier_ref', 'speed')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'carrier_ref', 'speed', 'provider_id')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status', 'carrier_ref', 'speed', 'provider_id')); // Criteria of the advanced search form
	}
	
	public function ComputeValues()
	{
/*
		$oLocatedObject = MetaModel::GetObject("Located Object", $this->Get("located_object_id"));

		$this->Set("location_id", $oLocatedObject->Get("location_id"));
		// Houston, I've got an issue, as this field is calculated, I should reload the object...
		$this->Set("location_name", "abc (to be finalized)");

		$this->Set("device_id", $oLocatedObject->Get("device_id"));
		// Houston, I've got an issue, as this field is calculated, I should reload the object...
		$this->Set("device_name", "abc (to be finalized)");

		$this->Set("interface_id", $oLocatedObject->Get("interface_id"));
		// Houston, I've got an issue, as this field is calculated, I should reload the object...
		$this->Set("interface_name", "abc (to be finalized)");
*/
	}
}

////////////////////////////////////////////////////////////////////////////////////
/**
* Any Device Network Interface 
*/
////////////////////////////////////////////////////////////////////////////////////
class bizInterface extends logInfra
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"name" => "Interface",
			"description" => "Interface",
			"key_type" => "",
			"key_label" => "id",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("org_name", "device_name", "name"),
			"db_table" => "interfaces",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/interface.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeExternalKey("device_id", array("targetclass"=>"bizDevice", "label"=>"Device", "description"=>"Device on which the interface is physically located", "allowed_values"=>null, "sql"=>"device_id", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("device_name", array("label"=>"Device", "description"=>"name of the device on which the interface is located", "allowed_values"=>null, "extkey_attcode"=> 'device_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeExternalField("device_location_id", array("label"=>"Device location", "description"=>"location of the device on which the interface is located", "allowed_values"=>null, "extkey_attcode"=> 'device_id', "target_attcode"=>"location_id")));
		MetaModel::Init_AddAttribute(new AttributeExternalField("device_location_name", array("label"=>"Device location", "description"=>"name of the location of the device on which the interface is located", "allowed_values"=>null, "extkey_attcode"=> 'device_id', "target_attcode"=>"location_name")));

		MetaModel::Init_AddAttribute(new AttributeEnum("logical_type", array("label"=>"Logical type", "description"=>"Logical type of interface", "allowed_values"=>new ValueSetEnum("primary,secondary,backup,port,logical"), "sql"=>"logical_type", "default_value"=>"port", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("physical_type", array("label"=>"Physical type", "description"=>"Physical type of interface", "allowed_values"=>new ValueSetEnum("ethernet,framerelay,atm,vlan"), "sql"=>"physical_type", "default_value"=>"ethernet", "is_null_allowed"=>false, "depends_on"=>array())));
	  MetaModel::Init_AddAttribute(new AttributeString("ip_address", array("label"=>"IP address", "description"=>"address IP for this interface", "allowed_values"=>null, "sql"=>"ip_address", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
	  MetaModel::Init_AddAttribute(new AttributeString("mask", array("label"=>"Subnet Mask", "description"=>"Subnet mask for this interface", "allowed_values"=>null, "sql"=>"mask", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
	  MetaModel::Init_AddAttribute(new AttributeString("mac", array("label"=>"MAC address", "description"=>"MAC address for this interface", "allowed_values"=>null, "sql"=>"mac", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
	  MetaModel::Init_AddAttribute(new AttributeString("speed", array("label"=>"Speed (Kb/s)", "description"=>"speed of this interface", "allowed_values"=>null, "sql"=>"speed", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
	  MetaModel::Init_AddAttribute(new AttributeEnum("duplex", array("label"=>"Duplex", "description"=>"Duplex configured for this interface", "allowed_values"=>new ValueSetEnum("half,full,unknown"), "sql"=>"duplex", "default_value"=>"unknown", "is_null_allowed"=>true, "depends_on"=>array())));
	
		MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("device_id");
		MetaModel::Init_AddFilterFromAttribute("device_name");
		MetaModel::Init_AddFilterFromAttribute("device_location_id");
		MetaModel::Init_AddFilterFromAttribute("logical_type");
		MetaModel::Init_AddFilterFromAttribute("physical_type");
    MetaModel::Init_AddFilterFromAttribute("ip_address");
    MetaModel::Init_AddFilterFromAttribute("mac");		
		// Display lists
		MetaModel::Init_SetZListItems('details', array('name', 'status', 'org_id', 'device_id', 'device_location_id','severity','logical_type','physical_type','ip_address','mask','mac','speed','duplex')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('name', 'status', 'org_id', 'device_id','severity')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'ip_address','mac','device_id')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status', 'device_id', 'org_id')); // Criteria of the advanced search form
	}

	function DisplayDetails(web_page $oPage)
	{
		parent::DisplayDetails($oPage);
    /*
		$oSearchFilter = new CMDBSearchFilter('lnkInterfaces');
		$oSearchFilter->AddCondition('interface1_id', $this->GetKey(), '=');
		$oSet = new CMDBObjectSet($oSearchFilter);
		$count = $oSet->Count();
		if ($count > 0)
		{
			$oPage->SetCurrentTab("Connected interfaces");
			$oPage->p("$count interface(s) connected to this device:");
			$this->DisplaySet($oPage, $oSet);
		}
	*/
	}

	public function ComputeValues()
	{
	/*
		// my location is the location of my device
		$oDevice = MetaModel::GetObject("bizDevice", $this->Get("device_id"));
		$this->Set("location_id", $oDevice->Get("location_id"));
		// Houston, I've got an issue, as this field is calculated, I should reload the object...
		$this->Set("location_name", "abc (to be finalized)");

		// my device is given by my Creator

		// my interface is myself
		$this->Set("interface_id", $this->GetKey());
		// Houston, I've got an issue, as this field is calculated, I should reload the object...
		$this->Set("interface_name", "abc (to be finalized)");
	*/
  }
}

////////////////////////////////////////////////////////////////////////////////////
/**
* n-n link between any Interfaces
*/
////////////////////////////////////////////////////////////////////////////////////
class lnkInterfaces extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"name" => "InterfacesLinks",
			"description" => "A link between two interfaces",
			"key_type" => "autoincrement",
			"key_label" => "link_id",
			"name_attcode" => "link_type",
			"state_attcode" => "",
			"reconc_keys" => array("interface1_id", "interface2_id"),
			"db_table" => "interfaces_links",
			"db_key_field" => "link_id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/default.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_AddAttribute(new AttributeExternalKey("interface1_id", array("targetclass"=>"bizInterface", "label"=>"Interface1", "description"=>"The interface1", "sql"=>"interface1_id", "allowed_values"=> null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("interface1_name", array("label"=>"Interface1 name", "description"=>"name of the interface1", "extkey_attcode"=> 'interface1_id', "allowed_values"=> null, "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("interface2_id", array("targetclass"=>"bizInterface", "label"=>"Interface2", "description"=>"The interface2", "sql"=>"interface2_id", "allowed_values"=> null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("interface2_name", array("label"=>"Interface2 name", "description"=>"name of the interface2", "extkey_attcode"=> 'interface2_id', "allowed_values"=> null, "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeExternalField("interface1_device_id", array("label"=>"Device1", "description"=>"device", "extkey_attcode"=> 'interface1_id', "allowed_values"=> null, "target_attcode"=>"device_id")));
		MetaModel::Init_AddAttribute(new AttributeExternalField("interface1_device_name", array("label"=>"Device name", "description"=>"name of the device", "extkey_attcode"=> 'interface1_id', "allowed_values"=> null, "target_attcode"=>"device_name")));
		MetaModel::Init_AddAttribute(new AttributeExternalField("interface2_device_id", array("label"=>"Device2", "description"=>"device", "extkey_attcode"=> 'interface2_id', "allowed_values"=> null, "target_attcode"=>"device_id")));
		MetaModel::Init_AddAttribute(new AttributeExternalField("interface2_device_name", array("label"=>"Device name", "description"=>"name of the device", "extkey_attcode"=> 'interface2_id', "allowed_values"=> null, "target_attcode"=>"device_name")));
		MetaModel::Init_AddAttribute(new AttributeString("link_type", array("label"=>"link type", "description"=>" Definition of the link", "sql"=>"link_type", "default_value"=>"", "allowed_values"=> null, "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_AddFilterFromAttribute("interface1_id");
		MetaModel::Init_AddFilterFromAttribute("interface1_name");
		MetaModel::Init_AddFilterFromAttribute("interface2_id");
		MetaModel::Init_AddFilterFromAttribute("interface2_name");
		MetaModel::Init_AddFilterFromAttribute("interface2_device_name");
		MetaModel::Init_AddFilterFromAttribute("link_type");
		
		// Display lists
		MetaModel::Init_SetZListItems('details', array('interface1_id', 'interface2_id', 'link_type')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('interface1_id', 'interface1_device_id', 'interface2_id', 'interface2_device_id', 'link_type')); // Attributes to be displayed for the complete details
	}
}

////////////////////////////////////////////////////////////////////////////////////
/**
* Any electronic device
*/
////////////////////////////////////////////////////////////////////////////////////
class bizDevice extends logInfra
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"name" => "Device",
			"description" => "Electronic devices",
			"key_type" => "",
			"key_label" => "id",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("org_name", "name"), // inherited attributes
			"db_table" => "devices",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/default.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeExternalKey("location_id", array("targetclass"=>"bizLocation", "label"=>"Location", "description"=>"where is the located object physically located", "allowed_values"=>null, "sql"=>"location_id", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("location_name", array("label"=>"Location name", "description"=>"name of the location", "allowed_values"=>null, "extkey_attcode"=> 'location_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeExternalField("country", array("label"=>"Country", "description"=>"country where the device is located", "allowed_values"=>null, "extkey_attcode"=> 'location_id', "target_attcode"=>"country")));
		MetaModel::Init_AddAttribute(new AttributeString("brand", array("label"=>"Brand", "description"=>"The manufacturer of the device", "allowed_values"=>null, "sql"=>"brand", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("model", array("label"=>"Model", "description"=>"The model number of the device", "allowed_values"=>null, "sql"=>"model", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("serial_number", array("label"=>"Serial Number", "description"=>"The serial number of the device", "allowed_values"=>null, "sql"=>"serial_number", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("location_id");
		MetaModel::Init_AddFilterFromAttribute("country");
		MetaModel::Init_AddFilterFromAttribute("brand");
		MetaModel::Init_AddFilterFromAttribute("model");
		MetaModel::Init_AddFilterFromAttribute("serial_number");
	}

	public static function GetRelationQueries($sRelCode)
	{
		switch ($sRelCode)
		{
		case "impacts":
			$aRels = array(
				"connected device" => array("sQuery"=>"bizDevice: PKEY IS device_id IN (bizInterface: PKEY IS interface2_id IN (lnkInterfaces: interface1_id IN (bizInterface: device_id = \$[this.pkey::])))", "bPropagate"=>true, "iDistance"=>3),
				"hosted app" => array("sQuery"=>"bizApplication: infra_id = \$[this.pkey::]", "bPropagate"=>true, "iDistance"=>3),
			);
			return array_merge($aRels, parent::GetRelationQueries($sRelCode));
		}
	}

	public function ComputeValues()
	{
	/*
		// my location is the location of my device (external field)
		$this->Set("location_id", $this->Get("device_location_id"));
		// Houston, I've got an issue, as this field is calculated, I should reload the object...
		$this->Set("location_name", "abc (to be finalized)");

		// my device is myself
		$this->Set("device_id", $this->GetKey());

		// my interface is "nothing"
		$this->Set("interface_id", null);
	*/
  }
}

////////////////////////////////////////////////////////////////////////////////////
/**
* A personal computer
*/
////////////////////////////////////////////////////////////////////////////////////
class bizPC extends bizDevice
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"name" => "PC",
			"description" => "Personal Computers",
			"key_type" => "",
			"key_label" => "id",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("org_name", "name"), // inherited attributes
			"db_table" => "pcs",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/pc.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeEnum("type", array("label"=>"Type", "description"=>"Type of computer", "allowed_values"=>new ValueSetEnum("desktop PC,laptop"), "sql"=>"type", "default_value"=>"desktop PC", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("memory_size", array("label"=>"Memory Size", "description"=>"Size of the memory", "allowed_values"=>null, "sql"=>"memory_size", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("cpu", array("label"=>"CPU", "description"=>"CPU type", "allowed_values"=>null, "sql"=>"cpu_type", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("hdd_size", array("label"=>"HDD Size", "description"=>"Size of the hard drive", "allowed_values"=>null, "sql"=>"hdd_size", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("os_family", array("label"=>"OS Family", "description"=>"Type of operating system", "allowed_values"=>null, "sql"=>"os_family", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("os_version", array("label"=>"OS Version", "description"=>"Detailed version number of the operating system", "allowed_values"=>null, "sql"=>"os_version", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("shipment_number", array("label"=>"Shipment number", "description"=>"Number for tracking shipment", "allowed_values"=>null, "sql"=>"shipment_number", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("mgmt_ip", array("label"=>"Mgmt IP", "description"=>"Management IP", "allowed_values"=>null, "sql"=>"mgmt_ip", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("default_gateway", array("label"=>"Default Gateway", "description"=>"Default Gateway for this device", "allowed_values"=>null, "sql"=>"default_gateway", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("type");
		MetaModel::Init_AddFilterFromAttribute("memory_size");
		MetaModel::Init_AddFilterFromAttribute("cpu");
		MetaModel::Init_AddFilterFromAttribute("hdd_size");
		MetaModel::Init_AddFilterFromAttribute("os_family");
		MetaModel::Init_AddFilterFromAttribute("os_version");
		MetaModel::Init_AddFilterFromAttribute("mgmt_ip");
		
		// Display lists
		MetaModel::Init_SetZListItems('details', array('name', 'status','severity', 'org_id', 'location_id', 'brand', 'model','os_family','os_version','mgmt_ip','default_gateway','shipment_number','serial_number', 'type', 'cpu', 'memory_size', 'hdd_size')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('name', 'status', 'severity', 'org_id', 'location_id', 'brand', 'model', 'type')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'severity','type', 'brand', 'model','os_family','mgmt_ip')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status', 'type', 'brand', 'model', 'cpu', 'memory_size', 'hdd_size')); // Criteria of the advanced search form
	}

	function DisplayDetails(web_page $oPage)
	{
		parent::DisplayDetails($oPage);
		/*
		parent::DisplayDetails($oPage);
		$oSearchFilter = new CMDBSearchFilter('lnkContactRealObject');
		$oSearchFilter->AddCondition('object_id', $this->GetKey(), '=');
		$oSet = new CMDBObjectSet($oSearchFilter);
		$count = $oSet->Count();
		if ($count > 0)
		{
			$oPage->SetCurrentTab("Contacts");
			$oPage->p("$count contact(s) linked to this PC:");
			$this->DisplaySet($oPage, $oSet);
		}
		$oSearchFilter = new CMDBSearchFilter('bizInterface');
		$oSearchFilter->AddCondition('device_id', $this->GetKey(), '=');
		$oSet = new CMDBObjectSet($oSearchFilter);
		$count = $oSet->Count();
		if ($count > 0)
		{
			$oPage->SetCurrentTab("Interfaces");
			$oPage->p("$count interface(s) for this device:");
			$this->DisplaySet($oPage, $oSet);
		}
		*/
	}

	
	public function Generate(cmdbDataGenerator $oGenerator)
	{
		$this->Set('org_id', $oGenerator->GetOrganizationId());
		$this->Set('location_id', $oGenerator->GenerateKey("bizLocation", array('org_id' =>$oGenerator->GetOrganizationId() )));
		$this->Set('name', $oGenerator->GenerateString("enum(pc,pc,pc,pc,pc,win,redhat,linux,srv,workstation)|number(000-999)|.|domain()"));
		$this->Set('brand', $oGenerator->GenerateString("enum(Hewlett-Packard,Dell,Compaq,Siemens,Packard Bell,IBM,Gateway,Medion,Sony)"));
		$this->Set('model', $oGenerator->GenerateString("enum(Vectra,Deskpro,Dimension,Optiplex,Latitude,Precision,Vaio)"));
		$this->Set('serial_number', $oGenerator->GenerateString("enum(FR,US,TW,CH)|number(000000-999999)"));
		$this->Set('memory_size', $oGenerator->GenerateString("enum(128,256,384,512,768,1024,1536,2048)"));
		$this->Set('cpu', $oGenerator->GenerateString("enum(Pentium III,Pentium 4, Pentium M,Core Duo,Core 2 Duo,Celeron,Opteron,Thurion,Athlon)"));
		$this->Set('hdd_size', $oGenerator->GenerateString("enum(40,60,80,120,200,300)"));
	}
}

////////////////////////////////////////////////////////////////////////////////////
/**
* A server
*/
////////////////////////////////////////////////////////////////////////////////////
class bizServer extends bizDevice
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"name" => "Server",
			"description" => "Computer Servers",
			"key_type" => "",
			"key_label" => "id",
			"name_attcode" => "name",
			"state_attcode" => "status",
			"reconc_keys" => array("org_name", "name"), // inherited attributes
			"db_table" => "servers",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/server.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeEnum("status", array("label"=>"Status", "description"=>"Status of the server", "allowed_values"=>new ValueSetEnum("In Store,Shipped,Plugged,Production Candidate,In Production,In Change,Being Deconfigured,Obsolete"), "sql"=>"status", "default_value"=>"In Store", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("memory_size", array("label"=>"Memory Size", "description"=>"Size of the memory", "allowed_values"=>null, "sql"=>"memory_size", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("cpu", array("label"=>"Model", "description"=>"CPU type", "allowed_values"=>null, "sql"=>"cpu_type", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("number_of_cpus", array("label"=>"Number of CPUs", "description"=>"Number of CPUs", "allowed_values"=>null, "sql"=>"number_of_cpus", "default_value"=>"1", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("hdd_size", array("label"=>"HDD Size", "description"=>"Size of the hard drive", "allowed_values"=>null, "sql"=>"hdd_size", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("hdd_free_size", array("label"=>"Free HDD Size", "description"=>"Size of the free space on the hard drive(s)", "allowed_values"=>null, "sql"=>"hdd_free_size", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("os_family", array("label"=>"OS Family", "description"=>"Type of operating system", "allowed_values"=>null, "sql"=>"os_family", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("os_version", array("label"=>"OS Version", "description"=>"Detailed version number of the operating system", "allowed_values"=>null, "sql"=>"os_version", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("shipment_number", array("label"=>"Shipment number", "description"=>"Number for tracking shipment", "allowed_values"=>null, "sql"=>"shipment_number", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("mgmt_ip", array("label"=>"Mgmt IP", "description"=>"Management IP", "allowed_values"=>null, "sql"=>"mgmt_ip", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("default_gateway", array("label"=>"Default Gateway", "description"=>"Default Gateway for this device", "allowed_values"=>null, "sql"=>"default_gateway", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("memory_size");
		MetaModel::Init_AddFilterFromAttribute("cpu");
		MetaModel::Init_AddFilterFromAttribute("number_of_cpus");
		MetaModel::Init_AddFilterFromAttribute("hdd_size");
		MetaModel::Init_AddFilterFromAttribute("hdd_free_size");
		MetaModel::Init_AddFilterFromAttribute("os_family");
		MetaModel::Init_AddFilterFromAttribute("os_version");
	
 
		// Life cycle
		MetaModel::Init_DefineState("In Store", array("label"=>"InStore", "description"=>"Device in store", "attribute_inherit"=>null,
												 "attribute_list"=>array()));
		MetaModel::Init_DefineState("Shipped", array("label"=>"Shipped", "description"=>"The device had been shipped to future location", "attribute_inherit"=>null,
												"attribute_list"=>array("location_id"=>OPT_ATT_MANDATORY,"serial_number"=>OPT_ATT_MANDATORY,"shipment_number"=>OPT_ATT_MANDATORY)));
		MetaModel::Init_DefineState("Plugged", array("label"=>"Plugged", "description"=>"The device is connected to the network", "attribute_inherit"=>null,
													"attribute_list"=>array("location_id"=>OPT_ATT_MANDATORY,"mgmt_ip"=>OPT_ATT_MANDATORY,"name"=>OPT_ATT_MANDATORY)));
		MetaModel::Init_DefineState("Production Candidate", array("label"=>"Pre-Production", "description"=>"The device is ready to be move to production", "attribute_inherit"=>null,
												"attribute_list"=>array()));
		MetaModel::Init_DefineState("In Production", array("label"=>"Production", "description"=>"The device is on production", "attribute_inherit"=>null,
												"attribute_list"=>array()));
		MetaModel::Init_DefineState("In Change", array("label"=>"InChange", "description"=>"A change is being performed on the device", "attribute_inherit"=>null,
												"attribute_list"=>array()));
		MetaModel::Init_DefineState("Being Deconfigured", array("label"=>"BeingDeconfigured", "description"=>"The device is about to be removed from is current location", "attribute_inherit"=>null,
												"attribute_list"=>array()));
		MetaModel::Init_DefineState("Obsolete", array("label"=>"Obsolete", "description"=>"The device is no more used", "attribute_inherit"=>null,
												"attribute_list"=>array()));


		MetaModel::Init_DefineStimulus("ev_store", new StimulusUserAction(array("label"=>"Store this server", "description"=>"This server is move to storage")));
		MetaModel::Init_DefineStimulus("ev_ship", new StimulusUserAction(array("label"=>"Ship this server", "description"=>"This server is shipped to futur location")));
		MetaModel::Init_DefineStimulus("ev_plug", new StimulusUserAction(array("label"=>"Plug this server", "description"=>"The server is pluuged on the network")));
		MetaModel::Init_DefineStimulus("ev_configuration_finished", new StimulusUserAction(array("label"=>"Configuration finished", "description"=>"The device is ready to move to production evaluation")));
		MetaModel::Init_DefineStimulus("ev_val_failed", new StimulusUserAction(array("label"=>"Review configuration", "description"=>"The configuration for this server is not completed")));
		MetaModel::Init_DefineStimulus("ev_mtp", new StimulusUserAction(array("label"=>"Move to Production", "description"=>"The server is moved to production")));
		MetaModel::Init_DefineStimulus("ev_start_change", new StimulusUserAction(array("label"=>"Change Start [No Click]", "description"=>"A change starts for this server")));
		MetaModel::Init_DefineStimulus("ev_end_change", new StimulusUserAction(array("label"=>"End Change [No Click]", "description"=>"No more change running for this server")));
		MetaModel::Init_DefineStimulus("ev_decommission", new StimulusUserAction(array("label"=>"Decommission", "description"=>"The server is being decommissioned")));
		MetaModel::Init_DefineStimulus("ev_obsolete", new StimulusUserAction(array("label"=>"Obsolete", "description"=>"The server is no more used")));
		MetaModel::Init_DefineStimulus("ev_recycle", new StimulusUserAction(array("label"=>"Recycle this server", "description"=>"The server is move back to deconfiguration")));

		MetaModel::Init_DefineTransition("In Store", "ev_ship", array("target_state"=>"Shipped", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("In Store", "ev_plug", array("target_state"=>"Plugged", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Shipped", "ev_store", array("target_state"=>"In Store", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Shipped", "ev_plug", array("target_state"=>"Plugged", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Plugged", "ev_ship", array("target_state"=>"Shipped", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Plugged", "ev_store", array("target_state"=>"In Store", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Plugged", "ev_configuration_finished", array("target_state"=>"Production Candidate", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Production Candidate", "ev_val_failed", array("target_state"=>"Plugged", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Production Candidate", "ev_mtp", array("target_state"=>"In Production", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("In Production", "ev_start_change", array("target_state"=>"In Change", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("In Production", "ev_obsolete", array("target_state"=>"Obsolete", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("In Production", "ev_decommission", array("target_state"=>"Being Deconfigured", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("In Change", "ev_end_change", array("target_state"=>"In Production", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Being Deconfigured", "ev_ship", array("target_state"=>"Shipped", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Being Deconfigured", "ev_plug", array("target_state"=>"Plugged", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Being Deconfigured", "ev_store", array("target_state"=>"In Store", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Being Deconfigured", "ev_obsolete", array("target_state"=>"Obsolete", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Obsolete", "ev_recycle", array("target_state"=>"Being Deconfigured", "actions"=>array(), "user_restriction"=>null));
		


		
	
		// Display lists

  		MetaModel::Init_SetZListItems('details', array('name', 'mgmt_ip','default_gateway','status', 'severity','org_id', 'location_id', 'brand', 'model', 'os_family', 'os_version','serial_number','shipment_number', 'cpu', 'number_of_cpus', 'memory_size', 'hdd_size', 'hdd_free_size')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('name', 'status','severity', 'org_id', 'location_id', 'brand', 'model', 'os_family', 'os_version')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status','severity', 'brand', 'model', 'os_family', 'location_id')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status','brand', 'model', 'os_family', 'os_version', 'location_id', 'cpu', 'number_of_cpus', 'memory_size', 'hdd_size', 'hdd_free_size')); // Criteria of the advanced search form
	}
	
	function DisplayDetails(web_page $oPage)
	{
		parent::DisplayDetails($oPage);
		/*
		parent::DisplayDetails($oPage);
		$oSearchFilter = new CMDBSearchFilter('lnkContactRealObject');
		$oSearchFilter->AddCondition('object_id', $this->GetKey(), '=');
		$oSet = new CMDBObjectSet($oSearchFilter);
		$count = $oSet->Count();
		if ($count > 0)
		{
			$oPage->SetCurrentTab("Contacts");
			$oPage->p("$count contact(s) for this server:");
			$this->DisplaySet($oPage, $oSet);
		}
		$oSearchFilter = new CMDBSearchFilter('bizInterface');
		$oSearchFilter->AddCondition('device_id', $this->GetKey(), '=');
		$oSet = new CMDBObjectSet($oSearchFilter);
		$count = $oSet->Count();
		if ($count > 0)
		{
			$oPage->SetCurrentTab("Interfaces");
			$oPage->p("$count interface(s) for this server:");
			$this->DisplaySet($oPage, $oSet);
		}
		$oSearchFilter = new CMDBSearchFilter('Application');
		$oSearchFilter->AddCondition('infra_id', $this->GetKey(), '=');
		$oSet = new CMDBObjectSet($oSearchFilter);
		$count = $oSet->Count();
		if ($count > 0)
		{
			$oPage->SetCurrentTab("Installed applications");
			$oPage->p("$count application(s) installed on this server:");
			$this->DisplaySet($oPage, $oSet);
		}
		$oSearchFilter = new CMDBSearchFilter('bizPatch');
		$oSearchFilter->AddCondition('infra_id', $this->GetKey(), '=');
		$oSet = new CMDBObjectSet($oSearchFilter);
		$count = $oSet->Count();
		if ($count > 0)
		{
			$oPage->SetCurrentTab("Installed patches");
			$oPage->p("$count patch(s) installed on this server:");
			$this->DisplaySet($oPage, $oSet);
		}
		*/


	}



	public function Generate(cmdbDataGenerator $oGenerator)
	{
		$this->Set('org_id', $oGenerator->GetOrganizationId());
		$this->Set('location_id', $oGenerator->GenerateKey("bizLocation", array('org_id' =>$oGenerator->GetOrganizationId() )));
		$this->Set('name', $oGenerator->GenerateString("enum(pc,pc,pc,pc,pc,win,redhat,linux,srv,workstation)|number(000-999)|.|domain()"));
		$this->Set('brand', $oGenerator->GenerateString("enum(Hewlett-Packard,Dell,Compaq,Siemens,Packard Bell,IBM,Gateway,Medion,Sony)"));
		$this->Set('model', $oGenerator->GenerateString("enum(Vectra,Deskpro,Dimension,Optiplex,Latitude,Precision,Vaio)"));
		$this->Set('serial_number', $oGenerator->GenerateString("enum(FR,US,TW,CH)|number(000000-999999)"));
		$this->Set('memory_size', $oGenerator->GenerateString("enum(512,1024,2048,4096,2048,4096,8192,8192,8192,16384,32768)"));
		$this->Set('cpu', $oGenerator->GenerateString("enum(Pentium III,Pentium 4,Pentium M,Core Duo,Core 2 Duo,Celeron,Opteron,Thurion,Athlon)"));
		$this->Set('number_of_cpu', $oGenerator->GenerateString("enum(1,1,2,2,2,2,2,4,4,8)"));
		$this->Set('hdd_size', $oGenerator->GenerateString("enum(500,1024,500,1024,500,1024,2048)"));
		$this->Set('hdd_free_size', $this->Get('hdd_size')*$oGenerator->GenerateString("number(20-80)"));
		$this->Set('os_family', $oGenerator->GenerateString("enum(Windows,Windows,Windows,Linux,Windows,Linux,Windows,Linux,Linux,HP-UX,Solaris,AIX)"));
		$this->Set('os_version', $oGenerator->GenerateString("enum(XP,XP,XP,RH EL 4,RH EL 5,SuSE 10.3,SuSE 10.4,11.11,11.11i)"));
	}
}

////////////////////////////////////////////////////////////////////////////////////
/**
* A network device
*/
////////////////////////////////////////////////////////////////////////////////////
class bizNetworkDevice extends bizDevice
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"name" => "Network Device",
			"description" => "A network device",
			"key_type" => "",
			"key_label" => "id",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("org_name", "name"), // inherited attributes
			"db_table" => "network_devices",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/network.device.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeEnum("type", array("label"=>"Type", "description"=>"Type of device", "allowed_values"=>new ValueSetEnum("switch,router,firewall,load balancer,hub,WAN accelerator"), "sql"=>"type", "default_value"=>"switch", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("ip_address", array("label"=>"Mgmt IP", "description"=>"Management IP address", "allowed_values"=>null, "sql"=>"ip_address", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("default_gateway", array("label"=>"Default Gateway", "description"=>"Default Gateway for this device", "allowed_values"=>null, "sql"=>"default_gateway", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("ios_version", array("label"=>"IOS version", "description"=>"IOS (software) version", "allowed_values"=>null, "sql"=>"ios_version", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("memory", array("label"=>"Memory", "description"=>"Memory description", "allowed_values"=>null, "sql"=>"memory", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeString("snmp_read", array("label"=>"SNMP Community (Read)", "description"=>"SNMP Read Community String", "allowed_values"=>null, "sql"=>"snmp_read", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("snmp_write", array("label"=>"SNMP Community (Write)", "description"=>"SNMP Write Community String", "allowed_values"=>null, "sql"=>"snmp_write", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("type");
		MetaModel::Init_AddFilterFromAttribute("ip_address");
		MetaModel::Init_AddFilterFromAttribute("ios_version");

		// Display lists
		MetaModel::Init_SetZListItems('details', array('name', 'status','severity','org_id', 'location_id', 'brand','model','type','ip_address','default_gateway','serial_number','ios_version','memory','snmp_read','snmp_write')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('name', 'status','brand','model','type','ip_address')); // Attributes to be displayed for a list
		
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'location_id', 'brand','model','type','ip_address')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status', 'org_id', 'location_id', 'brand','model','type','ip_address','serial_number','ios_version','snmp_read','snmp_write')); // Criteria of the advanced search form


	}

	public function Generate(cmdbDataGenerator $oGenerator)
	{
		$this->Set('org_id', $oGenerator->GetOrganizationId());
		$this->Set('location_id', $oGenerator->GenerateKey("bizLocation", array('org_id' =>$oGenerator->GetOrganizationId() )));
		$this->Set('name', $oGenerator->GenerateString("enum(sw,swi,switch,rout,rtr,gw)|number(000-999)|.|domain()"));
		$this->Set('brand', $oGenerator->GenerateString("enum(Hewlett-Packard,Cisco,3Com,Avaya,Alcatel,Cabletron,Extrem Networks,Juniper,Netgear,Synopitcs,Xylan)"));
		$this->Set('model', $oGenerator->GenerateString("enum(Procurve ,Catalyst ,Multiswitch ,C)|enum(25,26,36,40,65)|enum(00,09,10,50)"));
		$this->Set('serial_number', $oGenerator->GenerateString("enum(FAA,AGA,PAD,COB,DFE)|number(0000-9999)|enum(M,X,L)"));
		$this->Set('ip_address', $oGenerator->GenerateString("number(10-248)|.|number(1-254)|.|number(1-254)|.|number(1-254)"));
		$this->Set('ios_version', $oGenerator->GenerateString("enum(9,10,12)|.|enum(0,1,2)|enum(,,,,XP,.5.1)"));
		$this->Set('snmp_read', $oGenerator->GenerateString("enum(Ew,+0,**,Ps)|number(00-99)|enum(+,=,],;, )|enum(Aze,Vbn,Bbn,+9+,-9-,#)"));
		$this->Set('snmp_write', $oGenerator->GenerateString("enum(M3,l3,$,*,Zz,Ks,jh)|number(00-99)|enum(A*e,V%n,Bbn,+,-,#)|number(0-9)"));
	}
}

////////////////////////////////////////////////////////////////////////////////////
/**
* A "Solution"
*/
////////////////////////////////////////////////////////////////////////////////////
class bizInfraGroup extends logInfra
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"name" => "Infra Group",
			"description" => "A group of infrastructure elements",
			"key_type" => "",
			"key_label" => "id",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("org_name", "name"), // inherited attributes
			"db_table" => "infra_group",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/group.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeEnum("type", array("label"=>"Type", "description"=>"Type of groupe", "allowed_values"=>new ValueSetEnum("Monitoring,Reporting,list"), "sql"=>"type", "default_value"=>"list", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("description", array("label"=>"Description", "description"=>"usage of the Group", "allowed_values"=>null, "sql"=>"description", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("parent_group_id", array("targetclass"=>"bizInfraGroup", "label"=>"Parent Group", "description"=>"including group", "allowed_values"=>null, "sql"=>"parent_group_id", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("parent_group_name", array("label"=>"Parent Group (Name)", "description"=>"name of the parent group", "allowed_values"=>null, "extkey_attcode"=> 'parent_group_id', "target_attcode"=>"name")));


		MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("type");
		MetaModel::Init_AddFilterFromAttribute("parent_group_id");
		MetaModel::Init_AddFilterFromAttribute("parent_group_name");


		// Display lists
		MetaModel::Init_SetZListItems('details', array('name', 'status', 'org_id', 'type', 'description','parent_group_id')); // Attributes to be displayed for a list
		MetaModel::Init_SetZListItems('list', array('name', 'status', 'org_id', 'type', 'description')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'type')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status', 'type', 'description', 'org_id')); // Criteria of the advanced search form
	}

	function DisplayDetails(web_page $oPage)
	{
		parent::DisplayDetails($oPage);
	/*
  	$oSearchFilter = new CMDBSearchFilter('lnkInfraGrouping');
		$oSearchFilter->AddCondition('infra_group_id', $this->GetKey(), '=');
		$oSet = new CMDBObjectSet($oSearchFilter);
		$count = $oSet->Count();
		if ($count > 0)
		{
   			$oPage->SetCurrentTab("RelatedInfrastructure");
			$oPage->p("Infrastructure Link to this group:");
			$this->DisplaySet($oPage, $oSet);
		}
		$oSearchFilter = new CMDBSearchFilter('lnkContactRealObject');
		$oSearchFilter->AddCondition('object_id', $this->GetKey(), '=');
		$oSet = new CMDBObjectSet($oSearchFilter);
		$count = $oSet->Count();
		if ($count > 0)
		{
   			$oPage->SetCurrentTab("TeamLinks");
			$oPage->p("People concerned by this group:");
			$this->DisplaySet($oPage, $oSet);
		}
*/
	}



	
	public function Generate(cmdbDataGenerator $oGenerator)
	{
		$this->Set('org_id', $oGenerator->GetOrganizationId());
		$this->Set('name', $oGenerator->GenerateString("enum(ov_nnm_,ovpi_,vitalnet_,datacenter_,web_farm_)|number(000-999)"));
		$this->Set('type', $oGenerator->GenerateString("enum(Application,Infrastructure)"));
	}	
}
////////////////////////////////////////////////////////////////////////////////////
//**
//* An application is an instance of a software install on a PC or Server
//* 
////////////////////////////////////////////////////////////////////////////////////
class bizApplication extends logInfra
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"name" => "Application",
			"description" => "General application",
			"key_type" => "",
			"key_label" => "id",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("device_name", "name"), // inherited attributes
			"db_table" => "applications",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/application.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeExternalKey("device_id", array("targetclass"=>"bizDevice", "jointype"=> '', "label"=>"Hosting device", "description"=>"The device where application is installed", "allowed_values"=>null, "sql"=>"device_id", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("device_name", array("label"=>"Hosting device", "description"=>"Name of the device where application is installed", "allowed_values"=>null, "extkey_attcode"=> 'device_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeDate("install_date", array("label"=>"Installed date", "description"=>"Date when application was installed", "allowed_values"=>null, "sql"=>"install_date", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeString("version", array("label"=>"Version", "description"=>"Application version", "allowed_values"=>null, "sql"=>"version", "default_value"=>"undefined", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("function", array("label"=>"Function", "description"=>"Function provided by this application", "allowed_values"=>null, "sql"=>"function", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));


		MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("function");
		MetaModel::Init_AddFilterFromAttribute("version");
		MetaModel::Init_AddFilterFromAttribute("device_id");

		
		
		
		// Display lists
		MetaModel::Init_SetZListItems('details', array('name','device_id','org_id','status','install_date', 'version','function')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('name','device_id', 'version', 'function')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('name', 'device_id','version','function')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'device_id','version','function')); // Criteria of the advanced search form

	}

	public static function GetRelationQueries($sRelCode)
	{
		switch ($sRelCode)
		{
		case "impacts":
			$aRels = array(
				"client app" => array("sQuery"=>"bizApplication: PKEY IS client_id IN (lnkClientServer: server_id = \$[this.pkey::])", "bPropagate"=>true, "iDistance"=>3),
			);
			return array_merge($aRels, parent::GetRelationQueries($sRelCode));
		}
	}

	function DisplayDetails(web_page $oPage)
	{
		parent::DisplayDetails($oPage);
	/*
  	$oSearchFilter = new CMDBSearchFilter('lnkClientServer');
		$oSearchFilter->AddCondition('server_id', $this->GetKey(), '=');
		$oSet = new CMDBObjectSet($oSearchFilter);
		$count = $oSet->Count();
		if ($count > 0)
		{
   			$oPage->SetCurrentTab("Connected clients");
			$oPage->p("Client applications impacted when down:");
			$this->DisplaySet($oPage, $oSet);
		}
*/
	}

}

////////////////////////////////////////////////////////////////////////////////////
/**
* n-n link between any Infra and a Group
*/
////////////////////////////////////////////////////////////////////////////////////
class lnkInfraGrouping extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"name" => "Infra Grouping",
			"description" => "Infra part of an Infra Group",
			"key_type" => "autoincrement",
			"key_label" => "link_id",
			"name_attcode" => "impact", 
			"state_attcode" => "",
			"reconc_keys" => array(""),
			"db_table" => "infra_grouping",
			"db_key_field" => "link_id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/default.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_AddAttribute(new AttributeExternalKey("infra_id", array("targetclass"=>"logInfra", "jointype"=> '', "label"=>"Infrastructure", "description"=>"Infrastructure part of the group", "allowed_values"=>null, "sql"=>"infra_id", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("infra_name", array("label"=>"Infrastructure name", "description"=>"Name of the impacted infrastructure", "allowed_values"=>null, "extkey_attcode"=> 'infra_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeExternalField("infra_status", array("label"=>"Status", "description"=>"Status of the impacted infrastructure", "allowed_values"=>null, "extkey_attcode"=> 'infra_id', "target_attcode"=>"status")));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("infra_group_id", array("targetclass"=>"bizInfraGroup", "jointype"=> '', "label"=>"Group Name", "description"=>"Name of the group", "allowed_values"=>null, "sql"=>"infra_group_id", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("group_name", array("label"=>"Group name", "description"=>"Name of the group containing infrastructure", "allowed_values"=>null, "extkey_attcode"=> 'infra_group_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeString("impact", array("label"=>"Relation", "description"=>"Relation between this group and infra", "allowed_values"=>null, "sql"=>"impact", "default_value"=>"none", "is_null_allowed"=>true, "depends_on"=>array())));
    // impact should modelized: enum (eg: if the group si dead when infra is dead)

		MetaModel::Init_AddFilterFromAttribute("infra_id");
		MetaModel::Init_AddFilterFromAttribute("infra_group_id");
		MetaModel::Init_AddFilterFromAttribute("impact");
		
		// Display lists
		MetaModel::Init_SetZListItems('details', array('infra_id','infra_status', 'impact', 'infra_group_id')); // Attributes to be displayed for a list
		MetaModel::Init_SetZListItems('list', array('infra_id','infra_status', 'impact', 'infra_group_id')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('infra_id', 'infra_group_id', 'impact')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('infra_id', 'infra_group_id', 'impact')); // Criteria of the advanced search form
	}
	
	public function Generate(cmdbDataGenerator $oGenerator)
	{
		$this->Set('infra_id', $oGenerator->GenerateKey("logInfra", array('org_id' =>$oGenerator->GetOrganizationId() )));
		$this->Set('infra_group_id', $oGenerator->GenerateKey("bizInfraGroup", array('org_id' =>$oGenerator->GetOrganizationId() )));
		$this->Set('impact', $oGenerator->GenerateString("enum(none,mandatory,partial)"));
	}

}






////////////////////////////////////////////////////////////////////////////////////
/**
* n-n link between two applications, one is the server side and the scond one the client*/
////////////////////////////////////////////////////////////////////////////////////
class lnkClientServer extends logRealObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"name" => "ClientServerLinks",
			"description" => "Link between client server application",
			"key_type" => "autoincrement",
			"key_label" => "link_id",
			"name_attcode" => "relation",  // ????
			"state_attcode" => "",
			"reconc_keys" => array("relation"),  // ????
			"db_table" => "clientserver_links",
			"db_key_field" => "link_id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/default.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_AddAttribute(new AttributeExternalKey("client_id", array("targetclass"=>"bizApplication", "jointype"=> '', "label"=>"Client", "description"=>"The client part of the link", "allowed_values"=>null, "sql"=>"client_id", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("client_name", array("label"=>"Client", "description"=>"Name of the client", "allowed_values"=>null, "extkey_attcode"=> 'client_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("server_id", array("targetclass"=>"bizApplication", "jointype"=> '', "label"=>"Server", "description"=>"the server part of the link", "allowed_values"=>null, "sql"=>"server_id", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("server_name", array("label"=>"Server", "description"=>"Name of the server", "allowed_values"=>null, "extkey_attcode"=> 'server_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeString("relation", array("label"=>"Relation", "description"=>"Type of relation between both application", "allowed_values"=>null, "sql"=>"relation", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_AddFilterFromAttribute("client_id");
		MetaModel::Init_AddFilterFromAttribute("server_id");
		
		// Display lists
		MetaModel::Init_SetZListItems('details', array('client_id', 'server_id', 'relation')); // Attributes to be displayed for a list
		MetaModel::Init_SetZListItems('list', array('client_id', 'server_id', 'relation')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('client_id', 'server_id')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('client_id', 'server_id')); // Criteria of the advanced search form
	}


}

////////////////////////////////////////////////////////////////////////////////////
//**
//* A patch is an application or OS fixe for an infrastructure
//* 
////////////////////////////////////////////////////////////////////////////////////
class bizPatch extends logRealObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"name" => "Patch",
			"description" => "Patch installed on infrastucture",
			"key_type" => "",
			"key_label" => "id",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("device_name", "name"), // inherited attributes
			"db_table" => "patches",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/default.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeExternalKey("device_id", array("targetclass"=>"bizDevice", "jointype"=> '', "label"=>"Device", "description"=>"The Device where patch is installed", "allowed_values"=>null, "sql"=>"device_id", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("device_name", array("label"=>"Device name", "description"=>"Name of the impacted device", "allowed_values"=>null, "extkey_attcode"=> 'device_id', "target_attcode"=>"name")));
   	MetaModel::Init_AddAttribute(new AttributeDate("install_date", array("label"=>"Installed date", "description"=>"Date when application was installed", "allowed_values"=>null, "sql"=>"install_date", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		
		MetaModel::Init_AddAttribute(new AttributeString("description", array("label"=>"Description", "description"=>"description du patch", "allowed_values"=>null, "sql"=>"description", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("patch_type", array("label"=>"Type", "description"=>"type de patch", "allowed_values"=>new ValueSetEnum("OS,Application"), "sql"=>"patch_type", "default_value"=>"OS", "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_InheritFilters();

		MetaModel::Init_AddFilterFromAttribute("patch_type");
		MetaModel::Init_AddFilterFromAttribute("device_id");

		
		
		
		// Display lists
		MetaModel::Init_SetZListItems('details', array('name','device_id', 'install_date', 'patch_type','description')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('name','device_id', 'patch_type','install_date')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('name', 'device_id','patch_type')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'device_id','patch_type')); // Criteria of the advanced search form

	}
}

/*** Insert here all modules requires for ITOP application  ***/

require_once('incident.business.php');
require_once('ServiceMgmt.business.php');
require_once('ChangeMgmt.php');
require_once('KEDB.php')
?>
