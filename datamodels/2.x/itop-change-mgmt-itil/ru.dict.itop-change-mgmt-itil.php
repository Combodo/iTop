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
	'Menu:ChangeManagement' => 'Управление изменениями',
	'Menu:Change:Overview' => 'Обзор',
	'Menu:Change:Overview+' => '',
	'Menu:NewChange' => 'Новый запрос на изменение',
	'Menu:NewChange+' => 'Создание нового запроса на изменение',
	'Menu:SearchChanges' => 'Поиск изменений',
	'Menu:SearchChanges+' => 'Поиск запросов на изменение',
	'Menu:Change:Shortcuts' => 'Ярлыки',
	'Menu:Change:Shortcuts+' => '',
	'Menu:WaitingAcceptance' => 'Изменения, ожидающие принятия',
	'Menu:WaitingAcceptance+' => '',
	'Menu:WaitingApproval' => 'Изменения, ожидающие утверждения',
	'Menu:WaitingApproval+' => '',
	'Menu:Changes' => 'Открытые изменения',
	'Menu:Changes+' => 'Все открытые изменения',
	'Menu:MyChanges' => 'Изменения, назначенные на меня',
	'Menu:MyChanges+' => 'Изменения, назначенные на меня (как агента)',
	'UI-ChangeManagementOverview-ChangeByCategory-last-7-days' => 'Изменения по категориям за 7 дней',
	'UI-ChangeManagementOverview-Last-7-days' => 'Количество изменений за 7 дней',
	'UI-ChangeManagementOverview-ChangeByDomain-last-7-days' => 'Изменения по домену за 7 дней',
	'UI-ChangeManagementOverview-ChangeByStatus-last-7-days' => 'Изменения по статусу за 7 дней',
));

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
// Class: Change
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Change' => 'Изменение',
	'Class:Change+' => '',
	'Class:Change/Attribute:status' => 'Статус',
	'Class:Change/Attribute:status+' => '',
	'Class:Change/Attribute:status/Value:new' => 'Новый',
	'Class:Change/Attribute:status/Value:new+' => '',
	'Class:Change/Attribute:status/Value:validated' => 'Проверен',
	'Class:Change/Attribute:status/Value:validated+' => '',
	'Class:Change/Attribute:status/Value:rejected' => 'Отклонён',
	'Class:Change/Attribute:status/Value:rejected+' => '',
	'Class:Change/Attribute:status/Value:assigned' => 'Назначен',
	'Class:Change/Attribute:status/Value:assigned+' => '',
	'Class:Change/Attribute:status/Value:plannedscheduled' => 'Запланирован',
	'Class:Change/Attribute:status/Value:plannedscheduled+' => '',
	'Class:Change/Attribute:status/Value:approved' => 'Утверждён',
	'Class:Change/Attribute:status/Value:approved+' => '',
	'Class:Change/Attribute:status/Value:notapproved' => 'Не утверждён',
	'Class:Change/Attribute:status/Value:notapproved+' => '',
	'Class:Change/Attribute:status/Value:implemented' => 'Реализован',
	'Class:Change/Attribute:status/Value:implemented+' => '',
	'Class:Change/Attribute:status/Value:monitored' => 'Под наблюдением',
	'Class:Change/Attribute:status/Value:monitored+' => '',
	'Class:Change/Attribute:status/Value:closed' => 'Закрыт',
	'Class:Change/Attribute:status/Value:closed+' => '',
	'Class:Change/Attribute:reason' => 'Причина',
	'Class:Change/Attribute:reason+' => '',
	'Class:Change/Attribute:requestor_id' => 'Инициатор запроса',
	'Class:Change/Attribute:requestor_id+' => '',
	'Class:Change/Attribute:requestor_email' => 'Email инициатора запроса',
	'Class:Change/Attribute:requestor_email+' => '',
	'Class:Change/Attribute:creation_date' => 'Создан',
	'Class:Change/Attribute:creation_date+' => '',
	'Class:Change/Attribute:impact' => 'Критичность',
	'Class:Change/Attribute:impact+' => '',
	'Class:Change/Attribute:supervisor_group_id' => 'Команда наблюдателя',
	'Class:Change/Attribute:supervisor_group_id+' => '',
	'Class:Change/Attribute:supervisor_group_name' => 'Команда наблюдателя',
	'Class:Change/Attribute:supervisor_group_name+' => '',
	'Class:Change/Attribute:supervisor_id' => 'Наблюдатель',
	'Class:Change/Attribute:supervisor_id+' => '',
	'Class:Change/Attribute:supervisor_email' => 'Email наблюдателя',
	'Class:Change/Attribute:supervisor_email+' => '',
	'Class:Change/Attribute:manager_group_id' => 'Команда менеджера',
	'Class:Change/Attribute:manager_group_id+' => '',
	'Class:Change/Attribute:manager_group_name' => 'Команда менеджера',
	'Class:Change/Attribute:manager_group_name+' => '',
	'Class:Change/Attribute:manager_id' => 'Менеджер',
	'Class:Change/Attribute:manager_id+' => '',
	'Class:Change/Attribute:manager_email' => 'Email менеджера',
	'Class:Change/Attribute:manager_email+' => '',
	'Class:Change/Attribute:outage' => 'Простой услуги',
	'Class:Change/Attribute:outage+' => '',
	'Class:Change/Attribute:outage/Value:no' => 'Нет',
	'Class:Change/Attribute:outage/Value:no+' => '',
	'Class:Change/Attribute:outage/Value:yes' => 'Да',
	'Class:Change/Attribute:outage/Value:yes+' => '',
	'Class:Change/Attribute:fallback' => 'План отката',
	'Class:Change/Attribute:fallback+' => '',
	'Class:Change/Attribute:parent_id' => 'Родительское изменение',
	'Class:Change/Attribute:parent_id+' => '',
	'Class:Change/Attribute:parent_name' => 'Имя родительского изменения',
	'Class:Change/Attribute:parent_name+' => '',
	'Class:Change/Attribute:related_request_list' => 'Связанные запросы',
	'Class:Change/Attribute:related_request_list+' => 'Связанные запросы',
	'Class:Change/Attribute:related_problems_list' => 'Связанные проблемы',
	'Class:Change/Attribute:related_problems_list+' => 'Связанные проблемы',
	'Class:Change/Attribute:related_incident_list' => 'Связанные инциденты',
	'Class:Change/Attribute:related_incident_list+' => 'Связанные инциденты',
	'Class:Change/Attribute:child_changes_list' => 'Дочерние изменения',
	'Class:Change/Attribute:child_changes_list+' => 'Дочерние изменения',
	'Class:Change/Attribute:parent_id_friendlyname' => 'Родительское изменение',
	'Class:Change/Attribute:parent_id_friendlyname+' => '',
	'Class:Change/Attribute:parent_id_finalclass_recall' => 'Тип изменения',
	'Class:Change/Attribute:parent_id_finalclass_recall+' => '',
	'Class:Change/Stimulus:ev_validate' => 'Подтвердить',
	'Class:Change/Stimulus:ev_validate+' => '',
	'Class:Change/Stimulus:ev_reject' => 'Отклонить',
	'Class:Change/Stimulus:ev_reject+' => '',
	'Class:Change/Stimulus:ev_assign' => 'Назначить',
	'Class:Change/Stimulus:ev_assign+' => '',
	'Class:Change/Stimulus:ev_reopen' => 'Вновь открыть',
	'Class:Change/Stimulus:ev_reopen+' => '',
	'Class:Change/Stimulus:ev_plan' => 'Планировать',
	'Class:Change/Stimulus:ev_plan+' => '',
	'Class:Change/Stimulus:ev_approve' => 'Утвердить',
	'Class:Change/Stimulus:ev_approve+' => '',
	'Class:Change/Stimulus:ev_replan' => 'Перепланировать',
	'Class:Change/Stimulus:ev_replan+' => '',
	'Class:Change/Stimulus:ev_notapprove' => 'Отклонить',
	'Class:Change/Stimulus:ev_notapprove+' => '',
	'Class:Change/Stimulus:ev_implement' => 'Реализовать',
	'Class:Change/Stimulus:ev_implement+' => '',
	'Class:Change/Stimulus:ev_monitor' => 'Наблюдать',
	'Class:Change/Stimulus:ev_monitor+' => '',
	'Class:Change/Stimulus:ev_finish' => 'Закончить',
	'Class:Change/Stimulus:ev_finish+' => '',
));

//
// Class: RoutineChange
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:RoutineChange' => 'Стандартное изменение',
	'Class:RoutineChange+' => '',
	'Class:RoutineChange/Stimulus:ev_validate' => 'Подтвердить',
	'Class:RoutineChange/Stimulus:ev_validate+' => '',
	'Class:RoutineChange/Stimulus:ev_reject' => 'Отклонить',
	'Class:RoutineChange/Stimulus:ev_reject+' => '',
	'Class:RoutineChange/Stimulus:ev_assign' => 'Назначить',
	'Class:RoutineChange/Stimulus:ev_assign+' => '',
	'Class:RoutineChange/Stimulus:ev_reopen' => 'Вновь открыть',
	'Class:RoutineChange/Stimulus:ev_reopen+' => '',
	'Class:RoutineChange/Stimulus:ev_plan' => 'Планировать',
	'Class:RoutineChange/Stimulus:ev_plan+' => '',
	'Class:RoutineChange/Stimulus:ev_approve' => 'Утвердить',
	'Class:RoutineChange/Stimulus:ev_approve+' => '',
	'Class:RoutineChange/Stimulus:ev_replan' => 'Перепланировать',
	'Class:RoutineChange/Stimulus:ev_replan+' => '',
	'Class:RoutineChange/Stimulus:ev_notapprove' => 'Отклонить',
	'Class:RoutineChange/Stimulus:ev_notapprove+' => '',
	'Class:RoutineChange/Stimulus:ev_implement' => 'Реализовать',
	'Class:RoutineChange/Stimulus:ev_implement+' => '',
	'Class:RoutineChange/Stimulus:ev_monitor' => 'Наблюдать',
	'Class:RoutineChange/Stimulus:ev_monitor+' => '',
	'Class:RoutineChange/Stimulus:ev_finish' => 'Закончить',
	'Class:RoutineChange/Stimulus:ev_finish+' => '',
));

//
// Class: ApprovedChange
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:ApprovedChange' => 'Утверждаемые изменения',
	'Class:ApprovedChange+' => '',
	'Class:ApprovedChange/Attribute:approval_date' => 'Дата утверждения',
	'Class:ApprovedChange/Attribute:approval_date+' => '',
	'Class:ApprovedChange/Attribute:approval_comment' => 'Коментарий утверждения',
	'Class:ApprovedChange/Attribute:approval_comment+' => '',
	'Class:ApprovedChange/Stimulus:ev_validate' => 'Подтвердить',
	'Class:ApprovedChange/Stimulus:ev_validate+' => '',
	'Class:ApprovedChange/Stimulus:ev_reject' => 'Отклонить',
	'Class:ApprovedChange/Stimulus:ev_reject+' => '',
	'Class:ApprovedChange/Stimulus:ev_assign' => 'Назначить',
	'Class:ApprovedChange/Stimulus:ev_assign+' => '',
	'Class:ApprovedChange/Stimulus:ev_reopen' => 'Вновь открыть',
	'Class:ApprovedChange/Stimulus:ev_reopen+' => '',
	'Class:ApprovedChange/Stimulus:ev_plan' => 'Планировать',
	'Class:ApprovedChange/Stimulus:ev_plan+' => '',
	'Class:ApprovedChange/Stimulus:ev_approve' => 'Утвердить',
	'Class:ApprovedChange/Stimulus:ev_approve+' => '',
	'Class:ApprovedChange/Stimulus:ev_replan' => 'Перепланировать',
	'Class:ApprovedChange/Stimulus:ev_replan+' => '',
	'Class:ApprovedChange/Stimulus:ev_notapprove' => 'Отклонить утверждение',
	'Class:ApprovedChange/Stimulus:ev_notapprove+' => '',
	'Class:ApprovedChange/Stimulus:ev_implement' => 'Реализовать',
	'Class:ApprovedChange/Stimulus:ev_implement+' => '',
	'Class:ApprovedChange/Stimulus:ev_monitor' => 'Наблюдать',
	'Class:ApprovedChange/Stimulus:ev_monitor+' => '',
	'Class:ApprovedChange/Stimulus:ev_finish' => 'Закончить',
	'Class:ApprovedChange/Stimulus:ev_finish+' => '',
));

//
// Class: NormalChange
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:NormalChange' => 'Нормальное изменение',
	'Class:NormalChange+' => '',
	'Class:NormalChange/Attribute:acceptance_date' => 'Дата принятия',
	'Class:NormalChange/Attribute:acceptance_date+' => '',
	'Class:NormalChange/Attribute:acceptance_comment' => 'Комментарий принятия',
	'Class:NormalChange/Attribute:acceptance_comment+' => '',
	'Class:NormalChange/Stimulus:ev_validate' => 'Подтвердить',
	'Class:NormalChange/Stimulus:ev_validate+' => '',
	'Class:NormalChange/Stimulus:ev_reject' => 'Отклонить',
	'Class:NormalChange/Stimulus:ev_reject+' => '',
	'Class:NormalChange/Stimulus:ev_assign' => 'Назначить',
	'Class:NormalChange/Stimulus:ev_assign+' => '',
	'Class:NormalChange/Stimulus:ev_reopen' => 'Вновь открыть',
	'Class:NormalChange/Stimulus:ev_reopen+' => '',
	'Class:NormalChange/Stimulus:ev_plan' => 'Планировать',
	'Class:NormalChange/Stimulus:ev_plan+' => '',
	'Class:NormalChange/Stimulus:ev_approve' => 'Утвердить',
	'Class:NormalChange/Stimulus:ev_approve+' => '',
	'Class:NormalChange/Stimulus:ev_replan' => 'Перепланировать',
	'Class:NormalChange/Stimulus:ev_replan+' => '',
	'Class:NormalChange/Stimulus:ev_notapprove' => 'Отклонить',
	'Class:NormalChange/Stimulus:ev_notapprove+' => '',
	'Class:NormalChange/Stimulus:ev_implement' => 'Реализовать',
	'Class:NormalChange/Stimulus:ev_implement+' => '',
	'Class:NormalChange/Stimulus:ev_monitor' => 'Наблюдать',
	'Class:NormalChange/Stimulus:ev_monitor+' => '',
	'Class:NormalChange/Stimulus:ev_finish' => 'Закончить',
	'Class:NormalChange/Stimulus:ev_finish+' => '',
));

//
// Class: EmergencyChange
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:EmergencyChange' => 'Экстренное изменение',
	'Class:EmergencyChange+' => '',
	'Class:EmergencyChange/Stimulus:ev_validate' => 'Подтвердить',
	'Class:EmergencyChange/Stimulus:ev_validate+' => '',
	'Class:EmergencyChange/Stimulus:ev_reject' => 'Отклонить',
	'Class:EmergencyChange/Stimulus:ev_reject+' => '',
	'Class:EmergencyChange/Stimulus:ev_assign' => 'Назначить',
	'Class:EmergencyChange/Stimulus:ev_assign+' => '',
	'Class:EmergencyChange/Stimulus:ev_reopen' => 'Вновь открыть',
	'Class:EmergencyChange/Stimulus:ev_reopen+' => '',
	'Class:EmergencyChange/Stimulus:ev_plan' => 'Планировать',
	'Class:EmergencyChange/Stimulus:ev_plan+' => '',
	'Class:EmergencyChange/Stimulus:ev_approve' => 'Утвердить',
	'Class:EmergencyChange/Stimulus:ev_approve+' => '',
	'Class:EmergencyChange/Stimulus:ev_replan' => 'Перепланировать',
	'Class:EmergencyChange/Stimulus:ev_replan+' => '',
	'Class:EmergencyChange/Stimulus:ev_notapprove' => 'Отклонить',
	'Class:EmergencyChange/Stimulus:ev_notapprove+' => '',
	'Class:EmergencyChange/Stimulus:ev_implement' => 'Реализовать',
	'Class:EmergencyChange/Stimulus:ev_implement+' => '',
	'Class:EmergencyChange/Stimulus:ev_monitor' => 'Наблюдать',
	'Class:EmergencyChange/Stimulus:ev_monitor+' => '',
	'Class:EmergencyChange/Stimulus:ev_finish' => 'Закончить',
	'Class:EmergencyChange/Stimulus:ev_finish+' => '',
));

?>
