<?php

/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 *
 */
/**
 *
 */
Dict::Add('PT BR', 'Brazilian', 'Brazilian', [
	'Class:KnownError' => 'Erro Conhecido',
	'Class:KnownError+' => 'Erro documentado de um problema conhecido',
	'Class:KnownError/Attribute:name' => 'Nome',
	'Class:KnownError/Attribute:name+' => 'This is expected to be a unique identifier within the Known Errors of this organization~~',
	'Class:KnownError/Attribute:org_id' => 'Cliente',
	'Class:KnownError/Attribute:org_id+' => 'Link the known error to the service provider in charge of handling them, or maybe to a customer organization if the error is specific to them~~',
	'Class:KnownError/Attribute:cust_name' => 'Nome do cliente',
	'Class:KnownError/Attribute:cust_name+' => '',
	'Class:KnownError/Attribute:problem_id' => 'Problema relacionado',
	'Class:KnownError/Attribute:problem_id+' => 'The problem which couldn\'t be solved immediately and has led to the creation of this known error~~',
	'Class:KnownError/Attribute:problem_ref' => 'Ref. problema relacionado',
	'Class:KnownError/Attribute:problem_ref+' => '',
	'Class:KnownError/Attribute:symptom' => 'Sintoma do erro',
	'Class:KnownError/Attribute:symptom+' => 'What are the observable effects of this error?~~',
	'Class:KnownError/Attribute:root_cause' => 'Causa',
	'Class:KnownError/Attribute:root_cause+' => 'What is the underlying cause of this error?~~',
	'Class:KnownError/Attribute:workaround' => 'Solução de contorno',
	'Class:KnownError/Attribute:workaround+' => 'How to bypass the effects of this error until a proper solution is found?~~',
	'Class:KnownError/Attribute:solution' => 'Solução',
	'Class:KnownError/Attribute:solution+' => 'What is the permanent solution for this error?~~',
	'Class:KnownError/Attribute:error_code' => 'Código do erro',
	'Class:KnownError/Attribute:error_code+' => 'If a specific error code is associated to this known error, specify it here~~',
	'Class:KnownError/Attribute:domain' => 'Domínio',
	'Class:KnownError/Attribute:domain+' => 'Choose the technical domain related to this known error?~~',
	'Class:KnownError/Attribute:domain/Value:Application' => 'Aplicação',
	'Class:KnownError/Attribute:domain/Value:Application+' => '',
	'Class:KnownError/Attribute:domain/Value:Desktop' => 'Desktop',
	'Class:KnownError/Attribute:domain/Value:Desktop+' => '',
	'Class:KnownError/Attribute:domain/Value:Network' => 'Rede',
	'Class:KnownError/Attribute:domain/Value:Network+' => '',
	'Class:KnownError/Attribute:domain/Value:Server' => 'Servidor',
	'Class:KnownError/Attribute:domain/Value:Server+' => '',
	'Class:KnownError/Attribute:vendor' => 'Fabricante',
	'Class:KnownError/Attribute:vendor+' => 'A free text field to identify the vendor of the CI(s) concerned by this known error~~',
	'Class:KnownError/Attribute:model' => 'Modelo',
	'Class:KnownError/Attribute:model+' => 'The model of the CI(s) concerned by this known error~~',
	'Class:KnownError/Attribute:version' => 'Versão',
	'Class:KnownError/Attribute:version+' => 'The version of the CI(s) concerned by this known error~~',
	'Class:KnownError/Attribute:ci_list' => 'ICs',
	'Class:KnownError/Attribute:ci_list+' => 'Todos os Itens de Configuração (ICs) associados à este Erro conhecido',
	'Class:KnownError/Attribute:document_list' => 'Documentos',
	'Class:KnownError/Attribute:document_list+' => 'Todos os Documentos associados à este Erro conhecido',
]);

//
// Class: lnkErrorToFunctionalCI
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', [
	'Class:lnkErrorToFunctionalCI' => 'Relação entre Erro conhecido / IC',
	'Class:lnkErrorToFunctionalCI+' => 'Itens de configuração (IC) associados à este Erro conhecido',
	'Class:lnkErrorToFunctionalCI/Name' => '%1$s / %2$s~~',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id' => 'ICs',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name' => 'Nome do IC',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:error_id' => 'Erro',
	'Class:lnkErrorToFunctionalCI/Attribute:error_id+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:error_name' => 'Nome do erro',
	'Class:lnkErrorToFunctionalCI/Attribute:error_name+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:reason' => 'Motivo do link',
	'Class:lnkErrorToFunctionalCI/Attribute:reason+' => '',
]);

//
// Class: lnkDocumentToError
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', [
	'Class:lnkDocumentToError' => 'Relação entre Documento / Erro',
	'Class:lnkDocumentToError+' => 'Uma ligação entre um documento e um erro conhecido',
	'Class:lnkDocumentToError/Name' => '%1$s / %2$s~~',
	'Class:lnkDocumentToError/Attribute:document_id' => 'Documento',
	'Class:lnkDocumentToError/Attribute:document_id+' => '',
	'Class:lnkDocumentToError/Attribute:document_name' => 'Nome do documento',
	'Class:lnkDocumentToError/Attribute:document_name+' => '',
	'Class:lnkDocumentToError/Attribute:error_id' => 'Erro',
	'Class:lnkDocumentToError/Attribute:error_id+' => '',
	'Class:lnkDocumentToError/Attribute:error_name' => 'Nome do erro',
	'Class:lnkDocumentToError/Attribute:error_name+' => '',
	'Class:lnkDocumentToError/Attribute:link_type' => 'Tipo de link',
	'Class:lnkDocumentToError/Attribute:link_type+' => '',
]);

Dict::Add('PT BR', 'Brazilian', 'Brazilian', [
	'Menu:ProblemManagement' => 'Gerenciamento de Problemas',
	'Menu:ProblemManagement+' => 'Lista de Gerenciamento de Problemas',
	'Menu:Problem:Shortcuts' => 'Atalhos',
	'Menu:NewError' => 'Novo erro conhecido',
	'Menu:NewError+' => '',
	'Menu:SearchError' => 'Pesquisar por erros conhecidos',
	'Menu:SearchError+' => '',
	'Menu:Problem:KnownErrors' => 'Erros Conhecidos',
	'Menu:Problem:KnownErrors+' => 'Erro documentado de um problema conhecido',
]);
