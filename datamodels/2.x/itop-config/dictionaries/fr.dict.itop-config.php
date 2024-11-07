<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * 
 */
/**
 *
 */
Dict::Add('FR FR', 'French', 'Français', [
	'Menu:ConfigFileEditor' => 'Éditeur de texte brut',
	'config-apply' => 'Enregistrer',
	'config-apply-title' => 'Enregistrer (Ctrl+S)',
	'config-cancel' => 'Annuler (restaurer)',
	'config-confirm-cancel' => 'Vos modifications seront perdues.',
	'config-current-line' => 'Ligne en édition : %1$s',
	'config-edit-intro' => 'Attention: une configuration incorrecte peut rendre '.ITOP_APPLICATION_SHORT.' inopérant pour tous les utilisateurs!',
	'config-edit-title' => 'Éditeur du Fichier de Configuration',
	'config-error-file-changed' => 'Erreur : La configuration a été modifiée depuis que vous l\'avez ouvert. Vos modifications ne peuvent <b>PAS</b> être enregistrées. Rechargez la page et recommencez.',
	'config-error-transaction' => 'Erreur : La transaction n\'est plus valide. Les modifications n\'ont <b>PAS</b> été enregistrées.',
	'config-interactive-not-allowed' => 'La modification interactive de la configuration n\'est pas autorisée. Voir le paramètre <code>\'config_editor\' => \'disabled\'</code> dans le fichier de configuration.',
	'config-no-change' => 'Aucun changement : le fichier n\'a pas été altéré.',
	'config-not-allowed-in-demo' => 'Désolé, '.ITOP_APPLICATION_SHORT.' est en <b>mode démonstration</b> : la configuration ne peut pas être modifiée.',
	'config-parse-error' => 'Ligne %2$d: %1$s.<br/>Le fichier n\'a PAS été modifié.',
	'config-reverted' => 'Vos modifications ont été écrasées par la version enregistrée.',
	'config-saved' => 'Configuration enregistrée.',
	'config-saved-warning-db-password' => 'Configuration enregistrée. Les sauvegardes ne fonctionneront pas à cause du format du mot de passe de la base.',
]);
