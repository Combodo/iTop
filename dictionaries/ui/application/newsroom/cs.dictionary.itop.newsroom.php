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
Dict::Add('CS CZ', 'Czech', 'Čeština', array(
	'UI:Newsroom:iTopNotification:Label' => ITOP_APPLICATION_SHORT,
	'UI:Newsroom:iTopNotification:ViewAllPage:Title' => 'Vaše '.ITOP_APPLICATION_SHORT.' novinky',
	'UI:Newsroom:iTopNotification:ViewAllPage:SubTitle' => 'Spravujte oznámení, označujte je jako přečtené, smažte je, atd.',
	'UI:Newsroom:iTopNotification:ViewAllPage:Read:Label' => 'Přečtené',
	'UI:Newsroom:iTopNotification:ViewAllPage:Unread:Label' => 'Nepřečtené',
	'UI:Newsroom:iTopNotification:SelectMode:Label' => 'Zvolte mód',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAllAsRead:Label' => 'Všechy označit jako přetené',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAllAsUnread:Label' => 'Všechy označit jako nepřetené',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteAll:Label' => 'Všechny smazat',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteAll:Success:Message' => 'Všech %1$s novinek bylo smazáno',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteAll:Confirmation:Title' => 'Smazat všechny novinky',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteAll:Confirmation:Message' => 'Opravdu chete smazat všechny novinky?',
	'UI:Newsroom:iTopNotification:ViewAllPage:Empty:Title' => 'Žádné nové zprávy',

	// Actions
 // - Unitary buttons
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:Delete:Label' => 'Smaž zprávu',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:ViewObject:Label' => 'Přejít na URL novinek',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsRead:Label' => 'Označ jako přečtenou',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsUnread:Label' => 'Označ jako nepřečtenou',
	// - Bulk buttons
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkSelectedAsRead:Label' => 'Vybrané označ jako přečtené',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkSelectedAsUnread:Label' => 'Vybrané označ jako nepřečtené',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteSelected:Label' => 'Smaž vybrané',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteSelected:Confirmation:Title' => 'Smazat vybrané novinky',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteSelected:Confirmation:Message' => 'Opravdu chete smazat vybrané novinky?',

	// Feedback messages
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:InvalidAction:Message' => 'Neplatná operace: "%1$s"',
	// - Mark as read
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsRead:NoEvent:Message' => 'Žádné novinky nelze označit jako přečtené',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsRead:Success:Message' => 'Novinky byly označeny jako přečtené',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsReadMultiple:Success:Message' => '%1$s novinek bylo označeno jako přečtené',
	// - Mark as unread
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsUnread:NoEvent:Message' => 'Žádné novinky nelze označit jako nepřečtené',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsUnread:Success:Message' => 'Novinky byly označeny jako ne přečtené',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsUnreadMultiple:Success:Message' => '%1$s novinek bylo označeno jako nepřečtené',
	// Delete
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:Delete:NoEvent:Message' => 'Žádné novinky nelze smazat',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:Delete:Success:Message' => 'Novinky byly smazány',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteMultiple:Success:Message' => '%1$s novinek bylo smazáno',
));