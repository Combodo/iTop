<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2018 Combodo SARL
 * @license    http://opensource.org/licenses/AGPL-3.0
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

Dict::Add('PL PL', 'Polish', 'Polski', array(
	// Dictionary entries go here
	'Menu:iTopHub' => 'iTop Hub',
	'Menu:iTopHub:Register' => 'Połącz się z iTop Hub',
	'Menu:iTopHub:Register+' => 'Przejdź do iTop Hub, aby zaktualizować swoją instancję '.ITOP_APPLICATION_SHORT,
	'Menu:iTopHub:Register:Description' => '<p>Uzyskaj dostęp do swojej platformy społecznościowej iTop Hub!</br>Znajdź wszystkie potrzebne treści i informacje, zarządzaj swoimi instancjami za pomocą spersonalizowanych narzędzi i zainstaluj więcej rozszerzeń.</br><br/>Łącząc się z Centrum z tej strony, będziesz przesyłać informacje o tej instancji iTop do Centrum.</p>',
	'Menu:iTopHub:MyExtensions' => 'Wdrożone rozszerzenia',
	'Menu:iTopHub:MyExtensions+' => 'Zobacz listę rozszerzeń wdrożonych w tej instancji '.ITOP_APPLICATION_SHORT,
	'Menu:iTopHub:BrowseExtensions' => 'Pobierz rozszerzenia z iTop Hub',
	'Menu:iTopHub:BrowseExtensions+' => 'Wyszukaj więcej rozszerzeń w iTop Hub',
	'Menu:iTopHub:BrowseExtensions:Description' => '<p>Zajrzyj do sklepu iTop Hub, jedynego miejsca, w którym można znaleźć wspaniałe rozszerzenia iTop!</br>Znajdź te, które pomogą Ci dostosować i dostosować iTop do Twoich procesów.</br><br/>Łącząc się z Centrum z tej strony, będziesz przesyłać informacje o tej instancji iTop do Centrum.</p>',
	'iTopHub:GoBtn' => 'Przejdź do iTop Hub',
	'iTopHub:CloseBtn' => 'Zamknij',
	'iTopHub:GoBtn:Tooltip' => 'Idź do www.itophub.io',
	'iTopHub:OpenInNewWindow' => 'Otwórz iTop Hub w nowym oknie',
	'iTopHub:AutoSubmit' => 'Nie pytaj mnie ponownie. Następnym razem przejdź automatycznie do iTop Hub.',
	'UI:About:RemoteExtensionSource' => 'iTop Hub',
	'iTopHub:Explanation' => 'Kliknięcie tego przycisku spowoduje przekierowanie do iTop Hub.',

	'iTopHub:BackupFreeDiskSpaceIn' => '%1$s wolne miejsce na dysku w %2$s.',
	'iTopHub:FailedToCheckFreeDiskSpace' => 'Nie udało się sprawdzić wolnego miejsca na dysku.',
	'iTopHub:BackupOk' => 'Kopia zapasowa Ok.',
	'iTopHub:BackupFailed' => 'Kopia zapasowa nie powiodła się!',
	'iTopHub:Landing:Status' => 'Stan wdrożenia',
	'iTopHub:Landing:Install' => 'Wdrażanie rozszerzeń...',
	'iTopHub:CompiledOK' => 'Kompilacja pomyślna.',
	'iTopHub:ConfigurationSafelyReverted' => 'Wykryto błąd podczas wdrażania!<br/>Konfiguracja '.ITOP_APPLICATION_SHORT.' NIE została zmodyfikowana.',
	'iTopHub:FailAuthent' => 'Uwierzytelnianie nie powiodło się dla tej akcji.',

	'iTopHub:InstalledExtensions' => 'Rozszerzenia wdrożone w tej instancji',
	'iTopHub:ExtensionCategory:Manual' => 'Rozszerzenia wdrażane ręcznie',
	'iTopHub:ExtensionCategory:Manual+' => 'Następujące rozszerzenia zostały wdrożone przez ręczne skopiowanie ich do katalogu %1$s programu '.ITOP_APPLICATION_SHORT.':',
	'iTopHub:ExtensionCategory:Remote' => 'Rozszerzenia wdrożone z iTop Hub',
	'iTopHub:ExtensionCategory:Remote+' => 'Następujące rozszerzenia zostały wdrożone z iTop Hub:',
	'iTopHub:NoExtensionInThisCategory' => 'W tej kategorii nie ma rozszerzenia.<br/><br/>Przeglądaj iTop Hub, aby znaleźć rozszerzenia, które pomogą Ci dostosować i dostosować '.ITOP_APPLICATION_SHORT.' do Twoich procesów.',
	'iTopHub:ExtensionNotInstalled' => 'Nie zainstalowane',
	'iTopHub:GetMoreExtensions' => 'Pobierz rozszerzenia z iTop Hub...',

	'iTopHub:LandingWelcome' => 'Gratulacje! Następujące rozszerzenia zostały pobrane z iTop Hub i wdrożone w '.ITOP_APPLICATION_SHORT.'.',
	'iTopHub:GoBackToITopBtn' => 'Wróć do '.ITOP_APPLICATION_SHORT,
	'iTopHub:Uncompressing' => 'Rozpakowywanie rozszerzeń...',
	'iTopHub:InstallationWelcome' => 'Instalacja rozszerzeń pobranych z iTop Hub',
	'iTopHub:DBBackupLabel' => 'Kopia zapasowa instancji',
	'iTopHub:DBBackupSentence' => 'Przed aktualizacją wykonaj kopię zapasową bazy danych i konfiguracji '.ITOP_APPLICATION_SHORT,
	'iTopHub:DeployBtn' => 'Wykonaj !',
	'iTopHub:DatabaseBackupProgress' => 'Kopia zapasowa instancji...',

	'iTopHub:InstallationEffect:Install' => 'Wersja: %1$s zostanie zainstalowana.',
	'iTopHub:InstallationEffect:NoChange' => 'Wersja: %1$s jest zainstalowana. Nic się nie zmieni.',
	'iTopHub:InstallationEffect:Upgrade' => 'Zostanie <b>uaktualniony</b> z wersji %1$s do wersji %2$s.',
	'iTopHub:InstallationEffect:Downgrade' => 'Zostanie <b>ZDEGRADOWANY</b> z wersji %1$s do wersji %2$s.',
	'iTopHub:InstallationProgress:DatabaseBackup' => 'Kopia zapasowa instancji '.ITOP_APPLICATION_SHORT.'...',
	'iTopHub:InstallationProgress:ExtensionsInstallation' => 'Instalacja rozszerzeń',
	'iTopHub:InstallationEffect:MissingDependencies' => 'Nie można zainstalować tego rozszerzenia z powodu niespełnionych zależności.',
	'iTopHub:InstallationEffect:MissingDependencies_Details' => 'Rozszerzenie wymaga modułu(ów): %1$s',
	'iTopHub:InstallationProgress:InstallationSuccessful' => 'Instalacja zakończyła się sukcesem!',

	'iTopHub:InstallationStatus:Installed_Version' => '%1$s wersja: %2$s.',
	'iTopHub:InstallationStatus:Installed' => 'Zainstalowana',
	'iTopHub:InstallationStatus:Version_NotInstalled' => 'Wersja %1$s <b>NIE</b> zainstalowana.',
));


