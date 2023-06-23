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
 * @author      Benjamin Planque <benjamin.planque@combodo.com>
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
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
	'Class:Organization/Attribute:code+' => 'Código da organização (CNPJ, Siret, DUNS, ...)',
	'Class:Organization/Attribute:status' => 'Status',
	'Class:Organization/Attribute:status+' => '',
	'Class:Organization/Attribute:status/Value:active' => 'Ativo',
	'Class:Organization/Attribute:status/Value:active+' => '',
	'Class:Organization/Attribute:status/Value:inactive' => 'Inativo',
	'Class:Organization/Attribute:status/Value:inactive+' => '',
	'Class:Organization/Attribute:parent_id' => 'Pai',
	'Class:Organization/Attribute:parent_id+' => 'Organização pai',
	'Class:Organization/Attribute:parent_name' => 'Organização pai',
	'Class:Organization/Attribute:parent_name+' => 'Nome da organização pai',
	'Class:Organization/Attribute:deliverymodel_id' => 'Modelo de entrega',
	'Class:Organization/Attribute:deliverymodel_id+' => '',
	'Class:Organization/Attribute:deliverymodel_name' => 'Nome do modelo de entrega',
	'Class:Organization/Attribute:deliverymodel_name+' => '',
	'Class:Organization/Attribute:parent_id_friendlyname' => 'Pai (nome amigável)',
	'Class:Organization/Attribute:parent_id_friendlyname+' => 'Nome amigável da organização pai',
	'Class:Organization/Attribute:overview' => 'Visão geral',
	'Organization:Overview:FunctionalCIs' => 'Itens de configuração associadas à esta organização',
	'Organization:Overview:FunctionalCIs:subtitle' => 'por tipo',
	'Organization:Overview:Users' => 'Usuários do '.ITOP_APPLICATION_SHORT.' associados à esta organização',
));

//
// Class: Location
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Location' => 'Localização',
	'Class:Location+' => 'Qualquer tipo de localização: Região, País, Cidade, Lugar, Edifício, Andar, Sala, Rack, etc.',
	'Class:Location/Attribute:name' => 'Nome',
	'Class:Location/Attribute:name+' => '',
	'Class:Location/Attribute:status' => 'Status',
	'Class:Location/Attribute:status+' => '',
	'Class:Location/Attribute:status/Value:active' => 'Ativo',
	'Class:Location/Attribute:status/Value:active+' => '',
	'Class:Location/Attribute:status/Value:inactive' => 'Inativo',
	'Class:Location/Attribute:status/Value:inactive+' => '',
	'Class:Location/Attribute:org_id' => 'Organização',
	'Class:Location/Attribute:org_id+' => '',
	'Class:Location/Attribute:org_name' => 'Nome da organização',
	'Class:Location/Attribute:org_name+' => '',
	'Class:Location/Attribute:address' => 'Endereço',
	'Class:Location/Attribute:address+' => '',
	'Class:Location/Attribute:postal_code' => 'CEP',
	'Class:Location/Attribute:postal_code+' => '',
	'Class:Location/Attribute:city' => 'Cidade',
	'Class:Location/Attribute:city+' => '',
	'Class:Location/Attribute:country' => 'País',
	'Class:Location/Attribute:country+' => '',
	'Class:Location/Attribute:physicaldevice_list' => 'Dispositivos',
	'Class:Location/Attribute:physicaldevice_list+' => 'Todos os dispositivos associados à esta localização',
	'Class:Location/Attribute:person_list' => 'Contatos',
	'Class:Location/Attribute:person_list+' => 'Todos os contatos associados à esta localização',
));

//
// Class: Contact
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Contact' => 'Contato',
	'Class:Contact+' => '',
	'Class:Contact/ComplementaryName' => '%1$s - %2$s~~',
	'Class:Contact/Attribute:name' => 'Nome',
	'Class:Contact/Attribute:name+' => '',
	'Class:Contact/Attribute:status' => 'Status',
	'Class:Contact/Attribute:status+' => '',
	'Class:Contact/Attribute:status/Value:active' => 'Ativo',
	'Class:Contact/Attribute:status/Value:active+' => '',
	'Class:Contact/Attribute:status/Value:inactive' => 'Inativo',
	'Class:Contact/Attribute:status/Value:inactive+' => '',
	'Class:Contact/Attribute:org_id' => 'Organização',
	'Class:Contact/Attribute:org_id+' => '',
	'Class:Contact/Attribute:org_name' => 'Nome da organização',
	'Class:Contact/Attribute:org_name+' => '',
	'Class:Contact/Attribute:email' => 'E-mail',
	'Class:Contact/Attribute:email+' => '',
	'Class:Contact/Attribute:phone' => 'Telefone',
	'Class:Contact/Attribute:phone+' => '',
	'Class:Contact/Attribute:notify' => 'Notificações',
	'Class:Contact/Attribute:notify+' => '',
	'Class:Contact/Attribute:notify/Value:no' => 'Não',
	'Class:Contact/Attribute:notify/Value:no+' => '',
	'Class:Contact/Attribute:notify/Value:yes' => 'Sim',
	'Class:Contact/Attribute:notify/Value:yes+' => '',
	'Class:Contact/Attribute:function' => 'Função',
	'Class:Contact/Attribute:function+' => '',
	'Class:Contact/Attribute:cis_list' => 'ICs',
	'Class:Contact/Attribute:cis_list+' => 'Todos os itens de configuração associados a este contato',
	'Class:Contact/Attribute:finalclass' => 'Tipo de contato',
	'Class:Contact/Attribute:finalclass+' => '',
));

//
// Class: Person
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Person' => 'Pessoa',
	'Class:Person+' => '',
	'Class:Person/ComplementaryName' => '%1$s - %2$s~~',
	'Class:Person/Attribute:name' => 'Último nome',
	'Class:Person/Attribute:name+' => '',
	'Class:Person/Attribute:first_name' => 'Primeiro nome',
	'Class:Person/Attribute:first_name+' => '',
	'Class:Person/Attribute:employee_number' => 'Número de colaborador',
	'Class:Person/Attribute:employee_number+' => '',
	'Class:Person/Attribute:mobile_phone' => 'Celular',
	'Class:Person/Attribute:mobile_phone+' => '',
	'Class:Person/Attribute:location_id' => 'Localização',
	'Class:Person/Attribute:location_id+' => '',
	'Class:Person/Attribute:location_name' => 'Nome da localização',
	'Class:Person/Attribute:location_name+' => '',
	'Class:Person/Attribute:manager_id' => 'Gerente',
	'Class:Person/Attribute:manager_id+' => '',
	'Class:Person/Attribute:manager_name' => 'Nome do gerente',
	'Class:Person/Attribute:manager_name+' => '',
	'Class:Person/Attribute:team_list' => 'Equipes',
	'Class:Person/Attribute:team_list+' => 'Todas as equipes que essa pessoa pertence',
	'Class:Person/Attribute:tickets_list' => 'Solicitações',
	'Class:Person/Attribute:tickets_list+' => 'Todos as solicitações que essa pessoa solicitou',
	'Class:Person/Attribute:user_list' => 'Users~~',
	'Class:Person/Attribute:user_list+' => 'All the Users associated to this person~~',
	'Class:Person/Attribute:manager_id_friendlyname' => 'Nome amigável do gerente',
	'Class:Person/Attribute:manager_id_friendlyname+' => 'Nome amigável do gerente do usuário correspondente',
	'Class:Person/Attribute:picture' => 'Imagem',
	'Class:Person/Attribute:picture+' => '',
	'Class:Person/UniquenessRule:employee_number+' => 'O número do colaborador deve ser único na organização',
	'Class:Person/UniquenessRule:employee_number' => 'Já existe uma pessoa na organização \'$this->org_name$\' com o mesmo número de colaborador',
	'Class:Person/UniquenessRule:name+' => 'O nome do colaborador deve ser único dentro de sua organização',
	'Class:Person/UniquenessRule:name' => 'Já existe uma pessoa na organização \'$this->org_name$\' com o mesmo nome',
	'Class:Person/Error:ChangingOrgDenied' => 'Impossible to move this person under organization \'%1$s\' as it would break his access to the User Portal, his associated user not being allowed on this organization~~',
));

//
// Class: Team
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Team' => 'Equipe',
	'Class:Team+' => '',
	'Class:Team/ComplementaryName' => '%1$s - %2$s~~',
	'Class:Team/Attribute:persons_list' => 'Membros',
	'Class:Team/Attribute:persons_list+' => 'Todas as pessoas que pertencem a essa equipe',
	'Class:Team/Attribute:tickets_list' => 'Solicitações',
	'Class:Team/Attribute:tickets_list+' => 'Todas as solicitações atribuídas a essa equipe',
));

//
// Class: Document
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Document' => 'Documento',
	'Class:Document+' => '',
	'Class:Document/ComplementaryName' => '%1$s - %2$s - %3$s~~',
	'Class:Document/Attribute:name' => 'Nome',
	'Class:Document/Attribute:name+' => '',
	'Class:Document/Attribute:org_id' => 'Organização',
	'Class:Document/Attribute:org_id+' => '',
	'Class:Document/Attribute:org_name' => 'Nome da organização',
	'Class:Document/Attribute:org_name+' => '',
	'Class:Document/Attribute:documenttype_id' => 'Tipo de documento',
	'Class:Document/Attribute:documenttype_id+' => '',
	'Class:Document/Attribute:documenttype_name' => 'Nome do tipo de documento',
	'Class:Document/Attribute:documenttype_name+' => '',
	'Class:Document/Attribute:version' => 'Versão',
	'Class:Document/Attribute:version+' => '',
	'Class:Document/Attribute:description' => 'Descrição',
	'Class:Document/Attribute:description+' => '',
	'Class:Document/Attribute:status' => 'Status',
	'Class:Document/Attribute:status+' => '',
	'Class:Document/Attribute:status/Value:draft' => 'Rascunho',
	'Class:Document/Attribute:status/Value:draft+' => '',
	'Class:Document/Attribute:status/Value:obsolete' => 'Obsoleto',
	'Class:Document/Attribute:status/Value:obsolete+' => '',
	'Class:Document/Attribute:status/Value:published' => 'Publicado',
	'Class:Document/Attribute:status/Value:published+' => '',
	'Class:Document/Attribute:cis_list' => 'CIs',
	'Class:Document/Attribute:cis_list+' => 'Todos os itens de configuração associados a este documento',
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
	'Class:DocumentType' => 'Tipo de documento',
	'Class:DocumentType+' => '',
));

//
// Class: ContactType
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:ContactType' => 'Tipo de contato',
	'Class:ContactType+' => '',
));

//
// Class: lnkPersonToTeam
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkPersonToTeam' => 'Link Pessoa / Equipe',
	'Class:lnkPersonToTeam+' => '',
	'Class:lnkPersonToTeam/Name' => '%1$s / %2$s~~',
	'Class:lnkPersonToTeam/Name+' => '~~',
	'Class:lnkPersonToTeam/Attribute:team_id' => 'Equipe',
	'Class:lnkPersonToTeam/Attribute:team_id+' => '',
	'Class:lnkPersonToTeam/Attribute:team_name' => 'Nome da equipe',
	'Class:lnkPersonToTeam/Attribute:team_name+' => '',
	'Class:lnkPersonToTeam/Attribute:person_id' => 'Pessoa',
	'Class:lnkPersonToTeam/Attribute:person_id+' => '',
	'Class:lnkPersonToTeam/Attribute:person_name' => 'Nome da pessoa',
	'Class:lnkPersonToTeam/Attribute:person_name+' => '',
	'Class:lnkPersonToTeam/Attribute:role_id' => 'Função',
	'Class:lnkPersonToTeam/Attribute:role_id+' => 'Define a função da Pessoa na Equipe (Líder de Equipe, Gerente...).',
	'Class:lnkPersonToTeam/Attribute:role_name' => 'Nome da função',
	'Class:lnkPersonToTeam/Attribute:role_name+' => '',
));

//
// Application Menu
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Menu:DataAdministration' => 'Administração de dados',
	'Menu:DataAdministration+' => '',
	'Menu:Catalogs' => 'Catálogos',
	'Menu:Catalogs+' => 'Tipos de dados',
	'Menu:Audit' => 'Auditoria',
	'Menu:Audit+' => '',
	'Menu:CSVImport' => 'Importar CSV',
	'Menu:CSVImport+' => 'Criação ou atualização em massa',
	'Menu:Organization' => 'Organizações',
	'Menu:Organization+' => 'Lista de organizações',
	'Menu:ConfigManagement' => 'Gerenciamento configuração',
	'Menu:ConfigManagement+' => 'Gerenciamento de configuração',
	'Menu:ConfigManagementCI' => 'Itens de configuração',
	'Menu:ConfigManagementCI+' => 'Lista de itens de configuração',
	'Menu:ConfigManagementOverview' => 'Visão geral',
	'Menu:ConfigManagementOverview+' => '',
	'Menu:Contact' => 'Contatos',
	'Menu:Contact+' => 'Lista de contatos',
	'Menu:Contact:Count' => '%1$d contato(s)',
	'Menu:Person' => 'Pessoas',
	'Menu:Person+' => 'Lista de pessoas',
	'Menu:Team' => 'Equipes',
	'Menu:Team+' => 'Lista de equipes',
	'Menu:Document' => 'Documentos',
	'Menu:Document+' => 'Lista de documentos',
	'Menu:Location' => 'Localizações',
	'Menu:Location+' => 'Lista de localizações',
	'Menu:NewContact' => 'Novo contato',
	'Menu:NewContact+' => '',
	'Menu:SearchContacts' => 'Pesquisar por contatos',
	'Menu:SearchContacts+' => '',
	'Menu:ConfigManagement:Shortcuts' => 'Atalhos',
	'Menu:ConfigManagement:AllContacts' => 'Todos os contatos: %1$d',
	'Menu:Typology' => 'Configuração de tipologias',
	'Menu:Typology+' => 'Lista de tipologias',
	'UI_WelcomeMenu_AllConfigItems' => 'Índice',
	'Menu:ConfigManagement:Typology' => 'Configuração de tipologias',
));

// Add translation for Fieldsets

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Person:info' => 'Informações gerais',
	'User:info' => 'Informações gerais',
	'User:profiles' => 'Profiles (minimum one)~~',
	'Person:personal_info' => 'Informações pessoais',
	'Person:notifiy' => 'Notificações',
));

// Themes
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'theme:fullmoon' => 'Full moon',
	'theme:test-red' => 'Test instance (Red)',
));
