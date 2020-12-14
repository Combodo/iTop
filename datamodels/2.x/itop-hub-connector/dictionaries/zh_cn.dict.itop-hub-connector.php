<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2018 Combodo SARL
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
	// Dictionary entries go here
	'Menu:iTopHub' => 'iTop Hub',
	'Menu:iTopHub:Register' => '进入iTop Hub ',
	'Menu:iTopHub:Register+' => '进入iTop Hub 更新您的组件',
	'Menu:iTopHub:Register:Description' => '<p>进入iTop Hub 社区平台!</br>寻找您想要的内容和信息, 管理本机扩展或安装新的扩展.</br><br/>通过这个页面连接到iTop Hub, 本机的信息也会被推送到 iTop Hub 上.</p>',
	'Menu:iTopHub:MyExtensions' => '已安装的扩展',
	'Menu:iTopHub:MyExtensions+' => '查看本机已安装的扩展',
	'Menu:iTopHub:BrowseExtensions' => '从iTop Hub 获取扩展',
	'Menu:iTopHub:BrowseExtensions+' => '去iTop Hub 浏览更多的扩展',
	'Menu:iTopHub:BrowseExtensions:Description' => '<p>进入iTop Hub 商店, 一站式查找各种iTop 扩展的地方 !</br>寻找符合您要求的扩展.</br><br/>通过这个页面连接到iTop Hub, 本机的信息也会被推送到 iTop Hub 上.</p>',
	'iTopHub:GoBtn' => '进入iTop Hub',
	'iTopHub:CloseBtn' => '关闭',
	'iTopHub:GoBtn:Tooltip' => '跳到 www.itophub.io',
	'iTopHub:OpenInNewWindow' => '从新窗口打开iTop Hub',
	'iTopHub:AutoSubmit' => '不再询问. 下次自动进入iTop Hub .',
	'UI:About:RemoteExtensionSource' => 'iTop Hub',
	'iTopHub:Explanation' => '点击这个按钮您将被引导至iTop Hub.',

	'iTopHub:BackupFreeDiskSpaceIn' => '%1$s free disk space in %2$s.',
	'iTopHub:FailedToCheckFreeDiskSpace' => '检查空闲的磁盘空间失败.',
	'iTopHub:BackupOk' => '备份成功.',
	'iTopHub:BackupFailed' => '备份失败!',
	'iTopHub:Landing:Status' => '安装状态',
	'iTopHub:Landing:Install' => '扩展安装进行中...',
	'iTopHub:CompiledOK' => '编译成功.',
	'iTopHub:ConfigurationSafelyReverted' => '安装时发生错误!<br/>iTop 配置将不会改变.',
	'iTopHub:FailAuthent' => 'Authentication failed for this action.~~',

	'iTopHub:InstalledExtensions' => '本机已安装的扩展',
	'iTopHub:ExtensionCategory:Manual' => '手动安装的扩展',
	'iTopHub:ExtensionCategory:Manual+' => '下列已安装的扩展是手动将文件放置到 %1$s 目录的:',
	'iTopHub:ExtensionCategory:Remote' => '从iTop Hub 安装的扩展',
	'iTopHub:ExtensionCategory:Remote+' => '下列已安装的扩展是来自iTop Hub:',
	'iTopHub:NoExtensionInThisCategory' => '尚未安装扩展.<br/><br/>浏览iTop Hub ,去寻找符合您要求的扩展.',
	'iTopHub:ExtensionNotInstalled' => '未安装',
	'iTopHub:GetMoreExtensions' => '从iTop Hub 获取扩展...',

	'iTopHub:LandingWelcome' => '恭喜! 下列来自iTop Hub 的扩展已被下载并安装到本机.',
	'iTopHub:GoBackToITopBtn' => '返回iTop',
	'iTopHub:Uncompressing' => '扩展解压中...',
	'iTopHub:InstallationWelcome' => '安装来自iTop Hub 的扩展',
	'iTopHub:DBBackupLabel' => '本机备份',
	'iTopHub:DBBackupSentence' => '在升级之前,备份数据库和iTop 配置文件',
	'iTopHub:DeployBtn' => '安装 !',
	'iTopHub:DatabaseBackupProgress' => '本机备份...',

	'iTopHub:InstallationEffect:Install' => 'Version: %1$s 将被安装.',
	'iTopHub:InstallationEffect:NoChange' => 'Version: %1$s 已安装. 保持不变.',
	'iTopHub:InstallationEffect:Upgrade' => '将从 version %1$s <b>升级</b> 到 version %2$s.',
	'iTopHub:InstallationEffect:Downgrade' => '将从 version %1$s <b>降级</b> 到 version %2$s.',
	'iTopHub:InstallationProgress:DatabaseBackup' => 'iTop 本机备份...',
	'iTopHub:InstallationProgress:ExtensionsInstallation' => '安装扩展',
	'iTopHub:InstallationEffect:MissingDependencies' => '扩展无法安装,因为未知的依赖.',
	'iTopHub:InstallationEffect:MissingDependencies_Details' => '该扩展依赖模块: %1$s',
	'iTopHub:InstallationProgress:InstallationSuccessful' => '安装成功!',

	'iTopHub:InstallationStatus:Installed_Version' => '%1$s 版本: %2$s.',
	'iTopHub:InstallationStatus:Installed' => '已安装',
	'iTopHub:InstallationStatus:Version_NotInstalled' => 'Version %1$s <b>未</b> 安装.',
));


