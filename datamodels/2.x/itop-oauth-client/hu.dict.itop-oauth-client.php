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
	'itop-oauth-client:Message:TokenCreated' => 'Hozzáférési token kész',
	'itop-oauth-client:Message:TokenRecreated' => 'Hozzáférési token újragenerálva',
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
	'Class:OAuthClient/Attribute:mailbox_list+' => '~~',
]);

//
// Class: OAuthClientAzure
//
Dict::Add('HU HU', 'Hungarian', 'Magyar', [
	'Class:OAuthClientAzure' => 'OAuth ügyfél Microsoft Azure-hoz',
	'Class:OAuthClientAzure/Name' => '%1$s (%2$s)',

]);

//
// Class: OAuthClientGoogle
//
Dict::Add('HU HU', 'Hungarian', 'Magyar', [
	'Class:OAuthClientGoogle' => 'OAuth ügyfél a Google-höz',
	'Class:OAuthClientGoogle/Name' => '%1$s (%2$s)',
]);

