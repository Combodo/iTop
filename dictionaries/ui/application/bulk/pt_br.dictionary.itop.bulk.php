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
Dict::Add('PT BR', 'Brazilian', 'Brazilian', [
	'UI:Bulk:modify:IncompatibleAttribute' => 'This attribute can\'t be edited in bulk context~~',
	'UI:Bulk:Export:MaliciousInjection:Alert:Title' => 'Excel security warning~~',
	'UI:Bulk:Export:MaliciousInjection:Alert:Message' => 'Opening a file with untrusted data in Microsoft Excel may lead to formula injection. Ensure that your Excel settings are configured to handle files safely. <a href="%1$s" target="_blank">Learn more in our documentation.</a>~~',
	'UI:Bulk:Export:MaliciousInjection:Sanitization:Alert:Message' => 'Some values have been sanitized to prevent potential security issues in Microsoft Excel. <a href="%1$s" target="_blank">Learn more in our documentation.</a>~~',
	'UI:Bulk:Export:MaliciousInjection:Input:Label' => 'Sanitize potentially dangerous values~~',
	'UI:Bulk:Export:MaliciousInjection:Input:Tooltip' => 'When enabled, potentially dangerous values will be sanitized during export. This will prevent Microsoft Excel from interpreting them as formulas. Note that this may alter the original data by prefixing it with a single quote (\') to ensure it is treated as text.~~',
	'Core:BulkExport:Security' => 'Security~~',
]);
