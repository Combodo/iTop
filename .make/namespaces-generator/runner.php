<?php
/**
 * Copyright (C) 2010-2020 Combodo SARL
 *
 *   This file is part of iTop.
 *
 *   iTop is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU Affero General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   iTop is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU Affero General Public License for more details.
 *
 *   You should have received a copy of the GNU Affero General Public License
 *   along with iTop. If not, see <http: *www.gnu.org/licenses/>
 *
 */

$iTopFolder = __DIR__ . "/../../" ;

require_once ("$iTopFolder/approot.inc.php");

require_once 'NodeVisitorClassExtractor.php';
require_once 'NamespaceGenerator.php';

$sWriteToPath = APPROOT.'/psr4-compat';
@mkdir($sWriteToPath, 0775, true);

$aScannedDirs = array(
	'addons',
	'application',
	'core' => array(
		'exclude' => array('oql'),
		'namespaces' => array(
			'apc-.*' => '\\Combodo\\iTop\\Core\\Cache\\Apc',
			'attributedef.class.inc.php' => '\\Combodo\\iTop\\Core\\AttributeDefinition',
			'db.*' => '\\Combodo\\iTop\\Core\\Orm\\DbObject',
			'oql.*' => '\\Combodo\\iTop\\Core\\Orm\\Oql',
		),
	),
	'pages',
	'setup',
	'sources',
	'synchro',
	'webservices',
);

$oNamespaceGenerator= new NamespaceGenerator($aScannedDirs, $sWriteToPath);
$oNamespaceGenerator->run();