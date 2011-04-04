<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * Localized data
 *
 * @author      Vladimir Shilov <shilow@ukr.net>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */


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
	'Class:CMDBChange/Attribute:date+' => 'дата и время регистрации изменений',
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
	'Change:AttName_SetTo_NewValue_PreviousValue_OldValue' => '%1$s установлено в %2$s (предыдущее значение: %3$s)',
	'Change:AttName_SetTo' => '%1$s установлено в %2$s',
	'Change:Text_AppendedTo_AttName' => '%1$s добавлено к %2$s',
	'Change:AttName_Changed_PreviousValue_OldValue' => '%1$s изменено, предыдущее значение: %2$s',
	'Change:AttName_Changed' => '%1$s изменено',
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
	'Class:Event/Attribute:message' => 'сообщение',
	'Class:Event/Attribute:message+' => 'короткое описание собітия',
	'Class:Event/Attribute:date' => 'дата',
	'Class:Event/Attribute:date+' => 'дата и время регистрации изменений',
	'Class:Event/Attribute:userinfo' => 'информация о пользователе',
	'Class:Event/Attribute:userinfo+' => 'идентификация пользователя, действия которого вызвали это событие',
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
	'Class:EventNotification/Attribute:trigger_id+' => 'учётная запись пользователя',
	'Class:EventNotification/Attribute:action_id' => 'пользователь',
	'Class:EventNotification/Attribute:action_id+' => 'учётная запись пользователя',
	'Class:EventNotification/Attribute:object_id' => 'id объекта',
	'Class:EventNotification/Attribute:object_id+' => 'id объекта (класс заданный тригером ?)',
));

//
// Class: EventNotificationEmail
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:EventNotificationEmail' => 'Отправка сообщений на e-mail',
	'Class:EventNotificationEmail+' => 'Отслеживание отправленных писем',
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
	'Class:EventNotificationEmail/Attribute:body' => 'Тело',
	'Class:EventNotificationEmail/Attribute:body+' => 'Тело',
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
	'Class:EventIssue/Attribute:callstack' => 'Стек?вызовов',
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
	'Class:EventWebService/Attribute:result+' => 'Overall success/failure',
	'Class:EventWebService/Attribute:log_info' => 'Info log',
	'Class:EventWebService/Attribute:log_info+' => 'Result info log',
	'Class:EventWebService/Attribute:log_warning' => 'Warning log',
	'Class:EventWebService/Attribute:log_warning+' => 'Result warning log',
	'Class:EventWebService/Attribute:log_error' => 'Error log',
	'Class:EventWebService/Attribute:log_error+' => 'Result error log',
	'Class:EventWebService/Attribute:data' => 'Данные',
	'Class:EventWebService/Attribute:data+' => 'Result data',
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
	'Class:ActionEmail' => 'Уведомление по e-mail',
	'Class:ActionEmail+' => '',
	'Class:ActionEmail/Attribute:test_recipient' => 'Проверка получателя',
	'Class:ActionEmail/Attribute:test_recipient+' => 'Назначение если статус "Test"',
	'Class:ActionEmail/Attribute:from' => 'От',
	'Class:ActionEmail/Attribute:from+' => 'Будет отослано в заголовке e-mail',
	'Class:ActionEmail/Attribute:reply_to' => 'Ответить на',
	'Class:ActionEmail/Attribute:reply_to+' => 'Будет отослано в заголовке e-mail',
	'Class:ActionEmail/Attribute:to' => 'Кому',
	'Class:ActionEmail/Attribute:to+' => 'Получатель e-mail',
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


?>
