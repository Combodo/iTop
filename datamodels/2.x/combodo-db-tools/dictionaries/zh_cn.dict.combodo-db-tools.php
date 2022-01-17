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
	'Menu:DBToolsMenu' => 'DB 工具',
	'DBTools:Class' => 'Class~~',
	'DBTools:Title' => '数据库维护工具',
	'DBTools:ErrorsFound' => '发现错误',
	'DBTools:Indication' => 'Important: after fixing errors in the database you\'ll have to run the analysis again as new inconsistencies will be generated~~',
	'DBTools:Disclaimer' => 'DISCLAIMER: BACKUP YOUR DATABASE BEFORE RUNNING THE FIXES~~',
	'DBTools:Error' => '错误',
	'DBTools:Count' => '个数',
	'DBTools:SQLquery' => 'SQL 查询',
	'DBTools:FixitSQLquery' => 'SQL query To Fix it (indication)~~',
	'DBTools:SQLresult' => 'SQL 结果',
	'DBTools:NoError' => '数据库OK ',
	'DBTools:HideIds' => '错误列表',
	'DBTools:ShowIds' => '详细视图',
	'DBTools:ShowReport' => '报告',
	'DBTools:IntegrityCheck' => '完整性检查',
	'DBTools:FetchCheck' => 'Fetch Check (long)~~',
	'DBTools:SelectAnalysisType' => '请选择分析类型',

	'DBTools:Analyze' => '分析',
	'DBTools:Details' => '显示详情',
	'DBTools:ShowAll' => '显示所有错误',

	'DBTools:Inconsistencies' => '数据库不一致',
	'DBTools:DetailedErrorTitle' => '%2$s error(s) in class %1$s: %3$s~~',

	'DBAnalyzer-Integrity-OrphanRecord' => 'Orphan record in `%1$s`, it should have its counterpart in table `%2$s`~~',
	'DBAnalyzer-Integrity-InvalidExtKey' => '无效的外键 %1$s (列: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-MissingExtKey' => '外键丢失 %1$s (列: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-InvalidValue' => '无效的值 %1$s (列: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-UsersWithoutProfile' => 'Some user accounts have no profile at all~~',
	'DBAnalyzer-Integrity-HKInvalid' => 'Broken hierarchical key `%1$s`~~',
	'DBAnalyzer-Fetch-Count-Error' => 'Fetch count error in `%1$s`, %2$d entries fetched / %3$d counted~~',
	'DBAnalyzer-Integrity-FinalClass' => 'Field `%2$s`.`%1$s` must have the same value than `%3$s`.`%1$s`~~',
	'DBAnalyzer-Integrity-RootFinalClass' => 'Field `%2$s`.`%1$s` must contains a valid class~~',
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
	'DBTools:LostAttachments:Disclaimer' => 'Here you can search your database for lost or misplaced attachments. This is NOT a data recovery tool, is does not retrieve deleted data.~~',

	'DBTools:LostAttachments:Button:Analyze' => '分析',
	'DBTools:LostAttachments:Button:Restore' => '还原',
	'DBTools:LostAttachments:Button:Restore:Confirm' => '该操作无法回退, 请确认是否继续还原.',
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

	'DBTools:LostAttachments:StoredAsInlineImage' => 'Stored as inline image~~',
	'DBTools:LostAttachments:History' => '附件 "%1$s" restored with DB 工具~~'
));
