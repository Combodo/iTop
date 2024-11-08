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

	// Placeholders
	// $%1s : host object class name
	// $%2s : host object friendlyname
	// $%3s : current tab name
	// $%4s : remote object class name
	// $%5s : remote object friendlyname

	'UI:Links:Object:New:Modal:Title'               => '创建对象',

	// Create
	'UI:Links:Create:Button'                        => '创建',
	'UI:Links:Create:Button+'                       => '创建一个 %4$s',
	'UI:Links:Create:Modal:Title'                   => '创建一个 %4$s 至% 2$s',

	// Add
	'UI:Links:Add:Button'                           => '添加',
	'UI:Links:Add:Button+'                          => '添加一个 %4$s',
	'UI:Links:Add:Modal:Title'                      => '添加一个 %4$s 至 %2$s',

	// Modify link
	'UI:Links:ModifyLink:Button'                    => '修改',
	'UI:Links:ModifyLink:Button+'                   => '修改此关联',
	'UI:Links:ModifyLink:Modal:Title'               => '修改 %2$s 和 %5$s 的关联',

	// Modify object
	'UI:Links:ModifyObject:Button'                  => '修改',
	'UI:Links:ModifyObject:Button+'                 => '修改此对象',
	'UI:Links:ModifyObject:Modal:Title'             => '%5$s',

	// Remove
	'UI:Links:Remove:Button'                        => '移除',
	'UI:Links:Remove:Button+'                       => '移除此 %4$s',
	'UI:Links:Remove:Modal:Title'                   => '从%1$s 移除 %4$s',
	'UI:Links:Remove:Modal:Message'                 => '请确认从 %2$s 移除 %5$s ?',

	// Delete
	'UI:Links:Delete:Button'                        => '删除',
	'UI:Links:Delete:Button+'                       => '删除此 %4$s',
	'UI:Links:Delete:Modal:Title'                   => '删除 %4$s',
	'UI:Links:Delete:Modal:Message'                 => '请确认删除 %5$s?',

	// Bulk
	'UI:Links:Bulk:LinkWillBeCreatedForAllObjects'  => '添加至所有对象',
	'UI:Links:Bulk:LinkWillBeDeletedFromAllObjects' => '从所有对象删除',
	'UI:Links:Bulk:LinkWillBeCreatedFor1Object'     => '添加至一个对象',
	'UI:Links:Bulk:LinkWillBeDeletedFrom1Object'    => '从一个对象移除',
	'UI:Links:Bulk:LinkWillBeCreatedForXObjects'    => '添加 {count} 个对象',
	'UI:Links:Bulk:LinkWillBeDeletedFromXObjects'   => '移除 {count} 个对象',
	'UI:Links:Bulk:LinkExistForAllObjects'          => '已关联所有对象',
	'UI:Links:Bulk:LinkExistForOneObject'           => '已关联一个对象',
	'UI:Links:Bulk:LinkExistForXObjects'            => '已关联 {count} 个对象',

	// New item
	'UI:Links:NewItem' => '新建条目',
]);