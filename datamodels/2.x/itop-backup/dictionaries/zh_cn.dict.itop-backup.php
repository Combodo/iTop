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
	'Menu:BackupStatus' => '定时备份',
	'bkp-backup-running' => '备份正在进行, 请稍后...',
	'bkp-button-backup-now' => '立即备份!',
	'bkp-button-restore-now' => '还原!',
	'bkp-confirm-backup' => '请确认是否立即开始备份.',
	'bkp-confirm-restore' => '请确认要还原的备份文件是 %1$s.',
	'bkp-dir-not-writeable' => '%1$s没有写入权限',
	'bkp-free-disk-space' => '<b>%1$s可用空间</b>位于<code>%2$s</code>',
	'bkp-missing-dir' => '目标目录<code>%1$s</code>找不到',
	'bkp-mysqldump-issue' => 'mysqldump无法运行 (retcode=%1$d): 请确认它安装在正确的路径, 或者调整'.ITOP_APPLICATION_SHORT.'配置文件的参数mysql_bindir',
	'bkp-mysqldump-notfound' => 'mysqldump找不到: %1$s - 请确认它安装在正确的路径, 或者调整'.ITOP_APPLICATION_SHORT.'配置文件的参数mysql_bindir.',
	'bkp-mysqldump-ok' => '已找到mysqldump : %1$s',
	'bkp-name-sample' => '备份文件将以数据库名, 日期和时间进行命名. 例如: %1$s',
	'bkp-next-backup' => '下一次备份将发生在<b>%1$s</b> (%2$s) 的%3$s',
	'bkp-next-backup-unknown' => '下一次备份<b>尚未被计划</b>.',
	'bkp-next-to-delete' => '当下一次备份时将被删除 (参阅设置 "retention_count")',
	'bkp-restore-running' => '还原正在进行, 请稍等...',
	'bkp-retention' => '最多<b>%1$d份备份文件</b>在目标目录.',
	'bkp-status-backups-auto' => '定时备份',
	'bkp-status-backups-manual' => '手动备份',
	'bkp-status-backups-none' => '尚未开始备份',
	'bkp-status-checks' => '设置与检查',
	'bkp-status-title' => '定时备份',
	'bkp-success-restore' => '还原成功.',
	'bkp-table-actions' => '操作',
	'bkp-table-actions+' => '',
	'bkp-table-file' => '文件',
	'bkp-table-file+' => '只有扩展名是.zip的文件才被认为是备份文件',
	'bkp-table-size' => '大小',
	'bkp-table-size+' => '',
	'bkp-wait-backup' => '请等待备份完成...',
	'bkp-wait-restore' => '请等待还原完成...',
	'bkp-week-days' => '在每个<b>%1$s的%2$s</b>进行备份',
	'bkp-wrong-format-spec' => '当前文件名格式错误 (%1$s). 默认格式应该是: %2$s',
]);
