<?php
/*
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


//////////////////////////////////////////////////////////////////////
// Classes in 'gui'
//////////////////////////////////////////////////////////////////////
//

//////////////////////////////////////////////////////////////////////
// Classes in 'application'
//////////////////////////////////////////////////////////////////////
//

//
// Class: AuditCategory
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:AuditCategory' => 'Audit Category',
	'Class:AuditCategory+' => 'A section inside the overall audit',
	'Class:AuditCategory/Attribute:name' => 'Category Name',
	'Class:AuditCategory/Attribute:name+' => 'Short name for this category',
	'Class:AuditCategory/Attribute:description' => 'Audit Category Description',
	'Class:AuditCategory/Attribute:description+' => 'Long description for this audit category',
	'Class:AuditCategory/Attribute:definition_set' => 'Definition Set',
	'Class:AuditCategory/Attribute:definition_set+' => 'OQL expression defining the set of objects to audit',
	'Class:AuditCategory/Attribute:rules_list' => 'Audit Rules',
	'Class:AuditCategory/Attribute:rules_list+' => 'Audit rules for this category',
));

//
// Class: AuditRule
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:AuditRule' => 'Audit Rule',
	'Class:AuditRule+' => 'A rule to check for a given Audit category',
	'Class:AuditRule/Attribute:name' => 'Rule Name',
	'Class:AuditRule/Attribute:name+' => 'Short name for this rule',
	'Class:AuditRule/Attribute:description' => 'Audit Rule Description',
	'Class:AuditRule/Attribute:description+' => 'Long description for this audit rule',
	'Class:TagSetFieldData/Attribute:finalclass' => 'Tag class',
	'Class:TagSetFieldData/Attribute:obj_class' => 'Object class',
	'Class:TagSetFieldData/Attribute:obj_attcode' => 'Field code',
	'Class:AuditRule/Attribute:query' => 'Query to Run',
	'Class:AuditRule/Attribute:query+' => 'The OQL expression to run',
	'Class:AuditRule/Attribute:valid_flag' => 'Valid objects?',
	'Class:AuditRule/Attribute:valid_flag+' => 'True if the rule returns the valid objects, false otherwise',
	'Class:AuditRule/Attribute:valid_flag/Value:true' => 'true',
	'Class:AuditRule/Attribute:valid_flag/Value:true+' => 'true',
	'Class:AuditRule/Attribute:valid_flag/Value:false' => 'false',
	'Class:AuditRule/Attribute:valid_flag/Value:false+' => 'false',
	'Class:AuditRule/Attribute:category_id' => 'Category',
	'Class:AuditRule/Attribute:category_id+' => 'The category for this rule',
	'Class:AuditRule/Attribute:category_name' => 'Category',
	'Class:AuditRule/Attribute:category_name+' => 'Name of the category for this rule',
));

//
// Class: QueryOQL
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Query' => 'Query',
	'Class:Query+' => 'A query is a data set defined in a dynamic way',
	'Class:Query/Attribute:name' => 'Name',
	'Class:Query/Attribute:name+' => 'Identifies the query',
	'Class:Query/Attribute:description' => 'Description',
	'Class:Query/Attribute:description+' => 'Long description for the query (purpose, usage, etc.)',
	'Class:Query/Attribute:is_template' => 'Template for OQL fields',
	'Class:Query/Attribute:is_template+' => 'Usable as source for recipient OQL in Notifications',
	'Class:Query/Attribute:is_template/Value:yes' => 'Yes',
	'Class:Query/Attribute:is_template/Value:no' => 'No',
	'Class:QueryOQL/Attribute:fields' => 'Fields',
	'Class:QueryOQL/Attribute:fields+' => 'Comma separated list of attributes (or alias.attribute) to export',
	'Class:QueryOQL' => 'OQL Query',
	'Class:QueryOQL+' => 'A query based on the Object Query Language',
	'Class:QueryOQL/Attribute:oql' => 'Expression',
	'Class:QueryOQL/Attribute:oql+' => 'OQL Expression',
));

//////////////////////////////////////////////////////////////////////
// Classes in 'addon/userrights'
//////////////////////////////////////////////////////////////////////
//

//
// Class: User
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:User' => 'User',
	'Class:User+' => 'User login',
	'Class:User/Attribute:finalclass' => 'Type of account',
	'Class:User/Attribute:finalclass+' => 'Name of the final class',
	'Class:User/Attribute:contactid' => 'Person',
	'Class:User/Attribute:contactid+' => 'Personal details from the business data',
	'Class:User/Attribute:org_id' => 'Organization',
	'Class:User/Attribute:org_id+' => 'Organization of the associated person',
	'Class:User/Attribute:last_name' => 'Last name',
	'Class:User/Attribute:last_name+' => 'Name of the corresponding contact',
	'Class:User/Attribute:first_name' => 'First name',
	'Class:User/Attribute:first_name+' => 'First name of the corresponding contact',
	'Class:User/Attribute:email' => 'Email',
	'Class:User/Attribute:email+' => 'Email of the corresponding contact',
	'Class:User/Attribute:login' => 'Login',
	'Class:User/Attribute:login+' => 'user identification string',
	'Class:User/Attribute:language' => 'Language',
	'Class:User/Attribute:language+' => 'User language',
	'Class:User/Attribute:language/Value:EN US' => 'English',
	'Class:User/Attribute:language/Value:EN US+' => 'English (U.S.)',
	'Class:User/Attribute:language/Value:FR FR' => 'French',
	'Class:User/Attribute:language/Value:FR FR+' => 'French (France)',
	'Class:User/Attribute:profile_list' => 'Profiles',
	'Class:User/Attribute:profile_list+' => 'Roles, granting rights for that person',
	'Class:User/Attribute:allowed_org_list' => 'Allowed Organizations',
	'Class:User/Attribute:allowed_org_list+' => 'The end user is allowed to see data belonging to the following organizations. If no organization is specified, there is no restriction.',
	'Class:User/Attribute:status' => 'Status',
	'Class:User/Attribute:status+' => 'Whether the user account is enabled or disabled.',
	'Class:User/Attribute:status/Value:enabled' => 'Enabled',
	'Class:User/Attribute:status/Value:disabled' => 'Disabled',

	'Class:User/Error:LoginMustBeUnique' => 'Login must be unique - "%1$s" is already being used.',
	'Class:User/Error:AtLeastOneProfileIsNeeded' => 'At least one profile must be assigned to this user.',
	'Class:User/Error:ProfileNotAllowed' => 'Profile "%1$s" cannot be added it will deny the access to backoffice',
	'Class:User/Error:StatusChangeIsNotAllowed' => 'Changing status is not allowed for your own User',
	'Class:User/Error:AllowedOrgsMustContainUserOrg' => 'Allowed organizations must contain User organization',
	'Class:User/Error:CurrentProfilesHaveInsufficientRights' => 'The current list of profiles does not give sufficient access rights (Users are not modifiable anymore)',
	'Class:User/Error:AtLeastOneOrganizationIsNeeded' => 'At least one organization must be assigned to this user.',
	'Class:User/Error:OrganizationNotAllowed' => 'Organization not allowed.',
	'Class:User/Error:UserOrganizationNotAllowed' => 'The user account does not belong to your allowed organizations.',
	'Class:User/Error:PersonIsMandatory' => 'The Contact is mandatory.',
	'Class:UserInternal' => 'User Internal',
	'Class:UserInternal+' => 'User defined within '.ITOP_APPLICATION_SHORT,
));

//
// Class: URP_Profiles
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:URP_Profiles' => 'Profile',
	'Class:URP_Profiles+' => 'User profile',
	'Class:URP_Profiles/Attribute:name' => 'Name',
	'Class:URP_Profiles/Attribute:name+' => '',
	'Class:URP_Profiles/Attribute:description' => 'Description',
	'Class:URP_Profiles/Attribute:description+' => 'one line description',
	'Class:URP_Profiles/Attribute:user_list' => 'Users',
	'Class:URP_Profiles/Attribute:user_list+' => 'persons having this role',
));

//
// Class: URP_Dimensions
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:URP_Dimensions' => 'dimension',
	'Class:URP_Dimensions+' => 'application dimension (defining silos)',
	'Class:URP_Dimensions/Attribute:name' => 'Name',
	'Class:URP_Dimensions/Attribute:name+' => '',
	'Class:URP_Dimensions/Attribute:description' => 'Description',
	'Class:URP_Dimensions/Attribute:description+' => 'one line description',
	'Class:URP_Dimensions/Attribute:type' => 'Type',
	'Class:URP_Dimensions/Attribute:type+' => 'class name or data type (projection unit)',
));

//
// Class: URP_UserProfile
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:URP_UserProfile' => 'User to profile',
	'Class:URP_UserProfile+' => 'user profiles',
	'Class:URP_UserProfile/Name' => 'Link between %1$s and %2$s',
	'Class:URP_UserProfile/Attribute:userid' => 'User',
	'Class:URP_UserProfile/Attribute:userid+' => 'user account',
	'Class:URP_UserProfile/Attribute:userlogin' => 'Login',
	'Class:URP_UserProfile/Attribute:userlogin+' => 'User\'s login',
	'Class:URP_UserProfile/Attribute:profileid' => 'Profile',
	'Class:URP_UserProfile/Attribute:profileid+' => 'usage profile',
	'Class:URP_UserProfile/Attribute:profile' => 'Profile',
	'Class:URP_UserProfile/Attribute:profile+' => 'Profile name',
	'Class:URP_UserProfile/Attribute:reason' => 'Reason',
	'Class:URP_UserProfile/Attribute:reason+' => 'explain why this person may have this role',
));

//
// Class: URP_UserOrg
//


Dict::Add('EN US', 'English', 'English', array(
	'Class:URP_UserOrg' => 'User organizations',
	'Class:URP_UserOrg+' => 'Allowed organizations',
	'Class:URP_UserOrg/Name' => 'LinkGG between %1$s and %2$s',
	'Class:URP_UserOrg/Attribute:userid' => 'User',
	'Class:URP_UserOrg/Attribute:userid+' => 'user account',
	'Class:URP_UserOrg/Attribute:userlogin' => 'Login',
	'Class:URP_UserOrg/Attribute:userlogin+' => 'User\'s login',
	'Class:URP_UserOrg/Attribute:allowed_org_id' => 'Organization',
	'Class:URP_UserOrg/Attribute:allowed_org_id+' => 'Allowed organization',
	'Class:URP_UserOrg/Attribute:allowed_org_name' => 'Organization',
	'Class:URP_UserOrg/Attribute:allowed_org_name+' => 'Allowed organization',
	'Class:URP_UserOrg/Attribute:reason' => 'Reason',
	'Class:URP_UserOrg/Attribute:reason+' => 'explain why this person is allowed to see the data belonging to this organization',
));

//
// Class: URP_ProfileProjection
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:URP_ProfileProjection' => 'profile_projection',
	'Class:URP_ProfileProjection+' => 'profile projections',
	'Class:URP_ProfileProjection/Attribute:dimensionid' => 'Dimension',
	'Class:URP_ProfileProjection/Attribute:dimensionid+' => 'application dimension',
	'Class:URP_ProfileProjection/Attribute:dimension' => 'Dimension',
	'Class:URP_ProfileProjection/Attribute:dimension+' => 'application dimension',
	'Class:URP_ProfileProjection/Attribute:profileid' => 'Profile',
	'Class:URP_ProfileProjection/Attribute:profileid+' => 'usage profile',
	'Class:URP_ProfileProjection/Attribute:profile' => 'Profile',
	'Class:URP_ProfileProjection/Attribute:profile+' => 'Profile name',
	'Class:URP_ProfileProjection/Attribute:value' => 'Value expression',
	'Class:URP_ProfileProjection/Attribute:value+' => 'OQL expression (using $user) | constant |  | +attribute code',
	'Class:URP_ProfileProjection/Attribute:attribute' => 'Attribute',
	'Class:URP_ProfileProjection/Attribute:attribute+' => 'Target attribute code (optional)',
));

//
// Class: URP_ClassProjection
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:URP_ClassProjection' => 'class_projection',
	'Class:URP_ClassProjection+' => 'class projections',
	'Class:URP_ClassProjection/Attribute:dimensionid' => 'Dimension',
	'Class:URP_ClassProjection/Attribute:dimensionid+' => 'application dimension',
	'Class:URP_ClassProjection/Attribute:dimension' => 'Dimension',
	'Class:URP_ClassProjection/Attribute:dimension+' => 'application dimension',
	'Class:URP_ClassProjection/Attribute:class' => 'Class',
	'Class:URP_ClassProjection/Attribute:class+' => 'Target class',
	'Class:URP_ClassProjection/Attribute:value' => 'Value expression',
	'Class:URP_ClassProjection/Attribute:value+' => 'OQL expression (using $this) | constant |  | +attribute code',
	'Class:URP_ClassProjection/Attribute:attribute' => 'Attribute',
	'Class:URP_ClassProjection/Attribute:attribute+' => 'Target attribute code (optional)',
));

//
// Class: URP_ActionGrant
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:URP_ActionGrant' => 'action_permission',
	'Class:URP_ActionGrant+' => 'permissions on classes',
	'Class:URP_ActionGrant/Attribute:profileid' => 'Profile',
	'Class:URP_ActionGrant/Attribute:profileid+' => 'usage profile',
	'Class:URP_ActionGrant/Attribute:profile' => 'Profile',
	'Class:URP_ActionGrant/Attribute:profile+' => 'usage profile',
	'Class:URP_ActionGrant/Attribute:class' => 'Class',
	'Class:URP_ActionGrant/Attribute:class+' => 'Target class',
	'Class:URP_ActionGrant/Attribute:permission' => 'Permission',
	'Class:URP_ActionGrant/Attribute:permission+' => 'allowed or not allowed?',
	'Class:URP_ActionGrant/Attribute:permission/Value:yes' => 'yes',
	'Class:URP_ActionGrant/Attribute:permission/Value:yes+' => 'yes',
	'Class:URP_ActionGrant/Attribute:permission/Value:no' => 'no',
	'Class:URP_ActionGrant/Attribute:permission/Value:no+' => 'no',
	'Class:URP_ActionGrant/Attribute:action' => 'Action',
	'Class:URP_ActionGrant/Attribute:action+' => 'operations to perform on the given class',
));

//
// Class: URP_StimulusGrant
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:URP_StimulusGrant' => 'stimulus_permission',
	'Class:URP_StimulusGrant+' => 'permissions on stimilus in the life cycle of the object',
	'Class:URP_StimulusGrant/Attribute:profileid' => 'Profile',
	'Class:URP_StimulusGrant/Attribute:profileid+' => 'usage profile',
	'Class:URP_StimulusGrant/Attribute:profile' => 'Profile',
	'Class:URP_StimulusGrant/Attribute:profile+' => 'usage profile',
	'Class:URP_StimulusGrant/Attribute:class' => 'Class',
	'Class:URP_StimulusGrant/Attribute:class+' => 'Target class',
	'Class:URP_StimulusGrant/Attribute:permission' => 'Permission',
	'Class:URP_StimulusGrant/Attribute:permission+' => 'allowed or not allowed?',
	'Class:URP_StimulusGrant/Attribute:permission/Value:yes' => 'yes',
	'Class:URP_StimulusGrant/Attribute:permission/Value:yes+' => 'yes',
	'Class:URP_StimulusGrant/Attribute:permission/Value:no' => 'no',
	'Class:URP_StimulusGrant/Attribute:permission/Value:no+' => 'no',
	'Class:URP_StimulusGrant/Attribute:stimulus' => 'Stimulus',
	'Class:URP_StimulusGrant/Attribute:stimulus+' => 'stimulus code',
));

//
// Class: URP_AttributeGrant
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:URP_AttributeGrant' => 'attribute_permission',
	'Class:URP_AttributeGrant+' => 'permissions at the attributes level',
	'Class:URP_AttributeGrant/Attribute:actiongrantid' => 'Action grant',
	'Class:URP_AttributeGrant/Attribute:actiongrantid+' => 'action grant',
	'Class:URP_AttributeGrant/Attribute:attcode' => 'Attribute',
	'Class:URP_AttributeGrant/Attribute:attcode+' => 'attribute code',
));

//
// Class: UserDashboard
//
Dict::Add('EN US', 'English', 'English', array(
	'Class:UserDashboard' => 'User dashboard',
	'Class:UserDashboard+' => '',
	'Class:UserDashboard/Attribute:user_id' => 'User',
	'Class:UserDashboard/Attribute:user_id+' => '',
	'Class:UserDashboard/Attribute:menu_code' => 'Menu code',
	'Class:UserDashboard/Attribute:menu_code+' => '',
	'Class:UserDashboard/Attribute:contents' => 'Contents',
	'Class:UserDashboard/Attribute:contents+' => '',
));

//
// Expression to Natural language
//
Dict::Add('EN US', 'English', 'English', array(
	'Expression:Unit:Short:DAY' => 'd',
	'Expression:Unit:Short:WEEK' => 'w',
	'Expression:Unit:Short:MONTH' => 'm',
	'Expression:Unit:Short:YEAR' => 'y',
));


//
// String from the User Interface: menu, messages, buttons, etc...
//

Dict::Add('EN US', 'English', 'English', array(
	'BooleanLabel:yes' => 'yes',
	'BooleanLabel:no' => 'no',
	'UI:Login:Title' => ITOP_APPLICATION_SHORT.' login',
	'Menu:WelcomeMenu' => 'Welcome',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenu+' => 'Welcome to '.ITOP_APPLICATION_SHORT,// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenuPage' => 'Welcome',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenuPage+' => 'Welcome to '.ITOP_APPLICATION_SHORT,// Duplicated into itop-welcome-itil (will be removed from here...)
	'UI:WelcomeMenu:Title' => 'Welcome to '.ITOP_APPLICATION_SHORT,

	'UI:WelcomeMenu:LeftBlock' => '<p>'.ITOP_APPLICATION_SHORT.' is a complete, OpenSource, IT Operational Portal.</p>
<ul>It includes:
<li>A complete CMDB (Configuration management database) to document and manage the IT inventory.</li>
<li>An Incident management module to track and communicate about all issues occurring in the IT.</li>
<li>A change management module to plan and track the changes to the IT environment.</li>
<li>A known error database to speed up the resolution of incidents.</li>
<li>An outage module to document all planned outages and notify the appropriate contacts.</li>
<li>Dashboards to quickly get an overview of your IT.</li>
</ul>
<p>All the modules can be setup, step by step, indepently of each other.</p>',

	'UI:WelcomeMenu:RightBlock' => '<p>'.ITOP_APPLICATION_SHORT.' is service provider oriented, it allows IT engineers to manage easily multiple customers or organizations.
<ul>'.ITOP_APPLICATION_SHORT.', delivers a feature-rich set of business processes that:
<li>Enhances IT management effectiveness</li> 
<li>Drives IT operations performance</li> 
<li>Improves customer satisfaction and provides executives with insights into business performance.</li>
</ul>
</p>
<p>'.ITOP_APPLICATION_SHORT.' is completely open to be integrated within your current IT Management infrastructure.</p>
<p>
<ul>Adopting this new generation of IT Operational portal will help you to:
<li>Better manage a more and more complex IT environment.</li>
<li>Implement ITIL processes at your own pace.</li>
<li>Manage the most important asset of your IT: Documentation.</li>
</ul>
</p>',
	'UI:WelcomeMenu:Text'=> '<div>Congratulations, you landed on '.ITOP_APPLICATION.' '.ITOP_VERSION_NAME.'!</div>

<div>This version features a brand new modern and accessible backoffice design.</div>

<div>We kept '.ITOP_APPLICATION.' core functions that you liked and modernized them to make you love them.
We hope you’ll enjoy this version as much as we enjoyed imagining and creating it.</div>

<div>Customize your '.ITOP_APPLICATION.' preferences for a personalized experience.</div>',
	'UI:WelcomeMenu:AllOpenRequests' => 'Open requests: %1$d',
	'UI:WelcomeMenu:MyCalls' => 'My requests',
	'UI:WelcomeMenu:OpenIncidents' => 'Open incidents: %1$d',
	'UI:WelcomeMenu:AllConfigItems' => 'Configuration Items: %1$d',
	'UI:WelcomeMenu:MyIncidents' => 'Incidents assigned to me',
	'UI:AllOrganizations' => ' All Organizations ',
	'UI:YourSearch' => 'Your Search',
	'UI:LoggedAsMessage' => 'Logged in as %1$s (%2$s)',
	'UI:LoggedAsMessage+Admin' => 'Logged in as %1$s (%2$s, Administrator)',
	'UI:Button:Logoff' => 'Log off',
	'UI:Button:GlobalSearch' => 'Search',
	'UI:Button:Search' => ' Search ',
	'UI:Button:Clear' => ' Clear ',
	'UI:Button:SearchInHierarchy' => 'Search in hierarchy',
	'UI:Button:Query' => ' Query ',
	'UI:Button:Ok' => 'Ok',
	'UI:Button:Save' => 'Save',
	'UI:Button:SaveAnd' => 'Save and %1$s',
	'UI:Button:Cancel' => 'Cancel',
	'UI:Button:Close' => 'Close',
	'UI:Button:Apply' => 'Apply',
	'UI:Button:Send' => 'Send',
	'UI:Button:SendAnd' => 'Send and %1$s',
	'UI:Button:Back' => ' << Back ',
	'UI:Button:Restart' => ' |<< Restart ',
	'UI:Button:Next' => ' Next >> ',
	'UI:Button:Finish' => ' Finish ',
	'UI:Button:DoImport' => ' Run the Import ! ',
	'UI:Button:Done' => ' Done ',
	'UI:Button:SimulateImport' => ' Simulate the Import ',
	'UI:Button:Test' => 'Test!',
	'UI:Button:Evaluate' => ' Evaluate ',
	'UI:Button:Evaluate:Title' => ' Evaluate (Ctrl+Enter)',
	'UI:Button:AddObject' => ' Add... ',
	'UI:Button:BrowseObjects' => ' Browse... ',
	'UI:Button:Add' => ' Add ',
	'UI:Button:AddToList' => ' << Add ',
	'UI:Button:RemoveFromList' => ' Remove >> ',
	'UI:Button:FilterList' => ' Filter... ',
	'UI:Button:Create' => ' Create ',
	'UI:Button:Delete' => ' Delete ! ',
	'UI:Button:Rename' => ' Rename... ',
	'UI:Button:ChangePassword' => ' Change Password ',
	'UI:Button:ResetPassword' => ' Reset Password ',
	'UI:Button:Insert' => 'Insert',
	'UI:Button:More' => 'More',
	'UI:Button:Less' => 'Less',
	'UI:Button:Wait' => 'Please wait while updating fields',
	'UI:Treeview:CollapseAll' => 'Collapse All',
	'UI:Treeview:ExpandAll' => 'Expand All',
	'UI:UserPref:DoNotShowAgain' => 'Do not show again',
	'UI:InputFile:NoFileSelected' => 'No File Selected',
	'UI:InputFile:SelectFile' => 'Select a file',

	'UI:SearchToggle' => 'Search',
	'UI:ClickToCreateNew' => 'Create a %1$s',
	'UI:SearchFor_Class' => 'Search for %1$s objects',
	'UI:NoObjectToDisplay' => 'No object to display.',
	'UI:Error:SaveFailed' => 'The object cannot be saved :',
	'UI:Error:MandatoryTemplateParameter_object_id' => 'Parameter object_id is mandatory when link_attr is specified. Check the definition of the display template.',
	'UI:Error:MandatoryTemplateParameter_target_attr' => 'Parameter target_attr is mandatory when link_attr is specified. Check the definition of the display template.',
	'UI:Error:MandatoryTemplateParameter_group_by' => 'Parameter group_by is mandatory. Check the definition of the display template.',
	'UI:Error:InvalidGroupByFields' => 'Invalid list of fields to group by: "%1$s".',
	'UI:Error:UnsupportedStyleOfBlock' => 'Error: unsupported style of block: "%1$s".',
	'UI:Error:IncorrectLinkDefinition_LinkedClass_Class' => 'Incorrect link definition: the class of objects to manage: %1$s was not found as an external key in the class %2$s',
	'UI:Error:Object_Class_Id_NotFound' => 'Object: %1$s:%2$d not found.',
	'UI:Error:WizardCircularReferenceInDependencies' => 'Error: Circular reference in the dependencies between the fields, check the data model.',
	'UI:Error:UploadedFileTooBig' => 'The uploaded file is too big. (Max allowed size is %1$s). To modify this limit, contact your '.ITOP_APPLICATION_SHORT.' administrator. (Check the PHP configuration for upload_max_filesize and post_max_size on the server).',
	'UI:Error:UploadedFileTruncated.' => 'Uploaded file has been truncated !',
	'UI:Error:NoTmpDir' => 'The temporary directory is not defined.',
	'UI:Error:CannotWriteToTmp_Dir' => 'Unable to write the temporary file to the disk. upload_tmp_dir = "%1$s".',
	'UI:Error:UploadStoppedByExtension_FileName' => 'Upload stopped  by extension. (Original file name = "%1$s").',
	'UI:Error:UploadFailedUnknownCause_Code' => 'File upload failed, unknown cause. (Error code = "%1$s").',

	'UI:Error:1ParametersMissing' => 'Error: the following parameter must be specified for this operation: %1$s.',
	'UI:Error:2ParametersMissing' => 'Error: the following parameters must be specified for this operation: %1$s and %2$s.',
	'UI:Error:3ParametersMissing' => 'Error: the following parameters must be specified for this operation: %1$s, %2$s and %3$s.',
	'UI:Error:4ParametersMissing' => 'Error: the following parameters must be specified for this operation: %1$s, %2$s, %3$s and %4$s.',
	'UI:Error:IncorrectOQLQuery_Message' => 'Error: incorrect OQL query: %1$s',
	'UI:Error:AnErrorOccuredWhileRunningTheQuery_Message' => 'An error occured while running the query: %1$s',
	'UI:Error:ObjectAlreadyUpdated' => 'Error: the object has already been updated.',
	'UI:Error:ObjectCannotBeUpdated' => 'Error: object cannot be updated.',
	'UI:Error:ObjectsAlreadyDeleted' => 'Error: objects have already been deleted!',
	'UI:Error:BulkDeleteNotAllowedOn_Class' => 'You are not allowed to perform a bulk delete of objects of class %1$s',
	'UI:Error:DeleteNotAllowedOn_Class' => 'You are not allowed to delete objects of class %1$s',
	'UI:Error:ReadNotAllowedOn_Class' => 'You are not allowed to view objects of class %1$s',
	'UI:Error:BulkModifyNotAllowedOn_Class' => 'You are not allowed to perform a bulk update of objects of class %1$s',
	'UI:Error:ObjectAlreadyCloned' => 'Error: the object has already been cloned!',
	'UI:Error:ObjectAlreadyCreated' => 'Error: the object has already been created!',
	'UI:Error:Invalid_Stimulus_On_Object_In_State' => '%2$s is currently in the "%3$s" state, the "%1$s" operation you requested could not be applied.',
	'UI:Error:InvalidDashboardFile' => 'Error: invalid dashboard file',
	'UI:Error:InvalidDashboard' => 'Error: invalid dashboard',
	'UI:Error:MaintenanceMode' => 'Application is currently in maintenance',
	'UI:Error:MaintenanceTitle' => 'Maintenance',
	'UI:Error:InvalidToken' => 'Error: the requested operation has already been performed (CSRF token not found)',

	'UI:Error:SMTP:UnknownVendor' => 'OAuth SMTP provider %1$s does not exist  (email_transport_smtp.oauth.provider)',

	'UI:GroupBy:Count' => 'Count',
	'UI:GroupBy:Count+' => 'Number of elements',
	'UI:CountOfObjects' => '%1$d objects matching the criteria.',
	'UI_CountOfObjectsShort' => '%1$d objects.',
	'UI:NoObject_Class_ToDisplay' => 'No %1$s to display',
	'UI:History:LastModified_On_By' => 'Last modified on %1$s by %2$s.',
	'UI:HistoryTab' => 'History',
	'UI:NotificationsTab' => 'Notifications',
	'UI:History:BulkImports' => 'History',
	'UI:History:BulkImports+' => 'List of CSV imports (latest import first)',
	'UI:History:BulkImportDetails' => 'Changes resulting from the CSV import performed on %1$s (by %2$s)',
	'UI:History:Date' => 'Date',
	'UI:History:Date+' => 'Date of the change',
	'UI:History:User' => 'User',
	'UI:History:User+' => 'User who made the change',
	'UI:History:Changes' => 'Changes',
	'UI:History:Changes+' => 'Changes made to the object',
	'UI:History:StatsCreations' => 'Created',
	'UI:History:StatsCreations+' => 'Count of objects created',
	'UI:History:StatsModifs' => 'Modified',
	'UI:History:StatsModifs+' => 'Count of objects modified',
	'UI:History:StatsDeletes' => 'Deleted',
	'UI:History:StatsDeletes+' => 'Count of objects deleted',
	'UI:Loading' => 'Loading...',
	'UI:Menu:Actions' => 'Actions',
	'UI:Menu:OtherActions' => 'Other Actions',
	'UI:Menu:Transitions' => 'Transitions',
	'UI:Menu:OtherTransitions' => 'Other Transitions',
	'UI:Menu:New' => 'New...',
	'UI:Menu:Add' => 'Add...',
	'UI:Menu:Manage' => 'Manage...',
	'UI:Menu:EMail' => 'eMail',
	'UI:Menu:CSVExport' => 'CSV Export...',
	'UI:Menu:Modify' => 'Modify...',
	'UI:Menu:Delete' => 'Delete...',
	'UI:Menu:BulkDelete' => 'Delete...',
	'UI:UndefinedObject' => 'undefined',
	'UI:Document:OpenInNewWindow:Download' => 'Open in new window: %1$s, Download: %2$s',
	'UI:SplitDateTime-Date' => 'date',
	'UI:SplitDateTime-Time' => 'time',
	'UI:TruncatedResults' => '%1$d objects displayed out of %2$d',
	'UI:DisplayAll' => 'Display All',
	'UI:CollapseList' => 'Collapse',
	'UI:CountOfResults' => '%1$d object(s)',
	'UI:ChangesLogTitle' => 'Changes log (%1$d):',
	'UI:EmptyChangesLogTitle' => 'Changes log is empty',
	'UI:SearchFor_Class_Objects' => 'Search for %1$s Objects',
	'UI:OQLQueryBuilderTitle' => 'OQL Query Builder',
	'UI:OQLQueryTab' => 'OQL Query',
	'UI:SimpleSearchTab' => 'Simple Search',
	'UI:Details+' => 'Details',
	'UI:SearchValue:Any' => '* Any *',
	'UI:SearchValue:Mixed' => '* mixed *',
	'UI:SearchValue:NbSelected' => '# selected',
	'UI:SearchValue:CheckAll' => 'Check All',
	'UI:SearchValue:UncheckAll' => 'Uncheck All',
	'UI:SelectOne' => '-- select one --',
	'UI:Login:Welcome' => 'Welcome to '.ITOP_APPLICATION_SHORT.'!',
	'UI:Login:IncorrectLoginPassword' => 'Incorrect login/password, please try again.',
	'UI:Login:IdentifyYourself' => 'Identify yourself before continuing',
	'UI:Login:UserNamePrompt' => 'User Name',
	'UI:Login:PasswordPrompt' => 'Password',
	'UI:Login:ForgotPwd' => 'Forgot your password?',
	'UI:Login:ForgotPwdForm' => 'Forgot your password',
	'UI:Login:ForgotPwdForm+' => ITOP_APPLICATION_SHORT.' can send you an email in which you will find instructions to follow to reset your account.',
	'UI:Login:ResetPassword' => 'Send now!',
	'UI:Login:ResetPwdFailed' => 'Failed to send an email: %1$s',
	'UI:Login:SeparatorOr' => 'Or',

	'UI:ResetPwd-Error-WrongLogin' => '\'%1$s\' is not a valid login',
	'UI:ResetPwd-Error-NotPossible' => 'external accounts do not allow password reset.',
	'UI:ResetPwd-Error-FixedPwd' => 'the account does not allow password reset.',
	'UI:ResetPwd-Error-NoContact' => 'the account is not associated to a person.',
	'UI:ResetPwd-Error-NoEmailAtt' => 'the account is not associated to a person having an email attribute. Please Contact your administrator.',
	'UI:ResetPwd-Error-NoEmail' => 'missing an email address. Please Contact your administrator.',
	'UI:ResetPwd-Error-Send' => 'email transport technical issue. Please Contact your administrator.',
	'UI:ResetPwd-EmailSent' => 'Please check your email box and follow the instructions. If you receive no email, please check the login you typed.',
	'UI:ResetPwd-EmailSubject' => 'Reset your '.ITOP_APPLICATION_SHORT.' password',
	'UI:ResetPwd-EmailBody' => '<body><p>You have requested to reset your '.ITOP_APPLICATION_SHORT.' password.</p><p>Please follow this link (single usage) to <a href="%1$s">enter a new password</a></p>.',

	'UI:ResetPwd-Title' => 'Reset password',
	'UI:ResetPwd-Error-InvalidToken' => 'Sorry, either the password has already been reset, or you have received several emails. Please make sure that you use the link provided in the very last email received.',
	'UI:ResetPwd-Error-EnterPassword' => 'Enter a new password for the account \'%1$s\'.',
	'UI:ResetPwd-Ready' => 'The password has been changed.',
	'UI:ResetPwd-Login' => 'Click here to login...',

	'UI:Login:About'                               => ITOP_APPLICATION.' Powered by Combodo',
	'UI:Login:ChangeYourPassword'                  => 'Change Your Password',
	'UI:Login:OldPasswordPrompt'                   => 'Old password',
	'UI:Login:NewPasswordPrompt'                   => 'New password',
	'UI:Login:RetypeNewPasswordPrompt'             => 'Retype new password',
	'UI:Login:IncorrectOldPassword'                => 'Error: the old password is incorrect',
	'UI:LogOffMenu'                                => 'Log off',
	'UI:LogOff:ThankYou'                           => 'Thank you for using '.ITOP_APPLICATION,
	'UI:LogOff:ClickHereToLoginAgain'              => 'Click here to login again...',
	'UI:ChangePwdMenu'                             => 'Change Password...',
	'UI:Login:PasswordChanged'                     => 'Password successfully set!',
	'UI:AccessRO-All'                              => ITOP_APPLICATION.' is read-only',
	'UI:AccessRO-Users'                            => ITOP_APPLICATION.' is read-only for end-users',
	'UI:ApplicationEnvironment'                    => 'Application environment: %1$s',
	'UI:Login:RetypePwdDoesNotMatch'               => 'New password and retyped new password do not match!',
	'UI:Button:Login'                              => 'Enter '.ITOP_APPLICATION,
	'UI:Login:Error:AccessRestricted'              => ITOP_APPLICATION_SHORT.' access to this page is restricted. Please, contact an '.ITOP_APPLICATION_SHORT.' administrator.',
	'UI:Login:Error:AccessAdmin'                   => 'Access restricted to people having administrator privileges. Please, contact an '.ITOP_APPLICATION_SHORT.' administrator.',
	'UI:Login:Error:WrongOrganizationName'         => 'Unknown organization',
	'UI:Login:Error:MultipleContactsHaveSameEmail' => 'Multiple contacts have the same e-mail',
	'UI:Login:Error:NoValidProfiles'               => 'No valid profile provided',
	'UI:CSVImport:MappingSelectOne'                => '-- select one --',
	'UI:CSVImport:MappingNotApplicable'            => '-- ignore this field --',
	'UI:CSVImport:NoData'                          => 'Empty data set..., please provide some data!',
	'UI:Title:DataPreview'                         => 'Data Preview',
	'UI:CSVImport:ErrorOnlyOneColumn'              => 'Error: The data contains only one column. Did you select the appropriate separator character?',
	'UI:CSVImport:FieldName'                       => 'Field %1$d',
	'UI:CSVImport:DataLine1'                       => 'Data Line 1',
	'UI:CSVImport:DataLine2'                       => 'Data Line 2',
	'UI:CSVImport:idField'                         => 'id (Primary Key)',
	'UI:Title:BulkImport'                          => ITOP_APPLICATION_SHORT.' - Bulk import',
	'UI:Title:BulkImport+'                         => 'CSV Import Wizard',
	'UI:Title:BulkSynchro_nbItem_ofClass_class'    => 'Synchronization of %1$d objects of class %2$s',
	'UI:CSVImport:ClassesSelectOne'                => '-- select one --',
	'UI:CSVImport:ErrorExtendedAttCode'            => 'Internal error: "%1$s" is an incorrect code because "%2$s" is NOT an external key of the class "%3$s"',
	'UI:CSVImport:ObjectsWillStayUnchanged'        => '%1$d objects(s) will stay unchanged.',
	'UI:CSVImport:ObjectsWillBeModified'           => '%1$d objects(s) will be modified.',
	'UI:CSVImport:ObjectsWillBeAdded' => '%1$d objects(s) will be added.',
	'UI:CSVImport:ObjectsWillHaveErrors' => '%1$d objects(s) will have errors.',
	'UI:CSVImport:ObjectsRemainedUnchanged' => '%1$d objects(s) remained unchanged.',
	'UI:CSVImport:ObjectsWereModified' => '%1$d objects(s) were modified.',
	'UI:CSVImport:ObjectsWereAdded' => '%1$d objects(s) were added.',
	'UI:CSVImport:ObjectsHadErrors' => '%1$d objects(s) had errors.',
	'UI:Title:CSVImportStep2' => 'Step 2 of 5: CSV data options',
	'UI:Title:CSVImportStep3' => 'Step 3 of 5: Data mapping',
	'UI:Title:CSVImportStep4' => 'Step 4 of 5: Import simulation',
	'UI:Title:CSVImportStep5' => 'Step 5 of 5: Import completed',
	'UI:CSVImport:LinesNotImported' => 'Lines that could not be loaded:',
	'UI:CSVImport:LinesNotImported+' => 'The following lines have not been imported because they contain errors',
	'UI:CSVImport:SeparatorComma+' => ', (comma)',
	'UI:CSVImport:SeparatorSemicolon+' => '; (semicolon)',
	'UI:CSVImport:SeparatorTab+' => 'tab',
	'UI:CSVImport:SeparatorOther' => 'other:',
	'UI:CSVImport:QualifierDoubleQuote+' => '" (double quote)',
	'UI:CSVImport:QualifierSimpleQuote+' => '\' (simple quote)',
	'UI:CSVImport:QualifierOther' => 'other:',
	'UI:CSVImport:TreatFirstLineAsHeader' => 'Treat the first line as a header (column names)',
	'UI:CSVImport:Skip_N_LinesAtTheBeginning' => 'Skip %1$s line(s) at the beginning of the file',
	'UI:CSVImport:CSVDataPreview' => 'CSV Data Preview',
	'UI:CSVImport:SelectFile' => 'Select the file to import:',
	'UI:CSVImport:Tab:LoadFromFile' => 'Load from a file',
	'UI:CSVImport:Tab:CopyPaste' => 'Copy and paste data',
	'UI:CSVImport:Tab:Templates' => 'Templates',
	'UI:CSVImport:PasteData' => 'Paste the data to import:',
	'UI:CSVImport:PickClassForTemplate' => 'Pick the template to download: ',
	'UI:CSVImport:SeparatorCharacter' => 'Separator character:',
	'UI:CSVImport:TextQualifierCharacter' => 'Text qualifier character',
	'UI:CSVImport:CommentsAndHeader' => 'Comments and header',
	'UI:CSVImport:SelectClass' => 'Select the class to import:',
	'UI:CSVImport:AdvancedMode' => 'Advanced mode',
	'UI:CSVImport:AdvancedMode+' => 'In advanced mode the "id" (primary key) of the objects can be used to update and rename objects.'.
		'However the column "id" (if present) can only be used as a search criteria and can not be combined with any other search criteria.',
	'UI:CSVImport:SelectAClassFirst' => 'To configure the mapping, select a class first.',
	'UI:CSVImport:HeaderFields' => 'Fields',
	'UI:CSVImport:HeaderMappings' => 'Mappings',
	'UI:CSVImport:HeaderSearch' => 'Search?',
	'UI:CSVImport:AlertIncompleteMapping' => 'Please select a mapping for every field.',
	'UI:CSVImport:AlertMultipleMapping' => 'Please make sure that a target field is mapped only once.',
	'UI:CSVImport:AlertNoSearchCriteria' => 'Please select at least one search criteria',
	'UI:CSVImport:Encoding' => 'Character encoding',
	'UI:UniversalSearchTitle' => ITOP_APPLICATION_SHORT.' - Universal Search',
	'UI:UniversalSearch:Error' => 'Error: %1$s',
	'UI:UniversalSearch:LabelSelectTheClass' => 'Select the class to search: ',

	'UI:CSVReport-Value-Modified' => 'Modified',
	'UI:CSVReport-Value-SetIssue' => 'Could not be changed - reason: %1$s',
	'UI:CSVReport-Value-ChangeIssue' => 'Could not be changed to %1$s - reason: %2$s',
	'UI:CSVReport-Value-NoMatch' => 'No match',
	'UI:CSVReport-Value-Missing' => 'Missing mandatory value',
	'UI:CSVReport-Value-Ambiguous' => 'Ambiguous: found %1$s objects',
	'UI:CSVReport-Row-Unchanged' => 'unchanged',
	'UI:CSVReport-Row-Created' => 'created',
	'UI:CSVReport-Row-Updated' => 'updated %1$d cols',
	'UI:CSVReport-Row-Disappeared' => 'disappeared, changed %1$d cols',
	'UI:CSVReport-Row-Issue' => 'Issue: %1$s',
	'UI:CSVReport-Value-Issue-Null' => 'Null not allowed',
	'UI:CSVReport-Value-Issue-NotFound' => 'Object not found',
	'UI:CSVReport-Value-Issue-FoundMany' => 'Found %1$d matches',
	'UI:CSVReport-Value-Issue-Readonly' => 'The attribute \'%1$s\' is read-only and cannot be modified (current value: %2$s, proposed value: %3$s)',
	'UI:CSVReport-Value-Issue-Format' => 'Failed to process input: %1$s',
	'UI:CSVReport-Value-Issue-NoMatch' => 'Unexpected value for attribute \'%1$s\': no match found, check spelling',
	'UI:CSVReport-Value-Issue-Unknown' => 'Unexpected value for attribute \'%1$s\': %2$s',
	'UI:CSVReport-Row-Issue-Inconsistent' => 'Attributes not consistent with each others: %1$s',
	'UI:CSVReport-Row-Issue-Attribute' => 'Unexpected attribute value(s)',
	'UI:CSVReport-Row-Issue-MissingExtKey' => 'Could not be created, due to missing external key(s): %1$s',
	'UI:CSVReport-Row-Issue-DateFormat' => 'wrong date format',
	'UI:CSVReport-Row-Issue-Reconciliation' => 'failed to reconcile',
	'UI:CSVReport-Row-Issue-Ambiguous' => 'ambiguous reconciliation',
	'UI:CSVReport-Row-Issue-Internal' => 'Internal error: %1$s, %2$s',

	'UI:CSVReport-Icon-Unchanged' => 'Unchanged',
	'UI:CSVReport-Icon-Modified' => 'Modified',
	'UI:CSVReport-Icon-Missing' => 'Missing',
	'UI:CSVReport-Object-MissingToUpdate' => 'Missing object: will be updated',
	'UI:CSVReport-Object-MissingUpdated' => 'Missing object: updated',
	'UI:CSVReport-Icon-Created' => 'Created',
	'UI:CSVReport-Object-ToCreate' => 'Object will be created',
	'UI:CSVReport-Object-Created' => 'Object created',
	'UI:CSVReport-Icon-Error' => 'Error',
	'UI:CSVReport-Object-Error' => 'ERROR: %1$s',
	'UI:CSVReport-Object-Ambiguous' => 'AMBIGUOUS: %1$s',
	'UI:CSVReport-Stats-Errors' => '%1$.0f %% of the loaded objects have errors and will be ignored.',
	'UI:CSVReport-Stats-Created' => '%1$.0f %% of the loaded objects will be created.',
	'UI:CSVReport-Stats-Modified' => '%1$.0f %% of the loaded objects will be modified.',

	'UI:CSVExport:AdvancedMode' => 'Advanced mode',
	'UI:CSVExport:AdvancedMode+' => 'In advanced mode, several columns are added to the export: the id of the object, the id of external keys and their reconciliation attributes.',
	'UI:CSVExport:LostChars' => 'Encoding issue',
	'UI:CSVExport:LostChars+' => 'The downloaded file will be encoded into %1$s. '.ITOP_APPLICATION_SHORT.' has detected some characters that are not compatible with this format. Those characters will either be replaced by a substitute (e.g. accentuated chars losing the accent), or they will be discarded. You can copy/paste the data from your web browser. Alternatively, you can contact your administrator to change the encoding (See parameter \'csv_file_default_charset\').',

	'UI:Audit:Title' => ITOP_APPLICATION_SHORT.' - CMDB Audit',
	'UI:Audit:InteractiveAudit' => 'Interactive Audit',
	'UI:Audit:HeaderAuditRule' => 'Audit Rule',
	'UI:Audit:HeaderNbObjects' => '# Objects',
	'UI:Audit:HeaderNbErrors' => '# Errors',
	'UI:Audit:PercentageOk' => '% Ok',
	'UI:Audit:OqlError' => 'OQL Error',
	'UI:Audit:Error:ValueNA' => 'n/a',
	'UI:Audit:ErrorIn_Rule' => 'Error in Rule',
	'UI:Audit:ErrorIn_Rule_Reason' => 'OQL Error in the Rule %1$s: %2$s.',
	'UI:Audit:ErrorIn_Category' => 'Error in Category',
	'UI:Audit:ErrorIn_Category_Reason' => 'OQL Error in the Category %1$s: %2$s.',
	'UI:Audit:AuditErrors' => 'Audit Errors',
	'UI:Audit:Dashboard:ObjectsAudited' => 'Objects audited',
	'UI:Audit:Dashboard:ObjectsInError' => 'Objects in errors',
	'UI:Audit:Dashboard:ObjectsValidated' => 'Objects validated',
	'UI:Audit:AuditCategory:Subtitle' => '%1$s errors ouf of %2$s - %3$s%%',


	'UI:RunQuery:Title' => ITOP_APPLICATION_SHORT.' - OQL Query Evaluation',
	'UI:RunQuery:QueryExamples' => 'Query Examples',
	'UI:RunQuery:QueryResults' => 'Query Results',
	'UI:RunQuery:HeaderPurpose' => 'Purpose',
	'UI:RunQuery:HeaderPurpose+' => 'Explanation about the query',
	'UI:RunQuery:HeaderOQLExpression' => 'OQL Expression',
	'UI:RunQuery:HeaderOQLExpression+' => 'The query in OQL syntax',
	'UI:RunQuery:ExpressionToEvaluate' => 'Expression to evaluate: ',
	'UI:RunQuery:QueryArguments' => 'Query Arguments',
	'UI:RunQuery:MoreInfo' => 'More information about the query: ',
	'UI:RunQuery:DevelopedQuery' => 'Redevelopped query expression: ',
	'UI:RunQuery:SerializedFilter' => 'Serialized filter: ',
	'UI:RunQuery:DevelopedOQL' => 'Developed OQL',
	'UI:RunQuery:DevelopedOQLCount' => 'Developed OQL for count',
	'UI:RunQuery:ResultSQLCount' => 'Resulting SQL for count',
	'UI:RunQuery:ResultSQL' => 'Resulting SQL',
	'UI:RunQuery:Error' => 'An error occured while running the query',
	'UI:Query:UrlForExcel' => 'URL to use for MS-Excel web queries',
	'UI:Query:UrlV1' => 'The list of fields has been left unspecified. The page <em>export-V2.php</em> cannot be invoked without this information. Therefore, the URL suggested here below points to the legacy page: <em>export.php</em>. This legacy version of the export has the following limitation: the list of exported fields may vary depending on the output format and the data model of '.ITOP_APPLICATION_SHORT.'. <br/>Should you want to guarantee that the list of exported columns will remain stable on the long run, then you must specify a value for the attribute "Fields" and use the page <em>export-V2.php</em>.',
	'UI:Schema:Title' => ITOP_APPLICATION_SHORT.' objects schema',
	'UI:Schema:TitleForClass' => '%1$s schema',
	'UI:Schema:CategoryMenuItem' => 'Category <b>%1$s</b>',
	'UI:Schema:Relationships' => 'Relationships',
	'UI:Schema:AbstractClass' => 'Abstract class: no object from this class can be instantiated.',
	'UI:Schema:NonAbstractClass' => 'Non abstract class: objects from this class can be instantiated.',
	'UI:Schema:ClassHierarchyTitle' => 'Class hierarchy',
	'UI:Schema:AllClasses' => 'All classes',
	'UI:Schema:ExternalKey_To' => 'External key to %1$s',
	'UI:Schema:Columns_Description' => 'Columns: <em>%1$s</em>',
	'UI:Schema:Default_Description' => 'Default: "%1$s"',
	'UI:Schema:NullAllowed' => 'Null Allowed',
	'UI:Schema:NullNotAllowed' => 'Null NOT Allowed',
	'UI:Schema:Attributes' => 'Attributes',
	'UI:Schema:AttributeCode' => 'Attribute Code',
	'UI:Schema:AttributeCode+' => 'Internal code of the attribute',
	'UI:Schema:Label' => 'Label',
	'UI:Schema:Label+' => 'Label of the attribute',
	'UI:Schema:Type' => 'Type',

	'UI:Schema:Type+' => 'Data type of the attribute',
	'UI:Schema:Origin' => 'Origin',
	'UI:Schema:Origin+' => 'The base class in which this attribute is defined',
	'UI:Schema:Description' => 'Description',
	'UI:Schema:Description+' => 'Description of the attribute',
	'UI:Schema:AllowedValues' => 'Allowed values',
	'UI:Schema:AllowedValues+' => 'Restrictions on the possible values for this attribute',
	'UI:Schema:MoreInfo' => 'More info',
	'UI:Schema:MoreInfo+' => 'More information about the field defined in the database',
	'UI:Schema:SearchCriteria' => 'Search criteria',
	'UI:Schema:FilterCode' => 'Filter code',
	'UI:Schema:FilterCode+' => 'Code of this search criteria',
	'UI:Schema:FilterDescription' => 'Description',
	'UI:Schema:FilterDescription+' => 'Description of this search criteria',
	'UI:Schema:AvailOperators' => 'Available operators',
	'UI:Schema:AvailOperators+' => 'Possible operators for this search criteria',
	'UI:Schema:ChildClasses' => 'Child classes',
	'UI:Schema:ReferencingClasses' => 'Referencing classes',
	'UI:Schema:RelatedClasses' => 'Related classes',
	'UI:Schema:LifeCycle' => 'Life cycle',
	'UI:Schema:Triggers' => 'Triggers',
	'UI:Schema:Relation_Code_Description' => 'Relation <em>%1$s</em> (%2$s)',
	'UI:Schema:RelationDown_Description' => 'Down: %1$s',
	'UI:Schema:RelationUp_Description' => 'Up: %1$s',
	'UI:Schema:RelationPropagates' => '%1$s: propagate to %2$d levels, query: %3$s',
	'UI:Schema:RelationDoesNotPropagate' => '%1$s: does not propagates (%2$d levels), query: %3$s',
	'UI:Schema:Class_ReferencingClasses_From_By' => '%1$s is referenced by the class %2$s via the field %3$s',
	'UI:Schema:Class_IsLinkedTo_Class_Via_ClassAndAttribute' => '%1$s is linked to %2$s via %3$s::<em>%4$s</em>',
	'UI:Schema:Links:1-n' => 'Classes pointing to %1$s (1:n links):',
	'UI:Schema:Links:n-n' => 'Classes linked to %1$s (n:n links):',
	'UI:Schema:Links:All' => 'Graph of all related classes',
	'UI:Schema:NoLifeCyle' => 'There is no life cycle defined for this class.',
	'UI:Schema:LifeCycleTransitions' => 'States and Transitions',
	'UI:Schema:LifeCyleAttributeOptions' => 'Attribute options',
	'UI:Schema:LifeCycleHiddenAttribute' => 'Hidden',
	'UI:Schema:LifeCycleReadOnlyAttribute' => 'Read-only',
	'UI:Schema:LifeCycleMandatoryAttribute' => 'Mandatory',
	'UI:Schema:LifeCycleAttributeMustChange' => 'Must change',
	'UI:Schema:LifeCycleAttributeMustPrompt' => 'User will be prompted to change the value',
	'UI:Schema:LifeCycleEmptyList' => 'empty list',
	'UI:Schema:ClassFilter' => 'Class:~~',
	'UI:Schema:DisplayLabel' => 'Display:~~',
	'UI:Schema:DisplaySelector/LabelAndCode' => 'Label and code~~',
	'UI:Schema:DisplaySelector/Label' => 'Label~~',
	'UI:Schema:DisplaySelector/Code' => 'Code~~',
	'UI:Schema:Attribute/Filter' => 'Filter~~',
	'UI:Schema:DefaultNullValue' => 'Default null : "%1$s"~~',
	'UI:LinksWidget:Autocomplete+' => 'Type the first 3 characters...',
	'UI:Edit:SearchQuery' => 'Select a predefined query',
	'UI:Edit:TestQuery' => 'Test query',
	'UI:Combo:SelectValue' => '--- select a value ---',
	'UI:Label:SelectedObjects' => 'Selected objects: ',
	'UI:Label:AvailableObjects' => 'Available objects: ',
	'UI:Link_Class_Attributes' => '%1$s attributes',
	'UI:SelectAllToggle+' => 'Select All / Deselect All',
	'UI:AddObjectsOf_Class_LinkedWith_Class_Instance' => 'Add %1$s objects linked with %2$s: %3$s',
	'UI:AddObjectsOf_Class_LinkedWith_Class' => 'Add %1$s objects to link with the %2$s',
	'UI:ManageObjectsOf_Class_LinkedWith_Class_Instance' => 'Manage %1$s objects linked with %2$s: %3$s',
	'UI:AddLinkedObjectsOf_Class' => 'Add %1$s objects...',
	'UI:RemoveLinkedObjectsOf_Class' => 'Remove selected objects',
	'UI:Message:EmptyList:UseAdd' => 'The list is empty, use the "Add..." button to add elements.',
	'UI:Message:EmptyList:UseSearchForm' => 'Use the search form above to search for objects to be added.',
	'UI:Wizard:FinalStepTitle' => 'Final step: confirmation',
	'UI:Title:DeletionOf_Object' => 'Deletion of %1$s',
	'UI:Title:BulkDeletionOf_Count_ObjectsOf_Class' => 'Bulk deletion of %1$d objects of class %2$s',
	'UI:Delete:NotAllowedToDelete' => 'You are not allowed to delete this object',
	'UI:Delete:NotAllowedToUpdate_Fields' => 'You are not allowed to update the fields: %1$s',
	'UI:Error:ActionNotAllowed' => 'You are not allowed to do this action',
	'UI:Error:NotEnoughRightsToDelete' => 'This object could not be deleted because the current user do not have sufficient rights',
	'UI:Error:CannotDeleteBecause' => 'This object could not be deleted because: %1$s',
	'UI:Error:CannotDeleteBecauseOfDepencies' => 'This object could not be deleted because some manual operations must be performed prior to that',
	'UI:Error:CannotDeleteBecauseManualOpNeeded' => 'This object could not be deleted because some manual operations must be performed prior to that',
	'UI:Archive_User_OnBehalfOf_User' => '%1$s on behalf of %2$s',
	'UI:Delete:Deleted' => 'deleted',
	'UI:Delete:AutomaticallyDeleted' => 'automatically deleted',
	'UI:Delete:AutomaticResetOf_Fields' => 'automatic reset of field(s): %1$s',
	'UI:Delete:CleaningUpRefencesTo_Object' => 'Cleaning up all references to %1$s...',
	'UI:Delete:CleaningUpRefencesTo_Several_ObjectsOf_Class' => 'Cleaning up all references to %1$d objects of class %2$s...',
	'UI:Delete:Done+' => 'What was done...',
	'UI:Delete:_Name_Class_Deleted' => '%1$s - %2$s deleted.',
	'UI:Delete:ConfirmDeletionOf_Name' => 'Deletion of %1$s',
	'UI:Delete:ConfirmDeletionOf_Count_ObjectsOf_Class' => 'Deletion of %1$d objects of class %2$s',
	'UI:Delete:CannotDeleteBecause' => 'Could not be deleted: %1$s',
	'UI:Delete:ShouldBeDeletedAtomaticallyButNotPossible' => 'Should be automatically deleted, but this is not feasible: %1$s',
	'UI:Delete:MustBeDeletedManuallyButNotPossible' => 'Must be deleted manually, but this is not feasible: %1$s',
	'UI:Delete:WillBeDeletedAutomatically' => 'Will be automatically deleted',
	'UI:Delete:MustBeDeletedManually' => 'Must be deleted manually',
	'UI:Delete:CannotUpdateBecause_Issue' => 'Should be automatically updated, but: %1$s',
	'UI:Delete:WillAutomaticallyUpdate_Fields' => 'will be automatically updated (reset: %1$s)',
	'UI:Delete:Count_Objects/LinksReferencing_Object' => '%1$d objects/links are referencing %2$s',
	'UI:Delete:Count_Objects/LinksReferencingTheObjects' => '%1$d objects/links are referencing some of the objects to be deleted',
	'UI:Delete:ReferencesMustBeDeletedToEnsureIntegrity' => 'To ensure Database integrity, any reference should be further eliminated',
	'UI:Delete:Consequence+' => 'What will be done',
	'UI:Delete:SorryDeletionNotAllowed' => 'Sorry, you are not allowed to delete this object, see the detailed explanations above',
	'UI:Delete:PleaseDoTheManualOperations' => 'Please perform the manual operations listed above prior to requesting the deletion of this object',
	'UI:Delect:Confirm_Object' => 'Please confirm that you want to delete %1$s.',
	'UI:Delect:Confirm_Count_ObjectsOf_Class' => 'Please confirm that you want to delete the following %1$d objects of class %2$s.',
	'UI:WelcomeToITop' => 'Welcome to '.ITOP_APPLICATION,
	'UI:DetailsPageTitle' => ITOP_APPLICATION_SHORT.' - %1$s - %2$s details',
	'UI:ErrorPageTitle' => ITOP_APPLICATION_SHORT.' - Error',
	'UI:ObjectDoesNotExist' => 'Sorry, this object does not exist (or you are not allowed to view it).',
	'UI:ObjectArchived' => 'This object has been archived. Please enable the archive mode or contact your administrator.',
	'Tag:Archived' => 'Archived',
	'Tag:Archived+' => 'Can be accessed only in archive mode',
	'Tag:Obsolete' => 'Obsolete',
	'Tag:Obsolete+' => 'Excluded from the impact analysis and search results',
	'Tag:Synchronized' => 'Synchronized',
	'ObjectRef:Archived' => 'Archived',
	'ObjectRef:Obsolete' => 'Obsolete',
	'UI:SearchResultsPageTitle' => ITOP_APPLICATION_SHORT.' - Search Results',
	'UI:SearchResultsTitle' => 'Search Results',
	'UI:SearchResultsTitle+' => 'Full-text search results',
	'UI:Search:NoSearch' => 'Nothing to search for',
	'UI:Search:NeedleTooShort' => 'The search string "%1$s" is too short. Please type at least %2$d characters.',
	'UI:Search:Ongoing' => 'Searching for "%1$s"',
	'UI:Search:Enlarge' => 'Broaden the search',
	'UI:FullTextSearchTitle_Text' => 'Results for "%1$s":',
	'UI:Search:Count_ObjectsOf_Class_Found' => '%1$d object(s) of class %2$s found.',
	'UI:Search:NoObjectFound' => 'No object found.',
	'UI:ModificationPageTitle_Object_Class' => ITOP_APPLICATION_SHORT.' - %1$s - %2$s modification',
	'UI:ModificationTitle_Class_Object' => 'Modification of %1$s: <span class=\"hilite\">%2$s</span>',
	'UI:ClonePageTitle_Object_Class' => ITOP_APPLICATION_SHORT.' - Clone %1$s - %2$s modification',
	'UI:CloneTitle_Class_Object' => 'Clone of %1$s: <span class=\"hilite\">%2$s</span>',
	'UI:CreationPageTitle_Class' => ITOP_APPLICATION_SHORT.' - Creation of a new %1$s ',
	'UI:CreationTitle_Class' => 'Creation of a new %1$s',
	'UI:SelectTheTypeOf_Class_ToCreate' => 'Select the type of %1$s to create:',
	'UI:Class_Object_NotUpdated' => 'No change detected, %1$s (%2$s) has <strong>not</strong> been modified.',
	'UI:Class_Object_Updated' => '%1$s (%2$s) updated.',
	'UI:BulkDeletePageTitle' => ITOP_APPLICATION_SHORT.' - Bulk Delete',
	'UI:BulkDeleteTitle' => 'Select the objects you want to delete:',
	'UI:PageTitle:ObjectCreated' => ITOP_APPLICATION_SHORT.' Object Created.',
	'UI:Title:Object_Of_Class_Created' => '%1$s - %2$s created.',
	'UI:Apply_Stimulus_On_Object_In_State_ToTarget_State' => 'Applying %1$s on object: %2$s in state %3$s to target state: %4$s.',
	'UI:ObjectCouldNotBeWritten' => 'The object could not be written: %1$s',
	'UI:PageTitle:FatalError' => ITOP_APPLICATION_SHORT.' - Fatal Error',
	'UI:SystemIntrusion' => 'Access denied. You have requested an operation that is not allowed for you.',
	'UI:FatalErrorMessage' => 'Fatal error, '.ITOP_APPLICATION_SHORT.' cannot continue.',
	'UI:Error_Details' => 'Error: %1$s.',

	'UI:PageTitle:ProfileProjections' => ITOP_APPLICATION_SHORT.' user management - profile projections',
	'UI:UserManagement:Class' => 'Class',
	'UI:UserManagement:Class+' => 'Class of objects',
	'UI:UserManagement:ProjectedObject' => 'Object',
	'UI:UserManagement:ProjectedObject+' => 'Projected object',
	'UI:UserManagement:AnyObject' => '* any *',
	'UI:UserManagement:User' => 'User',
	'UI:UserManagement:User+' => 'User involved in the projection',
	'UI:UserManagement:Action:Read' => 'Read',
	'UI:UserManagement:Action:Read+' => 'Read/display objects',
	'UI:UserManagement:Action:Modify' => 'Modify',
	'UI:UserManagement:Action:Modify+' => 'Create and edit (modify) objects',
	'UI:UserManagement:Action:Delete' => 'Delete',
	'UI:UserManagement:Action:Delete+' => 'Delete objects',
	'UI:UserManagement:Action:BulkRead' => 'Bulk Read (Export)',
	'UI:UserManagement:Action:BulkRead+' => 'List objects or export massively',
	'UI:UserManagement:Action:BulkModify' => 'Bulk Modify',
	'UI:UserManagement:Action:BulkModify+' => 'Massively create/edit (CSV import)',
	'UI:UserManagement:Action:BulkDelete' => 'Bulk Delete',
	'UI:UserManagement:Action:BulkDelete+' => 'Massively delete objects',
	'UI:UserManagement:Action:Stimuli' => 'Stimuli',
	'UI:UserManagement:Action:Stimuli+' => 'Allowed (compound) actions',
	'UI:UserManagement:Action' => 'Action',
	'UI:UserManagement:Action+' => 'Action performed by the user',
	'UI:UserManagement:TitleActions' => 'Actions',
	'UI:UserManagement:Permission' => 'Permission',
	'UI:UserManagement:Permission+' => 'User\'s permissions',
	'UI:UserManagement:Attributes' => 'Attributes',
	'UI:UserManagement:ActionAllowed:Yes' => 'Yes',
	'UI:UserManagement:ActionAllowed:No' => 'No',
	'UI:UserManagement:AdminProfile+' => 'Administrators have full read/write access to all objects in the database.',
	'UI:UserManagement:NoLifeCycleApplicable' => 'N/A',
	'UI:UserManagement:NoLifeCycleApplicable+' => 'No lifecycle has been defined for this class',
	'UI:UserManagement:GrantMatrix' => 'Grant Matrix',

	'Menu:AdminTools' => 'Administration',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AdminTools+' => 'Administration tools',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AdminTools?' => 'Tools accessible only to users having the administrator profile',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:SystemTools' => 'System',

	'UI:ChangeManagementMenu' => 'Change Management',
	'UI:ChangeManagementMenu+' => 'Change Management',
	'UI:ChangeManagementMenu:Title' => 'Changes Overview',
	'UI-ChangeManagementMenu-ChangesByType' => 'Changes by type',
	'UI-ChangeManagementMenu-ChangesByStatus' => 'Changes by status',
	'UI-ChangeManagementMenu-ChangesNotYetAssigned' => 'Changes not yet assigned',

	'UI:ConfigurationManagementMenu' => 'Configuration Management',
	'UI:ConfigurationManagementMenu+' => 'Configuration Management',
	'UI:ConfigurationManagementMenu:Title' => 'Infrastructure Overview',
	'UI-ConfigurationManagementMenu-InfraByType' => 'Infrastructure objects by type',
	'UI-ConfigurationManagementMenu-InfraByStatus' => 'Infrastructure objects by status',

	'UI:ConfigMgmtMenuOverview:Title' => 'Dashboard for Configuration Management',
	'UI-ConfigMgmtMenuOverview-FunctionalCIbyStatus' => 'Configuration Items by status',
	'UI-ConfigMgmtMenuOverview-FunctionalCIByType' => 'Configuration Items by type',

	'UI:RequestMgmtMenuOverview:Title' => 'Dashboard for Request Management',
	'UI-RequestManagementOverview-RequestByService' => 'User Requests by service',
	'UI-RequestManagementOverview-RequestByPriority' => 'User Requests by priority',
	'UI-RequestManagementOverview-RequestUnassigned' => 'User Requests not yet assigned to an agent',

	'UI:IncidentMgmtMenuOverview:Title' => 'Dashboard for Incident Management',
	'UI-IncidentManagementOverview-IncidentByService' => 'Incidents by service',
	'UI-IncidentManagementOverview-IncidentByPriority' => 'Incidents by priority',
	'UI-IncidentManagementOverview-IncidentUnassigned' => 'Incidents not yet assigned to an agent',

	'UI:ChangeMgmtMenuOverview:Title' => 'Dashboard for Change Management',
	'UI-ChangeManagementOverview-ChangeByType' => 'Changes by type',
	'UI-ChangeManagementOverview-ChangeUnassigned' => 'Changes not yet assigned to an agent',
	'UI-ChangeManagementOverview-ChangeWithOutage' => 'Outages due to changes',

	'UI:ServiceMgmtMenuOverview:Title' => 'Dashboard for Service Management',
	'UI-ServiceManagementOverview-CustomerContractToRenew' => 'Customer contracts to be renewed in 30 days',
	'UI-ServiceManagementOverview-ProviderContractToRenew' => 'Provider contracts to be renewed in 30 days',

	'UI:ContactsMenu' => 'Contacts',
	'UI:ContactsMenu+' => 'Contacts',
	'UI:ContactsMenu:Title' => 'Contacts Overview',
	'UI-ContactsMenu-ContactsByLocation' => 'Contacts by location',
	'UI-ContactsMenu-ContactsByType' => 'Contacts by type',
	'UI-ContactsMenu-ContactsByStatus' => 'Contacts by status',

	'Menu:CSVImportMenu' => 'CSV import',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:CSVImportMenu+' => 'Bulk creation or update',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:DataModelMenu' => 'Data Model',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataModelMenu+' => 'Overview of the Data Model',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:ExportMenu' => 'Export',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ExportMenu+' => 'Export the results of any query in HTML, CSV or XML',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:NotificationsMenu' => 'Notifications',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:NotificationsMenu+' => 'Configuration of the Notifications',// Duplicated into itop-welcome-itil (will be removed from here...)
	'UI:NotificationsMenu:Title' => 'Configuration of the Notifications',
	'UI:NotificationsMenu:Help' => 'Help',
	'UI:NotificationsMenu:HelpContent' => '<p>In '.ITOP_APPLICATION_SHORT.' the notifications are fully customizable. They are based on two sets of objects: <i>triggers and actions</i>.</p>
<p><i><b>Triggers</b></i> define when a notification will be executed. There are different triggers as part of '.ITOP_APPLICATION_SHORT.' core, but others can be brought by extensions:
<ol>
	<li>Some triggers are executed when an object of the specified class is <b>created</b>, <b>updated</b> or <b>deleted</b>.</li>
	<li>Some triggers are executed when an object of a given class <b>enter</b> or <b>leave</b> a specified </b>state</b>.</li>
	<li>Some triggers are executed when a <b>threshold on TTO or TTR</b> has been <b>reached</b>.</li>
</ol>
</p>
<p>
<i><b>Actions</b></i> define the actions to be performed when the triggers execute. For now there are only two kind of actions:
<ol>
	<li>Sending an email message: Such actions also define the template to be used for sending the email as well as the other parameters of the message like the recipients, importance, etc.<br />
	A special page: <a href="../setup/email.test.php" target="_blank">email.test.php</a> is available for testing and troubleshooting your PHP mail configuration.</li>
	<li>Outgoing webhooks: Allow integration with a third-party application by sending structured data to a defined URL.</li>
</ol>
</p>
<p>To be executed, actions must be associated to triggers.
When associated with a trigger, each action is given an "order" number, specifying in which order the actions are to be executed.</p>',
	'UI:NotificationsMenu:Triggers' => 'Triggers',
	'UI:NotificationsMenu:AvailableTriggers' => 'Available triggers',
	'UI:NotificationsMenu:OnCreate' => 'When an object is created',
	'UI:NotificationsMenu:OnStateEnter' => 'When an object enters a given state',
	'UI:NotificationsMenu:OnStateLeave' => 'When an object leaves a given state',
	'UI:NotificationsMenu:Actions' => 'Actions',
	'UI:NotificationsMenu:Actions:ActionEmail' => 'Email actions',
	'UI:NotificationsMenu:Actions:ActionWebhook' => 'Webhook actions (outgoing integrations)',
	'UI:NotificationsMenu:Actions:Action' => 'Other actions',
	'UI:NotificationsMenu:AvailableActions' => 'Available actions',

	'Menu:TagAdminMenu' => 'Tags configuration',
	'Menu:TagAdminMenu+' => 'Tags values management',
	'UI:TagAdminMenu:Title' => 'Tags configuration',
	'UI:TagAdminMenu:NoTags' => 'No Tag field configured',
	'UI:TagSetFieldData:Error' => 'Error: %1$s',

	'Menu:AuditCategories' => 'Audit Categories',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AuditCategories+' => 'Audit Categories',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:Notifications:Title' => 'Audit Categories',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:RunQueriesMenu' => 'Run Queries',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:RunQueriesMenu+' => 'Run any query',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:QueryMenu' => 'Query phrasebook',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:QueryMenu+' => 'Query phrasebook',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:DataAdministration' => 'Data Administration',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataAdministration+' => 'Data Administration',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UniversalSearchMenu' => 'Universal Search',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UniversalSearchMenu+' => 'Search for anything...',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UserManagementMenu' => 'User Management',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserManagementMenu+' => 'User management',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:ProfilesMenu' => 'Profiles',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ProfilesMenu+' => 'Profiles',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ProfilesMenu:Title' => 'Profiles',
	// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UserAccountsMenu' => 'User Accounts',
	// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserAccountsMenu+' => 'User Accounts',
	// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserAccountsMenu:Title' => 'User Accounts',
	// Duplicated into itop-welcome-itil (will be removed from here...)

	'UI:iTopVersion:Short' => '%1$s version %2$s',
	'UI:iTopVersion:Long' => '%1$s version %2$s-%3$s built on %4$s',
	'UI:PropertiesTab' => 'Properties',

	'UI:OpenDocumentInNewWindow_' => 'Open',
	'UI:DownloadDocument_' => 'Download',
	'UI:Document:NoPreview' => 'No preview is available for this type of document',
	'UI:Download-CSV' => 'Download %1$s',

	'UI:DeadlineMissedBy_duration' => 'Missed  by %1$s',
	'UI:Deadline_LessThan1Min' => '< 1 min',
	'UI:Deadline_Minutes' => '%1$d min',
	'UI:Deadline_Hours_Minutes' => '%1$dh %2$dmin',
	'UI:Deadline_Days_Hours_Minutes' => '%1$dd %2$dh %3$dmin',
	'UI:Help' => 'Help',
	'UI:PasswordConfirm' => 'Confirm',
	'UI:BeforeAdding_Class_ObjectsSaveThisObject' => 'Before adding more %1$s objects, save this object.',
	'UI:DisplayThisMessageAtStartup' => 'Display this message at startup',
	'UI:RelationshipGraph' => 'Graphical view',
	'UI:RelationshipList' => 'List',
	'UI:RelationGroups' => 'Groups',
	'UI:OperationCancelled' => 'Operation Cancelled',
	'UI:ElementsDisplayed' => 'Filtering',
	'UI:RelationGroupNumber_N' => 'Group #%1$d',
	'UI:Relation:ExportAsPDF' => 'Export as PDF...',
	'UI:RelationOption:GroupingThreshold' => 'Grouping threshold',
	'UI:Relation:AdditionalContextInfo' => 'Additional context info',
	'UI:Relation:NoneSelected' => 'None',
	'UI:Relation:Zoom' => 'Zoom',
	'UI:Relation:ExportAsAttachment' => 'Export as Attachment...',
	'UI:Relation:DrillDown' => 'Details...',
	'UI:Relation:PDFExportOptions' => 'PDF Export Options',
	'UI:Relation:AttachmentExportOptions_Name' => 'Options for Attachment to %1$s',
	'UI:RelationOption:Untitled' => 'Untitled',
	'UI:Relation:Key' => 'Key',
	'UI:Relation:Comments' => 'Comments',
	'UI:RelationOption:Title' => 'Title',
	'UI:RelationOption:IncludeList' => 'Include the list of objects',
	'UI:RelationOption:Comments' => 'Comments',
	'UI:Button:Export' => 'Export',
	'UI:Relation:PDFExportPageFormat' => 'Page format',
	'UI:PageFormat_A3' => 'A3',
	'UI:PageFormat_A4' => 'A4',
	'UI:PageFormat_Letter' => 'Letter',
	'UI:Relation:PDFExportPageOrientation' => 'Page orientation',
	'UI:PageOrientation_Portrait' => 'Portrait',
	'UI:PageOrientation_Landscape' => 'Landscape',
	'UI:RelationTooltip:Redundancy' => 'Redundancy',
	'UI:RelationTooltip:ImpactedItems_N_of_M' => '# of impacted items: %1$d / %2$d',
	'UI:RelationTooltip:CriticalThreshold_N_of_M' => 'Critical threshold: %1$d / %2$d',
	'Portal:Title' => ITOP_APPLICATION_SHORT.' user portal',
	'Portal:NoRequestMgmt' => 'Dear %1$s, you have been redirected to this page because your account is configured with the profile \'Portal user\'. Unfortunately, '.ITOP_APPLICATION_SHORT.' has not been installed with the feature \'Request Management\'. Please contact your administrator.',
	'Portal:Refresh' => 'Refresh',
	'Portal:Back' => 'Back',
	'Portal:WelcomeUserOrg' => 'Welcome %1$s, from %2$s',
	'Portal:TitleDetailsFor_Request' => 'Details for request',
	'Portal:ShowOngoing' => 'Show open requests',
	'Portal:ShowClosed' => 'Show closed requests',
	'Portal:CreateNewRequest' => 'Create a new request',
	'Portal:CreateNewRequestItil' => 'Create a new request',
	'Portal:CreateNewIncidentItil' => 'Create a new incident report',
	'Portal:ChangeMyPassword' => 'Change my password',
	'Portal:Disconnect' => 'Disconnect',
	'Portal:OpenRequests' => 'My open requests',
	'Portal:ClosedRequests' => 'My closed requests',
	'Portal:ResolvedRequests' => 'My resolved requests',
	'Portal:SelectService' => 'Select a service from the catalog:',
	'Portal:PleaseSelectOneService' => 'Please select one service',
	'Portal:SelectSubcategoryFrom_Service' => 'Select a sub-category for the service %1$s:',
	'Portal:PleaseSelectAServiceSubCategory' => 'Please select one sub-category',
	'Portal:DescriptionOfTheRequest' => 'Enter the description of your request:',
	'Portal:TitleRequestDetailsFor_Request' => 'Details for request %1$s:',
	'Portal:NoOpenRequest' => 'No request in this category',
	'Portal:NoClosedRequest' => 'No request in this category',
	'Portal:Button:ReopenTicket' => 'Reopen this ticket',
	'Portal:Button:CloseTicket' => 'Close this ticket',
	'Portal:Button:UpdateRequest' => 'Update the request',
	'Portal:EnterYourCommentsOnTicket' => 'Enter your comments about the resolution of this ticket:',
	'Portal:ErrorNoContactForThisUser' => 'Error: the current user is not associated with a Contact/Person. Please contact your administrator.',
	'Portal:Attachments' => 'Attachments',
	'Portal:AddAttachment' => ' Add Attachment ',
	'Portal:RemoveAttachment' => ' Remove Attachment ',
	'Portal:Attachment_No_To_Ticket_Name' => 'Attachment #%1$d to %2$s (%3$s)',
	'Portal:SelectRequestTemplate' => 'Select a template for %1$s',
	'Enum:Undefined' => 'Undefined',
	'UI:DurationForm_Days_Hours_Minutes_Seconds' => '%1$s d %2$s h %3$s min %4$s s',
	'UI:ModifyAllPageTitle' => 'Modify All',
	'UI:Modify_N_ObjectsOf_Class' => 'Modifying %1$d objects of class %2$s',
	'UI:Modify_M_ObjectsOf_Class_OutOf_N' => 'Modifying %1$d objects of class %2$s out of %3$d',
	'UI:Menu:ModifyAll' => 'Modify...',
	'UI:Button:ModifyAll' => 'Modify All',
	'UI:Button:PreviewModifications' => 'Preview Modifications >>',
	'UI:ModifiedObject' => 'Object Modified',
	'UI:BulkModifyStatus' => 'Operation',
	'UI:BulkModifyStatus+' => 'Status of the operation',
	'UI:BulkModifyErrors' => 'Errors (if any)',
	'UI:BulkModifyErrors+' => 'Errors preventing the modification',
	'UI:BulkModifyStatusOk' => 'Ok',
	'UI:BulkModifyStatusError' => 'Error',
	'UI:BulkModifyStatusModified' => 'Modified',
	'UI:BulkModifyStatusSkipped' => 'Skipped',
	'UI:BulkModify_Count_DistinctValues' => '%1$d distinct values:',
	'UI:BulkModify:Value_Exists_N_Times' => '%1$s, %2$d time(s)',
	'UI:BulkModify:N_MoreValues' => '%1$d more values...',
	'UI:AttemptingToSetAReadOnlyAttribute_Name' => 'Attempting to set the read-only field: %1$s',
	'UI:FailedToApplyStimuli' => 'The action has failed.',
	'UI:StimulusModify_N_ObjectsOf_Class' => '%1$s: Modifying %2$d objects of class %3$s',
	'UI:CaseLogTypeYourTextHere' => 'Type your text here...',
	'UI:CaseLog:Header_Date_UserName' => '%1$s - %2$s:',
	'UI:CaseLog:InitialValue' => 'Initial value:',
	'UI:AttemptingToSetASlaveAttribute_Name' => 'The field %1$s (%2$s) is not writable because it is mastered by the data synchronization. Value not set.',
	'UI:ActionNotAllowed' => 'You are not allowed to perform this action on these objects.',
	'UI:BulkAction:NoObjectSelected' => 'Please select at least one object to perform this operation',
	'UI:AttemptingToChangeASlaveAttribute_Name' => 'The field %1$s is not writable because it is mastered by the data synchronization. Value remains unchanged.',
	'UI:Pagination:HeaderSelection' => 'Total: %1$s objects (%2$s objects selected).',
	'UI:Pagination:HeaderNoSelection' => 'Total: %1$s objects.',
	'UI:Pagination:PageSize' => '%1$s objects per page',
	'UI:Pagination:PagesLabel' => 'Pages:',
	'UI:Pagination:All' => 'All',
	'UI:HierarchyOf_Class' => 'Hierarchy of %1$s',
	'UI:Preferences' => 'Preferences...',
	'UI:ArchiveModeOn' => 'Activate archive mode',
	'UI:ArchiveModeOff' => 'Deactivate archive mode',
	'UI:ArchiveMode:Banner' => 'Archive mode',
	'UI:ArchiveMode:Banner+' => 'Archived objects are visible, and no modification is allowed',
	'UI:FavoriteOrganizations' => 'Favorite Organizations',
	'UI:FavoriteOrganizations+' => 'Check in the list below the organizations that you want to see in the drop-down menu for a quick access. '.
		'Note that this is not a security setting, objects from any organization are still visible and can be accessed by selecting "All Organizations" in the drop-down list.',
	'UI:FavoriteLanguage' => 'Favorite language',
	'UI:Favorites:SelectYourLanguage' => 'Select your preferred language',
	'UI:FavoriteOtherSettings' => 'Other Settings',
	'UI:Favorites:Default_X_ItemsPerPage' => 'Default length:  %1$s items per page',
	'UI:Favorites:ShowObsoleteData' => 'Show obsolete data',
	'UI:Favorites:ShowObsoleteData+' => 'Show obsolete data in search results and lists of items to select',
	'UI:NavigateAwayConfirmationMessage' => 'Any modification will be discarded.',
	'UI:CancelConfirmationMessage' => 'You will loose your changes. Continue anyway?',
	'UI:AutoApplyConfirmationMessage' => 'Some changes have not been applied yet. Do you want '.ITOP_APPLICATION_SHORT.' to take them into account?',
	'UI:Create_Class_InState' => 'Create the %1$s in state: ',
	'UI:OrderByHint_Values' => 'Sort order: %1$s',
	'UI:Menu:AddToDashboard' => 'Add To Dashboard...',
	'UI:Button:Refresh' => 'Refresh',
	'UI:Button:GoPrint' => 'Print...',
	'UI:ExplainPrintable' => 'Click onto the %1$s icon to hide items from the print.<br/>Use the "print preview" feature of your browser to preview before printing.<br/>Note: this header and the other tuning controls will not be printed.',
	'UI:PrintResolution:FullSize' => 'Full size',
	'UI:PrintResolution:A4Portrait' => 'A4 Portrait',
	'UI:PrintResolution:A4Landscape' => 'A4 Landscape',
	'UI:PrintResolution:LetterPortrait' => 'Letter Portrait',
	'UI:PrintResolution:LetterLandscape' => 'Letter Landscape',
	'UI:Toggle:SwitchToStandardDashboard' => 'Switch to standard dashboard',
	'UI:Toggle:SwitchToCustomDashboard' => 'Switch to custom dashboard',

	'UI:ConfigureThisList' => 'Configure This List...',
	'UI:ListConfigurationTitle' => 'List Configuration',
	'UI:ColumnsAndSortOrder' => 'Columns and sort order:',
	'UI:UseDefaultSettings' => 'Use the Default Settings',
	'UI:UseSpecificSettings' => 'Use the Following Settings:',
	'UI:Display_X_ItemsPerPage_prefix' => 'Display',
	'UI:Display_X_ItemsPerPage_suffix' => 'items per page',
	'UI:UseSavetheSettings' => 'Save the Settings',
	'UI:OnlyForThisList' => 'Only for this list',
	'UI:ForAllLists' => 'Default for all lists',
	'UI:ExtKey_AsLink' => '%1$s (Link)',
	'UI:ExtKey_AsFriendlyName' => '%1$s (Friendly Name)',
	'UI:ExtField_AsRemoteField' => '%1$s (%2$s)',
	'UI:Button:MoveUp' => 'Move Up',
	'UI:Button:MoveDown' => 'Move Down',

	'UI:OQL:UnknownClassAndFix' => 'Unknown class "%1$s". You may try "%2$s" instead.',
	'UI:OQL:UnknownClassNoFix' => 'Unknown class "%1$s"',

	'UI:Dashboard:EditCustom' => 'Edit custom version...',
	'UI:Dashboard:CreateCustom' => 'Create a custom version...',
	'UI:Dashboard:DeleteCustom' => 'Delete custom version...',
	'UI:Dashboard:RevertConfirm' => 'Every changes made to the original version will be lost. Please confirm that you want to do this.',
	'UI:ExportDashBoard' => 'Export to a file',
	'UI:ImportDashBoard' => 'Import from a file...',
	'UI:ImportDashboardTitle' => 'Import From a File',
	'UI:ImportDashboardText' => 'Select a dashboard file to import:',
	'UI:Dashboard:Actions' => 'Dashboard actions',
	'UI:Dashboard:NotUpToDateUntilContainerSaved' => 'This dashboard displays information that does not include the on-going changes.',


	'UI:DashletCreation:Title' => 'Create a new Dashlet',
	'UI:DashletCreation:Dashboard' => 'Dashboard',
	'UI:DashletCreation:DashletType' => 'Dashlet Type',
	'UI:DashletCreation:EditNow' => 'Edit the Dashboard',

	'UI:DashboardEdit:Title' => 'Dashboard Editor',
	'UI:DashboardEdit:DashboardTitle' => 'Title',
	'UI:DashboardEdit:AutoReload' => 'Automatic refresh',
	'UI:DashboardEdit:AutoReloadSec' => 'Automatic refresh interval (seconds)',
	'UI:DashboardEdit:AutoReloadSec+' => 'The minimum allowed is %1$d seconds',
	'UI:DashboardEdit:Revert' => 'Revert',
	'UI:DashboardEdit:Apply' => 'Apply',

	'UI:DashboardEdit:Layout' => 'Layout',
	'UI:DashboardEdit:Properties' => 'Dashboard Properties',
	'UI:DashboardEdit:Dashlets' => 'Available Dashlets',
	'UI:DashboardEdit:DashletProperties' => 'Dashlet Properties',

	'UI:Form:Property' => 'Property',
	'UI:Form:Value' => 'Value',

	'UI:DashletUnknown:Label' => 'Unknown',
	'UI:DashletUnknown:Description' => 'Unknown dashlet (might have been uninstalled)',
	'UI:DashletUnknown:RenderText:View' => 'Unable to render this dashlet.',
	'UI:DashletUnknown:RenderText:Edit' => 'Unable to render this dashlet (class "%1$s"). Check with your administrator if it is still available.',
	'UI:DashletUnknown:RenderNoDataText:Edit' => 'No preview available for this dashlet (class "%1$s").',
	'UI:DashletUnknown:Prop-XMLConfiguration' => 'Configuration (shown as raw XML)',

	'UI:DashletProxy:Label' => 'Proxy',
	'UI:DashletProxy:Description' => 'Proxy dashlet',
	'UI:DashletProxy:RenderNoDataText:Edit' => 'No preview available for this third-party dashlet (class "%1$s").',
	'UI:DashletProxy:Prop-XMLConfiguration' => 'Configuration (shown as raw XML)',

	'UI:DashletPlainText:Label' => 'Text',
	'UI:DashletPlainText:Description' => 'Plain text (no formatting)',
	'UI:DashletPlainText:Prop-Text' => 'Text',
	'UI:DashletPlainText:Prop-Text:Default' => 'Please enter some text here...',

	'UI:DashletObjectList:Label' => 'Object list',
	'UI:DashletObjectList:Description' => 'Object list dashlet',
	'UI:DashletObjectList:Prop-Title' => 'Title',
	'UI:DashletObjectList:Prop-Query' => 'Query',
	'UI:DashletObjectList:Prop-Menu' => 'Menu',

	'UI:DashletGroupBy:Prop-Title' => 'Title',
	'UI:DashletGroupBy:Prop-Query' => 'Query',
	'UI:DashletGroupBy:Prop-Style' => 'Style',
	'UI:DashletGroupBy:Prop-GroupBy' => 'Group by...',
	'UI:DashletGroupBy:Prop-GroupBy:Hour' => 'Hour of %1$s (0-23)',
	'UI:DashletGroupBy:Prop-GroupBy:Month' => 'Month of %1$s (1 - 12)',
	'UI:DashletGroupBy:Prop-GroupBy:DayOfWeek' => 'Day of week for %1$s',
	'UI:DashletGroupBy:Prop-GroupBy:DayOfMonth' => 'Day of month for %1$s',
	'UI:DashletGroupBy:Prop-GroupBy:Select-Hour' => '%1$s (hour)',
	'UI:DashletGroupBy:Prop-GroupBy:Select-Month' => '%1$s (month)',
	'UI:DashletGroupBy:Prop-GroupBy:Select-DayOfWeek' => '%1$s (day of week)',
	'UI:DashletGroupBy:Prop-GroupBy:Select-DayOfMonth' => '%1$s (day of month)',
	'UI:DashletGroupBy:MissingGroupBy' => 'Please select the field on which the objects will be grouped together',

	'UI:DashletGroupByPie:Label' => 'Pie Chart',
	'UI:DashletGroupByPie:Description' => 'Pie Chart',
	'UI:DashletGroupByBars:Label' => 'Bar Chart',
	'UI:DashletGroupByBars:Description' => 'Bar Chart',
	'UI:DashletGroupByTable:Label' => 'Group By (table)',
	'UI:DashletGroupByTable:Description' => 'List (Grouped by a field)',

	// New in 2.5
	'UI:DashletGroupBy:Prop-Function' => 'Aggregation function',
	'UI:DashletGroupBy:Prop-FunctionAttribute' => 'Function attribute',
	'UI:DashletGroupBy:Prop-OrderDirection' => 'Direction',
	'UI:DashletGroupBy:Prop-OrderField' => 'Order by',
	'UI:DashletGroupBy:Prop-Limit' => 'Limit',

	'UI:DashletGroupBy:Order:asc' => 'Ascending',
	'UI:DashletGroupBy:Order:desc' => 'Descending',

	'UI:GroupBy:count' => 'Count',
	'UI:GroupBy:count+' => 'Number of elements',
	'UI:GroupBy:sum' => 'Sum',
	'UI:GroupBy:sum+' => 'Sum of %1$s',
	'UI:GroupBy:avg' => 'Average',
	'UI:GroupBy:avg+' => 'Average of %1$s',
	'UI:GroupBy:min' => 'Minimum',
	'UI:GroupBy:min+' => 'Minimum of %1$s',
	'UI:GroupBy:max' => 'Maximum',
	'UI:GroupBy:max+' => 'Maximum of %1$s',
	// ---

	'UI:DashletHeaderStatic:Label' => 'Header',
	'UI:DashletHeaderStatic:Description' => 'Displays an horizontal separator',
	'UI:DashletHeaderStatic:Prop-Title' => 'Title',
	'UI:DashletHeaderStatic:Prop-Title:Default' => 'Contacts',
	'UI:DashletHeaderStatic:Prop-Icon' => 'Icon',

	'UI:DashletHeaderDynamic:Label' => 'Header with statistics',
	'UI:DashletHeaderDynamic:Description' => 'Header with stats (grouped by...)',
	'UI:DashletHeaderDynamic:Prop-Title' => 'Title',
	'UI:DashletHeaderDynamic:Prop-Title:Default' => 'Contacts',
	'UI:DashletHeaderDynamic:Prop-Icon' => 'Icon',
	'UI:DashletHeaderDynamic:Prop-Subtitle' => 'Subtitle',
	'UI:DashletHeaderDynamic:Prop-Subtitle:Default' => 'Contacts',
	'UI:DashletHeaderDynamic:Prop-Query' => 'Query',
	'UI:DashletHeaderDynamic:Prop-GroupBy' => 'Group by',
	'UI:DashletHeaderDynamic:Prop-Values' => 'Values',

	'UI:DashletBadge:Label' => 'Badge',
	'UI:DashletBadge:Description' => 'Object Icon with new/search',
	'UI:DashletBadge:Prop-Class' => 'Class',

	'DayOfWeek-Sunday' => 'Sunday',
	'DayOfWeek-Monday' => 'Monday',
	'DayOfWeek-Tuesday' => 'Tuesday',
	'DayOfWeek-Wednesday' => 'Wednesday',
	'DayOfWeek-Thursday' => 'Thursday',
	'DayOfWeek-Friday' => 'Friday',
	'DayOfWeek-Saturday' => 'Saturday',
	'Month-01' => 'January',
	'Month-02' => 'February',
	'Month-03' => 'March',
	'Month-04' => 'April',
	'Month-05' => 'May',
	'Month-06' => 'June',
	'Month-07' => 'July',
	'Month-08' => 'August',
	'Month-09' => 'September',
	'Month-10' => 'October',
	'Month-11' => 'November',
	'Month-12' => 'December',

	// Short version for the DatePicker
	'DayOfWeek-Sunday-Min' => 'Su',
	'DayOfWeek-Monday-Min' => 'Mo',
	'DayOfWeek-Tuesday-Min' => 'Tu',
	'DayOfWeek-Wednesday-Min' => 'We',
	'DayOfWeek-Thursday-Min' => 'Th',
	'DayOfWeek-Friday-Min' => 'Fr',
	'DayOfWeek-Saturday-Min' => 'Sa',
	'Month-01-Short' => 'Jan',
	'Month-02-Short' => 'Feb',
	'Month-03-Short' => 'Mar',
	'Month-04-Short' => 'Apr',
	'Month-05-Short' => 'May',
	'Month-06-Short' => 'Jun',
	'Month-07-Short' => 'Jul',
	'Month-08-Short' => 'Aug',
	'Month-09-Short' => 'Sep',
	'Month-10-Short' => 'Oct',
	'Month-11-Short' => 'Nov',
	'Month-12-Short' => 'Dec',
	'Calendar-FirstDayOfWeek' => 0,// 0 = Sunday, 1 = Monday, etc...

	'UI:Menu:ShortcutList' => 'Create a Shortcut...',
	'UI:ShortcutRenameDlg:Title' => 'Rename the shortcut',
	'UI:ShortcutListDlg:Title' => 'Create a shortcut for the list',
	'UI:ShortcutDelete:Confirm' => 'Please confirm that wou wish to delete the shortcut(s).',
	'Menu:MyShortcuts' => 'My Shortcuts',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Class:Shortcut' => 'Shortcut',
	'Class:Shortcut+' => '',
	'Class:Shortcut/Attribute:name' => 'Name',
	'Class:Shortcut/Attribute:name+' => 'Label used in the menu and page title',
	'Class:ShortcutOQL' => 'Search result shortcut',
	'Class:ShortcutOQL+' => '',
	'Class:ShortcutOQL/Attribute:oql' => 'Query',
	'Class:ShortcutOQL/Attribute:oql+' => 'OQL defining the list of objects to search for',
	'Class:ShortcutOQL/Attribute:auto_reload' => 'Automatic refresh',
	'Class:ShortcutOQL/Attribute:auto_reload/Value:none' => 'Disabled',
	'Class:ShortcutOQL/Attribute:auto_reload/Value:custom' => 'Custom rate',
	'Class:ShortcutOQL/Attribute:auto_reload_sec' => 'Automatic refresh interval (seconds)',
	'Class:ShortcutOQL/Attribute:auto_reload_sec/tip' => 'The minimum allowed is %1$d seconds',

	'UI:FillAllMandatoryFields' => 'Please fill all mandatory fields.',
	'UI:ValueMustBeSet' => 'Please specify a value',
	'UI:ValueMustBeChanged' => 'Please change the value',
	'UI:ValueInvalidFormat' => 'Invalid format',

	'UI:CSVImportConfirmTitle' => 'Please confirm the operation',
	'UI:CSVImportConfirmMessage' => 'Are you sure you want to do this?',
	'UI:CSVImportError_items' => 'Errors: %1$d',
	'UI:CSVImportCreated_items' => 'Created: %1$d',
	'UI:CSVImportModified_items' => 'Modified: %1$d',
	'UI:CSVImportUnchanged_items' => 'Unchanged: %1$d',
	'UI:CSVImport:DateAndTimeFormats' => 'Date and time format',
	'UI:CSVImport:DefaultDateTimeFormat_Format_Example' => 'Default format: %1$s (e.g. %2$s)',
	'UI:CSVImport:CustomDateTimeFormat' => 'Custom format: %1$s',
	'UI:CSVImport:CustomDateTimeFormatTooltip' => 'Available placeholders:<table>
<tr><td>Y</td><td>year (4 digits, e.g. 2016)</td></tr>
<tr><td>y</td><td>year (2 digits, e.g. 16 for 2016)</td></tr>
<tr><td>m</td><td>month (2 digits, e.g. 01..12)</td></tr>
<tr><td>n</td><td>month (1 or 2 digits no leading zero, e.g. 1..12)</td></tr>
<tr><td>d</td><td>day (2 digits, e.g. 01..31)</td></tr>
<tr><td>j</td><td>day (1 or 2 digits no leading zero, e.g. 1..31)</td></tr>
<tr><td>H</td><td>hour (24 hour, 2 digits, e.g. 00..23)</td></tr>
<tr><td>h</td><td>hour (12 hour, 2 digits, e.g. 01..12)</td></tr>
<tr><td>G</td><td>hour (24 hour, 1 or 2 digits no leading zero, e.g. 0..23)</td></tr>
<tr><td>g</td><td>hour (12 hour, 1 or 2 digits no leading zero, e.g. 1..12)</td></tr>
<tr><td>a</td><td>hour, am or pm (lowercase)</td></tr>
<tr><td>A</td><td>hour, AM or PM (uppercase)</td></tr>
<tr><td>i</td><td>minutes (2 digits, e.g. 00..59)</td></tr>
<tr><td>s</td><td>seconds (2 digits, e.g. 00..59)</td></tr>
</table>',

	'UI:Button:Remove' => 'Remove',
	'UI:AddAnExisting_Class' => 'Add objects of type %1$s...',
	'UI:SelectionOf_Class' => 'Selection of objects of type %1$s',

	'UI:AboutBox' => 'About '.ITOP_APPLICATION_SHORT.'...',
	'UI:About:Title' => 'About '.ITOP_APPLICATION_SHORT,
	'UI:About:DataModel' => 'Data model',
	'UI:About:Support' => 'Support information',
	'UI:About:Licenses' => 'Licenses',
	'UI:About:InstallationOptions' => 'Installation options',
	'UI:About:ManualExtensionSource' => 'Extension',
	'UI:About:Extension_Version' => 'Version: %1$s',
	'UI:About:RemoteExtensionSource' => 'Data',

	'UI:DisconnectedDlgMessage' => 'You are disconnected. You must identify yourself to continue using the application.',
	'UI:DisconnectedDlgTitle' => 'Warning!',
	'UI:LoginAgain' => 'Login again',
	'UI:StayOnThePage' => 'Stay on this page',

	'ExcelExporter:ExportMenu' => 'Excel Export...',
	'ExcelExporter:ExportDialogTitle' => 'Excel Export',
	'ExcelExporter:ExportButton' => 'Export',
	'ExcelExporter:DownloadButton' => 'Download %1$s',
	'ExcelExporter:RetrievingData' => 'Retrieving data...',
	'ExcelExporter:BuildingExcelFile' => 'Building the Excel file...',
	'ExcelExporter:Done' => 'Done.',
	'ExcelExport:AutoDownload' => 'Start the download automatically when the export is ready',
	'ExcelExport:PreparingExport' => 'Preparing the export...',
	'ExcelExport:Statistics' => 'Statistics',
	'portal:legacy_portal' => 'End-User Portal',
	'portal:backoffice' => ITOP_APPLICATION_SHORT.' Back-Office User Interface',

	'UI:CurrentObjectIsLockedBy_User' => 'The object is locked since it is currently being modified by %1$s.',
	'UI:CurrentObjectIsLockedBy_User_Explanation' => 'The object is currently being modified by %1$s. Your modifications cannot be submitted since they would be overwritten.',
	'UI:CurrentObjectIsSoftLockedBy_User' => 'The object is currently being modified by %1$s. You\'ll be able to submit your modifications once they have finished.',
	'UI:CurrentObjectLockExpired' => 'The lock to prevent concurrent modifications of the object has expired.',
	'UI:CurrentObjectLockExpired_Explanation' => 'The lock to prevent concurrent modifications of the object has expired. You can no longer submit your modification since other users are now allowed to modify this object.',
	'UI:ConcurrentLockKilled' => 'The lock preventing modifications on the current object has been deleted.',
	'UI:Menu:KillConcurrentLock' => 'Kill the Concurrent Modification Lock !',

	'UI:Menu:ExportPDF' => 'Export as PDF...',
	'UI:Menu:PrintableVersion' => 'Printer friendly version',

	'UI:BrowseInlineImages' => 'Browse images...',
	'UI:UploadInlineImageLegend' => 'Upload a new image',
	'UI:SelectInlineImageToUpload' => 'Select the image to upload',
	'UI:AvailableInlineImagesLegend' => 'Available images',
	'UI:NoInlineImage' => 'There is no image available on the server. Use the "Browse" button above to select an image from your computer and upload it to the server.',

	'UI:ToggleFullScreen' => 'Toggle Maximize / Minimize',
	'UI:Button:ResetImage' => 'Recover the previous image',
	'UI:Button:RemoveImage' => 'Remove the image',
	'UI:Button:UploadImage' => 'Upload an image from the disk',
	'UI:UploadNotSupportedInThisMode' => 'The modification of images or files is not supported in this mode.',

	'UI:Button:RemoveDocument' => 'Remove the document',

	// Search form
	'UI:Search:Toggle' => 'Minimize / Expand',
	'UI:Search:AutoSubmit:DisabledHint' => 'Auto submit has been disabled for this class',
	'UI:Search:Obsolescence:DisabledHint' => 'Based on your preferences, obsolete data are hidden',
	'UI:Search:NoAutoSubmit:ExplainText' => 'Add some criterion on the search box or click the search button to view the objects.',
	'UI:Search:Criterion:MoreMenu:AddCriteria' => 'Add new criteria',
	// - Add new criteria button
	'UI:Search:AddCriteria:List:RecentlyUsed:Title' => 'Recently used',
	'UI:Search:AddCriteria:List:MostPopular:Title' => 'Most popular',
	'UI:Search:AddCriteria:List:Others:Title' => 'Others',
	'UI:Search:AddCriteria:List:RecentlyUsed:Placeholder' => 'None yet.',

	// - Criteria header actions
	'UI:Search:Criteria:Toggle' => 'Minimize / Expand',
	'UI:Search:Criteria:Remove' => 'Remove',
	'UI:Search:Criteria:Locked' => 'Locked',

	// - Criteria titles
	//   - Default widget
	'UI:Search:Criteria:Title:Default:Any' => '%1$s: Any',
	'UI:Search:Criteria:Title:Default:Empty' => '%1$s is empty',
	'UI:Search:Criteria:Title:Default:NotEmpty' => '%1$s is not empty',
	'UI:Search:Criteria:Title:Default:Equals' => '%1$s equals %2$s',
	'UI:Search:Criteria:Title:Default:Contains' => '%1$s contains %2$s',
	'UI:Search:Criteria:Title:Default:StartsWith' => '%1$s starts with %2$s',
	'UI:Search:Criteria:Title:Default:EndsWith' => '%1$s ends with %2$s',
	'UI:Search:Criteria:Title:Default:RegExp' => '%1$s matches %2$s',
	'UI:Search:Criteria:Title:Default:GreaterThan' => '%1$s > %2$s',
	'UI:Search:Criteria:Title:Default:GreaterThanOrEquals' => '%1$s >= %2$s',
	'UI:Search:Criteria:Title:Default:LessThan' => '%1$s < %2$s',
	'UI:Search:Criteria:Title:Default:LessThanOrEquals' => '%1$s <= %2$s',
	'UI:Search:Criteria:Title:Default:Different' => '%1$s ≠ %2$s',
	'UI:Search:Criteria:Title:Default:Between' => '%1$s between [%2$s]',
	'UI:Search:Criteria:Title:Default:BetweenDates' => '%1$s [%2$s]',
	'UI:Search:Criteria:Title:Default:BetweenDates:All' => '%1$s: Any',
	'UI:Search:Criteria:Title:Default:BetweenDates:From' => '%1$s from %2$s',
	'UI:Search:Criteria:Title:Default:BetweenDates:Until' => '%1$s until %2$s',
	'UI:Search:Criteria:Title:Default:Between:All' => '%1$s: Any',
	'UI:Search:Criteria:Title:Default:Between:From' => '%1$s from %2$s',
	'UI:Search:Criteria:Title:Default:Between:Until' => '%1$s up to %2$s',
	//   - Numeric widget
	//   None yet
	//   - DateTime widget
	'UI:Search:Criteria:Title:DateTime:Between' => '%2$s <= 1$s <= %3$s',
	//   - Enum widget
	'UI:Search:Criteria:Title:Enum:In' => '%1$s: %2$s',
	'UI:Search:Criteria:Title:Enum:In:Many' => '%1$s: %2$s and %3$s others',
	'UI:Search:Criteria:Title:Enum:In:All' => '%1$s: Any',
	//   - TagSet widget
	'UI:Search:Criteria:Title:TagSet:Matches' => '%1$s: %2$s',
	//   - External key widget
	'UI:Search:Criteria:Title:ExternalKey:Empty' => '%1$s is defined',
	'UI:Search:Criteria:Title:ExternalKey:NotEmpty' => '%1$s is not defined',
	'UI:Search:Criteria:Title:ExternalKey:Equals' => '%1$s %2$s',
	'UI:Search:Criteria:Title:ExternalKey:In' => '%1$s: %2$s',
	'UI:Search:Criteria:Title:ExternalKey:In:Many' => '%1$s: %2$s and %3$s others',
	'UI:Search:Criteria:Title:ExternalKey:In:All' => '%1$s: Any',
	//   - Hierarchical key widget
	'UI:Search:Criteria:Title:HierarchicalKey:Empty' => '%1$s is defined',
	'UI:Search:Criteria:Title:HierarchicalKey:NotEmpty' => '%1$s is not defined',
	'UI:Search:Criteria:Title:HierarchicalKey:Equals' => '%1$s %2$s',
	'UI:Search:Criteria:Title:HierarchicalKey:In' => '%1$s: %2$s',
	'UI:Search:Criteria:Title:HierarchicalKey:In:Many' => '%1$s: %2$s and %3$s others',
	'UI:Search:Criteria:Title:HierarchicalKey:In:All' => '%1$s: Any',

	// - Criteria operators
	//   - Default widget
	'UI:Search:Criteria:Operator:Default:Empty' => 'Is empty',
	'UI:Search:Criteria:Operator:Default:NotEmpty' => 'Is not empty',
	'UI:Search:Criteria:Operator:Default:Equals' => 'Equals',
	'UI:Search:Criteria:Operator:Default:Between' => 'Between',
	//   - String widget
	'UI:Search:Criteria:Operator:String:Contains' => 'Contains',
	'UI:Search:Criteria:Operator:String:StartsWith' => 'Starts with',
	'UI:Search:Criteria:Operator:String:EndsWith' => 'Ends with',
	'UI:Search:Criteria:Operator:String:RegExp' => 'Regular exp.',
	//   - Numeric widget
	'UI:Search:Criteria:Operator:Numeric:Equals' => 'Equals',// => '=',
	'UI:Search:Criteria:Operator:Numeric:GreaterThan' => 'Greater',// => '>',
	'UI:Search:Criteria:Operator:Numeric:GreaterThanOrEquals' => 'Greater / equals',// > '>=',
	'UI:Search:Criteria:Operator:Numeric:LessThan' => 'Less',// => '<',
	'UI:Search:Criteria:Operator:Numeric:LessThanOrEquals' => 'Less / equals',// > '<=',
	'UI:Search:Criteria:Operator:Numeric:Different' => 'Different',// => '≠',
	//   - Tag Set Widget
	'UI:Search:Criteria:Operator:TagSet:Matches' => 'Matches',

	// - Other translations
	'UI:Search:Value:Filter:Placeholder' => 'Filter...',
	'UI:Search:Value:Search:Placeholder' => 'Search...',
	'UI:Search:Value:Autocomplete:StartTyping' => 'Start typing for possible values.',
	'UI:Search:Value:Autocomplete:Wait' => 'Please wait...',
	'UI:Search:Value:Autocomplete:NoResult' => 'No result.',
	'UI:Search:Value:Toggler:CheckAllNone' => 'Check all / none',
	'UI:Search:Value:Toggler:CheckAllNoneFiltered' => 'Check all / none visibles',

	// - Widget other translations
	'UI:Search:Criteria:Numeric:From' => 'From',
	'UI:Search:Criteria:Numeric:Until' => 'To',
	'UI:Search:Criteria:Numeric:PlaceholderFrom' => 'Any',
	'UI:Search:Criteria:Numeric:PlaceholderUntil' => 'Any',
	'UI:Search:Criteria:DateTime:From' => 'From',
	'UI:Search:Criteria:DateTime:FromTime' => 'From',
	'UI:Search:Criteria:DateTime:Until' => 'until',
	'UI:Search:Criteria:DateTime:UntilTime' => 'until',
	'UI:Search:Criteria:DateTime:PlaceholderFrom' => 'Any date',
	'UI:Search:Criteria:DateTime:PlaceholderFromTime' => 'Any date',
	'UI:Search:Criteria:DateTime:PlaceholderUntil' => 'Any date',
	'UI:Search:Criteria:DateTime:PlaceholderUntilTime' => 'Any date',
	'UI:Search:Criteria:HierarchicalKey:ChildrenIncluded:Hint' => 'Children of the selected objects will be included.',

	'UI:Search:Criteria:Raw:Filtered' => 'Filtered',
	'UI:Search:Criteria:Raw:FilteredOn' => 'Filtered on %1$s',

	'UI:StateChanged' => 'State changed',
));

//
// Expression to Natural language
//
Dict::Add('EN US', 'English', 'English', array(
	'Expression:Operator:AND' => ' AND ',
	'Expression:Operator:OR' => ' OR ',
	'Expression:Operator:=' => ': ',

	'Expression:Unit:Short:DAY' => 'd',
	'Expression:Unit:Short:WEEK' => 'w',
	'Expression:Unit:Short:MONTH' => 'm',
	'Expression:Unit:Short:YEAR' => 'y',

	'Expression:Unit:Long:DAY' => 'day(s)',
	'Expression:Unit:Long:HOUR' => 'hour(s)',
	'Expression:Unit:Long:MINUTE' => 'minute(s)',

	'Expression:Verb:NOW' => 'now',
	'Expression:Verb:ISNULL' => ': undefined',
));

//
// iTop Newsroom menu
//
Dict::Add('EN US', 'English', 'English', array(
	'UI:Newsroom:NoNewMessage' => 'No new message',
	'UI:Newsroom:XNewMessage' => '%1$s new message(s)',
	'UI:Newsroom:MarkAllAsRead' => 'Mark all messages as read',
	'UI:Newsroom:ViewAllMessages' => 'View all messages',
	'UI:Newsroom:Preferences' => 'Newsroom preferences',
	'UI:Newsroom:ConfigurationLink' => 'Configuration',
	'UI:Newsroom:ResetCache' => 'Reset cache',
	'UI:Newsroom:DisplayMessagesFor_Provider' => 'Display messages from %1$s',
	'UI:Newsroom:DisplayAtMost_X_Messages' => 'Display up to %1$s messages in the %2$s menu.',
));


Dict::Add('EN US', 'English', 'English', array(
	'Menu:DataSources' => 'Synchronization Data Sources',
	'Menu:DataSources+' => 'All Synchronization Data Sources',
	'Menu:WelcomeMenu' => 'Welcome',
	'Menu:WelcomeMenu+' => 'Welcome to '.ITOP_APPLICATION_SHORT,
	'Menu:WelcomeMenuPage' => 'Welcome',
	'Menu:WelcomeMenuPage+' => 'Welcome to '.ITOP_APPLICATION_SHORT,
	'Menu:AdminTools' => 'Administration',
	'Menu:AdminTools+' => 'Administration tools',
	'Menu:AdminTools?' => 'Tools accessible only to users having the administrator profile',
	'Menu:DataModelMenu' => 'Data Model',
	'Menu:DataModelMenu+' => 'Overview of the Data Model',
	'Menu:ExportMenu' => 'Export',
	'Menu:ExportMenu+' => 'Export the results of any query in HTML, CSV or XML',
	'Menu:NotificationsMenu' => 'Notifications',
	'Menu:NotificationsMenu+' => 'Configuration of the Notifications',
	'Menu:AuditCategories' => 'Audit Categories',
	'Menu:AuditCategories+' => 'Audit Categories',
	'Menu:Notifications:Title' => 'Audit Categories',
	'Menu:RunQueriesMenu' => 'Run Queries',
	'Menu:RunQueriesMenu+' => 'Run any query',
	'Menu:QueryMenu' => 'Query phrasebook',
	'Menu:QueryMenu+' => 'Query phrasebook',
	'Menu:UniversalSearchMenu' => 'Universal Search',
	'Menu:UniversalSearchMenu+' => 'Search for anything...',
	'Menu:UserManagementMenu' => 'User Management',
	'Menu:UserManagementMenu+' => 'User management',
	'Menu:ProfilesMenu' => 'Profiles',
	'Menu:ProfilesMenu+' => 'Profiles',
	'Menu:ProfilesMenu:Title' => 'Profiles',
	'Menu:UserAccountsMenu' => 'User Accounts',
	'Menu:UserAccountsMenu+' => 'User Accounts',
	'Menu:UserAccountsMenu:Title' => 'User Accounts',
	'Menu:MyShortcuts' => 'My Shortcuts',
	'Menu:UserManagement' => 'User Management',
	'Menu:Queries' => 'Queries',
	'Menu:ConfigurationTools' => 'Configuration',
));
