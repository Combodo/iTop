<?php
/**
 * Spanish localized data
 *
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 * @traductor   Miguel Turrubiates <miguel_tf@yahoo.com> 
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
Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	// Dictionary entries go here
	'Menu:DBToolsMenu' => 'Integridad de Base de Datos',
	'DBTools:Class' => 'Clase',
	'DBTools:Title' => 'Herramientas de Mantenimiento de Base de Datos',
	'DBTools:ErrorsFound' => 'Errores encontrados',
	'DBTools:Indication' => 'Important: after fixing errors in the database you\'ll have to run the analysis again as new inconsistencies will be generated~~',
	'DBTools:Disclaimer' => 'DISCLAIMER: BACKUP YOUR DATABASE BEFORE RUNNING THE FIXES~~',
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
	'DBTools:FetchCheck' => 'Verificación de búsqueda (larga)',
	'DBTools:SelectAnalysisType' => 'Select analysis type~~',

	'DBTools:Analyze' => 'Analizar',
	'DBTools:Details' => 'Mostrar detalles',
	'DBTools:ShowAll' => 'Mostrar todos los errores',

	'DBTools:Inconsistencies' => 'Inconsistencias de Base de Datos',
	'DBTools:DetailedErrorTitle' => '%2$s error(s) in class %1$s: %3$s~~',

	'DBAnalyzer-Integrity-OrphanRecord' => 'Registro huérfano en `%1$s`, debería tener su contraparte en la tabla `%2$s`',
	'DBAnalyzer-Integrity-InvalidExtKey' => 'Llave externa inválida %1$s (columna: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-MissingExtKey' => 'Llave externa perdida %1$s (columna: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-InvalidValue' => 'Valor inválido para %1$s (columna: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-UsersWithoutProfile' => 'Algunas cuentas de usuario no tienen perfil asignado',
	'DBAnalyzer-Fetch-Count-Error' => 'Obtener cuenta de errores en `%1$s`, %2$d entradas recuperadas / %3$d contadas',
	'DBAnalyzer-Integrity-FinalClass' => 'Campo `%2$s`.`%1$s` debe tener los mismos valores que `%3$s`.`%1$s`',
	'DBAnalyzer-Integrity-RootFinalClass' => 'Campo `%2$s`.`%1$s` debe contener un caracter válido',
));

// Database Info
Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'DBTools:DatabaseInfo' => 'Información de Base de Datos',
	'DBTools:Base' => 'Base',
	'DBTools:Size' => 'Tamaño',
));

// Lost attachments
Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'DBTools:LostAttachments' => 'Adjuntos perdidos',
	'DBTools:LostAttachments:Disclaimer' => 'Aquí usted puede buscar anexos perdidos o fuera de lugar. Esta NO es una herramienta de recuperación de datos, no obtiene datos borrados.',

	'DBTools:LostAttachments:Button:Analyze' => 'Analizar',
	'DBTools:LostAttachments:Button:Restore' => 'Restaurar',
	'DBTools:LostAttachments:Button:Restore:Confirm' => 'Esta acción no se puede deshacer, por favor confirme que quiere restaurar los archivos seleccionados.',
	'DBTools:LostAttachments:Button:Busy' => 'Por favor espere...',

	'DBTools:LostAttachments:Step:Analyze' => 'Primero, buscar anexos perdidos o fuera de lugar analizando la base de datos.',

	'DBTools:LostAttachments:Step:AnalyzeResults' => 'Analizar resultados:',
	'DBTools:LostAttachments:Step:AnalyzeResults:None' => '¡Genial! Todo parece estar en el lugar correcto.',
	'DBTools:LostAttachments:Step:AnalyzeResults:Some' => 'Algunos adjuntos (%1$d) parecen estar desplazados. Mire la siguiente lista y verifique los que quiera mover.',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:Filename' => 'Nombre de archivo',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:CurrentLocation' => 'Ubicación actual',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:TargetLocation' => 'Mover a...',

	'DBTools:LostAttachments:Step:RestoreResults' => 'Resultados de restauración:',
	'DBTools:LostAttachments:Step:RestoreResults:Results' => '%1$d/%2$d adjuntos fueron restaurados.',

	'DBTools:LostAttachments:StoredAsInlineImage' => 'Almacenado como imagen en línea',
	'DBTools:LostAttachments:History' => 'Adjunto "%1$s" restaurado con herramientas de base de datos'
));
