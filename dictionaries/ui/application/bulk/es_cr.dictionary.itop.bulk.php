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
	'UI:Bulk:modify:IncompatibleAttribute' => 'Este atributo no se puede editar en contexto masivo',
	'UI:Bulk:Export:MaliciousInjection:Alert:Title' => 'Advertencia de seguridad de Excel',
	'UI:Bulk:Export:MaliciousInjection:Alert:Message' => 'Abrir un archivo con datos que no son de confianza en Microsoft Excel puede provocar la inyección de fórmulas. Asegúrese de que la configuración de Excel esté configurada para manejar archivos de forma segura. <a href="%1$s">Obtenga más información en nuestra documentación.</a>',
	'UI:Bulk:Export:MaliciousInjection:Input:Label' => 'Sanitize potentially dangerous values~~',
	'UI:Bulk:Export:MaliciousInjection:Input:Tooltip' => 'When enabled, potentially dangerous values will be sanitized during export. This will prevent Microsoft Excel from interpreting them as formulas. Note that this may alter the original data by prefixing it with a single quote (\') to ensure it is treated as text.~~',
	'Core:BulkExport:Security' => 'Security~~',
]);
