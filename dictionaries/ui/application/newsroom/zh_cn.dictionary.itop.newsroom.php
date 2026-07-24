<?php

/**
 * Copyright (C) 2013-2024 Combodo SARL
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
	'UI:Newsroom:iTopNotification:Label' => ITOP_APPLICATION_SHORT,
	'UI:Newsroom:iTopNotification:ViewAllPage:Title' => '您的 '.ITOP_APPLICATION_SHORT.' 消息',
	'UI:Newsroom:iTopNotification:ViewAllPage:SubTitle' => '管理您的消息, 标记为已读或未读, 或者删除它们.',
	'UI:Newsroom:iTopNotification:ViewAllPage:Read:Label' => '已读',
	'UI:Newsroom:iTopNotification:ViewAllPage:Unread:Label' => '未读',
	'UI:Newsroom:iTopNotification:SelectMode:Label' => '请选择模式',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAllAsRead:Label' => '全部标记为已读',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAllAsUnread:Label' => '全部标记为未读',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteAll:Label' => '全部删除',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteAll:Success:Message' => '全部 %1$s 条消息已被删除',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteAll:Confirmation:Title' => '删除全部消息',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteAll:Confirmation:Message' => '请确认是否删除所有消息?',

	'UI:Newsroom:iTopNotification:ViewAllPage:Empty:Title' => '没有消息, 已是最新!',

	// Actions
	// - Unitary buttons
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:Delete:Label' => '删除这条消息',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:ViewObject:Label' => '查看消息',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsRead:Label' => '标记为已读',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsUnread:Label' => '标记为未读',
	// - Bulk buttons
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkSelectedAsRead:Label' => '标记已选为已读',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkSelectedAsUnread:Label' => '标记已选为未读',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteSelected:Label' => '删除已选择',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteSelected:Confirmation:Title' => '删除已选的消息',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteSelected:Confirmation:Message' => '请确认是否删除所选消息?',

	// Feedback messages
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:InvalidAction:Message' => '无效操作: "%1$s"',
	// - Mark as read
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsRead:NoEvent:Message' => '没有消息被标记为已读',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsRead:Success:Message' => '消息被标记为已读',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsReadMultiple:Success:Message' => '%1$s 条消息被标记为已读',
	// - Mark as unread
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsUnread:NoEvent:Message' => '没有消息标记为已读',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsUnread:Success:Message' => '消息被标记为未读',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsUnreadMultiple:Success:Message' => '%1$s 条消息被标记为未读',
	// Delete
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:Delete:NoEvent:Message' => '没有可删除的消息',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:Delete:Success:Message' => '消息已删除',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteMultiple:Success:Message' => '已删除 %1$s 条消息',
]);
