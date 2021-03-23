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
	'Class:FAQ/Attribute:category_name' => 'Категория',
	'Class:FAQ/Attribute:category_name+' => '',
	'Class:FAQ/Attribute:error_code' => 'Код ошибки',
	'Class:FAQ/Attribute:error_code+' => '',
	'Class:FAQ/Attribute:key_words' => 'Ключевые слова',
	'Class:FAQ/Attribute:key_words+' => '',
	'Class:FAQ/Attribute:domains' => 'Домены',
));

//
// Class: FAQCategory
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:FAQCategory' => 'Категории FAQ',
	'Class:FAQCategory+' => 'Категории FAQ',
	'Class:FAQCategory/Attribute:name' => 'Название',
	'Class:FAQCategory/Attribute:name+' => '',
	'Class:FAQCategory/Attribute:faq_list' => 'FAQ',
	'Class:FAQCategory/Attribute:faq_list+' => 'Связанные FAQ',
));
Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Menu:ProblemManagement' => 'Управление проблемами',
	'Menu:ProblemManagement+' => 'Управление проблемами',
	'Menu:Problem:Shortcuts' => 'Ярлыки',
	'Menu:FAQCategory' => 'Категории FAQ',
	'Menu:FAQCategory+' => 'Категории FAQ',
	'Menu:FAQ' => 'FAQ',
	'Menu:FAQ+' => 'Часто задаваемые вопросы',
	'Brick:Portal:FAQ:Menu' => 'FAQ',
	'Brick:Portal:FAQ:Title' => 'Часто задаваемые вопросы',
	'Brick:Portal:FAQ:Title+' => '<p>Торопитесь?</p><p>Проверьте список часто задаваемых вопросов, возможно, ответ уже есть.</p>',
));
