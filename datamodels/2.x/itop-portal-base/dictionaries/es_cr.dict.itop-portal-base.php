<?php
/**
 * Copyright (C) 2013-2020 Combodo SARL
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
// Portal
Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
	'Page:DefaultTitle' => '%1$s - Portal de Usuario',
	'Page:PleaseWait' => 'Por favor espere...',
	'Page:Home' => 'Inicio',
	'Page:GoPortalHome' => 'Regresar a Inicio',
	'Page:GoPreviousPage' => 'Página anterior',
	'Page:ReloadPage' => 'Recargar página',
	'Portal:Button:Submit' => 'Enviar',
	'Portal:Button:Apply' => 'Actualizar',
	'Portal:Button:Cancel' => 'Cancelar',
	'Portal:Button:Close' => 'Cerrar',
	'Portal:Button:Add' => 'Añadir',
	'Portal:Button:Remove' => 'Eliminar',
	'Portal:Button:Delete' => 'Borrar',
	'Portal:EnvironmentBanner:Title' => 'Se encuentra en modo <strong>%1$s</strong>',
	'Portal:EnvironmentBanner:GoToProduction' => 'Regresar a modo PRODUCTION',
	'Error:HTTP:400' => 'Bad request~~',
	'Error:HTTP:401' => 'Autenticación',
	'Error:HTTP:404' => 'Página no encontrada',
	'Error:HTTP:500' => '¡Vaya! Ha ocurrido un error.',
	'Error:HTTP:GetHelp' => 'Póngase en contacto con el administrador de %1$s si el problema persiste.',
	'Error:XHR:Fail' => 'No se pudieron cargar datos, póngase en contacto con su administrador de %1$s',
	'Portal:ErrorUserLoggedOut' => 'Se encuentra desconectado y necesita volver a identificarse para continuar.',
	'Portal:Datatables:Language:Processing' => 'Por favor esperar...',
	'Portal:Datatables:Language:Search' => 'Filtrar:',
	'Portal:Datatables:Language:LengthMenu' => 'Mostrar _MENU_ elementos por página',
	'Portal:Datatables:Language:ZeroRecords' => 'Sin resultados',
	'Portal:Datatables:Language:Info' => 'Página _PAGE_ de _PAGES_',
	'Portal:Datatables:Language:InfoEmpty' => 'Sin información',
	'Portal:Datatables:Language:InfoFiltered' => 'Filtrada de _MAX_ elementos',
	'Portal:Datatables:Language:EmptyTable' => 'No hay datos disponibles en esta tabla',
	'Portal:Datatables:Language:DisplayLength:All' => 'Todas',
	'Portal:Datatables:Language:Paginate:First' => 'primero',
	'Portal:Datatables:Language:Paginate:Previous' => 'Anterior',
	'Portal:Datatables:Language:Paginate:Next' => 'Siguiente',
	'Portal:Datatables:Language:Paginate:Last' => 'Último',
	'Portal:Datatables:Language:Sort:Ascending' => 'Habilitar para un orden ascendente',
	'Portal:Datatables:Language:Sort:Descending' => 'Habilitar para un tipo descendente',
	'Portal:Autocomplete:NoResult' => 'Sin datos',
	'Portal:Attachments:DropZone:Message' => 'Agrega tus archivos para agregarlos como documentos adjuntos',
	'Portal:File:None' => 'No hay archivo',
	'Portal:File:DisplayInfo' => '<a href="%2$s" class="file_download_link">%1$s</a>',
	'Portal:File:DisplayInfo+' => '%1$s (%2$s) <a href="%3$s" class="file_open_link" target="_blank">Abierto</a> / <a href="%4$s" class="file_download_link">Download</a>',
	'Portal:Calendar-FirstDayOfWeek' => 'es', //work with moment.js locales
	'Portal:Form:Close:Warning' => 'Do you want to leave this form ? Data entered may be lost~~',
));

// UserProfile brick
Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
	'Brick:Portal:UserProfile:Name' => 'Perfil del usuario',
	'Brick:Portal:UserProfile:Navigation:Dropdown:MyProfil' => 'Mi perfil',
	'Brick:Portal:UserProfile:Navigation:Dropdown:Logout' => 'Cerrar Sesión',
	'Brick:Portal:UserProfile:Password:Title' => 'Contraseña',
	'Brick:Portal:UserProfile:Password:ChoosePassword' => 'Elegir una contraseña',
	'Brick:Portal:UserProfile:Password:ConfirmPassword' => 'Confirmar contraseña',
	'Brick:Portal:UserProfile:Password:CantChangeContactAdministrator' => 'Para cambiar su contraseña, póngase en contacto con su administrador de %1$s',
	'Brick:Portal:UserProfile:Password:CantChangeForUnknownReason' => 'No se puede cambiar la contraseña, póngase en contacto con el administrador de %1$s',
	'Brick:Portal:UserProfile:PersonalInformations:Title' => 'Información Personal',
	'Brick:Portal:UserProfile:Photo:Title' => 'Foto',
));

// AggregatePageBrick
Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
	'Brick:Portal:AggregatePage:DefaultTitle' => 'Tablero de Control',
));

// BrowseBrick brick
Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
	'Brick:Portal:Browse:Name' => 'Buscar en todos los elementos',
	'Brick:Portal:Browse:Mode:List' => 'Lista',
	'Brick:Portal:Browse:Mode:Tree' => 'Árbol',
	'Brick:Portal:Browse:Mode:Mosaic' => 'Mosaico',
	'Brick:Portal:Browse:Action:Drilldown' => 'Desglose',
	'Brick:Portal:Browse:Action:View' => 'Detalles',
	'Brick:Portal:Browse:Action:Edit' => 'Editar',
	'Brick:Portal:Browse:Action:Create' => 'Crear',
	'Brick:Portal:Browse:Action:CreateObjectFromThis' => 'Nuevo %1$s',
	'Brick:Portal:Browse:Tree:ExpandAll' => 'Expandir todo',
	'Brick:Portal:Browse:Tree:CollapseAll' => 'Desplegar todo',
	'Brick:Portal:Browse:Filter:NoData' => 'Sin elementos',
));

// ManageBrick brick
Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
	'Brick:Portal:Manage:Name' => 'Administrar elementos',
	'Brick:Portal:Manage:Table:NoData' => 'Sin elementos',
	'Brick:Portal:Manage:Table:ItemActions' => 'Acciones',
	'Brick:Portal:Manage:DisplayMode:list' => 'Lista',
	'Brick:Portal:Manage:DisplayMode:pie-chart' => 'Gráfica de Pastel',
	'Brick:Portal:Manage:DisplayMode:bar-chart' => 'Gráfica de Barra',
	'Brick:Portal:Manage:Others' => 'Otros',
	'Brick:Portal:Manage:All' => 'Todos',
	'Brick:Portal:Manage:Group' => 'Grupo',
	'Brick:Portal:Manage:fct:count' => 'Total',
	'Brick:Portal:Manage:fct:sum' => 'Suma',
	'Brick:Portal:Manage:fct:avg' => 'Promedio',
	'Brick:Portal:Manage:fct:min' => 'Mínimo',
	'Brick:Portal:Manage:fct:max' => 'Máximo',
));

// ObjectBrick brick
Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
	'Brick:Portal:Object:Name' => 'Objecto',
	'Brick:Portal:Object:Form:Create:Title' => 'Nuevo %1$s',
	'Brick:Portal:Object:Form:Edit:Title' => 'Actualizando %2$s (%1$s)',
	'Brick:Portal:Object:Form:View:Title' => '%1$s : %2$s',
	'Brick:Portal:Object:Form:Stimulus:Title' => 'Por favor, proporcione la siguiente información:',
	'Brick:Portal:Object:Form:Message:Saved' => 'Guardado',
	'Brick:Portal:Object:Form:Message:ObjectSaved' => '%1$s guardado~~',
	'Brick:Portal:Object:Search:Regular:Title' => 'Selección %1$s (%2$s)',
	'Brick:Portal:Object:Search:Hierarchy:Title' => 'Selección %1$s (%2$s)',
	'Brick:Portal:Object:Copy:TextToCopy' => '%1$s: %2$s~~',
	'Brick:Portal:Object:Copy:Tooltip' => 'Copy object link~~',
	'Brick:Portal:Object:Copy:CopiedTooltip' => 'Copied~~'
));

// CreateBrick brick
Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
	'Brick:Portal:Create:Name' => 'Creación rápida',
	'Brick:Portal:Create:ChooseType' => 'Por favor, seleccione un tipo',
));

// Filter brick
Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
	'Brick:Portal:Filter:Name' => 'Prefiltre un bloquek',
	'Brick:Portal:Filter:SearchInput:Placeholder' => 'Ej.:. Conectar a WiFi',
	'Brick:Portal:Filter:SearchInput:Submit' => 'Buscar',
));
