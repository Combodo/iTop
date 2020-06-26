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
	'iTopUpdate:UI:PageTitle' => 'Обновление приложения',
    'itop-core-update:UI:SelectUpdateFile' => 'Обновление',
    'itop-core-update:UI:ConfirmUpdate' => 'Обновление',
    'itop-core-update:UI:UpdateCoreFiles' => 'Обновление',
	'iTopUpdate:UI:MaintenanceModeActive' => 'В настоящее время приложение находится в режиме технического обслуживания, пользователи не могут получить доступ к приложению. Вы должны запустить программу установки или восстановить архив приложения, чтобы вернуться к нормальному режиму.',
	'itop-core-update:UI:UpdateDone' => 'Обновление завершено',

	'itop-core-update/Operation:SelectUpdateFile/Title' => 'Обновление приложения',
	'itop-core-update/Operation:ConfirmUpdate/Title' => 'Подтверждение обновления приложения',
	'itop-core-update/Operation:UpdateCoreFiles/Title' => 'Обновление приложения',
	'itop-core-update/Operation:UpdateDone/Title' => 'Обновление приложения завершено',

	'iTopUpdate:UI:SelectUpdateFile' => 'Выбор файла обновления',
	'iTopUpdate:UI:CheckUpdate' => 'Проверить файл обновления',
	'iTopUpdate:UI:ConfirmInstallFile' => 'Вы собираетесь установить %1$s',
	'iTopUpdate:UI:DoUpdate' => 'Начать обновление',
	'iTopUpdate:UI:CurrentVersion' => 'Текущая версия',
	'iTopUpdate:UI:NewVersion' => 'Новая версия',
    'iTopUpdate:UI:Back' => 'Назад',
    'iTopUpdate:UI:Cancel' => 'Отменть',
    'iTopUpdate:UI:Continue' => 'Продолжить',
	'iTopUpdate:UI:RunSetup' => 'Запустить установку',
    'iTopUpdate:UI:WithDBBackup' => 'Резервная копия базы данных',
    'iTopUpdate:UI:WithFilesBackup' => 'Архив файлов приложения',
    'iTopUpdate:UI:WithoutBackup' => 'Без резервного копирования',
    'iTopUpdate:UI:Backup' => 'Резервное копирование перед обновлением',
	'iTopUpdate:UI:DoFilesArchive' => 'Создать архив файлов приложения',
	'iTopUpdate:UI:UploadArchive' => 'Выбор пакета для загрузки',
	'iTopUpdate:UI:ServerFile' => 'Путь к пакету на сервере',
	'iTopUpdate:UI:WarningReadOnlyDuringUpdate' => 'Во время обновления приложение будет доступно только для чтения.',

    'iTopUpdate:UI:Status' => 'Статус',
    'iTopUpdate:UI:Action' => 'Обновление',
    'iTopUpdate:UI:History' => 'История версий',
    'iTopUpdate:UI:Progress' => 'Ход обновления',

    'iTopUpdate:UI:DoBackup:Label' => 'Создать резервную копию базы данных',
    'iTopUpdate:UI:DoBackup:Warning' => 'Резервное копирование не рекомендуется из-за ограниченного свободного места на диске',

    'iTopUpdate:UI:DiskFreeSpace' => 'Доступное дисковое пространство',
    'iTopUpdate:UI:ItopDiskSpace' => 'Размер приложения',
    'iTopUpdate:UI:DBDiskSpace' => 'Размер базы данных',
	'iTopUpdate:UI:FileUploadMaxSize' => 'Максимальный размер загружаемого файла',

	'iTopUpdate:UI:PostMaxSize' => 'Значение PHP ini post_max_size: %1$s',
	'iTopUpdate:UI:UploadMaxFileSize' => 'Значение PHP ini upload_max_filesize: %1$s',

    'iTopUpdate:UI:CanCoreUpdate:Loading' => 'Проверка файловой системы',
    'iTopUpdate:UI:CanCoreUpdate:Error' => 'Ошибка проверки файловой системы (%1$s)',
    'iTopUpdate:UI:CanCoreUpdate:ErrorFileNotExist' => 'Ошибка проверки файловой системы (файл не существует %1$s)',
    'iTopUpdate:UI:CanCoreUpdate:Failed' => 'Ошибка проверки файловой системы',
    'iTopUpdate:UI:CanCoreUpdate:Yes' => 'Приложение может быть обновлено',
	'iTopUpdate:UI:CanCoreUpdate:No' => 'Приложение не может быть обновлено: %1$s',
	'iTopUpdate:UI:CanCoreUpdate:Warning' => 'Warning: application update can fail: %1$s~~',

	// Setup Messages
    'iTopUpdate:UI:SetupMessage:Ready' => 'Всё готово к началу',
	'iTopUpdate:UI:SetupMessage:EnterMaintenance' => 'Переход в режим технического обслуживания',
	'iTopUpdate:UI:SetupMessage:Backup' => 'Резервное копирование базы данных',
	'iTopUpdate:UI:SetupMessage:FilesArchive' => 'Архивирование файлов приложения',
    'iTopUpdate:UI:SetupMessage:CopyFiles' => 'Копирование файлов обновления',
	'iTopUpdate:UI:SetupMessage:CheckCompile' => 'Проверка обновления',
	'iTopUpdate:UI:SetupMessage:Compile' => 'Обновление приложения',
	'iTopUpdate:UI:SetupMessage:UpdateDatabase' => 'Обновление базы данных',
	'iTopUpdate:UI:SetupMessage:ExitMaintenance' => 'Выход из режима технического обслуживания',
    'iTopUpdate:UI:SetupMessage:UpdateDone' => 'Обновление завершено',

	// Errors
	'iTopUpdate:Error:MissingFunction' => 'Невозможно запустить обновление, функция отсутствует',
	'iTopUpdate:Error:MissingFile' => 'Отсутствует файл: %1$s',
	'iTopUpdate:Error:CorruptedFile' => 'Файл %1$s поврежден',
    'iTopUpdate:Error:BadFileFormat' => 'Файл обновления не является zip-файлом',
    'iTopUpdate:Error:BadFileContent' => 'Файл обновления не является архивом приложения',
    'iTopUpdate:Error:BadItopProduct' => 'Файл обновления не совместим с вашим приложением',
	'iTopUpdate:Error:Copy' => 'Ошибка, не удаётся скопировать \'%1$s\' в \'%2$s\'',
    'iTopUpdate:Error:FileNotFound' => 'Файл не найден',
    'iTopUpdate:Error:NoFile' => 'Нет архива',
	'iTopUpdate:Error:InvalidToken' => 'Недопустимый токен',
	'iTopUpdate:Error:UpdateFailed' => 'Ошибка обновления',
	'iTopUpdate:Error:FileUploadMaxSizeTooSmall' => 'Максимальный размер загрузки недостаточный для обновления. Пожалуйста, измените конфигурацию PHP.',

	'iTopUpdate:UI:RestoreArchive' => 'Вы можете восстановить приложение из архива \'%1$s\'',
	'iTopUpdate:UI:RestoreBackup' => 'Вы можете восстановить базу данных из резервной копии \'%1$s\'',
	'iTopUpdate:UI:UpdateDone' => 'Обновление выполнено успешно',
	'Menu:iTopUpdate' => 'Обновление приложения',
	'Menu:iTopUpdate+' => 'Обновление приложения',

    // Missing itop entries
    'Class:ModuleInstallation/Attribute:installed' => 'Дата установки',
    'Class:ModuleInstallation/Attribute:name' => 'Название',
    'Class:ModuleInstallation/Attribute:version' => 'Версия',
    'Class:ModuleInstallation/Attribute:comment' => 'Комментарий',
));


