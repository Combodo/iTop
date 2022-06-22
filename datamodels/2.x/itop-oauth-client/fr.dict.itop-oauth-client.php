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
	'itop-oauth-client:Message:OAuthClientCreated' => 'Générez les jetons d\'accès avant d\'utiliser cd client OAuth',
	'itop-oauth-client:Message:TokenCreated' => 'Le jeton d\'accès à été créé',
	'itop-oauth-client:Message:TokenRecreated' => 'Le jeton d\'accès à été renouvelé',
]);

//
// Class: OAuthClient
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:OAuthClient' => 'Client OAuth',
	'Class:OAuthClient/Attribute:provider' => 'Fournisseur',
	'Class:OAuthClient/Attribute:provider+' => '',
	'Class:OAuthClient/Attribute:name' => 'Login',
	'Class:OAuthClient/Attribute:name+' => '',
	'Class:OAuthClient/Attribute:scope' => 'Niveaux d\'accès',
	'Class:OAuthClient/Attribute:scope+' => '',
	'Class:OAuthClient/Attribute:description' => 'Description',
	'Class:OAuthClient/Attribute:description+' => '',
	'Class:OAuthClient/Attribute:client_id' => 'ID Client',
	'Class:OAuthClient/Attribute:client_id+' => '',
	'Class:OAuthClient/Attribute:client_secret' => 'Code secret du client',
	'Class:OAuthClient/Attribute:client_secret+' => '',
	'Class:OAuthClient/Attribute:refresh_token' => 'Jeton de renouvellement',
	'Class:OAuthClient/Attribute:refresh_token+' => '',
	'Class:OAuthClient/Attribute:refresh_token_expiration' => 'Date d\'expiration du jeton de renouvellement',
	'Class:OAuthClient/Attribute:refresh_token_expiration+' => '',
	'Class:OAuthClient/Attribute:token' => 'Jeton d\'accès',
	'Class:OAuthClient/Attribute:token+' => '',
	'Class:OAuthClient/Attribute:token_expiration' => 'Date d\'expiration du jeton d\'accès',
	'Class:OAuthClient/Attribute:token_expiration+' => '',
	'Class:OAuthClient/Attribute:redirect_url' => 'URL de redirection',
	'Class:OAuthClient/Attribute:redirect_url+' => '',
	'Class:OAuthClient/Attribute:mailbox_list' => 'Mailbox list',
	'Class:OAuthClient/Attribute:mailbox_list+' => '',
]);

//
// Class: OAuthClientAzure
//
Dict::Add('FR FR', 'French', 'Français', [
	'Class:OAuthClientAzure' => 'Client OAuth pour Microsoft Azure',
	'Class:OAuthClientAzure/Name' => '%1$s (%2$s)',

]);

//
// Class: OAuthClientGoogle
//
Dict::Add('FR FR', 'French', 'Français', [
	'Class:OAuthClientGoogle' => 'Client OAuth pour Google',
	'Class:OAuthClientGoogle/Name' => '%1$s (%2$s)',
]);


// Additional language entries not present in English dict
Dict::Add('FR FR', 'French', 'Français', array(
 'Class:OAuthClient/Name' => '%1$s-%%2$~',
));
