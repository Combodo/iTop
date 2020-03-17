<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2018 Combodo SARL
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
// Database inconsistencies
Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
	// Dictionary entries go here
	'Menu:DBToolsMenu' => 'Herramientas de bases de datos',
	'DBTools:Class' => 'Clase',
	'DBTools:Title' => 'Herramientas de mantenimiento de base de datos',
	'DBTools:ErrorsFound' => 'Errores encontrados',
	'DBTools:Error' => 'Error',
	'DBTools:Count' => 'Cantidad',
	'DBTools:SQLquery' => 'Consulta SQL',
	'DBTools:FixitSQLquery' => 'Consulta SQL para solucioner el problema (sugerencia)',
	'DBTools:SQLresult' => 'Resultado SQL',
	'DBTools:NoError' => 'La base de datos está correcta',
	'DBTools:HideIds' => 'Lista de errores',
	'DBTools:ShowIds' => 'Vista detallada',
	'DBTools:ShowReport' => 'Reporte',
	'DBTools:IntegrityCheck' => 'Verificación de integridad',
	'DBTools:FetchCheck' => 'Fetch Check (long)~~',

	'DBTools:Analyze' => 'Analizar',
	'DBTools:Details' => 'Mostrar detalles',
	'DBTools:ShowAll' => 'Mostrar todos los errores',

	'DBTools:Inconsistencies' => 'Inconsistencias de base de datos',

	'DBAnalyzer-Integrity-OrphanRecord' => 'Registro huérfano en `%1$s`, debería tener su contraparte en la tabla `%2$s`',
	'DBAnalyzer-Integrity-InvalidExtKey' => 'Llave externa inválida %1$s (columna: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-MissingExtKey' => 'Llave externa perdida %1$s (columna: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-InvalidValue' => 'Valor inválido para %1$s (columna: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-UsersWithoutProfile' => 'Alunas cuentas de usuario no tienen perfil asignado',
	'DBAnalyzer-Fetch-Count-Error' => 'Fetch count error in `%1$s`, %2$d entries fetched / %3$d counted~~',
));

// Database Info
Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
	'DBTools:DatabaseInfo' => 'Información de base de datos',
	'DBTools:Base' => 'Base',
	'DBTools:Size' => 'Tamaño',
));

// Lost attachments
Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
	'DBTools:LostAttachments' => 'Adjuntos perdidos',
	'DBTools:LostAttachments:Disclaimer' => 'Aquí usted puede buscar adjuntos perdidos o desplazados. Esta NO es una herramienta de recuperación de datos, no obtiene datos borrados.',

	'DBTools:LostAttachments:Button:Analyze' => 'Analizar',
	'DBTools:LostAttachments:Button:Restore' => 'Restaurar',
	'DBTools:LostAttachments:Button:Restore:Confirm' => 'Esta acción no se puede deshacer, por favor confirme que quiere restaurar los archivos seleccionados.',
	'DBTools:LostAttachments:Button:Busy' => 'Por favor espera...',

	'DBTools:LostAttachments:Step:Analyze' => 'First, search for lost/misplaced attachments by analyzing the database.~~',

	'DBTools:LostAttachments:Step:AnalyzeResults' => 'Analyze results:~~',
	'DBTools:LostAttachments:Step:AnalyzeResults:None' => 'Great! Every thing seems to be at the right place.~~',
	'DBTools:LostAttachments:Step:AnalyzeResults:Some' => 'Some attachments (%1$d) seem to be misplaced. Take a look at the following list and check the ones you would like to move.~~',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:Filename' => 'Filename~~',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:CurrentLocation' => 'Current location~~',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:TargetLocation' => 'Move to...~~',

	'DBTools:LostAttachments:Step:RestoreResults' => 'Restore results:~~',
	'DBTools:LostAttachments:Step:RestoreResults:Results' => '%1$d/%2$d attachments were restored.~~',

	'DBTools:LostAttachments:StoredAsInlineImage' => 'Stored as inline image~~',
	'DBTools:LostAttachments:History' => 'Attachment "%1$s" restored with DB tools~~'
));
