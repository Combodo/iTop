<?php

/**
 * Localized data
 *
 * @copyright   Copyright (C) 2013 XXXXX
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

//
// Menu : ConfigManagement : PowerSupply
//

Dict::Add('FR FR', 'French', 'Français', [
	'Menu:ConfigManagement:PowerSupply' => 'Alimentations électriques',
]);

//
// Class: Inverter
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:Inverter' => 'Onduleur',
	'Class:Inverter+' => '',
	'Class:Inverter/Attribute:rack_id' => 'Rack',
	'Class:Inverter/Attribute:rack_id+' => '',
	'Class:Inverter/Attribute:autonomy' => 'Autonomie (min)',
	'Class:Inverter/Attribute:autonomy+' => 'Autonomie estimée en charge nominale',
	'Class:Inverter/Attribute:power' => 'Puissance (kVA)',
	'Class:Inverter/Attribute:power+' => 'Puissance apparente délivrable en kVA',
	'Class:Inverter/Attribute:maintenance_date' => 'Dernière maintenance',
	'Class:Inverter/Attribute:maintenance_date+' => '',
	'Class:Inverter/Attribute:battery_date' => 'Date des batteries',
	'Class:Inverter/Attribute:battery_date+' => 'Indique la date d\'installation des batteries',
	'Class:Inverter/Attribute:powerconnection_id' => 'Arrivée électrique',
	'Class:Inverter/Attribute:powerconnection_id+' => '',
	'Class:Inverter/Attribute:supply_type' => 'Type de la source',
	'Class:Inverter/Attribute:supply_type+' => '',
	'Class:Inverter/Attribute:nb_u' => 'Nombre d\'unités (U)',
	'Class:Inverter/Attribute:nb_u+' => 'Nombre d\'unités consommés dans le rack par cet équipement',
	'Class:Inverter/Attribute:position' => 'Position',
	'Class:Inverter/Attribute:position+' => 'Position dans le rack',
	'Class:Inverter/Attribute:powerstarts_list' => 'Départs électriques',
	'Class:Inverter/Attribute:powerstarts_list+' => 'Départs électriques en aval',
	'Class:Inverter/Attribute:pdus_list' => 'PDUs',
	'Class:Inverter/Attribute:pdus_list+' => 'PDUs en aval',
	'Class:Inverter/Attribute:stss_list_a' => 'ATS/STS - source A',
	'Class:Inverter/Attribute:stss_list_a+' => 'ATS/STS en aval (source A)',
	'Class:Inverter/Attribute:stss_list_b' => 'ATS/STS - source B',
	'Class:Inverter/Attribute:stss_list_b+' => 'ATS/STS en aval (source B)',

	'Class:Inverter/Attribute:obsolescence_flag' => 'Obsolète',
	'Class:Inverter/Attribute:obsolescence_flag+' => 'Calculé dynamiquement en fonction d\'autres attributs de l\'objet',
	'Class:Inverter/Attribute:rack_id_friendlyname' => 'Rack',
	'Class:Inverter/Attribute:rack_id_friendlyname+' => 'Nom complet',
	'Class:Inverter/Attribute:rack_id_obsolescence_flag' => 'Rack->Obsolète',
	'Class:Inverter/Attribute:rack_id_obsolescence_flag+' => 'Calculé dynamiquement en fonction d\'autres attributs de l\'objet',
	'Class:Inverter/Attribute:powerconnection_id_friendlyname' => 'Arrivée électrique',
	'Class:Inverter/Attribute:powerconnection_id_friendlyname+' => 'Nom complet',
	'Class:Inverter/Attribute:powerconnection_id_finalclass_recall' => 'Arrivée électrique->Sous-classe de CI',
	'Class:Inverter/Attribute:powerconnection_id_finalclass_recall+' => 'Nom de la classe instanciable',
	'Class:Inverter/Attribute:powerconnection_id_obsolescence_flag' => 'Arrivée électrique->Obsolète',
	'Class:Inverter/Attribute:powerconnection_id_obsolescence_flag+' => 'Calculé dynamiquement en fonction d\'autres attributs de l\'objet',
		'Inverter:baseinfo' => 'Informations générales',
		'Inverter:moreinfo' => 'Informations complémentaires',
		'Inverter:Date' => 'Dates',
		'Inverter:otherinfo' => 'Autres informations',
		'Inverter:technicalinfo' => 'Données techniques',
]);

//
// Class: PDU
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:PDU' => 'PDU',
	'Class:PDU+' => 'Unité de distribution d\'alimentation',
	'Class:PDU/Attribute:rack_id' => 'Rack',
	'Class:PDU/Attribute:rack_id+' => '',
	'Class:PDU/Attribute:rack_name' => 'Nom rack',
	'Class:PDU/Attribute:rack_name+' => '',
	'Class:PDU/Attribute:powerconnection_id' => 'Arrivée électrique',
	'Class:PDU/Attribute:powerconnection_id+' => '',
	'Class:PDU/Attribute:powerstart_name' => 'Nom arrivée électrique',
	'Class:PDU/Attribute:powerstart_name+' => '',
	'Class:PDU/Attribute:output_number' => 'Nombre de sortie',
	'Class:PDU/Attribute:output_number+' => '',
	'Class:PDU/Attribute:protection' => 'Protection',
	'Class:PDU/Attribute:protection+' => '',
	'Class:PDU/Attribute:protection/Value:inverter' => 'Ondulée',
	'Class:PDU/Attribute:protection/Value:inverter+' => '',
	'Class:PDU/Attribute:protection/Value:no' => 'Aucune',
	'Class:PDU/Attribute:protection/Value:no+' => '',
	'Class:PDU/Attribute:protection/Value:sts' => 'ATS/STS',
	'Class:PDU/Attribute:protection/Value:sts+' => '',
	'Class:PDU/Attribute:nb_u' => 'Nombre d\'unités (U)',
	'Class:PDU/Attribute:nb_u+' => 'Nombre d\'unités consommés par l\'équipement dans le rack',
	'Class:PDU/Attribute:position' => 'Position',
	'Class:PDU/Attribute:position+' => 'Position dans le rack',

	'Class:PDU/Attribute:obsolescence_flag' => 'Obsolète',
	'Class:PDU/Attribute:obsolescence_flag+' => 'Calculé dynamiquement en fonction d\'autres attributs de l\'objet',
	'Class:PDU/Attribute:rack_id_friendlyname' => 'Rack',
	'Class:PDU/Attribute:rack_id_friendlyname+' => 'Nom complet',
	'Class:PDU/Attribute:rack_id_obsolescence_flag' => 'Rack->Obsolète',
	'Class:PDU/Attribute:rack_id_obsolescence_flag+' => 'Calculé dynamiquement en fonction d\'autres attributs de l\'objet',
	'Class:PDU/Attribute:powerconnection_id_friendlyname' => 'Arrivée électrique',
	'Class:PDU/Attribute:powerconnection_id_friendlyname+' => 'Nom complet',
	'Class:PDU/Attribute:powerconnection_id_finalclass_recall' => 'Arrivée électrique->Sous-classe de CI',
	'Class:PDU/Attribute:powerconnection_id_finalclass_recall+' => 'Nom de la classe instanciable',
	'Class:PDU/Attribute:powerconnection_id_obsolescence_flag' => 'Arrivée électrique->Obsolète',
	'Class:PDU/Attribute:powerconnection_id_obsolescence_flag+' => 'Calculé dynamiquement en fonction d\'autres attributs de l\'objet',
		'PDU:baseinfo' => 'Informations générales',
		'PDU:moreinfo' => 'Informations complémentaires',
		'PDU:Date' => 'Dates',
		'PDU:otherinfo' => 'Autres informations',
		'PDU:technicalinfo' => 'Données techniques',
]);

//
// Class: PowerConnection
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:PowerConnection' => 'Connection électrique',
	'Class:PowerConnection+' => '',
	'Class:PowerConnection/Attribute:charge_capacity' => 'Charge maximale (A)',
	'Class:PowerConnection/Attribute:charge_capacity+' => 'Capacité de charge maximale admissible en ampère',
	'Class:PowerConnection/Attribute:power_capacity' => 'Puissance maximale (kVA)',
	'Class:PowerConnection/Attribute:power_capacity+' => 'Puissance active maximale admissible en kVA',
	'Class:PowerConnection/Attribute:power_type' => 'Type d\'alimentation',
	'Class:PowerConnection/Attribute:power_type+' => 'Type d\'alimentation fournie',
	'Class:PowerConnection/Attribute:power_type/Value:continuous' => 'continue',
	'Class:PowerConnection/Attribute:power_type/Value:continuous+' => 'continue',
	'Class:PowerConnection/Attribute:power_type/Value:single' => 'monophasée',
	'Class:PowerConnection/Attribute:power_type/Value:single+' => 'monophasée',
	'Class:PowerConnection/Attribute:power_type/Value:three' => 'triphasée',
	'Class:PowerConnection/Attribute:power_type/Value:three+' => 'triphasée',
	'Class:PowerConnection/Attribute:power_phase' => 'Phase utilisée',
	'Class:PowerConnection/Attribute:power_phase+' => 'Phase utilisée dans le cas d\'une source initiale triphasée vers monophasée',
	'Class:PowerConnection/Attribute:management_url' => 'URL de management',
	'Class:PowerConnection/Attribute:management_url+' => '',
	'Class:PowerConnection/Attribute:managementip' => 'IP de management',
	'Class:PowerConnection/Attribute:managementip+' => '',
	'Class:PowerConnection/Attribute:voltage' => 'Voltage',
	'Class:PowerConnection/Attribute:voltage+' => '',
	'Class:PowerConnection/Attribute:obsolescence_flag' => 'Obsolète',
	'Class:PowerConnection/Attribute:obsolescence_flag+' => 'Calculé dynamiquement en fonction d\'autres attributs de l\'objet',
]);

//
// Class: PowerSource
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:PowerSource' => 'Arrivée électrique',
	'Class:PowerSource+' => '',
	'Class:PowerSource/Attribute:pdus_list' => 'PDUs',
	'Class:PowerSource/Attribute:pdus_list+' => 'PDU qui utilise cette alimentation',
	'Class:PowerSource/Attribute:inverters_list' => 'Onduleurs',
	'Class:PowerSource/Attribute:inverters_list+' => 'Onduleurs en aval',
	'Class:PowerSource/Attribute:powerstarts_list' => 'Départs électriques',
	'Class:PowerSource/Attribute:powerstarts_list+' => 'Départs électriques en aval',
	'Class:PowerSource/Attribute:stss_list_a' => 'ATS/STS - source A',
	'Class:PowerSource/Attribute:stss_list_a+' => 'ATS/STS en aval (source A)',
	'Class:PowerSource/Attribute:stss_list_b' => 'ATS/STS - source B',
	'Class:PowerSource/Attribute:stss_list_b+' => 'ATS/STS en aval (source B)',
	'Class:PowerSource/Attribute:obsolescence_flag' => 'Obsolète',
	'Class:PowerSource/Attribute:obsolescence_flag+' => 'Calculé dynamiquement en fonction d\'autres attributs de l\'objet',
		'PowerSource:baseinfo' => 'Informations générales',
		'PowerSource:moreinfo' => 'Informations complémentaires',
		'PowerSource:Date' => 'Dates',
		'PowerSource:otherinfo' => 'Autres informations',
		'PowerSource:technicalinfo' => 'Données techniques',
]);

//
// Class: PowerStart
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:PowerStart' => 'Départ électrique',
	'Class:PowerStart+' => '',
	'Class:PowerStart/Attribute:powerconnection_id' => 'Source',
	'Class:PowerStart/Attribute:powerconnection_id+' => '',
	'Class:PowerStart/Attribute:supply_type' => 'Type de la source',
	'Class:PowerStart/Attribute:supply_type+' => 'Type d\'alimentation sur la source',
	'Class:PowerStart/Attribute:inverters_list' => 'Onduleurs',
	'Class:PowerStart/Attribute:inverters_list+' => 'Onduleurs en aval',
	'Class:PowerStart/Attribute:stss_list_a' => 'ATS/STS - source A',
	'Class:PowerStart/Attribute:stss_list_a+' => 'ATS/STS en aval (source A)',
	'Class:PowerStart/Attribute:stss_list_b' => 'ATS/STS - source B',
	'Class:PowerStart/Attribute:stss_list_b+' => 'ATS/STS en aval (source B)',
	'Class:PowerStart/Attribute:pdus_list' => 'PDUs',
	'Class:PowerStart/Attribute:pdus_list+' => 'PDUs en aval',
	'Class:PowerStart/Attribute:powerstarts_list' => 'Départs électriques',
	'Class:PowerStart/Attribute:powerstarts_list+' => 'Départs électriques en aval',
	'Class:PowerStart/Attribute:obsolescence_flag' => 'Obsolète',
	'Class:PowerStart/Attribute:obsolescence_flag+' => 'Calculé dynamiquement en fonction d\'autres attributs de l\'objet',
	'Class:PowerStart/Attribute:powerconnection_id_friendlyname' => 'Source',
	'Class:PowerStart/Attribute:powerconnection_id_friendlyname+' => 'Nom complet',
	'Class:PowerStart/Attribute:powerconnection_id_finalclass_recall' => 'Source->Sous-classe de CI',
	'Class:PowerStart/Attribute:powerconnection_id_finalclass_recall+' => 'Nom de la classe instanciable',
	'Class:PowerStart/Attribute:powerconnection_id_obsolescence_flag' => 'Source->Obsolète',
	'Class:PowerStart/Attribute:powerconnection_id_obsolescence_flag+' => 'Calculé dynamiquement en fonction d\'autres attributs de l\'objet',
		'PowerStart:baseinfo' => 'Informations générales',
		'PowerStart:moreinfo' => 'Informations complémentaires',
		'PowerStart:Date' => 'Dates',
		'PowerStart:otherinfo' => 'Autres informations',
		'PowerStart:technicalinfo' => 'Données techniques',
]);

//
// Class: STS
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:STS' => 'ATS / STS',
	'Class:STS+' => 'Système de Transfert Automatique de Source',
	'Class:STS/Attribute:powerconnection_source1_id' => 'Source A',
	'Class:STS/Attribute:powerconnection_source1_id+' => '',
	'Class:STS/Attribute:powerconnection_source2_id' => 'Source B',
	'Class:STS/Attribute:powerconnection_source2_id+' => '',
	'Class:STS/Attribute:nominal_source' => 'Source nominale',
	'Class:STS/Attribute:nominal_source+' => 'Source nominale utilisée par le STS',
	'Class:STS/Attribute:nominal_source/Value:source1' => 'Source A',
	'Class:STS/Attribute:nominal_source/Value:source1+' => 'Source A',
	'Class:STS/Attribute:nominal_source/Value:source2' => 'Source B',
	'Class:STS/Attribute:nominal_source/Value:source2+' => 'Source B',
	'Class:STS/Attribute:rack' => 'Rack',
	'Class:STS/Attribute:rack+' => '',
	'Class:STS/Attribute:nb_u' => 'Nombre d\'unités (U)',
	'Class:STS/Attribute:nb_u+' => 'Nombre d\'unités consommés dans le rack par cet équipement',
	'Class:STS/Attribute:position' => 'Position',
	'Class:STS/Attribute:position+' => 'Position dans le rack',
	'Class:STS/Attribute:redundancy' => 'Configuration de la redondance électrique',
	'Class:STS/Attribute:redundancy+' => '',
	'Class:STS/Attribute:pdus_list' => 'PDUs',
	'Class:STS/Attribute:pdus_list+' => 'PDUs en aval',
	'Class:STS/Attribute:obsolescence_flag' => 'Obsolète',
	'Class:STS/Attribute:obsolescence_flag+' => 'Calculé dynamiquement en fonction d\'autres attributs de l\'objet',
	'Class:STS/Attribute:powerconnection_source1_id_friendlyname' => 'Source A',
	'Class:STS/Attribute:powerconnection_source1_id_friendlyname+' => 'Nom complet',
	'Class:STS/Attribute:powerconnection_source1_id_finalclass_recall' => 'Source A->Sous-classe de CI',
	'Class:STS/Attribute:powerconnection_source1_id_finalclass_recall+' => 'Nom de la classe instanciable',
	'Class:STS/Attribute:powerconnection_source1_id_obsolescence_flag' => 'Source A->Obsolète',
	'Class:STS/Attribute:powerconnection_source1_id_obsolescence_flag+' => 'Calculé dynamiquement en fonction d\'autres attributs de l\'objet',
	'Class:STS/Attribute:powerconnection_source2_id_friendlyname' => 'Source B',
	'Class:STS/Attribute:powerconnection_source2_id_friendlyname+' => 'Nom complet',
	'Class:STS/Attribute:powerconnection_source2_id_finalclass_recall' => 'Source B->Sous-classe de CI',
	'Class:STS/Attribute:powerconnection_source2_id_finalclass_recall+' => 'Nom de la classe instanciable',
	'Class:STS/Attribute:powerconnection_source2_id_obsolescence_flag' => 'Source B->Obsolète',
	'Class:STS/Attribute:powerconnection_source2_id_obsolescence_flag+' => 'Calculé dynamiquement en fonction d\'autres attributs de l\'objet',
	'Class:STS/Attribute:rack_friendlyname' => 'Rack',
	'Class:STS/Attribute:rack_friendlyname+' => 'Nom complet',
	'Class:STS/Attribute:rack_obsolescence_flag' => 'Rack->Obsolète',
	'Class:STS/Attribute:rack_obsolescence_flag+' => 'Calculé dynamiquement en fonction d\'autres attributs de l\'objet',
		'STS:baseinfo' => 'Informations générales',
		'STS:moreinfo' => 'Informations complémentaires',
		'STS:Date' => 'Dates',
		'STS:otherinfo' => 'Autres informations',
		'STS:technicalinfo' => 'Données techniques',
]);

//
// Class: Model
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:Model/Attribute:type/Value:PDU+' => 'Unité de Distribution d\'Alimentation',
		'Class:Model/Attribute:type/Value:PowerStart' => 'Départ Electrique',
		'Class:Model/Attribute:type/Value:STS' => 'ATS/STS',
		'Class:Model/Attribute:type/Value:Inverter' => 'Onduleur',
]);
