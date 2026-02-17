<?php

/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license	http://opensource.org/licenses/AGPL-3.0
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with iTop. If not, see <http://www.gnu.org/licenses/>
 */
Dict::Add('DE DE', 'German', 'Deutsch', [
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

Dict::Add('DE DE', 'German', 'Deutsch', [
	'Class:LogicalInterface/Attribute:org_id' => 'Organization~~',
	'Class:LogicalInterface/Attribute:org_id+' => '~~',
]);
