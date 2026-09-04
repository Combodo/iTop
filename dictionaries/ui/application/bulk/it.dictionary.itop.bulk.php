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
	'UI:Bulk:modify:IncompatibleAttribute' => 'Questo attributo non può essere modificato nel contesto di modifica bulk',
	'UI:Bulk:Export:MaliciousInjection:Alert:Title' => 'Avviso di sicurezza di Excel',
	'UI:Bulk:Export:MaliciousInjection:Alert:Message' => 'L\'apertura di un file con dati non fidati in Microsoft Excel potrebbe comportare l\'iniezione di formule. Assicurati che le impostazioni di Excel siano configurate per gestire i file in modo sicuro. <a href="%1$s" target="_blank">Ulteriori informazioni nella nostra documentazione.</a>',
	'UI:Bulk:Export:MaliciousInjection:Sanitization:Alert:Message' => 'Some values have been sanitized to prevent potential security issues in Microsoft Excel. <a href="%1$s" target="_blank">Learn more in our documentation.</a>~~',
	'UI:Bulk:Export:MaliciousInjection:Input:Label' => 'Sanitize potentially dangerous values~~',
	'UI:Bulk:Export:MaliciousInjection:Input:Tooltip' => 'When enabled, potentially dangerous values will be sanitized during export. This will prevent Microsoft Excel from interpreting them as formulas. Note that this may alter the original data by prefixing it with a single quote (\') to ensure it is treated as text.~~',
	'Core:BulkExport:Security' => 'Security~~',
]);
