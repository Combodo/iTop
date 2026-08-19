<?php

/**
 * Module combodo-flow-map
 *
 * @copyright   Copyright (C) 2026 XXXXX
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('NL NL', 'Dutch', 'Nederlands', [

	'Relation:dataflows/Description'    => 'Gegevensstromen tussen CIs',
	'Relation:dataflows/DownStream'     => 'Uitgaande stromen...',
	'Relation:dataflows/DownStream+'    => 'Uitgaande gegevensstromen van',
	'Relation:dataflows/UpStream'       => 'Inkomende stromen...',
	'Relation:dataflows/UpStream+'      => 'Inkomende gegevensstromen van',

	'Class:FunctionalCI/Attribute:dataflows' => 'Gegevensstromen',
	'Class:FunctionalCI/Attribute:dataflows+' => 'Gegevensstromen waarbij dit object de bron of de bestemming is.',
	'FunctionalCI:DataFlow:Title' => 'Gegevensstromen',
	'FunctionalCI:DataFlow:Inbound' => 'Inkomende stromen',
	'FunctionalCI:DataFlow:Outbound' => 'Uitgaande stromen',

	'DataFlow:moreinfo' => 'Gegevensstroom informatie',

	'Class:DataFlow' => 'Gegevensstroom',
	'Class:DataFlow+' => 'Bijvoorbeeld voor de gegevensstroom in een applicatie.',
	'Class:DataFlow/ComplementaryName' => '%1$s - %2$s',
	'Class:DataFlow/Attribute:name' => 'Naam',
	'Class:DataFlow/Attribute:name+' => 'Identificeer de gegevensstroom',
	'Class:DataFlow/Attribute:source_id' => 'Bron',
	'Class:DataFlow/Attribute:source_id+' => 'Bron CI van de gegevensstroom',
	'Class:DataFlow/Attribute:source_impact' => 'Impact van de bron?',
	'Class:DataFlow/Attribute:source_impact+' => 'Heeft de bron invloed op de gegevensstroom?',
	'Class:DataFlow/Attribute:source_impact/Value:yes' => 'Ja',
	'Class:DataFlow/Attribute:source_impact/Value:yes+' => 'Als de bron uitvalt, wordt de gegevensstroom beïnvloed.',
	'Class:DataFlow/Attribute:source_impact/Value:no' => 'Nee',
	'Class:DataFlow/Attribute:source_impact/Value:no+' => 'Als de bron uitvalt, wordt de gegevensstroom niet beïnvloed.',
	'Class:DataFlow/Attribute:destination_id' => 'Bestemming',
	'Class:DataFlow/Attribute:destination_id+' => 'Bestemmings CI van de gegevensstroom',
	'Class:DataFlow/Attribute:destination_impact' => 'Bestemming geïmpacteerd?',
	'Class:DataFlow/Attribute:destination_impact+' => 'Wordt de bestemming beïnvloed door de gegevensstroom?',
	'Class:DataFlow/Attribute:destination_impact/Value:yes' => 'Ja',
	'Class:DataFlow/Attribute:destination_impact/Value:yes+' => 'Als de gegevensstroom stopt, heeft dat gevolgen voor de bestemming.',
	'Class:DataFlow/Attribute:destination_impact/Value:no' => 'Nee',
	'Class:DataFlow/Attribute:destination_impact/Value:no+' => 'Als de gegevensstroom stopt, heeft dit geen gevolgen voor de bestemming.',
	'Class:DataFlow/Attribute:dataflowtype_id' => 'Type',
	'Class:DataFlow/Attribute:dataflowtype_id+' => 'Soort gegevensstroom',
	'Class:DataFlow/Attribute:dataflowprotocol_id' => 'Protocol',
	'Class:DataFlow/Attribute:dataflowprotocol_id+' => 'Gegevensstroomprotocol',
	'Class:DataFlow/Attribute:documentation_url' => 'Documentatie-URL',
	'Class:DataFlow/Attribute:documentation_url+' => 'URL naar de documentatie van de gegevensstroom',
	'Class:DataFlow/Attribute:last_change_date' => 'Datum laatste wijziging',
	'Class:DataFlow/Attribute:last_change_date+' => 'Datum van de laatste wijziging van de software of configuratie van de gegevensstroom',
	'Class:DataFlow/Attribute:status' => 'Status',
	'Class:DataFlow/Attribute:status+' => '',
	'Class:DataFlow/Attribute:status/Value:active' => 'Actief',
	'Class:DataFlow/Attribute:status/Value:inactive' => 'Inactief',
	'Class:DataFlow/Attribute:execution_frequency' => 'Uitvoeringsfrequentie',
	'Class:DataFlow/Attribute:execution_frequency+' => 'Hoe vaak de gegevensstroom wordt uitgevoerd.',
	'Class:DataFlow/Attribute:execution_frequency/Value:realtime' => 'Realtime',
	'Class:DataFlow/Attribute:execution_frequency/Value:realtime+' => '',
	'Class:DataFlow/Attribute:execution_frequency/Value:ondemand' => 'Op aanvraag',
	'Class:DataFlow/Attribute:execution_frequency/Value:ondemand+' => 'Spontaan, niet gepland',
	'Class:DataFlow/Attribute:execution_frequency/Value:hourly' => 'Ieder uur',
	'Class:DataFlow/Attribute:execution_frequency/Value:hourly+' => '',
	'Class:DataFlow/Attribute:execution_frequency/Value:daily' => 'Dagelijks',
	'Class:DataFlow/Attribute:execution_frequency/Value:daily+'	=> '',
	'Class:DataFlow/Attribute:execution_frequency/Value:weekly' => 'Wekelijks',
	'Class:DataFlow/Attribute:execution_frequency/Value:weekly+' => '',
	'Class:DataFlow/Attribute:execution_frequency/Value:monthly' => 'Maandelijks',
	'Class:DataFlow/Attribute:execution_frequency/Value:monthly+' => '',
	'Class:DataFlow/Attribute:execution_frequency/Value:yearly'	=> 'Jaarlijks',
	'Class:DataFlow/Attribute:execution_frequency/Value:yearly+' => '',
	'Class:DataFlow/Attribute:documents_list+' => 'Bv: Technische specificaties, runbooks, enz.',
	'Class:DataFlow/Attribute:contacts_list+' => 'Bv: Proceseigenaar, technische ondersteuning, enz.',
	'Class:DataFlow/Error:CheckSource' => 'De bron van een gegevensstroom mag niet zelf een gegevensstroom zijn. Kies een andere bron-CI dan %1$s',
	'Class:DataFlow/Error:CheckDestination' => 'De bestemming van een dataflow mag niet zelf een gegevensstroom zijn. Kies een andere bestemmings-CI dan %1$s',

	'Class:DataFlowType' => 'Soort gegevensstroom',
	'Class:DataFlowType+' => '',

	'Class:DataFlowProtocol' => 'Gegevensstroomprotocol',
	'Class:DataFlowProtocol+' => 'Typologie van gegevensstroomprotocol',

]);
