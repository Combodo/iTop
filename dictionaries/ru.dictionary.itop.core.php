<?php

/**
 * Локализация интерфейса Combodo iTop подготовлена сообществом iTop по-русски http://community.itop-itsm.ru.
 *
 * @author      Vladimir Kunin <v.b.kunin@gmail.com>
 * @link        http://community.itop-itsm.ru  iTop Russian Community
 * @link        https://github.com/itop-itsm-ru/itop-rus
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
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
	'Core:AttributeBoolean/Value:null' => '',
	'Core:AttributeBoolean/Value:yes' => 'Yes~~',
	'Core:AttributeBoolean/Value:no' => 'No~~',

	'Core:AttributeArchiveFlag' => 'Archive flag~~',
	'Core:AttributeArchiveFlag/Value:yes' => 'Yes~~',
	'Core:AttributeArchiveFlag/Value:yes+' => 'This object is visible only in archive mode~~',
	'Core:AttributeArchiveFlag/Value:no' => 'No~~',
	'Core:AttributeArchiveFlag/Label' => 'Archived~~',
	'Core:AttributeArchiveFlag/Label+' => '',
	'Core:AttributeArchiveDate/Label' => 'Archive date~~',
	'Core:AttributeArchiveDate/Label+' => '',

	'Core:AttributeObsolescenceFlag' => 'Obsolescence flag~~',
	'Core:AttributeObsolescenceFlag/Value:yes' => 'Yes~~',
	'Core:AttributeObsolescenceFlag/Value:yes+' => 'This object is excluded from the impact analysis, and hidden from search results~~',
	'Core:AttributeObsolescenceFlag/Value:no' => 'No~~',
	'Core:AttributeObsolescenceFlag/Label' => 'Obsolete~~',
	'Core:AttributeObsolescenceFlag/Label+' => 'Computed dynamically on other attributes~~',
	'Core:AttributeObsolescenceDate/Label' => 'Obsolescence date~~',
	'Core:AttributeObsolescenceDate/Label+' => 'Approximative date at which the object has been considered obsolete~~',

	'Core:AttributeString' => 'Строка',
	'Core:AttributeString+' => 'Alphanumeric string',

	'Core:AttributeClass' => 'Класс',
	'Core:AttributeClass+' => 'Class',

	'Core:AttributeApplicationLanguage' => 'Язык пользователя',
	'Core:AttributeApplicationLanguage+' => 'Язык и страна (EN US)',

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
	Формат даты:<br/>
	<b>гггг-мм-дд чч:мм:сс</b><br/>
	Пример: 2017-07-19 18:40:00
</p>
<p>
Операторы:<br/>
	<b>&gt;</b><em>дата</em><br/>
	<b>&lt;</b><em>дата</em><br/>
	<b>[</b><em>дата</em>,<em>дата</em><b>]</b>
</p>
<p>
Если время не указано, по умолчанию используется 00:00:00
</p>',

	'Core:AttributeDate' => 'Дата',
	'Core:AttributeDate+' => 'Дата (год-месяц-день)',
	'Core:AttributeDate?SmartSearch' => '
<p>
	Формат даты:<br/>
	<b>гггг-мм-дд</b><br/>
	Пример: 2017-07-19
</p>
<p>
Операторы:<br/>
	<b>&gt;</b><em>дата</em><br/>
	<b>&lt;</b><em>дата</em><br/>
	<b>[</b><em>дата</em>,<em>дата</em><b>]</b>
</p>',

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

	'Core:AttributeFriendlyName' => 'Полное название',
	'Core:AttributeFriendlyName+' => 'Атрибут создается автоматически; полное название вычисляется из нескольких атрибутов',

	'Core:FriendlyName-Label' => 'Полное название',
	'Core:FriendlyName-Description' => 'Полное название',
));


//////////////////////////////////////////////////////////////////////
// Classes in 'core/cmdb'
//////////////////////////////////////////////////////////////////////
//

//
// Class: CMDBChange
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:CMDBChange' => 'Изменение CMDB',
	'Class:CMDBChange+' => 'Отслеживание изменений CMDB',
	'Class:CMDBChange/Attribute:date' => 'Дата',
	'Class:CMDBChange/Attribute:date+' => 'Дата и время изменения',
	'Class:CMDBChange/Attribute:origin' => 'Источник',
	'Class:CMDBChange/Attribute:origin+' => 'Источник приосхождения изменения',
	'Class:CMDBChange/Attribute:userinfo' => 'Пользователь',
	'Class:CMDBChange/Attribute:userinfo+' => 'Кто произвёл изменение',
));

//
// Class: CMDBChangeOp
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:CMDBChangeOp' => 'Операция изменения CMDB',
	'Class:CMDBChangeOp+' => 'Отслеживание операции изменения CMDB',
	'Class:CMDBChangeOp/Attribute:change' => 'Изменение CMDB',
	'Class:CMDBChangeOp/Attribute:change+' => 'Изменение CMDB',
	'Class:CMDBChangeOp/Attribute:date' => 'Дата',
	'Class:CMDBChangeOp/Attribute:date+' => 'Дата и время изменения',
	'Class:CMDBChangeOp/Attribute:userinfo' => 'Пользователь',
	'Class:CMDBChangeOp/Attribute:userinfo+' => 'Кто произвёл изменение',
	'Class:CMDBChangeOp/Attribute:objclass' => 'Класс объекта',
	'Class:CMDBChangeOp/Attribute:objclass+' => 'Класс объекта',
	'Class:CMDBChangeOp/Attribute:objkey' => 'ID объекта',
	'Class:CMDBChangeOp/Attribute:objkey+' => 'ID объекта',
	'Class:CMDBChangeOp/Attribute:finalclass' => 'Итоговый класс',
	'Class:CMDBChangeOp/Attribute:finalclass+' => '',
));

//
// Class: CMDBChangeOpCreate
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:CMDBChangeOpCreate' => 'Операция создания объекта',
	'Class:CMDBChangeOpCreate+' => 'Отслеживание создания объекта',
));

//
// Class: CMDBChangeOpDelete
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:CMDBChangeOpDelete' => 'Операция удаления объекта',
	'Class:CMDBChangeOpDelete+' => 'Отслеживание удаления объекта',
));

//
// Class: CMDBChangeOpSetAttribute
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:CMDBChangeOpSetAttribute' => 'Изменение объекта',
	'Class:CMDBChangeOpSetAttribute+' => 'Отслеживание изменения объекта',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode' => 'Свойство',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode+' => 'Код изменённого свойства',
));

//
// Class: CMDBChangeOpSetAttributeScalar
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:CMDBChangeOpSetAttributeScalar' => 'Изменение свойства',
	'Class:CMDBChangeOpSetAttributeScalar+' => 'Отслеживание изменения скалярного свойства объекта',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue' => 'Предыдущее значение',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue+' => 'Предыдущее значение атрибута',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue' => 'Новое значение',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue+' => 'Новое значение атрибута',
));
// Used by CMDBChangeOp... & derived classes
Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Change:ObjectCreated' => 'Объект создан.',
	'Change:ObjectDeleted' => 'Объект удалён.',
	'Change:ObjectModified' => 'Объект изменён.',
	'Change:AttName_SetTo_NewValue_PreviousValue_OldValue' => 'В поле "%1$s" установлено значение "%2$s" (предыдущее значение "%3$s").',
	'Change:AttName_SetTo' => 'В поле "%1$s" установлено значение "%2$s".',
	'Change:Text_AppendedTo_AttName' => 'Новое значение "%1$s" добавлено к полю "%2$s".',
	'Change:AttName_Changed_PreviousValue_OldValue' => 'Поле "%1$s" изменено (предыдущее значение "%2$s").',
	'Change:AttName_Changed' => 'Поле "%1$s" изменено.',
	'Change:AttName_EntryAdded' => 'В поле "%1$s" добавлено новое значение.',
	'Change:LinkSet:Added' => 'добавлен объект %1$s.',
	'Change:LinkSet:Removed' => 'удалён объект %1$s.',
	'Change:LinkSet:Modified' => 'изменён объект %1$s.',
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
// Class: EventWebService
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
));

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:EventRestService' => 'REST/JSON call',
	'Class:EventRestService+' => 'Trace of a REST/JSON service call',
	'Class:EventRestService/Attribute:operation' => 'Operation',
	'Class:EventRestService/Attribute:operation+' => 'Argument \'operation\'',
	'Class:EventRestService/Attribute:version' => 'Version',
	'Class:EventRestService/Attribute:version+' => 'Argument \'version\'',
	'Class:EventRestService/Attribute:json_input' => 'Input',
	'Class:EventRestService/Attribute:json_input+' => 'Argument \'json_data\'',
	'Class:EventRestService/Attribute:code' => 'Code',
	'Class:EventRestService/Attribute:code+' => 'Result code',
	'Class:EventRestService/Attribute:json_output' => 'Response',
	'Class:EventRestService/Attribute:json_output+' => 'HTTP response (json)',
	'Class:EventRestService/Attribute:provider' => 'Provider',
	'Class:EventRestService/Attribute:provider+' => 'PHP class implementing the expected operation',
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
	'Class:Action' => 'Действие',
	'Class:Action+' => 'Действие, определённое пользователем',
	'Class:Action/Attribute:name' => 'Название',
	'Class:Action/Attribute:name+' => '',
	'Class:Action/Attribute:description' => 'Описание',
	'Class:Action/Attribute:description+' => '',
	'Class:Action/Attribute:status' => 'Статус',
	'Class:Action/Attribute:status+' => '',
	'Class:Action/Attribute:status/Value:test' => 'Тест',
	'Class:Action/Attribute:status/Value:test+' => 'Тест',
	'Class:Action/Attribute:status/Value:enabled' => 'Включено',
	'Class:Action/Attribute:status/Value:enabled+' => 'Включено',
	'Class:Action/Attribute:status/Value:disabled' => 'Выключено',
	'Class:Action/Attribute:status/Value:disabled+' => 'Выключено',
	'Class:Action/Attribute:trigger_list' => 'Связанные триггеры',
	'Class:Action/Attribute:trigger_list+' => 'Триггеры, которые запускают данное действие',
	'Class:Action/Attribute:finalclass' => 'Тип',
	'Class:Action/Attribute:finalclass+' => '',
));

//
// Class: ActionNotification
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:ActionNotification' => 'Уведомление',
	'Class:ActionNotification+' => 'Уведомление',
));

//
// Class: ActionEmail
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:ActionEmail' => 'Уведомление по email',
	'Class:ActionEmail+' => '',
	'Class:ActionEmail/Attribute:test_recipient' => 'Тестовый получатель',
	'Class:ActionEmail/Attribute:test_recipient+' => 'Получатель, если уведомление в статусе "Тест"',
	'Class:ActionEmail/Attribute:from' => 'От',
	'Class:ActionEmail/Attribute:from+' => 'Будет отправлено в заголовке email',
	'Class:ActionEmail/Attribute:reply_to' => 'Ответить на',
	'Class:ActionEmail/Attribute:reply_to+' => 'Будет отправлено в заголовке email',
	'Class:ActionEmail/Attribute:to' => 'Кому',
	'Class:ActionEmail/Attribute:to+' => 'Получатель email',
	'Class:ActionEmail/Attribute:cc' => 'Копия',
	'Class:ActionEmail/Attribute:cc+' => 'Копия',
	'Class:ActionEmail/Attribute:bcc' => 'Скр. копия',
	'Class:ActionEmail/Attribute:bcc+' => 'Скрытая копия',
	'Class:ActionEmail/Attribute:subject' => 'Тема',
	'Class:ActionEmail/Attribute:subject+' => 'Заголовок письма',
	'Class:ActionEmail/Attribute:body' => 'Тело',
	'Class:ActionEmail/Attribute:body+' => 'Содержимое письма',
	'Class:ActionEmail/Attribute:importance' => 'Важность',
	'Class:ActionEmail/Attribute:importance+' => 'Флаг важности',
	'Class:ActionEmail/Attribute:importance/Value:low' => 'Низкая',
	'Class:ActionEmail/Attribute:importance/Value:low+' => 'Низкая',
	'Class:ActionEmail/Attribute:importance/Value:normal' => 'Нормальная',
	'Class:ActionEmail/Attribute:importance/Value:normal+' => 'Нормальная',
	'Class:ActionEmail/Attribute:importance/Value:high' => 'Высокая',
	'Class:ActionEmail/Attribute:importance/Value:high+' => 'Высокая',
));

//
// Class: Trigger
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Trigger' => 'Триггер',
	'Class:Trigger+' => 'Пользовательский обработчик событий',
	'Class:Trigger/Attribute:description' => 'Описание',
	'Class:Trigger/Attribute:description+' => 'Описание триггера',
	'Class:Trigger/Attribute:action_list' => 'Действия триггера',
	'Class:Trigger/Attribute:action_list+' => 'Действия, выполняемые при срабатывании триггера',
	'Class:Trigger/Attribute:finalclass' => 'Тип',
	'Class:Trigger/Attribute:finalclass+' => '',
));

//
// Class: TriggerOnObject
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:TriggerOnObject' => 'Триггер (на класс объекта)',
	'Class:TriggerOnObject+' => 'Триггер на события объектов данного класса',
	'Class:TriggerOnObject/Attribute:target_class' => 'Целевой класс',
	'Class:TriggerOnObject/Attribute:target_class+' => 'Класс объектов, для которых будет срабатывать данный триггер',
	'Class:TriggerOnObject/Attribute:filter' => 'Фильтр OQL',
	'Class:TriggerOnObject/Attribute:filter+' => 'Позволяет ограничить список объектов, для которых будет срабатывать триггер',
	'TriggerOnObject:WrongFilterQuery' => 'Направильный запрос фильтра: %1$s',
	'TriggerOnObject:WrongFilterClass' => 'Запрос фильтра должен возвращать объекты класса "%1$s"',
));

//
// Class: TriggerOnPortalUpdate
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:TriggerOnPortalUpdate' => 'Триггер (обновление из портала)',
	'Class:TriggerOnPortalUpdate+' => 'Триггер на обновление объекта пользователем портала',
));

//
// Class: TriggerOnStateChange
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:TriggerOnStateChange' => 'Триггер (изменение статуса)',
	'Class:TriggerOnStateChange+' => 'Триггер на изменение статуса объекта',
	'Class:TriggerOnStateChange/Attribute:state' => 'Статус',
	'Class:TriggerOnStateChange/Attribute:state+' => 'Код статуса объекта, например \'resolved\'',
));

//
// Class: TriggerOnStateEnter
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:TriggerOnStateEnter' => 'Триггер (на вход в статус)',
	'Class:TriggerOnStateEnter+' => 'Триггер на вход объекта в статус',
));

//
// Class: TriggerOnStateLeave
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:TriggerOnStateLeave' => 'Триггер (на выход из статуса)',
	'Class:TriggerOnStateLeave+' => 'Триггер на выход объекта из статуса',
));

//
// Class: TriggerOnObjectCreate
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:TriggerOnObjectCreate' => 'Триггер (на создание объекта)',
	'Class:TriggerOnObjectCreate+' => 'Триггер на создание объекта данного или дочернего класса',
));

//
// Class: TriggerOnThresholdReached
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:TriggerOnThresholdReached' => 'Триггер (на пороговое значение)',
	'Class:TriggerOnThresholdReached+' => 'Триггер на достижение секундомером порогового значения (TTO, TTR)',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code' => 'Секундомер',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code+' => 'По умолчанию для Инцидентов и Запросов доступны \'ttr\' и \'tto\'',
	'Class:TriggerOnThresholdReached/Attribute:threshold_index' => 'Порог',
	'Class:TriggerOnThresholdReached/Attribute:threshold_index+' => 'Пороговое значние секундомера в %, по умолчанию \'75\' и \'100\'',
));

//
// Class: lnkTriggerAction
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkTriggerAction' => 'Связь Триггер/Действие',
	'Class:lnkTriggerAction+' => 'Связь между триггером и действиями',
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

    'Class:SynchroDataSource' => 'Источник синхронизации данных',
    'Class:SynchroDataSource/Attribute:name' => 'Название',
    'Class:SynchroDataSource/Attribute:name+' => 'Название',
    'Class:SynchroDataSource/Attribute:description' => 'Описание',
    'Class:SynchroDataSource/Attribute:status' => 'Статус',
    'Class:SynchroDataSource/Attribute:status/Value:implementation' => 'Внедрение',
    'Class:SynchroDataSource/Attribute:status/Value:obsolete' => 'Устаревшее',
    'Class:SynchroDataSource/Attribute:status/Value:production' => 'Эксплуатация',
    'Class:SynchroDataSource/Attribute:scope_class' => 'Целевой класс',
    'Class:SynchroDataSource/Attribute:scope_restriction' => 'Объем ограничений', // не используется пока
    'Class:SynchroDataSource/Attribute:user_id' => 'Пользователь',
    'Class:SynchroDataSource/Attribute:user_id+' => 'Только этот пользователь (и администраторы) смогут выполнять эту синхронизацию',
    'Class:SynchroDataSource/Attribute:notify_contact_id' => 'Контакт для уведомления',
    'Class:SynchroDataSource/Attribute:notify_contact_id+' => 'Контакт для уведомления в случае ошибки',
    'Class:SynchroDataSource/Attribute:url_icon' => 'Иконка (ссылка)',
    'Class:SynchroDataSource/Attribute:url_icon+' => 'Гиперссылка на иконку приложения-источника данных для отображения на страницах синхронизованных объектов',
    'Class:SynchroDataSource/Attribute:url_application' => 'Приложение (ссылка)',
    'Class:SynchroDataSource/Attribute:url_application+' => 'Гиперссылка на объект в приложение-источнике данных. Возможные шаблоны: $this->attribute$ и $replica->primary_key$',
    'Class:SynchroDataSource/Attribute:database_table_name' => 'Таблица данных',
    'Class:SynchroDataSource/Attribute:database_table_name+' => 'Имя таблицы для хранения данных синхронизации. Если оставить поле пустым, будет назначено имя по умолчанию.',
    'Class:SynchroDataSource/Attribute:attribute_list' => 'Атрибуты',

    'Class:SynchroDataSource/Attribute:reconciliation_policy' => 'Политика сопоставления',
    'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_attributes' => 'Использовать атрибуты',
    'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_primary_key' => 'Использовать primary_key значение',
    'Class:SynchroDataSource/Attribute:action_on_zero' => 'Действие при нуле',
    'Class:SynchroDataSource/Attribute:action_on_zero+' => 'Действие, если объект не найдет',
    'Class:SynchroDataSource/Attribute:action_on_zero/Value:create' => 'Создать',
    'Class:SynchroDataSource/Attribute:action_on_zero/Value:error' => 'Ошибка',
    'Class:SynchroDataSource/Attribute:action_on_one' => 'Действие при единице',
    'Class:SynchroDataSource/Attribute:action_on_one+' => 'Действие, если найден только один объект',
    'Class:SynchroDataSource/Attribute:action_on_one/Value:error' => 'Ошибка',
    'Class:SynchroDataSource/Attribute:action_on_one/Value:update' => 'Обновить',
    'Class:SynchroDataSource/Attribute:action_on_multiple' => 'Действие при множестве',
    'Class:SynchroDataSource/Attribute:action_on_multiple+' => 'Действие, если найдено несколько объектов',
    'Class:SynchroDataSource/Attribute:action_on_multiple/Value:create' => 'Создать',
    'Class:SynchroDataSource/Attribute:action_on_multiple/Value:error' => 'Ошибка',
    'Class:SynchroDataSource/Attribute:action_on_multiple/Value:take_first' => 'Использовать первый (случайно)',

    'Class:SynchroDataSource/Attribute:user_delete_policy' => 'Авторизованные пользователи',
    'Class:SynchroDataSource/Attribute:user_delete_policy+' => 'Кому разрешено удаление синхронизируемых объектов',
    'Class:SynchroDataSource/Attribute:user_delete_policy/Value:administrators' => 'Только администраторы',
    'Class:SynchroDataSource/Attribute:user_delete_policy/Value:everybody' => 'Пользователи с правами на удаление',
    'Class:SynchroDataSource/Attribute:user_delete_policy/Value:nobody' => 'Никто',
    'Class:SynchroDataSource/Attribute:delete_policy' => 'Устаревшие объекты',
    'Class:SynchroDataSource/Attribute:delete_policy+' => 'Как обрабатывать устаревшие объекты',
    'Class:SynchroDataSource/Attribute:delete_policy/Value:delete' => 'Удалить',
    'Class:SynchroDataSource/Attribute:delete_policy/Value:ignore' => 'Игнорировать',
    'Class:SynchroDataSource/Attribute:delete_policy/Value:update' => 'Обновить',
    'Class:SynchroDataSource/Attribute:delete_policy/Value:update_then_delete' => 'Обновить, затем удалить',
    'Class:SynchroDataSource/Attribute:delete_policy_update' => 'Обновляемые атрибуты',
    'Class:SynchroDataSource/Attribute:delete_policy_update+' => 'Формат: field_name:value; ... Пример: status:inactive',
    'Class:SynchroDataSource/Attribute:delete_policy_retention' => 'Срок хранения',
    'Class:SynchroDataSource/Attribute:delete_policy_retention+' => 'Сколько времени хранятся устаревшие объекты, прежде чем будут удалены',
	'Class:SynchroDataSource/Attribute:full_load_periodicity' => 'Интервал устаревания',
	'Class:SynchroDataSource/Attribute:full_load_periodicity+' => 'Объект будет считаться устаревшим, если данные о нем в таблице синхронизации не обновлялись в течение этого интервала.',
    'Class:SynchroDataSource/Error:AtLeastOneReconciliationKeyMustBeSpecified' => 'Как минимум один атрибут должен быть выбран для поиска и сопоставления объектов, либо используйте политику сопоставления по primary_key.',
    'Class:SynchroDataSource/Error:DeleteRetentionDurationMustBeSpecified' => 'Срок хранения должен быть указан, поскольку объекты должны быть удалены после того, помечены как устаревшие.',
    'Class:SynchroDataSource/Error:DeletePolicyUpdateMustBeSpecified' => 'Устаревшие объекты должны быть обновлены, но способ обновления не указан.',
    'Class:SynchroDataSource/Error:DataTableAlreadyExists' => 'Таблица %1$s уже существует в базе данных. Пожалуйста, используйте другое имя для таблицы данных из этого источника.',
    'SynchroDataSource:Description' => 'Описание',
	'SynchroDataSource:Reconciliation' => 'Поиск и сопоставление',
	'SynchroDataSource:Deletion' => 'Устаревание и удаление',
	'SynchroDataSource:Status' => 'Статус',
	'SynchroDataSource:Information' => 'Инфо~~',
	'SynchroDataSource:Definition' => 'Definition~~',

	'Core:SynchroAttributes' => 'Атрибуты',
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
	'Menu:DataSources' => 'Источники данных',
	'Menu:DataSources+' => 'Источники синхронизируемых данных',
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
	'Core:Synchro:History' => 'История синхронизаций',
	'Core:SynchroLogTitle' => '%1$s - %2$s~~',
	'Core:Synchro:Nb_Replica' => 'Replica processed: %1$s~~',
	'Core:Synchro:Nb_Class:Objects' => '%1$s: %2$s~~',

	'Core:SynchroReplica:PublicData' => 'Public Data~~',
	'Core:SynchroReplica:PrivateDetails' => 'Private Details~~',
	'Core:SynchroReplica:BackToDataSource' => 'Go Back to the Synchro Data Source: %1$s~~',
	'Core:SynchroReplica:ListOfReplicas' => 'List of Replica~~',
	'Core:SynchroAttExtKey:ReconciliationById' => 'id (Primary Key)~~',
	'Core:SynchroAtt:attcode' => 'Атрибут',
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

	'Class:SynchroAttribute' => 'Синх.характеристики~~',
	'Class:SynchroAttribute/Attribute:sync_source_id' => 'Синхронизация данных',
	'Class:SynchroAttribute/Attribute:attcode' => 'Код атрибута',
	'Class:SynchroAttribute/Attribute:update' => 'Обновить',
	'Class:SynchroAttribute/Attribute:reconcile' => 'Согласование',
	'Class:SynchroAttribute/Attribute:update_policy' => 'Обновить политику',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_locked' => 'Заблокирован',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_unlocked' => 'Разблокирован',
	'Class:SynchroAttribute/Attribute:update_policy/Value:write_if_empty' => 'Инициализация если пусто',
	'Class:SynchroAttribute/Attribute:finalclass' => 'Класс~~',
	'Class:SynchroAttExtKey' => 'Synchro Attribute (ExtKey)~~',
	'Class:SynchroAttExtKey/Attribute:reconciliation_attcode' => 'Атрибут согласования',
	'Class:SynchroAttLinkSet' => 'Synchro Attribute (Linkset)~~',
	'Class:SynchroAttLinkSet/Attribute:row_separator' => 'Разделитель строк',
	'Class:SynchroAttLinkSet/Attribute:attribute_separator' => 'Разделитель атрибутов',
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
	'Class:appUserPreferences' => 'Предпочтения пользователя',
	'Class:appUserPreferences/Attribute:userid' => 'Пользователь',
	'Class:appUserPreferences/Attribute:preferences' => 'Предпочтения',
	'Core:ExecProcess:Code1' => 'Неверная команда или команда завершена с ошибкой (возможно, неверное имя скрипта)',
	'Core:ExecProcess:Code255' => 'Ошибка PHP (parsing, or runtime)~~',
));

//
// Attribute Duration
//
Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Core:Duration_Seconds'	=> '%1$d с',
	'Core:Duration_Minutes_Seconds'	=>'%1$d мин %2$d с',
	'Core:Duration_Hours_Minutes_Seconds' => '%1$d ч %2$d мин %3$d с',
	'Core:Duration_Days_Hours_Minutes_Seconds' => '%1$s д %2$d ч %3$d мин %4$d с',

		// Explain working time computing
	'Core:ExplainWTC:ElapsedTime' => 'Time elapsed (stored as "%1$s")',
	'Core:ExplainWTC:StopWatch-TimeSpent' => 'Time spent for "%1$s"',
	'Core:ExplainWTC:StopWatch-Deadline' => 'Deadline for "%1$s" at %2$d%%',

	// Bulk export
	'Core:BulkExport:MissingParameter_Param' => 'Missing parameter "%1$s"',
	'Core:BulkExport:InvalidParameter_Query' => 'Invalid value for the parameter "query". There is no Query Phrasebook corresponding to the id: "%1$s".',
	'Core:BulkExport:ExportFormatPrompt' => 'Export format:',
	'Core:BulkExportOf_Class' => '%1$s Export',
	'Core:BulkExport:ClickHereToDownload_FileName' => 'Click here to download %1$s',
	'Core:BulkExport:ExportResult' => 'Result of the export:',
	'Core:BulkExport:RetrievingData' => 'Retrieving data...',
	'Core:BulkExport:HTMLFormat' => 'Web Page (*.html)',
	'Core:BulkExport:CSVFormat' => 'Comma Separated Values (*.csv)',
	'Core:BulkExport:XLSXFormat' => 'Excel 2007 or newer (*.xlsx)',
	'Core:BulkExport:PDFFormat' => 'PDF Document (*.pdf)',
	'Core:BulkExport:DragAndDropHelp' => 'Drag and drop the columns\' headers to arrange the columns. Preview of %1$s lines. Total number of lines to export: %2$s.',
	'Core:BulkExport:EmptyPreview' => 'Select the columns to be exported from the list above',
	'Core:BulkExport:ColumnsOrder' => 'Columns order',
	'Core:BulkExport:AvailableColumnsFrom_Class' => 'Available columns from %1$s',
	'Core:BulkExport:NoFieldSelected' => 'Select at least one column to be exported',
	'Core:BulkExport:CheckAll' => 'Check All',
	'Core:BulkExport:UncheckAll' => 'Uncheck All',
	'Core:BulkExport:ExportCancelledByUser' => 'Export cancelled by the user',
	'Core:BulkExport:CSVOptions' => 'CSV Options',
	'Core:BulkExport:CSVLocalization' => 'Localization',
	'Core:BulkExport:PDFOptions' => 'PDF Options',
	'Core:BulkExport:PDFPageFormat' => 'Page Format',
	'Core:BulkExport:PDFPageSize' => 'Page Size:',
	'Core:BulkExport:PageSize-A4' => 'A4',
	'Core:BulkExport:PageSize-A3' => 'A3',
	'Core:BulkExport:PageSize-Letter' => 'Letter',
	'Core:BulkExport:PDFPageOrientation' => 'Page Orientation:',
	'Core:BulkExport:PageOrientation-L' => 'Landscape',
	'Core:BulkExport:PageOrientation-P' => 'Portrait',
	'Core:BulkExport:XMLFormat' => 'XML file (*.xml)',
	'Core:BulkExport:XMLOptions' => 'XML Options',
	'Core:BulkExport:SpreadsheetFormat' => 'Spreadsheet HTML format (*.html)',
	'Core:BulkExport:SpreadsheetOptions' => 'Spreadsheet Options',
	'Core:BulkExport:OptionNoLocalize' => 'Do not localize the values (for Enumerated fields)',
	'Core:BulkExport:OptionLinkSets' => 'Include linked objects',
	'Core:BulkExport:OptionFormattedText' => 'Preserve text formatting',
	'Core:BulkExport:ScopeDefinition' => 'Definition of the objects to export',
	'Core:BulkExportLabelOQLExpression' => 'OQL Query:',
	'Core:BulkExportLabelPhrasebookEntry' => 'Query Phrasebook Entry:',
	'Core:BulkExportMessageEmptyOQL' => 'Please enter a valid OQL query.',
	'Core:BulkExportMessageEmptyPhrasebookEntry' => 'Please select a valid phrasebook entry.',
	'Core:BulkExportQueryPlaceholder' => 'Type an OQL query here...',
	'Core:BulkExportCanRunNonInteractive' => 'Click here to run the export in non-interactive mode.',
	'Core:BulkExportLegacyExport' => 'Click here to access the legacy export.',
	'Core:BulkExport:XLSXOptions' => 'Excel Options',
	'Core:BulkExport:TextFormat' => 'Text fields containing some HTML markup',
	'Core:BulkExport:DateTimeFormat' => 'Date and Time format',
	'Core:BulkExport:DateTimeFormatDefault_Example' => 'Default format (%1$s), e.g. %2$s',
	'Core:BulkExport:DateTimeFormatCustom_Format' => 'Custom format: %1$s',

	'Core:DateTime:Placeholder_d' => 'DD', // Day of the month: 2 digits (with leading zero)
	'Core:DateTime:Placeholder_j' => 'D', // Day of the month: 1 or 2 digits (without leading zero)
	'Core:DateTime:Placeholder_m' => 'MM', // Month on 2 digits i.e. 01-12
	'Core:DateTime:Placeholder_n' => 'M', // Month on 1 or 2 digits 1-12
	'Core:DateTime:Placeholder_Y' => 'YYYY', // Year on 4 digits
	'Core:DateTime:Placeholder_y' => 'YY', // Year on 2 digits
	'Core:DateTime:Placeholder_H' => 'hh', // Hour 00..23
	'Core:DateTime:Placeholder_h' => 'h', // Hour 01..12
	'Core:DateTime:Placeholder_G' => 'hh', // Hour 0..23
	'Core:DateTime:Placeholder_g' => 'h', // Hour 1..12
	'Core:DateTime:Placeholder_a' => 'am/pm', // am/pm (lowercase)
	'Core:DateTime:Placeholder_A' => 'AM/PM', // AM/PM (uppercase)
	'Core:DateTime:Placeholder_i' => 'mm', // minutes, 2 digits: 00..59
	'Core:DateTime:Placeholder_s' => 'ss', // seconds, 2 digits 00..59
	'Core:Validator:Default' => 'Неправильный формат',
	'Core:Validator:Mandatory' => 'Пожалуйста, заполните это поле',
	'Core:Validator:MustBeInteger' => 'Должно быть целым числом',
	'Core:Validator:MustSelectOne' => 'Пожалуйста, выберите значение',
));

