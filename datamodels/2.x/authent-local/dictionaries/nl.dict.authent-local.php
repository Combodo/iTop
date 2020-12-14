<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2019 Combodo SARL
 * @license	http://opensource.org/licenses/AGPL-3.0
 * @author Jeffrey Bostoen - <jbostoen.itop@outlook.com> (2018 - 2020)
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
//
// Class: UserLocal
//
Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:UserLocal' => 'iTop-gebruiker',
	'Class:UserLocal+' => 'Gebruiker die aanmeldt met gegevens aangemaakt in het gebruikersbeheer van iTop',
	'Class:UserLocal/Attribute:password' => 'Wachtwoord',
	'Class:UserLocal/Attribute:password+' => 'Het wachtwoord waarmee de gebruiker zich aanmeldt bij iTop',

	'Class:UserLocal/Attribute:expiration' => 'Wachtwoord verloopt',
	'Class:UserLocal/Attribute:expiration+' => 'Of het wachtwoord al dan niet verlopen is (vereist een extensie vooraleer dit werkt)',
	'Class:UserLocal/Attribute:expiration/Value:can_expire' => 'Kan verlopen',
	'Class:UserLocal/Attribute:expiration/Value:can_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:never_expire' => 'Verloopt nooit',
	'Class:UserLocal/Attribute:expiration/Value:never_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:force_expire' => 'Moet veranderd worden',
	'Class:UserLocal/Attribute:expiration/Value:force_expire+' => '',
	'Class:UserLocal/Attribute:password_renewed_date' => 'Wachtwoord laatst aangepast',
	'Class:UserLocal/Attribute:password_renewed_date+' => 'Tijdstip waarop het wachtwoord het laatst aangepast werd.',

	'Error:UserLocalPasswordValidator:UserPasswordPolicyRegex:ValidationFailed' => 'Het wachtwoord bestaat uit minstens 8 tekens en bestaat uit een mix van minstens 1 hoofdletter, kleine letter, cijfer en speciaal teken.',

	'UserLocal:password:expiration' => 'De velden hieronder vereisen een extensie.'
));
