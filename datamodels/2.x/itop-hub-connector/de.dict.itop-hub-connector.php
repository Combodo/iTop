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
Dict::Add('DE DE', 'German', 'Deutsch', array(
	// Dictionary entries go here
	'Menu:iTopHub' => 'iTop Hub',
	'Menu:iTopHub:Register' => 'Mit dem iTop Hub verbinden',
	'Menu:iTopHub:Register+' => 'iTop-Instanzen über den iTop Hub updaten',
	'Menu:iTopHub:Register:Description' => '<p>Zugriff auf die Community-Plattform iTop Hub!</br>Hier finden sie Informationen zu ihren iTop Instanzen, können diese mit personalisierten Tools verwalten und sich Erweiterungen installieren.</br><br/>Durch die Verbindung mit dem iTop Hub, werden Informationen zu Ihrer iTop Instanz zum iTop Hub übertragen.</p>',
	'Menu:iTopHub:MyExtensions' => 'Installierte Erweiterungen',
	'Menu:iTopHub:MyExtensions+' => 'Liste der auf ihrer iTop Instanz installierten Erweiterungen',
	'Menu:iTopHub:BrowseExtensions' => 'Erweiterungen vom iTop Hub beziehen',
	'Menu:iTopHub:BrowseExtensions+' => 'Mehr Erweiterungen auf dem iTop Hub entdecken',
	'Menu:iTopHub:BrowseExtensions:Description' => '<p>Zugriff auf die Community-Plattform iTop Hub!</br>Hier finden sie Informationen zu ihren iTop Instanzen, können diese mit personalisierten Tools verwalten und sich Erweiterungen installieren.</br><br/>Durch die Verbindung mit dem iTop Hub, werden Informationen zu Ihrer iTop Instanz zum iTop Hub übertragen.</p>',
	'iTopHub:GoBtn' => 'Gehe zum iTop Hub',
	'iTopHub:CloseBtn' => 'Schließen',
	'iTopHub:GoBtn:Tooltip' => 'Gehe zu www.itophub.io',
	'iTopHub:OpenInNewWindow' => 'iTop Hub in einem neuen Fenster öffnen',
	'iTopHub:AutoSubmit' => 'Diese Meldung nicht noch einmal anzeigen und beim nächsten Mal automatisch zum iTop Hub gehen.',
	'UI:About:RemoteExtensionSource' => 'iTop Hub',
	'iTopHub:Explanation' => 'Durch Klick auf diesen Button werden Sie zum iTop Hub weitergeleitet.',

	'iTopHub:BackupFreeDiskSpaceIn' => '%1$s freier Speicherplatz aus %2$s.',
	'iTopHub:FailedToCheckFreeDiskSpace' => 'Überprüfung des freien Speicherplatzes fehlgeschlagen',
	'iTopHub:BackupOk' => 'Backup erstellt.',
	'iTopHub:BackupFailed' => 'Backup fehlgeschlagen!',
	'iTopHub:Landing:Status' => 'Installationsstatus',
	'iTopHub:Landing:Install' => 'Erweiterungen werden installiert...',
	'iTopHub:CompiledOK' => 'Installation erfolgreich',
	'iTopHub:ConfigurationSafelyReverted' => 'Fehler während der Installation!<br/>iTop Konfiguration wurde NICHT angepasst.',
	'iTopHub:FailAuthent' => 'Die Authentifizierung für diese Aktion ist fehlgeschlagen.',

	'iTopHub:InstalledExtensions' => 'Erweiterungen, die auf dieser Instanz installiert sind',
	'iTopHub:ExtensionCategory:Manual' => 'Manuell installierte Erweiterungen',
	'iTopHub:ExtensionCategory:Manual+' => 'Die folgenden Erweiterungen wurden installiert, indem sie manuell in das Verzeichnis %1$s kopiert wurden:',
	'iTopHub:ExtensionCategory:Remote' => 'Erweiterungen vom iTop Hub',
	'iTopHub:ExtensionCategory:Remote+' => 'Die folgenden Erweiterungen wurden über den iTop Hub installiert:',
	'iTopHub:NoExtensionInThisCategory' => 'Es gibt keine Erweiterungen dieser Kategorie<br/><br/>Gehe zum iTop Hub, um Erweiterungen zu finden, die dir helfen dein iTop so zu erweitern, dass es zu deinen Bedürfnissen passt.',
	'iTopHub:ExtensionNotInstalled' => 'Nicht installiert',
	'iTopHub:GetMoreExtensions' => 'Erweiterungen vom iTop Hub beziehen ...',

	'iTopHub:LandingWelcome' => 'Herzlichen Glückwunsch! Die folgenden Erweiterungen wurden vom iTop Hub heruntergeladen und installiert.',
	'iTopHub:GoBackToITopBtn' => 'Gehe zurück zu iTop',
	'iTopHub:Uncompressing' => 'Erweiterungen entpacken...',
	'iTopHub:InstallationWelcome' => 'Installation der Erweiterungen, die vom iTop Hub heruntergeladen wurden.',
	'iTopHub:DBBackupLabel' => 'Backup der iTop-Instanz',
	'iTopHub:DBBackupSentence' => 'Vor dem Update ein Backup der iTop Datenbank und der iTop Konfiguration durchführen.',
	'iTopHub:DeployBtn' => 'Installieren !',
	'iTopHub:DatabaseBackupProgress' => 'Backup durchführen...',

	'iTopHub:InstallationEffect:Install' => 'Version: %1$s wird installiert.',
	'iTopHub:InstallationEffect:NoChange' => 'Version: %1$s ist bereits installiert. Es wird keine Änderung durchgeführt.',
	'iTopHub:InstallationEffect:Upgrade' => 'Aktualisierung von Version %1$s auf Version %2$s.',
	'iTopHub:InstallationEffect:Downgrade' => 'DOWNGRADE von Version %1$s auf Version %2$s.',
	'iTopHub:InstallationProgress:DatabaseBackup' => 'Backup der iTop-Instanz...',
	'iTopHub:InstallationProgress:ExtensionsInstallation' => 'Installation der Erweiterungen',
	'iTopHub:InstallationEffect:MissingDependencies' => 'Diese Erweiterung kann nicht installiert werden, da Abhängigkeiten nicht erfüllt werden.',
	'iTopHub:InstallationEffect:MissingDependencies_Details' => 'The Erweiterung benötigt folgende(s) Modul(e): %1$s',
	'iTopHub:InstallationProgress:InstallationSuccessful' => 'Installation erfolgreich!',

	'iTopHub:InstallationStatus:Installed_Version' => '%1$s Version: %2$s.',
	'iTopHub:InstallationStatus:Installed' => 'Installiert',
	'iTopHub:InstallationStatus:Version_NotInstalled' => 'Version %1$s <b>NICHT</b> installiert.',
));


