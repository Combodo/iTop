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
Dict::Add('ZH CN', 'Chinese', '简体中文', [

	// Bulk modify
	'UI:Bulk:modify:IncompatibleAttribute' => '此属性无法在批量操作中编辑',
	'UI:Bulk:Export:MaliciousInjection:Alert:Title' => 'Excel 安全警告',
	'UI:Bulk:Export:MaliciousInjection:Alert:Message' => '在 Microsoft Excel 中打开不信任的文件可能导致公式注入. 请确保 Excel 设置能够安全的处理该文件. <a href="%1$s" target="_blank">进入我们的文档了解更多.</a>',
	'UI:Bulk:Export:MaliciousInjection:Sanitization:Alert:Message' => '部分数据已进行脱敏处理以规避 Microsoft Excel 中可能的潜在安全问题. <a href="%1$s" target="_blank">进入我们的文档了解更多.</a>',
	'UI:Bulk:Export:MaliciousInjection:Input:Label' => '对危险数据进行脱敏',
	'UI:Bulk:Export:MaliciousInjection:Input:Tooltip' => '当启用时, 导出过程中会对危险数据进行脱敏. 这样做可以阻止 Microsoft Excel 把它们当作公式. 请注意，该操作可能会在原始数据前添加单引号(\')作为前缀，以此确保数据被正确识别为文本格式.',
	'Core:BulkExport:Security' => '安全',
]);
