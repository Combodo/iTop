<?php

/**
 * Module combodo-flow-map
 *
 * @copyright   Copyright (C) 2013 XXXXX
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('FR FR', 'French', 'Français', [

	'Relation:flow/Description'                              => 'Carte des fluxs',
	'Relation:flow/DownStream'                               => 'Impacte flux...',
	'Relation:flow/UpStream'                                 => 'Dépend de flux...',

	'Class:Flow'                                             => 'Flux',
	'Class:Flow+'                                            => 'Pour les fluxs applicatifs par exemple',
	'Class:Flow/Name'                                        => '%1$s %2$s %3$s',
	'Class:Flow/Attribute:source_id'                         => 'Source',
	'Class:Flow/Attribute:source_id+'                        => 'Ci source du flux',
	'Class:Flow/Attribute:source_name'                       => 'Nom de la source ',
	'Class:Flow/Attribute:source_name+'                      => 'Nom du CI à la source du flux',
	'Class:Flow/Attribute:destination_id'                    => 'Destination',
	'Class:Flow/Attribute:destination_id+'                   => 'Ci destinataire du flux',
	'Class:Flow/Attribute:destination_name'                  => 'Nom du destinataire',
	'Class:Flow/Attribute:destination_name+'                 => 'Nom du Ci destinataire du flux',
	'Class:Flow/Attribute:type_id'                           => 'Type de flux',
	'Class:Flow/Attribute:type_id+'                          => 'Type de flux : http, https, ftp..., apparaitra dans le nom commun',
	'Class:Flow/Attribute:description'                       => 'Description',
	'Class:Flow/Attribute:description+'                      => 'Description du flux, apparaitra dans les informations résumées ',
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
	'Class:Flow/Attribute:status'                            => 'Etat',
	'Class:Flow/Attribute:status+'                           => '',
	'Class:Flow/Attribute:status/Value:active'               => 'Actif',
	'Class:Flow/Attribute:status/Value:inactive'             => 'Inactif',
	'Class:Flow/Attribute:org_id'                            => 'Organisation',
	'Class:Flow/Attribute:org_id+'                           => '',

]);
