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
	'Class:Ticket/Attribute:org_name' => 'Организация',
	'Class:Ticket/Attribute:org_name+' => '',
	'Class:Ticket/Attribute:caller_id' => 'Инициатор',
	'Class:Ticket/Attribute:caller_id+' => '',
	'Class:Ticket/Attribute:caller_name' => 'Инициатор',
	'Class:Ticket/Attribute:caller_name+' => '',
	'Class:Ticket/Attribute:team_id' => 'Команда',
	'Class:Ticket/Attribute:team_id+' => '',
	'Class:Ticket/Attribute:team_name' => 'Команда',
	'Class:Ticket/Attribute:team_name+' => '',
	'Class:Ticket/Attribute:agent_id' => 'Агент',
	'Class:Ticket/Attribute:agent_id+' => '',
	'Class:Ticket/Attribute:agent_name' => 'Агент',
	'Class:Ticket/Attribute:agent_name+' => '',
	'Class:Ticket/Attribute:title' => 'Название',
	'Class:Ticket/Attribute:title+' => '',
	'Class:Ticket/Attribute:description' => 'Описание',
	'Class:Ticket/Attribute:description+' => '',
	'Class:Ticket/Attribute:start_date' => 'Дата начала',
	'Class:Ticket/Attribute:start_date+' => '',
	'Class:Ticket/Attribute:end_date' => 'Дата окончания',
	'Class:Ticket/Attribute:end_date+' => '',
	'Class:Ticket/Attribute:last_update' => 'Дата обновления',
	'Class:Ticket/Attribute:last_update+' => '',
	'Class:Ticket/Attribute:close_date' => 'Дата закрытия',
	'Class:Ticket/Attribute:close_date+' => '',
	'Class:Ticket/Attribute:private_log' => 'Внутренний журнал',
	'Class:Ticket/Attribute:private_log+' => 'Информация внутреннего журнала недоступна пользователям портала',
    'Class:Ticket/Attribute:contacts_list' => 'Контакты',
	'Class:Ticket/Attribute:contacts_list+' => 'Все контакты, связанные с этим тикетом',
	'Class:Ticket/Attribute:functionalcis_list' => 'КЕ',
	'Class:Ticket/Attribute:functionalcis_list+' => 'Все конфигурационные единицы, на которые влияет этот тикет. Элементы, отмеченные как "Вычислено" автоматически считаются затронутыми и участвуют в анализе влияния. Элементы, отмеченные как "Не влияет" исключены из анализа.',
	'Class:Ticket/Attribute:workorders_list' => 'Наряды на работу',
	'Class:Ticket/Attribute:workorders_list+' => 'Наряды на работу',
	'Class:Ticket/Attribute:finalclass' => 'Тип',
	'Class:Ticket/Attribute:finalclass+' => '',
	'Class:Ticket/Attribute:operational_status' => 'Статус обработки',
	'Class:Ticket/Attribute:operational_status+' => 'Вычисляется после детального статуса',
	'Class:Ticket/Attribute:operational_status/Value:ongoing' => 'Выполняется',
	'Class:Ticket/Attribute:operational_status/Value:ongoing+' => 'В процессе обработки',
	'Class:Ticket/Attribute:operational_status/Value:resolved' => 'Выполнен',
	'Class:Ticket/Attribute:operational_status/Value:resolved+' => '',
	'Class:Ticket/Attribute:operational_status/Value:closed' => 'Закрыт',
	'Class:Ticket/Attribute:operational_status/Value:closed+' => '',
	'Ticket:ImpactAnalysis' => 'Анализ влияния',
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
	'Class:lnkContactToTicket/Attribute:role' => 'Роль (текст)',
	'Class:lnkContactToTicket/Attribute:role+' => '',
	'Class:lnkContactToTicket/Attribute:role_code' => 'Роль',
	'Class:lnkContactToTicket/Attribute:role_code/Value:manual' => 'Добавлено вручную',
	'Class:lnkContactToTicket/Attribute:role_code/Value:computed' => 'Вычислено',
	'Class:lnkContactToTicket/Attribute:role_code/Value:do_not_notify' => 'Не уведомлять',
));

//
// Class: lnkFunctionalCIToTicket
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkFunctionalCIToTicket' => 'Связь Функциональная КЕ/Тикет',
	'Class:lnkFunctionalCIToTicket+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_id' => 'Тикет',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_id+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_ref' => 'Тикет',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_ref+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_title' => 'Ticket title~~',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_title+' => '~~',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_id' => 'КЕ',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_name' => 'КЕ',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_name+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:impact' => 'Влияние (текст)',
	'Class:lnkFunctionalCIToTicket/Attribute:impact+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code' => 'Влияние',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:manual' => 'Добавлено вручную',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:computed' => 'Вычислено',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:not_impacted' => 'Не влияет',
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
	'Class:WorkOrder/Attribute:ticket_ref' => 'Тикет',
	'Class:WorkOrder/Attribute:ticket_ref+' => '',
	'Class:WorkOrder/Attribute:team_id' => 'Команда',
	'Class:WorkOrder/Attribute:team_id+' => '',
	'Class:WorkOrder/Attribute:team_name' => 'Команда',
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
	'Ticket:relation' => 'Зависимости',
	'Ticket:log' => 'Журнал',
	'Ticket:Type' => 'Приоритет',
	'Ticket:support' => 'Поддержка',
	'Ticket:resolution' => 'Решение',
	'Ticket:SLA' => 'Отчёт SLA',
	'WorkOrder:Details' => 'Детали',
	'WorkOrder:Moreinfo' => 'Дополнительная информация',
	'Tickets:ResolvedFrom' => 'Автоматическое решение из %1$s',

	'Class:cmdbAbstractObject/Method:Set' => 'Установить',
	'Class:cmdbAbstractObject/Method:Set+' => 'Установить поле со статичным значением',
	'Class:cmdbAbstractObject/Method:Set/Param:1' => 'Целевое поле',
	'Class:cmdbAbstractObject/Method:Set/Param:1+' => 'Установить поле, в текущем объекте ',
	'Class:cmdbAbstractObject/Method:Set/Param:2' => 'Значение',
	'Class:cmdbAbstractObject/Method:Set/Param:2+' => 'Установить значение',
	'Class:cmdbAbstractObject/Method:SetCurrentDate' => 'Установить текущую дату',
	'Class:cmdbAbstractObject/Method:SetCurrentDate+' => 'Установить поле с текущей датой и временем',
	'Class:cmdbAbstractObject/Method:SetCurrentDate/Param:1' => 'Целевое поле',
	'Class:cmdbAbstractObject/Method:SetCurrentDate/Param:1+' => 'Установить поле, в текущем объекте',
	'Class:cmdbAbstractObject/Method:SetCurrentUser' => 'Установитьтекущегопользователя',
	'Class:cmdbAbstractObject/Method:SetCurrentUser+' => 'Установить поле с текущим вошедшим пользователем',
	'Class:cmdbAbstractObject/Method:SetCurrentUser/Param:1' => 'Целевое поле',
	'Class:cmdbAbstractObject/Method:SetCurrentUser/Param:1+' => 'Установить поле, в текущем объекте. Если поле является строкой, тогда будет использоваться псевдоним, в противном случае будет использоваться идентификатор. Псевдонимом является имя человека, если оно связано с пользователем, в иных случаях - это логин.',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson' => 'Установитьтекущуюперсону',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson+' => ' Установить поле с текущим вошедшим человеком ("человек" связан с  "пользователь").',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson/Param:1' => 'Целевое поле',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson/Param:1+' => 'Установить поле, в текущем объекте. Если поле является строкой, тогда будет использоваться псевдоним, в противном случае будет использоваться идентификатор. Псевдонимом является имя человека, если оно связано с пользователем, в иных случаях - это логин.',
	'Class:cmdbAbstractObject/Method:SetElapsedTime' => 'Установитьистекшеевремя',
	'Class:cmdbAbstractObject/Method:SetElapsedTime+' => 'Установить поле с временем (секунды), истекающее с даты, задданой ',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:1' => 'Целевое поле',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:1+' => 'Установить поле, в текущем объекте ',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:2' => 'Ссылочное поле',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:2+' => 'Поле, откуда берется референсная дата',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:3' => 'Рабочие часы',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:3+' => 'Оставьте пустым, чтобы полагаться на стандартную схему рабочих часов, или установите значение «DefaultWorkingTimeComputer», чтобы принудительно настроить схему 24x7',
	'Class:cmdbAbstractObject/Method:Reset' => 'Сброс',
	'Class:cmdbAbstractObject/Method:Reset+' => 'Сбросить поле до его стандартного значения ',
	'Class:cmdbAbstractObject/Method:Reset/Param:1' => 'Целевое поле',
	'Class:cmdbAbstractObject/Method:Reset/Param:1+' => 'Сбросить поле, в текущем объекте',
	'Class:cmdbAbstractObject/Method:Copy' => 'Копировать',
	'Class:cmdbAbstractObject/Method:Copy+' => 'Скопировать значение с поля на другое поле',
	'Class:cmdbAbstractObject/Method:Copy/Param:1' => 'Целевое поле',
	'Class:cmdbAbstractObject/Method:Copy/Param:1+' => 'Установить поле, в текущем объекте ',
	'Class:cmdbAbstractObject/Method:Copy/Param:2' => 'Исходное поле ',
	'Class:cmdbAbstractObject/Method:Copy/Param:2+' => 'Присвоить значение поля, в текущем объекте',
	'Class:cmdbAbstractObject/Method:ApplyStimulus' => 'ApplyStimulus~~',
	'Class:cmdbAbstractObject/Method:ApplyStimulus+' => 'Apply the specified stimulus to the current object~~',
	'Class:cmdbAbstractObject/Method:ApplyStimulus/Param:1' => 'Stimulus code~~',
	'Class:cmdbAbstractObject/Method:ApplyStimulus/Param:1+' => 'A valid stimulus code for the current class~~',
	'Class:ResponseTicketTTO/Interface:iMetricComputer' => 'Время создания тикета до его назначения',
	'Class:ResponseTicketTTO/Interface:iMetricComputer+' => 'TTO Цель, основанная на SLT типа ТТО',
	'Class:ResponseTicketTTR/Interface:iMetricComputer' => 'Время решения',
	'Class:ResponseTicketTTR/Interface:iMetricComputer+' => 'Цель основанная на SLT типа TTR',

	'portal:itop-portal' => 'Пользовательский портал', // This is the portal name that will be displayed in portal dispatcher (eg. URL in menus)
	'Page:DefaultTitle' => '%1$s - Пользовательский портал',
	'Brick:Portal:UserProfile:Title' => 'Мой профиль',
	'Brick:Portal:NewRequest:Title' => 'Новый запрос',
	'Brick:Portal:NewRequest:Title+' => '<p>Нужна помощь?</p><p>Выберите услугу из&nbsp;каталога и&nbsp;отправьте свой запрос команде поддержки.</p>',
	'Brick:Portal:OngoingRequests:Title' => 'Текущие запросы',
	'Brick:Portal:OngoingRequests:Title+' => '<p>Следите за&nbsp;открытыми запросами.</p><p>Проверяйте ход решения, добавляйте комментарии и&nbsp;вложения, подтверждайте решение.</p>',
	'Brick:Portal:OngoingRequests:Tab:OnGoing' => 'В работе',
	'Brick:Portal:OngoingRequests:Tab:Resolved' => 'Решенные',
	'Brick:Portal:ClosedRequests:Title' => 'Закрытые запросы',
));
