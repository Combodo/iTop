<?php

/**
 * Localized data
 *
 * @copyright   Copyright (C) 2010-2019 Combodo SARL
 * @license     https://www.combodo.com/documentation/combodo-software-license.html
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @author      Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @author      Vincent Dumas <vincent.dumas@combodo.com>
 */

// ClosingRule
Dict::Add('FR FR', 'French', 'Français', array(
	// Class
	'Class:ExpirationRule/Name' => '%1$s',
	'Class:ExpirationRule' => 'Régle de Préavis',
	'Class:ExpirationRule+' => '',
	'Class:ExpirationRule/Attribute:name' => 'Nom',
	'Class:ExpirationRule/Attribute:name+' => '',
	'Class:ExpirationRule/Attribute:class' => 'Classe',
	'Class:ExpirationRule/Attribute:class+' => '',
	'Class:ExpirationRule/Attribute:description' => 'Description',
	'Class:ExpirationRule/Attribute:description+' => '',
	'Class:ExpirationRule/Attribute:status' => 'Statut',
	'Class:ExpirationRule/Attribute:status+' => '',
	'Class:ExpirationRule/Attribute:status/Value:active' => 'Active',
	'Class:ExpirationRule/Attribute:status/Value:inactive' => 'Inactive',
	'Class:ExpirationRule/Attribute:type' => 'Option retenue',
	'Class:ExpirationRule/Attribute:type+' => 'Quelle option sera utilisée au regard des champs remplis. Si les 2 options sont remplies, l\'option avancée sera appliquée',
	'Class:ExpirationRule/Attribute:type/Value:simple' => 'Simple',
	'Class:ExpirationRule/Attribute:type/Value:advanced' => 'Avancée',
	'Class:ExpirationRule/Attribute:date_to_check_att' => 'Date à utiliser',
	'Class:ExpirationRule/Attribute:date_to_check_att+' => 'Code du champ date à controler',
	'Class:ExpirationRule/Attribute:term_of_notice' => 'Préavis en jours',
	'Class:ExpirationRule/Attribute:term_of_notice+' => 'Nombre de jours avant la date pour déclencher la notification',
	'Class:ExpirationRule/Attribute:oql_scope' => 'Périmêtre en OQL',
	'Class:ExpirationRule/Attribute:oql_scope+' => 'Requête OQL définissant les objets concernés par cette règle (trigger à déclencher).',
	
	// Integrity errors
	'Class:ExpirationRule/Error:ClassNotValid' => 'La classe doit faire partie du modèle de données, "%1$s" donnée',
	'Class:ExpirationRule/Error:AttributeNotValid' => '"%2$s" n\'est pas un attribut valide pour la classe "%1$s"',
	'Class:ExpirationRule/Error:AttributeMustBeDate' => '"%2$s" doit être un attribut de type date pour la classe "%1$s"',
	'Class:ExpirationRule/Error:NoOptionFilled' => 'Une des 2 options doit être remplie',
	'Class:ExpirationRule/Error:OptionOneMissingField' => 'Tous les champs de l\'option 1 doivent être remplis',
	
	// Presentation
	'ExpirationRule:general' => 'Informations générales',
	'ExpirationRule:simple' => 'Remplir l\'option (simple) ...',
	'ExpirationRule:advanced' => '... oo l\'option 2 (avancée)',
	
	// Menus
	'Menu:ExpirationRule' => 'Régles de préavis',
	'Menu:ExpirationRule+' => 'Régles de préavis',
	
	// Tabs
	'UI:ExpiredObject:Preview' => 'Aperçu',
	'UI:ExpiredObject:Title' => '%1$s ont leur préavis qui démarre aujourd\'hui',

	'Class:TriggerOnExpirationRule' => 'Déclencheur sur préavis atteint',
	'Class:TriggerOnExpirationRule+' => 'Déclencheur activé lorsqu\'une régle de préavis est applicable à l\'objet',
		
));
