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

Dict::Add('FR FR', 'French', 'Français', [
	'Virtualization:baseinfo' => 'Informations générales',
	'Virtualization:moreinfo' => 'Spécificités de la virtualisation',
	'Virtualization:otherinfo' => 'Dates et description',
]);

//
// Class Cloud
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:Cloud/Name' => '%1$s',
	'Class:Cloud/ComplementaryName' => '%1$s-%2$s',
	'Class:Cloud' => 'Nuage',
	'Class:Cloud+' => 'Hôte virtuel, opéré par un fournisseur de services Cloud, il peut héberger des Machines Virtuelles, des Hôtes pour Conteneurs, etc.',
	'Class:Cloud/Attribute:provider_id' => 'Fournisseur',
	'Class:Cloud/Attribute:provider_id+' => 'Organisation fournissant le nuage',
	'Class:Cloud/Attribute:location_id' => 'Site',
	'Class:Cloud/Attribute:location_id+' => 'Site du fournisseur, hébergeant le nuage',
	'Class:Cloud/Attribute:containerhosts_list' => 'Hôtes pour conteneurs',
	'Class:Cloud/Attribute:containerhosts_list+' => 'Liste des hôtes hébergés dans ce nuage',
]);

//
// Class: LogicalInterface
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:LogicalInterface/Attribute:org_id' => 'Organisation',
	'Class:LogicalInterface/Attribute:org_id+' => '',
]);
