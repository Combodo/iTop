<?php
/**
 * Localized data
 *
 * @copyright   Copyright (C) 2013 XXXXX
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
Dict::Add('FR FR', 'French', 'Français', [
	'Menu:CreateMailbox' => 'Créer une boite mail...',
	'Menu:OAuthClient' => 'Client OAuth',
	'Menu:OAuthClient+' => '',
	'Menu:GenerateTokens' => 'Créer un jeton d\'accès...',
	'Menu:RegenerateTokens' => 'Recréer un jeton d\'accès..',
	'itop-oauth-client/Operation:CreateMailBox/Title' => 'Création de boite mail',
	'itop-oauth-client:UsedForSMTP' => 'Ce client Oauth est utilisé pour SMTP',
	'itop-oauth-client:TestSMTP' => 'Tester l\'envoi de mail',
	'itop-oauth-client:MissingOAuthClient' => 'Il n\'y a pas de client OAuth pour l\'utilisateur %1$s',
	'itop-oauth-client:Message:MissingToken' => 'Générez le jeton d\'accès avant d\'utiliser ce client OAuth',
	'itop-oauth-client:Message:RegenerateToken' => 'Re-générez le jeton d\'accès prendre en compte les modifications',
	'itop-oauth-client:Message:TokenCreated' => 'Le jeton d\'accès à été créé',
	'itop-oauth-client:Message:TokenRecreated' => 'Le jeton d\'accès à été renouvelé',
	'itop-oauth-client:Message:TokenError' => 'Le jeton d\'accès n\'a pas été généré à cause d`une erreur serveur',
	'OAuthClient:Name/UseForSMTPMustBeUnique' => 'La combinaison Login (%1$s) and Utilisé pour SMTP (%2$s) a déjà été utilisée pour OAuth Client',
	'OAuthClient:baseinfo' => 'Information',
	'OAuthClient:scope' => 'Scope',
]);

//
// Class: OAuthClient
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:OAuthClient' => 'Client OAuth',
	'Class:OAuthClient/Attribute:provider' => 'Fournisseur',
	'Class:OAuthClient/Attribute:provider+' => '',
	'Class:OAuthClient/Attribute:name' => 'Login',
	'Class:OAuthClient/Attribute:name+' => 'L\'adresse email à utiliser chez ce fournisseur',
	'Class:OAuthClient/Attribute:status' => 'Etat',
	'Class:OAuthClient/Attribute:status+' => 'Après la création, effectuer l\'action \'Créer un jeton d\'accès...\' pour activer ce client OAuth',
	'Class:OAuthClient/Attribute:status/Value:active' => 'Jeton d\'accès créé',
	'Class:OAuthClient/Attribute:status/Value:inactive' => 'Pas de jeton d\'accès',
	'Class:OAuthClient/Attribute:description' => 'Description',
	'Class:OAuthClient/Attribute:description+' => '',
	'Class:OAuthClient/Attribute:client_id' => 'ID Client',
	'Class:OAuthClient/Attribute:client_id+' => 'Recopier la chaine fournie par votre fournisseur OAuth2',
	'Class:OAuthClient/Attribute:client_secret' => 'Code secret du client',
	'Class:OAuthClient/Attribute:client_secret+' => 'Recopier l\'information fournie par votre fournisseur OAuth2',
	'Class:OAuthClient/Attribute:refresh_token' => 'Jeton de renouvellement',
	'Class:OAuthClient/Attribute:refresh_token+' => '',
	'Class:OAuthClient/Attribute:refresh_token_expiration' => 'Date d\'expiration du jeton de renouvellement',
	'Class:OAuthClient/Attribute:refresh_token_expiration+' => '',
	'Class:OAuthClient/Attribute:token' => 'Jeton d\'accès',
	'Class:OAuthClient/Attribute:token+' => '',
	'Class:OAuthClient/Attribute:token_expiration' => 'Date d\'expiration du jeton d\'accès',
	'Class:OAuthClient/Attribute:token_expiration+' => '',
	'Class:OAuthClient/Attribute:redirect_url' => 'URL de redirection',
	'Class:OAuthClient/Attribute:redirect_url+' => <<<EOF
Cet URL doit être recopiée dans la configuration OAuth2 de votre fournisseur
Pour recalculer la valeur par défaut, il faut effacer le champ
EOF

,
	'Class:OAuthClient/Attribute:mailbox_list' => 'Mailbox list',
	'Class:OAuthClient/Attribute:mailbox_list+' => '',
]);

//
// Class: OAuthClientAzure
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:OAuthClientAzure' => 'Client OAuth pour Microsoft Azure',
	'Class:OAuthClientAzure/Name' => '%1$s (%2$s)',
	'Class:OAuthClientAzure/Attribute:scope' => 'Niveaux d\'accès',
	'Class:OAuthClientAzure/Attribute:scope+' => 'Les niveaux par défaut sont les plus souvent suffisants',
	'Class:OAuthClientAzure/Attribute:scope/Value:SMTP' => 'SMTP',
	'Class:OAuthClientAzure/Attribute:scope/Value:SMTP+' => '',
	'Class:OAuthClientAzure/Attribute:scope/Value:IMAP' => 'IMAP',
	'Class:OAuthClientAzure/Attribute:scope/Value:IMAP+' => '',
	'Class:OAuthClientAzure/Attribute:advanced_scope' => 'Niveaux d\'accès avancé',
	'Class:OAuthClientAzure/Attribute:advanced_scope+' => 'A saisir, lorsque les niveaux prédéfinis ne suffisent pas',
	'Class:OAuthClientAzure/Attribute:used_scope' => 'Niveaux d\'accès utilisés',
	'Class:OAuthClientAzure/Attribute:used_scope+' => '',
	'Class:OAuthClientAzure/Attribute:used_scope/Value:simple' => 'Simple',
	'Class:OAuthClientAzure/Attribute:used_scope/Value:simple+' => '',
	'Class:OAuthClientAzure/Attribute:used_scope/Value:advanced' => 'Avancé',
	'Class:OAuthClientAzure/Attribute:used_scope/Value:advanced+' => '',
	'Class:OAuthClientAzure/Attribute:used_for_smtp' => 'Utilisé pour SMTP',
	'Class:OAuthClientAzure/Attribute:used_for_smtp+' => 'Le Client OAuth utilisé pour l\'envoi d\'emails doit être à \'Oui\'',
	'Class:OAuthClientAzure/Attribute:used_for_smtp/Value:yes' => 'Oui',
	'Class:OAuthClientAzure/Attribute:used_for_smtp/Value:no' => 'Non',
));

//
// Class: OAuthClientGoogle
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:OAuthClientGoogle' => 'Client OAuth pour Google',
	'Class:OAuthClientGoogle/Name' => '%1$s (%2$s)',
	'Class:OAuthClientGoogle/Attribute:scope' => 'Niveaux d\'accès',
	'Class:OAuthClientGoogle/Attribute:scope+' => 'Les niveaux par défaut sont les plus souvent suffisants',
	'Class:OAuthClientGoogle/Attribute:scope/Value:SMTP' => 'SMTP',
	'Class:OAuthClientGoogle/Attribute:scope/Value:SMTP+' => '',
	'Class:OAuthClientGoogle/Attribute:scope/Value:IMAP' => 'IMAP',
	'Class:OAuthClientGoogle/Attribute:scope/Value:IMAP+' => '',
	'Class:OAuthClientGoogle/Attribute:advanced_scope' => 'Niveaux d\'accès avancé',
	'Class:OAuthClientGoogle/Attribute:advanced_scope+' => 'A saisir, lorsque les niveaux prédéfinis ne suffisent pas',
	'Class:OAuthClientGoogle/Attribute:used_scope' => 'Niveaux d\'accès utilisés',
	'Class:OAuthClientGoogle/Attribute:used_scope+' => '',
	'Class:OAuthClientGoogle/Attribute:used_scope/Value:simple' => 'Simple',
	'Class:OAuthClientGoogle/Attribute:used_scope/Value:simple+' => '',
	'Class:OAuthClientGoogle/Attribute:used_scope/Value:advanced' => 'Avancé',
	'Class:OAuthClientGoogle/Attribute:used_scope/Value:advanced+' => '',
	'Class:OAuthClientGoogle/Attribute:used_for_smtp' => 'Utilisé pour SMTP',
	'Class:OAuthClientGoogle/Attribute:used_for_smtp+' => 'Le Client OAuth utilisé pour l\'envoi d\'emails doit être à \'Oui\'',
	'Class:OAuthClientGoogle/Attribute:used_for_smtp/Value:yes' => 'Oui',
	'Class:OAuthClientGoogle/Attribute:used_for_smtp/Value:no' => 'Non',
));
// 1:n relations custom labels for tooltip and pop-up title
Dict::Add('FR FR', 'French', 'Français', array(
	'Class:OAuthClient/Attribute:mailbox_list/UI:Links:Create:Button+' => 'Créer une %4$s',
	'Class:OAuthClient/Attribute:mailbox_list/UI:Links:Create:Modal:Title' => 'Ajouter une %4$s à %2$s',
	'Class:OAuthClient/Attribute:mailbox_list/UI:Links:Remove:Button+' => 'Retirer cette %4$s',
	'Class:OAuthClient/Attribute:mailbox_list/UI:Links:Remove:Modal:Title' => 'Retirer cette %4$s de son %1$s',
	'Class:OAuthClient/Attribute:mailbox_list/UI:Links:Delete:Button+' => 'Supprimer cette %4$s',
	'Class:OAuthClient/Attribute:mailbox_list/UI:Links:Delete:Modal:Title' => 'Supprimer une %4$s'
));
