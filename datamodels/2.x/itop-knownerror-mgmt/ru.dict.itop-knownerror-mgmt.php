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

//
// Class: KnownError
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:KnownError' => 'Известная ошибка',
	'Class:KnownError+' => 'Проблема, имеющая задокументированные корневую причину и обходное решение',
	'Class:KnownError/Attribute:name' => 'Название',
	'Class:KnownError/Attribute:name+' => '',
	'Class:KnownError/Attribute:org_id' => 'Организация',
	'Class:KnownError/Attribute:org_id+' => '',
	'Class:KnownError/Attribute:cust_name' => 'Имя клиента',
	'Class:KnownError/Attribute:cust_name+' => '',
	'Class:KnownError/Attribute:problem_id' => 'Проблема',
	'Class:KnownError/Attribute:problem_id+' => '',
	'Class:KnownError/Attribute:problem_ref' => 'Ссылка на проблему',
	'Class:KnownError/Attribute:problem_ref+' => '',
	'Class:KnownError/Attribute:symptom' => 'Проявление',
	'Class:KnownError/Attribute:symptom+' => '',
	'Class:KnownError/Attribute:root_cause' => 'Корневая причина',
	'Class:KnownError/Attribute:root_cause+' => '',
	'Class:KnownError/Attribute:workaround' => 'Обходное решение',
	'Class:KnownError/Attribute:workaround+' => '',
	'Class:KnownError/Attribute:solution' => 'Решение',
	'Class:KnownError/Attribute:solution+' => '',
	'Class:KnownError/Attribute:error_code' => 'Код ошибки',
	'Class:KnownError/Attribute:error_code+' => '',
	'Class:KnownError/Attribute:domain' => 'Домен',
	'Class:KnownError/Attribute:domain+' => '',
	'Class:KnownError/Attribute:domain/Value:Application' => 'Приложение',
	'Class:KnownError/Attribute:domain/Value:Application+' => 'Приложение',
	'Class:KnownError/Attribute:domain/Value:Desktop' => 'Рабочее окружение',
	'Class:KnownError/Attribute:domain/Value:Desktop+' => 'Рабочее окружение',
	'Class:KnownError/Attribute:domain/Value:Network' => 'Сеть',
	'Class:KnownError/Attribute:domain/Value:Network+' => 'Сеть',
	'Class:KnownError/Attribute:domain/Value:Server' => 'Сервер',
	'Class:KnownError/Attribute:domain/Value:Server+' => 'Сервер',
	'Class:KnownError/Attribute:vendor' => 'Производитель',
	'Class:KnownError/Attribute:vendor+' => '',
	'Class:KnownError/Attribute:model' => 'Модель',
	'Class:KnownError/Attribute:model+' => '',
	'Class:KnownError/Attribute:version' => 'Версия',
	'Class:KnownError/Attribute:version+' => '',
	'Class:KnownError/Attribute:ci_list' => 'КЕ',
	'Class:KnownError/Attribute:ci_list+' => 'Связанный конфигурационные единицы',
	'Class:KnownError/Attribute:document_list' => 'Документы',
	'Class:KnownError/Attribute:document_list+' => 'Связанные документы',
));

//
// Class: lnkErrorToFunctionalCI
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkErrorToFunctionalCI' => 'Связь Известная ошибка/Функциональная КЕ',
	'Class:lnkErrorToFunctionalCI+' => 'Infra related to a known error',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id' => 'КЕ',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name' => 'Имя КЕ',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:error_id' => 'Известная ошибка',
	'Class:lnkErrorToFunctionalCI/Attribute:error_id+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:error_name' => 'Имя известной ошибки',
	'Class:lnkErrorToFunctionalCI/Attribute:error_name+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:reason' => 'Причина',
	'Class:lnkErrorToFunctionalCI/Attribute:reason+' => '',
));

//
// Class: lnkDocumentToError
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkDocumentToError' => 'Связь Документ/Известная ошибка',
	'Class:lnkDocumentToError+' => 'A link between a document and a known error',
	'Class:lnkDocumentToError/Attribute:document_id' => 'Документ',
	'Class:lnkDocumentToError/Attribute:document_id+' => '',
	'Class:lnkDocumentToError/Attribute:document_name' => 'Имя документа',
	'Class:lnkDocumentToError/Attribute:document_name+' => '',
	'Class:lnkDocumentToError/Attribute:error_id' => 'Известная ошибка',
	'Class:lnkDocumentToError/Attribute:error_id+' => '',
	'Class:lnkDocumentToError/Attribute:error_name' => 'Часто задаваемые вопросы',
	'Class:lnkDocumentToError/Attribute:error_name+' => '',
	'Class:lnkDocumentToError/Attribute:link_type' => 'Тип связи',
	'Class:lnkDocumentToError/Attribute:link_type+' => '',
));

//
// Class: FAQ
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:FAQ' => 'FAQ',
	'Class:FAQ+' => 'Часто задаваемые вопросы',
	'Class:FAQ/Attribute:title' => 'Название',
	'Class:FAQ/Attribute:title+' => '',
	'Class:FAQ/Attribute:summary' => 'Краткое содержание',
	'Class:FAQ/Attribute:summary+' => '',
	'Class:FAQ/Attribute:description' => 'Описание',
	'Class:FAQ/Attribute:description+' => '',
	'Class:FAQ/Attribute:category_id' => 'Категория',
	'Class:FAQ/Attribute:category_id+' => '',
	'Class:FAQ/Attribute:category_name' => 'Имя категории',
	'Class:FAQ/Attribute:category_name+' => '',
	'Class:FAQ/Attribute:error_code' => 'Код ошибки',
	'Class:FAQ/Attribute:error_code+' => '',
	'Class:FAQ/Attribute:key_words' => 'Ключевые слова',
	'Class:FAQ/Attribute:key_words+' => '',
));

//
// Class: FAQCategory
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:FAQCategory' => 'Категории ЧаВо',
	'Class:FAQCategory+' => 'Category for FAQ',
	'Class:FAQCategory/Attribute:name' => 'Имя',
	'Class:FAQCategory/Attribute:name+' => '',
	'Class:FAQCategory/Attribute:faq_list' => 'ЧаВо',
	'Class:FAQCategory/Attribute:faq_list+' => 'All the frequently asked questions related to this category',
));
Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Menu:ProblemManagement' => 'Problem Management',
	'Menu:ProblemManagement+' => 'Problem Management',
	'Menu:Problem:Shortcuts' => 'Shortcuts',
	'Menu:NewError' => 'Создать известную ошибку',
	'Menu:NewError+' => 'Creation of a new known error',
	'Menu:SearchError' => 'Найти известную ошибку',
	'Menu:SearchError+' => 'Search for known errors',
        'Menu:Problem:KnownErrors' => 'Известные ошибки',
        'Menu:Problem:KnownErrors+' => 'База известных ошибок',
	'Menu:FAQCategory' => 'Категории FAQ',
	'Menu:FAQCategory+' => 'Категории FAQ',
	'Menu:FAQ' => 'FAQ',
	'Menu:FAQ+' => 'Часто задаваемые вопросы',

));
?>
