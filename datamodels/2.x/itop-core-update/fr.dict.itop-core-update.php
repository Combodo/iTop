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
Dict::Add('FR FR', 'French', 'Français', array(
	'iTopUpdate:UI:PageTitle' => 'Mise à jour de l\'application',
    'itop-core-update:UI:SelectUpdateFile' => 'Mise à jour',
    'itop-core-update:UI:ConfirmUpdate' => 'Confirmation de la mise à jour',
    'itop-core-update:UI:UpdateCoreFiles' => 'Mise à jour en cours',
	'iTopUpdate:UI:MaintenanceModeActive' => 'L\'application est actuellement en maintenance en mode lecture seule. Vous pouvez lancer un Setup pour retourner dans un mode normal.',
	'itop-core-update:UI:UpdateDone' => 'Mise à jour effectuée',

	'itop-core-update/Operation:SelectUpdateFile/Title' => 'Mise à jour',
	'itop-core-update/Operation:ConfirmUpdate/Title' => 'Mise à jour',
	'itop-core-update/Operation:UpdateCoreFiles/Title' => 'Mise à jour',
	'itop-core-update/Operation:UpdateDone/Title' => 'Mise à jour',

	'iTopUpdate:UI:SelectUpdateFile' => 'Sélectionner un fichier de mise à jour',
	'iTopUpdate:UI:CheckUpdate' => 'Vérification de la mise à jour',
	'iTopUpdate:UI:ConfirmInstallFile' => 'La mise à jour %1$s va être installée',
	'iTopUpdate:UI:DoUpdate' => 'Mettre à jour',
	'iTopUpdate:UI:CurrentVersion' => 'Version installée',
	'iTopUpdate:UI:NewVersion' => 'Nouvelle version',
    'iTopUpdate:UI:Back' => 'Annuler',
    'iTopUpdate:UI:Cancel' => 'Annuler',
    'iTopUpdate:UI:Continue' => 'Continuer',
	'iTopUpdate:UI:RunSetup' => 'Lancer le Setup',
    'iTopUpdate:UI:WithDBBackup' => 'Sauvegarde de la base de données',
    'iTopUpdate:UI:WithFilesBackup' => 'Archive des fichiers de l\'application',
    'iTopUpdate:UI:WithoutBackup' => 'Pas de sauvegarde',
    'iTopUpdate:UI:Backup' => 'Sauvegarde effectuée avant la mise à jour',
	'iTopUpdate:UI:DoFilesArchive' => 'Archive les fichiers de l\'application',
	'iTopUpdate:UI:UploadArchive' => 'Choisir un package à télécharger',
	'iTopUpdate:UI:ServerFile' => 'Chemin d\'un package présent sur le serveur',
	'iTopUpdate:UI:WarningReadOnlyDuringUpdate' => 'Pendant toute la durée de la mise à jour, l\'application sera en lecture seule.',

    'iTopUpdate:UI:Status' => 'Versions installées',
    'iTopUpdate:UI:Action' => 'Mettre à jour',
    'iTopUpdate:UI:History' => 'Historique des versions',
    'iTopUpdate:UI:Progress' => 'Progression de la mise à jour',

    'iTopUpdate:UI:DoBackup:Label' => 'Sauvegarde de la base de données',
    'iTopUpdate:UI:DoBackup:Warning' => 'La sauvegarde n\'est pas conseillée à cause du manque de place disque disponible',

    'iTopUpdate:UI:DiskFreeSpace' => 'Taille disque disponible',
    'iTopUpdate:UI:ItopDiskSpace' => 'Taille disque utilisée par l\'application',
    'iTopUpdate:UI:DBDiskSpace' => 'Taille disque utilisée par la base de données',
	'iTopUpdate:UI:FileUploadMaxSize' => 'Taille maximale de chargement de fichier',

	'iTopUpdate:UI:PostMaxSize' => 'Valeur PHP ini post_max_size : %1$s',
	'iTopUpdate:UI:UploadMaxFileSize' => 'Valeur PHP ini upload_max_filesize : %1$s',

    'iTopUpdate:UI:CanCoreUpdate:Loading' => 'Vérification des fichiers',
    'iTopUpdate:UI:CanCoreUpdate:Error' => 'Échec de la vérification des fichiers (%1$s)',
    'iTopUpdate:UI:CanCoreUpdate:ErrorFileNotExist' => 'Échec de la vérification des fichiers (Fichier manquant %1$s)',
    'iTopUpdate:UI:CanCoreUpdate:Failed' => 'Échec de la vérification des fichiers',
    'iTopUpdate:UI:CanCoreUpdate:Yes' => 'L\'application peut être mise à jour',
    'iTopUpdate:UI:CanCoreUpdate:No' => 'L\'application ne peut pas être mise à jour : %1$s',
	'iTopUpdate:UI:CanCoreUpdate:Warning' => 'Attention : la mise à jour de l\'application peut échouer : %1$s',
	'iTopUpdate:UI:CannotUpdateUseSetup' => 'Vous devez utiliser la page <a href="%1$s">d\'installation</a> pour mettre à jour l\'application.<br />Des fichiers modifiés ont été détectés, une mise à jour partielle ne peut pas être effectuée.',

	// Setup Messages
    'iTopUpdate:UI:SetupMessage:Ready' => 'Prêt pour l\\installation',
	'iTopUpdate:UI:SetupMessage:EnterMaintenance' => 'Application en maintenance',
	'iTopUpdate:UI:SetupMessage:Backup' => 'Sauvegarde des fichiers de l\'application',
	'iTopUpdate:UI:SetupMessage:FilesArchive' => 'Archivage des fichiers de l\'application',
    'iTopUpdate:UI:SetupMessage:CopyFiles' => 'Copie des fichiers de la nouvelle version',
	'iTopUpdate:UI:SetupMessage:CheckCompile' => 'Contrôle de la mise à jour',
	'iTopUpdate:UI:SetupMessage:Compile' => 'Mise à jour de l\'application',
	'iTopUpdate:UI:SetupMessage:UpdateDatabase' => 'Mise à jour de la base de données',
	'iTopUpdate:UI:SetupMessage:ExitMaintenance' => 'Application en utilisation normale',
    'iTopUpdate:UI:SetupMessage:UpdateDone' => 'Installation terminée',

	// Errors
	'iTopUpdate:Error:MissingFunction' => 'Impossible de mettre à jour',
	'iTopUpdate:Error:MissingFile' => 'Ficher manquant : %1$s',
	'iTopUpdate:Error:CorruptedFile' => 'Le fichier %1$s est corrompu',
    'iTopUpdate:Error:BadFileFormat' => 'Le fichier de mise à jour n\'est pas au format "zip"',
    'iTopUpdate:Error:BadFileContent' => 'Le fichier n\'est pas un package valide',
    'iTopUpdate:Error:BadItopProduct' => 'Le package n\'est pas compatible avec votre application',
	'iTopUpdate:Error:Copy' => 'Erreur : impossible de copier le fichier \'%1$s\' dans \'%2$s\'',
    'iTopUpdate:Error:FileNotFound' => 'Fichier manquant',
    'iTopUpdate:Error:NoFile' => 'Pas d\'archive',
	'iTopUpdate:Error:InvalidToken' => 'Information manquante',
	'iTopUpdate:Error:UpdateFailed' => 'La mise à jour a échoué',
	'iTopUpdate:Error:FileUploadMaxSizeTooSmall' => 'La taille maximale de chargement de fichier semble trop petite pour faire la mise à jour. Veuillez changer la configuration de PHP.',

	'iTopUpdate:UI:RestoreArchive' => 'Vous pouvez restaurer l\'application depuis \'%1$s\'',
	'iTopUpdate:UI:RestoreBackup' => 'Vous pouvez restaurer la base de données depuis \'%1$s\'',
	'iTopUpdate:UI:UpdateDone' => 'Mise à jour effectuée',
	'Menu:iTopUpdate' => 'Mise à jour de l\'application',
	'Menu:iTopUpdate+' => 'Mise à jour de l\'application',

    // Missing itop entries
    'Class:ModuleInstallation/Attribute:installed' => 'Installé le',
    'Class:ModuleInstallation/Attribute:name' => 'Nom',
    'Class:ModuleInstallation/Attribute:version' => 'Version',
    'Class:ModuleInstallation/Attribute:comment' => 'Commentaire',
));



// Additional language entries not present in English dict
Dict::Add('FR FR', 'French', 'Français', array(
 'iTopUpdate:UI:DoBackup' => 'Faire une sauvegarde des fichiers et de la base',
 'iTopUpdate:UI:WithBackup' => 'Avec sauvegarde de l\'application, l\'archive sera dans \'%1$s\'',
 'iTopUpdate:UI:InstallationCanBeUpdated' => 'L\'application peut être mise à jour',
 'iTopUpdate:UI:InstallationCanNotBeUpdated' => 'L\'application ne peut pas être mise à jour',
 'iTopUpdate:Error:NoUpdate' => 'La mise à jour n\'a pas été effectuée',
));
