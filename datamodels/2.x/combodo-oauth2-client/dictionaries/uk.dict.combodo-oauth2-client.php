<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

/**
 * Localized data
 */
Dict::Add('UK UA', 'Ukrainian', 'Українська', [
	'Oauth2Client:UI:IDPParameters' => 'Дані для провайдера ідентифікації',
	'Oauth2Client:UI:AppURL' => 'URL додатку',
	'Oauth2Client:UI:AppURL+' => 'Ця URL-адреса має бути скопійована в конфігурацію OAuth2 провайдера',
	'Oauth2Client:UI:LandingURL' => 'Цільова URL-адреса',
	'Oauth2Client:UI:LandingURL+' => 'Ця URL-адреса має бути скопійована в конфігурацію OAuth2 провайдера',

	'Oauth2Client:UI:Message:ValidationOK' => 'Перевірка пройшла успішно:<BR/>%1$s',
	'Oauth2Client:UI:Message:ValidationError' => 'Помилка перевірки токена: %1$s',

	'Oauth2Client:UI:Button:GetToken' => 'Показати токен доступу. Якщо термін дії закінчився, токен доступу буде оновлено через OAuth.',
	'Oauth2Client:UI:Button:Authenticate' => 'Автентифікуватися в IDP через OAuth.',

	'Oauth2Client:UI:Error:RefreshTokenNotAvailable' => 'Токен оновлення недоступний',

	'Menu:Oauth2Client' => 'Клієнт OAuth 2.0',
	'Menu:Oauth2Client+' => 'Універсальний клієнт OAuth 2.0',

	'Class:Oauth2Client' => 'Клієнт OAuth 2.0',
	'Class:Oauth2Client/Attribute:name' => 'Назва',
	'Class:Oauth2Client/Attribute:name+' => '',
	'Class:Oauth2Client/Attribute:provider' => 'Провайдер',
	'Class:Oauth2Client/Attribute:provider+' => '',
	'Class:Oauth2Client/Attribute:description' => 'Опис',
	'Class:Oauth2Client/Attribute:description+' => '',
	'Class:Oauth2Client/Attribute:client_id' => 'ID клієнта',
	'Class:Oauth2Client/Attribute:client_id+' => 'Довгий рядок символів, наданий вашим провайдером OAuth2',
	'Class:Oauth2Client/Attribute:client_secret' => 'Секретний ключ клієнта',
	'Class:Oauth2Client/Attribute:client_secret+' => 'Інший довгий рядок символів, наданий вашим провайдером OAuth2',
	'Class:Oauth2Client/Attribute:refresh_token' => 'Токен оновлення',
	'Class:Oauth2Client/Attribute:refresh_token+' => 'Токен, необхідний для автоматичного оновлення токена доступу',
	'Class:Oauth2Client/Attribute:access_token' => 'Токен доступу',
	'Class:Oauth2Client/Attribute:access_token+' => 'Токен, необхідний для виклику віддалених API OAuth2',
	'Class:Oauth2Client/Attribute:refresh_token_expiration' => 'Термін дії токена оновлення',
	'Class:Oauth2Client/Attribute:refresh_token_expiration+' => 'Дата закінчення терміну дії, після якої токени доступу, термін дії яких закінчився, більше не можуть бути оновлені. Необхідно повторно перевірити поточний клієнт OAuth 2.0 у вашого провайдера ідентифікації',
	'Class:Oauth2Client/Attribute:access_token_expiration' => 'Термін дії токена доступу',
	'Class:Oauth2Client/Attribute:access_token_expiration+' => 'Дата закінчення терміну дії токена доступу, після якої API OAuth2 більше не можуть бути викликані. Після цієї дати iTop автоматично оновлює токен доступу (якщо це можливо) через обмін OAuth2, щоб зберегти та надати дійсний токен доступу.',
	'Class:Oauth2Client/Attribute:scope' => 'Область дії',
	'Class:Oauth2Client/Attribute:scope+' => 'Залиште це поле порожнім, і воно буде заповнено iTop на основі провайдера під час створення об\'єкта',
	'Class:Oauth2Client/Attribute:authorization_state' => 'Стан авторизації',
	'Class:Oauth2Client/Attribute:authorization_state+' => '',
	'Class:Oauth2Client/Attribute:token_type' => 'Тип токена',
	'Class:Oauth2Client/Attribute:token_type+' => '',

	'Class:GitHubOauth2Client' => 'Клієнт GitHub OAuth 2.0',

	'Class:MicrosoftGraphOauth2Client' => 'Клієнт Microsoft Graph OAuth 2.0',
	'Class:MicrosoftGraphOauth2Client/Attribute:tenant' => 'Клієнт',
	'Class:MicrosoftGraphOauth2Client/Attribute:tenant+' => 'ID клієнта налаштованого додатку. Для багатоклієнтського додатку використовуйте "common".',

	'Class:GoogleOauth2Client' => 'Клієнт Google OAuth 2.0',

	'Class:HeadlessOauth2Client' => 'Headless клієнт OAuth 2.0',
	'Class:HeadlessOauth2Client/Attribute:username' => 'Ім\'я користувача',
	'Class:HeadlessOauth2Client/Attribute:username+' => 'Логін користувача iTop',
	'Class:HeadlessOauth2Client/Attribute:password' => 'Пароль',
	'Class:HeadlessOauth2Client/Attribute:password+' => 'Пароль користувача iTop',
	'Class:HeadlessOauth2Client/Attribute:base_url' => 'Базова URL-адреса',
	'Class:HeadlessOauth2Client/Attribute:base_url+' => 'URL-адреса додатку iTop.',
	'Class:HeadlessOauth2Client/Attribute:version' => 'Версія',
	'Class:HeadlessOauth2Client/Attribute:version+' => '',

	'Class:KeycloakOauth2Client' => 'Клієнт Keycloak OAuth 2.0',
	'Class:KeycloakOauth2Client/Attribute:url' => 'URL-адреса',
	'Class:KeycloakOauth2Client/Attribute:url+' => 'URL-адреса додатку Keycloak.',
	'Class:KeycloakOauth2Client/Attribute:realm' => 'Область',
	'Class:KeycloakOauth2Client/Attribute:realm+' => 'Область налаштованого додатку.',

	'OAuth2Client:baseinfo' => 'Призначення',
	'OAuth2Client:idp_info' => 'Дані від провайдера ідентифікації',
]);
