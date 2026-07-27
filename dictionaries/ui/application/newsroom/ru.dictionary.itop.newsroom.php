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

Dict::Add('RU RU', 'Russian', 'Русский', [
	'UI:Newsroom:iTopNotification:Label' => ITOP_APPLICATION_SHORT,
	'UI:Newsroom:iTopNotification:ViewAllPage:Title' => 'Ваши новости '.ITOP_APPLICATION_SHORT.'',
	'UI:Newsroom:iTopNotification:ViewAllPage:SubTitle' => 'Управляйте своими новостями: отмечайте их как прочитанные или непрочитанные, удаляйте и т. д.',
	'UI:Newsroom:iTopNotification:ViewAllPage:Read:Label' => 'Прочитано',
	'UI:Newsroom:iTopNotification:ViewAllPage:Unread:Label' => 'Не прочитано',
	'UI:Newsroom:iTopNotification:SelectMode:Label' => 'Режим выбора',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAllAsRead:Label' => 'Отметить все как прочитанные',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAllAsUnread:Label' => 'Отметить все как непрочитанные',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteAll:Label' => 'Удалить все',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteAll:Success:Message' => 'Все новости (%1$s) удалены',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteAll:Confirmation:Title' => 'Удаление всех новостей',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteAll:Confirmation:Message' => 'Вы уверены, что хотите удалить все новости?',
	'UI:Newsroom:iTopNotification:ViewAllPage:Empty:Title' => 'Новостей нет, вы в курсе всех событий!',

	// Actions
	// - Unitary buttons
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:Delete:Label' => 'Удалить эту новость',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:ViewObject:Label' => 'Перейти по ссылке новости',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsRead:Label' => 'Отметить как прочитанное',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsUnread:Label' => 'Отметить как непрочитанное',
	// - Bulk buttons
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkSelectedAsRead:Label' => 'Отметить выбранные как прочитанные',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkSelectedAsUnread:Label' => 'Отметить выбранные как непрочитанные',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteSelected:Label' => 'Удалить выбранные',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteSelected:Confirmation:Title' => 'Удаление выбранных новостей',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteSelected:Confirmation:Message' => 'Вы уверены, что хотите удалить выбранные новости?',

	// Feedback messages
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:InvalidAction:Message' => 'Недопустимое действие: "%1$s"',
	// - Mark as read
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsRead:NoEvent:Message' => 'Нет новостей для отметки как прочитанные',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsRead:Success:Message' => 'Новость отмечена как прочитанная',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsReadMultiple:Success:Message' => 'Отмечено как прочитанные новостей: %1$s',
	// - Mark as unread
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsUnread:NoEvent:Message' => 'Нет новостей для отметки как непрочитанные',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsUnread:Success:Message' => 'Новость отмечена как непрочитанная',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsUnreadMultiple:Success:Message' => 'Отмечено как непрочитанные новостей: %1$s',
	// Delete
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:Delete:NoEvent:Message' => 'Нет новостей для удаления',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:Delete:Success:Message' => 'Новость удалена',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteMultiple:Success:Message' => 'Удалено новостей: %1$s',
]);
