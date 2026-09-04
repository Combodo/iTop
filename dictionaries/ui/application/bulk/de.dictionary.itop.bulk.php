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
	'UI:Bulk:Export:MaliciousInjection:Sanitization:Alert:Message' => 'Einige Werte wurden bereinigt, um mögliche Sicherheitsprobleme in Microsoft Excel zu vermeiden. <a href="%1$s" target="_blank">Mehr dazu in unserer Dokumentation.</a>',
	'UI:Bulk:Export:MaliciousInjection:Input:Label' => 'Potenziell gefährliche Werte bereinigen',
	'UI:Bulk:Export:MaliciousInjection:Input:Tooltip' => 'Wenn aktiviert, werden potenziell gefährliche Werte beim Export bereinigt. Dadurch interpretiert Microsoft Excel sie nicht als Formeln. Beachten Sie, dass die ursprünglichen Daten dabei verändert werden können: Ihnen wird ein einfaches Anführungszeichen (\') vorangestellt, damit sie als Text behandelt werden.',
	'Core:BulkExport:Security' => 'Sicherheit',
]);
