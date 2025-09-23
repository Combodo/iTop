<?php
/**
 * @copyright Copyright (C) 2024 Combodo SAS
 * @license https://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('EN GB', 'British English', 'British English', array(
	'UI:Newsroom:iTopNotification:Label' => ITOP_APPLICATION_SHORT,
	'UI:Newsroom:iTopNotification:ViewAllPage:Title' => 'Your ' . ITOP_APPLICATION_SHORT.' news',
	'UI:Newsroom:iTopNotification:ViewAllPage:SubTitle' => 'Manage your news, flag them as read or unread, delete them, etc.',
	'UI:Newsroom:iTopNotification:ViewAllPage:Read:Label' => 'Read',
	'UI:Newsroom:iTopNotification:ViewAllPage:Unread:Label' => 'Unread',
	'UI:Newsroom:iTopNotification:SelectMode:Label' => 'Select mode',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAllAsRead:Label' => 'Mark all as read',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAllAsUnread:Label' => 'Mark all as unread',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteAll:Label' => 'Delete all',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteAll:Success:Message' => 'All %1$s news have been deleted',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteAll:Confirmation:Title' => 'Delete all news',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteAll:Confirmation:Message' => 'Are you sure you want to delete all news?',
	
	'UI:Newsroom:iTopNotification:ViewAllPage:Empty:Title' => 'No news, you are up to date!',

	// Actions
	// - Unitary buttons
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:Delete:Label' => 'Delete this news',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:ViewObject:Label' => 'Go to the news URL',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsRead:Label' => 'Mark as read',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsUnread:Label' => 'Mark as unread',
	// - Bulk buttons
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkSelectedAsRead:Label' => 'Mark selected as read',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkSelectedAsUnread:Label' => 'Mark selected as unread',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteSelected:Label' => 'Delete selected',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteSelected:Confirmation:Title' => 'Delete selected news',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteSelected:Confirmation:Message' => 'Are you sure you want to delete selected news?',

	// Feedback messages
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:InvalidAction:Message' => 'Invalid action: "%1$s"',
	// - Mark as read
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsRead:NoEvent:Message' => 'No news to mark as read',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsRead:Success:Message' => 'The news has been marked as read',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsReadMultiple:Success:Message' => '%1$s news have been marked as read',
	// - Mark as unread
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsUnread:NoEvent:Message' => 'No news to mark as read',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsUnread:Success:Message' => 'The news has been marked as unread',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsUnreadMultiple:Success:Message' => '%1$s news have been marked as unread',
	// Delete
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:Delete:NoEvent:Message' => 'No news to delete',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:Delete:Success:Message' => 'The news has been deleted',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteMultiple:Success:Message' => '%1$s news have been deleted',
));