<?php
/**
 * Spanish Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * @author Miguel Turrubiates <miguel_tf@yahoo.com>
 * @notas       Utilizar codificación UTF-8 para mostrar acentos y otros caracteres especiales 
 */
Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Attachments:TabTitle_Count' => 'Anexos (%1$d)',
	'Attachments:EmptyTabTitle' => 'Anexos',
	'Attachments:FieldsetTitle' => 'Anexos',
	'Attachments:DeleteBtn' => 'Borrar',
	'Attachments:History_File_Added' => 'Anexo %1$s agregado.',
	'Attachments:History_File_Removed' => 'Anexo %1$s removido.',
	'Attachments:AddAttachment' => 'Agregar Anexo: ',
	'Attachments:UploadNotAllowedOnThisSystem' => 'La carga de archivos NO está permitida en este sistema.',
	'Attachment:Max_Go' => '(Tamaño Máximo de Archivo: %1$s Gb)',
	'Attachment:Max_Mo' => '(Tamaño Máximo de Archivo: %1$s Mb)',
	'Attachment:Max_Ko' => '(Tamaño Máximo de Archivo: %1$s Kb)',
	'Attachments:NoAttachment' => 'No hay Anexo. ',
	'Attachments:PreviewNotAvailable' => 'Vista preliminar no disponible para este tipo de Anexo.',
	'Attachments:Error:FileTooLarge' => 'El archivo es demasiado grande para ser cargado. %1$s',
	'Attachments:Error:UploadedFileEmpty' => 'El archivo recibido está vacío y no puede ser anexado.
Puede ser que haya enviado un archivo vació,
o pregunte al administador de iTop si el servidor que ha quedado sin espacio en disco.',
	'Attachments:Render:Icons' => 'Desplegar como icono',
	'Attachments:Render:Table' => 'Desplegar como lista',
	'UI:Attachments:DropYourFileHint' => 'Arrastre los archivos en cualquier lugar de esta área',
));

//
// Class: Attachment
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Attachment' => 'Anexo',
	'Class:Attachment+' => 'Anexo',
	'Class:Attachment/Attribute:expire' => 'Expira',
	'Class:Attachment/Attribute:expire+' => '',
	'Class:Attachment/Attribute:temp_id' => 'Id Temporal',
	'Class:Attachment/Attribute:temp_id+' => '',
	'Class:Attachment/Attribute:item_class' => 'Clase de Elemento',
	'Class:Attachment/Attribute:item_class+' => '',
	'Class:Attachment/Attribute:item_id' => 'Elemento',
	'Class:Attachment/Attribute:item_id+' => '',
	'Class:Attachment/Attribute:item_org_id' => 'Organización de Elemento',
	'Class:Attachment/Attribute:item_org_id+' => '',
	'Class:Attachment/Attribute:contents' => 'Contenido',
	'Class:Attachment/Attribute:contents+' => '',
));


Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Attachments:File:Thumbnail' => 'Ícono',
	'Attachments:File:Name' => 'Nombre de Archivo',
	'Attachments:File:Date' => 'Fecha de Carga',
	'Attachments:File:Uploader' => 'Cargado por',
	'Attachments:File:Size' => 'Tamaño',
	'Attachments:File:MimeType' => 'Tipo',
	'Attachments:File:DownloadsCount' => 'Descargas',
));
//
// Class: Attachment
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Attachment/Attribute:creation_date' => 'Fecha de Creación',
	'Class:Attachment/Attribute:creation_date+' => '',
	'Class:Attachment/Attribute:user_id' => 'Id del Usuario',
	'Class:Attachment/Attribute:user_id+' => '',
	'Class:Attachment/Attribute:contact_id' => 'Id del Contacto',
	'Class:Attachment/Attribute:contact_id+' => '',
));

//
// Class: TriggerOnAttachmentDownload
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:TriggerOnAttachmentDownload' => 'Disparador (al descargar el archivo adjunto del objeto)',
	'Class:TriggerOnAttachmentDownload+' => 'Disparador al descargar el archivo adjunto del objeto de [una clase secundaria de] la clase dada',
	'Class:TriggerOnAttachmentCreate'                         => 'Trigger (on object\'s attachment create)~~',
	'Class:TriggerOnAttachmentCreate+'                        => 'Trigger on object\'s attachment create~~',
	'Class:TriggerOnAttachmentCreate/Attribute:file_in_email' => 'Add file in email~~',
	'Class:TriggerOnAttachmentCreate/Attribute:file_in_email+' => 'If checked, the file will be automatically attached to the email when an email action is triggered~~',
	'Class:TriggerOnAttachmentDelete'                         => 'Trigger (on object\'s attachment delete)~~',
	'Class:TriggerOnAttachmentDelete+'                        => 'Trigger on object\'s attachment delete~~',
    'Class:TriggerOnObject:TriggerClassAttachment/ReadOnlyMessage' => 'Trigger on object is not allowed on class Attachment. Please use specific trigger~~',
));
