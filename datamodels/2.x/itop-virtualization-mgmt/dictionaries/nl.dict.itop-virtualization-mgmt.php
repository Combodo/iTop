<?php

/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 *
 */
/**
 * @author Erwan Taloc <erwan.taloc@combodo.com>
 * @author Romain Quetiez <romain.quetiez@combodo.com>
 * @author Denis Flaven <denis.flaven@combodo.com>
 *
 */
//
// Fieldsets for Virtualization classes
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', [
	'Virtualization:baseinfo' => 'General~~',
	'Virtualization:moreinfo' => 'Virtualization specifics~~',
	'Virtualization:otherinfo' => 'Dates and description~~',
]);

//
// Class Cloud
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', [
	'Class:Cloud/Name' => '%1$s',
	'Class:Cloud/ComplementaryName' => '%1$s-%2$s',
	'Class:Cloud' => 'Cloud~~',
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

Dict::Add('NL NL', 'Dutch', 'Nederlands', [
	'Class:LogicalInterface/Attribute:org_id' => 'Organisatie',
	'Class:LogicalInterface/Attribute:org_id+' => '~~',
]);
