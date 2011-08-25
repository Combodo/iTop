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
// Classes in 'core/cmdb'
//////////////////////////////////////////////////////////////////////
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Core:AttributeLinkedSet' => 'Array of objects',
	'Core:AttributeLinkedSet+' => 'Any kind of objects [subclass] of the same class',

	'Core:AttributeLinkedSetIndirect' => 'Array of objects (N-N)',
	'Core:AttributeLinkedSetIndirect+' => 'Any kind of objects [subclass] of the same class',

	'Core:AttributeInteger' => 'Integer',
	'Core:AttributeInteger+' => 'Numeric value (could be negative)',

	'Core:AttributeDecimal' => 'Decimal',
	'Core:AttributeDecimal+' => 'Decimal value (could be negative)',

	'Core:AttributeBoolean' => 'Boolean',
	'Core:AttributeBoolean+' => 'Boolean',

	'Core:AttributeString' => 'String',
	'Core:AttributeString+' => 'Alphanumeric string',

	'Core:AttributeClass' => 'Class',
	'Core:AttributeClass+' => 'Class',

	'Core:AttributeApplicationLanguage' => 'User language',
	'Core:AttributeApplicationLanguage+' => 'Language and country (EN US)',

	'Core:AttributeFinalClass' => 'Class (auto)',
	'Core:AttributeFinalClass+' => 'Real class of the object (automatically created by the core)',

	'Core:AttributePassword' => 'Password',
	'Core:AttributePassword+' => 'Password of an external device',

 	'Core:AttributeEncryptedString' => 'Encrypted string',
	'Core:AttributeEncryptedString+' => 'String encrypted with a local key',

	'Core:AttributeText' => 'Text',
	'Core:AttributeText+' => 'Multiline character string',

	'Core:AttributeHTML' => 'HTML',
	'Core:AttributeHTML+' => 'HTML string',

	'Core:AttributeEmailAddress' => 'Email address',
	'Core:AttributeEmailAddress+' => 'Email address',

	'Core:AttributeIPAddress' => 'IP address',
	'Core:AttributeIPAddress+' => 'IP address',

	'Core:AttributeOQL' => 'OQL',
	'Core:AttributeOQL+' => 'Object Query Langage expression',

	'Core:AttributeEnum' => 'Enum',
	'Core:AttributeEnum+' => 'List of predefined alphanumeric strings',

	'Core:AttributeTemplateString' => 'Template string',
	'Core:AttributeTemplateString+' => 'String containing placeholders',

	'Core:AttributeTemplateText' => 'Template text',
	'Core:AttributeTemplateText+' => 'Text containing placeholders',

	'Core:AttributeTemplateHTML' => 'Template HTML',
	'Core:AttributeTemplateHTML+' => 'HTML containing placeholders',

	'Core:AttributeDateTime' => 'Date/time',
	'Core:AttributeDateTime+' => 'Date and time (year-month-day hh:mm:ss)',

	'Core:AttributeDate' => 'Date',
	'Core:AttributeDate+' => 'Date (year-month-day)',

	'Core:AttributeDeadline' => 'Deadline',
	'Core:AttributeDeadline+' => 'Date, displayed relatively to the current time',

	'Core:AttributeExternalKey' => 'External key',
	'Core:AttributeExternalKey+' => 'External (or foreign) key',

	'Core:AttributeExternalField' => 'External field',
	'Core:AttributeExternalField+' => 'Field mapped from an external key',

	'Core:AttributeURL' => 'URL',
	'Core:AttributeURL+' => 'Absolute or relative URL as a text string',

	'Core:AttributeBlob' => 'Blob',
	'Core:AttributeBlob+' => 'Any binary content (document)',

	'Core:AttributeOneWayPassword' => 'One way password',
	'Core:AttributeOneWayPassword+' => 'One way encrypted (hashed) password',

	'Core:AttributeTable' => 'Table',
	'Core:AttributeTable+' => 'Indexed array having two dimensions',

	'Core:AttributePropertySet' => 'Properties',
	'Core:AttributePropertySet+' => 'List of untyped properties (name and value)',
));


//
// Class: CMDBChange
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:CMDBChange' => 'Modificación',
	'Class:CMDBChange+' => 'Seguimiento a Modificaciones',
	'Class:CMDBChange/Attribute:date' => 'fecha',
	'Class:CMDBChange/Attribute:date+' => 'fecha y hora en que los cambios fueron registrados',
	'Class:CMDBChange/Attribute:userinfo' => 'misc. info',
	'Class:CMDBChange/Attribute:userinfo+' => 'información definida por el solicitante',
));

//
// Class: CMDBChangeOp
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:CMDBChangeOp' => 'Operacion de Modificación',
	'Class:CMDBChangeOp+' => 'Seguimiento Operaciones de Modificación',
	'Class:CMDBChangeOp/Attribute:change' => 'modificación',
	'Class:CMDBChangeOp/Attribute:change+' => 'modificación',
	'Class:CMDBChangeOp/Attribute:date' => 'fecha',
	'Class:CMDBChangeOp/Attribute:date+' => 'fecha y hora de la modificacón',
	'Class:CMDBChangeOp/Attribute:userinfo' => 'usuario',
	'Class:CMDBChangeOp/Attribute:userinfo+' => 'quien hizo este cambio',
	'Class:CMDBChangeOp/Attribute:objclass' => 'clase de objeto',
	'Class:CMDBChangeOp/Attribute:objclass+' => 'clase de objeto',
	'Class:CMDBChangeOp/Attribute:objkey' => 'id de objeto',
	'Class:CMDBChangeOp/Attribute:objkey+' => 'id de objeto',
	'Class:CMDBChangeOp/Attribute:finalclass' => 'tipo',
	'Class:CMDBChangeOp/Attribute:finalclass+' => '',
));

//
// Class: CMDBChangeOpCreate
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:CMDBChangeOpCreate' => 'creación de objeto',
	'Class:CMDBChangeOpCreate+' => 'Seguimiento de creación de objeto',
));

//
// Class: CMDBChangeOpDelete
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:CMDBChangeOpDelete' => 'borrado de objeto',
	'Class:CMDBChangeOpDelete+' => 'Seguimiento de borrado de objeto',
));

//
// Class: CMDBChangeOpSetAttribute
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:CMDBChangeOpSetAttribute' => 'modificación de objeto',
	'Class:CMDBChangeOpSetAttribute+' => 'Seguimiento de modificacion de propiedades de objeto',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode' => 'Atributo',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode+' => 'código de la propiedad modificada',
));

//
// Class: CMDBChangeOpSetAttributeScalar
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:CMDBChangeOpSetAttributeScalar' => 'modificación de propiedad',
	'Class:CMDBChangeOpSetAttributeScalar+' => 'Seguimiento de modificación de propiedades escalares del objeto',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue' => 'Valor anterior',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue+' => 'valor anterior del atributo',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue' => 'Nuevo valor',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue+' => 'nuevo valor del atributo',
));
// Used by CMDBChangeOp... & derived classes
Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Change:AttName_SetTo_NewValue_PreviousValue_OldValue' => '%1$s modificado en %2$s (valor anterior: %3$s)',
	'Change:AttName_SetTo' => '%1$s modificado en %2$s',
	'Change:Text_AppendedTo_AttName' => '%1$s añadido a %2$s',
	'Change:AttName_Changed_PreviousValue_OldValue' => '%1$s modificado, valor anterior: %2$s',
	'Change:AttName_Changed' => '%1$s modificado',
));

//
// Class: CMDBChangeOpSetAttributeBlob
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:CMDBChangeOpSetAttributeBlob' => 'modificación de datos',
	'Class:CMDBChangeOpSetAttributeBlob+' => 'seguimiento de modificación de datos',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata' => 'Dato anterior',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata+' => 'contenido anterior del atributo',
));

//
// Class: CMDBChangeOpSetAttributeText
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:CMDBChangeOpSetAttributeText' => 'modificación de texto',
	'Class:CMDBChangeOpSetAttributeText+' => 'seguimiento de modificación de texto',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata' => 'Dato anterior',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata+' => 'contenido anterior del atributo',
));

//
// Class: Event
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Event' => 'Bitacora de Evento',
	'Class:Event+' => 'evento interno de aplicación',
	'Class:Event/Attribute:message' => 'mensaje',
	'Class:Event/Attribute:message+' => 'corta descripción del evento',
	'Class:Event/Attribute:date' => 'fecha',
	'Class:Event/Attribute:date+' => 'fecha y hora en que las modificaciones fueron registradas',
	'Class:Event/Attribute:userinfo' => 'información de usuario',
	'Class:Event/Attribute:userinfo+' => 'indentificación de la actividad que realizaba el usuario durante la cual se disparó este evento',
	'Class:Event/Attribute:finalclass' => 'tipo',
	'Class:Event/Attribute:finalclass+' => '',
));

//
// Class: EventNotification
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:EventNotification' => 'Notificación de evento',
	'Class:EventNotification+' => 'Seguimiento de notificación enviada',
	'Class:EventNotification/Attribute:trigger_id' => 'Disparador',
	'Class:EventNotification/Attribute:trigger_id+' => 'cuenta de usuario',
	'Class:EventNotification/Attribute:action_id' => 'usuario',
	'Class:EventNotification/Attribute:action_id+' => 'cuenta de usuario',
	'Class:EventNotification/Attribute:object_id' => 'Id de Objeto',
	'Class:EventNotification/Attribute:object_id+' => 'id de objeto (¿clase definida por el disparador?)',
));

//
// Class: EventNotificationEmail
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:EventNotificationEmail' => 'Emision de correo electrónico de evento',
	'Class:EventNotificationEmail+' => 'Evidencia de correo electrónico enviado',
	'Class:EventNotificationEmail/Attribute:to' => 'Destinatario',
	'Class:EventNotificationEmail/Attribute:to+' => 'Destinatario',
	'Class:EventNotificationEmail/Attribute:cc' => 'C.C',
	'Class:EventNotificationEmail/Attribute:cc+' => 'C.C',
	'Class:EventNotificationEmail/Attribute:bcc' => 'C.C.O',
	'Class:EventNotificationEmail/Attribute:bcc+' => 'C.C.O',
	'Class:EventNotificationEmail/Attribute:from' => 'Remitente',
	'Class:EventNotificationEmail/Attribute:from+' => 'Remitente del mensaje',
	'Class:EventNotificationEmail/Attribute:subject' => 'Asunto',
	'Class:EventNotificationEmail/Attribute:subject+' => 'Asunto',
	'Class:EventNotificationEmail/Attribute:body' => 'Cuerpo del mensaje',
	'Class:EventNotificationEmail/Attribute:body+' => 'Cuerpo del mensaje',
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
	'Class:EventWebService' => 'Evento de servicio Web',
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
// Class: Action
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Action' => 'Custom Action',
	'Class:Action+' => 'Acción definida por el usuario',
	'Class:Action/Attribute:name' => 'Nombre',
	'Class:Action/Attribute:name+' => '',
	'Class:Action/Attribute:description' => 'Descripción',
	'Class:Action/Attribute:description+' => '',
	'Class:Action/Attribute:status' => 'Estado',
	'Class:Action/Attribute:status+' => 'En produccion o ?',
//The following value is linked with 'Class:ActionEmail/Attribute:test_recipient+' => ?
	'Class:Action/Attribute:status/Value:test' => 'En pruebas',
	'Class:Action/Attribute:status/Value:test+' => 'En pruebas',
	'Class:Action/Attribute:status/Value:enabled' => 'En producción',
	'Class:Action/Attribute:status/Value:enabled+' => 'En producción',
	'Class:Action/Attribute:status/Value:disabled' => 'Inactivo',
	'Class:Action/Attribute:status/Value:disabled+' => 'Inactivo',
	'Class:Action/Attribute:trigger_list' => 'Disparadores relacionados',
	'Class:Action/Attribute:trigger_list+' => 'Disparadores asociados a esta acción',
	'Class:Action/Attribute:finalclass' => 'Tipo',
	'Class:Action/Attribute:finalclass+' => '',
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
	'Class:ActionEmail' => 'Notificación por correo electrónico',
	'Class:ActionEmail+' => '',
	'Class:ActionEmail/Attribute:test_recipient' => 'Destinatario de prueba',
	'Class:ActionEmail/Attribute:test_recipient+' => 'Destinatario en caso que el estado sea "En pruebas"',
	'Class:ActionEmail/Attribute:from' => 'Remitente',
	'Class:ActionEmail/Attribute:from+' => 'Será enviando en el encabezado del correo electrónico',
	'Class:ActionEmail/Attribute:reply_to' => 'Responder a',
	'Class:ActionEmail/Attribute:reply_to+' => 'Será enviando en el encabezado del correo electrónico',
	'Class:ActionEmail/Attribute:to' => 'Destinatario',
	'Class:ActionEmail/Attribute:to+' => 'Destinatario del correo electrónico',
	'Class:ActionEmail/Attribute:cc' => 'C.C',
	'Class:ActionEmail/Attribute:cc+' => 'Copia al carbón',
	'Class:ActionEmail/Attribute:bcc' => 'C.C.O',
	'Class:ActionEmail/Attribute:bcc+' => 'Copia al carbón oculta',
	'Class:ActionEmail/Attribute:subject' => 'asunto',
	'Class:ActionEmail/Attribute:subject+' => 'Asunto del correo electrónico',
	'Class:ActionEmail/Attribute:body' => 'cuerpo',
	'Class:ActionEmail/Attribute:body+' => 'Contenido del correo electronico',
	'Class:ActionEmail/Attribute:importance' => 'importancia',
	'Class:ActionEmail/Attribute:importance+' => 'Bandera de importancia',
	'Class:ActionEmail/Attribute:importance/Value:low' => 'baja',
	'Class:ActionEmail/Attribute:importance/Value:low+' => 'baja',
	'Class:ActionEmail/Attribute:importance/Value:normal' => 'normal',
	'Class:ActionEmail/Attribute:importance/Value:normal+' => 'normal',
	'Class:ActionEmail/Attribute:importance/Value:high' => 'alta',
	'Class:ActionEmail/Attribute:importance/Value:high+' => 'alta',
));

//
// Class: Trigger
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Trigger' => 'Disparador',
	'Class:Trigger+' => 'Custom event handler',
	'Class:Trigger/Attribute:description' => 'Descripción',
	'Class:Trigger/Attribute:description+' => 'descripción en una línea',
	'Class:Trigger/Attribute:action_list' => 'Acciones disparadas',
	'Class:Trigger/Attribute:action_list+' => 'Acciones realizadas cuando se activó el disparador',
	'Class:Trigger/Attribute:finalclass' => 'Tipo',
	'Class:Trigger/Attribute:finalclass+' => '',
));

//
// Class: TriggerOnObject
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:TriggerOnObject' => 'Disparador (Depende de la clase)',
	'Class:TriggerOnObject+' => 'Disparador en una clase de objeto dada',
	'Class:TriggerOnObject/Attribute:target_class' => 'Clase destino',
	'Class:TriggerOnObject/Attribute:target_class+' => '',
));

//
// Class: TriggerOnStateChange
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:TriggerOnStateChange' => 'Disparador (en cambio de estado)',
	'Class:TriggerOnStateChange+' => 'Disparador en cambio de estado de objeto',
	'Class:TriggerOnStateChange/Attribute:state' => 'Estado',
	'Class:TriggerOnStateChange/Attribute:state+' => '',
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
// Class: lnkTriggerAction
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkTriggerAction' => 'Acción/Disparador',
	'Class:lnkTriggerAction+' => 'Asociación entre un disparador y una acción',
	'Class:lnkTriggerAction/Attribute:action_id' => 'Acción',
	'Class:lnkTriggerAction/Attribute:action_id+' => 'Acción a ser realizada',
	'Class:lnkTriggerAction/Attribute:action_name' => 'Acción',
	'Class:lnkTriggerAction/Attribute:action_name+' => '',
	'Class:lnkTriggerAction/Attribute:trigger_id' => 'Disparador',
	'Class:lnkTriggerAction/Attribute:trigger_id+' => '',
	'Class:lnkTriggerAction/Attribute:trigger_name' => 'Disparador',
	'Class:lnkTriggerAction/Attribute:trigger_name+' => '',
	'Class:lnkTriggerAction/Attribute:order' => 'Orden',
	'Class:lnkTriggerAction/Attribute:order+' => 'Orden de realización de acciones',
));
?>
