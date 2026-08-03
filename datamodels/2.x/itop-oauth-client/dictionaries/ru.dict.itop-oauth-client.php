<?php

/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 *
 */
/**
 *
 */
Dict::Add('RU RU', 'Russian', 'Русский', [
	'Menu:CreateMailbox' => 'Создать почтовый ящик…',
	'Menu:OAuthClient' => 'Доступ к почте через OAuth',
	'Menu:OAuthClient+' => '',
	'Menu:GenerateTokens' => 'Сгенерировать токен доступа…',
	'Menu:RegenerateTokens' => 'Перегенерировать токен доступа…',
	'itop-oauth-client/Operation:CreateMailBox/Title' => 'Создание почтового ящика',
	'itop-oauth-client:UsedForSMTP' => 'Этот клиент OAuth используется для SMTP',
	'itop-oauth-client:TestSMTP' => 'Тест отправки email',
	'itop-oauth-client:MissingOAuthClient' => 'Отсутствует клиент OAuth для пользователя %1$s',
	'itop-oauth-client:Message:MissingToken' => 'Сгенерируйте токен доступа перед использованием этого клиента OAuth',
	'itop-oauth-client:Message:RegenerateToken' => 'Перегенерируйте токен доступа, чтобы учесть изменения',
	'itop-oauth-client:Message:TokenCreated' => 'Токен доступа создан',
	'itop-oauth-client:Message:TokenRecreated' => 'Токен доступа перегенерирован',
	'itop-oauth-client:Message:TokenError' => 'Токен доступа не сгенерирован из-за ошибки сервера',
	'OAuthClient:Name/UseForSMTPMustBeUnique' => 'Комбинация Логин (%1$s) и Использовать для SMTP (%2$s) уже используется другим клиентом OAuth',
	'OAuthClient:baseinfo' => 'Основная информация',
	'OAuthClient:scope' => 'Область доступа',
]);

//
// Class: OAuthClient
//

Dict::Add('RU RU', 'Russian', 'Русский', [
	'Class:OAuthClient' => 'Доступ к почте через OAuth',
	'Class:OAuthClient/Attribute:provider' => 'Провайдер',
	'Class:OAuthClient/Attribute:provider+' => '',
	'Class:OAuthClient/Attribute:name' => 'Логин',
	'Class:OAuthClient/Attribute:name+' => 'Обычно это ваш email-адрес',
	'Class:OAuthClient/Attribute:status' => 'Статус',
	'Class:OAuthClient/Attribute:status+' => 'После создания используйте действие «Сгенерировать токен доступа», чтобы иметь возможность использовать этого клиента OAuth',
	'Class:OAuthClient/Attribute:status/Value:active' => 'Токен доступа сгенерирован',
	'Class:OAuthClient/Attribute:status/Value:inactive' => 'Нет токена доступа',
	'Class:OAuthClient/Attribute:description' => 'Описание',
	'Class:OAuthClient/Attribute:description+' => '',
	'Class:OAuthClient/Attribute:client_id' => 'Client id',
	'Class:OAuthClient/Attribute:client_id+' => 'Длинная строка символов, предоставленная вашим провайдером OAuth2',
	'Class:OAuthClient/Attribute:client_secret' => 'Client secret',
	'Class:OAuthClient/Attribute:client_secret+' => 'Ещё одна длинная строка символов, предоставленная вашим провайдером OAuth2',
	'Class:OAuthClient/Attribute:refresh_token' => 'Refresh token',
	'Class:OAuthClient/Attribute:refresh_token+' => '',
	'Class:OAuthClient/Attribute:refresh_token_expiration' => 'Истечение refresh token',
	'Class:OAuthClient/Attribute:refresh_token_expiration+' => '',
	'Class:OAuthClient/Attribute:scope' => 'Область доступа',
	'Class:OAuthClient/Attribute:scope+' => '',
	'Class:OAuthClient/Attribute:token' => 'Токен доступа',
	'Class:OAuthClient/Attribute:token+' => '',
	'Class:OAuthClient/Attribute:token_expiration' => 'Истечение токена доступа',
	'Class:OAuthClient/Attribute:token_expiration+' => '',
	'Class:OAuthClient/Attribute:redirect_url' => 'Redirect url',
	'Class:OAuthClient/Attribute:redirect_url+' => 'Этот url нужно скопировать в конфигурацию OAuth2 у провайдера.
Очистите поле, чтобы пересчитать значение по умолчанию',
	'Class:OAuthClient/Attribute:mailbox_list' => 'Список почтовых ящиков',
	'Class:OAuthClient/Attribute:mailbox_list+' => '',
]);

//
// Class: OAuthClientAzure
//

Dict::Add('RU RU', 'Russian', 'Русский', [
	'Class:OAuthClientAzure' => 'Доступ к почте через OAuth (Microsoft Azure)',
	'Class:OAuthClientAzure/Name' => '%1$s (%2$s)',
	'Class:OAuthClientAzure/Attribute:scope' => 'Область доступа',
	'Class:OAuthClientAzure/Attribute:scope+' => 'Обычно подходит выбор по умолчанию',
	'Class:OAuthClientAzure/Attribute:scope/Value:SMTP' => 'SMTP',
	'Class:OAuthClientAzure/Attribute:scope/Value:SMTP+' => '',
	'Class:OAuthClientAzure/Attribute:scope/Value:IMAP' => 'IMAP',
	'Class:OAuthClientAzure/Attribute:scope/Value:IMAP+' => '',
	'Class:OAuthClientAzure/Attribute:advanced_scope' => 'Расширенная область доступа',
	'Class:OAuthClientAzure/Attribute:advanced_scope+' => 'Как только здесь что-то указано, это имеет приоритет над выбором «Область доступа», который в этом случае игнорируется',
	'Class:OAuthClientAzure/Attribute:used_scope' => 'Используемая область доступа',
	'Class:OAuthClientAzure/Attribute:used_scope+' => '',
	'Class:OAuthClientAzure/Attribute:used_scope/Value:simple' => 'Простая',
	'Class:OAuthClientAzure/Attribute:used_scope/Value:simple+' => '',
	'Class:OAuthClientAzure/Attribute:used_scope/Value:advanced' => 'Расширенная',
	'Class:OAuthClientAzure/Attribute:used_scope/Value:advanced+' => '',
	'Class:OAuthClientAzure/Attribute:used_for_smtp' => 'Используется для SMTP',
	'Class:OAuthClientAzure/Attribute:used_for_smtp+' => 'Хотя бы у одного клиента OAuth этот флаг должен быть «Да», если вы хотите, чтобы iTop использовал его для отправки почты',
	'Class:OAuthClientAzure/Attribute:used_for_smtp/Value:yes' => 'Да',
	'Class:OAuthClientAzure/Attribute:used_for_smtp/Value:no' => 'Нет',
	'Class:OAuthClientAzure/Attribute:tenant' => 'Tenant',
	'Class:OAuthClientAzure/Attribute:tenant+' => 'Tenant ID настроенного приложения. Для multi-tenant приложения используйте "common".',
]);

//
// Class: OAuthClientGoogle
//

Dict::Add('RU RU', 'Russian', 'Русский', [
	'Class:OAuthClientGoogle' => 'Доступ к почте через OAuth (Google)',
	'Class:OAuthClientGoogle/Name' => '%1$s (%2$s)',
	'Class:OAuthClientGoogle/Attribute:scope' => 'Область доступа',
	'Class:OAuthClientGoogle/Attribute:scope+' => 'Обычно подходит выбор по умолчанию',
	'Class:OAuthClientGoogle/Attribute:scope/Value:SMTP' => 'SMTP',
	'Class:OAuthClientGoogle/Attribute:scope/Value:SMTP+' => '',
	'Class:OAuthClientGoogle/Attribute:scope/Value:IMAP' => 'IMAP',
	'Class:OAuthClientGoogle/Attribute:scope/Value:IMAP+' => '',
	'Class:OAuthClientGoogle/Attribute:advanced_scope' => 'Расширенная область доступа',
	'Class:OAuthClientGoogle/Attribute:advanced_scope+' => 'Как только здесь что-то указано, это имеет приоритет над выбором «Область доступа», который в этом случае игнорируется',
	'Class:OAuthClientGoogle/Attribute:used_scope' => 'Используемая область доступа',
	'Class:OAuthClientGoogle/Attribute:used_scope+' => '',
	'Class:OAuthClientGoogle/Attribute:used_scope/Value:simple' => 'Простая',
	'Class:OAuthClientGoogle/Attribute:used_scope/Value:simple+' => '',
	'Class:OAuthClientGoogle/Attribute:used_scope/Value:advanced' => 'Расширенная',
	'Class:OAuthClientGoogle/Attribute:used_scope/Value:advanced+' => '',
	'Class:OAuthClientGoogle/Attribute:used_for_smtp' => 'Используется для SMTP',
	'Class:OAuthClientGoogle/Attribute:used_for_smtp+' => 'Хотя бы у одного клиента OAuth этот флаг должен быть «Да», если вы хотите, чтобы iTop использовал его для отправки почты',
	'Class:OAuthClientGoogle/Attribute:used_for_smtp/Value:yes' => 'Да',
	'Class:OAuthClientGoogle/Attribute:used_for_smtp/Value:no' => 'Нет',
]);
