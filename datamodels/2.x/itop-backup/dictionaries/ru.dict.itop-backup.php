<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * 
 */
/**
 * @author Vladimir Kunin <v.b.kunin@gmail.com>
 *
 */
Dict::Add('RU RU', 'Russian', 'Русский', [
	'Menu:BackupStatus' => 'Резервное копирование',
	'bkp-backup-running' => 'Выполняется резервное копирование. Пожалуйста, подождите...',
	'bkp-button-backup-now' => 'Запустить сейчас!',
	'bkp-button-restore-now' => 'Восстановить!',
	'bkp-confirm-backup' => 'Пожалуйста, подтвердите, что вы хотите выполнить резервное копирование прямо сейчас.',
	'bkp-confirm-restore' => 'Пожалуйста, подтвердите, что вы хотите выполнить восстановление из резервной копии %1$s.',
	'bkp-dir-not-writeable' => '%1$s недоступен для записи',
	'bkp-free-disk-space' => '<b>%1$s свободно</b> в <code>%2$s</code>',
	'bkp-missing-dir' => 'The target directory <code>%1$s</code> could not be found~~',
	'bkp-mysqldump-issue' => 'Утилита mysqldump на может быть запущена (retcode=%1$d) Пожалуйста, убедитесь в том, что она установлена, и путь до директории с бинарными файлами добавлен в PATH, либо измените параметр mysql_bindir в файле конфигурации.',
	'bkp-mysqldump-notfound' => 'Утилиту mysqldump найти не удалось: %1$s - пожалуйста, убедитесь в том, что она установлена, и путь до директории с бинарными файлами добавлен в PATH, либо измените параметр mysql_bindir в файле конфигурации.',
	'bkp-mysqldump-ok' => 'Утилита mysqldump найдена: %1$s',
	'bkp-name-sample' => 'Название файлов резервных копий зависит от идентификатора БД, даты и времени. Пример: %1$s',
	'bkp-next-backup' => 'Следующее резервное копирование будет выполняться в <b>%1$s</b> (%2$s) в %3$s',
	'bkp-next-backup-unknown' => 'The next backup is <b>not scheduled</b> yet.~~',
	'bkp-next-to-delete' => 'Будет удалена при следующем запуске резервного копирования (см. параметр \"retention_count\")',
	'bkp-restore-running' => 'Выполняется восстановление из резервной копии. Пожалуйста, подождите...',
	'bkp-retention' => 'Не более <b>%1$d файлов резервных копий будут храниться</b> в целевом каталоге.',
	'bkp-status-backups-auto' => 'Резервное копирование по расписанию',
	'bkp-status-backups-manual' => 'Резервное копирование вручную',
	'bkp-status-backups-none' => 'Резервных копий ещё нет',
	'bkp-status-checks' => 'Настройки и проверки',
	'bkp-status-title' => 'Резервное копирование по расписанию',
	'bkp-success-restore' => 'Восстановление успешно завершено.',
	'bkp-table-actions' => 'Действия',
	'bkp-table-actions+' => '',
	'bkp-table-file' => 'Файл',
	'bkp-table-file+' => 'Только файлы с расширением .zip считаются файлами резервных копий.',
	'bkp-table-size' => 'Размер',
	'bkp-table-size+' => '',
	'bkp-wait-backup' => 'Пожалуйста, дождитесь завершения резервного копирования...',
	'bkp-wait-restore' => 'Пожалуйста, дождитесь завершения восстановления...',
	'bkp-week-days' => 'Резервное копирование будет выполняться <b>каждый %1$s в %2$s</b>',
	'bkp-wrong-format-spec' => 'Неправильный формат шаблона названия файлов резервных копий (%1$s). Будет использован шаблон по умолчанию: %2$s',
]);
