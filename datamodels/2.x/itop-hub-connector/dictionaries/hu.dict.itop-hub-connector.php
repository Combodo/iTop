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
	// Dictionary entries go here
	'Menu:iTopHub' => 'iTop Hub',
	'Menu:iTopHub:Register' => 'Kapcsolódás az iTop Hub-ra',
	'Menu:iTopHub:Register+' => 'Továbblépés az iTop Hub-ra a '.ITOP_APPLICATION_SHORT.' példányának frissítéséhez',
	'Menu:iTopHub:Register:Description' => '<p>Szerezzen hozzáférést az iTop Hub közösségi platformjához!</br>Találja meg az összes szükséges tartalmat és információt, kezelje példányait személyre szabott eszközökkel és telepítsen további bővítményeket.</br><br/>Ha erről az oldalról csatlakozik a Hub-hoz, akkor a Hub-ra továbbítja az '.ITOP_APPLICATION_SHORT.' példányára vonatkozó információkat.</p>',
	'Menu:iTopHub:MyExtensions' => 'Telepített bővítmények',
	'Menu:iTopHub:MyExtensions+' => 'Lásd a '.ITOP_APPLICATION_SHORT.' példányon telepített bővítmények listáját',
	'Menu:iTopHub:BrowseExtensions' => 'Bővítmények beszerzése az iTop Hub-ról',
	'Menu:iTopHub:BrowseExtensions+' => 'További bővítmények keresése az iTop Hub-on',
	'Menu:iTopHub:BrowseExtensions:Description' => '<p>Nézze meg az iTop Hub áruházát, az Ön egyablakos helyét, ahol csodálatos '.ITOP_APPLICATION_SHORT.' bővítményeket talál!</br>Keresse meg azokat, amelyek segítenek a '.ITOP_APPLICATION_SHORT.' testreszabásában és az Ön folyamataihoz való igazításában.</br><br/>Ha erről az oldalról csatlakozik a Hub-hoz, akkor a Hub-ra továbbítja a '.ITOP_APPLICATION_SHORT.' példányára vonatkozó információkat.</p>',
	'iTopHub:GoBtn' => 'Tovább az iTop Hub-ra',
	'iTopHub:CloseBtn' => 'Bezárás',
	'iTopHub:GoBtn:Tooltip' => 'Ugrás a www.itophub.io -ra',
	'iTopHub:OpenInNewWindow' => 'iTop Hub megnyitása új ablakban',
	'iTopHub:AutoSubmit' => 'Ne kérdezze újra. Legközelebb menjen az iTop Hub-hoz automatikusan.',
	'UI:About:RemoteExtensionSource' => 'iTop Hub',
	'iTopHub:Explanation' => 'Erre a gombra kattintva átirányítjuk Önt az iTop Hub oldalára.',
	'iTopHub:BackupFreeDiskSpaceIn' => '%1$s szabad lemezterület %2$s -ben',
	'iTopHub:FailedToCheckFreeDiskSpace' => 'Nem sikerült ellenőrizni a szabad lemezterületet.',
	'iTopHub:BackupOk' => 'Biztonsági mentés Ok.',
	'iTopHub:BackupFailed' => 'Biztonsági mentés sikertelen!',
	'iTopHub:Landing:Status' => 'Telepítés állapota',
	'iTopHub:Landing:Install' => 'Bővítmények telepítése...',
	'iTopHub:CompiledOK' => 'Összeállítás sikeres',
	'iTopHub:ConfigurationSafelyReverted' => 'Hiba történt telepítés közben!<br/>Az '.ITOP_APPLICATION_SHORT.' konfigurációja NEM lett elmentve.',
	'iTopHub:FailAuthent' => 'Azonosítás sikertelen ennél a műveletnél.',
	'iTopHub:InstalledExtensions' => 'A bővítmények feltelepültek erre a példányra',
	'iTopHub:ExtensionCategory:Manual' => 'A bővítmények manuálisan telepítve',
	'iTopHub:ExtensionCategory:Manual+' => 'A következő bővítményeket kézi másolással telepítettük a '.ITOP_APPLICATION_SHORT.' %1$s könyvtárába:',
	'iTopHub:ExtensionCategory:Remote' => 'Az iTop Hub-ról telepített bővítmények',
	'iTopHub:ExtensionCategory:Remote+' => 'A következő bővítményeket telepítettük az iTop Hub-ról:',
	'iTopHub:NoExtensionInThisCategory' => 'Ebben a kategóriában nincs bővítmény',
	'iTopHub:NoExtensionInThisCategory+' => 'Böngésszen az iTop Hub-ban, hogy megtalálja azokat a bővítményeket, amelyek segítenek testreszabni és az Ön folyamataihoz igazítani az '.ITOP_APPLICATION_SHORT.'-ot !',
	'iTopHub:ExtensionNotInstalled' => 'Nincs telepítve',
	'iTopHub:GetMoreExtensions' => 'Bővítmények beszerzése az iTop Hub-ról...',
	'iTopHub:LandingWelcome' => 'Gratulálunk! A következő bővítményeket letöltöttük az iTop Hub-ról, és telepítettük az Ön '.ITOP_APPLICATION_SHORT.' példányára.',
	'iTopHub:GoBackToITopBtn' => 'Vissza az '.ITOP_APPLICATION_SHORT.'-hoz',
	'iTopHub:Uncompressing' => 'Bővítmények kicsomagolása...',
	'iTopHub:InstallationWelcome' => 'Az iTop Hub-ról letöltött bővítmények telepítése',
	'iTopHub:DBBackupLabel' => 'Példány mentés',
	'iTopHub:DBBackupSentence' => 'A frissítés előtt biztonsági mentést készítünk az adatbázisról és az '.ITOP_APPLICATION_SHORT.' konfigurációról.',
	'iTopHub:DeployBtn' => 'Telepítés !',
	'iTopHub:DatabaseBackupProgress' => 'Példány mentés...',
	'iTopHub:InstallationEffect:Install' => 'Verzió: %1$s lesz telepítve.',
	'iTopHub:InstallationEffect:NoChange' => 'Verzió: %1$s már telepítve. Nincs változás.',
	'iTopHub:InstallationEffect:Upgrade' => '<b>Frissítve</b> lesz %1$s verzióról %2$s verzióra.',
	'iTopHub:InstallationEffect:Downgrade' => '<b>Lebutítva</b> lesz %1$s verzióról %2$s verzióra.',
	'iTopHub:InstallationProgress:DatabaseBackup' => ITOP_APPLICATION_SHORT.' példány backup...',
	'iTopHub:InstallationProgress:ExtensionsInstallation' => 'A bővítmény telepítése',
	'iTopHub:InstallationEffect:MissingDependencies' => 'Ez a bővítmény nem telepíthető nem teljesített függőségek miatt.',
	'iTopHub:InstallationEffect:MissingDependencies_Details' => 'A bővítményhez további modulok szükségesek: %1$s',
	'iTopHub:InstallationProgress:InstallationSuccessful' => 'A telepítés sikeres',
	'iTopHub:InstallationStatus:Installed_Version' => '%1$s verzió: %2$s.',
	'iTopHub:InstallationStatus:Installed' => 'Telepítve',
	'iTopHub:InstallationStatus:Version_NotInstalled' => 'A %1$s verzió <b>NINCS</b> telepítve.',
));


