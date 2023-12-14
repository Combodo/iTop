<?php
// Copyright (C) 2010-2023 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>
/**
 * Localized data
 *
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
//////////////////////////////////////////////////////////////////////
// Relations
//////////////////////////////////////////////////////////////////////
//
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Relation:impacts/Description'    => 'Elementos impactados por',
	'Relation:impacts/DownStream'     => 'Impacto...',
	'Relation:impacts/DownStream+'    => 'Elementos impactados por',
	'Relation:impacts/UpStream'       => 'Depende de...',
	'Relation:impacts/UpStream+'      => 'Elementos estes, que dependem deste elemento',
	// Legacy entries
	'Relation:depends on/Description' => 'Elementos estes, que dependem deste elemento',
	'Relation:depends on/DownStream'  => 'Depende de...',
	'Relation:depends on/UpStream'    => 'Impactos...',
	'Relation:impacts/LoadData'       => 'Load data~~',
	'Relation:impacts/NoFilteredData' => 'please select objects and load data~~',
	'Relation:impacts/FilteredData'   => 'Filtered data~~',
));


// Dictionnay conventions
// Class:<class_name>
// Class:<class_name>+
// Class:<class_name>/Attribute:<attribute_code>
// Class:<class_name>/Attribute:<attribute_code>+
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>+
// Class:<class_name>/Stimulus:<stimulus_code>
// Class:<class_name>/Stimulus:<stimulus_code>+
// Class:<class_name>/UniquenessRule:<rule_code>
// Class:<class_name>/UniquenessRule:<rule_code>+

//////////////////////////////////////////////////////////////////////
// Classes in 'bizmodel'
//////////////////////////////////////////////////////////////////////
//

// Dictionnay conventions
// Class:<class_name>
// Class:<class_name>+
// Class:<class_name>/Attribute:<attribute_code>
// Class:<class_name>/Attribute:<attribute_code>+
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>+
// Class:<class_name>/Stimulus:<stimulus_code>
// Class:<class_name>/Stimulus:<stimulus_code>+
// Class:<class_name>/UniquenessRule:<rule_code>
// Class:<class_name>/UniquenessRule:<rule_code>+

//////////////////////////////////////////////////////////////////////
// Note: The classes have been grouped by categories: bizmodel
//////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////
// Classes in 'bizmodel'
//////////////////////////////////////////////////////////////////////
//

//
// Class: lnkContactToFunctionalCI
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkContactToFunctionalCI' => 'Link Contato / IC',
	'Class:lnkContactToFunctionalCI+' => '',
	'Class:lnkContactToFunctionalCI/Name' => '%1$s / %2$s~~',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id' => 'ICs',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name' => 'Nome do IC',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id' => 'Contato',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name' => 'Nome do contato',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name+' => '',
));

//
// Class: FunctionalCI
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:FunctionalCI' => 'Item de Configuração',
	'Class:FunctionalCI+' => '',
	'Class:FunctionalCI/Attribute:name' => 'Nome',
	'Class:FunctionalCI/Attribute:name+' => '',
	'Class:FunctionalCI/Attribute:description' => 'Descrição',
	'Class:FunctionalCI/Attribute:description+' => '',
	'Class:FunctionalCI/Attribute:org_id' => 'Organização',
	'Class:FunctionalCI/Attribute:org_id+' => '',
	'Class:FunctionalCI/Attribute:organization_name' => 'Nome da organização',
	'Class:FunctionalCI/Attribute:organization_name+' => '',
	'Class:FunctionalCI/Attribute:business_criticity' => 'Criticidade ao negócio',
	'Class:FunctionalCI/Attribute:business_criticity+' => '',
	'Class:FunctionalCI/Attribute:business_criticity/Value:high' => 'Alta',
	'Class:FunctionalCI/Attribute:business_criticity/Value:high+' => '',
	'Class:FunctionalCI/Attribute:business_criticity/Value:low' => 'Baixa',
	'Class:FunctionalCI/Attribute:business_criticity/Value:low+' => '',
	'Class:FunctionalCI/Attribute:business_criticity/Value:medium' => 'Média',
	'Class:FunctionalCI/Attribute:business_criticity/Value:medium+' => '',
	'Class:FunctionalCI/Attribute:move2production' => 'Data de migração para produção',
	'Class:FunctionalCI/Attribute:move2production+' => '',
	'Class:FunctionalCI/Attribute:contacts_list' => 'Contatos',
	'Class:FunctionalCI/Attribute:contacts_list+' => 'Todos os contatos associados a este item de configuração',
	'Class:FunctionalCI/Attribute:documents_list' => 'Documentos',
	'Class:FunctionalCI/Attribute:documents_list+' => 'Todos os documentos associados a este item de configuração',
	'Class:FunctionalCI/Attribute:applicationsolution_list' => 'Soluções de aplicação',
	'Class:FunctionalCI/Attribute:applicationsolution_list+' => 'Todas as soluções de aplicação dependentes desse item de configuração',
	'Class:FunctionalCI/Attribute:softwares_list' => 'Softwares',
	'Class:FunctionalCI/Attribute:softwares_list+' => 'Todos os softwares instalados neste item de configuração',
	'Class:FunctionalCI/Attribute:finalclass' => 'Tipo de IC',
	'Class:FunctionalCI/Attribute:finalclass+' => '',
	'Class:FunctionalCI/Tab:OpenedTickets' => 'Solicitações Ativas',
	'Class:FunctionalCI/Tab:OpenedTickets+' => 'Active Tickets which are impacting this functional CI~~',
));

//
// Class: PhysicalDevice
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:PhysicalDevice' => 'Dispositivo Físico',
	'Class:PhysicalDevice+' => 'Lista de Dispositivos Físicos',
	'Class:PhysicalDevice/ComplementaryName' => '%1$s - %2$s~~',
	'Class:PhysicalDevice/Attribute:serialnumber' => 'Número serial',
	'Class:PhysicalDevice/Attribute:serialnumber+' => '',
	'Class:PhysicalDevice/Attribute:location_id' => 'Localização',
	'Class:PhysicalDevice/Attribute:location_id+' => '',
	'Class:PhysicalDevice/Attribute:location_name' => 'Nome da localização',
	'Class:PhysicalDevice/Attribute:location_name+' => '',
	'Class:PhysicalDevice/Attribute:status' => 'Status',
	'Class:PhysicalDevice/Attribute:status+' => '',
	'Class:PhysicalDevice/Attribute:status/Value:implementation' => 'Em homologação',
	'Class:PhysicalDevice/Attribute:status/Value:implementation+' => '',
	'Class:PhysicalDevice/Attribute:status/Value:obsolete' => 'Obsoleto',
	'Class:PhysicalDevice/Attribute:status/Value:obsolete+' => '',
	'Class:PhysicalDevice/Attribute:status/Value:production' => 'Em produção',
	'Class:PhysicalDevice/Attribute:status/Value:production+' => '',
	'Class:PhysicalDevice/Attribute:status/Value:stock' => 'Suporte',
	'Class:PhysicalDevice/Attribute:status/Value:stock+' => '',
	'Class:PhysicalDevice/Attribute:brand_id' => 'Fabricante',
	'Class:PhysicalDevice/Attribute:brand_id+' => '',
	'Class:PhysicalDevice/Attribute:brand_name' => 'Nome do fabricante',
	'Class:PhysicalDevice/Attribute:brand_name+' => '',
	'Class:PhysicalDevice/Attribute:model_id' => 'Modelo',
	'Class:PhysicalDevice/Attribute:model_id+' => '',
	'Class:PhysicalDevice/Attribute:model_name' => 'Nome do modelo',
	'Class:PhysicalDevice/Attribute:model_name+' => '',
	'Class:PhysicalDevice/Attribute:asset_number' => 'Número do ativo',
	'Class:PhysicalDevice/Attribute:asset_number+' => '',
	'Class:PhysicalDevice/Attribute:purchase_date' => 'Data de compra',
	'Class:PhysicalDevice/Attribute:purchase_date+' => '',
	'Class:PhysicalDevice/Attribute:end_of_warranty' => 'Fim da garantia',
	'Class:PhysicalDevice/Attribute:end_of_warranty+' => '',
));

//
// Class: Rack
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Rack' => 'Rack',
	'Class:Rack+' => '',
	'Class:Rack/ComplementaryName' => '%1$s - %2$s~~',
	'Class:Rack/Attribute:nb_u' => 'Unidades',
	'Class:Rack/Attribute:nb_u+' => '',
	'Class:Rack/Attribute:device_list' => 'Dispositivos',
	'Class:Rack/Attribute:device_list+' => 'Todos os dispositivos físicos empilhados neste rack',
	'Class:Rack/Attribute:enclosure_list' => 'Gavetas',
	'Class:Rack/Attribute:enclosure_list+' => 'Todas as gavetas neste rack',
));

//
// Class: TelephonyCI
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:TelephonyCI' => 'Telefonia',
	'Class:TelephonyCI+' => '',
	'Class:TelephonyCI/Attribute:phonenumber' => 'Número de telefone',
	'Class:TelephonyCI/Attribute:phonenumber+' => '',
));

//
// Class: Phone
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Phone' => 'Telefone',
	'Class:Phone+' => '',
));

//
// Class: MobilePhone
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:MobilePhone' => 'Telefone Celular',
	'Class:MobilePhone+' => '',
	'Class:MobilePhone/Attribute:imei' => 'IMEI',
	'Class:MobilePhone/Attribute:imei+' => '',
	'Class:MobilePhone/Attribute:hw_pin' => 'Hardware PIN',
	'Class:MobilePhone/Attribute:hw_pin+' => '',
));

//
// Class: IPPhone
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:IPPhone' => 'Telefone IP',
	'Class:IPPhone+' => '',
));

//
// Class: Tablet
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Tablet' => 'Tablet',
	'Class:Tablet+' => '',
));

//
// Class: ConnectableCI
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:ConnectableCI' => 'Conectividade',
	'Class:ConnectableCI+' => 'Físico',
	'Class:ConnectableCI/ComplementaryName' => '%1$s - %2$s~~',
	'Class:ConnectableCI/Attribute:networkdevice_list' => 'Dispositivo de rede',
	'Class:ConnectableCI/Attribute:networkdevice_list+' => 'Todos os dispositivos de rede conectados neste dispositivo',
	'Class:ConnectableCI/Attribute:physicalinterface_list' => 'Interface de rede',
	'Class:ConnectableCI/Attribute:physicalinterface_list+' => 'Todas as interfaces de rede',
));

//
// Class: DatacenterDevice
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:DatacenterDevice' => 'Dispositivo de Datacenter',
	'Class:DatacenterDevice+' => '',
	'Class:DatacenterDevice/ComplementaryName' => '%1$s - %2$s~~',
	'Class:DatacenterDevice/Attribute:rack_id' => 'Rack',
	'Class:DatacenterDevice/Attribute:rack_id+' => '',
	'Class:DatacenterDevice/Attribute:rack_name' => 'Nome do rack',
	'Class:DatacenterDevice/Attribute:rack_name+' => '',
	'Class:DatacenterDevice/Attribute:enclosure_id' => 'Gaveta',
	'Class:DatacenterDevice/Attribute:enclosure_id+' => '',
	'Class:DatacenterDevice/Attribute:enclosure_name' => 'Nome da gaveta',
	'Class:DatacenterDevice/Attribute:enclosure_name+' => '',
	'Class:DatacenterDevice/Attribute:nb_u' => 'Unidades',
	'Class:DatacenterDevice/Attribute:nb_u+' => '',
	'Class:DatacenterDevice/Attribute:managementip' => 'IP de gerenciamento',
	'Class:DatacenterDevice/Attribute:managementip+' => '',
	'Class:DatacenterDevice/Attribute:powerA_id' => 'Fonte de energia A',
	'Class:DatacenterDevice/Attribute:powerA_id+' => '',
	'Class:DatacenterDevice/Attribute:powerA_name' => 'Nome da fonte de energia A',
	'Class:DatacenterDevice/Attribute:powerA_name+' => '',
	'Class:DatacenterDevice/Attribute:powerB_id' => 'Fonte de energia B',
	'Class:DatacenterDevice/Attribute:powerB_id+' => '',
	'Class:DatacenterDevice/Attribute:powerB_name' => 'Nome da fonte energia B',
	'Class:DatacenterDevice/Attribute:powerB_name+' => '',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list' => 'Portas FC',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list+' => 'Todas as portas Fiber Channel para esse dispositivo',
	'Class:DatacenterDevice/Attribute:san_list' => 'SANs',
	'Class:DatacenterDevice/Attribute:san_list+' => 'Todos os switches SAN associados a este dispositivo',
	'Class:DatacenterDevice/Attribute:redundancy' => 'Redundância',
	'Class:DatacenterDevice/Attribute:redundancy/count' => 'O dispositivo está ativo se pelo menos uma conexão de energia (A ou B) estiver ativa',
	// Unused yet
	'Class:DatacenterDevice/Attribute:redundancy/disabled' => 'O dispositivo está ativo se todas as conexões de energia estiverem ativadas',
	'Class:DatacenterDevice/Attribute:redundancy/percent' => 'O dispositivo está ativo se pelo menos %1$s %% de suas conexões de energia estiverem funcionando',
));

//
// Class: NetworkDevice
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:NetworkDevice' => 'Dispositivo de Rede',
	'Class:NetworkDevice+' => '',
	'Class:NetworkDevice/ComplementaryName' => '%1$s - %2$s~~',
	'Class:NetworkDevice/Attribute:networkdevicetype_id' => 'Tipo de rede',
	'Class:NetworkDevice/Attribute:networkdevicetype_id+' => '',
	'Class:NetworkDevice/Attribute:networkdevicetype_name' => 'Nome do tipo de rede',
	'Class:NetworkDevice/Attribute:networkdevicetype_name+' => '',
	'Class:NetworkDevice/Attribute:connectablecis_list' => 'Dispositivos',
	'Class:NetworkDevice/Attribute:connectablecis_list+' => 'Todos os dispositivos associados a este dispositivo de rede',
	'Class:NetworkDevice/Attribute:iosversion_id' => 'Versão do IOS',
	'Class:NetworkDevice/Attribute:iosversion_id+' => '',
	'Class:NetworkDevice/Attribute:iosversion_name' => 'Nome da versão do IOS',
	'Class:NetworkDevice/Attribute:iosversion_name+' => '',
	'Class:NetworkDevice/Attribute:ram' => 'RAM',
	'Class:NetworkDevice/Attribute:ram+' => '',
));

//
// Class: Server
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Server' => 'Servidor',
	'Class:Server+' => '',
	'Class:Server/ComplementaryName' => '%1$s - %2$s~~',
	'Class:Server/Attribute:osfamily_id' => 'Família do SO',
	'Class:Server/Attribute:osfamily_id+' => '',
	'Class:Server/Attribute:osfamily_name' => 'Nome da família do SO',
	'Class:Server/Attribute:osfamily_name+' => '',
	'Class:Server/Attribute:osversion_id' => 'Versão do OS',
	'Class:Server/Attribute:osversion_id+' => '',
	'Class:Server/Attribute:osversion_name' => 'Nome da versão do SO',
	'Class:Server/Attribute:osversion_name+' => '',
	'Class:Server/Attribute:oslicence_id' => 'Licença do SO',
	'Class:Server/Attribute:oslicence_id+' => '',
	'Class:Server/Attribute:oslicence_name' => 'Nome da licença do SO',
	'Class:Server/Attribute:oslicence_name+' => '',
	'Class:Server/Attribute:cpu' => 'CPU',
	'Class:Server/Attribute:cpu+' => '',
	'Class:Server/Attribute:ram' => 'RAM',
	'Class:Server/Attribute:ram+' => '',
	'Class:Server/Attribute:logicalvolumes_list' => 'Volumes lógicos',
	'Class:Server/Attribute:logicalvolumes_list+' => 'Todos os volumoes lógicos associados a este servidor',
));

//
// Class: StorageSystem
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:StorageSystem' => 'Sistema de Storage',
	'Class:StorageSystem+' => '',
	'Class:StorageSystem/ComplementaryName' => '%1$s - %2$s~~',
	'Class:StorageSystem/Attribute:logicalvolume_list' => 'Volumes lógicos',
	'Class:StorageSystem/Attribute:logicalvolume_list+' => 'Todos os volumes lógicos neste sistema storage',
));

//
// Class: SANSwitch
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:SANSwitch' => 'Switch SAN',
	'Class:SANSwitch+' => '',
	'Class:SANSwitch/ComplementaryName' => '%1$s - %2$s~~',
	'Class:SANSwitch/Attribute:datacenterdevice_list' => 'Dispositivos',
	'Class:SANSwitch/Attribute:datacenterdevice_list+' => 'Todos os dispositivos associados a este switch SAN',
));

//
// Class: TapeLibrary
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:TapeLibrary' => 'Biblioteca de Fitas',
	'Class:TapeLibrary+' => '',
	'Class:TapeLibrary/ComplementaryName' => '%1$s - %2$s~~',
	'Class:TapeLibrary/Attribute:tapes_list' => 'Fitas',
	'Class:TapeLibrary/Attribute:tapes_list+' => 'Todas as fitas associadas à esta biblioteca de fitas',
));

//
// Class: NAS
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:NAS' => 'NAS',
	'Class:NAS+' => '',
	'Class:NAS/ComplementaryName' => '%1$s - %2$s~~',
	'Class:NAS/Attribute:nasfilesystem_list' => 'Sistemas de arquivos',
	'Class:NAS/Attribute:nasfilesystem_list+' => 'Todos os sistemas de arquivos para esse NAS',
));

//
// Class: PC
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:PC' => 'PC',
	'Class:PC+' => '',
	'Class:PC/ComplementaryName' => '%1$s - %2$s~~',
	'Class:PC/Attribute:osfamily_id' => 'Família do SO',
	'Class:PC/Attribute:osfamily_id+' => '',
	'Class:PC/Attribute:osfamily_name' => 'Nome da família do SO',
	'Class:PC/Attribute:osfamily_name+' => '',
	'Class:PC/Attribute:osversion_id' => 'Versão do SO',
	'Class:PC/Attribute:osversion_id+' => '',
	'Class:PC/Attribute:osversion_name' => 'Nome da versão do OS',
	'Class:PC/Attribute:osversion_name+' => '',
	'Class:PC/Attribute:cpu' => 'CPU',
	'Class:PC/Attribute:cpu+' => '',
	'Class:PC/Attribute:ram' => 'RAM',
	'Class:PC/Attribute:ram+' => '',
	'Class:PC/Attribute:type' => 'Tipo',
	'Class:PC/Attribute:type+' => '',
	'Class:PC/Attribute:type/Value:desktop' => 'Desktop',
	'Class:PC/Attribute:type/Value:desktop+' => '',
	'Class:PC/Attribute:type/Value:laptop' => 'Laptop',
	'Class:PC/Attribute:type/Value:laptop+' => '',
));

//
// Class: Printer
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Printer' => 'Impressora',
	'Class:Printer+' => '',
	'Class:Printer/ComplementaryName' => '%1$s - %2$s~~',
));

//
// Class: PowerConnection
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:PowerConnection' => 'Conexão de Energia',
	'Class:PowerConnection+' => '',
	'Class:PowerConnection/ComplementaryName' => '%1$s - %2$s~~',
));

//
// Class: PowerSource
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:PowerSource' => 'Fonte de Energia',
	'Class:PowerSource+' => '',
	'Class:PowerSource/ComplementaryName' => '%1$s - %2$s~~',
	'Class:PowerSource/Attribute:pdus_list' => 'PDUs',
	'Class:PowerSource/Attribute:pdus_list+' => 'Todos os PDUs utilizando essa fonte de energia',
));

//
// Class: PDU
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:PDU' => 'PDU',
	'Class:PDU+' => '',
	'Class:PDU/ComplementaryName' => '%1$s - %2$s - %3$s - %4$s~~',
	'Class:PDU/Attribute:rack_id' => 'Rack',
	'Class:PDU/Attribute:rack_id+' => '',
	'Class:PDU/Attribute:rack_name' => 'Nome do rack',
	'Class:PDU/Attribute:rack_name+' => '',
	'Class:PDU/Attribute:powerstart_id' => 'Fonte de energia',
	'Class:PDU/Attribute:powerstart_id+' => '',
	'Class:PDU/Attribute:powerstart_name' => 'Nome da fonte de energia',
	'Class:PDU/Attribute:powerstart_name+' => '',
));

//
// Class: Peripheral
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Peripheral' => 'Periférico',
	'Class:Peripheral+' => '',
	'Class:Peripheral/ComplementaryName' => '%1$s - %2$s~~',
));

//
// Class: Enclosure
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Enclosure' => 'Gaveta',
	'Class:Enclosure+' => '',
	'Class:Enclosure/ComplementaryName' => '%1$s - %2$s - %3$s~~',
	'Class:Enclosure/Attribute:rack_id' => 'Rack',
	'Class:Enclosure/Attribute:rack_id+' => '',
	'Class:Enclosure/Attribute:rack_name' => 'Nome do rack',
	'Class:Enclosure/Attribute:rack_name+' => '',
	'Class:Enclosure/Attribute:nb_u' => 'Unidades',
	'Class:Enclosure/Attribute:nb_u+' => '',
	'Class:Enclosure/Attribute:device_list' => 'Dispositivos',
	'Class:Enclosure/Attribute:device_list+' => 'Todos os dispositivos para essa gaveta',
));

//
// Class: ApplicationSolution
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:ApplicationSolution' => 'Solução de Aplicação',
	'Class:ApplicationSolution+' => '',
	'Class:ApplicationSolution/Attribute:functionalcis_list' => 'ICs',
	'Class:ApplicationSolution/Attribute:functionalcis_list+' => 'Todos os itens de configuração que compõem essa solução de aplicação',
	'Class:ApplicationSolution/Attribute:businessprocess_list' => 'Processos de negócio',
	'Class:ApplicationSolution/Attribute:businessprocess_list+' => 'Todos os processos do negócio dependente para essa solução de aplicação',
	'Class:ApplicationSolution/Attribute:status' => 'Status',
	'Class:ApplicationSolution/Attribute:status+' => '',
	'Class:ApplicationSolution/Attribute:status/Value:active' => 'Ativo',
	'Class:ApplicationSolution/Attribute:status/Value:active+' => '',
	'Class:ApplicationSolution/Attribute:status/Value:inactive' => 'Inativo',
	'Class:ApplicationSolution/Attribute:status/Value:inactive+' => '',
	'Class:ApplicationSolution/Attribute:redundancy' => 'Análise de impacto: configuração de redundância',
	'Class:ApplicationSolution/Attribute:redundancy/disabled' => 'A solução está funcionando se todos os ICs estiverem funcionando',
	'Class:ApplicationSolution/Attribute:redundancy/count' => 'A solução está funcionando se no mínimo %1$s IC(s) estiver(em) funcionando',
	'Class:ApplicationSolution/Attribute:redundancy/percent' => 'A solução está funcionando se no mínimo %1$s %% dos ICs estiverem funcionando',
));

//
// Class: BusinessProcess
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:BusinessProcess' => 'Processo de Negócio',
	'Class:BusinessProcess+' => '',
	'Class:BusinessProcess/Attribute:applicationsolutions_list' => 'Soluções de aplicação',
	'Class:BusinessProcess/Attribute:applicationsolutions_list+' => 'Todas as soluções de aplicação que impactam este processo de negócio',
	'Class:BusinessProcess/Attribute:status' => 'Status',
	'Class:BusinessProcess/Attribute:status+' => '',
	'Class:BusinessProcess/Attribute:status/Value:active' => 'Ativo',
	'Class:BusinessProcess/Attribute:status/Value:active+' => '',
	'Class:BusinessProcess/Attribute:status/Value:inactive' => 'Inativo',
	'Class:BusinessProcess/Attribute:status/Value:inactive+' => '',
));

//
// Class: SoftwareInstance
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:SoftwareInstance' => 'Instância de Software',
	'Class:SoftwareInstance+' => '',
	'Class:SoftwareInstance/Attribute:system_id' => 'Sistema',
	'Class:SoftwareInstance/Attribute:system_id+' => '',
	'Class:SoftwareInstance/Attribute:system_name' => 'Nome do sistema',
	'Class:SoftwareInstance/Attribute:system_name+' => '',
	'Class:SoftwareInstance/Attribute:software_id' => 'Software',
	'Class:SoftwareInstance/Attribute:software_id+' => '',
	'Class:SoftwareInstance/Attribute:software_name' => 'Nome do software',
	'Class:SoftwareInstance/Attribute:software_name+' => '',
	'Class:SoftwareInstance/Attribute:softwarelicence_id' => 'Licença do software',
	'Class:SoftwareInstance/Attribute:softwarelicence_id+' => '',
	'Class:SoftwareInstance/Attribute:softwarelicence_name' => 'Nome da licença do software',
	'Class:SoftwareInstance/Attribute:softwarelicence_name+' => '',
	'Class:SoftwareInstance/Attribute:path' => 'Caminho',
	'Class:SoftwareInstance/Attribute:path+' => '',
	'Class:SoftwareInstance/Attribute:status' => 'Status',
	'Class:SoftwareInstance/Attribute:status+' => '',
	'Class:SoftwareInstance/Attribute:status/Value:active' => 'Ativo',
	'Class:SoftwareInstance/Attribute:status/Value:active+' => 'Ativo',
	'Class:SoftwareInstance/Attribute:status/Value:inactive' => 'Inativo',
	'Class:SoftwareInstance/Attribute:status/Value:inactive+' => 'Inativo',
));

//
// Class: Middleware
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Middleware' => 'Middleware',
	'Class:Middleware+' => '',
	'Class:Middleware/Attribute:middlewareinstance_list' => 'Instância Middleware',
	'Class:Middleware/Attribute:middlewareinstance_list+' => 'Todos as instância middleware fornecida por essa middleware',
));

//
// Class: DBServer
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:DBServer' => 'Servidor de DB',
	'Class:DBServer+' => '',
	'Class:DBServer/Attribute:dbschema_list' => 'Esquemas de DB',
	'Class:DBServer/Attribute:dbschema_list+' => 'Todos os esquemas para esse servidor de banco de dados',
));

//
// Class: WebServer
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:WebServer' => 'Servidor Web',
	'Class:WebServer+' => '',
	'Class:WebServer/Attribute:webapp_list' => 'Aplicações Web',
	'Class:WebServer/Attribute:webapp_list+' => 'Todas as aplicações web disponíveis para esse servidor web',
));

//
// Class: PCSoftware
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:PCSoftware' => 'Software de PC',
	'Class:PCSoftware+' => '',
));

//
// Class: OtherSoftware
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:OtherSoftware' => 'Outro Software',
	'Class:OtherSoftware+' => '',
));

//
// Class: MiddlewareInstance
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:MiddlewareInstance' => 'Instância Middleware',
	'Class:MiddlewareInstance+' => '',
	'Class:MiddlewareInstance/ComplementaryName' => '%1$s - %2$s~~',
	'Class:MiddlewareInstance/Attribute:middleware_id' => 'Middleware',
	'Class:MiddlewareInstance/Attribute:middleware_id+' => '',
	'Class:MiddlewareInstance/Attribute:middleware_name' => 'Nome do middleware',
	'Class:MiddlewareInstance/Attribute:middleware_name+' => '',
));

//
// Class: DatabaseSchema
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:DatabaseSchema' => 'Esquema de DB',
	'Class:DatabaseSchema+' => '',
	'Class:DatabaseSchema/ComplementaryName' => '%1$s - %2$s~~',
	'Class:DatabaseSchema/Attribute:dbserver_id' => 'Servidor de DB',
	'Class:DatabaseSchema/Attribute:dbserver_id+' => '',
	'Class:DatabaseSchema/Attribute:dbserver_name' => 'Nome do servidor de DB',
	'Class:DatabaseSchema/Attribute:dbserver_name+' => '',
));

//
// Class: WebApplication
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:WebApplication' => 'Aplicação Web',
	'Class:WebApplication+' => '',
	'Class:WebApplication/ComplementaryName' => '%1$s - %2$s~~',
	'Class:WebApplication/Attribute:webserver_id' => 'Servidor Web',
	'Class:WebApplication/Attribute:webserver_id+' => '',
	'Class:WebApplication/Attribute:webserver_name' => 'Nome do servidor Web',
	'Class:WebApplication/Attribute:webserver_name+' => '',
	'Class:WebApplication/Attribute:url' => 'URL',
	'Class:WebApplication/Attribute:url+' => '',
));


//
// Class: VirtualDevice
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:VirtualDevice' => 'Dispositivo Virtual',
	'Class:VirtualDevice+' => '',
	'Class:VirtualDevice/Attribute:status' => 'Status',
	'Class:VirtualDevice/Attribute:status+' => '',
	'Class:VirtualDevice/Attribute:status/Value:implementation' => 'Em homologação',
	'Class:VirtualDevice/Attribute:status/Value:implementation+' => 'Em homologação',
	'Class:VirtualDevice/Attribute:status/Value:obsolete' => 'Obsoleto',
	'Class:VirtualDevice/Attribute:status/Value:obsolete+' => 'Obsoleto',
	'Class:VirtualDevice/Attribute:status/Value:production' => 'Em produção',
	'Class:VirtualDevice/Attribute:status/Value:production+' => 'Em produção',
	'Class:VirtualDevice/Attribute:status/Value:stock' => 'Suporte',
	'Class:VirtualDevice/Attribute:status/Value:stock+' => 'Suporte',
	'Class:VirtualDevice/Attribute:logicalvolumes_list' => 'Volume lógico',
	'Class:VirtualDevice/Attribute:logicalvolumes_list+' => 'Todos os volumes lógicos associados a este dispositivo',
));

//
// Class: VirtualHost
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:VirtualHost' => 'Host Virtual',
	'Class:VirtualHost+' => '',
	'Class:VirtualHost/Attribute:virtualmachine_list' => 'Máquinas Virtuais',
	'Class:VirtualHost/Attribute:virtualmachine_list+' => 'Todas as máquinas virtuais hospedadas neste Host',
));

//
// Class: Hypervisor
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Hypervisor' => 'Hypervisor',
	'Class:Hypervisor+' => '',
	'Class:Hypervisor/Attribute:farm_id' => 'Cluster/HA',
	'Class:Hypervisor/Attribute:farm_id+' => '',
	'Class:Hypervisor/Attribute:farm_name' => 'Nome do Cluster/HA',
	'Class:Hypervisor/Attribute:farm_name+' => '',
	'Class:Hypervisor/Attribute:server_id' => 'Servidor',
	'Class:Hypervisor/Attribute:server_id+' => '',
	'Class:Hypervisor/Attribute:server_name' => 'Nome do servidor',
	'Class:Hypervisor/Attribute:server_name+' => '',
));

//
// Class: Farm
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Farm' => 'Cluster/HA',
	'Class:Farm+' => '',
	'Class:Farm/Attribute:hypervisor_list' => 'Hypervisors',
	'Class:Farm/Attribute:hypervisor_list+' => 'Todos os hypervisors que compõem esse Cluster/HA',
	'Class:Farm/Attribute:redundancy' => 'Alta disponibilidade',
	'Class:Farm/Attribute:redundancy/disabled' => 'O farm está ativo se todos os hipervisores estiverem em alta',
	'Class:Farm/Attribute:redundancy/count' => 'O farm está ativo se pelo menos %1$s hypervisor(es) estiver (ão) para cima',
	'Class:Farm/Attribute:redundancy/percent' => 'O farm está ativo se pelo menos %1$s %% dos hipervisores estiverem em alta',
));

//
// Class: VirtualMachine
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:VirtualMachine' => 'Máquina Virtual',
	'Class:VirtualMachine+' => '',
	'Class:VirtualMachine/ComplementaryName' => '%1$s - %2$s~~',
	'Class:VirtualMachine/Attribute:virtualhost_id' => 'Host virtual',
	'Class:VirtualMachine/Attribute:virtualhost_id+' => '',
	'Class:VirtualMachine/Attribute:virtualhost_name' => 'Nome do host virtual',
	'Class:VirtualMachine/Attribute:virtualhost_name+' => '',
	'Class:VirtualMachine/Attribute:osfamily_id' => 'Família do SO',
	'Class:VirtualMachine/Attribute:osfamily_id+' => '',
	'Class:VirtualMachine/Attribute:osfamily_name' => 'Nome da família do SO',
	'Class:VirtualMachine/Attribute:osfamily_name+' => '',
	'Class:VirtualMachine/Attribute:osversion_id' => 'Versão do SO',
	'Class:VirtualMachine/Attribute:osversion_id+' => '',
	'Class:VirtualMachine/Attribute:osversion_name' => 'Nome da versão do SO',
	'Class:VirtualMachine/Attribute:osversion_name+' => '',
	'Class:VirtualMachine/Attribute:oslicence_id' => 'Licença do SO',
	'Class:VirtualMachine/Attribute:oslicence_id+' => '',
	'Class:VirtualMachine/Attribute:oslicence_name' => 'Nome da licença do SO',
	'Class:VirtualMachine/Attribute:oslicence_name+' => '',
	'Class:VirtualMachine/Attribute:cpu' => 'CPU',
	'Class:VirtualMachine/Attribute:cpu+' => '',
	'Class:VirtualMachine/Attribute:ram' => 'RAM',
	'Class:VirtualMachine/Attribute:ram+' => '',
	'Class:VirtualMachine/Attribute:managementip' => 'IP',
	'Class:VirtualMachine/Attribute:managementip+' => '',
	'Class:VirtualMachine/Attribute:logicalinterface_list' => 'Placas de rede',
	'Class:VirtualMachine/Attribute:logicalinterface_list+' => 'Todas as placas de rede',
));

//
// Class: LogicalVolume
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:LogicalVolume' => 'Volume lógico',
	'Class:LogicalVolume+' => '',
	'Class:LogicalVolume/Attribute:name' => 'Nome',
	'Class:LogicalVolume/Attribute:name+' => '',
	'Class:LogicalVolume/Attribute:lun_id' => 'LUN ID',
	'Class:LogicalVolume/Attribute:lun_id+' => '',
	'Class:LogicalVolume/Attribute:description' => 'Descrição',
	'Class:LogicalVolume/Attribute:description+' => '',
	'Class:LogicalVolume/Attribute:raid_level' => 'Nível RAID',
	'Class:LogicalVolume/Attribute:raid_level+' => '',
	'Class:LogicalVolume/Attribute:size' => 'Tamanho',
	'Class:LogicalVolume/Attribute:size+' => '',
	'Class:LogicalVolume/Attribute:storagesystem_id' => 'Sistema de arquivos',
	'Class:LogicalVolume/Attribute:storagesystem_id+' => '',
	'Class:LogicalVolume/Attribute:storagesystem_name' => 'Nome do sistema de arquivos',
	'Class:LogicalVolume/Attribute:storagesystem_name+' => '',
	'Class:LogicalVolume/Attribute:servers_list' => 'Servidores',
	'Class:LogicalVolume/Attribute:servers_list+' => 'Todos os servidores usando esse volume',
	'Class:LogicalVolume/Attribute:virtualdevices_list' => 'Dispositivos virtuais',
	'Class:LogicalVolume/Attribute:virtualdevices_list+' => 'Todos os dispositivos virtuais usando esse volume',
));

//
// Class: lnkServerToVolume
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkServerToVolume' => 'Link Servidor / Volume',
	'Class:lnkServerToVolume+' => '',
	'Class:lnkServerToVolume/Name' => '%1$s / %2$s~~',
	'Class:lnkServerToVolume/Attribute:volume_id' => 'Volume',
	'Class:lnkServerToVolume/Attribute:volume_id+' => '',
	'Class:lnkServerToVolume/Attribute:volume_name' => 'Nome do volume',
	'Class:lnkServerToVolume/Attribute:volume_name+' => '',
	'Class:lnkServerToVolume/Attribute:server_id' => 'Servidor',
	'Class:lnkServerToVolume/Attribute:server_id+' => '',
	'Class:lnkServerToVolume/Attribute:server_name' => 'Nome do servidor',
	'Class:lnkServerToVolume/Attribute:server_name+' => '',
	'Class:lnkServerToVolume/Attribute:size_used' => 'Tamanho utilizado',
	'Class:lnkServerToVolume/Attribute:size_used+' => '',
));

//
// Class: lnkVirtualDeviceToVolume
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkVirtualDeviceToVolume' => 'Link Dispositivo Virtual / Volume',
	'Class:lnkVirtualDeviceToVolume+' => '',
	'Class:lnkVirtualDeviceToVolume/Name' => '%1$s / %2$s~~',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_id' => 'Volume',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_id+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_name' => 'Nome do volume',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_name+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_id' => 'Dispositivo virtual',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_id+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_name' => 'Nome do dispositivo virtual',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_name+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:size_used' => 'Tamanho utilizado',
	'Class:lnkVirtualDeviceToVolume/Attribute:size_used+' => '',
));

//
// Class: lnkSanToDatacenterDevice
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkSanToDatacenterDevice' => 'Link SAN / Dispositivo Datacenter',
	'Class:lnkSanToDatacenterDevice+' => '',
	'Class:lnkSanToDatacenterDevice/Name' => '%1$s / %2$s~~',
	'Class:lnkSanToDatacenterDevice/Attribute:san_id' => 'Switch SAN',
	'Class:lnkSanToDatacenterDevice/Attribute:san_id+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:san_name' => 'Nome do switch SAN',
	'Class:lnkSanToDatacenterDevice/Attribute:san_name+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_id' => 'Dispositivo',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_id+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_name' => 'Nome do dispositivo',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_name+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:san_port' => 'FC SAN',
	'Class:lnkSanToDatacenterDevice/Attribute:san_port+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_port' => 'Dispositivo de FC',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_port+' => '',
));

//
// Class: Tape
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Tape' => 'Fita',
	'Class:Tape+' => '',
	'Class:Tape/Attribute:name' => 'Nome',
	'Class:Tape/Attribute:name+' => '',
	'Class:Tape/Attribute:description' => 'Descrição',
	'Class:Tape/Attribute:description+' => '',
	'Class:Tape/Attribute:size' => 'Tamanho',
	'Class:Tape/Attribute:size+' => '',
	'Class:Tape/Attribute:tapelibrary_id' => 'Biblioteca de fitas',
	'Class:Tape/Attribute:tapelibrary_id+' => '',
	'Class:Tape/Attribute:tapelibrary_name' => 'Nome da biblioteca de fitas',
	'Class:Tape/Attribute:tapelibrary_name+' => '',
));

//
// Class: NASFileSystem
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:NASFileSystem' => 'Sistema de arquivos NAS',
	'Class:NASFileSystem+' => '',
	'Class:NASFileSystem/Attribute:name' => 'Nome',
	'Class:NASFileSystem/Attribute:name+' => '',
	'Class:NASFileSystem/Attribute:description' => 'Descrição',
	'Class:NASFileSystem/Attribute:description+' => '',
	'Class:NASFileSystem/Attribute:raid_level' => 'Nível RAID',
	'Class:NASFileSystem/Attribute:raid_level+' => '',
	'Class:NASFileSystem/Attribute:size' => 'Tamanho',
	'Class:NASFileSystem/Attribute:size+' => '',
	'Class:NASFileSystem/Attribute:nas_id' => 'NAS',
	'Class:NASFileSystem/Attribute:nas_id+' => '',
	'Class:NASFileSystem/Attribute:nas_name' => 'Nome do NAS',
	'Class:NASFileSystem/Attribute:nas_name+' => '',
));

//
// Class: Software
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Software' => 'Software',
	'Class:Software+' => '',
	'Class:Software/ComplementaryName' => '%1$s - %2$s~~',
	'Class:Software/Attribute:name' => 'Nome',
	'Class:Software/Attribute:name+' => '',
	'Class:Software/Attribute:vendor' => 'Fabricante',
	'Class:Software/Attribute:vendor+' => '',
	'Class:Software/Attribute:version' => 'Versão',
	'Class:Software/Attribute:version+' => '',
	'Class:Software/Attribute:documents_list' => 'Documentos',
	'Class:Software/Attribute:documents_list+' => 'Todos os documentos associados a este software',
	'Class:Software/Attribute:type' => 'Tipo',
	'Class:Software/Attribute:type+' => '',
	'Class:Software/Attribute:type/Value:DBServer' => 'Servidor de DB',
	'Class:Software/Attribute:type/Value:DBServer+' => 'Servidor de DB',
	'Class:Software/Attribute:type/Value:Middleware' => 'Middleware',
	'Class:Software/Attribute:type/Value:Middleware+' => 'Middleware',
	'Class:Software/Attribute:type/Value:OtherSoftware' => 'Outro Software',
	'Class:Software/Attribute:type/Value:OtherSoftware+' => 'Outro Software',
	'Class:Software/Attribute:type/Value:PCSoftware' => 'Software de PC',
	'Class:Software/Attribute:type/Value:PCSoftware+' => 'Software de PC',
	'Class:Software/Attribute:type/Value:WebServer' => 'Servidor Web',
	'Class:Software/Attribute:type/Value:WebServer+' => 'Servidor Web',
	'Class:Software/Attribute:softwareinstance_list' => 'Instâncias de Software',
	'Class:Software/Attribute:softwareinstance_list+' => 'Todas as instâncias software para esse software',
	'Class:Software/Attribute:softwarepatch_list' => 'Atualizações de software',
	'Class:Software/Attribute:softwarepatch_list+' => 'Todas as atualizações para esse software',
	'Class:Software/Attribute:softwarelicence_list' => 'Licenças de Software',
	'Class:Software/Attribute:softwarelicence_list+' => 'Todas as licenças software para esse software',
));

//
// Class: Patch
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Patch' => 'Atualização',
	'Class:Patch+' => '',
	'Class:Patch/Attribute:name' => 'Nome',
	'Class:Patch/Attribute:name+' => '',
	'Class:Patch/Attribute:documents_list' => 'Documentos',
	'Class:Patch/Attribute:documents_list+' => 'Todos os documentos associados à esta atualização',
	'Class:Patch/Attribute:description' => 'Descrição',
	'Class:Patch/Attribute:description+' => '',
	'Class:Patch/Attribute:finalclass' => 'Tipo',
	'Class:Patch/Attribute:finalclass+' => '',
));

//
// Class: OSPatch
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:OSPatch' => 'Atualização de SO',
	'Class:OSPatch+' => '',
	'Class:OSPatch/Attribute:functionalcis_list' => 'Dispositivos',
	'Class:OSPatch/Attribute:functionalcis_list+' => 'Todos os sistemas onde essa atualização está instalada',
	'Class:OSPatch/Attribute:osversion_id' => 'Versão do SO',
	'Class:OSPatch/Attribute:osversion_id+' => '',
	'Class:OSPatch/Attribute:osversion_name' => 'Nome da versão do SO',
	'Class:OSPatch/Attribute:osversion_name+' => '',
));

//
// Class: SoftwarePatch
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:SoftwarePatch' => 'Atualização de Software',
	'Class:SoftwarePatch+' => '',
	'Class:SoftwarePatch/Attribute:software_id' => 'Software',
	'Class:SoftwarePatch/Attribute:software_id+' => '',
	'Class:SoftwarePatch/Attribute:software_name' => 'Nome do software',
	'Class:SoftwarePatch/Attribute:software_name+' => '',
	'Class:SoftwarePatch/Attribute:softwareinstances_list' => 'Instâncias do Software',
	'Class:SoftwarePatch/Attribute:softwareinstances_list+' => 'Todos os sistemas onde essa atualização de software está instalada',
));

//
// Class: Licence
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Licence' => 'Licença',
	'Class:Licence+' => '',
	'Class:Licence/Attribute:name' => 'Nome',
	'Class:Licence/Attribute:name+' => '',
	'Class:Licence/Attribute:documents_list' => 'Documentos',
	'Class:Licence/Attribute:documents_list+' => 'Todos os documentos associados à esta licença',
	'Class:Licence/Attribute:org_id' => 'Organização',
	'Class:Licence/Attribute:org_id+' => '',
	'Class:Licence/Attribute:organization_name' => 'Nome da organização',
	'Class:Licence/Attribute:organization_name+' => '',
	'Class:Licence/Attribute:usage_limit' => 'Limite de utilização',
	'Class:Licence/Attribute:usage_limit+' => '',
	'Class:Licence/Attribute:description' => 'Descrição',
	'Class:Licence/Attribute:description+' => '',
	'Class:Licence/Attribute:start_date' => 'Data de início',
	'Class:Licence/Attribute:start_date+' => '',
	'Class:Licence/Attribute:end_date' => 'Data de expiração',
	'Class:Licence/Attribute:end_date+' => '',
	'Class:Licence/Attribute:licence_key' => 'Chave de licença',
	'Class:Licence/Attribute:licence_key+' => '',
	'Class:Licence/Attribute:perpetual' => 'Permanente',
	'Class:Licence/Attribute:perpetual+' => '',
	'Class:Licence/Attribute:perpetual/Value:no' => 'Não',
	'Class:Licence/Attribute:perpetual/Value:no+' => 'Não',
	'Class:Licence/Attribute:perpetual/Value:yes' => 'Sim',
	'Class:Licence/Attribute:perpetual/Value:yes+' => 'Sim',
	'Class:Licence/Attribute:finalclass' => 'Tipo',
	'Class:Licence/Attribute:finalclass+' => '',
));

//
// Class: OSLicence
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:OSLicence' => 'Licença de SO',
	'Class:OSLicence+' => '',
	'Class:OSLicence/ComplementaryName' => '%1$s - %2$s~~',
	'Class:OSLicence/Attribute:osversion_id' => 'Versão do SO',
	'Class:OSLicence/Attribute:osversion_id+' => '',
	'Class:OSLicence/Attribute:osversion_name' => 'Nome da versão do SO',
	'Class:OSLicence/Attribute:osversion_name+' => '',
	'Class:OSLicence/Attribute:virtualmachines_list' => 'Máquinas virtuais',
	'Class:OSLicence/Attribute:virtualmachines_list+' => 'Todas as máquinas virtuais onde essa licença é utilizada',
	'Class:OSLicence/Attribute:servers_list' => 'servidores',
	'Class:OSLicence/Attribute:servers_list+' => 'Todos os servidores onde essa licença é utilizada',
));

//
// Class: SoftwareLicence
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:SoftwareLicence' => 'Licença de software',
	'Class:SoftwareLicence+' => '',
	'Class:SoftwareLicence/ComplementaryName' => '%1$s - %2$s~~',
	'Class:SoftwareLicence/Attribute:software_id' => 'Software',
	'Class:SoftwareLicence/Attribute:software_id+' => '',
	'Class:SoftwareLicence/Attribute:software_name' => 'Nome do software',
	'Class:SoftwareLicence/Attribute:software_name+' => '',
	'Class:SoftwareLicence/Attribute:softwareinstance_list' => 'Instâncias do software',
	'Class:SoftwareLicence/Attribute:softwareinstance_list+' => 'Todos os sistemas onde essa licença é utilizada',
));

//
// Class: lnkDocumentToLicence
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkDocumentToLicence' => 'Link Documento / Licença',
	'Class:lnkDocumentToLicence+' => '',
	'Class:lnkDocumentToLicence/Name' => '%1$s / %2$s~~',
	'Class:lnkDocumentToLicence/Attribute:licence_id' => 'Licença',
	'Class:lnkDocumentToLicence/Attribute:licence_id+' => '',
	'Class:lnkDocumentToLicence/Attribute:licence_name' => 'Nome da licença',
	'Class:lnkDocumentToLicence/Attribute:licence_name+' => '',
	'Class:lnkDocumentToLicence/Attribute:document_id' => 'Documento',
	'Class:lnkDocumentToLicence/Attribute:document_id+' => '',
	'Class:lnkDocumentToLicence/Attribute:document_name' => 'Nome do documento',
	'Class:lnkDocumentToLicence/Attribute:document_name+' => '',
));

//
// Class: OSVersion
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:OSVersion' => 'Versão do OS',
	'Class:OSVersion+' => '',
	'Class:OSVersion/Attribute:osfamily_id' => 'Família do SO',
	'Class:OSVersion/Attribute:osfamily_id+' => '',
	'Class:OSVersion/Attribute:osfamily_name' => 'Nome da família do SO',
	'Class:OSVersion/Attribute:osfamily_name+' => '',
));

//
// Class: OSFamily
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:OSFamily' => 'Família do OS',
	'Class:OSFamily+' => '',
));

//
// Class: Brand
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Brand' => 'Fabricante',
	'Class:Brand+' => '',
	'Class:Brand/Attribute:physicaldevices_list' => 'Dispositivos físicos',
	'Class:Brand/Attribute:physicaldevices_list+' => 'Todos os dispositivos físicos correspondentes a esse(a) fabricante',
	'Class:Brand/UniquenessRule:name+' => 'O nome do(a) fabricante deve ser único',
	'Class:Brand/UniquenessRule:name' => 'Esse(a) fabricante já existe',
));

//
// Class: Model
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Model' => 'Modelo',
	'Class:Model+' => '',
	'Class:Model/ComplementaryName' => '%1$s - %2$s~~',
	'Class:Model/Attribute:brand_id' => 'Fabricante',
	'Class:Model/Attribute:brand_id+' => '',
	'Class:Model/Attribute:brand_name' => 'Nome do fabricante',
	'Class:Model/Attribute:brand_name+' => '',
	'Class:Model/Attribute:type' => 'Tipo de dispositivo',
	'Class:Model/Attribute:type+' => '',
	'Class:Model/Attribute:type/Value:PowerSource' => 'Fonte de energia',
	'Class:Model/Attribute:type/Value:PowerSource+' => '',
	'Class:Model/Attribute:type/Value:DiskArray' => 'Array de disco',
	'Class:Model/Attribute:type/Value:DiskArray+' => '',
	'Class:Model/Attribute:type/Value:Enclosure' => 'Gaveta',
	'Class:Model/Attribute:type/Value:Enclosure+' => '',
	'Class:Model/Attribute:type/Value:IPPhone' => 'Telefone IP',
	'Class:Model/Attribute:type/Value:IPPhone+' => '',
	'Class:Model/Attribute:type/Value:MobilePhone' => 'Telefone celular',
	'Class:Model/Attribute:type/Value:MobilePhone+' => '',
	'Class:Model/Attribute:type/Value:NAS' => 'NAS',
	'Class:Model/Attribute:type/Value:NAS+' => '',
	'Class:Model/Attribute:type/Value:NetworkDevice' => 'Dispositivo de rede',
	'Class:Model/Attribute:type/Value:NetworkDevice+' => '',
	'Class:Model/Attribute:type/Value:PC' => 'PC',
	'Class:Model/Attribute:type/Value:PC+' => '',
	'Class:Model/Attribute:type/Value:PDU' => 'PDU',
	'Class:Model/Attribute:type/Value:PDU+' => '',
	'Class:Model/Attribute:type/Value:Peripheral' => 'Periférico',
	'Class:Model/Attribute:type/Value:Peripheral+' => '',
	'Class:Model/Attribute:type/Value:Printer' => 'Impressora',
	'Class:Model/Attribute:type/Value:Printer+' => '',
	'Class:Model/Attribute:type/Value:Rack' => 'Rack',
	'Class:Model/Attribute:type/Value:Rack+' => '',
	'Class:Model/Attribute:type/Value:SANSwitch' => 'Switch SAN',
	'Class:Model/Attribute:type/Value:SANSwitch+' => '',
	'Class:Model/Attribute:type/Value:Server' => 'Servidor',
	'Class:Model/Attribute:type/Value:Server+' => '',
	'Class:Model/Attribute:type/Value:StorageSystem' => 'Sistema de Storage',
	'Class:Model/Attribute:type/Value:StorageSystem+' => '',
	'Class:Model/Attribute:type/Value:Tablet' => 'Tablet',
	'Class:Model/Attribute:type/Value:Tablet+' => '',
	'Class:Model/Attribute:type/Value:TapeLibrary' => 'Biblioteca de fitas',
	'Class:Model/Attribute:type/Value:TapeLibrary+' => '',
	'Class:Model/Attribute:type/Value:Phone' => 'Telefone',
	'Class:Model/Attribute:type/Value:Phone+' => '',
	'Class:Model/Attribute:physicaldevices_list' => 'Dispositivo físico',
	'Class:Model/Attribute:physicaldevices_list+' => 'Todos os dispositivos físicos correspondentes a este modelo',
	'Class:Model/UniquenessRule:name_brand+' => 'O nome do modelo deve ser único',
	'Class:Model/UniquenessRule:name_brand' => 'Este modelo já existe',
));

//
// Class: NetworkDeviceType
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:NetworkDeviceType' => 'Tipo de dispositivo de rede',
	'Class:NetworkDeviceType+' => '',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list' => 'Dispositivo de rede',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list+' => 'Todos os dispositivo de rede correspondentes a este tipo',
));

//
// Class: IOSVersion
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:IOSVersion' => 'Versão do IOS',
	'Class:IOSVersion+' => '',
	'Class:IOSVersion/Attribute:brand_id' => 'Fabricante',
	'Class:IOSVersion/Attribute:brand_id+' => '',
	'Class:IOSVersion/Attribute:brand_name' => 'Nome do fabricante',
	'Class:IOSVersion/Attribute:brand_name+' => '',
));

//
// Class: lnkDocumentToPatch
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkDocumentToPatch' => 'Link Documento / Atualização',
	'Class:lnkDocumentToPatch+' => '',
	'Class:lnkDocumentToPatch/Name' => '%1$s / %2$s~~',
	'Class:lnkDocumentToPatch/Attribute:patch_id' => 'Atualização',
	'Class:lnkDocumentToPatch/Attribute:patch_id+' => '',
	'Class:lnkDocumentToPatch/Attribute:patch_name' => 'Nome da atualização',
	'Class:lnkDocumentToPatch/Attribute:patch_name+' => '',
	'Class:lnkDocumentToPatch/Attribute:document_id' => 'Documento',
	'Class:lnkDocumentToPatch/Attribute:document_id+' => '',
	'Class:lnkDocumentToPatch/Attribute:document_name' => 'Nome do documento',
	'Class:lnkDocumentToPatch/Attribute:document_name+' => '',
));

//
// Class: lnkSoftwareInstanceToSoftwarePatch
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkSoftwareInstanceToSoftwarePatch' => 'Link Instância de Software / Atualização de Software',
	'Class:lnkSoftwareInstanceToSoftwarePatch+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Name' => '%1$s / %2$s~~',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_id' => 'Atualização de software',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_id+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_name' => 'Nome da atualização de software',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_name+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_id' => 'Instância de software',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_id+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_name' => 'Nome da instância de software',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_name+' => '',
));

//
// Class: lnkFunctionalCIToOSPatch
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkFunctionalCIToOSPatch' => 'Link IC / Atualização de SO',
	'Class:lnkFunctionalCIToOSPatch+' => '',
	'Class:lnkFunctionalCIToOSPatch/Name' => '%1$s / %2$s~~',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_id' => 'Atualização de SO',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_id+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_name' => 'Nome da atualização de SO',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_name+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_id' => 'ICs',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_name' => 'Nome do IC',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_name+' => '',
));

//
// Class: lnkDocumentToSoftware
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkDocumentToSoftware' => 'Link Documento / Software',
	'Class:lnkDocumentToSoftware+' => '',
	'Class:lnkDocumentToSoftware/Name' => '%1$s / %2$s~~',
	'Class:lnkDocumentToSoftware/Attribute:software_id' => 'Software',
	'Class:lnkDocumentToSoftware/Attribute:software_id+' => '',
	'Class:lnkDocumentToSoftware/Attribute:software_name' => 'Nome do software',
	'Class:lnkDocumentToSoftware/Attribute:software_name+' => '',
	'Class:lnkDocumentToSoftware/Attribute:document_id' => 'Documento',
	'Class:lnkDocumentToSoftware/Attribute:document_id+' => '',
	'Class:lnkDocumentToSoftware/Attribute:document_name' => 'Nome do documento',
	'Class:lnkDocumentToSoftware/Attribute:document_name+' => '',
));

//
// Class: Subnet
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Subnet' => 'Sub-rede',
	'Class:Subnet+' => '',
	'Class:Subnet/Name' => '%1$s/%2$s~~',
	'Class:Subnet/ComplementaryName' => '%1$s - %2$s~~',
	'Class:Subnet/Attribute:description' => 'Descrição',
	'Class:Subnet/Attribute:description+' => '',
	'Class:Subnet/Attribute:subnet_name' => 'Nome da sub-rede',
	'Class:Subnet/Attribute:subnet_name+' => '',
	'Class:Subnet/Attribute:org_id' => 'Organização',
	'Class:Subnet/Attribute:org_id+' => '',
	'Class:Subnet/Attribute:org_name' => 'Nome',
	'Class:Subnet/Attribute:org_name+' => 'Nome comum',
	'Class:Subnet/Attribute:ip' => 'IP',
	'Class:Subnet/Attribute:ip+' => '',
	'Class:Subnet/Attribute:ip_mask' => 'Máscara de rede',
	'Class:Subnet/Attribute:ip_mask+' => '',
	'Class:Subnet/Attribute:vlans_list' => 'VLANs',
	'Class:Subnet/Attribute:vlans_list+' => '',
));

//
// Class: VLAN
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:VLAN' => 'VLAN',
	'Class:VLAN+' => '',
	'Class:VLAN/Attribute:vlan_tag' => 'Nome da VLAN',
	'Class:VLAN/Attribute:vlan_tag+' => '',
	'Class:VLAN/Attribute:description' => 'Descrição',
	'Class:VLAN/Attribute:description+' => '',
	'Class:VLAN/Attribute:org_id' => 'Organização',
	'Class:VLAN/Attribute:org_id+' => '',
	'Class:VLAN/Attribute:org_name' => 'Nome da organização',
	'Class:VLAN/Attribute:org_name+' => 'Nome comum',
	'Class:VLAN/Attribute:subnets_list' => 'Sub-redes',
	'Class:VLAN/Attribute:subnets_list+' => '',
	'Class:VLAN/Attribute:physicalinterfaces_list' => 'Interfaces de rede física',
	'Class:VLAN/Attribute:physicalinterfaces_list+' => '',
));

//
// Class: lnkSubnetToVLAN
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkSubnetToVLAN' => 'Link Sub-rede / VLAN',
	'Class:lnkSubnetToVLAN+' => '',
	'Class:lnkSubnetToVLAN/Name' => '%1$s / %2$s~~',
	'Class:lnkSubnetToVLAN/Attribute:subnet_id' => 'Sub-rede',
	'Class:lnkSubnetToVLAN/Attribute:subnet_id+' => '',
	'Class:lnkSubnetToVLAN/Attribute:subnet_ip' => 'IP da sub-rede',
	'Class:lnkSubnetToVLAN/Attribute:subnet_ip+' => '',
	'Class:lnkSubnetToVLAN/Attribute:subnet_name' => 'Nome da sub-rede',
	'Class:lnkSubnetToVLAN/Attribute:subnet_name+' => '',
	'Class:lnkSubnetToVLAN/Attribute:vlan_id' => 'VLAN',
	'Class:lnkSubnetToVLAN/Attribute:vlan_id+' => '',
	'Class:lnkSubnetToVLAN/Attribute:vlan_tag' => 'Nome da VLAN',
	'Class:lnkSubnetToVLAN/Attribute:vlan_tag+' => '',
));

//
// Class: NetworkInterface
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:NetworkInterface' => 'Placa de Rede',
	'Class:NetworkInterface+' => '',
	'Class:NetworkInterface/Attribute:name' => 'Nome',
	'Class:NetworkInterface/Attribute:name+' => '',
	'Class:NetworkInterface/Attribute:finalclass' => 'Tipo',
	'Class:NetworkInterface/Attribute:finalclass+' => '',
));

//
// Class: IPInterface
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:IPInterface' => 'Endereço IP',
	'Class:IPInterface+' => '',
	'Class:IPInterface/Attribute:ipaddress' => 'Endereço IP',
	'Class:IPInterface/Attribute:ipaddress+' => '',
	'Class:IPInterface/Attribute:macaddress' => 'Endereço MAC',
	'Class:IPInterface/Attribute:macaddress+' => '',
	'Class:IPInterface/Attribute:comment' => 'Comentário',
	'Class:IPInterface/Attribute:coment+' => '',
	'Class:IPInterface/Attribute:ipgateway' => 'Gateway',
	'Class:IPInterface/Attribute:ipgateway+' => '',
	'Class:IPInterface/Attribute:ipmask' => 'Máscara de rede',
	'Class:IPInterface/Attribute:ipmask+' => '',
	'Class:IPInterface/Attribute:speed' => 'Velocidade',
	'Class:IPInterface/Attribute:speed+' => '',
));

//
// Class: PhysicalInterface
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:PhysicalInterface' => 'Placa física',
	'Class:PhysicalInterface+' => '',
	'Class:PhysicalInterface/Name' => '%2$s %1$s~~',
	'Class:PhysicalInterface/Attribute:connectableci_id' => 'Dispositivo',
	'Class:PhysicalInterface/Attribute:connectableci_id+' => '',
	'Class:PhysicalInterface/Attribute:connectableci_name' => 'Nome do dispositivo',
	'Class:PhysicalInterface/Attribute:connectableci_name+' => '',
	'Class:PhysicalInterface/Attribute:vlans_list' => 'VLANs',
	'Class:PhysicalInterface/Attribute:vlans_list+' => '',
));

//
// Class: lnkPhysicalInterfaceToVLAN
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkPhysicalInterfaceToVLAN' => 'Link Interfaces físicas / VLAN',
	'Class:lnkPhysicalInterfaceToVLAN+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Name' => '%1$s %2$s / %3$s~~',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_id' => 'Interface física',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_id+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_name' => 'Nome da interface física',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_name+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_id' => 'Dispositivo',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_id+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_name' => 'Nome do dispositivo',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_name+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_id' => 'VLAN',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_id+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_tag' => 'Nome da VLAN',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_tag+' => '',
));


//
// Class: LogicalInterface
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:LogicalInterface' => 'Placa lógica',
	'Class:LogicalInterface+' => '',
	'Class:LogicalInterface/Attribute:virtualmachine_id' => 'Máquina virtual',
	'Class:LogicalInterface/Attribute:virtualmachine_id+' => '',
	'Class:LogicalInterface/Attribute:virtualmachine_name' => 'Nome da máquina virtual',
	'Class:LogicalInterface/Attribute:virtualmachine_name+' => '',
));

//
// Class: FiberChannelInterface
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:FiberChannelInterface' => 'Placa Fiber Channel',
	'Class:FiberChannelInterface+' => '',
	'Class:FiberChannelInterface/Attribute:speed' => 'Velocidade',
	'Class:FiberChannelInterface/Attribute:speed+' => '',
	'Class:FiberChannelInterface/Attribute:topology' => 'Topologia',
	'Class:FiberChannelInterface/Attribute:topology+' => '',
	'Class:FiberChannelInterface/Attribute:wwn' => 'WWN',
	'Class:FiberChannelInterface/Attribute:wwn+' => '',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_id' => 'Dispositivo',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_id+' => '',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_name' => 'Nome do dispositivo',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_name+' => '',
));

//
// Class: lnkConnectableCIToNetworkDevice
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkConnectableCIToNetworkDevice' => 'Link ConnectableCI / NetworkDevice',
	'Class:lnkConnectableCIToNetworkDevice+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Name' => '%1$s / %2$s~~',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_id' => 'Dispositivo de rede',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_id+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_name' => 'Nome do dispositivo rede',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_name+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_id' => 'Dispositivo conectado',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_id+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_name' => 'Nome do dispositivo conectado',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_name+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:network_port' => 'Porta de rede',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:network_port+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:device_port' => 'Porta do dispositivo',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:device_port+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type' => 'Tipo de conexão',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:downlink' => 'Link down',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:downlink+' => 'Link down',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:uplink' => 'Link up',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:uplink+' => 'Link up',
));

//
// Class: lnkApplicationSolutionToFunctionalCI
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkApplicationSolutionToFunctionalCI' => 'Link Solução de Aplicação / IC',
	'Class:lnkApplicationSolutionToFunctionalCI+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Name' => '%1$s / %2$s~~',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_id' => 'Solução de aplicação',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_id+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_name' => 'Nome da solução de aplicação',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_name+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_id' => 'ICs',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_name' => 'Nome do IC',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_name+' => '',
));

//
// Class: lnkApplicationSolutionToBusinessProcess
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkApplicationSolutionToBusinessProcess' => 'Link ApplicationSolution / BusinessProcess',
	'Class:lnkApplicationSolutionToBusinessProcess+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Name' => '%1$s / %2$s~~',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_id' => 'Processo de negócio',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_id+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_name' => 'Nome do processo de negócio',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_name+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_id' => 'Solução de aplicação',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_id+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_name' => 'Nome da solução de aplicação',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_name+' => '',
));

//
// Class: Group
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Group' => 'Grupo',
	'Class:Group+' => '',
	'Class:Group/ComplementaryName' => '%1$s - %2$s~~',
	'Class:Group/Attribute:name' => 'Nome',
	'Class:Group/Attribute:name+' => '',
	'Class:Group/Attribute:status' => 'Status',
	'Class:Group/Attribute:status+' => '',
	'Class:Group/Attribute:status/Value:implementation' => 'Em homologação',
	'Class:Group/Attribute:status/Value:implementation+' => 'Em homologação',
	'Class:Group/Attribute:status/Value:obsolete' => 'Obsoleto',
	'Class:Group/Attribute:status/Value:obsolete+' => 'Obsoleto',
	'Class:Group/Attribute:status/Value:production' => 'Em produção',
	'Class:Group/Attribute:status/Value:production+' => 'Em produção',
	'Class:Group/Attribute:org_id' => 'Organização',
	'Class:Group/Attribute:org_id+' => '',
	'Class:Group/Attribute:owner_name' => 'Nome',
	'Class:Group/Attribute:owner_name+' => 'Nome comum',
	'Class:Group/Attribute:description' => 'Descrição',
	'Class:Group/Attribute:description+' => '',
	'Class:Group/Attribute:type' => 'Tipo',
	'Class:Group/Attribute:type+' => '',
	'Class:Group/Attribute:parent_id' => 'Grupo pai',
	'Class:Group/Attribute:parent_id+' => '',
	'Class:Group/Attribute:parent_name' => 'Nome',
	'Class:Group/Attribute:parent_name+' => '',
	'Class:Group/Attribute:ci_list' => 'ICs relacionados',
	'Class:Group/Attribute:ci_list+' => 'Todos os itens de configuração associados a este grupo',
	'Class:Group/Attribute:parent_id_friendlyname' => 'Grupo pai',
	'Class:Group/Attribute:parent_id_friendlyname+' => '',
));

//
// Class: lnkGroupToCI
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkGroupToCI' => 'Link Grupo / IC',
	'Class:lnkGroupToCI+' => '',
	'Class:lnkGroupToCI/Name' => '%1$s / %2$s~~',
	'Class:lnkGroupToCI/Attribute:group_id' => 'Grupo',
	'Class:lnkGroupToCI/Attribute:group_id+' => '',
	'Class:lnkGroupToCI/Attribute:group_name' => 'Nome do grupo',
	'Class:lnkGroupToCI/Attribute:group_name+' => '',
	'Class:lnkGroupToCI/Attribute:ci_id' => 'IC',
	'Class:lnkGroupToCI/Attribute:ci_id+' => '',
	'Class:lnkGroupToCI/Attribute:ci_name' => 'Nome do IC',
	'Class:lnkGroupToCI/Attribute:ci_name+' => '',
	'Class:lnkGroupToCI/Attribute:reason' => 'Motivo do link',
	'Class:lnkGroupToCI/Attribute:reason+' => '',
));

// Add translation for Fieldsets

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Server:baseinfo' => 'Informações gerais',
	'Server:Date' => 'Data',
	'Server:moreinfo' => 'Mais informações',
	'Server:otherinfo' => 'Outras informações',
	'Server:power' => 'Fonte de alimentação',
	'Class:Subnet/Tab:IPUsage' => 'IP usado',
	'Class:Subnet/Tab:IPUsage+' => 'Which IP within this Subnet are used or not~~',
	'Class:Subnet/Tab:IPUsage-explain' => 'Placas de rede contendo IP na faixa: <em>%1$s</em> para <em>%2$s</em>',
	'Class:Subnet/Tab:FreeIPs' => 'IPs livres',
	'Class:Subnet/Tab:FreeIPs-count' => 'IPs livres: %1$s',
	'Class:Subnet/Tab:FreeIPs-explain' => 'Aqui uma faixa de 10 endereços IPs livres',
	'Class:Document:PreviewTab' => 'Visualização',
));


//
// Class: lnkDocumentToFunctionalCI
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkDocumentToFunctionalCI' => 'Link de Documento / IC',
	'Class:lnkDocumentToFunctionalCI+' => '',
	'Class:lnkDocumentToFunctionalCI/Name' => '%1$s / %2$s~~',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_id' => 'ICs',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_name' => 'Nome do IC',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_id' => 'Documento',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_id+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_name' => 'Nome do documento',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_name+' => '',
));

//
// Application Menu
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Menu:Application' => 'Aplicações',
	'Menu:Application+' => 'Lista de Aplicações',
	'Menu:DBServer' => 'Servidores de Banco de Dados',
	'Menu:DBServer+' => 'Lista de Servidores de Banco de Dados',
	'Menu:BusinessProcess' => 'Processos de Negócios',
	'Menu:BusinessProcess+' => 'Lista de Processos de Negócios',
	'Menu:ApplicationSolution' => 'Soluções de Aplicação',
	'Menu:ApplicationSolution+' => 'Lista de Soluções de Aplicação',
	'Menu:ConfigManagementSoftware' => 'Gerenciamento de Software',
	'Menu:Licence' => 'Licenças',
	'Menu:Licence+' => 'Lista de Licenças',
	'Menu:Patch' => 'Atualizações',
	'Menu:Patch+' => 'Lista de Atualizações',
	'Menu:ApplicationInstance' => 'Softwares Instalados',
	'Menu:ApplicationInstance+' => 'Serviços de aplicações e servidores de banco de dados',
	'Menu:ConfigManagementHardware' => 'Gerenciamento de Infraestrutura',
	'Menu:Subnet' => 'Sub-redes',
	'Menu:Subnet+' => 'Lista de Sub-redes',
	'Menu:NetworkDevice' => 'Dispositivos de Rede',
	'Menu:NetworkDevice+' => 'Lista de Dispositivos de Rede',
	'Menu:Server' => 'Servidores',
	'Menu:Server+' => 'Lista de Servidores',
	'Menu:Printer' => 'Impressoras',
	'Menu:Printer+' => 'Lista de Impressoras',
	'Menu:MobilePhone' => 'Telefone Celular',
	'Menu:MobilePhone+' => 'Lista de Telefones Celulares',
	'Menu:PC' => 'Estação de Trabalho',
	'Menu:PC+' => 'Lista de Estações de Trabalho',
	'Menu:NewCI' => 'Novo IC',
	'Menu:NewCI+' => '',
	'Menu:SearchCIs' => 'Pesquisar por ICs',
	'Menu:SearchCIs+' => '',
	'Menu:ConfigManagement:Devices' => 'Dispositivos',
	'Menu:ConfigManagement:AllDevices' => 'Infraestrutura',
	'Menu:ConfigManagement:virtualization' => 'Virtualização',
	'Menu:ConfigManagement:EndUsers' => 'Dispositivos de usuários finais',
	'Menu:ConfigManagement:SWAndApps' => 'Softwares e aplicações',
	'Menu:ConfigManagement:Misc' => 'Diversos',
	'Menu:Group' => 'Grupos de ICs',
	'Menu:Group+' => 'Lista de Grupos de ICs',
	'Menu:OSVersion' => 'Versão do SO',
	'Menu:OSVersion+' => 'Lista de Versões do SO',
	'Menu:Software' => 'Catálogo de Software',
	'Menu:Software+' => '',
));
?>
