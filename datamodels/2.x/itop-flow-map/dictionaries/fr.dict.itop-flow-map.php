<?php

/**
 * Module combodo-flow-map
 *
 * @copyright   Copyright (C) 2013 XXXXX
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('FR FR', 'French', 'Français', [

	'Class:FunctionalCI/Attribute:dataflows' => 'Flux de données',
	'Class:FunctionalCI/Attribute:dataflows+' => 'Flux de données dont cet objet est la source ou la destination',
	'FunctionalCI:DataFlow:Title' => 'Flux de données',
	'FunctionalCI:DataFlow:Inbound' => 'Flux entrants',
	'FunctionalCI:DataFlow:Outbound' => 'Flux sortants',
	'FunctionalCI:DataFlow:Source' => 'CI sources',
	'FunctionalCI:DataFlow:Destination' => 'CI destinataires',

	'DataFlow:baseinfo' => 'Informations générales',
	'DataFlow:otherinfo' => 'Autres informations',
	'DataFlow:moreinfo' => 'Spécificités du flux',

	'Relation:flow/Description'                              => 'Carte des flux de données',
	'Relation:flow/DownStream'                               => 'Flux sortants...',
	'Relation:flow/UpStream'                                 => 'Flux reçus...',

	'Class:DataFlow'                                             => 'Flux de Données',
	'Class:DataFlow+'                                            => 'Modélise les données transférées entre instances d\'application',
	'Class:DataFlow/Name'                                        => '%1$s',
	'Class:DataFlow/Attribute:name'                              => 'Nom',
	'Class:DataFlow/Attribute:name_id+'                          => 'Type de données transferées',
	'Class:DataFlow/Attribute:source_id'                         => 'Source',
	'Class:DataFlow/Attribute:source_id+'                        => 'Instance d\application à la source du flux de données',
	'Class:DataFlow/Attribute:destination_id'                    => 'Destinataire',
	'Class:DataFlow/Attribute:destination_id+'                   => 'Destinataire des données, à choisir parmi les instances d\'application',
	'Class:DataFlow/Attribute:type_id'                           => 'Type de flux',
	'Class:DataFlow/Attribute:type_id+'                          => 'Typologie du flux',
	'Class:DataFlow/Attribute:description'                       => 'Description',
	'Class:DataFlow/Attribute:description+'                      => '',
	'Class:DataFlow/Attribute:status'                            => 'Etat',
	'Class:DataFlow/Attribute:status+'                           => '',
	'Class:DataFlow/Attribute:status/Value:active'               => 'actif',
	'Class:DataFlow/Attribute:status/Value:inactive'             => 'inactif',
	'Class:DataFlow/Attribute:org_id'                            => 'Organisation',
	'Class:DataFlow/Attribute:org_id+'                           => '',
	'Class:DataFlow/Attribute:business_criticity'                => 'Criticité',
	'Class:DataFlow/Attribute:business_criticity+'               => '',
	'Class:DataFlow/Attribute:business_criticity/Value:high'     => 'haute',
	'Class:DataFlow/Attribute:business_criticity/Value:high+'    => '',
	'Class:DataFlow/Attribute:business_criticity/Value:low'      => 'basse',
	'Class:DataFlow/Attribute:business_criticity/Value:low+'     => '',
	'Class:DataFlow/Attribute:business_criticity/Value:medium'   => 'moyenne',
	'Class:DataFlow/Attribute:business_criticity/Value:medium+'  => '',
	'Class:DataFlow/Attribute:execution_frequency' => 'Fréquence d\'exécution',
	'Class:DataFlow/Attribute:execution_frequency+' => 'À quelle fréquence le transfert de données est-il exécuté',
	'Class:DataFlow/Attribute:execution_frequency/Value:real_time' => 'temps réel',
	'Class:DataFlow/Attribute:execution_frequency/Value:real_time+' => '',
	'Class:DataFlow/Attribute:execution_frequency/Value:hourly' => 'horaire',
	'Class:DataFlow/Attribute:execution_frequency/Value:hourly+' => '',
	'Class:DataFlow/Attribute:execution_frequency/Value:daily' => 'journalière',
	'Class:DataFlow/Attribute:execution_frequency/Value:daily+'	=> '',
	'Class:DataFlow/Attribute:execution_frequency/Value:weekly' => 'hebdomadaire',
	'Class:DataFlow/Attribute:execution_frequency/Value:weekly+' => '',
	'Class:DataFlow/Attribute:execution_frequency/Value:monthly' => 'mensuelle',
	'Class:DataFlow/Attribute:execution_frequency/Value:monthly+' => '',
	'Class:DataFlow/Attribute:execution_frequency/Value:yearly'	=> 'annuelle',
	'Class:DataFlow/Attribute:execution_frequency/Value:yearly+' => '',
/*
	'Class:DataFlow/Attribute:source_id_friendlyname'            => 'source_id_friendlyname',
	'Class:DataFlow/Attribute:source_id_friendlyname+'           => 'Nom complet',
	'Class:DataFlow/Attribute:source_id_finalclass_recall'       => 'source_id->CI sub-class',
	'Class:DataFlow/Attribute:source_id_finalclass_recall+'      => 'Classe finale',
	'Class:DataFlow/Attribute:source_id_obsolescence_flag'       => 'source_id->Obsolete',
	'Class:DataFlow/Attribute:source_id_obsolescence_flag+'      => 'Computed dynamically on other attributes',
	'Class:DataFlow/Attribute:destination_id_friendlyname'       => 'destination_id_friendlyname',
	'Class:DataFlow/Attribute:destination_id_friendlyname+'      => 'Nom complet',
	'Class:DataFlow/Attribute:destination_id_finalclass_recall'  => 'destination_id->CI sub-class',
	'Class:DataFlow/Attribute:destination_id_finalclass_recall+' => 'Classe finale',
	'Class:DataFlow/Attribute:destination_id_obsolescence_flag'  => 'destination_id->Obsolete',
	'Class:DataFlow/Attribute:destination_id_obsolescence_flag+' => 'Computed dynamically on other attributes',
*/
]);
