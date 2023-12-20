<?php
// Copyright (C) 2010-2021 Combodo SARL
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
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
//
// Class: FAQ
//
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
    'Class:FAQ' => 'Tudástár',
    'Class:FAQ+' => 'Gyakran Ismételt Kérdések',
    'Class:FAQ/Attribute:title' => 'Cím',
    'Class:FAQ/Attribute:title+' => 'A tudástár címe',
    'Class:FAQ/Attribute:summary' => 'Összefoglaló',
    'Class:FAQ/Attribute:summary+' => 'Egy rövid leírás a témáról',
    'Class:FAQ/Attribute:description' => 'Leírás',
    'Class:FAQ/Attribute:description+' => 'Maga a tudástár cikk',
    'Class:FAQ/Attribute:category_id' => 'Kategória',
    'Class:FAQ/Attribute:category_id+' => '~~',
    'Class:FAQ/Attribute:category_name' => 'Kategória név',
    'Class:FAQ/Attribute:category_name+' => '~~',
    'Class:FAQ/Attribute:error_code' => 'Hibakód',
    'Class:FAQ/Attribute:error_code+' => '~~',
    'Class:FAQ/Attribute:key_words' => 'Kulcsszavak',
    'Class:FAQ/Attribute:key_words+' => 'A keresést segítő kulcsszavak',
    'Class:FAQ/Attribute:domains' => 'Hibatartomány',
));

//
// Class: FAQCategory
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
    'Class:FAQCategory' => 'Tudástár kategória',
    'Class:FAQCategory+' => '',
    'Class:FAQCategory/Attribute:name' => 'Kategória név',
    'Class:FAQCategory/Attribute:name+' => '~~',
    'Class:FAQCategory/Attribute:faq_list' => 'Tudástárak',
    'Class:FAQCategory/Attribute:faq_list+' => 'Gyakori kérdések ehhez a kategóriához kapcsolódóan',
));
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
    'Menu:ProblemManagement' => 'Problémakezelés',
    'Menu:ProblemManagement+' => '',
    'Menu:Problem:Shortcuts' => 'Gyorsgombok',
    'Menu:FAQCategory' => 'Tudástár kategória',
    'Menu:FAQCategory+' => 'Tudástár kategóriák',
    'Menu:FAQ' => 'Tudástár',
    'Menu:FAQ+' => 'Meglévő tudástárak',
    'Brick:Portal:FAQ:Menu' => 'Tudástár',
    'Brick:Portal:FAQ:Title' => 'Tudástárak',
    'Brick:Portal:FAQ:Title+' => '<p>Siet?</p><p>Nézze át a leggyakoribb kérdések listáját, és (talán) azonnal megtalálja a keresett választ.</p>',
));
