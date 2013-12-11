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

//
// Class: KnownError
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:KnownError' => 'Erros conhecidos',
	'Class:KnownError+' => 'Erro documentado de um problema conhecido',
	'Class:KnownError/Attribute:name' => 'Nome',
	'Class:KnownError/Attribute:name+' => '',
	'Class:KnownError/Attribute:org_id' => 'Cliente',
	'Class:KnownError/Attribute:org_id+' => '',
	'Class:KnownError/Attribute:cust_name' => 'Nome cliente',
	'Class:KnownError/Attribute:cust_name+' => '',
	'Class:KnownError/Attribute:problem_id' => 'Problema vinculado',
	'Class:KnownError/Attribute:problem_id+' => '',
	'Class:KnownError/Attribute:problem_ref' => 'Ref problema vinculado',
	'Class:KnownError/Attribute:problem_ref+' => '',
	'Class:KnownError/Attribute:symptom' => 'Sinal erro',
	'Class:KnownError/Attribute:symptom+' => '',
	'Class:KnownError/Attribute:root_cause' => 'Origem causa',
	'Class:KnownError/Attribute:root_cause+' => '',
	'Class:KnownError/Attribute:workaround' => 'Contornar',
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
	'Class:KnownError/Attribute:vendor' => 'Fabricante',
	'Class:KnownError/Attribute:vendor+' => '',
	'Class:KnownError/Attribute:model' => 'Modelo',
	'Class:KnownError/Attribute:model+' => '',
	'Class:KnownError/Attribute:version' => 'Versão',
	'Class:KnownError/Attribute:version+' => '',
	'Class:KnownError/Attribute:ci_list' => 'CIs',
	'Class:KnownError/Attribute:ci_list+' => 'Todos os itens de configuração que estão vinculados a esse erro conhecido',
	'Class:KnownError/Attribute:document_list' => 'Documentos',
	'Class:KnownError/Attribute:document_list+' => 'Todos os documentos vinculados a esse erro conhecido',
));

//
// Class: lnkErrorToFunctionalCI
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkErrorToFunctionalCI' => 'Link Erro / CI',
	'Class:lnkErrorToFunctionalCI+' => 'Infra-estrutura vinculado para esse erro conhecido',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id' => 'CIs',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name' => 'Nome CI',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:error_id' => 'Erro',
	'Class:lnkErrorToFunctionalCI/Attribute:error_id+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:error_name' => 'Nome erro',
	'Class:lnkErrorToFunctionalCI/Attribute:error_name+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:reason' => 'Razão',
	'Class:lnkErrorToFunctionalCI/Attribute:reason+' => '',
));

//
// Class: lnkDocumentToError
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkDocumentToError' => 'Link Documentos / Erros',
	'Class:lnkDocumentToError+' => 'Uma ligação entre um documento e um erro conhecido',
	'Class:lnkDocumentToError/Attribute:document_id' => 'Documento',
	'Class:lnkDocumentToError/Attribute:document_id+' => '',
	'Class:lnkDocumentToError/Attribute:document_name' => 'Nome documento',
	'Class:lnkDocumentToError/Attribute:document_name+' => '',
	'Class:lnkDocumentToError/Attribute:error_id' => 'Erro',
	'Class:lnkDocumentToError/Attribute:error_id+' => '',
	'Class:lnkDocumentToError/Attribute:error_name' => 'Nome erro',
	'Class:lnkDocumentToError/Attribute:error_name+' => '',
	'Class:lnkDocumentToError/Attribute:link_type' => 'link_type',
	'Class:lnkDocumentToError/Attribute:link_type+' => '',
));

//
// Class: FAQ
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:FAQ' => 'FAQ',
	'Class:FAQ+' => 'Perguntas mais frequentes',
	'Class:FAQ/Attribute:title' => 'Título',
	'Class:FAQ/Attribute:title+' => '',
	'Class:FAQ/Attribute:summary' => 'Índice',
	'Class:FAQ/Attribute:summary+' => '',
	'Class:FAQ/Attribute:description' => 'Descrição',
	'Class:FAQ/Attribute:description+' => '',
	'Class:FAQ/Attribute:category_id' => 'Categoria',
	'Class:FAQ/Attribute:category_id+' => '',
	'Class:FAQ/Attribute:category_name' => 'Nome categoria',
	'Class:FAQ/Attribute:category_name+' => '',
	'Class:FAQ/Attribute:error_code' => 'Código erro',
	'Class:FAQ/Attribute:error_code+' => '',
	'Class:FAQ/Attribute:key_words' => 'Palavras-chaves',
	'Class:FAQ/Attribute:key_words+' => '',
));

//
// Class: FAQCategory
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:FAQCategory' => 'Categoria FAQ',
	'Class:FAQCategory+' => 'Categoria por FAQ',
	'Class:FAQCategory/Attribute:name' => 'Nome',
	'Class:FAQCategory/Attribute:name+' => '',
	'Class:FAQCategory/Attribute:faq_list' => 'FAQs',
	'Class:FAQCategory/Attribute:faq_list+' => 'Todas as perguntas mais frequentes vinculadas a essa categoria',
));

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Menu:ProblemManagement' => 'Gerencimento Problemas',
	'Menu:ProblemManagement+' => 'Gerencimento Problemas',
	'Menu:Problem:Shortcuts' => 'Atalho',
	'Menu:NewError' => 'Novo erro conhecido',
	'Menu:NewError+' => 'Criar um erro conhecido',
	'Menu:SearchError' => 'Pesquisar por um erro conhecido',
	'Menu:SearchError+' => 'Pesquisar por erros conhecidos',
        'Menu:Problem:KnownErrors' => 'Todos erros conhecidos',
        'Menu:Problem:KnownErrors+' => 'Todos erros conhecidos',
	'Menu:FAQCategory' => 'Categorias FAQ',
	'Menu:FAQCategory+' => 'Todas categorias FAQ',
	'Menu:FAQ' => 'FAQs',
	'Menu:FAQ+' => 'Todas FAQs',

));
?>
