<?php
// Copyright (C) 2010-2012 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>

/**
 * Localized data
 *
 * @author      Vladimir Shilov <shilow@ukr.net>
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
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
    	'Menu:Problem:Overview+' => 'Обзор',
    	'Menu:NewProblem' => 'Новая проблема',
    	'Menu:NewProblem+' => 'Новая проблема',
    	'Menu:SearchProblems' => 'Поиск проблем',
    	'Menu:SearchProblems+' => 'Поиск проблем',
    	'Menu:Problem:Shortcuts' => 'Ярлыки',
        'Menu:Problem:MyProblems' => 'Мои проблемы',
        'Menu:Problem:MyProblems+' => 'Мои проблемы',
        'Menu:Problem:OpenProblems' => 'Все открытые проблемы',
        'Menu:Problem:OpenProblems+' => 'Все открытые проблемы',
	'UI-ProblemManagementOverview-ProblemByService' => 'Проблемы по сервису',
	'UI-ProblemManagementOverview-ProblemByService+' => 'Проблемы по сервису',
	'UI-ProblemManagementOverview-ProblemByPriority' => 'Проблемы по приоритету',
	'UI-ProblemManagementOverview-ProblemByPriority+' => 'Проблемы по приоритету',
	'UI-ProblemManagementOverview-ProblemUnassigned' => 'Неназначенные проблемы',
	'UI-ProblemManagementOverview-ProblemUnassigned+' => 'Неназначенные проблемы',
	'UI:ProblemMgmtMenuOverview:Title' => 'Панель управление проблемами',
	'UI:ProblemMgmtMenuOverview:Title+' => 'Панель управление проблемами',

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
	'Class:Problem/Attribute:status/Value:assigned' => 'Подписана',
	'Class:Problem/Attribute:status/Value:assigned+' => '',
	'Class:Problem/Attribute:status/Value:resolved' => 'Решена',
	'Class:Problem/Attribute:status/Value:resolved+' => '',
	'Class:Problem/Attribute:status/Value:closed' => 'Закрыта',
	'Class:Problem/Attribute:status/Value:closed+' => '',
	'Class:Problem/Attribute:org_id' => 'Клиент',
	'Class:Problem/Attribute:org_id+' => '',
	'Class:Problem/Attribute:org_name' => 'Название',
	'Class:Problem/Attribute:org_name+' => 'Общее название',
	'Class:Problem/Attribute:service_id' => 'Услуга',
	'Class:Problem/Attribute:service_id+' => '',
	'Class:Problem/Attribute:service_name' => 'Название',
	'Class:Problem/Attribute:service_name+' => '',
	'Class:Problem/Attribute:servicesubcategory_id' => 'Категория услуги',
	'Class:Problem/Attribute:servicesubcategory_id+' => '',
	'Class:Problem/Attribute:servicesubcategory_name' => 'Название',
	'Class:Problem/Attribute:servicesubcategory_name+' => '',
	'Class:Problem/Attribute:product' => 'Продукт',
	'Class:Problem/Attribute:product+' => '',
	'Class:Problem/Attribute:impact' => 'Воздействие',
	'Class:Problem/Attribute:impact+' => '',
	'Class:Problem/Attribute:impact/Value:1' => 'Лицо',
	'Class:Problem/Attribute:impact/Value:1+' => '',
	'Class:Problem/Attribute:impact/Value:2' => 'Сервис',
	'Class:Problem/Attribute:impact/Value:2+' => '',
	'Class:Problem/Attribute:impact/Value:3' => 'Департамент',
	'Class:Problem/Attribute:impact/Value:3+' => '',
	'Class:Problem/Attribute:urgency' => 'Срочность',
	'Class:Problem/Attribute:urgency+' => '',
	'Class:Problem/Attribute:urgency/Value:1' => 'Низкая',
	'Class:Problem/Attribute:urgency/Value:1+' => 'Низкая',
	'Class:Problem/Attribute:urgency/Value:2' => 'Средняя',
	'Class:Problem/Attribute:urgency/Value:2+' => 'Средняя',
	'Class:Problem/Attribute:urgency/Value:3' => 'Высокая',
	'Class:Problem/Attribute:urgency/Value:3+' => 'Высокая',
	'Class:Problem/Attribute:priority' => 'Приоритет',
	'Class:Problem/Attribute:priority+' => '',
	'Class:Problem/Attribute:priority/Value:1' => 'Низкий',
	'Class:Problem/Attribute:priority/Value:1+' => '',
	'Class:Problem/Attribute:priority/Value:2' => 'Средний',
	'Class:Problem/Attribute:priority/Value:2+' => '',
	'Class:Problem/Attribute:priority/Value:3' => 'Высокий',
	'Class:Problem/Attribute:priority/Value:3+' => '',
	'Class:Problem/Attribute:workgroup_id' => 'Рабочая группа',
	'Class:Problem/Attribute:workgroup_id+' => '',
	'Class:Problem/Attribute:workgroup_name' => 'Название',
	'Class:Problem/Attribute:workgroup_name+' => '',
	'Class:Problem/Attribute:agent_id' => 'Агент',
	'Class:Problem/Attribute:agent_id+' => '',
	'Class:Problem/Attribute:agent_name' => 'Имя агента',
	'Class:Problem/Attribute:agent_name+' => '',
	'Class:Problem/Attribute:agent_email' => 'e-mail агента',
	'Class:Problem/Attribute:agent_email+' => '',
	'Class:Problem/Attribute:related_change_id' => 'Связанные изменения',
	'Class:Problem/Attribute:related_change_id+' => '',
	'Class:Problem/Attribute:related_change_ref' => 'Ссылка',
	'Class:Problem/Attribute:related_change_ref+' => '',
	'Class:Problem/Attribute:close_date' => 'Дата закрытия',
	'Class:Problem/Attribute:close_date+' => '',
	'Class:Problem/Attribute:last_update' => 'Последнее обновление',
	'Class:Problem/Attribute:last_update+' => '',
	'Class:Problem/Attribute:assignment_date' => 'Дата назначения',
	'Class:Problem/Attribute:assignment_date+' => '',
	'Class:Problem/Attribute:resolution_date' => 'Дата решения',
	'Class:Problem/Attribute:resolution_date+' => '',
	'Class:Problem/Attribute:knownerrors_list' => 'Известные ошибки',
	'Class:Problem/Attribute:knownerrors_list+' => '',
	'Class:Problem/Stimulus:ev_assign' => 'Назначить',
	'Class:Problem/Stimulus:ev_assign+' => '',
	'Class:Problem/Stimulus:ev_reassign' => 'Переназначить',
	'Class:Problem/Stimulus:ev_reassign+' => '',
	'Class:Problem/Stimulus:ev_resolve' => 'Решение',
	'Class:Problem/Stimulus:ev_resolve+' => '',
	'Class:Problem/Stimulus:ev_close' => 'Закрыть',
	'Class:Problem/Stimulus:ev_close+' => '',
));

?>
