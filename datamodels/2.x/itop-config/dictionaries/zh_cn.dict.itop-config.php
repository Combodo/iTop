<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
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

Dict::Add('ZH CN', 'Chinese', '简体中文', [

	'Menu:ConfigEditor' => '编辑配置文件',
	'config-edit-title' => '配置文件编辑器',
	'config-edit-intro' => '编辑配置文件时请务必格外小心.',
	'config-apply' => '应用',
	'config-apply-title' => '应用 (Ctrl+S)',
	'config-cancel' => '重置',
	'config-saved' => '保存成功.',
	'config-confirm-cancel' => '您的修改将被丢弃.',
	'config-no-change' => '没有变化: 配置文件将保持不变.',
	'config-reverted' => '配置文件已恢复.',
	'config-parse-error' => '第%2$d行: %1$s.<br/>配置文件尚未更新.',
	'config-current-line' => '正在编辑第%1$s行',
	'config-saved-warning-db-password' => '保存成功, 但因为数据库密码中包含不支持的字符, 配置文件备份不会成功.',
	'config-error-transaction' => '错误: 无效的事务编号. 配置<b>没有</b>被更新.',
	'config-error-file-changed' => '错误: 配置文件在您打开以后已被更改, 无法保存. 请刷新并再次保存.',
	'config-not-allowed-in-demo' => '抱歉, '.ITOP_APPLICATION_SHORT.'处于<b>演示模式</b>: 不能编辑配置文件.',
	'config-interactive-not-allowed' => ITOP_APPLICATION_SHORT.'交互式配置编辑器已禁用. 请在配置文件中查看 <code>\'config_editor\' => \'disabled\'</code>.',
]);
