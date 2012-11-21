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
 * @author	Vladimir Shilov <shilow@ukr.net>

 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @licence	http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:KnownError' => 'Известные ошибки',
	'Class:KnownError+' => 'Ошибки задокументированные как известные',
	'Class:KnownError/Attribute:name' => 'Название',
	'Class:KnownError/Attribute:name+' => '',
	'Class:KnownError/Attribute:org_id' => 'Клинт',
	'Class:KnownError/Attribute:org_id+' => '',
	'Class:KnownError/Attribute:problem_id' => 'Связанная проблема',
	'Class:KnownError/Attribute:problem_id+' => '',
	'Class:KnownError/Attribute:symptom' => 'Проявление',
	'Class:KnownError/Attribute:symptom+' => '',
	'Class:KnownError/Attribute:root_cause' => 'Основная причина',
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
	'Class:KnownError/Attribute:ci_list+' => '',
	'Class:KnownError/Attribute:document_list' => 'Документы',
	'Class:KnownError/Attribute:document_list+' => '',
	'Class:lnkErrorToFunctionalCI' => 'Link Error / FunctionalCI~~',
	'Class:lnkErrorToFunctionalCI+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id' => 'CI~~',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:error_id' => 'Error~~',
	'Class:lnkErrorToFunctionalCI/Attribute:error_id+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:reason' => 'Reason~~',
	'Class:lnkErrorToFunctionalCI/Attribute:reason+' => '',
	'Class:lnkDocumentToError' => 'Link Documents / Errors~~',
	'Class:lnkDocumentToError+' => '',
	'Class:lnkDocumentToError/Attribute:document_id' => 'Document~~',
	'Class:lnkDocumentToError/Attribute:document_id+' => '',
	'Class:lnkDocumentToError/Attribute:error_id' => 'Error~~',
	'Class:lnkDocumentToError/Attribute:error_id+' => '',
	'Class:lnkDocumentToError/Attribute:link_type' => 'link_type~~',
	'Class:lnkDocumentToError/Attribute:link_type+' => '',
	'Class:FAQ' => 'FAQ~~',
	'Class:FAQ+' => '',
	'Class:FAQ/Attribute:title' => 'Title~~',
	'Class:FAQ/Attribute:title+' => '',
	'Class:FAQ/Attribute:summary' => 'Summary~~',
	'Class:FAQ/Attribute:summary+' => '',
	'Class:FAQ/Attribute:description' => 'Description~~',
	'Class:FAQ/Attribute:description+' => '',
	'Class:FAQ/Attribute:category_id' => 'Category~~',
	'Class:FAQ/Attribute:category_id+' => '',
	'Class:FAQ/Attribute:error_code' => 'Error code~~',
	'Class:FAQ/Attribute:error_code+' => '',
	'Class:FAQ/Attribute:key_words' => 'Key words~~',
	'Class:FAQ/Attribute:key_words+' => '',
	'Class:FAQcategory' => 'FAQ Category~~',
	'Class:FAQcategory+' => '',
	'Class:FAQcategory/Attribute:name' => 'Name~~',
	'Class:FAQcategory/Attribute:name+' => '',
	'Class:FAQcategory/Attribute:faq_list' => 'FAQs~~',
	'Class:FAQcategory/Attribute:faq_list+' => '',
	'Menu:NewError' => 'Новая известная ошибка',
	'Menu:NewError+' => 'Создание новой известной ошибки',
	'Menu:SearchError' => 'Поиск известных ошибок',
	'Menu:SearchError+' => 'Поиск известных ошибок',
	'Menu:Problem:KnownErrors' => 'Все известные ошибки',
	'Menu:Problem:KnownErrors+' => 'Все известные ошибки',
	'Menu:FAQCategory' => 'FAQ categories~~',
	'Menu:FAQCategory+' => '',
	'Menu:FAQ' => 'FAQs~~',
	'Menu:FAQ+' => '',
	'Class:KnownError/Attribute:cust_name' => 'Имя клиента',
	'Class:KnownError/Attribute:cust_name+' => '',
	'Class:KnownError/Attribute:problem_ref' => 'Ссылка',
	'Class:KnownError/Attribute:problem_ref+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name' => 'CI name~~',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:error_name' => 'Error name~~',
	'Class:lnkErrorToFunctionalCI/Attribute:error_name+' => '',
	'Class:lnkDocumentToError/Attribute:document_name' => 'Document Name~~',
	'Class:lnkDocumentToError/Attribute:document_name+' => '',
	'Class:lnkDocumentToError/Attribute:error_name' => 'Error name~~',
	'Class:lnkDocumentToError/Attribute:error_name+' => '',
	'Class:FAQ/Attribute:category_name' => 'Category name~~',
	'Class:FAQ/Attribute:category_name+' => '',
));
?>