<?php

/**
 * Локализация интерфейса Combodo iTop подготовлена сообществом iTop по-русски http://community.itop-itsm.ru.
 * 
 * @author   Vladimir Kunin <v.b.kunin@gmail.com>
 * @license   http://opensource.org/licenses/AGPL-3.0
 *
 * 
 * Инструкция по установке
 * 
 * Процесс установки заключается в замене имеющихся локализационных файлов полученными и последующем запуске процедуры обновления iTop для перекомпиляции кода.
 * 	1. Скопируйте с заменой два полученных файла из "itop-rus/dictionaries" в "путь/до/вашего/itop/dictionaries".
 * 	2. Скопируйте с заменой полученные файлы "itop-rus/datamodels/2.x/название-модуля/ru.dict.название-модуля.php" в "путь/до/вашего/itop/datamodels/2.x/название-модуля".
 *  3. Перейдите по адресу "http://адрес/вашего/itop/setup", при этом файл "путь/до/вашего/itop/conf/production/config-itop.php" должен быть доступен для записи.
 *  4. На второй странице установщика выберите "Upgrade an existing iTop instance" и следуйте дальнейшим инструкциям установщика.
 *
 * Ответы на вопросы по установке и использованию переводов, а также на любые другие вопросы по iTop всегда можно получить на сайте сообщества iTop по-русски http://community.itop-itsm.ru.
 *
 */

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Core:DeletedObjectLabel' => '%1ы (удалены)',
	'Core:DeletedObjectTip' => 'Объект был удален %1$s (%2$s)',

	'Core:UnknownObjectLabel' => 'Объект не найден (class: %1$s, id: %2$d)',
	'Core:UnknownObjectTip' => 'Не может быть найден. Возможно он был удален и очищен в лог-е.',

	'Core:AttributeLinkedSet' => 'Массив объектов',
	'Core:AttributeLinkedSet+' => 'Any kind of objects of the same class or subclass',

	'Core:AttributeLinkedSetIndirect' => 'Массив объектов (N-N)',
	'Core:AttributeLinkedSetIndirect+' => 'Any kind of objects [subclass] of the same class',

	'Core:AttributeInteger' => 'Целый',
	'Core:AttributeInteger+' => 'Numeric value (could be negative)',

	'Core:AttributeDecimal' => 'Десятичн.',
	'Core:AttributeDecimal+' => 'Decimal value (could be negative)',

	'Core:AttributeBoolean' => 'Логич.',
	'Core:AttributeBoolean+' => 'Boolean',

	'Core:AttributeString' => 'Строка',
	'Core:AttributeString+' => 'Alphanumeric string',

	'Core:AttributeClass' => 'Класс',
	'Core:AttributeClass+' => 'Class',

	'Core:AttributeApplicationLanguage' => 'Язык пользователя',
	'Core:AttributeApplicationLanguage+' => 'Language and country (EN US)',

	'Core:AttributeFinalClass' => 'Класс (авто)',
	'Core:AttributeFinalClass+' => 'Real class of the object (automatically created by the core)',

	'Core:AttributePassword' => 'Пароль~~',
	'Core:AttributePassword+' => 'Password of an external device',

 	'Core:AttributeEncryptedString' => 'Шифр.значение',
	'Core:AttributeEncryptedString+' => 'String encrypted with a local key',

	'Core:AttributeText' => 'Текст~~',
	'Core:AttributeText+' => 'Multiline character string',

	'Core:AttributeHTML' => 'HTML~~',
	'Core:AttributeHTML+' => 'HTML string',

	'Core:AttributeEmailAddress' => 'Email~~',
	'Core:AttributeEmailAddress+' => 'Email address',

	'Core:AttributeIPAddress' => 'IP адрес~~',
	'Core:AttributeIPAddress+' => 'IP адрес',

	'Core:AttributeOQL' => 'OQL~~',
	'Core:AttributeOQL+' => 'Object Query Langage expression',

	'Core:AttributeEnum' => 'Enum~~',
	'Core:AttributeEnum+' => 'List of predefined alphanumeric strings',

	'Core:AttributeTemplateString' => 'Шаблон строки',
	'Core:AttributeTemplateString+' => 'String containing placeholders',

	'Core:AttributeTemplateText' => 'Шаблон текста',
	'Core:AttributeTemplateText+' => 'Text containing placeholders',

	'Core:AttributeTemplateHTML' => 'HTML шаблон~~',
	'Core:AttributeTemplateHTML+' => 'HTML containing placeholders',

	'Core:AttributeDateTime' => 'Дата/время',
	'Core:AttributeDateTime+' => 'Date and time (year-month-day hh:mm:ss)',
	'Core:AttributeDateTime?SmartSearch' => '
<p>
	Date format:<br/>
	<b>гггг-мм-дд чч:мм:сс</b><br/>
	Пример: 2011-07-19 18:40:00
</p>
<p>
Operators:<br/>
	<b>&gt;</b><em>date</em><br/>
	<b>&lt;</b><em>date</em><br/>
	<b>[</b><em>date</em>,<em>date</em><b>]</b>
</p>
<p>
If the time is omitted, it defaults to 00:00:00
</p>~~',

	'Core:AttributeDate' => 'Дата~~',
	'Core:AttributeDate+' => 'Дата (год-месяц-день)',
	'Core:AttributeDate?SmartSearch' => '
<p>
	Date format:<br/>
	<b>гггг-мм-дд</b><br/>
	Example: 2011-07-19
</p>
<p>
Operators:<br/>
	<b>&gt;</b><em>date</em><br/>
	<b>&lt;</b><em>date</em><br/>
	<b>[</b><em>date</em>,<em>date</em><b>]</b>
</p>~~',

	'Core:AttributeDeadline' => 'Deadline~~',
	'Core:AttributeDeadline+' => 'Date, displayed relatively to the current time',

	'Core:AttributeExternalKey' => 'External key~~',
	'Core:AttributeExternalKey+' => 'External (or foreign) key',

	'Core:AttributeHierarchicalKey' => 'Hierarchical Key~~',
	'Core:AttributeHierarchicalKey+' => 'External (or foreign) key to the parent',

	'Core:AttributeExternalField' => 'External field~~',
	'Core:AttributeExternalField+' => 'Field mapped to an external key',

	'Core:AttributeURL' => 'URL~~',
	'Core:AttributeURL+' => 'Absolute or relative URL as a text string',

	'Core:AttributeBlob' => 'Blob~~',
	'Core:AttributeBlob+' => 'Any binary content (document)',

	'Core:AttributeOneWayPassword' => 'Одноразовый пароль',
	'Core:AttributeOneWayPassword+' => 'One way encrypted (hashed) password',

	'Core:AttributeTable' => 'Table~~',
	'Core:AttributeTable+' => 'Indexed array having two dimensions',

	'Core:AttributePropertySet' => 'Свойства~~',
	'Core:AttributePropertySet+' => 'List of untyped properties (name and value)',

	'Core:AttributeFriendlyName' => 'Виден как~~',
	'Core:AttributeFriendlyName+' => 'Attribute created automatically ; the friendly name is computed after several attributes',

	'Core:FriendlyName-Label' => 'Виден как~~',
	'Core:FriendlyName-Description' => 'Виден как~~',
));


//////////////////////////////////////////////////////////////////////
// Classes in 'core/cmdb'
//////////////////////////////////////////////////////////////////////
//

//
// Class: CMDBChange
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:CMDBChange' => 'Изменение',
	'Class:CMDBChange+' => 'Отслеживание изменений',
	'Class:CMDBChange/Attribute:date' => 'дата',
	'Class:CMDBChange/Attribute:date+' => 'Дата и время регистрации изменений',
	'Class:CMDBChange/Attribute:userinfo' => 'разная информация',
	'Class:CMDBChange/Attribute:userinfo+' => 'изменение определённые -вызвавшим-',
));

//
// Class: CMDBChangeOp
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:CMDBChangeOp' => 'Операция изменения',
	'Class:CMDBChangeOp+' => 'Отслеживание операции изменения',
	'Class:CMDBChangeOp/Attribute:change' => 'изменение',
	'Class:CMDBChangeOp/Attribute:change+' => 'изменение',
	'Class:CMDBChangeOp/Attribute:date' => 'дата',
	'Class:CMDBChangeOp/Attribute:date+' => 'дата и время изменения',
	'Class:CMDBChangeOp/Attribute:userinfo' => 'пользователь',
	'Class:CMDBChangeOp/Attribute:userinfo+' => 'кто сделал изменение',
	'Class:CMDBChangeOp/Attribute:objclass' => 'класс объекта',
	'Class:CMDBChangeOp/Attribute:objclass+' => 'класс объекта',
	'Class:CMDBChangeOp/Attribute:objkey' => 'id объекта',
	'Class:CMDBChangeOp/Attribute:objkey+' => 'id объекта',
	'Class:CMDBChangeOp/Attribute:finalclass' => 'тип',
	'Class:CMDBChangeOp/Attribute:finalclass+' => '',
));

//
// Class: CMDBChangeOpCreate
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:CMDBChangeOpCreate' => 'создание объекта',
	'Class:CMDBChangeOpCreate+' => 'Отслеживание создания объекта',
));

//
// Class: CMDBChangeOpDelete
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:CMDBChangeOpDelete' => 'удаление объекта',
	'Class:CMDBChangeOpDelete+' => 'Отслеживание удаления объекта',
));

//
// Class: CMDBChangeOpSetAttribute
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:CMDBChangeOpSetAttribute' => 'изменение объекта',
	'Class:CMDBChangeOpSetAttribute+' => 'Отслеживание изменения объекта',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode' => 'Атрибут',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode+' => 'код изменённого свойства',
));

//
// Class: CMDBChangeOpSetAttributeScalar
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:CMDBChangeOpSetAttributeScalar' => 'изменение свойства',
	'Class:CMDBChangeOpSetAttributeScalar+' => 'Отслеживание изменения скалярного свойства объекта',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue' => 'Предыдущее значение',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue+' => 'предыдущее значение атрибута',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue' => 'Новое значение',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue+' => 'новое значение атрибута',
));
// Used by CMDBChangeOp... & derived classes
Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Change:ObjectCreated' => 'Объект создан',
	'Change:ObjectDeleted' => 'Объект удалён',
	'Change:ObjectModified' => 'Object modified~~',
	'Change:AttName_SetTo_NewValue_PreviousValue_OldValue' => '%1$s установлено в %2$s (предыдущее значение: %3$s)',
	'Change:AttName_SetTo' => '%1$s установлено в %2$s',
	'Change:Text_AppendedTo_AttName' => '%1$s добавлено к %2$s',
	'Change:AttName_Changed_PreviousValue_OldValue' => '%1$s изменено, предыдущее значение: %2$s',
	'Change:AttName_Changed' => '%1$s изменено',
	'Change:AttName_EntryAdded' => '%1$s изменено, добавлено новое значение: %2$s',
	'Change:LinkSet:Added' => 'добавлен %1$s~~',
	'Change:LinkSet:Removed' => 'удален %1$s~~',
	'Change:LinkSet:Modified' => 'изменен %1$s~~',
));

//
// Class: CMDBChangeOpSetAttributeBlob
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:CMDBChangeOpSetAttributeBlob' => 'изменение данных',
	'Class:CMDBChangeOpSetAttributeBlob+' => 'отслеживание изменения данных',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata' => 'Предыдущие данные',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata+' => 'предыдущее содержимое атрибута',
));

//
// Class: CMDBChangeOpSetAttributeText
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:CMDBChangeOpSetAttributeText' => 'изменение текста',
	'Class:CMDBChangeOpSetAttributeText+' => 'отслеживание изменения текста',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata' => 'Предыдущие данные',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata+' => 'предыдущее содержимое атрибута',
));

//
// Class: Event
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Event' => 'Журнал событий',
	'Class:Event+' => 'Внутренние событие приложения',
	'Class:Event/Attribute:message' => 'Сообщение',
	'Class:Event/Attribute:message+' => 'Краткое описание события',
	'Class:Event/Attribute:date' => 'Дата',
	'Class:Event/Attribute:date+' => 'Дата и время регистрации события',
	'Class:Event/Attribute:userinfo' => 'Пользователь',
	'Class:Event/Attribute:userinfo+' => 'Пользователь, действия которого вызвали это событие',
	'Class:Event/Attribute:finalclass' => 'тип',
	'Class:Event/Attribute:finalclass+' => '',
));

//
// Class: EventNotification
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:EventNotification' => 'Уведомление о событии',
	'Class:EventNotification+' => 'Отслеживание отосланных уведомлений',
	'Class:EventNotification/Attribute:trigger_id' => 'Триггер',
	'Class:EventNotification/Attribute:trigger_id+' => 'Сработавший триггер',
	'Class:EventNotification/Attribute:action_id' => 'Действие',
	'Class:EventNotification/Attribute:action_id+' => 'Выполненное действие',
	'Class:EventNotification/Attribute:object_id' => 'ID объекта',
	'Class:EventNotification/Attribute:object_id+' => 'Идентификатор объекта целевого класса триггера',
));

//
// Class: EventNotificationEmail
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:EventNotificationEmail' => 'Уведомление по email',
	'Class:EventNotificationEmail+' => 'Уведомление по email',
	'Class:EventNotificationEmail/Attribute:to' => 'Кому',
	'Class:EventNotificationEmail/Attribute:to+' => 'Кому',
	'Class:EventNotificationEmail/Attribute:cc' => 'Копия',
	'Class:EventNotificationEmail/Attribute:cc+' => 'Копия',
	'Class:EventNotificationEmail/Attribute:bcc' => 'Скрытая копия',
	'Class:EventNotificationEmail/Attribute:bcc+' => 'Скрытая копия',
	'Class:EventNotificationEmail/Attribute:from' => 'От',
	'Class:EventNotificationEmail/Attribute:from+' => 'Отправитель сообщения',
	'Class:EventNotificationEmail/Attribute:subject' => 'Тема',
	'Class:EventNotificationEmail/Attribute:subject+' => 'Тема',
	'Class:EventNotificationEmail/Attribute:body' => 'Сообщение',
	'Class:EventNotificationEmail/Attribute:body+' => 'Сообщение',
	'Class:EventNotificationEmail/Attribute:attachments' => 'Вложения',
	'Class:EventNotificationEmail/Attribute:attachments+' => '',
));

//
// Class: EventIssue
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:EventIssue' => 'Выпуск события',
	'Class:EventIssue+' => 'Отслеживание выпуска (warning, error, др.)',
	'Class:EventIssue/Attribute:issue' => 'Выпуск',
	'Class:EventIssue/Attribute:issue+' => 'Что произошло',
	'Class:EventIssue/Attribute:impact' => 'Воздействие',
	'Class:EventIssue/Attribute:impact+' => 'Последствия',
	'Class:EventIssue/Attribute:page' => 'Страница',
	'Class:EventIssue/Attribute:page+' => 'Точка входа HTTP',
	'Class:EventIssue/Attribute:arguments_post' => 'Отправленные аргументы',
	'Class:EventIssue/Attribute:arguments_post+' => 'Аргументы HTTP POST',
	'Class:EventIssue/Attribute:arguments_get' => 'Аргументы URL',
	'Class:EventIssue/Attribute:arguments_get+' => 'Аргументы HTTP GET',
	'Class:EventIssue/Attribute:callstack' => 'Стек вызовов',
	'Class:EventIssue/Attribute:callstack+' => 'Стек вызовов',
	'Class:EventIssue/Attribute:data' => 'Данные',
	'Class:EventIssue/Attribute:data+' => 'Подробнее',
));

//
// Classes: EventWebService and EventRestService
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:EventWebService' => 'События Web сервиса',
	'Class:EventWebService+' => 'Trace of an web service call',
	'Class:EventWebService/Attribute:verb' => 'Verb',
	'Class:EventWebService/Attribute:verb+' => 'Название операции',
	'Class:EventWebService/Attribute:result' => 'Результат',
	'Class:EventWebService/Attribute:result+' => 'Все удачн./неудачн.',
	'Class:EventWebService/Attribute:log_info' => 'Журнал',
	'Class:EventWebService/Attribute:log_info+' => 'Результаты журнала',
	'Class:EventWebService/Attribute:log_warning' => 'Лог предупреждений',
	'Class:EventWebService/Attribute:log_warning+' => 'Результаты логов предупреждений',
	'Class:EventWebService/Attribute:log_error' => 'Лог ошибок',
	'Class:EventWebService/Attribute:log_error+' => 'Результаты логов ошибок',
	'Class:EventWebService/Attribute:data' => 'Данные',
	'Class:EventWebService/Attribute:data+' => 'Результаты данных',
	'Class:EventRestService' => 'REST/JSON call~~',
	'Class:EventRestService+' => 'Trace of a REST/JSON service call~~',
	'Class:EventRestService/Attribute:operation' => 'Operation~~',
	'Class:EventRestService/Attribute:operation+' => 'Argument \'operation\'~~',
	'Class:EventRestService/Attribute:version' => 'Version~~',
	'Class:EventRestService/Attribute:version+' => 'Argument \'version\'~~',
	'Class:EventRestService/Attribute:json_input' => 'Input~~',
	'Class:EventRestService/Attribute:json_input+' => 'Argument \'json_data\'~~',
	'Class:EventRestService/Attribute:code' => 'Code~~',
	'Class:EventRestService/Attribute:code+' => 'Result code~~',
	'Class:EventRestService/Attribute:json_output' => 'Response~~',
	'Class:EventRestService/Attribute:json_output+' => 'HTTP response (json)~~',
	'Class:EventRestService/Attribute:provider' => 'Provider~~',
	'Class:EventRestService/Attribute:provider+' => 'PHP class implementing the expected operation~~',
));

//
// Class: EventLoginUsage
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:EventLoginUsage' => 'Статистика авторизаций~~',
	'Class:EventLoginUsage+' => 'Connection to the application',
	'Class:EventLoginUsage/Attribute:user_id' => 'Логин~~',
	'Class:EventLoginUsage/Attribute:user_id+' => 'Login',
	'Class:EventLoginUsage/Attribute:contact_name' => 'Имя пользователя~~',
	'Class:EventLoginUsage/Attribute:contact_name+' => 'Имя пользователя',
	'Class:EventLoginUsage/Attribute:contact_email' => 'Email пользователя~~',
	'Class:EventLoginUsage/Attribute:contact_email+' => 'Email Address of the User',
));

//
// Class: Action
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Action' => 'Заказное действие',
	'Class:Action+' => 'Действие определённое пользователем',
	'Class:Action/Attribute:name' => 'Имя',
	'Class:Action/Attribute:name+' => '',
	'Class:Action/Attribute:description' => 'Описание',
	'Class:Action/Attribute:description+' => '',
	'Class:Action/Attribute:status' => 'Статус',
	'Class:Action/Attribute:status+' => 'В производстве или ?',
	'Class:Action/Attribute:status/Value:test' => 'Проходит проверку',
	'Class:Action/Attribute:status/Value:test+' => 'Проходит проверку',
	'Class:Action/Attribute:status/Value:enabled' => 'В производстве',
	'Class:Action/Attribute:status/Value:enabled+' => 'В производстве',
	'Class:Action/Attribute:status/Value:disabled' => 'Неактивный',
	'Class:Action/Attribute:status/Value:disabled+' => 'Неактивный',
	'Class:Action/Attribute:trigger_list' => 'Связанные триггеры',
	'Class:Action/Attribute:trigger_list+' => 'Триггеры привызанные к этому действию',
	'Class:Action/Attribute:finalclass' => 'Тип',
	'Class:Action/Attribute:finalclass+' => '',
));

//
// Class: ActionNotification
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:ActionNotification' => 'Уведомление',
	'Class:ActionNotification+' => 'Уведомление (выдержка)',
));

//
// Class: ActionEmail
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:ActionEmail' => 'Уведомление по email',
	'Class:ActionEmail+' => '',
	'Class:ActionEmail/Attribute:test_recipient' => 'Проверочный получатель',
	'Class:ActionEmail/Attribute:test_recipient+' => 'Получатель, если уведомление в статусе "Проходит проверку"',
	'Class:ActionEmail/Attribute:from' => 'От',
	'Class:ActionEmail/Attribute:from+' => 'Будет отослано в заголовке email',
	'Class:ActionEmail/Attribute:reply_to' => 'Ответить на',
	'Class:ActionEmail/Attribute:reply_to+' => 'Будет отослано в заголовке email',
	'Class:ActionEmail/Attribute:to' => 'Кому',
	'Class:ActionEmail/Attribute:to+' => 'Получатель email',
	'Class:ActionEmail/Attribute:cc' => 'Копия',
	'Class:ActionEmail/Attribute:cc+' => 'Копия',
	'Class:ActionEmail/Attribute:bcc' => 'Скр. копия',
	'Class:ActionEmail/Attribute:bcc+' => 'Скрытая копия',
	'Class:ActionEmail/Attribute:subject' => 'тема',
	'Class:ActionEmail/Attribute:subject+' => 'Заголовок письма',
	'Class:ActionEmail/Attribute:body' => 'тело',
	'Class:ActionEmail/Attribute:body+' => 'Содержимое письма',
	'Class:ActionEmail/Attribute:importance' => 'значение',
	'Class:ActionEmail/Attribute:importance+' => 'Флаг значения',
	'Class:ActionEmail/Attribute:importance/Value:low' => 'низкий',
	'Class:ActionEmail/Attribute:importance/Value:low+' => 'низкий',
	'Class:ActionEmail/Attribute:importance/Value:normal' => 'нормальный',
	'Class:ActionEmail/Attribute:importance/Value:normal+' => 'нормальный',
	'Class:ActionEmail/Attribute:importance/Value:high' => 'высокий',
	'Class:ActionEmail/Attribute:importance/Value:high+' => 'высокий',
));

//
// Class: Trigger
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Trigger' => 'Триггер',
	'Class:Trigger+' => 'Заказной триггер события',
	'Class:Trigger/Attribute:description' => 'Описание',
	'Class:Trigger/Attribute:description+' => 'однострочное описание',
	'Class:Trigger/Attribute:action_list' => 'Действия триггера',
	'Class:Trigger/Attribute:action_list+' => 'Действия, выполняемые при активации триггера',
	'Class:Trigger/Attribute:finalclass' => 'Тип',
	'Class:Trigger/Attribute:finalclass+' => '',
));

//
// Class: TriggerOnObject
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:TriggerOnObject' => 'Триггер (в зависимости класс)',
	'Class:TriggerOnObject+' => 'Триггер по даному классу объектов',
	'Class:TriggerOnObject/Attribute:target_class' => 'Целевой класс',
	'Class:TriggerOnObject/Attribute:target_class+' => '',
));

//
// Class: TriggerOnPortalUpdate
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:TriggerOnPortalUpdate' => 'Триггер (при обновлении из портала)',
	'Class:TriggerOnPortalUpdate+' => 'Trigger on a end-user\'s update from the portal',
));

//
// Class: TriggerOnStateChange
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:TriggerOnStateChange' => 'Триггер (на изменение состояния)',
	'Class:TriggerOnStateChange+' => 'Триггер на изменение состояния объекта',
	'Class:TriggerOnStateChange/Attribute:state' => 'Статус',
	'Class:TriggerOnStateChange/Attribute:state+' => '',
));

//
// Class: TriggerOnStateEnter
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:TriggerOnStateEnter' => 'Триггер (на начало состояния)',
	'Class:TriggerOnStateEnter+' => 'Триггер на изменению состояния объекта - начало',
));

//
// Class: TriggerOnStateLeave
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:TriggerOnStateLeave' => 'Триггер (на окончание состояния)',
	'Class:TriggerOnStateLeave+' => 'Триггер на изменению состояния объекта - окончание',
));

//
// Class: TriggerOnObjectCreate
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:TriggerOnObjectCreate' => 'Триггер (на создание объекта)',
	'Class:TriggerOnObjectCreate+' => 'Триггер на создание объекта [дочерний класс] данного класса',
));

//
// Class: TriggerOnThresholdReached
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:TriggerOnThresholdReached' => 'Триггер (пороговое)~~',
	'Class:TriggerOnThresholdReached+' => 'Trigger on Stop-Watch threshold reached',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code' => 'Секундомер~~',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code+' => '',
	'Class:TriggerOnThresholdReached/Attribute:threshold_index' => 'Порог~~',
	'Class:TriggerOnThresholdReached/Attribute:threshold_index+' => '',
));

//
// Class: lnkTriggerAction
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkTriggerAction' => 'Действие/Триггер',
	'Class:lnkTriggerAction+' => 'Связь между триггером и действий',
	'Class:lnkTriggerAction/Attribute:action_id' => 'Действие',
	'Class:lnkTriggerAction/Attribute:action_id+' => 'Выполняемое действие',
	'Class:lnkTriggerAction/Attribute:action_name' => 'Действие',
	'Class:lnkTriggerAction/Attribute:action_name+' => '',
	'Class:lnkTriggerAction/Attribute:trigger_id' => 'Триггер',
	'Class:lnkTriggerAction/Attribute:trigger_id+' => '',
	'Class:lnkTriggerAction/Attribute:trigger_name' => 'Триггер',
	'Class:lnkTriggerAction/Attribute:trigger_name+' => '',
	'Class:lnkTriggerAction/Attribute:order' => 'Порядок',
	'Class:lnkTriggerAction/Attribute:order+' => 'Порядок выполнения действий',
));

//
// Synchro Data Source
//
Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:SynchroDataSource/Attribute:name' => 'Название',
	'Class:SynchroDataSource/Attribute:name+' => 'Название',
	'Class:SynchroDataSource/Attribute:description' => 'Описание',
	'Class:SynchroDataSource/Attribute:status' => 'Статус', //TODO: enum values
	'Class:SynchroDataSource/Attribute:scope_class' => 'Целевой класс',
	'Class:SynchroDataSource/Attribute:user_id' => 'Пользователь~~',
	'Class:SynchroDataSource/Attribute:notify_contact_id' => 'Слать уведомления',
	'Class:SynchroDataSource/Attribute:notify_contact_id+' => 'Contact to notify in case of error',
	'Class:SynchroDataSource/Attribute:url_icon' => 'Ссылка на иконку',
	'Class:SynchroDataSource/Attribute:url_icon+' => 'Hyperlink a (small) image representing the application with which iTop is synchronized',
	'Class:SynchroDataSource/Attribute:url_application' => 'Ссылки программ',
	'Class:SynchroDataSource/Attribute:url_application+' => 'Hyperlink to the iTop object in the external application with which iTop is synchronized (if applicable). Possible placeholders: $this->attribute$ and $replica->primary_key$',
	'Class:SynchroDataSource/Attribute:reconciliation_policy' => 'Политика согласования', //TODO enum values
	'Class:SynchroDataSource/Attribute:full_load_periodicity' => 'Интервал полной нагрузки~~',
	'Class:SynchroDataSource/Attribute:full_load_periodicity+' => 'A complete reload of all data must occur at least as often as specified here',
	'Class:SynchroDataSource/Attribute:action_on_zero' => 'Действие при нуле',
	'Class:SynchroDataSource/Attribute:action_on_zero+' => 'Action taken when the search returns no object',
	'Class:SynchroDataSource/Attribute:action_on_one' => 'Действие при единице',
	'Class:SynchroDataSource/Attribute:action_on_one+' => 'Action taken when the search returns exactly one object',
	'Class:SynchroDataSource/Attribute:action_on_multiple' => 'Действия при множестве~~',
	'Class:SynchroDataSource/Attribute:action_on_multiple+' => 'Action taken when the search returns more than one object',
	'Class:SynchroDataSource/Attribute:user_delete_policy' => 'Разрешено',
	'Class:SynchroDataSource/Attribute:user_delete_policy+' => 'Who is allowed to delete synchronized objects',
	'Class:SynchroDataSource/Attribute:user_delete_policy' => 'Разрешено',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:never' => 'Никто~~',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:depends' => 'Только администратор~~',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:always' => 'Все пользователи~~',
	'Class:SynchroDataSource/Attribute:delete_policy_update' => 'Обновить правила~~',
	'Class:SynchroDataSource/Attribute:delete_policy_update+' => 'Syntax: field_name:value; ...',
	'Class:SynchroDataSource/Attribute:delete_policy_retention' => 'Время "жизни"',
	'Class:SynchroDataSource/Attribute:delete_policy_retention+' => 'How much time an obsolete object is kept before being deleted',
	'Class:SynchroDataSource/Attribute:database_table_name' => 'Таблица данных',
	'Class:SynchroDataSource/Attribute:database_table_name+' => 'Name of the table to store the synchronization data. If left empty, a default name will be computed.',
	'SynchroDataSource:Description' => 'Описание',
	'SynchroDataSource:Reconciliation' => 'Поиск и согласование~~',
	'SynchroDataSource:Deletion' => 'Правила для удаления~~',
	'SynchroDataSource:Status' => 'Статус~~',
	'SynchroDataSource:Information' => 'Инфо~~',
	'SynchroDataSource:Definition' => 'Definition~~',
	'Core:SynchroAttributes' => 'Аттрибуты~~',
	'Core:SynchroStatus' => 'Свойства~~',
	'Core:Synchro:ErrorsLabel' => 'Ошибки~~',	
	'Core:Synchro:CreatedLabel' => 'Создан~~',
	'Core:Synchro:ModifiedLabel' => 'Изменен~~',
	'Core:Synchro:UnchangedLabel' => 'Неизменен~~',
	'Core:Synchro:ReconciledErrorsLabel' => 'Ошибки~~',
	'Core:Synchro:ReconciledLabel' => 'Reconciled~~',
	'Core:Synchro:ReconciledNewLabel' => 'Создан~~',
	'Core:SynchroReconcile:Yes' => 'Да~~',
	'Core:SynchroReconcile:No' => 'Нет~~',
	'Core:SynchroUpdate:Yes' => 'Да~~',
	'Core:SynchroUpdate:No' => 'Нет~~',
	'Core:Synchro:LastestStatus' => 'Последний статус~~',
	'Core:Synchro:History' => 'История синхронизаций~~',
	'Core:Synchro:NeverRun' => 'Синхронизация не запускалась. Логи отсутсвуют.~~',
	'Core:Synchro:SynchroEndedOn_Date' => 'Синхронизация была закончена в %1$s.~~',
	'Core:Synchro:SynchroRunningStartedOn_Date' => 'Синхронизация запущена в %1$s сейчас в процессе...~~',
	'Menu:DataSources' => 'Синхронизация данных', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataSources+' => 'All Synchronization Data Sources', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Core:Synchro:label_repl_ignored' => 'Игнор. (%1$s)~~',
	'Core:Synchro:label_repl_disappeared' => 'Невид. (%1$s)~~',
	'Core:Synchro:label_repl_existing' => 'Existing (%1$s)~~',
	'Core:Synchro:label_repl_new' => 'Новый (%1$s)~~',
	'Core:Synchro:label_obj_deleted' => 'Удаленный (%1$s)~~',
	'Core:Synchro:label_obj_obsoleted' => 'Obsoleted (%1$s)~~',
	'Core:Synchro:label_obj_disappeared_errors' => 'Ошибки (%1$s)~~',
	'Core:Synchro:label_obj_disappeared_no_action' => 'No Action (%1$s)~~',
	'Core:Synchro:label_obj_unchanged' => 'Unchanged (%1$s)~~',
	'Core:Synchro:label_obj_updated' => 'Обновлен (%1$s)~~', 
	'Core:Synchro:label_obj_updated_errors' => 'Ошибки (%1$s)~~',
	'Core:Synchro:label_obj_new_unchanged' => 'Unchanged (%1$s)~~',
	'Core:Synchro:label_obj_new_updated' => 'Обновлен (%1$s)~~',
	'Core:Synchro:label_obj_created' => 'Создан (%1$s)~~',
	'Core:Synchro:label_obj_new_errors' => 'Ошибки (%1$s)~~',
	'Core:Synchro:History' => 'История синхронизаций~~',
	'Core:SynchroLogTitle' => '%1$s - %2$s~~',
	'Core:Synchro:Nb_Replica' => 'Replica processed: %1$s~~',
	'Core:Synchro:Nb_Class:Objects' => '%1$s: %2$s~~',
	'Class:SynchroDataSource/Error:AtLeastOneReconciliationKeyMustBeSpecified' => 'At Least one reconciliation key must be specified, or the reconciliation policy must be to use the primary key.~~',			
	'Class:SynchroDataSource/Error:DeleteRetentionDurationMustBeSpecified' => 'A delete retention period must be specified, since objects are to be deleted after being marked as obsolete~~',			
	'Class:SynchroDataSource/Error:DeletePolicyUpdateMustBeSpecified' => 'Obsolete objects are to be updated, but no update is specified.~~',
	'Class:SynchroDataSource/Error:DataTableAlreadyExists' => 'Строка %1$s уже есть в базе. Пожалуйста, используйте другое значение для синхронизаций.~~',
	'Core:SynchroReplica:PublicData' => 'Public Data~~',
	'Core:SynchroReplica:PrivateDetails' => 'Private Details~~',
	'Core:SynchroReplica:BackToDataSource' => 'Go Back to the Synchro Data Source: %1$s~~',
	'Core:SynchroReplica:ListOfReplicas' => 'List of Replica~~',
	'Core:SynchroAttExtKey:ReconciliationById' => 'id (Primary Key)~~',
	'Core:SynchroAtt:attcode' => 'Аттрибут~~',
	'Core:SynchroAtt:attcode+' => 'Field of the object',
	'Core:SynchroAtt:reconciliation' => 'Reconciliation ?~~',
	'Core:SynchroAtt:reconciliation+' => 'Used for searching',
	'Core:SynchroAtt:update' => 'Обновить?',
	'Core:SynchroAtt:update+' => 'Used to update the object',
	'Core:SynchroAtt:update_policy' => 'Политика обновлений~~',
	'Core:SynchroAtt:update_policy+' => 'Behavior of the updated field',
	'Core:SynchroAtt:reconciliation_attcode' => 'Reconciliation Key~~',
	'Core:SynchroAtt:reconciliation_attcode+' => 'Attribute Code for the External Key Reconciliation',
	'Core:SyncDataExchangeComment' => '(Data Synchro)~~',
	'Core:Synchro:ListOfDataSources' => 'Список данных:~~',
	'Core:Synchro:LastSynchro' => 'Последняя синхронизация:',
	'Core:Synchro:ThisObjectIsSynchronized' => 'This object is synchronized with an external data source~~',
	'Core:Synchro:TheObjectWasCreatedBy_Source' => 'The object was <b>created</b> by the external data source %1$s~~',
	'Core:Synchro:TheObjectCanBeDeletedBy_Source' => 'The object <b>can be deleted</b> by the external data source %1$s~~',
	'Core:Synchro:TheObjectCannotBeDeletedByUser_Source' => 'You <b>cannot delete the object</b> because it is owned by the external data source %1$s~~',
	'TitleSynchroExecution' => 'Запуск синхронизаций.~~',
	'Class:SynchroDataSource:DataTable' => 'Таблица: %1$s~~',
	'Core:SyncDataSourceObsolete' => 'The data source is marked as obsolete. Operation cancelled.~~',
	'Core:SyncDataSourceAccessRestriction' => 'Могут запускать только администраторы и определенные пользователи. Операция отменена.~~',
	'Core:SyncTooManyMissingReplicas' => 'All records have been untouched for some time (all of the objects could be deleted). Please check that the process that writes into the synchronization table is still running. Operation cancelled.~~',
	'Core:SyncSplitModeCLIOnly' => 'The synchronization can be executed in chunks only if run in mode CLI~~',
	'Core:Synchro:ListReplicas_AllReplicas_Errors_Warnings' => '%1$s replicas, Ошибок %2$s, Предупреждений %3$s.~~',
	'Core:SynchroReplica:TargetObject' => 'Синхронизировано объектов: %1$s~~',
	'Class:AsyncSendEmail' => 'Email (asynchronous)~~',
	'Class:AsyncSendEmail/Attribute:to' => 'Кому~~',
	'Class:AsyncSendEmail/Attribute:subject' => 'Получатель~~',
	'Class:AsyncSendEmail/Attribute:body' => 'Тело~~',
	'Class:AsyncSendEmail/Attribute:header' => 'Заголовок~~',
	'Class:CMDBChangeOpSetAttributeOneWayPassword' => 'Шифрованный пароль',
	'Class:CMDBChangeOpSetAttributeOneWayPassword/Attribute:prev_pwd' => 'Предыдущее значение~~',
	'Class:CMDBChangeOpSetAttributeEncrypted' => 'Encrypted Field~~',
	'Class:CMDBChangeOpSetAttributeEncrypted/Attribute:prevstring' => 'Предыдущее значение~~',
	'Class:CMDBChangeOpSetAttributeCaseLog' => 'Лог',
	'Class:CMDBChangeOpSetAttributeCaseLog/Attribute:lastentry' => 'Посл.значение',
	'Class:SynchroDataSource' => 'Синх.исходные данные',
	'Class:SynchroDataSource/Attribute:status/Value:implementation' => 'Имплементация',
	'Class:SynchroDataSource/Attribute:status/Value:obsolete' => 'Устаревший~~',
	'Class:SynchroDataSource/Attribute:status/Value:production' => 'Производство~~',
	'Class:SynchroDataSource/Attribute:scope_restriction' => 'Объем ограничений',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_attributes' => 'Использовать аттрибуты',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_primary_key' => 'Использовать primary_key значение',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:create' => 'Создать~~',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:error' => 'Ошибка~~',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:error' => 'Ошибка~~',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:update' => 'Обновить~~',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:create' => 'Создать~~',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:error' => 'Ошибка~~',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:take_first' => 'Взять первый (случайно?)~~',
	'Class:SynchroDataSource/Attribute:delete_policy' => 'Удалить правило~~',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:delete' => 'Удалить~~',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:ignore' => 'Игнорировать~~',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update' => 'Обновить~~',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update_then_delete' => 'Обновить и удалить',
	'Class:SynchroDataSource/Attribute:attribute_list' => 'Список свойств~~',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:administrators' => 'Только администраторы~~',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:everybody' => 'Каждый может удалить объект',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:nobody' => 'Никто',
	'Class:SynchroAttribute' => 'Синх.характеристики~~',
	'Class:SynchroAttribute/Attribute:sync_source_id' => 'Синхронизация данных',
	'Class:SynchroAttribute/Attribute:attcode' => 'Код аттрибута',
	'Class:SynchroAttribute/Attribute:update' => 'Обновить',
	'Class:SynchroAttribute/Attribute:reconcile' => 'Согласование',
	'Class:SynchroAttribute/Attribute:update_policy' => 'Обновить политику',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_locked' => 'Заблокирован',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_unlocked' => 'Разблокирован',
	'Class:SynchroAttribute/Attribute:update_policy/Value:write_if_empty' => 'Инициализация если пусто',
	'Class:SynchroAttribute/Attribute:finalclass' => 'Класс~~',
	'Class:SynchroAttExtKey' => 'Synchro Attribute (ExtKey)~~',
	'Class:SynchroAttExtKey/Attribute:reconciliation_attcode' => 'Аттрибут согласования',
	'Class:SynchroAttLinkSet' => 'Synchro Attribute (Linkset)~~',
	'Class:SynchroAttLinkSet/Attribute:row_separator' => 'Разделитель строк',
	'Class:SynchroAttLinkSet/Attribute:attribute_separator' => 'Делитель аттрибутов',
	'Class:SynchroLog' => 'Synchr Log~~',
	'Class:SynchroLog/Attribute:sync_source_id' => 'Синх.исходные данные',
	'Class:SynchroLog/Attribute:start_date' => 'Стартовать в',
	'Class:SynchroLog/Attribute:end_date' => 'Закончить в',
	'Class:SynchroLog/Attribute:status' => 'Статус',
	'Class:SynchroLog/Attribute:status/Value:completed' => 'Завершен',
	'Class:SynchroLog/Attribute:status/Value:error' => 'Ошибка',
	'Class:SynchroLog/Attribute:status/Value:running' => 'Запущен',
	'Class:SynchroLog/Attribute:stats_nb_replica_seen' => 'Nb replica seen~~',
	'Class:SynchroLog/Attribute:stats_nb_replica_total' => 'Nb replica total~~',
	'Class:SynchroLog/Attribute:stats_nb_obj_deleted' => 'Nb objects deleted~~',
	'Class:SynchroLog/Attribute:stats_nb_obj_deleted_errors' => 'Nb of errors while deleting~~',
	'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted' => 'Nb objects obsoleted~~',
	'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted_errors' => 'Nb of errors while obsoleting~~',
	'Class:SynchroLog/Attribute:stats_nb_obj_created' => 'Nb objects created~~',
	'Class:SynchroLog/Attribute:stats_nb_obj_created_errors' => 'Nb or errors while creating~~',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated' => 'Nb objects updated~~',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated_errors' => 'Nb errors while updating~~',
	'Class:SynchroLog/Attribute:stats_nb_replica_reconciled_errors' => 'Nb of errors during reconciliation~~',
	'Class:SynchroLog/Attribute:stats_nb_replica_disappeared_no_action' => 'Nb replica disappeared~~',
	'Class:SynchroLog/Attribute:stats_nb_obj_new_updated' => 'Nb objects updated~~',
	'Class:SynchroLog/Attribute:stats_nb_obj_new_unchanged' => 'Nb objects unchanged~~',
	'Class:SynchroLog/Attribute:last_error' => 'Посл.ошибка',
	'Class:SynchroLog/Attribute:traces' => 'Слежения',
	'Class:SynchroReplica' => 'Synchro Replica~~',
	'Class:SynchroReplica/Attribute:sync_source_id' => 'Синх.исходные данные',
	'Class:SynchroReplica/Attribute:dest_id' => 'Назначение объекта',
	'Class:SynchroReplica/Attribute:dest_class' => 'Назначение типа',
	'Class:SynchroReplica/Attribute:status_last_seen' => 'Был виден',
	'Class:SynchroReplica/Attribute:status' => 'Статус~~',
	'Class:SynchroReplica/Attribute:status/Value:modified' => 'Изменен',
	'Class:SynchroReplica/Attribute:status/Value:new' => 'Новый',
	'Class:SynchroReplica/Attribute:status/Value:obsolete' => 'Устаревший',
	'Class:SynchroReplica/Attribute:status/Value:orphan' => 'Сирота',
	'Class:SynchroReplica/Attribute:status/Value:synchronized' => 'Синхронизирован',
	'Class:SynchroReplica/Attribute:status_dest_creator' => 'Объект создан',
	'Class:SynchroReplica/Attribute:status_last_error' => 'Посл.ошибка',
	'Class:SynchroReplica/Attribute:status_last_warning' => 'Предупреждения',
	'Class:SynchroReplica/Attribute:info_creation_date' => 'Дата создания',
	'Class:SynchroReplica/Attribute:info_last_modified' => 'Дата последнего изменения',
	'Class:appUserPreferences' => 'Свойства пользователей',
	'Class:appUserPreferences/Attribute:userid' => 'Пользователь',
	'Class:appUserPreferences/Attribute:preferences' => 'Свойства',
	'Core:ExecProcess:Code1' => 'Неверная команда или команда завершена с ошибкой (возможно, неверное имя скрипта)',
	'Core:ExecProcess:Code255' => 'Ошибка PHP (parsing, or runtime)~~',
));

//
// Attribute Duration
//
Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Core:Duration_Seconds'	=> '%1$ds~~',	
	'Core:Duration_Minutes_Seconds'	=>'%1$dmin %2$ds~~',	
	'Core:Duration_Hours_Minutes_Seconds' => '%1$dh %2$dmin %3$ds~~',		
	'Core:Duration_Days_Hours_Minutes_Seconds' => '%1$sd %2$dh %3$dmin %4$ds~~',		
	'Class:TriggerOnObject/Attribute:filter' => 'Filter~~',
	'TriggerOnObject:WrongFilterQuery' => 'Wrong filter query: %1$s~~',
	'TriggerOnObject:WrongFilterClass' => 'The filter query must return objects of class \"%1$s\"~~',
	'Core:ExplainWTC:ElapsedTime' => 'Time elapsed (stored as \"%1$s\")~~',
	'Core:ExplainWTC:StopWatch-TimeSpent' => 'Time spent for \"%1$s\"~~',
	'Core:ExplainWTC:StopWatch-Deadline' => 'Deadline for \"%1$s\" at %2$d%%~~',
	'Core:BulkExport:MissingParameter_Param' => 'Missing parameter \"%1$s\"~~',
	'Core:BulkExport:InvalidParameter_Query' => 'Invalid value for the parameter \"query\". There is no Query Phrasebook corresponding to the id: \"%1$s\".~~',
	'Core:BulkExport:ExportFormatPrompt' => 'Export format:~~',
	'Core:BulkExportOf_Class' => '%1$s Export~~',
	'Core:BulkExport:ClickHereToDownload_FileName' => 'Click here to download %1$s~~',
	'Core:BulkExport:ExportResult' => 'Result of the export:~~',
	'Core:BulkExport:RetrievingData' => 'Retrieving data...~~',
	'Core:BulkExport:HTMLFormat' => 'Web Page (*.html)~~',
	'Core:BulkExport:CSVFormat' => 'Comma Separated Values (*.csv)~~',
	'Core:BulkExport:XLSXFormat' => 'Excel 2007 or newer (*.xlsx)~~',
	'Core:BulkExport:PDFFormat' => 'PDF Document (*.pdf)~~',
	'Core:BulkExport:DragAndDropHelp' => 'Drag and drop the columns\' headers to arrange the columns. Preview of %1$s lines. Total number of lines to export: %2$s.~~',
	'Core:BulkExport:EmptyPreview' => 'Select the columns to be exported from the list above~~',
	'Core:BulkExport:ColumnsOrder' => 'Columns order~~',
	'Core:BulkExport:AvailableColumnsFrom_Class' => 'Available columns from %1$s~~',
	'Core:BulkExport:NoFieldSelected' => 'Select at least one column to be exported~~',
	'Core:BulkExport:CheckAll' => 'Check All~~',
	'Core:BulkExport:UncheckAll' => 'Uncheck All~~',
	'Core:BulkExport:ExportCancelledByUser' => 'Export cancelled by the user~~',
	'Core:BulkExport:CSVOptions' => 'CSV Options~~',
	'Core:BulkExport:CSVLocalization' => 'Localization~~',
	'Core:BulkExport:PDFOptions' => 'PDF Options~~',
	'Core:BulkExport:PDFPageSize' => 'Page Size:~~',
	'Core:BulkExport:PageSize-A4' => 'A4~~',
	'Core:BulkExport:PageSize-A3' => 'A3~~',
	'Core:BulkExport:PageSize-Letter' => 'Letter~~',
	'Core:BulkExport:PDFPageOrientation' => 'Page Orientation:~~',
	'Core:BulkExport:PageOrientation-L' => 'Landscape~~',
	'Core:BulkExport:PageOrientation-P' => 'Portrait~~',
	'Core:BulkExport:XMLFormat' => 'XML file (*.xml)~~',
	'Core:BulkExport:XMLOptions' => 'XML Options~~',
	'Core:BulkExport:SpreadsheetFormat' => 'Spreadsheet HTML format (*.html)~~',
	'Core:BulkExport:SpreadsheetOptions' => 'Spreadsheet Options~~',
	'Core:BulkExport:OptionLinkSets' => 'Include linked objects~~',
	'Core:BulkExport:OptionNoLocalize' => 'Do not localize the values (for Enumerated fields)~~',
	'Core:BulkExport:ScopeDefinition' => 'Definition of the objects to export~~',
	'Core:BulkExportLabelOQLExpression' => 'OQL Query:~~',
	'Core:BulkExportLabelPhrasebookEntry' => 'Query Phrasebook Entry:~~',
	'Core:BulkExportMessageEmptyOQL' => 'Please enter a valid OQL query.~~',
	'Core:BulkExportMessageEmptyPhrasebookEntry' => 'Please select a valid phrasebook entry.~~',
	'Core:BulkExportQueryPlaceholder' => 'Type an OQL query here...~~',
	'Core:BulkExportCanRunNonInteractive' => 'Click here to run the export in non-interactive mode.~~',
	'Core:BulkExportLegacyExport' => 'Click here to access the legacy export.~~',
));

?>
