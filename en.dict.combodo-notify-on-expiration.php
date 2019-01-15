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

// ExpirationRule
Dict::Add('EN US', 'English', 'English', array(
	// Class
	'Class:ExpirationRule/Name' => '%1$s',
	'Class:ExpirationRule' => 'Expiration rule',
	'Class:ExpirationRule+' => '',
	'Class:ExpirationRule/Attribute:name' => 'Name',
	'Class:ExpirationRule/Attribute:name+' => '',
	'Class:ExpirationRule/Attribute:class' => 'Class',
	'Class:ExpirationRule/Attribute:class+' => '',
	'Class:ExpirationRule/Attribute:description' => 'Description',
	'Class:ExpirationRule/Attribute:description+' => '',
	'Class:ExpirationRule/Attribute:status' => 'Status',
	'Class:ExpirationRule/Attribute:status+' => '',
	'Class:ExpirationRule/Attribute:status/Value:active' => 'Active',
	'Class:ExpirationRule/Attribute:status/Value:inactive' => 'Inactive',
	'Class:ExpirationRule/Attribute:type' => 'Applied option',
	'Class:ExpirationRule/Attribute:type+' => 'Which option will be used regarding the filled fields. If both are filled, advanced option is applied',
	'Class:ExpirationRule/Attribute:type/Value:simple' => 'Simple',
	'Class:ExpirationRule/Attribute:type/Value:advanced' => 'Advanced',
	'Class:ExpirationRule/Attribute:date_to_check_att' => 'Date to check',
	'Class:ExpirationRule/Attribute:date_to_check_att+' => 'Attribute code of the date to check',
	'Class:ExpirationRule/Attribute:term_of_notice' => 'Term of notice',
	'Class:ExpirationRule/Attribute:term_of_notice+' => 'Number of days before the date to check, to trigger the notification',
	'Class:ExpirationRule/Attribute:oql_scope' => 'OQL scope',
	'Class:ExpirationRule/Attribute:oql_scope+' => 'OQL query to define which objects are concerned by this rule (stimulus to apply). Note that the OQL will automatically be restricted to the states in which the stimulus is available.',

	// Integrity errors
	'Class:ExpirationRule/Error:ClassNotValid' => 'Class must be a valid class from datamodel, "%1$s" given',
	'Class:ExpirationRule/Error:AttributeNotValid' => '"%2$s" is not a valid attribute for class "%1$s"',
	'Class:ExpirationRule/Error:AttributeMustBeDate' => '"%2$s" must be a date attribute of class "%1$s"',
	'Class:ExpirationRule/Error:NoOptionFilled' => 'Either option 1 or option 2 must be filled',
	'Class:ExpirationRule/Error:OptionOneMissingField' => 'All fields of option 1 must be filled',

	// Presentation
	'ExpirationRule:general' => 'General informations',
	'ExpirationRule:simple' => 'Fill either option 1 (simple) ...',
	'ExpirationRule:advanced' => '... or option 2 (advanced)',

	// Menus
	'Menu:ExpirationRule' => 'Expiration rules',
	'Menu:ExpirationRule+' => 'Expiration rules',

	// Tabs
	'UI:ExpiredObject:Preview' => 'Preview',
	'UI:ExpiredObject:Title' => '%1$s will enter their term of notice today',
		
	'Class:TriggerOnExpirationRule' => 'Trigger (on expiration)',
	'Class:TriggerOnExpirationRule+' => 'Trigger activated when an object is in scope of an expiration rule',
	
));
