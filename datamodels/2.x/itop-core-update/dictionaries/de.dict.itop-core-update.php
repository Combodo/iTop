<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2021 Combodo SARL
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
	'iTopUpdate:UI:PageTitle' => 'Anwendungsupgrade',
    'itop-core-update:UI:SelectUpdateFile' => 'Upgrade-Datei hochladen',
    'itop-core-update:UI:ConfirmUpdate' => 'Upgrade bestätigen',
    'itop-core-update:UI:UpdateCoreFiles' => 'Upgrade der '.ITOP_APPLICATION_SHORT.'-Core-Dateien',
	'iTopUpdate:UI:MaintenanceModeActive' => 'Die Anwendung läuft im Wartungsmodus, Benutzerzugriffe sind nicht möglich. Führen Sie erneut ein Setup oder Restore der Anwendung aus, um in den normalen Betriebsmodus zurückzukehren.',
	'itop-core-update:UI:UpdateDone' => 'Upgrade abgeschlossen',

	'itop-core-update/Operation:SelectUpdateFile/Title' => 'Upgrade',
	'itop-core-update/Operation:ConfirmUpdate/Title' => 'Upgrade bestätigen',
	'itop-core-update/Operation:UpdateCoreFiles/Title' => 'Anwendungsupgrade',
	'itop-core-update/Operation:UpdateDone/Title' => 'App-Upgrade abgeschlossen',

	'iTopUpdate:UI:SelectUpdateFile' => 'Upgrade-Datei hochladen',
	'iTopUpdate:UI:CheckUpdate' => 'Upgrade-Datei überprüfen',
	'iTopUpdate:UI:ConfirmInstallFile' => 'Installation von %1$s',
	'iTopUpdate:UI:DoUpdate' => 'Upgrade',
	'iTopUpdate:UI:CurrentVersion' => 'Installierte Version',
	'iTopUpdate:UI:NewVersion' => 'Neue installierte Version',
    'iTopUpdate:UI:Back' => 'Zurück',
    'iTopUpdate:UI:Cancel' => 'Abbrechen',
    'iTopUpdate:UI:Continue' => 'Weiter',
	'iTopUpdate:UI:RunSetup' => 'Setuplauf',
    'iTopUpdate:UI:WithDBBackup' => 'Datenbankbackup',
    'iTopUpdate:UI:WithFilesBackup' => 'Backup der Anwendungsdateien',
    'iTopUpdate:UI:WithoutBackup' => 'Kein geplantes Backup',
    'iTopUpdate:UI:Backup' => 'Backup wurde vor dem Upgrade erzeugt',
	'iTopUpdate:UI:DoFilesArchive' => 'Anwendungsdateien archivieren',
	'iTopUpdate:UI:UploadArchive' => 'Archivpaket hochladen',
	'iTopUpdate:UI:ServerFile' => 'Pfad zu Archivpaket, das bereits auf dem Server liegt',
	'iTopUpdate:UI:WarningReadOnlyDuringUpdate' => 'Während des Upgrades läuft die Anwendung im read-only Modus',

    'iTopUpdate:UI:Status' => 'Status',
    'iTopUpdate:UI:Action' => 'Update',
    'iTopUpdate:UI:History' => 'Versionshistorie',
    'iTopUpdate:UI:Progress' => 'Upgradefortschritt',

    'iTopUpdate:UI:DoBackup:Label' => 'Backup von Dateien und Datenbank',
    'iTopUpdate:UI:DoBackup:Warning' => 'Wegen geringem verbleibenden Speicherplatz sollte kein Backup mehr erzeugt werden.',

    'iTopUpdate:UI:DiskFreeSpace' => 'Freier Speicherplatz',
    'iTopUpdate:UI:ItopDiskSpace' => ITOP_APPLICATION_SHORT.' Speicherplatz',
    'iTopUpdate:UI:DBDiskSpace' => 'Datenbankgröße',
	'iTopUpdate:UI:FileUploadMaxSize' => 'Maximale Dateigröße für Uploads',

	'iTopUpdate:UI:PostMaxSize' => 'PHP.ini Wert post_max_size: %1$s',
	'iTopUpdate:UI:UploadMaxFileSize' => 'PHP.ini Wert upload_max_filesize: %1$s',

    'iTopUpdate:UI:CanCoreUpdate:Loading' => 'Prüfung des Dateisystems',
    'iTopUpdate:UI:CanCoreUpdate:Error' => 'Dateisystemprüfung fehlgeschlagen (%1$s)',
    'iTopUpdate:UI:CanCoreUpdate:ErrorFileNotExist' => 'Dateisystemprüfung fehlgeschlagen (Datei nicht vorhanden %1$s)',
    'iTopUpdate:UI:CanCoreUpdate:Failed' => 'Dateisystemprüfung fehlgeschlagen',
    'iTopUpdate:UI:CanCoreUpdate:Yes' => 'Anwendungsupgrade kann durchgeführt werden',
	'iTopUpdate:UI:CanCoreUpdate:No' => 'Anwendungsupgrade nicht möglich: %1$s',
	'iTopUpdate:UI:CanCoreUpdate:Warning' => 'Vorsicht: App-Upgrade kann fehlschlagen: %1$s',
	'iTopUpdate:UI:CannotUpdateUseSetup' => '<b>Einige angepasste Dateien wurden erkannt</b>, eine Teil-Update kann nicht ausgeführt werden.<br/>Befolgen Sie das  <a target="_blank" href="%2$s">Verfahren</a>, um Ihr iTop manuell zu aktualisieren. Sie müssen das <a href="%1$s">Setup</a> benutzen, um Ihre Applikation zu aktualisieren.<br />',
	'iTopUpdate:UI:CannotUpdateNewModules' =>   '<b>Einige neue Module wurden erkannt</b>, eine Teil-Update kann nicht ausgeführt werden.<br/>Befolgen Sie das  <a target="_blank" href="%2$s">Verfahren</a>, um Ihr iTop manuell zu aktualisieren. Sie müssen das <a href="%1$s">Setup</a> benutzen, um Ihre Applikation zu aktualisieren.<br />',
	'iTopUpdate:UI:CheckInProgress'=>'Please wait during integrity check~~',

	// Setup Messages
    'iTopUpdate:UI:SetupMessage:Ready' => 'Bereit zum Upgrade',
	'iTopUpdate:UI:SetupMessage:EnterMaintenance' => 'Wartungsmodus aktiviert',
	'iTopUpdate:UI:SetupMessage:Backup' => 'Datenbankbackup',
	'iTopUpdate:UI:SetupMessage:FilesArchive' => 'Archivierung der Anwendungsdaten',
    'iTopUpdate:UI:SetupMessage:CopyFiles' => 'Kopieren neuer Dateien',
	'iTopUpdate:UI:SetupMessage:CheckCompile' => 'Prüfung des Anwendungsupgrades',
	'iTopUpdate:UI:SetupMessage:Compile' => 'Upgrade von Anwendung und Datenbank',
	'iTopUpdate:UI:SetupMessage:UpdateDatabase' => 'Upgrade Datenbank',
	'iTopUpdate:UI:SetupMessage:ExitMaintenance' => 'Wartungsmodus deaktiviert',
    'iTopUpdate:UI:SetupMessage:UpdateDone' => 'Upgrade abgeschlossen',

	// Errors
	'iTopUpdate:Error:MissingFunction' => 'Start des Upgrades nicht möglich. Fehlende Funktion.',
	'iTopUpdate:Error:MissingFile' => 'Fehlende Datei: %1$s',
	'iTopUpdate:Error:CorruptedFile' => 'Datei %1$s ist beschädigt',
    'iTopUpdate:Error:BadFileFormat' => 'Die Upgradedatei ist keine ZIP-Datei',
    'iTopUpdate:Error:BadFileContent' => 'Die Upgradedatei ist kein '.ITOP_APPLICATION_SHORT.'-Paket',
    'iTopUpdate:Error:BadItopProduct' => 'Die Upgradedatei ist nicht mit dieser '.ITOP_APPLICATION_SHORT.'-Version kompatibel.',
	'iTopUpdate:Error:Copy' => 'Fehler, kopieren von \'%1$s\' nach \'%2$s\' nicht möglich',
    'iTopUpdate:Error:FileNotFound' => 'Datei nicht gefunden',
    'iTopUpdate:Error:NoFile' => 'Keine Datei angegeben',
	'iTopUpdate:Error:InvalidToken' => 'Ungültiges Token',
	'iTopUpdate:Error:UpdateFailed' => 'Upgrade fehlgeschlagen',
	'iTopUpdate:Error:FileUploadMaxSizeTooSmall' => 'Die maximale Größe für Dateiuploads ist zu klein. Bitte die PHP-Konfiguration anpassen.',

	'iTopUpdate:UI:RestoreArchive' => 'Wiederherstellung der Anwendung aus archivierten Daten \'%1$s\'',
	'iTopUpdate:UI:RestoreBackup' => 'Wiederherstellung der Datenbank aus \'%1$s\'',
	'iTopUpdate:UI:UpdateDone' => 'Upgrade erfolgreich',
	'Menu:iTopUpdate' => 'Anwendungsupgrade',
	'Menu:iTopUpdate+' => 'Anwendungsupgrade',

    // Missing itop entries
    'Class:ModuleInstallation/Attribute:installed' => 'Installiert am',
    'Class:ModuleInstallation/Attribute:name' => 'Name',
    'Class:ModuleInstallation/Attribute:version' => 'Version',
    'Class:ModuleInstallation/Attribute:comment' => 'Kommentar',
));
