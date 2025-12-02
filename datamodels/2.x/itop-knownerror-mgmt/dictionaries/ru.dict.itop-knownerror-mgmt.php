<?php

/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 *
 */
/**
 * @author Vladimir Kunin <v.b.kunin@gmail.com>
 *
 */
Dict::Add('RU RU', 'Russian', 'Русский', [
	'Class:KnownError' => 'Известная ошибка',
	'Class:KnownError+' => 'Проблема, имеющая задокументированные корневую причину и обходное решение',
	'Class:KnownError/Attribute:name' => 'Название',
	'Class:KnownError/Attribute:name+' => 'This is expected to be a unique identifier within the Known Errors of this organization~~',
	'Class:KnownError/Attribute:org_id' => 'Организация',
	'Class:KnownError/Attribute:org_id+' => 'Link the known error to the service provider in charge of handling them, or maybe to a customer organization if the error is specific to them~~',
	'Class:KnownError/Attribute:cust_name' => 'Организация',
	'Class:KnownError/Attribute:cust_name+' => '',
	'Class:KnownError/Attribute:problem_id' => 'Проблема',
	'Class:KnownError/Attribute:problem_id+' => 'The problem which couldn\'t be solved immediately and has led to the creation of this known error~~',
	'Class:KnownError/Attribute:problem_ref' => 'Проблема',
	'Class:KnownError/Attribute:problem_ref+' => '',
	'Class:KnownError/Attribute:symptom' => 'Проявление',
	'Class:KnownError/Attribute:symptom+' => 'What are the observable effects of this error?~~',
	'Class:KnownError/Attribute:root_cause' => 'Корневая причина',
	'Class:KnownError/Attribute:root_cause+' => 'What is the underlying cause of this error?~~',
	'Class:KnownError/Attribute:workaround' => 'Обходное решение',
	'Class:KnownError/Attribute:workaround+' => 'How to bypass the effects of this error until a proper solution is found?~~',
	'Class:KnownError/Attribute:solution' => 'Решение',
	'Class:KnownError/Attribute:solution+' => 'What is the permanent solution for this error?~~',
	'Class:KnownError/Attribute:error_code' => 'Код ошибки',
	'Class:KnownError/Attribute:error_code+' => 'If a specific error code is associated to this known error, specify it here~~',
	'Class:KnownError/Attribute:domain' => 'Домен',
	'Class:KnownError/Attribute:domain+' => 'Choose the technical domain related to this known error?~~',
	'Class:KnownError/Attribute:domain/Value:Application' => 'Приложение',
	'Class:KnownError/Attribute:domain/Value:Application+' => '',
	'Class:KnownError/Attribute:domain/Value:Desktop' => 'Рабочее окружение',
	'Class:KnownError/Attribute:domain/Value:Desktop+' => '',
	'Class:KnownError/Attribute:domain/Value:Network' => 'Сеть',
	'Class:KnownError/Attribute:domain/Value:Network+' => '',
	'Class:KnownError/Attribute:domain/Value:Server' => 'Сервер',
	'Class:KnownError/Attribute:domain/Value:Server+' => '',
	'Class:KnownError/Attribute:vendor' => 'Производитель',
	'Class:KnownError/Attribute:vendor+' => 'A free text field to identify the vendor of the CI(s) concerned by this known error~~',
	'Class:KnownError/Attribute:model' => 'Модель',
	'Class:KnownError/Attribute:model+' => 'The model of the CI(s) concerned by this known error~~',
	'Class:KnownError/Attribute:version' => 'Версия',
	'Class:KnownError/Attribute:version+' => 'The version of the CI(s) concerned by this known error~~',
	'Class:KnownError/Attribute:ci_list' => 'КЕ',
	'Class:KnownError/Attribute:ci_list+' => 'Связанный конфигурационные единицы',
	'Class:KnownError/Attribute:document_list' => 'Документы',
	'Class:KnownError/Attribute:document_list+' => 'Связанные документы',
]);

//
// Class: lnkErrorToFunctionalCI
//

Dict::Add('RU RU', 'Russian', 'Русский', [
	'Class:lnkErrorToFunctionalCI' => 'Связь Известная ошибка/Функциональная КЕ',
	'Class:lnkErrorToFunctionalCI+' => 'Infra related to a known error',
	'Class:lnkErrorToFunctionalCI/Name' => '%1$s / %2$s~~',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id' => 'КЕ',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name' => 'КЕ',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:error_id' => 'Известная ошибка',
	'Class:lnkErrorToFunctionalCI/Attribute:error_id+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:error_name' => 'Известная ошибка',
	'Class:lnkErrorToFunctionalCI/Attribute:error_name+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:reason' => 'Причина',
	'Class:lnkErrorToFunctionalCI/Attribute:reason+' => '',
]);

//
// Class: lnkDocumentToError
//

Dict::Add('RU RU', 'Russian', 'Русский', [
	'Class:lnkDocumentToError' => 'Связь Документ/Известная ошибка',
	'Class:lnkDocumentToError+' => 'A link between a document and a known error',
	'Class:lnkDocumentToError/Name' => '%1$s / %2$s~~',
	'Class:lnkDocumentToError/Attribute:document_id' => 'Документ',
	'Class:lnkDocumentToError/Attribute:document_id+' => '',
	'Class:lnkDocumentToError/Attribute:document_name' => 'Документ',
	'Class:lnkDocumentToError/Attribute:document_name+' => '',
	'Class:lnkDocumentToError/Attribute:error_id' => 'Известная ошибка',
	'Class:lnkDocumentToError/Attribute:error_id+' => '',
	'Class:lnkDocumentToError/Attribute:error_name' => 'Известная ошибка',
	'Class:lnkDocumentToError/Attribute:error_name+' => '',
	'Class:lnkDocumentToError/Attribute:link_type' => 'Тип связи',
	'Class:lnkDocumentToError/Attribute:link_type+' => '',
]);

Dict::Add('RU RU', 'Russian', 'Русский', [
	'Menu:ProblemManagement' => 'Управление проблемами',
	'Menu:ProblemManagement+' => 'Управление проблемами',
	'Menu:Problem:Shortcuts' => 'Ярлыки',
	'Menu:NewError' => 'Новая известная ошибка',
	'Menu:NewError+' => 'Создать новую известную ошибку',
	'Menu:SearchError' => 'Поиск известных ошибок',
	'Menu:SearchError+' => 'Поиск известных ошибок',
	'Menu:Problem:KnownErrors' => 'Известные ошибки',
	'Menu:Problem:KnownErrors+' => 'База известных ошибок',
]);
