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
 * Localized data
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

// Dictionnay conventions
// Class:<class_name>
// Class:<class_name>+
// Class:<class_name>/Attribute:<attribute_code>
// Class:<class_name>/Attribute:<attribute_code>+
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>+
// Class:<class_name>/Stimulus:<stimulus_code>
// Class:<class_name>/Stimulus:<stimulus_code>+

//
// Class: UserLocal
//

Dict::Add('EN US', 'French', 'Français', array(
	'Class:UserLocal' => 'Utilisateur iTop',
	'Class:UserLocal+' => 'Utilisateur authentifié par iTop',
	'Class:UserLocal/Attribute:contactid' => 'Contact (personne)',
	'Class:UserLocal/Attribute:contactid+' => '',
	'Class:UserLocal/Attribute:last_name' => 'Nom',
	'Class:UserLocal/Attribute:last_name+' => '',
	'Class:UserLocal/Attribute:first_name' => 'Prénom',
	'Class:UserLocal/Attribute:first_name+' => '',
	'Class:UserLocal/Attribute:email' => 'Adresse email',
	'Class:UserLocal/Attribute:email+' => '',
	'Class:UserLocal/Attribute:login' => 'Login',
	'Class:UserLocal/Attribute:login+' => '',
	'Class:UserLocal/Attribute:password' => 'Mot de passe',
	'Class:UserLocal/Attribute:password+' => '',
	'Class:UserLocal/Attribute:language' => 'Language',
	'Class:UserLocal/Attribute:language+' => '',
	'Class:UserLocal/Attribute:language/Value:EN US' => 'Anglais',
	'Class:UserLocal/Attribute:language/Value:EN US+' => 'Anglais (Etats-unis)',
	'Class:UserLocal/Attribute:language/Value:FR FR' => 'Français',
	'Class:UserLocal/Attribute:language/Value:FR FR+' => 'Français (France)',
	'Class:UserLocal/Attribute:profile_list' => 'Profils',
	'Class:UserLocal/Attribute:profile_list+' => 'Rôles, ouvrants les droits d\'accès',
));

?>
