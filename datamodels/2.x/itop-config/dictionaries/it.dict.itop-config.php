<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * 
 */
/**
 *
 */
Dict::Add('IT IT', 'Italian', 'Italiano', [
	'Menu:ConfigEditor' => 'Configurazione',
	'config-apply' => 'Applica',
	'config-apply-title' => 'Applica (Ctrl+S)',
	'config-cancel' => 'Reset',
	'config-confirm-cancel' => 'Le tue modifiche andranno perse.',
	'config-current-line' => 'Modifica linea: %1$s',
	'config-edit-intro' => 'Prestare molta attenzione durante la modifica del file di configurazione.',
	'config-edit-title' => 'Editor del File di Configurazione',
	'config-error-file-changed' => 'Errore: Il file di Configurazione è stato modificato da quando lo hai aperto e non può essere salvato. Aggiorna e applica nuovamente le tue modifiche.',
	'config-error-transaction' => 'Errore: ID di Transazione non valido. La configurazione <b>NON</b> è stata modificata.',
	'config-interactive-not-allowed' => 'La modifica interattiva della configurazione di '.ITOP_APPLICATION_SHORT.' è stata disabilitata. Vedere <code>\'config_editor\' => \'disabled\'</code> nel file di configurazione.',
	'config-no-change' => 'Nessun cambiamento: il file è rimasto invariato.',
	'config-not-allowed-in-demo' => 'Spiacente, '.ITOP_APPLICATION_SHORT.' è in <b>modalità dimostrativa</b>: il file di configurazione non può essere modificato.',
	'config-parse-error' => 'Linea %2$d: %1$s.<br/>Il file NON è stato aggiornato.',
	'config-reverted' => 'La configurazione è stata ripristinata.',
	'config-saved' => 'Salvato con successo.',
	'config-saved-warning-db-password' => 'Salvato con successo, ma il backup non funzionerà a causa di caratteri non supportati nella password del database.',
]);
