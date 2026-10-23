<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2026 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * 
 */
Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:KnownError' => 'Відома помилка',
	'Class:KnownError+' => 'Проблема, що має задокументовані кореневу причину і обхідне рішення',
	'Class:KnownError/Attribute:name' => 'Назва',
	'Class:KnownError/Attribute:name+' => '',
	'Class:KnownError/Attribute:org_id' => 'Організація',
	'Class:KnownError/Attribute:org_id+' => '',
	'Class:KnownError/Attribute:cust_name' => 'Організація',
	'Class:KnownError/Attribute:cust_name+' => '',
	'Class:KnownError/Attribute:problem_id' => 'Проблема',
	'Class:KnownError/Attribute:problem_id+' => '',
	'Class:KnownError/Attribute:problem_ref' => 'Проблема',
	'Class:KnownError/Attribute:problem_ref+' => '',
	'Class:KnownError/Attribute:symptom' => 'Прояв',
	'Class:KnownError/Attribute:symptom+' => '',
	'Class:KnownError/Attribute:root_cause' => 'Коренева причина',
	'Class:KnownError/Attribute:root_cause+' => '',
	'Class:KnownError/Attribute:workaround' => 'Обхідне рішення',
	'Class:KnownError/Attribute:workaround+' => '',
	'Class:KnownError/Attribute:solution' => 'Рішення',
	'Class:KnownError/Attribute:solution+' => '',
	'Class:KnownError/Attribute:error_code' => 'Код помилки',
	'Class:KnownError/Attribute:error_code+' => '',
	'Class:KnownError/Attribute:domain' => 'Домен',
	'Class:KnownError/Attribute:domain+' => '',
	'Class:KnownError/Attribute:domain/Value:Application' => 'Застосунок',
	'Class:KnownError/Attribute:domain/Value:Application+' => 'Застосунок',
	'Class:KnownError/Attribute:domain/Value:Desktop' => 'Робоче оточення',
	'Class:KnownError/Attribute:domain/Value:Desktop+' => 'Робоче оточення',
	'Class:KnownError/Attribute:domain/Value:Network' => 'Мережа',
	'Class:KnownError/Attribute:domain/Value:Network+' => 'Мережа',
	'Class:KnownError/Attribute:domain/Value:Server' => 'Сервер',
	'Class:KnownError/Attribute:domain/Value:Server+' => 'Сервер',
	'Class:KnownError/Attribute:vendor' => 'Виробник',
	'Class:KnownError/Attribute:vendor+' => '',
	'Class:KnownError/Attribute:model' => 'Модель',
	'Class:KnownError/Attribute:model+' => '',
	'Class:KnownError/Attribute:version' => 'Версія',
	'Class:KnownError/Attribute:version+' => '',
	'Class:KnownError/Attribute:ci_list' => 'КО',
	'Class:KnownError/Attribute:ci_list+' => 'Пов\'язані конфігураційні одиниці',
	'Class:KnownError/Attribute:document_list' => 'Документи',
	'Class:KnownError/Attribute:document_list+' => 'Пов\'язані документи',
));

//
// Class: lnkErrorToFunctionalCI
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:lnkErrorToFunctionalCI' => 'Зв\'язок Відома помилка/Функціональна КО',
	'Class:lnkErrorToFunctionalCI+' => 'Інфраструктура, пов\'язана з відомою помилкою',
	'Class:lnkErrorToFunctionalCI/Name' => '%1$s / %2$s~~',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id' => 'КО',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name' => 'КО',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:error_id' => 'Відома помилка',
	'Class:lnkErrorToFunctionalCI/Attribute:error_id+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:error_name' => 'Відома помилка',
	'Class:lnkErrorToFunctionalCI/Attribute:error_name+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:reason' => 'Причина',
	'Class:lnkErrorToFunctionalCI/Attribute:reason+' => '',
));

//
// Class: lnkDocumentToError
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:lnkDocumentToError' => 'Зв\'язок Документ/Відома помилка',
	'Class:lnkDocumentToError+' => 'Зв\'язок між документом та відомою помилкою',
	'Class:lnkDocumentToError/Name' => '%1$s / %2$s~~',
	'Class:lnkDocumentToError/Attribute:document_id' => 'Документ',
	'Class:lnkDocumentToError/Attribute:document_id+' => '',
	'Class:lnkDocumentToError/Attribute:document_name' => 'Документ',
	'Class:lnkDocumentToError/Attribute:document_name+' => '',
	'Class:lnkDocumentToError/Attribute:error_id' => 'Відома помилка',
	'Class:lnkDocumentToError/Attribute:error_id+' => '',
	'Class:lnkDocumentToError/Attribute:error_name' => 'Відома помилка',
	'Class:lnkDocumentToError/Attribute:error_name+' => '',
	'Class:lnkDocumentToError/Attribute:link_type' => 'Тип зв\'язку',
	'Class:lnkDocumentToError/Attribute:link_type+' => '',
));

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Menu:ProblemManagement' => 'Управління проблемами',
	'Menu:ProblemManagement+' => 'Управління проблемами',
	'Menu:Problem:Shortcuts' => 'Ярлики',
	'Menu:NewError' => 'Нова відома помилка',
	'Menu:NewError+' => 'Створити нову відому помилку',
	'Menu:SearchError' => 'Пошук відомих помилок',
	'Menu:SearchError+' => 'Пошук відомих помилок',
	'Menu:Problem:KnownErrors' => 'Відомі помилки',
	'Menu:Problem:KnownErrors+' => 'База відомих помилок',
));
