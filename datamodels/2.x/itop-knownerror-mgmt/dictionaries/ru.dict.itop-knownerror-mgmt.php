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
	'Class:KnownError/Attribute:name+' => 'Ожидается уникальный идентификатор в рамках известных ошибок этой организации',
	'Class:KnownError/Attribute:org_id' => 'Организация',
	'Class:KnownError/Attribute:org_id+' => 'Свяжите известную ошибку с поставщиком услуг, отвечающим за её обработку, либо с организацией-заказчиком, если ошибка специфична для неё',
	'Class:KnownError/Attribute:cust_name' => 'Организация',
	'Class:KnownError/Attribute:cust_name+' => '',
	'Class:KnownError/Attribute:problem_id' => 'Проблема',
	'Class:KnownError/Attribute:problem_id+' => 'Проблема, которую не удалось решить сразу и которая привела к созданию этой известной ошибки',
	'Class:KnownError/Attribute:problem_ref' => 'Проблема',
	'Class:KnownError/Attribute:problem_ref+' => '',
	'Class:KnownError/Attribute:symptom' => 'Проявление',
	'Class:KnownError/Attribute:symptom+' => 'Какие наблюдаемые последствия у этой ошибки?',
	'Class:KnownError/Attribute:root_cause' => 'Корневая причина',
	'Class:KnownError/Attribute:root_cause+' => 'Какова первопричина этой ошибки?',
	'Class:KnownError/Attribute:workaround' => 'Обходное решение',
	'Class:KnownError/Attribute:workaround+' => 'Как обойти последствия этой ошибки до нахождения полноценного решения?',
	'Class:KnownError/Attribute:solution' => 'Решение',
	'Class:KnownError/Attribute:solution+' => 'В чём заключается окончательное решение этой ошибки?',
	'Class:KnownError/Attribute:error_code' => 'Код ошибки',
	'Class:KnownError/Attribute:error_code+' => 'Если с этой известной ошибкой связан конкретный код ошибки, укажите его здесь',
	'Class:KnownError/Attribute:domain' => 'Домен',
	'Class:KnownError/Attribute:domain+' => 'Выберите технический домен, связанный с этой известной ошибкой',
	'Class:KnownError/Attribute:domain/Value:Application' => 'Приложение',
	'Class:KnownError/Attribute:domain/Value:Application+' => '',
	'Class:KnownError/Attribute:domain/Value:Desktop' => 'Рабочее окружение',
	'Class:KnownError/Attribute:domain/Value:Desktop+' => '',
	'Class:KnownError/Attribute:domain/Value:Network' => 'Сеть',
	'Class:KnownError/Attribute:domain/Value:Network+' => '',
	'Class:KnownError/Attribute:domain/Value:Server' => 'Сервер',
	'Class:KnownError/Attribute:domain/Value:Server+' => '',
	'Class:KnownError/Attribute:vendor' => 'Производитель',
	'Class:KnownError/Attribute:vendor+' => 'Произвольное текстовое поле для указания производителя КЕ, к которым относится эта известная ошибка',
	'Class:KnownError/Attribute:model' => 'Модель',
	'Class:KnownError/Attribute:model+' => 'Модель КЕ, к которым относится эта известная ошибка',
	'Class:KnownError/Attribute:version' => 'Версия',
	'Class:KnownError/Attribute:version+' => 'Версия КЕ, к которым относится эта известная ошибка',
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
	'Class:lnkErrorToFunctionalCI/Name' => '%1$s / %2$s',
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
	'Class:lnkDocumentToError/Name' => '%1$s / %2$s',
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
	'Menu:ProblemManagement+' => 'Процесс ITIL, который выявляет первопричины инцидентов, документирует известные ошибки и FAQ, чтобы снизить нагрузку на службу поддержки',
	'Menu:Problem:Shortcuts' => 'Ярлыки',
	'Menu:NewError' => 'Новая известная ошибка',
	'Menu:NewError+' => 'Создать новую известную ошибку',
	'Menu:SearchError' => 'Поиск известных ошибок',
	'Menu:SearchError+' => 'Поиск известных ошибок',
	'Menu:Problem:KnownErrors' => 'Известные ошибки',
	'Menu:Problem:KnownErrors+' => 'База известных ошибок',
]);
