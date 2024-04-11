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
	'Menu:GenerateTokens' => 'Zugriffstoken generieren...',
	'Menu:RegenerateTokens' => 'Zugriffstoken neu generieren...',
	'itop-oauth-client/Operation:CreateMailBox/Title' => 'Mailpostfach-Erstellung',
	'itop-oauth-client:UsedForSMTP' => 'Dieser OAuth-Client wird für SMTP verwendet',
	'itop-oauth-client:TestSMTP' => 'Mail-Versand testen',
	'itop-oauth-client:MissingOAuthClient' => 'Fehlender OAuth-Client für den Benutzernamen %1$s',
	'itop-oauth-client:Message:MissingToken' => 'Bitte Zugriffstoken generieren bevor der OAuth-Client verwendet wird',
	'itop-oauth-client:Message:RegenerateToken' => 'Generieren Sie das Zugriffstoken neu, um die Änderungen zu berücksichtigen',
	'itop-oauth-client:Message:TokenCreated' => 'Zugriffstoken erstellt',
	'itop-oauth-client:Message:TokenRecreated' => 'Zugriffstoken neu erstellt',
	'itop-oauth-client:Message:TokenError' => 'Das Zugriffstoken wurde aufgrund eines Serverfehlers nicht generiert.',
	'OAuthClient:Name/UseForSMTPMustBeUnique' => 'Die Kombination aus "Login" (%1$s) und "Verwendung für SMTP" (%2$s) existiert bereits.',
	'OAuthClient:baseinfo' => 'Allgemeine Informationen',
	'OAuthClient:scope' => 'Scope',
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
	'Class:OAuthClient/Attribute:status' => 'Status',
	'Class:OAuthClient/Attribute:status+' => 'Führen Sie nach der Objekterstellung die Aktion "Zugriffstoken generieren..." aus, um diesen oAuth-Client zu aktivieren.',
	'Class:OAuthClient/Attribute:status/Value:active' => 'Zugriffstoken erstellt',
	'Class:OAuthClient/Attribute:status/Value:inactive' => 'Kein Zugriffstoken',
	'Class:OAuthClient/Attribute:description' => 'Beschreibung',
	'Class:OAuthClient/Attribute:description+' => '',
	'Class:OAuthClient/Attribute:client_id' => 'Client ID',
	'Class:OAuthClient/Attribute:client_id+' => 'Eine lange Zeichenfolge, die durch den oAuth2-Provider bereitgestellt wird',
	'Class:OAuthClient/Attribute:client_secret' => 'Client Secret',
	'Class:OAuthClient/Attribute:client_secret+' => 'Eine weitere lange Zeichenfolge, die durch den oAuth2-Provider bereitgestellt wird',
	'Class:OAuthClient/Attribute:refresh_token' => 'Erneuerungs-Token',
	'Class:OAuthClient/Attribute:refresh_token+' => '',
	'Class:OAuthClient/Attribute:refresh_token_expiration' => 'Erneuerungs-Token Ablaufzeitpunkt',
	'Class:OAuthClient/Attribute:refresh_token_expiration+' => '',
	'Class:OAuthClient/Attribute:token' => 'Zugriffstoken',
	'Class:OAuthClient/Attribute:token+' => '',
	'Class:OAuthClient/Attribute:token_expiration' => 'Zugriffstoken Ablaufszeitpunkt',
	'Class:OAuthClient/Attribute:token_expiration+' => '',
	'Class:OAuthClient/Attribute:redirect_url' => 'Umleitungs-URL',
	'Class:OAuthClient/Attribute:redirect_url+' => <<<EOF
Diese URL muss in die oAuth2-Konfiguration des Providers kopiert werden.
Löschen Sie das Feld, um den Standardwert neu zu berechnen.
EOF
,
	'Class:OAuthClient/Attribute:mailbox_list' => 'Mailpostfächer',
	'Class:OAuthClient/Attribute:mailbox_list+' => ''
]);

//
// Class: OAuthClientAzure
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:OAuthClientAzure' => 'OAuth-Client für Microsoft Azure',
	'Class:OAuthClientAzure/Name' => '%1$s (%2$s)',
	'Class:OAuthClientAzure/Attribute:scope' => 'Scope',
	'Class:OAuthClientAzure/Attribute:scope+' => 'Normalerweise ist die Standardauswahl ausreichend.',
	'Class:OAuthClientAzure/Attribute:scope/Value:SMTP' => 'SMTP',
	'Class:OAuthClientAzure/Attribute:scope/Value:SMTP+' => '',
	'Class:OAuthClientAzure/Attribute:scope/Value:IMAP' => 'IMAP',
	'Class:OAuthClientAzure/Attribute:scope/Value:IMAP+' => '',
	'Class:OAuthClientAzure/Attribute:advanced_scope' => 'Erweiterter Scope',
	'Class:OAuthClientAzure/Attribute:advanced_scope+' => 'Sobald Sie hier etwas eingeben, hat es Vorrang vor der Auswahl im Feld "Scope", die dann ignoriert wird.',
	'Class:OAuthClientAzure/Attribute:used_scope' => 'Angewendeter Scope',
	'Class:OAuthClientAzure/Attribute:used_scope+' => '',
	'Class:OAuthClientAzure/Attribute:used_scope/Value:simple' => 'Einfach',
	'Class:OAuthClientAzure/Attribute:used_scope/Value:simple+' => '',
	'Class:OAuthClientAzure/Attribute:used_scope/Value:advanced' => 'Erweitert',
	'Class:OAuthClientAzure/Attribute:used_scope/Value:advanced+' => '',
	'Class:OAuthClientAzure/Attribute:used_for_smtp' => 'Verwendung für SMTP',
	'Class:OAuthClientAzure/Attribute:used_for_smtp+' => 'Mindestens ein oAuth-Client muss dieses Flag auf "Ja" gesetzt haben, um über diesen Weg Mails durch iTop senden zu lassen.',
	'Class:OAuthClientAzure/Attribute:used_for_smtp/Value:yes' => 'Ja',
	'Class:OAuthClientAzure/Attribute:used_for_smtp/Value:no' => 'Nein',
));

//
// Class: OAuthClientGoogle
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:OAuthClientGoogle' => 'OAuth-Client für Google',
	'Class:OAuthClientGoogle/Name' => '%1$s (%2$s)',
	'Class:OAuthClientGoogle/Attribute:scope' => 'Scope',
	'Class:OAuthClientGoogle/Attribute:scope+' => 'Normalerweise ist die Standardauswahl ausreichend.',
	'Class:OAuthClientGoogle/Attribute:scope/Value:SMTP' => 'SMTP',
	'Class:OAuthClientGoogle/Attribute:scope/Value:SMTP+' => '',
	'Class:OAuthClientGoogle/Attribute:scope/Value:IMAP' => 'IMAP',
	'Class:OAuthClientGoogle/Attribute:scope/Value:IMAP+' => '',
	'Class:OAuthClientGoogle/Attribute:advanced_scope' => 'Erweiterter Scope',
	'Class:OAuthClientGoogle/Attribute:advanced_scope+' => 'Sobald Sie hier etwas eingeben, hat es Vorrang vor der Auswahl im Feld "Scope", die dann ignoriert wird.',
	'Class:OAuthClientGoogle/Attribute:used_scope' => 'Angewendeter Scope',
	'Class:OAuthClientGoogle/Attribute:used_scope+' => '',
	'Class:OAuthClientGoogle/Attribute:used_scope/Value:simple' => 'Einfach',
	'Class:OAuthClientGoogle/Attribute:used_scope/Value:simple+' => '',
	'Class:OAuthClientGoogle/Attribute:used_scope/Value:advanced' => 'Erweitert',
	'Class:OAuthClientGoogle/Attribute:used_scope/Value:advanced+' => '',
	'Class:OAuthClientGoogle/Attribute:used_for_smtp' => 'Verwendung für SMTP',
	'Class:OAuthClientGoogle/Attribute:used_for_smtp+' => 'Mindestens ein oAuth-Client muss dieses Flag auf "Ja" gesetzt haben, um über diesen Weg Mails durch iTop senden zu lassen.',
	'Class:OAuthClientGoogle/Attribute:used_for_smtp/Value:yes' => 'Ja',
	'Class:OAuthClientGoogle/Attribute:used_for_smtp/Value:no' => 'Nein',
));
