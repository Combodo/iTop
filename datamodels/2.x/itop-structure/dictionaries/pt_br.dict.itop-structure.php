<?php
// Copyright (C) 2010-2021 Combodo SARL
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
 * @author      Benjamin Planque <benjamin.planque@combodo.com>
 * @copyright   Copyright (C) 2010-2018 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
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
	'Class:Organization/Attribute:overview' => 'Visão geral',
	'Organization:Overview:FunctionalCIs' => 'Itens de configuração desta organização',
	'Organization:Overview:FunctionalCIs:subtitle' => 'por tipo',
	'Organization:Overview:Users' => 'Usuários iTop dentro desta organização',
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
	'Class:Person/Attribute:picture' => 'Foto',
	'Class:Person/Attribute:picture+' => '',
	'Class:Person/UniquenessRule:employee_number+' => 'O número de funcionário deve ser único na organização',
	'Class:Person/UniquenessRule:employee_number' => 'Já existe uma pessoa na organização \'$this->org_name$\' com o mesmo número de funcionário',
	'Class:Person/UniquenessRule:name+' => 'O nome do funcionário deve ser único dentro de sua organização',
	'Class:Person/UniquenessRule:name' => 'Já existe uma pessoa na organização \'$this->org_name$\' com o mesmo nome',
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
	'Class:Document/Attribute:version' => 'Versão',
	'Class:Document/Attribute:version+' => '',
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
	'Menu:ConfigManagement' => 'Gerenciamento Configurações',
	'Menu:ConfigManagement+' => 'Gerenciamento Configurações',
	'Menu:ConfigManagementCI' => 'Itens de configuração',
	'Menu:ConfigManagementCI+' => 'Itens de configuração',
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
	'Menu:NewContact' => 'Novo contato',
	'Menu:NewContact+' => 'Novo contato',
	'Menu:SearchContacts' => 'Pesquisar por contatos',
	'Menu:SearchContacts+' => 'Pesquisar por contatos',
	'Menu:ConfigManagement:Shortcuts' => 'Atalhos',
	'Menu:ConfigManagement:AllContacts' => 'Todos contatos: %1$d',
	'Menu:Typology' => 'Configuração tipologia',
	'Menu:Typology+' => 'Configuração tipologia',
	'UI_WelcomeMenu_AllConfigItems' => 'Índice',
	'Menu:ConfigManagement:Typology' => 'Configuração tipologia',
));

// Add translation for Fieldsets

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Person:info' => 'Informações gerais',
	'UserLocal:info' => 'General information~~',
	'Person:personal_info' => 'Informação pessoal',
	'Person:notifiy' => 'Notificação',
));

// Themes
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'theme:fullmoon' => 'Full moon 🌕~~',
	'theme:test-red' => 'Test instance (Red)~~',
));
