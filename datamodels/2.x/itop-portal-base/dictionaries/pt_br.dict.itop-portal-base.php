<?php
/**
 * Copyright (C) 2013-2023 Combodo SARL
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
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Page:DefaultTitle' => 'Portal do Usuário do '.ITOP_APPLICATION_SHORT,
	'Page:PleaseWait' => 'Aguarde...',
	'Page:Home' => 'Página inicial',
	'Page:GoPortalHome' => 'Página inicial',
	'Page:GoPreviousPage' => 'Página anterior',
	'Page:ReloadPage' => 'Atualizar página',
	'Portal:Button:Submit' => 'Enviar',
	'Portal:Button:Apply' => 'Alterar',
	'Portal:Button:Cancel' => 'Cancelar',
	'Portal:Button:Close' => 'Fechar',
	'Portal:Button:Add' => 'Adicionar',
	'Portal:Button:Remove' => 'Remover',
	'Portal:Button:Delete' => 'Deletar',
	'Portal:EnvironmentBanner:Title' => 'Você está atualmente em <strong>%1$s</strong>',
	'Portal:EnvironmentBanner:GoToProduction' => 'Volte para o modo PRODUÇÃO',
	'Error:HTTP:400' => 'Solicitação inválida',
	'Error:HTTP:401' => 'Autenticação',
	'Error:HTTP:404' => 'Está página não existe',
	'Error:HTTP:500' => 'Oops! Ocorreu um erro, informe a T.I.',
	'Error:HTTP:GetHelp' => 'Por favor, entre em contato com a T.I. para verificar este problema.',
	'Error:XHR:Fail' => 'Não foi possível carregar dados, entre em contato com o T.I.',
	'Portal:ErrorUserLoggedOut' => 'Você foi desconectado e precisa fazer o login novamente para continuar.',
	'Portal:Datatables:Language:Processing' => 'Aguarde...',
	'Portal:Datatables:Language:Search' => 'Filtro:',
	'Portal:Datatables:Language:LengthMenu' => 'Exibir _MENU_ item(ns) por página',
	'Portal:Datatables:Language:ZeroRecords' => 'Sem resultados',
	'Portal:Datatables:Language:Info' => 'Páginas: _PAGE_ of _PAGES_',
	'Portal:Datatables:Language:InfoEmpty' => 'Sem Informações',
	'Portal:Datatables:Language:InfoFiltered' => 'Filtrado de: _MAX_ item(ns)',
	'Portal:Datatables:Language:EmptyTable' => 'Não há dados disponíveis nesta tabela',
	'Portal:Datatables:Language:DisplayLength:All' => 'Todos',
	'Portal:Datatables:Language:Paginate:First' => 'Primeira',
	'Portal:Datatables:Language:Paginate:Previous' => 'Anterior',
	'Portal:Datatables:Language:Paginate:Next' => 'Próximo',
	'Portal:Datatables:Language:Paginate:Last' => 'Anterior',
	'Portal:Datatables:Language:Sort:Ascending' => 'Ordem ascendente',
	'Portal:Datatables:Language:Sort:Descending' => 'Ordem descendente',
	'Portal:Autocomplete:NoResult' => 'Sem dados',
	'Portal:Attachments:DropZone:Message' => 'Arraste seus arquivos aqui para adicioná-los como anexos',
	'Portal:File:None' => 'Nenhum arquivo',
	'Portal:File:DisplayInfo' => '<a href="%2$s" class="file_download_link">%1$s</a>',
	'Portal:File:DisplayInfo+' => '%1$s (%2$s) <a href="%3$s" class="file_open_link" target="_blank">Abrir</a> / <a href="%4$s" class="file_download_link">Download</a>',
	'Portal:Calendar-FirstDayOfWeek' => 'pt-br', //work with moment.js locales
));

// Object form
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Portal:Form:Caselog:Entry:Close:Tooltip' => 'Fechar esta solicitação',
	'Portal:Form:Close:Warning' => 'Você deseja abandonar esta página? Os dados digitados podem ser perdidos',
	'Portal:Error:ObjectCannotBeCreated' => 'Erro: objeto não pode ser criado. Verifique os objetos e anexos associados antes de enviar novamente este formulário',
	'Portal:Error:ObjectCannotBeUpdated' => 'Erro: objeto não pode ser atualizado. Verifique os objetos e anexos associados antes de enviar novamente este formulário',
));

// UserProfile brick
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Brick:Portal:UserProfile:Name' => 'Perfil de Usuário',
	'Brick:Portal:UserProfile:Navigation:Dropdown:MyProfil' => 'Meu Perfil',
	'Brick:Portal:UserProfile:Navigation:Dropdown:Logout' => 'Sair',
	'Brick:Portal:UserProfile:Password:Title' => 'Senha',
	'Brick:Portal:UserProfile:Password:ChoosePassword' => 'Nova senha',
	'Brick:Portal:UserProfile:Password:ConfirmPassword' => 'Repetir nova senha',
	'Brick:Portal:UserProfile:Password:CantChangeContactAdministrator' => 'Para alterar sua senha, entre em contato com a T.I.',
	'Brick:Portal:UserProfile:Password:CantChangeForUnknownReason' => 'Não foi possível alterar sua senha, entre em contato com a T.I.',
	'Brick:Portal:UserProfile:PersonalInformations:Title' => 'Informações pessoais',
	'Brick:Portal:UserProfile:Photo:Title' => 'Imagem',
));

// AggregatePageBrick
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Brick:Portal:AggregatePage:DefaultTitle' => 'Painel do '.ITOP_APPLICATION_SHORT,
));

// BrowseBrick brick
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Brick:Portal:Browse:Name' => 'Navegar por itens',
	'Brick:Portal:Browse:Mode:List' => 'Lista',
	'Brick:Portal:Browse:Mode:Tree' => 'Cascata',
	'Brick:Portal:Browse:Mode:Mosaic' => 'Mosaico',
	'Brick:Portal:Browse:Action:Drilldown' => 'Detalhamento',
	'Brick:Portal:Browse:Action:View' => 'Detalhes',
	'Brick:Portal:Browse:Action:Edit' => 'Editar',
	'Brick:Portal:Browse:Action:Create' => 'Criar',
	'Brick:Portal:Browse:Action:CreateObjectFromThis' => 'Novo %1$s',
	'Brick:Portal:Browse:Tree:ExpandAll' => 'Expandir todos',
	'Brick:Portal:Browse:Tree:CollapseAll' => 'Recolher todos',
	'Brick:Portal:Browse:Filter:NoData' => 'Sem dados',
));

// ManageBrick brick
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Brick:Portal:Manage:Name' => 'Gerenciar itens',
	'Brick:Portal:Manage:Table:NoData' => 'Sem dados',
	'Brick:Portal:Manage:Table:ItemActions' => 'Ações',
	'Brick:Portal:Manage:DisplayMode:list' => 'List',
	'Brick:Portal:Manage:DisplayMode:pie-chart' => 'Gráfico de Pizza',
	'Brick:Portal:Manage:DisplayMode:bar-chart' => 'Gráfico de Barras',
	'Brick:Portal:Manage:Others' => 'Outros',
	'Brick:Portal:Manage:All' => 'Todos',
	'Brick:Portal:Manage:Group' => 'Grupo',
	'Brick:Portal:Manage:fct:count' => 'Total',
	'Brick:Portal:Manage:fct:sum' => 'Soma',
	'Brick:Portal:Manage:fct:avg' => 'Média',
	'Brick:Portal:Manage:fct:min' => 'Min.',
	'Brick:Portal:Manage:fct:max' => 'Máx.',
));

// ObjectBrick brick
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Brick:Portal:Object:Name' => 'Objeto',
	'Brick:Portal:Object:Form:Create:Title' => 'Novo %1$s',
	'Brick:Portal:Object:Form:Edit:Title' => 'Alterar %2$s (%1$s)',
	'Brick:Portal:Object:Form:View:Title' => '%1$s : %2$s',
	'Brick:Portal:Object:Form:Stimulus:Title' => 'Por favor, preencha as seguintes informações:',
	'Brick:Portal:Object:Form:Message:Saved' => 'Salvo',
	'Brick:Portal:Object:Form:Message:ObjectSaved' => '%1$s salvo',
	'Brick:Portal:Object:Search:Regular:Title' => 'Selecionar %1$s (%2$s)',
	'Brick:Portal:Object:Search:Hierarchy:Title' => 'Selecinar %1$s (%2$s)',
	'Brick:Portal:Object:Copy:TextToCopy' => '%2$s',
	'Brick:Portal:Object:Copy:Tooltip' => 'Copiar',
	'Brick:Portal:Object:Copy:CopiedTooltip' => 'Copiado'
));

// CreateBrick brick
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Brick:Portal:Create:Name' => 'Criação rápida',
	'Brick:Portal:Create:ChooseType' => 'Por favor, escolha um tipo:',
));

// Filter brick
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Brick:Portal:Filter:Name' => 'Filtro ativado',
	'Brick:Portal:Filter:SearchInput:Placeholder' => 'ex. conectar ao WiFi',
	'Brick:Portal:Filter:SearchInput:Submit' => 'Pesquisa',
));
