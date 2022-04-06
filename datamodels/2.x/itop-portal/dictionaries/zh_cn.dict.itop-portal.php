<?php
// Copyright (C) 2010-2021 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>
/**
 * @author      Benjamin Planque <benjamin.planque@combodo.com>
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
//////////////////////////////////////////////////////////////////////
// Note: The classes have been grouped by categories: bizmodel
//////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////
// Classes in 'bizmodel'
//////////////////////////////////////////////////////////////////////
//
Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'portal:itop-portal' => '标准门户', // This is the portal name that will be displayed in portal dispatcher (eg. URL in menus)
	'Page:DefaultTitle' => ITOP_APPLICATION_SHORT.' - 用户门户',
	'Brick:Portal:UserProfile:Title' => '我的资料',
	'Brick:Portal:NewRequest:Title' => '新建工单',
	'Brick:Portal:NewRequest:Title+' => '<p>需要帮助?</p><p>选择子服务,然后提交工单给我们的支持团队.</p>',
	'Brick:Portal:OngoingRequests:Title' => '正在处理的工单',
	'Brick:Portal:OngoingRequests:Title+' => '<p>跟踪正在处理的工单.</p><p>查询进度, 添加留言, 添加附件, 确认解决方案.</p>',
	'Brick:Portal:OngoingRequests:Tab:OnGoing' => '正在处理',
	'Brick:Portal:OngoingRequests:Tab:Resolved' => '已解决',
	'Brick:Portal:ClosedRequests:Title' => '已关闭的工单',
));
