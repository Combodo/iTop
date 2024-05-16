<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
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
Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'iTopUpdate:UI:PageTitle' => 'Aggiornamento dell\'Applicazione',
	'itop-core-update:UI:SelectUpdateFile' => 'Aggiornamento dell\'Applicazione',
	'itop-core-update:UI:ConfirmUpdate' => 'Aggiornamento dell\'Applicazione',
	'itop-core-update:UI:UpdateCoreFiles' => 'Aggiornamento dell\'Applicazione',
	'iTopUpdate:UI:MaintenanceModeActive' => 'L\'applicazione è attualmente in manutenzione, nessun utente può accedere. È necessario eseguire un setup o ripristinare l\'archivio dell\'applicazione per tornare alla modalità normale.',
	'itop-core-update:UI:UpdateDone' => 'Aggiornamento dell\'Applicazione',
	'itop-core-update/Operation:SelectUpdateFile/Title' => 'Aggiornamento dell\'Applicazione',
	'itop-core-update/Operation:ConfirmUpdate/Title' => 'Conferma Aggiornamento dell\'Applicazione',
	'itop-core-update/Operation:UpdateCoreFiles/Title' => 'Aggiornamento dell\'Applicazione in Corso',
	'itop-core-update/Operation:UpdateDone/Title' => 'Aggiornamento dell\'Applicazione Completato',
	'iTopUpdate:UI:SelectUpdateFile' => 'Seleziona un file di aggiornamento da caricare',
	'iTopUpdate:UI:CheckUpdate' => 'Verifica file di aggiornamento',
	'iTopUpdate:UI:ConfirmInstallFile' => 'Stai per installare %1$s',
	'iTopUpdate:UI:DoUpdate' => 'Aggiorna',
	'iTopUpdate:UI:CurrentVersion' => 'Versione attualmente installata',
	'iTopUpdate:UI:NewVersion' => 'Nuova versione installata',
	'iTopUpdate:UI:Back' => 'Indietro',
	'iTopUpdate:UI:Cancel' => 'Annulla',
	'iTopUpdate:UI:Continue' => 'Continua',
	'iTopUpdate:UI:RunSetup' => 'Esegui Setup',
	'iTopUpdate:UI:WithDBBackup' => 'Backup del Database',
	'iTopUpdate:UI:WithFilesBackup' => 'Backup dei File dell\'Applicazione',
	'iTopUpdate:UI:WithoutBackup' => 'Nessun backup pianificato',
	'iTopUpdate:UI:Backup' => 'Backup generato prima dell\'aggiornamento',
	'iTopUpdate:UI:DoFilesArchive' => 'Archivia file dell\'applicazione',
	'iTopUpdate:UI:UploadArchive' => 'Seleziona un pacchetto da caricare',
	'iTopUpdate:UI:ServerFile' => 'Percorso di un pacchetto già sul server',
	'iTopUpdate:UI:WarningReadOnlyDuringUpdate' => 'Durante l\'aggiornamento, l\'applicazione sarà in sola lettura.',
	'iTopUpdate:UI:Status' => 'Stato',
	'iTopUpdate:UI:Action' => 'Aggiorna',
	'iTopUpdate:UI:Setup' => ITOP_APPLICATION_SHORT.' Setup',
	'iTopUpdate:UI:History' => 'Storia delle Versioni',
	'iTopUpdate:UI:Progress' => 'Progresso dell\'aggiornamento',
	'iTopUpdate:UI:DoBackup:Label' => 'Backup dei file e del database',
	'iTopUpdate:UI:DoBackup:Warning' => 'Backup non raccomandato a causa dello spazio su disco limitato disponibile',
	'iTopUpdate:UI:DiskFreeSpace' => 'Spazio libero su disco',
	'iTopUpdate:UI:ItopDiskSpace' => ITOP_APPLICATION_SHORT.' spazio su disco',
	'iTopUpdate:UI:DBDiskSpace' => 'Spazio su disco del Database',
	'iTopUpdate:UI:FileUploadMaxSize' => 'Dimensione massima del file da caricare',
	'iTopUpdate:UI:PostMaxSize' => 'Valore PHP ini post_max_size: %1$s',
	'iTopUpdate:UI:UploadMaxFileSize' => 'Valore PHP ini upload_max_filesize: %1$s',
	'iTopUpdate:UI:CanCoreUpdate:Loading' => 'Verifica del filesystem in corso',
	'iTopUpdate:UI:CanCoreUpdate:Error' => 'Verifica del filesystem fallita (%1$s)',
	'iTopUpdate:UI:CanCoreUpdate:ErrorFileNotExist' => 'Verifica del filesystem fallita (File non esistente %1$s)',
	'iTopUpdate:UI:CanCoreUpdate:Failed' => 'Verifica del filesystem fallita',
	'iTopUpdate:UI:CanCoreUpdate:Yes' => 'L\'applicazione può essere aggiornata',
	'iTopUpdate:UI:CanCoreUpdate:No' => 'L\'applicazione non può essere aggiornata: %1$s',
	'iTopUpdate:UI:CanCoreUpdate:Warning' => 'Attenzione: l\'aggiornamento dell\'applicazione può fallire: %1$s',
	'iTopUpdate:UI:CannotUpdateUseSetup' => '<b>Alcuni file modificati sono stati rilevati</b>, non è possibile eseguire un aggiornamento parziale.</br>Segui la <a target="_blank" href="%2$s"> procedura</a> per aggiornare manualmente il tuo iTop. Devi utilizzare il <a href="%1$s">setup</a> per aggiornare l\'applicazione.',
	'iTopUpdate:UI:CheckInProgress' => 'Attendere durante il controllo dell\'integrità',
	'iTopUpdate:UI:SetupLaunch' => 'Avvia '.ITOP_APPLICATION_SHORT.' Setup',
	'iTopUpdate:UI:SetupLaunchConfirm' => 'Questo avvierà '.ITOP_APPLICATION_SHORT.' setup, sei sicuro?',
	
	// Setup Messages
	'iTopUpdate:UI:SetupMessage:Ready' => 'Pronto per iniziare',
	'iTopUpdate:UI:SetupMessage:EnterMaintenance' => 'Entrando in modalità manutenzione',
	'iTopUpdate:UI:SetupMessage:Backup' => 'Backup del Database',
	'iTopUpdate:UI:SetupMessage:FilesArchive' => 'Archiviazione dei file dell\'applicazione',
	'iTopUpdate:UI:SetupMessage:CopyFiles' => 'Copia dei file della nuova versione',
	'iTopUpdate:UI:SetupMessage:CheckCompile' => 'Verifica aggiornamento dell\'applicazione',
	'iTopUpdate:UI:SetupMessage:Compile' => 'Aggiornamento dell\'applicazione e del database',
	'iTopUpdate:UI:SetupMessage:UpdateDatabase' => 'Aggiornamento del Database',
	'iTopUpdate:UI:SetupMessage:ExitMaintenance' => 'Uscendo dalla modalità manutenzione',
	'iTopUpdate:UI:SetupMessage:UpdateDone' => 'Aggiornamento completato',
	
	// Errors
	'iTopUpdate:Error:MissingFunction' => 'Impossibile iniziare l\'aggiornamento, funzione mancante',
	'iTopUpdate:Error:MissingFile' => 'File mancante: %1$s',
	'iTopUpdate:Error:CorruptedFile' => 'File %1$s è corrotto',
	'iTopUpdate:Error:BadFileFormat' => 'Il file di aggiornamento non è un file zip',
	'iTopUpdate:Error:BadFileContent' => 'Il file di aggiornamento non è un archivio dell\'applicazione',
	'iTopUpdate:Error:BadItopProduct' => 'Il file di aggiornamento non è compatibile con la tua applicazione',
	'iTopUpdate:Error:Copy' => 'Errore, impossibile copiare \'%1$s\' in \'%2$s\'',
	'iTopUpdate:Error:FileNotFound' => 'File non trovato',
	'iTopUpdate:Error:NoFile' => 'Nessun file fornito',
	'iTopUpdate:Error:InvalidToken' => 'Token non valido',
	'iTopUpdate:Error:UpdateFailed' => 'Aggiornamento fallito ',
	'iTopUpdate:Error:FileUploadMaxSizeTooSmall' => 'La dimensione massima del file da caricare sembra troppo piccola per l\'aggiornamento. Si prega di modificare la configurazione PHP.',
	'iTopUpdate:UI:RestoreArchive' => 'Puoi ripristinare la tua applicazione dall\'archivio \'%1$s\'',
	'iTopUpdate:UI:RestoreBackup' => 'Puoi ripristinare il database da \'%1$s\'',
	'iTopUpdate:UI:UpdateDone' => 'Aggiornamento riuscito',
	'Menu:iTopUpdate' => 'Aggiornamento dell\'Applicazione',
	'Menu:iTopUpdate+' => 'Aggiornamento dell\'Applicazione',
	
	// Missing itop entries
	'Class:ModuleInstallation/Attribute:installed' => 'Installato il',
	'Class:ModuleInstallation/Attribute:name' => 'Nome',
	'Class:ModuleInstallation/Attribute:version' => 'Versione',
	'Class:ModuleInstallation/Attribute:comment' => 'Commento',
));


