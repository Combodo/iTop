<?php
/**
 * Localized data
 *
 * @copyright   Copyright (C) 2013 XXXXX
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
Dict::Add('FR FR', 'French', 'Français', [
	'Menu:CreateMailbox' => 'Créer une boite mail...',
	'Menu:RemoteOAuth' => 'Client OAuth',
	'Menu:GenerateTokens' => 'Créer un jeton d\'accès...',
	'Menu:RegenerateTokens' => 'Recréer un jeton d\'accès..',

	'itop-remote-authent-oauth/Operation:CreateMailBox/Title' => 'Création de boite mail',

	'itop-remote-authent-oauth:UsedForSMTP' => 'Ce client Oauth est utilisé pour SMTP',
	'itop-remote-authent-oauth:TestSMTP' => 'Tester l\'envoi de mail',
	'itop-remote-authent-oauth:MissingRemoteAuthentOAuth' => 'Il n\'y a pas de client OAuth pour l\'utilisateur %1$s',
	'itop-remote-authent-oauth:Message:TokenCreated' => 'Le jeton d\'accès à été créé',
	'itop-remote-authent-oauth:Message:TokenRecreated' => 'Le jeton d\'accès à été renouvelé',

	'Class:RemoteAuthentOAuthGoogle' => 'Client OAuth pour Google',
	'Class:RemoteAuthentOAuthAzure' => 'Client OAuth pour Microsoft Azure',
]);

//
// Class: RemoteAuthentOAuth
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:RemoteAuthentOAuth' => 'Client OAuth',
	'Class:RemoteAuthentOAuth/Name' => '%1$s-%%2$~',
	'Class:RemoteAuthentOAuth/Attribute:provider' => 'Fournisseur',
	'Class:RemoteAuthentOAuth/Attribute:provider+' => '',
	'Class:RemoteAuthentOAuth/Attribute:name' => 'Login',
	'Class:RemoteAuthentOAuth/Attribute:name+' => '',
	'Class:RemoteAuthentOAuth/Attribute:scope' => 'Niveaux d\'accès',
	'Class:RemoteAuthentOAuth/Attribute:scope+' => '',
	'Class:RemoteAuthentOAuth/Attribute:description' => 'Description',
	'Class:RemoteAuthentOAuth/Attribute:description+' => '',
	'Class:RemoteAuthentOAuth/Attribute:client_id' => 'ID Client',
	'Class:RemoteAuthentOAuth/Attribute:client_id+' => '',
	'Class:RemoteAuthentOAuth/Attribute:client_secret' => 'Code secret du client',
	'Class:RemoteAuthentOAuth/Attribute:client_secret+' => '',
	'Class:RemoteAuthentOAuth/Attribute:refresh_token' => 'Jeton de renouvellement',
	'Class:RemoteAuthentOAuth/Attribute:refresh_token+' => '',
	'Class:RemoteAuthentOAuth/Attribute:refresh_token_expiration' => 'Date d\'expiration du jeton de renouvellement',
	'Class:RemoteAuthentOAuth/Attribute:refresh_token_expiration+' => '',
	'Class:RemoteAuthentOAuth/Attribute:token' => 'Jeton d\'accès',
	'Class:RemoteAuthentOAuth/Attribute:token+' => '',
	'Class:RemoteAuthentOAuth/Attribute:token_expiration' => 'Date d\'expiration du jeton d\'accès',
	'Class:RemoteAuthentOAuth/Attribute:token_expiration+' => '',
	'Class:RemoteAuthentOAuth/Attribute:redirect_url' => 'URL de redirection',
	'Class:RemoteAuthentOAuth/Attribute:redirect_url+' => '',
	'Class:RemoteAuthentOAuth/Attribute:mailbox_list' => 'Mailbox list',
	'Class:RemoteAuthentOAuth/Attribute:mailbox_list+' => '',
]);

