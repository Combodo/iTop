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
// Database inconsistencies
Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	// Dictionary entries go here
	'Menu:DBToolsMenu' => '数据库工具',
	'DBTools:Class' => '类',
	'DBTools:Title' => '数据库维护工具',
	'DBTools:ErrorsFound' => '发现错误',
	'DBTools:Indication' => '重要: 修复数据库错误后,可能会出现新的不一致,您必须重新运行一次分析.',
	'DBTools:Disclaimer' => '免责申明: 在应用修复之前,应先备份数据库',
	'DBTools:Error' => '错误',
	'DBTools:Count' => '个数',
	'DBTools:SQLquery' => 'SQL 查询',
	'DBTools:FixitSQLquery' => 'SQL query To Fix it (indication)~~',
	'DBTools:SQLresult' => 'SQL 结果',
	'DBTools:NoError' => '数据库正确',
	'DBTools:HideIds' => '错误列表',
	'DBTools:ShowIds' => '详细视图',
	'DBTools:ShowReport' => '报告',
	'DBTools:IntegrityCheck' => '完整性检查',
	'DBTools:FetchCheck' => '提取检查(耗时长)',
	'DBTools:SelectAnalysisType' => '请选择分析类型',

	'DBTools:Analyze' => '分析',
	'DBTools:Details' => '显示详情',
	'DBTools:ShowAll' => '显示所有错误',

	'DBTools:Inconsistencies' => '数据库不一致',
	'DBTools:DetailedErrorTitle' => '%2$s 个错误在类 %1$s: %3$s',

	'DBAnalyzer-Integrity-OrphanRecord' => 'Orphan record in `%1$s`, it should have its counterpart in table `%2$s`~~',
	'DBAnalyzer-Integrity-InvalidExtKey' => '无效的外键 %1$s (列: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-MissingExtKey' => '外键丢失 %1$s (列: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-InvalidValue' => '无效的值 %1$s (列: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-UsersWithoutProfile' => '一些用户账号没有角色',
	'DBAnalyzer-Integrity-HKInvalid' => '损坏的层级链 `%1$s`',
	'DBAnalyzer-Fetch-Count-Error' => 'Fetch count error in `%1$s`, %2$d entries fetched / %3$d counted~~',
	'DBAnalyzer-Integrity-FinalClass' => '字段 `%2$s`.`%1$s` 必须是相同的值,而不是 `%3$s`.`%1$s`',
	'DBAnalyzer-Integrity-RootFinalClass' => '字段 `%2$s`.`%1$s` 必须包含一个有效的类',
));

// Database Info
Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'DBTools:DatabaseInfo' => '数据库信息',
	'DBTools:Base' => 'Base~~',
	'DBTools:Size' => '大小',
));

// Lost attachments
Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'DBTools:LostAttachments' => '缺失附件',
	'DBTools:LostAttachments:Disclaimer' => '可以在此搜索数据库中丢失或错放的附件.这不是数据恢复工具,其无法恢复已删除的数据.',

	'DBTools:LostAttachments:Button:Analyze' => '分析',
	'DBTools:LostAttachments:Button:Restore' => '还原',
	'DBTools:LostAttachments:Button:Restore:Confirm' => '此操作无法回退, 请确认是否继续还原.',
	'DBTools:LostAttachments:Button:Busy' => '请稍后...',

	'DBTools:LostAttachments:Step:Analyze' => '首先, 通过分析数据库来搜索丢失或误挪动的附件.',

	'DBTools:LostAttachments:Step:AnalyzeResults' => '分析结果:',
	'DBTools:LostAttachments:Step:AnalyzeResults:None' => '非常好! 所有附件都是正常的.',
	'DBTools:LostAttachments:Step:AnalyzeResults:Some' => '某些附件 (%1$d) 看起来放错了位置. 请检查下面的列表并选择要挪动的文件.',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:Filename' => '文件名',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:CurrentLocation' => '当前位置',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:TargetLocation' => '移动到...',

	'DBTools:LostAttachments:Step:RestoreResults' => '还原结果:',
	'DBTools:LostAttachments:Step:RestoreResults:Results' => '%1$d/%2$d 的附件被还原.',

	'DBTools:LostAttachments:StoredAsInlineImage' => '存储为内嵌图像',
	'DBTools:LostAttachments:History' => '附件 "%1$s" 已使用数据库工具还原'
));
