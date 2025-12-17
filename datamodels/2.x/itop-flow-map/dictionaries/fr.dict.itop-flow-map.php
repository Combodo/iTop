<?php

/**
 * Module combodo-flow-map
 *
 * @copyright   Copyright (C) 2013 XXXXX
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('FR FR', 'French', 'Français', [

	'Relation:flow/Description'                              => 'Carte des fluxs',
	'Relation:flow/DownStream'                               => 'Flux émis...',
	'Relation:flow/UpStream'                                 => 'Flux reçus...',

	'Class:Flow'                                             => 'Flux',
	'Class:Flow+'                                            => 'Modélise les fluxs d\'informations entre applications par exemple, mais aussi n\'importe quel autre type de flux entre CI',
	'Class:Flow/Name'                                        => '%1$s de %2$s à %3$s',
	'Class:Flow/Attribute:name'                              => 'Nom',
	'Class:Flow/Attribute:name_id+'                          => 'Type de données transferées',
	'Class:Flow/Attribute:source_id'                         => 'Source',
	'Class:Flow/Attribute:source_id+'                        => 'Ci source du flux, le plus souvent une instance d\'application',
	'Class:Flow/Attribute:source_name'                       => 'Nom de la source ',
	'Class:Flow/Attribute:source_name+'                      => 'Nom du CI à la source du flux',
	'Class:Flow/Attribute:destination_id'                    => 'Destinataire',
	'Class:Flow/Attribute:destination_id+'                   => 'Ci destinataire du flux, le plus souvent une instance d\'application',
	'Class:Flow/Attribute:destination_name'                  => 'Nom du destinataire',
	'Class:Flow/Attribute:destination_name+'                 => 'Nom du Ci destinataire du flux',
	'Class:Flow/Attribute:type_id'                           => 'Type de flux',
	'Class:Flow/Attribute:type_id+'                          => 'Typologie du flux',
	'Class:Flow/Attribute:description'                       => 'Description',
	'Class:Flow/Attribute:description+'                      => 'Description du flux, apparaitra dans les informations résumées ',
	'Class:Flow/Attribute:status'                            => 'Etat',
	'Class:Flow/Attribute:status+'                           => '',
	'Class:Flow/Attribute:status/Value:active'               => 'actif',
	'Class:Flow/Attribute:status/Value:inactive'             => 'inactif',
	'Class:Flow/Attribute:org_id'                            => 'Organisation',
	'Class:Flow/Attribute:org_id+'                           => '',
	'Class:Flow/Attribute:business_criticity'                => 'Criticité',
	'Class:Flow/Attribute:business_criticity+'               => '',
	'Class:Flow/Attribute:business_criticity/Value:high'     => 'haute',
	'Class:Flow/Attribute:business_criticity/Value:high+'    => '',
	'Class:Flow/Attribute:business_criticity/Value:low'      => 'basse',
	'Class:Flow/Attribute:business_criticity/Value:low+'     => '',
	'Class:Flow/Attribute:business_criticity/Value:medium'   => 'moyenne',
	'Class:Flow/Attribute:business_criticity/Value:medium+'  => '',

	'Class:Flow/Attribute:source_id_friendlyname'            => 'source_id_friendlyname',
	'Class:Flow/Attribute:source_id_friendlyname+'           => 'Nom complet',
	'Class:Flow/Attribute:source_id_finalclass_recall'       => 'source_id->CI sub-class',
	'Class:Flow/Attribute:source_id_finalclass_recall+'      => 'Classe finale',
	'Class:Flow/Attribute:source_id_obsolescence_flag'       => 'source_id->Obsolete',
	'Class:Flow/Attribute:source_id_obsolescence_flag+'      => 'Computed dynamically on other attributes',
	'Class:Flow/Attribute:destination_id_friendlyname'       => 'destination_id_friendlyname',
	'Class:Flow/Attribute:destination_id_friendlyname+'      => 'Nom complet',
	'Class:Flow/Attribute:destination_id_finalclass_recall'  => 'destination_id->CI sub-class',
	'Class:Flow/Attribute:destination_id_finalclass_recall+' => 'Classe finale',
	'Class:Flow/Attribute:destination_id_obsolescence_flag'  => 'destination_id->Obsolete',
	'Class:Flow/Attribute:destination_id_obsolescence_flag+' => 'Computed dynamically on other attributes',
]);
