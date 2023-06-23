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
	'itop-oauth-client:Message:RegenerateToken' => 'Regenerate access token to to take into account the changes~~',
	'itop-oauth-client:Message:TokenCreated' => 'Zugriffs-Token erstellt',
	'itop-oauth-client:Message:TokenRecreated' => 'Zugriffs-Token neu erstellt',
	'itop-oauth-client:Message:TokenError' => 'Access token not generated due to server error~~',
	'OAuthClient:Name/UseForSMTPMustBeUnique' => 'The combination Login (%1$s) and Use for SMTP (%2$s) has already be used for OAuth Client~~',
	'OAuthClient:baseinfo' => 'Base Information~~',
	'OAuthClient:scope' => 'Scope~~',
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
	'Class:OAuthClient/Attribute:status' => 'Status~~',
	'Class:OAuthClient/Attribute:status+' => 'After creation, use the action “Generate access token” to be able to use this OAuth client~~',
	'Class:OAuthClient/Attribute:status/Value:active' => 'Access token generated~~',
	'Class:OAuthClient/Attribute:status/Value:inactive' => 'No Access token~~',
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
	'Class:OAuthClient/Attribute:mailbox_list+' => ''
]);

//
// Class: OAuthClientAzure
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:OAuthClientAzure' => 'OAuth-Client für Microsoft Azure',
	'Class:OAuthClientAzure/Name' => '%1$s (%2$s)',
	'Class:OAuthClientAzure/Attribute:scope' => 'Scope~~',
	'Class:OAuthClientAzure/Attribute:scope+' => 'Usually default selection is appropriate~~',
	'Class:OAuthClientAzure/Attribute:scope/Value:SMTP' => 'SMTP~~',
	'Class:OAuthClientAzure/Attribute:scope/Value:SMTP+' => '~~',
	'Class:OAuthClientAzure/Attribute:scope/Value:IMAP' => 'IMAP~~',
	'Class:OAuthClientAzure/Attribute:scope/Value:IMAP+' => '~~',
	'Class:OAuthClientAzure/Attribute:advanced_scope' => 'Advanced scope~~',
	'Class:OAuthClientAzure/Attribute:advanced_scope+' => 'As soon as you enter something here it takes precedence on the “Scope” selection which is then ignored~~',
	'Class:OAuthClientAzure/Attribute:used_scope' => 'Used scope~~',
	'Class:OAuthClientAzure/Attribute:used_scope+' => '~~',
	'Class:OAuthClientAzure/Attribute:used_scope/Value:simple' => 'Simple~~',
	'Class:OAuthClientAzure/Attribute:used_scope/Value:simple+' => '~~',
	'Class:OAuthClientAzure/Attribute:used_scope/Value:advanced' => 'Advanced~~',
	'Class:OAuthClientAzure/Attribute:used_scope/Value:advanced+' => '~~',
	'Class:OAuthClientAzure/Attribute:used_for_smtp' => 'Used for SMTP~~',
	'Class:OAuthClientAzure/Attribute:used_for_smtp+' => 'At least one OAuth client must have this flag to “Yes”, if you want iTop to use it for sending mails~~',
	'Class:OAuthClientAzure/Attribute:used_for_smtp/Value:yes' => 'Yes~~',
	'Class:OAuthClientAzure/Attribute:used_for_smtp/Value:no' => 'No~~',
));

//
// Class: OAuthClientGoogle
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:OAuthClientGoogle' => 'OAuth-Client für Google',
	'Class:OAuthClientGoogle/Name' => '%1$s (%2$s)',
	'Class:OAuthClientGoogle/Attribute:scope' => 'Scope~~',
	'Class:OAuthClientGoogle/Attribute:scope+' => 'Usually default selection is appropriate~~',
	'Class:OAuthClientGoogle/Attribute:scope/Value:SMTP' => 'SMTP~~',
	'Class:OAuthClientGoogle/Attribute:scope/Value:SMTP+' => '~~',
	'Class:OAuthClientGoogle/Attribute:scope/Value:IMAP' => 'IMAP~~',
	'Class:OAuthClientGoogle/Attribute:scope/Value:IMAP+' => '~~',
	'Class:OAuthClientGoogle/Attribute:advanced_scope' => 'Advanced scope~~',
	'Class:OAuthClientGoogle/Attribute:advanced_scope+' => 'As soon as you enter something here it takes precedence on the “Scope” selection which is then ignored~~',
	'Class:OAuthClientGoogle/Attribute:used_scope' => 'Used scope~~',
	'Class:OAuthClientGoogle/Attribute:used_scope+' => '~~',
	'Class:OAuthClientGoogle/Attribute:used_scope/Value:simple' => 'Simple~~',
	'Class:OAuthClientGoogle/Attribute:used_scope/Value:simple+' => '~~',
	'Class:OAuthClientGoogle/Attribute:used_scope/Value:advanced' => 'Advanced~~',
	'Class:OAuthClientGoogle/Attribute:used_scope/Value:advanced+' => '~~',
	'Class:OAuthClientGoogle/Attribute:used_for_smtp' => 'Used for SMTP~~',
	'Class:OAuthClientGoogle/Attribute:used_for_smtp+' => 'At least one OAuth client must have this flag to “Yes”, if you want iTop to use it for sending mails~~',
	'Class:OAuthClientGoogle/Attribute:used_for_smtp/Value:yes' => 'Yes~~',
	'Class:OAuthClientGoogle/Attribute:used_for_smtp/Value:no' => 'No~~',
));
