<?php
// Copyright (C) 2010-2012 Combodo SARL
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
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Core:DeletedObjectLabel' => '%1s (eliminado)',
	'Core:DeletedObjectTip' => 'Elemento ha sido Eliminado en %1$s (%2$s)',

	'Core:UnknownObjectLabel' => 'Elemento No Encontrado (Clase: %1$s, Identificador: %2$d)',
	'Core:UnknownObjectTip' => 'El Elemento no pudo ser encontrado.  Pudo haber sido eliminado hace tiempo y purgado de la Bitácora.',

	'Core:AttributeLinkedSet' => 'Arreglo de objetos',
	'Core:AttributeLinkedSet+' => 'Cualquier tipo de objetos [subclass] de la misma clase',

	'Core:AttributeLinkedSetIndirect' => 'Arreglo de objetos (N-N)',
	'Core:AttributeLinkedSetIndirect+' => 'Cualquier tipo de objetos [subclass] de la misma clase',

	'Core:AttributeInteger' => 'Entero',
	'Core:AttributeInteger+' => 'Valor numérico (puede ser negativo)',

	'Core:AttributeDecimal' => 'Decimal',
	'Core:AttributeDecimal+' => 'Valor decimal (puede ser negativo)',

	'Core:AttributeBoolean' => 'Booleano',
	'Core:AttributeBoolean+' => 'Booleano',

	'Core:AttributeString' => 'Cadena de caracteres',
	'Core:AttributeString+' => 'Cadena de caracteres alfanumerico',

	'Core:AttributeClass' => 'Clase',
	'Core:AttributeClass+' => 'Clase',

	'Core:AttributeApplicationLanguage' => 'Lenguaje del usuario',
	'Core:AttributeApplicationLanguage+' => 'Lenguaje y país (EN US)',

	'Core:AttributeFinalClass' => 'Clase (auto)',
	'Core:AttributeFinalClass+' => 'Clase real del objeto (automaticamente creada por el core)',

	'Core:AttributePassword' => 'Contrase&ntilde;a',
	'Core:AttributePassword+' => 'Contrase&ntilde;a para dispositivo externo',

 	'Core:AttributeEncryptedString' => 'Cadena encriptada',
	'Core:AttributeEncryptedString+' => 'Cadena encriptada con llave local',

	'Core:AttributeText' => 'Texto',
	'Core:AttributeText+' => 'Cadena de Múltiples Líneas de Caracteres',

	'Core:AttributeHTML' => 'HTML',
	'Core:AttributeHTML+' => 'Cadena HTML',

	'Core:AttributeEmailAddress' => 'Correo Electrónico',
	'Core:AttributeEmailAddress+' => 'Correo Electrónico',

	'Core:AttributeIPAddress' => 'Dirección IP',
	'Core:AttributeIPAddress+' => 'Dirección IP',

	'Core:AttributeOQL' => 'OQL',
	'Core:AttributeOQL+' => 'Object Query Language expresion',

	'Core:AttributeEnum' => 'Enum',
	'Core:AttributeEnum+' => 'Lista de cadenas alfanumericas predefinidas',

	'Core:AttributeTemplateString' => 'Cadena de plantilla',
	'Core:AttributeTemplateString+' => 'Cadena conteniendo lugares',

	'Core:AttributeTemplateText' => 'Texto de plantilla',
	'Core:AttributeTemplateText+' => 'Texto conteniendo lugares',

	'Core:AttributeTemplateHTML' => 'Plantilla HTML',
	'Core:AttributeTemplateHTML+' => 'HTML conteniendo lugares',

	'Core:AttributeDateTime' => 'Fecha/hora',
	'Core:AttributeDateTime+' => 'Fecha y horae (año-mes-dia hh:mm:ss)',
	'Core:AttributeDateTime?SmartSearch' => '
<p>
	Formato de Fecha:<br/>
	<b>yyyy-mm-dd hh:mm:ss</b><br/>
	Ejemplo: 2011-07-19 18:40:00
</p>
<p>
Operadores:<br/>
	<b>&gt;</b><em>fecha</em><br/>
	<b>&lt;</b><em>fecha</em><br/>
	<b>[</b><em>fecha</em>,<em>fecha</em><b>]</b>
</p>
<p>
Si se omite el tiempo, por omisión es 00:00:00
</p>',

	'Core:AttributeDate' => 'Fecha',
	'Core:AttributeDate+' => 'Fecha (año-mes-dia)',
	'Core:AttributeDate?SmartSearch' => '
<p>
	Formato de Fecha:<br/>
	<b>yyyy-mm-dd</b><br/>
	Ejemplo: 2011-07-19
</p>
<p>
Operadores:<br/>
	<b>&gt;</b><em>fecha</em><br/>
	<b>&lt;</b><em>fecha</em><br/>
	<b>[</b><em>fecha</em>,<em>fecha</em><b>]</b>
</p>',

	'Core:AttributeDeadline' => 'Fecha límite',
	'Core:AttributeDeadline+' => 'Fecha, desplegada relativamente al tiempo actual',

	'Core:AttributeExternalKey' => 'Llave externa',
	'Core:AttributeExternalKey+' => 'Llave external o foránea',

	'Core:AttributeHierarchicalKey' => 'Llave jerárquica',
	'Core:AttributeHierarchicalKey+' => 'Llave externa o foránea al padre',
	'Core:AttributeExternalField' => 'Campo externo',
	'Core:AttributeExternalField+' => 'Campo mapeado de una llave externa',

	'Core:AttributeURL' => 'URL',
	'Core:AttributeURL+' => 'URL absoluto o relativo',

	'Core:AttributeBlob' => 'Blob',
	'Core:AttributeBlob+' => 'Cualquier contenido binario (documento)',

	'Core:AttributeOneWayPassword' => 'Contrase&ntilde;a de una sola vía',
	'Core:AttributeOneWayPassword+' => 'Contrase&ntilde;a encriptada de una sola vía (hashed)',

	'Core:AttributeTable' => 'Tabla',
	'Core:AttributeTable+' => 'Arreglo indexado con dos dimensiones',

	'Core:AttributePropertySet' => 'Propiedades',
	'Core:AttributePropertySet+' => 'Lista de propiedades sin tipo (nombre y valor)',

	'Core:AttributeFriendlyName' => 'Nombre común',
	'Core:AttributeFriendlyName+' => 'Atributo creado automáticamente; el nombre común es obtenido de varios atributos',

	'Core:FriendlyName-Label' => 'Nombre común',
	'Core:FriendlyName-Description' => 'Nombre común',
));


//////////////////////////////////////////////////////////////////////
// Classes in 'core/cmdb'
//////////////////////////////////////////////////////////////////////
//

//
// Class: CMDBChange
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:CMDBChange' => 'Cambio',
	'Class:CMDBChange+' => 'Cambios',
	'Class:CMDBChange/Attribute:date' => 'Fecha',
	'Class:CMDBChange/Attribute:date+' => 'Fecha y Hora en que los Cambios fueron Registrados',
	'Class:CMDBChange/Attribute:userinfo' => 'Información Adicional',
	'Class:CMDBChange/Attribute:userinfo+' => 'Información definida por el solicitante',
));

//
// Class: CMDBChangeOp
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:CMDBChangeOp' => 'Operación de Cambios',
	'Class:CMDBChangeOp+' => 'Operación de Cambios',
	'Class:CMDBChangeOp/Attribute:change' => 'Cambio',
	'Class:CMDBChangeOp/Attribute:change+' => 'Cambio',
	'Class:CMDBChangeOp/Attribute:date' => 'Fecha',
	'Class:CMDBChangeOp/Attribute:date+' => 'Fecha y Hora del Cambio',
	'Class:CMDBChangeOp/Attribute:userinfo' => 'Usuario',
	'Class:CMDBChangeOp/Attribute:userinfo+' => 'Quién hizo este Cambio',
	'Class:CMDBChangeOp/Attribute:objclass' => 'Clase de Objeto',
	'Class:CMDBChangeOp/Attribute:objclass+' => 'Clase de Objeto',
	'Class:CMDBChangeOp/Attribute:objkey' => 'Id de Objeto',
	'Class:CMDBChangeOp/Attribute:objkey+' => 'Id de Objeto',
	'Class:CMDBChangeOp/Attribute:finalclass' => 'Clase',
	'Class:CMDBChangeOp/Attribute:finalclass+' => 'Clase',
));

//
// Class: CMDBChangeOpCreate
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:CMDBChangeOpCreate' => 'Creación de Objeto',
	'Class:CMDBChangeOpCreate+' => 'Creación de Objeto',
));

//
// Class: CMDBChangeOpDelete
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:CMDBChangeOpDelete' => 'Borrado de Objeto',
	'Class:CMDBChangeOpDelete+' => 'Borrado de Objeto',
));

//
// Class: CMDBChangeOpSetAttribute
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:CMDBChangeOpSetAttribute' => 'Cambio en Objeto',
	'Class:CMDBChangeOpSetAttribute+' => 'Cambio en Objeto',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode' => 'Atributo',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode+' => 'Código de la propiedad modificada',
));

//
// Class: CMDBChangeOpSetAttributeScalar
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:CMDBChangeOpSetAttributeScalar' => 'Cambio de Propiedad',
	'Class:CMDBChangeOpSetAttributeScalar+' => 'Cambio de Propiedades escalares del Objeto',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue' => 'Valor Anterior',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue+' => 'valor Anterior del Atributo',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue' => 'Nuevo Valor',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue+' => 'Nuevo Valor del Atributo',
));
// Used by CMDBChangeOp... & derived classes
Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Change:ObjectCreated' => 'Objeto Creado',
	'Change:ObjectDeleted' => 'Objeto Eliminado',
	'Change:ObjectModified' => 'Objeto Modificado',
	'Change:AttName_SetTo_NewValue_PreviousValue_OldValue' => '%1$s cambiado en %2$s (valor anterior: %3$s)',
	'Change:AttName_SetTo' => '%1$s cambiado en %2$s',
	'Change:Text_AppendedTo_AttName' => '%1$s agregado a %2$s',
	'Change:AttName_Changed_PreviousValue_OldValue' => '%1$s cambiado, valor anterior: %2$s',
	'Change:AttName_Changed' => '%1$s cambiado',
	'Change:AttName_EntryAdded' => '%1$s cambiado, nuevo registro agregado.',
	'Change:LinkSet:Added' => 'Agregado %1$s',
	'Change:LinkSet:Removed' => 'Removido %1$s',
	'Change:LinkSet:Modified' => 'Modificado %1$s',
));

//
// Class: CMDBChangeOpSetAttributeBlob
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:CMDBChangeOpSetAttributeBlob' => 'Cambio de Datos',
	'Class:CMDBChangeOpSetAttributeBlob+' => 'Cambio de Datos',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata' => 'Valor Anterior',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata+' => 'Valor Anterior del Atributo',
));

//
// Class: CMDBChangeOpSetAttributeText
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:CMDBChangeOpSetAttributeText' => 'Cambio de Texto',
	'Class:CMDBChangeOpSetAttributeText+' => 'Cambio de Texto',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata' => 'Valor Anterior',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata+' => 'Valor Anterior del Atributo',
));

//
// Class: Event
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Event' => 'Bitácora de Eventos',
	'Class:Event+' => 'Evento interno de aplicación',
	'Class:Event/Attribute:message' => 'Mensaje',
	'Class:Event/Attribute:message+' => 'Descripción corta del evento',
	'Class:Event/Attribute:date' => 'Fecha',
	'Class:Event/Attribute:date+' => 'Fecha y Hora en que los Cambios fueron Regitrados',
	'Class:Event/Attribute:userinfo' => 'Información de Usuario',
	'Class:Event/Attribute:userinfo+' => 'Indentificación de la actividad que realizaba el usuario durante la cual se disparó este evento',
	'Class:Event/Attribute:finalclass' => 'Clase',
	'Class:Event/Attribute:finalclass+' => 'Clase',
));

//
// Class: EventNotification
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:EventNotification' => 'Notificación de Evento',
	'Class:EventNotification+' => 'Notificación de Evento',
	'Class:EventNotification/Attribute:trigger_id' => 'Disparador',
	'Class:EventNotification/Attribute:trigger_id+' => 'Disparador',
	'Class:EventNotification/Attribute:action_id' => 'Usuario',
	'Class:EventNotification/Attribute:action_id+' => 'Cuenta de usuario',
	'Class:EventNotification/Attribute:object_id' => 'Id de Objeto',
	'Class:EventNotification/Attribute:object_id+' => 'Id de objeto (¿clase definida por el disparador?)',
));

//
// Class: EventNotificationEmail
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:EventNotificationEmail' => 'Correo Electrónico de Notificación de Evento',
	'Class:EventNotificationEmail+' => 'Correo Electrónico de Notificación de Evento',
	'Class:EventNotificationEmail/Attribute:to' => 'Para',
	'Class:EventNotificationEmail/Attribute:to+' => 'Destinatario',
	'Class:EventNotificationEmail/Attribute:cc' => 'CC',
	'Class:EventNotificationEmail/Attribute:cc+' => 'Copia',
	'Class:EventNotificationEmail/Attribute:bcc' => 'CCO',
	'Class:EventNotificationEmail/Attribute:bcc+' => 'Copia Oculta',
	'Class:EventNotificationEmail/Attribute:from' => 'De',
	'Class:EventNotificationEmail/Attribute:from+' => 'Remitente del mensaje',
	'Class:EventNotificationEmail/Attribute:subject' => 'Asunto',
	'Class:EventNotificationEmail/Attribute:subject+' => 'Asunto',
	'Class:EventNotificationEmail/Attribute:body' => 'Cuerpo del mensaje',
	'Class:EventNotificationEmail/Attribute:body+' => 'Cuerpo del mensaje',
  'Class:EventNotificationEmail/Attribute:attachments' => 'Anexos',
  'Class:EventNotificationEmail/Attribute:attachments+' => 'Anexos',
));

//
// Class: EventIssue
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:EventIssue' => 'Registro de Evento',
	'Class:EventIssue+' => 'Evidencia de un evento (warning, error, etc.)',
	'Class:EventIssue/Attribute:issue' => 'Evento',
	'Class:EventIssue/Attribute:issue+' => 'Qué pasó',
	'Class:EventIssue/Attribute:impact' => 'Impacto',
	'Class:EventIssue/Attribute:impact+' => 'Cuales son las consecuencias',
	'Class:EventIssue/Attribute:page' => 'Página',
	'Class:EventIssue/Attribute:page+' => 'Punto de entrada HTTP',
	'Class:EventIssue/Attribute:arguments_post' => 'Argumentos usados',
	'Class:EventIssue/Attribute:arguments_post+' => 'Argumentos HTTP POST',
	'Class:EventIssue/Attribute:arguments_get' => 'Argumentos URL',
	'Class:EventIssue/Attribute:arguments_get+' => 'Argumentos HTTP GET',
	'Class:EventIssue/Attribute:callstack' => 'Secuencia de llamadas',
	'Class:EventIssue/Attribute:callstack+' => 'Pila de llamadas',
	'Class:EventIssue/Attribute:data' => 'Datos',
	'Class:EventIssue/Attribute:data+' => 'Más información',
));

//
// Class: EventWebService
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:EventWebService' => 'Evento de WebService',
	'Class:EventWebService+' => 'Evidencia de una llamada de servicio Web',
	'Class:EventWebService/Attribute:verb' => 'Verbo',
	'Class:EventWebService/Attribute:verb+' => 'Nombre de la operación',
	'Class:EventWebService/Attribute:result' => 'Resultado',
	'Class:EventWebService/Attribute:result+' => 'Exito/Falla Total',
	'Class:EventWebService/Attribute:log_info' => 'Bitácora de Información',
	'Class:EventWebService/Attribute:log_info+' => 'Bitácora de Resultado',
	'Class:EventWebService/Attribute:log_warning' => 'Bitácora de Advertencia',
	'Class:EventWebService/Attribute:log_warning+' => 'Bitácora de Resultado de Advertencia',
	'Class:EventWebService/Attribute:log_error' => 'Bitácora de Error',
	'Class:EventWebService/Attribute:log_error+' => 'Bitácora de Error de Resultado',
	'Class:EventWebService/Attribute:data' => 'Datos',
	'Class:EventWebService/Attribute:data+' => 'Datos de Resultado',
));

//
// Class: EventLoginUsage
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:EventLoginUsage' => 'Uso de la Cuenta',
	'Class:EventLoginUsage+' => 'Uso de la Cuenta',
	'Class:EventLoginUsage/Attribute:user_id' => 'Usuario',
	'Class:EventLoginUsage/Attribute:user_id+' => 'Usuario',
	'Class:EventLoginUsage/Attribute:contact_name' => 'Nombre',
	'Class:EventLoginUsage/Attribute:contact_name+' => 'Nombre',
	'Class:EventLoginUsage/Attribute:contact_email' => 'Correo Electrónico',
	'Class:EventLoginUsage/Attribute:contact_email+' => 'Correo Electrónico del usuario',
));

//
// Class: Action
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Action' => 'Acción Personalizada',
	'Class:Action+' => 'Acción definida por el usuario',
	'Class:Action/Attribute:name' => 'Nombre',
	'Class:Action/Attribute:name+' => 'Nombre de la Acción',
	'Class:Action/Attribute:description' => 'Descripción',
	'Class:Action/Attribute:description+' => 'Descripción',
	'Class:Action/Attribute:status' => 'Estatus',
	'Class:Action/Attribute:status+' => 'Estatus',
	'Class:Action/Attribute:status/Value:test' => 'En Pruebas',
	'Class:Action/Attribute:status/Value:test+' => 'En Pruebas',
	'Class:Action/Attribute:status/Value:enabled' => 'Activo',
	'Class:Action/Attribute:status/Value:enabled+' => 'Activo',
	'Class:Action/Attribute:status/Value:disabled' => 'Inactivo',
	'Class:Action/Attribute:status/Value:disabled+' => 'Inactivo',
	'Class:Action/Attribute:trigger_list' => 'Disparadores Relacionados',
	'Class:Action/Attribute:trigger_list+' => 'Disparadores Asociados a esta Acción',
	'Class:Action/Attribute:finalclass' => 'Clase',
	'Class:Action/Attribute:finalclass+' => 'Clase',
));

//
// Class: ActionNotification
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:ActionNotification' => 'Notificación',
	'Class:ActionNotification+' => 'Notificación (resúmen)',
));

//
// Class: ActionEmail
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:ActionEmail' => 'Notificación por Correo Electrónico',
	'Class:ActionEmail+' => 'Notificación por Correo Electrónico',
	'Class:ActionEmail/Attribute:test_recipient' => 'Destinatario de Prueba',
	'Class:ActionEmail/Attribute:test_recipient+' => 'Destinatario en caso que el Estatus sea "En pruebas"',
	'Class:ActionEmail/Attribute:from' => 'Remitente',
	'Class:ActionEmail/Attribute:from+' => 'Será enviando en el encabezado del Correo Electrónico',
	'Class:ActionEmail/Attribute:reply_to' => 'Responder a',
	'Class:ActionEmail/Attribute:reply_to+' => 'Será enviando en el encabezado del Correo Electrónico',
	'Class:ActionEmail/Attribute:to' => 'Para',
	'Class:ActionEmail/Attribute:to+' => 'Destinatario del Correo Electrónico',
	'Class:ActionEmail/Attribute:cc' => 'CC',
	'Class:ActionEmail/Attribute:cc+' => 'Copia al carbón',
	'Class:ActionEmail/Attribute:bcc' => 'CCO',
	'Class:ActionEmail/Attribute:bcc+' => 'Copia al carbón oculta',
	'Class:ActionEmail/Attribute:subject' => 'Asunto',
	'Class:ActionEmail/Attribute:subject+' => 'Asunto del Correo Electrónico',
	'Class:ActionEmail/Attribute:body' => 'Cuerpo',
	'Class:ActionEmail/Attribute:body+' => 'Contenido del correo electronico',
	'Class:ActionEmail/Attribute:importance' => 'Importancia',
	'Class:ActionEmail/Attribute:importance+' => 'Bandera de importancia',
	'Class:ActionEmail/Attribute:importance/Value:low' => 'Baja',
	'Class:ActionEmail/Attribute:importance/Value:low+' => 'baja',
	'Class:ActionEmail/Attribute:importance/Value:normal' => 'Normal',
	'Class:ActionEmail/Attribute:importance/Value:normal+' => 'Normal',
	'Class:ActionEmail/Attribute:importance/Value:high' => 'Alta',
	'Class:ActionEmail/Attribute:importance/Value:high+' => 'Alta',
));

//
// Class: Trigger
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Trigger' => 'Disparador',
	'Class:Trigger+' => 'Disparador',
	'Class:Trigger/Attribute:description' => 'Descripción',
	'Class:Trigger/Attribute:description+' => 'Descripción',
	'Class:Trigger/Attribute:action_list' => 'Acciones',
	'Class:Trigger/Attribute:action_list+' => 'Acciones realizadas cuando se activó el disparador',
	'Class:Trigger/Attribute:finalclass' => 'Clase',
	'Class:Trigger/Attribute:finalclass+' => 'Clase',
));

//
// Class: TriggerOnObject
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:TriggerOnObject' => 'Disparador (Depende de la clase)',
	'Class:TriggerOnObject+' => 'Disparador en una clase de objeto dada',
	'Class:TriggerOnObject/Attribute:target_class' => 'Clase destino',
	'Class:TriggerOnObject/Attribute:target_class+' => 'Clase destino',
));

//
// Class: TriggerOnPortalUpdate
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:TriggerOnPortalUpdate' => 'Disparador (cuando se actualiza desde el portal)',
	'Class:TriggerOnPortalUpdate+' => 'Disparador cuando un usuario actualiza desde el portal',
));

//
// Class: TriggerOnStateChange
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:TriggerOnStateChange' => 'Disparador (en cambio de estado)',
	'Class:TriggerOnStateChange+' => 'Disparador en cambio de estado de objeto',
	'Class:TriggerOnStateChange/Attribute:state' => 'Estado',
	'Class:TriggerOnStateChange/Attribute:state+' => 'Estado',
));

//
// Class: TriggerOnStateEnter
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:TriggerOnStateEnter' => 'Disparador (entrando a un estado)',
	'Class:TriggerOnStateEnter+' => 'Disparador en cambio de estado de objeto - entrando',
));

//
// Class: TriggerOnStateLeave
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:TriggerOnStateLeave' => 'Disparador (saliendo de un estado)',
	'Class:TriggerOnStateLeave+' => 'Disparador en cambio de estado de objeto - saliendo',
));

//
// Class: TriggerOnObjectCreate
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:TriggerOnObjectCreate' => 'Disparador (creación de objeto)',
	'Class:TriggerOnObjectCreate+' => 'Disparador en la creación de objeto (hija de clase) de una clase dada',
));

//
// Class: TriggerOnThresholdReached
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:TriggerOnThresholdReached' => 'Disparador (en umbral)',
	'Class:TriggerOnThresholdReached+' => 'Disparador en umbral Stop-Watch alcanzado',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code' => 'Detener watch',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code+' => 'Detener watch',
	'Class:TriggerOnThresholdReached/Attribute:threshold_index' => 'Umbral',
	'Class:TriggerOnThresholdReached/Attribute:threshold_index+' => 'Umbral',
));

//
// Class: lnkTriggerAction
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkTriggerAction' => 'Relación Acción y Disparador',
	'Class:lnkTriggerAction+' => 'Relación Acción y Disparador',
	'Class:lnkTriggerAction/Attribute:action_id' => 'Acción',
	'Class:lnkTriggerAction/Attribute:action_id+' => 'Acción a ser realizada',
	'Class:lnkTriggerAction/Attribute:action_name' => 'Acción',
	'Class:lnkTriggerAction/Attribute:action_name+' => 'Acción',
	'Class:lnkTriggerAction/Attribute:trigger_id' => 'Disparador',
	'Class:lnkTriggerAction/Attribute:trigger_id+' => 'Disparador',
	'Class:lnkTriggerAction/Attribute:trigger_name' => 'Disparador',
	'Class:lnkTriggerAction/Attribute:trigger_name+' => 'Disparador',
	'Class:lnkTriggerAction/Attribute:order' => 'Orden',
	'Class:lnkTriggerAction/Attribute:order+' => 'Orden de realización de acciones',
));

//
// Synchro Data Source
//
Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:SynchroDataSource/Attribute:name' => 'Nombre',
	'Class:SynchroDataSource/Attribute:name+' => 'Nombre de la Fuente de Datos',
	'Class:SynchroDataSource/Attribute:description' => 'Descripción',
	'Class:SynchroDataSource/Attribute:status' => 'Estatus', //TODO: enum values
	'Class:SynchroDataSource/Attribute:scope_class' => 'Clase',
	'Class:SynchroDataSource/Attribute:user_id' => 'Usuario',  
	'Class:SynchroDataSource/Attribute:notify_contact_id' => 'Contacto a Notificar',
	'Class:SynchroDataSource/Attribute:notify_contact_id+' => 'Contact a Notificar en Caso de Error',
	'Class:SynchroDataSource/Attribute:url_icon' => 'Icono de URL',
	'Class:SynchroDataSource/Attribute:url_icon+' => 'URL a pequeña imagen representando la aplicación con la que iTop se sincronizará',
	'Class:SynchroDataSource/Attribute:url_application' => 'URL a la Aplicación',
	'Class:SynchroDataSource/Attribute:url_application+' => 'URL a la Aplicación (Si aplica). Posibles lugares: $this->attribute$ y $replica->primary_key$',
	'Class:SynchroDataSource/Attribute:reconciliation_policy' => 'Política de Reconciliación', //TODO enum values
	'Class:SynchroDataSource/Attribute:full_load_periodicity' => 'Interválo de Carga',
	'Class:SynchroDataSource/Attribute:full_load_periodicity+' => 'Una recarga completa de datos debe ocurrir en el intervalo especificado aquí',
	'Class:SynchroDataSource/Attribute:action_on_zero' => 'Accíón con NADA',
	'Class:SynchroDataSource/Attribute:action_on_zero+' => 'Acción cuando la búsquda no regresa datos',
	'Class:SynchroDataSource/Attribute:action_on_one' => 'Acción con UNO',
	'Class:SynchroDataSource/Attribute:action_on_one+' => 'Acción cuando la búsqueda regresa solo un objeto',
	'Class:SynchroDataSource/Attribute:action_on_multiple' => 'Acción con MUCHOS',
	'Class:SynchroDataSource/Attribute:action_on_multiple+' => 'cción cuando la búsqueda regresa más de un objeto',
	'Class:SynchroDataSource/Attribute:user_delete_policy' => 'Usuarios Permitidos',
	'Class:SynchroDataSource/Attribute:user_delete_policy+' => 'Usuarios Permitidos',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:never' => 'Nadie',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:depends' => 'Sólo Administradores',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:always' => 'Usuarios Permitidos',
	'Class:SynchroDataSource/Attribute:delete_policy_update' => 'Reglas de Actualización',
	'Class:SynchroDataSource/Attribute:delete_policy_update+' => 'Sintáxis: field_name:value; ...',
	'Class:SynchroDataSource/Attribute:delete_policy_retention' => 'Retención',
	'Class:SynchroDataSource/Attribute:delete_policy_retention+' => 'Cuanto tiempo un objeto obsoleto es conservado antes de borrarse',
	'Class:SynchroDataSource/Attribute:database_table_name' => 'Tabla de Datos',
	'Class:SynchroDataSource/Attribute:database_table_name+' => 'Dónde se guardan los datos sincronizados. Si se deja vacía, un nombre será asignado automáticamente.',
	'SynchroDataSource:Description' => 'Descripción',
	'SynchroDataSource:Reconciliation' => 'Búsqueda y Reconciliación',
	'SynchroDataSource:Deletion' => 'Reglas de Borrado',
	'SynchroDataSource:Status' => 'Estatus',
	'SynchroDataSource:Information' => 'Información',
	'SynchroDataSource:Definition' => 'Definición',
	'Core:SynchroAttributes' => 'Atributos',
	'Core:SynchroStatus' => 'Estatus',
	'Core:Synchro:ErrorsLabel' => 'Errores',	
	'Core:Synchro:CreatedLabel' => 'Creado',
	'Core:Synchro:ModifiedLabel' => 'Modificado',
	'Core:Synchro:UnchangedLabel' => 'Sin Cambio',
	'Core:Synchro:ReconciledErrorsLabel' => 'Errores',
	'Core:Synchro:ReconciledLabel' => 'Reconciliado',
	'Core:Synchro:ReconciledNewLabel' => 'Creado',
	'Core:SynchroReconcile:Yes' => 'Si',
	'Core:SynchroReconcile:No' => 'No',
	'Core:SynchroUpdate:Yes' => 'Si',
	'Core:SynchroUpdate:No' => 'No',
	'Core:Synchro:LastestStatus' => 'Último Estatus',
	'Core:Synchro:History' => 'Historia de Sincronización',
	'Core:Synchro:NeverRun' => 'Esta Sincronización no ha sido ejecutada. No hay bitácora todavía.',
	'Core:Synchro:SynchroEndedOn_Date' => 'La última Sincronización terminó en %1$s.',
	'Core:Synchro:SynchroRunningStartedOn_Date' => 'La Sincronización iniciada en %1$s está todavía en ejecución.',
	'Menu:DataSources' => 'Fuentes de Datos Sincronizables', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataSources+' => 'Fuentes de Datos Sincronizables', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Core:Synchro:label_repl_ignored' => 'Ignorados (%1$s)',
	'Core:Synchro:label_repl_disappeared' => 'Desaparecieron (%1$s)',
	'Core:Synchro:label_repl_existing' => 'Existen (%1$s)',
	'Core:Synchro:label_repl_new' => 'Nuevos (%1$s)',
	'Core:Synchro:label_obj_deleted' => 'Borrados (%1$s)',
	'Core:Synchro:label_obj_obsoleted' => 'Obsoletose (%1$s)',
	'Core:Synchro:label_obj_disappeared_errors' => 'Errors (%1$s)',
	'Core:Synchro:label_obj_disappeared_no_action' => 'Sin Acción (%1$s)',
	'Core:Synchro:label_obj_unchanged' => 'Sin Cambio (%1$s)',
	'Core:Synchro:label_obj_updated' => 'Actualizados (%1$s)', 
	'Core:Synchro:label_obj_updated_errors' => 'Errores (%1$s)',
	'Core:Synchro:label_obj_new_unchanged' => 'Sin Cambios (%1$s)',
	'Core:Synchro:label_obj_new_updated' => 'Actualizados (%1$s)',
	'Core:Synchro:label_obj_created' => 'Creados (%1$s)',
	'Core:Synchro:label_obj_new_errors' => 'Errores (%1$s)',
	'Core:Synchro:History' => 'Historia de Sincronización',
	'Core:SynchroLogTitle' => '%1$s - %2$s',
	'Core:Synchro:Nb_Replica' => 'Replica Procesada: %1$s',
	'Core:Synchro:Nb_Class:Objects' => '%1$s: %2$s',
	'Class:SynchroDataSource/Error:AtLeastOneReconciliationKeyMustBeSpecified' => 'Al menos una llave de reconciliación debe ser especificada, o la política de reconciliación deberá usar la llave primaria',
  'Class:SynchroDataSource/Error:DeleteRetentionDurationMustBeSpecified' => 'Un periodo de retención debe ser especificdo, debido a que los objetos no son borrados despues de ser marcados como obsoletos',
  'Class:SynchroDataSource/Error:DeletePolicyUpdateMustBeSpecified' => 'Objetos obsoletos serán actualizados, pero no se especificó la actualización,',
  'Class:SynchroDataSource/Error:DataTableAlreadyExists' => 'La Tabla %1$s ya existe en la base de datos. Por favor use otro nombre para la tabla de sincronización de datos',
  'Core:SynchroReplica:PublicData' => 'Datos Públicos',
	'Core:SynchroReplica:PrivateDetails' => 'Detalles Privados',
	'Core:SynchroReplica:BackToDataSource' => 'Regresar a la Fuente de Datos Sincronizable %1$s',
	'Core:SynchroReplica:ListOfReplicas' => 'Lista de Replicas',
	'Core:SynchroAttExtKey:ReconciliationById' => 'Id (Llave Primaria)',
	'Core:SynchroAtt:attcode' => 'Atributo',
	'Core:SynchroAtt:attcode+' => 'Campo de este Objeto',
	'Core:SynchroAtt:reconciliation' => '¿Reconciliación?',
	'Core:SynchroAtt:reconciliation+' => 'Usado para Búsquedas',
	'Core:SynchroAtt:update' => '¿Actualizar?',
	'Core:SynchroAtt:update+' => 'Usado para Actualizar el Objeto',
	'Core:SynchroAtt:update_policy' => 'Política de Actualización',
	'Core:SynchroAtt:update_policy+' => 'Política de Actualización',
	'Core:SynchroAtt:reconciliation_attcode' => 'Llave de Reconciliación',
	'Core:SynchroAtt:reconciliation_attcode+' => 'Llave de Reconciliación',
	'Core:SyncDataExchangeComment' => '(Datos Sincronizados)',
	'Core:Synchro:ListOfDataSources' => 'Lista de Fuentes de Datos:',
	'Core:Synchro:LastSynchro' => 'Última Sincronización:',
	'Core:Synchro:ThisObjectIsSynchronized' => 'Este Objeto es Sincronizado con una Fuente de Datos Externa',
	'Core:Synchro:TheObjectWasCreatedBy_Source' => 'El Objeto fué <b>creado</b> por la Fuente de Datos Externa %1$s',
	'Core:Synchro:TheObjectCanBeDeletedBy_Source' => 'El Objeto <b>puede ser borrado</b> por la Fuente de Datos Externa %1$s',
	'Core:Synchro:TheObjectCannotBeDeletedByUser_Source' => 'Usted <b>No puede borrar el Objeto</b> porque pertenece a la Fuente de Datos Externa %1$s',
  'TitleSynchroExecution' => 'Ejecución de la Sincronización',
	'Class:SynchroDataSource:DataTable' => 'Tabla de Base de Datos: %1$s',
	'Core:SyncDataSourceObsolete' => 'La Fuente de Datos está marcada como Obsoleta.  Operación Cancelada.',
	'Core:SyncDataSourceAccessRestriction' => 'Sólo Administradores o el usuario especificado en la fuente de datos pueden ejecutar esta operación.  Operación Cancelada.',
  'Core:SyncTooManyMissingReplicas' => 'Todos los registros no se han modificado por un tiempo (Todos los objetos pueden ser borrados).  Por favor verifique que el proceso que escribe a la tabla de sincronización esté todavía corriendo.  Operación Cancelada.',
	'Core:SyncSplitModeCLIOnly' => 'La Sincronización se puede ejecutar solo en partes si se ejecuta en modo CLI',
	'Core:Synchro:ListReplicas_AllReplicas_Errors_Warnings' => '%1$s Replicas, %2$s Errores, %3$s Advertencias.',
	'Core:SynchroReplica:TargetObject' => 'Objetos Sincronizados: %1$s',
	'Class:AsyncSendEmail' => 'Correo Electrónico (asíncrono)',
	'Class:AsyncSendEmail/Attribute:to' => 'Para',
	'Class:AsyncSendEmail/Attribute:subject' => 'Asunto',
	'Class:AsyncSendEmail/Attribute:body' => 'Cuerpo',
	'Class:AsyncSendEmail/Attribute:header' => 'Encabezado',
	'Class:CMDBChangeOpSetAttributeOneWayPassword' => 'Contrase&ntilde;a Encriptada',
	'Class:CMDBChangeOpSetAttributeOneWayPassword/Attribute:prev_pwd' => 'Valor Anterior',
	'Class:CMDBChangeOpSetAttributeEncrypted' => 'Campo Encriptado',
	'Class:CMDBChangeOpSetAttributeEncrypted/Attribute:prevstring' => 'Valor Anterior',
	'Class:CMDBChangeOpSetAttributeCaseLog' => 'Bitácora de Caso',
	'Class:CMDBChangeOpSetAttributeCaseLog/Attribute:lastentry' => 'Última Entrada',
	'Class:SynchroDataSource' => 'Fuente de Datos Sincronizable',
	'Class:SynchroDataSource/Attribute:status/Value:implementation' => 'No Productivo',
	'Class:SynchroDataSource/Attribute:status/Value:obsolete' => 'Obsoleto',
	'Class:SynchroDataSource/Attribute:status/Value:production' => 'Productivo',
	'Class:SynchroDataSource/Attribute:scope_restriction' => 'Alcance de la restricción',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_attributes' => 'Usar los Atributos',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_primary_key' => 'Usar el Campo Primary_Key',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:create' => 'Crear',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:error' => 'Error',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:error' => 'Error',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:update' => 'Actualizar',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:create' => 'Crear',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:error' => 'Error',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:take_first' => 'Tomar el primero(¿random?)',
	'Class:SynchroDataSource/Attribute:delete_policy' => 'Política de Borrado',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:delete' => 'Borrar',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:ignore' => 'Ignorar',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update' => 'Actualizar',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update_then_delete' => 'Actualizar y después Borrar',
	'Class:SynchroDataSource/Attribute:attribute_list' => 'Lista de Atributos',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:administrators' => 'Sólo Administradores',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:everybody' => 'Todos tienen permitido borrar esos objetos',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:nobody' => 'Ninguno',
	'Class:SynchroAttribute' => 'Atributos de Sincronización',
	'Class:SynchroAttribute/Attribute:sync_source_id' => 'Fuente de Datos Sincronizable',
	'Class:SynchroAttribute/Attribute:attcode' => 'Código de Atributo',
	'Class:SynchroAttribute/Attribute:update' => 'Actualizar',
	'Class:SynchroAttribute/Attribute:reconcile' => 'Reconciliar',
	'Class:SynchroAttribute/Attribute:update_policy' => 'Política de Actualización',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_locked' => 'Bloqueado',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_unlocked' => 'Desbloqueado',
	'Class:SynchroAttribute/Attribute:update_policy/Value:write_if_empty' => 'Inicializar si está vacío',
	'Class:SynchroAttribute/Attribute:finalclass' => 'Clase',
	'Class:SynchroAttExtKey' => 'Atributo de Sincronización (ExtKey)',
	'Class:SynchroAttExtKey/Attribute:reconciliation_attcode' => 'Atributo de Reconciliación',
	'Class:SynchroAttLinkSet' => 'Atributo de Sincronización (Linkset)',
	'Class:SynchroAttLinkSet/Attribute:row_separator' => 'Separador de Renglones',
	'Class:SynchroAttLinkSet/Attribute:attribute_separator' => 'Separador de Atributos',
	'Class:SynchroLog' => 'Bitácora de Sincronización',
	'Class:SynchroLog/Attribute:sync_source_id' => 'Fuente de Datos Sincronizable',
	'Class:SynchroLog/Attribute:start_date' => 'Fecha Inicio',
	'Class:SynchroLog/Attribute:end_date' => 'Fecha Fin',
	'Class:SynchroLog/Attribute:status' => 'Estatus',
	'Class:SynchroLog/Attribute:status/Value:completed' => 'Completado',
	'Class:SynchroLog/Attribute:status/Value:error' => 'Error',
	'Class:SynchroLog/Attribute:status/Value:running' => 'Continua en Ejecución',
	'Class:SynchroLog/Attribute:stats_nb_replica_seen' => 'Num. Replica Vistos',
	'Class:SynchroLog/Attribute:stats_nb_replica_total' => 'Num. Replica Total',
	'Class:SynchroLog/Attribute:stats_nb_obj_deleted' => 'Num. Objetos Borrados',
	'Class:SynchroLog/Attribute:stats_nb_obj_deleted_errors' => 'Num. de Errores durante el Borrado',
	'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted' => 'Num. Objetos Obsoletos',
	'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted_errors' => 'Num. de Errores durante la Obsolescencia',
	'Class:SynchroLog/Attribute:stats_nb_obj_created' => 'Num. Objetos Creados',
	'Class:SynchroLog/Attribute:stats_nb_obj_created_errors' => 'Num. Errores durante la Creación',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated' => 'Num. Objetos Actualizados',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated_errors' => 'Num. Errores mientras se Actualizaba',
	'Class:SynchroLog/Attribute:stats_nb_replica_reconciled_errors' => 'Num. de Errores durante Reconciliación',
	'Class:SynchroLog/Attribute:stats_nb_replica_disappeared_no_action' => 'Num. Desapareció Replica',
	'Class:SynchroLog/Attribute:stats_nb_obj_new_updated' => 'Num. Objetos Actualizados',
	'Class:SynchroLog/Attribute:stats_nb_obj_new_unchanged' => 'Num. Objetos Sin Cambio',
	'Class:SynchroLog/Attribute:last_error' => 'Último Error',
	'Class:SynchroLog/Attribute:traces' => 'Trazas',
	'Class:SynchroReplica' => 'Replica de Sincronización',
	'Class:SynchroReplica/Attribute:sync_source_id' => 'Fuente de Datos Sincronizable',
	'Class:SynchroReplica/Attribute:dest_id' => 'Objeto Destino (ID)',
	'Class:SynchroReplica/Attribute:dest_class' => 'Tipo de Destino',
	'Class:SynchroReplica/Attribute:status_last_seen' => 'Última vez Visto',
	'Class:SynchroReplica/Attribute:status' => 'Estatus',
	'Class:SynchroReplica/Attribute:status/Value:modified' => 'Modificado',
	'Class:SynchroReplica/Attribute:status/Value:new' => 'Nuevo',
	'Class:SynchroReplica/Attribute:status/Value:obsolete' => 'Obsoleto',
	'Class:SynchroReplica/Attribute:status/Value:orphan' => 'Huérfano',
	'Class:SynchroReplica/Attribute:status/Value:synchronized' => 'Sincronizado',
	'Class:SynchroReplica/Attribute:status_dest_creator' => '¿Objeto Creado?',
	'Class:SynchroReplica/Attribute:status_last_error' => 'Último Error',
	'Class:SynchroReplica/Attribute:status_last_warning' => 'Advertencias',
	'Class:SynchroReplica/Attribute:info_creation_date' => 'Fecha de Creación',
	'Class:SynchroReplica/Attribute:info_last_modified' => 'Fecha Última Modificación',
	'Class:appUserPreferences' => 'Preferencias de Usuario',
	'Class:appUserPreferences/Attribute:userid' => 'Usuario',
	'Class:appUserPreferences/Attribute:preferences' => 'Preferencias',
	'Core:ExecProcess:Code1' => 'Comando equivocado o comando terminó con errores (ejem. nombre incorrecto de script)',
	'Core:ExecProcess:Code255' => 'PHP Error (parsing, or runtime)',
));

//
// Attribute Duration
//
Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Core:Duration_Seconds'	=> '%1$ds',	
	'Core:Duration_Minutes_Seconds'	=>'%1$dmin %2$ds',	
	'Core:Duration_Hours_Minutes_Seconds' => '%1$dh %2$dmin %3$ds',		
	'Core:Duration_Days_Hours_Minutes_Seconds' => '%1$sd %2$dh %3$dmin %4$ds',		
));

?>