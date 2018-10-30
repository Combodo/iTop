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
    'Menu:iTopHub:Register+' => 'Gehe zum iTop Hub um deine iTop-Intsanzen zu updaten',
    'Menu:iTopHub:Register:Description' => '<p>Erhalte Zugriff zu der Community-Platform iTop Hub!</br>Finde alle Informationen und Inhalte, die du brauchst, verwalte deine Instanzen mit personalisierten Tools & installiere weitere Extensions.</br><br/>Durch die Verbindung mit dem iTop Hub, werden Informationen zu deiner iTop Instanz auf den iTop Hub hochgeladen.</p>',
    'Menu:iTopHub:MyExtensions' => 'Installierte Erweiterungen',
    'Menu:iTopHub:MyExtensions+' => 'Lass dir eine Liste aller Erweiterungen anzeigen, die auf deiner iTop-Instanz installiert sind',
    'Menu:iTopHub:BrowseExtensions' => 'Erhalte Erweiterungen vom iTop Hub',
    'Menu:iTopHub:BrowseExtensions+' => 'Finde noch mehr Erweiterungen auf dem iTop Hub',
    'Menu:iTopHub:BrowseExtensions:Description' => '<p>Besuche den iTop Hub Store, die Stelle, um perfekte iTop Erweiterungen zu finden!</br>Finde die Erweiterungen, die dir helfen dein iTop so zu erweitern, dass es zu deinen Bedürfnissen passt.</br><br/>Durch die Verbindung mit dem iTop Hub, werden Informationen zu deiner iTop Instanz auf den iTop Hub hochgeladen.</p>',
    'iTopHub:GoBtn' => 'Gehe zum iTop Hub',
	'iTopHub:CloseBtn' => 'Schließen',
	'iTopHub:GoBtn:Tooltip' => 'Gehe zu www.itophub.io',
	'iTopHub:OpenInNewWindow' => 'Öffne den iTop Hub in einem neuen Fenster',
	'iTopHub:AutoSubmit' => 'Zeige diese Meldung nicht noch einmal und gehe nächstes Mal automatisch zum iTop Hub.',
	'UI:About:RemoteExtensionSource' => 'iTop Hub',
	'iTopHub:Explanation' => 'Mit Klick auf diesen Button wirst Du zum iTop Hub weitergeleitet.',

    'iTopHub:BackupFreeDiskSpaceIn' => '%1$s freier Speicherplatz aus %2$s.',
    'iTopHub:FailedToCheckFreeDiskSpace' => 'Überprüfen des freien Speicherplatzes fehlgeschlagen',
    'iTopHub:BackupOk' => 'Backup erstellt.',
    'iTopHub:BackupFailed' => 'Backup fehlgeschlagen!',
    'iTopHub:Landing:Status' => 'Installationsstatus',
	'iTopHub:Landing:Install' => 'Erweiterungen werden installiert...',
	'iTopHub:CompiledOK' => 'Installation erfolgreich',
	'iTopHub:ConfigurationSafelyReverted' => 'Fehler während der Installation!<br/>iTop Konfiguration wurde NICHT angepasst.',
	
	'iTopHub:InstalledExtensions' => 'Erweiterung, die auf dieser Instanz installiert sind',
	'iTopHub:ExtensionCategory:Manual' => 'Manuell installierte Erweiterungen',
	'iTopHub:ExtensionCategory:Manual+' => 'Die folgenden Erweiterungen installiert, indem sie manuell in das iTop-Verzeichnis %1$s kopiert wurden:',
	'iTopHub:ExtensionCategory:Remote' => 'Erweiterungen vom iTop Hub',
	'iTopHub:ExtensionCategory:Remote+' => 'Die folgenden Erweiterungen wurden über den iTop Hub installiert:',
	'iTopHub:NoExtensionInThisCategory' => 'Es gibt keine Erweiterungen dieser Kategorie<br/><br/>Gehe zum iTop Hub, um Erweiterungen zu finden, die dir helfen dein iTop so zu erweitern, dass es zu deinen Bedürfnissen passt.',
	'iTopHub:ExtensionNotInstalled' => 'Nicht installiert',
	'iTopHub:GetMoreExtensions' => 'Hole Erweiterungen vom iTop Hub...',
	
	'iTopHub:LandingWelcome' => 'Herzlichen Glückwunsch! Die folgenden Erweiterungen wurden vom iTop Hub heruntergeladen und installiert.',
	'iTopHub:GoBackToITopBtn' => 'Gehe zurück zu iTop',
	'iTopHub:Uncompressing' => 'Erweiterungen entpacken...',
	'iTopHub:InstallationWelcome' => 'Installation der Erweiterungen, die vom iTop Hub heruntergeladen wurden.',
	'iTopHub:DBBackupLabel' => 'Backup der iTop-Instanz',
	'iTopHub:DBBackupSentence' => 'Vor dem Update ein Backup der iTop Datenbank und Konfiguration durchführen.',
	'iTopHub:DeployBtn' => 'Installieren !',
	'iTopHub:DatabaseBackupProgress' => 'Backup durchführen...',
	
	'iTopHub:InstallationEffect:Install' => 'Version: %1$s wird installiert.',
	'iTopHub:InstallationEffect:NoChange' => 'Version: %1$s ist bereits installiert. Es wird sich nichts ändern.',
	'iTopHub:InstallationEffect:Upgrade' => 'Wird <b>geupgraded</b> von Version %1$s auf Version %2$s.',
	'iTopHub:InstallationEffect:Downgrade' => 'Wird <b>GEDOWNGRADED</b> von Version %1$s auf Version %2$s.',
	'iTopHub:InstallationProgress:DatabaseBackup' => 'Backup der iTop-Instanz...',
	'iTopHub:InstallationProgress:ExtensionsInstallation' => 'Installation der Erweiterungen',
	'iTopHub:InstallationEffect:MissingDependencies' => 'Diese Erweiterung kann nicht installiert werden, da Abhängigkeiten nicht erfüllt werden.',
	'iTopHub:InstallationEffect:MissingDependencies_Details' => 'The Erweiterung benötigt folgende(s) Modul(e): %1$s',
	'iTopHub:InstallationProgress:InstallationSuccessful' => 'Installation erfolgreich!',
	
	'iTopHub:InstallationStatus:Installed_Version' => '%1$s Version: %2$s.',
	'iTopHub:InstallationStatus:Installed' => 'Installiert',
	'iTopHub:InstallationStatus:Version_NotInstalled' => 'Version %1$s <b>NICHT</b> installiert.',
));


