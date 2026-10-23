<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2026 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * 
 */
Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Menu:ChangeManagement' => 'Управління змінами',
	'Menu:Change:Overview' => 'Огляд',
	'Menu:Change:Overview+' => 'Управління змінами - Огляд',
	'Menu:NewChange' => 'Новий запит на зміну',
	'Menu:NewChange+' => 'Створення нового запиту на зміну',
	'Menu:SearchChanges' => 'Пошук змін',
	'Menu:SearchChanges+' => 'Пошук запитів на зміни',
	'Menu:Change:Shortcuts' => 'Ярлики',
	'Menu:Change:Shortcuts+' => 'Ярлики',
	'Menu:WaitingAcceptance' => 'Очікують прийняття',
	'Menu:WaitingAcceptance+' => 'Зміни, що очікують прийняття',
	'Menu:WaitingApproval' => 'Очікують затвердження',
	'Menu:WaitingApproval+' => 'Зміни, що очікують затвердження',
	'Menu:Changes' => 'Відкриті',
	'Menu:Changes+' => 'Відкриті зміни',
	'Menu:MyChanges' => 'Призначені мені',
	'Menu:MyChanges+' => 'Зміни, призначені мені',
	'UI-ChangeManagementOverview-ChangeByCategory-last-7-days' => 'Зміни за категоріями за 7 днів',
	'UI-ChangeManagementOverview-Last-7-days' => 'Кількість змін за 7 днів',
	'UI-ChangeManagementOverview-ChangeByDomain-last-7-days' => 'Зміни за доменом за 7 днів',
	'UI-ChangeManagementOverview-ChangeByStatus-last-7-days' => 'Зміни за статусом за 7 днів',
	'Tickets:Related:OpenChanges' => 'Відкриті зміни',
	'Tickets:Related:RecentChanges' => 'Недавні зміни (72год)',
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

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:Change' => 'Зміна',
	'Class:Change+' => '',
	'Class:Change/Attribute:status' => 'Статус',
	'Class:Change/Attribute:status+' => '',
	'Class:Change/Attribute:status/Value:new' => 'Новий',
	'Class:Change/Attribute:status/Value:new+' => '',
	'Class:Change/Attribute:status/Value:assigned' => 'Призначений',
	'Class:Change/Attribute:status/Value:assigned+' => '',
	'Class:Change/Attribute:status/Value:planned' => 'Запланований',
	'Class:Change/Attribute:status/Value:planned+' => '',
	'Class:Change/Attribute:status/Value:rejected' => 'Відхилений',
	'Class:Change/Attribute:status/Value:rejected+' => '',
	'Class:Change/Attribute:status/Value:approved' => 'Затверджений',
	'Class:Change/Attribute:status/Value:approved+' => '',
	'Class:Change/Attribute:status/Value:closed' => 'Закритий',
	'Class:Change/Attribute:status/Value:closed+' => '',
	'Class:Change/Attribute:category' => 'Категорія',
	'Class:Change/Attribute:category+' => '',
	'Class:Change/Attribute:category/Value:application' => 'Застосунок',
	'Class:Change/Attribute:category/Value:application+' => 'Застосунок',
	'Class:Change/Attribute:category/Value:hardware' => 'Обладнання',
	'Class:Change/Attribute:category/Value:hardware+' => 'Обладнання',
	'Class:Change/Attribute:category/Value:network' => 'Мережа',
	'Class:Change/Attribute:category/Value:network+' => 'Мережа',
	'Class:Change/Attribute:category/Value:other' => 'Інше',
	'Class:Change/Attribute:category/Value:other+' => 'Інше',
	'Class:Change/Attribute:category/Value:software' => 'Програмне забезпечення',
	'Class:Change/Attribute:category/Value:software+' => 'Програмне забезпечення',
	'Class:Change/Attribute:category/Value:system' => 'Система',
	'Class:Change/Attribute:category/Value:system+' => 'Система',
	'Class:Change/Attribute:reject_reason' => 'Причина відхилення',
	'Class:Change/Attribute:reject_reason+' => '',
	'Class:Change/Attribute:changemanager_id' => 'Менеджер зміни',
	'Class:Change/Attribute:changemanager_id+' => '',
	'Class:Change/Attribute:changemanager_email' => 'Email менеджера',
	'Class:Change/Attribute:changemanager_email+' => '',
	'Class:Change/Attribute:parent_id' => 'Батьківська зміна',
	'Class:Change/Attribute:parent_id+' => '',
	'Class:Change/Attribute:parent_name' => 'Батьківська зміна',
	'Class:Change/Attribute:parent_name+' => '',
	'Class:Change/Attribute:creation_date' => 'Дата створення',
	'Class:Change/Attribute:creation_date+' => '',
	'Class:Change/Attribute:approval_date' => 'Дата затвердження',
	'Class:Change/Attribute:approval_date+' => '',
	'Class:Change/Attribute:fallback_plan' => 'План відкату',
	'Class:Change/Attribute:fallback_plan+' => '',
	'Class:Change/Attribute:related_request_list' => 'Пов\'язані запити',
	'Class:Change/Attribute:related_request_list+' => 'Пов\'язані запити',
	'Class:Change/Attribute:related_incident_list' => 'Пов\'язані інциденти',
	'Class:Change/Attribute:related_incident_list+' => 'Пов\'язані інциденти',
	'Class:Change/Attribute:related_problems_list' => 'Пов\'язані проблеми',
	'Class:Change/Attribute:related_problems_list+' => 'Пов\'язані проблеми',
	'Class:Change/Attribute:child_changes_list' => 'Дочірні зміни',
	'Class:Change/Attribute:child_changes_list+' => 'Дочірні зміни',
	'Class:Change/Attribute:parent_id_friendlyname' => 'Батьківська зміна',
	'Class:Change/Attribute:parent_id_friendlyname+' => '',
	'Class:Change/Stimulus:ev_assign' => 'Призначити',
	'Class:Change/Stimulus:ev_assign+' => '',
	'Class:Change/Stimulus:ev_plan' => 'Планувати',
	'Class:Change/Stimulus:ev_plan+' => '',
	'Class:Change/Stimulus:ev_reject' => 'Відхилити',
	'Class:Change/Stimulus:ev_reject+' => '',
	'Class:Change/Stimulus:ev_reopen' => 'Знову відкрити',
	'Class:Change/Stimulus:ev_reopen+' => '',
	'Class:Change/Stimulus:ev_approve' => 'Затвердити',
	'Class:Change/Stimulus:ev_approve+' => '',
	'Class:Change/Stimulus:ev_finish' => 'Закрити',
	'Class:Change/Stimulus:ev_finish+' => '',
	'Class:Change/Attribute:outage' => 'Простій сервісу',
	'Class:Change/Attribute:outage+' => '',
	'Class:Change/Attribute:outage/Value:no' => 'Ні',
	'Class:Change/Attribute:outage/Value:no+' => '',
	'Class:Change/Attribute:outage/Value:yes' => 'Так',
	'Class:Change/Attribute:outage/Value:yes+' => '',
));
