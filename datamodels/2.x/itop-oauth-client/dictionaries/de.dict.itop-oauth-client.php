<?php
/**
 * Localized data
 *
 * @copyright   Copyright (C) 2013 XXXXX
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('DE DE', 'German', 'Deutsch', [
	'Menu:CreateMailbox' => 'Mailpostfach erstellen...',
	'Menu:OAuthClient' => 'OAuth-Client',
	'Menu:OAuthClient+' => '',
	'Menu:GenerateTokens' => 'Zugriffs-Token generieren...',
	'Menu:RegenerateTokens' => 'Zugriffs-Token neu generieren...',

	'itop-oauth-client/Operation:CreateMailBox/Title' => 'Mailpostfach-Erstellung',

	'itop-oauth-client:UsedForSMTP' => 'Dieser OAuth-Client wird für SMTP verwendet',
	'itop-oauth-client:TestSMTP' => 'Mail-Versand testen',
	'itop-oauth-client:MissingOAuthClient' => 'Fehlender OAuth-Client für den Benutzernamen %1$s',
	'itop-oauth-client:Message:MissingToken' => 'Bitte Zugriffs-Token generieren bevor der OAuth-Client verwendet wird',
	'itop-oauth-client:Message:TokenCreated' => 'Zugriffs-Token erstellt',
	'itop-oauth-client:Message:TokenRecreated' => 'Zugriffs-Token neu erstellt',
]);

//
// Class: OAuthClient
//

Dict::Add('DE DE', 'German', 'Deutsch', [
	'Class:OAuthClient' => 'OAuth-Client',
	'Class:OAuthClient/Attribute:provider' => 'Provider',
	'Class:OAuthClient/Attribute:provider+' => '',
	'Class:OAuthClient/Attribute:name' => 'Login',
	'Class:OAuthClient/Attribute:name+' => '',
	'Class:OAuthClient/Attribute:scope' => 'Scope',
	'Class:OAuthClient/Attribute:scope+' => '',
	'Class:OAuthClient/Attribute:description' => 'Beschreibung',
	'Class:OAuthClient/Attribute:description+' => '',
	'Class:OAuthClient/Attribute:client_id' => 'Client ID',
	'Class:OAuthClient/Attribute:client_id+' => '',
	'Class:OAuthClient/Attribute:client_secret' => 'Client Secret',
	'Class:OAuthClient/Attribute:client_secret+' => '',
	'Class:OAuthClient/Attribute:refresh_token' => 'Erneuerungs-Token',
	'Class:OAuthClient/Attribute:refresh_token+' => '',
	'Class:OAuthClient/Attribute:refresh_token_expiration' => 'Erneuerungs-Token Ablaufzeitpunkt',
	'Class:OAuthClient/Attribute:refresh_token_expiration+' => '',
	'Class:OAuthClient/Attribute:token' => 'Zugriffs-Token',
	'Class:OAuthClient/Attribute:token+' => '',
	'Class:OAuthClient/Attribute:token_expiration' => 'Zugriffs-Token Ablaufszeitpunkt',
	'Class:OAuthClient/Attribute:token_expiration+' => '',
	'Class:OAuthClient/Attribute:redirect_url' => 'Umleitungs-URL',
	'Class:OAuthClient/Attribute:redirect_url+' => '',
	'Class:OAuthClient/Attribute:mailbox_list' => 'Mailpostfächer',
	'Class:OAuthClient/Attribute:mailbox_list+' => '',
]);

//
// Class: OAuthClientAzure
//
Dict::Add('DE DE', 'German', 'Deutsch', [
	'Class:OAuthClientAzure' => 'OAuth-Client für Microsoft Azure',
	'Class:OAuthClientAzure/Name' => '%1$s (%2$s)',

]);

//
// Class: OAuthClientGoogle
//
Dict::Add('DE DE', 'German', 'Deutsch', [
	'Class:OAuthClientGoogle' => 'OAuth-Client für Google',
	'Class:OAuthClientGoogle/Name' => '%1$s (%2$s)',
]);

