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

Dict::Add('DE DE', 'German', 'Deutsch', [
	'UI:Newsroom:iTopNotification:Label' => ITOP_APPLICATION_SHORT,
	'UI:Newsroom:iTopNotification:ViewAllPage:Title' => 'Ihre '.ITOP_APPLICATION_SHORT.'-Nachrichten',
	'UI:Newsroom:iTopNotification:ViewAllPage:SubTitle' => 'Verwalten Sie Ihre Nachrichten: als gelesen oder ungelesen markieren, löschen und mehr.',
	'UI:Newsroom:iTopNotification:ViewAllPage:Read:Label' => 'Gelesen',
	'UI:Newsroom:iTopNotification:ViewAllPage:Unread:Label' => 'Ungelesen',
	'UI:Newsroom:iTopNotification:SelectMode:Label' => 'Auswahlmodus',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAllAsRead:Label' => 'Alle als gelesen markieren',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAllAsUnread:Label' => 'Alle als ungelesen markieren',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteAll:Label' => 'Alle löschen',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteAll:Success:Message' => 'Alle %1$s Nachrichten wurden gelöscht',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteAll:Confirmation:Title' => 'Alle Nachrichten löschen',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteAll:Confirmation:Message' => 'Möchten Sie wirklich alle Nachrichten löschen?',
	'UI:Newsroom:iTopNotification:ViewAllPage:Empty:Title' => 'Keine Nachrichten, Sie sind auf dem neuesten Stand!',

	// Actions
	// - Unitary buttons
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:Delete:Label' => 'Diese Nachricht löschen',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:ViewObject:Label' => 'Zur URL der Nachricht wechseln',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsRead:Label' => 'Als gelesen markieren',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsUnread:Label' => 'Als ungelesen markieren',
	// - Bulk buttons
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkSelectedAsRead:Label' => 'Auswahl als gelesen markieren',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkSelectedAsUnread:Label' => 'Auswahl als ungelesen markieren',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteSelected:Label' => 'Auswahl löschen',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteSelected:Confirmation:Title' => 'Ausgewählte Nachrichten löschen',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteSelected:Confirmation:Message' => 'Möchten Sie die ausgewählten Nachrichten wirklich löschen?',

	// Feedback messages
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:InvalidAction:Message' => 'Ungültige Aktion: "%1$s"',
	// - Mark as read
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsRead:NoEvent:Message' => 'Keine Nachricht, die als gelesen markiert werden könnte',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsRead:Success:Message' => 'Die Nachricht wurde als gelesen markiert',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsReadMultiple:Success:Message' => '%1$s Nachrichten wurden als gelesen markiert',
	// - Mark as unread
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsUnread:NoEvent:Message' => 'Keine Nachricht, die als ungelesen markiert werden könnte',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsUnread:Success:Message' => 'Die Nachricht wurde als ungelesen markiert',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsUnreadMultiple:Success:Message' => '%1$s Nachrichten wurden als ungelesen markiert',
	// Delete
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:Delete:NoEvent:Message' => 'Keine Nachricht zum Löschen vorhanden',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:Delete:Success:Message' => 'Die Nachricht wurde gelöscht',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteMultiple:Success:Message' => '%1$s Nachrichten wurden gelöscht',
]);
