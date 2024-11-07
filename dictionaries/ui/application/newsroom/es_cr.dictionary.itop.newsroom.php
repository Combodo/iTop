<?php
/**
 * Spanish Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * @author Miguel Turrubiates <miguel_tf@yahoo.com>
 * @notas       Utilizar codificación UTF-8 para mostrar acentos y otros caracteres especiales
 */
Dict::Add('ES CR', 'Spanish', 'Español, Castellano', [
	'UI:Newsroom:iTopNotification:Label' => ITOP_APPLICATION_SHORT,
	'UI:Newsroom:iTopNotification:ViewAllPage:Title' => 'Sus noticias de ' . ITOP_APPLICATION_SHORT.'',
	'UI:Newsroom:iTopNotification:ViewAllPage:SubTitle' => 'Gestione sus noticias, márquelas como leídas o no leídas, elimínadas, etc.',
	'UI:Newsroom:iTopNotification:ViewAllPage:Read:Label' => 'Leído',
	'UI:Newsroom:iTopNotification:ViewAllPage:Unread:Label' => 'No leído',
	'UI:Newsroom:iTopNotification:SelectMode:Label' => 'Seleccionar modo',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAllAsRead:Label' => 'Marcar todo como leido',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAllAsUnread:Label' => 'Marcar todo como no leído',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteAll:Label' => 'Eliminar todos',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteAll:Success:Message' => 'Se han eliminado todas las noticias de %1$s',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteAll:Confirmation:Title' => 'Eliminar todas las noticias',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteAll:Confirmation:Message' => '¿Está seguro de que eliminar todas las noticias?',

	'UI:Newsroom:iTopNotification:ViewAllPage:Empty:Title' => '¡Sin novedades, está al día!',

	// Actions
	// - Unitary buttons
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:Delete:Label' => 'Eliminar esta noticia',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:ViewObject:Label' => 'Ir a la URL de noticias',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsRead:Label' => 'Marcar como leída',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsUnread:Label' => 'Marcar como no leída',
	// - Bulk buttons
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkSelectedAsRead:Label' => 'Marcar seleccionada como leída',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkSelectedAsUnread:Label' => 'Marcar seleccionada como no leída',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteSelected:Label' => 'Eliminar seleccionada',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteSelected:Confirmation:Title' => 'Eliminar noticias seleccionadas',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteSelected:Confirmation:Message' => '¿Estás seguro de que deseas eliminar las noticias seleccionadas?',

	// Feedback messages
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:InvalidAction:Message' => 'Acción no válida: "%1$s"',
	// - Mark as read
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsRead:NoEvent:Message' => 'No hay noticias para marcar como leídas',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsRead:Success:Message' => 'La noticia ha sido marcada como leída.',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsReadMultiple:Success:Message' => '%1$s noticias han sido marcadas como leídas',
	// - Mark as unread
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsUnread:NoEvent:Message' => 'No hay noticias para marcar como leídas',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsUnread:Success:Message' => 'La noticia ha sido marcada como no leída.',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsUnreadMultiple:Success:Message' => '%1$s noticias han sido marcadas como no leídas',
	// Delete
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:Delete:NoEvent:Message' => 'No hay noticias para eliminar',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:Delete:Success:Message' => 'La noticia ha sido eliminada.',
	'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteMultiple:Success:Message' => '%1$s noticias han sido eliminadas',
]);
