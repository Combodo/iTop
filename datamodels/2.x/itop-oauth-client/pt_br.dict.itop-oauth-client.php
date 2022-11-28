<?php
/**
 * Localized data
 *
 * @copyright   Copyright (C) 2013 XXXXX
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('PT BR', 'Brazilian', 'Brazilian', [
	'Menu:CreateMailbox' => 'Criar uma caixa de e-mail...',
	'Menu:OAuthClient' => 'Clientes OAuth',
	'Menu:OAuthClient+' => '',
	'Menu:GenerateTokens' => 'Criar tokens de acesso...',
	'Menu:RegenerateTokens' => 'Recriar tokens de acesso...',

	'itop-oauth-client/Operation:CreateMailBox/Title' => 'Criação de caixa de e-mail',

	'itop-oauth-client:UsedForSMTP' => 'Este cliente OAuth é usado para SMTP',
	'itop-oauth-client:TestSMTP' => 'Enviar e-mail de teste',
	'itop-oauth-client:MissingOAuthClient' => 'Cliente OAuth ausente para o nome de usuário %1$s',
	'itop-oauth-client:Message:MissingToken' => 'Crie o token de acesso antes de usar este cliente OAuth',
	'itop-oauth-client:Message:TokenCreated' => 'Token de acesso criado',
	'itop-oauth-client:Message:TokenRecreated' => 'Token de acceso recriado',
]);

//
// Class: OAuthClient
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', [
	'Class:OAuthClient' => 'Cliente OAuth',
	'Class:OAuthClient/Attribute:provider' => 'Provedor',
	'Class:OAuthClient/Attribute:provider+' => '',
	'Class:OAuthClient/Attribute:name' => 'Login',
	'Class:OAuthClient/Attribute:name+' => '',
	'Class:OAuthClient/Attribute:scope' => 'Escopo',
	'Class:OAuthClient/Attribute:scope+' => '',
	'Class:OAuthClient/Attribute:description' => 'Descrição',
	'Class:OAuthClient/Attribute:description+' => '',
	'Class:OAuthClient/Attribute:client_id' => 'ID de cliente',
	'Class:OAuthClient/Attribute:client_id+' => '',
	'Class:OAuthClient/Attribute:client_secret' => 'Segredo do cliente',
	'Class:OAuthClient/Attribute:client_secret+' => '',
	'Class:OAuthClient/Attribute:refresh_token' => 'Atualizar token',
	'Class:OAuthClient/Attribute:refresh_token+' => '',
	'Class:OAuthClient/Attribute:refresh_token_expiration' => 'Atualizar expiração do token',
	'Class:OAuthClient/Attribute:refresh_token_expiration+' => '',
	'Class:OAuthClient/Attribute:token' => 'Token de acesso',
	'Class:OAuthClient/Attribute:token+' => '',
	'Class:OAuthClient/Attribute:token_expiration' => 'Expiração do token de acesso',
	'Class:OAuthClient/Attribute:token_expiration+' => '',
	'Class:OAuthClient/Attribute:redirect_url' => 'URL de redirecionamento',
	'Class:OAuthClient/Attribute:redirect_url+' => '',
	'Class:OAuthClient/Attribute:mailbox_list' => 'Lista de caixa de e-mail',
	'Class:OAuthClient/Attribute:mailbox_list+' => '',
]);

//
// Class: OAuthClientAzure
//
Dict::Add('PT BR', 'Brazilian', 'Brazilian', [
	'Class:OAuthClientAzure' => 'Cliente OAuth para Microsoft Azure',
	'Class:OAuthClientAzure/Name' => '%1$s (%2$s)~~',

]);

//
// Class: OAuthClientGoogle
//
Dict::Add('PT BR', 'Brazilian', 'Brazilian', [
	'Class:OAuthClientGoogle' => 'Cliente OAuth para Google',
	'Class:OAuthClientGoogle/Name' => '%1$s (%2$s)~~',
]);
