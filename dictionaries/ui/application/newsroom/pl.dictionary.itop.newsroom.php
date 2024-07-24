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

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'UI:Newsroom:iTopNotification:Label' => ITOP_APPLICATION_SHORT,
	'UI:Newsroom:iTopNotification:ViewAllPage:Title' => 'Twoje wiadomości ' . ITOP_APPLICATION_SHORT,
	'UI:Newsroom:iTopNotification:ViewAllPage:SubTitle' => 'Zarządzaj swoimi wiadomościami, oznaczaj je jako przeczytane lub nieprzeczytane, usuwaj je itp.',
	'UI:Newsroom:iTopNotification:ViewAllPage:Read:Label' => 'Przeczytane',
	'UI:Newsroom:iTopNotification:ViewAllPage:Unread:Label' => 'Nieprzeczytane',
	'UI:Newsroom:iTopNotification:SelectMode:Label' => 'Wybierz tryb',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAllAsRead:Label' => 'Oznacz wszystkie jako przeczytane',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAllAsUnread:Label' => 'Oznacz wszystkie jako nieprzeczytane',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteAll:Label' => 'Usuń wszystkie',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteAll:Success:Message' => 'Wszystkie wiadomości %1$s zostały usunięte',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteAll:Confirmation:Title' => 'Usuń wszystkie wiadomości',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteAll:Confirmation:Message' => 'Czy na pewno chcesz usunąć wszystkie wiadomości?',
	
	'UI:Newsroom:iTopNotification:ViewAllPage:Empty:Title' => 'Brak nowości, jesteś na bieżąco!',

	// Actions
	// - Unitary buttons
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:Delete:Label' => 'Usuń tę wiadomość',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:ViewObject:Label' => 'Przejdź do adresu URL wiadomości',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsRead:Label' => 'Oznacz jako przeczytane',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsUnread:Label' => 'Oznacz jako nieprzeczytane',
	// - Bulk buttons
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkSelectedAsRead:Label' => 'Oznacz wybrane jako przeczytane',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkSelectedAsUnread:Label' => 'Oznacz wybrane jako nieprzeczytane',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteSelected:Label' => 'Usuń wybrane',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteSelected:Confirmation:Title' => 'Usuń wybrane wiadomości',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteSelected:Confirmation:Message' => 'Czy na pewno chcesz usunąć wybrane wiadomości?',

	// Feedback messages
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:InvalidAction:Message' => 'Nieprawidłowa akcja: "%1$s"',
	// - Mark as read
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsRead:NoEvent:Message' => 'Brak wiadomości do oznaczenia jako przeczytane',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsRead:Success:Message' => 'Wiadomość została oznaczona jako przeczytana',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsReadMultiple:Success:Message' => '%1$s wiadomości zostało oznaczonych jako przeczytane',
	// - Mark as unread
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsUnread:NoEvent:Message' => 'Brak wiadomości do oznaczenia jako nieprzeczytane',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsUnread:Success:Message' => 'Wiadomość została oznaczona jako nieprzeczytana',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsUnreadMultiple:Success:Message' => '%1$s wiadomości zostało oznaczonych jako nieprzeczytane',
	// Delete
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:Delete:NoEvent:Message' => 'Brak wiadomości do usunięcia',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:Delete:Success:Message' => 'Wiadomość została usunięta',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteMultiple:Success:Message' => '%1$s wiadomości zostało usuniętych',
));