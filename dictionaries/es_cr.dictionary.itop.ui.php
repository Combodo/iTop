<?php
// Copyright (C) 2010-2017 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>
/**
 * Localized data
 *
 * @copyright   Copyright (C) 2010-2017 Combodo SARL
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
Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
	'Class:AuditCategory' => 'Auditoría de Categorías',
	'Class:AuditCategory+' => 'Auditoría de Categorías',
	'Class:AuditCategory/Attribute:name' => 'Nombre de Categoría',
	'Class:AuditCategory/Attribute:name+' => 'Nombre corto para esta categoría',
	'Class:AuditCategory/Attribute:description' => 'Descripcción de Categoría a auditar',
	'Class:AuditCategory/Attribute:description+' => 'Descripción larga para esta categoría de auditoría',
	'Class:AuditCategory/Attribute:definition_set' => 'Conjunto de definición',
	'Class:AuditCategory/Attribute:definition_set+' => 'Expresión OQL que define el conjunto de objetos a auditar',
	'Class:AuditCategory/Attribute:rules_list' => 'Reglas de Auditoría',
	'Class:AuditCategory/Attribute:rules_list+' => 'Reglas de Auditoria para esta Categoría',
));

//
// Class: AuditRule
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
	'Class:AuditRule' => 'Regla de Auditoría',
	'Class:AuditRule+' => 'Regla a revisar para una categoría de auditoría específica',
	'Class:AuditRule/Attribute:name' => 'Nombre de la Regla',
	'Class:AuditRule/Attribute:name+' => 'Nombre corto para esta regla',
	'Class:AuditRule/Attribute:description' => 'Descripción de regla de auditoría',
	'Class:AuditRule/Attribute:description+' => 'Descripción larga para esta regla de auditoría',
	'Class:TagSetFieldData/Attribute:finalclass' => 'Tag class~~',
	'Class:TagSetFieldData/Attribute:obj_class' => 'Object class~~',
	'Class:TagSetFieldData/Attribute:obj_attcode' => 'Field code~~',
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

//
// Class: QueryOQL
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
	'Class:Query' => 'Consulta',
	'Class:Query+' => 'Un query es un set de datos definidos de manera dinámica',
	'Class:Query/Attribute:name' => 'Nombre',
	'Class:Query/Attribute:name+' => 'Identifica la consulta',
	'Class:Query/Attribute:description' => 'Descripción',
	'Class:Query/Attribute:description+' => 'Descripción larga de la consulta (propósito, uso, etc.)',
	'Class:QueryOQL/Attribute:fields' => 'Campos',
	'Class:QueryOQL/Attribute:fields+' => 'Lista de atributos separados por coma (o alias.attribute) para exportación',
	'Class:QueryOQL' => 'Consulta OQL',
	'Class:QueryOQL+' => 'Una consulta basada en Object Query Language',
	'Class:QueryOQL/Attribute:oql' => 'Expresión',
	'Class:QueryOQL/Attribute:oql+' => 'Expresión OQL',
));

//////////////////////////////////////////////////////////////////////
// Classes in 'addon/userrights'
//////////////////////////////////////////////////////////////////////
//

//
// Class: User
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
	'Class:User' => 'Usuario',
	'Class:User+' => 'Credencial de usuario',
	'Class:User/Attribute:finalclass' => 'Tipo de Cuenta',
	'Class:User/Attribute:finalclass+' => 'Tipo de Cuenta',
	'Class:User/Attribute:contactid' => 'Contacto (persona)',
	'Class:User/Attribute:contactid+' => 'Detalles personales de la información de negocio',
	'Class:User/Attribute:org_id' => 'Organización',
	'Class:User/Attribute:org_id+' => 'Organization of the associated person~~',
	'Class:User/Attribute:last_name' => 'Apellidos',
	'Class:User/Attribute:last_name+' => 'Apellidos',
	'Class:User/Attribute:first_name' => 'Nombre',
	'Class:User/Attribute:first_name+' => 'Nombre',
	'Class:User/Attribute:email' => 'Correo Electrónico',
	'Class:User/Attribute:email+' => 'Correo Electrónico del contacto correspondiente',
	'Class:User/Attribute:login' => 'Usuario',
	'Class:User/Attribute:login+' => 'cadena de identificacion de usuario',
	'Class:User/Attribute:language' => 'Idioma',
	'Class:User/Attribute:language+' => 'idioma del usuario',
	'Class:User/Attribute:language/Value:EN US' => 'English',
	'Class:User/Attribute:language/Value:EN US+' => 'English (U.S.)',
	'Class:User/Attribute:language/Value:FR FR' => 'Frances',
	'Class:User/Attribute:language/Value:FR FR+' => 'Frances (Francia)',
	'Class:User/Attribute:profile_list' => 'Perfiles',
	'Class:User/Attribute:profile_list+' => 'Roles, y permisos otorgados a esa persona',
	'Class:User/Attribute:allowed_org_list' => 'Organizaciones Permitidas',
	'Class:User/Attribute:allowed_org_list+' => 'El usuario tiene permitido ver la información perteneciente a las siguientes Organizaciones. Sino se especificó una Organización, esto no es una restricción.',
	'Class:User/Attribute:status' => 'Estatus',
	'Class:User/Attribute:status+' => 'Cuando el usuario se encuentra habilitado o deshabilitado.',
	'Class:User/Attribute:status/Value:enabled' => 'Habilitado',
	'Class:User/Attribute:status/Value:disabled' => 'Deshabilitado',

	'Class:User/Error:LoginMustBeUnique' => 'Usuario debe ser único - "%1s" ya se encuentra en uso.',
	'Class:User/Error:AtLeastOneProfileIsNeeded' => 'Al menos un Perfil debe ser asignado a este usuario.',
	'Class:User/Error:AtLeastOneOrganizationIsNeeded' => 'Al menos una organización debe ser asignada a este usuario.',
	'Class:User/Error:OrganizationNotAllowed' => 'Organización no permitida.',
	'Class:User/Error:UserOrganizationNotAllowed' => 'El usuario no pertenece a las oganizaciones permitidas.',
	'Class:User/Error:PersonIsMandatory' => 'The Contact is mandatory.~~',
	'Class:UserInternal' => 'Usuario Interno',
	'Class:UserInternal+' => 'Usuario definido en iTop',
));

//
// Class: URP_Profiles
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
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

Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
	'Class:URP_Dimensions' => 'Dimensión',
	'Class:URP_Dimensions+' => 'Dimensión de Aplicación (definiendo silos)',
	'Class:URP_Dimensions/Attribute:name' => 'Nombre',
	'Class:URP_Dimensions/Attribute:name+' => 'Etiqueta',
	'Class:URP_Dimensions/Attribute:description' => 'Descripción',
	'Class:URP_Dimensions/Attribute:description+' => 'Descripción en una línea',
	'Class:URP_Dimensions/Attribute:type' => 'Tipo',
	'Class:URP_Dimensions/Attribute:type+' => 'Nombre de Clase o Tipo de Datos (Unidad de Proyección)',
));

//
// Class: URP_UserProfile
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
	'Class:URP_UserProfile' => 'Asignación de Perfiles',
	'Class:URP_UserProfile+' => 'Perfiles de Usuarios',
	'Class:URP_UserProfile/Attribute:userid' => 'Usuario',
	'Class:URP_UserProfile/Attribute:userid+' => 'Cuenta de usuario',
	'Class:URP_UserProfile/Attribute:userlogin' => 'Clave de usuario',
	'Class:URP_UserProfile/Attribute:userlogin+' => 'Nombre de usuario',
	'Class:URP_UserProfile/Attribute:profileid' => 'Perfil',
	'Class:URP_UserProfile/Attribute:profileid+' => 'uso de perfil',
	'Class:URP_UserProfile/Attribute:profile' => 'Perfil',
	'Class:URP_UserProfile/Attribute:profile+' => 'Nombre del perfil',
	'Class:URP_UserProfile/Attribute:reason' => 'Motivo',
	'Class:URP_UserProfile/Attribute:reason+' => 'Justificación de por qué esta persona tiene este rol',
));

//
// Class: URP_UserOrg
//


Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
	'Class:URP_UserOrg' => 'Organizaciones de Usuario',
	'Class:URP_UserOrg+' => 'Organizaciones Permitidas',
	'Class:URP_UserOrg/Attribute:userid' => 'Usuario',
	'Class:URP_UserOrg/Attribute:userid+' => 'Cuenta de usuario',
	'Class:URP_UserOrg/Attribute:userlogin' => 'Login',
	'Class:URP_UserOrg/Attribute:userlogin+' => 'Login del usuario',
	'Class:URP_UserOrg/Attribute:allowed_org_id' => 'Organización',
	'Class:URP_UserOrg/Attribute:allowed_org_id+' => 'Organización Permitida',
	'Class:URP_UserOrg/Attribute:allowed_org_name' => 'Organización',
	'Class:URP_UserOrg/Attribute:allowed_org_name+' => 'Organización Permitida',
	'Class:URP_UserOrg/Attribute:reason' => 'Motivo',
	'Class:URP_UserOrg/Attribute:reason+' => 'Explicar porqué esta personal tiene permitido ver la información de esta Organización',
));

//
// Class: URP_ProfileProjection
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
	'Class:URP_ProfileProjection' => 'Proyecciones de Perfil',
	'Class:URP_ProfileProjection+' => 'Proyecciones de Perfil',
	'Class:URP_ProfileProjection/Attribute:dimensionid' => 'Dimensión',
	'Class:URP_ProfileProjection/Attribute:dimensionid+' => 'Dimensión de aplicación',
	'Class:URP_ProfileProjection/Attribute:dimension' => 'Dimensión',
	'Class:URP_ProfileProjection/Attribute:dimension+' => 'Dimensión de aplicación',
	'Class:URP_ProfileProjection/Attribute:profileid' => 'Perfil',
	'Class:URP_ProfileProjection/Attribute:profileid+' => 'Uso del Perfil',
	'Class:URP_ProfileProjection/Attribute:profile' => 'Perfil',
	'Class:URP_ProfileProjection/Attribute:profile+' => 'Nombre del Perfil',
	'Class:URP_ProfileProjection/Attribute:value' => 'Valor de la Expresión',
	'Class:URP_ProfileProjection/Attribute:value+' => 'Expresión OQL (usando $user) | constante |  | +código de atributo',
	'Class:URP_ProfileProjection/Attribute:attribute' => 'Atributo',
	'Class:URP_ProfileProjection/Attribute:attribute+' => 'Código de Atributo Destino (opcional)',
));

//
// Class: URP_ClassProjection
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
	'Class:URP_ClassProjection' => 'Proyecciones de Clase',
	'Class:URP_ClassProjection+' => 'Proyecciones de Clase',
	'Class:URP_ClassProjection/Attribute:dimensionid' => 'Dimensión',
	'Class:URP_ClassProjection/Attribute:dimensionid+' => 'Dimensión de Aplicación',
	'Class:URP_ClassProjection/Attribute:dimension' => 'Dimensión',
	'Class:URP_ClassProjection/Attribute:dimension+' => 'Dimensión de Aplicación',
	'Class:URP_ClassProjection/Attribute:class' => 'Clase',
	'Class:URP_ClassProjection/Attribute:class+' => 'Clase Destino',
	'Class:URP_ClassProjection/Attribute:value' => 'Valor de la Expresión',
	'Class:URP_ClassProjection/Attribute:value+' => 'Expresión OQL (usando $this) | constante |  | +código de atributo',
	'Class:URP_ClassProjection/Attribute:attribute' => 'Atributo',
	'Class:URP_ClassProjection/Attribute:attribute+' => 'Código de Atributo Destino (opcional)',
));

//
// Class: URP_ActionGrant
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
	'Class:URP_ActionGrant' => 'Permisos sobre Acciones',
	'Class:URP_ActionGrant+' => 'Permisos sobre Acciones',
	'Class:URP_ActionGrant/Attribute:profileid' => 'Perfil',
	'Class:URP_ActionGrant/Attribute:profileid+' => 'Uso del Perfil',
	'Class:URP_ActionGrant/Attribute:profile' => 'Perfil',
	'Class:URP_ActionGrant/Attribute:profile+' => 'Uso del Perfil',
	'Class:URP_ActionGrant/Attribute:class' => 'Clase',
	'Class:URP_ActionGrant/Attribute:class+' => 'Clase Destino',
	'Class:URP_ActionGrant/Attribute:permission' => 'Permisos',
	'Class:URP_ActionGrant/Attribute:permission+' => '¿Permitido o No Permitido?',
	'Class:URP_ActionGrant/Attribute:permission/Value:yes' => 'si',
	'Class:URP_ActionGrant/Attribute:permission/Value:yes+' => 'si',
	'Class:URP_ActionGrant/Attribute:permission/Value:no' => 'no',
	'Class:URP_ActionGrant/Attribute:permission/Value:no+' => 'no',
	'Class:URP_ActionGrant/Attribute:action' => 'Acción',
	'Class:URP_ActionGrant/Attribute:action+' => 'Operaciones a realizar en la clase especificada',
));

//
// Class: URP_StimulusGrant
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
	'Class:URP_StimulusGrant' => 'Permisos de Cambio de Estado',
	'Class:URP_StimulusGrant+' => 'Permisos de Cambio de Estado en el Ciclo de Vida del Objeto',
	'Class:URP_StimulusGrant/Attribute:profileid' => 'Perfil',
	'Class:URP_StimulusGrant/Attribute:profileid+' => 'Uso del perfil',
	'Class:URP_StimulusGrant/Attribute:profile' => 'Perfil',
	'Class:URP_StimulusGrant/Attribute:profile+' => 'Uso del perfil',
	'Class:URP_StimulusGrant/Attribute:class' => 'Clase',
	'Class:URP_StimulusGrant/Attribute:class+' => 'Clase destino',
	'Class:URP_StimulusGrant/Attribute:permission' => 'Permiso',
	'Class:URP_StimulusGrant/Attribute:permission+' => '¿Permitido o No Permitido?',
	'Class:URP_StimulusGrant/Attribute:permission/Value:yes' => 'si',
	'Class:URP_StimulusGrant/Attribute:permission/Value:yes+' => 'si',
	'Class:URP_StimulusGrant/Attribute:permission/Value:no' => 'no',
	'Class:URP_StimulusGrant/Attribute:permission/Value:no+' => 'no',
	'Class:URP_StimulusGrant/Attribute:stimulus' => 'Cambio de Estado',
	'Class:URP_StimulusGrant/Attribute:stimulus+' => 'Código de Cambio de Estado',
));

//
// Class: URP_AttributeGrant
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
	'Class:URP_AttributeGrant' => 'Permisos en Atributos',
	'Class:URP_AttributeGrant+' => 'Permisos en Atributos',
	'Class:URP_AttributeGrant/Attribute:actiongrantid' => 'Concesión de Acción',
	'Class:URP_AttributeGrant/Attribute:actiongrantid+' => 'concesión de Acción',
	'Class:URP_AttributeGrant/Attribute:attcode' => 'Atributo',
	'Class:URP_AttributeGrant/Attribute:attcode+' => 'Código de Atributo',
));

//
// Class: UserDashboard
//
Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
	'Class:UserDashboard' => 'Tablero de Usuario',
	'Class:UserDashboard+' => 'Tablero de Usuario',
	'Class:UserDashboard/Attribute:user_id' => 'Usuario',
	'Class:UserDashboard/Attribute:user_id+' => 'Usuario',
	'Class:UserDashboard/Attribute:menu_code' => 'Código de Menú',
	'Class:UserDashboard/Attribute:menu_code+' => 'Código de Menú',
	'Class:UserDashboard/Attribute:contents' => 'Contenidos',
	'Class:UserDashboard/Attribute:contents+' => 'Contenidos',
));

//
// Expression to Natural language
//
Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
	'Expression:Unit:Short:DAY' => 'd',
	'Expression:Unit:Short:WEEK' => 's',
	'Expression:Unit:Short:MONTH' => 'm',
	'Expression:Unit:Short:YEAR' => 'a',
));


//
// String from the User Interface: menu, messages, buttons, etc...
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
	'BooleanLabel:yes' => 'Si',
	'BooleanLabel:no' => 'No',
	'UI:Login:Title' => 'Inicio de Sesión',
	'Menu:WelcomeMenu' => 'Bienvenido', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenu+' => 'Bienvenido a iTop', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenuPage' => 'Bienvenido', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenuPage+' => 'Bienvenido a iTop', // Duplicated into itop-welcome-itil (will be removed from here...)
	'UI:WelcomeMenu:Title' => 'Bienvenido a iTop',

	'UI:WelcomeMenu:LeftBlock' => '<p>iTop es un completo portal de administración de servicios de TI basado en código abierto.</p>
<p>Incluye:</p>
<ul><li>Una CMDB completa (Base de datos de Configuraciones) para documentar y manejar el inventario de TI.</li>
<li>Un módulo de Administración de Incidentes, para llevar el seguimiento y comunicar los eventos que están afectando a los servicios TI.</li>
<li>Un módulo de Administración de Cambios para planear y llevar el seguimiento de cambios hechos al ambiente de TI.</li>
<li>Una base de Conocimiento para acelerar la correción de Incidentes.</li>
<li>Un módulo de Cortes/Caídas para documentar todas las caídas planeadas o no y notificar a los contactos del caso.</li>
<li>Tableros de Control para rápidamente tener visión general del ambiente de TI.</li>
</ul>
<p>Todos los módulos pueden ser configurados, paso a paso, individual e independientemente de los otros.</p>',

	'UI:WelcomeMenu:RightBlock' => '<p>iTop está orientado a los proveedores de servicios, le permite al personal de TI administrar fácilmente múltiples Organizaciones.
<p>iTop, provee un conjunto de funciones de procesos de negocio que: </p>
<ul><li>Mejora la efectividad de la adminitración de TI</li> 
<li>Dirige el desempeño de la operaciones de TI</li> 
<li>Incrementa la satisfacción del cliente y provee a los ejecutivos con detalles del desempeño del negocio.</li>
</ul>
</p>
<p>iTop es completamente abierto para ser integrado con su actual infraestructura de administración de TI.</p>
<p>
<p>Adoptar esta nueva generación de portales de operaciones de TI le ayudará a:</p>
<ul><li>Mejorar la administración de entornos de TI más y más complejos.</li>
<li>Implementar los procesos de ITIL a su propio ritmo.</li>
<li>Administrar el bien más importante de su infraestructura de TI: La Documentación.</li>
</ul>
</p>',
	'UI:WelcomeMenu:AllOpenRequests' => 'Requerimientos Abiertos: %1$d',
	'UI:WelcomeMenu:MyCalls' => 'Mis Requerimientos',
	'UI:WelcomeMenu:OpenIncidents' => 'Incidentes Abiertos: %1$d',
	'UI:WelcomeMenu:AllConfigItems' => 'Elementos de Configuración: %1$d',
	'UI:WelcomeMenu:MyIncidents' => 'Incidentes Asignados a Mí',
	'UI:AllOrganizations' => ' Todas las Organizaciones',
	'UI:YourSearch' => 'Su búsqueda',
	'UI:LoggedAsMessage' => 'Conectado como %1$s',
	'UI:LoggedAsMessage+Admin' => 'Conectado como %1$s (Administrator)',
	'UI:Button:Logoff' => 'Cerrar Sesión',
	'UI:Button:GlobalSearch' => 'Buscar',
	'UI:Button:Search' => 'Buscar',
	'UI:Button:Query' => 'Consultar',
	'UI:Button:Ok' => 'Ok',
	'UI:Button:Save' => 'Guardar',
	'UI:Button:Cancel' => 'Cancelar',
	'UI:Button:Close' => 'Cerrar',
	'UI:Button:Apply' => 'Aplicar',
	'UI:Button:Back' => '<< Anterior',
	'UI:Button:Restart' => '|<< Reiniciar',
	'UI:Button:Next' => 'Siguiente >>',
	'UI:Button:Finish' => 'Finalizar',
	'UI:Button:DoImport' => '¡Importar los datos!',
	'UI:Button:Done' => 'Listo',
	'UI:Button:SimulateImport' => 'Simular la Importación',
	'UI:Button:Test' => 'Probar',
	'UI:Button:Evaluate' => 'Evaluar',
	'UI:Button:Evaluate:Title' => 'Evaluar (Ctrl+Enter)',
	'UI:Button:AddObject' => 'Agregar',
	'UI:Button:BrowseObjects' => 'Examinar',
	'UI:Button:Add' => 'Agregar ',
	'UI:Button:AddToList' => '<< Agregar',
	'UI:Button:RemoveFromList' => 'Remover >>',
	'UI:Button:FilterList' => 'Filtrar',
	'UI:Button:Create' => 'Crear',
	'UI:Button:Delete' => 'Borrar',
	'UI:Button:Rename' => 'Renombrar',
	'UI:Button:ChangePassword' => 'Cambiar Contraseña',
	'UI:Button:ResetPassword' => 'Restablecer Contraseña',
	'UI:Button:Insert' => 'Insertar',
	'UI:Button:More' => 'Más',
	'UI:Button:Less' => 'Menos',
	'UI:Button:Wait' => 'Please wait while updating fields~~',
	'UI:Treeview:CollapseAll' => 'Collapse All~~',
	'UI:Treeview:ExpandAll' => 'Expand All~~',

	'UI:SearchToggle' => 'Buscar',
	'UI:ClickToCreateNew' => 'Crear %1$s',
	'UI:SearchFor_Class' => 'Buscar %1$s',
	'UI:NoObjectToDisplay' => 'Ninguna Información por Visualizar.',
	'UI:Error:SaveFailed' => 'El objeto no puede ser guardado :',
	'UI:Error:MandatoryTemplateParameter_object_id' => 'El parámetro object_id es obligatorio cuando link_attr es especificado. Verifique la definición de la plantilla de visualización.',
	'UI:Error:MandatoryTemplateParameter_target_attr' => 'El parámetro target_attr es obligatorio cuando link_attr es especificado. Verifique la definición de la plantilla de visualización.',
	'UI:Error:MandatoryTemplateParameter_group_by' => 'El parámetro group_by es obligatorio. Verifique la definición de la plantilla de visualización.',
	'UI:Error:InvalidGroupByFields' => 'La lista de campos para agrupar por: "%1$s" es invalida.',
	'UI:Error:UnsupportedStyleOfBlock' => 'Error: Estilo de bloque no soportado: "%1$s".',
	'UI:Error:IncorrectLinkDefinition_LinkedClass_Class' => 'Definición de vínculo incorrecto: la clase de objeto a administrar : %1$s no fue encontrada como clave externa en la clase %2$s',
	'UI:Error:Object_Class_Id_NotFound' => 'No se encontro el objeto: %1$s:%2$d.',
	'UI:Error:WizardCircularReferenceInDependencies' => 'Error: Verifique el modelo de datos, Existen referencias circulares  en la dependencias entre los campos.',
	'UI:Error:UploadedFileTooBig' => 'archivo cargado es muy grande. (Tamaño máximo permitido es de %1$s. Verifique su configuración de PHP para upload_max_filesize.',
	'UI:Error:UploadedFileTruncated.' => 'El archivo cargado ha sido truncado!',
	'UI:Error:NoTmpDir' => 'El directorio temporal no ha sido definido.',
	'UI:Error:CannotWriteToTmp_Dir' => 'No fue posible escribir el archivo temporal al disco. upload_tmp_dir = "%1$s".',
	'UI:Error:UploadStoppedByExtension_FileName' => 'Carga de archivo interrumpida por la extension. (Nombre de archivo original = "%1$s").',
	'UI:Error:UploadFailedUnknownCause_Code' => 'Carga de archivo fallida, causa desconocida. (Codigo de error = "%1$s").',

	'UI:Error:1ParametersMissing' => 'Error: El siguiente parámetro debe ser especificado para esta operacion: %1$s.',
	'UI:Error:2ParametersMissing' => 'Error: Los siguientes parámetros deben ser especificados para esta operacion: %1$s y %2$s.',
	'UI:Error:3ParametersMissing' => 'Error: Los siguientes parámetros deben ser especificados para esta operacion: %1$s, %2$s y %3$s.',
	'UI:Error:4ParametersMissing' => 'Error: Los siguientes parámetros deben ser especificados para esta operacion: %1$s, %2$s, %3$s y %4$s.',
	'UI:Error:IncorrectOQLQuery_Message' => 'Error: Consulta OQL incorrecta: %1$s',
	'UI:Error:AnErrorOccuredWhileRunningTheQuery_Message' => 'Se ha producido un error al ejecutar la consulta: %1$s',
	'UI:Error:ObjectAlreadyUpdated' => 'Error: el objeta ha sido previamente actualizado.',
	'UI:Error:ObjectCannotBeUpdated' => 'Error: el objeto no puede ser actualizado.',
	'UI:Error:ObjectsAlreadyDeleted' => 'Error: los objetos ya han sido borrados!',
	'UI:Error:BulkDeleteNotAllowedOn_Class' => 'No esta autorizado a borrar un lote de de objetos de la clase %1$s',
	'UI:Error:DeleteNotAllowedOn_Class' => 'No esta autorizado a borrar objetos del la clase %1$s',
	'UI:Error:BulkModifyNotAllowedOn_Class' => 'No esta autorizado a actualizar un lote de de objetos de la clase %1$s',
	'UI:Error:ObjectAlreadyCloned' => 'Error: el objeto ha sido previamente duplicado!',
	'UI:Error:ObjectAlreadyCreated' => 'Error: el objeto ha sido previamente creado!',
	'UI:Error:Invalid_Stimulus_On_Object_In_State' => 'Error: estimulo invalido "%1$s" en objeto %2$s en estado "%3$s".',
	'UI:Error:InvalidDashboardFile' => 'Error: invalid dashboard file~~',
	'UI:Error:InvalidDashboard' => 'Error: invalid dashboard~~',
	'UI:Error:MaintenanceMode' => 'La aplicación se encuentra actualmente en mantenimiento',
	'UI:Error:MaintenanceTitle' => 'Maintenance~~',

	'UI:GroupBy:Count' => 'Cuenta',
	'UI:GroupBy:Count+' => 'Número de Elementos',
	'UI:CountOfObjects' => '%1$d Elementos cumplen Criterio.',
	'UI_CountOfObjectsShort' => '%1$d Elemento(s)',
	'UI:NoObject_Class_ToDisplay' => 'No hay %1$s para Mostrar',
	'UI:History:LastModified_On_By' => 'Última Modificación el %1$s por %2$s.',
	'UI:HistoryTab' => 'Historia',
	'UI:NotificationsTab' => 'Notificaciones',
	'UI:History:BulkImports' => 'Historia',
	'UI:History:BulkImports+' => 'Lista de importaciones CSV (últimas importaciones primero)',
	'UI:History:BulkImportDetails' => 'Cambios resultantes de la importación CVS realizada en %1$s (por %2$s)',
	'UI:History:Date' => 'Fecha',
	'UI:History:Date+' => 'Fecha del Cambio',
	'UI:History:User' => 'Usuario',
	'UI:History:User+' => 'Usuario que hizo el Cambio',
	'UI:History:Changes' => 'Cambios',
	'UI:History:Changes+' => 'Cambios hechos al objeto',
	'UI:History:StatsCreations' => 'Creado',
	'UI:History:StatsCreations+' => 'Cuenta de objetos creados',
	'UI:History:StatsModifs' => 'Modificado',
	'UI:History:StatsModifs+' => 'Cuenta de objetos modificados',
	'UI:History:StatsDeletes' => 'Borrados',
	'UI:History:StatsDeletes+' => 'Cuenta de objetos borrados',
	'UI:Loading' => 'Cargando',
	'UI:Menu:Actions' => 'Acciones',
	'UI:Menu:OtherActions' => 'Otras Acciones',
	'UI:Menu:New' => 'Nuevo',
	'UI:Menu:Add' => 'Agregar',
	'UI:Menu:Manage' => 'Administrar',
	'UI:Menu:EMail' => 'Enviar por Correo Electrónico',
	'UI:Menu:CSVExport' => 'Exportar a CSV...',
	'UI:Menu:Modify' => 'Modificar',
	'UI:Menu:Delete' => 'Borrar',
	'UI:Menu:BulkDelete' => 'Borrar',
	'UI:UndefinedObject' => 'No Definido',
	'UI:Document:OpenInNewWindow:Download' => 'abrir en nueva ventana: %1$s, Descargar: %2$s',
	'UI:SplitDateTime-Date' => 'fecha',
	'UI:SplitDateTime-Time' => 'hora',
	'UI:TruncatedResults' => 'Mostrando %1$d objetos de %2$d',
	'UI:DisplayAll' => 'Mostrar todo',
	'UI:CollapseList' => 'Colapsar',
	'UI:CountOfResults' => '%1$d objeto(s)',
	'UI:ChangesLogTitle' => 'Registro de cambios (%1$d):',
	'UI:EmptyChangesLogTitle' => 'Registro de cambios esta vacio',
	'UI:SearchFor_Class_Objects' => 'Buscar %1$s',
	'UI:OQLQueryBuilderTitle' => 'Constructor de consultas OQL',
	'UI:OQLQueryTab' => 'Consulta OQL',
	'UI:SimpleSearchTab' => 'Busqueda simple',
	'UI:Details+' => 'Detalles',
	'UI:SearchValue:Any' => '* Cualquiera *',
	'UI:SearchValue:Mixed' => '* mezclado *',
	'UI:SearchValue:NbSelected' => '# seleccionado',
	'UI:SearchValue:CheckAll' => 'Seleccionar Todo',
	'UI:SearchValue:UncheckAll' => 'Deseleccionar Todo',
	'UI:SelectOne' => '-- Seleccione uno --',
	'UI:Login:Welcome' => 'Bienvenido a iTop',
	'UI:Login:IncorrectLoginPassword' => 'Usuario/Contraseña incorrecto, por favor intente otra vez.',
	'UI:Login:IdentifyYourself' => 'Identifiquese antes de continuar',
	'UI:Login:UserNamePrompt' => 'Usuario   ',
	'UI:Login:PasswordPrompt' => 'Contraseña',
	'UI:Login:ForgotPwd' => '¿Olvidó su contraseña?',
	'UI:Login:ForgotPwdForm' => 'Olvido de Contraseña',
	'UI:Login:ForgotPwdForm+' => 'iTop puede enviarle un correo en el cual encontrará las instrucciones a seguir para restablecer su contraseña.',
	'UI:Login:ResetPassword' => 'Enviar Ahora',
	'UI:Login:ResetPwdFailed' => 'Error al enviar correo-e: %1$s',
	'UI:Login:SeparatorOr' => 'Or~~',

	'UI:ResetPwd-Error-WrongLogin' => '\'%1$s\' no es un usuario válido',
	'UI:ResetPwd-Error-NotPossible' => 'Cuentas externas no permiten restablecimiento de contraseña.',
	'UI:ResetPwd-Error-FixedPwd' => 'La cuenta no permite restablecimiento de contraseña.',
	'UI:ResetPwd-Error-NoContact' => 'La cuenta no está asociada a una persona.',
	'UI:ResetPwd-Error-NoEmailAtt' => 'La cuenta no está asociada a una persona con correo electrónico. Por favor contacte al administrador.',
	'UI:ResetPwd-Error-NoEmail' => 'Falta dirección de correo electrónico. Por favor contacte al administrador.',
	'UI:ResetPwd-Error-Send' => 'Falla al envar un correo. Por favor contacte al administrador.',
	'UI:ResetPwd-EmailSent' => 'Please check your email box and follow the instructions. If you receive no email, please check the login you typed.~~',
	'UI:ResetPwd-EmailSubject' => 'Restablecer contraseña de iTop',
	'UI:ResetPwd-EmailBody' => '<body><p>Ha solicitado restablecer su contraseña en iTop.</p><p>Por favor de click en la siguiente liga: <a href="%1$s">proporcione una nueva contraseña</a></p>.',

	'UI:ResetPwd-Title' => 'Restablecer Contraseña',
	'UI:ResetPwd-Error-InvalidToken' => 'Lo siento, tal vez su contraseña ya ha sido cambiada, o ha recibido varios correos electrónicos. Por favor asegurese de haber dado click a la liga del último correo recibido.',
	'UI:ResetPwd-Error-EnterPassword' => 'Contraseña Nueva para \'%1$s\'.',
	'UI:ResetPwd-Ready' => 'La contraseña ha sido cambiada.',
	'UI:ResetPwd-Login' => 'Click aquí para conectarse ',

	'UI:Login:About' => '',
	'UI:Login:ChangeYourPassword' => 'Cambie su Contraseña',
	'UI:Login:OldPasswordPrompt' => 'Contraseña Actual',
	'UI:Login:NewPasswordPrompt' => 'Contraseña Nueva',
	'UI:Login:RetypeNewPasswordPrompt' => 'Confirme Contraseña Nueva',
	'UI:Login:IncorrectOldPassword' => 'Error: la Contraseña Anterior es Incorrecta',
	'UI:LogOffMenu' => 'Cerrar Sesión',
	'UI:LogOff:ThankYou' => 'Gracias por usar iTop',
	'UI:LogOff:ClickHereToLoginAgain' => 'Click aquí para conectarse nuevamente',
	'UI:ChangePwdMenu' => 'Cambiar Contraseña',
	'UI:Login:PasswordChanged' => '¡Contraseña Exitosamente Cambiada!',
	'UI:AccessRO-All' => 'iTop está en modo de solo lectura',
	'UI:AccessRO-Users' => 'iTop está en modo de solo lectura para usuarios',
	'UI:ApplicationEnvironment' => 'Ambiente: %1$s',
	'UI:Login:RetypePwdDoesNotMatch' => '¡La Nueva Contraseña y su Confirmación No Coinciden!',
	'UI:Button:Login' => 'Entrar',
	'UI:Login:Error:AccessRestricted' => 'El acceso a iTop está restringido. Por favor contacte al Administrador de iTop.',
	'UI:Login:Error:AccessAdmin' => 'Acceso restringido a usuarios con privilegio de administrador. Por favor contacte al Administrador de iTop.',
	'UI:Login:Error:WrongOrganizationName' => 'Unknown organization~~',
	'UI:Login:Error:MultipleContactsHaveSameEmail' => 'Multiple contacts have the same e-mail~~',
	'UI:Login:Error:NoValidProfiles' => 'No valid profile provided~~',
	'UI:CSVImport:MappingSelectOne' => '-- seleccione uno --',
	'UI:CSVImport:MappingNotApplicable' => '-- ignore este campo --',
	'UI:CSVImport:NoData' => 'Conjunto de datos vacío..., por favor provea algun dato.',
	'UI:Title:DataPreview' => 'Vista previa de datos',
	'UI:CSVImport:ErrorOnlyOneColumn' => 'Error: Los datos sólo contienen una columna. ¿Seleccionó el separador de campos adecuado?',
	'UI:CSVImport:FieldName' => 'Campo %1$d',
	'UI:CSVImport:DataLine1' => 'Linea de datos 1',
	'UI:CSVImport:DataLine2' => 'Linea de datos 2',
	'UI:CSVImport:idField' => 'Id (Clave Primaria)',
	'UI:Title:BulkImport' => 'iTop - Importación por Lotes',
	'UI:Title:BulkImport+' => 'Asistente de Importación Archivos CSV',
	'UI:Title:BulkSynchro_nbItem_ofClass_class' => 'Sincronización de %1$d objetos de la clase %2$s',
	'UI:CSVImport:ClassesSelectOne' => '-- seleccione uno --',
	'UI:CSVImport:ErrorExtendedAttCode' => 'Error Interno: "%1$s" es un código incorrecto debido a que "%2$s" NO es una clave externa de la clase "%3$s"',
	'UI:CSVImport:ObjectsWillStayUnchanged' => '%1$d objeto(s) permanecerá sin cambio.',
	'UI:CSVImport:ObjectsWillBeModified' => '%1$d objeto(s) será modificado.',
	'UI:CSVImport:ObjectsWillBeAdded' => '%1$d objeto(s) será agregado.',
	'UI:CSVImport:ObjectsWillHaveErrors' => '%1$d objeto(s) tendrá error.',
	'UI:CSVImport:ObjectsRemainedUnchanged' => '%1$d objeto(s) permanencen sin cambio.',
	'UI:CSVImport:ObjectsWereModified' => '%1$d objeto(s) será modificado.',
	'UI:CSVImport:ObjectsWereAdded' => '%1$d objeto(s) fué agregado.',
	'UI:CSVImport:ObjectsHadErrors' => '%1$d objeto(s) tuvo errores.',
	'UI:Title:CSVImportStep2' => 'Paso 2 de 5: Opciones de Datos CSV',
	'UI:Title:CSVImportStep3' => 'Paso 3 de 5: Mapeo de Datos',
	'UI:Title:CSVImportStep4' => 'Paso 4 de 5: Simular Importación',
	'UI:Title:CSVImportStep5' => 'Paso 5 de 5: Importación Completada',
	'UI:CSVImport:LinesNotImported' => 'Líneas que no pudieron ser cargadas:',
	'UI:CSVImport:LinesNotImported+' => 'Las siguientes líneas no pudieron ser importadas porque contienen errores',
	'UI:CSVImport:SeparatorComma+' => ', (coma)',
	'UI:CSVImport:SeparatorSemicolon+' => '; (punto y coma)',
	'UI:CSVImport:SeparatorTab+' => 'Tabulador',
	'UI:CSVImport:SeparatorOther' => 'Otro:',
	'UI:CSVImport:QualifierDoubleQuote+' => '" (comilla doble)',
	'UI:CSVImport:QualifierSimpleQuote+' => '\' (comilla simple)',
	'UI:CSVImport:QualifierOther' => 'Otro:',
	'UI:CSVImport:TreatFirstLineAsHeader' => 'Use la primera línea como encabezado de columna(nombre de columnas))',
	'UI:CSVImport:Skip_N_LinesAtTheBeginning' => 'Omitir %1$s linea(s) al inicio de el archivo',
	'UI:CSVImport:CSVDataPreview' => 'Vista Previa de los Datos CSV',
	'UI:CSVImport:SelectFile' => 'Seleccione el Archivo a Importar:',
	'UI:CSVImport:Tab:LoadFromFile' => 'Cargar desde Archivo',
	'UI:CSVImport:Tab:CopyPaste' => 'Copiar y Pegar Datos',
	'UI:CSVImport:Tab:Templates' => 'Plantillas',
	'UI:CSVImport:PasteData' => 'Pegue los Datos a Importar:',
	'UI:CSVImport:PickClassForTemplate' => 'Seleccione la Plantilla a Utilizar: ',
	'UI:CSVImport:SeparatorCharacter' => 'Caracter Separador:',
	'UI:CSVImport:TextQualifierCharacter' => 'Caracter para calificar como texto',
	'UI:CSVImport:CommentsAndHeader' => 'Comentarios y encabezado',
	'UI:CSVImport:SelectClass' => 'Seleccione la clase a importar:',
	'UI:CSVImport:AdvancedMode' => 'Modo Avanzado',
	'UI:CSVImport:AdvancedMode+' => 'En modo avanzado el "id" (clave primaria) de los objetos puede ser usado para actualizar y renombrar objetos.Sin embargo, la columna "id" (si esta presente) solo puede ser usado como criterio de busqueda y no puede ser combinado con ningun otro criterio de busqueda.',
	'UI:CSVImport:SelectAClassFirst' => 'Para configurar el mapeo, primero seleccione un clase.',
	'UI:CSVImport:HeaderFields' => 'Campos',
	'UI:CSVImport:HeaderMappings' => 'Mapeo',
	'UI:CSVImport:HeaderSearch' => '¿Buscar?',
	'UI:CSVImport:AlertIncompleteMapping' => 'Por favor seleccione un mapeo para cada categoria.',
	'UI:CSVImport:AlertMultipleMapping' => 'Por favor asegurese que el campo objetivo esté mapeado una sola vez',
	'UI:CSVImport:AlertNoSearchCriteria' => 'Por favor seleccione al menos un criterio de busqueda',
	'UI:CSVImport:Encoding' => 'Código de Caracteres',
	'UI:UniversalSearchTitle' => 'iTop - Busqueda Universal',
	'UI:UniversalSearch:Error' => 'Error: %1$s',
	'UI:UniversalSearch:LabelSelectTheClass' => 'Seleccione la clase a buscar: ',

	'UI:CSVReport-Value-Modified' => 'Modificado',
	'UI:CSVReport-Value-SetIssue' => 'No puede ser modificado - motivo: %1$s',
	'UI:CSVReport-Value-ChangeIssue' => 'No puede ser cambiado a %1$s - motivo: %2$s',
	'UI:CSVReport-Value-NoMatch' => 'No hay Coincidencias',
	'UI:CSVReport-Value-Missing' => 'Falta valor obligatorio',
	'UI:CSVReport-Value-Ambiguous' => 'Ambigüedad: encontrados %1$s objetos',
	'UI:CSVReport-Row-Unchanged' => 'Sin Cambios',
	'UI:CSVReport-Row-Created' => 'Creados',
	'UI:CSVReport-Row-Updated' => 'Actualizados %1$d cols',
	'UI:CSVReport-Row-Disappeared' => 'desaparecidos, cambiados %1$d cols',
	'UI:CSVReport-Row-Issue' => 'Asunto: %1$s',
	'UI:CSVReport-Value-Issue-Null' => 'No se permiten valores nulos',
	'UI:CSVReport-Value-Issue-NotFound' => 'Objeto no encontrado',
	'UI:CSVReport-Value-Issue-FoundMany' => 'Encontradas %1$d coincidencias',
	'UI:CSVReport-Value-Issue-Readonly' => 'El atributo \'%1$s\' es de solo lectura y nno puede ser modificado (valor actual: %2$s, valor propuesto: %3$s)',
	'UI:CSVReport-Value-Issue-Format' => 'Falla al procesar entrada: %1$s',
	'UI:CSVReport-Value-Issue-NoMatch' => 'Valor no esperado para el atributo \'%1$s\': no hay coincidencias, verifique ortografía',
	'UI:CSVReport-Value-Issue-Unknown' => 'Valor inesperado para el atributo \'%1$s\': %2$s',
	'UI:CSVReport-Row-Issue-Inconsistent' => 'Atributos no consistentes entre ellos: %1$s',
	'UI:CSVReport-Row-Issue-Attribute' => 'Valor(es) inesperado(s) para el atributo',
	'UI:CSVReport-Row-Issue-MissingExtKey' => 'No puede ser creado, debido a llaves externas faltantes: %1$s',
	'UI:CSVReport-Row-Issue-DateFormat' => 'Formato de fecha incorrecto',
	'UI:CSVReport-Row-Issue-Reconciliation' => 'Falla al reconciliar',
	'UI:CSVReport-Row-Issue-Ambiguous' => 'Reconciliación Ambigua',
	'UI:CSVReport-Row-Issue-Internal' => 'Error Interno: %1$s, %2$s',

	'UI:CSVReport-Icon-Unchanged' => 'Sin Cambio',
	'UI:CSVReport-Icon-Modified' => 'Modificado',
	'UI:CSVReport-Icon-Missing' => 'Faltante',
	'UI:CSVReport-Object-MissingToUpdate' => 'Objeto Faltante: erá Actualizado',
	'UI:CSVReport-Object-MissingUpdated' => 'Objeto Faltante: Actualizado',
	'UI:CSVReport-Icon-Created' => 'Creado',
	'UI:CSVReport-Object-ToCreate' => 'Objeto será creado',
	'UI:CSVReport-Object-Created' => 'Objeto creado',
	'UI:CSVReport-Icon-Error' => 'Error',
	'UI:CSVReport-Object-Error' => 'ERROR: %1$s',
	'UI:CSVReport-Object-Ambiguous' => 'AMBIGÜEDAD: %1$s',
	'UI:CSVReport-Stats-Errors' => '%1$.0f %% de los objetos cargados tienen errores y serán ignorados.',
	'UI:CSVReport-Stats-Created' => '%1$.0f %% de los objetos cargados serán creados.',
	'UI:CSVReport-Stats-Modified' => '%1$.0f %% de los objetos cargados serán moficados.',

	'UI:CSVExport:AdvancedMode' => 'Modo Avanzado',
	'UI:CSVExport:AdvancedMode+' => 'En el modo avanzado, varias columnas son agregadas a la exportación: ID del objeto, ID de las llaves externas y los atributos de reconciliación.',
	'UI:CSVExport:LostChars' => 'Errores de Código de Caracteres',
	'UI:CSVExport:LostChars+' => 'El archivo descargado será codificado a %1$s. iTop detectó alguos caracteres que no son compatibles con este formato. Esos caracteres serán reemplazados por un sustituto (ejem.: caracteres sin acento), o serán descartados. Puede copiar/pegar datos desde su navegador de internet.  Alternativamente, puede contactar al administrador para cambiar el código de caracteres (Ver parámetro \'csv_file_default_charset\').',

	'UI:Audit:Title' => 'iTop - Auditoria a CMDB',
	'UI:Audit:InteractiveAudit' => 'Auditoria Interactiva',
	'UI:Audit:HeaderAuditRule' => 'Reglas de Auditoria',
	'UI:Audit:HeaderNbObjects' => '# Objetos',
	'UI:Audit:HeaderNbErrors' => '# Errores',
	'UI:Audit:PercentageOk' => '% Ok',
	'UI:Audit:ErrorIn_Rule_Reason' => 'Error de OQL en la Regla %1$s: %2$s.',
	'UI:Audit:ErrorIn_Category_Reason' => 'Error de OQL en la Categoría %1$s: %2$s.',

	'UI:RunQuery:Title' => 'iTop - Evaluación de consultas OQL',
	'UI:RunQuery:QueryExamples' => 'Explorador de Consultas',
	'UI:RunQuery:HeaderPurpose' => 'Propósito',
	'UI:RunQuery:HeaderPurpose+' => 'Explicación acerca de la consulta',
	'UI:RunQuery:HeaderOQLExpression' => 'Expresión OQL',
	'UI:RunQuery:HeaderOQLExpression+' => 'La consulta en sintáxis OQL',
	'UI:RunQuery:ExpressionToEvaluate' => 'Expresión a evaluar: ',
	'UI:RunQuery:MoreInfo' => 'Más información acerca de la consulta: ',
	'UI:RunQuery:DevelopedQuery' => 'Expresión de consulta rediseñada: ',
	'UI:RunQuery:SerializedFilter' => 'Filtro de serialización: ',
	'UI:RunQuery:DevelopedOQL' => 'Developed OQL~~',
	'UI:RunQuery:DevelopedOQLCount' => 'Developed OQL for count~~',
	'UI:RunQuery:ResultSQLCount' => 'Resulting SQL for count~~',
	'UI:RunQuery:ResultSQL' => 'Resulting SQL~~',
	'UI:RunQuery:Error' => 'Ha ocurrido un error al ejecutar la consulta: %1$s',
	'UI:Query:UrlForExcel' => 'URL para usarse en consultas web de MS-Excel',
	'UI:Query:UrlV1' => 'La lista de campos se ha dejado sin especificación. La página <em>export-V2.php</em> no puede ser invocada sin está información. Por lo tanto, el URL sugerido abajo apunta a la página legada: <em>export.php</em>. Esta versión legada de exportación tiene la siguiente limitación: la lista de campos exportados puede variar, dependiendo del formato de salida y el modelo de datos de iTop. Desea garantizar que la lista de columnas exportadas permanenzcan estables durante la ejecución, entonces debe especificar un valor para el atributo "Campos" y utilice la página <em>export-V2.php</em>.',
	'UI:Schema:Title' => 'Esquema de Objetos en iTop',
	'UI:Schema:CategoryMenuItem' => 'Categoria <b>%1$s</b>',
	'UI:Schema:Relationships' => 'Relaciones',
	'UI:Schema:AbstractClass' => 'Clase Abstracta: Ningún objeto de esta clase puede ser representado.',
	'UI:Schema:NonAbstractClass' => 'Clase NoAbstracta: Objetos de esta clase pueden ser representados.',
	'UI:Schema:ClassHierarchyTitle' => 'Jerarquia de Clases',
	'UI:Schema:AllClasses' => 'Todas las Clases',
	'UI:Schema:ExternalKey_To' => 'Clave Externa a %1$s',
	'UI:Schema:Columns_Description' => 'Columnas: <em>%1$s</em>',
	'UI:Schema:Default_Description' => 'Predeterminar: "%1$s"',
	'UI:Schema:NullAllowed' => 'Permite Nulos',
	'UI:Schema:NullNotAllowed' => 'NO permite Nulos',
	'UI:Schema:Attributes' => 'Atributos',
	'UI:Schema:AttributeCode' => 'Código de Atributo',
	'UI:Schema:AttributeCode+' => 'Código Interno del Atributo',
	'UI:Schema:Label' => 'Etiqueta',
	'UI:Schema:Label+' => 'Etiqueta del Atributo',
	'UI:Schema:Type' => 'Tipo',

	'UI:Schema:Type+' => 'Tipo de dato del Atributo',
	'UI:Schema:Origin' => 'Origen',
	'UI:Schema:Origin+' => 'La clase base en dónde está definido este atributo',
	'UI:Schema:Description' => 'Descripción',
	'UI:Schema:Description+' => 'Descripción del Atributo',
	'UI:Schema:AllowedValues' => 'Valores Permitidos',
	'UI:Schema:AllowedValues+' => 'Restricciones en los posibles valores para este atributo',
	'UI:Schema:MoreInfo' => 'Más información',
	'UI:Schema:MoreInfo+' => 'Más información acerca del campo definido en la base de datos',
	'UI:Schema:SearchCriteria' => 'Criterio de Búsqueda',
	'UI:Schema:FilterCode' => 'Código de Filtro',
	'UI:Schema:FilterCode+' => 'Código de este Criterio de Búsqueda',
	'UI:Schema:FilterDescription' => 'Descripción',
	'UI:Schema:FilterDescription+' => 'Descripción de este Criterio de Búsqueda',
	'UI:Schema:AvailOperators' => 'Operadores Disponibles',
	'UI:Schema:AvailOperators+' => 'Operadores posibles para este Criterio de Búsqueda',
	'UI:Schema:ChildClasses' => 'Clases Hijo',
	'UI:Schema:ReferencingClasses' => 'Clases de Referencia',
	'UI:Schema:RelatedClasses' => 'Clases Relacionadas',
	'UI:Schema:LifeCycle' => 'Ciclo de Vida',
	'UI:Schema:Triggers' => 'Disparadores',
	'UI:Schema:Relation_Code_Description' => 'Relación <em>%1$s</em> (%2$s)',
	'UI:Schema:RelationDown_Description' => 'Abajo: %1$s',
	'UI:Schema:RelationUp_Description' => 'Arriba: %1$s',
	'UI:Schema:RelationPropagates' => '%1$s: propagar a %2$d niveles, consulta: %3$s',
	'UI:Schema:RelationDoesNotPropagate' => '%1$s: no se propaga(%2$d nivel), consulta: %3$s',
	'UI:Schema:Class_ReferencingClasses_From_By' => '%1$s esta referenciado por la clase %2$s a travez de el campo %3$s',
	'UI:Schema:Class_IsLinkedTo_Class_Via_ClassAndAttribute' => '%1$s está vinculado a %2$s a travez de %3$s::<em>%4$s</em>',
	'UI:Schema:Links:1-n' => 'Clases apuntando a %1$s (1:n enlaces):',
	'UI:Schema:Links:n-n' => 'Clases apuntando a %1$s (n:n enlaces):',
	'UI:Schema:Links:All' => 'Gráfico de todos los Casos Relacionados',
	'UI:Schema:NoLifeCyle' => 'No hay Ciclo de Vida definido para esta Clase.',
	'UI:Schema:LifeCycleTransitions' => 'Transiciones',
	'UI:Schema:LifeCyleAttributeOptions' => 'Opciones del Atributo',
	'UI:Schema:LifeCycleHiddenAttribute' => 'Oculto',
	'UI:Schema:LifeCycleReadOnlyAttribute' => 'Solo-lectrura',
	'UI:Schema:LifeCycleMandatoryAttribute' => 'Mandatorio',
	'UI:Schema:LifeCycleAttributeMustChange' => 'Debe cambiar',
	'UI:Schema:LifeCycleAttributeMustPrompt' => 'Se le pedira al usuario que cambie el valor',
	'UI:Schema:LifeCycleEmptyList' => 'Lista Vacía',
	'UI:Schema:ClassFilter' => 'Clase:',
	'UI:Schema:DisplayLabel' => 'Visualización:',
	'UI:Schema:DisplaySelector/LabelAndCode' => 'Etiqueta y código',
	'UI:Schema:DisplaySelector/Label' => 'Etiqueta',
	'UI:Schema:DisplaySelector/Code' => 'Código',
	'UI:Schema:Attribute/Filter' => 'Filtro',
	'UI:Schema:DefaultNullValue' => 'Nulo por Omisión : "%1$s"',
	'UI:LinksWidget:Autocomplete+' => 'Escriba los primeros 3 caracteres...',
	'UI:Edit:TestQuery' => 'Consulta de Prueba',
	'UI:Combo:SelectValue' => '--- seleccione un valor ---',
	'UI:Label:SelectedObjects' => 'Objetos seleccionados: ',
	'UI:Label:AvailableObjects' => 'Objetos disponibles: ',
	'UI:Link_Class_Attributes' => '%1$s atributos',
	'UI:SelectAllToggle+' => 'Seleccionar / Deseleccionar todo',
	'UI:AddObjectsOf_Class_LinkedWith_Class_Instance' => 'Agregar %1$s objetos vinculados con %2$s: %3$s',
	'UI:AddObjectsOf_Class_LinkedWith_Class' => 'Agregar %1$s objetos a vincular con %2$s',
	'UI:ManageObjectsOf_Class_LinkedWith_Class_Instance' => 'Administrar %1$s objetos vinculados con %2$s: %3$s',
	'UI:AddLinkedObjectsOf_Class' => 'Agregar %1$s',
	'UI:RemoveLinkedObjectsOf_Class' => 'Eliminar Seleccionados',
	'UI:Message:EmptyList:UseAdd' => 'La lista esta vacía, use el botón "Agregar" para añadir elementos.',
	'UI:Message:EmptyList:UseSearchForm' => 'Use la forma arriba para buscar objetos a ser agregados.',
	'UI:Wizard:FinalStepTitle' => 'Paso Final: Confirmación',
	'UI:Title:DeletionOf_Object' => 'Borrado de %1$s',
	'UI:Title:BulkDeletionOf_Count_ObjectsOf_Class' => 'Borrado por lote de %1$d objetos de la clase %2$s',
	'UI:Delete:NotAllowedToDelete' => 'No esta autorizado para borrar este objeto',
	'UI:Delete:NotAllowedToUpdate_Fields' => 'No esta autorizado para actualizar el siguiente campo(s): %1$s',
	'UI:Error:ActionNotAllowed' => 'You are not allowed to do this action~~',
	'UI:Error:NotEnoughRightsToDelete' => 'Este objeto no pudo ser borrado porque el usuario actual no posee suficientes permisos',
	'UI:Error:CannotDeleteBecause' => 'Esto objeto no puede ser borrado debido a: %1$s',
	'UI:Error:CannotDeleteBecauseOfDepencies' => 'Este objeto no pudo ser borrado porque algunas operaciones manuales deben ser ejecutadas antes de eso',
	'UI:Error:CannotDeleteBecauseManualOpNeeded' => 'Este objeto no puede ser borrado debido a que algunas operaciones manuales manuales deben ser realizadas antes',
	'UI:Archive_User_OnBehalfOf_User' => '%1$s en nombre de %2$s',
	'UI:Delete:Deleted' => 'Borrado',
	'UI:Delete:AutomaticallyDeleted' => 'Borrado automaticamente',
	'UI:Delete:AutomaticResetOf_Fields' => 'Reinicio automático de campo(s): %1$s',
	'UI:Delete:CleaningUpRefencesTo_Object' => 'Limpiando todas las referencias a %1$s',
	'UI:Delete:CleaningUpRefencesTo_Several_ObjectsOf_Class' => 'Limpiando todas las referencias a %1$d objetos de la clase %2$s',
	'UI:Delete:Done+' => 'Realizado',
	'UI:Delete:_Name_Class_Deleted' => '%1$s - %2$s borrado.',
	'UI:Delete:ConfirmDeletionOf_Name' => 'Borrado de %1$s',
	'UI:Delete:ConfirmDeletionOf_Count_ObjectsOf_Class' => 'Borrado de %1$d objetos de la clase %2$s',
	'UI:Delete:CannotDeleteBecause' => 'No puede ser borrado: %1$s',
	'UI:Delete:ShouldBeDeletedAtomaticallyButNotPossible' => 'Deberia ser borrado automaticamente, pero usted no esta autorizado para hacerlo',
	'UI:Delete:MustBeDeletedManuallyButNotPossible' => 'Debe ser borrado manualmente - pero usted no está autorizado para borrar este objeto, por favor contacte al administrador de la aplicación',
	'UI:Delete:WillBeDeletedAutomatically' => 'Será borrado automaticamente',
	'UI:Delete:MustBeDeletedManually' => 'Debe ser borrado manualmente',
	'UI:Delete:CannotUpdateBecause_Issue' => 'Debe ser actualizado automaticamente, pero: %1$s',
	'UI:Delete:WillAutomaticallyUpdate_Fields' => 'Será actualizado automaticamente (reset: %1$s)',
	'UI:Delete:Count_Objects/LinksReferencing_Object' => '%1$d objetos/vinculos están referenciando %2$s',
	'UI:Delete:Count_Objects/LinksReferencingTheObjects' => '%1$d objetos/vinculos están referenciando algunos de los objetos a ser borrados',
	'UI:Delete:ReferencesMustBeDeletedToEnsureIntegrity' => 'Para asegurar la integridad de la Base de Datos, cualquier referencia debera ser completamente eliminada',
	'UI:Delete:Consequence+' => 'Lo que se hará',
	'UI:Delete:SorryDeletionNotAllowed' => 'Disculpe, usted no está autorizado a eliminar este objeto, vea la explicación detallada abajo',
	'UI:Delete:PleaseDoTheManualOperations' => 'Por favor ejecute las operaciones manuales antes de eliminar este objeto',
	'UI:Delect:Confirm_Object' => 'Por favor confirme que quiere borrar %1$s.',
	'UI:Delect:Confirm_Count_ObjectsOf_Class' => 'Por favor confirme que quiere eliminar los siguientes %1$d objeto de la clase %2$s.',
	'UI:WelcomeToITop' => 'Bienvenido a iTop',
	'UI:DetailsPageTitle' => 'iTop - %1$s - Detalles %2$s',
	'UI:ErrorPageTitle' => 'iTop - Error',
	'UI:ObjectDoesNotExist' => 'Disculpe, este objeto no existe (o no está autorizado para verlo).',
	'UI:ObjectArchived' => 'Este objeto ha sido archivado. Por favor habilité el modo Archivado o contacte al Administrador.',
	'Tag:Archived' => 'Archivado',
	'Tag:Archived+' => 'Sólo puede ser accesible en Modo Archivado',
	'Tag:Obsolete' => 'Obsoleto',
	'Tag:Obsolete+' => 'Excluír de análisis de impacto y resultados de búsqueda',
	'Tag:Synchronized' => 'Sincronizado',
	'ObjectRef:Archived' => 'Archivado',
	'ObjectRef:Obsolete' => 'Obsoleto',
	'UI:SearchResultsPageTitle' => 'iTop - Resultados de la Búsqueda',
	'UI:SearchResultsTitle' => 'Resultados de la Búsqueda',
	'UI:SearchResultsTitle+' => 'Resultados de la Búsqueda',
	'UI:Search:NoSearch' => 'Nada para buscar',
	'UI:Search:NeedleTooShort' => 'La cadena de búsqueda \\"%1$s\\" es demasiado corta. Por favor escriba al menos %2$d caracteres.',
	'UI:Search:Ongoing' => 'Buscando por \\"%1$s\\"',
	'UI:Search:Enlarge' => 'Ampliar la búsqueda',
	'UI:FullTextSearchTitle_Text' => 'Resultados para "%1$s":',
	'UI:Search:Count_ObjectsOf_Class_Found' => '%1$d objeto(s) de la clase %2$s encontrado(s).',
	'UI:Search:NoObjectFound' => 'No se encontraron objetos.',
	'UI:ModificationPageTitle_Object_Class' => 'iTop - %1$s - %2$s modificación',
	'UI:ModificationTitle_Class_Object' => 'Modificación de %1$s: <span class=\\"hilite\\">%2$s</span>',
	'UI:ClonePageTitle_Object_Class' => 'iTop - Duplicar %1$s - %2$s modificación',
	'UI:CloneTitle_Class_Object' => 'Duplicado de %1$s: <span class=\\"hilite\\">%2$s</span>',
	'UI:CreationPageTitle_Class' => 'iTop - Creación de %1$s ',
	'UI:CreationTitle_Class' => 'Creación de %1$s',
	'UI:SelectTheTypeOf_Class_ToCreate' => 'Seleccione el tipo de %1$s a crear:',
	'UI:Class_Object_NotUpdated' => 'No se detectaron cambios, %1$s (%2$s) <strong>no</strong> fue modificado.',
	'UI:Class_Object_Updated' => '%1$s (%2$s) actualizado.',
	'UI:BulkDeletePageTitle' => 'iTop - Eliminar por lote',
	'UI:BulkDeleteTitle' => 'Seleccione los objetos que desea eliminar:',
	'UI:PageTitle:ObjectCreated' => 'Objeto de iTop creado.',
	'UI:Title:Object_Of_Class_Created' => '%1$s - %2$s creado.',
	'UI:Apply_Stimulus_On_Object_In_State_ToTarget_State' => 'Aplicando %1$s en el objeto: %2$s en estado %3$s al estado deseado: %4$s.',
	'UI:ObjectCouldNotBeWritten' => 'el objeto no pudo ser escrito: %1$s',
	'UI:PageTitle:FatalError' => 'iTop - Error Fatal',
	'UI:SystemIntrusion' => 'Acceso denegado. Esta tratando de ejecutar una operación no permitida para usted.',
	'UI:FatalErrorMessage' => 'Error fatal, iTop no puede continuar.',
	'UI:Error_Details' => 'Error: %1$s.',

	'UI:PageTitle:ClassProjections' => 'Administración de usuarios iTop - proyecciones de clases',
	'UI:PageTitle:ProfileProjections' => 'Administración de usuarios iTop - proyecciones de Perfil',
	'UI:UserManagement:Class' => 'Clase',
	'UI:UserManagement:Class+' => 'Clase de objetos',
	'UI:UserManagement:ProjectedObject' => 'Objeto',
	'UI:UserManagement:ProjectedObject+' => 'Objeto proyectado',
	'UI:UserManagement:AnyObject' => '* cualquiera *',
	'UI:UserManagement:User' => 'Usuario',
	'UI:UserManagement:User+' => 'Usuario implicado en la proyección',
	'UI:UserManagement:Profile' => 'Perfil',
	'UI:UserManagement:Profile+' => 'Perfil en el cual se especifico la proyección',
	'UI:UserManagement:Action:Read' => 'Leer',
	'UI:UserManagement:Action:Read+' => 'Leer/Mostrar objetos',
	'UI:UserManagement:Action:Modify' => 'Modificar',
	'UI:UserManagement:Action:Modify+' => 'Crear y editar (modificar) objetos',
	'UI:UserManagement:Action:Delete' => 'Eliminar',
	'UI:UserManagement:Action:Delete+' => 'Eliminar objetos',
	'UI:UserManagement:Action:BulkRead' => 'Lectura por lote (Exportar)',
	'UI:UserManagement:Action:BulkRead+' => 'Listar objetos o exportar masivamente',
	'UI:UserManagement:Action:BulkModify' => 'Modificación masiva',
	'UI:UserManagement:Action:BulkModify+' => 'Crear/Editar masivamente (importar CSV)',
	'UI:UserManagement:Action:BulkDelete' => 'eliminación masiva',
	'UI:UserManagement:Action:BulkDelete+' => 'eliminación masiva de objetos',
	'UI:UserManagement:Action:Stimuli' => 'Stimuli',
	'UI:UserManagement:Action:Stimuli+' => 'Acciones (compound) permitidas',
	'UI:UserManagement:Action' => 'Acción',
	'UI:UserManagement:Action+' => 'Acción ejecutada por el usuario',
	'UI:UserManagement:TitleActions' => 'Acciones',
	'UI:UserManagement:Permission' => 'Permisos',
	'UI:UserManagement:Permission+' => 'Permisos de usuario',
	'UI:UserManagement:Attributes' => 'Atributos',
	'UI:UserManagement:ActionAllowed:Yes' => 'Si',
	'UI:UserManagement:ActionAllowed:No' => 'No',
	'UI:UserManagement:AdminProfile+' => 'Los administradores tienen acceso total de lectura/escritura para todos los objetos en la base de datos.',
	'UI:UserManagement:NoLifeCycleApplicable' => 'N/A',
	'UI:UserManagement:NoLifeCycleApplicable+' => 'No se ha definido ciclo de vida para esta clase',
	'UI:UserManagement:GrantMatrix' => 'Matriz de Acceso',
	'UI:UserManagement:LinkBetween_User_And_Profile' => 'Vinculo entre %1$s y %2$s',
	'UI:UserManagement:LinkBetween_User_And_Org' => 'Vínculo entre %1$s y %2$s',

	'Menu:AdminTools' => 'Herramientas Administrativas', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AdminTools+' => 'Herramientas Administrativas', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AdminTools?' => 'Herramientas accesibles sólo a usuarios con Perfil de administrador', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:SystemTools' => 'System~~',

	'UI:ChangeManagementMenu' => 'Control de Cambios',
	'UI:ChangeManagementMenu+' => 'Control de Cambios',
	'UI:ChangeManagementMenu:Title' => 'Resumen de Cambios',
	'UI-ChangeManagementMenu-ChangesByType' => 'Cambios por Tipo',
	'UI-ChangeManagementMenu-ChangesByStatus' => 'Cambios por Estatus',
	'UI-ChangeManagementMenu-ChangesByWorkgroup' => 'Cambios por Grupo de Trabajo',
	'UI-ChangeManagementMenu-ChangesNotYetAssigned' => 'Cambios No Asignados Aún',

	'UI:ConfigurationManagementMenu' => 'Administración de la Configuración',
	'UI:ConfigurationManagementMenu+' => 'Administración de la Configuración',
	'UI:ConfigurationManagementMenu:Title' => 'Resumen de Infrastructura',
	'UI-ConfigurationManagementMenu-InfraByType' => 'Objetos de Infraestructura por Tipo',
	'UI-ConfigurationManagementMenu-InfraByStatus' => 'Objetos de Infraestructura por Estatus',

	'UI:ConfigMgmtMenuOverview:Title' => 'Panel de Control para Administración de la Configuración',
	'UI-ConfigMgmtMenuOverview-FunctionalCIbyStatus' => 'Elementos de Configuración por Estatus',
	'UI-ConfigMgmtMenuOverview-FunctionalCIByType' => 'Elementos de Configuración por Tipo',

	'UI:RequestMgmtMenuOverview:Title' => 'Panel de Control para Administración de Requerimientos',
	'UI-RequestManagementOverview-RequestByService' => 'Requerimientos de Usuario por Servicio',
	'UI-RequestManagementOverview-RequestByPriority' => 'Requerimientos de Usuario por Prioridad',
	'UI-RequestManagementOverview-RequestUnassigned' => 'Requerimientos de Usuario Sin Asignar a un Analista',

	'UI:IncidentMgmtMenuOverview:Title' => 'Panel de Control para Administración de Incidentes',
	'UI-IncidentManagementOverview-IncidentByService' => 'Incidentes por Servicio',
	'UI-IncidentManagementOverview-IncidentByPriority' => 'Incidentes por Prioridad',
	'UI-IncidentManagementOverview-IncidentUnassigned' => 'Incidentes No Asignados a un Analista',

	'UI:ChangeMgmtMenuOverview:Title' => 'Panel de Control para Control de Cambios',
	'UI-ChangeManagementOverview-ChangeByType' => 'Cambios por Tipo',
	'UI-ChangeManagementOverview-ChangeUnassigned' => 'Cambios No Asignados a un Analista',
	'UI-ChangeManagementOverview-ChangeWithOutage' => 'Interrupciones de Servicios debida a Cambios',

	'UI:ServiceMgmtMenuOverview:Title' => 'Panel de Control para Administración de Servicios',
	'UI-ServiceManagementOverview-CustomerContractToRenew' => 'Contratos de Clientes a ser Renovados en 30 días',
	'UI-ServiceManagementOverview-ProviderContractToRenew' => 'Contratos de Proveedores a ser Renovados en 30 días',

	'UI:ContactsMenu' => 'Contactos',
	'UI:ContactsMenu+' => 'Contactos',
	'UI:ContactsMenu:Title' => 'Resumen de Contactos',
	'UI-ContactsMenu-ContactsByLocation' => 'Contactos por Localidad',
	'UI-ContactsMenu-ContactsByType' => 'Contactos por Tipo',
	'UI-ContactsMenu-ContactsByStatus' => 'Contactos por Estatus',

	'Menu:CSVImportMenu' => 'Importar CSV', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:CSVImportMenu+' => 'Creación o Actualización Másiva', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:DataModelMenu' => 'Modelo de Datos', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataModelMenu+' => 'Resumen del Modelo de Datos', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:ExportMenu' => 'Exportar', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ExportMenu+' => 'Exportar los Resultados de Cualquier Consulta en HTML, CSV o XML', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:NotificationsMenu' => 'Notificaciones', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:NotificationsMenu+' => 'Configuración de las Notificaciones', // Duplicated into itop-welcome-itil (will be removed from here...)
	'UI:NotificationsMenu:Title' => 'Configuración de las <span class="hilite">Notificaciones</span>',
	'UI:NotificationsMenu:Help' => 'Ayuda',
	'UI:NotificationsMenu:HelpContent' => '<p>En iTop las notificaciones son completamente personalizables. Están basadas en dos conjuntos de objetos: <i>Disparadores y Acciones</i>.</p>
<p>Los <i><b>disparadores</b></i> definen cuando una notificación debe ser ejecutada.  Existen 3 tipos de disparadores para cubrir las 3 diferentes fases del ciclo de vida de un objeto:
<ol>
	<li>Los disparadores "OnCreate" son ejecutados cuando un objeto de la clase especificada es creado</li>
	<li>Los disparadores "OnStateEnter" son ejecutados antes de que un determinado objeto entre un estado especificado (viniendo de otro estado)</li>
	<li>Los disparadores "OnStateLeave" son ejecutados cuando un objeto de clase determinada deja un estado especificado</li>
	<li>Los disparadores "On threshold" son ejecutados cuando un umbral para TDA o TDS es alcanzado</li>
	<li>Los disparadores "On portal update" son ejecutados cuando un ticket es actualizado desde el portal</li>
</ol>
</p>
<p>
<i>Las <b>Acciones</b></i> definen las acciones a ser ejecutadas cuando los disparadores se disparan, por ahora el único tipo de acción consiste en enviar un mensaje de correo.
Tales acciones tambien definen la plantilla a ser usada para enviar el correo asi como otros parametros del mensaje como receptor, importancia, etc.
</p>
<p>Una página especial: <a href="../setup/email.test.php" target="_blank">email.test.php</a> está disponible para probar y diagnosticar su configuración de correo de PHP.</p>
<p>Para ser ejecutadas, las acciones deben estar asociadas con los disparadores.
Cuando se asocien con un disparador, cada acción recibe un número de "orden", esto especifica en que orden se ejecutaran las acciones.</p>',
	'UI:NotificationsMenu:Triggers' => 'Disparadores',
	'UI:NotificationsMenu:AvailableTriggers' => 'Disparadores disponibles',
	'UI:NotificationsMenu:OnCreate' => 'Cuando un objeto es creado',
	'UI:NotificationsMenu:OnStateEnter' => 'Cuando un objeto entra a un estado específico',
	'UI:NotificationsMenu:OnStateLeave' => 'Cuando un objeto sale de un estado específico',
	'UI:NotificationsMenu:Actions' => 'Acciones',
	'UI:NotificationsMenu:AvailableActions' => 'Acciones Disponibles',

	'Menu:TagAdminMenu' => 'Tags configuration~~',
	'Menu:TagAdminMenu+' => 'Tags values management~~',
	'UI:TagAdminMenu:Title' => 'Tags configuration~~',
	'UI:TagAdminMenu:NoTags' => 'No Tag field configured~~',
	'UI:TagSetFieldData:Error' => 'Error: %1$s~~',

	'Menu:AuditCategories' => 'Auditar Categorías', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AuditCategories+' => 'Auditar Categorías', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:Notifications:Title' => 'Auditar Categorías', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:RunQueriesMenu' => 'Ejecutar Consultas', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:RunQueriesMenu+' => 'Ejecutar Cualquier Consulta', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:QueryMenu' => 'Libreta de Consultas', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:QueryMenu+' => 'Libreta de Consultas', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:DataAdministration' => 'Administración de Datos', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataAdministration+' => 'Administración de Datos', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UniversalSearchMenu' => 'Búsqueda Universal', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UniversalSearchMenu+' => 'Buscar cualquier cosa', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UserManagementMenu' => 'Administración de Usuarios', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserManagementMenu+' => 'Administración de Usuarios', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:ProfilesMenu' => 'Perfiles', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ProfilesMenu+' => 'Perfiles', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ProfilesMenu:Title' => 'Perfiles', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UserAccountsMenu' => 'Cuentas de Usuario', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserAccountsMenu+' => 'Cuentas de Usuario', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserAccountsMenu:Title' => 'Cuentas de Usuario', // Duplicated into itop-welcome-itil (will be removed from here...)	

	'UI:iTopVersion:Short' => '%1$s versión %2$s',
	'UI:iTopVersion:Long' => '%1$s versión %2$s-%3$s compilada en %4$s',
	'UI:PropertiesTab' => 'Propiedades',

	'UI:OpenDocumentInNewWindow_' => 'Abra este documento en una ventana nueva: %1$s',
	'UI:DownloadDocument_' => 'Descargue este documento: %1$s',
	'UI:Document:NoPreview' => 'No hay prevista disponible para este tipo de archivo',
	'UI:Download-CSV' => 'Descargar %1$s',

	'UI:DeadlineMissedBy_duration' => 'No se cumplió por %1$s',
	'UI:Deadline_LessThan1Min' => '< 1 min',
	'UI:Deadline_Minutes' => '%1$d min',
	'UI:Deadline_Hours_Minutes' => '%1$dh %2$dmin',
	'UI:Deadline_Days_Hours_Minutes' => '%1$dd %2$dh %3$dmin',
	'UI:Help' => 'Ayuda',
	'UI:PasswordConfirm' => '(Confirmar)',
	'UI:BeforeAdding_Class_ObjectsSaveThisObject' => 'Antes de Agregar un(a) %1$s, Guarde los Cambios Realizados.',
	'UI:DisplayThisMessageAtStartup' => 'Desplegar este Mensaje al Inicio',
	'UI:RelationshipGraph' => 'Vista Gráfica',
	'UI:RelationshipList' => 'Lista',
	'UI:RelationGroups' => 'Grupos',
	'UI:OperationCancelled' => 'Operación Cancelada',
	'UI:ElementsDisplayed' => 'Despliegue',
	'UI:RelationGroupNumber_N' => 'Grupo #%1$d',
	'UI:Relation:ExportAsPDF' => 'Exportar como PDF...',
	'UI:RelationOption:GroupingThreshold' => 'Umbral de Agrupamiento',
	'UI:Relation:AdditionalContextInfo' => 'Información Contextual Adicional',
	'UI:Relation:NoneSelected' => 'Ninguno',
	'UI:Relation:Zoom' => 'Zoom~~',
	'UI:Relation:ExportAsAttachment' => 'Exportar como Anexo...',
	'UI:Relation:DrillDown' => 'Detalles...',
	'UI:Relation:PDFExportOptions' => 'Opciones de exportación PDF',
	'UI:Relation:AttachmentExportOptions_Name' => 'Opciones para anexo a %1$s',
	'UI:RelationOption:Untitled' => 'Sin Título',
	'UI:Relation:Key' => 'Llave',
	'UI:Relation:Comments' => 'Comentarios',
	'UI:RelationOption:Title' => 'Título',
	'UI:RelationOption:IncludeList' => 'Incluír lista de objetos',
	'UI:RelationOption:Comments' => 'Comentarios',
	'UI:Button:Export' => 'Exportar',
	'UI:Relation:PDFExportPageFormat' => 'Formato de Página',
	'UI:PageFormat_A3' => 'A3',
	'UI:PageFormat_A4' => 'A4',
	'UI:PageFormat_Letter' => 'Carta',
	'UI:Relation:PDFExportPageOrientation' => 'Orientación de Página',
	'UI:PageOrientation_Portrait' => 'Vertical',
	'UI:PageOrientation_Landscape' => 'Horizontal',
	'UI:RelationTooltip:Redundancy' => 'Redundancia',
	'UI:RelationTooltip:ImpactedItems_N_of_M' => '# de elementos impactados: %1$d / %2$d',
	'UI:RelationTooltip:CriticalThreshold_N_of_M' => 'Umbral Crítico: %1$d / %2$d',
	'Portal:Title' => 'Portal de Usuario',
	'Portal:NoRequestMgmt' => 'Estimado(a) %1$s, ha sido redirigido a esta página porque su cuenta está configurada con el Perfil \'Portal user\'. Desafortunadamente, iTop no fue instalado con el módulo \'Request Management\'. Por favor contacte a su Administrador.',
	'Portal:Refresh' => 'Actualizar',
	'Portal:Back' => 'Atrás',
	'Portal:WelcomeUserOrg' => 'Bienvenido %1$s, de %2$s',
	'Portal:TitleDetailsFor_Request' => 'Detalles del Requerimiento',
	'Portal:ShowOngoing' => 'Mostrar Requerimientos Abiertos',
	'Portal:ShowClosed' => 'Mostrar Requerimientos Cerrados',
	'Portal:CreateNewRequest' => 'Crear Requerimiento',
	'Portal:CreateNewRequestItil' => 'Crear Requerimiento',
	'Portal:CreateNewIncidentItil' => 'Crear Incidente',
	'Portal:ChangeMyPassword' => 'Cambiar Contraseña',
	'Portal:Disconnect' => 'Cerrar Sesión',
	'Portal:OpenRequests' => 'Mis Requerimientos Abiertos',
	'Portal:ClosedRequests' => 'Mis Requerimientos Cerrados',
	'Portal:ResolvedRequests' => 'Mis Requerimientos Solucionados',
	'Portal:SelectService' => 'Selecciona un Servicio del Catálogo:',
	'Portal:PleaseSelectOneService' => 'Por favor, selecciona un Servicio',
	'Portal:SelectSubcategoryFrom_Service' => 'Selecciona una Subcategoría para el Servicio %1$s:',
	'Portal:PleaseSelectAServiceSubCategory' => 'Por favor selecciona una Subcategoría',
	'Portal:DescriptionOfTheRequest' => 'Captura una Descripción para tu Requerimiento:',
	'Portal:TitleRequestDetailsFor_Request' => 'Detalles del Requerimiento %1$s:',
	'Portal:NoOpenRequest' => 'No hay Requerimientos para esta Categoría',
	'Portal:NoClosedRequest' => 'No hay Requerimientos para esta Categoría',
	'Portal:Button:ReopenTicket' => 'Reabrir este Ticket',
	'Portal:Button:CloseTicket' => 'Cerrar este Ticket',
	'Portal:Button:UpdateRequest' => 'Actualizar el Requerimiento',
	'Portal:EnterYourCommentsOnTicket' => 'Captura tus Comentarios acerca de la Solución de este Ticket:',
	'Portal:ErrorNoContactForThisUser' => 'Error: el Usuario no está asociado con un Contacto/Persona. Por favor contacte al Administrador de iTop',
	'Portal:Attachments' => 'Anexos',
	'Portal:AddAttachment' => 'Agregar Anexo',
	'Portal:RemoveAttachment' => 'Borrar Anexo',
	'Portal:Attachment_No_To_Ticket_Name' => 'Anexo #%1$d to %2$s (%3$s)',
	'Portal:SelectRequestTemplate' => 'Seleccione una Plantilla para %1$s',
	'Enum:Undefined' => 'No Definido',
	'UI:DurationForm_Days_Hours_Minutes_Seconds' => '%1$s Días %2$s Hrs. %3$s Mins. %4$s Segs.',
	'UI:ModifyAllPageTitle' => 'Modificar Todos',
	'UI:Modify_N_ObjectsOf_Class' => 'Modificando %1$d objetos de la clase %2$s',
	'UI:Modify_M_ObjectsOf_Class_OutOf_N' => 'Modificando %1$d objetos de la clase %2$s de un total de %3$d',
	'UI:Menu:ModifyAll' => 'Modificar',
	'UI:Button:ModifyAll' => 'Modificar Todos',
	'UI:Button:PreviewModifications' => 'Previsualizar Modificaciones >>',
	'UI:ModifiedObject' => 'Objecto Modificado',
	'UI:BulkModifyStatus' => 'Operación',
	'UI:BulkModifyStatus+' => 'Estatus de la operación',
	'UI:BulkModifyErrors' => 'Errores (si los hubiera)',
	'UI:BulkModifyErrors+' => 'Errores que evitan la modificación',
	'UI:BulkModifyStatusOk' => 'Ok',
	'UI:BulkModifyStatusError' => 'Error',
	'UI:BulkModifyStatusModified' => 'Modificado',
	'UI:BulkModifyStatusSkipped' => 'Saltado',
	'UI:BulkModify_Count_DistinctValues' => '%1$d diferentes valores:',
	'UI:BulkModify:Value_Exists_N_Times' => '%1$s, %2$d tiempo(s)',
	'UI:BulkModify:N_MoreValues' => '%1$d más valores',
	'UI:AttemptingToSetAReadOnlyAttribute_Name' => 'Intentando configurar campo de solo lectura: %1$s',
	'UI:FailedToApplyStimuli' => 'La acción ha fallado.',
	'UI:StimulusModify_N_ObjectsOf_Class' => '%1$s: Modificando %2$d objetos de la clase %3$s',
	'UI:CaseLogTypeYourTextHere' => 'Escriba su texto aquí:',
	'UI:CaseLog:Header_Date_UserName' => '%1$s - %2$s:',
	'UI:CaseLog:InitialValue' => 'Valor inicial:',
	'UI:AttemptingToSetASlaveAttribute_Name' => 'El campo %1$s no es escribible porque es manejado por el sincronizador de datos. Valor no cambiado.',
	'UI:ActionNotAllowed' => 'No tiene permitodo realizar esta acción sobre estos objetos.',
	'UI:BulkAction:NoObjectSelected' => 'Por favor seleccione al menos un objeto para realizar esta operación',
	'UI:AttemptingToChangeASlaveAttribute_Name' => 'El campo %1$s no es escribible porque es manejado por el sincronizador de datos. Valor se mantiene sin cambios.',
	'UI:Pagination:HeaderSelection' => 'Total: %1$s Elementos (%2$s Elementos Seleccionados).',
	'UI:Pagination:HeaderNoSelection' => 'Total: %1$s Elemento(s)',
	'UI:Pagination:PageSize' => '%1$s Elementos por Página',
	'UI:Pagination:PagesLabel' => 'Páginas:',
	'UI:Pagination:All' => 'Todos',
	'UI:HierarchyOf_Class' => 'Jerarquía de %1$s',
	'UI:Preferences' => 'Preferencias',
	'UI:ArchiveModeOn' => 'Activar modo Archivado',
	'UI:ArchiveModeOff' => 'Deactivar modo Archivado',
	'UI:ArchiveMode:Banner' => 'Modo Archivado',
	'UI:ArchiveMode:Banner+' => 'Objetos archivados son visibles, y ninguna modificación es permitida',
	'UI:FavoriteOrganizations' => 'Mi Organización Favorita',
	'UI:FavoriteOrganizations+' => 'Verifique en la siguiente lista de Organizaciones, la que necesite ver en los menues para un rápido acceso. Nota, esto no es una configuración de seguridad, elementos de cualquier Organización son visibles y pueden ser accesados mediante la selección de "Todas las Organizaciones" en la lista del menú.',
	'UI:FavoriteLanguage' => 'Idioma de la Interfaz de Usuario',
	'UI:Favorites:SelectYourLanguage' => 'Seleccione su Idioma Predeterminado',
	'UI:FavoriteOtherSettings' => 'Otras Configuraciones',
	'UI:Favorites:Default_X_ItemsPerPage' => 'Tamaño Predeterminado de Listas:  %1$s elementos por página',
	'UI:Favorites:ShowObsoleteData' => 'Mostrar datos Obsoletos',
	'UI:Favorites:ShowObsoleteData+' => 'Mostrar datos obsoletos en resultados de búsqueda y listas de elementos seleccionables',
	'UI:NavigateAwayConfirmationMessage' => 'Cualquier modificación será descartada.',
	'UI:CancelConfirmationMessage' => 'Perderá los cambios realizados. ¿Desea Continuar?',
	'UI:AutoApplyConfirmationMessage' => 'Algunos cambios no han sido aplicados todavía. ¿Quiere que iTop los tome en cuenta?',
	'UI:Create_Class_InState' => 'Crear %1$s en el estado: ',
	'UI:OrderByHint_Values' => 'Ordenamiento: %1$s',
	'UI:Menu:AddToDashboard' => 'Agregar a Panel de Control',
	'UI:Button:Refresh' => 'Refrescar',
	'UI:Button:GoPrint' => 'Imprimir...',
	'UI:ExplainPrintable' => 'Click en el icono %1$s para ocultar elementos de la impresión.<br/>Use la funcionalidad "vista preliminar" de su navegador para visualizar antes de imprimir.<br/>Nota: Este encabezado y controles de ajuste no serán impresos.',
	'UI:PrintResolution:FullSize' => 'Full size~~',
	'UI:PrintResolution:A4Portrait' => 'A4 Portrait~~',
	'UI:PrintResolution:A4Landscape' => 'A4 Landscape~~',
	'UI:PrintResolution:LetterPortrait' => 'Letter Portrait~~',
	'UI:PrintResolution:LetterLandscape' => 'Letter Landscape~~',
	'UI:Toggle:StandardDashboard' => 'Standard~~',
	'UI:Toggle:CustomDashboard' => 'Custom~~',

	'UI:ConfigureThisList' => 'Configurar Lista',
	'UI:ListConfigurationTitle' => 'Configuración de Lista',
	'UI:ColumnsAndSortOrder' => 'Columnas y Ordenamiento:',
	'UI:UseDefaultSettings' => 'Usar Configuración por Omisión',
	'UI:UseSpecificSettings' => 'Usar la Siguiente Configuración:',
	'UI:Display_X_ItemsPerPage' => 'Desplegar %1$s elementos por página',
	'UI:UseSavetheSettings' => 'Guardar Configuraciones',
	'UI:OnlyForThisList' => 'Sólo esta Lista',
	'UI:ForAllLists' => 'Defecto en todas las listas',
	'UI:ExtKey_AsLink' => '%1$s (Liga)',
	'UI:ExtKey_AsFriendlyName' => '%1$s (Nombre Común)',
	'UI:ExtField_AsRemoteField' => '%1$s (%2$s)',
	'UI:Button:MoveUp' => 'Mover Arriba',
	'UI:Button:MoveDown' => 'Mover Abajo',

	'UI:OQL:UnknownClassAndFix' => 'Clase Desconocida "%1$s". Puede usar "%2$s" en su lugar.',
	'UI:OQL:UnknownClassNoFix' => 'Clase Desconocida "%1$s"',

	'UI:Dashboard:Edit' => 'Editar esta Página',
	'UI:Dashboard:Revert' => 'Regresar a Versión Original',
	'UI:Dashboard:RevertConfirm' => 'Todos los cambios realizados a la versión original se perderán.  Por favor confime que quiere hacer esto.',
	'UI:ExportDashBoard' => 'Exportar a un Archivo',
	'UI:ImportDashBoard' => 'Importar de un Archivo',
	'UI:ImportDashboardTitle' => 'Importar de un Archivo',
	'UI:ImportDashboardText' => 'Seleccione el Archivo de Panel de Control a Importar:',


	'UI:DashletCreation:Title' => 'Crear Dashlet',
	'UI:DashletCreation:Dashboard' => 'Panel de Control',
	'UI:DashletCreation:DashletType' => 'Tipo de Dashlet',
	'UI:DashletCreation:EditNow' => 'Editar el Panel de Control',

	'UI:DashboardEdit:Title' => 'Editor de Panel del Control',
	'UI:DashboardEdit:DashboardTitle' => 'Título',
	'UI:DashboardEdit:AutoReload' => 'Actualización Automática',
	'UI:DashboardEdit:AutoReloadSec' => 'Interválo de Actualización Automática (segundos)',
	'UI:DashboardEdit:AutoReloadSec+' => 'El interválo mínimo es de %1$d segundos',

	'UI:DashboardEdit:Layout' => 'Distribución',
	'UI:DashboardEdit:Properties' => 'Propiedades',
	'UI:DashboardEdit:Dashlets' => 'Dashlets disponibles',
	'UI:DashboardEdit:DashletProperties' => 'Propiedades de Dashlet',

	'UI:Form:Property' => 'Propiedad',
	'UI:Form:Value' => 'Valor',

	'UI:DashletUnknown:Label' => 'Desconocido',
	'UI:DashletUnknown:Description' => 'Dashlet desconocido (puede haber sido desinstalado)',
	'UI:DashletUnknown:RenderText:View' => 'No es posible desplegar este dashlet.',
	'UI:DashletUnknown:RenderText:Edit' => 'No es posible desplegar este dashlet (clase "%1$s"). Verifique con su administrador si está todavia disponible.',
	'UI:DashletUnknown:RenderNoDataText:Edit' => 'No hay vista previa para este dashlet (clase "%1$s").',
	'UI:DashletUnknown:Prop-XMLConfiguration' => 'Configuración (mostrado como código XML)',

	'UI:DashletProxy:Label' => 'Proxy',
	'UI:DashletProxy:Description' => 'Proxy dashlet',
	'UI:DashletProxy:RenderNoDataText:Edit' => 'No preview available for this third-party dashlet (class "%1$s").~~',
	'UI:DashletProxy:Prop-XMLConfiguration' => 'Configuration (shown as raw XML)',

	'UI:DashletPlainText:Label' => 'Texto',
	'UI:DashletPlainText:Description' => 'Texto Plano (sin formato)',
	'UI:DashletPlainText:Prop-Text' => 'Texto',
	'UI:DashletPlainText:Prop-Text:Default' => 'Escriba texto aquí...',

	'UI:DashletObjectList:Label' => 'Lista de Objetos',
	'UI:DashletObjectList:Description' => 'Lista de Objetos en dashlet',
	'UI:DashletObjectList:Prop-Title' => 'Título',
	'UI:DashletObjectList:Prop-Query' => 'Consulta',
	'UI:DashletObjectList:Prop-Menu' => 'Menú',

	'UI:DashletGroupBy:Prop-Title' => 'Título',
	'UI:DashletGroupBy:Prop-Query' => 'Consulta',
	'UI:DashletGroupBy:Prop-Style' => 'Estilo',
	'UI:DashletGroupBy:Prop-GroupBy' => 'Agrupar por',
	'UI:DashletGroupBy:Prop-GroupBy:Hour' => 'Horas de %1$s (0-23)',
	'UI:DashletGroupBy:Prop-GroupBy:Month' => 'Mes de  %1$s (1 - 12)',
	'UI:DashletGroupBy:Prop-GroupBy:DayOfWeek' => 'Día de la semana por %1$s',
	'UI:DashletGroupBy:Prop-GroupBy:DayOfMonth' => 'Día del mes por %1$s',
	'UI:DashletGroupBy:Prop-GroupBy:Select-Hour' => '%1$s (hora)',
	'UI:DashletGroupBy:Prop-GroupBy:Select-Month' => '%1$s (mes)',
	'UI:DashletGroupBy:Prop-GroupBy:Select-DayOfWeek' => '%1$s (día de la semana)',
	'UI:DashletGroupBy:Prop-GroupBy:Select-DayOfMonth' => '%1$s (día del mes)',
	'UI:DashletGroupBy:MissingGroupBy' => 'Por favor seleccione los campos por los cuales los objetos serán agrupados',

	'UI:DashletGroupByPie:Label' => 'Gráfica de Pastel',
	'UI:DashletGroupByPie:Description' => 'Gráfica de Pastel',
	'UI:DashletGroupByBars:Label' => 'Gráfica de Barras',
	'UI:DashletGroupByBars:Description' => 'Gráfica de Barras',
	'UI:DashletGroupByTable:Label' => 'Agrupado por (tabla)',
	'UI:DashletGroupByTable:Description' => 'Lista (Campos de agrupación)',

	// New in 2.5
	'UI:DashletGroupBy:Prop-Function' => 'Función de Agrupación',
	'UI:DashletGroupBy:Prop-FunctionAttribute' => 'Atributo de Función',
	'UI:DashletGroupBy:Prop-OrderDirection' => 'Dirección',
	'UI:DashletGroupBy:Prop-OrderField' => 'Ordenar por',
	'UI:DashletGroupBy:Prop-Limit' => 'Límite',

	'UI:DashletGroupBy:Order:asc' => 'Ascendente',
	'UI:DashletGroupBy:Order:desc' => 'Descendente',

	'UI:GroupBy:count' => 'Cuenta',
	'UI:GroupBy:count+' => 'Número de elementos',
	'UI:GroupBy:sum' => 'Suma',
	'UI:GroupBy:sum+' => 'Suma de %1$s',
	'UI:GroupBy:avg' => 'Promedio',
	'UI:GroupBy:avg+' => 'Promedio de %1$s',
	'UI:GroupBy:min' => 'Mínimo',
	'UI:GroupBy:min+' => 'Mínimo de %1$s',
	'UI:GroupBy:max' => 'Máximo',
	'UI:GroupBy:max+' => 'Máximo de %1$s',
	// ---

	'UI:DashletHeaderStatic:Label' => 'Encabezado',
	'UI:DashletHeaderStatic:Description' => 'Desplegar un separador horizontal',
	'UI:DashletHeaderStatic:Prop-Title' => 'Título',
	'UI:DashletHeaderStatic:Prop-Title:Default' => 'Contactos',
	'UI:DashletHeaderStatic:Prop-Icon' => 'Icono',

	'UI:DashletHeaderDynamic:Label' => 'Encabezado con Estadísticas',
	'UI:DashletHeaderDynamic:Description' => 'Encabezado con estadísticas (agrupado por)',
	'UI:DashletHeaderDynamic:Prop-Title' => 'Título',
	'UI:DashletHeaderDynamic:Prop-Title:Default' => 'Contactos',
	'UI:DashletHeaderDynamic:Prop-Icon' => 'Icon',
	'UI:DashletHeaderDynamic:Prop-Subtitle' => 'Subtítulo',
	'UI:DashletHeaderDynamic:Prop-Subtitle:Default' => 'Contactos',
	'UI:DashletHeaderDynamic:Prop-Query' => 'Query',
	'UI:DashletHeaderDynamic:Prop-GroupBy' => 'Agrupar por',
	'UI:DashletHeaderDynamic:Prop-Values' => 'Valores',

	'UI:DashletBadge:Label' => 'Etiqueta',
	'UI:DashletBadge:Description' => 'Icono con nuevo/buscar',
	'UI:DashletBadge:Prop-Class' => 'Clase',

	'DayOfWeek-Sunday' => 'Domingo',
	'DayOfWeek-Monday' => 'Lunes',
	'DayOfWeek-Tuesday' => 'Martes',
	'DayOfWeek-Wednesday' => 'Miércoles',
	'DayOfWeek-Thursday' => 'Jueves',
	'DayOfWeek-Friday' => 'Viernes',
	'DayOfWeek-Saturday' => 'Sábado',
	'Month-01' => 'Enero',
	'Month-02' => 'Febrero',
	'Month-03' => 'Marzo',
	'Month-04' => 'Abril',
	'Month-05' => 'Mayo',
	'Month-06' => 'Junio',
	'Month-07' => 'Julio',
	'Month-08' => 'Agosto',
	'Month-09' => 'Septiembre',
	'Month-10' => 'Octubre',
	'Month-11' => 'Noviembre',
	'Month-12' => 'Diciembre',

	// Short version for the DatePicker
	'DayOfWeek-Sunday-Min' => 'Do',
	'DayOfWeek-Monday-Min' => 'Lu',
	'DayOfWeek-Tuesday-Min' => 'Ma',
	'DayOfWeek-Wednesday-Min' => 'Mi',
	'DayOfWeek-Thursday-Min' => 'Ju',
	'DayOfWeek-Friday-Min' => 'Vi',
	'DayOfWeek-Saturday-Min' => 'Sa',
	'Month-01-Short' => 'Ene',
	'Month-02-Short' => 'Feb',
	'Month-03-Short' => 'Mar',
	'Month-04-Short' => 'Abr',
	'Month-05-Short' => 'May',
	'Month-06-Short' => 'Jun',
	'Month-07-Short' => 'Jul',
	'Month-08-Short' => 'Ago',
	'Month-09-Short' => 'Sep',
	'Month-10-Short' => 'Oct',
	'Month-11-Short' => 'Nov',
	'Month-12-Short' => 'Dic',
	'Calendar-FirstDayOfWeek' => '0', // 0 = Sunday, 1 = Monday, etc...

	'UI:Menu:ShortcutList' => 'Crear Acceso Rápido',
	'UI:ShortcutRenameDlg:Title' => 'Renombrar Acceso Rápido',
	'UI:ShortcutListDlg:Title' => 'Crear Acceso Rápido para la Lista',
	'UI:ShortcutDelete:Confirm' => 'Por favor conforme que desea Eliminar el/los Acceso(s) Rápido(s)',
	'Menu:MyShortcuts' => 'Mis Accesos Rápidos', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Class:Shortcut' => 'Acceso Rápido',
	'Class:Shortcut+' => 'Acceso Rápido',
	'Class:Shortcut/Attribute:name' => 'Nombre',
	'Class:Shortcut/Attribute:name+' => 'Etiqueta usada en el Menú y Título de Página',
	'Class:ShortcutOQL' => 'Resultado de Búsqueda de Acceso Rápido',
	'Class:ShortcutOQL+' => 'Resultado de Búsqueda de Acceso Rápido',
	'Class:ShortcutOQL/Attribute:oql' => 'Consulta',
	'Class:ShortcutOQL/Attribute:oql+' => 'OQL definiendo la lista de objetos a buscar',
	'Class:ShortcutOQL/Attribute:auto_reload' => 'Actualización Automática',
	'Class:ShortcutOQL/Attribute:auto_reload/Value:none' => 'Deshabilitado',
	'Class:ShortcutOQL/Attribute:auto_reload/Value:custom' => 'Frecuencia configurable',
	'Class:ShortcutOQL/Attribute:auto_reload_sec' => 'Interválo de Actualización Automática (segundos)',
	'Class:ShortcutOQL/Attribute:auto_reload_sec/tip' => 'El interválo mínimo es de %1$d segundos',

	'UI:FillAllMandatoryFields' => 'Por favor llenar los campos obligatorios.',
	'UI:ValueMustBeSet' => 'Por favor, ingrese un valor',
	'UI:ValueMustBeChanged' => 'Por favor cambie el valor',
	'UI:ValueInvalidFormat' => 'Formato inválido',

	'UI:CSVImportConfirmTitle' => 'Por favor confirme la operación',
	'UI:CSVImportConfirmMessage' => '¿Está seguro?',
	'UI:CSVImportError_items' => 'Errores: %1$d',
	'UI:CSVImportCreated_items' => 'Creados: %1$d',
	'UI:CSVImportModified_items' => 'Modificados: %1$d',
	'UI:CSVImportUnchanged_items' => 'Sin cambios: %1$d',
	'UI:CSVImport:DateAndTimeFormats' => 'Date and time format~~',
	'UI:CSVImport:DefaultDateTimeFormat_Format_Example' => 'Default format: %1$s (e.g. %2$s)~~',
	'UI:CSVImport:CustomDateTimeFormat' => 'Custom format: %1$s~~',
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
</table>~~',

	'UI:Button:Remove' => 'Remover',
	'UI:AddAnExisting_Class' => 'Agregar objetos del tipo %1$s...',
	'UI:SelectionOf_Class' => 'Selección de objetos del tipo %1$s',

	'UI:AboutBox' => 'Acerca de iTop...',
	'UI:About:Title' => 'Acerca de iTop',
	'UI:About:DataModel' => 'Modelo de Datos',
	'UI:About:Support' => 'Información de Soporte',
	'UI:About:Licenses' => 'Licencias',
	'UI:About:InstallationOptions' => 'Opciones de Instalación',
	'UI:About:ManualExtensionSource' => 'Extension~~',
	'UI:About:Extension_Version' => 'Version: %1$s~~',
	'UI:About:RemoteExtensionSource' => 'Data~~',

	'UI:DisconnectedDlgMessage' => 'Está desconectado. Debe identificarse para continuar usando la aplicación.',
	'UI:DisconnectedDlgTitle' => 'Advertencia',
	'UI:LoginAgain' => 'Conectarse nuevamente',
	'UI:StayOnThePage' => 'Mantenerse en esta página',

	'ExcelExporter:ExportMenu' => 'Exportar a Excel...',
	'ExcelExporter:ExportDialogTitle' => 'Exportar a Excel',
	'ExcelExporter:ExportButton' => 'Exportar',
	'ExcelExporter:DownloadButton' => 'Descargar %1$s',
	'ExcelExporter:RetrievingData' => 'Recuperando datos...',
	'ExcelExporter:BuildingExcelFile' => 'Construyendo el archivo de Excel...',
	'ExcelExporter:Done' => 'Hecho.',
	'ExcelExport:AutoDownload' => 'Iniciar la descarga automáticamente cuando la exportación esté lista',
	'ExcelExport:PreparingExport' => 'Preparando la exportación...',
	'ExcelExport:Statistics' => 'Estadísticas',
	'portal:legacy_portal' => 'Portal de Clientes',
	'portal:backoffice' => 'Portal de Soporte',

	'UI:CurrentObjectIsLockedBy_User' => 'El objeto está bloqueado debido a que está siendo modificado por %1$s.',
	'UI:CurrentObjectIsLockedBy_User_Explanation' => 'El objeto está siendo modificado por %1$s. Sus modificaciones no pueden ser guardadas debeido a que serán re-escritas.',
	'UI:CurrentObjectLockExpired' => 'El bloqueo que prevenia modificaciones concurrentes ha expirado',
	'UI:CurrentObjectLockExpired_Explanation' => 'TEl bloqueo que prevenia modificaciones concurrentes ha expirado. Sus modificaiones no pueden ser guardadas debido a que otros usuario tiene el permiso para modificar este objeto.',
	'UI:ConcurrentLockKilled' => 'El bloqueo que prevenia modificaciones concurrentes ha sido eliminado.',
	'UI:Menu:KillConcurrentLock' => 'Detener el bloque por modificaciones concurrentes!',

	'UI:Menu:ExportPDF' => 'Exportar como PDF...',
	'UI:Menu:PrintableVersion' => 'Versión imprimible',

	'UI:BrowseInlineImages' => 'Ver imágenes...',
	'UI:UploadInlineImageLegend' => 'Subir nueva imágen',
	'UI:SelectInlineImageToUpload' => 'Seleccione la imágen a subir',
	'UI:AvailableInlineImagesLegend' => 'Imágenes disponibles',
	'UI:NoInlineImage' => 'No hay imágenes disponibles en el servidor. Use el botón "Seleccionar archivo" para seleccionar una imágen de su equipo local y subirla al servidor.',

	'UI:ToggleFullScreen' => 'Cambiar Maximizar / Minimizar',
	'UI:Button:ResetImage' => 'Recuperar imágen previa',
	'UI:Button:RemoveImage' => 'Remover imágen',
	'UI:UploadNotSupportedInThisMode' => 'La modificación de imágenes o archivos no está soportado en este modo.',

	'UI:Button:RemoveDocument' => 'Remove the document~~',

	// Search form
	'UI:Search:Toggle' => 'Minimizar/ Expandir',
	'UI:Search:AutoSubmit:DisabledHint' => 'Auto enviar ha sido deshabilitado para esta clase',
	'UI:Search:Obsolescence:DisabledHint' => '<span class="fas fa-eye-slash fa-1x"></span> Based on your preferences, obsolete data are hidden~~',
	'UI:Search:NoAutoSubmit:ExplainText' => 'Add some criterion on the search box or click the search button to view the objects.~~',
	'UI:Search:Criterion:MoreMenu:AddCriteria' => 'Agregar nuevo criterio',
	// - Add new criteria button
	'UI:Search:AddCriteria:List:RecentlyUsed:Title' => 'Recientemente usado',
	'UI:Search:AddCriteria:List:MostPopular:Title' => 'Más popular',
	'UI:Search:AddCriteria:List:Others:Title' => 'Otros',
	'UI:Search:AddCriteria:List:RecentlyUsed:Placeholder' => 'Ninguno todavía',

	// - Criteria titles
	//   - Default widget
	'UI:Search:Criteria:Title:Default:Any' => '%1$s: Cualquier',
	'UI:Search:Criteria:Title:Default:Empty' => '%1$s está vacío',
	'UI:Search:Criteria:Title:Default:NotEmpty' => '%1$s no está vacío',
	'UI:Search:Criteria:Title:Default:Equals' => '%1$s igual a %2$s',
	'UI:Search:Criteria:Title:Default:Contains' => '%1$s contiene %2$s',
	'UI:Search:Criteria:Title:Default:StartsWith' => '%1$s comienza con %2$s',
	'UI:Search:Criteria:Title:Default:EndsWith' => '%1$s termina con %2$s',
	'UI:Search:Criteria:Title:Default:RegExp' => '%1$s coincide con %2$s',
	'UI:Search:Criteria:Title:Default:GreaterThan' => '%1$s > %2$s',
	'UI:Search:Criteria:Title:Default:GreaterThanOrEquals' => '%1$s >= %2$s',
	'UI:Search:Criteria:Title:Default:LessThan' => '%1$s < %2$s',
	'UI:Search:Criteria:Title:Default:LessThanOrEquals' => '%1$s <= %2$s',
	'UI:Search:Criteria:Title:Default:Different' => '%1$s ≠ %2$s',
	'UI:Search:Criteria:Title:Default:Between' => '%1$s entre [%2$s]',
	'UI:Search:Criteria:Title:Default:BetweenDates' => '%1$s [%2$s]',
	'UI:Search:Criteria:Title:Default:BetweenDates:All' => '%1$s: Cualquier',
	'UI:Search:Criteria:Title:Default:BetweenDates:From' => '%1$s desde %2$s',
	'UI:Search:Criteria:Title:Default:BetweenDates:Until' => '%1$s hasta %2$s',
	'UI:Search:Criteria:Title:Default:Between:All' => '%1$s: Cualquier',
	'UI:Search:Criteria:Title:Default:Between:From' => '%1$s desde %2$s',
	'UI:Search:Criteria:Title:Default:Between:Until' => '%1$s hasta %2$s',
	//   - Numeric widget
	//   None yet
	//   - DateTime widget
	'UI:Search:Criteria:Title:DateTime:Between' => '%2$s <= 1$s <= %3$s',
	//   - Enum widget
	'UI:Search:Criteria:Title:Enum:In' => '%1$s: %2$s',
	'UI:Search:Criteria:Title:Enum:In:Many' => '%1$s: %2$s y %3$s otros',
	'UI:Search:Criteria:Title:Enum:In:All' => '%1$s: Cualquier',
	//   - TagSet widget
	'UI:Search:Criteria:Title:TagSet:Matches' => '%1$s: %2$s',
	//   - External key widget
	'UI:Search:Criteria:Title:ExternalKey:Empty' => '%1$s está definido',
	'UI:Search:Criteria:Title:ExternalKey:NotEmpty' => '%1$s no está definido',
	'UI:Search:Criteria:Title:ExternalKey:Equals' => '%1$s %2$s',
	'UI:Search:Criteria:Title:ExternalKey:In' => '%1$s: %2$s',
	'UI:Search:Criteria:Title:ExternalKey:In:Many' => '%1$s: %2$s y %3$s otros',
	'UI:Search:Criteria:Title:ExternalKey:In:All' => '%1$s: Cualquier',
	//   - Hierarchical key widget
	'UI:Search:Criteria:Title:HierarchicalKey:Empty' => '%1$s está definido',
	'UI:Search:Criteria:Title:HierarchicalKey:NotEmpty' => '%1$s no está definido',
	'UI:Search:Criteria:Title:HierarchicalKey:Equals' => '%1$s %2$s',
	'UI:Search:Criteria:Title:HierarchicalKey:In' => '%1$s: %2$s',
	'UI:Search:Criteria:Title:HierarchicalKey:In:Many' => '%1$s: %2$s y %3$s otros',
	'UI:Search:Criteria:Title:HierarchicalKey:In:All' => '%1$s: Cualquier',

	// - Criteria operators
	//   - Default widget
	'UI:Search:Criteria:Operator:Default:Empty' => 'Está vacío',
	'UI:Search:Criteria:Operator:Default:NotEmpty' => 'No está vacío',
	'UI:Search:Criteria:Operator:Default:Equals' => 'Igual',
	'UI:Search:Criteria:Operator:Default:Between' => 'Entre',
	//   - String widget
	'UI:Search:Criteria:Operator:String:Contains' => 'Contiene',
	'UI:Search:Criteria:Operator:String:StartsWith' => 'Comienza con',
	'UI:Search:Criteria:Operator:String:EndsWith' => 'Termina con',
	'UI:Search:Criteria:Operator:String:RegExp' => 'Exp. Regular',
	//   - Numeric widget
	'UI:Search:Criteria:Operator:Numeric:Equals' => 'Igual',  // => '=',
	'UI:Search:Criteria:Operator:Numeric:GreaterThan' => 'Mayor',  // => '>',
	'UI:Search:Criteria:Operator:Numeric:GreaterThanOrEquals' => 'Mayor / igual',  // > '>=',
	'UI:Search:Criteria:Operator:Numeric:LessThan' => 'Menor',  // => '<',
	'UI:Search:Criteria:Operator:Numeric:LessThanOrEquals' => 'Menor / igual',  // > '<=',
	'UI:Search:Criteria:Operator:Numeric:Different' => 'Diferente',  // => '≠',
	//   - Tag Set Widget
	'UI:Search:Criteria:Operator:TagSet:Matches' => 'Matches~~',

	// - Other translations
	'UI:Search:Value:Filter:Placeholder' => 'Filtro...',
	'UI:Search:Value:Search:Placeholder' => 'Búsqueda...',
	'UI:Search:Value:Autocomplete:StartTyping' => 'Inicie escribiento posibles valores.',
	'UI:Search:Value:Autocomplete:Wait' => 'Por favor espere...',
	'UI:Search:Value:Autocomplete:NoResult' => 'Sin Resultados.',
	'UI:Search:Value:Toggler:CheckAllNone' => 'Marcar todos / ninguno',
	'UI:Search:Value:Toggler:CheckAllNoneFiltered' => 'Marcar todos / ninguno visible',

	// - Widget other translations
	'UI:Search:Criteria:Numeric:From' => 'De',
	'UI:Search:Criteria:Numeric:Until' => 'Para',
	'UI:Search:Criteria:Numeric:PlaceholderFrom' => 'Cualquier',
	'UI:Search:Criteria:Numeric:PlaceholderUntil' => 'Cualquier',
	'UI:Search:Criteria:DateTime:From' => 'De',
	'UI:Search:Criteria:DateTime:FromTime' => 'De',
	'UI:Search:Criteria:DateTime:Until' => 'hasta',
	'UI:Search:Criteria:DateTime:UntilTime' => 'hasta',
	'UI:Search:Criteria:DateTime:PlaceholderFrom' => 'Cualquier fecha',
	'UI:Search:Criteria:DateTime:PlaceholderFromTime' => 'Cualquier fecha',
	'UI:Search:Criteria:DateTime:PlaceholderUntil' => 'Cualquier fecha',
	'UI:Search:Criteria:DateTime:PlaceholderUntilTime' => 'Cualquier fecha',
	'UI:Search:Criteria:HierarchicalKey:ChildrenIncluded:Hint' => 'Hijos de los objetos seleccionados serán incluídos.',

	'UI:Search:Criteria:Raw:Filtered' => 'Filtrado',
	'UI:Search:Criteria:Raw:FilteredOn' => 'Filtrado en %1$s',
));

//
// Expression to Natural language
//
Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
	'Expression:Operator:AND' => ' Y ',
	'Expression:Operator:OR' => ' O ',
	'Expression:Operator:=' => ': ',

	'Expression:Unit:Short:DAY' => 'd',
	'Expression:Unit:Short:WEEK' => 's',
	'Expression:Unit:Short:MONTH' => 'm',
	'Expression:Unit:Short:YEAR' => 'a',

	'Expression:Unit:Long:DAY' => 'día(s)',
	'Expression:Unit:Long:HOUR' => 'hora(s)',
	'Expression:Unit:Long:MINUTE' => 'minuto(s)',

	'Expression:Verb:NOW' => 'Ahora',
	'Expression:Verb:ISNULL' => ': undefined~~',
));

//
// iTop Newsroom menu
//
Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
	'UI:Newsroom:NoNewMessage' => 'No new message~~',
	'UI:Newsroom:MarkAllAsRead' => 'Mark all messages as read~~',
	'UI:Newsroom:ViewAllMessages' => 'View all messages~~',
	'UI:Newsroom:Preferences' => 'Newsroom preferences~~',
	'UI:Newsroom:ConfigurationLink' => 'Configuration~~',
	'UI:Newsroom:ResetCache' => 'Reset cache~~',
	'UI:Newsroom:DisplayMessagesFor_Provider' => 'Display messages from %1$s~~',
	'UI:Newsroom:DisplayAtMost_X_Messages' => 'Display up to %1$s messages in the %2$s menu.~~',
));
