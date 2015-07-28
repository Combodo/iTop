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

//////////////////////////////////////////////////////////////////////
// Classes in 'bizmodel'
//////////////////////////////////////////////////////////////////////
//

// Dictionnay conventions
// Class:<class_name>
// Class:<class_name>+
// Class:<class_name>/Attribute:<attribute_code>
// Class:<class_name>/Attribute:<attribute_code>+
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>+
// Class:<class_name>/Stimulus:<stimulus_code>
// Class:<class_name>/Stimulus:<stimulus_code>+




Dict::Add('RU RU', 'Russian', 'Русский', array(
        'Menu:ProblemManagement' => 'Управление проблемами',
        'Menu:ProblemManagement+' => 'Управление проблемами',
    	'Menu:Problem:Overview' => 'Обзор',
    	'Menu:Problem:Overview+' => 'Overview',
    	'Menu:NewProblem' => 'Создать проблему',
    	'Menu:NewProblem+' => 'Новая проблема',
    	'Menu:SearchProblems' => 'Найти проблему',
    	'Menu:SearchProblems+' => 'Search for problems',
    	'Menu:Problem:Shortcuts' => 'Ярлыки',
        'Menu:Problem:MyProblems' => 'Назначенные мне проблемы',
        'Menu:Problem:MyProblems+' => 'Мои проблемы',
        'Menu:Problem:OpenProblems' => 'Открытые проблемы',
        'Menu:Problem:OpenProblems+' => 'Все открытые проблемы',
	'UI-ProblemManagementOverview-ProblemByService' => 'Проблемы по сервису',
	'UI-ProblemManagementOverview-ProblemByService+' => 'Проблемы по сервису',
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
	'Class:Problem/Attribute:service_name' => 'Имя услуги',
	'Class:Problem/Attribute:service_name+' => '',
	'Class:Problem/Attribute:servicesubcategory_id' => 'Тип запроса',
	'Class:Problem/Attribute:servicesubcategory_id+' => '',
	'Class:Problem/Attribute:servicesubcategory_name' => 'Тип запроса',
	'Class:Problem/Attribute:servicesubcategory_name+' => '',
	'Class:Problem/Attribute:product' => 'Продукт',
	'Class:Problem/Attribute:product+' => '',
	'Class:Problem/Attribute:impact' => 'Влияние',
	'Class:Problem/Attribute:impact+' => '',
	'Class:Problem/Attribute:impact/Value:1' => 'Услуга',
	'Class:Problem/Attribute:impact/Value:1+' => '',
	'Class:Problem/Attribute:impact/Value:2' => 'Отдел',
	'Class:Problem/Attribute:impact/Value:2+' => '',
	'Class:Problem/Attribute:impact/Value:3' => 'Персона',
	'Class:Problem/Attribute:impact/Value:3+' => '',
	'Class:Problem/Attribute:urgency' => 'Срочность',
	'Class:Problem/Attribute:urgency+' => '',
	'Class:Problem/Attribute:urgency/Value:1' => 'Критическая',
	'Class:Problem/Attribute:urgency/Value:1+' => 'critical',
	'Class:Problem/Attribute:urgency/Value:2' => 'Высокая',
	'Class:Problem/Attribute:urgency/Value:2+' => 'high',
	'Class:Problem/Attribute:urgency/Value:3' => 'Средняя',
	'Class:Problem/Attribute:urgency/Value:3+' => 'medium',
	'Class:Problem/Attribute:urgency/Value:4' => 'Низкая',
	'Class:Problem/Attribute:urgency/Value:4+' => 'low',
	'Class:Problem/Attribute:priority' => 'Приоритет',
	'Class:Problem/Attribute:priority+' => '',
	'Class:Problem/Attribute:priority/Value:1' => 'Критический',
	'Class:Problem/Attribute:priority/Value:1+' => 'Critical',
	'Class:Problem/Attribute:priority/Value:2' => 'Высокий',
	'Class:Problem/Attribute:priority/Value:2+' => 'High',
	'Class:Problem/Attribute:priority/Value:3' => 'Средний',
	'Class:Problem/Attribute:priority/Value:3+' => 'Medium',
	'Class:Problem/Attribute:priority/Value:4' => 'Низкий',
	'Class:Problem/Attribute:priority/Value:4+' => 'Low',
	'Class:Problem/Attribute:related_change_id' => 'Связанное изменение',
	'Class:Problem/Attribute:related_change_id+' => '',
	'Class:Problem/Attribute:related_change_ref' => 'Ссылка на изменение',
	'Class:Problem/Attribute:related_change_ref+' => '',
	'Class:Problem/Attribute:assignment_date' => 'Назначение',
	'Class:Problem/Attribute:assignment_date+' => '',
	'Class:Problem/Attribute:resolution_date' => 'Решение',
	'Class:Problem/Attribute:resolution_date+' => '',
	'Class:Problem/Attribute:knownerrors_list' => 'Известные ошибки',
	'Class:Problem/Attribute:knownerrors_list+' => 'Связанные известные ошибки',
	'Class:Problem/Attribute:related_request_list' => 'Запросы',
	'Class:Problem/Attribute:related_request_list+' => 'Связанные запросы',
	'Class:Problem/Stimulus:ev_assign' => 'Назначить',
	'Class:Problem/Stimulus:ev_assign+' => '',
	'Class:Problem/Stimulus:ev_reassign' => 'Переназначить',
	'Class:Problem/Stimulus:ev_reassign+' => '',
	'Class:Problem/Stimulus:ev_resolve' => 'Отметить как решенную',
	'Class:Problem/Stimulus:ev_resolve+' => '',
	'Class:Problem/Stimulus:ev_close' => 'Закрыть',
	'Class:Problem/Stimulus:ev_close+' => '',
	'Class:Problem/Attribute:related_incident_list' => 'Related incidents~~',
	'Class:Problem/Attribute:related_incident_list+' => 'All the incidents that are related to this problem~~',
));

?>
