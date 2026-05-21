<?php

/**
 * Module combodo-flow-map
 *
 * @copyright   Copyright (C) 2013 XXXXX
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('FR FR', 'French', 'Français', [

	'Relation:dataflows/Description'    => 'Flux de données entre CIs',
	'Relation:dataflows/DownStream'     => 'Flux sortants...',
	'Relation:dataflows/DownStream+'    => 'Carte des flux sortants depuis',
	'Relation:dataflows/UpStream'       => 'Flux entrants...',
	'Relation:dataflows/UpStream+'      => 'Carte des flux entrants vers',

	'Class:FunctionalCI/Attribute:dataflows' => 'Flux de données',
	'Class:FunctionalCI/Attribute:dataflows+' => 'Flux de données dont cet objet est la source ou la destination',
	'FunctionalCI:DataFlow:Title' => 'Flux de données',
	'FunctionalCI:DataFlow:Inbound' => 'Flux entrants',
	'FunctionalCI:DataFlow:Outbound' => 'Flux sortants',

	'DataFlow:moreinfo' => 'Spécificités du flux',

	'Class:DataFlow'                                             => 'Flux de Données',
	'Class:DataFlow+'                                            => 'Modélise les données transférées entre instances d\'application ou plus généralement entre CIs.',
	'Class:DataFlow/ComplementaryName' => '%1$s - %2$s',
	'Class:DataFlow/Attribute:name'                              => 'Nom',
	'Class:DataFlow/Attribute:name+'                          => 'Identifie le flux de données',
	'Class:DataFlow/Attribute:source_id'                         => 'Source',
	'Class:DataFlow/Attribute:source_id+'                        => 'Instance d\application à la source du flux de données',
	'Class:DataFlow/Attribute:source_impact' => 'Source impactante ?',
	'Class:DataFlow/Attribute:source_impact+' => 'La source impacte-t-elle le flux de données ?',
	'Class:DataFlow/Attribute:source_impact/Value:yes' => 'oui',
	'Class:DataFlow/Attribute:source_impact/Value:yes+' => 'Si la source tombe en panne, le flux de données est impacté',
	'Class:DataFlow/Attribute:source_impact/Value:no' => 'non',
	'Class:DataFlow/Attribute:source_impact/Value:no+' => 'Si la source tombe en panne, le flux de données n\'est pas impacté',
	'Class:DataFlow/Attribute:destination_id'                    => 'Destinataire',
	'Class:DataFlow/Attribute:destination_id+'                   => 'Destinataire des données, à choisir parmi les instances d\'application',
	'Class:DataFlow/Attribute:destination_impact' => 'Destinataire impacté ?',
	'Class:DataFlow/Attribute:destination_impact+' => 'Le destinataire est-il impacté si le flux de données s\'arrête ?',
	'Class:DataFlow/Attribute:destination_impact/Value:yes' => 'oui',
	'Class:DataFlow/Attribute:destination_impact/Value:yes+' => 'Si le flux s\'arrête, le destinataire est impacté',
	'Class:DataFlow/Attribute:destination_impact/Value:no' => 'non',
	'Class:DataFlow/Attribute:destination_impact/Value:no+' => 'Si le flux s\'arrête, le destinataire n\'est pas impacté',
	'Class:DataFlow/Attribute:dataflowtype_id'                           => 'Type de flux',
	'Class:DataFlow/Attribute:dataflowtype_id+'                          => 'Typologie du flux',
	'Class:DataFlow/Attribute:status'                            => 'Etat',
	'Class:DataFlow/Attribute:status+'                           => '',
	'Class:DataFlow/Attribute:status/Value:active'               => 'actif',
	'Class:DataFlow/Attribute:status/Value:inactive'             => 'inactif',
	'Class:DataFlow/Attribute:execution_frequency' => 'Fréquence d\'exécution',
	'Class:DataFlow/Attribute:execution_frequency+' => 'À quelle fréquence le transfert de données est-il exécuté',
	'Class:DataFlow/Attribute:execution_frequency/Value:realtime' => 'temps réel',
	'Class:DataFlow/Attribute:execution_frequency/Value:realtime+' => '',
	'Class:DataFlow/Attribute:execution_frequency/Value:ondemand' => 'à la demande',
	'Class:DataFlow/Attribute:execution_frequency/Value:ondemand+' => '',
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
	'Class:DataFlow/Attribute:documents_list+' => 'Eg: technical specifications, runbooks, etc.',
	'Class:DataFlow/Attribute:contacts_list+' => 'Eg: flow owner, technical support, etc.',
	'Class:DataFlow/Error:CheckSource' => 'La source d\'un flux de données ne peut pas être un flux de données elle-même. Choisissez un autre CI source que %1$s',
	'Class:DataFlow/Error:CheckDestination' => 'La destination d\'un flux de données ne peut pas être un flux de données elle-même. Choisissez un autre CI destination que %1$s',

	'Class:DataFlowType' => 'Type de flux',
	'Class:DataFlowType+' => 'Typologie des flux de données',

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
