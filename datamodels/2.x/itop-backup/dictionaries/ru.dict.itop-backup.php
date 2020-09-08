<?php
/**
 * Локализация интерфейса Combodo iTop подготовлена сообществом iTop по-русски http://community.itop-itsm.ru.
 *
 * @author      Vladimir Kunin <v.b.kunin@gmail.com>
 * @link        http://community.itop-itsm.ru  iTop Russian Community
 * @link        https://github.com/itop-itsm-ru/itop-rus
 * @license     http://opensource.org/licenses/AGPL-3.0
 *
 */
Dict::Add('RU RU', 'Russian', 'Русский', array(

	'bkp-backup-running' => 'Выполняется резервное копирование. Пожалуйста, подождите...',
	'bkp-restore-running' => 'Выполняется восстановление из резервной копии. Пожалуйста, подождите...',

	'Menu:BackupStatus' => 'Резервное копирование',
	'bkp-status-title' => 'Резервное копирование по расписанию',
	'bkp-status-checks' => 'Настройки и проверки',
	'bkp-mysqldump-ok' => 'Утилита mysqldump найдена: %1$s',
	'bkp-mysqldump-notfound' => 'Утилиту mysqldump найти не удалось: %1$s - пожалуйста, убедитесь в том, что она установлена, и путь до директории с бинарными файлами добавлен в PATH, либо измените параметр mysql_bindir в файле конфигурации.',
	'bkp-mysqldump-issue' => 'Утилита mysqldump на может быть запущена (retcode=%1$d) Пожалуйста, убедитесь в том, что она установлена, и путь до директории с бинарными файлами добавлен в PATH, либо измените параметр mysql_bindir в файле конфигурации.',
	'bkp-missing-dir' => 'The target directory %1$s count not be found',
	'bkp-free-disk-space' => '<b>%1$s свободно</b> в %2$s',
	'bkp-dir-not-writeable' => '%1$s недоступен для записи',
	'bkp-wrong-format-spec' => 'Неправильный формат шаблона названия файлов резервных копий (%1$s). Будет использован шаблон по умолчанию: %2$s',
	'bkp-name-sample' => 'Название файлов резервных копий зависит от идентификатора БД, даты и времени. Пример: %1$s',
	'bkp-week-days' => 'Резервное копирование будет выполняться <b>каждый %1$s в %2$s</b>',
	'bkp-retention' => 'Не более <b>%1$d файлов резервных копий будут храниться</b> в целевом каталоге.',
	'bkp-next-to-delete' => 'Будет удалена при следующем запуске резервного копирования (см. параметр \\"retention_count\\")',
	'bkp-table-file' => 'Файл',
	'bkp-table-file+' => 'Только файлы с расширением .zip считаются файлами резервных копий.',
	'bkp-table-size' => 'Размер',
	'bkp-table-size+' => '',
	'bkp-table-actions' => 'Действия',
	'bkp-table-actions+' => '',
	'bkp-status-backups-auto' => 'Резервное копирование по расписанию',
	'bkp-status-backups-manual' => 'Резервное копирование вручную',
	'bkp-status-backups-none' => 'Резервных копий ещё нет',
	'bkp-next-backup' => 'Следующее резервное копирование будет выполняться в <b>%1$s</b> (%2$s) в %3$s',
	'bkp-button-backup-now' => 'Запустить сейчас!',
	'bkp-button-restore-now' => 'Восстановить!',
	'bkp-confirm-backup' => 'Пожалуйста, подтвердите, что вы хотите выполнить резервное копирование прямо сейчас.',
	'bkp-confirm-restore' => 'Пожалуйста, подтвердите, что вы хотите выполнить восстановление из резервной копии %1$s.',
	'bkp-wait-backup' => 'Пожалуйста, дождитесь завершения резервного копирования...',
	'bkp-wait-restore' => 'Пожалуйста, дождитесь завершения восстановления...',
	'bkp-success-restore' => 'Восстановление успешно завершено.',
));
