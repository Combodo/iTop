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
	'Class:Cloud/Name' => '%1$s',
	'Class:Cloud/ComplementaryName' => '%1$s-%2$s',
	'Class:Cloud' => 'Cloud',
	'Class:Cloud+' => 'A Virtual Host operated by a Cloud provider. It can host Virtual Machines and Container Hosts.~~',
	'Class:Cloud/Attribute:provider_id+' => 'Who provides the cloud~~',
	'Class:Cloud/Attribute:location_id' => 'Location~~',
	'Class:Cloud/Attribute:location_id+' => 'Where is located the cloud~~',
	'Class:Cloud/Attribute:containerhosts_list' => 'Container Hosts~~',
	'Class:Cloud/Attribute:containerhosts_list+' => 'List of container hosts hosted in this cloud~~',
]);

//
// Class: LogicalInterface
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', [
	'Class:LogicalInterface/Attribute:org_id' => 'Organization~~',
	'Class:LogicalInterface/Attribute:org_id+' => '~~',
]);
