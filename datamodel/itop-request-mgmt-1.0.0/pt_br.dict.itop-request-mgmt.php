<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * @author	Erwan Taloc <erwan.taloc@combodo.com>
 * @author	Romain Quetiez <romain.quetiez@combodo.com>
 * @author	Denis Flaven <denis.flaven@combodo.com>
 * @author	Marco Tulio <mtulio@opensolucoes.com.br>

 * @licence	http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:UserRequest' => 'Usuário solicitante',
	'Class:UserRequest+' => '',
	'Class:UserRequest/Attribute:request_type' => 'Tipo solicitação',
	'Class:UserRequest/Attribute:request_type+' => '',
	'Class:UserRequest/Attribute:request_type/Value:information' => 'Informação',
	'Class:UserRequest/Attribute:request_type/Value:information+' => '',
	'Class:UserRequest/Attribute:request_type/Value:issue' => 'Falha',
	'Class:UserRequest/Attribute:request_type/Value:issue+' => '',
	'Class:UserRequest/Attribute:request_type/Value:service request' => 'Solicitação serviço',
	'Class:UserRequest/Attribute:request_type/Value:service request+' => '',
	'Class:UserRequest/Attribute:freeze_reason' => 'Razão pendência',
	'Class:UserRequest/Attribute:freeze_reason+' => '',
	'Class:UserRequest/Stimulus:ev_assign' => 'Atribuír',
	'Class:UserRequest/Stimulus:ev_assign+' => '',
	'Class:UserRequest/Stimulus:ev_reassign' => 'Re-atribuír',
	'Class:UserRequest/Stimulus:ev_reassign+' => '',
	'Class:UserRequest/Stimulus:ev_timeout' => 'ev_timeout',
	'Class:UserRequest/Stimulus:ev_timeout+' => '',
	'Class:UserRequest/Stimulus:ev_resolve' => 'Marque como resolvido',
	'Class:UserRequest/Stimulus:ev_resolve+' => '',
	'Class:UserRequest/Stimulus:ev_close' => 'Fechado',
	'Class:UserRequest/Stimulus:ev_close+' => '',
	'Class:UserRequest/Stimulus:ev_freeze' => 'Marque como pendente',
	'Class:UserRequest/Stimulus:ev_freeze+' => '',
	'Menu:RequestManagement' => 'Gerenciamento Solicitação',
	'Menu:RequestManagement+' => 'Gerenciamento Solicitação',
	'Menu:UserRequest:Overview' => 'Visão geral',
	'Menu:UserRequest:Overview+' => 'Visão geral',
	'Menu:NewUserRequest' => 'Novo Chamado',
	'Menu:NewUserRequest+' => 'Novo Chamado',
	'Menu:SearchUserRequests' => 'Pesquisa para Chamados',
	'Menu:SearchUserRequests+' => 'Pesquisa para Chamados',
	'Menu:UserRequest:Shortcuts' => 'Atalhos',
	'Menu:UserRequest:Shortcuts+' => '',
	'Menu:UserRequest:MyRequests' => 'Chamados atribuídos para mim',
	'Menu:UserRequest:MyRequests+' => 'Chamados atribuídos para mim (como Agente)',
	'Menu:UserRequest:EscalatedRequests' => 'Chamados encaminhados',
	'Menu:UserRequest:EscalatedRequests+' => 'Chamados encaminhados',
	'Menu:UserRequest:OpenRequests' => 'Todos chamados abertos',
	'Menu:UserRequest:OpenRequests+' => 'Todos chamados abertos',
));
?>
