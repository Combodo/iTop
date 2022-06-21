<?php
/**
 * Localized data
 *
 * @copyright   Copyright (C) 2013 XXXXX
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('EN US', 'English', 'English', [
	'Menu:CreateMailbox' => 'Create a mailbox',
	'Menu:RemoteOAuth' => 'Remote OAuth',
	'Menu:GenerateTokens' => 'Generate tokens',
	'Menu:RegenerateTokens' => 'Regenerate tokens',

	'itop-remote-authent-oauth/Operation:CreateMailBox/Title' => 'Mailbox creation',

	'itop-remote-authent-oauth:UsedForSMTP' => 'This connection is used for SMTP',
	'itop-remote-authent-oauth:TestSMTP' => 'Email test',
	'itop-remote-authent-oauth:MissingRemoteAuthentOAuth' => 'Missing Remote Authentication (OAuth) for user name %1$s',
	'itop-remote-authent-oauth:Message:TokenCreated' => 'Connection token created',
	'itop-remote-authent-oauth:Message:TokenRecreated' => 'Connection token regenerated',

	'Class:RemoteAuthentOAuthGoogle' => 'Remote Google Authentication (OAuth)',
	'Class:RemoteAuthentOAuthAzure' => 'Remote Microsoft Azure Authentication (OAuth)',
]);

//
// Class: RemoteAuthentOAuth
//

Dict::Add('EN US', 'English', 'English', [
	'Class:RemoteAuthentOAuth' => 'Remote Authentication (OAuth)',
	'Class:RemoteAuthentOAuth/Name' => '%1$s-%%2$s',
	'Class:RemoteAuthentOAuth/Attribute:provider' => 'Provider',
	'Class:RemoteAuthentOAuth/Attribute:provider+' => '',
	'Class:RemoteAuthentOAuth/Attribute:name' => 'Login',
	'Class:RemoteAuthentOAuth/Attribute:name+' => '',
	'Class:RemoteAuthentOAuth/Attribute:scope' => 'Scope',
	'Class:RemoteAuthentOAuth/Attribute:scope+' => '',
	'Class:RemoteAuthentOAuth/Attribute:description' => 'Description',
	'Class:RemoteAuthentOAuth/Attribute:description+' => '',
	'Class:RemoteAuthentOAuth/Attribute:client_id' => 'Client id',
	'Class:RemoteAuthentOAuth/Attribute:client_id+' => '',
	'Class:RemoteAuthentOAuth/Attribute:client_secret' => 'Client secret',
	'Class:RemoteAuthentOAuth/Attribute:client_secret+' => '',
	'Class:RemoteAuthentOAuth/Attribute:refresh_token' => 'Refresh token',
	'Class:RemoteAuthentOAuth/Attribute:refresh_token+' => '',
	'Class:RemoteAuthentOAuth/Attribute:refresh_token_expiration' => 'Refresh token expiration',
	'Class:RemoteAuthentOAuth/Attribute:refresh_token_expiration+' => '',
	'Class:RemoteAuthentOAuth/Attribute:token' => 'Token',
	'Class:RemoteAuthentOAuth/Attribute:token+' => '',
	'Class:RemoteAuthentOAuth/Attribute:token_expiration' => 'Token expiration',
	'Class:RemoteAuthentOAuth/Attribute:token_expiration+' => '',
	'Class:RemoteAuthentOAuth/Attribute:redirect_url' => 'Redirect url',
	'Class:RemoteAuthentOAuth/Attribute:redirect_url+' => '',
]);
