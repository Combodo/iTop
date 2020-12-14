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
 *
 * @author Jeffrey Bostoen - <jbostoen.itop@outlook.com> (2018 - 2020)
 */
Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'iTopUpdate:UI:PageTitle' => 'Upgraden toepassing',
    'itop-core-update:UI:SelectUpdateFile' => 'Upgrade',
    'itop-core-update:UI:ConfirmUpdate' => 'Upgrade',
    'itop-core-update:UI:UpdateCoreFiles' => 'Upgrade',
	'iTopUpdate:UI:MaintenanceModeActive' => 'De onderhoudsmode van deze toepassing is actief. Geen enkele gebruiker heeft momenteel toegang. Voer een setup of herstel uit om de onderhoudsmode te deactiveren.',
	'itop-core-update:UI:UpdateDone' => 'Upgrade voltooid',

	'itop-core-update/Operation:SelectUpdateFile/Title' => 'Upgrade',
	'itop-core-update/Operation:ConfirmUpdate/Title' => 'Upgrade',
	'itop-core-update/Operation:UpdateCoreFiles/Title' => 'Upgrade',
	'itop-core-update/Operation:UpdateDone/Title' => 'Upgrade voltooid',

	'iTopUpdate:UI:SelectUpdateFile' => 'Selecteer een upgrade-bestand om te uploaden',
	'iTopUpdate:UI:CheckUpdate' => 'Verifieer upgrade-bestand',
	'iTopUpdate:UI:ConfirmInstallFile' => 'Er zal een upgrade uitgevoerd worden met %1$s',
	'iTopUpdate:UI:DoUpdate' => 'Upgrade',
	'iTopUpdate:UI:CurrentVersion' => 'Huidige versie',
	'iTopUpdate:UI:NewVersion' => 'Nieuwe versie',
    'iTopUpdate:UI:Back' => 'Vorige',
    'iTopUpdate:UI:Cancel' => 'Annuleer',
    'iTopUpdate:UI:Continue' => 'Volgende',
	'iTopUpdate:UI:RunSetup' => 'Setup uitvoeren',
    'iTopUpdate:UI:WithDBBackup' => 'Backup database',
    'iTopUpdate:UI:WithFilesBackup' => 'Backup toepassingsbestanden',
    'iTopUpdate:UI:WithoutBackup' => 'Er is geen backup gepland',
    'iTopUpdate:UI:Backup' => 'Er is een backup gegenereerd voorafgaand aan de installatie',
	'iTopUpdate:UI:DoFilesArchive' => 'Archiveer toepassingsbestanden',
	'iTopUpdate:UI:UploadArchive' => 'Selecteer een archief om te uploaden',
	'iTopUpdate:UI:ServerFile' => 'Het pad van dit archief bestaat al op de server',
	'iTopUpdate:UI:WarningReadOnlyDuringUpdate' => 'Tijdens de upgrade zal de applicatie enkel toegankelijk zijn als "alleen lezen".',

    'iTopUpdate:UI:Status' => 'Status',
    'iTopUpdate:UI:Action' => 'Update',
    'iTopUpdate:UI:History' => 'Versiegeschiedenis',
    'iTopUpdate:UI:Progress' => 'Voortgang van de upgrade',

    'iTopUpdate:UI:DoBackup:Label' => 'Maak een backup van de bestanden en database',
    'iTopUpdate:UI:DoBackup:Warning' => 'Een backup maken wordt afgeraden doordat er weinig schijfruimte is',

    'iTopUpdate:UI:DiskFreeSpace' => 'Vrije schijfruimte',
    'iTopUpdate:UI:ItopDiskSpace' => 'iTop schijfgebruik',
    'iTopUpdate:UI:DBDiskSpace' => 'Database schijfgebruik',
	'iTopUpdate:UI:FileUploadMaxSize' => 'Maximale bestandsgrootte (upload)',

	'iTopUpdate:UI:PostMaxSize' => 'PHP ini-waarde post_max_size: %1$s',
	'iTopUpdate:UI:UploadMaxFileSize' => 'PHP ini-waarde upload_max_filesize: %1$s',

    'iTopUpdate:UI:CanCoreUpdate:Loading' => 'Controle van het bestandssysteem',
    'iTopUpdate:UI:CanCoreUpdate:Error' => 'Controle van het bestandssysteem is mislukt (%1$s)',
    'iTopUpdate:UI:CanCoreUpdate:ErrorFileNotExist' => 'Controle van het bestandssysteem mislukt (Bestand bestaat niet: %1$s)',
    'iTopUpdate:UI:CanCoreUpdate:Failed' => 'Controle van het bestandssysteem is mislukt',
    'iTopUpdate:UI:CanCoreUpdate:Yes' => 'Updaten van toepassing is mogelijk',
	'iTopUpdate:UI:CanCoreUpdate:No' => 'Updaten van de toepassing is niet mogelijk: %1$s',
	'iTopUpdate:UI:CanCoreUpdate:Warning' => 'Warning: application update can fail: %1$s~~',

	// Setup Messages
    'iTopUpdate:UI:SetupMessage:Ready' => 'Klaar om verder te gaan',
	'iTopUpdate:UI:SetupMessage:EnterMaintenance' => 'Activeren van onderhoudsmode',
	'iTopUpdate:UI:SetupMessage:Backup' => 'Maken van backup database',
	'iTopUpdate:UI:SetupMessage:FilesArchive' => 'Archiveren van de toepassingsbestanden',
    'iTopUpdate:UI:SetupMessage:CopyFiles' => 'Kopiëren van nieuwe versies van bestanden',
	'iTopUpdate:UI:SetupMessage:CheckCompile' => 'Controleren van de upgrade van de toepassing',
	'iTopUpdate:UI:SetupMessage:Compile' => 'Upgraden van toepassing en database',
	'iTopUpdate:UI:SetupMessage:UpdateDatabase' => 'Upgraden van database',
	'iTopUpdate:UI:SetupMessage:ExitMaintenance' => 'Deactiveren van onderhoudsmode',
    'iTopUpdate:UI:SetupMessage:UpdateDone' => 'Upgrade voltooid',

	// Errors
	'iTopUpdate:Error:MissingFunction' => 'Onmogelijk om de upgrade te starten, een functie ontbreekt',
	'iTopUpdate:Error:MissingFile' => 'Bestand ontbreekt: %1$s',
	'iTopUpdate:Error:CorruptedFile' => 'Bestand %1$s is corrupt',
    'iTopUpdate:Error:BadFileFormat' => 'Upgradebestand is geen ZIP-bestand',
    'iTopUpdate:Error:BadFileContent' => 'Upgradebestand is geen toepassingsarchief',
    'iTopUpdate:Error:BadItopProduct' => 'Upgradebestand is niet compatibel met jouw toepassing',
	'iTopUpdate:Error:Copy' => 'Fout: kan niet kopiëren van "%1$s" naar "%2$s"',
    'iTopUpdate:Error:FileNotFound' => 'Bestand niet gevonden',
    'iTopUpdate:Error:NoFile' => 'Geen bestand opgegeven',
	'iTopUpdate:Error:InvalidToken' => 'Token ongeldig',
	'iTopUpdate:Error:UpdateFailed' => 'Upgrade mislukt',
	'iTopUpdate:Error:FileUploadMaxSizeTooSmall' => 'De maximale bestandsgrootte voor uploads lijkt te klein voor deze update. Controleer de PHP-configuratie.',

	'iTopUpdate:UI:RestoreArchive' => 'Je kan de toepassing herstellen via het archief "%1$s"',
	'iTopUpdate:UI:RestoreBackup' => 'Je kan de database herstellen via het archief "%1$s"',
	'iTopUpdate:UI:UpdateDone' => 'Upgrade geslaagd',
	'Menu:iTopUpdate' => 'Upgrade toepassing',
	'Menu:iTopUpdate+' => 'Upgrade toepassing',

    // Missing itop entries
    'Class:ModuleInstallation/Attribute:installed' => 'Geïnstalleerd op',
    'Class:ModuleInstallation/Attribute:name' => 'Naam',
    'Class:ModuleInstallation/Attribute:version' => 'Versie',
    'Class:ModuleInstallation/Attribute:comment' => 'Opmerkingen',
));


