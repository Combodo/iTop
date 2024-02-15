<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2023 Combodo SARL
 * @license	http://opensource.org/licenses/AGPL-3.0
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
 * along with iTop. If not, see <http://www.gnu.org/licenses/>
 */
Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Menu:ConfigEditor' => 'Configurazione',
	'config-edit-title' => 'Editor del File di Configurazione',
	'config-edit-intro' => 'Prestare molta attenzione durante la modifica del file di configurazione.',
	'config-apply' => 'Applica',
	'config-apply-title' => 'Applica (Ctrl+S)',
	'config-cancel' => 'Reset',
	'config-saved' => 'Salvato con successo.',
	'config-confirm-cancel' => 'Le tue modifiche andranno perse.',
	'config-no-change' => 'Nessun cambiamento: il file è rimasto invariato.',
	'config-reverted' => 'La configurazione è stata ripristinata.',
	'config-parse-error' => 'Linea %2$d: %1$s.<br/>Il file NON è stato aggiornato.',
	'config-current-line' => 'Modifica linea: %1$s',
	'config-saved-warning-db-password' => 'Salvato con successo, ma il backup non funzionerà a causa di caratteri non supportati nella password del database.',
	'config-error-transaction' => 'Errore: ID di Transazione non valido. La configurazione <b>NON</b> è stata modificata.',
	'config-error-file-changed' => 'Errore: Il file di Configurazione è stato modificato da quando lo hai aperto e non può essere salvato. Aggiorna e applica nuovamente le tue modifiche.',
	'config-not-allowed-in-demo' => 'Spiacente, '.ITOP_APPLICATION_SHORT.' è in <b>modalità dimostrativa</b>: il file di configurazione non può essere modificato.',
	'config-interactive-not-allowed' => 'La modifica interattiva della configurazione di '.ITOP_APPLICATION_SHORT.' è stata disabilitata. Vedere <code>\'config_editor\' => \'disabled\'</code> nel file di configurazione.',
));
