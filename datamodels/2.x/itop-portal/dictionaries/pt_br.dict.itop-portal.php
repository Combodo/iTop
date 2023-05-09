<?php
// Copyright (C) 2010-2023 Combodo SARL
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
 * @author      Benjamin Planque <benjamin.planque@combodo.com>
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
//////////////////////////////////////////////////////////////////////
// Note: The classes have been grouped by categories: bizmodel
//////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////
// Classes in 'bizmodel'
//////////////////////////////////////////////////////////////////////
//
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'portal:itop-portal' => 'Portal do Usuário', // This is the portal name that will be displayed in portal dispatcher (eg. URL in menus)
	'Page:DefaultTitle' => ITOP_APPLICATION_SHORT.' - Portal do Usuário',
	'Brick:Portal:UserProfile:Title' => 'Meu perfil',
	'Brick:Portal:NewRequest:Title' => 'Nova solicitação',
	'Brick:Portal:NewRequest:Title+' => '<p>Precisa de ajuda?</p><p>Escolha no Catálogo de Serviços e envie sua solicitação para nossas equipes de suporte.</p>',
	'Brick:Portal:OngoingRequests:Title' => 'Solicitações abertas',
	'Brick:Portal:OngoingRequests:Title+' => '<p>Acompanhar suas solicitações em andamento, adicionar comentários, anexar documentos e aceitar a solução.</p>',
	'Brick:Portal:OngoingRequests:Tab:OnGoing' => 'Em andamento',
	'Brick:Portal:OngoingRequests:Tab:Resolved' => 'Resolvidas',
	'Brick:Portal:ClosedRequests:Title' => 'Solicitações fechadas',
));
