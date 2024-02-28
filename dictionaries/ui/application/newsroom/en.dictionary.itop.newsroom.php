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

Dict::Add('EN US', 'English', 'English', array(
	'UI:Newsroom:iTopNotification:Label' => ITOP_APPLICATION_SHORT,
	'UI:Newsroom:iTopNotification:ViewAllPage:Title' => 'Your ' . ITOP_APPLICATION_SHORT.' notifications',
	'UI:Newsroom:iTopNotification:ViewAllPage:Read:Label' => 'Read',
	'UI:Newsroom:iTopNotification:ViewAllPage:Unread:Label' => 'Unread',
	'UI:Newsroom:iTopNotification:SelectMode:Label' => 'Select mode',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAllAsRead:Label' => 'Mark all as read',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAllAsUnread:Label' => 'Mark all as unread',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteAll:Label' => 'Delete all',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteAll:Success:Message' => 'All %1$s notifications have been deleted',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteAll:Confirmation:Title' => 'Delete all notifications',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteAll:Confirmation:Message' => 'Are you sure you want to delete all notifications?',
	
	'UI:Newsroom:iTopNotification:ViewAllPage:Empty:Title' => 'No notification, you are up to date!',

	// Actions
	// - Unitary buttons
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:Delete:Label' => 'Delete this notification',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:ViewObject:Label' => 'Go to the notification url',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsRead:Label' => 'Mark as read',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsUnread:Label' => 'Mark as unread',
	// - Bulk buttons
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkSelectedAsRead:Label' => 'Mark selected as read',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkSelectedAsUnread:Label' => 'Mark selected as unread',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteSelected:Label' => 'Delete selected',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteSelected:Confirmation:Title' => 'Delete selected notifications',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteSelected:Confirmation:Message' => 'Are you sure you want to delete selected notifications?',

	// Feedback messages
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:InvalidAction:Message' => 'Invalid action: "%1$s"',
	// - Mark as read
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsRead:NoEvent:Message' => 'No notification to mark as read',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsRead:Success:Message' => 'The notification has been marked as read',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsReadMultiple:Success:Message' => '%1$s notifications have been marked as read',
	// - Mark as unread
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsUnread:NoEvent:Message' => 'No notification to mark as read',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsUnread:Success:Message' => 'The notification has been marked as unread',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsUnreadMultiple:Success:Message' => '%1$s notifications have been marked as unread',
	// Delete
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:Delete:NoEvent:Message' => 'No notification to delete',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:Delete:Success:Message' => 'The notification has been deleted',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteMultiple:Success:Message' => '%1$s notifications have been deleted',
));