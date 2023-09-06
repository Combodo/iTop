<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2023 Combodo SARL
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
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'iTopUpdate:UI:PageTitle' => 'Alkalmazásfrissítés',
	'itop-core-update:UI:SelectUpdateFile' => 'Alkalmazásfrissítés',
	'itop-core-update:UI:ConfirmUpdate' => 'Alkalmazásfrissítés',
	'itop-core-update:UI:UpdateCoreFiles' => 'Alkalmazásfrissítés',
	'iTopUpdate:UI:MaintenanceModeActive' => 'Az alkalmazás jelenleg karbantartás alatt áll, egyetlen felhasználó sem tud hozzáférni az alkalmazáshoz. A normál üzemmódba való visszatéréshez telepítést kell futtatnia, vagy vissza kell állítania az alkalmazás archívumát..',
	'itop-core-update:UI:UpdateDone' => 'Alkalmazásfrissítés',
	'itop-core-update/Operation:SelectUpdateFile/Title' => 'Alkalmazásfrissítés',
	'itop-core-update/Operation:ConfirmUpdate/Title' => 'Alkalmazásfrissítés jóváhagyása',
	'itop-core-update/Operation:UpdateCoreFiles/Title' => 'Az alkalmazás frissül',
	'itop-core-update/Operation:UpdateDone/Title' => 'Alkalmazásfrissítés kész',
	'iTopUpdate:UI:SelectUpdateFile' => 'Válasszon egy frissítést a feltöltéshez',
	'iTopUpdate:UI:CheckUpdate' => 'Frissítés ellenőrzése',
	'iTopUpdate:UI:ConfirmInstallFile' => '%1$s lesz telepítve',
	'iTopUpdate:UI:DoUpdate' => 'Frissítés',
	'iTopUpdate:UI:CurrentVersion' => 'Jelenlegi telepített verzió',
	'iTopUpdate:UI:NewVersion' => 'Újonnan telepített verzió',
	'iTopUpdate:UI:Back' => 'Vissza',
	'iTopUpdate:UI:Cancel' => 'Mégsem',
	'iTopUpdate:UI:Continue' => 'Folytatás',
	'iTopUpdate:UI:RunSetup' => 'Telepítés futtatása',
	'iTopUpdate:UI:WithDBBackup' => 'Adatbázis biztonsági mentése',
	'iTopUpdate:UI:WithFilesBackup' => 'Alkalmazás fájlok biztonsági mentése',
	'iTopUpdate:UI:WithoutBackup' => 'Nincs mentés tervbe véve',
	'iTopUpdate:UI:Backup' => 'Frissítés előtt létrehozott biztonsági mentés.',
	'iTopUpdate:UI:DoFilesArchive' => 'Archív alkalmazás fájlok',
	'iTopUpdate:UI:UploadArchive' => 'Válasszon egy csomagot a feltöltéshez',
	'iTopUpdate:UI:ServerFile' => 'A kiszolgálón már meglévő csomag elérési útvonala',
	'iTopUpdate:UI:WarningReadOnlyDuringUpdate' => 'A frissítés során az alkalmazás csak olvasható lesz.',
	'iTopUpdate:UI:Status' => 'Státusz',
	'iTopUpdate:UI:Action' => 'Frissítés',
	'iTopUpdate:UI:Setup' => ITOP_APPLICATION_SHORT.' Setup~~',
	'iTopUpdate:UI:History' => 'Verziótörténet',
	'iTopUpdate:UI:Progress' => 'A frissítés folyamata',
	'iTopUpdate:UI:DoBackup:Label' => 'Mentés fájlok és adatbázis',
	'iTopUpdate:UI:DoBackup:Warning' => 'A biztonsági mentés nem ajánlott a korlátozottan rendelkezésre álló lemezterület miatt.',
	'iTopUpdate:UI:DiskFreeSpace' => 'Lemez szabad terület',
	'iTopUpdate:UI:ItopDiskSpace' => ITOP_APPLICATION_SHORT.' lemezterület',
	'iTopUpdate:UI:DBDiskSpace' => 'Adatbázis lemezterület',
	'iTopUpdate:UI:FileUploadMaxSize' => 'Feltöltés maximális fájlmérete',
	'iTopUpdate:UI:PostMaxSize' => 'PHP ini érték post_max_size: %1$s',
	'iTopUpdate:UI:UploadMaxFileSize' => 'PHP ini érték upload_max_filesize: %1$s',
	'iTopUpdate:UI:CanCoreUpdate:Loading' => 'Fájlrendszer ellenőrzése',
	'iTopUpdate:UI:CanCoreUpdate:Error' => 'Fájlrendszer ellenőrzése sikertelen (%1$s)',
	'iTopUpdate:UI:CanCoreUpdate:ErrorFileNotExist' => 'Fájlrendszer ellenőrzése sikertelen (Fájl nincs meg %1$s)',
	'iTopUpdate:UI:CanCoreUpdate:Failed' => 'Fájlrendszer ellenőrzése sikertelen',
	'iTopUpdate:UI:CanCoreUpdate:Yes' => 'Az alkalmazás frissíthető',
	'iTopUpdate:UI:CanCoreUpdate:No' => 'Az alkalmazás nem frissíthető: %1$s',
	'iTopUpdate:UI:CanCoreUpdate:Warning'          => 'Figyelem: alkalmazás frissítés sikertelen lehet: %1$s',
	'iTopUpdate:UI:CannotUpdateUseSetup'           => '<b>SNéhány módosított fájlt észleltünk</b>, a részleges frissítés nem hajtható végre.</br>Kövesse a <a target="_blank" href="%2$s"> eljárást</a> az iTop manuális frissítéséhez. Az alkalmazás frissítéséhez a <a href="%1$s">setup</a> parancsot kell használnia.',
	'iTopUpdate:UI:CheckInProgress'                => 'Kérjük, várjon az integritás ellenőrzés alatt',
	'iTopUpdate:UI:SetupLaunch'                    => 'Launch '.ITOP_APPLICATION_SHORT.' Setup~~',
	'iTopUpdate:UI:SetupLaunchConfirm'             => 'This will launch '.ITOP_APPLICATION_SHORT.' setup, are you sure?~~',

	// Setup Messages
	'iTopUpdate:UI:SetupMessage:Ready'             => 'Készen állunk',
	'iTopUpdate:UI:SetupMessage:EnterMaintenance'  => 'Karbantartási módba lépés',
	'iTopUpdate:UI:SetupMessage:Backup'            => 'Adatbázis biztonsági mentése',
	'iTopUpdate:UI:SetupMessage:FilesArchive'      => 'Alkalmazás fájlok archiválása',
	'iTopUpdate:UI:SetupMessage:CopyFiles'         => 'Új fájlverziók másolása',
	'iTopUpdate:UI:SetupMessage:CheckCompile'      => 'Alkalmazásfrissítés ellenőrzése',
	'iTopUpdate:UI:SetupMessage:Compile'           => 'Az alkalmazás és az adatbázis frissítése',
	'iTopUpdate:UI:SetupMessage:UpdateDatabase'    => 'Adatbázis frissítés',
	'iTopUpdate:UI:SetupMessage:ExitMaintenance'   => 'Kilépés a karbantartási módból',
	'iTopUpdate:UI:SetupMessage:UpdateDone'        => 'A frissítés befejeződött',

	// Errors
	'iTopUpdate:Error:MissingFunction'             => 'Lehetetlen elindítani a frissítést, hiányzó funkció',
	'iTopUpdate:Error:MissingFile'                 => 'Hiányzó fájl: %1$s',
	'iTopUpdate:Error:CorruptedFile'               => 'A %1$s fájl sérült',
	'iTopUpdate:Error:BadFileFormat'               => 'A frissítési fájl nem zip fájl',
	'iTopUpdate:Error:BadFileContent'              => 'A frissítési fájl nem alkalmazás archívum',
	'iTopUpdate:Error:BadItopProduct'              => 'A frissítési fájl nem kompatibilis az alkalmazással',
	'iTopUpdate:Error:Copy'                        => 'Hiba: \'%1$s\' nem másolható \'%2$s\' -ba',
	'iTopUpdate:Error:FileNotFound'                => 'Nincs meg a fájl',
	'iTopUpdate:Error:NoFile'                      => 'Nincs fájl megadva',
	'iTopUpdate:Error:InvalidToken'                => 'Érvénytelen token',
	'iTopUpdate:Error:UpdateFailed'                => 'Frissítés sikertelen',
	'iTopUpdate:Error:FileUploadMaxSizeTooSmall'   => 'A feltöltés maximális mérete túl kicsinek tűnik a frissítéshez. Kérjük, módosítsa a PHP konfigurációt.',
	'iTopUpdate:UI:RestoreArchive'                 => 'Visszaállíthatja az alkalmazást a \'%1$s\' archívumból',
	'iTopUpdate:UI:RestoreBackup'                  => 'Visszaállíthatja az adatbázist a \'%1$s\' archívumból',
	'iTopUpdate:UI:UpdateDone'                     => 'Frissítés sikeres',
	'Menu:iTopUpdate'                              => 'Alkalmazás frissítés',
	'Menu:iTopUpdate+'                             => 'Alkalmazás frissítés',

	// Missing itop entries
	'Class:ModuleInstallation/Attribute:installed' => 'Telepítve ',
	'Class:ModuleInstallation/Attribute:name'      => 'Név',
	'Class:ModuleInstallation/Attribute:version'   => 'Verzió',
	'Class:ModuleInstallation/Attribute:comment' => 'Megjegyzés',
));


