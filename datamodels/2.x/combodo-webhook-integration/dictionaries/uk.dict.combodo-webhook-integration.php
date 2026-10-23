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
	'ActionGoogleChatNotification:message' => 'Повідомлення',
	'ActionMicrosoftTeamsNotification:additionalelements' => 'Додаткові елементи для включення',
	'ActionMicrosoftTeamsNotification:message' => 'Базове повідомлення',
	'ActionMicrosoftTeamsNotification:theme' => 'Тема',
	'ActionRocketChatNotification:additionalelements' => 'Інформація про бота',
	'ActionRocketChatNotification:message' => 'Базове повідомлення',
	'ActionSlackNotification:Payload:BlockKit:UserInfo' => 'Сповіщення від <%2$s|%1$s> (%3$s)',
	'ActionSlackNotification:additionalelements' => 'Додаткові елементи для включення',
	'ActionSlackNotification:message' => 'Базове повідомлення',
	'ActionWebhook:advancedparameters' => 'Розширені параметри',
	'ActionWebhook:baseinfo' => 'Загальна інформація',
	'ActionWebhook:moreinfo' => 'Додаткова інформація',
	'ActionWebhook:requestparameters' => 'Параметри запиту',
	'ActionWebhook:webhookconnection' => 'З\'єднання webhook',
	'Class:ActionGoogleChatNotification' => 'Сповіщення через Google Chat',
	'Class:ActionGoogleChatNotification+' => 'Надіслати сповіщення як повідомлення Google Chat у простір',
	'Class:ActionGoogleChatNotification/Attribute:message' => 'Повідомлення',
	'Class:ActionGoogleChatNotification/Attribute:message+' => 'Повідомлення, яке буде відображено в чаті, наразі підтримується тільки звичайний текст.',
	'Class:ActionGoogleChatNotification/Attribute:prepare_payload_callback+' => 'PHP метод для підготовки даних payload, які будуть надіслані під час виклику webhook. Використовуйте це, якщо стандартні опції недостатньо гнучкі або якщо структура payload має бути побудована динамічно.

Ви можете використовувати 2 типи методів:
- З об\'єкта, що ініціює тригер (наприклад, UserRequest), має бути публічним. Приклад: $this->XXX
- З будь-якого PHP класу, має бути статичним ТА публічним. Ім\'я має бути повністю кваліфікованим. Приклад: \SomeClass::XXX

ВАЖЛИВО: Якщо встановлено, \'message\' буде проігноровано.',
	'Class:ActionMicrosoftTeamsNotification' => 'Сповіщення через Microsoft Teams',
	'Class:ActionMicrosoftTeamsNotification+' => 'Надіслати сповіщення як повідомлення Microsoft Teams у канал',
	'Class:ActionMicrosoftTeamsNotification/Attribute:image_url' => 'Зображення медальйона',
	'Class:ActionMicrosoftTeamsNotification/Attribute:image_url+' => 'URL зображення для відображення як медальйон на картці повідомлення, воно має бути публічно доступним в інтернеті, щоб Microsoft Teams міг його відобразити',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_delete_button' => 'Кнопка видалення',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_delete_button+' => 'Включити кнопку під повідомленням для видалення об\'єкта в '.ITOP_APPLICATION_SHORT,
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_delete_button/Value:no' => 'Ні',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_delete_button/Value:yes' => 'Так',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_list_attributes' => 'Атрибути з',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_list_attributes+' => 'Відображати додаткові атрибути під повідомленням. Вони можуть бути або зі звичайного подання \'list\', або з користувацького подання \'msteams\' об\'єкта, що ініціює сповіщення. Зверніть увагу, що подання \'msteams\' має бути спочатку визначено в моделі даних (zlist)',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_list_attributes/Value:list' => 'звичайне подання списку',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_list_attributes/Value:msteams' => 'користувацьке подання "msteams"',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_modify_button' => 'Кнопка зміни',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_modify_button+' => 'Включити кнопку під повідомленням для редагування об\'єкта в '.ITOP_APPLICATION_SHORT,
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_modify_button/Value:no' => 'Ні',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_modify_button/Value:yes' => 'Так',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_other_actions_button' => 'Кнопки інших дій',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_other_actions_button+' => 'Включити інші дії (такі як переходи, доступні в поточному стані) під повідомленням',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_other_actions_button/Value:no' => 'Ні',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_other_actions_button/Value:specify' => 'Вказати',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_other_actions_button/Value:yes' => 'Так',
	'Class:ActionMicrosoftTeamsNotification/Attribute:message' => 'Повідомлення',
	'Class:ActionMicrosoftTeamsNotification/Attribute:prepare_payload_callback+' => 'PHP метод для підготовки даних payload, які будуть надіслані під час виклику webhook. Використовуйте це, якщо стандартні опції недостатньо гнучкі або якщо структура payload має бути побудована динамічно.

Ви можете використовувати 2 типи методів:
- З об\'єкта, що ініціює тригер (наприклад, UserRequest), має бути публічним. Приклад: $this->XXX
- З будь-якого PHP класу, має бути статичним ТА публічним. Ім\'я має бути повністю кваліфікованим. Приклад: \SomeClass::XXX

ВАЖЛИВО: Якщо встановлено, \'title\', \'message\' та всі \'additional elements\' будуть проігноровані.',
	'Class:ActionMicrosoftTeamsNotification/Attribute:specified_other_actions' => 'Коди інших дій',
	'Class:ActionMicrosoftTeamsNotification/Attribute:specified_other_actions+' => 'Вкажіть, які дії включити як кнопки під повідомленням. Має бути список кодів дій через кому (наприклад, \'ev_reopen, ev_close\')',
	'Class:ActionMicrosoftTeamsNotification/Attribute:theme_color' => 'Колір виділення',
	'Class:ActionMicrosoftTeamsNotification/Attribute:theme_color+' => 'Колір виділення в Microsoft Teams, має бути дійсним шістнадцятковим кольором (наприклад, FF0000) для Message Cards та одним із наступних значень: "default", "emphasis", "good", "attention", "warning" або "accent" для Adaptive Cards',
	'Class:ActionMicrosoftTeamsNotification/Attribute:title' => 'Заголовок',
	'Class:ActionRocketChatNotification' => 'Сповіщення через Rocket.Chat',
	'Class:ActionRocketChatNotification+' => 'Надіслати сповіщення як повідомлення Rocket.Chat у канал або користувачу',
	'Class:ActionRocketChatNotification/Attribute:bot_alias' => 'Псевдонім',
	'Class:ActionRocketChatNotification/Attribute:bot_alias+' => 'Перевизначає псевдонім бота за замовчуванням, з\'явиться перед іменем користувача повідомлення',
	'Class:ActionRocketChatNotification/Attribute:bot_emoji_avatar' => 'Emoji аватар',
	'Class:ActionRocketChatNotification/Attribute:bot_emoji_avatar+' => 'Перевизначає аватар бота за замовчуванням, може бути будь-яким emoji Rocket.Chat (наприклад, :ghost:, :white_check_mark:, ...). Зверніть увагу, що якщо встановлено URL аватар, emoji не буде відображено.',
	'Class:ActionRocketChatNotification/Attribute:bot_url_avatar' => 'Аватар зображення',
	'Class:ActionRocketChatNotification/Attribute:bot_url_avatar+' => 'Перевизначає аватар бота за замовчуванням, має бути абсолютним URL зображення для використання',
	'Class:ActionRocketChatNotification/Attribute:message' => 'Повідомлення',
	'Class:ActionRocketChatNotification/Attribute:message+' => 'Повідомлення, яке буде відображено в чаті',
	'Class:ActionRocketChatNotification/Attribute:prepare_payload_callback+' => 'PHP метод для підготовки даних payload, які будуть надіслані під час виклику webhook. Використовуйте це, якщо стандартні опції недостатньо гнучкі або якщо структура payload має бути побудована динамічно.

Ви можете використовувати 2 типи методів:
- З об\'єкта, що ініціює тригер (наприклад, UserRequest), має бути публічним. Приклад: $this->XXX
- З будь-якого PHP класу, має бути статичним ТА публічним. Ім\'я має бути повністю кваліфікованим. Приклад: \SomeClass::XXX

ВАЖЛИВО: Якщо встановлено, \'message\' та вся \'bot information\' будуть проігноровані.',
	'Class:ActionSlackNotification' => 'Сповіщення через Slack',
	'Class:ActionSlackNotification+' => 'Надіслати сповіщення як повідомлення Slack у канал або користувачу',
	'Class:ActionSlackNotification/Attribute:include_delete_button' => 'Кнопка видалення',
	'Class:ActionSlackNotification/Attribute:include_delete_button+' => 'Включити кнопку під повідомленням для видалення об\'єкта в '.ITOP_APPLICATION_SHORT,
	'Class:ActionSlackNotification/Attribute:include_delete_button/Value:no' => 'Ні',
	'Class:ActionSlackNotification/Attribute:include_delete_button/Value:yes' => 'Так',
	'Class:ActionSlackNotification/Attribute:include_list_attributes' => 'Атрибути з',
	'Class:ActionSlackNotification/Attribute:include_list_attributes+' => 'Відображати додаткові атрибути під повідомленням. Вони можуть бути або зі звичайного подання \'list\', або з користувацького подання \'slack\' об\'єкта, що ініціює сповіщення. Зверніть увагу, що подання \'slack\' має бути спочатку визначено в моделі даних (zlist)',
	'Class:ActionSlackNotification/Attribute:include_list_attributes/Value:list' => 'звичайне подання списку',
	'Class:ActionSlackNotification/Attribute:include_list_attributes/Value:slack' => 'користувацьке подання "slack"',
	'Class:ActionSlackNotification/Attribute:include_modify_button' => 'Кнопка зміни',
	'Class:ActionSlackNotification/Attribute:include_modify_button+' => 'Включити кнопку під повідомленням для редагування об\'єкта в '.ITOP_APPLICATION_SHORT,
	'Class:ActionSlackNotification/Attribute:include_modify_button/Value:no' => 'Ні',
	'Class:ActionSlackNotification/Attribute:include_modify_button/Value:yes' => 'Так',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button' => 'Кнопки інших дій',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button+' => 'Включити інші дії (такі як переходи, доступні в поточному стані) під повідомленням',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button/Value:no' => 'Ні',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button/Value:specify' => 'Вказати',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button/Value:yes' => 'Так',
	'Class:ActionSlackNotification/Attribute:include_user_info' => 'Інформація про користувача',
	'Class:ActionSlackNotification/Attribute:include_user_info+' => 'Відображати інформацію про користувача (повне ім\'я) під повідомленням',
	'Class:ActionSlackNotification/Attribute:include_user_info/Value:no' => 'Ні',
	'Class:ActionSlackNotification/Attribute:include_user_info/Value:yes' => 'Так',
	'Class:ActionSlackNotification/Attribute:message' => 'Повідомлення',
	'Class:ActionSlackNotification/Attribute:prepare_payload_callback+' => 'PHP метод для підготовки даних payload, які будуть надіслані під час виклику webhook. Використовуйте це, якщо стандартні опції недостатньо гнучкі або якщо структура payload має бути побудована динамічно.

Ви можете використовувати 2 типи методів:
- З об\'єкта, що ініціює тригер (наприклад, UserRequest), має бути публічним. Приклад: $this->XXX
- З будь-якого PHP класу, має бути статичним ТА публічним. Ім\'я має бути повністю кваліфікованим. Приклад: \SomeClass::XXX

ВАЖЛИВО: Якщо встановлено, \'message\' та всі \'additional elements\' будуть проігноровані.',
	'Class:ActionSlackNotification/Attribute:specified_other_actions' => 'Коди інших дій',
	'Class:ActionSlackNotification/Attribute:specified_other_actions+' => 'Вкажіть, які дії включити як кнопки під повідомленням. Має бути список кодів дій через кому (наприклад, \'ev_reopen, ev_close\')',
	'Class:ActionWebhook' => 'Дія через Webhook (загальна)',
	'Class:ActionWebhook+' => 'Виклик Webhook для будь-якого типу застосунку',
	'Class:ActionWebhook/Attribute:asynchronous+' => 'Чи повинна ця дія виконуватися у фоновому режимі чи ні (майте на увазі, що глобальне налаштування для дій webhook - це параметр конфігурації "prefer_asynchronous" модуля "combodo-webhook-action")',
	'Class:ActionWebhook/Attribute:headers' => 'Заголовки',
	'Class:ActionWebhook/Attribute:headers+' => 'Заголовки HTTP запиту, має бути по одному на рядок (наприклад, \'Content-type: application/json\')',
	'Class:ActionWebhook/Attribute:language' => 'Мова',
	'Class:ActionWebhook/Attribute:language+' => 'Мова цього сповіщення, в основному використовується при пошуку сповіщень, але також може використовуватися для перекладу міток атрибутів',
	'Class:ActionWebhook/Attribute:method' => 'Метод',
	'Class:ActionWebhook/Attribute:method+' => 'Метод HTTP запиту',
	'Class:ActionWebhook/Attribute:method/Value:delete' => 'DELETE',
	'Class:ActionWebhook/Attribute:method/Value:get' => 'GET',
	'Class:ActionWebhook/Attribute:method/Value:head' => 'HEAD',
	'Class:ActionWebhook/Attribute:method/Value:patch' => 'PATCH',
	'Class:ActionWebhook/Attribute:method/Value:post' => 'POST',
	'Class:ActionWebhook/Attribute:method/Value:put' => 'PUT',
	'Class:ActionWebhook/Attribute:path' => 'Шлях',
	'Class:ActionWebhook/Attribute:path+' => 'Додатковий шлях для додавання до URL з\'єднання (наприклад, \'/some/specific-endpoint\')',
	'Class:ActionWebhook/Attribute:payload' => 'Payload',
	'Class:ActionWebhook/Attribute:payload+' => 'Дані, надіслані під час виклику webhook, найчастіше це JSON рядок. Використовуйте це, якщо структура вашого payload статична.

ВАЖЛИВО: Буде проігноровано, якщо встановлено \'Prepare payload callback\'',
	'Class:ActionWebhook/Attribute:prepare_payload_callback' => 'Callback підготовки payload',
	'Class:ActionWebhook/Attribute:prepare_payload_callback+' => 'PHP метод для підготовки даних payload, які будуть надіслані під час виклику webhook. Використовуйте це, якщо структура вашого payload має бути побудована динамічно.

Ви можете використовувати 2 типи методів:
- З об\'єкта, що ініціює тригер (наприклад, UserRequest), має бути публічним. Приклад: $this->XXX
- З будь-якого PHP класу, має бути статичним ТА публічним. Ім\'я має бути повністю кваліфікованим. Приклад: \SomeClass::XXX

ВАЖЛИВО: Якщо встановлено, атрибут \'Payload\' буде проігноровано.',
	'Class:ActionWebhook/Attribute:process_response_callback' => 'Callback обробки відповіді',
	'Class:ActionWebhook/Attribute:process_response_callback+' => 'PHP метод для обробки відповіді виклику webhook.

Ви можете використовувати 2 типи методів:
- З об\'єкта, що ініціює тригер (наприклад, UserRequest), має бути публічним. Приклад: $this->XXX
- З будь-якого PHP класу, має бути статичним ТА публічним. Ім\'я має бути повністю кваліфікованим. Приклад: \SomeClass::XXX
- $oResponse може бути null в деяких випадках (наприклад, не вдалося надіслати запит)',
	'Class:ActionWebhook/Attribute:remoteapplicationconnection_id' => 'З\'єднання',
	'Class:ActionWebhook/Attribute:remoteapplicationconnection_id+' => 'Інформація про з\'єднання для використання, коли статус \'в продакшені\'',
	'Class:ActionWebhook/Attribute:test_remoteapplicationconnection_id' => 'Тестове з\'єднання',
	'Class:ActionWebhook/Attribute:test_remoteapplicationconnection_id+' => 'Інформація про з\'єднання для використання, коли статус \'тестується\'',
	'Class:ActioniTopWebhook' => 'Дія через '.ITOP_APPLICATION_SHORT.' REST API',
	'Class:ActioniTopWebhook+' => 'Виклик Webhook до віддаленого застосунку iTop',
	'Class:ActioniTopWebhook/Attribute:headers+' => 'Заголовки HTTP запиту, має бути по одному на рядок (наприклад, \'Content-type: application/x-www-form-urlencoded\')

ВАЖЛИВО:
- \'Content-type\' має бути встановлено на \'application/x-www-form-urlencoded\' для iTop, навіть якщо ми надсилаємо JSON
- Заголовок \'Basic authorization\' буде додано автоматично до запиту під час відправлення, що містить облікові дані з вибраного з\'єднання',
	'Class:ActioniTopWebhook/Attribute:payload' => 'JSON дані',
	'Class:ActioniTopWebhook/Attribute:payload+' => 'Payload JSON, має бути JSON рядком, що містить назву операції та параметри, дивіться документацію для детальної інформації',
	'Class:ActioniTopWebhook/Attribute:prepare_payload_callback+' => 'PHP метод для підготовки даних payload, які будуть надіслані під час виклику webhook. Використовуйте це, якщо структура вашого payload має бути побудована динамічно.

Ви можете використовувати 2 типи методів:
- З об\'єкта, що ініціює тригер (наприклад, UserRequest), має бути публічним. Приклад: $this->XXX
- З будь-якого PHP класу, має бути статичним ТА публічним. Ім\'я має бути повністю кваліфікованим. Приклад: \SomeClass::XXX

ВАЖЛИВО: Якщо встановлено, атрибут \'JSON data\' буде проігноровано.',
	'Class:EventWebhook' => 'Webhook ініційовано',
	'Class:EventWebhook/Attribute:action_finalclass' => 'Фінальний клас',
	'Class:EventWebhook/Attribute:headers' => 'Заголовки',
	'Class:EventWebhook/Attribute:payload' => 'Payload',
	'Class:EventWebhook/Attribute:response' => 'Відповідь',
	'Class:EventWebhook/Attribute:webhook_url' => 'URL Webhook',
	'Class:RemoteApplicationConnection' => 'З\'єднання з віддаленим застосунком',
	'Class:RemoteApplicationConnection/Attribute:actions_list' => 'Webhook сповіщення',
	'Class:RemoteApplicationConnection/Attribute:actions_list+' => 'Webhook сповіщення, що використовують це з\'єднання',
	'Class:RemoteApplicationConnection/Attribute:environment' => 'Середовище',
	'Class:RemoteApplicationConnection/Attribute:environment+' => 'Тип середовища з\'єднання',
	'Class:RemoteApplicationConnection/Attribute:environment/Value:1-development' => 'Розробка',
	'Class:RemoteApplicationConnection/Attribute:environment/Value:2-test' => 'Тест',
	'Class:RemoteApplicationConnection/Attribute:environment/Value:3-production' => 'Продакшен',
	'Class:RemoteApplicationConnection/Attribute:remoteapplicationtype_id' => 'Тип застосунку',
	'Class:RemoteApplicationConnection/Attribute:remoteapplicationtype_id+' => 'Тип застосунку, для якого призначено з\'єднання (використовуйте \'Generic\', якщо вашого немає в списку)',
	'Class:RemoteApplicationConnection/Attribute:url' => 'URL',
	'Class:RemoteApplicationType' => 'Тип віддаленого застосунку',
	'Class:RemoteApplicationType/Attribute:remoteapplicationconnections_list' => 'З\'єднання',
	'Class:RemoteApplicationType/Attribute:remoteapplicationconnections_list+' => 'З\'єднання для цього застосунку',
	'Class:RemoteiTopConnection' => 'З\'єднання з віддаленим iTop',
	'Class:RemoteiTopConnection/Attribute:auth_pwd' => 'Пароль аутентифікації',
	'Class:RemoteiTopConnection/Attribute:auth_pwd+' => 'Пароль користувача (на віддаленому iTop), що використовується для аутентифікації',
	'Class:RemoteiTopConnection/Attribute:auth_user' => 'Користувач аутентифікації',
	'Class:RemoteiTopConnection/Attribute:auth_user+' => 'Логін користувача (на віддаленому iTop), що використовується для аутентифікації',
	'Class:RemoteiTopConnection/Attribute:version' => 'Версія API',
	'Class:RemoteiTopConnection/Attribute:version+' => 'Версія API, що викликається (наприклад, 1.3)',
	'Class:RemoteiTopConnectionToken' => 'З\'єднання з віддаленим iTop через токен',
	'Class:RemoteiTopConnectionToken/Attribute:token+' => 'Токен',
	'Dashboard:Integrations:ActionWebhookList:Title' => 'Дії типу Webhook',
	'Dashboard:Integrations:Outgoing:Title' => 'Вихідні webhook інтеграції',
	'Dashboard:Integrations:Title' => 'Інтеграції з зовнішніми застосунками',
	'Menu:Webhook' => 'Webhooks',
	'RemoteApplicationConnection:authinfo' => 'Аутентифікація',
	'RemoteApplicationConnection:baseinfo' => 'Загальна інформація',
	'RemoteApplicationConnection:moreinfo' => 'Додаткова інформація',
	'Class:ActionWebhook/Error:InvalidJSONFormatForPayload' => 'Невірний формат JSON для Payload',
]);
