<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2018 Combodo SARL
 * @license	http://opensource.org/licenses/AGPL-3.0
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with iTop. If not, see <http://www.gnu.org/licenses/>
 */
Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(

	'bkp-backup-running' => 'Un respaldo está en ejecuión.  Por favor espere...',
	'bkp-restore-running' => 'Una restauración está en ejecución. Por favor espere...',

	'Menu:BackupStatus' => 'Respaldos Programados',
	'bkp-status-title' => 'Respaldos Programados',
	'bkp-status-checks' => 'Configuraciones y verificaciones',
	'bkp-mysqldump-ok' => 'mysqldump está presente: %1$s',
	'bkp-mysqldump-notfound' => 'mysqldump no pudo ser encontrado: %1$s - Por favor asegurese que esté instalado en el "path", o edite el archivo de configuración para ajustar mysql_bindir.',
	'bkp-mysqldump-issue' => 'mysqldump no puede ejecutarse (retcode=%1$d): Por favor asegurese que esté instalado en el "path", o edite el archivo de configuración para ajustar mysql_bindir.',
	'bkp-missing-dir' => 'El directorio destino %1$s no puede ser encontrado',
	'bkp-free-disk-space' => '<b>%1$s libre</b> in %2$s',
	'bkp-dir-not-writeable' => '%1$s no es escribible',
	'bkp-wrong-format-spec' => 'La actual especificación para el formato de nombre de archivo es erróneo (%1$s). Una especifiación por omisión se aplicará: %2$s',
	'bkp-name-sample' => 'Los archivos de respaldo son nombrados dependiento de identificadores de BD, fecha y hora. Ejemplo: %1$s',
	'bkp-week-days' => 'Respaldos se realizaran <b>cada %1$s a %2$s</b>',
	'bkp-retention' => 'Al menos <b>%1$d archivos de respaldo serán conservados</b> en el directorio destino.',
	'bkp-next-to-delete' => 'Serán borrados cuando el siguiente respaldo ocurra (ver configuración "retention_count")',
	'bkp-table-file' => 'Archivo',
	'bkp-table-file+' => 'Solo archivos con la extensión .zip son considerados como archivos de respaldos',
	'bkp-table-size' => 'Tamaño',
	'bkp-table-size+' => '',
	'bkp-table-actions' => 'Acciones',
	'bkp-table-actions+' => '',
	'bkp-status-backups-auto' => 'Respaldos Programandos',
	'bkp-status-backups-manual' => 'Respaldos Manuales',
	'bkp-status-backups-none' => 'No hay respaldos',
	'bkp-next-backup' => 'El siguiente respaldo ocurrirá el <b>%1$s</b> (%2$s) a %3$s',
	'bkp-button-backup-now' => 'Respaldar Ahora!',
	'bkp-button-restore-now' => 'Restaurar!',
	'bkp-confirm-backup' => 'Por favor confirme que requiere realizar el respaldo en este momento.',
	'bkp-confirm-restore' => 'Por favor confirme que desea restaurar el respaldo %1$s.',
	'bkp-wait-backup' => 'Por favor espera a que se complete el respaldo...',
	'bkp-wait-restore' => 'Por favor espera a que se complete la restauración...',
	'bkp-success-restore' => 'Restauración completada exitosamente.',
));
