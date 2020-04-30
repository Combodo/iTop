<?php
/**
 * Локализация интерфейса Combodo iTop подготовлена сообществом iTop по-русски http://community.itop-itsm.ru.
 *
 * @author      Vladimir Kunin <v.b.kunin@gmail.com>
 * @link        http://community.itop-itsm.ru  iTop Russian Community
 * @link        https://github.com/itop-itsm-ru/itop-rus
 * @license     http://opensource.org/licenses/AGPL-3.0
 *
 */
Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Core:DeletedObjectLabel' => '%1ы (удален)',
	'Core:DeletedObjectTip' => 'Объект был удален %1$s (%2$s)',

	'Core:UnknownObjectLabel' => 'Объект не найден (class: %1$s, id: %2$d)',
	'Core:UnknownObjectTip' => 'Объект не удается найти. Возможно, он был удален некоторое время назад, и журнал с тех пор был очищен.',

	'Core:UniquenessDefaultError' => 'Ошибка правила уникальности \'%1$s\'',

	'Core:AttributeLinkedSet' => 'Массив объектов (1-n)',
	'Core:AttributeLinkedSet+' => 'Список объектов заданного класса, указывающих на текущий объект',

	'Core:AttributeDashboard' => 'Дашборд',
	'Core:AttributeDashboard+' => '',

	'Core:AttributePhoneNumber' => 'Номер телефона',
	'Core:AttributePhoneNumber+' => '',

	'Core:AttributeObsolescenceDate' => 'Дата устаревания',
	'Core:AttributeObsolescenceDate+' => '',

	'Core:AttributeTagSet' => 'Список тегов',
	'Core:AttributeTagSet+' => '',
	'Core:AttributeSet:placeholder' => 'нажмите, чтобы добавить',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromClass' => '%1$s (%2$s)~~',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromOneChildClass' => '%1$s (%2$s from %3$s)~~',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromSeveralChildClasses' => '%1$s (%2$s from child classes)~~',

	'Core:AttributeCaseLog' => 'Журнал',
	'Core:AttributeCaseLog+' => '',

	'Core:AttributeMetaEnum' => 'Вычисляемый enum',
	'Core:AttributeMetaEnum+' => '',

	'Core:AttributeLinkedSetIndirect' => 'Массив объектов (n-n)',
	'Core:AttributeLinkedSetIndirect+' => 'Список объектов заданного класса, связанные с текущим объектом через промежуточный класс',

	'Core:AttributeInteger' => 'Целое',
	'Core:AttributeInteger+' => 'Целочисленное значение (может быть отрицательным)',

	'Core:AttributeDecimal' => 'Десятичное',
	'Core:AttributeDecimal+' => 'Десятичное значение (может быть отрицательным)',

	'Core:AttributeBoolean' => 'Логическое',
	'Core:AttributeBoolean+' => 'Да/Нет',
	'Core:AttributeBoolean/Value:null' => '',
	'Core:AttributeBoolean/Value:yes' => 'Да',
	'Core:AttributeBoolean/Value:no' => 'Нет',

	'Core:AttributeArchiveFlag' => 'Архивный флаг',
	'Core:AttributeArchiveFlag/Value:yes' => 'Да',
	'Core:AttributeArchiveFlag/Value:yes+' => 'Этот объект виден только в режиме архива',
	'Core:AttributeArchiveFlag/Value:no' => 'Нет',
	'Core:AttributeArchiveFlag/Label' => 'Архивный',
	'Core:AttributeArchiveFlag/Label+' => '',
	'Core:AttributeArchiveDate/Label' => 'Дата архивирования',
	'Core:AttributeArchiveDate/Label+' => '',

	'Core:AttributeObsolescenceFlag' => 'Устаревший флаг',
	'Core:AttributeObsolescenceFlag/Value:yes' => 'Да',
	'Core:AttributeObsolescenceFlag/Value:yes+' => 'Этот объект исключен из анализа влияния и скрыт из результатов поиска',
	'Core:AttributeObsolescenceFlag/Value:no' => 'Нет',
	'Core:AttributeObsolescenceFlag/Label' => 'Устаревший',
	'Core:AttributeObsolescenceFlag/Label+' => 'Вычисляется динамически на основании значений других атрибутов',
	'Core:AttributeObsolescenceDate/Label' => 'Дата устаревания',
	'Core:AttributeObsolescenceDate/Label+' => 'Приблизительная дата, с которой объект считается устаревшим',

	'Core:AttributeString' => 'Строка',
	'Core:AttributeString+' => 'Текстовая строка',

	'Core:AttributeClass' => 'Класс',
	'Core:AttributeClass+' => 'Класс объекта',

	'Core:AttributeApplicationLanguage' => 'Язык пользователя',
	'Core:AttributeApplicationLanguage+' => 'Язык и страна (EN US)',

	'Core:AttributeFinalClass' => 'Класс (авто)',
	'Core:AttributeFinalClass+' => 'Реальный класс объекта (автоматически создаваемый ядром)',

	'Core:AttributePassword' => 'Пароль',
	'Core:AttributePassword+' => 'Пароль внешнего устройства',

	'Core:AttributeEncryptedString' => 'Зашифрованная строка',
	'Core:AttributeEncryptedString+' => 'Строка, зашифрованная локальным ключом',
	'Core:AttributeEncryptUnknownLibrary' => 'Заданная библиотека шифрования (%1$s) неизвестна',
	'Core:AttributeEncryptFailedToDecrypt' => '** ошибка расшифровки **',

	'Core:AttributeText' => 'Текст',
	'Core:AttributeText+' => 'Многострочный текст',

	'Core:AttributeHTML' => 'HTML',
	'Core:AttributeHTML+' => 'HTML',

	'Core:AttributeEmailAddress' => 'Email',
	'Core:AttributeEmailAddress+' => 'Email адрес',

	'Core:AttributeIPAddress' => 'IP адрес',
	'Core:AttributeIPAddress+' => 'IP адрес',

	'Core:AttributeOQL' => 'OQL',
	'Core:AttributeOQL+' => 'Выражение языка запроса объекта (Object Query Language)',

	'Core:AttributeEnum' => 'Enum',
	'Core:AttributeEnum+' => 'Список предопределенных тестовых строк',

	'Core:AttributeTemplateString' => 'Шаблон строки',
	'Core:AttributeTemplateString+' => 'Строка, содержащая плейсхолдеры',

	'Core:AttributeTemplateText' => 'Шаблон текста',
	'Core:AttributeTemplateText+' => 'Текст, содержащий плейсхолдеры',

	'Core:AttributeTemplateHTML' => 'Шаблон HTML',
	'Core:AttributeTemplateHTML+' => 'HTML, содержащий плейсхолдеры',

	'Core:AttributeDateTime' => 'Дата/время',
	'Core:AttributeDateTime+' => 'Дата и время (гггг-мм-дд чч:мм:сс)',
	'Core:AttributeDateTime?SmartSearch' => '
<p>
	Формат даты:<br/>
	<b>гггг-мм-дд чч:мм:сс</b><br/>
	Пример: 2017-11-27 19:17:00
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
	'Core:AttributeDate+' => 'Дата (гггг-мм-дд)',
	'Core:AttributeDate?SmartSearch' => '
<p>
	Формат даты:<br/>
	<b>гггг-мм-дд</b><br/>
	Пример: 2017-11-27
</p>
<p>
Операторы:<br/>
	<b>&gt;</b><em>дата</em><br/>
	<b>&lt;</b><em>дата</em><br/>
	<b>[</b><em>дата</em>,<em>дата</em><b>]</b>
</p>',

	'Core:AttributeDeadline' => 'Крайний срок',
	'Core:AttributeDeadline+' => 'Дата, отображаемая относительно текущего времени',

	'Core:AttributeExternalKey' => 'Внешний ключ',
	'Core:AttributeExternalKey+' => 'Внешний ключ',

	'Core:AttributeHierarchicalKey' => 'Иерархический ключ',
	'Core:AttributeHierarchicalKey+' => 'Внешний ключ к родителю',

	'Core:AttributeExternalField' => 'Внешнее поле',
	'Core:AttributeExternalField+' => 'Поле, сопоставленное с внешним ключом',

	'Core:AttributeURL' => 'URL',
	'Core:AttributeURL+' => 'Абсолютный или относительный URL в виде текстовой строки',

	'Core:AttributeBlob' => 'Blob',
	'Core:AttributeBlob+' => 'Любой двоичный контент (документ)',

	'Core:AttributeOneWayPassword' => 'Хэшированный пароль',
	'Core:AttributeOneWayPassword+' => 'Зашифрованный (хэшированный) пароль',

	'Core:AttributeTable' => 'Таблица',
	'Core:AttributeTable+' => 'Индексированный массив с двумя измерениями',

	'Core:AttributePropertySet' => 'Свойства',
	'Core:AttributePropertySet+' => 'Список нетипизированных свойств (имя и значение)',

	'Core:AttributeFriendlyName' => 'Полное название',
	'Core:AttributeFriendlyName+' => 'Атрибут создается автоматически; полное название вычисляется из нескольких атрибутов',

	'Core:FriendlyName-Label' => 'Полное название',
	'Core:FriendlyName-Description' => 'Полное название',

	'Core:AttributeTag' => 'Тег',
	'Core:AttributeTag+' => 'Тег',
	
	'Core:Context=REST/JSON' => 'REST',
	'Core:Context=Synchro' => 'Synchro',
	'Core:Context=Setup' => 'Setup',
	'Core:Context=GUI:Console' => 'Console',
	'Core:Context=CRON' => 'cron',
	'Core:Context=GUI:Portal' => 'Portal',
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
	'Class:CMDBChangeOpSetAttributeBlob' => 'Изменение данных',
	'Class:CMDBChangeOpSetAttributeBlob+' => 'Отслеживание изменения данных',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata' => 'Предыдущие данные',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata+' => 'Предыдущее содержимое атрибута',
));

//
// Class: CMDBChangeOpSetAttributeText
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:CMDBChangeOpSetAttributeText' => 'Изменение текста',
	'Class:CMDBChangeOpSetAttributeText+' => 'Отслеживание изменения текста',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata' => 'Предыдущие данные',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata+' => 'Предыдущее содержимое атрибута',
));

//
// Class: Event
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Event' => 'Событие',
	'Class:Event+' => 'Внутренние событие приложения',
	'Class:Event/Attribute:message' => 'Сообщение',
	'Class:Event/Attribute:message+' => 'Краткое описание события',
	'Class:Event/Attribute:date' => 'Дата',
	'Class:Event/Attribute:date+' => 'Дата и время регистрации события',
	'Class:Event/Attribute:userinfo' => 'Пользователь',
	'Class:Event/Attribute:userinfo+' => 'Пользователь, действия которого вызвали это событие',
	'Class:Event/Attribute:finalclass' => 'Тип',
	'Class:Event/Attribute:finalclass+' => '',
));

//
// Class: EventNotification
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:EventNotification' => 'Уведомление',
	'Class:EventNotification+' => 'Отслеживание отправленных уведомлений',
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
	'Class:EventNotificationEmail+' => 'Отслеживание уведомлений по email',
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
	'Class:EventIssue' => 'Ошибка',
	'Class:EventIssue+' => 'Отслеживание ошибок (warning, error, др.)',
	'Class:EventIssue/Attribute:issue' => 'Ошибка',
	'Class:EventIssue/Attribute:issue+' => 'Что произошло',
	'Class:EventIssue/Attribute:impact' => 'Воздействие',
	'Class:EventIssue/Attribute:impact+' => 'Последствия',
	'Class:EventIssue/Attribute:page' => 'Страница',
	'Class:EventIssue/Attribute:page+' => 'Точка входа HTTP',
	'Class:EventIssue/Attribute:arguments_post' => 'Аргументы POST',
	'Class:EventIssue/Attribute:arguments_post+' => 'Аргументы HTTP POST',
	'Class:EventIssue/Attribute:arguments_get' => 'Аргументы GET',
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
	'Class:EventWebService' => 'События Web-сервиса',
	'Class:EventWebService+' => 'Trace of an web service call~~',
	'Class:EventWebService/Attribute:verb' => 'Verb~~',
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
	'Class:Trigger/Attribute:context' => 'Контекст',
	'Class:Trigger/Attribute:context+' => 'Контекст, в котором будет срабатывать триггер',
));

//
// Class: TriggerOnObject
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:TriggerOnObject' => 'Триггер на класс объекта',
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
	'Class:TriggerOnPortalUpdate' => 'Триггер на обновление из портала',
	'Class:TriggerOnPortalUpdate+' => 'Триггер на обновление объекта пользователем портала',
));

//
// Class: TriggerOnStateChange
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:TriggerOnStateChange' => 'Триггер на изменение статуса',
	'Class:TriggerOnStateChange+' => 'Триггер на изменение статуса объекта',
	'Class:TriggerOnStateChange/Attribute:state' => 'Статус',
	'Class:TriggerOnStateChange/Attribute:state+' => 'Код статуса объекта, например \'resolved\'',
));

//
// Class: TriggerOnStateEnter
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:TriggerOnStateEnter' => 'Триггер на вход в статус',
	'Class:TriggerOnStateEnter+' => 'Триггер на вход объекта в статус',
));

//
// Class: TriggerOnStateLeave
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:TriggerOnStateLeave' => 'Триггер на выход из статуса',
	'Class:TriggerOnStateLeave+' => 'Триггер на выход объекта из статуса',
));

//
// Class: TriggerOnObjectCreate
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:TriggerOnObjectCreate' => 'Триггер на создание объекта',
	'Class:TriggerOnObjectCreate+' => 'Триггер на создание объекта данного или дочернего класса',
));

//
// Class: TriggerOnObjectDelete
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:TriggerOnObjectDelete' => 'Триггер на удаление объекта',
	'Class:TriggerOnObjectDelete+' => 'Триггер на удаление объекта данного или дочернего класса',
));

//
// Class: TriggerOnObjectUpdate
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:TriggerOnObjectUpdate' => 'Триггер на обновление объекта',
	'Class:TriggerOnObjectUpdate+' => 'Триггер на обновление объекта данного или дочернего класса',
	'Class:TriggerOnObjectUpdate/Attribute:target_attcodes' => 'Отслеживаемые поля',
	'Class:TriggerOnObjectUpdate/Attribute:target_attcodes+' => 'Поля объекта, при обновлении которых сработает триггер',
));

//
// Class: TriggerOnThresholdReached
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:TriggerOnThresholdReached' => 'Триггер на пороговое значение',
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
	'Class:SynchroDataSource/Attribute:name' => 'Название',
	'Class:SynchroDataSource/Attribute:name+' => 'Название',
	'Class:SynchroDataSource/Attribute:description' => 'Описание',
	'Class:SynchroDataSource/Attribute:status' => 'Статус',
	'Class:SynchroDataSource/Attribute:scope_class' => 'Целевой класс',
	'Class:SynchroDataSource/Attribute:user_id' => 'Пользователь',
	'Class:SynchroDataSource/Attribute:notify_contact_id' => 'Контакт для уведомления',
	'Class:SynchroDataSource/Attribute:notify_contact_id+' => 'Контакт для уведомления в случае ошибки',
	'Class:SynchroDataSource/Attribute:url_icon' => 'Иконка (ссылка)',
	'Class:SynchroDataSource/Attribute:url_icon+' => 'Гиперссылка на иконку приложения-источника данных для отображения на страницах синхронизованных объектов',
	'Class:SynchroDataSource/Attribute:url_application' => 'Приложение (ссылка)',
	'Class:SynchroDataSource/Attribute:url_application+' => 'Гиперссылка на объект в приложение-источнике данных. Возможные шаблоны: $this->attribute$ и $replica->primary_key$',
	'Class:SynchroDataSource/Attribute:reconciliation_policy' => 'Политика сопоставления',
	'Class:SynchroDataSource/Attribute:full_load_periodicity' => 'Интервал устаревания',
	'Class:SynchroDataSource/Attribute:full_load_periodicity+' => 'Объект будет считаться устаревшим, если данные о нем в таблице синхронизации не обновлялись в течение этого интервала.',
	'Class:SynchroDataSource/Attribute:action_on_zero' => 'Действие при нуле',
	'Class:SynchroDataSource/Attribute:action_on_zero+' => 'Действие, если объект не найдет',
	'Class:SynchroDataSource/Attribute:action_on_one' => 'Действие при единице',
	'Class:SynchroDataSource/Attribute:action_on_one+' => 'Действие, если найден только один объект',
	'Class:SynchroDataSource/Attribute:action_on_multiple' => 'Действие при множестве',
	'Class:SynchroDataSource/Attribute:action_on_multiple+' => 'Действие, если найдено несколько объектов',
	'Class:SynchroDataSource/Attribute:user_delete_policy' => 'Авторизованные пользователи',
	'Class:SynchroDataSource/Attribute:user_delete_policy+' => 'Кому разрешено удаление синхронизируемых объектов',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:never' => 'Nobody~~',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:depends' => 'Administrators only~~',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:always' => 'All allowed users~~',
	'Class:SynchroDataSource/Attribute:delete_policy_update' => 'Обновляемые атрибуты',
	'Class:SynchroDataSource/Attribute:delete_policy_update+' => 'Формат: field_name:value; ... Пример: status:inactive',
	'Class:SynchroDataSource/Attribute:delete_policy_retention' => 'Срок хранения',
	'Class:SynchroDataSource/Attribute:delete_policy_retention+' => 'Сколько времени хранятся устаревшие объекты, прежде чем будут удалены',
	'Class:SynchroDataSource/Attribute:database_table_name' => 'Таблица данных',
	'Class:SynchroDataSource/Attribute:database_table_name+' => 'Имя таблицы для хранения данных синхронизации. Если оставить поле пустым, будет назначено имя по умолчанию.',
	'SynchroDataSource:Description' => 'Описание',
	'SynchroDataSource:Reconciliation' => 'Поиск и сопоставление',
	'SynchroDataSource:Deletion' => 'Устаревание и удаление',
	'SynchroDataSource:Status' => 'Статус',
	'SynchroDataSource:Information' => 'Инфо~~',
	'SynchroDataSource:Definition' => 'Определение~~',
	'Core:SynchroAttributes' => 'Атрибуты',
	'Core:SynchroStatus' => 'Свойства~~',
	'Core:Synchro:ErrorsLabel' => 'Ошибки~~',
	'Core:Synchro:CreatedLabel' => 'Создан~~',
	'Core:Synchro:ModifiedLabel' => 'Изменен~~',
	'Core:Synchro:UnchangedLabel' => 'Неизменен~~',
	'Core:Synchro:ReconciledErrorsLabel' => 'Ошибки~~',
	'Core:Synchro:ReconciledLabel' => 'Согласован~~',
	'Core:Synchro:ReconciledNewLabel' => 'Создан~~',
	'Core:SynchroReconcile:Yes' => 'Да',
	'Core:SynchroReconcile:No' => 'Нет',
	'Core:SynchroUpdate:Yes' => 'Да',
	'Core:SynchroUpdate:No' => 'Нет',
	'Core:Synchro:LastestStatus' => 'Последний статус',
	'Core:Synchro:History' => 'История синхронизаций',
	'Core:Synchro:NeverRun' => 'Синхронизация не запускалась. Логи отсутсвуют.',
	'Core:Synchro:SynchroEndedOn_Date' => 'Синхронизация была закончена в %1$s.',
	'Core:Synchro:SynchroRunningStartedOn_Date' => 'Синхронизация запущена в %1$s, сейчас в процессе...',
	'Menu:DataSources' => 'Синхронизация данных', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataSources+' => 'Синхронизация данных', // Duplicated into itop-welcome-itil (will be removed from here...)
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
	'Core:SynchroLogTitle' => '%1$s - %2$s~~',
	'Core:Synchro:Nb_Replica' => 'Replica processed: %1$s~~',
	'Core:Synchro:Nb_Class:Objects' => '%1$s: %2$s~~',
	'Class:SynchroDataSource/Error:AtLeastOneReconciliationKeyMustBeSpecified' => 'Как минимум один атрибут должен быть выбран для поиска и сопоставления объектов, либо используйте политику сопоставления по primary_key.',
	'Class:SynchroDataSource/Error:DeleteRetentionDurationMustBeSpecified' => 'Срок хранения должен быть указан, поскольку объекты должны быть удалены после того, помечены как устаревшие.',
	'Class:SynchroDataSource/Error:DeletePolicyUpdateMustBeSpecified' => 'Устаревшие объекты должны быть обновлены, но способ обновления не указан.',
	'Class:SynchroDataSource/Error:DataTableAlreadyExists' => 'Таблица %1$s уже существует в базе данных. Пожалуйста, используйте другое имя для таблицы данных из этого источника.',
	'Core:SynchroReplica:PublicData' => 'Public Data~~',
	'Core:SynchroReplica:PrivateDetails' => 'Private Details~~',
	'Core:SynchroReplica:BackToDataSource' => 'Go Back to the Synchro Data Source: %1$s~~',
	'Core:SynchroReplica:ListOfReplicas' => 'List of Replica~~',
	'Core:SynchroAttExtKey:ReconciliationById' => 'id (Primary Key)~~',
	'Core:SynchroAtt:attcode' => 'Атрибут',
	'Core:SynchroAtt:attcode+' => 'Поле объекта',
	'Core:SynchroAtt:reconciliation' => 'Сопоставление ?',
	'Core:SynchroAtt:reconciliation+' => 'Атрибуты, используемые для поиска существуюущего объекта',
	'Core:SynchroAtt:update' => 'Обновление ?',
	'Core:SynchroAtt:update+' => 'Атрибуты, которые будут обновляться при синхронизации',
	'Core:SynchroAtt:update_policy' => 'Политика обновления',
	'Core:SynchroAtt:update_policy+' => 'Поведение обновляемого атрибута',
	'Core:SynchroAtt:reconciliation_attcode' => 'Ключ сопоставления',
	'Core:SynchroAtt:reconciliation_attcode+' => 'Код атрибута для сопоставления с внешним ключом',
	'Core:SyncDataExchangeComment' => '(Синхронизация)',
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
	'Class:SynchroDataSource' => 'Источник синхронизации данных',
	'Class:SynchroDataSource/Attribute:status/Value:implementation' => 'Внедрение',
	'Class:SynchroDataSource/Attribute:status/Value:obsolete' => 'Устаревший',
	'Class:SynchroDataSource/Attribute:status/Value:production' => 'Эксплуатация',
	'Class:SynchroDataSource/Attribute:scope_restriction' => 'Объем ограничений',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_attributes' => 'Использовать атрибуты',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_primary_key' => 'Использовать primary_key значение',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:create' => 'Создать',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:error' => 'Ошибка',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:error' => 'Ошибка',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:update' => 'Обновить',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:create' => 'Создать',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:error' => 'Ошибка',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:take_first' => 'Использовать первый (случайно)',
	'Class:SynchroDataSource/Attribute:delete_policy' => 'Устаревшие объекты',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:delete' => 'Удалить',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:ignore' => 'Игнорировать',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update' => 'Обновить',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update_then_delete' => 'Обновить, затем удалить',
	'Class:SynchroDataSource/Attribute:attribute_list' => 'Атрибуты',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:administrators' => 'Только администраторы',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:everybody' => 'Пользователи с правами на удаление',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:nobody' => 'Никто',
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

	// Attribute Duration
	'Core:Duration_Seconds' => '%1$d с',
	'Core:Duration_Minutes_Seconds' => '%1$d мин %2$d с',
	'Core:Duration_Hours_Minutes_Seconds' => '%1$d ч %2$d мин %3$d с',
	'Core:Duration_Days_Hours_Minutes_Seconds' => '%1$s д %2$d ч %3$d мин %4$d с',

	// Explain working time computing
	'Core:ExplainWTC:ElapsedTime' => 'Time elapsed (stored as "%1$s")',
	'Core:ExplainWTC:StopWatch-TimeSpent' => 'Time spent for "%1$s"',
	'Core:ExplainWTC:StopWatch-Deadline' => 'Deadline for "%1$s" at %2$d%%',

	// Bulk export
	'Core:BulkExport:MissingParameter_Param' => 'Отсутствует параметр "%1$s"',
	'Core:BulkExport:InvalidParameter_Query' => 'Недопустимое значение параметра "query". В Книге запросов отсутствует запись с id: "%1$s".',
	'Core:BulkExport:ExportFormatPrompt' => 'Формат экспорта:',
	'Core:BulkExportOf_Class' => '%1$s Export',
	'Core:BulkExport:ClickHereToDownload_FileName' => 'Нажмите здесь, чтобы скачать %1$s',
	'Core:BulkExport:ExportResult' => 'Результат экспорта:',
	'Core:BulkExport:RetrievingData' => 'Извлечение данных...',
	'Core:BulkExport:HTMLFormat' => 'Web-страница (*.html)',
	'Core:BulkExport:CSVFormat' => 'Текст с разделителями-запятыми (*.csv)',
	'Core:BulkExport:XLSXFormat' => 'Excel 2007 или новее (*.xlsx)',
	'Core:BulkExport:PDFFormat' => 'Документ PDF (*.pdf)',
	'Core:BulkExport:DragAndDropHelp' => 'Перетащите заголовки столбцов, чтобы упорядочить столбцы. Предварительный просмотр %1$s строк. Общее количество строк для экспорта: %2$s.',
	'Core:BulkExport:EmptyPreview' => 'Выберите столбцы для экспорта из списка выше',
	'Core:BulkExport:ColumnsOrder' => 'Порядок столбцов',
	'Core:BulkExport:AvailableColumnsFrom_Class' => 'Доступные столбцы из класса %1$s',
	'Core:BulkExport:NoFieldSelected' => 'Выберите хотя бы один столбец для экспорта',
	'Core:BulkExport:CheckAll' => 'Отметить все',
	'Core:BulkExport:UncheckAll' => 'Снять все',
	'Core:BulkExport:ExportCancelledByUser' => 'Экспорт отменен пользователем',
	'Core:BulkExport:CSVOptions' => 'Параметры CSV',
	'Core:BulkExport:CSVLocalization' => 'Локализация',
	'Core:BulkExport:PDFOptions' => 'Параметры PDF',
	'Core:BulkExport:PDFPageFormat' => 'Формат страницы',
	'Core:BulkExport:PDFPageSize' => 'Размер:',
	'Core:BulkExport:PageSize-A4' => 'A4',
	'Core:BulkExport:PageSize-A3' => 'A3',
	'Core:BulkExport:PageSize-Letter' => 'Письмо',
	'Core:BulkExport:PDFPageOrientation' => 'Ориентация:',
	'Core:BulkExport:PageOrientation-L' => 'Альбомная',
	'Core:BulkExport:PageOrientation-P' => 'Книжная',
	'Core:BulkExport:XMLFormat' => 'Файл XML (*.xml)',
	'Core:BulkExport:XMLOptions' => 'Параметры XML',
	'Core:BulkExport:SpreadsheetFormat' => 'Таблица HTML (*.html)',
	'Core:BulkExport:SpreadsheetOptions' => 'Параметры таблицы',
	'Core:BulkExport:OptionNoLocalize' => 'Не локализовать значения (для полей с выпадающими списками)',
	'Core:BulkExport:OptionLinkSets' => 'Включить связанные объекты',
	'Core:BulkExport:OptionFormattedText' => 'Сохранить форматирование текста',
	'Core:BulkExport:ScopeDefinition' => 'Определение экспортируемых объектов',
	'Core:BulkExportLabelOQLExpression' => 'Запрос OQL:',
	'Core:BulkExportLabelPhrasebookEntry' => 'Запись в книге запросов:',
	'Core:BulkExportMessageEmptyOQL' => 'Пожалуйста, введите OQL-запрос.',
	'Core:BulkExportMessageEmptyPhrasebookEntry' => 'Выберите запись в книге запросов.',
	'Core:BulkExportQueryPlaceholder' => 'Введите здесь OQL-запрос...',
	'Core:BulkExportCanRunNonInteractive' => 'Нажмите здесь, чтобы перейти к экспорту в неинтерактивном режиме',
	'Core:BulkExportLegacyExport' => 'Нажмите здесь, чтобы перейти к устаревшему экспорту',
	'Core:BulkExport:XLSXOptions' => 'Параметры Excel',
	'Core:BulkExport:TextFormat' => 'Текстовые поля с HTML-разметкой',
	'Core:BulkExport:DateTimeFormat' => 'Формат даты и времени',
	'Core:BulkExport:DateTimeFormatDefault_Example' => 'Формат по умолчанию (%1$s), например %2$s',
	'Core:BulkExport:DateTimeFormatCustom_Format' => 'Пользовательский формат: %1$s',
	'Core:BulkExport:PDF:PageNumber' => 'Страница %1$s',
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

//
// Class: TagSetFieldData
//
Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:TagSetFieldData' => '%2$s для класса %1$s',
	'Class:TagSetFieldData+' => '',

	'Class:TagSetFieldData/Attribute:code' => 'Код',
	'Class:TagSetFieldData/Attribute:code+' => 'Внутренний код. Должен содержать не менее 3 цифробуквенных символа.',
	'Class:TagSetFieldData/Attribute:label' => 'Метка',
	'Class:TagSetFieldData/Attribute:label+' => 'Отображаемая метка',
	'Class:TagSetFieldData/Attribute:description' => 'Описание',
	'Class:TagSetFieldData/Attribute:description+' => 'Описание',
	'Class:TagSetFieldData/Attribute:finalclass' => 'Класс тега',
	'Class:TagSetFieldData/Attribute:obj_class' => 'Класс объекта',
	'Class:TagSetFieldData/Attribute:obj_attcode' => 'Код поля',

	'Core:TagSetFieldData:ErrorDeleteUsedTag' => 'Используемые теги не могут быть удалены',
	'Core:TagSetFieldData:ErrorDuplicateTagCodeOrLabel' => 'Коды и метки тегов должны быть уникальными',
	'Core:TagSetFieldData:ErrorTagCodeSyntax' => 'Код тега должен содержать от 3 до %1$d цифробуквенных символов',
	'Core:TagSetFieldData:ErrorTagCodeReservedWord' => 'Выбранный код тега является зарезервированным словом',
	'Core:TagSetFieldData:ErrorTagLabelSyntax' => 'Метка тега не должна содержать \'%1$s\' или быть пустой',
	'Core:TagSetFieldData:ErrorCodeUpdateNotAllowed' => 'Код тега не может быть изменен при использовании',
	'Core:TagSetFieldData:ErrorClassUpdateNotAllowed' => 'Нельзя изменить "Object Class" тега',
	'Core:TagSetFieldData:ErrorAttCodeUpdateNotAllowed' => 'Нельзя изменить "Attribute Code" тега',
	'Core:TagSetFieldData:WhereIsThisTagTab' => 'Использование тега (%1$d)',
	'Core:TagSetFieldData:NoEntryFound' => 'Не найдено записей с этим тегом',
));

//
// Class: DBProperty
//
Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:DBProperty' => 'DB property~~',
	'Class:DBProperty+' => '~~',
	'Class:DBProperty/Attribute:name' => 'Name~~',
	'Class:DBProperty/Attribute:name+' => '~~',
	'Class:DBProperty/Attribute:description' => 'Description~~',
	'Class:DBProperty/Attribute:description+' => '~~',
	'Class:DBProperty/Attribute:value' => 'Value~~',
	'Class:DBProperty/Attribute:value+' => '~~',
	'Class:DBProperty/Attribute:change_date' => 'Change date~~',
	'Class:DBProperty/Attribute:change_date+' => '~~',
	'Class:DBProperty/Attribute:change_comment' => 'Change comment~~',
	'Class:DBProperty/Attribute:change_comment+' => '~~',
));

//
// Class: BackgroundTask
//
Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:BackgroundTask' => 'Background task~~',
	'Class:BackgroundTask+' => '~~',
	'Class:BackgroundTask/Attribute:class_name' => 'Class name~~',
	'Class:BackgroundTask/Attribute:class_name+' => '~~',
	'Class:BackgroundTask/Attribute:first_run_date' => 'First run date~~',
	'Class:BackgroundTask/Attribute:first_run_date+' => '~~',
	'Class:BackgroundTask/Attribute:latest_run_date' => 'Latest run date~~',
	'Class:BackgroundTask/Attribute:latest_run_date+' => '~~',
	'Class:BackgroundTask/Attribute:next_run_date' => 'Next run date~~',
	'Class:BackgroundTask/Attribute:next_run_date+' => '~~',
	'Class:BackgroundTask/Attribute:total_exec_count' => 'Total exec. count~~',
	'Class:BackgroundTask/Attribute:total_exec_count+' => '~~',
	'Class:BackgroundTask/Attribute:latest_run_duration' => 'Latest run duration~~',
	'Class:BackgroundTask/Attribute:latest_run_duration+' => '~~',
	'Class:BackgroundTask/Attribute:min_run_duration' => 'Min. run duration~~',
	'Class:BackgroundTask/Attribute:min_run_duration+' => '~~',
	'Class:BackgroundTask/Attribute:max_run_duration' => 'Max. run duration~~',
	'Class:BackgroundTask/Attribute:max_run_duration+' => '~~',
	'Class:BackgroundTask/Attribute:average_run_duration' => 'Average run duration~~',
	'Class:BackgroundTask/Attribute:average_run_duration+' => '~~',
	'Class:BackgroundTask/Attribute:running' => 'Running~~',
	'Class:BackgroundTask/Attribute:running+' => '~~',
	'Class:BackgroundTask/Attribute:status' => 'Status~~',
	'Class:BackgroundTask/Attribute:status+' => '~~',
));

//
// Class: AsyncTask
//
Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:AsyncTask' => 'Async. task~~',
	'Class:AsyncTask+' => '~~',
	'Class:AsyncTask/Attribute:created' => 'Created~~',
	'Class:AsyncTask/Attribute:created+' => '~~',
	'Class:AsyncTask/Attribute:started' => 'Started~~',
	'Class:AsyncTask/Attribute:started+' => '~~',
	'Class:AsyncTask/Attribute:planned' => 'Planned~~',
	'Class:AsyncTask/Attribute:planned+' => '~~',
	'Class:AsyncTask/Attribute:event_id' => 'Event~~',
	'Class:AsyncTask/Attribute:event_id+' => '~~',
	'Class:AsyncTask/Attribute:finalclass' => 'Final class~~',
	'Class:AsyncTask/Attribute:finalclass+' => '~~',
));
