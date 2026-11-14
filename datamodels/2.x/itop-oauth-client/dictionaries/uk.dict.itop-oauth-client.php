<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2026 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * 
 */
/**
 *
 */
Dict::Add('UK UA', 'Ukrainian', 'Українська', [
	'Menu:CreateMailbox' => 'Створити поштову скриньку...',
	'Menu:OAuthClient' => 'Доступ до пошти через OAuth',
	'Menu:OAuthClient+' => '',
	'Menu:GenerateTokens' => 'Згенерувати токен доступу...',
	'Menu:RegenerateTokens' => 'Перегенерувати токен доступу...',
	'itop-oauth-client/Operation:CreateMailBox/Title' => 'Створення поштової скриньки',
	'itop-oauth-client:UsedForSMTP' => 'Цей OAuth-клієнт використовується для SMTP',
	'itop-oauth-client:TestSMTP' => 'Тест надсилання електронної пошти',
	'itop-oauth-client:MissingOAuthClient' => 'Відсутній OAuth-клієнт для імені користувача %1$s',
	'itop-oauth-client:Message:MissingToken' => 'Згенеруйте токен доступу перед використанням цього OAuth-клієнта',
	'itop-oauth-client:Message:RegenerateToken' => 'Перегенеруйте токен доступу, щоб врахувати зміни',
	'itop-oauth-client:Message:TokenCreated' => 'Токен доступу створено',
	'itop-oauth-client:Message:TokenRecreated' => 'Токен доступу перегенеровано',
	'itop-oauth-client:Message:TokenError' => 'Токен доступу не згенеровано через помилку сервера',
	'OAuthClient:Name/UseForSMTPMustBeUnique' => 'Комбінація Логін (%1$s) та Використовувати для SMTP (%2$s) вже використовується для OAuth-клієнта',
	'OAuthClient:baseinfo' => 'Основна інформація',
	'OAuthClient:scope' => 'Область дії',
]);

//
// Class: OAuthClient
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', [
	'Class:OAuthClient' => 'Доступ до пошти через OAuth',
	'Class:OAuthClient/Attribute:provider' => 'Провайдер',
	'Class:OAuthClient/Attribute:provider+' => '',
	'Class:OAuthClient/Attribute:name' => 'Логін',
	'Class:OAuthClient/Attribute:name+' => 'Зазвичай це ваша адреса електронної пошти',
	'Class:OAuthClient/Attribute:status' => 'Статус',
	'Class:OAuthClient/Attribute:status+' => 'Після створення використовуйте дію "Згенерувати токен доступу", щоб мати можливість використовувати цей OAuth-клієнт',
	'Class:OAuthClient/Attribute:status/Value:active' => 'Токен доступу згенеровано',
	'Class:OAuthClient/Attribute:status/Value:inactive' => 'Немає токена доступу',
	'Class:OAuthClient/Attribute:description' => 'Опис',
	'Class:OAuthClient/Attribute:description+' => '',
	'Class:OAuthClient/Attribute:client_id' => 'ID клієнта',
	'Class:OAuthClient/Attribute:client_id+' => 'Довгий рядок символів, наданий вашим OAuth2-провайдером',
	'Class:OAuthClient/Attribute:client_secret' => 'Секрет клієнта',
	'Class:OAuthClient/Attribute:client_secret+' => 'Ще один довгий рядок символів, наданий вашим OAuth2-провайдером',
	'Class:OAuthClient/Attribute:refresh_token' => 'Токен оновлення',
	'Class:OAuthClient/Attribute:refresh_token+' => '',
	'Class:OAuthClient/Attribute:refresh_token_expiration' => 'Термін дії токена оновлення',
	'Class:OAuthClient/Attribute:refresh_token_expiration+' => '',
	'Class:OAuthClient/Attribute:scope' => 'Область дії',
	'Class:OAuthClient/Attribute:scope+' => '',
	'Class:OAuthClient/Attribute:token' => 'Токен доступу',
	'Class:OAuthClient/Attribute:token+' => '',
	'Class:OAuthClient/Attribute:token_expiration' => 'Термін дії токена доступу',
	'Class:OAuthClient/Attribute:token_expiration+' => '',
	'Class:OAuthClient/Attribute:redirect_url' => 'URL переспрямування',
	'Class:OAuthClient/Attribute:redirect_url+' => 'Цей URL необхідно скопіювати в конфігурацію OAuth2 провайдера
Очистіть поле для перерахунку значення за замовчуванням',
	'Class:OAuthClient/Attribute:mailbox_list' => 'Список поштових скриньок',
	'Class:OAuthClient/Attribute:mailbox_list+' => ''
]);

//
// Class: OAuthClientAzure
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:OAuthClientAzure' => 'Доступ до пошти через OAuth для Microsoft Azure',
	'Class:OAuthClientAzure/Name' => '%1$s (%2$s)',
	'Class:OAuthClientAzure/Attribute:scope' => 'Область дії',
	'Class:OAuthClientAzure/Attribute:scope+' => 'Зазвичай вибір за замовчуванням є прийнятним',
	'Class:OAuthClientAzure/Attribute:scope/Value:SMTP' => 'SMTP',
	'Class:OAuthClientAzure/Attribute:scope/Value:SMTP+' => '',
	'Class:OAuthClientAzure/Attribute:scope/Value:IMAP' => 'IMAP',
	'Class:OAuthClientAzure/Attribute:scope/Value:IMAP+' => '',
	'Class:OAuthClientAzure/Attribute:advanced_scope' => 'Розширена область дії',
	'Class:OAuthClientAzure/Attribute:advanced_scope+' => 'Як тільки ви введете тут щось, це матиме пріоритет над виборoм "Область дії", який буде проігноровано',
	'Class:OAuthClientAzure/Attribute:used_scope' => 'Використовувана область дії',
	'Class:OAuthClientAzure/Attribute:used_scope+' => '',
	'Class:OAuthClientAzure/Attribute:used_scope/Value:simple' => 'Проста',
	'Class:OAuthClientAzure/Attribute:used_scope/Value:simple+' => '',
	'Class:OAuthClientAzure/Attribute:used_scope/Value:advanced' => 'Розширена',
	'Class:OAuthClientAzure/Attribute:used_scope/Value:advanced+' => '',
	'Class:OAuthClientAzure/Attribute:used_for_smtp' => 'Використовувати для SMTP',
	'Class:OAuthClientAzure/Attribute:used_for_smtp+' => 'Принаймні один OAuth-клієнт повинен мати цей прапорець встановлений у "Так", якщо ви хочете, щоб iTop використовував його для надсилання пошти',
	'Class:OAuthClientAzure/Attribute:used_for_smtp/Value:yes' => 'Так',
	'Class:OAuthClientAzure/Attribute:used_for_smtp/Value:no' => 'Ні',
	'Class:OAuthClientAzure/Attribute:tenant' => 'Клієнт (Tenant)',
	'Class:OAuthClientAzure/Attribute:tenant+' => 'ID клієнта налаштованого додатку. Для мультиклієнтських додатків використовуйте "common".',
));

//
// Class: OAuthClientGoogle
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:OAuthClientGoogle' => 'Доступ до пошти через OAuth для Google',
	'Class:OAuthClientGoogle/Name' => '%1$s (%2$s)',
	'Class:OAuthClientGoogle/Attribute:scope' => 'Область дії',
	'Class:OAuthClientGoogle/Attribute:scope+' => 'Зазвичай вибір за замовчуванням є прийнятним',
	'Class:OAuthClientGoogle/Attribute:scope/Value:SMTP' => 'SMTP',
	'Class:OAuthClientGoogle/Attribute:scope/Value:SMTP+' => '',
	'Class:OAuthClientGoogle/Attribute:scope/Value:IMAP' => 'IMAP',
	'Class:OAuthClientGoogle/Attribute:scope/Value:IMAP+' => '',
	'Class:OAuthClientGoogle/Attribute:advanced_scope' => 'Розширена область дії',
	'Class:OAuthClientGoogle/Attribute:advanced_scope+' => 'Як тільки ви введете тут щось, це матиме пріоритет над виборoм "Область дії", який буде проігноровано',
	'Class:OAuthClientGoogle/Attribute:used_scope' => 'Використовувана область дії',
	'Class:OAuthClientGoogle/Attribute:used_scope+' => '',
	'Class:OAuthClientGoogle/Attribute:used_scope/Value:simple' => 'Проста',
	'Class:OAuthClientGoogle/Attribute:used_scope/Value:simple+' => '',
	'Class:OAuthClientGoogle/Attribute:used_scope/Value:advanced' => 'Розширена',
	'Class:OAuthClientGoogle/Attribute:used_scope/Value:advanced+' => '',
	'Class:OAuthClientGoogle/Attribute:used_for_smtp' => 'Використовувати для SMTP',
	'Class:OAuthClientGoogle/Attribute:used_for_smtp+' => 'Принаймні один OAuth-клієнт повинен мати цей прапорець встановлений у "Так", якщо ви хочете, щоб iTop використовував його для надсилання пошти',
	'Class:OAuthClientGoogle/Attribute:used_for_smtp/Value:yes' => 'Так',
	'Class:OAuthClientGoogle/Attribute:used_for_smtp/Value:no' => 'Ні',
));