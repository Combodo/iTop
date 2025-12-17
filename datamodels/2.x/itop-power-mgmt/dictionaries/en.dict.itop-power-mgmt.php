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

Dict::Add('EN US', 'English', 'English', array(
	'Menu:ConfigManagement:PowerSupply' => 'Power supplies',
));

//
// Class: Inverter
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Inverter' => 'Inverter',
	'Class:Inverter+' => '',
	'Class:Inverter/Attribute:rack_id' => 'Rack',
	'Class:Inverter/Attribute:rack_id+' => '',
	'Class:Inverter/Attribute:autonomy' => 'Autonomy (min)',
	'Class:Inverter/Attribute:autonomy+' => 'Estimated autonomy at nominal load',
	'Class:Inverter/Attribute:power' => 'Power (kVA)',
	'Class:Inverter/Attribute:power+' => 'Apparent power deliverable in kVA',
	'Class:Inverter/Attribute:maintenance_date' => 'Last maintenance',
	'Class:Inverter/Attribute:maintenance_date+' => '',
	'Class:Inverter/Attribute:battery_date' => 'Batterys\' date',
	'Class:Inverter/Attribute:battery_date+' => 'Indicates the date of batteries\' installation',
	'Class:Inverter/Attribute:powerconnection_id' => 'Power supply',
	'Class:Inverter/Attribute:powerconnection_id+' => '',
	'Class:Inverter/Attribute:supply_type' => 'Source type',
	'Class:Inverter/Attribute:supply_type+' => 'Type of power supplied',
	'Class:Inverter/Attribute:nb_u' => 'Number of units (U)',
	'Class:Inverter/Attribute:nb_u+' => 'Number of units consumed in the rack by this equipment',
	'Class:Inverter/Attribute:position' => 'Position',
	'Class:Inverter/Attribute:position+' => 'Position in the rack',
	'Class:Inverter/Attribute:powerstarts_list' => 'Power start',
	'Class:Inverter/Attribute:powerstarts_list+' => 'Downstream power start',
	'Class:Inverter/Attribute:pdus_list' => 'PDUs',
	'Class:Inverter/Attribute:pdus_list+' => 'Downstream PDUs',
	'Class:Inverter/Attribute:stss_list_a' => 'ATS/STS - source A',
	'Class:Inverter/Attribute:stss_list_a+' => 'Downstream ATS/STS (source A)',
	'Class:Inverter/Attribute:stss_list_b' => 'ATS/STS - source B',
	'Class:Inverter/Attribute:stss_list_b+' => 'Downstream ATS/STS (source B)',
	
	'Class:Inverter/Attribute:obsolescence_flag' => 'Obsolete',
	'Class:Inverter/Attribute:obsolescence_flag+' => 'Computed dynamically on other attributes',
	'Class:Inverter/Attribute:rack_id_friendlyname' => 'Rack',
	'Class:Inverter/Attribute:rack_id_friendlyname+' => 'Full name',
	'Class:Inverter/Attribute:rack_id_obsolescence_flag' => 'Rack->Obsolete',
	'Class:Inverter/Attribute:rack_id_obsolescence_flag+' => 'Computed dynamically on other attributes',
	'Class:Inverter/Attribute:powerconnection_id_friendlyname' => 'Power supply',
	'Class:Inverter/Attribute:powerconnection_id_friendlyname+' => 'Full name',
	'Class:Inverter/Attribute:powerconnection_id_finalclass_recall' => 'Power supply->CI sub-class',
	'Class:Inverter/Attribute:powerconnection_id_finalclass_recall+' => 'Name of the final class',
	'Class:Inverter/Attribute:powerconnection_id_obsolescence_flag' => 'Power supply->Obsolete',
	'Class:Inverter/Attribute:powerconnection_id_obsolescence_flag+' => 'Computed dynamically on other attributes',
        'Inverter:baseinfo' => 'General informations',
        'Inverter:moreinfo' => 'Additional informations',
        'Inverter:technicalinfo' => 'Technical informations',
        'Inverter:Date' => 'Dates',
        'Inverter:otherinfo' => 'Other informations',
));

//
// Class: PDU
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:PDU' => 'PDU',
	'Class:PDU+' => 'Power distribution unit. A type of Power Connection.',
	'Class:PDU/Attribute:rack_id' => 'Rack',
	'Class:PDU/Attribute:rack_id+' => '',
	'Class:PDU/Attribute:rack_name' => 'Rack name',
	'Class:PDU/Attribute:rack_name+' => '',
	'Class:PDU/Attribute:powerconnection_id' => 'Electric supply',
	'Class:PDU/Attribute:powerconnection_id+' => '',
	'Class:PDU/Attribute:powerstart_name' => 'Power start name',
	'Class:PDU/Attribute:powerstart_name+' => '',
	'Class:PDU/Attribute:output_number' => 'Number of output',
	'Class:PDU/Attribute:output_number+' => '',
	'Class:PDU/Attribute:protection' => 'Protection',
	'Class:PDU/Attribute:protection+' => '',
	'Class:PDU/Attribute:protection/Value:inverter' => 'Uninterruptible',
	'Class:PDU/Attribute:protection/Value:inverter+' => '',
	'Class:PDU/Attribute:protection/Value:no' => 'No',
	'Class:PDU/Attribute:protection/Value:no+' => '',
	'Class:PDU/Attribute:protection/Value:sts' => 'ATS/STS',
	'Class:PDU/Attribute:protection/Value:sts+' => '',
	'Class:PDU/Attribute:nb_u' => 'Number of units (U)',
	'Class:PDU/Attribute:nb_u+' => 'Number of units consumed by the equipment in the rack',
	'Class:PDU/Attribute:position' => 'Position',
	'Class:PDU/Attribute:position+' => 'Position in the rack',

	'Class:PDU/Attribute:obsolescence_flag' => 'Obsolete',
	'Class:PDU/Attribute:obsolescence_flag+' => 'Computed dynamically on other attributes',
	'Class:PDU/Attribute:rack_id_friendlyname' => 'Rack',
	'Class:PDU/Attribute:rack_id_friendlyname+' => 'Full name',
	'Class:PDU/Attribute:rack_id_obsolescence_flag' => 'Rack->Obsolete',
	'Class:PDU/Attribute:rack_id_obsolescence_flag+' => 'Computed dynamically on other attributes',
	'Class:PDU/Attribute:powerconnection_id_friendlyname' => 'Electric supply',
	'Class:PDU/Attribute:powerconnection_id_friendlyname+' => 'Full name',
	'Class:PDU/Attribute:powerconnection_id_finalclass_recall' => 'Electric supply->CI sub-class',
	'Class:PDU/Attribute:powerconnection_id_finalclass_recall+' => 'Name of the final class',
	'Class:PDU/Attribute:powerconnection_id_obsolescence_flag' => 'Electric supply->Obsolete',
	'Class:PDU/Attribute:powerconnection_id_obsolescence_flag+' => 'Computed dynamically on other attributes',
        'PDU:baseinfo' => 'General informations',
        'PDU:moreinfo' => 'Additional informations',
        'PDU:technicalinfo' => 'Technical informations',
        'PDU:Date' => 'Dates',
        'PDU:otherinfo' => 'Other informations',
));

//
// Class: PowerConnection
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:PowerConnection' => 'Power Connection',
	'Class:PowerConnection+' => 'Abstract class, grouping all power devices',
	'Class:PowerConnection/Attribute:charge_capacity' => 'Maximum load (A)',
	'Class:PowerConnection/Attribute:charge_capacity+' => 'Maximum admissible load capacity in amperes',
	'Class:PowerConnection/Attribute:power_capacity' => 'Maximum power (kVA)',
	'Class:PowerConnection/Attribute:power_capacity+' => 'Maximum admissible active power in kVA',
	'Class:PowerConnection/Attribute:power_type' => 'Power type',
	'Class:PowerConnection/Attribute:power_type+' => 'Type of power supplied',
	'Class:PowerConnection/Attribute:power_type/Value:continuous' => 'Continuous',
	'Class:PowerConnection/Attribute:power_type/Value:continuous+' => 'Continuous',
	'Class:PowerConnection/Attribute:power_type/Value:single' => 'single phase',
	'Class:PowerConnection/Attribute:power_type/Value:single+' => 'single phase',
	'Class:PowerConnection/Attribute:power_type/Value:three' => 'three phase',
	'Class:PowerConnection/Attribute:power_type/Value:three+' => 'three phase',
	'Class:PowerConnection/Attribute:charge_current' => 'Current charge',
	'Class:PowerConnection/Attribute:charge_current+' => 'Load currently in use',
	'Class:PowerConnection/Attribute:power_current' => 'Currently power',
	'Class:PowerConnection/Attribute:power_current+' => 'Active power currently in use',
	'Class:PowerConnection/Attribute:power_phase' => 'Phase used',
	'Class:PowerConnection/Attribute:power_phase+' => 'Phase used in the case of an initial three-phase to single-phase source',
	'Class:PowerConnection/Attribute:management_url' => 'Management URL',
	'Class:PowerConnection/Attribute:management_url+' => '',
	'Class:PowerConnection/Attribute:managementip' => 'Management IP',
	'Class:PowerConnection/Attribute:managementip+' => '',
	'Class:PowerConnection/Attribute:voltage' => 'Voltage',
	'Class:PowerConnection/Attribute:voltage+' => '',
	'Class:PowerConnection/Attribute:obsolescence_flag' => 'Obsolete',
	'Class:PowerConnection/Attribute:obsolescence_flag+' => 'Computed dynamically on other attributes',
));

//
// Class: PowerSource
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:PowerSource' => 'Power Source',
	'Class:PowerSource+' => 'First Power Connection documented in a power circuit, 
It has no electrical source documented as an object in the CMDB.',
	'Class:PowerSource/Attribute:pdus_list' => 'PDUs',
	'Class:PowerSource/Attribute:pdus_list+' => 'All the PDUs using this power source',
	'Class:PowerSource/Attribute:inverters_list' => 'Inverter',
	'Class:PowerSource/Attribute:inverters_list+' => 'Downstream inverters',
	'Class:PowerSource/Attribute:powerstarts_list' => 'Power start',
	'Class:PowerSource/Attribute:powerstarts_list+' => 'Downstream power start',
	'Class:PowerSource/Attribute:stss_list_a' => 'ATS/STS - source A',
	'Class:PowerSource/Attribute:stss_list_a+' => 'Downstream ATS/STS (source A)',
	'Class:PowerSource/Attribute:stss_list_b' => 'ATS/STS - source B',
	'Class:PowerSource/Attribute:stss_list_b+' => 'Downstream ATS (source B)',
	'Class:PowerSource/Attribute:obsolescence_flag' => 'Obsolete',
	'Class:PowerSource/Attribute:obsolescence_flag+' => 'Computed dynamically on other attributes',
        'PowerSource:baseinfo' => 'General informations',
        'PowerSource:moreinfo' => 'Additional informations',
        'PowerSource:technicalinfo' => 'Technical informations',
        'PowerSource:Date' => 'Dates',
        'PowerSource:otherinfo' => 'Other informations',
        
));

//
// Class: PowerStart
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:PowerStart' => 'Power Start',
	'Class:PowerStart+' => '',
	'Class:PowerStart/Attribute:powerconnection_id' => 'Source',
	'Class:PowerStart/Attribute:powerconnection_id+' => '',
	'Class:PowerStart/Attribute:supply_type' => 'Source type',
	'Class:PowerStart/Attribute:supply_type+' => 'Type of power supply on the source',
	'Class:PowerStart/Attribute:inverters_list' => 'Inverters',
	'Class:PowerStart/Attribute:inverters_list+' => 'Dowstream inverters',
	'Class:PowerStart/Attribute:stss_list_a' => 'ATS/STS - source A',
	'Class:PowerStart/Attribute:stss_list_a+' => 'Downstream ATS/STS (source A)',
	'Class:PowerStart/Attribute:stss_list_b' => 'ATS/STS - source B',
	'Class:PowerStart/Attribute:stss_list_b+' => 'Downstream ATS/STS (source B)',
	'Class:PowerStart/Attribute:pdus_list' => 'PDUs',
	'Class:PowerStart/Attribute:pdus_list+' => 'Downstream PDUs',
	'Class:PowerStart/Attribute:powerstarts_list' => 'powerstarts list',
	'Class:PowerStart/Attribute:powerstarts_list+' => '',
	'Class:PowerStart/Attribute:obsolescence_flag' => 'Obsolete',
	'Class:PowerStart/Attribute:obsolescence_flag+' => 'Computed dynamically on other attributes',
	'Class:PowerStart/Attribute:powerconnection_id_friendlyname' => 'Source',
	'Class:PowerStart/Attribute:powerconnection_id_friendlyname+' => 'Full name',
	'Class:PowerStart/Attribute:powerconnection_id_finalclass_recall' => 'Source->CI sub-class',
	'Class:PowerStart/Attribute:powerconnection_id_finalclass_recall+' => 'Name of the final class',
	'Class:PowerStart/Attribute:powerconnection_id_obsolescence_flag' => 'Source->Obsolete',
	'Class:PowerStart/Attribute:powerconnection_id_obsolescence_flag+' => 'Computed dynamically on other attributes',
        'PowerStart:baseinfo' => 'General informations',
        'PowerStart:moreinfo' => 'Additional informations',
        'PowerStart:technicalinfo' => 'Technical informations',
        'PowerStart:Date' => 'Dates',
        'PowerStart:otherinfo' => 'Other informations',
));

//
// Class: STS
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:STS' => 'ATS / STS',
	'Class:STS+' => 'Automatic Transfer Switch / Static Transfer Switch
Redondant power supply for devices with a single electrical input.
It can supply PDU(s)
They are themselves supplied with two different Power Source, through Power Connection ',
	'Class:STS/Attribute:powerconnection_source1_id' => 'Source A',
	'Class:STS/Attribute:powerconnection_source1_id+' => '',
	'Class:STS/Attribute:powerconnection_source2_id' => 'Source B',
	'Class:STS/Attribute:powerconnection_source2_id+' => '',
	'Class:STS/Attribute:nominal_source' => 'Nominal source',
	'Class:STS/Attribute:nominal_source+' => 'Nominal source used by STS',
	'Class:STS/Attribute:nominal_source/Value:source1' => 'Source A',
	'Class:STS/Attribute:nominal_source/Value:source1+' => '',
	'Class:STS/Attribute:nominal_source/Value:source2' => 'Source B',
	'Class:STS/Attribute:nominal_source/Value:source2+' => '',
	'Class:STS/Attribute:rack' => 'Rack',
	'Class:STS/Attribute:rack+' => '',
	'Class:STS/Attribute:nb_u' => 'Number of units (U)',
	'Class:STS/Attribute:nb_u+' => 'Number of units consumed in the rack by this equipment',
	'Class:STS/Attribute:position' => 'Position',
	'Class:STS/Attribute:position+' => 'Position in the rack',
	'Class:STS/Attribute:redundancy' => 'Configuration of electrical redundancy',
	'Class:STS/Attribute:redundancy+' => '',
	'Class:STS/Attribute:pdus_list' => 'PDUs',
	'Class:STS/Attribute:pdus_list+' => 'Downstream PDUs',

	'Class:STS/Attribute:obsolescence_flag' => 'Obsolete',
	'Class:STS/Attribute:obsolescence_flag+' => 'Computed dynamically on other attributes',
	'Class:STS/Attribute:powerconnection_source1_id_friendlyname' => 'Source A',
	'Class:STS/Attribute:powerconnection_source1_id_friendlyname+' => 'Full name',
	'Class:STS/Attribute:powerconnection_source1_id_finalclass_recall' => 'Source A->CI sub-class',
	'Class:STS/Attribute:powerconnection_source1_id_finalclass_recall+' => 'Name of the final class',
	'Class:STS/Attribute:powerconnection_source1_id_obsolescence_flag' => 'Source A->Obsolete',
	'Class:STS/Attribute:powerconnection_source1_id_obsolescence_flag+' => 'Computed dynamically on other attributes',
	'Class:STS/Attribute:powerconnection_source2_id_friendlyname' => 'Source B',
	'Class:STS/Attribute:powerconnection_source2_id_friendlyname+' => 'Full name',
	'Class:STS/Attribute:powerconnection_source2_id_finalclass_recall' => 'Source B->CI sub-class',
	'Class:STS/Attribute:powerconnection_source2_id_finalclass_recall+' => 'Name of the final class',
	'Class:STS/Attribute:powerconnection_source2_id_obsolescence_flag' => 'Source B->Obsolete',
	'Class:STS/Attribute:powerconnection_source2_id_obsolescence_flag+' => 'Computed dynamically on other attributes',
	'Class:STS/Attribute:rack_friendlyname' => 'Rack',
	'Class:STS/Attribute:rack_friendlyname+' => 'Full name',
	'Class:STS/Attribute:rack_obsolescence_flag' => 'Rack->Obsolete',
	'Class:STS/Attribute:rack_obsolescence_flag+' => 'Computed dynamically on other attributes',
        'STS:baseinfo' => 'General informations',
        'STS:moreinfo' => 'Additional informations',
        'STS:technicalinfo' => 'Technical informations',
        'STS:Date' => 'Dates',
        'STS:otherinfo' => 'Other informations',
));

//
// Class: Model
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Model/Attribute:type/Value:PDU+' => 'Power Distribution Unit',
        'Class:Model/Attribute:type/Value:PowerStart' => 'Power Start',
        'Class:Model/Attribute:type/Value:STS' => 'ATS/STS',
        'Class:Model/Attribute:type/Value:Inverter' => 'Inverter',
));
