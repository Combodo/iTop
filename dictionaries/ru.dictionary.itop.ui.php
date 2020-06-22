<?php
/**
 * Локализация интерфейса Combodo iTop подготовлена сообществом iTop по-русски http://community.itop-itsm.ru.
 *
 * @author      Vladimir Kunin <v.b.kunin@gmail.com>
 * @link        http://community.itop-itsm.ru  iTop Russian Community
 * @link        https://github.com/itop-itsm-ru/itop-rus
 * @license     http://opensource.org/licenses/AGPL-3.0
 *
 */
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
	'Class:AuditCategory/Attribute:definition_set' => 'Набор объектов',
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
	'Class:TagSetFieldData/Attribute:finalclass' => 'Класс тега',
	'Class:TagSetFieldData/Attribute:obj_class' => 'Класс объекта',
	'Class:TagSetFieldData/Attribute:obj_attcode' => 'Код поля',
	'Class:AuditRule/Attribute:query' => 'Запрос для выполнения',
	'Class:AuditRule/Attribute:query+' => 'OQL выражение, выполняющее проверку набора объектов категории аудита',
	'Class:AuditRule/Attribute:valid_flag' => 'Валидные объекты?',
	'Class:AuditRule/Attribute:valid_flag+' => 'Выберите \'Истина\', если правило возвращает объекты, успешно прошедшие проверку, иначе выберите \'Ложь\'.',
	'Class:AuditRule/Attribute:valid_flag/Value:true' => 'Истина',
	'Class:AuditRule/Attribute:valid_flag/Value:true+' => 'Возвращаемые объекты считаются прошедшими проверку',
	'Class:AuditRule/Attribute:valid_flag/Value:false' => 'Ложь',
	'Class:AuditRule/Attribute:valid_flag/Value:false+' => 'Возвращаемые объекты считаются НЕ прошедшими проверку',
	'Class:AuditRule/Attribute:category_id' => 'Категория',
	'Class:AuditRule/Attribute:category_id+' => 'Категория для этого правила',
	'Class:AuditRule/Attribute:category_name' => 'Категория',
	'Class:AuditRule/Attribute:category_name+' => 'Категория для этого правила',
));

//
// Class: QueryOQL
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Query' => 'Запрос',
	'Class:Query+' => 'Запрос - это набор данных, определенных динамическим путем',
	'Class:Query/Attribute:name' => 'Название',
	'Class:Query/Attribute:name+' => 'Идентифицирует запрос',
	'Class:Query/Attribute:description' => 'Описание',
	'Class:Query/Attribute:description+' => 'Длинное описание запроса (назначение, использование и т.д.)',
	'Class:QueryOQL/Attribute:fields' => 'Экспорт. поля',
	'Class:QueryOQL/Attribute:fields+' => 'Список атрибутов для экспорта, разделённых запятыми (или alias.attribute)',
	'Class:QueryOQL' => 'OQL запрос',
	'Class:QueryOQL+' => 'Запрос, основанный на OQL (Object Query Language)',
	'Class:QueryOQL/Attribute:oql' => 'Выражение',
	'Class:QueryOQL/Attribute:oql+' => 'OQL Выражение',
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
	'Class:User+' => 'Пользователь',
	'Class:User/Attribute:finalclass' => 'Тип аккаунта',
	'Class:User/Attribute:finalclass+' => '',
	'Class:User/Attribute:contactid' => 'Персона',
	'Class:User/Attribute:contactid+' => '',
	'Class:User/Attribute:org_id' => 'Организация',
	'Class:User/Attribute:org_id+' => 'Организация связанной персоны',
	'Class:User/Attribute:last_name' => 'Фамилия',
	'Class:User/Attribute:last_name+' => 'Фамилия связанной персоны',
	'Class:User/Attribute:first_name' => 'Имя',
	'Class:User/Attribute:first_name+' => 'Имя связанной персоны',
	'Class:User/Attribute:email' => 'email',
	'Class:User/Attribute:email+' => 'email связанной персоны',
	'Class:User/Attribute:login' => 'Логин',
	'Class:User/Attribute:login+' => 'Уникальный логин пользователя',
	'Class:User/Attribute:language' => 'Язык',
	'Class:User/Attribute:language+' => 'Язык пользователя',
	'Class:User/Attribute:language/Value:EN US' => 'Английский',
	'Class:User/Attribute:language/Value:EN US+' => 'Английский (США)',
	'Class:User/Attribute:language/Value:FR FR' => 'Французский',
	'Class:User/Attribute:language/Value:FR FR+' => 'Французский (Франция)',
	'Class:User/Attribute:profile_list' => 'Профили',
	'Class:User/Attribute:profile_list+' => 'Профили, предоставляющие права этому пользователю',
	'Class:User/Attribute:allowed_org_list' => 'Разрешённые организации',
	'Class:User/Attribute:allowed_org_list+' => 'Пользователь может видеть данные только указанных ниже организации. Оставьте поле пустым для доступа ко всем данным.',
	'Class:User/Attribute:status' => 'Статус',
	'Class:User/Attribute:status+' => 'Учетная запись пользователя включена или отключена.',
	'Class:User/Attribute:status/Value:enabled' => 'Включен',
	'Class:User/Attribute:status/Value:disabled' => 'Отключен',

	'Class:User/Error:LoginMustBeUnique' => 'Логин должен быть уникальным - "%1s" уже используется.',
	'Class:User/Error:AtLeastOneProfileIsNeeded' => 'Как минимум один профиль должен быть назначен данному пользователю.',
	'Class:User/Error:AtLeastOneOrganizationIsNeeded' => 'Этому пользователю должна быть назначена хотя бы одна организация.',
	'Class:User/Error:OrganizationNotAllowed' => 'Организация не разрешена.',
	'Class:User/Error:UserOrganizationNotAllowed' => 'Учетная запись пользователя не принадлежит вашим разрешенным организациям.',
	'Class:User/Error:PersonIsMandatory' => 'Необходимо выбрать персону.',
	'Class:UserInternal' => 'Внутренний пользователь',
	'Class:UserInternal+' => 'Учетная запись создана внутри iTop',
));

//
// Class: URP_Profiles
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:URP_Profiles' => 'Профиль',
	'Class:URP_Profiles+' => 'Пользовательский профиль',
	'Class:URP_Profiles/Attribute:name' => 'Название',
	'Class:URP_Profiles/Attribute:name+' => 'Название',
	'Class:URP_Profiles/Attribute:description' => 'Описание',
	'Class:URP_Profiles/Attribute:description+' => 'Описание',
	'Class:URP_Profiles/Attribute:user_list' => 'Пользователи',
	'Class:URP_Profiles/Attribute:user_list+' => 'Пользователи, имеющие эту роль',
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
	'Class:URP_UserProfile' => 'Профиль пользователя',
	'Class:URP_UserProfile+' => 'Профиль пользователя',
	'Class:URP_UserProfile/Attribute:userid' => 'Пользователь',
	'Class:URP_UserProfile/Attribute:userid+' => 'учетная запись пользователя',
	'Class:URP_UserProfile/Attribute:userlogin' => 'Логин',
	'Class:URP_UserProfile/Attribute:userlogin+' => 'Логин пользователя',
	'Class:URP_UserProfile/Attribute:profileid' => 'Профиль',
	'Class:URP_UserProfile/Attribute:profileid+' => 'использование профиля',
	'Class:URP_UserProfile/Attribute:profile' => 'Профиль',
	'Class:URP_UserProfile/Attribute:profile+' => 'Название профиля',
	'Class:URP_UserProfile/Attribute:reason' => 'Причина',
	'Class:URP_UserProfile/Attribute:reason+' => 'Пояснение причины назначения этой роли',
));

//
// Class: URP_UserOrg
//


Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:URP_UserOrg' => 'Организации пользователя',
	'Class:URP_UserOrg+' => 'Разрешённые организации',
	'Class:URP_UserOrg/Attribute:userid' => 'Пользователь',
	'Class:URP_UserOrg/Attribute:userid+' => 'Учетная запись пользователя',
	'Class:URP_UserOrg/Attribute:userlogin' => 'Логин',
	'Class:URP_UserOrg/Attribute:userlogin+' => 'Логин пользователя',
	'Class:URP_UserOrg/Attribute:allowed_org_id' => 'Организация',
	'Class:URP_UserOrg/Attribute:allowed_org_id+' => 'Разрешённая организация',
	'Class:URP_UserOrg/Attribute:allowed_org_name' => 'Организация',
	'Class:URP_UserOrg/Attribute:allowed_org_name+' => 'Разрешённая организация',
	'Class:URP_UserOrg/Attribute:reason' => 'Причина',
	'Class:URP_UserOrg/Attribute:reason+' => 'Пояснение причины разрешения доступа к данным этой организации',
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
// Class: UserDashboard
//
Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:UserDashboard' => 'Дашборд пользователя',
	'Class:UserDashboard+' => '~~',
	'Class:UserDashboard/Attribute:user_id' => 'Пользователь',
	'Class:UserDashboard/Attribute:user_id+' => '',
	'Class:UserDashboard/Attribute:menu_code' => 'Код меню',
	'Class:UserDashboard/Attribute:menu_code+' => '',
	'Class:UserDashboard/Attribute:contents' => 'Содержимое',
	'Class:UserDashboard/Attribute:contents+' => '',
));

//
// Expression to Natural language
//
Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Expression:Unit:Short:DAY' => 'd',
	'Expression:Unit:Short:WEEK' => 'w',
	'Expression:Unit:Short:MONTH' => 'm',
	'Expression:Unit:Short:YEAR' => 'y',
));


//
// String from the User Interface: menu, messages, buttons, etc...
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'BooleanLabel:yes' => 'да',
	'BooleanLabel:no' => 'нет',
	'UI:Login:Title' => 'Вход в iTop',
	'Menu:WelcomeMenu' => 'Добро пожаловать', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenu+' => 'Добро пожаловать в iTop', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenuPage' => 'Добро пожаловать', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenuPage+' => 'Добро пожаловать в iTop', // Duplicated into itop-welcome-itil (will be removed from here...)
	'UI:WelcomeMenu:Title' => 'Добро пожаловать в iTop',

	'UI:WelcomeMenu:LeftBlock' => '<p>iTop является порталом оперативного централизованного управления IT инфраструктурой с открытым исходным кодом.</p>
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
<li>Повышают эффективность управления IT</li>
<li>Повышают производительность IT-операций</li>
<li>Улучшают удовлетворенность клиентов и обеспечивают понимание бизнес-процессов.</li>
</ul>
</p>
<p>iTop полностью открыт для интеграции в рамках текущего управления ИТ-инфраструктурой.</p>
<p>
<ul>Внедрение ИТ-портала нового поколения поможет вам:
<li>Лучше управлять более и более сложными ИТ-окружениями.</li>
<li>Реализовывать процессы ITIL в вашем собственном темпе.</li>
<li>Управлять наиболее важным активом ИТ: документацией.</li>
</ul>
</p>',
	'UI:WelcomeMenu:AllOpenRequests' => 'Открытые запросы: %1$d',
	'UI:WelcomeMenu:MyCalls' => 'Мои запросы',
	'UI:WelcomeMenu:OpenIncidents' => 'Открытые инциденты: %1$d',
	'UI:WelcomeMenu:AllConfigItems' => 'Кофигурационные единицы: %1$d',
	'UI:WelcomeMenu:MyIncidents' => 'Инциденты назначенные на меня',
	'UI:AllOrganizations' => ' Все организации ',
	'UI:YourSearch' => 'Поиск',
	'UI:LoggedAsMessage' => 'Вы вошли как %1$s',
	'UI:LoggedAsMessage+Admin' => 'Вы вошли как %1$s (Администратор)',
	'UI:Button:Logoff' => 'Выход',
	'UI:Button:GlobalSearch' => 'Поиск',
	'UI:Button:Search' => ' Поиск ',
	'UI:Button:Query' => ' Запрос ',
	'UI:Button:Ok' => 'Ok',
	'UI:Button:Save' => 'Сохранить',
	'UI:Button:Cancel' => 'Отмена',
	'UI:Button:Close' => 'Закрыть',
	'UI:Button:Apply' => 'Применить',
	'UI:Button:Back' => ' << Назад ',
	'UI:Button:Restart' => ' |<< Перезапустить ',
	'UI:Button:Next' => ' Вперёд >> ',
	'UI:Button:Finish' => ' Завершить ',
	'UI:Button:DoImport' => ' Выполнить импорт ! ',
	'UI:Button:Done' => ' Готово ',
	'UI:Button:SimulateImport' => ' Эмулировать импорт ',
	'UI:Button:Test' => 'Тестировать!',
	'UI:Button:Evaluate' => ' Оценка ',
	'UI:Button:Evaluate:Title' => ' Оценка (Ctrl+Enter)',
	'UI:Button:AddObject' => ' Добавить... ',
	'UI:Button:BrowseObjects' => ' Обзор... ',
	'UI:Button:Add' => ' Добавить ',
	'UI:Button:AddToList' => ' << Добавить ',
	'UI:Button:RemoveFromList' => ' Удалить >> ',
	'UI:Button:FilterList' => ' Фильтр... ',
	'UI:Button:Create' => ' Создать ',
	'UI:Button:Delete' => ' Удалить ! ',
	'UI:Button:Rename' => ' Переименовать...',
	'UI:Button:ChangePassword' => ' Изменить пароль ',
	'UI:Button:ResetPassword' => ' Сбросить пароль ',
	'UI:Button:Insert' => 'Вставить',
	'UI:Button:More' => 'Больше',
	'UI:Button:Less' => 'Меньше',
	'UI:Button:Wait' => 'Please wait while updating fields~~',
	'UI:Treeview:CollapseAll' => 'Collapse All~~',
	'UI:Treeview:ExpandAll' => 'Expand All~~',

	'UI:SearchToggle' => 'Поиск',
	'UI:ClickToCreateNew' => 'Создать: %1$s',
	'UI:SearchFor_Class' => 'Поиск: %1$s',
	'UI:NoObjectToDisplay' => 'Нет объектов для отображения.',
	'UI:Error:SaveFailed' => 'Не удается сохранить объект :',
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
	'UI:Error:InvalidDashboardFile' => 'Ошибка: недопустимый файл дашборда',
	'UI:Error:InvalidDashboard' => 'Ошибка: недопустимый дашборд',
	'UI:Error:MaintenanceMode' => 'Приложение в режиме технического обслуживания',
	'UI:Error:MaintenanceTitle' => 'Техническое обслуживание',

	'UI:GroupBy:Count' => 'Количество',
	'UI:GroupBy:Count+' => 'Количество элементов',
	'UI:CountOfObjects' => '%1$d объектов соответствует критериям.',
	'UI_CountOfObjectsShort' => '%1$d объектов.',
	'UI:NoObject_Class_ToDisplay' => 'Нечего отображать %1$s',
	'UI:History:LastModified_On_By' => 'Последнее изменение %1$s by %2$s.',
	'UI:HistoryTab' => 'История',
	'UI:NotificationsTab' => 'Оповещения',
	'UI:History:BulkImports' => 'История~~',
	'UI:History:BulkImports+' => 'List of CSV imports (latest import first)',
	'UI:History:BulkImportDetails' => 'Changes resulting from the CSV import performed on %1$s (by %2$s)~~',
	'UI:History:Date' => 'Дата',
	'UI:History:Date+' => 'Дата изменения',
	'UI:History:User' => 'Пользователь',
	'UI:History:User+' => 'Пользователь сделавший изменение',
	'UI:History:Changes' => 'Изменения',
	'UI:History:Changes+' => 'Изменения, внесенные в объект',
	'UI:History:StatsCreations' => 'Создан~~',
	'UI:History:StatsCreations+' => 'Count of objects created',
	'UI:History:StatsModifs' => 'Изменен~~',
	'UI:History:StatsModifs+' => 'Count of objects modified',
	'UI:History:StatsDeletes' => 'Удален~~',
	'UI:History:StatsDeletes+' => 'Count of objects deleted',
	'UI:Loading' => 'Загрузка...',
	'UI:Menu:Actions' => 'Действия',
	'UI:Menu:OtherActions' => 'Другие Действия',
	'UI:Menu:New' => 'Новый...',
	'UI:Menu:Add' => 'Добавить...',
	'UI:Menu:Manage' => 'Управление...',
	'UI:Menu:EMail' => 'Отправить ссылку по email',
	'UI:Menu:CSVExport' => 'Экспорт в CSV',
	'UI:Menu:Modify' => 'Изменить...',
	'UI:Menu:Delete' => 'Удалить...',
	'UI:Menu:BulkDelete' => 'Удалить...',
	'UI:UndefinedObject' => 'неопределённый',
	'UI:Document:OpenInNewWindow:Download' => 'Открыть в новом окне: %1$s, Загрузка: %2$s',
	'UI:SplitDateTime-Date' => 'дата~~',
	'UI:SplitDateTime-Time' => 'время~~',
	'UI:TruncatedResults' => '%1$d объектов отображено из %2$d',
	'UI:DisplayAll' => 'Показать всё',
	'UI:CollapseList' => 'Свернуть',
	'UI:CountOfResults' => '%1$d объект(ы)',
	'UI:ChangesLogTitle' => 'Журнал изменений (%1$d):',
	'UI:EmptyChangesLogTitle' => 'Журнал изменений пустой',
	'UI:SearchFor_Class_Objects' => 'Поиск: %1$s',
	'UI:OQLQueryBuilderTitle' => 'Коструктор запросов OQL',
	'UI:OQLQueryTab' => 'Запрос OQL',
	'UI:SimpleSearchTab' => 'Простой поиск',
	'UI:Details+' => 'Подробности',
	'UI:SearchValue:Any' => '* Любой *',
	'UI:SearchValue:Mixed' => '* смешанный *',
	'UI:SearchValue:NbSelected' => '# выбрано',
	'UI:SearchValue:CheckAll' => 'Выбрать все',
	'UI:SearchValue:UncheckAll' => 'Сбросить',
	'UI:SelectOne' => '-- выбрать --',
	'UI:Login:Welcome' => 'Добро пожаловать в iTop!',
	'UI:Login:IncorrectLoginPassword' => 'Неправильный логин/пароль. Пожалуйста, попробуйте еще раз.',
	'UI:Login:IdentifyYourself' => 'Представьтесь, прежде чем продолжить',
	'UI:Login:UserNamePrompt' => 'Имя пользователя',
	'UI:Login:PasswordPrompt' => 'Пароль',
	'UI:Login:ForgotPwd' => 'Забыли пароль?',
	'UI:Login:ForgotPwdForm' => 'Восстановление пароля',
	'UI:Login:ForgotPwdForm+' => 'Введите свой логин для входа в систему и нажмите "Отправить". iTop отправит email с инструкциями по восстановлению пароля на ваш электронный адрес.',
	'UI:Login:ResetPassword' => 'Отправить',
	'UI:Login:ResetPwdFailed' => 'Не удалось отправить email: %1$s',
	'UI:Login:SeparatorOr' => 'или',

	'UI:ResetPwd-Error-WrongLogin' => 'учетная запись с логином "%1$s" не найдена.',
	'UI:ResetPwd-Error-NotPossible' => 'восстановление пароля для внешних учётных записей недоступно.',
	'UI:ResetPwd-Error-FixedPwd' => 'восстановление пароля для данной учётной записи недоступно. Пожалуйста, обратитесь к администратору.',
	'UI:ResetPwd-Error-NoContact' => 'данная учетная запись не ассоциирована с персоной. Пожалуйста, обратитесь к администратору.',
	'UI:ResetPwd-Error-NoEmailAtt' => 'аккаунт не ассоциирован с персоной, имеющей атрибут электронной почты. Пожалуйста, обратитесь к администратору.',
	'UI:ResetPwd-Error-NoEmail' => 'отсутствует адрес электронной почты. Пожалуйста, обратитесь к администратору.',
	'UI:ResetPwd-Error-Send' => 'технические проблемы с отправкой электронной почты. Пожалуйста, обратитесь к администратору.',
	'UI:ResetPwd-EmailSent' => 'Please check your email box and follow the instructions. If you receive no email, please check the login you typed.~~',
	'UI:ResetPwd-EmailSubject' => 'Восстановление пароля',
	'UI:ResetPwd-EmailBody' => '<body><p>Вы запросили восстановление пароля iTop.</p><p>Пожалуйста, воспользуйтесь <a href="%1$s">этой ссылкой</a> для задания нового пароля.</p></body>',

	'UI:ResetPwd-Title' => 'Восстановление пароля',
	'UI:ResetPwd-Error-InvalidToken' => 'Извините, недействительная ссылка. Если вы запрашивали восстановление пароля несколько раз подряд, пожалуйста, убедитесь, что используете ссылку из последнего полученного письма.',
	'UI:ResetPwd-Error-EnterPassword' => 'Введите новый пароль для учетной записи пользователя \'%1$s\'.',
	'UI:ResetPwd-Ready' => 'Пароль успешно изменён.',
	'UI:ResetPwd-Login' => 'Войти...',

	'UI:Login:About' => '',
	'UI:Login:ChangeYourPassword' => 'Изменение пароля',
	'UI:Login:OldPasswordPrompt' => 'Старый пароль',
	'UI:Login:NewPasswordPrompt' => 'Новый пароль',
	'UI:Login:RetypeNewPasswordPrompt' => 'Повторите новый пароль',
	'UI:Login:IncorrectOldPassword' => 'Ошибка: старый пароль неверный',
	'UI:LogOffMenu' => 'Выход',
	'UI:LogOff:ThankYou' => 'Спасибо за использование iTop',
	'UI:LogOff:ClickHereToLoginAgain' => 'Нажмите здесь, чтобы снова войти...',
	'UI:ChangePwdMenu' => 'Изменить пароль...',
	'UI:Login:PasswordChanged' => 'Пароль успешно изменён!',
	'UI:AccessRO-All' => 'Только чтение~~',
	'UI:AccessRO-Users' => 'Только чтение для конечных пользователей~~',
	'UI:ApplicationEnvironment' => 'Application environment: %1$s~~',
	'UI:Login:RetypePwdDoesNotMatch' => 'Пароли не совпадают',
	'UI:Button:Login' => 'Войти',
	'UI:Login:Error:AccessRestricted' => 'Доступ к iTop ограничен. Пожалуйста, свяжитесь с администратором iTop.',
	'UI:Login:Error:AccessAdmin' => 'Доступ ограничен для лиц с административными привилегиями. Пожалуйста, свяжитесь с администратором iTop.',
	'UI:Login:Error:WrongOrganizationName' => 'Неизвестная организация',
	'UI:Login:Error:MultipleContactsHaveSameEmail' => 'Несколько контактов имеют один и тот же адрес электронной почты',
	'UI:Login:Error:NoValidProfiles' => 'Нет допустимого профиля',
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
	'UI:Title:BulkSynchro_nbItem_ofClass_class' => 'Synchronization of %1$d objects of class %2$s~~',
	'UI:CSVImport:ClassesSelectOne' => '-- выбрать один --',
	'UI:CSVImport:ErrorExtendedAttCode' => 'Внутренняя ошибка: "%1$s" некорректный код потому, что "%2$s" НЕ являеться внешним ключом класса "%3$s"',
	'UI:CSVImport:ObjectsWillStayUnchanged' => '%1$d объект(ы) останеться неизменным.',
	'UI:CSVImport:ObjectsWillBeModified' => '%1$d объект(ы) будет изменён.',
	'UI:CSVImport:ObjectsWillBeAdded' => '%1$d объект(ы) будет добавлен.',
	'UI:CSVImport:ObjectsWillHaveErrors' => '%1$d объект(ы) будут ошибочны.',
	'UI:CSVImport:ObjectsRemainedUnchanged' => '%1$d объект(ы) остался неизменённым.',
	'UI:CSVImport:ObjectsWereModified' => '%1$d объект(ы) изменён.',
	'UI:CSVImport:ObjectsWereAdded' => '%1$d объект(ы) был добавлен.',
	'UI:CSVImport:ObjectsHadErrors' => '%1$d объект(ы) содержит ошибки.',
	'UI:Title:CSVImportStep2' => 'Шаг 2 из 5: Опции данных CSV',
	'UI:Title:CSVImportStep3' => 'Шаг 3 из 5: Распределение данных',
	'UI:Title:CSVImportStep4' => 'Шаг 4 из 5: Симуляция импорта',
	'UI:Title:CSVImportStep5' => 'Шаг 5 из 5: Импорт завершён',
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
	'UI:CSVImport:AdvancedMode+' => 'In advanced mode the "id" (primary key) of the objects can be used to update and rename objects. However the column "id" (if present) can only be used as a search criteria and can not be combined with any other search criteria.',
	'UI:CSVImport:SelectAClassFirst' => 'Выберите класс импортируемых объетов для настройки распределения полей',
	'UI:CSVImport:HeaderFields' => 'Поля',
	'UI:CSVImport:HeaderMappings' => 'Распределение',
	'UI:CSVImport:HeaderSearch' => 'Поиск?',
	'UI:CSVImport:AlertIncompleteMapping' => 'Необходимо выбрать распределение для каждой ячейки.',
	'UI:CSVImport:AlertMultipleMapping' => 'Please make sure that a target field is mapped only once.',
	'UI:CSVImport:AlertNoSearchCriteria' => 'Необходимо выбрать, по крайней мере один критерий',
	'UI:CSVImport:Encoding' => 'Кодировка символов',
	'UI:UniversalSearchTitle' => 'iTop - Универсальный поиск',
	'UI:UniversalSearch:Error' => 'Ошибка: %1$s',
	'UI:UniversalSearch:LabelSelectTheClass' => 'Выбор класса для поиска: ',

	'UI:CSVReport-Value-Modified' => 'Изменен~~',
	'UI:CSVReport-Value-SetIssue' => 'Не может быть изменен - причина: %1$s~~',
	'UI:CSVReport-Value-ChangeIssue' => 'Не может быть изменен %1$s - причина: %2$s~~',
	'UI:CSVReport-Value-NoMatch' => 'Нет совпадений',
	'UI:CSVReport-Value-Missing' => 'Отсутствует обязательное значение',
	'UI:CSVReport-Value-Ambiguous' => 'Ambiguous: найдено %1$s объектов~~',
	'UI:CSVReport-Row-Unchanged' => 'без изменений',
	'UI:CSVReport-Row-Created' => 'созданный',
	'UI:CSVReport-Row-Updated' => 'updated %1$d cols~~',
	'UI:CSVReport-Row-Disappeared' => 'disappeared, changed %1$d cols~~',
	'UI:CSVReport-Row-Issue' => 'Issue: %1$s~~',
	'UI:CSVReport-Value-Issue-Null' => 'Поле не должно быть пустым~~',
	'UI:CSVReport-Value-Issue-NotFound' => 'Не найден~~',
	'UI:CSVReport-Value-Issue-FoundMany' => 'Найдено %1$d значений~~',
	'UI:CSVReport-Value-Issue-Readonly' => 'Аттрибут \'%1$s\' доступен только для чтения и не может быть изменен (ткущее значение: %2$s, предложенное значение: %3$s)~~',
	'UI:CSVReport-Value-Issue-Format' => 'Не удалось обработать запрос: %1$s~~',
	'UI:CSVReport-Value-Issue-NoMatch' => 'Неизвестное значение атрибута \'%1$s\': ничего не найдено, проверьте правильность ввода',
	'UI:CSVReport-Value-Issue-Unknown' => 'Неизвестное значение атрибута \'%1$s\': %2$s~~',
	'UI:CSVReport-Row-Issue-Inconsistent' => 'Несоответствие атрибутов: %1$s~~',
	'UI:CSVReport-Row-Issue-Attribute' => 'Неизвестное значение(я) атрибута~~',
	'UI:CSVReport-Row-Issue-MissingExtKey' => 'Не может быть создан так как отсутсвует внешний ключ(и): %1$s~~',
	'UI:CSVReport-Row-Issue-DateFormat' => 'Формат даты неверен~~',
	'UI:CSVReport-Row-Issue-Reconciliation' => 'Невозможно согласовать',
	'UI:CSVReport-Row-Issue-Ambiguous' => 'Согласование спорное',
	'UI:CSVReport-Row-Issue-Internal' => 'Внутренняя ошибка: %1$s, %2$s~~',

	'UI:CSVReport-Icon-Unchanged' => 'Неизмен.',
	'UI:CSVReport-Icon-Modified' => 'Измен.',
	'UI:CSVReport-Icon-Missing' => 'Упущен.',
	'UI:CSVReport-Object-MissingToUpdate' => 'Отсутствующий объект: будет обновлен',
	'UI:CSVReport-Object-MissingUpdated' => 'Отсутствующий объект: обновлен',
	'UI:CSVReport-Icon-Created' => 'Создан',
	'UI:CSVReport-Object-ToCreate' => 'Был создан объект~~',
	'UI:CSVReport-Object-Created' => 'Объект создан~~',
	'UI:CSVReport-Icon-Error' => 'Ошибка~~',
	'UI:CSVReport-Object-Error' => 'ОШИБКА: %1$s~~',
	'UI:CSVReport-Object-Ambiguous' => 'Двусмыслен.: %1$s~~',
	'UI:CSVReport-Stats-Errors' => '%1$.0f %% загруженных объектов имеют ошибки. Проигнорированы.',
	'UI:CSVReport-Stats-Created' => '%1$.0f %% загруженных объектов были созданы.',
	'UI:CSVReport-Stats-Modified' => '%1$.0f %% загруженных объектов были изменены.',

	'UI:CSVExport:AdvancedMode' => 'Расширенный режим',
	'UI:CSVExport:AdvancedMode+' => 'In advanced mode, several columns are added to the export: the id of the object, the id of external keys and their reconciliation attributes.',
	'UI:CSVExport:LostChars' => 'Проблема кодировки',
	'UI:CSVExport:LostChars+' => 'The downloaded file will be encoded into %1$s. iTop has detected some characters that are not compatible with this format. Those characters will either be replaced by a substitute (e.g. accentuated chars losing the accent), or they will be discarded. You can copy/paste the data from your web browser. Alternatively, you can contact your administrator to change the encoding (See parameter \'csv_file_default_charset\').',

	'UI:Audit:Title' => 'iTop - Аудит CMDB',
	'UI:Audit:InteractiveAudit' => 'Интерактивный аудит',
	'UI:Audit:HeaderAuditRule' => 'Правило аудита',
	'UI:Audit:HeaderNbObjects' => '# Объекты',
	'UI:Audit:HeaderNbErrors' => '# Ошибки',
	'UI:Audit:PercentageOk' => '% Ok',
	'UI:Audit:ErrorIn_Rule_Reason' => 'OQL ошибка в правиле %1$s: %2$s.~~',
	'UI:Audit:ErrorIn_Category_Reason' => 'OQL ошибка в категории %1$s: %2$s.~~',

	'UI:RunQuery:Title' => 'iTop - Оценка запросов OQL',
	'UI:RunQuery:QueryExamples' => 'Примеры запросов',
	'UI:RunQuery:HeaderPurpose' => 'Цель',
	'UI:RunQuery:HeaderPurpose+' => 'Объяснение запросов',
	'UI:RunQuery:HeaderOQLExpression' => 'Выражение OQL',
	'UI:RunQuery:HeaderOQLExpression+' => 'Запрос в синтаксисе OQL',
	'UI:RunQuery:ExpressionToEvaluate' => 'Оценка выражения: ',
	'UI:RunQuery:MoreInfo' => 'Подробная информация о запросе: ',
	'UI:RunQuery:DevelopedQuery' => 'Декомпилированный запрос OQL: ',
	'UI:RunQuery:SerializedFilter' => 'Сериализованная версия: ',
	'UI:RunQuery:DevelopedOQL' => 'Подготовленный OQL: ',
	'UI:RunQuery:DevelopedOQLCount' => 'Подготовленный OQL для count: ',
	'UI:RunQuery:ResultSQLCount' => 'Результирующий SQL для count: ',
	'UI:RunQuery:ResultSQL' => 'Результирующий SQL: ',
	'UI:RunQuery:Error' => 'Ошибка при выполнении запроса: %1$s',
	'UI:Query:UrlForExcel' => 'URL-адрес для использования в веб-запросах MS-Excel',
	'UI:Query:UrlV1' => 'Список полей был оставлен неопределенным. Страница <em>export-V2.php</em> не может быть вызван без этой информации. Поэтому URL-адрес, предложенный здесь ниже, указывает на устаревшую страницу: <em>export.php</ем>. Эта устаревшая версия экспорта имеет следующее ограничение: список экспортируемых полей может варьироваться в зависимости от формата вывода и модели данных iTop. если вы хотите гарантировать, что список экспортируемых столбцов будет оставаться стабильным в долгосрочной перспективе, то вы должны указать значение атрибута "Экспорт. поля" и использовать страницу <em>export-V2.php</ем>.',
	'UI:Schema:Title' => 'iTop схема объектов',
	'UI:Schema:CategoryMenuItem' => 'Категория <b>%1$s</b>',
	'UI:Schema:Relationships' => 'Отношения',
	'UI:Schema:AbstractClass' => 'Абстрактный класс: используется для наследования свойств, объекты этого класса не создаются.',
	'UI:Schema:NonAbstractClass' => 'Реальный класс: объекты этого класса могут быть созданы.',
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
	'UI:Schema:ClassFilter' => 'Class:~~',
	'UI:Schema:DisplayLabel' => 'Display:~~',
	'UI:Schema:DisplaySelector/LabelAndCode' => 'Label and code~~',
	'UI:Schema:DisplaySelector/Label' => 'Label~~',
	'UI:Schema:DisplaySelector/Code' => 'Code~~',
	'UI:Schema:Attribute/Filter' => 'Filter~~',
	'UI:Schema:DefaultNullValue' => 'Default null : "%1$s"~~',
	'UI:LinksWidget:Autocomplete+' => 'Введите первые 3 символа...',
	'UI:Edit:TestQuery' => 'Проверить запрос',
	'UI:Combo:SelectValue' => '--- выбор значения ---',
	'UI:Label:SelectedObjects' => 'Выбранные объекты: ',
	'UI:Label:AvailableObjects' => 'Доступные объекты: ',
	'UI:Link_Class_Attributes' => '%1$s атрибуты',
	'UI:SelectAllToggle+' => 'Выбрать всё / Отменить всё',
	'UI:AddObjectsOf_Class_LinkedWith_Class_Instance' => 'Добавить %1$s объекты связанные с %2$s: %3$s',
	'UI:AddObjectsOf_Class_LinkedWith_Class' => 'Добавление объектов %1$s для связи с объектом %2$s',
	'UI:ManageObjectsOf_Class_LinkedWith_Class_Instance' => 'Управление %1$s объектами связанными с %2$s: %3$s',
	'UI:AddLinkedObjectsOf_Class' => 'Добавить объект %1$s...',
	'UI:RemoveLinkedObjectsOf_Class' => 'Удалить выбранные объекты',
	'UI:Message:EmptyList:UseAdd' => 'Список пуст, используйте кнопку "Добавить ..." для добавления новых элементов.',
	'UI:Message:EmptyList:UseSearchForm' => 'Используйте форму поиска выше для поиска объектов, которые будут добавлены.',
	'UI:Wizard:FinalStepTitle' => 'Последний шаг: подтверждение',
	'UI:Title:DeletionOf_Object' => 'Удаление %1$s',
	'UI:Title:BulkDeletionOf_Count_ObjectsOf_Class' => 'Пакетное удаление %1$d объектов класса %2$s',
	'UI:Delete:NotAllowedToDelete' => 'Вы не можете удалить этот объект',
	'UI:Delete:NotAllowedToUpdate_Fields' => 'Вы не можете обновить следующее(ие) поле(я): %1$s',
	'UI:Error:ActionNotAllowed' => 'У вас недостаточно прав для выполнения это действия',
	'UI:Error:NotEnoughRightsToDelete' => 'Не удалось удалить этот объект, так как текущий пользователь не обладает необходимыми правами.',
	'UI:Error:CannotDeleteBecause' => 'Не удалось удалить этот объект: %1$s',
	'UI:Error:CannotDeleteBecauseOfDepencies' => 'Не удалось удалить этот объект, поскольку перед удалением необходимо выполнить некоторые операции вручную (в отношении зависимостей от объекта).',
	'UI:Error:CannotDeleteBecauseManualOpNeeded' => 'Не удалось удалить этот объект, поскольку перед удалением необходимо выполнить некоторые операции вручную.',
	'UI:Archive_User_OnBehalfOf_User' => '%1$s от имени %2$s',
	'UI:Delete:Deleted' => 'удален~~',
	'UI:Delete:AutomaticallyDeleted' => 'автоматически удалён',
	'UI:Delete:AutomaticResetOf_Fields' => 'автоматически сброшено поле(я): %1$s',
	'UI:Delete:CleaningUpRefencesTo_Object' => 'Очищенны все ссылки(связи?) на %1$s...',
	'UI:Delete:CleaningUpRefencesTo_Several_ObjectsOf_Class' => 'Очищенны все ссылки(связи?) на %1$d объектов класса %2$s...',
	'UI:Delete:Done+' => 'Что было сделано...',
	'UI:Delete:_Name_Class_Deleted' => '%1$s - %2$s удалено.',
	'UI:Delete:ConfirmDeletionOf_Name' => 'Удаление %1$s',
	'UI:Delete:ConfirmDeletionOf_Count_ObjectsOf_Class' => 'Удаление %1$d объектов класса %2$s',
	'UI:Delete:CannotDeleteBecause' => 'Could not be deleted: %1$s~~',
	'UI:Delete:ShouldBeDeletedAtomaticallyButNotPossible' => 'Should be automaticaly deleted, but this is not feasible: %1$s~~',
	'UI:Delete:MustBeDeletedManuallyButNotPossible' => 'Must be deleted manually, but this is not feasible: %1$s~~',
	'UI:Delete:WillBeDeletedAutomatically' => 'Будет удалено автоматически',
	'UI:Delete:MustBeDeletedManually' => 'Необходимо удалить вручную',
	'UI:Delete:CannotUpdateBecause_Issue' => 'Должно быть автоматически обновлено, но: %1$s',
	'UI:Delete:WillAutomaticallyUpdate_Fields' => 'Будет обновлено автоматически (сброс: %1$s)',
	'UI:Delete:Count_Objects/LinksReferencing_Object' => '%1$d объектов/связей ссылаются на %2$s.',
	'UI:Delete:Count_Objects/LinksReferencingTheObjects' => '%1$d объектов/связей ссылаются на удаляемые объекты.',
	'UI:Delete:ReferencesMustBeDeletedToEnsureIntegrity' => 'Для обеспечения целостности базы данных необходимо очистить все ссылки на удаляемые объекты.',
	'UI:Delete:Consequence+' => 'Влияние',
	'UI:Delete:SorryDeletionNotAllowed' => 'К сожалению, вы не можете удалить этот объект, см. подробное объяснение выше',
	'UI:Delete:PleaseDoTheManualOperations' => 'Необходимо выполнить указанные операции в ручную до удаления этого объекта',
	'UI:Delect:Confirm_Object' => 'Подтвердите удаление %1$s.',
	'UI:Delect:Confirm_Count_ObjectsOf_Class' => 'Подтвердите удаление %1$d объектов класса %2$s.',
	'UI:WelcomeToITop' => 'Добро пожаловать в iTop',
	'UI:DetailsPageTitle' => 'iTop - %1$s - %2$s подробности',
	'UI:ErrorPageTitle' => 'iTop - Ошибка',
	'UI:ObjectDoesNotExist' => 'Извните, этот объект не существует (или вы не можете его видеть).',
	'UI:ObjectArchived' => 'Этот объект заархивирован. Включите режим просмотра архива или обратитесь к администратору.',
	'Tag:Archived' => 'Архивный',
	'Tag:Archived+' => 'Доступен только в режиме архива',
	'Tag:Obsolete' => 'Устаревший',
	'Tag:Obsolete+' => 'Исключяется из результатов поиска и анализа влияния',
	'Tag:Synchronized' => 'Синхронизированный',
	'ObjectRef:Archived' => 'Архивный',
	'ObjectRef:Obsolete' => 'Устаревший',
	'UI:SearchResultsPageTitle' => 'iTop - Результаты поиска',
	'UI:SearchResultsTitle' => 'Результаты поиска',
	'UI:SearchResultsTitle+' => 'Результаты полнотекстового поиска',
	'UI:Search:NoSearch' => 'Ничего не найдено',
	'UI:Search:NeedleTooShort' => 'Строка поиска "%1$s" слишком короткая. Введите не менее %2$d символов.',
	'UI:Search:Ongoing' => 'Поиск "%1$s"',
	'UI:Search:Enlarge' => 'Расширить поиск',
	'UI:FullTextSearchTitle_Text' => 'Результаты для "%1$s":',
	'UI:Search:Count_ObjectsOf_Class_Found' => '%1$d объект(ы) класса %2$s найдено.',
	'UI:Search:NoObjectFound' => 'Объекты не найдены.',
	'UI:ModificationPageTitle_Object_Class' => 'iTop - Изменение объекта %2$s - %1$s',
	'UI:ModificationTitle_Class_Object' => 'Изменение объекта %1$s: <span class=\\"hilite\\">%2$s</span>',
	'UI:ClonePageTitle_Object_Class' => 'iTop - Клон %1$s - %2$s модификация',
	'UI:CloneTitle_Class_Object' => 'Клон %1$s: <span class=\\"hilite\\">%2$s</span>',
	'UI:CreationPageTitle_Class' => 'iTop - Создание объекта %1$s',
	'UI:CreationTitle_Class' => 'Создание объекта %1$s',
	'UI:SelectTheTypeOf_Class_ToCreate' => 'Выбор типа %1$s для создания:',
	'UI:Class_Object_NotUpdated' => 'Изменений не обнаружено, %1$s (%2$s) <strong>не</strong> был изменён.',
	'UI:Class_Object_Updated' => '%1$s (%2$s) обновлён.',
	'UI:BulkDeletePageTitle' => 'iTop - Пакетное удаление',
	'UI:BulkDeleteTitle' => 'Выбор объектов для удаления:',
	'UI:PageTitle:ObjectCreated' => 'iTop Объект создан.',
	'UI:Title:Object_Of_Class_Created' => '%1$s - %2$s создан.',
	'UI:Apply_Stimulus_On_Object_In_State_ToTarget_State' => 'Применение %1$s на объект: %2$s в состоянии %3$s для целевого класса: %4$s.',
	'UI:ObjectCouldNotBeWritten' => 'Объект не может быть записан: %1$s',
	'UI:PageTitle:FatalError' => 'iTop - Критическая ошибка',
	'UI:SystemIntrusion' => 'Доступ запрещён. Вы пытаетесь выполнить неразрешённую операцию.',
	'UI:FatalErrorMessage' => 'Критическая ошибка, iTop не может продолжать работу.',
	'UI:Error_Details' => 'Ошибка: %1$s.',

	'UI:PageTitle:ClassProjections' => 'iTop управление пользователями - проектирование классов',
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
	'UI:UserManagement:Action:Modify' => 'Изменить',
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

	'Menu:AdminTools' => 'Инструменты администратора', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AdminTools+' => 'Инструменты администратора', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AdminTools?' => 'Инструменты доступны только для пользователей, имеющих профиль администратора', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:SystemTools' => 'Система',

	'UI:ChangeManagementMenu' => 'Управление изменениями',
	'UI:ChangeManagementMenu+' => 'Управление изменениями',
	'UI:ChangeManagementMenu:Title' => 'Обзор изменений',
	'UI-ChangeManagementMenu-ChangesByType' => 'Изменения по типу',
	'UI-ChangeManagementMenu-ChangesByStatus' => 'Изменения по статутсу',
	'UI-ChangeManagementMenu-ChangesByWorkgroup' => 'Изменения по рабочей группе',
	'UI-ChangeManagementMenu-ChangesNotYetAssigned' => 'Не назначенные изменения',

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
	'UI-ServiceManagementOverview-CustomerContractToRenew' => 'Договоры с клиентами, которые будут обновлены в течении 30 дней',
	'UI-ServiceManagementOverview-ProviderContractToRenew' => 'Договоры с провайдерами, которые будут обновлены в течении 30 дней',

	'UI:ContactsMenu' => 'Договора',
	'UI:ContactsMenu+' => 'Договора',
	'UI:ContactsMenu:Title' => 'Обзор договоров',
	'UI-ContactsMenu-ContactsByLocation' => 'Договора по размещению',
	'UI-ContactsMenu-ContactsByType' => 'Договора по типу',
	'UI-ContactsMenu-ContactsByStatus' => 'Договора по статусу',

	'Menu:CSVImportMenu' => 'Импорт CSV', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:CSVImportMenu+' => 'Пакетное создание или обновление', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:DataModelMenu' => 'Модель данных', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataModelMenu+' => 'Обзор модели данных', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:ExportMenu' => 'Экспорт', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ExportMenu+' => 'Экспорт результатов любого запроса в HTML, CSV или XML', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:NotificationsMenu' => 'Уведомления', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:NotificationsMenu+' => 'Конфигурация уведомлений', // Duplicated into itop-welcome-itil (will be removed from here...)
	'UI:NotificationsMenu:Title' => 'Конфигурация <span class="hilite">Уведомлений</span>',
	'UI:NotificationsMenu:Help' => 'Помощь',
	'UI:NotificationsMenu:HelpContent' => '<p>В iTop полностью настраиваемые уведомления, которые основаны на двух наборах объектов: <i>триггерах и действиях</i>.</p>
<p><i><b>Триггеры</b></i> определяют, когда необходимо выполнить действия. Базовые триггеры доступны как часть ядра iTop, другие триггеры добавляются дополнительными расширениями:
<ol>
	<li>триггеры на создание/обновление/удаление объекта срабатывают при <b>создании</b>, <b>обновлении</b> или <b>удалении</b> объекта целевого класса;</li>
	<li>триггеры на изменение статуса срабатывают, когда объект целевого класса <b>входит</b> или <b>выходит</b> из указанного <b>статуса</b>;</li>
	<li>триггеры на пороговое значение срабатывают при <b>достижении порога</b> указанным секундомером <b>TTO</b> или <b>TTR</b>.</li>
</ol>
</p>
<p>
<i><b>Действия</b></i> определяют, что произойдет при срабатывании триггера. Базовое действие в iTop – <b>Уведомление по email</b>, дополнительные действия добавляются расширениями.
Действие <b>Уведомление по email</b> задаёт шаблон сообщения, который будет использоваться для отправки письма, а также другие параметры, такие как получатели, важность и т.д.
</p>
<p>Для тестирования и устранения неполадок в настройках почты доступна специальная страница: <a href="../setup/email.test.php" target="_blank">email.test.php</a>.</p>
<p>Для выполнения действия связываются с триггерами. При связывании с триггером каждому действию присваивается порядковый номер, который указывает на очерёдность выполнения действий при срабатывании триггера.</p>',
	'UI:NotificationsMenu:Triggers' => 'Триггеры',
	'UI:NotificationsMenu:AvailableTriggers' => 'Доступные триггеры',
	'UI:NotificationsMenu:OnCreate' => 'При создании объекта',
	'UI:NotificationsMenu:OnStateEnter' => 'При входе объекта в заданное состояние',
	'UI:NotificationsMenu:OnStateLeave' => 'При выходе объекта из заданного состояния',
	'UI:NotificationsMenu:Actions' => 'Действия',
	'UI:NotificationsMenu:AvailableActions' => 'Доступные действия',

	'Menu:TagAdminMenu' => 'Теги',
	'Menu:TagAdminMenu+' => 'Теги',
	'UI:TagAdminMenu:Title' => 'Настройка тегов',
	'UI:TagAdminMenu:NoTags' => 'Не настроены поля тегов',
	'UI:TagSetFieldData:Error' => 'Ошибка: %1$s',

	'Menu:AuditCategories' => 'Категории аудита', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AuditCategories+' => 'Категории аудита', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:Notifications:Title' => 'Категории аудита', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:RunQueriesMenu' => 'Выполнение запросов', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:RunQueriesMenu+' => 'Выполнение любых запросов', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:QueryMenu' => 'Книга запросов', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:QueryMenu+' => 'Книга запросов', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:DataAdministration' => 'Администрирование данных', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataAdministration+' => 'Администрирование данных', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UniversalSearchMenu' => 'Универсальный поиск', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UniversalSearchMenu+' => 'Поиск чего угодно...', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UserManagementMenu' => 'Управление пользователями', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserManagementMenu+' => 'Управление пользователями', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:ProfilesMenu' => 'Профили', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ProfilesMenu+' => 'Профили пользователей', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ProfilesMenu:Title' => 'Профили пользователей', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UserAccountsMenu' => 'Учетные записи', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserAccountsMenu+' => 'Учетные записи пользователей', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserAccountsMenu:Title' => 'Учетные записи пользователей', // Duplicated into itop-welcome-itil (will be removed from here...)	

	'UI:iTopVersion:Short' => '%1$s версия %2$s',
	'UI:iTopVersion:Long' => '%1$s версия %2$s-%3$s основан на %4$s',
	'UI:PropertiesTab' => 'Свойства',

	'UI:OpenDocumentInNewWindow_' => 'Открыть этот документ в новом окне: %1$s',
	'UI:DownloadDocument_' => 'Скачать этот документ: %1$s',
	'UI:Document:NoPreview' => 'Предварительный просмотр недоступен для документов данного типа',
	'UI:Download-CSV' => 'Загрузка %1$s~~',

	'UI:DeadlineMissedBy_duration' => 'Пропущен %1$s',
	'UI:Deadline_LessThan1Min' => '< 1 мин',
	'UI:Deadline_Minutes' => '%1$d мин',
	'UI:Deadline_Hours_Minutes' => '%1$d ч %2$d мин',
	'UI:Deadline_Days_Hours_Minutes' => '%1$d д %2$d ч %3$d мин',
	'UI:Help' => 'Помощь',
	'UI:PasswordConfirm' => '(Подтвердить)',
	'UI:BeforeAdding_Class_ObjectsSaveThisObject' => 'Перед добавлением объекта %1$s сохраните текущий объект.',
	'UI:DisplayThisMessageAtStartup' => 'Показать это сообщение при запуске',
	'UI:RelationshipGraph' => 'Графический вид',
	'UI:RelationshipList' => 'Список',
	'UI:RelationGroups' => 'Группы',
	'UI:OperationCancelled' => 'Операция отменена',
	'UI:ElementsDisplayed' => 'Фильтр',
	'UI:RelationGroupNumber_N' => 'Группа #%1$d',
	'UI:Relation:ExportAsPDF' => 'Экспорт в PDF...',
	'UI:RelationOption:GroupingThreshold' => 'Порог группировки',
	'UI:Relation:AdditionalContextInfo' => 'Дополнительная контекстная информация',
	'UI:Relation:NoneSelected' => 'None',
	'UI:Relation:Zoom' => 'Масштаб',
	'UI:Relation:ExportAsAttachment' => 'Export as Attachment...',
	'UI:Relation:DrillDown' => 'Подробнее...',
	'UI:Relation:PDFExportOptions' => 'Параметры экспорта в PDF',
	'UI:Relation:AttachmentExportOptions_Name' => 'Options for Attachment to %1$s',
	'UI:RelationOption:Untitled' => 'Untitled',
	'UI:Relation:Key' => 'Key',
	'UI:Relation:Comments' => 'Comments',
	'UI:RelationOption:Title' => 'Заголовок',
	'UI:RelationOption:IncludeList' => 'Включить перечень объектов',
	'UI:RelationOption:Comments' => 'Комментарии',
	'UI:Button:Export' => 'Экспорт',
	'UI:Relation:PDFExportPageFormat' => 'Формат страницы',
	'UI:PageFormat_A3' => 'A3',
	'UI:PageFormat_A4' => 'A4',
	'UI:PageFormat_Letter' => 'Letter',
	'UI:Relation:PDFExportPageOrientation' => 'Ориентация страницы',
	'UI:PageOrientation_Portrait' => 'Портретная',
	'UI:PageOrientation_Landscape' => 'Альбомная',
	'UI:RelationTooltip:Redundancy' => 'Избыточность',
	'UI:RelationTooltip:ImpactedItems_N_of_M' => 'Кол-во затронутых элементов: %1$d / %2$d',
	'UI:RelationTooltip:CriticalThreshold_N_of_M' => 'Критический порог: %1$d / %2$d',
	'Portal:Title' => 'Пользовательский портал iTop',
	'Portal:NoRequestMgmt' => 'Уважаемый %1$s, вы были перенаправлены на потрал, потому что ваш аккаунт содержит профиль \'Portal user\'. К сожалению, iTop не содержит модуля \'Request Management\'. Пожалуйста, свяжитесь с вашим администратором.',
	'Portal:Refresh' => 'Обновить',
	'Portal:Back' => 'Назад',
	'Portal:WelcomeUserOrg' => 'Добро пожаловать, %1$s (%2$s)',
	'Portal:TitleDetailsFor_Request' => 'Подробности запроса',
	'Portal:ShowOngoing' => 'Показать открытые запросы',
	'Portal:ShowClosed' => 'Показать закрытые запросы',
	'Portal:CreateNewRequest' => 'Создать новый запрос',
	'Portal:CreateNewRequestItil' => 'Создать новый запрос',
	'Portal:CreateNewIncidentItil' => 'Создать новый инцидент',
	'Portal:ChangeMyPassword' => 'Изменить пароль',
	'Portal:Disconnect' => 'Выйти',
	'Portal:OpenRequests' => 'Мои открытые запросы',
	'Portal:ClosedRequests' => 'Мои закрытые запросы',
	'Portal:ResolvedRequests' => 'Мои решённые запросы',
	'Portal:SelectService' => 'Выберите услугу из каталога:',
	'Portal:PleaseSelectOneService' => 'Пожалуйста, выберите услугу для создания запроса',
	'Portal:SelectSubcategoryFrom_Service' => 'Выберите подкатегорию услуги %1$s:',
	'Portal:PleaseSelectAServiceSubCategory' => 'Пожалуйста, выберите подкатегорию услуги для создания запроса',
	'Portal:DescriptionOfTheRequest' => 'Введите описание запроса:',
	'Portal:TitleRequestDetailsFor_Request' => 'Подробности запроса %1$s:',
	'Portal:NoOpenRequest' => 'Нет запросов в этой категории.',
	'Portal:NoClosedRequest' => 'Нет запросов в этой категории',
	'Portal:Button:ReopenTicket' => 'Вновь открыть запрос',
	'Portal:Button:CloseTicket' => 'Закрыть запрос',
	'Portal:Button:UpdateRequest' => 'Обновить запрос',
	'Portal:EnterYourCommentsOnTicket' => 'Введите ваши комментарии по решению этого запроса:',
	'Portal:ErrorNoContactForThisUser' => 'Ошибка: текущий пользователь не ассоциирован с Контактом/Персоной. Пожалуйста, свяжитесь с вашим администратором.',
	'Portal:Attachments' => 'Вложения',
	'Portal:AddAttachment' => 'Добавить вложения',
	'Portal:RemoveAttachment' => ' Удалить вложения',
	'Portal:Attachment_No_To_Ticket_Name' => 'Вложение #%1$d to %2$s (%3$s)~~',
	'Portal:SelectRequestTemplate' => 'Select a template for %1$s~~',
	'Enum:Undefined' => 'Неопределён',
	'UI:DurationForm_Days_Hours_Minutes_Seconds' => '%1$s д %2$s ч %3$s мин %4$s с',
	'UI:ModifyAllPageTitle' => 'Изменить все',
	'UI:Modify_N_ObjectsOf_Class' => 'Изменение %1$d объектов класса %2$s~~',
	'UI:Modify_M_ObjectsOf_Class_OutOf_N' => 'Изменение %1$d объектов класса %2$s из %3$d~~',
	'UI:Menu:ModifyAll' => 'Изменить...',
	'UI:Button:ModifyAll' => 'Изменить все',
	'UI:Button:PreviewModifications' => 'Предпросмотр изменений >>',
	'UI:ModifiedObject' => 'Объект изменен',
	'UI:BulkModifyStatus' => 'Операция',
	'UI:BulkModifyStatus+' => 'Статус операции',
	'UI:BulkModifyErrors' => 'Ошибки (если есть)',
	'UI:BulkModifyErrors+' => 'Ошибки, препятствующие изменению',
	'UI:BulkModifyStatusOk' => 'Ok',
	'UI:BulkModifyStatusError' => 'Ошибка',
	'UI:BulkModifyStatusModified' => 'Изменен',
	'UI:BulkModifyStatusSkipped' => 'Пропущен',
	'UI:BulkModify_Count_DistinctValues' => '%1$d distinct values:~~',
	'UI:BulkModify:Value_Exists_N_Times' => '%1$s, %2$d time(s)~~',
	'UI:BulkModify:N_MoreValues' => '%1$d more values...~~',
	'UI:AttemptingToSetAReadOnlyAttribute_Name' => 'Attempting to set the read-only field: %1$s~~',
	'UI:FailedToApplyStimuli' => 'Операция не может быть выполнена.',
	'UI:StimulusModify_N_ObjectsOf_Class' => '%1$s: Modifying %2$d objects of class %3$s~~',
	'UI:CaseLogTypeYourTextHere' => 'Введите свой текст:',
	'UI:CaseLog:Header_Date_UserName' => '%1$s - %2$s:~~',
	'UI:CaseLog:InitialValue' => 'Initial value:~~',
	'UI:AttemptingToSetASlaveAttribute_Name' => 'The field %1$s is not writable because it is mastered by the data synchronization. Value not set.~~',
	'UI:ActionNotAllowed' => 'You are not allowed to perform this action on these objects.~~',
	'UI:BulkAction:NoObjectSelected' => 'Please select at least one object to perform this operation~~',
	'UI:AttemptingToChangeASlaveAttribute_Name' => 'The field %1$s is not writable because it is mastered by the data synchronization. Value remains unchanged.~~',
	'UI:Pagination:HeaderSelection' => 'Всего: %1$s элементов (%2$s элементов выделено).',
	'UI:Pagination:HeaderNoSelection' => 'Всего: %1$s элементов',
	'UI:Pagination:PageSize' => '%1$s объектов на страницу',
	'UI:Pagination:PagesLabel' => 'Страницы:~~',
	'UI:Pagination:All' => 'Все',
	'UI:HierarchyOf_Class' => 'Иерархия по: %1$s~~',
	'UI:Preferences' => 'Предпочтения...',
	'UI:ArchiveModeOn' => 'Activate archive mode~~',
	'UI:ArchiveModeOff' => 'Deactivate archive mode~~',
	'UI:ArchiveMode:Banner' => 'Archive mode~~',
	'UI:ArchiveMode:Banner+' => 'Archived objects are visible, and no modification is allowed~~',
	'UI:FavoriteOrganizations' => 'Избранные организации',
	'UI:FavoriteOrganizations+' => 'Отметьте в списке ниже организации, которые вы хотите видеть в раскрывающемся списке бокового меню для быстрого доступа. Обратите внимание, что это не параметр безопасности, объекты из любой организации по-прежнему видны и могут быть доступны, выбрав "Все организации" в раскрывающемся списке.',
	'UI:FavoriteLanguage' => 'Язык пользовательского интерфейса',
	'UI:Favorites:SelectYourLanguage' => 'Выберите Ваш язык',
	'UI:FavoriteOtherSettings' => 'Другие настройки',
	'UI:Favorites:Default_X_ItemsPerPage' => 'Длина списка по умолчанию: %1$s элементов на страницу.',
	'UI:Favorites:ShowObsoleteData' => 'Показывать устаревшие данные',
	'UI:Favorites:ShowObsoleteData+' => 'Отображение устаревших данных в результатах поиска и списках элементов для выбора',
	'UI:NavigateAwayConfirmationMessage' => 'Все изменения будут отменены.',
	'UI:CancelConfirmationMessage' => 'Настройки НЕ будут сохранены. Продолжить?',
	'UI:AutoApplyConfirmationMessage' => 'Некоторые изменения не вступили в силу. Применить их немедленно?',
	'UI:Create_Class_InState' => 'Create the %1$s in state: ~~',
	'UI:OrderByHint_Values' => 'Sort order: %1$s~~',
	'UI:Menu:AddToDashboard' => 'Добавить на дашборд...',
	'UI:Button:Refresh' => 'Обновить',
	'UI:Button:GoPrint' => 'Печать...',
	'UI:ExplainPrintable' => 'Щелкните значок %1$s, чтобы скрыть элементы от печати.<br/>Используйте функцию "печать" вашего браузера для предварительного просмотра перед печатью.<br/>Примечание: этот заголовок и другие элементы управления не будут напечатаны.',
	'UI:PrintResolution:FullSize' => 'Полный размер',
	'UI:PrintResolution:A4Portrait' => 'A4 (портрет)',
	'UI:PrintResolution:A4Landscape' => 'A4 (альбом)',
	'UI:PrintResolution:LetterPortrait' => 'Письмо (портрет)',
	'UI:PrintResolution:LetterLandscape' => 'Письмо (альбом)',
	'UI:Toggle:StandardDashboard' => 'Стандартный',
	'UI:Toggle:CustomDashboard' => 'Пользовательский',

	'UI:ConfigureThisList' => 'Настроить список...',
	'UI:ListConfigurationTitle' => 'Настройка списка',
	'UI:ColumnsAndSortOrder' => 'Колонки и порядок сортировки:',
	'UI:UseDefaultSettings' => 'Использовать настройки по умолчанию',
	'UI:UseSpecificSettings' => 'Использовать эти настройки:',
	'UI:Display_X_ItemsPerPage' => 'Показывать %1$s элементов на странице',
	'UI:UseSavetheSettings' => 'Сохранить настройки',
	'UI:OnlyForThisList' => 'Только для текущего списка',
	'UI:ForAllLists' => 'Для всех списков',
	'UI:ExtKey_AsLink' => '%1$s (Link)~~',
	'UI:ExtKey_AsFriendlyName' => '%1$s (Friendly Name)~~',
	'UI:ExtField_AsRemoteField' => '%1$s (%2$s)~~',
	'UI:Button:MoveUp' => 'Вверх',
	'UI:Button:MoveDown' => 'Вниз',

	'UI:OQL:UnknownClassAndFix' => 'Unknown class "%1$s". You may try "%2$s" instead.~~',
	'UI:OQL:UnknownClassNoFix' => 'Unknown class "%1$s"~~',

	'UI:Dashboard:Edit' => 'Редактировать дашборд...',
	'UI:Dashboard:Revert' => 'Вернуть оригинальную версию...',
	'UI:Dashboard:RevertConfirm' => 'Будет возвращена оригинальная версия дашборда. Все изменения будут утеряны. Хотите продолжить?',
	'UI:ExportDashBoard' => 'Экспорт',
	'UI:ImportDashBoard' => 'Импорт',
	'UI:ImportDashboardTitle' => 'Импорт из файла',
	'UI:ImportDashboardText' => 'Выберите файл дашборда для импорта:',


	'UI:DashletCreation:Title' => 'Создать новый дашлет',
	'UI:DashletCreation:Dashboard' => 'Добавить на дашборд',
	'UI:DashletCreation:DashletType' => 'Тип дашлета',
	'UI:DashletCreation:EditNow' => 'Перейти в редактор дашборда',

	'UI:DashboardEdit:Title' => 'Редактор дашборда',
	'UI:DashboardEdit:DashboardTitle' => 'Заголовок',
	'UI:DashboardEdit:AutoReload' => 'Обновлять автоматически',
	'UI:DashboardEdit:AutoReloadSec' => 'Интервал обновления (секунды)',
	'UI:DashboardEdit:AutoReloadSec+' => 'Минимальный интервал %1$d секунд',

	'UI:DashboardEdit:Layout' => 'Макет',
	'UI:DashboardEdit:Properties' => 'Свойства дашборда',
	'UI:DashboardEdit:Dashlets' => 'Доступные дашлеты',
	'UI:DashboardEdit:DashletProperties' => 'Свойства дашлета',

	'UI:Form:Property' => 'Свойство',
	'UI:Form:Value' => 'Значение',

	'UI:DashletUnknown:Label' => 'Unknown~~',
	'UI:DashletUnknown:Description' => 'Unknown dashlet (might have been uninstalled)~~',
	'UI:DashletUnknown:RenderText:View' => 'Unable to render this dashlet.~~',
	'UI:DashletUnknown:RenderText:Edit' => 'Unable to render this dashlet (class "%1$s"). Check with your administrator if it is still available.~~',
	'UI:DashletUnknown:RenderNoDataText:Edit' => 'No preview available for this dashlet (class "%1$s").~~',
	'UI:DashletUnknown:Prop-XMLConfiguration' => 'Configuration (shown as raw XML)~~',

	'UI:DashletProxy:Label' => 'Proxy~~',
	'UI:DashletProxy:Description' => 'Proxy dashlet~~',
	'UI:DashletProxy:RenderNoDataText:Edit' => 'No preview available for this third-party dashlet (class "%1$s").~~',
	'UI:DashletProxy:Prop-XMLConfiguration' => 'Configuration (shown as raw XML)~~',

	'UI:DashletPlainText:Label' => 'Текст',
	'UI:DashletPlainText:Description' => 'Текст (без форматирования)',
	'UI:DashletPlainText:Prop-Text' => 'Текст',
	'UI:DashletPlainText:Prop-Text:Default' => 'Введите текст...',

	'UI:DashletObjectList:Label' => 'Список объектов',
	'UI:DashletObjectList:Description' => 'Список объектов',
	'UI:DashletObjectList:Prop-Title' => 'Заголовок',
	'UI:DashletObjectList:Prop-Query' => 'Запрос',
	'UI:DashletObjectList:Prop-Menu' => 'Меню',

	'UI:DashletGroupBy:Prop-Title' => 'Заголовок',
	'UI:DashletGroupBy:Prop-Query' => 'Запрос',
	'UI:DashletGroupBy:Prop-Style' => 'Стиль',
	'UI:DashletGroupBy:Prop-GroupBy' => 'Группировка',
	'UI:DashletGroupBy:Prop-GroupBy:Hour' => 'Hour of %1$s (0-23)~~',
	'UI:DashletGroupBy:Prop-GroupBy:Month' => 'Month of %1$s (1 - 12)~~',
	'UI:DashletGroupBy:Prop-GroupBy:DayOfWeek' => 'Day of week for %1$s~~',
	'UI:DashletGroupBy:Prop-GroupBy:DayOfMonth' => 'Day of month for %1$s~~',
	'UI:DashletGroupBy:Prop-GroupBy:Select-Hour' => '%1$s (hour)~~',
	'UI:DashletGroupBy:Prop-GroupBy:Select-Month' => '%1$s (month)~~',
	'UI:DashletGroupBy:Prop-GroupBy:Select-DayOfWeek' => '%1$s (day of week)~~',
	'UI:DashletGroupBy:Prop-GroupBy:Select-DayOfMonth' => '%1$s (day of month)~~',
	'UI:DashletGroupBy:MissingGroupBy' => 'Пожалуйста, выберите поле по которому объекты будут сгруппированы',

	'UI:DashletGroupByPie:Label' => 'Круговая диаграмма',
	'UI:DashletGroupByPie:Description' => 'Круговая диаграмма',
	'UI:DashletGroupByBars:Label' => 'Столбчатая диаграмма',
	'UI:DashletGroupByBars:Description' => 'Столбчатая диаграмма',
	'UI:DashletGroupByTable:Label' => 'Группировка (таблица)',
	'UI:DashletGroupByTable:Description' => 'Список (сгруппированный по полю)',

	// New in 2.5
	'UI:DashletGroupBy:Prop-Function' => 'Функция агрегирования',
	'UI:DashletGroupBy:Prop-FunctionAttribute' => 'Атрибут функции',
	'UI:DashletGroupBy:Prop-OrderDirection' => 'Направление',
	'UI:DashletGroupBy:Prop-OrderField' => 'Сортировать по',
	'UI:DashletGroupBy:Prop-Limit' => 'Лимит',

	'UI:DashletGroupBy:Order:asc' => 'По возрастанию',
	'UI:DashletGroupBy:Order:desc' => 'По убыванию',

	'UI:GroupBy:count' => 'Количество',
	'UI:GroupBy:count+' => 'Число элементов',
	'UI:GroupBy:sum' => 'Сумма',
	'UI:GroupBy:sum+' => 'Sum of %1$s',
	'UI:GroupBy:avg' => 'Среднее',
	'UI:GroupBy:avg+' => 'Average of %1$s',
	'UI:GroupBy:min' => 'Минимум',
	'UI:GroupBy:min+' => 'Minimum of %1$s',
	'UI:GroupBy:max' => 'Максимум',
	'UI:GroupBy:max+' => 'Maximum of %1$s',
	// ---

	'UI:DashletHeaderStatic:Label' => 'Заголовок',
	'UI:DashletHeaderStatic:Description' => 'Displays an horizontal separator~~',
	'UI:DashletHeaderStatic:Prop-Title' => 'Заголовок',
	'UI:DashletHeaderStatic:Prop-Title:Default' => 'Контакты',
	'UI:DashletHeaderStatic:Prop-Icon' => 'Иконка',

	'UI:DashletHeaderDynamic:Label' => 'Заголовок со статистикой',
	'UI:DashletHeaderDynamic:Description' => 'Заголовок со статистикой (группировать по ...)',
	'UI:DashletHeaderDynamic:Prop-Title' => 'Заголовок',
	'UI:DashletHeaderDynamic:Prop-Title:Default' => 'Контакты',
	'UI:DashletHeaderDynamic:Prop-Icon' => 'Иконка',
	'UI:DashletHeaderDynamic:Prop-Subtitle' => 'Подзаголовок',
	'UI:DashletHeaderDynamic:Prop-Subtitle:Default' => 'Контакты',
	'UI:DashletHeaderDynamic:Prop-Query' => 'Запрос',
	'UI:DashletHeaderDynamic:Prop-GroupBy' => 'Группировать по',
	'UI:DashletHeaderDynamic:Prop-Values' => 'Значения',

	'UI:DashletBadge:Label' => 'Значок',
	'UI:DashletBadge:Description' => 'Иконка объекта с возможностью создания и поиска',
	'UI:DashletBadge:Prop-Class' => 'Класс',

	'DayOfWeek-Sunday' => 'Воскресенье',
	'DayOfWeek-Monday' => 'Понедельник',
	'DayOfWeek-Tuesday' => 'Вторник',
	'DayOfWeek-Wednesday' => 'Среда',
	'DayOfWeek-Thursday' => 'Четверг',
	'DayOfWeek-Friday' => 'Пятница',
	'DayOfWeek-Saturday' => 'Суббота',
	'Month-01' => 'Январь',
	'Month-02' => 'Февраль',
	'Month-03' => 'Март',
	'Month-04' => 'Апрель',
	'Month-05' => 'Май',
	'Month-06' => 'Июнь',
	'Month-07' => 'Июль',
	'Month-08' => 'Август',
	'Month-09' => 'Сентябрь',
	'Month-10' => 'Октябрь',
	'Month-11' => 'Ноябрь',
	'Month-12' => 'Декабрь',

	// Short version for the DatePicker
	'DayOfWeek-Sunday-Min' => 'Вс',
	'DayOfWeek-Monday-Min' => 'Пн',
	'DayOfWeek-Tuesday-Min' => 'Вт',
	'DayOfWeek-Wednesday-Min' => 'Ср',
	'DayOfWeek-Thursday-Min' => 'Чт',
	'DayOfWeek-Friday-Min' => 'Пт',
	'DayOfWeek-Saturday-Min' => 'Сб',
	'Month-01-Short' => 'Янв.',
	'Month-02-Short' => 'Фев.',
	'Month-03-Short' => 'Мар.',
	'Month-04-Short' => 'Апр.',
	'Month-05-Short' => 'Май',
	'Month-06-Short' => 'Июн.',
	'Month-07-Short' => 'Июл.',
	'Month-08-Short' => 'Авг.',
	'Month-09-Short' => 'Сен.',
	'Month-10-Short' => 'Окт.',
	'Month-11-Short' => 'Ноя.',
	'Month-12-Short' => 'Дек.',
	'Calendar-FirstDayOfWeek' => '1', // 0 = Sunday, 1 = Monday, etc...

	'UI:Menu:ShortcutList' => 'Добавить в избранное...',
	'UI:ShortcutRenameDlg:Title' => 'Переименовать ссылку',
	'UI:ShortcutListDlg:Title' => 'Добавить в избранное ссылку на список',
	'UI:ShortcutDelete:Confirm' => 'Подтвердите удаление ссылки (ссылок).',
	'Menu:MyShortcuts' => 'Избранное', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Class:Shortcut' => 'Ссылка',
	'Class:Shortcut+' => '',
	'Class:Shortcut/Attribute:name' => 'Название',
	'Class:Shortcut/Attribute:name+' => 'Используется в меню и заголовке страницы',
	'Class:ShortcutOQL' => 'Search result shortcut~~',
	'Class:ShortcutOQL+' => '',
	'Class:ShortcutOQL/Attribute:oql' => 'Запрос',
	'Class:ShortcutOQL/Attribute:oql+' => 'OQL defining the list of objects to search for',
	'Class:ShortcutOQL/Attribute:auto_reload' => 'Обновлять автоматически',
	'Class:ShortcutOQL/Attribute:auto_reload/Value:none' => 'Disabled',
	'Class:ShortcutOQL/Attribute:auto_reload/Value:custom' => 'Custom rate',
	'Class:ShortcutOQL/Attribute:auto_reload_sec' => 'Интервал обновления (секунды)',
	'Class:ShortcutOQL/Attribute:auto_reload_sec/tip' => 'Минимальное значение %1$d секунд(ы)',

	'UI:FillAllMandatoryFields' => 'Пожалуйста, заполните все обязательные поля.',
	'UI:ValueMustBeSet' => 'Пожалуйста, укажите значение',
	'UI:ValueMustBeChanged' => 'Пожалуйста, измените значение',
	'UI:ValueInvalidFormat' => 'Недопустимый формат',

	'UI:CSVImportConfirmTitle' => 'Please confirm the operation',
	'UI:CSVImportConfirmMessage' => 'Are you sure you want to do this?',
	'UI:CSVImportError_items' => 'Errors: %1$d',
	'UI:CSVImportCreated_items' => 'Created: %1$d',
	'UI:CSVImportModified_items' => 'Modified: %1$d',
	'UI:CSVImportUnchanged_items' => 'Unchanged: %1$d',
	'UI:CSVImport:DateAndTimeFormats' => 'Date and time format',
	'UI:CSVImport:DefaultDateTimeFormat_Format_Example' => 'Default format: %1$s (e.g. %2$s)',
	'UI:CSVImport:CustomDateTimeFormat' => 'Custom format: %1$s',
	'UI:CSVImport:CustomDateTimeFormatTooltip' => 'Available placeholders:<table>
<tr><td>Y</td><td>year (4 digits, e.g. 2016)</td></tr>
<tr><td>y</td><td>year (2 digits, e.g. 16 for 2016)</td></tr>
<tr><td>m</td><td>month (2 digits, e.g. 01..12)</td></tr>
<tr><td>n</td><td>month (1 or 2 digits no leading zero, e.g. 1..12)</td></tr>
<tr><td>d</td><td>day (2 digits, e.g. 01..31)</td></tr>
<tr><td>j</td><td>day (1 or 2 digits no leading zero, e.g. 1..31)</td></tr>
<tr><td>H</td><td>hour (24 hour, 2 digits, e.g. 00..23)</td></tr>
<tr><td>h</td><td>hour (12 hour, 2 digits, e.g. 01..12)</td></tr>
<tr><td>G</td><td>hour (24 hour, 1 or 2 digits no leading zero, e.g. 0..23)</td></tr>
<tr><td>g</td><td>hour (12 hour, 1 or 2 digits no leading zero, e.g. 1..12)</td></tr>
<tr><td>a</td><td>hour, am or pm (lowercase)</td></tr>
<tr><td>A</td><td>hour, AM or PM (uppercase)</td></tr>
<tr><td>i</td><td>minutes (2 digits, e.g. 00..59)</td></tr>
<tr><td>s</td><td>seconds (2 digits, e.g. 00..59)</td></tr>
</table>',

	'UI:Button:Remove' => 'Удалить',
	'UI:AddAnExisting_Class' => 'Добавить объекты класса %1$s...',
	'UI:SelectionOf_Class' => 'Выбор объектов класса %1$s',

	'UI:AboutBox' => 'Об этом iTop...',
	'UI:About:Title' => 'Об этом iTop',
	'UI:About:DataModel' => 'Модель данных',
	'UI:About:Support' => 'Информация для технической поддержки',
	'UI:About:Licenses' => 'Лицензии',
	'UI:About:InstallationOptions' => 'Параметр установки',
	'UI:About:ManualExtensionSource' => 'Расширение',
	'UI:About:Extension_Version' => 'Версия: %1$s',
	'UI:About:RemoteExtensionSource' => 'Data~~',

	'UI:DisconnectedDlgMessage' => 'Вы отключены. Вы должны идентифицировать себя для продолжения использования приложения.',
	'UI:DisconnectedDlgTitle' => 'Внимание!',
	'UI:LoginAgain' => 'Войти снова',
	'UI:StayOnThePage' => 'Остаться на этой странице',

	'ExcelExporter:ExportMenu' => 'Экспорт в Excel...',
	'ExcelExporter:ExportDialogTitle' => 'Экспорт в Excel',
	'ExcelExporter:ExportButton' => 'Экспорт',
	'ExcelExporter:DownloadButton' => 'Загрузить %1$s',
	'ExcelExporter:RetrievingData' => 'Извлечение данных...',
	'ExcelExporter:BuildingExcelFile' => 'Формирование файла Excel...',
	'ExcelExporter:Done' => 'Готово',
	'ExcelExport:AutoDownload' => 'Начать загрузку файла автоматически по готовности',
	'ExcelExport:PreparingExport' => 'Подготовка к экспорту...',
	'ExcelExport:Statistics' => 'Статистика',
	'portal:legacy_portal' => 'Пользовательский портал',
	'portal:backoffice' => 'iTop Back-Office интерфейс',

	'UI:CurrentObjectIsLockedBy_User' => 'Объект заблокирован, поскольку в настоящее время редактируется пользователем %1$s.',
	'UI:CurrentObjectIsLockedBy_User_Explanation' => 'Объект в настоящее время редактируется пользователем %1$s. Ваши изменения не будут сохранены, поскольку они могут быть перезаписаны.',
	'UI:CurrentObjectLockExpired' => 'Срок блокировки для предотвращения одновременного изменения объекта истек.',
	'UI:CurrentObjectLockExpired_Explanation' => 'Срок блокировки для предотвращения одновременного изменения объекта истек. Вы больше не можете сохранить свои изменения, поскольку другим пользователям теперь разрешено изменять данный объект.',
	'UI:ConcurrentLockKilled' => 'Блокировка для предотвращения изменений текущего объекта снята.',
	'UI:Menu:KillConcurrentLock' => 'Снять блокировку одноврем. измен.!',

	'UI:Menu:ExportPDF' => 'Экспорт в PDF...',
	'UI:Menu:PrintableVersion' => 'Версия для печати',

	'UI:BrowseInlineImages' => 'Обзор...',
	'UI:UploadInlineImageLegend' => 'Загрузить новое изображение',
	'UI:SelectInlineImageToUpload' => 'Выберите изображение для загрузки',
	'UI:AvailableInlineImagesLegend' => 'Доступные изображения',
	'UI:NoInlineImage' => 'На сервере нет доступных изображений. С помощью кнопки "Обзор..." выше выберите изображение на вашем компьютере, чтобы загрузить его на сервер.',

	'UI:ToggleFullScreen' => 'Развернуть / Свернуть',
	'UI:Button:ResetImage' => 'Восстановить предыдущее изображение',
	'UI:Button:RemoveImage' => 'Удалить изображение',
	'UI:UploadNotSupportedInThisMode' => 'Изменение изображений и файлов не поддерживается в этом режиме.',

	'UI:Button:RemoveDocument' => 'Remove the document~~',

	// Search form
	'UI:Search:Toggle' => 'Развернуть / Свернуть',
	'UI:Search:AutoSubmit:DisabledHint' => 'Автоматический запуск поиска отключен для данного класса',
	'UI:Search:Obsolescence:DisabledHint' => '<span class="fas fa-eye-slash fa-1x"></span> Устаревшие данные скрыты в соответствии с вашими предпочтениями',
	'UI:Search:NoAutoSubmit:ExplainText' => 'Добавьте критерии поиска или нажмите кнопку поиска, чтобы просмотреть объекты.',
	'UI:Search:Criterion:MoreMenu:AddCriteria' => 'Добавить критерий',
	// - Add new criteria button
	'UI:Search:AddCriteria:List:RecentlyUsed:Title' => 'Недавние',
	'UI:Search:AddCriteria:List:MostPopular:Title' => 'Популярные',
	'UI:Search:AddCriteria:List:Others:Title' => 'Остальные',
	'UI:Search:AddCriteria:List:RecentlyUsed:Placeholder' => 'Пока нет',

	// - Criteria titles
	//   - Default widget
	'UI:Search:Criteria:Title:Default:Any' => '%1$s: все',
	'UI:Search:Criteria:Title:Default:Empty' => '%1$s пусто',
	'UI:Search:Criteria:Title:Default:NotEmpty' => '%1$s не пусто',
	'UI:Search:Criteria:Title:Default:Equals' => '%1$s равно %2$s',
	'UI:Search:Criteria:Title:Default:Contains' => '%1$s содержит %2$s',
	'UI:Search:Criteria:Title:Default:StartsWith' => '%1$s начинается с %2$s',
	'UI:Search:Criteria:Title:Default:EndsWith' => '%1$s заканчивается на %2$s',
	'UI:Search:Criteria:Title:Default:RegExp' => '%1$s соответствует %2$s',
	'UI:Search:Criteria:Title:Default:GreaterThan' => '%1$s > %2$s',
	'UI:Search:Criteria:Title:Default:GreaterThanOrEquals' => '%1$s >= %2$s',
	'UI:Search:Criteria:Title:Default:LessThan' => '%1$s < %2$s',
	'UI:Search:Criteria:Title:Default:LessThanOrEquals' => '%1$s <= %2$s',
	'UI:Search:Criteria:Title:Default:Different' => '%1$s ≠ %2$s',
	'UI:Search:Criteria:Title:Default:Between' => '%1$s между [%2$s]',
	'UI:Search:Criteria:Title:Default:BetweenDates' => '%1$s [%2$s]',
	'UI:Search:Criteria:Title:Default:BetweenDates:All' => '%1$s: все',
	'UI:Search:Criteria:Title:Default:BetweenDates:From' => '%1$s с %2$s',
	'UI:Search:Criteria:Title:Default:BetweenDates:Until' => '%1$s по %2$s',
	'UI:Search:Criteria:Title:Default:Between:All' => '%1$s: все',
	'UI:Search:Criteria:Title:Default:Between:From' => '%1$s от %2$s',
	'UI:Search:Criteria:Title:Default:Between:Until' => '%1$s до %2$s',
	//   - Numeric widget
	//   None yet
	//   - DateTime widget
	'UI:Search:Criteria:Title:DateTime:Between' => '%2$s <= 1$s <= %3$s',
	//   - Enum widget
	'UI:Search:Criteria:Title:Enum:In' => '%1$s: %2$s',
	'UI:Search:Criteria:Title:Enum:In:Many' => '%1$s: %2$s и %3$s других',
	'UI:Search:Criteria:Title:Enum:In:All' => '%1$s: все',
	//   - TagSet widget
	'UI:Search:Criteria:Title:TagSet:Matches' => '%1$s: %2$s',
	//   - External key widget
	'UI:Search:Criteria:Title:ExternalKey:Empty' => '%1$s определён',
	'UI:Search:Criteria:Title:ExternalKey:NotEmpty' => '%1$s неопределён',
	'UI:Search:Criteria:Title:ExternalKey:Equals' => '%1$s %2$s',
	'UI:Search:Criteria:Title:ExternalKey:In' => '%1$s: %2$s',
	'UI:Search:Criteria:Title:ExternalKey:In:Many' => '%1$s: %2$s и %3$s других',
	'UI:Search:Criteria:Title:ExternalKey:In:All' => '%1$s: все',
	//   - Hierarchical key widget
	'UI:Search:Criteria:Title:HierarchicalKey:Empty' => '%1$s определён',
	'UI:Search:Criteria:Title:HierarchicalKey:NotEmpty' => '%1$s неопределён',
	'UI:Search:Criteria:Title:HierarchicalKey:Equals' => '%1$s %2$s',
	'UI:Search:Criteria:Title:HierarchicalKey:In' => '%1$s: %2$s',
	'UI:Search:Criteria:Title:HierarchicalKey:In:Many' => '%1$s: %2$s и %3$s других',
	'UI:Search:Criteria:Title:HierarchicalKey:In:All' => '%1$s: все',

	// - Criteria operators
	//   - Default widget
	'UI:Search:Criteria:Operator:Default:Empty' => 'Пусто',
	'UI:Search:Criteria:Operator:Default:NotEmpty' => 'Не пусто',
	'UI:Search:Criteria:Operator:Default:Equals' => 'Равно',
	'UI:Search:Criteria:Operator:Default:Between' => 'Между',
	//   - String widget
	'UI:Search:Criteria:Operator:String:Contains' => 'Содержит',
	'UI:Search:Criteria:Operator:String:StartsWith' => 'Начинается с',
	'UI:Search:Criteria:Operator:String:EndsWith' => 'Заканч. на',
	'UI:Search:Criteria:Operator:String:RegExp' => 'Регуляр. выраж.',
	//   - Numeric widget
	'UI:Search:Criteria:Operator:Numeric:Equals' => 'Равно',  // => '=',
	'UI:Search:Criteria:Operator:Numeric:GreaterThan' => 'Больше',  // => '>',
	'UI:Search:Criteria:Operator:Numeric:GreaterThanOrEquals' => 'Больше / равно',  // > '>=',
	'UI:Search:Criteria:Operator:Numeric:LessThan' => 'Меньше',  // => '<',
	'UI:Search:Criteria:Operator:Numeric:LessThanOrEquals' => 'Меньше / равно',  // > '<=',
	'UI:Search:Criteria:Operator:Numeric:Different' => 'Не равно',  // => '≠',
	//   - Tag Set Widget
	'UI:Search:Criteria:Operator:TagSet:Matches' => 'Совпадает',

	// - Other translations
	'UI:Search:Value:Filter:Placeholder' => 'Фильтр...',
	'UI:Search:Value:Search:Placeholder' => 'Поиск...',
	'UI:Search:Value:Autocomplete:StartTyping' => 'Начните печатать, чтобы увидеть возможные значения.',
	'UI:Search:Value:Autocomplete:Wait' => 'Пожалуйста, подождите...',
	'UI:Search:Value:Autocomplete:NoResult' => 'Нет результата.',
	'UI:Search:Value:Toggler:CheckAllNone' => 'Отметить / снять все',
	'UI:Search:Value:Toggler:CheckAllNoneFiltered' => 'Отметить / снять все видимые',

	// - Widget other translations
	'UI:Search:Criteria:Numeric:From' => 'С',
	'UI:Search:Criteria:Numeric:Until' => 'По',
	'UI:Search:Criteria:Numeric:PlaceholderFrom' => 'Любой',
	'UI:Search:Criteria:Numeric:PlaceholderUntil' => 'Любой',
	'UI:Search:Criteria:DateTime:From' => 'С',
	'UI:Search:Criteria:DateTime:FromTime' => 'С',
	'UI:Search:Criteria:DateTime:Until' => 'По',
	'UI:Search:Criteria:DateTime:UntilTime' => 'По',
	'UI:Search:Criteria:DateTime:PlaceholderFrom' => 'Любая дата',
	'UI:Search:Criteria:DateTime:PlaceholderFromTime' => 'Любая дата',
	'UI:Search:Criteria:DateTime:PlaceholderUntil' => 'Любая дата',
	'UI:Search:Criteria:DateTime:PlaceholderUntilTime' => 'Любая дата',
	'UI:Search:Criteria:HierarchicalKey:ChildrenIncluded:Hint' => 'Включаются все нижестоящие объекты.',

	'UI:Search:Criteria:Raw:Filtered' => 'Отфильтровано',
	'UI:Search:Criteria:Raw:FilteredOn' => 'Отфильтровано по %1$s',
));

//
// Expression to Natural language
//
Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Expression:Operator:AND' => ' AND ',
	'Expression:Operator:OR' => ' OR ',
	'Expression:Operator:=' => ': ~~',

	'Expression:Unit:Short:DAY' => 'd',
	'Expression:Unit:Short:WEEK' => 'w',
	'Expression:Unit:Short:MONTH' => 'm',
	'Expression:Unit:Short:YEAR' => 'y',

	'Expression:Unit:Long:DAY' => 'day(s)',
	'Expression:Unit:Long:HOUR' => 'hour(s)',
	'Expression:Unit:Long:MINUTE' => 'minute(s)',

	'Expression:Verb:NOW' => 'now',
	'Expression:Verb:ISNULL' => ': undefined~~',
));

//
// iTop Newsroom menu
//
Dict::Add('RU RU', 'Russian', 'Русский', array(
	'UI:Newsroom:NoNewMessage' => 'Нет новых сообщений',
	'UI:Newsroom:MarkAllAsRead' => 'Отметить все как прочитанные сообщения',
	'UI:Newsroom:ViewAllMessages' => 'Посмотреть все сообщения',
	'UI:Newsroom:Preferences' => 'Настройки новостей',
	'UI:Newsroom:ConfigurationLink' => 'конфигурация',
	'UI:Newsroom:ResetCache' => 'Сбросить кеш',
	'UI:Newsroom:DisplayMessagesFor_Provider' => 'Показать сообщения от %1$s',
	'UI:Newsroom:DisplayAtMost_X_Messages' => 'Отобразите не более %1$s сообщений в меню %2$s.',
));
