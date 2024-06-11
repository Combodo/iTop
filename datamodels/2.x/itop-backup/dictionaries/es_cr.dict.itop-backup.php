<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * 
 */
/**
 * @author Miguel Turrubiates <miguel_tf@yahoo.com>
 *
 */
Dict::Add('ES CR', 'Spanish', 'Español, Castellano', [
	'Menu:BackupStatus' => 'Respaldos programados',
	'bkp-backup-running' => 'Un respaldo está en ejecuión.  Por favor espere...',
	'bkp-button-backup-now' => 'Respaldar Ahora!',
	'bkp-button-restore-now' => 'Restaurar!',
	'bkp-confirm-backup' => 'Por favor confirme que requiere realizar el respaldo en este momento.',
	'bkp-confirm-restore' => 'Por favor confirme que desea restaurar el respaldo %1$s.',
	'bkp-dir-not-writeable' => '%1$s no es escribible',
	'bkp-free-disk-space' => '<b>%1$s libre</b> in <code>%2$s</code>',
	'bkp-missing-dir' => 'El directorio destino <code>%1$s</code> no puede ser encontrado',
	'bkp-mysqldump-issue' => 'mysqldump no puede ejecutarse (retcode=%1$d): Por favor asegurese que esté instalado en el "path", o edite el archivo de configuración para ajustar mysql_bindir.',
	'bkp-mysqldump-notfound' => 'mysqldump no pudo ser encontrado: %1$s - Por favor asegurese que esté instalado en el "path", o edite el archivo de configuración para ajustar mysql_bindir.',
	'bkp-mysqldump-ok' => 'mysqldump está presente: %1$s',
	'bkp-name-sample' => 'Los archivos de respaldo son nombrados dependiento de identificadores de BD, fecha y hora. Ejemplo: %1$s',
	'bkp-next-backup' => 'El siguiente respaldo ocurrirá el <b>%1$s</b> (%2$s) a %3$s',
	'bkp-next-backup-unknown' => 'El siguiente respaldo <b>no está programado</b> todavía.',
	'bkp-next-to-delete' => 'Serán borrados cuando el siguiente respaldo ocurra (ver configuración "retention_count")',
	'bkp-restore-running' => 'Una restauración está en ejecución. Por favor espere...',
	'bkp-retention' => 'Al menos <b>%1$d archivos de respaldo serán conservados</b> en el directorio destino.',
	'bkp-status-backups-auto' => 'Respaldos Programados',
	'bkp-status-backups-manual' => 'Respaldos Manuales',
	'bkp-status-backups-none' => 'No hay respaldos',
	'bkp-status-checks' => 'Configuraciones y verificaciones',
	'bkp-status-title' => 'Respaldos Programados',
	'bkp-success-restore' => 'Restauración completada exitosamente.',
	'bkp-table-actions' => 'Acciones',
	'bkp-table-actions+' => '',
	'bkp-table-file' => 'Archivo',
	'bkp-table-file+' => 'Solo archivos con la extensión .zip son considerados como archivos de respaldos',
	'bkp-table-size' => 'Tamaño',
	'bkp-table-size+' => '',
	'bkp-wait-backup' => 'Por favor espera a que se complete el respaldo...',
	'bkp-wait-restore' => 'Por favor espera a que se complete la restauración...',
	'bkp-week-days' => 'Respaldos se realizaran <b>cada %1$s a %2$s</b>',
	'bkp-wrong-format-spec' => 'La actual especificación para el formato de nombre de archivo es erróneo (%1$s). Una especifiación por omisión se aplicará: %2$s',
]);
