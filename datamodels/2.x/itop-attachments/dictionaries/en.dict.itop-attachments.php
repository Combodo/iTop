<?php
/**
 * Copyright (C) 2013-2024 Combodo SAS
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

Dict::Add('EN US', 'English', 'English', array(
	'Attachments:TabTitle_Count' => 'Attachments (%1$d)',
	'Attachments:EmptyTabTitle' => 'Attachments',
	'Attachments:FieldsetTitle' => 'Attachments',
	'Attachments:DeleteBtn' => 'Delete',
	'Attachments:History_File_Added' => 'Attachment %1$s added.',
	'Attachments:History_File_Removed' => 'Attachment %1$s removed.',
	'Attachments:AddAttachment' => 'Add attachment: ',
	'Attachments:UploadNotAllowedOnThisSystem' => 'File upload in NOT allowed on this system.',
	'Attachment:Max_Go' => '(Maximum file size: %1$s GB)',
	'Attachment:Max_Mo' => '(Maximum file size: %1$s MB)',
	'Attachment:Max_Ko' => '(Maximum file size: %1$s KB)',
	'Attachments:NoAttachment' => 'No attachment. ',
	'Attachments:PreviewNotAvailable' => 'Preview not available for this type of attachment.',
	'Attachments:Error:FileTooLarge' => 'File is too large to be uploaded. %1$s',
	'Attachments:Error:UploadedFileEmpty' => 'The received file is empty and cannot be attached.
Either you have pushed an empty file,
or ask your '.ITOP_APPLICATION_SHORT.' administrator if the '.ITOP_APPLICATION_SHORT.' server disk is full.',
	'Attachments:Render:Icons' => 'Display as icons',
	'Attachments:Render:Table' => 'Display as list',
	'UI:Attachments:DropYourFileHint' => 'Drop files anywhere in this area',
));

//
// Class: Attachment
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Attachment' => 'Attachment',
	'Class:Attachment+' => '',
	'Class:Attachment/Attribute:expire' => 'Expire',
	'Class:Attachment/Attribute:expire+' => '',
	'Class:Attachment/Attribute:temp_id' => 'Temporary id',
	'Class:Attachment/Attribute:temp_id+' => '',
	'Class:Attachment/Attribute:item_class' => 'Item class',
	'Class:Attachment/Attribute:item_class+' => '',
	'Class:Attachment/Attribute:item_id' => 'Item',
	'Class:Attachment/Attribute:item_id+' => '',
	'Class:Attachment/Attribute:item_org_id' => 'Item organization',
	'Class:Attachment/Attribute:item_org_id+' => '',
	'Class:Attachment/Attribute:contents' => 'Contents',
	'Class:Attachment/Attribute:contents+' => '',
));


Dict::Add('EN US', 'English', 'English', array(
	'Attachments:File:Thumbnail' => 'Icon',
	'Attachments:File:Name' => 'File name',
	'Attachments:File:Date' => 'Upload date',
	'Attachments:File:Uploader' => 'Uploaded by',
	'Attachments:File:Size' => 'Size',
	'Attachments:File:MimeType' => 'Type',
	'Attachments:File:DownloadsCount' => 'Downloads',
));
//
// Class: Attachment
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Attachment/Attribute:creation_date' => 'Creation date',
	'Class:Attachment/Attribute:creation_date+' => '',
	'Class:Attachment/Attribute:user_id' => 'User id',
	'Class:Attachment/Attribute:user_id+' => '',
	'Class:Attachment/Attribute:contact_id' => 'Contact id',
	'Class:Attachment/Attribute:contact_id+' => '',
));

//
// Class: TriggerOnAttachmentDownload
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:TriggerOnAttachmentDownload' => 'Trigger (on object\'s attachment download)',
	'Class:TriggerOnAttachmentDownload+' => 'Trigger on object\'s attachment download of [a child class of] the given class',
));
