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
	'Class:KnownError' => 'Известные ошибки',
	'Class:KnownError+' => 'Ошибки задокументированные как известные',
	'Class:KnownError/Attribute:name' => 'Название',
	'Class:KnownError/Attribute:name+' => '',
	'Class:KnownError/Attribute:org_id' => 'Клинт',
	'Class:KnownError/Attribute:org_id+' => '',
	'Class:KnownError/Attribute:cust_name' => 'Имя клиента',
	'Class:KnownError/Attribute:cust_name+' => '',
	'Class:KnownError/Attribute:problem_id' => 'Связанная проблема',
	'Class:KnownError/Attribute:problem_id+' => '',
	'Class:KnownError/Attribute:problem_ref' => 'Ссылка',
	'Class:KnownError/Attribute:problem_ref+' => '',
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
));


//
// Class: lnkInfraError
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkInfraError' => 'InfraErrorLinks',
	'Class:lnkInfraError+' => 'Infra относящаяся к известной ошибке',
	'Class:lnkInfraError/Attribute:infra_id' => 'КЕ',
	'Class:lnkInfraError/Attribute:infra_id+' => '',
	'Class:lnkInfraError/Attribute:infra_name' => 'Название КЕ',
	'Class:lnkInfraError/Attribute:infra_name+' => '',
	'Class:lnkInfraError/Attribute:infra_status' => 'Статус КЕ',
	'Class:lnkInfraError/Attribute:infra_status+' => '',
	'Class:lnkInfraError/Attribute:error_id' => 'Ошибка',
	'Class:lnkInfraError/Attribute:error_id+' => '',
	'Class:lnkInfraError/Attribute:error_name' => 'Название ошибки',
	'Class:lnkInfraError/Attribute:error_name+' => '',
	'Class:lnkInfraError/Attribute:reason' => 'Причина',
	'Class:lnkInfraError/Attribute:reason+' => '',
));

//
// Class: lnkDocumentError
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkDocumentError' => 'DocumentsErrorLinks',
	'Class:lnkDocumentError+' => 'Связь между документом и известной ошибкой',
	'Class:lnkDocumentError/Attribute:doc_id' => 'Документ',
	'Class:lnkDocumentError/Attribute:doc_id+' => '',
	'Class:lnkDocumentError/Attribute:doc_name' => 'Название документа',
	'Class:lnkDocumentError/Attribute:doc_name+' => '',
	'Class:lnkDocumentError/Attribute:error_id' => 'Ошибка',
	'Class:lnkDocumentError/Attribute:error_id+' => '',
	'Class:lnkDocumentError/Attribute:error_name' => 'Название ошибки',
	'Class:lnkDocumentError/Attribute:error_name+' => '',
	'Class:lnkDocumentError/Attribute:link_type' => 'Информация',
	'Class:lnkDocumentError/Attribute:link_type+' => '',
));

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Menu:NewError' => 'Новая известная ошибка',
	'Menu:NewError+' => 'Создание новой известной ошибки',
	'Menu:SearchError' => 'Поиск известных ошибок',
	'Menu:SearchError+' => 'Поиск известных ошибок',
        'Menu:Problem:KnownErrors' => 'Все известные ошибки',
        'Menu:Problem:KnownErrors+' => 'Все известные ошибки',
));
?>
