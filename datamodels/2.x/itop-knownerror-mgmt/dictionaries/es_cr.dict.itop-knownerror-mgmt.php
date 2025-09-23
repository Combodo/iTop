<?php
/**
 * Spanish Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * @author Miguel Turrubiates <miguel_tf@yahoo.com>
 * @notas       Utilizar codificación UTF-8 para mostrar acentos y otros caracteres especiales 
 */
Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:KnownError' => 'Error Conocido',
	'Class:KnownError+' => 'Documentación para un error conocido',
	'Class:KnownError/Attribute:name' => 'Nombre',
	'Class:KnownError/Attribute:name+' => 'Nombre del Error Conocido',
	'Class:KnownError/Attribute:org_id' => 'Organización',
	'Class:KnownError/Attribute:org_id+' => 'Organización',
	'Class:KnownError/Attribute:cust_name' => 'Nombre',
	'Class:KnownError/Attribute:cust_name+' => 'Nombre',
	'Class:KnownError/Attribute:problem_id' => 'Problema Relacionado',
	'Class:KnownError/Attribute:problem_id+' => 'Problema',
	'Class:KnownError/Attribute:problem_ref' => 'Referencia',
	'Class:KnownError/Attribute:problem_ref+' => 'Refencia',
	'Class:KnownError/Attribute:symptom' => 'Síntoma',
	'Class:KnownError/Attribute:symptom+' => 'Síntoma',
	'Class:KnownError/Attribute:root_cause' => 'Causa Raíz',
	'Class:KnownError/Attribute:root_cause+' => 'Causa Raíz',
	'Class:KnownError/Attribute:workaround' => 'Solución Temporal',
	'Class:KnownError/Attribute:workaround+' => 'Solución Temporal',
	'Class:KnownError/Attribute:solution' => 'Solución Final',
	'Class:KnownError/Attribute:solution+' => 'Solución Final',
	'Class:KnownError/Attribute:error_code' => 'Código de Error',
	'Class:KnownError/Attribute:error_code+' => 'Código de Error',
	'Class:KnownError/Attribute:domain' => 'Dominio',
	'Class:KnownError/Attribute:domain+' => 'Dominio',
	'Class:KnownError/Attribute:domain/Value:Application' => 'Aplicación',
	'Class:KnownError/Attribute:domain/Value:Application+' => 'Aplicación',
	'Class:KnownError/Attribute:domain/Value:Desktop' => 'Escritorio',
	'Class:KnownError/Attribute:domain/Value:Desktop+' => 'Escritorio',
	'Class:KnownError/Attribute:domain/Value:Network' => 'Red',
	'Class:KnownError/Attribute:domain/Value:Network+' => 'Red',
	'Class:KnownError/Attribute:domain/Value:Server' => 'Servidor',
	'Class:KnownError/Attribute:domain/Value:Server+' => 'Servidor',
	'Class:KnownError/Attribute:vendor' => 'Proveedor',
	'Class:KnownError/Attribute:vendor+' => 'Proveedor',
	'Class:KnownError/Attribute:model' => 'Modelo',
	'Class:KnownError/Attribute:model+' => 'Modelo',
	'Class:KnownError/Attribute:version' => 'Versión',
	'Class:KnownError/Attribute:version+' => 'Versión',
	'Class:KnownError/Attribute:ci_list' => 'ECs',
	'Class:KnownError/Attribute:ci_list+' => 'ECs',
	'Class:KnownError/Attribute:document_list' => 'Documentos',
	'Class:KnownError/Attribute:document_list+' => 'Documentos',
));

//
// Class: lnkErrorToFunctionalCI
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkErrorToFunctionalCI' => 'Relación Error Conocido y EC Funcional',
	'Class:lnkErrorToFunctionalCI+' => 'Relación Error Conocido y EC Funcional',
	'Class:lnkErrorToFunctionalCI/Name' => '%1$s / %2$s',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id' => 'EC',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id+' => 'Elemento de Configuración',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name' => 'CI',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name+' => 'Elemento de Configuración',
	'Class:lnkErrorToFunctionalCI/Attribute:error_id' => 'Error Conocido',
	'Class:lnkErrorToFunctionalCI/Attribute:error_id+' => 'Error Conocido',
	'Class:lnkErrorToFunctionalCI/Attribute:error_name' => 'Error Conocido',
	'Class:lnkErrorToFunctionalCI/Attribute:error_name+' => 'Error Conocido',
	'Class:lnkErrorToFunctionalCI/Attribute:reason' => 'Motivo',
	'Class:lnkErrorToFunctionalCI/Attribute:reason+' => 'Motivo',
));

//
// Class: lnkDocumentToError
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
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
));

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Menu:ProblemManagement' => 'Administración de Problemas',
	'Menu:ProblemManagement+' => 'GestAdministraciónión de problemas',
	'Menu:Problem:Shortcuts' => 'Acceso Rápido',
	'Menu:NewError' => 'Nuevo Error Conocido',
	'Menu:NewError+' => 'Nuevo Error Conocido',
	'Menu:SearchError' => 'Búsqueda de Errores Conocidos',
	'Menu:SearchError+' => 'Búsqueda de Errores Conocidos',
	'Menu:Problem:KnownErrors' => 'Errores Conocidos',
	'Menu:Problem:KnownErrors+' => 'Errores Conocidos',
));
