<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * 
 */

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
    'Class:AuditCategory' => 'Категорія аудиту',
    'Class:AuditCategory+' => 'Розділ всередині загального аудиту',
    'Class:AuditCategory/Attribute:name' => "Назва категорії",
    'Class:AuditCategory/Attribute:name+' => "Коротка назва для цієї категорії",
    'Class:AuditCategory/Attribute:description' => 'Опис категорії аудиту',
    'Class:AuditCategory/Attribute:description+' => 'Повний опис категорії аудиту',
    'Class:AuditCategory/Attribute:definition_set' => 'Набір обʼєктів',
    'Class:AuditCategory/Attribute:definition_set+' => 'OQL-вираз, який визначає набір обʼєктів для перевірки',
    'Class:AuditCategory/Attribute:rules_list' => 'Правила аудиту',
    'Class:AuditCategory/Attribute:rules_list+' => 'Правила аудиту для цієї категорії',
    'Class:AuditCategory/Attribute:ok_error_tolerance' => 'Поріг попередження~~',
    'Class:AuditCategory/Attribute:ok_error_tolerance+' => 'Відсоток невалідних обʼєктів, нижче якого результат — попередження (помаранчевий)~~',
    'Class:AuditCategory/Attribute:warning_error_tolerance' => 'Поріг помилки~~',
    'Class:AuditCategory/Attribute:warning_error_tolerance+' => 'Відсоток невалідних обʼєктів, нижче якого результат — помилка (червоний)~~',
    'Class:AuditCategory/Attribute:domains_list' => 'Домени~~',
    'Class:AuditCategory/Attribute:domains_list+' => 'Домени, що включають цю категорію~~',
));

//
// Class: AuditRule
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
    'Class:AuditRule' => 'Правило аудиту',
    'Class:AuditRule+' => 'Правило для перевірки цієї категорії аудиту',
    'Class:AuditRule/Attribute:name' => 'Назва правила',
    'Class:AuditRule/Attribute:name+' => 'Коротка назва цього правила',
    'Class:AuditRule/Attribute:description' => 'Опис правила аудиту',
    'Class:AuditRule/Attribute:description+' => 'Повний опис цього правила аудиту',
    'Class:AuditRule/Attribute:query' => 'Запит для виконання',
    'Class:AuditRule/Attribute:query+' => 'OQL-вираз, що виконує перевірку набору обʼєктів категорії аудиту',
    'Class:AuditRule/Attribute:valid_flag' => 'Валідні обʼєкти?',
    'Class:AuditRule/Attribute:valid_flag+' => "Виберіть 'Істина', якщо правило повертає обʼєкти, які успішно пройшли перевірку, інакше виберіть 'Хибність'.",
    'Class:AuditRule/Attribute:valid_flag/Value:true' => 'Істина',
    'Class:AuditRule/Attribute:valid_flag/Value:true+' => 'Повернуті обʼєкти вважаються такими, що пройшли перевірку',
    'Class:AuditRule/Attribute:valid_flag/Value:false' => 'Хибність',
    'Class:AuditRule/Attribute:valid_flag/Value:false+' => 'Повернуті обʼєкти вважаються такими, що НЕ пройшли перевірку',
    'Class:AuditRule/Attribute:category_id' => 'Категорія',
    'Class:AuditRule/Attribute:category_id+' => 'Категорія для цього правила',
    'Class:AuditRule/Attribute:category_name' => 'Категорія',
    'Class:AuditRule/Attribute:category_name+' => 'Категорія для цього правила',
));

//
// Class: AuditDomain
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
    'Class:AuditDomain' => 'Аудиторський домен~~',
    'Class:AuditDomain+' => 'Аудиторські домени дозволяють групувати категорії аудиту. Домен зазвичай відповідає тим, хто несе відповідальність за перевірку та виправлення помилок~~',
    'Class:AuditDomain/Attribute:name' => 'Назва~~',
    'Class:AuditDomain/Attribute:name+' => 'Сегментація аудиту за відповідальними за виправлення або зацікавленими~~',
    'Class:AuditDomain/Attribute:description' => 'Опис~~',
    'Class:AuditDomain/Attribute:description+' => '~~',
    'Class:AuditDomain/Attribute:icon' => 'Іконка~~',
    'Class:AuditDomain/Attribute:icon+' => '~~',
    'Class:AuditDomain/Attribute:categories_list' => 'Категорії~~',
    'Class:AuditDomain/Attribute:categories_list+' => 'Пов’язані категорії аудиту. Під час запуску аудиту для домену перевіряються лише пов’язані категорії аудиту~~',
));

//
// Class: lnkAuditCategoryToAuditDomain
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
    'Class:lnkAuditCategoryToAuditDomain' => 'Зв’язок Категорія аудиту / Аудиторський домен~~',
    'Class:lnkAuditCategoryToAuditDomain+' => '~~',
    'Class:lnkAuditCategoryToAuditDomain/Attribute:category_id' => 'Категорія~~',
    'Class:lnkAuditCategoryToAuditDomain/Attribute:category_id+' => 'Категорія аудиту~~',
    'Class:lnkAuditCategoryToAuditDomain/Attribute:category_name' => "Назва категорії~~",
    'Class:lnkAuditCategoryToAuditDomain/Attribute:category_name+' => "Назва категорії аудиту~~",
    'Class:lnkAuditCategoryToAuditDomain/Attribute:domain_id' => 'Домен~~',
    'Class:lnkAuditCategoryToAuditDomain/Attribute:domain_id+' => 'Аудиторський домен~~',
    'Class:lnkAuditCategoryToAuditDomain/Attribute:domain_name' => "Назва домену~~",
    'Class:lnkAuditCategoryToAuditDomain/Attribute:domain_name+' => "Назва аудиторського домену~~",
));

//
// Class: QueryOQL
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
    'Class:Query' => 'Запит',
    'Class:Query+' => 'Запит — це набір даних, визначених динамічним шляхом',
    'Class:Query/Attribute:name' => 'Назва',
    'Class:Query/Attribute:name+' => 'Ідентифікує запит',
    'Class:Query/Attribute:description' => 'Опис',
    'Class:Query/Attribute:description+' => 'Детальний опис запиту (призначення, використання тощо)',
    'Class:Query/Attribute:is_template' => 'Шаблон для OQL-полів~~',
    'Class:Query/Attribute:is_template+' => 'Може використовуватися як джерело для отримувача OQL у сповіщеннях~~',
    'Class:Query/Attribute:is_template/Value:yes' => 'Так~~',
    'Class:Query/Attribute:is_template/Value:no' => 'Ні~~',
    'Class:Query/Attribute:export_count' => 'Лічильник експорту~~',
    'Class:Query/Attribute:export_count+' => 'Лічильник, що відображає, скільки разів цей запит був виконаний~~',
    'Class:Query/Attribute:export_last_date' => 'Останній експорт~~',
    'Class:Query/Attribute:export_last_date+' => 'Дата і час останнього виконання експорту~~',
    'Class:Query/Attribute:export_last_user_id' => 'Користувач~~',
    'Class:Query/Attribute:export_last_user_id+' => 'Користувач, який виконав останній експорт~~',
    'Class:Query/Attribute:export_last_user_contact' => 'Контакт~~',
    'Class:Query/Attribute:export_last_user_contact+' => 'Контакт, який виконав останній експорт~~',
    'Query:baseinfo' => 'Загальна інформація~~',
    'Query:exportInfo' => 'Інформація про експорт~~',
    'Class:QueryOQL/Attribute:fields' => 'Поля експорту',
    'Class:QueryOQL/Attribute:fields+' => 'Список атрибутів для експорту, розділених комами (або alias.attribute)',
    'Class:QueryOQL' => 'OQL-запит',
    'Class:QueryOQL+' => 'Запит, заснований на OQL (Object Query Language)',
    'Class:QueryOQL/Attribute:oql' => 'Вираз',
    'Class:QueryOQL/Attribute:oql+' => 'OQL-вираз',
));

//////////////////////////////////////////////////////////////////////
// Classes in 'addon/userrights'
//////////////////////////////////////////////////////////////////////

//
//
// Class: User
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
    'Class:User' => 'Користувач',
    'Class:User+' => 'Користувач',
    'Class:User/Attribute:finalclass' => 'Тип облікового запису',
    'Class:User/Attribute:finalclass+' => '',
    'Class:User/Attribute:contactid' => 'Персона',
    'Class:User/Attribute:contactid+' => '',
    'Class:User/Attribute:org_id' => 'Організація',
    'Class:User/Attribute:org_id+' => 'Організація повʼязаної персони',
    'Class:User/Attribute:last_name' => 'Прізвище',
    'Class:User/Attribute:last_name+' => 'Прізвище повʼязаної персони',
    'Class:User/Attribute:first_name' => "Ім'я",
    'Class:User/Attribute:first_name+' => "Ім'я повʼязаної персони",
    'Class:User/Attribute:email' => 'Email',
    'Class:User/Attribute:email+' => 'Адреса електронної пошти повʼязаної персони',
    'Class:User/Attribute:login' => 'Логін',
    'Class:User/Attribute:login+' => 'Унікальний логін користувача',
    'Class:User/Attribute:language' => 'Мова',
    'Class:User/Attribute:language+' => 'Мова користувача',
    'Class:User/Attribute:language/Value:EN US' => 'Англійська',
    'Class:User/Attribute:language/Value:EN US+' => 'Англійська (США)',
    'Class:User/Attribute:language/Value:FR FR' => 'Французька',
    'Class:User/Attribute:language/Value:FR FR+' => 'Французька (Франція)',
    'Class:User/Attribute:profile_list' => 'Профілі',
    'Class:User/Attribute:profile_list+' => 'Профілі, що надають права цьому користувачу',
    'Class:User/Attribute:allowed_org_list' => 'Дозволені організації',
    'Class:User/Attribute:allowed_org_list+' => 'Користувач може бачити дані лише зазначених нижче організацій. Залиште поле порожнім для доступу до всіх даних.',
    'Class:User/Attribute:status' => 'Статус',
    'Class:User/Attribute:status+' => 'Обліковий запис користувача увімкнений або вимкнений.',
    'Class:User/Attribute:status/Value:enabled' => 'Увімкнено',
    'Class:User/Attribute:status/Value:disabled' => 'Вимкнено',
    'Class:User/Error:LoginMustBeUnique' => 'Логін має бути унікальним — "%1s" вже використовується.',
    'Class:User/Error:AtLeastOneProfileIsNeeded' => 'Щонайменше один профіль має бути призначений цьому користувачу.',
    'Class:User/Error:ProfileNotAllowed' => 'Profile "%1$s" cannot be added it will deny the access to backoffice~~',
    'Class:User/Error:StatusChangeIsNotAllowed' => 'Changing status is not allowed for your own User~~',
    'Class:User/Error:AllowedOrgsMustContainUserOrg' => 'Allowed organizations must contain User organization~~',
    'Class:User/Error:CurrentProfilesHaveInsufficientRights' => 'The current list of profiles does not give sufficient access rights (Users are not modifiable anymore)~~',
    'Class:User/Error:PortalPowerUserHasInsufficientRights' => 'The Portal power user profile does not give sufficient access rights (another profile must be added)~~',
    'Class:User/Error:AtLeastOneOrganizationIsNeeded' => 'Цьому користувачу має бути призначена хоча б одна організація.',
    'Class:User/Error:OrganizationNotAllowed' => 'Організація не дозволена.',
    'Class:User/Error:UserOrganizationNotAllowed' => 'Обліковий запис користувача не належить до ваших дозволених організацій.',
    'Class:User/Error:PersonIsMandatory' => 'Необхідно вибрати персону.',
    'Class:User/Warning:NoOrganizationMeansFullAccess' => 'No "Allowed Organizations" defined for this user, this means they have access to all organizations~~',
    'Class:User/Warning:NoContactHasImpact' => 'Cautious: there is no Person defined on this User, this prevents access to portals, news notifications and other side effects in back-office~~',
    'Class:UserInternal' => 'Внутрішній користувач',
    'Class:UserInternal+' => "Обліковий запис створено всередині ".ITOP_APPLICATION_SHORT,
));

//
// Class: URP_Profiles
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
    'Class:URP_Profiles' => 'Профіль',
    'Class:URP_Profiles+' => 'Користувацький профіль',
    'Class:URP_Profiles/Attribute:name' => 'Назва',
    'Class:URP_Profiles/Attribute:name+' => 'Назва',
    'Class:URP_Profiles/Attribute:description' => 'Опис',
    'Class:URP_Profiles/Attribute:description+' => 'Опис',
    'Class:URP_Profiles/Attribute:user_list' => 'Користувачі',
    'Class:URP_Profiles/Attribute:user_list+' => 'Користувачі, які мають цю роль',
));
//
// Class: URP_Dimensions
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
    'Class:URP_Dimensions' => 'Вимір',
    'Class:URP_Dimensions+' => 'застосування виміру (визначення силосів)',
    'Class:URP_Dimensions/Attribute:name' => 'Назва',
    'Class:URP_Dimensions/Attribute:name+' => 'мітка',
    'Class:URP_Dimensions/Attribute:description' => 'Опис',
    'Class:URP_Dimensions/Attribute:description+' => 'короткий опис',
    'Class:URP_Dimensions/Attribute:type' => 'Тип',
    'Class:URP_Dimensions/Attribute:type+' => 'імʼя класу або типу даних (проекційний блок)',
));

//
// Class: URP_UserProfile
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
    'Class:URP_UserProfile' => 'Профіль користувача',
    'Class:URP_UserProfile+' => 'Профіль користувача',
    'Class:URP_UserProfile/Name' => 'Звʼязок між %1$s і %2$s',
    'Class:URP_UserProfile/Attribute:userid' => 'Користувач',
    'Class:URP_UserProfile/Attribute:userid+' => 'обліковий запис користувача',
    'Class:URP_UserProfile/Attribute:userlogin' => 'Логін',
    'Class:URP_UserProfile/Attribute:userlogin+' => 'Логін користувача',
    'Class:URP_UserProfile/Attribute:profileid' => 'Профіль',
    'Class:URP_UserProfile/Attribute:profileid+' => 'використання профілю',
    'Class:URP_UserProfile/Attribute:profile' => 'Профіль',
    'Class:URP_UserProfile/Attribute:profile+' => 'Назва профілю',
    'Class:URP_UserProfile/Attribute:reason' => 'Причина',
    'Class:URP_UserProfile/Attribute:reason+' => 'Пояснення причини призначення цієї ролі',
));

//
// Class: URP_UserOrg
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
    'Class:URP_UserOrg' => 'Організації користувача',
    'Class:URP_UserOrg+' => 'Дозволені організації',
    'Class:URP_UserOrg/Name' => 'Звʼязок між %1$s і %2$s',
    'Class:URP_UserOrg/Attribute:userid' => 'Користувач',
    'Class:URP_UserOrg/Attribute:userid+' => 'Обліковий запис користувача',
    'Class:URP_UserOrg/Attribute:userlogin' => 'Логін',
    'Class:URP_UserOrg/Attribute:userlogin+' => 'Логін користувача',
    'Class:URP_UserOrg/Attribute:allowed_org_id' => 'Організація',
    'Class:URP_UserOrg/Attribute:allowed_org_id+' => 'Дозволена організація',
    'Class:URP_UserOrg/Attribute:allowed_org_name' => 'Організація',
    'Class:URP_UserOrg/Attribute:allowed_org_name+' => 'Дозволена організація',
    'Class:URP_UserOrg/Attribute:reason' => 'Причина',
    'Class:URP_UserOrg/Attribute:reason+' => 'Пояснення причини надання доступу до даних цієї організації',
));

//
// Class: URP_ProfileProjection
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
    'Class:URP_ProfileProjection' => 'Проєкція профілю',
    'Class:URP_ProfileProjection+' => 'Проєкція профілю',
    'Class:URP_ProfileProjection/Attribute:dimensionid' => 'Вимір',
    'Class:URP_ProfileProjection/Attribute:dimensionid+' => 'застосування виміру',
    'Class:URP_ProfileProjection/Attribute:dimension' => 'Вимір',
    'Class:URP_ProfileProjection/Attribute:dimension+' => 'застосування виміру',
    'Class:URP_ProfileProjection/Attribute:profileid' => 'Профіль',
    'Class:URP_ProfileProjection/Attribute:profileid+' => 'використання профілю',
    'Class:URP_ProfileProjection/Attribute:profile' => 'Профіль',
    'Class:URP_ProfileProjection/Attribute:profile+' => 'Назва профілю',
    'Class:URP_ProfileProjection/Attribute:value' => 'Значення виразу',
    'Class:URP_ProfileProjection/Attribute:value+' => 'OQL-вираз (з використанням $user) | константа |  | +атрибут коду',
    'Class:URP_ProfileProjection/Attribute:attribute' => 'Атрибут',
    'Class:URP_ProfileProjection/Attribute:attribute+' => 'Цільовий атрибут коду (необовʼязково)',
));

//
// Class: URP_ClassProjection
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
    'Class:URP_ClassProjection' => 'Проєкція класів',
    'Class:URP_ClassProjection+' => 'Проєкція класів',
    'Class:URP_ClassProjection/Attribute:dimensionid' => 'Вимір',
    'Class:URP_ClassProjection/Attribute:dimensionid+' => 'застосування виміру',
    'Class:URP_ClassProjection/Attribute:dimension' => 'Вимір',
    'Class:URP_ClassProjection/Attribute:dimension+' => 'застосування виміру',
    'Class:URP_ClassProjection/Attribute:class' => 'Клас',
    'Class:URP_ClassProjection/Attribute:class+' => 'Цільовий клас',
    'Class:URP_ClassProjection/Attribute:value' => 'Значення виразу',
    'Class:URP_ClassProjection/Attribute:value+' => 'OQL-вираз (з використанням $this) | константа |  | +атрибут коду',
    'Class:URP_ClassProjection/Attribute:attribute' => 'Атрибут',
    'Class:URP_ClassProjection/Attribute:attribute+' => 'Цільовий атрибут коду (необовʼязково)',
));

//
// Class: URP_ActionGrant
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
    'Class:URP_ActionGrant' => 'дія дозволів',
    'Class:URP_ActionGrant+' => 'дозволи на класи',
    'Class:URP_ActionGrant/Attribute:profileid' => 'Профіль',
    'Class:URP_ActionGrant/Attribute:profileid+' => 'використання профілю',
    'Class:URP_ActionGrant/Attribute:profile' => 'Профіль',
    'Class:URP_ActionGrant/Attribute:profile+' => 'використання профілю',
    'Class:URP_ActionGrant/Attribute:class' => 'Клас',
    'Class:URP_ActionGrant/Attribute:class+' => 'Цільовий клас',
    'Class:URP_ActionGrant/Attribute:permission' => 'Дозволи',
    'Class:URP_ActionGrant/Attribute:permission+' => 'дозволено чи ні?',
    'Class:URP_ActionGrant/Attribute:permission/Value:yes' => 'так',
    'Class:URP_ActionGrant/Attribute:permission/Value:yes+' => 'так',
    'Class:URP_ActionGrant/Attribute:permission/Value:no' => 'ні',
    'Class:URP_ActionGrant/Attribute:permission/Value:no+' => 'ні',
    'Class:URP_ActionGrant/Attribute:action' => 'Дія',
    'Class:URP_ActionGrant/Attribute:action+' => 'дія, що виконується для цього класу',
));

//
// Class: URP_StimulusGrant
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
    'Class:URP_StimulusGrant' => 'дозволи стимулів',
    'Class:URP_StimulusGrant+' => 'дозволи на стимули в життєвому циклі обʼєкта',
    'Class:URP_StimulusGrant/Attribute:profileid' => 'Профіль',
    'Class:URP_StimulusGrant/Attribute:profileid+' => 'використання профілю',
    'Class:URP_StimulusGrant/Attribute:profile' => 'Профіль',
    'Class:URP_StimulusGrant/Attribute:profile+' => 'використання профілю',
    'Class:URP_StimulusGrant/Attribute:class' => 'Клас',
    'Class:URP_StimulusGrant/Attribute:class+' => 'Цільовий клас',
    'Class:URP_StimulusGrant/Attribute:permission' => 'Дозволи',
    'Class:URP_StimulusGrant/Attribute:permission+' => 'дозволено чи ні?',
    'Class:URP_StimulusGrant/Attribute:permission/Value:yes' => 'так',
    'Class:URP_StimulusGrant/Attribute:permission/Value:yes+' => 'так',
    'Class:URP_StimulusGrant/Attribute:permission/Value:no' => 'ні',
    'Class:URP_StimulusGrant/Attribute:permission/Value:no+' => 'ні',
    'Class:URP_StimulusGrant/Attribute:stimulus' => 'Стимули',
    'Class:URP_StimulusGrant/Attribute:stimulus+' => 'код стимулів',
));

//
// Class: URP_AttributeGrant
//

Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
    'Class:URP_AttributeGrant' => 'дозволи атрибутів',
    'Class:URP_AttributeGrant+' => 'дозволи на рівні атрибутів',
    'Class:URP_AttributeGrant/Attribute:actiongrantid' => 'Дія надання',
    'Class:URP_AttributeGrant/Attribute:actiongrantid+' => 'дія надання',
    'Class:URP_AttributeGrant/Attribute:attcode' => 'Атрибут',
    'Class:URP_AttributeGrant/Attribute:attcode+' => 'Код атрибута',
));

//
// Class: UserDashboard
//
Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
    'Class:UserDashboard' => 'Дашборд користувача',
    'Class:UserDashboard+' => '~~',
    'Class:UserDashboard/Attribute:user_id' => 'Користувач',
    'Class:UserDashboard/Attribute:user_id+' => '',
    'Class:UserDashboard/Attribute:menu_code' => 'Код меню',
    'Class:UserDashboard/Attribute:menu_code+' => '',
    'Class:UserDashboard/Attribute:contents' => 'Вміст',
    'Class:UserDashboard/Attribute:contents+' => '',
));

//
// Duplicated into itop-welcome-itil (will be removed from here...)
//
Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
    'Menu:WelcomeMenu' => 'Ласкаво просимо',
    'Menu:WelcomeMenu+' => 'Ласкаво просимо в '.ITOP_APPLICATION_SHORT,
    'Menu:WelcomeMenuPage' => 'Ласкаво просимо',
    'Menu:WelcomeMenuPage+' => 'Ласкаво просимо в '.ITOP_APPLICATION_SHORT,
    'Menu:AdminTools' => 'Інструменти адміністратора',
    'Menu:AdminTools+' => 'Інструменти адміністратора',
    'Menu:AdminTools?' => 'Інструменти доступні тільки для користувачів із правами адміністратора',
    'Menu:CSVImportMenu' => 'Імпорт CSV',
    'Menu:CSVImportMenu+' => 'Пакетне створення або оновлення',
    'Menu:DataModelMenu' => 'Модель даних',
    'Menu:DataModelMenu+' => 'Огляд моделі даних',
    'Menu:ExportMenu' => 'Експорт',
    'Menu:ExportMenu+' => 'Експорт результатів будь-якого запиту у HTML, CSV або XML',
    'Menu:NotificationsMenu' => 'Сповіщення',
    'Menu:NotificationsMenu+' => 'Налаштування сповіщень',
    'Menu:MyShortcuts' => 'Обране',
    'Menu:Notifications:Title' => 'Категорії аудиту',
    'Menu:DataAdministration' => 'Адміністрування даних',
    'Menu:DataAdministration+' => 'Адміністрування даних'
));
//
// String from the User Interface: menu, messages, buttons, etc...
//
Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
    // Boolean labels
    'BooleanLabel:yes' => 'так',
    'BooleanLabel:no' => 'ні',

    // Login & Welcome
    'UI:Login:Title' => 'Вхід у '.ITOP_APPLICATION_SHORT,
    'UI:WelcomeMenu:Title' => 'Ласкаво просимо у '.ITOP_APPLICATION_SHORT,
    'UI:WelcomeMenu:AllOpenRequests' => 'Відкриті запити: %1$d',
    'UI:WelcomeMenu:MyCalls' => 'Мої запити',
    'UI:WelcomeMenu:OpenIncidents' => 'Відкриті інциденти: %1$d',
    'UI:WelcomeMenu:AllConfigItems' => 'Конфігураційні одиниці: %1$d',
    'UI:WelcomeMenu:MyIncidents' => 'Інциденти призначені мені',

    // User & Session
    'UI:AllOrganizations' => ' Всі організації ',
    'UI:YourSearch' => 'Пошук',
    'UI:LoggedAsMessage' => 'Ви увійшли як %1$s (%2$s)',
    'UI:LoggedAsMessage+Admin' => 'Ви увійшли як %1$s (%2$s, Адміністратор)',
    'UI:Button:Logoff' => 'Вийти',
    'UI:Button:Login' => 'Увійти',

    // Common Buttons
    'UI:Button:GlobalSearch' => 'Пошук',
    'UI:Button:Search' => 'Пошук',
    'UI:Button:Clear' => 'Очистити',
    'UI:Button:Confirm' => 'Підтвердити',
    'UI:Button:Ok' => 'ОК',
    'UI:Button:Save' => 'Зберегти',
    'UI:Button:Cancel' => 'Скасувати',
    'UI:Button:Close' => 'Закрити',
    'UI:Button:Apply' => 'Застосувати',
    'UI:Button:Send' => 'Відправити',
    'UI:Button:Back' => 'Назад',
    'UI:Button:Next' => 'Далі',
    'UI:Button:Finish' => 'Завершити',
    'UI:Button:Delete' => 'Видалити',
    'UI:Button:Create' => 'Створити',
    'UI:Button:Add' => 'Додати',
    'UI:Button:Refresh' => 'Оновити',
    'UI:Button:Export' => 'Експорт',
    'UI:Button:Test' => 'Тестувати',
    'UI:Button:Done' => 'Готово',
    'UI:Button:ResetPassword' => 'Скинути пароль',
    'UI:Button:ChangePassword' => 'Змінити пароль',
    'UI:Button:Insert' => 'Вставити',
    'UI:Button:More' => 'Більше',
    'UI:Button:Less' => 'Менше',
    'UI:Button:Remove' => 'Видалити',

    // General UI Labels
    'UI:NoObjectToDisplay' => 'Немає об’єктів для відображення.',
    'UI:Error:SaveFailed' => 'Не вдалося зберегти об’єкт:',
    'UI:Loading' => 'Завантаження...',
    'UI:HistoryTab' => 'Історія',
    'UI:NotificationsTab' => 'Сповіщення',
    'UI:Details+' => 'Деталі',

    // Search
    'UI:SearchToggle' => 'Пошук',
    'UI:SearchFor_Class' => 'Пошук: %1$s',
    'UI:SearchResultsTitle' => 'Результати пошуку',
    'UI:Search:NoObjectFound' => 'Об’єкти не знайдено.',
    'UI:Search:NoSearch' => 'Нічого не знайдено',

    // CSV Import/Export
    'UI:CSVImportMenu' => 'Імпорт CSV',
    'UI:CSVImportMenu+' => 'Пакетне створення або оновлення',
    'UI:ExportMenu' => 'Експорт',
    'UI:ExportMenu+' => 'Експорт результатів у HTML, CSV або XML',

    // Dashboard
    'UI:Toggle:CustomDashboard' => 'Користувацький',
    'UI:Toggle:StandardDashboard' => 'Стандартний',
    'UI:Menu:AddToDashboard' => 'Додати на дашборд...',

    // Days, Months
    'DayOfWeek-Sunday' => 'Неділя',
    'DayOfWeek-Monday' => 'Понеділок',
    'DayOfWeek-Tuesday' => 'Вівторок',
    'DayOfWeek-Wednesday' => 'Середа',
    'DayOfWeek-Thursday' => 'Четвер',
    'DayOfWeek-Friday' => 'П’ятниця',
    'DayOfWeek-Saturday' => 'Субота',
    'Month-01' => 'Січень',
    'Month-02' => 'Лютий',
    'Month-03' => 'Березень',
    'Month-04' => 'Квітень',
    'Month-05' => 'Травень',
    'Month-06' => 'Червень',
    'Month-07' => 'Липень',
    'Month-08' => 'Серпень',
    'Month-09' => 'Вересень',
    'Month-10' => 'Жовтень',
    'Month-11' => 'Листопад',
    'Month-12' => 'Грудень',

    // Misc
    'UI:Help' => 'Допомога',
    'UI:FavoriteLanguage' => 'Мова',
    'UI:AboutBox' => 'Про '.ITOP_APPLICATION_SHORT.'...',
    'UI:LogOffMenu' => 'Вийти',
    'UI:PropertiesTab' => 'Властивості',
    'UI:ChangePwdMenu' => 'Змінити пароль...',

    // Portal (self-service)
    'Portal:Title' => 'Портал користувача '.ITOP_APPLICATION_SHORT,
    'Portal:Back' => 'Назад',
    'Portal:Refresh' => 'Оновити',
    'Portal:CreateNewRequest' => 'Створити новий запит',
    'Portal:OpenRequests' => 'Мої відкриті запити',
    'Portal:ClosedRequests' => 'Мої закриті запити',
    'Portal:ResolvedRequests' => 'Мої вирішені запити',
    'Portal:EnterYourCommentsOnTicket' => 'Введіть коментарі до цього запиту:',
    'Portal:AddAttachment' => 'Додати вкладення',
    'Portal:RemoveAttachment' => 'Видалити вкладення',

    // General
    'UI:Menu:WelcomeMenu' => 'Ласкаво просимо',
    'UI:Menu:AdminTools' => 'Інструменти адміністратора',
    'UI:Menu:DataModelMenu' => 'Модель даних',
    'UI:Menu:NotificationsMenu' => 'Сповіщення',
    'UI:Menu:MyShortcuts' => 'Обране',
    'UI:Menu:DataAdministration' => 'Адміністрування даних'
));

//
// Expression to Natural language
//
Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
    'Expression:Operator:AND' => ' І ',
    'Expression:Operator:OR' => ' АБО ',
    'Expression:Operator:=' => ': ~~',
    'Expression:Unit:Short:DAY' => 'д',
    'Expression:Unit:Short:WEEK' => 'тиж',
    'Expression:Unit:Short:MONTH' => 'міс',
    'Expression:Unit:Short:YEAR' => 'р',
    'Expression:Unit:Long:DAY' => 'день(днів)',
    'Expression:Unit:Long:HOUR' => 'година(годин)',
    'Expression:Unit:Long:MINUTE' => 'хвилина(хвилин)',
    'Expression:Verb:NOW' => 'зараз',
    'Expression:Verb:ISNULL' => ': невизначено~~',
));

//
// iTop Newsroom menu
//
Dict::Add('UA UA', 'Ukrainian', 'Українська', array(
    'UI:Newsroom:NoNewMessage' => 'Немає нових повідомлень',
    'UI:Newsroom:XNewMessage' => 'Нові повідомлення (%1$s)',
    'UI:Newsroom:MarkAllAsRead' => 'Позначити все як прочитане',
    'UI:Newsroom:ViewAllMessages' => 'Переглянути всі повідомлення',
    'UI:Newsroom:Preferences' => 'Центр новин',
    'UI:Newsroom:ConfigurationLink' => 'Конфігурація',
    'UI:Newsroom:ResetCache' => 'Скинути кеш',
    'UI:Newsroom:ResetCache:Success:Message' => 'Кеш вашого центру новин успішно скинуто',
    'UI:Newsroom:DisplayMessagesFor_Provider' => 'Показати повідомлення від %1$s',
    'UI:Newsroom:DisplayAtMost_X_Messages' => 'Відображати не більше %1$s повідомлень у меню %2$s.',
    'UI:Newsroom:Priority:1:Tooltip' => 'Критично',
    'UI:Newsroom:Priority:2:Tooltip' => 'Терміново',
    'UI:Newsroom:Priority:3:Tooltip' => 'Важливо',
    'UI:Newsroom:Priority:4:Tooltip' => 'Звичайно',
));


Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Menu:DataSources' => 'Синхронизация данных',
	'Menu:DataSources+' => 'Синхронизация данных',
	'Menu:AuditCategories' => 'Категории аудита',
	'Menu:AuditCategories+' => 'Категории аудита',
	'Menu:AuditCategories:Title' => 'Audit configuration~~',
	'Menu:RunQueriesMenu' => 'Выполнение запросов',
	'Menu:RunQueriesMenu+' => 'Выполнение любых запросов',
	'Menu:QueryMenu' => 'Книга запросов',
	'Menu:QueryMenu+' => 'Книга запросов',
	'Menu:UniversalSearchMenu' => 'Универсальный поиск',
	'Menu:UniversalSearchMenu+' => 'Поиск чего угодно...',
	'Menu:UserManagementMenu' => 'Управление пользователями',
	'Menu:UserManagementMenu+' => 'Управление пользователями',
	'Menu:ProfilesMenu' => 'Профили',
	'Menu:ProfilesMenu+' => 'Профили пользователей',
	'Menu:ProfilesMenu:Title' => 'Профили пользователей',
	'Menu:UserAccountsMenu' => 'Учетные записи',
	'Menu:UserAccountsMenu+' => 'Учетные записи пользователей',
	'Menu:UserAccountsMenu:Title' => 'Учетные записи пользователей',
	'Menu:UserManagement' => 'Управление пользователями',
	'Menu:Queries' => 'Запросы OQL',
	'Menu:ConfigurationTools' => 'Конфигурация',
	'Menu:ConfigEditor' => 'General configuration~~',
	'Menu:ConfigEditor+' => 'Configuration File editor~~',
	'Menu:Integrations' => 'Integrations~~',
	'Menu:Integrations+' => '~~',

));
