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
	'Class:UserLocal' => 'Utilisateur '.ITOP_APPLICATION_SHORT,
	'Class:UserLocal+' => 'Utilisateur authentifié par '.ITOP_APPLICATION_SHORT,
	'Class:UserLocal/Attribute:expiration' => 'Validité du mot de passe',
	'Class:UserLocal/Attribute:expiration+' => 'Statut du mot de passe (nécessite une extension pour avoir un effet)',
	'Class:UserLocal/Attribute:expiration/Value:can_expire' => 'Durée limitée',
	'Class:UserLocal/Attribute:expiration/Value:can_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:force_expire' => 'A changer à la prochaine connexion',
	'Class:UserLocal/Attribute:expiration/Value:force_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:never_expire' => 'Permanente',
	'Class:UserLocal/Attribute:expiration/Value:never_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:otp_expire' => 'Usage unique',
	'Class:UserLocal/Attribute:expiration/Value:otp_expire+' => '',
	'Class:UserLocal/Attribute:password' => 'Mot de passe',
	'Class:UserLocal/Attribute:password+' => '',
	'Class:UserLocal/Attribute:password_renewed_date' => 'Mot de passe changé le',
	'Class:UserLocal/Attribute:password_renewed_date+' => 'Dernière date à laquelle le mot de passe a été changé',
	'Class:UserLocal/Error:OneTimePasswordChangeIsNotAllowed' => 'Impossible de mettre "Usage unique" comme validité du mot de passe pour son propre utilisateur.',
	'Error:UserLocalPasswordValidator:UserPasswordPolicyRegex:ValidationFailed' => 'Le mot de passe doit contenir au moins 8 caractères, avec minuscule, majuscule, nombre et caractère spécial.',
	'UserLocal:password:expiration' => 'Les champs ci-dessous nécessitent une extension',
]);
