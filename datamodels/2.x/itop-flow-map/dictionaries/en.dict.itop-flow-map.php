<?php

/**
 * Module combodo-flow-map
 *
 * @copyright   Copyright (C) 2013 XXXXX
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('EN US', 'English', 'English', [

	'Class:FunctionalCI/Attribute:dataflows' => 'Data flows',
	'Class:FunctionalCI/Attribute:dataflows+' => 'Data flows for which this object is the source or the destination',
	'FunctionalCI:DataFlow:Title' => 'Data flows',
	'FunctionalCI:DataFlow:Inbound' => 'Inbound flows',
	'FunctionalCI:DataFlow:Outbound' => 'Outbound flows',

	'DataFlow:moreinfo' => 'Flow specifics',

	'Class:DataFlow' => 'Flow',
	'Class:DataFlow+' => 'For application flow for example',
	'Class:DataFlow/Name' => '%1$s - %2$s',
	'Class:DataFlow/Attribute:name' => 'Name',
	'Class:DataFlow/Attribute:name+' => 'Identify the transferred data flow',
	'Class:DataFlow/Attribute:source_id' => 'Source',
	'Class:DataFlow/Attribute:source_id+' => 'Source Ci of the flow',
	'Class:DataFlow/Attribute:source_impact' => 'Source impacts?',
	'Class:DataFlow/Attribute:source_impact+' => 'Does the source impact the flow?',
	'Class:DataFlow/Attribute:source_impact/Value:yes' => 'yes',
	'Class:DataFlow/Attribute:source_impact/Value:yes+' => 'If the source falls down, the flow is impacted',
	'Class:DataFlow/Attribute:source_impact/Value:no' => 'no',
	'Class:DataFlow/Attribute:source_impact/Value:no+' => 'If the source falls down, the flow is not impacted',
	'Class:DataFlow/Attribute:destination_id' => 'Destination',
	'Class:DataFlow/Attribute:destination_id+' => 'Destination Ci for the flow',
	'Class:DataFlow/Attribute:destination_impact' => 'Destination impacted',
	'Class:DataFlow/Attribute:destination_impact+' => 'Is the destination impacted by the flow ?',
	'Class:DataFlow/Attribute:destination_impact/Value:yes' => 'yes',
	'Class:DataFlow/Attribute:destination_impact/Value:yes+' => 'If the flow stops, the destination is impacted',
	'Class:DataFlow/Attribute:destination_impact/Value:no' => 'no',
	'Class:DataFlow/Attribute:destination_impact/Value:no+' => 'If the flow stops, the destination is not impacted',
	'Class:DataFlow/Attribute:dataflowtype_id' => 'Flow type',
	'Class:DataFlow/Attribute:dataflowtype_id+' => 'Typology of Flow.',
	'Class:DataFlow/Attribute:description' => 'Description',
	'Class:DataFlow/Attribute:description+' => '',
	'Class:DataFlow/Attribute:status' => 'Status',
	'Class:DataFlow/Attribute:status+' => '',
	'Class:DataFlow/Attribute:status/Value:active' => 'active',
	'Class:DataFlow/Attribute:status/Value:inactive' => 'inactive',
	'Class:DataFlow/Attribute:execution_frequency' => 'Execution frequency',
	'Class:DataFlow/Attribute:execution_frequency+' => 'How often the data flow is executed',
	'Class:DataFlow/Attribute:execution_frequency/Value:realtime' => 'real-time',
	'Class:DataFlow/Attribute:execution_frequency/Value:realtime+' => '',
	'Class:DataFlow/Attribute:execution_frequency/Value:ondemand' => 'on demand',
	'Class:DataFlow/Attribute:execution_frequency/Value:ondemand+' => 'on the fly, not scheduled',
	'Class:DataFlow/Attribute:execution_frequency/Value:hourly' => 'hourly',
	'Class:DataFlow/Attribute:execution_frequency/Value:hourly+' => '',
	'Class:DataFlow/Attribute:execution_frequency/Value:daily' => 'daily',
	'Class:DataFlow/Attribute:execution_frequency/Value:daily+'	=> '',
	'Class:DataFlow/Attribute:execution_frequency/Value:weekly' => 'weekly',
	'Class:DataFlow/Attribute:execution_frequency/Value:weekly+' => '',
	'Class:DataFlow/Attribute:execution_frequency/Value:monthly' => 'monthly',
	'Class:DataFlow/Attribute:execution_frequency/Value:monthly+' => '',
	'Class:DataFlow/Attribute:execution_frequency/Value:yearly'	=> 'yearly',
	'Class:DataFlow/Attribute:execution_frequency/Value:yearly+' => '',
	'Class:DataFlow/Attribute:documents_list+' => 'Eg: technical specifications, runbooks, etc.',
	'Class:DataFlow/Attribute:contacts_list+' => 'Eg: flow owner, technical support, etc.',
	'Class:DataFlow/Error:CheckSource' => 'The source of a data flow cannot be a data flow itself. Choose another source CI than %1$s',
	'Class:DataFlow/Error:CheckDestination' => 'The destination of a data flow cannot be a data flow itself. Choose another destination CI than %1$s',

/*
	'Class:DataFlow/Attribute:source_id_friendlyname'             => 'source_id_friendlyname',
	'Class:DataFlow/Attribute:source_id_friendlyname+'            => 'Full name',
	'Class:DataFlow/Attribute:source_id_finalclass_recall'        => 'source_id->CI sub-class',
	'Class:DataFlow/Attribute:source_id_finalclass_recall+'       => 'Name of the final class',
	'Class:DataFlow/Attribute:source_id_obsolescence_flag'        => 'source_id->Obsolete',
	'Class:DataFlow/Attribute:source_id_obsolescence_flag+'       => 'Computed dynamically on other attributes',
	'Class:DataFlow/Attribute:destination_id_friendlyname'        => 'destination_id_friendlyname',
	'Class:DataFlow/Attribute:destination_id_friendlyname+'       => 'Full name',
	'Class:DataFlow/Attribute:destination_id_finalclass_recall'   => 'destination_id->CI sub-class',
	'Class:DataFlow/Attribute:destination_id_finalclass_recall+'  => 'Name of the final class',
	'Class:DataFlow/Attribute:destination_id_obsolescence_flag'   => 'destination_id->Obsolete',
	'Class:DataFlow/Attribute:destination_id_obsolescence_flag+'  => 'Computed dynamically on other attributes',
*/
]);
