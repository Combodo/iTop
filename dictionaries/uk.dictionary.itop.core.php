<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2026 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * 
 */
Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Core:DeletedObjectLabel' => '%1$s (видалено)',
	'Core:DeletedObjectTip' => 'Об\'єкт був видалений %1$s (%2$s)',
	'Core:UnknownObjectLabel' => 'Об\'єкт не знайдено (клас: %1$s, id: %2$d)',
	'Core:UnknownObjectTip' => 'Об\'єкт не вдається знайти. Можливо, він був видалений деякий час тому, і журнал з того часу був очищений.',
	'Core:UniquenessDefaultError' => 'Помилка правила унікальності \'%1$s\'',
	'Core:CheckConsistencyError' => 'Правила узгодженості не дотримані: %1$s',
	'Core:CheckValueError' => 'Неочікуване значення для атрибута \'%1$s\' (%2$s) : %3$s',
	'Core:AttributeLinkedSet' => 'Масив об\'єктів (1-n)',
	'Core:AttributeLinkedSet+' => 'Список об\'єктів заданого класу, що вказують на поточний об\'єкт',
	'Core:AttributeLinkedSetDuplicatesFound' => 'Дублікати в полі \'%1$s\' : %2$s',
	'Core:AttributeDashboard' => 'Панель інструментів',
	'Core:AttributeDashboard+' => '',
	'Core:AttributePhoneNumber' => 'Номер телефону',
	'Core:AttributePhoneNumber+' => '',
	'Core:AttributeObsolescenceDate' => 'Дата застарівання',
	'Core:AttributeObsolescenceDate+' => '',
	'Core:AttributeTagSet' => 'Список тегів',
	'Core:AttributeTagSet+' => '',
	'Core:AttributeSet:placeholder' => 'натисніть, щоб додати',
	'Core:Placeholder:CannotBeResolved' => '(%1$s : не може бути розв\'язано)',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromClass' => '%1$s (%2$s)',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromOneChildClass' => '%1$s (%2$s від %3$s)',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromSeveralChildClasses' => '%1$s (%2$s від дочірніх класів)',
	'Core:AttributeCaseLog' => 'Журнал',
	'Core:AttributeCaseLog+' => '',
	'Core:AttributeMetaEnum' => 'Обчислюваний enum',
	'Core:AttributeMetaEnum+' => '',
	'Core:AttributeLinkedSetIndirect' => 'Масив об\'єктів (n-n)',
	'Core:AttributeLinkedSetIndirect+' => 'Список об\'єктів заданого класу, пов\'язаних з поточним об\'єктом через проміжний клас',
	'Core:AttributeInteger' => 'Ціле число',
	'Core:AttributeInteger+' => 'Ціле число (може бути від\'ємним)',
	'Core:AttributeDecimal' => 'Десяткове число',
	'Core:AttributeDecimal+' => 'Десяткове число (може бути від\'ємним)',
	'Core:AttributeBoolean' => 'Логічне',
	'Core:AttributeBoolean+' => 'Так/Ні',
	'Core:AttributeBoolean/Value:null' => '',
	'Core:AttributeBoolean/Value:yes' => 'Так',
	'Core:AttributeBoolean/Value:no' => 'Ні',
	'Core:AttributeArchiveFlag' => 'Архівний прапорець',
	'Core:AttributeArchiveFlag/Value:yes' => 'Так',
	'Core:AttributeArchiveFlag/Value:yes+' => 'Цей об\'єкт видимий тільки в режимі архіву',
	'Core:AttributeArchiveFlag/Value:no' => 'Ні',
	'Core:AttributeArchiveFlag/Label' => 'Архівний',
	'Core:AttributeArchiveFlag/Label+' => '',
	'Core:AttributeArchiveDate/Label' => 'Дата архівування',
	'Core:AttributeArchiveDate/Label+' => '',
	'Core:AttributeObsolescenceFlag' => 'Застарілий прапорець',
	'Core:AttributeObsolescenceFlag/Value:yes' => 'Так',
	'Core:AttributeObsolescenceFlag/Value:yes+' => 'Цей об\'єкт виключено з аналізу впливу і прихований з результатів пошуку',
	'Core:AttributeObsolescenceFlag/Value:no' => 'Ні',
	'Core:AttributeObsolescenceFlag/Label' => 'Застарілий',
	'Core:AttributeObsolescenceFlag/Label+' => 'Обчислюється динамічно на основі значень інших атрибутів',
	'Core:AttributeObsolescenceDate/Label' => 'Дата застарівання',
	'Core:AttributeObsolescenceDate/Label+' => 'Приблизна дата, з якої об\'єкт вважається застарілим',
	'Core:AttributeString' => 'Рядок',
	'Core:AttributeString+' => 'Текстовий рядок',
	'Core:AttributeClass' => 'Клас',
	'Core:AttributeClass+' => 'Клас об\'єкта',
	'Core:AttributeApplicationLanguage' => 'Мова користувача',
	'Core:AttributeApplicationLanguage+' => 'Мова і країна (EN US)',
	'Core:AttributeFinalClass' => 'Клас (авто)',
	'Core:AttributeFinalClass+' => 'Справжній клас об\'єкта (автоматично створюваний ядром)',
	'Core:AttributePassword' => 'Пароль',
	'Core:AttributePassword+' => 'Пароль зовнішнього пристрою',
	'Core:AttributeEncryptedString' => 'Зашифрований рядок',
	'Core:AttributeEncryptedString+' => 'Рядок, зашифрований локальним ключем',
	'Core:AttributeEncryptUnknownLibrary' => 'Задана бібліотека шифрування (%1$s) невідома',
	'Core:AttributeEncryptFailedToDecrypt' => '** помилка розшифрування **',
	'Core:AttributeText' => 'Текст',
	'Core:AttributeText+' => 'Багаторядковий текст',
	'Core:AttributeHTML' => 'HTML',
	'Core:AttributeHTML+' => '',
	'Core:AttributeEmailAddress' => 'Email',
	'Core:AttributeEmailAddress+' => 'Email адреса',
	'Core:AttributeIPAddress' => 'IP адреса',
	'Core:AttributeIPAddress+' => '',
	'Core:AttributeOQL' => 'OQL',
	'Core:AttributeOQL+' => 'Вираз мови запиту об\'єкта (Object Query Language)',
	'Core:AttributeEnum' => 'Enum',
	'Core:AttributeEnum+' => 'Список попередньо визначених текстових рядків',
	'Core:AttributeTemplateString' => 'Шаблон рядка',
	'Core:AttributeTemplateString+' => 'Рядок, що містить заповнювачі',
	'Core:AttributeTemplateText' => 'Шаблон тексту',
	'Core:AttributeTemplateText+' => 'Текст, що містить заповнювачі',
	'Core:AttributeTemplateHTML' => 'Шаблон HTML',
	'Core:AttributeTemplateHTML+' => 'HTML, що містить заповнювачі',
	'Core:AttributeDateTime' => 'Дата/час',
	'Core:AttributeDateTime+' => 'Дата і час (гггг-мм-дд чч:мм:сс)',
	'Core:AttributeDateTime?SmartSearch' => ' <p> Формат дати:<br/> <b>рррр-мм-дд гг:хх:сс</b><br/> Приклад: 2017-11-27 19:17:00 </p> <p> Оператори:<br/> <b>&gt;</b><em>дата</em><br/> <b>&lt;</b><em>дата</em><br/> <b>[</b><em>дата</em>,<em>дата</em><b>]</b> </p> <p> Якщо час не вказано, за замовчуванням використовується 00:00:00 </p>',
	'Core:AttributeDate' => 'Дата',
	'Core:AttributeDate+' => 'Дата (рррр-мм-дд)',
	'Core:AttributeDate?SmartSearch' => ' <p> Формат дати:<br/> <b>рррр-мм-дд</b><br/> Приклад: 2017-11-27 </p> <p> Оператори:<br/> <b>&gt;</b><em>дата</em><br/> <b>&lt;</b><em>дата</em><br/> <b>[</b><em>дата</em>,<em>дата</em><b>]</b> </p>',
	'Core:AttributeDeadline' => 'Кінцевий термін',
	'Core:AttributeDeadline+' => 'Дата, що відображається відносно поточного часу',
	'Core:AttributeExternalKey' => 'Зовнішній ключ',
	'Core:AttributeExternalKey+' => '',
	'Core:AttributeHierarchicalKey' => 'Ієрархічний ключ',
	'Core:AttributeHierarchicalKey+' => 'Зовнішній ключ до батьківського об\'єкта',
	'Core:AttributeExternalField' => 'Зовнішнє поле',
	'Core:AttributeExternalField+' => 'Поле, зіставлене із зовнішнім ключем',
	'Core:AttributeURL' => 'URL',
	'Core:AttributeURL+' => 'Абсолютний або відносний URL у вигляді текстового рядка',
	'Core:AttributeBlob' => 'Blob',
	'Core:AttributeBlob+' => 'Будь-який двійковий контент (документ)',
	'Core:AttributeOneWayPassword' => 'Хешований пароль',
	'Core:AttributeOneWayPassword+' => 'Зашифрований (хешований) пароль',
	'Core:AttributeTable' => 'Таблиця',
	'Core:AttributeTable+' => 'Індексований масив з двома вимірами',
	'Core:AttributePropertySet' => 'Властивості',
	'Core:AttributePropertySet+' => 'Список нетипізованих властивостей (ім\'я і значення)',
	'Core:AttributeFriendlyName' => 'Повна назва',
	'Core:AttributeFriendlyName+' => 'Атрибут створюється автоматично; повна назва обчислюється з декількох атрибутів',
	'Core:FriendlyName-Label' => 'Повна назва',
	'Core:FriendlyName-Description' => 'Повна назва',
	'Core:AttributeTag' => 'Тег',
	'Core:AttributeTag+' => '',
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

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:CMDBChange' => 'Зміна CMDB',
	'Class:CMDBChange+' => 'Відстеження змін CMDB',
	'Class:CMDBChange/Attribute:date' => 'Дата',
	'Class:CMDBChange/Attribute:date+' => 'Дата і час зміни',
	'Class:CMDBChange/Attribute:userinfo' => 'Користувач',
	'Class:CMDBChange/Attribute:userinfo+' => 'Хто виконав зміну',
	'Class:CMDBChange/Attribute:origin/Value:interactive' => 'Взаємодія користувача в GUI',
	'Class:CMDBChange/Attribute:origin/Value:csv-import.php' => 'Скрипт імпорту CSV',
	'Class:CMDBChange/Attribute:origin/Value:csv-interactive' => 'Імпорт CSV в GUI',
	'Class:CMDBChange/Attribute:origin/Value:email-processing' => 'Обробка електронної пошти',
	'Class:CMDBChange/Attribute:origin/Value:synchro-data-source' => 'Джерело даних синхронізації',
	'Class:CMDBChange/Attribute:origin/Value:webservice-rest' => 'Веб-сервіси REST/JSON',
	'Class:CMDBChange/Attribute:origin/Value:webservice-soap' => 'Веб-сервіси SOAP',
	'Class:CMDBChange/Attribute:origin/Value:custom-extension' => 'Через розширення',
));

//
// Class: CMDBChangeOp
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:CMDBChangeOp' => 'Операція зміни CMDB',
	'Class:CMDBChangeOp+' => 'Відстеження операції зміни CMDB',
	'Class:CMDBChangeOp/Attribute:change' => 'Зміна CMDB',
	'Class:CMDBChangeOp/Attribute:change+' => '',
	'Class:CMDBChangeOp/Attribute:date' => 'Дата',
	'Class:CMDBChangeOp/Attribute:date+' => 'Дата і час зміни',
	'Class:CMDBChangeOp/Attribute:userinfo' => 'Користувач',
	'Class:CMDBChangeOp/Attribute:userinfo+' => 'Хто виконав зміну',
	'Class:CMDBChangeOp/Attribute:objclass' => 'Клас об\'єкта',
	'Class:CMDBChangeOp/Attribute:objclass+' => '',
	'Class:CMDBChangeOp/Attribute:objkey' => 'ID об\'єкта',
	'Class:CMDBChangeOp/Attribute:objkey+' => '',
	'Class:CMDBChangeOp/Attribute:finalclass' => 'Підсумковий клас',
	'Class:CMDBChangeOp/Attribute:finalclass+' => '',
));

//
// Class: CMDBChangeOpCreate
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:CMDBChangeOpCreate' => 'Операція створення об\'єкта',
	'Class:CMDBChangeOpCreate+' => 'Відстеження створення об\'єкта',
));

//
// Class: CMDBChangeOpDelete
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:CMDBChangeOpDelete' => 'Операція видалення об\'єкта',
	'Class:CMDBChangeOpDelete+' => 'Відстеження видалення об\'єкта',
));

//
// Class: CMDBChangeOpSetAttribute
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:CMDBChangeOpSetAttribute' => 'Зміна об\'єкта',
	'Class:CMDBChangeOpSetAttribute+' => 'Відстеження зміни об\'єкта',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode' => 'Властивість',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode+' => 'Код зміненої властивості',
));

//
// Class: CMDBChangeOpSetAttributeScalar
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:CMDBChangeOpSetAttributeScalar' => 'Зміна властивості',
	'Class:CMDBChangeOpSetAttributeScalar+' => 'Відстеження зміни скалярної властивості об\'єкта',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue' => 'Попереднє значення',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue+' => 'Попереднє значення атрибута',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue' => 'Нове значення',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue+' => 'Нове значення атрибута',
));
// Used by CMDBChangeOp... & derived classes
Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Change:ObjectCreated' => 'Об\'єкт створено.',
	'Change:ObjectDeleted' => 'Об\'єкт видалено.',
	'Change:ObjectModified' => 'Об\'єкт змінено.',
	'Change:TwoAttributesChanged' => 'Змінено %1$s і %2$s',
	'Change:ThreeAttributesChanged' => 'Змінено %1$s, %2$s і ще 1',
	'Change:FourOrMoreAttributesChanged' => 'Змінено %1$s, %2$s і ще %3$s',
	'Change:AttName_SetTo_NewValue_PreviousValue_OldValue' => 'У полі %1$s встановлено значення %2$s (попереднє значення %3$s).',
	'Change:AttName_SetTo' => 'У полі %1$s встановлено значення %2$s.',
	'Change:Text_AppendedTo_AttName' => 'Нове значення %1$s додано до поля %2$s.',
	'Change:AttName_Changed_PreviousValue_OldValue' => 'Поле %1$s змінено (попереднє значення %2$s).',
	'Change:AttName_Changed' => 'Поле %1$s змінено.',
	'Change:AttName_EntryAdded' => 'У поле %1$s додано нове значення.',
	'Change:State_Changed_NewValue_OldValue' => 'Перехід з %2$s у %1$s',
	'Change:LinkSet:Added' => 'додано об\'єкт %1$s.',
	'Change:LinkSet:Removed' => 'видалено об\'єкт %1$s.',
	'Change:LinkSet:Modified' => 'змінено об\'єкт %1$s.',
));

//
// Class: CMDBChangeOpSetAttributeBlob
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:CMDBChangeOpSetAttributeBlob' => 'Зміна даних',
	'Class:CMDBChangeOpSetAttributeBlob+' => 'Відстеження зміни даних',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata' => 'Попередні дані',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata+' => 'Попередній вміст атрибута',
));

//
// Class: CMDBChangeOpSetAttributeText
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:CMDBChangeOpSetAttributeText' => 'Зміна тексту',
	'Class:CMDBChangeOpSetAttributeText+' => 'Відстеження зміни тексту',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata' => 'Попередні дані',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata+' => 'Попередній вміст атрибута',
));

//
// Class: Event
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:Event' => 'Подія',
	'Class:Event+' => 'Внутрішня подія застосунку',
	'Class:Event/Attribute:message' => 'Повідомлення',
	'Class:Event/Attribute:message+' => 'Короткий опис події',
	'Class:Event/Attribute:date' => 'Дата',
	'Class:Event/Attribute:date+' => 'Дата і час реєстрації події',
	'Class:Event/Attribute:userinfo' => 'Користувач',
	'Class:Event/Attribute:userinfo+' => 'Користувач, дії якого викликали цю подію',
	'Class:Event/Attribute:finalclass' => 'Тип',
	'Class:Event/Attribute:finalclass+' => '',
));

//
// Class: EventNotification
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:EventNotification' => 'Сповіщення',
	'Class:EventNotification+' => 'Відстеження надісланих сповіщень',
	'Class:EventNotification/Attribute:trigger_id' => 'Тригер',
	'Class:EventNotification/Attribute:trigger_id+' => 'Спрацьований тригер',
	'Class:EventNotification/Attribute:action_id' => 'Дія',
	'Class:EventNotification/Attribute:action_id+' => 'Виконана дія',
	'Class:EventNotification/Attribute:object_id' => 'ID об\'єкта',
	'Class:EventNotification/Attribute:object_id+' => 'Ідентифікатор об\'єкта цільового класу тригера',
));

//
// Class: EventNotificationEmail
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:EventNotificationEmail' => 'Сповіщення через email',
	'Class:EventNotificationEmail+' => 'Відстеження сповіщень через email',
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

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:EventIssue' => 'Помилка',
	'Class:EventIssue+' => 'Відстеження помилок (warning, error, тощо)',
	'Class:EventIssue/Attribute:issue' => 'Помилка',
	'Class:EventIssue/Attribute:issue+' => 'Що сталося',
	'Class:EventIssue/Attribute:impact' => 'Вплив',
	'Class:EventIssue/Attribute:impact+' => 'Наслідки',
	'Class:EventIssue/Attribute:page' => 'Сторінка',
	'Class:EventIssue/Attribute:page+' => 'Точка входу HTTP',
	'Class:EventIssue/Attribute:arguments_post' => 'Аргументи POST',
	'Class:EventIssue/Attribute:arguments_post+' => 'Аргументи HTTP POST',
	'Class:EventIssue/Attribute:arguments_get' => 'Аргументи GET',
	'Class:EventIssue/Attribute:arguments_get+' => 'Аргументи HTTP GET',
	'Class:EventIssue/Attribute:callstack' => 'Стек викликів',
	'Class:EventIssue/Attribute:callstack+' => '',
	'Class:EventIssue/Attribute:data' => 'Дані',
	'Class:EventIssue/Attribute:data+' => 'Детальніше',
));

//
// Class: EventWebService
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:EventWebService' => 'Подія веб-сервісу',
	'Class:EventWebService+' => 'Відстеження виклику веб-сервісу',
	'Class:EventWebService/Attribute:verb' => 'Дієслово',
	'Class:EventWebService/Attribute:verb+' => 'Назва операції',
	'Class:EventWebService/Attribute:result' => 'Результат',
	'Class:EventWebService/Attribute:result+' => 'Успіх/невдача',
	'Class:EventWebService/Attribute:log_info' => 'Журнал',
	'Class:EventWebService/Attribute:log_info+' => 'Результати журналу',
	'Class:EventWebService/Attribute:log_warning' => 'Журнал попереджень',
	'Class:EventWebService/Attribute:log_warning+' => 'Результати журналу попереджень',
	'Class:EventWebService/Attribute:log_error' => 'Журнал помилок',
	'Class:EventWebService/Attribute:log_error+' => 'Результати журналу помилок',
	'Class:EventWebService/Attribute:data' => 'Дані',
	'Class:EventWebService/Attribute:data+' => 'Результати даних',
));

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:EventRestService' => 'Виклик REST/JSON',
	'Class:EventRestService+' => 'Відстеження виклику REST/JSON сервісу',
	'Class:EventRestService/Attribute:operation' => 'Операція',
	'Class:EventRestService/Attribute:operation+' => 'Аргумент \'operation\'',
	'Class:EventRestService/Attribute:version' => 'Версія',
	'Class:EventRestService/Attribute:version+' => 'Аргумент \'version\'',
	'Class:EventRestService/Attribute:json_input' => 'Вхідні дані',
	'Class:EventRestService/Attribute:json_input+' => 'Аргумент \'json_data\'',
	'Class:EventRestService/Attribute:code' => 'Код',
	'Class:EventRestService/Attribute:code+' => 'Код результату',
	'Class:EventRestService/Attribute:json_output' => 'Відповідь',
	'Class:EventRestService/Attribute:json_output+' => 'HTTP відповідь (json)',
	'Class:EventRestService/Attribute:provider' => 'Провайдер',
	'Class:EventRestService/Attribute:provider+' => 'PHP клас, що реалізує очікувану операцію',
));

//
// Class: EventLoginUsage
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:EventLoginUsage' => 'Статистика авторизацій',
	'Class:EventLoginUsage+' => 'Підключення до застосунку',
	'Class:EventLoginUsage/Attribute:user_id' => 'Логін',
	'Class:EventLoginUsage/Attribute:user_id+' => 'Логін',
	'Class:EventLoginUsage/Attribute:contact_name' => 'Ім\'я користувача',
	'Class:EventLoginUsage/Attribute:contact_name+' => 'Ім\'я користувача',
	'Class:EventLoginUsage/Attribute:contact_email' => 'Email користувача',
	'Class:EventLoginUsage/Attribute:contact_email+' => 'Email адреса користувача',
));

//
// Class: EventNotificationNewsroom
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:EventNotificationNewsroom' => 'Новини надіслано',
	'Class:EventNotificationNewsroom+' => '',
	'Class:EventNotificationNewsroom/Attribute:title' => 'Заголовок',
	'Class:EventNotificationNewsroom/Attribute:title+' => '',
	'Class:EventNotificationNewsroom/Attribute:icon' => 'Іконка',
	'Class:EventNotificationNewsroom/Attribute:icon+' => '',
	'Class:EventNotificationNewsroom/Attribute:priority' => 'Пріоритет',
	'Class:EventNotificationNewsroom/Attribute:priority+' => '',
	'Class:EventNotificationNewsroom/Attribute:priority/Value:1' => 'Критичний',
	'Class:EventNotificationNewsroom/Attribute:priority/Value:1+' => 'Критичний',
	'Class:EventNotificationNewsroom/Attribute:priority/Value:2' => 'Терміновий',
	'Class:EventNotificationNewsroom/Attribute:priority/Value:2+' => 'Терміновий',
	'Class:EventNotificationNewsroom/Attribute:priority/Value:3' => 'Важливий',
	'Class:EventNotificationNewsroom/Attribute:priority/Value:3+' => 'Важливий',
	'Class:EventNotificationNewsroom/Attribute:priority/Value:4' => 'Стандартний',
	'Class:EventNotificationNewsroom/Attribute:priority/Value:4+' => 'Стандартний',
	'Class:EventNotificationNewsroom/Attribute:url' => 'URL',
	'Class:EventNotificationNewsroom/Attribute:url+' => '',
	'Class:EventNotificationNewsroom/Attribute:read' => 'Прочитано',
	'Class:EventNotificationNewsroom/Attribute:read+' => '',
	'Class:EventNotificationNewsroom/Attribute:read/Value:no' => 'Ні',
	'Class:EventNotificationNewsroom/Attribute:read/Value:no+' => 'Ні',
	'Class:EventNotificationNewsroom/Attribute:read/Value:yes' => 'Так',
	'Class:EventNotificationNewsroom/Attribute:read/Value:yes+' => 'Так',
	'Class:EventNotificationNewsroom/Attribute:read_date' => 'Дата прочитання',
	'Class:EventNotificationNewsroom/Attribute:read_date+' => '',
	'Class:EventNotificationNewsroom/Attribute:contact_id' => 'Контакт',
	'Class:EventNotificationNewsroom/Attribute:contact_id+' => '',
));

//
// Class: Action
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
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
	'Class:Action/Attribute:trigger_list' => 'Пов\'язані тригери',
	'Class:Action/Attribute:trigger_list+' => 'Тригери, які запускають дану дію',
	'Class:Action/Attribute:asynchronous' => 'Асинхронна',
	'Class:Action/Attribute:asynchronous+' => 'Чи має ця дія виконуватися у фоновому режимі',
	'Class:Action/Attribute:asynchronous/Value:use_global_setting' => 'Використовувати глобальне налаштування',
	'Class:Action/Attribute:asynchronous/Value:yes' => 'Так',
	'Class:Action/Attribute:asynchronous/Value:no' => 'Ні',
	'Class:Action/Attribute:finalclass' => 'Тип',
	'Class:Action/Attribute:finalclass+' => '',
	'Action:WarningNoTriggerLinked' => 'Увага, жоден тригер не пов\'язаний з дією. Вона не буде активна, поки не буде призначено принаймні 1 тригер.',
	'Action:last_executions_tab' => 'Останні виконання',
	'Action:last_executions_tab_panel_title' => 'Виконання цієї дії (%1$s)',
	'Action:last_executions_tab_limit_days' => 'останні %1$s днів',
	'Action:last_executions_tab_limit_none' => 'без обмежень',
));

//
// Class: ActionNotification
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:ActionNotification' => 'Сповіщення',
	'Class:ActionNotification+' => '',
	'Class:ActionNotification/Attribute:language' => 'Мова',
	'Class:ActionNotification/Attribute:language+' => '',
));

//
// Class: ActionEmail
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:ActionEmail' => 'Сповіщення через email',
	'Class:ActionEmail+' => '',
	'Class:ActionEmail/Attribute:status+' => 'Цей статус визначає, хто буде сповіщений:
- У тестуванні: тільки тестовий отримувач,
- У роботі: всі (To, cc і Bcc)
- Неактивний: ніхто',
	'Class:ActionEmail/Attribute:status/Value:test+' => 'Сповіщається тільки тестовий отримувач',
	'Class:ActionEmail/Attribute:status/Value:enabled+' => 'Всі адреси To, Cc і Bcc отримають сповіщення',
	'Class:ActionEmail/Attribute:status/Value:disabled+' => 'Email сповіщення не буде надіслано',
	'Class:ActionEmail/Attribute:test_recipient' => 'Тестовий отримувач',
	'Class:ActionEmail/Attribute:test_recipient+' => 'Отримувач, якщо сповіщення в статусі "Тест"',
	'Class:ActionEmail/Attribute:from' => 'Від',
	'Class:ActionEmail/Attribute:from+' => 'Буде відправлено в заголовку email',
	'Class:ActionEmail/Attribute:from_label' => 'Від (мітка)',
	'Class:ActionEmail/Attribute:from_label+' => 'Або статична мітка, або заповнювач типу $this->agent_id->friendlyname$',
	'Class:ActionEmail/Attribute:reply_to' => 'Відповісти на',
	'Class:ActionEmail/Attribute:reply_to+' => 'Буде відправлено в заголовку email',
	'Class:ActionEmail/Attribute:reply_to_label' => 'Відповісти на (мітка)',
	'Class:ActionEmail/Attribute:reply_to_label+' => 'Або статична мітка, або заповнювач типу $this->team_id->friendlyname$.
Якщо не вказано, використовується Від (мітка).',
	'Class:ActionEmail/Attribute:to' => 'Кому',
	'Class:ActionEmail/Attribute:to+' => 'Отримувач email',
	'Class:ActionEmail/Attribute:cc' => 'Копія',
	'Class:ActionEmail/Attribute:cc+' => '',
	'Class:ActionEmail/Attribute:bcc' => 'Прихована копія',
	'Class:ActionEmail/Attribute:bcc+' => 'Прихована копія',
	'Class:ActionEmail/Attribute:subject' => 'Тема',
	'Class:ActionEmail/Attribute:subject+' => 'Заголовок листа',
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
	'Class:ActionEmail/Attribute:language+' => 'Мова для використання в заповнювачах ($xxx$) всередині повідомлення (стан, важливість, пріоритет тощо)',
	'Class:ActionEmail/Attribute:html_template' => 'HTML шаблон',
	'Class:ActionEmail/Attribute:html_template+' => 'Додатковий HTML шаблон, що обгортає вміст атрибута \'Тіло\' нижче, корисний для індивідуальних макетів email (у шаблоні вміст атрибута \'Тіло\' замінить заповнювач $content$)',
	'Class:ActionEmail/Attribute:ignore_notify' => 'Ігнорувати прапорець Сповіщення',
	'Class:ActionEmail/Attribute:ignore_notify+' => 'Якщо встановлено \'Так\', прапорець \'Сповіщення\' на Контактах не має ефекту.',
	'Class:ActionEmail/Attribute:ignore_notify/Value:no' => 'Ні',
	'Class:ActionEmail/Attribute:ignore_notify/Value:yes' => 'Так',
	'ActionEmail:main' => 'Повідомлення',
	'ActionEmail:trigger' => 'Тригери',
	'ActionEmail:recipients' => 'Контакти',
	'ActionEmail:preview_tab' => 'Попередній перегляд',
	'ActionEmail:preview_tab+' => 'Попередній перегляд шаблону email',
	'ActionEmail:preview_warning' => 'Фактичний email може виглядати інакше в email клієнті, ніж цей попередній перегляд у вашому браузері.',
	'ActionEmail:preview_more_info' => 'Для додаткової інформації про функції CSS, підтримувані різними email клієнтами, зверніться до %1$s',
	'ActionEmail:content_placeholder_missing' => 'Заповнювач "%1$s" не знайдено в HTML шаблоні. Вміст поля "%2$s" не буде включено у створені email.',
));


//
// Class: ActionNewsroom
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'ActionNewsroom:trigger' => 'Тригер',
	'ActionNewsroom:content' => 'Повідомлення',
	'ActionNewsroom:settings' => 'Налаштування',
	'Class:ActionNewsroom' => 'Сповіщення через Новини',
	'Class:ActionNewsroom+' => '',
	'Class:ActionNewsroom/Attribute:title' => 'Заголовок',
	'Class:ActionNewsroom/Attribute:title+' => 'Заголовок новини. Може містити заповнювачі типу $this->attribute_code$',
	'Class:ActionNewsroom/Attribute:message' => 'Повідомлення',
	'Class:ActionNewsroom/Attribute:message+' => 'Вміст новини у форматі Markdown, а не HTML. Може містити заповнювачі, наприклад:
- $this->attribute_code$ будь-який атрибут об\'єкта, що викликає сповіщення,
- $this->attribute_external_key->attribute$ рекурсивний синтаксис для будь-якого віддаленого атрибута,
- $current_contact->attribute$ атрибут Особи, яка викликала сповіщення',
	'Class:ActionNewsroom/Attribute:icon' => 'Іконка',
	'Class:ActionNewsroom/Attribute:icon+' => 'Іконка, що відображається поряд з новиною в стрічці новин.
- Якщо заповнено, буде використана власна іконка
- Інакше іконка об\'єкта, що викликає, якщо така є (наприклад, зображення Особи),
- Інакше іконка класу об\'єкта, що викликає,
- В іншому випадку буде використано компактне лого застосунку',
	'Class:ActionNewsroom/Attribute:priority' => 'Пріоритет',
	'Class:ActionNewsroom/Attribute:priority+' => 'Новини будуть відсортовані за зменшенням пріоритету при відображенні у спливаючому вікні Новин',
	'Class:ActionNewsroom/Attribute:priority/Value:1' => 'Критичний',
	'Class:ActionNewsroom/Attribute:priority/Value:1+' => 'Критичний',
	'Class:ActionNewsroom/Attribute:priority/Value:2' => 'Терміновий',
	'Class:ActionNewsroom/Attribute:priority/Value:2+' => 'Терміновий',
	'Class:ActionNewsroom/Attribute:priority/Value:3' => 'Важливий',
	'Class:ActionNewsroom/Attribute:priority/Value:3+' => 'Важливий',
	'Class:ActionNewsroom/Attribute:priority/Value:4' => 'Стандартний',
	'Class:ActionNewsroom/Attribute:priority/Value:4+' => 'Стандартний',
	'Class:ActionNewsroom/Attribute:test_recipient_id' => 'Тестовий отримувач',
	'Class:ActionNewsroom/Attribute:test_recipient_id+' => 'Особа, що використовується замість Отримувачів при тестуванні сповіщення',
	'Class:ActionNewsroom/Attribute:recipients' => 'Отримувачі',
	'Class:ActionNewsroom/Attribute:recipients+' => 'OQL запит, що повертає об\'єкти Контактів',
	'Class:ActionNewsroom/Attribute:url' => 'URL',
	'Class:ActionNewsroom/Attribute:url+' => 'За замовчуванням вказує на об\'єкт, що викликає сповіщення. Але ви також можете вказати власний URL.',
));

//
// Class: Trigger
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
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
	'Class:Trigger/Attribute:context+' => 'Контекст, в якому буде спрацьовувати тригер',
	'Class:Trigger/Attribute:complement' => 'Додаткова інформація',
	'Class:Trigger/Attribute:complement+' => 'Обчислюється автоматично для тригерів, похідних від TriggerOnObject',
	'Class:Trigger/Attribute:subscription_policy' => 'Політика підписки',
	'Class:Trigger/Attribute:subscription_policy+' => 'Дозволяє користувачам відписатися від тригера',
	'Class:Trigger/Attribute:subscription_policy/Value:allow_no_channel' => 'Дозволити повну відписку',
	'Class:Trigger/Attribute:subscription_policy/Value:force_at_least_one_channel' => 'Вимагати принаймні один канал (Новини або Email)',
	'Class:Trigger/Attribute:subscription_policy/Value:force_all_channels' => 'Заборонити відписку',
));

//
// Class: TriggerOnObject
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:TriggerOnObject' => 'Тригер на клас об\'єкта',
	'Class:TriggerOnObject+' => 'Тригер на події об\'єктів даного класу',
	'Class:TriggerOnObject/Attribute:target_class' => 'Цільовий клас',
	'Class:TriggerOnObject/Attribute:target_class+' => 'Клас об\'єктів, для яких буде спрацьовувати даний тригер',
	'Class:TriggerOnObject/Attribute:filter' => 'Фільтр OQL',
	'Class:TriggerOnObject/Attribute:filter+' => 'Дозволяє обмежити список об\'єктів, для яких буде спрацьовувати тригер',
	'TriggerOnObject:WrongFilterQuery' => 'Неправильний запит фільтра: %1$s',
	'TriggerOnObject:WrongFilterClass' => 'Запит фільтра повинен повертати об\'єкти класу "%1$s"',
));

//
// Class: TriggerOnPortalUpdate
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:TriggerOnPortalUpdate' => 'Тригер на оновлення з порталу',
	'Class:TriggerOnPortalUpdate+' => 'Тригер на оновлення об\'єкта користувачем порталу',
));

//
// Class: TriggerOnStateChange
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:TriggerOnStateChange' => 'Тригер на зміну статусу',
	'Class:TriggerOnStateChange+' => 'Тригер на зміну статусу об\'єкта',
	'Class:TriggerOnStateChange/Attribute:state' => 'Статус',
	'Class:TriggerOnStateChange/Attribute:state+' => 'Код статусу об\'єкта, наприклад \'resolved\'',
));

//
// Class: TriggerOnStateEnter
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:TriggerOnStateEnter' => 'Тригер на вхід у статус',
	'Class:TriggerOnStateEnter+' => 'Тригер на вхід об\'єкта у статус',
));

//
// Class: TriggerOnStateLeave
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:TriggerOnStateLeave' => 'Тригер на вихід зі статусу',
	'Class:TriggerOnStateLeave+' => 'Тригер на вихід об\'єкта зі статусу',
));

//
// Class: TriggerOnObjectCreate
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:TriggerOnObjectCreate' => 'Тригер на створення об\'єкта',
	'Class:TriggerOnObjectCreate+' => 'Тригер на створення об\'єкта даного або дочірнього класу',
));

//
// Class: TriggerOnObjectDelete
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:TriggerOnObjectDelete' => 'Тригер на видалення об\'єкта',
	'Class:TriggerOnObjectDelete+' => 'Тригер на видалення об\'єкта даного або дочірнього класу',
));

//
// Class: TriggerOnObjectUpdate
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:TriggerOnObjectUpdate' => 'Тригер на оновлення об\'єкта',
	'Class:TriggerOnObjectUpdate+' => 'Тригер на оновлення об\'єкта даного або дочірнього класу',
	'Class:TriggerOnObjectUpdate/Attribute:target_attcodes' => 'Відстежувані поля',
	'Class:TriggerOnObjectUpdate/Attribute:target_attcodes+' => 'Поля об\'єкта, при оновленні яких спрацює тригер',
));

//
// Class: TriggerOnObjectMention
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:TriggerOnObjectMention' => 'Тригер (на згадку об\'єкта)',
	'Class:TriggerOnObjectMention+' => 'Тригер на згадку (@xxx) об\'єкта [дочірнього класу] даного класу в атрибуті журналу',
	'Class:TriggerOnObjectMention/Attribute:mentioned_filter' => 'Фільтр згаданих',
	'Class:TriggerOnObjectMention/Attribute:mentioned_filter+' => 'Обмежити список згаданих об\'єктів, які активують тригер. Якщо порожньо, будь-який згаданий об\'єкт (будь-якого класу) активує його.',
));

//
// Class: TriggerOnAttributeBlobDownload
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:TriggerOnAttributeBlobDownload' => 'Тригер (на завантаження документа об\'єкта)',
	'Class:TriggerOnAttributeBlobDownload+' => 'Тригер на завантаження поля документа об\'єкта [дочірнього класу] даного класу',
	'Class:TriggerOnAttributeBlobDownload/Attribute:target_attcodes' => 'Цільові поля',
	'Class:TriggerOnAttributeBlobDownload/Attribute:target_attcodes+' => '',
));

//
// Class: TriggerOnThresholdReached
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:TriggerOnThresholdReached' => 'Тригер на порогове значення',
	'Class:TriggerOnThresholdReached+' => 'Тригер на досягнення секундоміром порогового значення (TTO, TTR)',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code' => 'Секундомір',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code+' => 'За замовчуванням для Інцидентів і Запитів доступні \'ttr\' і \'tto\'',
	'Class:TriggerOnThresholdReached/Attribute:threshold_index' => 'Поріг',
	'Class:TriggerOnThresholdReached/Attribute:threshold_index+' => 'Порогове значення секундоміра у %, за замовчуванням \'75\' і \'100\'',
));

//
// Class: lnkTriggerAction
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:lnkTriggerAction' => 'Зв\'язок Тригер/Дія',
	'Class:lnkTriggerAction+' => 'Зв\'язок між тригером і діями',
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
Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:SynchroDataSource' => 'Джерело синхронізації даних',
	'Class:SynchroDataSource/Attribute:name' => 'Назва',
	'Class:SynchroDataSource/Attribute:name+' => '',
	'Class:SynchroDataSource/Attribute:description' => 'Опис',
	'Class:SynchroDataSource/Attribute:status' => 'Статус',
	'Class:SynchroDataSource/Attribute:scope_class' => 'Цільовий клас',
	'Class:SynchroDataSource/Attribute:scope_class+' => 'Джерело синхронізації даних може заповнювати тільки один клас '.ITOP_APPLICATION_SHORT,
	'Class:SynchroDataSource/Attribute:user_id' => 'Користувач',
	'Class:SynchroDataSource/Attribute:notify_contact_id' => 'Контакт для сповіщення',
	'Class:SynchroDataSource/Attribute:notify_contact_id+' => 'Контакт для сповіщення у випадку помилки',
	'Class:SynchroDataSource/Attribute:url_icon' => 'Іконка (посилання)',
	'Class:SynchroDataSource/Attribute:url_icon+' => 'Гіперпосилання на іконку застосунку-джерела даних для відображення на сторінках синхронізованих об\'єктів',
	'Class:SynchroDataSource/Attribute:url_application' => 'Застосунок (посилання)',
	'Class:SynchroDataSource/Attribute:url_application+' => 'Гіперпосилання на об\'єкт у застосунку-джерелі даних. Можливі шаблони: $this->attribute$ і $replica->primary_key$',
	'Class:SynchroDataSource/Attribute:reconciliation_policy' => 'Політика зіставлення',
	'Class:SynchroDataSource/Attribute:reconciliation_policy+' => '"Використовувати атрибути": '.ITOP_APPLICATION_SHORT.' об\'єкт відповідає значенням репліки для кожного атрибута Synchro, позначеного для Reconciliation.
"Використовувати primary_key": очікується, що стовпець primary_key репліки міститиме ідентифікатор '.ITOP_APPLICATION_SHORT.' об\'єкта',
	'Class:SynchroDataSource/Attribute:full_load_periodicity' => 'Інтервал застарівання',
	'Class:SynchroDataSource/Attribute:full_load_periodicity+' => 'Об\'єкт буде вважатися застарілим, якщо дані про нього в таблиці синхронізації не оновлювалися протягом цього інтервалу.',
	'Class:SynchroDataSource/Attribute:action_on_zero' => 'Дія при нулі',
	'Class:SynchroDataSource/Attribute:action_on_zero+' => 'Дія, якщо об\'єкт не знайдено',
	'Class:SynchroDataSource/Attribute:action_on_one' => 'Дія при одиниці',
	'Class:SynchroDataSource/Attribute:action_on_one+' => 'Дія, якщо знайдено тільки один об\'єкт',
	'Class:SynchroDataSource/Attribute:action_on_multiple' => 'Дія при множині',
	'Class:SynchroDataSource/Attribute:action_on_multiple+' => 'Дія, якщо знайдено кілька об\'єктів',
	'Class:SynchroDataSource/Attribute:user_delete_policy' => 'Авторизовані користувачі',
	'Class:SynchroDataSource/Attribute:user_delete_policy+' => 'Кому дозволено видалення синхронізованих об\'єктів',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:never' => 'Ніхто',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:depends' => 'Тільки адміністратори',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:always' => 'Усі дозволені користувачі',
	'Class:SynchroDataSource/Attribute:delete_policy_update' => 'Оновлювані атрибути',
	'Class:SynchroDataSource/Attribute:delete_policy_update+' => 'Формат: назва_поля:значення; ... Приклад: status:inactive',
	'Class:SynchroDataSource/Attribute:delete_policy_retention' => 'Термін зберігання',
	'Class:SynchroDataSource/Attribute:delete_policy_retention+' => 'Скільки часу зберігаються застарілі об\'єкти, перш ніж будуть видалені',
	'Class:SynchroDataSource/Attribute:database_table_name' => 'Таблиця даних',
	'Class:SynchroDataSource/Attribute:database_table_name+' => 'Ім\'я таблиці для зберігання даних синхронізації. Якщо залишити поле порожнім, буде призначено ім\'я за замовчуванням.',
	'Class:SynchroDataSource/Attribute:status/Value:implementation' => 'Впровадження',
	'Class:SynchroDataSource/Attribute:status/Value:obsolete' => 'Застарілий',
	'Class:SynchroDataSource/Attribute:status/Value:production' => 'Експлуатація',
	'Class:SynchroDataSource/Attribute:scope_restriction' => 'Обсяг обмежень',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_attributes' => 'Використовувати атрибути',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_primary_key' => 'Використовувати значення primary_key',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:create' => 'Створити',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:error' => 'Помилка',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:error' => 'Помилка',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:update' => 'Оновити',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:create' => 'Створити',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:error' => 'Помилка',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:take_first' => 'Використовувати перший (випадково)',
	'Class:SynchroDataSource/Attribute:delete_policy' => 'Застарілі об\'єкти',
	'Class:SynchroDataSource/Attribute:delete_policy+' => 'Що робити, коли репліка стає застарілою:
"Ігнорувати": нічого не робити, пов\'язаний об\'єкт залишається в iTop.
"Видалити": Видалити пов\'язаний об\'єкт в iTop (і репліку в таблиці даних).
"Оновити": Оновити пов\'язаний об\'єкт згідно з правилами оновлення (див. нижче).
"Оновити, потім видалити": застосувати "Правила оновлення". Коли закінчується термін зберігання, виконати "Видалення"',
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
	'SynchroDataSource:Deletion' => 'Застарівання і видалення',
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
	'Core:Synchro:NeverRun' => 'Синхронізація не запускалася. Логи відсутні.',
	'Core:Synchro:SynchroEndedOn_Date' => 'Синхронізація була завершена в %1$s.',
	'Core:Synchro:SynchroRunningStartedOn_Date' => 'Синхронізація запущена в %1$s, зараз у процесі...',
	'Core:Synchro:label_repl_ignored' => 'Ігноровано (%1$s)',
	'Core:Synchro:label_repl_disappeared' => 'Зникли (%1$s)',
	'Core:Synchro:label_repl_existing' => 'Існуючі (%1$s)',
	'Core:Synchro:label_repl_new' => 'Нові (%1$s)',
	'Core:Synchro:label_obj_deleted' => 'Видалено (%1$s)',
	'Core:Synchro:label_obj_obsoleted' => 'Застаріло (%1$s)',
	'Core:Synchro:label_obj_disappeared_errors' => 'Помилки (%1$s)',
	'Core:Synchro:label_obj_disappeared_no_action' => 'Без дії (%1$s)',
	'Core:Synchro:label_obj_unchanged' => 'Без змін (%1$s)',
	'Core:Synchro:label_obj_updated' => 'Оновлено (%1$s)',
	'Core:Synchro:label_obj_updated_errors' => 'Помилки (%1$s)',
	'Core:Synchro:label_obj_new_unchanged' => 'Без змін (%1$s)',
	'Core:Synchro:label_obj_new_updated' => 'Оновлено (%1$s)',
	'Core:Synchro:label_obj_created' => 'Створено (%1$s)',
	'Core:Synchro:label_obj_new_errors' => 'Помилки (%1$s)',
	'Core:SynchroLogTitle' => '%1$s - %2$s',
	'Core:Synchro:Nb_Replica' => 'Оброблено реплік: %1$s',
	'Core:Synchro:Nb_Class:Objects' => '%1$s: %2$s',
	'Class:SynchroDataSource/Error:AtLeastOneReconciliationKeyMustBeSpecified' => 'Як мінімум один атрибут повинен бути вибраний для пошуку і зіставлення об\'єктів, або використовуйте політику зіставлення за primary_key.',
	'Class:SynchroDataSource/Error:DeleteRetentionDurationMustBeSpecified' => 'Термін зберігання повинен бути вказаний, оскільки об\'єкти повинні бути видалені після того, як позначені як застарілі.',
	'Class:SynchroDataSource/Error:DeletePolicyUpdateMustBeSpecified' => 'Застарілі об\'єкти повинні бути оновлені, але спосіб оновлення не вказано.',
	'Class:SynchroDataSource/Error:DataTableAlreadyExists' => 'Таблиця %1$s вже існує в базі даних. Будь ласка, використовуйте інше ім\'я для таблиці даних з цього джерела.',
	'Core:SynchroReplica:PublicData' => 'Публічні дані',
	'Core:SynchroReplica:PrivateDetails' => 'Приватні деталі',
	'Core:SynchroReplica:BackToDataSource' => 'Повернутися до джерела синхронізації даних: %1$s',
	'Core:SynchroReplica:ListOfReplicas' => 'Список реплік',
	'Core:SynchroAttExtKey:ReconciliationById' => 'id (Первинний ключ)',
	'Core:SynchroAtt:attcode' => 'Атрибут',
	'Core:SynchroAtt:attcode+' => 'Поле об\'єкта',
	'Core:SynchroAtt:reconciliation' => 'Зіставлення ?',
	'Core:SynchroAtt:reconciliation+' => 'Атрибути, що використовуються для пошуку існуючого об\'єкта',
	'Core:SynchroAtt:update' => 'Оновлення ?',
	'Core:SynchroAtt:update+' => 'Атрибути, які будуть оновлюватися при синхронізації',
	'Core:SynchroAtt:update_policy' => 'Політика оновлення',
	'Core:SynchroAtt:update_policy+' => 'Поведінка оновлюваного атрибута',
	'Core:SynchroAtt:reconciliation_attcode' => 'Ключ зіставлення',
	'Core:SynchroAtt:reconciliation_attcode+' => 'Код атрибута для зіставлення із зовнішнім ключем',
	'Core:SyncDataExchangeComment' => '(Синхронізація)',
	'Core:Synchro:ListOfDataSources' => 'Список джерел даних:',
	'Core:Synchro:LastSynchro' => 'Остання синхронізація:',
	'Core:Synchro:ThisObjectIsSynchronized' => 'Цей об\'єкт синхронізовано із зовнішнім джерелом даних',
	'Core:Synchro:TheObjectWasCreatedBy_Source' => 'Об\'єкт був <b>створений</b> зовнішнім джерелом даних %1$s',
	'Core:Synchro:TheObjectCanBeDeletedBy_Source' => 'Об\'єкт <b>може бути видалений</b> зовнішнім джерелом даних %1$s',
	'Core:Synchro:TheObjectCannotBeDeletedByUser_Source' => 'Ви <b>не можете видалити об\'єкт</b>, оскільки він належить зовнішньому джерелу даних %1$s',
	'TitleSynchroExecution' => 'Запуск синхронізації.',
	'Class:SynchroDataSource:DataTable' => 'Таблиця: %1$s',
	'Core:SyncDataSourceObsolete' => 'Джерело даних позначено як застаріле. Операцію скасовано.',
	'Core:SyncDataSourceAccessRestriction' => 'Можуть запускати тільки адміністратори і визначені користувачі. Операцію скасовано.',
	'Core:SyncTooManyMissingReplicas' => 'Всі записи не змінювалися деякий час (всі об\'єкти можуть бути видалені). Будь ласка, перевірте, що процес, який записує в таблицю синхронізації, все ще працює. Операцію скасовано.',
	'Core:SyncSplitModeCLIOnly' => 'Синхронізація може виконуватися частинами тільки в режимі CLI',
	'Core:Synchro:ListReplicas_AllReplicas_Errors_Warnings' => '%1$s реплік, Помилок %2$s, Попереджень %3$s.',
	'Core:SynchroReplica:TargetObject' => 'Синхронізовано об\'єктів: %1$s',
	'Class:AsyncSendEmail' => 'Email (асинхронний)',
	'Class:AsyncSendEmail/Attribute:to' => 'Кому',
	'Class:AsyncSendEmail/Attribute:subject' => 'Тема',
	'Class:AsyncSendEmail/Attribute:body' => 'Тіло',
	'Class:AsyncSendEmail/Attribute:header' => 'Заголовок',
	'Class:CMDBChangeOpSetAttributeOneWayPassword' => 'Зашифрований пароль',
	'Class:CMDBChangeOpSetAttributeOneWayPassword/Attribute:prev_pwd' => 'Попереднє значення',
	'Class:CMDBChangeOpSetAttributeEncrypted' => 'Зашифроване поле',
	'Class:CMDBChangeOpSetAttributeEncrypted/Attribute:prevstring' => 'Попереднє значення',
	'Class:CMDBChangeOpSetAttributeCaseLog' => 'Журнал',
	'Class:CMDBChangeOpSetAttributeCaseLog/Attribute:lastentry' => 'Останнє значення',
	'Class:SynchroAttribute' => 'Атрибут синхронізації',
	'Class:SynchroAttribute/Attribute:sync_source_id' => 'Джерело синхронізації даних',
	'Class:SynchroAttribute/Attribute:attcode' => 'Код атрибута',
	'Class:SynchroAttribute/Attribute:update' => 'Оновити',
	'Class:SynchroAttribute/Attribute:reconcile' => 'Узгодження',
	'Class:SynchroAttribute/Attribute:update_policy' => 'Політика оновлення',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_locked' => 'Заблоковано',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_unlocked' => 'Розблоковано',
	'Class:SynchroAttribute/Attribute:update_policy/Value:write_if_empty' => 'Ініціалізація, якщо порожньо',
	'Class:SynchroAttribute/Attribute:finalclass' => 'Клас',
	'Class:SynchroAttExtKey' => 'Атрибут синхронізації (ExtKey)',
	'Class:SynchroAttExtKey/Attribute:reconciliation_attcode' => 'Атрибут узгодження',
	'Class:SynchroAttLinkSet' => 'Атрибут синхронізації (Linkset)',
	'Class:SynchroAttLinkSet/Attribute:row_separator' => 'Роздільник рядків',
	'Class:SynchroAttLinkSet/Attribute:attribute_separator' => 'Роздільник атрибутів',
	'Class:SynchroLog' => 'Журнал синхронізації',
	'Class:SynchroLog/Attribute:sync_source_id' => 'Джерело синхронізації даних',
	'Class:SynchroLog/Attribute:start_date' => 'Дата початку',
	'Class:SynchroLog/Attribute:end_date' => 'Дата завершення',
	'Class:SynchroLog/Attribute:status' => 'Статус',
	'Class:SynchroLog/Attribute:status/Value:completed' => 'Завершено',
	'Class:SynchroLog/Attribute:status/Value:error' => 'Помилка',
	'Class:SynchroLog/Attribute:status/Value:running' => 'Виконується',
	'Class:SynchroLog/Attribute:stats_nb_replica_seen' => 'К-ть переглянутих реплік',
	'Class:SynchroLog/Attribute:stats_nb_replica_total' => 'К-ть реплік загалом',
	'Class:SynchroLog/Attribute:stats_nb_obj_deleted' => 'К-ть видалених об\'єктів',
	'Class:SynchroLog/Attribute:stats_nb_obj_deleted_errors' => 'К-ть помилок при видаленні',
	'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted' => 'К-ть застарілих об\'єктів',
	'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted_errors' => 'К-ть помилок при застаріванні',
	'Class:SynchroLog/Attribute:stats_nb_obj_created' => 'К-ть створених об\'єктів',
	'Class:SynchroLog/Attribute:stats_nb_obj_created_errors' => 'К-ть помилок при створенні',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated' => 'К-ть оновлених об\'єктів',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated_errors' => 'К-ть помилок при оновленні',
	'Class:SynchroLog/Attribute:stats_nb_replica_reconciled_errors' => 'К-ть помилок при узгодженні',
	'Class:SynchroLog/Attribute:stats_nb_replica_disappeared_no_action' => 'К-ть зниклих реплік',
	'Class:SynchroLog/Attribute:stats_nb_obj_new_updated' => 'К-ть оновлених об\'єктів',
	'Class:SynchroLog/Attribute:stats_nb_obj_new_unchanged' => 'К-ть незмінених об\'єктів',
	'Class:SynchroLog/Attribute:last_error' => 'Остання помилка',
	'Class:SynchroLog/Attribute:traces' => 'Трасування',
	'Class:SynchroReplica' => 'Репліка синхронізації',
	'Class:SynchroReplica/Attribute:sync_source_id' => 'Джерело синхронізації даних',
	'Class:SynchroReplica/Attribute:dest_id' => 'Об\'єкт призначення',
	'Class:SynchroReplica/Attribute:dest_class' => 'Тип призначення',
	'Class:SynchroReplica/Attribute:status_last_seen' => 'Останнє бачення',
	'Class:SynchroReplica/Attribute:status' => 'Статус',
	'Class:SynchroReplica/Attribute:status/Value:modified' => 'Змінено',
	'Class:SynchroReplica/Attribute:status/Value:new' => 'Новий',
	'Class:SynchroReplica/Attribute:status/Value:obsolete' => 'Застарілий',
	'Class:SynchroReplica/Attribute:status/Value:orphan' => 'Сирота',
	'Class:SynchroReplica/Attribute:status/Value:synchronized' => 'Синхронізовано',
	'Class:SynchroReplica/Attribute:status_dest_creator' => 'Об\'єкт створено',
	'Class:SynchroReplica/Attribute:status_last_error' => 'Остання помилка',
	'Class:SynchroReplica/Attribute:status_last_warning' => 'Попередження',
	'Class:SynchroReplica/Attribute:info_creation_date' => 'Дата створення',
	'Class:SynchroReplica/Attribute:info_last_modified' => 'Дата останньої зміни',
	'Class:appUserPreferences' => 'Налаштування користувача',
	'Class:appUserPreferences/Attribute:userid' => 'Користувач',
	'Class:appUserPreferences/Attribute:preferences' => 'Налаштування',
	'Core:ExecProcess:Code1' => 'Неправильна команда або команда завершена з помилкою (можливо, неправильне ім\'я скрипта)',
	'Core:ExecProcess:Code255' => 'Помилка PHP (парсинг або виконання)',
	'Core:Duration_Seconds' => '%1$d с',
	'Core:Duration_Minutes_Seconds' => '%1$d хв %2$d с',
	'Core:Duration_Hours_Minutes_Seconds' => '%1$d год %2$d хв %3$d с',
	'Core:Duration_Days_Hours_Minutes_Seconds' => '%1$s дн %2$d год %3$d хв %4$d с',
	'Core:ExplainWTC:ElapsedTime' => 'Минув час (збережено як "%1$s")',
	'Core:ExplainWTC:StopWatch-TimeSpent' => 'Витрачено часу для "%1$s"',
	'Core:ExplainWTC:StopWatch-Deadline' => 'Кінцевий термін для "%1$s" на %2$d%%',
	'Core:BulkExport:MissingParameter_Param' => 'Відсутній параметр "%1$s"',
	'Core:BulkExport:InvalidParameter_Query' => 'Недопустиме значення параметра "query". У Книзі запитів відсутній запис з id: "%1$s".',
	'Core:BulkExport:ExportFormatPrompt' => 'Формат експорту:',
	'Core:BulkExportOf_Class' => 'Експорт %1$s',
	'Core:BulkExport:ClickHereToDownload_FileName' => 'Натисніть тут, щоб завантажити %1$s',
	'Core:BulkExport:ExportResult' => 'Результат експорту:',
	'Core:BulkExport:RetrievingData' => 'Отримання даних...',
	'Core:BulkExport:HTMLFormat' => 'Веб-сторінка (*.html)',
	'Core:BulkExport:CSVFormat' => 'Текст з роздільниками-комами (*.csv)',
	'Core:BulkExport:XLSXFormat' => 'Excel 2007 або новіший (*.xlsx)',
	'Core:BulkExport:PDFFormat' => 'Документ PDF (*.pdf)',
	'Core:BulkExport:DragAndDropHelp' => 'Перетягніть заголовки стовпців, щоб впорядкувати стовпці. Попередній перегляд %1$s рядків. Загальна кількість рядків для експорту: %2$s.',
	'Core:BulkExport:EmptyPreview' => 'Виберіть стовпці для експорту зі списку вище',
	'Core:BulkExport:ColumnsOrder' => 'Порядок стовпців',
	'Core:BulkExport:AvailableColumnsFrom_Class' => 'Доступні стовпці з класу %1$s',
	'Core:BulkExport:NoFieldSelected' => 'Виберіть хоча б один стовпець для експорту',
	'Core:BulkExport:CheckAll' => 'Позначити всі',
	'Core:BulkExport:UncheckAll' => 'Зняти всі',
	'Core:BulkExport:ExportCancelledByUser' => 'Експорт скасовано користувачем',
	'Core:BulkExport:CSVOptions' => 'Параметри CSV',
	'Core:BulkExport:CSVLocalization' => 'Локалізація',
	'Core:BulkExport:PDFOptions' => 'Параметри PDF',
	'Core:BulkExport:PDFPageFormat' => 'Формат сторінки',
	'Core:BulkExport:PDFPageSize' => 'Розмір:',
	'Core:BulkExport:PageSize-A4' => 'A4',
	'Core:BulkExport:PageSize-A3' => 'A3',
	'Core:BulkExport:PageSize-Letter' => 'Letter',
	'Core:BulkExport:PDFPageOrientation' => 'Орієнтація:',
	'Core:BulkExport:PageOrientation-L' => 'Альбомна',
	'Core:BulkExport:PageOrientation-P' => 'Книжкова',
	'Core:BulkExport:XMLFormat' => 'Файл XML (*.xml)',
	'Core:BulkExport:XMLOptions' => 'Параметри XML',
	'Core:BulkExport:SpreadsheetFormat' => 'Таблиця HTML (*.html)',
	'Core:BulkExport:SpreadsheetOptions' => 'Параметри таблиці',
	'Core:BulkExport:OptionNoLocalize' => 'Не локалізовувати значення (для полів з випадаючими списками)',
	'Core:BulkExport:OptionLinkSets' => 'Включити пов\'язані об\'єкти',
	'Core:BulkExport:OptionFormattedText' => 'Зберегти форматування тексту',
	'Core:BulkExport:ScopeDefinition' => 'Визначення експортованих об\'єктів',
	'Core:BulkExportLabelOQLExpression' => 'Запит OQL:',
	'Core:BulkExportLabelPhrasebookEntry' => 'Запис у книзі запитів:',
	'Core:BulkExportMessageEmptyOQL' => 'Будь ласка, введіть OQL-запит.',
	'Core:BulkExportMessageEmptyPhrasebookEntry' => 'Виберіть запис у книзі запитів.',
	'Core:BulkExportQueryPlaceholder' => 'Введіть тут OQL-запит...',
	'Core:BulkExportCanRunNonInteractive' => 'Натисніть тут, щоб перейти до експорту в неінтерактивному режимі',
	'Core:BulkExportLegacyExport' => 'Натисніть тут, щоб перейти до застарілого експорту',
	'Core:BulkExport:XLSXOptions' => 'Параметри Excel',
	'Core:BulkExport:TextFormat' => 'Текстові поля з HTML-розміткою',
	'Core:BulkExport:DateTimeFormat' => 'Формат дати і часу',
	'Core:BulkExport:DateTimeFormatDefault_Example' => 'Формат за замовчуванням (%1$s), наприклад %2$s',
	'Core:BulkExport:DateTimeFormatCustom_Format' => 'Користувацький формат: %1$s',
	'Core:BulkExport:PDF:PageNumber' => 'Сторінка %1$s',
	'Core:DateTime:Placeholder_d' => 'DD',
	'Core:DateTime:Placeholder_j' => 'D',
	'Core:DateTime:Placeholder_m' => 'MM',
	'Core:DateTime:Placeholder_n' => 'M',
	'Core:DateTime:Placeholder_Y' => 'YYYY',
	'Core:DateTime:Placeholder_y' => 'YY',
	'Core:DateTime:Placeholder_H' => 'hh',
	'Core:DateTime:Placeholder_h' => 'h',
	'Core:DateTime:Placeholder_G' => 'hh',
	'Core:DateTime:Placeholder_g' => 'h',
	'Core:DateTime:Placeholder_a' => 'am/pm',
	'Core:DateTime:Placeholder_A' => 'AM/PM',
	'Core:DateTime:Placeholder_i' => 'mm',
	'Core:DateTime:Placeholder_s' => 'ss',
	'Core:Validator:Default' => 'Неправильний формат',
	'Core:Validator:Mandatory' => 'Будь ласка, заповніть це поле',
	'Core:Validator:MustBeInteger' => 'Повинно бути цілим числом',
	'Core:Validator:MustSelectOne' => 'Будь ласка, виберіть значення',
	'Menu:DataSources' => 'Синхронізація даних',
	'Menu:DataSources+' => ''
));

//
// Class: TagSetFieldData
//
Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:TagSetFieldData' => '%2$s для класу %1$s',
	'Class:TagSetFieldData+' => '',
	'Class:TagSetFieldData/Attribute:code' => 'Код',
	'Class:TagSetFieldData/Attribute:code+' => 'Внутрішній код. Має містити не менше 3 цифро-буквених символів.',
	'Class:TagSetFieldData/Attribute:label' => 'Метка',
	'Class:TagSetFieldData/Attribute:label+' => 'Мітка, що відображається',
	'Class:TagSetFieldData/Attribute:description' => 'Опис',
	'Class:TagSetFieldData/Attribute:description+' => '',
	'Class:TagSetFieldData/Attribute:finalclass' => 'Клас тега',
	'Class:TagSetFieldData/Attribute:obj_class' => 'Клас об\'єкта',
	'Class:TagSetFieldData/Attribute:obj_attcode' => 'Код поля',
	'Core:TagSetFieldData:ErrorDeleteUsedTag' => 'Використовувані теги не можуть бути видалені',
	'Core:TagSetFieldData:ErrorDuplicateTagCodeOrLabel' => 'Коди та мітки тегів повинні бути унікальними',
	'Core:TagSetFieldData:ErrorTagCodeSyntax' => 'Код тега повинен містити від 3 до %1$d цифро-буквених символів',
	'Core:TagSetFieldData:ErrorTagCodeReservedWord' => 'Вибраний код тега є зарезервованим словом',
	'Core:TagSetFieldData:ErrorTagLabelSyntax' => 'Мітка тега не повинна містити \'%1$s\' або бути порожньою',
	'Core:TagSetFieldData:ErrorCodeUpdateNotAllowed' => 'Код тега не може бути змінений при використанні',
	'Core:TagSetFieldData:ErrorClassUpdateNotAllowed' => 'Неможливо змінити "Object Class" тега',
	'Core:TagSetFieldData:ErrorAttCodeUpdateNotAllowed' => 'Неможливо змінити "Attribute Code" тега',
	'Core:TagSetFieldData:WhereIsThisTagTab' => 'Использование тега (%1$d)',
	'Core:TagSetFieldData:NoEntryFound' => 'Не знайдено записів з цим тегом',
));

//
// Class: DBProperty
//
Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:DBProperty' => 'Властивість БД',
	'Class:DBProperty+' => '',
	'Class:DBProperty/Attribute:name' => 'Назва',
	'Class:DBProperty/Attribute:name+' => '',
	'Class:DBProperty/Attribute:description' => 'Опис',
	'Class:DBProperty/Attribute:description+' => '',
	'Class:DBProperty/Attribute:value' => 'Значення',
	'Class:DBProperty/Attribute:value+' => '',
	'Class:DBProperty/Attribute:change_date' => 'Дата зміни',
	'Class:DBProperty/Attribute:change_date+' => '',
	'Class:DBProperty/Attribute:change_comment' => 'Коментар зміни',
	'Class:DBProperty/Attribute:change_comment+' => '',
));

//
// Class: BackgroundTask
//
Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:BackgroundTask' => 'Фонове завдання',
	'Class:BackgroundTask+' => '',
	'Class:BackgroundTask/Attribute:class_name' => 'Назва класу',
	'Class:BackgroundTask/Attribute:class_name+' => '',
	'Class:BackgroundTask/Attribute:first_run_date' => 'Дата першого запуску',
	'Class:BackgroundTask/Attribute:first_run_date+' => '',
	'Class:BackgroundTask/Attribute:latest_run_date' => 'Дата останнього запуску',
	'Class:BackgroundTask/Attribute:latest_run_date+' => '',
	'Class:BackgroundTask/Attribute:next_run_date' => 'Дата наступного запуску',
	'Class:BackgroundTask/Attribute:next_run_date+' => '',
	'Class:BackgroundTask/Attribute:total_exec_count' => 'Загальна к-ть виконань',
	'Class:BackgroundTask/Attribute:total_exec_count+' => '',
	'Class:BackgroundTask/Attribute:latest_run_duration' => 'Тривалість останнього виконання',
	'Class:BackgroundTask/Attribute:latest_run_duration+' => '',
	'Class:BackgroundTask/Attribute:min_run_duration' => 'Мін. тривалість виконання',
	'Class:BackgroundTask/Attribute:min_run_duration+' => '',
	'Class:BackgroundTask/Attribute:max_run_duration' => 'Макс. тривалість виконання',
	'Class:BackgroundTask/Attribute:max_run_duration+' => '',
	'Class:BackgroundTask/Attribute:average_run_duration' => 'Середня тривалість виконання',
	'Class:BackgroundTask/Attribute:average_run_duration+' => '',
	'Class:BackgroundTask/Attribute:running' => 'Виконується',
	'Class:BackgroundTask/Attribute:running+' => '',
	'Class:BackgroundTask/Attribute:status' => 'Статус',
	'Class:BackgroundTask/Attribute:status+' => '',
));

//
// Class: AsyncTask
//
Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
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
	'Class:AsyncTask/Attribute:finalclass' => 'Підсумковий клас',
	'Class:AsyncTask/Attribute:finalclass+' => '',
	'Class:AsyncTask/Attribute:status' => 'Статус',
	'Class:AsyncTask/Attribute:status+' => '',
	'Class:AsyncTask/Attribute:remaining_retries' => 'Залишилося спроб',
	'Class:AsyncTask/Attribute:remaining_retries+' => '',
	'Class:AsyncTask/Attribute:last_error_code' => 'Код останньої помилки',
	'Class:AsyncTask/Attribute:last_error_code+' => '',
	'Class:AsyncTask/Attribute:last_error' => 'Остання помилка',
	'Class:AsyncTask/Attribute:last_error+' => '',
	'Class:AsyncTask/Attribute:last_attempt' => 'Остання спроба',
	'Class:AsyncTask/Attribute:last_attempt+' => '',
	'Class:AsyncTask:InvalidConfig_Class_Keys' => 'Неправильний формат конфігурації "async_task_retries[%1$s]". Очікується масив з наступними ключами: %2$s',
	'Class:AsyncTask:InvalidConfig_Class_InvalidKey_Keys' => 'Неправильний формат конфігурації "async_task_retries[%1$s]": неочікуваний ключ "%2$s". Очікуються тільки наступні ключі: %3$s',
));

//
// Class: AbstractResource
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:AbstractResource' => 'Ресурс',
	'Class:AbstractResource+' => '',
));

//
// Class: ResourceAdminMenu
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:ResourceAdminMenu' => 'Меню Інструменти адміністратора',
	'Class:ResourceAdminMenu+' => '',
));

//
// Class: ResourceRunQueriesMenu
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:ResourceRunQueriesMenu' => 'Меню Виконання запитів',
	'Class:ResourceRunQueriesMenu+' => '',
));

//
// Class: Action
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:ResourceSystemMenu' => 'Меню Система',
	'Class:ResourceSystemMenu+' => '',
));



