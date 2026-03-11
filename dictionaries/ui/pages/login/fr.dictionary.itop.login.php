<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('FR FR', 'French', 'Français', [
	'UI:Login:Title'                  => ITOP_APPLICATION_SHORT.' login',
	'UI:Login:Logo:AltText'           => 'Logo '.ITOP_APPLICATION_SHORT,
	'UI:Login:Welcome'                => 'Bienvenue dans '.ITOP_APPLICATION_SHORT.'!',
	'UI:Login:IncorrectLoginPassword' => 'Mot de passe ou identifiant incorrect.',
	'UI:Login:IdentifyYourself'       => 'Merci de vous identifier',
	'UI:Login:UserNamePrompt'         => 'Identifiant',
	'UI:Login:PasswordPrompt'         => 'Mot de passe',
	'UI:Login:ForgotPwd'              => 'Mot de passe oublié ?',
	'UI:Login:ForgotPwdForm'          => 'Mot de passe oublié',
	'UI:Login:ForgotPwdForm+'         => 'Vous pouvez demander à saisir un nouveau mot de passe. Vous allez recevoir un email et vous pourrez suivre les instructions.',
	'UI:Login:ResetPassword'          => 'Envoyer le message',
	'UI:Login:ResetPwdFailed'         => 'Impossible de vous faire parvenir le message: %1$s',
	'UI:Login:SeparatorOr'            => 'Ou',

	'UI:ResetPwd-Error-WrongLogin'    => 'le compte \'%1$s\' est inconnu.',
	'UI:ResetPwd-Error-NotPossible'   => 'les comptes "externes" ne permettent pas la saisie d\'un mot de passe dans '.ITOP_APPLICATION_SHORT.'.',
	'UI:ResetPwd-Error-FixedPwd'      => 'ce mode de saisie du mot de passe n\'est pas autorisé pour ce compte.',
	'UI:ResetPwd-Error-NoContact'     => 'le comte n\'est pas associé à une Personne.',
	'UI:ResetPwd-Error-NoEmailAtt'    => 'il manque un attribut de type "email" sur la Personne associée à ce compte. Veuillez contacter l\'administrateur de l\'application.',
	'UI:ResetPwd-Error-NoEmail'       => 'il manque une adresse email sur la Personne associée à ce compte. Veuillez contacter l\'administrateur de l\'application.',
	'UI:ResetPwd-Error-Send'          => 'erreur technique lors de l\'envoi de l\'email. Veuillez contacter l\'administrateur de l\'application.',
	'UI:ResetPwd-EmailSent'           => 'Veuillez vérifier votre boîte de réception. Ensuite, suivez les instructions données dans l\'email. Si vous ne recevez pas d\'email, merci de vérifier le login saisi',
	'UI:ResetPwd-EmailSubject'        => 'Changer votre mot de passe '.ITOP_APPLICATION_SHORT,
	'UI:ResetPwd-EmailBody'           => '<body><p>Vous avez demandé à changer votre mot de passe '.ITOP_APPLICATION_SHORT.' sans connaître le mot de passe précédent.</p><p>Veuillez suivre le lien suivant (usage unique) afin de pouvoir <a href="%1$s">saisir un nouveau mot de passe</a></p>.',
	'UI:ResetPwd-Title'               => 'Nouveau mot de passe',
	'UI:ResetPwd-Error-InvalidToken'  => 'Désolé, le mot de passe a déjà été modifié avec le lien que vous avez suivi, ou bien vous avez reçu plusieurs emails. Dans ce cas, veillez à utiliser le tout dernier lien reçu.',
	'UI:ResetPwd-Error-EnterPassword' => 'Veuillez saisir le nouveau mot de passe pour \'%1$s\'.',
	'UI:ResetPwd-Ready'               => 'Le mot de passe a bien été changé.',
	'UI:ResetPwd-Login'               => 'Cliquez ici pour vous connecter...',

	'UI:Login:About'                               => ITOP_APPLICATION.' Powered by Combodo~~',
	'UI:Login:ChangeYourPassword'                  => 'Changer de mot de passe',
	'UI:Login:OldPasswordPrompt'                   => 'Ancien mot de passe',
	'UI:Login:NewPasswordPrompt'                   => 'Nouveau mot de passe',
	'UI:Login:RetypeNewPasswordPrompt'             => 'Resaisir le nouveau mot de passe',
	'UI:Login:IncorrectOldPassword'                => 'Erreur: l\'ancien mot de passe est incorrect',
	'UI:LogOffMenu'                                => 'Déconnexion',
	'UI:LogOff:ThankYou'                           => 'Merci d\'avoir utilisé '.ITOP_APPLICATION_SHORT,
	'UI:LogOff:ClickHereToLoginAgain'              => 'Cliquez ici pour vous reconnecter...',
	'UI:ChangePwdMenu'                             => 'Changer de mot de passe...',
	'UI:Login:PasswordChanged'                     => 'Mot de passe mis à jour !',
	'UI:Login:PasswordNotChanged'                  => 'Erreur : le mot de passe est identique !',
	'UI:Login:RetypePwdDoesNotMatch'               => 'Les deux saisies du nouveau mot de passe ne sont pas identiques !',
	'UI:Button:Login'                              => 'Entrer dans '.ITOP_APPLICATION_SHORT,
	'UI:Login:Error:AccessRestricted'              => 'L\'accès à cette page '.ITOP_APPLICATION_SHORT.' est soumis à autorisation. Merci de contacter votre administrateur '.ITOP_APPLICATION_SHORT.'.',
	'UI:Login:Error:AccessAdmin'                   => 'Accès restreint aux utilisateurs possédant le profil Administrateur.',
	'UI:Login:Error:WrongOrganizationName'         => 'Organisation inconnue',
	'UI:Login:Error:MultipleContactsHaveSameEmail' => 'Email partagé par plusieurs contacts',
	'UI:Login:Error:NoValidProfiles'               => 'Pas de profil valide',
]);
