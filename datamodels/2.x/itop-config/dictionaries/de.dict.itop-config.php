<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * 
 */
/**
 * @author ITOMIG GmbH <martin.raenker@itomig.de>
 *
 */
Dict::Add('DE DE', 'German', 'Deutsch', [
	'Menu:ConfigEditor' => 'Konfiguration',
	'config-apply' => 'Anwenden (Ctrl+S)',
	'config-apply-title' => 'Anwenden (Ctrl+S)',
	'config-cancel' => 'Zurücksetzen',
	'config-confirm-cancel' => 'Ihre Änderungen werden nicht gespeichert.',
	'config-current-line' => 'Editiere Zeile: %1$s',
	'config-edit-intro' => 'Achtung: Eine falsche Konfiguration kann dazu führen, dass '.ITOP_APPLICATION_SHORT.' für alle Benutzer unbenutzbar ist!',
	'config-edit-title' => 'Konfigurations-Editor',
	'config-error-file-changed' => 'Fehler: Die Konfigurationsdatei hat sich seit dem Öffnen geändert und kann nicht gespeichert werden. Aktualisieren Sie die Datei und wenden Sie Ihre Änderungen erneut an.',
	'config-error-transaction' => 'Fehler: Ungültige Transaction ID. Die Konfiguration wurde <b>NICHT</b> modifiziert.',
	'config-interactive-not-allowed' => 'Die interaktive Bearbeitung der '.ITOP_APPLICATION_SHORT.' Konfiguration wurde deaktiviert. Siehe <code>\'config_editor\' => \'disabled\'</code> in der Konfigurationsdatei.',
	'config-no-change' => 'Keine Änderungen: Die Datei wurde nicht verändert.',
	'config-not-allowed-in-demo' => 'Entschuldigung, '.ITOP_APPLICATION_SHORT.' befindet sich im <b>Demo-Modus</b>: Die Konfigurationsdatei kann nicht bearbeitet werden.',
	'config-parse-error' => 'Zeile %2$d: %1$s.<br/>Die Datei wurde nicht aktualisiert.',
	'config-reverted' => 'Die Konfiguration wurde zurückgesetzt',
	'config-saved' => 'Erfolgreich gespeichert',
	'config-saved-warning-db-password' => 'Die Konfiguration wurde gespeichert. Das Backup wird NICHT funktionieren, im Datenbankpasswort sind unzulässige Zeichen enthalten.',
]);
