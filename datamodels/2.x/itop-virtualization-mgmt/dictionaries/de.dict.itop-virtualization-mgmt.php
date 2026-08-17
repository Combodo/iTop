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

//
// Fieldsets for Virtualization classes
//

Dict::Add('DE DE', 'German', 'Deutsch', [
	'Virtualization:baseinfo' => 'Allgemein',
	'Virtualization:moreinfo' => 'Virtualisierungsspezifische Angaben',
	'Virtualization:otherinfo' => 'Daten und Beschreibung',
]);

//
// Class Cloud
//

Dict::Add('DE DE', 'German', 'Deutsch', [
	'Class:Cloud/Name' => '%1$s',
	'Class:Cloud/ComplementaryName' => '%1$s-%2$s',
	'Class:Cloud' => 'Cloud',
	'Class:Cloud+' => 'Ein von einem Cloud-Anbieter betriebener virtueller Host. Er kann virtuelle Maschinen und Container-Hosts beherbergen.',
	'Class:Cloud/Attribute:logo' => 'Logo',
	'Class:Cloud/Attribute:logo+' => 'Wird als Objektsymbol verwendet, wenn diese Cloud in Impact-Analyse-Graphen dargestellt wird',
	'Class:Cloud/Attribute:provider_id+' => 'Anbieter der Cloud',
	'Class:Cloud/Attribute:location_id' => 'Standort',
	'Class:Cloud/Attribute:location_id+' => 'Standort der Cloud',
]);

//
// Class: LogicalInterface
//

Dict::Add('DE DE', 'German', 'Deutsch', [
	'Class:LogicalInterface/Attribute:org_id' => 'Organisation',
	'Class:LogicalInterface/Attribute:org_id+' => '',
	'Class:Cloud/Attribute:provider_id' => 'Provider',
	'Class:LogicalInterface/Name' => '%2$s %1$s',
]);
