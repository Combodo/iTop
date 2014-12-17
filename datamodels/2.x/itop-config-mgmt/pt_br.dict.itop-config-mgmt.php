<?php
// Copyright (C) 2010-2012 Combodo SARL
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
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

//////////////////////////////////////////////////////////////////////
// Relations
//////////////////////////////////////////////////////////////////////
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Relation:impacts/Description' => 'Elementos impactados por',
	'Relation:impacts/VerbUp' => 'Impacto...',
	'Relation:impacts/VerbDown' => 'Elementos impactados por...',
	'Relation:depends on/Description' => 'Elementos estes, que dependem deste elemento',
	'Relation:depends on/VerbUp' => 'Depende de...',
	'Relation:depends on/VerbDown' => 'Impactos...',
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

//////////////////////////////////////////////////////////////////////
// Note: The classes have been grouped by categories: bizmodel
//////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////
// Classes in 'bizmodel'
//////////////////////////////////////////////////////////////////////
//


//
// Class: Organization
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Organization' => 'Organização',
	'Class:Organization+' => '',
	'Class:Organization/Attribute:name' => 'Nome',
	'Class:Organization/Attribute:name+' => 'Nome comum',
	'Class:Organization/Attribute:code' => 'Código',
	'Class:Organization/Attribute:code+' => 'Código organização (Siret, DUNS,...)',
	'Class:Organization/Attribute:status' => 'Estado',
	'Class:Organization/Attribute:status+' => '',
	'Class:Organization/Attribute:status/Value:active' => 'Ativo',
	'Class:Organization/Attribute:status/Value:active+' => 'Ativo',
	'Class:Organization/Attribute:status/Value:inactive' => 'Inativo',
	'Class:Organization/Attribute:status/Value:inactive+' => 'Inativo',
	'Class:Organization/Attribute:parent_id' => 'Principal',
	'Class:Organization/Attribute:parent_id+' => 'Organização principal',
	'Class:Organization/Attribute:parent_name' => 'Nome principal',
	'Class:Organization/Attribute:parent_name+' => 'Nome da organização principal',
	'Class:Organization/Attribute:deliverymodel_id' => 'Modelo entrega',
	'Class:Organization/Attribute:deliverymodel_id+' => '',
	'Class:Organization/Attribute:deliverymodel_name' => 'Nome modelo entrega',
	'Class:Organization/Attribute:deliverymodel_name+' => '',
	'Class:Organization/Attribute:parent_id_friendlyname' => 'Principal',
	'Class:Organization/Attribute:parent_id_friendlyname+' => 'Organização principal',
));

//
// Class: Location
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Location' => 'Localidade',
	'Class:Location+' => 'Qualquer tipo de localização: Região, País, Cidade, Lugar, Edifício, Andar, Sala, Rack,...',
	'Class:Location/Attribute:name' => 'Nome',
	'Class:Location/Attribute:name+' => '',
	'Class:Location/Attribute:status' => 'Estado',
	'Class:Location/Attribute:status+' => '',
	'Class:Location/Attribute:status/Value:active' => 'Ativo',
	'Class:Location/Attribute:status/Value:active+' => 'Ativo',
	'Class:Location/Attribute:status/Value:inactive' => 'Inativo',
	'Class:Location/Attribute:status/Value:inactive+' => 'Inativo',
	'Class:Location/Attribute:org_id' => 'Organização',
	'Class:Location/Attribute:org_id+' => '',
	'Class:Location/Attribute:org_name' => 'Nome organização',
	'Class:Location/Attribute:org_name+' => '',
	'Class:Location/Attribute:address' => 'Endereço',
	'Class:Location/Attribute:address+' => 'Endereço',
	'Class:Location/Attribute:postal_code' => 'CEP',
	'Class:Location/Attribute:postal_code+' => 'CEP',
	'Class:Location/Attribute:city' => 'Cidade',
	'Class:Location/Attribute:city+' => '',
	'Class:Location/Attribute:country' => 'País',
	'Class:Location/Attribute:country+' => '',
	'Class:Location/Attribute:physicaldevice_list' => 'Dispositivos',
	'Class:Location/Attribute:physicaldevice_list+' => 'Todos os dispositivos desta localidade',
	'Class:Location/Attribute:person_list' => 'Contatos',
	'Class:Location/Attribute:person_list+' => 'Todos os contatos desta localidade',
));

//
// Class: Contact
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Contact' => 'Contato',
	'Class:Contact+' => '',
	'Class:Contact/Attribute:name' => 'Nome',
	'Class:Contact/Attribute:name+' => '',
	'Class:Contact/Attribute:status' => 'Estado',
	'Class:Contact/Attribute:status+' => '',
	'Class:Contact/Attribute:status/Value:active' => 'Ativo',
	'Class:Contact/Attribute:status/Value:active+' => 'Ativo',
	'Class:Contact/Attribute:status/Value:inactive' => 'Inativo',
	'Class:Contact/Attribute:status/Value:inactive+' => 'Inativo',
	'Class:Contact/Attribute:org_id' => 'Organização',
	'Class:Contact/Attribute:org_id+' => '',
	'Class:Contact/Attribute:org_name' => 'Nome organização',
	'Class:Contact/Attribute:org_name+' => '',
	'Class:Contact/Attribute:email' => 'Email',
	'Class:Contact/Attribute:email+' => '',
	'Class:Contact/Attribute:phone' => 'Telefone',
	'Class:Contact/Attribute:phone+' => '',
	'Class:Contact/Attribute:notify' => 'Notificação',
	'Class:Contact/Attribute:notify+' => '',
	'Class:Contact/Attribute:notify/Value:no' => 'Não',
	'Class:Contact/Attribute:notify/Value:no+' => 'Não',
	'Class:Contact/Attribute:notify/Value:yes' => 'Sim',
	'Class:Contact/Attribute:notify/Value:yes+' => 'Sim',
	'Class:Contact/Attribute:function' => 'Função',
	'Class:Contact/Attribute:function+' => '',
	'Class:Contact/Attribute:cis_list' => 'CIs',
	'Class:Contact/Attribute:cis_list+' => 'Todos os itens de configuração vinculado a esse contato',
	'Class:Contact/Attribute:finalclass' => 'Tipo contato',
	'Class:Contact/Attribute:finalclass+' => '',
));

//
// Class: Person
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Person' => 'Pessoa',
	'Class:Person+' => '',
	'Class:Person/Attribute:name' => 'Último nome',
	'Class:Person/Attribute:name+' => '',
	'Class:Person/Attribute:first_name' => 'Primeiro nome',
	'Class:Person/Attribute:first_name+' => '',
	'Class:Person/Attribute:employee_number' => 'Número colaborador',
	'Class:Person/Attribute:employee_number+' => '',
	'Class:Person/Attribute:mobile_phone' => 'Celular',
	'Class:Person/Attribute:mobile_phone+' => '',
	'Class:Person/Attribute:location_id' => 'Localidade',
	'Class:Person/Attribute:location_id+' => '',
	'Class:Person/Attribute:location_name' => 'Nome localidade',
	'Class:Person/Attribute:location_name+' => '',
	'Class:Person/Attribute:manager_id' => 'Gerente',
	'Class:Person/Attribute:manager_id+' => '',
	'Class:Person/Attribute:manager_name' => 'Nome gerente',
	'Class:Person/Attribute:manager_name+' => '',
	'Class:Person/Attribute:team_list' => 'Equipes',
	'Class:Person/Attribute:team_list+' => 'Todas as equipes que essa pessoa pertence',
	'Class:Person/Attribute:tickets_list' => 'Solicitações',
	'Class:Person/Attribute:tickets_list+' => 'Todos as solicitações que essa pessoa solicitou',
	'Class:Person/Attribute:manager_id_friendlyname' => 'Nome amigável gerente',
	'Class:Person/Attribute:manager_id_friendlyname+' => '',
));

//
// Class: Team
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Team' => 'Equipe',
	'Class:Team+' => '',
	'Class:Team/Attribute:persons_list' => 'Membros',
	'Class:Team/Attribute:persons_list+' => 'Todas as pessoas que pertencem a esta equipe',
	'Class:Team/Attribute:tickets_list' => 'Solicitações',
	'Class:Team/Attribute:tickets_list+' => 'Todas as solicitações atribuídas a esta equipe',
));

//
// Class: Document
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Document' => 'Documento',
	'Class:Document+' => '',
	'Class:Document/Attribute:name' => 'Nome',
	'Class:Document/Attribute:name+' => '',
	'Class:Document/Attribute:org_id' => 'Organização',
	'Class:Document/Attribute:org_id+' => '',
	'Class:Document/Attribute:org_name' => 'Nome organização',
	'Class:Document/Attribute:org_name+' => '',
	'Class:Document/Attribute:documenttype_id' => 'Tipo documento',
	'Class:Document/Attribute:documenttype_id+' => '',
	'Class:Document/Attribute:documenttype_name' => 'Nome tipo documento',
	'Class:Document/Attribute:documenttype_name+' => '',
	'Class:Document/Attribute:description' => 'Descrição',
	'Class:Document/Attribute:description+' => '',
	'Class:Document/Attribute:status' => 'Estado',
	'Class:Document/Attribute:status+' => '',
	'Class:Document/Attribute:status/Value:draft' => 'Rascunho',
	'Class:Document/Attribute:status/Value:draft+' => '',
	'Class:Document/Attribute:status/Value:obsolete' => 'Obsoleto',
	'Class:Document/Attribute:status/Value:obsolete+' => '',
	'Class:Document/Attribute:status/Value:published' => 'Publicado',
	'Class:Document/Attribute:status/Value:published+' => '',
	'Class:Document/Attribute:cis_list' => 'CIs',
	'Class:Document/Attribute:cis_list+' => 'Todos os itens de configuração vinculados a esse documento',
	'Class:Document/Attribute:contracts_list' => 'Contratos',
	'Class:Document/Attribute:contracts_list+' => 'Todos os contratos vinculados com esse documento',
	'Class:Document/Attribute:services_list' => 'Services',
	'Class:Document/Attribute:services_list+' => 'Todos os serviços vinculados a esse documento',
	'Class:Document/Attribute:finalclass' => 'Tipo documento',
	'Class:Document/Attribute:finalclass+' => '',
));

//
// Class: DocumentFile
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:DocumentFile' => 'Arquivo',
	'Class:DocumentFile+' => '',
	'Class:DocumentFile/Attribute:file' => 'Arquivo',
	'Class:DocumentFile/Attribute:file+' => '',
));

//
// Class: DocumentNote
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:DocumentNote' => 'Texto',
	'Class:DocumentNote+' => '',
	'Class:DocumentNote/Attribute:text' => 'Texto',
	'Class:DocumentNote/Attribute:text+' => '',
));

//
// Class: DocumentWeb
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:DocumentWeb' => 'Web',
	'Class:DocumentWeb+' => '',
	'Class:DocumentWeb/Attribute:url' => 'URL',
	'Class:DocumentWeb/Attribute:url+' => '',
));

//
// Class: FunctionalCI
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:FunctionalCI' => 'CI',
	'Class:FunctionalCI+' => '',
	'Class:FunctionalCI/Attribute:name' => 'Nome',
	'Class:FunctionalCI/Attribute:name+' => '',
	'Class:FunctionalCI/Attribute:description' => 'Descrição',
	'Class:FunctionalCI/Attribute:description+' => '',
	'Class:FunctionalCI/Attribute:org_id' => 'Organização',
	'Class:FunctionalCI/Attribute:org_id+' => '',
	'Class:FunctionalCI/Attribute:organization_name' => 'Nome organização',
	'Class:FunctionalCI/Attribute:organization_name+' => 'Nome comum',
	'Class:FunctionalCI/Attribute:business_criticity' => 'Criticidade negócio',
	'Class:FunctionalCI/Attribute:business_criticity+' => '',
	'Class:FunctionalCI/Attribute:business_criticity/Value:high' => 'Alta',
	'Class:FunctionalCI/Attribute:business_criticity/Value:high+' => 'Alta',
	'Class:FunctionalCI/Attribute:business_criticity/Value:low' => 'Baixa',
	'Class:FunctionalCI/Attribute:business_criticity/Value:low+' => 'Baixa',
	'Class:FunctionalCI/Attribute:business_criticity/Value:medium' => 'Média',
	'Class:FunctionalCI/Attribute:business_criticity/Value:medium+' => 'Média',
	'Class:FunctionalCI/Attribute:move2production' => 'Data ir para produção',
	'Class:FunctionalCI/Attribute:move2production+' => '',
	'Class:FunctionalCI/Attribute:contacts_list' => 'Contatos',
	'Class:FunctionalCI/Attribute:contacts_list+' => 'Todos os contatos para esse item de configuração',
	'Class:FunctionalCI/Attribute:documents_list' => 'Documentos',
	'Class:FunctionalCI/Attribute:documents_list+' => 'Todos os documentos vinculados a este item de configuração',
	'Class:FunctionalCI/Attribute:applicationsolution_list' => 'Soluções de aplicações',
	'Class:FunctionalCI/Attribute:applicationsolution_list+' => 'Todas as soluções de aplicação, dependente desse item de configuração',
	'Class:FunctionalCI/Attribute:providercontracts_list' => 'Contrato provedor(a)',
	'Class:FunctionalCI/Attribute:providercontracts_list+' => 'Todos os contratos para esse item de configuração',
	'Class:FunctionalCI/Attribute:services_list' => 'Serviços',
	'Class:FunctionalCI/Attribute:services_list+' => 'Todos os serviços impactados por esse item de configuração',
	'Class:FunctionalCI/Attribute:softwares_list' => 'Softwares',
	'Class:FunctionalCI/Attribute:softwares_list+' => 'Todos os softwares instalados neste item de configuração',
	'Class:FunctionalCI/Attribute:tickets_list' => 'Solicitações',
	'Class:FunctionalCI/Attribute:tickets_list+' => 'Todos as solicitações para este item de configuração',
	'Class:FunctionalCI/Attribute:finalclass' => 'Tipo CI',
	'Class:FunctionalCI/Attribute:finalclass+' => '',
));

//
// Class: PhysicalDevice
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:PhysicalDevice' => 'Dispositivos físicos',
	'Class:PhysicalDevice+' => '',
	'Class:PhysicalDevice/Attribute:serialnumber' => 'Número serial',
	'Class:PhysicalDevice/Attribute:serialnumber+' => '',
	'Class:PhysicalDevice/Attribute:location_id' => 'Localidade',
	'Class:PhysicalDevice/Attribute:location_id+' => '',
	'Class:PhysicalDevice/Attribute:location_name' => 'Nome localidade',
	'Class:PhysicalDevice/Attribute:location_name+' => '',
	'Class:PhysicalDevice/Attribute:status' => 'Estado',
	'Class:PhysicalDevice/Attribute:status+' => '',
	'Class:PhysicalDevice/Attribute:status/Value:implementation' => 'Implementação',
	'Class:PhysicalDevice/Attribute:status/Value:implementation+' => 'Implementação',
	'Class:PhysicalDevice/Attribute:status/Value:obsolete' => 'Obsoleto',
	'Class:PhysicalDevice/Attribute:status/Value:obsolete+' => 'Obsoleto',
	'Class:PhysicalDevice/Attribute:status/Value:production' => 'Produção',
	'Class:PhysicalDevice/Attribute:status/Value:production+' => 'Produção',
	'Class:PhysicalDevice/Attribute:status/Value:stock' => 'Suporte',
	'Class:PhysicalDevice/Attribute:status/Value:stock+' => 'Suporte',
	'Class:PhysicalDevice/Attribute:brand_id' => 'Fabricante',
	'Class:PhysicalDevice/Attribute:brand_id+' => '',
	'Class:PhysicalDevice/Attribute:brand_name' => 'Nome fabricante',
	'Class:PhysicalDevice/Attribute:brand_name+' => '',
	'Class:PhysicalDevice/Attribute:model_id' => 'Modelo',
	'Class:PhysicalDevice/Attribute:model_id+' => '',
	'Class:PhysicalDevice/Attribute:model_name' => 'Nome modelo',
	'Class:PhysicalDevice/Attribute:model_name+' => '',
	'Class:PhysicalDevice/Attribute:asset_number' => 'Número do ativo',
	'Class:PhysicalDevice/Attribute:asset_number+' => '',
	'Class:PhysicalDevice/Attribute:purchase_date' => 'Data da compra',
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
	'Class:TelephonyCI/Attribute:phonenumber' => 'Número telefone',
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
	'Class:MobilePhone' => 'Telefone celular',
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
	'Class:ConnectableCI' => 'Conectividades',
	'Class:ConnectableCI+' => 'Físicos',
	'Class:ConnectableCI/Attribute:networkdevice_list' => 'Dispositivo de rede',
	'Class:ConnectableCI/Attribute:networkdevice_list+' => 'Todos os dispositivos de rede conectados nesse dispositivo',
	'Class:ConnectableCI/Attribute:physicalinterface_list' => 'Interface de rede',
	'Class:ConnectableCI/Attribute:physicalinterface_list+' => 'Todas as interfaces de rede',
));

//
// Class: DatacenterDevice
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:DatacenterDevice' => 'Dispositivos Datacenter',
	'Class:DatacenterDevice+' => '',
	'Class:DatacenterDevice/Attribute:rack_id' => 'Rack',
	'Class:DatacenterDevice/Attribute:rack_id+' => '',
	'Class:DatacenterDevice/Attribute:rack_name' => 'Nome rack',
	'Class:DatacenterDevice/Attribute:rack_name+' => '',
	'Class:DatacenterDevice/Attribute:enclosure_id' => 'Gaveta',
	'Class:DatacenterDevice/Attribute:enclosure_id+' => '',
	'Class:DatacenterDevice/Attribute:enclosure_name' => 'Nome gaveta',
	'Class:DatacenterDevice/Attribute:enclosure_name+' => '',
	'Class:DatacenterDevice/Attribute:nb_u' => 'Unidades',
	'Class:DatacenterDevice/Attribute:nb_u+' => '',
	'Class:DatacenterDevice/Attribute:managementip' => 'IP gerenciamento',
	'Class:DatacenterDevice/Attribute:managementip+' => '',
	'Class:DatacenterDevice/Attribute:powerA_id' => 'Fonte energia A',
	'Class:DatacenterDevice/Attribute:powerA_id+' => '',
	'Class:DatacenterDevice/Attribute:powerA_name' => 'Nome fonte energia A',
	'Class:DatacenterDevice/Attribute:powerA_name+' => '',
	'Class:DatacenterDevice/Attribute:powerB_id' => 'Fonte energia B',
	'Class:DatacenterDevice/Attribute:powerB_id+' => '',
	'Class:DatacenterDevice/Attribute:powerB_name' => 'Nome fonte energia B',
	'Class:DatacenterDevice/Attribute:powerB_name+' => '',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list' => 'Portas FC',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list+' => 'Todas as portas Fiber Channel para esse dispositivo',
	'Class:DatacenterDevice/Attribute:san_list' => 'SANs',
	'Class:DatacenterDevice/Attribute:san_list+' => 'Todos os switches SAN vinculados para esse dispositivo',
));

//
// Class: NetworkDevice
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:NetworkDevice' => 'Dispositivo Rede',
	'Class:NetworkDevice+' => '',
	'Class:NetworkDevice/Attribute:networkdevicetype_id' => 'Tipo rede',
	'Class:NetworkDevice/Attribute:networkdevicetype_id+' => '',
	'Class:NetworkDevice/Attribute:networkdevicetype_name' => 'Nome tipo rede',
	'Class:NetworkDevice/Attribute:networkdevicetype_name+' => '',
	'Class:NetworkDevice/Attribute:connectablecis_list' => 'Dispositivos',
	'Class:NetworkDevice/Attribute:connectablecis_list+' => 'Todos os dispositivos vinculados para esse dispositivo de rede',
	'Class:NetworkDevice/Attribute:iosversion_id' => 'Versão IOS',
	'Class:NetworkDevice/Attribute:iosversion_id+' => '',
	'Class:NetworkDevice/Attribute:iosversion_name' => 'Nome versão IOS',
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
	'Class:Server/Attribute:osfamily_id' => 'Família OS',
	'Class:Server/Attribute:osfamily_id+' => '',
	'Class:Server/Attribute:osfamily_name' => 'Nome família OS',
	'Class:Server/Attribute:osfamily_name+' => '',
	'Class:Server/Attribute:osversion_id' => 'Versão OS',
	'Class:Server/Attribute:osversion_id+' => '',
	'Class:Server/Attribute:osversion_name' => 'Nome versão OS',
	'Class:Server/Attribute:osversion_name+' => '',
	'Class:Server/Attribute:oslicence_id' => 'Licença OS',
	'Class:Server/Attribute:oslicence_id+' => '',
	'Class:Server/Attribute:oslicence_name' => 'Nome licença OS',
	'Class:Server/Attribute:oslicence_name+' => '',
	'Class:Server/Attribute:cpu' => 'CPU',
	'Class:Server/Attribute:cpu+' => '',
	'Class:Server/Attribute:ram' => 'RAM',
	'Class:Server/Attribute:ram+' => '',
	'Class:Server/Attribute:logicalvolumes_list' => 'Volumes lógicos',
	'Class:Server/Attribute:logicalvolumes_list+' => 'Todos os volumoes lógicos vinculados para esse servidor',
));

//
// Class: StorageSystem
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:StorageSystem' => 'Sistema Storage',
	'Class:StorageSystem+' => '',
	'Class:StorageSystem/Attribute:logicalvolume_list' => 'Volumes lógicos',
	'Class:StorageSystem/Attribute:logicalvolume_list+' => 'Todos os volumes lógicos neste sistema storage',
));

//
// Class: SANSwitch
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:SANSwitch' => 'Switch SAN',
	'Class:SANSwitch+' => '',
	'Class:SANSwitch/Attribute:datacenterdevice_list' => 'Dispositivos',
	'Class:SANSwitch/Attribute:datacenterdevice_list+' => 'Todos os dispositivos vinculados para esse switch SAN',
));

//
// Class: TapeLibrary
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:TapeLibrary' => 'Tape Library',
	'Class:TapeLibrary+' => '',
	'Class:TapeLibrary/Attribute:tapes_list' => 'Fitas',
	'Class:TapeLibrary/Attribute:tapes_list+' => 'Todas as fitas para essa Tape library',
));

//
// Class: NAS
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:NAS' => 'NAS',
	'Class:NAS+' => '',
	'Class:NAS/Attribute:nasfilesystem_list' => 'Sistemas de arquivos',
	'Class:NAS/Attribute:nasfilesystem_list+' => 'Todos os sistemas de arquivos para esse NAS',
));

//
// Class: PC
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:PC' => 'PC',
	'Class:PC+' => '',
	'Class:PC/Attribute:osfamily_id' => 'Família OS',
	'Class:PC/Attribute:osfamily_id+' => '',
	'Class:PC/Attribute:osfamily_name' => 'Nome família OS',
	'Class:PC/Attribute:osfamily_name+' => '',
	'Class:PC/Attribute:osversion_id' => 'Versão OS',
	'Class:PC/Attribute:osversion_id+' => '',
	'Class:PC/Attribute:osversion_name' => 'Nome versão OS',
	'Class:PC/Attribute:osversion_name+' => '',
	'Class:PC/Attribute:cpu' => 'CPU',
	'Class:PC/Attribute:cpu+' => '',
	'Class:PC/Attribute:ram' => 'RAM',
	'Class:PC/Attribute:ram+' => '',
	'Class:PC/Attribute:type' => 'Tipo',
	'Class:PC/Attribute:type+' => '',
	'Class:PC/Attribute:type/Value:desktop' => 'Desktop',
	'Class:PC/Attribute:type/Value:desktop+' => 'Desktop',
	'Class:PC/Attribute:type/Value:laptop' => 'Laptop',
	'Class:PC/Attribute:type/Value:laptop+' => 'Laptop',
));

//
// Class: Printer
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Printer' => 'Impressoras',
	'Class:Printer+' => '',
));

//
// Class: PowerConnection
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:PowerConnection' => 'Conexão energia',
	'Class:PowerConnection+' => '',
));

//
// Class: PowerSource
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:PowerSource' => 'Fonte energia',
	'Class:PowerSource+' => '',
	'Class:PowerSource/Attribute:pdus_list' => 'PDUs',
	'Class:PowerSource/Attribute:pdus_list+' => 'Todos os PDUs utilizando essa fonte de energia',
));

//
// Class: PDU
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:PDU' => 'PDU',
	'Class:PDU+' => '',
	'Class:PDU/Attribute:rack_id' => 'Rack',
	'Class:PDU/Attribute:rack_id+' => '',
	'Class:PDU/Attribute:rack_name' => 'Nome rack',
	'Class:PDU/Attribute:rack_name+' => '',
	'Class:PDU/Attribute:powerstart_id' => 'Fonte energia',
	'Class:PDU/Attribute:powerstart_id+' => '',
	'Class:PDU/Attribute:powerstart_name' => 'Nome fonte de energia',
	'Class:PDU/Attribute:powerstart_name+' => '',
));

//
// Class: Peripheral
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Peripheral' => 'Periféricos',
	'Class:Peripheral+' => '',
));

//
// Class: Enclosure
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Enclosure' => 'Gaveta',
	'Class:Enclosure+' => '',
	'Class:Enclosure/Attribute:rack_id' => 'Rack',
	'Class:Enclosure/Attribute:rack_id+' => '',
	'Class:Enclosure/Attribute:rack_name' => 'Nome rack',
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
	'Class:ApplicationSolution' => 'Solução aplicação',
	'Class:ApplicationSolution+' => '',
	'Class:ApplicationSolution/Attribute:functionalcis_list' => 'CIs',
	'Class:ApplicationSolution/Attribute:functionalcis_list+' => 'Todos os itens de configuração que compõem essa solução de aplicação',
	'Class:ApplicationSolution/Attribute:businessprocess_list' => 'Processos de negócio',
	'Class:ApplicationSolution/Attribute:businessprocess_list+' => 'Todos os processos do negócio dependente para essa solução de aplicação',
	'Class:ApplicationSolution/Attribute:status' => 'Estado',
	'Class:ApplicationSolution/Attribute:status+' => '',
	'Class:ApplicationSolution/Attribute:status/Value:active' => 'Ativo',
	'Class:ApplicationSolution/Attribute:status/Value:active+' => 'Ativo',
	'Class:ApplicationSolution/Attribute:status/Value:inactive' => 'Inativo',
	'Class:ApplicationSolution/Attribute:status/Value:inactive+' => 'Inativo',
));

//
// Class: BusinessProcess
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:BusinessProcess' => 'Processos de negócio',
	'Class:BusinessProcess+' => '',
	'Class:BusinessProcess/Attribute:applicationsolutions_list' => 'Solução aplicação',
	'Class:BusinessProcess/Attribute:applicationsolutions_list+' => 'Todas as soluções de aplicações que impactam este processo de negócio',
	'Class:BusinessProcess/Attribute:status' => 'Estado',
	'Class:BusinessProcess/Attribute:status+' => '',
	'Class:BusinessProcess/Attribute:status/Value:active' => 'Ativo',
	'Class:BusinessProcess/Attribute:status/Value:active+' => 'Ativo',
	'Class:BusinessProcess/Attribute:status/Value:inactive' => 'Inativo',
	'Class:BusinessProcess/Attribute:status/Value:inactive+' => 'Inativo',
));

//
// Class: SoftwareInstance
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:SoftwareInstance' => 'Instância Software',
	'Class:SoftwareInstance+' => '',
	'Class:SoftwareInstance/Attribute:system_id' => 'Sistema',
	'Class:SoftwareInstance/Attribute:system_id+' => '',
	'Class:SoftwareInstance/Attribute:system_name' => 'Nome sistema',
	'Class:SoftwareInstance/Attribute:system_name+' => '',
	'Class:SoftwareInstance/Attribute:software_id' => 'Software',
	'Class:SoftwareInstance/Attribute:software_id+' => '',
	'Class:SoftwareInstance/Attribute:software_name' => 'Nome software',
	'Class:SoftwareInstance/Attribute:software_name+' => '',
	'Class:SoftwareInstance/Attribute:softwarelicence_id' => 'Licença software',
	'Class:SoftwareInstance/Attribute:softwarelicence_id+' => '',
	'Class:SoftwareInstance/Attribute:softwarelicence_name' => 'Nome licença software',
	'Class:SoftwareInstance/Attribute:softwarelicence_name+' => '',
	'Class:SoftwareInstance/Attribute:path' => 'Caminho',
	'Class:SoftwareInstance/Attribute:path+' => '',
	'Class:SoftwareInstance/Attribute:status' => 'Estado',
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
	'Class:DBServer' => 'Serviço DB',
	'Class:DBServer+' => '',
	'Class:DBServer/Attribute:dbschema_list' => 'Schemas DB',
	'Class:DBServer/Attribute:dbschema_list+' => 'Todos os schemas para esse banco de dados',
));

//
// Class: WebServer
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:WebServer' => 'Serviço Web',
	'Class:WebServer+' => '',
	'Class:WebServer/Attribute:webapp_list' => 'Aplicações Web',
	'Class:WebServer/Attribute:webapp_list+' => 'Todas as aplicações web disponíveis para esse serviço web',
));

//
// Class: PCSoftware
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:PCSoftware' => 'PC Software',
	'Class:PCSoftware+' => '',
));

//
// Class: OtherSoftware
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:OtherSoftware' => 'Outros software',
	'Class:OtherSoftware+' => '',
));

//
// Class: MiddlewareInstance
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:MiddlewareInstance' => 'Instância Middleware',
	'Class:MiddlewareInstance+' => '',
	'Class:MiddlewareInstance/Attribute:middleware_id' => 'Middleware',
	'Class:MiddlewareInstance/Attribute:middleware_id+' => '',
	'Class:MiddlewareInstance/Attribute:middleware_name' => 'Nome middleware',
	'Class:MiddlewareInstance/Attribute:middleware_name+' => '',
));

//
// Class: DatabaseSchema
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:DatabaseSchema' => 'Schema Banco Dados',
	'Class:DatabaseSchema+' => '',
	'Class:DatabaseSchema/Attribute:dbserver_id' => 'Serviço DB',
	'Class:DatabaseSchema/Attribute:dbserver_id+' => '',
	'Class:DatabaseSchema/Attribute:dbserver_name' => 'Nome serviço DB',
	'Class:DatabaseSchema/Attribute:dbserver_name+' => '',
));

//
// Class: WebApplication
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:WebApplication' => 'Aplicação Web',
	'Class:WebApplication+' => '',
	'Class:WebApplication/Attribute:webserver_id' => 'Serviço Web',
	'Class:WebApplication/Attribute:webserver_id+' => '',
	'Class:WebApplication/Attribute:webserver_name' => 'Nome serviço Web',
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
	'Class:VirtualDevice/Attribute:status' => 'Estado',
	'Class:VirtualDevice/Attribute:status+' => '',
	'Class:VirtualDevice/Attribute:status/Value:implementation' => 'Implementação',
	'Class:VirtualDevice/Attribute:status/Value:implementation+' => 'Implementação',
	'Class:VirtualDevice/Attribute:status/Value:obsolete' => 'Obsoleto',
	'Class:VirtualDevice/Attribute:status/Value:obsolete+' => 'Obsoleto',
	'Class:VirtualDevice/Attribute:status/Value:production' => 'Produção',
	'Class:VirtualDevice/Attribute:status/Value:production+' => 'Produção',
	'Class:VirtualDevice/Attribute:status/Value:stock' => 'Suporte',
	'Class:VirtualDevice/Attribute:status/Value:stock+' => 'Suporte',
	'Class:VirtualDevice/Attribute:logicalvolumes_list' => 'Volume lógico',
	'Class:VirtualDevice/Attribute:logicalvolumes_list+' => 'Todos os volumes lógicos vinculados para esse dispositivo',
));

//
// Class: VirtualHost
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:VirtualHost' => 'Host virtual',
	'Class:VirtualHost+' => '',
	'Class:VirtualHost/Attribute:virtualmachine_list' => 'Máquinas Virtuais',
	'Class:VirtualHost/Attribute:virtualmachine_list+' => 'Todas as máquinas virtuais hospedados para esse Host',
));

//
// Class: Hypervisor
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Hypervisor' => 'Hypervisor',
	'Class:Hypervisor+' => '',
	'Class:Hypervisor/Attribute:farm_id' => 'Cluster/HA',
	'Class:Hypervisor/Attribute:farm_id+' => '',
	'Class:Hypervisor/Attribute:farm_name' => 'Nome Cluster/HA',
	'Class:Hypervisor/Attribute:farm_name+' => '',
	'Class:Hypervisor/Attribute:server_id' => 'Servidor',
	'Class:Hypervisor/Attribute:server_id+' => '',
	'Class:Hypervisor/Attribute:server_name' => 'Nome servidor',
	'Class:Hypervisor/Attribute:server_name+' => '',
));

//
// Class: Farm
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Farm' => 'Cluster/HA',
	'Class:Farm+' => '',
	'Class:Farm/Attribute:hypervisor_list' => 'Hypervisors',
	'Class:Farm/Attribute:hypervisor_list+' => 'Todos os hypervisors que compoem esse Cluster/HA',
));

//
// Class: VirtualMachine
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:VirtualMachine' => 'Máquina virtual',
	'Class:VirtualMachine+' => '',
	'Class:VirtualMachine/Attribute:virtualhost_id' => 'Host virtual',
	'Class:VirtualMachine/Attribute:virtualhost_id+' => '',
	'Class:VirtualMachine/Attribute:virtualhost_name' => 'Nome Host virtual',
	'Class:VirtualMachine/Attribute:virtualhost_name+' => '',
	'Class:VirtualMachine/Attribute:osfamily_id' => 'Família OS',
	'Class:VirtualMachine/Attribute:osfamily_id+' => '',
	'Class:VirtualMachine/Attribute:osfamily_name' => 'Nome família OS',
	'Class:VirtualMachine/Attribute:osfamily_name+' => '',
	'Class:VirtualMachine/Attribute:osversion_id' => 'Versão OS',
	'Class:VirtualMachine/Attribute:osversion_id+' => '',
	'Class:VirtualMachine/Attribute:osversion_name' => 'Nome versão OS',
	'Class:VirtualMachine/Attribute:osversion_name+' => '',
	'Class:VirtualMachine/Attribute:oslicence_id' => 'Licença OS',
	'Class:VirtualMachine/Attribute:oslicence_id+' => '',
	'Class:VirtualMachine/Attribute:oslicence_name' => 'Nome licença OS',
	'Class:VirtualMachine/Attribute:oslicence_name+' => '',
	'Class:VirtualMachine/Attribute:cpu' => 'CPU',
	'Class:VirtualMachine/Attribute:cpu+' => '',
	'Class:VirtualMachine/Attribute:ram' => 'RAM',
	'Class:VirtualMachine/Attribute:ram+' => '',
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
	'Class:LogicalVolume/Attribute:raid_level' => 'Raid nível',
	'Class:LogicalVolume/Attribute:raid_level+' => '',
	'Class:LogicalVolume/Attribute:size' => 'Tamanho',
	'Class:LogicalVolume/Attribute:size+' => '',
	'Class:LogicalVolume/Attribute:storagesystem_id' => 'Sistema arquivo',
	'Class:LogicalVolume/Attribute:storagesystem_id+' => '',
	'Class:LogicalVolume/Attribute:storagesystem_name' => 'Nome sistema arquivo',
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
	'Class:lnkServerToVolume/Attribute:volume_id' => 'Volume',
	'Class:lnkServerToVolume/Attribute:volume_id+' => '',
	'Class:lnkServerToVolume/Attribute:volume_name' => 'Nome volume',
	'Class:lnkServerToVolume/Attribute:volume_name+' => '',
	'Class:lnkServerToVolume/Attribute:server_id' => 'Servidor',
	'Class:lnkServerToVolume/Attribute:server_id+' => '',
	'Class:lnkServerToVolume/Attribute:server_name' => 'Nome servidor',
	'Class:lnkServerToVolume/Attribute:server_name+' => '',
	'Class:lnkServerToVolume/Attribute:size_used' => 'Tamanho usado',
	'Class:lnkServerToVolume/Attribute:size_used+' => '',
));

//
// Class: lnkVirtualDeviceToVolume
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkVirtualDeviceToVolume' => 'Link Dispositivo Virtual / Volume',
	'Class:lnkVirtualDeviceToVolume+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_id' => 'Volume',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_id+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_name' => 'Nome volume',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_name+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_id' => 'Dispositivo virtual',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_id+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_name' => 'Nome dispositivo virtual',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_name+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:size_used' => 'Tamanho usado',
	'Class:lnkVirtualDeviceToVolume/Attribute:size_used+' => '',
));

//
// Class: lnkSanToDatacenterDevice
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkSanToDatacenterDevice' => 'Link SAN / Dispositivo Datacenter',
	'Class:lnkSanToDatacenterDevice+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:san_id' => 'Switch SAN',
	'Class:lnkSanToDatacenterDevice/Attribute:san_id+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:san_name' => 'Nome switch SAN',
	'Class:lnkSanToDatacenterDevice/Attribute:san_name+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_id' => 'Dispositivo',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_id+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_name' => 'Nome Dispositivo',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_name+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:san_port' => 'FC SAN',
	'Class:lnkSanToDatacenterDevice/Attribute:san_port+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_port' => 'Dispositivo FC',
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
	'Class:Tape/Attribute:tapelibrary_id' => 'Tape library',
	'Class:Tape/Attribute:tapelibrary_id+' => '',
	'Class:Tape/Attribute:tapelibrary_name' => 'Nome Tape library',
	'Class:Tape/Attribute:tapelibrary_name+' => '',
));

//
// Class: NASFileSystem
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:NASFileSystem' => 'Sistema arquivo NAS',
	'Class:NASFileSystem+' => '',
	'Class:NASFileSystem/Attribute:name' => 'Nome',
	'Class:NASFileSystem/Attribute:name+' => '',
	'Class:NASFileSystem/Attribute:description' => 'Descrição',
	'Class:NASFileSystem/Attribute:description+' => '',
	'Class:NASFileSystem/Attribute:raid_level' => 'Raid nível',
	'Class:NASFileSystem/Attribute:raid_level+' => '',
	'Class:NASFileSystem/Attribute:size' => 'Tamanho',
	'Class:NASFileSystem/Attribute:size+' => '',
	'Class:NASFileSystem/Attribute:nas_id' => 'NAS',
	'Class:NASFileSystem/Attribute:nas_id+' => '',
	'Class:NASFileSystem/Attribute:nas_name' => 'Nome NAS',
	'Class:NASFileSystem/Attribute:nas_name+' => '',
));

//
// Class: Software
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Software' => 'Software',
	'Class:Software+' => '',
	'Class:Software/Attribute:name' => 'Nome',
	'Class:Software/Attribute:name+' => '',
	'Class:Software/Attribute:vendor' => 'Fabricante',
	'Class:Software/Attribute:vendor+' => '',
	'Class:Software/Attribute:version' => 'Versão',
	'Class:Software/Attribute:version+' => '',
	'Class:Software/Attribute:documents_list' => 'Documentos',
	'Class:Software/Attribute:documents_list+' => 'Todos os documentos vinculados a esse software',
	'Class:Software/Attribute:type' => 'Tipo',
	'Class:Software/Attribute:type+' => '',
	'Class:Software/Attribute:type/Value:DBServer' => 'Serviço DB',
	'Class:Software/Attribute:type/Value:DBServer+' => 'Serviço DB',
	'Class:Software/Attribute:type/Value:Middleware' => 'Middleware',
	'Class:Software/Attribute:type/Value:Middleware+' => 'Middleware',
	'Class:Software/Attribute:type/Value:OtherSoftware' => 'Outro Software',
	'Class:Software/Attribute:type/Value:OtherSoftware+' => 'Outro Software',
	'Class:Software/Attribute:type/Value:PCSoftware' => 'PC Software',
	'Class:Software/Attribute:type/Value:PCSoftware+' => 'PC Software',
	'Class:Software/Attribute:type/Value:WebServer' => 'Serviço Web',
	'Class:Software/Attribute:type/Value:WebServer+' => 'Serviço Web',
	'Class:Software/Attribute:softwareinstance_list' => 'Instâncias Software',
	'Class:Software/Attribute:softwareinstance_list+' => 'Todas as instâncias software para esse software',
	'Class:Software/Attribute:softwarepatch_list' => 'Software Patches',
	'Class:Software/Attribute:softwarepatch_list+' => 'Todos os patchs para esse software',
	'Class:Software/Attribute:softwarelicence_list' => 'Licença Software',
	'Class:Software/Attribute:softwarelicence_list+' => 'Todas as licenças software para esse software',
));

//
// Class: Patch
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Patch' => 'Patch',
	'Class:Patch+' => '',
	'Class:Patch/Attribute:name' => 'Nome',
	'Class:Patch/Attribute:name+' => '',
	'Class:Patch/Attribute:documents_list' => 'Documentos',
	'Class:Patch/Attribute:documents_list+' => 'Todos os documentos vinculados a esse patch',
	'Class:Patch/Attribute:description' => 'Descrição',
	'Class:Patch/Attribute:description+' => '',
	'Class:Patch/Attribute:finalclass' => 'Tipo',
	'Class:Patch/Attribute:finalclass+' => '',
));

//
// Class: OSPatch
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:OSPatch' => 'OS Patch',
	'Class:OSPatch+' => '',
	'Class:OSPatch/Attribute:functionalcis_list' => 'Dispositivos',
	'Class:OSPatch/Attribute:functionalcis_list+' => 'Todos os sistemas onde o patch está instalado',
	'Class:OSPatch/Attribute:osversion_id' => 'Versão OS',
	'Class:OSPatch/Attribute:osversion_id+' => '',
	'Class:OSPatch/Attribute:osversion_name' => 'Nome versão OS',
	'Class:OSPatch/Attribute:osversion_name+' => '',
));

//
// Class: SoftwarePatch
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:SoftwarePatch' => 'Software Patch',
	'Class:SoftwarePatch+' => '',
	'Class:SoftwarePatch/Attribute:software_id' => 'Software',
	'Class:SoftwarePatch/Attribute:software_id+' => '',
	'Class:SoftwarePatch/Attribute:software_name' => 'Nome software',
	'Class:SoftwarePatch/Attribute:software_name+' => '',
	'Class:SoftwarePatch/Attribute:softwareinstances_list' => 'Instâncias Software',
	'Class:SoftwarePatch/Attribute:softwareinstances_list+' => 'Todos os sistemas onde software patch está instalado',
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
	'Class:Licence/Attribute:documents_list+' => 'Todos os documentos vinculados a essa licença',
	'Class:Licence/Attribute:org_id' => 'Organização',
	'Class:Licence/Attribute:org_id+' => '',
	'Class:Licence/Attribute:organization_name' => 'Nome organização',
	'Class:Licence/Attribute:organization_name+' => 'Nome comum',
	'Class:Licence/Attribute:usage_limit' => 'Limite usado',
	'Class:Licence/Attribute:usage_limit+' => '',
	'Class:Licence/Attribute:description' => 'Descrição',
	'Class:Licence/Attribute:description+' => '',
	'Class:Licence/Attribute:start_date' => 'Data início',
	'Class:Licence/Attribute:start_date+' => '',
	'Class:Licence/Attribute:end_date' => 'Data final',
	'Class:Licence/Attribute:end_date+' => '',
	'Class:Licence/Attribute:licence_key' => 'Chave',
	'Class:Licence/Attribute:licence_key+' => '',
	'Class:Licence/Attribute:perpetual' => 'Permanente',
	'Class:Licence/Attribute:perpetual+' => '',
	'Class:Licence/Attribute:perpetual/Value:no' => 'Não',
	'Class:Licence/Attribute:perpetual/Value:no+' => 'Não',
	'Class:Licence/Attribute:perpetual/Value:yes' => 'Sim',
	'Class:Licence/Attribute:perpetual/Value:yes+' => 'sim',
	'Class:Licence/Attribute:finalclass' => 'Tipo',
	'Class:Licence/Attribute:finalclass+' => '',
));

//
// Class: OSLicence
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:OSLicence' => 'Licença OS',
	'Class:OSLicence+' => '',
	'Class:OSLicence/Attribute:osversion_id' => 'Versão OS',
	'Class:OSLicence/Attribute:osversion_id+' => '',
	'Class:OSLicence/Attribute:osversion_name' => 'Nome versão OS',
	'Class:OSLicence/Attribute:osversion_name+' => '',
	'Class:OSLicence/Attribute:virtualmachines_list' => 'Máquinas virtuais',
	'Class:OSLicence/Attribute:virtualmachines_list+' => 'Todas as máquinas virtuais onde essa licença é usada',
	'Class:OSLicence/Attribute:servers_list' => 'servidores',
	'Class:OSLicence/Attribute:servers_list+' => 'Todos os servidores onde essa licença é usada',
));

//
// Class: SoftwareLicence
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:SoftwareLicence' => 'Licença software',
	'Class:SoftwareLicence+' => '',
	'Class:SoftwareLicence/Attribute:software_id' => 'Software',
	'Class:SoftwareLicence/Attribute:software_id+' => '',
	'Class:SoftwareLicence/Attribute:software_name' => 'Nome software',
	'Class:SoftwareLicence/Attribute:software_name+' => '',
	'Class:SoftwareLicence/Attribute:softwareinstance_list' => 'Instâncias software',
	'Class:SoftwareLicence/Attribute:softwareinstance_list+' => 'Todos os sistemas onde essa licença é usada',
));

//
// Class: lnkDocumentToLicence
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkDocumentToLicence' => 'Link Documento / Licença',
	'Class:lnkDocumentToLicence+' => '',
	'Class:lnkDocumentToLicence/Attribute:licence_id' => 'Licença',
	'Class:lnkDocumentToLicence/Attribute:licence_id+' => '',
	'Class:lnkDocumentToLicence/Attribute:licence_name' => 'Nome licença',
	'Class:lnkDocumentToLicence/Attribute:licence_name+' => '',
	'Class:lnkDocumentToLicence/Attribute:document_id' => 'Documento',
	'Class:lnkDocumentToLicence/Attribute:document_id+' => '',
	'Class:lnkDocumentToLicence/Attribute:document_name' => 'Nome documento',
	'Class:lnkDocumentToLicence/Attribute:document_name+' => '',
));

//
// Class: Typology
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Typology' => 'Tipologia',
	'Class:Typology+' => '',
	'Class:Typology/Attribute:name' => 'Nome',
	'Class:Typology/Attribute:name+' => '',
	'Class:Typology/Attribute:finalclass' => 'Tipo',
	'Class:Typology/Attribute:finalclass+' => '',
));

//
// Class: OSVersion
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:OSVersion' => 'Versão OS',
	'Class:OSVersion+' => '',
	'Class:OSVersion/Attribute:osfamily_id' => 'Família OS',
	'Class:OSVersion/Attribute:osfamily_id+' => '',
	'Class:OSVersion/Attribute:osfamily_name' => 'Nome família OS',
	'Class:OSVersion/Attribute:osfamily_name+' => '',
));

//
// Class: OSFamily
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:OSFamily' => 'Família OS',
	'Class:OSFamily+' => '',
));

//
// Class: DocumentType
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:DocumentType' => 'Tipo documento',
	'Class:DocumentType+' => '',
));

//
// Class: ContactType
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:ContactType' => 'Tipo contato',
	'Class:ContactType+' => '',
));

//
// Class: Brand
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Brand' => 'Fabricante',
	'Class:Brand+' => '',
	'Class:Brand/Attribute:physicaldevices_list' => 'Dispositivos físicos',
	'Class:Brand/Attribute:physicaldevices_list+' => 'Todos os dispositivos físicos correspondentes a essa fabricante',
));

//
// Class: Model
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Model' => 'Modelo',
	'Class:Model+' => '',
	'Class:Model/Attribute:brand_id' => 'Fabricante',
	'Class:Model/Attribute:brand_id+' => '',
	'Class:Model/Attribute:brand_name' => 'Nome fabricante',
	'Class:Model/Attribute:brand_name+' => '',
	'Class:Model/Attribute:type' => 'Tipo dispositivo',
	'Class:Model/Attribute:type+' => '',
	'Class:Model/Attribute:type/Value:PowerSource' => 'Fonte energia',
	'Class:Model/Attribute:type/Value:PowerSource+' => 'Fonte energia',
	'Class:Model/Attribute:type/Value:DiskArray' => 'Array disco',
	'Class:Model/Attribute:type/Value:DiskArray+' => 'Array disco',
	'Class:Model/Attribute:type/Value:Enclosure' => 'Gaveta',
	'Class:Model/Attribute:type/Value:Enclosure+' => 'Gaveta',
	'Class:Model/Attribute:type/Value:IPPhone' => 'Telefone IP',
	'Class:Model/Attribute:type/Value:IPPhone+' => 'Telefone IP',
	'Class:Model/Attribute:type/Value:MobilePhone' => 'Telefone celular',
	'Class:Model/Attribute:type/Value:MobilePhone+' => 'Telefone celular',
	'Class:Model/Attribute:type/Value:NAS' => 'NAS',
	'Class:Model/Attribute:type/Value:NAS+' => 'NAS',
	'Class:Model/Attribute:type/Value:NetworkDevice' => 'Dispositivo rede',
	'Class:Model/Attribute:type/Value:NetworkDevice+' => 'Dispositivo rede',
	'Class:Model/Attribute:type/Value:PC' => 'PC',
	'Class:Model/Attribute:type/Value:PC+' => 'PC',
	'Class:Model/Attribute:type/Value:PDU' => 'PDU',
	'Class:Model/Attribute:type/Value:PDU+' => 'PDU',
	'Class:Model/Attribute:type/Value:Peripheral' => 'Periférico',
	'Class:Model/Attribute:type/Value:Peripheral+' => 'Periférico',
	'Class:Model/Attribute:type/Value:Printer' => 'Impressora',
	'Class:Model/Attribute:type/Value:Printer+' => 'Impressora',
	'Class:Model/Attribute:type/Value:Rack' => 'Rack',
	'Class:Model/Attribute:type/Value:Rack+' => 'Rack',
	'Class:Model/Attribute:type/Value:SANSwitch' => 'Switch SAN',
	'Class:Model/Attribute:type/Value:SANSwitch+' => 'Switch SAN',
	'Class:Model/Attribute:type/Value:Server' => 'Servidor',
	'Class:Model/Attribute:type/Value:Server+' => 'Servidor',
	'Class:Model/Attribute:type/Value:StorageSystem' => 'Sistema Storage',
	'Class:Model/Attribute:type/Value:StorageSystem+' => 'Sistema Storage',
	'Class:Model/Attribute:type/Value:Tablet' => 'Tablet',
	'Class:Model/Attribute:type/Value:Tablet+' => 'Tablet',
	'Class:Model/Attribute:type/Value:TapeLibrary' => 'Tape Library',
	'Class:Model/Attribute:type/Value:TapeLibrary+' => 'Tape Library',
	'Class:Model/Attribute:type/Value:Phone' => 'Telefone',
	'Class:Model/Attribute:type/Value:Phone+' => 'Telefone',
	'Class:Model/Attribute:physicaldevices_list' => 'Dispositivo físico',
	'Class:Model/Attribute:physicaldevices_list+' => 'Todos os dispositivos físicos correspondentes a esse modelo',
));

//
// Class: NetworkDeviceType
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:NetworkDeviceType' => 'Tipo dispositivo rede',
	'Class:NetworkDeviceType+' => '',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list' => 'Dispositivo rede',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list+' => 'Todos os dispositivo de rede correspondentes a esse tipo',
));

//
// Class: IOSVersion
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:IOSVersion' => 'Versão IOS',
	'Class:IOSVersion+' => '',
	'Class:IOSVersion/Attribute:brand_id' => 'Fabricante',
	'Class:IOSVersion/Attribute:brand_id+' => '',
	'Class:IOSVersion/Attribute:brand_name' => 'Nome fabricante',
	'Class:IOSVersion/Attribute:brand_name+' => '',
));

//
// Class: lnkDocumentToPatch
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkDocumentToPatch' => 'Link Documento / Patch',
	'Class:lnkDocumentToPatch+' => '',
	'Class:lnkDocumentToPatch/Attribute:patch_id' => 'Patch',
	'Class:lnkDocumentToPatch/Attribute:patch_id+' => '',
	'Class:lnkDocumentToPatch/Attribute:patch_name' => 'Nome patch',
	'Class:lnkDocumentToPatch/Attribute:patch_name+' => '',
	'Class:lnkDocumentToPatch/Attribute:document_id' => 'Documento',
	'Class:lnkDocumentToPatch/Attribute:document_id+' => '',
	'Class:lnkDocumentToPatch/Attribute:document_name' => 'Nome documento',
	'Class:lnkDocumentToPatch/Attribute:document_name+' => '',
));

//
// Class: lnkSoftwareInstanceToSoftwarePatch
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkSoftwareInstanceToSoftwarePatch' => 'Link Instância Software / Software Patch',
	'Class:lnkSoftwareInstanceToSoftwarePatch+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_id' => 'Software patch',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_id+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_name' => 'Nome software patch',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_name+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_id' => 'Instância software',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_id+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_name' => 'Nome instância software',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_name+' => '',
));

//
// Class: lnkFunctionalCIToOSPatch
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkFunctionalCIToOSPatch' => 'Link CI / OS patch',
	'Class:lnkFunctionalCIToOSPatch+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_id' => 'OS patch',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_id+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_name' => 'Nome OS patch',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_name+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_id' => 'CIs',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_name' => 'Nome CI',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_name+' => '',
));

//
// Class: lnkDocumentToSoftware
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkDocumentToSoftware' => 'Link Documento / Software',
	'Class:lnkDocumentToSoftware+' => '',
	'Class:lnkDocumentToSoftware/Attribute:software_id' => 'Software',
	'Class:lnkDocumentToSoftware/Attribute:software_id+' => '',
	'Class:lnkDocumentToSoftware/Attribute:software_name' => 'Nome software',
	'Class:lnkDocumentToSoftware/Attribute:software_name+' => '',
	'Class:lnkDocumentToSoftware/Attribute:document_id' => 'Documento',
	'Class:lnkDocumentToSoftware/Attribute:document_id+' => '',
	'Class:lnkDocumentToSoftware/Attribute:document_name' => 'Nome documento',
	'Class:lnkDocumentToSoftware/Attribute:document_name+' => '',
));

//
// Class: lnkContactToFunctionalCI
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkContactToFunctionalCI' => 'Link Contato / CI',
	'Class:lnkContactToFunctionalCI+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id' => 'CIs',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name' => 'Nome CI',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id' => 'Contato',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name' => 'Nome contato',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name+' => '',
));

//
// Class: lnkDocumentToFunctionalCI
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkDocumentToFunctionalCI' => 'Link Documento / CI',
	'Class:lnkDocumentToFunctionalCI+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_id' => 'CIs',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_name' => 'Nome CI',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_id' => 'Documento',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_id+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_name' => 'Nome documento',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_name+' => '',
));

//
// Class: Subnet
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Subnet' => 'Sub-rede',
	'Class:Subnet+' => '',
	'Class:Subnet/Attribute:description' => 'Descrição',
	'Class:Subnet/Attribute:description+' => '',
	'Class:Subnet/Attribute:subnet_name' => 'Nome Sub-rede',
	'Class:Subnet/Attribute:subnet_name+' => '',
	'Class:Subnet/Attribute:org_id' => 'Organização',
	'Class:Subnet/Attribute:org_id+' => '',
	'Class:Subnet/Attribute:org_name' => 'Nome',
	'Class:Subnet/Attribute:org_name+' => 'Nome comum',
	'Class:Subnet/Attribute:ip' => 'IP',
	'Class:Subnet/Attribute:ip+' => '',
	'Class:Subnet/Attribute:ip_mask' => 'Máscara rede',
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
	'Class:VLAN/Attribute:vlan_tag' => 'Nome VLAN',
	'Class:VLAN/Attribute:vlan_tag+' => '',
	'Class:VLAN/Attribute:description' => 'Descrição',
	'Class:VLAN/Attribute:description+' => '',
	'Class:VLAN/Attribute:org_id' => 'Organização',
	'Class:VLAN/Attribute:org_id+' => '',
	'Class:VLAN/Attribute:org_name' => 'Nome organização',
	'Class:VLAN/Attribute:org_name+' => 'Nome comum',
	'Class:VLAN/Attribute:subnets_list' => 'Sub-redes',
	'Class:VLAN/Attribute:subnets_list+' => '',
	'Class:VLAN/Attribute:physicalinterfaces_list' => 'Interfaces rede física',
	'Class:VLAN/Attribute:physicalinterfaces_list+' => '',
));

//
// Class: lnkSubnetToVLAN
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkSubnetToVLAN' => 'Link Sub-rede / VLAN',
	'Class:lnkSubnetToVLAN+' => '',
	'Class:lnkSubnetToVLAN/Attribute:subnet_id' => 'Sub-rede',
	'Class:lnkSubnetToVLAN/Attribute:subnet_id+' => '',
	'Class:lnkSubnetToVLAN/Attribute:subnet_ip' => 'IP sub-rede',
	'Class:lnkSubnetToVLAN/Attribute:subnet_ip+' => '',
	'Class:lnkSubnetToVLAN/Attribute:subnet_name' => 'Nome sub-rede',
	'Class:lnkSubnetToVLAN/Attribute:subnet_name+' => '',
	'Class:lnkSubnetToVLAN/Attribute:vlan_id' => 'VLAN',
	'Class:lnkSubnetToVLAN/Attribute:vlan_id+' => '',
	'Class:lnkSubnetToVLAN/Attribute:vlan_tag' => 'Nome VLAN',
	'Class:lnkSubnetToVLAN/Attribute:vlan_tag+' => '',
));

//
// Class: NetworkInterface
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:NetworkInterface' => 'Placa de rede',
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
	'Class:PhysicalInterface/Attribute:connectableci_id' => 'Dispositivo',
	'Class:PhysicalInterface/Attribute:connectableci_id+' => '',
	'Class:PhysicalInterface/Attribute:connectableci_name' => 'Nome dispositivo',
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
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_id' => 'Interface física',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_id+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_name' => 'Nome interface física',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_name+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_id' => 'Dispositivo',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_id+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_name' => 'Nome dispositivo',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_name+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_id' => 'VLAN',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_id+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_tag' => 'Nome VLAN',
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
	'Class:LogicalInterface/Attribute:virtualmachine_name' => 'Nome máquina virtual',
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
	'Class:FiberChannelInterface/Attribute:datacenterdevice_name' => 'Nome dispositivo',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_name+' => '',
));

//
// Class: lnkConnectableCIToNetworkDevice
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkConnectableCIToNetworkDevice' => 'Link ConnectableCI / NetworkDevice',
	'Class:lnkConnectableCIToNetworkDevice+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_id' => 'Dispositivo rede',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_id+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_name' => 'Nome dispositivo rede',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_name+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_id' => 'Dispositivo conectado',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_id+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_name' => 'Nome dispositivo conectado',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_name+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:network_port' => 'Porta de rede',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:network_port+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:device_port' => 'Porta dispositivo',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:device_port+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type' => 'Tipo conexão',
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
	'Class:lnkApplicationSolutionToFunctionalCI' => 'Link ApplicationSolution / CI',
	'Class:lnkApplicationSolutionToFunctionalCI+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_id' => 'Solução aplicação',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_id+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_name' => 'Nome solução aplicação',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_name+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_id' => 'CIs',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_name' => 'Nome CI',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_name+' => '',
));

//
// Class: lnkApplicationSolutionToBusinessProcess
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkApplicationSolutionToBusinessProcess' => 'Link ApplicationSolution / BusinessProcess',
	'Class:lnkApplicationSolutionToBusinessProcess+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_id' => 'Processos de negócio',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_id+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_name' => 'Nome processos de negócio',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_name+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_id' => 'Solução aplicação',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_id+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_name' => 'Nome solução aplicação',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_name+' => '',
));

//
// Class: lnkPersonToTeam
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkPersonToTeam' => 'Link Pessoa / Equipe',
	'Class:lnkPersonToTeam+' => '',
	'Class:lnkPersonToTeam/Attribute:team_id' => 'Equipe',
	'Class:lnkPersonToTeam/Attribute:team_id+' => '',
	'Class:lnkPersonToTeam/Attribute:team_name' => 'Nome equipe',
	'Class:lnkPersonToTeam/Attribute:team_name+' => '',
	'Class:lnkPersonToTeam/Attribute:person_id' => 'Pessoa',
	'Class:lnkPersonToTeam/Attribute:person_id+' => '',
	'Class:lnkPersonToTeam/Attribute:person_name' => 'Nome pessoa',
	'Class:lnkPersonToTeam/Attribute:person_name+' => '',
	'Class:lnkPersonToTeam/Attribute:role_id' => 'Função',
	'Class:lnkPersonToTeam/Attribute:role_id+' => '',
	'Class:lnkPersonToTeam/Attribute:role_name' => 'Nome função',
	'Class:lnkPersonToTeam/Attribute:role_name+' => '',
));

//
// Class: Group
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Group' => 'Grupo',
	'Class:Group+' => '',
	'Class:Group/Attribute:name' => 'Nome',
	'Class:Group/Attribute:name+' => '',
	'Class:Group/Attribute:status' => 'Status',
	'Class:Group/Attribute:status+' => '',
	'Class:Group/Attribute:status/Value:implementation' => 'Implementação',
	'Class:Group/Attribute:status/Value:implementation+' => 'Implementação',
	'Class:Group/Attribute:status/Value:obsolete' => 'Obsoleto',
	'Class:Group/Attribute:status/Value:obsolete+' => 'Obsoleto',
	'Class:Group/Attribute:status/Value:production' => 'Produção',
	'Class:Group/Attribute:status/Value:production+' => 'Produção',
	'Class:Group/Attribute:org_id' => 'Organização',
	'Class:Group/Attribute:org_id+' => '',
	'Class:Group/Attribute:owner_name' => 'Nome',
	'Class:Group/Attribute:owner_name+' => 'Nome comum',
	'Class:Group/Attribute:description' => 'Descrição',
	'Class:Group/Attribute:description+' => '',
	'Class:Group/Attribute:type' => 'Tipo',
	'Class:Group/Attribute:type+' => '',
	'Class:Group/Attribute:parent_id' => 'Grupo principal',

	'Class:Group/Attribute:parent_id+' => '',
	'Class:Group/Attribute:parent_name' => 'Nome',
	'Class:Group/Attribute:parent_name+' => '',
	'Class:Group/Attribute:ci_list' => 'CIs ligados',
	'Class:Group/Attribute:ci_list+' => 'Todos os itens de configuração associada a esse grupo',
	'Class:Group/Attribute:parent_id_friendlyname' => 'Grupo principal',
	'Class:Group/Attribute:parent_id_friendlyname+' => '',
));

//
// Class: lnkGroupToCI
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkGroupToCI' => 'Link Grupo / CI',
	'Class:lnkGroupToCI+' => '',
	'Class:lnkGroupToCI/Attribute:group_id' => 'Grupo',
	'Class:lnkGroupToCI/Attribute:group_id+' => '',
	'Class:lnkGroupToCI/Attribute:group_name' => 'Nome',
	'Class:lnkGroupToCI/Attribute:group_name+' => '',
	'Class:lnkGroupToCI/Attribute:ci_id' => 'CI',
	'Class:lnkGroupToCI/Attribute:ci_id+' => '',
	'Class:lnkGroupToCI/Attribute:ci_name' => 'Nome',
	'Class:lnkGroupToCI/Attribute:ci_name+' => '',
	'Class:lnkGroupToCI/Attribute:reason' => 'Razão',
	'Class:lnkGroupToCI/Attribute:reason+' => '',
));


//
// Application Menu
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
'Menu:DataAdministration' => 'Administração Dados',
'Menu:DataAdministration+' => 'Administração Dados',
'Menu:Catalogs' => 'Catálogos',
'Menu:Catalogs+' => 'Tipos dados',
'Menu:Audit' => 'Auditoria',
'Menu:Audit+' => 'Auditoria',
'Menu:CSVImport' => 'Importar CSV',
'Menu:CSVImport+' => 'Criação ou atualização em massa',
'Menu:Organization' => 'Organizações',
'Menu:Organization+' => 'Todas organizações',
'Menu:Application' => 'Applicações',
'Menu:Application+' => 'Todas aplicações',
'Menu:DBServer' => 'Serviços Banco de Dados',
'Menu:DBServer+' => 'Serviços Banco de Dados',
'Menu:ConfigManagement' => 'Gerenciamento Configurações',
'Menu:ConfigManagement+' => 'Gerenciamento Configurações',
'Menu:ConfigManagementOverview' => 'Visão geral',
'Menu:ConfigManagementOverview+' => 'Visão geral',
'Menu:Contact' => 'Contatos',
'Menu:Contact+' => 'Contatos',
'Menu:Contact:Count' => '%1$d contatos',
'Menu:Person' => 'Pessoas',
'Menu:Person+' => 'Todas pessoas',
'Menu:Team' => 'Equipes',
'Menu:Team+' => 'Todas equipes',
'Menu:Document' => 'Documentos',
'Menu:Document+' => 'Todos documentos',
'Menu:Location' => 'Localidades',
'Menu:Location+' => 'Todas localidades',
'Menu:ConfigManagementCI' => 'Itens de configuração',
'Menu:ConfigManagementCI+' => 'Itens de configuração',
'Menu:BusinessProcess' => 'Processos de negócios',
'Menu:BusinessProcess+' => 'Todos processos de negócios',
'Menu:ApplicationSolution' => 'Solução aplicação',
'Menu:ApplicationSolution+' => 'Todas soluções aplicações',
'Menu:ConfigManagementSoftware' => 'Gerenciamento aplicações',
'Menu:Licence' => 'Licenças',
'Menu:Licence+' => 'Todoas licenças',
'Menu:Patch' => 'Patches',
'Menu:Patch+' => 'Todos patches',
'Menu:ApplicationInstance' => 'Software instalados',
'Menu:ApplicationInstance+' => 'Serviços aplicações e Banco de dados',
'Menu:ConfigManagementHardware' => 'Gerenciamento Infra-estrutura',
'Menu:Subnet' => 'Sub-redes',
'Menu:Subnet+' => 'Todas sub-redes',
'Menu:NetworkDevice' => 'Dispositivos rede',
'Menu:NetworkDevice+' => 'Todos dispositivos rede',
'Menu:Server' => 'Servidores',
'Menu:Server+' => 'Todos servidores',
'Menu:Printer' => 'Impressoras',
'Menu:Printer+' => 'Todas impressoras',
'Menu:MobilePhone' => 'Telefone celulares',
'Menu:MobilePhone+' => 'Todos telefone celulares',
'Menu:PC' => 'Estação de trabalho',
'Menu:PC+' => 'Todas estação de trabalho',
'Menu:NewContact' => 'Novo contato',
'Menu:NewContact+' => 'Novo contato',
'Menu:SearchContacts' => 'Pesquisar por contatos',
'Menu:SearchContacts+' => 'Pesquisar por contatos',
'Menu:NewCI' => 'Novo CI',
'Menu:NewCI+' => 'Novo CI',
'Menu:SearchCIs' => 'Pesquisar por CIs',
'Menu:SearchCIs+' => 'Pesquisar por CIs',
'Menu:ConfigManagement:Devices' => 'Dispositivos',
'Menu:ConfigManagement:AllDevices' => 'Infra-estrutura',
'Menu:ConfigManagement:virtualization' => 'Virtualização',
'Menu:ConfigManagement:EndUsers' => 'Dispositivos usuário final',
'Menu:ConfigManagement:SWAndApps' => 'Software e aplicações',
'Menu:ConfigManagement:Misc' => 'Diversos',
'Menu:Group' => 'Grupos de CIs',
'Menu:Group+' => 'Grupos de CIs',
'Menu:ConfigManagement:Shortcuts' => 'Atalhos',
'Menu:ConfigManagement:AllContacts' => 'Todos contatos: %1$d',
'Menu:Typology' => 'Configuração tipologia',
'Menu:Typology+' => 'Configuração tipologia',
'Menu:OSVersion' => 'Versão OS',
'Menu:OSVersion+' => '',
'Menu:Software' => 'Catálogo software',
'Menu:Software+' => 'Catálogo software',
'UI_WelcomeMenu_AllConfigItems' => 'Índice',
'Menu:ConfigManagement:Typology' => 'Configuração tipologia',

));


// Add translation for Fieldsets

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
'Server:baseinfo' => 'Informações gerais',
'Server:Date' => 'Data',
'Server:moreinfo' => 'Mais informações',
'Server:otherinfo' => 'Outras informações',
'Person:info' => 'Informações gerais',
'Person:notifiy' => 'Notificação',
'Class:Subnet/Tab:IPUsage' => 'IP usado',
'Class:Subnet/Tab:IPUsage-explain' => 'Placas de rede contendo IP na faixa: <em>%1$s</em> para <em>%2$s</em>',
'Class:Subnet/Tab:FreeIPs' => 'IPs livres',
'Class:Subnet/Tab:FreeIPs-count' => 'IPs livres: %1$s',
'Class:Subnet/Tab:FreeIPs-explain' => 'Aqui uma faixa de 10 endereços IPs livres',
'Class:Document:PreviewTab' => 'Visualização',
));
?>
