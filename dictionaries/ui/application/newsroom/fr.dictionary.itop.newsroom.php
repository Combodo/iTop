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

Dict::Add('FR FR', 'French', 'Français', array(
	'UI:Newsroom:iTopNotification:Label' => ITOP_APPLICATION_SHORT,
	'UI:Newsroom:iTopNotification:ViewAllPage:Title' => 'Vos notifications ' . ITOP_APPLICATION_SHORT,
	'UI:Newsroom:iTopNotification:ViewAllPage:Read:Label' => 'Lue',
	'UI:Newsroom:iTopNotification:ViewAllPage:Unread:Label' => 'Non lue',
	'UI:Newsroom:iTopNotification:SelectMode:Label' => 'Sélection multiple',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAllAsRead:Label' => 'Marquer tout comme lu',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAllAsUnread:Label' => 'Marquer tout comme non lu',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteAll:Label' => 'Supprimer tout',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteAll:Success:Message' => '%1$s notifications ont été supprimées',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteAll:Confirmation:Title' => 'Supprimer toutes les notifications',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteAll:Confirmation:Message' => 'Êtes-vous sûr de vouloir supprimer toutes les notifications ?',

	'UI:Newsroom:iTopNotification:ViewAllPage:Empty:Title' => 'Aucune notification, vous êtes à jour !',

	// Actions
	// - Unitary buttons
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:Delete:Label' => 'Supprimer cette notification',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:ViewObject:Label' => 'Aller à l\'url de la notification',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsRead:Label' => 'Marquer comme lu',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsUnread:Label' => 'Marquer comme non lu',
	// - Bulk buttons
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkSelectedAsRead:Label' => 'Marquer sélectionnée(s) comme lu',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkSelectedAsUnread:Label' => 'Marquer sélectionnée(s) comme non lu',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteSelected:Label' =>  'Supprimer sélectionnée(s)',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteSelected:Confirmation:Title' => 'Supprimer les notifications sélectionnées',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteSelected:Confirmation:Message' => 'Êtes-vous sûr de vouloir supprimer les notifications sélectionnées ?',

	// Feedback messages
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:InvalidAction:Message' => 'Action invalide : "%1$s"',
	// - Mark as read
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsRead:NoEvent:Message' => 'Aucune notification à marquer comme lue',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsRead:Success:Message' => 'La notification a été marquée comme lue',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsReadMultiple:Success:Message' => '%1$s notifications ont été marquées comme lues',
	// - Mark as unread
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsUnread:NoEvent:Message' => 'Aucune notification à marquer comme non lue',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsUnread:Success:Message' => 'La notification a été marquée comme non lue',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsUnreadMultiple:Success:Message' => '%1$s notifications ont été marquées comme non lues',
	// Delete
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:Delete:NoEvent:Message' => 'Aucune notification à supprimer',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:Delete:Success:Message' => 'La notification a été supprimée',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteMultiple:Success:Message' => '%1$s notifications ont été supprimées',
));