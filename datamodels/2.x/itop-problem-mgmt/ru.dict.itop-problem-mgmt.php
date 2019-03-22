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
	'Menu:ProblemManagement' => 'Управление проблемами',
	'Menu:ProblemManagement+' => 'Управление проблемами',
	'Menu:Problem:Overview' => 'Обзор',
	'Menu:Problem:Overview+' => 'Управление проблемами - Обзор',
	'Menu:NewProblem' => 'Новая проблема',
	'Menu:NewProblem+' => 'Создать новую проблему',
	'Menu:SearchProblems' => 'Поиск проблем',
	'Menu:SearchProblems+' => 'Поиск проблем',
	'Menu:Problem:Shortcuts' => 'Ярлыки',
	'Menu:Problem:MyProblems' => 'Назначенные мне',
	'Menu:Problem:MyProblems+' => 'Назначенные мне проблемы',
	'Menu:Problem:OpenProblems' => 'Открытые',
	'Menu:Problem:OpenProblems+' => 'Все открытые проблемы',
	'UI-ProblemManagementOverview-ProblemByService' => 'Проблемы по услугам',
	'UI-ProblemManagementOverview-ProblemByService+' => 'Проблемы по услугам',
	'UI-ProblemManagementOverview-ProblemByPriority' => 'Проблемы по приоритету',
	'UI-ProblemManagementOverview-ProblemByPriority+' => 'Проблемы по приоритету',
	'UI-ProblemManagementOverview-ProblemUnassigned' => 'Неназначенные проблемы',
	'UI-ProblemManagementOverview-ProblemUnassigned+' => 'Неназначенные проблемы',
	'UI:ProblemMgmtMenuOverview:Title' => 'Панель управления проблемами',
	'UI:ProblemMgmtMenuOverview:Title+' => 'Панель управления проблемами',

));
//
// Class: Problem
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Problem' => 'Проблема',
	'Class:Problem+' => '',
	'Class:Problem/Attribute:status' => 'Статус',
	'Class:Problem/Attribute:status+' => '',
	'Class:Problem/Attribute:status/Value:new' => 'Новая',
	'Class:Problem/Attribute:status/Value:new+' => '',
	'Class:Problem/Attribute:status/Value:assigned' => 'Назначена',
	'Class:Problem/Attribute:status/Value:assigned+' => '',
	'Class:Problem/Attribute:status/Value:resolved' => 'Решена',
	'Class:Problem/Attribute:status/Value:resolved+' => '',
	'Class:Problem/Attribute:status/Value:closed' => 'Закрыта',
	'Class:Problem/Attribute:status/Value:closed+' => '',
	'Class:Problem/Attribute:service_id' => 'Услуга',
	'Class:Problem/Attribute:service_id+' => '',
	'Class:Problem/Attribute:service_name' => 'Услуга',
	'Class:Problem/Attribute:service_name+' => '',
	'Class:Problem/Attribute:servicesubcategory_id' => 'Подкатегория',
	'Class:Problem/Attribute:servicesubcategory_id+' => 'Подкатегория услуги',
	'Class:Problem/Attribute:servicesubcategory_name' => 'Подкатегория услуги',
	'Class:Problem/Attribute:servicesubcategory_name+' => '',
	'Class:Problem/Attribute:product' => 'Продукт',
	'Class:Problem/Attribute:product+' => '',
	'Class:Problem/Attribute:impact' => 'Влияние',
	'Class:Problem/Attribute:impact+' => '',
	'Class:Problem/Attribute:impact/Value:1' => 'Департамент',
	'Class:Problem/Attribute:impact/Value:1+' => '',
	'Class:Problem/Attribute:impact/Value:2' => 'Служба',
	'Class:Problem/Attribute:impact/Value:2+' => '',
	'Class:Problem/Attribute:impact/Value:3' => 'Персона',
	'Class:Problem/Attribute:impact/Value:3+' => '',
	'Class:Problem/Attribute:urgency' => 'Срочность',
	'Class:Problem/Attribute:urgency+' => '',
	'Class:Problem/Attribute:urgency/Value:1' => 'Критическая',
	'Class:Problem/Attribute:urgency/Value:1+' => 'Критическая',
	'Class:Problem/Attribute:urgency/Value:2' => 'Высокая',
	'Class:Problem/Attribute:urgency/Value:2+' => 'Высокая',
	'Class:Problem/Attribute:urgency/Value:3' => 'Средняя',
	'Class:Problem/Attribute:urgency/Value:3+' => 'Средняя',
	'Class:Problem/Attribute:urgency/Value:4' => 'Низкая',
	'Class:Problem/Attribute:urgency/Value:4+' => 'Низкая',
	'Class:Problem/Attribute:priority' => 'Приоритет',
	'Class:Problem/Attribute:priority+' => '',
	'Class:Problem/Attribute:priority/Value:1' => 'Критический',
	'Class:Problem/Attribute:priority/Value:1+' => 'Критический',
	'Class:Problem/Attribute:priority/Value:2' => 'Высокий',
	'Class:Problem/Attribute:priority/Value:2+' => 'Высокий',
	'Class:Problem/Attribute:priority/Value:3' => 'Средний',
	'Class:Problem/Attribute:priority/Value:3+' => 'Средний',
	'Class:Problem/Attribute:priority/Value:4' => 'Низкий',
	'Class:Problem/Attribute:priority/Value:4+' => 'Низкий',
	'Class:Problem/Attribute:related_change_id' => 'Связанное изменение',
	'Class:Problem/Attribute:related_change_id+' => '',
	'Class:Problem/Attribute:related_change_ref' => 'Связанное изменение',
	'Class:Problem/Attribute:related_change_ref+' => '',
	'Class:Problem/Attribute:assignment_date' => 'Дата назначения',
	'Class:Problem/Attribute:assignment_date+' => '',
	'Class:Problem/Attribute:resolution_date' => 'Дата решения',
	'Class:Problem/Attribute:resolution_date+' => '',
	'Class:Problem/Attribute:knownerrors_list' => 'Известные ошибки',
	'Class:Problem/Attribute:knownerrors_list+' => 'Связанные известные ошибки',
	'Class:Problem/Attribute:related_request_list' => 'Запросы',
	'Class:Problem/Attribute:related_request_list+' => 'Связанные запросы',
	'Class:Problem/Attribute:related_incident_list' => 'Инциденты',
	'Class:Problem/Attribute:related_incident_list+' => 'Связанные инциденты',
	'Class:Problem/Stimulus:ev_assign' => 'Назначить',
	'Class:Problem/Stimulus:ev_assign+' => '',
	'Class:Problem/Stimulus:ev_reassign' => 'Переназначить',
	'Class:Problem/Stimulus:ev_reassign+' => '',
	'Class:Problem/Stimulus:ev_resolve' => 'Отметить как решенную',
	'Class:Problem/Stimulus:ev_resolve+' => '',
	'Class:Problem/Stimulus:ev_close' => 'Закрыть',
	'Class:Problem/Stimulus:ev_close+' => '',
));
