<?php

/**
 * Spanish Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * @author Miguel Turrubiates <miguel_tf@yahoo.com>
 * @notas       Utilizar codificación UTF-8 para mostrar acentos y otros caracteres especiales
 */
Dict::Add('ES CR', 'Spanish', 'Español, Castellano', [
	'Class:KnownError' => 'Error Conocido',
	'Class:KnownError+' => 'Documentación para un error conocido',
	'Class:KnownError/Attribute:name' => 'Nombre',
	'Class:KnownError/Attribute:name+' => 'This is expected to be a unique identifier within the Known Errors of this organization~~',
	'Class:KnownError/Attribute:org_id' => 'Organización',
	'Class:KnownError/Attribute:org_id+' => 'Link the known error to the service provider in charge of handling them, or maybe to a customer organization if the error is specific to them~~',
	'Class:KnownError/Attribute:cust_name' => 'Nombre',
	'Class:KnownError/Attribute:cust_name+' => '',
	'Class:KnownError/Attribute:problem_id' => 'Problema Relacionado',
	'Class:KnownError/Attribute:problem_id+' => 'The problem which couldn\'t be solved immediately and has led to the creation of this known error~~',
	'Class:KnownError/Attribute:problem_ref' => 'Referencia',
	'Class:KnownError/Attribute:problem_ref+' => '',
	'Class:KnownError/Attribute:symptom' => 'Síntoma',
	'Class:KnownError/Attribute:symptom+' => 'What are the observable effects of this error?~~',
	'Class:KnownError/Attribute:root_cause' => 'Causa Raíz',
	'Class:KnownError/Attribute:root_cause+' => 'What is the underlying cause of this error?~~',
	'Class:KnownError/Attribute:workaround' => 'Solución Temporal',
	'Class:KnownError/Attribute:workaround+' => 'How to bypass the effects of this error until a proper solution is found?~~',
	'Class:KnownError/Attribute:solution' => 'Solución Final',
	'Class:KnownError/Attribute:solution+' => 'What is the permanent solution for this error?~~',
	'Class:KnownError/Attribute:error_code' => 'Código de Error',
	'Class:KnownError/Attribute:error_code+' => 'If a specific error code is associated to this known error, specify it here~~',
	'Class:KnownError/Attribute:domain' => 'Dominio',
	'Class:KnownError/Attribute:domain+' => 'Choose the technical domain related to this known error?~~',
	'Class:KnownError/Attribute:domain/Value:Application' => 'Aplicación',
	'Class:KnownError/Attribute:domain/Value:Application+' => '',
	'Class:KnownError/Attribute:domain/Value:Desktop' => 'Escritorio',
	'Class:KnownError/Attribute:domain/Value:Desktop+' => '',
	'Class:KnownError/Attribute:domain/Value:Network' => 'Red',
	'Class:KnownError/Attribute:domain/Value:Network+' => '',
	'Class:KnownError/Attribute:domain/Value:Server' => 'Servidor',
	'Class:KnownError/Attribute:domain/Value:Server+' => '',
	'Class:KnownError/Attribute:vendor' => 'Proveedor',
	'Class:KnownError/Attribute:vendor+' => 'A free text field to identify the vendor of the CI(s) concerned by this known error~~',
	'Class:KnownError/Attribute:model' => 'Modelo',
	'Class:KnownError/Attribute:model+' => 'The model of the CI(s) concerned by this known error~~',
	'Class:KnownError/Attribute:version' => 'Versión',
	'Class:KnownError/Attribute:version+' => 'The version of the CI(s) concerned by this known error~~',
	'Class:KnownError/Attribute:ci_list' => 'ECs',
	'Class:KnownError/Attribute:ci_list+' => 'The configuration items that are potentially impacted by this known error~~',
	'Class:KnownError/Attribute:document_list' => 'Documentos',
	'Class:KnownError/Attribute:document_list+' => '',
]);

//
// Class: lnkErrorToFunctionalCI
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', [
	'Class:lnkErrorToFunctionalCI' => 'Relación Error Conocido y EC Funcional',
	'Class:lnkErrorToFunctionalCI+' => '',
	'Class:lnkErrorToFunctionalCI/Name' => '%1$s / %2$s',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id' => 'EC',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id+' => 'Elemento de Configuración',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name' => 'CI',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name+' => 'Elemento de Configuración',
	'Class:lnkErrorToFunctionalCI/Attribute:error_id' => 'Error Conocido',
	'Class:lnkErrorToFunctionalCI/Attribute:error_id+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:error_name' => 'Error Conocido',
	'Class:lnkErrorToFunctionalCI/Attribute:error_name+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:reason' => 'Motivo',
	'Class:lnkErrorToFunctionalCI/Attribute:reason+' => '',
]);

//
// Class: lnkDocumentToError
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', [
	'Class:lnkDocumentToError' => 'Relación Documento y Error Conocido',
	'Class:lnkDocumentToError+' => 'Relación Documento y Error Conocido',
	'Class:lnkDocumentToError/Name' => '%1$s / %2$s',
	'Class:lnkDocumentToError/Attribute:document_id' => 'Documento',
	'Class:lnkDocumentToError/Attribute:document_id+' => 'Documento',
	'Class:lnkDocumentToError/Attribute:document_name' => 'Documento',
	'Class:lnkDocumentToError/Attribute:document_name+' => 'Documento',
	'Class:lnkDocumentToError/Attribute:error_id' => 'Error Conocido',
	'Class:lnkDocumentToError/Attribute:error_id+' => 'Error Conocido',
	'Class:lnkDocumentToError/Attribute:error_name' => 'Error Conocido',
	'Class:lnkDocumentToError/Attribute:error_name+' => 'Error Conocido',
	'Class:lnkDocumentToError/Attribute:link_type' => 'Tipo',
	'Class:lnkDocumentToError/Attribute:link_type+' => 'Tipo',
]);

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', [
	'Menu:ProblemManagement' => 'Administración de Problemas',
	'Menu:ProblemManagement+' => 'GestAdministraciónión de problemas',
	'Menu:Problem:Shortcuts' => 'Acceso Rápido',
	'Menu:NewError' => 'Nuevo Error Conocido',
	'Menu:NewError+' => 'Nuevo Error Conocido',
	'Menu:SearchError' => 'Búsqueda de Errores Conocidos',
	'Menu:SearchError+' => 'Búsqueda de Errores Conocidos',
	'Menu:Problem:KnownErrors' => 'Errores Conocidos',
	'Menu:Problem:KnownErrors+' => 'Errores Conocidos',
]);
