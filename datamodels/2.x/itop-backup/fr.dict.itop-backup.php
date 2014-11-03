<?php
/**
 * Localized data
 *
 * @copyright   Copyright (C) 2013 Combodo
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('FR FR', 'French', 'Français', array(

	'bkp-backup-running' => 'Une sauvegarde est en cours. Veuillez patienter...',
	'bkp-restore-running' => 'Une restauration des données est en cours. Veuillez patienter...',

	'Menu:BackupStatus' => 'Sauvegarde automatique',
	'bkp-status-title' => 'Sauvegarde automatique',
	'bkp-status-checks' => 'Réglages et vérifications',
	'bkp-mysqldump-ok' => 'mysqldump est installé: %1$s',
	'bkp-mysqldump-notfound' => 'mysqldump n\'a pas été trouvé: %1$s - Veuillez vous assurer que les outils mysql sont installés et qu\'ils sont accessibles en ligne de commande, ou bien éditez le fichier de configuration pour en donner le chemin via mysql_bindir.',
	'bkp-mysqldump-issue' => 'mysqldump n\'a pas pu être exécuté (code de retour: %1$d). Veuillez vérifier que les outils mysql sont installés et qu\'ils sont accessibles en ligne de commande, ou bien éditez le fichier de configuration pour en donner le chemin via mysql_bindir.',
	'bkp-missing-dir' => 'Le répertoire cible \'%1$s\' n\'existe pas ou ne peut pas être lu.',
	'bkp-free-disk-space' => 'Vous disposez de <b>%1$s d\'espace disque</b> sur %2$s',
	'bkp-dir-not-writeable' => 'Le répertoire cible \'%1$s\' n\'est pas accessible en écriture.',
	'bkp-wrong-format-spec' => 'La spécification de format pour le nom des sauvegarde est incorrecte (%1$s). La spécification par défaut sera appliquée: %2$s',
	'bkp-name-sample' => 'Les fichiers de sauvegardes seront nommés en fonction de la base, la date et l\'heure. Par exemple: %1$s',
	'bkp-week-days' => 'Les sauvegardes seront effectuées <b>tous les %1$s à %2$s</b>',
	'bkp-retention' => 'Au plus <b>%1$d fichiers de sauvegardes seront conservés</b> dans le répertoire cible.',
	'bkp-next-to-delete' => 'Sera effacé lors de la prochaine sauvegarde (Cf. réglage "retention_count")',
	'bkp-table-file' => 'Fichier', 
	'bkp-table-file+' => 'Seuls les fichiers ayant l\'extension .zip sont considérés comme étant des fichiers de sauvegarde',
	'bkp-table-size' => 'Taille',
	'bkp-table-size+' => '',
	'bkp-table-actions' => 'Actions',
	'bkp-table-actions+' => '',
	'bkp-status-backups-auto' => 'Sauvegardes automatiques',
	'bkp-status-backups-manual' => 'Sauvegardes manuelles',
	'bkp-status-backups-none' => 'Aucune sauvegarde n\'a été faite jusqu\' à présent.',
	'bkp-next-backup' => 'La prochaine sauvegarde aura lieu <b>%1$s</b> (%2$s) à %3$s',
	'bkp-button-backup-now' => 'Sauvegarder maintenant !',
	'bkp-button-restore-now' => 'Restaurer !',
	'bkp-confirm-backup' => 'Veuillez confirmer que vous souhaiter effectuer une sauvegarde maintenant.',
	'bkp-confirm-restore' => 'Veuillez confirmer que vous souhaiter effectuer la restauration de \'%1$s\' maintenant.',
	'bkp-wait-backup' => 'Sauvegarde en cours...',
	'bkp-wait-restore' => 'Restauration des données en cours...',
	'bkp-success-restore' => 'Restauration des données terminée.',
));
?>
