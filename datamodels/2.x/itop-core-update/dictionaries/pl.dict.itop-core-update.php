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
Dict::Add('PL PL', 'Polish', 'Polski', array(
	'iTopUpdate:UI:PageTitle' => 'Aktualizacja aplikacji',
    'itop-core-update:UI:SelectUpdateFile' => 'Aktualizacja aplikacji',
    'itop-core-update:UI:ConfirmUpdate' => 'Potwierdź aktualizację aplikacji',
    'itop-core-update:UI:UpdateCoreFiles' => 'Aktualizacja aplikacji',
	'iTopUpdate:UI:MaintenanceModeActive' => 'Aplikacja jest obecnie w trakcie konserwacji w trybie tylko do odczytu. Musisz uruchomić konfigurację, aby powrócić do normalnego trybu.',
	'itop-core-update:UI:UpdateDone' => 'Aktualizacja aplikacji',

	'itop-core-update/Operation:SelectUpdateFile/Title' => 'Aktualizacja aplikacji',
	'itop-core-update/Operation:ConfirmUpdate/Title' => 'Potwierdź aktualizację aplikacji',
	'itop-core-update/Operation:UpdateCoreFiles/Title' => 'Aktualizacja aplikacji',
	'itop-core-update/Operation:UpdateDone/Title' => 'Aktualizacja aplikacji zakończona',

	'iTopUpdate:UI:SelectUpdateFile' => 'Wybierz plik aktualizacji do przesłania',
	'iTopUpdate:UI:CheckUpdate' => 'Weryfikacja pliku aktualizacji',
	'iTopUpdate:UI:ConfirmInstallFile' => 'Masz zamiar zainstalować %1$s',
	'iTopUpdate:UI:DoUpdate' => 'Aktualizacja',
	'iTopUpdate:UI:CurrentVersion' => 'Aktualnie zainstalowana wersja',
	'iTopUpdate:UI:NewVersion' => 'Nowo zainstalowana wersja',
    'iTopUpdate:UI:Back' => 'Wstecz',
    'iTopUpdate:UI:Cancel' => 'Anuluj',
    'iTopUpdate:UI:Continue' => 'Kontynuuj',
	'iTopUpdate:UI:RunSetup' => 'Uruchom instalację',
    'iTopUpdate:UI:WithDBBackup' => 'Kopia zapasowa bazy danych',
    'iTopUpdate:UI:WithFilesBackup' => 'Kopia zapasowa plików aplikacji',
    'iTopUpdate:UI:WithoutBackup' => 'Nie ma zaplanowanych kopii zapasowych',
    'iTopUpdate:UI:Backup' => 'Kopia zapasowa wygenerowana przed aktualizacją',
	'iTopUpdate:UI:DoFilesArchive' => 'Archiwizuj pliki aplikacji',
	'iTopUpdate:UI:UploadArchive' => 'Wybierz pakiet do przesłania',
	'iTopUpdate:UI:ServerFile' => 'Ścieżka pakietu znajdującego się na serwerze',
	'iTopUpdate:UI:WarningReadOnlyDuringUpdate' => 'Podczas aktualizacji aplikacja będzie tylko do odczytu.',

	'iTopUpdate:UI:Status' => 'Status',
	'iTopUpdate:UI:Action' => 'Aktualizacja',
	'iTopUpdate:UI:History' => 'Historia wersji',
	'iTopUpdate:UI:Progress' => 'Progress of the upgrade',

	'iTopUpdate:UI:DoBackup:Label' => 'Kopie zapasowe plików i bazy danych',
	'iTopUpdate:UI:DoBackup:Warning' => 'Tworzenie kopii zapasowych nie jest zalecane ze względu na ograniczoną ilość wolnego miejsca na dysku',

	'iTopUpdate:UI:DiskFreeSpace' => 'Wolne miejsce na dysku',
	'iTopUpdate:UI:ItopDiskSpace' => 'Przestrzeń dyskowa  '.ITOP_APPLICATION_SHORT,
	'iTopUpdate:UI:DBDiskSpace' => 'Przestrzeń dyskowa bazy danych',
	'iTopUpdate:UI:FileUploadMaxSize' => 'Maksymalny rozmiar przesyłanego pliku',

	'iTopUpdate:UI:PostMaxSize' => 'Wartość PHP ini post_max_size: %1$s',
	'iTopUpdate:UI:UploadMaxFileSize' => 'Wartość PHP ini upload_max_filesize: %1$s',

	'iTopUpdate:UI:CanCoreUpdate:Loading' => 'Sprawdzanie plików',
	'iTopUpdate:UI:CanCoreUpdate:Error' => 'Sprawdzanie plików nie powiodło się (%1$s)',
	'iTopUpdate:UI:CanCoreUpdate:ErrorFileNotExist' => 'Sprawdzanie plików nie powiodło się (plik nie istnieje %1$s)',
	'iTopUpdate:UI:CanCoreUpdate:Failed' => 'Sprawdzanie plików nie powiodło się',
    'iTopUpdate:UI:CanCoreUpdate:Yes' => 'Aplikacja może być zaktualizowana',
	'iTopUpdate:UI:CanCoreUpdate:No' => 'Nie można zaktualizować aplikacji: %1$s',
	'iTopUpdate:UI:CanCoreUpdate:Warning' => 'Ostrzeżenie: aktualizacja aplikacji może się nie powieść: %1$s',
	'iTopUpdate:UI:CannotUpdateUseSetup' => 'Aby zaktualizować aplikację, musisz skorzystać ze strony <a href="%1$s">setup</a>.<br />Wykryto niektóre zmodyfikowane pliki, częściowej aktualizacji nie można przeprowadzić.',

	// Setup Messages
    'iTopUpdate:UI:SetupMessage:Ready' => 'Gotowy do startu',
	'iTopUpdate:UI:SetupMessage:EnterMaintenance' => 'Wejście w tryb konserwacji',
	'iTopUpdate:UI:SetupMessage:Backup' => 'Kopia zapasowa bazy danych',
	'iTopUpdate:UI:SetupMessage:FilesArchive' => 'Archiwizacja pliki aplikacji',
    'iTopUpdate:UI:SetupMessage:CopyFiles' => 'Kopiowanie plików nowej wersji',
	'iTopUpdate:UI:SetupMessage:CheckCompile' => 'Sprawdzenie aktualizacji aplikacji',
	'iTopUpdate:UI:SetupMessage:Compile' => 'Aktualizacja aplikacji',
	'iTopUpdate:UI:SetupMessage:UpdateDatabase' => 'Aktualizacja bazy danych',
	'iTopUpdate:UI:SetupMessage:ExitMaintenance' => 'Wyjście z trybu konserwacji',
    'iTopUpdate:UI:SetupMessage:UpdateDone' => 'Aktualizacja zakończona',

	// Errors
	'iTopUpdate:Error:MissingFunction' => 'Niemożliwe rozpoczęcie aktualizacji, brak funkcji',
	'iTopUpdate:Error:MissingFile' => 'Brakujący plik: %1$s',
	'iTopUpdate:Error:CorruptedFile' => 'Plik %1$s jest uszkodzony',
    'iTopUpdate:Error:BadFileFormat' => 'Plik aktualizacji nie jest plikiem ZIP',
    'iTopUpdate:Error:BadFileContent' => 'Plik aktualizacji nie jest archiwum aplikacji',
    'iTopUpdate:Error:BadItopProduct' => 'Plik aktualizacji nie jest zgodny z twoją aplikacją',
	'iTopUpdate:Error:Copy' => 'Błąd, nie można skopiować \'%1$s\' do \'%2$s\'',
    'iTopUpdate:Error:FileNotFound' => 'Nie znaleziono pliku',
    'iTopUpdate:Error:NoFile' => 'Brak pliku',
	'iTopUpdate:Error:InvalidToken' => 'Nieprawidłowy Token',
	'iTopUpdate:Error:UpdateFailed' => 'Aktualizacja nie powiodła się ',
	'iTopUpdate:Error:FileUploadMaxSizeTooSmall' => 'Maksymalny rozmiar przesyłania wydaje się za mały do aktualizacji. Zmień konfigurację PHP.',

	'iTopUpdate:UI:RestoreArchive' => 'Możesz przywrócić swoją aplikację z archiwum \'%1$s\'',
	'iTopUpdate:UI:RestoreBackup' => 'Możesz przywrócić bazę danych z pliku \'%1$s\'',
	'iTopUpdate:UI:UpdateDone' => 'Aktualizacja powiodła się',
	'Menu:iTopUpdate' => 'Aktualizacja aplikacji',
	'Menu:iTopUpdate+' => 'Aktualizacja aplikacji',

    // Missing itop entries
    'Class:ModuleInstallation/Attribute:installed' => 'Zainstalowano',
    'Class:ModuleInstallation/Attribute:name' => 'Nazwa',
    'Class:ModuleInstallation/Attribute:version' => 'Wersja',
    'Class:ModuleInstallation/Attribute:comment' => 'Komentarz',
));


