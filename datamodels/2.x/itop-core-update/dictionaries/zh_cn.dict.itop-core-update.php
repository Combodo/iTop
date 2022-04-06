<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2021 Combodo SARL
 * @license	http://opensource.org/licenses/AGPL-3.0
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
 * along with iTop. If not, see <http://www.gnu.org/licenses/>
 */
Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'iTopUpdate:UI:PageTitle' => '应用升级',
    'itop-core-update:UI:SelectUpdateFile' => '应用升级',
    'itop-core-update:UI:ConfirmUpdate' => ' 升级',
    'itop-core-update:UI:UpdateCoreFiles' => '应用升级',
	'iTopUpdate:UI:MaintenanceModeActive' => 'The application is currently under maintenance, no user can access the application. You have to run a setup or restore the application archive to return in normal mode.~~',
	'itop-core-update:UI:UpdateDone' => '应用升级',

	'itop-core-update/Operation:SelectUpdateFile/Title' => '应用升级',
	'itop-core-update/Operation:ConfirmUpdate/Title' => '请确认升级应用',
	'itop-core-update/Operation:UpdateCoreFiles/Title' => '应用正在升级',
	'itop-core-update/Operation:UpdateDone/Title' => '应用升级完毕',

	'iTopUpdate:UI:SelectUpdateFile' => '请选择要上传的升级文件',
	'iTopUpdate:UI:CheckUpdate' => '校验升级文件',
	'iTopUpdate:UI:ConfirmInstallFile' => '即将安装 %1$s',
	'iTopUpdate:UI:DoUpdate' => '升级',
	'iTopUpdate:UI:CurrentVersion' => '当前安装的版本',
	'iTopUpdate:UI:NewVersion' => '新安装的版本',
    'iTopUpdate:UI:Back' => '返回',
    'iTopUpdate:UI:Cancel' => '取消',
    'iTopUpdate:UI:Continue' => '继续',
	'iTopUpdate:UI:RunSetup' => '运行向导',
    'iTopUpdate:UI:WithDBBackup' => '数据库备份',
    'iTopUpdate:UI:WithFilesBackup' => '应用文件备份',
    'iTopUpdate:UI:WithoutBackup' => '无需备份',
    'iTopUpdate:UI:Backup' => '升级之前执行备份',
	'iTopUpdate:UI:DoFilesArchive' => '打包应用文件',
	'iTopUpdate:UI:UploadArchive' => '请选择要上传的软件包',
	'iTopUpdate:UI:ServerFile' => '服务器上的软件包路径已存在',
	'iTopUpdate:UI:WarningReadOnlyDuringUpdate' => '升级期间, 应用会变成只读状态.',

    'iTopUpdate:UI:Status' => '状态',
    'iTopUpdate:UI:Action' => '升级',
    'iTopUpdate:UI:History' => '版本历史',
    'iTopUpdate:UI:Progress' => '升级进度',

    'iTopUpdate:UI:DoBackup:Label' => '备份文件和数据库',
    'iTopUpdate:UI:DoBackup:Warning' => '由于磁盘空间不足, 不建议备份',

    'iTopUpdate:UI:DiskFreeSpace' => '磁盘剩余空间',
    'iTopUpdate:UI:ItopDiskSpace' => ITOP_APPLICATION_SHORT.' 的磁盘空间',
    'iTopUpdate:UI:DBDiskSpace' => '数据库的磁盘空间',
	'iTopUpdate:UI:FileUploadMaxSize' => '文件上传大小上限',

	'iTopUpdate:UI:PostMaxSize' => 'PHP ini 值 post_max_size: %1$s',
	'iTopUpdate:UI:UploadMaxFileSize' => 'PHP ini 值 upload_max_filesize: %1$s',

    'iTopUpdate:UI:CanCoreUpdate:Loading' => '正在文件系统',
    'iTopUpdate:UI:CanCoreUpdate:Error' => '文件系统检查失败 (%1$s)',
    'iTopUpdate:UI:CanCoreUpdate:ErrorFileNotExist' => '文件系统检查失败 ( %1$s 文件不存在)',
    'iTopUpdate:UI:CanCoreUpdate:Failed' => '文件系统检查失败',
    'iTopUpdate:UI:CanCoreUpdate:Yes' => '应用无法升级',
	'iTopUpdate:UI:CanCoreUpdate:No' => '应用无法升级: %1$s',
	'iTopUpdate:UI:CanCoreUpdate:Warning' => '警告: 应用升级可能失败: %1$s',
	'iTopUpdate:UI:CannotUpdateUseSetup' => '<b>Some modified files were detected</b>, a partial update cannot be executed.</br>Follow the <a target="_blank" href="%2$s"> procedure</a> in order to manually upgrade your iTop. You must use the <a href="%1$s">setup</a> to update the application.~~',
	'iTopUpdate:UI:CannotUpdateNewModules' => '<b>Some new modules were detected</b>, a partial update cannot be executed.</br>Follow the <a target="_blank" href="%2$s"> procedure</a> in order to manually upgrade your iTop. You must use the <a href="%1$s">setup</a> to update the application.~~',
	'iTopUpdate:UI:CheckInProgress' => 'Please wait during integrity check~~',

	// Setup Messages
    'iTopUpdate:UI:SetupMessage:Ready' => '准备开始',
	'iTopUpdate:UI:SetupMessage:EnterMaintenance' => '正在进入维护模式',
	'iTopUpdate:UI:SetupMessage:Backup' => '数据库备份',
	'iTopUpdate:UI:SetupMessage:FilesArchive' => '打包应用文件',
    'iTopUpdate:UI:SetupMessage:CopyFiles' => '复制新文件',
	'iTopUpdate:UI:SetupMessage:CheckCompile' => '检查更新',
	'iTopUpdate:UI:SetupMessage:Compile' => '升级应用程序和数据库',
	'iTopUpdate:UI:SetupMessage:UpdateDatabase' => '升级数据库',
	'iTopUpdate:UI:SetupMessage:ExitMaintenance' => '正在退出维护模式',
    'iTopUpdate:UI:SetupMessage:UpdateDone' => '升级完成',

	// Errors
	'iTopUpdate:Error:MissingFunction' => '无法开始升级, 功能缺失',
	'iTopUpdate:Error:MissingFile' => '缺少文件: %1$s',
	'iTopUpdate:Error:CorruptedFile' => '文件 %1$s 已损坏',
    'iTopUpdate:Error:BadFileFormat' => '上传的不是zip 文件',
    'iTopUpdate:Error:BadFileContent' => '升级文件不是程序升级包',
    'iTopUpdate:Error:BadItopProduct' => '升级文件与您的系统不兼容',
	'iTopUpdate:Error:Copy' => '错误, 无法复制 \'%1$s\' 到 \'%2$s\'',
    'iTopUpdate:Error:FileNotFound' => '文件找不到',
    'iTopUpdate:Error:NoFile' => '没有提供文件',
	'iTopUpdate:Error:InvalidToken' => '无效的 token',
	'iTopUpdate:Error:UpdateFailed' => '升级失败',
	'iTopUpdate:Error:FileUploadMaxSizeTooSmall' => '上传上限太小. 请调整 PHP 配置.',

	'iTopUpdate:UI:RestoreArchive' => '您可以从归档文件 \'%1$s\' 还原应用程序',
	'iTopUpdate:UI:RestoreBackup' => '您可以从 \'%1$s\' 还原数据库',
	'iTopUpdate:UI:UpdateDone' => '升级成功',
	'Menu:iTopUpdate' => '应用升级',
	'Menu:iTopUpdate+' => '应用升级',

    // Missing itop entries
    'Class:ModuleInstallation/Attribute:installed' => '安装时间',
    'Class:ModuleInstallation/Attribute:name' => '名称',
    'Class:ModuleInstallation/Attribute:version' => '版本',
    'Class:ModuleInstallation/Attribute:comment' => '备注',
));


