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

//
// Class: QueryOQL
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Query' => 'Запрос',
	'Class:Query+' => 'Запрос - это набор данных, определенных динамическим путем',
	'Class:Query/Attribute:name' => 'Имя~~',
	'Class:Query/Attribute:name+' => 'Идентифицирует запрос',
	'Class:Query/Attribute:description' => 'Расшифровка~~',
	'Class:Query/Attribute:description+' => 'Длинное описание запроса (назначение, использование и т.д.)',
	'Class:Query/Attribute:fields' => 'Значения~~',
	'Class:Query/Attribute:fields+' => 'Список атрибутов для экспорта, разделённых запятыми (или alias.attribute)',

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
	'Class:User+' => 'Пользовательский логин',
	'Class:User/Attribute:finalclass' => 'Тип счёта',
	'Class:User/Attribute:finalclass+' => '',
	'Class:User/Attribute:contactid' => 'Контакт (человек)',
	'Class:User/Attribute:contactid+' => 'Личные данные из бизнес-данных',
	'Class:User/Attribute:last_name' => 'Фамилия',
	'Class:User/Attribute:last_name+' => 'Фамилия соответсвующего контакта',
	'Class:User/Attribute:first_name' => 'Имя',
	'Class:User/Attribute:first_name+' => 'Имя соответсвующего контакта',
	'Class:User/Attribute:email' => 'email',
	'Class:User/Attribute:email+' => 'email соответсвующего контакта',
	'Class:User/Attribute:login' => 'Логин',
	'Class:User/Attribute:login+' => 'строка идентификации пользователя',
	'Class:User/Attribute:language' => 'Язык',
	'Class:User/Attribute:language+' => 'язык пользователя',
	'Class:User/Attribute:language/Value:EN US' => 'Английский',
	'Class:User/Attribute:language/Value:EN US+' => 'Английский (США)',
	'Class:User/Attribute:language/Value:FR FR' => 'Французский',
	'Class:User/Attribute:language/Value:FR FR+' => 'Французский (Франция)',
	'Class:User/Attribute:profile_list' => 'Профили',
	'Class:User/Attribute:profile_list+' => 'Роли, предоставляющие права этому пользователю',
	'Class:User/Attribute:allowed_org_list' => 'Разрешённые организации',
	'Class:User/Attribute:allowed_org_list+' => 'Пользователь может видеть данные только указанных ниже организации. Оставьте поле пустым для доступа ко всем данным.',

	'Class:User/Error:LoginMustBeUnique' => 'Логин должен быть уникальным - "%1s" уже используется.',
	'Class:User/Error:AtLeastOneProfileIsNeeded' => 'Как минимум один профиль должен быть назначен данному пользователю.',
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
	'BooleanLabel:yes' => 'yes',
	'BooleanLabel:no' => 'no',
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
<li>Повышают производительность IT-операция</li> 
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
	'UI:YourSearch' => 'Ваш поиск',
	'UI:LoggedAsMessage' => 'Вы вошли как %1$s',
	'UI:LoggedAsMessage+Admin' => 'Вы вошли как %1$s (Администратор)',
	'UI:Button:Logoff' => 'Выход',
	'UI:Button:GlobalSearch' => 'Поиск',
	'UI:Button:Search' => ' Поиск ',
	'UI:Button:Query' => ' Запрос ',
	'UI:Button:Ok' => 'Ok',
	'UI:Button:Save' => 'Сохранить',
	'UI:Button:Cancel' => 'Отмена',
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
	'UI:Button:AddObject' => ' Добавить... ',
	'UI:Button:BrowseObjects' => ' Обзор... ',
	'UI:Button:Add' => ' Добавить ',
	'UI:Button:AddToList' => ' << Добавить ',
	'UI:Button:RemoveFromList' => ' Удалить >> ',
	'UI:Button:FilterList' => ' Фильтр... ',
	'UI:Button:Create' => ' Создать ',
	'UI:Button:Delete' => ' Удалить ! ',
	'UI:Button:Rename' => ' Переименовать...',
	'UI:Button:ChangePassword' => ' Сменить пароль ',
	'UI:Button:ResetPassword' => ' Сбросить пароль ',
	
	'UI:SearchToggle' => 'Поиск',
	'UI:ClickToCreateNew' => 'Создать: %1$s',
	'UI:SearchFor_Class' => 'Поиск: %1$s',
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
	'UI:Menu:CSVExport' => 'Экспорт в CSV...',
	'UI:Menu:Modify' => 'Изменить...',
	'UI:Menu:Delete' => 'Удалить...',
	'UI:Menu:Manage' => 'Управление...',
	'UI:Menu:BulkDelete' => 'Удалить...',
	'UI:UndefinedObject' => 'неопределённый',
	'UI:Document:OpenInNewWindow:Download' => 'Открыть в новом окне: %1$s, Загрузка: %2$s',
	'UI:SelectAllToggle+' => 'Выбрать всё / Отменить всё',
	'UI:SplitDateTime-Date' => 'дата~~',
	'UI:SplitDateTime-Time' => 'время~~',
	'UI:TruncatedResults' => '%1$d объектов отображено из %2$d',
	'UI:DisplayAll' => 'Показать всё',
	'UI:CollapseList' => 'Свернуть',
	'UI:CountOfResults' => '%1$d объект(ы)',
	'UI:ChangesLogTitle' => 'Журнал изменений (%1$d):',
	'UI:EmptyChangesLogTitle' => 'Журнал изменений пустой',
	'UI:SearchFor_Class_Objects' => 'Поиск %1$s',
	'UI:OQLQueryBuilderTitle' => 'Коструктор запросов OQL',
	'UI:OQLQueryTab' => 'Запрос OQL',
	'UI:SimpleSearchTab' => 'Простой поиск',
	'UI:Details+' => 'Подробности',
	'UI:SearchValue:Any' => '* Любой *',
	'UI:SearchValue:Mixed' => '* смешанный *',
	'UI:SearchValue:NbSelected' => '# выбрано',
	'UI:SearchValue:CheckAll' => 'Отметить все',
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
	'UI:Login:ResetPwdFailed' => 'Не удалось оптравить email: %1$s',

	'UI:ResetPwd-Error-WrongLogin' => 'аккаунт с логином "%1$s" не найден',
	'UI:ResetPwd-Error-NotPossible' => 'external accounts do not allow password reset.',
	'UI:ResetPwd-Error-FixedPwd' => 'the account does not allow password reset.',
	'UI:ResetPwd-Error-NoContact' => 'данный аккаунт не связан с персоной.',
	'UI:ResetPwd-Error-NoEmailAtt' => 'the account is not associated to a person having an email attribute. Please Contact your administrator.',
	'UI:ResetPwd-Error-NoEmail' => 'missing an email address. Please Contact your administrator.',
	'UI:ResetPwd-Error-Send' => 'email transport technical issue. Please Contact your administrator.',
	'UI:ResetPwd-EmailSent' => 'Пожалуйста, проверьте свой почтовый ящик и следуйте инструкциям.',
	'UI:ResetPwd-EmailSubject' => 'Reset your iTop password',
	'UI:ResetPwd-EmailBody' => '<body><p>You have requested to reset your iTop password.</p><p>Please follow this link (single usage) to <a href="%1$s">enter a new password</a></p>.',

	'UI:ResetPwd-Title' => 'Reset password',
	'UI:ResetPwd-Error-InvalidToken' => 'Sorry, either the password has already been reset, or you have received several emails. Please make sure that you use the link provided in the very last email received.',
	'UI:ResetPwd-Error-EnterPassword' => 'Enter a new password for the account \'%1$s\'.',
	'UI:ResetPwd-Ready' => 'The password has been changed.',
	'UI:ResetPwd-Login' => 'Click here to login...',

	'UI:Login:About' => '',
	'UI:Login:ChangeYourPassword' => 'Изменение пароля',
	'UI:Login:OldPasswordPrompt' => 'Старый пароль',
	'UI:Login:NewPasswordPrompt' => 'Новый пароль',
	'UI:Login:RetypeNewPasswordPrompt' => 'Повтор нового пароля',
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
	'UI:CSVImport:ErrorExtendedAttCode' => 'Внутренняя ошибка: "%1$s" некорректный код потому, что "%2$s" НЕ являеться внешним ключём класса "%3$s"',
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
	'UI:CSVImport:AdvancedMode+' => 'In advanced mode the "id" (primary key) of the objects can be used to update and rename objects.' .
									'However the column "id" (if present) can only be used as a search criteria and can not be combined with any other search criteria.',
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
	'UI:RunQuery:DevelopedQuery' => 'Переработанное выражение запроса: ',
	'UI:RunQuery:SerializedFilter' => 'Сериализованные фильты: ',
	'UI:RunQuery:Error' => 'Ошибка при выполнении запроса: %1$s',
	'UI:Query:UrlForExcel' => 'URL to use for MS-Excel web queries~~',
	'UI:Query:UrlV1' => 'The list of fields has been left unspecified. The page <em>export-V2.php</em> cannot be invoked without this information. Therefore, the URL suggested herebelow points to the legacy page: <em>export.php</em>. This legacy version of the export has the following limitation: the list of exported fields may vary depending on the output format and the data model of iTop. Should you want to garantee that the list of exported columns will remain stable on the long run, then you must specify a value for the attribute "Fields" and use the page <em>export-V2.php</em>.~~',
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
	'UI:Edit:TestQuery' => 'Проверить запрос',
	'UI:Combo:SelectValue' => '--- выбор значения ---',
	'UI:Label:SelectedObjects' => 'Выбранные объекты: ',
	'UI:Label:AvailableObjects' => 'Доступные объекты: ',
	'UI:Link_Class_Attributes' => '%1$s атрибуты',
	'UI:SelectAllToggle+' => 'Выбрать всё / Отменить всё',
	'UI:AddObjectsOf_Class_LinkedWith_Class_Instance' => 'Добавить %1$s объекты связанные с %2$s: %3$s',
	'UI:AddObjectsOf_Class_LinkedWith_Class' => 'Добавть %1$s объекты для связи с %2$s',
	'UI:ManageObjectsOf_Class_LinkedWith_Class_Instance' => 'Управление %1$s объектами связанными с %2$s: %3$s',
	'UI:AddLinkedObjectsOf_Class' => 'Добавить %1$s...',
	'UI:RemoveLinkedObjectsOf_Class' => 'Удалить выбранные объекты',
	'UI:Message:EmptyList:UseAdd' => 'Список пуст, используте кнопку "Добавить ...", для добавения новых элементов.',
	'UI:Message:EmptyList:UseSearchForm' => 'Используйте форму поиска выше для поиска объектов, которые будут добавлены.',
	'UI:Wizard:FinalStepTitle' => 'Последний шаг: подтверждение',
	'UI:Title:DeletionOf_Object' => 'Удаление %1$s',
	'UI:Title:BulkDeletionOf_Count_ObjectsOf_Class' => 'Пакетное удаление %1$d объектов класса %2$s',
	'UI:Delete:NotAllowedToDelete' => 'Вы не можете удалить этот объект',
	'UI:Delete:NotAllowedToUpdate_Fields' => 'Вы не можете обновить следующее(ие) поле(я): %1$s',
	'UI:Error:NotEnoughRightsToDelete' => 'Этот объект не может быть удален, потому что текущий пользователь не имеет достаточных прав',
	'UI:Error:CannotDeleteBecause' => 'This object could not be deleted because: %1$s',
	'UI:Error:CannotDeleteBecauseOfDepencies' => 'Этот объект не может быть удален, потому что некоторые ручные операции должны быть выполнены до этого',
	'UI:Error:CannotDeleteBecauseManualOpNeeded' => 'This object could not be deleted because some manual operations must be performed prior to that~~',
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
	'UI:ModificationTitle_Class_Object' => 'Модификации %1$s: <span class=\\"hilite\\">%2$s</span>',
	'UI:ClonePageTitle_Object_Class' => 'iTop - Клон %1$s - %2$s модификация',
	'UI:CloneTitle_Class_Object' => 'Клон %1$s: <span class=\\"hilite\\">%2$s</span>',
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
	'UI:PageTitle:FatalError' => 'iTop - Критическая ошибка',
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
	
	'Menu:AdminTools' => 'Инструменты админа', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AdminTools+' => 'Административные инструменты', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AdminTools?' => 'Инструменты доступны только для пользователей, имеющих профиль администратора', // Duplicated into itop-welcome-itil (will be removed from here...)

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
'UI-ServiceManagementOverview-CustomerContractToRenew' => 'Договора с клиентами, которые будут обновлены в течении 30 дней',
'UI-ServiceManagementOverview-ProviderContractToRenew' => 'Договора с провайдерами, которые будут обновлены в течении 30 дней',

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
	'UI:NotificationsMenu:HelpContent' => '<p>В iTop уведомления полностью настраиваемые. Они основаны на двух наборах объектов: <i>триггеры</i> и <i>действия</i>.</p>
<p><i><b>Триггеры</b></i> оперделяют когда уведомление будет выполнено. Есть 3 типа триггеров обробатывающих 3 разных фазы жизненного цикла объекта:
<ol>
	<li>"OnCreate" триггеры сработают когда объект заданного класса будет создан</li>
	<li>"OnStateEnter" триггеры сработают перед тем как объект заданного класса войдёт в заданное состояние (выйдет из другого состояния)</li>
	<li>"OnStateLeave" триггеры сработают когда объекты заданного класса выйдут из заданного состояния</li>
</ol>
</p>
<p>
<i><b>Действия</b></i> определяют, какое действие будет выполнено при срабатывании триггера. Пока есть только одно действие, которое состоит в отправке сообщения на электронную почту.
Эти действия также определяют шаблон, который будет использован для отправки электронного сообщения, а также другие параметры сообщения, такие как получатель, важность и т.д.
</p>
<p>Специальная страница: <a href="../setup/email.test.php" target="_blank">email.test.php</a> доступна для тестирования и устранения неполадок в настройках почты.</p>
<p>Чтобы быть выполненными, действия необходимо ассоциировать с триггерами.
При ассоциации с триггером, каждое действие получает "порядковый" номер, который определяет порядок выполнения действий.</p>',
	'UI:NotificationsMenu:Triggers' => 'Триггеры',
	'UI:NotificationsMenu:AvailableTriggers' => 'Доступные триггеры',
	'UI:NotificationsMenu:OnCreate' => 'При создании объекта',
	'UI:NotificationsMenu:OnStateEnter' => 'При входе объекта в заданное состояние',
	'UI:NotificationsMenu:OnStateLeave' => 'При выходе объекта из заданного состояния',
	'UI:NotificationsMenu:Actions' => 'Действия',
	'UI:NotificationsMenu:AvailableActions' => 'Доступные действия',
	
	'Menu:AuditCategories' => 'Категории аудита', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AuditCategories+' => 'Категории аудита', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:Notifications:Title' => 'Категории аудита', // Duplicated into itop-welcome-itil (will be removed from here...)
	
	'Menu:RunQueriesMenu' => 'Выполнение запросов', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:RunQueriesMenu+' => 'Выполнение любых запросов', // Duplicated into itop-welcome-itil (will be removed from here...)
	
	'Menu:QueryMenu' => 'Книга запросов', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:QueryMenu+' => 'Query phrasebook', // Duplicated into itop-welcome-itil (will be removed from here...)
	
	'Menu:DataAdministration' => 'Административные данные', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataAdministration+' => 'Административные данные', // Duplicated into itop-welcome-itil (will be removed from here...)
	
	'Menu:UniversalSearchMenu' => 'Универсальный поиск', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UniversalSearchMenu+' => 'Поиск чего угодно...', // Duplicated into itop-welcome-itil (will be removed from here...)
	
	'Menu:UserManagementMenu' => 'Управление пользователями', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserManagementMenu+' => 'Управление пользователями', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:ProfilesMenu' => 'Профили', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ProfilesMenu+' => 'Профили', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ProfilesMenu:Title' => 'Профили', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UserAccountsMenu' => 'Учетные записи пользователей', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserAccountsMenu+' => 'Учетные записи пользователей', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserAccountsMenu:Title' => 'Учетные записи пользователей',	 // Duplicated into itop-welcome-itil (will be removed from here...)

	'UI:iTopVersion:Short' => 'iTop версия %1$s',
	'UI:iTopVersion:Long' => 'iTop версия %1$s-%2$s основан на %3$s',
	'UI:PropertiesTab' => 'Свойства',

	'UI:OpenDocumentInNewWindow_' => 'Открыть этот документ в новом окне: %1$s',
	'UI:DownloadDocument_' => 'Скачать этот документ: %1$s',
	'UI:Document:NoPreview' => 'Не доступен предварительный просомтр для документов данного типа',
	'UI:Download-CSV' => 'Загрузка %1$s~~',

	'UI:DeadlineMissedBy_duration' => 'Пропущен %1$s',
	'UI:Deadline_LessThan1Min' => '< 1 мин',		
	'UI:Deadline_Minutes' => '%1$d мин',			
	'UI:Deadline_Hours_Minutes' => '%1$dч %2$dмин',			
	'UI:Deadline_Days_Hours_Minutes' => '%1$dд %2$dч %3$dмин',
	'UI:Help' => 'Помощь',
	'UI:PasswordConfirm' => '(Подтвердить)',
	'UI:BeforeAdding_Class_ObjectsSaveThisObject' => 'Перед добавлением %1$s объектов, сохраните этот объект.',
	'UI:DisplayThisMessageAtStartup' => 'Показать это сообщение при запуске',
	'UI:RelationshipGraph' => 'Графический вид',
	'UI:RelationshipList' => 'Список',
	'UI:OperationCancelled' => 'Операция отменена',
	'UI:ElementsDisplayed' => 'Фильтрация',

	'Portal:Title' => 'Пользовательский iTop портал',
	'Portal:NoRequestMgmt' => 'Уважаемый %1$s, Вы были перенаправлены на потрал, потому что Ваш аккаунт содержить профиль \'Portal user\'. К сожалению, iTop не содержит модуля \'Request Management\'. Пожалуйста, свяжитель с системным администратором.',
	'Portal:Refresh' => 'Обновить',
	'Portal:Back' => 'Назад',
	'Portal:WelcomeUserOrg' => 'Добро пожаловать %1$s, из %2$s',
	'Portal:TitleDetailsFor_Request' => 'Details for request',
	'Portal:ShowOngoing' => 'Показать открытые запросы',
	'Portal:ShowClosed' => 'Показать закрытые запросы',
	'Portal:CreateNewRequest' => 'Создать новый запрос',
	'Portal:ChangeMyPassword' => 'Изменить мой пароль',
	'Portal:Disconnect' => 'Отключить',
	'Portal:OpenRequests' => 'Мои открытые запросы',
	'Portal:ClosedRequests'  => 'Мои закрытые запросы',
	'Portal:ResolvedRequests'  => 'Мои решённые запросы',
	'Portal:SelectService' => 'Выбери сервис из каталога:',
	'Portal:PleaseSelectOneService' => 'Необходимо выбрать хотя-бы один сервис',
	'Portal:SelectSubcategoryFrom_Service' => 'Выбери под-категорию для сервиса %1$s:',
	'Portal:PleaseSelectAServiceSubCategory' => 'Необходимо выбрать одну под-категорию',
	'Portal:DescriptionOfTheRequest' => 'Введи описание запроса:',
	'Portal:TitleRequestDetailsFor_Request' => 'Подробности запроса %1$s:',
	'Portal:NoOpenRequest' => 'Нет запросов в этой категории.',
	'Portal:NoClosedRequest' => 'Нет запросов в этой категории',
	'Portal:Button:ReopenTicket' => 'Открыть эту заявку',
	'Portal:Button:CloseTicket' => 'Закрыть эту заявку"',
	'Portal:Button:UpdateRequest' => 'Обновить запрос',
	'Portal:EnterYourCommentsOnTicket' => 'Введите ваши каментарии по решению этого "тикета":',
	'Portal:ErrorNoContactForThisUser' => 'Ошибка: текющий пользователь не ассоциирован с Контактом/Человеком. Пожалуйста свяжитесь с вашим администратором.',
	'Portal:Attachments' => 'Вложения',
	'Portal:AddAttachment' => 'Добавить вложения',
	'Portal:RemoveAttachment' => ' Удалить вложения',
	'Portal:Attachment_No_To_Ticket_Name' => 'Вложение #%1$d to %2$s (%3$s)~~',
	'Enum:Undefined' => 'Неопределён',	
	'UI:DurationForm_Days_Hours_Minutes_Seconds' => '%1$s Days %2$s час %3$s мин %4$s сек~~',
	'UI:ModifyAllPageTitle' => 'Изменить все',
	'UI:Modify_N_ObjectsOf_Class' => 'Изменение %1$d объектов класса %2$s~~',
	'UI:Modify_M_ObjectsOf_Class_OutOf_N' => 'Изменение %1$d объектов класса %2$s из %3$d~~',
	'UI:Menu:ModifyAll' => 'Изменить...~~',
	'UI:Button:ModifyAll' => 'Изменить все~~',
	'UI:Button:PreviewModifications' => 'Предпросмотр изменений >>~~',
	'UI:ModifiedObject' => 'Объект изменен',
	'UI:BulkModifyStatus' => 'Операция',
	'UI:BulkModifyStatus+' => 'Status of the operation',
	'UI:BulkModifyErrors' => 'Ошибки (если есть)~~',
	'UI:BulkModifyErrors+' => 'Errors preventing the modification',	
	'UI:BulkModifyStatusOk' => 'Ok~~',
	'UI:BulkModifyStatusError' => 'Ошибка',
	'UI:BulkModifyStatusModified' => 'Изменен',
	'UI:BulkModifyStatusSkipped' => 'Пропущен',
	'UI:BulkModify_Count_DistinctValues' => '%1$d distinct values:~~',
	'UI:BulkModify:Value_Exists_N_Times' => '%1$s, %2$d time(s)~~',
	'UI:BulkModify:N_MoreValues' => '%1$d more values...~~',
	'UI:AttemptingToSetAReadOnlyAttribute_Name' => 'Attempting to set the read-only field: %1$s~~',
	'UI:FailedToApplyStimuli' => 'The action has failed.~~',
	'UI:StimulusModify_N_ObjectsOf_Class' => '%1$s: Modifying %2$d objects of class %3$s~~',
	'UI:CaseLogTypeYourTextHere' => 'Введите свой текст:',
	'UI:CaseLog:DateFormat' => 'Y-m-d H:i:s~~',
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
	'UI:Preferences' => 'Дополнительно...~~',
	'UI:FavoriteOrganizations' => 'Избранные организации',
	'UI:FavoriteOrganizations+' => 'Check in the list below the organizations that you want to see in the drop-down menu for a quick access. '.
								   'Note that this is not a security setting, objects from any organization are still visible and can be accessed by selecting "All Organizations" in the drop-down list.',
	'UI:FavoriteLanguage' => 'Язык пользовательского интерфейса',
	'UI:Favorites:SelectYourLanguage' => 'Выберите Ваш язык',
	'UI:FavoriteOtherSettings' => 'Другие настройки',
	'UI:Favorites:Default_X_ItemsPerPage' => 'Длина списка по-умолчанию: %1$s элементов на страницу.',
	'UI:NavigateAwayConfirmationMessage' => 'Все изменения будут отмененыт.',
	'UI:CancelConfirmationMessage' => 'Настройки НЕ будут сохранены. Продолжить?',
	'UI:AutoApplyConfirmationMessage' => 'Некоторые изменения не вступили в силу.Хотите что бы iТop применил их немедленно?~~',
	'UI:Create_Class_InState' => 'Create the %1$s in state: ~~',
	'UI:OrderByHint_Values' => 'Sort order: %1$s~~',
	'UI:Menu:AddToDashboard' => 'Добавить на панель...',
	'UI:Button:Refresh' => 'Обновить',

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

	'UI:DashletCreation:Title' => 'Создать новую панель',
	'UI:DashletCreation:Dashboard' => 'Приборная панель',
	'UI:DashletCreation:DashletType' => 'Тип панели',
	'UI:DashletCreation:EditNow' => 'Редактировать панель',

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

	'UI:Menu:ShortcutList' => 'Добавить в избранное...',
	'UI:ShortcutRenameDlg:Title' => 'Переименовать ссылку',
	'UI:ShortcutListDlg:Title' => 'Добавить в избранное ссылку на список',
	'UI:ShortcutDelete:Confirm' => 'Подтвердите удаление ссылки (ссылок).',
	'Menu:MyShortcuts' => 'Избранное', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Class:Shortcut' => 'Ссылка',
	'Class:Shortcut+' => '',
	'Class:Shortcut/Attribute:name' => 'Название',
	'Class:Shortcut/Attribute:name+' => 'Label used in the menu and page title',
	'Class:ShortcutOQL' => 'Search result shortcut~~',
	'Class:ShortcutOQL+' => '',
	'Class:ShortcutOQL/Attribute:oql' => 'Запрос',
	'Class:ShortcutOQL/Attribute:oql+' => 'OQL defining the list of objects to search for',
	'Class:ShortcutOQL/Attribute:auto_reload' => 'Automatic refresh',
	'Class:ShortcutOQL/Attribute:auto_reload/Value:none' => 'Disabled',
	'Class:ShortcutOQL/Attribute:auto_reload/Value:custom' => 'Custom rate',
	'Class:ShortcutOQL/Attribute:auto_reload_sec' => 'Automatic refresh interval (seconds)',
	'Class:ShortcutOQL/Attribute:auto_reload_sec/tip' => 'The minimum allowed is %1$d seconds',

	'UI:FillAllMandatoryFields' => 'Пожалуйста, заполните все обязательные поля.',
	
	'UI:CSVImportConfirmTitle' => 'Please confirm the operation',
	'UI:CSVImportConfirmMessage' => 'Are you sure you want to do this?',
	'UI:CSVImportError_items' => 'Errors: %1$d',
	'UI:CSVImportCreated_items' => 'Created: %1$d',
	'UI:CSVImportModified_items' => 'Modified: %1$d',
	'UI:CSVImportUnchanged_items' => 'Unchanged: %1$d',

	'UI:Button:Remove' => 'Remove',
	'UI:AddAnExisting_Class' => 'Add objects of type %1$s...',
	'UI:SelectionOf_Class' => 'Selection of objects of type %1$s',

	'UI:AboutBox' => 'Об этом iTop...',
	'UI:About:Title' => 'Об этом iTop',
	'UI:About:DataModel' => 'Модель данных',
	'UI:About:Support' => 'Информация для технической поддержки',
	'UI:About:Licenses' => 'Лицензии',
	'UI:About:Modules' => 'Установленные модули',
	'Class:UserInternal' => 'User Internal~~',
	'Class:UserInternal+' => 'User defined within iTop~~',
	'UI:CSVImport:AlertMultipleMapping' => 'Please make sure that a target field is mapped only once.~~',
	'UI:Search:NeedleTooShort' => 'The search string \"%1$s\" is too short. Please type at least %2$d characters.~~',
	'UI:Search:Ongoing' => 'Searching for \"%1$s\"~~',
	'UI:Search:Enlarge' => 'Broaden the search~~',
	'UI:RelationGroups' => 'Groups~~',
	'UI:RelationGroupNumber_N' => 'Group #%1$d~~',
	'UI:Relation:ExportAsPDF' => 'Export as PDF...~~',
	'UI:RelationOption:GroupingThreshold' => 'Grouping threshold~~',
	'UI:Relation:AdditionalContextInfo' => 'Additional context info~~',
	'UI:Relation:NoneSelected' => 'None~~',
	'UI:Relation:ExportAsAttachment' => 'Export as Attachment...~~',
	'UI:Relation:DrillDown' => 'Details...~~',
	'UI:Relation:PDFExportOptions' => 'PDF Export Options~~',
	'UI:Relation:AttachmentExportOptions_Name' => 'Options for Attachment to %1$s~~',
	'UI:RelationOption:Untitled' => 'Untitled~~',
	'UI:Relation:Key' => 'Key~~',
	'UI:Relation:Comments' => 'Comments~~',
	'UI:RelationOption:Title' => 'Title~~',
	'UI:RelationOption:IncludeList' => 'Include the list of objects~~',
	'UI:RelationOption:Comments' => 'Comments~~',
	'UI:Button:Export' => 'Export~~',
	'UI:Relation:PDFExportPageFormat' => 'Page format~~',
	'UI:PageFormat_A3' => 'A3~~',
	'UI:PageFormat_A4' => 'A4~~',
	'UI:PageFormat_Letter' => 'Letter~~',
	'UI:Relation:PDFExportPageOrientation' => 'Page orientation~~',
	'UI:PageOrientation_Portrait' => 'Portrait~~',
	'UI:PageOrientation_Landscape' => 'Landscape~~',
	'UI:RelationTooltip:Redundancy' => 'Redundancy~~',
	'UI:RelationTooltip:ImpactedItems_N_of_M' => '# of impacted items: %1$d / %2$d~~',
	'UI:RelationTooltip:CriticalThreshold_N_of_M' => 'Critical threshold: %1$d / %2$d~~',
	'Portal:SelectRequestTemplate' => 'Select a template for %1$s~~',
	'DayOfWeek-Sunday-Min' => 'Su~~',
	'DayOfWeek-Monday-Min' => 'Mo~~',
	'DayOfWeek-Tuesday-Min' => 'Tu~~',
	'DayOfWeek-Wednesday-Min' => 'We~~',
	'DayOfWeek-Thursday-Min' => 'Th~~',
	'DayOfWeek-Friday-Min' => 'Fr~~',
	'DayOfWeek-Saturday-Min' => 'Sa~~',
	'Month-01-Short' => 'Jan~~',
	'Month-02-Short' => 'Feb~~',
	'Month-03-Short' => 'Mar~~',
	'Month-04-Short' => 'Apr~~',
	'Month-05-Short' => 'May~~',
	'Month-06-Short' => 'Jun~~',
	'Month-07-Short' => 'Jul~~',
	'Month-08-Short' => 'Aug~~',
	'Month-09-Short' => 'Sep~~',
	'Month-10-Short' => 'Oct~~',
	'Month-11-Short' => 'Nov~~',
	'Month-12-Short' => 'Dec~~',
	'Calendar-FirstDayOfWeek' => '0~~',
	'UI:ValueMustBeSet' => 'Please specify a value~~',
	'UI:ValueMustBeChanged' => 'Please change the value~~',
	'UI:ValueInvalidFormat' => 'Invalid format~~',
	'UI:DisconnectedDlgMessage' => 'You are disconnected. You must identify yourself to continue using the application.~~',
	'UI:DisconnectedDlgTitle' => 'Warning!~~',
	'UI:LoginAgain' => 'Login again~~',
	'UI:StayOnThePage' => 'Stay on this page~~',
	'ExcelExporter:ExportMenu' => 'Excel Export...~~',
	'ExcelExporter:ExportDialogTitle' => 'Excel Export~~',
	'ExcelExporter:ExportButton' => 'Export~~',
	'ExcelExporter:DownloadButton' => 'Download %1$s~~',
	'ExcelExporter:RetrievingData' => 'Retrieving data...~~',
	'ExcelExporter:BuildingExcelFile' => 'Building the Excel file...~~',
	'ExcelExporter:Done' => 'Done.~~',
	'ExcelExport:AutoDownload' => 'Start the download automatically when the export is ready~~',
	'ExcelExport:PreparingExport' => 'Preparing the export...~~',
	'ExcelExport:Statistics' => 'Statistics~~',
	'portal:legacy_portal' => 'End-User Portal~~',
	'portal:backoffice' => 'iTop Back-Office User Interface~~',
	'UI:CurrentObjectIsLockedBy_User' => 'The object is locked since it is currently being modified by %1$s.~~',
	'UI:CurrentObjectIsLockedBy_User_Explanation' => 'The object is currently being modified by %1$s. Your modifications cannot be submitted since they would be overwritten.~~',
	'UI:CurrentObjectLockExpired' => 'The lock to prevent concurrent modifications of the object has expired.~~',
	'UI:CurrentObjectLockExpired_Explanation' => 'The lock to prevent concurrent modifications of the object has expired. You can no longer submit your modification since other users are now allowed to modify this object.~~',
	'UI:ConcurrentLockKilled' => 'The lock preventing modifications on the current object has been deleted.~~',
	'UI:Menu:KillConcurrentLock' => 'Kill the Concurrent Modification Lock !~~',
	'UI:Menu:ExportPDF' => 'Export as PDF...~~',
));
?>
