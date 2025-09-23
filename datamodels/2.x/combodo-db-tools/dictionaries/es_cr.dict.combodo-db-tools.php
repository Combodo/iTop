<?php
/**
 * Spanish Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * @author Miguel Turrubiates <miguel_tf@yahoo.com>
 * @notas       Utilizar codificación UTF-8 para mostrar acentos y otros caracteres especiales 
 */
Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Menu:DBToolsMenu' => 'Integridad de Base de Datos',
	'DBTools:Class' => 'Clase',
	'DBTools:Title' => 'Herramientas de Mantenimiento de Base de Datos',
	'DBTools:ErrorsFound' => 'Errores encontrados',
	'DBTools:Indication' => 'Importante: después de corregir los errores en la base de datos, deberá ejecutar el análisis nuevamente ya que se generarán nuevas inconsistencias.',
	'DBTools:Disclaimer' => 'ADVERTENCIA: HAGA UNA COPIA DE SEGURIDAD DE SU BASE DE DATOS ANTES DE EJECUTAR LAS CORRECCIONES',
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
	'DBTools:SelectAnalysisType' => 'Seleccionar tipo de análisis',
	'DBTools:Analyze' => 'Analizar',
	'DBTools:Details' => 'Mostrar detalles',
	'DBTools:ShowAll' => 'Mostrar todos los errores',
	'DBTools:Inconsistencies' => 'Inconsistencias de Base de Datos',
	'DBTools:DetailedErrorTitle' => '%2$s error(es) en clase %1$s: %3$s',
	'DBTools:DetailedErrorLimit' => 'Lista limitada a %1$s errores',
	'DBAnalyzer-Integrity-OrphanRecord' => 'Registro huérfano en `%1$s`, debería tener su contraparte en la tabla `%2$s`',
	'DBAnalyzer-Integrity-InvalidExtKey' => 'Llave externa inválida %1$s (columna: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-MissingExtKey' => 'Llave externa perdida %1$s (columna: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-InvalidValue' => 'Valor inválido para %1$s (columna: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-UsersWithoutProfile' => 'Algunas cuentas de usuario no tienen perfil asignado',
	'DBAnalyzer-Integrity-HKInvalid' => 'Clave jerárquica rota `%1$s`',
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
