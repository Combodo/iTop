<?php

/**
 * Module combodo-flow-map
 *
 * @copyright   Copyright (C) 2013 XXXXX
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('EN US', 'English', 'English', [

	'Relation:flow/Description'                               => 'Flow maps',
	'Relation:flow/DownStream'                                => 'Sent flow...',
	'Relation:flow/UpStream'                                  => 'Received flow...',

	'Class:Flow'                                              => 'Flow',
	'Class:Flow+'                                             => 'For application flow for example',
	'Class:Flow/Name'                                         => '%1$s from %2$s to %3$s',
	'Class:Flow/Attribute:name'                               => 'Name',
	'Class:Flow/Attribute:name_id+'                           => 'Data that are transferred',
	'Class:Flow/Attribute:source_id'                          => 'Source',
	'Class:Flow/Attribute:source_id+'                         => 'Source Ci of the flow',
	'Class:Flow/Attribute:source_name'                        => 'Source name',
	'Class:Flow/Attribute:source_name+'                       => 'Name of the source Ci of the flow',
	'Class:Flow/Attribute:destination_id'                     => 'Destination',
	'Class:Flow/Attribute:destination_id+'                    => 'Destination Ci for the flow',
	'Class:Flow/Attribute:destination_name'                   => 'Destination name',
	'Class:Flow/Attribute:destination_name+'                  => 'Name of the destination CI for the flow',
	'Class:Flow/Attribute:type_id'                            => 'Flow type',
	'Class:Flow/Attribute:type_id+'                           => 'Typology of Flow.',
	'Class:Flow/Attribute:description'                        => 'Description',
	'Class:Flow/Attribute:description+'                       => 'Flow description, will appear in the summary card',
	'Class:Flow/Attribute:status'                             => 'Status',
	'Class:Flow/Attribute:status+'                            => '',
	'Class:Flow/Attribute:status/Value:active'                => 'active',
	'Class:Flow/Attribute:status/Value:inactive'              => 'inactive',
	'Class:Flow/Attribute:org_id'                             => 'Organization',
	'Class:Flow/Attribute:org_id+'                            => '',
	'Class:Flow/Attribute:business_criticity'                 => 'Business criticality',
	'Class:Flow/Attribute:business_criticity+'                => '',
	'Class:Flow/Attribute:business_criticity/Value:high'      => 'high',
	'Class:Flow/Attribute:business_criticity/Value:high+'     => '',
	'Class:Flow/Attribute:business_criticity/Value:low'       => 'low',
	'Class:Flow/Attribute:business_criticity/Value:low+'      => '',
	'Class:Flow/Attribute:business_criticity/Value:medium'    => 'medium',
	'Class:Flow/Attribute:business_criticity/Value:medium+'   => '',

	'Class:Flow/Attribute:source_id_friendlyname'             => 'source_id_friendlyname',
	'Class:Flow/Attribute:source_id_friendlyname+'            => 'Full name',
	'Class:Flow/Attribute:source_id_finalclass_recall'        => 'source_id->CI sub-class',
	'Class:Flow/Attribute:source_id_finalclass_recall+'       => 'Name of the final class',
	'Class:Flow/Attribute:source_id_obsolescence_flag'        => 'source_id->Obsolete',
	'Class:Flow/Attribute:source_id_obsolescence_flag+'       => 'Computed dynamically on other attributes',
	'Class:Flow/Attribute:destination_id_friendlyname'        => 'destination_id_friendlyname',
	'Class:Flow/Attribute:destination_id_friendlyname+'       => 'Full name',
	'Class:Flow/Attribute:destination_id_finalclass_recall'   => 'destination_id->CI sub-class',
	'Class:Flow/Attribute:destination_id_finalclass_recall+'  => 'Name of the final class',
	'Class:Flow/Attribute:destination_id_obsolescence_flag'   => 'destination_id->Obsolete',
	'Class:Flow/Attribute:destination_id_obsolescence_flag+'  => 'Computed dynamically on other attributes',

]);
