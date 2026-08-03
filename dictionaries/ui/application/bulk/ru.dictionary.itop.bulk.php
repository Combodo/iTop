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
Dict::Add('RU RU', 'Russian', 'Русский', [
	'UI:Bulk:modify:IncompatibleAttribute' => 'Этот атрибут нельзя редактировать при массовом изменении',
	'UI:Bulk:Export:MaliciousInjection:Alert:Title' => 'Предупреждение безопасности Excel',
	'UI:Bulk:Export:MaliciousInjection:Alert:Message' => 'Открытие файла с недоверенными данными в Microsoft Excel может привести к внедрению вредоносных формул. Убедитесь, что настройки Excel безопасно обрабатывают такие файлы. <a href="%1$s" target="_blank">Подробнее в нашей документации.</a>',
	'UI:Bulk:Export:MaliciousInjection:Sanitization:Alert:Message' => 'Некоторые значения были очищены для предотвращения потенциальных проблем безопасности в Microsoft Excel. <a href="%1$s" target="_blank">Подробнее в нашей документации.</a>',
	'UI:Bulk:Export:MaliciousInjection:Input:Label' => 'Очищать потенциально опасные значения',
	'UI:Bulk:Export:MaliciousInjection:Input:Tooltip' => 'При включении потенциально опасные значения будут очищены при экспорте. Это не позволит Microsoft Excel интерпретировать их как формулы. Обратите внимание, что при этом исходные данные могут быть изменены добавлением одинарной кавычки (\') в начале, чтобы значение обрабатывалось как текст.',
	'Core:BulkExport:Security' => 'Безопасность',
]);
