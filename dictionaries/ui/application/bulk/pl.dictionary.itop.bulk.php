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
Dict::Add('PL PL', 'Polish', 'Polski', [
	'UI:Bulk:modify:IncompatibleAttribute' => 'Tego atrybutu nie można edytować zbiorczo',
	'UI:Bulk:Export:MaliciousInjection:Alert:Title' => 'Ostrzeżenie dotyczące bezpieczeństwa programu Excel',
	'UI:Bulk:Export:MaliciousInjection:Alert:Message' => 'Otwarcie pliku z niezaufanymi danymi w programie Microsoft Excel może spowodować wstrzyknięcie formuły. Upewnij się, że ustawienia programu Excel są skonfigurowane tak, aby bezpiecznie obsługiwać pliki. <a href="%1$s">Dowiedz się więcej w naszej dokumentacji.</a>',
	'UI:Bulk:Export:MaliciousInjection:Input:Label' => 'Sanitize potentially dangerous values~~',
	'UI:Bulk:Export:MaliciousInjection:Input:Tooltip' => 'When enabled, potentially dangerous values will be sanitized during export. This will prevent Microsoft Excel from interpreting them as formulas. Note that this may alter the original data by prefixing it with a single quote (\') to ensure it is treated as text.~~',
	'Core:BulkExport:Security' => 'Security~~',
]);
