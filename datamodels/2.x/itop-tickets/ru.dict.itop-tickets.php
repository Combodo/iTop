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

// Dictionnay conventions
// Class:<class_name>
// Class:<class_name>+
// Class:<class_name>/Attribute:<attribute_code>
// Class:<class_name>/Attribute:<attribute_code>+
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>+
// Class:<class_name>/Stimulus:<stimulus_code>
// Class:<class_name>/Stimulus:<stimulus_code>+


//
// Class: Ticket
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Ticket' => 'Тикет',
	'Class:Ticket+' => '',
	'Class:Ticket/Attribute:ref' => 'Номер',
	'Class:Ticket/Attribute:ref+' => '',
	'Class:Ticket/Attribute:org_id' => 'Организация',
	'Class:Ticket/Attribute:org_id+' => '',
	'Class:Ticket/Attribute:org_name' => 'Имя организации',
	'Class:Ticket/Attribute:org_name+' => '',
	'Class:Ticket/Attribute:caller_id' => 'Инициатор',
	'Class:Ticket/Attribute:caller_id+' => '',
	'Class:Ticket/Attribute:caller_name' => 'Имя заявителя',
	'Class:Ticket/Attribute:caller_name+' => '',
	'Class:Ticket/Attribute:team_id' => 'Команда',
	'Class:Ticket/Attribute:team_id+' => '',
	'Class:Ticket/Attribute:team_name' => 'Имя команды',
	'Class:Ticket/Attribute:team_name+' => '',
	'Class:Ticket/Attribute:agent_id' => 'Агент',
	'Class:Ticket/Attribute:agent_id+' => '',
	'Class:Ticket/Attribute:agent_name' => 'Имя агента',
	'Class:Ticket/Attribute:agent_name+' => '',
	'Class:Ticket/Attribute:title' => 'Название',
	'Class:Ticket/Attribute:title+' => '',
	'Class:Ticket/Attribute:description' => 'Описание',
	'Class:Ticket/Attribute:description+' => '',
	'Class:Ticket/Attribute:start_date' => 'Начало',
	'Class:Ticket/Attribute:start_date+' => '',
	'Class:Ticket/Attribute:end_date' => 'Окончание',
	'Class:Ticket/Attribute:end_date+' => '',
	'Class:Ticket/Attribute:last_update' => 'Обновление',
	'Class:Ticket/Attribute:last_update+' => '',
	'Class:Ticket/Attribute:close_date' => 'Закрытие',
	'Class:Ticket/Attribute:close_date+' => '',
	'Class:Ticket/Attribute:private_log' => 'Внутренний журнал',
	'Class:Ticket/Attribute:private_log+' => 'Информация внутреннего журнала пользователям портала недоступна',
	'Class:Ticket/Attribute:contacts_list' => 'Контакты',
	'Class:Ticket/Attribute:contacts_list+' => 'Все контакты, связанные с этим тикетом',
	'Class:Ticket/Attribute:functionalcis_list' => 'КЕ',
	'Class:Ticket/Attribute:functionalcis_list+' => 'Все конфигурационные единицы, на которые влияет этот тикет',
	'Class:Ticket/Attribute:workorders_list' => 'Наряды на работу',
	'Class:Ticket/Attribute:workorders_list+' => 'Наряды на работу',
	'Class:Ticket/Attribute:finalclass' => 'Тип',
	'Class:Ticket/Attribute:finalclass+' => '',
));


//
// Class: lnkContactToTicket
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkContactToTicket' => 'Связь Контакт/Тикет',
	'Class:lnkContactToTicket+' => '',
	'Class:lnkContactToTicket/Attribute:ticket_id' => 'Тикет',
	'Class:lnkContactToTicket/Attribute:ticket_id+' => '',
	'Class:lnkContactToTicket/Attribute:ticket_ref' => 'Связь',
	'Class:lnkContactToTicket/Attribute:ticket_ref+' => '',
	'Class:lnkContactToTicket/Attribute:contact_id' => 'Контакт',
	'Class:lnkContactToTicket/Attribute:contact_id+' => '',
	'Class:lnkContactToTicket/Attribute:contact_email' => 'Email контакта',
	'Class:lnkContactToTicket/Attribute:contact_email+' => '',
	'Class:lnkContactToTicket/Attribute:role' => 'Роль',
	'Class:lnkContactToTicket/Attribute:role+' => '',
));

//
// Class: lnkFunctionalCIToTicket
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkFunctionalCIToTicket' => 'Связь Функциональная КЕ/Тикет',
	'Class:lnkFunctionalCIToTicket+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_id' => 'Тикет',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_id+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_ref' => 'Связь',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_ref+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_id' => 'КЕ',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_name' => 'Имя КЕ',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_name+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:impact' => 'Влияние',
	'Class:lnkFunctionalCIToTicket/Attribute:impact+' => '',
));


//
// Class: WorkOrder
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:WorkOrder' => 'Наряд на работу',
	'Class:WorkOrder+' => '',
	'Class:WorkOrder/Attribute:name' => 'Название',
	'Class:WorkOrder/Attribute:name+' => '',
	'Class:WorkOrder/Attribute:status' => 'Статус',
	'Class:WorkOrder/Attribute:status+' => '',
	'Class:WorkOrder/Attribute:status/Value:open' => 'Открыт',
	'Class:WorkOrder/Attribute:status/Value:open+' => '',
	'Class:WorkOrder/Attribute:status/Value:closed' => 'Закрыт',
	'Class:WorkOrder/Attribute:status/Value:closed+' => '',
	'Class:WorkOrder/Attribute:description' => 'Описание',
	'Class:WorkOrder/Attribute:description+' => '',
	'Class:WorkOrder/Attribute:ticket_id' => 'Тикет',
	'Class:WorkOrder/Attribute:ticket_id+' => '',
	'Class:WorkOrder/Attribute:ticket_ref' => 'Связь тикета',
	'Class:WorkOrder/Attribute:ticket_ref+' => '',
	'Class:WorkOrder/Attribute:team_id' => 'Команда',
	'Class:WorkOrder/Attribute:team_id+' => '',
	'Class:WorkOrder/Attribute:team_name' => 'Имя тикета',
	'Class:WorkOrder/Attribute:team_name+' => '',
	'Class:WorkOrder/Attribute:agent_id' => 'Агент',
	'Class:WorkOrder/Attribute:agent_id+' => '',
	'Class:WorkOrder/Attribute:agent_email' => 'Email агента',
	'Class:WorkOrder/Attribute:agent_email+' => '',
	'Class:WorkOrder/Attribute:start_date' => 'Дата начала',
	'Class:WorkOrder/Attribute:start_date+' => '',
	'Class:WorkOrder/Attribute:end_date' => 'Дата окончания',
	'Class:WorkOrder/Attribute:end_date+' => '',
	'Class:WorkOrder/Attribute:log' => 'Журнал',
	'Class:WorkOrder/Attribute:log+' => '',
	'Class:WorkOrder/Stimulus:ev_close' => 'Закрыть',
	'Class:WorkOrder/Stimulus:ev_close+' => '',
));


// Fieldset translation
Dict::Add('RU RU', 'Russian', 'Русский', array(

	'Ticket:baseinfo' => 'Общая информация',
	'Ticket:date' => 'Даты',
	'Ticket:contact' => 'Контакты',
	'Ticket:moreinfo' => 'Дополнительная информация',
	'Ticket:relation' => 'Зависимость',
	'Ticket:log' => 'Журнал',
	'Ticket:Type' => 'Приоритет',
	'Ticket:support' => 'Поддержка',
	'Ticket:resolution' => 'Решение',
	'Ticket:SLA' => 'Отчёт SLA',
	'WorkOrder:Details' => 'Детали',
	'WorkOrder:Moreinfo' => 'Дополнительная информация',

));







?>
