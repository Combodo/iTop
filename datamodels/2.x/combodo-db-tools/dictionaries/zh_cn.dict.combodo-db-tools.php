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
	'DBAnalyzer-Fetch-Count-Error' => '读取计数出错于 `%1$s`, %2$d个记录已读取 / %3$d已',
	'DBAnalyzer-Integrity-FinalClass' => '字段 `%2$s`.`%1$s` 必须是相同的值, 而不是 `%3$s`.`%1$s`',
	'DBAnalyzer-Integrity-HKInvalid' => '损坏的层级链 `%1$s`',
	'DBAnalyzer-Integrity-InvalidExtKey' => '无效的外键%1$s (列: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-InvalidValue' => '无效的值%1$s (列: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-MissingExtKey' => '外键丢失%1$s (列: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-OrphanRecord' => '孤立记录位于 `%1$s`, 其应该有副本位于表 `%2$s`',
	'DBAnalyzer-Integrity-RootFinalClass' => '字段 `%2$s`.`%1$s` 必须包含一个有效的类型',
	'DBAnalyzer-Integrity-UsersWithoutProfile' => '一些用户账号没有角色',
	'DBTools:Analyze' => '分析',
	'DBTools:Base' => '数据库',
	'DBTools:Class' => '类型',
	'DBTools:Count' => '个数',
	'DBTools:DatabaseInfo' => '数据库信息',
	'DBTools:DetailedErrorLimit' => '列表限制为%1$s错误',
	'DBTools:DetailedErrorTitle' => '%2$s个错误在类型%1$s: %3$s',
	'DBTools:Details' => '显示详情',
	'DBTools:Disclaimer' => '免责申明: 在应用修复之前, 应先备份数据库',
	'DBTools:Error' => '错误',
	'DBTools:ErrorsFound' => '发现错误',
	'DBTools:FetchCheck' => '提取检查 (耗时长)',
	'DBTools:FixitSQLquery' => '修复问题的SQL查询 (指示)',
	'DBTools:HideIds' => '错误列表',
	'DBTools:Inconsistencies' => '数据库不一致',
	'DBTools:Indication' => '重要: 修复数据库错误后, 可能会出现新的不一致, 您必须重新运行一次分析.',
	'DBTools:IntegrityCheck' => '完整性检查',
	'DBTools:LostAttachments' => '附件缺失',
	'DBTools:LostAttachments:Button:Analyze' => '分析',
	'DBTools:LostAttachments:Button:Busy' => '请稍后...',
	'DBTools:LostAttachments:Button:Restore' => '还原',
	'DBTools:LostAttachments:Button:Restore:Confirm' => '此操作无法回退, 请确认是否继续还原.',
	'DBTools:LostAttachments:Disclaimer' => '可以在此搜索数据库中丢失或错放的附件. 这不是数据恢复工具, 其无法恢复已删除的数据.',
	'DBTools:LostAttachments:History' => '附件 "%1$s" 已使用数据库工具还原',
	'DBTools:LostAttachments:Step:Analyze' => '首先, 通过分析数据库来搜索丢失或误挪动的附件.',
	'DBTools:LostAttachments:Step:AnalyzeResults' => '分析结果:',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:CurrentLocation' => '当前位置',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:Filename' => '文件名',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:TargetLocation' => '移动到...',
	'DBTools:LostAttachments:Step:AnalyzeResults:None' => '非常好! 所有附件都是正常的.',
	'DBTools:LostAttachments:Step:AnalyzeResults:Some' => '某些附件 (%1$d) 看起来放错了位置. 请检查下面的列表并选择要挪动的文件.',
	'DBTools:LostAttachments:Step:RestoreResults' => '还原结果:',
	'DBTools:LostAttachments:Step:RestoreResults:Results' => '%1$d/%2$d的附件被还原.',
	'DBTools:LostAttachments:StoredAsInlineImage' => '存储为内嵌图像',
	'DBTools:NoError' => '数据库正确',
	'DBTools:SQLquery' => 'SQL查询',
	'DBTools:SQLresult' => 'SQL结果',
	'DBTools:SelectAnalysisType' => '请选择分析类型',
	'DBTools:ShowAll' => '显示所有错误',
	'DBTools:ShowIds' => '详细视图',
	'DBTools:ShowReport' => '报告',
	'DBTools:Size' => '大小',
	'DBTools:Title' => '数据库维护工具',
	'Menu:DBToolsMenu' => '数据库工具',
]);
