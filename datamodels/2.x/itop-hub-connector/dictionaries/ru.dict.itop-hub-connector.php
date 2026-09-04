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
	'Menu:iTopHub' => 'iTop Hub',
	'Menu:iTopHub:Register' => 'Подключение к iTop Hub',
	'Menu:iTopHub:Register+' => 'Перейдите в iTop Hub, чтобы обновить ваш экземпляр '.ITOP_APPLICATION_SHORT,
	'Menu:iTopHub:Register:Description' => '<p>Получите доступ к вашей платформе сообщества iTop Hub!<br>Найдите весь необходимый контент и информацию, управляйте своими инстансами через персонализированные инструменты и устанавливайте дополнительные расширения.<br><br>Подключившись к Hub с этой страницы, вы отправите информацию об этом инстансе '.ITOP_APPLICATION_SHORT.' в Hub.</p>',
	'Menu:iTopHub:MyExtensions' => 'Установленные расширения',
	'Menu:iTopHub:MyExtensions+' => 'Расширения, развернутые на данном экземпляре '.ITOP_APPLICATION_SHORT,
	'Menu:iTopHub:BrowseExtensions' => 'Получить расширения из iTop Hub',
	'Menu:iTopHub:BrowseExtensions+' => 'Найдите дополнительные расширения на iTop Hub',
	'Menu:iTopHub:BrowseExtensions:Description' => '<p>Look into iTop Hub’s store, your one stop place to find wonderful iTop extensions !<br>Find the ones that will help you customize and adapt '.ITOP_APPLICATION_SHORT.' to your processes.<br><br>By connecting to the Hub from this page, you will push information about this '.ITOP_APPLICATION_SHORT.' instance into the Hub.</p>',
	'iTopHub:GoBtn' => 'Перейти в iTop Hub',
	'iTopHub:CloseBtn' => 'Закрыть',
	'iTopHub:GoBtn:Tooltip' => 'Перейти на www.itophub.io',
	'iTopHub:OpenInNewWindow' => 'Открыть iTop Hub в новом окне',
	'iTopHub:AutoSubmit' => 'Больше не спрашивать. В следующий раз переходить в iTop Hub автоматически.',
	'UI:About:RemoteExtensionSource' => 'iTop Hub',
	'iTopHub:Explanation' => 'При нажатии на эту кнопку вы будете перенаправлены в iTop Hub.',
	'iTopHub:BackupFreeDiskSpaceIn' => 'Свободно места на диске: %1$s в %2$s.',
	'iTopHub:FailedToCheckFreeDiskSpace' => 'Не удалось проверить свободное место на диске.',
	'iTopHub:BackupOk' => 'Резервная копия создана успешно.',
	'iTopHub:BackupFailed' => 'Ошибка создания резервной копии!',
	'iTopHub:Landing:Status' => 'Статус развёртывания',
	'iTopHub:Landing:Install' => 'Развёртывание расширений…',
	'iTopHub:CompiledOK' => 'Компиляция выполнена успешно.',
	'iTopHub:ConfigurationSafelyReverted' => 'При развёртывании обнаружена ошибка!<br>Конфигурация '.ITOP_APPLICATION_SHORT.' НЕ была изменена.',
	'iTopHub:FailAuthent' => 'Не удалось выполнить аутентификацию для этого действия.',
	'iTopHub:InstalledExtensions' => 'Расширения, развёрнутые в этом инстансе',
	'iTopHub:ExtensionCategory:Manual' => 'Расширения, развёрнутые вручную',
	'iTopHub:ExtensionCategory:Manual+' => 'Следующие расширения были развёрнуты вручную копированием в каталог %1$s '.ITOP_APPLICATION_SHORT.':',
	'iTopHub:ExtensionCategory:Remote' => 'Расширения, развёрнутые из iTop Hub',
	'iTopHub:ExtensionCategory:Remote+' => 'Следующие расширения были развёрнуты из iTop Hub:',
	'iTopHub:NoExtensionInThisCategory' => 'В этой категории нет расширений',
	'iTopHub:NoExtensionInThisCategory+' => 'Просмотрите iTop Hub, чтобы найти расширения, которые помогут настроить и адаптировать '.ITOP_APPLICATION_SHORT.' под ваши процессы!',
	'iTopHub:ExtensionNotInstalled' => 'Не установлено',
	'iTopHub:GetMoreExtensions' => 'Получить расширения из iTop Hub…',
	'iTopHub:LandingWelcome' => 'Поздравляем! Следующие расширения были загружены из iTop Hub и развёрнуты в вашем '.ITOP_APPLICATION_SHORT.'.',
	'iTopHub:GoBackToITopBtn' => 'Вернуться в '.ITOP_APPLICATION_SHORT,
	'iTopHub:Uncompressing' => 'Распаковка расширений…',
	'iTopHub:InstallationWelcome' => 'Установка расширений, загруженных из iTop Hub',
	'iTopHub:DBBackupLabel' => 'Резервная копия инстанса',
	'iTopHub:DBBackupSentence' => 'Сделайте резервную копию базы данных и конфигурации '.ITOP_APPLICATION_SHORT.' перед обновлением',
	'iTopHub:DeployBtn' => 'Развернуть!',
	'iTopHub:DatabaseBackupProgress' => 'Резервное копирование инстанса…',
	'iTopHub:InstallationEffect:Install' => 'Версия %1$s будет установлена.',
	'iTopHub:InstallationEffect:NoChange' => 'Версия %1$s уже установлена. Ничего не изменится.',
	'iTopHub:InstallationEffect:Upgrade' => 'Будет <b>обновлено</b> с версии %1$s до версии %2$s.',
	'iTopHub:InstallationEffect:Downgrade' => 'Версия будет <b>ПОНИЖЕНА</b> с %1$s до %2$s.',
	'iTopHub:InstallationProgress:DatabaseBackup' => 'Резервное копирование инстанса '.ITOP_APPLICATION_SHORT.'…',
	'iTopHub:InstallationProgress:ExtensionsInstallation' => 'Установка расширений',
	'iTopHub:InstallationEffect:MissingDependencies' => 'Это расширение нельзя установить из-за невыполненных зависимостей.',
	'iTopHub:InstallationEffect:MissingDependencies_Details' => 'Расширению требуются модули: %1$s',
	'iTopHub:InstallationProgress:InstallationSuccessful' => 'Установка выполнена успешно!',
	'iTopHub:InstallationStatus:Installed_Version' => '%1$s версия: %2$s.',
	'iTopHub:InstallationStatus:Installed' => 'Установлено',
	'iTopHub:InstallationStatus:Version_NotInstalled' => 'Версия %1$s <b>НЕ</b> установлена.',
]);
