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
// Classes in 'gui'
//////////////////////////////////////////////////////////////////////
//

//////////////////////////////////////////////////////////////////////
// Classes in 'application'
//////////////////////////////////////////////////////////////////////
//

//
// Class: AuditCategory
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:AuditCategory' => 'Категория аудита',
	'Class:AuditCategory+' => 'Раздел внутри общего аудита',
	'Class:AuditCategory/Attribute:name' => 'Название категории',
	'Class:AuditCategory/Attribute:name+' => 'Краткое название для этой категории',
	'Class:AuditCategory/Attribute:description' => 'Описание категории аудита',
	'Class:AuditCategory/Attribute:description+' => 'Полное описание категории аудита',
	'Class:AuditCategory/Attribute:definition_set' => 'Набор определений',
	'Class:AuditCategory/Attribute:definition_set+' => 'OQL выражение, определяющее набор объектов для проверки',
	'Class:AuditCategory/Attribute:rules_list' => 'Правила аудита',
	'Class:AuditCategory/Attribute:rules_list+' => 'Правила аудита для этой категории',
));

//
// Class: AuditRule
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:AuditRule' => 'Правило аудита',
	'Class:AuditRule+' => 'Правило для проверки данной категории аудита',
	'Class:AuditRule/Attribute:name' => 'Название правила',
	'Class:AuditRule/Attribute:name+' => 'Краткое название этого правила',
	'Class:AuditRule/Attribute:description' => 'Описание правила аудита',
	'Class:AuditRule/Attribute:description+' => 'Полное описание этого правила аудита',
	'Class:AuditRule/Attribute:query' => 'Запрос на исполнение',
	'Class:AuditRule/Attribute:query+' => 'OQL выражение на исполнение',
	'Class:AuditRule/Attribute:valid_flag' => 'Действительные объекты?',
	'Class:AuditRule/Attribute:valid_flag+' => 'Истина, если правило возвращает действительный объект, иначе ложь',
	'Class:AuditRule/Attribute:valid_flag/Value:true' => 'истина',
	'Class:AuditRule/Attribute:valid_flag/Value:true+' => 'истина',
	'Class:AuditRule/Attribute:valid_flag/Value:false' => 'ложь',
	'Class:AuditRule/Attribute:valid_flag/Value:false+' => 'ложь',
	'Class:AuditRule/Attribute:category_id' => 'Категория',
	'Class:AuditRule/Attribute:category_id+' => 'Категория этого правила',
	'Class:AuditRule/Attribute:category_name' => 'Категория',
	'Class:AuditRule/Attribute:category_name+' => 'Название категории для этого правила',
));

//////////////////////////////////////////////////////////////////////
// Classes in 'addon/userrights'
//////////////////////////////////////////////////////////////////////
//

//
// Class: User
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:User' => 'Пользователь',
	'Class:User+' => 'Пользовательский логин',
	'Class:User/Attribute:finalclass' => 'Тип счёта',
	'Class:User/Attribute:finalclass+' => '',
	'Class:User/Attribute:contactid' => 'Контакт (человек)',
	'Class:User/Attribute:contactid+' => 'Личные данные из бизнес-данных',
	'Class:User/Attribute:last_name' => 'Фамилия',
	'Class:User/Attribute:last_name+' => 'Фамилия соответсвующего контакта',
	'Class:User/Attribute:first_name' => 'Имя',
	'Class:User/Attribute:first_name+' => 'Имя соответсвующего контакта',
	'Class:User/Attribute:email' => 'e-mail',
	'Class:User/Attribute:email+' => 'e-mail соответсвующего контакта',
	'Class:User/Attribute:login' => 'Логин',
	'Class:User/Attribute:login+' => 'строка идентификации пользователя',
	'Class:User/Attribute:language' => 'Язык',
	'Class:User/Attribute:language+' => 'язык пользователя',
	'Class:User/Attribute:language/Value:RU RU' => 'Русский',
	'Class:User/Attribute:language/Value:RU RU+' => 'Русский (Россия)',
	'Class:User/Attribute:language/Value:EN US' => 'English',
	'Class:User/Attribute:language/Value:EN US+' => 'English (U.S.)',
	'Class:User/Attribute:language/Value:FR FR' => 'French',
	'Class:User/Attribute:language/Value:FR FR+' => 'French (France)',
	'Class:User/Attribute:profile_list' => 'Профили',
	'Class:User/Attribute:profile_list+' => 'Роли, предоставление прав этому человеку',
	'Class:User/Attribute:allowed_org_list' => 'Разрешённые организации',
	'Class:User/Attribute:allowed_org_list+' => 'Конечный пользователь имеет право видеть данные, принадлежащие к следующим организациям. Если ни одна организация не указан, нет никаких ограничений.',

	'Class:User/Error:LoginMustBeUnique' => 'Логин должен быть уникальным - "%1s" уже используется.',
	'Class:User/Error:AtLeastOneProfileIsNeeded' => 'По крайней мере, один профиль должен быть отнесен к этому пользователю.',
));

//
// Class: URP_Profiles
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:URP_Profiles' => 'Профиль',
	'Class:URP_Profiles+' => 'Пользовательский профиль',
	'Class:URP_Profiles/Attribute:name' => 'Название',
	'Class:URP_Profiles/Attribute:name+' => 'метка',
	'Class:URP_Profiles/Attribute:description' => 'Описание',
	'Class:URP_Profiles/Attribute:description+' => 'однострочное описание',
	'Class:URP_Profiles/Attribute:user_list' => 'Пользователи',
	'Class:URP_Profiles/Attribute:user_list+' => 'лица, имеющие эту роль',
));

//
// Class: URP_Dimensions
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:URP_Dimensions' => 'размерность',
	'Class:URP_Dimensions+' => 'применение размерности (определение силосов)',
	'Class:URP_Dimensions/Attribute:name' => 'Название',
	'Class:URP_Dimensions/Attribute:name+' => 'метка',
	'Class:URP_Dimensions/Attribute:description' => 'Описание',
	'Class:URP_Dimensions/Attribute:description+' => 'краткое описание',
	'Class:URP_Dimensions/Attribute:type' => 'Тип',
	'Class:URP_Dimensions/Attribute:type+' => 'имя класса или типа данных (проекционный блок)',
));

//
// Class: URP_UserProfile
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:URP_UserProfile' => 'Пользователь в профиль',
	'Class:URP_UserProfile+' => 'профили пользователей',
	'Class:URP_UserProfile/Attribute:userid' => 'Пользователь',
	'Class:URP_UserProfile/Attribute:userid+' => 'учетная запись пользователя',
	'Class:URP_UserProfile/Attribute:userlogin' => 'Логин',
	'Class:URP_UserProfile/Attribute:userlogin+' => 'Логин пользователя',
	'Class:URP_UserProfile/Attribute:profileid' => 'Профиль',
	'Class:URP_UserProfile/Attribute:profileid+' => 'использование профиля',
	'Class:URP_UserProfile/Attribute:profile' => 'Профиль',
	'Class:URP_UserProfile/Attribute:profile+' => 'Название профиля',
	'Class:URP_UserProfile/Attribute:reason' => 'Причина',
	'Class:URP_UserProfile/Attribute:reason+' => 'объяснение, почему этому человеку назначена эта роль',
));

//
// Class: URP_UserOrg
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:URP_UserOrg' => 'Организации пользователя',
	'Class:URP_UserOrg+' => 'Разрешённые организации',
	'Class:URP_UserOrg/Attribute:userid' => 'Пользователь',
	'Class:URP_UserOrg/Attribute:userid+' => 'учетная запись пользователя',
	'Class:URP_UserOrg/Attribute:userlogin' => 'Логин',
	'Class:URP_UserOrg/Attribute:userlogin+' => 'Логин пользователя',
	'Class:URP_UserOrg/Attribute:allowed_org_id' => 'Организация',
	'Class:URP_UserOrg/Attribute:allowed_org_id+' => 'Разрешённая организация',
	'Class:URP_UserOrg/Attribute:allowed_org_name' => 'Организация',
	'Class:URP_UserOrg/Attribute:allowed_org_name+' => 'Разрешённая организация',
	'Class:URP_UserOrg/Attribute:reason' => 'Причина',
	'Class:URP_UserOrg/Attribute:reason+' => 'объяснение, почему этот человек имеет право видеть данные, принадлежащие к этой организации',
));

//
// Class: URP_ProfileProjection
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:URP_ProfileProjection' => 'проэктирование профилей',
	'Class:URP_ProfileProjection+' => 'проэктирование профилей',
	'Class:URP_ProfileProjection/Attribute:dimensionid' => 'Размерность',
	'Class:URP_ProfileProjection/Attribute:dimensionid+' => 'применение размерности',
	'Class:URP_ProfileProjection/Attribute:dimension' => 'Размерность',
	'Class:URP_ProfileProjection/Attribute:dimension+' => 'применение размерности',
	'Class:URP_ProfileProjection/Attribute:profileid' => 'Профиль',
	'Class:URP_ProfileProjection/Attribute:profileid+' => 'использование профиля',
	'Class:URP_ProfileProjection/Attribute:profile' => 'Профиль',
	'Class:URP_ProfileProjection/Attribute:profile+' => 'Название профиля',
	'Class:URP_ProfileProjection/Attribute:value' => 'Значение выражения',
	'Class:URP_ProfileProjection/Attribute:value+' => 'OQL выражение (используя $user) | константа |  | +атрибут кода',
	'Class:URP_ProfileProjection/Attribute:attribute' => 'Атрибут',
	'Class:URP_ProfileProjection/Attribute:attribute+' => 'Целевой атрибут кода (необязательный)',
));

//
// Class: URP_ClassProjection
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:URP_ClassProjection' => 'прожктирование классов',
	'Class:URP_ClassProjection+' => 'прожктирование классов',
	'Class:URP_ClassProjection/Attribute:dimensionid' => 'Размерность',
	'Class:URP_ClassProjection/Attribute:dimensionid+' => 'применение размерности',
	'Class:URP_ClassProjection/Attribute:dimension' => 'Размерность',
	'Class:URP_ClassProjection/Attribute:dimension+' => 'применение размерности',
	'Class:URP_ClassProjection/Attribute:class' => 'Класс',
	'Class:URP_ClassProjection/Attribute:class+' => 'Целевой класс',
	'Class:URP_ClassProjection/Attribute:value' => 'Значение выражения',
	'Class:URP_ClassProjection/Attribute:value+' => 'OQL выражение (используя $this) | константа |  | +атрибут кода',
	'Class:URP_ClassProjection/Attribute:attribute' => 'Атрибут',
	'Class:URP_ClassProjection/Attribute:attribute+' => 'Целевой атрибут кода (необязательный)',
));

//
// Class: URP_ActionGrant
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:URP_ActionGrant' => 'действие разрешений',
	'Class:URP_ActionGrant+' => 'разрешения на классы',
	'Class:URP_ActionGrant/Attribute:profileid' => 'Профиль',
	'Class:URP_ActionGrant/Attribute:profileid+' => 'использование профиля',
	'Class:URP_ActionGrant/Attribute:profile' => 'Профиль',
	'Class:URP_ActionGrant/Attribute:profile+' => 'использование профиля',
	'Class:URP_ActionGrant/Attribute:class' => 'Класс',
	'Class:URP_ActionGrant/Attribute:class+' => 'Целевой класс',
	'Class:URP_ActionGrant/Attribute:permission' => 'Разрешения',
	'Class:URP_ActionGrant/Attribute:permission+' => 'разрешено или нет?',
	'Class:URP_ActionGrant/Attribute:permission/Value:yes' => 'да',
	'Class:URP_ActionGrant/Attribute:permission/Value:yes+' => 'да',
	'Class:URP_ActionGrant/Attribute:permission/Value:no' => 'нет',
	'Class:URP_ActionGrant/Attribute:permission/Value:no+' => 'нет',
	'Class:URP_ActionGrant/Attribute:action' => 'Действие',
	'Class:URP_ActionGrant/Attribute:action+' => 'действие выполняемое на данном классе',
));

//
// Class: URP_StimulusGrant
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:URP_StimulusGrant' => 'разрешения стимулов',
	'Class:URP_StimulusGrant+' => 'разрешения на стимулы в жизненном цикле объекта',
	'Class:URP_StimulusGrant/Attribute:profileid' => 'Профиль',
	'Class:URP_StimulusGrant/Attribute:profileid+' => 'использование профиля',
	'Class:URP_StimulusGrant/Attribute:profile' => 'Профиль',
	'Class:URP_StimulusGrant/Attribute:profile+' => 'использование профиля',
	'Class:URP_StimulusGrant/Attribute:class' => 'Класс',
	'Class:URP_StimulusGrant/Attribute:class+' => 'Целевой класс',
	'Class:URP_StimulusGrant/Attribute:permission' => 'Разрешения',
	'Class:URP_StimulusGrant/Attribute:permission+' => 'разрешено или нет?',
	'Class:URP_StimulusGrant/Attribute:permission/Value:yes' => 'да',
	'Class:URP_StimulusGrant/Attribute:permission/Value:yes+' => 'да',
	'Class:URP_StimulusGrant/Attribute:permission/Value:no' => 'нет',
	'Class:URP_StimulusGrant/Attribute:permission/Value:no+' => 'нет',
	'Class:URP_StimulusGrant/Attribute:stimulus' => 'Стимулы',
	'Class:URP_StimulusGrant/Attribute:stimulus+' => 'код стимулов',
));

//
// Class: URP_AttributeGrant
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:URP_AttributeGrant' => 'разрешения атрибутов',
	'Class:URP_AttributeGrant+' => 'разрешения на уровне атрибутов',
	'Class:URP_AttributeGrant/Attribute:actiongrantid' => 'Действие предоставления',
	'Class:URP_AttributeGrant/Attribute:actiongrantid+' => 'действие предоставления',
	'Class:URP_AttributeGrant/Attribute:attcode' => 'Атрибут',
	'Class:URP_AttributeGrant/Attribute:attcode+' => 'Код атрибута',
));

//
// String from the User Interface: menu, messages, buttons, etc...
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Menu:WelcomeMenu' => 'Добро пожаловать',
	'Menu:WelcomeMenu+' => 'Добро пожаловать в iTop',
	'Menu:WelcomeMenuPage' => 'Добро пожаловать',
	'Menu:WelcomeMenuPage+' => 'Добро пожаловать в iTop',
	'UI:WelcomeMenu:Title' => 'Добро пожаловать в iTop',

	'UI:WelcomeMenu:LeftBlock' => '<p>iTop is a complete, OpenSource, IT Operational Portal.</p>
<ul>Он включает:
<li>A complete CMDB (Configuration management database) to document and manage the IT inventory.</li>
<li>Модуль управления инцидентами для отслеживания и общения по вопросам IT.</li>
<li>Модуль управления изменениями для планирования и отслеживания изменений в IT.</li>
<li>База данных известных ошибок для ускорения устранения инцидентов.</li>
<li>Модуль простоев для документирования всех запланированных простоев и оповещения соответстсвующих контактов.</li>
<li>Панели для быстрого обзора IT.</li>
</ul>
<p>Все модули могут быть настроены, шаг за шагом, независмо друг от друга.</p>',

	'UI:WelcomeMenu:RightBlock' => '<p>iTop ориентирован на предоставления сервисов, он позволяет IT специалистам легко управляться с несколькими заказчиками или организациями.
<ul>iTop обеспечивает многофункциональный набор бизнес-процессов, которые:
<li>Повышает эффективность управления IT</li> 
<li>Повышает производительность IT-операция</li> 
<li>Улучшает удовлетворенность клиентов и обеспечивает понимание бизнес-процессов.</li>
</ul>
</p>
<p>iTop полностью открыт для интеграции в рамках текущего управления ИТ-инфраструктурой.</p>
<p>
<ul>Внедрение ИТ-портала нового поколения поможет вам:
<li>Лучше управлять более и более сложными ИТ-окружениями.</li>
<li>Реализовывать процессы ITIL в ваем собственном темпе.</li>
<li>Управлять наиболее важнім активом ИТ: документацией.</li>
</ul>
</p>',
	'UI:WelcomeMenu:AllOpenRequests' => 'Открытые запросы: %1$d',
	'UI:WelcomeMenu:MyCalls' => 'Мои запросы',
	'UI:WelcomeMenu:OpenIncidents' => 'Открытые инциденты: %1$d',
	'UI:WelcomeMenu:AllConfigItems' => 'Кофигурационные единицы: %1$d',
	'UI:WelcomeMenu:MyIncidents' => 'Инциденты назначенные на меня',
	'UI:AllOrganizations' => ' Все организации ',
	'UI:YourSearch' => 'Ваш поиск',
	'UI:LoggedAsMessage' => 'Вы вошли как %1$s',
	'UI:LoggedAsMessage+Admin' => 'Вы вошли как %1$s (Администратор)',
	'UI:Button:Logoff' => 'Выход',
	'UI:Button:GlobalSearch' => 'Поиск',
	'UI:Button:Search' => ' Поиск ',
	'UI:Button:Query' => ' Запрос ',
	'UI:Button:Ok' => 'Ok',
	'UI:Button:Cancel' => 'Отмена',
	'UI:Button:Apply' => 'Применить',
	'UI:Button:Back' => ' << Назад ',
	'UI:Button:Next' => ' Вперёд >> ',
	'UI:Button:Finish' => ' Конец ',
	'UI:Button:DoImport' => ' Выполнить импорт ! ',
	'UI:Button:Done' => ' Сделать ',
	'UI:Button:SimulateImport' => ' Эмулировать импорт ',
	'UI:Button:Test' => 'Тестировать!',
	'UI:Button:Evaluate' => ' Оценка ',
	'UI:Button:AddObject' => ' Добавить... ',
	'UI:Button:BrowseObjects' => ' Обзор... ',
	'UI:Button:Add' => ' Добавить ',
	'UI:Button:AddToList' => ' << Добавить ',
	'UI:Button:RemoveFromList' => ' Удалить >> ',
	'UI:Button:FilterList' => ' Фильтр... ',
	'UI:Button:Create' => ' Создать ',
	'UI:Button:Delete' => ' Удалить ! ',
	'UI:Button:ChangePassword' => ' Сменить пароль ',
	'UI:Button:ResetPassword' => ' Сбросить пароль ',
	
	'UI:SearchToggle' => 'Поиск',
	'UI:ClickToCreateNew' => 'Создать новый %1$s',
	'UI:SearchFor_Class' => 'Поиск для %1$s объектов',
	'UI:NoObjectToDisplay' => 'Нет объектов для отображения.',
	'UI:Error:MandatoryTemplateParameter_object_id' => 'Параметр object_id является обязательным если указан link_attr. Проверьте определение отображения шаблона.',
	'UI:Error:MandatoryTemplateParameter_target_attr' => 'Параметр object_id является обязательным если указан link_attr. Проверьте определение отображения шаблона',
	'UI:Error:MandatoryTemplateParameter_group_by' => 'Параметр group_by является обязательным. Проверьте определение отображения шаблона.',
	'UI:Error:InvalidGroupByFields' => 'Неверный список полей для группировки: "%1$s".',
	'UI:Error:UnsupportedStyleOfBlock' => 'Ошибка: неподдерживаемый стиль блока: "%1$s".',
	'UI:Error:IncorrectLinkDefinition_LinkedClass_Class' => 'Неправильное определение ссылки: класс объектов для управления: %1$s не был найден в качестве внешнего ключа в классе %2$s',
	'UI:Error:Object_Class_Id_NotFound' => 'Объект: %1$s:%2$d не найден.',
	'UI:Error:WizardCircularReferenceInDependencies' => 'Ошибка: Циклическая ссылка в зависимостях между полями, проверить модель данных.',
	'UI:Error:UploadedFileTooBig' => 'Загружаемый файл слишком большой. (Максимально разрешённый размер %1$s). Проверьте в конфинурации PHP параметры upload_max_filesize и post_max_size.',
	'UI:Error:UploadedFileTruncated.' => 'Загруженный файл был усечен !',
	'UI:Error:NoTmpDir' => 'Временный каталог не определен.',
	'UI:Error:CannotWriteToTmp_Dir' => ' Невозможно записать временный файл на диск. upload_tmp_dir = "%1$s".',
	'UI:Error:UploadStoppedByExtension_FileName' => 'Загрузка остановлена по расширению. (Имя файла = "%1$s").',
	'UI:Error:UploadFailedUnknownCause_Code' => 'Загрузка файла не удалась по неизвестной причине. (Код ошибки = "%1$s").',
	
	'UI:Error:1ParametersMissing' => 'Ошибка: следующий параметр должен быть указан для этой операции: %1$s.',
	'UI:Error:2ParametersMissing' => 'Ошибка: следующие параметры должен быть указан для этой операции: %1$s и %2$s.',
	'UI:Error:3ParametersMissing' => 'Ошибка: следующие параметры должен быть указан для этой операции: %1$s, %2$s и %3$s.',
	'UI:Error:4ParametersMissing' => 'Ошибка: следующие параметры должен быть указан для этой операции: %1$s, %2$s, %3$s и %4$s.',
	'UI:Error:IncorrectOQLQuery_Message' => 'Ошибка: неправильній запрос OQL: %1$s',
	'UI:Error:AnErrorOccuredWhileRunningTheQuery_Message' => 'Ошибка при выполнении запроса: %1$s',
	'UI:Error:ObjectAlreadyUpdated' => 'Ошибка: объект уже обновлён.',
	'UI:Error:ObjectCannotBeUpdated' => 'Ошибка: объект не может быть обновлён.',
	'UI:Error:ObjectsAlreadyDeleted' => 'Ошибка: объект уже удалён!',
	'UI:Error:BulkDeleteNotAllowedOn_Class' => 'Вам не разрешено выполнять массовое удаления объектов класса %1$s',
	'UI:Error:DeleteNotAllowedOn_Class' => 'Вы не можете удалять объекты класса %1$s',
	'UI:Error:BulkModifyNotAllowedOn_Class' => 'Вам не разрешено выполнять массовое обновление объектов класса %1$s',
	'UI:Error:ObjectAlreadyCloned' => 'Ошибка: объект уже клонирован!',
	'UI:Error:ObjectAlreadyCreated' => 'Ошибка: объект уже создан!',
	'UI:Error:Invalid_Stimulus_On_Object_In_State' => 'Ошибка: недействительный стимул "%1$s" на объекте %2$s в состоянии "%3$s".',
	
	
	'UI:GroupBy:Count' => 'Счётчик',
	'UI:GroupBy:Count+' => 'Количество элементов',
	'UI:CountOfObjects' => '%1$d объектов соответствует критериям.',
	'UI_CountOfObjectsShort' => '%1$d объектов.',
	'UI:NoObject_Class_ToDisplay' => 'Нечего отображать %1$s',
	'UI:History:LastModified_On_By' => 'Последнее изменение %1$s by %2$s.',
	'UI:HistoryTab' => 'История',
	'UI:NotificationsTab' => 'Оповещения',
	'UI:History:Date' => 'Дата',
	'UI:History:Date+' => 'Дата изменения',
	'UI:History:User' => 'Пользователь',
	'UI:History:User+' => 'Пользователь сделавший изменение',
	'UI:History:Changes' => 'Изменения',
	'UI:History:Changes+' => 'Изменения, внесенные в объект',
	'UI:Loading' => 'Загрузка...',
	'UI:Menu:Actions' => 'Действия',
	'UI:Menu:OtherActions' => 'Другие Действия',
	'UI:Menu:New' => 'Новый...',
	'UI:Menu:Add' => 'Добавить...',
	'UI:Menu:Manage' => 'Управление...',
	'UI:Menu:EMail' => 'eMail',
	'UI:Menu:CSVExport' => 'Экспорт CSV',
	'UI:Menu:Modify' => 'Изменить...',
	'UI:Menu:Delete' => 'Удалить...',
	'UI:Menu:Manage' => 'Управление...',
	'UI:Menu:BulkDelete' => 'Удалить...',
	'UI:UndefinedObject' => 'неопределённый',
	'UI:Document:OpenInNewWindow:Download' => 'Открыть в новом окне: %1$s, Загрузка: %2$s',
	'UI:SelectAllToggle+' => 'Выбрать / Отменить всё',
	'UI:TruncatedResults' => '%1$d объектов отображено из %2$d',
	'UI:DisplayAll' => 'Показать всё',
	'UI:CollapseList' => 'Свернуть',
	'UI:CountOfResults' => '%1$d объект(ы)',
	'UI:ChangesLogTitle' => 'Журнал изменений (%1$d):',
	'UI:EmptyChangesLogTitle' => 'Журнал изменений пустой',
	'UI:SearchFor_Class_Objects' => 'Поиск объекта %1$s',
	'UI:OQLQueryBuilderTitle' => 'Коструктор запросов OQL',
	'UI:OQLQueryTab' => 'Запрос OQL',
	'UI:SimpleSearchTab' => 'Простой поиск',
	'UI:Details+' => 'Подробности',
	'UI:SearchValue:Any' => '* Любой *',
	'UI:SearchValue:Mixed' => '* смешанный *',
	'UI:SelectOne' => '-- выбрать один --',
	'UI:Login:Welcome' => 'Добро пожаловать в iTop!',
	'UI:Login:IncorrectLoginPassword' => 'Неправильный логин/пароль. Пожалуйста, попробуйте еще раз.',
	'UI:Login:IdentifyYourself' => 'Представтесть, прежде чем продолжить',
	'UI:Login:UserNamePrompt' => 'Имя пользователя',
	'UI:Login:PasswordPrompt' => 'Пароль',
	'UI:Login:ChangeYourPassword' => 'Изменение пароля',
	'UI:Login:OldPasswordPrompt' => 'Старый пароль',
	'UI:Login:NewPasswordPrompt' => 'Новый пароль',
	'UI:Login:RetypeNewPasswordPrompt' => 'Повтор нового пароля',
	'UI:Login:IncorrectOldPassword' => 'Ошибка: старый пароль неверный',
	'UI:LogOffMenu' => 'Выход',
	'UI:LogOff:ThankYou' => 'Спасибо за использование iTop',
	'UI:LogOff:ClickHereToLoginAgain' => 'Нажмите здесь, чтобы снова войти...',
	'UI:ChangePwdMenu' => 'Изменить пароль...',
	'UI:Login:RetypePwdDoesNotMatch' => 'Новый пароль и повторный пароль не совпадают!',
	'UI:Button:Login' => 'Введите iTop',
	'UI:Login:Error:AccessRestricted' => 'Доступ к iTop ограничен. Пожалуйста, свяжитесь с администратором iTop.',
	'UI:Login:Error:AccessAdmin' => 'Доступ ограничен для лиц с административными привилегиями. Пожалуйста, свяжитесь с администратором iTop.',
	'UI:CSVImport:MappingSelectOne' => '-- выбрать один --',
	'UI:CSVImport:MappingNotApplicable' => '-- игнорировать это поле --',
	'UI:CSVImport:NoData' => 'Пустой набор данных..., пожалуйста введите что-нибудь!',
	'UI:Title:DataPreview' => 'Предпросмотр данных',
	'UI:CSVImport:ErrorOnlyOneColumn' => 'Ошибка: Данные содежат только одну колонку. Выбран правильный разделитель?',
	'UI:CSVImport:FieldName' => 'Поле %1$d',
	'UI:CSVImport:DataLine1' => 'Строка данных 1',
	'UI:CSVImport:DataLine2' => 'Строка данных 2',
	'UI:CSVImport:idField' => 'id (Первичный ключ)',
	'UI:Title:BulkImport' => 'iTop - Пакетный импорт',
	'UI:Title:BulkImport+' => 'Мастер импорта CSV',
	'UI:CSVImport:ClassesSelectOne' => '-- выбрать один --',
	'UI:CSVImport:ErrorExtendedAttCode' => 'Внутренняя ошибка: "%1$s" некорректный код потому, что "%2$s" НЕ являеться внешним ключём класса "%3$s"',
	'UI:CSVImport:ObjectsWillStayUnchanged' => '%1$d объект(ы) останеться неизменным.',
	'UI:CSVImport:ObjectsWillBeModified' => '%1$d объект(ы) будет изменён.',
	'UI:CSVImport:ObjectsWillBeAdded' => '%1$d объект(ы) будет добавлен.',
	'UI:CSVImport:ObjectsWillHaveErrors' => '%1$d объект(ы) будут ошибочны.',
	'UI:CSVImport:ObjectsRemainedUnchanged' => '%1$d объект(ы) остался неизменённым.',
	'UI:CSVImport:ObjectsWereModified' => '%1$d объект(ы) изменён.',
	'UI:CSVImport:ObjectsWereAdded' => '%1$d объект(ы) был добавлен.',
	'UI:CSVImport:ObjectsHadErrors' => '%1$d объект(ы) содержит ошибки.',
	'UI:Title:CSVImportStep2' => 'Step 2 of 5: Опции данных CSV',
	'UI:Title:CSVImportStep3' => 'Step 3 of 5: Распределение данных',
	'UI:Title:CSVImportStep4' => 'Step 4 of 5: Симуляция импорта',
	'UI:Title:CSVImportStep5' => 'Step 5 of 5: Импорт завершён',
	'UI:CSVImport:LinesNotImported' => 'Строки небыли загружены:',
	'UI:CSVImport:LinesNotImported+' => 'Следующие строки не были импортированы, потому что они содержат ошибки',
	'UI:CSVImport:SeparatorComma+' => ', (запятая)',
	'UI:CSVImport:SeparatorSemicolon+' => '; (точка с запятой)',
	'UI:CSVImport:SeparatorTab+' => 'табулятор',
	'UI:CSVImport:SeparatorOther' => 'другое:',
	'UI:CSVImport:QualifierDoubleQuote+' => '" (двойная кавычка)',
	'UI:CSVImport:QualifierSimpleQuote+' => '\' (одинарная кавычка)',
	'UI:CSVImport:QualifierOther' => 'другое:',
	'UI:CSVImport:TreatFirstLineAsHeader' => 'Использовать первую строку как заголовок (названия столбцов)',
	'UI:CSVImport:Skip_N_LinesAtTheBeginning' => 'Пропустить %1$s строк(у) от начала файла',
	'UI:CSVImport:CSVDataPreview' => 'Предпросмотр данных CSV',
	'UI:CSVImport:SelectFile' => 'Выбор файла для иморта:',
	'UI:CSVImport:Tab:LoadFromFile' => 'Загрузить из файла',
	'UI:CSVImport:Tab:CopyPaste' => 'Копировать и вставить данные',
	'UI:CSVImport:Tab:Templates' => 'Шаблоны',
	'UI:CSVImport:PasteData' => 'Вставить данные для импорта:',
	'UI:CSVImport:PickClassForTemplate' => 'Выбор шаблона для загрузки: ',
	'UI:CSVImport:SeparatorCharacter' => 'Символ разделителя:',
	'UI:CSVImport:TextQualifierCharacter' => 'Символ экранирования текста',
	'UI:CSVImport:CommentsAndHeader' => 'Коментарии и заголовок',
	'UI:CSVImport:SelectClass' => 'Выбор класса импорта:',
	'UI:CSVImport:AdvancedMode' => 'Расширенный режим',
	'UI:CSVImport:AdvancedMode+' => 'В расширенном режиме "id" (первичный ключ) объекта может быть использован для обновления и переименования объектов.' .
									'Однако колонка "id" (if present) может быть использовать только как критерий поиска и не модет быть совмещена с любым другим критерием поиска.',
	'UI:CSVImport:SelectAClassFirst' => 'Для настройки рапределения, в первую очередь выберите класс.',
	'UI:CSVImport:HeaderFields' => 'Поля',
	'UI:CSVImport:HeaderMappings' => 'Распределение',
	'UI:CSVImport:HeaderSearch' => 'Поиск?',
	'UI:CSVImport:AlertIncompleteMapping' => 'Необходимо выбрать распределение для каждой ячейки.',
	'UI:CSVImport:AlertNoSearchCriteria' => 'Необходимо выбрать, по крайней мере один критерий',
	'UI:CSVImport:Encoding' => 'Кодировка символов',	
	'UI:UniversalSearchTitle' => 'iTop - Универсальный поиск',
	'UI:UniversalSearch:Error' => 'Ошибка: %1$s',
	'UI:UniversalSearch:LabelSelectTheClass' => 'Выбор класса для поиска: ',
	
	'UI:Audit:Title' => 'iTop - Аудит CMDB',
	'UI:Audit:InteractiveAudit' => 'Интерактивный аудит',
	'UI:Audit:HeaderAuditRule' => 'Правило аудита',
	'UI:Audit:HeaderNbObjects' => '# Объекты',
	'UI:Audit:HeaderNbErrors' => '# Ошибки',
	'UI:Audit:PercentageOk' => '% Ok',
	
	'UI:RunQuery:Title' => 'iTop - Оценка запросов OQL',
	'UI:RunQuery:QueryExamples' => 'Примеры запросов',
	'UI:RunQuery:HeaderPurpose' => 'Цель',
	'UI:RunQuery:HeaderPurpose+' => 'Объяснение запросов',
	'UI:RunQuery:HeaderOQLExpression' => 'Выражение OQL',
	'UI:RunQuery:HeaderOQLExpression+' => 'Запрос в синтаксисе OQL',
	'UI:RunQuery:ExpressionToEvaluate' => 'Оценка віражения: ',
	'UI:RunQuery:MoreInfo' => 'Подробная информация о запросе: ',
	'UI:RunQuery:DevelopedQuery' => 'Переработанное выражение запроса: ',
	'UI:RunQuery:SerializedFilter' => 'Сериализованные фильты: ',
	'UI:RunQuery:Error' => 'Ошибка при выполнении запроса: %1$s',
	
	'UI:Schema:Title' => 'iTop схема объектов',
	'UI:Schema:CategoryMenuItem' => 'Категория <b>%1$s</b>',
	'UI:Schema:Relationships' => 'Отношения',
	'UI:Schema:AbstractClass' => 'Абстрактный класс: ни один объект из этого класса может быть создан.',
	'UI:Schema:NonAbstractClass' => 'Не абстрактный класс: объекты этого класса могут быть созданы.',
	'UI:Schema:ClassHierarchyTitle' => 'Иерархия классов',
	'UI:Schema:AllClasses' => 'Все классы',
	'UI:Schema:ExternalKey_To' => 'Внешний ключ %1$s',
	'UI:Schema:Columns_Description' => 'Столбцы: <em>%1$s</em>',
	'UI:Schema:Default_Description' => 'По умолчанию: "%1$s"',
	'UI:Schema:NullAllowed' => 'Null разрешён',
	'UI:Schema:NullNotAllowed' => 'Null НЕ разрешён',
	'UI:Schema:Attributes' => 'Атрибуты',
	'UI:Schema:AttributeCode' => 'Код атрибута',
	'UI:Schema:AttributeCode+' => 'Внутренний код атрибута',
	'UI:Schema:Label' => 'Метка',
	'UI:Schema:Label+' => 'Метка атрибута',
	'UI:Schema:Type' => 'Тип',
	
	'UI:Schema:Type+' => 'Тип данных атрибута',
	'UI:Schema:Origin' => 'Происхождение',
	'UI:Schema:Origin+' => 'Базовый класс, в котором этот атрибут определен',
	'UI:Schema:Description' => 'Описание',
	'UI:Schema:Description+' => 'Описание атрибута',
	'UI:Schema:AllowedValues' => 'Допустимые значения',
	'UI:Schema:AllowedValues+' => 'Ограничения на возможные значения для этого атрибута',
	'UI:Schema:MoreInfo' => 'Подробнее',
	'UI:Schema:MoreInfo+' => 'Более подробная информация о поле, определённом в базе данных',
	'UI:Schema:SearchCriteria' => 'Критерий поиска',
	'UI:Schema:FilterCode' => 'Код фильтра',
	'UI:Schema:FilterCode+' => 'Код критерия поиска',
	'UI:Schema:FilterDescription' => 'Описание',
	'UI:Schema:FilterDescription+' => 'Описание еритерия поиска',
	'UI:Schema:AvailOperators' => 'Доступные операторы',
	'UI:Schema:AvailOperators+' => 'Возможные операторы для этого критерия поиска',
	'UI:Schema:ChildClasses' => 'Дочерние классы',
	'UI:Schema:ReferencingClasses' => 'Привязки классов',
	'UI:Schema:RelatedClasses' => 'Зависимые классы',
	'UI:Schema:LifeCycle' => 'Жизненный цикл',
	'UI:Schema:Triggers' => 'Триггеры',
	'UI:Schema:Relation_Code_Description' => 'Зависимость <em>%1$s</em> (%2$s)',
	'UI:Schema:RelationDown_Description' => 'Вниз: %1$s',
	'UI:Schema:RelationUp_Description' => 'Вверх: %1$s',
	'UI:Schema:RelationPropagates' => '%1$s: распространяется на %2$d уровней, запрос: %3$s',
	'UI:Schema:RelationDoesNotPropagate' => '%1$s: не распространяется (%2$d уровней), запрос: %3$s',
	'UI:Schema:Class_ReferencingClasses_From_By' => '%1$s связан с классом %2$s через поле %3$s',
	'UI:Schema:Class_IsLinkedTo_Class_Via_ClassAndAttribute' => '%1$s связан с %2$s через %3$s::<em>%4$s</em>',
	'UI:Schema:Links:1-n' => 'Классы, указывающие на %1$s (1:n ссылки):',
	'UI:Schema:Links:n-n' => 'Классы связаны с %1$s (n:n сслыки):',
	'UI:Schema:Links:All' => 'График всех связанных классов',
	'UI:Schema:NoLifeCyle' => 'Не определён жизненный цикл для этих классов.',
	'UI:Schema:LifeCycleTransitions' => 'Переходы',
	'UI:Schema:LifeCyleAttributeOptions' => 'Варианты атрибутов',
	'UI:Schema:LifeCycleHiddenAttribute' => 'Скрытый',
	'UI:Schema:LifeCycleReadOnlyAttribute' => 'Только для чтения',
	'UI:Schema:LifeCycleMandatoryAttribute' => 'Обязательный',
	'UI:Schema:LifeCycleAttributeMustChange' => 'Необходимо изменить',
	'UI:Schema:LifeCycleAttributeMustPrompt' => 'Пользователю будет предложено изменить значение',
	'UI:Schema:LifeCycleEmptyList' => 'пустой список',
	
	'UI:LinksWidget:Autocomplete+' => 'Введите первые 3 символа...',
	'UI:Combo:SelectValue' => '--- выбор значения ---',
	'UI:Label:SelectedObjects' => 'Выбранные объекты: ',
	'UI:Label:AvailableObjects' => 'Доступные объекты: ',
	'UI:Link_Class_Attributes' => '%1$s атрибуты',
	'UI:SelectAllToggle+' => 'Выбрать всё / Отменить всё',
	'UI:AddObjectsOf_Class_LinkedWith_Class_Instance' => 'Добавить %1$s объекты связанные с %2$s: %3$s',
	'UI:AddObjectsOf_Class_LinkedWith_Class' => 'Добавть %1$s объекты для связи с %2$s',
	'UI:ManageObjectsOf_Class_LinkedWith_Class_Instance' => 'Управление %1$s объектами связанными с %2$s: %3$s',
	'UI:AddLinkedObjectsOf_Class' => 'Добавить %1$ss...',
	'UI:RemoveLinkedObjectsOf_Class' => 'Удалить выбранные объекты',
	'UI:Message:EmptyList:UseAdd' => 'Список пуст, используй кнопку "Добавить ...", для добавения элементов.',
	'UI:Message:EmptyList:UseSearchForm' => 'Используйте форму поиска выше для поиска объектов, которые будут добавлены.',
	
	'UI:Wizard:FinalStepTitle' => 'Последний шаг: подтверждение',
	'UI:Title:DeletionOf_Object' => 'Удаление %1$s',
	'UI:Title:BulkDeletionOf_Count_ObjectsOf_Class' => 'Пакетное удаление %1$d объектов класса %2$s',
	'UI:Delete:NotAllowedToDelete' => 'Вы не можете удалить этот объект',
	'UI:Delete:NotAllowedToUpdate_Fields' => 'Вы не можете обновить следующее(ие) поле(я): %1$s',
	'UI:Error:CannotDeleteBecause' => 'This object could not be deleted because: %1$s',
	'UI:Error:NotEnoughRightsToDelete' => 'Этот объект не может быть удален, потому что текущий пользователь не имеет достаточных прав',
	'UI:Error:CannotDeleteBecauseOfDepencies' => 'Этот объект не может быть удален, потому что некоторые ручные операции должны быть выполнены до этого',
	'UI:Archive_User_OnBehalfOf_User' => '%1$s от имени %2$s',
	'UI:Delete:AutomaticallyDeleted' => 'автоматически удалён',
	'UI:Delete:AutomaticResetOf_Fields' => 'автоматически сброшено поле(я): %1$s',
	'UI:Delete:CleaningUpRefencesTo_Object' => 'Очищенны все ссылки(связи?) на %1$s...',
	'UI:Delete:CleaningUpRefencesTo_Several_ObjectsOf_Class' => 'Очищенны все ссылки(связи?) на %1$d объектов класса %2$s...',
	'UI:Delete:Done+' => 'Что было сделано...',
	'UI:Delete:_Name_Class_Deleted' => '%1$s - %2$s удалено.',
	'UI:Delete:ConfirmDeletionOf_Name' => 'Удаление %1$s',
	'UI:Delete:ConfirmDeletionOf_Count_ObjectsOf_Class' => 'Удаление %1$d объектов класса %2$s',
//	'UI:Delete:ShouldBeDeletedAtomaticallyButNotPossible' => 'Должно быть автоматичски удалено, но вы не можете это сделать',
//	'UI:Delete:MustBeDeletedManuallyButNotPossible' => 'Необходимо удалить вручную - но вы не можете удалить этот объект, свяжитесь с администратором вашего приложения',
	'UI:Delete:WillBeDeletedAutomatically' => 'Будет удалено автоматически',
	'UI:Delete:MustBeDeletedManually' => 'Необходимо удалить вручную',
	'UI:Delete:CannotUpdateBecause_Issue' => 'Должно быть автоматически обновлено, но: %1$s',
	'UI:Delete:WillAutomaticallyUpdate_Fields' => 'будет автоматически обновлено (сброс: %1$s)',
	'UI:Delete:Count_Objects/LinksReferencing_Object' => '%1$d объектов/связей ссылаются(связаны?) %2$s',
	'UI:Delete:Count_Objects/LinksReferencingTheObjects' => '%1$d объектов/связей ссылаются на объекты, которые будут удалены',	
	'UI:Delete:ReferencesMustBeDeletedToEnsureIntegrity' => 'Для обеспечения целостности базы данных, необходимо устранить все связи',
	'UI:Delete:Consequence+' => 'Что будет сделано',
	'UI:Delete:SorryDeletionNotAllowed' => 'К сожалению, вы не можете удалить этот объект, см. подробное объяснение выше',
	'UI:Delete:PleaseDoTheManualOperations' => 'Необходимо выполнить указанные ручные операции до запроса на удаление этого объекта',
	'UI:Delect:Confirm_Object' => 'Подтвердите удаление %1$s.',
	'UI:Delect:Confirm_Count_ObjectsOf_Class' => 'Подтвердите удаление %1$d объектов класса %2$s.',
	'UI:WelcomeToITop' => 'Добро пожаловать в iTop',
	'UI:DetailsPageTitle' => 'iTop - %1$s - %2$s подробности',
	'UI:ErrorPageTitle' => 'iTop - Ошибка',
	'UI:ObjectDoesNotExist' => 'Извните, этот объект не существует (или вы не можете его видеть).',
	'UI:SearchResultsPageTitle' => 'iTop - Результаты поиска',
	'UI:Search:NoSearch' => 'Ничего не найдено',
	'UI:FullTextSearchTitle_Text' => 'Результаты для "%1$s":',
	'UI:Search:Count_ObjectsOf_Class_Found' => '%1$d объект(ы) класса %2$s найдено.',
	'UI:Search:NoObjectFound' => 'Объекты не найдены.',
	'UI:ModificationPageTitle_Object_Class' => 'iTop - %1$s - %2$s модификации',
	'UI:ModificationTitle_Class_Object' => 'Модификации %1$s: <span class=\"hilite\">%2$s</span>',
	'UI:ClonePageTitle_Object_Class' => 'iTop - Клон %1$s - %2$s модификация',
	'UI:CloneTitle_Class_Object' => 'Клон %1$s: <span class=\"hilite\">%2$s</span>',
	'UI:CreationPageTitle_Class' => 'iTop - Создание нового %1$s ',
	'UI:CreationTitle_Class' => 'Создание нового %1$s',
	'UI:SelectTheTypeOf_Class_ToCreate' => 'Выбор типа %1$s для создания:',
	'UI:Class_Object_NotUpdated' => 'Изменений не обнаружено, %1$s (%2$s) <strong>не</strong> был изменён.',
	'UI:Class_Object_Updated' => '%1$s (%2$s) обновлён.',
	'UI:BulkDeletePageTitle' => 'iTop - Пакетное удаление',
	'UI:BulkDeleteTitle' => 'Выбор объектов для удаления:',
	'UI:PageTitle:ObjectCreated' => 'iTop Объект создан.',
	'UI:Title:Object_Of_Class_Created' => '%1$s - %2$s создан.',
	'UI:Apply_Stimulus_On_Object_In_State_ToTarget_State' => 'Применение %1$s на объект: %2$s в состоянии %3$s для целевого класса: %4$s.',
	'UI:ObjectCouldNotBeWritten' => 'Объект не может быть записан: %1$s',
	'UI:PageTitle:FatalError' => 'iTop - Фатальная ошибка',
	'UI:SystemIntrusion' => 'Доступ запрещён. Вы пытаетесь выполнить неразрешённую операцию.',
	'UI:FatalErrorMessage' => 'Фатальная ошибка, iTop не может продолжать.',
	'UI:Error_Details' => 'Ошибка: %1$s.',

	'UI:PageTitle:ClassProjections'	=> 'iTop управление пользователями - проектирование классов',
	'UI:PageTitle:ProfileProjections' => 'iTop управление пользователями - проектирование профилей',
	'UI:UserManagement:Class' => 'Классs',
	'UI:UserManagement:Class+' => 'Класс объектов',
	'UI:UserManagement:ProjectedObject' => 'Объект',
	'UI:UserManagement:ProjectedObject+' => 'Проектируемый объект',
	'UI:UserManagement:AnyObject' => '* любой *',
	'UI:UserManagement:User' => 'Пользователь',
	'UI:UserManagement:User+' => 'Пользователь учавствует',
	'UI:UserManagement:Profile' => 'Профиль',
	'UI:UserManagement:Profile+' => 'Профиль, указанный в проектировании',
	'UI:UserManagement:Action:Read' => 'Чтение',
	'UI:UserManagement:Action:Read+' => 'Чтение/отображение объектов',
	'UI:UserManagement:Action:Modify' => 'Modify',
	'UI:UserManagement:Action:Modify+' => 'Создание и редактирование (изменение) объектов',
	'UI:UserManagement:Action:Delete' => 'Удаление',
	'UI:UserManagement:Action:Delete+' => 'Удаление объектов',
	'UI:UserManagement:Action:BulkRead' => 'Пакетное чтение (Экспорт)',
	'UI:UserManagement:Action:BulkRead+' => 'Список оъектов или массовый экспорт',
	'UI:UserManagement:Action:BulkModify' => 'Пакетное изменение',
	'UI:UserManagement:Action:BulkModify+' => 'Массовое создание/редактирование (импорт CSV)',
	'UI:UserManagement:Action:BulkDelete' => 'Пакетное удаление',
	'UI:UserManagement:Action:BulkDelete+' => 'Массовое удаление объектов',
	'UI:UserManagement:Action:Stimuli' => 'Стимулы',
	'UI:UserManagement:Action:Stimuli+' => 'Допустимые (составные) действия',
	'UI:UserManagement:Action' => 'Действие',
	'UI:UserManagement:Action+' => 'Действие, выполняемое пользователем',
	'UI:UserManagement:TitleActions' => 'Действия',
	'UI:UserManagement:Permission' => 'Разрешения',
	'UI:UserManagement:Permission+' => 'Пользовательские разрешения',
	'UI:UserManagement:Attributes' => 'Атрибуты',
	'UI:UserManagement:ActionAllowed:Yes' => 'Да',
	'UI:UserManagement:ActionAllowed:No' => 'Нет',
	'UI:UserManagement:AdminProfile+' => 'Администраторы имеют полный доступ на чтение/запись всех объектов в базе данных.',
	'UI:UserManagement:NoLifeCycleApplicable' => 'не определено',
	'UI:UserManagement:NoLifeCycleApplicable+' => 'Не определён жизненній цикл для данного класса',
	'UI:UserManagement:GrantMatrix' => 'Матрица разрешений',
	'UI:UserManagement:LinkBetween_User_And_Profile' => 'Связь между %1$s и %2$s',
	'UI:UserManagement:LinkBetween_User_And_Org' => 'Связь между %1$s и %2$s',
	
	'Menu:AdminTools' => 'Инструменты админа',
	'Menu:AdminTools+' => 'Административные инструменты',
	'Menu:AdminTools?' => 'Инструменты доступны только для пользователей, имеющих профиль администратора',

	'UI:ChangeManagementMenu' => 'Управление изменениями',
	'UI:ChangeManagementMenu+' => 'Управление изменениями',
	'UI:ChangeManagementMenu:Title' => 'Обзор изменений',
	'UI-ChangeManagementMenu-ChangesByType' => 'Изменения по типу',
	'UI-ChangeManagementMenu-ChangesByStatus' => 'Изменения по статутсу',
	'UI-ChangeManagementMenu-ChangesByWorkgroup' => 'Изменения по рабочей группе',
	'UI-ChangeManagementMenu-ChangesNotYetAssigned' => 'Не назначенные изменения',

	'UI:ConfigurationItemsMenu'=> 'Элементы конфигурации',
	'UI:ConfigurationItemsMenu+'=> 'Все устройства',
	'UI:ConfigurationItemsMenu:Title' => 'Обзор элементов конфигурации',
	'UI-ConfigurationItemsMenu-ServersByCriticity' => 'Серверы по критичности',
	'UI-ConfigurationItemsMenu-PCsByCriticity' => 'ПК по критичности',
	'UI-ConfigurationItemsMenu-NWDevicesByCriticity' => 'Сетевые устройства по критичности',
	'UI-ConfigurationItemsMenu-ApplicationsByCriticity' => 'Приложения по критичности',
	
	'UI:ConfigurationManagementMenu' => 'Управление конфигурациями',
	'UI:ConfigurationManagementMenu+' => 'Управление конфигурациями',
	'UI:ConfigurationManagementMenu:Title' => 'Обзор инфраструктуры',
	'UI-ConfigurationManagementMenu-InfraByType' => 'Объекты инфраструктуры по типу',
	'UI-ConfigurationManagementMenu-InfraByStatus' => 'Объекты инфраструктуры по статусу',

'UI:ConfigMgmtMenuOverview:Title' => 'Панель управления конфигурациями',
'UI-ConfigMgmtMenuOverview-FunctionalCIbyStatus' => 'Элементы конфигурации по статусу',
'UI-ConfigMgmtMenuOverview-FunctionalCIByType' => 'Элементы конфигурации по типу',

'UI:RequestMgmtMenuOverview:Title' => 'Панель управления запросами',
'UI-RequestManagementOverview-RequestByService' => 'Пользовательские запросы по сервису',
'UI-RequestManagementOverview-RequestByPriority' => 'Пользовательские запросы по приоритету',
'UI-RequestManagementOverview-RequestUnassigned' => 'Пользовательские запросы не назначенные не на один агент',

'UI:IncidentMgmtMenuOverview:Title' => 'Панель управления инцидентами',
'UI-IncidentManagementOverview-IncidentByService' => 'Инциденты по сервису',
'UI-IncidentManagementOverview-IncidentByPriority' => 'Инциденты по приоритету',
'UI-IncidentManagementOverview-IncidentUnassigned' => 'Инциденты не назначенные не на один агент',

'UI:ChangeMgmtMenuOverview:Title' => 'Панель управления изменениями',
'UI-ChangeManagementOverview-ChangeByType' => 'Изменения по типу',
'UI-ChangeManagementOverview-ChangeUnassigned' => 'Изменения не назначенные не на один агент',
'UI-ChangeManagementOverview-ChangeWithOutage' => 'Отключения в связи с изменениями',

'UI:ServiceMgmtMenuOverview:Title' => 'Панель управления сервисами',
'UI-ServiceManagementOverview-CustomerContractToRenew' => 'Договора с клиентами, которые будут обновлены в течении 30 дней',
'UI-ServiceManagementOverview-ProviderContractToRenew' => 'Договора с поставщиками, которые будут обновлены в течении 30 дней',

	'UI:ContactsMenu' => 'Договора',
	'UI:ContactsMenu+' => 'Договора',
	'UI:ContactsMenu:Title' => 'Обзор договоров',
	'UI-ContactsMenu-ContactsByLocation' => 'Договора по размещению',
	'UI-ContactsMenu-ContactsByType' => 'Договора по типу',
	'UI-ContactsMenu-ContactsByStatus' => 'Договора по статусу',

	'Menu:CSVImportMenu' => 'Импорт CSV',
	'Menu:CSVImportMenu+' => 'Пакетное создание или обновление',
	
	'Menu:DataModelMenu' => 'Модель данных',
	'Menu:DataModelMenu+' => 'Обзор модели данных',
	
	'Menu:ExportMenu' => 'Экспорт',
	'Menu:ExportMenu+' => 'Экспорт результатов любого запроса в HTML, CSV или XML',
	
	'Menu:NotificationsMenu' => 'Уведомления',
	'Menu:NotificationsMenu+' => 'Конфигурация уведомлений',
	'UI:NotificationsMenu:Title' => 'Конфигурация <span class="hilite">Уведомлений</span>',
	'UI:NotificationsMenu:Help' => 'Помощь',
	'UI:NotificationsMenu:HelpContent' => '<p>В iTop уведомления полностью настраиваемые. Они основаны на двух наборах объектов: <i>триггеры и действия</i>.</p>
<p><i><b>Триггеры</b></i> оперделяют когда уведомление будет выполнено. Есть 3 типа триггеров обробатывающих 3 разных фазы жизненного цикла объекта:
<ol>
	<li>the "OnCreate" триггеры сработают когда объект заданного класса будет создан</li>
	<li>the "OnStateEnter" триггеры сработают перед тем как объект заданного класса войдёт в заданное состояние (выйдет из другого состояния)</li>
	<li>the "OnStateLeave" триггеры сработают когда объекты заданного класса выйдут из заданного состояния</li>
</ol>
</p>
<p>
<i><b>Действия</b></i> определяют, какое действие будет выполнено при срабатывании триггера. Пока есть только одно действие, которое состоит в отправке сообщения на электронную почту.
Эти действия также определяют шаблон, который будет использован для отправки электронного сообщения, а также другие параметры сообщения, такие как получатель, важность и т.д.
</p>
<p>Специальная страница: <a href="../setup/email.test.php" target="_blank">email.test.php</a> доступна для тестирования и устранения неполадок в настройка почты в PHP.</p>
<p>Чтобы быть выполненными, действия необходимо ассоциировать с триггерами.
При ассоциации с триггером, каждое действие получает "порядковый" номер, который определяет порядок выполнения действий.</p>',
	'UI:NotificationsMenu:Triggers' => 'Триггеры',
	'UI:NotificationsMenu:AvailableTriggers' => 'Доступные триггеры',
	'UI:NotificationsMenu:OnCreate' => 'При создании объекта',
	'UI:NotificationsMenu:OnStateEnter' => 'При входе объекта в заданное состояние',
	'UI:NotificationsMenu:OnStateLeave' => 'При выходе объекта из заданного состояния',
	'UI:NotificationsMenu:Actions' => 'Действия',
	'UI:NotificationsMenu:AvailableActions' => 'Доступные действия',
	
	'Menu:AuditCategories' => 'Категории аудита',
	'Menu:AuditCategories+' => 'Категории аудита',
	'Menu:Notifications:Title' => 'Категории аудита',
	
	'Menu:RunQueriesMenu' => 'Выполнение запросов',
	'Menu:RunQueriesMenu+' => 'Выполнение любых запросов',
	
	'Menu:DataAdministration' => 'Административные данные',
	'Menu:DataAdministration+' => 'Административные данные',
	
	'Menu:UniversalSearchMenu' => 'Универсальный поиск',
	'Menu:UniversalSearchMenu+' => 'Поиск чего угодно...',
	
	'Menu:ApplicationLogMenu' => 'Логгирование приложения',
	'Menu:ApplicationLogMenu+' => 'Логгирование приложения',
	'Menu:ApplicationLogMenu:Title' => 'Логгирование приложения',

	'Menu:UserManagementMenu' => 'Управление пользователями',
	'Menu:UserManagementMenu+' => 'Управление пользователями',

	'Menu:ProfilesMenu' => 'Профили',
	'Menu:ProfilesMenu+' => 'Профили',
	'Menu:ProfilesMenu:Title' => 'Профили',

	'Menu:UserAccountsMenu' => 'Учетные записи пользователей',
	'Menu:UserAccountsMenu+' => 'Учетные записи пользователей',
	'Menu:UserAccountsMenu:Title' => 'Учетные записи пользователей',	

	'UI:iTopVersion:Short' => 'iTop версия %1$s',
	'UI:iTopVersion:Long' => 'iTop версия %1$s-%2$s основан на %3$s',
	'UI:PropertiesTab' => 'Свойства',

	'UI:OpenDocumentInNewWindow_' => 'Открыть этот документ в новом окне: %1$s',
	'UI:DownloadDocument_' => 'Скачать этот документ: %1$s',
	'UI:Document:NoPreview' => 'Не доступен предварительный просомтр для документов данного типа',

	'UI:DeadlineMissedBy_duration' => 'Пропущен %1$s',
	'UI:Deadline_LessThan1Min' => '< 1 мин',		
	'UI:Deadline_Minutes' => '%1$d мин',			
	'UI:Deadline_Hours_Minutes' => '%1$dч %2$dмин',			
	'UI:Deadline_Days_Hours_Minutes' => '%1$dд %2$dч %3$dмин',
	'UI:Help' => 'Помощь',
	'UI:PasswordConfirm' => '(Подтвердить)',
	'UI:BeforeAdding_Class_ObjectsSaveThisObject' => 'Перед добавлением %1$s объектво, сохраните этот объект.',
	'UI:DisplayThisMessageAtStartup' => 'Показать это сообщение при запуске',
	'UI:RelationshipGraph' => 'Графический вид',
	'UI:RelationshipList' => 'Список',

	'Portal:Title' => 'Пользовательский iTop портал',
	'Portal:Refresh' => 'Обновить',
	'Portal:Back' => 'Назад',
	'Portal:CreateNewRequest' => 'Создать новый запрос',
	'Portal:ChangeMyPassword' => 'Изменить мой пароль',
	'Portal:Disconnect' => 'Отключить',
	'Portal:OpenRequests' => 'Мои открытые запросы',
	'Portal:ResolvedRequests'  => 'Мои решённые запросы',
	'Portal:SelectService' => 'Выбери сервис из каталога:',
	'Portal:PleaseSelectOneService' => 'Необходимо выбрать хотя-бы один сервис',
	'Portal:SelectSubcategoryFrom_Service' => 'Выбери под-категорию для сервиса %1$s:',
	'Portal:PleaseSelectAServiceSubCategory' => 'Необходимо выбрать одну под-категорию',
	'Portal:DescriptionOfTheRequest' => 'Введи описание запроса:',
	'Portal:TitleRequestDetailsFor_Request' => 'Подробности запроса %1$s:',
	'Portal:NoOpenRequest' => 'Нет запросов в этой категории.',
	'Portal:Button:CloseTicket' => 'Закрыть этот "тикет"',
	'Portal:EnterYourCommentsOnTicket' => 'Введите ваши каментарии по решению этого "тикета":',
	'Portal:ErrorNoContactForThisUser' => 'Ошибка: текющий пользователь не ассоциирован с Контактом/Человеком. Пожалуйста свяжитесь с вашим администратором.',
	
	'Enum:Undefined' => 'Неопределён',
));



?>
