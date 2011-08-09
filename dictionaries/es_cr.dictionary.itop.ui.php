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
	'Menu:WelcomeMenu' => 'Bienvenido',
	'Menu:WelcomeMenu+' => 'Bienvenido a iTop',
	'Menu:WelcomeMenuPage' => 'Bienvenido',
	'Menu:WelcomeMenuPage+' => 'Bienvenido a iTop',
	'UI:WelcomeMenu:Title' => 'Bienvenido a iTop',

	'UI:WelcomeMenu:LeftBlock' => '<p>iTop es un completo; portal  IT funcional basado en código abierto (OpenSource).</p>
<ul>Incluye:
<li>Una CMDB competa (Configuration management database) para documentar y manejar el inverntario de TI..</li>
<li>Un módul de gestión de incidentes, para llevar la trazabilidad y comunicar los eventos que estan afectando IT.</li>
<li>Un módulo de gestion de cambio para planear y llevar la trazabilidad de cambios hechos al ambiente de TI.</li>
<li>Una base de conocimiento para acelerar la correción de incidentes.</li>
<li>Un moódulo de Cortes/Caídas para documentar todas las caídas planeadas o no y notificar a los contactods del caso.</li>
<li>Tableros de controles para rapidamente tener visión general del ambiente TI..</li>
</ul>
<p>Todos los modulos pueden ser configurados, paso a paso, individual e independientemente de los otros.</p>',

	'UI:WelcomeMenu:RightBlock' => '<p>iTop esta orientada a los proveedores de servicios, le permite a los Ingenieros de TI administrar facilmente multiples clientes y organizaciones.
<ul>iTop, provee un conjunto de funciones de procesos de negocio que:
<li>Mejora Enhances IT management effectiveness</li> 
<li>Dirige el desempeño de la operaciones de TI</li> 
<li>Incrementa la satisfaccion del cliente y provee a los ejecutivos con detalles del desempeño del negocio.</li>
</ul>
</p>
<p>iTop es completamente abierto para ser integrado con su actual infraestructura de Gestion de TI.</p>
<p>
<ul>Adoptar esta nueva generacion de portales de operaciones de TI le ayudara a:
<li>Mejorar gestion de entornos de TI mas y mas complejos.</li>
<li>Implementar los procesos de ITIL a su propio ritmo.</li>
<li>Administrar el bien mas importante de su TI: Documentacion.</li>
</ul>
</p>',

	'UI:WelcomeMenu:MyCalls' => 'Mis solicitudes',
	'UI:WelcomeMenu:MyIncidents' => 'Incidentes asignados a mi',
	'UI:AllOrganizations' => ' Todas las Organizaciones',
	'UI:YourSearch' => 'Su busqueda',
	'UI:LoggedAsMessage' => 'Conectado como %1$s',
	'UI:LoggedAsMessage+Admin' => 'Conectado como %1$s (Administrator)',
	'UI:Button:Logoff' => 'Cerrar sesion',
	'UI:Button:GlobalSearch' => 'Buscar',
	'UI:Button:Search' => ' Buscar ',
	'UI:Button:Query' => ' Consulta ',
	'UI:Button:Ok' => 'Ok',
	'UI:Button:Cancel' => 'Cancelar',
	'UI:Button:Apply' => 'Aplicar',
	'UI:Button:Back' => ' << Anterior ',
	'UI:Button:Next' => ' Siguiente >> ',
	'UI:Button:Finish' => ' Finalizar ',
	'UI:Button:DoImport' => ' Importar los datos ! ',
	'UI:Button:Done' => ' Listo ',
	'UI:Button:SimulateImport' => ' Simular la Importacion ',
	'UI:Button:Test' => 'Probar!',
	'UI:Button:Evaluate' => ' Evaluar ',
	'UI:Button:AddObject' => ' Agregar... ',
	'UI:Button:BrowseObjects' => ' Examinar... ',
	'UI:Button:Add' => ' agregar ',
	'UI:Button:AddToList' => ' << Agregar ',
	'UI:Button:RemoveFromList' => ' Remover >> ',
	'UI:Button:FilterList' => ' Filtrar... ',
	'UI:Button:Create' => ' Crear ',
	'UI:Button:Delete' => ' Borrar! ',
	'UI:Button:ChangePassword' => ' Cambiar Contraseña',
	'UI:Button:ResetPassword' => ' Restablecer Contraseña',

	'UI:SearchToggle' => 'Buscar',
	'UI:ClickToCreateNew' => 'Crear un nuevo %1$s',
	'UI:SearchFor_Class' => 'Buscar %1$s objetos',
	'UI:NoObjectToDisplay' => 'Ningún objeto para visualizar.',
	'UI:Error:MandatoryTemplateParameter_object_id' => 'El parametro object_id es obligatorio cuando link_attr es especificado. Verifique la definicion de la plantilla de visualizacion.',
	'UI:Error:MandatoryTemplateParameter_target_attr' => 'El parametro target_attr es obligatorio cuando link_attr es especificado. Verifique la definicion de la plantilla de visualizacion.',
	'UI:Error:MandatoryTemplateParameter_group_by' => 'El parametro group_by es obligatorio. Verifique la definicion de la plantilla de visualizacion.',
	'UI:Error:InvalidGroupByFields' => 'La lista de campos para agrupar por: "%1$s" es invalida.',
	'UI:Error:UnsupportedStyleOfBlock' => 'Error: Estilo de bloque no soportado: "%1$s".',
	'UI:Error:IncorrectLinkDefinition_LinkedClass_Class' => 'Definicio de vinculo incorrecto: la clase de objeto a administrar : %1$s no fue encontrada como clave externa en la clase %2$s',
	'UI:Error:Object_Class_Id_NotFound' => 'No se encontro el objeto: %1$s:%2$d.',
	'UI:Error:WizardCircularReferenceInDependencies' => 'Error: Verifique el modelo de datos, Existen referencias cisculares  en la dependencias entre los campos.',
	'UI:Error:UploadedFileTooBig' => 'archivo cargado es muy grande. (tamaño maximo permitido es de %1$s. Verifique su configuracion de PHP para upload_max_filesize.',
	'UI:Error:UploadedFileTruncated.' => 'El archivo cargado ha sido truncado!',
	'UI:Error:NoTmpDir' => 'El directorio temporal no ha sido definido.',
	'UI:Error:CannotWriteToTmp_Dir' => 'No fue posible escribir el archivo temporal al disco. upload_tmp_dir = "%1$s".',
	'UI:Error:UploadStoppedByExtension_FileName' => 'Carga de archivo interrumpida por la extension. (Nombre de archivo original = "%1$s").',
	'UI:Error:UploadFailedUnknownCause_Code' => 'Carga de archivo fallida, causa desconocida. (Codigo de error = "%1$s").',
	
	'UI:Error:1ParametersMissing' => 'Error: El siguiente parametro debe ser especificado para esta operacion: %1$s.',
	'UI:Error:2ParametersMissing' => 'Error: Los siguientes parametros deben ser especificados para esta operacion: %1$s and %2$s.',
	'UI:Error:3ParametersMissing' => 'Error: Los siguientes parametros deben ser especificados para esta operacion: %1$s, %2$s and %3$s.',
	'UI:Error:4ParametersMissing' => 'Error: Los siguientes parametros deben ser especificados para esta operacion: %1$s, %2$s, %3$s and %4$s.',
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
	
	
	'UI:GroupBy:Count' => 'Cuenta',
	'UI:GroupBy:Count+' => 'Numero de elementoso',
	'UI:CountOfObjects' => '%1$d objetos cumplen criterio.',
	'UI_CountOfObjectsShort' => '%1$d objetos.',
	'UI:NoObject_Class_ToDisplay' => 'No hay %1$s para mostrar',
	'UI:History:LastModified_On_By' => 'Ultima modificacion el %1$s por %2$s.',
	'UI:HistoryTab' => 'Historia',
	'UI:History:Date' => 'Fecha',
	'UI:History:Date+' => 'Fecha del Cambio',
	'UI:History:User' => 'Usuario',
	'UI:History:User+' => 'Usuario que hizo el cambio',
	'UI:History:Changes' => 'Cambios',
	'UI:History:Changes+' => 'Chambios hechos al objeto',
	'UI:Loading' => 'Cargando...',
	'UI:Menu:Actions' => 'Acciones',
	'UI:Menu:OtherActions' => 'Otras Acciones',
	'UI:Menu:New' => 'Nuevo...',
	'UI:History:StatsCreations' => 'Created',
	'UI:History:StatsCreations+' => 'Count of objects created',
	'UI:History:StatsModifs' => 'Modified',
	'UI:History:StatsModifs+' => 'Count of objects modified',
	'UI:History:StatsDeletes' => 'Deleted',
	'UI:History:StatsDeletes+' => 'Count of objects deleted',
	'UI:Menu:EMail' => 'eMail',
	'UI:Menu:CSVExport' => 'exportar a CSV',
	'UI:Menu:Modify' => 'Modificar...',
	'UI:Menu:Delete' => 'Borrar...',
	'UI:Menu:Manage' => 'Administrar...',
	'UI:Menu:BulkDelete' => 'Borrar...',
	'UI:UndefinedObject' => 'indefinido',
	'UI:Document:OpenInNewWindow:Download' => 'abrir en nueva ventana: %1$s, Descargar: %2$s',
	'UI:SelectAllToggle+' => 'Seleccionar / Deseleccionar Todo',
	'UI:TruncatedResults' => 'Mostrando %1$d objetos de %2$d',
	'UI:DisplayAll' => 'Mostrar todo',
	'UI:CountOfResults' => '%1$d objeto(s)',
	'UI:ChangesLogTitle' => 'Registro de cambios (%1$d):',
	'UI:EmptyChangesLogTitle' => 'Registro de cambios esta vacio',
	'UI:SearchFor_Class_Objects' => 'Buscar %1$s objetos',
	'UI:OQLQueryBuilderTitle' => 'Constructor de consultas OQL',
	'UI:OQLQueryTab' => 'Consulta OQL',
	'UI:SimpleSearchTab' => 'Busqueda simple',
	'UI:Details+' => 'Detalles',
	'UI:SearchValue:Any' => '* Cualquiera *',
	'UI:SearchValue:Mixed' => '* mezclado *',
	'UI:SelectOne' => '-- Seleccione uno --',
	'UI:Login:Welcome' => 'Bienvenido a iTop!',
	'UI:Login:IncorrectLoginPassword' => 'Usuario/Contraseña incorrecto, por favor intente otra vez.',
	'UI:Login:IdentifyYourself' => 'Identifiquese antes de continuar',
	'UI:Login:UserNamePrompt' => 'Nombre de Usuario',
	'UI:Login:PasswordPrompt' => 'Contraseña',
	'UI:Login:ChangeYourPassword' => 'Cambien su Contraseña',
	'UI:Login:OldPasswordPrompt' => 'Contraseña Anterior',
	'UI:Login:NewPasswordPrompt' => 'Contraseña Nueva',
	'UI:Login:RetypeNewPasswordPrompt' => 'Reintroduzca Nueva contraseña',
	'UI:Login:IncorrectOldPassword' => 'Error: la contraseña anterior es incorrecta',
	'UI:LogOffMenu' => 'Cerrar sesion',
	'UI:ChangePwdMenu' => 'Cambiar Contraseña...',
	'UI:AccessRO-All' => 'iTop is read-only',
	'UI:AccessRO-Users' => 'iTop is read-only for end-users',
	'UI:Login:Error:AccessRestricted' => 'El acceso a iTop esta restringido. Por favor contacte al administrador de iTop.',
	'UI:Login:Error:AccessAdmin' => 'Acceso restringido a usuarios con privilegio de administrador. Por favor contacte al administrador de iTop.',
	'UI:CSVImport:MappingSelectOne' => '-- seleccione uno --',
	'UI:CSVImport:MappingNotApplicable' => '-- ignore este campo --',
	'UI:CSVImport:NoData' => 'Conjunto de datos vacio..., pro favor provea alguna data!',
	'UI:Title:DataPreview' => 'Vista previa de datos',
	'UI:CSVImport:ErrorOnlyOneColumn' => 'Error: La data solo contiene una columna. Selecciono el separador de campos adecuado?',
	'UI:CSVImport:FieldName' => 'Campo %1$d',
	'UI:CSVImport:DataLine1' => 'Linea de datos 1',
	'UI:CSVImport:DataLine2' => 'Linea de datos 2',
	'UI:CSVImport:idField' => 'id (Clave primaria)',
	'UI:Title:BulkImport' => 'iTop - Importacion por lotes',
	'UI:Title:BulkImport+' => 'Asistente de importar CSV',
	'UI:CSVImport:ClassesSelectOne' => '-- seleccione uno --',
	'UI:CSVImport:ErrorExtendedAttCode' => 'error interno: "%1$s" es un codigo incorrecto debido a que "%2$s" NO es una clave externa de la clase "%3$s"',
	'UI:CSVImport:ObjectsWillStayUnchanged' => '%1$d objeto(s) permanecera sin cambio.',
	'UI:CSVImport:ObjectsWillBeModified' => '%1$d objeto(s) sera modificado.',
	'UI:CSVImport:ObjectsWillBeAdded' => '%1$d objeto(s) sera agregado.',
	'UI:CSVImport:ObjectsWillHaveErrors' => '%1$d objeto(s) tendra error.',
	'UI:CSVImport:ObjectsRemainedUnchanged' => '%1$d objects(s) remained unchanged.',
	'UI:CSVImport:ObjectsWereModified' => '%1$d objeto(s) sera modificado.',
	'UI:CSVImport:ObjectsWereAdded' => '%1$d objeto(s) fue agregado.',
	'UI:CSVImport:ObjectsHadErrors' => '%1$d objeto(s) tuvo errores.',
	'UI:Title:CSVImportStep2' => 'Paso 2 de 5: opciones de datos CSV',
	'UI:Title:CSVImportStep3' => 'Paso 3 de 5: mapeo de datos',
	'UI:Title:CSVImportStep4' => 'Paso 4 de 5: simular la importacion',
	'UI:Title:CSVImportStep5' => 'Paso 5 de 5: importacion completada',
	'UI:CSVImport:LinesNotImported' => 'Lineas que no pudieron ser cargadas:',
	'UI:CSVImport:LinesNotImported+' => 'Las siguientes lineas no pudieron ser importadas porque contienen errores',
	'UI:CSVImport:SeparatorComma+' => ', (coma)',
	'UI:CSVImport:SeparatorSemicolon+' => '; (punto y coma)',
	'UI:CSVImport:SeparatorTab+' => 'tabulador',
	'UI:CSVImport:SeparatorOther' => 'otro:',
	'UI:CSVImport:QualifierDoubleQuote+' => '" (comilla doble)',
	'UI:CSVImport:QualifierSimpleQuote+' => '\' (comilla simple)',
	'UI:CSVImport:QualifierOther' => 'otro:',
	'UI:CSVImport:TreatFirstLineAsHeader' => 'Use la primera linea como encabezado de columna(nombre de columnas))',
	'UI:CSVImport:Skip_N_LinesAtTheBeginning' => 'Omitir %1$s linea(s) al inicio de el archivo',
	'UI:CSVImport:CSVDataPreview' => 'Vista previa de los datos CSV',
	'UI:CSVImport:SelectFile' => 'Seleccione el archivo a importar:',
	'UI:CSVImport:Tab:LoadFromFile' => 'Cargar desde archivo',
	'UI:CSVImport:Tab:CopyPaste' => 'Copiar y pegar data',
	'UI:CSVImport:Tab:Templates' => 'Plantillas',
	'UI:CSVImport:PasteData' => 'Pegue la data a importar:',
	'UI:CSVImport:PickClassForTemplate' => 'seleccione la plantilla a descargar: ',
	'UI:CSVImport:SeparatorCharacter' => 'Caracter separador:',
	'UI:CSVImport:TextQualifierCharacter' => 'Caracter para calificar como texto',
	'UI:CSVImport:CommentsAndHeader' => 'Comentarios y encabezado',
	'UI:CSVImport:SelectClass' => 'Seleccione la clase a importar:',
	'UI:CSVImport:AdvancedMode' => 'Modo avanzado',
	'UI:CSVImport:AdvancedMode+' => 'En modo avanzado el "id" (clave primaria) de los objetos puede ser usado para actualizar y renombrar objetos.' .
									'Sin embargo, la columna "id" (si esta presente) solo puede ser usado como criterio de busqueda y no puede ser combinado con ningun otro criterio de busqueda.',
	'UI:CSVImport:SelectAClassFirst' => 'Para configurar el mapeo, primero seleccione un clase.',
	'UI:CSVImport:HeaderFields' => 'Campos',
	'UI:CSVImport:HeaderMappings' => 'Mapeo',
	'UI:CSVImport:HeaderSearch' => 'Buscar?',
	'UI:CSVImport:AlertIncompleteMapping' => 'Por favor seleccione un mapeo para cada categoria.',
	'UI:CSVImport:AlertNoSearchCriteria' => 'Por favor seleccione al menos un criterio de busqueda',
	
	'UI:UniversalSearchTitle' => 'iTop - Busqueda Universal',
	'UI:UniversalSearch:Error' => 'Error: %1$s',
	'UI:UniversalSearch:LabelSelectTheClass' => 'Seleccione la clase a buscar: ',
	
	'UI:Audit:Title' => 'iTop - Auditoria a CMDB',
	'UI:Audit:InteractiveAudit' => 'Auditoria Interactiva',
	'UI:Audit:HeaderAuditRule' => 'Reglas de Auditoria',
	'UI:Audit:HeaderNbObjects' => '# Objetos',
	'UI:Audit:HeaderNbErrors' => '# Errores',
	'UI:Audit:PercentageOk' => '% Ok',
	
	'UI:RunQuery:Title' => 'iTop - Evaluacion de consultas OQL',
	'UI:RunQuery:QueryExamples' => 'Explorador de Consultas',
	'UI:RunQuery:HeaderPurpose' => 'Proposito',
	'UI:RunQuery:HeaderPurpose+' => 'Explicacion acerca de la consulta',
	'UI:RunQuery:HeaderOQLExpression' => 'Expresion OQL',
	'UI:RunQuery:HeaderOQLExpression+' => 'La consulta en syntaxis OQL',
	'UI:RunQuery:ExpressionToEvaluate' => 'Expresion a evaluar: ',
	'UI:RunQuery:MoreInfo' => 'Mas informacion acerca de la consulta: ',
	'UI:RunQuery:DevelopedQuery' => 'Expresion de consulta rediseñada: ',
	'UI:RunQuery:SerializedFilter' => 'Filtro de serializacion: ',
	'UI:RunQuery:Error' => 'Ha ocurrido un error al ejecutar la consulta: %1$s',
	
	'UI:Schema:Title' => 'Esquema de objetos iTop',
	'UI:Schema:CategoryMenuItem' => 'Categoria <b>%1$s</b>',
	'UI:Schema:Relationships' => 'Relaciones',
	'UI:Schema:AbstractClass' => 'Clase abstracta: ningun objeto de esta clase puede ser representado.',
	'UI:Schema:NonAbstractClass' => 'Clase no abstracta: objetos de esta clase pueden ser representados.',
	'UI:Schema:ClassHierarchyTitle' => 'Jerarquia de clases',
	'UI:Schema:AllClasses' => 'Todas las clases',
	'UI:Schema:ExternalKey_To' => 'clave externa a %1$s',
	'UI:Schema:Columns_Description' => 'Columnas: <em>%1$s</em>',
	'UI:Schema:Default_Description' => 'Predeterminar: "%1$s"',
	'UI:Schema:NullAllowed' => 'Permite Null',
	'UI:Schema:NullNotAllowed' => 'NO permite Null',
	'UI:Schema:Attributes' => 'Atributos',
	'UI:Schema:AttributeCode' => 'Codigo de Atributo',
	'UI:Schema:AttributeCode+' => 'Codigo interno del atributo',
	'UI:Schema:Label' => 'Etiqueta',
	'UI:Schema:Label+' => 'Etiqueta del atributo',
	'UI:Schema:Type' => 'Tipo',
	
	'UI:Schema:Type+' => 'Tipo de dato del atributo',
	'UI:Schema:Origin' => 'Origen',
	'UI:Schema:Origin+' => 'La clase base en donde esta definido este atributo',
	'UI:Schema:Description' => 'Descripcion',
	'UI:Schema:Description+' => 'Descripcion del atributo',
	'UI:Schema:AllowedValues' => 'Valores permitidos',
	'UI:Schema:AllowedValues+' => 'Restricciones en los posibles valores para este atributo',
	'UI:Schema:MoreInfo' => 'Mas informacion',
	'UI:Schema:MoreInfo+' => 'Mas informacion acerca del campo definido en la base de datos',
	'UI:Schema:SearchCriteria' => 'Criterio de busqueda',
	'UI:Schema:FilterCode' => 'Codigo de filtro',
	'UI:Schema:FilterCode+' => 'Codigo de este criterio de busqueda',
	'UI:Schema:FilterDescription' => 'Descripcion',
	'UI:Schema:FilterDescription+' => 'Descripcion de este criterio de busqueda',
	'UI:Schema:AvailOperators' => 'Operadores disponibles',
	'UI:Schema:AvailOperators+' => 'Operadores posibles para este criterio de busqueda',
	'UI:Schema:ChildClasses' => 'Clases menores',
	'UI:Schema:ReferencingClasses' => 'Clases de referencia',
	'UI:Schema:RelatedClasses' => 'Clases relacionadas',
	'UI:Schema:LifeCycle' => 'Ciclo de vida',
	'UI:Schema:Triggers' => 'Gatillos',
	'UI:Schema:Relation_Code_Description' => 'Relacion <em>%1$s</em> (%2$s)',
	'UI:Schema:RelationDown_Description' => 'Abajo: %1$s',
	'UI:Schema:RelationUp_Description' => 'Arriba: %1$s',
	'UI:Schema:RelationPropagates' => '%1$s: propagar a %2$d niveles, consulta: %3$s',
	'UI:Schema:RelationDoesNotPropagate' => '%1$s: no se propaga(%2$d nivel), consulta: %3$s',
	'UI:Schema:Class_ReferencingClasses_From_By' => '%1$s esta referenciado por la clase %2$s a travez de el campo %3$s',
	'UI:Schema:Class_IsLinkedTo_Class_Via_ClassAndAttribute' => '%1$s esta vinculado a %2$s a travez de %3$s::<em>%4$s</em>',
	'UI:Schema:Links:1-n' => 'Clases apuntando a %1$s (1:n enlaces):',
	'UI:Schema:Links:n-n' => 'Clases apuntando a %1$s (n:n enlaces):',
	'UI:Schema:Links:All' => 'Grafico de todos los casos relacionados',
	'UI:Schema:NoLifeCyle' => 'No hay ciclo de vida definido para esta clase.',
	'UI:Schema:LifeCycleTransitions' => 'Transiciones',
	'UI:Schema:LifeCyleAttributeOptions' => 'Opciones del atributo',
	'UI:Schema:LifeCycleHiddenAttribute' => 'Oculto',
	'UI:Schema:LifeCycleReadOnlyAttribute' => 'Solo-lectrura',
	'UI:Schema:LifeCycleMandatoryAttribute' => 'Mandatorio',
	'UI:Schema:LifeCycleAttributeMustChange' => 'Debe cambiar',
	'UI:Schema:LifeCycleAttributeMustPrompt' => 'Se le pedira al usuario que cambie el valor',
	'UI:Schema:LifeCycleEmptyList' => 'lista vacia',
	
	'UI:LinksWidget:Autocomplete+' => 'Escriba los primeros 3 caracteres...',
	'UI:Combo:SelectValue' => '--- seleccione un valor ---',
	'UI:Label:SelectedObjects' => 'Objetos seleccionados: ',
	'UI:Label:AvailableObjects' => 'Objetos disponibles: ',
	'UI:Link_Class_Attributes' => '%1$s atributos',
	'UI:SelectAllToggle+' => 'Seleccionar todo / Deseleccionar todo',
	'UI:AddObjectsOf_Class_LinkedWith_Class_Instance' => 'Agregar %1$s objetos vinculados con %2$s: %3$s',
	'UI:ManageObjectsOf_Class_LinkedWith_Class_Instance' => 'Administrar %1$s objetos vinculados con %2$s: %3$s',
	'UI:AddLinkedObjectsOf_Class' => 'Agregar %1$ss...',
	'UI:RemoveLinkedObjectsOf_Class' => 'Eliminar los objetos seleccionados',
	'UI:Message:EmptyList:UseAdd' => 'La lista esta vaica, use el boton Agregar... para agregar elementos.',
	'UI:Message:EmptyList:UseSearchForm' => 'Use la forma arriba para buscar objetos a ser agregados.',
	
	'UI:Wizard:FinalStepTitle' => 'Paso final: Confirmacion',
	'UI:Title:DeletionOf_Object' => 'Borrado de %1$s',
	'UI:Title:BulkDeletionOf_Count_ObjectsOf_Class' => 'Borrado por lote de %1$d objetos de la clase %2$s',
	'UI:Delete:NotAllowedToDelete' => 'No esta autorizado para borrar este objeto',
	'UI:Delete:NotAllowedToUpdate_Fields' => 'No esta autorizado para actualizar el siguiente campo(s): %1$s',
	'UI:Error:NotEnoughRightsToDelete' => 'Este objeto no pudo ser borrado porque el usuario actual no posee suficientes permisos',
	'UI:Error:CannotDeleteBecauseOfDepencies' => 'Este objeto no pudo ser borrado porque algunas operaciones manuales deben ser ejecutadas antes de eso',
	'UI:Archive_User_OnBehalfOf_User' => '%1$s en nombre de %2$s',
	'UI:Delete:AutomaticallyDeleted' => 'Borrado automaticamente',
	'UI:Delete:AutomaticResetOf_Fields' => 'reinicio automatico de campo(s): %1$s',
	'UI:Delete:CleaningUpRefencesTo_Object' => 'Limpiando todas las referencias a %1$s...',
	'UI:Delete:CleaningUpRefencesTo_Several_ObjectsOf_Class' => 'Limpiando todas las referencias a %1$d objetos de la clase %2$s...',
	'UI:Delete:Done+' => 'Lo que se hizo...',
	'UI:Delete:_Name_Class_Deleted' => '%1$s - %2$s borrado.',
	'UI:Delete:ConfirmDeletionOf_Name' => 'Borrado de %1$s',
	'UI:Delete:ConfirmDeletionOf_Count_ObjectsOf_Class' => 'Borrado de %1$d objetos de al clase %2$s',
//	'UI:Delete:ShouldBeDeletedAtomaticallyButNotPossible' => 'Beberia ser eliminado automaticamente, pero usted no esta autorizado para hacerlo',
//	'UI:Delete:MustBeDeletedManuallyButNotPossible' => 'Debe ser borrado manualmente - pero usted no esta autorizado para borrar este objeto, por favor contacte al administrador de la aplicacion',
	'UI:Delete:WillBeDeletedAutomatically' => 'Sera borrado automaticamente',
	'UI:Delete:MustBeDeletedManually' => 'Debe ser borrado manualmente',
	'UI:Delete:CannotUpdateBecause_Issue' => 'Debe ser actualizado automaticamente, pero: %1$s',
	'UI:Delete:WillAutomaticallyUpdate_Fields' => 'sera actualizado automaticamente (reset: %1$s)',
	'UI:Delete:Count_Objects/LinksReferencing_Object' => '%1$d objetos/vinculos estan referenciando %2$s',
	'UI:Delete:Count_Objects/LinksReferencingTheObjects' => '%1$d objetos/vinculos estan referenciando algunos de los objetos a ser borrados',	
	'UI:Delete:ReferencesMustBeDeletedToEnsureIntegrity' => 'Para asegurar la integridad de la Base de Datos, cualquier referencia debera ser completamente eliminada',
	'UI:Delete:Consequence+' => 'Lo que se hara',
	'UI:Delete:SorryDeletionNotAllowed' => 'Disculpe, usted no esta autorizado a eliminar este objeto, vea la explciacion detallada abajo',
	'UI:Delete:PleaseDoTheManualOperations' => 'Por favor ejecute las operaciones manuales antes de eliminar este objeto',
	'UI:Delect:Confirm_Object' => 'Por favor confirme que quiere borrar %1$s.',
	'UI:Delect:Confirm_Count_ObjectsOf_Class' => 'Port favor confirme que quiere eliminar los siguientes %1$d objeto de la clase %2$s.',
	'UI:WelcomeToITop' => 'Bienvenido a iTop',
	'UI:DetailsPageTitle' => 'iTop - %1$s - %2$s detalles',
	'UI:ErrorPageTitle' => 'iTop - Error',
	'UI:ObjectDoesNotExist' => 'Disculpe, este objeto no existe (o no esta autorizado para verlo).',
	'UI:SearchResultsPageTitle' => 'iTop - Resultados de la Busqueda',
	'UI:Search:NoSearch' => 'Nada para buscar',
	'UI:FullTextSearchTitle_Text' => 'Resultados para "%1$s":',
	'UI:Search:Count_ObjectsOf_Class_Found' => '%1$d objeto(s) de la clase %2$s encontrado(s).',
	'UI:Search:NoObjectFound' => 'No se encontraron objetos.',
	'UI:ModificationPageTitle_Object_Class' => 'iTop - %1$s - %2$s modificacion',
	'UI:ModificationTitle_Class_Object' => 'Modificacion de %1$s: <span class=\"hilite\">%2$s</span>',
	'UI:ClonePageTitle_Object_Class' => 'iTop - Duplicar %1$s - %2$s modificacion',
	'UI:CloneTitle_Class_Object' => 'Duplicado de %1$s: <span class=\"hilite\">%2$s</span>',
	'UI:CreationPageTitle_Class' => 'iTop - Creacion de un nuevo %1$s ',
	'UI:CreationTitle_Class' => 'Creacion de un nuevo %1$s',
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
	'UI:FatalErrorMessage' => 'Error fatal, iTop no puede continuar.',
	'UI:SystemIntrusion' => 'Acceso denegado. Esta tratando de ejecutar una operacion no permitida para usted.',
	'UI:Error_Details' => 'Error: %1$s.',

	'UI:PageTitle:ClassProjections'	=> 'Administracion de usuarios iTop - proyecciones de clases',
	'UI:PageTitle:ProfileProjections' => 'Administracion de usuarios iTop - proyecciones de perfil',
	'UI:UserManagement:Class' => 'Clase',
	'UI:UserManagement:Class+' => 'Clase de objetos',
	'UI:UserManagement:ProjectedObject' => 'Objeto',
	'UI:UserManagement:ProjectedObject+' => 'Objeto proyectado',
	'UI:UserManagement:AnyObject' => '* cualquiera *',
	'UI:UserManagement:User' => 'Usuario',
	'UI:UserManagement:User+' => 'Usuario implicado en la proyeccion',
	'UI:UserManagement:Profile' => 'Perfil',
	'UI:UserManagement:Profile+' => 'Perfil en el cual se especifico la proyeccion',
	'UI:UserManagement:Action:Read' => 'Leer',
	'UI:UserManagement:Action:Read+' => 'Leer/Mostrar objetos',
	'UI:UserManagement:Action:Modify' => 'Modificar',
	'UI:UserManagement:Action:Modify+' => 'Crear y editar (modificar) objetos',
	'UI:UserManagement:Action:Delete' => 'Eliminar',
	'UI:UserManagement:Action:Delete+' => 'Eliminar objetos',
	'UI:UserManagement:Action:BulkRead' => 'Lectura por lote (Exportar)',
	'UI:UserManagement:Action:BulkRead+' => 'Listar objetos o exportar masivamente',
	'UI:UserManagement:Action:BulkModify' => 'Modificacion masiva',
	'UI:UserManagement:Action:BulkModify+' => 'Crear/Editar masivamente (importar CSV)',
	'UI:UserManagement:Action:BulkDelete' => 'eliminacion masiva',
	'UI:UserManagement:Action:BulkDelete+' => 'eliminacion masiva de objetos',
	'UI:UserManagement:Action:Stimuli' => 'Stimuli',
	'UI:UserManagement:Action:Stimuli+' => 'Acciones (compound) permitidas',
	'UI:UserManagement:Action' => 'Accion',
	'UI:UserManagement:Action+' => 'Accion ejecutada por el usuario',
	'UI:UserManagement:TitleActions' => 'Acciones',
	'UI:UserManagement:Permission' => 'Permisos',
	'UI:UserManagement:Permission+' => 'Permisos de usuario',
	'UI:UserManagement:Attributes' => 'Atributos',
	'UI:UserManagement:ActionAllowed:Yes' => 'Si',
	'UI:UserManagement:ActionAllowed:No' => 'No',
	'UI:UserManagement:AdminProfile+' => 'Los administradores tienen acceso total de lectura/escritura para todos los objetos en la base de datos.',
	'UI:UserManagement:NoLifeCycleApplicable' => 'N/A',
	'UI:UserManagement:NoLifeCycleApplicable+' => 'No se ha definido ciclo de vida para esta clase',
	'UI:UserManagement:GrantMatrix' => 'Matriz de acceso',
	'UI:UserManagement:LinkBetween_User_And_Profile' => 'Vinculo entre %1$s y %2$s',
	
	'Menu:AdminTools' => 'Herramientas Administrativas',
	'Menu:AdminTools+' => 'Herramientas de administracion',
	'Menu:AdminTools?' => 'Herramientas accesibles soloa  usuariso con perfil de administrador',

	'UI:ChangeManagementMenu' => 'Control de Cambios',
	'UI:ChangeManagementMenu+' => 'Control de Cambios',
	'UI:ChangeManagementMenu:Title' => 'Sumario de cambios',
	'UI-ChangeManagementMenu-ChangesByType' => 'Cambios por tipo',
	'UI-ChangeManagementMenu-ChangesByStatus' => 'Cambios por estado',
	'UI-ChangeManagementMenu-ChangesByWorkgroup' => 'Cambios por grupo de trabajo',
	'UI-ChangeManagementMenu-ChangesNotYetAssigned' => 'Cambios no asignados aun',

	'UI:ConfigurationItemsMenu'=> 'Elementos de configuracion',
	'UI:ConfigurationItemsMenu+'=> 'Todos los dispositivos',
	'UI:ConfigurationItemsMenu:Title' => 'Sumario de Elementos de Configuracion',
	'UI-ConfigurationItemsMenu-ServersByCriticity' => 'Servidores por criticidad',
	'UI-ConfigurationItemsMenu-PCsByCriticity' => 'PCs por criticidad',
	'UI-ConfigurationItemsMenu-NWDevicesByCriticity' => 'Dispositivos de red por criticidad',
	'UI-ConfigurationItemsMenu-ApplicationsByCriticity' => 'Aplicaciones por criticidad',
	
	'UI:ConfigurationManagementMenu' => 'Gestion de la Configuracion',
	'UI:ConfigurationManagementMenu+' => 'Gestion de la Configuracion',
	'UI:ConfigurationManagementMenu:Title' => 'Sumario de Infrastructura',
	'UI-ConfigurationManagementMenu-InfraByType' => 'Objetos de infrastructura por tipo',
	'UI-ConfigurationManagementMenu-InfraByStatus' => 'Objetos de infraestructura por estatus',

'UI:ConfigMgmtMenuOverview:Title' => 'Panel de control for Gestion de la Configuracion',
'UI-ConfigMgmtMenuOverview-FunctionalCIbyStatus' => 'Elementos de la configuracion por estado',
'UI-ConfigMgmtMenuOverview-FunctionalCIByType' => 'elementos de configuracion por tipo',

'UI:RequestMgmtMenuOverview:Title' => 'Panel de control for Gestion de Solicitudes',
'UI-RequestManagementOverview-RequestByService' => 'Solicitudes de usuario por servicio',
'UI-RequestManagementOverview-RequestByPriority' => 'Solicitudes de usuario por prioridad',
'UI-RequestManagementOverview-RequestUnassigned' => 'Solicitudes de usuario sin asignar a un agente',

'UI:IncidentMgmtMenuOverview:Title' => 'Panel de control for Gestion de Incidentes',
'UI-IncidentManagementOverview-IncidentByService' => 'Incidentes por servicio',
'UI-IncidentManagementOverview-IncidentByPriority' => 'Incidentes por prioridad',
'UI-IncidentManagementOverview-IncidentUnassigned' => 'Incidentes no asignados a un agente',

'UI:ChangeMgmtMenuOverview:Title' => 'Panel de control for Control de Cambios',
'UI-ChangeManagementOverview-ChangeByType' => 'Cambios por tipo',
'UI-ChangeManagementOverview-ChangeUnassigned' => 'Cambios no asignados a un agente',
'UI-ChangeManagementOverview-ChangeWithOutage' => 'Interrupciones de servicios debida a cambios',

'UI:ServiceMgmtMenuOverview:Title' => 'Panel de control for Gestion de Servicios',
'UI-ServiceManagementOverview-CustomerContractToRenew' => 'Contratos de usuario a ser renovados en 30 dias',
'UI-ServiceManagementOverview-ProviderContractToRenew' => 'contratos de proveedores a ser renovados en 30 dias',

	'UI:ContactsMenu' => 'Contactos',
	'UI:ContactsMenu+' => 'Contactos',
	'UI:ContactsMenu:Title' => 'Sumario de Contactos',
	'UI-ContactsMenu-ContactsByLocation' => 'Contactos por ubicacion',
	'UI-ContactsMenu-ContactsByType' => 'Contactos por tipo',
	'UI-ContactsMenu-ContactsByStatus' => 'Contactos por estado',

	'Menu:CSVImportMenu' => 'Importar CSV',
	'Menu:CSVImportMenu+' => 'Creacion o actualizacion masiva',
	
	'Menu:DataModelMenu' => 'Modelo de Datos',
	'Menu:DataModelMenu+' => 'Sumario del Modelo de Datos',
	
	'Menu:ExportMenu' => 'Exportar',
	'Menu:ExportMenu+' => 'Exportar los resultados de cualquier consulta eb HTML, CSV o XML',
	
	'Menu:NotificationsMenu' => 'Notificaciones',
	'Menu:NotificationsMenu+' => 'Configuracion de las Notificaciones',
	'UI:NotificationsMenu:Title' => 'Configuracion de las <span class="hilite">Notificaciones</span>',
	'UI:NotificationsMenu:Help' => 'Ayuda',
	'UI:NotificationsMenu:HelpContent' => '<p>En iTop las notificaciones son completamente personalizables. Estan basadas en dos conjuntos de objetos: <i>Gatuillos y acciones</i>.</p>
<p><i><b>Gatillos</b></i> definen cuando una notificacion debe ser ejecutada. existen 3 tipos de gatillos para cubrir las 3 diferentes fases del ciclo de vida de un objeto:
<ol>
	<li>los gatillos "OnCreate" son ejecutados cuando un objeto de la clase especificada es creado</li>
	<li>los gatillos "OnStateEnter" son ejecutados antes de que un determinado objeto entre un estado especificado (viniendo de otro estado)</li>
	<li>los gatillos "OnStateLeave" son ejecutados cuando un objeto de clase determinada deja un estado especificado</li>
</ol>
</p>
<p>
<i><b>Acciones</b></i> definen las acciones a ser ejecutadas cuando los gatillos se disparan, por ahora el unico tipo de accion consiste en enviar un mensaje de correo.
Tales acciones tambien definen la plantilla a ser usada para enviar el correo asi como otros parametros del mensaje como receptor, importancia, etc.
</p>
<p>Una pagina especial: <a href="../setup/email.test.php" target="_blank">email.test.php</a> esta disponible para pruebar y diagnosticar su configuracion de correo de PHP.</p>
<p>Para ser ejecutadas, las acciones deben estar asociadas con los gatillos.
Cuando se asocien con un gatillo, cada accion recibe un numero de "orden", esto especifica en que orden se ejecutaran las acciones.</p>',
	'UI:NotificationsMenu:Triggers' => 'Disparadores',
	'UI:NotificationsMenu:AvailableTriggers' => 'Disparadores disponibles',
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
	'UI:iTopVersion:Long' => 'iTop versión %1$s-%2$s compilada en %3$s',
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
	'UI:PasswordConfirm' => '(Confirmar)',

	'Enum:Undefined' => 'Indefinido',
));

?>
