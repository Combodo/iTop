<?php
/**
 * Copyright (C) 2013-2024 Combodo SAS
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
 */

Dict::Add('EN US', 'English', 'English', array(

	// Placeholders
	// $%1s : host object class name
	// $%2s : host object friendlyname
	// $%3s : current tab name
	// $%4s : remote object class name
	// $%5s : remote object friendlyname

	'UI:Links:Object:New:Modal:Title'               => 'Create an object',

	// Create
	'UI:Links:Create:Button'                        => 'Create',
	'UI:Links:Create:Button+'                       => 'Create a %4$s',
	'UI:Links:Create:Modal:Title'                   => 'Create a %4$s in %2$s',

	// Add
	'UI:Links:Add:Button'                           => 'Add',
	'UI:Links:Add:Button+'                          => 'Add a %4$s',
	'UI:Links:Add:Modal:Title'                      => 'Add a %4$s to %2$s',

	// Modify link
	'UI:Links:ModifyLink:Button'                    => 'Modify',
	'UI:Links:ModifyLink:Button+'                   => 'Modify this link',
	'UI:Links:ModifyLink:Modal:Title'               => 'Modify the link between %2$s and %5$s',

	// Modify object
	'UI:Links:ModifyObject:Button'                  => 'Modify',
	'UI:Links:ModifyObject:Button+'                 => 'Modify this object',
	'UI:Links:ModifyObject:Modal:Title'             => '%5$s',

	// Remove
	'UI:Links:Remove:Button'                        => 'Remove',
	'UI:Links:Remove:Button+'                       => 'Remove this %4$s',
	'UI:Links:Remove:Modal:Title'                   => 'Remove a %4$s from its %1$s',
	'UI:Links:Remove:Modal:Message'                 => 'Do you really want to remove %5$s from %2$s?',

	// Delete
	'UI:Links:Delete:Button'                        => 'Delete',
	'UI:Links:Delete:Button+'                       => 'Delete this %4$s',
	'UI:Links:Delete:Modal:Title'                   => 'Delete a %4$s',
	'UI:Links:Delete:Modal:Message'                 => 'Do you really want to delete %5$s?',

	// Bulk
	'UI:Links:Bulk:LinkWillBeCreatedForAllObjects'  => 'Add to all objects',
	'UI:Links:Bulk:LinkWillBeDeletedFromAllObjects' => 'Remove from all objects',
	'UI:Links:Bulk:LinkWillBeCreatedFor1Object'     => 'Add to one object',
	'UI:Links:Bulk:LinkWillBeDeletedFrom1Object'    => 'Remove from one object',
	'UI:Links:Bulk:LinkWillBeCreatedForXObjects'    => 'Add {count} objects',
	'UI:Links:Bulk:LinkWillBeDeletedFromXObjects'   => 'Remove {count} objects',
	'UI:Links:Bulk:LinkExistForAllObjects'          => 'All objets are already linked',
	'UI:Links:Bulk:LinkExistForOneObject'           => 'One object is linked',
	'UI:Links:Bulk:LinkExistForXObjects'            => '{count} objects are linked',

	// New item
	'UI:Links:NewItem' => 'New item',
));