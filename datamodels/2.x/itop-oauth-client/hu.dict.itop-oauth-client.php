<?php
/**
 * Localized data
 *
 * @copyright   Copyright (C) 2013 XXXXX
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
Dict::Add('HU HU', 'Hungarian', 'Magyar', [
	'Menu:CreateMailbox' => 'Postafiók létrehozása...',
	'Menu:OAuthClient' => 'OAuth ügyfél',
	'Menu:OAuthClient+' => '~~',
	'Menu:GenerateTokens' => 'Hozzáférési tokenek generálása...',
	'Menu:RegenerateTokens' => 'Hozzáférési tokenek újragenerálása...',
	'itop-oauth-client/Operation:CreateMailBox/Title' => 'Postafiók létrehozás',
	'itop-oauth-client:UsedForSMTP' => 'Ez az OAuth ügyfél SMTP-hez van használva',
	'itop-oauth-client:TestSMTP' => 'Email tesztüzenet',
	'itop-oauth-client:MissingOAuthClient' => 'Hiányzó Oauth ügyfél a %1$s felhasználóhoz',
	'itop-oauth-client:Message:MissingToken' => 'Hozzáférési token generálása az OAuth ügyfél használata előtt',
	'itop-oauth-client:Message:RegenerateToken' => 'Regenerate access token to to take into account the changes~~',
	'itop-oauth-client:Message:TokenCreated' => 'Hozzáférési token kész',
	'itop-oauth-client:Message:TokenRecreated' => 'Hozzáférési token újragenerálva',
	'itop-oauth-client:Message:TokenError' => 'Access token not generated due to server error~~',
	'OAuthClient:Name/UseForSMTPMustBeUnique' => 'The combination Login (%1$s) and Use for SMTP (%2$s) has already be used for OAuth Client~~',
	'OAuthClient:baseinfo' => 'Base Information~~',
	'OAuthClient:scope' => 'Scope~~',
]);

//
// Class: OAuthClient
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', [
	'Class:OAuthClient' => 'OAuth ügyfél',
	'Class:OAuthClient/Attribute:provider' => 'Szolgáltató',
	'Class:OAuthClient/Attribute:provider+' => '~~',
	'Class:OAuthClient/Attribute:name' => 'Bejelentkezés',
	'Class:OAuthClient/Attribute:name+' => '~~',
	'Class:OAuthClient/Attribute:scope' => 'Hatókör',
	'Class:OAuthClient/Attribute:scope+' => '~~',
	'Class:OAuthClient/Attribute:status' => 'Status~~',
	'Class:OAuthClient/Attribute:status+' => 'After creation, use the action “Generate access token” to be able to use this OAuth client~~',
	'Class:OAuthClient/Attribute:status/Value:active' => 'Access token generated~~',
	'Class:OAuthClient/Attribute:status/Value:inactive' => 'No Access token~~',
	'Class:OAuthClient/Attribute:description' => 'Leírás',
	'Class:OAuthClient/Attribute:description+' => '~~',
	'Class:OAuthClient/Attribute:client_id' => 'Ügyfél azonosító',
	'Class:OAuthClient/Attribute:client_id+' => '~~',
	'Class:OAuthClient/Attribute:client_secret' => 'Ügyfél kulcs',
	'Class:OAuthClient/Attribute:client_secret+' => '~~',
	'Class:OAuthClient/Attribute:refresh_token' => 'Token frissítése',
	'Class:OAuthClient/Attribute:refresh_token+' => '~~',
	'Class:OAuthClient/Attribute:refresh_token_expiration' => 'A token lejáratának frissítése',
	'Class:OAuthClient/Attribute:refresh_token_expiration+' => '~~',
	'Class:OAuthClient/Attribute:token' => 'Hozzáférési token',
	'Class:OAuthClient/Attribute:token+' => '~~',
	'Class:OAuthClient/Attribute:token_expiration' => 'Hozzáférési token lejárata',
	'Class:OAuthClient/Attribute:token_expiration+' => '~~',
	'Class:OAuthClient/Attribute:redirect_url' => 'URL átirányítás',
	'Class:OAuthClient/Attribute:redirect_url+' => '~~',
	'Class:OAuthClient/Attribute:mailbox_list' => 'Postafiók lista',
	'Class:OAuthClient/Attribute:mailbox_list+' => '~~'
]);

//
// Class: OAuthClientAzure
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:OAuthClientAzure' => 'OAuth ügyfél Microsoft Azure-hoz',
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

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:OAuthClientGoogle' => 'OAuth ügyfél a Google-höz',
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
