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
	'Attachments:TabTitle_Count' => 'Вложения (%1$d)',
	'Attachments:EmptyTabTitle' => 'Вложения',
	'Attachments:FieldsetTitle' => 'Вложения',
	'Attachments:DeleteBtn' => 'Удалить',
	'Attachments:History_File_Added' => 'Вложение %1$s добавлено.',
	'Attachments:History_File_Removed' => 'Вложение %1$s удалено.',
	'Attachments:AddAttachment' => 'Добавить вложение:',
	'Attachments:UploadNotAllowedOnThisSystem' => 'Загрузка файлов НЕ разрешена в этой системе. За подробностями обратитесь к администратору вашего '.ITOP_APPLICATION_SHORT,
	'Attachment:Max_Go' => '(Максимальный размер файла: %1$s ГБ)',
	'Attachment:Max_Mo' => '(Максимальный размер файла: %1$s МБ)',
	'Attachment:Max_Ko' => '(Максимальный размер файла: %1$s кБ)',
	'Attachments:NoAttachment' => 'Нет вложений.',
	'Attachments:PreviewNotAvailable' => 'Предварительный просмотр не доступен для этого типа вложений.',
	'Attachments:Error:FileTooLarge' => 'Файл слишком велик для загрузки. %1$s',
	'Attachments:Error:UploadedFileEmpty' => 'Полученный файл пуст и не может быть прикреплён.
Либо вы загрузили пустой файл,
либо обратитесь к администратору '.ITOP_APPLICATION_SHORT.' — возможно, диск сервера '.ITOP_APPLICATION_SHORT.' переполнен.',
	'Attachments:Render:Icons' => 'Отображать как иконки',
	'Attachments:Render:Table' => 'Отображать как список',
	'UI:Attachments:DropYourFileHint' => 'Перетащите файлы в любое место этой области',
]);

//
// Class: Attachment
//

Dict::Add('RU RU', 'Russian', 'Русский', [
	'Class:Attachment' => 'Вложение',
	'Class:Attachment+' => '',
	'Class:Attachment/Attribute:expire' => 'Истекает',
	'Class:Attachment/Attribute:expire+' => '',
	'Class:Attachment/Attribute:temp_id' => 'Временный Id',
	'Class:Attachment/Attribute:temp_id+' => '',
	'Class:Attachment/Attribute:item_class' => 'Класс объекта',
	'Class:Attachment/Attribute:item_class+' => '',
	'Class:Attachment/Attribute:item_id' => 'Id объекта',
	'Class:Attachment/Attribute:item_id+' => '',
	'Class:Attachment/Attribute:item_org_id' => 'Id организации объекта',
	'Class:Attachment/Attribute:item_org_id+' => '',
	'Class:Attachment/Attribute:contents' => 'Содержимое',
	'Class:Attachment/Attribute:contents+' => '',
]);

Dict::Add('RU RU', 'Russian', 'Русский', [
	'Attachments:File:Thumbnail' => 'Предпросмотр',
	'Attachments:File:Name' => 'Имя файла',
	'Attachments:File:Date' => 'Дата',
	'Attachments:File:Uploader' => 'Пользователь',
	'Attachments:File:Size' => 'Размер',
	'Attachments:File:MimeType' => 'Тип',
	'Attachments:File:DownloadsCount' => 'Скачиваний',
]);
//
// Class: Attachment
//

Dict::Add('RU RU', 'Russian', 'Русский', [
	'Class:Attachment/Attribute:creation_date' => 'Дата создания',
	'Class:Attachment/Attribute:creation_date+' => '',
	'Class:Attachment/Attribute:user_id' => 'Пользователь',
	'Class:Attachment/Attribute:user_id+' => '',
	'Class:Attachment/Attribute:contact_id' => 'Контакт',
	'Class:Attachment/Attribute:contact_id+' => '',
]);

//
// Class: TriggerOnAttachmentDownload
//

Dict::Add('RU RU', 'Russian', 'Русский', [
	'Class:TriggerOnAttachmentDownload' => 'Триггер (на скачивание вложения объекта)',
	'Class:TriggerOnAttachmentDownload+' => 'Триггер на скачивание вложения объекта заданного класса (или его дочернего класса)',
	'Class:TriggerOnAttachmentCreate'                         => 'Триггер (на создание вложения объекта)',
	'Class:TriggerOnAttachmentCreate+'                        => 'Триггер на создание вложения объекта',
	'Class:TriggerOnAttachmentCreate/Attribute:file_in_email' => 'Добавлять файл в email',
	'Class:TriggerOnAttachmentCreate/Attribute:file_in_email+' => 'Если отмечено, файл будет автоматически прикреплён к письму при срабатывании действия email',
	'Class:TriggerOnAttachmentDelete'                         => 'Триггер (на удаление вложения объекта)',
	'Class:TriggerOnAttachmentDelete+'                        => 'Триггер на удаление вложения объекта',
	'Class:TriggerOnAttachmentDelete/Attribute:file_in_email' => 'Добавлять удалённый файл в email',
	'Class:TriggerOnAttachmentDelete/Attribute:file_in_email+' => 'Если отмечено, удалённый файл будет автоматически прикреплён к письму при срабатывании действия email',
	'Class:TriggerOnObject:TriggerClassAttachment/ReadOnlyMessage' => 'Триггер на объект не допускается для класса Attachment. Используйте специальный триггер',
]);
