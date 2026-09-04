<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * 
 */
/**
 *
 */
Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Attachments:TabTitle_Count' => '附件 (%1$d)',
	'Attachments:EmptyTabTitle' => '附件',
	'Attachments:FieldsetTitle' => '附件',
	'Attachments:DeleteBtn' => '删除',
	'Attachments:History_File_Added' => '附件%1$s已添加.',
	'Attachments:History_File_Removed' => '附件%1$s已移除.',
	'Attachments:AddAttachment' => '添加附件: ',
	'Attachments:UploadNotAllowedOnThisSystem' => '本系统不支持文件上传.',
	'Attachment:Max_Go' => '(最大文件尺寸: %1$s GB)',
	'Attachment:Max_Mo' => '(最大文件尺寸: %1$s MB)',
	'Attachment:Max_Ko' => '(最大文件尺寸: %1$s KB)',
	'Attachments:NoAttachment' => '没有附件. ',
	'Attachments:PreviewNotAvailable' => '此附件类型不支持预览.',
	'Attachments:Error:FileTooLarge' => '上传的文件过大. %1$s',
	'Attachments:Error:UploadedFileEmpty' => '收到的文件为空, 无法添加.
可能是因为您发送的是空文件,
或者咨询 '.ITOP_APPLICATION_SHORT.' 管理员,检查 '.ITOP_APPLICATION_SHORT.' 服务器硬盘是否满了.',
	'Attachments:Render:Icons' => '显示为图标',
	'Attachments:Render:Table' => '显示为列表',
	'UI:Attachments:DropYourFileHint' => '将文件拖放到此区域的任意位置',
	'Class:Attachment' => '附件',
	'Class:Attachment+' => '文件: 链接到单一对象的文本或图片. 它无法被修改，只能被删除. 附件无法在编辑界面之外创建.',
	'Class:Attachment/Attribute:expire' => '过期',
	'Class:Attachment/Attribute:expire+' => '~~',
	'Class:Attachment/Attribute:temp_id' => '临时id',
	'Class:Attachment/Attribute:temp_id+' => '~~',
	'Class:Attachment/Attribute:item_class' => '项目类型',
	'Class:Attachment/Attribute:item_class+' => '~~',
	'Class:Attachment/Attribute:item_id' => '项目',
	'Class:Attachment/Attribute:item_id+' => '~~',
	'Class:Attachment/Attribute:item_org_id' => '项目组织',
	'Class:Attachment/Attribute:item_org_id+' => '~~',
	'Class:Attachment/Attribute:contents' => '内容',
	'Class:Attachment/Attribute:contents+' => '~~',
	'Attachments:File:Thumbnail' => '图标',
	'Attachments:File:Name' => '文件名',
	'Attachments:File:Date' => '上传日期',
	'Attachments:File:Uploader' => '上传者',
	'Attachments:File:Size' => '大小',
	'Attachments:File:MimeType' => '类型',
	'Attachments:File:DownloadsCount' => '下载',
	'Class:Attachment/Attribute:creation_date' => '创建日期',
	'Class:Attachment/Attribute:creation_date+' => '~~',
	'Class:Attachment/Attribute:user_id' => '用户id',
	'Class:Attachment/Attribute:user_id+' => '~~',
	'Class:Attachment/Attribute:contact_id' => '联系人id',
	'Class:Attachment/Attribute:contact_id+' => '~~',
	'Class:TriggerOnAttachmentDownload' => '触发器 (对象附件被下载时)',
	'Class:TriggerOnAttachmentDownload+' => '触发器基于指定类型 [或子类型] 对象附件被下载时',
	'Class:TriggerOnAttachmentCreate' => '触发器 (对象附件被创建时)',
	'Class:TriggerOnAttachmentCreate+' => '触发器 (对象附件被创建时)',
	'Class:TriggerOnAttachmentDelete' => '触发器 (对象附件被删除时)',
	'Class:TriggerOnAttachmentDelete+' => '触发器 (对象附件被删除时)',
	'Class:TriggerOnAttachmentCreate/Attribute:file_in_email' => '在邮件里添加文件',
	'Class:TriggerOnAttachmentCreate/Attribute:file_in_email+' => '如果勾选，文件将在触发邮件操作时自动附加到邮件中',
	'Class:TriggerOnAttachmentDelete/Attribute:file_in_email' => '在邮件里添加已删除的文件',
	'Class:TriggerOnAttachmentDelete/Attribute:file_in_email+' => '如果勾选，已删除的文件将在触发邮件操作时自动附加到邮件中',
	'Class:TriggerOnObject:TriggerClassAttachment/ReadOnlyMessage' => '此触发器不允许用于附件. 请使用特定的触发器',
]);
