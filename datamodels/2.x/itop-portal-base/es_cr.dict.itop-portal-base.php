<?php

// Copyright (C) 2010-2015 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>

/**
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license	 http://opensource.org/licenses/AGPL-3.0
 */


// Portal
Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Page:DefaultTitle' => 'iTop User portal',
	'Page:PleaseWait' => 'Please wait...',
	'Page:Home' => 'Bienvenido',
	'Page:GoPortalHome' => 'Regresar a bienvenida',
	'Page:GoPreviousPage' => 'página anterior',
	'Portal:Button:Submit' => 'Enviar',
	'Portal:Button:Cancel' => 'Cancelar',
	'Portal:Button:Close' => 'Cerrar',
	'Portal:Button:Add' => 'Añadir',
	'Portal:Button:Remove' => 'Eliminar',
	'Portal:Button:Delete' => 'Borrar',
	'Error:HTTP:404' => 'Página no encontrada',
	'Error:HTTP:500' => '¡Vaya! Ha ocurrido un error.',
	'Error:HTTP:GetHelp' => 'Póngase en contacto con el administrador de iTop si el problema persiste.',
	'Error:XHR:Fail' => 'No se pudieron cargar datos, póngase en contacto con su administrador de iTop',
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
	'Portal:File:None' => 'No file',
	'Portal:File:DisplayInfo' => '<a href="%2$s" class="file_download_link">%1$s</a>',
	'Portal:File:DisplayInfo+' => '%1$s (%2$s) <a href="%3$s" class="file_open_link" target="_blank">Open</a> / <a href="%4$s" class="file_download_link">Download</a>',
));

// UserProfile brick
Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Brick:Portal:UserProfile:Name' => 'Perfil del usuario',
	'Brick:Portal:UserProfile:Navigation:Dropdown:MyProfil' => 'Mi perfil',
	'Brick:Portal:UserProfile:Navigation:Dropdown:Logout' => 'Desconectarse',
	'Brick:Portal:UserProfile:Password:Title' => 'Contraseña',
	'Brick:Portal:UserProfile:Password:ChoosePassword' => 'Elegir una contraseña',
	'Brick:Portal:UserProfile:Password:ConfirmPassword' => 'Confirmar contraseña',
	'Brick:Portal:UserProfile:Password:CantChangeContactAdministrator' => 'Para cambiar su contraseña, póngase en contacto con su administrador de iTop',
	'Brick:Portal:UserProfile:Password:CantChangeForUnknownReason' => 'No se puede cambiar la contraseña, póngase en contacto con el administrador de iTop',
	'Brick:Portal:UserProfile:PersonalInformations:Title' => 'Informaciones personales',
	'Brick:Portal:UserProfile:Photo:Title' => 'Foto',
));

// BrowseBrick brick
Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Brick:Portal:Browse:Name' => 'Buscar en todos los elementos',
	'Brick:Portal:Browse:Mode:List' => 'Lista',
	'Brick:Portal:Browse:Mode:Tree' => 'Árbol',
	'Brick:Portal:Browse:Action:Drilldown' => 'Desglose',
	'Brick:Portal:Browse:Action:View' => 'Detalles',
	'Brick:Portal:Browse:Action:Edit' => 'Editar',
	'Brick:Portal:Browse:Action:Create' => 'Crear',
	'Brick:Portal:Browse:Action:CreateObjectFromThis' => 'Nuevo %1$s',
	'Brick:Portal:Browse:Tree:ExpandAll' => 'Expandir todo',
	'Brick:Portal:Browse:Tree:CollapseAll' => 'Desplegar todo',
	'Brick:Portal:Browse:Filter:NoData' => 'Sin objeto',
));

// ManageBrick brick
Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Brick:Portal:Manage:Name' => 'Administrar elementos',
	'Brick:Portal:Manage:Table:NoData' => 'Sin objeto.',
));

// ObjectBrick brick
Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Brick:Portal:Object:Name' => 'Object',
	'Brick:Portal:Object:Form:Create:Title' => 'New %1$s',
	'Brick:Portal:Object:Form:Edit:Title' => 'Updating %2$s (%1$s)',
	'Brick:Portal:Object:Form:View:Title' => '%1$s : %2$s',
	'Brick:Portal:Object:Form:Stimulus:Title' => 'Please, fill the following informations:',
	'Brick:Portal:Object:Form:Message:Saved' => 'Saved',
	'Brick:Portal:Object:Search:Regular:Title' => 'Select %1$s (%2$s)',
	'Brick:Portal:Object:Search:Hierarchy:Title' => 'Select %1$s (%2$s)',
));

// CreateBrick brick
Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Brick:Portal:Create:Name' => 'Creación rápida',
));
?>
