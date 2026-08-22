<?php

/**
 * Module combodo-flow-map
 *
 * @copyright   Copyright (C) 2013-2026 XXXXX
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('DE DE', 'German', 'Deutsch', [

	'Relation:dataflows/Description'    => 'Datenflüsse zwischen CIs',
	'Relation:dataflows/DownStream'     => 'Ausgehende Flüsse...',
	'Relation:dataflows/DownStream+'    => 'Karte der ausgehenden Flüsse ab',
	'Relation:dataflows/UpStream'       => 'Eingehende Flüsse...',
	'Relation:dataflows/UpStream+'      => 'Karte der eingehenden Flüsse bis',

	'Class:FunctionalCI/Attribute:dataflows' => 'Datenflüsse',
	'Class:FunctionalCI/Attribute:dataflows+' => 'Datenflüsse, bei denen dieses Objekt Quelle oder Ziel ist',
	'FunctionalCI:DataFlow:Title' => 'Datenflüsse',
	'FunctionalCI:DataFlow:Inbound' => 'Eingehende Flüsse',
	'FunctionalCI:DataFlow:Outbound' => 'Ausgehende Flüsse',

	'DataFlow:moreinfo' => 'Flussspezifische Angaben',

	'Class:DataFlow' => 'Fluss',
	'Class:DataFlow+' => 'Zum Beispiel für Anwendungsflüsse',
	'Class:DataFlow/ComplementaryName' => '%1$s - %2$s',
	'Class:DataFlow/Attribute:name' => 'Name',
	'Class:DataFlow/Attribute:name+' => 'Bezeichnet den übertragenen Datenfluss',
	'Class:DataFlow/Attribute:source_id' => 'Quelle',
	'Class:DataFlow/Attribute:source_id+' => 'Quell-CI des Flusses',
	'Class:DataFlow/Attribute:source_impact' => 'Quelle wirkt sich aus?',
	'Class:DataFlow/Attribute:source_impact+' => 'Wirkt sich die Quelle auf den Fluss aus?',
	'Class:DataFlow/Attribute:source_impact/Value:yes' => 'ja',
	'Class:DataFlow/Attribute:source_impact/Value:yes+' => 'Fällt die Quelle aus, ist der Fluss beeinträchtigt',
	'Class:DataFlow/Attribute:source_impact/Value:no' => 'nein',
	'Class:DataFlow/Attribute:source_impact/Value:no+' => 'Fällt die Quelle aus, ist der Fluss nicht beeinträchtigt',
	'Class:DataFlow/Attribute:destination_id' => 'Ziel',
	'Class:DataFlow/Attribute:destination_id+' => 'Ziel-CI des Flusses',
	'Class:DataFlow/Attribute:destination_impact' => 'Ziel betroffen',
	'Class:DataFlow/Attribute:destination_impact+' => 'Ist das Ziel vom Fluss betroffen?',
	'Class:DataFlow/Attribute:destination_impact/Value:yes' => 'ja',
	'Class:DataFlow/Attribute:destination_impact/Value:yes+' => 'Stoppt der Fluss, ist das Ziel beeinträchtigt',
	'Class:DataFlow/Attribute:destination_impact/Value:no' => 'nein',
	'Class:DataFlow/Attribute:destination_impact/Value:no+' => 'Stoppt der Fluss, ist das Ziel nicht beeinträchtigt',
	'Class:DataFlow/Attribute:dataflowtype_id' => 'Flusstyp',
	'Class:DataFlow/Attribute:dataflowtype_id+' => 'Werte aus der Typologie der Datenflusstypen',
	'Class:DataFlow/Attribute:dataflowprotocol_id' => 'Flussprotokoll',
	'Class:DataFlow/Attribute:dataflowprotocol_id+' => 'Werte aus der Typologie der Datenflussprotokolle',
	'Class:DataFlow/Attribute:documentation_url' => 'Dokumentations-URL',
	'Class:DataFlow/Attribute:documentation_url+' => 'URL zur Dokumentation des Datenflusses',
	'Class:DataFlow/Attribute:last_change_date' => 'Datum der letzten Änderung',
	'Class:DataFlow/Attribute:last_change_date+' => 'Zeitpunkt, zu dem die Software oder Konfiguration des Datenflusses zuletzt aktualisiert wurde',
	'Class:DataFlow/Attribute:status' => 'Status',
	'Class:DataFlow/Attribute:status+' => '',
	'Class:DataFlow/Attribute:status/Value:active' => 'aktiv',
	'Class:DataFlow/Attribute:status/Value:inactive' => 'inaktiv',
	'Class:DataFlow/Attribute:execution_frequency' => 'Ausführungshäufigkeit',
	'Class:DataFlow/Attribute:execution_frequency+' => 'Wie oft der Datenfluss ausgeführt wird',
	'Class:DataFlow/Attribute:execution_frequency/Value:realtime' => 'in Echtzeit',
	'Class:DataFlow/Attribute:execution_frequency/Value:realtime+' => '',
	'Class:DataFlow/Attribute:execution_frequency/Value:ondemand' => 'bei Bedarf',
	'Class:DataFlow/Attribute:execution_frequency/Value:ondemand+' => 'spontan, nicht eingeplant',
	'Class:DataFlow/Attribute:execution_frequency/Value:hourly' => 'stündlich',
	'Class:DataFlow/Attribute:execution_frequency/Value:hourly+' => '',
	'Class:DataFlow/Attribute:execution_frequency/Value:daily' => 'täglich',
	'Class:DataFlow/Attribute:execution_frequency/Value:daily+'	=> '',
	'Class:DataFlow/Attribute:execution_frequency/Value:weekly' => 'wöchentlich',
	'Class:DataFlow/Attribute:execution_frequency/Value:weekly+' => '',
	'Class:DataFlow/Attribute:execution_frequency/Value:monthly' => 'monatlich',
	'Class:DataFlow/Attribute:execution_frequency/Value:monthly+' => '',
	'Class:DataFlow/Attribute:execution_frequency/Value:yearly'	=> 'jährlich',
	'Class:DataFlow/Attribute:execution_frequency/Value:yearly+' => '',
	'Class:DataFlow/Attribute:documents_list+' => 'Z. B. technische Spezifikationen, Runbooks usw.',
	'Class:DataFlow/Attribute:contacts_list+' => 'Z. B. Verantwortlicher für den Fluss, technischer Support usw.',
	'Class:DataFlow/Error:CheckSource' => 'Die Quelle eines Datenflusses kann nicht selbst ein Datenfluss sein. Wählen Sie ein anderes Quell-CI als %1$s',
	'Class:DataFlow/Error:CheckDestination' => 'Das Ziel eines Datenflusses kann nicht selbst ein Datenfluss sein. Wählen Sie ein anderes Ziel-CI als %1$s',

	'Class:DataFlowType' => 'Datenflusstyp',
	'Class:DataFlowType+' => 'Typologie der Datenflüsse',

	'Class:DataFlowProtocol' => 'Datenflussprotokoll',
	'Class:DataFlowProtocol+' => 'Typologie der Datenflussprotokolle',

]);
