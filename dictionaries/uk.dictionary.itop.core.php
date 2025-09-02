<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * 
 */

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
    'Core:DeletedObjectLabel' => '%1$s (видалено)',
    'Core:DeletedObjectTip' => 'Об’єкт було видалено %1$s (%2$s)',
    'Core:UnknownObjectLabel' => 'Об’єкт не знайдено (клас: %1$s, id: %2$d)',
    'Core:UnknownObjectTip' => 'Об’єкт не вдалося знайти. Можливо, його було видалено певний час тому, і журнал з того часу був очищений.',
    'Core:UniquenessDefaultError' => 'Помилка правила унікальності «%1$s»',
    'Core:CheckConsistencyError' => 'Правила узгодженості не виконані: %1$s',
    'Core:CheckValueError' => 'Неочікуване значення атрибута «%1$s» (%2$s) : %3$s',
    'Core:AttributeLinkedSet' => 'Масив об’єктів (1-n)',
    'Core:AttributeLinkedSet+' => 'Список об’єктів заданого класу, що вказують на поточний об’єкт',
    'Core:AttributeLinkedSetDuplicatesFound' => 'Дублікати у полі «%1$s»: %2$s',
    'Core:AttributeDashboard' => 'Дашборд',
    'Core:AttributeDashboard+' => '',
    'Core:AttributePhoneNumber' => 'Номер телефону',
    'Core:AttributePhoneNumber+' => '',
    'Core:AttributeObsolescenceDate' => 'Дата застарівання',
    'Core:AttributeObsolescenceDate+' => '',
    'Core:AttributeTagSet' => 'Список тегів',
    'Core:AttributeTagSet+' => '',
    'Core:AttributeSet:placeholder' => 'натисніть, щоб додати',
    'Core:Placeholder:CannotBeResolved' => '(%1$s : не вдалося визначити)',
    'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromClass' => '%1$s (%2$s)',
    'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromOneChildClass' => '%1$s (%2$s з %3$s)',
    'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromSeveralChildClasses' => '%1$s (%2$s з дочірніх класів)',
    'Core:AttributeCaseLog' => 'Журнал',
    'Core:AttributeCaseLog+' => '',
    'Core:AttributeMetaEnum' => 'Обчислюваний enum',
    'Core:AttributeMetaEnum+' => '',
    'Core:AttributeLinkedSetIndirect' => 'Масив об’єктів (n-n)',
    'Core:AttributeLinkedSetIndirect+' => 'Список об’єктів заданого класу, пов’язаних з поточним через проміжний клас',
    'Core:AttributeInteger' => 'Ціле',
    'Core:AttributeInteger+' => 'Ціле значення (може бути від’ємним)',
    'Core:AttributeDecimal' => 'Десяткове',
    'Core:AttributeDecimal+' => 'Десяткове значення (може бути від’ємним)',
    'Core:AttributeBoolean' => 'Логічне',
    'Core:AttributeBoolean+' => 'Так/Ні',
    'Core:AttributeBoolean/Value:null' => '',
    'Core:AttributeBoolean/Value:yes' => 'Так',
    'Core:AttributeBoolean/Value:no' => 'Ні',
    'Core:AttributeArchiveFlag' => 'Архівний прапор',
    'Core:AttributeArchiveFlag/Value:yes' => 'Так',
    'Core:AttributeArchiveFlag/Value:yes+' => 'Цей об’єкт видно лише в режимі архіву',
    'Core:AttributeArchiveFlag/Value:no' => 'Ні',
    'Core:AttributeArchiveFlag/Label' => 'Архівний',
    'Core:AttributeArchiveFlag/Label+' => '',
    'Core:AttributeArchiveDate/Label' => 'Дата архівування',
    'Core:AttributeArchiveDate/Label+' => '',
    'Core:AttributeObsolescenceFlag' => 'Прапор застарілості',
    'Core:AttributeObsolescenceFlag/Value:yes' => 'Так',
    'Core:AttributeObsolescenceFlag/Value:yes+' => 'Цей об’єкт виключено з аналізу впливу та приховано з результатів пошуку',
    'Core:AttributeObsolescenceFlag/Value:no' => 'Ні',
    'Core:AttributeObsolescenceFlag/Label' => 'Застарілий',
    'Core:AttributeObsolescenceFlag/Label+' => 'Обчислюється динамічно на основі значень інших атрибутів',
    'Core:AttributeObsolescenceDate/Label' => 'Дата застарівання',
    'Core:AttributeObsolescenceDate/Label+' => 'Орієнтовна дата, з якої об’єкт вважається застарілим',
    'Core:AttributeString' => 'Рядок',
    'Core:AttributeString+' => 'Текстовий рядок',
    'Core:AttributeClass' => 'Клас',
    'Core:AttributeClass+' => 'Клас об’єкта',
    'Core:AttributeApplicationLanguage' => 'Мова користувача',
    'Core:AttributeApplicationLanguage+' => 'Мова і країна (UA UA)',
    'Core:AttributeFinalClass' => 'Клас (авто)',
    'Core:AttributeFinalClass+' => 'Реальний клас об’єкта (автоматично створюється ядром)',
    'Core:AttributePassword' => 'Пароль',
    'Core:AttributePassword+' => 'Пароль зовнішнього пристрою',
    'Core:AttributeEncryptedString' => 'Зашифрований рядок',
    'Core:AttributeEncryptedString+' => 'Рядок, зашифрований локальним ключем',
    'Core:AttributeEncryptUnknownLibrary' => 'Вказана бібліотека шифрування (%1$s) невідома',
    'Core:AttributeEncryptFailedToDecrypt' => '** помилка дешифрування **',
    'Core:AttributeText' => 'Текст',
    'Core:AttributeText+' => 'Багаторядковий текст',
    'Core:AttributeHTML' => 'HTML',
    'Core:AttributeHTML+' => '',
    'Core:AttributeEmailAddress' => 'Email',
    'Core:AttributeEmailAddress+' => 'Email адреса',
    'Core:AttributeIPAddress' => 'IP адреса',
    'Core:AttributeIPAddress+' => '',
    'Core:AttributeOQL' => 'OQL',
    'Core:AttributeOQL+' => 'Вираз мови запитів до об’єктів (Object Query Language)',
    'Core:AttributeEnum' => 'Enum',
    'Core:AttributeEnum+' => 'Список наперед визначених текстових рядків',
    'Core:AttributeTemplateString' => 'Шаблон рядка',
    'Core:AttributeTemplateString+' => 'Рядок із плейсхолдерами',
    'Core:AttributeTemplateText' => 'Шаблон тексту',
    'Core:AttributeTemplateText+' => 'Текст із плейсхолдерами',
    'Core:AttributeTemplateHTML' => 'Шаблон HTML',
    'Core:AttributeTemplateHTML+' => 'HTML із плейсхолдерами',
    'Core:AttributeDateTime' => 'Дата/час',
    'Core:AttributeDateTime+' => 'Дата і час (рррр-мм-дд гг:хх:сс)',
    'Core:AttributeDateTime?SmartSearch' => '
<p>
    Формат дати:<br/>
    <b>рррр-мм-дд гг:хх:сс</b><br/>
    Приклад: 2017-11-27 19:17:00
</p>
<p>
Оператори:<br/>
    <b>&gt;</b><em>дата</em><br/>
    <b>&lt;</b><em>дата</em><br/>
    <b>[</b><em>дата</em>,<em>дата</em><b>]</b>
</p>
<p>
Якщо час не вказано, за замовчуванням використовується 00:00:00
</p>',
    'Core:AttributeDate' => 'Дата',
    'Core:AttributeDate+' => 'Дата (рррр-мм-дд)',
    'Core:AttributeDate?SmartSearch' => '
<p>
    Формат дати:<br/>
    <b>рррр-мм-дд</b><br/>
    Приклад: 2017-11-27
</p>
<p>
Оператори:<br/>
    <b>&gt;</b><em>дата</em><br/>
    <b>&lt;</b><em>дата</em><br/>
    <b>[</b><em>дата</em>,<em>дата</em><b>]</b>
</p>',
    'Core:AttributeDeadline' => 'Кінцевий термін',
    'Core:AttributeDeadline+' => 'Дата, що відображається відносно поточного часу',
    'Core:AttributeExternalKey' => 'Зовнішній ключ',
    'Core:AttributeExternalKey+' => '',
    'Core:AttributeHierarchicalKey' => 'Ієрархічний ключ',
    'Core:AttributeHierarchicalKey+' => 'Зовнішній ключ до батьківського об’єкта',
    'Core:AttributeExternalField' => 'Зовнішнє поле',
    'Core:AttributeExternalField+' => 'Поле, пов’язане з зовнішнім ключем',
    'Core:AttributeURL' => 'URL',
    'Core:AttributeURL+' => 'Абсолютна чи відносна URL як текстовий рядок',
    'Core:AttributeBlob' => 'Blob',
    'Core:AttributeBlob+' => 'Будь-який двійковий вміст (документ)',
    'Core:AttributeOneWayPassword' => 'Хешований пароль',
    'Core:AttributeOneWayPassword+' => 'Зашифрований (хешований) пароль',
    'Core:AttributeTable' => 'Таблиця',
    'Core:AttributeTable+' => 'Індексований масив із двома вимірами',
    'Core:AttributePropertySet' => 'Властивості',
    'Core:AttributePropertySet+' => 'Список нетипізованих властивостей (ім’я та значення)',
    'Core:AttributeFriendlyName' => 'Повна назва',
    'Core:AttributeFriendlyName+' => 'Атрибут створюється автоматично; повна назва обчислюється з кількох атрибутів',
    'Core:FriendlyName-Label' => 'Повна назва',
    'Core:FriendlyName-Description' => 'Повна назва',
    'Core:AttributeTag' => 'Тег',
    'Core:AttributeTag+' => '',
    'Core:Context=REST/JSON' => 'REST',
    'Core:Context=Synchro' => 'Синхро',
    'Core:Context=Setup' => 'Налаштування',
    'Core:Context=GUI:Console' => 'Консоль',
    'Core:Context=CRON' => 'cron',
    'Core:Context=GUI:Portal' => 'Портал',
));


//////////////////////////////////////////////////////////////////////
// Classes in 'core/cmdb'
//////////////////////////////////////////////////////////////////////
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
    'Class:CMDBChange' => 'Зміна CMDB',
    'Class:CMDBChange+' => 'Відстеження змін у CMDB',
    'Class:CMDBChange/Attribute:date' => 'Дата',
    'Class:CMDBChange/Attribute:date+' => 'Дата та час зміни',
    'Class:CMDBChange/Attribute:userinfo' => 'Користувач',
    'Class:CMDBChange/Attribute:userinfo+' => 'Хто виконав зміну',
    'Class:CMDBChange/Attribute:origin/Value:interactive' => 'Взаємодія користувача в інтерфейсі',
    'Class:CMDBChange/Attribute:origin/Value:csv-import.php' => 'Скрипт імпорту CSV',
    'Class:CMDBChange/Attribute:origin/Value:csv-interactive' => 'Імпорт CSV через інтерфейс',
    'Class:CMDBChange/Attribute:origin/Value:email-processing' => 'Обробка електронної пошти',
    'Class:CMDBChange/Attribute:origin/Value:synchro-data-source' => 'Джерело даних синхронізації',
    'Class:CMDBChange/Attribute:origin/Value:webservice-rest' => 'Вебсервіси REST/JSON',
    'Class:CMDBChange/Attribute:origin/Value:webservice-soap' => 'Вебсервіси SOAP',
    'Class:CMDBChange/Attribute:origin/Value:custom-extension' => 'За допомогою розширення',
));

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
    'Class:CMDBChangeOp' => 'Операція зміни CMDB',
    'Class:CMDBChangeOp+' => 'Відстеження операції зміни в CMDB',
    'Class:CMDBChangeOp/Attribute:change' => 'Зміна CMDB',
    'Class:CMDBChangeOp/Attribute:change+' => '',
    'Class:CMDBChangeOp/Attribute:date' => 'Дата',
    'Class:CMDBChangeOp/Attribute:date+' => 'Дата та час зміни',
    'Class:CMDBChangeOp/Attribute:userinfo' => 'Користувач',
    'Class:CMDBChangeOp/Attribute:userinfo+' => 'Хто виконав зміну',
    'Class:CMDBChangeOp/Attribute:objclass' => 'Клас обʼєкта',
    'Class:CMDBChangeOp/Attribute:objclass+' => '',
    'Class:CMDBChangeOp/Attribute:objkey' => 'ID обʼєкта',
    'Class:CMDBChangeOp/Attribute:objkey+' => '',
    'Class:CMDBChangeOp/Attribute:finalclass' => 'Кінцевий клас',
    'Class:CMDBChangeOp/Attribute:finalclass+' => '',
));

//
// Class: CMDBChangeOpCreate
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
    'Class:CMDBChangeOpCreate' => 'Операція створення обʼєкта',
    'Class:CMDBChangeOpCreate+' => 'Відстеження створення обʼєкта',
));

//
// Class: CMDBChangeOpDelete
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
    'Class:CMDBChangeOpDelete' => 'Операція видалення обʼєкта',
    'Class:CMDBChangeOpDelete+' => 'Відстеження видалення обʼєкта',
));

//
// Class: CMDBChangeOpSetAttribute
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
    'Class:CMDBChangeOpSetAttribute' => 'Зміна обʼєкта',
    'Class:CMDBChangeOpSetAttribute+' => 'Відстеження зміни обʼєкта',
    'Class:CMDBChangeOpSetAttribute/Attribute:attcode' => 'Властивість',
    'Class:CMDBChangeOpSetAttribute/Attribute:attcode+' => 'Код зміненої властивості',
));

//
// Class: CMDBChangeOpSetAttributeScalar
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
    'Class:CMDBChangeOpSetAttributeScalar' => 'Зміна властивості',
    'Class:CMDBChangeOpSetAttributeScalar+' => 'Відстеження зміни скалярної властивості обʼєкта',
    'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue' => 'Попереднє значення',
    'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue+' => 'Попереднє значення атрибуту',
    'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue' => 'Нове значення',
    'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue+' => 'Нове значення атрибуту',
));

// Used by CMDBChangeOp... & derived classes
Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
    'Change:ObjectCreated' => 'Обʼєкт створено.',
    'Change:ObjectDeleted' => 'Обʼєкт видалено.',
    'Change:ObjectModified' => 'Обʼєкт змінено.',
    'Change:TwoAttributesChanged' => 'Змінено %1$s та %2$s',
    'Change:ThreeAttributesChanged' => 'Змінено %1$s, %2$s та ще 1',
    'Change:FourOrMoreAttributesChanged' => 'Змінено %1$s, %2$s та ще %3$s',
    'Change:AttName_SetTo_NewValue_PreviousValue_OldValue' => 'У полі %1$s встановлено значення %2$s (попереднє значення %3$s).',
    'Change:AttName_SetTo' => 'У полі %1$s встановлено значення %2$s.',
    'Change:Text_AppendedTo_AttName' => 'Нове значення %1$s додано до поля %2$s.',
    'Change:AttName_Changed_PreviousValue_OldValue' => 'Поле %1$s змінено (попереднє значення %2$s).',
    'Change:AttName_Changed' => 'Поле %1$s змінено.',
    'Change:AttName_EntryAdded' => 'До поля %1$s додано нове значення.',
    'Change:State_Changed_NewValue_OldValue' => 'Перехід з %2$s у %1$s',
    'Change:LinkSet:Added' => 'додано обʼєкт %1$s.',
    'Change:LinkSet:Removed' => 'видалено обʼєкт %1$s.',
    'Change:LinkSet:Modified' => 'змінено обʼєкт %1$s.',
));

//
// Class: CMDBChangeOpSetAttributeBlob
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
    'Class:CMDBChangeOpSetAttributeBlob' => 'Зміна даних',
    'Class:CMDBChangeOpSetAttributeBlob+' => 'Відстеження зміни даних',
    'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata' => 'Попередні дані',
    'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata+' => 'Попередній вміст атрибута',
));

//
// Class: CMDBChangeOpSetAttributeText
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
    'Class:CMDBChangeOpSetAttributeText' => 'Зміна тексту',
    'Class:CMDBChangeOpSetAttributeText+' => 'Відстеження зміни тексту',
    'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata' => 'Попередні дані',
    'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata+' => 'Попередній вміст атрибута',
));

//
// Class: Event
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
    'Class:Event' => 'Подія',
    'Class:Event+' => 'Внутрішня подія додатку',
    'Class:Event/Attribute:message' => 'Повідомлення',
    'Class:Event/Attribute:message+' => 'Короткий опис події',
    'Class:Event/Attribute:date' => 'Дата',
    'Class:Event/Attribute:date+' => 'Дата та час реєстрації події',
    'Class:Event/Attribute:userinfo' => 'Користувач',
    'Class:Event/Attribute:userinfo+' => 'Користувач, чиї дії спричинили цю подію',
    'Class:Event/Attribute:finalclass' => 'Тип',
    'Class:Event/Attribute:finalclass+' => '',
));

//
// Class: EventNotification
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
    'Class:EventNotification' => 'Сповіщення',
    'Class:EventNotification+' => 'Відстеження надісланих сповіщень',
    'Class:EventNotification/Attribute:trigger_id' => 'Тригер',
    'Class:EventNotification/Attribute:trigger_id+' => 'Тригер, що спрацював',
    'Class:EventNotification/Attribute:action_id' => 'Дія',
    'Class:EventNotification/Attribute:action_id+' => 'Виконана дія',
    'Class:EventNotification/Attribute:object_id' => 'ID обʼєкта',
    'Class:EventNotification/Attribute:object_id+' => 'Ідентифікатор обʼєкта цільового класу тригера',
));

//
// Class: EventNotificationEmail
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
    'Class:EventNotificationEmail' => 'Email-сповіщення',
    'Class:EventNotificationEmail+' => 'Відстеження сповіщень по електронній пошті',
    'Class:EventNotificationEmail/Attribute:to' => 'Кому',
    'Class:EventNotificationEmail/Attribute:to+' => '',
    'Class:EventNotificationEmail/Attribute:cc' => 'Копія',
    'Class:EventNotificationEmail/Attribute:cc+' => '',
    'Class:EventNotificationEmail/Attribute:bcc' => 'Прихована копія',
    'Class:EventNotificationEmail/Attribute:bcc+' => '',
    'Class:EventNotificationEmail/Attribute:from' => 'Від',
    'Class:EventNotificationEmail/Attribute:from+' => 'Відправник повідомлення',
    'Class:EventNotificationEmail/Attribute:subject' => 'Тема',
    'Class:EventNotificationEmail/Attribute:subject+' => '',
    'Class:EventNotificationEmail/Attribute:body' => 'Повідомлення',
    'Class:EventNotificationEmail/Attribute:body+' => '',
    'Class:EventNotificationEmail/Attribute:attachments' => 'Вкладення',
    'Class:EventNotificationEmail/Attribute:attachments+' => '',
));

//
// Class: EventIssue
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
    'Class:EventIssue' => 'Помилка',
    'Class:EventIssue+' => 'Відстеження помилок (попередження, помилка, інше)',
    'Class:EventIssue/Attribute:issue' => 'Помилка',
    'Class:EventIssue/Attribute:issue+' => 'Що сталося',
    'Class:EventIssue/Attribute:impact' => 'Вплив',
    'Class:EventIssue/Attribute:impact+' => 'Наслідки',
    'Class:EventIssue/Attribute:page' => 'Сторінка',
    'Class:EventIssue/Attribute:page+' => 'HTTP точка входу',
    'Class:EventIssue/Attribute:arguments_post' => 'POST-аргументи',
    'Class:EventIssue/Attribute:arguments_post+' => 'Аргументи HTTP POST',
    'Class:EventIssue/Attribute:arguments_get' => 'GET-аргументи',
    'Class:EventIssue/Attribute:arguments_get+' => 'Аргументи HTTP GET',
    'Class:EventIssue/Attribute:callstack' => 'Стек викликів',
    'Class:EventIssue/Attribute:callstack+' => '',
    'Class:EventIssue/Attribute:data' => 'Дані',
    'Class:EventIssue/Attribute:data+' => 'Детальніше',
));

//
// Class: EventWebService
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
    'Class:EventWebService' => 'Подія веб-сервісу',
    'Class:EventWebService+' => 'Відстеження виклику веб-сервісу',
    'Class:EventWebService/Attribute:verb' => 'Операція',
    'Class:EventWebService/Attribute:verb+' => 'Назва операції',
    'Class:EventWebService/Attribute:result' => 'Результат',
    'Class:EventWebService/Attribute:result+' => 'Вдалий/невдалий виклик',
    'Class:EventWebService/Attribute:log_info' => 'Журнал',
    'Class:EventWebService/Attribute:log_info+' => 'Результати журналу',
    'Class:EventWebService/Attribute:log_warning' => 'Журнал попереджень',
    'Class:EventWebService/Attribute:log_warning+' => 'Результати журналу попереджень',
    'Class:EventWebService/Attribute:log_error' => 'Журнал помилок',
    'Class:EventWebService/Attribute:log_error+' => 'Результати журналу помилок',
    'Class:EventWebService/Attribute:data' => 'Дані',
    'Class:EventWebService/Attribute:data+' => 'Результати даних',
));

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
    'Class:EventRestService' => 'Виклик REST/JSON',
    'Class:EventRestService+' => 'Відстеження виклику REST/JSON сервісу',
    'Class:EventRestService/Attribute:operation' => 'Операція',
    'Class:EventRestService/Attribute:operation+' => 'Аргумент "operation"',
    'Class:EventRestService/Attribute:version' => 'Версія',
    'Class:EventRestService/Attribute:version+' => 'Аргумент "version"',
    'Class:EventRestService/Attribute:json_input' => 'Вхідні дані',
    'Class:EventRestService/Attribute:json_input+' => 'Аргумент "json_data"',
    'Class:EventRestService/Attribute:code' => 'Код',
    'Class:EventRestService/Attribute:code+' => 'Код результату',
    'Class:EventRestService/Attribute:json_output' => 'Відповідь',
    'Class:EventRestService/Attribute:json_output+' => 'HTTP-відповідь (json)',
    'Class:EventRestService/Attribute:provider' => 'Постачальник',
    'Class:EventRestService/Attribute:provider+' => 'PHP-клас, який реалізує відповідну операцію',
));

//
// Class: EventLoginUsage
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
    'Class:EventLoginUsage' => 'Статистика авторизацій',
    'Class:EventLoginUsage+' => 'Підключення до застосунку',
    'Class:EventLoginUsage/Attribute:user_id' => 'Логін',
    'Class:EventLoginUsage/Attribute:user_id+' => 'Логін',
    'Class:EventLoginUsage/Attribute:contact_name' => "Ім'я користувача",
    'Class:EventLoginUsage/Attribute:contact_name+' => "Ім'я користувача",
    'Class:EventLoginUsage/Attribute:contact_email' => 'Email користувача',
    'Class:EventLoginUsage/Attribute:contact_email+' => 'Email-адреса користувача',
));

//
// Class: EventNotificationNewsroom
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
	'Class:EventNotificationNewsroom' => 'Надіслано новину',
	'Class:EventNotificationNewsroom+' => 'Відстеження розсилки новин',
	'Class:EventNotificationNewsroom/Attribute:title' => 'Заголовок',
	'Class:EventNotificationNewsroom/Attribute:title+' => 'Заголовок повідомлення',
	'Class:EventNotificationNewsroom/Attribute:icon' => 'Іконка',
	'Class:EventNotificationNewsroom/Attribute:icon+' => 'Іконка новини',
	'Class:EventNotificationNewsroom/Attribute:priority' => 'Пріоритет',
	'Class:EventNotificationNewsroom/Attribute:priority+' => 'Пріоритет повідомлення',
	'Class:EventNotificationNewsroom/Attribute:priority/Value:1' => 'Критичний',
	'Class:EventNotificationNewsroom/Attribute:priority/Value:1+' => 'Критичний рівень',
	'Class:EventNotificationNewsroom/Attribute:priority/Value:2' => 'Терміновий',
	'Class:EventNotificationNewsroom/Attribute:priority/Value:2+' => 'Терміновий рівень',
	'Class:EventNotificationNewsroom/Attribute:priority/Value:3' => 'Важливий',
	'Class:EventNotificationNewsroom/Attribute:priority/Value:3+' => 'Важливий рівень',
	'Class:EventNotificationNewsroom/Attribute:priority/Value:4' => 'Звичайний',
	'Class:EventNotificationNewsroom/Attribute:priority/Value:4+' => 'Звичайний рівень',
	'Class:EventNotificationNewsroom/Attribute:url' => 'URL',
	'Class:EventNotificationNewsroom/Attribute:url+' => 'Посилання на новину',
	'Class:EventNotificationNewsroom/Attribute:read' => 'Прочитано',
	'Class:EventNotificationNewsroom/Attribute:read+' => 'Статус прочитання',
	'Class:EventNotificationNewsroom/Attribute:read/Value:no' => 'Ні',
	'Class:EventNotificationNewsroom/Attribute:read/Value:no+' => 'Не прочитано',
	'Class:EventNotificationNewsroom/Attribute:read/Value:yes' => 'Так',
	'Class:EventNotificationNewsroom/Attribute:read/Value:yes+' => 'Прочитано',
	'Class:EventNotificationNewsroom/Attribute:read_date' => 'Дата прочитання',
	'Class:EventNotificationNewsroom/Attribute:read_date+' => 'Коли було прочитано',
	'Class:EventNotificationNewsroom/Attribute:contact_id' => 'Контакт',
	'Class:EventNotificationNewsroom/Attribute:contact_id+' => 'Кому було відправлено',
));

//
// Class: Action
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
	'Class:Action' => 'Дія',
	'Class:Action+' => 'Дія, визначена користувачем',
	'Class:Action/ComplementaryName' => '%1$s: %2$s',
	'Class:Action/Attribute:name' => 'Назва',
	'Class:Action/Attribute:name+' => '',
	'Class:Action/Attribute:description' => 'Опис',
	'Class:Action/Attribute:description+' => '',
	'Class:Action/Attribute:status' => 'Статус',
	'Class:Action/Attribute:status+' => '',
	'Class:Action/Attribute:status/Value:test' => 'Тест',
	'Class:Action/Attribute:status/Value:test+' => '',
	'Class:Action/Attribute:status/Value:enabled' => 'Увімкнено',
	'Class:Action/Attribute:status/Value:enabled+' => '',
	'Class:Action/Attribute:status/Value:disabled' => 'Вимкнено',
	'Class:Action/Attribute:status/Value:disabled+' => '',
	'Class:Action/Attribute:trigger_list' => 'Пов’язані тригери',
	'Class:Action/Attribute:trigger_list+' => 'Тригери, які запускають цю дію',
	'Class:Action/Attribute:asynchronous' => 'Асинхронно',
	'Class:Action/Attribute:asynchronous+' => 'Чи виконувати цю дію у фоновому режимі',
	'Class:Action/Attribute:asynchronous/Value:use_global_setting' => 'Використовувати глобальні налаштування',
	'Class:Action/Attribute:asynchronous/Value:yes' => 'Так',
	'Class:Action/Attribute:asynchronous/Value:yes+' => '',
	'Class:Action/Attribute:asynchronous/Value:no' => 'Ні',
	'Class:Action/Attribute:asynchronous/Value:no+' => '',
	'Class:Action/Attribute:finalclass' => 'Тип',
	'Class:Action/Attribute:finalclass+' => '',
	'Action:WarningNoTriggerLinked' => 'Увага: до цієї дії не прив’язано жодного тригера. Вона не буде активною, доки не з’явиться хоча б один.',
	'Action:last_executions_tab' => 'Останні виконання',
	'Action:last_executions_tab_panel_title' => 'Виконання цієї дії (%1$s)',
	'Action:last_executions_tab_limit_days' => 'за останні %1$s днів',
	'Action:last_executions_tab_limit_none' => 'без обмежень',
));

//
// Class: ActionNotification
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
	'Class:ActionNotification' => 'Сповіщення',
	'Class:ActionNotification+' => '',
));

//
// Class: ActionEmail
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
	'Class:ActionEmail' => 'Сповіщення на email',
	'Class:ActionEmail+' => '',
	'Class:ActionEmail/Attribute:status+' => "Цей статус визначає, хто буде повідомлений:\n- Тестування: тільки тестовий отримувач,\n- У продакшені: всі (Кому, Копія та Скр. копія),\n- Неактивно: ніхто",
	'Class:ActionEmail/Attribute:status/Value:test+' => 'Повідомляється лише тестовий отримувач',
	'Class:ActionEmail/Attribute:status/Value:enabled+' => 'Повідомляються всі email у полях Кому, Копія та Скр. копія',
	'Class:ActionEmail/Attribute:status/Value:disabled+' => 'Сповіщення на email не буде надіслано',
	'Class:ActionEmail/Attribute:test_recipient' => 'Тестовий отримувач',
	'Class:ActionEmail/Attribute:test_recipient+' => 'Отримувач, якщо сповіщення у статусі "Тест"',
	'Class:ActionEmail/Attribute:from' => 'Від',
	'Class:ActionEmail/Attribute:from+' => 'Буде відображено у заголовку email',
	'Class:ActionEmail/Attribute:from_label' => 'Від (мітка)',
	'Class:ActionEmail/Attribute:from_label+' => 'Або статична мітка, або плейсхолдер, наприклад $this->agent_id->friendlyname$',
	'Class:ActionEmail/Attribute:reply_to' => 'Відповісти на',
	'Class:ActionEmail/Attribute:reply_to+' => 'Буде відображено у заголовку email',
	'Class:ActionEmail/Attribute:reply_to_label' => 'Відповісти на (мітка)',
	'Class:ActionEmail/Attribute:reply_to_label+' => 'Або статична мітка, або плейсхолдер, наприклад $this->team_id->friendlyname$. Якщо не вказано, використовується Від (мітка).',
	'Class:ActionEmail/Attribute:to' => 'Кому',
	'Class:ActionEmail/Attribute:to+' => 'Отримувач email',
	'Class:ActionEmail/Attribute:cc' => 'Копія',
	'Class:ActionEmail/Attribute:cc+' => '',
	'Class:ActionEmail/Attribute:bcc' => 'Скр. копія',
	'Class:ActionEmail/Attribute:bcc+' => 'Схована копія',
	'Class:ActionEmail/Attribute:subject' => 'Тема',
	'Class:ActionEmail/Attribute:subject+' => 'Тема листа',
	'Class:ActionEmail/Attribute:body' => 'Тіло',
	'Class:ActionEmail/Attribute:body+' => 'Вміст листа',
	'Class:ActionEmail/Attribute:importance' => 'Важливість',
	'Class:ActionEmail/Attribute:importance+' => 'Прапорець важливості',
	'Class:ActionEmail/Attribute:importance/Value:low' => 'Низька',
	'Class:ActionEmail/Attribute:importance/Value:low+' => '',
	'Class:ActionEmail/Attribute:importance/Value:normal' => 'Нормальна',
	'Class:ActionEmail/Attribute:importance/Value:normal+' => '',
	'Class:ActionEmail/Attribute:importance/Value:high' => 'Висока',
	'Class:ActionEmail/Attribute:importance/Value:high+' => '',
	'Class:ActionEmail/Attribute:language' => 'Мова',
	'Class:ActionEmail/Attribute:language+' => "Мова для плейсхолдерів (\$xxx\$) у повідомленні (стан, важливість, пріоритет тощо)",
	'Class:ActionEmail/Attribute:html_template' => 'HTML шаблон',
	'Class:ActionEmail/Attribute:html_template+' => "Додатковий HTML шаблон для обгортки вмісту атрибуту 'Тіло', зручно для кастомних email-розміток (у шаблоні вміст 'Тіло' замінює плейсхолдер \$content\$)",
	'Class:ActionEmail/Attribute:ignore_notify' => "Ігнорувати прапорець 'Сповіщати'",
	'Class:ActionEmail/Attribute:ignore_notify+' => "Якщо встановлено 'Так', прапорець 'Сповіщати' у Контактах не враховується.",
	'Class:ActionEmail/Attribute:ignore_notify/Value:no' => 'Ні',
	'Class:ActionEmail/Attribute:ignore_notify/Value:yes' => 'Так',
	'ActionEmail:main' => 'Повідомлення',
	'ActionEmail:trigger' => 'Тригери',
	'ActionEmail:recipients' => 'Контакти',
	'ActionEmail:preview_tab' => 'Попередній перегляд',
	'ActionEmail:preview_tab+' => 'Попередній перегляд шаблону email',
	'ActionEmail:preview_warning' => 'Фактичний вигляд листа може відрізнятися у поштовому клієнті від попереднього перегляду у браузері.',
	'ActionEmail:preview_more_info' => "Для докладнішої інформації про підтримку CSS у різних поштових клієнтах дивіться %1\$s",
	'ActionEmail:content_placeholder_missing' => 'Плейсхолдер "%1$s" не знайдено в HTML-шаблоні. Вміст поля "%2$s" не буде включений у згенеровані листи.',
));


//
// Class: ActionNewsroom
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
	'ActionNewsroom:trigger' => 'Тригер',
	'ActionNewsroom:content' => 'Повідомлення',
	'ActionNewsroom:settings' => 'Налаштування',
	'Class:ActionNewsroom' => 'Сповіщення через Newsroom',
	'Class:ActionNewsroom+' => '',
	'Class:ActionNewsroom/Attribute:title' => 'Заголовок',
	'Class:ActionNewsroom/Attribute:title+' => 'Заголовок новини. Може містити плейсхолдери, наприклад $this->attribute_code$',
	'Class:ActionNewsroom/Attribute:message' => 'Повідомлення',
	'Class:ActionNewsroom/Attribute:message+' => "Вміст новини, у форматі Markdown (не HTML). Може містити плейсхолдери:\n- \$this->attribute_code\$ будь-який атрибут об'єкта-тригера,\n- \$this->attribute_external_key->attribute\$ рекурсивний синтаксис для зовнішніх атрибутів,\n- \$current_contact->attribute\$ атрибут користувача, що ініціював сповіщення",
	'Class:ActionNewsroom/Attribute:icon' => 'Іконка',
	'Class:ActionNewsroom/Attribute:icon+' => "Іконка, що відображається поряд із новиною у Newsroom.\n- Якщо вказано, використовується власна іконка\n- Якщо ні — іконка об'єкта-тригера (наприклад, фото Персони)\n- Якщо немає — іконка класу об'єкта-тригера\n- В іншому випадку — компактний логотип застосунку",
	'Class:ActionNewsroom/Attribute:priority' => 'Пріоритет',
	'Class:ActionNewsroom/Attribute:priority+' => 'Новини будуть впорядковані за зменшенням пріоритету у спливаючому вікні Newsroom',
	'Class:ActionNewsroom/Attribute:priority/Value:1' => 'Критичний',
	'Class:ActionNewsroom/Attribute:priority/Value:1+' => 'Критичний',
	'Class:ActionNewsroom/Attribute:priority/Value:2' => 'Терміновий',
	'Class:ActionNewsroom/Attribute:priority/Value:2+' => 'Терміновий',
	'Class:ActionNewsroom/Attribute:priority/Value:3' => 'Важливий',
	'Class:ActionNewsroom/Attribute:priority/Value:3+' => 'Важливий',
	'Class:ActionNewsroom/Attribute:priority/Value:4' => 'Звичайний',
	'Class:ActionNewsroom/Attribute:priority/Value:4+' => 'Звичайний',
	'Class:ActionNewsroom/Attribute:test_recipient_id' => 'Тестовий отримувач',
	'Class:ActionNewsroom/Attribute:test_recipient_id+' => 'Особа, яка використовується замість Отримувачів під час тестування сповіщення',
	'Class:ActionNewsroom/Attribute:recipients' => 'Отримувачі',
	'Class:ActionNewsroom/Attribute:recipients+' => 'OQL-запит, що повертає об’єкти Контактів',
	'Class:ActionNewsroom/Attribute:url' => 'URL',
	'Class:ActionNewsroom/Attribute:url+' => "За замовчуванням вказує на об'єкт-тригер сповіщення. Але можна вказати і власний URL.",
));

//
// Class: Trigger
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
	'Class:Trigger' => 'Тригер',
	'Class:Trigger+' => 'Користувацький обробник подій',
	'Class:Trigger/ComplementaryName' => '%1$s, %2$s',
	'Class:Trigger/Attribute:description' => 'Опис',
	'Class:Trigger/Attribute:description+' => 'Опис тригера',
	'Class:Trigger/Attribute:action_list' => 'Дії тригера',
	'Class:Trigger/Attribute:action_list+' => 'Дії, що виконуються при спрацьовуванні тригера',
	'Class:Trigger/Attribute:finalclass' => 'Тип',
	'Class:Trigger/Attribute:finalclass+' => '',
	'Class:Trigger/Attribute:context' => 'Контекст',
	'Class:Trigger/Attribute:context+' => 'Контекст, у якому буде спрацьовувати тригер',
	'Class:Trigger/Attribute:complement' => 'Додаткова інформація',
	'Class:Trigger/Attribute:complement+' => "Обчислюється автоматично англійською для тригерів, що наслідують TriggerOnObject",
	'Class:Trigger/Attribute:subscription_policy' => 'Політика підписки',
	'Class:Trigger/Attribute:subscription_policy+' => 'Дозволяє користувачам відписуватись від тригера',
	'Class:Trigger/Attribute:subscription_policy/Value:allow_no_channel' => 'Дозволити повну відписку',
	'Class:Trigger/Attribute:subscription_policy/Value:force_at_least_one_channel' => 'Вимагати хоча б один канал (News або Email)',
	'Class:Trigger/Attribute:subscription_policy/Value:force_all_channels' => 'Заборонити відписку',
));

//
// Class: TriggerOnObject
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
	'Class:TriggerOnObject' => 'Тригер на клас об’єкта',
	'Class:TriggerOnObject+' => 'Тригер на події об’єктів цього класу',
	'Class:TriggerOnObject/Attribute:target_class' => 'Цільовий клас',
	'Class:TriggerOnObject/Attribute:target_class+' => 'Клас об’єктів, для яких буде спрацьовувати цей тригер',
	'Class:TriggerOnObject/Attribute:filter' => 'OQL-фільтр',
	'Class:TriggerOnObject/Attribute:filter+' => 'Дозволяє обмежити список об’єктів, для яких спрацьовує тригер',
	'TriggerOnObject:WrongFilterQuery' => 'Неправильний запит фільтра: %1$s',
	'TriggerOnObject:WrongFilterClass' => 'Запит фільтра має повертати об’єкти класу "%1$s"',
));

//
// Class: TriggerOnPortalUpdate
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
	'Class:TriggerOnPortalUpdate' => 'Тригер на оновлення з порталу',
	'Class:TriggerOnPortalUpdate+' => 'Тригер на оновлення об’єкта користувачем порталу',
));

//
// Class: TriggerOnStateChange
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
	'Class:TriggerOnStateChange' => 'Тригер на зміну статусу',
	'Class:TriggerOnStateChange+' => 'Тригер на зміну статусу об’єкта',
	'Class:TriggerOnStateChange/Attribute:state' => 'Статус',
	'Class:TriggerOnStateChange/Attribute:state+' => 'Код статусу об’єкта, наприклад, \'resolved\'',
));

//
// Class: TriggerOnStateEnter
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
	'Class:TriggerOnStateEnter' => 'Тригер на вхід у статус',
	'Class:TriggerOnStateEnter+' => 'Тригер на вхід об’єкта у статус',
));

//
// Class: TriggerOnStateLeave
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
	'Class:TriggerOnStateLeave' => 'Тригер на вихід зі статусу',
	'Class:TriggerOnStateLeave+' => 'Тригер на вихід об’єкта зі статусу',
));

//
// Class: TriggerOnObjectCreate
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
	'Class:TriggerOnObjectCreate' => 'Тригер на створення обʼєкта',
	'Class:TriggerOnObjectCreate+' => 'Тригер на створення обʼєкта цього або дочірнього класу',
));

//
// Class: TriggerOnObjectDelete
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
	'Class:TriggerOnObjectDelete' => 'Тригер на видалення обʼєкта',
	'Class:TriggerOnObjectDelete+' => 'Тригер на видалення обʼєкта цього або дочірнього класу',
));

//
// Class: TriggerOnObjectUpdate
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
	'Class:TriggerOnObjectUpdate' => 'Тригер на оновлення обʼєкта',
	'Class:TriggerOnObjectUpdate+' => 'Тригер на оновлення обʼєкта цього або дочірнього класу',
	'Class:TriggerOnObjectUpdate/Attribute:target_attcodes' => 'Відстежувані поля',
	'Class:TriggerOnObjectUpdate/Attribute:target_attcodes+' => 'Поля обʼєкта, при оновленні яких спрацює тригер',
));

//
// Class: TriggerOnObjectMention
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
	'Class:TriggerOnObjectMention' => 'Тригер (на згадку обʼєкта)',
	'Class:TriggerOnObjectMention+' => 'Тригер на згадку (@xxx) обʼєкта цього або дочірнього класу у журнальному атрибуті',
	'Class:TriggerOnObjectMention/Attribute:mentioned_filter' => 'Фільтр згаданого',
	'Class:TriggerOnObjectMention/Attribute:mentioned_filter+' => 'Обмежити список згаданих обʼєктів, які активують тригер. Якщо порожньо — будь-який згаданий обʼєкт (будь-якого класу) активує тригер.',
));

//
// Class: TriggerOnAttributeBlobDownload
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
	'Class:TriggerOnAttributeBlobDownload' => 'Тригер (на завантаження документа обʼєкта)',
	'Class:TriggerOnAttributeBlobDownload+' => 'Тригер на завантаження поля-документа обʼєкта цього або дочірнього класу',
	'Class:TriggerOnAttributeBlobDownload/Attribute:target_attcodes' => 'Цільові поля',
	'Class:TriggerOnAttributeBlobDownload/Attribute:target_attcodes+' => '',
));

//
// Class: TriggerOnThresholdReached
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
	'Class:TriggerOnThresholdReached' => 'Тригер на досягнення порогового значення',
	'Class:TriggerOnThresholdReached+' => 'Тригер на досягнення секундоміром порогового значення (TTO, TTR)',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code' => 'Секундомір',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code+' => 'За замовчуванням для Інцидентів та Запитів доступні \'ttr\' та \'tto\'',
	'Class:TriggerOnThresholdReached/Attribute:threshold_index' => 'Поріг',
	'Class:TriggerOnThresholdReached/Attribute:threshold_index+' => 'Порогове значення секундоміра у %, за замовчуванням \'75\' та \'100\'',
));

//
// Class: lnkTriggerAction
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
	'Class:lnkTriggerAction' => 'Звʼязок Тригер/Дія',
	'Class:lnkTriggerAction+' => 'Звʼязок між тригером і діями',
	'Class:lnkTriggerAction/Attribute:action_id' => 'Дія',
	'Class:lnkTriggerAction/Attribute:action_id+' => 'Виконувана дія',
	'Class:lnkTriggerAction/Attribute:action_name' => 'Дія',
	'Class:lnkTriggerAction/Attribute:action_name+' => '',
	'Class:lnkTriggerAction/Attribute:trigger_id' => 'Тригер',
	'Class:lnkTriggerAction/Attribute:trigger_id+' => '',
	'Class:lnkTriggerAction/Attribute:trigger_name' => 'Тригер',
	'Class:lnkTriggerAction/Attribute:trigger_name+' => '',
	'Class:lnkTriggerAction/Attribute:order' => 'Порядок',
	'Class:lnkTriggerAction/Attribute:order+' => 'Порядок виконання дій',
));

//
// Synchro Data Source
//
Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
	'Class:SynchroDataSource' => 'Джерело синхронізації даних',
	'Class:SynchroDataSource/Attribute:name' => 'Назва',
	'Class:SynchroDataSource/Attribute:name+' => '',
	'Class:SynchroDataSource/Attribute:description' => 'Опис',
	'Class:SynchroDataSource/Attribute:status' => 'Статус',
	'Class:SynchroDataSource/Attribute:scope_class' => 'Цільовий клас',
	'Class:SynchroDataSource/Attribute:scope_class+' => 'Джерело синхронізації може наповнювати тільки один клас '.ITOP_APPLICATION_SHORT.'~~',
	'Class:SynchroDataSource/Attribute:user_id' => 'Користувач',
	'Class:SynchroDataSource/Attribute:notify_contact_id' => 'Контакт для сповіщень',
	'Class:SynchroDataSource/Attribute:notify_contact_id+' => 'Контакт для сповіщень у разі помилки',
	'Class:SynchroDataSource/Attribute:url_icon' => 'Іконка (посилання)',
	'Class:SynchroDataSource/Attribute:url_icon+' => 'Гіперпосилання на іконку застосунку-джерела даних для відображення на сторінках синхронізованих обʼєктів',
	'Class:SynchroDataSource/Attribute:url_application' => 'Додаток (посилання)',
	'Class:SynchroDataSource/Attribute:url_application+' => 'Гіперпосилання на обʼєкт у додатку-джерелі даних. Можливі шаблони: $this->attribute$ і $replica->primary_key$',
	'Class:SynchroDataSource/Attribute:reconciliation_policy' => 'Політика зіставлення',
	'Class:SynchroDataSource/Attribute:reconciliation_policy+' => '"Використовувати атрибути": обʼєкт '.ITOP_APPLICATION_SHORT.' співпадає із значеннями репліки для кожного атрибута, позначеного для зіставлення.
"Використовувати primary_key": колонка primary_key репліки має містити ідентифікатор обʼєкта '.ITOP_APPLICATION_SHORT.'~~',
	'Class:SynchroDataSource/Attribute:full_load_periodicity' => 'Інтервал застарівання',
	'Class:SynchroDataSource/Attribute:full_load_periodicity+' => 'Обʼєкт вважається застарілим, якщо дані про нього у таблиці синхронізації не оновлювалися протягом цього інтервалу.',
	'Class:SynchroDataSource/Attribute:action_on_zero' => 'Дія при нулі',
	'Class:SynchroDataSource/Attribute:action_on_zero+' => 'Дія, якщо обʼєкт не знайдено',
	'Class:SynchroDataSource/Attribute:action_on_one' => 'Дія при одиниці',
	'Class:SynchroDataSource/Attribute:action_on_one+' => 'Дія, якщо знайдено лише один обʼєкт',
	'Class:SynchroDataSource/Attribute:action_on_multiple' => 'Дія при множині',
	'Class:SynchroDataSource/Attribute:action_on_multiple+' => 'Дія, якщо знайдено кілька обʼєктів',
	'Class:SynchroDataSource/Attribute:user_delete_policy' => 'Авторизовані користувачі',
	'Class:SynchroDataSource/Attribute:user_delete_policy+' => 'Кому дозволено видаляти синхронізовані обʼєкти',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:never' => 'Ніхто',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:depends' => 'Тільки адміністратори',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:always' => 'Всі дозволені користувачі',
	'Class:SynchroDataSource/Attribute:delete_policy_update' => 'Оновлювані атрибути',
	'Class:SynchroDataSource/Attribute:delete_policy_update+' => 'Формат: field_name:value; ... Приклад: status:inactive',
	'Class:SynchroDataSource/Attribute:delete_policy_retention' => 'Термін зберігання',
	'Class:SynchroDataSource/Attribute:delete_policy_retention+' => 'Як довго зберігати застарілі обʼєкти перед їх видаленням',
	'Class:SynchroDataSource/Attribute:database_table_name' => 'Таблиця даних',
	'Class:SynchroDataSource/Attribute:database_table_name+' => 'Імʼя таблиці для зберігання даних синхронізації. Якщо залишити порожнім — призначиться імʼя за замовчуванням.',
	'Class:SynchroDataSource/Attribute:status/Value:implementation' => 'Впровадження',
	'Class:SynchroDataSource/Attribute:status/Value:obsolete' => 'Застарілий',
	'Class:SynchroDataSource/Attribute:status/Value:production' => 'Експлуатація',
	'Class:SynchroDataSource/Attribute:scope_restriction' => 'Обмеження обсягу',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_attributes' => 'Використовувати атрибути',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_primary_key' => 'Використовувати primary_key',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:create' => 'Створити',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:error' => 'Помилка',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:error' => 'Помилка',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:update' => 'Оновити',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:create' => 'Створити',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:error' => 'Помилка',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:take_first' => 'Взяти перший (випадково)',
	'Class:SynchroDataSource/Attribute:delete_policy' => 'Застарілі обʼєкти',
	'Class:SynchroDataSource/Attribute:delete_policy+' => 'Що робити, якщо репліка стає застарілою:
"Ігнорувати" — нічого не робити, повʼязаний обʼєкт залишається в iTop.
"Видалити" — видалити повʼязаний обʼєкт у iTop (і репліку у таблиці даних).
"Оновити" — оновити повʼязаний обʼєкт згідно з правилами оновлення (див. нижче).
"Оновити, потім видалити" — застосувати правила оновлення, після завершення терміну зберігання — видалити.~~',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:delete' => 'Видалити',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:ignore' => 'Ігнорувати',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update' => 'Оновити',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update_then_delete' => 'Оновити, потім видалити',
	'Class:SynchroDataSource/Attribute:attribute_list' => 'Атрибути',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:administrators' => 'Тільки адміністратори',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:everybody' => 'Користувачі з правами на видалення',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:nobody' => 'Ніхто',
	'SynchroDataSource:Description' => 'Опис',
	'SynchroDataSource:Reconciliation' => 'Пошук і зіставлення',
	'SynchroDataSource:Deletion' => 'Застарівання та видалення',
	'SynchroDataSource:Status' => 'Статус',
	'SynchroDataSource:Information' => 'Інформація',
	'SynchroDataSource:Definition' => 'Визначення',
	'Core:SynchroAttributes' => 'Атрибути',
	'Core:SynchroStatus' => 'Властивості',
	'Core:Synchro:ErrorsLabel' => 'Помилки',
	'Core:Synchro:CreatedLabel' => 'Створено',
	'Core:Synchro:ModifiedLabel' => 'Змінено',
	'Core:Synchro:UnchangedLabel' => 'Не змінено',
	'Core:Synchro:ReconciledErrorsLabel' => 'Помилки',
	'Core:Synchro:ReconciledLabel' => 'Узгоджено',
	'Core:Synchro:ReconciledNewLabel' => 'Створено',
	'Core:SynchroReconcile:Yes' => 'Так',
	'Core:SynchroReconcile:No' => 'Ні',
	'Core:SynchroUpdate:Yes' => 'Так',
	'Core:SynchroUpdate:No' => 'Ні',
	'Core:Synchro:LastestStatus' => 'Останній статус',
	'Core:Synchro:History' => 'Історія синхронізацій',
	'Core:Synchro:NeverRun' => 'Синхронізація не запускалась. Логів немає.',
	'Core:Synchro:SynchroEndedOn_Date' => 'Синхронізація завершена %1$s.',
	'Core:Synchro:SynchroRunningStartedOn_Date' => 'Синхронізація розпочата %1$s, зараз виконується...',
	'Core:Synchro:label_repl_ignored' => 'Ігнор. (%1$s)',
	'Core:Synchro:label_repl_disappeared' => 'Зник. (%1$s)',
	'Core:Synchro:label_repl_existing' => 'Існуючий (%1$s)~~',
	'Core:Synchro:label_repl_new' => 'Новий (%1$s)',
	'Core:Synchro:label_obj_deleted' => 'Видалений (%1$s)',
	'Core:Synchro:label_obj_obsoleted' => 'Застарілий (%1$s)~~',
	'Core:Synchro:label_obj_disappeared_errors' => 'Помилки (%1$s)',
	'Core:Synchro:label_obj_disappeared_no_action' => 'Без дій (%1$s)~~',
	'Core:Synchro:label_obj_unchanged' => 'Не змінено (%1$s)~~',
	'Core:Synchro:label_obj_updated' => 'Оновлено (%1$s)',
	'Core:Synchro:label_obj_updated_errors' => 'Помилки (%1$s)',
	'Core:Synchro:label_obj_new_unchanged' => 'Не змінено (%1$s)~~',
	'Core:Synchro:label_obj_new_updated' => 'Оновлено (%1$s)',
	'Core:Synchro:label_obj_created' => 'Створено (%1$s)',
	'Core:Synchro:label_obj_new_errors' => 'Помилки (%1$s)',
	'Core:SynchroLogTitle' => '%1$s - %2$s~~',
	'Core:Synchro:Nb_Replica' => 'Оброблено реплік: %1$s~~',
	'Core:Synchro:Nb_Class:Objects' => '%1$s: %2$s~~',
	'Class:SynchroDataSource/Error:AtLeastOneReconciliationKeyMustBeSpecified' => 'Мінімум один атрибут має бути вибраний для пошуку і зіставлення обʼєктів, або використовуйте політику зіставлення по primary_key.',
	'Class:SynchroDataSource/Error:DeleteRetentionDurationMustBeSpecified' => 'Термін зберігання повинен бути вказаний, бо обʼєкти мають бути видалені після позначення як застарілі.',
	'Class:SynchroDataSource/Error:DeletePolicyUpdateMustBeSpecified' => 'Застарілі обʼєкти мають бути оновлені, але спосіб оновлення не вказано.',
	'Class:SynchroDataSource/Error:DataTableAlreadyExists' => 'Таблиця %1$s вже існує у базі даних. Використайте іншу назву для таблиці даних цього джерела.',
	// ... (Далі можна продовжувати аналогічно, якщо потрібно більше полів)
	'Menu:DataSources' => 'Синхронізація даних',
	'Menu:DataSources+' => ''
));

//
// Class: TagSetFieldData
//
Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
	'Class:TagSetFieldData' => '%2$s для класу %1$s',
	'Class:TagSetFieldData+' => '',
	'Class:TagSetFieldData/Attribute:code' => 'Код',
	'Class:TagSetFieldData/Attribute:code+' => 'Внутрішній код. Має містити не менше 3 алфавітно-цифрових символів.',
	'Class:TagSetFieldData/Attribute:label' => 'Мітка',
	'Class:TagSetFieldData/Attribute:label+' => 'Відображувана мітка',
	'Class:TagSetFieldData/Attribute:description' => 'Опис',
	'Class:TagSetFieldData/Attribute:description+' => '',
	'Class:TagSetFieldData/Attribute:finalclass' => 'Клас тегу',
	'Class:TagSetFieldData/Attribute:obj_class' => 'Клас обʼєкта',
	'Class:TagSetFieldData/Attribute:obj_attcode' => 'Код поля',
	'Core:TagSetFieldData:ErrorDeleteUsedTag' => 'Використані теги не можна видаляти',
	'Core:TagSetFieldData:ErrorDuplicateTagCodeOrLabel' => 'Коди та мітки тегів мають бути унікальними',
	'Core:TagSetFieldData:ErrorTagCodeSyntax' => 'Код тегу має містити від 3 до %1$d алфавітно-цифрових символів',
	'Core:TagSetFieldData:ErrorTagCodeReservedWord' => 'Обраний код тегу є зарезервованим словом',
	'Core:TagSetFieldData:ErrorTagLabelSyntax' => 'Мітка тегу не повинна містити «%1$s» або бути порожньою',
	'Core:TagSetFieldData:ErrorCodeUpdateNotAllowed' => 'Код тегу не можна змінювати під час використання',
	'Core:TagSetFieldData:ErrorClassUpdateNotAllowed' => 'Не можна змінити "Клас обʼєкта" тегу',
	'Core:TagSetFieldData:ErrorAttCodeUpdateNotAllowed' => 'Не можна змінити "Код поля" тегу',
	'Core:TagSetFieldData:WhereIsThisTagTab' => 'Використання тегу (%1$d)',
	'Core:TagSetFieldData:NoEntryFound' => 'Записів із цим тегом не знайдено',
));

//
// Class: DBProperty
//
Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
	'Class:DBProperty' => 'Властивість БД',
	'Class:DBProperty+' => '',
	'Class:DBProperty/Attribute:name' => "Ім'я",
	'Class:DBProperty/Attribute:name+' => '',
	'Class:DBProperty/Attribute:description' => 'Опис',
	'Class:DBProperty/Attribute:description+' => '',
	'Class:DBProperty/Attribute:value' => 'Значення',
	'Class:DBProperty/Attribute:value+' => '',
	'Class:DBProperty/Attribute:change_date' => 'Дата зміни',
	'Class:DBProperty/Attribute:change_date+' => '',
	'Class:DBProperty/Attribute:change_comment' => 'Коментар до зміни',
	'Class:DBProperty/Attribute:change_comment+' => '',
));

//
// Class: BackgroundTask
//
Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
	'Class:BackgroundTask' => 'Фонова задача',
	'Class:BackgroundTask+' => '',
	'Class:BackgroundTask/Attribute:class_name' => 'Назва класу',
	'Class:BackgroundTask/Attribute:class_name+' => '',
	'Class:BackgroundTask/Attribute:first_run_date' => 'Дата першого запуску',
	'Class:BackgroundTask/Attribute:first_run_date+' => '',
	'Class:BackgroundTask/Attribute:latest_run_date' => 'Дата останнього запуску',
	'Class:BackgroundTask/Attribute:latest_run_date+' => '',
	'Class:BackgroundTask/Attribute:next_run_date' => 'Дата наступного запуску',
	'Class:BackgroundTask/Attribute:next_run_date+' => '',
	'Class:BackgroundTask/Attribute:total_exec_count' => 'Загальна к-сть виконань',
	'Class:BackgroundTask/Attribute:total_exec_count+' => '',
	'Class:BackgroundTask/Attribute:latest_run_duration' => 'Тривалість останнього запуску',
	'Class:BackgroundTask/Attribute:latest_run_duration+' => '',
	'Class:BackgroundTask/Attribute:min_run_duration' => 'Мінімальна тривалість',
	'Class:BackgroundTask/Attribute:min_run_duration+' => '',
	'Class:BackgroundTask/Attribute:max_run_duration' => 'Максимальна тривалість',
	'Class:BackgroundTask/Attribute:max_run_duration+' => '',
	'Class:BackgroundTask/Attribute:average_run_duration' => 'Середня тривалість',
	'Class:BackgroundTask/Attribute:average_run_duration+' => '',
	'Class:BackgroundTask/Attribute:running' => 'Виконується',
	'Class:BackgroundTask/Attribute:running+' => '',
	'Class:BackgroundTask/Attribute:status' => 'Статус',
	'Class:BackgroundTask/Attribute:status+' => '',
));

//
// Class: AsyncTask
//
Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
	'Class:AsyncTask' => 'Асинхронне завдання',
	'Class:AsyncTask+' => '',
	'Class:AsyncTask/Attribute:created' => 'Створено',
	'Class:AsyncTask/Attribute:created+' => '',
	'Class:AsyncTask/Attribute:started' => 'Запущено',
	'Class:AsyncTask/Attribute:started+' => '',
	'Class:AsyncTask/Attribute:planned' => 'Заплановано',
	'Class:AsyncTask/Attribute:planned+' => '',
	'Class:AsyncTask/Attribute:event_id' => 'Подія',
	'Class:AsyncTask/Attribute:event_id+' => '',
	'Class:AsyncTask/Attribute:finalclass' => 'Кінцевий клас',
	'Class:AsyncTask/Attribute:finalclass+' => '',
	'Class:AsyncTask/Attribute:status' => 'Статус',
	'Class:AsyncTask/Attribute:status+' => '',
	'Class:AsyncTask/Attribute:remaining_retries' => 'Залишилось спроб',
	'Class:AsyncTask/Attribute:remaining_retries+' => '',
	'Class:AsyncTask/Attribute:last_error_code' => 'Код останньої помилки',
	'Class:AsyncTask/Attribute:last_error_code+' => '',
	'Class:AsyncTask/Attribute:last_error' => 'Остання помилка',
	'Class:AsyncTask/Attribute:last_error+' => '',
	'Class:AsyncTask/Attribute:last_attempt' => 'Остання спроба',
	'Class:AsyncTask/Attribute:last_attempt+' => '',
	'Class:AsyncTask:InvalidConfig_Class_Keys' => 'Некоректний формат для конфігурації "async_task_retries[%1$s]". Очікується масив із такими ключами: %2$s',
	'Class:AsyncTask:InvalidConfig_Class_InvalidKey_Keys' => 'Некоректний формат для конфігурації "async_task_retries[%1$s]": неочікуваний ключ "%2$s". Очікуються лише такі ключі: %3$s',
));

//
// Class: AbstractResource
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
	'Class:AbstractResource' => 'Ресурс',
	'Class:AbstractResource+' => '',
));

//
// Class: ResourceAdminMenu
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
	'Class:ResourceAdminMenu' => 'Меню Інструменти адміністратора',
	'Class:ResourceAdminMenu+' => '',
));

//
// Class: ResourceRunQueriesMenu
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
	'Class:ResourceRunQueriesMenu' => 'Меню Виконання запитів',
	'Class:ResourceRunQueriesMenu+' => '',
));

//
// Class: Action
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
	'Class:ResourceSystemMenu' => 'Меню Система',
	'Class:ResourceSystemMenu+' => '',
    'Class:EventNotification/Attribute:object_class' => 'Клас об’єкта',
    'Class:EventNotification/Attribute:object_class+' => 'Клас об’єкта (такий самий, як у тригера)',
	'Core:EventNotificationNewsroom:ErrorNotificationNotSent' => 'Сповіщення не надіслано',
	'Core:EventNotificationNewsroom:ErrorOnDBInsert' => 'Сталася помилка під час збереження сповіщення',
));
