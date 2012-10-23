<?php
// Copyright (C) 2010-2012 Combodo SARL
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


MetaModel::RegisterRelation("impacts", array("description"=>"Objects impacted by", "verb_down"=>"impacts", "verb_up"=>"depends on"));
MetaModel::RegisterRelation("depends on", array("description"=>"That impacts ", "verb_down"=>"depends on", "verb_up"=>"impacts"));

// Starting with iTop 1.2 you can restrict the list of organizations displayed in the drop-down list
// by specifying a query as shown below. Note that this is NOT a security settings, since the
// choice 'All Organizations' will always be available in the menu
ApplicationMenu::SetFavoriteSiloQuery('SELECT Organization');

?>
