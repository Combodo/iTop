<?php

//////////////////////////////////////////////////////////////////////
// Classes in 'gui'
//////////////////////////////////////////////////////////////////////
//

//
// Class: menuNode
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:menuNode' => 'menuNode',
	'Class:menuNode+' => 'Main menu configuration elements',
	'Class:menuNode/Attribute:name' => 'Menu Name',
	'Class:menuNode/Attribute:name+' => 'Short name for this menu',
	'Class:menuNode/Attribute:label' => 'Menu Description',
	'Class:menuNode/Attribute:label+' => 'Long description for this menu',
	'Class:menuNode/Attribute:hyperlink' => 'Hyperlink',
	'Class:menuNode/Attribute:hyperlink+' => 'Hyperlink to the page',
	'Class:menuNode/Attribute:icon_path' => 'Menu Icon',
	'Class:menuNode/Attribute:icon_path+' => 'Path to the icon o the menu',
	'Class:menuNode/Attribute:template' => 'Template',
	'Class:menuNode/Attribute:template+' => 'HTML template for the view',
	'Class:menuNode/Attribute:type' => 'Type',
	'Class:menuNode/Attribute:type+' => 'Type of menu',
	'Class:menuNode/Attribute:type/Value:application' => 'application',
	'Class:menuNode/Attribute:type/Value:application+' => 'application',
	'Class:menuNode/Attribute:type/Value:user' => 'user',
	'Class:menuNode/Attribute:type/Value:user+' => 'user',
	'Class:menuNode/Attribute:type/Value:administrator' => 'administrator',
	'Class:menuNode/Attribute:type/Value:administrator+' => 'administrator',
	'Class:menuNode/Attribute:rank' => 'Display rank',
	'Class:menuNode/Attribute:rank+' => 'Sort order for displaying the menu',
	'Class:menuNode/Attribute:parent_id' => 'Parent Menu Item',
	'Class:menuNode/Attribute:parent_id+' => 'Parent Menu Item',
	'Class:menuNode/Attribute:parent_name' => 'Parent Menu Item',
	'Class:menuNode/Attribute:parent_name+' => 'Parent Menu Item',
	'Class:menuNode/Attribute:user_id' => 'Owner of the menu',
	'Class:menuNode/Attribute:user_id+' => 'User who owns this menu (for user defined menus)',
));

//////////////////////////////////////////////////////////////////////
// Classes in 'application'
//////////////////////////////////////////////////////////////////////
//

//
// Class: AuditCategory
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:AuditCategory' => 'AuditCategory',
	'Class:AuditCategory+' => 'A section inside the overall audit',
	'Class:AuditCategory/Attribute:name' => 'Category Name',
	'Class:AuditCategory/Attribute:name+' => 'Short name for this category',
	'Class:AuditCategory/Attribute:description' => 'Audit Category Description',
	'Class:AuditCategory/Attribute:description+' => 'Long description for this audit category',
	'Class:AuditCategory/Attribute:definition_set' => 'Definition Set',
	'Class:AuditCategory/Attribute:definition_set+' => 'SibusQL expression defining the set of objects to audit',
));

//
// Class: AuditRule
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:AuditRule' => 'AuditRule',
	'Class:AuditRule+' => 'A rule to check for a given Audit category',
	'Class:AuditRule/Attribute:name' => 'Rule Name',
	'Class:AuditRule/Attribute:name+' => 'Short name for this rule',
	'Class:AuditRule/Attribute:description' => 'Audit Rule Description',
	'Class:AuditRule/Attribute:description+' => 'Long description for this audit rule',
	'Class:AuditRule/Attribute:query' => 'Query to Run',
	'Class:AuditRule/Attribute:query+' => 'The SibusQL expression to run',
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

//////////////////////////////////////////////////////////////////////
// Classes in 'addon/userrights'
//////////////////////////////////////////////////////////////////////
//

//
// Class: URP_Users
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:URP_Users' => 'user',
	'Class:URP_Users+' => 'users and credentials',
	'Class:URP_Users/Attribute:userid' => 'Contact (person)',
	'Class:URP_Users/Attribute:userid+' => 'Personal details from the business data',
	'Class:URP_Users/Attribute:last_name' => 'Last name',
	'Class:URP_Users/Attribute:last_name+' => 'Name of the corresponding contact',
	'Class:URP_Users/Attribute:first_name' => 'First name',
	'Class:URP_Users/Attribute:first_name+' => 'First name of the corresponding contact',
	'Class:URP_Users/Attribute:email' => 'Email',
	'Class:URP_Users/Attribute:email+' => 'Email of the corresponding contact',
	'Class:URP_Users/Attribute:login' => 'Login',
	'Class:URP_Users/Attribute:login+' => 'user identification string',
	'Class:URP_Users/Attribute:password' => 'Password',
	'Class:URP_Users/Attribute:password+' => 'user authentication string',
	'Class:URP_Users/Attribute:language' => 'Language',
	'Class:URP_Users/Attribute:language+' => 'user language',
	'Class:URP_Users/Attribute:language/Value:EN US' => 'English',
	'Class:URP_Users/Attribute:language/Value:EN US+' => 'English U.S.',
	'Class:URP_Users/Attribute:language/Value:FR FR' => 'French',
	'Class:URP_Users/Attribute:language/Value:FR FR+' => 'FR FR',
	'Class:URP_Users/Attribute:profiles' => 'Profiles',
	'Class:URP_Users/Attribute:profiles+' => 'roles, granting rights for that person',
));

//
// Class: URP_Profiles
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:URP_Profiles' => 'profile',
	'Class:URP_Profiles+' => 'usage profiles',
	'Class:URP_Profiles/Attribute:name' => 'Name',
	'Class:URP_Profiles/Attribute:name+' => 'label',
	'Class:URP_Profiles/Attribute:description' => 'Description',
	'Class:URP_Profiles/Attribute:description+' => 'one line description',
	'Class:URP_Profiles/Attribute:users' => 'Users',
	'Class:URP_Profiles/Attribute:users+' => 'persons having this role',
));

//
// Class: URP_Dimensions
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:URP_Dimensions' => 'dimension',
	'Class:URP_Dimensions+' => 'application dimension (defining silos)',
	'Class:URP_Dimensions/Attribute:name' => 'Name',
	'Class:URP_Dimensions/Attribute:name+' => 'label',
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
// String from the User Interface: menu, messages, buttons, etc...
//

Dict::Add('EN US', 'English', 'English', array(
	'UI:WelcomeMenu' => 'Welcome',
	'UI:WelcomeMenu+' => 'Welcome to iTop',
	'UI:WelcomeMenu:Title' => 'Welcome to iTop',

	'UI:WelcomeMenu:LeftBlock' => '<p>iTop is a complete, OpenSource, IT Operational Portal.</p>
<ul>It includes:
<li>A complete CMDB (Configuration management database) to document and manage the IT inventory.</li>
<li>An Incident management module to track and communicate about all issues occurring in the IT.</li>
<li>A change management module to plan and track the changes to the IT environment.</li>
<li>A known error database to speed up the resolution of incidents.</li>
<li>An outage module to document all planned outages and notify the appropriate contacts.</li>
<li>Dashboards to quickly get an overview of your IT.</li>
</ul>
<p>All the modules can be setup, step by step, indepently of each other.</p>',

	'UI:WelcomeMenu:RightBlock' => '<p>iTop is service provider oriented, it allows IT engineers to manage easily multiple customers or organizations.
<ul>iTop, delivers a feature-rich set of business processes that:
<li>Enhances IT management effectiveness</li> 
<li>Drives IT operations performance</li> 
<li>Improves customer satisfaction and provides executives with insights into business performance.</li>
</ul>
</p>
<p>iTop is completely opened to be integrated within your current IT Management infrastructure.</p>
<p>
<ul>Adopting this new generation of IT Operational portal will help you to:
<li>Better manage a more and more complex IT environment.</li>
<li>Implement ITIL processes at your own pace.</li>
<li>Manage the most important asset of your IT: Documentation.</li>
</ul>
</p>',

	'UI:WelcomeMenu:MyCalls' => 'My Service Calls',
	'UI:WelcomeMenu:MyIncidents' => 'My Incidents',
	'UI:AllOrganizations' => ' All Organizations ',
	'UI:YourSearch' => 'Your Search',
	'UI:LoggedAsMessage' => 'Logged in as %1$s',
	'UI:LoggedAsMessage+Admin' => 'Logged in as %1$s (Administrator)',
	'UI:Button:Logoff' => 'Log off',
	'UI:Button:GlobalSearch' => 'Search',
	'UI:Button:Search' => ' Search ',
	'UI:Button:Query' => ' Query ',
	'UI:Button:Cancel' => 'Cancel',
	'UI:Button:Apply' => 'Apply',
	'UI:SearchToggle' => 'Search',
	'UI:ClickToCreateNew' => 'Click here to create a new %1$s',
	'UI:NoObjectToDisplay' => 'No object to display.',
	'UI:Error:MandatoryTemplateParameter_object_id' => 'Parameter object_id is mandatory when link_attr is specified. Check the definition of the display template.',
	'UI:Error:MandatoryTemplateParameter_target_attr' => 'Parameter target_attr is mandatory when link_attr is specified. Check the definition of the display template.',
	'UI:Error:MandatoryTemplateParameter_group_by' => 'Parameter group_by is mandatory. Check the definition of the display template.',
	'UI:Error:InvalidGroupByFields' => 'Invalid list of fields to group by: "%1$s".',
	'UI:Error:UnsupportedStyleOfBlock' => 'Error: unsupported style of block: "%1$s".',
	'UI:GroupBy:Count' => 'Count',
	'UI:GroupBy:Count+' => 'Number of elements',
	'UI:CountOfObjects' => '%1$d objects matching the criteria.',
	'UI:NoObject_Class_ToDisplay' => 'No %1$s to display',
	'UI:History:LastModified_On_By' => 'Last modified on %1$s by %2$s.',
	'UI:History:Date' => 'Date',
	'UI:History:Date+' => 'Date of the change',
	'UI:History:User' => 'User',
	'UI:History:User+' => 'User who made the change',
	'UI:History:Changes' => 'Changes',
	'UI:History:Changes+' => 'Changes made to the object',
	'UI:Loading' => 'Loading...',
	'UI:Menu:Actions' => 'Actions',
	'UI:Menu:New' => 'New...',
	'UI:Menu:Add' => 'Add...',
	'UI:Menu:Manage' => 'Manage...',
	'UI:Menu:EMail' => 'eMail',
	'UI:Menu:CSVExport' => 'CSV Export',
	'UI:Menu:Modify' => 'Modify...',
	'UI:Menu:Delete' => 'Delete...',
	'UI:Menu:Manage' => 'Manage...',
	'UI:Menu:BulkDelete' => 'Delete...',
	'UI:UndefinedObject' => 'undefined',
	'UI:Document:OpenInNewWindow:Download' => 'Open in new window: %1$s, Download: %2$s',
	'UI:SelectAllToggle+' => 'Select / Deselect All',
	'UI:ClickToDisplay+' => 'Click to display',
	'UI:TruncatedResults' => '%1$d objects displayed out of %2%d',
	'UI:DisplayAll' => 'Display All',
	'UI:CountOfResults' => '%1$d object(s)',
	'UI:ChangesLogTitle' => 'Changes log (%1$d):',
	'UI:EmptyChangesLogTitle' => 'Changes log is empty',
	'UI:SearchFor_Class_Objects' => 'Search for %1$s Objects',
	'UI:OQLQueryBuilderTitle' => 'OQL Query Builder',
	'UI:OQLQueryTab' => 'OQL Query',
	'UI:SimpleSearchTab' => 'Simple Search',
	'UI:Details+' => 'Details',
	'UI:Login:Welcome' => 'Welcome to iTop!',
	'UI:Login:IncorrectLoginPassword' => 'Incorrect login/password, please try again.',
	'UI:Login:IdentifyYourself' => 'Identify yourself before continuing',
	'UI:Login:UserNamePrompt' => 'User Name:',
	'UI:Login:PasswordPrompt' => 'Password:',
	'UI:Button:Login' => 'Enter iTop',
	'UI:Login:Error:AccessRestricted' => 'iTop access is restricted. Please, contact an iTop administrator.',
));



?>
