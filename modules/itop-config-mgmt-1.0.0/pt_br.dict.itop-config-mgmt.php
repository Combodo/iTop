<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * Localized data
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

//////////////////////////////////////////////////////////////////////
// Relations
//////////////////////////////////////////////////////////////////////
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Relation:impacts/Description' => 'Elementos impactados por',
	'Relation:impacts/VerbUp' => 'Impacto...',
	'Relation:impacts/VerbDown' => 'Elementos impactados por...',
	'Relation:depends on/Description' => 'Elements this element depends on',
	'Relation:depends on/VerbUp' => 'Dependente...',
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
	'Class:Organization/Attribute:code' => 'Codigo',
	'Class:Organization/Attribute:code+' => 'C&oacute;digo Organiza&ccedil;&atilde;o',
	'Class:Organization/Attribute:status' => 'Status',
	'Class:Organization/Attribute:status+' => '',
	'Class:Organization/Attribute:status/Value:active' => 'Ativo',
	'Class:Organization/Attribute:status/Value:active+' => 'Ativo',
	'Class:Organization/Attribute:status/Value:inactive' => 'Inativo',
	'Class:Organization/Attribute:status/Value:inactive+' => 'Inativo',
	'Class:Organization/Attribute:parent_id' => 'Matriz',
	'Class:Organization/Attribute:parent_id+' => 'Organização matriz',
	'Class:Organization/Attribute:parent_name' => 'Nome matriz',
	'Class:Organization/Attribute:parent_name+' => 'Nome da matriz',
));


//
// Class: Location
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Location' => 'Localizacao',
	'Class:Location+' => 'Qualquer tipo localizacao: Região, Pais, Cidade, Site, Construção, Piso, Sala, Rack,...',
	'Class:Location/Attribute:name' => 'Nome',
	'Class:Location/Attribute:name+' => '',
	'Class:Location/Attribute:status' => 'Status',
	'Class:Location/Attribute:status+' => '',
	'Class:Location/Attribute:status/Value:active' => 'Ativo',
	'Class:Location/Attribute:status/Value:active+' => 'Ativo',
	'Class:Location/Attribute:status/Value:inactive' => 'Inativo',
	'Class:Location/Attribute:status/Value:inactive+' => 'Inativo',
	'Class:Location/Attribute:org_id' => 'Proprietário',
	'Class:Location/Attribute:org_id+' => '',
	'Class:Location/Attribute:org_name' => 'Nome do proprietário',
	'Class:Location/Attribute:org_name+' => '',
	'Class:Location/Attribute:address' => 'Endereço',
	'Class:Location/Attribute:address+' => 'Endereço',
	'Class:Location/Attribute:postal_code' => 'CEP',
	'Class:Location/Attribute:postal_code+' => 'CEP',
	'Class:Location/Attribute:city' => 'Cidade',
	'Class:Location/Attribute:city+' => '',
	'Class:Location/Attribute:country' => 'Pais',
	'Class:Location/Attribute:country+' => '',
	'Class:Location/Attribute:parent_id' => 'Parent location',
	'Class:Location/Attribute:parent_id+' => '',
	'Class:Location/Attribute:parent_name' => 'Parent name',
	'Class:Location/Attribute:parent_name+' => '',
	'Class:Location/Attribute:contact_list' => 'Contatos',
	'Class:Location/Attribute:contact_list+' => 'Contatos localizados neste site',
	'Class:Location/Attribute:infra_list' => 'Infra-estrutura',
	'Class:Location/Attribute:infra_list+' => 'CIs localizados neste site',
));

//
// Class: Contact
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Contact' => 'Contato',
	'Class:Contact+' => '',
	'Class:Contact/Attribute:name' => 'Nome',
	'Class:Contact/Attribute:name+' => '',
	'Class:Contact/Attribute:status' => 'Status',
	'Class:Contact/Attribute:status+' => '',
	'Class:Contact/Attribute:status/Value:active' => 'Ativo',
	'Class:Contact/Attribute:status/Value:active+' => 'Ativo',
	'Class:Contact/Attribute:status/Value:inactive' => 'Inativo',
	'Class:Contact/Attribute:status/Value:inactive+' => 'Inativo',
	'Class:Contact/Attribute:org_id' => 'Organizacao',
	'Class:Contact/Attribute:org_id+' => '',
	'Class:Contact/Attribute:org_name' => 'Organizacao',
	'Class:Contact/Attribute:org_name+' => '',
	'Class:Contact/Attribute:email' => 'Email',
	'Class:Contact/Attribute:email+' => '',
	'Class:Contact/Attribute:phone' => 'Telefone',
	'Class:Contact/Attribute:phone+' => '',
	'Class:Contact/Attribute:location_id' => 'Localizacao',
	'Class:Contact/Attribute:location_id+' => '',
	'Class:Contact/Attribute:location_name' => 'Localizacao',
	'Class:Contact/Attribute:location_name+' => '',
	'Class:Contact/Attribute:ci_list' => 'CIs',
	'Class:Contact/Attribute:ci_list+' => 'CIs relacionados para o contato',
	'Class:Contact/Attribute:contract_list' => 'Contratos',
	'Class:Contact/Attribute:contract_list+' => 'Contratos relativo ao contato',
	'Class:Contact/Attribute:service_list' => 'Servicos',
	'Class:Contact/Attribute:service_list+' => 'Servicos relativo ao contato',
	'Class:Contact/Attribute:ticket_list' => 'Tickets',
	'Class:Contact/Attribute:ticket_list+' => 'Tickets relacionado ao contato',
	'Class:Contact/Attribute:team_list' => 'Equipes',
	'Class:Contact/Attribute:team_list+' => 'Equipes que esse contato pertence',
	'Class:Contact/Attribute:finalclass' => 'Tipo',
	'Class:Contact/Attribute:finalclass+' => '',
));

//
// Class: Person
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Person' => 'Pessoas',
	'Class:Person+' => '',
	'Class:Person/Attribute:first_name' => 'Primeiro nome',
	'Class:Person/Attribute:first_name+' => '',
	'Class:Person/Attribute:employee_id' => 'ID colaborador',
	'Class:Person/Attribute:employee_id+' => '',
));

//
// Class: Team
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Team' => 'Equipe',
	'Class:Team+' => '',
	'Class:Team/Attribute:member_list' => 'Membros',
	'Class:Team/Attribute:member_list+' => 'Contatos que são partes da equipe',
));

//
// Class: lnkTeamToContact
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkTeamToContact' => 'Membros equipe',
	'Class:lnkTeamToContact+' => 'Membros da equipe',
	'Class:lnkTeamToContact/Attribute:team_id' => 'Equipe',
	'Class:lnkTeamToContact/Attribute:team_id+' => '',
	'Class:lnkTeamToContact/Attribute:contact_id' => 'Membro',
	'Class:lnkTeamToContact/Attribute:contact_id+' => '',
	'Class:lnkTeamToContact/Attribute:contact_location_id' => 'Localização',
	'Class:lnkTeamToContact/Attribute:contact_location_id+' => '',
	'Class:lnkTeamToContact/Attribute:contact_email' => 'Email',
	'Class:lnkTeamToContact/Attribute:contact_email+' => '',
	'Class:lnkTeamToContact/Attribute:contact_phone' => 'Telefone',
	'Class:lnkTeamToContact/Attribute:contact_phone+' => '',
	'Class:lnkTeamToContact/Attribute:role' => 'Regra',
	'Class:lnkTeamToContact/Attribute:role+' => '',
));

//
// Class: Document
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Document' => 'Documentos',
	'Class:Document+' => '',
	'Class:Document/Attribute:name' => 'Nome',
	'Class:Document/Attribute:name+' => '',
	'Class:Document/Attribute:description' => 'Descrição',
	'Class:Document/Attribute:description+' => '',
	'Class:Document/Attribute:type' => 'Tipo',
	'Class:Document/Attribute:type+' => '',
	'Class:Document/Attribute:type/Value:contract' => 'Contrato',
	'Class:Document/Attribute:type/Value:contract+' => '',
	'Class:Document/Attribute:type/Value:networkmap' => 'Mapa rede',
	'Class:Document/Attribute:type/Value:networkmap+' => '',
	'Class:Document/Attribute:type/Value:presentation' => 'Apresentação',
	'Class:Document/Attribute:type/Value:presentation+' => '',
	'Class:Document/Attribute:type/Value:training' => 'Treinamento',
	'Class:Document/Attribute:type/Value:training+' => '',
	'Class:Document/Attribute:type/Value:whitePaper' => 'How To',
	'Class:Document/Attribute:type/Value:whitePaper+' => '',
	'Class:Document/Attribute:type/Value:workinginstructions' => 'Instruções trabalho',
	'Class:Document/Attribute:type/Value:workinginstructions+' => '',
	'Class:Document/Attribute:status' => 'Status',
	'Class:Document/Attribute:status+' => '',
	'Class:Document/Attribute:status/Value:draft' => 'Rascunho',
	'Class:Document/Attribute:status/Value:draft+' => '',
	'Class:Document/Attribute:status/Value:obsolete' => 'Obsoleto',
	'Class:Document/Attribute:status/Value:obsolete+' => '',
	'Class:Document/Attribute:status/Value:published' => 'Publicado',
	'Class:Document/Attribute:status/Value:published+' => '',
	'Class:Document/Attribute:ci_list' => 'CIs',
	'Class:Document/Attribute:ci_list+' => 'CIs referente a este documento',
	'Class:Document/Attribute:contract_list' => 'Contratos',
	'Class:Document/Attribute:contract_list+' => 'Contratos referente a este documento',
	'Class:Document/Attribute:service_list' => 'Serviços',
	'Class:Document/Attribute:service_list+' => 'Serviços referente a este documento',
	'Class:Document/Attribute:ticket_list' => 'Tickets',
	'Class:Document/Attribute:ticket_list+' => 'Tickets referente a este documento',
	'Class:Document:PreviewTab' => 'Visualização',
));

//
// Class: ExternalDoc
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:ExternalDoc' => 'Documento externo',
	'Class:ExternalDoc+' => 'Documento disponível em outro web server',
	'Class:ExternalDoc/Attribute:url' => 'Url',
	'Class:ExternalDoc/Attribute:url+' => '',
));

//
// Class: Note
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Note' => 'Notas',
	'Class:Note+' => '',
	'Class:Note/Attribute:note' => 'Textos',
	'Class:Note/Attribute:note+' => '',
));

//
// Class: FileDoc
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:FileDoc' => 'Documento (arquivo)',
	'Class:FileDoc+' => '',
	'Class:FileDoc/Attribute:contents' => 'Conteudos',
	'Class:FileDoc/Attribute:contents+' => '',
));

//
// Class: Licence
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Licence' => 'Licenças',
	'Class:Licence+' => '',
	'Class:Licence/Attribute:provider' => 'Provedora',
	'Class:Licence/Attribute:provider+' => '',
	'Class:Licence/Attribute:product' => 'Produto',
	'Class:Licence/Attribute:product+' => '',
	'Class:Licence/Attribute:name' => 'Nome',
	'Class:Licence/Attribute:name+' => '',
	'Class:Licence/Attribute:start' => 'Data início',
	'Class:Licence/Attribute:start+' => '',
	'Class:Licence/Attribute:end' => 'Data final',
	'Class:Licence/Attribute:end+' => '',
	'Class:Licence/Attribute:licence_key' => 'Chave',
	'Class:Licence/Attribute:licence_key+' => '',
	'Class:Licence/Attribute:scope' => 'Scope',
	'Class:Licence/Attribute:scope+' => '',
	'Class:Licence/Attribute:usage_limit' => 'Limite uso',
	'Class:Licence/Attribute:usage_limit+' => '',
	'Class:Licence/Attribute:usage_list' => 'Usado',
	'Class:Licence/Attribute:usage_list+' => 'inst&acirc;ncias de aplicativos usando esta licen&ccedil;a',
));

//
// Class: Subnet
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Subnet' => 'Sub-rede',
	'Class:Subnet+' => '',
	'Class:Subnet/Name' => '%1$s / %2$s',
	//'Class:Subnet/Attribute:name' => 'Nome',
	//'Class:Subnet/Attribute:name+' => '',
	'Class:Subnet/Attribute:org_id' => 'Organização',
	'Class:Subnet/Attribute:org_id+' => '',
	'Class:Subnet/Attribute:description' => 'Descrição',
	'Class:Subnet/Attribute:description+' => '',
	'Class:Subnet/Attribute:ip' => 'IP',
	'Class:Subnet/Attribute:ip+' => '',
	'Class:Subnet/Attribute:ip_mask' => 'Máscara de rede',
	'Class:Subnet/Attribute:ip_mask+' => '',
));

//
// Class: Patch
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Patch' => 'Patch',
	'Class:Patch+' => '',
	'Class:Patch/Attribute:name' => 'Nome',
	'Class:Patch/Attribute:name+' => '',
	'Class:Patch/Attribute:description' => 'Descri&ccedil;&atilde;o',
	'Class:Patch/Attribute:description+' => '',
	'Class:Patch/Attribute:target_sw' => 'Application scope',
	'Class:Patch/Attribute:target_sw+' => 'Destino software (OS ou aplica&ccedil;&atilde;o)',
	'Class:Patch/Attribute:version' => 'Vers&atilde;o',
	'Class:Patch/Attribute:version+' => '',
	'Class:Patch/Attribute:type' => 'Tipo',
	'Class:Patch/Attribute:type+' => '',
	'Class:Patch/Attribute:type/Value:application' => 'Applica&ccedil;&atilde;o',
	'Class:Patch/Attribute:type/Value:application+' => '',
	'Class:Patch/Attribute:type/Value:os' => 'OS',
	'Class:Patch/Attribute:type/Value:os+' => '',
	'Class:Patch/Attribute:type/Value:security' => 'Seguran&ccedil;a',
	'Class:Patch/Attribute:type/Value:security+' => '',
	'Class:Patch/Attribute:type/Value:servicepack' => 'Service Pack',
	'Class:Patch/Attribute:type/Value:servicepack+' => '',
	'Class:Patch/Attribute:ci_list' => 'Dispositivo',
	'Class:Patch/Attribute:ci_list+' => 'Dispositivo onde o patch est&aacute; instalado',
));

//
// Class: Software
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Software' => 'Software',
	'Class:Software+' => '',
	'Class:Software/Attribute:name' => 'Nome',
	'Class:Software/Attribute:name+' => '',
	'Class:Software/Attribute:description' => 'Descri&ccedil;&atilde;o',
	'Class:Software/Attribute:description+' => '',
	'Class:Software/Attribute:instance_list' => 'Instala&ccedil;&otilde;es',
	'Class:Software/Attribute:instance_list+' => 'Inst&acirc;ncias do software',
	'Class:Software/Attribute:finalclass' => 'Tipo',
	'Class:Software/Attribute:finalclass+' => '',
));

//
// Class: Application
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Application' => 'Aplica&ccedil;&otilde;es',
	'Class:Application+' => '',
	'Class:Application/Attribute:name' => 'Nome',
	'Class:Application/Attribute:name+' => '',
	'Class:Application/Attribute:description' => 'Descri&ccedil;&atilde;o',
	'Class:Application/Attribute:description+' => '',
	'Class:Application/Attribute:instance_list' => 'Instala&ccedil;&otilde;es',
	'Class:Application/Attribute:instance_list+' => 'Inst&acirc;ncias da aplica&ccedil;&atilde;o',
));

//
// Class: DBServer
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:DBServer' => 'Database',
	'Class:DBServer+' => 'Database server SW',
	'Class:DBServer/Attribute:instance_list' => 'Instala&ccedil;&otilde;es',
	'Class:DBServer/Attribute:instance_list+' => 'Inst&acirc;ncias desta base de dados do servidor',
));

//
// Class: lnkPatchToCI
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkPatchToCI' => 'Patch utilizado',
	'Class:lnkPatchToCI+' => '',
	'Class:lnkPatchToCI/Attribute:patch_id' => 'Patch',
	'Class:lnkPatchToCI/Attribute:patch_id+' => '',
	'Class:lnkPatchToCI/Attribute:patch_name' => 'Patch',
	'Class:lnkPatchToCI/Attribute:patch_name+' => '',
	'Class:lnkPatchToCI/Attribute:ci_id' => 'CI',
	'Class:lnkPatchToCI/Attribute:ci_id+' => '',
	'Class:lnkPatchToCI/Attribute:ci_name' => 'CI',
	'Class:lnkPatchToCI/Attribute:ci_name+' => '',
	'Class:lnkPatchToCI/Attribute:ci_status' => 'CI Status',
	'Class:lnkPatchToCI/Attribute:ci_status+' => '',
));

//
// Class: FunctionalCI
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:FunctionalCI' => 'CI funcionalidades',
	'Class:FunctionalCI+' => '',
	'Class:FunctionalCI/Attribute:name' => 'Nome',
	'Class:FunctionalCI/Attribute:name+' => '',
	'Class:FunctionalCI/Attribute:status' => 'Status',
	'Class:FunctionalCI/Attribute:status+' => '',
	'Class:FunctionalCI/Attribute:status/Value:implementation' => 'Implementação',
	'Class:FunctionalCI/Attribute:status/Value:implementation+' => '',
	'Class:FunctionalCI/Attribute:status/Value:obsolete' => 'Obsoleto',
	'Class:FunctionalCI/Attribute:status/Value:obsolete+' => '',
	'Class:FunctionalCI/Attribute:status/Value:production' => 'Produção',
	'Class:FunctionalCI/Attribute:status/Value:production+' => '',
	'Class:FunctionalCI/Attribute:org_id' => 'Organização',
	'Class:FunctionalCI/Attribute:org_id+' => '',
	'Class:FunctionalCI/Attribute:owner_name' => 'Organização',
	'Class:FunctionalCI/Attribute:owner_name+' => '',
	'Class:FunctionalCI/Attribute:importance' => 'Criticidade negócio',
	'Class:FunctionalCI/Attribute:importance+' => '',
	'Class:FunctionalCI/Attribute:importance/Value:high' => 'Alto',
	'Class:FunctionalCI/Attribute:importance/Value:high+' => '',
	'Class:FunctionalCI/Attribute:importance/Value:low' => 'Baixo',
	'Class:FunctionalCI/Attribute:importance/Value:low+' => '',
	'Class:FunctionalCI/Attribute:importance/Value:medium' => 'Médio',
	'Class:FunctionalCI/Attribute:importance/Value:medium+' => '',
	'Class:FunctionalCI/Attribute:contact_list' => 'Contatos',
	'Class:FunctionalCI/Attribute:contact_list+' => 'Contatos para este CI',
	'Class:FunctionalCI/Attribute:document_list' => 'Documentos',
	'Class:FunctionalCI/Attribute:document_list+' => 'Documenção para este CI',
	'Class:FunctionalCI/Attribute:solution_list' => 'Application solutions',
	'Class:FunctionalCI/Attribute:solution_list+' => 'Application solutions using this CI',
	'Class:FunctionalCI/Attribute:contract_list' => 'Contratos',
	'Class:FunctionalCI/Attribute:contract_list+' => 'Contratos suportanto este CI',
	'Class:FunctionalCI/Attribute:ticket_list' => 'Tickets',
	'Class:FunctionalCI/Attribute:ticket_list+' => 'Tickets relacionado a este CI',
	'Class:FunctionalCI/Attribute:finalclass' => 'Tipo',
	'Class:FunctionalCI/Attribute:finalclass+' => '',
));

//
// Class: SoftwareInstance
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:SoftwareInstance' => 'Software Instance',
	'Class:SoftwareInstance+' => '',
	'Class:SoftwareInstance/Name' => '%1$s - %2$s',
	'Class:SoftwareInstance/Attribute:device_id' => 'Dispositivo',
	'Class:SoftwareInstance/Attribute:device_id+' => '',
	'Class:SoftwareInstance/Attribute:device_name' => 'Dispositivo',
	'Class:SoftwareInstance/Attribute:device_name+' => '',
	'Class:SoftwareInstance/Attribute:licence_id' => 'Licen&ccedil;a',
	'Class:SoftwareInstance/Attribute:licence_id+' => '',
	'Class:SoftwareInstance/Attribute:licence_name' => 'Licen&ccedil;a',
	'Class:SoftwareInstance/Attribute:licence_name+' => '',
	'Class:SoftwareInstance/Attribute:software_id' => 'Software',
	'Class:SoftwareInstance/Attribute:software_id+' => '',
	'Class:SoftwareInstance/Attribute:software_name' => 'Software',
	'Class:SoftwareInstance/Attribute:software_name+' => '',
	'Class:SoftwareInstance/Attribute:version' => 'Vers&atilde;o',
	'Class:SoftwareInstance/Attribute:version+' => '',
	'Class:SoftwareInstance/Attribute:description' => 'Descri&ccedil;&atilde;o',
	'Class:SoftwareInstance/Attribute:description+' => '',
));

//
// Class: ApplicationInstance
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:ApplicationInstance' => 'Inst&acirc;ncia Aplica&ccedil;&atilde;o',
	'Class:ApplicationInstance+' => '',
	'Class:ApplicationInstance/Name' => '%1$s - %2$s',
));

//
// Class: DBServerInstance
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:DBServerInstance' => 'Inst&acirc;ncias DB Server',
	'Class:DBServerInstance+' => '',
	'Class:DBServerInstance/Name' => '%1$s - %2$s',
	'Class:DBServerInstance/Attribute:dbinstance_list' => 'Base de Dados',
	'Class:DBServerInstance/Attribute:dbinstance_list+' => 'Origem Base de dados',
));

//
// Class: DatabaseInstance
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:DatabaseInstance' => 'Inst&acirc;ncia Base de Dados',
	'Class:DatabaseInstance+' => '',
	'Class:DatabaseInstance/Name' => '%1$s - %2$s',
	'Class:DatabaseInstance/Attribute:db_server_instance_id' => 'Servidor Base de Dados',
	'Class:DatabaseInstance/Attribute:db_server_instance_id+' => '',
	'Class:DatabaseInstance/Attribute:db_server_instance_version' => 'Vers&atilde;o Base de Dados',
	'Class:DatabaseInstance/Attribute:db_server_instance_version+' => '',
	'Class:DatabaseInstance/Attribute:description' => 'Descri&ccedil;&atilde;o',
	'Class:DatabaseInstance/Attribute:description+' => '',
));

//
// Class: ApplicationSolution
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:ApplicationSolution' => 'Application Solution',
	'Class:ApplicationSolution+' => '',
	'Class:ApplicationSolution/Attribute:description' => 'Descri&ccedil;&atilde;o',
	'Class:ApplicationSolution/Attribute:description+' => '',
	'Class:ApplicationSolution/Attribute:ci_list' => 'CIs',
	'Class:ApplicationSolution/Attribute:ci_list+' => 'CIs que comp&otilde;em a solu&ccedil&atilde;o',
	'Class:ApplicationSolution/Attribute:process_list' => 'Os processos do neg&oacute;cios',
	'Class:ApplicationSolution/Attribute:process_list+' => 'Os processos de neg&oacute;cio baseando-se na solu&ccedil;&atilde;o',
));

//
// Class: BusinessProcess
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:BusinessProcess' => 'Processos Neg&oacute;cio',
	'Class:BusinessProcess+' => '',
	'Class:BusinessProcess/Attribute:description' => 'Descri&ccedil;&atilde;o',
	'Class:BusinessProcess/Attribute:description+' => '',
	'Class:BusinessProcess/Attribute:used_solution_list' => 'Application solutions',
	'Class:BusinessProcess/Attribute:used_solution_list+' => 'Application solutions the process is relying on',
));

//
// Class: ConnectableCI
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:ConnectableCI' => 'Conectividade CI',
	'Class:ConnectableCI+' => 'CI físicos',
	'Class:ConnectableCI/Attribute:brand' => 'Fabricante',
	'Class:ConnectableCI/Attribute:brand+' => '',
	'Class:ConnectableCI/Attribute:model' => 'Modelo',
	'Class:ConnectableCI/Attribute:model+' => '',
	'Class:ConnectableCI/Attribute:serial_number' => 'Serial  Number',
	'Class:ConnectableCI/Attribute:serial_number+' => '',
	'Class:ConnectableCI/Attribute:asset_ref' => 'Atribuir Refer&ecirc;ncia',
	'Class:ConnectableCI/Attribute:asset_ref+' => '',
));

//
// Class: NetworkInterface
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:NetworkInterface' => 'Interface de rede',
	'Class:NetworkInterface+' => '',
	'Class:NetworkInterface/Name' => '%1$s - %2$s',
	'Class:NetworkInterface/Attribute:device_id' => 'Dispositivo',
	'Class:NetworkInterface/Attribute:device_id+' => '',
	'Class:NetworkInterface/Attribute:device_name' => 'Dispositivo',
	'Class:NetworkInterface/Attribute:device_name+' => '',
	'Class:NetworkInterface/Attribute:logical_type' => 'Tipo lógico',
	'Class:NetworkInterface/Attribute:logical_type+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:backup' => 'Backup',
	'Class:NetworkInterface/Attribute:logical_type/Value:backup+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:logical' => 'Lógico',
	'Class:NetworkInterface/Attribute:logical_type/Value:logical+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:port' => 'Porta',
	'Class:NetworkInterface/Attribute:logical_type/Value:port+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:primary' => 'Primário',
	'Class:NetworkInterface/Attribute:logical_type/Value:primary+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:secondary' => 'Secundário',
	'Class:NetworkInterface/Attribute:logical_type/Value:secondary+' => '',
	'Class:NetworkInterface/Attribute:physical_type' => 'Físico',
	'Class:NetworkInterface/Attribute:physical_type+' => '',
	'Class:NetworkInterface/Attribute:physical_type/Value:atm' => 'ATM',
	'Class:NetworkInterface/Attribute:physical_type/Value:atm+' => '',
	'Class:NetworkInterface/Attribute:physical_type/Value:ethernet' => 'Ethernet',
	'Class:NetworkInterface/Attribute:physical_type/Value:ethernet+' => '',
	'Class:NetworkInterface/Attribute:physical_type/Value:framerelay' => 'Frame Relay',
	'Class:NetworkInterface/Attribute:physical_type/Value:framerelay+' => '',
	'Class:NetworkInterface/Attribute:physical_type/Value:vlan' => 'VLAN',
	'Class:NetworkInterface/Attribute:physical_type/Value:vlan+' => '',
	'Class:NetworkInterface/Attribute:ip_address' => 'Endereço IP',
	'Class:NetworkInterface/Attribute:ip_address+' => '',
	'Class:NetworkInterface/Attribute:ip_mask' => 'Máscara de rede',
	'Class:NetworkInterface/Attribute:ip_mask+' => '',
	'Class:NetworkInterface/Attribute:mac_address' => 'Endereço MAC',
	'Class:NetworkInterface/Attribute:mac_address+' => '',
	'Class:NetworkInterface/Attribute:speed' => 'Velocidade',
	'Class:NetworkInterface/Attribute:speed+' => '',
	'Class:NetworkInterface/Attribute:duplex' => 'Duplex',
	'Class:NetworkInterface/Attribute:duplex+' => '',
	'Class:NetworkInterface/Attribute:duplex/Value:full' => 'Full',
	'Class:NetworkInterface/Attribute:duplex/Value:full+' => '',
	'Class:NetworkInterface/Attribute:duplex/Value:half' => 'Half',
	'Class:NetworkInterface/Attribute:duplex/Value:half+' => '',
	'Class:NetworkInterface/Attribute:duplex/Value:unknown' => 'Desconhecido',
	'Class:NetworkInterface/Attribute:duplex/Value:unknown+' => '',
	'Class:NetworkInterface/Attribute:connected_if' => 'Connected to',
	'Class:NetworkInterface/Attribute:connected_if+' => 'Connected interface',
	'Class:NetworkInterface/Attribute:connected_name' => 'Connected to',
	'Class:NetworkInterface/Attribute:connected_name+' => '',
	'Class:NetworkInterface/Attribute:connected_if_device_id' => 'Connected device',
	'Class:NetworkInterface/Attribute:connected_if_device_id+' => '',
	'Class:NetworkInterface/Attribute:link_type' => 'Link type',
	'Class:NetworkInterface/Attribute:link_type+' => '',
	'Class:NetworkInterface/Attribute:link_type/Value:uplink' => 'Link Up',
	'Class:NetworkInterface/Attribute:link_type/Value:uplink+' => '',
	'Class:NetworkInterface/Attribute:link_type/Value:downlink' => 'Link Down',
	'Class:NetworkInterface/Attribute:link_type/Value:downlink+' => '',
));

//
// Class: Device
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Device' => 'Dispositivo',
	'Class:Device+' => '',
	'Class:Device/Attribute:nwinterface_list' => 'Interfaces de rede',
	'Class:Device/Attribute:nwinterface_list+' => '',
));

//
// Class: PC
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:PC' => 'PC',
	'Class:PC+' => '',
	'Class:PC/Attribute:cpu' => 'CPU',
	'Class:PC/Attribute:cpu+' => '',
	'Class:PC/Attribute:ram' => 'RAM',
	'Class:PC/Attribute:ram+' => '',
	'Class:PC/Attribute:hdd' => 'Hard disk',
	'Class:PC/Attribute:hdd+' => '',
	'Class:PC/Attribute:os_family' => 'Família OS',
	'Class:PC/Attribute:os_family+' => '',
	'Class:PC/Attribute:os_version' => 'Versão OS',
	'Class:PC/Attribute:os_version+' => '',
	'Class:PC/Attribute:application_list' => 'Aplicativos',
	'Class:PC/Attribute:application_list+' => 'Aplicativos instalados neste PC',
	'Class:PC/Attribute:patch_list' => 'Patches',
	'Class:PC/Attribute:patch_list+' => 'Patches instalados neste PC',
));

//
// Class: MobileCI
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:MobileCI' => 'Mobile CI',
	'Class:MobileCI+' => '',
));

//
// Class: MobilePhone
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:MobilePhone' => 'Telefone celular',
	'Class:MobilePhone+' => '',
	'Class:MobilePhone/Attribute:number' => 'N&uacute;mero telefone',
	'Class:MobilePhone/Attribute:number+' => '',
	'Class:MobilePhone/Attribute:imei' => 'IMEI',
	'Class:MobilePhone/Attribute:imei+' => '',
	'Class:MobilePhone/Attribute:hw_pin' => 'Hardware PIN',
	'Class:MobilePhone/Attribute:hw_pin+' => '',
));

//
// Class: InfrastructureCI
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:InfrastructureCI' => 'Infra-estrutura CI',
	'Class:InfrastructureCI+' => '',
	'Class:InfrastructureCI/Attribute:description' => 'Descrição',
	'Class:InfrastructureCI/Attribute:description+' => '',
	'Class:InfrastructureCI/Attribute:location_id' => 'Localização',
	'Class:InfrastructureCI/Attribute:location_id+' => '',
	'Class:InfrastructureCI/Attribute:location_name' => 'Localização',
	'Class:InfrastructureCI/Attribute:location_name+' => '',
	'Class:InfrastructureCI/Attribute:location_details' => 'Detalhes localização',
	'Class:InfrastructureCI/Attribute:location_details+' => '',
	'Class:InfrastructureCI/Attribute:management_ip' => 'IP gerenciamento',
	'Class:InfrastructureCI/Attribute:management_ip+' => '',
	'Class:InfrastructureCI/Attribute:default_gateway' => 'Gateway padrão',
	'Class:InfrastructureCI/Attribute:default_gateway+' => '',
));

//
// Class: NetworkDevice
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:NetworkDevice' => 'Dispositivo rede',
	'Class:NetworkDevice+' => '',
	'Class:NetworkDevice/Attribute:type' => 'Tipo',
	'Class:NetworkDevice/Attribute:type+' => '',
	'Class:NetworkDevice/Attribute:type/Value:wanaccelerator' => 'WAN Accelerator',
	'Class:NetworkDevice/Attribute:type/Value:wanaccelerator+' => '',
	'Class:NetworkDevice/Attribute:type/Value:firewall' => 'Firewall',
	'Class:NetworkDevice/Attribute:type/Value:firewall+' => '',
	'Class:NetworkDevice/Attribute:type/Value:hub' => 'Hub',
	'Class:NetworkDevice/Attribute:type/Value:hub+' => '',
	'Class:NetworkDevice/Attribute:type/Value:loadbalancer' => 'Load Balancer',
	'Class:NetworkDevice/Attribute:type/Value:loadbalancer+' => '',
	'Class:NetworkDevice/Attribute:type/Value:router' => 'Roteador',
	'Class:NetworkDevice/Attribute:type/Value:router+' => '',
	'Class:NetworkDevice/Attribute:type/Value:switch' => 'Switch',
	'Class:NetworkDevice/Attribute:type/Value:switch+' => '',
	'Class:NetworkDevice/Attribute:ios_version' => 'Versão IOS',
	'Class:NetworkDevice/Attribute:ios_version+' => '',
	'Class:NetworkDevice/Attribute:ram' => 'RAM',
	'Class:NetworkDevice/Attribute:ram+' => '',
	'Class:NetworkDevice/Attribute:snmp_read' => 'SNMP Read',
	'Class:NetworkDevice/Attribute:snmp_read+' => '',
	'Class:NetworkDevice/Attribute:snmp_write' => 'SNMP Write',
	'Class:NetworkDevice/Attribute:snmp_write+' => '',
));

//
// Class: Server
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Server' => 'Servidor',
	'Class:Server+' => '',
	'Class:Server/Attribute:cpu' => 'CPU',
	'Class:Server/Attribute:cpu+' => '',
	'Class:Server/Attribute:ram' => 'RAM',
	'Class:Server/Attribute:ram+' => '',
	'Class:Server/Attribute:hdd' => 'Hard Disk',
	'Class:Server/Attribute:hdd+' => '',
	'Class:Server/Attribute:os_family' => 'Família OS',
	'Class:Server/Attribute:os_family+' => '',
	'Class:Server/Attribute:os_version' => 'Versão OS',
	'Class:Server/Attribute:os_version+' => '',
	'Class:Server/Attribute:application_list' => 'Aplicativos',
	'Class:Server/Attribute:application_list+' => 'Aplicativos instalados neste servidor',
	'Class:Server/Attribute:patch_list' => 'Patches',
	'Class:Server/Attribute:patch_list+' => 'Patches instalados neste servidor',
));

//
// Class: Printer
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Printer' => 'Impress&otilde;es',
	'Class:Printer+' => '',
	'Class:Printer/Attribute:type' => 'Tipo',
	'Class:Printer/Attribute:type+' => '',
	'Class:Printer/Attribute:type/Value:mopier' => 'Mopier',
	'Class:Printer/Attribute:type/Value:mopier+' => '',
	'Class:Printer/Attribute:type/Value:printer' => 'Impressora',
	'Class:Printer/Attribute:type/Value:printer+' => '',
	'Class:Printer/Attribute:technology' => 'Tecnologia',
	'Class:Printer/Attribute:technology+' => '',
	'Class:Printer/Attribute:technology/Value:inkjet' => 'Inkjet',
	'Class:Printer/Attribute:technology/Value:inkjet+' => '',
	'Class:Printer/Attribute:technology/Value:laser' => 'Laser',
	'Class:Printer/Attribute:technology/Value:laser+' => '',
	'Class:Printer/Attribute:technology/Value:tracer' => 'Tracer',
	'Class:Printer/Attribute:technology/Value:tracer+' => '',
));

//
// Class: lnkCIToDoc
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkCIToDoc' => 'Doc/CI',
	'Class:lnkCIToDoc+' => '',
	'Class:lnkCIToDoc/Attribute:ci_id' => 'CI',
	'Class:lnkCIToDoc/Attribute:ci_id+' => '',
	'Class:lnkCIToDoc/Attribute:ci_name' => 'CI',
	'Class:lnkCIToDoc/Attribute:ci_name+' => '',
	'Class:lnkCIToDoc/Attribute:ci_status' => 'CI Status',
	'Class:lnkCIToDoc/Attribute:ci_status+' => '',
	'Class:lnkCIToDoc/Attribute:document_id' => 'Documento',
	'Class:lnkCIToDoc/Attribute:document_id+' => '',
	'Class:lnkCIToDoc/Attribute:document_name' => 'Documento',
	'Class:lnkCIToDoc/Attribute:document_name+' => '',
	'Class:lnkCIToDoc/Attribute:document_type' => 'Tipo documento',
	'Class:lnkCIToDoc/Attribute:document_type+' => '',
	'Class:lnkCIToDoc/Attribute:document_status' => 'Status documento',
	'Class:lnkCIToDoc/Attribute:document_status+' => '',
));

//
// Class: lnkCIToContact
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkCIToContact' => 'CI/Contatos',
	'Class:lnkCIToContact+' => '',
	'Class:lnkCIToContact/Attribute:ci_id' => 'CI',
	'Class:lnkCIToContact/Attribute:ci_id+' => '',
	'Class:lnkCIToContact/Attribute:ci_name' => 'CI',
	'Class:lnkCIToContact/Attribute:ci_name+' => '',
	'Class:lnkCIToContact/Attribute:ci_status' => 'CI Status',
	'Class:lnkCIToContact/Attribute:ci_status+' => '',
	'Class:lnkCIToContact/Attribute:contact_id' => 'Contatos',
	'Class:lnkCIToContact/Attribute:contact_id+' => '',
	'Class:lnkCIToContact/Attribute:contact_name' => 'Contatos',
	'Class:lnkCIToContact/Attribute:contact_name+' => '',
	'Class:lnkCIToContact/Attribute:contact_email' => 'Contatos Email',
	'Class:lnkCIToContact/Attribute:contact_email+' => '',
	'Class:lnkCIToContact/Attribute:role' => 'Papel',
	'Class:lnkCIToContact/Attribute:role+' => 'Papel do contato em rela&ccedil;&atilde;o ao CI',
));

//
// Class: lnkSolutionToCI
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkSolutionToCI' => 'CI/Solu&ccedil;&atilde;o',
	'Class:lnkSolutionToCI+' => '',
	'Class:lnkSolutionToCI/Attribute:solution_id' => 'Application solution',
	'Class:lnkSolutionToCI/Attribute:solution_id+' => '',
	'Class:lnkSolutionToCI/Attribute:solution_name' => 'Application solution',
	'Class:lnkSolutionToCI/Attribute:solution_name+' => '',
	'Class:lnkSolutionToCI/Attribute:ci_id' => 'CI',
	'Class:lnkSolutionToCI/Attribute:ci_id+' => '',
	'Class:lnkSolutionToCI/Attribute:ci_name' => 'CI',
	'Class:lnkSolutionToCI/Attribute:ci_name+' => '',
	'Class:lnkSolutionToCI/Attribute:ci_status' => 'CI Status',
	'Class:lnkSolutionToCI/Attribute:ci_status+' => '',
	'Class:lnkSolutionToCI/Attribute:utility' => 'Utilidade',
	'Class:lnkSolutionToCI/Attribute:utility+' => 'Utilidade da CI na solu&ccedil;&atilde;o',
));

//
// Class: lnkProcessToSolution
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkProcessToSolution' => 'Business process/Solution',
	'Class:lnkProcessToSolution+' => '',
	'Class:lnkProcessToSolution/Attribute:solution_id' => 'Application solution',
	'Class:lnkProcessToSolution/Attribute:solution_id+' => '',
	'Class:lnkProcessToSolution/Attribute:solution_name' => 'Application solution',
	'Class:lnkProcessToSolution/Attribute:solution_name+' => '',
	'Class:lnkProcessToSolution/Attribute:process_id' => 'Processo',
	'Class:lnkProcessToSolution/Attribute:process_id+' => '',
	'Class:lnkProcessToSolution/Attribute:process_name' => 'Processo',
	'Class:lnkProcessToSolution/Attribute:process_name+' => '',
	'Class:lnkProcessToSolution/Attribute:reason' => 'Raz&atilde;o',
	'Class:lnkProcessToSolution/Attribute:reason+' => 'Mais informa&ccedil;&otilde;es sobre a liga&ccedil;&atilde;o entre o processo e a solução',
));



//
// Class extensions
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
'Class:Subnet/Tab:IPUsage' => 'IP Usado',
'Class:Subnet/Tab:IPUsage-explain' => 'Interfaces possuem um endereço IP na faixa: <em>%1$s</em> até <em>%2$s</em>',
'Class:Subnet/Tab:FreeIPs' => 'IPs livres',
'Class:Subnet/Tab:FreeIPs-count' => 'IPs livres: %1$s',
'Class:Subnet/Tab:FreeIPs-explain' => 'Abaixo um extrato de 10 endereços de IP livres',
));

//
// Application Menu
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
'Menu:Catalogs' => 'Catálogos',
'Menu:Catalogs+' => 'Tipo de dados',
'Menu:Audit' => 'Auditoria',
'Menu:Audit+' => 'Auditoria',
'Menu:Organization' => 'Organizações',
'Menu:Organization+' => 'Todas Organizações',
'Menu:Application' => 'Aplicativos',
'Menu:Application+' => 'Todos Aplicativos',
'Menu:DBServer' => 'Banco de dado Servers',
'Menu:DBServer+' => 'Banco de dado Servers',
'Menu:Audit' => 'Auditoria',
'Menu:ConfigManagement' => 'Gerenciamento Configurações',
'Menu:ConfigManagement+' => 'Gerenciamento Configurações',
'Menu:ConfigManagementOverview' => 'Visão Global',
'Menu:ConfigManagementOverview+' => 'Visão Global',
'Menu:Contact' => 'Contatos',
'Menu:Contact+' => 'Contatos',
'Menu:Person' => 'Pessoas',
'Menu:Person+' => 'Todas Pessoas',
'Menu:Team' => 'Equipes',
'Menu:Team+' => 'Todas Equipes',
'Menu:Document' => 'Documentos',
'Menu:Document+' => 'Todos Documentos',
'Menu:Location' => 'Localizações',
'Menu:Location+' => 'Todas Localizações',
'Menu:ConfigManagementCI' => 'Configura&ccedil;&atilde;o Itens',
'Menu:ConfigManagementCI+' => 'Configura&ccedil;&atilde;o Itens',
'Menu:BusinessProcess' => 'Processos Negócio',
'Menu:BusinessProcess+' => 'Todos Processos Negócio',
'Menu:ApplicationSolution' => 'Application Solutions',
'Menu:ApplicationSolution+' => 'All Application Solutions',
'Menu:ConfigManagementSoftware' => 'Gerenciamento Aplicativos',
'Menu:Licence' => 'Licenças',
'Menu:Licence+' => 'Todas Licenças',
'Menu:Patch' => 'Patches',
'Menu:Patch+' => 'Todos Patches',
'Menu:ApplicationInstance' => 'Software instalados',
'Menu:ApplicationInstance+' => 'Aplicativos e Banco de dados em servidores',
'Menu:ConfigManagementHardware' => 'Gerenciamento Infra-estrutura',
'Menu:Subnet' => 'Sub-redes',
'Menu:Subnet+' => 'Todas as sub-redes',
'Menu:NetworkDevice' => 'Dispositivos de rede',
'Menu:NetworkDevice+' => 'Todos os dispositivos de rede',
'Menu:Server' => 'Servidores',
'Menu:Server+' => 'Todos servidores',
'Menu:Printer' => 'Impressoras',
'Menu:Printer+' => 'Todas impressoras',
'Menu:MobilePhone' => 'Mobilidade',
'Menu:MobilePhone+' => 'Todos dispositivos móveis',
'Menu:PC' => 'Micro-computadores',
'Menu:PC+' => 'Todos micro-computadores',
'Menu:NewContact' => 'Novo Contato',
'Menu:NewContact+' => 'Novo Contato',
'Menu:SearchContacts' => 'Pesquisa para contatos',
'Menu:SearchContacts+' => 'Pesquisa para contatos',
'Menu:NewCI' => 'Novo Configuração Iten',
'Menu:NewCI+' => 'Novo Configuração Iten',
'Menu:SearchCIs' => 'Pesquisa para CIs',
'Menu:SearchCIs+' => 'Pesquisa para CIs',
'Menu:ConfigManagement:Devices' => 'Devices',
'Menu:ConfigManagement:AllDevices' => 'Number of devices: %1$d',
'Menu:ConfigManagement:SWAndApps' => 'Software and Applications',
'Menu:ConfigManagement:Misc' => 'Miscellaneous',
'Menu:Group' => 'Grupo de CIs',
'Menu:Group+' => 'Grupo de CIs',
'Menu:ConfigManagement:Shortcuts' => 'Atalhos',
'Menu:ConfigManagement:AllContacts' => 'Todos contatos: %1$d',
));
?>
