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

MetaModel::RegisterRelation("impacts", array("description"=>"Objects impacted by", "verb_down"=>"impacts", "verb_up"=>"depends on"));
MetaModel::RegisterRelation("depends on", array("description"=>"That impacts ", "verb_down"=>"depends on", "verb_up"=>"impacts"));

// Starting with iTop 1.2 you can restrict the list of organizations displayed in the drop-down list
// by specifying a query as shown below. Note that this is NOT a security settings, since the
// choice 'All Organizations' will always be available in the menu
ApplicationMenu::SetFavoriteSiloQuery('SELECT Organization');

?>
