<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * Localized data
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */


//////////////////////////////////////////////////////////////////////
// Classes in 'gui'
//////////////////////////////////////////////////////////////////////
//

//
// Class: menuNode
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:menuNode' => 'Nodo de menú',
	'Class:menuNode+' => 'Menú principal de configuración de elementos',
	'Class:menuNode/Attribute:name' => 'Nombre de Menú',
	'Class:menuNode/Attribute:name+' => 'Nombre corto para este menú',
	'Class:menuNode/Attribute:label' => 'Descripción del menú',
	'Class:menuNode/Attribute:label+' => 'Descripción larga para este menú',
	'Class:menuNode/Attribute:hyperlink' => 'Liga',
	'Class:menuNode/Attribute:hyperlink+' => 'Liga (URL) hacia la página',
	'Class:menuNode/Attribute:icon_path' => 'Ícono de menú',
	'Class:menuNode/Attribute:icon_path+' => 'Ruta hacia el ícono del menú',
	'Class:menuNode/Attribute:template' => 'Plantilla',
	'Class:menuNode/Attribute:template+' => 'Plantilla HTML para la vista',
	'Class:menuNode/Attribute:type' => 'Tipo',
	'Class:menuNode/Attribute:type+' => 'Tipo de menú',
	'Class:menuNode/Attribute:type/Value:application' => 'aplicación',
	'Class:menuNode/Attribute:type/Value:application+' => 'aplicación',
	'Class:menuNode/Attribute:type/Value:user' => 'usuario',
	'Class:menuNode/Attribute:type/Value:user+' => 'usuario',
	'Class:menuNode/Attribute:type/Value:administrator' => 'administrator',
	'Class:menuNode/Attribute:type/Value:administrator+' => 'administrator',
	'Class:menuNode/Attribute:rank' => 'Muestra categoría',
	'Class:menuNode/Attribute:rank+' => 'Orden de despliegue del menú',
	'Class:menuNode/Attribute:parent_id' => 'Ítem del Menú Padre',
	'Class:menuNode/Attribute:parent_id+' => 'Ítem del Menú Padre',
	'Class:menuNode/Attribute:parent_name' => 'Ítem del Menú Padre',
	'Class:menuNode/Attribute:parent_name+' => 'Ítem del Menú Padre',
	'Class:menuNode/Attribute:user_id' => 'Dueño del menú',
	'Class:menuNode/Attribute:user_id+' => 'Usuario dueño de este menú (para menúes definidos por el usuario)',
));

//////////////////////////////////////////////////////////////////////
// Classes in 'application'
//////////////////////////////////////////////////////////////////////
//

//
// Class: AuditCategory
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:AuditCategory' => 'Categoría Auditoría',
	'Class:AuditCategory+' => 'Una sección intrínseca a la auditoría',
	'Class:AuditCategory/Attribute:name' => 'Nombre de Categoría',
	'Class:AuditCategory/Attribute:name+' => 'Nombre corto para esta categoría',
	'Class:AuditCategory/Attribute:description' => 'Descripcción de Categoría de Auditoría',
	'Class:AuditCategory/Attribute:description+' => 'Descripción larga para esta categoría de auditoría',
	'Class:AuditCategory/Attribute:definition_set' => 'Conjunto de definición',
	'Class:AuditCategory/Attribute:definition_set+' => 'Expresión OQL que define el conjunto de objetos a auditar',
));

//
// Class: AuditRule
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:AuditRule' => 'Regla de Auditoría',
	'Class:AuditRule+' => 'Regla a revisar para una categoría de auditoría específica',
	'Class:AuditRule/Attribute:name' => 'Nombre de la Regla',
	'Class:AuditRule/Attribute:name+' => 'Nombre corto para esta regla',
	'Class:AuditRule/Attribute:description' => 'Descripción de regla de auditoría',
	'Class:AuditRule/Attribute:description+' => 'Descripcion larga para esta regla de auditoría',
	'Class:AuditRule/Attribute:query' => 'Consulta a Ejecutar',
	'Class:AuditRule/Attribute:query+' => 'Expresión OQL a ejecutar',
	'Class:AuditRule/Attribute:valid_flag' => '¿Objetos Válidos?',
	'Class:AuditRule/Attribute:valid_flag+' => 'Verdadero si la regla retorna los objetos válidos, falso cualquier otra cosa',
	'Class:AuditRule/Attribute:valid_flag/Value:true' => 'verdadero',
	'Class:AuditRule/Attribute:valid_flag/Value:true+' => 'verdadero',
	'Class:AuditRule/Attribute:valid_flag/Value:false' => 'falso',
	'Class:AuditRule/Attribute:valid_flag/Value:false+' => 'falso',
	'Class:AuditRule/Attribute:category_id' => 'Categoría',
	'Class:AuditRule/Attribute:category_id+' => 'La categoría para esta regla',
	'Class:AuditRule/Attribute:category_name' => 'Categoría',
	'Class:AuditRule/Attribute:category_name+' => 'Nombre de la categoría para esta regla',
));

//////////////////////////////////////////////////////////////////////
// Classes in 'addon/userrights'
//////////////////////////////////////////////////////////////////////
//

//
// Class: URP_Users
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:URP_Users' => 'Usuario',
	'Class:URP_Users+' => 'Usuarios y credenciales',
	'Class:URP_Users/Attribute:userid' => 'Contacto (persona)',
	'Class:URP_Users/Attribute:userid+' => 'Detalles personales de los datos empresariales',
	'Class:URP_Users/Attribute:last_name' => 'Apellido',
	'Class:URP_Users/Attribute:last_name+' => 'Apellido del contacto',
	'Class:URP_Users/Attribute:first_name' => 'Nombre',
	'Class:URP_Users/Attribute:first_name+' => 'Nombre de pila del contacto',
	'Class:URP_Users/Attribute:email' => 'Correo Electrónico',
	'Class:URP_Users/Attribute:email+' => 'Correo Electrónico del contacto',
	'Class:URP_Users/Attribute:login' => 'Login',
	'Class:URP_Users/Attribute:login+' => 'Nombre de usuario',
	'Class:URP_Users/Attribute:password' => 'Password',
	'Class:URP_Users/Attribute:password+' => 'Palabra clave del usuario',
	'Class:URP_Users/Attribute:language' => 'Lenguaje',
	'Class:URP_Users/Attribute:language+' => 'Lenguaje de la interfase de usuario',
	'Class:URP_Users/Attribute:language/Value:EN US' => 'English',
	'Class:URP_Users/Attribute:language/Value:EN US+' => 'English U.S.',
	'Class:URP_Users/Attribute:language/Value:FR FR' => 'French',
	'Class:URP_Users/Attribute:language/Value:FR FR+' => 'FR FR',
	'Class:URP_Users/Attribute:language/Value:ES CR' => 'Español',
	'Class:URP_Users/Attribute:language/Value:ES CR+' => 'Español Costa Rica',
	'Class:URP_Users/Attribute:profile_list' => 'Perfiles',
	'Class:URP_Users/Attribute:profile_list+' => 'Roles, herencia de derechos para este contacto',
));

//
// Class: URP_Profiles
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:URP_Profiles' => 'Perfil',
	'Class:URP_Profiles+' => 'Perfil de usuario',
	'Class:URP_Profiles/Attribute:name' => 'Nombre',
	'Class:URP_Profiles/Attribute:name+' => 'Etiqueta',
	'Class:URP_Profiles/Attribute:description' => 'Descripción',
	'Class:URP_Profiles/Attribute:description+' => 'descripción en una línea',
	'Class:URP_Profiles/Attribute:user_list' => 'Usuarios',
	'Class:URP_Profiles/Attribute:user_list+' => 'Personas que tienen este Rol.',
));

//
// Class: URP_Dimensions
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:URP_Dimensions' => 'dimensión',
	'Class:URP_Dimensions+' => 'dimensión de aplicación (definiendo silos)',
	'Class:URP_Dimensions/Attribute:name' => 'Nombre',
	'Class:URP_Dimensions/Attribute:name+' => 'Etiqueta',
	'Class:URP_Dimensions/Attribute:description' => 'Descripción',
	'Class:URP_Dimensions/Attribute:description+' => 'descripción en una línea',
	'Class:URP_Dimensions/Attribute:type' => 'Tipo',
	'Class:URP_Dimensions/Attribute:type+' => 'nombre de clase o tipo de datos (unidad de proyección)',
));

//
// Class: URP_UserProfile
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:URP_UserProfile' => 'Asignación de Perfiles',
	'Class:URP_UserProfile+' => 'Perfiles de Usuarios',
	'Class:URP_UserProfile/Attribute:userid' => 'Usuario',
	'Class:URP_UserProfile/Attribute:userid+' => 'Cuenta de usuario',
	'Class:URP_UserProfile/Attribute:userlogin' => 'Login',
	'Class:URP_UserProfile/Attribute:userlogin+' => 'Nomber de usuario',
	'Class:URP_UserProfile/Attribute:profileid' => 'Perfil',
	'Class:URP_UserProfile/Attribute:profileid+' => 'uso de perfil',
	'Class:URP_UserProfile/Attribute:profile' => 'Perfil',
	'Class:URP_UserProfile/Attribute:profile+' => 'Nombre del perfil',
	'Class:URP_UserProfile/Attribute:reason' => 'Razón',
	'Class:URP_UserProfile/Attribute:reason+' => 'Justificación de por qué esta persona tiene este rol',
));

//
// Class: URP_ProfileProjection
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:URP_ProfileProjection' => 'Proyecciones_de_Perfil',
	'Class:URP_ProfileProjection+' => 'Proyecciones de perfil',
	'Class:URP_ProfileProjection/Attribute:dimensionid' => 'Dimensión',
	'Class:URP_ProfileProjection/Attribute:dimensionid+' => 'dimensión de aplicación',
	'Class:URP_ProfileProjection/Attribute:dimension' => 'Dimensión',
	'Class:URP_ProfileProjection/Attribute:dimension+' => 'dimensión de aplicación',
	'Class:URP_ProfileProjection/Attribute:profileid' => 'Perfile',
	'Class:URP_ProfileProjection/Attribute:profileid+' => 'uso del perfil',
	'Class:URP_ProfileProjection/Attribute:profile' => 'Perfil',
	'Class:URP_ProfileProjection/Attribute:profile+' => 'Nombre del perfil',
	'Class:URP_ProfileProjection/Attribute:value' => 'Valor de la expresión',
	'Class:URP_ProfileProjection/Attribute:value+' => 'Expresión OQL (usando $user) | constante |  | +código de atributo',
	'Class:URP_ProfileProjection/Attribute:attribute' => 'Atributo',
	'Class:URP_ProfileProjection/Attribute:attribute+' => 'Código de atributo destino (opcional)',
));

//
// Class: URP_ClassProjection
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:URP_ClassProjection' => 'proyecciones_de_clase',
	'Class:URP_ClassProjection+' => 'proyecciones de clase',
	'Class:URP_ClassProjection/Attribute:dimensionid' => 'Dimensión',
	'Class:URP_ClassProjection/Attribute:dimensionid+' => 'dimensión de aplicación',
	'Class:URP_ClassProjection/Attribute:dimension' => 'Dimensión',
	'Class:URP_ClassProjection/Attribute:dimension+' => 'dimensión de aplicación',
	'Class:URP_ClassProjection/Attribute:class' => 'Clase',
	'Class:URP_ClassProjection/Attribute:class+' => 'Clase destino',
	'Class:URP_ClassProjection/Attribute:value' => 'Valor de la expresión',
	'Class:URP_ClassProjection/Attribute:value+' => 'Expresión OQL (usando $this) | constante |  | +código de atributo',
	'Class:URP_ClassProjection/Attribute:attribute' => 'Atributo',
	'Class:URP_ClassProjection/Attribute:attribute+' => 'Código de atributo destino (opcional)',
));

//
// Class: URP_ActionGrant
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:URP_ActionGrant' => 'permisos_acciones',
	'Class:URP_ActionGrant+' => 'permisos en las clases',
	'Class:URP_ActionGrant/Attribute:profileid' => 'Perfil',
	'Class:URP_ActionGrant/Attribute:profileid+' => 'uso del perfil',
	'Class:URP_ActionGrant/Attribute:profile' => 'Perfil',
	'Class:URP_ActionGrant/Attribute:profile+' => 'usage profile',
	'Class:URP_ActionGrant/Attribute:class' => 'Clase',
	'Class:URP_ActionGrant/Attribute:class+' => 'Clase destino',
	'Class:URP_ActionGrant/Attribute:permission' => 'Permisos',
	'Class:URP_ActionGrant/Attribute:permission+' => 'permitido o no permitido?',
	'Class:URP_ActionGrant/Attribute:permission/Value:yes' => 'si',
	'Class:URP_ActionGrant/Attribute:permission/Value:yes+' => 'si',
	'Class:URP_ActionGrant/Attribute:permission/Value:no' => 'no',
	'Class:URP_ActionGrant/Attribute:permission/Value:no+' => 'no',
	'Class:URP_ActionGrant/Attribute:action' => 'Acción',
	'Class:URP_ActionGrant/Attribute:action+' => 'operaciones a realizar en la case especificada',
));

//
// Class: URP_StimulusGrant
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:URP_StimulusGrant' => 'permisos_cambios_de_estado',
	'Class:URP_StimulusGrant+' => 'permisos de cambio de estado en el ciclo de vida del objeto',
	'Class:URP_StimulusGrant/Attribute:profileid' => 'Perfil',
	'Class:URP_StimulusGrant/Attribute:profileid+' => 'uso del perfil',
	'Class:URP_StimulusGrant/Attribute:profile' => 'Perfil',
	'Class:URP_StimulusGrant/Attribute:profile+' => 'uso del perfil',
	'Class:URP_StimulusGrant/Attribute:class' => 'Clase',
	'Class:URP_StimulusGrant/Attribute:class+' => 'Clase destino',
	'Class:URP_StimulusGrant/Attribute:permission' => 'Permiso',
	'Class:URP_StimulusGrant/Attribute:permission+' => '¿permitido o no permitido?',
	'Class:URP_StimulusGrant/Attribute:permission/Value:yes' => 'si',
	'Class:URP_StimulusGrant/Attribute:permission/Value:yes+' => 'si',
	'Class:URP_StimulusGrant/Attribute:permission/Value:no' => 'no',
	'Class:URP_StimulusGrant/Attribute:permission/Value:no+' => 'no',
	'Class:URP_StimulusGrant/Attribute:stimulus' => 'Cambio de estado',
	'Class:URP_StimulusGrant/Attribute:stimulus+' => 'código de cambio de estado',
));

//
// Class: URP_AttributeGrant
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:URP_AttributeGrant' => 'permisos_de_atributo',
	'Class:URP_AttributeGrant+' => 'permisos a nivel de atributos',
	'Class:URP_AttributeGrant/Attribute:actiongrantid' => 'Concesión de acción',
	'Class:URP_AttributeGrant/Attribute:actiongrantid+' => 'concesión de acción',
	'Class:URP_AttributeGrant/Attribute:attcode' => 'Atributo',
	'Class:URP_AttributeGrant/Attribute:attcode+' => 'código de atributo',
));

//
// String from the User Interface: menu, messages, buttons, etc...
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Menu:WelcomeMenu' => 'Bievenido',
	'Menu:WelcomeMenu+' => 'Bievenido a iTop',
	'Menu:WelcomeMenuPage' => 'Bievenido',
	'Menu:WelcomeMenuPage+' => 'Bievenido a iTop',
	'UI:WelcomeMenu:Title' => 'Bievenido a iTop',

	'UI:WelcomeMenu:LeftBlock' => '<p>iTop es un completo; portal  IT funcioanl basado en código abierto (OpenSource).</p>
<ul>Incluye:
<li>Una CMDB competa (Configuration management database) para documentar y manejar el inverntario de TI..</li>
<li>Un módul de gestión de incidentes, para llevar la trazabilidad y comunicar los eventos que estan afectando IT.</li>
<li>Un módulo de gestion de cambio para planear y llevar la trazabilidad hechos al ambiente de TI.</li>
<li>Una base de conocimiento para acelerar la correción de incidentes.</li>
<li>Un moódulo de Cortes/Caídas para documentar todas las caídas planeadas o no y notificar a los contactods del caso.</li>
<li>Tableros de controles para rapidamente tener visión general del ambiente TI..</li>
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

	'UI:WelcomeMenu:MyCalls' => 'My requests',
	'UI:WelcomeMenu:MyIncidents' => 'Incidents assigned to me',
	'UI:AllOrganizations' => ' All Organizations ',
	'UI:YourSearch' => 'Your Search',
	'UI:LoggedAsMessage' => 'Logged in as %1$s',
	'UI:LoggedAsMessage+Admin' => 'Logged in as %1$s (Administrator)',
	'UI:Button:Logoff' => 'Log off',
	'UI:Button:GlobalSearch' => 'Search',
	'UI:Button:Search' => ' Search ',
	'UI:Button:Query' => ' Query ',
	'UI:Button:Ok' => 'Ok',
	'UI:Button:Cancel' => 'Cancel',
	'UI:Button:Apply' => 'Apply',
	'UI:Button:Back' => ' << Back ',
	'UI:Button:Next' => ' Next >> ',
	'UI:Button:Finish' => ' Finish ',
	'UI:Button:DoImport' => ' Run the Import ! ',
	'UI:Button:Done' => ' Done ',
	'UI:Button:SimulateImport' => ' Simulate the Import ',
	'UI:Button:Test' => 'Test!',
	'UI:Button:Evaluate' => ' Evaluate ',
	'UI:Button:AddObject' => ' Add... ',
	'UI:Button:BrowseObjects' => ' Browse... ',
	'UI:Button:Add' => ' Add ',
	'UI:Button:AddToList' => ' << Add ',
	'UI:Button:RemoveFromList' => ' Remove >> ',
	'UI:Button:FilterList' => ' Filter... ',
	'UI:Button:Create' => ' Create ',
	'UI:Button:Delete' => ' Delete ! ',
	'UI:Button:ChangePassword' => ' Change Password ',
	'UI:Button:ResetPassword' => ' Reset Password ',

	'UI:SearchToggle' => 'Search',
	'UI:ClickToCreateNew' => 'Click here to create a new %1$s',
	'UI:NoObjectToDisplay' => 'No object to display.',
	'UI:Error:MandatoryTemplateParameter_object_id' => 'Parameter object_id is mandatory when link_attr is specified. Check the definition of the display template.',
	'UI:Error:MandatoryTemplateParameter_target_attr' => 'Parameter target_attr is mandatory when link_attr is specified. Check the definition of the display template.',
	'UI:Error:MandatoryTemplateParameter_group_by' => 'Parameter group_by is mandatory. Check the definition of the display template.',
	'UI:Error:InvalidGroupByFields' => 'Invalid list of fields to group by: "%1$s".',
	'UI:Error:UnsupportedStyleOfBlock' => 'Error: unsupported style of block: "%1$s".',
	'UI:Error:IncorrectLinkDefinition_LinkedClass_Class' => 'Incorrect link definition: the class of objects to manage: %1$s was not found as an external key in the class %2$s',
	'UI:Error:Object_Class_Id_NotFound' => 'Object: %1$s:%2$d not found.',
	'UI:Error:WizardCircularReferenceInDependencies' => 'Error: Circular reference in the dependencies between the fields, check the data model.',
	'UI:Error:UploadedFileTooBig' => 'Uploaded file is too big. (Max allowed size is %1$s. Check you PHP configuration for upload_max_filesize.',
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
	'UI:Error:ObjectAlreadyCloned' => 'Error: the object has already been cloned!',
	'UI:Error:ObjectAlreadyCreated' => 'Error: the object has already been created!',
	'UI:Error:Invalid_Stimulus_On_Object_In_State' => 'Error: invalid stimulus "%1$s" on object %2$s in state "%3$s".',
	
	
	'UI:GroupBy:Count' => 'Count',
	'UI:GroupBy:Count+' => 'Number of elements',
	'UI:CountOfObjects' => '%1$d objects matching the criteria.',
	'UI_CountOfObjectsShort' => '%1$d objects.',
	'UI:NoObject_Class_ToDisplay' => 'No %1$s to display',
	'UI:History:LastModified_On_By' => 'Last modified on %1$s by %2$s.',
	'UI:HistoryTab' => 'History',
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
	'UI:TruncatedResults' => '%1$d objects displayed out of %2$d',
	'UI:DisplayAll' => 'Display All',
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
	'UI:SelectOne' => '-- select one --',
	'UI:Login:Welcome' => 'Welcome to iTop!',
	'UI:Login:IncorrectLoginPassword' => 'Incorrect login/password, please try again.',
	'UI:Login:IdentifyYourself' => 'Identify yourself before continuing',
	'UI:Login:UserNamePrompt' => 'User Name',
	'UI:Login:PasswordPrompt' => 'Password',
	'UI:Login:ChangeYourPassword' => 'Change Your Password',
	'UI:Login:OldPasswordPrompt' => 'Old password',
	'UI:Login:NewPasswordPrompt' => 'New password',
	'UI:Login:RetypeNewPasswordPrompt' => 'Retype new password',
	'UI:Login:IncorrectOldPassword' => 'Error: the old password is incorrect',
	'UI:LogOffMenu' => 'Log off',
	'UI:ChangePwdMenu' => 'Change Password...',
	'UI:Login:RetypePwdDoesNotMatch' => 'New password and retyped new password do not match !',
	'UI:Button:Login' => 'Enter iTop',
	'UI:Login:Error:AccessRestricted' => 'iTop access is restricted. Please, contact an iTop administrator.',
	'UI:CSVImport:MappingSelectOne' => '-- select one --',
	'UI:CSVImport:MappingNotApplicable' => '-- ignore this field --',
	'UI:CSVImport:NoData' => 'Empty data set..., please provide some data!',
	'UI:Title:DataPreview' => 'Data Preview',
	'UI:CSVImport:ErrorOnlyOneColumn' => 'Error: The data contains only one column. Did you select the appropriate separator character?',
	'UI:CSVImport:FieldName' => 'Field %1$d',
	'UI:CSVImport:DataLine1' => 'Data Line 1',
	'UI:CSVImport:DataLine2' => 'Data Line 2',
	'UI:CSVImport:idField' => 'id (Primary Key)',
	'UI:Title:BulkImport' => 'iTop - Bulk import',
	'UI:Title:BulkImport+' => 'CSV Import Wizard',
	'UI:CSVImport:ClassesSelectOne' => '-- select one --',
	'UI:CSVImport:ErrorExtendedAttCode' => 'Internal error: "%1$s" is an incorrect code because "%2$s" is NOT an external key of the class "%3$s"',
	'UI:CSVImport:ObjectsWillStayUnchanged' => '%1$d objects(s) will stay unchanged.',
	'UI:CSVImport:ObjectsWillBeModified' => '%1$d objects(s) will be modified.',
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
	'UI:CSVImport:AdvancedMode+' => 'In advanced mode the "id" (primary key) of the objects can be used to update and rename objects.' .
									'However the column "id" (if present) can only be used as a search criteria and can not be combined with any other search criteria.',
	'UI:CSVImport:SelectAClassFirst' => 'To configure the mapping, select a class first.',
	'UI:CSVImport:HeaderFields' => 'Fields',
	'UI:CSVImport:HeaderMappings' => 'Mappings',
	'UI:CSVImport:HeaderSearch' => 'Search?',
	'UI:CSVImport:AlertIncompleteMapping' => 'Please select a mapping for every field.',
	'UI:CSVImport:AlertNoSearchCriteria' => 'Please select at least one search criteria',
	
	'UI:UniversalSearchTitle' => 'iTop - Universal Search',
	'UI:UniversalSearch:Error' => 'Error: %1$s',
	'UI:UniversalSearch:LabelSelectTheClass' => 'Select the class to search: ',
	
	'UI:Audit:Title' => 'iTop - CMDB Audit',
	'UI:Audit:InteractiveAudit' => 'Interactive Audit',
	'UI:Audit:HeaderAuditRule' => 'Audit Rule',
	'UI:Audit:HeaderNbObjects' => '# Objects',
	'UI:Audit:HeaderNbErrors' => '# Errors',
	'UI:Audit:PercentageOk' => '% Ok',
	
	'UI:RunQuery:Title' => 'iTop - OQL Query Evaluation',
	'UI:RunQuery:QueryExamples' => 'Query Examples',
	'UI:RunQuery:HeaderPurpose' => 'Purpose',
	'UI:RunQuery:HeaderPurpose+' => 'Explanation about the query',
	'UI:RunQuery:HeaderOQLExpression' => 'OQL Expression',
	'UI:RunQuery:HeaderOQLExpression+' => 'The query in OQL syntax',
	'UI:RunQuery:ExpressionToEvaluate' => 'Expression to evaluate: ',
	'UI:RunQuery:MoreInfo' => 'More information about the query: ',
	'UI:RunQuery:DevelopedQuery' => 'Redevelopped query expression: ',
	'UI:RunQuery:SerializedFilter' => 'Serialized filter: ',
	'UI:RunQuery:Error' => 'An error occured while running the query: %1$s',
	
	'UI:Schema:Title' => 'iTop objects schema',
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
	'UI:Schema:LifeCycleTransitions' => 'Transitions',
	'UI:Schema:LifeCyleAttributeOptions' => 'Attribute options',
	'UI:Schema:LifeCycleHiddenAttribute' => 'Hidden',
	'UI:Schema:LifeCycleReadOnlyAttribute' => 'Read-only',
	'UI:Schema:LifeCycleMandatoryAttribute' => 'Mandatory',
	'UI:Schema:LifeCycleAttributeMustChange' => 'Must change',
	'UI:Schema:LifeCycleAttributeMustPrompt' => 'User will be prompted to change the value',
	'UI:Schema:LifeCycleEmptyList' => 'empty list',
	
	'UI:LinksWidget:Autocomplete+' => 'Type the first 3 characters...',
	'UI:Combo:SelectValue' => '--- select a value ---',
	'UI:Label:SelectedObjects' => 'Selected objects: ',
	'UI:Label:AvailableObjects' => 'Available objects: ',
	'UI:Link_Class_Attributes' => '%1$s attributes',
	'UI:SelectAllToggle+' => 'Select All / Deselect All',
	'UI:AddObjectsOf_Class_LinkedWith_Class_Instance' => 'Add %1$s objects linked with %2$s: %3$s',
	'UI:ManageObjectsOf_Class_LinkedWith_Class_Instance' => 'Manage %1$s objects linked with %2$s: %3$s',
	'UI:AddLinkedObjectsOf_Class' => 'Add %1$ss...',
	'UI:RemoveLinkedObjectsOf_Class' => 'Remove selected objects',
	'UI:Message:EmptyList:UseAdd' => 'The list is empty, use the "Add..." button to add elements.',
	'UI:Message:EmptyList:UseSearchForm' => 'Use the search form above to search for objects to be added.',
	
	'UI:Wizard:FinalStepTitle' => 'Final step: confirmation',
	'UI:Title:DeletionOf_Object' => 'Deletion of %1$s',
	'UI:Title:BulkDeletionOf_Count_ObjectsOf_Class' => 'Bulk deletion of %1$d objects of class %2$s',
	'UI:Delete:NotAllowedToDelete' => 'You are not allowed to delete this object',
	'UI:Delete:NotAllowedToUpdate_Fields' => 'You are not allowed to update the following field(s): %1$s',
	'UI:Error:NotEnoughRightsToDelete' => 'This object could not be deleted because the current user do not have sufficient rights',
	'UI:Error:CannotDeleteBecauseOfDepencies' => 'This object could not be deleted because some manual operations must be performed prior to that',
	'UI:Archive_User_OnBehalfOf_User' => '%1$s on behalf of %2$s',
	'UI:Delete:AutomaticallyDeleted' => 'automatically deleted',
	'UI:Delete:AutomaticResetOf_Fields' => 'automatic reset of field(s): %1$s',
	'UI:Delete:CleaningUpRefencesTo_Object' => 'Cleaning up all references to %1$s...',
	'UI:Delete:CleaningUpRefencesTo_Several_ObjectsOf_Class' => 'Cleaning up all references to %1$d objects of class %2$s...',
	'UI:Delete:Done+' => 'What was done...',
	'UI:Delete:_Name_Class_Deleted' => '%1$s - %2$s deleted.',
	'UI:Delete:ConfirmDeletionOf_Name' => 'Deletion of %1$s',
	'UI:Delete:ConfirmDeletionOf_Count_ObjectsOf_Class' => 'Deletion of %1$d objects of class %2$s',
	'UI:Delete:ShouldBeDeletedAtomaticallyButNotAllowed' => 'Should be automaticaly deleted, but you are not allowed to do so',
	'UI:Delete:MustBeDeletedManuallyButNotAllowed' => 'Must be deleted manually - but you are not allowed to delete this object, please contact your application admin',
	'UI:Delete:WillBeDeletedAutomatically' => 'Will be automaticaly deleted',
	'UI:Delete:MustBeDeletedManually' => 'Must be deleted manually',
	'UI:Delete:CannotUpdateBecause_Issue' => 'Should be automatically updated, but: %1$s',
	'UI:Delete:WillAutomaticallyUpdate_Fields' => 'will be automaticaly updated (reset: %1$s)',
	'UI:Delete:Count_Objects/LinksReferencing_Object' => '%1$d objects/links are referencing %2$s',
	'UI:Delete:Count_Objects/LinksReferencingTheObjects' => '%1$d objects/links are referencing some of the objects to be deleted',	
	'UI:Delete:ReferencesMustBeDeletedToEnsureIntegrity' => 'To ensure Database integrity, any reference should be further eliminated',
	'UI:Delete:Consequence+' => 'What will be done',
	'UI:Delete:SorryDeletionNotAllowed' => 'Sorry, you are not allowed to delete this object, see the detailed explanations above',
	'UI:Delete:PleaseDoTheManualOperations' => 'Please perform the manual operations listed above prior to requesting the deletion of this object',
	'UI:Delect:Confirm_Object' => 'Please confirm that you want to delete %1$s.',
	'UI:Delect:Confirm_Count_ObjectsOf_Class' => 'Please confirm that you want to delete the following %1$d objects of class %2$s.',
	'UI:WelcomeToITop' => 'Welcome to iTop',
	'UI:DetailsPageTitle' => 'iTop - %1$s - %2$s details',
	'UI:ErrorPageTitle' => 'iTop - Error',
	'UI:ObjectDoesNotExist' => 'Sorry, this object does not exist (or you are not allowed to view it).',
	'UI:SearchResultsPageTitle' => 'iTop - Search Results',
	'UI:Search:NoSearch' => 'Nothing to search for',
	'UI:FullTextSearchTitle_Text' => 'Results for "%1$s":',
	'UI:Search:Count_ObjectsOf_Class_Found' => '%1$d object(s) of class %2$s found.',
	'UI:Search:NoObjectFound' => 'No object found.',
	'UI:ModificationPageTitle_Object_Class' => 'iTop - %1$s - %2$s modification',
	'UI:ModificationTitle_Class_Object' => 'Modification of %1$s: <span class=\"hilite\">%2$s</span>',
	'UI:ClonePageTitle_Object_Class' => 'iTop - Clone %1$s - %2$s modification',
	'UI:CloneTitle_Class_Object' => 'Clone of %1$s: <span class=\"hilite\">%2$s</span>',
	'UI:CreationPageTitle_Class' => 'iTop - Creation of a new %1$s ',
	'UI:CreationTitle_Class' => 'Creation of a new %1$s',
	'UI:SelectTheTypeOf_Class_ToCreate' => 'Select the type of %1$s to create:',
	'UI:Class_Object_NotUpdated' => 'No change detected, %1$s (%2$s) has <strong>not</strong> been modified.',
	'UI:Class_Object_Updated' => '%1$s (%2$s) updated.',
	'UI:BulkDeletePageTitle' => 'iTop - Bulk Delete',
	'UI:BulkDeleteTitle' => 'Select the objects you want to delete:',
	'UI:PageTitle:ObjectCreated' => 'iTop Object Created.',
	'UI:Title:Object_Of_Class_Created' => '%1$s - %2$s created.',
	'UI:Apply_Stimulus_On_Object_In_State_ToTarget_State' => 'Applying %1$s on object: %2$s in state %3$s to target state: %4$s.',
	'UI:PageTitle:FatalError' => 'iTop - Fatal Error',
	'UI:FatalErrorMessage' => 'Fatal error, iTop cannot continue.',
	'UI:Error_Details' => 'Error: %1$s.',

	'UI:PageTitle:ClassProjections'	=> 'iTop user management - class projections',
	'UI:PageTitle:ProfileProjections' => 'iTop user management - profile projections',
	'UI:UserManagement:Class' => 'Class',
	'UI:UserManagement:Class+' => 'Class of objects',
	'UI:UserManagement:ProjectedObject' => 'Object',
	'UI:UserManagement:ProjectedObject+' => 'Projected object',
	'UI:UserManagement:AnyObject' => '* any *',
	'UI:UserManagement:User' => 'User',
	'UI:UserManagement:User+' => 'User involved in the projection',
	'UI:UserManagement:Profile' => 'Profile',
	'UI:UserManagement:Profile+' => 'Profile in which the projection is specified',
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
	'UI:UserManagement:LinkBetween_User_And_Profile' => 'Link between %1$s and %2$s',
	
	'Menu:AdminTools' => 'Admin tools',
	'Menu:AdminTools+' => 'Administration tools',
	'Menu:AdminTools?' => 'Tools accessible only to users having the administrator profile',

	'UI:ChangeManagementMenu' => 'Change Management',
	'UI:ChangeManagementMenu+' => 'Change Management',
	'UI:ChangeManagementMenu:Title' => 'Changes Overview',
	'UI-ChangeManagementMenu-ChangesByType' => 'Changes by type',
	'UI-ChangeManagementMenu-ChangesByStatus' => 'Changes by status',
	'UI-ChangeManagementMenu-ChangesByWorkgroup' => 'Changes by workgroup',
	'UI-ChangeManagementMenu-ChangesNotYetAssigned' => 'Changes not yet assigned',

	'UI:ConfigurationItemsMenu'=> 'Configuration Items',
	'UI:ConfigurationItemsMenu+'=> 'All Devices',
	'UI:ConfigurationItemsMenu:Title' => 'Configuration Items Overview',
	'UI-ConfigurationItemsMenu-ServersByCriticity' => 'Servers by criticity',
	'UI-ConfigurationItemsMenu-PCsByCriticity' => 'PCs by criticity',
	'UI-ConfigurationItemsMenu-NWDevicesByCriticity' => 'Network devices by criticity',
	'UI-ConfigurationItemsMenu-ApplicationsByCriticity' => 'Applications by criticity',
	
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
'UI-IncidentManagementOverview-IncidentByPriority' => 'Incident by priority',
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

	'Menu:CSVImportMenu' => 'CSV import',
	'Menu:CSVImportMenu+' => 'Bulk creation or update',
	
	'Menu:DataModelMenu' => 'Data Model',
	'Menu:DataModelMenu+' => 'Overview of the Data Model',
	
	'Menu:ExportMenu' => 'Export',
	'Menu:ExportMenu+' => 'Export the results of any query in HTML, CSV or XML',
	
	'Menu:NotificationsMenu' => 'Notifications',
	'Menu:NotificationsMenu+' => 'Configuration of the Notifications',
	'UI:NotificationsMenu:Title' => 'Configuration of the <span class="hilite">Notifications</span>',
	'UI:NotificationsMenu:Help' => 'Help',
	'UI:NotificationsMenu:HelpContent' => '<p>In iTop the notifications are fully customizable. They are based on two sets of objects: <i>triggers and actions</i>.</p>
<p><i><b>Triggers</b></i> define when a notification will be executed. There are 3 types of triggers for covering 3 differents phases of an object life cycle:
<ol>
	<li>the "OnCreate" triggers get executed when an object of the specified class is created</li>
	<li>the "OnStateEnter" triggers get executed before an object of the given class enters a specified state (coming from another state)</li>
	<li>the "OnStateLeave" triggers get executed when an object of the given class is leaving a specified state</li>
</ol>
</p>
<p>
<i><b>Actions</b></i> define the actions to be performed when the triggers execute. For now there is only one kind of action consisting in sending an email message.
Such actions also define the template to be used for sending the email as well as the other parameters of the message like the recipients, importance, etc.
</p>
<p>A special page: <a href="../setup/email.test.php" target="_blank">email.test.php</a> is available for testing and troubleshooting your PHP mail configuration.</p>
<p>To be executed, actions must be associated to triggers.
When associated with a trigger, each action is given an "order" number, specifying in which order the actions are to be executed.</p>',
	'UI:NotificationsMenu:Triggers' => 'Disparadores',
	'UI:NotificationsMenu:AvailableTriggers' => 'Disparadores disponiblesAvailable triggers',
	'UI:NotificationsMenu:OnCreate' => 'cuando un objeto es creado',
	'UI:NotificationsMenu:OnStateEnter' => 'Cuando un objeto entra a un estado específico',
	'UI:NotificationsMenu:OnStateLeave' => 'Cuando un objeto sale de un estado específico',
	'UI:NotificationsMenu:Actions' => 'Acciones',
	'UI:NotificationsMenu:AvailableActions' => 'Acciones disponibles',
	
	'Menu:RunQueriesMenu' => 'Ejecutar Consultas',
	'Menu:RunQueriesMenu+' => 'Ejecute cualquier consulta',
	
	'Menu:DataAdministration' => 'Administración de datos',
	'Menu:DataAdministration+' => 'Administración de datos',
	
	'Menu:UniversalSearchMenu' => 'Búsqueda universal',
	'Menu:UniversalSearchMenu+' => 'Buscar cualquier cosa...',
	
	'Menu:ApplicationLogMenu' => 'Bitácoras de la aplicación',
	'Menu:ApplicationLogMenu+' => 'Bitácoras de la aplicación',
	'Menu:ApplicationLogMenu:Title' => 'Bitácoras de la aplicación',

	'Menu:UserManagementMenu' => 'Gestión de usuarios',
	'Menu:UserManagementMenu+' => 'Gestión de usuarios',

	'Menu:ProfilesMenu' => 'Perfiles',
	'Menu:ProfilesMenu+' => 'Perfiles',
	'Menu:ProfilesMenu:Title' => 'Perfiles',

	'Menu:UserAccountsMenu' => 'Cuentas de usuario',
	'Menu:UserAccountsMenu+' => 'Cuentas de usuario',
	'Menu:UserAccountsMenu:Title' => 'Cuentas de usuario',	

	'UI:iTopVersion:Short' => 'iTop versión %1$s',
	'UI:iTopVersion:Long' => 'iTop versión %1$s-%2$s built on %3$s',
	'UI:PropertiesTab' => 'Propiedades',

	'UI:OpenDocumentInNewWindow_' => 'Abra este documento en una ventana nueva: %1$s',
	'UI:DownloadDocument_' => 'Descargue este documento: %1$s',
	'UI:Document:NoPreview' => 'No hay prevista disponible para este tipo de archivo',

	'UI:DeadlineMissedBy_duration' => 'No se cumplió por %1$s',
	'UI:Deadline_LessThan1Min' => '< 1 min',		
	'UI:Deadline_Minutes' => '%1$d min',			
	'UI:Deadline_Hours_Minutes' => '%1$dh %2$dmin',			
	'UI:Deadline_Days_Hours_Minutes' => '%1$dd %2$dh %3$dmin',
	'UI:Help' => 'Ayuda',
	'UI:PasswordConfirm' => '(Confirm)',
));

?>
