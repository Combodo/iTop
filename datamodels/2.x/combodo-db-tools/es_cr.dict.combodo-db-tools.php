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
	'DBAnalyzer-Integrity-UsersWithoutProfile' => 'Algunas cuentas de usuario no tienen perfil asignado',
	'DBAnalyzer-Fetch-Count-Error' => 'Fetch count error in `%1$s`, %2$d entries fetched / %3$d counted~~',
	'DBAnalyzer-Integrity-FinalClass' => 'Field `%2$s`.`%1$s` must have the same value as `%3$s`.`%1$s`~~',
	'DBAnalyzer-Integrity-RootFinalClass' => 'Field `%2$s`.`%1$s` must contains a valid class~~',
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
	'DBTools:LostAttachments:Button:Busy' => 'Por favor espere...',

	'DBTools:LostAttachments:Step:Analyze' => 'Primero, buscaremos adjuntos perdidos/desplazados analizando la base de datos.',

	'DBTools:LostAttachments:Step:AnalyzeResults' => 'Analizar resultados:',
	'DBTools:LostAttachments:Step:AnalyzeResults:None' => 'Genial! Todo parece estar en el lugar correcto.',
	'DBTools:LostAttachments:Step:AnalyzeResults:Some' => 'Algunos adjuntos (%1$d) parecen estar desplazados. Mire la siguiente lista y verifique los que quiera mover.',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:Filename' => 'Nombre de archivo',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:CurrentLocation' => 'Ubicación actual',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:TargetLocation' => 'Mover a...',

	'DBTools:LostAttachments:Step:RestoreResults' => 'Resultados de restauración:',
	'DBTools:LostAttachments:Step:RestoreResults:Results' => '%1$d/%2$d adjuntos fueron restaurados.',

	'DBTools:LostAttachments:StoredAsInlineImage' => 'Almacenado como imagen en línea',
	'DBTools:LostAttachments:History' => 'Adjunto "%1$s" restaurado con herramientas de base de datos'
));
