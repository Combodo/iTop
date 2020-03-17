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
Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
	'iTopUpdate:UI:PageTitle' => 'Actualización de aplicación',
    'itop-core-update:UI:SelectUpdateFile' => 'Application Upgrade~~',
    'itop-core-update:UI:ConfirmUpdate' => 'Application Upgrade~~',
    'itop-core-update:UI:UpdateCoreFiles' => 'Application Upgrade~~',
	'iTopUpdate:UI:MaintenanceModeActive' => 'La aplicación está actualmente en mantenimiento, ningún usuario puede acceder. UStede debe ejecutar la instalación o restaturar la aplicación para regresar al modo normal.',
	'itop-core-update:UI:UpdateDone' => 'Application Upgrade~~',

	'itop-core-update/Operation:SelectUpdateFile/Title' => 'Application Upgrade~~',
	'itop-core-update/Operation:ConfirmUpdate/Title' => 'Confirmar actualización de la aplicación',
	'itop-core-update/Operation:UpdateCoreFiles/Title' => 'Actualizando aplicación',
	'itop-core-update/Operation:UpdateDone/Title' => 'Actualización de aplicación terminada',

	'iTopUpdate:UI:SelectUpdateFile' => 'Seleccione un archivo de actualización para subir',
	'iTopUpdate:UI:CheckUpdate' => 'Verificar archivo de actualización',
	'iTopUpdate:UI:ConfirmInstallFile' => 'Usted va a instalar %1$s',
	'iTopUpdate:UI:DoUpdate' => 'Actualizar',
	'iTopUpdate:UI:CurrentVersion' => 'Versión instalada actualmente',
	'iTopUpdate:UI:NewVersion' => 'Nueva versión instalada',
    'iTopUpdate:UI:Back' => 'Volver',
    'iTopUpdate:UI:Cancel' => 'Cancelar',
    'iTopUpdate:UI:Continue' => 'Continuar',
	'iTopUpdate:UI:RunSetup' => 'Ejecutar instalación',
    'iTopUpdate:UI:WithDBBackup' => 'Respaldo de base de datos',
    'iTopUpdate:UI:WithFilesBackup' => 'Respaldo de archivos de aplicación',
    'iTopUpdate:UI:WithoutBackup' => 'No hay respaldos planificados',
    'iTopUpdate:UI:Backup' => 'Respaldo generado antes de actualizar',
	'iTopUpdate:UI:DoFilesArchive' => 'Respaldar archivos de aplicación',
	'iTopUpdate:UI:UploadArchive' => 'Selecione un paquete para subir',
	'iTopUpdate:UI:ServerFile' => 'Ruta del paquete disponible en el servidor',
	'iTopUpdate:UI:WarningReadOnlyDuringUpdate' => 'Durante la actualización, la aplicación estará en modo sólo lectura.',

    'iTopUpdate:UI:Status' => 'Estado',
    'iTopUpdate:UI:Action' => 'Actualización',
    'iTopUpdate:UI:History' => 'Historial de versiones',
    'iTopUpdate:UI:Progress' => 'Progreso de actualización',

    'iTopUpdate:UI:DoBackup:Label' => 'Respaldo de archivos y base de datos',
    'iTopUpdate:UI:DoBackup:Warning' => 'El respaldo no está recomendado por el limitado espacio en el dispositivo',

    'iTopUpdate:UI:DiskFreeSpace' => 'Espaciolibre en el dispositivo',
    'iTopUpdate:UI:ItopDiskSpace' => 'Espacio en diso de iTop',
    'iTopUpdate:UI:DBDiskSpace' => 'Espacio en diso de base de datos',
	'iTopUpdate:UI:FileUploadMaxSize' => 'Máximo tamaño de subida de archivos',

	'iTopUpdate:UI:PostMaxSize' => 'Valor post_max_size en PHP ini: %1$s~~',
	'iTopUpdate:UI:UploadMaxFileSize' => 'Valor upload_max_filesize en PHP ini: %1$s~~',

    'iTopUpdate:UI:CanCoreUpdate:Loading' => 'Revisando sistema de archivos',
    'iTopUpdate:UI:CanCoreUpdate:Error' => 'La revisión del sistema de archivos falló (%1$s)',
    'iTopUpdate:UI:CanCoreUpdate:ErrorFileNotExist' => 'La revisión del sistema de archivos falló (Archivo no existe %1$s)~~',
    'iTopUpdate:UI:CanCoreUpdate:Failed' => 'La revisión del sistema de archivos falló',
    'iTopUpdate:UI:CanCoreUpdate:Yes' => 'La aplicación puede ser actualizada',
    'iTopUpdate:UI:CanCoreUpdate:No' => 'La aplicación no puede ser actualizada: %1$s',

	// Setup Messages
    'iTopUpdate:UI:SetupMessage:Ready' => 'Listo para empezar',
	'iTopUpdate:UI:SetupMessage:EnterMaintenance' => 'Entrando en modo mantenimiento',
	'iTopUpdate:UI:SetupMessage:Backup' => 'Respaldo de base de datos',
	'iTopUpdate:UI:SetupMessage:FilesArchive' => 'Respaldar archivos de aplicación',
    'iTopUpdate:UI:SetupMessage:CopyFiles' => 'Copiar archivos de nueva version',
	'iTopUpdate:UI:SetupMessage:CheckCompile' => 'Revisar actualización de aplicación',
	'iTopUpdate:UI:SetupMessage:Compile' => 'Actualizar aplicación y base de datos',
	'iTopUpdate:UI:SetupMessage:UpdateDatabase' => 'Actualizar base de datos',
	'iTopUpdate:UI:SetupMessage:ExitMaintenance' => 'Saliendo del modo mantenimiento',
    'iTopUpdate:UI:SetupMessage:UpdateDone' => 'Actualización completada',

	// Errors
	'iTopUpdate:Error:MissingFunction' => 'Impossible to start upgrade, missing function~~',
	'iTopUpdate:Error:MissingFile' => 'Missing file: %1$s~~',
	'iTopUpdate:Error:CorruptedFile' => 'File %1$s is corrupted~~',
    'iTopUpdate:Error:BadFileFormat' => 'Upgrade file is not a zip file~~',
    'iTopUpdate:Error:BadFileContent' => 'Upgrade file is not an application archive~~',
    'iTopUpdate:Error:BadItopProduct' => 'Upgrade file is not compatible with your application~~',
	'iTopUpdate:Error:Copy' => 'Error, cannot copy \'%1$s\' to \'%2$s\'~~',
    'iTopUpdate:Error:FileNotFound' => 'File not found~~',
    'iTopUpdate:Error:NoFile' => 'No file provided~~',
	'iTopUpdate:Error:InvalidToken' => 'Invalid token~~',
	'iTopUpdate:Error:UpdateFailed' => 'Upgrade failed ~~',
	'iTopUpdate:Error:FileUploadMaxSizeTooSmall' => 'The upload max size seems too small for update. Please change the PHP configuration.~~',

	'iTopUpdate:UI:RestoreArchive' => 'You can restore your application from the archive \'%1$s\'~~',
	'iTopUpdate:UI:RestoreBackup' => 'You can restore the database from \'%1$s\'~~',
	'iTopUpdate:UI:UpdateDone' => 'Upgrade successful~~',
	'Menu:iTopUpdate' => 'Actualización de aplicación',
	'Menu:iTopUpdate+' => 'Actualización de aplicación',

    // Missing itop entries
    'Class:ModuleInstallation/Attribute:installed' => 'Instalado en',
    'Class:ModuleInstallation/Attribute:name' => 'Nombre',
    'Class:ModuleInstallation/Attribute:version' => 'Versión',
    'Class:ModuleInstallation/Attribute:comment' => 'Commentario',
));


