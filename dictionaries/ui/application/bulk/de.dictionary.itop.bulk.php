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
Dict::Add('DE DE', 'German', 'Deutsch', [
	'UI:Bulk:modify:IncompatibleAttribute' => 'Dieses Attribut kann in einer Massenänderung nicht bearbeitet werden.',
	'UI:Bulk:Export:MaliciousInjection:Alert:Title' => 'Excel-Sicherheitswarnung',
	'UI:Bulk:Export:MaliciousInjection:Alert:Message' => 'Das Öffnen einer Datei mit nicht vertrauenswürdigen Daten in Microsoft Excel kann zu einer Formel-Injektion führen. Stellen Sie sicher, dass Ihre Excel-Einstellungen so konfiguriert sind, dass Dateien sicher verarbeitet werden. <a href="%1$s" target="_blank">Erfahren Sie mehr in unserer Dokumentation.</a>',
	'UI:Bulk:Export:MaliciousInjection:Sanitization:Alert:Message' => 'Some values have been sanitized to prevent potential security issues in Microsoft Excel. <a href="%1$s" target="_blank">Learn more in our documentation.</a>~~',
	'UI:Bulk:Export:MaliciousInjection:Input:Label' => 'Sanitize potentially dangerous values~~',
	'UI:Bulk:Export:MaliciousInjection:Input:Tooltip' => 'When enabled, potentially dangerous values will be sanitized during export. This will prevent Microsoft Excel from interpreting them as formulas. Note that this may alter the original data by prefixing it with a single quote (\') to ensure it is treated as text.~~',
	'Core:BulkExport:Security' => 'Security~~',
]);
