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
 * @author	Erwan Taloc <erwan.taloc@combodo.com>
 * @author	Romain Quetiez <romain.quetiez@combodo.com>
 * @author	Denis Flaven <denis.flaven@combodo.com>
 * @author	Marco Tulio <mtulio@opensolucoes.com.br>

 * @licence	http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:KnownError' => 'Erros conhecidos',
	'Class:KnownError+' => 'Erro documentado de um problema conhecido',
	'Class:KnownError/Attribute:name' => 'Nome',
	'Class:KnownError/Attribute:name+' => '',
	'Class:KnownError/Attribute:org_id' => 'Cliente',
	'Class:KnownError/Attribute:org_id+' => '',
	'Class:KnownError/Attribute:problem_id' => 'Problema relacionado',
	'Class:KnownError/Attribute:problem_id+' => '',
	'Class:KnownError/Attribute:symptom' => 'Sintoma',
	'Class:KnownError/Attribute:symptom+' => '',
	'Class:KnownError/Attribute:root_cause' => 'Origem causa',
	'Class:KnownError/Attribute:root_cause+' => '',
	'Class:KnownError/Attribute:workaround' => 'Solução',
	'Class:KnownError/Attribute:workaround+' => '',
	'Class:KnownError/Attribute:solution' => 'Solução',
	'Class:KnownError/Attribute:solution+' => '',
	'Class:KnownError/Attribute:error_code' => 'Código erro',
	'Class:KnownError/Attribute:error_code+' => '',
	'Class:KnownError/Attribute:domain' => 'Domínio',
	'Class:KnownError/Attribute:domain+' => '',
	'Class:KnownError/Attribute:domain/Value:Application' => 'Aplicação',
	'Class:KnownError/Attribute:domain/Value:Application+' => 'Aplicação',
	'Class:KnownError/Attribute:domain/Value:Desktop' => 'Desktop',
	'Class:KnownError/Attribute:domain/Value:Desktop+' => 'Desktop',
	'Class:KnownError/Attribute:domain/Value:Network' => 'Rede',
	'Class:KnownError/Attribute:domain/Value:Network+' => 'Rede',
	'Class:KnownError/Attribute:domain/Value:Server' => 'Servidor',
	'Class:KnownError/Attribute:domain/Value:Server+' => 'Servidor',
	'Class:KnownError/Attribute:vendor' => 'Vendedor',
	'Class:KnownError/Attribute:vendor+' => '',
	'Class:KnownError/Attribute:model' => 'Modelo',
	'Class:KnownError/Attribute:model+' => '',
	'Class:KnownError/Attribute:version' => 'Versão',
	'Class:KnownError/Attribute:version+' => '',
	'Class:KnownError/Attribute:ci_list' => 'CIs',
	'Class:KnownError/Attribute:ci_list+' => '',
	'Class:KnownError/Attribute:document_list' => 'Documentos',
	'Class:KnownError/Attribute:document_list+' => '',
	'Class:lnkInfraError' => 'InfraErrorLinks',
	'Class:lnkInfraError+' => 'Infra relacionada a um erro conhecido',
	'Class:lnkInfraError/Attribute:infra_id' => 'CI',
	'Class:lnkInfraError/Attribute:infra_id+' => '',
	'Class:lnkInfraError/Attribute:error_id' => 'Erro',
	'Class:lnkInfraError/Attribute:error_id+' => '',
	'Class:lnkInfraError/Attribute:reason' => 'Razão',
	'Class:lnkInfraError/Attribute:reason+' => '',
	'Class:lnkDocumentError' => 'DocumentosErroLinks',
	'Class:lnkDocumentError+' => 'Ligação entre um documento e um erro conhecido',
	'Class:lnkDocumentError/Attribute:doc_id' => 'Documento',
	'Class:lnkDocumentError/Attribute:doc_id+' => '',
	'Class:lnkDocumentError/Attribute:error_id' => 'Erro',
	'Class:lnkDocumentError/Attribute:error_id+' => '',
	'Class:lnkDocumentError/Attribute:link_type' => 'Informação',
	'Class:lnkDocumentError/Attribute:link_type+' => '',
	'Class:KnownError/Attribute:cust_name' => 'Nome cliente',
	'Class:KnownError/Attribute:cust_name+' => '',
	'Class:KnownError/Attribute:problem_ref' => 'Ref',
	'Class:KnownError/Attribute:problem_ref+' => '',
	'Class:lnkInfraError/Attribute:infra_name' => 'Nome CI',
	'Class:lnkInfraError/Attribute:infra_name+' => '',
	'Class:lnkInfraError/Attribute:infra_status' => 'Status CI',
	'Class:lnkInfraError/Attribute:infra_status+' => '',
	'Class:lnkInfraError/Attribute:error_name' => 'Nome Erro',
	'Class:lnkInfraError/Attribute:error_name+' => '',
	'Class:lnkDocumentError/Attribute:doc_name' => 'Nome Documento',
	'Class:lnkDocumentError/Attribute:doc_name+' => '',
	'Class:lnkDocumentError/Attribute:error_name' => 'Nome Erro',
	'Class:lnkDocumentError/Attribute:error_name+' => '',
	'Menu:NewError' => 'Novo erro conhecido',
	'Menu:NewError+' => 'Criação de um novo erro conhecido',
	'Menu:SearchError' => 'Pesquisar Erros Conhecidos',
	'Menu:SearchError+' => 'Pesquisar Erros Conhecidos',
	'Menu:Problem:KnownErrors' => 'Todos Erros Conhecidos',
	'Menu:Problem:KnownErrors+' => 'Todos Erros Conhecidos',
));
?>
