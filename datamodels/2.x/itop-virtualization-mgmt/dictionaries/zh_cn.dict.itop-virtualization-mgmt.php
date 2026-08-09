<?php

/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
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

//
// Fieldsets for Virtualization classes
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Virtualization:baseinfo' => '基本信息',
	'Virtualization:moreinfo' => '虚拟化详情',
	'Virtualization:otherinfo' => '日期和描述',
]);

//
// Class Cloud
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:Cloud/Name' => '%1$s',
	'Class:Cloud/ComplementaryName' => '%1$s-%2$s',
	'Class:Cloud' => '云平台',
	'Class:Cloud+' => '由云供应商运营的虚拟化主机. 它可以托管虚拟机和容器化主机.',
	'Class:Cloud/Attribute:provider_id' => '供应商',
	'Class:Cloud/Attribute:logo' => 'Logo',
	'Class:Cloud/Attribute:logo+' => '在影响分析图中显示此云平台时用作对象图标',
	'Class:Cloud/Attribute:provider_id+' => '谁提供云平台',
	'Class:Cloud/Attribute:location_id' => '位置',
	'Class:Cloud/Attribute:location_id+' => '云平台的位置',
]);

//
// Class: LogicalInterface
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:LogicalInterface/Name' => '%2$s %1$s',
	'Class:LogicalInterface/Attribute:org_id' => '组织',
	'Class:LogicalInterface/Attribute:org_id+' => '',
]);
