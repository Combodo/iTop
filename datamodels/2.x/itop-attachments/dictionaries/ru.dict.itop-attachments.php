<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 *
 */
/**
 * @author Vladimir Kunin <v.b.kunin@gmail.com>
 *
 */
Dict::Add('RU RU', 'Russian', 'Русский', [
	'Attachment:Max_Go' => '(Максимальный размер файла: %1$s ГБ)',
	'Attachment:Max_Ko' => '(Максимальный размер файла: %1$s кБ)',
	'Attachment:Max_Mo' => '(Максимальный размер файла: %1$s МБ)',
	'Attachments:AddAttachment' => 'Добавить вложение:',
	'Attachments:DeleteBtn' => 'Удалить',
	'Attachments:EmptyTabTitle' => 'Вложения',
	'Attachments:Error:FileTooLarge' => 'Файл слишком велик для загрузки. %1$s',
	'Attachments:Error:UploadedFileEmpty' => 'The received file is empty and cannot be attached.
Either you have pushed an empty file,
or ask your '.ITOP_APPLICATION_SHORT.' administrator if the '.ITOP_APPLICATION_SHORT.' server disk is full.~~',
	'Attachments:FieldsetTitle' => 'Вложения',
	'Attachments:File:Date' => 'Дата',
	'Attachments:File:DownloadsCount' => 'Downloads~~',
	'Attachments:File:MimeType' => 'Тип',
	'Attachments:File:Name' => 'Имя файла',
	'Attachments:File:Size' => 'Размер',
	'Attachments:File:Thumbnail' => 'Предпросмотр',
	'Attachments:File:Uploader' => 'Пользователь',
	'Attachments:History_File_Added' => 'Вложение %1$s добавлено.',
	'Attachments:History_File_Removed' => 'Вложение %1$s удалено.',
	'Attachments:NoAttachment' => 'Нет вложений.',
	'Attachments:PreviewNotAvailable' => 'Предварительный просмотр не доступен для этого типа вложений.',
	'Attachments:Render:Icons' => 'Display as icons~~',
	'Attachments:Render:Table' => 'Display as list~~',
	'Attachments:TabTitle_Count' => 'Вложения (%1$d)',
	'Attachments:UploadNotAllowedOnThisSystem' => 'Загрузка файлов НЕ разрешена в этой системе. За подробностями обратитесь к администратору вашего '.ITOP_APPLICATION_SHORT,
	'Class:Attachment' => 'Вложение',
	'Class:Attachment+' => '',
	'Class:Attachment/Attribute:contact_id' => 'Контакт',
	'Class:Attachment/Attribute:contact_id+' => '',
	'Class:Attachment/Attribute:contents' => 'Содержимое',
	'Class:Attachment/Attribute:contents+' => '',
	'Class:Attachment/Attribute:creation_date' => 'Дата создания',
	'Class:Attachment/Attribute:creation_date+' => '',
	'Class:Attachment/Attribute:expire' => 'Истекает',
	'Class:Attachment/Attribute:expire+' => '',
	'Class:Attachment/Attribute:item_class' => 'Класс объекта',
	'Class:Attachment/Attribute:item_class+' => '',
	'Class:Attachment/Attribute:item_id' => 'Id объекта',
	'Class:Attachment/Attribute:item_id+' => '',
	'Class:Attachment/Attribute:item_org_id' => 'Id организации объекта',
	'Class:Attachment/Attribute:item_org_id+' => '',
	'Class:Attachment/Attribute:temp_id' => 'Временный Id',
	'Class:Attachment/Attribute:temp_id+' => '',
	'Class:Attachment/Attribute:user_id' => 'Пользователь',
	'Class:Attachment/Attribute:user_id+' => '',
	'Class:TriggerOnAttachmentDownload'                       => 'Trigger (on object\'s attachment download)~~',
	'Class:TriggerOnAttachmentDownload+'                      => 'Trigger on object\'s attachment download of [a child class of] the given class~~',
	'UI:Attachments:DropYourFileHint' => 'Drop files anywhere in this area~~',
	'Class:TriggerOnAttachmentCreate'                         => 'Trigger (on object\'s attachment create)~~',
	'Class:TriggerOnAttachmentCreate+'                        => 'Trigger on object\'s attachment create~~',
	'Class:TriggerOnAttachmentCreate/Attribute:file_in_email' => 'Add file in email~~',
	'Class:TriggerOnAttachmentCreate/Attribute:file_in_email+' => 'If checked, the file will be automatically attached to the email when an email action is triggered~~',
	'Class:TriggerOnAttachmentDelete'                         => 'Trigger (on object\'s attachment delete)~~',
	'Class:TriggerOnAttachmentDelete+'                        => 'Trigger on object\'s attachment delete~~',
    'Class:TriggerOnObject:TriggerClassAttachment/ReadOnlyMessage' => 'Trigger on object is not allowed on class Attachment. Please use specific trigger~~',
]);
